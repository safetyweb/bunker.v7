<?php

//echo fnDebug('true');

$log_ativo = 'N';
$lista_gerada = 0;
$contatos_graph = 0;
$nentregues_graph = 0;
$disparados_graph = 0;
$sucesso_graph = 0;
$falha_graph = 0;
$optout_graph = 0;
$aguardo_graph = 0;
$nrecebido_graph = 0;

if (isset($_GET['pop'])) {
	$popUp = fnLimpaCampo($_GET['pop']);
} else {
	$popUp = '';
}

$cod_template = "";

$hashLocal = mt_rand();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_template = fnLimpaCampoZero($_REQUEST['COD_TEMPLATE']);
		$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
		if (empty($_REQUEST['LOG_ATIVO'])) {
			$log_ativo = 'N';
		} else {
			$log_ativo = $_REQUEST['LOG_ATIVO'];
		}
		$nom_template = fnLimpaCampo($_REQUEST['NOM_TEMPLATE']);
		$abv_template = fnLimpaCampo($_REQUEST['ABV_TEMPLATE']);
		$des_template = fnLimpaCampo($_REQUEST['DES_TEMPLATE']);

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

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

//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {

	//busca dados da empresa
	$cod_campanha = fnDecode($_GET['idc']);
	$cod_empresa = fnDecode($_GET['id']);
	$sql = "SELECT NOM_EMPRESA FROM EMPRESAS WHERE COD_EMPRESA = " . $cod_empresa;

	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error());
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($qrBuscaEmpresa)) {
		$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
	}
} else {
	$nom_empresa = "";
}

$log_labels = "S";

$sqlGraph = "SELECT SUM(CEM.QTD_DISPARADOS) AS QTD_DISPARADOS, 
					SUM(CEM.QTD_SUCESSO) AS QTD_SUCESSO, 
					SUM(CEM.QTD_NRECEBIDO) AS QTD_NENTREGUES,  
					SUM(CEM.QTD_OPTOUT) AS QTD_OPTOUT, 
					SUM(CEM.QTD_FALHA) AS BOUNCE,
					SUM(CEM.QTD_AGUARADANDO) AS QTD_AGUARDO,
					SUM(CEM.QTD_NRECEBIDO) AS QTD_NRECEBIDO,
					SUM(EL.QTD_LISTA) AS QTD_LISTA
					FROM WHATSAPP_LOTE EL
					LEFT JOIN CONTROLE_ENTREGA_WHATSAPP CEM ON EL.COD_DISPARO_EXT = CEM.COD_DISPARO
														  AND CEM.cod_empresa=EL.COD_EMPRESA 
				                                          AND CEM.cod_campanha=EL.COD_CAMPANHA
				                                          AND CEM.LOG_TESTE=EL.LOG_TESTE
					LEFT JOIN TEMPLATE_WHATSAPP TE ON TE.COD_EXT_TEMPLATE = CEM.ID_TEMPLETE AND TE.LOG_ATIVO = 'S'
					WHERE CEM.COD_EMPRESA = $cod_empresa
					AND CEM.COD_CAMPANHA = $cod_campanha 
					AND EL.LOG_ENVIO = 'S' 
					AND CEM.COD_DISPARO IS NOT NULL
					GROUP BY CEM.LOG_TESTE,CEM.COD_CAMPANHA;";

// fnEscreve($sqlGraph);
// exit();

$arrayGraph = mysqli_query(connTemp($cod_empresa, ''), $sqlGraph);

while ($qrGraph = mysqli_fetch_assoc($arrayGraph)) {

	$lista_gerada += $qrGraph['QTD_LISTA'];

	$contatos_graph += $qrGraph['QTD_LISTA'];
	$nentregues_graph += $qrGraph['QTD_NENTREGUES'];
	$disparados_graph += $qrGraph['QTD_DISPARADOS'];
	$sucesso_graph += $qrGraph['QTD_SUCESSO'];
	$falha_graph += $qrGraph['BOUNCE'];
	$optout_graph += $qrGraph['QTD_OPTOUT'];
	$aguardo_graph += $qrGraph['QTD_AGUARDO'];
	$nrecebido_graph += $qrGraph['QTD_NRECEBIDO'];
}

