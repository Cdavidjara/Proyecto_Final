<?php

require_once __DIR__ . '/../../Application/UseCases/Auth/RegisterUser.php';
require_once __DIR__ . '/../../Application/UseCases/Auth/LoginUser.php';

class AuthController
{
    public function register()
    {
        $placa = null;

        if (isset($_POST['rol']) && $_POST['rol'] === 'conductor') {
            $placa = $_POST['placa'] ?? null;
        }

        $ok = (new RegisterUser())->execute(
            $_POST['nombre'],
            $_POST['email'],
            $_POST['password'],
            $_POST['rol'],
            $placa
        );

        if (!$ok) {
            header('Location: register.php?error=email');
            exit;
        }

        header('Location: login.php?registered=1');
        exit;
    }

    public function login()
    {
        $ok = (new LoginUser())->execute(
            $_POST['email'],
            $_POST['password']
        );

        header('Location: ' . ($ok ? 'dashboard.php' : 'login.php?error=1'));
        exit;
    }
}
