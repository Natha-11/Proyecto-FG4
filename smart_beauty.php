<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Análisis de Piel | BEAUTY MAKEUP</title>
    <link rel="stylesheet" href="style.css?v=1.5">
    <style>
        .smart-main { padding: 10rem 5% 4rem; max-width: 1200px; margin: 0 auto; min-height: 100vh; text-align: center; }
        .smart-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 3rem; margin-top: 4rem; }
        .smart-card { 
            background: rgba(255,255,255,0.03); 
            padding: 4rem 2rem; 
            border-radius: 30px; 
            border: 1px solid rgba(223, 207, 190, 0.1);
            transition: all 0.5s ease;
            text-decoration: none;
            color: inherit;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1.5rem;
        }
        .smart-card:hover {
            background: rgba(223, 207, 190, 0.08);
            transform: translateY(-10px);
            border-color: var(--primary-color);
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        }
        .smart-icon {
            font-size: 3rem;
            color: var(--primary-color);
        }
    </style>
</head>
<body>
    <header id="navbar">
        <div class="logo-container">
            <a href="index.php" class="logo-link">
                <img src="logo.png" alt="Logo" class="logo-img-circular">
                <span class="logo-text">SMART BEAUTY</span>
            </a>
        </div>
        <nav>
            <ul class="nav-links">
                <li><a href="index.php">Inicio</a></li>
                <li><a href="#collection">Colección</a></li>
            </ul>
        </nav>
    </header>

    <main class="smart-main">
        <h1 class="section-title">Análisis de <span style="color: var(--primary-color);">Piel</span></h1>
        <p class="reveal">Elige la herramienta que mejor se adapte a lo que buscas hoy.</p>

        <div class="smart-grid">
            <a href="evaluacion_facial.php" class="smart-card reveal">
                <div class="smart-icon">📸</div>
                <h2>Escaneo Facial</h2>
                <p>Usa tu cámara para detectar facciones y recibir sugerencias instantáneas.</p>
                <span class="text-link">Empezar Escaneo</span>
            </a>

            <a href="encuesta_piel.php" class="smart-card reveal">
                <div class="smart-icon">📝</div>
                <h2>Test de Piel</h2>
                <p>Responde unas preguntas para conocer tu tipo de piel y rutina ideal.</p>
                <span class="text-link">Realizar Test</span>
            </a>
        </div>
    </main>

    <script src="script.js"></script>
</body>
</html>
