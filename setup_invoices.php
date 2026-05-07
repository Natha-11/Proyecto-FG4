<?php
include 'conexion.php';

// Crear tabla de facturas
$sql_facturas = "CREATE TABLE IF NOT EXISTS facturas (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT(11) NOT NULL,
    nro_factura VARCHAR(20) UNIQUE NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

// Crear tabla de ítems de factura
$sql_items = "CREATE TABLE IF NOT EXISTS factura_items (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    factura_id INT(11) UNSIGNED NOT NULL,
    producto VARCHAR(100) NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (factura_id) REFERENCES facturas(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

echo "<h2>⚙️ Setup de Facturación</h2>";

if ($conexion->query($sql_facturas) === TRUE) {
    echo "✅ Tabla 'facturas' creada/verificada.<br>";
} else {
    echo "❌ Error 'facturas': " . $conexion->error . "<br>";
}

if ($conexion->query($sql_items) === TRUE) {
    echo "✅ Tabla 'factura_items' creada/verificada.<br>";
} else {
    echo "❌ Error 'factura_items': " . $conexion->error . "<br>";
}

$conexion->close();
?>