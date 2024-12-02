<?php 
include 'config.php'; // Archivo de conexión

$totalVentas = 0;
$mensaje = '';

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mesInicio = $_POST['mesInicio'];
    $mesFin = $_POST['mesFin'];

    // Consulta para obtener el total de ventas en el rango seleccionado
    $query = "SELECT SUM(precio * vendidos) AS total_ventas FROM productos WHERE MONTH(fecha_venta) BETWEEN ? AND ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("ii", $mesInicio, $mesFin);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $totalVentas = $row['total_ventas'];
    }

    $stmt->close();
}

$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informe de Ventas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #b233d1;
        }
        .form-container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin: auto;
            width: 300px;
        }
        label {
            display: block;
            margin: 10px 0 5px;
        }
        select, input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .resultado {
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>
<body>

<h1>Reporte de Ventas</h1>

<div class="form-container">
    <form method="POST">
        <label for="mesInicio">Mes de Inicio:</label>
        <select id="mesInicio" name="mesInicio" required>
            <?php for ($mes = 1; $mes <= 12; $mes++): ?>
                <option value="<?php echo $mes; ?>"><?php echo date("F", mktime(0, 0, 0, $mes, 1)); ?></option>
            <?php endfor; ?>
        </select>

        <label for="mesFin">Mes de Fin:</label>
        <select id="mesFin" name="mesFin" required>
            <?php for ($mes = 1; $mes <= 12; $mes++): ?>
                <option value="<?php echo $mes; ?>"><?php echo date("F", mktime(0, 0, 0, $mes, 1)); ?></option>
            <?php endfor; ?>
        </select>

        <input type="submit" value="Generar Reporte">
    </form>
</div>

<?php if ($totalVentas > 0): ?>
    <div class="resultado">
        <h2>Total de Ventas:</h2>
        <p><?php echo '$' . number_format($totalVentas, 2); ?></p>
    </div>
<?php elseif ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
    <div class="resultado">
        <h2>No se encontraron ventas en este rango.</h2>
    </div>
<?php endif; ?>

</body>
</html>
