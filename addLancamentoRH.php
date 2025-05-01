<?php

//echo "<h5>_".$opcao."</h5>";

$hashLocal = mt_rand();
if (isset($_GET['idx'])) {
	$cod_caixa = fnDecode($_GET['idx']);
} else {
	$cod_caixa = 0;
}

if (isset($_GET['idm'])) {
	$cod_mes = fnDecode($_GET['idm']);
} else {
	$cod_mes = 0;
}
if (isset($_GET['idd'])) {
	$num_dia = fnDecode($_GET['idd']);
} else {
	$num_dia = 0;
}

$cod_modulo = fnDecode($_GET['mod']);
//tipo de lançamento	
switch ($cod_modulo) {
	case 1705: //folha de pagamento
		$tip_lancame = "F";
		$sql_lancame = "AND LOG_LANCAME = 'F' ";
		break;
	case 1718: //bonificação
		$tip_lancame = "B";
		$sql_lancame = " ";
		break;
	case 1720: //bonificação
		$tip_lancame = "B";
		$sql_lancame = " ";
		break;
	case 2024: //adorai
		$tip_lancame = "F";
		$sql_lancame = " ";
		$cod_mes = 0;
		break;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_caixa = fnLimpaCampoZero(fnDecode($_REQUEST['COD_CAIXA']));
		$cod_cliente = fnLimpaCampoZero($_REQUEST['COD_CLIENTE']);
		$cod_empresa = fnLimpaCampo($_REQUEST['COD_EMPRESA']);
		$dat_lancame = fnDataSql($_REQUEST['DAT_LANCAME']);
		$cod_tipo = fnLimpacampozero($_REQUEST['COD_TIPO']);
		$cod_mes = fnLimpacampozero($_REQUEST['COD_MES']);
		$val_credito = fnValorSql($_REQUEST['VAL_CREDITO']);
		$pct_extra = fnValorSql($_REQUEST['PCT_EXTRA']);
		$num_dias = fnLimpacampozero($_REQUEST['NUM_DIA']);
		$cod_conta = fnLimpacampozero($_REQUEST['COD_CONTA']);
		$des_coment = fnLimpaCampo($_REQUEST['DES_COMENT']);

		$cod_usucada = $_SESSION['SYS_COD_USUARIO'];

		$newDate = explode('/', $_REQUEST['DAT_LANCAME']);
		$dia = $newDate[0];
		$mes   = $newDate[1];
		$ano  = $newDate[2];

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		if ($opcao != '') {

?>
			<script type="text/javascript">
				parent.$("#REFRESH_LANCAMENTO").val("S");
			</script>
<?php

			//mensagem de retorno
			switch ($opcao) {
				case 'CAD':

					$sql = "INSERT INTO CAIXA(
												COD_EMPRESA,
												COD_CONTRAT,
												DAT_LANCAME,
												COD_MES,
												MES,
												ANO,
												COD_TIPO,
												VAL_CREDITO,
												TIP_LANCAME,
												PCT_EXTRA,
												DES_COMENT,
												COD_USUCADA,
												COD_CONTA,
												NUM_DIA
											) VALUES(
												$cod_empresa,
												$cod_cliente,
												'$dat_lancame',
												$cod_mes,
												$mes,
												$ano,
												$cod_tipo,
												'$val_credito',
												'$tip_lancame',
												'$pct_extra',
												'$des_coment',
												$cod_usucada,
												$cod_conta,
												$num_dias
											)";

					//echo($sql);

					mysqli_query(connTemp($cod_empresa, ''), $sql);

					// Adicionado por Lucas 24/04/2024 Adorai
					if ($cod_modulo == 2024) {
						//busca descontos
						$sqlADPedido = "SELECT VALOR_PEDIDO, DESCONTO_PIX, VAL_CUPOM, COD_CUPOM FROM adorai_pedido WHERE COD_PEDIDO = $cod_cliente";

						$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sqlADPedido);

						if ($qrBusca = mysqli_fetch_assoc($arrayQuery)) {
							$val_chale = $qrBusca['VALOR_PEDIDO'];

							//desconto removido por solicitação da adorai
							// $desconto_pix = 0;
							// if (isset($qrBusca['DESCONTO_PIX'])) {
							// 	$desconto_pix = $qrBusca['DESCONTO_PIX'];
							// }

							$val_cupom = 0;
							if (isset($qrBusca['COD_CUPOM']) && $qrBusca['VAL_CUPOM'] != 0) {
								$val_cupom = $qrBusca['VAL_CUPOM'];
							}

							$sqlOpcionais = "SELECT SUM(VALOR) as VAL_OPCIONAIS FROM adorai_pedido_opcionais ap
												INNER JOIN opcionais_adorai AS op ON op.COD_OPCIONAL = ap.COD_OPCIONAL
												WHERE cod_pedido = $cod_cliente AND LOG_CORTESIA = 'N'
												AND ap.COD_EXCLUSA IS NULL";
							$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sqlOpcionais);

							$val_opcionais = 0;

							if ($qrBuscaOpc = mysqli_fetch_assoc($arrayQuery)) {
								$val_opcionais = $qrBuscaOpc['VAL_OPCIONAIS'];
							}

							$val_contrato = ($val_chale + $val_opcionais) - $val_cupom;

							$sqlLancamentosCred = "SELECT SUM(CX.VAL_CREDITO) AS VAL_CREDITO
							FROM CAIXA AS CX
							INNER JOIN TIP_CREDITO AS TC ON TC.COD_TIPO = CX.COD_TIPO
							WHERE CX.COD_CONTRAT = $cod_cliente
							AND TC.TIP_OPERACAO = 'C'";

							$query = mysqli_query(connTemp($cod_empresa, ''), $sqlLancamentosCred);
							$creditos = 0;
							if ($qrBuscaLanca = mysqli_fetch_assoc($query)) {
								$creditos = $qrBuscaLanca['VAL_CREDITO'];
							}

							if ($creditos == $val_contrato) {
								$sqlUpdate = "UPDATE ADORAI_PEDIDO SET 
								COD_STATUSPAG = 6
								WHERE COD_PEDIDO = $cod_cliente";
							} else {
								$sqlUpdate = "UPDATE ADORAI_PEDIDO SET 
								COD_STATUSPAG = 5
							 	WHERE COD_PEDIDO = $cod_cliente";
							}

							// fnEscreve($sqlUpdate);

							mysqli_query(connTemp($cod_empresa, ''), $sqlUpdate);
						}
					}

					$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					break;
				case 'ALT':

					$sql = "UPDATE CAIXA SET
												DAT_LANCAME='$dat_lancame',
												COD_TIPO='$cod_tipo',
												VAL_CREDITO='$val_credito',
												NUM_DIA = '$num_dias',
												PCT_EXTRA = '$pct_extra',
												DAT_ALTERAC=CONVERT_TZ(NOW(),'America/Sao_Paulo','America/Sao_Paulo'),
												COD_CONTA=$cod_conta,
												COD_ALTERAC = $cod_usucada
								WHERE COD_EMPRESA = $cod_empresa
								AND COD_CAIXA = $cod_caixa";

					mysqli_query(connTemp($cod_empresa, ''), $sql);

					$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";
					break;
				case 'EXC':

					$sql = "UPDATE CAIXA SET
												DAT_EXCLUSA = CONVERT_TZ(NOW(),'America/Sao_Paulo','America/Sao_Paulo'),
												COD_EXCLUSA = $cod_usucada
								WHERE COD_EMPRESA = $cod_empresa
								AND COD_CAIXA = $cod_caixa";

					mysqli_query(connTemp($cod_empresa, ''), $sql);

					$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";
					break;
					break;
			}
			$msgTipo = 'alert-success';
		}
	}
}


