<?php

include "_system/_functionsMain.php"; 

$acao = fnLimpacampo(@$_REQUEST['acao']);
$cod_empresa = fnLimpacampozero(fnDecode(@$_REQUEST['cod_empresa']));
$cod_bloco = fnLimpacampozero(@$_REQUEST['cod_bloco']);

if ($acao == "del"){
	$sql = "UPDATE BLOCO_TERMOS SET LOG_EXCLUSAO='S', COD_EXCLUSAO=$_SESSION[SYS_COD_USUARIO], DAT_EXCLUSAO=NOW() WHERE COD_BLOCO='$cod_bloco' AND COD_EMPRESA = $cod_empresa";
	//fnescreve($sql);
	mysqli_query(connTemp($cod_empresa,''),$sql);
	echo "ok";
}