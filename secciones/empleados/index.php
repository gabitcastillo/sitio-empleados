<?php
include('../../bdd.php');
$ruta_foto = "files/fotos/";
$ruta_cv = "files/cv/";

if (isset($_GET['txtID'])) {

    // $txtID = (isset($_GET['txtID'])) ? $_GET['txtID'] : "";
    $txtID =  $_GET['txtID'];

    // Buscar el archivo relacionado con el empleado
    $sentencia = $conexion->prepare("SELECT foto,cv FROM empleados WHERE id=:id");
    $sentencia->bindParam(':id', $txtID);
    $sentencia->execute();
    $registro_recuperado = $sentencia->fetch(PDO::FETCH_LAZY);

    //print_r($registro_recuperado);

    if (isset($registro_recuperado["foto"]) && $registro_recuperado["foto"] != "") {
        if (file_exists($ruta_foto. $registro_recuperado["foto"])) {
            unlink($ruta_foto. $registro_recuperado["foto"]);
        }
    }

    if (isset($registro_recuperado["cv"]) && $registro_recuperado["cv"] != "") {
        if (file_exists($ruta_cv. $registro_recuperado["cv"])) {
            unlink($ruta_cv. $registro_recuperado["cv"]);
        }
    }

    $sentencia = $conexion->prepare("DELETE FROM empleados WHERE id=:id");
    $sentencia->bindParam(":id", $txtID);
    $sentencia->execute();

    $mensaje = "Registro eliminado";
    header('Location:index.php?mensaje=' . $mensaje);
}
$sentencia = $conexion->prepare("SELECT *,(SELECT nombredelpuesto from puestos WHERE puestos.id=empleados.idpuesto limit 1) as puesto FROM `empleados`");
$sentencia->execute();
$empleados = $sentencia->fetchAll(PDO::FETCH_ASSOC);


$route = "../..";
include('../../templates/header.php');
?>

<br />
<h4>Empleados</h4>
<div class="card">
    <div class="card-header">
        <a name="" class="btn btn-primary" href="crear.php" role="button"><b>Agregar Empleado</b></a>
    </div>
    <table class="table table-striped display responsive nowrap" style="width:100%" id='table_id'>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Foto</th>
                <th>CV</th>
                <th>Puesto</th>
                <th>Fecha de Ingreso</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($empleados as $registro) { ?>
                <tr>
                    <td><?php echo $registro['id'] ?></td>
                    <td><?php echo $registro['nombre'] ?></td>
                    <td><?php echo $registro['apellido'] ?></td>
                    <td>
                        <img width="50" src="files/fotos/<?php echo $registro['foto'] ?>" class="img-fluid rounded" />
                    </td>
                    <td>
                        <a href="files/cv/<?php echo $registro['cv']; ?>">
                            <?php echo $registro['cv'] ?>
                        </a>
                    </td>
                    <td><?php echo $registro['puesto'] ?></td>
                    <td><?php echo $registro['fechadeingreso'] ?></td>
                    <td>
                        <a name="" id="" class="btn btn-light" href="carta_recomendacion.php?txtID=<?php echo $registro['id']; ?>" role="button"><b>Carta</b></a>
                        <a name="" id="" class="btn btn-info" href="editar.php?txtID=<?php echo $registro['id']; ?>" role="button"><b>Editar</b></a>
                        <a name="" id="" class="btn btn-danger" href="javascript:borrar(<?php echo $registro['id']; ?>);" role="button"><b>Eliminar</b></a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php include('../../templates/footer.php'); ?>