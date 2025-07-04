<?php

$hashLocal = "";
$hoje = "";
$dias30 = "";
$msgRetorno = "";
$msgTipo = "";
$log_labels = "";
$cod_controle = "";
$periodo = "";
$dat_ini = "";
$dat_fim = "";
$opcao = "";
$hHabilitado = "";
$hashForm = "";
$array_dat_fim = [];
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$dat_cadastr = "";
$temUnivend = "";
$maxComp = "";
$maxEvo = "";
$qrBuscaFiltro = "";
$sqlPeriodo = "";
$arrayQueryPeriodo = [];
$qtd_periodos = 0;
$checkLabels = "";
$dt_filtro = "";
$qrListaFiltro = "";
$ano = "";
$sqlPeriodo2 = "";
$arrayQueryPeriodo2 = [];
$qrPer = "";
$classifica = "";
$andPeriodo = "";
$rs = "";
$tot_QTD_CLIENTE = "";
$tot_QTD_COMPRAS = "";
$tot_VAL_TOTCOMPRAS = "";
$tot_VAL_TOTPRODU = "";
$tot_QTD_RESGATES = "";
$tot_VAL_RESGATES = "";
$tot_QTD_PRODUTOS = "";
$tot_VAL_VINCULADO_RESGATE = "";
$qrLista = "";
$qtd_cliente = 0;
$qtd_compras = 0;
$val_totcompras = "";
$val_totprodu = "";
$tot_casual = "";
$tot_frequente = "";
$tot_fiel = "";
$tot_fa = "";
$pct_casual = "";
$pct_frequente = "";
$pct_fiel = "";
$pct_fa = "";
$tot_compra_casual = "";
$tot_compra_frequente = "";
$tot_compra_fiel = "";
$tot_compra_fa = "";
$tot_produ_casual = "";
$tot_produ_frequente = "";
$tot_produ_fiel = "";
$tot_produ_fa = "";
$media_val_casual = "";
$media_val_frequente = "";
$media_val_fiel = "";
$media_val_fa = "";
$valores = "";
$porcentagens = "";
$minMed = "";
$maxMed = "";
$minPct = "";
$maxPct = "";
$primeiro_indiceM = "";
$primeiro_indiceP = "";
$ticksMed = "";
$ticksPct = "";
$ticksExibe = "";
$ticksExibeP = "";
$tick = "";
$step = "";
$tickPct = "";
$stepPct = "";


