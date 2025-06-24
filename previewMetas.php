<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$hashLocal = "";
$msgRetorno = "";
$msgTipo = "";
$hHabilitado = "";
$hashForm = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$sqlConfig = "";
$arrayConfig = [];
$qrConfig = "";
$totem = "";
$abaMetas = "";
$diasemana = "";
$campo_parimpar = "";
$queryTurnos = "";
$turnos = "";
$cod_turno = "";
$turno = "";
$linha = "";
$ARRAY_UNIDADE1 = [];
$ARRAY_UNIDADE = [];
$NOM_ARRAY_UNIDADE = [];
$k = "";
$unidade = "";
$qrBusca = "";
$lbl = "";
$tipoCalc = "";
$disp_metaprod = "";
$disp_metadest = "";
$disp_alertmin = "";
$disp_fideliz = "";
$totalAbast1 = 0;
$totalAbast2 = 0;
$totalAbast3 = 0;
$totalAditiv1 = 0;
$totalAditiv2 = 0;
$totalAditiv3 = 0;
$total20Lit1 = 0;
$total20Lit2 = 0;
$total20Lit3 = 0;
$totalFidel1 = 0;
$totalFidel2 = 0;
$totalFidel3 = 0;
$arrayQueryUni = [];
$qrListaUniVendas = "";
$nom_fantasi = "";
$top = "";
$tipoUsuario = "";
$sql_meta = "";
$col_metaprod = "";
$col_metadest = "";
$col_alertmin = "";
$col_fideliz = "";
$meta = "";
$qrLista = "";
$qtd = 0;
$qrListaUsuario = "";
$nom_usuario = "";
$cod_usuario = "";
$desc_turno = "";
$qrUser = "";
$log_ativo = "";
$val_metaprod = "";
$val_metaprod_vl = "";
$val_metadest = "";
$val_metadest_vl = "";
$val_alertmin = "";
$val_alertmin_vl = "";
$qtd_fideliz = 0;
$qtd_fideliz_vl = 0;
$mtAbast = "";
$vlAbast = "";
$saldoAbast = "";
$txtAbast = "";
$icoAbast = "";
$mtAditiv = "";
$vlAditiv = "";
$saldoAditiv = "";
$txtAditiv = "";
$icoAditiv = "";
$mt20Lit = "";
$vl20Lit = "";
$saldo20Lit = "";
$txt20Lit = "";
$ico20Lit = "";
$mtFidel = "";
$vlFidel = "";
$saldoFidel = "";
$txtFidel = "";
$icoFidel = "";



$hashLocal = mt_rand();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode(@$_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;


		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		if ($opcao != '') {


			//mensagem de retorno
			switch ($opcao) {
				case 'CAD':
					$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					break;
				case 'ALT':
					$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";
					break;
				case 'EXC':
					$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";
					break;
					break;
			}
			$msgTipo = 'alert-success';
		}
	}
}

