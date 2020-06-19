<?php
    require_once($_SERVER["DOCUMENT_ROOT"].'/sta/php/clases/Tutor.php');
    $Grupo=new tutor();
    $Grupo->llenarAlumnos($_POST['Grup']);
    #$Grupo->listar_alumnos('Nano Prueba2');
    #echo $Grupo->consultarCalificacionGnr('120300178');
 ?>
