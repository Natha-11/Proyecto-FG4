<?php
include 'n8n_send_data.php';

echo "--- INICIANDO SIMULACIÓN DE RESERVA ---\n";

$datos_prueba = [
    'reserva_id' => 999,
    'nombre' => 'PRUEBA FINAL',
    'email' => 'nathaliacorniel838@gmail.com', // El email del usuario
    'telefono' => '8298949979',
    'servicio' => 'Servicio de Prueba',
    'fecha' => '2026-12-31',
    'hora' => '10:00',
    'codigo_confirmacion' => 'PRUEBA-TEST',
    'timestamp' => date('Y-m-d H:i:s')
];

$resultado = enviarAn8n('nueva_reserva', $datos_prueba);

echo "Resultado del envío:\n";
print_r($resultado);

if ($resultado['success']) {
    echo "\n✅ EL SITIO WEB ENVIÓ LOS DATOS CORRECTAMENTE.\n";
    echo "Si no recibes el correo, es porque falta configurar el nodo de Gmail en n8n.\n";
} else {
    echo "\n❌ ERROR EN EL ENVÍO: " . $resultado['message'] . "\n";
}
?>