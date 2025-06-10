<?php

//echo "<h5>_".$opcao."</h5>";
// echo fnDebug('true');
// 	ini_set('display_errors', 1);
// 	ini_set('display_startup_errors', 1);
// 	error_reporting(E_ALL);
$hashLocal = mt_rand();
$val_pesquisa = "";

$conn = conntemp($cod_empresa, "");
$adm = $connAdm->connAdm();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_grupotr = fnLimpaCampoZero($_REQUEST['COD_GRUPOTR']);
		$des_grupotr = fnLimpaCampo($_REQUEST['DES_GRUPOTR']);
		$cod_empresa = fnLimpaCampo($_REQUEST['COD_EMPRESA']);

		$nom_usuario = $_SESSION["SYS_NOM_USUARIO"];
		$actual_link = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		$MODULO = $_GET['mod'];
		$COD_MODULO = fndecode($_GET['mod']);

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];
	}
}


//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode($_GET['id']);
	$filtro = fnDecode($_GET['filtro']);
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($adm, $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
	}
} else {
	$cod_empresa = 0;
	//fnEscreve('entrou else');
}

if ($val_pesquisa != "") {
	$esconde = " ";
} else {
	$esconde = "display: none;";
}

$sqlBusca = "SELECT * FROM tab_encurtador WHERE COD_EMPRESA = $cod_empresa AND tip_url = 'TKT'";
$arrayBusca = mysqli_query($adm, $sqlBusca);
if (mysqli_num_rows($arrayBusca) == 0) {
	$sql = "SELECT COD_TEMPLATE, NOM_TEMPLATE FROM TEMPLATE WHERE COD_EMPRESA = $cod_empresa AND LOG_ATIVO = 'S' LIMIT 1";
	$array = mysqli_query($conn, $sql);
	if (mysqli_num_rows($array) > 0) {
		$sqlProd = "SELECT * FROM PRODUTOTKT WHERE COD_EMPRESA = $cod_empresa AND LOG_ATIVOTK = 'S'";
		$arrayProd = mysqli_query($conn, $sqlProd);
		if (mysqli_num_rows($arrayProd) > 0) {
			$qrTkt = mysqli_fetch_assoc($array);
			$titulo = $qrTkt['NOM_TEMPLATE'] . ' #' . $qrTkt['COD_TEMPLATE'];
			fnEncurtador($titulo, '', '', '', 'TKT', $cod_empresa, $connAdm->connAdm(), $qrTkt['COD_TEMPLATE']);
		}
	}
}
$ano = date('Y');
$mes = date('m');
$semana = date('W');
$dataMes = date('Y-m-01');
$andSemana = "";
$filtroSemana = "";
$activeM = 'active';
$activeS = '';
if ($filtro == 'semanal') {
	$andSemana = " AND WEEK(DATE_ADD('$dataMes', INTERVAL seq DAY), 1) = $semana";
	$filtroSemana = " AND WEEK(cl.DAT_CADASTR, 1) = $semana";
	$activeM = '';
	$activeS = 'active';
}

$sqlIds = "SELECT GROUP_CONCAT(ID) as ids FROM tab_encurtador WHERE COD_EMPRESA = $cod_empresa ORDER BY DAT_CADASTR DESC";
$queryIds = mysqli_query($adm, $sqlIds);
$qrIds = mysqli_fetch_assoc($queryIds);
if (isset($qrIds['ids'])) {
	$cod_encurtadores = $qrIds['ids'];
} else {
	$cod_encurtadores = 0;
}
?>

<style>
	.portlet {
		background-color: unset;
		border: unset;
	}

	.no-more-tables {
		background-color: #fff;
		padding: 30px;
		border-radius: 5px;
		box-shadow: 0px 10px 35px 0px rgba(56, 71, 109, 0.075);
	}

	.text-white {
		color: #fff;
	}

	.portlet-title {
		border-bottom: unset;
	}

	.link-item,
	.link-item:hover {
		text-decoration: none;
		color: #2c3e50;
	}

	.active {
		text-decoration: none;
		font-weight: 800;
	}
</style>
<div class="push30"></div>

