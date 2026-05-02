<?php
include("conexion.php");
session_start();

// Si el HTML solicita los datos, respondemos con JSON
if (isset($_GET['obtener_datos_usuario'])) {
    $id_usuario = $_SESSION['id_usuario'];

    $query = mysqli_query($conn, "SELECT nombre_usuario, creditos, foto_perfil FROM usuarios WHERE id_usuario = '$id_usuario'");
    $datos = mysqli_fetch_assoc($query);

    // Buscamos sus cursos comprados para enviarlos también
    $cursos_query = mysqli_query($conn, "
        SELECT c.nombre_curso 
        FROM cursos c 
        INNER JOIN inscripciones_cursos i ON c.id_curso = i.id_curso 
        WHERE i.id_usuario = '$id_usuario'
    ");
    
    $cursos = [];
    while($row = mysqli_fetch_assoc($cursos_query)){
        $cursos[] = $row['nombre_curso'];
    }

    // Enviamos todo en un solo paquete
    echo json_encode([
        "usuario" => $datos['nombre_usuario'],
        "creditos" => $datos['creditos'],
        "foto" => $datos['foto_perfil'],
        "cursos" => $cursos
    ]);
    exit(); // Detenemos aquí para que no imprima nada más
}

// Lógica para guardar la imagen en backend/img/
if (isset($_FILES['foto'])) {
    $id_usuario = $_SESSION['id_usuario'];
    $nombre_foto = $_FILES['foto']['name'];
    $ruta_temporal = $_FILES['foto']['tmp_name'];
    
    // Definimos la ruta absoluta hacia la carpeta img dentro de backend
    $directorio_destino = __DIR__ . "/img/";
    $nombre_final = time() . "_" . $nombre_foto; // Evita nombres duplicados
    $ruta_completa = $directorio_destino . $nombre_final;

    if (move_uploaded_file($ruta_temporal, $ruta_completa)) {
        // Guardamos la ruta relativa para que sea fácil de leer después
        $ruta_db = "backend/img/" . $nombre_final;
        mysqli_query($conn, "UPDATE usuarios SET foto_perfil = '$ruta_db' WHERE id_usuario = '$id_usuario'");
        header("Location: ../perfil.html?update=success");
    } else {
        header("Location: ../perfil.html?error=upload_failed");
    }
}
?>