<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$dat_ini = "";
$hashLocal = "";
$connboard = "";
$hoje = "";
$dias30 = "";
$sqlUsuario = "";
$arrUsu = "";
$qrUsu = "";
$Arr_COD_EMPRESA = "";
$i = "";
$cod_empresas = "";
$cod_empresas_combo = "";
$msgRetorno = "";
$msgTipo = "";
$dat_ini_campo = "";
$num_cgcecpf = "";
$nom_cliente = "";
$cod_segment = "";
$Arr_COD_SISTEMAS = "";
$cod_sistemas = "";
$filtro = "";
$val_pesquisa = "";
$opcao = "";
$hHabilitado = "";
$hashForm = "";
$cod_campanha = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$dat_fim = "";
$andEmpresas = "";
$sqlTotal = "";
$arrayTotal = [];
$qrTotal = "";
$totCli = "";
$totVenda = "";
$totCupom = "";
$totAtend = "";
$totCliPeriodo = "";
$totAvulsa = "";
$esconde = "";
$formBack = "";
$qrListaEmpresa = "";
$desabilitado = "";
$qrBuscaSistema = "";
$sistemasMarka = "";
$qrListaSistemas = "";
$mostraAutoriza = "";
$qrLista = "";
$mes = "";
$arrayMeses = [];
$mesCompara = "";
$andSegment = "";
$codSistemas = "";
$andSistemas = "";
$qrTask = "";
$chave_linha = "";
$sqlCompara = "";
$qrCompara = "";
$totalFidelizado = 0;
$totalFidCompara = 0;
$itensFidelizado = "";
$itensFidCompara = "";
$totalResgate = 0;
$totalResCompara = 0;
$totalResgateFid = 0;
$totalResFidCompara = 0;
$totalTransac = 0;
$totalTransacCompara = 0;
$totVariacaoTM = "";
$totVariacaoTMCompara = "";
$totVariacaoTMFid = "";
$totVariacaoTMFidCompara = "";
$totCadXacesso = "";
$totCadXacessoCompara = "";
$totalExpira = 0;
$totalExpiraCompara = 0;
$totalResGeral = 0;
$totalResGeralCompara = 0;
$totalCredExpira = 0;
$totalCredExpiraCompara = 0;
$totalIndiceFideliz = 0;
$totalIndiceFidelizCompara = 0;
$totalFaturamento = 0;
$totalFaturamentoCompara = 0;
$icone22 = "";
$icone21 = "";
$pontosTotalFreq = "";
$icone20 = "";
$icone19 = "";
$icone18 = "";
$icone14 = "";
$pontosVaria = "";
$icone24 = "";
$icone15 = "";
$icone13 = "";
$icone12 = "";
$icone11 = "";
$pontosFideliza = "";
$icone0 = "";
$pontosTotCli = "";
$icone1 = "";
$icone2 = "";
$icone3 = "";
$icone4 = "";
$icone5 = "";
$icone6 = "";
$icone7 = "";
$icone8 = "";
$icone9 = "";
$pontosTotal = "";
$icone10 = "";
$icone16 = "";
$icone17 = "";
$icone23 = "";
$pontosItem = "";
$datProdEmpresa = "";
$backgroudColor = "";
$pontos = "";
$classePontos = "";
$color = "";
$data = "";
$lojasSelecionadas = "";
$content = "";

$itens_por_pagina = 50;
$pagina = 1;
$dat_ini = date("Y-m");
$hashLocal = mt_rand();

setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');

$connboard = $Cdashboard->connUser();

//inicialização de variáveis
$hoje = fnFormatDate(date("Y-m-d"));
$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30 . '- 1 days')));

// $sqlUsuario = "SELECT COD_MULTEMP FROM USUARIOS WHERE COD_USUARIO = $_SESSION['SYS_COD_USUARIO']";
// $arrUsu = mysqli_query($connAdm->connAdm(), $sqlUsuario);
// $qrUsu = mysqli_fetch_assoc($arrUsu);

// if ($qrUsu['COD_MULTEMP'] != "" && $qrUsu['COD_MULTEMP'] != "0") {
// 	$Arr_COD_EMPRESA = explode(",", $qrUsu['COD_MULTEMP']);
// 	//print_r($Arr_COD_EMPRESA);			 

// 	for ($i = 0; $i < count($Arr_COD_EMPRESA); $i++) {
// 		@$cod_empresas .= $Arr_COD_EMPRESA[$i] . ",";
// 	}

