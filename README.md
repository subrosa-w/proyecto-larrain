# 🏢 Importadora Larraín - Sistema de Gestión Web

## 📋 Descripción

Sistema web completo para la gestión de productos de Importadora Larraín, una empresa con 15 años de experiencia en la importación y venta de accesorios electrónicos desde China. La aplicación permite gestionar un catálogo de productos con diferentes niveles de acceso según el rol del usuario.

## ✨ Características Principales

### 🔐 Sistema de Roles y Permisos
- **Administradores**: Control total del catálogo (añadir, eliminar, modificar productos)
- **Gestores de Precios**: Solo pueden modificar precios de productos existentes
- **Usuarios**: Acceso de solo lectura al catálogo

### 📱 Interfaz Moderna y Responsiva
- Diseño adaptativo que funciona en desktop, tablet y móvil
- Bootstrap 3.4.1 para una experiencia visual consistente
- Carrusel de imágenes con información de la empresa
- Panel de información de usuario con badges de rol

### 🛍️ Gestión de Productos
- Catálogo visual con imágenes de productos
- Formularios intuitivos para añadir productos
- Eliminación segura con confirmación modal
- Gestión de precios especializada
- Subida y gestión automática de imágenes

## 🚀 Instalación y Configuración

### Requisitos Previos
- **Servidor Web**: Apache (XAMPP, WAMP, o similar)
- **PHP**: Versión 7.0 o superior
- **MySQL**: Versión 5.6 o superior
- **Navegador**: Chrome, Firefox, Safari, Edge (versiones recientes)

### Pasos de Instalación

#### 1. Clonar el Repositorio
```bash
git clone https://github.com/tu-usuario/importadora-larrain.git
cd importadora-larrain
```

#### 2. Configurar la Base de Datos
1. Crea una base de datos MySQL llamada `mi_app`
2. Importa la estructura de la base de datos (archivo SQL incluido)
3. Ejecuta el script de configuración de roles

#### 3. Configurar la Conexión
Edita el archivo `db.php` con tus credenciales de base de datos:
```php
$host = 'localhost';
$user = 'tu_usuario';
$pass = 'tu_contraseña';
$db = 'mi_app';
```

#### 4. Configurar Permisos
Asegúrate de que la carpeta `uploads/` tenga permisos de escritura:
```bash
chmod 755 uploads/
```

#### 5. Ejecutar Configuración Inicial
Ve a tu navegador y ejecuta:
```
http://localhost/importadora-larrain/ejecutar_sql.php
```

## 👥 Tipos de Usuario y Funcionalidades

### 🔴 Administrador
**Credenciales de prueba**: `admin1` / `password`

**Funcionalidades**:
- ✅ Ver catálogo completo
- ✅ Añadir nuevos productos
- ✅ Eliminar productos (con confirmación)
- ✅ Modificar productos existentes
- ✅ Gestión total del inventario

**Interfaz**:
- Formulario de añadir productos visible
- Botones de eliminar en cada producto
- Panel de administrador en la página de inicio

### 🟡 Gestor de Precios
**Credenciales de prueba**: `gestor1` / `password`

**Funcionalidades**:
- ✅ Ver catálogo completo (solo lectura)
- ✅ Modificar únicamente precios de productos
- ❌ No puede añadir productos
- ❌ No puede eliminar productos

**Interfaz**:
- Enlace a "Gestión de Precios" en el menú
- Página especializada para modificar precios
- Formularios individuales por producto

### 🔵 Usuario
**Funcionalidades**:
- ✅ Ver catálogo completo (solo lectura)
- ❌ No puede modificar ningún dato

**Interfaz**:
- Vista limpia del catálogo
- Sin opciones de edición

## 📁 Estructura del Proyecto

```
importadora-larrain/
├── 📄 home.php              # Página principal con carrusel y información
├── 📄 catalogo.php          # Gestión de productos (según rol)
├── 📄 editar_precios.php    # Página especial para gestores de precios
├── 📄 login.php             # Sistema de autenticación
├── 📄 registro.php          # Registro de nuevos usuarios
├── 📄 logout.php            # Cerrar sesión
├── 📄 db.php                # Configuración de base de datos
├── 📁 imagenes/             # Imágenes del carrusel y estáticas
├── 📁 uploads/              # Imágenes subidas por usuarios
├── 📄 ejecutar_sql.php      # Script de configuración inicial
├── 📄 verificar_estado.php  # Verificación del estado de la BD
├── 📄 actualizar_db.sql     # Script SQL para configuración
├── 📄 README_ROLES.md       # Documentación técnica del sistema de roles
└── 📄 README.md             # Este archivo
```

