<?php
session_start();
require_once($_SERVER["DOCUMENT_ROOT"].'/php/clases/Alumno.php');
$Alu = new Alumno();

$Alu->upPdf($_SESSION['usuario']['Tipo'],$_SESSION['usuario']['Clave']);
#$Doc->setPassWord('Alu','12030178','Rmx2030$#');
?>
