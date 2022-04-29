<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/php/clases/areaPS.php');
$Grupo = new ps();
$Grupo->Generaciones($_POST['Car']);
