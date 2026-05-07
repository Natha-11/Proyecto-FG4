<?php
include 'conexion.php';
$sql = "SELECT * FROM servicios WHERE id = 15 OR nombre LIKE '%Limpieza%'";
$result = $conexion->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo json_encode($row, JSON_PRETTY_PRINT) . "\n";
    }
} else {
    echo "No service found with ID 15 or name like Limpieza";
}
?>
