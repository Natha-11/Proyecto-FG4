<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión | glow belleza</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,600;1,400&family=Montserrat:wght@200;400;500&display=swap"
        rel="stylesheet">
</head>

<body>


    <a href="index.php" class="back-link">← Volver al Inicio</a>

    <section class="login-section">
        <div class="login-container">
            <div class="form-box login">
                <h2>Iniciar Sesión</h2>
                <?php if (isset($_GET['error'])): ?>
                    <div
                        style="background: rgba(255, 0, 0, 0.1); color: #ff6b6b; padding: 10px; border-radius: 5px; margin-bottom: 15px; text-align: center; border: 1px solid rgba(255, 0, 0, 0.2);">
                        <?php
                        if ($_GET['error'] == 'wrongpass' || $_GET['error'] == '1')
                            echo "Credenciales incorrectas.";
                        elseif ($_GET['error'] == 'notfound')
                            echo "Usuario no registrado.";
                        else
                            echo "Error al iniciar sesión.";
                        ?>
                    </div>
                <?php endif; ?>
                <?php if (isset($_GET['success'])): ?>
                    <div
                        style="background: rgba(0, 255, 0, 0.1); color: #2ecc71; padding: 10px; border-radius: 5px; margin-bottom: 15px; text-align: center; border: 1px solid rgba(0, 255, 0, 0.2);">
                        Administrador creado correctamente.
                    </div>
                <?php endif; ?>
                <form action="auth_login.php" method="POST">
                    <div class="input-box" style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 10px; color: var(--primary-color); font-weight: 500;">¿Qué tipo de usuario eres?</label>
                        <div style="display: flex; gap: 20px;">
                            <label style="display: flex; align-items: center; cursor: pointer;">
                                <input type="radio" name="user_type" value="cliente" checked style="margin-right: 8px;"> Cliente
                            </label>
                            <label style="display: flex; align-items: center; cursor: pointer;">
                                <input type="radio" name="user_type" value="admin" style="margin-right: 8px;"> Administrador
                            </label>
                        </div>
                    </div>
                    <div id="admin-fields" style="display: none;">
                        <div class="input-box">
                            <input type="text" name="admin_username" placeholder="Usuario o ID de Administrador">
                        </div>
                    </div>
                    <div class="input-box">
                        <input type="email" name="email" required placeholder="Correo Electrónico">
                    </div>
                    <div class="input-box">
                        <input type="password" name="password" required placeholder="Contraseña">
                    </div>
                    <button type="submit" class="cta-button">Entrar</button>
                    <div class="switch-link">
                        <p>¿No tienes cuenta? <a href="registro.php">Regístrate</a></p>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const userTypeRadios = document.querySelectorAll('input[name="user_type"]');
            const adminFields = document.getElementById('admin-fields');
            const emailField = document.querySelector('input[name="email"]');

            userTypeRadios.forEach(radio => {
                radio.addEventListener('change', (e) => {
                    if (e.target.value === 'admin') {
                        adminFields.style.display = 'block';
                        emailField.required = false;
                        emailField.placeholder = 'Correo Electrónico (opcional para admin)';
                    } else {
                        adminFields.style.display = 'none';
                        emailField.required = true;
                        emailField.placeholder = 'Correo Electrónico';
                    }
                });
            });
        });
    </script>
    <script src="script.js"></script>
</body>

</html>