<?php

require_once 'Repository.php';
require_once 'UserExistsException.php';
require_once __DIR__ . '/../models/User.php';

class UserRepository extends Repository
{

    public function getUser(string $email, PDO $existingConn = null): ?User
    {
        $conn = $existingConn == null ? $this->connectRepository() : $existingConn;
        $stmt = $conn->prepare('
            SELECT * FROM users WHERE email = :email
        ');
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user == false) {
            return null;
        }

        return new User(
            $user['email'],
            $user['password'],
        );
    }

    public function addUser(User $user, string $companyId, PDO $existingConn = null): string
    {
        // TODO: handle errors
        $conn = $existingConn == null ? $this->connectRepository() : $existingConn;
//        if ($this->getUser($user->getEmail())) {
//            throw new UserExistsException("User with email ". $user->getEmail() . " already exists");
//        }

        $stmt = $conn->prepare('
            INSERT INTO users (email,password,company_id)
            VALUES (?, ?, ?)
        ');

        $stmt->execute([
            $user->getEmail(),
            $user->getPassword(),
            $companyId
        ]);

        return $conn->lastInsertId();
    }
}
