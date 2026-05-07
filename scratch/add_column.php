<?php
include 'conexion.php';
$sql = "ALTER TABLE admins ADD COLUMN ultimo_acceso DATETIME DEFAULT NULL";
if ($conexion->query($sql)) {
    echo "Column 'ultimo_acceso' added successfully.\n";
} else {
    echo "Error: " . $conexion->error . "\n";
}
?>
