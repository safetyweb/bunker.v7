<?php 

	include '../_system/_functionsMain.php'; 	

	//echo fnDebug('true');
			
	$cod_credito = $_POST['COD_CREDITO'];
	$cod_usucada = $_POST['COD_USUCADA'];
	$cod_empresa = $_POST['COD_EMPRESA'];

	$sql = "CALL SP_EXCLUI_RESGATE($cod_credito,$cod_usucada,$cod_empresa)";
        
	mysqli_query(connTemp($cod_empresa,''),$sql);
        

?>