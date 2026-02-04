<?php

require_once __DIR__ . '/../../../Infrastructure/Persistence/MySQLUserRepository.php';
require_once __DIR__ . '/../../../Infrastructure/Security/AuthSession.php';

class LoginUser
{
    public function execute($email, $password): bool
    {
        $repo = new MySQLUserRepository();
        $user = $repo->findByEmail($email);

        if (!$user || !password_verify($password, $user->password)) {
            return false;
        }

        AuthSession::login($user);
        return true;
    }
}
