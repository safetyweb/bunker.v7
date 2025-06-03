<?php

//echo "<h5>_".$opcao."</h5>";

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

		if ($opcao != '') {

			$sql = "CALL SP_ALTERA_GRUPOTRABALHO (
				 '" . $cod_grupotr . "', 
				 '" . $des_grupotr . "', 
				 '" . $cod_empresa . "', 
				 '" . $opcao . "'    
				) ";

			//echo $sql;

			$arrayProc = mysqli_query($adm, $sql);

			if (!$arrayProc) {

				$cod_erro = Log_error_comand($adm, $conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql, $nom_usuario);
			}

			//mensagem de retorno
			switch ($opcao) {
				case 'CAD':
					if (@$cod_erro == 0 || @$cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível cadastrar o registro : @$cod_erro";
					}
					break;
				case 'ALT':
					if (@$cod_erro == 0 || @$cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível alterar o registro : @$cod_erro";
					}
					break;
				case 'EXC':
					if (@$cod_erro == 0 || @$cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível excluir o registro : @$cod_erro";
					}
					break;
			}
			if (@$cod_erro == 0 || @$cod_erro == "") {
				$msgTipo = 'alert-success';
			} else {
				$msgTipo = 'alert-danger';
			}
		}
	}
}


//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode($_GET['id']);
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
											<a href="javascript:void(0)" class="link-item active">Mensal</a>
										</div>
									</div>
									<div class="row">
										<div class="col-xs-12">
											<a href="javascript:void(0)" class="link-item">Semanal</a>
										</div>
									</div>

									<div class="push20"></div>

									<h4>Gerador de Link</h4>

									<div class="row">
										<div class="col-xs-12">
											<a href="javascript:void(0)" class="btn btn-sm btn-info addBox" data-size="modal-sm modal-centered" data-title="Cadastrar link" data-url="">Novo link</a>
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

										<div class="col-md-3 text-center text-info"></div>

										<?php
										$sql = "SELECT COUNT(DISTINCT URL_ORIGINAL) AS QTD_URLS FROM tab_encurtador WHERE COD_EMPRESA = $cod_empresa;";
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
										$sql = "SELECT COUNT(ID) AS QTD_CLICK FROM CLICK_LINKSENCURTADO WHERE COD_EMPRESA = $cod_empresa";
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
										$sql = "SELECT COUNT(DISTINCT IP) AS QTD_CLICK FROM CLICK_LINKSENCURTADO WHERE COD_EMPRESA = $cod_empresa GROUP BY DES_LINK";
										$query = mysqli_query($conn, $sql);
										$qtd_unico = 0;
										if (mysqli_num_rows($query) > 0) {
											$row = mysqli_fetch_assoc($query);
											$qtd_unico = $row['QTD_CLICK'];
										}
										?>

										<!-- <div class="col-md-3 text-center text-info">
											<i class="fal fa-users" aria-hidden="true"></i>
											<div class="push5"></div>
											<span class="f18b"></span>
											<div class="push"></div>
											<small style="font-weight:normal;">Usuários</small>
										</div> -->

										<div class="col-md-2 text-center text-info">
											<i class="fal fa-globe" aria-hidden="true"></i>
											<div class="push5"></div>
											<span class="f18b"><?= $qtd_unico; ?></span>
											<div class="push"></div>
											<small style="font-weight:normal;">Ips Únicos</small>
										</div>

										<div class="col-md-3 text-center text-info"></div>

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

											<canvas id="lineChart2" height="135px"></canvas>
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
														// fnEscreveArray($rowLink);
														// echo "<pre>";
														// print_r($rowLink);
														// echo "</pre>";
														$sql = "SELECT DES_LINK, COUNT(ID) AS QTD_CLICK FROM CLICK_LINKSENCURTADO WHERE COD_EMPRESA = $cod_empresa AND COD_ENCURTADOR = " . $rowLink['id'] . " GROUP BY DES_LINK";
														$query = mysqli_query($conn, $sql);
														$qtd_clic = 0;

														if ($row = mysqli_fetch_assoc($query)) {
															$qtd_clic = $row['QTD_CLICK'];
														}
														// fnEscreveArray($row);

														$sqlTemplate = "SELECT COD_TEMPLATE FROM MENSAGEM_SMS WHERE COD_EMPRESA = $cod_empresa AND COD_CAMPANHA = " . $rowLink['cod_campanha'] . " ORDER BY 1 DESC LIMIT 1";
														$queryTemplate = mysqli_query($conn, $sqlTemplate);
														$cod_template = 0;
														if ($rowTemplate = mysqli_fetch_assoc($queryTemplate)) {
															$cod_template = $row['COD_TEMPLATE'];
														}

														$modRelatorio = 1274;
														$modTemplate = 1647;
														$modComunic = 1653;

														$arrLink = explode('=', $rowLink['url_original']);
														$lastSegment = end($arrLink);
														$cod_pesquisaEncoded = $lastSegment;

												?>
														<tr>
															<td>
																<a href="<?= $rowLink['url_original'] ?>" class="f14 f-bold-500 link-item" target="_blank"><?= $rowLink['titulo'] ?></a>
															</td>
															<td>
																<small><?= fnDataFull($rowLink['dat_cadastr'])?></small>
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
																			<li><a href="action.do?mod=<?php echo fnEncode($modRelatorio); ?>&id=<?php echo fnEncode($cod_empresa); ?>&idP=<?=$cod_pesquisaEncoded?>" target="_blank">Relatório</a></li>
																			<li><a href="javascript:void(0)" class="addBox" data-size="modal-md modal-centered" data-title="Template SMS" data-url="action.do?mod=<?php echo fnEncode($modTemplate); ?>&id=<?php echo fnEncode($cod_empresa); ?>&idc=<?php echo fnEncode($rowLink['cod_campanha']); ?>&idT=<?php echo fnEncode($cod_template); ?>&tipo=<?= fnEncode('ALT') ?>&pop=true">Template</a></li>
																			<li><a href="action.do?mod=<?php echo fnEncode($modComunic); ?>&id=<?php echo fnEncode($cod_empresa); ?>&idc=<?php echo fnEncode($rowLink['cod_campanha']); ?>" target="_blank">Comunicação</a></li>
																			<!-- <li class="divider"></li> -->
																		</ul>
																	</div>
																</small>
															</td>
														</tr>
																
														
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
										WHERE COD_EMPRESA = $cod_empresa GROUP BY COUNTRY";
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
        GROUP BY SIS_OPERACIONAL";
	$query = mysqli_query($conn, $sql);

	if (mysqli_num_rows($query) > 0) {
		while ($row = mysqli_fetch_assoc($query)) {
			$labels[] = isset($row['SIS_OPERACIONAL']) ? $row['SIS_OPERACIONAL'] : 'Desconhecido';
			$data[] = $row['QTD_SISTEMAS'];
		}
	}



	// DADOS DO GRAFICO DE LINHA
	$ano = date('Y');
	$mes = date('m');
	$dataFinal = [];

	$sql = "SELECT COUNT(ID) AS QTD_CLICKS, DATE_FORMAT(DAT_CADASTR, '%d') AS DATA 
		FROM CLICK_LINKSENCURTADO 
		WHERE COD_EMPRESA = $cod_empresa 
		AND YEAR(DAT_CADASTR) = $ano
		AND MONTH(DAT_CADASTR) = $mes
		GROUP BY DATA";

	$query = mysqli_query($conn, $sql);

	if (mysqli_num_rows($query) > 0) {
		$valoresPorDia = [];
		while ($row = mysqli_fetch_assoc($query)) {
			$valoresPorDia[$row['DATA']] = $row['QTD_CLICKS'];
		}

		$totalDias = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);

		for ($i = 1; $i <= $totalDias; $i++) {
			$dia = str_pad($i, 2, '0', STR_PAD_LEFT);
			$dataFinal[] = isset($valoresPorDia[$dia]) ? $valoresPorDia[$dia] : 0;
		}

		// Agora pode fazer um echo json_encode($dataFinal)
		$contadores = json_encode($dataFinal);
	}

	?>

