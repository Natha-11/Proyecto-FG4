<?php
include 'conexion.php';
$conexion->query('UPDATE servicios SET activo = 0 WHERE id IN (1, 2, 3, 4, 5, 6, 7, 8)');
$conexion->query('UPDATE servicios SET activo = 1 WHERE id IN (9, 10, 11, 12, 13, 14, 16)');
echo "Database updated.\n";
