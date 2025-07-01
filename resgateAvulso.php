<?php

//echo fnDebug('true');

$hashLocal = mt_rand();

$tem_prodaux = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_cliente = fnLimpacampoZero($_REQUEST['COD_CLIENTE']);
		$val_resgate = fnLimpacampo($_REQUEST['VAL_RESGATE']);
		$cod_empresa = fnLimpacampoZero($_REQUEST['COD_EMPRESA']);
		$cod_univend = fnLimpacampoZero($_REQUEST['COD_UNIVEND']);
		$cod_produto = fnLimpacampoZero($_REQUEST['COD_PRODUTO']);
		$qtd_produto = fnLimpacampo($_REQUEST['QTD_PRODUTO']);
		$val_unitario = fnLimpacampo($_REQUEST['VAL_UNITARIO']);
		if (isset($_REQUEST['VAL_TOTPROD'])) {
			$val_totprod = fnLimpacampo($_REQUEST['VAL_TOTPROD']);
		}
		$des_comenta = fnLimpaCampo($_REQUEST['DES_COMENTA']);
		$casasDec = fnLimpaCampo($_REQUEST['CASAS_DEC']);

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		if ($opcao != '') {

			$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

			//verifica saldo atual					
			$sql = "CALL SP_CONSULTA_SALDO_CLIENTE('$cod_cliente')";

			$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
			$qrBuscaTotais = mysqli_fetch_assoc($arrayQuery);

			if (isset($arrayQuery)) {

				$credito_disponivel = $qrBuscaTotais['CREDITO_DISPONIVEL'];
			}

			// $val_resgateCompara = number_format((float)$val_resgate, 2, '.', '');
			// $credito_disponivelCompara = number_format((float)$credito_disponivel, 2, '.', '');
			$val_resgateCompara = fnValorSql($val_resgate);
			$credito_disponivelCompara = fnValorSql(fnValor($credito_disponivel, $casasDec));

			// fnEscreve($val_resgateCompara);
			// fnEscreve($credito_disponivelCompara);

			if ($val_resgateCompara > $credito_disponivelCompara) {
				//fnEscreve('entrou aqui');
				$msgRetorno = "Valor do resgate <strong>superior</strong> ao saldo <strong>disponível</strong>!";
				$msgTipo = 'alert-danger';
			} else {

				$sql1 = "CALL SP_DEBITA_CREDITO(
							'" . $cod_cliente . "',
							'" . fnValorSql($val_resgate) . "',
							'" . $cod_empresa . "',
							'" . $cod_usucada . "',   
							'" . $cod_univend . "',
							'" . $cod_produto . "',
							'" . fnValorSql($qtd_produto) . "',
							'" . fnValorSql($val_unitario) . "',
							'" . fnValorSql($val_resgate) . "'
						) ";

				$sql2 = "SELECT COUNT(COD_ESTOQUE) AS ESTOQUE, COD_ESTOQUE 
								FROM ESTOQUE_PRODUTO 
								WHERE COD_EMPRESA = $cod_empresa 
								AND COD_UNIVEND = $cod_univend 
								AND COD_PRODUTO = $cod_produto 
								AND QTD_ESTOQUE != 0";

				$arrayEst = mysqli_query(connTemp($cod_empresa, ''), $sql2);
				$qrEst = mysqli_fetch_assoc($arrayEst);

				if ($qrEst['ESTOQUE'] == 1) {

					$sql3 = "UPDATE ESTOQUE_PRODUTO SET
									QTD_ESTOQUE = (QTD_ESTOQUE-" . fnValorSql($qtd_produto) . ")
									WHERE COD_EMPRESA = $cod_empresa 
									AND COD_UNIVEND = $cod_univend
									AND COD_ESTOQUE =" . $qrEst['COD_ESTOQUE'];

					mysqli_query(connTemp($cod_empresa, ''), $sql3);
				}

				//fnEscreve($sql1);
				$qrBuscaRecibo = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa, ''), $sql1));
				$reciboVenda = $qrBuscaRecibo['V_COD_CREDITO'];

				if ($reciboVenda != '') {
					$comentariovenda = "INSERT INTO venda_info (COD_VENDA,
																		 COD_EMPRESA,
																		 COD_USUCADA,
																		 DES_TIPO,
																		 DES_COMENTA) 
																		 VALUES 
																		 (" . $reciboVenda . ", 
																		  $cod_empresa, 
																		  $cod_usucada, 
																		  '3',
																		  '" . addslashes($des_comenta) . "');";
					mysqli_query(connTemp($cod_empresa, ''), $comentariovenda);
				}

				//mensagem de retorno
				switch ($opcao) {
					case 'CAD':

						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
						if ($qtd_produto > 0) {
							$msgRetorno .= "<br> <a id='btnImprimirVoucher' class='addBox' data-url='action.php?mod=" . fnEncode(1250) . "&id=" . fnEncode($cod_empresa) . "&idR=" . fnEncode($reciboVenda) . "&idC=" . fnEncode(0) . "&pop=true' data-title='Recibo de Resgate'><b>Clique aqui &nbsp;</b></a> para imprimir o voucher";
						} else {
							$msgRetorno .= "<br> <a id='btnImprimirVoucher' class='addBox' data-url='action.php?mod=" . fnEncode(1250) . "&id=" . fnEncode($cod_empresa) . "&idR=" . fnEncode($reciboVenda) . "&idC=" . fnEncode($cod_cliente) . "&pop=true' data-title='Recibo de Resgate'><b>Clique aqui &nbsp;</b></a> para imprimir o voucher";
						}
						break;
					case 'ALT':
						$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";
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
}

