<?php

//echo "<h5>_".$opcao."</h5>";
$itens_por_pagina = 50;
$pagina = "1";

//inicialização de variáveis
$hoje = fnFormatDate(date("Y-m-d"));
$ontem = fnFormatDate(date('Y-m-d', strtotime($ontem . '-1 days')));

$hashLocal = mt_rand();
$adm = $connAdm->connAdm();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$valorpag = fnLimpaCampo($_REQUEST['VALOR']);
		$cod_empresa = fnLimpaCampo($_REQUEST['COD_EMPRESA']);
		$cod_propriedade = fnLimpaCampo($_REQUEST['COD_PROPRIEDADE']);
		$cod_chale = fnLimpaCampo($_REQUEST['COD_CHALE']);
		$cod_statuspag = fnLimpaCampo($_REQUEST['COD_STATUSPAG']);
		$cod_formapag = fnLimpaCampo($_REQUEST['COD_FORMAPAG']);
		$dat_ini = fnDataSql($_REQUEST['DAT_INI']);
		$dat_fim = fnDataSql($_REQUEST['DAT_FIM']);
		$filtro_data = fnLimpaCampo($_REQUEST['FILTRO_DATA']);
		$id_reserva = fnLimpacampoZero($_REQUEST['ID_RESERVA']);
		$des_chavecupom = fnLimpaCampo($_REQUEST['DES_CHAVECUPOM']);


		$nom_usuario = $_SESSION["SYS_NOM_USUARIO"];
		$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$MODULO = $_GET['mod'];
		$COD_MODULO = fndecode($_GET['mod']);

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];
	}
}


//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode($_GET['id']);
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($adm, $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
	}
} else {
	$cod_empresa = 274;
	//fnEscreve('entrou else');
}


//inicialização das variáveis - default	
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
	$dat_ini = fnDataSql($ontem);
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {

	$dat_fim = fnDataSql($hoje);
}

//fnMostraForm();

$checkDiaria = "";

if ($log_diaria == "S") {
	$checkDiaria = "checked";
}
$conn = conntemp($cod_empresa, "");

if ($filtro_data == "") {
	$filtro_data = "RESERVA";
}

?>

<style>
	.hiddenRow {
		padding: 0 !important;
	}

	tr {
		border-bottom: none !important;
	}

	#blocker {
		display: none;
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		opacity: .8;
		background-color: #fff;
		z-index: 1000;
	}

	#blocker div {
		position: absolute;
		top: 30%;
		left: 48%;
		width: 200px;
		height: 2em;
		margin: -1em 0 0 -2.5em;
		color: #000;
		font-weight: bold;
	}

	/*Menu DropDown*/
	.menu {
		top: 0 !important;
		left: -100px !important;
		width: 100px !important;
		z-index: 9999999;
		font-size: 13px !important;
	}



	.menu li a {
		color: #3c3c3c !important;
	}



	.menu-down-right,
	.menu-down-left,
	.menu.menu--right {
		transform-origin: top left !important;
	}

	@media screen and (max-width:778px) {
		.dropleft ul {
			right: inherit !important;
		}
	}
</style>

<div id="blocker">
	<div style="text-align: center;"><img src="images/loading2.gif"><br /> Aguarde. Processando... ;-)</div>
</div>

<div class="push30"></div>

