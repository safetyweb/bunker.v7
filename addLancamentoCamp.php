<?php

//echo "<h5>_".$opcao."</h5>";

$hashLocal = mt_rand();
$multiplo = 'false';

if (isset($_GET['idx'])) {
	$cod_caixa = fnDecode($_GET['idx']);
} else {
	$cod_caixa = 0;
}

if (isset($_GET['m'])){
	$multiplo = 'true';
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
		$cod_contrat = fnLimpacampozero($_REQUEST['COD_CONTRAT']);
		$val_credito = fnValorSql($_REQUEST['VAL_CREDITO']);
		$num_dias = fnLimpacampozero($_REQUEST['TIP_PAGAMEN']);
		$cod_formapa = fnLimpacampozero($_REQUEST['COD_FORMAPA']);

		$cod_usucada = $_SESSION[SYS_COD_USUARIO];

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
				let multiplo = "<?=$multiplo?>";
				if(multiplo == "true"){
					parent.refreshCliente("<?=fnEncode($cod_cliente)?>");						
				}else{
					parent.$("#REFRESH_LANCAMENTO").val("S");
				}
			</script>
<?php
			//mensagem de retorno
			switch ($opcao) {
				case 'CAD':

					$sql = "INSERT INTO CAIXA(
												COD_EMPRESA,
												COD_CONTRAT,
												COD_CLIENTE,
												DAT_LANCAME,
												COD_TIPO,
												VAL_CREDITO,
												TIP_LANCAME,
												COD_USUCADA,
												NUM_DIA,
												COD_PAGAMENTO
											) VALUES(
												$cod_empresa,
												$cod_contrat,
												$cod_cliente,
												'$dat_lancame',
												99,
												'$val_credito',
												'D',
												$cod_usucada,
												$num_dias,
												$cod_formapa
											)";

					// fnEscreve($sql);

					mysqli_query(connTemp($cod_empresa, ''), $sql);

					$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					break;
				case 'ALT':

					$sql = "UPDATE CAIXA SET
												DAT_LANCAME='$dat_lancame',
												VAL_CREDITO='$val_credito',
												COD_PAGAMENTO =$cod_formapa,
												DAT_ALTERAC=CONVERT_TZ(NOW(),'America/Sao_Paulo','America/Sao_Paulo'),
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
	$cod_contrat = fnDecode($_GET['idCT']);
	$tip_pagamen = fnDecode($_GET['idT']);
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

	$dat_lancame = fnDataShort($qrCx[DAT_LANCAME]);
	$cod_tipo = $qrCx[COD_TIPO];
	$val_credito = fnValor($qrCx[VAL_CREDITO], 2);
	$num_dia = $qrCx[NUM_DIA];
	$cod_contrat = $qrCx[COD_CONTRAT];
	$tip_pagamen = $qrCx[TIP_PAGAMEN];
	$cod_pagamento = $qrCx[COD_PAGAMENTO];
} else {
	$dat_lancame = "";
	$cod_tipo = "";
	$val_credito = "";
	$cod_pagamento = "";
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

									<div class="col-sm-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Forma de Pagamento</label>
											<select data-placeholder="Selecione uma forma de pagamento" name="COD_FORMAPA" id="COD_FORMAPA" class="chosen-select-deselect" tabindex="1" value="<?=$cod_pagamento?>" required>
												<option value=""></option>
												<option value="1">Dinheiro</option>
												<option value="2">Pix</option>
												<option value="3">TED/DOC</option>
												<option value="4">Transferência</option>
												<option value="5">Cheque</option>
											</select>
											<div class="help-block with-errors"></div>
											<script>
												$("#formulario #COD_FORMAPA").val(<?=$cod_pagamento?>);
											</script>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Valor (R$)</label>
											<input type="text" class="form-control input-sm text-center money" name="VAL_CREDITO" id="VAL_CREDITO" value="<?= $val_credito ?>" required>
											<div class="help-block with-errors"></div>
										</div>
									</div>

								</div>

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
							<input type="hidden" name="COD_CONTRAT" id="COD_CONTRAT" value="<?= $cod_contrat ?>">
							<input type="hidden" name="TIP_PAGAMEN" id="TIP_PAGAMEN" value="<?= $tip_pagamen ?>">
							<input type="hidden" name="COD_CAIXA" id="COD_CAIXA" value="<?= fnEncode($cod_caixa) ?>">
							<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
							<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

							<div class="push5"></div>

							<?php
							//busca total líquido
							// $sqlTotLiq = "SELECT COD_MES FROM MES_CAIXA WHERE COD_EMPRESA = $cod_empresa ORDER BY DAT_FIM DESC LIMIT 1";

							// $sqlTotLiq = "SELECT
							// 						   IFNULL(SUM(case when TIP_CREDITO.TIP_OPERACAO ='C' then
							// 						   CAIXA.VAL_CREDITO 
							// 						  END),0) -
							// 						   IFNULL(SUM(case when TIP_CREDITO.TIP_OPERACAO ='D' then
							// 						   CAIXA.VAL_CREDITO 
							// 						  END),0) VAL_LIQUIDO
													  
													
							// 					FROM CAIXA
							// 					LEFT JOIN TIP_CREDITO ON caixa.COD_TIPO=TIP_CREDITO.COD_TIPO
							// 					WHERE CAIXA.COD_CONTRAT=$cod_cliente AND 
							// 						  CAIXA.COD_EMPRESA=$cod_empresa AND 
							// 							CAIXA.COD_MES = $cod_mes AND 
							// 							CAIXA.DAT_EXCLUSA IS NULL AND 
							// 							CAIXA.COD_EXCLUSA = 0 AND 
							// 							CAIXA.TIP_LANCAME = '$tip_lancame'
							// 					ORDER BY CAIXA.DAT_LANCAME DESC ";

							// //fnEscreve($sqlTotLiq);
							// $arrayTotLiq = mysqli_query(connTemp($cod_empresa, ''), $sqlTotLiq);
							// $qrTotLiq = mysqli_fetch_assoc($arrayTotLiq);

							// $val_liquido = fnValor($qrTotLiq[VAL_LIQUIDO], 2);
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
		$(function() {

			$('.datePicker').datetimepicker({
				format: 'DD/MM/YYYY',
				// minDate: '<?= $dat_ini ?>',
				// maxDate: '<?= $dat_fim ?>',
			}).on('changeDate', function(e) {
				$(this).datetimepicker('hide');
			});

		});

		try {
			// parent.$('#valLiq<?= $cod_cliente; ?>').html("<?= $val_liquido; ?>");
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