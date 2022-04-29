<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/php/clases/JfeCar.php');
$Grupo = new Jfe();
$Grupo->Generaciones($_POST['Car']);
?>
