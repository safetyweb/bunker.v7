<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$hoje = "";
$dias30 = "";
$hashLocal = "";
$valorTCreditos = "";
$valorTResgate = "";
$msgRetorno = "";
$msgTipo = "";
$cod_venda = "";
$cod_orcamento = "";
$cod_cliente = "";
$cod_lancamen = "";
$cod_ocorren = "";
$cod_formapa = "";
$tem_prodaux = "";
$dat_ini = "";
$dat_fim = "";
$numCartao = "";
$nomCliente = "";
$opcao = "";
$hHabilitado = "";
$hashForm = "";
$cod_usucada = "";
$sql1 = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$tip_retorno = "";
$casasDec = "";
$qrBuscaCliente = [];
$nom_cliente = "";
$num_cartao = "";
$num_cgcecpf = "";
$arrayParamAutorizacao = [];
$autoriza = "";
$modulo = "";
$query = "";
$result = "";
$msgFooter = "";
$formBack = "";
$abaEmpresa = "";
$andCodCli = "";
$andDat = "";
$andNome = "";
$andCartao = "";
$condicaoCartao = "";
$lojasSelecionadas = "";
$ARRAY_VENDEDOR = "";
$valorTTotal = "";
$valorTRegaste = "";
$valorTDesconto = "";
$valorTvenda = "";
$qrBuscaBloqueados = "";
$log_funciona = "";
$mostraCracha = "";
$colCliente = "";
$content = "";




//inicialização de variáveis
$hoje = fnFormatDate(date("Y-m-d"));
//$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 30 days')));
$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30 . '- 1 days')));

// definir o numero de itens por pagina
$itens_por_pagina = 50;
$pagina = "1";

