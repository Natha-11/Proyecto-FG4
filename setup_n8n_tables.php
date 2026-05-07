<?php
/**
 * Configuración de Tablas de Integración de n8n
 * Crea las tablas necesarias para la integración con n8n
 */

include 'conexion.php';

echo "<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Setup n8n Tables</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; background: #f5f5f5; }
        .container { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #333; border-bottom: 3px solid #d4af37; padding-bottom: 10px; }
        .success { background: #d4edda; border-left: 4px solid #28a745; padding: 15px; margin: 15px 0; border-radius: 5px; color: #155724; }
        .error { background: #f8d7da; border-left: 4px solid #dc3545; padding: 15px; margin: 15px 0; border-radius: 5px; color: #721c24; }
        .info { background: #d1ecf1; border-left: 4px solid #17a2b8; padding: 15px; margin: 15px 0; border-radius: 5px; color: #0c5460; }
        code { background: #f4f4f4; padding: 2px 6px; border-radius: 3px; font-family: 'Courier New', monospace; }
        .table-info { margin-top: 20px; }
        .table-info h3 { color: #d4af37; }
    </style>
</head>
<body>
<div class='container'>
<h1>🔧 Configuración de Tablas n8n</h1>";

// Tabla n8n_logs
$sql_logs = "CREATE TABLE IF NOT EXISTS n8n_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    evento VARCHAR(100) NOT NULL,
    datos_enviados TEXT,
    respuesta TEXT,
    estado VARCHAR(20) DEFAULT 'pending',
    fecha_hora TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_evento (evento),
    INDEX idx_fecha (fecha_hora),
    INDEX idx_estado (estado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

echo "<div class='info'><strong>Creando tabla:</strong> <code>n8n_logs</code></div>";

if ($conexion->query($sql_logs) === TRUE) {
    echo "<div class='success'>✅ Tabla <code>n8n_logs</code> creada/verificada exitosamente</div>";

    // Mostrar info de la tabla
    echo "<div class='table-info'>
        <h3>Información de n8n_logs:</h3>
        <p>Esta tabla registra todas las interacciones con n8n, incluyendo:</p>
        <ul>
            <li><strong>evento</strong>: Tipo de evento (nueva_reserva, notificación, etc.)</li>
            <li><strong>datos_enviados</strong>: Payload JSON enviado</li>
            <li><strong>respuesta</strong>: Respuesta recibida de n8n</li>
            <li><strong>estado</strong>: success, error, pending</li>
            <li><strong>fecha_hora</strong>: Timestamp del evento</li>
        </ul>
    </div>";
} else {
    echo "<div class='error'>❌ Error al crear <code>n8n_logs</code>: " . $conexion->error . "</div>";
}

// Tabla notificaciones
$sql_notificaciones = "CREATE TABLE IF NOT EXISTS notificaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reserva_id INT NULL,
    tipo VARCHAR(50) NOT NULL,
    destinatario VARCHAR(100),
    mensaje TEXT,
    estado VARCHAR(20) DEFAULT 'pendiente',
    fecha_envio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_entregado TIMESTAMP NULL,
    error_mensaje TEXT,
    INDEX idx_reserva (reserva_id),
    INDEX idx_tipo (tipo),
    INDEX idx_estado (estado),
    INDEX idx_fecha (fecha_envio)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

echo "<div class='info'><strong>Creando tabla:</strong> <code>notificaciones</code></div>";

if ($conexion->query($sql_notificaciones) === TRUE) {
    echo "<div class='success'>✅ Tabla <code>notificaciones</code> creada/verificada exitosamente</div>";

    echo "<div class='table-info'>
        <h3>Información de notificaciones:</h3>
        <p>Esta tabla registra el historial de notificaciones enviadas:</p>
        <ul>
            <li><strong>reserva_id</strong>: ID de la reserva relacionada</li>
            <li><strong>tipo</strong>: email, sms, whatsapp, recordatorio</li>
            <li><strong>destinatario</strong>: Email, teléfono o identificador del destinatario</li>
            <li><strong>mensaje</strong>: Contenido de la notificación</li>
            <li><strong>estado</strong>: pendiente, enviado, entregado, error</li>
            <li><strong>fecha_envio</strong>: Timestamp de envío</li>
            <li><strong>fecha_entregado</strong>: Timestamp de confirmación de entrega</li>
        </ul>
    </div>";
} else {
    echo "<div class='error'>❌ Error al crear <code>notificaciones</code>: " . $conexion->error . "</div>";
}

// Tabla estadisticas (opcional)
$sql_stats = "CREATE TABLE IF NOT EXISTS estadisticas_cache (
    id INT AUTO_INCREMENT PRIMARY KEY,
    metrica VARCHAR(100) NOT NULL,
    valor TEXT,
    periodo_inicio DATE,
    periodo_fin DATE,
    fecha_calculo TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_metrica_periodo (metrica, periodo_inicio, periodo_fin)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

echo "<div class='info'><strong>Creando tabla:</strong> <code>estadisticas_cache</code></div>";

if ($conexion->query($sql_stats) === TRUE) {
    echo "<div class='success'>✅ Tabla <code>estadisticas_cache</code> creada/verificada exitosamente</div>";

    echo "<div class='table-info'>
        <h3>Información de estadisticas_cache:</h3>
        <p>Cache de estadísticas para mejorar rendimiento de reportes:</p>
        <ul>
            <li><strong>metrica</strong>: Nombre de la métrica (total_reservas, ingresos, etc.)</li>
            <li><strong>valor</strong>: Valor calculado (JSON para datos complejos)</li>
            <li><strong>periodo_inicio/fin</strong>: Rango de fechas del cálculo</li>
            <li><strong>fecha_calculo</strong>: Cuándo se calculó la estadística</li>
        </ul>
    </div>";
} else {
    echo "<div class='error'>❌ Error al crear <code>estadisticas_cache</code>: " . $conexion->error . "</div>";
}

// Verificar tablas creadas
echo "<h2 style='margin-top: 40px; color: #d4af37;'>📊 Verificación de Tablas</h2>";

$tables = ['n8n_logs', 'notificaciones', 'estadisticas_cache'];
foreach ($tables as $table) {
    $result = $conexion->query("SHOW TABLES LIKE '$table'");
    if ($result && $result->num_rows > 0) {
        $count_result = $conexion->query("SELECT COUNT(*) as total FROM $table");
        $count = $count_result->fetch_assoc()['total'];
        echo "<div class='success'>✅ Tabla <code>$table</code> existe - Registros: <strong>$count</strong></div>";
    } else {
        echo "<div class='error'>❌ Tabla <code>$table</code> no encontrada</div>";
    }
}

echo "
<div style='margin-top: 40px; padding: 20px; background: #f8f9fa; border-radius: 5px;'>
    <h3 style='color: #d4af37; margin-top: 0;'>✨ Configuración Completada</h3>
    <p>Las tablas para la integración con n8n han sido configuradas correctamente.</p>
    <p><strong>Próximos pasos:</strong></p>
    <ol>
        <li>Importar los workflows de n8n proporcionados</li>
        <li>Configurar credenciales de email/SMS en n8n</li>
        <li>Probar el endpoint: <code>n8n_webhook_handler.php</code></li>
        <li>Integrar <code>n8n_send_data.php</code> en tus flujos PHP</li>
    </ol>
</div>
</div>
</body>
</html>";

$conexion->close();
?>