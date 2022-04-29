<?php
session_start();
require_once($_SERVER["DOCUMENT_ROOT"].'/php/clases/Grupo.php');
$Grupo=new Grupo();
$Grupo->listar_alumnos($_POST['Grup']);
?>
