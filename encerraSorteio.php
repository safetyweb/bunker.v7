<?php

//echo "<h5>_".$opcao."</h5>";

$hashLocal = mt_rand();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_cupom = fnLimpaCampoZero($_REQUEST['COD_CUPOM']);
		$cod_empresa = fnLimpaCampo($_REQUEST['COD_EMPRESA']);
		$cod_campanha = fnLimpaCampo($_REQUEST['COD_CAMPANHA']);
		$cod_univend = fnLimpaCampo($_REQUEST['COD_UNIVEND']);
		$num_sorteado = fnLimpaCampo($_REQUEST['NUM_SORTEADO']);

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		if ($opcao != '') {

			// CREATE DEFINER=`adminterno`@`%` PROCEDURE `SP_CUPOM_SORTEADO`(
			// 	IN `P_COD_CUPOM` INT,
			// 	IN `p_COD_EMPRESA` INT,
			// 	IN `p_COD_CAMPANHA` INT,
			// 	IN `p_COD_UNIVEND` VARCHAR(6000)
			// 	IN `p_NUM_SORTEADO` INT





			// )

			$sqlUnivend = "SELECT COD_UNIVEND FROM CUPOM WHERE COD_EMPRESA = $cod_empresa AND COD_CUPOM = $cod_cupom";
			$arrayUnivend = mysqli_query(connTemp($cod_empresa, ""), $sqlUnivend);
			$qrUnivend = mysqli_fetch_assoc($arrayUnivend);

			$unidade = $qrUnivend["COD_UNIVEND"];

			if ($qrUnivend["COD_UNIVEND"] == "") {
				$unidade = 0;
			}

			$sql = "CALL SP_CUPOM_SORTEADO (
				 '" . $cod_cupom . "', 
				 '" . $cod_empresa . "', 
				 '" . $cod_campanha . "', 
				 '" . $unidade . "', 
				 '" . $num_sorteado . "'
				) ";

			// fnEscreve($sql);

			//echo $sql;

			mysqli_query(connTemp($cod_empresa, ""), $sql);

			//mensagem de retorno
			switch ($opcao) {
				case 'CAD':
					$msgRetorno = "Sorteio encerrado com <strong>sucesso!</strong>";
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


//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode($_GET['id']);
	$cod_campanha = fnDecode($_GET['idc']);
	$cod_cupom = fnDecode($_GET['idcp']);
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

//fnMostraForm();

// fnEscreve($cod_campanha);

$sqlSorteado = "SELECT NOM_CLIENTE,COD_CUPOM,NUM_CUPOM FROM geracupom a, clientes b
					WHERE A.COD_CLIENTE=B.COD_CLIENTE AND 
					A.COD_EMPRESA = $cod_empresa AND 
					A.COD_CAMPANHA = $cod_campanha AND 
					A.cod_cupom = $cod_cupom
					AND A.LOG_SORTEADO = 'S'";

$arraySorteado = mysqli_query(connTemp($cod_empresa, ''), $sqlSorteado);

$qrSorteado = mysqli_fetch_assoc($arraySorteado);

$sorteado = $qrSorteado['NOM_CLIENTE'];
$num_sorte = $qrSorteado['NUM_CUPOM'];

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
								<?php if ($sorteado != "") { ?>
									<legend>Dados do Ganhador</legend>

									<div class="row">

										<div class="col-md-5">
											<div class="form-group">
												<label for="inputName" class="control-label">Ganhador do Sorteio</label>
												<input type="text" class="form-control input-sm leitura" name="NOM_SORTEADO" id="NOM_SORTEADO" value="<?= $sorteado ?>" maxlength="9" readonly>
												<div class="help-block with-errors"></div>
											</div>
										</div>

										<div class="col-md-2">
											<div class="form-group">
												<label for="inputName" class="control-label">Cupom Sorteado</label>
												<input type="text" class="form-control input-sm leitura" name="CUPOM_SORTEADO" id="CUPOM_SORTEADO" value="<?= $num_sorte ?>" maxlength="9" readonly>
												<div class="help-block with-errors"></div>
											</div>
										</div>

									</div>

								<?php } else { ?>

									<legend>Dados Gerais</legend>

									<div class="row">
										<!-- <div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label required">Código</label>
															<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_GRUPOTR" id="COD_GRUPOTR" value="">
														</div>
													</div> -->

										<div class="col-md-4">
											<div class="form-group">
												<label for="inputName" class="control-label">Informe o Nro. sorteado</label>
												<input type="text" class="form-control input-sm" name="NUM_SORTEADO" id="NUM_SORTEADO" maxlength="50" required>
												<div class="help-block with-errors"></div>
											</div>
										</div>

									<?php } ?>

									</div>

							</fieldset>

							<div class="push10"></div>

							<?php if ($sorteado == "") { ?>
								<hr>
								<div class="form-group text-right col-lg-12">

									<button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
									<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Encerrar</button>

								</div>

							<?php } ?>

							<input type="hidden" name="opcao" id="opcao" value="">
							<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
							<input type="hidden" name="COD_CAMPANHA" id="COD_CAMPANHA" value="<?php echo $cod_campanha; ?>" />
							<input type="hidden" name="COD_CUPOM" id="COD_CUPOM" value="<?php echo $cod_cupom; ?>" />
							<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">
							<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">

							<div class="push5"></div>

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
				format: 'DD/MM/YYYY'
			}).on('changeDate', function(e) {
				$(this).datetimepicker('hide');
			});

		});
	</script>