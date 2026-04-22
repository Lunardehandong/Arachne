<?php
include("conexion.php");

$nombre_usuario = $_POST['usuario'];
$nueva_password = $_POST['nueva_password'];

if (empty($nombre_usuario) || empty($nueva_password)) {
    die("Faltan datos obligatorios.");
}

// Actualizamos la contraseña directamente con el texto plano
$update = $conn->prepare("UPDATE usuarios SET contrasena = ? WHERE nombre_usuario = ?");
$update->bind_param("ss", $nueva_password, $nombre_usuario);

if ($update->execute()) {
    // Redirigir al login tras éxito
    header("Location: ../index.html?update=success");
} else {
    echo "Error al actualizar la contraseña.";
}

$update->close();
$conn->close();
?>