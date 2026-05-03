<?php
session_start();
include("conexion.php");

if (isset($_POST['usuario']) && isset($_POST['password'])) {
    $user_input = $_POST['usuario'];
    $pass_input = $_POST['password'];

    // Buscamos al usuario
    $stmt = $conn->prepare("SELECT id_usuario, contrasena, cuenta_activa FROM usuarios WHERE nombre_usuario = ?");
    $stmt->bind_param("s", $user_input);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $fila = $result->fetch_assoc();
        
        // COMPARACIÓN DIRECTA (Sin hash)
        if ($pass_input == $fila['contrasena']) {
            if ($fila['cuenta_activa'] == 1) {
                $_SESSION['id_usuario'] = $fila['id_usuario'];
                $_SESSION['nombre_usuario'] = $user_input;
                
                header("Location: ../principal.html");
                exit();
            } else {
                echo "Cuenta desactivada.";
            }
        } else {
            echo "Contraseña incorrecta.";
        }
    } else {
        echo "Usuario no encontrado.";
    }
    $stmt->close();
}
$conn->close();
?>