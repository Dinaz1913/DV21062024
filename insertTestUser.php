<?php

require 'vendor/autoload.php';

class insertTestUser
{
    public static function insert()
    {
        $wallet = new \Reelz222z\CryptoExchange\Wallet(1000.0);
        $user = new \Reelz222z\CryptoExchange\User('Test User', 'test@example.com', $wallet, password_hash('testpassword', PASSWORD_DEFAULT));
        \Reelz222z\CryptoExchange\User::saveUser($user);
        echo "Test user inserted.\n";
    }
}

insertTestUser::insert();
