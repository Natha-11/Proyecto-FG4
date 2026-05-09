<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$cliente_id = $_SESSION['user_id'];

// Obtener datos del cliente
$stmt = $conexion->prepare("SELECT nombre, email, telefono FROM clientes WHERE id = ?");
$stmt->bind_param("i", $cliente_id);
$stmt->execute();
$user_data = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Obtener citas del cliente
$stmt_citas = $conexion->prepare("SELECT id, nro_factura, total, fecha, metodo_pago FROM facturas WHERE cliente_id = ? ORDER BY fecha DESC");
$stmt_citas->bind_param("i", $cliente_id);
$stmt_citas->execute();
$citas_result = $stmt_citas->get_result();
$citas = $citas_result->fetch_all(MYSQLI_ASSOC);
$stmt_citas->close();

$total_citas = count($citas);

$conexion->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil | BEAUTY MAKEUP</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,600;1,400&family=Montserrat:wght@200;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #0d0d0d;
            color: #f8f1eb;
        }
        .profile-container {
            max-width: 1000px;
            margin: 100px auto;
            padding: 2rem;
        }
        .profile-header {
            display: flex;
            align-items: center;
            gap: 2rem;
            margin-bottom: 4rem;
            padding-bottom: 2rem;
            border-bottom: 1px solid rgba(212,175,55,0.2);
        }
        .profile-avatar {
            width: 120px;
            height: 120px;
            background: var(--primary-color);
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 3rem;
            color: #000;
        }
        .profile-info h1 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 3rem;
            margin: 0;
            color: var(--primary-color);
        }
        .profile-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            margin-bottom: 4rem;
        }
        .stat-card {
            background: rgba(255,255,255,0.02);
            padding: 2rem;
            border-radius: 12px;
            border: 1px solid rgba(223, 207, 190, 0.1);
            text-align: center;
        }
        .stat-card h3 {
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #888;
            margin-bottom: 1rem;
        }
        .stat-card p {
            font-size: 2rem;
            font-family: 'Cormorant Garamond', serif;
            color: var(--primary-color);
        }
        .appointments-table {
            width: 100%;
            border-collapse: collapse;
            background: rgba(255,255,255,0.02);
            border-radius: 12px;
            overflow: hidden;
        }
        .appointments-table th, .appointments-table td {
            padding: 1.5rem;
            text-align: left;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        .appointments-table th {
            background: rgba(212,175,55,0.1);
            color: var(--primary-color);
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 1px;
        }
        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.75rem;
            text-transform: uppercase;
            background: rgba(212,175,55,0.2);
            color: var(--primary-color);
        }
        .view-btn {
            color: var(--primary-color);
            text-decoration: none;
            font-size: 0.9rem;
            transition: 0.3s;
        }
        .view-btn:hover {
            color: #fff;
        }
        .back-home {
            display: inline-block;
            margin-bottom: 2rem;
            color: var(--primary-color);
            text-decoration: none;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>

    <div class="profile-container">
        <a href="index.php" class="back-home"><i class="fas fa-arrow-left"></i> Volver al Inicio</a>
        
        <div class="profile-header">
            <div class="profile-avatar">
                <?php echo strtoupper(substr($user_data['nombre'], 0, 1)); ?>
            </div>
            <div class="profile-info">
                <h1>Hola, <?php echo htmlspecialchars(explode(' ', $user_data['nombre'])[0]); ?></h1>
                <p style="color: #888;"><?php echo htmlspecialchars($user_data['email']); ?> | <?php echo htmlspecialchars($user_data['telefono']); ?></p>
            </div>
        </div>

        <div class="profile-stats">
            <div class="stat-card">
                <h3>Citas Agendadas</h3>
                <p><?php echo $total_citas; ?></p>
            </div>
            <div class="stat-card">
                <h3>Próxima Cita</h3>
                <p>
                    <?php 
                    $proxima = null;
                    foreach($citas as $c) {
                        if(strtotime($c['fecha']) >= time()) {
                            $proxima = $c['fecha'];
                        }
                    }
                    echo $proxima ? date('d/m/Y', strtotime($proxima)) : 'Ninguna';
                    ?>
                </p>
            </div>
        </div>

        <h2 style="font-family: 'Cormorant Garamond', serif; font-size: 2rem; margin-bottom: 2rem;">Historial de Reservas</h2>
        <table class="appointments-table">
            <thead>
                <tr>
                    <th>Factura #</th>
                    <th>Fecha</th>
                    <th>Total</th>
                    <th>Método</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($citas)): ?>
                    <tr>
                        <td colspan="5" style="text-align: center; color: #666;">No tienes reservas registradas.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach($citas as $cita): ?>
                    <tr>
                        <td style="color: var(--primary-color);">#<?php echo htmlspecialchars($cita['nro_factura']); ?></td>
                        <td><?php echo date('d/m/Y g:i A', strtotime($cita['fecha'])); ?></td>
                        <td>$<?php echo number_format($cita['total'], 2); ?></td>
                        <td style="text-transform: capitalize;"><?php echo htmlspecialchars($cita['metodo_pago']); ?></td>
                        <td>
                            <a href="invoice.php?id=<?php echo $cita['id']; ?>" class="view-btn"><i class="fas fa-file-invoice"></i> Ver Factura</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
