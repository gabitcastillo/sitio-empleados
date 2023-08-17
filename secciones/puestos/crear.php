<?php 
include('../../bdd.php');

$nombreError = '';
if($_POST){
    //print_r($_POST);

    // Recolectamos los datos del metodo post
    $nombredelpuesto= (isset($_POST["nombredelpuesto"])?$_POST["nombredelpuesto"]:"");

    if(empty($nombredelpuesto)){
        $nombreError = "Por favor, ingresa un nombre.";
    } else if(!preg_match("/^[a-zA-Z0-9\s]{2,30}$/", $_POST["nombredelpuesto"])){
        $nombreError = "Por favor, ingrese un nombre de puesto valido.";
    }

    if(empty($nombreError)){
     // Preparar la inserccion de los datos
    $sentencia=$conexion->prepare("INSERT INTO `puestos`(id,nombredelpuesto) values (NULL,:nombredelpuesto)");
    // Asignando los valores que vienen del metodo POST (Los que vienen del formulario)
    $sentencia->bindParam(":nombredelpuesto", $nombredelpuesto);
    $sentencia->execute();
    
    $mensaje= "Registro agregado";
    header('Location:index.php?mensaje='.$mensaje);
    }  
}
$route = "../..";
include('../../templates/header.php');
?>

<br>
 <div class="card">
    <div class="card-header">
        <label class="label-titulo"><b>Formulario de Alta | Datos del Puesto</b></label>
    </div>
    <div class="card-body">
        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-group">
              <label for="nombredelpuesto"><b>* Nombre Puesto</b></label>
              <input type="text"
                class="form-control" name="nombredelpuesto" id="nombredelpuesto" aria-describedby="helpId" placeholder="Ingrese aquí el Nombre del puesto" required pattern="[a-zA-Z0-9\s]{2,30}" maxlength="30">
               <span class="error"><? echo $nombreError; ?></span>
            </div>
            <p>(*)<i> Campos obligatorios</i></p>
            <div class="botons-group">
            <button type="submit" class="btn btn-success"><b>Confirmar</b></button>
            <a name="volver" id="btn-volver" class="btn btn-warning" href="index.php" role="button"><b>Volver</b></a>
            </div>
        </form>
    </div>
 </div>
<?php include('../../templates/footer.php'); ?>