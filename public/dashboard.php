<?php
require_once __DIR__ . '/../app/Infrastructure/Security/AuthSession.php';

$user = AuthSession::user();
if (!$user) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Dashboard | TaxiApp</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<nav class="navbar navbar-dark bg-dark px-4">
    <span class="navbar-brand">
        🚕 TaxiApp
    </span>

    <span class="text-white me-3">
        <?= ucfirst($user['rol']) ?>: <?= $user['nombre'] ?>
    </span>

    <a href="logout.php" class="btn btn-outline-light btn-sm">
        Cerrar sesión
    </a>
</nav>

<div class="container mt-4">

    <h3>Bienvenido, <?= $user['nombre'] ?></h3>

    <!-- CLIENTE -->
    <?php if ($user['rol'] === 'cliente'): ?>
        <a href="solicitar_viaje.php" class="btn btn-warning mt-3">
            <i class="fas fa-taxi"></i> Solicitar Taxi
        </a>
    <?php endif; ?>

    <!-- CONDUCTOR -->
    <?php if ($user['rol'] === 'conductor'): ?>
        <a href="conductor.php" class="btn btn-primary mt-3">
            <i class="fas fa-road"></i> Ver mis viajes
        </a>
    <?php endif; ?>

    <!-- ADMIN -->
    <?php if ($user['rol'] === 'admin'): ?>
        <a href="admin.php" class="btn btn-danger mt-3">
            <i class="fas fa-user-shield"></i> Panel Administrador
        </a>
    <?php endif; ?>

</div>

</body>
</html>
