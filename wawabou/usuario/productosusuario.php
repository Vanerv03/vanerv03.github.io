<?php
include $_SERVER['DOCUMENT_ROOT'] . '/wawabou/includes/db.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Realizar la consulta de productos
$sql = "SELECT * FROM productos";
$result = $conn->query($sql);

// Verificar si la consulta fue exitosa
if (!$result) {
    echo "Error en la consulta: " . $conn->error;
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
    <link rel="stylesheet" href="styleusuario.css">

</head>
<body>

<?php
if ($result->num_rows > 0): ?>
    <div class="products-container">
        <?php while ($row = $result->fetch_assoc()): 
            $rutaImagen = "includes/imagenes/" . $row['imagen'];
            echo "<!-- Ruta de la imagen: $rutaImagen -->";  // Esto imprimirá la ruta en el HTML para ver si es correcta
        ?>
            <div class="product-card">
                <img src="<?php echo $rutaImagen; ?>" class="product-image" alt="<?php echo $row['nombre']; ?>">
                <div class="card-body">
                    <h5 class="product-title"><?php echo $row['nombre']; ?></h5>
                    <p class="product-description"><?php echo $row['descripcion']; ?></p>
                    <p class="product-price"><?php echo $row['precio']; ?> MXN</p>

                    <?php if (isset($row['bajo_pedido']) && $row['bajo_pedido'] == 1): ?>
                        <p>Este producto es bajo pedido.</p>
                    <?php else: ?>
                        <p>Este producto está en existencia.</p>
                    <?php endif; ?>

                    <form method="post" action="checkout.php">
                        <input type="hidden" name="producto_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" class="add-to-cart-btn">Agregar al carrito</button>
                    </form>

                    <a href="https://wa.me/+523921427735" target="_blank" class="whatsapp-btn">Contacta por WhatsApp</a>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
<?php else: ?>
    <p>No hay productos disponibles.</p>
<?php endif;

$conn->close();
?>

</body>
</html>
