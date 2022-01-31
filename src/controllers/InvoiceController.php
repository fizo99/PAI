<?php

require_once 'AppController.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../repository/UserRepository.php';
require_once __DIR__ . '/../repository/InvoiceRepository.php';
require_once __DIR__ . '/../repository/ProductRepository.php';
require_once __DIR__ . '/../repository/AddressRepository.php';
require_once __DIR__ . '/../repository/CompanyRepository.php';
require_once __DIR__ . '/../models/Invoice.php';
require_once __DIR__ . '/../repository/UserExistsException.php';

class InvoiceController extends AppController
{

    private $invoiceRepository;
    private $userRepository;
    private $companyRepository;
    private $addressRepository;
    private $productRepository;

    public function __construct()
    {
        parent::__construct();
        $this->userRepository = new UserRepository();
        $this->invoiceRepository = new InvoiceRepository();
        $this->companyRepository = new CompanyRepository();
        $this->addressRepository = new AddressRepository();
        $this->productRepository = new ProductRepository();
    }

    public function create()
    {
        $this->checkAuth();

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
            if ($this->companyRepository->getCompany($buyerId) == null) {
                $addressId = $this->addressRepository->addAddress(new Address(
                    $decoded['city'],
                    $decoded['zip_code'],
                    $decoded['street_name'],
                    intval($decoded['street_nr'])
                ), $conn);
                $this->companyRepository->addCompany(new Company(
                    $buyerId,
                    $decoded['company_name'],
                    $decoded['phone_number'],
                ), $addressId, $conn);
            }
            //$invoiceTypeId = $this->invoiceRepository->getInvoiceType($decoded['invoice_type'],$conn);
            $invoice = new Invoice(
                $buyerId,
                $sellerId,
                $decoded['place'],
                $decoded['date'],
                $decoded['invoice_nr'],
                $decoded['payment_method'],
                $decoded['additional_informations'],
                $userId,
                $decoded['invoice_type'],
                "UNPAID"
            );

            $invoiceId = $this->invoiceRepository->addInvoice($invoice, $conn);

            $products = $decoded['products'];
            $this->productRepository->addProducts($products, $invoiceId, $conn);

            $conn->commit();

            http_response_code(200);
        } else {
            http_response_code(400);
        }
    }

    public function update($id)
    {
        $this->checkAuth();

        $contentType = $this->getContentType();
        if ($contentType === "application/json") {
            $content = trim(file_get_contents("php://input"));
            $decoded = json_decode($content, true);
            if ($this->invoiceRepository->updateState($id, $decoded['newState'])) {
                http_response_code(200);
            } else {
                http_response_code(400);
            }
        } else {
            http_response_code(400);
        }

    }


    public function delete(string $id)
    {
        $this->checkAuth();

        $this->invoiceRepository->deleteInvoice($id);
        http_response_code(200);
    }

    public function download(string $id)
    {
        $this->checkAuth();

        $details = $this->invoiceRepository->getInvoiceDetails($id);
        $products = $this->invoiceRepository->getInvoiceProducts($id);
        $totalBruttoPrice = $this->invoiceRepository->getTotalBruttoValueForInvoice($id);

        $i = 1;
        $productsValues = array();
        foreach ($products as $product) {
            array_push($productsValues,
                ['productNr' => $i++,
                    'productName' => $product['name'],
                    'quantity' => $product['quantity'],
                    'unit' => $product['unit'],
                    'nettoPrice' => $product['netto_price'],
                    'taxPercent' => $product['tax_percent'],
                    'taxAmount' => $product['tax_amount'],
                    'bruttoPrice' => $product['brutto_price']
                ]);
        }

        if (strcmp($_COOKIE['role'], "demo") == 0) {
            $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('public/word/template-watermark.docx');
        } else {
            $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('public/word/template.docx');
        }

        if ($details['payment_method'] == 'cash') {
            $paymentMethod = "Gotówka";
        } else if ($details['payment_method'] == 'transfer') {
            $paymentMethod = "Przelew";
        }

        $templateProcessor->setValue('invoiceType', $details['invoice_type']);
        $templateProcessor->setValue('invoiceDate', $details['date']);
        $templateProcessor->setValue('invoiceNumber', $details['number']);
        $templateProcessor->setValue('paymentMethod', $paymentMethod);
        $templateProcessor->setValue('additionalInformations', $details['additional_info']);


        $templateProcessor->setValue('sellerName', $details['seller_name']);
        $templateProcessor->setValue('sellerNIP', $details['seller_nip']);
        $templateProcessor->setValue('sellerStreetName', $details['seller_street_name']);
        $templateProcessor->setValue('sellerStreetNr', $details['seller_street_number']);
        $templateProcessor->setValue('sellerZipCode', $details['seller_zip_code']);
        $templateProcessor->setValue('sellerCity', $details['seller_city']);

        $templateProcessor->setValue('buyerName', $details['buyer_name']);
        $templateProcessor->setValue('buyerNIP', $details['buyer_nip']);
        $templateProcessor->setValue('buyerStreetName', $details['buyer_street_name']);
        $templateProcessor->setValue('buyerStreetNr', $details['buyer_street_number']);
        $templateProcessor->setValue('buyerZipCode', $details['buyer_zip_code']);
        $templateProcessor->setValue('buyerCity', $details['buyer_city']);

        $templateProcessor->cloneRowAndSetValues('productNr', $productsValues);
        $templateProcessor->setValue('totalBruttoPrice', $totalBruttoPrice);
        $templateProcessor->setValue('sellerIban', $details['seller_iban']);

        $filename = $templateProcessor->save();

        header("Content-Disposition: attachment; filename='myFile.docx'");
        readfile($filename);
        unlink($filename);
    }
}


