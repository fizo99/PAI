<?php

require_once 'AppController.php';
require_once __DIR__ .'/../models/User.php';
require_once __DIR__ .'/../models/Invoice.php';
require_once __DIR__ .'/../repository/UserRepository.php';
require_once __DIR__ .'/../repository/AddressRepository.php';
require_once __DIR__ .'/../repository/CompanyRepository.php';
require_once __DIR__ .'/../repository/InvoiceRepository.php';
require_once __DIR__ .'/../repository/ProductRepository.php';
require_once __DIR__ .'/../repository/UserExistsException.php';

class NewInvoiceController extends AppController {

    private $userRepository;
    private $addressRepository;
    private $companyRepository;
    private $invoiceRepository;
    private $productRepository;

    public function __construct()
    {
        parent::__construct();
        $this->userRepository = new UserRepository();
        $this->companyRepository = new CompanyRepository();
        $this->addressRepository = new AddressRepository();
        $this->invoiceRepository = new InvoiceRepository();
        $this->productRepository = new ProductRepository();
    }
    function console_log( $data ){
        echo '<script>';
        echo 'console.log('. json_encode( $data ) .')';
        echo '</script>';
    }

    public function new_invoice()
    {
        session_start();
        if (!isset($_SESSION['userID'])) {
            $url = "http://$_SERVER[HTTP_HOST]";
            header("Location: {$url}/login");
        }else{
            if (!$this->isPost()) {
                return $this->render('new_invoice');
            }
            $contentType = $this->getContentType();

            if ($contentType === "application/json") {
                $content = trim(file_get_contents("php://input"));
                $decoded = json_decode($content, true);
                $userId = $_SESSION['userID'];

                $conn = Repository::connect();
                $conn->beginTransaction();
                //get seller id
                $sellerId = $this->userRepository->getUserCompanyId($userId, $conn);
                $buyerId = $decoded['nip'];
                //get buyer id or create new one
                if($this->companyRepository->getCompany($buyerId) == null) {
                    $addressId = $this->addressRepository->addAddress(new Address(
                        $decoded['city'],
                        $decoded['zip_code'],
                        $decoded['street_name'],
                        intval($decoded['street_nr'])
                    ),$conn);
                    $this->companyRepository->addCompany(new Company(
                        $buyerId,
                        $decoded['company_name'],
                        $decoded['phone_number'],
                    ),$addressId,$conn);
                }

                $invoice = new Invoice(
                    $buyerId,
                    $sellerId,
                    $decoded['place'],
                    $decoded['date'],
                    $decoded['invoice_nr'],
                    $decoded['payment_method'],
                    $decoded['additional_informations'],
                    $userId
                );

                $invoiceId = $this->invoiceRepository->addInvoice($invoice, $conn);

                $products = $decoded['products'];
                $this->productRepository->addProducts($products,$invoiceId, $conn);

                $conn->commit();

                header('Content-type: application/json');
                http_response_code(200);
            }else{
                http_response_code(400);
            }
        }

    }


}