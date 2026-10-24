<?php
/**
 * Newsletter subscriptions.
 *
 * @package ModernCore\Store
 */

declare(strict_types=1);

namespace ModernCore\Store;

use ModernCore\Core\HooksManager;
use ModernCore\Core\ThemeSettings;
use ModernCore\Core\ThemeSetup;

/**
 * Handles the Newsletter block's signup form.
 *
 * Every signup is saved in WordPress (Tools > Subscribers, with CSV export),
 * so nothing is lost if an email provider is down or not configured. When
 * Mailchimp or Klaviyo keys are set in Appearance > Theme Settings, the
 * signup is also sent to that provider.
 */
class Newsletter {

	public const POST_TYPE = 'mc_subscriber';

	/**
	 * Attach hooks.
	 *
	 * @param HooksManager $hooks Hooks manager.
	 */
	public function register_hooks( HooksManager $hooks ): void {
		$hooks->add_action( 'init', $this, 'register_post_type' );
		$hooks->add_action( 'wp_ajax_mc_newsletter_submit_ajax', $this, 'submit' );
		$hooks->add_action( 'wp_ajax_nopriv_mc_newsletter_submit_ajax', $this, 'submit' );
		$hooks->add_action( 'admin_menu', $this, 'admin_menu' );
		$hooks->add_action( 'admin_post_mc_export_subscribers', $this, 'export_csv' );
		$hooks->add_filter( 'manage_' . self::POST_TYPE . '_posts_columns', $this, 'columns' );
		$hooks->add_action( 'manage_' . self::POST_TYPE . '_posts_custom_column', $this, 'column_content', 10, 2 );
	}

	/**
	 * Private post type that stores subscribers.
	 */
	public function register_post_type(): void {
		register_post_type(
			self::POST_TYPE,
			[
				'labels'          => [
					'name'          => __( 'Subscribers', 'modern-core' ),
					'singular_name' => __( 'Subscriber', 'modern-core' ),
					'search_items'  => __( 'Search subscribers', 'modern-core' ),
					'not_found'     => __( 'No subscribers yet.', 'modern-core' ),
				],
				'public'          => false,
				'show_ui'         => true,
				'show_in_menu'    => 'tools.php',
				'show_in_rest'    => false,
				'supports'        => [ 'title' ],
				'capability_type' => 'post',
				'capabilities'    => [ 'create_posts' => 'do_not_allow' ],
				'map_meta_cap'    => true,
			]
		);
	}

	/**
	 * AJAX: subscribe an email address.
	 */
	public function submit(): void {
		check_ajax_referer( ThemeSetup::AJAX_NONCE, 'nonce' );

		$email = isset( $_POST['mc_email'] ) ? sanitize_email( wp_unslash( $_POST['mc_email'] ) ) : '';
		$name  = isset( $_POST['mc_fname'] ) ? sanitize_text_field( wp_unslash( $_POST['mc_fname'] ) ) : '';

		// Honeypot: real visitors never fill this hidden field.
		if ( ! empty( $_POST['mc_website'] ) ) {
			wp_send_json_success();
		}

		if ( ! is_email( $email ) ) {
			wp_send_json_error( [ 'message' => __( 'Please enter a valid email address.', 'modern-core' ) ] );
		}

		if ( $this->is_rate_limited() ) {
			wp_send_json_error( [ 'message' => __( 'Too many attempts. Please try again in a few minutes.', 'modern-core' ) ] );
		}

		$provider = ThemeSettings::get( 'newsletter_provider' );
		$this->save_locally( $email, $name, $provider );

		$result = true;
		if ( 'mailchimp' === $provider ) {
			$result = $this->send_to_mailchimp( $email, $name );
		} elseif ( 'klaviyo' === $provider ) {
			$result = $this->send_to_klaviyo( $email, $name );
		}

		if ( is_wp_error( $result ) ) {
			// The signup is already saved locally, so the visitor still sees success.
			$this->log( $email, $result->get_error_message() );
		}

		do_action( 'modern_core_newsletter_subscribed', $email, $name, $provider );
		wp_send_json_success();
	}

	/**
	 * Max five signups per IP address every ten minutes.
	 */
	private function is_rate_limited(): bool {
		$ip  = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
		$key = 'mc_nl_rate_' . md5( $ip );
		$hit = (int) get_transient( $key );
		if ( $hit >= 5 ) {
			return true;
		}
		set_transient( $key, $hit + 1, 10 * MINUTE_IN_SECONDS );
		return false;
	}

