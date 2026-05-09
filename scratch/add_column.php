<?php
include 'conexion.php';

$sql = "ALTER TABLE clientes ADD COLUMN IF NOT EXISTS telefono VARCHAR(20) DEFAULT NULL";
if ($conexion->query($sql) === TRUE) {
    echo "Columna 'telefono' agregada o ya existía.\n";
} else {
    echo "Error agregando columna: " . $conexion->error . "\n";
}

// También agregar columna para el conteo de citas si no existe, o simplemente usar COUNT(*)
// El usuario quiere ver en su perfil cuántas veces agendó.

$conexion->close();
?>
