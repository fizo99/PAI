<?php

class Company
{
    private $NIP;
    private $name;
    private $email;
    private $phoneNumber;
    private $IBAN;

    public function __construct(
        $NIP,
        $name,
        $phoneNumber,
        $email = null,
        $IBAN = null
    )
    {
        $this->NIP = $NIP;
        $this->name = $name;
        $this->email = $email;
        $this->phoneNumber = $phoneNumber;
        $this->IBAN = $IBAN;
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
}