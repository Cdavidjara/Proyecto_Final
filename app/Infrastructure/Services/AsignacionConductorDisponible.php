<?php

require_once __DIR__ . '/../../Infrastructure/Database/MySQLConnection.php';
require_once __DIR__ . '/../../Domain/Services/AsignacionConductorStrategy.php';

class AsignacionConductorDisponible implements AsignacionConductorStrategy
{
    public function asignar(): ?int
    {
        $db = MySQLConnection::getInstance();

        $stmt = $db->query(
            "SELECT id FROM users WHERE rol = 'conductor' LIMIT 1"
        );

        $conductor = $stmt->fetch(PDO::FETCH_ASSOC);
        return $conductor ? $conductor['id'] : null;
    }
}
