<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/sta/php/clases/JfeCar.php');
$Grupo = new Jfe();
$Grupo->Generaciones($_POST['Car']);
?>
