<?php
include("conexion.php");

$nombre_completo = $_POST['nombre_completo'];
$nombre_usuario = $_POST['nombre_usuario'];
$correo = $_POST['correo'];
$password = $_POST['password']; // Se toma directamente del POST
$biografia = "¡Hola! Soy nuevo en Arachne."; 

if (empty($nombre_completo) || empty($nombre_usuario) || empty($correo) || empty($password)) {
    die("Todos los campos son obligatorios.");
}

// ELIMINAMOS la línea de $passwordHash = password_hash(...)

// Insertar usando la variable $password directamente
$stmt = $conn->prepare("INSERT INTO usuarios (nombre_completo, nombre_usuario, correo, contrasena, biografia) VALUES (?, ?, ?, ?, ?)");

// Aquí pasamos $password en lugar de $passwordHash
$stmt->bind_param("sssss", $nombre_completo, $nombre_usuario, $correo, $password, $biografia);

if ($stmt->execute()) {
    header("Location: ../index.html?registro=exitoso");
} else {
    echo "Error: El usuario o correo ya están registrados.";
}

$stmt->close();
$conn->close();
?>