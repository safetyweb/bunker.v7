<?php

//echo "<h5>_".$opcao."</h5>";

// definir o numero de itens por pagina
$itens_por_pagina = 50;

// Página default
$pagina = 1;

$dias30="";
//inicialização de variáveis
$hoje = fnFormatDate(date("Y-m-d"));
$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 1 days')));

$hashLocal = mt_rand();

$conn = conntemp($cod_empresa,"");
$adm = $connAdm->connAdm();

function getColor($num) {
    $hash = md5('color' . $num); // modify 'color' to get a different palette
    return array(
        hexdec(substr($hash, 0, 2)), // r
        hexdec(substr($hash, 2, 2)), // g
        hexdec(substr($hash, 4, 2))); //b
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;
		$num_celular = fnLimpaCampo(fnLimpaDoc($_REQUEST['NUM_CELULAR']));
		$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
		$des_origem = fnLimpaCampo($_REQUEST['DES_ORIGEM']);
		$cod_hotel = fnLimpaCampoZero($_REQUEST['COD_HOTEL']);
		$cod_chale = fnLimpaCampoZero($_REQUEST['COD_CHALE']);
		$dat_ini = fnDataSql($_POST['DAT_INI']);
		$dat_fim = fnDataSql($_POST['DAT_FIM']);

		if (empty($_REQUEST['LOG_LABELS'])) {$log_labels='N';}else{$log_labels=$_REQUEST['LOG_LABELS'];}

		$nom_usuario = $_SESSION["SYS_NOM_USUARIO"];
		$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$MODULO = $_GET['mod'];
		$COD_MODULO = fndecode($_GET['mod']);

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

if($log_labels == 'S'){
	$checkLabels = "checked";
}else{
	$checkLabels = "";
}

//inicialização das variáveis - default	
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31" ){
	$dat_ini = fnDataSql($dias30); 
} 
if (strlen($dat_fim ) == 0 || $dat_fim == "1969-12-31"){
	$dat_fim = fnDataSql($hoje); 
}

if($des_origem != ""){
	$andOrigem = "AND AD.DES_ORIGEM = '$des_origem'";
}else{
	$andOrigem = "";
}

if($cod_hotel != "" && $cod_hotel != "0"){
	$andHotel = "AND AD.COD_HOTEL IN($cod_hotel)";
}else{
	$andHotel = "";
}

if($cod_chale != "" && $cod_chale != "0"){
	$andChale = "AND AD.COD_CHALE = $cod_chale";
}else{
	$andChale = "";
}

if($num_celular != ""){
	$andCelular = "AND AD.NUM_CELULAR = '$num_celular'";
}else{
	$andCelular = "";
}

// $sql = "SELECT AD.DES_ORIGEM, COUNT(AD.COD_ACESSO) ACESSOS
// 		FROM ACESSOS_ADORAI AD
// 		WHERE AD.COD_EMPRESA = 274
// 		AND AD.DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
// 		GROUP BY DES_ORIGEM					
// 		ORDER BY DAT_CADASTR DESC";

// // fnEscreve($sql);

// $arrayQuery = mysqli_query(conntemp($cod_empresa,""), $sql);

// $origens = "";
// $acessosOrig = [];
// $countOrig = 0;

// while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery)) {
// 	$countOrig++;
// 	$origens .= '["'.$qrBuscaModulos['DES_ORIGEM'].'"],';
// 	array_push($acessosOrig, $qrBuscaModulos['ACESSOS']);
// }

// $origens = rtrim(ltrim(trim($origens),","),",");

// $arrCorOrig = array();
// $arrCorOrigLbl = array();

// for ($i=0; $i < $countOrig; $i++) { 
// 	$cores2 = getColor($i);
// 	array_push($arrCorOrig, "rgba($cores2[0],$cores2[1],$cores2[2],0.4)");
// 	array_push($arrCorOrigLbl, "rgba($cores2[0],$cores2[1],$cores2[2],0.9)");
// }

$sql = "SELECT DATE_FORMAT(DAT_CADASTR, '%d/%m') DIA, COUNT(AD.COD_ACESSO) ACESSOS FROM ACESSOS_ADORAI AD
		WHERE AD.COD_EMPRESA = $cod_empresa
		AND AD.DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
		$andOrigem
		$andHotel
		$andChale
		$andCelular
		GROUP BY DATE_FORMAT(DAT_CADASTR, '%Y-%m-%d')
		ORDER BY DAT_CADASTR ASC";

// fnEscreve($sql);

