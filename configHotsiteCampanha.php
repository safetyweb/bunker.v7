<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$hashLocal = "";
$contemRegistro = "";
$msgRetorno = "";
$msgTipo = "";
$cod_registr = "";
$cod_univend_pref = "";
$cod_filtros = "";
$des_chavecamp = "";
$img_bannermain = "";
$img_bannercad = "";
$img_bannerlog = "";
$txt_bannermain = "";
$txt_bannercad = "";
$pct_vantagem = "";
$qtd_vantagem = 0;
$dat_min = "";
$dat_max = "";
$log_ctrlext = "";
$Arr_COD_PERSONA = "";
$i = 0;
$cod_persona = "";
$cod_campanha = "";
$cod_usucada = "";
$hHabilitado = "";
$hashForm = "";
$arrayInsert = [];
$cod_erro = "";
$actual_link = "";
$MODULO = "";
$COD_MODULO = "";
$nom_usuarioSESSION = "";
$sqlInsert = "";
$arrayUpdate = [];
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$qrBuscaCampanha = "";
$log_ativo = "";
$des_campanha = "";
$abr_campanha = "";
$des_icone = "";
$tip_campanha = "";
$log_realtime = "";
$dat_ini = "";
$hor_ini = "";
$dat_fim = "";
$hor_fim = "";
$maxPersona = "";
$msgPersona = "";
$sqlCampanha = "";
$qrBuscaTpCampanha = "";
$nom_fantasi = "";
$nom_tpcampa = "";
$abv_tpcampa = "";
$des_iconecp = "";
$num_pessoas = "";
$tem_personas = "";
$arrayDom = [];
$qrDom = "";
$des_dominio = "";
$cod_dominio = "";
$extensaoDominio = "";
$linkCode2 = "";
$dias30 = "";
$abaCampanhas = "";
$abaCli = "";
$check = "";
$qrListaUnive = "";
$disabled = "";
$linhas = "";
$countFiltros = "";
$qrTipo = "";
$sqlFiltro = "";
$arrayFiltros = [];
$qrFiltros = "";
$arrayAutorizado = [];
$andUnidade = "";
$qrListaPersonas = "";
$desabilitado = "";
$desabilitadoOnTxt = "";
$desabilitadoRg = "";
$desabilitadoRgTxt = "";
$mask = "";
$titulo = "";
$txt = "";
$col = "";
$colTxt = "";
$tip_credito = "";
$txt_cred = "";
$tip_geracao = "";
$qrCampanha = "";
$chk_bannermain = "";
$chk_bannercad = "";
$chk_bannerlog = "";
$nome = "";
$hashLocal = mt_rand();

$contemRegistro = "";

$conn = conntemp($cod_empresa, "");
$adm = $connAdm->connAdm();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_registr = fnLimpaCampo(@$_REQUEST['COD_REGISTR']);
		$cod_empresa = fnLimpaCampoZero(@$_REQUEST['COD_EMPRESA']);
		$cod_univend_pref = fnLimpaCampoZero(@$_REQUEST['COD_UNIVEND']);
		$cod_filtros = fnLimpaCampo(@$_REQUEST['COD_FILTROS']);
		$des_chavecamp = fnLimpaCampoHtml(@$_REQUEST['DES_CHAVECAMP']);
		$img_bannermain = fnLimpaCampo(@$_REQUEST['IMG_BANNERMAIN']);
		$img_bannercad = fnLimpaCampo(@$_REQUEST['IMG_BANNERCAD']);
		$img_bannerlog = fnLimpaCampo(@$_REQUEST['IMG_BANNERLOG']);
		$txt_bannermain = fnLimpaCampo(@$_REQUEST['TXT_BANNERMAIN']);
		$txt_bannercad = fnLimpaCampo(@$_REQUEST['TXT_BANNERCAD']);
		$pct_vantagem = fnValorSql(@$_REQUEST['PCT_VANTAGEM']);
		$qtd_vantagem = fnLimpaCampoZero(@$_REQUEST['QTD_VANTAGEM']);
		$dat_min = fnDataSql(@$_REQUEST['DAT_MIN']);
		$dat_max = fnDataSql(@$_REQUEST['DAT_MAX']);
		$log_ctrlext = fnLimpaCampo(@$_REQUEST['LOG_CTRLEXT']);
		if ($log_ctrlext == "") {
			$log_ctrlext = "N";
		}

		if (isset($_POST['COD_PERSONA'])) {
			$Arr_COD_PERSONA = @$_POST['COD_PERSONA'];

			for ($i = 0; $i < count($Arr_COD_PERSONA); $i++) {
				$cod_persona = $cod_persona . $Arr_COD_PERSONA[$i] . ",";
			}

			$cod_persona = substr($cod_persona, 0, -1);
		} else {
			$cod_persona = "0";
		}

		$cod_campanha = fnDecode(@$_GET['idc']);
		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
		$opcao = @$_REQUEST['opcao'];

		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		if ($opcao != '') {

			switch ($opcao) {

				case 'CAD':
					$sql = "INSERT INTO CAMPANHA_HOTSITE(
					COD_EMPRESA,
					COD_CAMPANHA,
					LOG_CTRLEXT,
					COD_FILTROS,
					DES_CHAVECAMP,
					IMG_BANNERMAIN,
					IMG_BANNERCAD,
					TXT_BANNERMAIN,
					TXT_BANNERCAD,
					COD_UNIVEND_PREF,
					IMG_BANNERLOG,
					PCT_VANTAGEM,
					QTD_VANTAGEM,
					DAT_MIN,
					DAT_MAX,
					COD_PERSONA,
					COD_USUCADA
					) VALUES(
					$cod_empresa,
					$cod_campanha,
					'$log_ctrlext',
					'$cod_filtros',
					'$des_chavecamp',
					'$img_bannermain',
					'$img_bannercad',
					'$txt_bannermain',
					'$txt_bannercad',
					$cod_univend_pref,
					'$img_bannerlog',
					'$pct_vantagem',
					'$qtd_vantagem',
					'$dat_min',
					'$dat_max',
					'$cod_persona',
					$cod_usucada
				)";

					$arrayInsert = mysqli_query(conntemp($cod_empresa, ''), trim($sql));

					if (!$arrayInsert) {

						$cod_erro = Log_error_comand($connAdm->connAdm(), conntemp($cod_empresa, ''), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql, $nom_usuarioSESSION);
					} else {
						$sqlInsert = "INSERT INTO CAMPANHAREGRA(
							COD_CAMPANHA,
							COD_PERSONA,
							PCT_VANTAGEM,
							QTD_VANTAGEM,
							QTD_RESULTADO,
							COD_USUCADA,
							NOM_VANTAGE,
							NUM_PESSOAS,
							COD_VANTAGE,
							LOG_CPFCNPJ,
							LOG_EMAIL,
							LOG_CELULAR,
							LOG_PRODUTO,
							LOG_CATPROD,
							LOG_INDICADOR,
							LOG_UNIFICA,
							PCT_VANTAGEM_IND,
							COD_VANTAGEM_IND,
							QTD_RESULTADO_IND,
							TIP_GERACAO,
							CPS_EXTRA_SEG,
							CPS_EXTRA_TER,
							CPS_EXTRA_QUA,
							CPS_EXTRA_QUI,
							CPS_EXTRA_SEX,
							CPS_EXTRA_SAB,
							CPS_EXTRA_DOM,
							CPS_EXTIND_SEG,
							CPS_EXTIND_TER,
							CPS_EXTIND_QUA,
							CPS_EXTIND_QUI,
							CPS_EXTIND_SEX,
							CPS_EXTIND_SAB,
							CPS_EXTIND_DOM,
							COD_UNIVENDESP,
							QTD_CUPOMPROD,
							QTD_CUPOMFORM,
							QTD_CUPOMFAIXA,
							QTD_CUPOMCATEG,
							QTD_CUPOMFORNE,
							LOG_CATESP,
							DAT_CADASTR
							)VALUES(
							$cod_campanha,
							'$cod_persona',
							'$pct_vantagem',
							'$qtd_vantagem',
							0,
							$cod_usucada,
							'$des_chavecamp',
							0,
							1,
							'N',
							'N',
							'N',
							'S',
							'N',
							'N',
							'N',
							0,
							0,
							0,
							'N',
							0,
							0,
							0,
							0,
							0,
							0,
							0,
							0,
							0,
							0,
							0,
							0,
							0,
							0,
							'9999',
							0,
							0,
							0,
							0,
							0,
							'N',
							NOW()
						)";
						// fnEscreve($sqlInsert);
						mysqli_query(connTemp($cod_empresa, ""), trim($sqlInsert));
					}

					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível cadastrar o registro : $cod_erro";
					}

					break;

				case 'ALT':

					$sql = "UPDATE CAMPANHA_HOTSITE SET
						LOG_CTRLEXT = '$log_ctrlext',
						COD_FILTROS = '$cod_filtros'
