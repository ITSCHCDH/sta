<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/sta/php/clases/Profesor.php');
$Cal=new Profesor();
$Cal->changeMotRep($_POST['mat'],$_POST['par'],$_POST['nc'],$_POST['mrep'],(isset($_POST['crep'])?$_POST['crep']:'')); 
?>
