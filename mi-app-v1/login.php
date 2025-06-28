<?php
session_start();
if (isset($_SESSION['usuario'])) {
    header('Location: home.php');
    exit();
}
include 'db.php';
$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $password = $_POST['password'] ?? '';
    if ($usuario && $password) {
        $stmt = $conn->prepare('SELECT id, nombre_usuario, password FROM usuarios WHERE nombre_usuario = ?');
        $stmt->bind_param('s', $usuario);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            if (password_verify($password, $row['password'])) {
                $_SESSION['usuario'] = $row['nombre_usuario'];
                $_SESSION['usuario_id'] = $row['id'];
                header('Location: home.php');
                exit();
            } else {
                $mensaje = 'Contraseña incorrecta.';
            }
        } else {
            $mensaje = 'Usuario no encontrado.';
        }
        $stmt->close();
    } else {
        $mensaje = 'Completa todos los campos.';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title mb-4">Iniciar Sesión</h3>
                    <?php if ($mensaje): ?>
                        <div class="alert alert-danger"><?= $mensaje ?></div>
                    <?php endif; ?>
                    <form method="POST">
                        <div class="mb-3">
                            <label for="usuario" class="form-label">Usuario</label>
                            <input type="text" class="form-control" id="usuario" name="usuario" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Entrar</button>
                    </form>
                    <div class="mt-3 text-center">
                        <a href="registro.php">¿No tienes cuenta? Regístrate</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html> 