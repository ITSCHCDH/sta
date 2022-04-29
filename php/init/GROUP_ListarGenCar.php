<?php
session_start();
require_once($_SERVER["DOCUMENT_ROOT"].'/php/clases/Grupo.php');
$Grupo=new Grupo();
$Grupo->listar_alumnosGE($_POST['Car'], $_POST['Gen']);
?>
