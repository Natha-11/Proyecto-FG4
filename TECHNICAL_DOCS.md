# DOCUMENTACIÓN TÉCNICA - GLOW BELLEZA

## Estructura del Proyecto

```
Proyecto_final6t0/
├── index.php                    # Página principal
├── login.php                    # Login unificado (clientes + admins)
├── registro.php                 # Registro de clientes
├── auth_login.php              # Procesador de login
├── auth_register.php           # Procesador de registro
├── logout.php                  # Logout
│
├── admin_register.php          # Registro de admins (legacy)
├── auth_admin_register.php     # Procesador de registro admin (legacy)
├── admin_dashboard.php         # Panel de administración
│
├── perfil.php                  # Perfil de usuario
├── invoice.php                 # Visualización de facturas
│
├── evaluacion_facial.php       # Análisis facial (servicio)
├── encuesta_piel.php           # Test de tipo de piel
├── smart_beauty.php            # Página de información (servicios adicionales)
├── manual.php                  # Manual técnico
│
├── API (endpoints JSON)
│   ├── api_reservations.php    # Obtener reservas
│   ├── api_availability.php    # Verificar disponibilidad
│   ├── api_create_invoice.php  # Crear factura
│
├── Integración n8n
│   ├── n8n_send_data.php       # Enviar datos a n8n
│   ├── n8n_webhook_handler.php # Recibir datos de n8n
│   ├── n8n_setup_guide_es.md   # Guía de configuración
│   └── n8n_ultimate_workflow.json # Workflow de n8n
│
├── Setup y Configuración
│   ├── setup_invoices.php      # Crear tabla de facturas
│   ├── setup_n8n_tables.php    # Crear tablas de n8n
│   ├── setup_registro.sql      # Dump SQL de esquema
│   └── conexion.php            # Configuración de BD
│
├── Recursos
│   ├── style.css               # CSS principal
│   ├── script.js               # JavaScript global
│   ├── logo.png                # Logo
│   ├── uploads/                # Imágenes de servicios
│   │   ├── imagen1.jpg        # Hero image
│   │   ├── imagen2.jpg        # Galería
│   │   ├── imagen3.jpg        # Galería
│   │   ├── imagen4.jpg        # Galería
│   │   ├── bridal.jpg         # Servicio: Bridal
│   │   ├── Cejas.jpg          # Servicio: Diseño de Cejas
│   │   ├── pestañas.jpg       # Servicio: Pestañas Premium
│   │   ├── limpiezaF.jpeg     # Servicio: Limpieza Facial
│   │   └── .htaccess          # Configuración Apache
│   │
│   └── resources/              # Archivos Laravel (no utilizados)
│
├── scratch/                    # Archivos de prueba/debugging
│   ├── create_admin.php
│   ├── check_db.php
│   └── check_admin_schema.php
│
├── Configuración
│   ├── .gitignore             # Git ignore rules
│   ├── composer.json          # Dependencias PHP
│   ├── package.json           # Dependencias Node (parcial)
│   ├── CHANGELOG.md           # Historial de cambios
│   └── README.md              # Documentación general
```

## Base de Datos (MySQL)

**Nombre de BD:** `registro`

### Tablas Principales

#### `clientes`
- id (INT, PRIMARY KEY, AUTO_INCREMENT)
- nombre (VARCHAR 150)
- email (VARCHAR 100, UNIQUE)
- telefono (VARCHAR 20)
- password (VARCHAR 255, hashed)

#### `admins`
- id (INT, PRIMARY KEY, AUTO_INCREMENT)
- username (VARCHAR 100, UNIQUE)
- email (VARCHAR 100)
- password (VARCHAR 255, hashed)
- ultimo_acceso (TIMESTAMP)

#### `servicios`
- id (INT, PRIMARY KEY, AUTO_INCREMENT)
- nombre (VARCHAR 100)
- precio (DECIMAL 10,2)
- imagen (VARCHAR 255)
- categoria (VARCHAR 50)
- activo (TINYINT, default 1)

#### `reservas`
- id (INT, PRIMARY KEY, AUTO_INCREMENT)
- cliente_id (INT, FOREIGN KEY)
- nombre_cliente (VARCHAR 150)
- telefono (VARCHAR 20)
- servicio (VARCHAR 100)
- fecha (DATE)
- hora (VARCHAR 10)

#### `facturas`
- id (INT, PRIMARY KEY, AUTO_INCREMENT)
- cliente_id (INT, FOREIGN KEY)
- nro_factura (VARCHAR 20, UNIQUE)
- total (DECIMAL 10,2)
- metodo_pago (VARCHAR 50)
- fecha (DATETIME)

#### `factura_items`
- id (INT, PRIMARY KEY, AUTO_INCREMENT)
- factura_id (INT, FOREIGN KEY)
- producto (VARCHAR 100)
- precio (DECIMAL 10,2)

### Tablas Adicionales (para n8n)

