<?php

require_once 'AppController.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../repository/UserRepository.php';
require_once __DIR__ . '/../repository/InvoiceRepository.php';
require_once __DIR__ . '/../models/Invoice.php';
require_once __DIR__ . '/../repository/UserExistsException.php';

@ini_set ('display_errors', 'on');

class MyInvoicesController extends AppController
{

    private $invoiceRepository;

    public function __construct()
    {
        parent::__construct();
        $this->invoiceRepository = new InvoiceRepository();
    }

    public function my_invoices()
    {
        session_start();
        if (!isset($_SESSION['userID'])) {
            $url = "http://$_SERVER[HTTP_HOST]";
            header("Location: {$url}/login");
        } else {
            $userId = $_SESSION['userID'];
            $invoices = $this->invoiceRepository->getInvoicesForInvoicesListView($userId);
            $invoiceStates = $this->invoiceRepository->getAllInvoiceStates();

            return $this->render("my_invoices", ['invoices' => $invoices, 'invoiceStates' => $invoiceStates]);
        }

    }

    public function update_state($id)
    {
        session_start();
        if (!isset($_SESSION['userID'])) {
            $url = "http://$_SERVER[HTTP_HOST]";
            header("Location: {$url}/login");
        } else {
            $contentType = $this->getContentType();

            if ($contentType === "application/json") {
                $content = trim(file_get_contents("php://input"));
                $decoded = json_decode($content, true);
                if($this->invoiceRepository->updateState($id, $decoded['newState'])){
                    http_response_code(200);
                }else {
                    http_response_code(400);
                }
            }else{
                http_response_code(400);
            }

        }

    }

    public function delete_invoice(string $id)
    {
        session_start();
        if (!isset($_SESSION['userID'])) {
            $url = "http://$_SERVER[HTTP_HOST]";
            header("Location: {$url}/login");
        } else {
            $this->invoiceRepository->deleteInvoice($id);

            header('Content-type: application/json');
            http_response_code(200);
        }
    }

    public function download_invoice(string $id)
    {
        session_start();
        if (!isset($_SESSION['userID'])) {
            $url = "http://$_SERVER[HTTP_HOST]";
            header("Location: {$url}/login");
        } else {
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



            $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('public/word/template.docx');
            $templateProcessor->setValue('invoiceType', $details['invoice_type']);
            $templateProcessor->setValue('invoiceDate', $details['date']);
            $templateProcessor->setValue('invoiceNumber', $details['number']);
            $templateProcessor->setValue('paymentMethod', $details['payment_method']);
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

}


