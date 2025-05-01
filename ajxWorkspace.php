<?php 

	include '_system/_functionsMain.php'; 

	$url = fnDecode($_REQUEST['url']);
	$mod = fnDecode($_REQUEST['mod']);
	$usuario = $_SESSION['SYS_COD_USUARIO'];
	$sistema = $_SESSION['SYS_COD_SISTEMA'];
	$cod_empresa = $_SESSION['SYS_COD_EMPRESA'];

	$sqlVerifica = "SELECT * FROM LINKS_WORKSPACE 
					WHERE DES_URL = '$url' 
					AND COD_USUARIO = '$usuario' 
					AND COD_SISTEMA = '$sistema'";
	
	$arrVerifica = mysqli_query(connTemp($cod_empresa,''), $sqlVerifica);
	$qrVerifica = mysqli_fetch_assoc($arrVerifica);
	
	$cod_link = fnLimpaCampoZero($qrVerifica['COD_LINK']);

	if($cod_link == 0){
		$sqlPin = "INSERT INTO LINKS_WORKSPACE (DES_URL,COD_USUARIO,COD_SISTEMA,COD_MODULO) VALUES('$url','$usuario','$sistema','$mod')";
		$arrInsert = mysqli_query(connTemp($cod_empresa,''), $sqlPin);
		echo 1;
	}else{
		$sqlPin = "DELETE FROM LINKS_WORKSPACE WHERE COD_LINK = $cod_link";
		$arrUpdate = mysqli_query(connTemp($cod_empresa,''), $sqlPin);
		echo 0;
	}
	// fnEscreve($sqlPin);
	

?>