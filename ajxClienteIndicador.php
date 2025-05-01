<?php 
include './_system/_functionsMain.php';

$cod_empresa = fnLimpaCampoZero($_GET['id']);
$cod_cliente = fnLimpaCampoZero($_POST['COD_CLIENTE']);
$cod_indicador = fnLimpaCampoZero($_POST['COD_INDICADOR']);

//fnEscreve($cod_empresa);
//fnEscreve($cod_cliente);
//fnEscreve($cod_indicador);

if($cod_cliente != 0 ){

	$sql = "UPDATE CLIENTES SET COD_INDICAD = $cod_indicador, DAT_INDICAD = NOW() WHERE COD_CLIENTE = $cod_cliente";
	fnEscreve($sql);
	mysqli_query(connTemp($cod_empresa,''),$sql);

	$sql = "SELECT DAT_INDICAD FROM CLIENTES WHERE COD_CLIENTE = $cod_cliente";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);

	$qrDat = mysqli_fetch_assoc($arrayQuery);

	echo date("d/m/Y H:i:s", strtotime($qrDat['DAT_INDICAD']));

}
?>