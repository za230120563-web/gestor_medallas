<?php

session_start();

include '../config/conexion.php';

// Verificar que venga el ID de la evidencia
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("No se recibió el ID de la evidencia.");
}

$id = intval($_GET['id']);

// --------------------------------------------------
// 1. Obtener información de la evidencia
// --------------------------------------------------

$consulta = "SELECT 
                e.id,
                e.usuario_id,
                e.actividad_id,
                e.estado,
                a.titulo,
                a.medalla_id
             FROM evidencias e
             INNER JOIN actividades a 
                ON e.actividad_id = a.id
             WHERE e.id = $id";

$resultado = $conn->query($consulta);

if (!$resultado || $resultado->num_rows == 0) {
    die("La evidencia no existe.");
}

$datos = $resultado->fetch_assoc();

$usuario_id = intval($datos['usuario_id']);
$actividad_id = intval($datos['actividad_id']);
$medalla_id = !empty($datos['medalla_id']) ? intval($datos['medalla_id']) : 0;

// --------------------------------------------------
// 2. Cambiar estado de la evidencia a Aprobado
// --------------------------------------------------

$actualizar = "UPDATE evidencias
               SET estado = 'Aprobado'
               WHERE id = $id";

if (!$conn->query($actualizar)) {
    die("Error al aprobar la evidencia: " . $conn->error);
}

// --------------------------------------------------
// 3. Verificar que la actividad tenga una medalla
// --------------------------------------------------

if ($medalla_id > 0) {

    // --------------------------------------------------
    // 4. Verificar si el docente ya tiene esa medalla
    // --------------------------------------------------

    $verificar = "SELECT id
                  FROM medallas_obtenidas
                  WHERE usuario_id = $usuario_id
                  AND medalla_id = $medalla_id";

    $resultado_verificar = $conn->query($verificar);

    if (!$resultado_verificar) {
        die("Error al verificar la medalla: " . $conn->error);
    }

    // --------------------------------------------------
    // 5. Si no la tiene, otorgar la medalla
    // --------------------------------------------------

    if ($resultado_verificar->num_rows == 0) {

        $guardar = "INSERT INTO medallas_obtenidas
                    (usuario_id, medalla_id)
                    VALUES
                    ($usuario_id, $medalla_id)";

        if (!$conn->query($guardar)) {
            die("Error al otorgar la medalla: " . $conn->error);
        }
    }
}

// --------------------------------------------------
// 6. Regresar a la página de revisión
// --------------------------------------------------

header("Location: revisar_evidencias.php");
exit();

?>