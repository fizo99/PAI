<?php

require_once 'AppController.php';
require_once __DIR__ .'/../models/User.php';
require_once __DIR__ .'/../repository/UserRepository.php';
require_once __DIR__ .'/../repository/CompanyRepository.php';
require_once __DIR__ .'/../repository/AddressRepository.php';
require_once 'PostArrayObjectFactory.php';

class AuthController extends AppController {

    private $userRepository;
    private $companyRepository;
    private $addressRepository;


    public function __construct()
    {
        parent::__construct();
        $this->userRepository = new UserRepository();
        $this->companyRepository = new CompanyRepository();
        $this->addressRepository = new AddressRepository();
    }

    public function login()
    {
        session_start();
        if (isset($_SESSION['userID'])) {
            $url = "http://$_SERVER[HTTP_HOST]";
            header("Location: {$url}/create");
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

        $role = $user->getIsDemo() == "true" ? "demo" : "standard";
        setcookie('role', $role, time()+(86400 * 30), "/");

        $_SESSION['userID'] = $user->getUserId();

        $url = "http://$_SERVER[HTTP_HOST]";
        header("Location: {$url}/create");
    }

    public function register()
    {
        $password = $_POST['password'];
        $passwordRepeat = $_POST['password-repeat'];

        $user = PostArrayObjectFactory::createUser();
        $address = PostArrayObjectFactory::createAddress();
        $company = PostArrayObjectFactory::createCompany();

        //TODO: throw validation to another class
        if ($password !== $passwordRepeat) {
            return $this->render('register', ['messages' => ['Passwords are different!']]);
        }

        try {
            $conn = Repository::connect();

            $conn->beginTransaction();
            $addressId = $this->addressRepository->addAddress($address, $conn);
            $companyId = $this->companyRepository->addCompany($company, $addressId, $conn);
            $userId = $this->userRepository->addUser($user, $companyId, $conn);

            $conn->commit();
        } catch (UserExistsException $ex) {
            return $this->render('register', ['messages' => [$ex->getMessage()]]);
        }catch (AddressExistsException $ex) {
            return $this->render('register', ['messages' => [$ex->getMessage()]]);
        } catch (CompanyExistsException $ex) {
            return $this->render('register', ['messages' => [$ex->getMessage()]]);
        }

        $role = $user->getIsDemo() == "true" ? "demo" : "standard";
        setcookie('role', $role, time()+(86400 * 30), "/");

        session_start();
        $_SESSION['userID'] = $userId;

        $url = "http://$_SERVER[HTTP_HOST]";
        header("Location: {$url}/create");
    }

    public function logout()
    {
        session_start();
        session_unset();
        session_destroy();

        setcookie("role", "", time()-3600);

        $url = "http://$_SERVER[HTTP_HOST]";
        header("Location: {$url}/login");
    }
}