$perc_sucesso = fnValorSql(fnValor(($sucesso_graph / $contatos_graph) * 100, 2));
$perc_nentregue = fnValorSql(fnValor(($nentregues_graph / $contatos_graph) * 100, 2));
$perc_falha = fnValorSql(fnValor(($falha_graph / $contatos_graph) * 100, 2));
$perc_optout = fnValorSql(fnValor(($optout_graph / $contatos_graph) * 100, 2));
$perc_aguardo = fnValorSql(fnValor(($aguardo_graph / $contatos_graph) * 100, 2));
$perc_nrecebido = fnValorSql(fnValor(($nrecebido_graph / $contatos_graph) * 100, 2));

$mostraRefresh = 'true';
$segundos = 0;

if (isset($_COOKIE['TEMPO_REFRESH_WHATSAPP']) && $_COOKIE['TEMPO_REFRESH_WHATSAPP'] == true) {


	$mostraRefresh = 'false';
	$cookie = json_decode($_COOKIE["TEMPO_REFRESH_WHATSAPP"]);
	$expira_refresh = $cookie->data->datExpira;

	$start_date = new DateTime($expira_refresh);
	$since_start = $start_date->diff(new DateTime(date("Y-m-d H:i:s")));

	// echo $since_start->i.' minutes<br>';
	// echo $since_start->s.' seconds<br>';

	$segundos = $since_start->i * 60;
	$segundos += $since_start->s;
	// echo $segundos.' seconds';

	// print_r($segundos);
	//    exit();
}

// fnEscreve($segundos);

?>

<style>
	body {
		overflow: hidden;
		/*overflow-x: scroll;*/
	}

	.change-icon .fa+.fa,
	.change-icon:hover .fa:not(.fa-edit) {
		display: none;
	}

	.change-icon:hover .fa+.fa:not(.fa-edit) {
		display: inherit;
	}

	.fa-edit:hover {
		color: #18bc9c;
		cursor: pointer;
	}

	.tile {
		cursor: pointer;
	}

	.item {
		padding-top: 0;
	}

	.circle {
		width: 120px;
		margin: 6px 6px 20px;
		display: inline-block;
		position: relative;
		text-align: center;
		line-height: 1.2;
	}

	.circle canvas {
		vertical-align: top;
		width: 120px !important;
	}

	.circle strong {
		position: absolute;
		top: 23.5%;
		left: 0;
		width: 100%;
		text-align: center;
		line-height: 40px;
		font-size: 16px;
		font-weight: normal !important;
		color: #17202A;
	}

	.circle strong i {
		font-style: normal;
		font-size: 0.6em;
		font-weight: normal;
	}

	.circle span {
		display: block;
		color: #aaa;
		margin-top: 12px;
	}

	.c1 {
		color: #cecece;
	}

	.c2 {
		color: #808B96;
	}

	.c3 {
		color: #17202A;
	}

	.vertical {
		margin-left: auto !important;
		margin-right: auto !important;
		float: unset !important;
		box-shadow: unset !important;
		background-color: rgb(247, 247, 247) !important;
	}

	#blocker {
		display: none;
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		opacity: .8;
		background-color: #fff;
		z-index: 1000;
		cursor: wait;
	}

	#blocker div {
		position: absolute;
		top: 30%;
		left: 48%;
		width: 200px;
		height: 2em;
		margin: -1em 0 0 -2.5em;
		color: #000;
		font-weight: bold;
	}
</style>
<!-- 	<link rel="stylesheet" href="css/widgets.css" /> -->
<link rel="stylesheet" type="text/css" href="js/plugins/bootstrap-progressbar-master/css/bootstrap-progressbar-3.3.4.min.css">

<div id="blocker">
	<div style="text-align: center;"><img src="images/loading2.gif"><br /> Aguarde. Processando... ;-)<br /><small>(este processo pode demorar vários minutos)</small></div>
</div>

