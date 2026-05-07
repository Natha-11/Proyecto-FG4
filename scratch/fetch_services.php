<?php
include 'conexion.php';
$sql = "SELECT * FROM servicios";
$result = $conexion->query($sql);
$services = [];
while($row = $result->fetch_assoc()) {
    $services[] = $row;
}
echo json_encode($services, JSON_PRETTY_PRINT);
?>
