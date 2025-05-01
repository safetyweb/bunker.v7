<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$array = "";
$key = "";
$default = "";
$itens_por_pagina = "";
$pagina = "";
$hashLocal = "";
$hoje = "";
$dias30 = "";
$cod_status = "";
$request = "";
$msgRetorno = "";
$msgTipo = "";
$dat_ini = "";
$dat_fim = "";
$opcao = "";
$hHabilitado = "";
$hashForm = "";
$arrayQuery = "";
$qrBuscaEmpresa = "";
$nom_empresa = "";
$tip_retorno = "";
$casasDec = "";
$arrayParamAutorizacao = "";
$autoriza = "";
$cod_cliente = "";
$lojasSelecionadas = "";
$formBack = "";
$log_resgate = "";
$andResgate = "";
$query = "";
$qrBusca = "";
$retorno = "";
$total_itens_por_pagina = "";
$inicio = "";
$countLinha = "";
$qrListaVendas = "";
$content = "";
$cod_controle = "";

function getInput($array, $key, $default = '')
{
	return isset($array[$key]) ? $array[$key] : $default;
}




// definir o numero de itens por pagina
$itens_por_pagina = 50;
$pagina  = "1";

$hashLocal = mt_rand();

//inicialização de variáveis
//$hoje = fnFormatDate(date("Y-m-d"));
//$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 60 days')));
$hoje = fnFormatDate(date('Y-m-d', strtotime($hoje . '- 1 days')));
$dias30 = fnFormatDate(date('Y-m-01'));
$cod_status = 11;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_empresa = fnLimpaCampoZero(getInput($_POST, 'COD_EMPRESA'));
		$cod_univend = getInput($_REQUEST, 'COD_UNIVEND');
		$cod_grupotr = getInput($_REQUEST, 'COD_GRUPOTR');
		$cod_tiporeg = getInput($_REQUEST, 'COD_TIPOREG');
		$cod_status = getInput($_POST, 'COD_STATUS');
		$dat_ini = fnDataSql(getInput($_POST, 'DAT_INI'));
		$dat_fim = fnDataSql(getInput($_POST, 'DAT_FIM'));

		$opcao = getInput($_REQUEST, 'opcao');
		$hHabilitado = getInput($_REQUEST, 'hHabilitado');
		$hashForm = getInput($_REQUEST, 'hashForm');

		if ($opcao != '') {
		}
	}
}

