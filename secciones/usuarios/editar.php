<?php
include('../../bdd.php');

$usuarioError = $passwordError = $correoError = '';

if (isset($_GET['txtID'])) {

  $txtID = (isset($_GET['txtID'])) ? $_GET['txtID'] : "";

  $sentencia = $conexion->prepare("SELECT * FROM usuario WHERE id=:id");
  $sentencia->bindParam(":id", $txtID);
  $sentencia->execute();

  $registro = $sentencia->fetch(PDO::FETCH_LAZY);

  $usuario = $registro['usuario'];
  $password = $registro['password'];
  $correo = $registro['correo'];
}
if ($_POST) {

  // Recolectamos los datos del metodo POST
  $txtID = (isset($_POST["txtID"]) ? $_POST["txtID"] : "");
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

  if (empty($correoError) && empty($usuarioError) && empty($passwordError)) {

    $password = password_hash($password, PASSWORD_DEFAULT, array("cost" => 10));
    // Preparar la inserccion de los datos
    $sentencia = $conexion->prepare("UPDATE usuario SET usuario=:usuario,password=:password,correo=:correo WHERE id=:id");
    // Asigna valores que tiene uso de :variable
    $sentencia->bindParam(":usuario", $usuario);
    $sentencia->bindParam(":password", $password);
    $sentencia->bindParam(":correo", $correo);
    $sentencia->bindParam(":id", $txtID);
    $sentencia->execute();

    $mensaje = "Registro editado";
    header('Location:index.php?mensaje=' . $mensaje);
  }
}
$route = "../..";
include('../../templates/header.php');
?>
<br>
<div class="card">
  <div class="card-header">
    <label class="label-titulo"><b>Formulario de Modificación | Datos del Usuario</b></label>
  </div>
  <div class="card-body">
    <form action="" method="post" enctype="multipart/form-data">
      <div class="form-group">
        <label for="txtID"><b>ID</b></label>
        <input type="text" value="<?php echo $txtID ?>" class="form-control" name="txtID" id="txtID" aria-describedby="helpId" placeholder="ID" readonly>
      </div>

      <div class="form-group">
        <label for="usuario"><b>* Usuario</b></label>
        <input type="text" value="<?php echo $usuario ?>" class="form-control" name="usuario" id="usuario" aria-describedby="helpId" placeholder="Ingrese aquí el usuario" required pattern="[a-zA-Z]{2,15}" maxlength="30">
      </div>

      <div class="form-group">
        <label for="password"><b>* Contraseña</b></label>
        <input type="password" value="<?php echo $usuario ?>" value="<?php echo $password ?>" class="form-control" name="password" id="password" aria-describedby="helpId" placeholder="Ingrese aquí la contraseña" required>
      </div>

      <div class="form-group">
        <label for="correo"><b>* Correo Electrónico</b></label>
        <input type="email" value="<?php echo $correo ?>" class="form-control" name="correo" id="correo" aria-describedby="helpId" placeholder="Ingrese aquí el correo electrónico" required>
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