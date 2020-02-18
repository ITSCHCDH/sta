<?php
session_start();
require_once($_SERVER["DOCUMENT_ROOT"].'/sta/php/clases/Grupo.php');
$Grupo=new Grupo();
$Grupo->listar_alumnosFormatos($_POST['Grup']);
?>
