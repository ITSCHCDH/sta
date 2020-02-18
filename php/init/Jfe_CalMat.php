<?php
    session_start();
    require_once($_SERVER["DOCUMENT_ROOT"].'/sta/php/clases/Materias.php');
    $Cal=new Materias();
    $Cal->materiasAll2();
?>
