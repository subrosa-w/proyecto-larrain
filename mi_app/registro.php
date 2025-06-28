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
            $rol = 'usuario'; // Por defecto, los usuarios registrados son 'usuario'
            $stmt = $conn->prepare('INSERT INTO usuarios (nombre_usuario, email, password, rol) VALUES (?, ?, ?, ?)');
            $stmt->bind_param('ssss', $usuario, $email, $hash, $rol);
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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registro - Importadora Larraín</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        html,
        body {
            height: 100%;
        }

        .form-signin {
            max-width: 400px;
            padding: 1rem;
        }

        .form-signin .form-floating:focus-within {
            z-index: 2;
        }

        .form-signin input[type="text"] {
            margin-bottom: -1px;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
        }

        .form-signin input[type="email"] {
            margin-bottom: -1px;
            border-radius: 0;
        }

        .form-signin input[type="password"] {
            margin-bottom: -1px;
            border-radius: 0;
        }

        .form-signin input[type="password"]:last-of-type {
            margin-bottom: 10px;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
        }

        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }

        .b-example-divider {
            width: 100%;
            height: 3rem;
            background-color: rgba(0, 0, 0, .1);
            border: solid rgba(0, 0, 0, .15);
            border-width: 1px 0;
            box-shadow: inset 0 .5em 1.5em rgba(0, 0, 0, .1), inset 0 .125em .5em rgba(0, 0, 0, .15);
        }

        .b-example-vr {
            flex-shrink: 0;
            width: 1.5rem;
            height: 100vh;
        }

        .bi {
            vertical-align: -.125em;
            fill: currentColor;
        }

        .nav-scroller {
            position: relative;
            z-index: 2;
            height: 2.75rem;
            overflow-y: hidden;
        }

        .nav-scroller .nav {
            display: flex;
            flex-wrap: nowrap;
            padding-bottom: 1rem;
            margin-top: -1px;
            overflow-x: auto;
            text-align: center;
            white-space: nowrap;
            -webkit-overflow-scrolling: touch;
        }

        .btn-bd-primary {
            --bd-violet-bg: #712cf9;
            --bd-violet-rgb: 112.520718, 44.062154, 249.437846;

            --bs-btn-font-weight: 600;
            --bs-btn-color: var(--bs-white);
            --bs-btn-bg: var(--bd-violet-bg);
            --bs-btn-border-color: var(--bd-violet-bg);
            --bs-btn-hover-color: var(--bs-white);
            --bs-btn-hover-bg: #6528e0;
            --bs-btn-hover-border-color: #6528e0;
            --bs-btn-focus-shadow-rgb: var(--bd-violet-rgb);
            --bs-btn-active-color: var(--bs-btn-hover-color);
            --bs-btn-active-bg: #5a23c8;
            --bs-btn-active-border-color: #5a23c8;
        }

        .bd-mode-toggle {
            z-index: 1500;
        }

        .bd-mode-toggle .dropdown-menu .active .bi {
            display: block !important;
        }
    </style>
</head>
<body class="d-flex align-items-center py-4 bg-body-tertiary">
<main class="form-signin w-100 m-auto">
    <form method="POST">
        <div class="text-center mb-4">
            <h1 class="h3 mb-3 fw-normal">Importadora Larraín</h1>
            <p class="text-muted">Crea tu cuenta</p>
        </div>

        <?php if ($mensaje): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($mensaje) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="form-floating">
            <input type="text" class="form-control" id="usuario" name="usuario" placeholder="Usuario" required>
            <label for="usuario">Usuario</label>
        </div>
        <div class="form-floating">
            <input type="email" class="form-control" id="email" name="email" placeholder="nombre@ejemplo.com" required>
            <label for="email">Correo electrónico</label>
        </div>
        <div class="form-floating">
            <input type="password" class="form-control" id="password" name="password" placeholder="Contraseña" required>
            <label for="password">Contraseña</label>
        </div>
        <div class="form-floating">
            <input type="password" class="form-control" id="password2" name="password2" placeholder="Repetir Contraseña" required>
            <label for="password2">Repetir Contraseña</label>
        </div>

        <div class="form-check text-start my-3">
            <input class="form-check-input" type="checkbox" value="agree-terms" id="flexCheckDefault" required>
            <label class="form-check-label" for="flexCheckDefault">
                Acepto los <a href="#" class="text-decoration-none">términos y condiciones</a>
            </label>
        </div>
        <button class="btn btn-primary w-100 py-2" type="submit">Registrarse</button>
        <p class="mt-5 mb-3 text-body-secondary text-center">
            <a href="login.php" class="text-decoration-none">¿Ya tienes cuenta? Inicia sesión</a>
        </p>
        <p class="mt-5 mb-3 text-body-secondary text-center">&copy; 2024 Importadora Larraín</p>
    </form>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 