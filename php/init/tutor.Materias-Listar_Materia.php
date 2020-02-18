<?php
    require_once '../clases/Materias.php';
    require_once '../clases/Grupo.php';

    $Grupo = new Grupo();

    $Materia= new Materias();

    $Materia->listarMaterias($Grupo->getIndGrupo($_POST['Grup']));
    #$Materia->listarMaterias("Nano Prueba2");
?>
