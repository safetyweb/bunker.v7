<?php 

	include '_system/_functionsMain.php';

	$opcao = fnLimpaCampo($_GET['opcao']);
	$tipo = fnLimpaCampo($_GET['tipo']);
	$cod_empresa = fnLimpaCampoZero(fnDecode($_GET['id']));
	$cod_campanha = fnLimpaCampoZero(fnDecode($_GET['idc']));
	$cod_usucada = $_SESSION['SYS_COD_USUARIO'];

	$ARRAY_UNIDADE1=array(
				   'sql'=>"select COD_UNIVEND,cod_empresa,nom_fantasi,NOM_UNIVEND from unidadevenda where cod_empresa=$cod_empresa  and cod_exclusa=0",
				   'cod_empresa'=>$cod_empresa,
				   'conntadm'=>$connAdm->connAdm(),
				   'IN'=>'N',
				   'nomecampo'=>'',
				   'conntemp'=>'',
				   'SQLIN'=> ""   
				   );
	$ARRAY_UNIDADE=fnUnivend($ARRAY_UNIDADE1);

	$sql = "SELECT MAX(LOG_OK) AS OK FROM EMAIL_CONTROLE 
			WHERE COD_EMPRESA = $cod_empresa 
			AND COD_CAMPANHA = $cod_campanha
			AND COD_LISTA = (
							 	SELECT MAX(COD_LISTA) FROM EMAIL_PARAMETROS
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

		$sqlGat = "SELECT TIP_GATILHO FROM GATILHO_EMAIL WHERE COD_CAMPANHA = $cod_campanha AND COD_EMPRESA = $cod_empresa";
		$qrGat = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sqlGat));

		if($qrGat['TIP_GATILHO'] == 'individual'){

			$sqlLote = "SELECT CP.DES_CAMPANHA, MAX(EL.COD_LOTE) AS NRO_LOTES 
						FROM EMAIL_LISTA EL
						LEFT JOIN CAMPANHA CP ON CP.COD_CAMPANHA = EL.COD_CAMPANHA
						WHERE EL.COD_EMPRESA = $cod_empresa 
						AND EL.COD_CAMPANHA = $cod_campanha";

			$qrLote = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sqlLote));
			$nro_lotes = $qrLote['NRO_LOTES'];
			$des_campanha = preg_replace('/\s+/', '_', fnAcentos($qrLote['DES_CAMPANHA']));

			$des_campanha = str_replace('/', '.', $des_campanha);
			
			include './_system/ibope/BuscarCampanha.php';
			include './_system/ibope/FnIbotpe.php';
			include '_system/ftpIbope.php';

			$sql = "SELECT CP.DAT_INI, 
						   CP.HOR_INI,
						   CP.COD_EXT_CAMPANHA, 
						   TE.COD_EXT_TEMPLATE,
						   TE.DES_ASSUNTO,
						   MDE.DES_TEMPLATE AS HTML
					FROM MENSAGEM_EMAIL ME
					LEFT JOIN TEMPLATE_EMAIL TE ON TE.COD_TEMPLATE = ME.COD_TEMPLATE_EMAIL
					LEFT JOIN CAMPANHA CP ON CP.COD_CAMPANHA = ME.COD_CAMPANHA
					INNER JOIN MODELO_EMAIL MDE ON MDE.COD_TEMPLATE = TE.COD_TEMPLATE
					WHERE ME.COD_EMPRESA = $cod_empresa 
					AND ME.COD_CAMPANHA = $cod_campanha
					AND ME.LOG_PRINCIPAL = 'S'";

			$qrMsg = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));

			$count = 0;

			if($qrMsg['COD_EXT_CAMPANHA'] && $qrMsg['COD_EXT_TEMPLATE']){

				$tagsPersonaliza=procpalavras($qrMsg['DES_ASSUNTO'].$qrMsg['HTML'],$connAdm->connAdm());

			$tagsPersonaliza = '<#EMAIL>,'.strtoupper($tagsPersonaliza);


			// $tags = explode(',',$tagsPersonaliza);

			$sqlLote = "SELECT * FROM EMAIL_LOTE
						WHERE COD_CAMPANHA = $cod_campanha 
						AND COD_EMPRESA = $cod_empresa
						AND LOG_TESTE = 'N'
						AND LOG_ENVIO = 'N'
						";

			$arrayLote = mysqli_query(connTemp($cod_empresa,''),$sqlLote);

			// fnEscreve('chega aqui');

			// exit();

			while($qrLote = mysqli_fetch_assoc($arrayLote)){

				$arraydebitos=array('quantidadeEmailenvio'=>$qrLote['QTD_LISTA'],
			                        'COD_EMPRESA'=>$cod_empresa,
			                        'PERMITENEGATIVO'=>'N',
			                        'COD_CANALCOM'=>'1',
			                        'CONFIRMACAO'=>'S',
			                        'COD_CAMPANHA'=>$cod_campanha,    
			                        'LOG_TESTE'=> 'N',
			                        'DAT_CADASTR'=> date('Y-m-d H:i:s'),
			                        'CONNADM'=>$connAdm->connAdm()
			                        ); 

			    $retornoDeb=FnDebitos($arraydebitos);

				if($qrLote['COD_STATUSUP'] == 3 && $retornoDeb['cod_msg'] == 1){

					$dat_ini = date("d/m/Y H:i",strtotime($qrLote['DAT_AGENDAMENTO']." +5 minutes"));

					// fnescreve($qrLote['DAT_INIAGENDAMENTO']);

					$dadosProcessaMailing=array(
			                            'txtNome'=>$des_campanha."#".$qrLote['COD_CONTROLE']."/".$qrLote['COD_LOTE'],
			                            'flgUploadArquivo'=> 1,
			                            'txtSeparadorDadoArquivo'=>';',
			                            'flgAtivo'=>1,      
			                            'txtPersonalizacao'=> $tagsPersonaliza,    
			                            'txtNomeArquivo'=>$qrLote['NOM_ARQUIVO'],
			                            'idConfig'=>$qrMsg['COD_EXT_TEMPLATE'],
			                            'idCampanha'=>$qrMsg['COD_EXT_CAMPANHA'],   
			                            'datAgendamento'=>$dat_ini
			                            );

					// echo '<pre>';
					// print_r($dadosProcessaMailing);
					// echo '</pre>';

					// $retornoCad = cadastraEProcessaMailing ($User,$dadosProcessaMailing);

					$retornoCad = cadastraEProcessaMailingV2 ($Userv2,$dadosProcessaMailing);



					echo '<pre>';
					print_r($retornoCad);
					echo '</pre>';

					$status = $retornoCad['body']['envelope']['body']['cadastraeprocessamailingv2response']['cadastraeprocessamailingv2result']['status'];
					$cod_mailing = $retornoCad['body']['envelope']['body']['cadastraeprocessamailingv2response']['cadastraeprocessamailingv2result']['mailingid'];

					// $cod_disparo_ext = BuscariDdisparo ($Userv2,$cod_mailing);

					// fnEscreve($status);

					if($status == 'true'){

						$sqlUpdt = "UPDATE EMAIL_LOTE SET 
									LOG_ENVIO = 'S',
									COD_MAILING_EXT = $cod_mailing,
									COD_EXT_TEMPLATE = $qrMsg[COD_EXT_TEMPLATE]
									WHERE COD_EMPRESA = $cod_empresa 
									AND COD_CAMPANHA = $cod_campanha 
									AND NOM_ARQUIVO = '".$qrLote[NOM_ARQUIVO]."';

									UPDATE EMAIL_CONTROLE SET DAT_ENVIO = NOW()
									WHERE COD_EMPRESA = $cod_empresa 
									AND COD_CAMPANHA = $cod_campanha;";

						mysqli_multi_query(connTemp($cod_empresa,''),$sqlUpdt);

						// echo date("d/m/Y H:i:s");

					}else{
						echo 'erro';
					}

					$count++;

				}else if($retornoDeb['cod_msg'] == 5){
					echo "Saldo insuficiente para processar todos os lotes";
				}
			}
		}

		}else{
			$sql = "UPDATE CAMPANHA SET LOG_PROCESSA = 'S' WHERE COD_CAMPANHA = $cod_campanha AND COD_EMPRESA = $cod_empresa";
			mysqli_query(connTemp($cod_empresa,''),$sql);
		}

	}else{

		echo "Necessária aprovação para o envio da lista";
		
	}


?>