<?php
include("conexion.php");
session_start();

if (isset($_GET['obtener_datos_usuario'])) {
    $id_usuario = $_SESSION['id_usuario'];

    // 1. Datos básicos
    $query = mysqli_query($conn, "SELECT nombre_usuario, creditos, foto_perfil FROM usuarios WHERE id_usuario = '$id_usuario'");
    $datos = mysqli_fetch_assoc($query);

    // 2. Cursos comprados
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

    // 3. Prendas obtenidas (Donaciones recibidas, compras o intercambios)
    // Asumimos que tienes una tabla de transacciones o que el id_propietario cambia al adquirirla
    $prendas_query = mysqli_query($conn, "
        SELECT nombre_prenda, tipo_servicio 
        FROM prendas 
        WHERE id_propietario = '$id_usuario'
    ");

    $prendas = [];
    while($row = mysqli_fetch_assoc($prendas_query)){
        $prendas[] = [
            "nombre" => $row['nombre_prenda'],
            "tipo" => $row['tipo_servicio']
        ];
    }

    echo json_encode([
        "usuario" => $datos['nombre_usuario'],
        "creditos" => $datos['creditos'],
        "foto" => $datos['foto_perfil'],
        "cursos" => $cursos,
        "prendas" => $prendas
    ]);
    exit();
}

// Lógica de guardado de imagen (se mantiene igual)
if (isset($_FILES['foto'])) {
    $id_usuario = $_SESSION['id_usuario'];
    $nombre_foto = $_FILES['foto']['name'];
    $ruta_temporal = $_FILES['foto']['tmp_name'];
    $directorio_destino = __DIR__ . "/img/";
    $nombre_final = time() . "_" . $nombre_foto;
    $ruta_completa = $directorio_destino . $nombre_final;

    if (move_uploaded_file($ruta_temporal, $ruta_completa)) {
        $ruta_db = "backend/img/" . $nombre_final;
        mysqli_query($conn, "UPDATE usuarios SET foto_perfil = '$ruta_db' WHERE id_usuario = '$id_usuario'");
        header("Location: ../perfil.html?update=success");
    } else {
        header("Location: ../perfil.html?error=upload_failed");
    }
}
?>