<?php
include("conexion.php");
header('Content-Type: application/json');

$query = "SELECT t.id_tutorial, t.titulo, t.descripcion, u.nombre_usuario 
          FROM tutoriales t 
          JOIN usuarios u ON t.id_autor = u.id_usuario 
          ORDER BY t.fecha_publicacion DESC";

$result = mysqli_query($conn, $query);
$publicaciones = mysqli_fetch_all($result, MYSQLI_ASSOC);

echo json_encode($publicaciones);
?>