<?php
session_start();
require_once($_SERVER["DOCUMENT_ROOT"].'/php/clases/User.php');
$Doc = new Usuario();

$Doc->editPerfil_img($_SESSION['usuario']['Tipo'],$_SESSION['usuario']['Clave']);
#$Doc->setPassWord('Alu','12030178','Rmx2030$#');
?>
