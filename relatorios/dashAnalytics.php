<?php

if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
//https://adm.bunker.mk/action.do?mod=VU6Q8bfsZp%C2%A30%C2%A2&id=GLtHxidZjko%C2%A2

setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');

$hoje = "";
$dias30 = "";
$dt_filtro = "";
$mes = "";
$mes_nome = "";
$mesAnt = "";
$msgRetorno = "";
$msgTipo = "";
$dt_exibe = "";
$log_labels = "";
$opcao = "";
$hHabilitado = "";
$hashForm = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$dat_cadastr = "";
$dat_ini = "";
$dat_fim = "";
$temUnivend = "";
$sqlPeriodos = "";
$arrayQueryPeriodos = [];
$qtd_periodos = 0;
$qrDt = "";
$mes0 = "";
$mes1 = "";
$mes2 = "";
$mes3 = "";
$mes4 = "";
$mes5 = "";
$mes6 = "";
$mesAniv = "";
$checkLabels = "";
$qrListaFiltro = "";
$data = "";
$partes = "";
$dia = "";
$ano = "";
$mesIni = "";
$mesFim = "";
$lojasSelecionadas = "";
$qrAnalitics = "";
$mes_ano = "";
$qtd_clientes = 0;
$qtd_cli_novos = 0;
$qtd_cli_novos_mes_ant = 0;
$qtd_cli_unicos_mes_ant = 0;
$qtd_masculino = 0;
$qtd_feminino = 0;
$pct_masculino = "";
$pct_feminino = "";
$idade_media = "";
$qtd_idade0 = 0;
$qtd_idade1 = 0;
$qtd_idade2 = 0;
$qtd_idade3 = 0;
$qtd_idade4 = 0;
$qtd_idade5 = 0;
$qtd_idade6 = 0;
$qtd_idade7 = 0;
$qtd_idade8 = 0;
$pct_idade0 = "";
$pct_idade1 = "";
$pct_idade2 = "";
$pct_idade3 = "";
$pct_idade4 = "";
$pct_idade5 = "";
$pct_idade6 = "";
$pct_idade7 = "";
$pct_idade8 = "";
$qtd_email = 0;
$pct_email = "";
$qtd_celular = 0;
$pct_celular = "";
$qtd_nascimento = 0;
$pct_nascimento = "";
$qtd_cep = 0;
$pct_cep = "";
$qtd_endereco = 0;
$pct_endereco = "";
$vl_faturamento_fidelizado = "";
$pct_faturamento_fidelizado = "";
$qtd_transacoes = 0;
$qtd_transacoes_fidelizado = 0;
$qtd_transacoes_avulso = 0;
$pct_transacoes_fidelizado = "";
$pct_transacoes_avulso = "";
$ticket_medio_fidelizado = "";
$ticket_medio_avulso = "";
$pct_fidelizado_anterior = "";
$qtd_inativos = 0;
$qtd_aniversariantes = 0;
$vl_faturamento_aniver = "";
$qtd_cli_expirar = 0;
$vl_faturamento_expirar = "";
$tm_inativos = "";
$vl_gasto_acumulado_inativos = "";
$qtd_20_cli_faturamento = 0;
$vl_total_concentracao_faturamento = 0;
$perc_20_concentracao_faturamento = "";
$vl_gm = "";
$vl_total_resgate = 0;
$qtd_cli_resgate = 0;
$perc_vl_resgate = "";
$qtd_cli_expirado = 0;
$vl_faturamento_cli_expirado = "";
$vl_faturamento_fidelizado_mes_ant = "";
$perc_faturamento_fidelizado_mes_ant = "";
$vl_indice_frequencia = "";
$vl_indice_frequencia_ant = "";
$pct_faturamento_ref = "";
$qtd_transacoes_fidelizado_mes_ant = 0;
$qtd_transacoes_mes_ant = 0;
$qtd_transacoes_avulso_mes_ant = 0;
$qtd_clientes_compraram_mesm0 = 0;
$qtd_clientes_compraram_mesm1 = 0;
$qtd_clientes_compraram_mesm2 = 0;
$qtd_clientes_compraram_mesm3 = 0;
$qtd_clientes_compraram_mesm4 = 0;
$qtd_clientes_compraram_mesm5 = 0;
$qtd_clientes_compraram_mesm6 = 0;
$pct_20_cli_faturamento = "";
$listaTotTmResgate = "";
$listaTotTmSem = "";
$listaTotTmAvulso = "";
$listaTotGmResgate = "";
$listaTotGmSem = "";
$listaTotTransacResgate = "";
$listaTotTransacSem = "";
$listaTotTransacAvulso = "";
$pct_clientes_compraram_mesm0 = "";
$pct_clientes_compraram_mesm1 = "";
$pct_clientes_compraram_mesm2 = "";
$pct_clientes_compraram_mesm3 = "";
$pct_clientes_compraram_mesm4 = "";
$pct_clientes_compraram_mesm5 = "";
$cor_seta_cli = "";
$cor_seta_uni = "";
$qtd_dias_inativo = 0;
$dt_filtro_ini = "";
$pctEngaja = "";
$cadBase = "";
$totCli = "";
$totUnicos = "";
$totNovos = "";
$qrBuscaIndiceDiario = "";
$mes_extenso = "";
$ano_linhas = "";
$MES = "";
$ANOS = "";
$maxEvo = "";
$maxComp = "";
$cor_seta_total = 0;
$cor_seta_transac = "";
$cor_seta_fid = "";
$cor_seta_av = "";
$qtd_clientes_compraram_mes0 = 0;
$cor_seta_fid_ant = "";
$cor_seta_freq = "";
$qtd_transacoes_f = 0;
$pct_tmsr = "";
$pct_tmcr = "";
$sql2 = "";
$top5cli = "";
$arrayQuery2 = [];
$qrAnalitics2 = "";
$qrMes = "";
$qtd_mesclass = 0;
$mesQuestaoFiltro = "";
$anoQuestaoFiltro = "";
$classifica = "";
$dt_filtro_fim = "";
$qrSpan = "";
$cod_filtro = "";
$qrBuscaFiltro = "";
$qtd_diashist = 0;
$ultimo_dia = "";
$ultima_data = "";
$dias_periodo = "";
$dt_filtroMenor = "";
$dataConsulta = "";
$qrFunil = [];
$faixa = [];
$vvr_medio = "";
$qtd_resgate = [];
$media_vr = "";
$media_vvr = "";
$qtd_media_item = [];
$qtd_total_item = [];
$resg1 = "";
$resg2 = "";
$resg3 = "";
$resg4 = "";
$resg5 = "";
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
$bar2Calc = "";
$bar3Calc = "";
$bar4Calc = "";
$compara1 = "";
$qtd_compras1 = 0;
$txt_compras1 = "";
$i = 0;
$compara2 = "";
$qtd_compras2 = 0;
$txt_compras2 = "";
$compara3 = "";
$qtd_compras3 = 0;
$txt_compras3 = "";
$compara4 = "";
$txt_compras4 = "";
$listaFatLmp = "";
$listaFatAv = "";
$listaFatTotRes = "";
$listaFatFid = "";
$listaPctFatAv = "";
$listaFatRes = "";
$vl_faturamento_expirado = "";
$listaTotGmAvulso = "";

$hashLocal = mt_rand();

//inicialização de variáveis
$hoje = fnFormatDate(date("Y-m-d"));
$hoje = fnFormatDate(date('Y-m-d', strtotime($hoje . '- 1 days')));
$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30 . '- 30 days')));
$dt_filtro = "";
$mes = ucfirst(strftime('%B', strtotime($hoje)));
$mes_nome = ucfirst(strftime('%B', strtotime($hoje)));
$mesAnt = ucfirst(strftime('%B', strtotime($hoje)));



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_empresa = fnLimpaCampoZero(@$_POST['COD_EMPRESA']);
		$cod_univend = @$_POST['COD_UNIVEND'];
		$cod_grupotr = @$_REQUEST['COD_GRUPOTR'];
		$cod_tiporeg = @$_REQUEST['COD_TIPOREG'];
		$dt_filtro = @$_REQUEST['DT_FILTRO'];
		$dt_exibe = $dt_filtro . "-01";

		if (empty(@$_REQUEST['LOG_LABELS'])) {
			$log_labels = 'N';
		} else {
			$log_labels = @$_REQUEST['LOG_LABELS'];
		}


		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		// fnescreve($dt_exibe);
		// fnescreve($dt_filtro);

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
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
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

// fnEscreve($dt_filtro);

//busca revendas do usuário
include "unidadesAutorizadas.php";

$sqlPeriodos = "SELECT DISTINCT MESANO FROM TB_FECHAMENTO_CLIENTE where COD_EMPRESA = $cod_empresa order by MESANO desc ";
$arrayQueryPeriodos = mysqli_query(conntemp($cod_empresa, ''), trim($sqlPeriodos));
//fnEscreve($sqlPeriodos);

$qtd_periodos = mysqli_num_rows($arrayQueryPeriodos);

if ($qtd_periodos == 0) {
	$msgTipo = "alert-danger";
	$msgRetorno = "Você ainda <b>não possui</b> a configuração para utilizar essa tela. <br/> Entre em <b>contato</b> com o seu <b>consultor</b>.";
}

if ($dt_filtro == "") {
	$sql = "SELECT MAX(MESANO) AS DT_FILTRO FROM TB_FECHAMENTO_CLIENTE where COD_EMPRESA = $cod_empresa";
	// $sql = "SELECT MAX(DT_FILTRO) AS DT_FILTRO FROM filtro_frequencia where COD_EMPRESA = $cod_empresa";
	$qrDt = mysqli_fetch_assoc(mysqli_query(conntemp($cod_empresa, ''), trim($sql)));
	$dt_filtro = fnmesanosql($qrDt['DT_FILTRO']);
	$dt_exibe = $qrDt['DT_FILTRO'] . "-01";
	$mes = utf8_encode(ucfirst(strftime('%B', strtotime($dt_exibe))));
	$mes_nome = utf8_encode(ucfirst(strftime('%B', strtotime($dt_exibe))));
	$mesAnt = utf8_encode(ucfirst(strftime('%B', strtotime($dt_exibe))));
	$mes0 = utf8_encode(ucfirst(strftime('%B', strtotime($dt_exibe . " -6 months"))));
	$mes1 = utf8_encode(ucfirst(strftime('%B', strtotime($dt_exibe . " -5 months"))));
	$mes2 = utf8_encode(ucfirst(strftime('%B', strtotime($dt_exibe . " -4 months"))));
	$mes3 = utf8_encode(ucfirst(strftime('%B', strtotime($dt_exibe . " -3 months"))));
	$mes4 = utf8_encode(ucfirst(strftime('%B', strtotime($dt_exibe . " -2 months"))));
	$mes5 = utf8_encode(ucfirst(strftime('%B', strtotime($dt_exibe . " -1 months"))));
	$mes6 = utf8_encode(ucfirst(strftime('%B', strtotime($dt_exibe))));

	$mesAniv = utf8_encode(ucfirst(strftime('%B', strtotime($dt_exibe . " +1 months"))));
	// fnEscreve($dt_exibe);
	// fnEscreve($mes);
} else {
	$dt_filtro = fnmesanosql($dt_filtro);
	$dt_exibe = $dt_filtro . "-01";
	$mes = utf8_encode(ucfirst(strftime('%B', strtotime($dt_exibe))));
	$mes_nome = utf8_encode(ucfirst(strftime('%B', strtotime($dt_exibe))));
	$mesAnt = utf8_encode(ucfirst(strftime('%B', strtotime($dt_exibe))));
	$mes0 = utf8_encode(ucfirst(strftime('%B', strtotime($dt_exibe . " -6 months"))));
	$mes1 = utf8_encode(ucfirst(strftime('%B', strtotime($dt_exibe . " -5 months"))));
	$mes2 = utf8_encode(ucfirst(strftime('%B', strtotime($dt_exibe . " -4 months"))));
	$mes3 = utf8_encode(ucfirst(strftime('%B', strtotime($dt_exibe . " -3 months"))));
	$mes4 = utf8_encode(ucfirst(strftime('%B', strtotime($dt_exibe . " -2 months"))));
	$mes5 = utf8_encode(ucfirst(strftime('%B', strtotime($dt_exibe . " -1 months"))));
	$mes6 = utf8_encode(ucfirst(strftime('%B', strtotime($dt_exibe))));

	$mesAniv = utf8_encode(ucfirst(strftime('%B', strtotime($dt_exibe . " +1 months"))));
}
//echo $log_labels;
// $log_labels = 'S';

if ($log_labels == 'S') {
	$checkLabels = "checked";
} else {
	$checkLabels = "";
}


