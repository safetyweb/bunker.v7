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
$hashLocal = "";
$itens_por_pagina = "";
$pagina = "";
$hoje = "";
$dias30 = "";
$cod_status = "";
$request = "";
$msgRetorno = "";
$msgTipo = "";
$dat_ini = "";
$dat_fim = "";
$num_cartao = "";
$opcao = "";
$hHabilitado = "";
$hashForm = "";
$sql = "";
$arrayQuery = "";
$qrBuscaEmpresa = "";
$nom_empresa = "";
$tip_retorno = "";
$casasDec = "";
$cod_cliente = "";
$arrayParamAutorizacao = "";
$autoriza = "";
$formBack = "";
$qrListaStatus = "";
$andCodStatus = "";
$andCartao = "";
$lojasSelecionadas = "";
$qrResult = "";
$qtd_univ = "";
$tot_unicos = "";
$tot_credito = "";
$tot_itens = "";
$qtd_transacao = "";
$retorno = "";
$total_itens_por_pagina = "";
$inicio = "";
$countLinha = "";
$qrListaVendas = "";
$vendedor = "";
$content = "";

function getInput($array, $key, $default = '')
{
	return isset($array[$key]) ? $array[$key] : $default;
}


$hashLocal = mt_rand();

$conn = conntemp($cod_empresa, "");
$adm = $connAdm->connAdm();

// definir o numero de itens por pagina
$itens_por_pagina = 50;

// Página default
$pagina = 1;

//inicialização de variáveis
//$hoje = fnFormatDate(date("Y-m-d"));
$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30 . '- 1 days')));
$hoje = fnFormatDate(date('Y-m-d', strtotime($hoje . '- 1 days')));
//$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 2 days')));
$cod_univend = "9999";
$cod_status = 9999;


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_empresa = fnLimpaCampoZero(getInput($_POST, 'COD_EMPRESA'));
		$cod_univend = getInput($_POST, 'COD_UNIVEND');
		$cod_status = getInput($_POST, 'COD_STATUS');
		$dat_ini = fnDataSql(getInput($_POST, 'DAT_INI'));
		$dat_fim = fnDataSql(getInput($_POST, 'DAT_FIM'));
		$num_cartao = fnLimpacampo(getInput($_POST, 'NUM_CARTAO'));

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		if ($opcao != '') {
		}
	}
}

