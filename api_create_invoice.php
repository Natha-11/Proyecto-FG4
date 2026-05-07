<?php
session_start();
include 'conexion.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuario no autenticado']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['cart']) || empty($input['cart'])) {
    echo json_encode(['success' => false, 'message' => 'El carrito está vacío']);
    exit;
}

$cliente_id = $_SESSION['user_id'];
$cliente_nombre = $_SESSION['user_name'];
$total = $input['total'];
$cart = $input['cart'];
$nro_factura = "INV-" . strtoupper(substr(uniqid(), -6));

// Iniciar transacción
$conexion->begin_transaction();

try {
    // Insertar factura
    $metodo_pago = isset($input['metodo_pago']) ? $input['metodo_pago'] : 'efectivo';
    $stmt = $conexion->prepare("INSERT INTO facturas (cliente_id, nro_factura, total, metodo_pago) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isds", $cliente_id, $nro_factura, $total, $metodo_pago);
    $stmt->execute();
    $factura_id = $stmt->insert_id;
    $stmt->close();

    // Insertar ítems
    $stmt_item = $conexion->prepare("INSERT INTO factura_items (factura_id, producto, precio) VALUES (?, ?, ?)");
    $stmt_res = $conexion->prepare("INSERT INTO reservas (cliente_id, nombre_cliente, telefono, servicio, fecha, hora) VALUES (?, ?, ?, ?, ?, ?)");
    
    // Definir duraciones de servicios
    $serviceDurations = [
        'natural' => 1,
        'soft-glam' => 2,
        'smokey-eyes' => 2,
        'editorial' => 3,
        'bridal' => 3,
        'glam-night' => 2,
        'eyes-only' => 1,
        'cejas' => 1
    ];

    foreach ($cart as $item) {
        $stmt_item->bind_param("isd", $factura_id, $item['name'], $item['price']);
        $stmt_item->execute();
        
        // Si es una reserva, insertarla también en la tabla de reservas
        if (isset($item['metadata']) && $item['metadata']['type'] === 'reservation') {
            $m = $item['metadata'];
            $servicio = $m['servicio'];
            $fecha = $m['fecha'];
            $horas = explode(',', $m['hora']); // Se esperan varias horas separadas por coma
            
            foreach ($horas as $hora_slot) {
                $trimmed_hora = trim($hora_slot);
                $stmt_res->bind_param("isssss", $cliente_id, $m['nombre'], $m['telefono'], $servicio, $fecha, $trimmed_hora);
                $stmt_res->execute();
            }
            
            // Notificar a n8n (solo una vez)
            include_once 'n8n_send_data.php';
            enviarAn8n('nueva_reserva', [
                'factura_nro' => $nro_factura,
                'nombre' => $m['nombre'],
                'email' => $m['email'],
                'telefono' => $m['telefono'],
                'servicio' => $servicio,
                'fecha' => $fecha,
                'hora' => $m['hora']
            ]);
        }
    }
    $stmt_item->close();
    $stmt_res->close();

    $conexion->commit();

    echo json_encode([
        'success' => true,
        'invoice_id' => $factura_id,
        'invoice' => [
            'nro' => $nro_factura,
            'fecha' => date('d/m/Y H:i'),
            'cliente' => $cliente_nombre,
            'items' => $cart,
            'total' => $total
        ]
    ]);

} catch (Exception $e) {
    $conexion->rollback();
    echo json_encode(['success' => false, 'message' => 'Error al procesar la factura: ' . $e->getMessage()]);
}

$conexion->close();
?>