,						DES_CHAVECAMP = '$des_chavecamp',
						IMG_BANNERMAIN = '$img_bannermain',
						IMG_BANNERCAD = '$img_bannercad',
						TXT_BANNERMAIN = '$txt_bannermain',
						TXT_BANNERCAD = '$txt_bannercad',
						COD_UNIVEND_PREF = $cod_univend_pref,
						IMG_BANNERLOG = '$img_bannerlog',
						PCT_VANTAGEM = '$pct_vantagem',
						QTD_VANTAGEM = '$qtd_vantagem',
						DAT_MIN = '$dat_min',
						DAT_MAX = '$dat_max',
						COD_PERSONA = '$cod_persona',
						COD_ALTERAC = $cod_usucada,
						DAT_ALTERAC = NOW()
						WHERE COD_REGISTR = $cod_registr 
						AND COD_EMPRESA = $cod_empresa";

					// fnEscreve($sql);

					$arrayUpdate = mysqli_query(conntemp($cod_empresa, ''), trim($sql));
					// fnTesteSql(conntemp($cod_empresa, ""), trim($sql));
					if (!$arrayUpdate) {

						$cod_erro = Log_error_comand($connAdm->connAdm(), conntemp($cod_empresa, ''), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql, $nom_usuarioSESSION);
					} else {
						$sql = "UPDATE CAMPANHAREGRA SET
								PCT_VANTAGEM = '$pct_vantagem',
								QTD_VANTAGEM = '$qtd_vantagem',
								COD_PERSONA = $cod_persona
								WHERE NOM_VANTAGE = '$des_chavecamp'";

						mysqli_query(conntemp($cod_empresa, ''), trim($sql));
					}

					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível alterar o registro : $cod_erro";
					}
					break;
			}

			if ($cod_erro == 0 || $cod_erro == "") {
				$msgTipo = 'alert-success';
			} else {
				$msgTipo = 'alert-danger';
			}
		}
	}
}

//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode(@$_GET['id']);
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

