<?php

require 'Router.php';

$path = trim($_SERVER['REQUEST_URI'], '/');
$path = parse_url($path, PHP_URL_PATH);

Router::get('', 'ViewController', 'default');
Router::get('login', 'ViewController', 'login');
Router::get('register', 'ViewController', 'register');
Router::get('create', 'ViewController', 'create');
Router::get('list', 'ViewController', 'list');

Router::get('logout', 'AuthController', 'logout');
Router::post('login', 'AuthController', 'login');
Router::post('register', 'AuthController', 'register');

Router::post('invoice', 'InvoiceController', 'create');
Router::delete('invoice', 'InvoiceController', 'delete');
Router::put('invoice', 'InvoiceController', 'update');
Router::get('invoice', 'InvoiceController', 'download');

Router::run($path);