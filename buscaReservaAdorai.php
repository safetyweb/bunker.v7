<?php
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
$des_owner = "";
$msgRetorno = "";
$msgTipo = "";
$valorpag = "";
$cod_propriedade = "";
$cod_chale = "";
$cod_statuspag = "";
$cod_formapag = "";
$dat_ini = "";
$dat_fim = "";
$filtro_data = "";
$log_statusreserva = "";
$id_reserva = "";
$des_chavecupom = "";
$nom_usuario = "";
$actual_link = "";
$MODULO = "";
$COD_MODULO = "";
$hHabilitado = "";
$hashForm = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$checkDiaria = "";
$formBack = "";
$abaAdorai = "";
$abaManutencaoAdorai = "";
$abaUsuario = "";
$sqlHotel = "";
$arrayHotel = [];
$qrHotel = "";
$dat_comp = "";
$dat_alterac = "";
$qrStatuspag = "";
$andreserva = "";
$andchavecupom = "";
$and_propriedade = "";
$and_chale = "";
$andStatusReserva = "";
$andDat = "";
$andStatusPag = "";
$andFormaPag = "";
$andowner = "";
$retorno = "";
$totalitens_por_pagina = 0;
$qrResult = "";
$inicio = "";
$countChale = "";
$countOpcionais = "";
$countReserva = "";
$total_registros = 0;
$qrBusca = "";
$abv_formapag = "";
$infoReserva = "";
$confirmacao = "";
$chkConfirma = "";
$hospedes = "";
$chkHospedes = "";
$chkVoucher = "";
$chkCupom = "";
$concluido = "";
$sqlCancela = "";
$arrayQueryCancela = [];
$tdDropMenu = "";
$cancelada = "";
$tot_reserva = "";
$cod_cupom = "";
$descCupom = "";
$val_descPix = "";
$reserva = "";
$content = "";