// 	$cod_empresas = substr($cod_empresas, 0, -1);
// } else {
// 	$cod_empresas = "0";
// }
$cod_empresas_combo = "9999";
$cod_empresas = $_SESSION["SYS_COD_MULTEMP"];


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_univend = @$_POST['COD_UNIVEND'];
		$dat_ini = fnmesanosql("01/" . @$_POST['DAT_INI']);
		$dat_ini_campo = @$_POST['DAT_INI'];
		$cod_grupotr = @$_REQUEST['COD_GRUPOTR'];
		$cod_tiporeg = @$_REQUEST['COD_TIPOREG'];
		$num_cgcecpf = fnLimpaCampo(fnLimpaDoc(@$_REQUEST['NUM_CGCECPF']));
		$nom_cliente = fnLimpaCampo(@$_REQUEST['NOM_CLIENTE']);
		$cod_segment = fnLimpaCampoZero(@$_REQUEST['COD_SEGMENT']);

		//array dos sistemas da empresas	
		if (isset($_POST['COD_SISTEMAS'])) {
			$Arr_COD_SISTEMAS = @$_POST['COD_SISTEMAS'];
			//print_r($Arr_COD_SISTEMAS);			 

			for ($i = 0; $i < count($Arr_COD_SISTEMAS); $i++) {
				@$cod_sistemas .= $Arr_COD_SISTEMAS[$i] . ",";
			}

			$cod_sistemas = substr($cod_sistemas, 0, -1);
		} else {
			$cod_sistemas = "0";
		}

		//array das empresas
		if (isset($_POST['COD_EMPRESA'])) {

			if (@$_POST['COD_EMPRESA'][0] == 9999) {

				$cod_empresas_combo = "9999";
				$cod_empresas = $_SESSION["SYS_COD_MULTEMP"];
			} else if (@$_POST['COD_EMPRESA'][0] == 9998) {

				$cod_empresas_combo = "9998";
				$cod_empresas = "0";
			} else {

				$cod_empresas = "";
				$Arr_COD_EMPRESA = @$_POST['COD_EMPRESA'];
				//print_r($Arr_COD_EMPRESA);			 

				for ($i = 0; $i < count($Arr_COD_EMPRESA); $i++) {
					$cod_empresas .= $Arr_COD_EMPRESA[$i] . ",";
				}

				$cod_empresas = substr($cod_empresas, 0, -1);
				$cod_empresas_combo = $cod_empresas;
			}
		} else {

			$cod_empresas = "0";
			$cod_empresas_combo = "9998";
		}

		// fnEscreve($cod_empresas);

		$filtro = fnLimpaCampo(@$_POST['VAL_PESQUISA']);
		$val_pesquisa = fnLimpaCampo(@$_POST['INPUT']);

		// fnEscreve($dat_ini_campo);

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		if ($opcao != '' && $opcao != 0) {
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

if ($cod_empresas != 0 && $cod_empresas != '') {
	$andEmpresas = " AND COD_EMPRESA IN($cod_empresas)";
} else {
	$andEmpresas = "";
}

$sqlTotal = "SELECT SUM(D.QTD_TOT_CLIENTE) TOT_CLI,
					SUM(D.VAL_TOTVENDA) TOT_VENDA,
					SUM(D.QT_TICKET) TOT_CUPOM,
					SUM(QT_TOTAL) TOT_ATEND,
					SUM(D.QTD_CLIENTE_PERIODO) TOT_CLI_PERIODO,
					SUM(D.QT_AVULSA) TOT_AVULSA
			FROM dash_consultor D
			WHERE D.ANO_MES = '$dat_ini'
			$andEmpresas
";
// FNeSCREVE($sqlTotal);
$arrayTotal = mysqli_query($connboard, $sqlTotal);

while ($qrTotal = mysqli_fetch_assoc($arrayTotal)) {


	$totCli = $qrTotal['TOT_CLI'];
	$totVenda = $qrTotal['TOT_VENDA'];
	$totCupom = $qrTotal['TOT_CUPOM'];
	$totAtend = $qrTotal['TOT_ATEND'];
	$totCliPeriodo = $qrTotal['TOT_CLI_PERIODO'];
	$totAvulsa = $qrTotal['TOT_AVULSA'];
}

if ($val_pesquisa != '' && $val_pesquisa != 0) {
	$esconde = " ";
} else {
	$esconde = "display: none;";
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

	.tooltip.top .tooltip-inner {
		color: #3c3c3c;
		min-width: 140px;
		min-height: 60px;
		padding-top: 10px;
		font-size: 16px;
		background-color: white;
		opacity: 1 !important;
		filter: alpha(opacity=100) !important;
		-webkit-box-shadow: 0px 0px 11px 0px rgba(186, 186, 186, 1);
		-moz-box-shadow: 0px 0px 11px 0px rgba(186, 186, 186, 1);
		box-shadow: 0px 0px 11px 0px rgba(186, 186, 186, 1);
	}

	.tooltip.top .tooltip-arrow {
		border-top-color: white;
		opacity: 1 !important;
		filter: alpha(opacity=100) !important;
	}

	.tooltip.in {
		opacity: 0.97 !important;
		filter: alpha(opacity=97) !important;
	}

	.table {
		margin-bottom: unset;
	}

	.hiddenRow {
		padding-top: unset !important;
		padding-bottom: unset !important;
	}

	#pontos {
		padding-right: 15px;
	}

	.tdTotal {
		width: 65px;
	}

	@media(min-width: 790px) and (max-width: 1344px) {
		.tdTotal {
			width: 75px;
		}

	}

	@media(min-width: 1345px) and (max-width: 1600px) {
		.tdTotal {
			width: 80px;
		}

	}

	@media(min-width: 1601px) and (max-width: 1920px) {
		.tdTotal {
			width: 83px;
		}

	}
</style>

<div class="push30"></div>

<div class="row" id="div_Report">

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fal fa-terminal"></i>
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

						<fieldset>
							<legend>Filtros</legend>

							<div class="row">

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Período</label>

										<div class="input-group date datePicker" id="DAT_INI_GRP">
											<input type='text' class="form-control input-sm" name="DAT_INI" id="DAT_INI" value="<?= $dat_ini_campo ?>" required />
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
										</div>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label">Empresa</label>
										<select data-placeholder="Selecione uma ou mais empresas" name="COD_EMPRESA[]" id="COD_EMPRESA" multiple="multiple" class="chosen-select-deselect requiredChk" style="width:100%;" tabindex="1">
											<option value="9998">Todas Empresas (Geral)</option>
											<option value="9999">Todas Empresas (Consultor)</option>
											<?php

											if ($_SESSION["SYS_COD_EMPRESA"] == 2) {
												$sql = "SELECT A.COD_EMPRESA, A.NOM_FANTASI, 
														(select count(B.COD_DATABASE) FROM tab_database B where B.COD_EMPRESA = A.COD_EMPRESA) as COD_DATABASE   
														from empresas A where A.cod_empresa <> 1 and A.cod_exclusa = 0 order by A.NOM_FANTASI 
														";
											} else {
												$sql = "SELECT A.COD_EMPRESA, A.NOM_FANTASI, 
														(select count(B.COD_DATABASE) FROM tab_database B where B.COD_EMPRESA = A.COD_EMPRESA) as COD_DATABASE   
														from empresas A where A.COD_EMPRESA IN (1," . $_SESSION["SYS_COD_MULTEMP"] . ") and A.cod_exclusa = 0 order by A.NOM_FANTASI 
														";
											}

											$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

											while ($qrListaEmpresa = mysqli_fetch_assoc($arrayQuery)) {
												if ((int)$qrListaEmpresa['COD_DATABASE'] == 0) {
													$desabilitado = "disabled";
												} else {
													$desabilitado = "";
												}

												echo "
															  <option value='" . $qrListaEmpresa['COD_EMPRESA'] . "' " . $desabilitado . " >" . $qrListaEmpresa['NOM_FANTASI'] . "</option> 
															";
											}
											?>
										</select>
										<div class="help-block with-errors"></div>
										<a class="btn btn-default btn-sm" id="iAll" style="padding: 0 2px ; font-size: 10px;"><i class="fa fa-check-square" aria-hidden="true"></i> selecionar todos</a>&nbsp;
										<a class="btn btn-default btn-sm" id="iNone" style="padding: 0 2px ; font-size: 10px;"><i class="fa fa-square-o" aria-hidden="true"></i>&nbsp; deselecionar todos</a>
									</div>
								</div>

								<!--sistema-->
								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Sistemas</label>
										<select data-placeholder="Selecione um ou mais sistemas" name="COD_SISTEMAS[]" id="COD_SISTEMAS" multiple="multiple" class="chosen-select-deselect requiredChk" style="width:100%;" tabindex="1">
											<option value=""></option>
											<?php

											if ($_SESSION["SYS_COD_MASTER"] == "2") {

												$sql = "SELECT COD_SISTEMA, DES_SISTEMA FROM SISTEMAS";
											} else {

												$sql = "SELECT COD_SISTEMAS FROM EMPRESAS WHERE COD_EMPRESA = 3 ";
												$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
												$qrBuscaSistema = mysqli_fetch_assoc($arrayQuery);
												$sistemasMarka = $qrBuscaSistema['COD_SISTEMAS'];

												$sql = "SELECT COD_SISTEMA, DES_SISTEMA FROM SISTEMAS WHERE COD_SISTEMA IN (" . $sistemasMarka . ") order by DES_SISTEMA ";
											}
											$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

											while ($qrListaSistemas = mysqli_fetch_assoc($arrayQuery)) {
												if ($qrListaSistemas['COD_SISTEMA'] == 'S') {
													$mostraAutoriza = '<i class="fa fa-check" aria-hidden="true"></i>';
												} else {
													$mostraAutoriza = '';
												}

												echo "
															  <option value='" . $qrListaSistemas['COD_SISTEMA'] . "'>" . $qrListaSistemas['DES_SISTEMA'] . "</option> 
															";
											}

											?>
										</select>
										<div class="help-block with-errors"></div>
									</div>

								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Segmento</label>
										<select data-placeholder="Selecione um segmento" name="COD_SEGMENT" id="COD_SEGMENT" class="chosen-select-deselect">
											<option value=""></option>
											<?php

											$sql = "select COD_SEGMENT, NOM_SEGMENT from SEGMENTOMARKA order by NOM_SEGMENT";
											$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

											while ($qrLista = mysqli_fetch_assoc($arrayQuery)) {
												echo "
															  <option value='" . $qrLista['COD_SEGMENT'] . "'>" . $qrLista['NOM_SEGMENT'] . "</option> 
															";
											}
											?>
										</select>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="push20"></div>
									<button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-sm btn-block getBtn"><i class="fal fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
								</div>

							</div>

						</fieldset>

						<div class="push30"></div>

						<div class="row">

							<div class="col-xs-12 col-sm-12 col-md-4 col-md-offset-4">
								<div class="input-group activeItem">
									<div class="input-group-btn search-panel">
										<button type="button" class="btn btn-outline dropdown-toggle form-control form-control-sm rounded-left search-bar" id="FILTERS" data-toggle="dropdown">
											<span id="search_concept">Sem filtro</span>&nbsp;
											<span class="far fa-angle-down"></span>
										</button>
										<ul class="dropdown-menu" role="menu">
											<li class="divisor"><a href="#">Sem filtro</a></li>
											<!-- <li class="divider"></li> -->
											<!-- <li><a href="#NOM_EMPRESA">Razão social</a></li>
						                    <li><a href="#NOM_FANTASI">Nome fantasia</a></li>
						                    <li><a href="#CNPJ">CNPJ</a></li> -->
										</ul>
									</div>
									<input type="hidden" name="VAL_PESQUISA" value="" id="VAL_PESQUISA">
									<input type="text" id="INPUT" class="form-control form-control-sm remove-side-borders search-bar" name="INPUT" value="<?= $val_pesquisa ?>" onkeyup="buscaRegistro(this)">
									<div class="input-group-btn" id="CLEARDIV" style="<?= $esconde ?>">
										<button class="btn btn-outline form-control form-control-sm remove-side-borders search-bar" id="CLEAR" type="button">&nbsp;<span class="fal fa-times"></span></button>
									</div>
									<div class="input-group-btn">
										<button type="submit" class="btn btn-outline form-control form-control-sm rounded-right search-bar" id="SEARCH">&nbsp;<span class="fal fa-search"></span></button>
									</div>
								</div>
							</div>

						</div>

						<div class="push30"></div>

						<div class="row">

							<div class="col-md-12">

								<div class="push20"></div>

								<table class="table table-hover">
									<thead>
										<tr>
											<!-- <th class="text-center text-info">Vendas com Oferta<b> &nbsp; <?= fnValor($totCupom, 0); ?></b></th> -->
											<th class="text-center text-info">Total de Transações<b> &nbsp; <?= fnValor($totAtend, 0); ?></b></th>
											<th class="text-center text-info">Total de Clientes<b> &nbsp; <?= fnValor($totCli, 0); ?></b></th>
											<th class="text-center text-info">Total de Faturamento &nbsp; <b>R$ <?= fnValor($totVenda, 2); ?></b></th>
										</tr>
									</thead>

								</table>
								<div class="push10"></div>

							</div>

						</div>

						<div class="row">

							<div class="col-md-12">

								<div class="no-more-tables buscavel">



									<?php

									$dat_fim = fnmesanosql(date('Y/m', strtotime('-1 month', strtotime($dat_ini))));
									$mes = date('M', strtotime('-1 month', strtotime($dat_ini)));


									$arrayMeses = array(
										'Jan' => 'Janeiro',
										'Fev' => 'Fevereiro',
										'Mar' => 'Março',
										'Abr' => 'Abril',
										'Mai' => 'Maio',
										'Jun' => 'Junho',
										'Jul' => 'Julho',
										'Ago' => 'Agosto',
										'Set' => 'Setembro',
										'Out' => 'Outubro',
										'Nov' => 'Novembro',
										'Dez' => 'Dezembro'
									);

									$mesCompara = @$arrayMeses[$mes];
									// fnEscreve($mesCompara);

									if ($cod_segment != 0 && $cod_segment != '') {
										$andSegment = " AND COD_SEGMENT = $cod_segment";
									} else {
										$andSegment = "";
									}

									if ($cod_sistemas != 0 && $cod_sistemas != '') {
										$codSistemas = str_replace(",", "|", $cod_sistemas);
										$andSistemas =  ' AND COD_SISTEMAS REGEXP "' . $codSistemas . '"';
									} else {
										$andSistemas = "";
									}


									$sql = "SELECT D.* FROM dash_consultor D 
												WHERE D.ANO_MES = '$dat_ini'
												$andEmpresas
												$andSegment
												$andSistemas
												ORDER BY D.NOM_FANTASI,D.ANO_MES DESC
													";

									// fnEscreve($sql);

									//fnsql(mysqli_query($connboard, $sql));

									$arrayQuery = mysqli_query($connboard, $sql);

									$count = 0;

									while ($qrTask = mysqli_fetch_assoc($arrayQuery)) {

										$chave_linha = $qrTask['COD_EMPRESA'];

										$sqlCompara = "SELECT D.* FROM dash_consultor D 
											WHERE D.ANO_MES = '$dat_fim' AND D.COD_EMPRESA = " . $qrTask['COD_EMPRESA'] . " 
											ORDER BY D.COD_EMPRESA,D.ANO_MES DESC";

										$qrCompara = mysqli_fetch_assoc(mysqli_query($connboard, $sqlCompara));

										// echo "<pre>";
										// print_r($qrTask);
										// echo "</pre>";

										// echo "<hr>";

										// echo "<pre>";
										// print_r($qrCompara);
										// echo "</pre>";


										$totalFidelizado = $qrTask['QT_FIDELIZA'] / $qrTask['QT_TOTAL'] * 100;
										$totalFidCompara = $qrCompara['QT_FIDELIZA'] / $qrCompara['QT_TOTAL'] * 100;

										$itensFidelizado = $qrTask['QTD_ITEM_FIDELIZA'];
										$itensFidCompara = $qrCompara['QTD_ITEM_FIDELIZA'];

										$totalResgate = $qrTask['QTD_RESGATE'] / $qrTask['QTD_CLIENTE_RESGATE'] * 100;
										$totalResCompara = $qrCompara['QTD_RESGATE'] / $qrCompara['QTD_CLIENTE_RESGATE'] * 100;

										$totalResgateFid = $qrTask['QTD_RESGATE'] / $qrTask['QT_FIDELIZA'] * 100;
										$totalResFidCompara = $qrCompara['QTD_RESGATE'] / $qrCompara['QT_FIDELIZA'] * 100;

										$totalTransac = $qrTask['QTD_RESGATE'] / $qrTask['QT_TOTAL'] * 100;
										$totalTransacCompara = $qrCompara['QTD_RESGATE'] / $qrCompara['QT_TOTAL'] * 100;

										// $totVariacaoTM = 100 - ($qrTask['VAL_VINCULADO1'] / $qrTask['QTD_VINCULADO1']) / ($qrTask['VAL_TOTAL_AV'] / $qrTask['QT_AVULSA']);
										// $totVariacaoTMCompara = 100 - ($qrCompara['VAL_VINCULADO1'] / $qrCompara['QTD_VINCULADO1']) / ($qrCompara['VAL_TOTAL_AV'] / $qrCompara['QT_AVULSA']);

										$totVariacaoTM = ((((($qrTask['VAL_VINCULADO1'] - $qrTask['VAL_RESGATE']) / $qrTask['QTD_VINCULADO1']) / ($qrTask['VAL_TOTAL_AV'] / $qrTask['QT_AVULSA'])) - 1) * 100);
										$totVariacaoTMCompara = ((((($qrCompara['VAL_VINCULADO1'] - $qrCompara['VAL_RESGATE']) / $qrCompara['QTD_VINCULADO1']) / ($qrCompara['VAL_TOTAL_AV'] / $qrCompara['QT_AVULSA'])) - 1) * 100);

										// $totVariacaoTMFid = 100 - ($qrTask['VAL_TOTAL_FIDELI'] / $qrTask['QT_FIDELIZA']) / ($qrTask['VAL_TOTAL_AV'] / $qrTask['QT_AVULSA']);
										// $totVariacaoTMFidCompara = 100 - ($qrTask['VAL_VINCULADO1'] / $qrTask['QTD_VINCULADO1']) / ($qrTask['VAL_TOTAL_AV'] / $qrTask['QT_AVULSA']);

										$totVariacaoTMFid = (((($qrTask['VAL_TOTAL_FIDELI'] / $qrTask['QT_FIDELIZA']) / ($qrTask['VAL_TOTAL_AV'] / $qrTask['QT_AVULSA'])) - 1) * 100);
										$totVariacaoTMFidCompara = (((($qrCompara['VAL_TOTAL_FIDELI'] / $qrCompara['QT_FIDELIZA']) / ($qrCompara['VAL_TOTAL_AV'] / $qrCompara['QT_AVULSA'])) - 1) * 100);

										$totCadXacesso = $qrTask['QTD_USUARIO'] != 0 ? ($qrTask['QTD_ACESSO'] / $qrTask['QTD_USUARIO']) : 0;
										$totCadXacessoCompara = $qrCompara['QTD_USUARIO'] != 0 ? ($qrCompara['QTD_ACESSO'] / $qrCompara['QTD_USUARIO']) : 0;

										$totalExpira = $qrTask['VAL_CRED_EXPIRADO'] / $qrTask['VAL_CREDITOS_GERADO'] * 100;
										$totalExpiraCompara = $qrCompara['VAL_CRED_EXPIRADO'] / $qrCompara['VAL_CREDITOS_GERADO'] * 100;

										$totalResGeral = $qrTask['QTD_RESGATE'] / $qrTask['QT_TOTAL'] * 100;
										$totalResGeralCompara = $qrCompara['QTD_RESGATE'] / $qrCompara['QT_TOTAL'] * 100;

										$totalCredExpira = $qrTask['QTD_EXPIRA_SALDO'] / $qrTask['QTD_CREDITO_GERADO'] * 100;
										$totalCredExpiraCompara = $qrCompara['QTD_EXPIRA_SALDO'] / $qrCompara['QTD_CREDITO_GERADO'] * 100;

										// $totalIndiceFideliz = $qrTask['QTD_CLIENTE_PERIODO'] / $qrTask['QT_FIDELIZA'] * 100;
										// $totalIndiceFideliz = $qrTask['QTD_CLIENTE_FIDELIZ'] != 0 ? ($qrTask['QT_FIDELIZA'] / $qrTask['QTD_CLIENTE_FIDELIZ']) : 0;
										$totalIndiceFideliz = $qrTask['VAL_FREQUENCIA'];

										// $totalIndiceFidelizCompara = $qrCompara['QTD_CLIENTE_FIDELIZ'] != 0 ? ($qrCompara['QT_FIDELIZA'] / $qrCompara['QTD_CLIENTE_FIDELIZ']) : 0;
										$totalIndiceFidelizCompara = $qrCompara['VAL_FREQUENCIA'];

										$totalFaturamento = $qrTask['VAL_RESGATE'] / $qrTask['VAL_TOTPRODU'] * 100;
										$totalFaturamentoCompara = $qrCompara['VAL_RESGATE'] / $qrCompara['VAL_TOTPRODU'] * 100;


										if ($totalFaturamentoCompara < $totalFaturamento) {
											$icone22 = "fal fa-arrow-up text-success";
										} else if ($totalFaturamentoCompara == $totalFaturamento) {
											$icone22 = "fal fa-do-not-enter text-warning";
										} else {
											$icone22 = "fal fa-arrow-down text-danger";
										}

										if ($totalIndiceFidelizCompara < $totalIndiceFideliz) {
											$icone21 = "fal fa-arrow-up text-success";
											$pontosTotalFreq = 1;
										} else if ($totalIndiceFidelizCompara == $totalIndiceFideliz) {
											$icone21 = "fal fa-do-not-enter text-warning";
											$pontosTotalFreq = 1;
										} else {
											$icone21 = "fal fa-arrow-down text-danger";
											$pontosTotalFreq = 0;
										}

										if ($totalCredExpiraCompara < $totalCredExpira) {
											$icone20 = "fal fa-arrow-up text-success";
										} else if ($totalCredExpiraCompara == $totalCredExpira) {
											$icone20 = "fal fa-do-not-enter text-warning";
										} else {
											$icone20 = "fal fa-arrow-down text-danger";
										}

										if ($totalResGeralCompara < $totalResGeral) {
											$icone19 = "fal fa-arrow-up text-success";
										} else if ($totalResGeralCompara == $totalResGeral) {
											$icone19 = "fal fa-do-not-enter text-warning";
										} else {
											$icone19 = "fal fa-arrow-down text-danger";
										}

										if ($totalExpiraCompara < $totalExpira) {
											$icone18 = "fal fa-arrow-up text-success";
										} else if ($totalExpiraCompara == $totalExpira) {
											$icone18 = "fal fa-do-not-enter text-warning";
										} else {
											$icone18 = "fal fa-arrow-down text-danger";
										}

										if ($totVariacaoTMCompara < $totVariacaoTM) {
											$icone14 = "fal fa-arrow-up text-success";
											$pontosVaria = 1;
										} else if ($totVariacaoTMCompara == $totVariacaoTM) {
											$icone14 = "fal fa-do-not-enter text-warning";
											$pontosVaria = 1;
										} else {
											$icone14 = "fal fa-arrow-down text-danger";
											$pontosVaria = 0;
										}

										if ($totVariacaoTMFidCompara < $totVariacaoTMFid) {
											$icone24 = "fal fa-arrow-up text-success";
										} else if ($totVariacaoTMFidCompara == $totVariacaoTMFid) {
											$icone24 = "fal fa-do-not-enter text-warning";
										} else {
											$icone24 = "fal fa-arrow-down text-danger";
										}

										if ($totCadXacessoCompara < $totCadXacesso) {
											$icone15 = "fal fa-arrow-up text-success";
										} else if ($totCadXacessoCompara == $totCadXacesso) {
											$icone15 = "fal fa-do-not-enter text-warning";
										} else {
											$icone15 = "fal fa-arrow-down text-danger";
										}

										if ($totalTransacCompara < $totalTransac) {
											$icone13 = "fal fa-arrow-up text-success";
										} else if ($totalTransacCompara == $totalTransac) {
											$icone13 = "fal fa-do-not-enter text-warning";
										} else {
											$icone13 = "fal fa-arrow-down text-danger";
										}

										if ($totalResFidCompara < $totalResgateFid) {
											$icone12 = "fal fa-arrow-up text-success";
										} else if ($totalResFidCompara == $totalResgateFid) {
											$icone12 = "fal fa-do-not-enter text-warning";
										} else {
											$icone12 = "fal fa-arrow-down text-danger";
										}

										if ($totalFidCompara < $totalFidelizado) {
											$icone11 = "fal fa-arrow-up text-success";
											$pontosFideliza = 1;
										} else if ($totalFidCompara == $totalFidelizado) {
											$icone11 = "fal fa-do-not-enter text-warning";
											$pontosFideliza = 1;
										} else {
											$icone11 = "fal fa-arrow-down text-danger";
											$pontosFideliza = 0;
										}

										if ($totalResCompara < $totalResgate) {
											$icone12 = "fal fa-arrow-up text-success";
										} else if ($totalResCompara == $totalResgate) {
											$icone12 = "fal fa-do-not-enter text-warning";
										} else {
											$icone12 = "fal fa-arrow-down text-danger";
										}

										if ($qrCompara['QTD_TOT_CLIENTE'] < $qrTask['QTD_TOT_CLIENTE']) {
											$icone0 = "fal fa-arrow-up text-success";
											$pontosTotCli = 1;
										} else if ($qrCompara['QTD_TOT_CLIENTE'] == $qrTask['QTD_TOT_CLIENTE']) {
											$icone0 = "fal fa-do-not-enter text-warning";
											$pontosTotCli = 1;
										} else {
											$icone0 = "fal fa-arrow-down text-danger";
											$pontosTotCli = 0;
										}

										if ($qrCompara['QTD_CLIENTE_PERIODO'] < $qrTask['QTD_CLIENTE_PERIODO']) {
											$icone1 = "fal fa-arrow-up text-success";
										} else if ($qrCompara['QTD_CLIENTE_PERIODO'] == $qrTask['QTD_CLIENTE_PERIODO']) {
											$icone1 = "fal fa-do-not-enter text-warning";
										} else {
											$icone1 = "fal fa-arrow-down text-danger";
										}

										if ($qrCompara['QT_FIDELIZA'] < $qrTask['QT_FIDELIZA']) {
											$icone2 = "fal fa-arrow-up text-success";
										} else if ($qrCompara['QT_FIDELIZA'] == $qrTask['QT_FIDELIZA']) {
											$icone2 = "fal fa-do-not-enter text-warning";
										} else {
											$icone2 = "fal fa-arrow-down text-danger";
										}

										if ($qrCompara['QTD_RESGATE'] < $qrTask['QTD_RESGATE']) {
											$icone3 = "fal fa-arrow-up text-success";
										} else if ($qrCompara['QTD_RESGATE'] == $qrTask['QTD_RESGATE']) {
											$icone3 = "fal fa-do-not-enter text-warning";
										} else {
											$icone3 = "fal fa-arrow-down text-danger";
										}

										if ($qrCompara['QTD_PRODTKT'] < $qrTask['QTD_PRODTKT']) {
											$icone4 = "fal fa-arrow-up text-success";
										} else if ($qrCompara['QTD_PRODTKT'] == $qrTask['QTD_PRODTKT']) {
											$icone4 = "fal fa-do-not-enter text-warning";
										} else {
											$icone4 = "fal fa-arrow-down text-danger";
										}

										if ($qrCompara['QTD_COMUNICACAO_SMS'] < $qrTask['QTD_COMUNICACAO_SMS']) {
											$icone5 = "fal fa-arrow-up text-success";
										} else if ($qrCompara['QTD_COMUNICACAO_SMS'] == $qrTask['QTD_COMUNICACAO_SMS']) {
											$icone5 = "fal fa-do-not-enter text-warning";
										} else {
											$icone5 = "fal fa-arrow-down text-danger";
										}

										if ($qrCompara['QTD_COMUNICACAO_EMAIL'] < $qrTask['QTD_COMUNICACAO_EMAIL']) {
											$icone6 = "fal fa-arrow-up text-success";
										} else if ($qrCompara['QTD_COMUNICACAO_EMAIL'] == $qrTask['QTD_COMUNICACAO_EMAIL']) {
											$icone6 = "fal fa-do-not-enter text-warning";
										} else {
											$icone6 = "fal fa-arrow-down text-danger";
										}

										if ($qrCompara['QTD_DEBITOS_SMS'] < $qrTask['QTD_DEBITOS_SMS']) {
											$icone7 = "fal fa-arrow-up text-success";
										} else if ($qrCompara['QTD_DEBITOS_SMS'] === $qrTask['QTD_DEBITOS_SMS']) {
											$icone7 = "fal fa-do-not-enter text-warning";
										} else {
											$icone7 = "fal fa-arrow-down text-danger";
										}

										if ($qrCompara['QTD_DEBITOS_EMAIL'] < $qrTask['QTD_DEBITOS_EMAIL']) {
											$icone8 = "fal fa-arrow-up text-success";
										} else if ($qrCompara['QTD_DEBITOS_EMAIL'] === $qrTask['QTD_DEBITOS_EMAIL']) {
											$icone8 = "fal fa-do-not-enter text-warning";
										} else {
											$icone8 = "fal fa-arrow-down text-danger";
										}

										if ($qrCompara['QT_TOTAL'] < $qrTask['QT_TOTAL']) {
											$icone9 = "fal fa-arrow-up text-success";
											// $pontosTotal = 1;
										} else if ($qrCompara['QT_TOTAL'] === $qrTask['QT_TOTAL']) {
											$icone9 = "fal fa-do-not-enter text-warning";
											// $pontosTotal = 1;
										} else {
											$icone9 = "fal fa-arrow-down text-danger";
											// $pontosTotal = 0;
										}

										if ($qrCompara['QT_AVULSA'] < $qrTask['QT_AVULSA']) {
											$icone10 = "fal fa-arrow-up text-success";
										} else if ($qrCompara['QT_AVULSA'] === $qrTask['QT_AVULSA']) {
											$icone10 = "fal fa-do-not-enter text-warning";
										} else {
											$icone10 = "fal fa-arrow-down text-danger";
										}
										if ($qrCompara['PC_FIDELIZADO'] < $qrTask['PC_FIDELIZADO']) {
											$icone16 = "fal fa-arrow-up text-success";
										} else if ($qrCompara['PC_FIDELIZADO'] === $qrTask['PC_FIDELIZADO']) {
											$icone16 = "fal fa-do-not-enter text-warning";
										} else {
											$icone16 = "fal fa-arrow-down text-danger";
										}

										if ($qrCompara['VAL_CRED_EXPIRADO'] < $qrTask['VAL_CRED_EXPIRADO']) {
											$icone17 = "fal fa-arrow-up text-success";
										} else if ($qrCompara['VAL_CRED_EXPIRADO'] == $qrTask['VAL_CRED_EXPIRADO']) {
											$icone17 = "fal fa-do-not-enter text-warning";
										} else {
											$icone17 = "fal fa-arrow-down text-danger";
										}

										// QTD ITENS TRANSACOES FIDELIZADAS
										if ($itensFidCompara < $itensFidelizado) {
											$icone23 = "fal fa-arrow-up text-success";
											$pontosItem = 1;
										} else if ($itensFidCompara == $itensFidelizado) {
											$icone23 = "fal fa-do-not-enter text-warning";
											$pontosItem = 1;
										} else {
											$icone23 = "fal fa-arrow-down text-danger";
											$pontosItem = 0;
										}

										if ($qrTask['DAT_PROD_EMPRESA'] != "") {
											$datProdEmpresa = fndatashort($qrTask['DAT_PROD_EMPRESA']);
										} else {
											$datProdEmpresa = "";
										}

										// COR DA TABLE DO LOOPING
										if ($count % 2 == 0) {
											$backgroudColor = "#fff!important";
										} else {
											$backgroudColor = "#F9F9F9!important";
										}

										$pontos = $pontosTotCli + $pontosFideliza + $pontosTotalFreq + $pontosVaria + $pontosItem;

										if ($pontos >= 4) {
											$classePontos = "fal fa-thumbs-up";
											$color = "color:green";
											$data = "Cresceu";
										} else {
											$classePontos = "fal fa-thumbs-down";
											$color = "color:red";
											$data = "Diminuiu";
										}



									?>

										<table class="table item-bd" id="empresa_<?= $qrTask['COD_EMPRESA'] ?>" style="background: <?= $backgroudColor ?>;">

											<thead style="background: <?= $backgroudColor ?>;">

												<tr data-toggle="collapse" class="accordion-toggle" data-target="#<?= $chave_linha ?>" onclick='rotacionaSeta("<?= $chave_linha ?>")'>
													<th width="180"><a class="transparency" href="javascript:void(0)" onclick='escondeEmpresa("<?= $qrTask['COD_EMPRESA'] ?>")'><span class="fal fa-times text-danger"></span></a></th>
													<th class="text-left"></th>
													<th><small>Cliente <br />Desde</small></th>
													<th><small>Base <br />Clientes¹</small></th>
													<th><small>Clientes <br />Novos</small></th>
													<th><small>Qtd. <br />Transações</small></th>
													<th><small>Qtd. <br />Transações Avulsas</small></th>
													<th><small>Qtd. <br />Transações Fid.</small></th>
													<th><small>% Transações Fid.¹</small></th>
													<th><small>Qtd. Itens Transações Fid.¹</small></th>
													<th><small>Qtd. <br />Resgates</small></th>
													<!--<th><small>% Resgates</small></th>-->
													<th><small>Índice <br />Freq. Mês¹</small></th>
													<th><small>% Qtd. Resg. <br />Qtd. Transac. Fideliz.</small></th>
													<th><small>% Qtd. Resg. <br />Qtd. Transac. Gerais</small></th>
													<th><small>% Qtd. Expi. <br />Qtd. Cred. Gerados</small></th>
													<th><small>% Resgate Fat.</small></th>
												</tr>

											</thead>

											<tbody style="background: <?= $backgroudColor ?>;">

												<tr data-toggle="collapse" class="accordion-toggle" data-target="#<?= $chave_linha ?>" onclick='rotacionaSeta("<?= $chave_linha ?>")'>
													<td><span class="fal fa-angle-right <?= $chave_linha ?>" data-expande='0'></span>&nbsp; <a href="javascript:void(0)" class="referencia-busca"><?= $qrTask['COD_EMPRESA'] ?> - <?= $qrTask['NOM_FANTASI'] ?></a></td>
													<td id="pontos"><small><span class="<?= $classePontos ?>" style="<?= $color ?>" data-toggle='tooltip' data-placement="top" data-html="true" data-original-title="<?= $data ?></br> Ref à <?= $mesCompara ?>"></span></small></td>
													<td><small><?= $datProdEmpresa ?>&nbsp;</span></td>
													<td><small><?= fnvalor($qrTask['QTD_TOT_CLIENTE'], 0) ?></small>&nbsp;<span class="<?= $icone0 ?>" data-toggle='tooltip' data-placement="top" data-html="true" data-original-title="<?= fnvalor($qrCompara['QTD_TOT_CLIENTE'], 0) ?></br> Ref à <?= $mesCompara ?>"></span></small></td>
													<td><small><?= fnvalor($qrTask['QTD_CLIENTE_PERIODO'], 0) ?>&nbsp;<span class="<?= $icone1 ?>" data-toggle='tooltip' data-placement="top" data-html="true" data-original-title="<?= fnvalor($qrCompara['QTD_CLIENTE_PERIODO'], 0) ?></br> Ref à <?= $mesCompara ?>"></span></small></td>
													<td><small><?= fnvalor($qrTask['QT_TOTAL'], 0) ?>&nbsp;<span class="<?= $icone9 ?>" data-toggle='tooltip' data-placement="top" data-html="true" data-original-title="<?= fnvalor($qrCompara['QT_TOTAL'], 0) ?></br> Ref à <?= $mesCompara ?>"></span></small></td>
													<td><small><?= fnvalor($qrTask['QT_AVULSA'], 0) ?>&nbsp;<span class="<?= $icone10 ?>" data-toggle='tooltip' data-placement="top" data-html="true" data-original-title="<?= fnvalor($qrCompara['QT_AVULSA'], 0) ?></br> Ref à <?= $mesCompara ?>"></span></small></td>
													<td><small><?= fnvalor($qrTask['QT_FIDELIZA'], 0) ?>&nbsp;<span class="<?= $icone2 ?>" data-toggle='tooltip' data-placement="top" data-html="true" data-original-title="<?= fnvalor($qrCompara['QT_FIDELIZA'], 0) ?></br> Ref à <?= $mesCompara ?>"></span></small></td>
													<td><small><?= fnvalor($totalFidelizado, 2) . "%" ?>&nbsp;<span class="<?= $icone11 ?>" data-toggle='tooltip' data-placement="top" data-html="true" data-original-title="<?= fnvalor($totalFidCompara, 2) . "%" ?></br> Ref à <?= $mesCompara ?>"></span></small></td>
													<td><small><?= fnvalor($itensFidelizado, 0) ?>&nbsp;<span class="<?= $icone23 ?>" data-toggle='tooltip' data-placement="top" data-html="true" data-original-title="<?= fnvalor($itensFidCompara, 0) . "%" ?></br> Ref à <?= $mesCompara ?>"></span></small></td>
													<td><small><?= fnvalor($qrTask['QTD_RESGATE'], 0) ?>&nbsp;<span class="<?= $icone3 ?>" data-toggle='tooltip' data-placement="top" data-html="true" data-original-title="<?= fnvalor($qrCompara['QTD_RESGATE'], 0) ?></br> Ref à <?= $mesCompara ?>"></span></small></td>

													<!--<td><small><?= fnvalor($totalResgate, 2) . "%" ?><span class="<?= $icone12 ?>" data-toggle='tooltip' data-placement="top" data-html="true" data-original-title="<?= fnvalor($totalResCompara, 2) . "%" ?></br> Ref à <?= $mesCompara ?>"></span></small></td>-->

													<td><small><?= round($totalIndiceFideliz, 2) ?><span class="<?= $icone21 ?>" data-toggle='tooltip' data-placement="top" data-html="true" data-original-title="<?= round($totalIndiceFidelizCompara, 2) ?></br> Ref à <?= $mesCompara ?>"></span></small></td>

													<td><small><?= fnvalor($totalResgateFid, 2) . "%" ?>&nbsp;<span class="<?= $icone12 ?>" data-toggle='tooltip' data-placement="top" data-html="true" data-original-title="<?= fnvalor($totalResFidCompara, 2) . "%" ?></br> Ref à <?= $mesCompara ?>"></span></small></td>
													<td><small><?= fnvalor($totalResGeral, 2) . "%" ?>&nbsp;<span class="<?= $icone19 ?>" data-toggle='tooltip' data-placement="top" data-html="true" data-original-title="<?= fnvalor($totalResGeralCompara, 2) . "%" ?></br> Ref à <?= $mesCompara ?>"></span></small></td>
													<td><small><?= fnvalor($totalCredExpira, 2) . "%" ?>&nbsp;<span class="<?= $icone20 ?>" data-toggle='tooltip' data-placement="top" data-html="true" data-original-title="<?= fnvalor($totalCredExpiraCompara, 2) . "%" ?></br> Ref à <?= $mesCompara ?>"></span></small></td>
													<td><small><?= fnvalor($totalFaturamento, 2) . "%" ?>&nbsp;<span class="<?= $icone22 ?>" data-toggle='tooltip' data-placement="top" data-html="true" data-original-title="<?= fnvalor($totalFaturamentoCompara, 2) . "%" ?></br> Ref à <?= $mesCompara ?>"></span></small></td>
												</tr>

											</tbody>

											<tbody style="background: <?= $backgroudColor ?>;">

												<!-- <div class="push10"></div> -->

												<tr>
													<td colspan="16" class="hiddenRow">
														<div class="accordian-body collapse" id="<?= $chave_linha ?>">
															<table class="table">

																<thead style="background: <?= $backgroudColor ?>;">

																	<tr>
																		<th><small>Cred. Expirados</small></th>
																		<!--<th>Qtd. Itens Atend.</th>-->
																		<th><small>% Val. Expira. / <br />Val. Cred. Gerados</small></th>
																		<th><small>% $ Variação <br />TM Resg. X Avulso</small></th>
																		<th><small>% $ Variação <br />TM Fid. X Avulso¹</small></th>
																		<th><small>Qtd. <br />Prod. Vigentes Tkt.</small></th>
																		<th><small>Dat. <br />Ult. Acesso</small></th>
																		<th><small>Usu. <br />Cadastrado / Acesso</small></th>
																		<th><small>Qtd. <br />Disparos SMS</small></th>
																		<th><small>Cobrança <br />Contratada SMS</small></th>
																		<th><small>Qtd. <br />Disparos E-mail</small></th>
																		<th><small>Cobrança <br />Contratada Email</small></th>
																		<th><small>LGPD</small></th>
																	</tr>

																</thead>
																<tbody style="background: <?= $backgroudColor ?>;">
																	<tr>
																		<td><small><?= fnvalor($qrTask['VAL_CRED_EXPIRADO'], 2) . "R$" ?>&nbsp;<span class="<?= $icone17 ?>" data-toggle='tooltip' data-placement="top" data-html="true" data-original-title="<?= fnvalor($qrCompara['VAL_CRED_EXPIRADO'], 2) . "R$" ?></br> Ref à <?= $mesCompara ?>"></span></small></td>
																		<!--<td>99&nbsp;<span class="" </span></td>-->
																		<td><small><?= fnvalor($totalExpira, 2) . "%" ?>&nbsp;<span class="<?= $icone18 ?>" data-toggle='tooltip' data-placement="top" data-html="true" data-original-title="<?= fnvalor($totalExpiraCompara, 2) . "%" ?></br> Ref à <?= $mesCompara ?>"></span></small></td>
																		<td><small><?= fnvalor($totVariacaoTM, 2) . "%" ?>&nbsp;<span class="<?= $icone14 ?>" data-toggle='tooltip' data-placement="top" data-html="true" data-original-title="<?= fnvalor($totVariacaoTMCompara, 2) . "%" ?></br> Ref à <?= $mesCompara ?>"></span></small></td>
																		<td><small><?= fnvalor($totVariacaoTMFid, 2) . "%" ?>&nbsp;<span class="<?= $icone24 ?>" data-toggle='tooltip' data-placement="top" data-html="true" data-original-title="<?= fnvalor($totVariacaoTMFidCompara, 2) . "%" ?></br> Ref à <?= $mesCompara ?>"></span></small></td>
																		<td><small><?= fnvalor($qrTask['QTD_PRODTKT'], 0) ?>&nbsp;<span class="<?= $icone4 ?>" data-toggle='tooltip' data-placement="top" data-html="true" data-original-title="<?= fnvalor($qrCompara['QTD_PRODTKT'], 0) ?></br> Ref à <?= $mesCompara ?>"></span></small></td>
																		<td><small><?= fndatashort($qrTask['DAT_ULT_ACESSO'], 0) ?>&nbsp;</small></td>
																		<td><small><?= $qrTask['QTD_USUARIO'] ?>/<?= $qrTask['QTD_ACESSO'] ?>&nbsp;<span class="<?= $icone15 ?>" data-toggle='tooltip' data-placement="top" data-html="true" data-original-title="<?= fnvalor($totCadXacessoCompara, 2) . "%" ?></br> Ref à <?= $mesCompara ?>"></span></small></td>
																		<!-- <td><small><?= fnvalor($totCadXacesso, 2) . "%" ?>&nbsp;<span class="<?= $icone15 ?>" data-toggle='tooltip' data-placement="top" data-html="true" data-original-title="<?= fnvalor($totCadXacessoCompara, 2) . "%" ?></br> Ref à <?= $mesCompara ?>"></span></small></td> -->
																		<td><small><?= fnvalor($qrTask['QTD_COMUNICACAO_SMS'], 0) ?>&nbsp;<span class="<?= $icone5 ?>" data-toggle='tooltip' data-placement="top" data-html="true" data-original-title="<?= fnvalor($qrCompara['QTD_COMUNICACAO_SMS'], 0) ?></br> Ref à <?= $mesCompara ?>"></span></small></td>
																		<td><small><?= fnvalor($qrTask['QTD_DEBITOS_SMS'], 0) ?>&nbsp;<span class="<?= $icone7 ?>" data-toggle='tooltip' data-placement="top" data-html="true" data-original-title="<?= fnvalor($qrCompara['QTD_DEBITOS_SMS'], 0) ?></br> Ref à <?= $mesCompara ?>"></span></small></td>
																		<td><small><?= fnvalor($qrTask['QTD_COMUNICACAO_EMAIL'], 0) ?>&nbsp;<span class="<?= $icone6 ?> " data-toggle='tooltip' data-placement="top" data-html="true" data-original-title="<?= fnvalor($qrCompara['QTD_COMUNICACAO_EMAIL'], 0) ?></br> Ref à <?= $mesCompara ?>"></span></small></td>
																		<td><small><?= fnvalor($qrTask['QTD_DEBITOS_EMAIL'], 0) ?>&nbsp;<span class="<?= $icone8 ?>" data-toggle='tooltip' data-placement="top" data-html="true" data-original-title="<?= fnvalor($qrCompara['QTD_DEBITOS_EMAIL'], 0) ?></br> Ref à <?= $mesCompara ?>"></span></small></td>
																		<td><small><?= $qrTask['LGPD'] ?>&nbsp;</small></td>
																	</tr>
																</tbody>
															</table>
															<div class="push30"></div>
														</div>

													</td>
												</tr>

											</tbody>
											<tfoot>

												<tr>
													<th colspan="100">
														<a class="btn btn-info btn-sm exportarCSV"> <i class="fal fa-file-excel" aria-hidden="true"></i>&nbsp; Exportar</a>
													</th>
												</tr>
											</tfoot>

										</table>

									<?php
										$count++;
									}

									?>

								</div>

								<!-- <table class="table">
									<tbody> -->
								<!-- <tr>
											<td colspan="2"></td>
											<td style="width: 40px;"></td>
											<td colspan="2"></td>
											<td class="text-right tdTotal"><small><b><?php echo $totCli; ?></b></small></td>
											<td class="text-right tdTotal"><small><b><?php echo $totCliPeriodo; ?></b></small></td>
											<td class="text-right tdTotal"><small><b><?php echo $totAtend; ?></b></small></td>
											<td class="text-right tdTotal"><small><b><?php echo "40.000"; ?></b></small></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
										</tr> -->
								<!-- </tbody>
								</table> -->
							</div>



							<input type="hidden" name="LOJAS" id="LOJAS" value="<?= $lojasSelecionadas; ?>" />
							<input type="hidden" name="opcao" id="opcao" value="">
							<input type="hidden" name="hashForm" id="hashForm" value="<?= $hashLocal; ?>" />
							<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

							<div class="push5"></div>

					</form>

					<div class="push50"></div>

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

<script>
	//Barra de pesquisa essentials ------------------------------------------------------
	$(document).ready(function(e) {
		var value = $('#INPUT').val().toLowerCase().trim();
		if (value) {
			$('#CLEARDIV').show();
		} else {
			$('#CLEARDIV').hide();
		}
		$('.search-panel .dropdown-menu').find('a').click(function(e) {
			e.preventDefault();
			var param = $(this).attr("href").replace("#", "");
			var concept = $(this).text();
			$('.search-panel span#search_concept').text(concept);
			$('.input-group #VAL_PESQUISA').val(param);
			$('#INPUT').focus();
		});

		$("#FILTERS, #INPUT, #SEARCH, #CLEAR").focus(function() {
			$("#FILTERS, #INPUT, #SEARCH, #CLEAR").addClass("activeItem");
		});

		$("#FILTERS, #INPUT, #SEARCH, #CLEAR").blur(function() {
			$("#FILTERS, #INPUT, #SEARCH, #CLEAR").removeClass("activeItem");
		});

		$('#CLEAR').click(function() {
			$('#INPUT').val('');
			$('#INPUT').focus();
			$('#CLEARDIV').hide();
			if ("<?= $filtro ?>" != "") {
				location.reload();
			} else {
				var value = $('#INPUT').val().toLowerCase().trim();
				if (value) {
					$('#CLEARDIV').show();
				} else {
					$('#CLEARDIV').hide();
				}
				$(".buscavel .item-bd").each(function(index) {
					if (!index) return;
					$(this).find(".referencia-busca").each(function() {
						var id = $(this).text().toLowerCase().trim();
						var sem_registro = (id.indexOf(value) == -1);
						$(this).closest('.item-bd').toggle(!sem_registro);
						return sem_registro;
					});
				});
			}
		});

		// $('#SEARCH').click(function(){
		// 	$('#formulario').submit();
		// });

		$('#iAll').on('click', function(e) {
			e.preventDefault();
			$('#COD_EMPRESA option').prop('selected', true).trigger('chosen:updated');
		});

		$('#iNone').on('click', function(e) {
			e.preventDefault();
			$("#COD_EMPRESA option:selected").removeAttr("selected").trigger('chosen:updated');
		});

		$(".exportarCSV").click(function() {
			$.confirm({
				title: 'Exportação',
				content: '' +
					'<form action="" class="formName">' +
					'<div class="form-group">' +
					'<label>Insira o nome do arquivo:</label>' +
					'<input type="text" placeholder="Nome" class="nome form-control" required />' +
					'</div>' +
					'</form>',
				buttons: {
					formSubmit: {
						text: 'Gerar',
						btnClass: 'btn-blue',
						action: function() {
							var nome = this.$content.find('.nome').val();
							if (!nome) {
								$.alert('Por favor, insira um nome');
								return false;
							}

							$.confirm({
								title: 'Mensagem',
								type: 'green',
								icon: 'fa fa-check-square-o',
								content: function() {
									var self = this;
									return $.ajax({
										url: "relatorios/ajxGerencialProjetos.do?opcao=exportar&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>",
										data: $('#formulario').serialize(),
										method: 'POST'
									}).done(function(response) {
										self.setContentAppend('<div>Exportação realizada com sucesso.</div>');
										var fileName = '<?php echo $cod_empresa; ?>_' + nome + '.csv';
										SaveToDisk('media/excel/' + fileName, fileName);
										console.log(response);
									}).fail(function() {
										self.setContentAppend('<div>Erro ao realizar o procedimento!</div>');
									});
								},
								buttons: {
									fechar: function() {
										//close
									}
								}
							});
						}
					},
					cancelar: function() {
						//close
					},
				}
			});
		});


	});

	function buscaRegistro(el) {
		var filtro = $('#search_concept').text().toLowerCase();

		if (filtro == "sem filtro") {
			var value = $(el).val().toLowerCase().trim();
			if (value) {
				$('#CLEARDIV').show();
			} else {
				$('#CLEARDIV').hide();
			}
			$(".buscavel .item-bd").each(function(index) {
				if (!index) return;
				$(this).find(".referencia-busca").each(function() {
					var id = $(this).text().toLowerCase().trim();
					var sem_registro = (id.indexOf(value) == -1);
					$(this).closest('.item-bd').toggle(!sem_registro);
					return sem_registro;
				});
			});
		}
	}

	//-----------------------------------------------------------------------------------
	//datas
	$(function() {

		// var numPaginas = <?php echo $numPaginas; ?>;
		// if(numPaginas != 0){
		// 	carregarPaginacao(numPaginas);
		// }

		$('.datePicker').datetimepicker({
			format: 'MM/YYYY',
		}).on('changeDate', function(e) {
			$(this).datetimepicker('hide');
		});

		if ("<?= $cod_sistemas ?>" != "" && "<?= $cod_sistemas ?>" != "0") {
			var sistemasCli = "<?= $cod_sistemas ?>";
			var sistemasCliArr = sistemasCli.split(',');
			//opções multiplas
			for (var i = 0; i < sistemasCliArr.length; i++) {
				$("#formulario #COD_SISTEMAS option[value=" + Number(sistemasCliArr[i]) + "]").prop("selected", "true");
			}
			$("#formulario #COD_SISTEMAS").trigger("chosen:updated");
		}

		if ("<?= $cod_empresas_combo ?>" != "" && "<?= $cod_empresas_combo ?>" != "0") {
			var sistemasCli = "<?= $cod_empresas_combo ?>";
			var sistemasCliArr = sistemasCli.split(',');
			//opções multiplas
			for (var i = 0; i < sistemasCliArr.length; i++) {
				$("#formulario #COD_EMPRESA option[value=" + Number(sistemasCliArr[i]) + "]").prop("selected", "true");
			}
			$("#formulario #COD_EMPRESA").trigger("chosen:updated");
		}

		$("#formulario #COD_SEGMENT").val("<?= $cod_segment ?>").trigger("chosen:updated");

	});

	function rotacionaSeta(obj) {

		let expande = $("." + obj).attr('data-expande');

		if (expande == 0) {
			$("." + obj).attr('data-expande', '1').removeClass('fa-angle-right').addClass('fa-angle-down');
		} else {
			$("." + obj).attr('data-expande', '0').removeClass('fa-angle-down').addClass('fa-angle-right');
		}

	}

	function escondeEmpresa(codEmpresa) {
		$("#empresa_" + codEmpresa).fadeOut('fast');
	}

	function abreDetail(idBloco) {
		var idItem = $('.abreDetail_' + idBloco)
		if (!idItem.is(':visible')) {
			idItem.show();
			$('#bloco_' + idBloco).find($(".fa")).removeClass('fa-angle-right').addClass('fa-angle-down');
		} else {
			idItem.hide();
			$('#bloco_' + idBloco).find($(".fa")).removeClass('fa-angle-down').addClass('fa-angle-right');
		}
	}
</script>