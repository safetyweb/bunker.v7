<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$opcao = "";
$hotel = "";
$log_diaria = "";
$num_adultos = "";
$num_criancas = "";
$cod_hotel = "";
$num_pessoas = "";
$hoje = "";
$ontem = "";
$hashLocal = "";
$msgRetorno = "";
$msgTipo = "";
$valorpag = "";
$cod_propriedade = "";
$cod_chale = "";
$cod_formapag = "";
$dat_ini = "";
$dat_fim = "";
$filtro_status = "";
$id_reserva = "";
$nom_usuario = "";
$actual_link = "";
$MODULO = "";
$COD_MODULO = "";
$hHabilitado = "";
$hashForm = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$formBack = "";
$abaAdorai = "";
$abaManutencaoAdorai = "";
$abaUsuario = "";
$sqlHotel = "";
$arrayHotel = [];
$qrHotel = "";
$qrStatuspag = "";
$andreserva = "";
$andData = "";
$andTip = "";
$and_propriedade = "";
$and_chale = "";
$sqlParcelas = "";
$arrParcelas = "";
$num_parcelas = "";
$qrBusca = "";
$status = "";
$dat_comp = "";
$dat_alterac = "";


//echo "<h5>_".$opcao."</h5>";

$hotel = "";
$log_diaria = 'N';
$num_adultos = 2;
$num_criancas = 0;
$cod_hotel = "2957,3010,3008,956";
$num_pessoas = 0;

//inicialização de variáveis
$hoje = fnFormatDate(date("Y-m-d"));
$ontem = fnFormatDate(date('Y-m-d', strtotime($ontem . '-1 days')));

$hashLocal = mt_rand();
$adm = $connAdm->connAdm();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;


		$valorpag = fnLimpaCampo(@$_REQUEST['VALOR']);
		$cod_empresa = fnLimpaCampo(@$_POST['COD_EMPRESA']);
		$cod_propriedade = fnLimpaCampo(@$_POST['COD_PROPRIEDADE']);
		$cod_chale = fnLimpaCampo(@$_POST['COD_CHALE']);
		$cod_formapag = fnLimpaCampo(@$_POST['COD_FORMAPAG']);
		$dat_ini = fnDataSql(@$_POST['DAT_INI']);
		$dat_fim = fnDataSql(@$_POST['DAT_FIM']);
		$filtro_status = fnLimpaCampo(@$_POST['FILTRO_STATUS']);
		$id_reserva = fnLimpacampoZero(@$_REQUEST['ID_RESERVA']);

		// fnEscreve($cod_hotel);

		$nom_usuario = $_SESSION["SYS_NOM_USUARIO"];
		$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$MODULO = @$_GET['mod'];
		$COD_MODULO = fndecode(@$_GET['mod']);

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];
	}
}


//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode(@$_GET['id']);
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


