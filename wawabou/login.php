<?php
include 'includes/db.php';

// Inicializar sesión
session_start();

// Variables para mensajes
$error = '';
$success = '';

// Limitar intentos de inicio de sesión
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
}

// Verificar si se ha excedido el límite de intentos
if ($_SESSION['login_attempts'] >= 5) {
    $error = "Has excedido el número de intentos. Intenta nuevamente más tarde.";
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validar campos
    if (empty($email) || empty($password)) {
        $error = 'Por favor, completa todos los campos.';
    } else {
        // Preparar la consulta para obtener los datos del usuario
        $sql = "SELECT * FROM usuarios WHERE email = ?";
        $stmt = $conn->prepare($sql);

        // Comprobar errores en la preparación
        if ($stmt === false) {
            die('Error en la preparación de la consulta: ' . $conn->error);
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        // Verificar si el usuario existe
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Verificar la contraseña
            if (password_verify($password, $user['password'])) {
                // Restablecer intentos fallidos
                $_SESSION['login_attempts'] = 0;

                // Almacenar datos en la sesión
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['nombre'];
                $_SESSION['user_role'] = $user['rol'];

                // Redirigir según el rol
                if ($user['rol'] === 'admin') {
                    header("Location: admin/productosadmin.php");
                } else {
                    header("Location: usuario/iniciou.php");
                }
                exit;
            } else {
                $error = 'Correo o contraseña incorrectos.';
                $_SESSION['login_attempts']++;
            }
        } else {
            $error = 'Correo o contraseña incorrectos.';
            $_SESSION['login_attempts']++;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
    <link rel="stylesheet" href="styles.css">
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
</head>
<body>
    <div class="container">
        <h2>Iniciar sesión</h2>
        
        <?php if (!empty($error)): ?>
            <p style="color: red; text-align: center;"><?php echo $error; ?></p>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <label for="email">Correo electrónico:</label>
            <input type="email" name="email" required placeholder="Ingrese su correo electrónico">
            
            <label for="password">Contraseña:</label>
            <input type="password" name="password" required placeholder="Ingrese su contraseña">
            
            <button type="submit">Iniciar sesión</button>
        </form>

        <p><a href="recuperar.php">¿Olvidaste tu contraseña?</a></p>
        <p><a href="index.php">Volver al inicio</a></p>
    </div>
</body>
</html>
