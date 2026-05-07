<?php
include 'conexion.php';
$res = $conexion->query('DESCRIBE admins');
while($row = $res->fetch_assoc()) {
    echo $row['Field'] . " (" . $row['Type'] . ")\n";
}
?>
