<?php
include("conexion.php");
header('Content-Type: application/json');

// Recibimos los datos del formulario vía POST
$nombre = $_POST['nombre_prenda'];
$desc = $_POST['descripcion'];
$estado = $_POST['estado_prenda'];
$costo = intval($_POST['costo_creditos']);
$tipo = $_POST['tipo_servicio'];
$id_propietario = 1; // ID temporal hasta implementar login

$nombre_imagen = 'prenda_default.jpg'; 

// Procesar la subida a la carpeta específica: Arachne/backend/img_prendas/
if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
    $ruta_destino = "img_prendas/"; 
    
    if (!file_exists($ruta_destino)) {
        mkdir($ruta_destino, 0777, true);
    }

    $extension = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
    $nombre_limpio = preg_replace("/[^a-zA-Z0-9]/", "_", $nombre);
    $nombre_imagen = time() . "_" . $nombre_limpio . "." . $extension;
    
    $archivo_final = $ruta_destino . $nombre_imagen;

    if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $archivo_final)) {
        echo json_encode(['status' => 'error', 'message' => 'No se pudo mover la imagen a img_prendas']);
        exit;
    }
}

// Inserción en la base de datos
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