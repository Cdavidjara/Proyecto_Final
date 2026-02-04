<?php

require_once __DIR__ . '/../Database/MySQLConnection.php';
require_once __DIR__ . '/../../Domain/Entities/User.php';
require_once __DIR__ . '/../../Domain/Repositories/UserRepository.php';

class MySQLUserRepository implements UserRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = MySQLConnection::getInstance();
    }

    public function save(User $user): void
    {
        $stmt = $this->db->prepare(
            "INSERT INTO users (nombre, email, password, rol, placa)
             VALUES (:nombre, :email, :password, :rol, :placa)"
        );

        $stmt->execute([
            'nombre' => $user->nombre,
            'email' => $user->email,
            'password' => $user->password,
            'rol' => $user->rol,
            'placa' => $user->placa
        ]);
    }

    public function findByEmail(string $email): ?User
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);

        $u = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$u) return null;

        return new User(
            $u['id'],
            $u['nombre'],
            $u['email'],
            $u['password'],
            $u['rol'],
            $u['placa']
        );
    }

    public function findAll(): array
    {
        return $this->db->query(
            "SELECT id,nombre,email,rol,placa FROM users ORDER BY id DESC"
        )->fetchAll(PDO::FETCH_ASSOC);
    }

    // ✅ Conductores disponibles: NO tienen viaje asignado activo
    public function conductoresDisponibles(): array
    {
        $stmt = $this->db->query(
            "SELECT u.id, u.nombre, u.placa
             FROM users u
             LEFT JOIN viajes v
               ON v.conductor_id = u.id
              AND v.estado = 'asignado'
             WHERE u.rol = 'conductor'
               AND v.id IS NULL
             ORDER BY u.nombre"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ✅ Conductores ocupados: tienen viaje asignado activo
    public function conductoresOcupados(): array
    {
        $stmt = $this->db->query(
            "SELECT u.id, u.nombre, u.placa, v.id AS viaje_id, v.origen, v.destino
             FROM users u
             JOIN viajes v
               ON v.conductor_id = u.id
              AND v.estado = 'asignado'
             WHERE u.rol = 'conductor'
             ORDER BY u.nombre"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
