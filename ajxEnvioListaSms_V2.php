<?php 

	include '_system/_functionsMain.php';
	include './_system/func_nexux/func_transacional.php';

	$agendamento = "";

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

	$sqlProcessa = "SELECT LOG_PROCESSA_SMS FROM CAMPANHA 
				WHERE COD_EMPRESA = $cod_empresa 
				AND COD_CAMPANHA = $cod_campanha";

	$arrayProc = mysqli_query(connTemp($cod_empresa,''),$sqlProcessa);

	$qrProc = mysqli_fetch_assoc($arrayProc);

	if($qrProc[LOG_PROCESSA_SMS] == 'N'){

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
							LOG_PROCESSA_SMS = 'S',
							DAT_PROCESSA_SMS = NOW()
							WHERE COD_EMPRESA = $cod_empresa 
							AND COD_CAMPANHA = $cod_campanha";

				mysqli_query(connTemp($cod_empresa,''),$sqlUpdt2);

			}else{

				$sqlDec = "SELECT TIP_RETORNO, NUM_DECIMAIS_B FROM empresas where COD_EMPRESA = $cod_empresa";
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

				$sql = "SELECT CP.DES_CAMPANHA, 
						   CP.DAT_INI, 
						   CP.HOR_INI,
						   CP.COD_CAMPANHA, 
						   CP.COD_EMPRESA, 
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
				$qrMsg = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));


				$des_campanha = preg_replace('/\s+/', '_', fnAcentos($qrMsg['DES_CAMPANHA']));

				$des_campanha = str_replace('/', '.', $des_campanha);

				$tagsPersonaliza=procpalavras($qrMsg['HTML'],$connAdm->connAdm());

				$tagsPersonaliza = '<#EMAIL>,<#CODIGO>,'.$tagsPersonaliza;

				$tags = explode(',',$tagsPersonaliza);

				$selectCliente = "";	

				for ($i=0; $i < count($tags) ; $i++) {

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
							// $selectCliente .= "(SELECT 
							// 					IFNULL((SELECT Sum(val_saldo) FROM creditosdebitos f 
							// 					WHERE f.cod_cliente = cred.cod_cliente AND 
							// 							f.tip_credito = 'C' AND 
							// 							f.cod_statuscred = 1 AND 
							// 							f.tip_campanha = cred.tip_campanha AND 
							// 							(( f.log_expira = 'S' AND DATE_FORMAT(f.dat_expira, '%Y-%m-%d') >= Date_format(Now(), '%Y-%m-%d') ) OR ( f.log_expira = 'N' ) )),0)+
							// 					IFNULL((SELECT Sum(val_saldo) FROM creditosdebitos_bkp g
							// 					WHERE g.cod_cliente = cred.cod_cliente AND 
							// 							g.tip_credito = 'C' AND 
							// 							g.cod_statuscred = 1 AND 
							// 							g.tip_campanha = cred.tip_campanha AND 
							// 							((g.log_expira = 'S' AND DATE_FORMAT(g.dat_expira, '%Y-%m-%d') >= Date_format(Now(), '%Y-%m-%d') ) OR (g.log_expira = 'N' ) )),0) AS CREDITO_DISPONIVEL
							// 							FROM creditosdebitos cred 
							// 							WHERE cred.cod_cliente=C.cod_CLIENTE
							// 							GROUP BY cred.cod_cliente ) AS CREDITO_DISPONIVEL,";

							$selectCliente .= "FORMAT(TRUNCATE(IFNULL((
												SELECT IFNULL((
												SELECT  SUM(val_saldo)
												FROM creditosdebitos f
												WHERE f.cod_cliente = cred.cod_cliente AND 
												f.tip_credito = 'C' AND 
												f.cod_statuscred = 1 AND 
												f.tip_campanha = cred.tip_campanha AND 
												((f.log_expira = 'S' AND DATE_FORMAT(f.dat_expira, '%Y-%m-%d') >= DATE_FORMAT(NOW(), '%Y-%m-%d')) OR (f.log_expira = 'N'))),0)+ IFNULL((
												SELECT SUM(val_saldo)
												FROM creditosdebitos_bkp g
												WHERE g.cod_cliente = cred.cod_cliente AND g.tip_credito = 'C' AND g.cod_statuscred = 1 AND g.tip_campanha = cred.tip_campanha AND ((g.log_expira = 'S' AND DATE_FORMAT(g.dat_expira, '%Y-%m-%d') >= DATE_FORMAT(NOW(), '%Y-%m-%d')) OR (g.log_expira = 'N'))),0)
												FROM creditosdebitos cred
												WHERE cred.cod_cliente=C.cod_CLIENTE
												GROUP BY cred.cod_cliente),0),$casasDec),$casasDec,'pt_BR') AS CREDITO_DISPONIVEL, ";
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
							$selectCliente .= "C.NUM_CELULAR,C.NUM_CGCECPF,C.NOM_CLIENTE,C.DAT_NASCIME,C.COD_SEXOPES,C.COD_UNIVEND,";
						break;
						
					}
					
				}

				$sqlNroLote = "SELECT COUNT(DISTINCT SLS.COD_LOTE) QTD_LOTE, 
									  COUNT(DISTINCT SLS.COD_CLIENTE) QTD_CONTATO FROM SMS_LISTA SLS
							   WHERE SLS.COD_CAMPANHA = $cod_campanha";
				$arrayLote = mysqli_query(connTemp($cod_empresa,''),$sqlNroLote);

				$qrLote = mysqli_fetch_assoc($arrayLote);

				$qtd_lotes = $qrLote[QTD_LOTE];
				$qtd_contatos = $qrLote[QTD_CONTATO];

		    	// $otp='desativado';
		    	include "autenticaNexux.php";
				// retorna: $usuario, $senha, $cliente_externo, $cod_parcomu_auth e $parc_cadastrado(0/1)
				if($parc_cadastrado == 0){
					fnEscreve("Parceiro não cadastrado na empresa");
				}

				if($cod_parcomu_auth != 17){
					$cod_parcomu_auth == 22;
					$saldoNexux = 9999999999;
				}else{	
					// VERIFICAÇÃO DE SALDO NEXUX ----------------------------------
					$saldoNexux = SaldoNexux($senha);
				}

				// VERIFICAÇÃO DE SALDO MARKA ----------------------------------
				// $arraydebitos=array('quantidadeEmailenvio'=>$qtd_contatos,
			    //                     'COD_EMPRESA'=>$cod_empresa,
			    //                     'PERMITENEGATIVO'=>'N',
			    //                     'COD_CANALCOM'=>'2',
			    //                     'CONFIRMACAO'=>'S',
			    //                     'COD_CAMPANHA'=>$cod_campanha,    
			    //                     'LOG_TESTE'=> 'N',
			    //                     'DAT_CADASTR'=> date('Y-m-d H:i:s'),
			    //                     'CONNADM'=>$connAdm->connAdm()
			    //                     ); 

			    // $retornoDeb=FnDebitos($arraydebitos);

			    // if($retornoDeb['cod_msg'] == 1 && $saldoNexux >= $qtd_contatos){

			    	$sqlInsertRel = "";
					$sqlInsertCtrl = "";

					// LOOPING DOS LOTES ----------------------------------------------------
			    	for ($lote=1; $lote <= $qtd_lotes; $lote++) {
			    		
			    		$sqlEnvio = "SELECT $selectCliente SLT.COD_LOTE, SLT.DAT_AGENDAMENTO, MAX(SLT.COD_LOTE) AS TOT_LOTE FROM sms_lista SLS
									 INNER JOIN sms_lote SLT ON SLT.COD_LOTE = SLS.COD_LOTE AND SLT.COD_CAMPANHA = $cod_campanha
									 INNER JOIN CLIENTES C ON C.COD_CLIENTE = SLS.COD_CLIENTE
									 WHERE SLS.COD_CAMPANHA = $cod_campanha
									 AND SLT.LOG_ENVIO = 'P'
									 AND SLS.LOG_COMPARA = 0
									 AND SLT.COD_LOTE = $lote
									 GROUP BY SLS.COD_CLIENTE
									 ORDER BY SLT.COD_LOTE";

						// fnEscreve($sqlEnvio);
						// exit();
						$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sqlEnvio);

						// LOOPING DOS CLIENTES DO LOTE ----------------------------------------------------
						while($row = mysqli_fetch_assoc($arrayQuery)){

							$linha = "";
							// LOOPING DAS VARIAVEIS -------------------------------------------------------
							for ($i=0; $i < count($tags) ; $i++) {

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
										$itemLinha = $row['CREDITO_DISPONIVEL'];
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

							// MONTAGEM DA MENSAGEM POR CLIENTE ------------------------------------------------------------
							$newRow[] = rtrim($linha,';');
							$linhas++;

							$NOM_CLIENTE=explode(" ", ucfirst(strtolower(fnAcentos($row['NOM_CLIENTE']))));                                
							$TEXTOENVIO=str_replace('<#NOME>', $NOM_CLIENTE[0], $qrMsg['HTML']);
							$TEXTOENVIO=str_replace('<#SALDO>', $row['CREDITO_DISPONIVEL'], $TEXTOENVIO);
							$TEXTOENVIO=str_replace('<#NOMELOJA>',  $row['NOM_FANTASI'], $TEXTOENVIO);
							$TEXTOENVIO=str_replace('<#ANIVERSARIO>', $row['DAT_NASCIME'], $TEXTOENVIO); 
							$TEXTOENVIO=str_replace('<#DATAEXPIRA>', fnDataShort($row['DAT_EXPIRA']), $TEXTOENVIO); 
							$TEXTOENVIO=str_replace('<#EMAIL>', $row['DES_EMAILUS'], $TEXTOENVIO); 
							$msgsbtr=nl2br($TEXTOENVIO,true);                                
							$msgsbtr= str_replace('<br />',' \n ', $msgsbtr);
							$msgsbtr = str_replace(array("\r", "\n"), '', $msgsbtr);

							$nom_camp_msg=$qrMsg[COD_CAMPANHA].'||'.$qrMsg[COD_EMPRESA].'||'.$row[COD_CLIENTE].'||'.$qrMsg[COD_TEMPLATE];
							$num_celular = fnLimpaDoc($row['NUM_CELULAR']);

							// CORREÇÃO DOS NUMEROS DE CELULAR ------------------------------------------------------------
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

							$agendamento = $row['DAT_AGENDAMENTO'];
							
							if($dataAtual > $agendamento){
								$agendamento = date("Y-m-d H:i:s", strtotime($dataAtual));
							}


							// MONTAGEM DOS ARRAYS DE CONTATOS PARA ENVIO ------------------------------------------------------------
							if($cod_parcomu_auth == 17){

								$CLIE_SMS_L[]=array("numero"=> fnLimpaDoc($row['NUM_CELULAR']),
						                            "mensagem"=>$msgsbtr,                   
						                            "DataAgendamento"=> "$agendamento",
						                            "Codigo_cliente"=>"$nom_camp_msg",
						                            "codCliente"=>$row['COD_CLIENTE'],
							                        "cpf"=>$row['NUM_CGCECPF'],
							                        "nome"=>addslashes(fnAcentos($row['NOM_CLIENTE'])),
							                        "nasc"=>$row['DAT_NASCIME'],
							                        "unidade"=>$row['COD_UNIVEND'],
							                        "email"=>$row['DES_EMAILUS'],
							                        "sexo"=>$row['COD_SEXOPES'],
							                        "numCelular"=>fnLimpaDoc($row['NUM_CELULAR'])
						                             );

							}else{

								$CLIE_SMS_L[]=array("from"=>$cliente_externo,
							                        "to" =>'+55'.fnLimpaDoc($row['NUM_CELULAR']), 
							                        "mensagem"=>$msgsbtr,                   
						                            "DataAgendamento"=> "$agendamento",
							                        "Codigointerno"=> base64_encode($nom_camp_msg),
							                        "codCliente"=>$row['COD_CLIENTE'],
							                        "cpf"=>$row['NUM_CGCECPF'],
							                        "nome"=>addslashes(fnAcentos($row['NOM_CLIENTE'])),
							                        "nasc"=>$row['DAT_NASCIME'],
							                        "unidade"=>$row['COD_UNIVEND'],
							                        "email"=>$row['DES_EMAILUS'],
							                        "sexo"=>$row['COD_SEXOPES'],
							                        "numCelular"=>fnLimpaDoc($row['NUM_CELULAR'])
							                       );  

							}

						}

						// MONTANDO UMA PLANILHA DOS CONTATOS DO ENVIO ----------------------------------------------------------------

						fngravacvs($newRow,$caminhoRelat,$nomeRel);

						$mensagensContatos = rtrim($mensagensContatos,',');

				       	// ENVIO -------------------------------------------------------------------------------------------------------------------------

						// if($cod_parcomu_auth == 17){

					    //     $testefast=EnvioSms_fast($senha,$des_campanha,json_encode($CLIE_SMS_L),'short');

					    //     $cod_erro_nexux=$testefast[Resultado][CodigoResultado];

					    //     $msgenvio=$testefast[Resultado][Mensagem];
					    //     $jsonputo=json_encode($testefast);

					    // }else{

					    // 	$base64= base64_encode($usuario.':'.$senha);
						//     $responsetwilo=sms_twilo($base64,$CLIE_SMS_L,$usuario,$senha);

						//     // echo "<pre>";
						//     // print_r($responsetwilo);
						//     // echo "</pre>";
						//     // exit();
						//     $cod_erro_nexux='0';

					    // }

					    // AUDITORIA DO ENVIO -------------------------------------------------------------------------------------------------------------------------
						// $cod_erro_nexux='0';

				        // if($cod_erro_nexux=='0' && $cod_parcomu_auth == 17){

				       	// 	$msgErro = "";
				        //     // $CHAVE_GERAL=$testefast[Resultado][Chave];
				        //     // $CHAVE_CLIENTE=$testefast[Mensagens][0][UniqueID];

				        //     foreach ($testefast[Mensagens] as $key => $cliente) {

						// 		$info = explode("||", $cliente[Codigo_cliente]);

						// 		$cod_cliente = $info[2];
						// 		$celular = substr($cliente[numero], 3);
						// 		$idDisparo = date('Ymd');
						// 		$TEXTOENVIO = $cliente[mensagem];
						// 		// $CHAVE_CLIENTE = $cliente[UniqueID];
				            	
				        //     	// $insertListaRet .= "('".$cod_empresa."',
						//         //                      '".$cod_campanha."',       
						//         //                      (SELECT NOM_CLIENTE FROM CLIENTES WHERE COD_EMPRESA = ".$cod_empresa." AND COD_CLIENTE = ".$cod_cliente."),
						//         //                      (SELECT COD_UNIVEND FROM CLIENTES WHERE COD_EMPRESA = ".$cod_empresa." AND COD_CLIENTE = ".$cod_cliente."),
						//         //                      '".$cod_cliente."',
						//         //                      '".$celular."',
						//         //                      'S',
						//         //                      '".$idDisparo."',
						//         //                      '".$TEXTOENVIO."',
						//         //                      '".$CHAVE_GERAL."',
						//         //                      '".$CHAVE_CLIENTE."',
						//         //                      NOW(),
						//         //                      'N',
						//         //                      '17',
						//         //                      '".$msgenvio."'    
						//         //                     ),";

						// 		$insertListaRet .= "('".$row['COD_EMPRESA']."', 
	                    //                              '9999', 
	                    //                              '".$cod_cliente."', 
	                    //                              '".$cliente['cpf']."', 
	                    //                              '".addslashes(fnAcentos($cliente['nome']))."', 
	                    //                              '".$cliente['nasc']."', 
	                    //                              '".trim($cliente['email'])."',
	                    //                              '".$cliente['numCelular']."',    
	                    //                              '".$cliente['sexo']."', 
	                    //                              '".$cod_campanha."', 
	                    //                              '99',
	                    //                              '14',
	                    //                              'individual',
	                    //                              '".date("W", strtotime("-2 day",strtotime(date('Y-m-d H:i:s'))))."',
	                    //                              '99',
	                    //                              '".date('m')."',
	                    //                              '".$cliente['DataAgendamento']."'  
	                    //                              ),";

				        //     }

				        // }else if($cod_erro_nexux == '0' && $cod_parcomu_auth != 17){

				        // 	$CHAVE_GERAL=$responsetwilo[0]['account_sid'];
						//     $CHAVE_CLIENTE=$responsetwilo[0]['sid'];
						//     $idDisparo = date('Ymd');

						//     foreach ($CLIE_SMS_L as $cliente) {

						//     	$celular = $cliente[numCelular];
						// 	    $codCliente = $cliente[codCliente];
						// 	    $TEXTOENVIO = $cliente[mensagem];

						// 	    $insertListaRet .= "('".$row['COD_EMPRESA']."', 
	                    //                              '9999', 
	                    //                              '".$cod_cliente."', 
	                    //                              '".$cliente['cpf']."', 
	                    //                              '".addslashes(fnAcentos($cliente['nome']))."', 
	                    //                              '".$cliente['nasc']."', 
	                    //                              '".trim($cliente['email'])."',
	                    //                              '".$cliente['numCelular']."',    
	                    //                              '".$cliente['sexo']."', 
	                    //                              '".$cod_campanha."', 
	                    //                              '99',
	                    //                              '14',
	                    //                              'individual',
	                    //                              '".date("W", strtotime("-2 day",strtotime(date('Y-m-d H:i:s'))))."',
	                    //                              '99',
	                    //                              '".date('m')."',
	                    //                              '".$cliente['DataAgendamento']."'  
	                    //                              ),";

						// 	}
							
				        // }else{
				       	// 	$msgErro = $msgenvio;
				        // }

				        foreach ($CLIE_SMS_L as $cliente) {

					    	$celular = $cliente[numCelular];
						    $codCliente = $cliente[codCliente];
						    $TEXTOENVIO = $cliente[mensagem];

						    if(!$cliente['unidade'] || $cliente['unidade'] == ""){
						    	$cliente['unidade'] = 0;
						    }

						    $insertListaRet .= "('".$cod_empresa."', 
                                                 '".$cliente['unidade']."', 
                                                 '".$cliente['codCliente']."', 
                                                 '".$cliente['cpf']."', 
                                                 '".addslashes(fnAcentos($cliente['nome']))."', 
                                                 '".$cliente['nasc']."', 
                                                 '".trim($cliente['email'])."',
                                                 '".$cliente['numCelular']."',    
                                                 '".$cliente['sexo']."', 
                                                 '".$cod_campanha."', 
                                                 '99',
                                                 '14',
                                                 'individual',
                                                 '".date("W", strtotime("-2 day",strtotime(date('Y-m-d H:i:s'))))."',
                                                 '99',
                                                 '".date('m')."',
                                                 '".$cliente['DataAgendamento']."'  
                                                 ),";

						}

						$insertListaRet = rtrim($insertListaRet,',');

				    	$sqlControle = "INSERT INTO email_fila ( COD_EMPRESA, 
                                                                   COD_UNIVEND, 
                                                                   COD_CLIENTE, 
                                                                   NUM_CGCECPF,
                                                                   NOM_CLIENTE, 
                                                                   DT_NASCIME, 
                                                                   DES_EMAILUS,
                                                                   NUM_CELULAR,
                                                                   COD_SEXOPES, 
                                                                   COD_CAMPANHA,
                                                                   TIP_MOMENTO,
                                                                   TIP_FILA,
                                                                   TIP_GATILHO,
                                                                   SEMANA,
                                                                   TIP_CONTROLE,
                                                                   MES,
                                                                   DT_CADASTR
                                                                   ) VALUES $insertListaRet;

				    					UPDATE SMS_LOTE SET
										LOG_ENVIO = 'A'
										WHERE COD_CAMPANHA = $cod_campanha
										AND COD_EMPRESA = $cod_empresa
										AND COD_LOTE = $lote
										AND LOG_ENVIO = 'P'";
						// fnEscreve($sqlControle);
						// exit();

						mysqli_multi_query(connTemp($cod_empresa,''),$sqlControle);

					    unset($CLIE_SMS_L);
					    $insertListaRet = "";
						$CLIE_SMS_L = array();

			    	}


					// $sqlUneLotes = "DELETE FROM SMS_LOTE
					// 				WHERE COD_CAMPANHA = $cod_campanha
					// 				AND COD_EMPRESA = $cod_empresa
					// 				AND LOG_ENVIO = 'A';

					// 				INSERT INTO SMS_LOTE(
					//  							COD_CAMPANHA,
					//  							COD_EMPRESA,
					//  							COD_PERSONAS,
					//  							COD_DISPARO_EXT,
					//  							COD_LOTE,
					//  							QTD_LISTA,
					//  							COD_LISTA,
					//  							NOM_ARQUIVO,
					//  							DES_PATHARQ,
					//  							LOG_TESTE,
					//  							LOG_ENVIO,
					//  							DAT_AGENDAMENTO,
					//  							COD_USUCADA
			 		// 				) VALUES(
					//  							$cod_campanha,
					//  							$cod_empresa,
					//  							'$qrMsg[COD_PERSONAS]',
					//  							".date('Ymd').",
					//  							1,
					//  							$linhas,
					//  							(SELECT MAX(COD_LISTA) FROM SMS_PARAMETROS WHERE COD_EMPRESA = $cod_empresa AND COD_CAMPANHA = $cod_campanha),
					//  							'INDIVIDUAL',
					//  							'INDIVIDUAL',
					//  							'N',
					//  							'S',
					//  							'$agendamento',
					//  							$cod_usucada
			 		// 				);

					// 				INSERT INTO lista_controle_cliente(COD_EMPRESA, COD_CAMPANHA, COD_CLIENTE, ID_DISPARO, TIP_COMUNICA, DES_COMUNICA)
					// 				SELECT $cod_empresa, $cod_campanha, COD_CLIENTE, ".date('Ymd').", 2, 'SMS' FROM sms_lista 
					// 				WHERE COD_EMPRESA = $cod_empresa AND COD_CAMPANHA = $cod_campanha AND log_compara = 1;

					// 				UPDATE CAMPANHA SET 
					// 				LOG_PROCESSA_SMS= 'S',
					// 				DAT_PROCESSA_SMS = NOW()
					// 				WHERE COD_EMPRESA = $cod_empresa 
					// 				AND COD_CAMPANHA = $cod_campanha;
					// ";

					$sqlUneLotes = "DELETE FROM SMS_LOTE
									WHERE COD_CAMPANHA = $cod_campanha
									AND COD_EMPRESA = $cod_empresa
									AND LOG_ENVIO = 'A';

									INSERT INTO lista_controle_cliente(COD_EMPRESA, COD_CAMPANHA, COD_CLIENTE, ID_DISPARO, TIP_COMUNICA, DES_COMUNICA)
									SELECT $cod_empresa, $cod_campanha, COD_CLIENTE, ".date('Ymd').", 2, 'SMS' FROM sms_lista 
									WHERE COD_EMPRESA = $cod_empresa AND COD_CAMPANHA = $cod_campanha AND log_compara = 1;

									UPDATE CAMPANHA SET 
									LOG_PROCESSA_SMS= 'S',
									DAT_PROCESSA_SMS = NOW()
									WHERE COD_EMPRESA = $cod_empresa 
									AND COD_CAMPANHA = $cod_campanha;
					";

					// fnEscreve($sqlUneLotes);

					mysqli_multi_query(connTemp($cod_empresa,''),$sqlUneLotes);

			    // }else if($retornoDeb['cod_msg'] == 5){

			    // 	$sqlSaldo = "INSERT INTO CONTROLE_SALDO(
				// 								COD_EMPRESA,
				// 								COD_TIPO,
				// 								COD_CAMPANHA,
				// 								QTD_TOTAL_ENVIO,
				// 								VAL_TROCO
				// 							) VALUES(
				// 								$cod_empresa,
				// 								1,
				// 								$cod_campanha,
				// 								$linhas,
				// 								$retornoDeb[Diferenca]
				// 							)";

				// 	mysqli_query(connTemp($cod_empresa,''),$sqlSaldo);

				// 	echo 'Saldo insuficiente para processar todos os lotes';

				// }else if($retornoDeb['cod_msg'] == 2){
				// 	echo 'Não foram gerados débitos';
				// }else{
				// 	echo 'Saldo Nexux insuficiente para processar todos os lotes';
				// }		

			// 	sleep(5);

			}


		}else{

			echo "Necessária aprovação para o envio da lista";
			
		}

	}else{

		echo "Campanha já processada";

	}


?> 