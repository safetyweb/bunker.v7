<?php 
include './_system/_functionsMain.php';

$cod_empresa = fnLimpaCampoZero($_GET['id']);
$cod_cliente = fnLimpaCampoZero($_POST['COD_CLIENTE']);
$cod_indicador = fnLimpaCampoZero($_POST['COD_INDICADOR']);

//fnEscreve($cod_empresa);
//fnEscreve($cod_cliente);
//fnEscreve($cod_indicador);

if($cod_cliente != 0 ){

	$sql = "UPDATE CLIENTES_EXTERNO 
			SET COD_INDICAD = $cod_indicador
			WHERE COD_CLIENTE = $cod_cliente";

	fnEscreve($sql);
	fnTestesql(connTemp($cod_empresa,''),$sql);

}
?>