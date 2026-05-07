<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro | glow belleza</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,600;1,400&family=Montserrat:wght@200;400;500&display=swap"
        rel="stylesheet">
</head>

<body>


    <a href="login.php" class="back-link">← Volver al Login</a>

    <section class="login-section">
        <div class="login-container">
            <div class="form-box login">
                <h2>Regístrate</h2>
                <form action="auth_register.php" method="POST">
                    <div class="input-box">
                        <input type="text" name="nombre" required placeholder="Nombre Completo">
                    </div>
                    <div class="input-box">
                        <input type="email" name="email" required placeholder="Correo Electrónico">
                    </div>
                    <div class="input-box">
                        <input type="password" name="password" required placeholder="Contraseña">
                    </div>
                    <button type="submit" class="cta-button">Registrarse</button>
                    <div class="switch-link">
                        <p>¿Ya tienes cuenta? <a href="login.php">Inicia Sesión</a></p>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <script src="script.js"></script>
</body>

</html>