//busca dados url
if (is_numeric(fnLimpacampo(fnDecode(getInput($_GET, 'id'))))) {
	//busca dados da empresa
	$cod_empresa = fnDecode(getInput($_GET, 'id'));
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI, TIP_RETORNO FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
		$tip_retorno = $qrBuscaEmpresa['TIP_RETORNO'];

		if ($tip_retorno == 1) {
			$casasDec = 0;
		} else {
			$casasDec = 2;
		}
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

// Filtro por Grupo de Lojas
include "filtroGrupoLojas.php";

//rotina de controle de acessos por módulo
include "moduloControlaAcesso.php";

if (fnControlaAcesso("1081", $arrayParamAutorizacao) === true) {
	$autoriza = 1;
} else {
	$autoriza = 0;
}

//fnMostraForm();
//fnEscreve($cod_cliente);
//fnEscreve($dat_ini);
//fnEscreve($dat_fim);
//fnEscreve($lojasSelecionadas);
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
</style>

<div class="push30"></div>

<div class="row">

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fal fa-terminal"></i>
					<span class="text-primary"> <?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
				</div>

				<?php
				$formBack = "1015";
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

								<div class="col-md-4">
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
										<label for="inputName" class="control-label required">Data Inicial</label>

										<div class="input-group date datePicker" id="DAT_INI_GRP">
											<input type='text' class="form-control input-sm data" name="DAT_INI" id="DAT_INI" value="" required />
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
											<input type='text' class="form-control input-sm data" name="DAT_FIM" id="DAT_FIM" value="<?php echo fnFormatDate($dat_fim); ?>" required />
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
										</div>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<!--<div class="col-md-1">
									<div class="form-group">
										<label for="inputName" class="control-label">Créditos Resgatados</label>
										<div class="push5"></div>
										<label class="switch">
											<input type="checkbox" name="LOG_RESGATE" id="LOG_RESGATE" class="switch switch-small" value="S" <?= (@getInput($_POST, 'LOG_RESGATE') == "S" ? "checked" : "") ?>>
											<span></span>
										</label>
									</div>
								</div>-->

								<div class="col-md-2">
									<div class="push20"></div>
									<button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-sm btn-block getBtn"><i class="fal fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
								</div>
							</div>
						</fieldset>


						<div class="push20"></div>

						<input type="hidden" name="LOJAS" id="LOJAS" value="<?php echo $lojasSelecionadas; ?>" />
						<input type="hidden" name="AUTORIZA" id="AUTORIZA" value="<?= $autoriza ?>" />
						<input type="hidden" name="CASAS_DEC" id="CASAS_DEC" value="<?= $casasDec ?>">
						<input type="hidden" name="NOVO_CLIENTE" id="NOVO_CLIENTE" value="">
						<!--<input type="hidden" name="LOG_RESGATE" id="LOG_RESGATE" value="">-->
						<input type="hidden" name="REFRESH_CLIENTE" id="REFRESH_CLIENTE" value="N">
						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
						<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
						<div class="push5"></div>

					</form>
				</div>
			</div>
		</div>

		<div class="push30"></div>

		<div class="portlet portlet-bordered">

			<div class="portlet-body">

				<div class="row text-center">

					<?php
					//ajuste solicitado pelo josé, deixar o val resgatado fixo
					/*if ($log_resgate == "S"){
						$andResgate = "WHERE VAL_RESGATADO > 0.1 AND DATA_RESGATE BETWEEN '$dat_ini' AND '$dat_fim'";
					}else {
						$andResgate = " ";
					}*/


					$sql = "SELECT 
					COUNT(DISTINCT COD_CLIENTE) AS QTD_CLIENTES,
					SUM(VAL_CREDITO) AS TOT_CREDCAMPANHA,
					SUM(VAL_RESGATADO) AS TOT_RESGATADO,
					SUM(VVR) AS Total_VVR,
					SUM(CREDITO_DISPONIVEL_GERAL) AS TOT_CREDITO_DISPONIVEL_GERAL
					FROM 
					(SELECT 
						A.COD_CREDITO, 
						B.COD_CLIENTE,  
						A.DAT_CADASTR, 
						ROUND(A.VAL_CREDITO,2) VAL_CREDITO, 
						A.VAL_CREDITO-A.VAL_SALDO VAL_RESGATADO, 
						IFNULL((SELECT MAX(dat_cadastr_ws) FROM vendas WHERE cod_venda IN (SELECT cod_venda_com_resgate FROM historico_resgate WHERE cod_credito=A.COD_CREDITO)),0) AS DATA_RESGATE, 
						IFNULL(ROUND((SELECT SUM(VAL_TOTPRODU) FROM vendas WHERE vendas.cod_statuscred IN(0,1,2,3,4,5,7,8,9) AND cod_venda IN (SELECT cod_venda_com_resgate FROM historico_resgate WHERE cod_credito=A.COD_CREDITO)),2),0) AS VVR, 
						des_operaca,
						(SELECT ifnull(SUM(AA.VAL_SALDO),0) 
							FROM CREDITOSDEBITOS AA, empresas c 
							WHERE AA.COD_CLIENTE=A.COD_CLIENTE 
							AND C.COD_EMPRESA=AA.COD_EMPRESA 
							AND AA.TIP_CREDITO='C' 
							AND AA.COD_STATUSCRED=1 
							AND AA.tip_campanha = c.TIP_CAMPANHA 
							AND (DATE_FORMAT(AA.DAT_EXPIRA, '%Y-%m-%d') >= DATE_FORMAT(NOW(),'%Y-%m-%d') or(AA.LOG_EXPIRA='N'))) AS CREDITO_DISPONIVEL_GERAL
						FROM 
						creditosdebitos a, clientes b 
						WHERE 
						a.cod_empresa=$cod_empresa 
						AND a.COD_UNIVEND IN ($lojasSelecionadas)
						AND a.cod_cliente=b.COD_CLIENTE 
						AND a.cod_credlot != 0 
						-- AND DATE(A.DAT_CADASTR) >= '$dat_ini' 
						-- AND DATE(A.DAT_CADASTR) <= '$dat_fim'
						AND EXTRACT(YEAR_MONTH FROM DATE(A.DAT_CADASTR))between EXTRACT(YEAR_MONTH FROM  '$dat_ini') AND EXTRACT(YEAR_MONTH FROM'$dat_fim')
						) AS tmpcred
						WHERE VAL_RESGATADO > 0.1 AND DATA_RESGATE BETWEEN '$dat_ini' AND '$dat_fim'";

					//fnEscreve($sql);

					$query = mysqli_query(connTemp($cod_empresa, ''), $sql);

					$qrBusca = mysqli_fetch_assoc($query);

					?>
					<div class="form-group text-center col-md-1 col-lg-1"></div>

					<div class="form-group text-center col-md-2 col-lg-2">

						<div class="push20"></div>

						<div class="form-group">
							<input type="text" class="form-control input-sm leitura text-center" readonly="readonly" name="QTD_CLIENTES" id="QTD_CLIENTES" maxlength="100" value="<?= fnValor($qrBusca['QTD_CLIENTES'], 0) ?>">
							<label for="inputName" class="control-label"><b>Qtd. Clientes</b></label>
							<div class="help-block with-errors"></div>
						</div>


						<div class="push20"></div>

					</div>

					<div class="form-group text-center col-md-2 col-lg-2">

						<div class="push20"></div>

						<div class="form-group">
							<input type="text" class="form-control input-sm leitura text-center" readonly="readonly" name="TOT_BONUS_CONCEDIDO" id="TOT_BONUS_CONCEDIDO" maxlength="100" value="R$ <?= fnValor($qrBusca['TOT_CREDCAMPANHA'], 2) ?>">
							<label for="inputName" class="control-label"><b>Tot. Bônus Concedido</b></label>
							<div class="help-block with-errors"></div>
						</div>

						<div class="push20"></div>

					</div>

					<div class="form-group text-center col-md-2 col-lg-2">

						<div class="push20"></div>

						<div class="form-group">
							<input type="text" class="form-control input-sm leitura text-center" readonly="readonly" name="TOT_RESGATADO" id="TOT_RESGATADO" maxlength="100" value="R$ <?= fnValor($qrBusca['TOT_RESGATADO'], 2) ?>">
							<label for="inputName" class="control-label"><b>Tot. Bônus Resgatado</b></label>
							<div class="help-block with-errors"></div>
						</div>

						<div class="push20"></div>

					</div>

					<div class="form-group text-center col-md-2 col-lg-2">

						<div class="push20"></div>

						<div class="form-group">
							<input type="text" class="form-control input-sm leitura text-center" readonly="readonly" name="TOT_BONUS_SEM_RESGATE" id="TOT_BONUS_SEM_RESGATE" maxlength="100" value="R$ <?= fnValor($qrBusca['TOT_CREDCAMPANHA'] - $qrBusca['TOT_RESGATADO'], 2) ?>">
							<label for="inputName" class="control-label"><b>Tot. Bônus Sem Resgate</b></label>
							<div class="help-block with-errors"></div>
						</div>

						<div class="push20"></div>

					</div>


					<div class="form-group text-center col-md-2 col-lg-2">

						<div class="push20"></div>

						<div class="form-group">
							<input type="text" class="form-control input-sm leitura text-center" readonly="readonly" name="TOT_VVR" id="TOT_VVR" maxlength="100" value="R$ <?= fnValor($qrBusca['Total_VVR'], 2) ?>">
							<label for="inputName" class="control-label"><b>Tot. Vendas Vínculadas</b></label>
							<div class="help-block with-errors"></div>
						</div>

						<div class="push20"></div>

					</div>

				</div>

			</div>

		</div>

		<div class="push30"></div>

		<div class="portlet portlet-bordered">
			<div class="portlet-body">
				<div class="login-form">
					<div class="row">
						<div class="col-md-12" id="div_Produtos">
							<table class="table table-bordered table-hover tablesorter">

								<thead>
									<tr>
										<th><small>Cód. Credito</small></th>
										<th><small>Cód. Cliente</small></th>
										<th><small>Nome Cliente</small></th>
										<th><small>Data do crédito</small></th>
										<th><small>Valor do crédito</small></th>
										<th><small>Valor Resgatado</small></th>
										<th><small>Data do Resgate</small></th>
										<th class="text-center"><small>Venda Vínculada</small></th>
									</tr>
								</thead>

								<tbody id="relatorioConteudo">

									<?php

									$sql = "SELECT * FROM (SELECT 
										A.COD_CREDITO,
										B.COD_CLIENTE,
										B.NOM_CLIENTE,
										A.DAT_CADASTR,
										ROUND(A.VAL_CREDITO,2) VAL_CREDITO,
										A.VAL_CREDITO-A.VAL_SALDO VAL_RESGATADO,
										IFNULL((SELECT max(dat_cadastr_ws) FROM vendas
											WHERE cod_venda in(SELECT cod_venda_com_resgate  FROM historico_resgate
												WHERE cod_credito=A.COD_CREDITO)),0) AS DATA_RESGATE,
										IFNULL(ROUND((SELECT SUM(VAL_TOTPRODU) FROM vendas
											WHERE cod_venda in(SELECT cod_venda_com_resgate  FROM historico_resgate
												WHERE cod_credito=A.COD_CREDITO)),2),0) AS VVR,
										des_operaca
										FROM creditosdebitos a, clientes b
										WHERE a.cod_empresa=$cod_empresa 
										AND a.COD_UNIVEND IN($lojasSelecionadas)
										AND a.cod_cliente=b.COD_CLIENTE 
										AND a.cod_credlot != 0
										-- AND DATE(A.DAT_CADASTR) >= '$dat_ini' AND DATE(A.DAT_CADASTR) <= '$dat_fim'
										AND EXTRACT(YEAR_MONTH FROM DATE(A.DAT_CADASTR))between EXTRACT(YEAR_MONTH FROM  '$dat_ini') AND EXTRACT(YEAR_MONTH FROM'$dat_fim')
										)tmpcred
									WHERE VAL_RESGATADO > 0.1 AND DATA_RESGATE BETWEEN '$dat_ini' AND '$dat_fim'
									";

									// fnEscreve($sql);

									$retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
									$total_itens_por_pagina = mysqli_num_rows($retorno);

									$numPaginas = ceil($total_itens_por_pagina / $itens_por_pagina);

									//variavel para calcular o início da visualização com base na página atual
									$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

									$sql = "SELECT * FROM (SELECT 
										A.COD_CREDITO,
										B.COD_CLIENTE,
										B.NOM_CLIENTE,
										A.DAT_CADASTR,
										ROUND(A.VAL_CREDITO,2) VAL_CREDITO,
										A.VAL_CREDITO-A.VAL_SALDO VAL_RESGATADO,
										date(IFNULL((SELECT max(dat_cadastr_ws) FROM vendas
											WHERE cod_venda in(SELECT cod_venda_com_resgate  FROM historico_resgate
												WHERE cod_credito=A.COD_CREDITO)),0)) AS DATA_RESGATE,
										IFNULL(ROUND((SELECT SUM(VAL_TOTPRODU) FROM vendas
											WHERE cod_venda in(SELECT cod_venda_com_resgate  FROM historico_resgate
												WHERE cod_credito=A.COD_CREDITO)),2),0) AS VVR,
										des_operaca
										FROM creditosdebitos A, clientes B
										WHERE A.cod_empresa=$cod_empresa 
										AND A.COD_UNIVEND IN($lojasSelecionadas) 
										AND A.cod_cliente=B.COD_CLIENTE 
										AND A.cod_credlot != 0
										AND EXTRACT(YEAR_MONTH FROM DATE(A.DAT_CADASTR))between EXTRACT(YEAR_MONTH FROM  '$dat_ini') AND EXTRACT(YEAR_MONTH FROM'$dat_fim')
										)tmpcred
									WHERE VAL_RESGATADO > 0.1 AND DATA_RESGATE BETWEEN '$dat_ini' AND '$dat_fim'
									LIMIT $inicio, $itens_por_pagina";

									//fnEscreve($sql);
									$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

									if (mysqli_num_rows($arrayQuery) != 0) {

										// fnEscreve("if");
										$countLinha = 1;
										while ($qrListaVendas = mysqli_fetch_assoc($arrayQuery)) {

									?>
											<tr>
												<td><small><?php echo $qrListaVendas['COD_CREDITO']; ?></small></td>
												<?php
												if ($autoriza == 1) {
												?>
													<td><a href="action.do?mod=<?php echo fnEncode(1081); ?>&id=<?php echo fnEncode($cod_empresa); ?>&idC=<?php echo fnEncode($qrListaVendas['COD_CLIENTE']); ?>" class="f14" target="_blank"><?php echo $qrListaVendas['COD_CLIENTE']; ?></a></td>
												<?php
												} else {
												?>
													<td><?php echo $qrListaVendas['COD_CLIENTE']; ?></td>
												<?php
												}
												?>
												<td><small><?php echo $qrListaVendas['NOM_CLIENTE']; ?></small></td>
												<td><small><?php echo fnDataShort($qrListaVendas['DAT_CADASTR']); ?></small></td>
												<td class="text-right"><small><?php echo fnValor($qrListaVendas['VAL_CREDITO'], $casasDec); ?></small></td>
												<td class="text-right"><small><?php echo fnValor(($qrListaVendas['VAL_RESGATADO'] * -1), $casasDec); ?></small></td>
												<td><small><?php echo fnDataShort($qrListaVendas['DATA_RESGATE']); ?></small></td>
												<td class="text-right"><small><?php echo fnValor($qrListaVendas['VVR'], $casasDec); ?></small></td>

												<!-- <td class="text-center"><small><?php echo fnValor($qrListaVendas['CREDITO_GERADO'], $casasDec); ?></small></td>
																<td class="text-center"><small><?php echo fnValor($qrListaVendas['CREDITO_EXPIRAR'], $casasDec); ?></small></td>
																<td class="text-center"><small><?php echo fnDataShort($qrListaVendas['DAT_EXPIRA']); ?></small></td>
																<td class="text-center"><small><?php echo fnDataShort($qrListaVendas['DAT_ULTCOMPR']); ?></small></td>
																<td class="text-center"><small><?php echo fnValor($qrListaVendas['VAL_TOTAL_SALDO'], $casasDec); ?></small></td> -->
											</tr>
										<?php
										}

										?>

								</tbody>
								<tfoot>
									<tr>
										<th colspan="100">
											<a class="btn btn-info btn-sm exportarCSV"><i class="fa fa-file-excel" aria-hidden="true"></i> &nbsp; Exportar </a>
										</th>
									</tr>
									<tr>
										<th class="" colspan="100">
											<center>
												<ul id="paginacao" class="pagination-sm"></ul>
											</center>
										</th>
									</tr>
								</tfoot>
							<?php
									}
							?>

							</table>

						</div>

					</div>



					<div class="push50"></div>

					<div class="push"></div>

				</div>

			</div>
		</div>
		<!-- fim Portlet -->
	</div>

