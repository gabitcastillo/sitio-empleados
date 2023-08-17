<?php
include('../../bdd.php');

$nombreError = $apellidoError  = '';
$fotoError = $cvError = '';
$idpuestoError = $fechadeingresoError = '';

$extension_per = array("image/jpeg", "image/png", "image/gif", "application/pdf", "application/xml");
$limite_tam = 200;

$fecha_ = new DateTime();

$sentencia = $conexion->prepare("SELECT * FROM puestos");
$sentencia->execute();
$lista_puestos = $sentencia->fetchAll(PDO::FETCH_ASSOC);

if ($_POST) {
  //print_r($_POST);
  //print_r($_FILES);

  $nombre = (isset($_POST["nombre"]) ? $_POST["nombre"] : "");
  $apellido = (isset($_POST["apellido"]) ? $_POST["apellido"] : "");

  $foto = (isset($_FILES["foto"]['name']) ? $_FILES["foto"]['name'] : "");
  $cv = (isset($_FILES["cv"]['name']) ? $_FILES["cv"]['name'] : "");

  $idpuesto = (isset($_POST["idpuesto"]) ? $_POST["idpuesto"] : "");
  $fechadeingreso = (isset($_POST["fechadeingreso"]) ? $_POST["fechadeingreso"] : "");

  if (empty($nombre)) {
    $nombreError = "Por favor, ingresa tu nombre.";
  } else if (!preg_match("/^[a-zA-Z\s]{2,30}$/", $_POST["nombre"])) {
    $nombreError = "Por favor, ingrese un nombre valido.";
  }

  if (empty($apellido)) {
    $apellidoError = "Por favor, ingresa tu apellido.";
  } else if (!preg_match("/^[a-zA-Z\s]{2,30}$/", $_POST["apellido"])) {
    $apellidoError = "Por favor, ingrese un apellido valido.";
  }

  if (empty($idpuesto)) {
    $idpuestoError = "Por favor, ingresa un puesto.";
  }

  if (empty($fechadeingreso)) {
    $fechadeingresoError = "Por favor, ingresa tu fecha de ingreso.";
  } else if (!preg_match("/^[0-9\/-]{10}$/", $_POST["fechadeingreso"])) {
    $fechadeingresoError = "Por favor, ingrese una fecha de ingreso valido.";
  }
  if (!empty($foto)) {
    if (in_array($_FILES['foto']['type'], $extension_per) && $_FILES['foto']['size'] <= $limite_tam * 1024) {
      // Se obtiene el nombre del archivo
      $nombre_archivo_foto = ($foto != '') ? $fecha_->getTimestamp() . "_" . $_FILES["foto"]['name'] : "";
      // Se obtiene el archivo
      $tmp_foto = $_FILES["foto"]['tmp_name'];

      $ruta = "files/fotos/";
      $archivo_foto = $ruta . $nombre_archivo_foto;

      if (!file_exists($ruta)) {
        @mkdir($ruta, 0777, true);
        @move_uploaded_file($tmp_foto, $archivo_foto);
      } else {
        @move_uploaded_file($tmp_foto, $archivo_foto);
      }
    } else {
      $fotoError = "Archivo no permitido o excede el tamaño";
    }
  } else {
    $fotoError = "Por favor, ingrese una foto";
  }

  if (!empty($cv)) {
    if (in_array($_FILES['cv']['type'], $extension_per) && $_FILES['cv']['size'] <= $limite_tam * 1024) {
      // Se obtiene el nombre del archivo
      $nombre_archivo_cv = ($cv != '') ? $fecha_->getTimestamp() . "_" . $_FILES["cv"]['name'] : "";
      // Se obtiene el archivo
      $tmp_cv = $_FILES["cv"]['tmp_name'];

      $ruta = "files/cv/";
      $archivo_cv = $ruta . $nombre_archivo_cv;

      if (!file_exists($ruta)) {
        @mkdir($ruta, 0777, true);
        @move_uploaded_file($tmp_cv, $archivo_cv);
      } else {
        @move_uploaded_file($tmp_cv, $archivo_cv);
      }
    } else {
      $cvError = "Archivo no permitido o excede el tamaño";
    }
  } else {
    $cvError = "Por favor, ingrese un documento";
  }

  if (empty($nombreError) && empty($apellidoError) && empty($fotoError) && empty($cvError) && empty($idpuestoError) && empty($fechadeingresoError) && empty($idpuestoError) && empty($fechadeingresoError) && empty($cvError) && empty($fotoError)) {
    $sentencia = $conexion->prepare("INSERT INTO empleados(id,nombre,apellido,foto,cv,idpuesto,fechadeingreso) VALUES(NULL,:nombre,:apellido,:foto,:cv,:idpuesto,:fechadeingreso);");
    $sentencia->bindParam(":nombre", $nombre);
    $sentencia->bindParam(":apellido", $apellido);
    $sentencia->bindParam(":foto", $nombre_archivo_foto);
    $sentencia->bindParam(":cv", $nombre_archivo_cv);
    $sentencia->bindParam(":idpuesto", $idpuesto);
    $sentencia->bindParam(":fechadeingreso", $fechadeingreso);
    $sentencia->execute();

    $mensaje = "Registro agregado";
    header('Location:index.php?mensaje=' . $mensaje);
  }
}

