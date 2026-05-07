<?php
/**
 * CONFIGURACIÓN DE CONEXIÓN A LA BASE DE DATOS
 * Este archivo define los parámetros para conectarse al servidor MySQL.
 */
$host = "localhost"; // Servidor de base de datos (usando IP para estabilidad)
$user = "root";      // Usuario por defecto en XAMPP
$pass = "";          // Contraseña (vacía por defecto)
$db = "registro";  // Nombre de la base de datos

// Crear la conexión utilizando la clase mysqli
$conexion = new mysqli($host, $user, $pass, $db);

// Comprobar si hubo errores en la conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Asegurar que los datos se manejen en UTF-8 para caracteres especiales
$conexion->set_charset("utf8mb4");
?>