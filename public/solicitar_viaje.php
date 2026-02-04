<?php
require_once __DIR__ . '/../app/Infrastructure/Security/AuthSession.php';
require_once __DIR__ . '/../app/Infrastructure/Persistence/MySQLViajeRepository.php';

$user = AuthSession::user();
if (!$user || $user['rol'] !== 'cliente') {
    header('Location: login.php');
    exit;
}

$repo = new MySQLViajeRepository();

/* Crear viaje */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $repo->crear(
        $user['id'],
        $_POST['origen'],
        $_POST['destino'],
        rand(5, 12)
    );
    header("Location: solicitar_viaje.php");
    exit;
}

/* Viaje activo (pendiente o asignado) */
$viajeActivo = $repo->viajeActivoCliente($user['id']);

/* Historial */
$historial = $repo->viajesCliente($user['id']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Cliente | TaxiApp</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-light">
<div class="container mt-4">

<h3 class="mb-3"><i class="fas fa-taxi text-warning"></i> Solicitar Taxi</h3>

<div class="card shadow p-3 mb-4">
<form method="POST" class="row g-2">
    <div class="col-md-5">
        <input class="form-control" name="origen" placeholder="Origen" required>
    </div>
    <div class="col-md-5">
        <input class="form-control" name="destino" placeholder="Destino" required>
    </div>
    <div class="col-md-2">
        <button class="btn btn-warning w-100">
            <i class="fas fa-route"></i> Solicitar
        </button>
    </div>
</form>
</div>

<?php if ($viajeActivo): ?>
<div class="alert alert-info">
    <strong>🚕 Viaje en curso</strong><br>
    <b>Origen:</b> <?= $viajeActivo['origen'] ?> |
    <b>Destino:</b> <?= $viajeActivo['destino'] ?> |
    <b>Tarifa:</b> $<?= $viajeActivo['tarifa'] ?><br>
    <b>Estado:</b> <?= $viajeActivo['estado'] ?><br>
    <b>Conductor:</b> <?= $viajeActivo['conductor'] ?? 'Asignando...' ?> |
    <b>Placa:</b> <?= $viajeActivo['placa'] ?? '---' ?>
</div>
<?php endif; ?>

<h5 class="mt-4">📋 Historial de viajes</h5>

<table class="table table-bordered table-striped">
<thead>
<tr>
<th>Origen</th>
<th>Destino</th>
<th>Tarifa</th>
<th>Estado</th>
<th>Conductor</th>
<th>Placa</th>
</tr>
</thead>
<tbody>
<?php foreach ($historial as $v): ?>
<tr>
<td><?= $v['origen'] ?></td>
<td><?= $v['destino'] ?></td>
<td>$<?= $v['tarifa'] ?></td>
<td><?= $v['estado'] ?></td>
<td><?= $v['conductor'] ?? '---' ?></td>
<td><?= $v['placa'] ?? '---' ?></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

</div>
</body>
</html>
