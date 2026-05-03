<?php
include("conexion.php");
session_start();
header('Content-Type: application/json');

// ACCIÓN 1: Obtener la lista de prendas disponibles (GET)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $query = "SELECT id_prenda, nombre_prenda, costo_creditos, url_imagen, tipo_servicio 
              FROM prendas 
              WHERE disponible = 1";
    $resultado = mysqli_query($conn, $query);
    
    $prendas = [];
    while($fila = mysqli_fetch_assoc($resultado)){
        $prendas[] = $fila;
    }
    echo json_encode($prendas);
    exit;
}

// ACCIÓN 2: Adquirir una prenda (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'adquirir') {
    $id_comprador = $_SESSION['id_usuario'];
    $id_prenda = intval($_POST['id_prenda']);

    // 1. Validar disponibilidad y obtener datos
    $res_prenda = mysqli_query($conn, "SELECT id_propietario, costo_creditos FROM prendas WHERE id_prenda = $id_prenda AND disponible = 1");
    $prenda = mysqli_fetch_assoc($res_prenda);

    if (!$prenda) {
        echo json_encode(['status' => 'error', 'message' => 'La prenda ya no está disponible']);
        exit;
    }

    $id_vendedor = $prenda['id_propietario'];
    $costo = $prenda['costo_creditos'];

    // 2. Verificar créditos del comprador
    $res_user = mysqli_query($conn, "SELECT creditos FROM usuarios WHERE id_usuario = $id_comprador");
    $user = mysqli_fetch_assoc($res_user);

    if ($user['creditos'] < $costo) {
        echo json_encode(['status' => 'error', 'message' => 'Créditos insuficientes']);
        exit;
    }

    // 3. Transacción Atómica
    mysqli_begin_transaction($conn);
    try {
        // Descontar al comprador
        mysqli_query($conn, "UPDATE usuarios SET creditos = creditos - $costo WHERE id_usuario = $id_comprador");
        // Pagar al vendedor
        mysqli_query($conn, "UPDATE usuarios SET creditos = creditos + $costo WHERE id_usuario = $id_vendedor");
        // Cambiar dueño y marcar como NO disponible (Stock 0)
        mysqli_query($conn, "UPDATE prendas SET id_propietario = $id_comprador, disponible = 0 WHERE id_prenda = $id_prenda");

        mysqli_commit($conn);
        echo json_encode(['status' => 'success']);
    } catch (Exception $e) {
        mysqli_rollback($conn);
        echo json_encode(['status' => 'error', 'message' => 'Error en el proceso de compra']);
    }
    exit;
}
?>