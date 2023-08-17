<?php
session_start();
$url_base = "http://localhost/sitio-empleados";
include('./bdd.php');

$mensaje = '';
$usuarioError = $correoError = $passwordError = '';

if ($_POST) {
  $usuario = (isset($_POST["usuario"]) ? $_POST["usuario"] : "");
  $password = (isset($_POST["password"]) ? $_POST["password"] : "");
  $correo = (isset($_POST["correo"]) ? $_POST["correo"] : "");
  
  if(empty($usuario)){
    $usuarioError = "Por favor, ingresa tu usuario.";
  } else if (!preg_match("/^[a-zA-Z]{2,15}$/", $_POST["usuario"])) {
    $usuarioError = 'Por favor, ingresa un nombre de usuario válido.';
  }

  if(empty($password)){
    $passwordError = "Por favor, ingresa tu contraseña.";
  } else if (!preg_match("/^[a-zA-Z0-9$@.-_]{8,}$/", $_POST["password"])) {
    $passwordError = 'La contraseña debe tener al menos 8 caracteres, incluyendo al menos una mayúscula, una minúscula y un número.';
  }

  if(empty($correo)){
    $correoError = "Por favor, ingresa tu correo electrónico.";
  }else if(!preg_match("/^[a-zA-Z0-9.-_]+@[a-zA-Z0-9.-]+\.[a-z]{2,4}$/", $_POST["correo"])) {
    $correoError = 'Por favor, ingresa un correo electrónico válido.';
  }

  if (empty($usuarioError) && empty($correoError) && empty($passwordError)) {
    
    $sentencia = $conexion->prepare("SELECT *,count(*) as n_usuario FROM usuario where usuario=:usuario or correo=:correo");

    $sentencia->bindParam(":usuario", $usuario);
    $sentencia->bindParam(":correo", $correo);
    $sentencia->execute();

    $registro = $sentencia->fetch(PDO::FETCH_LAZY);
    if ($registro["n_usuario"] > 0) {
      if ($registro['usuario'] == $usuario) {
        $mensaje = "<strong>Error</strong>: El nombre de usuario ya existe";
      } else if ($registro['correo'] == $correo) {
        $mensaje = "<strong>Error</strong>: La dirección de correo ya existe";
      }
    } else {
      $password = password_hash($password, PASSWORD_DEFAULT, array("cost"=>10));
      $sentencia = $conexion->prepare("INSERT INTO usuario(id,usuario,password,correo) values(NULL,:usuario,:password,:correo)");
      $sentencia->bindParam(":usuario", $usuario);
      $sentencia->bindParam(":password",$password);
      $sentencia->bindParam(":correo", $correo);
      $sentencia->execute();
      header("Location:login.php");
    }
  }
}
?>

<!doctype html>
<html lang="es">

<head>
<title>Sitio Empleados</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <!-- Style CSS -->
  <link rel="stylesheet" href="./style.css">
  <link rel="shortcut icon" href="./assets/img/favicon.png" type="image/x-icon">
</head>

<body>
  <div class="container">
    <div class="row">
      <div class="col-md-4"></div>
      <div class="col-md-4">
        <br><br>
        <span class="img-container">
          <img src="./assets/img/Login.png" height="125" alt="centered image" width="200">
        </span>
        <br>
        <div class="card">
          <div class="card-header">
            <b>Registra tus datos</b>
          </div>
          <div class="card-body">
            <?php if ($mensaje !== '') { ?>
              <div class="alert alert-danger" role="start">
                <?php echo $mensaje; ?>
              </div>
            <?php } ?>
            <form method="post" enctype="multipart/form-data">
              <div class="form-group">
                <input autofocus type="text" name="usuario" id="usuario" class="form-control" placeholder="Usuario" pattern="[a-zA-Z0-9]{2,30}" maxlength="30" required>
                <span class="error"><?php echo $usuarioError; ?></span>
              </div>
              <div class="form-group">
                <input type="email" name="correo" id="correo" class="form-control" placeholder="Correo Electrónico" pattern="[a-zA-Z0-9.-_]+@[a-zA-Z0-9.-]+\.[a-z]+" required>
                <span class="error"><?php echo $correoError ?></span>
              </div>
              <div class="form-group">
                <input type="password" name="password" id="password" class="form-control" placeholder="Contraseña" pattern="[a-zA-Z0-9$@.-_]{4,30}" maxlength="30" required>
                <span class="error"><?php echo $passwordError ?></span>
              </div>
              <button type="submit" class="btn btn-info btn-block"><b>Registrarse</b></button>
            </form>
          </div>
          <div class="info-group">
            <p>¿Ya tienes una cuenta?
              <a href="<?php echo $url_base; ?>/login.php">Iniciar Sesión</a>.
            </p>
          </div>
        </div>
      </div>
    </div>
    <br>
</body>
</html>