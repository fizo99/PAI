<?php

require_once 'src/controllers/ViewController.php';
require_once 'src/controllers/AuthController.php';
require_once 'src/controllers/InvoiceController.php';
require_once 'Handler.php';

class Router
{

    public static $routes;

    public static function get($url, $controller, $method)
    {
        self::$routes["get"][$url] = new Handler($controller, $method);
    }

    public static function post($url, $controller, $method)
    {
        self::$routes["post"][$url] = new Handler($controller, $method);
    }
    public static function delete($url, $controller, $method)
    {
        self::$routes["delete"][$url] = new Handler($controller, $method);
    }
    public static function put($url, $controller, $method)
    {
        self::$routes["put"][$url] = new Handler($controller, $method);
    }

    public static function run($url)
    {
        $urlParts = explode("/", $url);
        $endpoint = $urlParts[0];
        $httpMethod = strtolower($_SERVER['REQUEST_METHOD']);

        if (!array_key_exists($endpoint, self::$routes[$httpMethod])) {
            die("Wrong request!");
        }

        $handler = self::$routes[$httpMethod][$endpoint];

        $controller = $handler->getController();
        $method = $handler->getMethod();

        $id = $urlParts[1] ?? '';
        $controllerObject = new $controller;
        $controllerObject->$method($id);
    }
}