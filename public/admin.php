<?php
require_once __DIR__ . '/../app/Interfaces/Controllers/AdminController.php';

$controller = new AdminController();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['viaje'], $_POST['conductor'])) {
    $controller->asignarViaje((int)$_POST['viaje'], (int)$_POST['conductor']);
    header("Location: admin.php");
    exit;
}

$data = $controller->index();

$users       = $data['users'];
$pendientes  = $data['pendientes'];
$conductores = $data['conductores']; // disponibles
$ocupados    = $data['ocupados'];    // ocupados
$viajes      = $data['viajes'];

function badgeEstado($estado) {
    switch ($estado) {
        case 'pendiente': return 'bg-secondary';
        case 'asignado': return 'bg-warning text-dark';
        case 'finalizado': return 'bg-success';
        default: return 'bg-dark';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Panel Admin | TaxiApp</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<nav class="navbar navbar-dark bg-dark px-4">
    <span class="navbar-brand">
        <i class="fas fa-user-shield"></i> Panel Administrador
    </span>
    <div class="d-flex gap-2">
        <a href="dashboard.php" class="btn btn-outline-light btn-sm">Dashboard</a>
        <a href="logout.php" class="btn btn-outline-warning btn-sm">Cerrar sesión</a>
    </div>
</nav>

<div class="container mt-4">

    <?php if (count($ocupados) > 0): ?>
        <div class="alert alert-warning">
            <b>⚠ Conductores ocupados:</b>
            <ul class="mb-0">
                <?php foreach ($ocupados as $o): ?>
                    <li>
                        <?= htmlspecialchars($o['nombre']) ?> (<?= htmlspecialchars($o['placa'] ?? 'SIN PLACA') ?>)
                        — Viaje #<?= $o['viaje_id'] ?> (<?= htmlspecialchars($o['origen']) ?> → <?= htmlspecialchars($o['destino']) ?>)
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- USUARIOS -->
    <div class="card mb-4 shadow">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-users"></i> Usuarios Registrados
        </div>
        <div class="card-body">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Placa</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $u): ?>
                    <tr>
                        <td><?= $u['id'] ?></td>
                        <td><?= htmlspecialchars($u['nombre']) ?></td>
                        <td><?= htmlspecialchars($u['email']) ?></td>
                        <td><span class="badge bg-secondary"><?= $u['rol'] ?></span></td>
                        <td><?= htmlspecialchars($u['placa'] ?? '---') ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- PENDIENTES -->
    <div class="card mb-4 shadow">
        <div class="card-header bg-warning">
            <i class="fas fa-clock"></i> Solicitudes Pendientes (Asignar Conductor)
        </div>
        <div class="card-body">

            <?php if (count($pendientes) === 0): ?>
                <div class="alert alert-success m-0">No hay viajes pendientes 🎉</div>
            <?php else: ?>

                <?php if (count($conductores) === 0): ?>
                    <div class="alert alert-danger">
                        No hay conductores disponibles (todos están ocupados o no existen conductores).
                    </div>
                <?php endif; ?>

                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Origen</th>
                            <th>Destino</th>
                            <th>Tarifa</th>
                            <th>Estado</th>
                            <th>Asignar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pendientes as $p): ?>
                        <tr>
                            <td><?= $p['id'] ?></td>
                            <td><?= htmlspecialchars($p['cliente']) ?></td>
                            <td><?= htmlspecialchars($p['origen']) ?></td>
                            <td><?= htmlspecialchars($p['destino']) ?></td>
                            <td>$<?= $p['tarifa'] ?></td>
                            <td>
                                <span class="badge <?= badgeEstado($p['estado']) ?>">
                                    <?= $p['estado'] ?>
                                </span>
                            </td>
                            <td style="min-width: 260px;">
                                <?php if (count($conductores) === 0): ?>
                                    <span class="text-muted">Sin conductores disponibles</span>
                                <?php else: ?>
                                    <form method="post" class="d-flex gap-2">
                                        <input type="hidden" name="viaje" value="<?= $p['id'] ?>">
                                        <select class="form-select" name="conductor" required>
                                            <?php foreach ($conductores as $c): ?>
                                                <option value="<?= $c['id'] ?>">
                                                    <?= htmlspecialchars($c['nombre']) ?> - <?= htmlspecialchars($c['placa'] ?? 'SIN PLACA') ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button class="btn btn-dark">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

            <?php endif; ?>
        </div>
    </div>

    <!-- TODOS LOS VIAJES -->
    <div class="card shadow">
        <div class="card-header bg-dark text-white">
            <i class="fas fa-taxi"></i> Todos los Viajes
        </div>
        <div class="card-body">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Conductor</th>
                        <th>Placa</th>
                        <th>Origen</th>
                        <th>Destino</th>
                        <th>Tarifa</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($viajes as $v): ?>
                    <tr>
                        <td><?= $v['id'] ?></td>
                        <td><?= htmlspecialchars($v['cliente']) ?></td>
                        <td><?= htmlspecialchars($v['conductor'] ?? '---') ?></td>
                        <td><?= htmlspecialchars($v['placa'] ?? '---') ?></td>
                        <td><?= htmlspecialchars($v['origen']) ?></td>
                        <td><?= htmlspecialchars($v['destino']) ?></td>
                        <td>$<?= $v['tarifa'] ?></td>
                        <td><span class="badge <?= badgeEstado($v['estado']) ?>"><?= $v['estado'] ?></span></td>
                        <td><?= $v['creado_en'] ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
</body>
</html>
