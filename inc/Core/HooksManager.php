<?php
/**
 * Hooks Manager for WordPress actions and filters.
 *
 * @package ModernCore\Core
 */

declare(strict_types=1);

namespace ModernCore\Core;

/**
 * Manages all WordPress actions and filters systematically.
 */
class HooksManager {

	/**
	 * The collection of actions and filters registered with WordPress.
	 *
	 * @var array<int, array<string, mixed>>
	 */
	private array $hooks = [];

	/**
	 * Add an action to the collection to be registered with WordPress.
	 *
	 * @param string $hook          The name of the WordPress action.
	 * @param object $component     A reference to the instance of the object on which the action is defined.
	 * @param string $callback      The name of the function definition on the $component.
	 * @param int    $priority      The priority at which the function should be fired.
	 * @param int    $accepted_args The number of arguments that should be passed to the $callback.
	 * @return void
	 */
	public function add_action( string $hook, object $component, string $callback, int $priority = 10, int $accepted_args = 1 ): void {
		$this->hooks[] = [
			'type'          => 'action',
			'hook'          => $hook,
			'component'     => $component,
			'callback'      => $callback,
			'priority'      => $priority,
			'accepted_args' => $accepted_args,
		];
	}

	/**
	 * Add a filter to the collection to be registered with WordPress.
	 *
	 * @param string $hook          The name of the WordPress filter.
	 * @param object $component     A reference to the instance of the object on which the filter is defined.
	 * @param string $callback      The name of the function definition on the $component.
	 * @param int    $priority      The priority at which the function should be fired.
	 * @param int    $accepted_args The number of arguments that should be passed to the $callback.
	 * @return void
	 */
	public function add_filter( string $hook, object $component, string $callback, int $priority = 10, int $accepted_args = 1 ): void {
		$this->hooks[] = [
			'type'          => 'filter',
			'hook'          => $hook,
			'component'     => $component,
			'callback'      => $callback,
			'priority'      => $priority,
			'accepted_args' => $accepted_args,
		];
	}

	/**
	 * Register the filters and actions with WordPress.
	 *
	 * @return void
	 */
	public function register(): void {
		foreach ( $this->hooks as $hook ) {
			$function = 'add_' . $hook['type'];
			$function(
				$hook['hook'],
				[ $hook['component'], $hook['callback'] ],
				$hook['priority'],
				$hook['accepted_args']
			);
		}
	}
}