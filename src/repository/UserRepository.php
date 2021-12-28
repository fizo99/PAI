<?php

require_once 'Repository.php';
require_once 'UserExistsException.php';
require_once __DIR__ . '/../models/User.php';

class UserRepository extends Repository
{

    public function getUser(string $email): ?User
    {
        $stmt = $this->database->connect()->prepare('
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

    public function addUser(User $user)
    {
        $existingUser = $this->getUser($user->getEmail());
        if ($existingUser) {
            throw new UserExistsException("User with email ". $user->getEmail() . " already exists");
        }

        $stmt = $this->database->connect()->prepare('
            INSERT INTO users (email,password)
            VALUES (?, ?)
        ');

        $stmt->execute([
                $user->getEmail(),
                $user->getPassword(),
        ]);
    }
}
