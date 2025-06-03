<?php

$hashLocal = mt_rand();

$mostraBtnTkt = "style='display:none'";
$mostraBtnCamp = "style='display:none'";
$des_programa = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_empresa = fnLimpaCampo($_REQUEST['COD_EMPRESA']);
		$nom_empresa = fnLimpaCampo($_REQUEST['NOM_EMPRESA']);
		$nom_fantasi = fnLimpaCampo($_REQUEST['NOM_FANTASI']);
		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
		if (empty(@$_REQUEST['LOG_PROCTKT'])) {
			$log_proctkt = 'N';
		} else {
			$log_proctkt = 'S';
		}

		$nom_abvempresa = fnLimpaCampo($_REQUEST['NOM_ABVEMPRESA']);

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		if ($opcao != '') {

			//mensagem de retorno
			switch ($opcao) {
				case 'CAD':

					//INSERE CATEGORIAS TICKET
					if ($log_proctkt == 'S') {

						$sqlBuscaCategor = "SELECT DES_CATEGOR FROM CATEGORIATKT WHERE COD_EMPRESA = $cod_empresa";
						$query = mysqli_query(connTemp($cod_empresa, ''), $sqlBuscaCategor);
						$numLinhas = mysqli_num_rows($query);
						$sqlInsertCat = "";
						if ($numLinhas == 0 || $numLinhas < 5) {
							if ($numLinhas != 0) {
								$nomCategoria = "";
								while ($qrResult = mysqli_fetch_assoc($query)) {
									$nomCategoria .= $qrResult['DES_CATEGOR'] . ',';
								}
							}

							if (empty($nomCategoria) || strpos($nomCategoria, 'Higiene Pessoal') === false) {
								$sqlInsertCat .= "CALL SP_ALTERA_CATEGORIATKT ( '0', '$cod_empresa', 'Higiene Pessoal', '', '', 'N', '$cod_usucada', 'CAD' );";
							}
							if (empty($nomCategoria) || strpos($nomCategoria, 'Saúde') === false) {
								$sqlInsertCat .= "CALL SP_ALTERA_CATEGORIATKT ( '0', '$cod_empresa', 'Saúde', '', '', 'N', '$cod_usucada', 'CAD' );";
							}
							if (empty($nomCategoria) || strpos($nomCategoria, 'Beleza') === false) {
								$sqlInsertCat .= "CALL SP_ALTERA_CATEGORIATKT ( '0', '$cod_empresa', 'Beleza', '', '', 'N', '$cod_usucada', 'CAD' );";
							}
							if (empty($nomCategoria) || strpos($nomCategoria, 'Vitaminas') === false) {
								$sqlInsertCat .= "CALL SP_ALTERA_CATEGORIATKT ( '0', '$cod_empresa', 'Vitaminas', '', '', 'N', '$cod_usucada', 'CAD' );";
							}
							if (empty($nomCategoria) || strpos($nomCategoria, 'Cuidados') === false) {
								$sqlInsertCat .= "CALL SP_ALTERA_CATEGORIATKT ( '0', '$cod_empresa', 'Cuidados', '', '', 'N', '$cod_usucada', 'CAD' );";
							}

							if (!empty($sqlInsertCat)) {
								$query = mysqli_multi_query(connTemp($cod_empresa, ''), $sqlInsertCat);

								$sqlTemplates = "SELECT COD_CATEGORTKT FROM CATEGORIATKT WHERE COD_EMPRESA = $cod_empresa";
								$query = mysqli_query(connTemp($cod_empresa, ''), $sqlTemplates);
								$codCategorTkt = "";
								while ($qrResult = mysqli_fetch_assoc($query)) {
									$codCategorTkt .= $qrResult['COD_CATEGORTKT'] . ",";
								}
								$codCategorTkt = rtrim($codCategorTkt, ',');

								$sqlAudit = "UPDATE AUDITORIA_EMPRESA SET 
								COD_CATEGORTKT = '$codCategorTkt',
								LOG_PROCTKT = 'S'
								WHERE COD_EMPRESA = $cod_empresa";

								$arrayProcAudit = mysqli_query($connAdm->connAdm(), trim($sqlAudit));
							}
						}

						//FIM INSERE CATEGORIA TICKET

						//INICIA TEMPLATE TICKET
						$sqlBusca = "SELECT * FROM TEMPLATE WHERE COD_EMPRESA = $cod_empresa";
						$query = mysqli_query(connTemp($cod_empresa, ''), $sqlBusca);
						$numLinhas = mysqli_num_rows($query);
						if ($numLinhas == 0) {
							$insertTempTkt = "CALL SP_ALTERA_TEMPLATE ( '0', '$cod_empresa', 'S', 'Ticket de Ofertas', '', '', '', '$cod_usucada', 'CAD' );";
							$query = mysqli_query(connTemp($cod_empresa, ''), $insertTempTkt);
							if ($query) {
								$sqlTemplates = "SELECT COD_TEMPLATE FROM TEMPLATE WHERE COD_EMPRESA = $cod_empresa";
								$query = mysqli_query(connTemp($cod_empresa, ''), $sqlTemplates);
								$codTemplate = "";
								if ($qrResult = mysqli_fetch_assoc($query)) {
									$codTemplate = $qrResult['COD_TEMPLATE'];
								}

								$sqlAudit = "UPDATE AUDITORIA_EMPRESA SET 
								COD_TEMPLATETKT = $codTemplate
								WHERE COD_EMPRESA = $cod_empresa";

								$arrayProcAudit = mysqli_query($connAdm->connAdm(), trim($sqlAudit));
							}
						}

						//MONTA TEMPLATE
						$sqlBusca = "SELECT * FROM TEMPLATE WHERE COD_EMPRESA = $cod_empresa";
						$query = mysqli_query(connTemp($cod_empresa, ''), $sqlBusca);
						$sqltemp = "";
						if ($qrResult = mysqli_fetch_assoc($query)) {
							$cod_template = $qrResult['COD_TEMPLATE'];
							$sqlBusca = "SELECT COD_BLTEMPL FROM MODELOTEMPLATETKT WHERE COD_EMPRESA = $cod_empresa AND COD_TEMPLATE = $cod_template";
							$query = mysqli_query(connTemp($cod_empresa, ''), $sqlBusca);
							$numLinhas = mysqli_num_rows($query);
							if ($numLinhas == 0 || $numLinhas < 8) {
								if ($numLinhas != 0) {
									$cod_bltempl = "";
									while ($qrResult = mysqli_fetch_assoc($query)) {
										$cod_bltempl .= $qrResult['COD_BLTEMPL'] . ',';
									}
								}

								if (empty($cod_bltempl) || strpos($cod_bltempl, '17') === false) {
									$sqltemp .= "CALL SP_ALTERA_MODELOTEMPLATETKT(0, '$cod_template', '$cod_empresa', '17', ' ', '$cod_usucada', 'CAD' );";
								}

								if (empty($cod_bltempl) || strpos($cod_bltempl, '6') === false) {
									$sqltemp .= "CALL SP_ALTERA_MODELOTEMPLATETKT(0, '$cod_template', '$cod_empresa', '6', ' ', '$cod_usucada', 'CAD' );";
								}

								if (empty($cod_bltempl) || strpos($cod_bltempl, '23') === false) {
									$sqltemp .= "CALL SP_ALTERA_MODELOTEMPLATETKT(0, '$cod_template', '$cod_empresa', '23', ' ', '$cod_usucada', 'CAD' );";
								}

								if (empty($cod_bltempl) || strpos($cod_bltempl, '1') === false) {
									$sqltemp .= "CALL SP_ALTERA_MODELOTEMPLATETKT(0, '$cod_template', '$cod_empresa', '1', ' ', '$cod_usucada', 'CAD' );";
								}

								if (empty($cod_bltempl) || strpos($cod_bltempl, '8') === false) {
									$sqltemp .= "CALL SP_ALTERA_MODELOTEMPLATETKT(0, '$cod_template', '$cod_empresa', '8', ' ', '$cod_usucada', 'CAD' );";
								}

								if (empty($cod_bltempl) || strpos($cod_bltempl, '18') === false) {
									$sqltemp .= "CALL SP_ALTERA_MODELOTEMPLATETKT(0, '$cod_template', '$cod_empresa', '18', ' ', '$cod_usucada', 'CAD' );";
								}

								if (empty($cod_bltempl) || strpos($cod_bltempl, '7') === false) {
									$sqltemp .= "CALL SP_ALTERA_MODELOTEMPLATETKT(0, '$cod_template', '$cod_empresa', '7', ' ', '$cod_usucada', 'CAD' );";
								}

								if (empty($cod_bltempl) || strpos($cod_bltempl, '4') === false) {
									$sqltemp .= "CALL SP_ALTERA_MODELOTEMPLATETKT(0, '$cod_template', '$cod_empresa', '4', ' ', '$cod_usucada', 'CAD' );";
								}

								if (!empty($sqltemp)) {
									$arrayInsert = explode(";", $sqltemp);
									$arrayInsert = array_filter($arrayInsert);
									foreach ($arrayInsert as $sqltemp) {
										mysqli_query(conntemp($cod_empresa, ''), $sqltemp);
										// fnEscreve($sqltemp);
									}


									if ($query) {
										$sqlTemplates = "SELECT COD_BLTEMPL FROM MODELOTEMPLATETKT WHERE COD_EMPRESA = $cod_empresa AND COD_TEMPLATE = $cod_template";
										$query = mysqli_query(connTemp($cod_empresa, ''), $sqlTemplates);
										$codBlTempl = "";
										while ($qrResult = mysqli_fetch_assoc($query)) {
											$codBlTempl .= $qrResult['COD_BLTEMPL'] . ",";
										}

										$sqlAudit = "UPDATE AUDITORIA_EMPRESA SET 
										COD_BLTEMPL = '$codBlTempl'
										WHERE COD_EMPRESA = $cod_empresa";

										$arrayProcAudit = mysqli_query($connAdm->connAdm(), trim($sqlAudit));
									}
								}
							}
						}

						//FIM MONTA TEMPLATE

						//INSERE CONFIGURAÇÕES TICKET

						$sqlBusca = "SELECT * FROM CONFIGURACAO_TICKET WHERE COD_EMPRESA = $cod_empresa";
						$query = mysqli_query(connTemp($cod_empresa, ''), $sqlBusca);
						$numLinhas = mysqli_num_rows($query);

						if ($numLinhas == 0) {

							$sqlBusca = "SELECT * FROM TEMPLATE WHERE COD_EMPRESA = $cod_empresa";
							$query = mysqli_query(connTemp($cod_empresa, ''), $sqlBusca);
							if ($qrResult = mysqli_fetch_assoc($query)) {
								$cod_template = $qrResult['COD_TEMPLATE'];

								$log_retws = "N";

								$integradoraRetWs = ['184', '310', '13', '218', '34'];
								$busca = "SELECT COD_INTEGRADORA FROM EMPRESAS WHERE COD_EMPRESA = $cod_empresa";
								$qr = mysqli_query($connAdm->connAdm(), $busca);
								$res = mysqli_fetch_assoc($qr);

								if (in_array($res['COD_INTEGRADORA'], $integradoraRetWs)) {
									$log_retws = "S";
								}

								$sqlInsertConfig = "CALL SP_ALTERA_CONFIGURACAO_TICKET ( '0', '$cod_empresa', 'S', '$cod_template', '4', '1', '20;120', '20', '120', '0', '0', '5', 'S', '$cod_usucada', '0', '0', '$log_retws', 'Sistema PDV', '1', '1', 'CAD' )";
								$query = mysqli_query(connTemp($cod_empresa, ''), $sqlInsertConfig);
								if ($query) {
									$sqlTemplates = "SELECT COD_CONFIGU FROM CONFIGURACAO_TICKET WHERE COD_EMPRESA = $cod_empresa";
									$query = mysqli_query(connTemp($cod_empresa, ''), $sqlTemplates);
									$codConfig = "";
									if ($qrResult = mysqli_fetch_assoc($query)) {
										$codConfig = $qrResult['COD_CONFIGU'];

										$sqlAudit = "UPDATE AUDITORIA_EMPRESA SET 
										COD_CONFIGTKT = $codConfig
										WHERE COD_EMPRESA = $cod_empresa";

										$arrayProcAudit = mysqli_query($connAdm->connAdm(), trim($sqlAudit));
									}
								}
							}
						}
					}
					//FIM TEMPLATE TICKET

					// INSERE TEMPLATES SMS
					$buscaTemplate = "SELECT NOM_TEMPLATE FROM TEMPLATE_SMS WHERE COD_EMPRESA = $cod_empresa";
					$query = mysqli_query(connTemp($cod_empresa, ''), $buscaTemplate);
					$numLinhas = mysqli_num_rows($query);

					$nomTemplate = "";
					$sqlInsertTemplate = "";
					if ($numLinhas == 0 || $numLinhas < 8) {
						if ($numLinhas != 0) {
							while ($qrResult = mysqli_fetch_assoc($query)) {
								$nomTemplate .= $qrResult['NOM_TEMPLATE'] . ',';
							}
						}

						$nomTemplate = rtrim($nomTemplate, ',');

						if (empty($nomTemplate) || strpos($nomTemplate, $cod_empresa . ' - Template AUTOM SMS Token') === false) {
							// BUSCA DOMINIO HOTSITE
							$sqlbusca = "SELECT DES_DOMINIO, COD_DOMINIO FROM SITE_EXTRATO WHERE COD_EMPRESA = $cod_empresa";
							$query = mysqli_query(connTemp($cod_empresa, ''), $sqlbusca);
							$dominio = "";
							if ($qrResult = mysqli_fetch_assoc($query)) {
								$des_dominio = $qrResult['DES_DOMINIO'];
								switch ($qrResult['COD_DOMINIO']) {
									case '1':
										$dominio = $des_dominio . ".mais.cash";
										break;

									case '2':
										$dominio = $des_dominio . ".fidelidade.mk";
										break;
								}
							}

							$sqlInsertTemplate = "INSERT INTO TEMPLATE_SMS( COD_EMPRESA, LOG_ATIVO, NOM_TEMPLATE, ABV_TEMPLATE, DES_TEMPLATE, COD_USUCADA )VALUES($cod_empresa, 'S', '$cod_empresa - Template AUTOM SMS Token', '', '" . strtoupper($nom_abvempresa) . ": <#TOKEN> Ao informar esse token ao atendente vc confirma estar de acordo c/ nossas politicas disponiveis em: $dominio/#info', $cod_usucada );";
							$query = mysqli_query(connTemp($cod_empresa, ''), $sqlInsertTemplate);
						}

						if (empty($nomTemplate) || strpos($nomTemplate, $cod_empresa . ' - Template AUTOM SMS Boas Vindas') === false) {
							$sqlInsertTemplate = "INSERT INTO TEMPLATE_SMS( COD_EMPRESA, LOG_ATIVO, NOM_TEMPLATE, ABV_TEMPLATE, DES_TEMPLATE, COD_USUCADA )VALUES($cod_empresa, 'S', '$cod_empresa - Template AUTOM SMS Boas Vindas', '', '" . strtoupper($nom_abvempresa) . ": <#NOME> parabens por se cadastrar! Agora vc acumula cashback em todas as compras e recebe ofertas exclusivas!', $cod_usucada );";
							$query = mysqli_query(connTemp($cod_empresa, ''), $sqlInsertTemplate);
						}

						if (empty($nomTemplate) || strpos($nomTemplate, $cod_empresa . ' - Template AUTOM SMS Niver') === false) {
							$sqlInsertTemplate = "INSERT INTO TEMPLATE_SMS( COD_EMPRESA, LOG_ATIVO, NOM_TEMPLATE, ABV_TEMPLATE, DES_TEMPLATE, COD_USUCADA )VALUES($cod_empresa, 'S', '$cod_empresa - Template AUTOM SMS Niver', '', '" . strtoupper($nom_abvempresa) . ":<#NOME> parabens por mais um ano de vida. Estamos felizes por voce e desejamos muita saude e felicidades!', $cod_usucada );";
							$query = mysqli_query(connTemp($cod_empresa, ''), $sqlInsertTemplate);
						}

						if (empty($nomTemplate) || strpos($nomTemplate, $cod_empresa . ' - Template AUTOM SMS Expirar') === false) {
							$sqlInsertTemplate = "INSERT INTO TEMPLATE_SMS( COD_EMPRESA, LOG_ATIVO, NOM_TEMPLATE, ABV_TEMPLATE, DES_TEMPLATE, COD_USUCADA )VALUES($cod_empresa, 'S', '$cod_empresa - Template AUTOM SMS Expirar', '', '" . strtoupper($nom_abvempresa) . ": Nao perca tempo, <#NOME> seu saldo e R$ <#SALDO> e parte dele vence em 7 dias. Corra e venha aproveitar seu credito!', $cod_usucada );";
							$query = mysqli_query(connTemp($cod_empresa, ''), $sqlInsertTemplate);
						}

						if (empty($nomTemplate) || strpos($nomTemplate, $cod_empresa . ' - Template AUTOM SMS Inativos 2a6') === false) {
							$sqlInsertTemplate = "INSERT INTO TEMPLATE_SMS( COD_EMPRESA, LOG_ATIVO, NOM_TEMPLATE, ABV_TEMPLATE, DES_TEMPLATE, COD_USUCADA )VALUES($cod_empresa, 'S', '$cod_empresa - Template AUTOM SMS Inativos 2a6', '', '" . strtoupper($nom_abvempresa) . ": <#NOME> como vai voce? Nao esqueca que aqui em todas as suas compras voce recebe dinheiro de volta!', $cod_usucada );";
							$query = mysqli_query(connTemp($cod_empresa, ''), $sqlInsertTemplate);
						}

						if (empty($nomTemplate) || strpos($nomTemplate, $cod_empresa . ' - Template AUTOM SMS Transacional') === false) {
							$sqlInsertTemplate = "INSERT INTO TEMPLATE_SMS( COD_EMPRESA, LOG_ATIVO, NOM_TEMPLATE, ABV_TEMPLATE, DES_TEMPLATE, COD_USUCADA )VALUES($cod_empresa, 'S', '$cod_empresa - Template AUTOM SMS Transacional', '', '" . strtoupper($nom_abvempresa) . ": <#NOME> obrigada pela preferencia, voce ja tem R$ <#SALDO> de saldo para abater nas proximas compras', $cod_usucada );";
							$query = mysqli_query(connTemp($cod_empresa, ''), $sqlInsertTemplate);
						}

						if (empty($nomTemplate) || strpos($nomTemplate, $cod_empresa . ' - Template AUTOM Nivers club 3B5') === false) {
							$sqlInsertTemplate = "INSERT INTO TEMPLATE_SMS( COD_EMPRESA, LOG_ATIVO, NOM_TEMPLATE, ABV_TEMPLATE, DES_TEMPLATE, COD_USUCADA )VALUES($cod_empresa, 'S', '$cod_empresa - Template AUTOM Nivers club 3B5', '', '" . strtoupper($nom_abvempresa) . ": <#NOME> este mes celebramos mais um ano de sua amizade e fidelidade. Muito obrigada pela confianca!', $cod_usucada );";
							$query = mysqli_query(connTemp($cod_empresa, ''), $sqlInsertTemplate);
						}

						if (empty($nomTemplate) || strpos($nomTemplate, $cod_empresa . ' - Template AUTOM SMS Resgate') === false) {
							$sqlInsertTemplate = "INSERT INTO TEMPLATE_SMS( COD_EMPRESA, LOG_ATIVO, NOM_TEMPLATE, ABV_TEMPLATE, DES_TEMPLATE, COD_USUCADA )VALUES($cod_empresa, 'S', '$cod_empresa - Template AUTOM SMS Resgate', '', '" . strtoupper($nom_abvempresa) . ": <#NOME> parabens hoje vc usou <#RESGATE> de seus creditos em nossa loja, estamos felizes! Continue aproveitando!', $cod_usucada );";
							$query = mysqli_query(connTemp($cod_empresa, ''), $sqlInsertTemplate);
						}

						// fnEscreve($sqlInsertTemplate);

						if (!empty($sqlInsertTemplate)) {

							$sqlTemplates = "SELECT COD_TEMPLATE FROM TEMPLATE_SMS WHERE COD_EMPRESA = $cod_empresa";
							$query = mysqli_query(connTemp($cod_empresa, ''), $sqlTemplates);
							$codTemplate = "";
							while ($qrResult = mysqli_fetch_assoc($query)) {
								$codTemplate .= $qrResult['COD_TEMPLATE'] . ",";
							}
							$codTemplate = rtrim($codTemplate, ',');

							$sqlAudit = "UPDATE AUDITORIA_EMPRESA SET 
							COD_TEMPLATESMS = '$codTemplate'
							WHERE COD_EMPRESA = $cod_empresa";

							$arrayProcAudit = mysqli_query($connAdm->connAdm(), trim($sqlAudit));
						}
					}

					// FIM INSERE TEMPLATES SMS

					// INSERE CAMPANHAS
					$nomesCampanhas = [];
					$sqlBusca = "SELECT * FROM CAMPANHA WHERE COD_EMPRESA = $cod_empresa";
					$queryBusca = mysqli_query(connTemp($cod_empresa, ''), $sqlBusca);
					$numLinhas = mysqli_num_rows($queryBusca);
					if ($numLinhas == 0 || $numLinhas < 10) {
						if ($numLinhas > 0) {
							while ($qrResult = mysqli_fetch_assoc($queryBusca)) {
								$nomesCampanhas[] = $qrResult['DES_CAMPANHA'];
							}
						}

						//CAMPANHA CASHBACK
						if (isset($_POST['LOG_CASHBACK'])) {
							if (empty($nomesCampanhas) || !in_array('CashBack', $nomesCampanhas)) {
								$sqlCamp = "CALL SP_ALTERA_CAMPANHA ( '0', '$cod_empresa', '9999', 'S', 'CashBack', '', 'fal fa-money-bill-alt', '0fd44a', 'S', '', '$cod_usucada', '13', 'S', NOW(), NULL, '17:08', '', 'S', 'S', 'S', 'S', 'S', 'S', 'S', 'S', 'CAD' )";
								mysqli_query(connTemp($cod_empresa, ''), $sqlCamp);

								//BUSCA CAMPANHA CASHBACK
								$busca = "SELECT COD_CAMPANHA FROM CAMPANHA WHERE COD_EMPRESA = $cod_empresa AND DES_CAMPANHA = 'CashBack'";
								$query = mysqli_query(connTemp($cod_empresa, ''), $busca);
								if ($qrResult = mysqli_fetch_assoc($query)) {
									$codCampanha = $qrResult['COD_CAMPANHA'];

									//BUSCA PERSONA
									$buscaPersona = "SELECT COD_PERSONA FROM PERSONA WHERE COD_EMPRESA = $cod_empresa AND DES_PERSONA = 'Fidelidade (acesso restrito)'";
									$query = mysqli_query(connTemp($cod_empresa, ''), $buscaPersona);
									$qrResultado = mysqli_fetch_assoc($query);
									$cod_persona = $qrResultado['COD_PERSONA'];

									$sql = "CALL SP_ALTERA_CAMPANHAREGRA ( '$codCampanha', '$cod_persona', '0.00', '1', '1', '$cod_usucada', 'Cashback', '1', 'N', 'N', '$cod_empresa', 'N', '', '0', '0', '0', '', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0' )";
									mysqli_query(connTemp($cod_empresa, ''), $sql);
								}
							}
						}

						//CAMPANHA CASHBACK MELHOR IDADE
						if (isset($_POST['LOG_CASHBACKMLIDA'])) {
							if (empty($nomesCampanhas) || !in_array('CashBack Melhor Idade', $nomesCampanhas)) {
								$sqlCamp = "CALL SP_ALTERA_CAMPANHA ( '0', '$cod_empresa', '9999', 'S', 'CashBack Melhor Idade', '', 'fal fa-money-bill-alt', '0fd44a', 'S', '', '$cod_usucada', '13', 'S', NOW(), NULL, '17:08', '', 'S', 'S', 'S', 'S', 'S', 'S', 'S', 'S', 'CAD' )";
								mysqli_query(connTemp($cod_empresa, ''), $sqlCamp);

								//BUSCA CAMPANHA CashBack Melhor Idade
								$busca = "SELECT COD_CAMPANHA FROM CAMPANHA WHERE COD_EMPRESA = $cod_empresa AND DES_CAMPANHA = 'CashBack Melhor Idade'";
								$query = mysqli_query(connTemp($cod_empresa, ''), $busca);
								if ($qrResult = mysqli_fetch_assoc($query)) {
									$codCampanha = $qrResult['COD_CAMPANHA'];

									//BUSCA PERSONA
									$buscaPersona = "SELECT COD_PERSONA FROM PERSONA WHERE COD_EMPRESA = $cod_empresa AND DES_PERSONA = 'Fidelidade (acesso restrito)'";
									$query = mysqli_query(connTemp($cod_empresa, ''), $buscaPersona);
									$qrResultado = mysqli_fetch_assoc($query);
									$cod_persona = $qrResultado['COD_PERSONA'];

									$sql = "CALL SP_ALTERA_CAMPANHAREGRA ( '$codCampanha', '$cod_persona', '0.00', '1', '1', '$cod_usucada', 'CashBack Melhor Idade', '1', 'N', 'N', '$cod_empresa', 'N', '', '0', '0', '0', '', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0' )";
									mysqli_query(connTemp($cod_empresa, ''), $sql);
								}
							}
						}

						//CAMPANHA AUTOM SMS Expirar
						if (isset($_POST['LOG_AUTSMSEXPIRA'])) {
							if (empty($nomesCampanhas) || !in_array($cod_empresa . ' - AUTOM SMS Expirar', $nomesCampanhas)) {
								$sqlCamp = "CALL SP_ALTERA_CAMPANHA ( '0', '$cod_empresa', '9999', 'N', '$cod_empresa - AUTOM SMS Expirar', '', 'fas fa-cogs', 'ff030b', 'N', '', '$cod_usucada', '21', 'N', NOW(), '2030-12-31 00:00:00', '08:51', '23:59', 'N', 'S', 'S', 'S', 'S', 'S', 'S', 'S', 'CAD' );";
								$query = mysqli_query(connTemp($cod_empresa, ''), $sqlCamp);

								if ($query) {

									$sql = "SELECT COD_CAMPANHA FROM CAMPANHA WHERE COD_EMPRESA = $cod_empresa AND DES_CAMPANHA = '$cod_empresa - AUTOM SMS Expirar'";
									$query = mysqli_query(connTemp($cod_empresa, ''), $sql);

									if ($qrResult = mysqli_fetch_assoc($query)) {
										$codCampanha = $qrResult['COD_CAMPANHA'];

										//INSERE GATILHO
										$sql = "INSERT INTO GATILHO_SMS( COD_EMPRESA, COD_CAMPANHA, TIP_GATILHO, TIP_CONTROLE, TIP_MOMENTO, DES_PERIODO, DES_PERIODOMIN, DES_PERIODOMAX, HOR_ESPECIF, TOT_SALDOMIN, DIAS_ANTECED, DIAS_HIST, DAT_INI, HOR_INI, LOG_DOMINGO, LOG_SEGUNDA, LOG_TERCA, LOG_QUARTA, LOG_QUINTA, LOG_SEXTA, LOG_SABADO, COD_USUARIO, LOG_STATUS ) VALUES( $cod_empresa, $codCampanha, 'credExp', '7', '9', '1', '0', '0', '0', 1.00, '7', '0', null, '', 'N', 'N', 'N', 'N', 'N', 'N', 'N', '$cod_usucada', 'S' );INSERT INTO CONTROLE_SCHEDULE_SMS( COD_EMPRESA, COD_CAMPANHA, TIP_GATILHO, COD_USUCADA ) VALUES( $cod_empresa, $codCampanha, 'credExp', $cod_usucada );DELETE FROM AGENDA_SMS WHERE COD_EMPRESA = $cod_empresa AND COD_CAMPANHA = $codCampanha AND DAT_INIAGENDAMENTO >= NOW();";
										mysqli_multi_query(connTemp($cod_empresa, ''), $sql);
										// fnEscreve($sql);

										//INSERE BLOCO TEMPLATE SMS
										$sql = "INSERT INTO TEMPLATE_AUTOMACAO_SMS( COD_EMPRESA, COD_CAMPANHA, COD_BLTEMPL) VALUES( $cod_empresa, $codCampanha, 25 );";
										// fnEscreve($sql);
										mysqli_query(connTemp($cod_empresa, ''), $sql);

										//INSERE MENSAGEM
										//BUSCA TEMPLATE MSG
										$buscaTemplate = "SELECT COD_TEMPLATE FROM TEMPLATE_SMS WHERE COD_EMPRESA = $cod_empresa AND NOM_TEMPLATE = '$cod_empresa - Template AUTOM SMS Expirar'";
										// fnEscreve($sql);
										$query = mysqli_query(connTemp($cod_empresa, ''), $buscaTemplate);
										$qrResult = mysqli_fetch_assoc($query);
										$cod_template = $qrResult['COD_TEMPLATE'];

										//BUSCA BLOCO TEMPLATE
										$buscaBloco = "SELECT COD_TEMPLATE FROM TEMPLATE_AUTOMACAO_SMS WHERE COD_EMPRESA = $cod_empresa AND COD_CAMPANHA = $codCampanha AND COD_BLTEMPL = 25";
										// fnEscreve($sql);
										$query = mysqli_query(connTemp($cod_empresa, ''), $buscaBloco);
										$qrResult = mysqli_fetch_assoc($query);
										$cod_bloco = $qrResult['COD_TEMPLATE'];

										if ($cod_template != "" && $cod_bloco != "") {
											$sql = "INSERT INTO MENSAGEM_SMS( COD_TEMPLATE_SMS, COD_TEMPLATE_BLOCO, COD_EMPRESA, COD_CAMPANHA, NUM_ORDENAC, LOG_PRINCIPAL, COD_USUCADA ) VALUES( $cod_template, $cod_bloco, $cod_empresa, $codCampanha, (SELECT NUM_ORDENAC FROM TEMPLATE_AUTOMACAO_SMS WHERE COD_TEMPLATE = $cod_bloco), 'N', $cod_usucada );";
											// fnEscreve($sql);

											mysqli_query(connTemp($cod_empresa, ''), $sql);
										}

										//INSERE LISTA DE ENVIO
										$buscaPersona = "SELECT COD_PERSONA FROM PERSONA WHERE COD_EMPRESA = $cod_empresa AND LOG_ATIVO = 'S' AND DES_PERSONA = 'Fidelidade (acesso restrito)'";
										$query = mysqli_query(connTemp($cod_empresa, ''), $buscaPersona);
										if ($qrResult = mysqli_fetch_assoc($query)) {
											$codPersona = $qrResult['COD_PERSONA'];

											$sql = "CALL SP_RELAT_SMS_CLIENTE($cod_empresa, $codCampanha, '0', '$codPersona', 'ANV')";
											mysqli_query(connTemp($cod_empresa, ''), $sql);
											$sql2 = "INSERT INTO SMS_PARAMETROS( COD_EMPRESA, COD_CAMPANHA, COD_PERSONAS, PCT_RESERVA, TOT_PERSONAS, CLIENTES_UNICOS, CLIENTES_UNICOS_SMS, CLIENTES_UNICO_PERC, TOTAL_CLIENTE_SMS_NAO, CLIENTES_OPTOUT, CLIENTES_BLACKLIST, COD_USUCADA ) VALUES( $cod_empresa, $codCampanha, '$codPersona', '0', '0', '0', '0', '0', '0', '0', '0', $cod_usucada );";
											mysqli_query(connTemp($cod_empresa, ''), $sql2);
										}
									}
								}
							}
						}

						//CAMPANHA AUTOM SMS TK LGPD
						if (isset($_POST['LOG_AUTSMSTKLGPD'])) {
							if (empty($nomesCampanhas) || !in_array($cod_empresa . ' - AUTOM SMS TK LGPD', $nomesCampanhas)) {
								$sqlCamp = "CALL SP_ALTERA_CAMPANHA ( '0', '$cod_empresa', '9999', 'N', '$cod_empresa - AUTOM SMS TK LGPD', '', 'fas fa-cogs', 'ff030b', 'N', '', '$cod_usucada', '21', 'N', NOW(), '2030-12-31 00:00:00', '08:51', '23:59', 'N', 'S', 'S', 'S', 'S', 'S', 'S', 'S', 'CAD' );";
								$query = mysqli_query(connTemp($cod_empresa, ''), $sqlCamp);

								if ($query) {

									$sql = "SELECT COD_CAMPANHA FROM CAMPANHA WHERE COD_EMPRESA = $cod_empresa AND DES_CAMPANHA = '$cod_empresa - AUTOM SMS TK LGPD'";
									$query = mysqli_query(connTemp($cod_empresa, ''), $sql);

									if ($qrResult = mysqli_fetch_assoc($query)) {
										$codCampanha = $qrResult['COD_CAMPANHA'];

										//INSERE GATILHO
										$sql = "INSERT INTO GATILHO_SMS( COD_EMPRESA, COD_CAMPANHA, TIP_GATILHO, TIP_CONTROLE, TIP_MOMENTO, DES_PERIODO, DES_PERIODOMIN, DES_PERIODOMAX, HOR_ESPECIF, TOT_SALDOMIN, DIAS_ANTECED, DIAS_HIST, DAT_INI, HOR_INI, LOG_DOMINGO, LOG_SEGUNDA, LOG_TERCA, LOG_QUARTA, LOG_QUINTA, LOG_SEXTA, LOG_SABADO, COD_USUARIO, LOG_STATUS ) VALUES( $cod_empresa, $codCampanha, 'tokenCad', '99', '99', '1', '0', '0', '1', 1.00, '7', '0', null, '', 'N', 'N', 'N', 'N', 'N', 'N', 'N', '$cod_usucada', 'S' );INSERT INTO CONTROLE_SCHEDULE_SMS( COD_EMPRESA, COD_CAMPANHA, TIP_GATILHO, COD_USUCADA ) VALUES( $cod_empresa, $codCampanha, 'tokenCad', $cod_usucada );DELETE FROM AGENDA_SMS WHERE COD_EMPRESA = $cod_empresa AND COD_CAMPANHA = $codCampanha AND DAT_INIAGENDAMENTO >= NOW();";
										mysqli_multi_query(connTemp($cod_empresa, ''), $sql);


										//INSERE BLOCO TEMPLATE SMS
										$sql = "INSERT INTO TEMPLATE_AUTOMACAO_SMS( COD_EMPRESA, COD_CAMPANHA, COD_BLTEMPL) VALUES( $cod_empresa, $codCampanha, 25 );";

										mysqli_query(connTemp($cod_empresa, ''), $sql);

										//INSERE MENSAGEM
										//BUSCA TEMPLATE MSG
										$buscaTemplate = "SELECT COD_TEMPLATE FROM TEMPLATE_SMS WHERE COD_EMPRESA = $cod_empresa AND NOM_TEMPLATE = '$cod_empresa - Template AUTOM SMS Token'";

										$query = mysqli_query(connTemp($cod_empresa, ''), $buscaTemplate);
										$qrResult = mysqli_fetch_assoc($query);
										$cod_template = $qrResult['COD_TEMPLATE'];

										//BUSCA BLOCO TEMPLATE
										$buscaBloco = "SELECT COD_TEMPLATE FROM TEMPLATE_AUTOMACAO_SMS WHERE COD_EMPRESA = $cod_empresa AND COD_CAMPANHA = $codCampanha AND COD_BLTEMPL = 25";

										$query = mysqli_query(connTemp($cod_empresa, ''), $buscaBloco);
										$qrResult = mysqli_fetch_assoc($query);
										$cod_bloco = $qrResult['COD_TEMPLATE'];

										if ($cod_template != "" && $cod_bloco != "") {
											$sql = "INSERT INTO MENSAGEM_SMS( COD_TEMPLATE_SMS, COD_TEMPLATE_BLOCO, COD_EMPRESA, COD_CAMPANHA, NUM_ORDENAC, LOG_PRINCIPAL, COD_USUCADA ) VALUES( $cod_template, $cod_bloco, $cod_empresa, $codCampanha, (SELECT NUM_ORDENAC FROM TEMPLATE_AUTOMACAO_SMS WHERE COD_TEMPLATE = $cod_bloco), 'N', $cod_usucada );";


											mysqli_query(connTemp($cod_empresa, ''), $sql);
										}

										//INSERE LISTA DE ENVIO
										$buscaPersona = "SELECT COD_PERSONA FROM PERSONA WHERE COD_EMPRESA = $cod_empresa AND LOG_ATIVO = 'S' AND DES_PERSONA = 'Fidelidade (acesso restrito)'";
										$query = mysqli_query(connTemp($cod_empresa, ''), $buscaPersona);
										if ($qrResult = mysqli_fetch_assoc($query)) {
											$codPersona = $qrResult['COD_PERSONA'];

											$sql = "CALL SP_RELAT_SMS_CLIENTE($cod_empresa, $codCampanha, '0', '$codPersona', 'ANV')";
											mysqli_query(connTemp($cod_empresa, ''), $sql);
											$sql2 = "INSERT INTO SMS_PARAMETROS( COD_EMPRESA, COD_CAMPANHA, COD_PERSONAS, PCT_RESERVA, TOT_PERSONAS, CLIENTES_UNICOS, CLIENTES_UNICOS_SMS, CLIENTES_UNICO_PERC, TOTAL_CLIENTE_SMS_NAO, CLIENTES_OPTOUT, CLIENTES_BLACKLIST, COD_USUCADA ) VALUES( $cod_empresa, $codCampanha, '$codPersona', '0', '0', '0', '0', '0', '0', '0', '0', $cod_usucada );";
											mysqli_query(connTemp($cod_empresa, ''), $sql2);
										}
									}
								}
							}
						}

						//CAMPANHA AUTOM BOAS VINDAS
						if (isset($_POST['LOG_AUTSMSBVS'])) {

							if (empty($nomesCampanhas) || !in_array($cod_empresa . ' - AUTOM SMS BVs', $nomesCampanhas)) {
								$sqlCamp = "CALL SP_ALTERA_CAMPANHA ( '0', '$cod_empresa', '9999', 'N', '$cod_empresa - AUTOM SMS BVs', '', 'fas fa-cogs', 'ff030b', 'N', '', '$cod_usucada', '21', 'N', NOW(), '2030-12-31 00:00:00', '08:51', '23:59', 'N', 'S', 'S', 'S', 'S', 'S', 'S', 'S', 'CAD' );";
								$query = mysqli_query(connTemp($cod_empresa, ''), $sqlCamp);

								if ($query) {

									$sql = "SELECT COD_CAMPANHA FROM CAMPANHA WHERE COD_EMPRESA = $cod_empresa AND DES_CAMPANHA = '$cod_empresa - AUTOM SMS BVs'";
									$query = mysqli_query(connTemp($cod_empresa, ''), $sql);

									if ($qrResult = mysqli_fetch_assoc($query)) {
										$codCampanha = $qrResult['COD_CAMPANHA'];

										//INSERE GATILHO
										$sql = "INSERT INTO GATILHO_SMS( COD_EMPRESA, COD_CAMPANHA, TIP_GATILHO, TIP_CONTROLE, TIP_MOMENTO, DES_PERIODO, DES_PERIODOMIN, DES_PERIODOMAX, HOR_ESPECIF, TOT_SALDOMIN, DIAS_ANTECED, DIAS_HIST, DAT_INI, HOR_INI, LOG_DOMINGO, LOG_SEGUNDA, LOG_TERCA, LOG_QUARTA, LOG_QUINTA, LOG_SEXTA, LOG_SABADO, COD_USUARIO, LOG_STATUS ) VALUES( $cod_empresa, $codCampanha, 'cadastro', '99', '99', '99', '0', '0', '0', 0.00, '0', '0', null, '', 'N', 'N', 'N', 'N', 'N', 'N', 'N', '$cod_usucada', 'S' );INSERT INTO CONTROLE_SCHEDULE_SMS( COD_EMPRESA, COD_CAMPANHA, TIP_GATILHO, COD_USUCADA ) VALUES( $cod_empresa, $codCampanha, 'cadastro', $cod_usucada );DELETE FROM AGENDA_SMS WHERE COD_EMPRESA = $cod_empresa AND COD_CAMPANHA = $codCampanha AND DAT_INIAGENDAMENTO >= NOW();";
										mysqli_multi_query(connTemp($cod_empresa, ''), $sql);


										//INSERE BLOCO TEMPLATE SMS
										$sql = "INSERT INTO TEMPLATE_AUTOMACAO_SMS( COD_EMPRESA, COD_CAMPANHA, COD_BLTEMPL) VALUES( $cod_empresa, $codCampanha, 25 );";

										mysqli_query(connTemp($cod_empresa, ''), $sql);

										//INSERE MENSAGEM
										//BUSCA TEMPLATE MSG
										$buscaTemplate = "SELECT COD_TEMPLATE FROM TEMPLATE_SMS WHERE COD_EMPRESA = $cod_empresa AND NOM_TEMPLATE = '$cod_empresa - Template AUTOM SMS Boas Vindas'";

										$query = mysqli_query(connTemp($cod_empresa, ''), $buscaTemplate);
										$qrResult = mysqli_fetch_assoc($query);
										$cod_template = $qrResult['COD_TEMPLATE'];

										//BUSCA BLOCO TEMPLATE
										$buscaBloco = "SELECT COD_TEMPLATE FROM TEMPLATE_AUTOMACAO_SMS WHERE COD_EMPRESA = $cod_empresa AND COD_CAMPANHA = $codCampanha AND COD_BLTEMPL = 25";

										$query = mysqli_query(connTemp($cod_empresa, ''), $buscaBloco);
										$qrResult = mysqli_fetch_assoc($query);
										$cod_bloco = $qrResult['COD_TEMPLATE'];

										if ($cod_template != "" && $cod_bloco != "") {
											$sql = "INSERT INTO MENSAGEM_SMS( COD_TEMPLATE_SMS, COD_TEMPLATE_BLOCO, COD_EMPRESA, COD_CAMPANHA, NUM_ORDENAC, LOG_PRINCIPAL, COD_USUCADA ) VALUES( $cod_template, $cod_bloco, $cod_empresa, $codCampanha, (SELECT NUM_ORDENAC FROM TEMPLATE_AUTOMACAO_SMS WHERE COD_TEMPLATE = $cod_bloco), 'N', $cod_usucada );";


											mysqli_query(connTemp($cod_empresa, ''), $sql);
										}

										//INSERE LISTA DE ENVIO
										$buscaPersona = "SELECT COD_PERSONA FROM PERSONA WHERE COD_EMPRESA = $cod_empresa AND LOG_ATIVO = 'S' AND DES_PERSONA = 'Fidelidade (acesso restrito)'";
										$query = mysqli_query(connTemp($cod_empresa, ''), $buscaPersona);
										if ($qrResult = mysqli_fetch_assoc($query)) {
											$codPersona = $qrResult['COD_PERSONA'];

											$sql = "CALL SP_RELAT_SMS_CLIENTE($cod_empresa, $codCampanha, '0', '$codPersona', 'ANV')";
											mysqli_query(connTemp($cod_empresa, ''), $sql);
											$sql2 = "INSERT INTO SMS_PARAMETROS( COD_EMPRESA, COD_CAMPANHA, COD_PERSONAS, PCT_RESERVA, TOT_PERSONAS, CLIENTES_UNICOS, CLIENTES_UNICOS_SMS, CLIENTES_UNICO_PERC, TOTAL_CLIENTE_SMS_NAO, CLIENTES_OPTOUT, CLIENTES_BLACKLIST, COD_USUCADA ) VALUES( $cod_empresa, $codCampanha, '$codPersona', '0', '0', '0', '0', '0', '0', '0', '0', $cod_usucada );";
											mysqli_query(connTemp($cod_empresa, ''), $sql2);
										}
									}
								}
							}
						}

						//CAMPANHA AUTOM TRANSACIONAL
						if (isset($_POST['LOG_AUTSMSTRANS'])) {

							if (empty($nomesCampanhas) || !in_array($cod_empresa . ' - AUTOM SMS TRANS', $nomesCampanhas)) {
								$sqlCamp = "CALL SP_ALTERA_CAMPANHA ( '0', '$cod_empresa', '9999', 'N', '$cod_empresa - AUTOM SMS TRANS', '', 'fas fa-cogs', 'ff030b', 'N', '', '$cod_usucada', '21', 'N', NOW(), '2030-12-31 00:00:00', '08:51', '23:59', 'N', 'S', 'S', 'S', 'S', 'S', 'S', 'S', 'CAD' );";
								$query = mysqli_query(connTemp($cod_empresa, ''), $sqlCamp);

								if ($query) {

									$sql = "SELECT COD_CAMPANHA FROM CAMPANHA WHERE COD_EMPRESA = $cod_empresa AND DES_CAMPANHA = '$cod_empresa - AUTOM SMS TRANS'";
									$query = mysqli_query(connTemp($cod_empresa, ''), $sql);

									if ($qrResult = mysqli_fetch_assoc($query)) {
										$codCampanha = $qrResult['COD_CAMPANHA'];

										//INSERE GATILHO
										$sql = "INSERT INTO GATILHO_SMS( COD_EMPRESA, COD_CAMPANHA, TIP_GATILHO, TIP_CONTROLE, TIP_MOMENTO, DES_PERIODO, DES_PERIODOMIN, DES_PERIODOMAX, HOR_ESPECIF, TOT_SALDOMIN, DIAS_ANTECED, DIAS_HIST, DAT_INI, HOR_INI, LOG_DOMINGO, LOG_SEGUNDA, LOG_TERCA, LOG_QUARTA, LOG_QUINTA, LOG_SEXTA, LOG_SABADO, COD_USUARIO, LOG_STATUS ) VALUES( $cod_empresa, $codCampanha, 'venda', '7', '99', '99', '0', '0', '0', 1.00, '0', '0', null, '', 'N', 'N', 'N', 'N', 'N', 'N', 'N', '$cod_usucada', 'S' );INSERT INTO CONTROLE_SCHEDULE_SMS( COD_EMPRESA, COD_CAMPANHA, TIP_GATILHO, COD_USUCADA ) VALUES( $cod_empresa, $codCampanha, 'venda', $cod_usucada );DELETE FROM AGENDA_SMS WHERE COD_EMPRESA = $cod_empresa AND COD_CAMPANHA = $codCampanha AND DAT_INIAGENDAMENTO >= NOW();";
										mysqli_multi_query(connTemp($cod_empresa, ''), $sql);


										//INSERE BLOCO TEMPLATE SMS
										$sql = "INSERT INTO TEMPLATE_AUTOMACAO_SMS( COD_EMPRESA, COD_CAMPANHA, COD_BLTEMPL) VALUES( $cod_empresa, $codCampanha, 25 );";

										mysqli_query(connTemp($cod_empresa, ''), $sql);

										//INSERE MENSAGEM
										//BUSCA TEMPLATE MSG
										$buscaTemplate = "SELECT COD_TEMPLATE FROM TEMPLATE_SMS WHERE COD_EMPRESA = $cod_empresa AND NOM_TEMPLATE = '$cod_empresa - Template AUTOM SMS Transacional'";

										$query = mysqli_query(connTemp($cod_empresa, ''), $buscaTemplate);
										$qrResult = mysqli_fetch_assoc($query);
										$cod_template = $qrResult['COD_TEMPLATE'];

										//BUSCA BLOCO TEMPLATE
										$buscaBloco = "SELECT COD_TEMPLATE FROM TEMPLATE_AUTOMACAO_SMS WHERE COD_EMPRESA = $cod_empresa AND COD_CAMPANHA = $codCampanha AND COD_BLTEMPL = 25";

										$query = mysqli_query(connTemp($cod_empresa, ''), $buscaBloco);
										$qrResult = mysqli_fetch_assoc($query);
										$cod_bloco = $qrResult['COD_TEMPLATE'];

										if ($cod_template != "" && $cod_bloco != "") {
											$sql = "INSERT INTO MENSAGEM_SMS( COD_TEMPLATE_SMS, COD_TEMPLATE_BLOCO, COD_EMPRESA, COD_CAMPANHA, NUM_ORDENAC, LOG_PRINCIPAL, COD_USUCADA ) VALUES( $cod_template, $cod_bloco, $cod_empresa, $codCampanha, (SELECT NUM_ORDENAC FROM TEMPLATE_AUTOMACAO_SMS WHERE COD_TEMPLATE = $cod_bloco), 'N', $cod_usucada );";


											mysqli_query(connTemp($cod_empresa, ''), $sql);
										}

										//INSERE LISTA DE ENVIO
										$buscaPersona = "SELECT COD_PERSONA FROM PERSONA WHERE COD_EMPRESA = $cod_empresa AND LOG_ATIVO = 'S' AND DES_PERSONA = 'Fidelidade (acesso restrito)'";
										$query = mysqli_query(connTemp($cod_empresa, ''), $buscaPersona);
										if ($qrResult = mysqli_fetch_assoc($query)) {
											$codPersona = $qrResult['COD_PERSONA'];

											$sql = "CALL SP_RELAT_SMS_CLIENTE($cod_empresa, $codCampanha, '0', '$codPersona', 'ANV')";
											mysqli_query(connTemp($cod_empresa, ''), $sql);
											$sql2 = "INSERT INTO SMS_PARAMETROS( COD_EMPRESA, COD_CAMPANHA, COD_PERSONAS, PCT_RESERVA, TOT_PERSONAS, CLIENTES_UNICOS, CLIENTES_UNICOS_SMS, CLIENTES_UNICO_PERC, TOTAL_CLIENTE_SMS_NAO, CLIENTES_OPTOUT, CLIENTES_BLACKLIST, COD_USUCADA ) VALUES( $cod_empresa, $codCampanha, '$codPersona', '0', '0', '0', '0', '0', '0', '0', '0', $cod_usucada );";
											mysqli_query(connTemp($cod_empresa, ''), $sql2);
										}
									}
								}
							}
						}

						//CAMPANHA AUTOM SMS Inativos 2a6
						if (isset($_POST['LOG_AUTSMSINAT26'])) {

							if (empty($nomesCampanhas) || !in_array($cod_empresa . ' - AUTOM SMS Inativos 2a6', $nomesCampanhas)) {
								$sqlCamp = "CALL SP_ALTERA_CAMPANHA ( '0', '$cod_empresa', '9999', 'N', '$cod_empresa - AUTOM SMS Inativos 2a6', '', 'fas fa-cogs', 'ff030b', 'N', '', '$cod_usucada', '21', 'N', NOW(), '2030-12-31 00:00:00', '08:51', '23:59', 'N', 'S', 'S', 'S', 'S', 'S', 'S', 'S', 'CAD' );";
								$query = mysqli_query(connTemp($cod_empresa, ''), $sqlCamp);

								if ($query) {

									$sql = "SELECT COD_CAMPANHA FROM CAMPANHA WHERE COD_EMPRESA = $cod_empresa AND DES_CAMPANHA = '$cod_empresa - AUTOM SMS Inativos 2a6'";
									$query = mysqli_query(connTemp($cod_empresa, ''), $sql);

									if ($qrResult = mysqli_fetch_assoc($query)) {
										$codCampanha = $qrResult['COD_CAMPANHA'];

										//INSERE GATILHO
										$sql = "INSERT INTO GATILHO_SMS( COD_EMPRESA, COD_CAMPANHA, TIP_GATILHO, TIP_CONTROLE, TIP_MOMENTO, DES_PERIODO, DES_PERIODOMIN, DES_PERIODOMAX, HOR_ESPECIF, TOT_SALDOMIN, DIAS_ANTECED, DIAS_HIST, DAT_INI, HOR_INI, LOG_DOMINGO, LOG_SEGUNDA, LOG_TERCA, LOG_QUARTA, LOG_QUINTA, LOG_SEXTA, LOG_SABADO, COD_USUARIO, LOG_STATUS ) VALUES( $cod_empresa, $codCampanha, 'inativos', '30', '8', '1', '0', '0', '0', 0.00, '0', '0', null, '', 'N', 'N', 'N', 'N', 'N', 'N', 'N', '$cod_usucada', 'S' );INSERT INTO CONTROLE_SCHEDULE_SMS( COD_EMPRESA, COD_CAMPANHA, TIP_GATILHO, COD_USUCADA ) VALUES( $cod_empresa, $codCampanha, 'inativos', $cod_usucada );DELETE FROM AGENDA_SMS WHERE COD_EMPRESA = $cod_empresa AND COD_CAMPANHA = $codCampanha AND DAT_INIAGENDAMENTO >= NOW();";
										mysqli_multi_query(connTemp($cod_empresa, ''), $sql);


										//INSERE BLOCO TEMPLATE SMS
										$sql = "INSERT INTO TEMPLATE_AUTOMACAO_SMS( COD_EMPRESA, COD_CAMPANHA, COD_BLTEMPL) VALUES( $cod_empresa, $codCampanha, 25 );";

										mysqli_query(connTemp($cod_empresa, ''), $sql);

										//INSERE MENSAGEM
										//BUSCA TEMPLATE MSG
										$buscaTemplate = "SELECT COD_TEMPLATE FROM TEMPLATE_SMS WHERE COD_EMPRESA = $cod_empresa AND NOM_TEMPLATE = '$cod_empresa - Template AUTOM SMS Inativos 2a6'";

										$query = mysqli_query(connTemp($cod_empresa, ''), $buscaTemplate);
										$qrResult = mysqli_fetch_assoc($query);
										$cod_template = $qrResult['COD_TEMPLATE'];

										//BUSCA BLOCO TEMPLATE
										$buscaBloco = "SELECT COD_TEMPLATE FROM TEMPLATE_AUTOMACAO_SMS WHERE COD_EMPRESA = $cod_empresa AND COD_CAMPANHA = $codCampanha AND COD_BLTEMPL = 25";

										$query = mysqli_query(connTemp($cod_empresa, ''), $buscaBloco);
										$qrResult = mysqli_fetch_assoc($query);
										$cod_bloco = $qrResult['COD_TEMPLATE'];

										if ($cod_template != "" && $cod_bloco != "") {
											$sql = "INSERT INTO MENSAGEM_SMS( COD_TEMPLATE_SMS, COD_TEMPLATE_BLOCO, COD_EMPRESA, COD_CAMPANHA, NUM_ORDENAC, LOG_PRINCIPAL, COD_USUCADA ) VALUES( $cod_template, $cod_bloco, $cod_empresa, $codCampanha, (SELECT NUM_ORDENAC FROM TEMPLATE_AUTOMACAO_SMS WHERE COD_TEMPLATE = $cod_bloco), 'N', $cod_usucada );";


											mysqli_query(connTemp($cod_empresa, ''), $sql);
										}

										//INSERE LISTA DE ENVIO
										$buscaPersona = "SELECT COD_PERSONA FROM PERSONA WHERE COD_EMPRESA = $cod_empresa AND LOG_ATIVO = 'S' AND DES_PERSONA = '$cod_empresa - Autom SMS Inativo 2a6'";
										$query = mysqli_query(connTemp($cod_empresa, ''), $buscaPersona);
										if ($qrResult = mysqli_fetch_assoc($query)) {
											$codPersona = $qrResult['COD_PERSONA'];

											$sql = "CALL SP_RELAT_SMS_CLIENTE($cod_empresa, $codCampanha, '0', '$codPersona', 'ANV')";
											mysqli_query(connTemp($cod_empresa, ''), $sql);
											$sql2 = "INSERT INTO SMS_PARAMETROS( COD_EMPRESA, COD_CAMPANHA, COD_PERSONAS, PCT_RESERVA, TOT_PERSONAS, CLIENTES_UNICOS, CLIENTES_UNICOS_SMS, CLIENTES_UNICO_PERC, TOTAL_CLIENTE_SMS_NAO, CLIENTES_OPTOUT, CLIENTES_BLACKLIST, COD_USUCADA ) VALUES( $cod_empresa, $codCampanha, '$codPersona', '0', '0', '0', '0', '0', '0', '0', '0', $cod_usucada );";
											mysqli_query(connTemp($cod_empresa, ''), $sql2);
										}
									}
								}
							}
						}

						//CAMPANHA AUTOM NIVERS
						if (isset($_POST['LOG_AUTSMSNIVERS'])) {
							if (empty($nomesCampanhas) || !in_array($cod_empresa . ' - AUTOM SMS Nivers', $nomesCampanhas)) {
								$sqlCamp = "CALL SP_ALTERA_CAMPANHA ( '0', '$cod_empresa', '9999', 'N', '$cod_empresa - AUTOM SMS Nivers', '', 'fas fa-cogs', 'ff030b', 'N', '', '$cod_usucada', '21', 'N', NOW(), '2030-12-31 00:00:00', '08:51', '23:59', 'N', 'S', 'S', 'S', 'S', 'S', 'S', 'S', 'CAD' );";
								$query = mysqli_query(connTemp($cod_empresa, ''), $sqlCamp);

								if ($query) {

									$sql = "SELECT COD_CAMPANHA FROM CAMPANHA WHERE COD_EMPRESA = $cod_empresa AND DES_CAMPANHA = '$cod_empresa - AUTOM SMS Nivers'";
									$query = mysqli_query(connTemp($cod_empresa, ''), $sql);

									if ($qrResult = mysqli_fetch_assoc($query)) {
										$codCampanha = $qrResult['COD_CAMPANHA'];

										//INSERE GATILHO
										$sql = "INSERT INTO GATILHO_SMS( COD_EMPRESA, COD_CAMPANHA, TIP_GATILHO, TIP_CONTROLE, TIP_MOMENTO, DES_PERIODO, DES_PERIODOMIN, DES_PERIODOMAX, HOR_ESPECIF, TOT_SALDOMIN, DIAS_ANTECED, DIAS_HIST, DAT_INI, HOR_INI, LOG_DOMINGO, LOG_SEGUNDA, LOG_TERCA, LOG_QUARTA, LOG_QUINTA, LOG_SEXTA, LOG_SABADO, COD_USUARIO, LOG_STATUS ) VALUES( $cod_empresa, $codCampanha, 'aniv', '30', '10', '1', '0', '0', '0', 0.00, '0', '0', null, '', 'N', 'N', 'N', 'N', 'N', 'N', 'N', '$cod_usucada', 'S' );INSERT INTO CONTROLE_SCHEDULE_SMS( COD_EMPRESA, COD_CAMPANHA, TIP_GATILHO, COD_USUCADA ) VALUES( $cod_empresa, $codCampanha, 'aniv', $cod_usucada );DELETE FROM AGENDA_SMS WHERE COD_EMPRESA = $cod_empresa AND COD_CAMPANHA = $codCampanha AND DAT_INIAGENDAMENTO >= NOW();";
										mysqli_multi_query(connTemp($cod_empresa, ''), $sql);


										//INSERE BLOCO TEMPLATE SMS
										$sql = "INSERT INTO TEMPLATE_AUTOMACAO_SMS( COD_EMPRESA, COD_CAMPANHA, COD_BLTEMPL) VALUES( $cod_empresa, $codCampanha, 25 );";

										mysqli_query(connTemp($cod_empresa, ''), $sql);

										//INSERE MENSAGEM
										//BUSCA TEMPLATE MSG
										$buscaTemplate = "SELECT COD_TEMPLATE FROM TEMPLATE_SMS WHERE COD_EMPRESA = $cod_empresa AND NOM_TEMPLATE = '$cod_empresa - Template AUTOM SMS Niver'";

										$query = mysqli_query(connTemp($cod_empresa, ''), $buscaTemplate);
										$qrResult = mysqli_fetch_assoc($query);
										$cod_template = $qrResult['COD_TEMPLATE'];

										//BUSCA BLOCO TEMPLATE
										$buscaBloco = "SELECT COD_TEMPLATE FROM TEMPLATE_AUTOMACAO_SMS WHERE COD_EMPRESA = $cod_empresa AND COD_CAMPANHA = $codCampanha AND COD_BLTEMPL = 25";

										$query = mysqli_query(connTemp($cod_empresa, ''), $buscaBloco);
										$qrResult = mysqli_fetch_assoc($query);
										$cod_bloco = $qrResult['COD_TEMPLATE'];

										if ($cod_template != "" && $cod_bloco != "") {
											$sql = "INSERT INTO MENSAGEM_SMS( COD_TEMPLATE_SMS, COD_TEMPLATE_BLOCO, COD_EMPRESA, COD_CAMPANHA, NUM_ORDENAC, LOG_PRINCIPAL, COD_USUCADA ) VALUES( $cod_template, $cod_bloco, $cod_empresa, $codCampanha, (SELECT NUM_ORDENAC FROM TEMPLATE_AUTOMACAO_SMS WHERE COD_TEMPLATE = $cod_bloco), 'N', $cod_usucada );";


											mysqli_query(connTemp($cod_empresa, ''), $sql);
										}

										//INSERE LISTA DE ENVIO
										$buscaPersona = "SELECT COD_PERSONA FROM PERSONA WHERE COD_EMPRESA = $cod_empresa AND LOG_ATIVO = 'S' AND DES_PERSONA = '$cod_empresa - Autom SMS Nivers'";
										$query = mysqli_query(connTemp($cod_empresa, ''), $buscaPersona);
										if ($qrResult = mysqli_fetch_assoc($query)) {
											$codPersona = $qrResult['COD_PERSONA'];

											$sql = "CALL SP_RELAT_SMS_CLIENTE($cod_empresa, $codCampanha, '0', '$codPersona', 'ANV')";
											mysqli_query(connTemp($cod_empresa, ''), $sql);
											$sql2 = "INSERT INTO SMS_PARAMETROS( COD_EMPRESA, COD_CAMPANHA, COD_PERSONAS, PCT_RESERVA, TOT_PERSONAS, CLIENTES_UNICOS, CLIENTES_UNICOS_SMS, CLIENTES_UNICO_PERC, TOTAL_CLIENTE_SMS_NAO, CLIENTES_OPTOUT, CLIENTES_BLACKLIST, COD_USUCADA ) VALUES( $cod_empresa, $codCampanha, '$codPersona', '0', '0', '0', '0', '0', '0', '0', '0', $cod_usucada );";
											mysqli_query(connTemp($cod_empresa, ''), $sql2);
										}
									}
								}
							}
						}

						//CAMPANHA AUTOM Resgate
						if (isset($_POST['LOG_AUTSMSRESGATE'])) {
							if (empty($nomesCampanhas) || !in_array($cod_empresa . ' - SMS AUTOM Resgate', $nomesCampanhas)) {
								$sqlCamp = "CALL SP_ALTERA_CAMPANHA ( '0', '$cod_empresa', '9999', 'N', '$cod_empresa - SMS AUTOM Resgate', '', 'fas fa-cogs', 'ff030b', 'N', '', '$cod_usucada', '21', 'N', NOW(), '2030-12-31 00:00:00', '08:51', '23:59', 'N', 'S', 'S', 'S', 'S', 'S', 'S', 'S', 'CAD' );";
								$query = mysqli_query(connTemp($cod_empresa, ''), $sqlCamp);

								if ($query) {

									$sql = "SELECT COD_CAMPANHA FROM CAMPANHA WHERE COD_EMPRESA = $cod_empresa AND DES_CAMPANHA = '$cod_empresa - SMS AUTOM Resgate'";
									$query = mysqli_query(connTemp($cod_empresa, ''), $sql);

									if ($qrResult = mysqli_fetch_assoc($query)) {
										$codCampanha = $qrResult['COD_CAMPANHA'];

										//INSERE GATILHO
										$sql = "INSERT INTO GATILHO_SMS( COD_EMPRESA, COD_CAMPANHA, TIP_GATILHO, TIP_CONTROLE, TIP_MOMENTO, DES_PERIODO, DES_PERIODOMIN, DES_PERIODOMAX, HOR_ESPECIF, TOT_SALDOMIN, DIAS_ANTECED, DIAS_HIST, DAT_INI, HOR_INI, LOG_DOMINGO, LOG_SEGUNDA, LOG_TERCA, LOG_QUARTA, LOG_QUINTA, LOG_SEXTA, LOG_SABADO, COD_USUARIO, LOG_STATUS ) VALUES( $cod_empresa, $codCampanha, 'resgate', '99', '99', '99', '0', '0', '0', 0.00, '0', '0', null, '', 'N', 'N', 'N', 'N', 'N', 'N', 'N', '$cod_usucada', 'S' );INSERT INTO CONTROLE_SCHEDULE_SMS( COD_EMPRESA, COD_CAMPANHA, TIP_GATILHO, COD_USUCADA ) VALUES( $cod_empresa, $codCampanha, 'resgate', $cod_usucada );DELETE FROM AGENDA_SMS WHERE COD_EMPRESA = $cod_empresa AND COD_CAMPANHA = $codCampanha AND DAT_INIAGENDAMENTO >= NOW();";
										mysqli_multi_query(connTemp($cod_empresa, ''), $sql);


										//INSERE BLOCO TEMPLATE SMS
										$sql = "INSERT INTO TEMPLATE_AUTOMACAO_SMS( COD_EMPRESA, COD_CAMPANHA, COD_BLTEMPL) VALUES( $cod_empresa, $codCampanha, 25 );";

										mysqli_query(connTemp($cod_empresa, ''), $sql);

										//INSERE MENSAGEM
										//BUSCA TEMPLATE MSG
										$buscaTemplate = "SELECT COD_TEMPLATE FROM TEMPLATE_SMS WHERE COD_EMPRESA = $cod_empresa AND NOM_TEMPLATE = '$cod_empresa - Template AUTOM SMS Resgate'";

										$query = mysqli_query(connTemp($cod_empresa, ''), $buscaTemplate);
										$qrResult = mysqli_fetch_assoc($query);
										$cod_template = $qrResult['COD_TEMPLATE'];

										//BUSCA BLOCO TEMPLATE
										$buscaBloco = "SELECT COD_TEMPLATE FROM TEMPLATE_AUTOMACAO_SMS WHERE COD_EMPRESA = $cod_empresa AND COD_CAMPANHA = $codCampanha AND COD_BLTEMPL = 25";

										$query = mysqli_query(connTemp($cod_empresa, ''), $buscaBloco);
										$qrResult = mysqli_fetch_assoc($query);
										$cod_bloco = $qrResult['COD_TEMPLATE'];

										if ($cod_template != "" && $cod_bloco != "") {
											$sql = "INSERT INTO MENSAGEM_SMS( COD_TEMPLATE_SMS, COD_TEMPLATE_BLOCO, COD_EMPRESA, COD_CAMPANHA, NUM_ORDENAC, LOG_PRINCIPAL, COD_USUCADA ) VALUES( $cod_template, $cod_bloco, $cod_empresa, $codCampanha, (SELECT NUM_ORDENAC FROM TEMPLATE_AUTOMACAO_SMS WHERE COD_TEMPLATE = $cod_bloco), 'N', $cod_usucada );";


											mysqli_query(connTemp($cod_empresa, ''), $sql);
										}

										//INSERE LISTA DE ENVIO
										$buscaPersona = "SELECT COD_PERSONA FROM PERSONA WHERE COD_EMPRESA = $cod_empresa AND LOG_ATIVO = 'S' AND DES_PERSONA = 'Fidelidade (acesso restrito)'";
										$query = mysqli_query(connTemp($cod_empresa, ''), $buscaPersona);
										if ($qrResult = mysqli_fetch_assoc($query)) {
											$codPersona = $qrResult['COD_PERSONA'];

											$sql = "CALL SP_RELAT_SMS_CLIENTE($cod_empresa, $codCampanha, '0', '$codPersona', 'ANV')";
											mysqli_query(connTemp($cod_empresa, ''), $sql);
											$sql2 = "INSERT INTO SMS_PARAMETROS( COD_EMPRESA, COD_CAMPANHA, COD_PERSONAS, PCT_RESERVA, TOT_PERSONAS, CLIENTES_UNICOS, CLIENTES_UNICOS_SMS, CLIENTES_UNICO_PERC, TOTAL_CLIENTE_SMS_NAO, CLIENTES_OPTOUT, CLIENTES_BLACKLIST, COD_USUCADA ) VALUES( $cod_empresa, $codCampanha, '$codPersona', '0', '0', '0', '0', '0', '0', '0', '0', $cod_usucada );";
											mysqli_query(connTemp($cod_empresa, ''), $sql2);
										}
									}
								}
							}
						}

						//CAMPANHA AUTOM NIVERS CLUB
						if (isset($_POST['LOG_AUTSMSNIVERSCLUB'])) {

							if (empty($nomesCampanhas) || !in_array($cod_empresa . ' - SMS AUTOM Nivers Club 3B5', $nomesCampanhas)) {
								$sqlCamp = "CALL SP_ALTERA_CAMPANHA ( '0', '$cod_empresa', '9999', 'N', '$cod_empresa - SMS AUTOM Nivers Club 3B5', '', 'fas fa-cogs', 'ff030b', 'N', '', '$cod_usucada', '21', 'N', NOW(), '2030-12-31 00:00:00', '08:51', '23:59', 'N', 'S', 'S', 'S', 'S', 'S', 'S', 'S', 'CAD' );";
								$query = mysqli_query(connTemp($cod_empresa, ''), $sqlCamp);

								if ($query) {

									$sql = "SELECT COD_CAMPANHA FROM CAMPANHA WHERE COD_EMPRESA = $cod_empresa AND DES_CAMPANHA = '$cod_empresa - SMS AUTOM Nivers Club 3B5'";
									$query = mysqli_query(connTemp($cod_empresa, ''), $sql);

									if ($qrResult = mysqli_fetch_assoc($query)) {
										$codCampanha = $qrResult['COD_CAMPANHA'];

										//INSERE GATILHO
										$sql = "INSERT INTO GATILHO_SMS( COD_EMPRESA, COD_CAMPANHA, TIP_GATILHO, TIP_CONTROLE, TIP_MOMENTO, DES_PERIODO, DES_PERIODOMIN, DES_PERIODOMAX, HOR_ESPECIF, TOT_SALDOMIN, DIAS_ANTECED, DIAS_HIST, DAT_INI, HOR_INI, LOG_DOMINGO, LOG_SEGUNDA, LOG_TERCA, LOG_QUARTA, LOG_QUINTA, LOG_SEXTA, LOG_SABADO, COD_USUARIO, LOG_STATUS ) VALUES( $cod_empresa, $codCampanha, 'anivCad', '30', '11', '30', '0', '0', '0', 0.00, '0', '0', null, '', 'N', 'N', 'N', 'N', 'N', 'N', 'N', '$cod_usucada', 'S' );INSERT INTO CONTROLE_SCHEDULE_SMS( COD_EMPRESA, COD_CAMPANHA, TIP_GATILHO, COD_USUCADA ) VALUES( $cod_empresa, $codCampanha, 'anivCad', $cod_usucada );DELETE FROM AGENDA_SMS WHERE COD_EMPRESA = $cod_empresa AND COD_CAMPANHA = $codCampanha AND DAT_INIAGENDAMENTO >= NOW();";
										mysqli_multi_query(connTemp($cod_empresa, ''), $sql);


										//INSERE BLOCO TEMPLATE SMS
										$sql = "INSERT INTO TEMPLATE_AUTOMACAO_SMS( COD_EMPRESA, COD_CAMPANHA, COD_BLTEMPL) VALUES( $cod_empresa, $codCampanha, 25 );";

										mysqli_query(connTemp($cod_empresa, ''), $sql);

										//INSERE MENSAGEM
										//BUSCA TEMPLATE MSG
										$buscaTemplate = "SELECT COD_TEMPLATE FROM TEMPLATE_SMS WHERE COD_EMPRESA = $cod_empresa AND NOM_TEMPLATE = '$cod_empresa - Template AUTOM Nivers club 3B5'";

										$query = mysqli_query(connTemp($cod_empresa, ''), $buscaTemplate);
										$qrResult = mysqli_fetch_assoc($query);
										$cod_template = $qrResult['COD_TEMPLATE'];

										//BUSCA BLOCO TEMPLATE
										$buscaBloco = "SELECT COD_TEMPLATE FROM TEMPLATE_AUTOMACAO_SMS WHERE COD_EMPRESA = $cod_empresa AND COD_CAMPANHA = $codCampanha AND COD_BLTEMPL = 25";

										$query = mysqli_query(connTemp($cod_empresa, ''), $buscaBloco);
										$qrResult = mysqli_fetch_assoc($query);
										$cod_bloco = $qrResult['COD_TEMPLATE'];

										if ($cod_template != "" && $cod_bloco != "") {
											$sql = "INSERT INTO MENSAGEM_SMS( COD_TEMPLATE_SMS, COD_TEMPLATE_BLOCO, COD_EMPRESA, COD_CAMPANHA, NUM_ORDENAC, LOG_PRINCIPAL, COD_USUCADA ) VALUES( $cod_template, $cod_bloco, $cod_empresa, $codCampanha, (SELECT NUM_ORDENAC FROM TEMPLATE_AUTOMACAO_SMS WHERE COD_TEMPLATE = $cod_bloco), 'N', $cod_usucada );";


											mysqli_query(connTemp($cod_empresa, ''), $sql);
										}

										//INSERE LISTA DE ENVIO
										$buscaPersona = "SELECT COD_PERSONA FROM PERSONA WHERE COD_EMPRESA = $cod_empresa AND LOG_ATIVO = 'S' AND DES_PERSONA = '$cod_empresa - AUTOM SMS Nivers Club 3B5'";
										$query = mysqli_query(connTemp($cod_empresa, ''), $buscaPersona);
										if ($qrResult = mysqli_fetch_assoc($query)) {
											$codPersona = $qrResult['COD_PERSONA'];

											$sql = "CALL SP_RELAT_SMS_CLIENTE($cod_empresa, $codCampanha, '0', '$codPersona', 'ANV')";
											mysqli_query(connTemp($cod_empresa, ''), $sql);
											$sql2 = "INSERT INTO SMS_PARAMETROS( COD_EMPRESA, COD_CAMPANHA, COD_PERSONAS, PCT_RESERVA, TOT_PERSONAS, CLIENTES_UNICOS, CLIENTES_UNICOS_SMS, CLIENTES_UNICO_PERC, TOTAL_CLIENTE_SMS_NAO, CLIENTES_OPTOUT, CLIENTES_BLACKLIST, COD_USUCADA ) VALUES( $cod_empresa, $codCampanha, '$codPersona', '0', '0', '0', '0', '0', '0', '0', '0', $cod_usucada );";
											mysqli_query(connTemp($cod_empresa, ''), $sql2);
										}
									}
								}
							}
						}

						//BLOQUEIA CAMPANHAS
						$sql = "UPDATE CAMPANHA 
						SET LOG_RESTRITO = 'S' 
						WHERE COD_EMPRESA = $cod_empresa 
						AND DES_CAMPANHA IN ('CashBack', 'CashBack Melhor Idade')";
						mysqli_query(connTemp($cod_empresa, ''), $sql);

						//INSERE NA AUDITORIA
						$sqlBusca = "SELECT COD_CAMPANHA FROM CAMPANHA WHERE COD_EMPRESA = $cod_empresa";
						$query = mysqli_query(connTemp($cod_empresa, ''), $sqlBusca);
						if ($query) {
							$codCampanha = "";
							while ($qrResult = mysqli_fetch_assoc($query)) {
								$codCampanha .= $qrResult['COD_CAMPANHA'] . ",";
							}
							$sqlAudit = "UPDATE AUDITORIA_EMPRESA SET COD_CAMPANHA = '$codCampanha' WHERE COD_EMPRESA = $cod_empresa";

							$query = mysqli_query($connAdm->connAdm(), $sqlAudit);
						}
					}
					//FIM INSERE CAMPANHAS

					//INSERE TERMOS E NOTAS
					$sqlControle = "SELECT * FROM CONTROLE_TERMO WHERE COD_EMPRESA = $cod_empresa";
					$arrayControle = mysqli_query(connTemp($cod_empresa, ''), $sqlControle);
					if (mysqli_num_rows($arrayControle) == 0) {
						$qrCont = mysqli_fetch_assoc($arrayControle);
						$sqlIns = "INSERT INTO CONTROLE_TERMO(
													COD_EMPRESA,
													TXT_ACEITE,
													TXT_COMUNICA,
													LOG_SEPARA,
													COD_USUCADA
												) VALUES(
													$cod_empresa,
													'Estou ciente e de acordo com os termos, e desejo me cadastrar:',
													'Comunicação',
													'N',
													$_SESSION[SYS_COD_USUARIO]
												); ";

						$sqlIns .= "INSERT INTO TERMOS_EMPRESA 
										(COD_EMPRESA, COD_TIPO, NOM_TERMO, ABV_TERMO, LOG_ATIVO, DES_TERMO, COD_USUCADA) 
										VALUES 
										($cod_empresa, 1, 'Termos de Uso', 'Termos de Uso', 'S', '', $_SESSION[SYS_COD_USUARIO]),
										($cod_empresa, 1, 'Política de Privacidade', 'Política de Privacidade', 'S', '', $_SESSION[SYS_COD_USUARIO]),
										($cod_empresa, 1, 'Regulamento de Uso do Programa', 'Regulamento', 'S', '', $_SESSION[SYS_COD_USUARIO]),
										($cod_empresa, 2, 'Autorização de email', 'email', 'S', '', $_SESSION[SYS_COD_USUARIO]),
										($cod_empresa, 3, 'Autorização de SMS', 'SMS', 'S', '', $_SESSION[SYS_COD_USUARIO]),
										($cod_empresa, 4, 'Autorização de WhatsApp', 'WhatsApp', 'S', '', $_SESSION[SYS_COD_USUARIO]),
										($cod_empresa, 5, 'Autorização de Push', 'Push', 'S', '', $_SESSION[SYS_COD_USUARIO]),
										($cod_empresa, 6, 'Ofertas personalizadas', 'Ofertas', 'S', '', $_SESSION[SYS_COD_USUARIO]),
										($cod_empresa, 7, 'Autorização de Telemarketing', 'Telemarketing', 'S', '', $_SESSION[SYS_COD_USUARIO]); ";

						mysqli_multi_query(connTemp($cod_empresa, ''), $sqlIns);
					}

					$sqlBusca = "SELECT ct.ABV_TERMO FROM bloco_termos AS bt
					INNER JOIN TERMOS_EMPRESA AS ct ON ct.COD_TERMO IN (bt.COD_TERMO) WHERE ct.COD_EMPRESA = $cod_empresa";
					$query = mysqli_query(connTemp($cod_empresa, ""), $sqlBusca);
					$numLinhas = mysqli_num_rows($query);
					if ($numLinhas == 0 || $numLinhas < 2) {

						$sqlbusca = "SELECT DES_PROGRAMA FROM SITE_EXTRATO WHERE COD_EMPRESA = $cod_empresa";
						$query = mysqli_query(connTemp($cod_empresa, ''), $sqlbusca);
						if ($qrResult = mysqli_fetch_assoc($query)) {
							$des_programa = $qrResult['DES_PROGRAMA'];
						}

						// fnEscreve($numLinhas);
						if ($numLinhas > 0) {
							$abvTermo = "";
							while ($qrResult = mysqli_fetch_assoc($query)) {
								$abvTermo .= $qrResult['ABV_TERMO'] . ',';
							}
						}
						$sqlBloco = "";
						if (empty($abvTermo) || strpos($abvTermo, 'Regulamento') === false) {
							$sqlBusca = "SELECT COD_TERMO FROM TERMOS_EMPRESA WHERE COD_EMPRESA = $cod_empresa AND ABV_TERMO in ('Regulamento', 'Política de Privacidade')";
							$query = mysqli_query(connTemp($cod_empresa, ""), $sqlBusca);
							$numRow = mysqli_num_rows($query);
							$codTermo = "";
							if ($numRow > 0) {
								$qrResult = mysqli_fetch_assoc($query);
								while ($qrResult = mysqli_fetch_assoc($query)) {
									$codTermo .= $qrResult['COD_TERMO'] . ',';
								}
								$codTermo = rtrim($codTermo, ',');
								$sqlBloco .= "INSERT INTO BLOCO_TERMOS( COD_EMPRESA, COD_TERMO, DES_BLOCO, LOG_OBRIGA, TIP_TERMO, COD_USUCADA, NUM_ORDENAC ) VALUES
									( '$cod_empresa', '$codTermo', 'Li e estou de acordo com o <#REGULAMENTO> e <#POLíTICA DE PRIVACIDADE> do programa $des_programa $nom_fantasi.', 'S', 'DOC', '$cod_usucada', 1 );";
							}
						}

						// if (empty($abvTermo) || strpos($abvTermo, 'Política de Privacidade') === false) {
						// 	$sqlBusca = "SELECT COD_TERMO FROM TERMOS_EMPRESA WHERE COD_EMPRESA = $cod_empresa AND ABV_TERMO = 'Política de Privacidade'";
						// 	$query = mysqli_query(connTemp($cod_empresa, ""), $sqlBusca);
						// 	$numRow = mysqli_num_rows($query);
						// 	if ($numRow > 0) {
						// 		$qrResult = mysqli_fetch_assoc($query);
						// 		$codTermo = $qrResult['COD_TERMO'];
						// 		$sqlBloco .= "INSERT INTO BLOCO_TERMOS( COD_EMPRESA, COD_TERMO, DES_BLOCO, LOG_OBRIGA, TIP_TERMO, COD_USUCADA, NUM_ORDENAC ) VALUES
						// 		( '$cod_empresa', '$codTermo', 'Li e estou de acordo com a <#POLíTICA DE PRIVACIDADE> do programa $des_programa $nom_fantasi.', 'S', 'DOC', '$cod_usucada', 2 );";
						// 	}
						// }

						if (empty($abvTermo) || strpos($abvTermo, 'email') === false) {
							$sqlBusca = "SELECT COD_TERMO FROM TERMOS_EMPRESA WHERE COD_EMPRESA = $cod_empresa AND ABV_TERMO IN('email', 'SMS', 'WhatsApp', 'Push', 'Ofertas', 'Telemarketing')";
							$query = mysqli_query(connTemp($cod_empresa, ""), $sqlBusca);
							$numRows = mysqli_num_rows($query);
							$codTermo = "";
							if ($numRows > 0) {
								while ($qrResult = mysqli_fetch_assoc($query)) {
									$codTermo .= $qrResult['COD_TERMO'] . ',';
								}
								$codTermo = rtrim($codTermo, ',');
								$sqlBloco .= "INSERT INTO BLOCO_TERMOS( COD_EMPRESA, COD_TERMO, DES_BLOCO, LOG_OBRIGA, TIP_TERMO, COD_USUCADA, NUM_ORDENAC ) VALUES
								( '$cod_empresa', '$codTermo', 'Aceito receber <#OFERTAS> e comunicação do programa por:  <#SMS>,  <#WHATSAPP> ,  <#PUSH> ,  <#TELEMARKETING>  e  <#EMAIL>.', 'S', 'DOC', '$cod_usucada', 3 );";
							}
						}
						// fnEscreve($sqlBloco

						if ($sqlBloco != "") {
							mysqli_multi_query(connTemp($cod_empresa, ""), $sqlBloco);

							$sqlBusca = "SELECT COD_BLOCO FROM BLOCO_TERMOS WHERE COD_EMPRESA = $cod_empresa";
							$query = mysqli_query(connTemp($cod_empresa, ""), $sqlBusca);
							$codBloco = "";
							while ($qrResult = mysqli_fetch_assoc($query)) {
								$codBloco .= $qrResult['COD_BLOCO'] . ',';
							}
							$codBloco = rtrim($codBloco, ',');
							$sqlAudit = "UPDATE AUDITORIA_EMPRESA SET 
								COD_TERMOS = '$codBloco',
								FASE4 = 'S'
								WHERE COD_EMPRESA = $cod_empresa";

							$arrayProcAudit = mysqli_query($connAdm->connAdm(), trim($sqlAudit));
						}
					}


					$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					break;
			}

			$msgTipo = 'alert-success';
		}
	}
}


