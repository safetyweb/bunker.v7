<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$hashLocal = "";
$inicializador = "";
$hoje = "";
$dias30 = "";
$msgRetorno = "";
$msgTipo = "";
$dat_ini = "";
$dat_fim = "";
$num_cgcecpf = "";
$nom_cliente = "";
$andAno = "";
$andTurno = "";
$andEstado = "";
$andCidade = "";
$andCargo = "";
$hHabilitado = "";
$hashForm = "";
$cod_campanha = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$classeAno = "";
$classeTurno = "";
$classeEstado = "";
$classeCidade = "";
$classeCargo = "";
$formBack = "";
$qrAno = "";
$qrTurnos = "";
$qrEstado = "";
$qrCidade = "";


$itens_por_pagina = 50;
$pagina = 1;
$hashLocal = mt_rand();

$inicializador = 0;

//inicialização de variáveis
$hoje = fnFormatDate(date("Y-m-d"));
$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30 . '- 1 days')));

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_empresa = fnLimpaCampoZero(@$_POST['COD_EMPRESA']);
		$cod_univend = @$_POST['COD_UNIVEND'];
		$dat_ini = fnDataSql(@$_POST['DAT_INI']);
		$dat_fim = fnDataSql(@$_POST['DAT_FIM']);
		$cod_grupotr = @$_REQUEST['COD_GRUPOTR'];
		$cod_tiporeg = @$_REQUEST['COD_TIPOREG'];
		$num_cgcecpf = fnLimpaCampo(fnLimpaDoc(@$_REQUEST['NUM_CGCECPF']));
		$nom_cliente = fnLimpaCampo(@$_REQUEST['NOM_CLIENTE']);
		$andAno = preg_replace('/\\\\/', '', fnLimpaCampo(@$_REQUEST['FILTRO_ANO']));
		$andTurno = preg_replace('/\\\\/', '', fnLimpaCampo(@$_REQUEST['FILTRO_TURNO']));
		$andEstado = preg_replace('/\\\\/', '', fnLimpaCampo(@$_REQUEST['FILTRO_ESTADO']));
		$andCidade = preg_replace('/\\\\/', '', fnLimpaCampo(@$_REQUEST['FILTRO_CIDADE']));
		$andCargo = preg_replace('/\\\\/', '', fnLimpaCampo(@$_REQUEST['FILTRO_CARGO']));

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		if ($opcao != '') {
		}
	}
}

//busca dados url
if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode(@$_GET['id']);
	$cod_campanha = fnDecode(@$_GET['idc']);
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

//busca revendas do usuário
include "unidadesAutorizadas.php";

if ($andAno != '') {
	$classeAno = "filtrado";
	$inicializador = 1;
} else {
	$classeAno = "";
}
if ($andTurno != '') {
	$classeTurno = "filtrado";
	$inicializador = 1;
} else {
	$classeTurno = "";
}
if ($andEstado != '') {
	$classeEstado = "filtrado";
	$inicializador = 1;
} else {
	$classeEstado = "";
}
if ($andCidade != '') {
	$classeCidade = "filtrado";
	$inicializador = 1;
} else {
	$classeCidade = "";
}
if ($andCargo != '') {
	$classeCargo = "filtrado";
	$inicializador = 1;
} else {
	$classeCargo = "";
}

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

	.line {
		background-color: transparent !important;
		border: none;
		border-bottom: 1px solid #CCCCCC;
		border-radius: 0;
	}

	.line:focus {
		border-bottom-color: #4d4d4d;
	}

	.form-control-feedback {
		line-height: 45px !important;
		right: 0;
		text-align: center;
		width: 50px;

		font-size: 14px;
	}

	.mb-track-x {
		margin-bottom: -15px;
	}

	.filtrado,
	.filtrado:link,
	.filtrado:visited,
	.filtrado:active {
		text-decoration: none;
		font-weight: 1000;
	}
</style>

<link href="https://unpkg.com/minibarjs@latest/dist/minibar.min.css" rel="stylesheet" type="text/css">

<div class="push30 hidden-print"></div>

