
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Principal</title>
    <link rel="stylesheet" href="styles.css">
</head>
<style>
        body {
            margin: 0;
            padding: 0;
            background-image: url('./includes/imagen1.jpg'); /* Ajusta la ruta según la ubicación de tu imagen */
            background-size: cover; /* Ajusta la imagen para cubrir toda la pantalla */
            background-position: center; /* Centra la imagen */
            background-attachment: fixed; /* Hace que el fondo no se desplace al hacer scroll */
        }

        .container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.9); /* Fondo blanco semitransparente */
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        input[type="email"],
        input[type="password"],
        button {
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        button {
            background-color: #ff69b4; /* Rosa */
            color: white;
            cursor: pointer;
            border: none;
        }

        button:hover {
            background-color: #ff1493; /* Rosa más oscuro */
        }

        a {
            text-align: center;
            display: block;
            margin-top: 20px;
            color: #ff69b4; /* Rosa */
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
<body>
    <div class="container">
        <h1>Bienvenido a Wawabou</h1>
        <p>Inicia secion o registra tu cuenta para continuar:</p>
        
        <div class="menu">
            <a href="login.php">Iniciar sesión</a>
            <a href="registro.php">Registrar cuenta</a>
        </div>
    </div>
</body>
</html>

