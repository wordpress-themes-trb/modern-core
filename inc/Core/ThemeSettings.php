<?php
/**
 * Theme Settings screen (Appearance > Theme Settings).
 *
 * @package ModernCore\Core
 */

declare(strict_types=1);

namespace ModernCore\Core;

/**
 * Stores store-wide settings that don't belong to a single block:
 * newsletter provider keys and back-in-stock email text.
 */
class ThemeSettings {

	public const OPTION = 'modern_core_settings';

	/**
	 * Field schema: key => [ label, type, default, help, options ].
	 *
	 * @return array<string, array<string, mixed>>
	 */
	public static function fields(): array {
		return [
			'newsletter_provider'     => [
				'section' => 'newsletter',
				'label'   => __( 'Send signups to', 'modern-core' ),
				'type'    => 'select',
				'default' => 'native',
				'options' => [
					'native'    => __( 'WordPress only (Tools > Subscribers)', 'modern-core' ),
					'mailchimp' => 'Mailchimp',
					'klaviyo'   => 'Klaviyo',
				],
				'help'    => __( 'Signups are always saved in WordPress as well, so none are lost if the provider is unavailable.', 'modern-core' ),
			],
			'newsletter_double_optin' => [
				'section' => 'newsletter',
				'label'   => __( 'Mailchimp double opt-in', 'modern-core' ),
				'type'    => 'checkbox',
				'default' => true,
				'help'    => __( 'Mailchimp sends a confirmation email before adding the person to your audience.', 'modern-core' ),
			],
			'mailchimp_api_key'       => [
				'section' => 'newsletter',
				'label'   => __( 'Mailchimp API key', 'modern-core' ),
				'type'    => 'password',
				'default' => '',
				'help'    => __( 'Mailchimp > Profile > Extras > API keys. Ends in something like -us21.', 'modern-core' ),
			],
			'mailchimp_list_id'       => [
				'section' => 'newsletter',
				'label'   => __( 'Mailchimp audience ID', 'modern-core' ),
				'type'    => 'text',
				'default' => '',
				'help'    => __( 'Audience > Settings > Audience name and defaults.', 'modern-core' ),
			],
			'klaviyo_api_key'         => [
				'section' => 'newsletter',
				'label'   => __( 'Klaviyo private API key', 'modern-core' ),
				'type'    => 'password',
				'default' => '',
				'help'    => __( 'Needs the "Subscriptions" and "Profiles" write scopes.', 'modern-core' ),
			],
			'klaviyo_list_id'         => [
				'section' => 'newsletter',
				'label'   => __( 'Klaviyo list ID', 'modern-core' ),
				'type'    => 'text',
				'default' => '',
			],
			'bis_subject'             => [
				'section' => 'back_in_stock',
				'label'   => __( 'Email subject', 'modern-core' ),
				'type'    => 'text',
				'default' => __( 'Good news: {product_name} is back in stock', 'modern-core' ),
			],
			'bis_body'                => [
				'section' => 'back_in_stock',
				'label'   => __( 'Email message', 'modern-core' ),
				'type'    => 'textarea',
				'default' => __( "Hi {customer_name},\n\n{product_name} is available again. Stock is limited, so grab yours while you can:\n\n{product_url}\n\n{site_name}", 'modern-core' ),
				'help'    => __( 'Placeholders: {product_name}, {product_url}, {customer_name}, {site_name}.', 'modern-core' ),
			],
		];
	}

	/**
	 * Read a setting, falling back to its default.
	 *
	 * @param string $key Field key.
	 * @return mixed
	 */
	public static function get( string $key ) {
		$fields = self::fields();
		$values = (array) get_option( self::OPTION, [] );
		$value  = $values[ $key ] ?? ( $fields[ $key ]['default'] ?? '' );
		return 'checkbox' === ( $fields[ $key ]['type'] ?? '' ) ? (bool) $value : (string) $value;
	}

	/**
	 * Attach hooks.
	 *
	 * @param HooksManager $hooks Hooks manager.
	 */
	public function register_hooks( HooksManager $hooks ): void {
		$hooks->add_action( 'admin_menu', $this, 'add_page' );
		$hooks->add_action( 'admin_init', $this, 'register_setting' );
	}

	/**
	 * Menu entry.
	 */
	public function add_page(): void {
		add_theme_page(
			__( 'Theme Settings', 'modern-core' ),
			__( 'Theme Settings', 'modern-core' ),
			'manage_options',
			'modern-core-settings',
			[ $this, 'render' ]
		);
	}

