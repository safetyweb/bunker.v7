<?php
include '_system/_functionsMain.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

$cod_empresa = $_REQUEST["codempresa"];
$name = $_REQUEST["name"];
$value = $_REQUEST["value"];
$pk = $_REQUEST["pk"];
$id = $_REQUEST["value_id"];

//$conn = conntemp($cod_empresa, '');
$conn = $connAdm->connAdm();

$sql = "UPDATE USUARIOS SET $name = '$value',COD_ALTERAC=0" . $_SESSION["SYS_COD_USUARIO"] . ",DAT_ALTERAC=NOW() WHERE $pk = $id AND COD_EMPRESA = $cod_empresa";
//echo $sql;
mysqli_query($conn, trim($sql)) or die(mysqli_error($conn));
