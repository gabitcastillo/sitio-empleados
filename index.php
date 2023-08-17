<?php 
    $route = ".";
    include('./templates/header.php');
?>

    <br><br><br>
    <div class="jumbotron jumbotron-fluid">
        <div class="container">
            <h3 class="display-3">Bienvenidos al Sistema de Empleados IT.</h3>
            <p class="lead">Usuario: <?php echo $_SESSION['usuario'];?></p>
            <hr class="my-2">
            <p>Más información</p>
            <p class="lead">
                <button id="btn-acerca" type="submit" class="btn btn-success"><b>Acerca del sitio</b></button>
            </p>
        </div>
    </div>
<script>
    document.getElementById('btn-acerca').addEventListener("click", function() {
        Swal.fire({
        title: 'Información',
        text: 'En este sistema podras registrarte, iniciar sesión con tus creedenciales y realizar un ABM de empleados, puestos laborales y usuarios.',
        icon: 'info',
        confirmButtonText: 'OK'
        });
    });
</script>

<?php include('./templates/footer.php'); 