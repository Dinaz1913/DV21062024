<?php

namespace Reelz222z\CryptoExchange;

use PDO;

class Wallet
{
    private int $userId;
    private float $balance;

    public function __construct(int $userId, float $balance = 0)
    {
        $this->userId = $userId;
        $this->balance = $balance;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getBalance(): float
    {
        return $this->balance;
    }

    public function deduct(float $amount): void
    {
        if ($amount > $this->balance) {
            throw new \Exception('Insufficient funds');
        }
        $this->balance -= $amount;
        self::saveWallet($this);
    }

    public function add(float $amount): void
    {
        $this->balance += $amount;
        self::saveWallet($this);
    }

    public static function loadWallet(int $userId): Wallet
    {
        $pdo = Database::getInstance()->getConnection();
        $stmt = $pdo->prepare("SELECT * FROM wallets WHERE user_id = :user_id");
        $stmt->execute([':user_id' => $userId]);
        $walletData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($walletData) {
            return new self($walletData['user_id'], $walletData['balance']);
        }

        return new self($userId);
    }

    public static function saveWallet(self $wallet): void
    {
        $pdo = Database::getInstance()->getConnection();
        $stmt = $pdo->prepare(
            "REPLACE INTO wallets (user_id, balance) 
            VALUES (:user_id, :balance)"
        );
        $stmt->execute([
            ':user_id' => $wallet->getUserId(),
            ':balance' => $wallet->getBalance()
        ]);
    }
}
