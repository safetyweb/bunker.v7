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
$cod_produto = "";
$msgRetorno = "";
$msgTipo = "";
$cod_categor = "";
$cod_subcate = "";
$log_select = "";
$checkSelect = "";
$dat_ini = "";
$dat_fim = "";
$opcao = "";
$hHabilitado = "";
$hashForm = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$cod_cliente = "";
$formBack = "";
$selecionado = "";
$andSubcate = "";
$retorno = "";
$totalitens_por_pagina = 0;
$inicio = "";
$content = "";
$hashLocal = mt_rand();
$itens_por_pagina = 50;
$pagina = 1;

//inicialização de variáveis
$hoje = fnFormatDate(date("Y-m-d"));
$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30 . '- 30 days')));
//$hoje = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 1 days')));
//$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 2 days')));
$cod_produto = "0";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;
		$cod_empresa = fnLimpaCampoZero(@$_POST['COD_EMPRESA']);
		$cod_univend = fnLimpaArray(@$_POST['COD_UNIVEND']);
		$cod_produto = fnLimpaCampoZero(@$_POST['COD_PRODUTO']);
		$cod_categor = fnLimpaCampoZero(@$_POST['COD_CATEGOR']);
		$cod_subcate = fnLimpaCampoZero(@$_POST['COD_SUBCATE']);
		$log_select = fnLimpaCampo(@$_POST['LOG_SELECT']);

		if ($log_select != '' && $log_select != 0) {
			$checkSelect = "checked";
		} else {
			$checkSelect = "";
		}

		$dat_ini = fnDataSql(@$_POST['DAT_INI']);
		$dat_fim = fnDataSql(@$_POST['DAT_FIM']);

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
	$sql = "SELECT COD_EMPRESA, NOM_EMPRESA FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
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

