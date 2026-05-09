<?php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $telefono = $_POST['telefono'];

    // Validar si el email ya existe
    $check_email = "SELECT id FROM clientes WHERE email = ?";
    $stmt_check = $conexion->prepare($check_email);
    $stmt_check->bind_param("s", $email);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        echo "<script>
                alert('Este correo ya está registrado.');
                window.location.href = 'login.php';
              </script>";
        $stmt_check->close();
        exit();
    }
    $stmt_check->close();

    // Insertar nuevo usuario
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO clientes (nombre, email, telefono, password) VALUES (?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ssss", $nombre, $email, $telefono, $hashed_password);
        if ($stmt->execute()) {
            $user_id = $stmt->insert_id;

            // INTEGRACIÓN N8N - Notificar nuevo usuario
            include_once 'n8n_send_data.php';
            $resultado_n8n = enviarAn8n('nuevo_usuario', [
                'user_id' => $user_id,
                'nombre' => $nombre,
                'email' => $email,
                'telefono' => $telefono,
                'fecha_registro' => date('Y-m-d H:i:s')
            ]);

            // Lógica de inicio de sesión automático
            session_start();
            $_SESSION['user_id'] = $user_id;
            $_SESSION['user_name'] = $nombre;
            $_SESSION['user_email'] = $email;
            $_SESSION['user_phone'] = $telefono;

            echo "<script>
                alert('Su registro ha sido verificado.');
                window.location.href = 'index.php';
              </script>";

        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error: " . $conexion->error;
    }
    $conexion->close();
}
?>