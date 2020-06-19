<?php
session_start();
require_once($_SERVER["DOCUMENT_ROOT"].'/sta/php/clases/User.php');
$Doc = new Usuario();

$Doc->setPassWord($_POST['userT'],$_POST['user'],$_POST['pass']);
#$Doc->setPassWord('Alu','12030178','Rmx2030$#');
?>
