<?php
/**
 * Dependency Injection Container.
 *
 * @package ModernCore\Core
 */

declare(strict_types=1);

namespace ModernCore\Core;

/**
 * A lightweight Dependency Injection Container to manage theme services.
 */
class ServiceContainer {

	/**
	 * Array of registered services.
	 *
	 * @var array<string, object>
	 */
	private array $services = [];

	/**
	 * Register a new service in the container.
	 *
	 * @param string $name    The unique identifier for the service.
	 * @param object $service The instantiated service object.
	 * @return void
	 */
	public function register( string $name, object $service ): void {
		if ( ! isset( $this->services[ $name ] ) ) {
			$this->services[ $name ] = $service;
		}
	}

	/**
	 * Retrieve a registered service from the container.
	 *
	 * @param string $name The unique identifier for the service.
	 * @return object|null The service object if found, null otherwise.
	 */
	public function get( string $name ): ?object {
		return $this->services[ $name ] ?? null;
	}

	/**
	 * Get all registered services.
	 *
	 * @return array<string, object>
	 */
	public function get_services(): array {
		return $this->services;
	}
}