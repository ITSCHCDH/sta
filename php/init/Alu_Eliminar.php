<?php
session_start();
require_once($_SERVER["DOCUMENT_ROOT"] . '/php/clases/Grupo.php');
$Grupo = new Grupo();
$Grupo->dropAlu($_POST['nom'],$_POST['Grup']);
?>
