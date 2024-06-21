<?php

namespace Reelz222z\CryptoExchange;

class BaseUser
{
    protected string $name;
    protected string $password;

    public function __construct(string $name, string $password)
    {
        $this->name = $name;
        $this->password = password_hash($password, PASSWORD_BCRYPT);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function verifyPassword(string $password): bool
    {
        return password_verify($password, $this->password);
    }
}
