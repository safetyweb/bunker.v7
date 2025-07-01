<?php

//echo "<h5>_".$opcao."</h5>";

$hotel = "";
$log_diaria = 'N';
$num_adultos = 2;
$num_criancas = 0;
$cod_hotel = "2957,3010,3008,956";
$num_pessoas = 0;
$filtro_data = "RESERVA";
$itens_por_pagina = 50;
$pagina = 1;
$ontem = '';
$dat_ini = "";
$dat_fim = "";

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

		$cod_statuspag = fnLimpaCampo($_POST['COD_STATUSPAG']);
		$cod_formapag = fnLimpaCampo($_POST['COD_FORMAPAG']);
		$cod_empresa = fnLimpaCampo($_POST['COD_EMPRESA']);
		$cod_propriedade = fnLimpaCampo($_POST['COD_PROPRIEDADE']);
		$cod_chale = fnLimpaCampo($_POST['COD_CHALE']);
		$dat_ini = fnDataSql($_POST['DAT_INI']);
		$dat_fim = fnDataSql($_POST['DAT_FIM']);
		$filtro_data = fnLimpaCampo($_POST['FILTRO_DATA']);

		// fnEscreve($cod_hotel);

		$nom_usuario = $_SESSION["SYS_NOM_USUARIO"];
		$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$MODULO = $_GET['mod'];
		$COD_MODULO = fndecode($_GET['mod']);

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		if ($opcao != '') {

			//mensagem de retorno
			switch ($opcao) {
				case 'CAD':
					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível cadastrar o registro : $cod_erro";
					}
					break;
				case 'ALT':
					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível alterar o registro : $cod_erro";
					}
					break;
				case 'EXC':
					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível excluir o registro : $cod_erro";
					}
					break;
			}
			if ($cod_erro == 0 || $cod_erro == "") {
				$msgTipo = 'alert-success';
			} else {
				$msgTipo = 'alert-danger';
			}
		}
	}
}


