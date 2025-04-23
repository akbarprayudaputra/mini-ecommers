<?php

namespace MiniECommers\Backend\Config;

class Database
{
    private static ?\PDO $pdo = null;

    public static function getConnection(string $env = "test"): \PDO
    {
        if (self::$pdo === null) {
            require_once __DIR__ . "/../../config/database.php";
            $config = getDatabaseConfig();

            self::$pdo = new \PDO(
                dsn: $config["database"][$env]["url"],
                username: $config["database"][$env]["username"],
                password: $config["database"][$env]["password"],
            );
        }

        return self::$pdo;
    }

    public static function beginTransaction(): void
    {
        self::$pdo->beginTransaction();
    }

    public static function commitTransaction(): void
    {
        self::$pdo->commit();
    }

    public static function rollbackTransaction(): void
    {
        self::$pdo->rollBack();
    }
}