if (is_numeric(fnLimpacampo(fnDecode(@$_GET['idc'])))) {

	$cod_campanha = fnDecode(@$_GET['idc']);
	$sql = "SELECT * FROM CAMPANHA where COD_CAMPANHA = '" . $cod_campanha . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
	$qrBuscaCampanha = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$log_ativo = $qrBuscaCampanha['LOG_ATIVO'];
		$des_campanha = $qrBuscaCampanha['DES_CAMPANHA'];
		$abr_campanha = $qrBuscaCampanha['ABR_CAMPANHA'];
		$des_icone = $qrBuscaCampanha['DES_ICONE'];
		$tip_campanha = $qrBuscaCampanha['TIP_CAMPANHA'];
		$log_realtime = $qrBuscaCampanha['LOG_REALTIME'];
		$dat_ini = $qrBuscaCampanha['DAT_INI'];
		$hor_ini = $qrBuscaCampanha['HOR_INI'];
		$dat_fim = $qrBuscaCampanha['DAT_FIM'];
		$hor_fim = $qrBuscaCampanha['HOR_FIM'];

		if ($log_realtime == "S") {
			$maxPersona = 1;
			$msgPersona = "Campanhas em <b>tempo real</b> permitem a utilização de <b>uma persona por campanha</b>";
		} else {
			$maxPersona = 10;
			$msgPersona = "";
		}
	}


	$sqlCampanha = "SELECT 
			CP.COD_UNIVENDESP, 
			UNI.NOM_FANTASI 
			FROM CAMPANHAREGRA AS CP 
			INNER JOIN UNIDADEVENDA AS UNI ON UNI.COD_UNIVEND = CP.COD_UNIVENDESP
			where CP.COD_CAMPANHA = '" . $cod_campanha . "' ";

	$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sqlCampanha);
	if ($qrBuscaTpCampanha = mysqli_fetch_assoc($arrayQuery)) {
		$cod_univend_pref = $qrBuscaTpCampanha['COD_UNIVENDESP'];
		$nom_fantasi = $qrBuscaTpCampanha['NOM_FANTASI'];
	} else {
		$cod_univend_pref = "";
		$nom_fantasi = "";
	}
}

//fnEscreve($contemRegistro);

$cod_campanha = fnDecode(@$_GET['idc']);
$sql = "SELECT * FROM CAMPANHA where COD_CAMPANHA = '" . $cod_campanha . "' ";
//fnEscreve($sql);
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
$qrBuscaCampanha = mysqli_fetch_assoc($arrayQuery);

if (isset($arrayQuery)) {
	$des_campanha = $qrBuscaCampanha['DES_CAMPANHA'];
	$abr_campanha = $qrBuscaCampanha['ABR_CAMPANHA'];
	$des_icone = $qrBuscaCampanha['DES_ICONE'];
	$tip_campanha = $qrBuscaCampanha['TIP_CAMPANHA'];
}

//busca dados do tipo da campanha
$sql = "SELECT * FROM TIPOCAMPANHA where COD_TPCAMPA = '" . $tip_campanha . "' ";
//fnEscreve($sql);
$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
$qrBuscaTpCampanha = mysqli_fetch_assoc($arrayQuery);

if (isset($arrayQuery)) {
	$nom_tpcampa = $qrBuscaTpCampanha['NOM_TPCAMPA'];
	$abv_tpcampa = $qrBuscaTpCampanha['ABV_TPCAMPA'];
	$des_iconecp = $qrBuscaTpCampanha['DES_ICONE'];
}

//busca dados da regra 
$sql = "SELECT * FROM CAMPANHAREGRA where COD_CAMPANHA = '" . $cod_campanha . "' ";
// fnEscreve($sql);
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
$qrBuscaTpCampanha = mysqli_fetch_assoc($arrayQuery);

if (isset($arrayQuery)) {
	$num_pessoas = $qrBuscaTpCampanha['NUM_PESSOAS'];
} else {
	$num_pessoas = 0;
}

$sql = "SELECT DES_DOMINIO, COD_DOMINIO FROM SITE_EXTRATO WHERE COD_EMPRESA = $cod_empresa";
$arrayDom = mysqli_query(connTemp($cod_empresa, ""), trim($sql));

$qrDom = mysqli_fetch_assoc($arrayDom);

$des_dominio = $qrDom['DES_DOMINIO'];
$cod_dominio = $qrDom['COD_DOMINIO'];

if ($cod_dominio == 2) {
	$extensaoDominio = ".fidelidade.mk";
} else {
	$extensaoDominio = ".mais.cash";
}

// $linkCode2 = "https://" . $des_dominio . $extensaoDominio."/". $des_chavecamp;	
if ($dat_fim == "") {
	$dias30 = fnFormatDate(date("Y-m-d"));
	$dat_fim = fnDataSql($dias30);
	$dat_fim = date('Y-m-d', strtotime($dat_fim . ' +1 year'));
}

?>

<div class="push30"></div>