$conn = conntemp($cod_empresa, "");


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

				<?php
				$abaAdorai = 2006;
				include "abasAdorai.php";

				$abaManutencaoAdorai = 2019;
				//echo $abaUsuario;

				//se não for sistema de campanhas

				echo ('<div class="push20"></div>');
				include "abasSistemaAdorai.php";
				?>

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
										<label for="inputName" class="control-label">Data Inicial</label>

										<div class="input-group date datePicker" id="DAT_INI_GRP">
											<input type='text' class="form-control input-sm data" name="DAT_INI" id="DAT_INI" value="<?= $dat_ini ?>" />
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
										</div>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Data Final</label>

										<div class="input-group date datePicker" id="DAT_FIM_GRP">
											<input type='text' class="form-control input-sm data" name="DAT_FIM" id="DAT_FIM" value="<?= $dat_fim ?>" />
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
										</div>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Status do Pagamento</label>
										<select data-placeholder="Selecione o Tipo" name="FILTRO_STATUS" id="FILTRO_STATUS" class="chosen-select-deselect">
											<option value="">Todos</option>
											<option value="A">Aberta</option>
											<option value="V">Vencida</option>
											<option value="C">Cancelada</option>
											<option value="L">Liquidada</option>
										</select>
										<div class="help-block with-errors"></div>
										<script>
											$("#FILTRO_STATUS").val("<?php echo $filtro_status; ?>").trigger("chosen:updated");
										</script>
									</div>
								</div>

							</div>

							<div class="row">

								<div class="col-xs-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Id Reserva Foco</label>
										<input type="text" class="form-control input-sm" name="ID_RESERVA" id="ID_RESERVA" value="<?php echo $id_reserva; ?>" maxlength="50" data-error="Campo obrigatório">
										<div class="help-block with-errors"></div>
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
							<table class="table table-bordered table-striped table-hover">
								<thead>
									<tr>
										<th class="text-center" width="50"><input type='checkbox' id="selectAll"></th>
										<th class="text-center">Cód</th>
										<th class="text-center">Id Reserva Foco</th>
										<th class='text-center'>Num. Parcela</th>
										<th class='text-right'>Valor</th>
										<th class='text-center'>Dt. Vencimento</th>
										<th class='text-right'>Juros</th>
										<th class='text-right'>Multa</th>
										<th class='text-center'>Status</th>
										<th class='text-center'>Dt. Cadastro</th>
									</tr>
								</thead>

								<tbody id='div_refreshCarrinho'>

									<?php

									if ($id_reserva != "" && $id_reserva != 0) {
										$andreserva = "AND AP.ID_RESERVA = $id_reserva";
									} else {
										$andreserva = "";
									}

									if ($dat_ini != "" && $dat_fim != "") {
										$andData = "AND PC.DAT_VENCIMEN >= '$dat_ini'
													AND PC.DAT_VENCIMEN <= '$dat_fim'";
									} else {
										$andData = "";
									}

									if ($filtro_status != '' && $filtro_status != 0) {
										$andTip = "AND PC.TIP_PARCELA = '$filtro_status'";
									} else {
										$andTip = "";
									}


									if ($cod_propriedade == "" or $cod_propriedade == 9999) {
										$and_propriedade = " ";
									} else {
										$and_propriedade = "AND UNV.COD_EXTERNO = $cod_propriedade";
									}
									if ($cod_chale != '' && $cod_chale != 0) {
										$and_chale = "AND AC.COD_EXTERNO = $cod_chale";
									} else {
										$and_chale = " ";
									}


									$sqlParcelas = "SELECT PC.*, AP.ID_RESERVA FROM adorai_parcelas AS PC
													INNER JOIN adorai_pedido AS AP ON PC.COD_PEDIDO = AP.COD_PEDIDO
													INNER JOIN adorai_pedido_items AS API ON AP.COD_PEDIDO = API.COD_PEDIDO
													INNER JOIN adorai_chales AS AC ON API.COD_EXTERNO = AC.COD_EXTERNO
													INNER JOIN unidadevenda AS UNV ON AC.COD_HOTEL = UNV.COD_EXTERNO
													WHERE PC.COD_EMPRESA = 274 
													$andData
													$andTip
													$and_propriedade
													$and_chale
													$andreserva
													GROUP BY PC.COD_PARCELA
													ORDER BY PC.DAT_VENCIMEN";

									$arrParcelas = mysqli_query(connTemp($cod_empresa, ''), $sqlParcelas);

									$num_parcelas = mysqli_num_rows($arrParcelas);
									$count = 0;

									while ($qrBusca = mysqli_fetch_assoc($arrParcelas)) {
										$count++;

										switch ($qrBusca['TIP_PARCELA']) {
											case 'L':
												$status = "Liquidada";
												break;
											case 'C':
												$status = "Cancelada";
												break;
											case 'A':
												$status = "Aberta";
												break;
											case 'V':
												$status = "Vencida";
												break;
										}

									?>
										<tr>
											<?php if ($qrBusca['TIP_PARCELA'] == 'A') { ?>
												<td class='text-center'><input type='checkbox' name='radio_<?= $qrBusca['COD_PARCELA'] ?>_<?= $qrBusca['COD_PEDIDO'] ?>'>&nbsp;</td>
											<?php } else { ?>
												<td width="50"></td>
											<?php } ?>
											<td class='text-center'><small><?= $qrBusca['COD_PARCELA'] ?></small></td>
											<td class='text-center'><small><?= $qrBusca['ID_RESERVA'] ?></small></td>
											<td class='text-center'><small><?= $qrBusca['NUM_PARCELA'] ?></small></td>
											<td class='text-right'><small>R$</small> <?= fnValor($qrBusca['VAL_PARCELA'], 2) ?></td>
											<td class='text-center'><small><?= fnDataShort($qrBusca['DAT_VENCIMEN']) ?></small></td>
											<td class='text-right'><small>R$</small> <?= fnValor($qrBusca['VAL_JUROS'], 2) ?></td>
											<td class='text-right'><small>R$</small> <?= fnValor($qrBusca['VAL_MULTA'], 2) ?></td>
											<td class='text-center'><small><?= $status ?></small></td>
											<td class='text-center'><small><?= fnDataFull($qrBusca['DAT_CADASTR']) ?></small></td>
											<?php if ($qrBusca['TIP_PARCELA'] == 'A') { ?>
												<td width='40' class='text-center transparency'>
													<small>
														<div class='btn-group dropdown dropleft'>
															<a href='javascript:void(0)' class="btn btn-xs btn-info" data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
																&nbsp;&nbsp;<span class="fal fa-cog"></span>&nbsp;&nbsp;
															</a>
															<ul class='dropdown-menu' role='menu' aria-labelledby='dropdownMenu'>
																<li>
																	<a href='javascript:void(0)' onclick="pagarParcelas(<?= $qrBusca['COD_PARCELA'] . ',' . $qrBusca['COD_PEDIDO'] ?>,'<?= fnDataShort($qrBusca['DAT_VENCIMEN']) ?>')">
																		<div class="row">
																			<div class="col-xs-2 text-center" style="padding: 0;">
																				<div class="push5"></div>
																				<span class="fal fa-dollar-sign"></span>
																			</div>
																			<div class="col-xs-9" style="padding: 0;">
																				&nbsp;&nbsp;Pagar
																			</div>
																		</div>
																	</a>
																</li>
																<li>
																	<a href='javascript:void(0)' onclick="cancelarParcela(<?= $qrBusca['COD_PARCELA'] . ',' . $qrBusca['COD_PEDIDO'] ?>,'<?= fnDataShort($qrBusca['DAT_VENCIMEN']) ?>')">
																		<div class="row">
																			<div class="col-xs-2 text-center" style="padding: 0;">
																				<div class="push5"></div>
																				<span class="fal fa-times text-danger"></span>
																			</div>
																			<div class="col-xs-9" style="padding: 0;">
																				&nbsp;&nbsp;Cancelar
																			</div>
																		</div>
																	</a>
																</li>
																<!-- <li class='divider'></li> -->
															</ul>
														</div>
													</small>
												</td>
											<?php } else { ?>
												<td width="50"></td>
											<?php } ?>
										</tr>
									<?php

									}
									?>

								</tbody>

								<div class="push20"></div>

								<tfoot>

									<tr>
										<th colspan="100">
											<div class="btn-group dropdown left">
												<button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
													&nbsp; Ação Selecionados&nbsp;
													<span class="fas fa-caret-down"></span>
												</button>
												<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
													<li><a class="btn btn-sm pagarLote" data-attr="all" style="text-align: left"><span class="fal fa-dollar-sign"></span>&nbsp; Pagar </a></li>
													<li><a class="btn btn-sm cancelarLote" data-attr="univend" style="text-align: left"><span class="fal fa-times text-danger"></span>&nbsp; Cancelar </a></li>
												</ul>
											</div>
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
		$('#selectAll').click(function() {
			$(this).closest('table').find('td input:checkbox').prop('checked', this.checked);
		});

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

		$(".pagarLote").click(function() {
			var valores = [];
			$("input[type='checkbox']:checked").not('#selectAll').each(function() {
				var name = $(this).attr('name');
				var valoresSeparados = name.split('_');
				var obj = {
					cod_parcela: valoresSeparados[1],
					cod_pedido: valoresSeparados[2]
				};
				valores.push(obj);
			});
			var quant = valores.length;
			$.confirm({
				title: 'Pagamento em Lote',
				content: '' +
					'<form action="" class="formName">' +
					'<div class="form-group">' +
					'<label>Deseja Realmente confirmar o pagamento em lote de  ' + quant + ' Parcelas</label>' +
					'</div>' +
					'</form>',
				buttons: {
					formSubmit: {
						text: 'Confirmar',
						btnClass: 'btn-green',
						action: function() {
							$.confirm({
								title: 'Mensagem',
								type: 'green',
								icon: 'fa fa-check-square-o',
								content: function() {
									var self = this;
									return $.ajax({
										url: "ajxRelParcelasAdorai.do?opcao=LTPAG&id=<?= fnEncode($cod_empresa) ?>",
										method: 'POST',
										data: JSON.stringify(valores)
									}).done(function(response) {
										self.setContentAppend('<div>Parcelas paga com sucesso.</div>');
										location.reload();
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

		$(".cancelarLote").click(function() {
			var valores = [];
			$("input[type='checkbox']:checked").not('#selectAll').each(function() {
				var name = $(this).attr('name');
				var valoresSeparados = name.split('_');
				var obj = {
					cod_parcela: valoresSeparados[1],
					cod_pedido: valoresSeparados[2]
				};
				valores.push(obj);
			});
			var quant = valores.length;
			$.confirm({
				title: 'Cancelamento em Lote',
				content: '' +
					'<form action="" class="formName">' +
					'<div class="form-group">' +
					'<label>Deseja Realmente confirmar o cancelamento em lote de  ' + quant + ' Parcelas</label>' +
					'</div>' +
					'</form>',
				buttons: {
					formSubmit: {
						text: 'Confirmar',
						btnClass: 'btn-red',
						action: function() {
							$.confirm({
								title: 'Mensagem',
								type: 'red',
								icon: 'fa fa-check-square-o',
								content: function() {
									var self = this;
									return $.ajax({
										url: "ajxRelParcelasAdorai.do?opcao=CNLOTE&id=<?= fnEncode($cod_empresa) ?>",
										method: 'POST',
										data: JSON.stringify(valores)
									}).done(function(response) {
										self.setContentAppend('<div>Parcelas canceladas com sucesso.</div>');
										location.reload();
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

	function pagarParcelas(codParcela, codPedido, datVencimen) {

		var codParcela = codParcela;
		var codPedido = codPedido;
		var datVencimen = datVencimen;

		$.confirm({
			title: 'Pagamento',
			content: '' +
				'<form action="" class="formName">' +
				'<div class="form-group">' +
				'<label>Deseja Realmente confirmar o pagamento da parcela ' + codParcela + '</label>' +
				'<label>Referente a Reserva: ' + codPedido + '</label></br>' +
				'<label>Com vencimento em: ' + datVencimen + '</label>' +
				'</div>' +
				'</form>',
			buttons: {
				formSubmit: {
					text: 'Confirmar',
					btnClass: 'btn-green',
					action: function() {
						$.confirm({
							title: 'Mensagem',
							type: 'green',
							icon: 'fa fa-check-square-o',
							content: function() {
								var self = this;
								return $.ajax({
									url: "ajxRelParcelasAdorai.do?opcao=PAG&id=<?= fnEncode($cod_empresa) ?>&idp=" + codPedido + "&cdp=" + codParcela,
									method: 'POST'
								}).done(function(response) {
									self.setContentAppend('<div>Parcela paga com sucesso.</div>');
									location.reload();
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

	function cancelarParcela(codParcela, codPedido, datVencimen) {

		var codParcela = codParcela;
		var codPedido = codPedido;
		var datVencimen = datVencimen;

		$.confirm({
			title: 'Cancelamento',
			content: '' +
				'<form action="" class="formName">' +
				'<div class="form-group">' +
				'<label>Deseja Realmente confirmar o cancelamento da parcela ' + codParcela + '</label>' +
				'<label>Referente a Reserva: ' + codPedido + '</label></br>' +
				'<label>Com vencimento em: ' + datVencimen + '</label>' +
				'</div>' +
				'</form>',
			buttons: {
				formSubmit: {
					text: 'Confirmar',
					btnClass: 'btn-red',
					action: function() {
						$.confirm({
							title: 'Mensagem',
							type: 'red',
							icon: 'fa fa-check-square-o',
							content: function() {
								var self = this;
								return $.ajax({
									url: "ajxRelParcelasAdorai.do?opcao=CNL&id=<?= fnEncode($cod_empresa) ?>&idp=" + codPedido + "&cdp=" + codParcela,
									method: 'POST'
								}).done(function(response) {
									self.setContentAppend('<div>Parcela cancelada com sucesso.</div>');
									location.reload();
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