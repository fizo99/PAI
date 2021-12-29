<?php

class User {
    private $email;
    private $password;
    private $userId;
    private $companyId;

    public function __construct(
        string $email,
        string $password,
        string $userId = null,
        string $companyId = null
    ) {
        $this->email = $email;
        $this->password = $password;
        $this->userId = $userId;
        $this->companyId = $companyId;
    }

    public function getUserId(): ?string
    {
        return $this->userId;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword()
    {
        return $this->password;
    }
}