<?php

session_start();

include '../config/conexion.php';
include '../includes/header.php';
require_once '../ia_helper.php';

$rol_actual = $_SESSION['usuario']['rol_id'];

if($rol_actual != 1 && $rol_actual != 2){
    header("Location: ../dashboard.php");
    exit();
}

$buscar = isset($_GET['buscar']) ? trim($_GET['buscar']) : '';

if(!empty($buscar)) {
    if($rol_actual == 2) {
        $sql = "SELECT * FROM usuarios WHERE (nombre LIKE ? OR correo LIKE ?) AND rol_id = 3";
    } else {
        $sql = "SELECT * FROM usuarios WHERE nombre LIKE ? OR correo LIKE ?";
    }
    $stmt = $conn->prepare($sql);
    $param = "%" . $buscar . "%";
    $stmt->bind_param("ss", $param, $param);
    $stmt->execute();
    $usuarios = $stmt->get_result();
} else {
    if($rol_actual == 2) {
        $sql = "SELECT * FROM usuarios WHERE rol_id = 3";
    } else {
        $sql = "SELECT * FROM usuarios";
    }
    $usuarios = $conn->query($sql);
}

?>

<h1>👥 Registrar usuario</h1>

<form action="guardar_usuario.php" method="POST">
    <label>Nombre</label>
    <input type="text" name="nombre" required>

    <label>Correo</label>
    <input type="email" name="correo" required>

    <label>Contraseña</label>
    <input type="password" name="password" required>

    <label>Rol</label>
    <select name="rol_id" required>
        <option value="">Selecciona rol</option>
        <?php if($rol_actual == 1){ ?>
        <option value="2">División</option>
        <option value="3">Docente</option>
        <?php } ?>
        <?php if($rol_actual == 2){ ?>
        <option value="3">Docente</option>
        <?php } ?>
    </select>
    <br><br>
    <button type="submit">Guardar usuario</button>
</form>

<hr style="margin:40px 0;">

<h1 id="tabla-usuarios">📋 Usuarios registrados</h1>

<form method="GET" action="#tabla-usuarios" style="margin-bottom: 20px;">
    <input type="text" name="buscar" placeholder="Buscar por nombre o correo..." value="<?php echo htmlspecialchars($buscar); ?>">
    <button type="submit">Buscar</button>
    <?php if(!empty($buscar)){ ?>
        <a href="usuarios.php"><button type="button">Limpiar</button></a>
    <?php } ?>
</form>

<table>
<tr>
    <th>ID</th>
    <th>Nombre</th>
    <th>Correo</th>
    <th>Rol</th>
    <th>Estado / Riesgo (IA)</th>
    <th>Acciones</th>
</tr>

<?php if($usuarios && $usuarios->num_rows > 0){ ?>
    <?php while($u = $usuarios->fetch_assoc()){ ?>
    <?php
    $etiqueta_riesgo = "No aplica";
    $color_badge = "#6c757d";

    if($u['rol_id'] == 3){
        $id_doc = (int)$u['id'];
        $q_stats = $conn->query("SELECT 
            SUM(CASE WHEN estado = 'Aceptado' THEN 1 ELSE 0 END) AS aceptadas,
            SUM(CASE WHEN estado = 'Rechazado' OR estado = 'Devuelto' THEN 1 ELSE 0 END) AS devueltas
            FROM evidencias WHERE usuario_id = $id_doc");
        
        $stats = $q_stats ? $q_stats->fetch_assoc() : ['aceptadas' => 0, 'devueltas' => 0];
        $aceptadas = (int)($stats['aceptadas'] ?? 0);
        $devueltas = (int)($stats['devueltas'] ?? 0);
        $dias_inactivo = ($aceptadas == 0 && $devueltas == 0) ? 45 : 5;

        $eval = consultar_ia('evaluar_docente', [
            'aceptadas' => $aceptadas,
            'devueltas' => $devueltas,
            'dias'      => $dias_inactivo
        ]);

        $etiqueta_riesgo = $eval['estado_clasificacion'] ?? 'Sin evaluar';

        if($etiqueta_riesgo === 'Cumplimiento Alto'){
            $color_badge = "#198754";
        } elseif($etiqueta_riesgo === 'En Riesgo'){
            $color_badge = "#d97706";
        } elseif($etiqueta_riesgo === 'Inactivo'){
            $color_badge = "#dc3545";
        }
    }
    ?>
    <tr>
        <td><?php echo $u['id']; ?></td>
        <td><?php echo $u['nombre']; ?></td>
        <td><?php echo $u['correo']; ?></td>
        <td>
        <?php
        if($u['rol_id'] == 1){ echo "RH"; }
        elseif($u['rol_id'] == 2){ echo "División"; }
        elseif($u['rol_id'] == 3){ echo "Docente"; }
        else{ echo "Sin rol"; }
        ?>
        </td>
        <td>
            <span style="background: <?php echo $color_badge; ?>; color: #fff; padding: 4px 10px; border-radius: 12px; font-size: 0.85em; font-weight: bold;">
                <?php echo htmlspecialchars($etiqueta_riesgo); ?>
            </span>
        </td>
        <td>
            <?php if($u['rol_id'] != 1 || $rol_actual == 1){ ?>
                <a href="eliminar_usuario.php?id=<?php echo $u['id']; ?>" 
                   onclick="return confirm('¿Estás seguro de que deseas eliminar a <?php echo $u['nombre']; ?>?');" 
                   style="color: #dc3545; font-weight: bold; text-decoration: none;">
                   🗑️ Eliminar
                </a>
            <?php } ?>
        </td>
    </tr>
    <?php } ?>
<?php } else { ?>
    <tr>
        <td colspan="6" style="text-align: center;">No se encontraron usuarios.</td>
    </tr>
<?php } ?>
</table>

<?php include '../includes/footer.php'; ?>