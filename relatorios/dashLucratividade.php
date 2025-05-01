<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}

$hashLocal = "";
$hoje = "";
$dias30 = "";
$msgRetorno = "";
$msgTipo = "";
$cod_filtro = "";
$dat_ini = "";
$dat_fim = "";
$hHabilitado = "";
$hashForm = "";
$array_dat_fim = [];
$month = "";
$year = "";
$last_day_of_month = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$dat_cadastr = "";
$temUnivend = "";
$maxComp = "";
$maxEvo = "";
$qrBuscaFiltro = "";
$qtd_diashist = 0;
$qtd_mesclass = 0;
$dt_filtro = "";
$sqlPeriodo = "";
$arrayQueryPeriodo = [];
$qtd_periodos = 0;
$qrListaFiltro = "";
$ano = "";
$dataConsulta = "";
$lojasSelecionadas = "";
$bar = [];
$cliente = [];
$gm = [];
$im = [];
$nomeFaixa = "";
$resgate_total = [];
$vvr = [];
$resgTot = "";
$qrFunil = "";
$faixa = [];
$vvr_medio = [];
$qtd_resgate = [];
$media_vr = [];
$total_vvr = [];
$vl_lucro_total = [];
$qtd_transacoes = [];
$qtd_total_item = [];
$qtd_media_compra = [];
$qtd_media_item = [];
$qtd_cliente_resg = [];
$perc_vvr1 = "";
$perc_vvr2 = "";
$perc_vvr3 = "";
$perc_vvr4 = "";
$resg1 = "";
$resg2 = "";
$resg3 = "";
$resg4 = "";
$freq1 = "";
$freq2 = "";
$freq3 = "";
$freq4 = "";
$freq5 = "";
$qtd_media_compra1 = 0;
$qtd_media_compra2 = 0;
$qtd_media_compra3 = 0;
$qtd_media_compra4 = 0;
$qtd_media_compra5 = 0;
$qtd_media_item1 = 0;
$qtd_media_item2 = 0;
$qtd_media_item3 = 0;
$qtd_media_item4 = 0;
$pa1 = "";
$pa2 = "";
$pa3 = "";
$pa4 = "";
$bar2Calc = "";
$bar3Calc = "";
$bar4Calc = "";
$Med_vr1 = "";
$Med_vr2 = "";
$Med_vr3 = "";
$Med_vr4 = "";
$mes = "";
$ultimo_dia = "";
$ultima_data = "";
$dt_filtro_fin = "";
$dias_periodo = "";
$dt_filtroMenor = "";
$classifica = "";
$compara1 = "";
$qtd_compras1 = 0;
$txt_compras1 = "";
$i = "";
$compara2 = "";
$qtd_compras2 = 0;
$txt_compras2 = "";
$compara3 = "";
$qtd_compras3 = 0;
$txt_compras3 = "";
$compara4 = "";
$txt_compras4 = "";
$qtd_compras5 = 0;
$compara5 = "";
$txt_compras5 = "";


$hashLocal = mt_rand();
//fnMostraForm();
//inicialização de variáveis
//$hoje = fnFormatDate(date("Y-m-d"));
$hoje = fnFormatDate(date('Y-m-d', strtotime($hoje . '- 1 days')));
$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30 . '- 30 days')));

$conn = connTemp($cod_empresa, '');
$adm = $connAdm->connAdm();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_empresa = fnLimpaCampoZero(@$_POST['COD_EMPRESA']);
		$cod_univend = @$_REQUEST['COD_UNIVEND'];
		$cod_grupotr = @$_REQUEST['COD_GRUPOTR'];
		$cod_tiporeg = @$_REQUEST['COD_TIPOREG'];
		$cod_filtro = @$_REQUEST['COD_FILTRO'];
		$dat_ini = "01/" . @$_REQUEST['DAT_INI'];
		$dat_fim = @$_REQUEST['DAT_FIM'];
		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		$array_dat_fim  = explode("/", $dat_fim);

		if (count($array_dat_fim) >= 2) {
			$month = (int) $array_dat_fim['0'];
			$year = (int) $array_dat_fim['1'];

			if ($month >= 1 && $month <= 12) {
				$last_day_of_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);
				$dat_fim = $last_day_of_month . "/" . $dat_fim;
			} else {
				//echo "Mês inválido.";
			}
		} else {
			//echo "Formato de data inválido.";
		}

		if ($opcao != '') {
		}
	}
}

//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode(@$_GET['id']);
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
if (@$cod_univend == "9999") {
	$temUnivend = "N";
} else {
	$temUnivend = "S";
}

//busca revendas do usuário
include "unidadesAutorizadas.php";

// Filtro por Grupo de Lojas
include "filtroGrupoLojas.php";

// fnEscreve($maxComp);
// fnEscreve($maxEvo);

