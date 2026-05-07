<?php
session_start();
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = !empty($_POST['email']) ? $_POST['email'] : null;
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validar contraseñas
    if ($password !== $confirm_password) {
        header("Location: admin_register.php?error=pass_mismatch");
        exit();
    }

    // Verificar si el usuario ya existe
    $stmt = $conexion->prepare("SELECT id FROM admins WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        header("Location: admin_register.php?error=user_exists");
        exit();
    }

    // Hashear la contraseña
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Insertar el nuevo administrador
    $stmt = $conexion->prepare("INSERT INTO admins (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $passwordHash);

    if ($stmt->execute()) {
        header("Location: login.php?success=1");
        exit();
    } else {
        header("Location: admin_register.php?error=db_error");
        exit();
    }
} else {
    header("Location: admin_register.php");
    exit();
}
?>