</div>

<!-- modal -->
<div class="modal fade" id="popModal" tabindex='-1'>
	<div class="modal-dialog" style="">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"></h4>
			</div>
			<div class="modal-body">
				<iframe frameborder="0" style="width: 100%; height: 80%"></iframe>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="push20"></div>


<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />

<script>
	//datas
	$(function() {

		var numPaginas = <?php echo $numPaginas; ?>;
		if (numPaginas != 0) {
			carregarPaginacao(numPaginas);
		}

		$('.datePicker').datetimepicker({
			format: 'DD/MM/YYYY'
		}).on('changeDate', function(e) {
			$(this).datetimepicker('hide');
		});

		$("#DAT_INI_GRP").on("dp.change", function(e) {
			$('#DAT_FIM_GRP').data("DateTimePicker").minDate(e.date);
		});

		$("#DAT_FIM_GRP").on("dp.change", function(e) {
			$('#DAT_INI_GRP').data("DateTimePicker").maxDate(e.date);
		});

		$("#DAT_INI").val("<?= fnDataShort($dat_ini) ?>");

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
								icon: 'fa fa-check-square',
								content: function() {
									var self = this;
									return $.ajax({
										url: "relatorios/ajxCreditoResgatadosAniversario.do?opcao=exportar&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>&itens_por_pagina=<?php echo $itens_por_pagina; ?>&idc=<?= fnEncode($cod_controle) ?>",
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

	function reloadPage(idPage) {
		$.ajax({
			type: "POST",
			url: "relatorios/ajxCreditoResgatadosAniversario.do?opcao=paginar&id=<?php echo fnEncode($cod_empresa); ?>&idc=<?= fnEncode($cod_controle) ?>&itens_por_pagina=<?php echo $itens_por_pagina; ?>&lojas=<?php echo $lojasSelecionadas ?>&idPage=" + idPage,
			data: $('#formulario').serialize(),
			beforeSend: function() {
				$('#relatorioConteudo').html('<tr><td colspan="100"><div class="loading" style="width: 100%;"></div></tr></td>');
			},
			success: function(data) {
				$("#relatorioConteudo").html(data);
			},
			error: function() {
				$('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
			}
		});
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