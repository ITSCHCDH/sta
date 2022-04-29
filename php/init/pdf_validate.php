<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/php/clases/Tutor.php');
$tutor=new tutor();
$tutor->valid_format($_POST['ncon'],$_POST['val']);
