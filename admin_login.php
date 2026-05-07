<?php
session_start();
if (isset($_SESSION['admin_id'])) {
    header("Location: admin_dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | BEAUTY MAKEUP</title>
    <link rel="stylesheet" href="style.css?v=1.1">
    <style>
        .admin-login-body {
            background-color: #0d0d0d;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body class="admin-login-body">
    <div class="login-container">
        <div class="form-box">
            <h2>Admin Login</h2>
            <?php if (isset($_GET['error'])): ?>
                <p style="color:red; text-align:center; margin-bottom:1rem;">Credenciales incorrectas.</p>
            <?php endif; ?>
            <form action="auth_admin.php" method="POST">
                <div class="input-box">
                    <input type="text" name="username" placeholder="Usuario o ID" required>
                </div>
                <div class="input-box">
                    <input type="password" name="password" placeholder="Contraseña" required>
                </div>
                <div style="text-align: center;">
                    <button type="submit" class="cta-button" style="width: 100%; max-width: 90%;">Ingresar</button>
                    <a href="index.php" style="display: block; margin-top: 1rem; color: #666; font-size: 0.8rem; text-decoration: none;">Volver al sitio</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
