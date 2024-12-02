//Genra otro php con el nombre mostrar o mostrar imagen, este codigo leera el binario que viene de la imagen de la base de datos, para ello ese campo debera contener el tipo BLOB el cual leera la imagen en binario.

<?php
// Conexión a la base de datos
include 'config.php';

// Obtener el ID del producto desde la URL
$id = $_POST['id'];

// Consulta para obtener la imagen del producto
$sql = "SELECT imagen FROM productos WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($imagen);
$stmt->fetch();
$stmt->close();

// Especificar el tipo de contenido (ajustar dependiendo del tipo de imagen almacenada, por ejemplo, 'image/jpeg' o 'image/png')
header("Content-Type: image/jpg, image/jpeg, image/png");

// Codificar la imagen en base64
echo base64_encode($imagen);

$conexion->close();
?>