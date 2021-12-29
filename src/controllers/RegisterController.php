<?php

require_once 'AppController.php';
require_once 'PostArrayObjectFactory.php';
require_once __DIR__ . '/../models/Address.php';
require_once __DIR__ . '/../models/Company.php';
require_once __DIR__ . '/../repository/CompanyRepository.php';
require_once __DIR__ . '/../repository/AddressRepository.php';
require_once __DIR__ . '/../repository/UserRepository.php';
require_once __DIR__ . '/../repository/CompanyExistsException.php';

class RegisterController extends AppController
{
    private $userRepository;
    private $companyRepository;
    private $addressRepository;

    public function __construct()
    {
        parent::__construct();
        $this->companyRepository = new CompanyRepository();
        $this->addressRepository = new AddressRepository();
        $this->userRepository = new UserRepository();
    }

    public function register()
    {
        if (!$this->isPost()) {
            return $this->render('register');
        }

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
            $conn = $this->userRepository->connectRepository();

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

        session_start();
        $_SESSION['userID'] = $userId;

        $url = "http://$_SERVER[HTTP_HOST]";
        header("Location: {$url}/new_invoice");
    }
}