//busca dados url
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode($_GET['id']);
	$cod_cliente = fnDecode($_GET['idC']);
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI, TIP_RETORNO,NUM_DECIMAIS_B, LOG_PONTUAR, TIP_CAMPANHA  FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
		$tip_retorno = $qrBuscaEmpresa['TIP_RETORNO'];
		$tip_campanha = $qrBuscaEmpresa['TIP_CAMPANHA'];
		$NUM_DECIMAIS_B = $qrBuscaEmpresa['NUM_DECIMAIS_B'];
		$log_pontuar = $qrBuscaEmpresa['LOG_PONTUAR'];

		if ($tip_retorno == 2) {
			$casasDec = $NUM_DECIMAIS_B;
			$readonly  = '';
		} else {
			$casasDec = '0';
			$readonly  = 'readonly';
		}
	}
} else {
	$cod_empresa = 0;
	$nom_empresa = "";
	$casasDec = 2;
}


//busca dados do cliente
$sql1 = "select count(1) as TEMPROMO from PRODUTOPROMOCAO 
		where COD_EMPRESA=$cod_empresa AND COD_EXCLUSA=0 AND LOG_ATIVO = 'S'";

//fnEscreve($sql1);
$arrayQuery = mysqli_query(connTemp($cod_empresa, ""), $sql1);
$qrBuscaProdPromocao = mysqli_fetch_assoc($arrayQuery);

if (isset($arrayQuery)) {
	$tempromo = $qrBuscaProdPromocao['TEMPROMO'];
} else {
	$tempromo = 0;
}

//busca dados do cliente
$sql = "SELECT NOM_CLIENTE, NUM_CARTAO, NUM_CGCECPF, COD_CLIENTE, LOG_TROCAPROD, LOG_FUNCIONA FROM CLIENTES where COD_CLIENTE = '" . $cod_cliente . "' ";
//fnEscreve($sql);
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
$qrBuscaCliente = mysqli_fetch_assoc($arrayQuery);

if (isset($arrayQuery)) {

	$nom_cliente = $qrBuscaCliente['NOM_CLIENTE'];
	$cod_cliente = $qrBuscaCliente['COD_CLIENTE'];
	$num_cartao = $qrBuscaCliente['NUM_CARTAO'];
	$num_cgcecpf = $qrBuscaCliente['NUM_CGCECPF'];
	$log_trocaprod = $qrBuscaCliente['LOG_TROCAPROD'];
	$log_funciona = $qrBuscaCliente['LOG_FUNCIONA'];
} else {

	$nom_cliente = "";
	$cod_cliente = "";
	$num_cartao = "";
	$num_cgcecpf = "";
	$log_trocaprod = "";
}

if ($log_trocaprod == 'N') {
	$msgRetorno = "Cliente <strong>bloqueado</strong> para trocas!";
	$msgTipo = 'alert-danger';
}