?>

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

	.f30 {
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
</style>


<div class="push30"></div>

<div class="row">

	<div class="col-md-12 margin-bottom-30">
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

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

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
										<select data-placeholder="Selecione o período" name="DT_FILTRO" id="DT_FILTRO" class="chosen-select-deselect">
											<option value=""></option>
											<?php

											while ($qrListaFiltro = mysqli_fetch_assoc($arrayQueryPeriodos))
											//$data = $qrListaFiltro['DT_FILTRO'];
											//$partes = explode("-", $data);
											//$dia = $partes['0'];
											//$mes = $partes['1'];
											//$ano = $partes['2'];


											{
												echo "
													<option value='" . fnmesanosql($qrListaFiltro['MESANO'] . "-01") . "'>" . date("m/Y", strtotime($qrListaFiltro['MESANO'] . "-01")) . " " . $ano . "</option> 
												";
											}
											?>
										</select>
										<script>
											$("#formulario #DT_FILTRO").val("<?php echo $dt_filtro; ?>").trigger("chosen:updated");
										</script>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-1">
									<div class="form-group">
										<label for="inputName" class="control-label">Exibir Legendas</label>
										<div class="push5"></div>
										<label class="switch">
											<input type="checkbox" name="LOG_LABELS" id="LOG_LABELS" class="switch" value="S" <?= $checkLabels ?>>
											<span></span>
										</label>
									</div>
								</div>

								<div class="col-md-2">
									<div class="push20"></div>
									<button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-sm btn-block getBtn"><i class="fal fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
								</div>

							</div>

						</fieldset>

						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
						<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
					</form>

				</div>

			</div>

		</div>
		<!-- fim Portlet -->
	</div>

</div>

<?php
if ($qtd_periodos > 0) {

	// Filtro por Grupo de Lojas
	include "filtroGrupoLojas.php";
	//$mesIni = '2019-08';
	// $mesFim = '2019-09';
	$sql = "CALL SP_RELAT_FECHAMENTO_CLIENTE('$dt_filtro','$lojasSelecionadas', $cod_empresa )";
	// fnEscreve($sql);
	//echo($sql);

	@$arrayQuery = mysqli_query(conntemp($cod_empresa, ''), $sql);
	@$qrAnalitics = mysqli_fetch_assoc($arrayQuery);

	$mes_ano = $qrAnalitics['MES_ANO'];
	$qtd_clientes = $qrAnalitics['QTD_CLIENTES'];
	$qtd_cli_novos = $qrAnalitics['QTD_CLI_NOVOS'];
	$qtd_cli_novos_mes_ant = $qrAnalitics['QTD_CLI_NOVOS_MES_ANT'];
	$qtd_cli_unicos_mes_ant = $qrAnalitics['QTD_CLI_UNICOS_MES_ANT'];
	$qtd_masculino = $qrAnalitics['QTD_MASCULINO'];
	$qtd_feminino = $qrAnalitics['QTD_FEMININO'];
	$pct_masculino = $qrAnalitics['PCT_MASCULINO'];
	$pct_feminino = $qrAnalitics['PCT_FEMININO'];

	$idade_media = $qrAnalitics['IDADE_MEDIA'];
	$qtd_idade0 = $qrAnalitics['QTD_IDADE0'];
	$qtd_idade1 = $qrAnalitics['QTD_IDADE1'];
	$qtd_idade2 = $qrAnalitics['QTD_IDADE2'];
	$qtd_idade3 = $qrAnalitics['QTD_IDADE3'];
	$qtd_idade4 = $qrAnalitics['QTD_IDADE4'];
	$qtd_idade5 = $qrAnalitics['QTD_IDADE5'];
	$qtd_idade6 = $qrAnalitics['QTD_IDADE6'];
	$qtd_idade7 = $qrAnalitics['QTD_IDADE7'];
	$qtd_idade8 = $qrAnalitics['QTD_IDADE8'];

	$pct_idade0 = $qtd_clientes != 0 ? (($qtd_idade0) / $qtd_clientes) * 100 : 0;
	$pct_idade1 = $qtd_clientes != 0 ? (($qtd_idade1) / $qtd_clientes) * 100 : 0;
	$pct_idade2 = $qtd_clientes != 0 ? (($qtd_idade2) / $qtd_clientes) * 100 : 0;
	$pct_idade3 = $qtd_clientes != 0 ? (($qtd_idade3) / $qtd_clientes) * 100 : 0;
	$pct_idade4 = $qtd_clientes != 0 ? (($qtd_idade4) / $qtd_clientes) * 100 : 0;
	$pct_idade5 = $qtd_clientes != 0 ? (($qtd_idade5) / $qtd_clientes) * 100 : 0;
	$pct_idade6 = $qtd_clientes != 0 ? (($qtd_idade6) / $qtd_clientes) * 100 : 0;
	$pct_idade7 = $qtd_clientes != 0 ? (($qtd_idade7) / $qtd_clientes) * 100 : 0;
	$pct_idade8 = $qtd_clientes != 0 ? (($qtd_idade8) / $qtd_clientes) * 100 : 0;

	$qtd_email = $qrAnalitics['QTD_EMAIL'];
	$pct_email = $qrAnalitics['PCT_EMAIL'];
	$qtd_celular = $qrAnalitics['QTD_CELULAR'];
	$pct_celular = $qrAnalitics['PCT_CELULAR'];
	$qtd_nascimento = $qrAnalitics['QTD_NASCIMENTO'];
	$pct_nascimento = $qrAnalitics['PCT_NASCIMENTO'];
	$qtd_cep = $qrAnalitics['QTD_CEP'];
	$pct_cep = $qrAnalitics['PCT_CEP'];
	$qtd_endereco = $qrAnalitics['QTD_ENDERECO'];
	$pct_endereco = $qrAnalitics['PCT_ENDERECO'];

	$vl_faturamento_fidelizado = $qrAnalitics['VL_FATURAMENTO_FIDELIZADO'];
	//$pct_faturamento_fidelizado = $qrAnalitics['PCT_FATURAMENTO_FIDELIZADO'];											
	$qtd_transacoes = $qrAnalitics['QTD_TRANSACOES'];
	$qtd_transacoes_fidelizado = $qrAnalitics['QTD_TRANSACOES_FIDELIZADO'];
	$qtd_transacoes_avulso = $qrAnalitics['QTD_TRANSACOES_AVULSO'];

	$pct_transacoes_fidelizado = $qtd_transacoes != 0 ? (($qtd_transacoes_fidelizado) / $qtd_transacoes) * 100 : 0;
	$pct_transacoes_avulso = $qtd_transacoes != 0 ? (($qtd_transacoes_avulso) / $qtd_transacoes) * 100 : 0;

	$ticket_medio_fidelizado = $qrAnalitics['TICKET_MEDIO_FIDELIZADO'];
	$ticket_medio_avulso = $qrAnalitics['TICKET_MEDIO_AVULSO'];

	$pct_fidelizado_anterior = $qrAnalitics['PCT_FIDELIZADO_ANTERIOR'];
	$qtd_inativos = $qrAnalitics['QTD_INATIVOS'];

	$qtd_aniversariantes = $qrAnalitics['QTD_ANIVERSARIANTES'];
	$vl_faturamento_aniver = $qrAnalitics['VL_FATURAMENTO_ANIVER'];

	$qtd_cli_expirar = $qrAnalitics['QTD_CLI_EXPIRAR'];
	$vl_faturamento_expirar = $qrAnalitics['VL_FATURAMENTO_EXPIRAR'];

	$tm_inativos = $qrAnalitics['TICKET_MEDIO_INATIVO'];
	$vl_gasto_acumulado_inativos = $qrAnalitics['VL_GASTO_ACUMULADO_INATIVOS'];

	$qtd_20_cli_faturamento = $qrAnalitics['QTD_20_CLI_FATURAMENTO'];
	$vl_total_concentracao_faturamento = $qrAnalitics['VL_TOTAL_CONCENTRACAO_FATURAMENTO'];
	$perc_20_concentracao_faturamento = @$qrAnalitics['PERC_20_CONCENTRACAO_FATURAMENTO'];

	$vl_gm = $qrAnalitics['VL_GM'];
	$qtd_transacoes = $qrAnalitics['QTD_TRANSACOES'];

	$vl_total_resgate = $qrAnalitics['VL_TOTAL_RESGATE'];
	$qtd_cli_resgate = $qrAnalitics['QTD_CLI_RESGATE'];
	$perc_vl_resgate = $qrAnalitics['PERC_VL_RESGATE'];
	$qtd_cli_expirado = $qrAnalitics['QTD_CLI_EXPIRADO'];

	$vl_faturamento_cli_expirado = $qrAnalitics['VL_FATURAMENTO_CLI_EXPIRADO'];

	$vl_faturamento_fidelizado_mes_ant = $qrAnalitics['VL_FATURAMENTO_FIDELIZADO_MES_ANT'];
	$perc_faturamento_fidelizado_mes_ant = @$qrAnalitics['PERC_FIDELIZADO_ANTERIOR'];

	$vl_indice_frequencia = $qrAnalitics['VL_INDICE_FREQUENCIA'];
	$vl_indice_frequencia_ant = $qrAnalitics['VL_INDICE_FREQUENCIA_ANT'];
	//fnEscreve($qrAnalitics['VL_INDICE_FREQUENCIA']);
	//fnEscreve($qrAnalitics['VL_INDICE_FREQUENCIA_ANT']);

	$pct_faturamento_fidelizado = $vl_faturamento_fidelizado_mes_ant != 0 ?  (($vl_faturamento_fidelizado - $vl_faturamento_fidelizado_mes_ant) / $vl_faturamento_fidelizado_mes_ant) * 100 : 0;
	$pct_faturamento_ref = $qrAnalitics['VL_FATURAMENTO'] != 0 ? (($vl_faturamento_fidelizado) / $qrAnalitics['VL_FATURAMENTO']) * 100 : 0;

	//fnEscreve($qrAnalitics['VL_FATURAMENTO_FIDELIZADO']);
	//fnEscreve($qrAnalitics['VL_FATURAMENTO']);
	//fnEscreve($pct_faturamento_ref);

	$qtd_transacoes_fidelizado_mes_ant = $qrAnalitics['QTD_TRANSACOES_FIDELIZADO_MES_ANT'];
	$qtd_transacoes_mes_ant = $qrAnalitics['QTD_TRANSACOES_MES_ANT'];
	$qtd_transacoes_avulso_mes_ant = $qrAnalitics['QTD_TRANSACOES_AVULSO_MES_ANT'];

	// 7 barras
	$qtd_clientes_compraram_mesm0 = $qrAnalitics['QTD_CLIENTES_COMPRARAM_MES'];
	$qtd_clientes_compraram_mesm1 = $qrAnalitics['QTD_CLIENTES_COMPRARAM_MESM1'];
	$qtd_clientes_compraram_mesm2 = $qrAnalitics['QTD_CLIENTES_COMPRARAM_MESM2'];
	$qtd_clientes_compraram_mesm3 = $qrAnalitics['QTD_CLIENTES_COMPRARAM_MESM3'];
	$qtd_clientes_compraram_mesm4 = $qrAnalitics['QTD_CLIENTES_COMPRARAM_MESM4'];
	$qtd_clientes_compraram_mesm5 = $qrAnalitics['QTD_CLIENTES_COMPRARAM_MESM5'];
	$qtd_clientes_compraram_mesm6 = $qrAnalitics['QTD_CLIENTES_COMPRARAM_MESM6'];

	$pct_20_cli_faturamento = $qrAnalitics['PCT_20_CLI_FATURAMENTO'];

	$listaTotTmResgate = $qrAnalitics['TM_RESGATE'];
	$listaTotTmSem = $qrAnalitics['TM_SEM_RESGATE'];
	$listaTotTmAvulso = $qrAnalitics['TM_AVULSO'];
	$listaTotGmResgate = $qrAnalitics['GM_RESGATE'];
	$listaTotGmSem = $qrAnalitics['GM_SEM_RESGATE'];


	$listaTotTransacResgate = $qrAnalitics['TRANSACOES_RESGATE'];
	$listaTotTransacSem = $qrAnalitics['TRANSACOES_SEM_RESGATE'];
	$listaTotTransacAvulso = $qrAnalitics['TRANSACOES_AVULSO'];

	// fnEscreve($listaTotTmResgate);
	// fnEscreve($listaTotTmSem);
	// fnEscreve($listaTotTmAvulso);
	// fnEscreve($listaTotGmResgate);
	// fnEscreve($listaTotGmSem);
	// fnEscreve($listaTotTransacResgate);
	// fnEscreve($listaTotTransacSem);
	// fnEscreve($listaTotTransacAvulso);

	$pct_clientes_compraram_mesm0 = $qtd_clientes_compraram_mesm6 != 0 ? (($qtd_clientes_compraram_mesm0) / $qtd_clientes_compraram_mesm6) * 100 : 0;
	$pct_clientes_compraram_mesm1 = $qtd_clientes_compraram_mesm6 != 0 ? (($qtd_clientes_compraram_mesm1) / $qtd_clientes_compraram_mesm6) * 100 : 0;
	$pct_clientes_compraram_mesm2 = $qtd_clientes_compraram_mesm6 != 0 ? (($qtd_clientes_compraram_mesm2) / $qtd_clientes_compraram_mesm6) * 100 : 0;
	$pct_clientes_compraram_mesm3 = $qtd_clientes_compraram_mesm6 != 0 ? (($qtd_clientes_compraram_mesm3) / $qtd_clientes_compraram_mesm6) * 100 : 0;
	$pct_clientes_compraram_mesm4 = $qtd_clientes_compraram_mesm6 != 0 ? (($qtd_clientes_compraram_mesm4) / $qtd_clientes_compraram_mesm6) * 100 : 0;
	$pct_clientes_compraram_mesm5 = $qtd_clientes_compraram_mesm6 != 0 ? (($qtd_clientes_compraram_mesm5) / $qtd_clientes_compraram_mesm6) * 100 : 0;
	// fnEscreve();

	if ($qtd_cli_novos >= $qtd_cli_novos_mes_ant) {
		$cor_seta_cli = "text-info fal fa-arrow-up";
	} else {
		$cor_seta_cli = "text-danger fal fa-arrow-down";
	}

	if ($qtd_clientes_compraram_mesm6 >= $qtd_cli_unicos_mes_ant) {
		$cor_seta_uni = "text-info fal fa-arrow-up";
	} else {
		$cor_seta_uni = "text-danger fal fa-arrow-down";
	}

	$qtd_dias_inativo = $qrAnalitics['QTD_DIAS_INATIVO'];

	// FNeSCREVE($dt_filtro);

	$dt_filtro_ini = date('Y-m', strtotime($dt_filtro . " -5 months"));

	$sql = "CALL SP_RELAT_INDICE_ENGAJAMENTO ( '" . $dt_filtro_ini . "' , '" . $dt_filtro . "' , '$lojasSelecionadas',$cod_empresa)";
	//fnEscreve($sql);

	$arrayQuery = mysqli_query(conntemp($cod_empresa, ''), $sql);

	$pctEngaja = [];
	$cadBase = [];
	$totCli = [];
	$totUnicos = [];
	$totNovos = [];

	while ($qrBuscaIndiceDiario = mysqli_fetch_assoc($arrayQuery)) {

		array_push($pctEngaja, $qrBuscaIndiceDiario['PERCENTUAL_ENGAJAMENTO']);
		array_push($cadBase, $qrBuscaIndiceDiario['TOTAL_CLIENTES_JA_CADASTRADOS']);
		array_push($totCli, $qrBuscaIndiceDiario['TOTAL_CLIENTES_COMPRA']);
		array_push($totUnicos, $qrBuscaIndiceDiario['TOTAL_UNICOS_ATIVOS']);
		array_push($totNovos, $qrBuscaIndiceDiario['TOTAL_CLIENTES_CADASTRADOS_MES']);



		switch ($qrBuscaIndiceDiario['MES']) {

			case '1':
				$mes_extenso = 'Jan';
				$ano_linhas = $qrBuscaIndiceDiario['ANO'];
				break;

			case '2':
				$mes_extenso = 'Fev';
				$ano_linhas = $qrBuscaIndiceDiario['ANO'];
				break;

			case '3':
				$mes_extenso = 'Mar';
				$ano_linhas = $qrBuscaIndiceDiario['ANO'];
				break;

			case '4':
				$mes_extenso = 'Abr';
				$ano_linhas = $qrBuscaIndiceDiario['ANO'];
				break;

			case '5':
				$mes_extenso = 'Mai';
				$ano_linhas = $qrBuscaIndiceDiario['ANO'];
				break;

			case '6':
				$mes_extenso = 'Jun';
				$ano_linhas = $qrBuscaIndiceDiario['ANO'];
				break;

			case '7':
				$mes_extenso = 'Jul';
				$ano_linhas = $qrBuscaIndiceDiario['ANO'];
				break;

			case '8':
				$mes_extenso = 'Ago';
				$ano_linhas = $qrBuscaIndiceDiario['ANO'];
				break;

			case '9':
				$mes_extenso = 'Set';
				$ano_linhas = $qrBuscaIndiceDiario['ANO'];
				break;

			case '10':
				$mes_extenso = 'Out';
				$ano_linhas = $qrBuscaIndiceDiario['ANO'];
				break;

			case '11':
				$mes_extenso = 'Nov';
				$ano_linhas = $qrBuscaIndiceDiario['ANO'];
				break;

			case '12':
				$mes_extenso = 'Dez';
				$ano_linhas = $qrBuscaIndiceDiario['ANO'];
				break;

			default:
				$mes_extenso = '"Mês não encontrado"';
				break;
		}

		$MES .= $mes_extenso . ',';
		$ANOS .= $ano_linhas . ',';
	}

	$MES = explode(',', rtrim($MES, ','));
	$ANOS = explode(',', rtrim($ANOS, ','));

	// print_r($MES);

	$maxEvo = max(max($cadBase), max($totCli));
	$maxComp = max(max($totNovos), max($totCli), max($totUnicos));

	if ($maxEvo < 20000) {
		$maxEvo = (ceil($maxEvo / 10000) * 1000) * 1.2;
	} else {
		$maxEvo = (ceil($maxEvo / 10000) * 10000) * 1.2;
	}
	if ($maxComp < 20000) {
		$maxComp = (ceil($maxComp / 10000) * 1000) * 1.2;
	} else {
		$maxComp = (ceil($maxComp / 10000) * 10000) * 1.2;
	}

?>

	<div class="row">

		<div class="col-md-12 margin-bottom-30">
			<!-- Portlet -->
			<div class="portlet portlet-bordered">

				<div class="portlet-body">

					<div class="row text-center">

						<div class="col-md-12 col-lg-12">

							<h3>Perfil do Cliente</h3>
							<div class="push20"></div>

							<div class="form-group text-center col-md-3 col-lg-3 shadow2">

								<h5>Total de Clientes Cadastrados até <?= date("m/Y", strtotime($dt_exibe)) ?></h5>
								<div class="push20"></div>

								<div style="max-height: 200px; max-width:100%;">
									<canvas id="chart-area" style="height: 100%"></canvas>
								</div>

								<div class="push30"></div>

								<h5>Base de Cadastros <b class="f21"><?= fnValor($qtd_clientes, 0); ?> <i class="text-info fal fa-arrow-up" data-toggle='tooltip' data-placement='top' data-html="true" data-original-title='<?= fnValor($qtd_clientes - $qtd_cli_novos_mes_ant, 0) ?>  <br/> Ref. <?php echo $mes5; ?> '></i> </b></h5>
								<h5>Novos Cadastros <b class="f21"><?= fnValor($qtd_cli_novos, 0); ?> <i class="<?= $cor_seta_cli ?>" data-toggle='tooltip' data-placement='top' data-html="true" data-original-title='<?= fnValor($qtd_cli_novos_mes_ant, 0) ?>  <br/> Ref. <?php echo $mes5; ?> '></i> </b>
									<div class="push5"></div>
									<small class="f14">Correspondem à <?= fnValor($qtd_clientes !=  0 ? ($qtd_cli_novos * 100) / ($qtd_clientes) : 0, 1) ?>% da base</small>
								</h5>


							</div>

							<div class="form-group text-center col-md-4 col-lg-4 shadow2">

								<h5>Idade Média dos Clientes Cadastrados</h5>
								<div class="push20"></div>

								<div style="max-height: 200px; max-width:100%;">
									<canvas id="bar-chart-grouped" style="height: 100%"></canvas>
								</div>

								<div class="push50"></div>
								<div class="push30"></div>

							</div>

							<div class="form-group text-center col-md-4 col-lg-4 shadow2">

								<h5>Cadastros</h5>
								<div class="push20"></div>

								<div style="max-height: 200px; max-width:100%;">
									<canvas id="bar-chart-grouped-2" style="height: 100%"></canvas>
								</div>

								<div class="push50"></div>
								<div class="push30"></div>

							</div>

						</div>

					</div>

					<div class="push20"></div>

				</div>
				<!-- fim Portlet -->
			</div>

		</div>

	</div>

	<div class="row">

		<div class="col-md-12 col-lg-12 margin-bottom-30">
			<!-- Portlet -->
			<div class="portlet portlet-bordered">

				<div class="portlet-body">

					<!-- =========================================== -->

					<div class="row text-center">

						<div class="col-md-12 col-lg-12">
							<h3>Fidelização</h3>
							<div class="push20"></div>

							<?php

							if ($vl_faturamento_fidelizado >= $vl_faturamento_fidelizado_mes_ant) {
								$cor_seta_total = "text-info fal fa-arrow-up";
							} else {
								$cor_seta_total = "text-danger fal fa-arrow-down";
							}

							if ($qtd_transacoes >= $qtd_transacoes_mes_ant) {
								$cor_seta_transac = "text-info fal fa-arrow-up";
							} else {
								$cor_seta_transac = "text-danger fal fa-arrow-down";
							}

							if ($qtd_transacoes_fidelizado >= $qtd_transacoes_fidelizado_mes_ant) {
								$cor_seta_fid = "text-info fal fa-arrow-up";
							} else {
								$cor_seta_fid = "text-danger fal fa-arrow-down";
							}

							if ($qtd_transacoes_avulso >= $qtd_transacoes_avulso_mes_ant) {
								$cor_seta_av = "text-info fal fa-arrow-up";
							} else {
								$cor_seta_av = "text-danger fal fa-arrow-down";
							}

							if ($qtd_clientes_compraram_mes0 >= $qtd_clientes_compraram_mesm6) {
								$cor_seta_fid_ant = "text-info fal fa-arrow-up";
							} else {
								$cor_seta_fid_ant = "text-danger fal fa-arrow-down";
							}

							if ($vl_indice_frequencia >= $vl_indice_frequencia_ant) {
								$cor_seta_freq = "text-info fal fa-arrow-up";
							} else {
								$cor_seta_freq = "text-danger fal fa-arrow-down";
							}

							?>


							<div class="form-group text-center col-md-6 col-lg-3 col-sm-6 shadow2">

								<h5>Faturamento em <b><?= $mes ?></b></h5>
								<div class="push20"></div>

								<div class="form-group">
									<label for="inputName">
										<h3><?= fnValor($pct_faturamento_fidelizado, 2) ?>% <i class="<?= $cor_seta_total ?>" data-toggle='tooltip' data-placement='top' data-html="true" data-original-title='R$<?= fnValor($vl_faturamento_fidelizado_mes_ant, 2) ?> <br/> Ref. <?php echo $mes5; ?> '></i></h3>
									</label>
									<input type="hidden" class="form-control input-sm" name="TOUR_PCTFATURAMENTO" id="TOUR_PCTFATURAMENTO">
								</div>
								<p>Clientes fidelizados geraram <br /><b>R$ <?= fnValor($vl_faturamento_fidelizado, 2) ?></b> de receita</p>
								<p>Que correspondem a <br /><b class="f21"><?= fnValor($pct_faturamento_ref, 0) ?>% </b>sobre o faturamento total</p>

								<div class="push10"></div>

							</div>

							<div class="form-group col-md-6 col-lg-4 col-sm-6 shadow2">

								<h5>Transações em <b><?= $mes; ?></b></h5>
								<div class="push20"></div>

								<div style="max-height: 200px; max-width:100%;">
									<div class="col-md-12 col-lg-12 text-center">
										<h3><?= fnValor($qtd_transacoes, 0) ?> <i class="<?= $cor_seta_transac ?>" data-toggle='tooltip' data-placement='top' data-html="true" data-original-title='<?= fnValor($qtd_transacoes_mes_ant, 0) ?> <br/> Ref. <?= $mes5 ?>'></i></h3>
										<?php $qtd_transacoes_f = $qtd_transacoes ?>
										<p>Total</p>
									</div>
									<div class="col-xs-4 col-md-4 col-lg-4 text-center">
										<h3>
											<div class="form-group" style="margin: 0 !important; padding: 0 !important;">
												<label>
													<h3><?= fnValor($qtd_transacoes_fidelizado, 0) ?> <i class="<?= $cor_seta_fid ?>" data-toggle='tooltip' data-placement='top' data-html="true" data-original-title='<?= fnValor($qtd_transacoes_fidelizado_mes_ant, 0) ?> <br/> <small><?= fnValor($qtd_transacoes_mes_ant != 0 ?  (($qtd_transacoes_fidelizado_mes_ant / $qtd_transacoes_mes_ant) * 100) : 0, 0) ?>%</small>  <br/> Ref. <?= $mes5 ?>'></i></h3>
												</label>
												<input type="hidden" name="TOUR_PCTFIDELIZADO" id="TOUR_PCTFIDELIZADO">
											</div>
											<div class="push5"></div>
											<small><?= fnValor($qtd_transacoes != 0 ?  (($qtd_transacoes_fidelizado / $qtd_transacoes) * 100) : 0, 0) ?>%</small>
										</h3>
										<p>Fidelizados</p>
									</div>
									<div class="col-xs-4 col-md-4 col-lg-4 text-center">
										<h3><?= fnValor($qtd_transacoes_avulso, 0) ?> <i class="<?= $cor_seta_av ?>" data-toggle='tooltip' data-placement='top' data-html="true" data-original-title='<?= fnValor($qtd_transacoes_avulso_mes_ant, 0) ?> <br/> <small><?= fnValor($qtd_transacoes_mes_ant != 0 ? (($qtd_transacoes_avulso_mes_ant / $qtd_transacoes_mes_ant) * 100) : 0, 0) ?>%</small> <br/> Ref. <?= $mes5 ?>'></i>
											<div class="push5"></div>
											<small><?= fnValor($qtd_transacoes != 0 ?  (($qtd_transacoes_avulso / $qtd_transacoes) * 100) : 0, 0) ?>%</small>
										</h3>
										<p>Avulsos</p>
									</div>
									<div class="col-xs4 col-md-4 col-lg-4 text-center">
										<h3><?= fnValor($vl_indice_frequencia, 2) ?> <i class="<?= $cor_seta_freq ?>" data-toggle='tooltip' data-placement='top' data-html="true" data-original-title='<?= fnValor($vl_indice_frequencia_ant, 2) ?> <br/> Ref. <?= $mes5 ?>'></i>
											<div class="push20"></div>
										</h3>
										<p>Índice de Frequência</p>
									</div>
								</div>

								<div class="push10"></div>

							</div>

							<div class="form-group text-center col-md-1 col-lg-1 col-sm-1">

							</div>

							<div class="form-group text-center col-md-1 col-lg-4 col-sm-4 shadow2">


								<div class="form-group">
									<label for="inputName" class="control-label">
										Clientes Fidelizados que Compraram em <b><?= $mes ?></b>
									</label>
									<input type="hidden" class="form-control input-sm" name="TOUR_qtd_clientes_compra" id="TOUR_qtd_clientes_compra">
									<div class="help-block with-errors"></div>
								</div>

								<div class="push10"></div>

								<h3>
									<?= fnValor($qtd_clientes_compraram_mesm6, 0) ?> <i class="<?= $cor_seta_uni ?>" data-toggle='tooltip' data-placement='top' data-html="true" data-original-title='<?= fnValor($qtd_cli_unicos_mes_ant, 0) ?>  <br/> Ref. <?php echo $mes5; ?> '></i>
									<div class="push5"></div>
									<small><?= fnValor($pct_fidelizado_anterior, 0) ?>%</small>
								</h3>
								<!-- <h3><?= fnValor($pct_fidelizado_anterior, 0) ?>% <i class="<?= $cor_seta_fid_ant ?>" data-toggle='tooltip' data-placement='top' data-html="true" data-original-title='R$ <?= fnValor($vl_total_concentracao_faturamento, 2) ?>'></i></h3> -->
								<p class="f14">Clientes com compras em <?= $mes ?> e compras nos meses anteriores</p>

								<div class="push10"></div>


							</div>

							<div class="push20"></div>


							<div class="form-group text-center col-md-11 col-lg-11 col-sm-11 shadow2">

								<h5>Evolução do Engajamento Mensal</h5>

								<div class="push20"></div>

								<div style="max-height: 350px;">
									<canvas id="lineChart2"></canvas>
								</div>

							</div>

							<div class="push30"></div>

							<div class="form-group text-center col-md-4 col-lg-4 shadow2">

								<h5>Tickets e Gastos Médios Limpos</h5>
								<div class="push20"></div>

								<div style="max-width:100%;">
									<canvas id="bar-chart-grouped-performance" style="height: 100%"></canvas>
								</div>

								<div class="push20"></div>

								<div class="row text-center">

									<?php

									$pct_tmsr = $listaTotTmAvulso != 0 ?  (($listaTotTmSem / $listaTotTmAvulso) * 100) - 100 : 0;
									$pct_tmcr = $listaTotTmAvulso != 0 ?  (($listaTotTmResgate / $listaTotTmSem) * 100) - 100 : 0;

									?>

									<small>
										<p>TM com resgate <b><?= fnValor($pct_tmcr, 0) ?>%</b> maior que TM sem resgate</p>
									</small>
									<small>
										<p>TM sem resgate <b><?= fnValor($pct_tmsr, 0) ?>%</b> maior que TM avulso</p>
									</small>

								</div>

								<div class="push10"></div>

							</div>

							<div class="form-group text-center col-md-3 col-lg-3-performance shadow2">

								<h5>Composição das Transações</h5>
								<div class="push20"></div>

								<div style="max-height: 200px; max-width:100%;">
									<canvas id="donut-performance" style="height: 100%"></canvas>
								</div>

								<!--
														<div class="push20"></div>
														<h5>TM Cash maior <b class="f21"><?= fnValor($qtd_clientes, 0); ?></b> </h5>
														Ricardinho
														-->
								<div class="push20"></div>

							</div>

							<div class="form-group text-center col-md-4 col-lg-4 shadow2">

								<h5>Clientes Sem Compras nos <b>Últimos <?= fnValor($qtd_dias_inativo, 0) ?> dias</b></h5>
								<div class="push20"></div>

								<div style="max-height: 200px; max-width:100%;">
									<div class="col-md-6 col-lg-6">
										<h3 style="top-margin: 0;"><?= fnValor($qtd_inativos, 0) ?>
											<div class="push5"></div>
											<small><?= fnValor($qtd_clientes != 0 ? (($qtd_inativos / $qtd_clientes) * 100) : 0, 0) ?>%</small>
										</h3>
										<p>Clientes inativos</p>
									</div>
									<div class="col-md-6 col-lg-6 text-left">
										<h4>R$ <?= fnValor($vl_gasto_acumulado_inativos, 2) ?></h4>
										<p>Gasto acumulado <br><small> nos últimos <?= fnValor($qtd_dias_inativo, 0) ?> dias anteriores</small></p>

										<div class="push10"></div>

										<h4>R$ <?= fnValor($tm_inativos, 2) ?></h4>
										<p>Ticket médio</p>
									</div>
								</div>

								<div class="push10"></div>

							</div>

							<div class="push30"></div>

						</div>

					</div>

				</div>

			</div>
			<!-- fim portable -->

		</div>

	</div>


	<div class="row">

		<div class="col-md-12 col-lg-12 margin-bottom-30">
			<!-- Portlet -->
			<div class="portlet portlet-bordered">

				<div class="portlet-body">


					<div class="row text-center">

						<div class="col-md-12 col-lg-12">
							<h3>Relacionamento</h3>
							<div class="push20"></div>
							<div class="push20"></div>

							<!--
											<div class="form-group text-center col-md-1 col-lg-1">
											</div>
											-->

							<div class="form-group text-center col-md-6 col-lg-6">

								<div class="col-md-11 col-lg-11 shadow2">

									<h5>Créditos a Expirar em <b><?= $mesAniv ?></b></h5>
									<div class="push20"></div>

									<div class="form-group text-center col-md-6 col-lg-6">

										<div style="max-height: 200px; max-width:100%;">
											<div class="col-md-3 col-lg-3">
												<i class="fal fa-flag fa-3x" style="color: #3498db;"></i>
											</div>
											<div class="col-md-9 col-lg-9">
												<h5><b><?= fnValor($qtd_cli_expirar, 0) ?></b> CLIENTES</h5>
												<h5><b>COM CRÉDITOS A EXPIRAR</b></h5>
											</div>
										</div>

									</div>

									<div class="form-group text-center col-md-6 col-lg-6">

										<div style="max-height: 200px; max-width:100%;">
											<div class="col-md-3 col-lg-3">
												<i class="fal fa-arrow-right fa-3x" style="color: #3498db;"></i>
											</div>
											<div class="col-md-9 col-lg-9">
												<p>Já compraram <br /><b>R$ <?= fnValor($vl_faturamento_expirar, 2) ?></b> <br /> <small>Nos últimos 12 meses</small> </p>
											</div>
										</div>

									</div>

									<div class="push20"></div>

								</div>

							</div>


							<div class="form-group text-center col-md-5 col-lg-5 shadow2">

								<div style="max-height: 200px; max-width:100%;">
									<h5>Resgate de Créditos de <b><?= $mes ?></b> </h5>
									<div class="push20"></div>
									<div class="push20"></div>
								</div>

								<div class="row">

									<div class="col-md-5">
										<h4><?= fnValor($qtd_cli_resgate, 0) ?></h4>
										<p><b>Clientes que realizaram resgate</b></p>
									</div>

									<div class="col-md-2 text-center">
										<div class="push20"></div>
										<i class="fal fa-arrow-right fa-3x" style="color: #3498db;"></i>
									</div>

									<div class="col-md-5">
										<h4>R$ <?= fnValor($vl_total_resgate, 2) ?></h4>
										<p><b>Valor total de resgate</b></p>
									</div>

								</div>

								<div class="push10"></div>

								<div class="row">

									<div class="col-md-5">
										<h4><?= fnValor($qtd_cli_expirado, 0) ?></h4>
										<p><b>Clientes com Créditos Expirados</b></p>
									</div>

									<div class="col-md-2 text-center">
										<div class="push20"></div>
										<i class="fal fa-arrow-right fa-3x" style="color: #3498db;"></i>
									</div>

									<div class="col-md-5">
										<h4>R$ <?= fnValor($vl_faturamento_cli_expirado, 2) ?></h4>
										<p><b>Valor dos créditos expirados</b></p>
									</div>

								</div>

								<div class="push10"></div>

								<div class="row">

									<div class="col-md-5">
										<h4><?= fnValor($perc_vl_resgate, 0) ?>%</h4>
										<p><b>Valor vinculado ao resgate</b></p>
									</div>

									<div class="col-md-2 text-center">
										<div class="push20"></div>
										<i class="fal fa-arrow-right fa-3x" style="color: #3498db;"></i>
									</div>

									<div class="col-md-5">
										<div class="push30"></div>
										<p> A cada R$ 1 investido em resgate o seu cliente comprou <b>R$ <?= fnValor(($perc_vl_resgate / 100), 2) ?></b></p>
									</div>

								</div>

								<div class="push20"></div>

							</div>



						</div>

						<div class="push50"></div>

						<div class="col-md-12 col-lg-12">

							<div class="form-group text-center col-md-6 col-lg-6">

								<div class="col-md-11 col-lg-11 shadow2">

									<h5>Concentração de Faturamento</h5>
									<div class="push20"></div>

									<div class="form-group text-center col-md-6 col-lg-6">

										<div style="max-height: 200px; max-width:100%;">
											<div class="col-md-3 col-lg-3">
												<i class="fal fa-chart-pie fa-3x" style="color: #3498db;"></i>
											</div>
											<div class="col-md-9 col-lg-9">
												<p><b>20%</b> dos clientes mais rentáveis</p>
												<p>atendidos em <b><?= $mes ?></b></p>
												<p>correspondem a <b><?= fnValor($qtd_20_cli_faturamento, 0) ?></b> clientes</p>
											</div>
										</div>

									</div>

									<div class="form-group text-center col-md-6 col-lg-6">

										<div class="push20"></div>

										<div style="max-height: 200px; max-width:100%;">
											<div class="col-md-3 col-lg-3">
												<i class="fal fa-arrow-right fa-3x" style="color: #3498db;"></i>
											</div>
											<div class="col-md-9 col-lg-9">
												<p>que correspondem a <b><?= fnValor($pct_20_cli_faturamento, 0) ?>%</b> do faturamento do mês</p>
												<!-- <p>Estes <b>20%</b> de clientes concentram</p>
																<p><b>45,33%</b> do seu faturamento em <?= $mes ?></p> -->
											</div>
										</div>

									</div>

									<div class="push20"></div>

								</div>

								<div class="col-md-11 col-lg-11 shadow2">

									<h5>Aniversariantes de <b><?= $mesAniv ?></b></h5>
									<div class="push20"></div>

									<div class="form-group text-center col-md-6 col-lg-6">

										<div style="max-height: 200px; max-width:100%;">
											<div class="col-md-3 col-lg-3">
												<i class="fal fa-birthday-cake fa-3x" style="color: #3498db;"></i>
											</div>
											<div class="col-md-9 col-lg-9">
												<h5><b><?= fnValor($qtd_aniversariantes, 0) ?></b> clientes</h5>
												<h5><b>Aniversariantes</b></h5>
											</div>
										</div>

									</div>

									<div class="form-group text-center col-md-6 col-lg-6">

										<div style="max-height: 200px; max-width:100%;">
											<div class="col-md-3 col-lg-3">
												<i class="fal fa-arrow-right fa-3x" style="color: #3498db;"></i>
											</div>
											<div class="col-md-9 col-lg-9">
												<p>Já compraram <br /><b>R$ <?= fnValor($vl_faturamento_aniver, 2) ?></b> <br /> <small>Nos últimos 12 meses</small> </p>
											</div>
										</div>

									</div>

									<div class="push20"></div>

								</div>

							</div>

							<div class="form-group text-center col-md-5 col-lg-5 shadow2">

								<div style="max-height: 200px; max-width:100%;">
									<h5>Top 5 Clientes de <b><?= $mes ?></b></h5>
									<div class="push20"></div>

									<table class="table table-hover">

										<thead>
											<tr>
												<th scope="col">NOME</th>
												<th scope="col">CARTÃO</th>
												<th scope="col">VALOR (R$)</th>
												<th scope="col">QTD. COMPRAS</th>
											</tr>
										</thead>

										<tbody>

											<?php

											$sql2 = "CALL SP_RELAT_FECHAMENTO_TOP5CLIENTE('$dt_filtro','$lojasSelecionadas', $cod_empresa )";
											//fnEscreve($sql2);
											$top5cli = array();
											$arrayQuery2 = mysqli_query(conntemp($cod_empresa, ''), $sql2);
											while ($qrAnalitics2 = mysqli_fetch_assoc($arrayQuery2)) {
												$top5cli[] = $qrAnalitics2;
											?>

												<tr>
													<td><?= fnMascaraCampo($qrAnalitics2['NOM_CLIENTE']) ?></td>
													<td><?= fnMascaraCampo($qrAnalitics2['CARTAO']) ?></td>
													<td><?= fnValor($qrAnalitics2['VALOR'], 2) ?></td>
													<td class="text-center"><?= fnValor($qrAnalitics2['COMPRAS'], 0) ?></td>
												</tr>

											<?php
											}

											?>

										</tbody>

									</table>
								</div>

								<div class="push100"></div>
								<div class="push50"></div>

							</div>

							<div class="push30"></div>

						</div>

					</div>

				</div>
				<!-- fim Portlet -->
			</div>

		</div>
	</div>

	<div class="row">

		<div class="col-md-12 col-lg-12 margin-bottom-30">
			<!-- Portlet -->
			<div class="portlet portlet-bordered">

				<div class="portlet-body">

					<div class="row text-center">


						<div class="form-group text-center col-md-12 col-lg-12">

							<h3>Funil de Clientes por Gasto</h3>
							<div class="push20"></div>
							<div class="push20"></div>

							<?php
							// Selecionando o último período configurado da tabela de filtros
							$sql = "SELECT DISTINCT QTD_MESCLASS FROM FILTRO_FREQUENCIA WHERE COD_EMPRESA = $cod_empresa AND DT_FILTRO = (SELECT MAX(DT_FILTRO) FROM FILTRO_FREQUENCIA WHERE COD_EMPRESA = $cod_empresa)";

							$qrMes = mysqli_fetch_assoc(mysqli_query(conntemp($cod_empresa, ''), $sql));

							$qtd_mesclass = $qrMes['QTD_MESCLASS'];

							$mesQuestaoFiltro = date("m", strtotime($dt_filtro));
							$anoQuestaoFiltro = date("Y", strtotime($dt_filtro));

							switch ($qtd_mesclass) {
								case 12:
									$classifica = "Anual";
									$dt_filtro_ini = date("Y", strtotime($dt_filtro)) . "-01-01";
									$dt_filtro_fim = date("Y", strtotime($dt_filtro)) . "-12-31";
									break;
								case 6:
									$classifica = "Semestral";

									if ($mesQuestaoFiltro <= 6) {
										$dt_filtro_ini = $anoQuestaoFiltro . "-01-01";
										$dt_filtro_fim = $anoQuestaoFiltro . "-06-30";
									} else {
										$dt_filtro_ini = $anoQuestaoFiltro . "-07-01";
										$dt_filtro_fim = $anoQuestaoFiltro . "-12-31";
									}
									break;
								case 4:
									$classifica = "Quadrimestral";
									if ($mesQuestaoFiltro <= 4) {
										$dt_filtro_ini = $anoQuestaoFiltro . "-01-01";
										$dt_filtro_fim = $anoQuestaoFiltro . "-04-30";
									} else if ($mesQuestaoFiltro <= 8) {
										$dt_filtro_ini = $anoQuestaoFiltro . "-05-01";
										$dt_filtro_fim = $anoQuestaoFiltro . "-08-31";
									} else {
										$dt_filtro_ini = $anoQuestaoFiltro . "-09-01";
										$dt_filtro_fim = $anoQuestaoFiltro . "-12-31";
									}
									break;
								case 3:
									$classifica = "Trimestral";
									if ($mesQuestaoFiltro <= 3) {
										$dt_filtro_ini = $anoQuestaoFiltro . "-01-01";
										$dt_filtro_fim = $anoQuestaoFiltro . "-03-31";
									} else if ($mesQuestaoFiltro <= 6) {
										$dt_filtro_ini = $anoQuestaoFiltro . "-04-01";
										$dt_filtro_fim = $anoQuestaoFiltro . "-06-30";
									} else if ($mesQuestaoFiltro <= 9) {
										$dt_filtro_ini = $anoQuestaoFiltro . "-07-01";
										$dt_filtro_fim = $anoQuestaoFiltro . "-09-30";
									} else {
										$dt_filtro_ini = $anoQuestaoFiltro . "-10-01";
										$dt_filtro_fim = $anoQuestaoFiltro . "-12-31";
									}
									break;
								case 2:
									$classifica = "Bimestral";
									if ($mesQuestaoFiltro <= 2) {
										$dt_filtro_ini = $anoQuestaoFiltro . "-01-01";
										$dt_filtro_fim = $anoQuestaoFiltro . "-02-" . cal_days_in_month(CAL_GREGORIAN, $anoQuestaoFiltro, '02');;
									} else if ($mesQuestaoFiltro <= 4) {
										$dt_filtro_ini = $anoQuestaoFiltro . "-03-01";
										$dt_filtro_fim = $anoQuestaoFiltro . "-04-30";
									} else if ($mesQuestaoFiltro <= 6) {
										$dt_filtro_ini = $anoQuestaoFiltro . "-05-01";
										$dt_filtro_fim = $anoQuestaoFiltro . "-06-30";
									} else if ($mesQuestaoFiltro <= 8) {
										$dt_filtro_ini = $anoQuestaoFiltro . "-07-01";
										$dt_filtro_fim = $anoQuestaoFiltro . "-08-31";
									} else if ($mesQuestaoFiltro <= 10) {
										$dt_filtro_ini = $anoQuestaoFiltro . "-09-01";
										$dt_filtro_fim = $anoQuestaoFiltro . "-10-31";
									} else {
										$dt_filtro_ini = $anoQuestaoFiltro . "-11-01";
										$dt_filtro_fim = $anoQuestaoFiltro . "-12-31";
									}
									break;
								case 1:
									$classifica = "Mensal";
									$dt_filtro_ini = $dt_filtro . "-01";
									$dt_filtro_fim = date("Y-m-t", strtotime($dt_filtro));
									break;
								case 0:
									$classifica = "Online (a cada venda)";
									$dt_filtro_ini = $dt_filtro . "-01";
									$dt_filtro_fim = date("Y-m-t", strtotime($dt_filtro));
									break;
							}


							$sql = "SELECT COD_FILTRO FROM FILTRO_FREQUENCIA WHERE COD_EMPRESA = $cod_empresa AND DT_FILTRO BETWEEN '$dt_filtro_ini' AND '$dt_filtro_fim'";

							// fnEscreve($sql);

							$qrSpan = mysqli_fetch_assoc(mysqli_query(conntemp($cod_empresa, ''), $sql));

							if ($qrSpan['COD_FILTRO'] != "") {

								$cod_filtro = $qrSpan['COD_FILTRO'];

								//busca dados do filtro
								$sql = "SELECT COD_FILTRO , QTD_DIASHIST , DT_FILTRO FROM filtro_frequencia where COD_EMPRESA = $cod_empresa AND COD_FILTRO = $cod_filtro ";
								//fnEscreve($sql);
								$arrayQuery = mysqli_query(conntemp($cod_empresa, ''), trim($sql));
								$qrBuscaFiltro = mysqli_fetch_assoc($arrayQuery);

								$cod_filtro = $qrBuscaFiltro['COD_FILTRO'];
								$qtd_diashist = $qrBuscaFiltro['QTD_DIASHIST'];
								$dt_filtro = $qrBuscaFiltro['DT_FILTRO'];

								$mes = date("m", strtotime($dt_filtro));;      // Mês desejado, pode ser por ser obtido por POST, GET, etc.
								$ano = date("Y", strtotime($dt_filtro)); // Ano atual
								$ultimo_dia = date("t", mktime(0, 0, 0, $mes, '01', $ano)); // Mágica, plim!
								$ultima_data = $ultimo_dia . "/" . $mes . "/" . $ano;

								$dias_periodo = $qtd_diashist + 1;
								$dt_filtroMenor = date('Y-m-d', strtotime($dt_filtro . '-' . $dias_periodo . ' days'));

								$dataConsulta = substr($dt_filtro, 0, 4) . "-" . substr($dt_filtro, 5, 2);
								//fnEscreve($dataConsulta);
								$sql = "CALL SP_RELAT_LUCRO_FREQUENCIA_UNIVEND('$lojasSelecionadas', $cod_empresa, $qrSpan[COD_FILTRO], '$dataConsulta' )";
								// fnEscreve($sql);
								$arrayQuery = mysqli_query(conntemp($cod_empresa, ''), $sql);

								$count = 0;
								$bar = [];
								$cliente = [];
								$gm = [];
								$im = [];
								$nomeFaixa = [];
								$resgate_total = [];
								$vvr = [];
								$resgTot = 0;
								$qtd_media_compra = [];

								while ($qrFunil = mysqli_fetch_assoc($arrayQuery)) {
									// fnEscreveArray($qrFunil);

									$bar[$count] = fnValor(@$qrFunil['PERC_QTD_CLIENTES'], 0);
									$faixa[$count] = @$qrFunil['DESCRICAO_FAIXA'];
									$cliente[$count] = @$qrFunil['QTD_CLIENTES'];
									$gm[$count] = @$qrFunil['GM'];
									$im[$count] = @$qrFunil['MEDIA_IDADE'];
									$resgate_total[$count] = @$qrFunil['VL_RESGATE_TOTAL'];
									$vvr[$count] = @$qrFunil['PERC_VVR'];
									$vvr_medio[$count] = @$qrFunil['VVR_MEDIO'];
									$qtd_resgate[$count] = @$qrFunil['QTD_RESGATE'];
									$media_vr[$count] = @$qrFunil['VL_RESGATE_MEDIO'];
									$media_vvr[$count] = @$qrFunil['VVR_MEDIO'];
									$resgTot += @$qrFunil['VL_RESGATE_TOTAL'];
									//fnEscreve($vvr[$count]);
									$qtd_media_compra[$count] = @$qrFunil['QTD_MEDIA_COMPRA'];
									$qtd_media_item[$count] = @$qrFunil['QTD_MEDIA_ITEM'];

									$qtd_transacoes[$count] = @$qrFunil['QTD_TRANSACOES'];
									$qtd_total_item[$count] = @$qrFunil['QTD_TOTAL_ITEM'];

									//fnEscreve($qtd_transacoes[$count]);
									//fnEscreve($qtd_total_item[$count]);
									//fnEscreve($qtd_media_compra);

									// fnEscreve($qtd_media_compra[$count]);

									$count++;
								}

								$resg1 = (@$resgate_total[0] / $resgTot) * 100;
								$resg2 = (@$resgate_total[1] / $resgTot) * 100;
								$resg3 = (@$resgate_total[2] / $resgTot) * 100;
								$resg4 = (@$resgate_total[3] / $resgTot) * 100;
								$resg5 = @$resgate_total[4];

								$freq1 = @$gm[0] / @$gm[0];
								$freq2 = @$gm[1] / @$gm[0];
								$freq3 = @$gm[2] / @$gm[0];
								$freq4 = @$gm[3] / @$gm[0];
								$freq5 = @$gm[4] / @$gm[0];

								$qtd_media_compra1 = @$qtd_media_compra[0];
								$qtd_media_compra2 = @$qtd_media_compra[1];
								$qtd_media_compra3 = @$qtd_media_compra[2];
								$qtd_media_compra4 = @$qtd_media_compra[3];
								$qtd_media_compra5 = @$qtd_media_compra[4];

								$qtd_media_item1 = @$qtd_media_compra[0];
								$qtd_media_item2 = @$qtd_media_compra[1];
								$qtd_media_item3 = @$qtd_media_compra[2];
								$qtd_media_item4 = @$qtd_media_compra[3];

								//fnEscreve($qtd_media_compra4);

								$bar2Calc = 70;
								$bar3Calc = 55;
								$bar4Calc = 35;

							?>

								<div class="row text-center">

									<div class="form-group text-center col-lg-12">

										<h5>Dados do Ciclo de Recompra</h5>

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
										<b><?= fnDataShort($dt_filtroMenor) ?> a <?= fnDataShort($dt_filtro); ?></b>
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

								<table class="table table-striped">
									<thead>
										<tr>
											<!--<th></th>-->

											<th class="text-center f18" colspan="2">CONCENTRAÇÃO DE CLIENTES</th>
											<th class="text-center f18">TIPO DE CLIENTE</th>
											<th class="text-center f18">MÉDIA IC</th>
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
												<div class="bar cor1" style="width: -webkit-calc(100%);"><span><?= @$bar[0] ?>%</span>&nbsp; <?= fnValor(@$cliente[0], 0); ?> </div>
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
												<span class="f18 fCor1"><b><?php echo @$faixa[0]; ?></b></span>
												<div class="push3"></div>
												<span class="f12 fCor1"><small><?= fnValor(@$im[0], 0) ?> anos </small></span>
												<div class="push3"></div>
												<span class="f13 fCor1"><b><?php echo $txt_compras1; ?></b></span>
											</td>

											<td class="text-center">
												<div class="push30"></div>
												<span class="f26b fCor1"><b><?php echo fnValor($qtd_media_item1, 0); ?></b></span>
											</td>

											<td class="text-center">
												<div class="push30"></div>
												<span class="f26b fCor1"><b>R$ <?php echo fnValor(@$gm[0], 2); ?></b></span>
											</td>

											<td class="text-center">
												<div class="push30"></div>
												<span class="f30 fCor1"><b><?= round($freq1) ?>x</b></span>
											</td>

										</tr>

										<tr>

											<td style="width: 50px;"></td>

											<td class="text-center">
												<div class="push30"></div>
												<div class="bar cor2" style="width: -webkit-calc(80%);"><span><?= @$bar[1] ?>%</span>&nbsp; <?= fnValor(@$cliente[1], 0); ?> </div>
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
												<span class="f18 fCor2"><b><?php echo @$faixa[1]; ?></b></span>
												<div class="push3"></div>
												<span class="f12 fCor2"><small><?= fnValor(@$im[1], 0) ?> anos </small></span>
												<div class="push3"></div>
												<span class="f13 fCor2"><b><?php echo $txt_compras2; ?></b></span>
											</td>

											<td class="text-center">
												<div class="push30"></div>
												<span class="f26b fCor2"><b><?php echo fnValor($qtd_media_item2, 0); ?></b></span>
											</td>

											<td class="text-center">
												<div class="push30"></div>
												<span class="f26b fCor2"><b>R$ <?php echo fnValor(@$gm[1], 2); ?></b></span>
											</td>

											<td class="text-center">
												<div class="push30"></div>
												<span class="f30 fCor2"><b> <?= round($freq2) ?>x </b></span>
											</td>

										</tr>

										<tr>

											<td style="width: 50px;"></td>

											<td class="text-center">
												<div class="push30"></div>
												<div class="bar cor3" style="width: -webkit-calc(65%);"><span><?= @$bar[2] ?>%</span>&nbsp; <?= fnValor(@$cliente[2], 0); ?></div>
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
												<span class="f18 fCor3"><b><?php echo @$faixa[2]; ?></b></span>
												<div class="push3"></div>
												<span class="f12 fCor3"><small><?= fnValor(@$im[2], 0) ?> anos </small></span>
												<div class="push3"></div>
												<span class="f13 fCor3"><b><?php echo $txt_compras3; ?></b></span>
											</td>

											<td class="text-center">
												<div class="push30"></div>
												<span class="f26b fCor3"><b><?php echo fnValor($qtd_media_item3, 0); ?></b></span>
											</td>

											<td class="text-center">
												<div class="push30"></div>
												<span class="f30 fCor3"><b>R$ <?php echo fnValor(@$gm[2], 2); ?></b></span>
											</td>

											<td class="text-center">
												<div class="push30"></div>
												<span class="f30 fCor3"><b><?= round($freq3) ?>x</b></span>
											</td>

										</tr>

										<tr>

											<td style="width: 50px;"></td>

											<td class="text-center">
												<div class="push30"></div>
												<div class="bar cor4" style="width: -webkit-calc(50%);"><span><?= @$bar[3] ?>%</span>&nbsp; <?= fnValor(@$cliente[3], 0); ?></div>
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
												<span class="f18 fCor4"><b><?php echo @$faixa[3]; ?></b></span>
												<div class="push3"></div>
												<span class="f12 fCor4"><small><?= fnValor(@$im[3], 0) ?> anos </small></span>
												<div class="push3"></div>
												<span class="f13 fCor4"><b><?php echo $txt_compras4; ?> </b></span>
											</td>

											<td class="text-center">
												<div class="push30"></div>
												<span class="f26b fCor4"><b><?php echo fnValor($qtd_media_item4, 0); ?></b></span>
											</td>

											<td class="text-center">
												<div class="push30"></div>
												<span class="f26b fCor4"><b>R$ <?php echo fnValor(@$gm[3], 2); ?></b></span>
											</td>

											<td class="text-center">
												<div class="push30"></div>
												<span class="f30 fCor4"><b><?= round($freq4) ?>x</b></span>
											</td>

										</tr>

									</tbody>
								</table>

								<div class="push50"></div>


							<?php
							} else {
							?>

								<div class="push50"></div>

								<div class="row">
									<div class="col-md-6 col-md-offset-3 text-center">
										<h5>Não há dados para o período de referência.</h5>
									</div>
								</div>

							<?php
							}
							?>

						</div>

					</div>

				</div>


			</div>
			<!-- fim Portlet -->
		</div>

	</div>

	<!--
					
					<div class="row">				
					
						<div class="col-md-3 col-lg-3 margin-bottom-30">							
						
							<div class="portlet portlet-bordered">
							
								<div class="portlet-body">						
								
								<div class="push100"></div>								
								<div class="push100"></div>							
									
								</div>
							</div>
							
						</div>	

						
						<div class="col-md-3 col-lg-3 margin-bottom-30">							
						
							<div class="portlet portlet-bordered">
							
								<div class="portlet-body">						
								
								<div class="push100"></div>								
								<div class="push100"></div>							
									
								</div>
							</div>
							
						</div>

					</div>
					-->

	<div class="row">

		<div class="col-md-12 margin-bottom-30">
			<!-- Portlet -->
			<div class="portlet portlet-bordered">
				<div class="portlet-body">


					<form target="_blank" data-toggle="validator" role="geraPDF" method="post" id="geraPDF" action="relatorios/pdfDashAnalytics.php">

						<input type="hidden" name="dt_exibe" value="<?= $dt_exibe ?>">
						<input type="hidden" name="qtd_clientes" value="<?= $qtd_clientes ?>">
						<input type="hidden" name="qtd_cli_novos" value="<?= $qtd_cli_novos ?>">
						<input type="hidden" name="cod_empresa" value="<?= $cod_empresa ?>">
						<input type="hidden" name="qtd_idade1" value="<?= $qtd_idade1 ?>">
						<input type="hidden" name="qtd_idade2" value="<?= $qtd_idade2 ?>">
						<input type="hidden" name="qtd_idade3" value="<?= $qtd_idade3 ?>">
						<input type="hidden" name="qtd_idade4" value="<?= $qtd_idade4 ?>">
						<input type="hidden" name="qtd_idade5" value="<?= $qtd_idade5 ?>">
						<input type="hidden" name="qtd_idade6" value="<?= $qtd_idade6 ?>">
						<input type="hidden" name="qtd_idade7" value="<?= $qtd_idade7 ?>">
						<input type="hidden" name="pct_email" value="<?= $pct_email ?>">
						<input type="hidden" name="pct_celular" value="<?= $pct_celular ?>">
						<input type="hidden" name="pct_nasciment0" value="<?= $pct_nascimento ?>">
						<input type="hidden" name="pct_cep" value="<?= $pct_cep ?>">
						<input type="hidden" name="pct_endereco" value="<?= $pct_endereco ?>">
						<input type="hidden" name="ticket_medio_fidelizado" value="<?= $ticket_medio_fidelizado ?>">
						<input type="hidden" name="ticket_medio_avulso" value="<?= $ticket_medio_avulso ?>">
						<input type="hidden" name="mes0" value="<?= $mes0 ?>">
						<input type="hidden" name="mes1" value="<?= $mes1 ?>">
						<input type="hidden" name="mes2" value="<?= $mes2 ?>">
						<input type="hidden" name="mes3" value="<?= $mes3 ?>">
						<input type="hidden" name="mes4" value="<?= $mes4 ?>">
						<input type="hidden" name="mes5" value="<?= $mes5 ?>">
						<input type="hidden" name="mes6" value="<?= $mes6 ?>">
						<input type="hidden" name="qtd_clientes_compraram_mesm0" value="<?= $qtd_clientes_compraram_mesm0 ?>">
						<input type="hidden" name="qtd_clientes_compraram_mesm1" value="<?= $qtd_clientes_compraram_mesm1 ?>">
						<input type="hidden" name="qtd_clientes_compraram_mesm2" value="<?= $qtd_clientes_compraram_mesm2 ?>">
						<input type="hidden" name="qtd_clientes_compraram_mesm3" value="<?= $qtd_clientes_compraram_mesm3 ?>">
						<input type="hidden" name="qtd_clientes_compraram_mesm4" value="<?= $qtd_clientes_compraram_mesm4 ?>">
						<input type="hidden" name="qtd_clientes_compraram_mesm5" value="<?= $qtd_clientes_compraram_mesm5 ?>">
						<input type="hidden" name="qtd_feminino" value="<?= $qtd_feminino ?>">
						<input type="hidden" name="qtd_masculino" value="<?= $qtd_masculino ?>">
						<input type="hidden" name="qtd_transacoes_fidelizado" value="<?= $qtd_transacoes_fidelizado ?>">
						<input type="hidden" name="qtd_transacoes_avulso" value="<?= $qtd_transacoes_avulso ?>">
						<input type="hidden" name="vl_indice_frequencia" value="<?= $vl_indice_frequencia ?>">
						<input type="hidden" name="listaFatLmp" value="<?= $listaFatLmp ?>">
						<input type="hidden" name="listaFatAv" value="<?= $listaFatAv ?>">
						<input type="hidden" name="listaFatTotRes" value="<?= $listaFatTotRes ?>">
						<input type="hidden" name="listaFatFid" value="<?= $listaFatFid ?>">
						<input type="hidden" name="listaPctFatAv" value="<?= $listaPctFatAv ?>">
						<input type="hidden" name="listaFatRes" value="<?= $listaFatRes ?>">
						<input type="hidden" name="mes" value="<?= $mes ?>">
						<input type="hidden" name="mes_nome" value="<?= $mes_nome ?>">
						<input type="hidden" name="pct_faturamento_fidelizado" value="<?= $pct_faturamento_fidelizado ?>">
						<input type="hidden" name="vl_faturamento_fidelizado_mes_ant" value="<?= $vl_faturamento_fidelizado_mes_ant ?>">
						<input type="hidden" name="vl_faturamento_fidelizado" value="<?= $vl_faturamento_fidelizado ?>">
						<input type="hidden" name="pct_faturamento_ref" value="<?= $pct_faturamento_ref ?>">
						<input type="hidden" name="cor_seta_total" value="<?= $cor_seta_total ?>">
						<input type="hidden" name="cor_seta_transac" value="<?= $cor_seta_transac ?>">
						<input type="hidden" name="cor_seta_cli" value="<?= $cor_seta_cli ?>">
						<input type="hidden" name="cor_seta_fid" value="<?= $cor_seta_fid ?>">
						<input type="hidden" name="cor_seta_av" value="<?= $cor_seta_av ?>">
						<input type="hidden" name="cor_seta_freq" value="<?= $cor_seta_freq ?>">
						<input type="hidden" name="cor_seta_uni" value="<?= $cor_seta_uni ?>">
						<input type="hidden" name="qtd_transacoes" value="<?= $qtd_transacoes_f ?>">
						<input type="hidden" name="qtd_transacoes_mes_ant" value="<?= $qtd_transacoes_mes_ant ?>">
						<input type="hidden" name="qtd_transacoes_fidelizado" value="<?= $qtd_transacoes_fidelizado ?>">
						<input type="hidden" name="qtd_transacoes_fidelizado_mes_ant" value="<?= $qtd_transacoes_fidelizado_mes_ant ?>">
						<input type="hidden" name="qtd_transacoes_avulso" value="<?= $qtd_transacoes_avulso ?>">
						<input type="hidden" name="qtd_transacoes_avulso_mes_ant" value="<?= $qtd_transacoes_avulso_mes_ant ?>">
						<input type="hidden" name="qtd_clientes_compraram_mesm6" value="<?= $qtd_clientes_compraram_mesm6 ?>">
						<input type="hidden" name="pct_fidelizado_anterior" value="<?= $pct_fidelizado_anterior ?>">
						<input type="hidden" name="qtd_inativos" value="<?= $qtd_inativos ?>">
						<input type="hidden" name="vl_gasto_acumulado_inativos" value="<?= $vl_gasto_acumulado_inativos ?>">
						<input type="hidden" name="tm_inativos" value="<?= $tm_inativos ?>">
						<input type="hidden" name="mesAniv" value="<?= $mesAniv ?>">
						<input type="hidden" name="qtd_aniversariantes" value="<?= $qtd_aniversariantes ?>">
						<input type="hidden" name="vl_faturamento_aniver" value="<?= $vl_faturamento_aniver ?>">
						<input type="hidden" name="qtd_cli_expirar" value="<?= $qtd_cli_expirar ?>">
						<input type="hidden" name="vl_faturamento_expirar" value="<?= $vl_faturamento_expirar ?>">
						<input type="hidden" name="qtd_20_cli_faturamento" value="<?= $qtd_20_cli_faturamento ?>">
						<input type="hidden" name="pct_20_cli_faturamento" value="<?= $pct_20_cli_faturamento ?>">
						<input type="hidden" name="lojasSelecionadas" value="<?= $lojasSelecionadas ?>">
						<input type="hidden" name="dt_filtro" value="<?= $dt_filtro ?>">

						<input type="hidden" name="qtd_cli_resgate" value="<?= $qtd_cli_resgate ?>">
						<input type="hidden" name="vl_total_resgate" value="<?= $vl_total_resgate ?>">
						<input type="hidden" name="qtd_cli_expirado" value="<?= $qtd_cli_expirado ?>">
						<input type="hidden" name="vl_faturamento_expirado" value="<?= $vl_faturamento_expirado ?>">
						<input type="hidden" name="perc_vl_resgate" value="<?= $perc_vl_resgate ?>">

						<input type="hidden" name="txt_compras1" value="<?= $txt_compras1 ?>">
						<input type="hidden" name="txt_compras2" value="<?= $txt_compras2 ?>">
						<input type="hidden" name="txt_compras3" value="<?= $txt_compras3 ?>">

						<input type="hidden" name="freq1" value="<?= $freq1 ?>">
						<input type="hidden" name="freq2" value="<?= $freq2 ?>">
						<input type="hidden" name="freq3" value="<?= $freq3 ?>">
						<input type="hidden" name="freq4" value="<?= $freq4 ?>">
						<input type="hidden" name="freq5" value="<?= $freq5 ?>">

						<input type="hidden" name="dt_filtro" value="<?= $dt_filtro ?>">
						<input type="hidden" name="dt_filtroMenor" value='<?= $dt_filtroMenor ?>'>
						<input type="hidden" name="ultima_data" value="<?= $ultima_data ?>">
						<input type="hidden" name="qtd_diashist" value="<?= $qtd_diashist ?>">
						<input type="hidden" name="classifica" value="<?= $classifica ?>">

						<input type="hidden" name="pct_tmcr" value="<?= $pct_tmcr ?>">
						<input type="hidden" name="pct_tmsr" value="<?= $pct_tmsr ?>">

						<input type="hidden" name="top5cli" value='<?= serialize($top5cli) ?>'>
						<input type="hidden" name="bar" value='<?= serialize($bar) ?>'>
						<input type="hidden" name="cliente" value='<?= serialize($cliente) ?>'>
						<input type="hidden" name="im" value='<?= serialize($im) ?>'>
						<input type="hidden" name="faixa" value='<?= serialize($faixa) ?>'>
						<input type="hidden" name="gm" value='<?= serialize($gm) ?>'>

						<input type="hidden" name="chartarea" value="">
						<input type="hidden" name="chartarea2" value="">
						<input type="hidden" name="lineChart2" value="">
						<input type="hidden" name="barchartgroupedperformance" value="">
						<input type="hidden" name="barchartgrouped" value="">
						<input type="hidden" name="barchartgrouped2" value="">
						<input type="hidden" name="mydoughnut" value="">


						<div class="col-md-2">
							<button type="submit" name="ALT" id="bt_PDF" class="btn btn-info" disabled>
								<i class="fa fa-file-pdf" aria-hidden="true"></i>
								&nbsp; Gerar PDF
							</button>
						</div>

						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

					</form>

					<div class="push20"></div>


				</div>
			</div>
		</div>
	</div>

	<div class="push20"></div>


	<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
	<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />

	<script src="js/gauge.coffee.js" type="text/javascript"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.min.js"></script>
	<script src="js/pie-chart.js"></script>
	<script src="js/plugins/Chart_Js/utils.js"></script>
	<!-- <script type="text/javascript" src="js/plugins/jquery.sparkline.min.js"></script> -->
	<?php
	if ($log_labels == 'S') {
	?>
		<!-- Script dos labels -->
		<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.5.0"></script>

	<?php
	}
	?>

	<script>
		<?php
		if ($log_labels == 'S') {
		?>
			Chart.plugins.unregister(ChartDataLabels);
		<?php
		}
		?>
		//datas
		$(function() {

			var cod_empresa = "<?= $cod_empresa ?>";

			if (cod_empresa == 77) {

				$('.datePicker').datetimepicker({
					format: 'DD/MM/YYYY',
					maxDate: 'now',
					minDate: '2018-12-31'
				}).on('changeDate', function(e) {
					$(this).datetimepicker('hide');
				});

			} else {

				$('.datePicker').datetimepicker({
					format: 'DD/MM/YYYY',
					maxDate: 'now',
				}).on('changeDate', function(e) {
					$(this).datetimepicker('hide');
				});

			}

			$("#DAT_INI_GRP").on("dp.change", function(e) {
				$('#DAT_FIM_GRP').data("DateTimePicker").minDate(e.date);
			});

			$("#DAT_FIM_GRP").on("dp.change", function(e) {
				$('#DAT_INI_GRP').data("DateTimePicker").maxDate(e.date);
			});


		});

		//graficos
		$(document).ready(function() {


			// $('#demo-pie-1').pieChart({
			//     barColor: '#3bb2d0',
			//     trackColor: '#eee',
			//     lineCap: 'round',
			//     lineWidth: 8,
			//     onStep: function (from, to, percent) {
			//         $(this.element).find('.pie-value').text(Math.round(percent) + '%');
			//     }
			// });

			//chosen
			$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
			$('#formulario').validator();

			//grouped
			var barchartgrouped = new Chart(document.getElementById("bar-chart-grouped"), {
				type: 'bar',
				data: {
					labels: [
						["-17", "<?= fnValor($pct_idade0, 0) ?>%"],
						["18-20", "<?= fnValor($pct_idade1, 0) ?>%"],
						["21-30", "<?= fnValor($pct_idade2, 0) ?>%"],
						["31-40", "<?= fnValor($pct_idade3, 0) ?>%"],
						["41-50", "<?= fnValor($pct_idade4, 0) ?>%"],
						["51-60", "<?= fnValor($pct_idade5, 0) ?>%"],
						["61-70", "<?= fnValor($pct_idade6, 0) ?>%"],
						["71-80", "<?= fnValor($pct_idade7, 0) ?>%"],
						["+81", "<?= fnValor($pct_idade8, 0) ?>%"]
					],
					datasets: [{
						<?php if ($log_labels == 'S') { ?>
							datalabels: {
								clamp: true,
								align: 'middle',
								anchor: 'end',
								borderRadius: 4,
								backgroundColor: '#75B1D9',
								color: '#fff',
								formatter: function(value) {
									if (parseInt(value) >= 1000) {
										return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
									} else {
										return value;
									}
									// eq. return ['line1', 'line2', value]
								}
							},
						<?php } ?>
						backgroundColor: "#85C1E9",
						data: [<?= $qtd_idade0 ?>, <?= $qtd_idade1 ?>, <?= $qtd_idade2 ?>, <?= $qtd_idade3 ?>, <?= $qtd_idade4 ?>, <?= $qtd_idade5 ?>, <?= $qtd_idade6 ?>, <?= $qtd_idade7 ?>, <?= $qtd_idade8 ?>]
					}, ]
				},
				<?php if ($log_labels == 'S') { ?>
					plugins: [ChartDataLabels],
				<?php } ?>
				options: {
					legend: {
						display: false
					},
					//  title: {
					// display: true,
					// text: ''
					//  },
					tooltips: {
						callbacks: {
							label: function(t, d) {
								if (parseInt(t.yLabel) >= 1000) {
									return t.yLabel.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
								} else {
									return t.yLabel;
								}
								// return t.yLabel
							}
						}
					},
					scales: {
						yAxes: [{
							ticks: {
								callback: function(value, index, values) {
									if (parseInt(value) >= 1000) {
										return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
									} else {
										return value;
									}
								}
							}
						}]
					},
					animation: {
						animateScale: true,
						animateRotate: true,
						onComplete: function() {
							$("input[name=barchartgrouped]").val(barchartgrouped.toBase64Image());
							botaoPDF();
						}
					}
				}
			});

			//grouped
			var barchartgrouped2 = new Chart(document.getElementById("bar-chart-grouped-2"), {
				type: 'bar',
				data: {
					labels: ["E-mails", "Celulares", "Dt. Nascimento", "CEP", "Endereços"],
					datasets: [{
						<?php if ($log_labels == 'S') { ?>
							datalabels: {
								clamp: true,
								align: 'middle',
								anchor: 'end',
								borderRadius: 4,
								backgroundColor: '#D5F5E3',
								color: '#fff',
								formatter: function(value) {
									if (parseInt(value) >= 1000) {
										return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
									} else {
										return value + "%";
									}
									// eq. return ['line1', 'line2', value]
								}
							},
						<?php } ?>
						backgroundColor: '#D6EAF8',
						data: [<?= $pct_email ?>, <?= $pct_celular ?>, <?= $pct_nascimento ?>, <?= $pct_cep ?>, <?= $pct_endereco ?>]
					}]
				},
				<?php if ($log_labels == 'S') { ?>
					plugins: [ChartDataLabels],
				<?php } ?>
				options: {
					legend: {
						display: false
					},
					tooltips: {
						callbacks: {
							label: function(t, d) {
								return t.yLabel + "%"
							}
						}
					},
					scales: {
						yAxes: [{
							ticks: {
								min: 0,
								stepSize: 20,
								callback: function(value, index, values) {
									if (parseInt(value) >= 1000) {
										return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + '%';
									} else {
										return value + '%';
									}
								}
							}
						}]
					},
					animation: {
						animateScale: true,
						animateRotate: true,
						onComplete: function() {
							$("input[name=barchartgrouped2]").val(barchartgrouped2.toBase64Image());
							botaoPDF();
						}
					}
				}
			});

			//grouped
			// var barchartgrouped3 = new Chart(document.getElementById("bar-chart-grouped-3"), {
			// 	type: 'bar',
			// 	data: {
			// 	  labels: [""],
			// 	  datasets: [
			// 		{
			// 		  label: "Fidelizadas",
			// 		  backgroundColor: window.chartColors.blue,
			// 		  data: [<?= $ticket_medio_fidelizado ?>]
			// 		}, {
			// 		  label: "Avulsas",
			// 		  backgroundColor: window.chartColors.green,
			// 		  data: [<?= $ticket_medio_avulso ?>]
			// 		}
			// 	  ]
			// 	},
			// 	options: {
			// 	 //  title: {
			// 		// display: true,
			// 		// text: ''
			// 	 //  },
			// 	   tooltips: {
			// 	      callbacks: {
			// 	         label: function (t, d) {
			// 		        return 'R$ ' + t.yLabel.toFixed(2)
			// 		  }
			// 		}
			// 	   },
			// 	  scales: {						
			// 			yAxes: [{
			// 				ticks: {
			// 					 beginAtZero: true,
			// 					callback: function(value, index, values) {
			// 		              if(parseInt(value) >= 1000){
			// 		                return 'R$ ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
			// 		              } else {
			// 		                return 'R$ ' + value;
			// 		              }
			// 		            }
			// 				}													
			// 			}],
			// 			xAxes: [{
			// 				labels: ["Fidelizadas","Avulsas"]													
			// 			}]					
			// 		},
			// 		animation: {
			// 			animateScale: true,
			// 			animateRotate: true,
			// 			onComplete : function(){   
			// 				$("input[name=barchartgrouped3]").val(barchartgrouped3.toBase64Image());
			// 				botaoPDF();
			// 			}
			// 		}
			// 	}
			// });

			// var barchartgrouped4 = new Chart(document.getElementById("bar-chart-grouped-4"), {
			// 	type: 'bar',
			// 	data: {
			// 	  labels: [
			// 		  ["<?= substr($mes0, 0, 3) ?>","<?= fnValor($pct_clientes_compraram_mesm0, 0) ?>%"], 
			// 		  ["<?= substr($mes1, 0, 3) ?>","<?= fnValor($pct_clientes_compraram_mesm1, 0) ?>%"], 
			// 		  ["<?= substr($mes2, 0, 3) ?>","<?= fnValor($pct_clientes_compraram_mesm2, 0) ?>%"], 
			// 		  ["<?= substr($mes3, 0, 3) ?>","<?= fnValor($pct_clientes_compraram_mesm3, 0) ?>%"], 
			// 		  ["<?= substr($mes4, 0, 3) ?>","<?= fnValor($pct_clientes_compraram_mesm4, 0) ?>%"], 
			// 		  ["<?= substr($mes5, 0, 3) ?>","<?= fnValor($pct_clientes_compraram_mesm5, 0) ?>%"]
			// 	  ],				  
			// 	  datasets: [
			// 		{
			// 		  // labels: ["18 a 20", "21 a 30", "31 a 40", "41 a 50", "51 a 60", "61 a 70", "71 a 80"],					  
			// 		  backgroundColor: "#85C1E9",					 
			// 		  data: [<?= $qtd_clientes_compraram_mesm0 ?>, <?= $qtd_clientes_compraram_mesm1 ?>, <?= $qtd_clientes_compraram_mesm2 ?>, <?= $qtd_clientes_compraram_mesm3 ?>, <?= $qtd_clientes_compraram_mesm4 ?>, <?= $qtd_clientes_compraram_mesm5 ?>]
			// 		},
			// 	  ]
			// 	},
			// 	options: {
			// 		legend: {
			//             display: false
			//          },
			// 	 //  title: {
			// 		// display: true,
			// 		// text: ''
			// 	 //  },
			// 	   tooltips: {
			// 	    callbacks: {
			// 	        label: function (t, d) {
			// 	         	if(parseInt(t.yLabel) >= 1000){
			// 	                return t.yLabel.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
			// 	            } else {
			// 	                return t.yLabel;
			// 	            }
			// 		        // return t.yLabel
			// 		  	}
			// 		}
			// 	   },
			// 	  scales: {						
			// 			yAxes: [{
			// 				ticks: {
			// 					callback: function(value, index, values) {
			// 		              if(parseInt(value) >= 1000){
			// 		                return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
			// 		              } else {
			// 		                return value;
			// 		              }
			// 		            }
			// 				}													
			// 			}]					
			// 		},
			// 		animation: {
			// 			animateScale: true,
			// 			animateRotate: true,
			// 			onComplete : function(){   
			// 				$("input[name=barchartgrouped4]").val(barchartgrouped4.toBase64Image());
			// 				botaoPDF();
			// 			}
			// 		}
			// 	}
			// });

			// Line chart 2
			var ctx = document.getElementById("lineChart2");
			var lineChart = new Chart(ctx, {
				type: 'line',
				data: {
					labels: [
						["<?= $MES['0'] ?>", "<?= $ANOS['0'] ?>"],
						["<?= $MES['1'] ?>", "<?= $ANOS['1'] ?>"],
						["<?= $MES['2'] ?>", "<?= $ANOS['2'] ?>"],
						["<?= $MES['3'] ?>", "<?= $ANOS['3'] ?>"],
						["<?= $MES['4'] ?>", "<?= $ANOS['4'] ?>"],
						["<?= $MES['5'] ?>", "<?= $ANOS['5'] ?>"],
					],
					datasets: [{
						<?php if ($log_labels == 'S') { ?>
							datalabels: {
								clamp: true,
								align: 'middle',
								anchor: 'middle',
								borderRadius: 4,
								backgroundColor: '#36A2EB',
								color: '#fff',
								formatter: function(value) {
									if (parseInt(value) >= 1000) {
										return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
									} else {
										return value;
									}
									// eq. return ['line1', 'line2', value]
								}
							},
						<?php } ?>
						label: "Total de Clientes no Mês",
						backgroundColor: "rgba(3, 88, 106, 0)",
						borderColor: "#36A2EB",
						pointBorderColor: "#36A2EB",
						pointBackgroundColor: "#fff",
						pointHoverBackgroundColor: "#fff",
						lineWidth: 3,
						pointRadius: 3,
						pointBorderWidth: 2,
						data: <?php echo json_encode($totCli) ?>
					}, {
						<?php if ($log_labels == 'S') { ?>
							datalabels: {
								clamp: true,
								align: 'middle',
								anchor: 'middle',
								borderRadius: 4,
								backgroundColor: '#4BC0C0',
								color: '#fff',
								formatter: function(value) {
									if (parseInt(value) >= 1000) {
										return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
									} else {
										return value;
									}
									// eq. return ['line1', 'line2', value]
								}
							},
						<?php } ?>
						label: "Clientes Cadastrados na Base",
						backgroundColor: "rgba(3, 88, 106, 0)",
						borderColor: "#4BC0C0",
						pointBorderColor: "#4BC0C0",
						pointBackgroundColor: "#fff",
						lineWidth: 3,
						pointRadius: 3,
						pointBorderWidth: 2,
						data: <?php echo json_encode($cadBase) ?>
					}]
				},
				<?php if ($log_labels == 'S') { ?>
					plugins: [ChartDataLabels],
				<?php } ?>
				options: {
					legend: {
						display: true,
						position: 'bottom'
					},
					maintainAspectRatio: false,
					animation: {
						duration: 2000,
						onComplete: function() {
							$("input[name=lineChart2]").val(lineChart.toBase64Image());
							botaoPDF();
						}
					},
					scales: {
						yAxes: [{
							ticks: {
								beginAtZero: true,
								callback: function(value, index, values) {
									if (parseInt(value) >= 1000) {
										return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
									} else {
										return value;
									}
								}
							},
							afterTickToLabelConversion: function(object) {
								for (var tick in object.ticks) {
									object.ticks[tick];
								}
							}
						}],
					},
					tooltips: {
						callbacks: {
							label: function(t, d) {
								if (parseInt(t.yLabel) >= 1000) {
									return t.yLabel.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
								} else {
									return t.yLabel;
								}
							}
						}
					},
				},

			});

			//grouped
			var barchartgroupedperformance = new Chart(document.getElementById("bar-chart-grouped-performance"), {
				type: 'bar',
				data: {
					labels: ["Ticket Médio", "Gasto Médio"],
					datasets: [{
						label: "Com Resgate",
						borderColor: '#36A2EB',
						backgroundColor: '#36A2EB',
						borderWidth: 1,
						data: [<?= $listaTotTmResgate ?>, <?= $listaTotGmResgate ?>],
						<?php if ($log_labels == 'S') { ?>
							datalabels: {
								clamp: true,
								align: 'middle',
								anchor: 'end',
								borderRadius: 4,
								backgroundColor: '#2692DB',
								color: '#fff',
								formatter: function(value) {
									if (parseInt(value) >= 1000) {
										return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
									} else {
										return "R$ " + value.toFixed(2).replace('.', ',');
									}
									// eq. return ['line1', 'line2', value]
								}
							},
						<?php } ?>
					}, {
						label: "Sem Resgate",
						borderColor: '#4BC0C0',
						backgroundColor: '#4BC0C0',
						borderWidth: 1,
						data: [<?= $listaTotTmSem ?>, <?= $listaTotGmSem ?>],
						<?php if ($log_labels == 'S') { ?>
							datalabels: {
								clamp: true,
								align: 'middle',
								anchor: 'end',
								borderRadius: 4,
								backgroundColor: '#3BB0B0',
								color: '#fff',
								formatter: function(value) {
									if (parseInt(value) >= 1000) {
										return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
									} else {
										return "R$ " + value.toFixed(2).replace('.', ',');
									}
									// eq. return ['line1', 'line2', value]
								}
							},
						<?php } ?>
					}, {
						label: "Avulso",
						borderColor: '#E5E5E5',
						backgroundColor: '#E5E5E5',
						borderWidth: 1,
						data: [<?= $listaTotTmAvulso ?>, <?= $listaTotGmAvulso ?>],
						<?php if ($log_labels == 'S') { ?>
							datalabels: {
								clamp: true,
								align: 'middle',
								anchor: 'end',
								borderRadius: 4,
								backgroundColor: '#D5D5D5',
								color: '#fff',
								formatter: function(value) {
									if (parseInt(value) >= 1000) {
										return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
									} else {
										return "R$ " + value.toFixed(2).replace('.', ',');
									}
									// eq. return ['line1', 'line2', value]
								}
							},
						<?php } ?>
					}]
				},
				<?php if ($log_labels == 'S') { ?>
					plugins: [ChartDataLabels],
				<?php } ?>
				options: {
					animation: {
						onComplete: function() {
							$("input[name=barchartgroupedperformance]").val(barchartgroupedperformance.toBase64Image());
							botaoPDF();
						}
					},
					title: {
						display: true,
						text: 'TMs e GMs Fidelizados [CR e SR] vs Avulsos'
					},
					tooltips: {
						callbacks: {
							label: function(t, d) {
								return 'R$ ' + t.yLabel.toFixed(2)
							}
						}
					},
					scales: {
						yAxes: [{
							ticks: {
								callback: function(value, index, values) {
									if (parseInt(value) >= 1000) {
										return 'R$ ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
									} else {
										return 'R$ ' + value;
									}
								}
							}
						}]
					},

				}
			});


			//donut 
			var config3 = {
				<?php if ($log_labels == 'S') { ?>
					plugins: [ChartDataLabels],
				<?php } ?>
				type: 'doughnut',
				data: {
					datasets: [{
						<?php if ($log_labels == 'S') { ?>
							datalabels: {
								clamp: true,
								align: 'middle',
								anchor: 'end',
								borderRadius: 4,
								backgroundColor: [
									'#2692DB',
									'#3BB0B0',
									'#D5D5D5',
								],
								color: '#fff',
								formatter: function(value) {
									if (parseInt(value) >= 1000) {
										return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
									} else {
										return value.toFixed(2).replace('.', ',') + '%';
									}
									// eq. return ['line1', 'line2', value]
								}
							},
						<?php } ?>
						data: [
							<?= $listaTotTransacResgate ?>,
							<?= $listaTotTransacSem ?>,
							<?= $listaTotTransacAvulso ?>
						],
						borderColor: [
							'#fff',
							'#fff',
							'#fff',
						],
						backgroundColor: [
							'#36A2EB',
							'#4BC0C0',
							'#E5E5E5',
						],
						borderWidth: [1, 1, 0],
					}],
					labels: [
						" COM Resgate",
						" SEM Resgate",
						" AVULSOS"
					]
				},
				options: {
					tooltips: {
						callbacks: {
							title: function(tooltipItem, data) {
								return data['labels'][tooltipItem[0]['index']];
							},
							label: function(tooltipItem, data) {
								return data['datasets'][0]['data'][tooltipItem['index']] + '%';
							},
							// afterLabel: function(tooltipItem, data) {
							//   var dataset = data['datasets'][0];
							//   var percent = Math.round((dataset['data'][tooltipItem['index']] / dataset["_meta"][0]['total']) * 100)
							//   return '(' + percent + '%)';
							// }
						}
					},
					responsive: true,
					legend: {
						position: 'bottom',
					},
					title: {
						display: true,
						text: 'Transações Fidelizadas [CR e SR] vs Avulsas'
					},
					animation: {
						animateScale: true,
						animateRotate: true,
						onComplete: function() {
							$("input[name=mydoughnut]").val(myDoughnutCt.toBase64Image());
							botaoPDF();
						}
					},
					legend: {
						position: 'bottom',
					}
				}
			};

			window.onload = function() {
				var ctx3 = document.getElementById("donut-performance").getContext("2d");
				window.myDoughnutCt = new Chart(ctx3, config3);
			};

			//donut 
			var config = {
				<?php if ($log_labels == 'S') { ?>
					plugins: [ChartDataLabels],
				<?php } ?>
				type: 'doughnut',
				data: {
					datasets: [{
						<?php if ($log_labels == 'S') { ?>
							datalabels: {
								clamp: true,
								align: 'middle',
								anchor: 'end',
								borderRadius: 4,
								backgroundColor: [
									'#3BB0B0',
									'#2692DB'
								],
								color: '#fff',
								formatter: function(value) {
									if (parseInt(value) >= 1000) {
										return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
									} else {
										return value;
									}
									// eq. return ['line1', 'line2', value]
								}
							},
						<?php } ?>
						data: [
							"<?= $qtd_feminino ?>",
							"<?= $qtd_masculino ?>",
							// <?= ($qtd_masculino + $qtd_feminino) ?>,
						],
						backgroundColor: [
							window.chartColors.green,
							window.chartColors.blue,
							// "#E5E5E5",
						],
						label: 'Dataset 1'
					}],
					labels: [
						"Mulheres - <?= fnValor($pct_feminino, 0) ?>%",
						"Homens - <?= fnValor($pct_masculino, 0) ?>%",
						// "Indefinidos"
					]
				},
				options: {
					tooltips: {
						callbacks: {
							title: function(tooltipItem, data) {
								return data['labels'][tooltipItem[0]['index']].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
							},
							label: function(tooltipItem, data) {
								return data['datasets'][0]['data'][tooltipItem['index']].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
							},
						}
					},
					//rotation: 1 * Math.PI,
					//circumference: 1 * Math.PI,
					responsive: true,
					legend: {
						position: 'bottom',
					},
					// title: {
					// 	display: true,
					// 	text: 'Chart.js Doughnut Chart'
					// },
					animation: {
						animateScale: true,
						animateRotate: true,
						onComplete: function() {
							$("input[name=chartarea]").val(myDoughnut.toBase64Image());
							botaoPDF();
						}
					}
				}
			};


			//donut 
			var config2 = {
				type: 'doughnut',
				data: {
					datasets: [{
						data: [
							"<?= $qtd_transacoes_fidelizado ?>",
							"<?= $qtd_transacoes_avulso ?>",
						],
						backgroundColor: [
							window.chartColors.blue,
							window.chartColors.green,
						],
						label: 'Dataset 1'
					}],
					labels: [
						"Fidelizadas - <?= fnValor($pct_transacoes_fidelizado, 0) ?>%",
						"Avulsas - <?= fnValor($pct_transacoes_avulso, 0) ?>%"
					]
				},
				options: {
					tooltips: {
						callbacks: {
							title: function(tooltipItem, data) {
								return data['labels'][tooltipItem[0]['index']].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
							},
							label: function(tooltipItem, data) {
								return data['datasets'][0]['data'][tooltipItem['index']].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
							},
						}
					},
					responsive: true,
					legend: {
						position: 'bottom',
					},
					// title: {
					// 	display: true,
					// 	text: 'Chart.js Doughnut Chart'
					// },
					animation: {
						animateScale: true,
						animateRotate: true,
						onComplete: function() {
							$("input[name=chartarea2]").val(myDoughnut2.toBase64Image());
							botaoPDF();
						}
					}
				}
			};

			var ctx = document.getElementById("chart-area").getContext("2d");
			var myDoughnut = new Chart(ctx, config);


			// var ctx2 = document.getElementById("chart-area2").getContext("2d");
			// var myDoughnut2 = new Chart(ctx2,config2);

			var data = {
				labels: ["Fideliz. Limpo - R$ <?= fnValor($listaFatLmp, 2) ?>", "Avulso - R$ <?= fnValor($listaFatAv, 2) ?>", "Resgate Total - R$ <?= fnValor($listaFatTotRes, 2) ?>"],
				datasets: [{

					backgroundColor: [
						window.chartColors.red,
						window.chartColors.blue,
						window.chartColors.green,

					],
					data: [<?= $listaFatFid ?>, <?= $listaPctFatAv ?>, <?= $listaFatRes ?>],
					// Notice the borderColor 
					// borderColor: ['black', 'black'],
					borderWidth: [1, 1]
				}]
			};


		});

		function botaoPDF() {
			if (
				($("input[name=barchartgrouped]").val() != "") &&
				($("input[name=barchartgrouped2]").val() != "") &&
				($("input[name=chartarea]").val() != "") &&
				($("input[name=barchartgroupedperformance]").val() != "") &&
				($("input[name=mydoughnut]").val() != "") &&
				($("input[name=lineChart2]").val() != "")
			) {
				$("#bt_PDF").removeAttr("disabled");
			}
		}
	</script>

<?php

}
