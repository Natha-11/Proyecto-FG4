<?php
include 'conexion.php';
$res = $conexion->query('SELECT * FROM admins');
while($row = $res->fetch_assoc()) {
    echo "ID: " . $row['id'] . " | User: " . $row['username'] . " | Pass: " . $row['password'] . "\n";
}
?>