//echo fnDebug('true');
$hashLocal = mt_rand();
//fnMostraForm();
//inicialização de variáveis
//$hoje = fnFormatDate(date("Y-m-d"));
$hoje = fnFormatDate(date('Y-m-d', strtotime($hoje . '- 1 days')));
$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30 . '- 30 days')));

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode(@$_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_empresa = fnLimpaCampoZero(@$_POST['COD_EMPRESA']);
		if (empty(@$_REQUEST['LOG_LABELS'])) {
			$log_labels = 'N';
		} else {
			$log_labels = @$_REQUEST['LOG_LABELS'];
		}
		$cod_univend = @$_REQUEST['COD_UNIVEND'];
		$cod_grupotr = @$_REQUEST['COD_GRUPOTR'];
		$cod_tiporeg = @$_REQUEST['COD_TIPOREG'];
		$cod_controle = @$_REQUEST['COD_CONTROLE'];
		$periodo = fnLimpaCampoZero(@$_REQUEST['COD_CONTROLE']);
		$dat_ini = @$_REQUEST['DAT_INI'] != 0 ? ("01 / " . @$_REQUEST['DAT_INI']) : 0;
		$dat_fim = @$_REQUEST['DAT_FIM'];
		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		$array_dat_fim  = explode("/", $dat_fim);

		$dat_fim = cal_days_in_month(CAL_GREGORIAN, $array_dat_fim['0'], $array_dat_fim['1']) . "/" . $dat_fim;

		if ($opcao != '' && $opcao != 0) {
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
if ($cod_univend == "9999") {
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
if ($cod_controle == "") {
	//fnEscreve(1); 
	$sql = "SELECT MAX(COD_CONTROLE) AS COD_CONTROLE FROM FECHAMENTO_CLIENTES_U where COD_EMPRESA = $cod_empresa ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query(connTemp($cod_empresa, ""), trim($sql));
	$qrBuscaFiltro = mysqli_fetch_assoc($arrayQuery);

	$cod_controle = $qrBuscaFiltro['COD_CONTROLE'];
} else {
	//fnEscreve(2); 		
	$sql = "SELECT COD_CONTROLE FROM FECHAMENTO_CLIENTES_U where COD_EMPRESA = $cod_empresa ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query(connTemp($cod_empresa, ""), trim($sql));
	$qrBuscaFiltro = mysqli_fetch_assoc($arrayQuery);

	$cod_controle = $qrBuscaFiltro['COD_CONTROLE'];
}

$dat_ini = fnDatasql($dat_ini);
$dat_fim = fnDatasql($dat_fim);


$sqlPeriodo = "SELECT COD_CONTROLE, DAT_PERIODO FROM FECHAMENTO_CLIENTES_U WHERE cod_empresa=$cod_empresa ORDER BY COD_CONTROLE desc";
$arrayQueryPeriodo = mysqli_query(connTemp($cod_empresa, ""), trim($sqlPeriodo));

$qtd_periodos = mysqli_num_rows($arrayQueryPeriodo);

if ($qtd_periodos == 0) {
	$msgTipo = "alert-danger";
	$msgRetorno = "Você ainda <b>não possui</b> a configuração para utilizar essa tela. <br/> Entre em <b>contato</b> com o seu <b>consultor</b>.";
}

if ($log_labels == 'S') {
	$checkLabels = "checked";
} else {
	$checkLabels = "";
}

//fnEscreve($dt_filtro);
//fnEscreve($cod_controle);
?>

<style>
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
</style>

<div class="push30"></div>

<div class="row">

	<div class="col-md-12 margin-bottom-30">
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
										<select data-placeholder="Selecione o período" name="COD_CONTROLE" id="COD_CONTROLE" class="chosen-select-deselect">
											<option value=""></option>
											<?php

											while ($qrListaFiltro = mysqli_fetch_assoc($arrayQueryPeriodo)) {
												echo "
																				  <option value='" . $qrListaFiltro['COD_CONTROLE'] . "'>" . $qrListaFiltro['DAT_PERIODO'] . " " . $ano . "</option> 
																				";
											}
											?>
										</select>
										<script>
											$("#formulario #COD_CONTROLE").val("<?php echo $periodo; ?>").trigger("chosen:updated");
										</script>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-1">
									<div class="form-group">
										<label for="inputName" class="control-label">Exibir legendas</label>
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

						<input type="hidden" name="NUM_ORDENAC" id="NUM_ORDENAC" value="">
						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
						<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
						<div class="push5"></div>

					</form>


				</div>

			</div>

		</div>

	</div>

	<div class="col-md-12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">

			<?php
			//fnEscreve($cod_controle);

			if ($periodo != 0 && $periodo != '') {
				$sqlPeriodo2 = "SELECT * FROM FECHAMENTO_CLIENTES_U WHERE COD_EMPRESA = $cod_empresa AND COD_CONTROLE = $periodo";
			} else {
				$sqlPeriodo2 = "SELECT * FROM FECHAMENTO_CLIENTES_U WHERE COD_EMPRESA = $cod_empresa AND COD_CONTROLE = $cod_controle";
			}

			// fnescreve($sqlPeriodo2);

			$arrayQueryPeriodo2 = mysqli_query(connTemp($cod_empresa, ""), trim($sqlPeriodo2));

			$qrPer = mysqli_fetch_assoc($arrayQueryPeriodo2);

			switch ($qrPer['COD_FREQUENCIA']) {
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

			<div class="push10"></div>

			<div class="row text-center">

				<div class="form-group text-center col-lg-12">

					<h4>Dados da Geração</h4>

				</div>

				<div class="push30"></div>

				<div class="col-md-8 col-md-offset-2">

					<div class="flexrow">

						<div class="col text-center text-info">
							<i class="fal fa-sync fa-3x" aria-hidden="true"></i>
							<div class="push10"></div>
							<b><?= $classifica; ?></b>
							<div class="push10"></div>
							<small style="font-weight:normal;">Periodicidade configurada para atualização <br /><small>(base ref. 01/jan)</small></small>
						</div>

						<div class="col text-center text-info">
							<i class="fal fa-shopping-cart fa-3x" aria-hidden="true"></i>
							<div class="push10"></div>
							<b><?= fnDataShort($qrPer['DAT_INICIO']) ?> a <?= fnDataShort($qrPer['DAT_FIM']); ?></b>
							<div class="push10"></div>
							<small style="font-weight:normal;">Compras Neste Período</small>
						</div>

						<div class="col text-center text-info">
							<i class="fal fa-history fa-3x" aria-hidden="true"></i>
							<div class="push10"></div>
							<b><?= $qrPer['QTD_DIASHIST'] ?></b>
							<div class="push10"></div>
							<small style="font-weight:normal;">Período Histórico de Consulta <br /><small>(dias)</small></small>
						</div>

					</div>

				</div>

			</div>

			<div class="push50"></div>
			<div class="push20"></div>

			<div class="row text-center">

				<div class="col-xs-12 text-center">
					<h4>Frequência de Compra X Valor Gasto</h4>
				</div>
				<div class="push20"></div>



				<div class="form-group text-center col-md-5 col-md-offset-1" style="padding: 0;">

					<!-- <h4>Índice de Venda Vinculada ao Resgate</h4> -->
					<div class="push20"></div>

					<div>
						<canvas id="bar-chart-grouped" style="height: 300px; width:100%;"></canvas>
					</div>

				</div>

				<div class="form-group text-center col-md-5" style="padding: 0; margin-left: -80px;">

					<!-- <h4>Índice de Venda Vinculada ao Resgate</h4> -->
					<div class="push20"></div>

					<div>
						<canvas id="bar-chart-grouped2" style="height: 300px; width:100%;"></canvas>
					</div>

				</div>



			</div>

			<div class="push100"></div>

			<div class="row text-center">

				<div class="col-md-2 col-md-offset-2 text-center">

					<span class="fCor1 f21"><i class='fas fa-male' style='margin: 0 3px 0 0;'></i><?= $qrPer['TXT_CASUAIS'] ?></span>
					<div class="push"></div>
					<span class="text-center f14">De <b><?= $qrPer['FAIXA_MIN_CASUAIS'] ?></b> a <b><?= $qrPer['FAIXA_MAX_CASUAIS'] ?></b> Compras no Período</span>

				</div>

				<div class="col-md-2 text-center">

					<span class="fCor2 f21"><i class='fas fa-male' style='margin: 0 3px 0 0;'></i><?= $qrPer['TXT_FREQUENTES'] ?></span>
					<div class="push"></div>
					<span class="text-center f14">De <b><?= $qrPer['FAIXA_MIN_FREQUENTES'] ?></b> a <b><?= $qrPer['FAIXA_MAX_FREQUENTES'] ?></b> Compras no Período</span>

				</div>

				<div class="col-md-2 text-center">

					<span class="fCor3 f21"><i class='fas fa-male' style='margin: 0 3px 0 0;'></i><?= $qrPer['TXT_FIEIS'] ?></span>
					<div class="push"></div>
					<span class="text-center f14">De <b><?= $qrPer['FAIXA_MIN_FIEIS'] ?></b> a <b><?= $qrPer['FAIXA_MAX_FIEIS'] ?></b> Compras no Período</span>

				</div>

				<div class="col-md-2 text-center">

					<span class="fCor4 f21"><i class='fas fa-male' style='margin: 0 3px 0 0;'></i><?= $qrPer['TXT_FANS'] ?></span>
					<div class="push"></div>
					<span class="text-center f14">Com <b><?= $qrPer['FAIXA_MIN_FANS'] ?></b> ou Mais Compras no Período</span>

				</div>

			</div>

			<div class="push50"></div>

			<table class="table table-striped">
				<thead>
					<tr>
						<th class="{sorter:false}"></th>
						<th class="text-center f16b">QTD. CLIENTES</th>
						<th class="text-center f16b">QTD. COMPRAS</th>
						<th class="text-center f16b">VAL. VENDA LIMPO</th>
						<th class="text-center f16b">VAL. VENDA BRUTO</th>
						<th class="text-center f16b">QTD. PRODUTOS</th>
						<th class="text-center f16b">QTD. RESGATES</th>
						<th class="text-center f16b">VAL. RESGATES</th>
						<th class="text-center f16b">VINC. RESGATE</th>
						<th class="text-center f16b">IDADE MÉDIA</th>
					</tr>
				</thead>
				<tbody>
					<?php

					if ($periodo != 0 && $periodo != '') {
						$andPeriodo = "AND COD_CONTROLE = $periodo";
					} else {
						$andPeriodo = "AND COD_CONTROLE = $cod_controle";
					}

					$sql = "SELECT
												DAT_PERIODO, DES_CATEGOR, SUM(QTD_CLIENTE) QTD_CLIENTE, SUM(QTD_COMPRAS) QTD_COMPRAS,
												SUM(VAL_TOTCOMPRAS) VAL_TOTCOMPRAS, SUM(VAL_TOTPRODU) VAL_TOTPRODU, 
												SUM(VAL_RESGATE) VAL_RESGATES, SUM(QTD_RESGATE) QTD_RESGATES,
												SUM(QTD_PRODUTOS) QTD_PRODUTOS, SUM(VAL_VINCULADO_RESGATE) VAL_VINCULADO_RESGATE,
												avg(IDADE_MEDIA) IDADE_MEDIA
												FROM FECHAMENTO_FREQUENCIA_U
												WHERE COD_EMPRESA=$cod_empresa 
												$andPeriodo 
													  " . ($cod_univend <> 9999 ? " AND COD_UNIVEND IN ($cod_univend) " : "") . "
												GROUP BY COD_CATEGOR ORDER BY COD_CATEGOR DESC";
					$rs = mysqli_query(connTemp($cod_empresa, ""), trim($sql));

					//fnEscreve($sql);

					$count = 0;
					$tot_QTD_CLIENTE = 0;
					$tot_QTD_COMPRAS = 0;
					$tot_VAL_TOTCOMPRAS = 0;
					$tot_VAL_TOTPRODU = 0;
					$tot_QTD_RESGATES = 0;
					$tot_VAL_RESGATES = 0;
					$tot_QTD_PRODUTOS = 0;
					$tot_VAL_VINCULADO_RESGATE = 0;

					while ($qrLista = mysqli_fetch_assoc($rs)) {

						$qtd_cliente[$count] = $qrLista["QTD_CLIENTE"];
						$tot_QTD_CLIENTE = $tot_QTD_CLIENTE + $qrLista["QTD_CLIENTE"];
						$qtd_compras[$count] = $qrLista["QTD_COMPRAS"];
						$tot_QTD_COMPRAS = $tot_QTD_COMPRAS + $qrLista["QTD_COMPRAS"];
						$val_totcompras[$count] = $qrLista["VAL_TOTCOMPRAS"];
						$tot_VAL_TOTCOMPRAS = $tot_VAL_TOTCOMPRAS + $qrLista["VAL_TOTCOMPRAS"];
						$val_totprodu[$count] = $qrLista["VAL_TOTPRODU"];
						$tot_VAL_TOTPRODU = $tot_VAL_TOTPRODU + $qrLista["VAL_TOTPRODU"];
						$tot_VAL_RESGATES = $tot_VAL_RESGATES + $qrLista["VAL_RESGATES"];
						$tot_QTD_RESGATES = $tot_QTD_RESGATES + $qrLista["QTD_RESGATES"];
						$tot_QTD_PRODUTOS = $tot_QTD_PRODUTOS + $qrLista["QTD_PRODUTOS"];
						$tot_VAL_VINCULADO_RESGATE = $tot_VAL_VINCULADO_RESGATE + $qrLista["VAL_VINCULADO_RESGATE"];
						$count++;

					?>
						<tr>
							<td>
								<i class='fas fa-male fCor<?= $count ?>' style='margin: 0 3px 0 0; font-size: 21px;'></i>
								<span class="f21 fCor<?= $count ?>"><?= $qrLista["DES_CATEGOR"] ?> </span>
							</td>

							<td class="text-right text-nowrap">
								<span class="f16"><?= fnValor($qrLista["QTD_CLIENTE"], 0) ?></span>
							</td>

							<td class="text-right text-nowrap">
								<span class="f16"><?= fnValor($qrLista["QTD_COMPRAS"], 0) ?></span>
							</td>

							<td class="text-right text-nowrap">
								<span class="f16">R$ <?= fnValor($qrLista["VAL_TOTCOMPRAS"], 2) ?></span>
							</td>

							<td class="text-right text-nowrap">
								<span class="f16">R$ <?= fnValor($qrLista["VAL_TOTPRODU"], 2) ?></span>
							</td>

							<td class="text-right text-nowrap">
								<span class="f16"><?= fnValor($qrLista["QTD_PRODUTOS"], 0) ?></span>
							</td>

							<td class="text-right text-nowrap">
								<span class="f16"><?= fnValor($qrLista["QTD_RESGATES"], 0) ?></span>
							</td>

							<td class="text-right text-nowrap">
								<span class="f16">R$ <?= fnValor($qrLista["VAL_RESGATES"], 2) ?></span>
							</td>

							<td class="text-right text-nowrap">
								<span class="f16">R$ <?= fnValor($qrLista["VAL_VINCULADO_RESGATE"], 2) ?></span>
							</td>

							<td class="text-right text-nowrap">
								<span class="f16"><?= fnValor($qrLista["IDADE_MEDIA"], 0) ?></span>
							</td>

						</tr>
					<?php
					}

					$tot_casual = $qtd_cliente[0];
					$tot_frequente = $qtd_cliente['1'];
					$tot_fiel = $qtd_cliente['2'];
					$tot_fa = $qtd_cliente['3'];

					/*
									fnEscreve($tot_casual);
									fnEscreve($tot_frequente);
									fnEscreve($tot_fiel);
									fnEscreve($tot_fa);
									*/

					$pct_casual = $tot_QTD_CLIENTE != 0 ? (($tot_casual) / $tot_QTD_CLIENTE) * 100 : 0;
					$pct_frequente = $tot_QTD_CLIENTE != 0 ? (($tot_frequente) / $tot_QTD_CLIENTE) * 100 : 0;
					$pct_fiel = $tot_QTD_CLIENTE != 0 ? (($tot_fiel) / $tot_QTD_CLIENTE) * 100 : 0;
					$pct_fa = $tot_QTD_CLIENTE != 0 ? (($tot_fa) / $tot_QTD_CLIENTE) * 100 : 0;


					// fnEscreve(fnValor($pct_casual,2));
					// fnEscreve(fnValor($pct_frequente,2));
					// fnEscreve(fnValor($pct_fiel,2));
					// fnEscreve(fnValor($pct_fa,2));


					$tot_compra_casual = $qtd_compras[0];
					$tot_compra_frequente = $qtd_compras['1'];
					$tot_compra_fiel = $qtd_compras['2'];
					$tot_compra_fa = $qtd_compras['3'];

					/*
									fnEscreve($tot_compra_casual);
									fnEscreve($tot_compra_frequente);
									fnEscreve($tot_compra_fiel);
									fnEscreve($tot_compra_fa);	
									*/

					$tot_produ_casual = $val_totprodu[0];
					$tot_produ_frequente = $val_totprodu['1'];
					$tot_produ_fiel = $val_totprodu['2'];
					$tot_produ_fa = $val_totprodu['3'];

					/*
									$media_val_casual = $tot_compra_casual != 0 ? (($tot_produ_casual) / $tot_compra_casual) : 0;
									$media_val_frequente = $tot_compra_frequente != 0 ? (($tot_produ_frequente) / $tot_compra_frequente) : 0;
									$media_val_fiel = $tot_compra_fiel != 0 ? (($tot_produ_fiel) / $tot_compra_fiel) : 0;
									$media_val_fa = $tot_compra_fa != 0 ? (($tot_produ_fa) / $tot_compra_fa) : 0;
									*/

					$media_val_casual = $tot_casual != 0 ? (($tot_produ_casual) / $tot_casual) : 0;
					$media_val_frequente = $tot_frequente != 0 ? (($tot_produ_frequente) / $tot_frequente) : 0;
					$media_val_fiel = $tot_fiel != 0 ? (($tot_produ_fiel) / $tot_fiel) : 0;
					$media_val_fa = $tot_fa != 0 ? (($tot_produ_fa) / $tot_fa) : 0;

					// echo("<br/>");
					// fnEscreve(fnValor($media_val_casual,2));
					// fnEscreve(fnValor($media_val_frequente,2));
					// fnEscreve(fnValor($media_val_fiel,2));
					// fnEscreve(fnValor($media_val_fa,2));
					// echo("<br/>");


					$valores = [];
					$porcentagens = [];

					array_push($valores, $media_val_casual);
					array_push($valores, $media_val_frequente);
					array_push($valores, $media_val_fiel);
					array_push($valores, $media_val_fa);

					array_push($porcentagens, $pct_casual);
					array_push($porcentagens, $pct_frequente);
					array_push($porcentagens, $pct_fiel);
					array_push($porcentagens, $pct_fa);

					// print_r($valores);

					$minMed = floor(min($valores));
					$maxMed = ceil(max($valores));


					$minPct = floor(min($porcentagens));
					$maxPct = ceil(max($porcentagens));

					$primeiro_indiceM = 2 != 0 ? ($minMed / 2) : 0;
					$primeiro_indiceP = 2 != 0 ? ($minPct / 2) : 0;
					// fnEscreve($minPct);
					// fnEscreve($maxPct);

					if (($maxPct + $primeiro_indiceP) == 100) {
						$maxPct = 100;
					} else {
						$maxPct += $primeiro_indiceP;
					}
					// fnEscreve($maxPct);


					$ticksMed = range($primeiro_indiceM, ($maxMed + $primeiro_indiceM), $maxMed / 10);
					$ticksPct = range($primeiro_indiceP, ($maxPct + $primeiro_indiceP), $maxPct / 10);

					$ticksExibe = [];
					$ticksExibeP = [];

					// array_push($ticksExibe, 0);
					// array_push($ticksExibe, $primeiro_indiceM);
					// array_push($ticksExibeP, 0);
					// array_push($ticksExibeP, 0);

					foreach ($ticksMed as $tick => $step) {
						array_push($ticksExibe, fnValorSql(fnValor($step, 2)));
					}

					foreach ($ticksPct as $tickPct => $stepPct) {
						array_push($ticksExibeP, fnValorSql(fnValor($stepPct, 2)));
					}



					// echo "<pre>";
					// print_r($ticksExibe);						
					// echo "</pre>";

					// echo "<pre>";
					// print_r($ticksExibeP);						
					// echo "</pre>";

					?>

				<tfoot>
					<td colspan="1"></td>
					<td class="text-right">
						<span class="f18"><small><b><?= fnValor($tot_QTD_CLIENTE, 0) ?></b></small></span>
					</td>
					<td class="text-right">
						<span class="f18"><small><b><?= fnValor($tot_QTD_COMPRAS, 0) ?></b></small></span>
					</td>
					<td class="text-right">
						<span class="f18"><small><b>R$ <?= fnValor($tot_VAL_TOTCOMPRAS, 2) ?></b></small></span>
					</td>
					<td class="text-right">
						<span class="f18"><small><b>R$ <?= fnValor($tot_VAL_TOTPRODU, 2) ?></b></small></span>
					</td>
					<td class="text-right">
						<span class="f18"><small><b><?= fnValor($tot_QTD_PRODUTOS, 0) ?></b></small></span>
					</td>
					<td class="text-right">
						<span class="f18"><small><b><?= fnValor($tot_QTD_RESGATES, 0) ?></b></small></span>
					</td>
					<td class="text-right">
						<span class="f18"><small><b><?= fnValor($tot_VAL_RESGATES, 2) ?></b></small></span>
					</td>
					<td class="text-right">
						<span class="f18"><small><b>R$ <?= fnValor($tot_VAL_VINCULADO_RESGATE, 2) ?></b></small></span>
					</td>
				</tfoot>

				</tbody>
			</table>

			<div class="push30"></div>

		</div>

	</div>
	<!-- fim Portlet -->
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

		//chosen
		$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
		$('#formulario').validator();

		var ticksP = <?= json_encode($ticksExibeP) ?>;

		// alert(ticksP[1]);

		//grouped
		new Chart(document.getElementById("bar-chart-grouped"), {
			type: 'bar',
			data: {
				labels: ["Qtd. Clientes %"],
				datasets: [{
					<?php if ($log_labels == 'S') { ?>
						datalabels: {
							clamp: true,
							align: 'middle',
							anchor: 'middle',
							borderRadius: 4,
							backgroundColor: '#EC7063',
							color: '#fff',
							formatter: function(value) {
								if (parseInt(value) >= 1000) {
									return value + "%";
								} else {
									return value + "%";
								}
								// eq. return ['line1', 'line2', value]
							}
						},
					<?php } ?>
					label: "Casual",
					borderColor: '#EC7063',
					backgroundColor: 'rgba(236,112,99,0.7)',
					borderWidth: 1,
					data: [<?= fnValorSql(fnValor($pct_casual, 2)) ?>]
				}, {
					<?php if ($log_labels == 'S') { ?>
						datalabels: {
							clamp: true,
							align: 'middle',
							anchor: 'middle',
							borderRadius: 4,
							backgroundColor: '#F4D03F',
							color: '#fff',
							formatter: function(value) {
								if (parseInt(value) >= 1000) {
									return value + "%";
								} else {
									return value + "%";
								}
								// eq. return ['line1', 'line2', value]
							}
						},
					<?php } ?>
					label: "Frequente",
					borderColor: '#F4D03F',
					backgroundColor: 'rgba(244,208,63,0.7)',
					borderWidth: 1,
					data: [<?= fnValorSql(fnValor($pct_frequente, 2)) ?>]
				}, {
					<?php if ($log_labels == 'S') { ?>
						datalabels: {
							clamp: true,
							align: 'middle',
							anchor: 'middle',
							borderRadius: 4,
							backgroundColor: '#58D68D',
							color: '#fff',
							formatter: function(value) {
								if (parseInt(value) >= 1000) {
									return value + "%";
								} else {
									return value + "%";
								}
								// eq. return ['line1', 'line2', value]
							}
						},
					<?php } ?>
					label: "Fiel",
					borderColor: '#58D68D',
					backgroundColor: 'rgba(88,214,141,0.7)',
					borderWidth: 1,
					data: [<?= fnValorSql(fnValor($pct_fiel, 2)) ?>]
				}, {
					<?php if ($log_labels == 'S') { ?>
						datalabels: {
							clamp: true,
							align: 'middle',
							anchor: 'middle',
							borderRadius: 4,
							backgroundColor: '#5DADE2',
							color: '#fff',
							formatter: function(value) {
								if (parseInt(value) >= 1000) {
									return value + "%";
								} else {
									return value + "%";
								}
								// eq. return ['line1', 'line2', value]
							}
						},
					<?php } ?>
					label: "Fã",
					borderColor: '#5DADE2',
					backgroundColor: 'rgba(93,173,226,0.7)',
					borderWidth: 1,
					data: [<?= fnValorSql(fnValor($pct_fa, 2)) ?>]
				}]
			},
			<?php if ($log_labels == 'S') { ?>
				plugins: [ChartDataLabels],
			<?php } ?>
			options: {
				legend: {
					position: 'bottom',
				},
				title: {
					display: false,
					text: ''
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
						gridLines: {
							drawBorder: false
						},
						ticks: {
							autoSkip: false,
							min: ticksP[ticksP.length - 1],
							max: ticksP[0],
							callback: function(value, index, values) {
								if (parseInt(value) >= 1000) {
									return value + "%";
								} else {
									return value + "%";
								}
							}
						},
						afterBuildTicks: function(scale) {
							scale.ticks = ticksP;
							return;
						},
						beforeUpdate: function(oScale) {
							return;
						}
					}],
					xAxes: [{
						barPercentage: 0.95
					}]
				},

			}
		});

		var ticks = <?= json_encode($ticksExibe) ?>;

		//grouped
		new Chart(document.getElementById("bar-chart-grouped2"), {
			type: 'bar',
			data: {
				labels: ["Gasto Médio"],
				datasets: [{
					<?php if ($log_labels == 'S') { ?>
						datalabels: {
							clamp: true,
							align: 'middle',
							anchor: 'middle',
							borderRadius: 4,
							backgroundColor: '#EC7063',
							color: '#fff',
							formatter: function(value) {
								if (parseInt(value) >= 1000) {
									return "R$ " + value;
								} else {
									return "R$ " + value;
								}
								// eq. return ['line1', 'line2', value]
							}
						},
					<?php } ?>
					label: "Casual",
					borderColor: '#EC7063',
					backgroundColor: 'rgba(236,112,99,0.7)',
					borderWidth: 1,
					data: [<?= fnValorSql(fnValor($media_val_casual, 2)) ?>]
				}, {
					<?php if ($log_labels == 'S') { ?>
						datalabels: {
							clamp: true,
							align: 'middle',
							anchor: 'middle',
							borderRadius: 4,
							backgroundColor: '#F4D03F',
							color: '#fff',
							formatter: function(value) {
								if (parseInt(value) >= 1000) {
									return "R$ " + value;
								} else {
									return "R$ " + value;
								}
								// eq. return ['line1', 'line2', value]
							}
						},
					<?php } ?>
					label: "Frequente",
					borderColor: '#F4D03F',
					backgroundColor: 'rgba(244,208,63,0.7)',
					borderWidth: 1,
					data: [<?= fnValorSql(fnValor($media_val_frequente, 2)) ?>]
				}, {
					<?php if ($log_labels == 'S') { ?>
						datalabels: {
							clamp: true,
							align: 'middle',
							anchor: 'middle',
							borderRadius: 4,
							backgroundColor: '#58D68D',
							color: '#fff',
							formatter: function(value) {
								if (parseInt(value) >= 1000) {
									return "R$ " + value;
								} else {
									return "R$ " + value;
								}
								// eq. return ['line1', 'line2', value]
							}
						},
					<?php } ?>
					label: "Fiel",
					borderColor: '#58D68D',
					backgroundColor: 'rgba(88,214,141,0.7)',
					borderWidth: 1,
					data: [<?= fnValorSql(fnValor($media_val_fiel, 2)) ?>]
				}, {
					<?php if ($log_labels == 'S') { ?>
						datalabels: {
							clamp: true,
							align: 'middle',
							anchor: 'middle',
							borderRadius: 4,
							backgroundColor: '#5DADE2',
							color: '#fff',
							formatter: function(value) {
								if (parseInt(value) >= 1000) {
									return "R$ " + value;
								} else {
									return "R$ " + value;
								}
								// eq. return ['line1', 'line2', value]
							}
						},
					<?php } ?>
					label: "Fã",
					borderColor: '#5DADE2',
					backgroundColor: 'rgba(93,173,226,0.7)',
					borderWidth: 1,
					data: [<?= fnValorSql(fnValor($media_val_fa, 2)) ?>]
				}]
			},
			<?php if ($log_labels == 'S') { ?>
				plugins: [ChartDataLabels],
			<?php } ?>
			options: {
				legend: {
					position: 'bottom',
				},
				title: {
					display: false,
					text: ''
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
						gridLines: {
							drawBorder: false
						},
						position: 'right',
						ticks: {
							autoSkip: false,
							min: ticks[ticks.length - 1],
							max: ticks[0],
							callback: function(value, index, values) {
								if (parseInt(value) >= 1000) {
									return "R$ " + value;
								} else {
									return "R$ " + value;
								}
							}
						},
						afterBuildTicks: function(scale) {
							scale.ticks = ticks;
							return;
						},
						beforeUpdate: function(oScale) {
							return;
						}
					}],
					xAxes: [{
						barPercentage: 0.95
					}]
				},

			}
		});

	});
</script>