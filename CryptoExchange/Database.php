<?php

namespace Reelz222z\CryptoExchange;

use PDO;

class Database
{
    private static ?self $instance = null;
    private PDO $pdo;

    private function __construct()
    {
        $dbPath = __DIR__ . '/../crypto_exchange.sqlite';
        echo "Connecting to database at: " . $dbPath . "\n"; // Debugging statement

        $this->pdo = new PDO(
            'sqlite:' . $dbPath
        );
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection(): PDO
    {
        return $this->pdo;
    }
}
