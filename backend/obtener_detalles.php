<?php
include("conexion.php"); 
header('Content-Type: application/json');

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    // Consulta limpia basada en tu SQL
    $sql = "SELECT nombre_curso, descripcion, contenido_detallado, url_imagen, creditos 
            FROM cursos 
            WHERE id_curso = $id";
    
    $res = mysqli_query($conn, $sql);

    if ($res && mysqli_num_rows($res) > 0) {
        echo json_encode(mysqli_fetch_assoc($res));
    } else {
        echo json_encode(["nombre_curso" => "Curso no encontrado"]);
    }
}
mysqli_close($conn);
?>