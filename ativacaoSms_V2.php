<?php

//echo "<h5>_".$opcao."</h5>";

$pct_unicos_sms = 0;
$pct_reservaVl = 0;

$hashLocal = mt_rand();

function hour_min($minutes)
{ // Total
	if ($minutes <= 0) return '00 horas e 00 minutos';
	else
		return sprintf("%02d", floor($minutes / 60)) . ' horas e ' . sprintf("%02d", str_pad(($minutes % 60), 2, "0", STR_PAD_LEFT)) . " minutos";
}

if (isset($_GET['pop'])) {
	$popUp = fnLimpaCampo($_GET['pop']);
} else {
	$popUp = '';
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$qtd_lote = fnLimpaCampo($_REQUEST['QTD_LOTE']);
		$cod_empresa = fnLimpaCampo($_REQUEST['COD_EMPRESA']);
		$cod_campanha = fnLimpaCampo($_REQUEST['COD_CAMPANHA']);
		$des_interval = fnLimpaCampoZero($_REQUEST['DES_INTERVAL']);
		$dat_iniagendamento = fnDataSql($_REQUEST['DAT_INIAGENDAMENTO']);

		$horas = explode(" ", $_REQUEST['DAT_INIAGENDAMENTO']);

		$dat_iniagendamento = $dat_iniagendamento . " " . $horas[1];

		if (isset($_POST['COD_PERSONA'])) {
			$Arr_COD_PERSONAS = $_POST['COD_PERSONA'];

			for ($i = 0; $i < count($Arr_COD_PERSONAS); $i++) {
				$cod_personas = $cod_personas . $Arr_COD_PERSONAS[$i] . ",";
			}

			$cod_personas = rtrim($cod_personas, ",");
			$cod_personas = ltrim($cod_personas, ",");
		} else {
			$cod_personas = "0";
		}

		$cod_usucada = $_SESSION['SYS_COD_USUARIO'];

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		if ($opcao != '') {

			//mensagem de retorno
			switch ($opcao) {
				case 'CAD':

					$sqlDel = "DELETE FROM SMS_LOTE 
								   WHERE COD_EMPRESA = $cod_empresa
								   AND COD_CAMPANHA = $cod_campanha
								   AND LOG_ENVIO = 'P'";

					mysqli_query(connTemp($cod_empresa, ''), $sqlDel);

					$periodo_hrs = 0;
					$cod_loteref = "";
					$lista = "";

					$sql = "CALL SP_RELAT_SMS_LOTE($cod_empresa, $cod_campanha, $qtd_lote)";
					// fnEscreve($sql);
					mysqli_query(connTemp($cod_empresa, ''), $sql);

					$sqlLote = "SELECT CP.DES_CAMPANHA, MAX(EL.COD_LOTE) AS NRO_LOTES 
									FROM SMS_LISTA EL
									LEFT JOIN CAMPANHA CP ON CP.COD_CAMPANHA = EL.COD_CAMPANHA
									WHERE EL.COD_EMPRESA = $cod_empresa 
									AND EL.COD_CAMPANHA = $cod_campanha";

					$qrLote = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa, ''), $sqlLote));
					$nro_lotes = $qrLote['NRO_LOTES'];
					$des_campanha = preg_replace('/\s+/', '_', fnAcentos($qrLote['DES_CAMPANHA']));

					$des_campanha = str_replace('/', '.', $des_campanha);

					// fnEscreve($nro_lotes);
					// fnEscreve($qrLote['DES_CAMPANHA']);

					$sql = "SELECT  TE.DES_TEMPLATE AS HTML 
										FROM CAMPANHA CP
										INNER JOIN MENSAGEM_SMS ME ON ME.COD_CAMPANHA = CP.COD_CAMPANHA
										INNER JOIN TEMPLATE_SMS TE ON TE.COD_TEMPLATE = ME.COD_TEMPLATE_SMS
										WHERE CP.COD_EMPRESA = $cod_empresa
										AND CP.COD_CAMPANHA = $cod_campanha
										AND ME.LOG_PRINCIPAL = 'S'";
					// fnEscreve($sql);
					$qrMsg = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa, ''), $sql));

					// fnEscreve($qrMsg['HTML']);

					$tagsPersonaliza = procpalavras($qrMsg['HTML'], $connAdm->connAdm());

					$tagsPersonaliza = '<#EMAIL>,' . $tagsPersonaliza;

					$tags = explode(',', $tagsPersonaliza);

					$selectCliente = "";


					for ($i = 0; $i < count($tags); $i++) {
						// fnEscreve($tags[$i]);
						switch ($tags[$i]) {

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
								$selectCliente .= "";
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

					$selectCliente = rtrim($selectCliente, ',');

					// include './_system/ibope/BuscarCampanha.php';
					// include '_system/ftpIbope.php';

					$sql = "SELECT COD_PERSONAS, COD_LISTA
								FROM SMS_PARAMETROS
								WHERE COD_LISTA = (
												 	SELECT MAX(COD_LISTA) FROM SMS_PARAMETROS
												 	WHERE COD_EMPRESA = $cod_empresa 
												 	AND COD_CAMPANHA = $cod_campanha
												  )";

					$qrMsg = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa, ''), $sql));

					$lista = $qrMsg['COD_LISTA'];
					$personas = $qrMsg['COD_PERSONAS'];

					for ($i = 1; $i <= $nro_lotes; $i++) {

						unset($newRow);

						$nomeRel = $cod_empresa . '_' . date("YmdHis") . "_" . $des_campanha . "_" . $i . '.csv';
						$arquivo = '_system/ibope/listas_envio/' . $nomeRel;
						$caminhoRelat = './_system/ibope/listas_envio/';

						$sql = "SELECT $selectCliente
									FROM clientes C 
									INNER JOIN SMS_LISTA EC ON EC.COD_CLIENTE = C.COD_CLIENTE
									WHERE EC.COD_EMPRESA = $cod_empresa 
									AND EC.COD_CAMPANHA = $cod_campanha
									AND EC.COD_LOTE = $i";

						$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

						$array = array();
						$linhas = 0;

						while ($row = mysqli_fetch_assoc($arrayQuery)) {

							$linha = "";

							for ($j = 0; $j < count($tags); $j++) {
								// fnEscreve($tags[$i]);
								switch ($tags[$j]) {

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
										$itemLinha = 'R$' . fnValor($row['CREDITO_DISPONIVEL'], 2);
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
										$itemLinha = "DES_EMAILUS AS '<#EMAIL>',";
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
										$NOM_ARRAY_UNIDADE = (array_search($row['COD_UNIVEND'], array_column($ARRAY_UNIDADE, 'COD_UNIVEND')));
										$itemLinha = fnAcentos($ARRAY_UNIDADE[$NOM_ARRAY_UNIDADE]['nom_fantasi']);
										break;
									case '<#ENDERECOLOJA>';
										$itemLinha = "DES_EMAILUS AS '<#EMAIL>',";
										break;
									case '<#TELEFONELOJA>';
										$itemLinha = "DES_EMAILUS AS '<#EMAIL>',";
										break;
									case '<#ANIVERSARIO>';
										$itemLinha = substr($row['DAT_NASCIME'], 0, -5);
										break;
									case '<#DATAEXPIRA>';
										$itemLinha = fnDataShort($row['DAT_EXPIRA']);
										break;
									default:
										$itemLinha = $row['DES_EMAILUS'];
										break;
								}
								$linha .= $itemLinha . ";";
							}

							// fnEscreve($linha);
							$newRow[] = rtrim($linha, ';');
							$linhas++;
						}

						// fnescreve($caminhoRelat);
						// fnescreve($nomeRel);

						fngravacvs($newRow, $caminhoRelat, $nomeRel);

						$dadosarquivo = array(
							'arqlocal' => $arquivo,
							'nomearq' => $nomeRel
						);

						// $retorno = ibopeftp($dadosarquivo);

						// fnescreve('data inicio: '.$dat_iniagendamento);
						if ($des_interval == 0) {
							$intervalo = $periodo_hrs . " minutes";
						} else {
							$intervalo = $periodo_hrs . " hours";
						}
						$dat_agendamento = date("Y-m-d H:i:s", strtotime($dat_iniagendamento));
						// fnescreve('data agendamen.: '.$dat_agendamento);

						$sqlControle = "INSERT INTO SMS_LOTE(
																COD_CAMPANHA,
																COD_EMPRESA,
																COD_LOTE,
																QTD_LISTA,
																COD_LISTA,
																COD_PERSONAS,
																DAT_AGENDAMENTO,
																LOG_ENVIO,
																NOM_ARQUIVO,
																DES_PATHARQ,
																COD_USUCADA
															) VALUES(
																$cod_campanha,
																$cod_empresa,
																$i,
																$linhas,
																'$lista',
																'$personas',
																'$dat_agendamento',
																'P',
																'$nomeRel',
																'$arquivo',
																$cod_usucada
															)";
						// fnEscreve($sqlControle);

						mysqli_query(connTemp($cod_empresa, ''), $sqlControle);

						// exit();

						$sqlCod = "SELECT MAX(COD_CONTROLE) AS COD_LOTEREF FROM SMS_LOTE
									   WHERE COD_EMPRESA = $cod_empresa 
									   AND COD_CAMPANHA = $cod_campanha";

						$qrCod = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa, ''), $sqlCod));

						$cod_loteref = $cod_loteref . $qrCod['COD_LOTEREF'] . ',';

						if ($des_interval == 0) {
							$periodo_hrs += 5;
						} else {
							$periodo_hrs += $des_interval;
						}

						// fnescreve('intervalo: '.$des_interval);
						// fnescreve('periodo acumulado'.$periodo_hrs);

					}

					$cod_loteref = ltrim(rtrim($cod_loteref, ','), ',');

					$sqlGeracao = "INSERT INTO CONTROLE_LOTE_SMS(
															COD_CAMPANHA,
															COD_EMPRESA,
															COD_LOTEREF,
															COD_LISTAREF,
															COD_PERSONAS,
															COD_USUCADA
														) VALUES(
															$cod_campanha,
															$cod_empresa,
															'$cod_loteref',
															'$lista',
															'$personas',
															$cod_usucada
														)";
					// fnEscreve($sqlGeracao);

					mysqli_query(connTemp($cod_empresa, ''), $sqlGeracao);

					$sqlUpdate = "UPDATE SMS_LOTE SET COD_GERACAO = ( SELECT MAX(COD_GERACAO) 
																			FROM CONTROLE_LOTE_SMS 
																			WHERE COD_EMPRESA = $cod_empresa 
									   										AND COD_CAMPANHA = $cod_campanha )
									  WHERE COD_EMPRESA = $cod_empresa 
									  AND COD_CAMPANHA = $cod_campanha
									  AND COD_CONTROLE IN($cod_loteref)";

					// fnEscreve($sqlUpdate);

					mysqli_query(connTemp($cod_empresa, ''), $sqlUpdate);


					$msgRetorno = "Lote gerado com <strong>sucesso!</strong>";
					break;
				case 'ENVIAR_MODEL':
					$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";
					break;
				case 'ENVIAR_LISTAMOD':
					$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";
					break;
					break;
				case 'ENVIAR_LISTA':
					$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";
					break;
					break;
			}
			$msgTipo = 'alert-success';
		}
	}
}

