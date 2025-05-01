<?php
//https://adm.bunker.mk/action.do?mod=VU6Q8bfsZp%C2%A30%C2%A2&id=GLtHxidZjko%C2%A2

setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');

//echo fnDebug('true');

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
		$dt_filtro = $_REQUEST['DT_FILTRO'];
		$dt_exibe = $dt_filtro . "-01";

		if (empty($_REQUEST['LOG_LABELS'])) {
			$log_labels = 'N';
		} else {
			$log_labels = $_REQUEST['LOG_LABELS'];
		}


		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		// fnescreve($dt_exibe);
		// fnescreve($dt_filtro);

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
// if ($cod_univend == "9999"){$temUnivend = "N";} else {$temUnivend = "S";}

// fnEscreve($dt_filtro);

//busca revendas do usuário
include "unidadesAutorizadas.php";

// Filtro por Grupo de Lojas
include "filtroGrupoLojas.php";

$sqlPeriodos = "SELECT DISTINCT MESANO FROM TB_FECHAMENTO_CLIENTE where COD_EMPRESA = $cod_empresa order by MESANO desc ";
$arrayQueryPeriodos = mysqli_query(conntemp($cod_empresa,''), trim($sqlPeriodos));
//fnEscreve($sqlPeriodos);

$qtd_periodos = mysqli_num_rows($arrayQueryPeriodos);

if ($qtd_periodos == 0) {
	$msgTipo = "alert-danger";
	$msgRetorno = "Você ainda <b>não possui</b> a configuração para utilizar essa tela. <br/> Entre em <b>contato</b> com o seu <b>consultor</b>.";
}

