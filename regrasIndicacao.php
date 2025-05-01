<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$hashLocal = "";
$msgRetorno = "";
$msgTipo = "";
$cod_regraind = "";
$log_ativo = "";
$tip_regrauso = "";
$qtd_historico = 0;
$qtd_indica = 0;
$tip_descart = "";
$cod_usucada = "";
$hHabilitado = "";
$hashForm = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$qrRegra = "";
$checkAtivo = "";
$popUp = "";
$abaModulo = "";
$cod_configu = "";
$min_historico_tkt = "";
$max_historico_tkt = "";


$hashLocal = mt_rand();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_regraind = fnLimpaCampoZero(@$_REQUEST['COD_REGRAIND']);
		$cod_empresa = fnLimpaCampoZero(@$_REQUEST['COD_EMPRESA']);
		if (empty(@$_REQUEST['LOG_ATIVO'])) {
			$log_ativo = 'N';
		} else {
			$log_ativo = @$_REQUEST['LOG_ATIVO'];
		}
		$tip_regrauso = fnLimpaCampo(@$_REQUEST['TIP_REGRAUSO']);
		$qtd_historico = fnLimpaCampoZero(@$_REQUEST['QTD_HISTORICO']);
		$qtd_indica = fnLimpaCampoZero(@$_REQUEST['QTD_INDICA']);
		$tip_descart = fnLimpaCampoZero(@$_REQUEST['TIP_DESCART']);

		$cod_usucada = $_SESSION['SYS_COD_USUARIO'];

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		//fnEscreve($opcao);	

		if ($opcao != '') {

			$sql = "DELETE FROM REGRAS_INDICACAO WHERE COD_EMPRESA = $cod_empresa;";

			$sql .= "INSERT INTO REGRAS_INDICACAO(
										COD_EMPRESA,
										TIP_REGRAUSO,
										QTD_HISTORICO,
										QTD_INDICA,
										TIP_DESCART,
										LOG_ATIVO,
										COD_USUCADA
									) VALUES(
										$cod_empresa,
										'$tip_regrauso',
										$qtd_historico,
										$qtd_indica,
										'$tip_descart',
										'$log_ativo',
										$cod_usucada
									)";

			//fnEscreve($sql);
			mysqli_multi_query(connTemp($cod_empresa, ""), trim($sql));



			//echo $sql;				
			//fnTesteSql(connTemp($cod_empresa,""),$sql);

			// mysqli_query(connTemp($cod_empresa,""),trim($sql));				

			//mensagem de retorno
			switch ($opcao) {
				case 'CAD':

					$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					break;
				case 'ALT':

					// $sql = "UPDATE REGRAS_INDICACAO SET
					// 				TIP_REGRAUSO='$tip_regrauso',
					// 				QTD_HISTORICO='$qtd_historico',
					// 				QTD_INDICA='$qtd_indica',
					// 				TIP_DESCART='$tip_descart',
					// 				LOG_ATIVO='$log_ativo'
					// 		WHERE COD_REGRAIND = $cod_regraind
					// 				"

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


//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode(@$_GET['id']);
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";

	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($qrBuscaEmpresa)) {
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
	}
} else {
	$cod_empresa = 0;
	$nom_empresa = "";
}


$sql = "SELECT * FROM REGRAS_INDICACAO WHERE COD_EMPRESA = $cod_empresa";

$qrRegra = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa, ''), $sql));

if (isset($qrRegra)) {

	$cod_regraind = $qrRegra['COD_REGRAIND'];
	$tip_regrauso = $qrRegra['TIP_REGRAUSO'];
	$qtd_historico = $qrRegra['QTD_HISTORICO'];
	$qtd_indica = $qrRegra['QTD_INDICA'];
	$tip_descart = $qrRegra['TIP_DESCART'];
	if ($qrRegra['LOG_ATIVO'] == 'S') {
		$checkAtivo = "checked";
	} else {
		$checkAtivo = "";
	}
} else {

	$cod_regraind = 0;
	$tip_regrauso = "";
	$qtd_historico = "";
	$qtd_indica = "";
	$tip_descart = "";
	$checkAtivo = "";
}

?>

<div class="push30"></div>