if (isset($_GET['alert'])) {
	$msgRetorno = "Campanha processada com <strong>sucesso!</strong>";
	$msgTipo = 'alert-success';
}

//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
	//busca dados da empresa
	$cod_campanha = fnDecode($_GET['idc']);
	$cod_empresa = fnDecode($_GET['id']);
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
	}
} else {
	$cod_empresa = 0;
	//fnEscreve('entrou else');
}

$sql = "SELECT * FROM SMS_PARAMETROS 
			WHERE COD_LISTA = (
							 	SELECT MAX(COD_LISTA) FROM SMS_PARAMETROS
							 	WHERE COD_EMPRESA = $cod_empresa 
							 	AND COD_CAMPANHA = $cod_campanha
							  )";

$qrTot = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa, ''), $sql));


if (isset($qrTot)) {
	$cod_lista = $qrTot['COD_LISTA'];
	$cod_persona = $qrTot['COD_PERSONAS'];
	$tot_personas = $qrTot['TOT_PERSONAS'];
	$pct_reserva = $qrTot['PCT_RESERVA'];
	$clientes_unicos = $qrTot['CLIENTES_UNICOS'];
	$clientes_unicos_sms = $qrTot['CLIENTES_UNICOS_SMS'];
	$pct_unicos_mail = ($clientes_unicos_sms * 100) / $clientes_unicos;
	$clientes_unico_perc = $qrTot['CLIENTES_UNICO_PERC'];
	$pct_clientes_unico = ($clientes_unico_perc  * 100) / $clientes_unicos_sms;
	$total_cliente_sms_nao = $qrTot['TOTAL_CLIENTE_SMS_NAO'];
	$pct_sem_sms = ($total_cliente_sms_nao * 100) / $clientes_unicos;
	$clientes_optout = $qrTot['CLIENTES_OPTOUT'];
	$pct_optout = ($clientes_optout * 100) / $clientes_unicos;
	$clientes_blacklist = $qrTot['CLIENTES_BLACKLIST'];
	$pct_blacklist = ($clientes_blacklist * 100) / $clientes_unicos;

	//$lista_envio = $clientes_unicos_sms - $clientes_unico_perc - $clientes_blacklist - $clientes_optout;
	//elina pediu pra mudar.
	// $lista_envio = $clientes_unicos - $clientes_unico_perc - $clientes_blacklist - $clientes_optout;
	// AJUSTADO COM ADILSON 17/02/2022
	$lista_envio = $clientes_unicos_sms - $clientes_unico_perc;

	$pct_lista = (($lista_envio * 100) / $clientes_unicos_sms);

	//fnEscreve($pct_lista);
	//fnEscreve($clientes_unicos);

} else {
	$cod_lista = 0;
	$cod_persona = 0;
	$pct_reserva = 0;
	$tot_personas = "0";
	$clientes_unicos = "0";
	$clientes_unicos_sms = "0";
	$clientes_unico_perc = "0";
	$total_cliente_sms_nao = "0";
	$clientes_optout = 0;
	$clientes_blacklist = 0;
	$pct_clientes_unico = 0;
	$pct_sem_sms = 0;
	$pct_optout = 0;
	$pct_blacklist = 0;
}

$sqlCamp = "SELECT CP.COD_EXT_CAMPANHA,
					   CP.LOG_PROCESSA_SMS,
					   CP.LOG_CANCELA,
					   CP.DAT_CANCELA,
					   GM.DAT_INI AS DAT_INIAGENDAMENTO,
					   GM.DAT_FIM AS DAT_FIMAGENDAMENTO,
					   GM.HOR_INI,
					   GM.HOR_FIM,
					   GM.TIP_GATILHO,
					   CP.LOG_ATIVO,
					   CP.LOG_CANCELA,
					   CP.DAT_CANCELA,
					   CP.DAT_PROCESSA_SMS
				FROM CAMPANHA CP
				LEFT JOIN GATILHO_SMS GM ON GM.COD_CAMPANHA = CP.COD_CAMPANHA 
				WHERE CP.COD_EMPRESA = $cod_empresa 
				AND CP.COD_CAMPANHA = $cod_campanha";

// fnEscreve($sqlCamp);

$qrCamp = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa, ''), $sqlCamp));

// fnEscreve($qrCamp['COD_EXT_CAMPANHA']);

$dat_iniagendamento = $qrCamp['DAT_INIAGENDAMENTO'] . " " . $qrCamp['HOR_INI'];
$dat_fimagendamento = $qrCamp['DAT_FIMAGENDAMENTO'] . " " . $qrCamp['HOR_FIM'];
$agora = date('Y-m-d H:i:s');
$tip_gatilho = $qrCamp['TIP_GATILHO'];
$log_processa = $qrCamp['LOG_PROCESSA_SMS'];
$log_cancela = $qrCamp['LOG_CANCELA'];
$dat_cancela = $qrCamp['DAT_CANCELA'];
$log_ativo = $qrCamp['LOG_ATIVO'];
$dat_cancela = $qrCamp['DAT_CANCELA'];
$log_cancela = $qrCamp['LOG_CANCELA'];