## 🎯 Casos de Uso Recomendados

### Para Administradores
1. **Gestión Diaria**: Revisar y actualizar el catálogo de productos
2. **Añadir Productos**: Usar el formulario en la página de catálogo
3. **Eliminar Productos**: Usar el botón rojo con confirmación
4. **Monitoreo**: Revisar el panel de información de usuario

### Para Gestores de Precios
1. **Actualización de Precios**: Usar la página "Gestión de Precios"
2. **Revisión de Catálogo**: Verificar productos en el catálogo
3. **Comunicación**: Coordinar cambios con administradores

### Para Usuarios
1. **Consulta de Productos**: Navegar por el catálogo
2. **Información de Contacto**: Usar los datos en el footer

## 🔧 Personalización

### Cambiar Información de la Empresa
Edita los siguientes archivos:
- `home.php`: Información del carrusel y sección "¿Quiénes somos?"
- Footer en todos los archivos: Datos de contacto

### Agregar Nuevos Roles
1. Modificar el ENUM en la base de datos
2. Actualizar verificaciones en archivos PHP
3. Agregar estilos CSS para nuevos badges
4. Actualizar lógica de navegación

### Modificar Estilos
- Los estilos están en cada archivo PHP
- Usa Bootstrap 3.4.1 para mantener consistencia
- Modifica las clases CSS según necesidades

## 🛡️ Seguridad

### Características Implementadas
- ✅ Verificación de sesiones en todas las páginas
- ✅ Validación de roles antes de mostrar funcionalidades
- ✅ Protección de formularios según permisos
- ✅ Confirmación obligatoria para eliminaciones
- ✅ Validación de datos en el servidor
- ✅ Escape de HTML para prevenir XSS

### Recomendaciones de Seguridad
1. **Cambiar contraseñas por defecto** después de la instalación
2. **Usar HTTPS** en producción
3. **Configurar firewall** del servidor
4. **Hacer backups regulares** de la base de datos
5. **Mantener PHP actualizado**

## 📊 Optimización y Rendimiento

### Recomendaciones para Uso Óptimo
1. **Imágenes**: Usar formatos optimizados (WebP, JPEG comprimido)
2. **Base de Datos**: Crear índices en campos de búsqueda frecuente
3. **Caché**: Implementar caché de consultas si el catálogo es grande
4. **CDN**: Usar CDN para imágenes en producción

### Mantenimiento
- Revisar logs de errores regularmente
- Limpiar archivos de imagen no utilizados
- Actualizar dependencias cuando sea necesario
- Hacer backups automáticos de la base de datos

## 🐛 Solución de Problemas

### Error de Conexión a Base de Datos
- Verificar credenciales en `db.php`
- Comprobar que MySQL esté ejecutándose
- Verificar permisos de usuario de base de datos

### Error de Permisos
- Verificar permisos de escritura en carpeta `uploads/`
- Comprobar permisos de archivos PHP

### Problemas de Sesión
- Verificar configuración de PHP para sesiones
- Limpiar cookies del navegador
- Comprobar configuración de timezone

### Error de Roles
- Ejecutar `verificar_estado.php` para diagnosticar
- Verificar que el campo `rol` existe en la tabla `usuarios`
- Comprobar que los usuarios tienen roles asignados

## 📞 Soporte

### Antes de Reportar un Problema
1. Verificar que cumples los requisitos del sistema
2. Revisar la sección de solución de problemas
3. Ejecutar `verificar_estado.php` para diagnóstico
4. Revisar logs de errores de PHP

### Información Útil para Reportes
- Versión de PHP
- Versión de MySQL
- Navegador y versión
- Pasos para reproducir el problema
- Capturas de pantalla del error

## 📄 Licencia

Este proyecto está desarrollado para Importadora Larraín. Todos los derechos reservados.

## 🤝 Contribuciones

Para contribuir al proyecto:
1. Fork el repositorio
2. Crea una rama para tu feature
3. Haz commit de tus cambios
4. Push a la rama
5. Abre un Pull Request

---

**Desarrollado con ❤️ para Importadora Larraín**

*Sistema web moderno y seguro para la gestión de productos electrónicos*