- `n8n_logs` - Registros de integraciones con n8n
- `notificaciones` - Historial de notificaciones
- `estadisticas_cache` - Cache de estadísticas
- `usuario` / `usuarios` - Legacy/adicional

## Autenticación

### Sistema Unificado
- Un único login en `login.php` para clientes y administradores
- Los admins se registran a través de `admin_register.php` o sistema interno
- Soporte para contraseñas en texto plano (migración automática a hash)
- Sesiones PHP con `$_SESSION`

### Sesiones de Cliente
```php
$_SESSION['user_id']    // ID del cliente
$_SESSION['user_name']  // Nombre completo
$_SESSION['user_email'] // Email
$_SESSION['user_phone'] // Teléfono
```

### Sesiones de Admin
```php
$_SESSION['admin_id']   // ID del admin
$_SESSION['admin_user'] // Usuario/nombre
```

## API Endpoints

### `/api_reservations.php`
**Método:** GET  
**Retorna:** JSON de todas las reservas (futuras)

### `/api_availability.php`
**Método:** GET  
**Params:** `fecha=YYYY-MM-DD`  
**Retorna:** JSON de horas disponibles para esa fecha

### `/api_create_invoice.php`
**Método:** POST  
**Body:** `{cart: [...], total: X, metodo_pago: "..."}`  
**Retorna:** JSON con ID de factura y detalles

## Rutas de Imágenes

### XAMPP/Windows
```
/uploads/imagen.jpg          → OK
uploads/imagen.jpg           → OK  
./uploads/imagen.jpg         → OK
```

### Ubuntu/Apache
```
/var/www/html/uploads/       → Ruta absoluta del servidor
/Proyecto_final6t0/uploads/  → Ruta relativa desde raíz web
```

### Configuración Recomendada para Ubuntu
```apache
<VirtualHost *:80>
    ServerName belleza.local
    DocumentRoot /var/www/html
    
    <Directory /var/www/html/Proyecto_final6t0>
        Options FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

## Instalación en Ubuntu

### 1. Requisitos
```bash
sudo apt-get update
sudo apt-get install apache2 php php-mysql php-curl php-json
sudo systemctl start apache2
```

### 2. Descargar el Proyecto
```bash
cd /var/www/html
sudo git clone https://github.com/Natha-11/Proyecto-FG4.git
cd Proyecto-FG4 && git checkout Nathalia
sudo chown -R www-data:www-data .
sudo chmod -R 755 .
sudo chmod -R 777 uploads/
```

### 3. Base de Datos
```bash
mysql -u root -p < setup_registro.sql
```

### 4. Configurar conexion.php
Para Ubuntu con MySQL estándar:
```php
$host = "localhost";
$user = "root";      // O el usuario configurado
$pass = "";          // O la contraseña si la hay
$db = "registro";
```

### 5. Permisos
```bash
sudo chmod 644 *.php
sudo chmod 755 uploads/
sudo chmod 644 uploads/*
```

### 6. Verificar
```
http://localhost/Proyecto-FG4/index.php
```

## Integración n8n

Ver archivo: `n8n_setup_guide_es.md` para detalles completos.

## Cambios Realizados en Limpieza

### Archivos Eliminados
- ✓ old_index.php (duplicado)
- ✓ temp_script.php (prueba)
- ✓ test_db.php (prueba)
- ✓ fix_db.php (script de mantenimiento)
- ✓ unnamed.png (imagen sin usar)
- ✓ phone-icon.svg (ícono sin usar)
- ✓ tests/ (directorio completo)
- ✓ vite.config.js (Laravel Vite, no necesario)
- ✓ phpunit.xml (Laravel PHPUnit, no necesario)

### Errores Corregidos
- ✓ updateCartUI() undefined en index.php (comentado)
- ✓ Rutas de imágenes en style.css (uploads/imagen1.jpg)
- ✓ .gitignore actualizado para permitir uploads/

### Verificaciones Realizadas
- ✓ Base de datos: Todas las tablas y columnas existen
- ✓ Autenticación: Sistema unificado funcionando
- ✓ Imágenes: Todas en uploads/ correctamente
- ✓ API endpoints: Configurados correctamente
- ✓ Rutas: Relativas y compatibles con Windows/Ubuntu

## Notas de Seguridad

1. **Contraseñas**: Todas hasheadas con PASSWORD_DEFAULT
2. **SQL Injection**: Se usan prepared statements
3. **XSS**: Se usa htmlspecialchars() en salidas
4. **CORS**: n8n webhook permite CORS (verificar en producción)
5. **HTTPS**: Recomendado para producción

## Próximos Pasos

1. Configurar dominio en producción
2. Instalar SSL certificate
3. Configurar email para notificaciones
4. Sincronizar con n8n production
5. Realizar backups automáticos
6. Configurar logs y monitoreo

---
**Última actualización:** Mayo 2026
**Versión:** 1.0.0 (Stable)
**Estado:** Listo para producción Ubuntu
