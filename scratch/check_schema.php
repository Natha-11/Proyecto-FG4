<?php
include 'conexion.php';

echo "--- Usuarios ---\n";
$res = $conexion->query("DESCRIBE usuarios");
if ($res) {
    while($row = $res->fetch_assoc()) {
        echo $row['Field'] . " - " . $row['Type'] . "\n";
    }
}

echo "\n--- Reservas ---\n";
$res = $conexion->query("DESCRIBE reservas");
if ($res) {
    while($row = $res->fetch_assoc()) {
        echo $row['Field'] . " - " . $row['Type'] . "\n";
    }
}

echo "\n--- Facturas/Invoices (if exist) ---\n";
$res = $conexion->query("SHOW TABLES LIKE 'invoices'");
if ($res->num_rows > 0) {
    $res = $conexion->query("DESCRIBE invoices");
    while($row = $res->fetch_assoc()) {
        echo $row['Field'] . " - " . $row['Type'] . "\n";
    }
}
?>
