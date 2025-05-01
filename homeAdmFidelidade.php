<?php

//echo fnDebug('true');

// definir o numero de itens por pagina
$itens_por_pagina = 100;

// Página default
$pagina = 1;

$dias30 = "";
$dat_ini = "";
$dat_fim = "";
$dat_fim_ent = "";
$dat_ini_ent = "";
$cod_chamado = "";
$cod_externo = "";
$cod_empresa = "";
$nom_chamado = "";
$cod_tpsolicitacao = "";
$cod_status = null; // Ou algum valor padrão
$cod_status_exc = "10,6";
$cod_tipo_exc = "21";
$cod_integradora = "";
$cod_plataforma = "";
$cod_versaointegra = "";
$cod_prioridade = "";
$hashLocal = mt_rand();

//inicialização de variáveis
$hoje = fnFormatDate(date("Y-m-d"));
$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30 . '- 1 days')));
$cod_univend = "9999"; //todas revendas - default

$cod_usucada = $_SESSION['SYS_COD_USUARIO'];

$cod_usuario = $cod_usucada;
$cod_usures = $cod_usucada;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_empresa = fnLimpaCampoZero($_POST['COD_EMPRESA']);
		$cod_univend = $_POST['COD_UNIVEND'];
		$dat_ini = fnDataSql($_POST['DAT_INI']);
		$dat_fim = fnDataSql($_POST['DAT_FIM']);
		$cod_vendapdv = $_POST['COD_VENDAPDV'];




		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		if ($opcao != '') {
		}
	}
}

//busca dados url
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
	//busca dados da empresa
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
	$nom_empresa = "";
}

//inicialização das variáveis - default	
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
	$dat_ini = fnDataSql($dias30);
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
	$dat_fim = fnDataSql($hoje);
}
if (strlen($cod_univend) == 0) {
	$cod_univend = "9999";
}

//busca revendas do usuário
include "unidadesAutorizadas.php";


//fnEscreve($_SESSION["SYS_COD_HOME"]);
//fnEscreve($_SESSION["SYS_PAG_HOME"]);	

//fnMostraForm();	
//fnEscreve($dat_ini);
//fnEscreve($dat_fim);
//fnEscreve($cod_univendUsu);
//fnEscreve($qtd_univendUsu);
//fnEscreve($lojasAut);
//fnEscreve($usuReportAdm);
//fnEscreve($lojasReportAdm);

?>

<style>
	table a:not(.btn),
	.table a:not(.btn) {
		text-decoration: none;
	}

	table a:not(.btn):hover,
	.table a:not(.btn):hover {
		text-decoration: underline;
	}

	.rounded-shadow {
		-webkit-box-shadow: 0px 0px 7px 0px rgba(237, 237, 237, 1);
		-moz-box-shadow: 0px 0px 7px 0px rgba(237, 237, 237, 1);
		box-shadow: 0px 0px 7px 0px rgba(237, 237, 237, 1);
		border-radius: 4px 4px 4px 4px;
		-moz-border-radius: 4px 4px 4px 4px;
		-webkit-border-radius: 4px 4px 4px 4px;
		border: 0px solid #000000;
	}

	.table-small {
		height: 350px !important;
	}

	.badge {
		display: table;
		border-radius: 30px 30px 30px 30px;
		width: 26px;
		height: 26px;
		text-align: center;
		color: white;
		font-size: 11px;
		margin-right: auto;
		margin-left: auto;
	}

	.txtBadge {
		display: table-cell;
		vertical-align: middle;
	}

	.pitstop {
		background: #d98880;
		color: #FFF;
		padding: 1px 5px 2px 5px;
		border-radius: 3px;
	}

	.pitstop:hover {
		color: #FFF;
	}
</style>

<div class="push30"></div>

<div class="row" id="div_Report" style="display:none;">

	<div class="col-md-12">
		<!-- Portlet -->
		<div class="portlet portlet-bordered margin-bottom-30 rounded-shadow">
			<div class="portlet-title">
				<div class="caption">
					<i class="glyphicon glyphicon-calendar"></i>
					<span class="text-primary"> <?php echo $NomePg; ?></span>
				</div>

				<?php
				//$formBack = "1015";
				include "atalhosPortlet.php";
				?>

			</div>
			<div class="portlet-body">

				<?php if ($msgRetorno <> '') { ?>
					<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<?php echo $msgRetorno; ?>
					</div>
				<?php } ?>


				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">





					</form>

				</div>

			</div>
		</div>
		<!-- fim Portlet -->
	</div>
