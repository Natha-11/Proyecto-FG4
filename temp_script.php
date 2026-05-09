<?php
include 'conexion.php';
$res = $conexion->query('SELECT id, nombre, precio, imagen, categoria, activo FROM servicios');
while($row = $res->fetch_assoc()) {
    echo json_encode($row) . PHP_EOL;
}
