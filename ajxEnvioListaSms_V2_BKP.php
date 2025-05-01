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

		$sql = "SELECT TE.COD_TEMPLATE
				FROM MENSAGEM_SMS ME
				INNER JOIN TEMPLATE_SMS TE ON TE.COD_TEMPLATE = ME.COD_TEMPLATE_SMS
				WHERE ME.COD_EMPRESA = $cod_empresa 
				AND ME.COD_CAMPANHA = $cod_campanha";

		// fnEscreve($sql);

		$qrMsg = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));

		if($qrGat['TIP_GATILHO'] != 'individual'){

			$sqlUpdt2 = "UPDATE CAMPANHA SET 
						LOG_PROCESSA_SMS = 'S'
						WHERE COD_EMPRESA = $cod_empresa 
						AND COD_CAMPANHA = $cod_campanha";

			mysqli_query(connTemp($cod_empresa,''),$sqlUpdt2);

		}else{

			$sqlDec = "SELECT TIP_RETORNO, NUM_DECIMAIS_B FROM empresas where COD_EMPRESA = $cod_empresa";
			//fnEscreve($sql);
			$arrayQuery = mysqli_query($connAdm->connAdm(),$sqlDec);
			$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
			
			if (isset($arrayQuery)){

				$tip_retorno = $qrBuscaEmpresa['TIP_RETORNO'];
				$num_decimais_b = $qrBuscaEmpresa['NUM_DECIMAIS_B'];

				if($tip_retorno == 1){
					$casasDec = 0;
				}else{
					$casasDec = $qrBuscaEmpresa['NUM_DECIMAIS_B'];
				}
			}

			// $sqlLote = "SELECT MAX(COD_LOTE) AS TOT_LOTE FROM SMS_LOTE 
			// 			WHERE COD_CAMPANHA = $cod_campanha 
			// 			AND COD_EMPRESA = $cod_empresa";

			// $arrayLote = mysqli_query(connTemp($cod_empresa,''),$sqlLote);

			// $qrLote = mysqli_fetch_assoc($arrayLote);

			// $max_lote = $qrLote['TOT_LOTE'];

			$sql = "SELECT CP.DES_CAMPANHA, 
					   CP.DAT_INI, 
					   CP.HOR_INI,
					   CP.COD_EXT_CAMPANHA, 
					   SPA.COD_PERSONAS,
					   TS.COD_TEMPLATE,
					   TS.DES_TEMPLATE AS HTML 
					FROM CAMPANHA CP
					INNER JOIN SMS_PARAMETROS SPA ON SPA.COD_CAMPANHA = CP.COD_CAMPANHA
					INNER JOIN MENSAGEM_SMS MS ON MS.COD_CAMPANHA = SPA.COD_CAMPANHA
					INNER JOIN TEMPLATE_SMS TS ON TS.COD_TEMPLATE = MS.COD_TEMPLATE_SMS
					WHERE CP.COD_EMPRESA = $cod_empresa
					AND CP.COD_CAMPANHA = $cod_campanha
					ORDER BY 1 DESC LIMIT 1";

			// fnEscreve($sql);
			$qrMsg = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));


			$des_campanha = preg_replace('/\s+/', '_', fnAcentos($qrMsg['DES_CAMPANHA']));

			$des_campanha = str_replace('/', '.', $des_campanha);

			// fnEscreve($qrMsg['HTML']);

			$tagsPersonaliza=procpalavras($qrMsg['HTML'],$connAdm->connAdm());

			$tagsPersonaliza = '<#EMAIL>,<#CODIGO>,'.$tagsPersonaliza;

			$tags = explode(',',$tagsPersonaliza);

			$selectCliente = "";	

			for ($i=0; $i < count($tags) ; $i++) {
				// fnEscreve($tags[$i]);
				switch($tags[$i]){

					case '<#NOME>';
						$selectCliente .= "C.NOM_CLIENTE,";
					break;
					case '<#CARTAO>';
						$selectCliente .= "";
					break;
					case '<#ESTADOCIVIL>';
						$selectCliente .= "";
					break;
					case '<#SEXO>';
						$selectCliente .= "";
					break;
					case '<#PROFISSAO>';
						$selectCliente .= "";
					break;
					case '<#NASCIMENTO>';
						$selectCliente .= "";
					break;
					case '<#ENDERECO>';
						$selectCliente .= "";
					break;
					case '<#NUMERO>';
						$selectCliente .= "";
					break;
					case '<#BAIRRO>';
						$selectCliente .= "";
					break;
					case '<#CIDADE>';
						$selectCliente .= "";
					break;
					case '<#ESTADO>';
						$selectCliente .= "";
					break;
					case '<#CEP>';
						$selectCliente .= "";
					break;
					case '<#COMPLEMENTO>';
						$selectCliente .= "";
					break;
					case '<#TELEFONE>';
						$selectCliente .= "";
					break;
					case '<#CELULAR>';
						$selectCliente .= "";
					break;
					case '<#SALDO>';
						$selectCliente .= "(SELECT 
											IFNULL((SELECT Sum(val_saldo) FROM creditosdebitos f 
											WHERE f.cod_cliente = cred.cod_cliente AND 
													f.tip_credito = 'C' AND 
													f.cod_statuscred = 1 AND 
													f.tip_campanha = cred.tip_campanha AND 
													(( f.log_expira = 'S' AND DATE_FORMAT(f.dat_expira, '%Y-%m-%d') >= Date_format(Now(), '%Y-%m-%d') ) OR ( f.log_expira = 'N' ) )),0)+
											IFNULL((SELECT Sum(val_saldo) FROM creditosdebitos_bkp g
											WHERE g.cod_cliente = cred.cod_cliente AND 
													g.tip_credito = 'C' AND 
													g.cod_statuscred = 1 AND 
													g.tip_campanha = cred.tip_campanha AND 
													((g.log_expira = 'S' AND DATE_FORMAT(g.dat_expira, '%Y-%m-%d') >= Date_format(Now(), '%Y-%m-%d') ) OR (g.log_expira = 'N' ) )),0) AS CREDITO_DISPONIVEL
													FROM creditosdebitos cred 
													WHERE cred.cod_cliente=C.cod_CLIENTE
													GROUP BY cred.cod_cliente ) AS CREDITO_DISPONIVEL,";
					break;
					case '<#PRIMEIRACOMPRA>';
						$selectCliente .= "";
					break;
					case '<#ULTIMACOMPRA>';
						$selectCliente .= "";
					break;
					case '<#TOTALCOMPRAS>';
						$selectCliente .= "";
					break;
					case '<#CODIGO>';
						$selectCliente .= "C.COD_CLIENTE,";
					break;
					case '<#CUPOMSORTEIO>';
						$selectCliente .= "";
					break;
					case '<#CUPOM_INDICACAO>';
						$selectCliente .= "";
					break;
					case '<#NUMEROLOJA>';
						$selectCliente .= "";
					break;
					case '<#BAIRROLOJA>';
						$selectCliente .= "";
					break;
					case '<#NOMELOJA>';
						$selectCliente .= "C.COD_UNIVEND,";
					break;
					case '<#ENDERECOLOJA>';
						$selectCliente .= "";
					break;
					case '<#TELEFONELOJA>';
						$selectCliente .= "";
					break;
					case '<#ANIVERSARIO>';
						$selectCliente .= "C.DAT_NASCIME,";
					break;
					case '<#DATAEXPIRA>';
						$selectCliente .= "(SELECT 
										        MIN(DAT_EXPIRA) AS DAT_EXPIRA
									        	FROM creditosdebitos 
											    WHERE DAT_EXPIRA >= NOW() AND  cod_CLIENTE=C.cod_CLIENTE) AS DAT_EXPIRA,";
					break;
					default:
						$selectCliente .= "C.NUM_CELULAR,";
					break;
					
				}
				
			}

			$selectCliente = rtrim($selectCliente,',');

			$sqlEnvio = "SELECT $selectCliente, SLT.COD_LOTE, SLT.DAT_AGENDAMENTO, MAX(SLT.COD_LOTE) AS TOT_LOTE FROM sms_lista SLS
							INNER JOIN sms_lote SLT ON SLT.COD_LOTE = SLS.COD_LOTE
							INNER JOIN CLIENTES C ON C.COD_CLIENTE = SLS.COD_CLIENTE
							WHERE SLS.COD_CAMPANHA = $cod_campanha
							AND SLT.LOG_ENVIO = 'P'
							AND SLS.LOG_COMPARA = 0
							GROUP BY SLS.COD_CLIENTE
							ORDER BY SLT.COD_LOTE
							";

			// fnEscreve($sqlEnvio);
			$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sqlEnvio);


			$array = array();
			$linhas = 0;

			$sqlInsertRel = "";
			$sqlInsertCtrl = "";

			$qtd_contatos = mysqli_num_rows($arrayQuery);

			$arraydebitos=array('quantidadeEmailenvio'=>$qtd_contatos,
		                        'COD_EMPRESA'=>$cod_empresa,
		                        'PERMITENEGATIVO'=>'N',
		                        'COD_CANALCOM'=>'2',
		                        'CONFIRMACAO'=>'S',
		                        'COD_CAMPANHA'=>$cod_campanha,    
		                        'LOG_TESTE'=> 'N',
		                        'DAT_CADASTR'=> date('Y-m-d H:i:s'),
		                        'CONNADM'=>$connAdm->connAdm()
		                        ); 

		    $retornoDeb=FnDebitos($arraydebitos);

		    if($retornoDeb['cod_msg'] == 1){

		    	include "autenticaNexux.php";
				// retorna: $usuario, $senha, $cliente_externo e $parc_cadastrado(0/1)

				if($parc_cadastrado == 0){
					fnEscreve("Parceiro não cadastrado na empresa");
				}

				$proxLote = 2;
				$loteAtual = 1;
				include "_system/func_nexux/func_nexux.php";

				while($row = mysqli_fetch_assoc($arrayQuery)){

					$linha = "";

					for ($i=0; $i < count($tags) ; $i++) {
		// 				// fnEscreve($tags[$i]);
						switch($tags[$i]){

							case '<#NOME>';
								$nome = explode(' ', $row['NOM_CLIENTE']);
								$itemLinha = ucfirst(strtolower($nome[0]));
							break;
							case '<#CARTAO>';
								$itemLinha = "DES_EMAILUS AS '<#EMAIL>',";
							break;
							case '<#ESTADOCIVIL>';
								$itemLinha = "DES_EMAILUS AS '<#EMAIL>',";
							break;
							case '<#SEXO>';
								$itemLinha = "DES_EMAILUS AS '<#EMAIL>',";
							break;
							case '<#PROFISSAO>';
								$itemLinha = "DES_EMAILUS AS '<#EMAIL>',";
							break;
							case '<#NASCIMENTO>';
								$itemLinha = $row['DAT_NASCIME'];
							break;
							case '<#ENDERECO>';
								$itemLinha = "DES_EMAILUS AS '<#EMAIL>',";
							break;
							case '<#NUMERO>';
								$itemLinha = "DES_EMAILUS AS '<#EMAIL>',";
							break;
							case '<#BAIRRO>';
								$itemLinha = "DES_EMAILUS AS '<#EMAIL>',";
							break;
							case '<#CIDADE>';
								$itemLinha = "DES_EMAILUS AS '<#EMAIL>',";
							break;
							case '<#ESTADO>';
								$itemLinha = "DES_EMAILUS AS '<#EMAIL>',";
							break;
							case '<#CEP>';
								$itemLinha = "DES_EMAILUS AS '<#EMAIL>',";
							break;
							case '<#COMPLEMENTO>';
								$itemLinha = "DES_EMAILUS AS '<#EMAIL>',";
							break;
							case '<#TELEFONE>';
								$itemLinha = "DES_EMAILUS AS '<#EMAIL>',";
							break;
							case '<#CELULAR>';
								$itemLinha = "DES_EMAILUS AS '<#EMAIL>',";
							break;
							case '<#SALDO>';
								$itemLinha = fnValor($row['CREDITO_DISPONIVEL'],$casasDec);
							break;
							case '<#PRIMEIRACOMPRA>';
								$itemLinha = "DES_EMAILUS AS '<#EMAIL>',";
							break;
							case '<#ULTIMACOMPRA>';
								$itemLinha = "DES_EMAILUS AS '<#EMAIL>',";
							break;
							case '<#TOTALCOMPRAS>';
								$itemLinha = "DES_EMAILUS AS '<#EMAIL>',";
							break;
							case '<#CODIGO>';
								$itemLinha = $row['COD_CLIENTE'];
							break;
							case '<#CUPOMSORTEIO>';
								$itemLinha = "DES_EMAILUS AS '<#EMAIL>',";
							break;
							case '<#CUPOM_INDICACAO>';
								$itemLinha = "DES_EMAILUS AS '<#EMAIL>',";
							break;
							case '<#NUMEROLOJA>';
								$itemLinha = "DES_EMAILUS AS '<#EMAIL>',";
							break;
							case '<#BAIRROLOJA>';
								$itemLinha = "DES_EMAILUS AS '<#EMAIL>',";
							break;
							case '<#NOMELOJA>';
								$NOM_ARRAY_UNIDADE=(array_search($row['COD_UNIVEND'], array_column($ARRAY_UNIDADE, 'COD_UNIVEND')));
								$itemLinha = fnAcentos($ARRAY_UNIDADE[$NOM_ARRAY_UNIDADE]['nom_fantasi']);
							break;
							case '<#ENDERECOLOJA>';
								$itemLinha = "DES_EMAILUS AS '<#EMAIL>',";
							break;
							case '<#TELEFONELOJA>';
								$itemLinha = "DES_EMAILUS AS '<#EMAIL>',";
							break;
							case '<#ANIVERSARIO>';
								$itemLinha = substr($row['DAT_NASCIME'], 0,-5);
							break;
							case '<#DATAEXPIRA>';
								$itemLinha = fnDataShort($row['DAT_EXPIRA']);
							break;
							default:
								$itemLinha = "";
							break;
							
						}
						$linha .= $itemLinha.";";
					}

		// 			// fnEscreve($linha);

					$newRow[] = rtrim($linha,';');
					$linhas++;

					$NOM_CLIENTE=explode(" ", ucfirst(strtolower(fnAcentos($row['NOM_CLIENTE']))));                                
					$TEXTOENVIO=str_replace('<#NOME>', $NOM_CLIENTE[0], $qrMsg['HTML']);
					$TEXTOENVIO=str_replace('<#SALDO>', fnValor($row['CREDITO_DISPONIVEL'],$casasDec), $TEXTOENVIO);
					$TEXTOENVIO=str_replace('<#NOMELOJA>',  $row['NOM_FANTASI'], $TEXTOENVIO);
					$TEXTOENVIO=str_replace('<#ANIVERSARIO>', $row['DAT_NASCIME'], $TEXTOENVIO); 
					$TEXTOENVIO=str_replace('<#DATAEXPIRA>', fnDataShort($row['DAT_EXPIRA']), $TEXTOENVIO); 
					$TEXTOENVIO=str_replace('<#EMAIL>', $row['DES_EMAILUS'], $TEXTOENVIO); 
					$msgsbtr=nl2br($TEXTOENVIO,true);                                
					$msgsbtr= str_replace('<br />',' \n ', $msgsbtr);
					$msgsbtr = str_replace(array("\r", "\n"), '', $msgsbtr);
					// $msgsbtr = $TEXTOENVIO;

					// fnEscreve($proxLote);
					// fnEscreve($row['COD_LOTE']);

					$num_celular = fnLimpaDoc($row['NUM_CELULAR']);

					if(substr($num_celular, 0, 1) == "0"){
			  			$num_celular = substr($num_celular, 1);
			  		}

			  		$num_celular = "55".$num_celular;

					if (strlen($num_celular) == 12) {
                        $inicio = substr($num_celular, 0, 4);
                        $fim =  substr($num_celular, 4, 10);
                        $num_celular = $inicio.'9'.$fim;
                    }

					$dataAtual = date("Y-m-d H:i:s");

					if($row['DAT_AGENDAMENTO'] < $dataAtual){
						$agendamento = date("Y-m-d H:i:s", strtotime($dataAtual."+5 minutes"));
					}else{
						$agendamento = $row['DAT_AGENDAMENTO'];
					}


					if($proxLote == $row['COD_LOTE']){

						$mensagensContatos = rtrim($mensagensContatos,',');

						$sqlCont = "SELECT NUM_CONTADOR FROM contador WHERE NUM_TKT = 50";
						$arrayCont = mysqli_query(connTemp($cod_empresa,''),$sqlCont);
						$qrCont = mysqli_fetch_assoc($arrayCont);
						$contador = $qrCont['NUM_CONTADOR'];

						$retornoEnvio = EnvioSms($usuario,
								                 $senha,
												 $des_campanha,
												 $cod_empresa.','.$cod_campanha.','.$qrMsg['COD_TEMPLATE'].",".$contador,
												 $cliente_externo,
												 "[".$mensagensContatos."]");

						// fnEscreve("proxLote igual rowCOD_LOTE");
						// echo "<pre>";
						// print_r($retornoEnvio);
						// echo "</pre>";

						if($retornoEnvio['infomacoes'][0] == "Lote adicionado a fila"){

					    	$sqlControle = "UPDATE SMS_LOTE SET
											LOG_ENVIO = 'S',
											COD_DISPARO_EXT = $contador
											WHERE COD_CAMPANHA = $cod_campanha
											AND COD_EMPRESA = $cod_empresa
											AND COD_LOTE = ($proxLote-1)
											AND LOG_ENVIO = 'P';

											UPDATE CONTADOR SET NUM_CONTADOR = (NUM_CONTADOR+1)
											WHERE NUM_TKT = 50;";
							// fnEscreve($sqlControle);

							mysqli_multi_query(connTemp($cod_empresa,''),$sqlControle);

					    }

					    $retornoEnvio = "";
						$mensagensContatos = "";
						$proxLote = $row['COD_LOTE']+1;

						$mensagensContatos .= '{"numero": "'.$num_celular.'",
												"mensagem": "'.$msgsbtr.'",
												"serial": "'.$row['COD_CLIENTE'].'",
												"data_agendamento": "'.$agendamento.'"
												},';

					}else{

						// fnEscreve("else proxLote igual rowCOD_LOTE");

						$mensagensContatos .= '{"numero": "'.$num_celular.'",
												"mensagem": "'.$msgsbtr.'",
												"serial": "'.$row['COD_CLIENTE'].'",
												"data_agendamento": "'.$agendamento.'"
												},';

						$loteAtual = $row['COD_LOTE'];

					}

					$sqlCont = "SELECT NUM_CONTADOR FROM contador WHERE NUM_TKT = 50";
					$arrayCont = mysqli_query(connTemp($cod_empresa,''),$sqlCont);
					$qrCont = mysqli_fetch_assoc($arrayCont);
					$contador = $qrCont['NUM_CONTADOR'];

					$sqlInsertRel .= "INSERT INTO SMS_LISTA_RET(
											COD_EMPRESA,
											COD_CAMPANHA,
											COD_CLIENTE,
											NOM_CLIENTE, 
											COD_SEXOPES,
											DES_EMAILUS,
											COD_UNIVEND,
											NUM_CELULAR,
											LOG_EMAIL,
											DAT_NASCIME,
											STATUS_ENVIO,
											ID_DISPARO,
											DES_MSG_ENVIADA										
										) SELECT $cod_empresa,
												 $cod_campanha,
												 $row[COD_CLIENTE],
												 CL.NOM_CLIENTE,
												 CL.COD_SEXOPES,
												 CL.DES_EMAILUS,
												 CL.COD_UNIVEND,
												 '".$num_celular."',
												 CL.LOG_SMS,
												 CL.DAT_NASCIME,
												 'S',
												 $contador,
												 '$msgsbtr'
										  FROM CLIENTES CL
										  WHERE CL.COD_CLIENTE = $row[COD_CLIENTE]
										  AND CL.COD_EMPRESA = $cod_empresa; ";

				}

				// || ($proxLote < $loteAtual) 

				if($proxLote != 2 || $loteAtual == 1){

						$mensagensContatos = rtrim($mensagensContatos,',');

						$retornoEnvio = EnvioSms($usuario,
								                 $senha,
												 $des_campanha,
												 $cod_empresa.','.$cod_campanha.','.$qrMsg['COD_TEMPLATE'].",".$contador,
												 $cliente_externo,
												 "[".$mensagensContatos."]");

					// fnEscreve("proxLote diff 2 OU loteAtual igual 1");

					// echo "<pre>";
					// print_r($retornoEnvio);
					// echo "</pre>";

					// echo "<pre>";
					// print_r($mensagensContatos);
					// echo "</pre>";

					

					// fnEscreve($mensagensContatos);

					if($retornoEnvio['infomacoes'][0] == "Lote adicionado a fila"){

						$sqlGrupoControle = "SELECT $selectCliente FROM sms_lista SLS
										INNER JOIN CLIENTES C ON C.COD_CLIENTE = SLS.COD_CLIENTE
										WHERE SLS.COD_CAMPANHA = $cod_campanha
										AND SLS.LOG_COMPARA = 1
										";

						// fnEscreve($sqlGrupoControle);
						$arrayGrupo = mysqli_query(connTemp($cod_empresa,''),$sqlGrupoControle);

						if(mysqli_num_rows($arrayGrupo) > 0){

							while($qrGrupo = mysqli_fetch_assoc($arrayGrupo)){

								$sqlInsertCtrl .= "INSERT INTO LISTA_CONTROLE_CLIENTE(
														COD_EMPRESA,
														COD_CAMPANHA,
														COD_CLIENTE,
														ID_DISPARO,
														TIP_COMUNICA,
														DES_COMUNICA
													) VALUES( $cod_empresa,
															 $cod_campanha,
															 $qrGrupo[COD_CLIENTE],
															 $contador,
															 2,
															 'SMS'); ";

							}

						}

				    	$sqlControle = "UPDATE SMS_LOTE SET
										LOG_ENVIO = 'S',
										COD_DISPARO_EXT = $contador
										WHERE COD_CAMPANHA = $cod_campanha
										AND COD_EMPRESA = $cod_empresa
										AND COD_LOTE = ($proxLote-1)
										AND LOG_ENVIO = 'P';

										UPDATE CONTADOR SET NUM_CONTADOR = ($contador+1)
										WHERE NUM_TKT = 50;";
						// fnEscreve($sqlControle);

						mysqli_multi_query(connTemp($cod_empresa,''),$sqlControle);

				    }

				}

				// fnEscreve("passou lotado pela proxLote diff 2 OU loteAtual igual 1");
				// fnEscreve($proxLote);
				// fnEscreve($loteAtual);

				if($sqlInsertRel != "" && $retornoEnvio['infomacoes'][0] == "Lote adicionado a fila"){
					// fnescreve($sqlInsertRel);
					mysqli_multi_query(connTemp($cod_empresa,''),$sqlInsertRel);

					if($sqlInsertCtrl != ""){
						mysqli_multi_query(connTemp($cod_empresa,''),$sqlInsertCtrl);
					}

					$sqlUpdt2 = "UPDATE CAMPANHA SET 
								LOG_PROCESSA_SMS= 'S'
								WHERE COD_EMPRESA = $cod_empresa 
								AND COD_CAMPANHA = $cod_campanha";

					mysqli_query(connTemp($cod_empresa,''),$sqlUpdt2);

					// $sql = "UPDATE "

				}else{
					echo "Erro ao processar a campanha. Contate o Suporte.";
				}

		    }else if($retornoDeb['cod_msg'] == 5){

		    	$sqlSaldo = "INSERT INTO CONTROLE_SALDO(
											COD_EMPRESA,
											COD_TIPO,
											COD_CAMPANHA,
											QTD_TOTAL_ENVIO,
											VAL_TROCO
										) VALUES(
											$cod_empresa,
											1,
											$cod_campanha,
											$linhas,
											$retornoDeb[Diferenca]
										)";

				mysqli_query(connTemp($cod_empresa,''),$sqlSaldo);

				echo 'Saldo insuficiente para processar todos os lotes';

			}else if($retornoDeb['cod_msg'] == 2){
				echo 'Não foram gerados débitos';
			}		

		// 	sleep(5);

		}


	}else{

		echo "Necessária aprovação para o envio da lista";
		
	}


?> 