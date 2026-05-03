<?php
include("conexion.php");
header('Content-Type: application/json');

// Recibimos los datos del formulario
$nombre = $_POST['nombre_curso'];
$desc = $_POST['descripcion'];
$cont = $_POST['contenido_detallado'];
$cred = intval($_POST['creditos']);
$nivel = $_POST['nivel'];

// Por ahora usaremos una imagen por defecto, puedes ampliarlo luego para subir archivos
$img_default = 'curso_default.jpg'; 

$stmt = $conn->prepare("INSERT INTO cursos (nombre_curso, descripcion, contenido_detallado, creditos, nivel, url_imagen) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssisss", $nombre, $desc, $cont, $cred, $nivel, $img_default);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => $conn->error]);
}

$stmt->close();
$conn->close();
?>