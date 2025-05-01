<?php
//echo fnDebug('true');

$hashLocal = mt_rand();

//inicialização de variáveis
$hoje = fnFormatDate(date("Y-m-d"));
$hojeSql = date("Y-m-d");
//$hoje = fnFormatDate(date('Y-m-d', strtotime($hoje. '- 1 days')));
//$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 30 days')));
$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30 . '- 2 days')));

$conn = conntemp($cod_empresa, '');
$amd = $connAdm->connAdm();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_empresa = fnLimpaCampoZero($_POST['COD_EMPRESA']);
		$cod_univend = $_POST['COD_UNIVEND'];
		$cod_grupotr = $_REQUEST['COD_GRUPOTR'];
		$cod_tiporeg = $_REQUEST['COD_TIPOREG'];
		$dat_ini = fnDataSql($_POST['DAT_INI']);
		$dat_fim = fnDataSql($_POST['DAT_FIM']);

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		if ($opcao != '') {
		}
	}
}

//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode($_GET['id']);
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI, DAT_CADASTR FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($adm, $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
		$dat_cadastr = $qrBuscaEmpresa['DAT_CADASTR'];
	}
} else {
	$cod_empresa = 0;
	//fnEscreve('entrou else');
}

//inicialização das variáveis - default	
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
	$dat_ini = fnDataSql($dias30);
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
	$dat_fim = fnDataSql($hoje);
}

//faz pesquisa por revenda (geral)
if ($cod_univend == "9999") {
	$temUnivend = "N";
} else {
	$temUnivend = "S";
}

//busca revendas do usuário
include "unidadesAutorizadas.php";

// Filtro por Grupo de Lojas
include "filtroGrupoLojas.php";

//índice de fidelização
$sql = "SELECT 
			  ((ifnull(SUM(if(B.LOG_AVULSO='N',1,0)),0) / count(cod_venda))*100) as INDICE_FIDELIZACAO 
			FROM VENDAS A
			LEFT JOIN CLIENTES B ON B.COD_CLIENTE = A.COD_CLIENTE 
			WHERE A.COD_STATUSCRED in (0,1,2,3,4,5,7,8,9) AND 
			  A.COD_EMPRESA = $cod_empresa AND 
			  A.COD_UNIVEND IN($lojasSelecionadas) AND 
			  A.DAT_CADASTR_WS BETWEEN  '$hojeSql 00:00:00' AND '$hojeSql 23:59:59' ";

//fnEscreve($sql);

$arrayQuery = mysqli_query($conn, $sql);
$qrBuscaIndFideliza = mysqli_fetch_assoc($arrayQuery);

if (isset($qrBuscaIndFideliza)) {
	$indice_fidelizacao_geral = $qrBuscaIndFideliza['INDICE_FIDELIZACAO'];
}

//fnEscreve($ticketgerado1);
//fnEscreve($qtd_vendas1);
//fnEscreve($val_media_venda1);
//fnEscreve($qtd_vendastkt1);
//fnEscreve($val_medio_tkt1);


//fnEscreve(strlen($dat_fim));
//fnEscreve($data_fim);
//fnEscreve($lojasSelecionadas);

$hor_ini = " 00:00:00";
$hor_fim = " 23:59:59";

?>

<style>
	/*.progress {
  height: 20px;
  margin-bottom: 10px;
}
.progress .skill {
  line-height: 20px;
  padding: 0;
  margin: 0 0 0 20px;
  text-shadow: 0 1px 1px rgba(0,0,0,.9);	
  text-transform: uppercase;
}
.progress .skill .val {
  float: right;
  font-style: normal;
  text-shadow: 0 1px 1px rgba(0,0,0,.9);
  margin: 0 20px 0 0;
}

.progress-bar {
  text-align: left;
  transition-duration: 3s;
}

.progress > .progress-completed {
	position: absolute;
	right: 0px;
	font-weight: 800;
	text-shadow: 0 1px 1px rgba(0,0,0,.9);
	padding: 3px 10px 2px;
}*/

	.slim {
		height: 23px;
	}

	.progress {
		border-radius: 3px;
		height: 15px;
		white-space: nowrap;
		word-spacing: nowrap;
	}

	.skill-name {
		text-transform: uppercase;
		margin-left: 10px;
		padding-left: 10px;
		padding-top: 2.5px;
		float: left;
		font-family: 'Raleway', sans-serif;
		font-size: 1.1em;
	}

	.progress-bar {
		text-shadow: -0.5px 0 1.4px #000 !important;
	}

	.progress .progress-bar,
	.progress .progress-bar.progress-bar-default {
		background-color: #3498DB;
	}

	.progress .progress-bar {
		animation-name: animateBar;
		animation-iteration-count: 1;
		animation-timing-function: ease-in;
		animation-duration: 1.0s;
	}

	@keyframes animateBar {
		0% {
			transform: translateX(-100%);
		}

		100% {
			transform: translateX(0);
		}
	}

	/*media Queries*/

	@media (min-width: 768px) and (max-width: 991px) {
		#about-section h1 {
			font-size: 2.0em;
		}

		.nav-pills li a {
			font-size: 1.3em !important;
		}


	}


	@media (max-width: 767px) {
		#about-section h1 {
			margin-top: 90px !important;
			font-size: 1.5em;
		}

		.nav-pills li a {
			font-size: 1.3em !important;
		}

		.about-me-text {
			font-size: 1.0em;
		}

		.btn-tab .btn-overide {
			font-size: 0.8em;
			width: 200px;
		}

		#about-section {
			height: 750px;
		}

		#about-section h1 {
			margin-top: 50px;

		}
	}

	@media (max-width: 456px) {
		#about-section {
			height: 730px;
		}

		.nav-pills li a {
			font-size: 0.9em !important;
		}
	}

	@media(max-width: 648px) {
		#about-section {
			height: 800px;
		}
	}

	@media (min-width: 481px) and (max-width: 553px) {
		#about-section {
			height: 900px;
		}
	}

	@media (max-width: 479px) {
		.btn-hire {
			margin-top: 20px !important;

		}

		.btn-contact {
			margin-top: 10px !important;
		}

		#about-section {
			height: 950px;
		}
	}

	@media (max-width: 442px) {
		#about-section {
			height: 980px;
		}
	}

	@media (max-width: 411px) {
		#about-section {
			height: 1020px;
		}
	}

	@media (max-width: 373px) {
		#about-section {
			height: 1050px;
		}
	}
