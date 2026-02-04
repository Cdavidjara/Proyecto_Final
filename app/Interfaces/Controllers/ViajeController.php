<?php

require_once __DIR__ . '/../../Application/UseCases/Viajes/SolicitarViaje.php';
require_once __DIR__ . '/../../Infrastructure/Security/AuthSession.php';

class ViajeController
{
    public function solicitar()
    {
        $user = AuthSession::user();

        (new SolicitarViaje())->execute(
            $user['id'],
            $_POST['origen'],
            $_POST['destino']
        );

        header('Location: dashboard.php');
    }
}
