<?php

namespace Komfort\Config;

use Monolog\Logger as MonologLogger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Level;

class Logger
{
    private static ?MonologLogger $logger = null;

    public static function getLogger(): MonologLogger
    {
        if (self::$logger === null) {
            $logPath = __DIR__ . '/../storage/logs';
            
            if (!is_dir($logPath)) {
                mkdir($logPath, 0755, true);
            }

            self::$logger = new MonologLogger('komfort');
            
            $level = self::getMonologLevel(App::get('logging.level', 'debug'));
            
            self::$logger->pushHandler(
                new RotatingFileHandler(
                    $logPath . '/app.log',
                    30,
                    $level
                )
            );

            if (App::isDebug()) {
                self::$logger->pushHandler(
                    new StreamHandler('php://stdout', $level)
                );
            }
        }

        return self::$logger;
    }

    private static function getMonologLevel(string $level): Level
    {
        return match(strtolower($level)) {
            'debug' => Level::Debug,
            'info' => Level::Info,
            'notice' => Level::Notice,
            'warning' => Level::Warning,
            'error' => Level::Error,
            'critical' => Level::Critical,
            'alert' => Level::Alert,
            'emergency' => Level::Emergency,
            default => Level::Debug,
        };
    }

    public static function debug(string $message, array $context = []): void
    {
        self::getLogger()->debug($message, $context);
    }

    public static function info(string $message, array $context = []): void
    {
        self::getLogger()->info($message, $context);
    }

    public static function warning(string $message, array $context = []): void
    {
        self::getLogger()->warning($message, $context);
    }

    public static function error(string $message, array $context = []): void
    {
        self::getLogger()->error($message, $context);
    }

    public static function critical(string $message, array $context = []): void
    {
        self::getLogger()->critical($message, $context);
    }
}
