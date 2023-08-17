<?php
include('../../bdd.php');

if (isset($_GET['txtID'])) {

    $txtID = (isset($_GET['txtID'])) ? $_GET['txtID'] : "";

    $sentencia = $conexion->prepare("DELETE FROM usuario WHERE id=:id");
    $sentencia->bindParam(":id", $txtID);
    $sentencia->execute();

    $mensaje = "Registro eliminado";
    header('Location:index.php?mensaje=' . $mensaje);
}
$sentencia = $conexion->prepare("SELECT * FROM usuario");
$sentencia->execute();
$lista_usuarios = $sentencia->fetchAll(PDO::FETCH_ASSOC);
$route = "../..";
include('../../templates/header.php');
?>

<br>
<h4>Usuarios</h4>
<div class="card">
    <div class="card-header">
        <a name="" id="" class="btn btn-primary" href="crear.php" role="button"><b>Agregar Usuario</b></a>
    </div>
    <div class="table-responsive-sm">
        <table class="table table-striped display responsive nowrap" id='table_id' style="width:100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre del usuario</th>
                    <th>Contraseña</th>
                    <th>Correo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($lista_usuarios as $registro) { ?>
                    <tr>
                        <td><?php echo $registro['id']; ?></td>
                        <td><?php echo $registro['usuario']; ?></td>
                        <td><?php echo $registro['password']; ?></td>
                        <td><?php echo $registro['correo']; ?></td>
                        <td>
                            <a name="" id="" class="btn btn-info" href="editar.php?txtID=<?php echo $registro['id']; ?>" role="button"><b>Editar</b></a>
                            <a name="" id="" class="btn btn-danger" href="javascript:borrar(<?php echo $registro['id']; ?>);" role="button"><b>Eliminar</b></a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<?php include('../../templates/footer.php'); ?>