<div class="row">

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fal fa-terminal"></i>
					<span class="text-primary"><?php echo $NomePg; ?></span>
				</div>
			</div>

			<div class="portlet-body">

				<?php $abaCampanhas = 1022;
				include "abasCampanhasConfig.php"; ?>

				<?php if ($msgRetorno <> '') { ?>
					<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<?php echo $msgRetorno; ?>
					</div>
				<?php } ?>

				<?php $abaCli = 2013;
				include "abasRegrasConfig.php"; ?>

				<div class="push30"></div>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Dados Gerais</legend>

							<div class="row">

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Campanha</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="DES_CAMPANHA" id="DES_CAMPANHA" value="<?php echo $des_campanha ?>">
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Tipo do Programa</label>
										<div class="push10"></div>
										<span class="fa <?php echo $des_iconecp; ?>"></span> <b><?php echo $nom_tpcampa; ?> </b>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Início Campanha</label>
										<input type="text" class="form-control input-sm leitura f14" readonly="readonly" value="<?= fnDataShort($dat_ini) . " " . $hor_ini ?>">
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Fim Campanha</label>
										<input type="text" class="form-control input-sm leitura f14" readonly="readonly" value="<?= fnDataShort($dat_fim) . " " . $hor_fim ?>">
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Pessoas Atingidas</label>
										<div class="push10"></div>
										<span class="fa fa-users"></span>&nbsp; <?php echo number_format($num_pessoas, 0, ",", "."); ?>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Ativar Controles Externos</label><br />
										<label class="switch switch-small">
											<input type="checkbox" name="LOG_CTRLEXT" id="LOG_CTRLEXT" class="switch" value="S" <?php echo $check; ?> />
											<span></span>
										</label>
										<div class="help-block with-errors"></div>
									</div>
								</div>
							</div>

							<div class="push10"></div>

							<div class="row">

								<?php
								if ($tip_campanha == 22 || $tip_campanha == 23) {
								?>

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label required">Unidade Específica</label>

											<select data-placeholder="Selecione uma unidade" name="COD_UNIVEND" id="COD_UNIVEND" class="chosen-select-deselect requiredChk" style="width:100%;" tabindex="1" required>
												<?php
												$sql = "SELECT COD_UNIVEND, NOM_FANTASI, LOG_ESTATUS FROM UNIDADEVENDA WHERE COD_EMPRESA = $cod_empresa AND LOG_ESTATUS = 'S' AND DAT_EXCLUSA IS NULL ORDER BY NOM_FANTASI ";
												$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
												while ($qrListaUnive = mysqli_fetch_assoc($arrayQuery)) {

													if ($qrListaUnive['LOG_ESTATUS'] == 'N') {
														$disabled = "disabled";
													} else {
														$disabled = " ";
													}

													echo "
															<option value='" . $qrListaUnive['COD_UNIVEND'] . "'" . $disabled . ">" . ucfirst($qrListaUnive['NOM_FANTASI']) . "</option> 
															";
												}
												?>
											</select>
											<?php //fnEscreve($sql); 
											?>
											<div class="help-block with-errors"></div>
										</div>
									</div>
									<script>
										$("#formulario #COD_UNIVEND").val("<?php echo $cod_univend; ?>").trigger("chosen:updated");
									</script>

								<?php } else { ?>

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Unidades Específicas</label>
											<?php include "unidadesAutorizadasComboMulti.php"; ?>
										</div>
									</div>

								<?php } ?>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Chave da Campanha</label>
										<input type="text" class="form-control input-sm" name="DES_CHAVECAMP" id="DES_CHAVECAMP" maxlength="20" value="<?php if ($des_chavecamp != '') {
																																							echo $des_chavecamp;
																																						} else {
																																							echo "#";
																																						}  ?>" required>
									</div>
									<span class="help-block">#chaveCamp</span>
								</div>

								<?php

								$sql = "SELECT COD_TPFILTRO, DES_TPFILTRO FROM TIPO_FILTRO
									WHERE COD_EMPRESA = $cod_empresa
									ORDER BY NUM_ORDENAC";
								$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), trim($sql));

								if ($linhas = mysqli_num_rows($arrayQuery) > 0) {
									$countFiltros = 0;
								?>

									<div class="col-md-3">
										<label for="cars" class="control-label">Tag Dinâmica</label>
										<select data-placeholder="Selecione o Perfil" name="COD_PERFIL" id="COD_PERFIL" class="chosen-select-deselect">
											<?php while ($qrTipo = mysqli_fetch_assoc($arrayQuery)) {
												$sqlFiltro = "SELECT COD_FILTRO, DES_FILTRO FROM FILTROS_CLIENTE
													WHERE COD_TPFILTRO = " . $qrTipo['COD_TPFILTRO'];

												$arrayFiltros = mysqli_query(connTemp($cod_empresa, ''), trim($sqlFiltro));
											?><optgroup id=<?= $qrTipo['COD_TPFILTRO'] ?> label=<?= $qrTipo['DES_TPFILTRO'] ?>>
													<option value=""></option>
													<?php
													while ($qrFiltros = mysqli_fetch_assoc($arrayFiltros)) {
													?>

														<option value="<?= $qrFiltros['COD_FILTRO'] ?>"><?= $qrFiltros['DES_FILTRO'] ?></option>

													<?php
													} ?>
												</optgroup>
										<?php
												$countFiltros++;
											}
										}
										?>
										</select>

									</div>

									<div class="col-md-3">
										<label for="inputName" class="control-label">Banner Principal</label>
										<div class="input-group">
											<span class="input-group-btn">
												<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary upload" idinput="IMG_BANNERMAIN" extensao="img"><i class="fa fa-cloud-upload" aria-hidden="true"></i></a>
											</span>
											<input type="hidden" name="IMG_BANNERMAIN" id="IMG_BANNERMAIN" maxlength="100" value="<?php echo $img_bannermain; ?>">
											<input type="text" name="BANNERMAIN" id="BANNERMAIN" class="form-control input-sm" style="border-radius: 0 3px 3px  0;" maxlength="100" value="<?php echo fnBase64DecodeImg($img_bannermain); ?>">
										</div>
										<span class="help-block">(.jpg 1400px X 600px)</span>
									</div>

							</div>

							<div class="row">


								<div class="col-md-3">
									<label for="inputName" class="control-label">Banner Cadastro</label>
									<div class="input-group">
										<span class="input-group-btn">
											<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary upload2" idinput="IMG_BANNERCAD" extensao="img"><i class="fa fa-cloud-upload" aria-hidden="true"></i></a>
										</span>
										<input type="hidden" name="IMG_BANNERCAD" id="IMG_BANNERCAD" maxlength="100" value="<?php echo $img_bannercad; ?>">
										<input type="text" name="BANNERCAD" id="BANNERCAD" class="form-control input-sm" style="border-radius: 0 3px 3px  0;" maxlength="100" value="<?php echo fnBase64DecodeImg($img_bannercad); ?>">
									</div>
									<span class="help-block">(.png 300px X 80px)</span>
								</div>

								<div class="col-md-3">
									<label for="inputName" class="control-label">Banner Login</label>
									<div class="input-group">
										<span class="input-group-btn">
											<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary upload3" idinput="IMG_BANNERLOG" extensao="img"><i class="fa fa-cloud-upload" aria-hidden="true"></i></a>
										</span>
										<input type="hidden" name="IMG_BANNERLOG" id="IMG_BANNERLOG" maxlength="100" value="<?php echo $img_bannerlog; ?>">
										<input type="text" name="BANNERLOG" id="BANNERLOG" class="form-control input-sm" style="border-radius: 0 3px 3px  0;" maxlength="100" value="<?php echo fnBase64DecodeImg($img_bannerlog); ?>">
									</div>
									<span class="help-block">(.png 120px X 120px)</span>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Texto Página inicial</label>
										<input type="text" class="form-control input-sm" name="TXT_BANNERMAIN" id="TXT_BANNERMAIN" maxlength="300" value="<?php echo $txt_bannermain; ?>">
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Texto Página de Cadastro</label>
										<input type="text" class="form-control input-sm" name="TXT_BANNERCAD" id="TXT_BANNERCAD" maxlength="300" value="<?php echo $txt_bannercad; ?>">
									</div>
								</div>
							</div>

							<div class="push10"></div>

							<div class="row">

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Data Inicial</label>

										<div class="input-group date datePicker" id="DAT_INI_GRP">
											<input type='text' class="form-control input-sm data" name="DAT_MIN" id="DAT_MIN" value="<?php echo fnFormatDate($dat_min); ?>" required />
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
										</div>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Data Final</label>

										<div class="input-group date datePicker" id="DAT_FIM_GRP">
											<input type='text' class="form-control input-sm data" name="DAT_MAX" id="DAT_MAX" value="<?php echo fnFormatDate($dat_max); ?>" required />
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>

										</div>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Personas Participantes da Campanha</label>

										<select data-placeholder="Selecione as personas desejadas" name="COD_PERSONA[]" id="COD_PERSONA" multiple="multiple" class="chosen-select-deselect requiredChk" style="width:100%;" tabindex="1" required>
											<?php
											//se venda em tempo real
											//$sql = "select * from persona where cod_empresa = ".$cod_empresa." order by DES_PERSONA  ";	

											// $arrayAutorizado = explode(",", $_SESSION["SYS_COD_UNIVEND"]);

											if (fnAutMaster($_SESSION["SYS_COD_TPUSUARIO"], $_SESSION["SYS_COD_EMPRESA"]) == '1') {

												$andUnidade = "";
											} else {

												$andUnidade = "AND PERSONA.COD_UNIVEND IN($_SESSION[SYS_COD_UNIVEND])";
											}

											$sql = "SELECT IFNULL(PERSONAREGRA.COD_REGRA,0) AS TEM_REGRA, 
											PERSONA.* 
											FROM PERSONA 
											LEFT JOIN PERSONAREGRA ON PERSONAREGRA.COD_PERSONA = PERSONA.COD_PERSONA
											WHERE COD_EMPRESA = $cod_empresa 
											$andUnidade
											GROUP BY COD_PERSONA
											ORDER BY DES_PERSONA ";

											$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

											while ($qrListaPersonas = mysqli_fetch_assoc($arrayQuery)) {

												if ($qrListaPersonas['LOG_ATIVO'] == "N") {
													$desabilitado = "disabled";
													$desabilitadoOnTxt = " (Off)";
												} else {
													$desabilitado = "";
													$desabilitadoOnTxt = "";
												}

												if ($qrListaPersonas['TEM_REGRA'] == "0") {
													$desabilitadoRg = " disabled";
													$desabilitadoRgTxt = " (s/ regra)";
												} else {
													$desabilitadoRg = "";
													$desabilitadoRgTxt = "";
												}

												echo "
												<option value='" . $qrListaPersonas['COD_PERSONA'] . "' " . $desabilitado . $desabilitadoRg . ">" . ucfirst($qrListaPersonas['DES_PERSONA']) . $desabilitadoRgTxt . $desabilitadoOnTxt . "</option> 
												";
											}


											?>
										</select>
										<span class="help-block"><?php echo $msgPersona; ?></span>
										<div class="help-block with-errors"></div>
									</div>

								</div>

							</div>

							<?php if ($des_chavecamp != "" && $des_chavecamp != "#") { ?>



							<?php } ?>

						</fieldset>

						<div class="push10"></div>

						<?php

						// fnEscreve($tip_campanha);
						//se bloco de cash back
						if ($tip_campanha == 13 || $tip_campanha == 22 || $tip_campanha == 23) {
							$mask = "money";
							$titulo = "Percentual";
							$txt = "Qual o percentual do valor da compra será revertido em vantagens?";
							$col = "6";
							$colTxt = "9";

							if ($tip_campanha == 22 || $tip_campanha == 23) {
								$mask = "int";
								$titulo = "Valor";
								$col = "10";
								$colTxt = "5";
								if ($tip_credito == 13) {
									$txt_cred = "créditos";
								} else {
									$txt_cred = "pontos";
								}
								$txt = "Informe o valor de $txt_cred a serem ganhos no cadastro:";
							}
						}

						?>

						<fieldset>
							<legend><?= $titulo ?> da Campanha </legend>

							<div class="row">

								<div class="push25"></div>

								<div class="col-md-<?= $colTxt ?>" style="margin:0; padding: 0 0 0 15px;">
									<div class="push20"></div>
									<h5><?= $txt ?></h5>
								</div>

								<div class="col-md-3">
									<div class="col-md-9" style="margin:0; padding: 0;">
										<div class="form-group">
											<label for="inputName" class="control-label required">&nbsp;</label>
											<input type="text" class="form-control text-center input-sm <?= $mask ?>" name="PCT_VANTAGEM" id="PCT_VANTAGEM" maxlength="6" value="<?php echo $pct_vantagem; ?>" data-error="Campo obrigatório" required>
											<div class="help-block with-errors"></div>
										</div>
									</div>
									<?php if ($tip_campanha != 22 && $tip_campanha != 23) { ?>
										<span style="margin:0; padding: 27px 0 0 3px; font-size: 18px;" class="col-md-2 pull-left">%<span>
											<?php } else { ?>
												<span style="margin:0; padding: 27px 0 0 3px; font-size: 16px;" class="col-md-2 pull-left"><?= $txt_cred ?><span>
													<?php } ?>
								</div>

								<?php if ($tip_campanha == 22 || $tip_campanha == 23) { ?>


									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Validade dos <?= $txt_cred ?></label>
											<input type="text" class="form-control text-center input-sm int" name="QTD_VANTAGEM" id="QTD_VANTAGEM" maxlength="3" value="<?php echo $qtd_vantagem; ?>" data-error="Campo obrigatório" required>
											<div class="help-block with-errors">Em dias</div>
										</div>
									</div>



								<?php } else { ?>
									<input type="hidden" name="QTD_VANTAGEM" id="QTD_VANTAGEM" value="1">
								<?php } ?>

							</div>


							<input type="hidden" class="money" name="CONTA_FAIXA" id="CONTA_FAIXA" maxlength="6" value="0" data-error="Campo obrigatório">
							<input type="hidden" name="COD_VANTAGE" id="COD_VANTAGE" value="1">
							<input type="hidden" name="QTD_RESULTADO" id="QTD_RESULTADO" value="1">

							<div class="push10"></div>

						</fieldset>

						<div class="push10"></div>
						<hr>
						<div class="form-group text-right col-lg-12">

							<button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
							<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
							<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>
							<button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fal fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>
							<?php if ($contemRegistro == "S") { ?>
								<!-- <button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Atualizar Cadastro</button> -->
							<?php } else { ?>
								<!-- <button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button> -->
							<?php } ?>

						</div>

						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
						<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
						<input type="hidden" name="COD_REGISTR" id="COD_REGISTR" value="<?php echo $cod_registr; ?>">
						<input type="hidden" name="COD_FILTROS" id="COD_FILTROS" value="<?php echo $cod_filtros; ?>">

						<div class="push5"></div>

					</form>

					<div class="push50"></div>
				</div>

				<div class="push50"></div>

				<div class="col-lg-12">

					<div class="no-more-tables">

						<form name="formLista">

							<table class="table table-bordered table-striped table-hover tableSorter">
								<thead>
									<tr>
										<th class="{ sorter: false }" width="40"></th>
										<th>Código</th>
										<th>Chave Campanha</th>
										<th>Início - Fim</th>
										<th class="text-right">Valor</th>
										<th class='text-center'>Validade</th>
										<th class='text-center'>Banner Principal</th>
										<th class='text-center'>Banner Cadastro</th>
										<th class='text-center'>Banner Login</th>
										<th class="{ sorter: false }"></th>
									</tr>
								</thead>
								<tbody>

									<?php

									$sql = "SELECT * FROM CAMPANHA_HOTSITE WHERE COD_CAMPANHA = $cod_campanha AND COD_EMPRESA = $cod_empresa";
									$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

									$count = 0;
									while ($qrCampanha = mysqli_fetch_assoc($arrayQuery)) {
										// echo "<pre>";
										// print_r($qrCampanha);
										// echo "</pre>";
										$count++;

										$chk_bannermain = "<span class='fal fa-times text-danger'></span>";
										$chk_bannercad = "<span class='fal fa-times text-danger'></span>";
										$chk_bannerlog = "<span class='fal fa-times text-danger'></span>";

										if ($qrCampanha['IMG_BANNERMAIN'] != "") {
											$chk_bannermain = "<span class='fal fa-check text-success'></span>";
										}

										if ($qrCampanha['IMG_BANNERCAD'] != "") {
											$chk_bannercad = "<span class='fal fa-check text-success'></span>";
										}

										if ($qrCampanha['IMG_BANNERLOG'] != "") {
											$chk_bannerlog = "<span class='fal fa-check text-success'></span>";
										}

										$linkCode2 = "https://" . $des_dominio . $extensaoDominio . "/" . $qrCampanha['DES_CHAVECAMP'];

									?>
										<tr>
											<td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(<?= $count ?>)'></th>
											<td><?= $qrCampanha['COD_REGISTR'] ?></td>
											<td><?= $qrCampanha['DES_CHAVECAMP'] ?></td>
											<td><small><?= fnDataShort($qrCampanha['DAT_MIN']) ?> - <?= fnDataShort($qrCampanha['DAT_MAX']) ?></small></td>
											<td class='text-right'><?= fnValor($qrCampanha['PCT_VANTAGEM'], 2) ?></td>
											<td class='text-center'><?= fnValor($qrCampanha['QTD_VANTAGEM'], 0) ?></td>
											<td class='text-center'><?= $chk_bannermain ?></td>
											<td class='text-center'><?= $chk_bannercad ?></td>
											<td class='text-center'><?= $chk_bannerlog ?></td>
											<td class="text-center">
												<small>
													<div class="btn-group dropdown dropleft">
														<button type="button" class="btn btn-info btn-xs dropdown-toggle transparency" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
															ações &nbsp;
															<span class="fas fa-caret-down"></span>
														</button>
														<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
															<li><a href="javascript:void(0)" class="addBox" data-title="QrCode Hotsite" onclick='geraQRCode("<?= $linkCode2 ?>")'>Visualizar QrCode</a></li>

														</ul>
													</div>
												</small>
											</td>
										</tr>
										<input type='hidden' id='ret_COD_REGISTR_<?= $count ?>' value='<?= $qrCampanha['COD_REGISTR'] ?>'>
										<input type='hidden' id='ret_COD_PERSONA_<?= $count ?>' value='<?= $qrCampanha['COD_PERSONA'] ?>'>
										<input type='hidden' id='ret_LOG_CTRLEXT_<?= $count ?>' value='<?= $qrCampanha['LOG_CTRLEXT'] ?>'>
										<input type='hidden' id='ret_COD_UNIVEND_<?= $count ?>' value='<?= $qrCampanha['COD_UNIVEND_PREF'] ?>'>
										<input type='hidden' id='ret_DES_CHAVECAMP_<?= $count ?>' value='<?= $qrCampanha['DES_CHAVECAMP'] ?>'>
										<input type='hidden' id='ret_COD_FILTROS_<?= $count ?>' value='<?= $qrCampanha['COD_FILTROS'] ?>'>

										<input type='hidden' id='ret_IMG_BANNERMAIN_<?= $count ?>' value='<?= $qrCampanha['IMG_BANNERMAIN'] ?>'>
										<input type='hidden' id='ret_BANNERMAIN_<?= $count ?>' value='<?= fnBase64DecodeImg($qrCampanha['IMG_BANNERMAIN']) ?>'>
										<input type='hidden' id='ret_IMG_BANNERLOG_<?= $count ?>' value='<?= $qrCampanha['IMG_BANNERLOG'] ?>'>
										<input type='hidden' id='ret_BANNERLOG_<?= $count ?>' value='<?= fnBase64DecodeImg($qrCampanha['IMG_BANNERLOG']) ?>'>
										<input type='hidden' id='ret_IMG_BANNERCAD_<?= $count ?>' value='<?= $qrCampanha['IMG_BANNERCAD'] ?>'>
										<input type='hidden' id='ret_BANNERCAD_<?= $count ?>' value='<?= fnBase64DecodeImg($qrCampanha['IMG_BANNERCAD']) ?>'>
										<input type='hidden' id='ret_TXT_BANNERMAIN_<?= $count ?>' value='<?= $qrCampanha['TXT_BANNERMAIN'] ?>'>
										<input type='hidden' id='ret_TXT_BANNERCAD_<?= $count ?>' value='<?= $qrCampanha['TXT_BANNERCAD'] ?>'>
										<input type='hidden' id='ret_DAT_MIN_<?= $count ?>' value='<?= fnDataShort($qrCampanha['DAT_MIN']) ?>'>
										<input type='hidden' id='ret_DAT_MAX_<?= $count ?>' value='<?= fnDataShort($qrCampanha['DAT_MAX']) ?>'>
										<input type='hidden' id='ret_PCT_VANTAGEM_<?= $count ?>' value='<?= fnValor($qrCampanha['PCT_VANTAGEM'], 2) ?>'>
										<input type='hidden' id='ret_QTD_VANTAGEM_<?= $count ?>' value='<?= $qrCampanha['QTD_VANTAGEM'] ?>'>
									<?php
									}

									?>

								</tbody>
							</table>

						</form>

					</div>

				</div>

				<div class="push"></div>

			</div>
		</div>
		<!-- fim Portlet -->
	</div>