</div>

<?php

if ($dat_ini == "") {
	$ANDdatIni = " ";
} else {
	$ANDdatIni = "AND DATE_FORMAT(SC.DAT_CADASTR, '%Y-%m-%d') >= '$dat_ini' ";
}

if ($dat_ini_ent == date('Y-m-d')) {
	$ANDdatIniEnt = " ";
} else {
	$ANDdatIniEnt = "AND DATE_FORMAT(SC.DAT_ENTREGA, '%Y-%m-%d') >= '$dat_ini_ent'";
}

if ($dat_fim_ent == "") {
	$ANDdatFimEnt = " ";
} else {
	$ANDdatFimEnt = "AND DATE_FORMAT(SC.DAT_ENTREGA, '%Y-%m-%d') <= '$dat_fim_ent'";
}

if ($cod_externo == "") {
	$ANDcodExterno = " ";
} else {
	$ANDcodExterno = "AND SC.COD_EXTERNO LIKE '%$cod_externo%' ";
}

if ($cod_chamado == "") {
	$ANDcodChamado = " ";
} else {
	$ANDcodChamado = "AND SC.COD_CHAMADO = $cod_chamado ";
}

if ($cod_empresa == "") {
	$ANDcodEmpresa = " ";
} else {
	$ANDcodEmpresa = "AND SC.COD_EMPRESA = $cod_empresa ";
}

if ($nom_chamado == "") {
	$ANDnomChamado = " ";
} else {
	$ANDnomChamado = "AND SC.NOM_CHAMADO LIKE '%$nom_chamado%' ";
}

if ($cod_tpsolicitacao == "") {
	$ANDcodTipo = " ";
} else {
	$ANDcodTipo = "AND SC.COD_TPSOLICITACAO = $cod_tpsolicitacao ";
}

if ($cod_status == "") {
	$ANDcodStatus = "";
} else {
	$ANDcodStatus = "AND SC.COD_STATUS = $cod_status ";
}

if ($cod_status_exc == "0") {
	$ANDcodStatusExc = "";
} else {
	$ANDcodStatusExc = "AND SC.COD_STATUS NOT IN($cod_status_exc) ";
}

if ($cod_tipo_exc == "0") {
	$ANDcodTipoExc = "";
} else {
	$ANDcodTipoExc = "AND SC.COD_TPSOLICITACAO NOT IN($cod_tipo_exc) ";
}

if ($cod_integradora == "") {
	$ANDcodIntegradora = " ";
} else {
	$ANDcodIntegradora = "AND SC.COD_INTEGRADORA = $cod_integradora ";
}

if ($cod_plataforma == "") {
	$ANDcodPlataforma = " ";
} else {
	$ANDcodPlataforma = "AND SC.COD_PLATAFORMA = $cod_plataforma ";
}

if ($cod_versaointegra == "") {
	$ANDcodVersaointegra = " ";
} else {
	$ANDcodStatus = "AND SC.COD_VERSAOINTEGRA = $cod_versaointegra ";
}

if ($cod_prioridade == "") {
	$ANDcodPrioridade = " ";
} else {
	$ANDcodPrioridade = "AND SC.COD_PRIORIDADE = $cod_prioridade ";
}

if ($cod_usuario == "") {
	$ANDcodUsuario = " ";
} else {
	$ANDcodUsuario = "AND SC.COD_USUARIO = $cod_usuario ";
}



if ($cod_usuario != "" && $cod_usures != "" && $cod_usuario == $cod_usures) {
	$ANDcod_usures = "AND (SC.COD_USUARIO = $cod_usuario OR SC.COD_USURES = $cod_usures OR SC.COD_CONSULTORES IN($cod_usuario) OR SC.COD_USUARIOS_ENV IN($cod_usuario)) ";
	$ANDcodUsuario = "";
} else {
	$ANDcod_usures = "";
}

?>

