-- Script para actualizar la base de datos con el sistema de roles

-- Agregar campo rol a la tabla usuarios
ALTER TABLE usuarios ADD COLUMN rol ENUM('admin', 'gestor_precios', 'usuario') DEFAULT 'usuario';

-- Actualizar usuarios existentes como admin (asumiendo que ya hay usuarios)
UPDATE usuarios SET rol = 'admin' WHERE id = 1;

-- Insertar un usuario gestor de precios de ejemplo
INSERT INTO usuarios (nombre_usuario, email, password, rol) VALUES 
('gestor1', 'gestor@importadoralarrain.cl', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'gestor_precios');

-- Insertar un usuario admin de ejemplo
INSERT INTO usuarios (nombre_usuario, email, password, rol) VALUES 
('admin1', 'admin@importadoralarrain.cl', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Nota: La contraseña hasheada corresponde a 'password' para ambos usuarios de ejemplo 