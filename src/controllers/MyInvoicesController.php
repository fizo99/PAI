<?php

require_once 'AppController.php';
require_once __DIR__ .'/../models/User.php';
require_once __DIR__ .'/../repository/UserRepository.php';
require_once __DIR__ .'/../repository/InvoiceRepository.php';
require_once __DIR__ .'/../models/Invoice.php';
require_once __DIR__ .'/../repository/UserExistsException.php';

class MyInvoicesController extends AppController {

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
        }else{
            $userId = $_SESSION['userID'];
            $invoices = $this->invoiceRepository->getInvoicesForInvoicesListView($userId);
            return $this->render("my_invoices", ['invoices' => $invoices]);
//            if (!$this->isPost()) {
//                return $this->render('my_invoices');
//            }

        }

    }

    public function delete_invoice(string $id)
    {
        session_start();
        if (!isset($_SESSION['userID'])) {
            $url = "http://$_SERVER[HTTP_HOST]";
            header("Location: {$url}/login");
        }else{
            $this->invoiceRepository->deleteInvoice($id);

            header('Content-type: application/json');
            http_response_code(200);
        }
    }
}