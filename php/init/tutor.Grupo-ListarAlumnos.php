<?php
    require_once '/clases/Grupo.php';
    $Grupo=new Grupo();
    $Grupo->listar_alumnos($_POST['Grup']);
    #$Grupo->listar_alumnos('Nano Prueba2');
    #echo $Grupo->consultarCalificacionGnr('120300178');
 ?>
