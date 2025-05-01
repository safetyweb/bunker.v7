<?php 

	include '_system/_functionsMain.php';
	include './_system/func_nexux/func_transacional.php';

	$agendamento = "";

	// echo "EM MANUTENÇÃO ATÉ 06/12/2021";
	// exit();

	$opcao = fnLimpaCampo($_GET['opcao']);
	$tipo = fnLimpaCampo($_GET['tipo']);
	$cod_empresa = fnLimpaCampoZero(fnDecode($_GET['id']));
	$cod_campanha = fnLimpaCampoZero(fnDecode($_GET['idc']));
	$cod_usucada = $_SESSION['SYS_COD_USUARIO'];

	$sql = "SELECT MAX(LOG_OK) AS OK FROM PUSH_CONTROLE 
			WHERE COD_EMPRESA = $cod_empresa 
			AND COD_CAMPANHA = $cod_campanha
			AND COD_LISTA = (
							 	SELECT MAX(COD_LISTA) FROM PUSH_PARAMETROS
							 	WHERE COD_EMPRESA = $cod_empresa 
							 	AND COD_CAMPANHA = $cod_campanha
							)";

	$qrOk = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));

	if($qrOk['OK'] == 'S'){
		$log_ok = 'S';
	}else{
		$log_ok = 'N';
	}

	$sqlProcessa = "SELECT LOG_PROCESSA_PUSH FROM CAMPANHA 
				WHERE COD_EMPRESA = $cod_empresa 
				AND COD_CAMPANHA = $cod_campanha";

	$arrayProc = mysqli_query(connTemp($cod_empresa,''),$sqlProcessa);

	$qrProc = mysqli_fetch_assoc($arrayProc);

	if($qrProc[LOG_PROCESSA_PUSH] == 'N'){

		if($log_ok == 'S'){

			$sqlGat = "SELECT COD_GATILHO, TIP_GATILHO FROM GATILHO_PUSH WHERE COD_CAMPANHA = $cod_campanha AND COD_EMPRESA = $cod_empresa";
			$qrGat = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sqlGat));

			$sql = "SELECT TE.COD_TEMPLATE
					FROM MENSAGEM_PUSH ME
					INNER JOIN TEMPLATE_PUSH TE ON TE.COD_TEMPLATE = ME.COD_TEMPLATE_PUSH
					WHERE ME.COD_EMPRESA = $cod_empresa 
					AND ME.COD_CAMPANHA = $cod_campanha";

			// fnEscreve($sql);

			$qrMsg = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));

			if($qrGat['TIP_GATILHO'] != 'individual' && $qrGat['TIP_GATILHO'] != 'individualB'){

				$sqlUpdt2 = "UPDATE CAMPANHA SET 
							LOG_PROCESSA_PUSH = 'S',
							DAT_PROCESSA_PUSH = NOW()
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

				// $sqlLote = "SELECT MAX(COD_LOTE) AS TOT_LOTE FROM PUSH_LOTE 
				// 			WHERE COD_CAMPANHA = $cod_campanha 
				// 			AND COD_EMPRESA = $cod_empresa";

				// $arrayLote = mysqli_query(connTemp($cod_empresa,''),$sqlLote);

				// $qrLote = mysqli_fetch_assoc($arrayLote);

				// $max_lote = $qrLote['TOT_LOTE'];

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
						INNER JOIN PUSH_PARAMETROS SPA ON SPA.COD_CAMPANHA = CP.COD_CAMPANHA
						INNER JOIN MENSAGEM_PUSH MS ON MS.COD_CAMPANHA = SPA.COD_CAMPANHA
						INNER JOIN TEMPLATE_PUSH TS ON TS.COD_TEMPLATE = MS.COD_TEMPLATE_PUSH
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
							$selectCliente .= "C.NUM_CELULAR,";
						break;
						
					}
					
				}

				// $selectCliente = rtrim($selectCliente,',');

				$logpushin="INSERT INTO push_fila_error()
                            SELECT * FROM email_fila
                                WHERE cod_empresa=$cod_empresa AND 
                                            cod_campanha = $cod_campanha and 
                                            ROW(COD_CLIENTE,COD_EMPRESA)  IN (SELECT COD_CLIENTE,COD_EMPRESA FROM cliente_push where cod_empresa=$cod_empresa) AND 
                                            dt_cadastr <= date_add(now(), INTERVAL -30 MINUTE) AND 
                                            COD_ENVIADO='N'
                             	ORDER BY ID_FILA DESC";
				mysqli_query(connTemp($cod_empresa,''), $logpushin);

				$sqlMaxLote = "SELECT MAX(SLT.COD_LOTE) AS TOT_LOTE FROM push_lote SLT
								WHERE SLT.COD_CAMPANHA = $cod_campanha
								AND SLT.LOG_ENVIO = 'P'";

				// fnEscreve($sqlEnvio);
				$arrayMaxLote = mysqli_query(connTemp($cod_empresa,''),$sqlMaxLote);

				$qrMax = mysqli_fetch_assoc($arrayMaxLote);

				for ($i=1; $i <= $qrMax[TOT_LOTE]; $i++) {

					$sqlEnvio = "SELECT C.*, SLT.COD_LOTE, SLT.DAT_AGENDAMENTO, MAX(SLT.COD_LOTE) AS TOT_LOTE FROM push_lista SLS
									INNER JOIN push_lote SLT ON SLT.COD_LOTE = SLS.COD_LOTE AND SLT.COD_CAMPANHA = $cod_campanha
									INNER JOIN CLIENTES C ON C.COD_CLIENTE = SLS.COD_CLIENTE
									INNER JOIN cliente_push ON (cliente_push.COD_CLIENTE = C.COD_CLIENTE)
									WHERE SLS.COD_CAMPANHA = $cod_campanha
									AND SLT.LOG_ENVIO = 'P'
									AND SLT.COD_LOTE = $i
									AND SLS.LOG_COMPARA = 0
									GROUP BY SLS.COD_CLIENTE
									ORDER BY SLT.COD_LOTE
									";

					// fnEscreve($sqlEnvio);
					$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sqlEnvio);

					$insertFila = "";

					while($row = mysqli_fetch_assoc($arrayQuery)){

	                    $insertFila .= "(
		                                   '".$row['COD_EMPRESA']."', 
		                                   '".$row['COD_UNIVEND']."', 
		                                   '".$row['COD_CLIENTE']."', 
		                                   '".$row['NUM_CGCECPF']."', 
		                                   '".addslashes(fnAcentos($row['NOM_CLIENTE']))."', 
		                                   '".$row['DAT_NASCIME']."', 
		                                   '".trim($row['DES_EMAILUS'])."',
		                                   '".fnLimpaDoc($row['NUM_CELULAR'])."',    
		                                   '".$row['COD_SEXOPES']."', 
		                                   '".$cod_campanha."', 
		                                   '".$qrGat['COD_GATILHO']."',
		                                   '14',
		                                   '".$qrGat['TIP_GATILHO']."',
		                                   '0',
		                                   '0',
		                                   '".date("W", strtotime("-2 day",strtotime(date('Y-m-d H:i:s'))))."',
		                                   99,
		                                   '".date('m')."',
		   								   '0',
		                                    0   
		                                ),";

					}

					$insertFila = rtrim(ltrim($insertFila,""),",");

					$sqlfila="INSERT INTO email_fila ( 
										   COD_EMPRESA, 
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
	                                       VAL_CRED_ACUMULADO,
	                                       VAL_RESGATE,
	                                       SEMANA,
	                                       TIP_CONTROLE,
	                                       MES,
		  								   CRED_VENDA,
	                                       COD_VENDA
	                            		) VALUES $insertFila";

					mysqli_query(connTemp($cod_empresa,''),$sqlfila);

					unset($sqlfila);
					unset($insertFila);


				}
				

				$sqlUpdt2 = "UPDATE PUSH_LOTE
							 SET LOG_ENVIO = 'S'
							 WHERE COD_EMPRESA = $cod_empresa
							 AND COD_CAMPANHA = $cod_campanha;

								UPDATE CAMPANHA SET 
								LOG_PROCESSA_PUSH = 'S',
								DAT_PROCESSA_PUSH = NOW()
								WHERE COD_EMPRESA = $cod_empresa 
								AND COD_CAMPANHA = $cod_campanha";

				mysqli_multi_query(connTemp($cod_empresa,''),$sqlUpdt2);

			}

		}else{

			echo "Necessária aprovação para o envio da lista";
			
		}

	}else{

		echo "Campanha já processada";

	}

?> 