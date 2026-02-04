<?php
require_once __DIR__ . '/../app/Interfaces/Controllers/AuthController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    (new AuthController())->login();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Login | TaxiApp</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-dark d-flex justify-content-center align-items-center vh-100">

<div class="card shadow-lg p-4" style="width: 350px;">
    <h3 class="text-center mb-3">
        <i class="fas fa-taxi text-warning"></i> TaxiApp
    </h3>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger">Credenciales incorrectas</div>
    <?php endif; ?>

    <form method="POST">
        <input class="form-control mb-2" name="email" placeholder="Email" required>
        <input class="form-control mb-3" type="password" name="password" placeholder="Contraseña" required>
        <button class="btn btn-warning w-100">Ingresar</button>
    </form>

    <div class="text-center mt-3">
        <a href="register.php">Crear cuenta</a>
    </div>
</div>

</body>
</html>
