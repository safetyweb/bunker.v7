<?php

//echo "<h5>_".$opcao."</h5>";

$hashLocal = mt_rand();

$mostraBtnHot = "style='display:none'";
$mostraBtnFiltro = "style='display:none'";
$mostraBtnPerfil = "style='display:none'";
$mostraBtnFreq = "style='display:none'";
$mostraBtnPersona = "style='display:none'";
$checkTodos = "checked='checked'";

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
		$des_dominio = fnLimpaCampo($_REQUEST['DES_DOMINIO']);
		$des_email = fnLimpaCampo($_REQUEST['DES_EMAIL']);
		$des_programa = fnLimpaCampo($_REQUEST['DES_PROGRAMA']);
		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		if ($opcao != '') {

			//mensagem de retorno
			switch ($opcao) {
				case 'CAD':

					//INSERE TIPO DE FILTRO SE NÃO EXISTIR
					$sqlBusca = "SELECT COD_TPFILTRO FROM TIPO_FILTRO where COD_EMPRESA = $cod_empresa";
					$query = mysqli_query(connTemp($cod_empresa, ''), $sqlBusca);

					if (mysqli_num_rows($query) == 0) {

						$sqlInsert = "INSERT INTO TIPO_FILTRO(
						COD_EMPRESA,
						DES_TPFILTRO,
						LOG_REQUIRED,
						COD_USUCADA
						) VALUES(
						$cod_empresa,
						'Person of Interest',
						'N',
						$cod_usucada
						)";
						// fnEscreve($sqlInsert);
						$arrayInsert = mysqli_query(conntemp($cod_empresa, ""), $sqlInsert);

						if (!$arrayInsert) {
							$cod_erro = Log_error_comand($connAdm->connAdm(), conntemp($cod_empresa, ""), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sqlInsert, $nom_usuario);
						}
					}
					////// FIM BLOCO DE INSERÇÃO DE TIPO DE FILTRO

					//VERIFICA SE EXISTE O TIPO DE FILTRO, SE EXISTIR INSERE FILTROS CLIENTE
					$sqlBusca = "SELECT COD_TPFILTRO FROM TIPO_FILTRO where COD_EMPRESA = $cod_empresa";
					$query = mysqli_query(connTemp($cod_empresa, ''), $sqlBusca);

					if ($qrResult = mysqli_fetch_assoc($query)) {
						$cod_tpfiltro = $qrResult['COD_TPFILTRO'];
						$labEmp = "00 - LAB " . $nom_fantasi;

						//VERIFICA SE EXISTE ALGUM FILTRO CLIENTE
						$sqlBusca = "SELECT * FROM FILTROS_CLIENTE 
						WHERE COD_EMPRESA = $cod_empresa 
						AND (DES_FILTRO = '00 - LAB MK 00' OR DES_FILTRO = '$labEmp')";
						$query = mysqli_query(connTemp($cod_empresa, ''), $sqlBusca);
						$numRegistros = mysqli_num_rows($query);
						$qrResult = mysqli_fetch_assoc($query);

						if ($numRegistros == 0) {
							$arrayInsere = "(
								$cod_empresa,
								$cod_tpfiltro,
								'00 - LAB MK 00',
								$cod_usucada
								),(
								$cod_empresa,
								$cod_tpfiltro,
								'$labEmp',
								$cod_usucada
								)";
						} elseif ($numRegistros == 1 && $qrResult['DES_FILTRO'] == '00 - LAB MK 00') {
							$arrayInsere = "(
								$cod_empresa,
								$cod_tpfiltro,
								'$labEmp',
								$cod_usucada
								)";
						} elseif ($numRegistros == 1 && $qrResult['DES_FILTRO'] == $labEmp) {
							$arrayInsere = "(
								$cod_empresa,
								$cod_tpfiltro,
								'00 - LAB MK 00',
								$cod_usucada
								)";
						} else {
							$arrayInsere = "";
						}

						if ($arrayInsere != "") {
							$sqlInsert = "INSERT INTO FILTROS_CLIENTE(
								COD_EMPRESA,
								COD_TPFILTRO,
								DES_FILTRO,
								COD_USUCADA
								) VALUES $arrayInsere";
							// fnEscreve($sqlInsert);

							$query = mysqli_query(connTemp($cod_empresa, ''), $sqlInsert);
						} else {

							if (!$query) {
								$cod_erro = Log_error_comand($connAdm->connAdm(), conntemp($cod_empresa, ""), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sqlInsert, $nom_usuario);
							}

							$sqlBsca = "SELECT * FROM FILTROS_CLIENTE WHERE COD_EMPRESA = $cod_empresa";
							// fnEscreve($sqlBsca);

							$query = mysqli_query(connTemp($cod_empresa, ''), $sqlBsca);
							$cod_filtroUm = null;
							$cod_filtroDois = null;
							$contador = 0;

							while ($qrResult = mysqli_fetch_assoc($query)) {
								$contador++;

								if ($contador == 1) {
									$cod_filtroUm = $qrResult['COD_FILTRO'];
								} elseif ($contador == 2) {
									$cod_filtroDois = $qrResult['COD_FILTRO'];
									break;
								}
							}

							$array = array(
								"COD_TPFILTRO" => $cod_tpfiltro,
								"COD_FILTRO1" => $cod_filtroUm,
								"COD_FILTRO2" => $cod_filtroDois
							);

							$json_array = json_encode($array, JSON_UNESCAPED_UNICODE);

							//insere unidade de venda na auditoria
							$sqlAudit = "UPDATE AUDITORIA_EMPRESA SET 
									FILTRO_DINAMICO = '$json_array' 
									WHERE COD_EMPRESA = $cod_empresa";
							// fnEscreve($sqlAudit);

							$arrayProcAudit = mysqli_query($connAdm->connAdm(), trim($sqlAudit));
						}
					}

					////// FIM BLOCO DE INSERÇÃO DE FILTROS CLIENTE

					//VERIFICA SE EXISTE CLIENTE
					$sqlCliente = "SELECT * FROM CLIENTES WHERE COD_EMPRESA = $cod_empresa";
					$query = mysqli_query(connTemp($cod_empresa, ''), $sqlCliente);
					$numRegistros = mysqli_num_rows($query);

					if ($numRegistros == 0) {
						$sqlUniv = "SELECT COD_UNIVEND FROM UNIDADEVENDA WHERE COD_EMPRESA = $cod_empresa LIMIT 1";
						$query = mysqli_query($connAdm->connAdm(), $sqlUniv);
						$qrResult = mysqli_fetch_assoc($query);

						$sql = "CALL SP_ALTERA_CLIENTES( '0', '$cod_empresa', 'Maurice Cartagena', '', 'maurice@markafidelizacao.com.br', '$cod_usucada', '21737545802', 'S', 'N', '', '07/09/2000', '0', '1', '', '16997970129', '', '', '21737545802', '0', '', '', '', '', '', '', '', '', '0', " . $qrResult['COD_UNIVEND'] . ", " . $qrResult['COD_UNIVEND'] . ", 'F', '', 'S', 'S', 'S', 'S', 'S', 'S', '', '', '1', '0', '', '0', 'N', 'N', 'S', '', 'CAD' );";
						$arrayProc = mysqli_query(connTemp($cod_empresa, ''), $sql);
						$qrGravaCliente = mysqli_fetch_assoc($arrayProc);
						$cod_clienteRetorno = $qrGravaCliente['COD_CLIENTE'];

						$sql = "UPDATE CLIENTES SET LOG_TERMO = 'S' WHERE COD_CLIENTE = $cod_clienteRetorno AND COD_EMPRESA = $cod_empresa";
						mysqli_query(connTemp($cod_empresa, ''), $sql);

						if (!$arrayProc) {
							$cod_erro = Log_error_comand($connAdm->connAdm(), connTemp($cod_empresa, ''), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql, $nom_usuario);
						} else {
							$sqlBusca = "SELECT TF.COD_TPFILTRO, FC.COD_FILTRO FROM tipo_filtro AS TF 
								INNER JOIN filtros_cliente AS FC ON TF.COD_TPFILTRO = FC.COD_TPFILTRO
								WHERE TF.COD_EMPRESA = $cod_empresa AND TF.DES_TPFILTRO = 'Person of Interest'
								AND FC.DES_FILTRO = '00 - LAB MK 00'";

							$queryRes = mysqli_query(connTemp($cod_empresa, ''), $sqlBusca);
							if ($qrResult = mysqli_fetch_assoc($queryRes)) {
								$cod_tpfiltro = $qrResult['COD_TPFILTRO'];
								$cod_filtro = $qrResult['COD_FILTRO'];
								$sql = "INSERT INTO CLIENTE_FILTROS(
											COD_EMPRESA,
											COD_TPFILTRO,
											COD_FILTRO,
											COD_CLIENTE,
											COD_USUCADA
											)VALUES(
											$cod_empresa,
											$cod_tpfiltro,
											$cod_filtro,
											$cod_clienteRetorno,
											$cod_usucada)";

								$arrayProc = mysqli_query(connTemp($cod_empresa, ''), $sql);
							}


							$sqlAudit = "UPDATE AUDITORIA_EMPRESA SET 
									COD_CLIENTE = $cod_clienteRetorno
									WHERE COD_EMPRESA = $cod_empresa";

							$arrayProcAudit = mysqli_query($connAdm->connAdm(), trim($sqlAudit));
						}
					}

					///FIM INSERE CLIENTE

					/// INSERE CLIENTE AVULSO
					$sqlBusca = "SELECT COD_CLIENTE_AV FROM EMPRESAS WHERE COD_EMPRESA = $cod_empresa";
					$arrayQuery = mysqli_query($connAdm->connAdm(), $sqlBusca);
					if (mysqli_num_rows($arrayQuery) == 0) {
						$sql = "CALL SP_CADASTRA_CLIENTE_AVULSO (
								'" . $cod_empresa . "', 
								'S'   
								) ";

						//fnEscreve($sql);														
						$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
						$qrBuscaClienteAvulso = mysqli_fetch_assoc($arrayQuery);

						$cod_avulso = $qrBuscaClienteAvulso['COD_CLIENTE'];

						$sql2 = "UPDATE EMPRESAS SET COD_CLIENTE_AV = $cod_avulso WHERE COD_EMPRESA = $cod_empresa ";
						$arrayQuery2 = mysqli_query($connAdm->connAdm(), $sql2);
					}


					/// FIM INSERE CLIENTE AVULSO

					////// BLOCO DE INSERÇÃO DE PERFIL
					$sqlBusca = "SELECT COD_PERFILS FROM PERFIL WHERE COD_EMPRESA = $cod_empresa";
					$query = mysqli_query($connAdm->connAdm(), $sqlBusca);

					if (mysqli_num_rows($query) == 0) {

						$sqlPerfil = "insert into PERFIL (
									DES_PERFILS,
									COD_SISTEMA,
									COD_EMPRESA,
									COD_MODULOS) values (
									'Marka Restrito',
									'4',
									$cod_empresa,
									'1280'),(
									'Gerencial',
									'4',
									$cod_empresa,
									'1102,1072,1081,1479,1195,1490,1210,1266,1216,1342,1246,1859,1139,1896,1223,1882,1620,1618,1280'
									),(
									'Suporte',
									'4',
									$cod_empresa,
									'1280')";
						// fnEscreve($sqlPerfil);

						$qrInsert = mysqli_query($connAdm->connAdm(), $sqlPerfil);

						if (!$qrInsert) {

							$cod_erro = Log_error_comand($connAdm->connAdm(), $conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $SqlInsPerfil, $nom_usuario);
						} else {
							$sqlBusca = "SELECT COD_PERFILS FROM PERFIL WHERE COD_EMPRESA = $cod_empresa";
							// fnEscreve($sqlBusca);

							$query = mysqli_query($connAdm->connAdm(), $sqlBusca);
							$cod_perfil = "";

							while ($qrBusca = mysqli_fetch_assoc($query)) {
								if ($cod_perfil == "") {
									$cod_perfil = $qrBusca['COD_PERFILS'];
								} else {
									$cod_perfil .= "," . $qrBusca['COD_PERFILS'];
								}
							}

							$sqlAudit = "UPDATE AUDITORIA_EMPRESA SET 
										COD_PERFIL = '$cod_perfil' 
										WHERE COD_EMPRESA = $cod_empresa";
							// fnEscreve($sqlAudit);

							$arrayProcAudit = mysqli_query($connAdm->connAdm(), trim($sqlAudit));
						}
					}

					////// FIM BLOCO DE INSERÇÃO DE PERFIL

					////// BLOCO DE INSERÇÃO DE FUNIL POR GASTO
					$sqlbusca = "SELECT * FROM FREQUENCIA_CLIENTE WHERE COD_EMPRESA = $cod_empresa";
					$query = mysqli_query(connTemp($cod_empresa, ''), $sqlbusca);
					if (mysqli_num_rows($query) == 0) {
						$sql = "INSERT INTO FREQUENCIA_CLIENTE(
							PCT_FANS,
							PCT_FIEIS,
							PCT_FREQUENTES,
							PCT_CASUAIS,
							TXT_FANS,
							TXT_FIEIS,
							TXT_FREQUENTES,
							TXT_CASUAIS,
							QTD_DIASHIST,
							QTD_INATIVO,
							QTD_MESCLASS,
							COD_USUCADA,
							COD_EMPRESA,
							DAT_CADASTR
							)VALUES(
							'5.00',
							'15.00',
							'30.00',
							'50.00',
							'Fã',
							'Fiel',
							'Frequente',
							'Casual',
							90,
							60,
							1,
							$cod_usucada,
							$cod_empresa,
							NOW()
							);";
						// fnEscreve($sql);

						$arrayInsert = mysqli_query(conntemp($cod_empresa, ""), $sql);

						if (!$arrayInsert) {

							$cod_erro = Log_error_comand($connAdm->connAdm(), conntemp($cod_empresa, ""), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql, $nom_usuario);
						} else {
							$sqlbusca = "SELECT * FROM FREQUENCIA_CLIENTE WHERE COD_EMPRESA = $cod_empresa";
							// fnEscreve($sqlbusca);

							$query = mysqli_query(connTemp($cod_empresa, ''), $sqlbusca);
							if ($qrResult = mysqli_fetch_assoc($query)) {
								$sqlAudit = "UPDATE AUDITORIA_EMPRESA SET 
								COD_FREQUENCIA = " . $qrResult['COD_FREQUENCIA'] . "
								WHERE COD_EMPRESA = $cod_empresa";
								// fnEscreve($sqlAudit);

								$arrayProcAudit = mysqli_query($connAdm->connAdm(), trim($sqlAudit));
							}
						}
					}

					////// FIM BLOCO DE INSERÇÃO DE FREQUENCIA CLIENTE


					//INSERE HOTSITE

					$sqlbusca = "SELECT COD_EXTRATO FROM SITE_EXTRATO WHERE COD_EMPRESA = $cod_empresa";
					// fnEscreve($sqlbusca);

					$query = mysqli_query(connTemp($cod_empresa, ''), $sqlbusca);

					if (mysqli_num_rows($query) == 0) {

						$sqlHotSite = "CALL SP_ALTERA_SITE_EXTRATO ( '0',
						'$cod_empresa',
						'2',
						'$des_dominio',
						'',
						'',
						'',
						'$des_email',
						'N',
						'',
						'S',
						'Vantagens',
						'S',
						'Regulamento',
						'S',
						'Lojas',
						'N',
						'FAQ',
						'S',
						'Extrato',
						'S',
						'Contato',
						'ffffff',
						'34495e',
						'ffffff',
						'34495e',
						'34495e',
						'34495e',
						'ffffff',
						'0092d8',
						'48c9b0',
						'ffffff',
						'Participe agora e comece a ganhar.',
						'',
						'',
						'icons/money.svg',
						'icons/pig.svg',
						'icons/iphone.svg',
						'Ganhe dinheiro para suas próximas compras',
						'Alcance outros Níveis',
						'Acesso Total',
						'Você acumula dinheiro e pode trocar por descontos ou prêmios.',
						'Quanto mais você participa,
						mais dinheiro você acumula.',
						'Transparência: Veja seu histórico de compras,
						ganhos e resgates.',
						'',
						'',
						'$des_programa',
						'S',
						'S',
						'N',
						'Prêmios',
						'N',
						'O Programa',
						'',
						'N',
						'DESC' )";

						$arrayProc = mysqli_query(connTemp($cod_empresa, ''), $sqlHotSite);

						if (!$arrayProc) {

							$cod_erro = Log_error_comand($connAdm->connAdm(), connTemp($cod_empresa, ''), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sqlHotSite, $nom_usuario);
						} else {

							$sqlbusca = "SELECT COD_EXTRATO FROM SITE_EXTRATO WHERE COD_EMPRESA = $cod_empresa";
							// fnEscreve($sqlbusca);

							$query = mysqli_query(connTemp($cod_empresa, ''), $sqlbusca);
							if ($qrResult = mysqli_fetch_assoc($query)) {
								$sqlAudit = "UPDATE AUDITORIA_EMPRESA SET 
								COD_HOTSITE = " . $qrResult['COD_EXTRATO'] . "
								WHERE COD_EMPRESA = $cod_empresa";
								// fnEscreve($sqlAudit);

								$arrayProcAudit = mysqli_query($connAdm->connAdm(), trim($sqlAudit));
							}
						}
					}

					//FIM INSERE HOTSITE

					// GERA PERSONAS
					$sqlBusca = "SELECT COD_PERSONA, DES_PERSONA FROM PERSONA WHERE COD_EMPRESA = $cod_empresa";
					$query = mysqli_query(connTemp($cod_empresa, ''), $sqlBusca);
					$qtd_linhas = mysqli_num_rows($query);
					if ($qtd_linhas < 9) {

						$arrayPersonas = array();
						$count = 0;
						if ($qtd_linhas > 0) {
							while ($qrResult = mysqli_fetch_assoc($query)) {
								// Armazena apenas o COD_PERSONA e DES_PERSONA no array
								$arrayPersonas['PERSONAS'][$qrResult['DES_PERSONA']] = $qrResult['COD_PERSONA'];
								$count++;
							}
						}

						$sqlPersona = "";
						// Checa se 'Fidelidade' já existe no array de personas
						if (isset($_POST['LOG_FIDELIDADE'])) {
							if (empty($arrayPersonas['PERSONAS']) || !array_key_exists('Fidelidade (acesso restrito)', $arrayPersonas['PERSONAS'])) {
								$sqlPersona .= "CALL SP_ALTERA_PERSONA('0', '$cod_empresa', 'S', 'Fidelidade (acesso restrito)', '', 'fal fa-user-tag', '2c3e50', '', 'N', 'N', '$cod_usucada', '9999', 'CAD'); ";
							}
						}
						// Checa se 'T.O. Homem' já existe no array de personas
						if (isset($_POST['LOG_TOHOMEM'])) {
							if (empty($arrayPersonas['PERSONAS']) || !array_key_exists('T.O. Homem', $arrayPersonas['PERSONAS'])) {
								$sqlPersona .= "CALL SP_ALTERA_PERSONA('0', '$cod_empresa', 'S', 'T.O. Homem', '', 'fal fa-user-tag', '2c3e50', '', 'N', 'N', '$cod_usucada', '9999', 'CAD');";
							}
						}
						// Checa se 'T.O. Mulher' já existe no array de personas
						if (isset($_POST['LOG_TOMULHER'])) {
							if (empty($arrayPersonas['PERSONAS']) || !array_key_exists('T.O. Mulher', $arrayPersonas['PERSONAS'])) {
								$sqlPersona .= "CALL SP_ALTERA_PERSONA ( '0', '$cod_empresa', 'S', 'T.O. Mulher', '', 'fal fa-user-tag', '2c3e50', '', 'N', 'N', '$cod_usucada', '9999', 'CAD' );";
							}
						}
						// Checa se 'T.O. Melhor Idade' já existe no array de personas
						if (isset($_POST['LOG_TOMELIDADE'])) {
							if (empty($arrayPersonas['PERSONAS']) || !array_key_exists('T.O. Melhor Idade', $arrayPersonas['PERSONAS'])) {
								$sqlPersona .= "CALL SP_ALTERA_PERSONA ( '0', '$cod_empresa', 'S', 'T.O. Melhor Idade', '', 'fal fa-user-tag', '2c3e50', '', 'N', 'N', '$cod_usucada', '9999', 'CAD' );";
							}
						}
						// Checa se '00- LAB MK 00' já existe no array de personas
						if (isset($_POST['LOG_LABMK00'])) {
							if (empty($arrayPersonas['PERSONAS']) || !array_key_exists('00- LAB MK 00', $arrayPersonas['PERSONAS'])) {
								$sqlPersona .= "CALL SP_ALTERA_PERSONA ( '0', '$cod_empresa', 'S', '00- LAB MK 00', '', 'fas fa-cogs', 'ff030b', '', 'N', 'N', '$cod_usucada', '9999', 'CAD' );";
							}
						}
						// Checa se '00- LAB NOME EMPRESA' já existe no array de personas
						if (isset($_POST['LOG_00LABEMP'])) {
							$labEmp = "00 - LAB " . $nom_fantasi;
							if (empty($arrayPersonas['PERSONAS']) || !array_key_exists($labEmp, $arrayPersonas['PERSONAS'])) {
								$sqlPersona .= "CALL SP_ALTERA_PERSONA ( '0', '$cod_empresa', 'S', '$labEmp', '', 'fas fa-cogs', 'ff030b', '', 'N', 'N', '$cod_usucada', '9999', 'CAD' );";
							}
						}

						// Checa se 'AUTOM SMS INATIVO' já existe no array de personas
						if (isset($_POST['LOG_AUTSMSINAT'])) {
							$automInatEmp = $cod_empresa . " - Autom SMS Inativo 2a6";
							if (empty($arrayPersonas['PERSONAS']) || !array_key_exists($automInatEmp, $arrayPersonas['PERSONAS'])) {
								$sqlPersona .= "CALL SP_ALTERA_PERSONA ( '0', '$cod_empresa', 'S', '$automInatEmp', '', 'fas fa-cogs', 'ff030b', '', 'N', 'N', '$cod_usucada', '9999', 'CAD' );";
							}
						}

						// Checa se 'Autom SMS Nivers' já existe no array de personas
						if (isset($_POST['LOG_AUTSMSNIVER'])) {
							$automNiverEmp = $cod_empresa . " - Autom SMS Nivers";
							if (empty($arrayPersonas['PERSONAS']) || !array_key_exists($automNiverEmp, $arrayPersonas['PERSONAS'])) {
								$sqlPersona .= "CALL SP_ALTERA_PERSONA ( '0', '$cod_empresa', 'S', '$automNiverEmp', '', 'fas fa-cogs', 'ff030b', '', 'N', 'N', '$cod_usucada', '9999', 'CAD' );";
							}
						}

						// Checa se 'Autom SMS Nivers' já existe no array de personas
						if (isset($_POST['LOG_AUTSMSNIVERCLUB'])) {
							$automClub = $cod_empresa . " - AUTOM SMS Nivers Club 3B5";
							if (empty($arrayPersonas['PERSONAS']) || !array_key_exists($automClub, $arrayPersonas['PERSONAS'])) {
								$sqlPersona .= "CALL SP_ALTERA_PERSONA ( '0', '$cod_empresa', 'S', '$automClub', '', 'fas fa-cogs', 'ff030b', '', 'N', 'N', '$cod_usucada', '9999', 'CAD' );";
							}
						}
						// fnEscreve($sqlPersona);
						// Se algum comando SQL foi gerado, executa as queries
						if ($sqlPersona != "") {
							$arrayInsert = explode(';', $sqlPersona);
							$arrayInsert = array_filter($arrayInsert);
							$qtd_total = count($arrayInsert);
							foreach ($arrayInsert as $sql) {
								$query = mysqli_query(connTemp($cod_empresa, ''), $sql);
								// if (!$query) {
								// 	$cod_erro = Log_error_comand($connAdm->connAdm(), connTemp($cod_empresa, ''), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql, $nom_usuario);
								// }
							}

							$sqlBusca = "SELECT COD_PERSONA FROM PERSONA WHERE COD_EMPRESA = $cod_empresa";
							$query = mysqli_query(connTemp($cod_empresa, ''), $sqlBusca);
							$cod_persona = "";
							while ($qrResult = mysqli_fetch_assoc($query)) {
								$cod_persona .= $qrResult['COD_PERSONA'] . ",";
							}

							if ($cod_persona != "") {
								$cod_persona = rtrim($cod_persona, ",");

								$sqlAudit = "UPDATE AUDITORIA_EMPRESA SET 
								COD_PERSONA = '" . $cod_persona . "'
								WHERE COD_EMPRESA = $cod_empresa";
								// fnEscreve($sqlAudit);

								$arrayProcAudit = mysqli_query($connAdm->connAdm(), trim($sqlAudit));
							}
						}
					}
					// FIM GERA PERSONAS	

					//REGRAS PERSONAS
					$sqlBusca = "SELECT COD_PERSONA, DES_PERSONA FROM PERSONA WHERE COD_EMPRESA = $cod_empresa";
					$queryPersona = mysqli_query(connTemp($cod_empresa, ''), $sqlBusca);
					$qtd_linhasPersona = mysqli_num_rows($queryPersona);
					if ($qtd_linhasPersona > 0) {

						$sqlRegra = "SELECT PR.COD_PERSONA FROM PERSONAREGRA AS PR
								INNER JOIN PERSONA AS PS ON PR.COD_PERSONA = PS.COD_PERSONA WHERE PS.COD_EMPRESA = $cod_empresa";
						$query = mysqli_query(connTemp($cod_empresa, ''), $sqlRegra);
						//VERIFICA SE AS REGRAS DA PERSONA EXISTEM
						$qtd_linhasRegra = mysqli_num_rows($query);
						if ($qtd_linhasRegra == 0 || $qtd_linhasRegra < 9) {

							//VERIFICA SE A EMPRESA USA TOKEN
							$sql = "SELECT LOG_CADTOKEN FROM EMPRESAS WHERE COD_EMPRESA = $cod_empresa";
							$query = mysqli_query($connAdm->connAdm(), $sql);
							$qrResult = mysqli_fetch_assoc($query);

							$log_comunica = $qrResult['LOG_CADTOKEN'];

							// MONTA ARRAY PERSONAS
							$arrayPersonas = array();
							$count = 0;
							if ($qtd_linhasPersona > 0) {
								while ($qrResult = mysqli_fetch_assoc($queryPersona)) {
									$arrayPersonas['PERSONAS'][$qrResult['DES_PERSONA']] = $qrResult['COD_PERSONA'];
									$count++;
								}
							}

							$arrayRegras = array();
							$countRegras = 0;
							if ($qtd_linhasRegra > 0) {
								while ($qrRegras = mysqli_fetch_assoc($query)) {
									$arrayRegras['PERSONASREGRA'][$qrRegras['COD_PERSONA']] = $qrRegras['COD_PERSONA'];
									$countRegras++;
								}
							}

							$sqlPersonaRegra = "";
							$sqlFiltros = "";
							if (!isset($arrayRegras['PERSONASREGRA'])) {
								$arrayRegras['PERSONASREGRA'] = 0;
							}

							// Checa se 'Fidelidade' já existe no array de personas
							if (isset($_POST['LOG_FIDELIDADE'])) {
								if (
									empty($arrayRegras['PERSONASREGRA']) ||
									(is_array($arrayPersonas['PERSONAS']) && array_key_exists('Fidelidade (acesso restrito)', $arrayPersonas['PERSONAS']) &&
										is_array($arrayRegras['PERSONASREGRA']) &&
										!array_key_exists($arrayPersonas['PERSONAS']['Fidelidade (acesso restrito)'], $arrayRegras['PERSONASREGRA']))
								) {
									$codPersona = $arrayPersonas['PERSONAS']['Fidelidade (acesso restrito)'];
									$sqlPersonaRegra .= "CALL SP_ALTERA_PERSONAREGRA ( '$codPersona', 'S', 'S', 'S', 'S', 'N', 'S', '0;150', 'N', 'N', 'N', 'N', 'N', 'N', '0', '', '0', 'S', 'S', '$log_comunica', '$log_comunica', '$log_comunica', '$log_comunica', '$log_comunica', 'N', '$cod_usucada', '', '', '', '', '', '', '0', '0', '0', '0', '', '', 'N', '', '', 'N', '', '', 'N', '', '0', '0', '0', '0', '0', '0', '0', '0', '0', '', '0', '0', '0', '', '', '0', '0', 'C', 'N', 'S', '0', '0', '0', '0', 'N', 'N', 'N', 'N', '', '', '0', NULL, NULL); ";
								}
							}

							// Checa se 'T.O. Homem' já existe no array de personas
							if (isset($_POST['LOG_TOHOMEM'])) {
								if (
									empty($arrayRegras['PERSONASREGRA']) ||
									(is_array($arrayPersonas['PERSONAS']) && array_key_exists('T.O. Homem', $arrayPersonas['PERSONAS']) &&
										is_array($arrayRegras['PERSONASREGRA']) &&
										!array_key_exists($arrayPersonas['PERSONAS']['T.O. Homem'], $arrayRegras['PERSONASREGRA']))
								) {
									$codPersona = $arrayPersonas['PERSONAS']['T.O. Homem'];
									$sqlPersonaRegra .= "CALL SP_ALTERA_PERSONAREGRA ( '$codPersona', 'S', 'N', 'S', 'N', 'N', 'N', '18;150', 'N', 'N', 'N', 'N', 'N', 'N', '0', '', '0', 'S', 'S', '$log_comunica', '$log_comunica', '$log_comunica', '$log_comunica', '$log_comunica', 'N', '$cod_usucada', '', '', '', '', '', '', '0', '0', '0', '0', '', '', 'N', '', '', 'N', '', '', 'N', '', '0', '0', '0', '0', '0', '0', '0', '0', '0', '', '0', '0', '0', '', '', '0', '0', 'C', 'N', 'S', '0', '0', '0', '0', 'N', 'N', 'N', 'N', '', '', '0', NULL, NULL);";
								}
							}

							// Checa se 'T.O. Mulher' já existe no array de personas
							if (isset($_POST['LOG_TOMULHER'])) {
								if (
									empty($arrayRegras['PERSONASREGRA']) ||
									(is_array($arrayPersonas['PERSONAS']) && array_key_exists('T.O. Mulher', $arrayPersonas['PERSONAS']) &&
										is_array($arrayRegras['PERSONASREGRA']) &&
										!array_key_exists($arrayPersonas['PERSONAS']['T.O. Mulher'], $arrayRegras['PERSONASREGRA']))
								) {
									$codPersona = $arrayPersonas['PERSONAS']['T.O. Mulher'];
									$sqlPersonaRegra .= "CALL SP_ALTERA_PERSONAREGRA ( '$codPersona', 'N', 'S', 'N', 'S', 'N', 'N', '18;150', 'N', 'N', 'N', 'N', 'N', 'N', '0', '', '0', 'S', 'S', '$log_comunica', '$log_comunica', '$log_comunica', '$log_comunica', '$log_comunica', 'N', '$cod_usucada', '', '', '', '', '', '', '0', '0', '0', '0', '', '', 'N', '', '', 'N', '', '', 'N', '', '0', '0', '0', '0', '0', '0', '0', '0', '0', '', '0', '0', '0', '', '', '0', '0', 'C', 'N', 'S', '0', '0', '0', '0', 'N', 'N', 'N', 'N', '', '', '0', NULL, NULL);";
								}
							}

							// Checa se 'T.O. Melhor Idade' já existe no array de personas
							if (isset($_POST['LOG_TOMELIDADE'])) {
								if (
									empty($arrayRegras['PERSONASREGRA']) ||
									(is_array($arrayPersonas['PERSONAS']) && array_key_exists('T.O. Melhor Idade', $arrayPersonas['PERSONAS']) &&
										is_array($arrayRegras['PERSONASREGRA']) &&
										!array_key_exists($arrayPersonas['PERSONAS']['T.O. Melhor Idade'], $arrayRegras['PERSONASREGRA']))
								) {
									$codPersona = $arrayPersonas['PERSONAS']['T.O. Melhor Idade'];
									$sqlPersonaRegra .= "CALL SP_ALTERA_PERSONAREGRA ( '$codPersona', 'S', 'S', 'S', 'S', 'N', 'S', '60;150', 'N', 'N', 'N', 'N', 'N', 'N', '0', '', '0', 'S', 'S', '$log_comunica', '$log_comunica', '$log_comunica', '$log_comunica', '$log_comunica', 'N', '$cod_usucada', '', '', '', '', '', '', '0', '0', '0', '0', '', '', 'N', '', '', 'N', '', '', 'N', '', '0', '0', '0', '0', '0', '0', '0', '0', '0', '', '0', '0', '0', '', '', '0', '0', 'C', 'N', 'S', '0', '0', '0', '0', 'N', 'N', 'N', 'N', '', '', '0', NULL, NULL);";
								}
							}

							// Checa se '00- LAB MK 00' já existe no array de personas
							if (isset($_POST['LOG_LABMK00'])) {
								if (
									empty($arrayRegras['PERSONASREGRA']) ||
									(is_array($arrayPersonas['PERSONAS']) && array_key_exists('00- LAB MK 00', $arrayPersonas['PERSONAS']) &&
										is_array($arrayRegras['PERSONASREGRA']) &&
										!array_key_exists($arrayPersonas['PERSONAS']['00- LAB MK 00'], $arrayRegras['PERSONASREGRA']))
								) {
									$codPersona = $arrayPersonas['PERSONAS']['00- LAB MK 00'];
									$sqlPersonaRegra .= "CALL SP_ALTERA_PERSONAREGRA ( '$codPersona', 'S', 'S', 'N', 'N', 'N', 'N', '18;150', 'S', '', 'N', 'N', 'N', 'N', '0', '', '0', 'S', 'S', '$log_comunica', '$log_comunica', '$log_comunica', '$log_comunica', '$log_comunica', 'N', '$cod_usucada', '', '', '', '', '', '', '0', '0', '0', '0', '', '', 'N', '', '', 'N', '', '', 'N', '', '0', '0', '0', '0', '0', '0', '0', '0', '0', '', '0', '0', '0', '', '', '0', '0', 'C', 'N', 'S', '0', '0', '0', '0', 'N', 'N', 'N', 'N', '', '', '0', NULL, NULL);";

									//BUSCA FILTRO
									$sqlBusca = "SELECT COD_FILTRO FROM FILTROS_CLIENTE WHERE COD_EMPRESA = $cod_empresa AND DES_FILTRO = '00 - LAB MK 00'";
									$queryFiltro = mysqli_query(connTemp($cod_empresa, ''), $sqlBusca);
									$qrResult = mysqli_fetch_assoc($queryFiltro);

									//BUSCA TIPO FILTRO
									$sqlBuscaTpFiltro = "SELECT COD_TPFILTRO FROM TIPO_FILTRO WHERE COD_EMPRESA = $cod_empresa AND DES_TPFILTRO = 'Person of Interest'";
									$queryTpFiltro = mysqli_query(connTemp($cod_empresa, ''), $sqlBuscaTpFiltro);
									$qrTpFiltro = mysqli_fetch_assoc($queryTpFiltro);

									$sqlFiltros .= "INSERT INTO FILTROS_PERSONA( COD_EMPRESA, COD_TPFILTRO, COD_FILTRO, COD_PERSONA, COD_USUCADA )VALUES( $cod_empresa, " . $qrTpFiltro['COD_TPFILTRO'] . ", " . $qrResult['COD_FILTRO'] . ", $codPersona, $cod_usucada );";
								}
							}

							// Checa se '00- LAB NOME EMPRESA' já existe no array de personas
							if (isset($_POST['LOG_00LABEMP'])) {
								$labEmp = "00 - LAB " . $nom_fantasi;
								if (
									empty($arrayRegras['PERSONASREGRA']) ||
									(is_array($arrayPersonas['PERSONAS']) && array_key_exists($labEmp, $arrayPersonas['PERSONAS']) &&
										is_array($arrayRegras['PERSONASREGRA']) &&
										!array_key_exists($arrayPersonas['PERSONAS'][$labEmp], $arrayRegras['PERSONASREGRA']))
								) {
									$codPersona = $arrayPersonas['PERSONAS'][$labEmp];
									$sqlPersonaRegra .= "CALL SP_ALTERA_PERSONAREGRA ( '$codPersona', 'S', 'S', 'N', 'N', 'N', 'N', '18;150', 'S', '', 'N', 'N', 'N', 'N', '0', '', '0', 'S', 'S', '$log_comunica', '$log_comunica', '$log_comunica', '$log_comunica', '$log_comunica', 'N', '$cod_usucada', '', '', '', '', '', '', '0', '0', '0', '0', '', '', 'N', '', '', 'N', '', '', 'N', '', '0', '0', '0', '0', '0', '0', '0', '0', '0', '', '0', '0', '0', '', '', '0', '0', 'C', 'N', 'S', '0', '0', '0', '0', 'N', 'N', 'N', 'N', '', '', '0', NULL, NULL);";

									//BUSCA FILTRO
									$sqlBusca = "SELECT COD_FILTRO FROM FILTROS_CLIENTE WHERE COD_EMPRESA = $cod_empresa AND DES_FILTRO = '$labEmp'";
									$queryFiltro = mysqli_query(connTemp($cod_empresa, ''), $sqlBusca);
									$qrResult = mysqli_fetch_assoc($queryFiltro);

									//BUSCA TIPO FILTRO
									$sqlBuscaTpFiltro = "SELECT COD_TPFILTRO FROM TIPO_FILTRO WHERE COD_EMPRESA = $cod_empresa AND DES_TPFILTRO = 'Person of Interest'";
									$queryTpFiltro = mysqli_query(connTemp($cod_empresa, ''), $sqlBuscaTpFiltro);
									$qrTpFiltro = mysqli_fetch_assoc($queryTpFiltro);

									$sqlFiltros .= "INSERT INTO FILTROS_PERSONA( COD_EMPRESA, COD_TPFILTRO, COD_FILTRO, COD_PERSONA, COD_USUCADA )VALUES( $cod_empresa, " . $qrTpFiltro['COD_TPFILTRO'] . ", " . $qrResult['COD_FILTRO'] . ", $codPersona, $cod_usucada );";
								}
							}

							// Checa se 'AUTOM SMS INATIVO' já existe no array de personas
							if (isset($_POST['LOG_AUTSMSINAT'])) {
								$automInatEmp = $cod_empresa . " - Autom SMS Inativo 2a6";
								if (
									empty($arrayRegras['PERSONASREGRA']) ||
									(is_array($arrayPersonas['PERSONAS']) && array_key_exists($automInatEmp, $arrayPersonas['PERSONAS']) &&
										is_array($arrayRegras['PERSONASREGRA']) &&
										!array_key_exists($arrayPersonas['PERSONAS'][$automInatEmp], $arrayRegras['PERSONASREGRA']))
								) {
									$codPersona = $arrayPersonas['PERSONAS'][$automInatEmp];
									$sqlPersonaRegra .= "CALL SP_ALTERA_PERSONAREGRA ( '$codPersona', 'S', 'S', 'N', 'N', 'N', 'S', '18;150', 'N', 'N', 'N', 'N', 'N', 'N', 'N', 'N', '0', 'S', 'S', '$log_comunica', '$log_comunica', '$log_comunica', '$log_comunica', '$log_comunica', 'N', '$cod_usucada', '', '', '', '', '', '', '61', '180', '0', '0', '', '', 'N', '', '', 'N', '', '', 'N', '', '0', '0', '0', '0', '0', '0', '0', '0', '0', '', '0', '0', '0', '', '', '0', '0', 'C', 'N', 'S', '0', '0', '0', '0', 'N', 'N', 'N', 'N', '', '', '0', NULL, NULL);";
								}
							}

							// Checa se 'Autom SMS Nivers' já existe no array de personas
							if (isset($_POST['LOG_AUTSMSNIVER'])) {
								$automNiverEmp = $cod_empresa . " - Autom SMS Nivers";
								if (
									empty($arrayRegras['PERSONASREGRA']) ||
									(is_array($arrayPersonas['PERSONAS']) && array_key_exists($automNiverEmp, $arrayPersonas['PERSONAS']) &&
										is_array($arrayRegras['PERSONASREGRA']) &&
										!array_key_exists($arrayPersonas['PERSONAS'][$automNiverEmp], $arrayRegras['PERSONASREGRA']))
								) {
									$codPersona = $arrayPersonas['PERSONAS'][$automNiverEmp];
									$sqlPersonaRegra .= "CALL SP_ALTERA_PERSONAREGRA ( '$codPersona', 'S', 'S', 'N', 'N', 'N', 'S', '18;150', 'N', 'N', 'N', 'N', 'N', 'N', '0', '', '0', 'S', 'S', '$log_comunica', '$log_comunica', '$log_comunica', '$log_comunica', '$log_comunica', 'N', '$cod_usucada', '', '', '', '', '', '', '0', '0', '0', '0', '', '', 'N', '', '', 'N', '', '', 'N', '', '0', '0', '0', '0', '0', '0', '0', '0', '0', '', '0', '0', '0', '', '', '0', '0', 'C', 'N', 'S', '0', '0', '0', '0', 'N', 'N', 'N', 'N', '', '', '0', NULL, NULL);";
								}
							}

							// Checa se 'Autom SMS Nivers club' já existe no array de personas
							if (isset($_POST['LOG_AUTSMSNIVERCLUB'])) {
								$automClub = $cod_empresa . " - AUTOM SMS Nivers Club 3B5";
								if (
									empty($arrayRegras['PERSONASREGRA']) ||
									(is_array($arrayPersonas['PERSONAS']) && array_key_exists($automClub, $arrayPersonas['PERSONAS']) &&
										is_array($arrayRegras['PERSONASREGRA']) &&
										!array_key_exists($arrayPersonas['PERSONAS'][$automClub], $arrayRegras['PERSONASREGRA']))
								) {
									$codPersona = $arrayPersonas['PERSONAS'][$automClub];
									$sqlPersonaRegra .= "CALL SP_ALTERA_PERSONAREGRA ( '$codPersona', 'S', 'S', 'S', 'S', 'N', 'S', '18;150', 'N', 'C', 'N', 'N', 'N', 'N', '0', '', '0', 'S', 'S', '$log_comunica', '$log_comunica', '$log_comunica', '$log_comunica', '$log_comunica', 'N', '$cod_usucada', '', '', '', '', '', '', '1', '90', '0', '0', '', '', 'N', '', '', 'N', '', '', 'N', '', '0', '0', '0', '0', '0', '0', '0', '0', '0', '', '0', '0', '0', '', '', '0', '0', 'C', 'N', 'S', '0', '0', '0', '0', 'N', 'N', 'N', 'N', '', '', '0', NULL, NULL);";
								}
							}
							// fnEscreve($sqlPersonaRegra);
							if ($sqlPersonaRegra != "") {
								$query = mysqli_multi_query(connTemp($cod_empresa, ''), $sqlPersonaRegra);
								if (!$query) {
									$cod_erro = Log_error_comand($connAdm->connAdm(), connTemp($cod_empresa, ''), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql, $nom_usuario);
								}

								$cod_regra = 0;
								$sqlBusca = "SELECT PR.COD_REGRA, PS.COD_PERSONA, PS.DES_PERSONA FROM PERSONAREGRA AS PR
								INNER JOIN PERSONA AS PS ON PR.COD_PERSONA = PS.COD_PERSONA WHERE PS.COD_EMPRESA = $cod_empresa";

								$query = mysqli_query(connTemp($cod_empresa, ''), $sqlBusca);
								$cod_persona = "";
								while ($qrResult = mysqli_fetch_assoc($query)) {
									$cod_regra .= $qrResult['COD_REGRA'] . ",";
									if ($qrResult['DES_PERSONA'] == '00- LAB MK 00') {
										$cod_perClas = $qrResult['COD_PERSONA'];
									}
								}

								//CLASSIFICA CLIENTE NA PERSONA 
								$sqlClassifica = "CALL SP_BUSCA_PERSONA_MASTER(
									'$cod_perClas', 
									'S', 
									'S',
									'N',
									'N',
									'0',										
									'110',
									'S',
									'',    
									'N',
									'N',
									'N',
									'N',
									'0',
									'',
									'0',
									'S',
									'S',
									'S',
									'S',
									'S',
									'S',
									'S',
									'N',
									'$cod_empresa',
									'N',
									'N',
									NULL,
									NULL,
									NULL,
									NULL,
									NULL,
									NULL,
									'0',
									'0',
									'0',
									'0',
									NULL,
									NULL,
									'N',
									NULL,
									NULL,
									'N',
									NULL,
									NULL,
									'N',
									'',
									'0',
									'0',
									'0',
									'0',
									'0',
									'0',
									'0',
									'0',
									'0',
									'',
									'0',
									'0',
									'0',
									'',
									'',
									'0',
									'0',
									'0',
									'C',
									'S',
									'N',
									'0',
									'0',										
									'0',
									'N',
									'N',
									'N',
									'N',
									'',
									'',
									'0',
									'',
									'',
									'S',
									'S'
									)";
								mysqli_query(connTemp($cod_empresa, ''), $sqlClassifica);


								//atualiza base de personas
								$sql2 = "CALL SP_ALTERA_PERSONACLASSIFICA (
									'" . $cod_perClas . "', 
									'" . $cod_empresa . "' 
								) ";

								// fnEscreve($sql2);
								$personacla = mysqli_query(connTemp($cod_empresa, ''), $sql2);

								//BLOQUEIA PERSONAS
								$sqlUpdatePerson = "UPDATE PERSONA 
								SET LOG_RESTRITO = 'S' 
								WHERE COD_EMPRESA = $cod_empresa";
								mysqli_query(connTemp($cod_empresa, ''), $sqlUpdatePerson);

								if ($cod_regra != "") {
									$cod_regra = rtrim($cod_regra, ",");

									$sqlAudit = "UPDATE AUDITORIA_EMPRESA SET 
									COD_PERSONAREGRA = '" . $cod_regra . "'
									WHERE COD_EMPRESA = $cod_empresa";
									// fnEscreve($sqlAudit);

									$arrayProcAudit = mysqli_query($connAdm->connAdm(), trim($sqlAudit));
								}
							}
							// fnEscreve($sqlFiltros);
							if ($sqlFiltros != "") {
								$arrayproc = mysqli_multi_query(connTemp($cod_empresa, ''), $sqlFiltros);

								$sql = "SELECT COD_FILTROPER FROM FILTROS_PERSONA WHERE COD_EMPRESA = $cod_empresa";
								$query = mysqli_query(connTemp($cod_empresa, ''), $sql);
								$cod_filtroper = "";
								if ($qrResult = mysqli_fetch_assoc($query)) {
									while ($qrResult = mysqli_fetch_assoc($query)) {
										$cod_filtroper .= $qrResult['COD_FILTROPER'] . ",";
									}

									$sqlAudit = "UPDATE AUDITORIA_EMPRESA SET 
									FILTROS_PERSONA = '" . $cod_filtroper . "'
									WHERE COD_EMPRESA = $cod_empresa";

									$arrayProcAudit = mysqli_query($connAdm->connAdm(), trim($sqlAudit));
								}
							}
						}
					}


					$sqlAudit = "UPDATE AUDITORIA_EMPRESA SET 
					FASE3 = 'S'
					WHERE COD_EMPRESA = $cod_empresa";

					$arrayProcAudit = mysqli_query($connAdm->connAdm(), trim($sqlAudit));


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
	//fnEscreve($sql);
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

