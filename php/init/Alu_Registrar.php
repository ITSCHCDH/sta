<?php
session_start();
require_once($_SERVER["DOCUMENT_ROOT"] . '/sta/php/clases/Alumno.php');
$Alu = new Alumno();
$Alu->saveFichaAlu($_SESSION['usuario']['Clave']);
?>