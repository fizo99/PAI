<?php

require_once 'AppController.php';
require_once __DIR__ . '/../repository/InvoiceRepository.php';

class ViewController extends AppController
{

    private $invoiceRepository;

    public function __construct()
    {
        $this->invoiceRepository = new InvoiceRepository();
    }

    public function default()
    {
        $this->checkAuth();
        $this->redirect('create');
    }

    public function login()
    {
        $this->render('login');
    }

    public function list()
    {
        $this->checkAuth();

        $userId = $_SESSION['userID'];
        $invoices = $this->invoiceRepository->getInvoicesForInvoicesListView($userId);

        return $this->render("list", ['invoices' => $invoices]);
    }

    public function create()
    {
        $this->checkAuth();
        $this->render('create');
    }

    public function register()
    {
        $this->render('register');
    }
}