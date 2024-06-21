<?php

namespace Reelz222z\CryptoExchange;

use PDO;

class TransactionHistory
{
    public static function addTransaction(int $userId, string $asset, float $amount, string $transactionType, float $price): void
    {
        $pdo = Database::getInstance()->getConnection();
        $stmt = $pdo->prepare(
            "INSERT INTO transactions (
                id, user_id, asset, amount, transaction_type, date, price, total
            ) VALUES (
                :id, :user_id, :asset, :amount, :transaction_type, :date, :price, :total
            )"
        );
        $transactionId = uniqid();  // Generate a unique transaction ID
        $stmt->execute([
            ':id' => $transactionId,
            ':user_id' => $userId,
            ':asset' => $asset,
            ':amount' => $amount,
            ':transaction_type' => $transactionType,
            ':date' => date('Y-m-d H:i:s'),
            ':price' => $price,
            ':total' => $price * $amount
        ]);
    }

    public static function getTransactions(int $userId): array
    {
        $pdo = Database::getInstance()->getConnection();
        $stmt = $pdo->prepare("SELECT * FROM transactions WHERE user_id = :user_id");
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