<div class="row" id="div_Report">

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">
			<div class="hidden-print">
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

			</div>
			<div class="portlet-body">

				<div class="hidden-print">

					<?php if ($msgRetorno <> '') { ?>
						<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<?php echo $msgRetorno; ?>
						</div>
					<?php } ?>

				</div>


				<div class="login-form">

					<div class="">

						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

							<fieldset>
								<legend class="hidden-print">Filtros</legend>

								<div class="row">

									<!-- search box -->
									<div class="col-sm-2 transparency">
										<div class="form-group">
											<span class="fal fa-search form-control-feedback"></span>
											<input type="text" class="form-control line" id="ano" onkeyup="buscaRegistro(this)" placeholder="">
										</div>
									</div>

									<!-- search box -->
									<div class="col-sm-2 transparency">
										<div class="form-group">
											<span class="fal fa-search form-control-feedback"></span>
											<input type="text" class="form-control line" id="turno" onkeyup="buscaRegistro(this)" placeholder="">
										</div>
									</div>

									<!-- search box -->
									<div class="col-sm-2 transparency">
										<div class="form-group">
											<span class="fal fa-search form-control-feedback"></span>
											<input type="text" class="form-control line" id="estado" onkeyup="buscaRegistro(this)" placeholder="" value="SP">
										</div>
									</div>

									<!-- search box -->
									<div class="col-sm-2 transparency">
										<div class="form-group">
											<span class="fal fa-search form-control-feedback"></span>
											<input type="text" class="form-control line" id="cidade" onkeyup="buscaRegistro(this)" placeholder="">
										</div>
									</div>

									<!-- search box -->
									<div class="col-sm-2 transparency">
										<div class="form-group">
											<span class="fal fa-search form-control-feedback"></span>
											<input type="text" class="form-control line" id="cargo" onkeyup="buscaRegistro(this)" placeholder="">
										</div>
									</div>


								</div>

								<div class="push10"></div>

								<div class="row">

									<div class="col-md-2">
										<h4 style="margin-top:0;" class="required">Ano</h4>
										<span class="text-danger required-ano" style="display: none;"><small>*O ano é obrigatório</small></span>

										<div class="slimscroll buscavel" id="box-ano" style="max-height: 100px; overflow:hidden;">

											<?php
											$sql = "SELECT ANO_ELEICAO FROM ANO_ELEICAO ORDER BY ANO_ELEICAO DESC";
											$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

											while ($qrAno = mysqli_fetch_assoc($arrayQuery)) {
											?>
												<div class="ano">
													<a class="activeRel" href="javascript:void(0)" onclick="carregaCargos(this,'<?= $qrAno['ANO_ELEICAO'] ?>')">&rsaquo; <?php echo $qrAno['ANO_ELEICAO'] ?> </a>
													<div class="push5"></div>
												</div>
											<?php
											}
											?>

										</div>

									</div>

									<div class="col-md-2">
										<h4 style="margin-top:0;" class="required">Turno</h4>
										<span class="text-danger required-turno" style="display: none;"><small>*O turno é obrigatório</small></span>

										<div class="slimscroll buscavel" id="box-turno" style="max-height: 100px; overflow:hidden;">

											<?php
											$sql = "SELECT NR_TURNO FROM TURNO ORDER BY NR_TURNO";
											$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

											while ($qrTurnos = mysqli_fetch_assoc($arrayQuery)) {
											?>
												<div class="turno">
													<a class="activeRel" href="javascript:void(0)" onclick="geraFiltro(this,'<?= $qrTurnos['NR_TURNO'] ?>')">&rsaquo; <?php echo $qrTurnos['NR_TURNO'] ?>° Turno </a>
													<div class="push5"></div>
												</div>
											<?php
											}
											?>

										</div>

									</div>

									<div class="col-md-2">
										<h4 style="margin-top:0;">Estado</h4>

										<div class="push5"></div>

										<div class="slimscroll buscavel" id="box-estado" style="max-height: 100px; overflow:hidden;">

											<?php
											$sql = "SELECT COD_ESTADO, UF FROM ESTADO ORDER BY UF";
											$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

											while ($qrEstado = mysqli_fetch_assoc($arrayQuery)) {
											?>
												<div class="estado">
													<a class="activeRel" href="javascript:void(0)" onclick="carregaCidades(this,'<?= $qrEstado['COD_ESTADO'] ?>','<?= $qrEstado['UF'] ?>')">&rsaquo; <?php echo $qrEstado['UF'] ?> </a>
													<div class="push5"></div>
												</div>
											<?php
											}
											?>

										</div>

									</div>

									<div class="col-md-2">
										<h4 style="margin-top:0;">Cidade</h4>

										<div class="push5"></div>

										<div class="slimscroll buscavel" id="box-cidade" style="max-height: 100px; overflow:hidden;">

											<!-- <?php
													$sql = "SELECT NOM_MUNICIPIO FROM MUNICIPIOS WHERE NOM_MUNICIPIO LIKE '%Tatuí%' ORDER BY NOM_MUNICIPIO";
													$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

													while ($qrCidade = mysqli_fetch_assoc($arrayQuery)) {
													?>
																<div class="cidade">
																	<a class="activeRel" href="javascript:void(0)">&rsaquo; <?php echo $qrCidade['NOM_MUNICIPIO'] ?> </a> 
																	<div class="push5"></div>
																</div>
															<?php
														}
															?> -->

										</div>

									</div>

									<div class="col-md-2">
										<h4 style="margin-top:0;">Cargo</h4>

										<div class="push5"></div>

										<div class="slimscroll buscavel" id="box-cargo" style="max-height: 100px; overflow:hidden;">

										</div>

									</div>

									<!-- search box -->
									<div class="col-md-2">
										<h4 style="margin-top:0;">Candidato</h4>
										<div class="form-group">
											<div class="push5"></div>
											<input type="text" class="form-control line" name="NM_CANDIDATO" id="NM_CANDIDATO" placeholder="">
										</div>
									</div>

								</div>

								<div class="row">

									<div class="col-md-2 hidden-print">
										<div class="push20"></div>
										<a href="javascript:void(0)" name="ALT" id="ALT" class="btn btn-primary btn-sm btn-block"><i class="fa fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</a>
									</div>

								</div>


							</fieldset>

							<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
							<input type="hidden" name="FILTRO_ANO" id="FILTRO_ANO" value="" />
							<input type="hidden" name="FILTRO_TURNO" id="FILTRO_TURNO" value="" />
							<input type="hidden" name="FILTRO_ESTADO" id="FILTRO_ESTADO" value="" />
							<input type="hidden" name="FILTRO_CIDADE" id="FILTRO_CIDADE" value="" />
							<input type="hidden" name="FILTRO_CARGO" id="FILTRO_CARGO" value="" />
							<input type="hidden" name="opcao" id="opcao" value="">
							<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
							<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

							<div class="push5"></div>

						</form>

					</div>

					<div class="push20"></div>

					<div>
						<div class="row">
							<div class="col-md-12">

								<div class="push20"></div>

								<div id="relatorioConteudo"></div>

							</div>


						</div>
					</div>

					<div class="push50 hidden-print"></div>

					<div class="push"></div>

				</div>

			</div>
		</div>
		<!-- fim Portlet -->
	</div>

