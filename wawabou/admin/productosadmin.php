<?php
include 'includes/db.php'; // Conexión a la base de datos
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
    <!-- Vinculación del archivo CSS -->
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php
// Consultar productos desde la base de datos
$sql = "SELECT * FROM productos";
$result = $conn->query($sql);

if ($result->num_rows > 0): ?>
    <div class="products-container"> <!-- Cambié 'row' a 'products-container' para usar las clases de CSS -->
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="product-card"> <!-- Cambié 'col-md-4' a 'product-card' -->
                <img src="<?php echo $row['imagen']; ?>" class="product-image" alt="<?php echo $row['nombre']; ?>">
                <div class="card-body">
                    <h5 class="product-title"><?php echo $row['nombre']; ?></h5>
                    <p class="product-description"><?php echo $row['descripcion']; ?></p>
                    <p class="product-price"><?php echo $row['precio']; ?> MXN</p>
                    <form method="post" action="checkout.php">
                        <input type="hidden" name="producto_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" class="add-to-cart-btn">Agregar al carrito</button>
                    </form>
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