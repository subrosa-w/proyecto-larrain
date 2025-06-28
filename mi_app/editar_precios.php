<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}

// Verificar que el usuario sea gestor de precios
if ($_SESSION['rol'] !== 'gestor_precios') {
    header('Location: home.php');
    exit();
}

include 'db.php';
$mensaje = '';

// Procesar actualización de precio
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar_precio'])) {
    $producto_id = $_POST['producto_id'] ?? '';
    $nuevo_precio = $_POST['nuevo_precio'] ?? '';
    
    if ($producto_id && is_numeric($nuevo_precio) && $nuevo_precio > 0) {
        $stmt = $conn->prepare('UPDATE productos SET precio = ? WHERE id = ?');
        $stmt->bind_param('di', $nuevo_precio, $producto_id);
        if ($stmt->execute()) {
            $mensaje = 'Precio actualizado correctamente.';
        } else {
            $mensaje = 'Error al actualizar el precio.';
        }
        $stmt->close();
    } else {
        $mensaje = 'Precio inválido.';
    }
}

// Obtener productos
$productos = $conn->query('SELECT * FROM productos ORDER BY id DESC');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Precios - Importadora Larraín</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <style>
      html, body {
        height: 100%;
      }
      body {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
      }
      .main-content {
        flex: 1 0 auto;
      }
      footer {
        flex-shrink: 0;
        width: 100%;
        background: #f2f2f2;
        padding: 25px;
      }

      /* Estilos para el footer moderno */
      .py-4 {
        padding-top: 1.5rem !important;
        padding-bottom: 1.5rem !important;
      }

      .my-4 {
        margin-top: 1.5rem !important;
        margin-bottom: 1.5rem !important;
      }

      .border-top {
        border-top: 1px solid #dee2e6 !important;
      }

      .mb-1 {
        margin-bottom: 0.25rem !important;
      }

      .mt-3 {
        margin-top: 1rem !important;
      }

      .text-decoration-none {
        text-decoration: none !important;
      }

      .text-decoration-none:hover {
        text-decoration: underline !important;
      }

      .text-body-secondary {
        color: #6c757d !important;
      }

      .mb-0 {
        margin-bottom: 0 !important;
      }

      .list-unstyled {
        list-style: none;
        padding-left: 0;
      }

      .list-unstyled li {
        margin-bottom: 0.5rem;
      }

      .precio-form {
        display: flex;
        align-items: center;
        gap: 10px;
      }

      .precio-input {
        width: 120px;
      }
    </style>
</head>
<body>
<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                        
      </button>
      <a class="navbar-brand" href="#">Importadora Larrain</a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav">
        <li><a href="home.php">Inicio</a></li>
        <li><a href="catalogo.php">Catálogo</a></li>
        <li class="active"><a href="editar_precios.php">Gestión de Precios</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Cerrar sesión</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-4 main-content" style="margin-top: 30px;">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Gestión de Precios</h2>
        <div>
            <a href="catalogo.php" class="btn btn-primary">Ver Catálogo</a>
            <a href="home.php" class="btn btn-default">Volver al Inicio</a>
        </div>
    </div>
    
    <div class="alert alert-info">
        <strong>Bienvenido, <?= htmlspecialchars($_SESSION['usuario']) ?>!</strong> 
        Como gestor de precios, puedes modificar únicamente los precios de los productos existentes.
    </div>

    <?php if ($mensaje): ?>
        <div class="alert alert-success"> <?= $mensaje ?> </div>
    <?php endif; ?>

    <div class="row">
        <?php while ($prod = $productos->fetch_assoc()): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <?php if ($prod['imagen_url']): ?>
                        <img src="<?= htmlspecialchars($prod['imagen_url']) ?>" class="card-img-top" style="max-height:200px;object-fit:cover;">
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($prod['nombre']) ?></h5>
                        <p class="card-text"><?= htmlspecialchars($prod['descripcion']) ?></p>
                        
                        <form method="POST" class="precio-form">
                            <input type="hidden" name="producto_id" value="<?= $prod['id'] ?>">
                            <label for="precio_<?= $prod['id'] ?>" class="control-label">Precio:</label>
                            <div class="input-group precio-input">
                                <span class="input-group-addon">$</span>
                                <input type="number" step="0.01" min="0" class="form-control" 
                                       id="precio_<?= $prod['id'] ?>" name="nuevo_precio" 
                                       value="<?= number_format($prod['precio'], 2, '.', '') ?>" required>
                            </div>
                            <button type="submit" name="actualizar_precio" class="btn btn-warning btn-sm">
                                <span class="glyphicon glyphicon-edit"></span> Actualizar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<footer class="py-4 my-4 border-top">
  <div class="container">
    <div class="row">
      <div class="col-md-6">
        <h5>Contacto</h5>
        <p class="mb-1"><strong>Teléfono:</strong> +56 9 1234 5678</p>
        <p class="mb-1"><strong>Email:</strong> contacto@importadoralarrain.cl</p>
        <p class="mb-1"><strong>Dirección:</strong> Calle San Alfonso</p>
      </div>
      <div class="col-md-6">
        <h5>Enlaces Rápidos</h5>
        <ul class="list-unstyled">
          <li><a href="home.php" class="text-decoration-none">Inicio</a></li>
          <li><a href="catalogo.php" class="text-decoration-none">Catálogo</a></li>
          <li><a href="editar_precios.php" class="text-decoration-none">Gestión de Precios</a></li>
        </ul>
      </div>
    </div>
    <div class="row mt-3">
      <div class="col-12 text-center">
        <p class="text-body-secondary mb-0">&copy; 2024 Importadora Larraín. Todos los derechos reservados.</p>
      </div>
    </div>
  </div>
</footer>

</body>
</html> 