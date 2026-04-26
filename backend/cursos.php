<?php
include("conexion.php"); 
session_start();

// Lógica para procesar la compra de un curso
if (isset($_POST['comprar'])) {
    $id_usuario = $_SESSION['id_usuario'];
    $id_curso = $_POST['id_curso'];
    $costo = $_POST['costo'];

    // Verificar créditos
    $query = mysqli_query($conn, "SELECT creditos FROM usuarios WHERE id_usuario = '$id_usuario'");
    $user = mysqli_fetch_assoc($query);

    if ($user['creditos'] >= $costo) {
        // Descontar y registrar
        mysqli_query($conn, "UPDATE usuarios SET creditos = creditos - $costo WHERE id_usuario = '$id_usuario'");
        mysqli_query($conn, "INSERT INTO inscripciones_cursos (id_usuario, id_curso) VALUES ('$id_usuario', '$id_curso')");
        header("Location: ../principal.html?compra=exitosa");
    } else {
        header("Location: ../principal.html?error=insuficiente");
    }
}
?>