	/**
	 * Settings API registration.
	 */
	public function register_setting(): void {
		register_setting(
			'modern_core_settings',
			self::OPTION,
			[
				'type'              => 'array',
				'sanitize_callback' => [ $this, 'sanitize' ],
				'default'           => [],
			]
		);
	}

	/**
	 * Sanitize submitted values against the schema.
	 *
	 * @param mixed $input Submitted values.
	 * @return array<string, mixed>
	 */
	public function sanitize( $input ): array {
		$input = is_array( $input ) ? $input : [];
		$clean = [];
		foreach ( self::fields() as $key => $field ) {
			$value = $input[ $key ] ?? '';
			switch ( $field['type'] ) {
				case 'checkbox':
					$clean[ $key ] = ! empty( $value );
					break;
				case 'select':
					$clean[ $key ] = array_key_exists( $value, $field['options'] ) ? $value : $field['default'];
					break;
				case 'textarea':
					$clean[ $key ] = sanitize_textarea_field( (string) $value );
					break;
				default:
					$clean[ $key ] = sanitize_text_field( (string) $value );
			}
		}
		return $clean;
	}

	/**
	 * Settings screen.
	 */
	public function render(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		$sections = [
			'newsletter'    => __( 'Newsletter', 'modern-core' ),
			'back_in_stock' => __( 'Back-in-stock emails', 'modern-core' ),
		];
		$errors   = (array) get_option( 'mc_newsletter_errors', [] );
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Theme Settings', 'modern-core' ); ?></h1>
			<form method="post" action="options.php">
				<?php settings_fields( 'modern_core_settings' ); ?>
				<?php foreach ( $sections as $section => $title ) : ?>
					<h2><?php echo esc_html( $title ); ?></h2>
					<table class="form-table" role="presentation">
						<?php
						foreach ( self::fields() as $key => $field ) :
							if ( $field['section'] !== $section ) {
								continue;
							}
							$id    = 'mc-setting-' . $key;
							$name  = self::OPTION . '[' . $key . ']';
							$value = self::get( $key );
							?>
							<tr>
								<th scope="row"><label for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $field['label'] ); ?></label></th>
								<td>
									<?php if ( 'select' === $field['type'] ) : ?>
										<select id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $name ); ?>">
											<?php foreach ( $field['options'] as $opt => $label ) : ?>
												<option value="<?php echo esc_attr( $opt ); ?>" <?php selected( $value, $opt ); ?>><?php echo esc_html( $label ); ?></option>
											<?php endforeach; ?>
										</select>
									<?php elseif ( 'checkbox' === $field['type'] ) : ?>
										<input type="checkbox" id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $name ); ?>" value="1" <?php checked( $value ); ?>>
									<?php elseif ( 'textarea' === $field['type'] ) : ?>
										<textarea id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $name ); ?>" rows="7" class="large-text"><?php echo esc_textarea( (string) $value ); ?></textarea>
									<?php else : ?>
										<input type="<?php echo esc_attr( $field['type'] ); ?>" id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( (string) $value ); ?>" class="regular-text" autocomplete="off">
									<?php endif; ?>
									<?php if ( ! empty( $field['help'] ) ) : ?>
										<p class="description"><?php echo esc_html( $field['help'] ); ?></p>
									<?php endif; ?>
								</td>
							</tr>
						<?php endforeach; ?>
					</table>
				<?php endforeach; ?>
				<?php submit_button(); ?>
			</form>

			<?php if ( $errors ) : ?>
				<h2><?php esc_html_e( 'Recent newsletter provider errors', 'modern-core' ); ?></h2>
				<p><?php esc_html_e( 'These signups were saved in WordPress but the provider rejected them.', 'modern-core' ); ?></p>
				<table class="widefat striped">
					<thead><tr><th><?php esc_html_e( 'When', 'modern-core' ); ?></th><th><?php esc_html_e( 'Email', 'modern-core' ); ?></th><th><?php esc_html_e( 'Error', 'modern-core' ); ?></th></tr></thead>
					<tbody>
						<?php foreach ( array_reverse( array_slice( $errors, -10 ) ) as $row ) : ?>
							<tr>
								<td><?php echo esc_html( wp_date( 'Y-m-d H:i', (int) $row['time'] ) ); ?></td>
								<td><?php echo esc_html( $row['email'] ); ?></td>
								<td><code><?php echo esc_html( $row['message'] ); ?></code></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			<?php endif; ?>
		</div>
		<?php
	}
}