<div class="row">

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<?php if ($popUp != "true") {  ?>
			<div class="portlet portlet-bordered">
			<?php } else { ?>
				<div class="portlet" style="padding: 0 20px 20px 20px;">
				<?php } ?>

				<?php if ($popUp != "true") {  ?>
					<div class="portlet-title">
						<div class="caption">
							<i class="fal fa-terminal"></i>
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

					<!-- <div class="alert alert-warning top30 bottom30" role="alert" id="msgRetorno">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					Os fechamentos são realizados <b>semanalmente as segundas 3h</b>.
					</div> -->


					<?php $abaModulo = 1484;
					include "abasTicketConfig.php"; ?>

					<div class="push30"></div>

					<div class="login-form">

						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

							<fieldset>
								<legend>Dados Gerais</legend>

								<div class="row">

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label required">Empresa</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa ?>">
											<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">
										</div>
									</div>


								</div>

								<div class="push20"></div>

								<div class="row">

									<div class="col-md-1">
										<div class="form-group">
											<label for="inputName" class="control-label">Indicação ativa</label>
											<div class="push5"></div>
											<label class="switch">
												<input type="checkbox" name="LOG_ATIVO" id="LOG_ATIVO" class="switch" value="S" <?= $checkAtivo ?>>
												<span></span>
											</label>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Regra de Utilização</label>
											<select data-placeholder="Selecione a regra de uso" name="TIP_REGRAUSO" id="TIP_REGRAUSO" class="chosen-select-deselect requiredChk" style="width:100%;" tabindex="1" required>
												<option></option>
												<option value="PRD">Por produtos</option>
												<option value="OBJ">Por objeto</option>
											</select>
											<script>
												$("#formulario #TIP_REGRAUSO").val("<?php echo $tip_regrauso; ?>").trigger("chosen:updated");
											</script>
											<div class="help-block with-errors"></div>
										</div>

									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Dias de Histórico</label>
											<select data-placeholder="Selecione os dias de histórico" name="QTD_HISTORICO" id="QTD_HISTORICO" class="chosen-select-deselect requiredChk" style="width:100%;" tabindex="1" required>
												<option></option>
												<option value="30">30</option>
												<option value="60">60</option>
												<option value="90">90</option>
											</select>
											<script>
												$("#formulario #QTD_HISTORICO").val("<?php echo $qtd_historico; ?>").trigger("chosen:updated");
											</script>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Quantidade de Indicações</label>
											<select data-placeholder="Selecione a quantidade de indicações" name="QTD_INDICA" id="QTD_INDICA" class="chosen-select-deselect requiredChk" style="width:100%;" tabindex="1" required>
												<option></option>
												<option value="5">5</option>
												<option value="10">10</option>
											</select>
											<script>
												$("#formulario #QTD_INDICA").val("<?php echo $qtd_indica; ?>").trigger("chosen:updated");
											</script>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Desprezar Prefixos Iguais</label>
											<select data-placeholder="Selecione a regra de desprezo" name="TIP_DESCART" id="TIP_DESCART" class="chosen-select-deselect requiredChk" style="width:100%;" tabindex="1" required>
												<option></option>
												<option value="0">Não desprezar retornos</option>
												<option value="1">1 palavra</option>
												<option value="2">2 palavras</option>
											</select>
											<script>
												$("#formulario #TIP_DESCART").val("<?php echo $tip_descart; ?>").trigger("chosen:updated");
											</script>
											<div class="help-block with-errors"></div>
										</div>
									</div>

								</div>


								<div class="push20"></div>

							</fieldset>

							<div class="push10"></div>
							<hr>
							<div class="form-group text-right col-lg-12">

								<button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
								<?php

								//fnEscreve($cod_configu);
								if ($cod_regraind == "0") { ?>
									<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
								<?php } else { ?>
									<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Atualizar Configuração</button>
								<?php } ?>

							</div>


							<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
							<input type="hidden" name="opcao" id="opcao" value="">
							<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
							<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

							<div class="push5"></div>

						</form>

						<div class="push50"></div>

					</div>
				</div>
				</div>
				<!-- fim Portlet -->
			</div>
	</div>

	<!-- <script src="js/plugins/ion.rangeSlider.js"></script>
	<link rel="stylesheet" href="css/ion.rangeSlider.css" />
	<link rel="stylesheet" href="css/ion.rangeSlider.skinHTML5.css" /> -->

	<div class="push20"></div>

	<script type="text/javascript">
		$(document).ready(function() {

			//chosen
			$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
			$('#formulario').validator();


		});

		// $(function () {

		// 	// $("#NUM_HISTORICO_TKT").ionRangeSlider({
		// 	// 	hide_min_max: true,
		// 	// 	keyboard: true,
		// 	// 	min: 0,
		// 	// 	max: 120,
		// 	// 	from: <?php echo $min_historico_tkt; ?>,
		// 	// 	to: <?php echo $max_historico_tkt; ?>,
		// 	// 	type: 'int',
		// 	// 	step: 5,
		// 	// 	//prettify_enabled: true,
		// 	// 	//prettify_separator: "."
		// 	// 	//prefix: "Idade ",
		// 	// 	postfix: " dias",
		// 	// 	max_postfix: ""
		// 	// 	//grid: true
		// 	// });
		// 	/*
		// 	$("#range").ionRangeSlider();
		// 	*/

		// });		

		function retornaForm(index) {
			$("#formulario #COD_BLKLIST").val($("#ret_COD_BLKLIST_" + index).val());
			$("#formulario #TIP_BLKLIST").val($("#ret_TIP_BLKLIST_" + index).val()).trigger("chosen:updated");
			$("#formulario #NOM_BLKLIST").val($("#ret_NOM_BLKLIST_" + index).val());
			$("#formulario #ABV_BLKLIST").val($("#ret_ABV_BLKLIST_" + index).val());
			$('#formulario').validator('validate');
			$("#formulario #hHabilitado").val('S');
		}
	</script>