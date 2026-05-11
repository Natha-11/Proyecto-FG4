<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manual Técnico | Luxury Glow</title>
    <link rel="stylesheet" href="style.css?v=2.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,600;1,400&family=Montserrat:wght@200;400;500&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --manual-bg: #0a0a0a;
            --code-bg: #1a1a1a;
            --highlight-pink: #d4a5a5;
        }

        .manual-body {
            background-color: var(--manual-bg);
            padding-top: 100px;
            cursor: auto;
            /* Dejar el cursor normal en el manual para fácil lectura */
        }

        .manual-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 2rem;
        }

        .doc-section {
            margin-bottom: 5rem;
            padding: 2rem;
            background: rgba(255, 255, 255, 0.02);
            border-left: 3px solid var(--highlight-pink);
            border-radius: 0 15px 15px 0;
            backdrop-filter: blur(10px);
        }

        .doc-section h2 {
            font-family: var(--font-serif);
            font-size: 2.5rem;
            color: var(--highlight-pink);
            margin-bottom: 2rem;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .doc-content p {
            line-height: 1.8;
            margin-bottom: 1.5rem;
            color: #ccc;
        }

        .code-block {
            background: var(--code-bg);
            padding: 1.5rem;
            border-radius: 8px;
            font-family: 'Courier New', Courier, monospace;
            font-size: 0.9rem;
            color: #e0e0e0;
            overflow-x: auto;
            margin: 1.5rem 0;
            border: 1px solid #333;
        }

        .tag {
            color: #f07178;
        }

        .attr {
            color: #c792ea;
        }

        .val {
            color: #c3e88d;
        }

        .comment {
            color: #676e95;
            font-style: italic;
        }

        .file-badge {
            display: inline-block;
            background: var(--highlight-pink);
            color: #000;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-bottom: 1rem;
            text-transform: uppercase;
        }

        .feature-list {
            list-style: none;
            padding: 0;
        }

        .feature-list li {
            margin-bottom: 1rem;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .feature-list li::before {
            content: '✦';
            color: var(--highlight-pink);
        }

        .nav-manual {
            position: fixed;
            top: 70px;
            right: 20px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            z-index: 100;
        }

        .nav-manual a {
            background: rgba(20, 20, 20, 0.8);
            border: 1px solid var(--highlight-pink);
            color: var(--highlight-pink);
            padding: 8px 15px;
            text-decoration: none;
            font-size: 0.8rem;
            border-radius: 4px;
            transition: all 0.3s;
        }

        .nav-manual a:hover {
            background: var(--highlight-pink);
            color: #000;
        }

        @media (max-width: 768px) {
            .nav-manual {
                display: none;
            }
        }
    </style>
</head>

<body class="manual-body">

    <header id="navbar">
        <div class="logo-container">
            <a href="index.php" class="logo-link">
                <img src="logo_estudio.jpg" alt="Logo" class="logo-img-circular">
                <span class="logo-text">GUÍA TÉCNICA</span>
            </a>
        </div>
        <nav>
            <ul class="nav-links">
                <li><a href="index.php">Volver al Sitio</a></li>
            </ul>
        </nav>
    </header>

    <div class="nav-manual">
        <a href="#html">Estructura HTML</a>
        <a href="#css">Estilos CSS</a>
        <a href="#js">Lógica JavaScript</a>
        <a href="#php">Backend PHP</a>
    </div>

    <main class="manual-container">

        <div class="doc-section fade-in" id="intro">
            <h2>Introducción</h2>
            <div class="doc-content">
                <p>Bienvenido al manual técnico del proyecto <strong>Luxury Glow</strong>. Este documento ha sido
                    diseñado para explicar minuciosamente cada componente del sitio web, facilitando su comprensión para
                    presentaciones académicas o futuras expansiones del código.</p>
                <p>El sitio se basa en una arquitectura de <strong>maquetación moderna</strong>, utilizando PHP para la
                    gestión de sesiones y plantillas dinámicas, CSS para una estética premium "Champagne & Night", y
                    JavaScript para una experiencia de usuario interactiva y fluida.</p>
            </div>
        </div>

        <div class="doc-section fade-in" id="html">
            <span class="file-badge">Estructura Base</span>
            <h2>1. Estructura y HTML</h2>
            <div class="doc-content">
                <p>El archivo principal <code>index.php</code> organiza la página en secciones modulares. El uso de PHP
                    al inicio permite manejar el acceso privado a la sección de reservas.</p>

                <div class="code-block">
                    <span class="comment">&lt;!-- Inicio de Sesion en index.php --&gt;</span><br>
                    <span class="tag">&lt;?php</span> session_start(); <span class="tag">?&gt;</span><br><br>
                    <span class="comment">&lt;!-- Navbar Dinámica --&gt;</span><br>
                    <span class="tag">&lt;nav&gt;</span><br>
                    &nbsp;&nbsp;<span class="tag">&lt;?php</span> if (isset($_SESSION['user_id'])): <span
                        class="tag">?&gt;</span><br>
                    &nbsp;&nbsp;&nbsp;&nbsp;<span class="tag">&lt;li&gt;&lt;a</span> <span
                        class="attr">href=</span><span class="val">"#booking"</span><span
                        class="tag">&gt;</span>Citas<span class="tag">&lt;/a&gt;&lt;/li&gt;</span><br>
                    &nbsp;&nbsp;<span class="tag">&lt;?php</span> else: <span class="tag">?&gt;</span><br>
                    &nbsp;&nbsp;&nbsp;&nbsp;<span class="tag">&lt;li&gt;&lt;a</span> <span
                        class="attr">href=</span><span class="val">"login.php"</span><span
                        class="tag">&gt;</span>Login<span class="tag">&lt;/a&gt;&lt;/li&gt;</span><br>
                    &nbsp;&nbsp;<span class="tag">&lt;?php</span> endif; <span class="tag">?&gt;</span><br>
                    <span class="tag">&lt;/nav&gt;</span>
                </div>

                <ul class="feature-list">
                    <li><strong>Header/Navbar:</strong> Contiene el logo circular y el menú que cambia si el usuario
                        está logueado.</li>
                    <li><strong>Hero Section:</strong> El primer impacto visual con una imagen de fondo fija y
                        animaciones de "fade-in".</li>
                    <li><strong>Collection:</strong> Una rejilla (grid) de productos con botones para añadir al carrito.
                    </li>
                    <li><strong>Booking Section:</strong> Formulario complejo accesible solo para usuarios registrados,
                        que integra un calendario dinámico.</li>
                </ul>
            </div>
        </div>

        <div class="doc-section fade-in" id="css">
            <span class="file-badge">Diseño y Estética</span>
            <h2>2. Estilos CSS</h2>
            <div class="doc-content">
                <p>El archivo <code>style.css</code> define la personalidad visual. Se basa en variables raíz para
                    facilitar cambios globales de color.</p>

                <div class="code-block">
                    <span class="comment">/* Variables de Luxury Glow */</span><br>
                    :root {<br>
                    &nbsp;&nbsp;--bg-color: <span class="val">#0a0a0a;</span> <span class="comment">/* Negro profundo
                        */</span><br>
                    &nbsp;&nbsp;--primary-color: <span class="val">#d4a5a5;</span> <span class="comment">/* Rosa Oro /
                        Rose Gold */</span><br>
                    &nbsp;&nbsp;--font-serif: <span class="val">'Cormorant Garamond', serif;</span><br>
                    }
                </div>

                <p><strong>Claves del diseño:</strong></p>
                <ul class="feature-list">
                    <li><strong>Glassmorphism:</strong> Se utiliza <code>backdrop-filter: blur(10px)</code> en el navbar
                        y tarjetas para un efecto de cristal.</li>
                    <li><strong>Grid & Flexbox:</strong> El layout es totalmente responsivo gracias al uso de
                        <code>display: grid</code> y <code>display: flex</code>.
                    </li>
                    <li><strong>Animaciones:</strong> Uso de Keyframes para los efectos de aparición y el flotado del
                        contenido Hero.</li>
                </ul>
            </div>
        </div>

        <div class="doc-section fade-in" id="js">
            <span class="file-badge">Interactividad</span>
            <h2>3. Lógica JavaScript</h2>
            <div class="doc-content">
                <p>La interactividad se divide entre <code>script.js</code> (global) y la lógica interna de reservas en
                    <code>index.php</code>.
                </p>

                <div class="code-block">
                    <span class="comment">// Efecto de Cursor Personalizado</span><br>
                    window.addEventListener(<span class="val">'mousemove'</span>, (e) => {<br>
                    &nbsp;&nbsp;cursorDot.style.left = <span class="val">`${e.clientX}px`</span>;<br>
                    &nbsp;&nbsp;cursorDot.style.top = <span class="val">`${e.clientY}px`</span>;<br>
                    &nbsp;&nbsp;cursorOutline.animate({<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;left: <span class="val">`${e.clientX}px`</span>,<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;top: <span class="val">`${e.clientY}px`</span><br>
                    &nbsp;&nbsp;}, { duration: 500 });<br>
                    });
                </div>

                <p><strong>Funcionalidades destacadas:</strong></p>
                <ul class="feature-list">
                    <li><strong>Calendario Dinámico:</strong> Genera los días del mes actual y permite seleccionar
                        fechas consultando disponibilidad mediante AJAX.</li>
                    <li><strong>Carrito Persistente:</strong> Utiliza <code>localStorage</code> para que los productos
                        no se borren al recargar la página.</li>
                    <li><strong>Intersection Observer:</strong> Detecta cuando el usuario hace scroll hacia una sección
                        para disparar las animaciones de aparición.</li>
                </ul>
            </div>
        </div>

        <div class="doc-section fade-in" id="design">
            <span class="file-badge">Estética y Dinamismo</span>
            <h2>5. Diseños y Movimientos</h2>
            <div class="doc-content">
                <p>Esta sección detalla los elementos visuales y de movimiento que definen la experiencia <strong>Luxury
                        Glow</strong>:</p>
                <ul class="feature-list">
                    <li><strong>Logo Circular Interactivo:</strong> Ubicado en el navbar, el logo rota 360 grados y se
                        expande sutilmente al pasar el cursor, simbolizando el ciclo de renovación y cuidado de la
                        belleza.</li>
                    <li><strong>Sitema de Revelado (Reveal):</strong> Las secciones y tarjetas de productos no aparecen
                        de golpe; utilizan un <em>Fade-In Up</em> suave activado por el scroll del usuario, creando una
                        sensación de descubrimiento premium.</li>
                    <li><strong>Paleta "Champagne & Night":</strong> Se utiliza un fondo #0d0d0d (Noche) con acentos en
                        #dfcfbe (Champagne) y #bfa38a (Bronze), proyectando exclusividad y elegancia atemporal.</li>
                    <li><strong>Cursor Cinematográfico:</strong> Un cursor personalizado con dos capas (dot y outline)
                        que interactúa con los elementos clicables, proporcionando un feedback visual fluido y
                        sofisticado.</li>
                    <li><strong>Layout Asimétrico:</strong> La grilla de productos rompe la monotonía con un diseño
                        editorial, donde las imágenes cambian a color al interactuar, enfocando la atención del usuario.
                    </li>
                    <li><strong>Sistema de Facturación:</strong> Al completar una compra desde el carrito, el sistema
                        genera dinámicamente una factura digital vinculada a la cuenta del cliente, registrando la
                        transacción en la base de datos para control de ventas.</li>
                </ul>
            </div>
        </div>

        <div class="doc-section fade-in" id="php">
            <span class="file-badge">Backend e Integración</span>
            <h2>4. Servidor y Datos</h2>
            <div class="doc-content">
                <p>El backend se gestiona con PHP conectado a una base de datos MySQL mediante
                    <code>conexion.php</code>.
                </p>
                <p>Además, se integra con un servicio de automatización mediante el archivo
                    <code>n8n_send_data.php</code>, el cual envía las reservas a un flujo externo (webhook) para
                    notificaciones o gestión interna.
                </p>
                <p>El nuevo <strong>Módulo de Facturación</strong> utiliza las tablas <code>facturas</code> y
                    <code>factura_items</code> para persistir cada venta, permitiendo generar comprobantes en tiempo
                    real mediante <code>api_create_invoice.php</code>.</p>
            </div>
        </div>

    </main>

    <footer class="footer section-padding">
        <div class="container" style="text-align: center;">
            <p>&copy; 2026 Manual Técnico Luxury Glow. Documentación para Examen Final.</p>
        </div>
    </footer>

</body>

</html>