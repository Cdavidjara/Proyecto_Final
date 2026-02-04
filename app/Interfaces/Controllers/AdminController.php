<?php

require_once __DIR__ . '/../../Infrastructure/Security/AuthSession.php';
require_once __DIR__ . '/../../Infrastructure/Persistence/MySQLUserRepository.php';
require_once __DIR__ . '/../../Infrastructure/Persistence/MySQLViajeRepository.php';

class AdminController
{
    public function index()
    {
        $user = AuthSession::user();
        if (!$user || $user['rol'] !== 'admin') {
            header('Location: login.php');
            exit;
        }

        $userRepo = new MySQLUserRepository();
        $viajeRepo = new MySQLViajeRepository();

        return [
            'users' => $userRepo->findAll(),
            'conductores' => $userRepo->conductoresDisponibles(),
            'ocupados' => $userRepo->conductoresOcupados(),
            'pendientes' => $viajeRepo->pendientesAdmin(),
            'viajes' => $viajeRepo->listarAdmin()
        ];
    }

    public function asignarViaje(int $viajeId, int $conductorId)
    {
        $user = AuthSession::user();
        if (!$user || $user['rol'] !== 'admin') {
            header('Location: login.php');
            exit;
        }

        (new MySQLViajeRepository())->asignar($viajeId, $conductorId);
    }
}
