<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tienda_ropa";

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar si hubo un error en la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

 echo "Conexión exitosa a la base de datos.";
?>

