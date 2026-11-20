<?php

session_start();

include 'config/conexion.php';
require_once 'ia_helper.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

$usuario = $_SESSION['usuario'];
$rol_id = $usuario['rol_id'];

$total_actividades = $conn->query("SELECT * FROM actividades")->num_rows;
$total_evidencias = $conn->query("SELECT * FROM evidencias")->num_rows;
$total_medallas = $conn->query("SELECT * FROM medallas_obtenidas")->num_rows;

$semanas_json = '[]';
$totales_json = '[]';
if ($rol_id == 1) {
    $proyeccion = consultar_ia('proyeccion_semestre') ?? [];
    $semanas_json = json_encode(array_column($proyeccion, 'semana'));
    $totales_json = json_encode(array_column($proyeccion, 'total'));
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>

    <div class="sidebar">
        <h2>🏅 Medallas</h2>
        <a href="dashboard.php">🏠 Inicio</a>

        <?php if($rol_id == 1){ ?>
            <a href="admin/actividades.php">📋 Actividades</a>
            <a href="admin/revisar_evidencias.php">📤 Evidencias</a>
            <a href="admin/evidencias_aceptadas.php">✅ Aceptadas</a>
            <a href="admin/evidencias_rechazadas.php">🔁 Devueltas</a>
            <a href="admin/usuarios.php">👥 Usuarios</a>
        <?php } ?>

        <?php if($rol_id == 2){ ?>
            <a href="docente/actividades.php">📋 Actividades disponibles</a>
            <a href="admin/revisar_evidencias.php">📤 Evidencias</a>
            <a href="admin/usuarios.php">👥 Buscar Docentes</a>
        <?php } ?>

        <?php if($rol_id == 3){ ?>
            <a href="docente/actividades.php">📋 Actividades disponibles</a>
            <a href="docente/mis_evidencias.php">📤 Mis evidencias</a>
            <a href="docente/perfil.php">🏅 Mis medallas</a>
        <?php } ?>

        <a href="logout.php">🚪 Cerrar sesión</a>
    </div>

    <div class="contenido">
        <h1>
            <?php if($rol_id == 1){ ?>
                Bienvenido, Admin 👋
            <?php } else { ?>
                Bienvenido, <?php echo htmlspecialchars($usuario['nombre']); ?> 👋
            <?php } ?>
        </h1>

        <div class="cards">
            <?php if($rol_id == 1){ ?>
                <div class="card">
                    <h3>📋 Actividades</h3>
                    <h1><?php echo $total_actividades; ?></h1>
                    <p>Actividades registradas</p>
                </div>
                <div class="card">
                    <h3>📤 Evidencias</h3>
                    <h1><?php echo $total_evidencias; ?></h1>
                    <p>Evidencias enviadas</p>
                </div>
                <div class="card">
                    <h3>🏅 Medallas</h3>
                    <h1><?php echo $total_medallas; ?></h1>
                    <p>Medallas otorgadas</p>
                </div>
                <div class="card">
                    <h3>👥 Usuarios</h3>
                    <p>Registrar docentes y División</p>
                    <a class="btn" href="admin/usuarios.php">Ir</a>
                </div>
                <div class="card">
                    <h3>🗑️ Eliminar usuarios</h3>
                    <p>Eliminar docentes y División</p>
                    <a class="btn" href="admin/eliminar_usuarios.php">Ir</a>
                </div>
            <?php } ?>

            <?php if($rol_id == 2){ ?>
                <div class="card">
                    <h3>📋 Actividades disponibles</h3>
                    <p>Consultar actividades</p>
                    <a class="btn" href="docente/actividades.php">Ir</a>
                </div>
                <div class="card">
                    <h3>👥 Buscar Usuarios</h3>
                    <p>Consultar docentes y personal</p>
                    <a class="btn" href="admin/usuarios.php">Ir</a>
                </div>
            <?php } ?>

            <?php if($rol_id == 3){ ?>
                <div class="card">
                    <h3>📋 Actividades disponibles</h3>
                    <p>Ver actividades y registrar evidencias</p>
                    <a class="btn" href="docente/actividades.php">Ir</a>
                </div>
                <div class="card">
                    <h3>📤 Mis Evidencias</h3>
                    <p>Consultar estado de mis evidencias</p>
                    <a class="btn" href="docente/mis_evidencias.php">Ir</a>
                </div>
            <?php } ?>
        </div>

        <?php if($rol_id == 1){ ?>
            <div style="background:#fff; padding:25px; border-radius:15px; margin-top:25px; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
                <h3 style="color:#1b396a; margin-bottom:15px;">📈 Proyección Semestral de Evidencias (Regresión Polinómica)</h3>
                <canvas id="graficaPoly" height="85"></canvas>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
            new Chart(document.getElementById('graficaPoly'), {
                type: 'line',
                data: {
                    labels: <?php echo $semanas_json; ?>.map(s => 'Semana ' + s),
                    datasets: [{
                        label: 'Evidencias acumuladas proyectadas',
                        data: <?php echo $totales_json; ?>,
                        borderColor: '#1b396a',
                        backgroundColor: 'rgba(27, 57, 106, 0.12)',
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true
                    }]
                }
            });
            </script>
        <?php } ?>
    </div> 

</body>
</html>