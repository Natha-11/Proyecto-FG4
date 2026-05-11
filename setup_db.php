<?php
include 'conexion.php';

$sql = "CREATE TABLE IF NOT EXISTS clientes (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

$sql_reservas = "CREATE TABLE IF NOT EXISTS reservas (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT(6),
    nombre_cliente VARCHAR(100),
    servicio VARCHAR(50),
    fecha DATE NOT NULL,
    hora VARCHAR(10) NOT NULL,
    fecha_reserva TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conexion->query($sql) === TRUE) {
    echo "Tabla 'clientes' verificada. <br>";
} else {
    echo "Error 'clientes': " . $conexion->error . "<br>";
}

if ($conexion->query($sql_reservas) === TRUE) {
    echo "Tabla 'reservas' verificada. <br>";
} else {
    echo "Error 'reservas': " . $conexion->error . "<br>";
}

$conexion->close();
?>