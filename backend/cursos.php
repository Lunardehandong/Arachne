<?php
include("conexion.php"); 

// OJO: En tu imagen anterior de conexion.php la variable era $conexion, no $conn.
// Asegúrate de usar la que definiste allá.
$query = "SELECT id_curso, nombre_curso FROM cursos";
$resultado = mysqli_query($conn, $query); 

if (mysqli_num_rows($resultado) > 0) {
    while ($row = mysqli_fetch_assoc($resultado)) {
        // CAMBIO IMPORTANTE: El enlace debe ir al HTML, no al PHP de datos.
        echo '
        <a href="detalles_cursos.html?id=' . $row['id_curso'] . '" class="curso-item">
            <span class="icon-corazon">♡</span>
            <span class="abril-fatface">' . htmlspecialchars($row['nombre_curso']) . '</span>
        </a>';
    }
} else {
    echo 'No hay cursos disponibles aún.';
}

mysqli_close($conn);
?>