<div class="row">

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet">
			<div class="portlet-title">
				<div class="caption">
					<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
				</div>

				<?php
				$formBack = "1019";
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


				<div class="push30"></div>

				<div class="row">

					<div class="col-md-2" style="height: 100%">

						<div class="row" style="height: 100%">

							<div class="col-xs-12" style="height: 100%">

								<div class="no-more-tables" style="height: 100%">

									<h4>Visualização dos Cliques</h4>

									<div class="row">
										<div class="col-xs-12">
											<a href="action.do?mod=<?php echo fnEncode(2104) ?>&id=<?php echo fnEncode($cod_empresa) ?>&filtro=<?= fnEncode('mensal') ?>" class="link-item <?= $activeM ?>">Mensal</a>
										</div>
									</div>
									<div class="row">
										<div class="col-xs-12">
											<a href="action.do?mod=<?php echo fnEncode(2104) ?>&id=<?php echo fnEncode($cod_empresa) ?>&filtro=<?= fnEncode('semanal') ?>" class="link-item <?= $activeS ?>">Semanal</a>
										</div>
									</div>

									<div class="push20"></div>

									<h4>Gerador de Link</h4>

									<div class="row">
										<div class="col-xs-12">
											<a href="javascript:void(0)" class="btn btn-sm btn-info addBox" data-size="modal-sm modal-centered" data-title="Cadastrar link" data-url="action.do?mod=<?php echo fnEncode(2106) ?>&id=<?php echo fnEncode($cod_empresa) ?>&pop=true">Novo link</a>
										</div>
									</div>

									<!-- <div class="push20"></div> -->

									<!-- <div class="row">
										<div class="col-xs-12">
											<div class="progress" style="margin-bottom:0">
												<div class="progress-bar bg-info" role="progressbar" style="width: 35%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
											</div>
										</div>
										<div class="push5"></div>
										<div class="col-xs-12">
											<p class="f14">99 de 300 links criados</p>
										</div>
										<div class="col-xs-3">
											<a href="javascript:void(0)" class="btn btn-outline btn-info btn-sm">Upgrade</a>
										</div>
									</div> -->

								</div>

							</div>

						</div>

					</div>

					<div class="col-md-10">

						<div class="row">

							<div class="col-md-12">

								<div class="no-more-tables" style="padding: 15px 0px 15px 0; border-radius: 5px;">

									<div class="row">

										<div class="col-md-2 text-center text-info"></div>

										<?php
										$sql = "SELECT COUNT(DISTINCT URL_ORIGINAL) AS QTD_URLS FROM tab_encurtador WHERE COD_EMPRESA = $cod_empresa AND ID IN ($cod_encurtadores);";
										$query = mysqli_query($adm, $sql);
										$qtd_urls = 0;
										if (mysqli_num_rows($query) > 0) {
											$row = mysqli_fetch_assoc($query);
											$qtd_urls = $row['QTD_URLS'];
										}
										?>

										<div class="col-md-2 text-center text-info">
											<i class="fal fa-link" aria-hidden="true"></i>
											<div class="push5"></div>
											<span class="f18b"><?= $qtd_urls ?></span>
											<div class="push"></div>
											<small style="font-weight:normal;">Links</small>
										</div>

										<?php
										$sql = "SELECT COUNT(ID) AS QTD_CLICK FROM CLICK_LINKSENCURTADO WHERE COD_EMPRESA = $cod_empresa AND COD_ENCURTADOR IN ($cod_encurtadores)";
										$query = mysqli_query($conn, $sql);
										$qtd_click = 0;
										if (mysqli_num_rows($query) > 0) {
											$row = mysqli_fetch_assoc($query);
											$qtd_click = $row['QTD_CLICK'];
										}
										?>

										<div class="col-md-2 text-center text-info">
											<i class="fal fa-mouse-pointer" aria-hidden="true"></i>
											<div class="push5"></div>
											<span class="f18b"><?= $qtd_click; ?></span>
											<div class="push"></div>
											<small style="font-weight:normal;">Acessos</small>
										</div>

										<?php
										$sql = "SELECT COUNT(DISTINCT IP) AS QTD_CLICK FROM CLICK_LINKSENCURTADO WHERE COD_EMPRESA = $cod_empresa AND COD_ENCURTADOR IN ($cod_encurtadores)";
										$query = mysqli_query($conn, $sql);
										$qtd_unico = 0;
										if (mysqli_num_rows($query) > 0) {
											$row = mysqli_fetch_assoc($query);
											$qtd_unico = $row['QTD_CLICK'];
										}
										?>

										<div class="col-md-2 text-center text-info">
											<i class="fal fa-globe" aria-hidden="true"></i>
											<div class="push5"></div>
											<span class="f18b"><?= $qtd_unico; ?></span>
											<div class="push"></div>
											<small style="font-weight:normal;">Ips Únicos</small>
										</div>

										<?php
										$sqlDis = "SELECT COUNT(DISTINCT SIS_OPERACIONAL) AS QTD_DISPOSITIVOS 
												FROM CLICK_LINKSENCURTADO 
												WHERE COD_EMPRESA = $cod_empresa;
												AND COD_ENCURTADOR IN ($cod_encurtadores)";
										$queryDis = mysqli_query($conn, $sqlDis);
										$qtd_unicoDis = 0;
										if (mysqli_num_rows($queryDis) > 0) {
											$rowDis = mysqli_fetch_assoc($queryDis);
											$qtd_unicoDis = $rowDis['QTD_DISPOSITIVOS'];
										}
										// fnEscreve($sqlDis);
										?>

										<div class="col-md-2 text-center text-info">
											<!-- <i class="fal fa-globe" aria-hidden="true"></i> -->
											<i class="fal fa-phone-laptop" aria-hidden="true"></i>
											<div class="push5"></div>
											<span class="f18b"><?= $qtd_unicoDis; ?></span>
											<div class="push"></div>
											<small style="font-weight:normal;">Dispositivos Únicos</small>
										</div>

										<div class="col-md-2 text-center text-info"></div>

									</div>

								</div>

							</div>

						</div>

						<div class="push20"></div>

						<div class="row">

							<div class="col-md-7">

								<div class="row">

									<div class="col-xs-12">

										<div class="no-more-tables">

											<h4>Contagem de cliques</h4>

											<canvas id="lineChart2" height="250px"></canvas>
										</div>

									</div>

								</div>

							</div>

							<div class="col-md-5">

								<div class="row">

									<div class="col-xs-12">

										<div class="no-more-tables">
											<h4>Dispositivos</h4>
											<div class="push20"></div>
											<canvas id="chart-area" height="186.5px"></canvas>


										</div>

									</div>

								</div>

							</div>

						</div>

						<div class="push20"></div>

						<div class="row">

							<div class="col-md-8">

								<div class="no-more-tables">

									<h4>Links</h4>

									<div class="row">

										<div class="col-xs-12">

											<table class="table table-bordered table-hover tableSorter">
												<thead>
													<tr>
														<th>Link</th>
														<th>Criado em</th>
														<th class="{ sorter: false }"></th>
													</tr>
												</thead>
												<tbody>

													<?php

													$sqlLink = "SELECT * FROM tab_encurtador WHERE COD_EMPRESA = $cod_empresa ORDER BY DAT_CADASTR DESC";
													$queryLink = mysqli_query($adm, $sqlLink);
													while ($rowLink = mysqli_fetch_assoc($queryLink)) {

														$sql = "SELECT DES_LINK, COUNT(ID) AS QTD_CLICK FROM CLICK_LINKSENCURTADO WHERE COD_EMPRESA = $cod_empresa AND COD_ENCURTADOR = " . $rowLink['id'] . " GROUP BY COD_ENCURTADOR";
														// fnEscreve($sql);
														$query = mysqli_query($conn, $sql);
														$qtd_clic = 0;
														$template = 'S';
														$tableTemplate = '';
														$fieldTemplate = '';
														$andTemplate = '';
														$cod_template = 0;
														$modRelatorio = 0;
														$modTemplateSms = 0;
														$modTemplateTkt = 0;
														$modComunic = 0;
														$modPesquisa = 0;
														$modEditar = 0;
														$modProdutos = 0;
														$modGeralink = 0;

														if ($row = mysqli_fetch_assoc($query)) {
															$qtd_clic = $row['QTD_CLICK'];
														}

														if ($rowLink['tip_url'] == 'NPS') {

															$modRelatorio = 1274;
															$modTemplateSms = 1647;
															$modComunic = 1653;
															$modPesquisa = 1510;
															$modEditar = 1255;
															$tableTemplate = 'MENSAGEM_SMS';
															$fieldTemplate = 'COD_TEMPLATE_SMS';
															$andTemplate = " AND COD_CAMPANHA = " . $rowLink['cod_campanha'];

															$arrLink = explode('=', $rowLink['url_original']);
															$lastSegment = end($arrLink);
															$cod_pesquisaEncoded = $lastSegment;
														} else if ($rowLink['tip_url'] == 'TKT') {

															$modTemplateTkt = 1114;
															$modProdutos = 1168;
															$tableTemplate = 'CONFIGURACAO_TICKET';
															$fieldTemplate = 'COD_TEMPLATE_TKT';
														} else {
															$modGeralink = 2106;
															$template = 'N';
														}

														if ($template == 'S') {
															$sqlTemplate = "SELECT $fieldTemplate FROM $tableTemplate WHERE COD_EMPRESA = $cod_empresa $andTemplate LIMIT 1";
															
															$queryTemplate = mysqli_query($conn, $sqlTemplate);
															if ($rowTemplate = mysqli_fetch_assoc($queryTemplate)) {
																$cod_template = $rowTemplate[$fieldTemplate];
															}
														}


													?>
														<tr>
															<td>
																<a href="<?= $rowLink['url_original'] ?>" class="f14 f-bold-500 link-item" target="_blank"><?= $rowLink['titulo'] ?></a>
															</td>
															<td>
																<small><?= fnDataFull($rowLink['dat_cadastr']) ?></small>
															</td>
															<td class="text-center">
																<span class="badge bg-info text-white badge-squared"><?= $qtd_clic ?></span>
															</td>
															<td class="text-right">

																<small>
																	<div class="btn-group dropdown dropleft">
																		<a href="javascript:void(0)" class="dropdown-toggle transparency" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: #2c3e50;">
																			<span class="fal fa-ellipsis-v fa-2x"></span>
																		</a>
																		<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
																			<?php if ($modEditar != 0) { ?>
																				<li><a href="javascript:void(0)" class="addBox" data-size="modal-md" data-title="Configurações" data-url="action.do?mod=<?php echo fnEncode($modEditar); ?>&id=<?php echo fnEncode($cod_empresa); ?>&idc=<?php echo fnEncode($rowLink['cod_campanha']); ?>&idP=<?= $cod_pesquisaEncoded ?>&tipo=<?php echo fnEncode('ALT') ?>&pop=true">Configurações</a></li>
																			<?php } ?>
																			<?php if ($modPesquisa != 0) { ?>
																				<li><a href="action.do?mod=<?php echo fnEncode($modPesquisa); ?>&id=<?php echo fnEncode($cod_empresa); ?>&idP=<?= $cod_pesquisaEncoded ?>" target="_blank">Pesquisa</a></li>
																			<?php } ?>
																			<?php if ($modRelatorio != 0) { ?>
																				<li><a href="action.do?mod=<?php echo fnEncode($modRelatorio); ?>&id=<?php echo fnEncode($cod_empresa); ?>&idP=<?= $cod_pesquisaEncoded ?>" target="_blank">Relatório</a></li>
																			<?php } ?>
																			<?php if ($modTemplateSms != 0) { ?>
																				<li><a href="javascript:void(0)" class="addBox" data-size="modal-md" data-title="Template SMS" data-url="action.do?mod=<?php echo fnEncode($modTemplateSms); ?>&id=<?php echo fnEncode($cod_empresa); ?>&idc=<?php echo fnEncode($rowLink['cod_campanha']); ?>&idT=<?php echo fnEncode($cod_template); ?>&tipo=<?= fnEncode('ALT') ?>&pop=true">Template</a></li>
																			<?php } ?>
																			<?php if ($modTemplateTkt != 0) { ?>
																				<li><a href="action.do?mod=<?php echo fnEncode($modTemplateTkt); ?>&id=<?php echo fnEncode($cod_empresa); ?>&idT=<?php echo fnEncode($cod_template); ?>" target="_blank">Template</a></li>
																			<?php } ?>
																			<?php if ($modComunic != 0) { ?>
																				<li><a href="action.do?mod=<?php echo fnEncode($modComunic); ?>&id=<?php echo fnEncode($cod_empresa); ?>&idc=<?php echo fnEncode($rowLink['cod_campanha']); ?>" target="_blank">Comunicação</a></li>
																			<?php } ?>
																			<?php if ($modProdutos != 0) { ?>
																				<li><a href="action.do?mod=<?php echo fnEncode($modProdutos); ?>&id=<?php echo fnEncode($cod_empresa); ?>" target="_blank">Produtos</a></li>
																			<?php } ?>
																			<?php if ($modGeralink != 0) { ?>
																				<li><a href="javascript:void(0)" class="addBox" data-size="modal-sm modal-centered" data-title="Editar link" data-url="action.do?mod=<?php echo fnEncode($modGeralink) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idl=<?= fnEncode($rowLink['id_rpad']) ?>&pop=true">Editar link</a></li>
																			<?php } ?>
																			<li class="divider"></li>
																			<li><a href="javascript:void(0)" id="btn_<?= $rowLink['id_rpad'] ?>">Copiar Link</a></li>
																		</ul>
																	</div>
																</small>

															</td>
														</tr>
														<input type="text" id="link_<?= $rowLink['id_rpad'] ?>" value='<?= $rowLink['url_encurtada'] ?>' style="display: none" />
														<script>
															$("#btn_<?= $rowLink['id_rpad'] ?>").click(function() {
																$("#link_<?= $rowLink['id_rpad'] ?>").show();
																if (navigator.userAgent.match(/ipad|ipod|iphone/i)) {
																	var el = $("#link_<?= $rowLink['id_rpad'] ?>").get(0);
																	var editable = el.contentEditable;
																	var readOnly = el.readOnly;
																	el.contentEditable = true;
																	el.readOnly = false;
																	var range = document.createRange();
																	range.selectNodeContents(el);
																	var sel = window.getSelection();
																	sel.removeAllRanges();
																	sel.addRange(range);
																	el.setSelectionRange(0, 999999);
																	el.contentEditable = editable;
																	el.readOnly = readOnly;
																} else {
																	$("#link_<?= $rowLink['id_rpad'] ?>").select();
																}
																document.execCommand('copy');
																$("#link_<?= $rowLink['id_rpad'] ?>").blur().hide();
																$.alert({
																	title: "",
																	content: "Link copiado com sucesso!",
																	type: "green",
																	buttons: {
																		Ok: function() {}
																	}
																});
															});
														</script>


													<?php

													}

													?>

												</tbody>
											</table>

										</div>


									</div>

								</div>

							</div>

							<div class="col-md-4">

								<div class="no-more-tables">

									<h4>Origem dos acessos</h4>
									<?php
									$sql = "SELECT COUNTRY, COUNT(ID) AS QTD_CLICKS FROM click_linksencurtado
											WHERE COD_EMPRESA = $cod_empresa 
											AND COD_ENCURTADOR IN ($cod_encurtadores)
											GROUP BY COUNTRY";
									$query = mysqli_query($conn, $sql);
									while ($qrResult = mysqli_fetch_assoc($query)) {
										$porcentagem = ($qrResult['QTD_CLICKS'] / $qtd_click) * 100;
									?>
										<div class="row">
											<div class="col-xs-6">
												<p class="f14b"><?= $qrResult['COUNTRY'] ?></p>
											</div>
											<div class="col-xs-6 text-right">
												<p class="fs-6 text-gray-400 f-bold-800"><?= fnValor($porcentagem, 2) ?>%</p>
											</div>
											<div class="col-xs-12">
												<div class="progress" style="margin-bottom:0">
													<div class="progress-bar bg-info" role="progressbar" style="width: <?= $porcentagem ?>%;" aria-valuenow="<?= fnValor($porcentagem, 2) ?>" aria-valuemin="0" aria-valuemax="100"></div>
												</div>
											</div>
										</div>
									<?php
									}
									?>

								</div>

							</div>

						</div>

					</div>

				</div>

			</div>

		</div>
		<!-- fim Portlet -->
	</div>

	<div class="modal fade" id="popModal" tabindex='-1'>
		<div class="modal-dialog" style="">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span
							aria-hidden="true">&times;</span></button>
					<h4 class="modal-title"></h4>
				</div>
				<div class="modal-body">
					<iframe id="frameModal" frameborder="0" style="width: 100%; min-height: 300px;"></iframe>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

	<?php

	// DADOS DO GRAFICO DE PIZZA
	$labels = [];
	$data = [];
	$sql = "SELECT SIS_OPERACIONAL, COUNT(ID) AS QTD_SISTEMAS 
        FROM CLICK_LINKSENCURTADO 
        WHERE COD_EMPRESA = $cod_empresa 
		AND COD_ENCURTADOR IN ($cod_encurtadores)
        GROUP BY SIS_OPERACIONAL";
	$query = mysqli_query($conn, $sql);

	if (mysqli_num_rows($query) > 0) {
		while ($row = mysqli_fetch_assoc($query)) {
			$labels[] = isset($row['SIS_OPERACIONAL']) ? $row['SIS_OPERACIONAL'] : 'Desconhecido';
			$data[] = $row['QTD_SISTEMAS'];
		}
	}



	// DADOS DO GRAFICO DE LINHA

	$dataFinal = [];

	$sql = "SELECT 
				DAY(d.dia) AS DATA,
				IFNULL(COUNT(cl.ID), 0) AS QTD_CLICKS
			FROM 
				(
					SELECT 
						DATE_ADD('$dataMes', INTERVAL seq DAY) AS dia
					FROM 
						(
							SELECT 0 AS seq UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL
							SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL
							SELECT 8 UNION ALL SELECT 9 UNION ALL SELECT 10 UNION ALL SELECT 11 UNION ALL
							SELECT 12 UNION ALL SELECT 13 UNION ALL SELECT 14 UNION ALL SELECT 15 UNION ALL
							SELECT 16 UNION ALL SELECT 17 UNION ALL SELECT 18 UNION ALL SELECT 19 UNION ALL
							SELECT 20 UNION ALL SELECT 21 UNION ALL SELECT 22 UNION ALL SELECT 23 UNION ALL
							SELECT 24 UNION ALL SELECT 25 UNION ALL SELECT 26 UNION ALL SELECT 27 UNION ALL
							SELECT 28 UNION ALL SELECT 29 UNION ALL SELECT 30
						) AS seq
					WHERE 
						DATE_ADD('$dataMes', INTERVAL seq DAY) <= LAST_DAY('$dataMes')
						$andSemana
				) AS d
			LEFT JOIN 
				CLICK_LINKSENCURTADO cl ON DATE(cl.DAT_CADASTR) = d.dia
				AND cl.COD_EMPRESA = $cod_empresa
				AND YEAR(cl.DAT_CADASTR) = $ano
				AND MONTH(cl.DAT_CADASTR) = $mes
				AND cl.COD_ENCURTADOR IN ($cod_encurtadores)
				$filtroSemana
			GROUP BY 
				d.dia
			ORDER BY 
				d.dia;";
	// fnEscreve($sql);
	$query = mysqli_query($conn, $sql);

	if (mysqli_num_rows($query) > 0) {
		while ($row = mysqli_fetch_assoc($query)) {
			$dataFinal[$row['DATA']] = $row['QTD_CLICKS'];
		}

		$contadores = json_encode($dataFinal);
		// echo "<pre>";
		// print_r($contadores);
		// print_r($dataFinal);
		// echo "</pre>";
		// Agora pode fazer um echo json_encode($dataFinal)
	}

	?>

