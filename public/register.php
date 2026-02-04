<?php
require_once __DIR__ . '/../app/Interfaces/Controllers/AuthController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    (new AuthController())->register();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Registro | TaxiApp</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<script>
function togglePlaca() {
    const rolSelect = document.getElementById('rol');
    const placaDiv = document.getElementById('placaDiv');
    const placaInput = document.getElementById('placa');

    if (rolSelect.value === 'conductor') {
        placaDiv.style.display = 'block';
        placaInput.required = true;
    } else {
        placaDiv.style.display = 'none';
        placaInput.required = false;
        placaInput.value = '';
    }
}
</script>
</head>

<body class="bg-light d-flex justify-content-center align-items-center vh-100">

<div class="card shadow p-4" style="width: 420px;">
    <h4 class="text-center mb-3">Registro de Usuario</h4>

    <?php if (isset($_GET['error']) && $_GET['error'] === 'email'): ?>
        <div class="alert alert-danger">
            El correo ya está registrado
        </div>
    <?php endif; ?>

    <form method="POST">
        <input class="form-control mb-2" name="nombre" placeholder="Nombre completo" required>

        <input class="form-control mb-2" type="email" name="email" placeholder="Correo electrónico" required>

        <input class="form-control mb-2" type="password" name="password" placeholder="Contraseña" required>

        <!-- ROL -->
        <select class="form-select mb-2" name="rol" id="rol" onchange="togglePlaca()" required>
            <option value="">Seleccione rol</option>
            <option value="cliente">Cliente</option>
            <option value="conductor">Conductor</option>
        </select>

        <!-- PLACA (SOLO CONDUCTOR) -->
        <div id="placaDiv" style="display:none;">
            <input
                class="form-control mb-3"
                type="text"
                name="placa"
                id="placa"
                placeholder="Placa del taxi (ej: ABC-1234)">
        </div>

        <button class="btn btn-primary w-100">Registrarse</button>
    </form>

    <div class="text-center mt-3">
        <a href="login.php">Ya tengo cuenta</a>
    </div>
</div>

</body>
</html>
