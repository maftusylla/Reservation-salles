<?php

declare(strict_types=1);

namespace App\Core;

final class SessionManager
{
    private static ?SessionManager $instance = null;

    private function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function getInstance(): SessionManager
    {
        if (self::$instance === null) {
            self::$instance = new SessionManager();
        }

        return self::$instance;
    }

    public static function getData(string $key): mixed
    {
        return $_SESSION[$key] ?? null;
    }

    public static function saveData(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public static function hasKey(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    public static function currentUser(): string
    {
        return $_SESSION["userConnected"]["nom"];
    }

    public static function isConnected(): bool
    {
        return isset($_SESSION["userConnected"]);
    }

    public static function removeData(string $key): void
    {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }
}