<div class="row">

	<div class="col-md-6">

		<div class="portlet portlet-bordered margin-bottom-30 rounded-shadow">

			<div class="portlet-body">

				<div class="no-more-tables">

					<form name="formLista">

						<h4 style="margin-left: 5px;">Chamados mais antigos</h4>

						<table class="table table-bordered table-striped table-hover table-sm">

							<thead>
								<tr>
									<th><small>Chamado</small></th>
									<th><small>Empresa</small></th>
									<th><small>Status</small></th>
									<th><small>Dias aberto</small></th>
								</tr>
							</thead>

							<tbody id="relatorioConteudoRecentes">

								<?php



								$sqlSac = "SELECT SC.COD_CHAMADO, SC.COD_EMPRESA, SC.NOM_CHAMADO, SC.COD_EXTERNO,SC.COD_STATUS, 
												SC.DAT_CADASTR, SC.DAT_CHAMADO, SC.DAT_ENTREGA, SC.DAT_PROXINT, SC.DES_PREVISAO, SC.COD_USUARIO,
												SC.COD_USURES, SC.LOG_ADM, SP.DES_PLATAFORMA, ST.DES_TPSOLICITACAO, 
												
												SS.ABV_STATUS, SS.DES_COR AS COR_STATUS, SS.DES_ICONE AS ICO_STATUS,
												(SELECT MAX(SCM.DAT_CADASTRO) FROM SAC_COMENTARIO SCM WHERE SCM.COD_CHAMADO = SC.COD_CHAMADO) AS DAT_INTERAC
												FROM SAC_CHAMADOS SC 
												LEFT JOIN SAC_PLATAFORMA SP ON SP.COD_PLATAFORMA=SC.COD_PLATAFORMA
												LEFT JOIN SAC_TPSOLICITACAO ST ON ST.COD_TPSOLICITACAO=SC.COD_TPSOLICITACAO
												LEFT JOIN SAC_VERSAOINTEGRA SV ON SV.COD_VERSAOINTEGRA=SC.COD_VERSAOINTEGRA
												LEFT JOIN SAC_PRIORIDADE SPR ON SPR.COD_PRIORIDADE=SC.COD_PRIORIDADE
												LEFT JOIN SAC_STATUS SS ON SS.COD_STATUS=SC.COD_STATUS
												WHERE SC.COD_STATUS NOT IN($cod_status_exc)
												AND SC.COD_TPSOLICITACAO NOT IN($cod_tipo_exc)
												$ANDcodUsuario
												$ANDcod_usures
												$ANDcodStatus
												ORDER BY SC.COD_CHAMADO ASC limit 5
												";
								#fnEscreve($sqlSac);
								//SV.DES_VERSAOINTEGRA, SPR.DES_PRIORIDADE, SPR.DES_COR AS COR_PRIORIDADE, SPR.DES_ICONE AS ICO_PRIORIDADE,

								$arrayQuerySac = mysqli_query($connAdmSAC->connAdm(), $sqlSac);

								$count = 0;
								$adm = "";
								$entrega = "";
								while ($qrSac = mysqli_fetch_assoc($arrayQuerySac)) {

									if ($qrSac['LOG_ADM'] == 'S') {
										$adm = "<i class='fal fa-user-check shortCut' data-toggle='tooltip' data-placement='left' data-original-title='ti'></i>";
									} else {
										$adm = "<i class='fal fa-user-tie shortCut' data-toggle='tooltip' data-placement='left' data-original-title='cliente'></i>";
									}

									$count++;

									$sqlEmpresa = "SELECT NOM_FANTASI FROM EMPRESAS WHERE COD_EMPRESA = $qrSac[COD_EMPRESA]";
									$qrNomEmp = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $sqlEmpresa));


									$sqlUsuarios = "SELECT (SELECT NOM_USUARIO FROM USUARIOS WHERE COD_USUARIO = $qrSac[COD_USUARIO]) AS NOM_SOLICITANTE,
																(SELECT NOM_USUARIO FROM USUARIOS WHERE COD_USUARIO = $qrSac[COD_USURES]) AS NOM_RESPONSAVEL";
									$qrNomUsu = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $sqlUsuarios));
									//fnEscreve($sqlUsuarios);										  

									if ($qrSac['DAT_ENTREGA'] == "1969-12-31") {
										$entrega = "";
									} else {
										$entrega = fnDataShort($qrSac['DAT_ENTREGA']);
										if (fnDatasql($entrega) < fnDatasql($hoje)) {
											$entrega = "<span class='text-danger'><b>" . fnDataShort($qrSac['DAT_ENTREGA']) . "</b></span>";
										}
									}

									if ($qrSac['DAT_PROXINT'] == "1969-12-31") {
										$proxInt = "";
									} else {
										$proxInt = fnDataShort($qrSac['DAT_PROXINT']);
										if (fnDatasql($proxInt) < fnDatasql($hoje)) {
											$proxInt = "<span class='text-danger'><b>" . fnDataShort($qrSac['DAT_PROXINT']) . "</b></span>";
										}
									}

									if ($qrSac['DAT_INTERAC'] != "") {
										if (fnDatasql($qrSac['DAT_INTERAC']) == fnDatasql($hoje)) {
											$atualizado = "<b>Hoje</b>";
											$f = "f17";
										} else if (fnDatasql($qrSac['DAT_INTERAC']) == date('Y-m-d', strtotime(' -1 days'))) {
											$atualizado = "<b>Ontem</b>";
											$f = "f17";
										} else {
											$atualizado = fnDataFull($qrSac['DAT_INTERAC']);
											$f = "f14";
										}
									} else {
										$atualizado = "";
									}

									if ($qrSac['COD_STATUS'] == 12) {

										$difference = fnValor((abs(strtotime(date("Y-m-d H:i:s")) - strtotime($qrSac['DAT_CADASTR'])) / 3600), 0);

										if ($difference <= 12) {
											$corDiff = "label-success";
										} else if ($difference > 12 && $difference <= 24) {
											$corDiff = "label-warning";
										} else {
											$corDiff = "label-danger";
										}

										$badgeDias = "<span class='label-as-badge text-center " . $corDiff . "'><span class='txtBadge'>" . $difference . "</span></span>";
									} else {
										$badgeDias = "";
									}

									$diff_dias = fnDateDif($qrSac['DAT_CADASTR'], Date("Y-m-d"));
								?>

									<tr>
										<td>
											<small>
												<a href="action.php?mod=<?= fnEncode(1285); ?>&id=<?php echo fnEncode($qrSac['COD_EMPRESA']); ?>&idC=<?php echo fnEncode($qrSac['COD_CHAMADO']); ?>" target="_blank">#<?= $qrSac['COD_CHAMADO'] ?>&nbsp;
													<?= $qrSac['NOM_CHAMADO'] ?>
												</a>
											</small>
										</td>

										<td><small><?= isset($qrNomEmp['NOM_FANTASI']) ? $qrNomEmp['NOM_FANTASI'] : null ?></small></td>

										<td class="text-center">
											<small>
												<p class="label" style="background-color: <?php echo $qrSac['COR_STATUS'] ?>">
													<span class="<?php echo $qrSac['ICO_STATUS']; ?>" style="color: #FFF;"></span>
													&nbsp;<?php echo $qrSac['ABV_STATUS']; ?>
												</p>
												&nbsp;
												<?= $badgeDias ?>
											</small>

										</td>

										<td class="text-center <?= $f ?>"><span class="badge" style="background-color: #E74C3C"><span class="txtBadge"><?= $diff_dias ?></span></span></td>

									</tr>
								<?php
								}
								?>

							</tbody>

						</table>

					</form>

					<div class="push10"></div>

				</div>

			</div>
		</div>

	</div>

	<div class="col-md-6">

		<div class="portlet portlet-bordered margin-bottom-30 rounded-shadow">

			<div class="portlet-body">

				<div class="no-more-tables">

					<form name="formLista">

						<h4 style="margin-left: 5px;">Novos chamados</h4>

						<table class="table table-bordered table-striped table-hover table-sm">

							<thead>
								<tr>
									<th><small>Chamado</small></th>
									<th><small>Empresa</small></th>
									<th><small>Status</small></th>
									<th><small>Dt. de Criação</small></th>
								</tr>
							</thead>

							<tbody id="relatorioConteudoRecentes">

								<?php



								$sqlSac = "SELECT SC.COD_CHAMADO, SC.COD_EMPRESA, SC.NOM_CHAMADO, SC.COD_EXTERNO,SC.COD_STATUS,
												SC.DAT_CADASTR, SC.DAT_CHAMADO, SC.DAT_ENTREGA, SC.DAT_PROXINT, SC.DES_PREVISAO, SC.COD_USUARIO,
												SC.COD_USURES, SC.LOG_ADM, SP.DES_PLATAFORMA, ST.DES_TPSOLICITACAO, 
												SV.DES_VERSAOINTEGRA, SPR.DES_PRIORIDADE, SPR.DES_COR AS COR_PRIORIDADE, SPR.DES_ICONE AS ICO_PRIORIDADE,
												SS.ABV_STATUS, SS.DES_COR AS COR_STATUS, SS.DES_ICONE AS ICO_STATUS,
												(SELECT MAX(SCM.DAT_CADASTRO) FROM SAC_COMENTARIO SCM WHERE SCM.COD_CHAMADO = SC.COD_CHAMADO) AS DAT_INTERAC
												FROM SAC_CHAMADOS SC 
												LEFT JOIN SAC_PLATAFORMA SP ON SP.COD_PLATAFORMA=SC.COD_PLATAFORMA
												LEFT JOIN SAC_TPSOLICITACAO ST ON ST.COD_TPSOLICITACAO=SC.COD_TPSOLICITACAO
												LEFT JOIN SAC_VERSAOINTEGRA SV ON SV.COD_VERSAOINTEGRA=SC.COD_VERSAOINTEGRA
												LEFT JOIN SAC_PRIORIDADE SPR ON SPR.COD_PRIORIDADE=SC.COD_PRIORIDADE
												LEFT JOIN SAC_STATUS SS ON SS.COD_STATUS=SC.COD_STATUS
												WHERE SC.COD_STATUS NOT IN($cod_status_exc)
												AND SC.COD_TPSOLICITACAO NOT IN($cod_tipo_exc)
												$ANDcodUsuario
												$ANDcod_usures
												$ANDcodStatus
												ORDER BY SC.COD_CHAMADO DESC limit 5
												";
								// fnEscreve($sqlSac);

								$arrayQuerySac = mysqli_query($connAdmSAC->connAdm(), $sqlSac);

								$count = 0;
								$adm = "";
								$entrega = "";
								while ($qrSac = mysqli_fetch_assoc($arrayQuerySac)) {

									if ($qrSac['LOG_ADM'] == 'S') {
										$adm = "<i class='fal fa-user-check shortCut' data-toggle='tooltip' data-placement='left' data-original-title='ti'></i>";
									} else {
										$adm = "<i class='fal fa-user-tie shortCut' data-toggle='tooltip' data-placement='left' data-original-title='cliente'></i>";
									}

									$count++;

									$sqlEmpresa = "SELECT NOM_FANTASI FROM EMPRESAS WHERE COD_EMPRESA = $qrSac[COD_EMPRESA]";
									$qrNomEmp = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $sqlEmpresa));

									$sqlUsuarios = "SELECT (SELECT NOM_USUARIO FROM USUARIOS WHERE COD_USUARIO = $qrSac[COD_USUARIO]) AS NOM_SOLICITANTE,
																(SELECT NOM_USUARIO FROM USUARIOS WHERE COD_USUARIO = $qrSac[COD_USURES]) AS NOM_RESPONSAVEL";
									$qrNomUsu = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $sqlUsuarios));
									//fnEscreve($sqlUsuarios);										  

									if ($qrSac['DAT_ENTREGA'] == "1969-12-31") {
										$entrega = "";
									} else {
										$entrega = fnDataShort($qrSac['DAT_ENTREGA']);
										if (fnDatasql($entrega) < fnDatasql($hoje)) {
											$entrega = "<span class='text-danger'><b>" . fnDataShort($qrSac['DAT_ENTREGA']) . "</b></span>";
										}
									}

									if ($qrSac['DAT_PROXINT'] == "1969-12-31") {
										$proxInt = "";
									} else {
										$proxInt = fnDataShort($qrSac['DAT_PROXINT']);
										if (fnDatasql($proxInt) < fnDatasql($hoje)) {
											$proxInt = "<span class='text-danger'><b>" . fnDataShort($qrSac['DAT_PROXINT']) . "</b></span>";
										}
									}

									if ($qrSac['DAT_INTERAC'] != "") {
										if (fnDatasql($qrSac['DAT_INTERAC']) == fnDatasql($hoje)) {
											$atualizado = "<b>Hoje</b>";
											$f = "f17";
										} else if (fnDatasql($qrSac['DAT_INTERAC']) == date('Y-m-d', strtotime(' -1 days'))) {
											$atualizado = "<b>Ontem</b>";
											$f = "f17";
										} else {
											$atualizado = fnDataFull($qrSac['DAT_INTERAC']);
											$f = "f14";
										}
									} else {
										$atualizado = "";
									}

									if ($qrSac['COD_STATUS'] == 12) {

										$difference = fnValor((abs(strtotime(date("Y-m-d H:i:s")) - strtotime($qrSac['DAT_CADASTR'])) / 3600), 0);

										if ($difference <= 12) {
											$corDiff = "label-success";
										} else if ($difference > 12 && $difference <= 24) {
											$corDiff = "label-warning";
										} else {
											$corDiff = "label-danger";
										}

										$badgeDias = "<span class='label-as-badge text-center " . $corDiff . "'><span class='txtBadge'>" . $difference . "</span></span>";
									} else {
										$badgeDias = "";
									}

									//$diff_dias = fnDateDif($qrSac['DAT_CADASTR'],Date("Y-m-d"));
									// fnEscreve(fnDatasql($qrSac['DAT_INTERAC']));
								?>

									<tr>
										<td>
											<small>
												<a href="action.php?mod=<?= fnEncode(1285); ?>&id=<?php echo fnEncode($qrSac['COD_EMPRESA']); ?>&idC=<?php echo fnEncode($qrSac['COD_CHAMADO']); ?>" target="_blank">#<?= $qrSac['COD_CHAMADO'] ?>&nbsp;
													<?= $qrSac['NOM_CHAMADO'] ?>
													<!-- <span class="fa fa-external-link-square"></span> -->
												</a>
											</small>
										</td>

										<td><small><?= isset($qrNomEmp['NOM_FANTASI']) ? $qrNomEmp['NOM_FANTASI'] : null ?></small></td>

										<td class="text-center">
											<div style="height: 0.5px;"></div>
											<small>
												<p class="label" style="background-color: <?php echo $qrSac['COR_STATUS'] ?>">
													<span class="<?php echo $qrSac['ICO_STATUS']; ?>" style="color: #FFF;"></span>
													&nbsp;<?php echo $qrSac['ABV_STATUS']; ?>
												</p>
												&nbsp;
												<?= $badgeDias ?>
											</small>

											<!-- <div><?= $badgeDias ?></div> -->
										</td>

										<td class="text-center f14"><small><?= fnDataShort($qrSac['DAT_CADASTR']) ?></small></td>

									</tr>
								<?php
								}
								?>

							</tbody>

						</table>

					</form>

					<div class="push10"></div>

				</div>

			</div>
		</div>

	</div>

