<?php

require 'Router.php';

$path = trim($_SERVER['REQUEST_URI'], '/');
$path = parse_url($path, PHP_URL_PATH);

Router::get('logout', 'LogoutController');
Router::post('my_invoices', 'MyInvoicesController');
Router::post('new_invoice', 'NewInvoiceController');
Router::post('login', 'LoginController');
Router::post('register', 'RegisterController');
Router::post('delete_invoice', 'MyInvoicesController');
Router::post('download_invoice', 'MyInvoicesController');

Router::run($path);