<?php
include 'db.php';

echo "<h2>Actualizando Base de Datos...</h2>";

try {
    // Verificar si el campo rol ya existe
    $result = $conn->query("SHOW COLUMNS FROM usuarios LIKE 'rol'");
    if ($result->num_rows > 0) {
        echo "<p style='color: green;'>✅ El campo 'rol' ya existe en la tabla usuarios.</p>";
    } else {
        // Agregar el campo rol
        $sql = "ALTER TABLE usuarios ADD COLUMN rol ENUM('admin', 'gestor_precios', 'usuario') DEFAULT 'usuario'";
        if ($conn->query($sql)) {
            echo "<p style='color: green;'>✅ Campo 'rol' agregado exitosamente.</p>";
        } else {
            echo "<p style='color: red;'>❌ Error al agregar el campo: " . $conn->error . "</p>";
        }
    }

    // Actualizar usuarios existentes como admin
    $sql = "UPDATE usuarios SET rol = 'admin' WHERE id = 1";
    if ($conn->query($sql)) {
        echo "<p style='color: green;'>✅ Usuario con ID 1 actualizado como admin.</p>";
    } else {
        echo "<p style='color: orange;'>⚠️ No se pudo actualizar el usuario 1: " . $conn->error . "</p>";
    }

    // Insertar usuario gestor de precios de ejemplo
    $gestor_password = password_hash('password', PASSWORD_DEFAULT);
    $sql = "INSERT INTO usuarios (nombre_usuario, email, password, rol) VALUES ('gestor1', 'gestor@importadoralarrain.cl', ?, 'gestor_precios')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $gestor_password);
    
    if ($stmt->execute()) {
        echo "<p style='color: green;'>✅ Usuario gestor1 creado exitosamente.</p>";
    } else {
        if ($conn->errno === 1062) {
            echo "<p style='color: orange;'>⚠️ El usuario gestor1 ya existe.</p>";
        } else {
            echo "<p style='color: red;'>❌ Error al crear gestor1: " . $conn->error . "</p>";
        }
    }
    $stmt->close();

    // Insertar usuario admin de ejemplo
    $admin_password = password_hash('password', PASSWORD_DEFAULT);
    $sql = "INSERT INTO usuarios (nombre_usuario, email, password, rol) VALUES ('admin1', 'admin@importadoralarrain.cl', ?, 'admin')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $admin_password);
    
    if ($stmt->execute()) {
        echo "<p style='color: green;'>✅ Usuario admin1 creado exitosamente.</p>";
    } else {
        if ($conn->errno === 1062) {
            echo "<p style='color: orange;'>⚠️ El usuario admin1 ya existe.</p>";
        } else {
            echo "<p style='color: red;'>❌ Error al crear admin1: " . $conn->error . "</p>";
        }
    }
    $stmt->close();

    echo "<h3>Credenciales de Prueba:</h3>";
    echo "<ul>";
    echo "<li><strong>Admin:</strong> admin1 / password</li>";
    echo "<li><strong>Gestor:</strong> gestor1 / password</li>";
    echo "</ul>";

    echo "<p><a href='login.php' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Ir al Login</a></p>";

} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}

$conn->close();
?> 