//busca saldo do cliente
$sql = "CALL `SP_CONSULTA_SALDO_CLIENTE`('$cod_cliente')";

//fnEscreve($sql);
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
$qrBuscaTotais = mysqli_fetch_assoc($arrayQuery);


if (isset($arrayQuery)) {

	$credito_disponivel = $qrBuscaTotais['CREDITO_DISPONIVEL'];
}

//fnMostraForm();
//fnEscreve($tempromo);
//fnEscreve($reciboVenda);

$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

$sql3 = "SELECT COD_UNIVEND FROM USUARIOS WHERE COD_USUARIO = $cod_usucada";
$arrayQuery3 = mysqli_query($connAdm->connAdm(), $sql3);

//fnEscreve($sql3);

$qrUs = mysqli_fetch_assoc($arrayQuery3);

$codUnivend_usu = $qrUs['COD_UNIVEND'];

$pontuar = "S";
//se empresa pontua funcionário
if ($log_pontuar == 'S') {
	$pontuar = "S";
	$creditou = 0;
} else {
	//se cliente é funcionario
	if ($log_funciona == 'S') {
		$pontuar = "N";
		$creditou = 4;
	} else {
		$pontuar = "S";
		$creditou = 0;
	}
}

//se GEF sempre pontua funcionário
if ($cod_empresa == 119) {
	if ($log_funciona == 'S') {
		$pontuar = "S";
		$creditou = 0;
	}
}

//fnEscreve($pontuar);