</style>


<div class="push30"></div>

<div class="row">

	<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

		<div class="col-md12 margin-bottom-30">
			<!-- Portlet -->
			<div class="portlet portlet-bordered">
				<div class="portlet-title">
					<div class="caption">
						<i class="fal fa-terminal"></i>
						<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
					</div>

					<?php
					include "backReport.php";
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

						<fieldset>
							<legend>Filtros</legend>

							<div class="row">

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Unidade de Atendimento</label>
										<?php include "unidadesAutorizadasComboMulti.php"; ?>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Grupo de Lojas</label>
										<?php include "grupoLojasComboMulti.php"; ?>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Região</label>
										<?php include "grupoRegiaoMulti.php"; ?>
									</div>
								</div>

							</div>

							<div class="row">

								<div class="col-md-2">
									<div class="push20"></div>
									<button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-sm btn-block getBtn"><i class="fal fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
								</div>

							</div>

						</fieldset>
					</div>
				</div>
			</div>

			<div class="push20"></div>

			<div class="portlet portlet-bordered">

				<div class="portlet-body">

					<div class="login-form">
						<div class="push50"></div>

						<div class="row text-center">

							<div class="form-group text-center col-lg-4">
								<h4>Índice de Fidelização <b>Geral</b> em <?php echo $hoje; ?> </h4>
								<div class="push30"></div>

								<canvas id="foo" width="400" height="200">guage</canvas>

								<div class="row">

									<div class="form-group text-right col-lg-4" style="padding:0 30px 0 0;">

									</div>
									<div class="form-group text-center col-lg-4">
										<h3 style="margin:10px 0 0 0;"><?php echo fnValor($indice_fidelizacao_geral, 2); ?>%</h3>
									</div>
									<div class="form-group text-left col-lg-4" style="padding:0 0 0 20px;">

									</div>

								</div>

								<div class="push20"></div>

							</div>

							<div class="form-group text-center col-lg-7">
								<h4>Índice de Fidelização <b>por Lojas</b> em <?php echo $hoje; ?></h4>
								<div class="push20"></div>

								<div role="tabpanel" class=" tab-pane" id="skill">
									<div class="skill-section">

										<?php

										// Filtro por Grupo de Lojas
										include "filtroGrupoLojas.php";

										//carrega Unidades
										/*$ARRAY_UNIDADE1 = array(
											'sql' => "select COD_UNIVEND,cod_empresa,nom_fantasi,NOM_UNIVEND from unidadevenda where cod_empresa=$cod_empresa  and cod_exclusa=0",
											'cod_empresa' => $cod_empresa,
											'conntadm' => $connAdm->connAdm(),
											'IN' => 'N',
											'nomecampo' => '',
											'conntemp' => '',
											'SQLIN' => ""
										);
										$ARRAY_UNIDADE = fnUnivend($ARRAY_UNIDADE1);
										*/

										//busca fidelização por lojas
										$sql = "SELECT  A.COD_UNIVEND,
														uni.NOM_FANTASI,
												((ifnull(SUM(if(B.LOG_AVULSO='N',1,0)),0) / count(cod_venda))*100) as INDICE_FIDELIZACAO 
												FROM VENDAS A 
												LEFT JOIN CLIENTES B ON B.COD_CLIENTE = A.COD_CLIENTE
												LEFT JOIN unidadevenda uni ON uni.COD_UNIVEND=A.COD_UNIVEND
												WHERE A.COD_STATUSCRED in (0,1,2,3,4,5,7,8,9) AND 
												A.COD_EMPRESA = $cod_empresa AND 
												A.COD_UNIVEND IN($lojasSelecionadas) AND 
												A.DAT_CADASTR_WS BETWEEN  '$hojeSql 00:00' AND '$hojeSql 23:59'
												GROUP BY A.COD_UNIVEND 
												ORDER BY INDICE_FIDELIZACAO DESC ";


										//fnEscreve($sql);	
										//fnValidaSql(connTemp($cod_empresa,''),$sql);

										$arrayQuery = mysqli_query($conn, $sql);



										$count = 0;
										$temExpande = "";
										while ($qrFidelizacaoLojas = mysqli_fetch_assoc($arrayQuery)) {
											$count++;
											//$NOM_ARRAY_UNIDADE = (array_search($qrFidelizacaoLojas['COD_UNIVEND'], array_column($ARRAY_UNIDADE, 'COD_UNIVEND')));

											$nom_univend = $qrFidelizacaoLojas['NOM_FANTASI'];
											$indice_fidelizacao = $qrFidelizacaoLojas['INDICE_FIDELIZACAO'];

											//abre expansor de lojas
											if ($count == 9) {
												echo "<div id='expLojas' style='display:none;'>";
												$temExpande = "S";
											}
										?>

											<div class="row">
												<div class="col-xs-1 slim text-right"><?= $count ?></div>
												<div class="col-xs-9 slim">
													<div class="progress">
														<div class="progress-bar active" role="progressbar" aria-valuenow="<?= fnvalorSql(fnValor($indice_fidelizacao, 0)) ?>" aria-valuemin="0" aria-valuemax="100" style="width: 90%">
															<span class="skill-name"><strong><?= $nom_univend ?></strong></span>
														</div>
													</div>
												</div>
												<div class="col-xs-2 text-center slim"><?= fnvalorSql(fnValor($indice_fidelizacao, 2)) ?>%</div>
											</div>


											<!-- <div class="progress skill-bar pull-left" style="width: 90%;">
															<div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="<?php echo fnvalorSql(fnValor($indice_fidelizacao, 0)); ?>" aria-valuemin="0" aria-valuemax="100">
																<span class="skill"><?php echo $nom_univend; ?> <i class="val"><?php echo fnvalorSql(fnValor($indice_fidelizacao, 2)); ?>%</i></span>
															</div>
														</div>
														<div class="pull-right"><?php echo fnValor($indice_fidelizacao, 2); ?>%</div> -->

										<?php
										}

										//fecha expansor de lojas
										if ($temExpande == "S") {
											echo "</div>";
											echo "<div class='push5'></div>";
											echo "<div id='dvShow' style='display:block;'><a id='btShow' class='btn btn-default btn-sm btn-block'><i class='fa fa-plus' aria-hidden='true'></i>&nbsp; Ver Mais Lojas </a></div>";
											echo "<div id='dvHide' style='display:none;'><a id='btHide' class='btn btn-default btn-sm btn-block'><i class='fa fa-minus' aria-hidden='true'></i>&nbsp; Ver Menos Lojas </a></div>";
										}

										?>
									</div>
								</div>

							</div>

							<div class="push50"></div>


						</div>

						<input type="hidden" name="NUM_ORDENAC" id="NUM_ORDENAC" value="">
						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
						<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
						<div class="push5"></div>



						<div class="push30"></div>


					</div>

				</div>
			</div>
			<!-- fim Portlet -->
	</form>

