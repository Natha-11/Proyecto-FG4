<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test de Piel | BEAUTY MAKEUP</title>
    <link rel="stylesheet" href="style.css?v=1.4">
    <style>
        .survey-main { padding: 8rem 5% 4rem; max-width: 800px; margin: 0 auto; min-height: 100vh; }
        .survey-card { background: rgba(255,255,255,0.02); padding: 3rem; border-radius: 20px; border: 1px solid rgba(223, 207, 190, 0.1); }
        .question { margin-bottom: 2.5rem; display: none; animation: fadeIn 0.5s; }
        .question.active { display: block; }
        .options { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-top: 1.5rem; }
        .option-btn { padding: 1rem; border: 1px solid rgba(223, 207, 190, 0.2); background: transparent; color: #fff; cursor: pointer; transition: all 0.3s; text-align: left; }
        .option-btn:hover { border-color: var(--primary-color); background: rgba(223, 207, 190, 0.05); }
        .option-btn.selected { background: var(--primary-color); color: #000; border-color: var(--primary-color); }
        .progress-bar { height: 2px; background: rgba(223, 207, 190, 0.1); margin-bottom: 2rem; border-radius: 10px; }
        .progress-fill { height: 100%; background: var(--primary-color); width: 0%; transition: width 0.5s; }
        #results { display: none; text-align: center; }
    </style>
</head>
<body>
    <header id="navbar">
        <div class="logo-container">
            <a href="index.php" class="logo-link">
                <img src="logo.png" alt="Logo" class="logo-img-circular">
                <span class="logo-text">SKIN TEST</span>
            </a>
        </div>
        <nav>
            <ul class="nav-links">
                <li><a href="index.php">Inicio</a></li>
                <li><a href="evaluacion_facial.php">Análisis Facial</a></li>
            </ul>
        </nav>
    </header>

    <main class="survey-main">
        <div class="survey-card">
            <div class="progress-bar"><div class="progress-fill" id="progressFill"></div></div>
            
            <div id="survey-form">
                <!-- Pregunta 1 -->
                <div class="question active" data-step="1">
                    <h3>1. ¿Cómo describirías tu tipo de piel predominante?</h3>
                    <div class="options">
                        <button class="option-btn" data-value="seca">Seca / Deshidratada</button>
                        <button class="option-btn" data-value="grasa">Grasa / Brillante</button>
                        <button class="option-btn" data-value="mixta">Mixta (Zona T grasa)</button>
                        <button class="option-btn" data-value="sensible">Sensible / Reactiva</button>
                    </div>
                </div>

                <!-- Pregunta 2 -->
                <div class="question" data-step="2">
                    <h3>2. ¿Cuál es tu principal preocupación estética?</h3>
                    <div class="options">
                        <button class="option-btn" data-value="imperfecciones">Acné / Imperfecciones</button>
                        <button class="option-btn" data-value="lineas">Líneas de expresión / Arrugas</button>
                        <button class="option-btn" data-value="manchas">Manchas / Tono desigual</button>
                        <button class="option-btn" data-value="luminosidad">Falta de luminosidad</button>
                    </div>
                </div>

                <!-- Pregunta 3 -->
                <div class="question" data-step="3">
                    <h3>3. ¿Qué acabado prefieres en tu maquillaje diario?</h3>
                    <div class="options">
                        <button class="option-btn" data-value="mate">Mate (Sin brillos)</button>
                        <button class="option-btn" data-value="dewy">Glowy / Jugoso (Efecto mojado)</button>
                        <button class="option-btn" data-value="natural">Natural (Segunda piel)</button>
                        <button class="option-btn" data-value="cubriente">Alta cobertura</button>
                    </div>
                </div>
            </div>

            <div id="results">
                <h2 style="color: var(--primary-color); margin-bottom: 1.5rem;">Tu Rutina Ideal</h2>
                <div id="recommendationText" style="margin-bottom: 2rem; line-height: 1.6;">
                    Basado en tus respuestas, tu piel necesita productos con <strong>Ácido Hialurónico</strong> y una base de cobertura media con acabado <strong>Natural</strong>.<br><br>
                    Te recomendamos nuestro servicio <strong style="color: var(--primary-color);">SOFT GLAM</strong> para un acabado profesional que cuide tu hidratación.
                </div>
                <div style="display: flex; gap: 1rem; justify-content: center;">
                    <a href="index.php#collection" class="cta-button">Ver Servicios</a>
                    <button onclick="location.reload()" class="cta-button" style="background: transparent; color: var(--primary-color);">Repetir Test</button>
                </div>
            </div>
        </div>
    </main>

    <script>
        const steps = document.querySelectorAll('.question');
        const progressFill = document.getElementById('progressFill');
        const results = document.getElementById('results');
        const surveyForm = document.getElementById('survey-form');
        let currentStep = 1;
        const totalSteps = steps.length;

        document.querySelectorAll('.option-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                // Marcar seleccionada
                btn.parentElement.querySelectorAll('.option-btn').forEach(b => b.classList.remove('selected'));
                btn.classList.add('selected');

                // Siguiente paso después de un pequeño delay
                setTimeout(() => {
                    if (currentStep < totalSteps) {
                        steps[currentStep - 1].classList.remove('active');
                        currentStep++;
                        steps[currentStep - 1].classList.add('active');
                        progressFill.style.width = ((currentStep - 1) / totalSteps * 100) + '%';
                    } else {
                        showResults();
                    }
                }, 400);
            });
        });

        function showResults() {
            progressFill.style.width = '100%';
            surveyForm.style.display = 'none';
            results.style.display = 'block';
        }
    </script>
</body>
</html>
