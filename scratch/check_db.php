<?php
include 'conexion.php';

echo "--- Tabla CLIENTES ---\n";
$res = $conexion->query("DESCRIBE clientes");
if ($res) {
    while ($row = $res->fetch_assoc()) {
        echo $row['Field'] . " (" . $row['Type'] . ")\n";
    }
} else {
    echo "Error describing clientes: " . $conexion->error . "\n";
}

echo "\n--- Intentando agregar columna 'telefono' de nuevo ---\n";
$sql = "ALTER TABLE clientes ADD COLUMN IF NOT EXISTS telefono VARCHAR(20) DEFAULT NULL";
if ($conexion->query($sql)) {
    echo "SQL ejecutado con éxito.\n";
} else {
    echo "Error ejecutando SQL: " . $conexion->error . "\n";
}

echo "\n--- Verificando de nuevo ---\n";
$res = $conexion->query("DESCRIBE clientes");
if ($res) {
    while ($row = $res->fetch_assoc()) {
        echo $row['Field'] . " (" . $row['Type'] . ")\n";
    }
}

$conexion->close();
?>
