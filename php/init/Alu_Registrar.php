<?php
session_start();
require_once($_SERVER["DOCUMENT_ROOT"] . '/sta/php/clases/Alumno.php');
if (isset($_SESSION['usuario']['Tipo'])) {

    if ($_SESSION['usuario']['Tipo'] == "Alu") {
        $Alu = new Alumno();
        $Alu->saveFichaAlu($_SESSION['usuario']['Clave']);
    }else{
        header('location:/sta/php/CerrarSesion.php');
    }
}else{
    header('location:/sta/php/CerrarSesion.php');
}
?>
