# Proyecto Web: mi_app

## Requisitos
- XAMPP (PHP, MySQL)
- Navegador web

## Configuración de la base de datos
1. Inicia XAMPP y asegúrate de que Apache y MySQL estén activos.
2. Accede a phpMyAdmin (`http://localhost/phpmyadmin`).
3. Ejecuta el siguiente script SQL para crear la base de datos y las tablas:

```sql
CREATE DATABASE IF NOT EXISTS mi_app;
USE mi_app;

CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_usuario VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10,2) NOT NULL,
    imagen_url VARCHAR(255)
);
```

## Instalación
1. Copia todos los archivos del proyecto en la carpeta `htdocs` de XAMPP (por ejemplo: `C:/xampp/htdocs/mi_app`).
2. Accede a `http://localhost/mi_app` desde tu navegador.

## Estructura del proyecto
- `login.php`: Página de inicio de sesión
- `registro.php`: Página de registro de usuarios
- `home.php`: Página de inicio (requiere sesión)
- `catalogo.php`: Catálogo de productos (requiere sesión)
- `logout.php`: Cerrar sesión
- `db.php`: Conexión a la base de datos
- `uploads/`: Carpeta para imágenes de productos

## Notas
- Las contraseñas se almacenan cifradas.
- Se usan sesiones para el manejo de usuarios.
- Compatible con XAMPP en entorno local. 