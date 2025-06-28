<?php
include 'db.php';

echo "<h2>Estado Actual de la Base de Datos</h2>";

try {
    // Verificar si el campo rol existe
    $result = $conn->query("SHOW COLUMNS FROM usuarios LIKE 'rol'");
    if ($result->num_rows > 0) {
        echo "<p style='color: green;'>✅ El campo 'rol' existe en la tabla usuarios.</p>";
    } else {
        echo "<p style='color: red;'>❌ El campo 'rol' NO existe en la tabla usuarios.</p>";
    }

    // Mostrar todos los usuarios existentes
    echo "<h3>Usuarios Existentes:</h3>";
    $usuarios = $conn->query("SELECT id, nombre_usuario, email, rol FROM usuarios ORDER BY id");
    
    if ($usuarios->num_rows > 0) {
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr style='background: #f0f0f0;'>";
        echo "<th>ID</th><th>Usuario</th><th>Email</th><th>Rol</th>";
        echo "</tr>";
        
        while ($usuario = $usuarios->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $usuario['id'] . "</td>";
            echo "<td>" . htmlspecialchars($usuario['nombre_usuario']) . "</td>";
            echo "<td>" . htmlspecialchars($usuario['email']) . "</td>";
            echo "<td>" . htmlspecialchars($usuario['rol']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No hay usuarios en la base de datos.</p>";
    }

    // Verificar si necesitamos crear usuarios de ejemplo
    $gestor = $conn->query("SELECT id FROM usuarios WHERE nombre_usuario = 'gestor1'");
    $admin = $conn->query("SELECT id FROM usuarios WHERE nombre_usuario = 'admin1'");
    
    echo "<h3>Estado de Usuarios de Prueba:</h3>";
    
    if ($gestor->num_rows > 0) {
        echo "<p style='color: green;'>✅ Usuario gestor1 ya existe.</p>";
    } else {
        echo "<p style='color: orange;'>⚠️ Usuario gestor1 no existe.</p>";
    }
    
    if ($admin->num_rows > 0) {
        echo "<p style='color: green;'>✅ Usuario admin1 ya existe.</p>";
    } else {
        echo "<p style='color: orange;'>⚠️ Usuario admin1 no existe.</p>";
    }

    // Mostrar credenciales disponibles
    echo "<h3>Credenciales Disponibles:</h3>";
    echo "<ul>";
    
    $usuarios_con_rol = $conn->query("SELECT nombre_usuario, rol FROM usuarios WHERE rol IN ('admin', 'gestor_precios')");
    while ($user = $usuarios_con_rol->fetch_assoc()) {
        echo "<li><strong>" . htmlspecialchars($user['nombre_usuario']) . "</strong> (Rol: " . htmlspecialchars($user['rol']) . ") - Contraseña: <strong>password</strong></li>";
    }
    
    echo "</ul>";

    echo "<p><a href='login.php' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Ir al Login</a></p>";

} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}

$conn->close();
?> 