<?php


namespace Reelz222z\CryptoExchange;

class Login
{
    public static function authenticate(string $username, string $password): ?User
    {
        $pdo = Database::getInstance()->getConnection();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE name = :name");
        $stmt->execute([':name' => $username]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($user && md5($password) === $user['password']) {
            $wallet = Wallet::loadWallet($user['id']);
            return new User($user['name'], $wallet, $user['email'], $user['password'], (int)$user['id']);
        }

        return null;
    }
}