</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.3.0/Chart.bundle.js"></script>
<script src="js/plugins/Chart_Js/utils.js"></script>
<script src="js/pie-chart.js"></script>

<script type="text/javascript">
	// Line chart
	// Pega o número de dias do mês atual
	function getDaysInCurrentMonth() {
		const date = new Date();
		const year = date.getFullYear();
		const month = date.getMonth() + 1; // Janeiro = 0, então soma 1
		const daysInMonth = new Date(year, month, 0).getDate();

		// Gera um array de 1 até o último dia
		const days = [];
		for (let i = 1; i <= daysInMonth; i++) {
			days.push(i.toString());
		}
		return days;
	}

	// Usa a função para gerar os labels
	const daysOfMonth = getDaysInCurrentMonth();

	// Dados fictícios só pra testar (deve ter o mesmo length dos labels)
	const dataValues = Array.from({
		length: daysOfMonth.length
	}, () => Math.floor(Math.random() * 100));

	const contadores = <?php echo $contadores; ?>;

	// Cria o gráfico
	var ctx = document.getElementById("lineChart2").getContext("2d");
	var lineChart2 = new Chart(ctx, {
		type: 'line',
		data: {
			labels: daysOfMonth, // <-- aqui estão os dias do mês
			datasets: [{
				label: "Clicks",
				backgroundColor: "rgba(3, 88, 106, 0.3)",
				borderColor: "rgba(3, 88, 106, 0.70)",
				pointBorderColor: "rgba(3, 88, 106, 0.70)",
				pointBackgroundColor: "rgba(3, 88, 106, 0.70)",
				pointHoverBackgroundColor: "#fff",
				pointHoverBorderColor: "rgba(151,187,205,1)",
				pointBorderWidth: 1,
				data: contadores // seus dados aqui, precisa ter a mesma quantidade de elementos que os labels
			}]
		},
		options: {
			responsive: true,
			plugins: {
				legend: {
					display: false
				}
			}
		}
	});

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