$hashLocal = mt_rand();
$valorTCreditos = 0;
$valorTResgate = 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request'] = $request;

		$cod_venda = fnLimpacampoZero(@$_REQUEST['COD_VENDA']);
		$cod_orcamento = fnLimpacampoZero(@$_REQUEST['COD_ORCAMENTO']);
		$cod_empresa = fnLimpacampoZero(@$_REQUEST['COD_EMPRESA']);
		$cod_cliente = fnLimpacampoZero(@$_REQUEST['COD_CLIENTE']);
		$cod_lancamen = fnLimpacampoZero(@$_REQUEST['COD_LANCAMEN']);
		$cod_ocorren = fnLimpacampoZero(@$_REQUEST['COD_OCORREN']);
		$cod_univend = fnLimpacampoZero(@$_REQUEST['COD_UNIVEND']);
		$cod_formapa = fnLimpacampoZero(@$_REQUEST['COD_FORMAPA']);
		$tem_prodaux = fnLimpacampoZero(@$_REQUEST['TEM_PRODAUX']);

		$dat_ini = fnDataSql(@$_POST['DAT_INI']);
		$dat_fim = fnDataSql(@$_POST['DAT_FIM']);
		$numCartao = @$_POST['NUM_CARTAO'];
		$nomCliente = @$_POST['NOM_CLIENTE'];

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		if ($opcao != '' && $opcao != 0) {

			$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

			//echo $sql1;	

			//mysqli_query(connTemp(fnDecode(@$_GET['key']),''),$sql1);

			//mensagem de retorno
			switch ($opcao) {
				case 'CAD':
					$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					break;
				case 'ALT':
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

//busca dados url
if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode(@$_GET['id']);
	$cod_cliente = fnDecode(@$_GET['idC']);
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


//busca dados do cliente
$sql = "SELECT NOM_CLIENTE, NUM_CARTAO, NUM_CGCECPF, COD_CLIENTE FROM CLIENTES where COD_CLIENTE = '" . $cod_cliente . "' ";
//fnEscreve($sql);
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
$qrBuscaCliente = mysqli_fetch_assoc($arrayQuery);

if (isset($arrayQuery)) {

	$nom_cliente = @$qrBuscaCliente['NOM_CLIENTE'];
	$cod_cliente = @$qrBuscaCliente['COD_CLIENTE'];
	$num_cartao = @$qrBuscaCliente['NUM_CARTAO'];
	$num_cgcecpf = @$qrBuscaCliente['NUM_CGCECPF'];
} else {

	$nom_cliente = "";
	$cod_cliente = "";
	$num_cartao = "";
	$num_cgcecpf = "";
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


//rotina de controle de acessos por módulo
include "moduloControlaAcesso.php";

if (fnControlaAcesso("1024", $arrayParamAutorizacao) === true) {
	$autoriza = 1;
} else {
	$autoriza = 0;
}

$modulo = fnDecode(@$_GET['mod']);

//busca dados url
if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {
	$sql = "SELECT
	COUNT(a.COD_VENDA) QTD_VENDAS,
	MIN(a.dat_cadastr_ws) dat_cadastr_ws
	FROM vendas a
	INNER JOIN clientes f ON f.COD_CLIENTE=A.COD_CLIENTE
	LEFT JOIN webtools.tipolancamentomarka b ON b.cod_lancamen = a.cod_lancamen
	LEFT JOIN webtools.ocorrenciamarka c ON c.cod_ocorren = a.cod_ocorren
	LEFT JOIN unidadevenda d ON d.cod_univend = a.cod_univend
	LEFT JOIN formapagamento e ON e.cod_formapa = a.cod_formapa
	WHERE a.COD_STATUSCRED=3
	AND a.COD_EMPRESA = '$cod_empresa'
	AND a.cod_avulso = 2
	AND a.dat_cadastr_ws <= '$dat_fim 00:00:00'";

	$query = mysqli_query(connTemp($cod_empresa, ''), $sql);
	$result = mysqli_fetch_assoc($query);

	if ($result['QTD_VENDAS'] > 0) {
		$msgRetorno = "<strong><i class='fas fa-radiation fa-lg'></i></strong>&nbsp; O total de vendas bloqueadas é de <strong>" . $result['QTD_VENDAS'] . "</strong>,  &nbsp<br>
	&nbsp; a venda mais antiga bloqueada em <strong>" . fnFormatDate($result['dat_cadastr_ws']) . "</strong> &nbsp";

		$msgFooter = "<strong><i class='fas fa-radiation fa-lg'></i></strong>&nbsp; O total de vendas bloqueadas é de <strong>" . $result['QTD_VENDAS'] . "</strong>, a venda mais antiga bloqueada em <b>" . fnFormatDate($result['dat_cadastr_ws']) . "</b> &nbsp";
	} else {

		$msgRetorno = "";
		$msgFooter = "";
	}
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

	.modal-body {
		position: relative;
		padding: 15px;
		padding-top: 0px;
		height: 30%;

	}
</style>

<div class="push30"></div>

<div class="row">

	<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

		<div class="col-md12 margin-bottom-30">
			<!-- Portlet -->
			<div class="portlet portlet-bordered">
				<div class="portlet-title">
					<div class="caption">
						<i class="fal fa-terminal"></i>
						<span class="text-primary">
							<?php echo $NomePg; ?> /
							<?php echo $nom_empresa; ?>
						</span>
					</div>

					<?php
					$formBack = "1015";
					include "atalhosPortlet.php";
					?>

				</div>
				<div class="portlet-body">

					<?php if ($msgRetorno <> '') { ?>
						<div class="alert alert-warning" role="alert">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
									aria-hidden="true">&times;</span></button>
							<?php echo $msgRetorno; ?>
						</div>
					<?php } ?>

					<?php
					if (fnDecode(@$_GET['mod']) != 1191 && fnDecode(@$_GET['mod']) != 1618) {
						$abaEmpresa = 1099;
						include "abasEmpresaConfig.php";
					}
					?>

					<div class="push30"></div>

					<div class="login-form">

						<fieldset>
							<legend>Filtros</legend>

							<div class="row">

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Data Inicial</label>

										<div class="input-group date datePicker" id="DAT_INI_GRP">
											<input type='text' class="form-control input-sm data" name="DAT_INI"
												id="DAT_INI" value="<?php echo fnFormatDate($dat_ini); ?>" required />
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
											<input type='text' class="form-control input-sm data" name="DAT_FIM"
												id="DAT_FIM" value="<?php echo fnFormatDate($dat_fim); ?>" required />
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
										</div>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Nome do Cliente</label>
										<input type="text" class="form-control input-sm" name="NOM_CLIENTE"
											id="NOM_CLIENTE" value="<?php echo $nom_cliente; ?>">
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Cartão</label>
										<input type="text" class="form-control input-sm" name="NUM_CARTAO"
											id="NUM_CARTAO" value="<?php echo $num_cartao; ?>">
									</div>
								</div>

								<div class="push10"></div>

								<div class="col-md-5">
									<div class="form-group">
										<label for="inputName" class="control-label required">Unidade de
											Atendimento</label>
										<?php include "unidadesAutorizadasComboMulti.php"; ?>
									</div>
								</div>

								<div class="col-md-2">
									<div class="push20"></div>
									<button type="submit" name="ALT" id="ALT"
										class="btn btn-primary btn-sm btn-block getBtn"><i class="fal fa-filter"
											aria-hidden="true"></i>&nbsp; Filtrar</button>
								</div>


							</div>

						</fieldset>

					</div>

				</div>

			</div>

			<div class="push20"></div>

			<div class="portlet portlet-bordered">

				<div class="portlet-body">

					<div class="login-form">

						<div class="push20"></div>

						<style>
							.btSmall {
								padding: 3px 4px !important;
								font-size: 12px !important;
								line-height: 1.0 !important;
								border-radius: 3px !important;
							}
						</style>

						<div class="row">

							<div class="col-md-12" id="div_Produtos">

								<div class="push20"></div>

								<table class="table table-bordered table-hover tablesorter">
									<thead>
										<tr>
											<th></th>
											<th>Nome</th>
											<th>Cartão</th>
											<th>Data</th>
											<th>Total</th>
											<th>Créditos/Pontos</th>
											<th>Resgate</th>
											<th>Bloqueio</th>
											<th>Loja</th>
										</tr>
									</thead>
									<tbody>

										<?php

										if ($cod_cliente != 0 && $cod_cliente != '') {
											$andCodCli = " AND A.COD_CLIENTE = $cod_cliente ";
										} else {
											$andCodCli = "";
											$andDat = "	AND B.dat_cadastr_ws >= '$dat_ini 00:00:00'
												AND B.dat_cadastr_ws <= '$dat_fim 23:59:59'";
										}

										if ($nomCliente == "") {
											$andNome = " ";
										} else {
											//$andNome = "NOM_CLIENTE LIKE '%".$nomCliente."%' AND ";
											$andNome = "AND A.NOM_CLIENTE LIKE '%" . $nomCliente . "%' ";
										}

										if ($numCartao == "") {
											$andCartao = " ";
										} else {
											//$condicaoCartao = "B.NUM_CARTAO = $numCartao AND ";
											$andCartao = "AND A.NUM_CARTAO='$numCartao'";
											$andDat = '';
										}

										/*$sql = "SELECT 
																	  A.COD_CLIENTE,
																	  A.LOG_TERMO,
																	  A.NOM_CLIENTE,
																	  A.NUM_CARTAO,
																	  A.LOG_FUNCIONA,
																	  MIN(B.dat_cadastr_ws) AS DAT_CADASTR,
																	  SUM(VAL_TOTPRODU) AS VAL_TOTPRODU,
																	  SUM(B.VAL_TOTVENDA) AS VAL_TOTVENDA,
																	  (SELECT SUM(VAL_CREDITO) FROM CREDITOSDEBITOS 
																		  WHERE COD_CLIENTE=A.COD_CLIENTE AND
																		  COD_STATUSCRED=3 AND
																		  cod_venda=B.COD_VENDA AND
																		  TIP_CREDITO='C') AS VAL_CREDITOS,
																	  SUM(VAL_RESGATE) AS VAL_RESGATE,
																	  COUNT(*) AS QTD_VENDAS,
																	  d.COD_UNIVEND,
																	  d.NOM_FANTASI

																	  FROM CLIENTES A, VENDAS B
																	  LEFT JOIN unidadevenda d ON  d.cod_univend = b.cod_univend 
																	  WHERE A.COD_CLIENTE=B.COD_CLIENTE 
																	  AND B.COD_STATUSCRED=3
																	  AND A.COD_EMPRESA = $cod_empresa 
																	  AND cod_avulso = 2 
																	  AND B.cod_univend IN( $lojasSelecionadas ) 
																	  AND B.dat_cadastr_ws >= '$dat_ini 00:00:00' 
																	  AND B.dat_cadastr_ws <= '$dat_fim 23:59:59' 
																	  $andNome 
																	  $andCartao
																	  $andCodCli
																	  GROUP BY A.COD_CLIENTE
																	  ORDER BY B.dat_cadastr_ws DESC
																	  ";*/

										$sql = "SELECT *,
																	  (SELECT SUM(VAL_CREDITO) 
																	  	FROM CREDITOSDEBITOS
																	  	WHERE COD_CLIENTE=tmpvendascreditos.COD_CLIENTE
																	  	AND COD_STATUSCRED=3
																	  	AND TIP_CREDITO='C'AND  FIND_IN_SET (COD_VENDA,COD_VENDAS)) AS VAL_CREDITOS

																	  FROM (
																	  	SELECT B.dat_cadastr_ws,
																	  	A.COD_CLIENTE,
																	  	A.LOG_TERMO,
																	  	A.NOM_CLIENTE,
																	  	A.NUM_CARTAO,
																	  	A.LOG_FUNCIONA,
																	  	MIN(B.dat_cadastr_ws) AS DAT_CADASTR,
																	  	SUM(VAL_TOTPRODU) AS VAL_TOTPRODU,
																	  	SUM(B.VAL_TOTVENDA) AS VAL_TOTVENDA,
																	  	GROUP_CONCAT( DISTINCT B.COD_VENDA SEPARATOR ',') COD_VENDAS,
																	  	SUM(VAL_RESGATE) AS VAL_RESGATE,
																	  	COUNT(*) AS QTD_VENDAS,
																	  	d.COD_UNIVEND,
																	  	d.NOM_FANTASI
																	  	FROM CLIENTES A, VENDAS B
																	  	LEFT JOIN unidadevenda d ON d.cod_univend = b.cod_univend
																	  	WHERE A.COD_CLIENTE=B.COD_CLIENTE
																	  	AND B.COD_STATUSCRED=3
																	  	AND A.COD_EMPRESA = $cod_empresa
																	  	AND B.cod_avulso = 2
																	  	AND B.cod_univend IN($lojasSelecionadas)
																	  	$andDat
																	  	$andNome 
																	  	$andCartao
																	  	$andCodCli
																	  	GROUP BY A.COD_CLIENTE
																	  	ORDER BY B.dat_cadastr_ws DESC
																	  )tmpvendascreditos";

										//fnEscreve($sql);

										$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

										// echo '<pre>';
										// print_r($ARRAY_VENDEDOR);
										// echo '</pre>';

										$count = 0;
										$valorTTotal = 0;
										$valorTRegaste = 0;
										$valorTDesconto = 0;
										$valorTvenda = 0;

										while ($qrBuscaBloqueados = mysqli_fetch_assoc($arrayQuery)) {

											$count++;
											$valorTTotal = $valorTTotal + $qrBuscaBloqueados['VAL_TOTPRODU'];
											$valorTCreditos = $valorTCreditos + $qrBuscaBloqueados['VAL_CREDITOS'];
											$valorTResgate = $valorTResgate + $qrBuscaBloqueados['VAL_RESGATE'];
											$valorTvenda += $qrBuscaBloqueados['QTD_VENDAS'];

											$log_funciona = $qrBuscaBloqueados['LOG_FUNCIONA'];
											if ($log_funciona == "S") {
												$mostraCracha = '<i class="fas fa-address-card" aria-hidden="true"></i>';
											} else {
												$mostraCracha = "";
											}


											if ($autoriza == 1) {
												$colCliente = "<td><small><a href='action.do?mod=" . fnEncode(1024) . "&id=" . fnEncode($cod_empresa) . "&idC=" . fnEncode($qrBuscaBloqueados['COD_CLIENTE']) . "' target='_blank'>" . fnMascaraCampo($qrBuscaBloqueados['NOM_CLIENTE']) . "&nbsp;" . $mostraCracha . "</a></small></td>";
											} else {
												$colCliente = "<td><small>" . fnMascaraCampo($qrBuscaBloqueados['NOM_CLIENTE']) . "&nbsp;" . $mostraCracha . "</small></td>";
											}

											echo "
																	  	<tr id=" . "cod_cliente_" . $qrBuscaBloqueados['COD_CLIENTE'] . ">
																	  	<td class='text-center'><a href='javascript:void(0);' onclick='abreDetail(" . $qrBuscaBloqueados['COD_CLIENTE'] . "," . $qrBuscaBloqueados['COD_UNIVEND'] . ")'><i class='fas fa-plus' aria-hidden='true'></i></a></td>
																	  	" . $colCliente . "
																	  	<td>" . $qrBuscaBloqueados['NUM_CARTAO'] . "</td>												
																	  	<td>" . fnDataFull($qrBuscaBloqueados['DAT_CADASTR']) . "</td>												
																	  	<td class='text-right'><b><div class='totalLinhaCliente'>" . fnValor($qrBuscaBloqueados['VAL_TOTPRODU'], 2) . "</div></b></td>
																	  	<td class='text-right'><b>" . fnValor($qrBuscaBloqueados['VAL_CREDITOS'], $casasDec) . "</b></td>
																	  	<td class='text-right'><b>" . fnValor($qrBuscaBloqueados['VAL_RESGATE'], $casasDec) . "</b></td>
																	  	<td class='text-center'>" . $qrBuscaBloqueados['QTD_VENDAS'] . "</td>																								
																	  	<td>" . $qrBuscaBloqueados['NOM_FANTASI'] . "</td>												
																	  	</tr>

																	  	<tr style='display:none; background-color: #fff;' id='abreDetail_" . $qrBuscaBloqueados['COD_CLIENTE'] . "'>
																	  	<td></td>
																	  	<td colspan='9'>
																	  	<div id='mostraDetail_" . $qrBuscaBloqueados['COD_CLIENTE'] . "'>


																	  	</div>
																	  	</td>
																	  	</tr>

																	  	";
										}

										?>

									</tbody>
									<tfoot>
										<tr>
											<th></th>
											<th colspan="3">Total</th>
											<th class="text-right">
												<?php echo fnValor($valorTTotal, 2); ?>
											</th>
											<th class="text-right">
												<?php echo fnValor($valorTCreditos, $casasDec); ?>
											</th>
											<th class="text-right">
												<?php echo fnValor($valorTResgate, $casasDec); ?>
											</th>
											<th class="text-center">
												<?php echo fnValor($valorTvenda, 0); ?>
											</th>
											<th colspan="3"></th>
										</tr>
										<tr>
											<th colspan="100">
												<a class="btn btn-info btn-sm exportarCSV"><i class="fal fa-file-excel"
														aria-hidden="true"></i>&nbsp; Exportar </a>
											</th>
										</tr>
									</tfoot>
								</table>

								<?php if ($msgFooter <> '') { ?>
									<span class="text-warning"
										style="background-color: #E5E7E9; padding: 5px 10px 5px 10px; border-radius: 15px;">
										<?= $msgFooter ?>
									</span>
								<?php } ?>


								<input type="hidden" name="TEM_PRODAUX" id="TEM_PRODAUX"
									value="<?php echo $tem_prodaux; ?>">

							</div>

						</div>

						<input type="hidden" name="LOJAS" id="LOJAS" value="<?php echo $lojasSelecionadas; ?>" />
						<input type="hidden" name="AUTORIZA" id="AUTORIZA" value="<?= $autoriza ?>" />
						<input type="hidden" name="mod" id="mod" value="<?= fnEncode($modulo) ?>">
						<input type="hidden" name="CASAS_DEC" id="CASAS_DEC" value="<?= $casasDec ?>">
						<input type="hidden" name="NOVO_CLIENTE" id="NOVO_CLIENTE" value="">
						<input type="hidden" name="REFRESH_CLIENTE" id="REFRESH_CLIENTE" value="N">
						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
						<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA"
							value="<?php echo $cod_empresa; ?>">

						<div class="push50"></div>


					</div>

				</div>

			</div>
			<!-- fim Portlet -->

		</div>
	</form>
</div>

<!-- modal -->
<div class="modal fade" id="popModal" tabindex='-1'>
	<div class="modal-dialog" style="">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span
						aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"></h4>
			</div>
			<div class="modal-body">
				<iframe frameborder="0" width="100%" height="100%"></iframe>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="push20"></div>

<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />

<script type="text/javascript">
	$(document).ready(function() {

		//chosen
		$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
		$('#formulario').validator();

		//modal close
		$('.modal').on('hidden.bs.modal', function() {

			if ($('#REFRESH_CLIENTE').val() == "S") {
				var newCli = $('#NOVO_CLIENTE').val();
				window.location.href =
					"action.php?mod=<?php echo @$_GET['mod']; ?>&id=<?php echo fnEncode($cod_empresa); ?>&idC=" +
					newCli + " ";
				$('#REFRESH_PRODUTOS').val("N");
			}

		});


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

	});

	function abreDetail(idCli, idUnive) {
		RefreshVenda(<?php echo $cod_empresa; ?>, idCli, idUnive);
		//alert(idUnive);
	}

	function RefreshVenda(idEmp, idCli, idUnive) {
		var idItem = $('#abreDetail_' + idCli);
		var clientRow = $('#cod_cliente_' + idCli);
		mod = $("#mod").val();
		idItem.insertAfter(clientRow);

		const params = new URLSearchParams(window.location.search);
		const idC = params.get('idC');

		if (idC == null) {
			dat_ini = $("#DAT_INI").val();
			dat_fim = $("#DAT_FIM").val();
		} else {
			dat_ini = '';
			dat_fim = '';
		}

		if (!idItem.is(':visible')) {
			$.ajax({
				type: "GET",
				url: "ajxProdutosBloqueados.php",
				data: {
					ajx1: idEmp,
					ajx2: idCli,
					ajx3: idUnive,
					mod: mod,
					dat_ini: dat_ini,
					dat_fim: dat_fim
				},
				beforeSend: function() {
					$("#mostraDetail_" + idCli).html('<div class="loading" style="width: 100%;"></div>');
				},
				success: function(data) {
					$("#mostraDetail_" + idCli).html(data);
				},
				error: function() {
					$("#mostraDetail_" + idCli).html(
						'<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>'
					);
				}
			});

			idItem.show();

			$('#cod_cliente_' + idCli).find($(".fa-plus")).removeClass('fa-plus').addClass('fa-minus');
		} else {
			idItem.hide();
			$('#cod_cliente_' + idCli).find($(".fa-minus")).removeClass('fa-minus').addClass('fa-plus');
		}
	}

	function RefreshProdutosExc(idEmp, idOrc, tipo, idItem) {
		$.ajax({
			type: "GET",
			url: "ajxListaOrcamento.php",
			data: {
				ajx1: idEmp,
				ajx2: idOrc,
				ajx3: tipo,
				ajx4: idItem
			},
			beforeSend: function() {
				$('#div_Produtos').html('<div class="loading" style="width: 100%;"></div>');
			},
			success: function(data) {
				$("#div_Produtos").html(data);
				//recalcula();					
			},
			error: function() {
				$('#div_Produtos').html(
					'<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>'
				);
			}
		});
	}

	function toggleBlock(el, cod_cliente) {

		let log_desblok = "N";

		if ($(el).prop("checked")) {
			log_desblok = "S";
		}

		$.ajax({
			type: "POST",
			url: "ajxBlockCliente.do?id=<?= fnEncode($cod_empresa) ?>",
			data: {
				LOG_DESBLOK: log_desblok,
				COD_CLIENTE: cod_cliente
			},
			success: function(data) {
				console.log(data);
			},
			error: function() {}
		});
	}

	function excluirVenda(thisObj, codEmpresa, codCliente, idUnive) {
		var codVenda = $(thisObj).parent().parent().parent().attr('cod_venda');
		var opcao = "EXC";
		var logGeral = "N";

		$.confirm({
			title: 'Atenção!',
			animation: 'opacity',
			closeAnimation: 'opacity',
			content: 'Deseja realmente excluir a venda?',
			buttons: {
				confirmar: function() {
					$.ajax({
						type: "GET",
						url: "ajxExcluiDesbloqueiaVendas.php",
						data: {
							ajx1: codVenda,
							ajx2: codCliente,
							ajx3: opcao,
							ajx4: logGeral,
							ajx5: codEmpresa,
							ajx6: idUnive
						},
						beforeSend: function() {
							$('[cod_venda="' + codVenda + '"]').html(
								'<td colspan="9"><div class="loading" style="width: 100%; "></div></td>'
							);
							$('#cod_cliente_' + codCliente + ' .totalLinhaCliente').html(
								'<div class="loading" style="width: 100%; "></div>');
							$('#mostraDetail_' + codCliente + ' .subtotalProdBloq').html(
								'<div class="loading" style="width: 100%; "></div>');
						},
						success: function(response) {
							$('[cod_venda="' + codVenda + '"]').fadeOut(200, function() {
								$(this).remove();
							});

							recalcula($('[cod_venda="' + codVenda + '"] .prodBloqLinha').text(),
								codCliente);
						},
						error: function(xhr, ajaxOptions, thrownError) {
							//On error, we alert user
							alert(thrownError);
						}
					});
				},
				cancelar: function() {


				},
			}
		});
	}

	function desbloquearVenda(thisObj, codEmpresa, codCliente, idUnive) {
		var codVenda = $(thisObj).parent().parent().parent().attr('cod_venda');
		var opcao = "DES";
		var logGeral = "N";

		$.confirm({
			title: 'Atenção!',
			animation: 'opacity',
			closeAnimation: 'opacity',
			content: 'Deseja realmente desbloquear a venda?',
			buttons: {
				confirmar: function() {
					$.ajax({
						type: "GET",
						url: "ajxExcluiDesbloqueiaVendas.php",
						data: {
							ajx1: codVenda,
							ajx2: codCliente,
							ajx3: opcao,
							ajx4: logGeral,
							ajx5: codEmpresa,
							ajx6: idUnive
						},
						beforeSend: function() {
							$('[cod_venda="' + codVenda + '"]').html(
								'<td colspan="9"><div class="loading" style="width: 100%; "></div></td>'
							);
							$('#cod_cliente_' + codCliente + ' .totalLinhaCliente').html(
								'<div class="loading" style="width: 100%; "></div>');
							$('#mostraDetail_' + codCliente + ' .subtotalProdBloq').html(
								'<div class="loading" style="width: 100%; "></div>');
						},
						success: function(response) {
							$('[cod_venda="' + codVenda + '"]').fadeOut(200, function() {
								$(this).remove();
							});

							recalcula($('[cod_venda="' + codVenda + '"] .prodBloqLinha').text(),
								codCliente);

							// console.log(response);
						},
						error: function(xhr, ajaxOptions, thrownError) {
							//On error, we alert user
							alert(thrownError);
						}
					});
				},
				cancelar: function() {

				},
			}
		});
	}

	function excluirTodasVendas(thisObj, codEmpresa, codCliente, idUnive) {
		var codVenda = 0;
		var opcao = "EXC";
		var logGeral = "S";

		$.confirm({
			title: 'Atenção!',
			animation: 'opacity',
			closeAnimation: 'opacity',
			content: 'Deseja realmente excluir todas as vendas?',
			buttons: {
				confirmar: function() {
					$.ajax({
						type: "GET",
						url: "ajxExcluiDesbloqueiaVendas.php",
						data: {
							ajx1: codVenda,
							ajx2: codCliente,
							ajx3: opcao,
							ajx4: logGeral,
							ajx5: codEmpresa,
							ajx6: idUnive
						},
						beforeSend: function() {
							//$('#cod_cliente_' + codCliente).html('<td colspan="9"><div class="loading" style="width: 100%; "></div></td>');
						},
						success: function(response) {
							$('#mostraDetail_' + codCliente).parent().parent().remove();
							$('#mostraDetail_' + codCliente).remove();
							$('#cod_cliente_' + codCliente).find($(".fa")).removeClass('fa-minus');
							$('#cod_cliente_' + codCliente + ' .totalLinhaCliente').text("0,00");
							$('#mostraDetail_' + codCliente).fadeOut("fast");
							$('#cod_cliente_' + codCliente).fadeOut("fast");
						},
						error: function(xhr, ajaxOptions, thrownError) {
							//On error, we alert user
							alert(thrownError);
						}
					});
				},
				cancelar: function() {

				},
			}
		});
	}

	function desbloquearTodasVendas(thisObj, codEmpresa, codCliente, idUnive) {
		var codVenda = 0;
		var opcao = "DES";
		var logGeral = "S";

		$.confirm({
			title: 'Atenção!',
			animation: 'opacity',
			closeAnimation: 'opacity',
			content: 'Deseja realmente desbloquear todas as vendas?',
			buttons: {
				confirmar: function() {
					$.ajax({
						type: "GET",
						url: "ajxExcluiDesbloqueiaVendas.php",
						data: {
							ajx1: codVenda,
							ajx2: codCliente,
							ajx3: opcao,
							ajx4: logGeral,
							ajx5: codEmpresa,
							ajx6: idUnive
						},
						beforeSend: function() {
							//$('#cod_cliente_' + codCliente).html('<td colspan="9"><div class="loading" style="width: 100%; "></div></td>');
						},
						success: function(response) {
							$('#mostraDetail_' + codCliente).parent().parent().remove();
							$('#mostraDetail_' + codCliente).remove();
							$('#cod_cliente_' + codCliente).find($(".fa")).removeClass('fa-minus');
							$('#cod_cliente_' + codCliente + ' .totalLinhaCliente').text("0,00");
							$('#mostraDetail_' + codCliente).fadeOut("fast");
							$('#cod_cliente_' + codCliente).fadeOut("fast");
							console.log(response);
						},
						error: function(xhr, ajaxOptions, thrownError) {
							//On error, we alert user
							alert(thrownError);
						}
					});
				},
				cancelar: function() {

				},
			}
		});
	}

	function recalcula(valor, codCliente) {
		var valTotal = 0;
		$('.prodBloqLinha').each(function(index, item) {
			if ($(item).text() != "") {
				valTotal += limpaValor($(item).text());
			}

			valTotal -= valor;
		});

		$('#cod_cliente_' + codCliente + ' .totalLinhaCliente').text(valTotal);
		$('#cod_cliente_' + codCliente + ' .totalLinhaCliente').unmask();
		$('#cod_cliente_' + codCliente + ' .totalLinhaCliente').text(valTotal.toFixed(2));
		$('#cod_cliente_' + codCliente + ' .totalLinhaCliente').mask("#.##0,00", {
			reverse: true
		});

		$('#mostraDetail_' + codCliente + ' .subtotalProdBloq').text(valTotal);
		$('#mostraDetail_' + codCliente + ' .subtotalProdBloq').unmask();
		$('#mostraDetail_' + codCliente + ' .subtotalProdBloq').text(valTotal.toFixed(2));
		$('#mostraDetail_' + codCliente + ' .subtotalProdBloq').mask("#.##0,00", {
			reverse: true
		});

		if (valTotal == 0) {
			$('#mostraDetail_' + codCliente).fadeOut("fast");
			$('#cod_cliente_' + codCliente).fadeOut("fast");
		}
	}

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
							icon: 'fal fa-check-square-o',
							content: function() {
								var self = this;
								return $.ajax({
									url: "ajxVendasDesbloqueio.php?opcao=exportar&nomeRel=" +
										nome +
										"&id=<?php echo fnEncode($cod_empresa); ?>",
									data: $('#formulario').serialize(),
									method: 'POST'
								}).done(function(response) {
									self.setContentAppend(
										'<div>Exportação realizada com sucesso.</div>'
									);
									var fileName = '<?php echo $cod_empresa; ?>_' +
										nome + '.csv';
									SaveToDisk('media/excel/' + fileName, fileName);
									console.log(response);
								}).fail(function(response) {
									self.setContentAppend(
										'<div>Erro ao realizar o procedimento!</div>'
									);
									console.log(response);
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

	function retornaForm(index) {

	}
</script>