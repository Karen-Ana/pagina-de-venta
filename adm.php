<?php
include "./../pages/config.php"; // Asegúrate de tener la conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $contrasena = $_POST['contrasena'];

    // Validar campos
    if (!empty($nombre) && !empty($correo) && !empty($contrasena)) {
        // Verificar si el correo ya existe
        $query = "SELECT * FROM admii WHERE correo = ?";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param('s', $correo);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            // Insertar nuevo usuario
            $hashed_password = password_hash($contrasena, PASSWORD_DEFAULT);
            $insert_query = "INSERT INTO admii (nombre, correo, contrasena) VALUES (?, ?, ?)";
            $insert_stmt = $conexion->prepare($insert_query);
            $insert_stmt->bind_param('sss', $nombre, $correo, $hashed_password);
            $insert_stmt->execute();

            if ($insert_stmt->affected_rows > 0) {
                echo "<p>Registro exitoso. Puedes iniciar sesión.</p>";
            } else {
                echo "<p>Error al registrar. Inténtalo de nuevo.</p>";
            }
        } else {
            echo "<p>El correo ya está en uso.</p>";
        }

        $stmt->close();
    } else {
        echo "<p>Por favor, completa todos los campos.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href="styles.css"> <!-- Enlaza tu CSS -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 300px;
            margin: 50px auto;
            padding: 20px;
            background: white;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }

        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        input[type="submit"] {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }

        input[type="submit"]:hover {
            background-color: #218838;
        }

        .message {
            text-align: center;
            color: #ff0000;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Registro</h1>
        <form action="" method="post">
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" required>

            <label for="correo">Correo:</label>
            <input type="email" name="correo" required>

            <label for="contrasena">Contraseña:</label>
            <input type="password" name="contrasena" required>

            <input type="submit" value="Registrar">
        </form>
        <div class="message">
            <?php if (isset($mensaje)) echo $mensaje; ?>
        </div>
    </div>
</body>
</html>
