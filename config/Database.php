<?php

namespace Komfort\Config;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $connection = null;

    public static function getConnection(): PDO
    {
        if (self::$connection === null) {
            try {
                $dsn = sprintf(
                    'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
                    App::get('database.host'),
                    App::get('database.port'),
                    App::get('database.name')
                );

                self::$connection = new PDO(
                    $dsn,
                    App::get('database.user'),
                    App::get('database.pass'),
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false,
                        PDO::ATTR_PERSISTENT => false,
                    ]
                );
            } catch (PDOException $e) {
                if (App::isDebug()) {
                    throw new PDOException('Database connection failed: ' . $e->getMessage());
                }
                throw new PDOException('Database connection failed. Please check your configuration.');
            }
        }

        return self::$connection;
    }

    public static function close(): void
    {
        self::$connection = null;
    }

    public static function beginTransaction(): void
    {
        self::getConnection()->beginTransaction();
    }

    public static function commit(): void
    {
        self::getConnection()->commit();
    }

    public static function rollback(): void
    {
        self::getConnection()->rollBack();
    }
}
