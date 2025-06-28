# Sistema de Roles - Importadora Larraín

## Descripción
Se ha implementado un sistema de roles para la aplicación web de Importadora Larraín que permite diferentes niveles de acceso según el tipo de usuario.

## Tipos de Usuario

### 1. Administrador (admin)
- **Acceso completo**: Puede añadir, eliminar y modificar productos
- **Funcionalidades**:
  - Ver catálogo completo
  - Añadir nuevos productos
  - **Eliminar productos existentes** (con confirmación)
  - Modificar productos existentes
  - Gestión total del inventario

### 2. Gestor de Precios (gestor_precios)
- **Acceso limitado**: Solo puede modificar precios de productos existentes
- **Funcionalidades**:
  - Ver catálogo completo (solo lectura)
  - Modificar únicamente los precios de productos
  - No puede añadir ni eliminar productos
  - Acceso a página especial "Gestión de Precios"

### 3. Usuario (usuario)
- **Acceso básico**: Solo puede ver el catálogo
- **Funcionalidades**:
  - Ver catálogo completo (solo lectura)
  - No puede modificar ningún dato

## Instalación y Configuración

### 1. Actualizar la Base de Datos
Ejecuta el script SQL `actualizar_db.sql` en tu base de datos MySQL:

```sql
-- Agregar campo rol a la tabla usuarios
ALTER TABLE usuarios ADD COLUMN rol ENUM('admin', 'gestor_precios', 'usuario') DEFAULT 'usuario';

-- Actualizar usuarios existentes como admin
UPDATE usuarios SET rol = 'admin' WHERE id = 1;

-- Insertar usuarios de ejemplo
INSERT INTO usuarios (nombre_usuario, email, password, rol) VALUES 
('gestor1', 'gestor@importadoralarrain.cl', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'gestor_precios'),
('admin1', 'admin@importadoralarrain.cl', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');
```

### 2. Credenciales de Prueba
- **Usuario Admin**: `admin1` / `password`
- **Usuario Gestor**: `gestor1` / `password`

## Archivos Modificados

### 1. `login.php`
- Agregado campo `rol` en la consulta de autenticación
- El rol se almacena en la sesión al iniciar sesión

### 2. `registro.php`
- Agregado campo `rol` con valor por defecto 'usuario'
- Los nuevos usuarios registrados tienen rol 'usuario'

### 3. `catalogo.php`
- Verificación de rol para mostrar formulario de añadir productos
- **NUEVO**: Funcionalidad de eliminación de productos para administradores
- **NUEVO**: Modal de confirmación antes de eliminar
- **NUEVO**: Eliminación automática de imágenes asociadas
- Solo administradores pueden añadir y eliminar productos
- Gestores de precios ven enlace a "Gestión de Precios"
- Mensajes informativos según el rol

### 4. `home.php`
- Panel de información de usuario con rol
- Enlaces dinámicos según el rol
- Badges visuales para identificar el tipo de usuario

### 5. `editar_precios.php` (NUEVO)
- Página exclusiva para gestores de precios
- Formularios para modificar solo precios
- Verificación de permisos
- Interfaz optimizada para gestión de precios

## Características de Seguridad

### 1. Verificación de Sesión
- Todas las páginas verifican que el usuario esté autenticado
- Redirección automática a login si no hay sesión

### 2. Verificación de Roles
- Cada página verifica el rol del usuario antes de mostrar funcionalidades
- Acceso denegado si el usuario no tiene permisos

### 3. Protección de Formularios
- Los formularios solo se procesan si el usuario tiene el rol correcto
- Validación de datos en el servidor

### 4. **NUEVO**: Confirmación de Eliminación
- Modal de confirmación antes de eliminar productos
- Prevención de eliminaciones accidentales
- Eliminación automática de archivos de imagen asociados

## Navegación por Roles

### Administrador
- **Navbar**: Inicio, Catálogo
- **Catálogo**: Formulario de añadir productos + lista de productos + botones de eliminar
- **Home**: Panel de usuario con badge "Administrador"
- **Funcionalidades**: Añadir, eliminar y ver productos

### Gestor de Precios
- **Navbar**: Inicio, Catálogo, Gestión de Precios
- **Catálogo**: Solo lista de productos (sin formulario de añadir)
- **Gestión de Precios**: Página especial para modificar precios
- **Home**: Panel de usuario con badge "Gestor de Precios"

### Usuario
- **Navbar**: Inicio, Catálogo
- **Catálogo**: Solo lista de productos (sin formulario de añadir)
- **Home**: Panel de usuario con badge "Usuario"

## Funcionalidades de Eliminación

### Características de Seguridad
- **Confirmación obligatoria**: Modal que requiere confirmación antes de eliminar
- **Eliminación de archivos**: Las imágenes asociadas se eliminan automáticamente del servidor
- **Validación de permisos**: Solo administradores pueden eliminar productos
- **Mensajes informativos**: Feedback claro sobre el resultado de la operación

### Interfaz de Usuario
- **Botón rojo**: Botón "Eliminar" claramente identificable
- **Modal de confirmación**: Muestra el nombre del producto a eliminar
- **Advertencia**: Mensaje claro de que la acción no se puede deshacer
- **Opción de cancelar**: Posibilidad de cancelar la operación

## Personalización

### Agregar Nuevos Roles
1. Modificar el ENUM en la base de datos
2. Actualizar las verificaciones en los archivos PHP
3. Agregar estilos CSS para nuevos badges
4. Actualizar la lógica de navegación

### Modificar Permisos
- Editar las condiciones `if ($_SESSION['rol'] === 'rol_especifico')` en los archivos correspondientes
- Actualizar la lógica de verificación según necesidades

## Notas Importantes

1. **Contraseñas**: Los usuarios de ejemplo usan la contraseña 'password' hasheada
2. **Sesiones**: El sistema usa sesiones PHP para mantener el estado del usuario
3. **Bootstrap**: La interfaz usa Bootstrap 3.4.1 para mantener consistencia
4. **Responsive**: Todas las páginas son responsivas y funcionan en móviles
5. **Eliminación segura**: Los productos se eliminan con confirmación y limpieza de archivos

## Solución de Problemas

### Error de Base de Datos
- Verificar que el campo `rol` existe en la tabla `usuarios`
- Ejecutar el script SQL completo

### Problemas de Sesión
- Verificar que las sesiones PHP están habilitadas
- Limpiar cookies del navegador si es necesario

### Permisos Incorrectos
- Verificar que el rol está correctamente asignado en la base de datos
- Comprobar que la sesión contiene el rol correcto

### Problemas de Eliminación
- Verificar permisos de escritura en la carpeta `uploads/`
- Comprobar que el usuario tiene permisos de administrador
- Revisar logs de errores de PHP si la eliminación falla 