<?php

class Company {
    public function getAddress(): Address
    {
        return $this->address;
    }
    public function getNIP()
    {
        return $this->NIP;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }
    public function getIBAN()
    {
        return $this->IBAN;
    }
    public function getEmail()
    {
        return $this->email;
    }
    private $NIP;
    private $name;
    private $email;
    private $phoneNumber;
    private $IBAN;

    public function __construct(
        string $NIP,
        string $name,
        string $email,
        string $phoneNumber,
        string $IBAN
    ) {
        $this->NIP=$NIP;
        $this->name=$name;
        $this->email=$email;
        $this->phoneNumber=$phoneNumber;
        $this->IBAN=$IBAN;
    }
}