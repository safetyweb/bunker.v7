<?php 

include '_system/_functionsMain.php';

//echo fnDebug('true');

$cod_empresa = fnDecode($_GET['id']);
$cod_template_email = $_GET['tmp'];
$cod_campanha = fnLimpaCampoZero($_GET['idc']);
$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
$habilita = $_REQUEST['hbi'];

$sql = "SELECT * FROM TEMPLATE_AUTOMACAO WHERE COD_BLTEMPL = 22 AND COD_EMPRESA = $cod_empresa AND COD_CAMPANHA = $cod_campanha";
$query = mysqli_query(connTemp($cod_empresa,''),$sql);

if($qrBusca = mysqli_fetch_assoc($query)){
	$cod_template_bloco = $qrBusca['COD_TEMPLATE'];
}else{
	$cod_template_bloco = "";
}

$sql = "SELECT COD_MENSAGEM, COD_TEMPLATE_EMAIL FROM MENSAGEM_EMAIL 
WHERE COD_EMPRESA = $cod_empresa 
AND COD_CAMPANHA = $cod_campanha 
AND COD_TEMPLATE_BLOCO = $cod_template_bloco";

$query = mysqli_query(connTemp($cod_empresa,''),$sql);

if($habilita == 'S'){

	if($qrTempl = mysqli_fetch_assoc($query)){

		$cod_mensagem = fnlimpaCampoZero($qrTempl['COD_MENSAGEM']);

		$sql = "UPDATE MENSAGEM_EMAIL SET
		COD_TEMPLATE_EMAIL = $cod_template_email
		WHERE COD_EMPRESA = $cod_empresa AND COD_MENSAGEM = $cod_mensagem";
		
		mysqli_query(connTemp($cod_empresa,''),$sql);

	}else{

		$sql = "INSERT INTO MENSAGEM_EMAIL(
			COD_TEMPLATE_EMAIL,
			COD_TEMPLATE_BLOCO,
			COD_EMPRESA,
			COD_CAMPANHA,
			NUM_ORDENAC,
			LOG_PRINCIPAL,
			COD_USUCADA
			) VALUES(
			$cod_template_email,
			$cod_template_bloco,
			$cod_empresa,
			$cod_campanha,
			(SELECT NUM_ORDENAC FROM TEMPLATE_AUTOMACAO WHERE COD_TEMPLATE = $cod_template_bloco),
			'S',
			$cod_usucada
		)";
			mysqli_query(connTemp($cod_empresa,''),$sql);
	}

	$sqlUpdt2 = "UPDATE CAMPANHA SET 
	LOG_PROCESSA_EMAIL = '$habilita',
	DAT_PROCESSA_EMAIL = NOW(),
	DAT_ALTERAC = NOW()
	WHERE COD_EMPRESA = $cod_empresa 
	AND COD_CAMPANHA = $cod_campanha";
	
	mysqli_query(connTemp($cod_empresa,''),$sqlUpdt2);	

}else{

	if($qrTempl = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql))){

		$cod_mensagem = fnlimpaCampoZero($qrTempl['COD_MENSAGEM']);

		$sql = "UPDATE MENSAGEM_EMAIL SET
		COD_TEMPLATE_EMAIL = null
		WHERE COD_EMPRESA = $cod_empresa AND COD_MENSAGEM = $cod_mensagem";
		
		mysqli_query(connTemp($cod_empresa,''),$sql);

	}

	$sqlUpdt2 = "UPDATE CAMPANHA SET 
	LOG_PROCESSA_EMAIL = '$habilita',
	DAT_ALTERAC = NOW()
	WHERE COD_EMPRESA = $cod_empresa 
	AND COD_CAMPANHA = $cod_campanha";
	
	mysqli_query(connTemp($cod_empresa,''),$sqlUpdt2);	

}