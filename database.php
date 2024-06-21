<?php

require 'vendor/autoload.php';

use Reelz222z\CryptoExchange\Database;

$database = Database::getInstance();
$database->getConnection();
echo "Database initialized and tables created.\n";
