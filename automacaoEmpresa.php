<?php

$hashLocal = mt_rand();
$desa_pontuar = "";
$desa_negativo = "";
$desa_cadtoken = "";
$nom_fantasi = "";
$nom_empresa = "";
$num_cgcecpf = "";
$cod_chaveco = "";
$cod_integradora = "";
$des_sufixo = "";
$check_cadtoken = "";
$check_pontuar = "";
$check_negativo = "";
$disa_cadtoken = "";
$disa_pontuar = "";
$disa_negativo = "";
$desabilita = "";
$leitura = "";
$qtd_univend = 0;
$desabilitado = "disabled";
$mostraBtnEmp = "style='display:none;'";
$log_cadtoken = 'N';
$log_pontuar = 'N';
$log_negativo = 'N';
$nom_respons = "";
$num_telefon = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		if (isset($_REQUEST['NOM_UNIVEND'])) {
			$nom_univend = fnLimpacampo($_REQUEST['NOM_UNIVEND']);
		}
		if (isset($_REQUEST['NUM_CGCECPFUNV'])) {
			$num_cgcecpfunv = fnLimpacampo($_REQUEST['NUM_CGCECPFUNV']);
		}
		if (isset($_REQUEST['NOM_FANTASIUNV'])) {
			$nom_fantasiunv = fnLimpaCampo($_REQUEST['NOM_FANTASIUNV']);
		}

		if (isset($_REQUEST['COD_INTEGRADORA'])) {
			$cod_integradora = fnLimpaCampoZero($_REQUEST['COD_INTEGRADORA']);
		}
		if (isset($_REQUEST['COD_CHAVECO'])) {
			$cod_chaveco = fnLimpaCampoZero($_REQUEST['COD_CHAVECO']);
		}
		if (isset($_REQUEST['QTD_UNIVEND'])) {
			$qtd_univend = fnLimpaCampoZero($_REQUEST['QTD_UNIVEND']);
		}
		if (isset($_REQUEST['NOM_EMPRESA'])) {
			$nom_empresa = fnLimpaCampo($_REQUEST['NOM_EMPRESA']);
		}
		if (isset($_REQUEST['NOM_FANTASI'])) {
			$nom_fantasi = fnLimpaCampo($_REQUEST['NOM_FANTASI']);
		}
		if (isset($_REQUEST['NUM_CGCECPF'])) {
			$num_cgcecpf = fnLimpaCampo($_REQUEST['NUM_CGCECPF']);
		}
		if (isset($_REQUEST['DES_SUFIXO'])) {
			$des_sufixo = fnLimpaCampo($_REQUEST['DES_SUFIXO']);
		}

		if (isset($_REQUEST['COD_CONSULTOR'])) {
			$cod_consultor = fnLimpaCampoZero($_REQUEST['COD_CONSULTOR']);
		}

		if (isset($_REQUEST['LOG_CADTOKEN']) && $_REQUEST['LOG_CADTOKEN'] == 'S') {
			$log_cadtoken = 'S';
		}

		if (isset($_REQUEST['LOG_PONTUAR']) && $_REQUEST['LOG_PONTUAR'] == 'S') {
			$log_pontuar = 'S';
		}

		if (isset($_REQUEST['LOG_NEGATIVO']) && $_REQUEST['LOG_NEGATIVO'] == 'S') {
			$log_negativo = 'S';
		}

		$cod_empresa = "";
		if (isset($_REQUEST['COD_EMPRESA'])) {
			$cod_empresa = fnLimpaCampo($_REQUEST['COD_EMPRESA']);
		}

		if (isset($_REQUEST['NOM_RESPONS'])) {
			$nom_respons = fnLimpaCampo($_REQUEST['NOM_RESPONS']);
		}

		if (isset($_REQUEST['NUM_TELEFON'])) {
			$num_telefon = fnLimpaCampo(fnLimpaDoc($_REQUEST['NUM_TELEFON']));
		}

		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
		$nom_usuario = $_SESSION["SYS_NOM_USUARIO"];
		$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$MODULO = @$_GET['mod'];
		$COD_MODULO = fndecode(@$_GET['mod']);

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];


		if ($opcao != '') {

			switch ($opcao) {
				case 'CAD':

					//case sql insert empresa
					if ($cod_empresa == "" || $cod_empresa == 0) {
						switch ($cod_integradora) {

							case '24': //alpha7
								$sqlInserEmpresa = "CALL SP_ALTERA_EMPRESAS_FULL (0,'$cod_usucada','$nom_empresa','','$nom_respons','$num_cgcecpf','S','6','','$nom_fantasi','$num_telefon','','','','','','','','','4','3','4','N','N','2','$des_sufixo','N','N','$cod_chaveco','RESG','N','N','','24','','','1','2','2',NULL,'$cod_consultor','N','$log_pontuar','2','','','','','N','S','N','N','4','1','2','S','2','0','N','N','$log_cadtoken','$log_negativo','N','0.00','13','6','2','18','N','N','2','6','8','','0','0','S','N','CAD')";
								break;

							case '13': //inovafarma
								$sqlInserEmpresa = "CALL SP_ALTERA_EMPRESAS_FULL (0,'$cod_usucada','$nom_empresa','','$nom_respons','$num_cgcecpf','S','6','','$nom_fantasi','$num_telefon','','','','','','','','','4','3','4','N','N','2','$des_sufixo','N','N','$cod_chaveco','RESG','N','N','','13','','','1','2','2',NULL,'$cod_consultor','N','$log_pontuar','2','','','','','S','S','N','N','4','1','1','S','2','0','N','N','$log_cadtoken','$log_negativo','N','0.00','13','6','2','18','N','N','2','6','8','','0','0','S','N','CAD')";
								break;

							case '34': //trier
								$sqlInserEmpresa = " CALL SP_ALTERA_EMPRESAS_FULL (0,'$cod_usucada','$nom_empresa','','$nom_respons','$num_cgcecpf','S','6','','$nom_fantasi','$num_telefon','','','','','','','','','4','3','4','N','N','2','$des_sufixo','N','N','$cod_chaveco','RESG','N','N','','34','','','1','4','2',NULL,'$cod_consultor','N','$log_pontuar','2','','','','','S','S','N','N','3','1','1','S','2','0','N','N','$log_cadtoken','$log_negativo','N','0.00','13','6','2','18','N','N','2','6','8','','0','0','S','N','CAD')";
								break;

							default:
								$sqlInserEmpresa = " CALL SP_ALTERA_EMPRESAS_FULL (0,'$cod_usucada','$nom_empresa','','$nom_respons','$num_cgcecpf','S','6','','$nom_fantasi','$num_telefon','','','','','','','','','4','3','4','N','N','2','$des_sufixo','N','N','$cod_chaveco','RESG','N','N','','34','','','1','4','2',NULL,'$cod_consultor','N','$log_pontuar','2','','','','','S','S','N','N','3','1','1','S','2','0','N','N','$log_cadtoken','$log_negativo','N','0.00','13','6','2','18','N','N','2','6','8','','0','0','S','N','CAD')";
								break;
						}

						$arrayProcEmp = mysqli_query($connAdm->connAdm(), trim($sqlInserEmpresa));

						if (!$arrayProcEmp) {

							$cod_erro = Log_error_comand($connAdm->connAdm(), $conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $arrayProcEmp, $nom_usuario);
							$cod_empresa = "";
						} else {
							$sqlBuscaEmpresa = "SELECT MAX(COD_EMPRESA) AS COD_EMPRESA FROM EMPRESAS WHERE COD_CADASTR = $cod_usucada";
							$arrayQuery = mysqli_query($connAdm->connAdm(), $sqlBuscaEmpresa);
							$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
							$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];

							//cria registro de auditoria
							$sqlAudit = "INSERT INTO AUDITORIA_EMPRESA
								(
								COD_EMPRESA,
								DAT_CADASTR,
								COD_USUCADA
								)VALUES(
								$cod_empresa,
								NOW(),
								$cod_usucada
								)";
							// fnEscreve($sqlAudit);
							$arrayProcAudit = mysqli_query($connAdm->connAdm(), trim($sqlAudit));

							if (!$arrayProcAudit) {

								$cod_erro = Log_error_comand($connAdm->connAdm(), $conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sqlAudit, $nom_usuario);
							}
						}
					}

					//case sql insert unidades
					if ($cod_empresa != "" && $cod_empresa != 0) {
						$countUniv = 0;
						$cod_univends = "";

						$cod_tiporeg = 0;

						foreach ($_REQUEST['NOM_UNIVEND'] as $unidade => $nom_univend) {

							$sqlUnidade = "CALL SP_ALTERA_UNIDADEVENDA (
									0,
									'" . $cod_usucada . "', 
									'" . $nom_univend . "', 
									NULL, 
									'" . $_REQUEST['NUM_CGCECPFUNV'][$countUniv] . "', 
									'S', 
									NULL, 
									'" . $_REQUEST['NOM_FANTASIUNV'][$countUniv] . "', 
									NULL, 
									'" . $_REQUEST['NUM_CELULARUNV'][$countUniv] . "',
									NULL, 
									NULL, 
									NULL, 
									NULL, 
									NULL, 
									NULL, 				 
									NULL,				 
									NULL,    
									NULL,    
									3,    
									1,    
									'" . $_REQUEST['DES_EMAILUSUNV'][$countUniv] . "',
									'" . $cod_empresa . "',    
									NULL,    
									NULL,    
									NULL,    
									'" . $cod_tiporeg . "', 
									NULL,    
									NULL,    
									NULL,    
									NULL,    
									NULL,    
									NULL,    
									NULL,
									NULL,
									NULL,
									NULL,    
									'CAD'    
								) ";
							// fnEscreve($sqlUnidade);
							$arrayProcUnv = mysqli_query($connAdm->connAdm(), trim($sqlUnidade));

							if (!$arrayProcUnv) {
								$cod_erro = Log_error_comand($connAdm->connAdm(), $conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sqlUnidade, $nom_usuario);
							} else {

								//busca unidade de venda criada
								$sqlBuscaUnidade = "SELECT * FROM UNIDADEVENDA WHERE COD_EMPRESA = $cod_empresa ORDER BY COD_UNIVEND DESC LIMIT 1";
								$queryUnv = mysqli_query($connAdm->connAdm(), trim($sqlBuscaUnidade));
								$qrBuscaUnidade = mysqli_fetch_assoc($queryUnv);
								$cod_univend = $qrBuscaUnidade['COD_UNIVEND'];

								$cod_univends .= $cod_univend . ",";
							}

							$countUniv++;
						}

						$cod_univends = rtrim($cod_univends, ",");

						if ($cod_univends != "") {

							//insere unidade de venda na auditoria
							$sqlAudit = "UPDATE AUDITORIA_EMPRESA SET 
										COD_UNIVEND = '$cod_univends',
										QTD_UNIVEND = $qtd_univend 
										WHERE COD_EMPRESA = $cod_empresa";

							$arrayProcAudit = mysqli_query($connAdm->connAdm(), trim($sqlAudit));

							if (!$arrayProcAudit) {
								$cod_erro = Log_error_comand($connAdm->connAdm(), $conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sqlAudit, $nom_usuario);
							}
						}
					}

					if ($cod_empresa != "" && $cod_empresa != 0) {
						//VERIFICA SE EXISTE MATRIZ
						$sqlBusca = "SELECT * FROM matriz_campo_integracao WHERE COD_EMPRESA = $cod_empresa";
						$query = mysqli_query($connAdm->connAdm(), trim($sqlBusca));

						if (mysqli_num_rows($query) == 0) {
							//insere campos obrigatorios
							$sqlCampo = "INSERT INTO matriz_campo_integracao 
							(COD_CAMPOOBG, TIP_CAMPOOBG, COD_USUCADA, DAT_CADASTR, COD_EMPRESA) VALUES 
							(24,'OBG',$cod_usucada,NOW(), $cod_empresa),
							(5,'OBG',$cod_usucada,NOW(), $cod_empresa),
							(8,'OBG',$cod_usucada,NOW(), $cod_empresa),
							(23,'OBG',$cod_usucada,NOW(), $cod_empresa),
							(13,'OBG',$cod_usucada,NOW(), $cod_empresa),
							(13,'TKN',$cod_usucada,NOW(), $cod_empresa),
							(11,'CAD',$cod_usucada,NOW(), $cod_empresa),
							(24,'TKN',$cod_usucada,NOW(), $cod_empresa)";
							// fnEscreve($sqlCampo);
							$arrayProcCampos = mysqli_query($connAdm->connAdm(), trim($sqlCampo));

							if (!$arrayProcCampos) {
								$cod_erro = Log_error_comand($connAdm->connAdm(), $conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sqlCampo, $nom_usuario);
							}

							$sqlCamposObrigat = "SELECT * FROM matriz_campo_integracao 
							WHERE COD_EMPRESA = $cod_empresa";
							$queryObriga = mysqli_query($connAdm->connAdm(), trim($sqlCamposObrigat));
							$cod_campobrig = "";
							while ($qrResult = mysqli_fetch_assoc($queryObriga)) {
								if ($cod_campobrig != '') {
									$cod_campobrig .= ',';
								}
								$cod_campobrig .= $qrResult['COD_MATRIZ'];
							}

							//insere campos obrigatorios na auditoria
							$sqlAudit = "
							UPDATE AUDITORIA_EMPRESA SET 
							COD_CAMPOBRIG = '$cod_campobrig'
							WHERE COD_EMPRESA = $cod_empresa";
							// fnEscreve($sqlAudit);
							$arrayProcAudit = mysqli_query($connAdm->connAdm(), trim($sqlAudit));

							if (!$arrayProcAudit) {
								$cod_erro = Log_error_comand($connAdm->connAdm(), $conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sqlAudit, $nom_usuario);
							}
						}
					}

					if ($cod_empresa != "" && $cod_empresa != 0) {

						$sqlBusca = "SELECT * FROM matriz_integracao WHERE COD_EMPRESA = $cod_empresa";
						$query = mysqli_query($connAdm->connAdm(), trim($sqlBusca));

						if (mysqli_num_rows($query) == 0) {

							//insere matriz de integração
							$sqlMatriz = "INSERT INTO matriz_integracao (COD_EMPRESA, COD_ACAOINT, COD_FASEVND, COD_USUCADA, DAT_CADASTR)
							SELECT $cod_empresa, COD_ACAOINT, COD_FASEVND, $cod_usucada, NOW()
							FROM matriz_integracao
							WHERE COD_EMPRESA = $cod_integradora";
							// fnEscreve($sqlMatriz);
							$arrayProcMatriz = mysqli_query($connAdm->connAdm(), trim($sqlMatriz));

							if (!$arrayProcMatriz) {
								$cod_erro = Log_error_comand($connAdm->connAdm(), $conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sqlMatriz, $nom_usuario);
							}

							//busca matrizes criadas
							$sqlBuscaMatriz = "SELECT * FROM matriz_integracao WHERE COD_EMPRESA = $cod_empresa";
							$queryBusca = mysqli_query($connAdm->connAdm(), trim($sqlBuscaMatriz));

							// fnTesteSql($connAdm->connAdm(), trim($sqlBuscaMatriz));
							$cod_matrizes = "";
							while ($qrResult = mysqli_fetch_assoc($queryBusca)) {
								if ($cod_matrizes != '') {
									$cod_matrizes .= ',';
								}
								$cod_matrizes .= $qrResult['COD_MATRIZ'];
							}

							//insere campos obrigatorios na auditoria
							$sqlAudit = "
							UPDATE AUDITORIA_EMPRESA SET 
							COD_MATRIZ = '$cod_matrizes',
							FASE1 = 'S'
							WHERE COD_EMPRESA = $cod_empresa";
							// fnEscreve($sqlAudit);
							$arrayProcAudit = mysqli_query($connAdm->connAdm(), trim($sqlAudit));

							if (!$arrayProcAudit) {
								$cod_erro = Log_error_comand($connAdm->connAdm(), $conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sqlAudit, $nom_usuario);
							}
						}
					}

					$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					break;
				case 'ALT':
					$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";
					break;
				case 'EXC':
					$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";
					break;
			}
			$msgTipo = 'alert-success';
		}
	}
}