$arrayQuery = mysqli_query(conntemp($cod_empresa,""), $sql);

$count = 0;
$pesquisasDia = [];
$indiceDias = [];

while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery)) {
	array_push($indiceDias, $qrBuscaModulos['DIA']);
	array_push($pesquisasDia, $qrBuscaModulos['ACESSOS']);
}
//fnMostraForm();

?>

<style type="text/css">
	
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

</style>

<div class="push30"></div>

<div class="row">

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fal fa-terminal"></i>
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

				<?php 
					$abaAdorai = 1833;
					include "abasAdorai.php";

					$abaManutencaoAdorai = 1864;
					//echo $abaUsuario;

					//se não for sistema de campanhas

					echo ('<div class="push20"></div>');
					include "abasManutencaoAdorai.php";
				?>

				<div class="push30"></div>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Dados Gerais</legend>

							<div class="col-md-2">
								<div class="form-group">
									<label for="inputName" class="control-label">Origem</label>
										<select data-placeholder="Selecione a origem" name="DES_ORIGEM" id="DES_ORIGEM" class="chosen-select-deselect" >
											<option value=""></option>
											<option value="SITE">Site</option>
											<option value="BUNKER">Bunker</option>
											<?php
												$sqlOrig = "SELECT DISTINCT DES_ORIGEM FROM ACESSOS_ADORAI WHERE COD_EMPRESA = $cod_empresa AND DES_ORIGEM NOT IN('SITE','BUNKER','') ORDER BY DES_ORIGEM";
												$arrayOrig = mysqli_query(connTemp($cod_empresa,''), $sqlOrig);

												while ($qrOrig = mysqli_fetch_assoc($arrayOrig)) {
											?>
													<option value="<?=$qrOrig[DES_ORIGEM]?>"><?=$qrOrig[DES_ORIGEM]?></option>
											<?php 
												}
											?>
										</select>
									<div class="help-block with-errors"></div>
									<script type="text/javascript">$("#DES_ORIGEM").val("<?=$des_origem?>").trigger("chosen:updated")</script>
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label for="inputName" class="control-label">Localidade</label>
										<select data-placeholder="Selecione a localidade" name="COD_HOTEL" id="COD_HOTEL" class="chosen-select-deselect">
											<option value=""></option>
											<?php
												$sqlHotel = "SELECT COD_EXTERNO, NOM_FANTASI FROM UNIDADEVENDA WHERE COD_EMPRESA = $cod_empresa AND LOG_ESTATUS = 'S'";
												$arrayHotel = mysqli_query(connTemp($cod_empresa,''), $sqlHotel);

												while ($qrHotel = mysqli_fetch_assoc($arrayHotel)) {
											?>
													<option value="<?=$qrHotel[COD_EXTERNO]?>"><?=$qrHotel[NOM_FANTASI]?></option>
											<?php 
												}
											?>
										</select>
									<div class="help-block with-errors"></div>
									<script type="text/javascript">$("#COD_HOTEL").val("<?=$cod_hotel?>").trigger("chosen:updated")</script>
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label for="inputName" class="control-label">Chalé</label>
										<div id="relatorioChale">
											<select data-placeholder="Selecione o chalé" name="COD_CHALE" id="COD_CHALE" class="chosen-select-deselect">
												<option value=""></option>
												<?php
													$sqlChale = "SELECT COD_EXTERNO, NOM_QUARTO FROM ADORAI_CHALES WHERE COD_EMPRESA = $cod_empresa AND COD_EXCLUSA = 0";
													$arrayChale = mysqli_query(connTemp($cod_empresa,''), $sqlChale);

													while ($qrChale = mysqli_fetch_assoc($arrayChale)) {
												?>
														<option value="<?=$qrChale[COD_EXTERNO]?>"><?=$qrChale[NOM_QUARTO]?></option>
												<?php 
													}
												?>
											</select>
										</div>
									<div class="help-block with-errors"></div>
									<script type="text/javascript">$("#COD_CHALE").val("<?=$cod_chale?>").trigger("chosen:updated")</script>
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label for="inputName" class="control-label required">Data Inicial</label>
									
									<div class="input-group date datePicker" id="DAT_INI_GRP">
										<input type='text' class="form-control input-sm data" name="DAT_INI" id="DAT_INI" value="<?php echo fnFormatDate($dat_ini); ?>" required/>
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
										<input type='text' class="form-control input-sm data" name="DAT_FIM" id="DAT_FIM" value="<?php echo fnFormatDate($dat_fim); ?>" required/>
										<span class="input-group-addon">
											<span class="glyphicon glyphicon-calendar"></span>
										</span>
									</div>
									<div class="help-block with-errors"></div>
								</div>
							</div>

							<div class="col-md-1">   
								<div class="form-group">
									<label for="inputName" class="control-label">Exibir legendas</label> 
									<div class="push5"></div>
										<label class="switch">
										<input type="checkbox" name="LOG_LABELS" id="LOG_LABELS" class="switch" value="S" <?=$checkLabels?>>
										<span></span>
										</label>
								</div>
							</div>						

						</fieldset>

						<div class="push10"></div>
						<hr>
						<div class="form-group text-right col-lg-12">

							<!-- <button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button> -->
							<button type="submit" name="BUS" id="BUS" class="btn btn-primary getBtn"><i class="fal fa-search" aria-hidden="true"></i>&nbsp; Pesquisar</button>
							<!-- <button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button> -->
							<!-- <button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fal fa-times" aria-hidden="true"></i>&nbsp; Excluir</button> -->

						</div>

						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">
						<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">

						<div class="push5"></div>

					</form>

					<div class="push20"></div>

					<div class="row">

						<div class="col-md-12">

							<div class="form-group text-center col-lg-12">
												
								<h4>Índice de acessos diários</h4>
								
								<div class="push20"></div>
								
								<div style="height: 300px; width:100%;">
									<canvas id="lineChart" ></canvas>
								</div>

							</div>

						</div>

					</div>

					<div class="push20"></div>

					<div class="row">
						
						<div class="col-md-12">

							<div class="form-group text-center col-lg-12">
								<h4>Acessos por origem</h4>
								<div class="push20"></div>

								<?php

									$sql = "SELECT COUNT(AD.COD_ACESSO) ACESSOS,
													AD.DES_ORIGEM,
													((COUNT(AD.COD_ACESSO)*100)/(SELECT COUNT(AD.COD_ACESSO) FROM ACESSOS_ADORAI AD WHERE AD.COD_EMPRESA = 274 AND AD.DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59')) PCT_ACESSOS
											FROM ACESSOS_ADORAI AD 
											WHERE AD.COD_EMPRESA = 274
											AND AD.DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
											GROUP BY DES_ORIGEM 
											ORDER BY COUNT(AD.COD_ACESSO) DESC";

									// FNeSCREVE($sql);

									// $sql = "SELECT AD.DES_ORIGEM, 
									// 			   COUNT(AD.COD_ACESSO) ACESSOS,
									// 			   (SUM(COUNT(AD.COD_ACESSO)))
									// 		FROM ACESSOS_ADORAI AD
									// 		WHERE AD.COD_EMPRESA = 274
									// 		AND AD.DAT_CADASTR BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
									// 		GROUP BY DES_ORIGEM					
									// 		ORDER BY COUNT(AD.COD_ACESSO) DESC";

									// fnEscreve($sql);

									$arrayQuery = mysqli_query(conntemp($cod_empresa,""), $sql);

									$origens = "";
									$acessosOrig = [];
									$countOrig = 0;

									while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery)) {

										$countOrig++;

								?>

										<div class="row">
											<div class="col-xs-3 slim text-right"><?=$qrBuscaModulos['DES_ORIGEM']?></div>
											<div class="col-xs-6 slim">
												<div class="progress">
													<div class="progress-bar active" role="progressbar" aria-valuenow="<?= fnvalorSql(fnValor($qrBuscaModulos[PCT_ACESSOS], 2)) ?>" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
														<span class="skill-name"><strong> <!-- <small>(<?=$qrBuscaModulos['ACESSOS']?>)</small> --></strong></span>
													</div>
												</div>
											</div>
											<div class="col-xs-3 slim text-left"><?= $qrBuscaModulos['ACESSOS'] ?></div>
										</div>

								<?php 


									}

								?>

								

								<!-- <canvas id="donut"></canvas>
								<div class="push5"></div> -->
							</div>

						</div>

					</div>

					<div class="push"></div>

				</div>

			</div>
		</div>
		<!-- fim Portlet -->
	</div>

