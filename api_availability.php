<?php
include 'conexion.php';

header('Content-Type: application/json');

if (isset($_GET['fecha'])) {
    $fecha = $_GET['fecha'];

    // Obtener horas ya reservadas para esa fecha
    $sql = "SELECT bora FROM reservas WHERE fecha = ?"; // Comprobación de error tipográfico: nombre de columna 'hora'
    // Usaré 'hora' como se define en setup_db.php
    $sql = "SELECT bora FROM reservas WHERE fecha = ?";
    // Espera, vamos a comprobar setup_db.php. Dice 'hora'.
    $sql = "SELECT hora FROM reservas WHERE fecha = ?";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $fecha);
    $stmt->execute();
    $result = $stmt->get_result();

    $reserved_hours = [];
    while ($row = $result->fetch_assoc()) {
        $reserved_hours[] = $row['hora']; // e.g. "10:00"
    }

    echo json_encode(['reserved' => $reserved_hours]);
    $stmt->close();
} else {
    echo json_encode(['error' => 'Fecha no proporcionada']);
}

$conexion->close();
?>