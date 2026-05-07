<?php
include 'conexion.php';
$username = 'temp_admin';
$password = password_hash('admin123', PASSWORD_DEFAULT);
$email = 'temp@example.com';
$stmt = $conexion->prepare("INSERT INTO admins (username, password, email) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $username, $password, $email);
if ($stmt->execute()) {
    echo "Temp admin created successfully.\n";
} else {
    echo "Error: " . $stmt->error . "\n";
}
?>
