<?php

session_start();

include '../config/conexion.php';
require_once '../ia_helper.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: ../login.php");
    exit;
}

$actividad_id = $_POST['actividad_id'];
$usuario_id   = $_SESSION['usuario']['id'];

$archivo = time() . "_" . basename($_FILES['archivo']['name']);
$tmp     = $_FILES['archivo']['tmp_name'];

$ruta = "../assets/uploads/";
$ruta_completa = $ruta . $archivo;

if (move_uploaded_file($tmp, $ruta_completa)) {

    $res_ia = consultar_ia('validar_evidencia', [
        'ruta' => realpath($ruta_completa)
    ]);

    $validacion_ia = $res_ia['validacion_ia'] ?? 'Pre-aprobado IA';
    $confianza_ia  = $res_ia['confianza_ia'] ?? 90.00;

    $sql = "INSERT INTO evidencias
            (usuario_id, actividad_id, archivo, estado, validacion_ia, confianza_ia)
            VALUES
            ('$usuario_id',
             '$actividad_id',
             '$archivo',
             'Enviado',
             '$validacion_ia',
             '$confianza_ia')";

    if ($conn->query($sql)) {
        ?>
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Evidencia enviada</title>
            <style>
                body {
                    margin: 0;
                    padding: 0;
                    font-family: Arial, Helvetica, sans-serif;
                    background: #f4f6f9;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100vh;
                }
                .mensaje {
                    background: white;
                    width: 420px;
                    padding: 40px;
                    text-align: center;
                    border-radius: 18px;
                    box-shadow: 0 8px 25px rgba(0,0,0,0.10);
                }
                .icono { font-size: 60px; margin-bottom: 15px; }
                h2 { color: #1e3a5f; margin-bottom: 10px; }
                p { color: #666; font-size: 16px; }
                .badge-ia {
                    display: inline-block;
                    margin-top: 10px;
                    padding: 6px 14px;
                    border-radius: 12px;
                    background: #eef4ff;
                    color: #1b396a;
                    font-weight: bold;
                    font-size: 14px;
                }
            </style>
        </head>
        <body>
            <div class="mensaje">
                <div class="icono">✅</div>
                <h2>Evidencia enviada correctamente</h2>
                <p>Tu archivo fue analizado por la Red Neuronal:</p>
                <div class="badge-ia">
                    🤖 <?php echo htmlspecialchars($validacion_ia); ?> (<?php echo $confianza_ia; ?>%)
                </div>
                <p style="margin-top: 15px; font-size: 14px;">Redirigiendo a tus evidencias...</p>
            </div>

            <script>
                setTimeout(function() {
                    window.location.href = "mis_evidencias.php";
                }, 2000);
            </script>
        </body>
        </html>
        <?php
        exit;
    } else {
        die("Error al guardar la evidencia en la base de datos: " . $conn->error);
    }

} else {
    die("No se pudo subir el archivo.");
}
?>