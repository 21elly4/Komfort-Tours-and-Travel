<?php

namespace Komfort\App\Middleware;

use Komfort\Config\Logger as Log;
use Komfort\App\Models\User;

class Auth
{
    private static ?array $currentUser = null;

    public static function init(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function check(): bool
    {
        self::init();
        return isset($_SESSION['user_id']);
    }

    public static function user(): ?array
    {
        if (self::$currentUser === null) {
            self::init();
            
            if (!isset($_SESSION['user_id'])) {
                return null;
            }

            $userModel = new User();
            $user = $userModel->find($_SESSION['user_id']);
            
            if ($user && $user['is_active']) {
                self::$currentUser = $user;
            } else {
                self::logout();
            }
        }

        return self::$currentUser;
    }

    public static function id(): ?int
    {
        return self::user()['id'] ?? null;
    }

    public static function isAdmin(): bool
    {
        $user = self::user();
        return $user && $user['role'] === 'admin';
    }

    public static function isTraveler(): bool
    {
        $user = self::user();
        return $user && $user['role'] === 'traveler';
    }

    public static function attempt(array $credentials): bool
    {
        self::init();
        
        $userModel = new User();
        $user = $userModel->findByEmail($credentials['email']);

        if ($user && $userModel->verifyPassword($credentials['password'], $user['password'])) {
            if (!$user['is_active']) {
                Log::warning('Login attempt for inactive user', ['email' => $credentials['email']]);
                return false;
            }

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['last_activity'] = time();
            
            self::$currentUser = $user;
            
            Log::info('User logged in successfully', ['user_id' => $user['id'], 'email' => $user['email']]);
            return true;
        }

        Log::warning('Failed login attempt', ['email' => $credentials['email']]);
        return false;
    }

    public static function login(int $userId): void
    {
        self::init();
        
        $userModel = new User();
        $user = $userModel->find($userId);

        if ($user && $user['is_active']) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['last_activity'] = time();
            
            self::$currentUser = $user;
            
            Log::info('User logged in programmatically', ['user_id' => $user['id']]);
        }
    }

    public static function logout(): void
    {
        self::init();
        
        $userId = $_SESSION['user_id'] ?? null;
        
        session_unset();
        session_destroy();
        
        self::$currentUser = null;
        
        if ($userId) {
            Log::info('User logged out', ['user_id' => $userId]);
        }
    }

    public static function requireAuth(): void
    {
        if (!self::check()) {
            self::redirectToLogin();
        }
    }

    public static function requireAdmin(): void
    {
        self::requireAuth();
        
        if (!self::isAdmin()) {
            Log::warning('Unauthorized admin access attempt', ['user_id' => self::id()]);
            http_response_code(403);
            die('Access denied. Admin privileges required.');
        }
    }

    public static function requireTraveler(): void
    {
        self::requireAuth();
        
        if (!self::isTraveler()) {
            Log::warning('Unauthorized traveler access attempt', ['user_id' => self::id()]);
            http_response_code(403);
            die('Access denied. Traveler account required.');
        }
    }

    private static function redirectToLogin(): void
    {
        header('Location: /login');
        exit;
    }

    public static function checkSessionTimeout(): void
    {
        self::init();
        
        if (isset($_SESSION['last_activity'])) {
            $timeout = 30 * 60; // 30 minutes
            
            if (time() - $_SESSION['last_activity'] > $timeout) {
                self::logout();
                header('Location: /login?timeout=1');
                exit;
            }
            
            $_SESSION['last_activity'] = time();
        }
    }
}