</div>

<div class="push20"></div>

<!-- modal -->
<div class="modal fade" id="popModal" tabindex='-1'>
	<div class="modal-dialog" style="">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"></h4>
			</div>
			<div class="modal-body">
				<div class="push100"></div>
				<div class="push100"></div>
				<div class="row">

					<div class="col-md-6 col-md-offset-3">
						<center>

							<div class="push20"></div>

							<div id="qrcodeCanvas"></div>
							<div id="qrcodeCanvas_save" style="display:none;"></div>

							<div class="push10"></div>

						</center>

					</div>

				</div>

				<div class="row">

					<div class="col-md-6 col-md-offset-3">
						<center>
							<div class="push5"></div>
							<h5>QrCode acesso Hotsite</h5>
							<div class="push20"></div>
							<a href="javascript:void(0)" class="btn btn-info" id="saveQr"><span class="fal fa-save"></span>&nbsp;Salvar imagem</a>
						</center>
					</div>

				</div>
				<div class="push100"></div>
				<div class="push100"></div>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script type="text/javascript" src="js/jquery-qrcode-master/src/jquery.qrcode.js"></script>
<script type="text/javascript" src="js/jquery-qrcode-master/src/qrcode.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />

<script type="text/javascript">
	$(document).ready(function() {

		$('.datePicker').datetimepicker({
			format: 'DD/MM/YYYY',
			minDate: "<?= $dat_ini ?>",
			maxDate: "<?= $dat_fim ?>",
		}).on('changeDate', function(e) {
			$(this).datetimepicker('hide');
		});

		// geraQRCode();

		$("#saveQr").click(function() {
			this.href = $('#qrcodeCanvas_save canvas')[0].toDataURL(); // Change here
			this.download = 'qrCode_' + "<?= $nome ?>" + '.jpg';
		});

	});

	function geraQRCode(linkCode) {
		$("#qrcodeCanvas").html("");
		jQuery('#qrcodeCanvas').qrcode({
			text: linkCode,
			width: 300,
			height: 300
		});
		$("#qrcodeCanvas_save").html("");
		jQuery('#qrcodeCanvas_save').qrcode({
			text: linkCode,
			width: 500,
			height: 500
		});

	}


	$(document).ready(function() {
		$('#COD_PERFIL').change(function() {
			var codTipoFiltro = $('#COD_PERFIL option:selected').parent().attr('id');
			var codFiltro = $('#COD_PERFIL').val();

			$('#COD_FILTROS').val(codFiltro + ',' + codTipoFiltro);
		});
	});

	$('.upload').on('click', function(e) {
		var idField = 'arqUpload_' + $(this).attr('idinput');
		var typeFile = $(this).attr('extensao');

		$.dialog({
			title: 'Arquivo',
			content: '' +
				'<form method = "POST" enctype = "multipart/form-data">' +
				'<input id="' + idField + '" type="file" name="image" style="margin-bottom: 20px;" />' +
				'<div class="progress" style="display: none">' +
				'<div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width:0%;">' +
				'   <span style="position: absolute; display: block; width: 100%; color:#2c3e50;">12</span></div>' +
				'</div>' +
				'<a type="button" id="btnUploadFile" class="btn btn-primary btn-sm" style="font-weight: bold" onClick="uploadFile(\'' + idField + '\', \'' + typeFile + '\')">UPLOAD</a>' +
				'</form>'
		});
	});

	$('.upload2').on('click', function(e) {
		var idField = 'arqUpload_' + $(this).attr('idinput');
		var typeFile = $(this).attr('extensao');

		$.dialog({
			title: 'Arquivo',
			content: '' +
				'<form method = "POST" enctype = "multipart/form-data">' +
				'<input id="' + idField + '" type="file" name="image" style="margin-bottom: 20px;" />' +
				'<div class="progress" style="display: none">' +
				'<div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width:0%;">' +
				'   <span style="position: absolute; display: block; width: 100%; color:#2c3e50;">12</span></div>' +
				'</div>' +
				'<a type="button" id="btnUploadFile" class="btn btn-primary btn-sm" style="font-weight: bold" onClick="uploadFile(\'' + idField + '\', \'' + typeFile + '\')">UPLOAD</a>' +
				'</form>'
		});
	});

	$('.upload3').on('click', function(e) {
		var idField = 'arqUpload_' + $(this).attr('idinput');
		var typeFile = $(this).attr('extensao');

		$.dialog({
			title: 'Arquivo',
			content: '' +
				'<form method = "POST" enctype = "multipart/form-data">' +
				'<input id="' + idField + '" type="file" name="image" style="margin-bottom: 20px;" />' +
				'<div class="progress" style="display: none">' +
				'<div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width:0%;">' +
				'   <span style="position: absolute; display: block; width: 100%; color:#2c3e50;">12</span></div>' +
				'</div>' +
				'<a type="button" id="btnUploadFile" class="btn btn-primary btn-sm" style="font-weight: bold" onClick="uploadFile(\'' + idField + '\', \'' + typeFile + '\')">UPLOAD</a>' +
				'</form>'
		});
	});

	function uploadFile(idField, typeFile) {
		var formData = new FormData();
		var nomeArquivo = $('#' + idField)[0].files[0]['name'];

		formData.append('arquivo', $('#' + idField)[0].files[0]);
		formData.append('diretorio', '../media/clientes');
		formData.append('diretorioAdicional', '');
		formData.append('id', <?php echo $cod_empresa ?>);
		formData.append('typeFile', typeFile);

		$('.progress').show();
		$.ajax({
			xhr: function() {
				var xhr = new window.XMLHttpRequest();
				$('#btnUploadFile').addClass('disabled');
				xhr.upload.addEventListener("progress", function(evt) {
					if (evt.lengthComputable) {
						var percentComplete = evt.loaded / evt.total;
						percentComplete = parseInt(percentComplete * 100);
						if (percentComplete !== 100) {
							$('.progress-bar').css('width', percentComplete + "%");
							$('.progress-bar > span').html(percentComplete + "%");
						}
					}
				}, false);
				return xhr;
			},
			url: '../uploads/uploaddoc.php',
			type: 'POST',
			data: formData,
			processData: false, // tell jQuery not to process the data
			contentType: false, // tell jQuery not to set contentType
			success: function(data) {
				$('.jconfirm-open').fadeOut(300, function() {
					$(this).remove();
				});

				var data = JSON.parse(data);

				if (data.success) {
					$('#' + idField.replace("arqUpload_", "")).val(data.nome_arquivo);
					$('#' + idField.replace("arqUpload_IMG_", "")).val(nomeArquivo);
					$.alert({
						title: "Mensagem",
						content: "Upload feito com sucesso",
						type: 'green'
					});

				} else {
					$.alert({
						title: "Erro ao efetuar o upload",
						content: data,
						type: 'red'
					});
				}
			}
		});
	}

	function retornaForm(index) {
		$("#formulario #COD_REGISTR").val($("#ret_COD_REGISTR_" + index).val());
		$("#formulario #COD_UNIVEND").val($("#ret_COD_UNIVEND_" + index).val()).trigger("chosen:updated");
		$("#formulario #DES_CHAVECAMP").val($("#ret_DES_CHAVECAMP_" + index).val());
		$("#formulario #IMG_BANNERMAIN").val($("#ret_IMG_BANNERMAIN_" + index).val());
		$("#formulario #IMG_BANNERCAD").val($("#ret_IMG_BANNERCAD_" + index).val());
		$("#formulario #IMG_BANNERLOG").val($("#ret_IMG_BANNERLOG_" + index).val());
		$("#formulario #BANNERMAIN").val($("#ret_BANNERMAIN_" + index).val());
		$("#formulario #BANNERCAD").val($("#ret_BANNERCAD_" + index).val());
		$("#formulario #BANNERLOG").val($("#ret_BANNERLOG_" + index).val());
		$("#formulario #TXT_BANNERMAIN").val($("#ret_TXT_BANNERMAIN_" + index).val());
		$("#formulario #TXT_BANNERCAD").val($("#ret_TXT_BANNERCAD_" + index).val());
		$("#formulario #DAT_MIN").val($("#ret_DAT_MIN_" + index).val());
		$("#formulario #DAT_MAX").val($("#ret_DAT_MAX_" + index).val());
		$("#formulario #PCT_VANTAGEM").val($("#ret_PCT_VANTAGEM_" + index).val());
		$("#formulario #QTD_VANTAGEM").val($("#ret_QTD_VANTAGEM_" + index).val());

		var ret_perfil = $("#ret_COD_FILTROS_" + index).val();
		var perfil = ret_perfil.split(',')[0];
		console.log(ret_perfil);

		$("#formulario #COD_PERFIL").val(perfil).trigger("chosen:updated");

		//retorno combo multiplo - lojas
		$("#formulario #COD_PERSONA").val('').trigger("chosen:updated");
		var sistemasUni = $("#ret_COD_PERSONA_" + index).val();
		var sistemasUniArr = sistemasUni.split(',');
		//opções multiplas
		for (var i = 0; i < sistemasUniArr.length; i++) {
			$("#formulario #COD_PERSONA option[value=" + sistemasUniArr[i] + "]").prop("selected", "true");
		}
		$("#formulario #COD_PERSONA").trigger("chosen:updated");


		if ($("#ret_LOG_CTRLEXT_" + index).val() == 'S') {
			$('#formulario #LOG_CTRLEXT').prop('checked', true);
		} else {
			$('#formulario #LOG_CTRLEXT').prop('checked', false);
		}

		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
	}
</script>