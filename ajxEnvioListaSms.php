<?php 

	include '_system/_functionsMain.php';

	$opcao = fnLimpaCampo($_GET['opcao']);
	$tipo = fnLimpaCampo($_GET['tipo']);
	$cod_empresa = fnLimpaCampoZero(fnDecode($_GET['id']));
	$cod_campanha = fnLimpaCampoZero(fnDecode($_GET['idc']));
	$cod_usucada = $_SESSION['SYS_COD_USUARIO'];

	$sql = "SELECT MAX(LOG_OK) AS OK FROM SMS_CONTROLE 
			WHERE COD_EMPRESA = $cod_empresa 
			AND COD_CAMPANHA = $cod_campanha
			AND COD_LISTA = (
							 	SELECT MAX(COD_LISTA) FROM SMS_PARAMETROS
							 	WHERE COD_EMPRESA = $cod_empresa 
							 	AND COD_CAMPANHA = $cod_campanha
							)";

	$qrOk = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));

	if($qrOk['OK'] == 'S'){
		$log_ok = 'S';
	}else{
		$log_ok = 'N';
	}

	if($log_ok == 'S'){

		$sqlGat = "SELECT TIP_GATILHO FROM GATILHO_SMS WHERE COD_CAMPANHA = $cod_campanha AND COD_EMPRESA = $cod_empresa";
		$qrGat = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sqlGat));

		// if($qrGat['TIP_GATILHO'] == 'individual'){

		// $arraydebitos=array('quantidadeEmailenvio'=>$qrLote['QTD_LISTA'],
	 //                        'COD_EMPRESA'=>$cod_empresa,
	 //                        'PERMITENEGATIVO'=>'N',
	 //                        'COD_CANALCOM'=>'1',
	 //                        'CONFIRMACAO'=>'S',
	 //                        'COD_CAMPANHA'=>$cod_campanha,    
	 //                        'LOG_TESTE'=> 'N',
	 //                        'DAT_CADASTR'=> date('Y-m-d H:i:s'),
	 //                        'CONNADM'=>$connAdm->connAdm()
	 //                        ); 

	 //    $retornoDeb=FnDebitos($arraydebitos);

		$sql = "SELECT TE.COD_TEMPLATE
				FROM MENSAGEM_SMS ME
				INNER JOIN TEMPLATE_SMS TE ON TE.COD_TEMPLATE = ME.COD_TEMPLATE_SMS
				WHERE ME.COD_EMPRESA = $cod_empresa 
				AND ME.COD_CAMPANHA = $cod_campanha";

		// fnEscreve($sql);

		$qrMsg = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));

		$sqlUpdt = "UPDATE SMS_LOTE SET 
					LOG_ENVIO = 'N',
					COD_EXT_TEMPLATE = $qrMsg[COD_TEMPLATE]
					WHERE COD_EMPRESA = $cod_empresa 
					AND COD_CAMPANHA = $cod_campanha
					AND LOG_ENVIO = 'P'";

		mysqli_query(connTemp($cod_empresa,''),$sqlUpdt);

		if($qrGat['TIP_GATILHO'] != 'individual'){
			$sqlUpdt2 = "UPDATE CAMPANHA SET 
						LOG_PROCESSA = 'S'
						WHERE COD_EMPRESA = $cod_empresa 
						AND COD_CAMPANHA = $cod_campanha";

			mysqli_query(connTemp($cod_empresa,''),$sqlUpdt2);
		}

		sleep(5);

		// echo date("d/m/Y H:i:s");

		// if($retornoDeb['cod_msg'] == 5){
		// 	echo "Saldo insuficiente para processar todos os lotes";
		// }


	}else{

		echo "Necessária aprovação para o envio da lista";
		
	}


?>