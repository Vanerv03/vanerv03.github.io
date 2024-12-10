<?php
include 'includes/db.php';

$error = ''; // Variable for error messages
$success = ''; // Variable for success messages

// Check if a token is passed in the URL
if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Validate the token and check if it has expired
    $sql = "SELECT * FROM usuarios WHERE reset_token = ? AND reset_token_expiry > NOW()";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die('Error in preparing the query: ' . $conn->error);
    }

    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    // If token is valid
    if ($result->num_rows > 0) {
        // Process password reset form submission
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];

            // Validate passwords
            if (empty($password) || empty($confirm_password)) {
                $error = 'Please enter both passwords.';
            } elseif ($password !== $confirm_password) {
                $error = 'Passwords do not match.';
            } else {
                // Hash the new password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Update the password and clear the token in the database
                $sql_update = "UPDATE usuarios SET password = ?, reset_token = NULL, reset_token_expiry = NULL WHERE reset_token = ?";
                $stmt_update = $conn->prepare($sql_update);

                if ($stmt_update === false) {
                    die('Error in preparing the update query: ' . $conn->error);
                }

                $stmt_update->bind_param("ss", $hashed_password, $token);

                // Execute the update
                if ($stmt_update->execute()) {
                    $success = 'Your password has been successfully reset. You can now log in with your new password.';
                } else {
                    $error = 'There was an error resetting your password. Please try again.';
                }
            }
        }
    } else {
        $error = 'The reset link has expired or is invalid.';
    }
} else {
    $error = 'No valid token provided.';
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña</title>
</head>
<body>
    <h2>Restablecer Contraseña</h2>

    <?php if ($error): ?>
        <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <?php if ($success): ?>
        <p style="color:green;"><?php echo htmlspecialchars($success); ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <input type="password" name="password" placeholder="Nueva Contraseña" required>
        <input type="password" name="confirm_password" placeholder="Confirmar Contraseña" required>
        <button type="submit">Restablecer Contraseña</button>
    </form>
</body>
</html>
