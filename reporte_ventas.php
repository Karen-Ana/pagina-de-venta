<?php 
session_start();

// Conexión a la base de datos
$host = "localhost";
$user = "anak";
$password = "525486Ak";
$database = "coffy";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Verificar si se enviaron las fechas para el rango del reporte
if (isset($_POST['mes_inicio']) && isset($_POST['mes_fin'])) {
    $mesInicio = $_POST['mes_inicio'] . "-01"; // Primer día del mes de inicio
    $mesFin = date("Y-m-t", strtotime($_POST['mes_fin'] . "-01")); // Último día del mes de fin

    // Consulta para obtener el reporte de ventas dentro del rango de fechas seleccionado
    $sql = "SELECT 
                p.nombre AS producto, 
                COUNT(c.id) AS cantidad_vendida, 
                SUM(c.cantidad * p.precio) AS ingresos_totales,
                DATE(c.fecha) AS fecha_venta
            FROM 
                ventas c
            JOIN 
                productos p ON c.producto_id = p.id
            WHERE 
                c.fecha BETWEEN '$mesInicio' AND '$mesFin'
            GROUP BY 
                p.nombre, DATE(c.fecha)
            ORDER BY 
                fecha_venta DESC, cantidad_vendida DESC";

    $result = $conn->query($sql);

    // Consulta para obtener los totales generales en el rango de fechas seleccionado
    $sqlTotales = "SELECT 
                        SUM(c.cantidad) AS total_cantidad,
                        SUM(c.cantidad * p.precio) AS total_ingresos
                    FROM 
                        ventas c
                    JOIN 
                        productos p ON c.producto_id = p.id
                    WHERE 
                        c.fecha BETWEEN '$mesInicio' AND '$mesFin'";

    $resultTotales = $conn->query($sqlTotales);
    $totales = $resultTotales->fetch_assoc();
} 

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Ventas - MyStoreComicxManga</title>
    <link rel="stylesheet" href="./../assets/css/reporte_ventas_styles.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Reporte de Ventas</h1>
        </header>

        <!-- Formulario para seleccionar el rango de fechas -->
        <form method="post">
            <label for="mes_inicio">Mes de inicio (YYYY-MM):</label>
            <input type="month" name="mes_inicio" required>
            
            <label for="mes_fin">Mes de fin (YYYY-MM):</label>
            <input type="month" name="mes_fin" required>
            
            <input type="submit" value="Generar Reporte">
        </form>

        <!-- Mostrar el reporte solo si hay datos de ventas -->
        <?php if (isset($result) && $result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad Vendida</th>
                        <th>Ingresos Totales</th>
                        <th>Fecha de Venta</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['producto']) ?></td>
                            <td><?= htmlspecialchars($row['cantidad_vendida']) ?></td>
                            <td>$<?= number_format($row['ingresos_totales'], 2) ?></td>
                            <td><?= htmlspecialchars($row['fecha_venta']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <!-- Mostrar totales generales -->
            <div class="totales-generales">
                <h3>Totales Generales</h3>
                <p>Total de productos vendidos: <?= number_format($totales['total_cantidad'], 0) ?></p>
                <p>Ingresos totales: $<?= number_format($totales['total_ingresos'], 2) ?></p>
            </div>
        <?php elseif (isset($result)): ?>
            <p>No hay datos de ventas disponibles para el período seleccionado.</p>
        <?php endif; ?>

        <button onclick="window.location.href='admin.php'">Regresar</button>
    </div>
</body>
</html>
