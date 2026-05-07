<?php
include 'conexion.php';
$result = $conexion->query("DESCRIBE usuario");
while ($row = $result->fetch_assoc()) {
    print_r($row);
}
?>