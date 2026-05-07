<?php
include 'conexion.php';
$res = $conexion->query("SELECT * FROM servicios WHERE activo = 1");
while ($s = $res->fetch_assoc()) {
    $price = number_format($s['precio'], 0, ',', '.');
    echo "<!-- SERVICIO: {$s['nombre']} -->\n";
    echo "<div class=\"product-card reveal\">\n";
    echo "    <img src=\"uploads/{$s['imagen']}\" alt=\"{$s['nombre']}\" class=\"product-img\">\n";
    echo "    <h3>{$s['nombre']}</h3>\n";
    echo "    <p class=\"price\">\${$price}</p>\n";
    echo "    <?php if (isset(\$_SESSION['admin_id'])): ?>\n";
    echo "    <div class=\"admin-card-controls-static\">\n";
    echo "        <a href=\"admin_dashboard.php?edit_service={$s['id']}\" class=\"admin-btn-mini\">Editar</a>\n";
    echo "    </div>\n";
    echo "    <?php endif; ?>\n";
    echo "    <button class=\"cta-button reserve-btn\" data-service=\"".strtolower(str_replace(' ', '-', $s['nombre']))."\">Reservar</button>\n";
    echo "</div>\n";
    echo "\n";
}
?>