</div>


<div class="push20"></div>


<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />

<script src="js/gauge.coffee.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.min.js"></script>
<script src="js/plugins/Chart_Js/utils.js"></script>

<script>
	//datas
	$(function() {

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


	//graficos
	$(document).ready(function() {

		$('#btShow').click(function() {
			$("#dvShow").hide();
			$("#dvHide").show();
			$("#expLojas").toggle("slow");
		});

		$('#btHide').click(function() {
			$("#dvHide").hide();
			$("#dvShow").show();
			$("#expLojas").toggle("slow");
		});

		//chosen
		$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
		$('#formulario').validator();


		//progress bar - índice de emissão de tickets - lojas
		$('.progress .progress-bar').css("width",
			function() {
				return $(this).attr("aria-valuenow") + "%";
			}
		)


		//Gauge
		var opts = {
			lines: 10, // The number of lines to draw
			angle: 0, // The length of each line
			lineWidth: 0.34, // The line thickness
			pointer: {
				length: 0.6, // The radius of the inner circle
				strokeWidth: 0.035, // The rotation offset
				color: '#566573' // Fill color
			},
			colorStart: '#1ABC9C', // Colors
			colorStop: '#1ABC9C', // just experiment with them
			strokeColor: '#E0E0E0', // to see which ones work best for you
			generateGradient: true
		};

		var target = document.getElementById('foo'); // your canvas element
		var gauge = new Gauge(target);
		//alert(gauge);
		gauge.setOptions(opts); // create sexy gauge!
		gauge.maxValue = 100; // set max gauge value
		gauge.animationSpeed = 32; // set animation speed (32 is default value)
		gauge.set(<?php echo $indice_fidelizacao_geral; ?>); // set actual value			
		//gauge.set(45); // set actual value	

		// alert("<?php echo $indice_fidelizacao_geral; ?>");		




	});
</script>