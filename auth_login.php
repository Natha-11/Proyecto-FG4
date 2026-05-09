<?php
session_start();
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_type = $_POST['user_type'] ?? 'cliente';

    if ($user_type === 'admin') {
        $ident = $_POST['admin_username'];
        $pass = $_POST['password'];

        if (ctype_digit($ident)) {
            $stmt = $conexion->prepare("SELECT id, password FROM admins WHERE id = ? OR username = ?");
            $stmt->bind_param("is", $ident, $ident);
        } else {
            $stmt = $conexion->prepare("SELECT id, password FROM admins WHERE username = ?");
            $stmt->bind_param("s", $ident);
        }
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $admin = $result->fetch_assoc();
            $storedPassword = $admin['password'];
            $isValid = false;

            if (password_verify($pass, $storedPassword)) {
                $isValid = true;
            } elseif ($pass === $storedPassword) {
                // Soporta contraseñas existentes en texto plano y las migra a hash seguro.
                $isValid = true;
                $newHash = password_hash($pass, PASSWORD_DEFAULT);
                $updateStmt = $conexion->prepare("UPDATE admins SET password = ? WHERE id = ?");
                $updateStmt->bind_param("si", $newHash, $admin['id']);
                $updateStmt->execute();
            }

            if ($isValid) {
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_user'] = $ident;
                
                // Actualizar último acceso
                $updateAccess = $conexion->prepare("UPDATE admins SET ultimo_acceso = NOW() WHERE id = ?");
                $updateAccess->bind_param("i", $admin['id']);
                $updateAccess->execute();

                header("Location: admin_dashboard.php");
                exit();
            }
        }
        header("Location: login.php?error=1");
        exit();
    } else {
        // Login normal de cliente
        $email = $_POST['email'];
        $password = $_POST['password'];

        $sql = "SELECT id, nombre, email, telefono, password FROM clientes WHERE email = ?";
        $stmt = $conexion->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $row = $result->fetch_assoc();
                if (password_verify($password, $row['password'])) {
                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['user_name'] = $row['nombre'];
                    $_SESSION['user_email'] = $row['email'];
                    $_SESSION['user_phone'] = $row['telefono'];
                    header("Location: index.php");
                    exit();
                } else {
                    header("Location: login.php?error=wrongpass");
                    exit();
                }
            } else {
                header("Location: login.php?error=notfound");
                exit();
            }
            $stmt->close();
        } else {
            echo "Error: " . $conexion->error;
        }
    }
    $conexion->close();
}
?>