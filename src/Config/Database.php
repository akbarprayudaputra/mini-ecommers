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

            try {
                self::$pdo = new \PDO(
                    dsn: $config["database"][$env]["url"],
                    username: $config["database"][$env]["username"],
                    password: $config["database"][$env]["password"],
                );
            } catch (\PDOException $e) {
                throw new \Exception("Failed to connect database: " . $e->getMessage(), 500);
            }
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