//echo "<h5>_".$opcao."</h5>";
$itens_por_pagina = 50;
$pagina = "1";


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
$des_owner = "9999";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$valorpag = fnLimpaCampo(@$_REQUEST['VALOR']);
		$cod_empresa = fnLimpaCampo(@$_REQUEST['COD_EMPRESA']);
		$cod_propriedade = fnLimpaCampo(@$_REQUEST['COD_PROPRIEDADE']);
		$cod_chale = fnLimpaCampo(@$_REQUEST['COD_CHALE']);
		$cod_statuspag = fnLimpaCampo(@$_REQUEST['COD_STATUSPAG']);
		$cod_formapag = fnLimpaCampo(@$_REQUEST['COD_FORMAPAG']);
		$dat_ini = fnDataSql(@$_REQUEST['DAT_INI']);
		$dat_fim = fnDataSql(@$_REQUEST['DAT_FIM']);
		$filtro_data = fnLimpaCampo(@$_REQUEST['FILTRO_DATA']);
		$des_owner = fnLimpaCampo(@$_REQUEST['DES_OWNER']);
		$log_statusreserva = fnLimpaCampo(@$_REQUEST['LOG_STATUSRESERVA']);
		$id_reserva = fnLimpacampoZero(@$_REQUEST['ID_RESERVA']);
		$des_chavecupom = fnLimpaCampo(@$_REQUEST['DES_CHAVECUPOM']);


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

				<?php
				$abaAdorai = 2006;
				include "abasAdorai.php";

				$abaManutencaoAdorai = fnDecode(@$_GET['mod']);
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

								<div class="col-xs-2">
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

								<div class="col-xs-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Id Reserva Foco</label>
										<input type="text" class="form-control input-sm" name="ID_RESERVA" id="ID_RESERVA" value="<?php echo $id_reserva; ?>" maxlength="50" data-error="Campo obrigatório">
										<div class="help-block with-errors"></div>
									</div>
								</div>

							</div>

							<div class="row">
								<!--<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label ">Data da Reserva</label>

										<div class="input-group date datePicker">
											<input type='text' class="form-control input-sm data" name="DAT_COMP" id="DAT_COMP" value="<?= $dat_comp ?>"/>
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
										</div>
										<div class="help-block">Data da Compra</div>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label ">Data Alteração</label>

										<div class="input-group date datePicker">
											<input type='text' class="form-control input-sm data" name="DAT_ALTERAC" id="DAT_ALTERAC" value="<?= $dat_alterac ?>"/>
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
										</div>
										<div class="help-block">Data de Alteração da reserva</div>
										<div class="help-block with-errors"></div>
									</div>
								</div>-->

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
										<label for="inputName" class="control-label">Cupom</label>
										<input type="text" class="form-control input-sm" name="DES_CHAVECUPOM" id="DES_CHAVECUPOM" value="<?php echo $des_chavecupom; ?>" maxlength="50" data-error="Campo obrigatório">
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Origem</label>
										<select data-placeholder="Selecione o Tipo" name="DES_OWNER" id="DES_OWNER" class="chosen-select-deselect" required>
											<option value="9999">Todos</option>
											<option value="A">Adorai</option>
											<option value="F">Foco</option>
										</select>
										<div class="help-block with-errors"></div>
										<script>
											$("#DES_OWNER").val("<?php echo $des_owner; ?>").trigger("chosen:updated");
										</script>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Status Reserva</label>
										<select data-placeholder="Selecione o Tipo" name="LOG_STATUSRESERVA" id="LOG_STATUSRESERVA" class="chosen-select-deselect">
											<option value=""></option>
											<option value="Cancelado">Cancelado</option>
											<option value="Reservado">Reservado</option>
										</select>
										<div class="help-block with-errors"></div>
										<script>
											$("#LOG_STATUSRESERVA").val("<?php echo $log_statusreserva; ?>").trigger("chosen:updated");
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

						<!-- <div class="form-group text-right col-lg-12">
							<button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
							<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
							<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>
							<button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fal fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>
						</div> -->

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
										<th class="text-center"><small>Cód Pedido</small></th>
										<th class="text-center"><small>Id Reserva Foco</small></th>
										<th class="text-center"><small>Origem</small></th>
										<th><small>Cliente</small></th>
										<th><small>Propriedade</small></th>
										<th><small>Telefone</small></th>
										<th><small>Data Reserva</small></th>
										<th><small>Vendedor</small></th>
										<th><small>Checkin - Checkout</small></th>
										<th class='text-right'><small>Tot. Chalé</small></th>
										<th class='text-right'><small>Tot. Opcionais</small></th>
										<th class='text-right'><small>Tot. Liquido</small></th>
										<th class='text-center'><small>Forma de Pagamento</small></th>
										<th class="text-center"><small>Formulário</br>Voucher</br>Confirmação</small></th>
										<th><small>Status</br> Reserva</small></th>
										<th><small>Status Pag.</small></th>
									</tr>
								</thead>

								<tbody id='div_refreshCarrinho' style='cursor: pointer;'>

									<?php

									$andreserva = "";
									if ($id_reserva != "" && $id_reserva != 0) {
										$andreserva = "AND AP.ID_RESERVA = $id_reserva";
									}

									$andchavecupom = "";
									if ($des_chavecupom != '' && $des_chavecupom != 0) {
										$andchavecupom = "AND AP.COD_CUPOM = '$des_chavecupom'";
									}

									if ($cod_propriedade == "" or $cod_propriedade == 9999) {
										$and_propriedade = " ";
									} else {
										$and_propriedade = "AND AI.COD_PROPRIEDADE = $cod_propriedade";
									}

									$and_chale = " ";
									if ($cod_chale != '' && $cod_chale != 0) {
										$and_chale = "AND AI.COD_CHALE = $cod_chale";
									}

									$andStatusReserva = "";
									if ($log_statusreserva != '' && $log_statusreserva != 0) {
										$andStatusReserva = "AND AP.LOG_STATUSRESERVA = '$log_statusreserva'";
									}

									// if($filtro_data == "ALTERACAO"){
									// 	$andDat = "AND AI.DAT_ALTERAC >= '$dat_ini 00:00:00'
									// 	AND AI.DAT_ALTERAC >= '$dat_fim 23:59:59'";

									// }else if
									if ($filtro_data == "DEFAULT") {
										$andDat = "	AND AI.DAT_INICIAL >= '$dat_ini 00:00:00'
										AND AI.DAT_FINAL <= '$dat_fim 23:59:59'";
									} else {
										$andDat = "AND AI.DAT_CADASTR >= '$dat_ini 00:00:00'
										AND AI.DAT_CADASTR <= '$dat_fim 23:59:59'";
									}

									if ($cod_statuspag != '' && $cod_statuspag != 0) {
										$andStatusPag = "AND AP.COD_STATUSPAG = $cod_statuspag";
									} else {
										$andStatusPag = "";
									}

									if ($cod_formapag != '' && $cod_formapag != 0) {
										$andFormaPag = "AND AP.COD_FORMAPAG = $cod_formapag";
									} else {
										$andFormaPag = "";
									}

									if ($des_owner != "9999") {
										$andowner = "AND AP.DES_OWNER = '$des_owner'";
									} else {
										$andowner = "";
									}

									$sql = "SELECT COUNT(DISTINCT AP.COD_PEDIDO) AS total_registros
												FROM adorai_pedido AS AP
												LEFT JOIN adorai_pedido_items AS AI ON AI.COD_PEDIDO = AP.COD_PEDIDO
												LEFT JOIN unidadevenda AS UNV ON UNV.COD_EXTERNO = AI.COD_PROPRIEDADE
												LEFT JOIN adorai_chales AS AC ON AC.COD_EXTERNO = AI.COD_CHALE
												LEFT JOIN adorai_statuspag AS AST ON AST.COD_STATUSPAG = AP.COD_STATUSPAG
												LEFT JOIN adorai_formapag AS FP ON FP.COD_FORMAPAG = AP.COD_FORMAPAG
												LEFT JOIN USUARIOS AS USU ON USU.COD_USUARIO = AP.COD_VENDEDOR
												LEFT JOIN CUPOM_ADORAI AS CP ON CP.DES_CHAVECUPOM = AP.COD_CUPOM
												WHERE AP.COD_EMPRESA = 274
												AND AP.ID_RESERVA != 0
												$andDat
												$andStatusReserva
												$andStatusPag
												$andFormaPag
												$and_propriedade
												$and_chale
												$andreserva
												$andchavecupom
												$andowner
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
									SELECT AP.COD_PEDIDO,
									AP.COD_CARRINHO,
									AP.VAL_CUPOM,
									AP.DES_OWNER,
									AP.LOG_CONCLUIDO,
									AP.ID_RESERVA,
									AP.DAT_CADASTR,
									AP.NOME,
									AP.UUID,
									AP.SOBRENOME,
									AP.TELEFONE,
									AP.EMAIL,
									AP.VALOR,
									AP.VALOR_PEDIDO,
									AP.LOG_CONFIRMA,
									AP.COD_VENDEDOR,
									AP.LOG_HOSPEDES,
									AP.LOG_VOUCHER,
									AC.NOM_QUARTO,
									AI.COD_PROPRIEDADE,
									AI.DAT_INICIAL,
									AI.DAT_FINAL,
									UNV.NOM_FANTASI,
									AST.ABV_STATUSPAG,
									USU.NOM_USUARIO,
									AP.COD_STATUSPAG,
									CP.TIP_DESCONTO,
									CP.VAL_DESCONTO,
									FP.COD_FORMAPAG,
									AP.VALOR_COBRADO,
									AP.COD_CUPOM,
									AP.LOG_STATUSRESERVA,
									(SELECT SUM(ADO.VALOR) 
										FROM adorai_pedido_opcionais AS ADO
										INNER JOIN opcionais_adorai AS APA ON APA.COD_OPCIONAL = ADO.COD_OPCIONAL
										WHERE ADO.COD_PEDIDO = AP.COD_PEDIDO
										AND APA.LOG_CORTESIA != 'S'
									) AS VALOR_OPCIONAIS,
									FP.ABV_FORMAPAG
									FROM adorai_pedido AS AP
									LEFT JOIN adorai_pedido_items AS AI ON AI.COD_PEDIDO = AP.COD_PEDIDO
									LEFT JOIN adorai_pedido_opcionais AS ACP ON ACP.COD_PEDIDO = AP.COD_PEDIDO
									LEFT JOIN unidadevenda AS UNV ON UNV.COD_EXTERNO = AI.COD_PROPRIEDADE
									LEFT JOIN adorai_chales AS AC ON AC.COD_EXTERNO = AI.COD_CHALE
									LEFT JOIN adorai_statuspag AS AST ON AST.COD_STATUSPAG = AP.COD_STATUSPAG
									LEFT JOIN adorai_formapag AS FP ON FP.COD_FORMAPAG = AP.COD_FORMAPAG
									LEFT JOIN opcionais_adorai AS OA ON OA.COD_OPCIONAL = ACP.COD_OPCIONAL
									LEFT JOIN USUARIOS AS USU ON USU.COD_USUARIO = AP.COD_VENDEDOR
									LEFT JOIN CUPOM_ADORAI AS CP ON CP.DES_CHAVECUPOM = AP.COD_CUPOM
									WHERE AP.COD_EMPRESA = 274
									AND AP.ID_RESERVA != 0
									$andDat
									$andStatusPag
									$andStatusReserva
									$andFormaPag
									$and_propriedade
									$and_chale
									$andreserva
									$andchavecupom
									$andowner
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

										if (!empty($qrBusca['ABV_FORMAPAG'])) {
											$abv_formapag = $qrBusca['ABV_FORMAPAG'];
										}

										$infoReserva = array(
											"uuid" => $qrBusca['UUID'],
											"cod_hotel" => $qrBusca['COD_PROPRIEDADE'],
											"nome" => $qrBusca['NOME'],
											"chale" => $qrBusca['NOM_QUARTO'],
											"dat_ini" => fnDataShort($qrBusca['DAT_INICIAL']),
											"dat_fim" => fnDataShort($qrBusca['DAT_FINAL']),
											"num_celular" => $qrBusca['TELEFONE'],
											"email" => $qrBusca['EMAIL']
										);

										$infoReserva = base64_encode(json_encode($infoReserva, true));

										if ($qrBusca['LOG_CONFIRMA'] == 'N') {
											$confirmacao = "<li><a href='javascript:void(0)' onclick='enviarMensageria(\"$infoReserva\",\"$qrBusca[NOME]\", \"confirmacao\")'><span class='fal fa-clock text-default'></span>&nbsp;Enviar confirmação de reserva e formulário de Hóspedes</a></li>";
											$chkConfirma = "<span class='fal fa-times text-danger'></span>";
										} else {
											$confirmacao = "<li class='disabled' disabled><span class='fal fa-check text-success'></span>&nbsp;Confirmação de reserva já enviada</li>";
											$chkConfirma = "<span class='fal fa-check text-success'></span>";
										}

										if ($qrBusca['LOG_HOSPEDES'] == 'N') {
											$hospedes = "<li><a href='javascript:void(0)' onclick='enviarMensageria(\"$infoReserva\",\"$qrBusca[NOME]\", \"hospedes\")'><span class='fal fa-list text-info'></span>&nbsp;Enviar formulário de hóspedes</a></li>";
											$chkHospedes = "<span class='fal fa-times text-danger'></span>";
										} else {
											$hospedes = "<li class='disabled' disabled><span class='fal fa-check text-success'></span>&nbsp;Formulário de hóspedes já enviado</li>";
											$chkHospedes = "<span class='fal fa-check text-success'></span>";
										}

										if ($qrBusca['LOG_VOUCHER'] == 'N') {
											$chkVoucher = "<span class='fal fa-times text-danger'></span>";
										} else {
											$chkVoucher = "<span class='fal fa-check text-success'></span>";
										}

										if ($qrBusca['COD_VENDEDOR'] == '') {
											$chkCupom = "";
										} else {
											$chkCupom = "<span class='fal fa-check text-success shortCut' data-toggle='tooltip' data-placement='top' data-original-title='" . $qrBusca['NOM_USUARIO'] . "' id='shortRFH'></span>";
										}

										if ($qrBusca['LOG_CONCLUIDO'] == "S") {
											$concluido = "style= 'background-color: #eafaf1;'";
										} else {
											$concluido = "";
										}

										//SQL PARA VERIFICAR SE A RESERVA FOI CANCELADA
										// $sqlCancela = "SELECT * FROM ADORAI_CANCELAMENTOS WHERE COD_PEDIDO = " . $qrBusca['COD_PEDIDO'];
										// $arrayQueryCancela = mysqli_query(connTemp($cod_empresa, ''), $sqlCancela);

										if ($qrBusca['LOG_STATUSRESERVA'] == "Cancelado") {
											$tdDropMenu = "<td width='40'></td>";
											$cancelada = "style='background-color: #fdedec;'";
										} else {
											$cancelada = "";
											$tdDropMenu = "
												<td width='40' class='text-center'>
													<div class='btn-group dropdown dropleft'>
													<a href='javascript:void(0)' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
													<span style='opacity: 0.4;' class='fal fa-ellipsis-v fa-2x'></span>
													</a>
													<ul class='dropdown-menu' role='menu' aria-labelledby='dropdownMenu'>
													<li><a href='javascript:void(0)' data-url='action.do?mod=" . fnEncode(2012) . "&id=" . fnEncode($cod_empresa) . "&idp=" . fnEncode($qrBusca['COD_PEDIDO']) . "&pop=true' class='addBox' data-title='Detalhes da Reserva'>Detalhes</a></li>
													<li><a href='https://roteirosadorai.com.br/hospedes.php?id=" . fnEncode($qrBusca['UUID']) . "' target='_blank'>Formulário de Hóspedes</a></li>
													<li><a href='https://roteirosadorai.com.br/voucher.php?id=" . fnEncode($qrBusca['UUID']) . "' target='_blank'>Voucher da Reserva</a></li>
													<li><a href='javascript:void(0)' data-url='action.do?mod=" . fnEncode(2067) . "&id=" . fnEncode($cod_empresa) . "&idp=" . fnEncode($qrBusca['COD_PEDIDO']) . "&pop=true' class='addBox' data-title='Vincular Vendedor'>Vincular Vendedor</a></li>
													<li class='divider'></li>

													$confirmacao
													<li><a href='javascript:void(0)' onclick='cancelaReserva($qrBusca[ID_RESERVA],\"$qrBusca[NOME]\",$qrBusca[COD_PEDIDO])'>&nbsp;<span class='fal fa-times text-danger'></span>&nbsp;&nbsp;Cancelar reserva</a></li>
													<li class='divider'></li>
													<li><a href='javascript:void(0)' onclick='concluiReserva($qrBusca[ID_RESERVA],$qrBusca[COD_PEDIDO])'>&nbsp;<span class='fal fa-check text-success'></span>&nbsp;&nbsp;Concluir reserva</a></li>
													</ul>
													</div>
												</td>";
										}

										$tot_reserva = $qrBusca['VALOR_PEDIDO'] + $qrBusca['VALOR_OPCIONAIS'];
										$cod_cupom = $qrBusca['COD_CUPOM'];

										$descCupom = 0;
										if ($cod_cupom != '' && $cod_cupom != 0) {
											$descCupom = $qrBusca['VAL_CUPOM'];
										}

										$val_descPix = 0;
										if ($qrBusca['PIX_50'] != "S") {
											$val_descPix = $qrBusca['DESCONTO_PIX'];
										} else {
											$val_descPix = 0;
										}

										$reserva = $tot_reserva - $descCupom - $val_descPix;

										echo "
										<tr $cancelada $concluido>
										<td class='text-center' data-url='action.do?mod=" . fnEncode(2012) . "&id=" . fnEncode($cod_empresa) . "&idp=" . fnEncode($qrBusca['COD_PEDIDO']) . "&pop=true' class='addBox' data-title='Detalhes da Reserva'><small>" . $qrBusca['COD_PEDIDO'] . "</small>
										</td>

										<td class='text-center' data-url='action.do?mod=" . fnEncode(2012) . "&id=" . fnEncode($cod_empresa) . "&idp=" . fnEncode($qrBusca['COD_PEDIDO']) . "&pop=true' class='addBox' data-title='Detalhes da Reserva'><small>" . $qrBusca['ID_RESERVA'] . "</small>
										</td>

										<td data-url='action.do?mod=" . fnEncode(2012) . "&id=" . fnEncode($cod_empresa) . "&idp=" . fnEncode($qrBusca['COD_PEDIDO']) . "&pop=true' class='addBox text-center' data-title='Detalhes da Reserva'><small>" . $qrBusca['DES_OWNER'] . "</small>
										</td>

										<td data-url='action.do?mod=" . fnEncode(2012) . "&id=" . fnEncode($cod_empresa) . "&idp=" . fnEncode($qrBusca['COD_PEDIDO']) . "&pop=true' class='addBox' data-title='Detalhes da Reserva'><small>" . strtoupper($qrBusca['NOME']) . " " . strtoupper($qrBusca['SOBRENOME']) . "</small>
										</td>

										<td data-url='action.do?mod=" . fnEncode(2012) . "&id=" . fnEncode($cod_empresa) . "&idp=" . fnEncode($qrBusca['COD_PEDIDO']) . "&pop=true' class='addBox' data-title='Detalhes da Reserva'><small>" . $qrBusca['NOM_FANTASI'] . ' - ' . $qrBusca['NOM_QUARTO'] . "</small>
										</td>

										<td data-url='action.do?mod=" . fnEncode(2012) . "&id=" . fnEncode($cod_empresa) . "&idp=" . fnEncode($qrBusca['COD_PEDIDO']) . "&pop=true' class='addBox' data-title='Detalhes da Reserva'><small>" . $qrBusca['TELEFONE'] . "</small>
										</td>

										<td data-url='action.do?mod=" . fnEncode(2012) . "&id=" . fnEncode($cod_empresa) . "&idp=" . fnEncode($qrBusca['COD_PEDIDO']) . "&pop=true' class='addBox' data-title='Detalhes da Reserva'><small>" . fnDataFull($qrBusca['DAT_CADASTR']) . "</small>
										</td>

										<td data-url='action.do?mod=" . fnEncode(2012) . "&id=" . fnEncode($cod_empresa) . "&idp=" . fnEncode($qrBusca['COD_PEDIDO']) . "&pop=true' class='addBox text-center' data-title='Detalhes da Reserva'><small>" . $chkCupom . "</small>
										</td>

										<td data-url='action.do?mod=" . fnEncode(2012) . "&id=" . fnEncode($cod_empresa) . "&idp=" . fnEncode($qrBusca['COD_PEDIDO']) . "&pop=true' class='addBox' data-title='Detalhes da Reserva'><small>" . fnDataShort($qrBusca['DAT_INICIAL']) . ' - ' . fnDataShort($qrBusca['DAT_FINAL']) . "</small>
										</td>

										<td data-url='action.do?mod=" . fnEncode(2012) . "&id=" . fnEncode($cod_empresa) . "&idp=" . fnEncode($qrBusca['COD_PEDIDO']) . "&pop=true' class='addBox text-right' data-title='Detalhes da Reserva'><small>R$ " . fnValor($qrBusca['VALOR_PEDIDO'], 2) . "</small>
										</td>

										<td data-url='action.do?mod=" . fnEncode(2012) . "&id=" . fnEncode($cod_empresa) . "&idp=" . fnEncode($qrBusca['COD_PEDIDO']) . "&pop=true' class='addBox text-right' data-title='Detalhes da Reserva'><small>R$ " . fnValor($qrBusca['VALOR_OPCIONAIS'], 2) . "</small>
										</td>

										<td data-url='action.do?mod=" . fnEncode(2012) . "&id=" . fnEncode($cod_empresa) . "&idp=" . fnEncode($qrBusca['COD_PEDIDO']) . "&pop=true' class='addBox text-right' data-title='Detalhes da Reserva'><small>R$ " . fnValor($reserva, 2) . "</small>
										</td>

										<td data-url='action.do?mod=" . fnEncode(2012) . "&id=" . fnEncode($cod_empresa) . "&idp=" . fnEncode($qrBusca['COD_PEDIDO']) . "&pop=true' class='addBox text-center' data-title='Detalhes da Reserva'><small>" . $abv_formapag . "</small>
										</td>			

										<td data-url='action.do?mod=" . fnEncode(2012) . "&id=" . fnEncode($cod_empresa) . "&idp=" . fnEncode($qrBusca['COD_PEDIDO']) . "&pop=true' class='addBox text-center' data-title='Detalhes da Reserva'><small>" . $chkHospedes . "&nbsp;" . $chkVoucher . "&nbsp;" . $chkConfirma . "</small>
										</td>
										
										<td data-url='action.do?mod=" . fnEncode(2012) . "&id=" . fnEncode($cod_empresa) . "&idp=" . fnEncode($qrBusca['COD_PEDIDO']) . "&pop=true' class='addBox' data-title='Detalhes da Reserva'><small>" . $qrBusca['LOG_STATUSRESERVA'] . "</small>						
										</td>	

										<td data-url='action.do?mod=" . fnEncode(2012) . "&id=" . fnEncode($cod_empresa) . "&idp=" . fnEncode($qrBusca['COD_PEDIDO']) . "&pop=true' class='addBox' data-title='Detalhes da Reserva'><small>" . $qrBusca['ABV_STATUSPAG'] . "</small>
										$tdDropMenu									
										</td>	
										
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
										<td></td>
										<td class='text-right'><b>R$ <?= fnValor($countChale, 2) ?></b></td>
										<td class='text-right'><b>R$ <?= fnValor($countOpcionais, 2) ?></b></td>
										<td class='text-right'><b>R$ <?= fnValor($countReserva, 2) ?></b></td>
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
									url: "ajxBcReservaAdorai.do?opcao=exportar&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>",
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

	function concluiReserva(idReserva, codPedido) {

		$.ajax({
			url: "ajxBcReservaAdorai.do?opcao=CONFIR&idc=" + codPedido + "&id=<?php echo fnEncode($cod_empresa); ?>",
			method: 'POST',
			success: function(response) {
				window.location.reload();
			},
			error: function() {
				console.log("Erro ao realizar o procedimento.");
			}
		});
	}

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