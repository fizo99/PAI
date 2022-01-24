<?php

class PostArrayObjectFactory
{
    static function createUser(): User
    {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $isDemo = $_POST['is_demo'] == null ? "false" : "true";
        return new User($email, md5($password), $isDemo);
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
            $phoneNumber,
            $contactEmail,
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