<div class="row portlet">

	<div class="col-xs-12 margin-bottom-30">

		<div class="portlet-body">

			<div class="col-xs-6">

				<h4 style="margin: 0 0 5px 0;"><span class="bolder">Resumo Geral de Entregabilidade</span></h4>

			</div>

			<div class="col-xs-6 text-right" id="refreshDisparos">

				<?php if ($mostraRefresh == "true") { ?>

					<a href="javascript:void(0)" class="btn btn-xs btn-info" data-toggle='tooltip' data-placement='left' data-original-title='Atualizar dados' onclick="atualizaDisparos()"><span class="fal fa-sync"></span> Atualizar relatório</a>

				<?php } else { ?>

					<a href="javascript:void(0)" class="btn btn-xs btn-info disabled" disabled>Atualização disponível em <div id="timer_div"></div> segundos</a>

				<?php } ?>

			</div>

			<div class="push30"></div>

			<!-- <div class="col-xs-12 text-center">

					<div class="col-sm-3 text-center">
						<div class="push50"></div>
						<i class="fal fa-paper-plane c2 fa-4x"></i>
						<div class="push20"></div>
						<h5 style="color: #aaa; margin-bottom: 0px; font-size: 12px;">LISTA DE ENVIO</h5>
						<div class="push"></div>
						<span class="c3 f16"><p style="color: #17202A"><?= fnValor($contatos_graph, 0) ?></p></span>
						<div class="push20"></div>
					</div>

					<div class="col-sm-3">
					    <div class="progressbar">
			            <div class="second circle" data-percent="<?= fnValor($perc_sucesso, 2) ?>">
			              <strong></strong>
			              <span style="font-size: 12px;">ENTREGUES <br/><p class="f18" style="color: #17202A"><?= $sucesso_graph ?></p></span>
			            </div>
			            </div>
					</div>
					
					<div class="col-sm-3">
					    <div class="progressbar">
			            <div class="second circle" data-percent="<?= fnValor($perc_lidos, 2) ?>" data-emptyFill="rgba(205, 42, 25, 1)">
			              <strong></strong>
			              <span style="font-size: 12px;">LIDOS <br/><p class="f18" style="color: #17202A"><?= $lidos_graph ?></p></span>
			            </div>
			            </div>
					</div>
					
					<div class="col-sm-3">
					    <div class="progressbar">
			            <div class="second circle" data-percent="<?= fnValor($perc_cliques, 2) ?>">
			              <strong></strong>
			              <span style="font-size: 12px;">CLIQUES <br/><p class="f18" style="color: #17202A"><?= $cliques_graph ?></p></span>
			            </div>
			            </div>
					</div>

				</div> -->

			<!-- <div class="push50"></div> -->

			<div class="col-xs-12">

				<div class="flexrow" style="height: 200px;">

					<div class="col text-center">
						<div class="push100"></div>
						<div class="push30"></div>
						<div class="push" style="height: 2.5px;"></div>
						<i class="fal fa-paper-plane c2 fa-4x"></i>
						<div class="push20"></div>
						<h5 style="color: #aaa; margin-bottom: 0px; font-size: 12px;">LISTA DE ENVIO</h5>
						<div class="push"></div>
						<span class="c3 f16">
							<p style="color: #17202A"><?= fnValor($contatos_graph, 0) ?></p>
						</span>
						<div class="push20"></div>
					</div>

					<div class="col text-center">

						<div class="progress vertical bottom" style="align-self: center;">
							<div class="progress-bar" role="progressbar" data-transitiongoal="<?= $perc_sucesso ?>" style="background-color: #5BC0DE!important"></div>
						</div>

						<div class="push"></div>

						<span style="font-size: 12px; color: #aaa;">ENTREGUES</span>

						<div class="push"></div>

						<p class="f18" style="color: #17202A; margin-bottom:0;"><?= $sucesso_graph ?></p>

						<div class="push"></div>

						<p class="text-muted" style="font-size: 12px;"><?= fnValor($perc_sucesso, 2) ?>%</p>

					</div>

					<div class="col text-center">

						<div class="progress vertical bottom" style="align-self: center;">
							<div class="progress-bar" role="progressbar" data-transitiongoal="<?= $perc_nrecebido ?>" style="background-color: #A3D2DF!important"></div>
						</div>

						<div class="push"></div>

						<span style="font-size: 12px; color: #aaa;">NÃO RECEBIDOS</span>

						<div class="push"></div>

						<p class="f18" style="color: #17202A; margin-bottom:0;"><?= $nrecebido_graph ?></p>

						<div class="push"></div>

						<p class="text-muted" style="font-size: 12px;"><?= fnValor($perc_nrecebido, 2) ?>%</p>

					</div>

					<!-- <div class="col text-center">

							<div class="progress vertical bottom" style="align-self: center;">
							  <div class="progress-bar" role="progressbar" data-transitiongoal="<?= $perc_lidos ?>" style="background-color: #44E2A6!important"></div>
							</div>

							<div class="push"></div>

							<span style="font-size: 12px; color: #aaa;">LIDOS</span>

							<div class="push"></div>

							<p class="f18" style="color: #17202A; margin-bottom:0;"><?= $lidos_graph ?></p>

							<div class="push"></div>

							<p class="text-muted" style="font-size: 12px;"><?= fnValor($perc_lidos, 2) ?>%</p>

						</div> -->


					<div class="col text-center">

						<div class="progress vertical bottom" style="align-self: center;">
							<div class="progress-bar" role="progressbar" data-transitiongoal="<?= $perc_optout ?>" style="background-color: #EF8686!important"></div>
						</div>

						<div class="push"></div>

						<span style="font-size: 12px; color: #aaa;">OPT OUT</span>

						<div class="push"></div>

						<p class="f18" style="color: #17202A; margin-bottom:0;"><?= $optout_graph ?></p>

						<div class="push"></div>

						<p class="text-muted" style="font-size: 12px;"><?= fnValor($perc_optout, 2) ?>%</p>

					</div>

					<!-- <div class="col text-center">

							<div class="progress vertical bottom" style="align-self: center;">
							  <div class="progress-bar" role="progressbar" data-transitiongoal="<?= $perc_soft ?>" style="background-color: #EBBE5C!important"></div>
							</div>

							<div class="push"></div>

							<span style="font-size: 12px; color: #aaa;">SOFT BOUNCE</span>

							<div class="push"></div>

							<p class="f18" style="color: #17202A; margin-bottom:0;"><?= $graph_soft ?></p>

							<div class="push"></div>

							<p class="text-muted" style="font-size: 12px;"><?= fnValor($perc_soft, 2) ?>%</p>

						</div> -->

					<div class="col text-center">

						<div class="progress vertical bottom" style="align-self: center;">
							<div class="progress-bar" role="progressbar" data-transitiongoal="<?= $perc_falha ?>" style="background-color: #BFB6F2!important"></div>
						</div>

						<div class="push"></div>

						<span style="font-size: 12px; color: #aaa;">FALHAS</span>

						<div class="push"></div>

						<p class="f18" style="color: #17202A; margin-bottom:0;"><?= $falha_graph ?></p>

						<div class="push"></div>

						<p class="text-muted" style="font-size: 12px;"><?= fnValor($perc_falha, 2) ?>%</p>

					</div>

					<div class="col text-center">

						<div class="progress vertical bottom" style="align-self: center;">
							<div class="progress-bar" role="progressbar" data-transitiongoal="<?= $perc_aguardo ?>" style="background-color: #967A60!important"></div>
						</div>

						<div class="push"></div>

						<span style="font-size: 12px; color: #aaa;">EM AGUARDO</span>

						<div class="push"></div>

						<p class="f18" style="color: #17202A; margin-bottom:0;"><?= $aguardo_graph ?></p>

						<div class="push"></div>

						<p class="text-muted" style="font-size: 12px;"><?= fnValor($perc_aguardo, 2) ?>%</p>

					</div>

				</div>

				<div class="push100"></div>

			</div>

			<div class="push50"></div>

			<div class="col-xs-2">

				<div class="dropdown">
					<a class="dropdown-toggle btn btn-info" data-toggle="dropdown" href="#">
						<span class="fal fa-file-excel"></span> Exportar
					</a>
					<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
						<li><a tabindex="-1" href="javascript:void(0);" onclick="parent.exportaRel('geral',0)">Resumo Geral</a></li>
						<li><a tabindex="-1" href="javascript:void(0);" onclick="parent.exportaRel('all',0)">Lista enviada</a></li>
						<li><a tabindex="-1" href="javascript:void(0);" onclick="parent.exportaRel('sent',0)">Entregues</a></li>
						<li><a tabindex="-1" href="javascript:void(0);" onclick="parent.exportaRel('notsent',0)">Não Recebidos</a></li>
						<li><a tabindex="-1" href="javascript:void(0);" onclick="parent.exportaRel('optout',0)">Opt-Out</a></li>
						<li><a tabindex="-1" href="javascript:void(0);" onclick="parent.exportaRel('bounce',0)">Falhas</a></li>
						<li><a tabindex="-1" href="javascript:void(0);" onclick="parent.exportaRel('wait',0)">Em Aguardo</a></li>
						<!-- <li class="divider"></li> -->
						<!-- <li><a tabindex="-1" href="#">Separated link</a></li> -->
					</ul>
				</div>

			</div>

			<div class="col-xs-2">

				<a class="btn btn-info" href="https://adm.bunker.mk/action.do?mod=<?php echo fnEncode(1662) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank"><span class="fal fa-list"></span> Relatório detalhado por campanha</a>

			</div>

			<div class="push100"></div>
			<div class="push100"></div>
			<div class="push100"></div>
			<div class="push100"></div>
			<div class="push100"></div>

		</div>

	</div>

