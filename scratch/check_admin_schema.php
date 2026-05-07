<?php
include 'conexion.php';
$result = $conexion->query("DESCRIBE admins");
while($row = $result->fetch_assoc()) {
    print_r($row);
}
?>