//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode($_GET['id']);
	$cod_cliente = fnDecode($_GET['idc']);
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
	}
} else {
	$cod_empresa = 0;
	//fnEscreve('entrou else');
}


if ($cod_caixa != 0) {

	$sqlCx = "SELECT * FROM CAIXA WHERE COD_EMPRESA = $cod_empresa AND COD_CAIXA = $cod_caixa";
	$arrayCx = mysqli_query(connTemp($cod_empresa, ''), $sqlCx);
	$qrCx = mysqli_fetch_assoc($arrayCx);

	$dat_lancame = fnDataShort($qrCx['DAT_LANCAME']);
	$cod_tipo = $qrCx['COD_TIPO'];
	$val_credito = fnValor($qrCx['VAL_CREDITO'], 2);
	$num_dia = $qrCx['NUM_DIA'];
	$cod_conta = $qrCx['COD_CONTA'];
	$pct_extra = fnValor($qrCx['PCT_EXTRA'], 2);
	if ($cod_tipo == 4) {
		$dpDias = "none";
		$dpPerc = "block";
	} else {
		$dpDias = "block";
		$dpPerc = "none";
	}
} else {
	$dat_lancame = fnDataShort(date("Y-m-d"));
	$cod_tipo = "";
	$val_credito = "";
	$dpDias = "block";
	$dpPerc = "none";
}

