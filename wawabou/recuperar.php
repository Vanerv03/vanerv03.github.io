<?php
include 'includes/db.php';
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);

    if (!$email) {
        $error = 'Por favor, ingresa un correo electrónico válido.';
    } else {
        // Verificar si el correo electrónico existe
        $sql = "SELECT * FROM usuarios WHERE email = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die('Error en la preparación de la consulta: ' . $conn->error);
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Generar un token único y configurar tiempo de expiración
            $token = bin2hex(random_bytes(50));
            $expire_time = date("Y-m-d H:i:s", strtotime("+1 hour"));

            // Almacenar el token en la base de datos
            $sql_token = "UPDATE usuarios SET reset_token = ?, reset_token_expiry = ? WHERE email = ?";
            $stmt_token = $conn->prepare($sql_token);

            if ($stmt_token === false) {
                die('Error en la preparación de la consulta de token: ' . $conn->error);
            }

            $stmt_token->bind_param("sss", $token, $expire_time, $email);

            if ($stmt_token->execute()) {
                // Enviar correo con el enlace para restablecer la contraseña
                $reset_link = "http://localhost/tu_proyecto/restablecer.php?token=" . urlencode($token);
                $subject = "Restablecer tu contraseña";
                $message = "Hola,\n\nHaz clic en el siguiente enlace para restablecer tu contraseña: \n\n" . $reset_link . "\n\nEste enlace expirará en 1 hora.";
                $headers = "From: no-reply@tusitio.com\r\n";
                $headers .= "Content-Type: text/plain; charset=utf-8";

                if (mail($email, $subject, $message, $headers)) {
                    $success = 'Se ha enviado un enlace para restablecer tu contraseña a tu correo electrónico.';
                } else {
                    $error = 'Hubo un error al enviar el correo. Intenta nuevamente.';
                }
            } else {
                $error = 'Hubo un problema al generar el token. Intenta nuevamente.';
            }
        } else {
            $error = 'No se encontró un usuario con ese correo electrónico.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña</title>
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
        <h2>Recuperar Contraseña</h2>

        <form method="POST" action="recuperar.php">
            <label for="email">Correo electrónico:</label>
            <input type="email" name="email" required placeholder="Ingrese su correo electrónico">
            
            <button type="submit">Enviar enlace de recuperación</button>
        </form>

        <p><a href="index.php">Volver al inicio</a></p>
    </div>
</body>
</html>