?>
<style>
	.widget .widget-title {
		font-size: 14px;
	}

	.widget .widget-int {
		font-size: 20px;
		padding: 0 0 10px 0;
	}

	.widget .widget-item-left .fa,
	.widget .widget-item-right .fa,
	.widget .widget-item-left .glyphicon,
	.widget .widget-item-right .glyphicon {
		font-size: 35px;
	}

	#btnImprimirVoucher {
		color: #1d22ea;
	}

	#btnImprimirVoucher:hover {
		color: #5c5ef0;
		cursor: pointer;
		text-decoration: none;
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
					<span class="text-primary"> <?php echo $NomePg; ?></span>
				</div>

				<?php
				$formBack = "1015";
				include "atalhosPortlet.php";
				?>

			</div>
			<div class="portlet-body">

				<?php if ($msgRetorno <> '') { ?>
					<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<?php echo $msgRetorno; ?>
					</div>
				<?php }

				if ($pontuar ==  'N') { ?>

					<div class="alert alert-warning alert-dismissible" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
						Cliente é funcionário. <br />
						Resgate <b>não</b> permitido. <br />
					</div>
				<?php } ?>


				<?php
				//menu superior - cliente
				$abaCli = 1173;
				switch ($_SESSION["SYS_COD_SISTEMA"]) {
					case 14: //rede duque
						include "abasClienteDuque.php";
						break;
					case 13: //sh manager
						include "abasIntegradoraCli.php";
						break;
					default;
						include "abasClienteConfig.php";
						break;
				}
				?>

				<div class="push30"></div>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Dados Gerais</legend>

							<div class="row">

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Código do Cliente</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_CLIENTE" id="COD_CLIENTE" value="<?php echo $cod_cliente; ?>">
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Empresa</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa ?>">
										<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>" required>
									</div>
								</div>

								<div class="col-md-5">
									<label for="inputName" class="control-label required">Nome do Usuário</label>
									<div class="input-group">
										<span class="input-group-btn">
											<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary btn-sm addBox" data-url="action.php?mod=<?php echo fnEncode(1071) ?>&id=<?php echo fnEncode($cod_empresa) ?>&pop=true" data-title="Venda Avulsa - Busca Clientes"><i class="fa fa-search" aria-hidden="true" style="padding-top: 3px;"></i></a>
										</span>
										<input type="text" name="NOM_CLIENTE" id="NOM_CLIENTE" class="form-control input-sm leituraOff" style="border-radius:0 3px 3px 0;" placeholder="Procurar cliente..." value="<?php echo $nom_cliente; ?>">
										<input type="hidden" name="COD_CLIENTE" id="COD_CLIENTE" value="<?php echo $cod_cliente; ?>" required>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Número do Cartão</label>
										<input type="text" class="form-control input-sm leitura" name="NUM_CARTAO" id="NUM_CARTAO" value="<?php echo $num_cartao; ?>" maxlength="50" data-error="Campo obrigatório" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>

							</div>

						</fieldset>

						<div class="push30"></div>

						<div class="row">

							<style>
								.chosen-container {
									font-size: 16px;
								}

								.chosen-container-single .chosen-single {
									height: 45px;
								}

								.chosen-container-single .chosen-single span {
									margin-top: 5px;
								}
							</style>

							<div class="col-md-6">
								<div class="form-group">
									<label for="inputName" class="control-label required">Unidade de Atendimento </label>
									<select data-placeholder="Selecione a unidade de atendimento" name="COD_UNIVEND" id="COD_UNIVEND" class="chosen-select-deselect requiredChk" required>
										<option value=""></option>
										<?php

										if ($_SESSION["SYS_COD_EMPRESA"] != $cod_empresa) {
											$sql = "SELECT COD_UNIVEND, NOM_FANTASI FROM UNIDADEVENDA WHERE COD_EMPRESA = $cod_empresa AND (LOG_ESTATUS = 'S' OR COD_EXCLUSA IS NULL) ORDER BY NOM_UNIVEND";
										} else {
											$sql = "SELECT COD_UNIVEND, NOM_FANTASI FROM UNIDADEVENDA WHERE COD_EMPRESA = $cod_empresa AND COD_UNIVEND IN($codUnivend_usu) AND (LOG_ESTATUS = 'S' OR COD_EXCLUSA IS NULL) ORDER BY NOM_UNIVEND";
										}

										$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

										while ($qrListaUnidade = mysqli_fetch_assoc($arrayQuery)) {
											echo "
														  <option value='" . $qrListaUnidade['COD_UNIVEND'] . "'>" . $qrListaUnidade['NOM_FANTASI'] . "</option> 
														";
										}
										?>
									</select>
									<div class="help-block with-errors"></div>
									<?php //fnEscreve($sql); 
									?>
								</div>
							</div>

							<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
								<div class="form-group">
									<label for="inputName" class="control-label">Saldo Disponível</label>
									<input type="text" class="form-control text-center leituraOff calcula" readOnly="readonly" name="VAL_DISPONIVEL" id="VAL_DISPONIVEL" tabindex="1" value="<?php echo fnValor($credito_disponivel, $casasDec); ?>">
								</div>
							</div>

							<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
								<div class="form-group">
									<label for="inputName" class="control-label">Valor do Resgate </label>
									<input type="text" class="form-control text-center decimaisEmp calcula" name="VAL_RESGATE" id="VAL_RESGATE" tabindex="1" value="" <?= $readonly ?>>
								</div>
							</div>

							<?php
							if ($tempromo > 0) {

								$valor_max = fnValorSql(fnValor($credito_disponivel, 0));

							?>

								<div class="push10"></div>

								<div class="col-md-6">
									<div class="form-group">
										<label for="inputName" class="control-label required">Produto Promocional </label>
										<div id="divProdPromo"></div>
										<select data-placeholder="Selecione um produto promocional" name="COD_PRODUTO" id="COD_PRODUTO" class="chosen-select-deselect requiredChk" required>
											<option value=""></option>
										</select>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
									<div class="form-group">
										<label for="inputName" class="control-label required">Quantidade do Produto </label>
										<input type="text" class="form-control text-center calcula decimaisEmp" name="QTD_PRODUTO" id="QTD_PRODUTO" tabindex="1" value="1" required>
									</div>
								</div>

								<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
									<div class="form-group">
										<label for="inputName" class="control-label required">Valor Unitário </label>
										<input type="text" class="form-control text-center calcula decimaisEmp leitura" readOnly="readonly" name="VAL_UNITARIO" id="VAL_UNITARIO" tabindex="1" value="" required>
									</div>
								</div>

								<div class="push10"></div>

								<div class="col-md-6">
									<div class="form-group">
										<label for="inputName" class="control-label required">Comentário</label>
										<input type="text" class="form-control input-sm" name="DES_COMENTA" id="DES_COMENTA" value="" maxlength="50" required>
										<div class="help-block with-errors">máx 50 caracteres</div>
									</div>
								</div>


							<?php
							} else {
							?>

								<div class="push10"></div>

								<div class="col-md-6">
									<div class="form-group">
										<label for="inputName" class="control-label required">Comentário</label>
										<input type="text" class="form-control input-sm " name="DES_COMENTA" id="DES_COMENTA" value="" maxlength="50" required>
										<div class="help-block with-errors">máx 50 caracteres</div>
									</div>
								</div>

								<input type="hidden" name="COD_PRODUTO" id="COD_PRODUTO" value="0">
								<input type="hidden" name="QTD_PRODUTO" id="QTD_PRODUTO" value="0">
								<input type="hidden" name="VAL_UNITARIO" id="VAL_UNITARIO" value="0">
								<input type="hidden" name="VAL_TOTPROD" id="VAL_TOTPROD" value="0">

							<?php
							}
							?>


						</div>

						<div class="row">

							<div class="push10"></div>
							<hr>
							<div class="form-group text-right col-lg-12">

								<button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>

								<?php
								//funcionário não pode resgatar
								if ($pontuar == "N") {
								?>
									<button type="button" name="CAD2" id="CAD2" class="btn btn-primary" tabindex="5" disabled><i class="fal fa-usd" aria-hidden="true"></i>&nbsp; Efetuar Resgate</button>
								<?php
								} else {
								?>
									<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn" tabindex="5"><i class="fal fa-usd" aria-hidden="true"></i>&nbsp; Efetuar Resgate</button>
								<?php
								}
								?>

							</div>

							<input type="hidden" name="NOVO_CLIENTE" id="NOVO_CLIENTE" value="">
							<input type="hidden" name="CASAS_DEC" id="CASAS_DEC" value="<?= $casasDec ?>">
							<input type="hidden" name="REFRESH_CLIENTE" id="REFRESH_CLIENTE" value="N">
							<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
							<input type="hidden" name="QTD_ESTOQUE" id="QTD_ESTOQUE" value="0">
							<input type="hidden" name="opcao" id="opcao" value="">
							<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
							<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">


					</form>
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

	<script type="text/javascript">
		let casasDec = "<?= $casasDec ?>",
			tipCampanha = "<?= $tip_campanha ?>";

		$(document).ready(function() {
			if (tipCampanha == 13) {
				if (casasDec == 2) {
					$('.decimaisEmp').mask("#.##0,00", {
						reverse: true
					});
				} else if (casasDec == 3) {
					$('.decimaisEmp').mask("#.##0,000", {
						reverse: true
					});
				} else if (casasDec == 4) {
					$('.decimaisEmp').mask("#.##0,0000", {
						reverse: true
					});
				} else if (casasDec == 5) {
					$('.decimaisEmp').mask("#.##0,00000", {
						reverse: true
					});
				} else {
					$('.decimaisEmp').addClass("int");
				}
			} else {
				$('.decimaisEmp').addClass("int");
			}
			//chosen
			$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
			$('#formulario').validator();

			if ('<?= $log_trocaprod ?>' == 'N') {
				$('#COD_UNIVEND').attr('disabled', 'disabled');
				$('#COD_PRODUTO').attr('disabled', 'disabled');
				$('#VAL_RESGATE').attr('disabled', 'disabled');
				$('#QTD_PRODUTO').attr('disabled', 'disabled');
			}

			//modal close
			$('.modal').on('hidden.bs.modal', function() {
				if ($('#REFRESH_CLIENTE').val() == "S") {
					var newCli = $('#NOVO_CLIENTE').val();
					window.location.href = "action.php?mod=<?php echo fnEncode(1173); ?>&id=<?php echo fnEncode($cod_empresa); ?>&idC=" + newCli + " ";
					$('#REFRESH_PRODUTOS').val("N");
				}
			});


			$("#COD_PRODUTO").change(function() {
				var cod_produto = $(this).val();

				$(this).find('option').each(function(index) {

					if ($(this).attr('cod-option') == cod_produto) {
						$('#VAL_UNITARIO').val($(this).attr('num-pontos'));
						return false;
					}
				});

				calcularTotal();
				//$("#CAD").removeClass("disabled");

				// if(cod_produto != ''){

				// 	$.ajax({
				// 		method: 'POST',
				// 		url: 'ajxCalculaEstoque.php',
				// 		data: {COD_PRODUTO:cod_produto, COD_EMPRESA:<?= $cod_empresa ?>},
				// 		success:function(data){
				// 			if(data == 0){
				// 				$.alert({
				// 					title: 'Atenção',
				// 					content: 'Produto indisponível em estoque.',
				// 				});
				// 				$("#QTD_ESTOQUE").val(0);
				// 				$("#formulario #COD_PRODUTO").val('').trigger("chosen:updated");
				// 				// $('#VAL_UNITARIO').val('');
				// 			}else{

				// 				$("#QTD_ESTOQUE").val(data);



				// 			}
				// 		}
				// 	});

				// }

			});


			// $("#QTD_PRODUTO").keyup(function() {

			// 	var qtd_produto = $('#QTD_PRODUTO').val();
			// 	var estoque = parseInt($('#QTD_ESTOQUE').val());

			// 	if(qtd_produto != 0 && qtd_produto > estoque){

			// 		$.alert({
			// 			title: 'Atenção',
			// 			content: 'Quantidade em estoque: '+estoque,
			// 		});

			// 		$('#QTD_PRODUTO').val(estoque);

			// 	}

			// });

			$("#QTD_PRODUTO").change(function() {
				calcularTotal();
			});

			$("#VAL_RESGATE").change(function() {
				if ($('#VAL_TOTPROD').val() != $(this).val() && $("#COD_PRODUTO").val().trim() != 0) {
					$.alert({
						title: 'Atenção',
						content: 'Valor do resgate deve ser igual ao valor total!',
					});
					$(this).val('');
				}
			});

			// ajax
			$("#COD_UNIVEND").change(function() {
				var codUnidade = $("#COD_UNIVEND").val();
				var codEmpresa = $("#COD_EMPRESA").val();
				var codValor = "<?php echo $credito_disponivel; ?>";
				buscaProdutoPromo(codUnidade, codEmpresa, codValor);
			});

			function buscaProdutoPromo(idUni, idEmp, idValor) {

				$.ajax({
					type: "GET",
					url: "ajxResgateAvulso.php",
					data: {
						ajx1: idUni,
						ajx2: idEmp,
						ajx3: idValor
					},
					beforeSend: function() {
						$("#COD_PRODUTO_chosen").hide();
						$('#divProdPromo').html('<div class="loading" style="width: 100%;"></div>');
					},
					success: function(data) {
						$('#divProdPromo').html("");
						$("#COD_PRODUTO").html(data).trigger("chosen:updated");;
						$("#COD_PRODUTO_chosen").show();
						//console.log(data);
					},
					error: function() {
						$('#divProdPromo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
					}
				});

			}


		});

		function calcularTotal() {
			var valor = $('#VAL_UNITARIO').cleanVal() * $('#QTD_PRODUTO').cleanVal();
			console.log(valor);

			if (casasDec == 0) {
				$('#VAL_RESGATE').val(valor);
				// if (valor !=0) {
				// 	$('#VAL_RESGATE').attr('disabled','disabled');
				// }else{
				// 	$('#VAL_RESGATE').removeAttr('disabled','disabled');
				// }	
			} else {
				// $('#VAL_RESGATE').unmask();
				$('#VAL_RESGATE').unmask().val(valor);
				if (tipCampanha == 13) {
					if (casasDec == 2) {
						$('.decimaisEmp').mask("#.##0,00", {
							reverse: true
						});
					} else if (casasDec == 3) {
						$('.decimaisEmp').mask("#.##0,000", {
							reverse: true
						});
					} else if (casasDec == 4) {
						$('.decimaisEmp').mask("#.##0,0000", {
							reverse: true
						});
					} else if (casasDec == 5) {
						$('.decimaisEmp').mask("#.##0,00000", {
							reverse: true
						});
					} else {
						$('.decimaisEmp').addClass("int");
					}
				} else {
					$('.decimaisEmp').addClass("int");
				}
				// if (valor !=0) {
				// 	$('#VAL_RESGATE').attr('disabled','disabled');
				// }else{
				// 	$('#VAL_RESGATE').removeAttr('disabled','disabled');
				// }	 
			}

		}
	</script>