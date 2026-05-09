<?php
session_start();
include 'conexion.php';

if (isset($_SESSION['admin_id'])) {
    header("Location: admin_dashboard.php");
    exit();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = !empty($_POST['email']) ? $_POST['email'] : null;
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $error = 'pass_mismatch';
    } else {
        $stmt = $conexion->prepare("SELECT id FROM admins WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $error = 'user_exists';
        } else {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conexion->prepare("INSERT INTO admins (username, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $email, $passwordHash);
            if ($stmt->execute()) {
                header("Location: login.php?success=1");
                exit();
            } else {
                $error = 'db_error';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Admin | Glow Belleza</title>
    <link rel="stylesheet" href="style.css?v=3.2">
    <style>
        .admin-login-body {
            background-color: #0d0d0d;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .form-box {
            background: rgba(255, 255, 255, 0.03);
            padding: 3rem;
            border-radius: 15px;
            border: 1px solid rgba(223, 207, 190, 0.1);
            width: 100%;
            max-width: 400px;
        }
        .form-box h2 {
            font-family: 'Cormorant Garamond', serif;
            color: #dfcfbe;
            font-size: 2.5rem;
            text-align: center;
            margin-bottom: 2rem;
            font-weight: 300;
        }
        .input-box {
            margin-bottom: 1.5rem;
        }
        .input-box input {
            width: 100%;
            padding: 12px;
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid #333;
            color: #fff;
            border-radius: 8px;
            outline: none;
        }
    </style>
</head>
<body class="admin-login-body">
    <div class="login-container">
        <div class="form-box">
            <h2>Nuevo Administrador</h2>
            <?php 
                $err_msg = $error ?: ($_GET['error'] ?? '');
                if ($err_msg): 
            ?>
                <p style="color:#ffaaaa; text-align:center; margin-bottom:1rem; font-size: 0.9rem;">
                    <?php 
                        if ($err_msg == 'user_exists') echo "El usuario ya existe.";
                        elseif ($err_msg == 'pass_mismatch') echo "Las contraseñas no coinciden.";
                        elseif ($err_msg == 'db_error') echo "Error en la base de datos.";
                        else echo "Ocurrió un error al registrar.";
                    ?>
                </p>
            <?php endif; ?>
            <form action="admin_register.php" method="POST">
                <div class="input-box">
                    <input type="text" name="username" placeholder="Nombre de usuario" required>
                </div>
                <div class="input-box">
                    <input type="email" name="email" placeholder="Correo electrónico (opcional)">
                </div>
                <div class="input-box">
                    <input type="password" name="password" placeholder="Contraseña" required>
                </div>
                <div class="input-box">
                    <input type="password" name="confirm_password" placeholder="Confirmar contraseña" required>
                </div>
                <div style="text-align: center;">
                    <button type="submit" class="cta-button" style="width: 100%;">Registrar Admin</button>
                    <a href="login.php" style="display: block; margin-top: 1.5rem; color: #888; font-size: 0.85rem; text-decoration: none; letter-spacing: 1px; text-transform: uppercase;">Volver al Login</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
