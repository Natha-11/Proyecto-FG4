<?php
/**
 * Manejador de Webhook de n8n
 * Recibe y procesa datos enviados desde workflows de n8n
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Manejar preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

include 'conexion.php';

// Función para registrar logs
function registrarLog($conexion, $evento, $datos, $respuesta, $estado)
{
    $sql = "INSERT INTO n8n_logs (evento, datos_enviados, respuesta, estado) VALUES (?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("ssss", $evento, $datos, $respuesta, $estado);
        $stmt->execute();
        $stmt->close();
    }
}

// Función para enviar respuesta JSON
function enviarRespuesta($codigo, $mensaje, $datos = null, $error = null)
{
    http_response_code($codigo);
    $respuesta = [
        'success' => ($codigo >= 200 && $codigo < 300),
        'message' => $mensaje,
        'timestamp' => date('Y-m-d H:i:s')
    ];
    if ($datos)
        $respuesta['data'] = $datos;
    if ($error)
        $respuesta['error'] = $error;
    echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);
    exit();
}

// Solo permitir POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    enviarRespuesta(405, 'Método no permitido', null, 'Solo se acepta POST');
}

// Leer datos JSON del body
$input = file_get_contents('php://input');
$datos = json_decode($input, true);

// Validar que llegaron datos
if (!$datos) {
    registrarLog($conexion, 'error_json', $input, 'JSON inválido', 'error');
    enviarRespuesta(400, 'Datos JSON inválidos', null, 'No se pudo decodificar JSON');
}

// Registrar recepción
$datos_json = json_encode($datos, JSON_UNESCAPED_UNICODE);

// Determinar tipo de acción
$accion = isset($datos['accion']) ? $datos['accion'] : (isset($datos['tipo']) ? $datos['tipo'] : 'desconocida');

try {
    switch ($accion) {
        case 'crear_reserva':
        case 'nueva_reserva':
            // Validar campos requeridos
            $campos_requeridos = ['nombre', 'servicio', 'fecha', 'hora'];
            foreach ($campos_requeridos as $campo) {
                if (!isset($datos[$campo]) || empty($datos[$campo])) {
                    throw new Exception("Campo requerido faltante: $campo");
                }
            }

            $nombre = trim($datos['nombre']);
            $servicio = trim($datos['servicio']);
            $fecha = $datos['fecha'];
            $hora = $datos['hora'];
            $cliente_id = isset($datos['cliente_id']) ? (int) $datos['cliente_id'] : 0;

            // Validar formato de fecha
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) {
                throw new Exception('Formato de fecha inválido. Use YYYY-MM-DD');
            }

            // Validar formato de hora
            if (!preg_match('/^\d{2}:\d{2}(:\d{2})?$/', $hora)) {
                throw new Exception('Formato de hora inválido. Use HH:MM');
            }

            // Verificar disponibilidad
            $check_sql = "SELECT id FROM reservas WHERE fecha = ? AND hora = ?";
            $stmt_check = $conexion->prepare($check_sql);
            $stmt_check->bind_param("ss", $fecha, $hora);
            $stmt_check->execute();
            $stmt_check->store_result();

            if ($stmt_check->num_rows > 0) {
                $stmt_check->close();
                throw new Exception('Esa hora ya está reservada');
            }
            $stmt_check->close();

            // Insertar reserva
            $sql = "INSERT INTO reservas (cliente_id, nombre_cliente, servicio, fecha, hora) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("issss", $cliente_id, $nombre, $servicio, $fecha, $hora);

            if ($stmt->execute()) {
                $reserva_id = $stmt->insert_id;
                $stmt->close();

                $respuesta_datos = [
                    'reserva_id' => $reserva_id,
                    'nombre' => $nombre,
                    'servicio' => $servicio,
                    'fecha' => $fecha,
                    'hora' => $hora,
                    'confirmacion' => 'RES-' . str_pad($reserva_id, 6, '0', STR_PAD_LEFT)
                ];

                registrarLog($conexion, 'crear_reserva', $datos_json, json_encode($respuesta_datos), 'success');
                enviarRespuesta(201, 'Reserva creada exitosamente', $respuesta_datos);
            } else {
                throw new Exception('Error al crear reserva: ' . $stmt->error);
            }
            break;

        case 'consultar_disponibilidad':
            $fecha = isset($datos['fecha']) ? $datos['fecha'] : null;

            if (!$fecha) {
                throw new Exception('Fecha requerida para consultar disponibilidad');
            }

            $sql = "SELECT hora FROM reservas WHERE fecha = ? ORDER BY hora ASC";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("s", $fecha);
            $stmt->execute();
            $result = $stmt->get_result();

            $horas_reservadas = [];
            while ($row = $result->fetch_assoc()) {
                $horas_reservadas[] = substr($row['hora'], 0, 5);
            }
            $stmt->close();

            // Horas de negocio
            $horas_totales = ["09:00", "10:00", "11:00", "12:00", "13:00", "14:00", "15:00", "16:00", "17:00", "18:00"];
            $horas_disponibles = array_diff($horas_totales, $horas_reservadas);

            $respuesta_datos = [
                'fecha' => $fecha,
                'horas_disponibles' => array_values($horas_disponibles),
                'horas_reservadas' => $horas_reservadas,
                'total_disponibles' => count($horas_disponibles)
            ];

            registrarLog($conexion, 'consultar_disponibilidad', $datos_json, json_encode($respuesta_datos), 'success');
            enviarRespuesta(200, 'Disponibilidad consultada', $respuesta_datos);
            break;

        case 'listar_reservas':
            $limite = isset($datos['limite']) ? (int) $datos['limite'] : 50;
            $fecha_desde = isset($datos['fecha_desde']) ? $datos['fecha_desde'] : date('Y-m-d');

            $sql = "SELECT id, nombre_cliente, servicio, fecha, hora, fecha_reserva 
                    FROM reservas 
                    WHERE fecha >= ? 
                    ORDER BY fecha ASC, hora ASC 
                    LIMIT ?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("si", $fecha_desde, $limite);
            $stmt->execute();
            $result = $stmt->get_result();

            $reservas = [];
            while ($row = $result->fetch_assoc()) {
                $reservas[] = [
                    'id' => $row['id'],
                    'nombre' => $row['nombre_cliente'],
                    'servicio' => $row['servicio'],
                    'fecha' => $row['fecha'],
                    'hora' => substr($row['hora'], 0, 5),
                    'fecha_creacion' => $row['fecha_reserva'],
                    'codigo' => 'RES-' . str_pad($row['id'], 6, '0', STR_PAD_LEFT)
                ];
            }
            $stmt->close();

            $respuesta_datos = [
                'total' => count($reservas),
                'reservas' => $reservas
            ];

            registrarLog($conexion, 'listar_reservas', $datos_json, json_encode(['total' => count($reservas)]), 'success');
            enviarRespuesta(200, 'Reservas obtenidas', $respuesta_datos);
            break;

        case 'estadisticas':
            $fecha_inicio = isset($datos['fecha_inicio']) ? $datos['fecha_inicio'] : date('Y-m-01');
            $fecha_fin = isset($datos['fecha_fin']) ? $datos['fecha_fin'] : date('Y-m-t');

            // Total de reservas
            $sql_total = "SELECT COUNT(*) as total FROM reservas WHERE fecha BETWEEN ? AND ?";
            $stmt = $conexion->prepare($sql_total);
            $stmt->bind_param("ss", $fecha_inicio, $fecha_fin);
            $stmt->execute();
            $total = $stmt->get_result()->fetch_assoc()['total'];
            $stmt->close();

            // Servicios más solicitados
            $sql_servicios = "SELECT servicio, COUNT(*) as cantidad FROM reservas WHERE fecha BETWEEN ? AND ? GROUP BY servicio ORDER BY cantidad DESC";
            $stmt = $conexion->prepare($sql_servicios);
            $stmt->bind_param("ss", $fecha_inicio, $fecha_fin);
            $stmt->execute();
            $result = $stmt->get_result();
            $servicios = [];
            while ($row = $result->fetch_assoc()) {
                $servicios[] = $row;
            }
            $stmt->close();

            $respuesta_datos = [
                'periodo' => ['inicio' => $fecha_inicio, 'fin' => $fecha_fin],
                'total_reservas' => $total,
                'servicios_populares' => $servicios
            ];

            registrarLog($conexion, 'estadisticas', $datos_json, json_encode($respuesta_datos), 'success');
            enviarRespuesta(200, 'Estadísticas generadas', $respuesta_datos);
            break;

        default:
            throw new Exception("Acción desconocida: $accion");
    }

} catch (Exception $e) {
    registrarLog($conexion, $accion, $datos_json, $e->getMessage(), 'error');
    enviarRespuesta(400, 'Error al procesar solicitud', null, $e->getMessage());
}

$conexion->close();
?>