$sqlMes = "SELECT DAT_INI, DAT_FIM FROM MES_CAIXA WHERE COD_EMPRESA = $cod_empresa AND COD_MES = $cod_mes";

$arrayMes = mysqli_query(connTemp($cod_empresa, ''), $sqlMes);
$qrMes = mysqli_fetch_assoc($arrayMes);

$dat_ini = $qrMes['DAT_INI'];
$dat_fim = $qrMes['DAT_FIM'];

if ($cod_modulo == 2024) {
	$dat_ini = date("Y-m-d");
	$dat_fim = date("Y-m-d", strtotime("+1 month"));
}

// fnEscreve($sqlMes);
// fnEscreve($dat_fim);

?>

<div class="push30"></div>

<div class="row">

	<div class="col-md-12 margin-bottom-30">
		<!-- Portlet -->
		<?php if ($popUp != "true") {  ?>
			<div class="portlet portlet-bordered">
			<?php } else { ?>
				<div class="portlet" style="padding: 0 20px 20px 20px;">
				<?php } ?>

				<?php if ($popUp != "true") {  ?>
					<div class="portlet-title">
						<div class="caption">
							<i class="glyphicon glyphicon-calendar"></i>
							<span class="text-primary"><?php echo $NomePg; ?></span>
						</div>
						<?php include "atalhosPortlet.php"; ?>
					</div>
				<?php } ?>
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
								<legend>Dados do Lançamento</legend>

								<div class="row">

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label required">Data do Lançamento</label>

											<div class="input-group date datePicker" id="DAT_INI_GRP">
												<input type='text' class="form-control input-sm data" name="DAT_LANCAME" id="DAT_LANCAME" value="<?= $dat_lancame ?>" required />
												<span class="input-group-addon">
													<span class="glyphicon glyphicon-calendar"></span>
												</span>
											</div>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label required">Tipo</label>
											<select data-placeholder="Selecione o tipo" name="COD_TIPO" id="COD_TIPO" class="chosen-select-deselect" style="width:100%;" required>
												<option value=""></option>

												<optgroup label="Créditos">

													<?php

													$andTipo = "AND COD_TIPO NOT IN(1,2)";

													if ($cod_modulo == 2024) {
														$andTipo = "";
													}

													$sqlTipo = "SELECT COD_TIPO, DES_TIPO 
																						FROM TIP_CREDITO 
																						WHERE COD_EMPRESA = $cod_empresa
																						$andTipo
																						AND TIP_OPERACAO = 'C'
																						AND COD_EXCLUSA = 0
																						$sql_lancame
																						ORDER BY DES_TIPO";

													$arrayTipo = mysqli_query(connTemp($cod_empresa, ''), $sqlTipo);
													while ($qrTipo = mysqli_fetch_assoc($arrayTipo)) {
													?>

														<option value="<?= $qrTipo['COD_TIPO'] ?>"><?= $qrTipo['DES_TIPO'] ?></option>

													<?php
													}
													?>
												</optgroup>
												<optgroup label="Débitos">
													<?php

													$sqlTipo = "SELECT COD_TIPO, DES_TIPO 
																						FROM TIP_CREDITO 
																						WHERE COD_EMPRESA = $cod_empresa
																						$andTipo
																						AND TIP_OPERACAO = 'D'
																						AND COD_EXCLUSA = 0
																						ORDER BY DES_TIPO";

													$arrayTipo = mysqli_query(connTemp($cod_empresa, ''), $sqlTipo);
													while ($qrTipo = mysqli_fetch_assoc($arrayTipo)) {
													?>

														<option value="<?= $qrTipo['COD_TIPO'] ?>"><?= $qrTipo['DES_TIPO'] ?></option>

													<?php
													}
													?>
												</optgroup>

											</select>
											<div class="help-block with-errors"></div>
											<script type="text/javascript">
												$("#COD_TIPO").val("<?= $cod_tipo ?>").trigger("chosen:updated");
											</script>
										</div>
									</div>

									<?php if ($cod_modulo != 2024) { ?>

										<div class="col-md-2" id="dias" style="display: <?= $dpDias ?>;">
											<div class="form-group">
												<label for="inputName" class="control-label required">Dias</label>
												<input type="tel" class="form-control input-sm int" name="NUM_DIA" id="NUM_DIA" maxlength="3" value="<?= $num_dia ?>" required>
												<div class="help-block with-errors"></div>
											</div>
										</div>

									<?php } else { ?>
										<input type="hidden" name="NUM_DIA" id="NUM_DIA" maxlength="3" value="0">
									<?php } ?>

									<div class="col-md-2" id="pct" style="display: <?= $dpPerc ?>;">
										<div class="form-group">
											<label for="inputName" class="control-label">Percentual</label>
											<input type="text" class="form-control input-sm money" name="PCT_EXTRA" id="PCT_EXTRA" value="<?= $pct_extra ?>">
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Valor (R$)</label>
											<input type="text" class="form-control input-sm text-center money" name="VAL_CREDITO" id="VAL_CREDITO" value="<?= $val_credito ?>" required>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<?php if ($cod_empresa == 274) { ?>

										<div class="col-xs-4">
											<div class="form-group">
												<label for="inputName" class="control-label required lbl_req">Conta Bancária</label>
												<select data-placeholder="Selecione a conta de recebimento" name="COD_CONTA" id="COD_CONTA" class="chosen-select-deselect requiredChk" required>
													<option value=""></option>
													<?php
													$sqlPedido = "SELECT * FROM ADORAI_PEDIDO_ITEMS WHERE COD_PEDIDO = $cod_cliente";
													$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sqlPedido);
													$qrResult = mysqli_fetch_assoc($arrayQuery);

													$cod_propriedade = $qrResult['COD_PROPRIEDADE'];

													$sql = "SELECT * FROM CONTABANCARIA WHERE COD_EMPRESA = 274 ORDER BY NOM_BANCO";

													$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
													$linhas = mysqli_num_rows($arrayQuery);

													while ($qrListaConta = mysqli_fetch_assoc($arrayQuery)) {
														if ($linhas == 1 || $qrListaConta['LOG_DEFAULT'] == 'S') {
															$selected = "selected";
														} else {
															$selected = "";
														}

														$codPropriedadesArray = explode(',', $qrListaConta['COD_PROPRIEDADE']);

														if (in_array($cod_propriedade, $codPropriedadesArray) || in_array('9999', $codPropriedadesArray)) {

															echo "
															<option value='" . $qrListaConta['COD_CONTA'] . "'$selected>" . $qrListaConta['NOM_BANCO'] . "</option> 
															";
														}
													}
													?>
												</select>
												<script>
													$("#formulario #COD_CONTA").val("<?php echo @$cod_conta; ?>").trigger("chosen:updated");
												</script>
												<div class="help-block with-errors"></div>
											</div>
										</div>

									<?php } ?>

								</div>


								<?php if ($cod_empresa == 274) { ?>
									<div class="row">
										<div class="col-xs-12">
											<div class="form-group">
												<label for="inputName" class="control-label">Observação do Lançamento</label>
												<input type="text" class="form-control input-sm" name="DES_COMENT" id="DES_COMENT" value="" maxlength="250" data-error="Campo obrigatório">
												<div class="help-block with-errors"></div>
											</div>
										</div>
									</div>
								<?php } ?>

							</fieldset>

							<div class="push10"></div>
							<hr>
							<div class="form-group text-right col-lg-12">

								<button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
								<?php if ($cod_caixa != 0) { ?>
									<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>
									<button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fal fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>
								<?php } else { ?>
									<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
								<?php } ?>

							</div>

							<input type="hidden" name="opcao" id="opcao" value="">
							<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?= $cod_empresa ?>">
							<input type="hidden" name="COD_CLIENTE" id="COD_CLIENTE" value="<?= $cod_cliente ?>">
							<input type="hidden" name="COD_MES" id="COD_MES" value="<?= $cod_mes ?>">
							<input type="hidden" name="COD_CAIXA" id="COD_CAIXA" value="<?= fnEncode($cod_caixa) ?>">
							<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
							<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

							<div class="push5"></div>

							<?php
							//busca total líquido
							$sqlTotLiq = "SELECT COD_MES FROM MES_CAIXA WHERE COD_EMPRESA = $cod_empresa ORDER BY DAT_FIM DESC LIMIT 1";

							$sqlTotLiq = "SELECT
													   IFNULL(SUM(case when TIP_CREDITO.TIP_OPERACAO ='C' then
													   CAIXA.VAL_CREDITO 
													  END),0) -
													   IFNULL(SUM(case when TIP_CREDITO.TIP_OPERACAO ='D' then
													   CAIXA.VAL_CREDITO 
													  END),0) VAL_LIQUIDO
													  
													
												FROM CAIXA
												LEFT JOIN TIP_CREDITO ON caixa.COD_TIPO=TIP_CREDITO.COD_TIPO
												WHERE CAIXA.COD_CONTRAT=$cod_cliente AND 
													  CAIXA.COD_EMPRESA=$cod_empresa AND 
														CAIXA.COD_MES = $cod_mes AND 
														CAIXA.DAT_EXCLUSA IS NULL AND 
														CAIXA.COD_EXCLUSA = 0 AND 
														CAIXA.TIP_LANCAME = '$tip_lancame'
												ORDER BY CAIXA.DAT_LANCAME DESC ";

							//fnEscreve($sqlTotLiq);
							$arrayTotLiq = mysqli_query(connTemp($cod_empresa, ''), $sqlTotLiq);
							$qrTotLiq = mysqli_fetch_assoc($arrayTotLiq);

							$val_liquido = fnValor($qrTotLiq['VAL_LIQUIDO'], 2);
							//fnEscreve($val_liquido);VAL_LIQUIDO
							?>

							<script>

							</script>


						</form>

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
		let tipo = "";
		$(function() {

			$('.datePicker').datetimepicker({
				format: 'DD/MM/YYYY',
				minDate: '<?= $dat_ini ?>',
				maxDate: '<?= $dat_fim ?>',
			}).on('changeDate', function(e) {
				$(this).datetimepicker('hide');
			});

			$("#COD_TIPO").on("change", function() {
				tipo = $(this).val();
				if (tipo == 4 && <?= $cod_empresa ?> != 274) {
					$("#pct").fadeIn('fast');
					$("#dias").fadeOut('fast');
				} else {
					$("#dias").fadeIn('fast');
					$("#pct").fadeOut('fast');
				}
			});

		});

		try {
			parent.$('#valLiq<?= $cod_cliente; ?>').html("<?= $val_liquido; ?>");
		} catch (err) {}

		function retornaForm(index) {
			$("#formulario #COD_CLIENTE").val($("#ret_COD_CLIENTE_" + index).val());
			$("#formulario #NOM_CLIENTE").val($("#ret_NOM_CLIENTE_" + index).val());
			$("#formulario #DAT_NASCIME").val($("#ret_DAT_NASCIME_" + index).val());
			$("#formulario #TIP_DEPENDE").val($("#ret_TIP_DEPENDE_" + index).val()).trigger("chosen:updated");
			$("#formulario #COD_SEXOPES").val($("#ret_COD_SEXOPES_" + index).val()).trigger("chosen:updated");
			$('#formulario').validator('validate');
			$("#formulario #hHabilitado").val('S');
		}
	</script>