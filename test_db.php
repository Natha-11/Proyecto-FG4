<?php
include 'conexion.php';
$res = $conexion->query('SELECT nombre, imagen FROM servicios');
while($row = $res->fetch_assoc()) {
    
    print_r($row);
}
