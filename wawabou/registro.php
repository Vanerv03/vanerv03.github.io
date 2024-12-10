<?php
include 'includes/db.php';

$error = ''; // Variable para almacenar errores
$success = ''; // Variable para almacenar el mensaje de éxito

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validación de los campos
    if (empty($nombre) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = 'Todos los campos son obligatorios.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Por favor, ingresa un correo electrónico válido.';
    } elseif ($password !== $confirm_password) {
        $error = 'Las contraseñas no coinciden.';
    } else {
        // Verificar si ya existe un administrador
        $sql_check_admin = "SELECT COUNT(*) AS count FROM usuarios WHERE rol = 'admin'";
        $stmt_check_admin = $conn->prepare($sql_check_admin);
        $stmt_check_admin->execute();
        $result_check_admin = $stmt_check_admin->get_result();
        $row_check_admin = $result_check_admin->fetch_assoc();

        // Si ya existe un administrador y el nuevo usuario es admin, bloquear el registro
        if ($row_check_admin['count'] >= 1 && isset($_POST['rol']) && $_POST['rol'] == 'admin') {
            $error = 'Ya existe un administrador en el sistema. No se puede crear otro.';
        } else {
            // Verificar si el correo electrónico o el nombre de usuario ya están en uso
            $sql = "SELECT * FROM usuarios WHERE nombre = ? OR email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $nombre, $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $error = 'El nombre de usuario o el correo electrónico ya están en uso.';
            } else {
                // Encriptar la contraseña
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Asignar rol por defecto "usuario" si no se especifica
                $rol = 'usuario'; // Aseguramos que el rol es usuario por defecto
                if (isset($_POST['rol']) && $_POST['rol'] == 'admin') {
                    $rol = 'admin'; // Solo se asigna admin si no hay otro administrador
                }

                // Insertar el nuevo usuario en la base de datos
                $sql_insert_user = "INSERT INTO usuarios (nombre, email, password, rol) VALUES (?, ?, ?, ?)";
                $stmt_insert_user = $conn->prepare($sql_insert_user);
                $stmt_insert_user->bind_param("ssss", $nombre, $email, $hashed_password, $rol);

                if ($stmt_insert_user->execute()) {
                    $success = 'Registro exitoso. Ahora puedes iniciar sesión.';
                } else {
                    $error = 'Hubo un error al registrar al usuario. Intenta nuevamente.';
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar cuenta</title>
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
        input[type="nombre"],
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
        <h2>Registrar cuenta</h2>

        <form method="POST" action="registro.php">
            <label for="nombre">Nombre:</label>
            <input type="nombre" name="nombre" required placeholder="Ingrese su nombre">
            
            <label for="email">Correo electrónico:</label>
            <input type="email" name="email" required placeholder="Ingrese su correo electrónico">
            
            <label for="password">Contraseña:</label>
            <input type="password" name="password" required placeholder="Ingrese su contraseña">

            <label for="confirm_password">Confirmar contraseña:</label>
            <input type="password" name="confirm_password" required placeholder="Confirme su contraseña">
            
            <button type="submit">Registrarse</button>
        </form>

        <p><a href="index.php">Volver al inicio</a></p>
    </div>
</body>
</html>

