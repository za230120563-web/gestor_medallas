<?php
session_start();

include 'config/conexion.php';

if($_POST){

    $correo = $_POST['correo'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM usuarios
    WHERE correo='$correo'";

    $resultado = $conn->query($sql);

    if($resultado->num_rows > 0){

        $usuario = $resultado->fetch_assoc();

        if(password_verify($password, $usuario['password'])){

            $_SESSION['usuario'] = $usuario;

            header("Location: dashboard.php");

        }else{

            $error = "Contraseña incorrecta";

        }

    }else{

        $error = "Usuario no encontrado";

    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>
Login
</title>

<link rel="stylesheet"
href="assets/css/styles.css">

</head>

<body>

<div class="login-container">

<div class="login-card">

<h2>
🏅 Gestor de Medallas
</h2>

<?php if(isset($error)){ ?>

<p style="color:red; margin-bottom:15px;">
<?php echo $error; ?>
</p>

<?php } ?>

<form method="POST">

<input type="email"
name="correo"
placeholder="Correo"
required>

<input type="password"
name="password"
placeholder="Contraseña"
required>

<button type="submit">

Iniciar sesión

</button>

</form>

</div>

</div>

</body>
</html>