$des_dominio = "";
$des_email = "";
$des_programa = "";
$cod_dominio = "";
$desabilita = "";
$leitura = "";
if ($cod_empresa != 0) {
	$sqlbusca = "SELECT DES_DOMINIO, DES_EMAIL, DES_PROGRAMA, COD_DOMINIO FROM SITE_EXTRATO WHERE COD_EMPRESA = $cod_empresa";
	$query = mysqli_query(connTemp($cod_empresa, ''), $sqlbusca);
	if ($qrResult = mysqli_fetch_assoc($query)) {
		$des_dominio = $qrResult['DES_DOMINIO'];
		$des_email = $qrResult['DES_EMAIL'];
		$des_programa = $qrResult['DES_PROGRAMA'];
		$cod_dominio = $qrResult['COD_DOMINIO'];
		$desabilita = "readonly='readonly'";
		$leitura = "leitura";
		$mostraBtnHot = "";
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
										$abaAtivo = 2093;
										include 'menuAutomacao.php';
										?>

										<div class="col-xs-10">
											<!-- conteudo abas -->
											<div class="tab-content">


												<!-- aba produtos-->
												<div class="tab-pane active"">
																	<h4 style=" margin: 0 0 5px 0;"><span class="bolder">Clientes e Hotsite</span></h4>
													<small style="font-size: 12px;"></small>

													<div class="row">

														<div class="col-md-12">
															<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

																<div class="push20"></div>

																<fieldset>
																	<legend>Hot Site</legend>
																	<div class="row">
																		<div class="col-md-2">
																			<div class="form-group">
																				<label for="inputName" class="control-label required">Nome do Programa</label>
																				<input type="text" class="form-control input-sm <?= $leitura; ?>" name="DES_PROGRAMA" id="DES_PROGRAMA" maxlength="30" <?= $desabilita ?> value="<?php echo $des_programa; ?>" required>
																				<div class="help-block with-errors">
																				</div>
																			</div>
																		</div>

																		<div class="col-md-2">
																			<div class="form-group">
																				<label for="inputName" class="control-label required">Nome do Hot Site</label>
																				<input type="text" class="form-control input-sm <?= $leitura; ?>" name="DES_DOMINIO" id="DES_DOMINIO" maxlength="15" <?= $desabilita ?> value="<?php echo $des_dominio; ?>" required>
																				<div class="help-block with-errors">Máximo de 15 caractéres</div>
																				<!-- <div class="help-block with-errors validaTemp"></div> -->
																			</div>
																		</div>

																		<div class="col-md-2">
																			<div class="form-group">
																				<label for="inputName" class="control-label">Domínio Padrão</label>
																				<select data-placeholder="Selecione a ordem <?= $leitura; ?>" name="COD_DOMINIO" id="COD_DOMINIO" <?= $desabilita ?> class="chosen-select-deselect">
																					<option value="2">.fidelidade.mk</option>
																					<option value="1">.mais.cash</option>
																				</select>
																				<script>
																					$("#formulario #COD_DOMINIO").val('<?= $cod_dominio ?>').trigger("chosen:updated");
																				</script>
																			</div>
																		</div>

																		<div class="col-md-3">
																			<div class="form-group">
																				<label for="inputName" class="control-label required">e-Mail de Contato</label>
																				<input type="text" class="form-control input-sm <?= $leitura; ?>" name="DES_EMAIL" id="DES_EMAIL" maxlength="100" <?= $desabilita ?> value="<?php echo $des_email; ?>" required>
																				<div class="help-block with-errors"></div>
																			</div>
																		</div>
																	</div>
																	<div class="row text-right col-lg-12" <?= $mostraBtnHot ?>>
																		<a href="action.do?mod=<?= fnEncode(1165) ?>&id=<?= fnEncode($cod_empresa) ?>" target="_blank" class="btn btn-info btn-sm">Acessar HotSite</a>
																	</div>
																</fieldset>

																<div class="push20"></div>

																<fieldset>
																	<legend>Filtros Dinâmicos</legend>

																	<div class="row">

																		<?php
																		// BUSCA TIPO FITLRO
																		$sqlBusca = "SELECT DES_TPFILTRO FROM TIPO_FILTRO WHERE COD_EMPRESA = $cod_empresa AND DES_TPFILTRO = 'Person of Interest'";
																		$queryFiltro = mysqli_query(connTemp($cod_empresa, ''), $sqlBusca);
																		if ($qrFiltro = mysqli_fetch_assoc($queryFiltro)) {
																			$temFiltro = "<i class='fal fa-check' style='margin-left: 6; font-size: 24px; color: #28a745;'></i>";
																			$mostraBtnFiltro = "";
																		} else {
																			$temFiltro = "<i class='fal fa-times' style=' margin-left: 6; font-size: 24px; opacity: 0.2;'></i>";
																		}
																		?>

																		<div class="col-md-3">
																			<span><b>Filtro</b></span>
																			<div class="center-content" style="margin-top: 12px;">
																				<span>Person of Interest</span>
																				<?= $temFiltro ?>
																			</div>
																		</div>

																		<?php
																		// BUSCA OCORRENCIA DE FILTRO
																		$temOcorrencia1 = "<i class='fal fa-times' style=' margin-left: 6; font-size: 24px; opacity: 0.2;'></i>";
																		$temOcorrencia2 = "<i class='fal fa-times' style=' margin-left: 6; font-size: 24px; opacity: 0.2;'></i>";

																		$sqlBusca = "SELECT DES_FILTRO FROM FILTROS_CLIENTE WHERE COD_EMPRESA = $cod_empresa";
																		$query = mysqli_query(connTemp($cod_empresa, ''), $sqlBusca);
																		while ($result = mysqli_fetch_assoc($query)) {
																			if ($result['DES_FILTRO'] == '00 - LAB MK 00') {
																				$temOcorrencia1 = "<i class='fal fa-check' style='margin-left: 6; font-size: 24px; color: #28a745;'></i>";
																			}

																			if ($result['DES_FILTRO'] == '00 - LAB ' . $nom_fantasi) {
																				$temOcorrencia2 = "<i class='fal fa-check' style='margin-left: 6; font-size: 24px; color: #28a745;'></i>";
																			}
																		}


																		?>

																		<div class="col-md-3">
																			<span><b>Ocorrencia de Filtro</b></span>
																			<div class="center-content" style="margin-top: 12px;">
																				<span>00- LAB MK00</span>
																				<?= $temOcorrencia1 ?>
																			</div>
																		</div>

																		<div class="col-md-3">
																			<span style="visibility: hidden;"><b>Ocorrencia de Filtro</b></span>
																			<div class="center-content" style="margin-top: 12px;">
																				<span>00- LAB <?= $nom_fantasi ?></span>
																				<?= $temOcorrencia2 ?>
																			</div>
																		</div>

																	</div>

																	<div class="row text-right col-lg-12" <?= $mostraBtnFiltro ?>>
																		<a href="action.do?mod=<?= fnEncode(1399) ?>&id=<?= fnEncode($cod_empresa) ?>" target="_blank" class="btn btn-info btn-sm">Acessar Filtros</a>
																	</div>

																</fieldset>

																<div class="push20"></div>

																<fieldset>
																	<legend>Perfil de Usuário</legend>

																	<div class="row">

																		<?php
																		// BUSCA PERFIL GERENCIAL

																		$temPerfil1 = "<i class='fal fa-times' style=' margin-left: 6; font-size: 24px; opacity: 0.2;'></i>";
																		$temPerfil2 = "<i class='fal fa-times' style=' margin-left: 6; font-size: 24px; opacity: 0.2;'></i>";
																		$temPerfil3 = "<i class='fal fa-times' style=' margin-left: 6; font-size: 24px; opacity: 0.2;'></i>";

																		$sqlBusca = "SELECT DES_PERFILS FROM PERFIL WHERE COD_EMPRESA = $cod_empresa";
																		$query = mysqli_query($connAdm->connAdm(), $sqlBusca);
																		while ($result = mysqli_fetch_assoc($query)) {
																			if ($result['DES_PERFILS'] == 'Marka Restrito') {
																				$temPerfil1 = "<i class='fal fa-check' style='margin-left: 6; font-size: 24px; color: #28a745;'></i>";
																				$mostraBtnPerfil = "";
																			}

																			if ($result['DES_PERFILS'] == 'Gerencial') {
																				$temPerfil2 = "<i class='fal fa-check' style='margin-left: 6; font-size: 24px; color: #28a745;'></i>";
																			}

																			if ($result['DES_PERFILS'] == 'Suporte') {
																				$temPerfil3 = "<i class='fal fa-check' style='margin-left: 6; font-size: 24px; color: #28a745;'></i>";
																			}
																		}


																		?>

																		<div class="col-md-3">
																			<div class="center-content" style="margin-top: 12px;">
																				<span>Marka Restrito</span>
																				<?= $temPerfil1 ?>
																			</div>
																		</div>

																		<div class="col-md-3">
																			<div class="center-content" style="margin-top: 12px;">
																				<span>Gerencial</span>
																				<?= $temPerfil2 ?>
																			</div>
																		</div>

																		<div class="col-md-3">
																			<div class="center-content" style="margin-top: 12px;">
																				<span>Suporte</span>
																				<?= $temPerfil3 ?>
																			</div>
																		</div>

																	</div>

																	<div class="row text-right col-lg-12" <?= $mostraBtnPerfil ?>>
																		<a href="action.do?mod=<?= fnEncode(1018) ?>&id=<?= fnEncode($cod_empresa) ?>" target="_blank" class="btn btn-info btn-sm">Acessar Perfil</a>
																	</div>

																</fieldset>

																<div class="push20"></div>

																<fieldset>
																	<legend>Cliente</legend>

																	<div class="row">

																		<?php
																		// BUSCA USUARIO

																		$temUsuario = "<i class='fal fa-times' style=' margin-left: 6; font-size: 24px; opacity: 0.2;'></i>";

																		$sqlBusca = "SELECT * FROM CLIENTES WHERE COD_EMPRESA = $cod_empresa";
																		$query = mysqli_query(connTemp($cod_empresa, ""), $sqlBusca);
																		if ($result = mysqli_fetch_assoc($query)) {
																			$temUsuario = "<i class='fal fa-check' style='margin-left: 6; font-size: 24px; color: #28a745;'></i>";
																		}

																		?>

																		<div class="col-md-3">
																			<div class="center-content" style="margin-top: 12px;">
																				<span>Usuário Inicial</span>
																				<?= $temUsuario ?>
																			</div>
																		</div>

																		<?php
																		// BUSCA FREQUENCIA DE CLIENTE

																		$temFreq = "<i class='fal fa-times' style=' margin-left: 6; font-size: 24px; opacity: 0.2;'></i>";

																		$sqlBusca = "SELECT * FROM FREQUENCIA_CLIENTE WHERE COD_EMPRESA = $cod_empresa";
																		$query = mysqli_query(connTemp($cod_empresa, ""), $sqlBusca);
																		if ($result = mysqli_fetch_assoc($query)) {
																			$temFreq = "<i class='fal fa-check' style='margin-left: 6; font-size: 24px; color: #28a745;'></i>";
																		}

																		?>

																		<div class="col-md-3">
																			<div class="center-content" style="margin-top: 12px;">
																				<span>Funil por Gasto</span>
																				<?= $temFreq ?>
																			</div>
																		</div>

																	</div>

																	<div class="row text-right col-lg-12" <?= $mostraBtnFreq ?>>
																		<a href="action.do?mod=<?= fnEncode(1621) ?>&id=<?= fnEncode($cod_empresa) ?>" target="_blank" class="btn btn-info btn-sm">Acessar Frequência</a>
																	</div>

																</fieldset>

																<div class="push20"></div>

																<fieldset>
																	<legend>Personas</legend>

																	<div class="row">

																		<?php
																		// BUSCA PERSONAS

																		$temPerson1 = "<i class='fal fa-times' style=' margin-left: 6; font-size: 24px; opacity: 0.2;'></i>";
																		$temPerson2 = "<i class='fal fa-times' style=' margin-left: 6; font-size: 24px; opacity: 0.2;'></i>";
																		$temPerson3 = "<i class='fal fa-times' style=' margin-left: 6; font-size: 24px; opacity: 0.2;'></i>";
																		$temPerson4 = "<i class='fal fa-times' style=' margin-left: 6; font-size: 24px; opacity: 0.2;'></i>";
																		$temPerson5 = "<i class='fal fa-times' style=' margin-left: 6; font-size: 24px; opacity: 0.2;'></i>";
																		$temPerson6 = "<i class='fal fa-times' style=' margin-left: 6; font-size: 24px; opacity: 0.2;'></i>";
																		$temPerson7 = "<i class='fal fa-times' style=' margin-left: 6; font-size: 24px; opacity: 0.2;'></i>";
																		$temPerson8 = "<i class='fal fa-times' style=' margin-left: 6; font-size: 24px; opacity: 0.2;'></i>";
																		$temPerson9 = "<i class='fal fa-times' style=' margin-left: 6; font-size: 24px; opacity: 0.2;'></i>";

																		$sqlBusca = "SELECT DES_PERSONA, COD_PERSONA FROM PERSONA WHERE COD_EMPRESA = $cod_empresa";
																		$query = mysqli_query(connTemp($cod_empresa, ""), $sqlBusca);
																		$numLinhas = mysqli_num_rows($query);
																		if ($numLinhas > 0) {
																			$checkTodos = "";
																			$mostraBtnPersona = "";
																			$count = 0;
																			$arrayPersonas = array();
																			while ($qrResult = mysqli_fetch_assoc($query)) {
																				// Armazena apenas o COD_PERSONA e DES_PERSONA no array
																				$arrayPersonas['PERSONAS'][$qrResult['DES_PERSONA']] = $qrResult['COD_PERSONA'];
																				$count++;
																			}
																		}

																		if (!empty($arrayPersonas['PERSONAS']) && array_key_exists('Fidelidade (acesso restrito)', $arrayPersonas['PERSONAS'])) {
																			$temPerson1 = "<i class='fal fa-check' style='margin-left: 6; font-size: 24px; color: #28a745;'></i>";
																			$check1 = "checked";
																		} else {
																			$check1 = "";
																		}

																		if (!empty($arrayPersonas['PERSONAS']) && array_key_exists('T.O. Homem', $arrayPersonas['PERSONAS'])) {
																			$temPerson2 = "<i class='fal fa-check' style='margin-left: 6; font-size: 24px; color: #28a745;'></i>";
																			$check2 = "checked";
																		} else {
																			$check2 = "";
																		}

																		if (!empty($arrayPersonas['PERSONAS']) && array_key_exists('T.O. Mulher', $arrayPersonas['PERSONAS'])) {
																			$temPerson3 = "<i class='fal fa-check' style='margin-left: 6; font-size: 24px; color: #28a745;'></i>";
																			$check3 = "checked";
																		} else {
																			$check3 = "";
																		}

																		if (!empty($arrayPersonas['PERSONAS']) && array_key_exists('T.O. Melhor Idade', $arrayPersonas['PERSONAS'])) {
																			$temPerson4 = "<i class='fal fa-check' style='margin-left: 6; font-size: 24px; color: #28a745;'></i>";
																			$check4 = "checked";
																		} else {
																			$check4 = "";
																		}

																		if (!empty($arrayPersonas['PERSONAS']) && array_key_exists('00- LAB MK 00', $arrayPersonas['PERSONAS'])) {
																			$temPerson5 = "<i class='fal fa-check' style='margin-left: 6; font-size: 24px; color: #28a745;'></i>";
																			$check5 = "checked";
																		} else {
																			$check5 = "";
																		}

																		if (!empty($arrayPersonas['PERSONAS']) && array_key_exists('00 - LAB ' . $nom_fantasi, $arrayPersonas['PERSONAS'])) {
																			$temPerson6 = "<i class='fal fa-check' style='margin-left: 6; font-size: 24px; color: #28a745;'></i>";
																			$check6 = "checked";
																		} else {
																			$check6 = "";
																		}

																		if (!empty($arrayPersonas['PERSONAS']) && array_key_exists($cod_empresa . ' - Autom SMS Inativo 2a6', $arrayPersonas['PERSONAS'])) {
																			$temPerson7 = "<i class='fal fa-check' style='margin-left: 6; font-size: 24px; color: #28a745;'></i>";
																			$check7 = "checked";
																		} else {
																			$check7 = "";
																		}

																		if (!empty($arrayPersonas['PERSONAS']) && array_key_exists($cod_empresa . ' - Autom SMS Nivers', $arrayPersonas['PERSONAS'])) {
																			$temPerson8 = "<i class='fal fa-check' style='margin-left: 6; font-size: 24px; color: #28a745;'></i>";
																			$check8 = "checked";
																		} else {
																			$check8 = "";
																		}

																		if (!empty($arrayPersonas['PERSONAS']) && array_key_exists($cod_empresa . ' - AUTOM SMS Nivers Club 3B5', $arrayPersonas['PERSONAS'])) {
																			$temPerson9 = "<i class='fal fa-check' style='margin-left: 6; font-size: 24px; color: #28a745;'></i>";
																			$check9 = "checked";
																		} else {
																			$check9 = "";
																		}
																		?>


																		<div class="col-md-3">
																			<div class="center-content" style="margin-top: 12px;">
																				<div style="flex-direction: column;">
																					<div>
																						<span>Fidelidade (acesso restrito)</span>
																						<?= $temPerson1 ?>
																					</div>
																					<div class="form-group">
																						<label class="switch switch-small">
																							<input type="checkbox" name="LOG_FIDELIDADE" id="LOG_FIDELIDADE" class="switch" value="S" <?= $check1 ?> <?= $checkTodos; ?> />
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
																						<span>T.O. Homem</span>
																						<?= $temPerson2 ?>
																					</div>
																					<div class="form-group">
																						<label class="switch switch-small">
																							<input type="checkbox" name="LOG_TOHOMEM" id="LOG_TOHOMEM" class="switch" value="S" <?= $check2 ?> <?= $checkTodos; ?> />
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
																						<span>T.O. Mulher</span>
																						<?= $temPerson3 ?>
																					</div>
																					<div class="form-group">
																						<label class="switch switch-small">
																							<input type="checkbox" name="LOG_TOMULHER" id="LOG_TOMULHER" class="switch" value="S" <?= $check3 ?> <?= $checkTodos; ?> />
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
																						<span>T.O. Melhor Idade</span>
																						<?= $temPerson4 ?>
																					</div>
																					<div class="form-group">
																						<label class="switch switch-small">
																							<input type="checkbox" name="LOG_TOMELIDADE" id="LOG_TOMELIDADE" class="switch" value="S" <?= $check4 ?> <?= $checkTodos; ?> />
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
																						<span>00- LAB MK 00</span>
																						<?= $temPerson5 ?>
																					</div>
																					<div class="form-group">
																						<label class="switch switch-small">
																							<input type="checkbox" name="LOG_LABMK00" id="LOG_LABMK00" class="switch" value="S" <?= $check5 ?> <?= $checkTodos; ?> />
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
																						<span>00 - LAB <?= $nom_fantasi ?></span>
																						<?= $temPerson6 ?>
																					</div>
																					<div class="form-group">
																						<label class="switch switch-small">
																							<input type="checkbox" name="LOG_00LABEMP" id="LOG_00LABEMP" class="switch" value="S" <?= $check6 ?> <?= $checkTodos; ?> />
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
																						<span><?= $cod_empresa ?> - Autom SMS Inativo 2a6</span>
																						<?= $temPerson7 ?>
																					</div>
																					<div class="form-group">
																						<label class="switch switch-small">
																							<input type="checkbox" name="LOG_AUTSMSINAT" id="LOG_AUTSMSINAT" class="switch" value="S" <?= $check7 ?> <?= $checkTodos; ?> />
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
																						<span><?= $cod_empresa ?> - Autom SMS Nivers</span>
																						<?= $temPerson8 ?>
																					</div>
																					<div class="form-group">
																						<label class="switch switch-small">
																							<input type="checkbox" name="LOG_AUTSMSNIVER" id="LOG_AUTSMSNIVER" class="switch" value="S" <?= $check8 ?> <?= $checkTodos; ?> />
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
																						<span><?= $cod_empresa ?> - AUTOM SMS Nivers Club 3B5</span>
																						<?= $temPerson9 ?>
																					</div>
																					<div class="form-group">
																						<label class="switch switch-small">
																							<input type="checkbox" name="LOG_AUTSMSNIVERCLUB" id="LOG_AUTSMSNIVERCLUB" class="switch" value="S" <?= $check9 ?> <?= $checkTodos; ?> />
																							<span></span>
																						</label>
																						<div class="help-block with-errors"></div>
																					</div>
																				</div>
																			</div>
																		</div>

																	</div>

																	<div class="row text-right col-lg-12" <?= $mostraBtnPersona ?>>
																		<a href="action.do?mod=<?= fnEncode(1049) ?>&id=<?= fnEncode($cod_empresa) ?>" target="_blank" class="btn btn-info btn-sm">Acessar Personas</a>
																	</div>

																</fieldset>

																<div class="push20"></div>

																<fieldset>
																	<legend>Regras de Personas</legend>

																	<div class="row">

																		<?php
																		// BUSCA PERSONAS

																		$temPerson1 = "<i class='fal fa-times' style=' margin-left: 6; font-size: 24px; opacity: 0.2;'></i>";
																		$temPerson2 = "<i class='fal fa-times' style=' margin-left: 6; font-size: 24px; opacity: 0.2;'></i>";
																		$temPerson3 = "<i class='fal fa-times' style=' margin-left: 6; font-size: 24px; opacity: 0.2;'></i>";
																		$temPerson4 = "<i class='fal fa-times' style=' margin-left: 6; font-size: 24px; opacity: 0.2;'></i>";
																		$temPerson5 = "<i class='fal fa-times' style=' margin-left: 6; font-size: 24px; opacity: 0.2;'></i>";
																		$temPerson6 = "<i class='fal fa-times' style=' margin-left: 6; font-size: 24px; opacity: 0.2;'></i>";
																		$temPerson7 = "<i class='fal fa-times' style=' margin-left: 6; font-size: 24px; opacity: 0.2;'></i>";
																		$temPerson8 = "<i class='fal fa-times' style=' margin-left: 6; font-size: 24px; opacity: 0.2;'></i>";
																		$temPerson9 = "<i class='fal fa-times' style=' margin-left: 6; font-size: 24px; opacity: 0.2;'></i>";

																		$sqlBusca = "SELECT PS.DES_PERSONA AS DES_PERSONA FROM PERSONAREGRA AS PR
																					INNER JOIN PERSONA AS PS ON PR.COD_PERSONA = PS.COD_PERSONA WHERE PS.COD_EMPRESA = $cod_empresa";
																		$query = mysqli_query(connTemp($cod_empresa, ""), $sqlBusca);
																		$numLinhas = mysqli_num_rows($query);
																		if ($numLinhas > 0) {
																			while ($qrResultado = mysqli_fetch_assoc($query)) {

																				if ($qrResultado['DES_PERSONA'] == 'Fidelidade (acesso restrito)') {
																					$temPerson1 = "<i class='fal fa-check' style='margin-left: 6; font-size: 24px; color: #28a745;'></i>";
																				}

																				if ($qrResultado['DES_PERSONA'] == 'T.O. Homem') {
																					$temPerson2 = "<i class='fal fa-check' style='margin-left: 6; font-size: 24px; color: #28a745;'></i>";
																				}

																				if ($qrResultado['DES_PERSONA'] == 'T.O. Mulher') {
																					$temPerson3 = "<i class='fal fa-check' style='margin-left: 6; font-size: 24px; color: #28a745;'></i>";
																				}

																				if ($qrResultado['DES_PERSONA'] == 'T.O. Melhor Idade') {
																					$temPerson4 = "<i class='fal fa-check' style='margin-left: 6; font-size: 24px; color: #28a745;'></i>";
																				}

																				if ($qrResultado['DES_PERSONA'] == '00- LAB MK 00') {
																					$temPerson5 = "<i class='fal fa-check' style='margin-left: 6; font-size: 24px; color: #28a745;'></i>";
																				}

																				if ($qrResultado['DES_PERSONA'] == '00 - LAB ' . $nom_fantasi) {
																					$temPerson6 = "<i class='fal fa-check' style='margin-left: 6; font-size: 24px; color: #28a745;'></i>";
																				}

																				if ($qrResultado['DES_PERSONA'] == $cod_empresa . ' - Autom SMS Inativo 2a6') {
																					$temPerson7 = "<i class='fal fa-check' style='margin-left: 6; font-size: 24px; color: #28a745;'></i>";
																				}

																				if ($qrResultado['DES_PERSONA'] == $cod_empresa . ' - Autom SMS Nivers') {
																					$temPerson8 = "<i class='fal fa-check' style='margin-left: 6; font-size: 24px; color: #28a745;'></i>";
																				}

																				if ($qrResultado['DES_PERSONA'] == $cod_empresa . ' - AUTOM SMS Nivers Club 3B5') {
																					$temPerson9 = "<i class='fal fa-check' style='margin-left: 6; font-size: 24px; color: #28a745;'></i>";
																				}
																			}
																		}

																		?>


																		<div class="col-md-3">
																			<div class="center-content" style="margin-top: 12px;">
																				<span>Fidelidade (acesso restrito)</span>
																				<?= $temPerson1 ?>
																			</div>
																		</div>

																		<div class="col-md-3">
																			<div class="center-content" style="margin-top: 12px;">
																				<span>T.O. Homem</span>
																				<?= $temPerson2 ?>
																			</div>
																		</div>

																		<div class="col-md-3">
																			<div class="center-content" style="margin-top: 12px;">
																				<span>T.O. Mulher</span>
																				<?= $temPerson3 ?>
																			</div>
																		</div>

																		<div class="col-md-3">
																			<div class="center-content" style="margin-top: 12px;">
																				<span>T.O. Melhor Idade</span>
																				<?= $temPerson4 ?>
																			</div>
																		</div>

																		<div class="col-md-3">
																			<div class="center-content" style="margin-top: 12px;">
																				<span>00- LAB MK 00</span>
																				<?= $temPerson5 ?>
																			</div>
																		</div>

																		<div class="col-md-3">
																			<div class="center-content" style="margin-top: 12px;">
																				<span>00 - LAB <?= $nom_fantasi ?></span>
																				<?= $temPerson6 ?>
																			</div>
																		</div>

																		<div class="col-md-3">
																			<div class="center-content" style="margin-top: 12px;">
																				<span><?= $cod_empresa ?> - Autom SMS Inativo 2a6</span>
																				<?= $temPerson7 ?>
																			</div>
																		</div>

																		<div class="col-md-3">
																			<div class="center-content" style="margin-top: 12px;">
																				<span><?= $cod_empresa ?> - Autom SMS Nivers</span>
																				<?= $temPerson8 ?>
																			</div>
																		</div>

																		<div class="col-md-3">
																			<div class="center-content" style="margin-top: 12px;">
																				<span><?= $cod_empresa ?> - AUTOM SMS Nivers Club 3B5</span>
																				<?= $temPerson9 ?>
																			</div>
																		</div>

																	</div>

																</fieldset>

																<div class="push20"></div>

																<fieldset>
																	<legend>Filtros de Personas</legend>

																	<div class="row">

																		<?php
																		// BUSCA PERFIL GERENCIAL

																		$temFiltroPerson1 = "<i class='fal fa-times' style=' margin-left: 6; font-size: 24px; opacity: 0.2;'></i>";
																		$temFiltroPerson2 = "<i class='fal fa-times' style=' margin-left: 6; font-size: 24px; opacity: 0.2;'></i>";

																		$sqlBusca = "SELECT P.DES_PERSONA FROM FILTROS_PERSONA AS FP 
																		INNER JOIN PERSONA AS P ON P.COD_PERSONA = FP.COD_PERSONA WHERE FP.COD_EMPRESA = $cod_empresa";
																		$query = mysqli_query(conntemp($cod_empresa, ""), $sqlBusca);
																		while ($result = mysqli_fetch_assoc($query)) {
																			if ($result['DES_PERSONA'] == '00- LAB MK 00') {
																				$temFiltroPerson1 = "<i class='fal fa-check' style='margin-left: 6; font-size: 24px; color: #28a745;'></i>";
																			}

																			if ($result['DES_PERSONA'] == '00 - LAB ' . $nom_fantasi) {
																				$temFiltroPerson2 = "<i class='fal fa-check' style='margin-left: 6; font-size: 24px; color: #28a745;'></i>";
																			}
																		}


																		?>

																		<div class="col-md-3">
																			<div class="center-content" style="margin-top: 12px;">
																				<span>00- LAB MK 00</span>
																				<?= $temFiltroPerson1 ?>
																			</div>
																		</div>

																		<div class="col-md-3">
																			<div class="center-content" style="margin-top: 12px;">
																				<span>00 - LAB <?= $nom_fantasi ?></span>
																				<?= $temFiltroPerson2 ?>
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

		// $('#DES_DOMINIO').on("blur", function() {
		// 	let nomFantasi = $('#NOM_FANTASI').val();
		// 	let desDominio = $(this).val();
		// 	$.ajax({
		// 		type: "POST",
		// 		url: "ajxValidaAutom.do?opcao=valFantasi&id=<?php echo fnEncode($cod_empresa); ?>",
		// 		data: {
		// 			NOM_FANTASI: nomFantasi,
		// 			DES_DOMINIO: desDominio
		// 		},
		// 		success: function(data) {
		// 			if (data != "") {
		// 				$('.validaTemp').html(data);
		// 			} else {
		// 				$('.validaTemp').html(''); // limpa se vier vazio
		// 			}
		// 		}
		// 	});
		// });

		$('.next1').click(function() {

			// $.ajax({
			// 	type: "POST",
			// 	url: "ajxAutomacaoEmpresa.php?acao=1&id=<?php echo $cod_empresa; ?>",
			// 	/*beforeSend:function(){
			// 		$("#passo1").html('<div class="loading" style="width: 100%;"></div>');
			// 	},*/
			// 	success:function(data){	

			// 		$('#passo1').hide();
			// 		$('#passo2').show();
			// 		$("#passo2").html(data);
			// 		$("#step2 div.fundo, #step2 a.btn").addClass('fundoAtivo');

			// 	}						
			// });
		});

	});
</script>