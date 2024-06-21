<?php

require __DIR__ . '/../vendor/autoload.php';

use Reelz222z\CryptoExchange\Database;
use Reelz222z\CryptoExchange\User;
use Reelz222z\CryptoExchange\Wallet;

class PredefinedUsers
{
    public static function insertPredefinedUsers(): void
    {
        $users = [
            [
                'name' => 'Alice',
                'email' => 'alice@example.com',
                'wallet_balance' => 1000.0,
                'password' => 'password123'
            ],
            [
                'name' => 'Bob',
                'email' => 'bob@example.com',
                'wallet_balance' => 1500.0,
                'password' => 'password456'
            ],
            [
                'name' => 'Charlie',
                'email' => 'charlie@example.com',
                'wallet_balance' => 2000.0,
                'password' => 'password789'
            ]
        ];

        foreach ($users as $userData) {
            $hashedPassword = md5($userData['password']);
            $user = new User(
                $userData['name'],
                new Wallet(0),  // Wallet will be created and linked in the saveUser method
                $userData['email'],
                $hashedPassword
            );
            User::saveUser($user);
            echo "User ID after insertion: " . $user->getId() . "\n"; // Debugging statement
            $user->getWallet()->add($userData['wallet_balance']);  // Add initial balance to the wallet
            echo "Wallet balance after addition: " . $user->getWallet()->getBalance() . "\n"; // Debugging statement
            echo "Inserted user: " . $userData['name'] . "\n";
        }

        echo "Predefined users inserted successfully.\n";
    }
}

PredefinedUsers::insertPredefinedUsers();
