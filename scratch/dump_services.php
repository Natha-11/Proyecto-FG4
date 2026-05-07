<?php
include 'conexion.php';
$res = $conexion->query("SELECT * FROM servicios");
$data = [];
while($row = $res->fetch_assoc()) {
    $data[] = $row;
}
echo json_encode($data);
?>
