<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}
$usuario = $_SESSION['usuario'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inicio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body text-center">
                    <h2 class="mb-4">Bienvenido, <?= htmlspecialchars($usuario) ?>!</h2>
                    <a href="catalogo.php" class="btn btn-primary mb-2">Ir al Catálogo</a><br>
                    <form method="POST" action="logout.php" style="display:inline;">
                        <button type="submit" class="btn btn-outline-danger mt-2">Cerrar sesión</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html> 