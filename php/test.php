<?php
require_once $_SERVER["DOCUMENT_ROOT"]."/sta/php/dbconfig.php";

$db = new mysqli(DB_SERVER,DB_USER,DB_PASS,DB_NAME);
if ($db ->connect_errno) {
    echo "Fallo al conectar a MySQL: ". $db->connect_error;
    return;
}
else{
  echo "string paso";
}

if (!$db->set_charset("utf8")) {
    printf("Error cargando el conjunto de caracteres utf8: %s\n", $mysqli->error);
}
echo "holaa";

?>