//busca dados url
if (is_numeric(fnLimpacampo(fnDecode(getInput($_GET, 'id'))))) {
	//busca dados da empresa
	$cod_empresa = fnDecode(getInput($_GET, 'id'));
	$sql = "SELECT COD_EMPRESA, NOM_EMPRESA, TIP_RETORNO FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($adm, $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
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

if (is_string($cod_univend) && strlen($cod_univend) == 0) {
	$cod_univend = "9999";
} elseif (is_array($cod_univend) && empty($cod_univend)) {
	$cod_univend = "9999";
}

//busca revendas do usuário
include "unidadesAutorizadas.php";

//fnMostraForm();
//fnEscreve($cod_cliente);

//rotina de controle de acessos por módulo
include "moduloControlaAcesso.php";

if (fnControlaAcesso("1024", $arrayParamAutorizacao) === true) {
	$autoriza = 1;
} else {
	$autoriza = 0;
}


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

					<div class="push30"></div>

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

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Cartão</label>
										<input type="text" class="form-control input-sm" name="NUM_CARTAO" id="NUM_CARTAO" value="">
										<div class="help-block with-errors"></div>
									</div>
								</div>

							</div>

							<div class="push10"></div>

							<div class="row">

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Data Inicial</label>

										<div class="input-group date datePicker" id="DAT_INI_GRP">
											<input type='text' class="form-control input-sm data" name="DAT_INI" id="DAT_INI" value="<?php echo fnFormatDate($dat_ini); ?>" required />
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

								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label required">Tipo do Crédito Extra</label>
										<select data-placeholder="Selecione um tipo de crédito" name="COD_STATUS" id="COD_STATUS" class="chosen-select-deselect">
											<option value="">&nbsp;</option>
											<option value="9999">Todos</option>
											<?php
											$sql = "SELECT COD_STATUS,DES_STATUS FROM STATUSMARKA  where cod_status in (6,7,8,9,10,11,12,18) ";
											$arrayQuery = mysqli_query($conn, trim($sql));

											while ($qrListaStatus = mysqli_fetch_assoc($arrayQuery)) {
												echo "
												<option value='" . $qrListaStatus['COD_STATUS'] . "'>" . $qrListaStatus['DES_STATUS'] . "</option> 
												";
											}
											?>
										</select>
										<script>
											$("#formulario #COD_STATUS").val("<?php echo $cod_status; ?>").trigger("chosen:updated");
										</script>

									</div>
								</div>

								<div class="col-md-2">
									<div class="push20"></div>
									<button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-sm btn-block getBtn"><i class="fal fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
								</div>


							</div>

						</fieldset>
					</div>
				</div>
			</div>

			<div class="push30"></div>

			<?php

			if ($cod_status == 9999) {
				$andCodStatus = "AND A.COD_STATUS IN(1,2,3,4,5,7,8,9,10,11,12)";
				//fnEscreve("if");
			} else {
				$andCodStatus = "AND A.COD_STATUS = $cod_status ";
				//fnEscreve("else");
			}
			if ($num_cartao != "" && $num_cartao != 0) {
				$andCartao = "AND C.NUM_CARTAO =$num_cartao";
			} else {
				$andCartao = "";
			}

			// Filtro por Grupo de Lojas
			include "filtroGrupoLojas.php";

			$sql = "SELECT 
			    COUNT(V.COD_ITEMVEN) AS QTD_TRANSACAO,
			    COUNT(DISTINCT A.COD_UNIVEND) AS QTD_UNIV,
			    COUNT(DISTINCT A.COD_CLIENTE) AS TOT_UNICOS,
			    SUM(A.VAL_CREDITO) AS TOT_CREDITO,
			    (
			        SELECT SUM(V.VAL_TOTITEM) 
			        FROM ITEMVENDA V 
			        WHERE V.COD_CLIENTE = A.COD_CLIENTE
			          AND V.COD_VENDA = A.COD_VENDA
			    ) AS TOT_ITENS
				FROM CREDITOSDEBITOS A 
				INNER  JOIN CLIENTES C ON C.COD_CLIENTE=A.COD_CLIENTE 
				INNER  JOIN STATUSMARKA D ON D.COD_STATUS=A.COD_STATUS 
				INNER  JOIN STATUSCREDITO F ON F.COD_STATUSCRED=A.COD_STATUSCRED 
				INNER  JOIN unidadevenda uni ON uni.COD_UNIVEND=A.COD_UNIVEND 
				INNER  JOIN USUARIOS US ON US.COD_USUARIO=A.COD_VENDEDOR 
				left JOIN ITEMVENDA V ON V.COD_CLIENTE=A.COD_CLIENTE AND V.COD_VENDA=A.COD_VENDA
				WHERE A.DAT_CADASTR >= '$dat_ini 00:00:00' AND A.DAT_CADASTR <= '$dat_fim 23:59:59' AND A.COD_EMPRESA = $cod_empresa
				$andCodStatus
				$andCartao
				AND A.TIP_CREDITO='C' AND 
				A.COD_UNIVEND IN ($lojasSelecionadas)";

			$arrayQuery = mysqli_query($conn, $sql);

			if ($qrResult = mysqli_fetch_assoc($arrayQuery)) {
				$qtd_univ = $qrResult['QTD_UNIV'];
				$tot_unicos = $qrResult['TOT_UNICOS'];
				$tot_credito = $qrResult['TOT_CREDITO'];
				$tot_itens = $qrResult['TOT_ITENS'];
				$qtd_transacao = $qrResult['QTD_TRANSACAO'];
			}


			?>

			<!-- Portlet -->
			<div class="portlet portlet-bordered">

				<div class="portlet-body">

					<div class="row text-center">
						<div class="col-md1 col-lg-1"></div>
						<div class="form-group text-center col-md-2 col-lg-2">

							<div class="push20"></div>

							<p><span id="QTD_SALDO_EMAIL"><?= fnValor($qtd_univ, 0) ?></span></p>
							<p><b>Total Lojas</b></p>

							<div class="push20"></div>

						</div>

						<div class="form-group text-center col-md-2 col-lg-2">

							<div class="push20"></div>

							<p><span id="QTD_SALDO_SMS"><?= fnValor($tot_unicos, 0) ?></span></p>
							<p><b>Clientes Únicos</b></p>

							<div class="push20"></div>

						</div>

						<div class="form-group text-center col-md-2 col-lg-2">

							<div class="push20"></div>

							<p><span id="QTD_SALDO_WPP">R$ <?= fnValor($tot_credito, 2) ?></span></p>
							<p><b>Total Créditos Extra</b></p>

							<div class="push20"></div>

						</div>

						<div class="form-group text-center col-md-2 col-lg-2">

							<div class="push20"></div>

							<p><span id="QTD_SALDO_WPP">R$ <?= fnValor($tot_itens, 2) ?></span></p>
							<p><b>Total Vendas</b></p>

							<div class="push20"></div>

						</div>

						<div class="form-group text-center col-md-2 col-lg-2">

							<div class="push20"></div>

							<p><span id="QTD_SALDO_WPP"><?= fnValor($qtd_transacao, 0) ?></span></p>
							<p><b>Total de Itens</b></p>

							<div class="push20"></div>

						</div>

					</div>


				</div>

			</div>

			<div class="push30"></div>

			<div class="portlet portlet-bordered">

				<div class="portlet-body">

					<div class="login-form">

						<div class="push20"></div>

						<div class="row">

							<div class="col-md-12" id="div_Produtos">

								<div class="push20"></div>

								<table class="table table-bordered table-hover  ">

									<thead>
										<tr>
											<th>Loja</small></th>
											<th><small>Cód. Campanha</small></th>
											<th><small>Cliente</small></th>
											<!-- <th><small>Cartão</small></th> -->
											<th><small>Dt. Crédito</small></th>
											<th><small>Créditos/Pontos</small></th>
											<th><small>Vl.Total</small></th>
											<th><small>Status</small></th>
											<th><small>Operação</small></th>
											<th><small>Expira em</small></th>
										</tr>
									</thead>

									<tbody id="relatorioConteudo">

										<?php

										$sql = "SELECT 
												1
										FROM CREDITOSDEBITOS A 
										INNER  JOIN CLIENTES C ON C.COD_CLIENTE=A.COD_CLIENTE 
										INNER  JOIN STATUSMARKA D ON D.COD_STATUS=A.COD_STATUS 
										INNER  JOIN STATUSCREDITO F ON F.COD_STATUSCRED=A.COD_STATUSCRED 
										INNER  JOIN unidadevenda uni ON uni.COD_UNIVEND=A.COD_UNIVEND 
										INNER  JOIN USUARIOS US ON US.COD_USUARIO=A.COD_VENDEDOR 
										WHERE A.DAT_CADASTR >= '$dat_ini 00:00:00' AND A.DAT_CADASTR <= '$dat_fim 23:59:59' AND A.COD_EMPRESA = $cod_empresa
										$andCodStatus
										$andCartao
										AND A.TIP_CREDITO='C' AND 
										A.COD_UNIVEND IN ($lojasSelecionadas)";

										//fnEscreve($sql);

										$retorno = mysqli_query($conn, $sql);
										$total_itens_por_pagina = mysqli_num_rows($retorno);

										$numPaginas = ceil($total_itens_por_pagina / $itens_por_pagina);

										//variavel para calcular o início da visualização com base na página atual
										$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

										//fnEscreve($numPaginas);


										// Filtro por Grupo de Lojas
										include "filtroGrupoLojas.php";

										$sql = "SELECT 
										A.COD_ITEMVEN,
										A.COD_CREDITO, 
										A.COD_VENDA,
										A.COD_CLIENTE, 
										A.COD_UNIVEND, 
										uni.NOM_FANTASI, 
										US.NOM_USUARIO, 
										A.COD_VENDEDOR, 
										C.NOM_CLIENTE, 
										C.NUM_CARTAO, 
										A.VAL_CREDITO, 
										CASE WHEN A.COD_ITEMVEN > 0 AND A.TIP_PONTUACAO='ABP' THEN 
										(SELECT B.VAL_TOTITEM FROM itemvenda B WHERE B.COD_VENDA=A.COD_VENDA AND B.COD_CLIENTE=A.COD_CLIENTE AND B.COD_PRODUTO=A.COD_ITEMVEN)
											WHEN A.COD_ITEMVEN > 0 AND A.TIP_PONTUACAO!='ABP' THEN
										(SELECT B.VAL_TOTITEM FROM itemvenda B WHERE B.COD_VENDA=A.COD_VENDA AND B.COD_CLIENTE=A.COD_CLIENTE AND B.COD_ITEMVEN=A.COD_ITEMVEN)     
										ELSE  
										0 
										END AS VAL_TOTITEM,
										D.DES_STATUS, 
										A.DES_OPERACA, 
										A.DAT_CADASTR, 
										A.DAT_EXPIRA, 
										A.COD_CAMPANHA, 
										F.DES_STATUSCRED
										FROM CREDITOSDEBITOS A 
										INNER  JOIN CLIENTES C ON C.COD_CLIENTE=A.COD_CLIENTE 
										INNER  JOIN STATUSMARKA D ON D.COD_STATUS=A.COD_STATUS 
										INNER  JOIN STATUSCREDITO F ON F.COD_STATUSCRED=A.COD_STATUSCRED 
										INNER  JOIN unidadevenda uni ON uni.COD_UNIVEND=A.COD_UNIVEND 
										INNER  JOIN USUARIOS US ON US.COD_USUARIO=A.COD_VENDEDOR 
										WHERE A.DAT_CADASTR >= '$dat_ini 00:00:00' AND A.DAT_CADASTR <= '$dat_fim 23:59:59' AND A.COD_EMPRESA = $cod_empresa
										$andCodStatus
										$andCartao
										AND A.TIP_CREDITO='C' AND 
										A.COD_UNIVEND IN ($lojasSelecionadas)
										LIMIT $inicio, $itens_por_pagina";

										//fnEscreve($sql);
										$arrayQuery = mysqli_query($conn, $sql);

										if (mysqli_num_rows($arrayQuery) != 0) {

											$countLinha = 1;
											while ($qrListaVendas = mysqli_fetch_assoc($arrayQuery)) {

												if ($qrListaVendas['NOM_USUARIO'] == 0) {
													$vendedor = "";
												}


										?>
												<tr>
													<td><b><?= $qrListaVendas['NOM_FANTASI'] ?></b></td>
													<td><?= $qrListaVendas['COD_CAMPANHA'] ?></td>
													<?php
													if ($autoriza == 1) {
													?>
														<td><a href="action.do?mod=<?php echo fnEncode(1024); ?>&id=<?php echo fnEncode($cod_empresa); ?>&idC=<?php echo fnEncode($qrListaVendas['COD_CLIENTE']); ?>" class="f14" target="_blank"><?php echo $qrListaVendas['NOM_CLIENTE']; ?></a></td>
													<?php
													} else {
													?>
														<td><?php echo $qrListaVendas['NOM_CLIENTE']; ?></td>
													<?php
													}
													?>
													<!-- <td><?= fnMascaraCampo($qrListaVendas['NUM_CARTAO']) ?></td> -->
													<td><small><?= fnDataFull($qrListaVendas['DAT_CADASTR']) ?></small></td>
													<td><small><?= fnValor($qrListaVendas['VAL_CREDITO'], $casasDec) ?></small></td>
													<td><small><?= fnValor($qrListaVendas['VAL_TOTITEM'], 2) ?></small></td>
													<td><?= $qrListaVendas['DES_STATUS'] ?></td>
													<td><?= $qrListaVendas['DES_OPERACA'] ?></td>
													<td><small><?= fnDataShort($qrListaVendas['DAT_EXPIRA']) ?></small></td>
												</tr>

											<?php

												$countLinha++;
											}
										} else {
											?>
									<tbody>
										<thead>
											<tr>
												<th colspan="100">
													<center>
														<div style="margin: 10px; font-size: 17px; font-weight: bold">Não há créditos extras nesse período</div>
													</center>
												</th>
											</tr>
										</thead>
									</tbody>

								<?php
										}
								?>
								</tbody>

								<tfoot>
									<tr>
										<th class="" colspan="100">
											<center>
												<ul id="paginacao" class="pagination-sm"></ul>
											</center>
										</th>
									</tr>
									<!-- 	<tr>
											<th colspan="100">
												<a class="btn btn-info btn-sm exportarCSV">Exportar &nbsp;<i class="fal fa-file-excel" aria-hidden="true"></i></a>
											</th>
										</tr> -->

									<tr>
										<th colspan="100">
											<div class="btn-group dropdown left">
												<button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-file-excel" aria-hidden="true"></i>
													&nbsp; Exportar&nbsp;
													<span class="fas fa-caret-down"></span>
												</button>
												<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
													<li><a class="btn btn-sm exportarCSV" data-attr="univend" style="text-align: left">&nbsp; Detalhado por Unidade </a></li>
													<li><a class="btn btn-sm exportCli" data-attr="cliente" style="text-align: left">&nbsp; Detalhado por Cliente </a></li>
													<li><a class="btn btn-sm exportVen" data-attr="venda" style="text-align: left">&nbsp; Detalhado por Item </a></li>
												</ul>
											</div>
										</th>
									</tr>

								</tfoot>

								</table>

							</div>

						</div>

						<input type="hidden" name="LOJAS" id="LOJAS" value="<?= $lojasSelecionadas ?>">
						<input type="hidden" name="CASAS_DEC" id="CASAS_DEC" value="<?= $casasDec ?>">
						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="AUTORIZA" id="AUTORIZA" value="<?= $autoriza ?>" />
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
						<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
						<input type="hidden" class="form-control input-sm" name="CARTAO" id="CARTAO" value="<?php echo $num_cartao; ?>">
						<div class="push5"></div>



						<div class="push50"></div>

						<div class="push"></div>

					</div>

				</div>
			</div>
			<!-- fim Portlet -->
		</div>
	</form>
</div>

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

		$(".exportarCSV").click(function() {
			exportarCsv('exportar');
		});

		$(".exportCli").click(function() {
			exportarCsv('exportCli');
		});

		$(".exportVen").click(function() {
			exportarCsv('exportVen');
		});

		function exportarCsv(tipo) {
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
										url: "relatorios/ajxRelCreditoExtra.do?opcao=exportar&tipo=" + tipo + "&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>",
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
		}

		$('#NUM_CARTAO').on('submit', function() {
			$(this).val('');
		})

	});

	function reloadPage(idPage) {
		$.ajax({
			type: "POST",
			url: "relatorios/ajxRelCreditoExtra.do?idPage=" + idPage + "&itens_por_pagina=<?php echo $itens_por_pagina; ?>&id=<?php echo fnEncode($cod_empresa); ?>&opcao=paginar",
			data: $('#formulario').serialize(),
			beforeSend: function() {
				$('#relatorioConteudo').html('<div class="loading" style="width: 100%;"></div>');
			},
			success: function(data) {
				$("#relatorioConteudo").html(data);
			},
			error: function(data) {
				console.log(data);
				$('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
			}
		});
	}


	// function abreDetail(idBloco){
	// 	var idItem = $('.abreDetail_' + idBloco)
	// 	if (!idItem.is(':visible')){
	// 		idItem.show();
	// 		$('#bloco_'+idBloco).find($(".fa")).removeClass('fa-angle-right').addClass('fa-angle-down');
	// 	}else{
	// 		idItem.hide();
	// 		$('#bloco_'+idBloco).find($(".fa")).removeClass('fa-angle-down').addClass('fa-angle-right');
	// 	}
	// }
</script>