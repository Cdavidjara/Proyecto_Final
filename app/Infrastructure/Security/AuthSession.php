<?php

require_once __DIR__ . '/../../Domain/Entities/User.php';

class AuthSession
{
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function login(User $user): void
    {
        self::start();

        $_SESSION['user'] = [
            'id'     => $user->id,
            'nombre' => $user->nombre,
            'email'  => $user->email,
            'rol'    => $user->rol,
            'placa'  => $user->placa ?? null
        ];
    }

    public static function user(): ?array
    {
        self::start();
        return $_SESSION['user'] ?? null;
    }

    public static function logout(): void
    {
        self::start();

        // Vaciar datos de sesión
        $_SESSION = [];

        // Borrar cookie de sesión (más limpio)
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        session_destroy();
    }
}
