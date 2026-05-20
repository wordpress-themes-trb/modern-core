<?php

declare(strict_types=1);

namespace ModernCore\Security;

/**
 * Class NonceManager
 *
 * Centralized nonce creation and validation for CSRF protection.
 */
class NonceManager {
    /**
     * Prefix for all theme security actions.
     */
    private const ACTION_PREFIX = 'modern_core_nonce_';

    /**
     * Generates a secure, time-sensitive token.
     *
     * @param string $action The specific action being secured.
     * @return string The generated nonce.
     */
    public function createNonce(string $action): string {
        return wp_create_nonce(self::ACTION_PREFIX . $action);
    }

    /**
     * Verifies an incoming token against the action.
     *
     * @param string $nonce  The token provided by the client.
     * @param string $action The action to verify against.
     * @return bool True if valid, false otherwise.
     */
    public function verifyNonce(string $nonce, string $action): bool {
        return wp_verify_nonce($nonce, self::ACTION_PREFIX . $action) !== false;
    }
}