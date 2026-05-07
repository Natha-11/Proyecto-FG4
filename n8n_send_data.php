<?php
/**
 * Enviar Datos a n8n
 * Función helper para enviar datos al webhook de n8n
 * Se puede incluir en otros archivos PHP para notificar eventos
 */

/**
 * Envía datos al webhook de n8n
 * 
 * @param string $evento Tipo de evento (nueva_reserva, nuevo_usuario, etc.)
 * @param array $datos Datos del evento
 * @param string $webhook_url (Opcional) URL del webhook personalizada
 * @return array Respuesta con success (bool) y mensaje
 */
function enviarAn8n($evento, $datos, $webhook_url = null)
{
    // Configuración de n8n
    // 'prod' = Webhook permanente (debes activar el workflow en n8n)
    // 'test' = Webhook temporal (debes darle a 'Execute Workflow' en n8n)
    $env = 'test'; // Cambiado a test para coincidir con tu link de prueba actual
    $base_url = 'https://nathacc18.app.n8n.cloud';
    $path = 'formulario-contacto';

    if (!$webhook_url) {
        $type = ($env === 'prod') ? 'webhook' : 'webhook-test';
        $webhook_url = "{$base_url}/{$type}/{$path}";
    }

    // Preparar payload plano para mapeo más fácil en n8n
    $payload = array_merge([
        'evento' => $evento,
        'timestamp_sistema' => date('Y-m-d H:i:s'),
        'origen' => 'glow-belleza-system'
    ], $datos);

    $payload_json = json_encode($payload, JSON_UNESCAPED_UNICODE);

    // Configurar cURL
    $ch = curl_init($webhook_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload_json);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($payload_json)
    ]);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

    // Ejecutar
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    // Log del resultado (opcional)
    if (function_exists('registrarLogN8n')) {
        registrarLogN8n($evento, $payload_json, $response, $http_code >= 200 && $http_code < 300 ? 'success' : 'error');
    }

    // Retornar resultado
    if ($error) {
        return [
            'success' => false,
            'message' => 'Error de conexión: ' . $error,
            'http_code' => $http_code
        ];
    }

    return [
        'success' => ($http_code >= 200 && $http_code < 300),
        'message' => $http_code >= 200 && $http_code < 300 ? 'Enviado a n8n exitosamente' : 'Error al enviar a n8n (HTTP ' . $http_code . '): ' . $response,
        'http_code' => $http_code,
        'response' => $response
    ];
}

/**
 * Función helper para registrar logs de n8n
 */
function registrarLogN8n($evento, $datos, $respuesta, $estado)
{
    // Conectar a BD
    $host = "localhost";
    $user = "root";
    $pass = "";
    $db = "registro";

    $conn = new mysqli($host, $user, $pass, $db);

    if ($conn->connect_error) {
        return false;
    }

    $sql = "INSERT INTO n8n_logs (evento, datos_enviados, respuesta, estado) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ssss", $evento, $datos, $respuesta, $estado);
        $stmt->execute();
        $stmt->close();
    }

    $conn->close();
    return true;
}

// Si se llama directamente (para testing)
if (basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME'])) {
    header('Content-Type: application/json');

    // Ejemplo de uso para testing
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = file_get_contents('php://input');
        $datos = json_decode($input, true);

        $evento = isset($datos['evento']) ? $datos['evento'] : 'test';
        $datos_evento = isset($datos['datos']) ? $datos['datos'] : ['mensaje' => 'Test desde n8n_send_data.php'];

        $resultado = enviarAn8n($evento, $datos_evento);
        echo json_encode($resultado, JSON_UNESCAPED_UNICODE);
    } else {
        // GET request - mostrar información
        echo json_encode([
            'message' => 'Ayuda para enviar datos a n8n',
            'usage' => 'Incluye este archivo y llama a enviarAn8n($evento, $datos)',
            'webhook_url' => 'https://nathacc18.app.n8n.cloud/webhook-test/antigravity-reservas',
            'test' => 'Envía JSON mediante POST a este endpoint para probar el envío a n8n'
        ], JSON_UNESCAPED_UNICODE);
    }
}
?>