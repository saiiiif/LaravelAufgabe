<?php

namespace App\Core;

use PDO;
use PDOException;
use RuntimeException;

class Database
{
    private PDO $connection;

    public function __construct(string $path)
    {
        $this->connection = $this->boot($path);
        $this->migrate();
    }

    public function connection(): PDO
    {
        return $this->connection;
    }

    private function boot(string $path): PDO
    {
        $directory = dirname($path);

        if (!is_dir($directory) && !mkdir($directory, 0777, true) && !is_dir($directory)) {
            throw new RuntimeException(sprintf('Unable to create database directory: %s', $directory));
        }

        try {
            $pdo = new PDO('sqlite:' . $path);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $pdo->exec('PRAGMA foreign_keys = ON');
        } catch (PDOException $exception) {
            throw new RuntimeException('Could not connect to the SQLite database.', 0, $exception);
        }

        return $pdo;
    }

    private function migrate(): void
    {
        $this->connection->exec(<<<'SQL'
            CREATE TABLE IF NOT EXISTS products (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                sku TEXT NOT NULL,
                quantity INTEGER NOT NULL DEFAULT 0,
                unit_price REAL NOT NULL DEFAULT 0,
                qr_token TEXT NOT NULL,
                created_at TEXT NOT NULL,
                updated_at TEXT NOT NULL
            )
        SQL);

        $this->connection->exec('CREATE UNIQUE INDEX IF NOT EXISTS products_sku_unique ON products (sku)');
        $this->connection->exec('CREATE UNIQUE INDEX IF NOT EXISTS products_qr_token_unique ON products (qr_token)');
    }
}
