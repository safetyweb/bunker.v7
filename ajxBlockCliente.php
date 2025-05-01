<?php
include "_system/_functionsMain.php";
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$cod_cliente = "";
$log_desblok = "";

$cod_empresa = fnLimpaCampoZero(fnDecode(@$_GET['id']));
$cod_cliente = fnLimpaCampoZero(@$_POST['COD_CLIENTE']);
$log_desblok = fnLimpaCampo(@$_POST['LOG_DESBLOK']);

$sql = "UPDATE CLIENTES
		SET LOG_DESBLOK = '$log_desblok'
		WHERE COD_EMPRESA = $cod_empresa
		AND COD_CLIENTE = $cod_cliente";

fnEscreve($sql);
mysqli_query(connTemp($cod_empresa, ''), trim($sql));

//fnEscreve($sql);