</div>

<style>
	body {
		overflow: hidden;
		overflow-x: scroll;
	}

	.change-icon .fa+.fa,
	.change-icon:hover .fa:not(.fa-edit) {
		display: none;
	}

	.change-icon:hover .fa+.fa:not(.fa-edit) {
		display: inherit;
	}

	.fa-edit:hover {
		color: #18bc9c;
		cursor: pointer;
	}

	.tile {
		cursor: pointer;
	}

	.item {
		padding-top: 0;
	}
</style>

<input type="hidden" class="input-sm" name="REFRESH_TEMPLATES" id="REFRESH_TEMPLATES" value="N">

<!-- modal -->
<!-- <div class="modal fade" id="popModal" tabindex='-1'>
		<div class="modal-dialog" style="">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title"></h4>
				</div>
				<div class="modal-body">
					<iframe frameborder="0" style="width: 100%; height: 80%"></iframe>
				</div>		
			</div>
		</div>
	</div>	 -->

<!-- <div class="push100"></div>
	<div class="push100"></div> -->

<script src="js/gauge.coffee.js" type="text/javascript"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.min.js"></script> 
	<script src="js/pie-chart.js"></script>
    <script src="js/plugins/Chart_Js/utils.js"></script> -->
<script src="js/plugins/bootstrap-progressbar-master/bootstrap-progressbar.js"></script>
<script src="https://rawgit.com/kottenator/jquery-circle-progress/1.2.2/dist/circle-progress.js"></script>
<!-- <script type="text/javascript" src="js/plugins/jquery.sparkline.min.js"></script> -->
<?php
if ($log_labels == 'S') {
?>
	<!-- Script dos labels -->
	<!-- <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.5.0"></script> -->

<?php
}
?>

