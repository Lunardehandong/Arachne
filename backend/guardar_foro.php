<?php
session_start();
include("conexion.php");
header('Content-Type: application/json');

if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['status' => 'error', 'message' => 'No hay sesión']);
    exit;
}

$id_autor = $_SESSION['id_usuario'];
$titulo = $_POST['titulo'];
$desc = $_POST['descripcion'];

$stmt = $conn->prepare("INSERT INTO tutoriales (id_autor, titulo, descripcion) VALUES (?, ?, ?)");
$stmt->bind_param("iss", $id_autor, $titulo, $desc);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error']);
}
?>