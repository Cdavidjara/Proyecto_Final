<?php

require_once __DIR__ . '/../../../Domain/Services/UserFactory.php';
require_once __DIR__ . '/../../../Infrastructure/Persistence/MySQLUserRepository.php';

class RegisterUser
{
    public function execute(
        string $nombre,
        string $email,
        string $password,
        string $rol,
        ?string $placa = null
    ): bool {
        $repo = new MySQLUserRepository();

        try {
            $user = UserFactory::create(
                $nombre,
                $email,
                $password,
                $rol,
                $placa
            );

            $repo->save($user);
            return true;

        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                return false; // email duplicado
            }
            throw $e;
        }
    }
}
