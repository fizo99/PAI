<?php

require 'Router.php';

$path = trim($_SERVER['REQUEST_URI'], '/');
$path = parse_url($path, PHP_URL_PATH);

Router::post('my_invoices', 'MyInvoicesController');
Router::post('new_invoice', 'NewInvoiceController');
Router::post('login', 'LoginController');
Router::post('register', 'RegisterController');

Router::run($path);