<?php
include('../../bdd.php');

if(isset($_GET['txtID'])){

    $txtID=(isset($_GET['txtID']))?$_GET['txtID']:"";

    // Buscar el archivo relacionado con el empleado
    $sentencia= $conexion->prepare("SELECT *,(SELECT nombredelpuesto from puestos WHERE puestos.id=empleados.idpuesto limit 1) as puesto
    FROM `empleados` WHERE id=:id");
    $sentencia->bindParam(':id', $txtID);
    $sentencia->execute();   
    $registro= $sentencia->fetch(PDO::FETCH_LAZY);


    $nombre=$registro["nombre"];
    $apellido=$registro["apellido"];

    $nombreApellido=$nombre." ".$apellido;

    $foto=$registro["foto"];
    $cv=$registro["cv"];

    $idpuesto=$registro["idpuesto"];
    $fechadeingreso=$registro["fechadeingreso"];
    $puesto=$registro["puesto"];


    $fechaInicio= new DateTime($fechadeingreso);
    $fechaFin= new DateTime(date('Y-m-d'));
    $diferencia=date_diff($fechaInicio, $fechaFin);
    $fechaActual= date(date('Y-m-d'));
}
ob_start();
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width= , initial-scale=1.0">
    <title>Carta de recomendación</title>
</head>
<body>
    <h1>Carta de Recomendación Laboral</h1>
    <br>
    Buenos Aires, Argentina
    <br><br>
    Estimados:
    <br><br>
    A través de estas líneas deseo hacer de su conocimiento que Sr(a) <strong><?php echo $nombreApellido; ?> </strong> quien laboro en mi organización durante <strong> <?php echo $diferencia->y; ?> </strong>año(s) es un ciudadano con una conducta ejemplar. Ha demostrado ser un excelente trabajador, comprometido, responsable y fiel cumplidor de sus tareas.
    Siempre ha manisfesto su preocupación por mejorar, capacitarse y actualizar sus conocimientos.
    <br><br>
    Durante estos años se ha desempeñado como: <strong> <?php echo $puesto; ?> </strong>.
    Es por ello le sugiero considere esta recomendación, con la confianza de que estara siempre a la altura de sus compromiso y sus responsabilidades.
    <br><br>
    Sin más nada a que referirme y, esperando que esta misiva sea tomada en su cuenta, dejo mi numero de contacto para cualquier interés.
    <br><br><br><br>
    
    Reciba un cordial y respetuoso saludo.
    <br><br>
    Atentamente,
    <br>
    Gabriel Castillo
    <br><br>
    <i> <?php echo $fechaActual; ?></i>
</body>
</html>

<?php

    $HTML= ob_get_clean();

    require_once('../../libs/autoload.inc.php');

    use Dompdf\Dompdf;

    $dompdf= new Dompdf();

    $opciones= $dompdf->getOptions();

    $opciones->set(array("isRemoteEnabled"=>true));

    $dompdf->setOptions($opciones);
    
    $dompdf->loadHTML($HTML);
    
    $dompdf->setPaper('letter');
    $dompdf->render();
    $dompdf->stream("archivo.pdf", array("Attachment"=>false));


?>