//fnMostraForm();
//fnEscreve($cod_cliente);

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
						<span class="text-primary"> <?php echo $NomePg; ?> / <?php echo $nom_empresa ?></span>
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
								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Log</label><br />
										<label class="switch switch-small">
											<input type="checkbox" name="LOG_SELECT" id="LOG_SELECT" class="switch" value="S" <?php echo $checkSelect; ?> />
											<span></span>
										</label>
										<div class="help-block with-errors"></div>
									</div>
								</div>
							</div>

							<div class="row">

								<!--<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label required">Unidade de Atendimento</label>
										<?php include "unidadesAutorizadasComboMulti.php"; ?>
									</div>
								</div>-->

								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label">Grupo do Produto</label>
										<select data-placeholder="Selecione o grupo" name="COD_CATEGOR" id="COD_CATEGOR" class="chosen-select-deselect requiredChk">
											<option value="9999">Todas categorias</option>
											<?php
											$sql = "select * from CATEGORIA where COD_EMPRESA = $cod_empresa AND (COD_EXCLUSA is null OR COD_EXCLUSA =0) order by DES_CATEGOR";
											$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

											while ($qrListaCategoria = mysqli_fetch_assoc($arrayQuery)) {

												if (recursive_array_search($qrListaCategoria['COD_CATEGOR'], array_filter(@$_REQUEST['COD_CATEGOR'])) !== false) {
													$selecionado = "selected";
												} else {
													$selecionado = "";
												}

												echo "<option value='" . $qrListaCategoria['COD_CATEGOR'] . "' " . $selecionado . " >" . $qrListaCategoria['DES_CATEGOR'] . "</option>";
											}
											?>
										</select>
										<script>
											$("#COD_CATEGOR").val("<?php echo $cod_categor; ?>").trigger("chosen:updated");
										</script>
										<div class="help-block with-errors"></div>
									</div>
									<a class="btn btn-default btn-sm" id="idAll" style="padding: 0 2px ; font-size: 10px;"><i class="fa fa-check-square-o" aria-hidden="true"></i> selecionar todos</a>&nbsp;
									<a class="btn btn-default btn-sm" id="idNone" style="padding: 0 2px ; font-size: 10px;"><i class="fa fa-square-o" aria-hidden="true"></i>&nbsp; deselecionar todos</a>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Sub Grupo do Produto</label>
										<div id="divId_sub">
											<select data-placeholder="Selecione o sub grupo" name="COD_SUBCATE" id="COD_SUBCATE" class="chosen-select-deselect">
												<option value="">&nbsp;</option>
											</select>
										</div>
										<script>
										</script>
										<div class="help-block with-errors"></div>
									</div>
								</div>

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

								<div class="col-md-2">
									<div class="push20"></div>
									<button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-sm btn-block getBtn"><i class="fal fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
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

						<div class="row">

							<div class="col-md-12" id="div_Produtos">

								<div class="push20"></div>

								<table class="table table-bordered table-hover tablesorter">


									<?php

									if ($cod_subcate != '' && $cod_subcate != 0) {
										$andSubcate = "AND B.cod_subcate = $cod_subcate";
									} else {
										$andSubcate = "";
									}

									if ($log_select == "") {

										$sql = "SELECT c.COD_CATEGOR,
										c.DES_CATEGOR,
										e.NOM_FANTASI,
										COUNT(distinct a.cod_venda) qtd_venda, 
										COUNT(DISTINCT a.COD_CLIENTE) qtd_clientes,
										SUM(a.qtd_produto) AS qtd_produto,
										SUM(a.val_totitem) AS valor_produto,
										SUM(a.val_resgate) AS valor_resgate,
										SUM(case when f.TIP_CREDITO='C' then
											f.val_credito
											else
												0 
											end) AS val_credito
										FROM itemvenda a
										INNER JOIN produtocliente b ON a.cod_produto=b.cod_produto AND a.cod_empresa=b.cod_empresa 
										INNER JOIN categoria c ON b.COD_CATEGOR=c.cod_categor AND b.cod_empresa=c.cod_empresa 
										INNER JOIN vendas d ON a.cod_venda=d.cod_venda AND d.COD_STATUSCRED IN(1,2,3,4,5,7,8,9) 
										INNER JOIN unidadevenda e on d.cod_univend=e.cod_univend  
										LEFT JOIN creditosdebitos f ON a.cod_itemven=f.cod_itemven AND d.cod_univend=f.cod_univend AND f.TIP_CREDITO='C'
										WHERE 
										a.cod_empresa=$cod_empresa AND 
										date(a.dat_cadastr) >='$dat_ini' AND 
										date(a.dat_cadastr) <='$dat_fim' AND 
										B.cod_categor IN ($cod_categor)
										$andSubcate
										";

										//fnEscreve($sql);
										$retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
										@$totalitens_por_pagina = mysqli_num_rows($retorno);
										$numPaginas = ceil($totalitens_por_pagina / $itens_por_pagina);
										//variavel para calcular o início da visualização com base na página atual
										$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;


										/*$sql = "SELECT c.COD_CATEGOR,
											c.DES_CATEGOR,
											e.NOM_FANTASI,
											COUNT(a.cod_venda) qtd_venda, 
											COUNT(DISTINCT a.COD_CLIENTE) qtd_clientes,
											SUM(qtd_produto)AS qtd_produto,
											SUM(val_totitem) AS valor_produto

											FROM itemvenda a,produtocliente b, categoria c, vendas d, unidadevenda e
											WHERE a.cod_produto IN(SELECT cod_produto FROM produtocliente
												WHERE cod_categor IN(SELECT cod_categor 
													FROM categoria
													WHERE cod_empresa=$cod_empresa AND 
													cod_categor IN($cod_categor)
													)
												) AND 
											a.cod_empresa=$cod_empresa AND 
											a.cod_venda=d.cod_venda AND 
											d.cod_univend=e.cod_univend AND 
											date(a.dat_cadastr) >='$dat_ini' AND 
											date(a.dat_cadastr) <='$dat_fim' AND 
											a.cod_produto=b.cod_produto AND 
											a.cod_empresa=b.cod_empresa AND 
											b.COD_CATEGOR=c.cod_categor AND 
											b.cod_empresa=c.cod_empresa
											GROUP BY c.cod_categor
											limit $inicio,$itens_por_pagina";*/



										$sql = "SELECT c.COD_CATEGOR,
											c.DES_CATEGOR,
											e.NOM_FANTASI,
											COUNT(distinct a.cod_venda) qtd_venda, 
											COUNT(DISTINCT a.COD_CLIENTE) qtd_clientes,
											SUM(a.qtd_produto) AS qtd_produto,
											SUM(a.val_totitem) AS valor_produto,
											SUM(a.val_resgate) AS valor_resgate,
											SUM(case when f.TIP_CREDITO='C' then
												f.val_credito
												else
													0 
												end) AS val_credito
											FROM itemvenda a
											INNER JOIN produtocliente b ON a.cod_produto=b.cod_produto AND a.cod_empresa=b.cod_empresa 
											INNER JOIN categoria c ON b.COD_CATEGOR=c.cod_categor AND b.cod_empresa=c.cod_empresa 
											INNER JOIN vendas d ON a.cod_venda=d.cod_venda AND d.COD_STATUSCRED IN(1,2,3,4,5,7,8,9) 
											INNER JOIN unidadevenda e on d.cod_univend=e.cod_univend  
											LEFT JOIN creditosdebitos f ON a.cod_itemven=f.cod_itemven AND d.cod_univend=f.cod_univend AND f.TIP_CREDITO='C'
											WHERE 
											a.cod_empresa=$cod_empresa AND 
											date(a.dat_cadastr) >='$dat_ini' AND 
											date(a.dat_cadastr) <='$dat_fim' AND 
											B.cod_categor IN ($cod_categor)
											$andSubcate
											limit $inicio,$itens_por_pagina";

										//fnEscreve($sql);

										$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
									?>

										<thead>
											<tr>
												<th><small>Categoria</small></th>
												<th class="text-center"><small>Qtd. Vendas</small></th>
												<th class="text-center"><small>Qtd. Clientes</small></th>
												<th class="text-center"><small>Qtd. Produtos</small></th>
												<th class="text-center"><small>Valor Produto</small></th>
												<th class="text-center"><small>Cashback Gerado</small></th>
												<th class="text-center"><small>Cashback Resgatado</small></th>
											</tr>
										</thead>

										<tbody id="relatorioConteudo">

											<?php

											$countLinha = 1;
											while (@$qrListaVendas = mysqli_fetch_assoc($arrayQuery)) {

											?>
												<tr>
													<td><small><?php echo $qrListaVendas['DES_CATEGOR']; ?></small></td>
													<td class="text-center"><small><?php echo $qrListaVendas['qtd_venda']; ?></small></td>
													<td class="text-center"><b><small><?php echo $qrListaVendas['qtd_clientes']; ?></small></b></td>
													<td class="text-center"><small><?php echo fnValor($qrListaVendas['qtd_produto'], 0); ?></small></td>
													<td class="text-center"><b><small><?php echo fnValor($qrListaVendas['valor_produto'], 2); ?></small></b></td>
													<td class="text-center"><b><small><?php echo fnValor($qrListaVendas['val_credito'], 2); ?></small></b></td>
													<td class="text-center"><b><small><?php echo fnValor($qrListaVendas['valor_resgate'], 2); ?></small></b></td>
												</tr>
											<?php

												$countLinha++;
											}
										} else {

											$sql = "
												SELECT 
												UNI.NOM_FANTASI,
												COUNT(DISTINCT CRED.COD_VENDA) QTD_VENDA,
												COUNT(DISTINCT CRED.COD_CLIENTE) QTD_CLIENTE,
												CRED.COD_ITEMVEN,
												SUM(ITM.QTD_PRODUTO) QTD_PRODUTO,
												SUM(ITM.VAL_TOTITEM) VAL_TOTITEM,
												SUM(CRED.VAL_CREDITO) VAL_CREDITO
												FROM creditosdebitos CRED
												INNER JOIN ITEMVENDA ITM ON ITM.COD_ITEMVEN=CRED.COD_ITEMVEN
												INNER JOIN UNIDADEVENDA UNI ON UNI.COD_UNIVEND=CRED.COD_UNIVEND
												INNER JOIN produtocliente B ON B.COD_PRODUTO=ITM.COD_PRODUTO
												WHERE 
												CRED.cod_empresa=$cod_empresa and CRED.TIP_CREDITO='C' AND
												date(CRED.DAT_CADASTR) BETWEEN '$dat_ini' AND '$dat_fim' AND
												CRED.COD_STATUSCRED IN (0,1,2,3,4,5,7,8) AND
												CRED.COD_ITEMVEN >0
												group by UNI.COD_UNIVEND ORDER BY UNI.NOM_FANTASI ASC
												";

											//fnEscreve($sql);
											$retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
											$totalitens_por_pagina = mysqli_num_rows($retorno);
											$numPaginas = ceil($totalitens_por_pagina / $itens_por_pagina);
											//variavel para calcular o início da visualização com base na página atual
											$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;


											$sql = "SELECT 
												UNI.NOM_FANTASI,
												COUNT(DISTINCT CRED.COD_VENDA) QTD_VENDA,
												COUNT(DISTINCT CRED.COD_CLIENTE) QTD_CLIENTE,
												CRED.COD_ITEMVEN,
												SUM(ITM.QTD_PRODUTO) QTD_PRODUTO,
												SUM(ITM.VAL_TOTITEM) VAL_TOTITEM,
												SUM(CRED.VAL_CREDITO) VAL_CREDITO
												FROM creditosdebitos CRED
												INNER JOIN ITEMVENDA ITM ON ITM.COD_ITEMVEN=CRED.COD_ITEMVEN
												INNER JOIN UNIDADEVENDA UNI ON UNI.COD_UNIVEND=CRED.COD_UNIVEND
												INNER JOIN produtocliente B ON B.COD_PRODUTO=ITM.COD_PRODUTO
												WHERE 
												CRED.cod_empresa=$cod_empresa and CRED.TIP_CREDITO='C' AND
												date(CRED.DAT_CADASTR) BETWEEN '$dat_ini' AND '$dat_fim' AND
												CRED.COD_STATUSCRED IN (0,1,2,3,4,5,7,8) AND
												CRED.COD_ITEMVEN >0
												group by UNI.COD_UNIVEND ORDER BY UNI.NOM_FANTASI ASC
												limit $inicio,$itens_por_pagina";

											//fnEscreve($sql);

											$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

											?>

											<thead>
												<tr>
													<th><small>Unidade</small></th>
													<th class="text-center"><small>Qtd. Vendas</small></th>
													<th class="text-center"><small>Qtd. Clientes</small></th>
													<th class="text-center"><small>Cód. Item</small></th>
													<th class="text-center"><small>Qtd. Produtos</small></th>
													<th class="text-center"><small>Valor Tot. Item</small></th>
													<th class="text-center"><small>Cashback Resgatado</small></th>
												</tr>
											</thead>

										<tbody id="relatorioConteudo">

											<?php

											$countLinha = 1;
											while (@$qrListaVendas = mysqli_fetch_assoc($arrayQuery)) {

											?>
												<tr>
													<td><small><?php echo $qrListaVendas['NOM_FANTASI']; ?></small></td>
													<td class="text-center"><small><?php echo $qrListaVendas['QTD_VENDA']; ?></small></td>
													<td class="text-center"><b><small><?php echo $qrListaVendas['QTD_CLIENTE']; ?></small></b></td>
													<td class="text-center"><small><?php echo $qrListaVendas['COD_ITEMVEN']; ?></small></td>
													<td class="text-center"><b><small><?php echo fnValor($qrListaVendas['QTD_PRODUTO'], 0); ?></small></b></td>
													<td class="text-center"><b><small><?php echo fnValor($qrListaVendas['VAL_TOTITEM'], 2); ?></small></b></td>
													<td class="text-center"><b><small><?php echo fnValor($qrListaVendas['VAL_CREDITO'], 2); ?></small></b></td>
												</tr>
										<?php

												$countLinha++;
											}
										}

										//fnEscreve($countLinha-1);				
										?>

										</tbody>

										<tfoot>
											<tr>
												<th colspan="100">
													<a class="btn btn-info btn-sm exportarCSV" data-opcao="exportar"><i class="fa fa-file-excel" aria-hidden="true"></i> &nbsp; Exportar </a>
													<!--<a class="btn btn-info btn-sm exportarCSV" data-opcao="detalhes"><i class="fa fa-file-excel" aria-hidden="true"></i> &nbsp; Exportar (Detalhado)</a>-->
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

								</table>

							</div>

						</div>

						<input type="hidden" name="NOVO_CLIENTE" id="NOVO_CLIENTE" value="">
						<input type="hidden" name="REFRESH_CLIENTE" id="REFRESH_CLIENTE" value="N">
						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
						<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
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