//busca período default
if ($cod_filtro == "") {
	//fnEscreve(1); 
	$sql = "SELECT MAX(COD_FILTRO) AS COD_FILTRO , MAX(QTD_DIASHIST) AS QTD_DIASHIST , MAX(QTD_MESCLASS) AS QTD_MESCLASS , MAX(DT_FILTRO) AS DT_FILTRO FROM filtro_frequencia where COD_EMPRESA = $cod_empresa ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($conn, trim($sql));
	$qrBuscaFiltro = mysqli_fetch_assoc($arrayQuery);

	$cod_filtro = $qrBuscaFiltro['COD_FILTRO'];
	$qtd_diashist = $qrBuscaFiltro['QTD_DIASHIST'];
	$qtd_mesclass = $qrBuscaFiltro['QTD_MESCLASS'];
	$dt_filtro = $qrBuscaFiltro['DT_FILTRO'];
} else {
	//fnEscreve(2); 		
	$sql = "SELECT COD_FILTRO , QTD_DIASHIST , QTD_MESCLASS , DT_FILTRO FROM filtro_frequencia where COD_EMPRESA = $cod_empresa AND COD_FILTRO = $cod_filtro ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($conn, trim($sql));
	$qrBuscaFiltro = mysqli_fetch_assoc($arrayQuery);

	$cod_filtro = $qrBuscaFiltro['COD_FILTRO'];
	$qtd_diashist = $qrBuscaFiltro['QTD_DIASHIST'];
	$qtd_mesclass = $qrBuscaFiltro['QTD_MESCLASS'];
	$dt_filtro = $qrBuscaFiltro['DT_FILTRO'];
}

$dat_ini = fnDatasql($dat_ini);
$dat_fim = fnDatasql($dat_fim);

$sqlPeriodo = "SELECT COD_FILTRO, DT_FILTRO FROM filtro_frequencia where COD_EMPRESA = $cod_empresa order by DT_FILTRO desc ";
$arrayQueryPeriodo = mysqli_query($conn, trim($sqlPeriodo));

$qtd_periodos = mysqli_num_rows($arrayQueryPeriodo);

if ($qtd_periodos == 0) {
	$msgTipo = "alert-danger";
	$msgRetorno = "Você ainda <b>não possui</b> a configuração para utilizar essa tela. <br/> Entre em <b>contato</b> com o seu <b>consultor</b>.";
}

//fnEscreve($dt_filtro);
// fnEscreve($cod_filtro);

?>

<div class="push30"></div>

<div class="row">
	<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
		<div class="col-md12 margin-bottom-30">
			<!-- Portlet -->
			<div class="portlet portlet-bordered">
				<div class="portlet-title">
					<div class="caption">
						<i class="fal fa-terminal"></i>
						<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa ?></span>
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
									<div class="form-group">
										<label for="inputName" class="control-label">Período </label>
										<select data-placeholder="Selecione o período" name="COD_FILTRO" id="COD_FILTRO" class="chosen-select-deselect">
											<option value=""></option>
											<?php

											while ($qrListaFiltro = mysqli_fetch_assoc($arrayQueryPeriodo)) {
												echo "
																				  <option value='" . $qrListaFiltro['COD_FILTRO'] . "'>" . date("m/Y", strtotime($qrListaFiltro['DT_FILTRO'])) . " " . $ano . "</option> 
																				";
											}
											?>
										</select>
										<script>
											$("#formulario #COD_FILTRO").val("<?php echo $cod_filtro; ?>").trigger("chosen:updated");
										</script>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="push20"></div>
									<button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-sm btn-block getBtn"><i class="fal fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
								</div>

							</div>

						</fieldset>

						<input type="hidden" name="NUM_ORDENAC" id="NUM_ORDENAC" value="">

						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
						<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">

						<div class="push5"></div>


						<!--login-form-->
					</div>
					<!--portlet-body-->
				</div>
				<!--portlet-title-->
			</div>
			<!-- fim Portlet -->
		</div>
	</form>

</div>

