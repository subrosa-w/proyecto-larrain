<?php
include 'db.php';
$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password2 = $_POST['password2'] ?? '';
    if ($usuario && $email && $password && $password2) {
        if ($password !== $password2) {
            $mensaje = 'Las contraseñas no coinciden.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare('INSERT INTO usuarios (nombre_usuario, email, password) VALUES (?, ?, ?)');
            $stmt->bind_param('sss', $usuario, $email, $hash);
            if ($stmt->execute()) {
                header('Location: login.php?registro=ok');
                exit();
            } else {
                if ($conn->errno === 1062) {
                    $mensaje = 'El usuario o correo ya existe.';
                } else {
                    $mensaje = 'Error al registrar usuario.';
                }
            }
            $stmt->close();
        }
    } else {
        $mensaje = 'Completa todos los campos.';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title mb-4">Registro de Usuario</h3>
                    <?php if ($mensaje): ?>
                        <div class="alert alert-danger"><?= $mensaje ?></div>
                    <?php endif; ?>
                    <form method="POST">
                        <div class="mb-3">
                            <label for="usuario" class="form-label">Usuario</label>
                            <input type="text" class="form-control" id="usuario" name="usuario" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Correo electrónico</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label for="password2" class="form-label">Repetir Contraseña</label>
                            <input type="password" class="form-control" id="password2" name="password2" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Registrarse</button>
                    </form>
                    <div class="mt-3 text-center">
                        <a href="login.php">¿Ya tienes cuenta? Inicia sesión</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html> 