	/**
	 * Store or update the subscriber record.
	 *
	 * @param string $email    Email.
	 * @param string $name     First name.
	 * @param string $provider Provider at signup time.
	 */
	private function save_locally( string $email, string $name, string $provider ): void {
		$existing = get_posts(
			[
				'post_type'      => self::POST_TYPE,
				'post_status'    => 'private',
				'title'          => $email,
				'posts_per_page' => 1,
				'fields'         => 'ids',
			]
		);
		if ( $existing ) {
			if ( '' !== $name ) {
				update_post_meta( (int) $existing[0], '_mc_name', $name );
			}
			return;
		}

		$id = wp_insert_post(
			[
				'post_type'   => self::POST_TYPE,
				'post_status' => 'private',
				'post_title'  => $email,
			]
		);
		if ( $id && ! is_wp_error( $id ) ) {
			update_post_meta( $id, '_mc_name', $name );
			update_post_meta( $id, '_mc_provider', $provider );
			$source = wp_get_referer();
			update_post_meta( $id, '_mc_source', $source ? esc_url_raw( $source ) : '' );
		}
	}

	/**
	 * Mailchimp Marketing API: add or update a list member.
	 *
	 * @param string $email Email.
	 * @param string $name  First name.
	 * @return true|\WP_Error
	 */
	private function send_to_mailchimp( string $email, string $name ) {
		$key  = ThemeSettings::get( 'mailchimp_api_key' );
		$list = ThemeSettings::get( 'mailchimp_list_id' );
		if ( '' === $key || '' === $list || false === strpos( $key, '-' ) ) {
			return new \WP_Error( 'mc_config', 'Mailchimp API key or audience ID missing.' );
		}

		$dc       = substr( $key, strrpos( $key, '-' ) + 1 );
		$url      = sprintf( 'https://%s.api.mailchimp.com/3.0/lists/%s/members/%s', rawurlencode( $dc ), rawurlencode( $list ), md5( strtolower( $email ) ) );
		$response = wp_remote_request(
			$url,
			[
				'method'  => 'PUT',
				'timeout' => 10,
				'headers' => [
					'Authorization' => 'Basic ' . base64_encode( 'modern-core:' . $key ), // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode
					'Content-Type'  => 'application/json',
				],
				'body'    => wp_json_encode(
					[
						'email_address' => $email,
						'status_if_new' => ThemeSettings::get( 'newsletter_double_optin' ) ? 'pending' : 'subscribed',
						'merge_fields'  => '' !== $name ? [ 'FNAME' => $name ] : new \stdClass(),
					]
				),
			]
		);
		return $this->check_response( $response, 'Mailchimp' );
	}

	/**
	 * Klaviyo API: subscribe a profile to a list.
	 *
	 * @param string $email Email.
	 * @param string $name  First name.
	 * @return true|\WP_Error
	 */
	private function send_to_klaviyo( string $email, string $name ) {
		$key  = ThemeSettings::get( 'klaviyo_api_key' );
		$list = ThemeSettings::get( 'klaviyo_list_id' );
		if ( '' === $key || '' === $list ) {
			return new \WP_Error( 'mc_config', 'Klaviyo private API key or list ID missing.' );
		}

		$attributes = [
			'email'         => $email,
			'subscriptions' => [ 'email' => [ 'marketing' => [ 'consent' => 'SUBSCRIBED' ] ] ],
		];
		$response   = wp_remote_post(
			'https://a.klaviyo.com/api/profile-subscription-bulk-create-jobs/',
			[
				'timeout' => 10,
				'headers' => [
					'Authorization' => 'Klaviyo-API-Key ' . $key,
					'revision'      => '2024-10-15',
					'Content-Type'  => 'application/json',
					'Accept'        => 'application/json',
				],
				'body'    => wp_json_encode(
					[
						'data' => [
							'type'          => 'profile-subscription-bulk-create-job',
							'attributes'    => [
								'profiles' => [
									'data' => [
										[
											'type'       => 'profile',
											'attributes' => $attributes,
										],
									],
								],
							],
							'relationships' => [
								'list' => [
									'data' => [
										'type' => 'list',
										'id'   => $list,
									],
								],
							],
						],
					]
				),
			]
		);
		$result     = $this->check_response( $response, 'Klaviyo' );

		// Klaviyo's subscription endpoint does not take names, so set it on the profile separately.
		if ( true === $result && '' !== $name ) {
			wp_remote_post(
				'https://a.klaviyo.com/api/profile-import/',
				[
					'timeout' => 10,
					'headers' => [
						'Authorization' => 'Klaviyo-API-Key ' . $key,
						'revision'      => '2024-10-15',
						'Content-Type'  => 'application/json',
					],
					'body'    => wp_json_encode(
						[
							'data' => [
								'type'       => 'profile',
								'attributes' => [
									'email'      => $email,
									'first_name' => $name,
								],
							],
						]
					),
				]
			);
		}
		return $result;
	}

