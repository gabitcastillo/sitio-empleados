<?php
include('../../bdd.php');

$sentencia = $conexion->prepare("SELECT * FROM puestos");
$sentencia->execute();
$lista_puestos = $sentencia->fetchAll(PDO::FETCH_ASSOC);

if (isset($_GET['txtID'])) {

    $txtID = (isset($_GET['txtID'])) ? $_GET['txtID'] : "";
    $sentencia = $conexion->prepare("DELETE FROM puestos WHERE id=:id");
    $sentencia->bindParam(":id", $txtID);
    $sentencia->execute();

    $mensaje = "Registro eliminado";
    header('Location:index.php?mensaje=' . $mensaje);
}
$route = "../..";
include('../../templates/header.php');
?>

<br />
<h4>Puestos</h4>
<div class="card">
    <div class="card-header">
        <a name="" id="" class="btn btn-primary" href="crear.php" role="button"><b>Agregar Puesto</b><a>
    </div>
    <div class="table-responsive-sm">
        <table class="table table-striped display responsive nowrap" id='table_id' style="width:100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre del puesto</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($lista_puestos as $registro) { ?>
                    <tr>
                        <td scope="row"><?php echo $registro['id'] ?></td>
                        <td><?php echo $registro['nombredelpuesto'] ?></td>
                        <td>
                            <a name="" id="" class="btn btn-info" href="editar.php?txtID=<?php echo $registro['id']; ?>" role="button"><b>Editar</b></a> |
                            <a name="" id="" class="btn btn-danger" href="javascript:borrar(<?php echo $registro['id']; ?>);" role="button"><b>Eliminar</b></a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<?php include('../../templates/footer.php'); ?>