<?php

include '_system/_functionsMain.php';

if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$cod_campanha = "";
$cod_usucada = "";
$qrOk = "";
$log_ok = "";
$sqlGat = "";
$qrGat = "";
$velocidade_envio = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$tip_retorno = "";
$casasDec = "";
$dat_envio = "";
$ARRAY_UNIDADE1 = [];
$ARRAY_UNIDADE = [];
$qrCamp = "";
$des_campanha = "";
$connTemp = "";
$qrMsg = "";
$tagsPersonaliza = "";
$nomeRel = "";
$arquivo = "";
$caminhoRelat = "";
$tags = "";
$selectCliente = "";
$tagsDinamize = "";
$i = 0;
$sqlExt = "";
$qrExterno = "";
$array = [];
$linhas = "";
$sqlInsertRel = "";
$row = "";
$newRow = "";
$sql2 = "";
$arrayQuery2 = [];
$array2 = [];
$linhas2 = "";
$row2 = "";
$arraydebitos = [];
$retornoDeb = "";
$headers = "";
$headers1 = "";
$retornoContatos = "";
$cod_mailing_ext = "";
$sqlSeg = "";
$qrSeg = "";
$retornoListaSeg = "";
$retornoSegmento = "";
$cod_ext_segmento = "";
$retornoLista = "";
$hojemais5min = "";
$retornoEnvio = "";
$cod_disparo_ext = "";
$log_envio = "";
$sqlControle = "";
$sqlCamp = "";
$QTDUPDATE = "";
$msg = "";
$cod_msg = "";
$alterac = "";
$cod_alterac = "";
$DebSaldo = "";
$CredSaldo = "";
$saldoDiferenca = "";
$saldorestante = "";
$sqlSaldo = "";

$cod_empresa = fnLimpaCampoZero(fnDecode(@$_GET['id']));
$cod_campanha = fnLimpaCampoZero(fnDecode(@$_GET['idc']));
$cod_usucada = $_SESSION['SYS_COD_USUARIO'];
$opcao = fnLimpaCampo(@$_GET['opcao']);
$cod_empresa = fnDecode(@$_GET['id']);

