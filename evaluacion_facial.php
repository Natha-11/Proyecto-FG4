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
        
        /* Modal Styles */
        .payment-modal {
            display: none;
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.9);
            z-index: 2000;
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background: #111;
            padding: 2.5rem;
            border-radius: 20px;
            border: 1px solid var(--primary-color);
            max-width: 500px;
            width: 90%;
            text-align: center;
            box-shadow: 0 0 50px rgba(212,175,55,0.2);
        }
        .payment-option {
            background: rgba(255,255,255,0.05);
            padding: 1.5rem;
            margin: 1rem 0;
            border-radius: 12px;
            cursor: pointer;
            border: 1px solid rgba(212,175,55,0.1);
            transition: 0.3s;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .payment-option:hover {
            border-color: var(--primary-color);
            background: rgba(212,175,55,0.1);
        }
        .payment-option.selected {
            border-color: var(--primary-color);
            background: rgba(212,175,55,0.2);
        }
        .price-tag {
            font-size: 2rem;
            color: var(--primary-color);
            font-family: 'Cormorant Garamond', serif;
            margin: 1rem 0;
        }
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
            <button id="payAnalysisBtn" class="cta-button" style="margin-top: 2rem;">Obtener Reporte y Factura</button>
        </div>

        <!-- Modal de Pago -->
        <div id="paymentModal" class="payment-modal">
            <div class="modal-content">
                <h2 style="font-family: 'Cormorant Garamond', serif; color: var(--primary-color);">Pago de Análisis</h2>
                <p>Para obtener tu reporte detallado y factura, por favor realiza el pago.</p>
                <div class="price-tag">$500.00</div>
                
                <div class="payment-option selected" data-method="efectivo">
                    <i class="fas fa-money-bill-wave"></i>
                    <span>Efectivo (Pagar en local)</span>
                </div>
                <div class="payment-option" data-method="tarjeta">
                    <i class="fas fa-credit-card"></i>
                    <span>Tarjeta de Crédito / Débito</span>
                </div>
                <div class="payment-option" data-method="transferencia">
                    <i class="fas fa-university"></i>
                    <span>Transferencia Bancaria</span>
                </div>

                <button id="confirmPaymentBtn" class="cta-button" style="width: 100%; margin-top: 2rem;">Confirmar y Pagar</button>
                <button id="closeModalBtn" style="background: transparent; border: none; color: #666; margin-top: 1rem; cursor: pointer;">Cancelar</button>
            </div>
        </div>

        <!-- Modal de Éxito -->
        <div id="successModal" class="payment-modal">
            <div class="modal-content">
                <div style="font-size: 4rem; color: #4CAF50; margin-bottom: 1rem;"><i class="fas fa-check-circle"></i></div>
                <h2 style="color: #fff;">¡Pago Realizado!</h2>
                <p>Tu análisis ha sido procesado y tu factura generada.</p>
                <a id="invoiceLink" href="#" class="cta-button" style="display: block; margin-top: 2rem; text-decoration: none;">Ver Factura</a>
                <a href="index.php" style="display: block; margin-top: 1rem; color: var(--primary-color); text-decoration: none;">Volver al Inicio</a>
            </div>
        </div>
    </main>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

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

        // Lógica de Pago
        const payAnalysisBtn = document.getElementById('payAnalysisBtn');
        const paymentModal = document.getElementById('paymentModal');
        const closeModalBtn = document.getElementById('closeModalBtn');
        const confirmPaymentBtn = document.getElementById('confirmPaymentBtn');
        const successModal = document.getElementById('successModal');
        const invoiceLink = document.getElementById('invoiceLink');
        const paymentOptions = document.querySelectorAll('.payment-option');
        
        let selectedMethod = 'efectivo';

        payAnalysisBtn.addEventListener('click', () => {
            paymentModal.style.display = 'flex';
        });

        closeModalBtn.addEventListener('click', () => {
            paymentModal.style.display = 'none';
        });

        paymentOptions.forEach(opt => {
            opt.addEventListener('click', () => {
                paymentOptions.forEach(o => o.classList.remove('selected'));
                opt.classList.add('selected');
                selectedMethod = opt.dataset.method;
            });
        });

        confirmPaymentBtn.addEventListener('click', async () => {
            confirmPaymentBtn.disabled = true;
            confirmPaymentBtn.textContent = 'Procesando...';

            const cart = [{
                name: 'Análisis Facial Smart',
                price: 500,
                metadata: { type: 'service' }
            }];

            try {
                const response = await fetch('api_create_invoice.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        cart: cart,
                        total: 500,
                        metodo_pago: selectedMethod
                    })
                });

                const data = await response.json();
                if (data.success) {
                    paymentModal.style.display = 'none';
                    successModal.style.display = 'flex';
                    invoiceLink.href = 'invoice.php?id=' + data.invoice_id;
                } else {
                    alert(data.message || 'Error al procesar el pago.');
                    confirmPaymentBtn.disabled = false;
                    confirmPaymentBtn.textContent = 'Confirmar y Pagar';
                }
            } catch (err) {
                console.error(err);
                alert('Ocurrió un error en la conexión.');
                confirmPaymentBtn.disabled = false;
                confirmPaymentBtn.textContent = 'Confirmar y Pagar';
            }
        });
    </script>
</body>
</html>
