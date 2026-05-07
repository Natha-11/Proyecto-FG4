<?php
include 'conexion.php';

header('Content-Type: application/json');

// Obtener todas las reservas desde hoy en adelante
$today = date('Y-m-d');
$sql = "SELECT fecha, hora, nombre_cliente, servicio FROM reservas WHERE fecha >= ? ORDER BY fecha ASC, hora ASC";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $today);
$stmt->execute();
$result = $stmt->get_result();

$reservations = [];
while ($row = $result->fetch_assoc()) {
    $reservations[] = $row;
}

echo json_encode($reservations);

$stmt->close();
$conexion->close();
?>