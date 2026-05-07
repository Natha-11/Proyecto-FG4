<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Análisis Facial | BEAUTY MAKEUP</title>
    <link rel="stylesheet" href="style.css?v=1.3">
    <style>
        .facial-main { padding: 8rem 5% 4rem; max-width: 1000px; margin: 0 auto; min-height: 100vh; text-align: center; }
        .camera-container { 
            position: relative; 
            width: 100%; 
            max-width: 600px; 
            margin: 2rem auto; 
            aspect-ratio: 4/3; 
            background: #1a1a1a; 
            border-radius: 20px; 
            overflow: hidden; 
            border: 2px solid var(--primary-color);
            box-shadow: 0 0 30px rgba(223, 207, 190, 0.2);
        }
        #video, #canvas { width: 100%; height: 100%; object-fit: cover; }
        .scan-line {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 2px;
            background: var(--primary-color);
            box-shadow: 0 0 15px var(--primary-color);
            display: none;
            z-index: 10;
        }
        @keyframes scan {
            0% { top: 0; }
            50% { top: 100%; }
            100% { top: 0; }
        }
        .scanning .scan-line {
            display: block;
            animation: scan 2s infinite ease-in-out;
        }
        .result-box {
            display: none;
            margin-top: 3rem;
            padding: 2rem;
            background: rgba(255,255,255,0.05);
            border-radius: 15px;
            border: 1px solid var(--primary-color);
            animation: fadeIn 1s;
        }
        .controls { display: flex; justify-content: center; gap: 1rem; margin-top: 2rem; }
    </style>
</head>
<body>
    <header id="navbar">
        <div class="logo-container">
            <a href="index.php" class="logo-link">
                <img src="logo.png" alt="Logo" class="logo-img-circular">
                <span class="logo-text">BEAUTY ANALYST</span>
            </a>
        </div>
        <nav>
            <ul class="nav-links">
                <li><a href="index.php">Inicio</a></li>
                <li><a href="encuesta_piel.php">Test de Piel</a></li>
            </ul>
        </nav>
    </header>

    <main class="facial-main">
        <h1 class="section-title">Análisis Facial <span style="color: var(--primary-color); font-style: italic;">Smart</span></h1>
        <p>Descubre tu esencia. Usa tu cámara para un análisis personalizado de tonos y facciones.</p>

        <div class="camera-container" id="cameraBox">
            <video id="video" autoplay playsinline></video>
            <canvas id="canvas" style="display:none;"></canvas>
            <div class="scan-line"></div>
        </div>

        <div class="controls">
            <button id="startBtn" class="cta-button">Activar Cámara</button>
            <button id="captureBtn" class="cta-button" style="display:none; background: var(--primary-color); color: #000;">Analizar Rostro</button>
            <label for="fileUpload" class="cta-button" style="cursor: pointer;">Subir Foto</label>
            <input type="file" id="fileUpload" style="display:none;" accept="image/*">
        </div>

        <div id="resultBox" class="result-box">
            <h2 style="color: var(--primary-color); margin-bottom: 1rem;">Análisis Completado</h2>
            <div id="resultText" style="font-size: 1.1rem; line-height: 1.8;">
                Detectamos tonos <strong>cálidos</strong> y una estructura facial <strong>ovalada</strong>.<br>
                Te recomendamos nuestra colección <span style="color: var(--primary-color);">SOFT GLAM</span> para resaltar tu belleza natural.
            </div>
            <a href="index.php#collection" class="text-link" style="margin-top: 2rem;">Ver Recomendaciones</a>
        </div>
    </main>

    <script>
        const video = document.getElementById('video');
        const startBtn = document.getElementById('startBtn');
        const captureBtn = document.getElementById('captureBtn');
        const cameraBox = document.getElementById('cameraBox');
        const resultBox = document.getElementById('resultBox');
        const fileUpload = document.getElementById('fileUpload');

        startBtn.addEventListener('click', async () => {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ video: true });
                video.srcObject = stream;
                startBtn.style.display = 'none';
                captureBtn.style.display = 'inline-block';
            } catch (err) {
                alert('No se pudo acceder a la cámara. Por favor, sube una foto.');
            }
        });

        function simulateAnalysis() {
            cameraBox.classList.add('scanning');
            captureBtn.disabled = true;
            
            setTimeout(() => {
                cameraBox.classList.remove('scanning');
                resultBox.style.display = 'block';
                resultBox.scrollIntoView({ behavior: 'smooth' });
                captureBtn.style.display = 'none';
                startBtn.style.display = 'inline-block';
                startBtn.textContent = 'Nuevo Análisis';
            }, 3000);
        }

        captureBtn.addEventListener('click', simulateAnalysis);
        fileUpload.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                simulateAnalysis();
            }
        });
    </script>
</body>
</html>