//busca dados da url	
if ($cod_empresa != "" || isset($_GET['id']) && is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
	//busca dados da empresa
	if (isset($_GET['id'])) {
		$cod_empresa = fnDecode($_GET['id']);
	}
	$sql = "SELECT * FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

	if (isset($arrayQuery)) {
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_fantasi = $qrBuscaEmpresa['NOM_FANTASI'];
		$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
		$num_cgcecpf = $qrBuscaEmpresa['NUM_CGCECPF'];
		$cod_chaveco = $qrBuscaEmpresa['COD_CHAVECO'];
		$cod_integradora = $qrBuscaEmpresa['COD_INTEGRADORA'];
		$des_sufixo = $qrBuscaEmpresa['DES_SUFIXO'];
		$cod_consultor = $qrBuscaEmpresa['COD_CONSULTOR'];
		$nom_respons = $qrBuscaEmpresa['NOM_RESPONS'];
		$num_telefon = $qrBuscaEmpresa['NUM_TELEFON'];
		$desabilita = "disabled";
		$leitura = "leitura";
		$desa_cadtoken = "disabled";
		$desa_pontuar = "disabled";
		$desa_negativo = "disabled";
		$check_cadtoken = "";
		$check_pontuar = "";
		$check_negativo = "";
		$desabilitado = "";
		$mostraBtnEmp = "";

		if ($qrBuscaEmpresa['LOG_CADTOKEN'] == 'S') {
			$check_cadtoken = "checked";
		}

		if ($qrBuscaEmpresa['LOG_PONTUAR'] == 'S') {
			$check_pontuar = "checked";
		}

		if ($qrBuscaEmpresa['LOG_NEGATIVO'] == 'S') {
			$check_negativo = "checked";
		}

		$sqlAudit = "SELECT * FROM
			AUDITORIA_EMPRESA
			WHERE COD_EMPRESA = $cod_empresa";

		$query = mysqli_query($connAdm->connAdm(), trim($sqlAudit));
		if ($result = mysqli_fetch_assoc($query)) {
			$qtd_univend = $result['QTD_UNIVEND'];
		}
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

				<?php //if ($msgRetorno <> '') { 
				?>
				<div class="alert alert-warning alert-dismissible top30 bottom30" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<?php //echo $msgRetorno; 
					?>
					Para gerar os dados, clique em <strong><i class="fal fa-cogs"></i>&nbsp;&nbsp;Processar</strong>, e depois em <strong>Próximo&nbsp;<i class="fal fa-arrow-right"></i></strong>
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
				</style>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">



						<div class="container-fluid">

							<div class="passo" id="passo1">

								<div class="row">

									<div class="col-sm-12" style="padding-left: 0;">

										<div class="col-xs-2" style="padding-left: 0;"> <!-- required for floating -->

											<?php
											$sqlAudit = "SELECT * FROM
												AUDITORIA_EMPRESA
												WHERE COD_EMPRESA = $cod_empresa";

											$queryAudit = mysqli_query($connAdm->connAdm(), trim($sqlAudit));

											$passoUm = "fal fa-clock";
											$passoDois = "fal fa-clock";
											$passoTres = "fal fa-clock";
											$passoQuatro = "fal fa-clock";
											$passoCinco = "fal fa-clock";
											$bgPassoUm = "bg-warning";
											$bgPassoDois = "bg-warning";
											$bgPassoTres = "bg-warning";
											$bgPassoQuatro = "bg-warning";
											$bgPassoCinco = "bg-warning";
											$desabilitaPag = 'onclick="event.preventDefault();" style="pointer-events: none;"';
											if ($resultAudit = mysqli_fetch_assoc($queryAudit)) {
												if ($resultAudit['FASE1'] == 'S') {
													$passoUm = "fal fa-check";
													$bgPassoUm = "bg-success";
												}

												if ($resultAudit['FASE2'] == 'S') {
													$passoDois = "fal fa-check";
													$bgPassoDois = "bg-success";
													$desabilitaPag = "";
												}

												if ($resultAudit['FASE3'] == 'S') {
													$passoTres = "fal fa-check";
													$bgPassoTres = "bg-success";
												}

												if ($resultAudit['FASE4'] == 'S') {
													$passoQuatro = "fal fa-check";
													$bgPassoQuatro = "bg-success";
												}

												if ($resultAudit['FASE5'] == 'S') {
													$passoCinco = "fal fa-check";
													$bgPassoCinco = "bg-success";
												}
											}
											?>
											<!-- Nav tabs -->
											<ul class="vTab nav nav-tabs tabs-left text-center">

												<li class="active vTab">
													<a href="action.do?mod=<?= fnEncode(2091) ?>&id=<?= fnEncode($cod_empresa) ?>">

														<div class="notify-badge text-center <?= $bgPassoUm ?>" id="notificaPasso1" style><span class="<?= $passoUm ?>"></span></div>

														<i class="fal fa-user-edit fa-2x" style="margin: 10px 0 2px 0"></i>
														<h5 class="hidden-xs" style="margin: 3px 0 0 0">Empresa e Usuários</h5>
													</a>
												</li>

												<li class="vTab">
													<a href="action.do?mod=<?= fnEncode(2092) ?>&id=<?= fnEncode($cod_empresa) ?>">

														<div class="notify-badge text-center <?= $bgPassoDois ?>" id="notificaPasso2"><span class="<?= $passoDois ?>"></span></div>

														<i class="fal fa-database fa-2x" style="margin: 10px 0 2px 0"></i>
														<h5 class="hidden-xs" style="margin: 3px 0 0 0">Database</h5>
													</a>
												</li>

												<li class="vTab">
													<a href="action.do?mod=<?= fnEncode(2093) ?>&id=<?= fnEncode($cod_empresa) ?>" <?= $desabilitaPag ?>>

														<div class="notify-badge text-center <?= $bgPassoTres ?>" id="notificaPasso3"><span class="<?= $passoTres ?>"></span></div>

														<i class="fal fa-user-edit fa-2x" style="margin: 10px 0 2px 0"></i>
														<h5 class="hidden-xs" style="margin: 3px 0 0 0">Clientes e Hotsite</h5>
													</a>
												</li>

												<li class="vTab">
													<a href="action.do?mod=<?= fnEncode(2096) ?>&id=<?= fnEncode($cod_empresa) ?>" <?= $desabilitaPag ?>>

														<div class="notify-badge text-center <?= $bgPassoQuatro ?>" id="notificaPasso4"><span class="<?= $passoQuatro ?>"></span></div>

														<i class="fal fa-user-edit fa-2x" style="margin: 10px 0 2px 0"></i>
														<h5 class="hidden-xs" style="margin: 3px 0 0 0">Campanhas e Comunicação</h5>
													</a>
												</li>

												<li class="vTab">
													<a href="action.do?mod=<?= fnEncode(2102) ?>&id=<?= fnEncode($cod_empresa) ?>" <?= $desabilitaPag ?>>

														<div class="notify-badge text-center <?= $bgPassoCinco ?>" id="notificaPasso5"><span class="<?= $passoCinco ?>"></span></div>
														<i class="fal fa-key fa-2x" style="margin: 10px 0 2px 0"></i>
														<h5 class="hidden-xs" style="margin: 3px 0 0 0">Dados de Login</h5>
													</a>
												</li>

											</ul>
										</div>

										<div class="col-xs-10">
											<!-- conteudo abas -->
											<div class="tab-content">


												<!-- aba produtos-->
												<div class="tab-pane active"">
																	<h4 style=" margin: 0 0 5px 0;"><span class="bolder">Empresa e Usuários</span></h4>
													<small style="font-size: 12px;"></small>

													<div class="row">

														<div class="col-md-12">
															<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

																<div class="push20"></div>

																<fieldset>
																	<legend>Dados da Empresa</legend>

																	<div class="row">

																		<div class="col-md-3">
																			<div class="form-group">
																				<label for="inputName" class="control-label">Cadastro com Token</label>
																				<div class="push5"></div>
																				<label class="switch switch-small">
																					<input type="checkbox" name="LOG_CADTOKEN" id="LOG_CADTOKEN" class="switch switch-small" value="S" <?= $desa_cadtoken ?> <?= $check_cadtoken ?>>
																					<span></span>
																				</label>
																			</div>
																		</div>

																		<div class="col-md-3">
																			<div class="form-group">
																				<label for="inputName" class="control-label">Pontuar <br />Funcionários</label>
																				<div class="push5"></div>
																				<label class="switch switch-small">
																					<input type="checkbox" name="LOG_PONTUAR" id="LOG_PONTUAR" class="switch switch-small" value="S" <?= $desa_pontuar ?> <?= $check_pontuar ?>>
																					<span></span>
																				</label>
																			</div>
																		</div>

																		<div class="col-md-3">
																			<div class="form-group">
																				<label for="inputName" class="control-label">Permite Saldo Negativo?</label>
																				<div class="push5"></div>
																				<label class="switch switch-small">
																					<input type="checkbox" name="LOG_NEGATIVO" id="LOG_NEGATIVO" class="switch switch-small" value="S" <?= $desa_negativo ?> <?= $check_negativo ?>>
																					<span></span>
																				</label>
																			</div>
																		</div>

																	</div>

																	<div class="push10"></div>

																	<div class="row">

																		<div class="col-md-2">
																			<div class="form-group">
																				<label for="inputName" class="control-label required">Nome da Empresa</label>
																				<input type="text" class="form-control input-sm <?= $leitura ?>" name="NOM_EMPRESA" id="NOM_EMPRESA" <?= $desabilita ?> value="<?= $nom_empresa ?>" maxlength="100" data-error="Campo obrigatório" required>
																				<div class="help-block with-errors"></div>
																			</div>
																		</div>

																		<div class="col-md-2">
																			<div class="form-group">
																				<label for="inputName" class="control-label required">Nome Fantasia</label>
																				<input type="text" class="form-control input-sm <?= $leitura ?>" name="NOM_FANTASI" id="NOM_FANTASI" <?= $desabilita ?> maxlength="40" value="<?= $nom_fantasi ?>" data-error="Campo obrigatório" required>
																				<div class="help-block with-errors"></div>
																				<!-- <div class="help-block with-errors validaTemp"></div> -->
																			</div>
																		</div>

																		<div class="col-md-2">
																			<div class="form-group">
																				<label for="inputName" class="control-label required">CNPJ</label>
																				<input type="text" class="form-control input-sm cpfcnpj <?= $leitura ?>" name="NUM_CGCECPF" id="NUM_CGCECPF" maxlength="40" <?= $desabilita ?> value="<?= fnformatCnpjCpf($num_cgcecpf) ?>" data-error="Campo obrigatório" required>
																				<div class="help-block with-errors"></div>
																			</div>
																		</div>

																		<div class="col-md-2">
																			<div class="form-group">
																				<label for="inputName" class="control-label required">Sufixo da Empresa</label>
																				<input type="text" class="form-control input-sm <?= $leitura ?>" name="DES_SUFIXO" id="DES_SUFIXO" maxlength="100" <?= $desabilita ?> value="<?= $des_sufixo ?>" data-error="Campo obrigatório" required>
																				<div class="help-block with-errors"></div>
																			</div>
																		</div>

																		<div class="col-md-2">
																			<div class="form-group">
																				<label for="inputName" class="control-label">Nome Responsável</label>
																				<input type="text" class="form-control input-sm <?= $leitura ?>" name="NOM_RESPONS" id="NOM_RESPONS" maxlength="40" <?= $desabilita ?> value="<?= $nom_respons ?>">
																				<div class="help-block with-errors"></div>
																			</div>
																		</div>

																		<div class="col-md-2">
																			<div class="form-group">
																				<label for="inputName" class="control-label">Telefone Responsável</label>
																				<input type="text" class="form-control input-sm sp_celphones <?= $leitura ?>" name="NUM_TELEFON" id="NUM_TELEFON" maxlength="20" <?= $desabilita ?> value="<?= $num_telefon ?>" data-error="Campo obrigatório">
																				<div class="help-block with-errors"></div>
																			</div>
																		</div>

																	</div>

																	<div class="push10"></div>

																	<div class="row">

																		<div class="col-md-2">
																			<div class="form-group">
																				<label for="inputName" class="control-label required">Chave Identificação</label>
																				<select data-placeholder="Selecione a chave de identificação <?= $leitura ?>" name="COD_CHAVECO" <?= $desabilita ?> id="COD_CHAVECO" class="chosen-select-deselect" required>
																					<option value=""></option>
																					<?php

																					if ($_SESSION["SYS_COD_MASTER"] == "2") {
																						$sql = "select * from CHAVECADASTRO order by DES_CHAVECO
																												";
																					} else {
																						$sql = "select * from CHAVECADASTRO where COD_CHAVECO <> 6 order by DES_CHAVECO
																												";
																					}

																					$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

																					while ($qrListaChaveCad = mysqli_fetch_assoc($arrayQuery)) {
																						if ($cod_chaveco == $qrListaChaveCad['COD_CHAVECO']) {
																							$selected = "selected";
																						} else {
																							$selected = "";
																						}

																						echo "
																							<option value='" . $qrListaChaveCad['COD_CHAVECO'] . "' " . $selected . ">" . $qrListaChaveCad['DES_CHAVECO'] . "</option> 
																						";
																					}
																					?>
																				</select>
																				<div class="help-block with-errors"></div>
																			</div>
																		</div>

																		<div class="col-md-2">
																			<div class="form-group">
																				<label for="inputName" class="control-label">Integradora</label>
																				<select data-placeholder="Selecione a integradora <?= $leitura ?>" name="COD_INTEGRADORA" id="COD_INTEGRADORA" <?= $desabilita ?> class="chosen-select-deselect">
																					<option value=""></option>
																					<?php

																					$sql = "select * from empresas where COD_EMPRESA <> 1 and LOG_INTEGRADORA = 'S' order by NOM_FANTASI";
																					$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

																					while ($qrListaIntegradora = mysqli_fetch_assoc($arrayQuery)) {

																						if ($cod_integradora == $qrListaIntegradora['COD_EMPRESA']) {
																							$selected = "selected";
																						} else {
																							$selected = "";
																						}

																						echo "
																							<option value='" . $qrListaIntegradora['COD_EMPRESA'] . "' " . $selected . ">" . $qrListaIntegradora['NOM_FANTASI'] . "</option> 
																							";
																					}
																					?>
																				</select>
																				<div class="help-block with-errors"></div>
																			</div>

																		</div>

																		<div class="col-md-2">
																			<div class="form-group">
																				<label for="inputName" class="control-label">Consultor</label>
																				<select data-placeholder="Selecione um consultor" name="COD_CONSULTOR"
																					id="COD_CONSULTOR" class="chosen-select-deselect <?= $leitura ?>" <?= $desabilita ?>>
																					<option value=""></option>
																					<?php

																					$sql = "select COD_USUARIO, NOM_USUARIO from usuarios 
																						where usuarios.COD_EMPRESA = 3
																						and usuarios.DAT_EXCLUSA is null order by  usuarios.NOM_USUARIO ";
																					$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

																					while ($qrLista = mysqli_fetch_assoc($arrayQuery)) {
																						echo "
																		                <option value='" . $qrLista['COD_USUARIO'] . "' " . (@$cod_consultor == $qrLista['COD_USUARIO'] ? "selected" : "") . ">" . $qrLista['NOM_USUARIO'] . "</option> ";
																					}
																					?>
																				</select>
																				<div class="help-block with-errors"></div>
																			</div>
																		</div>

																		<div class="col-md-2">
																			<div class="form-group">
																				<label for="inputName" class="control-label">Nro. de Unidades</label>
																				<input type="number" class="form-control input-sm int" name="QTD_UNIVEND" id="QTD_UNIVEND" <?= $desabilita ?> maxlength="3" value="<?= $qtd_univend ?>" required>
																				<div class="help-block with-errors">Qtd. Aproximada</div>
																			</div>
																		</div>

																	</div>

																	<div class="row text-right col-lg-12" <?= $mostraBtnEmp ?>>
																		<a href="action.do?mod=<?= fnEncode(1020) ?>&id=<?= fnEncode($cod_empresa) ?>" target="_blank" class="btn btn-info btn-sm">Acessar Empresa</a>
																	</div>

																</fieldset>

																<div class="push10"></div>

																<fieldset>
																	<legend>Dados da Unidade</legend>

																	<div class="row">
																		<div class="col-md-11">
																			<div class="row">
																				<div class="col-md-3">
																					<label>Nome da Unidade</label>
																				</div>

																				<div class="col-md-2">
																					<label>CNPJ</label>
																				</div>

																				<div class="col-md-3">
																					<label>Nome Fantasia</label>
																				</div>

																				<div class="col-md-2">
																					<label>Telefone</label>
																				</div>

																				<div class="col-md-2">
																					<label>E-mail</label>
																				</div>
																			</div>
																		</div>
																	</div>

																	<div class="push10"></div>

																	<div class="form_field_outer">

																		<div class="row form_field_outer_row">
																			<div class="col-md-11">
																				<div class="row">
																					<div class="col-md-3">
																						<div class="form-group">
																							<input type="text" placeholder="Nome da Unidade" class="form-control input-sm" name="NOM_UNIVEND[]" id="NOM_UNIVEND_1" maxlength="50" required>
																							<div class="help-block with-errors"></div>
																						</div>
																					</div>

																					<div class="col-md-2">
																						<div class="form-group">
																							<input type="text" placeholder="CNPJ" class="form-control input-sm cpfcnpj" name="NUM_CGCECPFUNV[]" id="NUM_CGCECPFUNV_1" maxlength="50" required>
																							<div class="help-block with-errors"></div>
																						</div>
																					</div>

																					<div class="col-md-3">
																						<div class="form-group">
																							<input type="text" placeholder="Nome Fantasia" class="form-control input-sm" name="NOM_FANTASIUNV[]" id="NOM_FANTASIUNV_1" maxlength="50" required>
																							<div class="help-block with-errors"></div>
																						</div>
																					</div>

																					<div class="col-md-2">
																						<div class="form-group">
																							<input type="text" placeholder="Telefone" class="form-control input-sm sp_celphones" name="NUM_CELULARUNV[]" id="NUM_CELULARUNV_1" maxlength="50" required>
																							<div class="help-block with-errors"></div>
																						</div>
																					</div>


																					<div class="col-md-2">
																						<div class="form-group">
																							<input type="email" placeholder="E-mail" class="form-control input-sm" name="DES_EMAILUSUNV[]" id="DES_EMAILUSUNV_1" maxlength="50" required>
																							<div class="help-block with-errors"></div>
																						</div>
																					</div>
																				</div>
																			</div>
																			<div class="col-md-1 form-group add_del_btn_outer">
																				<!-- <a href="javascript:void(0)" class="btn btn-xs btn-default btn_round add_node_btn_frm_field" title="Duplicar esta linha">
																					<i class="fas fa-copy"></i>
																				</a> -->

																				<a href="javascript:void(0)" class="btn btn-xs btn-danger btn_round remove_node_btn_frm_field" disabled>
																					<i class="fas fa-trash-alt"></i>
																				</a>
																			</div>
																		</div>
																	</div>
																	<div class="row">
																		<div class="col-md-12">
																			<a href="javascript:void(0)" class="btn btn-info add_new_frm_field_btn"><i class="fas fa-plus add_icon"></i> Adicionar Outra Unidade</a>
																		</div>
																	</div>



																	<div class="push10"></div>

																	<?php

																	$sql = "SELECT * FROM UNIDADEVENDA WHERE COD_EMPRESA = $cod_empresa ORDER BY COD_UNIVEND DESC";
																	$queryUnv = mysqli_query($connAdm->connAdm(), trim($sql));
																	if (mysqli_num_rows($queryUnv) > 0) {
																	?>

																		<div class="push30"></div>

																		<div class="col-lg-12">

																			<div class="no-more-tables">

																				<form name="formLista">

																					<table class="table table-bordered table-striped table-hover tableSorter buscavel">
																						<thead>
																							<tr>
																								<th class="{ sorter: false }" width="40"></th>
																								<th>Código</th>
																								<th>Nome da Unidade</th>
																								<th>Nome Fantasia</th>
																								<th>CNPJ</th>
																								<th>Telefones</th>
																								<th>Email</th>
																							</tr>
																						</thead>
																						<tbody>
																							<?php
																							while ($qrBuscaUnv = mysqli_fetch_assoc($queryUnv)) {

																								echo "
																										<tr>
																										<td width='40'></td>
																										<td><small>" . $qrBuscaUnv['COD_UNIVEND'] . "</td>
																										<td><small>" . $qrBuscaUnv['NOM_UNIVEND'] . "</td>
																										<td><small>" . $qrBuscaUnv['NOM_FANTASI'] . "</td>
																										<td><small>" . $qrBuscaUnv['NUM_CGCECPF'] . "</td>
																										<td><small>" . $qrBuscaUnv['NUM_CELULAR'] . "</td>
																										<td><small>" . $qrBuscaUnv['NOM_EMAIL'] . "</td>
																										</tr>
																										";
																							}
																							?>

																						</tbody>

																					</table>

																				</form>

																			</div>

																		</div>
																	<?php
																	}
																	?>

																	<div class="row text-right col-lg-12" <?= $mostraBtnEmp ?>>
																		<a href="action.do?mod=<?= fnEncode(1023) ?>&id=<?= fnEncode($cod_empresa) ?>" target="_blank" class="btn btn-info btn-sm">Acessar Unidades</a>
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
								</div>

								<input type="hidden" name="opcao" id="opcao" value="">
								<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
								<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">
								<input type="hidden" name="FEZ_UPLOAD" id="FEZ_UPLOAD" value="N">
								<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?= $cod_empresa ?>">

								<hr>

								<div class="form-group text-right col-lg-12">
									<button type="submit" name="CAD" id="CAD" class="btn btn-success getBtn"><i class="fas fa-cogs"></i>&nbsp;&nbsp;Processar</button>
									<a href="action.do?mod=<?= fnEncode(2092) ?>&id=<?= fnEncode($cod_empresa) ?>" class="btn btn-primary next next1" <?= $desabilitado ?> name="next">Próximo&nbsp;&nbsp;<i class="fas fa-arrow-right"></i></a>
								</div>

								<div class="push10"></div>

							</div>

						</div>

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
	$(document).ready(function() {

		// $('#NOM_FANTASI').on("blur", function() {
		// 	let nomFantasi = $(this).val();
		// 	$.ajax({
		// 		type: "POST",
		// 		url: "ajxValidaAutom.do?opcao=valFantasi&id=<?php echo fnEncode($cod_empresa); ?>",
		// 		data: {
		// 			NOM_FANTASI: nomFantasi
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


		$('#NUM_CGCECPF').on("blur", function() {
			$('#NOM_UNIVEND_1').val($('#NOM_EMPRESA').val());
			$('#NUM_CGCECPFUNV_1').val($('#NUM_CGCECPF').val());
			$('#NOM_FANTASIUNV_1').val($('#NOM_FANTASI').val());
		})

		var SPMaskBehavior = function(val) {
				return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
			},
			spOptions = {
				onKeyPress: function(val, e, field, options) {
					field.mask(SPMaskBehavior.apply({}, arguments), options);
				}
			};

		$('.sp_celphones').mask(SPMaskBehavior, spOptions);

	});

	$(document).ready(function() {
		$(".next").on("click", function(e) {
			if ($.trim($("#COD_EMPRESA").val()) === 0) {
				e.preventDefault();
			}
		});

		$("body").on("click", ".add_new_frm_field_btn", function() {

			var index = $(".form_field_outer").find(".form_field_outer_row").length + 1;
			$(".form_field_outer").append(`
				<div class="row form_field_outer_row">
					<div class="col-md-11">
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<input type="text" placeholder="Nome da Unidade" class="form-control input-sm nom_univend" name="NOM_UNIVEND[]" id="NOM_UNIVEND_${index}" maxlength="50" required>
									<div class="help-block with-errors"></div>
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<input type="text" placeholder="CNPJ" class="form-control input-sm num_cgcecpfunv" name="NUM_CGCECPFUNV[]" id="NUM_CGCECPFUNV_${index}" maxlength="50" required>
									<div class="help-block with-errors"></div>
								</div>
							</div>

							<div class="col-md-3">
								<div class="form-group">
									<input type="text" placeholder="Nome Fantasia" class="form-control input-sm nom_fantasiunv" name="NOM_FANTASIUNV[]" id="NOM_FANTASIUNV_${index}" maxlength="50" required>
									<div class="help-block with-errors"></div>
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<input type="text" placeholder="Telefone" class="form-control input-sm num_celularunv" name="NUM_CELULARUNV[]" id="NUM_CELULARUNV_${index}" maxlength="50" required>
									<div class="help-block with-errors"></div>
								</div>
							</div>


							<div class="col-md-2">
								<div class="form-group">
									<input type="text" placeholder="E-mail" class="form-control input-sm des_emailusunv" name="DES_EMAILUSUNV[]" id="DES_EMAILUSUNV_${index}" maxlength="50" required>
									<div class="help-block with-errors"></div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-1 form-group add_del_btn_outer">
						<!-- <a href="javascript:void(0)" class="btn btn-xs btn-default btn_round add_node_btn_frm_field" title="Duplicar esta linha">
							<i class="fas fa-copy"></i>
						</a> -->

						<a href="javascript:void(0)" class="btn btn-xs btn-danger btn_round remove_node_btn_frm_field">
							<i class="fas fa-trash-alt"></i>
						</a>
					</div>
				</div>
        	`);

			$(".form_field_outer").find(".remove_node_btn_frm_field:not(:first)").prop("disabled", false);
			$(".form_field_outer").find(".remove_node_btn_frm_field").first().prop("disabled", true);
		});
	});


	///======Clone method
	$(document).ready(function() {
		$("body").on("click", ".add_node_btn_frm_field", function(e) {
			var index = $(e.target).closest(".form_field_outer").find(".form_field_outer_row").length + 1;
			var cloned_el = $(e.target).closest(".form_field_outer_row").clone(true);

			$(e.target).closest(".form_field_outer").last().append(cloned_el).find(".remove_node_btn_frm_field:not(:first)").prop("disabled", false);

			$(e.target).closest(".form_field_outer").find(".remove_node_btn_frm_field").first().prop("disabled", true);

			alert($(e.target).attr("id"));

			//change id
			$(e.target).closest(".form_field_outer").find(".form_field_outer_row").last().find(".nom_univend").attr("id", "NOM_UNIVEND_" + index);
			$(e.target).closest(".form_field_outer").find(".form_field_outer_row").last().find(".num_cgcecpfunv").attr("id", "NUM_CGCECPFUNV_" + index);
			$(e.target).closest(".form_field_outer").find(".form_field_outer_row").last().find(".nom_fantasiunv").attr("id", "NOM_FANTASIUNV_" + index);
			$(e.target).closest(".form_field_outer").find(".form_field_outer_row").last().find(".num_celularunv").attr("id", "NUM_CELULARUNV_" + index);
			$(e.target).closest(".form_field_outer").find(".form_field_outer_row").last().find(".des_emailusunv").attr("id", "DES_EMAILUSUNV_" + index);

			console.log(cloned_el);
			//count++;
		});
	});


	$(document).ready(function() {
		//===== delete the form fieed row
		$("body").on("click", ".remove_node_btn_frm_field", function() {
			$(this).closest(".form_field_outer_row").remove();
			console.log("success");
		});
	});
</script>