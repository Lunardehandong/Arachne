<?php
include("conexion.php");
header('Content-Type: application/json');

if (isset($_GET['id'])) {
    $id_prenda = intval($_GET['id']);

    // Consultamos los detalles y unimos con la tabla usuarios para saber quién la subió
    $query = "SELECT p.*, u.nombre_usuario 
              FROM prendas p 
              LEFT JOIN usuarios u ON p.id_propietario = u.id_usuario 
              WHERE p.id_prenda = $id_prenda";

    $resultado = mysqli_query($conn, $query);

    if ($resultado && mysqli_num_rows($resultado) > 0) {
        $prenda = mysqli_fetch_assoc($resultado);
        echo json_encode($prenda);
    } else {
        echo json_encode(["error" => "Prenda no encontrada"]);
    }
} else {
    echo json_encode(["error" => "ID no proporcionado"]);
}

mysqli_close($conn);
?>