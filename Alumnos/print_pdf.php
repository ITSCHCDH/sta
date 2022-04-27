<?php
require_once $_SERVER["DOCUMENT_ROOT"].'/dompdf/lib/html5lib/Parser.php';
require_once $_SERVER["DOCUMENT_ROOT"].'/dompdf/lib/php-font-lib/src/FontLib/Autoloader.php';
require_once $_SERVER["DOCUMENT_ROOT"].'/dompdf/lib/php-svg-lib/src/autoload.php';
require_once $_SERVER["DOCUMENT_ROOT"].'/dompdf/src/Autoloader.php';
session_start();

if (isset($_SESSION['usuario']['Tipo'])) {
    if ($_SESSION['usuario']['Tipo'] != "Alu") {
        if ($_SESSION['usuario']['Tipo']=="Admin"){
            header('location: ' .$_SERVER["DOCUMENT_ROOT"].'/Admin/');
        }elseif ($_SESSION['usuario']['Tipo']=="Profe"){
            header('location: ' .$_SERVER["DOCUMENT_ROOT"].'/Profe/');
        }elseif ($_SESSION['usuario']['Tipo']=="Tutor"){
            header('location: ' .$_SERVER["DOCUMENT_ROOT"].'/Tutor/');
        }elseif ($_SESSION['usuario']['Tipo']=="Jefe"){
            header('location: ' .$_SERVER["DOCUMENT_ROOT"].'/JefeCar/');
        }elseif ($_SESSION['usuario']['Tipo']=="Dire"){
            header('location: ' .$_SERVER["DOCUMENT_ROOT"].'/Direc/');
        }elseif ($_SESSION['usuario']['Tipo']=="Tutoria"){
            header('location: ' .$_SERVER["DOCUMENT_ROOT"].'/ATutor/');
        }elseif ($_SESSION['usuario']['Tipo']=="Medic"){
            header('location: ' .$_SERVER["DOCUMENT_ROOT"].'/Medico/');
        }
    }
}else{
    header('location:/php/CerrarSesion.php');
}
require_once($_SERVER["DOCUMENT_ROOT"] . '/php/clases/Alumno.php');
$Alu = new Alumno();

Dompdf\Autoloader::register();

use Dompdf\Dompdf;

ob_start();

$Alu->printpdf($_SESSION['usuario']['Clave']);
$html= ob_get_clean();
// instantiate and use the dompdf class
$dompdf = new Dompdf();
// Load HTML content
$dompdf->loadHtml($html);

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('letter', 'portrait');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
$dompdf->stream($_SESSION['usuario']['Clave']);
?>