//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode($_GET['id']);
	$sql = "SELECT COD_EMPRESA, NOM_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
		$nom_fantasi = $qrBuscaEmpresa['NOM_FANTASI'];
	}
} else {
	$cod_empresa = 0;
	//fnEscreve('entrou else');
}

$sqlAudit = "SELECT * FROM AUDITORIA_EMPRESA
		WHERE COD_EMPRESA = $cod_empresa";

$query = mysqli_query($connAdm->connAdm(), trim($sqlAudit));

$log_checked = "";
if ($qrResult = mysqli_fetch_assoc($query)) {

	if ($qrResult['LOG_PROCTKT'] == 'S') {
		$log_checked = "checked";
	}
}


?>

<div class="push30"></div>

<div class="row">

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="glyphicon glyphicon-calendar"></i>
					<span class="text-primary"><?php echo $NomePg . " - " . $nom_fantasi; ?></span>
				</div>

				<?php
				$formBack = "1019";
				?>

			</div>
			<div class="portlet-body">

				<?php if ($msgRetorno <> '') { ?>
					<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<?php echo $msgRetorno; ?>
					</div>
				<?php } ?>

				<?php //if ($msgRetorno <> '') { 
				?>
				<div class="alert alert-warning alert-dismissible top30 bottom30" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<?php //echo $msgRetorno; 
					?>
					Para gerar os dados, clique em <strong><i class="fas fa-cogs"></i>&nbsp;&nbsp; Processar</strong>, e depois em <strong>Próximo&nbsp;<i class="fas fa-arrow-right"></i></strong>
				</div>
				<?php //} 
				?>

				<?php $abaEmpresa = 1025; ?>

				<div class="push30"></div>

				<style>
					.leitura2 {
						border: none transparent !important;
						outline: none !important;
						background: #fff !important;
						font-size: 18px;
						padding: 0;
					}

					.container-fluid .passo:not(:first-of-type) {
						display: none;
					}

					.wizard .col-md-2 {
						padding: 0;
					}

					.btn-circle {
						background-color: #DDD;
						opacity: 1 !important;
						border: 2px solid #efefef;
						height: 55px;
						width: 55px;
						margin-top: -23px;
						padding-top: 11px;
						border-radius: 50%;
						-moz-border-radius: 50%;
						-webkit-border-radius: 50%;
						color: #fff;
						font-size: 20px;
					}

					.fa-2x {
						font-size: 19px;
						margin-top: 5px;
					}

					.collapse-chevron .fa {
						transition: .3s transform ease-in-out;
					}

					.collapse-chevron .collapsed .fa {
						transform: rotate(-90deg);
					}

					.pull-right,
					.pull-left {
						margin-top: 3.5px;
					}

					.fundo {
						background: #D3D3D3;
						height: 10px;
						width: 100%;
					}

					.fundoAtivo {
						background: #2ed4e0;
					}

					.inicio {
						background: #2ed4e0;
						border-bottom-left-radius: 10px 7px;
						border-top-left-radius: 10px 7px;
					}

					.final {
						border-bottom-right-radius: 10px 7px;
						border-top-right-radius: 10px 7px;
					}

					.notify-badge {
						position: absolute;
						display: flex;
						align-items: center;
						right: 36%;
						top: 10px;
						border-radius: 30px 30px 30px 30px;
						text-align: center;
						color: white;
						font-size: 11px;
					}

					.notify-badge span {
						margin: 0 auto;
					}

					.bg-success {
						background-color: #18bc9c;
					}

					.bg-warning {
						background-color: #f39c12;
					}

					.center-content {
						display: flex;
						flex-direction: row;
						align-items: center;
						justify-content: center;
						height: 80px;
						text-align: center;
						border-radius: 6px;
						box-shadow: 1px 2px 2px 1px rgba(0, 0, 0, 0.2);
						background-color: #fff;
						padding: 4px;
					}
				</style>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">



						<div class="container-fluid">

							<div class="passo" id="passo1">


								<div class="row">

									<div class="col-sm-12" style="padding-left: 0;">

										<?php
										$abaAtivo = 2096;
										include 'menuAutomacao.php';
										?>

										<div class="col-xs-10">
											<!-- conteudo abas -->
											<div class="tab-content">


												<!-- aba produtos-->
												<div class="tab-pane active"">
																	<h4 style=" margin: 0 0 5px 0;"><span class="bolder">Campanhas e Comunicação</span></h4>
													<small style="font-size: 12px;"></small>

													<div class="row">

														<div class="col-md-12">
															<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

																<div class="push20"></div>

																<fieldset>
																	<legend>Configuração templates SMS</legend>

																	<div class="row">

																		<div class="col-md-2">
																			<div class="form-group">
																				<label for="inputName" class="control-label required">Nome Fantasia</label>
																				<input type="text" class="form-control input-sm leitura" disabled=disabled maxlength="40" value="<?= $nom_fantasi ?>" data-error="Campo obrigatório" required>
																				<div class="help-block with-errors"></div>
																				<!-- <div class="help-block with-errors validaTemp"></div> -->
																			</div>
																		</div>

																		<div class="col-md-2">
																			<div class="form-group">
																				<label for="inputName" class="control-label required">Abv. Nome Fantasia</label>
																				<input type="text" class="form-control input-sm" name="NOM_ABVEMPRESA" id="NOM_ABVEMPRESA" maxlength="25" value="" data-error="Campo obrigatório" required>
																				<div class="help-block with-errors">Nome que será exibido nas templates (Máximo 25 Caractéres)</div>
																				<div class="help-block validaTemp"></div>
																			</div>
																		</div>

																	</div>

																</fieldset>


																<div class="push20"></div>

																<fieldset>
																	<legend>Ticket de Ofertas</legend>

																	<div class="row">
																		<div class="col-md-2">
																			<div class="form-group">
																				<label for="inputName" class="control-label required">Gerar Ticket de Ofertas</label><br />
																				<label class="switch switch-small">
																					<input type="checkbox" name="LOG_PROCTKT" id="LOG_PROCTKT" class="switch" value="S" <?php echo $log_checked; ?> />
																					<span></span>
																				</label>
																				<div class="help-block with-errors"></div>
																			</div>
																		</div>
																	</div>

																	<div class="push20"></div>

																	<div class="row" id="templateRow">

																		<?php
																		// BUSCA Template
																		$temTemplate = "<i class='fal fa-times' style=' margin-left: 6; font-size: 24px; opacity: 0.2;'></i>";
																		$sqlBusca = "SELECT * FROM TEMPLATE WHERE COD_EMPRESA = $cod_empresa";
																		$query = mysqli_query(connTemp($cod_empresa, ''), $sqlBusca);
																		if (mysqli_num_rows($query) > 0) {
																			$temTemplate = "<i class='fal fa-check' style='margin-left: 6; font-size: 24px; color: #28a745;'></i>";
																			$mostraBtnTkt = "";
																		}

																		?>

																		<div class="col-md-3">
																			<span><b>Template</b></span>
																			<div class="center-content" style="margin-top: 12px;">
																				<span>Ticket de Oferta</span>
																				<?= $temTemplate ?>
																			</div>
																		</div>

																		<div class="col-md-3">
																			<span><b>Configurações T.O</b></span>
																			<div class="center-content" style="margin-top: 12px;">
																				<span>Ticket de Oferta</span>
																				<?= $temTemplate ?>
																			</div>
																		</div>

																	</div>

																	<div class="push20"></div>

																	<div class="row" id="categoriaRow">
																		<?php
																		// BUSCA PERFIL GERENCIAL

																		$temHigiene = "<i class='fal fa-times' style=' margin-left: 6; font-size: 24px; opacity: 0.2;'></i>";
																		$temSaude = "<i class='fal fa-times' style=' margin-left: 6; font-size: 24px; opacity: 0.2;'></i>";
																		$temBeleza = "<i class='fal fa-times' style=' margin-left: 6; font-size: 24px; opacity: 0.2;'></i>";
																		$temVitaminas = "<i class='fal fa-times' style=' margin-left: 6; font-size: 24px; opacity: 0.2;'></i>";
																		$temCuidados = "<i class='fal fa-times' style=' margin-left: 6; font-size: 24px; opacity: 0.2;'></i>";

																		$sqlBuscaCategor = "SELECT DES_CATEGOR FROM CATEGORIATKT WHERE COD_EMPRESA = $cod_empresa";
																		$query = mysqli_query(connTemp($cod_empresa, ''), $sqlBuscaCategor);
																		while ($result = mysqli_fetch_assoc($query)) {

																			if ($result['DES_CATEGOR'] == 'Higiene Pessoal') {
																				$temHigiene = "<i class='fal fa-check' style='margin-left: 6; font-size: 24px; color: #28a745;'></i>";
																			}

																			if ($result['DES_CATEGOR'] == 'Saúde') {
																				$temSaude = "<i class='fal fa-check' style='margin-left: 6; font-size: 24px; color: #28a745;'></i>";
																			}

																			if ($result['DES_CATEGOR'] == 'Beleza') {
																				$temBeleza = "<i class='fal fa-check' style='margin-left: 6; font-size: 24px; color: #28a745;'></i>";
																			}

																			if ($result['DES_CATEGOR'] == 'Vitaminas') {
																				$temVitaminas = "<i class='fal fa-check' style='margin-left: 6; font-size: 24px; color: #28a745;'></i>";
																			}

																			if ($result['DES_CATEGOR'] == 'Cuidados') {
																				$temCuidados = "<i class='fal fa-check' style='margin-left: 6; font-size: 24px; color: #28a745;'></i>";
																			}
																		}


																		?>

																		<div class="col-md-3">
																			<span><b>Categorias T.O</b></span>
																			<div class="center-content" style="margin-top: 12px;">
																				<span>Higiene Pessoal</span>
																				<?= $temHigiene ?>
																			</div>
																		</div>


																		<div class="col-md-3">
																			<span style="visibility: hidden;"><b>Categorias T.O</b></span>
																			<div class="center-content" style="margin-top: 12px;">
																				<span>Saúde</span>
																				<?= $temSaude ?>
																			</div>
																		</div>

																		<div class="col-md-3">
																			<span style="visibility: hidden;"><b>Categorias T.O</b></span>
																			<div class="center-content" style="margin-top: 12px;">
																				<span>Beleza</span>
																				<?= $temBeleza ?>
																			</div>
																		</div>

																		<div class="col-md-3">
																			<span style="visibility: hidden;"><b>Categorias T.O</b></span>
																			<div class="center-content" style="margin-top: 12px;">
																				<span>Vitaminas</span>
																				<?= $temVitaminas ?>
																			</div>
																		</div>

																		<div class="col-md-3">
																			<div class="center-content" style="margin-top: 12px;">
																				<span>Cuidados</span>
																				<?= $temCuidados ?>
																			</div>
																		</div>

																	</div>

																	<div class="row text-right col-lg-12" <?= $mostraBtnTkt ?>>
																		<a href="action.do?mod=<?= fnEncode(1111) ?>&id=<?= fnEncode($cod_empresa) ?>" target="_blank" class="btn btn-info btn-sm">Acessar T.O</a>
																	</div>

																</fieldset>

																<div class="push20"></div>

																<fieldset>
																	<legend>Campanhas</legend>

																	<div class="row">

																		<?php
																		// CAMPANHAS

																		$template1 = "<i class='fal fa-times' style=' margin-left: 6; font-size: 24px; opacity: 0.2;'></i>";
																		$check1 = "";
																		$template2 = "<i class='fal fa-times' style=' margin-left: 6; font-size: 24px; opacity: 0.2;'></i>";
																		$check2 = "";
																		$template3 = "<i class='fal fa-times' style=' margin-left: 6; font-size: 24px; opacity: 0.2;'></i>";
																		$check3 = "";
																		$template4 = "<i class='fal fa-times' style=' margin-left: 6; font-size: 24px; opacity: 0.2;'></i>";
																		$check4 = "";
																		$template5 = "<i class='fal fa-times' style=' margin-left: 6; font-size: 24px; opacity: 0.2;'></i>";
																		$check5 = "";
																		$template6 = "<i class='fal fa-times' style=' margin-left: 6; font-size: 24px; opacity: 0.2;'></i>";
																		$check6 = "";
																		$template7 = "<i class='fal fa-times' style=' margin-left: 6; font-size: 24px; opacity: 0.2;'></i>";
																		$check7 = "";
																		$template8 = "<i class='fal fa-times' style=' margin-left: 6; font-size: 24px; opacity: 0.2;'></i>";
																		$check8 = "";
																		$template9 = "<i class='fal fa-times' style=' margin-left: 6; font-size: 24px; opacity: 0.2;'></i>";
																		$check9 = "";
																		$template10 = "<i class='fal fa-times' style=' margin-left: 6; font-size: 24px; opacity: 0.2;'></i>";
																		$check10 = "";

																		$buscaTemplate = "SELECT DES_CAMPANHA FROM CAMPANHA WHERE COD_EMPRESA = $cod_empresa";
																		// fnEscreve($buscaTemplate);
																		$query = mysqli_query(connTemp($cod_empresa, ''), $buscaTemplate);
																		while ($result = mysqli_fetch_assoc($query)) {
																			$mostraBtnCamp = "";

																			if ($result['DES_CAMPANHA'] ==  'CashBack') {
																				$template1 = "<i class='fal fa-check' style='margin-left: 6; font-size: 24px; color: #28a745;'></i>";
																				$check1 = "checked";
																			}

																			if ($result['DES_CAMPANHA'] ==  'CashBack Melhor Idade') {
																				$template10 = "<i class='fal fa-check' style='margin-left: 6; font-size: 24px; color: #28a745;'></i>";
																				$check10 = "checked";
																			}

																			if ($result['DES_CAMPANHA'] ==   $cod_empresa . ' - AUTOM SMS Expirar') {
																				$template2 = "<i class='fal fa-check' style='margin-left: 6; font-size: 24px; color: #28a745;'></i>";
																				$check2 = "checked";
																			}

																			if ($result['DES_CAMPANHA'] ==  $cod_empresa . ' - AUTOM SMS TK LGPD') {
																				$template3 = "<i class='fal fa-check' style='margin-left: 6; font-size: 24px; color: #28a745;'></i>";
																				$check3 = "checked";
																			}

																			if ($result['DES_CAMPANHA'] ==  $cod_empresa . ' - AUTOM SMS BVs') {
																				$template4 = "<i class='fal fa-check' style='margin-left: 6; font-size: 24px; color: #28a745;'></i>";
																				$check4 = "checked";
																			}

																			if ($result['DES_CAMPANHA'] ==  $cod_empresa . ' - AUTOM SMS TRANS') {
																				$template5 = "<i class='fal fa-check' style='margin-left: 6; font-size: 24px; color: #28a745;'></i>";
																				$check5 = "checked";
																			}

																			if ($result['DES_CAMPANHA'] ==  $cod_empresa . ' - AUTOM SMS Inativos 2a6') {
																				$template6 = "<i class='fal fa-check' style='margin-left: 6; font-size: 24px; color: #28a745;'></i>";
																				$check6 = "checked";
																			}

																			if ($result['DES_CAMPANHA'] ==  $cod_empresa . ' - AUTOM SMS Nivers') {
																				$template7 = "<i class='fal fa-check' style='margin-left: 6; font-size: 24px; color: #28a745;'></i>";
																				$check7 = "checked";
																			}

																			if ($result['DES_CAMPANHA'] ==  $cod_empresa . ' - SMS AUTOM Resgate') {
																				$template8 = "<i class='fal fa-check' style='margin-left: 6; font-size: 24px; color: #28a745;'></i>";
																				$check8 = "checked";
																			}

																			if ($result['DES_CAMPANHA'] ==  $cod_empresa . ' - SMS AUTOM Nivers Club 3B5') {
																				$template9 = "<i class='fal fa-check' style='margin-left: 6; font-size: 24px; color: #28a745;'></i>";
																				$check9 = "checked";
																			}
																		}

																		?>

																		<div class="col-md-3">
																			<div class="center-content" style="margin-top: 12px;">
																				<div style="flex-direction: column;">
																					<div>
																						<span>CashBack</span>
																						<?= $template1 ?>
																					</div>
																					<div class="form-group">
																						<label class="switch switch-small">
																							<input type="checkbox" name="LOG_CASHBACK" id="LOG_CASHBACK" class="switch" value="S" <?= $check1 ?> />
																							<span></span>
																						</label>
																						<div class="help-block with-errors"></div>
																					</div>
																				</div>
																			</div>
																		</div>

																		<div class="col-md-3">
																			<div class="center-content" style="margin-top: 12px;">
																				<div style="flex-direction: column;">
																					<div>
																						<span>CashBack Melhor Idade</span>
																						<?= $template10 ?>
																					</div>
																					<div class="form-group">
																						<label class="switch switch-small">
																							<input type="checkbox" name="LOG_CASHBACKMLIDA" id="LOG_CASHBACKMLIDA" class="switch" value="S" <?= $check10 ?> />
																							<span></span>
																						</label>
																						<div class="help-block with-errors"></div>
																					</div>
																				</div>
																			</div>
																		</div>

																		<div class="col-md-3">
																			<div class="center-content" style="margin-top: 12px;">
																				<div style="flex-direction: column;">
																					<div>
																						<span> <?= $cod_empresa ?> - AUTOM SMS Expirar</span>
																						<?= $template2 ?>
																					</div>
																					<div class="form-group">
																						<label class="switch switch-small">
																							<input type="checkbox" name="LOG_AUTSMSEXPIRA" id="LOG_AUTSMSEXPIRA" class="switch" value="S" <?= $check2 ?> />
																							<span></span>
																						</label>
																						<div class="help-block with-errors"></div>
																					</div>
																				</div>
																			</div>
																		</div>

																		<div class="col-md-3">
																			<div class="center-content" style="margin-top: 12px;">
																				<div style="flex-direction: column;">
																					<div>
																						<span><?= $cod_empresa ?> - AUTOM SMS TK LGPD</span>
																						<?= $template3 ?>
																					</div>
																					<div class="form-group">
																						<label class="switch switch-small">
																							<input type="checkbox" name="LOG_AUTSMSTKLGPD" id="LOG_AUTSMSTKLGPD" class="switch" value="S" <?= $check3 ?> />
																							<span></span>
																						</label>
																						<div class="help-block with-errors"></div>
																					</div>
																				</div>
																			</div>
																		</div>

																		<div class="col-md-3">
																			<div class="center-content" style="margin-top: 12px;">
																				<div style="flex-direction: column;">
																					<div>
																						<span><?= $cod_empresa ?> - AUTOM SMS BVs</span>
																						<?= $template4 ?>
																					</div>
																					<div class="form-group">
																						<label class="switch switch-small">
																							<input type="checkbox" name="LOG_AUTSMSBVS" id="LOG_AUTSMSBVS" class="switch" value="S" <?= $check4 ?> />
																							<span></span>
																						</label>
																						<div class="help-block with-errors"></div>
																					</div>
																				</div>
																			</div>
																		</div>

																		<div class="col-md-3">
																			<div class="center-content" style="margin-top: 12px;">
																				<div style="flex-direction: column;">
																					<div>
																						<span><?= $cod_empresa ?> - AUTOM SMS TRANS</span>
																						<?= $template5 ?>
																					</div>
																					<div class="form-group">
																						<label class="switch switch-small">
																							<input type="checkbox" name="LOG_AUTSMSTRANS" id="LOG_AUTSMSTRANS" class="switch" value="S" <?= $check5 ?> />
																							<span></span>
																						</label>
																						<div class="help-block with-errors"></div>
																					</div>
																				</div>
																			</div>
																		</div>

																		<div class="col-md-3">
																			<div class="center-content" style="margin-top: 12px;">
																				<div style="flex-direction: column;">
																					<div>
																						<span><?= $cod_empresa ?> - AUTOM SMS Inativos 2a6</span>
																						<?= $template6 ?>
																					</div>
																					<div class="form-group">
																						<label class="switch switch-small">
																							<input type="checkbox" name="LOG_AUTSMSINAT26" id="LOG_AUTSMSINAT26" class="switch" value="S" <?= $check6 ?> />
																							<span></span>
																						</label>
																						<div class="help-block with-errors"></div>
																					</div>
																				</div>
																			</div>
																		</div>


																		<div class="col-md-3">
																			<div class="center-content" style="margin-top: 12px;">
																				<div style="flex-direction: column;">
																					<div>
																						<span><?= $cod_empresa ?> - AUTOM SMS Nivers</span>
																						<?= $template7 ?>
																					</div>
																					<div class="form-group">
																						<label class="switch switch-small">
																							<input type="checkbox" name="LOG_AUTSMSNIVERS" id="LOG_AUTSMSNIVERS" class="switch" value="S" <?= $check7 ?> />
																							<span></span>
																						</label>
																						<div class="help-block with-errors"></div>
																					</div>
																				</div>
																			</div>
																		</div>

																		<div class="col-md-3">
																			<div class="center-content" style="margin-top: 12px;">
																				<div style="flex-direction: column;">
																					<div>
																						<span><?= $cod_empresa ?> - SMS AUTOM Resgate</span>
																						<?= $template8 ?>
																					</div>
																					<div class="form-group">
																						<label class="switch switch-small">
																							<input type="checkbox" name="LOG_AUTSMSRESGATE" id="LOG_AUTSMSRESGATE" class="switch" value="S" <?= $check8 ?> />
																							<span></span>
																						</label>
																						<div class="help-block with-errors"></div>
																					</div>
																				</div>
																			</div>
																		</div>

																		<div class="col-md-3">
																			<div class="center-content" style="margin-top: 12px;">
																				<div style="flex-direction: column;">
																					<div>
																						<span><?= $cod_empresa ?> - SMS AUTOM Nivers Club 3B5</span>
																						<?= $template9 ?>
																					</div>
																					<div class="form-group">
																						<label class="switch switch-small">
																							<input type="checkbox" name="LOG_AUTSMSNIVERSCLUB" id="LOG_AUTSMSNIVERSCLUB" class="switch" value="S" <?= $check9 ?> />
																							<span></span>
																						</label>
																						<div class="help-block with-errors"></div>
																					</div>
																				</div>
																			</div>
																		</div>

																	</div>

																	<div class="row text-right col-lg-12" <?= $mostraBtnCamp ?>>
																		<a href="action.do?mod=<?= fnEncode(1468) ?>&id=<?= fnEncode($cod_empresa) ?>" target="_blank" class="btn btn-info btn-sm">Acessar Campanha</a>
																	</div>

																</fieldset>


																<div class="push20"></div>

																<fieldset>
																	<legend>Gatilhos Sms</legend>

																	<div class="row">

																		<?php
																		// CAMPANHAS

																		$template1 = "<i class='fal fa-times' style=' margin-left: 6; font-size: 24px; opacity: 0.2;'></i>";
																		$template2 = "<i class='fal fa-times' style=' margin-left: 6; font-size: 24px; opacity: 0.2;'></i>";
																		$template3 = "<i class='fal fa-times' style=' margin-left: 6; font-size: 24px; opacity: 0.2;'></i>";
																		$template4 = "<i class='fal fa-times' style=' margin-left: 6; font-size: 24px; opacity: 0.2;'></i>";
																		$template5 = "<i class='fal fa-times' style=' margin-left: 6; font-size: 24px; opacity: 0.2;'></i>";
																		$template6 = "<i class='fal fa-times' style=' margin-left: 6; font-size: 24px; opacity: 0.2;'></i>";
																		$template7 = "<i class='fal fa-times' style=' margin-left: 6; font-size: 24px; opacity: 0.2;'></i>";
																		$template8 = "<i class='fal fa-times' style=' margin-left: 6; font-size: 24px; opacity: 0.2;'></i>";
																		$template9 = "<i class='fal fa-times' style=' margin-left: 6; font-size: 24px; opacity: 0.2;'></i>";

																		$buscaTemplate = "SELECT CP.DES_CAMPANHA FROM gatilho_sms AS GS 
																		INNER JOIN CAMPANHA AS CP ON GS.COD_CAMPANHA = CP.COD_CAMPANHA
																		WHERE CP.COD_EMPRESA = $cod_empresa";
																		$query = mysqli_query(connTemp($cod_empresa, ''), $buscaTemplate);
																		while ($result = mysqli_fetch_assoc($query)) {

																			if ($result['DES_CAMPANHA'] ==  'CashBack') {
																				$template1 = "<i class='fal fa-check' style='margin-left: 6; font-size: 24px; color: #28a745;'></i>";
																			}

																			if ($result['DES_CAMPANHA'] ==   $cod_empresa . ' - AUTOM SMS Expirar') {
																				$template2 = "<i class='fal fa-check' style='margin-left: 6; font-size: 24px; color: #28a745;'></i>";
																			}

																			if ($result['DES_CAMPANHA'] ==  $cod_empresa . ' - AUTOM SMS TK LGPD') {
																				$template3 = "<i class='fal fa-check' style='margin-left: 6; font-size: 24px; color: #28a745;'></i>";
																			}

																			if ($result['DES_CAMPANHA'] ==  $cod_empresa . ' - AUTOM SMS BVs') {
																				$template4 = "<i class='fal fa-check' style='margin-left: 6; font-size: 24px; color: #28a745;'></i>";
																			}

																			if ($result['DES_CAMPANHA'] ==  $cod_empresa . ' - AUTOM SMS TRANS') {
																				$template5 = "<i class='fal fa-check' style='margin-left: 6; font-size: 24px; color: #28a745;'></i>";
																			}

																			if ($result['DES_CAMPANHA'] ==  $cod_empresa . ' - AUTOM SMS Inativos 2a6') {
																				$template6 = "<i class='fal fa-check' style='margin-left: 6; font-size: 24px; color: #28a745;'></i>";
																			}

																			if ($result['DES_CAMPANHA'] ==  $cod_empresa . ' - AUTOM SMS Nivers') {
																				$template7 = "<i class='fal fa-check' style='margin-left: 6; font-size: 24px; color: #28a745;'></i>";
																			}

																			if ($result['DES_CAMPANHA'] ==  $cod_empresa . ' - SMS AUTOM Resgate') {
																				$template8 = "<i class='fal fa-check' style='margin-left: 6; font-size: 24px; color: #28a745;'></i>";
																			}

																			if ($result['DES_CAMPANHA'] ==  $cod_empresa . ' - SMS AUTOM Nivers Club 3B5') {
																				$template9 = "<i class='fal fa-check' style='margin-left: 6; font-size: 24px; color: #28a745;'></i>";
																			}
																		}

																		?>

																		<div class="col-md-3">
																			<div class="center-content" style="margin-top: 12px;">
																				<span>CashBack</span>
																				<?= $template1 ?>
																			</div>
																		</div>

																		<div class="col-md-3">
																			<div class="center-content" style="margin-top: 12px;">
																				<span> <?= $cod_empresa ?> - AUTOM SMS Expirar</span>
																				<?= $template2 ?>
																			</div>
																		</div>

																		<div class="col-md-3">
																			<div class="center-content" style="margin-top: 12px;">
																				<span><?= $cod_empresa ?> - AUTOM SMS TK LGPD</span>
																				<?= $template3 ?>
																			</div>
																		</div>

																		<div class="col-md-3">
																			<div class="center-content" style="margin-top: 12px;">
																				<span><?= $cod_empresa ?> - AUTOM SMS BVs</span>
																				<?= $template4 ?>
																			</div>
																		</div>

																		<div class="col-md-3">
																			<div class="center-content" style="margin-top: 12px;">
																				<span><?= $cod_empresa ?> - AUTOM SMS TRANS</span>
																				<?= $template5 ?>
																			</div>
																		</div>

																		<div class="col-md-3">
																			<div class="center-content" style="margin-top: 12px;">
																				<span><?= $cod_empresa ?> - AUTOM SMS Inativos 2a6</span>
																				<?= $template6 ?>
																			</div>
																		</div>

																		<div class="col-md-3">
																			<div class="center-content" style="margin-top: 12px;">
																				<span><?= $cod_empresa ?> - AUTOM SMS Nivers</span>
																				<?= $template7 ?>
																			</div>
																		</div>

																		<div class="col-md-3">
																			<div class="center-content" style="margin-top: 12px;">
																				<span><?= $cod_empresa ?> - SMS AUTOM Resgate</span>
																				<?= $template8 ?>
																			</div>
																		</div>

																		<div class="col-md-3">
																			<div class="center-content" style="margin-top: 12px;">
																				<span><?= $cod_empresa ?> - SMS AUTOM Nivers Club 3B5</span>
																				<?= $template9 ?>
																			</div>
																		</div>

																	</div>

																</fieldset>

																<div class="push20"></div>

																<fieldset>
																	<legend>Templates Sms</legend>

																	<div class="row">

																		<?php
																		// BUSCA USUARIO

																		$template1 = "<i class='fal fa-times' style=' margin-left: 6; font-size: 24px; opacity: 0.2;'></i>";
																		$template2 = "<i class='fal fa-times' style=' margin-left: 6; font-size: 24px; opacity: 0.2;'></i>";
																		$template3 = "<i class='fal fa-times' style=' margin-left: 6; font-size: 24px; opacity: 0.2;'></i>";
																		$template4 = "<i class='fal fa-times' style=' margin-left: 6; font-size: 24px; opacity: 0.2;'></i>";
																		$template5 = "<i class='fal fa-times' style=' margin-left: 6; font-size: 24px; opacity: 0.2;'></i>";
																		$template6 = "<i class='fal fa-times' style=' margin-left: 6; font-size: 24px; opacity: 0.2;'></i>";
																		$template7 = "<i class='fal fa-times' style=' margin-left: 6; font-size: 24px; opacity: 0.2;'></i>";
																		$template8 = "<i class='fal fa-times' style=' margin-left: 6; font-size: 24px; opacity: 0.2;'></i>";

																		$buscaTemplate = "SELECT NOM_TEMPLATE FROM TEMPLATE_SMS WHERE COD_EMPRESA = $cod_empresa";
																		$query = mysqli_query(connTemp($cod_empresa, ''), $buscaTemplate);
																		while ($result = mysqli_fetch_assoc($query)) {

																			if ($result['NOM_TEMPLATE'] == $cod_empresa . ' - Template AUTOM SMS Token') {
																				$template1 = "<i class='fal fa-check' style='margin-left: 6; font-size: 24px; color: #28a745;'></i>";
																			}

																			if ($result['NOM_TEMPLATE'] == $cod_empresa . ' - Template AUTOM SMS Boas Vindas') {
																				$template2 = "<i class='fal fa-check' style='margin-left: 6; font-size: 24px; color: #28a745;'></i>";
																			}

																			if ($result['NOM_TEMPLATE'] == $cod_empresa . ' - Template AUTOM SMS Niver') {
																				$template3 = "<i class='fal fa-check' style='margin-left: 6; font-size: 24px; color: #28a745;'></i>";
																			}

																			if ($result['NOM_TEMPLATE'] == $cod_empresa . ' - Template AUTOM SMS Expirar') {
																				$template4 = "<i class='fal fa-check' style='margin-left: 6; font-size: 24px; color: #28a745;'></i>";
																			}

																			if ($result['NOM_TEMPLATE'] == $cod_empresa . ' - Template AUTOM SMS Inativos 2a6') {
																				$template5 = "<i class='fal fa-check' style='margin-left: 6; font-size: 24px; color: #28a745;'></i>";
																			}

																			if ($result['NOM_TEMPLATE'] == $cod_empresa . ' - Template AUTOM SMS Transacional') {
																				$template6 = "<i class='fal fa-check' style='margin-left: 6; font-size: 24px; color: #28a745;'></i>";
																			}

																			if ($result['NOM_TEMPLATE'] == $cod_empresa . ' - Template AUTOM Nivers club 3B5') {
																				$template7 = "<i class='fal fa-check' style='margin-left: 6; font-size: 24px; color: #28a745;'></i>";
																			}

																			if ($result['NOM_TEMPLATE'] == $cod_empresa . ' - Template AUTOM SMS Resgate') {
																				$template8 = "<i class='fal fa-check' style='margin-left: 6; font-size: 24px; color: #28a745;'></i>";
																			}
																		}

																		?>

																		<div class="col-md-3">
																			<div class="center-content" style="margin-top: 12px;">
																				<span>Template AUTOM SMS Token</span>
																				<?= $template1 ?>
																			</div>
																		</div>

																		<div class="col-md-3">
																			<div class="center-content" style="margin-top: 12px;">
																				<span>Template AUTOM SMS Boas Vindas</span>
																				<?= $template2 ?>
																			</div>
																		</div>

																		<div class="col-md-3">
																			<div class="center-content" style="margin-top: 12px;">
																				<span>Template AUTOM SMS Niver</span>
																				<?= $template3 ?>
																			</div>
																		</div>

																		<div class="col-md-3">
																			<div class="center-content" style="margin-top: 12px;">
																				<span>Template AUTOM SMS Expirar</span>
																				<?= $template4 ?>
																			</div>
																		</div>

																		<div class="col-md-3">
																			<div class="center-content" style="margin-top: 12px;">
																				<span>Template AUTOM SMS Inativo 2a6</span>
																				<?= $template5 ?>
																			</div>
																		</div>

																		<div class="col-md-3">
																			<div class="center-content" style="margin-top: 12px;">
																				<span>Template AUTOM SMS Transacional</span>
																				<?= $template6 ?>
																			</div>
																		</div>

																		<div class="col-md-3">
																			<div class="center-content" style="margin-top: 12px;">
																				<span>Template AUTOM Nivers Club 3B5</span>
																				<?= $template7 ?>
																			</div>
																		</div>

																		<div class="col-md-3">
																			<div class="center-content" style="margin-top: 12px;">
																				<span>Template AUTOM SMS Resgate</span>
																				<?= $template8 ?>
																			</div>
																		</div>

																	</div>

																</fieldset>

															</form>
														</div>

													</div>

												</div>


											</div>

										</div>

										<div class="clearfix"></div>

									</div>



									<hr>

									<div class="form-group text-right col-lg-12">
										<button type="submit" name="CAD" id="CAD" class="btn btn-success getBtn"><i class="fas fa-cogs"></i>&nbsp;&nbsp;Processar</button>
										<?= $btnProximo ?>
									</div>

									<div class="push10"></div>

								</div>



							</div>


							<div class="push10"></div>

							<input type="hidden" name="opcao" id="opcao" value="">
							<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
							<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?= $cod_empresa ?>">
							<input type="hidden" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?= $nom_empresa ?>">
							<input type="hidden" name="NOM_FANTASI" id="NOM_FANTASI" value="<?= $nom_fantasi ?>">
							<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">

							<div class="push5"></div>

					</form>

					<div class="push50"></div>

				</div>

			</div>
		</div>
		<!-- fim Portlet -->
	</div>

