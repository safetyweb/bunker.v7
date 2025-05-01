<?php 

	include '_system/_functionsMain.php'; 	

	//echo fnDebug('true');	

	$cod_empresa = fnLimpaCampoZero($_POST['pk']);
	$campo = fnLimpaCampo($_POST['name']);
	$valor = fnLimpaCampo($_POST['value']);

	$sql = "UPDATE CONTROLE_TERMO SET $campo='$valor', DAT_ALTERAC = NOW(), COD_ALTERAC = $_SESSION[SYS_COD_USUARIO] WHERE COD_EMPRESA = $cod_empresa";
	fnEscreve($sql);
	fnTestesql(connTemp($cod_empresa,''),$sql);

				
?>