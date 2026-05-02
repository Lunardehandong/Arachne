<?php
session_start(); // Fundamental para leer el ID del usuario que hizo login
include("conexion.php"); // Usamos tu archivo de conexión

header('Content-Type: application/json');

// 1. Verificamos que el usuario realmente haya pasado por el login
if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['status' => 'error', 'message' => 'Sesión no activa. Por favor, inicia sesión.']);
    exit();
}

$id_usuario = $_SESSION['id_usuario']; // El ID dinámico del usuario actual
$id_curso = isset($_POST['id_curso']) ? intval($_POST['id_curso']) : 0;

if ($id_curso > 0) {
    
    // 2. Revisamos si ya lo agregó antes (usando tu variable $conn)
    $stmt = $conn->prepare("SELECT id_inscripcion FROM inscripciones_cursos WHERE id_usuario = ? AND id_curso = ?");
    $stmt->bind_param("ii", $id_usuario, $id_curso);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Este curso ya está en tu perfil.']);
    } else {
        // 3. Insertamos la relación (sin costo de créditos)
        $stmt_ins = $conn->prepare("INSERT INTO inscripciones_cursos (id_usuario, id_curso) VALUES (?, ?)");
        $stmt_ins->bind_param("ii", $id_usuario, $id_curso);
        
        if ($stmt_ins->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No se pudo agregar el curso.']);
        }
        $stmt_ins->close();
    }
    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'ID de curso no válido.']);
}

$conn->close();
?>