$route = "../..";
include('../../templates/header.php');
?>

<br>
<div class="card">
  <div class="card-header">
    <label class="label-titulo"><b>Formulario de Alta | Datos del Empleado</b></label>
  </div>
  <div class="card-body">
    <form action="" method="post" enctype="multipart/form-data">

      <div class="form-group">
        <label for="nombre"><b>* Nombre</b></label>
        <input type="text" class="form-control" name="nombre" id="nombre" aria-describedby="helpId" placeholder="Ingrese aquí el Nombre" pattern="[a-zA-Z\s]{2,30}" maxlength="30" required>
        <span class="error"><?php echo $nombreError; ?></span>
      </div>

      <div class="form-group">
        <label for="apellido"><b>* Apellido</b></label>
        <input type="text" class="form-control" name="apellido" id="apellido" aria-describedby="helpId" placeholder="Ingrese aquí el Apellido" pattern="[a-zA-Z\s]{2,30}" maxlength="30" required>
        <span class="error"><?php echo $apellidoError; ?></span>
      </div>

      <div class="form-group">
        <label for="foto"><b>Foto</b></label>
        <input type="file" class="form-control" name="foto" id="foto" aria-describedby="helpId" required accept="image/jpeg,image/gif,image/png">
        <span class="error"><?php echo $fotoError; ?></span>
      </div>

      <div class="form-group">
        <label for="cv"><b>Curriculum o hoja de vida (.pdf /.docx /.xml)</b></label>
        <input type="file" class="form-control" name="cv" id="cv" aria-describedby="helpId" required accept="application/pdf,application/docx,application/xml">
        <span class="error"><?php echo $cvError; ?></span>
      </div>

      <div class="mb-3">
        <label for="idpuesto"><b>Puesto</b></label>

        <select class="form-select form-select-lg" name="idpuesto" id="idpuesto" required>
          <?php foreach ($lista_puestos as $registro) { ?>
            <option value="<?php echo $registro['id'] ?>;">
              <?php echo $registro['nombredelpuesto'] ?>
            </option>
          <?php } ?>
        </select>
        <span class="error"><?php echo $idpuestoError; ?></span>
      </div>

      <div class="form-group">
        <label for="fechadeingreso"><b>Fecha de Ingreso</b></label>
        <input type="date" class="form-control" name="fechadeingreso" id="fechadeingreso" aria-describedby="emailHelpId" placeholder="" required>
        <span class="error"><?php echo $fechadeingresoError; ?></span>
      </div>
      <p>(*)<i> Campos obligatorios</i></p>
      <div class="botons-group">
        <button type="submit" class="btn btn-success"><b>Confirmar<b></b></button>
        <a id="btn-volver" class="btn btn-warning" href="index.php" role="button"><b>Volver</b></a>
      </div>
    </form>
  </div>
</div>
<br>
<?php include('../../templates/footer.php'); ?>