//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode($_GET['id']);
	$cod_empresa = 274;
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

				$abaManutencaoAdorai = fnDecode($_GET['mod']);
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
											<input type='text' class="form-control input-sm data" name="DAT_FIM" id="DAT_FIM" required value="<?= $dat_fim ?>" />
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
											<option value="RESERVA">Data do Pedido</option>
											<option value="DEFAULT">Checkin - Checkout</option>
										</select>
										<div class="help-block with-errors"></div>
										<script>
											$("#FILTRO_DATA").val("<?php echo $filtro_data; ?>").trigger("chosen:updated");
										</script>
									</div>
								</div>

								<div class="col-md-2">
									<div class="push20"></div>
									<button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-sm btn-block getBtn"><i class="fal fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
								</div>
							</div>

							<!--<div class="row">
								<div class="col-md-2">
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
								</div>

								<div class="col-md-10"></div>

								<div class="col-md-2">
									<div class="push20"></div>
									<button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-sm btn-block getBtn"><i class="fal fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
								</div>

							</div>
							 <div class="row">
								<div class="col-md-2">
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
								</div>

							<div class="col-xs-2">
									<div class="form-group">
										<label for="inputName" class="control-label ">Status de Pagamento</label>
										<select data-placeholder="Selecione o status" name="COD_STATUSPAG" id="COD_STATUSPAG" class="chosen-select-deselect" >
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
										<select data-placeholder="Selecione o status" name="COD_FORMAPAG" id="COD_FORMAPAG" class="chosen-select-deselect" >
											<option value=""></option>
											<?php
											$sql = "SELECT * FROM ADORAI_FORMAPAG WHERE COD_EXCLUSA IS NULL";
											$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

											while ($qrformapag = mysqli_fetch_assoc($arrayQuery)) {
											?>
												<option value="<?= $qrformapag['COD_FORMAPAG'] ?>"><?= $qrformapag['ABV_FORMAPAG'] ?></option>
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

							</div> -->

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
										<th class="text-center">Cod Carrinho</th>
										<th>Tel. Cliente</th>
										<th>Propriedade</th>
										<th>Chalé</th>
										<th>Data Pedido</th>
										<th>Check-in - Check-out</th>
										<th class='text-right'>Tot. Chalé</th>
										<th class='text-right' class="{ sorter: false }">Cupom</th>
										<th class='text-right'>Tot. Opcionais</th>
										<th class='text-right'>Tot. Reserva</th>
										<th width='40' class="{ sorter: false }"></th>
									</tr>
								</thead>
								<tbody id='div_refreshCarrinho'>

									<?php
									if ($cod_propriedade == "" or $cod_propriedade == 9999) {
										$and_propriedade = " ";
									} else {
										$and_propriedade = "AND ACI.COD_PROPRIEDADE = $cod_propriedade";
									}
									if ($cod_chale != "") {
										$and_chale = "AND ACI.COD_CHALE = $cod_chale";
									} else {
										$and_chale = " ";
									}

									// if($filtro_data == "ALTERACAO"){
									// 	$andDat = "AND ACI.DAT_ALTERAC >= '$dat_alterac 00:00:00'
									// 	AND ACI.DAT_ALTERAC >= '$dat_alterac 23:59:59'";

									// }else 
									if ($filtro_data == "DEFAULT") {
										$andDat = "	AND ACI.DAT_INICIAL >= '$dat_ini 00:00:00'
										AND ACI.DAT_FINAL <= '$dat_fim 23:59:59'";
									} else {
										$andDat = "AND ACI.DAT_CADASTR >= '$dat_ini 00:00:00'
										AND ACI.DAT_CADASTR <= '$dat_fim 23:59:59'";
									}

									$sql2 = "
									SELECT DISTINCT 
									AC.COD_CARRINHO,
									AC.TELEFONE,
									ACI.DAT_INICIAL,
									ACI.DAT_FINAL,
									ACI.VALOR,
									UNI.NOM_FANTASI,
									ACI.COD_PROPRIEDADE,
									ACI.COD_ITEM,
									SUM(ACP.valor) AS VALOR_OPCIONAIS,
									CH.NOM_QUARTO
									FROM adorai_carrinho AS AC
									INNER JOIN adorai_carrinho_items AS ACI ON ACI.COD_CARRINHO = ac.COD_CARRINHO
									INNER JOIN adorai_carrinho_opcionais as ACP ON ACO.COD_CARRINHO = ACI.COD_CARRINHO
									LEFT JOIN unidadevenda AS UNI ON UNI.cod_externo = ACI.COD_PROPRIEDADE
									LEFT JOIN adorai_chales AS CH ON CH.cod_externo = ACI.COD_CHALE
									WHERE
									AC.COD_EMPRESA = $cod_empresa 
									$andDat
									$and_propriedade
									$and_chale
									ORDER BY AC.COD_CARRINHO
									";

									$retorno = mysqli_query(connTemp($cod_empresa, ''), $sql2);

									$totalitens_por_pagina = mysqli_num_rows($retorno);

									// fnescreve($sql);
									$numPaginas = ceil($totalitens_por_pagina / $itens_por_pagina);

									// fnEscreve($numPaginas);

									//variavel para calcular o início da visualização com base na página atual
									$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

									$sql = "
									SELECT DISTINCT 
									AC.COD_CARRINHO,
									AC.COD_CUPOM,
									AC.DAT_CADASTR,
									AC.TELEFONE,
									ACI.DAT_INICIAL,
									ACI.DAT_FINAL,
									ACI.VALOR,
									UNI.NOM_FANTASI,
									ACI.COD_PROPRIEDADE,
									ACI.COD_ITEM,
									SUM(CASE WHEN OA.LOG_CORTESIA = 'N' THEN ACP.valor ELSE 0 END) AS VALOR_OPCIONAIS,
									CH.NOM_QUARTO
									FROM adorai_carrinho AS AC
									INNER JOIN adorai_carrinho_items AS ACI ON ACI.COD_CARRINHO = ac.COD_CARRINHO
									INNER JOIN adorai_carrinho_opcionais as ACP ON ACP.COD_CARRINHO = AC.COD_CARRINHO
									INNER JOIN unidadevenda AS UNI ON UNI.cod_externo = ACI.COD_PROPRIEDADE
									INNER JOIN adorai_chales AS CH ON CH.cod_externo = ACI.COD_CHALE
									INNER JOIN opcionais_adorai AS OA ON OA.COD_OPCIONAL = ACP.COD_OPCIONAL
									WHERE
									AC.COD_EMPRESA = $cod_empresa 
									$andDat
									$andStatusPag
									$andFormaPag
									$and_propriedade
									$and_chale
									GROUP BY AC.COD_CARRINHO
									ORDER BY AC.COD_CARRINHO
									";
									//fnEscreve($sql);
									$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
									$count = 0;

									while ($qrBusca = mysqli_fetch_assoc($arrayQuery)) {
										$count++;

										if ($qrBusca['COD_CUPOM'] == '') {
											$chkCupom = "";
										} else {
											$chkCupom = "<span class='fal fa-check text-success shortCut' data-toggle='tooltip' data-placement='top' data-original-title='" . $qrBusca['COD_CUPOM'] . "' id='shortRFH'></span>";
										}

										echo "
										<tr id='tr_$qrBusca[COD_CARRINHO]'>
										<td class='text-center'>" . $qrBusca['COD_CARRINHO'] . "</td>
										<td>" . fnmasktelefone($qrBusca['TELEFONE']) . "</td>
										<td>" . $qrBusca['NOM_FANTASI'] . "</td>														
										<td>" . $qrBusca['NOM_QUARTO'] . "</td>														
										<td>" . fnDataShort($qrBusca['DAT_CADASTR']) . "</td>					
										<td>" . fnDataShort($qrBusca['DAT_INICIAL']) . " - " . fnDataShort($qrBusca['DAT_FINAL']) . "</td>					
										<td class='text-right'>R$ " . fnValor($qrBusca['VALOR'], 2) . "</td>												
										<td class='text-right'>" . $chkCupom . "</td>												
										<td class='text-right'>R$ " . fnValor($qrBusca['VALOR_OPCIONAIS'], 2) . "</td>											
										<td class='text-right'>R$ " . fnValor($qrBusca['VALOR'] + $qrBusca['VALOR_OPCIONAIS'], 2) . "</td>											
										<td width='40' class='text-center'>
										<small>
										<div class='btn-group dropdown dropleft'>
										<a href='javascript:void(0)' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
										<span style='opacity: 0.4;' class='fal fa-ellipsis-v fa-2x'></span>
										</a>
										<ul class='dropdown-menu' role='menu' aria-labelledby='dropdownMenu'>
										<li><a href='javascript:void(0)' onclick='excluicarrinho($cod_empresa,$qrBusca[COD_CARRINHO],$qrBusca[COD_PROPRIEDADE],$qrBusca[COD_ITEM])'>Excluir </a></li>
										</ul>
										</div>
										</small>
										</td>
										</tr>
										
										";
									}
									?>

								</tbody>

								<div class="push20"></div>

								<tfoot>

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

</div>

<div class="push20"></div>

<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />

<script type="text/javascript">
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
									url: "ajxCheckoutAdorai.do?opcao=exportar&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>",
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

	$(document).ready(function() {
		var numPaginas = <?php echo $numPaginas; ?>;
		if (numPaginas != 0) {
			carregarPaginacao(numPaginas);
		}
	});


	function retornaForm(index) {
		$("#formulario #COD_STATUSPAG").val($("#ret_COD_STATUSPAG_" + index).val());
		$("#formulario #DES_STATUSPAG").val($("#ret_DES_STATUSPAG_" + index).val());
		$("#formulario #ABV_STATUSPAG").val($("#ret_ABV_STATUSPAG_" + index).val());
		$("#formulario #DES_COR").val($("#ret_DES_COR_" + index).val());
		$('#btnIcon').iconpicker('setIcon', $("#ret_DES_ICONE_" + index).val());
		$("#formulario #DES_ICONE").val($("#ret_DES_ICONE_" + index).val());
		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
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
	$("#DAT_FIM").val("<?= fnDataShort($dat_fim) ?>");

	/*$(document).ready( function() {

		$('input[name="DAT_RESERVA"]').daterangepicker({
			opens: 'bottom',
			autoApply: true,
			locale: { cancelLabel: 'Cancelar', applyLabel: 'Aplicar' }  
		}, function(start, end, label) {
		    //console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
		});
	});	*/

	// ajax
	$("#COD_PROPRIEDADE").change(function() {
		var codBusca = $("#COD_PROPRIEDADE").val();
		var codBusca3 = $("#COD_EMPRESA").val();
		buscaSubCat(codBusca, codBusca3);
	});


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

	/*function RefresCarrinho(cod_empresa, dat_ini, dat_fim) {
		$.ajax({
			type: "GET",
			url: "ajxCheckoutAdorai.do?opcao=REFRESH",
			data: { COD_EMPRESA: cod_empresa, DAT_INI: dat_ini, DAT_FIM: dat_fim},
			beforeSend:function(){
				$('#div_refreshCarrinho').html('<div class="loading" style="width: 100%;"></div>');
			},
			success:function(data){
				$("#div_refreshCarrinho").html(data); 
			},
			error:function(){
				$('#div_refreshCarrinho').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
			}
		});		
	}*/

	function excluicarrinho(idEmp, cod_carrinho, cod_propriedade, cod_item) {
		$.alert({
			title: "Alerta",
			type: 'orange',
			content: "Deseja mesmo excluir o carrinho <b>" + cod_carrinho + "</b>?<br>Essa ação não pode ser desfeita.",
			buttons: {
				"Sim": {
					btnClass: 'btn-danger',
					action: function() {
						$.ajax({
							type: "POST",
							url: "ajxCheckoutAdorai.do?opcao=EXC",
							data: {
								COD_EMPRESA: idEmp,
								COD_CARRINHO: cod_carrinho,
								COD_PROPRIEDADE: cod_propriedade,
								COD_ITEM: cod_item
							},
							success: function(data) {

								$("#tr_" + cod_carrinho).hide();
							},
							error: function() {
								$('#div_refreshCarrinho').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
							}
						});
					}
				},
				"Não": {
					action: function() {

					}
				}
			}
		});

	}
</script>