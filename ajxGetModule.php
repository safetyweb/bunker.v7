<?php

include '_system/_functionsMain.php';

$cod_modulos = fnLimpacampoZero($_REQUEST['id']);

$sql = "SELECT * FROM MODULOS where COD_MODULOS = '" . $cod_modulos . "' ";
//fnEscreve($sql);
$rs = mysqli_query($connAdm->connAdm(), $sql);
$linha = mysqli_fetch_assoc($rs);


header('Content-Type: application/json');
echo json_encode($linha);