<div class="row">

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fal fa-terminal"></i>
					<span class="text-primary"><?php echo $NomePg; ?></span>
				</div>

				<?php
				$formBack = "1019";
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

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Filtros</legend>

							<div class="row">

								<div class="col-xs-4">
									<div class="form-group">
										<label for="inputName" class="control-label ">Propriedades</label>
										<select data-placeholder="Selecione os hotéis" name="COD_PROPRIEDADE" id="COD_PROPRIEDADE" class="chosen-select-deselect">
											<option value="9999">Todas</option>
											<?php
											$sqlHotel = "SELECT COD_EXTERNO, NOM_FANTASI FROM UNIDADEVENDA WHERE COD_EMPRESA = $cod_empresa AND LOG_ESTATUS = 'S'";
											$arrayHotel = mysqli_query(connTemp($cod_empresa, ''), $sqlHotel);

											while ($qrHotel = mysqli_fetch_assoc($arrayHotel)) {
											?>
												<option value="<?= $qrHotel['COD_EXTERNO'] ?>"><?= $qrHotel['NOM_FANTASI'] ?></option>
											<?php
											}
											?>
										</select>
										<script>
											$("#COD_PROPRIEDADE").val("<?php echo $cod_propriedade; ?>").trigger("chosen:updated");
										</script>
										<div class="help-block with-errors"></div>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Chalés</label>
										<div id="divId_sub">
											<select data-placeholder="Selecione o sub grupo" name="COD_CHALE" id="COD_CHALE" class="chosen-select-deselect">
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
											<input type='text' class="form-control input-sm data" name="DAT_INI" id="DAT_INI" value="<?= $dat_ini ?>" required />
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
											<input type='text' class="form-control input-sm data" name="DAT_FIM" id="DAT_FIM" value="<?= $dat_fim ?>" required />
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
										</div>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Filtro de data</label>
										<select data-placeholder="Selecione o Tipo" name="FILTRO_DATA" id="FILTRO_DATA" class="chosen-select-deselect" required>
											<option value="RESERVA">Data do pedido</option>
											<option value="DEFAULT">Checkin - Checkout</option>
										</select>
										<div class="help-block with-errors"></div>
										<script>
											$("#FILTRO_DATA").val("<?php echo $filtro_data; ?>").trigger("chosen:updated");
										</script>
									</div>
								</div>

							</div>

							<div class="row">

								<div class="col-xs-2">
									<div class="form-group">
										<label for="inputName" class="control-label ">Status de Pagamento</label>
										<select data-placeholder="Selecione o status" name="COD_STATUSPAG" id="COD_STATUSPAG" class="chosen-select-deselect">
											<option value=""></option>
											<?php
											$sql = "SELECT * FROM ADORAI_STATUSPAG WHERE COD_EXCLUSA IS NULL";
											$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

											while ($qrStatuspag = mysqli_fetch_assoc($arrayQuery)) {
											?>
												<option value="<?= $qrStatuspag['COD_STATUSPAG'] ?>"><?= $qrStatuspag['ABV_STATUSPAG'] ?></option>
											<?php
											}
											?>
										</select>
										<div class="help-block with-errors"></div>
										<script>
											$("#COD_STATUSPAG").val("<?php echo $cod_statuspag; ?>").trigger("chosen:updated");
										</script>
									</div>
								</div>

								<div class="col-xs-2">
									<div class="form-group">
										<label for="inputName" class="control-label ">Forma de Pagamento</label>
										<select data-placeholder="Selecione o status" name="COD_FORMAPAG" id="COD_FORMAPAG" class="chosen-select-deselect">
											<option value=""></option>
											<?php
											$sql = "SELECT * FROM ADORAI_FORMAPAG WHERE COD_EXCLUSA IS NULL";
											$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

											while ($qrStatuspag = mysqli_fetch_assoc($arrayQuery)) {
											?>
												<option value="<?= $qrStatuspag['COD_FORMAPAG'] ?>"><?= $qrStatuspag['ABV_FORMAPAG'] ?></option>
											<?php
											}
											?>
										</select>
										<div class="help-block with-errors"></div>
										<script>
											$("#COD_FORMAPAG").val("<?php echo $cod_formapag; ?>").trigger("chosen:updated");
										</script>
									</div>
								</div>

								<div class="col-xs-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Id Reserva Foco</label>
										<input type="text" class="form-control input-sm" name="ID_RESERVA" id="ID_RESERVA" value="<?php echo $id_reserva; ?>" maxlength="50" data-error="Campo obrigatório">
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<!--<div class="col-xs-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Cupom</label>
										<input type="text" class="form-control input-sm" name="DES_CHAVECUPOM" id="DES_CHAVECUPOM" value="<?php echo $des_chavecupom; ?>" maxlength="50" data-error="Campo obrigatório">
										<div class="help-block with-errors"></div>
									</div>
								</div> -->

								<div class="col-md-2">
									<div class="push20"></div>
									<button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-sm btn-block getBtn"><i class="fal fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
								</div>

							</div>

						</fieldset>

						<div class="push10"></div>
						<hr>


						<div class="push10"></div>

						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
						<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">
						<div class="push5"></div>

					</form>

					<div class="push50"></div>



					<div class="no-more-tables">

						<form name="formLista">
							<table class="table table-bordered table-hover table-sortable tablesorter">
								<thead>
									<tr>
										<th class="text-center"><small>Id Reserva Foco</small></th>
										<th class="text-center"><small>Cliente</small></th>
										<th><small>Propriedade</small></th>
										<th><small>Contato</small></th>
										<th><small>Data Reserva</small></th>
										<th><small>Check-in</small></th>
										<th><small>Check-out</small></th>
										<th class='text-right'><small>Valor Recebido</small></th>
										<th class='text-right'><small>Valor a Receber</small></th>
										<th class='text-center'><small>Forma de Pagamento</small></th>
									</tr>
								</thead>

								<tbody id='div_refreshCarrinho' style='cursor: pointer;'>

									<?php

									if ($id_reserva != "" && $id_reserva != 0) {
										$andreserva = "AND AP.ID_RESERVA = $id_reserva";
									} else {
										$andreserva = "";
									}
									if ($des_chavecupom != "") {
										$andchavecupom = "AND AP.COD_CUPOM = '$des_chavecupom'";
									} else {
										$andchavecupom = "";
									}


									if ($cod_propriedade == "" or $cod_propriedade == 9999) {
										$and_propriedade = " ";
									} else {
										$and_propriedade = "AND AI.COD_PROPRIEDADE = $cod_propriedade";
									}
									if ($cod_chale != "") {
										$and_chale = "AND AI.COD_CHALE = $cod_chale";
									} else {
										$and_chale = " ";
									}

									if ($filtro_data == "DEFAULT") {
										$andDat = " AI.DAT_INICIAL >= '$dat_ini 00:00:00'
										AND AI.DAT_FINAL <= '$dat_fim 23:59:59'";
									} else {
										$andDat = "AI.DAT_CADASTR >= '$dat_ini 00:00:00'
										AND AI.DAT_CADASTR <= '$dat_fim 23:59:59'";
									}

									// if($cod_statuspag != ""){
									// 	$andStatusPag = "AND AP.COD_STATUSPAG = $cod_statuspag";
									// }else{
									// 	$andStatusPag ="";
									// }	

									if ($cod_formapag != "") {
										$andFormaPag = "AND AP.COD_FORMAPAG = $cod_formapag";
									} else {
										$andFormaPag = "";
									}

									$sql = "SELECT
											1
											FROM adorai_pedido AS AP
											INNER JOIN adorai_pedido_items AS AI ON AI.COD_PEDIDO = AP.COD_PEDIDO
											INNER JOIN adorai_chales AS AC ON AC.COD_EXTERNO = AI.COD_CHALE
											INNER JOIN unidadevenda AS UNV ON UNV.COD_EXTERNO = AI.COD_PROPRIEDADE
											INNER JOIN adorai_formapag AS FP ON FP.COD_FORMAPAG = AP.COD_FORMAPAG
												$andDat
												$andFormaPag
												$and_propriedade
												$and_chale
												$andreserva
												";

									$retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
									$totalitens_por_pagina = mysqli_num_rows($retorno);

									$qrResult = mysqli_fetch_assoc($retorno);

									// fnescreve($sql);
									$numPaginas = ceil($totalitens_por_pagina / $itens_por_pagina);

									// fnEscreve($numPaginas);
									//variavel para calcular o início da visualização com base na página atual
									$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

									$sql = "					
									SELECT
											AP.ID_RESERVA,
											AP.NOME,
											AP.SOBRENOME,
											AP.TELEFONE,
											AC.NOM_QUARTO,
											UNV.NOM_UNIVEND,
											AI.DAT_CADASTR,
											AI.DAT_INICIAL,
											AI.DAT_FINAL,
											FP.DES_FORMAPAG,
											AP.COD_CUPOM,
											CP.TIP_DESCONTO,
											CP.VAL_DESCONTO,
											AP.VALOR_COBRADO,
											AP.VALOR,
											AP.VALOR_PEDIDO,
											FP.COD_FORMAPAG,
											(
												SELECT SUM(ADO.VALOR) 
												FROM adorai_pedido_opcionais AS ADO
												INNER JOIN opcionais_adorai AS APA ON APA.COD_OPCIONAL = ADO.COD_OPCIONAL
												WHERE ADO.COD_PEDIDO = AP.COD_PEDIDO
												AND APA.LOG_CORTESIA != 'S'
												AND ADO.COD_EXCLUSA IS NULL
											) AS VALOR_OPCIONAIS,
											(
												SELECT SUM(CX.VAL_CREDITO) FROM caixa AS CX
												WHERE CX.COD_CONTRAT = AP.COD_PEDIDO
											) AS TOT_PAGO
											FROM adorai_pedido AS AP
											INNER JOIN adorai_pedido_items AS AI ON AI.COD_PEDIDO = AP.COD_PEDIDO
											INNER JOIN adorai_chales AS AC ON AC.COD_EXTERNO = AI.COD_CHALE
											LEFT JOIN adorai_pedido_opcionais AS ACP ON ACP.COD_PEDIDO = AP.COD_PEDIDO AND ACP.COD_EXCLUSA IS NULL
											INNER JOIN unidadevenda AS UNV ON UNV.COD_EXTERNO = AI.COD_PROPRIEDADE
											INNER JOIN adorai_formapag AS FP ON FP.COD_FORMAPAG = AP.COD_FORMAPAG
											LEFT JOIN CUPOM_ADORAI AS CP ON CP.DES_CHAVECUPOM = AP.COD_CUPOM
											LEFT JOIN opcionais_adorai AS OA ON OA.COD_OPCIONAL = ACP.COD_OPCIONAL
											WHERE
												$andDat
												$andFormaPag
												$and_propriedade
												$and_chale
												$andreserva
												GROUP BY AP.COD_PEDIDO
												ORDER BY AP.DAT_CADASTR DESC
									";
									$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
									$count = 0;
									$countChale = 0;
									$countOpcionais = 0;
									$countReserva = 0;

									$total_registros = mysqli_num_rows($arrayQuery);

									while ($qrBusca = mysqli_fetch_assoc($arrayQuery)) {
										$count++;

										$val_cobrado = $qrBusca['VALOR_COBRADO'];
										$valor = $qrBusca['VALOR'];
										$pct = $valor / 2;
										$tot_reserva = $qrBusca['VALOR_PEDIDO'] + $qrBusca['VALOR_OPCIONAIS'];
										$cod_cupom = $qrBusca['COD_CUPOM'];

										$descCupom = 0;
										if ($cod_cupom != "") {

											$qtd_diarias = fnDateDif($qrBusca['DAT_INICIAL'], $qrBusca['DAT_FINAL']);

											$tip_desconto = $qrBusca['TIP_DESCONTO'];
											$val_desconto = $qrBusca['VAL_DESCONTO'];

											switch ($tip_desconto) {
												case '1':
													$descCupom = $val_desconto * $qtd_diarias;
													break;

												case '2':
													$pct_desc = $val_desconto / 100;
													$val_diaria = $valor_chale / $qtd_diarias;
													$desc = $val_diaria * $pct_desc;
													$descCupom = $desc * $qtd_diarias;

													break;

												case '3':
													$pct_desc = $val_desconto / 100;
													$descCupom = $tot_reserva * $pct_desc;

													break;

												case '4':
													$descCupom = $val_desconto;

													break;
											}
										}

										$val_descPix = 0;
										if ($qrBusca['COD_FORMAPAG'] == 1 && $descCupom == "" && $pct != $val_cobrado) {
											$desc = $tot_reserva - $descCupom;
											$val_descPix = $desc * 0.05;
										} else {
											$val_descPix = 0;
										}

										if ($descCupom == "") {
											$descCupom = 0;
										}

										$reserva = $tot_reserva - $descCupom - $val_descPix;
										$restaPag = $reserva - $qrBusca['TOT_PAGO'];

										echo "
										<tr>
										<td class='text-center'><small>" . $qrBusca['ID_RESERVA'] . "</small></td>

										<td ><small>" . $qrBusca['NOME'] . " " . $qrBusca['SOBRENOME'] . "</small></td>

										<td ><small>" . $qrBusca['TELEFONE'] . "</small></td>

										<td ><small>" . $qrBusca['NOM_QUARTO'] . " - " . $qrBusca['NOM_UNIVEND'] . "</small></td>

										<td class='text-center'><small>" . fnDataShort($qrBusca['DAT_CADASTR']) . "</small></td>

										<td class='text-center'><small>" . fnDataShort($qrBusca['DAT_INICIAL']) . "</small></td>

										<td class='text-center'><small>" . fnDataShort($qrBusca['DAT_FINAL']) . "</small></td>

										<td class='text-right'><small>R$ " . fnValor($qrBusca['TOT_PAGO'], 2) . "</small></td>

										<td class='text-right'><small>R$ " . fnValor($restaPag, 2) . "</small></td>

										<td class='text-center'><small>" . $qrBusca['DES_FORMAPAG'] . "</small></td>
									
										</tr>	
										
										";

										$infoReserva = "";

										$countChale += $qrBusca['VALOR_PEDIDO'];
										$countOpcionais += $qrBusca['VALOR_OPCIONAIS'];
										$countReserva += $reserva;
									}
									?>

								</tbody>

								<div class="push20"></div>

								<tfoot>
									<tr>
										<td class='text-right'><b>Total de Reservas</td>
										<td class='text-right'><b><?= $total_registros ?></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td class='text-right'><b>R$ <?= fnValor($countReserva, 2) ?></b></td>
										<td class='text-right'><b>R$ <?= fnValor($countOpcionais, 2) ?></b></td>
										<td></td>
									</tr>

									<tr>
										<th colspan="100">
											<a class="btn btn-info btn-sm exportarCSV"> <i class="fal fa-file-excel" aria-hidden="true"></i>&nbsp; Exportar</a>
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
						</form>

					</div>

					<div class="push20"></div>

					<div class="push"></div>

				</div>

			</div>
		</div>
		<!-- fim Portlet -->
	</div>

	<div class="modal fade" id="popModal" tabindex='-1'>
		<div class="modal-dialog " style="max-width: 93% !important;">
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

