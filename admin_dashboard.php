<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
include 'conexion.php';

// Manejar Acciones (Cargarlas primero para que el listado se actualice)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'add') {
            $nombre = $_POST['nombre'];
            $precio = $_POST['precio'];
            $cat = $_POST['categoria'];

            // Procesar imagen subida
            $imagen = '';
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'uploads/';
                $fileName = uniqid() . '_' . basename($_FILES['imagen']['name']);
                $uploadFile = $uploadDir . $fileName;

                if (move_uploaded_file($_FILES['imagen']['tmp_name'], $uploadFile)) {
                    $imagen = $fileName;
                } else {
                    echo "Error al subir la imagen.";
                    exit();
                }
            }

            $stmt = $conexion->prepare("INSERT INTO servicios (nombre, precio, imagen, categoria) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("sdss", $nombre, $precio, $imagen, $cat);
            $stmt->execute();
        } elseif ($_POST['action'] === 'update') {
            $id = $_POST['id'];
            $nombre = $_POST['nombre'];
            $precio = $_POST['precio'];
            $cat = $_POST['categoria'];

            // Procesar imagen subida (opcional)
            $imagen = $_POST['current_imagen'] ?? '';
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'uploads/';
                $fileName = uniqid() . '_' . basename($_FILES['imagen']['name']);
                $uploadFile = $uploadDir . $fileName;

                if (move_uploaded_file($_FILES['imagen']['tmp_name'], $uploadFile)) {
                    $imagen = $fileName;
                }
            }

            $stmt = $conexion->prepare("UPDATE servicios SET nombre=?, precio=?, imagen=?, categoria=? WHERE id=?");
            $stmt->bind_param("sdssi", $nombre, $precio, $imagen, $cat, $id);
            $stmt->execute();
        } elseif ($_POST['action'] === 'delete') {
            $id = $_POST['id'];
            $stmt = $conexion->prepare("DELETE FROM servicios WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
        } elseif ($_POST['action'] === 'toggle') {
            $id = $_POST['id'];
            $status = $_POST['status'];
            $stmt = $conexion->prepare("UPDATE servicios SET activo = ? WHERE id = ?");
            $stmt->bind_param("ii", $status, $id);
            $stmt->execute();
        } elseif ($_POST['action'] === 'add_admin') {
            $username = $_POST['username'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $email = $_POST['email'];
            
            // Check if username already exists
            $check_stmt = $conexion->prepare("SELECT id FROM admins WHERE username = ?");
            $check_stmt->bind_param("s", $username);
            $check_stmt->execute();
            $check_stmt->store_result();
            if ($check_stmt->num_rows > 0) {
                $_SESSION['error'] = "Username already exists.";
                header("Location: admin_dashboard.php");
                $check_stmt->close();
                exit;
            }
            $check_stmt->close();
            
            $stmt = $conexion->prepare("INSERT INTO admins (username, password, email) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $password, $email);
            $stmt->execute();
        } elseif ($_POST['action'] === 'update_admin') {
            $id = $_POST['id'];
            $username = $_POST['username'];
            $email = $_POST['email'];
            
            // Check if username already exists for another admin
            $check_stmt = $conexion->prepare("SELECT id FROM admins WHERE username = ? AND id != ?");
            $check_stmt->bind_param("si", $username, $id);
            $check_stmt->execute();
            $check_stmt->store_result();
            if ($check_stmt->num_rows > 0) {
                $_SESSION['error'] = "Username already exists.";
                header("Location: admin_dashboard.php");
                $check_stmt->close();
                exit;
            }
            $check_stmt->close();
            
            if (!empty($_POST['password'])) {
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $stmt = $conexion->prepare("UPDATE admins SET username=?, password=?, email=? WHERE id=?");
                $stmt->bind_param("sssi", $username, $password, $email, $id);
            } else {
                $stmt = $conexion->prepare("UPDATE admins SET username=?, email=? WHERE id=?");
                $stmt->bind_param("ssi", $username, $email, $id);
            }
            $stmt->execute();
        } elseif ($_POST['action'] === 'delete_admin') {
            $id = $_POST['id'];
            if ($id != $_SESSION['admin_id']) { // No permitir eliminar a sí mismo
                $stmt = $conexion->prepare("DELETE FROM admins WHERE id = ?");
                $stmt->bind_param("i", $id);
                $stmt->execute();
            }
        }
    }
}

$editService = null;
if (isset($_GET['edit_service'])) {
    $serviceId = (int) $_GET['edit_service'];
    $stmt = $conexion->prepare("SELECT * FROM servicios WHERE id = ?");
    $stmt->bind_param("i", $serviceId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $editService = $result->fetch_assoc();
    }
    $stmt->close();
}

$servicios = $conexion->query("SELECT * FROM servicios ORDER BY id DESC");
$admins = $conexion->query("SELECT id, username, email, ultimo_acceso FROM admins ORDER BY id ASC");
$reservas = $conexion->query("SELECT r.*, f.nro_factura, f.metodo_pago, f.total FROM reservas r LEFT JOIN facturas f ON r.cliente_id = f.cliente_id AND r.fecha = DATE(f.fecha) ORDER BY r.fecha DESC, r.hora DESC");
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | BEAUTY MAKEUP</title>
    <link rel="stylesheet" href="style.css?v=1.3">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <header id="navbar">
        <div class="logo-container" style="display: flex; align-items: center; gap: 2rem;">
            <a href="index.php" class="logo-link">
                <img src="logo.png" alt="Logo" class="logo-img-circular">
                <span class="logo-text">ADMIN PANEL</span>
            </a>
            <span
                style="color: var(--primary-color); font-size: 0.8rem; opacity: 0.8; border-left: 1px solid rgba(223, 207, 190, 0.3); padding-left: 1.5rem;">Hola,
                <strong><?php echo $_SESSION['admin_user']; ?></strong></span>
        </div>
        <nav>
            <ul class="nav-links">
                <li><a href="logout.php"
                        style="color: #ff8888; text-decoration: none; font-size: 0.7rem; letter-spacing: 1px;">CERRAR
                        SESIÓN</a></li>
            </ul>
        </nav>
    </header>

    <main class="admin-main">
        <div class="admin-header" style="margin-bottom: 2rem; padding: 1.5rem;">
            <h2 class="section-title" style="margin-bottom: 0;">Panel de Administración</h2>
        </div>

        <!-- Navegación por secciones -->
        <nav class="admin-nav">
            <button class="nav-tab active" onclick="showSection('services')">Servicios</button>
            <button class="nav-tab" onclick="showSection('reservations')">Reservas</button>
            <button class="nav-tab" onclick="showSection('admins')">Administradores</button>
        </nav>

        <!-- Sección de Servicios -->
        <div id="services-section" class="admin-section active">
            <section class="add-form">
                <h3 id="form-title" style="margin-bottom: 1.5rem; color: var(--primary-color);">
                    <?php echo $editService ? 'Editar Servicio: ' . htmlspecialchars($editService['nombre']) : 'Agregar Nuevo Servicio'; ?>
                </h3>
                <form action="admin_dashboard.php" method="POST" enctype="multipart/form-data" id="mainForm">
                    <input type="hidden" name="action" id="form-action"
                        value="<?php echo $editService ? 'update' : 'add'; ?>">
                    <input type="hidden" name="id" id="form-id"
                        value="<?php echo $editService ? $editService['id'] : ''; ?>">
                    <input type="hidden" name="current_imagen"
                        value="<?php echo $editService ? htmlspecialchars($editService['imagen']) : ''; ?>">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="form-nombre">Nombre del Servicio</label>
                            <input type="text" name="nombre" id="form-nombre" placeholder="Ej: Maquillaje de Novia"
                                value="<?php echo $editService ? htmlspecialchars($editService['nombre']) : ''; ?>"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="form-precio">Precio ($)</label>
                            <input type="number" step="0.01" name="precio" id="form-precio" placeholder="0.00"
                                value="<?php echo $editService ? $editService['precio'] : ''; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="form-categoria">Categoría</label>
                            <select name="categoria" id="form-categoria" required>
                                <option value="">Seleccionar categoría</option>
                                <option value="maquillaje" <?php echo ($editService && $editService['categoria'] == 'maquillaje') ? 'selected' : ''; ?>>Maquillaje</option>
                                <option value="especial" <?php echo ($editService && $editService['categoria'] == 'especial') ? 'selected' : ''; ?>>Especial</option>
                                <option value="ojos" <?php echo ($editService && $editService['categoria'] == 'ojos') ? 'selected' : ''; ?>>Ojos</option>
                                <option value="cejas" <?php echo ($editService && $editService['categoria'] == 'cejas') ? 'selected' : ''; ?>>Cejas</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="form-imagen">Imagen del Servicio</label>
                            <input type="file" name="imagen" id="form-imagen" accept="image/*" <?php echo $editService ? '' : 'required'; ?> onchange="previewImage(this)">
                            <div id="image-preview"
                                style="margin-top: 10px; max-width: 200px; max-height: 150px; border: 1px solid rgba(223, 207, 190, 0.3); border-radius: 4px; overflow: hidden; display: none;">
                                <img id="preview-img" src="" alt="Vista previa"
                                    style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <?php if ($editService): ?>
                                <small style="color: #c1a8a8ff; margin-top: 5px; display: block;">Deja vacío para mantener
                                    la imagen actual:
                                    <strong><?php echo htmlspecialchars($editService['imagen']); ?></strong></small>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="admin-btn primary" id="submitBtn"
                            onclick="showLoading(this)"><?php echo $editService ? 'Actualizar Servicio' : 'Guardar Servicio'; ?></button>
                        <button type="button" class="admin-btn secondary" id="cancelBtn"
                            style="display:<?php echo $editService ? 'inline-block' : 'none'; ?>;"
                            onclick="resetForm()">Cancelar</button>
                    </div>
                </form>
            </section>

            <section class="service-list admin-service-list">
                <h3 style="margin-bottom: 1.5rem; color: var(--primary-color);">Gestión de Servicios</h3>
                <div class="list-container">
                <?php while ($s = $servicios->fetch_assoc()): ?>
                    <div class="service-item">
                        <div class="service-info">
                            <?php if ($s['imagen']): ?>
                                <img src="uploads/<?php echo htmlspecialchars($s['imagen']); ?>" alt="<?php echo htmlspecialchars($s['nombre']); ?>" class="service-img-preview">
                            <?php else: ?>
                                <div class="service-img-preview no-image" style="background: #222; display: flex; align-items: center; justify-content: center; font-size: 0.6rem;">SIN FOTO</div>
                            <?php endif; ?>
                            
                            <div class="service-details">
                                <h4><?php echo htmlspecialchars($s['nombre']); ?></h4>
                                <div class="service-meta">
                                    <span>Categoría: <strong class="badge badge-<?php echo htmlspecialchars($s['categoria']); ?>"><?php echo ucfirst(htmlspecialchars($s['categoria'])); ?></strong></span>
                                    <span>Precio: <strong>$<?php echo number_format($s['precio'], 0, ',', '.'); ?></strong></span>
                                    <span class="status-indicator <?php echo $s['activo'] ? 'status-on' : 'status-off'; ?>">
                                        <i class="fas <?php echo $s['activo'] ? 'fa-check-circle' : 'fa-times-circle'; ?>"></i>
                                        <?php echo $s['activo'] ? 'Visible' : 'Oculto'; ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="service-actions">
                            <button class="action-icon-btn" title="Editar"
                                onclick="editService(<?php echo htmlspecialchars(json_encode($s)); ?>)">
                                <i class="fas fa-edit"></i>
                            </button>
                            
                            <form action="admin_dashboard.php" method="POST" style="display:inline;">
                                <input type="hidden" name="action" value="toggle">
                                <input type="hidden" name="id" value="<?php echo $s['id']; ?>">
                                <input type="hidden" name="status" value="<?php echo $s['activo'] ? 0 : 1; ?>">
                                <button type="submit" class="action-icon-btn toggle" title="<?php echo $s['activo'] ? 'Ocultar' : 'Mostrar'; ?>">
                                    <i class="fas <?php echo $s['activo'] ? 'fa-eye-slash' : 'fa-eye'; ?>"></i>
                                </button>
                            </form>
                            
                            <form action="admin_dashboard.php" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar servicio?');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $s['id']; ?>">
                                <button type="submit" class="action-icon-btn delete" title="Eliminar">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endwhile; ?>
                </div>
            </section>
        </div>

        <!-- Sección de Reservas -->
        <div id="reservations-section" class="admin-section">
            <section class="service-list admin-service-list">
                <h3 style="margin-bottom: 1.5rem; color: var(--primary-color);">Gestión de Reservas</h3>
                <div class="list-container">
                <?php if ($reservas->num_rows > 0): ?>
                    <?php while ($r = $reservas->fetch_assoc()): ?>
                        <div class="service-item">
                            <div class="service-info">
                                <div class="service-img-preview" style="background: rgba(212,175,55,0.1); display: flex; align-items: center; justify-content: center; color: var(--primary-color); font-weight: bold;">
                                    <i class="fas fa-calendar-check"></i>
                                </div>
                                <div class="service-details">
                                    <h4><?php echo htmlspecialchars($r['nombre_cliente']); ?></h4>
                                    <div class="service-meta">
                                        <span>Servicio: <strong><?php echo htmlspecialchars($r['servicio']); ?></strong></span>
                                        <span>Fecha: <strong><?php echo date('d/m/Y', strtotime($r['fecha'])); ?></strong></span>
                                        <span>Hora: <strong><?php echo htmlspecialchars($r['hora']); ?></strong></span>
                                        <span>WhatsApp: <strong><?php echo htmlspecialchars($r['telefono']); ?></strong></span>
                                    </div>
                                    <div class="service-meta" style="margin-top: 0.5rem; border-top: 1px solid rgba(255,255,255,0.05); padding-top: 0.5rem;">
                                        <span>Factura: <strong style="color: var(--primary-color);"><?php echo $r['nro_factura'] ?? 'N/A'; ?></strong></span>
                                        <span>Pago: <strong class="badge badge-<?php echo $r['metodo_pago'] ?? 'default'; ?>"><?php echo ucfirst($r['metodo_pago'] ?? 'Pendiente'); ?></strong></span>
                                        <?php if(isset($r['total'])): ?>
                                            <span>Monto: <strong>$<?php echo number_format($r['total'], 2); ?></strong></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="service-actions">
                                <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $r['telefono']); ?>" target="_blank" class="action-icon-btn" title="Contactar por WhatsApp">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                                <?php if($r['nro_factura']): ?>
                                    <!-- Buscamos el ID de la factura real para el enlace -->
                                    <?php 
                                    $stmt_f = $conexion->prepare("SELECT id FROM facturas WHERE nro_factura = ?");
                                    $stmt_f->bind_param("s", $r['nro_factura']);
                                    $stmt_f->execute();
                                    $f_id = $stmt_f->get_result()->fetch_assoc()['id'] ?? null;
                                    $stmt_f->close();
                                    ?>
                                    <?php if($f_id): ?>
                                        <a href="invoice.php?id=<?php echo $f_id; ?>" class="action-icon-btn" title="Ver Factura">
                                            <i class="fas fa-file-invoice"></i>
                                        </a>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p style="color: #666; font-style: italic; text-align: center; padding: 2rem;">No hay reservas registradas.</p>
                <?php endif; ?>
                </div>
            </section>
        </div>

        <!-- Sección de Administradores -->
        <div id="admins-section" class="admin-section">
            <section class="add-form">
                <h3 id="admin-form-title" style="margin-bottom: 1.5rem; color: var(--primary-color);">Agregar Nuevo
                    Administrador</h3>
                <form action="admin_dashboard.php" method="POST" id="adminForm">
                    <input type="hidden" name="action" id="admin-form-action" value="add_admin">
                    <input type="hidden" name="id" id="admin-form-id" value="">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="admin-form-username">Nombre de Usuario</label>
                            <input type="text" name="username" id="admin-form-username" placeholder="Ej: admin1"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="admin-form-password">Contraseña</label>
                            <input type="password" name="password" id="admin-form-password"
                                placeholder="Contraseña segura" required>
                        </div>
                        <div class="form-group">
                            <label for="admin-form-email">Correo Electrónico</label>
                            <input type="email" name="email" id="admin-form-email" placeholder="admin@glowbelleza.com">
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="admin-btn primary" id="admin-submitBtn">Guardar
                            Administrador</button>
                        <button type="button" class="admin-btn secondary" id="admin-cancelBtn" style="display:none;"
                            onclick="resetAdminForm()">Cancelar</button>
                    </div>
                </form>
            </section>

            <section class="service-list">
                <h3 style="margin-bottom: 1.5rem; color: var(--primary-color);">Listado de Administradores</h3>
                <div class="list-container">
                <?php if ($admins->num_rows > 0): ?>
                    <?php while ($a = $admins->fetch_assoc()): ?>
                        <div class="service-item">
                            <div class="service-info">
                                <div class="service-img-preview" style="background: var(--primary-color); display: flex; align-items: center; justify-content: center; color: #000; font-weight: bold; font-size: 1.2rem;">
                                    <?php echo strtoupper(substr($a['username'], 0, 1)); ?>
                                </div>
                                <div class="service-details">
                                    <h4><?php echo htmlspecialchars($a['username']); ?></h4>
                                    <div class="service-meta">
                                        <span>Email: <strong><?php echo htmlspecialchars($a['email']); ?></strong></span>
                                        <span>Acceso: <strong><?php echo $a['ultimo_acceso'] ? date('d/m/Y H:i', strtotime($a['ultimo_acceso'])) : 'Nunca'; ?></strong></span>
                                        <?php if ($a['id'] == $_SESSION['admin_id']): ?>
                                            <span style="color: var(--primary-color); font-weight: bold;">(Tú)</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="service-actions">
                                <button class="action-icon-btn" title="Editar"
                                    onclick="editAdmin(<?php echo htmlspecialchars(json_encode($a)); ?>)">
                                    <i class="fas fa-user-edit"></i>
                                </button>
                                <?php if ($a['id'] != $_SESSION['admin_id']): ?>
                                    <form action="admin_dashboard.php" method="POST" style="display:inline;"
                                        onsubmit="return confirm('¿Eliminar administrador?');">
                                        <input type="hidden" name="action" value="delete_admin">
                                        <input type="hidden" name="id" value="<?php echo $a['id']; ?>">
                                        <button type="submit" class="action-icon-btn delete" title="Eliminar">
                                            <i class="fas fa-user-minus"></i>
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p style="color: #666; font-style: italic; text-align: center; padding: 2rem;">No hay administradores registrados.</p>
                <?php endif; ?>
                </div>
            </section>
        </div>
    </main>

        <script>
            const formTitle = document.getElementById('form-title');
            const formAction = document.getElementById('form-action');
            const formId = document.getElementById('form-id');
            const submitBtn = document.getElementById('submitBtn');
            const cancelBtn = document.getElementById('cancelBtn');

            const fNombre = document.getElementById('form-nombre');
            const fPrecio = document.getElementById('form-precio');
            const fImagen = document.getElementById('form-imagen');
            const fCategoria = document.getElementById('form-categoria');

            function editService(s) {
                formTitle.textContent = 'Editar Servicio: ' + s.nombre;
                formAction.value = 'update';
                formId.value = s.id;
                document.querySelector('input[name="current_imagen"]').value = s.imagen;
                fNombre.value = s.nombre;
                fPrecio.value = s.precio;
                fImagen.value = ''; // Limpiar el input file
                fCategoria.value = s.categoria;
                submitBtn.textContent = 'Actualizar Servicio';
                cancelBtn.style.display = 'inline-block';
                formTitle.scrollIntoView({ behavior: 'smooth' });
            }

            function resetForm() {
                formTitle.textContent = 'Agregar Nuevo Servicio';
                formAction.value = 'add';
                formId.value = '';
                document.querySelector('input[name="current_imagen"]').value = '';
                document.getElementById('mainForm').reset();
                document.getElementById('image-preview').style.display = 'none';
                submitBtn.textContent = 'Guardar Servicio';
                cancelBtn.style.display = 'none';
            }

            // Funciones para Administradores
            const adminFormTitle = document.getElementById('admin-form-title');
            const adminFormAction = document.getElementById('admin-form-action');
            const adminFormId = document.getElementById('admin-form-id');
            const adminSubmitBtn = document.getElementById('admin-submitBtn');
            const adminCancelBtn = document.getElementById('admin-cancelBtn');

            const adminFUsername = document.getElementById('admin-form-username');
            const adminFPassword = document.getElementById('admin-form-password');
            const adminFEmail = document.getElementById('admin-form-email');

            function editAdmin(a) {
                adminFormTitle.textContent = 'Editar Administrador: ' + a.username;
                adminFormAction.value = 'update_admin';
                adminFormId.value = a.id;
                adminFUsername.value = a.username;
                adminFPassword.value = ''; // No mostrar contraseña
                adminFPassword.required = false;
                adminFEmail.value = a.email;
                adminSubmitBtn.textContent = 'Actualizar Administrador';
                adminCancelBtn.style.display = 'inline-block';
                adminFormTitle.scrollIntoView({ behavior: 'smooth' });
            }

            function resetAdminForm() {
                adminFormTitle.textContent = 'Agregar Nuevo Administrador';
                adminFormAction.value = 'add_admin';
                adminFormId.value = '';
                document.getElementById('adminForm').reset();
                adminFPassword.required = true;
                adminSubmitBtn.textContent = 'Guardar Administrador';
                adminCancelBtn.style.display = 'none';
            }

            // Función para cambiar entre secciones
            function showSection(sectionName) {
                // Ocultar todas las secciones
                document.querySelectorAll('.admin-section').forEach(section => {
                    section.classList.remove('active');
                });

                // Remover clase active de todas las pestañas
                document.querySelectorAll('.nav-tab').forEach(tab => {
                    tab.classList.remove('active');
                });

                // Mostrar sección seleccionada
                document.getElementById(sectionName + '-section').classList.add('active');

                // Activar pestaña correspondiente
                event.target.classList.add('active');
            }

            // Función para vista previa de imagen
            function previewImage(input) {
                const preview = document.getElementById('image-preview');
                const previewImg = document.getElementById('preview-img');

                if (input.files && input.files[0]) {
                    const reader = new FileReader();

                    reader.onload = function (e) {
                        previewImg.src = e.target.result;
                        preview.style.display = 'block';
                    }

                    reader.readAsDataURL(input.files[0]);
                } else {
                    preview.style.display = 'none';
                }
            }

            // Función para mostrar loading en botones
            function showLoading(button) {
                const originalText = button.textContent;
                button.textContent = 'Guardando...';
                button.disabled = true;

                // Re-enable después de 3 segundos (por si el form no se envía)
                setTimeout(() => {
                    button.textContent = originalText;
                    button.disabled = false;
                }, 3000);
            }
        </script>
</body>

</html>