<?php 

include '_system/_functionsMain.php';

$cod_empresa = fnDecode($_GET['id']);
$opcao = $_GET['opcao'];
$cod_template_whatsapp = $_GET['tmp'];
$cod_campanha = fnLimpaCampoZero($_GET['idc']);
$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
$habilita = $_REQUEST['hbi'];

$sql = "SELECT * FROM TEMPLATE_AUTOMACAO_WHATSAPP WHERE COD_BLTEMPL = 25 AND COD_EMPRESA = $cod_empresa AND COD_CAMPANHA = $cod_campanha";

$query = mysqli_query(connTemp($cod_empresa,''),$sql);

if($qrBusca = mysqli_fetch_assoc($query)){
	$cod_template_bloco = $qrBusca['COD_TEMPLATE'];
}else{
	$cod_template_bloco = "";
}

$sql = "SELECT COD_MENSAGEM, COD_TEMPLATE_WHATSAPP FROM MENSAGEM_WHATSAPP 
WHERE COD_EMPRESA = $cod_empresa 
AND COD_CAMPANHA = $cod_campanha 
AND COD_TEMPLATE_BLOCO = $cod_template_bloco";

if($habilita == 'S'){

	if($qrTempl = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql))){

		$cod_mensagem = fnlimpaCampoZero($qrTempl['COD_MENSAGEM']);

		$sql = "UPDATE MENSAGEM_WHATSAPP SET
		COD_TEMPLATE_WHATSAPP = $cod_template_whatsapp
		WHERE COD_EMPRESA = $cod_empresa AND COD_MENSAGEM = $cod_mensagem";
		
		mysqli_query(connTemp($cod_empresa,''),$sql);

	}else{

		$sql = "INSERT INTO MENSAGEM_WHATSAPP(
			COD_TEMPLATE_WHATSAPP,
			COD_TEMPLATE_BLOCO,
			COD_EMPRESA,
			COD_CAMPANHA,
			NUM_ORDENAC,
			LOG_PRINCIPAL,
			COD_USUCADA
			) VALUES(
			$cod_template_whatsapp,
			$cod_template_bloco,
			$cod_empresa,
			$cod_campanha,
			(SELECT NUM_ORDENAC FROM TEMPLATE_AUTOMACAO_WHATSAPP WHERE COD_TEMPLATE = $cod_template_bloco),
			'S',
			$cod_usucada
		)";

			mysqli_query(connTemp($cod_empresa,''),$sql);
		}

		$sqlCampanha = "SELECT * FROM CAMPANHA WHERE COD_CAMPANHA = $cod_campanha";
		$query = mysqli_query(connTemp($cod_empresa, ''), $sqlCampanha);

		if($qrResult = mysqli_fetch_assoc($query)){
			if(!$qrResult['ABR_CAMPANHA'] == 'MASS'){
				$sqlUpdt2 = "UPDATE CAMPANHA SET 
				LOG_PROCESSA_WHATSAPP = '$habilita',
				DAT_ALTERAC = NOW()
				WHERE COD_EMPRESA = $cod_empresa 
				AND COD_CAMPANHA = $cod_campanha";
				
				mysqli_query(connTemp($cod_empresa,''),$sqlUpdt2);
			}
		}
		
	}else{

		if($qrTempl = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql))){

			$cod_mensagem = fnlimpaCampoZero($qrTempl['COD_MENSAGEM']);

			$sql = "UPDATE MENSAGEM_WHATSAPP SET
			COD_TEMPLATE_WHATSAPP = null
			WHERE COD_EMPRESA = $cod_empresa AND COD_MENSAGEM = $cod_mensagem";
			
			mysqli_query(connTemp($cod_empresa,''),$sql);

		}

		$sqlCampanha = "SELECT * FROM CAMPANHA WHERE COD_CAMPANHA = $cod_campanha";
		$query = mysqli_query(connTemp($cod_empresa, ''), $sqlCampanha);

		if($qrResult = mysqli_fetch_assoc($query)){
			if(!$qrResult['ABR_CAMPANHA'] == 'MASS'){
				$sqlUpdt2 = "UPDATE CAMPANHA SET 
				LOG_PROCESSA_WHATSAPP = '$habilita',
				DAT_ALTERAC = NOW()
				WHERE COD_EMPRESA = $cod_empresa 
				AND COD_CAMPANHA = $cod_campanha";
				
				mysqli_query(connTemp($cod_empresa,''),$sqlUpdt2);
			}
		}

	}