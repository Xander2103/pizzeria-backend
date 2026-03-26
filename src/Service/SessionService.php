<?php
// SessionService.php
// Beheert sessie + auto-logout na 1 uur inactiviteit

declare(strict_types=1);

namespace App\Service;

final class SessionService
{
    private const TIMEOUT_SECONDS = 3600; // 1 uur

    public function ensureStarted(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        // auto-logout na 1 uur inactiviteit
        $now = time();
        $last = (int)($_SESSION['_last_activity'] ?? 0);

        if ($last !== 0 && ($now - $last) > self::TIMEOUT_SECONDS) {
            $_SESSION = [];

            // sessie vernietigen
            if (session_status() === PHP_SESSION_ACTIVE) {
                session_destroy();
            }

            // nieuwe sessie starten zodat app verder kan
            session_start();
        }

        $_SESSION['_last_activity'] = $now;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $this->ensureStarted();
        return $_SESSION[$key] ?? $default;
    }

    public function set(string $key, mixed $value): void
    {
        $this->ensureStarted();
        $_SESSION[$key] = $value;
    }

    public function remove(string $key): void
    {
        $this->ensureStarted();
        unset($_SESSION[$key]);
    }

    public function has(string $key): bool
    {
        $this->ensureStarted();
        return array_key_exists($key, $_SESSION);
    }
}