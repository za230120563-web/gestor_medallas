<?php

session_start();

include '../config/conexion.php';

if (!isset($_SESSION['usuario']) || ($_SESSION['usuario']['rol_id'] != 1 && $_SESSION['usuario']['rol_id'] != 2)) {
    http_response_code(403);
    exit();
}

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    if ($id != $_SESSION['usuario']['id']) {
        $conn->query("DELETE FROM evidencias WHERE usuario_id = $id");
        $conn->query("DELETE FROM medallas_obtenidas WHERE usuario_id = $id");
        $conn->query("DELETE FROM usuarios WHERE id = $id");
    }
}

if (isset($_GET['ajax'])) {
    header('Content-Type: application/json');
    echo json_encode(['ok' => true]);
    exit();
}

header("Location: usuarios.php#tabla-usuarios");
exit();

?>