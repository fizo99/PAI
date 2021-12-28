<?php

require_once 'AppController.php';
require_once __DIR__ . '/../models/Address.php';
require_once __DIR__ . '/../models/Company.php';
require_once __DIR__ . '/../repository/CompanyRepository.php';
require_once __DIR__ . '/../repository/CompanyExistsException.php';

class UserSettingsController extends AppController
{

    private $companyRepository;

    public function __construct()
    {
        parent::__construct();
        $this->companyRepository = new CompanyRepository();
    }

    function debug_to_console($data)
    {
        $output = $data;
        if (is_array($output))
            $output = implode(',', $output);

        echo "<script>console.log('Debug Objects: " . json_encode($output) . "' );</script>";
    }


    public function user_settings()
    {
        if (!$this->isPost()) {
            return $this->render('user_settings');
        }

        $nip = $_POST['nip'];
        $companyName = $_POST['company_name'];
        $email = $_POST['email'];
        $phoneNumber = $_POST['phone_number'];
        $iban = $_POST['iban'];
        $city = $_POST['city'];
        $zip = $_POST['zip'];
        $streetName = $_POST['street_name'];
        $streetNumber = $_POST['street_nr'];

        $address = new Address(
            $city,
            $zip,
            $streetName,
            $streetNumber
        );

        $company = new Company(
            $nip,
            $companyName,
            $email,
            $phoneNumber,
            $iban
        );

        try {
            //$this->addressRepository->addAddress($address);
            $this->companyRepository->addCompany($company, $address);
        } catch (CompanyExistsException $ex) {
            return $this->render('user_settings', ['messages' => [$ex->getMessage()]]);
        }

        $url = "http://$_SERVER[HTTP_HOST]";
        header("Location: {$url}/new_invoice");
    }
}