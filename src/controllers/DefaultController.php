<?php

require_once 'AppController.php';

class DefaultController extends AppController {

    public function login()
    {
        $this->render('login');
    }

    public function my_invoices()
    {
        $this->render('my_invoices');
    }
    public function new_invoice()
    {
        $this->render('new_invoice');
    }
    public function register()
    {
        $this->render('register');
    }
    public function user_settings()
    {
        $this->render('user_settings');
    }
}