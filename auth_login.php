<?php
session_start();
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_type = $_POST['user_type'] ?? 'cliente';

    if ($user_type === 'admin') {
        // Redirigir a auth_admin.php con los datos de admin
        $_POST['username'] = $_POST['admin_username'];
        include 'auth_admin.php';
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