</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.3.0/Chart.bundle.js"></script>
<script src="js/plugins/Chart_Js/utils.js"></script>
<script src="js/pie-chart.js"></script>


<script type="text/javascript">
	// Line chart

	const contadores = <?php echo $contadores; ?>;

	// Line chart 2
	var ctx = document.getElementById("lineChart2");
	var lineChart = new Chart(ctx, {
		type: 'line',
		data: {
			labels: Object.keys(contadores),
			datasets: [{
				label: "Cliques",
				backgroundColor: "rgba(0,0,0,0)",
				borderColor: "#00A8C6",
				pointBorderColor: "#00A8C6",
				pointBackgroundColor: "#fff",
				pointRadius: 4,
				pointBorderWidth: 3,
				data: Object.values(contadores)
			}]
		},
		options: {
			legend: {
				display: false
			},
			maintainAspectRatio: false,
			animation: {
				duration: 2000,
			},
		}
	});



	//---------------------------------------------------------------------

	const labels = <?php echo json_encode($labels); ?>;
	const data = <?php echo json_encode($data); ?>;

	var config = {
		type: 'doughnut',
		data: {
			datasets: [{
				data: data, // usa o array de quantidades do PHP
				backgroundColor: [
					window.chartColors.red,
					window.chartColors.green,
					window.chartColors.blue,
					window.chartColors.yellow,
					window.chartColors.orange,
					window.chartColors.purple,
					// adicione mais cores se precisar
				],
				label: 'Dataset 1'
			}],
			labels: labels // usa o array de labels do PHP
		},
		options: {
			responsive: true,
			aspectRatio: 2,
			cutoutPercentage: 70,
			legend: {
				position: 'bottom',
				display: true,
			},
			title: {
				display: true,
				// text: 'Chart.js Doughnut Chart'
			},
			animation: {
				animateScale: true,
				animateRotate: true
			}
		}
	};

	window.onload = function() {
		var ctx2 = document.getElementById("chart-area").getContext("2d");
		window.myDoughnut = new Chart(ctx2, config);
	};
</script>