<?php
session_start();
$url_base="http://localhost/sitio-empleados";
include('./bdd.php');

if ($_POST) {
  $mensaje = '';
  $sentencia = $conexion->prepare("SELECT *,count(*) as n_usuario FROM usuario where usuario=:usuario");

  $usuario = $_POST["usuario"];
  $contrasenia = $_POST["contrasenia"];

  $sentencia->bindParam(":usuario", $usuario);
  // $sentencia->bindParam(":password", $contrasenia);
  $sentencia->execute();

  $registro = $sentencia->fetch(PDO::FETCH_LAZY);

    if ($registro["n_usuario"] > 0) {
      if($registro['usuario'] == $usuario && password_verify($contrasenia, $registro['password'])){
        $_SESSION['usuario'] = $registro["usuario"];
        $_SESSION['logueado'] = true;
        header("Location:index.php");
      } else {
        $mensaje = "<strong>Error</strong>: El usuario y/o contraseña son incorrectos";
      }
    } else {
      $mensaje = "Usuario no registrado";
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
  <!-- favicon -->
  <link rel="shortcut icon" href="./assets/img/favicon.png" type="image/png">
</head>
<body>
  <div class="container">
    <div class="row">
      <div class="col-md-4"></div>
      <div class="col-md-4">
        <br><br>
        <span class="img-container">
        <img src="./assets/img/Login-3.png"  height="150" alt="centered image" width="170">
        </span>
        <br>  
        <div class="card">
          <div class="card-header">
           <b>Ingresa tus datos</b>
          </div>
          <div class="card-body">
            <?php if (isset($mensaje)) { ?>

              <div class="alert alert-danger" role="start">
                <?php echo $mensaje; ?>
              </div>
            <?php } ?>
            <form method="post">
              <div class="form-group">
                <input autofocus type="text" class="form-control" name="usuario" placeholder="Usuario" pattern="[a-zA-Z0-9]{2,30}" maxlength="30" required>
              </div>
              <div class="form-group">
                <input type="password" class="form-control" name="contrasenia" placeholder="Contraseña" pattern="[a-zA-Z0-9$@.-_]{4,30}" maxlength="30" required>
              </div>
              <button type="submit" class="btn btn-success btn-block"><b>Iniciar Sesión</b></button>
            </form>
          </div>
            <div class="info-group">
              <p>¿No tienes una cuenta?
              <a href="<?php echo $url_base;?>/registro.php">Regístrate</a>.
            </div>
        </div>
        <br>
      </div>
    </div>
  </div>
</body>

</html>