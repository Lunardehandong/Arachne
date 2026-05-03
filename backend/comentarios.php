<?php
session_start();
include("conexion.php");
header('Content-Type: application/json');

// SI ES POST: Guardamos un comentario nuevo
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['id_usuario'])) {
        echo json_encode(['status' => 'error', 'message' => 'Inicia sesión']);
        exit;
    }

    $id_user = $_SESSION['id_usuario'];
    $id_tuto = $_POST['id_tutorial'];
    $texto = $_POST['comentario'];

    $stmt = $conn->prepare("INSERT INTO foro_comentarios (id_usuario, id_tutorial, comentario) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $id_user, $id_tuto, $texto);
    
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    }
    exit;
}

// SI ES GET: Mostramos los comentarios (lo que ya hacía antes)
$id_tutorial = isset($_GET['id']) ? intval($_GET['id']) : 0;
$query = "SELECT c.comentario, u.nombre_usuario FROM foro_comentarios c 
          JOIN usuarios u ON c.id_usuario = u.id_usuario 
          WHERE c.id_tutorial = $id_tutorial ORDER BY c.fecha ASC";

$result = mysqli_query($conn, $query);
echo json_encode(mysqli_fetch_all($result, MYSQLI_ASSOC));
?>