$datProcessa = "";
$msgProcessa = "Não Processada";
$logProcessa = '<span class="fas fa-times text-danger"></span>';

if ($log_processa == "S") {
	$datProcessa = fndatafull($qrCamp['DAT_PROCESSA_SMS']);
	$msgProcessa = "Processada";
	$logProcessa = '<span class="fas fa-check text-success"></span>';
}

// fnEscreve($log_processa);
// fnEscreve($datProcessa);

// fnEscreve($dat_iniagendamento);
// fnEscreve($agora);

if ($agora > $dat_iniagendamento) {
	$dat_original = $dat_iniagendamento;
	$dat_iniagendamento = date('Y-m-d H:i:s');
} else {
	$dat_original = $dat_iniagendamento;
}

$day1 = $dat_iniagendamento;
$day1 = strtotime($day1);
$day2 = $dat_fimagendamento;
$day2 = strtotime($day2);

$diffHours = round(($day2 - $day1) / 3600);


$data_teste = fnDatesql($qrCamp['DAT_INIAGENDAMENTO']);

// $sql = "SELECT DES_CAMPANHA, COD_EXT_CAMPANHA, DAT_EXTERNA
// 		FROM CAMPANHA
// 		WHERE COD_EMPRESA = $cod_empresa 
// 		AND COD_CAMPANHA = $cod_campanha";

// $arrayIntegra = mysqli_query(connTemp($cod_empresa,''),$sql);

// $qrIntegra = mysqli_fetch_assoc($arrayIntegra);


$sql = "SELECT COD_LISTA, DAT_CADASTR FROM SMS_PARAMETROS 
			where COD_CAMPANHA = $cod_campanha 
			AND COD_EMPRESA = $cod_empresa
			ORDER BY 1 DESC LIMIT 1";
//fnEscreve($sql);
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
$syncSms = mysqli_num_rows($arrayQuery);

$qrIntegra = mysqli_fetch_assoc($arrayQuery);

if ($syncSms > 0) {
	$dat_cadastrIntegra = fnDataFull($qrIntegra['DAT_CADASTR']);
	$integraSync = '<span class="fas fa-check text-success"></span>';
	$syncMsgIntegra = "Sincronizado";
	$sync = 1;
} else {
	$dat_cadastrIntegra = "";
	$integraSync = '<span class="fas fa-times text-danger"></span>';
	$syncMsgIntegra = "Sincronizando... aguarde.";
	$sync = 0;
}

$sql2 = "SELECT DISTINCT ME.COD_TEMPLATE_SMS, ME.DAT_CADASTR, TE.NOM_TEMPLATE 
			 FROM MENSAGEM_SMS ME
			 LEFT JOIN TEMPLATE_SMS TE ON TE.COD_TEMPLATE = ME.COD_TEMPLATE_SMS
			 WHERE ME.COD_EMPRESA = $cod_empresa
			 AND ME.COD_CAMPANHA = $cod_campanha";

$arrayTemplates = mysqli_query(connTemp($cod_empresa, ''), $sql2);

$sql = "SELECT case 
           when   SUM(PM.QTD_SALDO_ATUAL) <=   SUM(PM.QTD_PRODUTO)
              then 
                 SUM(PM.QTD_SALDO_ATUAL) 
              ELSE 
               SUM(PM.QTD_PRODUTO) - SUM(PM.QTD_SALDO_ATUAL) end QTD_PRODUTO ,
                   PM.TIP_LANCAMENTO,
                   CC.DES_CANALCOM 
            FROM PEDIDO_MARKA PM
            INNER JOIN PRODUTO_MARKA PRM ON PRM.COD_PRODUTO = PM.COD_PRODUTO
            INNER JOIN CANAL_COMUNICACAO CC ON CC.COD_CANALCOM = PRM.COD_CANALCOM 
            WHERE PM.COD_ORCAMENTO > 0 
            AND PM.PAG_CONFIRMACAO='S'
            AND  PM.TIP_LANCAMENTO='C'
            AND PM.COD_EMPRESA = $cod_empresa
            AND  PM.QTD_SALDO_ATUAL > 0
            GROUP BY CC.COD_TPCOM";

// fnEscreve($sql);

$arrayQuery = mysqli_query($connAdm->connAdm(), trim($sql));

$count = "";
while ($qrLista = mysqli_fetch_assoc($arrayQuery)) {

	// fnEscreve($qrLista[QTD_PRODUTO]);

	$count++;
	$qtd_sms = 0;
	$qtd_email = 0;
	$qtd_wpp = 0;
	switch ($qrLista['DES_CANALCOM']) {

		case 'SMS':
			if ($qrLista['TIP_LANCAMENTO'] == 'D') {
				$qtd_sms = $qtd_sms - $qrLista['QTD_PRODUTO'];
			} else {
				$qtd_sms = $qtd_sms + $qrLista['QTD_PRODUTO'];
			}
			break;

		case 'WhatsApp':
			if ($qrLista['TIP_LANCAMENTO'] == 'D') {
				$qtd_wpp = $qtd_wpp - $qrLista['QTD_PRODUTO'];
			} else {
				$qtd_wpp = $qtd_wpp + $qrLista['QTD_PRODUTO'];
			}
			break;

		default:
			if ($qrLista['TIP_LANCAMENTO'] == 'D') {
				$qtd_email = $qtd_email - $qrLista['QTD_PRODUTO'];
			} else {
				$qtd_email = $qtd_email + $qrLista['QTD_PRODUTO'];
			}
			break;
	}
}

$sqlLista = "SELECT 1 FROM SMS_LISTA 
    			 WHERE COD_EMPRESA = $cod_empresa 
    			 AND COD_CAMPANHA = $cod_campanha
    			 AND LOG_COMPARA = 0";
$arrayLista = mysqli_query(connTemp($cod_empresa, ''), $sqlLista);

$qtd_lista = mysqli_num_rows($arrayLista);
$qtd_lote = ceil($qtd_lista / 500);

if ($qtd_lote == 0) {
	$qtd_lote = 1;
}
// $qtd_sms = 9999999999999999;
$sync = 1;

if ($tip_gatilho == 'individual') {

	$msgRetornoSaldo = "<span class='fal fa-exclamation-triangle f16'></span><strong> &nbsp;Atenção!</strong> Você possui <strong>" . fnValor($qtd_sms, 0) . "</strong> envios restantes. &nbsp;<a href='https://adm.bunker.mk/action.do?mod=" . fnEncode(1485) . "&id=" . fnEncode($cod_empresa) . "' target='_blank' style='color: #FFF; text-decoration: underline;'>Contratar mais envios</a><br/>Os <b>débitos</b> dessa campanham ocorrerão no momento do <b>disparo</b>.";
} else {

	$msgRetornoSaldo = "<span class='fal fa-exclamation-triangle f16'></span><strong> &nbsp;Atenção!</strong> Você possui <strong>" . fnValor($qtd_sms, 0) . "</strong> envios restantes. &nbsp;<a href='https://adm.bunker.mk/action.do?mod=" . fnEncode(1485) . "&id=" . fnEncode($cod_empresa) . "' target='_blank' style='color: #FFF; text-decoration: underline;'>Contratar mais envios</a><br/>Os <b>débitos</b> dessa campanham ocorrerão no momento do <b>disparo</b>.";
}

$msgTipoSaldo = 'alert-info';

$tempoCancelaSec = $lista_envio * 3;
$tempoCancela = gmdate("H", $tempoCancelaSec) . " horas, " . gmdate("i", $tempoCancelaSec) . " minutos e " . gmdate("H", $tempoCancelaSec) . " segundos";
?>

