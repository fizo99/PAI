<?php

require_once 'src/controllers/DefaultController.php';
require_once 'src/controllers/LoginController.php';
require_once 'src/controllers/RegisterController.php';
require_once 'src/controllers/NewInvoiceController.php';
require_once 'src/controllers/MyInvoicesController.php';
require_once 'src/controllers/LogoutController.php';

class Router {

    public static $routes;

    public static function get($url, $controller) {
        self::$routes[$url] = $controller;
    }

    public static function post($url, $view) {
        self::$routes[$url] = $view;
    }

    public static function run ($url) {
        $action = explode("/", $url)[0];
        if (!array_key_exists($action, self::$routes)) {
            die("Wrong url!");
        }

        $controllerName = self::$routes[$action];
        $controller = new $controllerName;
        $action = $action ?: 'index';

        $controller->$action();
    }
}