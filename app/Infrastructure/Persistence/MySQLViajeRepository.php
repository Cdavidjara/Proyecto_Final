<?php

require_once __DIR__ . '/../Database/MySQLConnection.php';

class MySQLViajeRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = MySQLConnection::getInstance();
    }

    // =========================
    // CLIENTE: crear solicitud
    // =========================
    public function crear(int $clienteId, string $origen, string $destino, float $tarifa): void
    {
        $stmt = $this->db->prepare(
            "INSERT INTO viajes (cliente_id, conductor_id, origen, destino, tarifa, estado)
             VALUES (:cliente, NULL, :origen, :destino, :tarifa, 'pendiente')"
        );

        $stmt->execute([
            ':cliente' => $clienteId,
            ':origen' => $origen,
            ':destino' => $destino,
            ':tarifa' => $tarifa
        ]);
    }

    // ======================================
    // CLIENTE: ver historial con conductor
    // ======================================
    public function viajesCliente(int $clienteId): array
    {
        $stmt = $this->db->prepare(
            "SELECT v.id, v.origen, v.destino, v.tarifa, v.estado, v.creado_en,
                    c.nombre AS conductor, c.placa AS placa
             FROM viajes v
             LEFT JOIN users c ON v.conductor_id = c.id
             WHERE v.cliente_id = :cliente
             ORDER BY v.id DESC"
        );
        $stmt->execute([':cliente' => $clienteId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ======================================
    // CONDUCTOR: ver viajes asignados/historial
    // ======================================
    public function viajesConductor(int $conductorId): array
    {
        $stmt = $this->db->prepare(
            "SELECT v.id, v.origen, v.destino, v.tarifa, v.estado, v.creado_en,
                    u.nombre AS cliente
             FROM viajes v
             JOIN users u ON v.cliente_id = u.id
             WHERE v.conductor_id = :conductor
             ORDER BY v.id DESC"
        );
        $stmt->execute([':conductor' => $conductorId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // =========================
    // ADMIN: viajes pendientes
    // =========================
    public function pendientesAdmin(): array
    {
        $stmt = $this->db->query(
            "SELECT v.id, v.origen, v.destino, v.tarifa, v.estado, v.creado_en,
                    u.nombre AS cliente
             FROM viajes v
             JOIN users u ON v.cliente_id = u.id
             WHERE v.estado = 'pendiente'
             ORDER BY v.id DESC"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // =========================
    // ADMIN: listar todos los viajes
    // =========================
    public function listarAdmin(): array
    {
        $stmt = $this->db->query(
            "SELECT v.id, v.origen, v.destino, v.tarifa, v.estado, v.creado_en,
                    cli.nombre AS cliente,
                    con.nombre AS conductor,
                    con.placa  AS placa
             FROM viajes v
             JOIN users cli ON v.cliente_id = cli.id
             LEFT JOIN users con ON v.conductor_id = con.id
             ORDER BY v.id DESC"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // =========================
    // ADMIN: asignar conductor
    // =========================
    public function asignar(int $viajeId, int $conductorId): void
    {
        $stmt = $this->db->prepare(
            "UPDATE viajes
             SET conductor_id = :conductor, estado = 'asignado'
             WHERE id = :viaje AND estado = 'pendiente'"
        );

        $stmt->execute([
            ':conductor' => $conductorId,
            ':viaje' => $viajeId
        ]);
    }

    // =========================
    // CONDUCTOR: finalizar viaje
    // =========================
    public function finalizar(int $viajeId, int $conductorId): void
    {
        $stmt = $this->db->prepare(
            "UPDATE viajes
             SET estado = 'finalizado'
             WHERE id = :viaje AND conductor_id = :conductor"
        );

        $stmt->execute([
            ':viaje' => $viajeId,
            ':conductor' => $conductorId
        ]);
    }

    // ======================================
    // CLIENTE: viaje activo (pendiente o asignado)
    // ======================================
    public function viajeActivoCliente(int $clienteId): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT v.id, v.origen, v.destino, v.tarifa, v.estado,
                    c.nombre AS conductor, c.placa AS placa
             FROM viajes v
             LEFT JOIN users c ON v.conductor_id = c.id
             WHERE v.cliente_id = :cliente
               AND v.estado IN ('pendiente','asignado')
             ORDER BY v.id DESC
             LIMIT 1"
        );
        $stmt->execute([':cliente' => $clienteId]);

        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res ?: null;
    }

    // ======================================
    // CONDUCTOR: viaje activo asignado
    // ======================================
    public function viajeActivoConductor(int $conductorId): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT v.id, v.origen, v.destino, v.tarifa, v.estado,
                    u.nombre AS cliente
             FROM viajes v
             JOIN users u ON v.cliente_id = u.id
             WHERE v.conductor_id = :conductor
               AND v.estado = 'asignado'
             LIMIT 1"
        );
        $stmt->execute([':conductor' => $conductorId]);

        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res ?: null;
    }
}
