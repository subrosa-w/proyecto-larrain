<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}
include 'db.php';
$mensaje = '';
// Procesar formulario de nuevo producto
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Catálogo de Productos</h2>
        <a href="home.php" class="btn btn-secondary">Volver al Inicio</a>
    </div>
    <?php if ($mensaje): ?>
        <div class="alert alert-info"> <?= $mensaje ?> </div>
    <?php endif; ?>
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
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>
</body>
</html> 