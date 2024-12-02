<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Ventas</title>
</head>
<body>
    <h1>Generador de Reporte de Ventas</h1>

    <?php
    // Conexión a la base de datos
    $host = "localhost";
    $usuario = "anak";
    $password = "525486Ak";
    $basedatos = "coffy";

    $conexion = new mysqli($host, $usuario, $password, $basedatos);
    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }

    // Verificar si se han enviado las fechas
    if (isset($_POST['mes_inicio']) && isset($_POST['mes_fin'])) {
        $mesInicio = $_POST['mes_inicio'] . "-01"; // Añadimos el día 01 para el inicio del mes
        $mesFin = date("Y-m-t", strtotime($_POST['mes_fin'] . "-01")); // Ultimo día del mes de fin

        // Consulta para obtener el total de ventas en el rango de fechas
        $query = $conexion->prepare("SELECT SUM(monto) AS total_ventas FROM ventas WHERE fecha_ventas BETWEEN ? AND ?");
        $query->bind_param("ss", $mesInicio, $mesFin);
        $query->execute();
        $resultado = $query->get_result();
        $fila = $resultado->fetch_assoc();
        
        $totalVentas = $fila['total_ventas'] ?? 0;
        
        echo "<h2>Reporte de Ventas</h2>";
        echo "Total de ventas de " . $_POST['mes_inicio'] . " a " . $_POST['mes_fin'] . ": $" . number_format($totalVentas, 2);
    } else {
        // Formulario para seleccionar el rango de meses
        ?>
        <form method="post">
            <label for="mes_inicio">Mes de inicio (YYYY-MM):</label>
            <input type="month" name="mes_inicio" required>
            
            <label for="mes_fin">Mes de fin (YYYY-MM):</label>
            <input type="month" name="mes_fin" required>
            
            <input type="submit" value="Generar Reporte">
        </form>
        <?php
    }

    // Cerrar la conexión
    $conexion->close();
    ?>
</body>
</html>