</div>

<div class="row">

	<div class="col-md-12">

		<div class="portlet portlet-bordered margin-bottom-30 rounded-shadow">

			<div class="portlet-body">

				<div class="no-more-tables">

					<h4 style="margin-left: 5px;">Minha esteira</h4>
					<?php
					$dat_ini = "";
					$dat_fim = "";
					$cod_empresa = "";
					$cod_usures = "";
					$dat_ini_ent = date('Y-m-d');
					$dat_fim_ent = "";
					$cod_usuario_ordenac = $cod_usuario;
					$cod_usuario = "";
					$orderby = "SC.NUM_ORDENAC,SC.COD_CHAMADO DESC";
					$manutencao = false;
					include("listaChamados.php");
					?>

					<div class="push10"></div>

				</div>

			</div>
		</div>

	</div>

</div>

<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />

<script>
	//datas
	$(function() {

		var numPaginas = <?php echo $numPaginas; ?>;
		if (numPaginas != 0) {
			carregarPaginacao(numPaginas);
		}

		$('.datePicker').datetimepicker({
			format: 'DD/MM/YYYY',
			maxDate: 'now',
		}).on('changeDate', function(e) {
			$(this).datetimepicker('hide');
		});

		$("#DAT_INI_GRP").on("dp.change", function(e) {
			$('#DAT_FIM_GRP').data("DateTimePicker").minDate(e.date);
		});

		$("#DAT_FIM_GRP").on("dp.change", function(e) {
			$('#DAT_INI_GRP').data("DateTimePicker").maxDate(e.date);
		});


	});

	function reloadPage(idPage) {
		$.ajax({
			type: "POST",
			url: "ajxListaComunicacaoGeradaCompra.do?opcao=paginar&id=<?php echo fnEncode($cod_empresa); ?>&idPage=" + idPage + "&itens_por_pagina=<?php echo $itens_por_pagina; ?>",
			data: $('#formulario').serialize(),
			beforeSend: function() {
				$('#relatorioConteudo').html('<tr><td colspan="100"><div class="loading" style="width: 100%;"></div></tr></td>');
			},
			success: function(data) {
				$("#relatorioConteudo").html(data);
			},
			error: function() {
				$('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
			}
		});
	}
</script>