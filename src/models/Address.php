<?php

class Address {
    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @return mixed
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

    /**
     * @return mixed
     */
    public function getStreetName()
    {
        return $this->streetName;
    }

    /**
     * @return mixed
     */
    public function getStreetNumber()
    {
        return $this->streetNumber;
    }
    private $city;
    private $zipCode;
    private $streetName;
    private $streetNumber;

    public function __construct(
        string $city,
        string $zipCode,
        string $streetName,
        int $streetNumber
    ) {
        $this->city=$city;
        $this->zipCode=$zipCode;
        $this->streetName=$streetName;
        $this->streetNumber=$streetNumber;
    }
}