<?php

class PostArrayObjectFactory
{
    static function createUser(): User
    {
        $email = $_POST['email'];
        $password = $_POST['password'];
        return new User($email, md5($password));
    }
    static function createCompany(): Company
    {
        $nip = $_POST['nip'];
        $companyName = $_POST['company_name'];
        $contactEmail = $_POST['contact-email'];
        $phoneNumber = $_POST['phone_number'];
        $iban = $_POST['iban'];

        return new Company(
            $nip,
            $companyName,
            $contactEmail,
            $phoneNumber,
            $iban
        );
    }
    static function createAddress(): Address
    {
        $city = $_POST['city'];
        $zip = $_POST['zip'];
        $streetName = $_POST['street_name'];
        $streetNumber = $_POST['street_nr'];

        return new Address(
            $city,
            $zip,
            $streetName,
            $streetNumber
        );
    }
}