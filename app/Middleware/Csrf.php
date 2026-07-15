<?php

namespace Komfort\App\Middleware;

use Komfort\Config\Logger as Log;

class Csrf
{
    public static function init(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    }

    public static function generateToken(): string
    {
        self::init();
        return $_SESSION['csrf_token'];
    }

    public static function validateToken(string $token): bool
    {
        self::init();
        
        if (empty($_SESSION['csrf_token'])) {
            Log::warning('CSRF validation failed: no session token');
            return false;
        }
        
        $isValid = hash_equals($_SESSION['csrf_token'], $token);
        
        if (!$isValid) {
            Log::warning('CSRF validation failed: token mismatch', [
                'session_token' => $_SESSION['csrf_token'],
                'provided_token' => $token
            ]);
        }
        
        return $isValid;
    }

    public static function validateRequest(): bool
    {
        $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        
        if (empty($token)) {
            Log::warning('CSRF validation failed: no token provided');
            return false;
        }
        
        return self::validateToken($token);
    }

    public static function regenerateToken(): string
    {
        self::init();
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        return $_SESSION['csrf_token'];
    }

    public static function getField(): string
    {
        return '<input type="hidden" name="csrf_token" value="' . self::generateToken() . '">';
    }

    public static function getMeta(): string
    {
        return '<meta name="csrf-token" content="' . self::generateToken() . '">';
    }
}
