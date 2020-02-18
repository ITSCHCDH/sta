<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/sta/php/clases/Profesor.php');
$Cal=new Profesor();
$Cal->consCalMaterias($_POST['Mat'],$_POST['Pro'],$_POST['NomMat']);
?>
