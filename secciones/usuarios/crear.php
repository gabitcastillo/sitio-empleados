<?php
include('../../bdd.php');

$mensaje = '';
$usuarioError = $correoError = $passwordError = '';

if ($_POST) {

  $usuario = (isset($_POST["usuario"]) ? $_POST["usuario"] : "");
  $password = (isset($_POST["password"]) ? $_POST["password"] : "");
  $correo = (isset($_POST["correo"]) ? $_POST["correo"] : "");

  if (empty($usuario)) {
    $usuarioError = "Por favor, ingresa tu usuario.";
  } else if (!preg_match("/^[a-zA-Z]{2,15}$/", $_POST["usuario"])) {
    $usuarioError = 'Por favor, ingresa un nombre de usuario válido.';
  }

  if (empty($password)) {
    $passwordError = "Por favor, ingresa tu contraseña.";
  } else if (!preg_match("/^[a-zA-Z0-9$@.-_]{8,}$/", $_POST["password"])) {
    $passwordError = 'La contraseña debe tener al menos 8 caracteres, incluyendo al menos una mayúscula, una minúscula y un número.';
  }

  if (empty($correo)) {
    $correoError = "Por favor, ingresa tu correo electrónico.";
  } else if (!preg_match("/^[a-zA-Z0-9.-_]+@[a-zA-Z0-9.-]+\.[a-z]{2,4}$/", $_POST["correo"])) {
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
      $password = password_hash($password, PASSWORD_DEFAULT, array("cost" => 10));
      $sentencia = $conexion->prepare("INSERT INTO usuario(id,usuario,password,correo) values(NULL,:usuario,:password,:correo)");
      $sentencia->bindParam(":usuario", $usuario);
      $sentencia->bindParam(":password", $password);
      $sentencia->bindParam(":correo", $correo);
      $sentencia->execute();

      $mensaje = "Registro agregado";
      header('Location:index.php?mensaje=' . $mensaje);
    }
  }
}
$route = "../..";
include('../../templates/header.php');
?>

<br>
<div class="card">
  <div class="card-header">
    <label class="label-titulo"><b>Formulario de Alta | Datos del Usuario</b></label>
  </div>
  <div class="card-body">
    <?php if ($mensaje !== '') { ?>
      <div class="alert alert-danger" role="start">
        <?php echo $mensaje; ?>
      </div>
    <?php } ?>
    <form action="" method="post" enctype="multipart/form-data">
      <div class="form-group">
        <label for="usuario"><b>* Usuario</b></label>
        <input type="text" class="form-control" name="usuario" id="usuario" aria-describedby="helpId" placeholder="Ingrese aquí el usuario" autofocus required pattern="[a-zA-Z0-9]{2,30}" maxlength="30">
        <span class="error"><?php echo $usuarioError; ?></span>
      </div>

      <div class="form-group">
        <label for="password"><b>* Contraseña</b></label>
        <input type="password" class="form-control" name="password" id="password" aria-describedby="helpId" placeholder="Ingrese aquí la contraseña" required pattern="[a-zA-Z0-9$@.-_]{4,30}" maxlength="30">
        <span class="error"><?php echo $passwordError; ?></span>
      </div>

      <div class="form-group">
        <label for="correo"><b>* Correo Electrónico</b></label>
        <input type="email" class="form-control" name="correo" id="correo" aria-describedby="helpId" placeholder="Ingrese aquí el correo electrónico" pattern="[a-zA-Z0-9.-_]+@[a-zA-Z0-9.-]+\.[a-z]+" required>
        <span class="error"><?php echo $correoError; ?></span>
      </div>
      <p>(*)<i> Campos obligatorios</i></p>
      <div class="botons-group">
        <button type="submit" class="btn btn-success"><b>Confirmar</b></button>
        <a name="volver" id="btn-volver" class="btn btn-warning" href="index.php" role="button"><b>Volver</b><a>
      </div>
    </form>
  </div>
</div>

<?php include('../../templates/footer.php'); ?>