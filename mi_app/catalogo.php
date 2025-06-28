<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}
include 'db.php';
$mensaje = '';

// Procesar eliminación de producto (solo admin)
if ($_SESSION['rol'] === 'admin' && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_producto'])) {
    $producto_id = $_POST['producto_id'] ?? '';
    
    if ($producto_id && is_numeric($producto_id)) {
        // Obtener información de la imagen antes de eliminar
        $stmt = $conn->prepare('SELECT imagen_url FROM productos WHERE id = ?');
        $stmt->bind_param('i', $producto_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $producto = $result->fetch_assoc();
        $stmt->close();
        
        // Eliminar el producto
        $stmt = $conn->prepare('DELETE FROM productos WHERE id = ?');
        $stmt->bind_param('i', $producto_id);
        if ($stmt->execute()) {
            // Eliminar la imagen del servidor si existe
            if ($producto && $producto['imagen_url'] && file_exists($producto['imagen_url'])) {
                unlink($producto['imagen_url']);
            }
            $mensaje = 'Producto eliminado correctamente.';
        } else {
            $mensaje = 'Error al eliminar el producto.';
        }
        $stmt->close();
    } else {
        $mensaje = 'ID de producto inválido.';
    }
}

// Solo los administradores pueden añadir productos
if ($_SESSION['rol'] === 'admin' && $_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['eliminar_producto'])) {
    $nombre = trim($_POST['nombre'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $precio = $_POST['precio'] ?? '';
    $imagen_url = '';
    if ($nombre && $precio && is_numeric($precio)) {
        // Subida de imagen
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
            $nombre_archivo = uniqid('img_') . '.' . $ext;
            $ruta = 'uploads/' . $nombre_archivo;
            if (move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta)) {
                $imagen_url = $ruta;
            } else {
                $mensaje = 'Error al subir la imagen.';
            }
        }
        if ($mensaje === '') {
            $stmt = $conn->prepare('INSERT INTO productos (nombre, descripcion, precio, imagen_url) VALUES (?, ?, ?, ?)');
            $stmt->bind_param('ssds', $nombre, $descripcion, $precio, $imagen_url);
            if ($stmt->execute()) {
                $mensaje = 'Producto añadido correctamente.';
            } else {
                $mensaje = 'Error al guardar el producto.';
            }
            $stmt->close();
        }
    } else {
        $mensaje = 'Nombre y precio son obligatorios. El precio debe ser numérico.';
    }
}

// Obtener productos
$productos = $conn->query('SELECT * FROM productos ORDER BY id DESC');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Catálogo de Productos</title>
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

      /* Estilos para botones de acción */
      .action-buttons {
        margin-top: 10px;
      }

      .btn-eliminar {
        background-color: #d9534f;
        border-color: #d43f3a;
        color: white;
      }

      .btn-eliminar:hover {
        background-color: #c9302c;
        border-color: #ac2925;
        color: white;
      }

      /* Modal de confirmación */
      .modal-header {
        background-color: #d9534f;
        color: white;
      }

      .modal-header .close {
        color: white;
        opacity: 0.8;
      }

      .modal-header .close:hover {
        opacity: 1;
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
        <li class="active"><a href="catalogo.php">Catálogo</a></li>
        <?php if ($_SESSION['rol'] === 'gestor_precios'): ?>
        <li><a href="editar_precios.php">Gestión de Precios</a></li>
        <?php endif; ?>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Cerrar sesión</a></li>
      </ul>
    </div>
  </div>
</nav>
<div class="container mt-4 main-content" style="margin-top: 30px;">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Catálogo de Productos</h2>
        <div>
            <?php if ($_SESSION['rol'] === 'gestor_precios'): ?>
                <a href="editar_precios.php" class="btn btn-warning">Gestión de Precios</a>
            <?php endif; ?>
            <a href="home.php" class="btn btn-primary">Volver al Inicio</a>
        </div>
    </div>
    
    <?php if ($_SESSION['rol'] === 'gestor_precios'): ?>
        <div class="alert alert-info">
            <strong>Bienvenido, <?= htmlspecialchars($_SESSION['usuario']) ?>!</strong> 
            Como gestor de precios, puedes ver todos los productos. Para modificar precios, usa el botón "Gestión de Precios".
        </div>
    <?php endif; ?>
    
    <?php if ($mensaje): ?>
        <div class="alert alert-info"> <?= $mensaje ?> </div>
    <?php endif; ?>
    
    <?php if ($_SESSION['rol'] === 'admin'): ?>
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Añadir Producto</h5>
            <form method="POST" enctype="multipart/form-data">
                <div class="row g-2">
                    <div class="col-md-3">
                        <input type="text" name="nombre" class="form-control" placeholder="Nombre" required>
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="descripcion" class="form-control" placeholder="Descripción">
                    </div>
                    <div class="col-md-2">
                        <input type="number" step="0.01" name="precio" class="form-control" placeholder="Precio" required>
                    </div>
                    <div class="col-md-2">
                        <input type="file" name="imagen" class="form-control">
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-success w-100">Añadir</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
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
                        <p class="card-text fw-bold">$<?= number_format($prod['precio'], 2) ?></p>
                        
                        <?php if ($_SESSION['rol'] === 'admin'): ?>
                        <div class="action-buttons">
                            <button type="button" class="btn btn-danger btn-sm btn-eliminar" 
                                    onclick="confirmarEliminacion(<?= $prod['id'] ?>, '<?= htmlspecialchars($prod['nombre']) ?>')">
                                <span class="glyphicon glyphicon-trash"></span> Eliminar
                            </button>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<!-- Modal de confirmación para eliminar -->
<div class="modal fade" id="modalEliminar" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title">Confirmar Eliminación</h4>
      </div>
      <div class="modal-body">
        <p>¿Estás seguro de que quieres eliminar el producto "<strong id="nombreProducto"></strong>"?</p>
        <p class="text-danger"><strong>Esta acción no se puede deshacer.</strong></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <form method="POST" style="display: inline;">
          <input type="hidden" name="producto_id" id="productoId">
          <button type="submit" name="eliminar_producto" class="btn btn-danger">Eliminar</button>
        </form>
      </div>
    </div>
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
          <?php if ($_SESSION['rol'] === 'gestor_precios'): ?>
          <li><a href="editar_precios.php" class="text-decoration-none">Gestión de Precios</a></li>
          <?php endif; ?>
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

<script>
function confirmarEliminacion(productoId, nombreProducto) {
    document.getElementById('productoId').value = productoId;
    document.getElementById('nombreProducto').textContent = nombreProducto;
    $('#modalEliminar').modal('show');
}
</script>

</body>
</html> 