<?php
include 'c:/xampp/htdocs/Proyecto_final6t0/conexion.php';
$res = $conexion->query("SELECT * FROM servicios WHERE activo = 1 LIMIT 8"); // Limit to 8 to keep it clean
$output = "";
while($s = $res->fetch_assoc()) {
    $img = htmlspecialchars($s['imagen']);
    $name = htmlspecialchars($s['nombre']);
    $price = number_format($s['precio'], 0);
    $service_data = htmlspecialchars(strtolower(str_replace(' ', '-', $s['nombre'])));

    $output .= <<<HTML
            <!-- Tarjeta: $name -->
            <div class="product-card reveal">
                <img src="$img" alt="$name" class="product-img">
                <h3 style="text-transform: uppercase;">$name</h3>
                <p class="price">$$price</p>
                <?php if (isset(\$_SESSION['user_id'])): ?>
                    <button class="cta-button reserve-btn" data-service="$service_data"
                        style="display:inline-block; margin-top:20px; padding: 10px 25px; width: 100%;">Reservar</button>
                <?php else: ?>
                    <a href="login.php" class="cta-button"
                        style="display:inline-block; margin-top:20px; padding: 10px 25px; width: 100%; text-align:center;">Reservar</a>
                <?php endif; ?>
            </div>

HTML;
}
file_put_contents('c:/xampp/htdocs/Proyecto_final6t0/static_cards.txt', $output);
?>
