<?php

require 'Router.php';

$path = trim($_SERVER['REQUEST_URI'], '/');
$path = parse_url($path, PHP_URL_PATH);

Router::get('login', 'DefaultController');
Router::get('register', 'DefaultController');
Router::get('user_settings', 'DefaultController');
Router::get('my_invoices', 'DefaultController');
Router::get('new_invoice', 'DefaultController');

Router::run($path);