</div>

<div class="push20 hidden-print"></div>

<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />
<script src="https://unpkg.com/minibarjs@latest/dist/minibar.min.js" type="text/javascript"></script>
<script src="https://rawgit.com/mplatt/fold-to-ascii/eae6030cc155a59fe7859666b4fb45171c67a17f/fold-to-ascii.js"></script>

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

		MiniBarOptions = {
			barType: "default",
			minBarSize: 10,
			hideBars: false,
			/* v0.4.0 and above */
			alwaysShowBars: true,
			horizontalMouseScroll: false,

			scrollX: true,
			scrollY: true,

			navButtons: false,
			scrollAmount: 10,

			mutationObserver: {
				attributes: false,
				childList: true,
				subtree: true
			},

			/* v0.4.0 and above */
			onInit: function() {

			},

			/* v0.4.0 and above */
			onUpdate: function() {
				/* do something on update */
			},

			/* v0.4.0 and above */
			onScroll: function() {
				/* do something on init */
			},

			classes: {
				container: "mb-container",
				content: "mb-content",
				track: "mb-track",
				bar: "mb-bar",
				visible: "mb-visible",
				progress: "mb-progress",
				hover: "mb-hover",
				scrolling: "mb-scrolling",
				textarea: "mb-textarea",
				wrapper: "mb-wrapper",
				nav: "mb-nav",
				btn: "mb-button",
				btns: "mb-buttons",
				increase: "mb-increase",
				decrease: "mb-decrease",
				item: "mb-item",
				/* v0.4.0 and above */
				itemVisible: "mb-item-visible",
				/* v0.4.0 and above */
				itemPartial: "mb-item-partial",
				/* v0.4.0 and above */
				itemHidden: "mb-item-hidden" /* v0.4.0 and above */
			}
		};

		new MiniBar('#box-ano', MiniBarOptions);
		new MiniBar('#box-turno', MiniBarOptions);
		new MiniBar('#box-cargo', MiniBarOptions);

		buscaRegistro($('#estado'));
		new MiniBar('#box-estado', MiniBarOptions);

		// $('.mb-container').css('maxHeight','100px');

		$("#ALT").click(function(e) {

			e.preventDefault();

			ano = $("#FILTRO_ANO").val(),
				turno = $("#FILTRO_TURNO").val(),
				candidato = $("#NM_CANDIDATO").val();

			if (ano == "" || turno == "") {

				if (ano == "") {
					$(".required-ano").show();
				}

				if (turno == "") {
					$(".required-turno").show();
				}

			} else {

				$.ajax({
					type: "POST",
					url: "relatorios/ajxRelEleicoes.do?id=<?= fnEncode($cod_empresa) ?>&idPage=1&itens_por_pagina=<?php echo $itens_por_pagina; ?>",
					data: $("#formulario").serialize(),
					beforeSend: function() {
						$('#relatorioConteudo').html('<div class="loading" style="width: 100%;"></div>');
					},
					success: function(data) {
						$("#relatorioConteudo").html(data);
					},
					error: function() {
						$('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> cidades não encontradas...</p>');
					}
				});

			}

		});

	});

	function geraFiltro(el, cod_el) {

		if (!$(el).hasClass('filtrado')) {

			tipo = $(el).parent().attr('class');

			if (tipo == "ano") {
				filtro = "AND ANO_ELEICAO = '" + cod_el + "'";
				$(".required-ano").hide();
			} else if (tipo == "turno") {
				filtro = "AND NR_TURNO = '" + cod_el + "'";
				$(".required-turno").hide();
			} else if (tipo == "estado") {
				filtro = "AND SG_UF = '" + cod_el + "'";
			} else if (tipo == "cidade") {
				filtro = "AND NM_MUNICIPIO LIKE '%" + cod_el + "%'";
			} else {
				filtro = "AND CD_CARGO = '" + cod_el + "'";
			}

			$("#FILTRO_" + tipo.toUpperCase()).val(filtro);

			$("." + tipo + " a").removeClass('filtrado');

			$(el).addClass('filtrado');

		} else {

			$("#FILTRO_" + tipo.toUpperCase()).val('');
			$(el).removeClass('filtrado');

		}

	}

	function carregaCidades(el, cod_estado, uf) {
		$.ajax({
			type: "POST",
			url: "relatorios/ajxCarregaCidades.do?id=<?= fnEncode($cod_empresa) ?>&uf=" + cod_estado,
			beforeSend: function() {
				$('#box-cidade').html('<tr><td colspan="5" class="text-center"><div class="loading" style="width: 100%;"></div></td></tr>');
			},
			success: function(data) {
				$("#box-cidade").html(data);
				new MiniBar('#box-cidade', MiniBarOptions);
				geraFiltro(el, uf);
			},
			error: function() {
				$('#cidade').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> cidades não encontradas...</p>');
			}
		});
	}

	function carregaCargos(el, des_ano) {
		$.ajax({
			type: "POST",
			url: "relatorios/ajxCarregaAnoEleicao.do?id=<?= fnEncode($cod_empresa) ?>",
			data: {
				DES_ANO: des_ano
			},
			beforeSend: function() {
				$('#box-cargo').html('<tr><td colspan="5" class="text-center"><div class="loading" style="width: 100%;"></div></td></tr>');
			},
			success: function(data) {
				$("#box-cargo").html(data);
				new MiniBar('#box-cargo', MiniBarOptions);
				geraFiltro(el, des_ano);
			},
			error: function() {
				$('#cargo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> cargos não encontrados...</p>');
			}
		});
	}

	function reloadPage(idPage) {
		$.ajax({
			type: "POST",
			url: "relatorios/ajxCadApoiador.do?id=<?php echo fnEncode($cod_empresa); ?>&idPage=" + idPage + "&itens_por_pagina=<?php echo $itens_por_pagina; ?>&opcao=paginar",
			data: $('#formulario').serialize(),
			beforeSend: function() {
				$('#relatorioConteudo').html('<div class="loading" style="width: 100%;"></div>');
			},
			success: function(data) {
				$("#relatorioConteudo").html(data);
				console.log(data);
			},
			error: function() {
				$('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
			}
		});
	}

	function buscaRegistro(el) {

		var value = foldToASCII($(el).val().toLowerCase().trim()),
			tipo = $(el).attr('id');

		$(".buscavel ." + tipo + ' a').each(function() {
			var id = foldToASCII($(this).text().replace('›', '').toLowerCase().trim());
			var sem_registro = (id.indexOf(value) == -1);
			$(this).parent().toggle(!sem_registro);
		});

	}
</script>