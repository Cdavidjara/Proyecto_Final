<?php
require_once __DIR__ . '/../Entities/User.php';

class UserFactory
{
    public static function create(
        string $nombre,
        string $email,
        string $password,
        string $rol,
        ?string $placa = null
    ): User {
        return new User(
            null,
            $nombre,
            $email,
            password_hash($password, PASSWORD_BCRYPT),
            $rol,
            $rol === 'conductor' ? $placa : null
        );
    }
}
