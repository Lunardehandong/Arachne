<?php
include("conexion.php");
header('Content-Type: application/json');

// Datos recibidos del formulario
$nombre = $_POST['nombre_prenda'];
$desc = $_POST['descripcion'];
$estado = $_POST['estado_prenda'];
$id_propietario = 1; // Temporal hasta tener sesión

// Valores forzados para donación
$costo = 0;
$tipo = "Donación";
$nombre_imagen = 'donacion_default.jpg'; 

if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
    $ruta_destino = "img_prendas/"; 
    $extension = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
    $nombre_imagen = "donacion_" . time() . "." . $extension;
    
    if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_destino . $nombre_imagen)) {
        echo json_encode(['status' => 'error', 'message' => 'Error al guardar imagen']);
        exit;
    }
}

$stmt = $conn->prepare("INSERT INTO prendas (id_propietario, nombre_prenda, descripcion, estado_prenda, tipo_servicio, costo_creditos, url_imagen) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("issssis", $id_propietario, $nombre, $desc, $estado, $tipo, $costo, $nombre_imagen);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => $conn->error]);
}

$stmt->close();
$conn->close();
?>