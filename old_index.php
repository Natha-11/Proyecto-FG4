<?php
session_start();
include 'conexion.php';

function serviceSlug($name) {
    $slug = mb_strtolower($name, 'UTF-8');
    $slug = preg_replace('/[^\p{L}\p{N}\s\-]+/u', '', $slug);
    $slug = preg_replace('/\s+/', '-', trim($slug));
    return $slug;
}

$activeServices = [];
$servicesResult = $conexion->query("SELECT * FROM servicios WHERE activo = 1 ORDER BY id ASC");
if ($servicesResult) {
    while ($row = $servicesResult->fetch_assoc()) {
        $row['slug'] = serviceSlug($row['nombre']);
        $activeServices[] = $row;
    }
}

$serviceMapJs = [];
foreach ($activeServices as $service) {
    $serviceMapJs[$service['slug']] = [
        'name' => $service['nombre'],
        'price' => floatval($service['precio'])
    ];
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>glow belleza | Makeup Artistry</title>
    <link rel="stylesheet" href="style.css?v=3.2">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,600;1,400&family=Montserrat:wght@200;400;500&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <meta name="description"
        content="Descubre la belleza atemporal con glow belleza. Maquillaje de alta gama para la mujer moderna.">
</head>

<body>
    <div class="cursor-dot" id="cursor-dot"></div>
    <div class="cursor-outline" id="cursor-outline"></div>

    <header id="navbar">
        <div class="logo-container">
            <a href="#" class="logo-link">
                <img src="logo.png" alt="Logo BEAUTY MAKEUP" class="logo-img-circular">
                <span class="logo-text">BEAUTY MAKEUP</span>
            </a>
        </div>
        <nav>
            <ul class="nav-links">
                <li><a href="#hero">Inicio</a></li>
                <li><a href="#collection">Colecci├│n</a></li>
                <li><a href="#about">Nosotros</a></li>
                <li><a href="#contact">Informaci├│n</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li>
                        <a href="#booking" class="nav-cta">
                            <svg xmlns="http://www.w3.org/2003/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 5px;"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                            Citas (<span id="menu-slots-count-badge">10</span>)
                        </a>
                    </li>
                    <li><a href="logout.php" id="logout-btn">Salir</a></li>
                <?php else: ?>
                    <li><a href="login.php" class="nav-cta">Login</a></li>
                    <li><a href="registro.php" class="nav-cta"
                            style="background: transparent; color: var(--primary-color); border: 1px solid var(--primary-color);">Registro</a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
        <div class="menu-toggle" id="mobile-menu">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </div>
    </header>

    <section id="hero" class="hero-section">
        <div class="hero-content">
            <h1 class="fade-in">Redefine tu <br><span class="highlight">Esencia</span></h1>
            <div class="fade-in delay-2" style="display: flex; gap: 1rem; margin-top: 2rem;">
                <a href="#collection" class="cta-button">Descubrir</a>
                <a href="evaluacion_facial.php" class="cta-button" style="background: var(--primary-color); color: #000;">An├ílisis Facial</a>
            </div>
        </div>
        <div class="hero-visual fade-in delay-1"></div>
    </section>

    <section id="collection" class="section-padding" style="padding-top:0;">
        <h2 class="section-title reveal">La Colecci├│n</h2>
        <div class="product-grid">
            <!-- Tarjeta: NATURAL -->
            <div class="product-card reveal">
                <?php if (isset($_SESSION['admin_id'])): ?>
                    <div class="admin-card-controls">
                        <form action="admin_dashboard.php" method="POST" style="display:inline;">
                            <input type="hidden" name="action" value="toggle">
                            <input type="hidden" name="id" value="9">
                            <input type="hidden" name="status" value="1">
                            <button type="submit" class="admin-control-btn toggle" title="Ocultar/Mostrar"><i class="fas fa-eye"></i></button>
                        </form>
                        <div class="admin-control-btn edit" title="Editar" onclick="editService(9)"><i class="fas fa-pen"></i></div>
                    </div>
                <?php endif; ?>
                <img src="imagen1.jpg" alt="NATURAL" class="product-img">
                <h3 style="text-transform: uppercase;">NATURAL</h3>
                <p class="price">$500</p>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <button class="cta-button reserve-btn" data-service="natural">Reservar</button>
                <?php else: ?>
                    <a href="login.php" class="cta-button">Reservar</a>
                <?php endif; ?>
            </div>

            <!-- Tarjeta: SOFT GLAM -->
            <div class="product-card reveal">
                <?php if (isset($_SESSION['admin_id'])): ?>
                    <div class="admin-card-controls">
                        <form action="admin_dashboard.php" method="POST" style="display:inline;">
                            <input type="hidden" name="action" value="toggle">
                            <input type="hidden" name="id" value="10">
                            <input type="hidden" name="status" value="1">
                            <button type="submit" class="admin-control-btn toggle" title="Ocultar/Mostrar"><i class="fas fa-eye"></i></button>
                        </form>
                        <div class="admin-control-btn edit" title="Editar" onclick="editService(10)"><i class="fas fa-pen"></i></div>
                    </div>
                <?php endif; ?>
                <img src="imagen2.jpg" alt="SOFT GLAM" class="product-img">
                <h3 style="text-transform: uppercase;">SOFT GLAM</h3>
                <p class="price">$600</p>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <button class="cta-button reserve-btn" data-service="soft-glam">Reservar</button>
                <?php else: ?>
                    <a href="login.php" class="cta-button">Reservar</a>
                <?php endif; ?>
            </div>

            <!-- Tarjeta: SMOKEY -->
            <div class="product-card reveal">
                <?php if (isset($_SESSION['admin_id'])): ?>
                    <div class="admin-card-controls">
                        <form action="admin_dashboard.php" method="POST" style="display:inline;">
                            <input type="hidden" name="action" value="toggle">
                            <input type="hidden" name="id" value="11">
                            <input type="hidden" name="status" value="1">
                            <button type="submit" class="admin-control-btn toggle" title="Ocultar/Mostrar"><i class="fas fa-eye"></i></button>
                        </form>
                        <div class="admin-control-btn edit" title="Editar" onclick="editService(11)"><i class="fas fa-pen"></i></div>
                    </div>
                <?php endif; ?>
                <img src="imagen3.jpg" alt="SMOKEY" class="product-img">
                <h3 style="text-transform: uppercase;">SMOKEY</h3>
                <p class="price">$1.200</p>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <button class="cta-button reserve-btn" data-service="smokey-eyes">Reservar</button>
                <?php else: ?>
                    <a href="login.php" class="cta-button">Reservar</a>
                <?php endif; ?>
            </div>

            <!-- Tarjeta: EDITORIAL -->
            <div class="product-card reveal">
                <?php if (isset($_SESSION['admin_id'])): ?>
                    <div class="admin-card-controls">
                        <form action="admin_dashboard.php" method="POST" style="display:inline;">
                            <input type="hidden" name="action" value="toggle">
                            <input type="hidden" name="id" value="12"> <!-- Ajustado a Bridal Elegance por falta de ID Editorial -->
                            <input type="hidden" name="status" value="1">
                            <button type="submit" class="admin-control-btn toggle" title="Ocultar/Mostrar"><i class="fas fa-eye"></i></button>
                        </form>
                        <div class="admin-control-btn edit" title="Editar" onclick="editService(12)"><i class="fas fa-pen"></i></div>
                    </div>
                <?php endif; ?>
                <img src="imagen4.jpg" alt="EDITORIAL" class="product-img">
                <h3 style="text-transform: uppercase;">EDITORIAL</h3>
                <p class="price">$1.500</p>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <button class="cta-button reserve-btn" data-service="editorial">Reservar</button>
                <?php else: ?>
                    <a href="login.php" class="cta-button">Reservar</a>
                <?php endif; ?>
            </div>

            <!-- Tarjeta: BRIDAL -->
            <div class="product-card reveal">
                <?php if (isset($_SESSION['admin_id'])): ?>
                    <div class="admin-card-controls">
                        <form action="admin_dashboard.php" method="POST" style="display:inline;">
                            <input type="hidden" name="action" value="toggle">
                            <input type="hidden" name="id" value="12">
                            <input type="hidden" name="status" value="1">
                            <button type="submit" class="admin-control-btn toggle" title="Ocultar/Mostrar"><i class="fas fa-eye"></i></button>
                        </form>
                        <div class="admin-control-btn edit" title="Editar" onclick="editService(12)"><i class="fas fa-pen"></i></div>
                    </div>
                <?php endif; ?>
                <img src="bridal.jpg" alt="BRIDAL" class="product-img">
                <h3 style="text-transform: uppercase;">BRIDAL</h3>
                <p class="price">$2.000</p>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <button class="cta-button reserve-btn" data-service="bridal-elegance">Reservar</button>
                <?php else: ?>
                    <a href="login.php" class="cta-button">Reservar</a>
                <?php endif; ?>
            </div>

            <!-- Tarjeta: GLAM NIGHT -->
            <div class="product-card reveal">
                <?php if (isset($_SESSION['admin_id'])): ?>
                    <div class="admin-card-controls">
                        <div class="admin-control-btn edit" title="Editar" onclick="editService(0)"><i class="fas fa-pen"></i></div>
                    </div>
                <?php endif; ?>
                <img src="nith.jpg" alt="GLAM NIGHT" class="product-img">
                <h3 style="text-transform: uppercase;">GLAM NIGHT</h3>
                <p class="price">$800</p>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <button class="cta-button reserve-btn" data-service="glam-night">Reservar</button>
                <?php else: ?>
                    <a href="login.php" class="cta-button">Reservar</a>
                <?php endif; ?>
            </div>

            <!-- Tarjeta: Pesta├▒as -->
            <div class="product-card reveal">
                <?php if (isset($_SESSION['admin_id'])): ?>
                    <div class="admin-card-controls">
                        <form action="admin_dashboard.php" method="POST" style="display:inline;">
                            <input type="hidden" name="action" value="toggle">
                            <input type="hidden" name="id" value="14">
                            <input type="hidden" name="status" value="1">
                            <button type="submit" class="admin-control-btn toggle" title="Ocultar/Mostrar"><i class="fas fa-eye"></i></button>
                        </form>
                        <div class="admin-control-btn edit" title="Editar" onclick="editService(14)"><i class="fas fa-pen"></i></div>
                    </div>
                <?php endif; ?>
                <img src="pesta├▒as.jpg" alt="Pesta├▒as" class="product-img">
                <h3 style="text-transform: uppercase;">Pesta├▒as</h3>
                <p class="price">$300</p>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <button class="cta-button reserve-btn" data-service="pesta├▒as-premium">Reservar</button>
                <?php else: ?>
                    <a href="login.php" class="cta-button">Reservar</a>
                <?php endif; ?>
            </div>

            <!-- Tarjeta: Cejas -->
            <div class="product-card reveal">
                <?php if (isset($_SESSION['admin_id'])): ?>
                    <div class="admin-card-controls">
                        <form action="admin_dashboard.php" method="POST" style="display:inline;">
                            <input type="hidden" name="action" value="toggle">
                            <input type="hidden" name="id" value="13">
                            <input type="hidden" name="status" value="1">
                            <button type="submit" class="admin-control-btn toggle" title="Ocultar/Mostrar"><i class="fas fa-eye"></i></button>
                        </form>
                        <div class="admin-control-btn edit" title="Editar" onclick="editService(13)"><i class="fas fa-pen"></i></div>
                    </div>
                <?php endif; ?>
                <img src="cejas.jpg" alt="Cejas" class="product-img">
                <h3 style="text-transform: uppercase;">Cejas</h3>
                <p class="price">$250</p>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <button class="cta-button reserve-btn" data-service="dise├▒o-de-cejas">Reservar</button>
                <?php else: ?>
                    <a href="login.php" class="cta-button">Reservar</a>
                <?php endif; ?>
            </div>

            <!-- Tarjeta: LIMPIEZA FACIAL -->
            <div class="product-card reveal">
                <?php if (isset($_SESSION['admin_id'])): ?>
                    <div class="admin-card-controls">
                        <form action="admin_dashboard.php" method="POST" style="display:inline;">
                            <input type="hidden" name="action" value="toggle">
                            <input type="hidden" name="id" value="15">
                            <input type="hidden" name="status" value="1">
                            <button type="submit" class="admin-control-btn toggle" title="Ocultar/Mostrar"><i class="fas fa-eye"></i></button>
                        </form>
                        <div class="admin-control-btn edit" title="Editar" onclick="editService(15)"><i class="fas fa-pen"></i></div>
                    </div>
                <?php endif; ?>
                <img src="uploads/limpiezaF.jpeg" alt="Limpieza Facial" class="product-img">
                <h3 style="text-transform: uppercase;">Limpieza Facial</h3>
                <p class="price">$1.000</p>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <button class="cta-button reserve-btn" data-service="limpieza-facial">Reservar</button>
                <?php else: ?>
                    <a href="login.php" class="cta-button">Reservar</a>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section id="about" class="section-padding team-section">
        <div class="container">
            <h2 class="section-title reveal">Desarrolladoras</h2>
            <p class="section-subtitle reveal"
                style="text-align: center; margin-bottom: 4rem; max-width: 800px; margin-left: auto; margin-right: auto; opacity: 0.8;">
                Detr├ís de cada transformaci├│n hay un coraz├│n dedicado. Conoce a los expertos que hacen posible la magia
                de Glow Belleza.
            </p>
            <div class="dev-grid">

                <div class="dev-card reveal">
                    <div class="dev-img-container">
                        <img src="nathalia.jpg" alt="Desarrollador 1" class="dev-img">
                    </div>
                    <div class="dev-info">
                        <h3>Nathalia Corniel</h3>
                        <p class="dev-role">Desarrolladora Frontend</p>
                    </div>
                </div>


                <div class="dev-card reveal">
                    <div class="dev-img-container">
                        <img src="isairis.jpeg" alt="Desarrollador 2" class="dev-img">
                    </div>
                    <div class="dev-info">
                        <h3>Isairis Ferrera</h3>
                        <p class="dev-role">Desarrolladora Backend</p>
                    </div>
                </div>


                <div class="dev-card reveal">
                    <div class="dev-img-container">
                        <img src="unnamed.png" alt="Desarrollador 3" class="dev-img">
                    </div>
                    <div class="dev-info">
                        <h3>Ery Joel</h3>
                        <p class="dev-role">Dise├▒ador UI/UX</p>
                    </div>
                </div>


                <div class="dev-card reveal">
                    <div class="dev-img-container">
                        <img src="Adam.jpg" alt="Desarrollador 4" class="dev-img">
                    </div>
                    <div class="dev-info">
                        <h3>Adam Luis</h3>
                        <p class="dev-role">Desarrollador Full Stack</p>
                    </div>
                </div>
            </div>
            <!-- Cont├íctanos removido de aqu├¡ para bajarlo -->
        </div>

        <!-- Cuadros Estilo Colecci├│n: Visi├│n, Misi├│n y Valores -->
        <div id="mvv" class="container" style="margin-top: 5rem;">
            <h2 class="section-title">Nuestra Esencia</h2>
            <div class="product-grid" style="padding-top: 0; padding-bottom: 0;">

                <!-- Visi├│n -->
                <div class="product-card reveal">
                    <h3 style="margin-top: 1rem;">VISI├ôN</h3>
                    <p class="mvv-card-text"
                        style="color: rgba(255,255,255,0.7); font-size: 0.95rem; line-height: 1.6;">
                        Ser el referente de belleza aut├⌐ntica e influyente en la regi├│n.
                    </p>
                    <div style="height: 20px;"></div>
                </div>

                <!-- Misi├│n -->
                <div class="product-card reveal">
                    <h3 style="margin-top: 1rem;">MISI├ôN</h3>
                    <p class="mvv-card-text"
                        style="color: rgba(255,255,255,0.7); font-size: 0.95rem; line-height: 1.6;">
                        Realzar la belleza con arte, dedicaci├│n y productos premium.
                    </p>
                    <div style="height: 20px;"></div>
                </div>

                <!-- Valores -->
                <div class="product-card reveal">
                    <h3 style="margin-top: 1rem;">VALORES</h3>
                    <p class="mvv-card-text"
                        style="color: rgba(255,255,255,0.7); font-size: 0.95rem; line-height: 1.6;">
                        Excelencia, Autenticidad, Respeto, Innovaci├│n y Confianza.
                    </p>
                    <div style="height: 20px;"></div>
                </div>
            </div>
        </div>
    </section>

    <?php if (isset($_SESSION['user_id'])): ?>
        <div id="booking-modal-overlay" class="modal-overlay">
            <div class="modal-container">
                <span class="close-modal" id="close-booking-modal">&times;</span>
                <h2 class="section-title" style="text-align: center; font-size: 2.2rem; margin-bottom: 2rem;">Confirma tu
                    Fecha y Hora</h2>
                <form class="booking-form" action="registro.php" method="POST" id="bookingForm"
                    style="box-shadow: none; background: transparent; padding: 0; border: none;">
                    <input type="hidden" name="hora" id="selectedHora" required>

                    <div class="form-group triple">
                        <input type="text" name="nombre" placeholder="Nombre Completo"
                            value="<?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : ''; ?>"
                            required>
                        <input type="email" name="email" placeholder="Correo Electr├│nico" required>
                        <input type="tel" name="telefono" placeholder="WhatsApp (Ej: +123...)" required>
                    </div>

                    <div class="form-group">
                        <label for="servicio-select"
                            style="display: block; margin-bottom: 8px; font-size: 0.9rem; color: var(--primary-color);">Selecciona
                            el Servicio deseado:</label>
                        <select name="servicio" id="servicio-select" required
                            style="width: 100%; padding: 12px; background: rgba(0,0,0,0.3); border: 1px solid rgba(212,175,55,0.3); color: white; border-radius: 8px;">
                            <option value="" disabled selected>Selecciona Servicio</option>
                            <?php foreach ($activeServices as $servicio): ?>
                                <option value="<?php echo htmlspecialchars($servicio['slug']); ?>"><?php echo htmlspecialchars($servicio['nombre']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="metodo-pago"
                            style="display: block; margin-bottom: 8px; font-size: 0.9rem; color: var(--primary-color);">M├⌐todo de Pago:</label>
                        <select name="metodo_pago" id="metodo-pago" required
                            style="width: 100%; padding: 12px; background: rgba(0,0,0,0.3); border: 1px solid rgba(212,175,55,0.3); color: white; border-radius: 8px;">
                            <option value="efectivo" selected>Efectivo (Pagar en local)</option>
                            <option value="tarjeta">Tarjeta de Cr├⌐dito / D├⌐bito</option>
                        </select>
                    </div>

                    <!-- Campos de Tarjeta (Ocultos por defecto) -->
                    <div id="card-details" style="display: none; margin-top: 20px; padding: 25px; background: rgba(223, 207, 190, 0.05); border: 1px solid rgba(223, 207, 190, 0.2); border-radius: 8px;">
                        <h4 style="color: var(--primary-color); margin-bottom: 20px; font-size: 1.1rem; text-transform: uppercase; letter-spacing: 2px; text-align: center;">Detalles de la Tarjeta</h4>
                        
                        <div style="margin-bottom: 20px;">
                            <label for="card-type" style="display: block; margin-bottom: 8px; font-size: 0.85rem; color: var(--primary-color); text-transform: uppercase; letter-spacing: 1px;">Tipo de Tarjeta</label>
                            <select id="card-type" style="width: 100%; padding: 12px; background: rgba(0,0,0,0.3); border: 1px solid rgba(212,175,55,0.3); color: white; border-radius: 8px;">
                                <option value="credito">Tarjeta de Cr├⌐dito</option>
                                <option value="debito">Tarjeta de D├⌐bito</option>
                            </select>
                        </div>

                        <div style="margin-bottom: 20px;">
                            <label for="card-number" style="display: block; margin-bottom: 8px; font-size: 0.85rem; color: var(--primary-color); text-transform: uppercase; letter-spacing: 1px;">N├║mero de tarjeta</label>
                            <input type="text" id="card-number" placeholder="XXXX XXXX XXXX XXXX" maxlength="16" style="width: 100%; letter-spacing: 2px;">
                        </div>

                        <div style="display: flex; gap: 20px; margin-bottom: 20px;">
                            <div style="flex: 1;">
                                <label for="card-expiry" style="display: block; margin-bottom: 8px; font-size: 0.85rem; color: var(--primary-color); text-transform: uppercase; letter-spacing: 1px;">Fecha de expiraci├│n</label>
                                <input type="text" id="card-expiry" placeholder="MM/AA" maxlength="5" style="width: 100%;">
                            </div>
                            <div style="flex: 1;">
                                <label for="card-cvv" style="display: block; margin-bottom: 8px; font-size: 0.85rem; color: var(--primary-color); text-transform: uppercase; letter-spacing: 1px;">C├│digo de seguridad</label>
                                <input type="text" id="card-cvv" placeholder="CVV" maxlength="3" style="width: 100%;">
                            </div>
                        </div>

                        <div style="margin-bottom: 10px;">
                             <label for="card-name" style="display: block; margin-bottom: 8px; font-size: 0.85rem; color: var(--primary-color); text-transform: uppercase; letter-spacing: 1px;">Nombre en la tarjeta</label>
                             <input type="text" id="card-name" placeholder="Nombre como figura en la tarjeta" style="width: 100%;">
                        </div>
                    </div>

                    <p style="color: var(--primary-color); text-align: center; font-style: italic; margin-bottom: 1rem; font-size: 1.2rem;"
                        id="selected-service-display">Servicio Seleccionado</p>

                    <!-- Wrapper oculto para el input de fecha (para enviar con el form) -->
                    <input type="hidden" name="fecha" id="bookingDateInput" required>

                    <!-- SECCI├ôN DIN├üMICA: Calendario y Selecci├│n de Horas -->
                    <div class="booking-layout">
                        <!-- Secci├│n del Calendario -->
                        <div class="calendar-section">
                            <div class="calendar-container">
                                <div class="calendar-header">
                                    <button type="button" class="calendar-nav-btn" id="prevMonth">&lt;</button>
                                    <h3 id="calendarMonthYear">Mes A├▒o</h3>
                                    <button type="button" class="calendar-nav-btn" id="nextMonth">&gt;</button>
                                </div>
                                <div class="calendar-grid-header">
                                    <div>Dom</div>
                                    <div>Lun</div>
                                    <div>Mar</div>
                                    <div>Mi├⌐</div>
                                    <div>Jue</div>
                                    <div>Vie</div>
                                    <div>S├íb</div>
                                </div>
                                <div class="calendar-grid" id="calendarGrid">
                                    <!-- JS generar├í los d├¡as aqu├¡ -->
                                </div>
                            </div>

                            <!-- Time Slots (Debajo del calendario) -->
                            <div class="hours-container" id="hoursGrid" style="display: none;">
                                <div id="available-counter"
                                    style="text-align: center; margin-bottom: 1.5rem; padding: 10px; background: rgba(223, 207, 190, 0.05); border-radius: 8px; border: 1px solid rgba(223, 207, 190, 0.1);">
                                    <span
                                        style="color: var(--primary-color); font-weight: 500; letter-spacing: 1px; font-size: 0.95rem;">
                                        Citas disponibles: <span id="slots-count">--</span>
                                    </span>
                                </div>
                                <p style="color: #666; margin-bottom: 1rem; text-align: center; font-size: 0.9rem;">
                                    HORAS DISPONIBLES PARA <span id="selectedDateDisplay"
                                        style="color:#fff; font-weight: 500;"></span>
                                </p>
                                <div class="hours-grid" id="hoursGridContainer">
                                    <!-- JS generar├í las horas -->
                                </div>
                            </div>
                        </div>

                        <!-- Lista de Citas (Debajo del calendario en desktop tambi├⌐n) -->
                        <div class="appointments-section">
                            <h4 class="list-header">Citas Agendadas</h4>
                            <div id="appointmentList" class="appointment-list-grid">
                                <p style="color: #444; text-align: center; grid-column: 1/-1;">Cargando...</p>
                            </div>
                            <div id="viewMoreContainer" style="text-align: center; margin-top: 15px; display: none;">
                                <button type="button" id="viewMoreBtn" class="add-cart"
                                    style="width: auto; padding: 5px 20px;">Ver m├ís</button>
                            </div>
                        </div>
                    </div>

                    <div style="text-align: center; margin-top: 3rem;">
                        <button type="submit" class="cta-button" style="width: 100%; max-width: 400px;">Reservar</button>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>




    <footer id="contact" class="footer section-padding">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <h3>INFORMACI├ôN</h3>
                    <p>Resaltando tu belleza natural con exclusividad y elegancia. Maquillaje de alta gama para cada
                        momento especial.</p>
                    <!-- Redes sociales removidas seg├║n solicitud -->
                </div>
                <div class="footer-links">
                    <h4>Enlaces</h4>
                    <ul>
                        <li><a href="#hero">Inicio</a></li>
                        <li><a href="#collection">Colecci├│n</a></li>
                        <li><a href="#about">Equipo</a></li>
                        <li><a href="#mvv">Nuestra Esencia</a></li>
                        <li><a href="#booking">Reservas</a></li>
                        <li><a href="#contact">Informaci├│n</a></li>
                    </ul>
                </div>
                <div class="footer-links">
                    <h4>Servicios</h4>
                    <ul>
                        <li><a href="#collection">Maquillaje Natural</a></li>
                        <li><a href="#collection">Soft Glam</a></li>
                        <li><a href="#collection">Bridal & Editorial</a></li>
                        <li><a href="#collection">Pesta├▒as & Cejas</a></li>
                    </ul>
                </div>
                <div class="footer-contact">
                    <h4>Informaci├│n</h4>
                    <p><a href="https://wa.me/123456789" target="_blank"
                            style="color: #888; text-decoration: none;">WhatsApp: 8298949979</a></p>
                    <p>Email: beautymakeup2312@gmail.com </p>
                    <p>Horario: Lun - S├íb: 9:00 AM - 6:00 PM</p>
                </div>
            </div>
            <div class="footer-bottom"
                style="display: flex; justify-content: center; align-items: center; flex-wrap: wrap; gap: 1rem; text-align: center;">
                <p>&copy; <?php echo date('Y'); ?> Beauty Makeup. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <script>
        /**
         * L├ôGICA DE RESERVAS Y CALENDARIO (Frontend)
         * Este script se encarga de:
         * 1. Cargar las citas existentes desde el servidor (AJAX).
         * 2. Dibujar el calendario mes a mes.
         * 3. Permitir seleccionar un d├¡a y consultar las horas disponibles.
         * 4. Manejar el carrito de compras persistente.
         */
        document.addEventListener('DOMContentLoaded', async () => {
            const calendarGrid = document.getElementById('calendarGrid');
            const monthYearTitle = document.getElementById('calendarMonthYear');
            const prevBtn = document.getElementById('prevMonth');
            const nextBtn = document.getElementById('nextMonth');
            const bookingDateInput = document.getElementById('bookingDateInput');
            const selectedDateDisplay = document.getElementById('selectedDateDisplay');
            const hoursGrid = document.getElementById('hoursGrid');
            const hoursGridContainer = document.getElementById('hoursGridContainer');
            const selectedHoraInput = document.getElementById('selectedHora');
            const appointmentList = document.getElementById('appointmentList');
            const viewMoreContainer = document.getElementById('viewMoreContainer');
            const bookingForm = document.getElementById('bookingForm');
            const viewMoreBtn = document.getElementById('viewMoreBtn');
            const metodoPagoSelect = document.getElementById('metodo-pago');
            const cardDetailsContainer = document.getElementById('card-details');
            let showAllAppointments = false;

            // Mostrar/Ocultar campos de tarjeta
            if (metodoPagoSelect) {
                metodoPagoSelect.addEventListener('change', (e) => {
                    if (e.target.value === 'tarjeta') {
                        cardDetailsContainer.style.display = 'block';
                        // Hacer campos requeridos si se selecciona tarjeta
                        document.getElementById('card-number').required = true;
                        document.getElementById('card-expiry').required = true;
                        document.getElementById('card-cvv').required = true;
                        document.getElementById('card-name').required = true;
                    } else {
                        cardDetailsContainer.style.display = 'none';
                        document.getElementById('card-number').required = false;
                        document.getElementById('card-expiry').required = false;
                        document.getElementById('card-cvv').required = false;
                        document.getElementById('card-name').required = false;
                    }
                });
            }

            // Duraciones de servicios
            const serviceDurations = {}; // Desactivado por solicitud de usuario
            const serviceMap = <?php echo json_encode($serviceMapJs, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>;

            let currentDate = new Date();
            let currentMonth = currentDate.getMonth();
            let currentYear = currentDate.getFullYear();
            let reservations = [];

            // Actualizar disponibilidad en el men├║ (Hoy)
            async function updateMenuAvailability() {
                const badge = document.getElementById('menu-slots-count-badge');
                if (!badge) return;

                try {
                    const todayStr = new Date().toISOString().split('T')[0];
                    const response = await fetch(`api_availability.php?fecha=${todayStr}`);
                    const data = await response.json();

                    // Total slots (10) - slots reservados hoy
                    const reservedCount = data.reserved ? data.reserved.length : 0;
                    const available = 10 - reservedCount;

                    if (available > 0) {
                        badge.textContent = available;
                        badge.style.display = 'inline-block';
                    } else {
                        badge.style.display = 'none';
                    }
                } catch (err) {
                    console.error("Error updating menu availability:", err);
                }
            }

            // Llamada inicial
            updateMenuAvailability();



            // Horas de trabajo
            const businessHours = [
                "09:00", "10:00", "11:00", "12:00", "13:00",
                "14:00", "15:00", "16:00", "17:00", "18:00"
            ];

            // Convertir hora 24h ("HH:MM") a 12h con AM/PM
            function to12h(hora24) {
                const [hStr, mStr] = hora24.split(':');
                let h = parseInt(hStr, 10);
                const ampm = h >= 12 ? 'PM' : 'AM';
                h = h % 12 || 12;
                return `${h}:${mStr} ${ampm}`;
            }

            // 1. Obtener Reservas
            async function fetchReservations() {
                try {
                    const res = await fetch('api_reservations.php');
                    reservations = await res.json();
                    renderAppointmentList();
                    renderCalendar(currentMonth, currentYear);
                } catch (err) {
                    console.error("Error loading reservations:", err);
                    appointmentList.innerHTML = '<p style="color:red; font-size:0.8rem;">Error cargando citas.</p>';
                }
            }

            // 2. Renderizar Lista de Citas (Minimalista, Enfocado en la Privacidad)
            function renderAppointmentList() {
                appointmentList.innerHTML = '';

                // Filtrar solo las futuras (desde hoy en adelante)
                const todayStr = new Date().toISOString().split('T')[0];
                const upcoming = reservations.filter(r => r.fecha >= todayStr);

                if (upcoming.length === 0) {
                    appointmentList.innerHTML = '<p style="color:#444; font-style:italic; grid-column: 1/-1; text-align:center;">No hay citas pr├│ximas.</p>';
                    viewMoreContainer.style.display = 'none';
                    return;
                }

                // Determinar cu├íntos mostrar
                const limit = 4;
                const itemsToShow = showAllAppointments ? upcoming : upcoming.slice(0, limit);

                if (upcoming.length > limit) {
                    viewMoreContainer.style.display = 'block';
                    viewMoreBtn.textContent = showAllAppointments ? 'Ver menos' : 'Ver m├ís';
                } else {
                    viewMoreContainer.style.display = 'none';
                }

                itemsToShow.forEach(res => {
                    const item = document.createElement('div');
                    item.className = 'appointment-item';

                    const dateParts = res.fecha.split('-');
                    const dateObj = new Date(dateParts[0], dateParts[1] - 1, dateParts[2]);
                    const dateStr = dateObj.toLocaleDateString('es-ES', { day: 'numeric', month: 'short' });
                    const timeStr = res.hora.substring(0, 5);

                    item.innerHTML = `
                        <div class="appt-date">${dateStr}</div>
                        <div class="appt-time">${timeStr}</div>
                    `;
                    appointmentList.appendChild(item);
                });
            }

            // Manejador del bot├│n Ver m├ís
            if (viewMoreBtn) {
                viewMoreBtn.addEventListener('click', () => {
                    showAllAppointments = !showAllAppointments;
                    renderAppointmentList();
                });
            }

            // 3. Renderizar Calendario
            function renderCalendar(month, year) {
                calendarGrid.innerHTML = '';

                // T├¡tulo
                const monthName = new Date(year, month).toLocaleDateString('es-ES', { month: 'long' });
                monthYearTitle.textContent = `${monthName.charAt(0).toUpperCase() + monthName.slice(1)} ${year}`;

                const firstDay = new Date(year, month, 1).getDay(); // 0 es Domingo
                const daysInMonth = new Date(year, month + 1, 0).getDate();

                // Espacios vac├¡os para el mes anterior
                for (let i = 0; i < firstDay; i++) {
                    const empty = document.createElement('div');
                    empty.className = 'calendar-day empty';
                    calendarGrid.appendChild(empty);
                }

                // D├¡as
                for (let day = 1; day <= daysInMonth; day++) {
                    const dayCell = document.createElement('div');
                    dayCell.className = 'calendar-day';

                    // Formato AAAA-MM-DD
                    const dateString = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;

                    // Comprobar si es hoy
                    const todayStr = new Date().toISOString().split('T')[0];
                    if (dateString === todayStr) dayCell.classList.add('today');

                    // Comprobar reservas (Indicador de punto minimalista)
                    const dayReservations = reservations.filter(r => r.fecha === dateString);
                    const hasReservation = dayReservations.length > 0;

                    let html = `<span class="day-number">${day}</span>`;

                    if (hasReservation) {
                        html += `<div class="day-marker"></div>`;
                    }

                    dayCell.innerHTML = html;

                    // Evento de Clic
                    dayCell.addEventListener('click', () => {
                        // L├│gica de selecci├│n
                        document.querySelectorAll('.calendar-day').forEach(d => d.classList.remove('selected'));
                        dayCell.classList.add('selected');

                        bookingDateInput.value = dateString;

                        // Formato de fecha de visualizaci├│n
                        const dateParts = dateString.split('-');
                        const dateObj = new Date(dateParts[0], dateParts[1] - 1, dateParts[2]);
                        selectedDateDisplay.textContent = dateObj.toLocaleDateString('es-ES', { weekday: 'long', day: 'numeric', month: 'long' });

                        loadHours(dateString);
                    });

                    calendarGrid.appendChild(dayCell);
                }
            }

            // 4. L├│gica de Carga de Horas
            async function loadHours(fecha) {
                hoursGrid.style.display = 'block';
                hoursGridContainer.innerHTML = '<p style="color:#666; font-size:0.8rem;">Cargando...</p>';
                selectedHoraInput.value = "";

                try {
                    const response = await fetch(`api_availability.php?fecha=${fecha}`);
                    const data = await response.json();
                    const reserved = data.reserved || [];

                    const totalSlots = businessHours.length;
                    const availableSlots = totalSlots - reserved.length;
                    const slotsCountEl = document.getElementById('slots-count');
                    if (slotsCountEl) {
                        slotsCountEl.textContent = availableSlots;
                    }

                    hoursGridContainer.innerHTML = '';

                    // Comprobar fecha pasada
                    const dateParts = fecha.split('-');
                    const checkDate = new Date(dateParts[0], dateParts[1] - 1, dateParts[2]);
                    const today = new Date();
                    today.setHours(0, 0, 0, 0);

                    if (checkDate < today) {
                        hoursGridContainer.innerHTML = '<p style="color:#444; text-align:center; width:100%;">No disponible</p>';
                        return;
                    }

                    businessHours.forEach(hora => {
                        const btn = document.createElement('div');
                        btn.classList.add('time-slot');

                        // Comprobar disponibilidad
                        // Nota: el backend suele devolver H:i:s, o H:i. Vamos a hacer una coincidencia difusa para estar seguros o asumiendo que el formato coincide
                        const isReserved = reserved.some(r => r.startsWith(hora));

                        if (isReserved) {
                            btn.classList.add('reserved');
                            btn.textContent = to12h(hora);
                        } else {
                            btn.classList.add('available');
                            btn.textContent = to12h(hora);
                            btn.addEventListener('click', () => {
                                document.querySelectorAll('.time-slot.selected').forEach(el => el.classList.remove('selected'));
                                btn.classList.add('selected');
                                selectedHoraInput.value = hora;
                            });
                        }
                        hoursGridContainer.appendChild(btn);
                    });

                } catch (error) {
                    console.error(error);
                    hoursGridContainer.innerHTML = '<p style="color:red">Error.</p>';
                }
            }

            // Navegaci├│n
            if (prevBtn) prevBtn.addEventListener('click', () => {
                currentMonth--;
                if (currentMonth < 0) {
                    currentMonth = 11;
                    currentYear--;
                }
                renderCalendar(currentMonth, currentYear);
            });

            if (nextBtn) nextBtn.addEventListener('click', () => {
                currentMonth++;
                if (currentMonth > 11) {
                    currentMonth = 0;
                    currentYear++;
                }
                renderCalendar(currentMonth, currentYear);
            });

            // Enviar Reserva Directamente
            if (bookingForm) {
                bookingForm.addEventListener('submit', async (e) => {
                    e.preventDefault();
                    const formData = new FormData(bookingForm);
                    const selectedService = formData.get('servicio');
                    const selectedHora = selectedHoraInput.value;
                    const selectedFecha = bookingDateInput.value;

                    if (!selectedFecha || !selectedHora || !selectedService) {
                        alert('Por favor selecciona servicio, fecha y al menos una hora.');
                        return;
                    }

                    executeReservation(formData);
                });
            }

            async function executeReservation(formData) {
                const selectedService = formData.get('servicio');
                const appointmentData = serviceMap[selectedService] || { name: 'Servicio', price: 0 };

                const resName = `Reserva: ${appointmentData.name}`;
                const metadata = {
                    type: 'reservation',
                    nombre: formData.get('nombre'),
                    email: formData.get('email'),
                    telefono: formData.get('telefono'),
                    servicio: selectedService,
                    fecha: formData.get('fecha'),
                    hora: formData.get('hora'),
                    metodo_pago: formData.get('metodo_pago')
                };

                const singleCartItem = { name: resName, price: appointmentData.price, metadata: metadata };

                const submitBtn = bookingForm.querySelector('button[type="submit"]');
                const originalText = submitBtn.textContent;
                submitBtn.textContent = 'Procesando...';
                submitBtn.disabled = true;

                try {
                    const response = await fetch('api_create_invoice.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ cart: [singleCartItem], total: appointmentData.price })
                    });
                    const result = await response.json();

                    if (result.success) {
                        const successModal = document.getElementById('success-modal');
                        if (successModal) successModal.style.display = 'flex';

                        setTimeout(() => {
                            window.location.href = `invoice.php?id=${result.invoice_id}`;
                        }, 800);

                        bookingForm.reset();
                        bookingDateInput.value = '';
                        selectedHoraInput.value = '';
                        document.querySelectorAll('.time-slot.selected').forEach(el => el.classList.remove('selected'));
                        document.querySelectorAll('.calendar-day.selected').forEach(d => d.classList.remove('selected'));
                        selectedDateDisplay.textContent = '';
                        hoursGrid.style.display = 'none';
                        document.getElementById('booking-modal-overlay').classList.remove('open');

                        fetchReservations();
                        updateMenuAvailability();
                    } else {
                        alert('Error al procesar la reserva: ' + result.message);
                    }
                } catch (err) {
                    console.error('Error en la reserva:', err);
                    alert('Error de conexi├│n.');
                } finally {
                    submitBtn.textContent = originalText;
                    submitBtn.disabled = false;
                }
            }



            document.getElementById('close-success-modal').addEventListener('click', () => {
                document.getElementById('success-modal').style.display = 'none';
            });

            // Botones de las tarjetas de producto redirigidos al Modal
            document.querySelectorAll('.reserve-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const serviceVal = btn.getAttribute('data-service');
                    const sel = document.querySelector('select[name="servicio"]');
                    const display = document.getElementById('selected-service-display');

                    if (sel && serviceVal) {
                        sel.value = serviceVal;
                        if (display) {
                            const nameText = sel.options[sel.selectedIndex].text;
                            display.textContent = `Servicio Seleccionado: ${nameText}`;
                        }
                    }

                    const modal = document.getElementById('booking-modal-overlay');
                    if (modal) modal.classList.add('open');
                });
            });

            const closeBookingModalBtn = document.getElementById('close-booking-modal');
            if (closeBookingModalBtn) {
                closeBookingModalBtn.addEventListener('click', () => {
                    document.getElementById('booking-modal-overlay').classList.remove('open');
                });
            }

            // Enlace de 'Citas' en el navbar
            document.querySelectorAll('.nav-cta').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    if (btn.getAttribute('href') === '#booking') {
                        e.preventDefault();
                        const modal = document.getElementById('booking-modal-overlay');
                        if (modal) modal.classList.add('open');
                    }
                });
            });

            updateCartUI();


            // Inicio
            await fetchReservations();
        });

        function editService(serviceId) {
            window.location.href = 'admin_dashboard.php?edit_service=' + serviceId;
        }
    </script>


    <!-- Modal de ├ëxito de Reserva -->
    <div id="success-modal" class="modal-notification" style="display: none;">
        <div class="modal-content">
            <div class="checkmark-wrapper">
                <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                    <circle class="checkmark-circle" cx="26" cy="26" r="25" fill="none" />
                    <path class="checkmark-check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8" />
                </svg>
            </div>
            <h3>┬íReserva Exitosa!</h3>
            <p id="success-modal-msg">Tu cita ha sido confirmada con ├⌐xito.</p>
            <button id="close-success-modal" class="cta-button">Entendido</button>
        </div>
    </div>

    <!-- El modal de duraci├│n ha sido eliminado a petici├│n del usuario -->



    <!-- Bot├│n Volver Arriba -->
    <button id="scroll-top-btn" class="scroll-top-btn" aria-label="Volver arriba">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
            stroke-linejoin="round">
            <polyline points="18 15 12 9 6 15"></polyline>
        </svg>
    </button>

    <script src="script.js"></script>
</body>

</html>
