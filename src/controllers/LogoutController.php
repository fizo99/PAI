<?php

require_once 'AppController.php';

class LogoutController extends AppController {

    public function __construct()
    {
        parent::__construct();
    }

    public function logout()
    {
        setcookie("userID", "", time()-3600);
        $url = "http://$_SERVER[HTTP_HOST]";
        header("Location: {$url}/login");
    }
}