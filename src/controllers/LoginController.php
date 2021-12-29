<?php

require_once 'AppController.php';
require_once __DIR__ .'/../models/User.php';
require_once __DIR__ .'/../repository/UserRepository.php';
require_once __DIR__ .'/../repository/UserExistsException.php';

class LoginController extends AppController {

    private $userRepository;

    public function __construct()
    {
        parent::__construct();
        $this->userRepository = new UserRepository();
    }

    public function login()
    {
        session_start();
        if (isset($_SESSION['userID'])) {
            $url = "http://$_SERVER[HTTP_HOST]";
            header("Location: {$url}/new_invoice");
        }
        if (!$this->isPost()) {
            return $this->render('login');
        }

        $email = $_POST['email'];
        $user = $this->userRepository->getUser($email);

        // TODO: throw validation to another class
        if (!$user) {
            return $this->render('login', ['messages' => ['User not found!']]);
        }

        if ($user->getEmail() !== $email) {
            return $this->render('login', ['messages' => ['User with this email does not exist!']]);
        }

        $password = md5($_POST['password']);
        if ($user->getPassword() !== $password) {
            return $this->render('login', ['messages' => ['Wrong password!']]);
        }

        $_SESSION['userID'] = $user->getUserId();

        $url = "http://$_SERVER[HTTP_HOST]";
        header("Location: {$url}/new_invoice");
    }
}