</div>

<div class="push20"></div>

<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />

<script src="js/gauge.coffee.js" type="text/javascript"></script>

<!-- Versão compatível do chart js com as labels -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.min.js"></script>
<?php
	if($log_labels == 'S'){
?>
		<!-- Script dos labels -->
		<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.5.0"></script>

<?php
	}
?>

<script src="js/plugins/Chart_Js/utils.js"></script>

<script type="text/javascript">

	$(function(){

		$('.datePicker').datetimepicker({
			 format: 'DD/MM/YYYY'
			}).on('changeDate', function(e){
				$(this).datetimepicker('hide');
			});
		
		$("#DAT_INI_GRP").on("dp.change", function (e) {
			$('#DAT_FIM_GRP').data("DateTimePicker").minDate(e.date);
		});
		
		$("#DAT_FIM_GRP").on("dp.change", function (e) {
			$('#DAT_INI_GRP').data("DateTimePicker").maxDate(e.date);
		});

		//progress bar - índice de emissão de tickets - lojas
		$('.progress .progress-bar').css("width",
			function() {
				return $(this).attr("aria-valuenow") + "%";
			}
		)

		$("#COD_HOTEL").on('change', function(){

			$.ajax({
				type: "POST",
				url: "ajxChalesConsulta.do?id=<?=fnEncode($cod_empresa)?>",
				data: {COD_HOTEL: $("#COD_HOTEL").val()},
				beforeSend:function(){
					$('#relatorioChale').html('<div class="loading" style="width: 100%;"></div>');
				},
				success:function(data){
					$("#relatorioChale").html(data);										
				},
				error:function(data){
					console.log(data);
					$('#relatorioChale').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
				}
			});	

		});

		// Line chart
		var ctx = document.getElementById("lineChart");
		var lineChart = new Chart(ctx, {
			type: 'line',
			onAnimationComplete: new function () {

			},
			data: {
			  labels: <?php echo json_encode($indiceDias) ?>,
			  datasets: [{
			  	<?php if($log_labels == 'S'){ ?>
			  	datalabels: {
					clamp: true,
					align: 'start',
					anchor: 'start',
					borderRadius: 6,
					backgroundColor: '#36A2EB',
					color: '#fff',
					formatter: function(value) {
					    if(parseInt(value) >= 1000){
			                return value;
			              } else {
			                return value;
			              }
					    // eq. return ['line1', 'line2', value]
					}
				},
				<?php } ?>
				label: "Acessos",
				backgroundColor: "rgba(93, 173, 226, 0)",
				borderColor: "#36A2EB",
				pointBorderColor: "#36A2EB",
				pointBackgroundColor: "#fff",
				pointRadius: 4,
				pointBorderWidth: 3,
				data: <?php echo json_encode($pesquisasDia) ?>
			  }]
			},
			options: {
				legend: {
							display: true,
							position: 'bottom'
						},					
				maintainAspectRatio: false,
				animation: {
					duration: 2000,
				},
				scales: {
					yAxes: [{
						ticks: {
							beginAtZero: true,
							callback: function(value, index, values) {
				              if(parseInt(value) >= 1000){
				                return  value;
				              } else {
				                return  value;
				              }
				            }
						},
						afterTickToLabelConversion : function(object){
							for(var tick in object.ticks){
								object.ticks[tick];
							}
						}							
					}],
				},
				tooltips: {
			     callbacks: {
					    label: function (t, d) {
					        if (parseInt(t.yLabel)>=1000) {
					            return t.yLabel;
					        } else{
					            return t.yLabel;
					        }
					    }
					}
			    },
			}
		});

		//donut - índice de emissão de tickets
		var config = {
			type: 'doughnut',
			data: {
				datasets: [{
					data: <?=json_encode($acessosOrig)?>,
					backgroundColor: <?=json_encode($arrCorOrig,true)?>,
					<?php if($log_labels == 'S'){ ?>
				  	datalabels: {
						clamp: true,
						align: 'middle',
						anchor: 'end',
						borderRadius: 4,
						backgroundColor: <?=json_encode($arrCorOrigLbl,true)?>,
						color: '#fff',
						formatter: function(value) {
						    if(parseInt(value) >= 1000){
				                return value;
				              } else {
				                return value;
				              }
						    // eq. return ['line1', 'line2', value]
						}
					},
					<?php } ?>
				}],
				labels: [<?=$origens?>]
			},
			options: {
				//rotation: 1 * Math.PI,
				//circumference: 1 * Math.PI,
				// tooltips: {
				//         enabled: false
				//    },
				responsive: true,
				legend: {
					position: 'bottom',
				},
				title: {
					display: false,
					text: 'Índice de Emissão de Tickets'
				},
				animation: {
					animateScale: true,
					animateRotate: true
				}
			}
		};

		window.onload = function() {
			var ctx = document.getElementById("donut").getContext("2d");
			window.myDoughnut = new Chart(ctx, config);
		};

	});

</script>