	/**
	 * Turn an HTTP response into true or a WP_Error.
	 *
	 * @param array<string, mixed>|\WP_Error $response Response.
	 * @param string                         $service  Service name for the log.
	 * @return true|\WP_Error
	 */
	private function check_response( $response, string $service ) {
		if ( is_wp_error( $response ) ) {
			return $response;
		}
		$code = (int) wp_remote_retrieve_response_code( $response );
		if ( $code >= 200 && $code < 300 ) {
			return true;
		}
		return new \WP_Error( 'mc_http', sprintf( '%s returned HTTP %d: %s', $service, $code, substr( (string) wp_remote_retrieve_body( $response ), 0, 300 ) ) );
	}

	/**
	 * Keep the last 50 provider errors for the settings screen.
	 *
	 * @param string $email   Email.
	 * @param string $message Error.
	 */
	private function log( string $email, string $message ): void {
		$log   = (array) get_option( 'mc_newsletter_errors', [] );
		$log[] = [
			'time'    => time(),
			'email'   => $email,
			'message' => $message,
		];
		update_option( 'mc_newsletter_errors', array_slice( $log, -50 ), false );
	}

	/**
	 * Admin list columns.
	 *
	 * @param array<string, string> $columns Columns.
	 * @return array<string, string>
	 */
	public function columns( array $columns ): array {
		return [
			'cb'          => $columns['cb'] ?? '',
			'title'       => __( 'Email', 'modern-core' ),
			'mc_name'     => __( 'Name', 'modern-core' ),
			'mc_provider' => __( 'Sent to', 'modern-core' ),
			'date'        => __( 'Signed up', 'modern-core' ),
		];
	}

	/**
	 * Admin list column values.
	 *
	 * @param string $column  Column key.
	 * @param int    $post_id Post ID.
	 */
	public function column_content( string $column, int $post_id ): void {
		if ( 'mc_name' === $column ) {
			echo esc_html( (string) get_post_meta( $post_id, '_mc_name', true ) );
		} elseif ( 'mc_provider' === $column ) {
			$provider = (string) get_post_meta( $post_id, '_mc_provider', true );
			echo esc_html( 'native' === $provider || '' === $provider ? __( 'WordPress only', 'modern-core' ) : ucfirst( $provider ) );
		}
	}

	/**
	 * Add the export link under Tools.
	 */
	public function admin_menu(): void {
		add_submenu_page(
			'tools.php',
			__( 'Export subscribers', 'modern-core' ),
			__( 'Export subscribers', 'modern-core' ),
			'manage_options',
			'mc-export-subscribers',
			[ $this, 'render_export_page' ]
		);
	}

	/**
	 * Export screen.
	 */
	public function render_export_page(): void {
		$count = (int) wp_count_posts( self::POST_TYPE )->private;
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Export subscribers', 'modern-core' ); ?></h1>
			<p>
				<?php
				/* translators: %d: number of subscribers */
				echo esc_html( sprintf( _n( '%d subscriber saved.', '%d subscribers saved.', $count, 'modern-core' ), $count ) );
				?>
			</p>
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<input type="hidden" name="action" value="mc_export_subscribers">
				<?php wp_nonce_field( 'mc_export_subscribers' ); ?>
				<?php submit_button( __( 'Download CSV', 'modern-core' ), 'primary', 'submit', false ); ?>
			</form>
		</div>
		<?php
	}

	/**
	 * Stream subscribers as CSV.
	 */
	public function export_csv(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Sorry, you are not allowed to do that.', 'modern-core' ), 403 );
		}
		check_admin_referer( 'mc_export_subscribers' );

		nocache_headers();
		header( 'Content-Type: text/csv; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename=subscribers-' . gmdate( 'Y-m-d' ) . '.csv' );

		$out = fopen( 'php://output', 'w' ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fopen
		fputcsv( $out, [ 'email', 'name', 'sent_to', 'signed_up', 'source' ] );

		$ids = get_posts(
			[
				'post_type'      => self::POST_TYPE,
				'post_status'    => 'private',
				'posts_per_page' => -1,
				'fields'         => 'ids',
				'orderby'        => 'date',
				'order'          => 'ASC',
			]
		);
		foreach ( $ids as $id ) {
			// Prefix values that start with a formula character so spreadsheets don't execute them.
			$row = array_map(
				static fn( $v ) => preg_match( '/^[=+\-@]/', (string) $v ) ? "'" . $v : (string) $v,
				[
					get_the_title( $id ),
					get_post_meta( $id, '_mc_name', true ),
					get_post_meta( $id, '_mc_provider', true ),
					get_the_date( 'Y-m-d H:i', $id ),
					get_post_meta( $id, '_mc_source', true ),
				]
			);
			fputcsv( $out, $row );
		}
		fclose( $out ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fclose
		exit;
	}
}