switch ($opcao) {

	case 'envio':

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

				$sqlGat = "SELECT TIP_GATILHO, DAT_INI, HOR_INI FROM GATILHO_EMAIL WHERE COD_CAMPANHA = $cod_campanha AND COD_EMPRESA = $cod_empresa";
				$qrGat = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sqlGat));

				$velocidade_envio = fnLimpaCampoZero($_POST['DES_INTERVAL']);

				if($velocidade_envio == 0){
					$velocidade_envio = 2;
				}

				if($qrGat['TIP_GATILHO'] == 'individual'){

					$sql = "SELECT TIP_RETORNO FROM EMPRESAS WHERE COD_EMPRESA = $cod_empresa";
					//fnEscreve($sql);
					$arrayQuery = mysqli_query($connAdm->connAdm(),$sql);
					$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
					$tip_retorno = $qrBuscaEmpresa['TIP_RETORNO'];
					
					if($tip_retorno == 1){
						$casasDec = 0;
					}else{
						$casasDec = 2;
					}

					$dat_envio = $qrGat['DAT_INI']." ".$qrGat['HOR_INI'];

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

					$sql = "SELECT DES_CAMPANHA FROM CAMPANHA WHERE COD_EMPRESA = $cod_empresa AND COD_CAMPANHA = $cod_campanha";

					$qrCamp = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));

					$des_campanha = preg_replace('/\s+/', '_', fnAcentos($qrCamp['DES_CAMPANHA']));

					$des_campanha = str_replace('/', '.', $des_campanha);

					$connTemp = connTemp($cod_empresa, "");

					mysqli_query ($connTemp,"set character_set_client='utf8mb4'"); 
					mysqli_query ($connTemp,"set character_set_results='utf8mb4'");
					mysqli_query ($connTemp,"set collation_connection='utf8mb4_unicode_ci'");

					$sql = "SELECT CP.DAT_INI, 
								   CP.HOR_INI,
								   CP.COD_EXT_CAMPANHA, 
								   TE.COD_EXT_TEMPLATE,
								   TE.DES_ASSUNTO,
								   TE.DES_REMET,
								   TE.END_REMET,
								   TE.EMAIL_RESPOSTA,
								   TE.DES_ASSUNTO,
								   MDE.DES_TEMPLATE AS HTML
							FROM MENSAGEM_EMAIL ME
							LEFT JOIN TEMPLATE_EMAIL TE ON TE.COD_TEMPLATE = ME.COD_TEMPLATE_EMAIL
							LEFT JOIN CAMPANHA CP ON CP.COD_CAMPANHA = ME.COD_CAMPANHA
							INNER JOIN MODELO_EMAIL MDE ON MDE.COD_TEMPLATE = TE.COD_TEMPLATE
							WHERE ME.COD_EMPRESA = $cod_empresa 
							AND ME.COD_CAMPANHA = $cod_campanha
							AND ME.LOG_PRINCIPAL = 'S'
							ORDER BY 1 DESC LIMIT 1";

					// fnEscreve($sql);

					$qrMsg = mysqli_fetch_assoc(mysqli_query($connTemp,$sql));

					mysqli_close($connTemp);

					$tagsPersonaliza=procpalavrasV2($qrMsg['DES_ASSUNTO'].$qrMsg['HTML'],$connAdm->connAdm(),$cod_empresa);

					$nomeRel = $cod_empresa.'_'.date("YmdHis")."_".$des_campanha."_ENVIO.csv";
					$arquivo = '_system/func_dinamiza/lista_envio/'.$nomeRel;
					$caminhoRelat = '_system/func_dinamiza/lista_envio/';

					$tagsPersonaliza = '{{cmp1}},{{cmp2}},'.$tagsPersonaliza.",{{cmp3}}";

					$tagsPersonaliza = str_replace(',,', ',', $tagsPersonaliza);

					$tags = explode(',',$tagsPersonaliza);

					$selectCliente = "";
					$tagsDinamize = "";		

					for ($i=0; $i < count($tags) ; $i++) {

						$sqlExt = "SELECT VD.COD_EXTERNO, VR.KEY_BANCOVAR FROM VARIAVEIS_DINAMIZE VD 
								   INNER JOIN VARIAVEIS VR ON VR.COD_BANCOVAR = VD.COD_BANCOVAR
								   WHERE VD.COD_EMPRESA = $cod_empresa AND VD.DES_EXTERNO = '$tags[$i]'";

						$qrExterno = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(),$sqlExt));

						$tagsDinamize .= '{"Position":"'.$i.'", "Field":"'.$qrExterno[COD_EXTERNO].'", "Rule":"3"},';

						switch($qrExterno['KEY_BANCOVAR']){

							case '<#NOME>';
								$selectCliente .= "SUBSTRING_INDEX(SUBSTRING_INDEX(concat(Upper(SUBSTR(C.NOM_CLIENTE, 1,1)), lower(SUBSTR(C.NOM_CLIENTE, 2,LENGTH(C.NOM_CLIENTE)))), ' ', 1), ' ', -1) AS NOM_CLIENTE, ";
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

								$selectCliente .= "FORMAT(IFNULL((
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
															GROUP BY cred.cod_cliente),0),$casasDec,'pt_BR') AS CREDITO_DISPONIVEL, ";
							break;
							case '<#SALDOEXPIRA>';

								$selectCliente .= "(SELECT  
													FORMAT(TRUNCATE(IFNULL(SUM(B.VAL_SALDO),0),2),2,'pt_BR') VAL_SALDO
													FROM CREDITOSDEBITOS B
													WHERE 
													B.COD_EMPRESA=$cod_empresa AND
													DATE(B.DAT_EXPIRA) between NOW() AND DATE(DATE_ADD(NOW(),INTERVAL 30 DAY)) AND
														B.TIP_CREDITO='C' AND
													B.COD_STATUSCRED='1' 
													AND COD_CLIENTE=C.cod_CLIENTE) AS VAL_SALDOEXPIRA, ";

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
								$selectCliente .= "C.DES_EMAILUS,";
							break;
							
						}
						
					}

					$tagsDinamize = rtrim(trim($tagsDinamize),',');

					$selectCliente = rtrim(trim($selectCliente),',');
				                       
					$sql = "SELECT $selectCliente
							FROM clientes C 
							INNER JOIN EMAIL_LISTA EL ON EL.COD_CLIENTE = C.COD_CLIENTE
							WHERE EL.COD_EMPRESA = $cod_empresa
							AND EL.COD_CAMPANHA = $cod_campanha
							AND EL.LOG_COMPARA = 0";
							
					// fnEscreve($sql);

					$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);


					$array = array();
					$linhas = 0;
					$sqlInsertRel = "";
				         
					while($row = mysqli_fetch_assoc($arrayQuery)){

						$newRow[] = $row;

						$sqlInsertRel .= "INSERT INTO EMAIL_LISTA_RET(
												COD_EMPRESA,
												COD_CAMPANHA,
												COD_CLIENTE,
												ID_DISPARO										
											) VALUES (
												$cod_empresa,
												$cod_campanha,
												$row[COD_CLIENTE],
												{{idDisparo}}
											);";
						

						$linhas++;
					}

					$sql2 = "SELECT $selectCliente
							FROM clientes C 
							INNER JOIN EMAIL_LISTA EL ON EL.COD_CLIENTE = C.COD_CLIENTE
							WHERE EL.COD_EMPRESA = $cod_empresa
							AND EL.COD_CAMPANHA = $cod_campanha
							AND EL.LOG_COMPARA = 1";
							
					// fnEscreve($sql);

					$arrayQuery2 = mysqli_query(connTemp($cod_empresa,''),$sql2);


					$array2 = array();
					$linhas2 = 0;
				         
					while($row2 = mysqli_fetch_assoc($arrayQuery)){

						$newRow[] = $row2;

						$sqlInsertRel .= "INSERT INTO LISTA_CONTROLE_CLIENTE(
												COD_EMPRESA,
												COD_CAMPANHA,
												COD_CLIENTE,
												ID_DISPARO,
												TIP_COMUNICA,
												DES_COMUNICA
											) VALUES (
												$cod_empresa,
												$cod_campanha,
												$row2[COD_CLIENTE],
												{{idDisparo}},
												1,
												'EMAIL'
											);";

						$linhas2++;
					}

					$arraydebitos=array('quantidadeEmailenvio'=>$linhas,
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

				    // fnEscreve($retornoDeb['cod_msg']);

				    if($retornoDeb['cod_msg'] == 1){

				    	while($headers=mysqli_fetch_field($arrayQuery))
						{
						    $headers1[campos][]=$headers->name; 
						}

						include "_system/func_dinamiza/Function_dinamiza.php";
						include "autenticaDinamize.php";
						// retorna $_SESSION[COD_LISTA] E $_SESSION[AUTH_DINAMIZE]

						gerandorcvs($caminhoRelat, $nomeRel, ';', $newRow, $headers1);

						$retornoContatos = contatos_dinamiza ("$_SESSION[AUTH_DINAMIZE]",
									                        "$arquivo",
															"$tagsDinamize",
															$_SESSION[COD_LISTA]);

						// echo "<pre>";
						// print_r($retornoContatos);
						// fnEscreve($arquivo);
						// echo "</pre>";

						sleep(2);

						$cod_mailing_ext = $retornoContatos[body][code];

						if($cod_mailing_ext != ""){

							$sqlSeg = "SELECT COD_EXT_SEGMENTO FROM EMAIL_LOTE 
										WHERE COD_EMPRESA = $cod_empresa
										AND COD_CAMPANHA = $cod_campanha 
										AND LOG_TESTE = 'N'
										AND COD_EXT_SEGMENTO IS NOT NULL
										LIMIT 1";

							// fnEscreve($sqlSeg);

							$qrSeg = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sqlSeg));

							// if($_SESSION[SYS_COD_USUARIO] == 11478){

							// 	$qrSeg['COD_EXT_SEGMENTO'] = 16;
								
							// }


							if($qrSeg['COD_EXT_SEGMENTO'] != ""){

								// fnEscreve('updateSeg');

								$retornoListaSeg = ListaSegmento("$_SESSION[AUTH_DINAMIZE]", $qrSeg['COD_EXT_SEGMENTO'], $_SESSION[COD_LISTA]);

								if($retornoListaSeg[code_detail] == 'Sucesso'){

									$retornoSegmento = UpdateSegmento("$_SESSION[AUTH_DINAMIZE]", $cod_mailing_ext, $cod_empresa."_".$cod_campanha."_ENVIO_TESTE" , $qrSeg['COD_EXT_SEGMENTO'], $_SESSION[COD_LISTA]);
									$cod_ext_segmento = $qrSeg['COD_EXT_SEGMENTO'];

								}else{

									$retornoSegmento = FiltroSegmentos("$_SESSION[AUTH_DINAMIZE]", $cod_empresa."_".$cod_campanha."_ENVIO_TESTE" , $cod_mailing_ext, $_SESSION[COD_LISTA]);
									$cod_ext_segmento = $retornoSegmento[body][code];

								}

							}else{

								// fnEscreve('addSeg');

								$retornoLista = ListaSegmento2("$_SESSION[AUTH_DINAMIZE]",$cod_empresa."_".$cod_campanha."_".$des_campanha,$_SESSION[COD_LISTA]);

								$cod_ext_segmento = $retornoLista[body][items][0][code];

								if($cod_ext_segmento != ""){

									// fnEscreve('updateSegInAdd');

									$retornoSegmento = UpdateSegmento("$_SESSION[AUTH_DINAMIZE]", $cod_mailing_ext, $cod_empresa."_".$cod_campanha."_".$des_campanha , $cod_ext_segmento, $_SESSION[COD_LISTA]);

								}else{

									$retornoSegmento = FiltroSegmentos("$_SESSION[AUTH_DINAMIZE]", $cod_empresa."_".$cod_campanha."_".$des_campanha , $cod_mailing_ext, $_SESSION[COD_LISTA]);

									$cod_ext_segmento = $retornoSegmento[body][code];

								}

							}

							// echo'<pre>';

							// print_r($retornoLista);
							// // print_r($retornoSegmento);
							// echo'</pre>';

							// fnEscreve($_SESSION[AUTH_DINAMIZE]);
							// fnEscreve($cod_empresa."_".$cod_campanha."_".$des_campanha);
							// fnEscreve($cod_mailing_ext);
							// fnEscreve($cod_ext_segmento);
							// fnEscreve($_SESSION[COD_LISTA]);

							if($cod_ext_segmento != ""){

								sleep(5);

								$hojemais5min = date("Y-m-d H:i:s", strtotime("+ 5 minutes"));
								// $hojemais5min = date("Y-m-d H:i:s", strtotime($hojemais5min." + 1 hour"));

								if($dat_envio < $hojemais5min){

									$dat_envio = $hojemais5min;

								}

								$retornoEnvio = addenvio(
										$_SESSION[AUTH_DINAMIZE],
										$des_campanha,
						                $_SESSION[COD_LISTA],
										addslashes($qrMsg[DES_ASSUNTO]),
										addslashes($qrMsg[DES_REMET]),
										$qrMsg['END_REMET'],
										$qrMsg['EMAIL_RESPOSTA'],
										$qrMsg['COD_EXT_CAMPANHA'],
										$cod_ext_segmento,
										$qrMsg['COD_EXT_TEMPLATE'],
										$velocidade_envio,
										$dat_envio
								);

								// echo'<pre>';
								// // fnEscreve($dat_envio);
								// // fnEscreve($hojemais5min);
								// print_r($retornoEnvio);
								// echo'</pre>';


								$cod_disparo_ext = $retornoEnvio[body][code];
								// fnEscreve($cod_disparo_ext);

								if($cod_disparo_ext != ""){
									$log_envio = 'S';
								}else{
									$log_envio = 'N';
									$cod_disparo_ext = 0;
								}

								$sqlControle = "INSERT INTO EMAIL_LOTE(
																COD_CAMPANHA,
																COD_EMPRESA,
																COD_LOTE,
																QTD_LISTA,
																COD_EXT_SEGMENTO,
																COD_MAILING_EXT,
																COD_DISPARO_EXT,
																COD_EXT_TEMPLATE,
																NOM_ARQUIVO,
																DES_PATHARQ,
																LOG_ENVIO,
																DAT_AGENDAMENTO,
																COD_USUCADA
															) VALUES(
																$cod_campanha,
																$cod_empresa,
																0,
																'$linhas',
																'$cod_ext_segmento',
																'$cod_mailing_ext',
																'$cod_disparo_ext',
																'$qrMsg[COD_EXT_TEMPLATE]',
																'$nomeRel',
																'$arquivo',
																'$log_envio',
																'$dat_envio',
																$cod_usucada
															)";

								mysqli_query(connTemp($cod_empresa,''),$sqlControle);

								if($log_envio == 'S'){

									$sqlCamp = "UPDATE CAMPANHA SET 
												LOG_PROCESSA = 'S',
												DAT_PROCESSA = NOW()
												WHERE COD_EMPRESA = $cod_empresa 
												AND COD_CAMPANHA = $cod_campanha";

									mysqli_query(connTemp($cod_empresa,''),$sqlCamp);

									if($sqlInsertRel != ""){

										$sqlInsertRel = str_replace("{{idDisparo}}", $cod_disparo_ext, $sqlInsertRel);
										mysqli_multi_query(connTemp($cod_empresa,''),$sqlInsertRel);

									}								

								}

							}

						}
				    	
				    }else if($retornoDeb['cod_msg'] == 5){

					    	// return array('QTD_AFETADA'=>$QTDUPDATE,
	     //                 'MSG'=>$msg,
	     //                 'cod_msg'=>$cod_msg,
	     //                 'MSG_ATERACAO'=>$alterac,
	     //                 'cod_altera'=>$cod_alterac,
	     //                 'DEBT'=>$DebSaldo,
	     //                 'Cred'=>$CredSaldo,
	     //                 'Diferenca'=>$saldoDiferenca,
	     //                 'Saldorestante'=>abs($saldorestante));

						// CREATE TABLE `CONTROLE_SALDO` (
						// 	`COD_CONTROLE` INT NOT NULL AUTO_INCREMENT,
						// 	`COD_EMPRESA` INT NULL DEFAULT '0',
						// 	`COD_TIPO` INT NULL DEFAULT '0' COMMENT '1= SMS\r\n2= EMAIL',
						// 	`COD_CAMPANHA` INT NULL DEFAULT '0',
						// 	`COD_DISPARO` INT NULL DEFAULT '0',
						// 	`DAT_CADASTR` DATETIME NULL DEFAULT CURRENT_TIMESTAMP(),
						// 	`QTD_TOTAL_ENVIO` VARCHAR(10) NULL DEFAULT '0',
						// 	`VAL_TROCO` VARCHAR(10) NULL DEFAULT '0',
						// 	INDEX `COD_EMPRESA_COD_TIPO_COD_CAMPANHA_COD_DISPARO` (`COD_EMPRESA`, `COD_TIPO`, `COD_CAMPANHA`, `COD_DISPARO`),
						// 	PRIMARY KEY (`COD_CONTROLE`)
						// )
						// COLLATE='utf8_general_ci'
						// ;

				    	$sqlSaldo = "INSERT INTO CONTROLE_SALDO(
												COD_EMPRESA,
												COD_TIPO,
												COD_CAMPANHA,
												QTD_TOTAL_ENVIO,
												VAL_TROCO
											) VALUES(
												$cod_empresa,
												2,
												$cod_campanha,
												$linhas,
												$retornoDeb[Diferenca]
											)";

						mysqli_query(connTemp($cod_empresa,''),$sqlSaldo);

						echo "Saldo insuficiente para processar todos os lotes";

						
					}

					

				}else{

					$sqlCamp = "UPDATE CAMPANHA SET 
								LOG_PROCESSA = 'S',
								VELOCIDADE_ENVIO = $velocidade_envio,
								DAT_PROCESSA = NOW()
								WHERE COD_EMPRESA = $cod_empresa 
								AND COD_CAMPANHA = $cod_campanha";

					// fnEscreve($sqlCamp);

					mysqli_query(connTemp($cod_empresa,''),$sqlCamp);

				}

			}else{

				echo "Necessária aprovação para o envio da lista";

			}
			
		break;
		
		default:
			
		break;

	}

?> 