</div>

<div class="push20"></div>

<script type="text/javascript">
	function retornaForm(index) {
		$("#formulario #COD_GRUPOTR").val($("#ret_COD_GRUPOTR_" + index).val());
		$("#formulario #DES_GRUPOTR").val($("#ret_DES_GRUPOTR_" + index).val());
		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
	}

	$(document).ready(function() {

		$('#NOM_ABVEMPRESA').on("blur", function() {
			let nomFantasi = $(this).val();
			$.ajax({
				type: "POST",
				url: "ajxValidaAutom.do?opcao=valFantasi&id=<?php echo fnEncode($cod_empresa); ?>",
				data: {
					NOM_FANTASI: nomFantasi
				},
				success: function(data) {
					if (data != "") {
						$('.validaTemp').html(data);
					} else {
						$('.validaTemp').html(''); // limpa se vier vazio
					}
				}
			});
		});

		// Verifica o estado do checkbox ao carregar a página
		if ($('#LOG_PROCTKT').prop('checked')) {
			$('#templateRow').show();
			$('#categoriaRow').show();
		} else {
			$('#templateRow').hide();
			$('#categoriaRow').hide();
		}

		// Monitora as mudanças no checkbox
		$('#LOG_PROCTKT').change(function() {
			if ($(this).prop('checked')) {
				$('#templateRow').show();
				$('#categoriaRow').show();
			} else {
				$('#templateRow').hide();
				$('#categoriaRow').hide();
			}
		});
	});
</script>