<style>
	.circle {
		display: block;
		border-radius: 50%;
		height: 75px;
		width: 75px;
		margin: auto;
		padding: 24px 0;
	}

	.circle span {
		font-size: 20px;
		color: #ffffff;
		font-weight: bold;
	}

	.circle2 {
		display: block;
		border-radius: 50%;
		height: 75px;
		width: 75px;
		margin: auto;
		padding: 28px 0;
	}

	.circle2 span {
		font-size: 17px;
		color: #ffffff;
		font-weight: bold;
	}

	.corBase {
		background: #F8F9F9;
	}

	.cor1 {
		background: #EC7063;
	}

	.cor2 {
		background: #F4D03F;
	}

	.cor3 {
		background: #58D68D;
	}

	.cor4 {
		background: #5DADE2;
	}

	.cor5 {
		background: #909497;
	}

	.fCor1 {
		color: #EC7063;
	}

	.fCor2 {
		color: #F4D03F;
	}

	.fCor3 {
		color: #58D68D;
	}

	.fCor4 {
		color: #5DADE2;
	}

	.fCor5 {
		color: #909497;
	}

	.cor1on {
		background: #CB4335;
		font-size: 18px !important;
	}

	.cor2on {
		background: #D4AC0D;
		font-size: 18px !important;
	}

	.cor3on {
		background: #239B56;
		font-size: 18px !important;
	}

	.cor4on {
		background: #2874A6;
		font-size: 18px !important;
	}

	.bar {
		font-size: 16px;
		line-height: 50px;
		height: 50px;
		border-radius: 5px;
		color: #ffffff;
		font-weight: bold;
		text-align: left;
		margin: auto;
	}

	.f26b {
		font-size: 30px;
		font-weight: bold;
	}

	.bar span {
		background: rgba(255, 255, 255, 0.3);
		padding: 6px 9px;
		border-radius: 4px;
		margin-left: 15px;
		font-size: 18px;
	}

	.tooltip.top .tooltip-inner {
		color: #3c3c3c;
		width: 120px;
		height: 40px;
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
</style>

<?php
if ($qtd_periodos > 0) {
?>

	<div class="row">

		<div class="col-md12 margin-bottom-30">
			<!-- Portlet -->
			<div class="portlet portlet-bordered">

				<div class="portlet-body">

					<div class="login-form">

						<div class="row">




							<?php

							$dataConsulta = substr($dt_filtro, 0, 4) . "-" . substr($dt_filtro, 5, 2);
							//fnEscreve($dataConsulta);
							$sql = "CALL SP_RELAT_LUCRO_FREQUENCIA_UNIVEND('$lojasSelecionadas', $cod_empresa, $cod_filtro, '$dataConsulta')";
							// fnEscreve($sql);
							$arrayQuery = mysqli_query($conn, $sql);

							$count = 0;
							$bar = [];
							$cliente = [];
							$gm = [];
							$im = [];
							$nomeFaixa = [];
							$resgate_total = [];
							$vvr = [];
							$resgTot = 0;

							while ($qrFunil = mysqli_fetch_assoc($arrayQuery)) {

								$bar[$count] = fnValor($qrFunil['PERC_QTD_CLIENTES'], 0);
								$faixa[$count] = $qrFunil['DESCRICAO_FAIXA'];
								$cliente[$count] = $qrFunil['QTD_CLIENTES'];
								$gm[$count] = $qrFunil['GM'];
								$im[$count] = $qrFunil['MEDIA_IDADE'];
								$resgate_total[$count] = $qrFunil['VL_RESGATE_TOTAL'];
								$vvr[$count] = $qrFunil['PERC_VVR'];
								$vvr_medio[$count] = $qrFunil['VVR_MEDIO'];
								$qtd_resgate[$count] = $qrFunil['QTD_RESGATE'];
								$media_vr[$count] = $qrFunil['VL_RESGATE_MEDIO'];
								$total_vvr[$count] = $qrFunil['TOTAL_VVR'];
								$resgTot += $qrFunil['VL_RESGATE_TOTAL'];
								$vl_lucro_total[$count] = $qrFunil['VL_LUCRO_TOTAL'];
								$qtd_transacoes[$count] = $qrFunil['QTD_TRANSACOES'];
								$qtd_total_item[$count] = $qrFunil['QTD_TOTAL_ITEM'];
								$qtd_media_compra[$count] = $qrFunil['QTD_MEDIA_COMPRA'];
								$qtd_media_item[$count] = $qrFunil['QTD_MEDIA_ITEM'];
								$qtd_cliente_resg[$count] = $qrFunil['QTD_CLIENTE_RESGATE'];

								//fnEscreve($vvr[$count]);

								$count++;
							}

							/*
												fnEscreve($vvr_medio['0']);
												fnEscreve($vvr_medio['1']);
												fnEscreve($vvr_medio['2']);
												fnEscreve($vvr_medio['3']);
												
												fnEscreve($total_vvr['0']);
												fnEscreve($total_vvr['1']);
												fnEscreve($total_vvr['2']);
												fnEscreve($total_vvr['3']);
												
												fnEscreve($qtd_cliente_resg['0']);
												fnEscreve($qtd_cliente_resg['1']);
												fnEscreve($qtd_cliente_resg['2']);
												fnEscreve($qtd_cliente_resg['3']);											

												*/



							$perc_vvr1 = isset($total_vvr[0], $resgate_total[0]) && $resgate_total[0] != 0
								? (($total_vvr[0] - $resgate_total[0]) / $resgate_total[0]) * 100
								: 0;

							$perc_vvr2 = isset($total_vvr[1], $resgate_total[1]) && $resgate_total[1] != 0
								? (($total_vvr[1] - $resgate_total[1]) / $resgate_total[1]) * 100
								: 0;

							$perc_vvr3 = isset($total_vvr[2], $resgate_total[2]) && $resgate_total[2] != 0
								? (($total_vvr[2] - $resgate_total[2]) / $resgate_total[2]) * 100
								: 0;

							$perc_vvr4 = isset($total_vvr[3], $resgate_total[3]) && $resgate_total[3] != 0
								? (($total_vvr[3] - $resgate_total[3]) / $resgate_total[3]) * 100
								: 0;

							$resg1 = isset($resgate_total[0]) && $resgTot != 0
								? ($resgate_total[0] / $resgTot) * 100
								: 0;

							$resg2 = isset($resgate_total[1]) && $resgTot != 0
								? ($resgate_total[1] / $resgTot) * 100
								: 0;

							$resg3 = isset($resgate_total[2]) && $resgTot != 0
								? ($resgate_total[2] / $resgTot) * 100
								: 0;

							$resg4 = isset($resgate_total[3]) && $resgTot != 0
								? ($resgate_total[3] / $resgTot) * 100
								: 0;

							$freq1 = isset($gm[0]) && $gm[0] != 0 ? ($gm[0] / $gm[0]) : 0;
							$freq2 = isset($gm[1], $gm[0]) && $gm[0] != 0 ? ($gm[1] / $gm[0]) : 0;
							$freq3 = isset($gm[2], $gm[0]) && $gm[0] != 0 ? ($gm[2] / $gm[0]) : 0;
							$freq4 = isset($gm[3], $gm[0]) && $gm[0] != 0 ? ($gm[3] / $gm[0]) : 0;
							$freq5 = isset($gm[4], $gm[0]) && $gm[0] != 0 ? ($gm[4] / $gm[0]) : 0;


							$qtd_media_compra1 = $qtd_media_compra['0'];
							$qtd_media_compra2 = $qtd_media_compra['1'];
							$qtd_media_compra3 = $qtd_media_compra['2'];
							$qtd_media_compra4 = $qtd_media_compra['3'];
							$qtd_media_compra5 = @$qtd_media_compra['4'];

							$qtd_media_item1 = $qtd_media_compra['0'];
							$qtd_media_item2 = $qtd_media_compra['1'];
							$qtd_media_item3 = $qtd_media_compra['2'];
							$qtd_media_item4 = $qtd_media_compra['3'];

							$pa1 = $qtd_transacoes['0'] != 0 ? ($qtd_total_item['0'] / $qtd_transacoes['0']) : 0;
							$pa2 = $qtd_transacoes['1'] != 0 ? ($qtd_total_item['1'] / $qtd_transacoes['1']) : 0;
							$pa3 = $qtd_transacoes['2'] != 0 ? ($qtd_total_item['2'] / $qtd_transacoes['2']) : 0;
							$pa4 = $qtd_transacoes['3'] != 0 ? ($qtd_total_item['3'] / $qtd_transacoes['3']) : 0;

							$bar2Calc = 70;
							$bar3Calc = 55;
							$bar4Calc = 35;

							//fnEscreve($qtd_diashist);
							//fnEscreve($qtd_mesclass);
							//fnEscreve($qtd_total_item['0']);
							//fnEscreve($qtd_transacoes['0']);

							$Med_vr1 = $qtd_transacoes['0'] != 0 ? ($qtd_total_item['0'] / $qtd_transacoes['0']) : 0;
							$Med_vr2 = $qtd_transacoes['1'] != 0 ? ($qtd_total_item['1'] / $qtd_transacoes['1']) : 0;
							$Med_vr3 = $qtd_transacoes['2'] != 0 ? ($qtd_total_item['2'] / $qtd_transacoes['2']) : 0;
							$Med_vr4 = $qtd_transacoes['3'] != 0 ? ($qtd_total_item['3'] / $qtd_transacoes['3']) : 0;

							$mes = date("m", strtotime($dt_filtro)); // Mês desejado, pode ser por ser obtido por POST, GET, etc.
							$ano = date("Y", strtotime($dt_filtro)); // Ano atual
							$ultimo_dia = date("t", mktime(0, 0, 0, $mes, '01', $ano)); // Mágica, plim!
							$ultima_data = $ultimo_dia . "/" . $mes . "/" . $ano;
							$dt_filtro_fin = $ano . "-" . $mes . "-" . $ultimo_dia;
							//fnEscreve($dt_filtro_fin);
							$dias_periodo = $qtd_diashist + 1;
							$dt_filtroMenor = date('Y-m-d', strtotime($dt_filtro_fin . '-' . $dias_periodo . ' days'));

							switch ($qtd_mesclass) {
								case 12:
									$classifica = "Anual";
									break;
								case 6:
									$classifica = "Semestral";
									break;
								case 4:
									$classifica = "Quadrimestral";
									break;
								case 3:
									$classifica = "Trimestral";
									break;
								case 2:
									$classifica = "Bimestral";
									break;
								case 1:
									$classifica = "Mensal";
									break;
								case 0:
									$classifica = "Online (a cada venda)";
									break;
							}

							?>

							<div class="row text-center">

								<div class="form-group text-center col-lg-12">

									<h4>Dados do Ciclo de Recompra</h4>

								</div>

								<div class="push30"></div>

								<div class="col-md-2"></div>

								<div class="col-md-2 text-center text-info">
									<i class="fal fa-calendar-alt fa-3x" aria-hidden="true"></i>
									<div class="push10"></div>
									<b><?= fnDataShort($dt_filtroMenor) ?> </b>
									<div class="push10"></div>
									<small style="font-weight:normal;">Clientes cadastrados anterior a esta data</small>
								</div>

								<div class="col-md-2 text-center text-info">
									<i class="fal fa-sync fa-3x" aria-hidden="true"></i>
									<div class="push10"></div>
									<b><?= $classifica; ?></b>
									<div class="push10"></div>
									<small style="font-weight:normal;">Periodicidade configurada para atualização <br /><small>(base ref. 01/jan)</small></small>
								</div>

								<div class="col-md-2 text-center text-info">
									<i class="fal fa-shopping-cart fa-3x" aria-hidden="true"></i>
									<div class="push10"></div>
									<b><?= fnDataShort($dt_filtroMenor) ?> a <?= fnDataShort($dt_filtro_fin); ?></b>
									<div class="push10"></div>
									<small style="font-weight:normal;">Com compras neste período</small>
								</div>

								<div class="col-md-2 text-center text-info">
									<i class="fal fa-history fa-3x" aria-hidden="true"></i>
									<div class="push10"></div>
									<b><?= $qtd_diashist ?></b>
									<div class="push10"></div>
									<small style="font-weight:normal;">Período previsto para retorno do cliente <br /><small>(dias)</small></small>
								</div>

								<div class="col-md-2"></div>

							</div>

							<div class="push50"></div>

							<div class="row text-center">

								<div class="form-group text-center col-lg-12">

									<h4>Índice de Rentabilidade por Perfil de Clientes</h4>

									<div class="push50"></div>

									<table class="table table-striped">
										<thead>
											<tr>
												<th class="text-center f18" colspan="2">CONCENTRAÇÃO DE CLIENTES</th>
												<th class="text-center f18">TIPO DE CLIENTE</th>
												<th class="text-center f18">MÉDIA IC</th>
												<th class="text-center f18">MÉDIA IA</th>
												<th class="text-center f18">GASTO MÉDIO</th>
												<th class="text-center f18">RENTABILIDADE</th>
												<th></th>
											</tr>
										</thead>
										<tbody>

											<tr>

												<td style="width: 50px;"></td>

												<td class="text-center">
													<div class="push30"></div>
													<div class="bar cor1" style="width: -webkit-calc(100%);"><span><?= @$bar['0'] ?>%</span>&nbsp; <?= fnValor(@$cliente['0'], 0); ?> </div>
												</td>

												<td class="text-center">
													<div class="push10"></div>
													<?php
													$compara1 = (round($freq2 - 1));
													$qtd_compras1 = round($compara1);
													if ($qtd_media_compra1 <= 1) {
														//if ($freq1 >= $compara1){
														//$txt_compras1 = "1 compra no período";														  
														$txt_compras1 = fnValor($qtd_media_compra1, 1) . " compras médias no período";
													} else {
														//$txt_compras1 = round($freq1)." a ".round($compara1)." compras no período";	
														$txt_compras1 = fnValor($qtd_media_compra1, 1) . " compras médias no período";
													}
													//for ($i=0; $i < round($freq1); $i++) {
													for ($i = 0; $i < round($qtd_media_compra1); $i++) {
														echo "<i class='fas fa-male fa-2x fCor1' style='margin: 0 3px 0 0;'></i>";
													}
													?>
													<div class="push5"></div>
													<span class="f18 fCor1"><b><?php echo @$faixa['0']; ?></b></span>
													<div class="push3"></div>
													<span class="f12 fCor1"><small><?= fnValor(@$im['0'], 0) ?> anos </small></span>
													<div class="push3"></div>
													<span class="f13 fCor1"><b><?php echo $txt_compras1; ?></b></span>
												</td>

												<td class="text-center">
													<div class="push30"></div>
													<span class="f21b fCor1"><b><?php echo fnValor($qtd_media_item['0'], 0); ?></b></span>
												</td>

												<td class="text-center">
													<div class="push30"></div>
													<span class="f21b fCor1"><b><?php echo fnValor($pa1, 1); ?></b></span>
												</td>

												<td class="text-center">
													<div class="push30"></div>
													<span class="f21b fCor1"><b>R$ <?php echo fnValor(@$gm['0'], 2); ?></b></span>
												</td>

												<td class="text-center">
													<div class="push30"></div>
													<span class="f26b fCor1"><b><?= round($freq1) ?>x</b></span>
												</td>

											</tr>

											<tr>

												<td style="width: 50px;"></td>

												<td class="text-center">
													<div class="push30"></div>
													<div class="bar cor2" style="width: -webkit-calc(80%);"><span><?= @$bar['1'] ?>%</span>&nbsp; <?= fnValor(@$cliente['1'], 0); ?> </div>
												</td>

												<td class="text-center">
													<div class="push10"></div>
													<?php
													$compara2 = (round($freq3 - 1));
													$qtd_compras2 = round($compara2);
													$txt_compras2 = fnValor($qtd_media_compra2, 1) . " compras médias no período";
													for ($i = 0; $i < round($qtd_media_compra2); $i++) {
														echo "<i class='fas fa-male fa-2x fCor2' style='margin: 0 3px 0 0;'></i>";
													}
													?>
													<div class="push5"></div>
													<span class="f18 fCor2"><b><?php echo @$faixa['1']; ?></b></span>
													<div class="push3"></div>
													<span class="f12 fCor2"><small><?= fnValor(@$im['1'], 0) ?> anos </small></span>
													<div class="push3"></div>
													<span class="f13 fCor2"><b><?php echo $txt_compras2; ?></b></span>
												</td>

												<td class="text-center">
													<div class="push30"></div>
													<span class="f21b fCor2"><b><?php echo fnValor($qtd_media_item['1'], 0); ?></b></span>
												</td>

												<td class="text-center">
													<div class="push30"></div>
													<span class="f21b fCor1"><b><?php echo fnValor($pa2, 1); ?></b></span>
												</td>

												<td class="text-center">
													<div class="push30"></div>
													<span class="f21b fCor2"><b>R$ <?php echo fnValor(@$gm['1'], 2); ?></b></span>
												</td>

												<td class="text-center">
													<div class="push30"></div>
													<span class="f26b fCor2"><b> <?= round($freq2) ?>x </b></span>
												</td>

											</tr>

											<tr>

												<td style="width: 50px;"></td>

												<td class="text-center">
													<div class="push30"></div>
													<div class="bar cor3" style="width: -webkit-calc(65%);"><span><?= @$bar['2'] ?>%</span>&nbsp; <?= fnValor(@$cliente['2'], 0); ?></div>
												</td>

												<td class="text-center">
													<div class="push10"></div>
													<?php
													$compara3 = (round($freq4 - 1));
													$qtd_compras3 = round($compara3);
													$txt_compras3 = fnValor($qtd_media_compra3, 1) . " compras médias no período";
													for ($i = 0; $i < round($qtd_media_compra3); $i++) {
														echo "<i class='fas fa-male fa-2x fCor3' style='margin: 0 3px 0 0;'></i>";
													}
													?>
													<div class="push5"></div>
													<span class="f18 fCor3"><b><?php echo @$faixa['2']; ?></b></span>
													<div class="push3"></div>
													<span class="f12 fCor3"><small><?= fnValor(@$im['2'], 0) ?> anos </small></span>
													<div class="push3"></div>
													<span class="f13 fCor3"><b><?php echo $txt_compras3; ?></b></span>
												</td>

												<td class="text-center">
													<div class="push30"></div>
													<span class="f21b fCor3"><b><?php echo fnValor($qtd_media_item['2'], 0); ?></b></span>
												</td>

												<td class="text-center">
													<div class="push30"></div>
													<span class="f21b fCor1"><b><?php echo fnValor($pa3, 1); ?></b></span>
												</td>

												<td class="text-center">
													<div class="push30"></div>
													<span class="f21b fCor3"><b>R$ <?php echo fnValor(@$gm['2'], 2); ?></b></span>
												</td>

												<td class="text-center">
													<div class="push30"></div>
													<span class="f26b fCor3"><b><?= round($freq3) ?>x</b></span>
												</td>

											</tr>

											<tr>

												<td style="width: 50px;"></td>

												<td class="text-center">
													<div class="push30"></div>
													<div class="bar cor4" style="width: -webkit-calc(50%);"><span><?= @$bar['3'] ?>%</span>&nbsp; <?= fnValor(@$cliente['3'], 0); ?></div>

													<div class="push5"></div>

													<?php
													//fnEscreve(fnValor( ($cliente['0']+$cliente['1']+$cliente['2']+$cliente['3']),0));
													?>

												</td>

												<td class="text-center">
													<div class="push5"></div>
													<?php
													$compara4 = round($freq4);
													$txt_compras4 = fnValor($qtd_media_compra4, 1) . " compras médias no período";
													for ($i = 0; $i < round($qtd_media_compra4); $i++) {
														echo "<i class='fas fa-male fa-2x fCor4' style='margin: 0 3px 0 0;'></i>";
													}
													?>
													<div class="push5"></div>
													<span class="f18 fCor4"><b><?php echo @$faixa['3']; ?></b></span>
													<div class="push3"></div>
													<span class="f12 fCor4"><small><?= fnValor(@$im['3'], 0) ?> anos </small></span>
													<div class="push3"></div>
													<span class="f13 fCor4"><b><?php echo $txt_compras4; ?> </b></span>
												</td>

												<td class="text-center">
													<div class="push30"></div>
													<span class="f21b fCor4"><b><?php echo fnValor($qtd_media_item['3'], 0); ?></b></span>
												</td>

												<td class="text-center">
													<div class="push30"></div>
													<span class="f21b fCor1"><b><?php echo fnValor($pa4, 1); ?></b></span>
												</td>

												<td class="text-center">
													<div class="push30"></div>
													<span class="f21b fCor4"><b>R$ <?php echo fnValor(@$gm['3'], 2); ?></b></span>
												</td>

												<td class="text-center">
													<div class="push30"></div>
													<span class="f26b fCor4"><b><?= round($freq4) ?>x</b></span>
												</td>

											</tr>

										</tbody>
									</table>

									<div class="push50"></div>

									<!-- ======================================================================================== Lista ======================================================================== -->

									<table class="table table-striped">
										<thead>
											<tr>
												<th scope="row"></th>
												<th class="text-center f16b">FATURAMENTO</th>
												<th class="text-center f16b">TRANSAÇÕES</th>
												<th class="text-center f16b">ITENS</th>
												<th class="text-center f16b">TICKET MÉDIO</th>
												<th class="text-center f16b">CLIENTES RESGATES</th>
												<th class="text-center f16b">TOTAL VR</th>
												<th class="text-center f16b">QTD. RESGATES</th>
												<th class="text-center f16b">MÉDIA VR</th>
												<th class="text-center f16b">VVR</th>
												<th class="text-center f16b">MÉDIA VVR%</th>
											</tr>
										</thead>
										<tbody>

											<tr>
												<th scope="row"><span class="f21 fCor1"><i class='fas fa-male fCor1' style='margin: 0 3px 0 0;'></i>&nbsp;<?php echo @$faixa['0']; ?></th>

												<td class="text-center">
													<span class="f16">R$ <?= fnValor($vl_lucro_total['0'], 2) ?></span>
												</td>

												<td class="text-center">
													<span class="f16"><?= fnValor($qtd_transacoes['0'], 0) ?></span>
												</td>

												<td class="text-center">
													<span class="f16"><?= fnValor($qtd_total_item['0'], 0) ?></span>
												</td>

												<td class="text-center">
													<span class="f16">R$ <?= fnValor($qtd_transacoes['0'] != 0 ? ($vl_lucro_total['0'] / $qtd_transacoes['0']) : 0, 2) ?></span>
												</td>

												<td class="text-center">
													<span class="f16"><?= fnValor($qtd_cliente_resg['0'], 0) ?></span>
												</td>

												<td class="text-center">
													<span class="f16">R$ <?= fnValor(@$resgate_total['0'], 2) ?></span>
												</td>

												<td class="text-center">
													<span class="f16"><?= fnValor($qtd_resgate['0'], 0) ?></span>
												</td>

												<td class="text-center">
													<span class="f16">R$ <?= fnValor(@$media_vr['0'], 2) ?></span>
												</td>

												<td class="text-center">
													<span class="f16">R$ <?= fnValor(@$total_vvr['0'], 2) ?></span>
												</td>

												<td class="text-center">
													<span class="f16"><?= fnValor($perc_vvr1, 2) ?>%</span>
												</td>

											</tr>

											<tr>
												<th scope="row"><span class="f21 fCor2"><i class='fas fa-male fCor2' style='margin: 0 3px 0 0;'></i>&nbsp;<?php echo @$faixa['1']; ?></th>

												<td class="text-center">
													<span class="f16">R$ <?= fnValor($vl_lucro_total['1'], 2) ?></span>
												</td>

												<td class="text-center">
													<span class="f16"><?= fnValor($qtd_transacoes['1'], 0) ?></span>
												</td>

												<td class="text-center">
													<span class="f16"><?= fnValor($qtd_total_item['1'], 0) ?></span>
												</td>

												<td class="text-center">
													<span class="f16">R$ <?= fnValor($qtd_transacoes['1'] != 0 ? ($vl_lucro_total['1'] / $qtd_transacoes['1']) : 0, 2) ?></span>
												</td>

												<td class="text-center">
													<span class="f16"><?= fnValor($qtd_cliente_resg['1'], 0) ?></span>
												</td>

												<td class="text-center">
													<span class="f16">R$ <?= fnValor(@$resgate_total['1'], 2) ?></span>
												</td>

												<td class="text-center">
													<span class="f16"><?= fnValor($qtd_resgate['1'], 0) ?></span>
												</td>

												<td class="text-center">
													<span class="f16">R$ <?= fnValor(@$media_vr['1'], 2) ?></span>
												</td>

												<td class="text-center">
													<span class="f16">R$ <?= fnValor(@$total_vvr['1'], 2) ?></span>
												</td>

												<td class="text-center">
													<span class="f16"><?= fnValor($perc_vvr2, 2) ?>%</span>
												</td>

											</tr>

											<tr>
												<th scope="row"><span class="f21 fCor3"><i class='fas fa-male fCor3' style='margin: 0 3px 0 0;'></i>&nbsp;<?php echo @$faixa['2']; ?></th>

												<td class="text-center">
													<span class="f16">R$ <?= fnValor($vl_lucro_total['2'], 2) ?></span>
												</td>

												<td class="text-center">
													<span class="f16"><?= fnValor($qtd_transacoes['2'], 0) ?></span>
												</td>

												<td class="text-center">
													<span class="f16"><?= fnValor($qtd_total_item['2'], 0) ?></span>
												</td>

												<td class="text-center">
													<span class="f16">R$ <?= fnValor($qtd_transacoes['2'] != 0 ? ($vl_lucro_total['2'] / $qtd_transacoes['2']) : 0, 2) ?></span>
												</td>

												<td class="text-center">
													<span class="f16"><?= fnValor($qtd_cliente_resg['2'], 0) ?></span>
												</td>

												<td class="text-center">
													<span class="f16">R$ <?= fnValor(@$resgate_total['2'], 2) ?></span>
												</td>

												<td class="text-center">
													<span class="f16"><?= fnValor($qtd_resgate['2'], 0) ?></span>
												</td>

												<td class="text-center">
													<span class="f16">R$ <?= fnValor(@$media_vr['2'], 2) ?></span>
												</td>

												<td class="text-center">
													<span class="f16">R$ <?= fnValor(@$total_vvr['2'], 2) ?></span>
												</td>

												<td class="text-center">
													<span class="f16"><?= fnValor($perc_vvr3, 2) ?>%</span>
												</td>

											</tr>

											<tr>
												<th scope="row"><span class="f21 fCor4"><i class='fas fa-male fCor4' style='margin: 0 3px 0 0;'></i>&nbsp;<span class=""><?php echo @$faixa['3']; ?></th>

												<td class="text-center">
													<span class="f16">R$ <?= fnValor($vl_lucro_total['3'], 2) ?></span>
												</td>

												<td class="text-center">
													<span class="f16"><?= fnValor($qtd_transacoes['3'], 0) ?></span>
												</td>

												<td class="text-center">
													<span class="f16"><?= fnValor($qtd_total_item['3'], 0) ?></span>
												</td>

												<td class="text-center">
													<span class="f16">R$ <?= fnValor($qtd_transacoes['3'] != 0 ? ($vl_lucro_total['3'] / $qtd_transacoes['3']) : 0, 2) ?></span>
												</td>

												<td class="text-center">
													<span class="f16"><?= fnValor($qtd_cliente_resg['3'], 0) ?></span>
												</td>

												<td class="text-center">
													<span class="f16">R$ <?= fnValor(@$resgate_total['3'], 2) ?></span>
												</td>

												<td class="text-center">
													<span class="f16"><?= fnValor($qtd_resgate['3'], 0) ?></span>
												</td>

												<td class="text-center">
													<span class="f16">R$ <?= fnValor(@$media_vr['3'], 2) ?></span>
												</td>

												<td class="text-center">
													<span class="f16">R$ <?= fnValor(@$total_vvr['3'], 2) ?></span>
												</td>

												<td class="text-center">
													<span class="f16"><?= fnValor($perc_vvr4, 2) ?>%</span>
												</td>

											</tr>

										<tfoot>
											<td class="text-center">
												<span class="f21"><small><b><?= fnValor((@$cliente['0'] + @$cliente['1'] + @$cliente['2'] + @$cliente['3']), 0) ?></b></small></span>
											</td>
											<td class="text-center">
												<span class="f18"><small><b>R$ <?= fnValor(($vl_lucro_total['0'] + $vl_lucro_total['1'] + $vl_lucro_total['2'] + $vl_lucro_total['3']), 2) ?></b></small></span>
											</td>
											<td class="text-center">
												<span class="f18"><small><b><?= fnValor(($qtd_transacoes['0'] + $qtd_transacoes['1'] + $qtd_transacoes['2'] + $qtd_transacoes['3']), 0) ?></b></small></span>
											</td>
											<td class="text-center">
												<span class="f18"><small><b><?= fnValor(($qtd_total_item['0'] + $qtd_total_item['1'] + $qtd_total_item['2'] + $qtd_total_item['3']), 0) ?></b></small></span>
											</td>
											<td class="text-center"></td>
											<td class="text-center">
												<span class="f18"><small><b><?= fnValor(($qtd_cliente_resg['0'] + $qtd_cliente_resg['1'] + $qtd_cliente_resg['2'] + $qtd_cliente_resg['3']), 0) ?></b></small></span>
											</td>
											<td class="text-center">
												<span class="f18"><small><b>R$ <?= fnValor((@$resgate_total['0'] + @$resgate_total['1'] + @$resgate_total['2'] + @$resgate_total['3']), 2) ?></b></small></span>
											</td>
											<td class="text-center">
												<span class="f18"><small><b><?= fnValor(($qtd_resgate['0'] + $qtd_resgate['1'] + $qtd_resgate['2'] + $qtd_resgate['3']), 0) ?></b></small></span>
											</td>
											<td class="text-center"></td>
											<td class="text-center">
												<span class="f18"><small><b>R$ <?= fnValor((@$total_vvr['0'] + @$total_vvr['1'] + @$total_vvr['2'] + @$total_vvr['3']), 2) ?></b></small></span>
											</td>
										</tfoot>

										</tbody>
									</table>

									<div class="push30"></div>

								</div>

								<div class="push10"></div>

								<div style="display: none;">
									<h4>Novo Ciclo</h4>

									<div class="push50"></div>

									<table class="table table-striped">
										<thead>
											<tr>
												<th></th>
												<th class="text-center f18">CONCENTRAÇÃO DE CLIENTE</th>
												<th class="text-center f18">CONSUMO</th>
												<th class="text-center f18">GASTO MÉDIO</th>
												<th class="text-center f18" colspan="2">RENTABILIDADE</th>
											</tr>
										</thead>
										<tbody>

											<tr>
												<th scope="row">&nbsp;</th>
												<td class="text-center">
													<div class="push10"></div>
													<?php
													$compara1 = ($freq5 - 1);
													$qtd_compras5 = round($compara1);
													if ($freq5 > $compara5) {
														$txt_compras5 = "1 compra no período";
													} else {
														$txt_compras5 = round($freq5) . " a " . round($compara5) . " compras no período";
													}
													for ($i = 0; $i < round($freq5); $i++) {
														echo "<i class='fas fa-male fa-2x fCor5' style='margin: 0 3px 0 0;'></i>";
													}
													?>
													<div class="push10"></div>
													<span class="f18 fCor5"><b><?php echo @$faixa['4']; ?></b></span>
													<div class="push3"></div>
													<span class="f12 fCor5"><small><?= fnValor(@$im['4'], 0) ?> anos </small></span>
													<div class="push3"></div>
													<span class="f13 fCor5"><b><?php echo $txt_compras1; ?></b></span>
												</td>
												<td class="text-center">
													<div class="push30"></div>
													<span class="f21b fCor5"><b>R$ <?php echo fnValor(@$gm['4'], 2); ?></b></span>
												</td>
												<td class="text-center">
													<div class="push30"></div>
													<span class="f26b fCor5"><b><?= round($freq5) ?>x</b></span>
												</td>
												<td style="width: 50px;"></td>
												<td>
													<div class="push30"></div>
													<div class="bar cor5" style="width: -webkit-calc(55%);"><span><?= @$bar['4'] ?>%</span>&nbsp; <?= fnValor(@$cliente['4'], 0); ?> </div>
												</td>
											</tr>

										</tbody>
									</table>

									<div class="push20"></div>

									<table class="table table-striped">
										<thead>
											<tr>
												<th scope="row"></th>
												<th class="text-center f18">TOTAL VR</th>
												<th class="text-center f18">QTD. RESGATES</th>
												<th class="text-center f18">MÉDIA VR</th>
												<th class="text-center f18">MÉDIA VVR</th>
												<th class="text-center f18">MÉDIA VVR%</th>
											</tr>
										</thead>
										<tbody>

											<tr>
												<th scope="row"><span class="f21 fCor5"><span class=""><?php echo @$faixa['4']; ?></th>

												<td class="text-center">
													<span class="f18">R$ <?= fnValor(@$resgate_total['4'], 2) ?></span>
												</td>

												<td class="text-center">
													<span class="f18"><?= fnValor($qtd_resgate['4'], 0) ?></span>
												</td>

												<td class="text-center">
													<span class="f18">R$ <?= fnValor(@$media_vr['4'], 2) ?></span>
												</td>

												<td class="text-center">
													<span class="f18">R$ <?= fnValor(@$vvr_medio['4'], 2) ?></span>
												</td>

												<td class="text-center">
													<span class="f18"><?= fnValor(@$vvr['4'], 2) ?>%</span>
												</td>

											</tr>

										</tbody>
									</table>


									<div class="push50"></div>


								</div>


							</div>

							<div class="push20"></div>

						</div>

					</div>
				</div>
				<!-- fim Portlet -->
			<?php
		}
			?>
			</div>

		</div>

	</div>

	<div class="push20"></div>


	<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
	<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />

	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.min.js"></script>
	<script src="js/plugins/Chart_Js/utils.js"></script>

	<script src="js/plugins/ion.rangeSlider.js"></script>

	<script>
		//datas
		$(function() {

			$('.datePicker').datetimepicker({
				viewMode: 'years',
				format: 'MM/YYYY',
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


			//chosen
			$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
			$('#formulario').validator();






		});
	</script>