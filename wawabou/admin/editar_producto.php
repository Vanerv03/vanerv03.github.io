<?php
include 'includes/db.php'; // Conexión a la base de datos

// Verificar si el ID del producto fue enviado
if (isset($_GET['producto_id'])) {
    $producto_id = $_GET['producto_id'];

    // Consultar el producto por ID
    $sql = "SELECT * FROM productos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $producto_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $producto = $result->fetch_assoc();
    } else {
        echo "Producto no encontrado.";
        exit;
    }
} else {
    echo "ID de producto no proporcionado.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener datos del formulario
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $imagen = $_POST['imagen']; // Aquí deberías implementar la carga de la nueva imagen, si la deseas cambiar
    $bajo_pedido = isset($_POST['bajo_pedido']) ? 1 : 0;

    // Actualizar el producto en la base de datos
    $update_sql = "UPDATE productos SET nombre = ?, descripcion = ?, precio = ?, imagen = ?, bajo_pedido = ? WHERE id = ?";
    $stmt_update = $conn->prepare($update_sql);
    $stmt_update->bind_param("ssdsii", $nombre, $descripcion, $precio, $imagen, $bajo_pedido, $producto_id);

    if ($stmt_update->execute()) {
        echo "Producto actualizado correctamente.";
    } else {
        echo "Error al actualizar el producto.";
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Editar Producto</h2>

<form method="POST" action="editar_producto.php?producto_id=<?php echo $producto['id']; ?>" enctype="multipart/form-data">
    <label for="nombre">Nombre:</label>
    <input type="text" name="nombre" id="nombre" value="<?php echo $producto['nombre']; ?>" required><br>

    <label for="descripcion">Descripción:</label>
    <textarea name="descripcion" id="descripcion" required><?php echo $producto['descripcion']; ?></textarea><br>

    <label for="precio">Precio (MXN):</label>
    <input type="number" step="0.01" name="precio" id="precio" value="<?php echo $producto['precio']; ?>" required><br>

    <label for="imagen">Imagen (URL o subir nueva imagen):</label>
    <input type="text" name="imagen" id="imagen" value="<?php echo $producto['imagen']; ?>"><br>
    <!-- Si deseas que se suba una nueva imagen, puedes agregar un input tipo file -->

    <label for="bajo_pedido">¿Producto bajo pedido?</label>
    <input type="checkbox" name="bajo_pedido" id="bajo_pedido" <?php echo $producto['bajo_pedido'] == 1 ? 'checked' : ''; ?>><br>

    <button type="submit">Actualizar Producto</button>
</form>

</body>
</html>
