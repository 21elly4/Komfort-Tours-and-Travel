<?php

namespace Komfort\Config;

use Dotenv\Dotenv;

class App
{
    private static array $config = [];

    public static function load(): void
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/..');
        $dotenv->safeLoad();

        self::$config = [
            'app' => [
                'name' => $_ENV['APP_NAME'] ?? 'Komfort',
                'env' => $_ENV['APP_ENV'] ?? 'production',
                'debug' => filter_var($_ENV['APP_DEBUG'] ?? 'false', FILTER_VALIDATE_BOOLEAN),
                'url' => $_ENV['APP_URL'] ?? 'http://localhost',
                'key' => $_ENV['APP_KEY'] ?? '',
            ],
            'database' => [
                'host' => $_ENV['DB_HOST'] ?? 'localhost',
                'port' => $_ENV['DB_PORT'] ?? '3306',
                'name' => $_ENV['DB_NAME'] ?? 'tours_travel_db',
                'user' => $_ENV['DB_USER'] ?? 'root',
                'pass' => $_ENV['DB_PASS'] ?? '',
            ],
            'session' => [
                'lifetime' => (int)($_ENV['SESSION_LIFETIME'] ?? 120),
            ],
            'logging' => [
                'channel' => $_ENV['LOG_CHANNEL'] ?? 'stack',
                'level' => $_ENV['LOG_LEVEL'] ?? 'debug',
            ],
            'mpesa' => [
                'env' => $_ENV['MPESA_ENV'] ?? 'sandbox',
                'consumer_key' => $_ENV['MPESA_CONSUMER_KEY'] ?? '',
                'consumer_secret' => $_ENV['MPESA_CONSUMER_SECRET'] ?? '',
                'passkey' => $_ENV['MPESA_PASSKEY'] ?? '',
                'shortcode' => $_ENV['MPESA_SHORTCODE'] ?? '',
            ],
        ];
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        $keys = explode('.', $key);
        $value = self::$config;

        foreach ($keys as $k) {
            if (!isset($value[$k])) {
                return $default;
            }
            $value = $value[$k];
        }

        return $value;
    }

    public static function isDevelopment(): bool
    {
        return self::get('app.env') === 'development';
    }

    public static function isProduction(): bool
    {
        return self::get('app.env') === 'production';
    }

    public static function isDebug(): bool
    {
        return self::get('app.debug', false);
    }
}