<script type="text/javascript">
	<?php
	if ($log_labels == 'S') {
	?>
		// Chart.plugins.unregister(ChartDataLabels);
	<?php
	}
	?>

	$(document).ready(function() {

		$('.progress .progress-bar').progressbar();


		parent.$("#conteudoAba").css("height", $('.portlet').height() + "px");
		// $(".tablesorter").bind("tablesorter-initialized",function(e, table) {
		// });

		if ("<?= fnLimpaCampoZero($segundos) ?>" == 0) {
			// atualizaDisparos();
		}

	});

	function animateElements() {
		$('.progressbar').each(function() {
			var elementPos = $(this).offset().top;
			var topOfWindow = $(window).scrollTop();
			var percent = $(this).find('.circle').attr('data-percent');
			var animate = $(this).data('animate');
			if (elementPos < topOfWindow + $(window).height() - 30 && !animate) {
				$(this).data('animate', true);
				$(this).find('.circle').circleProgress({
					// startAngle: -Math.PI / 2,
					value: percent / 100,
					size: 400,
					thickness: 35,
					fill: {
						color: '#5bc0de'
					}
				}).on('circle-animation-progress', function(event, progress, stepValue) {
					$(this).find('strong').text((stepValue * 100).toFixed(0) + "%");
				}).stop();
			}
		});

	}

	function secondsTimeSpanToHMS(s) {
		var m = Math.floor(s / 60); //Get remaining minutes
		s -= m * 60;
		return (m < 10 ? '0' + m : m) + ":" + (s < 10 ? '0' + s : s); //zero padding on minutes and seconds
	}

	animateElements();
	$(window).scroll(animateElements);

	var seconds_left = "<?= $segundos ?>";
	// alert(seconds_left);

	if (seconds_left > 0) {

		var interval = setInterval(function() {
			seconds_left--;
			document.getElementById('timer_div').innerHTML = secondsTimeSpanToHMS(seconds_left);

			if (seconds_left <= 0) {
				document.getElementById('refreshDisparos').innerHTML = '<a href="javascript:void(0)" class="btn btn-xs btn-info" data-toggle="tooltip" data-placement="left" data-original-title="Atualizar dados" onclick="atualizaDisparos()"><span class="fal fa-sync"></span> Atualizar relatório</a>';
				clearInterval(interval);
			}
		}, 1000);

	}

	function atualizaDisparos() {
		$.ajax({
			type: "POST",
			url: "ajxRefreshResultadosWhatsApp_V2.php",
			data: {
				COD_EMPRESA: "<?= fnEncode($cod_empresa) ?>",
				COD_CAMPANHA: "<?= fnEncode($cod_campanha) ?>"
			},
			beforeSend: function() {
				$('#blocker').show();
			},
			success: function(data) {
				window.location.href = "action.php?mod=<?php echo fnEncode(1649) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idc=<?= fnEncode($cod_campanha) ?>&pop=true";
				// console.log(data);
			},
			error: function() {
				alert("Erro no carregamento...");
			}
		});
	}

	function RefreshTemplates(idEmp) {
		$.ajax({
			type: "GET",
			url: "ajxRefreshTemplatesWhatsApp.php",
			data: {
				ajx1: idEmp
			},
			beforeSend: function() {
				$('#listaTemplates').html('<div class="loading" style="width: 100%;"></div>');
			},
			success: function(data) {
				$("#listaTemplates").html(data);
			},
			error: function() {
				$('#listaTemplates').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
			}
		});
	}

	function retornaForm(index) {
		$("#formulario #COD_CONVENI").val($("#ret_COD_CONVENI_" + index).val());
		$("#formulario #COD_EMPRESA").val($("#ret_COD_EMPRESA_" + index).val());
		$("#formulario #COD_ENTIDAD").val($("#ret_COD_ENTIDAD_" + index).val()).trigger("chosen:updated");
		$("#formulario #NUM_PROCESS").val($("#ret_NUM_PROCESS_" + index).val());
		$("#formulario #NUM_CONVENI").val($("#ret_NUM_CONVENI_" + index).val());
		$("#formulario #NOM_CONVENI").val($("#ret_NOM_CONVENI_" + index).val());
		$("#formulario #NOM_ABREVIA").val($("#ret_NOM_ABREVIA_" + index).val());
		$("#formulario #DES_DESCRIC").val($("#ret_DES_DESCRIC_" + index).val());
		$("#formulario #VAL_VALOR").unmask().val($("#ret_VAL_VALOR_" + index).val());
		$("#formulario #VAL_CONTPAR").unmask().val($("#ret_VAL_CONTPAR_" + index).val());
		$("#formulario #DAT_INICINV").unmask().val($("#ret_DAT_INICINV_" + index).val());
		$("#formulario #DAT_FIMCONV").unmask().val($("#ret_DAT_FIMCONV_" + index).val());
		$("#formulario #DAT_ASSINAT").unmask().val($("#ret_DAT_ASSINAT_" + index).val());
		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
	}
</script>