if (is_numeric(fnLimpacampo(fnDecode(@$_GET['idU'])))) {
	//busca dados da empresa
	$cod_univend = fnDecode(@$_GET['idU']);
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

$sqlConfig = "SELECT * FROM COMPARATIVO_METAS WHERE COD_EMPRESA = $cod_empresa";
$arrayConfig = mysqli_query(connTemp($cod_empresa, ""), $sqlConfig);
$qrConfig = mysqli_fetch_assoc($arrayConfig);

//fnMostraForm();


?>

<div class="push30"></div>

<div class="row">

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">
			<?php if (!@$totem) { ?>
				<div class="portlet-title">
					<div class="caption">
						<i class="fal fa-terminal"></i>
						<span class="text-primary"> <?php echo $NomePg; ?></span>
					</div>

					<?php
					include "atalhosPortlet.php";
					?>

				</div>
			<?php } ?>
			<div class="portlet-body">

				<?php
				if (!@$totem) {
					$abaMetas = 1305;
					include "abasUsuariosMetas.php";
				}
				?>

				<div class="push50"></div>

				<div class="login-form">

					<div class="col-lg-12">

						<div class="no-more-tables">

							<style>
								.membership-pricing-table {
									max-width: 1020px;
									margin: auto;
								}

								.membership-pricing-table table .icon-no,
								.membership-pricing-table table .icon-yes {
									font-size: 22px
								}

								.membership-pricing-table table .icon-no {
									color: #a93717
								}

								.membership-pricing-table table .icon-yes {
									color: #209e61
								}

								.membership-pricing-table table .plan-header {
									text-align: center;
									border: 1px solid #e2e2e2;
									padding: 15px 10px 15px 10px;
								}

								.membership-pricing-table table .plan-header-free {
									/*background-color: #415264;*/
									background-color: #B3B6B7;
									color: #000
								}

								.membership-pricing-table table .plan-header-blue {
									color: #fff;
									background-color: #61a1d1;
									border-color: #3989c6
								}

								.membership-pricing-table table .plan-header-purple {
									color: #fff;
									background-color: #61a1d1;
									border-color: #3989c6
								}

								.membership-pricing-table table .plan-header-standard {
									color: #fff;
									background-color: #ff9317;
									border-color: #e37900
								}

								.membership-pricing-table table td {
									text-align: center;
									padding: 10px 15px;
									/*background-color: #fafafa;*/
									background-color: #fff;
									font-size: 20px;
									-webkit-box-shadow: 0 1px 0 #fff inset;
									box-shadow: 0 1px 0 #fff inset
								}

								.membership-pricing-table table,
								.membership-pricing-table table td {
									border: 2px solid #ebebeb
								}

								.membership-pricing-table table tr td:first-child {
									background-color: transparent;
									text-align: left;
								}

								.membership-pricing-table table tr td:nth-child(5) {
									background-color: #FFF
								}

								.membership-pricing-table table tr:first-child td,
								.membership-pricing-table table tr:nth-child(2) td {
									-webkit-box-shadow: none;
									box-shadow: none
								}

								.membership-pricing-table table tr:first-child th:first-child {
									border-top-color: transparent;
									border-left-color: transparent;
									border-right-color: #e2e2e2
								}

								.membership-pricing-table table tr:first-child th .pricing-plan-name {
									font-size: 18px;
									padding: 0 20px 0 20px;
								}

								.membership-pricing-table table tr:first-child th .pricing-plan-price {
									line-height: 35px
								}

								.membership-pricing-table table tr:first-child th .pricing-plan-price>sup {
									font-size: 45%
								}

								.membership-pricing-table table tr:first-child th .pricing-plan-price>span {
									font-size: 30%
								}

								.membership-pricing-table table tr:first-child th .pricing-plan-period {
									font-size: 15px;
								}

								.membership-pricing-table table .header-plan-inner {
									position: relative
								}

								.membership-pricing-table table .recommended-plan-ribbon {
									box-sizing: content-box;
									background-color: #dc3b5d;
									color: #FFF;
									position: absolute;
									padding: 3px 6px;
									font-size: 11px !important;
									font-weight: 500;
									left: -6px;
									top: -22px;
									z-index: 99;
									width: 100%;
									-webkit-box-shadow: 0 -1px #c2284c inset;
									box-shadow: 0 -1px #c2284c inset;
									text-shadow: 0 -1px #c2284c
								}

								.membership-pricing-table table .recommended-plan-ribbon:before {
									border: solid;
									border-color: #c2284c transparent;
									border-width: 6px 0 0 6px;
									bottom: -5px;
									content: "";
									left: 0;
									position: absolute;
									z-index: 90
								}

								.membership-pricing-table table .recommended-plan-ribbon:after {
									border: solid;
									border-color: #c2284c transparent;
									border-width: 6px 6px 0 0;
									bottom: -5px;
									content: "";
									right: 0;
									position: absolute;
									z-index: 90
								}

								.membership-pricing-table table .plan-head {
									box-sizing: content-box;
									background-color: #ff9c00;
									border: 1px solid #cf7300;
									position: absolute;
									top: -33px;
									left: -1px;
									height: 30px;
									width: 100%;
									border-bottom: none
								}

								.f10 {
									font-size: 10px;
									font-weight: normal !important;
								}

								.f12 {
									font-size: 12px;
									font-weight: normal !important;
								}

								.f13 {
									font-size: 13px;
									font-weight: normal !important;
								}

								.f14 {
									font-size: 14px;
									font-weight: normal !important;
								}

								.f14b {
									font-size: 14px;
									font-weight: bold !important;
								}

								.f16 {
									font-size: 16px;
									font-weight: normal !important;
								}

								.f16b {
									font-size: 16px;
									font-weight: bold !important;
								}

								.f18 {
									font-size: 18px !important;
								}

								.f19 {
									font-size: 19px !important;
								}

								.f21 {
									font-size: 21px;
									font-weight: normal !important;
								}

								.f21b {
									font-size: 21px;
									font-weight: bold !important;
								}

								.f26 {
									font-size: 26px;
									font-weight: normal !important;
								}

								.f26b {
									font-size: 26px;
									font-weight: bold !important;
								}

								.f30b {
									font-size: 30px;
									font-weight: bold !important;
								}
							</style>

							<div style="<?= (@$totem ? "padding:50px;" : "") ?>">
								<?php
								$diasemana = date('w') + 1;
								$campo_parimpar = ((date("d") % 2 == 1) ? "LOG_DIAIMPAR" : "LOG_DIAPAR");
								$sql = "SELECT * FROM TURNOSTRABALHO
			WHERE COD_EMPRESA = $cod_empresa
				AND COD_EXCLUSA=0
				AND LOG_SEMANA_$diasemana='S'
				AND $campo_parimpar='S'
				AND (
					(HOR_SAIDA >= HOR_ENTRADA AND HOR_ENTRADA <= '" . date("H:i:s") . "' AND HOR_SAIDA >= '" . date("H:i:s") . "')
					OR
					(HOR_SAIDA < HOR_ENTRADA AND (HOR_ENTRADA <= '" . date("H:i:s") . "' OR HOR_SAIDA >= '" . date("H:i:s") . "'))
				)
			ORDER BY NOM_TURNO";
								//echo $sql;
								$queryTurnos = mysqli_query($connAdm->connAdm(), $sql);
								$turnos = "";
								$cod_turno = "0";
								$turno = array();
								while ($linha = mysqli_fetch_assoc($queryTurnos)) {
									$turnos .= "<span class='badge badge-info'>" . $linha["NOM_TURNO"] . "</span>&nbsp;";
									$cod_turno .= "," . $linha["COD_TURNO"];
									$turno[$linha["COD_TURNO"]] = $linha["NOM_TURNO"];
								}




								if (!$totem) {
									$ARRAY_UNIDADE1 = array(
										'sql' => "select COD_UNIVEND,cod_empresa,nom_fantasi,NOM_UNIVEND from unidadevenda where cod_empresa=$cod_empresa AND LOG_ESTATUS = 'S' AND LOG_ATIVOMETA='S'",
										'cod_empresa' => $cod_empresa,
										'conntadm' => $connAdm->connAdm(),
										'IN' => 'N',
										'nomecampo' => '',
										'conntemp' => '',
										'SQLIN' => ""
									);
									$ARRAY_UNIDADE = fnUnivend($ARRAY_UNIDADE1);

									$NOM_ARRAY_UNIDADE = (array_search(@$cod_univend, array_column($ARRAY_UNIDADE, 'COD_UNIVEND')));
								?>
									<div class="login-form">

										<fieldset>
											<legend>Dados Gerais</legend>
											<div class="row">

												<div class="col-md-3">
													<div class="form-group">
														<label for="inputName" class="control-label required">Empresa</label>
														<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa ?>">
														<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">
													</div>
												</div>

												<div class="col-md-3">
													<div class="form-group">
														<label for="inputName" class="control-label required">Unidade</label>
														<select data-placeholder="Selecione um Tipo" name="COD_UNIVEND" id="COD_UNIVEND" class="chosen-select-deselect requiredChk" required onChange="window.location.href='action.do?mod=<?= @$_GET["mod"] ?>&id=<?= @$_GET["id"] ?>&idU='+this.value">
															<option value=""></option>
															<?php

															foreach ($ARRAY_UNIDADE as $k => $unidade) {
																$cod_univend = (@$cod_univend == "" ? $unidade["COD_UNIVEND"] : $cod_univend);
																echo "<option value=\"" . fnEncode($unidade["COD_UNIVEND"]) . "\" " . ($unidade["COD_UNIVEND"] == $cod_univend ? "selected" : "") . ">" . $unidade["nom_fantasi"] . "</option>";
															}
															?>
														</select>
													</div>
												</div>

											</div>

										</fieldset>

									</div>
									<div class="push30"></div>
								<?php
								}

								//if ($totem){
								$sql = "SELECT * FROM UNIDADEVENDA WHERE COD_EMPRESA = $cod_empresa" .
									(@$cod_univend != "" ? " AND COD_UNIVEND IN ($cod_univend)" : "") .
									" AND LOG_ESTATUS = 'S'
			AND LOG_ATIVOMETA='S'
			AND (COD_EXCLUSA IS NULL OR COD_EXCLUSA = 0)
			LIMIT 1";
								$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
								$qrBusca = mysqli_fetch_assoc($arrayQuery);

								echo "<div class='f30b text-center'>CONTROLE DE METAS - " . $qrBusca["NOM_FANTASI"] . "</div>";
								echo "<div style='clear:both;height:0px;'></div>";
								echo "<div class='text-center'>";
								echo $turnos;
								echo "</div>";
								echo "<div style='clear:both;height:30px;'></div>";
								//}

								?>

								<style>
									.c1 {
										background-color: #81D4FA;
										!important;
									}

									.c2 {
										background-color: #4FC3F7;
										!important;
									}

									.c3 {
										background-color: #03A9F4;
										!important;
									}

									.c4 {
										background-color: #FFA726;
										!important;
									}
								</style>

								<?php
								$sql = "SELECT * FROM CONTROLE_METAS_DESC WHERE COD_EMPRESA = $cod_empresa";

								$arrayQuery = mysqli_query(connTemp($cod_empresa, ""), $sql);
								$lbl = array();
								while ($linha = mysqli_fetch_assoc($arrayQuery)) {
									$lbl[@$linha["COD_DESCRICAO"]] = @$linha["NOM_DESCRICAO"];
									$tipoCalc[@$linha["COD_DESCRICAO"]] = @$linha["DES_COMBO"];
									if ($linha["COD_DESCRICAO"] == "VAL_METAPROD") {
										$disp_metaprod = @$linha["LOG_STATUS"];
									} else if ($linha["COD_DESCRICAO"] == "VAL_METADEST") {
										$disp_metadest = $linha["LOG_STATUS"];
									} else if ($linha["COD_DESCRICAO"] == "VAL_ALERTMIN") {
										$disp_alertmin = $linha["LOG_STATUS"];
									} else {
										$disp_fideliz = $linha["LOG_STATUS"];
									}
								}

								$lbl["VAL_METAPROD"] = (@$lbl["VAL_METAPROD"] <> "" ? $lbl["VAL_METAPROD"] : "ABASTECIMENTOS");
								$lbl["VAL_METADEST"] = (@$lbl["VAL_METADEST"] <> "" ? $lbl["VAL_METADEST"] : "% ADITIVADA");
								$lbl["VAL_ALERTMIN"] = (@$lbl["VAL_ALERTMIN"] <> "" ? $lbl["VAL_ALERTMIN"] : "+ 20 LITROS");
								$lbl["QTD_FIDELIZ"] = (@$lbl["QTD_FIDELIZ"] <> "" ? $lbl["QTD_FIDELIZ"] : "FIDELIDADE");

								$tipoCalc["VAL_METAPROD"] = (@$tipoCalc["VAL_METAPROD"] <> "" ? $tipoCalc["VAL_METAPROD"] : "4");
								$tipoCalc["VAL_METADEST"] = (@$tipoCalc["VAL_METADEST"] <> "" ? $tipoCalc["VAL_METADEST"] : "5");
								$tipoCalc["VAL_ALERTMIN"] = (@$tipoCalc["VAL_ALERTMIN"] <> "" ? $tipoCalc["VAL_ALERTMIN"] : "6");
								$tipoCalc["QTD_FIDELIZ"] = (@$tipoCalc["QTD_FIDELIZ"] <> "" ? $tipoCalc["QTD_FIDELIZ"] : "4");
								?>

								<div class="no-table-responsive text-center">
									<div class="membership-pricing-table" style="max-width:100% !important;width:100% !important;">
										<table style="width:100% !important;">
											<tbody>

												<tr>
													<th style="position:sticky;top:-10px;background:#FFF;"></th>

													<?php if ($disp_metaprod == "S") { ?>

														<th colspan="3" width="20%" class="plan-header c1" style="position:sticky;top:-10px;">
															<div class="pricing-plan-name"><?= $lbl["VAL_METAPROD"] ?></div>
														</th>

													<?php } ?>

													<?php if ($disp_metadest == "S") { ?>

														<th colspan="3" width="20%" class="plan-header c2" style="position:sticky;top:-10px;">
															<div class="pricing-plan-name"><?= $lbl["VAL_METADEST"] ?></div>
														</th>

													<?php } ?>

													<?php if ($disp_alertmin == "S") { ?>

														<th colspan="3" width="20%" class="plan-header c3" style="position:sticky;top:-10px;">
															<div class="pricing-plan-name"><?= $lbl["VAL_ALERTMIN"] ?></div>
														</th>

													<?php } ?>

													<?php if ($disp_fideliz == "S") { ?>

														<th colspan="3" width="20%" class="plan-header c4" style="position:sticky;top:-10px;">
															<div class="header-plan-inner">
																<div class="pricing-plan-name"><?= $lbl["QTD_FIDELIZ"] ?></div>
															</div>
														</th>

													<?php } ?>

												</tr>

												<?php
												$totalAbast1 = 0;
												$totalAbast2 = 0;
												$totalAbast3 = 0;
												$totalAditiv1 = 0;
												$totalAditiv2 = 0;
												$totalAditiv3 = 0;
												$total20Lit1 = 0;
												$total20Lit2 = 0;
												$total20Lit3 = 0;
												$totalFidel1 = 0;
												$totalFidel2 = 0;
												$totalFidel3 = 0;

												$sql = "SELECT * FROM UNIDADEVENDA WHERE COD_EMPRESA = $cod_empresa" .
													(@$cod_univend != "" ? " AND COD_UNIVEND IN ($cod_univend)" : "") .
													" AND LOG_ESTATUS = 'S'
						AND LOG_ATIVOMETA='S'
						AND (COD_EXCLUSA IS NULL OR COD_EXCLUSA = 0) ORDER BY TRIM(NOM_FANTASI)";
												$arrayQueryUni = mysqli_query($connAdm->connAdm(), $sql);
												//fnEscreve($sql);
												$count = 0;
												while ($qrListaUniVendas = mysqli_fetch_assoc($arrayQueryUni)) {
													$cod_univend = $qrListaUniVendas['COD_UNIVEND'];
													$nom_fantasi = $qrListaUniVendas['NOM_FANTASI'];

													$top = 42;
												?>

													<tr>
														<th class="plan-header plan-header-free f21b" style="position:sticky;top:<?= $top ?>px;">Frentistas</th>

														<?php if ($disp_metaprod == "S") { ?>

															<!-- abastecimentos -->
															<th class="plan-header c1" style="position:sticky;top:<?= $top ?>px;">
																<div class="pricing-plan-period f21b">Feito</div>
															</th>

															<th class="plan-header c1" style="position:sticky;top:<?= $top ?>px;">
																<div class="pricing-plan-period f21b">Meta</div>
															</th>

															<th class="plan-header c1" style="position:sticky;top:<?= $top ?>px;">
																<div class="pricing-plan-period f21b">Saldo</div>
															</th>

														<?php } ?>

														<?php if ($disp_metadest == "S") { ?>

															<!-- aditivada -->
															<th class="plan-header c2" style="position:sticky;top:<?= $top ?>px;">
																<div class="pricing-plan-period f21b">Feito</div>
															</th>

															<th class="plan-header c2" style="position:sticky;top:<?= $top ?>px;">
																<div class="pricing-plan-period f21b">Meta</div>
															</th>

															<th class="plan-header c2" style="position:sticky;top:<?= $top ?>px;">
																<div class="pricing-plan-period f21b">Saldo</div>
															</th>

														<?php } ?>

														<?php if ($disp_alertmin == "S") { ?>

															<!-- 20 litros -->
															<th class="plan-header c3" style="position:sticky;top:<?= $top ?>px;">
																<div class="pricing-plan-period f21b">Feito</div>
															</th>

															<th class="plan-header c3" style="position:sticky;top:<?= $top ?>px;">
																<div class="pricing-plan-period f21b">Meta</div>
															</th>

															<th class="plan-header c3" style="position:sticky;top:<?= $top ?>px;">
																<div class="pricing-plan-period f21b">Saldo</div>
															</th>

														<?php } ?>

														<?php if ($disp_fideliz == "S") { ?>

															<!-- fidelidade -->
															<th class="plan-header c4" style="position:sticky;top:<?= $top ?>px;">
																<div class="pricing-plan-period f21b">Feito</div>
															</th>

															<th class="plan-header c4" style="position:sticky;top:<?= $top ?>px;">
																<div class="pricing-plan-period f21b">Meta</div>
															</th>

															<th class="plan-header c4" style="position:sticky;top:<?= $top ?>px;">
																<div class="pricing-plan-period f21b">Saldo</div>
															</th>

														<?php } ?>

													</tr>


													<?php
													$tipoUsuario = "8,7,11,2";

													/*
					$sql_meta = "SELECT
									controle_metas.COD_EMPRESA,
									controle_metas.COD_ATENDENTE,
									controle_metas.LOG_ATIVO,
									MIN(controle_metas.VAL_METAPROD) VAL_METAPROD,
									COUNT(vendas.COD_VENDA) VAL_METAPROD_VL,
									MIN(controle_metas.VAL_METADEST) VAL_METADEST,
									
									SUM(IF(produto_meta.log_destaque='S',itemvenda.VAL_TOTITEM,0)) / 
										(SELECT SUM(val_totprodu) FROM vendas v
										WHERE v.cod_vendedor=vendas.COD_VENDEDOR AND 
											  DATE(v.DAT_CADASTR)=DATE(NOW())
										AND v.COD_EMPRESA=vendas.COD_EMPRESA) * 100 VAL_METADEST_VL,

									MIN(controle_metas.VAL_ALERTMIN) VAL_ALERTMIN,

									SUM(IF(itemvenda.QTD_PRODUTO > 20,1,0)) VAL_ALERTMIN_VL,

									MIN(controle_metas.QTD_FIDELIZ) QTD_FIDELIZ,
									(SELECT SUM(IF(COD_AVULSO=2,1,0))/COUNT(0) FROM vendas v
										WHERE v.cod_vendedor=vendas.COD_VENDEDOR AND 
											  DATE(v.DAT_CADASTR)=DATE(NOW())
										AND v.COD_EMPRESA=vendas.COD_EMPRESA)*100 QTD_FIDELIZ_VL
								FROM controle_metas
									LEFT JOIN vendas ON (vendas.COD_VENDEDOR=controle_metas.COD_ATENDENTE AND vendas.COD_EMPRESA=controle_metas.COD_EMPRESA) AND DATE(vendas.DAT_CADASTR)=DATE(NOW())
									LEFT JOIN itemvenda ON (itemvenda.COD_VENDA=vendas.COD_VENDA AND itemvenda.cod_empresa=controle_metas.COD_EMPRESA)
									LEFT JOIN produto_meta ON (produto_meta.COD_EMPRESA=itemvenda.COD_EMPRESA AND produto_meta.COD_PRODUTO=itemvenda.COD_PRODUTO)
								WHERE controle_metas.COD_EMPRESA=$cod_empresa
								GROUP BY controle_metas.COD_ATENDENTE";
*/

													/*$sql_meta = "SELECT u.NOM_USUARIO,
									controle_metas.COD_EMPRESA,
								   controle_metas.COD_ATENDENTE,
								   controle_metas.LOG_ATIVO,
								   Min(controle_metas.val_metaprod)                      VAL_METAPROD,
								   COUNT(distinct vendas.cod_venda)                               VAL_METAPROD_VL,
								   Min(controle_metas.val_metadest)                      VAL_METADEST,
								   Sum(IF(produto_meta.log_destaque = 'S', itemvenda.val_totitem, 0)) /
								   (SELECT Sum(val_totprodu)
												FROM
											   vendas v
											   WHERE
											   v.cod_vendedor = vendas.cod_vendedor
											   AND DATE(v.dat_cadastr) = DATE(Now())
											   AND v.cod_empresa = vendas.cod_empresa) * 100   VAL_METADEST_VL,

								   Min(controle_metas.val_alertmin)                      VAL_ALERTMIN,
								   Sum(IF(itemvenda.qtd_produto > 20, 1, 0))             VAL_ALERTMIN_VL,
								   Min(controle_metas.qtd_fideliz)                       QTD_FIDELIZ,
								   (SELECT Sum(IF(cod_avulso = 2, 1, 0)) / COUNT(0)
									FROM   vendas v
									WHERE  v.cod_vendedor = vendas.cod_vendedor
										   AND DATE(v.dat_cadastr) = DATE(Now())
										   AND v.cod_empresa = vendas.cod_empresa) * 100 QTD_FIDELIZ_VL
							FROM   controle_metas
								   LEFT JOIN vendas on CASE 
														  WHEN  vendas.cod_vendedor = controle_metas.cod_atendente 
															 THEN  controle_metas.cod_atendente 
														  WHEN  vendas.COD_ATENDENTE = controle_metas.cod_atendente 
															 THEN  controle_metas.cod_atendente   				         
															 END
											 AND DATE(vendas.dat_cadastr) = DATE(Now())
								   LEFT JOIN itemvenda
										  ON ( itemvenda.cod_venda = vendas.cod_venda
											   AND itemvenda.cod_empresa = controle_metas.cod_empresa )
								   LEFT JOIN produto_meta
										  ON ( produto_meta.cod_empresa = itemvenda.cod_empresa
											   AND produto_meta.cod_produto = itemvenda.cod_produto )
							   INNER JOIN usuarios u ON    controle_metas.cod_atendente=u.COD_USUARIO              
							WHERE  controle_metas.cod_empresa = $cod_empresa
							 GROUP  BY controle_metas.cod_atendente";*/

													switch ($tipoCalc["VAL_METAPROD"]) {

														default: //4
															$col_metaprod = "SUM(QTD_PRODUTO) VAL_METAPROD_VL,";
															break;

														case '5':
															$col_metaprod = "SUM(VAL_TOTITEM) VAL_METAPROD_VL,";
															break;

														case '6':
															$col_metaprod = "COUNT(DISTINCT COD_VENDA) VAL_METAPROD_VL,";
															break;
													}

													switch ($tipoCalc["VAL_METADEST"]) {
														case '4':
															$col_metadest = "SUM(CASE WHEN MT.LOG_DESTAQUE='S' THEN QTD_PRODUTO ELSE 0 END) VAL_METADEST_VL,";
															break;

														default: //5
															$col_metadest = "SUM(CASE WHEN MT.LOG_DESTAQUE='S' THEN VAL_TOTITEM ELSE 0 END) / SUM(VAL_TOTPRODU)* 100 VAL_METADEST_VL,";
															break;

														case '6':
															$col_metadest = "COUNT(DISTINCT CASE WHEN MT.LOG_DESTAQUE='S' THEN COD_VENDA ELSE NULL END) VAL_METADEST_VL,";
															break;
													}

													switch ($tipoCalc["VAL_ALERTMIN"]) {
														case '4':
															$col_alertmin = "SUM(CASE WHEN QTD_PRODUTO >= 20 THEN QTD_PRODUTO ELSE 0 END) VAL_METAPROD_VL,";
															break;

														case '5':
															$col_alertmin = "SUM(CASE WHEN QTD_PRODUTO >= 20 THEN VAL_TOTITEM ELSE 0 END) VAL_METAPROD_VL,";
															break;

														default: //6
															$col_alertmin = "COUNT(DISTINCT CASE WHEN QTD_PRODUTO >= 20 THEN COD_VENDA ELSE NULL END) VAL_ALERTMIN_VL,";
															break;
													}

													switch ($tipoCalc["QTD_FIDELIZ"]) {

														default: //4
															$col_fideliz = "SUM(CASE WHEN COD_AVULSO = 2 THEN QTD_PRODUTO ELSE 0 END) / COUNT(0) * 100 QTD_FIDELIZ_VL";
															break;

														case '5':
															$col_fideliz = "SUM(CASE WHEN COD_AVULSO = 2 THEN VAL_TOTITEM ELSE 0 END) / COUNT(0) * 100 QTD_FIDELIZ_VL";
															break;

														case '6':
															$col_fideliz = "COUNT(DISTINCT CASE WHEN COD_AVULSO = 2 THEN COD_VENDA ELSE NULL END) / COUNT(0) * 100 QTD_FIDELIZ_VL";
															break;
													}

													$sql_meta = "SELECT 
										US.NOM_USUARIO,
										MET.COD_EMPRESA,
										MET.COD_ATENDENTE,
										MET.LOG_ATIVO,
										MIN(distinct MET.VAL_METAPROD) VAL_METAPROD,
										'0' VAL_METAPROD_VL,
										MIN(DISTINCT MET.VAL_METADEST) VAL_METADEST,										
										'0.00'  VAL_METADEST_VL,
										Min(MET.val_alertmin) VAL_ALERTMIN,
										'0' VAL_ALERTMIN_VL,
										Min(MET.qtd_fideliz)  QTD_FIDELIZ,
										'0' QTD_FIDELIZ_VL

										FROM controle_metas MET
										INNER JOIN usuarios US ON US.COD_USUARIO=MET.COD_ATENDENTE 
												   AND US.COD_TURNO  IN ($cod_turno)
										WHERE MET.COD_EMPRESA	=$cod_empresa													
										GROUP BY MET.COD_ATENDENTE

								UNION ALL

								SELECT  
										U.NOM_USUARIO,
										CTRL.COD_EMPRESA,
										CTRL.COD_ATENDENTE,
										CTRL.LOG_ATIVO,
										MIN(distinct CTRL.VAL_METAPROD) VAL_METAPROD,
										$col_metaprod
										MIN(DISTINCT CTRL.VAL_METADEST) VAL_METADEST,										
										$col_metadest
										Min(CTRL.val_alertmin) VAL_ALERTMIN,
										$col_alertmin
										Min(CTRL.qtd_fideliz)  QTD_FIDELIZ,
										$col_fideliz
										FROM (

													SELECT
														(SELECT 
															CONCAT_WS(',',VEN.COD_ATENDENTE ,VEN.COD_VENDEDOR, GROUP_CONCAT(DISTINCT COD_ATENDENTE SEPARATOR ',' )) 
															 FROM controle_metas WHERE COD_EMPRESA=$cod_empresa AND COD_ATENDENTE IN (SELECT 
																																	COD_USUARIO 
																															   FROM usuarios 
																															 WHERE cod_empresa= $cod_empresa
																															 AND cod_turno  IN ($cod_turno))
															) BUSCA_ATENDENTE,
															  IT.VAL_TOTITEM,
															  VEN.VAL_TOTPRODU,
															 IT.QTD_PRODUTO,
															 VEN.COD_AVULSO,
															VEN.COD_VENDA,																											      
															IT.COD_PRODUTO CODPRODUTO,
															  COD_ATENDENTE
													FROM VENDAS VEN
													 inner JOIN ITEMVENDA IT ON IT.COD_VENDA = VEN.COD_VENDA 
																		  AND IT.COD_EMPRESA=VEN.COD_EMPRESA
																		  AND IT.COD_CLIENTE=VEN.COD_CLIENTE
																				 AND IT.COD_EMPRESA= $cod_empresa	
												
													WHERE 
													  VEN.COD_EMPRESA= $cod_empresa
													 AND DATE(VEN.DAT_CADASTR_WS) = CURDATE()
													 AND VEN.COD_STATUSCRED IN (0,1,2,3,4,5,7,8,9)												
											
													 
												) TEMPVENDAMETAS

										 LEFT JOIN CONTROLE_METAS CTRL ON CTRL.COD_ATENDENTE IN (BUSCA_ATENDENTE)                                         
																							AND CTRL.COD_EMPRESA= $cod_empresa
										LEFT JOIN PRODUTO_META MT ON MT.COD_PRODUTO = CODPRODUTO AND CTRL.COD_EMPRESA= $cod_empresa												
										INNER JOIN USUARIOS U ON U.COD_USUARIO=CTRL.COD_ATENDENTE 	AND U.COD_EMPRESA= $cod_empresa
								WHERE 
								CTRL.COD_ATENDENTE IN (BUSCA_ATENDENTE)
								GROUP BY CTRL.COD_ATENDENTE";

													//	fnEscreve($sql_meta);
													$arrayQuery = mysqli_query(connTemp($cod_empresa, ""), $sql_meta);
													$meta = array();
													while ($qrLista = mysqli_fetch_assoc($arrayQuery)) {
														$meta[$qrLista["COD_ATENDENTE"]] = $qrLista;
													}
													//echo "<pre>";
													//print_r($meta);
													//echo "</pre>";

													$sql = "select * from usuarios 
					left join tipousuario on usuarios.COD_TPUSUARIO = tipousuario.COD_TPUSUARIO
					where usuarios.COD_EMPRESA = $cod_empresa 
					AND usuarios.COD_TPUSUARIO IN ($tipoUsuario)
					AND usuarios.COD_UNIVEND IN ($cod_univend)
					AND (usuarios.COD_TURNO IN ($cod_turno) OR usuarios.COD_TURNO = 0 OR IFNULL(usuarios.COD_TURNO,'') = '')
					AND usuarios.DAT_EXCLUSA is null order by usuarios.NOM_USUARIO";
													//fnEscreve($sql);

													$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

													$qtd = 0;
													while ($qrListaUsuario = mysqli_fetch_assoc($arrayQuery)) {
														$nom_usuario = $qrListaUsuario['NOM_USUARIO'];
														$cod_usuario = $qrListaUsuario['COD_USUARIO'];
														$cod_turno = $qrListaUsuario['COD_TURNO'];
														$desc_turno = "";
														if ($cod_turno <> "") {
															//	$desc_turno = "<span class='badge badge-info'>".$turno[$cod_turno]."</span>";
														}
														$qrUser = @$meta[$cod_usuario];
														$log_ativo = @$qrUser['LOG_ATIVO'];
														if ($log_ativo != "S") {
															continue;
														}
														$val_metaprod = "0" . @$qrUser['VAL_METAPROD'];
														$val_metaprod_vl = "0" . @$qrUser['VAL_METAPROD_VL'];
														$val_metadest = "0" . @$qrUser['VAL_METADEST'];
														$val_metadest_vl = "0" . @$qrUser['VAL_METADEST_VL'];
														$val_alertmin = "0" . @$qrUser['VAL_ALERTMIN'];
														$val_alertmin_vl = "0" . @$qrUser['VAL_ALERTMIN_VL'];
														$qtd_fideliz = "0" . @$qrUser['QTD_FIDELIZ'];
														$qtd_fideliz_vl = "0" . @$qrUser['QTD_FIDELIZ_VL'];

														$mtAbast = $val_metaprod;
														$vlAbast = $val_metaprod_vl;
														$saldoAbast = $vlAbast - $mtAbast;
														if ($saldoAbast < 0) {
															$txtAbast = "text-danger";
															$icoAbast = "<i class='fas fa-arrow-alt-down'></i>";
														} else {
															$txtAbast = "text-success";
															$icoAbast = "<i class='far fa-thumbs-up'></i>";
														}
														$totalAbast1 = $totalAbast1 + $vlAbast;
														$totalAbast2 = $totalAbast2 + $mtAbast;
														$totalAbast3 = $totalAbast3 + $saldoAbast;

														$mtAditiv = $val_metadest;
														$vlAditiv = $val_metadest_vl;
														$saldoAditiv = $vlAditiv - $mtAditiv;
														if ($saldoAditiv < 0) {
															$txtAditiv = "text-danger";
															$icoAditiv = "<i class='fas fa-arrow-alt-down'></i>";
														} else {
															$txtAditiv = "text-success";
															$icoAditiv = "<i class='far fa-thumbs-up'></i>";
														}
														$totalAditiv1 = $totalAditiv1 + $vlAditiv;
														$totalAditiv2 = $totalAditiv2 + $mtAditiv;
														$totalAditiv3 = $totalAditiv3 + $saldoAditiv;

														$mt20Lit = $val_alertmin;
														$vl20Lit = $val_alertmin_vl;
														$saldo20Lit = $vl20Lit - $mt20Lit;
														if ($saldo20Lit < 0) {
															$txt20Lit = "text-danger";
															$ico20Lit = "<i class='fas fa-arrow-alt-down'></i>";
														} else {
															$txt20Lit = "text-success";
															$ico20Lit = "<i class='far fa-thumbs-up'></i>";
														}
														$total20Lit1 = $total20Lit1 + $vl20Lit;
														$total20Lit2 = $total20Lit2 + $mt20Lit;
														$total20Lit3 = $total20Lit3 + $saldo20Lit;

														$mtFidel = $qtd_fideliz;
														$vlFidel = 	$qtd_fideliz_vl;
														$saldoFidel = $vlFidel - $mtFidel;
														if ($saldoFidel < 0) {
															$txtFidel = "text-danger";
															$icoFidel = "<i class='fas fa-arrow-alt-down'></i>";
														} else {
															$txtFidel = "text-success";
															$icoFidel = "<i class='far fa-thumbs-up'></i>";
														}
														$totalFidel1 = $totalFidel1 + $vlFidel;
														$totalFidel2 = $totalFidel2 + $mtFidel;
														$totalFidel3 = $totalFidel3 + $saldoFidel;


														/*
						.c1Off {background-color: #f4fbfe; !important;}
						.c2Off {background-color: #eafafd; !important;}
						.c3Off {background-color: #d9e6f1; !important;}
						.c4Off {background-color: #fdefe6; !important;}
						*/

													?>

														<tr>
															<td class="f21b text-left" style="padding: 15px 10px;"><?= $nom_usuario; ?><?= $desc_turno ?></td>

															<?php if ($disp_metaprod == "S") { ?>

																<td style="background-color: #fff;" class="text-nowrap f18 <?php echo $txtAbast; ?>"><?php echo fnValor($vlAbast, 0); ?></td>
																<td style="background-color: #fff;" class="text-nowrap f18"><b><?php echo fnValor($mtAbast, 0); ?></b></td>
																<td style="background-color: #fff;" class="text-nowrap f18 <?php echo $txtAbast; ?>">
																	<?php echo $icoAbast; ?> <?php echo fnValor($saldoAbast, 0); ?>
																	<br><span class="f12"><?= fnValor($vlAbast * 100 / $mtAbast, 2) ?>%</span>
																</td>

															<?php } ?>

															<?php if ($disp_metadest == "S") { ?>

																<td style="background-color: #f6fbfe;" class="text-nowrap f18 <?php echo $txtAditiv; ?>"><?php echo fnValor($vlAditiv, 0); ?><small>%</small></td>
																<td style="background-color: #f6fbfe;" class="text-nowrap f18"><b><?php echo fnValor($mtAditiv, 0); ?><small>%</small></b></td>
																<td style="background-color: #f6fbfe;" class="text-nowrap f18 <?php echo $txtAditiv; ?>">
																	<?php echo $icoAditiv; ?> <?php echo fnValor($saldoAditiv, 0); ?><small>%</small>
																	<br><span class="f12"><?= fnValor($vlAditiv * 100 / $mtAditiv, 2) ?>%</span>
																</td>

															<?php } ?>

															<?php if ($disp_alertmin == "S") { ?>

																<td style="background-color: #e0f4fd;" class="text-nowrap f18 <?php echo $txt20Lit; ?>"><?php echo fnValor($vl20Lit, 0); ?></td>
																<td style="background-color: #e0f4fd;" class="text-nowrap f18"><b><?php echo fnValor($mt20Lit, 0); ?></b></td>
																<td style="background-color: #e0f4fd;" class="text-nowrap f18 <?php echo $txt20Lit; ?>">
																	<?php echo $ico20Lit; ?> <?php echo fnValor($saldo20Lit, 0); ?>
																	<br><span class="f12"><?= fnValor($vl20Lit * 100 / $mt20Lit, 2) ?>%</span>
																</td>

															<?php } ?>

															<?php if ($disp_fideliz == "S") { ?>

																<td style="background-color: #fdefe6;" class="text-nowrap f18 <?php echo $txtFidel; ?>"><?php echo fnValor($vlFidel, 0); ?><small>%</small></td>
																<td style="background-color: #fdefe6;" class="text-nowrap f18"><b><?php echo fnValor($mtFidel, 0); ?><small>%</small></b></td>
																<td style="background-color: #fdefe6;" class="text-nowrap f18 <?php echo $txtFidel; ?>">
																	<?php echo $icoFidel; ?> <?php echo fnValor($saldoFidel, 0); ?><small>%</small>
																	<br><span class="f12"><?= fnValor($vlFidel * 100 / $mtFidel, 2) ?>%</span>
																</td>

															<?php } ?>

														</tr>

												<?php
														$qtd++;
													}
												}
												?>


												<tr>
													<th style="position:sticky;bottom:0px;" class="plan-header plan-header-free f21b">TOTAL</th>

													<?php if ($disp_metaprod == "S") { ?>

														<!-- abastecimentos -->
														<th style="position:sticky;bottom:0px;" class="plan-header c1 f21b">
															<div class="pricing-plan-period"><?php echo fnValor($totalAbast1, 0); ?></div>
														</th>

														<th style="position:sticky;bottom:0px;" class="plan-header c1 f21b">
															<div class="pricing-plan-period"><?php echo fnValor($totalAbast2, 0); ?></div>
														</th>

														<th style="position:sticky;bottom:0px;" class="plan-header c1 f21b">
															<div class="pricing-plan-period"><?php echo fnValor($totalAbast3, 0); ?></div>
														</th>

													<?php } ?>

													<?php if ($disp_metadest == "S") { ?>

														<!-- aditivada -->
														<th style="position:sticky;bottom:0px;" class="plan-header c2 f21b">
															<div class="pricing-plan-period"><?php echo fnValor($totalAditiv1 / $qtd, 2); ?><small>%</small></div>
														</th>

														<th style="position:sticky;bottom:0px;" class="plan-header c2 f21b">
															<div class="pricing-plan-period"><?php echo fnValor($totalAditiv2 / $qtd, 2); ?><small>%</small></div>
														</th>

														<th style="position:sticky;bottom:0px;" class="plan-header c2 f21b">
															<div class="pricing-plan-period"><?php echo fnValor($totalAditiv3 / $qtd, 2); ?><small>%</small></div>
														</th>

													<?php } ?>

													<?php if ($disp_alertmin == "S") { ?>

														<!-- 20 litros -->
														<th style="position:sticky;bottom:0px;" class="plan-header c3 f21b">
															<div class="pricing-plan-period"><?php echo fnValor($total20Lit1, 0); ?></div>
														</th>

														<th style="position:sticky;bottom:0px;" class="plan-header c3 f21b">
															<div class="pricing-plan-period"><?php echo fnValor($total20Lit2, 0); ?></div>
														</th>

														<th style="position:sticky;bottom:0px;" class="plan-header c3 f21b">
															<div class="pricing-plan-period"><?php echo fnValor($total20Lit3, 0); ?></div>
														</th>

													<?php } ?>

													<?php if ($disp_fideliz == "S") { ?>

														<!-- fidelidade -->
														<th style="position:sticky;bottom:0px;" class="plan-header c4 f21b">
															<div class="pricing-plan-period"><?php echo fnValor($totalFidel1 / $qtd, 2); ?><small>%</small></div>
														</th>

														<th style="position:sticky;bottom:0px;" class="plan-header c4 f21b">
															<div class="pricing-plan-period"><?php echo fnValor($totalFidel2 / $qtd, 2); ?><small>%</small></div>
														</th>

														<th style="position:sticky;bottom:0px;" class="plan-header c4 f21b">
															<div class="pricing-plan-period"><?php echo fnValor($totalFidel3 / $qtd, 2); ?><small>%</small></div>
														</th>

													<?php } ?>

												</tr>

											</tbody>
										</table>
									</div>
								</div>
							</div>



						</div>

					</div>

					<div class="push100"></div>

				</div>

			</div>
		</div>
		<!-- fim Portlet -->
	</div>

</div>

<div class="push20"></div>

<script type="text/javascript">
	function retornaForm(index) {

		//$("#nomBusca").val($("#ret_NOM_EMPRESA_"+index).val());
		$('#formLista').attr('action', 'action.do?mod=<?php echo fnEncode(1302); ?>&id=<?php echo fnEncode($cod_empresa); ?>&idU=' + $("#ret_COD_UNIVEND_" + index).val());
		$('#formLista').submit();

	}

	<?php
	if (@$totem) {
	?>
		setInterval(function() {
			document.location.reload(true);
		}, 180000);
	<?php
	}
	?>
</script>