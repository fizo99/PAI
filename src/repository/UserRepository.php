<?php

require_once 'Repository.php';
require_once 'UserExistsException.php';
require_once 'UserAbsentException.php';
require_once __DIR__ . '/../models/User.php';

class UserRepository
{
    public function getUser(string $email, PDO $existingConn = null): ?User
    {
        $conn = $existingConn == null ? Repository::connect() : $existingConn;
        $stmt = $conn->prepare('
            SELECT email,password,user_id,is_demo::text FROM users WHERE email = :email
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
            $user['is_demo'],
            $user['user_id']
        );
    }

    public function getUserCompanyId(string $userId, PDO $existingConn = null): string
    {
        $conn = $existingConn == null ? Repository::connect() : $existingConn;
        $stmt = $conn->prepare('
            SELECT company_id FROM users WHERE user_id = :userId
        ');
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result == false) {
            throw new UserAbsentException("User with id " . $userId . "does not exist.");
        }
        return $result['company_id'];
    }

    public function addUser(User $user, string $companyId, PDO $existingConn = null): string
    {
        // TODO: handle errors
        $conn = $existingConn == null ? Repository::connect() : $existingConn;
//        if ($this->getUser($user->getEmail())) {
//            throw new UserExistsException("User with email ". $user->getEmail() . " already exists");
//        }

        $stmt = $conn->prepare('
            INSERT INTO users (email,password,company_id,is_demo)
            VALUES (?, ?, ?, ?)
        ');

        $stmt->execute([
            $user->getEmail(),
            $user->getPassword(),
            $companyId,
            $user->getIsDemo()
        ]);

        return $conn->lastInsertId();
    }
}
