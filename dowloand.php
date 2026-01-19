<?php
require("_Ajax.comun.php");
if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}

$id = $_REQUEST['ruta'];

$enlace = path(DIR_ARCHIVOS).'/'.$id;
header ("Content-Disposition: attachment; filename=".$id." ");
header ("Content-Type: application/octet-stream");
header ("Content-Length: ".filesize($enlace));
readfile($enlace);

?>
