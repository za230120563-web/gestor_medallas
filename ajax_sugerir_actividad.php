<?php
require_once 'ia_helper.php';
$input = json_decode(file_get_contents('php://input'), true);
header('Content-Type: application/json');
echo json_encode(consultar_ia('sugerir_actividad', $input));
?> 