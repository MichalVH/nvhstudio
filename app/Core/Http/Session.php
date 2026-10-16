<?php

declare(strict_types=1);

namespace App\Core\Http;

/**
 * Gestionnaire d'infrastructure étanche encapsulant la superglobale $_SESSION.
 */
final class Session
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            
            ini_set('session.cookie_httponly', '1');
            ini_set('session.cookie_samesite', 'Strict');
            ini_set('session.use_only_cookies', '1');

            // CORRECTIF SÉCURITÉ HTTPS EXTENSIBLE (Reverse Proxies & Proxies Cloud)
            $isHttps = (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] === 'on' || $_SERVER['HTTPS'] === 1))
                || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');

            if ($isHttps) {
                ini_set('session.cookie_secure', '1');
            }

            session_start();

            // CORRECTIF RADICAL ANTI-FIXATION
            $created = $_SESSION['CREATED'] ?? null;
            if (!is_int($created)) {
                $_SESSION['CREATED'] = time();
                session_regenerate_id(true);
            } elseif (time() - $created > 300) {
                $_SESSION['CREATED'] = time();
                session_regenerate_id(true);
            }
        }
    }

    public function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }

    public function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    public function remove(string $key): void
    {
        if ($this->has($key)) {
            unset($_SESSION[$key]);
        }
    }

    public function setFlash(string $type, string $message): void
    {
        /** @var array<string, string> $flash */
        $flash = $_SESSION['_flash'] ?? [];
        $flash[$type] = $message;
        $_SESSION['_flash'] = $flash;
    }

    public function hasFlash(string $type): bool
    {
        /** @var array<string, string> $flash */
        $flash = $_SESSION['_flash'] ?? [];
        return isset($flash[$type]) && $flash[$type] !== '';
    }

    public function getFlash(string $type): ?string
    {
        if (!$this->hasFlash($type)) {
            return null;
        }

        /** @var array<string, string> $flash */
        $flash = $_SESSION['_flash'] ?? [];
        $message = $flash[$type] ?? null;
        
        unset($flash[$type]);
        $_SESSION['_flash'] = $flash;

        return $message;
    }
}
