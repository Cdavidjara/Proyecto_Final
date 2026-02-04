<?php
require_once __DIR__ . '/../app/Infrastructure/Security/AuthSession.php';
require_once __DIR__ . '/../app/Infrastructure/Persistence/MySQLViajeRepository.php';

$user = AuthSession::user();
if (!$user || $user['rol'] !== 'conductor') {
    header('Location: login.php');
    exit;
}

$repo = new MySQLViajeRepository();

// Finalizar viaje
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['finalizar'])) {
    $repo->finalizar((int)$_POST['finalizar'], (int)$user['id']);
    header("Location: conductor.php"); // recarga para ver actualizado
    exit;
}

// Viaje activo asignado
$viajeActivo = $repo->viajeActivoConductor((int)$user['id']);

// Historial (incluye asignados y finalizados)
$historial = $repo->viajesConductor((int)$user['id']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Conductor | TaxiApp</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<nav class="navbar navbar-dark bg-dark px-4">
  <span class="navbar-brand">🚖 TaxiApp - Conductor</span>
  <div class="d-flex gap-2">
    <a href="dashboard.php" class="btn btn-outline-light btn-sm">Dashboard</a>
    <a href="logout.php" class="btn btn-outline-warning btn-sm">Cerrar sesión</a>
  </div>
</nav>

<div class="container mt-4">

  <h4 class="mb-3">Bienvenido, <?= htmlspecialchars($user['nombre']) ?></h4>

  <?php if (!$viajeActivo): ?>
    <div class="alert alert-secondary">
      <i class="fas fa-hourglass-half"></i> Esperando asignación de viajes...
    </div>
  <?php else: ?>
    <div class="card shadow p-3 mb-4">
      <h5 class="mb-2"><i class="fas fa-route"></i> Viaje asignado</h5>
      <div class="row">
        <div class="col-md-4"><b>Origen:</b> <?= htmlspecialchars($viajeActivo['origen']) ?></div>
        <div class="col-md-4"><b>Destino:</b> <?= htmlspecialchars($viajeActivo['destino']) ?></div>
        <div class="col-md-4"><b>Tarifa:</b> $<?= $viajeActivo['tarifa'] ?></div>
      </div>

      <form method="POST" class="mt-3">
        <input type="hidden" name="finalizar" value="<?= $viajeActivo['id'] ?>">
        <button class="btn btn-success w-100">
          <i class="fas fa-flag-checkered"></i> Finalizar viaje
        </button>
      </form>
    </div>
  <?php endif; ?>

  <div class="card shadow">
    <div class="card-header bg-primary text-white">
      <i class="fas fa-list"></i> Historial de mis viajes
    </div>
    <div class="card-body">
      <?php if (count($historial) === 0): ?>
        <div class="alert alert-info m-0">Aún no tienes viajes registrados.</div>
      <?php else: ?>
        <table class="table table-striped table-bordered align-middle">
          <thead>
            <tr>
              <th>ID</th>
              <th>Cliente</th>
              <th>Origen</th>
              <th>Destino</th>
              <th>Tarifa</th>
              <th>Estado</th>
              <th>Fecha</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($historial as $v): ?>
              <tr>
                <td><?= $v['id'] ?></td>
                <td><?= htmlspecialchars($v['cliente']) ?></td>
                <td><?= htmlspecialchars($v['origen']) ?></td>
                <td><?= htmlspecialchars($v['destino']) ?></td>
                <td>$<?= $v['tarifa'] ?></td>
                <td><?= $v['estado'] ?></td>
                <td><?= $v['creado_en'] ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </div>
  </div>

</div>
</body>
</html>