if ($dt_filtro == "") {
	$sql = "SELECT MAX(MESANO) AS DT_FILTRO FROM TB_FECHAMENTO_CLIENTE where COD_EMPRESA = $cod_empresa";
	// $sql = "SELECT MAX(DT_FILTRO) AS DT_FILTRO FROM filtro_frequencia where COD_EMPRESA = $cod_empresa";
	$qrDt = mysqli_fetch_assoc(mysqli_query(conntemp($cod_empresa,''), trim($sql)));
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

	.icones {
	    display: inline-block;
	    border-radius: 5px;
	    color: #ffffff;
	    font-weight: bold;
	    text-align: center;
	    margin-bottom: 10px;
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

	.lista {
		background: rgba(255, 255, 255, 0.3);
		padding: 6px 9px;
		border-radius: 4px;
		font-size: 24px;
		height: 30px;
		width: 100px;
		gap: 5px;
		display: flex;
	    flex-direction: row;
	    align-items: center;
	    justify-content: center;
	}

	.lista i {
		font-size: 18px;
	}

	.lista span {
		font-size: 14px;
	}

	.bartext {
		text-align: right;
		margin-right: 15px;
	}

	.div-oculta {
		visibility: hidden;
		opacity: 0;
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

	//$mesIni = '2019-08';
	// $mesFim = '2019-09';
	$sql = "CALL SP_RELAT_FECHAMENTO_CLIENTE('$dt_filtro','$lojasSelecionadas', $cod_empresa )";
	// fnEscreve($sql);
	//echo($sql);

	$arrayQuery = mysqli_query(conntemp($cod_empresa,''), $sql);
	$qrAnalitics = mysqli_fetch_assoc($arrayQuery);

	$vl_faturamento_fidelizado = $qrAnalitics['VL_FATURAMENTO_FIDELIZADO'];
	//$pct_faturamento_fidelizado = $qrAnalitics['PCT_FATURAMENTO_FIDELIZADO'];											
	$qtd_transacoes = $qrAnalitics['QTD_TRANSACOES'];
	$qtd_transacoes_fidelizado = $qrAnalitics['QTD_TRANSACOES_FIDELIZADO'];
	$qtd_transacoes_avulso = $qrAnalitics['QTD_TRANSACOES_AVULSO'];

	$pct_transacoes_fidelizado = ($qtd_transacoes_fidelizado / $qtd_transacoes) * 100;
	$pct_transacoes_avulso = ($qtd_transacoes_avulso / $qtd_transacoes) * 100;

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
	$perc_20_concentracao_faturamento = $qrAnalitics['PERC_20_CONCENTRACAO_FATURAMENTO'];

	$vl_gm = $qrAnalitics['VL_GM'];
	$qtd_transacoes = $qrAnalitics['QTD_TRANSACOES'];

	$vl_total_resgate = $qrAnalitics['VL_TOTAL_RESGATE'];
	$qtd_cli_resgate = $qrAnalitics['QTD_CLI_RESGATE'];
	$perc_vl_resgate = $qrAnalitics['PERC_VL_RESGATE'];
	$qtd_cli_expirado = $qrAnalitics['QTD_CLI_EXPIRADO'];

	$vl_faturamento_cli_expirado = $qrAnalitics['VL_FATURAMENTO_CLI_EXPIRADO'];

	$vl_faturamento_fidelizado_mes_ant = $qrAnalitics['VL_FATURAMENTO_FIDELIZADO_MES_ANT'];
	$perc_faturamento_fidelizado_mes_ant = $qrAnalitics['PERC_FIDELIZADO_ANTERIOR'];

	$vl_indice_frequencia = $qrAnalitics['VL_INDICE_FREQUENCIA'];
	$vl_indice_frequencia_ant = $qrAnalitics['VL_INDICE_FREQUENCIA_ANT'];
	//fnEscreve($qrAnalitics['VL_INDICE_FREQUENCIA']);
	//fnEscreve($qrAnalitics['VL_INDICE_FREQUENCIA_ANT']);

	$pct_faturamento_fidelizado = (($vl_faturamento_fidelizado - $vl_faturamento_fidelizado_mes_ant) / $vl_faturamento_fidelizado_mes_ant) * 100;
	$pct_faturamento_ref = ($vl_faturamento_fidelizado / $qrAnalitics['VL_FATURAMENTO']) * 100;

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

	$arrayQuery = mysqli_query(conntemp($cod_empresa,''), $sql);

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

							$qrMes = mysqli_fetch_assoc(mysqli_query(conntemp($cod_empresa,''), $sql));

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

							$qrSpan = mysqli_fetch_assoc(mysqli_query(conntemp($cod_empresa,''), $sql));

							if ($qrSpan['COD_FILTRO'] != "") {

								$cod_filtro = $qrSpan['COD_FILTRO'];

								//busca dados do filtro
								$sql = "SELECT COD_FILTRO , QTD_DIASHIST , DT_FILTRO FROM filtro_frequencia where COD_EMPRESA = $cod_empresa AND COD_FILTRO = $cod_filtro ";
								//fnEscreve($sql);
								$arrayQuery = mysqli_query(conntemp($cod_empresa,''), trim($sql));
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
								$arrayQuery = mysqli_query(conntemp($cod_empresa,''), $sql);

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
									$media_vvr[$count] = $qrFunil['VVR_MEDIO'];
									$resgTot += $qrFunil['VL_RESGATE_TOTAL'];
									//fnEscreve($vvr[$count]);
									$qtd_media_compra[$count] = $qrFunil['QTD_MEDIA_COMPRA'];
									$qtd_media_item[$count] = $qrFunil['QTD_MEDIA_ITEM'];

									$qtd_transacoes[$count] = $qrFunil['QTD_TRANSACOES'];
									$qtd_total_item[$count] = $qrFunil['QTD_TOTAL_ITEM'];

									//fnEscreve($qtd_transacoes[$count]);
									//fnEscreve($qtd_total_item[$count]);
									//fnEscreve($qtd_media_compra);

									$count++;
								}

								$resg1 = ($resgate_total[0] / $resgTot) * 100;
								$resg2 = ($resgate_total[1] / $resgTot) * 100;
								$resg3 = ($resgate_total[2] / $resgTot) * 100;
								$resg4 = ($resgate_total[3] / $resgTot) * 100;
								$resg5 = $resgate_total[4];

								$soma_cliente = array_sum($cliente);

								$bar_porc1 = 6524;
								$porcent_bar1 = round(($bar_porc1 / $soma_cliente) * 100);

								$bar_porc2 = 3915;
								$porcent_bar2 = round(($bar_porc2 / $soma_cliente) * 100);

								$bar_porc3 = 1957;
								$porcent_bar3 = round(($bar_porc3 / $soma_cliente) * 100);

								$bar_porc4 = 654;
								$porcent_bar4 = round(($bar_porc4 / $soma_cliente) * 100);

								$freq1 = $gm[0] / $gm[0];
								$freq2 = $gm[1] / $gm[0];
								$freq3 = $gm[2] / $gm[0];
								$freq4 = $gm[3] / $gm[0];
								$freq5 = $gm[4] / $gm[0];

								$qtd_media_compra1 = $qtd_media_compra[0];
								$qtd_media_compra2 = $qtd_media_compra[1];
								$qtd_media_compra3 = $qtd_media_compra[2];
								$qtd_media_compra4 = $qtd_media_compra[3];
								$qtd_media_compra5 = $qtd_media_compra[4];

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

												<div class="form-group text-center col-lg-5">
													<table class="table table-striped">
														<thead>
															<tr>
																<th class="text-center f18" colspan="2" style="visibility: hidden;">CONCENTRAÇÃO DE CLIENTES</th>
															</tr>
														</thead>
														<tbody>

															<tr>
																<td>
																	<div class="row">
																		<div class="col-md-12">
																			<div class="row">
																				<div class="col-md-12 text-center">
																					<div class="push10"></div>
																					<span class="f18 fCor1"><b><?php echo $faixa[0]; ?></b></span>
																				</div>
																			</div>
																			<div class="col-md-6">

																				<div class="row">
																				<div class="push30"></div>	
																				<div class="push10"></div>	

																					<div class="col-md-6 text-center mr-3">
																						<div class="icones cor1 center-block" 
																							 data-toggle="tooltip"
																							 data-placement="top" title="
																							 ">
																							<div class="lista">
																								<i class="fas fa-male"></i>
																								<span>1x</span>		 	
																							</div>
																						</div>
																					</div>

																					<div class="col-md-6 text-center mr-3">
																						<div class="icones cor1 center-block" 
																							 data-toggle="tooltip"
																							 data-placement="top" title="<?= fnValor($qtd_media_compra1, 1)?> compras médias no período
																							 ">
																							<div class="lista">
																								<span><?= fnValor($qtd_media_compra1, 1) ?></span>		 	
																							</div>
																						</div>
																					</div>

																				</div>

																				<div class="row">
																					<div class="col-md-6 text-center mr-3">
																						<div class="icones cor1 center-block" 
																							 data-toggle="tooltip"
																							 data-placement="top" title="Idade Média dos clientes: <?= fnValor($im[0], 0) ?> anos
																							 ">
																							<div class="lista">
																								<i class="fal fa-birthday-cake"></i>
																								<span><?= fnValor($im[0], 0) ?></span>	 	
																							</div>
																						</div>
																					</div>													

																					<div class="col-md-6 text-center mr-3">
																						<div class="icones cor1 center-block" 
																							 data-toggle="tooltip" 
																							 data-placement="top" 
																							 title="Gasto Médio:  R$ <?php echo fnValor($gm[0], 2); ?>" 
																							 >
																							 <div class="lista">
																								<i class="fal fa-shopping-cart"></i>
																								<span><?php echo fnValor($gm[0], 2); ?></span>
																							 </div>
																						</div>
																					</div>
																				</div>

																				<div class="row">
																					<div class="col-md-6 text-center">
																						<div class="icones cor1 center-block" 
																					    	 data-toggle="tooltip" 
																							 data-placement="top" 
																							 title="Média IC: <?php echo fnValor($qtd_media_item[0], 0); ?>" 
																						 >
																							 <div class="lista">
																								<i class="fal fa-shopping-cart"></i>
																								<span><?php echo fnValor($qtd_media_item[0], 0); ?></span>
																							 </div>
																						</div>
																					</div>

																					<div class="col-md-6 text-center">
																						<div class="icones cor1 center-block" 
 																							 data-toggle="tooltip" 
																							 data-placement="top" 
																							 title="Rentabilidade: <?= round($freq1) ?>x" 
																						 >
																							 <div class="lista">
																								<i class="ico fal fa-chart-line"></i>
																								<span><?= round($freq1) ?>x</span>
																							 </div>
																						</div>
																					</div>
																				</div>
																			</div>

																			<div class="col-md-6" style="margin-top: 6px;">
																				<div class="push30"></div>
																				<div class="bar cor1" style="width: -webkit-calc(100%);">
																					<div class="bartext"> <?= fnValor($cliente[0], 0); ?>
																						<span><?= $bar[0] ?>%</span>
																					</div> 
																				</div>

																				<div class="div-oculta">
																					<div class="push20"></div>
																					<input type="text" class="casual" name="my_range" value="" />
																					<div class="push20"></div>
																				</div>
																			</div>
																		</div>
																	</div>
																</td>	
															</tr>

															<tr>
																<td>
																	<div class="row">
																		<div class="col-md-12">
																			<div class="row">
																				<div class="col-md-12 text-center">
																					<div class="push10"></div>
																					<span class="f18 fCor2"><b><?php echo $faixa[1]; ?></b></span>
																				</div>
																			</div>
																			<div class="col-md-6">

																				<div class="row">
																					<div class="push30"></div>	
																					<div class="push10"></div>

																					<div class="col-md-6 text-center mr-3">
																						<div class="icones cor2 center-block" 
																							 data-toggle="tooltip"
																							 data-placement="top" title="
																							 ">
																							<div class="lista">
																								<i class="fas fa-male"></i>
																								<span>2x</span>		 	
																							</div>
																						</div>
																					</div>	

																					<div class="col-md-6 text-center mr-3">
																						<div class="icones cor2 center-block" 
																							 data-toggle="tooltip"
																							 data-placement="top" title="<?= fnValor($qtd_media_compra2, 1)?> compras médias no período
																							 ">
																							<div class="lista">
																								<span><?= fnValor($qtd_media_compra2, 1) ?></span>		 	
																							</div>
																						</div>
																					</div>
																				</div>

																				<div class="row">
																					<div class="col-md-6 text-center mr-3">
																						<div class="icones cor2 center-block" 
																							 data-toggle="tooltip"
																							 data-placement="top" title="Idade Média dos clientes: <?= fnValor($im[1], 0) ?> anos
																							 ">
																							<div class="lista">
																								<i class="fal fa-birthday-cake"></i>
																								<span><?= fnValor($im[1], 0) ?></span>	 	
																							</div>
																						</div>
																					</div>													

																					<div class="col-md-6 text-center mr-3">
																						<div class="icones cor2 center-block" 
																							 data-toggle="tooltip" 
																							 data-placement="top" 
																							 title="Gasto Médio:  R$ <?php echo fnValor($gm[1], 2); ?>" 
																							 >
																							 <div class="lista">
																								<i class="fal fa-shopping-cart"></i>
																								<span><?php echo fnValor($gm[1], 2); ?></span>
																							 </div>
																						</div>
																					</div>
																				</div>

																				<div class="row">

																					<div class="col-md-6 text-center">
																						<div class="icones cor2 center-block" 
																					    	 data-toggle="tooltip" 
																							 data-placement="top" 
																							 title="Média IC: <?php echo fnValor($qtd_media_item[1], 0); ?>" 
																							 >
																							 <div class="lista">
																								<i class="fal fa-shopping-cart"></i>
																								<span><?php echo fnValor($qtd_media_item[0], 0); ?></span>
																							 </div>
																						</div>
																					</div>

																					<div class="col-md-6 text-center">
																						<div class="icones cor2 center-block" 
																							 data-toggle="tooltip" 
																							 data-placement="top" 
																							 title="Rentabilidade: <?= round($freq2) ?>x" 
																							 >
																							 <div class="lista">
																								<i class="ico fal fa-chart-line"></i>
																								<span><?= round($freq2) ?>x</span>
																							 </div>
																						</div>
																					</div>
																				</div>
																			</div>
																			<div class="col-md-6" style="margin-top: 6px;">
																				<div class="push30"></div>
																				<div class="bar cor2" style="width: -webkit-calc(80%);">
																					<div class="bartext"> <?= fnValor($cliente[1], 0); ?>
																						<span><?= $bar[1] ?>%</span>
																					</div> 
																				</div>

																				<div class="div-oculta">
																					<div class="push20"></div>
																					<input type="text" class="casual" name="my_range" value="" />
																					<div class="push20"></div>
																				</div>
																			</div>
																		</div>
																	</div>
																</td>	
															</tr>

															<tr>
																<td>
																	<div class="row">
																		<div class="col-md-12">
																			<div class="col-md-12 text-center">
																				<div class="push10"></div>
																				<span class="f18 fCor3"><b><?php echo $faixa[2]; ?></b></span>
																			</div>
																			<div class="col-md-6">
																				<div class="row">
																				</div>

																				<div class="row">
																					<div class="push30"></div>	
																					<div class="push10"></div>

																					<div class="col-md-6 text-center mr-3">
																						<div class="icones cor3 center-block" 
																							 data-toggle="tooltip"
																							 data-placement="top" title="
																							 ">
																							<div class="lista">
																								<i class="fas fa-male"></i>
																								<span>4x</span>		 	
																							</div>
																						</div>
																					</div>

																					<div class="col-md-6 text-center mr-3">
																						<div class="icones cor3 center-block" 
																							 data-toggle="tooltip"
																							 data-placement="top" title="<?= fnValor($qtd_media_compra3, 1)?> compras médias no período
																							 ">
																							<div class="lista">
																								<span><?= fnValor($qtd_media_compra3, 1) ?></span>		 	
																							</div>
																						</div>
																					</div>
																				</div>

																				<div class="row">

																					<div class="col-md-6 text-center mr-3">
																						<div class="icones cor3 center-block" 
																							 data-toggle="tooltip"
																							 data-placement="top" title="Idade Média dos clientes: <?= fnValor($im[2], 0) ?> anos
																							 ">
																							<div class="lista">
																								<i class="fal fa-birthday-cake"></i>
																								<span><?= fnValor($im[2], 0) ?></span>	 	
																							</div>
																						</div>
																					</div>													

																					<div class="col-md-6 text-center mr-3">
																						<div class="icones cor3 center-block" 
																							 data-toggle="tooltip" 
																							 data-placement="top" 
																							 title="Gasto Médio:  R$ <?php echo fnValor($gm[2], 2); ?>" 
																							 >
																							 <div class="lista">
																								<i class="fal fa-shopping-cart"></i>
																								<span><?php echo fnValor($gm[2], 2); ?></span>
																							 </div>
																						</div>
																					</div>
																					
																				</div>

																				<div class="row">

																						<div class="col-md-6 text-center">
																							<div class="icones cor3 center-block" 
																						    	 data-toggle="tooltip" 
																								 data-placement="top" 
																								 title="Média IC: <?php echo fnValor($qtd_media_item[2], 0); ?>" 
																								 >
																								 <div class="lista">
																									<i class="fal fa-shopping-cart"></i>
																									<span><?php echo fnValor($qtd_media_item[2], 0); ?></span>
																								 </div>
																							</div>
																						</div>
																						<div class="col-md-6 text-center">
																							<div class="icones cor3 center-block" 
 																								 data-toggle="tooltip" 
																								 data-placement="top" 
																								 title="Rentabilidade: <?= round($freq3) ?>x" 
																								 >
																								 <div class="lista">
																									<i class="ico fal fa-chart-line"></i>
																									<span><?= round($freq3) ?>x</span>
																								 </div>
																							</div>
																						</div>
																					</div>
																				</div>
																			<div class="col-md-6" style="margin-top: 6px;">
																				<div class="push30"></div>
																				<div class="bar cor3" style="width: -webkit-calc(65%);">
																					<div class="bartext"> <?= fnValor($cliente[2], 0); ?>
																						<span><?= $bar[2] ?>%</span>
																					</div> 
																				</div>
																				<div class="div-oculta">
																					<div class="push20"></div>
																					<input type="text" class="casual" name="my_range" value="" />
																					<div class="push20"></div>
																				</div>
																			</div>
																		</div>
																	</div>
																</td>	
															</tr>

															<tr>
																<td>
																	<div class="row">
																		<div class="col-md-12">
																			<div class="col-md-12 text-center">
																				<div class="push10"></div>
																				<span class="f18 fCor4"><b><?php echo $faixa[3]; ?></b></span>
																			</div>
																			<div class="col-md-6">
																				<div class="row">
																				</div>

																				<div class="row">
																					<div class="push30"></div>	
																					<div class="push10"></div>

																					<div class="col-md-6 text-center mr-3">
																						<div class="icones cor4 center-block" 
																							 data-toggle="tooltip"
																							 data-placement="top" title="
																							 ">
																							<div class="lista">
																								<i class="fas fa-male"></i>
																								<span>12x</span>		 	
																							</div>
																						</div>
																					</div>

																					<div class="col-md-6 text-center mr-3">
																						<div class="icones cor4 center-block" 
																							 data-toggle="tooltip"
																							 data-placement="top" title="<?= fnValor($qtd_media_compra4, 1)?> compras médias no período
																							 ">
																							<div class="lista">
																								<span><?= fnValor($qtd_media_compra4, 1) ?></span>		 	
																							</div>
																						</div>
																					</div>
																				</div>

																				<div class="row">

																					<div class="col-md-6 text-center mr-3">
																						<div class="icones cor4 center-block" 
																							 data-toggle="tooltip"
																							 data-placement="top" title="Idade Média dos clientes: <?= fnValor($im[3], 0) ?> anos
																							 ">
																							<div class="lista">
																								<i class="fal fa-birthday-cake"></i>
																								<span><?= fnValor($im[3], 0) ?></span>	 	
																							</div>
																						</div>
																					</div>													

																					<div class="col-md-6 text-center mr-3">
																						<div class="icones cor4 center-block" 
																							 data-toggle="tooltip" 
																							 data-placement="top" 
																							 title="Gasto Médio:  R$ <?php echo fnValor($gm[3], 2); ?>" 
																							 >
																							 <div class="lista">
																								<i class="fal fa-shopping-cart"></i>
																								<span><?php echo fnValor($gm[3], 2); ?></span>
																							 </div>
																						</div>
																					</div>
																				</div>
																				<div class="row">

																					<div class="col-md-6 text-center">
																						<div class="icones cor4 center-block" 
																					    	 data-toggle="tooltip" 
																							 data-placement="top" 
																							 title="Média IC: <?php echo fnValor($qtd_media_item[3], 0); ?>" 
																							 >
																							 <div class="lista">
																								<i class="fal fa-shopping-cart"></i>
																								<span><?php echo fnValor($qtd_media_item[3], 0); ?></span>
																							 </div>
																						</div>
																					</div>

																					<div class="col-md-6 text-center">
																						<div class="icones cor4 center-block" 
 																							 data-toggle="tooltip" 
																							 data-placement="top" 
																							 title="Rentabilidade: <?= round($freq4) ?>x" 
																							 >
																							 <div class="lista">
																								<i class="ico fal fa-chart-line"></i>
																								<span><?= round($freq4) ?>x</span>
																							 </div>
																						</div>
																					</div>
																				</div>
																			</div>
																			<div class="col-md-6" style="margin-top: 6px;">
																				<div class="push30"></div>
																				<div class="bar cor4" style="width: -webkit-calc(50%);">
																					<div class="bartext"> <?= fnValor($cliente[3], 0); ?>
																						<span><?= $bar[3] ?>%</span>
																					</div> 
																				</div>
																				<div class="div-oculta">
																					<div class="push20"></div>
																					<input type="text" class="casual" name="my_range" value="" />
																					<div class="push20"></div>
																				</div>
																			</div>
																		</div>
																	</div>
																</td>	
															</tr>
														</tbody>		
													</table>
												</div>

												<div class="form-group text-center col-lg-7" id="atualiza">

													<table class="table table-striped">
														<thead>
															<tr>
																<th class="text-center f18" colspan="2">CONCENTRAÇÃO DE CLIENTES</th>
																<th class="text-center f18">TIPO DE CLIENTE</th>
																<th class="text-center f18">GASTO MÉDIO</th>
																<th class="text-center f18">RENTABILIDADE</th>
																<th></th>
															</tr>
														</thead>
														<tbody>

															<tr>
																<td colspan="2" class="text-cente" >
																	<div class="push50"></div>
																	<div class="push30"></div>
																	<div class="bar cor1" style="width: -webkit-calc(100%);">
																		<div id="textbar1"> 
																			<span style="margin-right: 15px;"><?= $bar[0] ?>%</span>
																			<?= fnValor($cliente[0], 0); ?>
																		</div>
																	</div>
																	<div class="push20"></div>
																	<input type="text" class="casual-slider" id="casual-slider" name="casual-slider" value="" />
																	<div class="push20"></div>
																</td>

																<td class="text-center">
																	<div class="push50"></div>
																	<div class="push30"></div>
																	<?php
																	$compara1 = (round($freq2 - 1));
																	$qtd_compras1 = round($compara1);
																	if ($qtd_media_compra1 <= 1) {			  
																		$txt_compras1 = fnValor($qtd_media_compra1, 1) . " compras médias no período";
																	} else {	
																		$txt_compras1 = fnValor($qtd_media_compra1, 1) . " compras médias no período";
																	}
																	for ($i = 0; $i < round($qtd_media_compra1); $i++) {
																		echo "<i class='fas fa-male fa-2x fCor1' style='margin: 0 3px 0 0;'></i>";
																	}
																	?>
																	<div class="push5"></div>
																	<span class="f18 fCor1"><b><?php echo $faixa[0]; ?></b></span>
																	<div class="push3"></div>
																	<span class="f12 fCor1"><small><?= fnValor($im[0], 0) ?> anos </small></span>
																	<div class="push3"></div>
																	<span class="f13 fCor1"><b><?php echo $txt_compras1; ?></b></span>
																</td>

																<td class="text-center">
																	<div class="push50"></div>
																	<div class="push30"></div>
																	<span class="f26b fCor1"><b>R$ <?php echo fnValor($gm[0], 2); ?></b></span>
																</td>

																<td class="text-center">
																	<div class="push50"></div>
																	<div class="push30"></div>
																	<span class="f30 fCor1"><b><?= round($freq1) ?>x</b></span>
																</td>

															</tr>

															<tr>

																<td colspan="2" class="text-center">
																	<div class="push50"></div>
																	<div class="push30"></div>
																	<div class="bar cor2" style="width: -webkit-calc(80%);">
																		<div id="textbar2">
																			<span style="margin-right: 15px;"><?= $bar[1] ?>%</span>
																			<?= fnValor($cliente[1], 0); ?>
																		</div>
																	</div>
																	<div class="push20"></div>
																	<input type="text" class="frequente-slider" id="frequente-slider" name="frequente-slider" value="" />
																	<div class="push20"></div>
																</td>

																<td class="text-center">
																	<div class="push50"></div>
																	<div class="push30"></div>
																	<?php
																	$compara2 = (round($freq3 - 1));
																	$qtd_compras2 = round($compara2);
																	$txt_compras2 = fnValor($qtd_media_compra2, 1) . " compras médias no período";
																	for ($i = 0; $i < round($qtd_media_compra2); $i++) {
																		echo "<i class='fas fa-male fa-2x fCor2' style='margin: 0 3px 0 0;'></i>";
																	}
																	?>
																	<div class="push5"></div>
																	<span class="f18 fCor2"><b><?php echo $faixa[1]; ?></b></span>
																	<div class="push3"></div>
																	<span class="f12 fCor2"><small><?= fnValor($im[1], 0) ?> anos </small></span>
																	<div class="push3"></div>
																	<span class="f13 fCor2"><b><?php echo $txt_compras2; ?></b></span>
																</td>

																<td class="text-center">
																	<div class="push50"></div>
																	<div class="push30"></div>
																	<span class="f26b fCor2"><b>R$ <?php echo fnValor($gm[1], 2); ?></b></span>
																</td>

																<td class="text-center">
																	<div class="push50"></div>
																	<div class="push30"></div>
																	<span class="f30 fCor2"><b> <?= round($freq2) ?>x </b></span>
																</td>

															</tr>

															<tr>

																<td colspan="2" class="text-center">
																	<div class="push50"></div>
																	<div class="push20"></div>
																	<div class="bar cor3" style="width: -webkit-calc(65%);">
																		<div id="textbar3">
																			<span style="margin-right: 15px;"><?= $bar[2] ?>%</span>
																			<?= fnValor($cliente[2], 0); ?>
																		</div>
																	</div>
																	<div class="push20"></div>
																	<input type="text" class="fiel-slider" id="fiel-slider" name="fiel-slider" value="" />
																	<div class="push20"></div>
																</td>

																<td class="text-center">
																	<div class="push50"></div>
																	<div class="push30"></div>
																	<?php
																	$compara3 = (round($freq4 - 1));
																	$qtd_compras3 = round($compara3);
																	$txt_compras3 = fnValor($qtd_media_compra3, 1) . " compras médias no período";
																	for ($i = 0; $i < round($qtd_media_compra3); $i++) {
																		echo "<i class='fas fa-male fa-2x fCor3' style='margin: 0 3px 0 0;'></i>";
																	}
																	?>
																	<div class="push5"></div>
																	<span class="f18 fCor3"><b><?php echo $faixa[2]; ?></b></span>
																	<div class="push3"></div>
																	<span class="f12 fCor3"><small><?= fnValor($im[2], 0) ?> anos </small></span>
																	<div class="push3"></div>
																	<span class="f13 fCor3"><b><?php echo $txt_compras3; ?></b></span>
																</td>

																<td class="text-center">
																	<div class="push50"></div>
																	<div class="push30"></div>
																	<span class="f30 fCor3"><b>R$ <?php echo fnValor($gm[2], 2); ?></b></span>
																</td>

																<td class="text-center">
																	<div class="push50"></div>
																	<div class="push30"></div>
																	<span class="f30 fCor3"><b><?= round($freq3) ?>x</b></span>
																</td>

															</tr>

															<tr>

																<td colspan="2" class="text-center">
																	<div class="push50"></div>
																	<div class="push30"></div>
																	<div class="bar cor4" style="width: -webkit-calc(50%);">
																		<div id="textbar4">
																			<span style="margin-right: 15px;"><?= $bar[3] ?>%</span>
																			<?= fnValor($cliente[3], 0); ?>
																		</div>
																	</div>
																	<div class="push20"></div>
																	<input type="text" class="fa-slider" id="fa-slider" name="fa-slider" value="" />
																	<div class="push20"></div>
																</td>

																<td class="text-center">
																	<div class="push50"></div>
																	<div class="push30"></div>
																	<?php
																	$compara4 = round($freq4);
																	$txt_compras4 = fnValor($qtd_media_compra4, 1) . " compras médias no período";
																	for ($i = 0; $i < round($qtd_media_compra4); $i++) {
																		echo "<i class='fas fa-male fa-2x fCor4' style='margin: 0 3px 0 0;'></i>";
																	}
																	?>
																	<div class="push5"></div>
																	<span class="f18 fCor4"><b><?php echo $faixa[3]; ?></b></span>
																	<div class="push3"></div>
																	<span class="f12 fCor4"><small><?= fnValor($im[3], 0) ?> anos </small></span>
																	<div class="push3"></div>
																	<span class="f13 fCor4"><b><?php echo $txt_compras4; ?> </b></span>
																</td>

																<td class="text-center">
																	<div class="push50"></div>
																	<div class="push30"></div>
																	<span class="f26b fCor4"><b>R$ <?php echo fnValor($gm[3], 2); ?></b></span>
																</td>

																<td class="text-center">
																	<div class="push50"></div>
																	<div class="push30"></div>
																	<span class="f30 fCor4"><b><?= round($freq4) ?>x</b></span>
																</td>
															</tr>

														</tbody>
													</table>
												</div>

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

					<div class="row">

						<div class="col-md-12 margin-bottom-30">
							<!-- Portlet -->
							<div class="portlet portlet-bordered">
								<div class="portlet-body">

									<form target="_blank" data-toggle="validator" role="geraPDF" method="post" id="geraPDF" action="relatorios/pdfDashFunilClientes.php">

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


										<div class="col-md-2">
											<button type="submit" name="ALT" id="bt_PDF" class="btn btn-info">
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<script src="js/gauge.coffee.js" type="text/javascript"></script>
<!--Plugin CSS file with desired skin-->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.1/css/ion.rangeSlider.min.css"/>

<!--Plugin JavaScript file-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.1/js/ion.rangeSlider.min.js"></script>

<script>

	$(".casual").ionRangeSlider({
		skin: "big",
		grid: true,
		from: 50,
		values: [
			0, 5, 10, 15, 20, 25,
			30, 35, 40, 45, 50, 55,
			60, 65, 70, 75, 80, 85, 90, 95, 100
			]
	});

	$("#casual-slider").ionRangeSlider({
		skin: "big",
		grid: true,
		min: 0,
  		max: 100,
		from: <?= $porcent_bar1 ?>,
		postfix: "%",
		onFinish: function (data) {

			$.ajax({
				type: "POST",
				url: "relatorios/ajxDashFunilClientes.do?opcao=casual",
				data: {valor_slider: data.from, soma_cliente: <?= $soma_cliente ?>},
				success: function(response) {
					$("#textbar1").html(response);
				},
				error: function(error){
					console.log(error); 
				}

			});

		}
	});

	$("#frequente-slider").ionRangeSlider({
		skin: "big",
		grid: true,
		min: 0,
  		max: 100,
		from: <?= $porcent_bar2 ?>,
		postfix: "%",
		onFinish: function (data) {

			$.ajax({
				type: "POST",
				url: "relatorios/ajxDashFunilClientes.do?opcao=frequente",
				data: {valor_slider: data.from, soma_cliente: <?= $soma_cliente ?>},
				success: function(response) {
					$("#textbar2").html(response);
				},
				error: function(error){
					console.log(error); 
				}

			});

		}
	});	

	$("#fiel-slider").ionRangeSlider({
		skin: "big",
		grid: true,
		min: 0,
  		max: 100,
		from: <?= $porcent_bar3 ?>,
		postfix: "%",
		onFinish: function (data) {

			$.ajax({
				type: "POST",
				url: "relatorios/ajxDashFunilClientes.do?opcao=fiel",
				data: {valor_slider: data.from, soma_cliente: <?= $soma_cliente ?>},
				success: function(response) {
					$("#textbar3").html(response);
				},
				error: function(error){
					console.log(error); 
				}

			});

		}
	});

	$("#fa-slider").ionRangeSlider({
		skin: "big",
		grid: true,
		min: 0,
 		max: 100,
		from: <?= $porcent_bar4 ?>,
		postfix: "%",
		onFinish: function (data) {

			$.ajax({
				type: "POST",
				url: "relatorios/ajxDashFunilClientes.do?opcao=fa",
				data: {valor_slider: data.from, soma_cliente: <?= $soma_cliente ?>},
				success: function(response) {
					$("#textbar4").html(response);
				},
				error: function(error){
					console.log(error); 
				}

			});

		}
	});
</script>
<?php
}