</div>

<div class="push20"></div>

<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />

<script type="text/javascript">
	$(document).ready(function() {
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
		$("#DAT_FIM").val("<?= fnDataShort($dat_fim) ?>");
		$("#DAT_COMP").val("<?= fnDataShort($dat_comp) ?>");
		$("#DAT_ALTERAC").val("<?= fnDataShort($dat_alterac) ?>");

		// ajax
		$("#COD_PROPRIEDADE").change(function() {
			var codBusca = $("#COD_PROPRIEDADE").val();
			var codBusca3 = $("#COD_EMPRESA").val();
			buscaSubCat(codBusca, codBusca3);
		});

		var numPaginas = <?php echo $numPaginas; ?>;
		if (numPaginas != 0) {
			carregarPaginacao(numPaginas);
		}
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
							icon: 'fal fa-check-square-o',
							content: function() {
								var self = this;
								return $.ajax({
									url: "relatorios/ajxRelFluxoCaixaAdorai.do?opcao=exportar&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>",
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

	function enviarMensageria(arrInfo, cliente, tipoMsg) {

		let alerta = "";

		if (tipoMsg == "confirmacao") {
			alerta = 'Deseja realmente <b>enviar a confirmação</b> para ' + cliente + '?<br />O cliente receberá um email e uma mensagem via WhatsApp confirmando os detalhes da reserva.<br />Essa ação <b>não poderá ser desfeita</b>.';
		} else if (tipoMsg == "hospedes") {
			alerta = 'Deseja realmente <b>enviar o formulário</b> para ' + cliente + '?<br />O cliente receberá um email e uma mensagem via WhatsApp solicitando o preenchimento do formulário.<br />Essa ação <b>não poderá ser desfeita</b>.';
		}

		$.confirm({
			title: 'Confirmação',
			content: '' +
				'<form action="" class="formName">' +
				'<div class="form-group">' +
				'<label>' + alerta +
				'<br /><br /><label>Informe o canal pelo qual deseja comunicar:</label>' +
				'<input type="tel" placeholder="Canal" class="Canal form-control" value="1" required />' +
				'</div>' +
				'</form>',
			buttons: {
				formSubmit: {
					text: 'Confirmar',
					btnClass: 'btn-green',
					action: function() {
						var cal = this.$content.find('.Canal').val();
						if (!cal) {
							$.alert('Por favor, informe um canal');
							return false;
						}
						$.confirm({
							title: 'Mensagem',
							type: 'green',
							icon: 'fa fa-check-square-o',
							content: function() {
								var self = this;
								return $.ajax({
									url: "ajxConfirmaReservaAdorai.do?cal=" + cal + "&arrInfo=" + arrInfo + "&tipoMsg=" + tipoMsg,
									method: 'POST'
								}).done(function(response) {
									self.setContentAppend('<div>Confirmação de reserva enviada com sucesso.</div>');
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
	};

	function cancelaReserva(idReserva, cliente, codPedido) {
		// console.log('chamou');

		var idReserva = idReserva;
		var cliente = cliente;
		var codPedido = codPedido;

		$.confirm({
			title: 'Cancelamento',
			content: '' +
				'<form action="" class="formName">' +
				'<div class="form-group">' +
				'<label>Deseja Realmente cancelar a reserva do cliente ' + cliente + '?</label>' +
				'<label>por favor informe o motivo:</label>' +
				'<input type="text" placeholder="Motivo" class="Motivo form-control" required />' +
				'</div>' +
				'</form>',
			buttons: {
				formSubmit: {
					text: 'Confirmar',
					btnClass: 'btn-red',
					action: function() {
						var motivo = this.$content.find('.Motivo').val();
						if (!motivo) {
							$.alert('Por favor, insira um motivo');
							return false;
						}

						$.confirm({
							title: 'Mensagem',
							type: 'green',
							icon: 'fa fa-check-square-o',
							content: function() {
								var self = this;
								return $.ajax({
									url: "ajxCancelaReservaAdorai.do?&id=" + idReserva + "&obs=" + motivo + "&idc=" + codPedido,
									method: 'POST'
								}).done(function(response) {
									self.setContentAppend('<div>Reserva Cancelada com Sucesso.</div>');
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
	};

	function buscaSubCat(codprop, idEmp) {
		$.ajax({
			type: "GET",
			url: "ajxCheckoutAdorai.do?opcao=SubBusca",
			data: {
				COD_PROPRIEDADE: codprop,
				COD_EMPRESA: idEmp
			},

			beforeSend: function() {
				$('#divId_sub').html('<div class="loading" style="width: 100%;"></div>');
			},
			success: function(data) {
				$("#divId_sub").html(data);
			},
			error: function() {
				$('#divId_sub').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
			}
		});
	}
</script>