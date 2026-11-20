<?php

session_start();

include '../config/conexion.php';


// ==========================================
// 1. VERIFICAR SESIÓN
// ==========================================

if (!isset($_SESSION['usuario'])) {
    die('No tienes permiso para realizar esta acción.');
}


// ==========================================
// 2. VERIFICAR QUE SEA RH
// ==========================================

if ($_SESSION['usuario']['rol_id'] != 1) {
    die('No tienes permisos para eliminar actividades.');
}


// ==========================================
// 3. VERIFICAR ID DE LA ACTIVIDAD
// ==========================================

if (!isset($_POST['id'])) {
    die('No se especificó la actividad.');
}

$id = intval($_POST['id']);


// ==========================================
// 4. BUSCAR LAS EVIDENCIAS DE LA ACTIVIDAD
// ==========================================

$sql = "SELECT id, archivo
        FROM evidencias
        WHERE actividad_id = ?";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die('Error al buscar las evidencias: ' . $conn->error);
}

$stmt->bind_param("i", $id);
$stmt->execute();

$resultado = $stmt->get_result();


// ==========================================
// 5. CREAR CARPETA DE RESPALDO
// ==========================================

$carpeta_destino = dirname(__DIR__) . "/evidencias_eliminadas/actividad_" . $id;


// Si no existe, crearla
if (!is_dir($carpeta_destino)) {

    if (!mkdir($carpeta_destino, 0777, true)) {
        die('No se pudo crear la carpeta de respaldo.');
    }

}


// ==========================================
// 6. MOVER LOS ARCHIVOS
// ==========================================

while ($evidencia = $resultado->fetch_assoc()) {

    $archivo = $evidencia['archivo'];

    // Ubicación actual del archivo
    $origen = dirname(__DIR__) . "/uploads/evidencias/" . $archivo;

    // Nueva ubicación
    $destino = $carpeta_destino . "/" . $archivo;


    // Comprobar que el archivo exista
    if (file_exists($origen)) {

        // Mover archivo
        if (!rename($origen, $destino)) {

            die("No se pudo mover el archivo: " . $archivo);

        }

    }

}


// ==========================================
// 7. ELIMINAR LAS EVIDENCIAS DE LA BASE DE DATOS
// ==========================================

$sql_delete_evidencias =
    "DELETE FROM evidencias WHERE actividad_id = ?";

$stmt_delete = $conn->prepare($sql_delete_evidencias);

if (!$stmt_delete) {
    die('Error al preparar la eliminación de evidencias: ' . $conn->error);
}

$stmt_delete->bind_param("i", $id);

if (!$stmt_delete->execute()) {
    die('No se pudieron eliminar las evidencias: ' . $stmt_delete->error);
}


// ==========================================
// 8. ELIMINAR LA ACTIVIDAD
// ==========================================

$sql_delete_actividad =
    "DELETE FROM actividades WHERE id = ?";

$stmt_actividad = $conn->prepare($sql_delete_actividad);

if (!$stmt_actividad) {
    die('Error al preparar la eliminación: ' . $conn->error);
}

$stmt_actividad->bind_param("i", $id);

if ($stmt_actividad->execute()) {

    // Regresar a actividades
    header("Location: actividades.php");
    exit;

} else {

    die('No se pudo eliminar la actividad: ' . $stmt_actividad->error);

}

?>