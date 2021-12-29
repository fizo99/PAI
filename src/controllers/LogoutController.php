<?php

require_once 'AppController.php';

class LogoutController extends AppController {

    public function __construct()
    {
        parent::__construct();
    }

    public function logout()
    {
        session_start();
        session_unset();
        session_destroy();

        $url = "http://$_SERVER[HTTP_HOST]";
        header("Location: {$url}/login");
    }
}