<style>
	body {
		overflow-y: scroll;
		scrollbar-width: none;
		/* Firefox */
		-ms-overflow-style: none;
		/* IE 10+ */
	}

	body::-webkit-scrollbar {
		/* WebKit */
		width: 0;
		height: 0;
	}

	#blocker {
		display: none;
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		opacity: .8;
		background-color: #fff;
		z-index: 1000;
		cursor: wait;
	}

	#blocker div {
		position: absolute;
		top: 30%;
		left: 48%;
		width: 200px;
		height: 2em;
		margin: -1em 0 0 -2.5em;
		color: #000;
		font-weight: bold;
	}

	.c1 {
		color: #cecece;
	}

	.c2 {
		color: #808B96;
	}

	.c3 {
		color: #17202A;
	}

	h5 {
		margin-top: 1px;
		margin-bottom: 30px;
	}
</style>

<div id="blocker">
	<div style="text-align: center;"><img src="images/loading2.gif"><br /> Aguarde. Processando... ;-)<br /><small>(este processo pode demorar vários minutos)</small></div>
</div>

<div class="row">

	<div class="col-md12 margin-bottom-30" id="corpo">
		<!-- Portlet -->
		<?php if ($popUp != "true") {  ?>
			<div class="portlet portlet-bordered">
			<?php } else { ?>
				<div class="portlet" style="padding: 0 20px 20px 20px;">
				<?php } ?>

				<?php if ($popUp != "true") {  ?>
					<div class="portlet-title">
						<div class="caption">
							<i class="glyphicon glyphicon-calendar"></i>
							<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa ?></span>
						</div>
						<?php include "atalhosPortlet.php"; ?>
					</div>
				<?php } ?>
				<div class="portlet-body">

					<div class="alert <?php echo $msgTipoSaldo; ?> top30 bottom30" role="alert" id="msgRetornoSaldo">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<?php echo $msgRetornoSaldo; ?>
					</div>

					<?php if ($msgRetorno <> '') { ?>
						<div class="alert <?php echo $msgTipo; ?> top30 bottom30" role="alert" id="msgRetorno">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<?php echo $msgRetorno; ?>
						</div>
					<?php } ?>

					<h4 style="margin: 0 0 5px 0;"><span class="bolder">Ativação da Campanha</span></h4>
					<div class="push20"></div>


					<div class="login-form">

						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">



							<!-- <div class="row">
												
												<div class="col-sm-7">
													<div class="form-group">
														<label for="inputName" class="control-label">Personas para Geração da Lista</label>
														<div class="push10"></div>
														
															<select data-placeholder="Selecione a persona desejada" name="COD_PERSONA[]" id="COD_PERSONA" multiple="multiple" class="chosen-select-deselect requiredChk" tabindex="1">
																<option value=""></option>
																<?php

																// $sql = "SELECT * from persona where cod_empresa = $cod_empresa and LOG_ATIVO = 'S' order by DES_PERSONA  ";																		
																// $arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);																
																// while ($qrListaPersonas = mysqli_fetch_assoc($arrayQuery))
																// {

																// echo"
																// 	  <option value='".$qrListaPersonas['COD_PERSONA']."'>".ucfirst($qrListaPersonas['DES_PERSONA']). "</option> 
																// 	";    
																// }

																?>								
															</select>

													</div>             

												</div>
												
												<div class="col-md-5">
													<div class="form-group">
														<label for="inputName" class="control-label">Emails Extras</label>
														<div class="push10"></div>
														<input type="text" class="form-control input-sm" name="DES_EMAILEX" id="DES_EMAILEX" maxlength="500" value="">
													</div>
													<div class="help-block with-errors">Separar múltiplos emails por ";"</div>
												</div>			
																			
											</div> -->

							<div class="push10"></div>

							<div class="flexrow">

								<div class="col text-center">
									<i class="fal fa-users c2 fa-2x"></i>
									<div class="push3"></div>
									<span class="f18" id="TOT_PERSONAS"><span class="c3 f18"><?= fnValor($tot_personas, 0) ?></span> &nbsp;
										<span class="f14 c1">&nbsp;</span>
									</span>
									<h5 class="c2">Personas Selecionadas</h5>
									<div class="push20"></div>
								</div>

								<div class="col text-center">
									<i class="fal fa-user-tag c2 fa-2x"></i>
									<div class="push3"></div>
									<span class="f18" id="CLIENTES_UNICOS"><span class="c3 f18"><?= fnValor($clientes_unicos, 0) ?></span> &nbsp;
										<span class="f14 c1">&nbsp;</span>
									</span>
									<h5 class="c2">Clientes Únicos</h5>
									<div class="push20"></div>
								</div>

								<div class="col text-center">
									<i class="fal fa-phone c2 fa-2x"></i>
									<div class="push3"></div>
									<span class="f18" id="CLIENTES_UNICOS_SMS"><span class="c3 f18"><?= fnValor($clientes_unicos_sms, 0) ?></span> &nbsp;
										<span class="f14 c1"><?= fnValor($pct_unicos_sms, 2) ?>%</span>
									</span>
									<h5 class="c2">Clientes Únicos Com Celular</h5>
									<div class="push20"></div>
								</div>

								<div class="col text-center">
									<i class="fal fa-phone-slash c2 fa-2x"></i>
									<div class="push3"></div>
									<span class="f18" id="TOTAL_CLIENTE_SMS_NAO"><span class="c3 f18"><?= fnValor($total_cliente_sms_nao, 0) ?></span> &nbsp;
										<span class="f14 c1"><?= fnValor($pct_sem_sms, 2) ?>%</span>
									</span>
									<h5 class="c2">Clientes Sem Celular</h5>
									<div class="push20"></div>
								</div>

							</div>

							<div class="flexrow">

								<div class="col text-center">
									<i class="fal fa-user-minus c2 fa-2x"></i>
									<div class="push3"></div>
									<span class="f18" id="CLIENTES_OPTOUT"><span class="c3 f18"><?= fnValor($clientes_optout, 0) ?></span> &nbsp;
										<span class="f14 c1"><?= fnValor($pct_optout, 2) ?>%</span>
									</span>
									<h5 class="c2">Clientes Opt Out</h5>
									<div class="push20"></div>
								</div>

								<div class="col text-center">
									<i class="fal fa-user-times c2 fa-2x"></i>
									<div class="push3"></div>
									<span class="f18" id="CLIENTES_BLACKLIST"><span class="c3 f18"><?= fnValor($clientes_blacklist, 0) ?></span> &nbsp;
										<span class="f14 c1"><?= fnValor($pct_blacklist, 2) ?>%</span>
									</span>
									<h5 class="c2">Clientes Black List</h5>
									<div class="push20"></div>
								</div>

								<div class="col text-center">
									<i class="fal fa-user-lock c2 fa-2x"></i>
									<div class="push3"></div>
									<span class="f18" id="CLIENTES_UNICO_PERC"><span class="c3 f18"><?= fnValor($clientes_unico_perc, 0) ?></span> &nbsp;
										<span class="f14 c1"><?= fnValor($pct_reservaVl, 2) ?>%</span>
									</span>
									<h5 class="c2">Grupo de Controle</h5>
									<div class="push20"></div>
								</div>

								<div class="col text-center">
									<i class="fal fa-paper-plane c2 fa-2x"></i>
									<div class="push3"></div>
									<span class="f18" id="LISTA_ENVIO"><span class="c3 f18"><?= fnValor($lista_envio, 0) ?></span> &nbsp;
										<span class="f14 c1"><?= fnValor($pct_lista, 2) ?>%</span>
									</span>
									<h5 class="c2">Lista de Envio</h5>
									<div class="push20"></div>
								</div>

							</div>

							<div class="push10"></div>
							<?php
							if ($lista_envio >= $qtd_sms && $personas != "") {
							?>
								<div class="alert alert-warning alert-dismissible top30 bottom30" role="alert">
									Seu saldo atual não permitirá o processamento desta lista de envio. Redimensione sua persona alvo, ou <a href='https://adm.bunker.mk/action.do?mod=<?= fnEncode(1485) ?>&id=<?= fnEncode($cod_empresa) ?>' target='_blank' style='color: #FFF; text-decoration: underline;'>contratate mais envios</a>.
								</div>
							<?php }
							?>
							<div class="push10"></div>
							<hr>
							<div class="row">

								<div class="col-md-offset-3 col-md-6 col-xs-offset-1 col-xs-10">

									<?php

									if ($tip_gatilho == 'individual' && $log_processa != 'S') {

									?>

										<div class="col-md-4 col-xs-5">
											<div class="form-group">
												<label for="inputName" class="control-label">Data Início <small>(referência)</small></label>
												<input type="text" class="form-control input-sm leitura" readonly="readonly" name="DAT_INIAGENDAMENTO" id="DAT_INIAGENDAMENTO" value="<?= fnDataFull($dat_iniagendamento) ?>">
												<div class="help-block with-errors"><?= fnDataFull($dat_original) ?></div>
											</div>
										</div>

										<div class="col-md-4 col-xs-5">
											<div class="form-group">
												<label for="inputName" class="control-label">Data Final <small>(referência)</small></label>
												<input type="text" class="form-control input-sm leitura" readonly="readonly" name="DAT_FIMAGENDAMENTO" id="DAT_FIMAGENDAMENTO" value="<?= fnDataFull($dat_fimagendamento) ?>">
												<label>&nbsp;</label>
											</div>
										</div>

										<div class="col-md-4 col-xs-2">
											<div class="form-group">
												<label for="inputName" class="control-label">Período <small>(referência)</small></label>
												<input type="text" class="form-control input-sm leitura" readonly="readonly" name="DES_PERIODO" id="DES_PERIODO" value="<?= fnValor($diffHours, 0) ?>h(s)">
												<label>&nbsp;</label>
											</div>
										</div>

										<div class="col-md-4 col-xs-3">
											<div class="form-group">
												<label for="inputName" class="control-label">Nro. de Lotes <small>(projetado)</small></label>
												<input type="text" class="form-control input-sm leitura" name="QTD_LOTE" id="QTD_LOTE" readonly="" value="<?= $qtd_lote ?>">
												<div class="help-block with-errors">Lote gerado a cada 500 contatos na lista</div>
											</div>
										</div>

										<div class="col-md-8 col-xs-3">
											<div class="form-group">
												<label for="inputName" class="control-label">Intervalo entre lotes</label>
												<select data-placeholder="Intervalo entre lotes" name="DES_INTERVAL" id="DES_INTERVAL" class="chosen-select-deselect pull-right" onchange="calculaPeriodo()" style="width:100%;">
													<option value="0">5 minutos</option>
													<option value="1">1 horas</option>
													<option value="2">2 horas</option>
													<option value="3">3 horas</option>
													<option value="4">4 horas</option>
													<option value="5">5 horas</option>
												</select>
												<div class="help-block with-errors"></div>
											</div>
										</div>

										<div class="push15"></div>

										<div class="col-md-4 col-xs-7" id="projecaoData">
											<div class="form-group">
												<label for="inputName" class="control-label">Data Final <small>(projetada)</small></label>
												<input type="text" class="form-control input-sm leituraOff" readonly="readonly" name="DES_PERIODOREF" id="DES_PERIODOREF" value="">
											</div>
										</div>

										<div class="col-md-8 col-xs-5">
											<div class="push10"></div>
											<div class="form-group">
												<?php

												if ($sync > 0 && $dat_iniagendamento != "" && $dat_fimagendamento != "" && mysqli_num_rows($arrayTemplates) > 0 && $qtd_sms >= $lista_envio) {

												?>

													<button type="submit" name="CAD" id="CAD" class="btn btn-primary btn-block getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Gerar lote</button>

												<?php

												} else if ($sync > 0 && $dat_iniagendamento != "" && $dat_fimagendamento != "" && mysqli_num_rows($arrayTemplates) > 0 && $qtd_sms < $lista_envio) {

												?>

													<!-- <a href="javascript:void(0)" class="btn btn-warning btn-block disabled"><i class="fal fa-exclamation-triangle" aria-hidden="true"></i>&nbsp; Saldo insuficiente</a> -->

													<button type="submit" name="CAD" id="CAD" class="btn btn-primary btn-block getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Gerar lote</button>

												<?php

												} else if ($sync > 0 && $dat_iniagendamento == "" && $dat_fimagendamento == "" && mysqli_num_rows($arrayTemplates) > 0) {

												?>

													<a href="javascript:void(0)" class="btn btn-warning btn-block disabled"><i class="fal fa-exclamation-triangle" aria-hidden="true"></i>&nbsp; Gatilho não configurado</a>

												<?php

												} else if ($sync == 0 && $dat_iniagendamento != "" && $dat_fimagendamento != "" && mysqli_num_rows($arrayTemplates) > 0) {

												?>

													<a href="javascript:void(0)" class="btn btn-warning btn-block disabled"><i class="fal fa-exclamation-triangle" aria-hidden="true"></i>&nbsp; Lista não configurada</a>

												<?php

												} else if ($sync > 0 && $dat_iniagendamento != "" && $dat_fimagendamento != "" && mysqli_num_rows($arrayTemplates) == 0) {

												?>

													<a href="javascript:void(0)" class="btn btn-warning btn-block disabled"><i class="fal fa-exclamation-triangle" aria-hidden="true"></i>&nbsp; Nenhuma mensagem configurada na automação</a>

												<?php

												} else {

												?>

													<a href="javascript:void(0)" class="btn btn-warning btn-block disabled"><i class="fal fa-exclamation-triangle" aria-hidden="true"></i>&nbsp; Gatilho/mensagem/lista não configurados</a>

												<?php

												}

												?>
											</div>
										</div>

									<?php

									}

									?>

									<div class="col-xs-12">

										<div class="push20"></div>

										<hr>

										<div class="push20"></div>

										<div class="col-md-12 col-xs-12">
											<div class="form-group">
												<?php

												$sql = "SELECT MAX(LOG_OK) AS OK FROM SMS_CONTROLE 
																			WHERE COD_EMPRESA = $cod_empresa 
																			AND COD_CAMPANHA = $cod_campanha
																			AND COD_LISTA = (
																							 	SELECT MAX(COD_LISTA) FROM SMS_PARAMETROS
																							 	WHERE COD_EMPRESA = $cod_empresa 
																							 	AND COD_CAMPANHA = $cod_campanha
																							)";
												$qrOk = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa, ''), $sql));

												if ($qrOk['OK'] == 'S') {
													$log_ok = 'S';
												} else {
													$log_ok = 'N';
												}

												$sqlVerLote = "SELECT COD_LOTE
																					FROM SMS_LOTE
																					WHERE COD_CAMPANHA = $cod_campanha 
																					AND COD_EMPRESA = $cod_empresa
																					AND COD_LOTE != 0
																					AND LOG_TESTE = 'N'
																					AND LOG_ENVIO = 'P'";

												// fnEscreve($sqlVerLote);

												$arrayVerLotes = mysqli_query(connTemp($cod_empresa, ''), $sqlVerLote);

												$sqlAgenda = "SELECT DAT_INI, HOR_INI FROM GATILHO_SMS WHERE COD_CAMPANHA = $cod_campanha AND COD_EMPRESA = $cod_empresa";


												$arrayAgenda = mysqli_query(connTemp($cod_empresa, ''), $sqlAgenda);

												$qrAgenda = mysqli_fetch_assoc($arrayAgenda);

												$dat_agendamento = $qrAgenda['DAT_INI'] . " " . $qrAgenda['HOR_INI'];

												// fnEscreve($log_ok);
												// fnEscreve(mysqli_num_rows($arrayTemplates));
												// fnEscreve($tip_gatilho);

												// fnEscreve($log_processa);

												// fnEscreve(mysqli_num_rows($arrayTemplates));
												// fnEscreve(mysqli_num_rows($arrayVerLotes));
												// fnEscreve($qtd_sms);
												// fnEscreve($lista_envio);
												// fnEscreve($log_ok);
												// fnEscreve($sync);
												// fnEscreve(mysqli_num_rows($arrayTemplates));
												// fnEscreve(mysqli_num_rows($arrayVerLotes));
												// fnEscreve($log_ativo);

												if ($log_ativo == 'N') {

												?>

													<a href="javascript:void(0)" class="btn btn-warning btn-block btn-lg getBtn disabled"><i class="fal fa-exclamation-triangle" aria-hidden="true"></i>&nbsp; A campanha está inativa</a>

													<?php

												} else if ($log_processa == 'S') {

													if ($log_cancela == "N") {

													?>
														<?php
														if ($tip_gatilho == 'individual' && $tempoCancelaSec >= 60) {
															fnEscreve($tempoCancelaSec);
														?>
															<div class="alert alert-info alert-dismissible top30 bottom30" role="alert">
																<?php if ($dat_agendamento <= date("Y-m-d H:i:s")) { ?>
																	Tempo estimado para cancelamento total da lista, à partir do momento do cancelamento: <b><?= $tempoCancela ?></b>.
																<?php } else { ?>
																	A data e horário de agendamento estão muito próximos da data/hora atual, e pode ocorrer de nem todos os envios poderem ser cancelados. <div class="push"></div>Tempo estimado para cancelamento total da lista, à partir do momento do cancelamento: <b><?= $tempoCancela ?></b>.
																<?php } ?>
															</div>
														<?php }
														?>
														<a href="javascript:void(0)" class="btn btn-warning btn-block btn-lg" onclick="verificaFila()"><i class="fal fa-trash" aria-hidden="true"></i>&nbsp; Cancelar Campanha</a>

													<?php


													} else {
													?>
														<a href="javascript:void(0)" class="btn btn-danger btn-block btn-lg getBtn disabled"><i class="fal fa-times" aria-hidden="true"></i>&nbsp; Campanha Cancelada</a>
													<?php
													}
												} else if ($lista_envio > $qtd_sms && (($log_ok == 'S' && $sync > 0 && mysqli_num_rows($arrayTemplates) > 0 && mysqli_num_rows($arrayVerLotes) > 0) || ($log_ok == 'S' && $sync > 0 && mysqli_num_rows($arrayTemplates) > 0  && $tip_gatilho == 'individual'))) {

													?>

													<a href="javascript:void(0)" class="btn btn-warning btn-block btn-lg getBtn disabled"><i class="fal fa-exclamation-triangle" aria-hidden="true"></i>&nbsp; Saldo insuficiente para processamento da campanha</a>

													<!-- <a href="javascript:void(0)" class="btn btn-info btn-block btn-lg getBtn" id="ENV" onclick="enviarLista()"><i class="fal fa-cogs" aria-hidden="true"></i>&nbsp; Processar campanha</a> -->

												<?php

												} else if ($qtd_sms >= $lista_envio && (($log_ok == 'S' && $sync > 0 && mysqli_num_rows($arrayTemplates) > 0 && mysqli_num_rows($arrayVerLotes) > 0) || ($log_ok == 'S' && $sync > 0 && mysqli_num_rows($arrayTemplates) > 0  && $tip_gatilho != 'individual'))) {

												?>

													<a href="javascript:void(0)" class="btn btn-info btn-block btn-lg getBtn" id="ENV" onclick="enviarLista()"><i class="fal fa-cogs" aria-hidden="true"></i>&nbsp; Processar campanha</a>

												<?php

												} else if ($qtd_sms < $lista_envio && (($log_ok == 'S' && $sync > 0 && mysqli_num_rows($arrayTemplates) > 0 && mysqli_num_rows($arrayVerLotes) > 0) || ($log_ok == 'S' && $sync > 0 && mysqli_num_rows($arrayTemplates) > 0  && $tip_gatilho != 'individual'))) {

												?>

													<!-- <a href="javascript:void(0)" class="btn btn-warning btn-block btn-lg getBtn disabled"><i class="fal fa-exclamation-triangle" aria-hidden="true"></i>&nbsp; Saldo insuficiente para processamento da campanha</a> -->

													<a href="javascript:void(0)" class="btn btn-info btn-block btn-lg getBtn" id="ENV" onclick="enviarLista()"><i class="fal fa-cogs" aria-hidden="true"></i>&nbsp; Processar campanha</a>

												<?php

												} else if ($log_ok == 'N' && $sync > 0 && mysqli_num_rows($arrayTemplates) > 0 && mysqli_num_rows($arrayVerLotes) > 0 || ($log_ok == 'N' && $sync > 0 && mysqli_num_rows($arrayTemplates) > 0  && $tip_gatilho != 'individual')) {

												?>

													<a href="javascript:void(0)" class="btn btn-warning btn-block btn-lg getBtn disabled"><i class="fal fa-exclamation-triangle" aria-hidden="true"></i>&nbsp; Necessária aprovação para processamento da lista</a>

												<?php

												} else {

												?>

													<a href="javascript:void(0)" class="btn btn-default btn-block btn-lg getBtn disabled"><i class="fal fa-exclamation-triangle" aria-hidden="true"></i>&nbsp; Nenhum lote a ser processado</a>

												<?php

												}

												?>

											</div>
										</div>

									</div>


								</div>

							</div>

							<div class="push50"></div>

							<div class="row">

								<div class="col-xs-12">
									<h5>Integrações</h5>
								</div>

								<div class="push10"></div>

								<div class="col-xs-12">

									<div class="no-more-tables">

										<table class="table table-bordered table-striped table-hover tableSorter">
											<thead>
												<tr>
													<th>Item</th>
													<th>Dt. Sincronização</th>
													<th>Status</th>
													<th class="{ sorter: false }"></th>
												</tr>
											</thead>
											<tbody id="relatorioConteudo2">

												<?php

												$sql = "SELECT DES_CAMPANHA, COD_EXT_CAMPANHA, DAT_EXTERNA
																			FROM CAMPANHA
																			WHERE COD_EMPRESA = $cod_empresa 
																			AND COD_CAMPANHA = $cod_campanha";

												$array = mysqli_query(connTemp($cod_empresa, ''), $sql);

												$qrCampanha = mysqli_fetch_assoc($array);

												// QUERY ESTÁ ACIMA DO BOTÃO DE GERAR LOTE - CAMPANHA

												?>

												<tr>
													<td><small>Processamento da Campanha: <?= $qrCampanha['DES_CAMPANHA'] ?></small></td>
													<td><small><?= $datProcessa ?></small></td>
													<td><small><?= $msgProcessa ?></small></td>
													<td class="text-center"><small><?= $logProcessa ?></small></td>
												</tr>

												<tr>
													<td><small>Integração da Campanha: <?= $qrCampanha['DES_CAMPANHA'] ?></small></td>
													<td><small><?= $dat_cadastrIntegra ?></small></td>
													<td><small><?= $syncMsgIntegra ?></small></td>
													<td class="text-center"><small><?= $integraSync ?></small></td>
												</tr>

												<?php

												// QUERY ESTÁ ACIMA DO BOTÃO DE PROCESSAMENTO - TEMPLATE (MENSAGEM AUTOMAÇÃO)

												while ($qrTemplate = mysqli_fetch_assoc($arrayTemplates)) {

													if ($qrTemplate['DAT_CADASTR'] != '') {
														$dat_cadastrTempl = fnDataFull($qrTemplate['DAT_CADASTR']);
														$templSync = '<span class="fas fa-check text-success"></span>';
														$syncMsgTempl = "Sincronizado";
													} else {
														$dat_cadastrTempl = "";
														$templSync = '<span class="fas fa-times text-danger"></span>';
														$syncMsgTempl = "Sincronizando... aguarde.";
													}

												?>

													<tr>
														<td><small>Template: <?= $qrTemplate['NOM_TEMPLATE'] ?></small></td>
														<td><small><?= $dat_cadastrTempl ?></small></td>
														<td><small><?= $syncMsgTempl ?></small></td>
														<td class="text-center"><small><?= $templSync ?></small></td>
													</tr>

												<?php
												}
												?>


												<tr>
													<td><small>Agendamento da Campanha</small></td>
													<td><small><?= fnDataFull($dat_agendamento) ?></small></td>
													<td></td>
													<td></td>
												</tr>

												<?php if ($log_cancela == 'S') { ?>
													<tr>
														<td><small>Cancelamento da Campanha</small></td>
														<td><small><?= fnDataFull($dat_cancela) ?></small></td>
														<td></td>
														<td></td>
													</tr>
												<?php } ?>


											</tbody>

										</table>

									</div>

								</div>

							</div>

							<div class="push10"></div>

							<?php

							$sqlCount = "SELECT 1
														FROM SMS_LOTE ELT
														WHERE ELT.COD_CAMPANHA = $cod_campanha 
														AND ELT.COD_EMPRESA = $cod_empresa
														AND COD_LOTE != 0
														AND LOG_TESTE = 'N'";

							// fnEscreve($sql3);

							$linhas = mysqli_num_rows(mysqli_query(connTemp($cod_empresa, ''), $sqlCount));

							// fnEscreve($linhas);

							$sql3 = "SELECT ELT.COD_LOTE, 
																ELT.DAT_AGENDAMENTO, 
																ELT.DAT_CADASTR, 
																-- ELT.COD_STATUSUP,
																ELT.LOG_ENVIO,
																ELT.DES_PATHARQ,
																ELT.COD_PERSONAS,
																ELT.COD_CONTROLE,
																ELT.QTD_LISTA,
																ELT.COD_GERACAO,
																TE.NOM_TEMPLATE
														FROM SMS_LOTE ELT
														LEFT JOIN TEMPLATE_SMS TE ON TE.COD_TEMPLATE = ELT.COD_EXT_TEMPLATE
														WHERE ELT.COD_CAMPANHA = $cod_campanha 
														AND ELT.COD_EMPRESA = $cod_empresa
														AND COD_LOTE != 0
														AND LOG_TESTE = 'N'
														ORDER BY ELT.DAT_CADASTR DESC
														LIMIT 0,20";

							// fnEscreve($sql3);

							$arrayLotes = mysqli_query(connTemp($cod_empresa, ''), $sql3);

							if (mysqli_num_rows($arrayLotes) > 0) {

							?>

								<div class="row">

									<div class="col-xs-12">
										<h5>Lotes</h5>
									</div>

									<div class="push10"></div>

									<div class="col-xs-12">

										<div class="no-more-tables">

											<table class="table table-bordered table-striped table-hover tableSorter">
												<thead>
													<tr>
														<th width="5%" class="{ sorter: false }"></th>
														<th>Item</th>
														<th>Template</th>
														<th>Lista</th>
														<th>Qtd. da Lista</th>
														<th>Dt. Sincronização</th>
														<th>Dt. Agendamento</th>
														<th>Status</th>
														<th class="{ sorter: false }"></th>
													</tr>
												</thead>
												<tbody id="relatorioConteudo">

													<?php

													$count = 0;
													$tot_qtd = 0;

													while ($qrLote = mysqli_fetch_assoc($arrayLotes)) {

														$count++;

														if ($qrLote['DAT_CADASTR'] != '') {
															$dat_cadastr = fnDataFull($qrLote['DAT_CADASTR']);
															$dat_agendamento_lote = fnDataFull($qrLote['DAT_AGENDAMENTO']);
															$urlAnexo = '<a href="' . $qrLote['DES_PATHARQ'] . '" download><span class="fa fa-download"></span></a>';

															if ($qrLote['LOG_ENVIO'] == 'P') {
																$loteSync = '<span class="fas fa-clock text-warning"></span>';
																$syncMsg = "Aguardando processamento";
															} else if ($qrLote['LOG_ENVIO'] == 'N') {
																$loteSync = '<span class="fas fa-calendar-check text-info"></span>';
																$syncMsg = "Enfileirado para envio";
															} else if ($qrLote['LOG_ENVIO'] == 'S') {
																$loteSync = '<span class="fas fa-check text-success"></span>';
																$syncMsg = "Enviado";
															} else {
																$loteSync = '<span class="fas fa-exclamation-triangle text-danger"></span>';
																$syncMsg = "Falha na geração do lote";
																$urlAnexo = "";
															}
														} else {
															$dat_cadastr = "";
															$loteSync = '<span class="fas fa-times text-danger"></span>';
															$syncMsg = "Sincronizando... aguarde.";
														}

														$sqlPers = "SELECT DES_PERSONA FROM PERSONA WHERE COD_PERSONA IN($qrLote[COD_PERSONAS])";
														$arrayPers = mysqli_query(connTemp($cod_empresa, ''), $sqlPers);
														$personas = "";

														// fnescreve($qrLote[COD_PERSONAS]);

														while ($qrPers = mysqli_fetch_assoc($arrayPers)) {
															$personas = $personas . $qrPers['DES_PERSONA'] . ", ";
														}

														$personas = rtrim(rtrim($personas, ' '), ',');

													?>

														<tr>
															<td class="text-center"><small><?= $urlAnexo ?></small></td>
															<td><small><small><?= $qrLote['COD_GERACAO'] ?></small>&nbsp;Geração do lote #<?= $qrLote['COD_CONTROLE'] ?>/<?= $qrLote['COD_LOTE'] ?></small></td>
															<td class="text-center"><small><?= $qrLote['NOM_TEMPLATE'] ?></small></td>
															<td class="text-center"><small><?= $personas ?></small></td>
															<td class="text-center"><small><?= fnValor($qrLote['QTD_LISTA'], 0) ?></small></td>
															<td><small><?= $dat_cadastr ?></small></td>
															<td><small><?= $dat_agendamento_lote ?></small></td>
															<td><small><?= $syncMsg ?></small></td>
															<td class="text-center"><small><?= $loteSync ?></small></td>
														</tr>

													<?php

														$tot_qtd += $qrLote['QTD_LISTA'];
													}

													?>

												</tbody>

												<!-- <tfoot>
																	<tr>
																		<td colspan="4"></td>
																		<td class="text-center"><b><?= fnValor($tot_qtd, 0) ?></b></td>
																		<td colspan="4"></td>
																	</tr>
																</tfoot> -->

											</table>

											<?php if ($linhas > 20) { ?>

												<div class="col-md-4 col-md-offset-4">

													<a href="javascript:void(0)" id="loadMore" class="btn btn-info btn-block" onclick="carregaMaisLotes()">Carregar mais</a>

												</div>


											<?php } ?>

										</div>

									</div>

								</div>

							<?php } ?>

							<!-- <div class="col-xs-12">
												<a href="javascript:void(0)" class="btn btn-primary col-md-4" id="CRIAR_CAMP" <?= $disable_criar_camp ?> onclick="wsIbope($(this).attr('id'))"><?= $txt_criar_camp ?></a>
												<div class="push10"></div>
												<a href="javascript:void(0)" class="btn btn-primary col-md-4" id="ENVIAR_MODEL" onclick="wsIbope($(this).attr('id'))">Enviar Modelo de e-Mail (WS Ibope)</a>
												<div class="push10"></div>
												<a href="javascript:void(0)" class="btn btn-primary col-md-4 exportarCSV" id="ENVIAR_LISTA">Enviar Lista de e-Mail (WS Ibope)</a>
												<div class="push30"></div>
												<a href="javascript:void(0)" class="btn btn-danger col-md-4" id="DISPARA_LISTA" onclick="wsIbope($(this).attr('id'))">Disparar Lista de e-Mail (WS Ibope)</a>
											</div>	
 -->
							<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?= $cod_empresa ?>">
							<input type="hidden" name="COD_CAMPANHA" id="COD_CAMPANHA" value="<?= $cod_campanha ?>">
							<input type="hidden" name="opcao" id="opcao" value="">
							<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
							<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">

							<div class="push30"></div>

							<div class="col-xs-12" id="load">

							</div>




						</form>



					</div>

					<div class="col-md-12">
						<a href="javascript:void(0)" class="btn btn-primary" onclick="proximoPasso()">Próximo Passo&nbsp;&nbsp;<span class="fal fa-arrow-right"></span></a>
					</div>
					<div class="push100"></div>

				</div>
				</div>
				<!-- fim Portlet -->
			</div>

	</div>

	<div class="push20"></div>

	<!-- <script src="js/plugins/ion.rangeSlider.js"></script> -->

	<script type="text/javascript">
		parent.$("#conteudoAba").css("height", (($(document).height()) + 100) + "px");
		//alert($(document).height());

		var cont = 0;

		$(function() {

			// $('#loadMore').click(function(){
			// 	$("#load").html('<div class="loading" style="width:100%"></div>')
			// });

			// $("#CAD").click(function(){
			// 	$("#relatorioConteudo").html('<div class="loading" style="width:100%"></div>')
			// });

			var cod_persona = '<?php echo $cod_persona; ?>';
			//alert(cod_persona);
			if (cod_persona != 0 && cod_persona != "") {
				//retorno combo multiplo - USUARIOS_ENV
				$("#formulario #COD_PERSONA").val('').trigger("chosen:updated");

				var sistemasUni = cod_persona;
				var sistemasUniArr = sistemasUni.split(',');
				//opções multiplas
				for (var i = 0; i < sistemasUniArr.length; i++) {
					$("#formulario #COD_PERSONA option[value=" + Number(sistemasUniArr[i]).toString() + "]").prop("selected", "true");
				}
				$("#formulario #COD_PERSONA").trigger('chosen:updated');

			}

		});

		function proximoPasso() {

			var log_processa = "<?= $log_processa ?>";

			if (log_processa == 'S') {

				parent.$.alert({
					title: "Aviso",
					content: "É possível que os resultados gerados estejam zerados caso o processamento da campanha seja recente.",
					buttons: {
						"OK": {
							btnClass: 'btn-primary',
							action: function() {
								parent.$('#RESULTADOS').click();
							}
						}
					},
					backgroundDismiss: true
				});

			} else {

				parent.$.alert({
					title: "Aviso",
					content: "A campanha não foi processada, e nenhum resultado estará disponível. Deseja prosseguir?",
					type: 'orange',
					buttons: {
						"PROSSEGUIR": {
							btnClass: 'btn-primary',
							action: function() {
								parent.$('#RESULTADOS').click();
							}
						},
						"CANCELAR": {
							btnClass: 'btn-default',
							action: function() {

							}
						}
					},
					backgroundDismiss: true
				});

			}

		}

		function carregaMaisLotes() {

			cont += 20;

			if (cont >= "<?= $linhas ?>") {
				$('#loadMore').addClass('disabled');
				$('#loadMore').text('Nada a carregar');
			}

			$.ajax({
				type: "GET",
				url: "ajxAtivacaoSms_V2.php?acao=loadMore&itens=" + cont + "&id=<?= fnEncode($cod_empresa) ?>&idc=<?= fnEncode($cod_campanha) ?>",
				beforeSend: function() {
					$('#loadMore').text('Carregando...');
				},
				success: function(data) {
					$('#loadMore').text('Carregar mais');
					$('#relatorioConteudo').append(data);
				},
				error: function() {
					alert('Erro ao carregar...');
				}
			});

		}

		function cancelarCampanha() {

			parent.$.alert({
				title: "Alerta",
				content: "Esta campanha SERÁ INUTILIZADA, e todos os disparos agendados serão CANCELADOS. Os créditos utilizados serão estornados. Deseja prosseguir?",
				type: 'orange',
				buttons: {
					"CANCELAR": {
						btnClass: 'btn-default',
						action: function() {

						}
					},
					"PROSSEGUIR": {
						btnClass: 'btn-warning',
						action: function() {

							$.ajax({
								type: "GET",
								url: "ajxAtivacaoSms_V2.php?acao=cancelar&id=<?= fnEncode($cod_empresa) ?>&idc=<?= fnEncode($cod_campanha) ?>",
								beforeSend: function() {
									$('#ENV').text('Processando...');
									$('#ENV').addClass('disabled');
									$('#blocker').show();
								},
								success: function(data) {
									window.location.href = "action.php?mod=<?php echo fnEncode(1659) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idc=<?= fnEncode($cod_campanha) ?>&pop=true";
								},
								error: function() {
									alert('Erro ao carregar...');
								}
							});

						}
					},
				},
				backgroundDismiss: true
			});

		}

		function verificaFila() {

			$.ajax({
				type: "GET",
				url: "ajxAtivacaoSms_V2.php?acao=verificaFila&id=<?= fnEncode($cod_empresa) ?>&idc=<?= fnEncode($cod_campanha) ?>",
				beforeSend: function() {
					$('#blocker').show();
				},
				success: function(data) {
					$('#blocker').hide();

					if (data > 0) {

						parent.$.alert({
							title: "Alerta",
							content: "A campanha que deseja cancelar possui " + data + " agendados para hoje. Esta ação é PERMANENTE e IRREVERSÍVEL. Tem certeza que deseja prosseguir?",
							type: 'red',
							buttons: {
								"CANCELAR": {
									btnClass: 'btn-default',
									action: function() {

									}
								},
								"ESTOU CIENTE E DESEJO PROSSEGUIR": {
									btnClass: 'btn-warning',
									action: function() {

										cancelarCampanha();

									}
								},
							},
							backgroundDismiss: true
						});

					} else {
						cancelarCampanha();
					}
				},
				error: function() {
					alert('Erro ao carregar...');
				}
			});

		}

		function enviarLista() {

			$.ajax({
				type: "POST",
				url: "ajxEnvioListaSms_V2.php?id=<?= fnEncode($cod_empresa) ?>&idc=<?= fnEncode($cod_campanha) ?>&opcao=envio&tipo=processar",
				beforeSend: function() {
					$('#ENV').text('Processando...');
					$('#ENV').addClass('disabled');
					$('#blocker').show();
				},
				success: function(data) {
					console.log(data);
					if (data.trim() == "Necessária aprovação para o envio da lista") {
						$('#ENV').html("<span class='fa fa-times'></span>&nbsp;" + data).removeClass('disabled').removeClass('btn-primary').addClass('btn-danger');
					} else {
						// $('#ENV').html("<span class='fa fa-check'></span>&nbsp;Campanha Processada").removeClass('btn-info').addClass('btn-success');
						window.location.href = "action.php?mod=<?php echo fnEncode(1659) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idc=<?= fnEncode($cod_campanha) ?>&alert=true&pop=true";
						console.log(data);
					}
					// $('#relatorioAjax').html(data);
				},
				error: function() {
					alert('Erro ao carregar...');
					// console.log(data);
				}
			});

		}

		function retornaForm(index) {
			$("#formulario #COD_GRUPOTR").val($("#ret_COD_GRUPOTR_" + index).val());
			$("#formulario #DES_GRUPOTR").val($("#ret_DES_GRUPOTR_" + index).val());
			$('#formulario').validator('validate');
			$("#formulario #hHabilitado").val('S');
		}

		function calculaPeriodo() {
			periodo_hrs = '',
				qtd_lote = $('#QTD_LOTE').val(),
				des_interval = $('#DES_INTERVAL').val(),
				dat_iniagendamento = "<?= $dat_iniagendamento ?>",
				dat_fimagendamento = "<?= $dat_fimagendamento ?>";

			if (qtd_lote != "" && des_interval != "") {

				periodo_hrs = qtd_lote * des_interval;

				$.ajax({
					type: "POST",
					url: "ajxCalculaPeriodoLotes.php?id=<?= fnEncode($cod_empresa) ?>&idc=<?= fnEncode($cod_campanha) ?>",
					data: {
						PERIODO_HRS: periodo_hrs,
						DAT_INIAGENDAMENTO: dat_iniagendamento,
						DAT_FIMAGENDAMENTO: dat_fimagendamento,
						DES_INTERVAL: des_interval
					},
					beforeSend: function() {
						$("#projecaoData").html('<div class="loading" style="width:100%"></div>');
					},
					success: function(data) {
						$("#projecaoData").html(data);
					}
				});

			}
		}
	</script>