<!-- modal -->
<div class="modal fade" id="popModalAux" tabindex='-1'>
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
	let opcaoExp = "";

	$(document).ready(function() {
		var codCategoria = '<?= $cod_categor ?>';
		var codSubcategoria = '<?= $cod_subcate ?>';

		if (codCategoria !== '' && codSubcategoria !== '') {
			buscaSubCat(codCategoria, codSubcategoria, '<?= $cod_empresa ?>');
		}
	});

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

	});

	//datas
	$(function() {

		var numPaginas = <?php echo $numPaginas; ?>;
		if (numPaginas != 0) {
			carregarPaginacao(numPaginas);
		}

		$('#idAll').on('click', function(e) {
			e.preventDefault();
			$('#COD_CATEGOR option').prop('selected', true).trigger('chosen:updated');
		});

		$('#idNone').on('click', function(e) {
			e.preventDefault();
			$("#COD_CATEGOR option:selected").removeAttr("selected").trigger('chosen:updated');
		});

		//modal close
		$('.modal').on('hidden.bs.modal', function() {
			$('#formulario').validator('validate');
		});

		$(".exportarCSV").click(function() {
			opcaoExp = $(this).attr("data-opcao");
			// alert(opcaoExp);
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
										url: "relatorios/ajxRelDesempenhoCategoria.do?opcao=" + opcaoExp + "&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>&CATEGOR=<?php echo $cod_categor; ?>",
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

		// ajax
		$("#COD_CATEGOR").change(function() {
			var codBusca = $("#COD_CATEGOR").val();
			console.log(codBusca);
			var codBusca3 = $("#COD_EMPRESA").val();
			console.log(codBusca3);
			buscaSubCat(codBusca, 0, codBusca3);
		});
	});

	function buscaSubCat(idCat, idSub, idEmp) {
		$.ajax({
			type: "GET",
			url: "ajxBuscaSubGrupo.php",
			data: {
				ajx1: idCat,
				ajx2: idSub,
				ajx3: idEmp
			},

			beforeSend: function() {
				$('#divId_sub').html('<div class="loading" style="width: 100%;"></div>');
			},
			success: function(data) {
				$("#divId_sub").html(data);
				console.log(data);
			},
			error: function() {
				$('#divId_sub').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
			}
		});
	}

	function reloadPage(idPage) {
		$.ajax({
			type: "POST",
			url: "relatorios/ajxRelDesempenhoCategoria.do?opcao=paginar&id=<?php echo fnEncode($cod_empresa); ?>&idPage=" + idPage + "&itens_por_pagina=<?php echo $itens_por_pagina; ?>",
			data: $('#formulario').serialize(),
			beforeSend: function() {
				$('#relatorioConteudo').html('<tr><td colspan="100"><div class="loading" style="width: 100%;"></div></tr></td>');
			},
			success: function(data) {
				$("#relatorioConteudo").html(data);
				//$(".tablesorter").trigger("updateAll");
				//console.log(data);
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