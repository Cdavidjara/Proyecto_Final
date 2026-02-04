<?php

require_once __DIR__ . '/../../../Domain/Entities/Viaje.php';
require_once __DIR__ . '/../../../Infrastructure/Persistence/MySQLViajeRepository.php';
require_once __DIR__ . '/../../../Infrastructure/Services/AsignacionConductorDisponible.php';

class SolicitarViaje
{
    public function execute(
        int $clienteId,
        string $origen,
        string $destino
    ) {
        $strategy = new AsignacionConductorDisponible();
        $conductorId = $strategy->asignar();

        $tarifa = rand(3, 10); // simulación

        $viaje = new Viaje(
            null,
            $clienteId,
            $conductorId,
            $origen,
            $destino,
            $tarifa,
            $conductorId ? 'asignado' : 'pendiente'
        );

        (new MySQLViajeRepository())->crear($viaje);
    }
}
