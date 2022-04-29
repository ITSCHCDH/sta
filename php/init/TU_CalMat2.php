<?php
    require_once($_SERVER["DOCUMENT_ROOT"].'/php/clases/Materias.php');
    $Cal=new Materias();
    $Cal->materiasAll();
?>
