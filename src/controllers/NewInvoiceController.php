<?php

require_once 'AppController.php';
require_once __DIR__ .'/../models/User.php';
require_once __DIR__ .'/../repository/UserRepository.php';
require_once __DIR__ .'/../repository/UserExistsException.php';

class NewInvoiceController extends AppController {

    public function __construct()
    {
        parent::__construct();
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
        }

    }
}