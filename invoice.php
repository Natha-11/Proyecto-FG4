<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    echo "ID de factura no válido.";
    exit;
}

$factura_id = $_GET['id'];
$cliente_id = $_SESSION['user_id'];

// Obtener detalles de la factura
$stmt = $conexion->prepare("SELECT nro_factura, total, fecha FROM facturas WHERE id = ? AND cliente_id = ?");
$stmt->bind_param("ii", $factura_id, $cliente_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Factura no encontrada o no tienes permisos para verla.";
    exit;
}

$factura = $result->fetch_assoc();
$stmt->close();

// Obtener ítems de la factura
$stmt_items = $conexion->prepare("SELECT producto, precio FROM factura_items WHERE factura_id = ?");
$stmt_items->bind_param("i", $factura_id);
$stmt_items->execute();
$items_result = $stmt_items->get_result();
$items = $items_result->fetch_all(MYSQLI_ASSOC);
$stmt_items->close();
$conexion->close();

// Parsear fecha
$fecha_formato = date('d/m/Y g:i A', strtotime($factura['fecha'] ?? date('Y-m-d H:i:s')));
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprobante de Reserva | Luxury Glow</title>
    <link rel="stylesheet" href="style.css?v=4.0">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;600&family=Montserrat:wght@200;400;500&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #0d0d0d;
            color: #f8f1eb;
            font-family: 'Montserrat', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }
        .invoice-page-container {
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(223, 207, 190, 0.2);
            border-radius: 8px;
            padding: 3rem;
            max-width: 600px;
            width: 100%;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        }
        .invoice-header {
            text-align: center;
            border-bottom: 1px solid rgba(223, 207, 190, 0.1);
            padding-bottom: 2rem;
            margin-bottom: 2rem;
        }
        .invoice-header h1 {
            font-family: 'Cormorant Garamond', serif;
            color: #dfcfbe;
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }
        .invoice-header p {
            color: #888;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .invoice-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .invoice-details p {
            margin: 0;
            font-size: 0.95rem;
        }
        .invoice-details strong {
            color: #dfcfbe;
        }
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2rem;
        }
        .invoice-table th, .invoice-table td {
            padding: 1rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            text-align: left;
        }
        .invoice-table th {
            color: #888;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 1px;
            border-bottom: 1px solid rgba(223, 207, 190, 0.2);
        }
        .invoice-table td:last-child, .invoice-table th:last-child {
            text-align: right;
        }
        .invoice-total {
            text-align: right;
            font-size: 1.5rem;
            font-family: 'Cormorant Garamond', serif;
            color: #dfcfbe;
            margin-bottom: 3rem;
        }
        .actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }
        .btn {
            padding: 1rem 2rem;
            border-radius: 4px;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-size: 0.8rem;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s ease;
            text-align: center;
        }
        .btn-primary {
            background: #dfcfbe;
            color: #000;
            border: none;
        }
        .btn-primary:hover {
            background: #fff;
        }
        .btn-secondary {
            background: transparent;
            color: #dfcfbe;
            border: 1px solid #dfcfbe;
        }
        .btn-secondary:hover {
            background: rgba(223, 207, 190, 0.1);
        }
    </style>
</head>
<body>

<div class="invoice-page-container">
    <div class="invoice-header">
        <h1>BEAUTY MAKEUP</h1>
        <p>Comprobante de Reserva</p>
    </div>

    <div class="invoice-details">
        <p><strong>Nro Factura:</strong> <br><?php echo htmlspecialchars($factura['nro_factura']); ?></p>
        <p><strong>Fecha:</strong> <br><?php echo htmlspecialchars($fecha_formato); ?></p>
        <p><strong>Cliente:</strong> <br><?php echo htmlspecialchars($_SESSION['user_name']); ?></p>
    </div>

    <table class="invoice-table">
        <thead>
            <tr>
                <th>Servicio / Producto</th>
                <th>Monto</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
            <tr>
                <td><?php echo htmlspecialchars($item['producto']); ?></td>
                <td>$<?php echo number_format($item['precio'], 2); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="invoice-total">
        Total: $<?php echo number_format($factura['total'], 2); ?>
    </div>

    <div style="text-align: center; margin-bottom: 2rem; padding: 1.5rem; border-top: 1px solid rgba(223,207,190,0.1);">
        <p style="font-family: 'Cormorant Garamond', serif; font-style: italic; color: #dfcfbe; font-size: 1.2rem; line-height: 1.6;">
            "La belleza comienza en el momento en que decides ser tú misma."
        </p>
    </div>

    <div class="actions">
        <button onclick="window.print()" class="btn btn-secondary">Imprimir</button>
        <a href="index.php" class="btn btn-primary">Volver al Inicio</a>
    </div>
</div>

</body>
</html>
