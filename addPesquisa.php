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
$cod_pesquisa = "";
$cod_campanha = "";
$log_ativo = "";
$log_permite = "";
$log_principal = "";
$des_pesquisa = "";
$abr_pesquisa = "";
$des_icone = "";
$des_cor = "";
$des_observa = "";
$dat_ini = "";
$hor_ini = "";
$dat_fim = "";
$hor_fim = "";
$cod_usucada = "";
$hHabilitado = "";
$hashForm = "";
$cod_tipo = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$qrBuscaTemplate = "";
$mostraChecadoAT = "";
$mostraChecadoRT = "";
$mostraChecadoPcp = "";
$popUp = "";
$hashLocal = mt_rand();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {

		$_SESSION['last_request']  = $request;

		$cod_pesquisa = fnLimpaCampoZero(@$_REQUEST['COD_PESQUISA']);
		$cod_campanha = fnLimpaCampoZero(@$_REQUEST['COD_CAMPANHA']);
		$cod_empresa = fnLimpaCampoZero(@$_REQUEST['COD_EMPRESA']);
		if (empty(@$_REQUEST['LOG_ATIVO'])) {
			$log_ativo = 'N';
		} else {
			$log_ativo = 'S';
		}
		if (empty(@$_REQUEST['LOG_PERMITE'])) {
			$log_permite = 'N';
		} else {
			$log_permite = 'S';
		}
		if (empty(@$_REQUEST['LOG_PRINCIPAL'])) {
			$log_principal = 'N';
		} else {
			$log_principal = 'S';
		}
		$des_pesquisa = fnLimpaCampo(@$_REQUEST['DES_PESQUISA']);
		$abr_pesquisa = fnLimpaCampo(@$_REQUEST['ABR_PESQUISA']);
		$des_icone = fnLimpaCampo(@$_REQUEST['DES_ICONE']);
		$des_cor = fnLimpaCampo(@$_REQUEST['DES_COR']);
		$des_observa = fnLimpaCampo(@$_REQUEST['DES_OBSERVA']);
		$dat_ini = fnLimpaCampo(@$_REQUEST['DAT_INI']);
		$hor_ini = fnLimpaCampo(@$_REQUEST['HOR_INI']);
		$dat_fim = fnLimpaCampo(@$_REQUEST['DAT_FIM']);
		$hor_fim = fnLimpaCampo(@$_REQUEST['HOR_FIM']);

		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		// fnEscreve($log_permite);
		// fnEscreve($log_principal);


		if ($opcao != '') {

			//mensagem de retorno
			switch ($opcao) {
				case 'CAD':

					$sql = "INSERT INTO PESQUISA(
									COD_EMPRESA, 
									COD_CAMPANHA,
									LOG_ATIVO, 
									DES_PESQUISA, 
									DES_ICONE, 
									DES_COR, 
									LOG_PERMITE, 
									LOG_PRINCIPAL, 
									DES_OBSERVA,
									COD_USUCADA, 
									DAT_INI,
									DAT_FIM,
									HOR_INI,
									HOR_FIM
								)VALUES(
									'$cod_empresa',
									$cod_campanha, 
									'$log_ativo', 
									'$des_pesquisa', 
									'$des_icone', 
									'$des_cor', 
									'$log_permite', 
									'$log_principal', 
									'$des_observa',
									'$cod_usucada', 
									'" . fndataSql($dat_ini) . "',
									'" . fndataSql($dat_fim) . "',
									'$hor_ini',
									'$hor_fim' 
								) ";

					//fnEscreve($sql);
					//fnTestesql(connTemp($cod_empresa,""),trim($sql));
					mysqli_query(connTemp($cod_empresa, ''), $sql);

					$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					break;

				case 'ALT':

					$sql = "UPDATE PESQUISA SET
									COD_EMPRESA='$cod_empresa',  
									COD_CAMPANHA='$cod_campanha',  
									LOG_ATIVO='$log_ativo',  
									DES_PESQUISA='$des_pesquisa',  
									DES_ICONE='$des_icone',  
									DES_COR='$des_cor',  
									LOG_PERMITE='$log_permite',  
									LOG_PRINCIPAL='$log_principal',  
									DES_OBSERVA='$des_observa',
									COD_USUCADA='$cod_usucada',  
									DAT_INI='" . fndataSql($dat_ini) . "',
									DAT_FIM='" . fndataSql($dat_fim) . "',
									HOR_INI='$hor_ini',
									HOR_FIM='$hor_fim'
								WHERE COD_PESQUISA = $cod_pesquisa";

					// fnEscreve($sql);
					// fnTestesql(connTemp($cod_empresa,""),trim($sql));
					mysqli_query(connTemp($cod_empresa, ''), $sql);

					$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";
					break;
				case 'EXC':

					$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";
					break;
					break;
			}
			$msgTipo = 'alert-success';

			//atualiza lista iframe				
?>
			<script>
				try {
					parent.$('#REFRESH_TEMPLATES').val("S");
				} catch (err) {}
			</script>
<?php

		}
	}
}

//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {

	//busca dados da empresa
	$cod_empresa = fnDecode(@$_GET['id']);
	$cod_campanha = fnDecode(@$_GET['idc']);
	$cod_tipo = fnDecode(@$_GET['tipo']);

	$sql = "SELECT NOM_EMPRESA FROM EMPRESAS WHERE COD_EMPRESA = " . $cod_empresa;

	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($qrBuscaEmpresa)) {
		$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
	}
} else {
	$nom_empresa = "";
}

if (is_numeric(fnLimpacampo(fnDecode(@$_GET['idP'])))) {

	//busca dados do convênio
	$cod_pesquisa = fnDecode(@$_GET['idP']);
	$sql = "SELECT * FROM PESQUISA WHERE COD_PESQUISA = " . $cod_pesquisa;

	//fnEscreve($sql);
	$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
	$qrBuscaTemplate = mysqli_fetch_assoc($arrayQuery);

	if (isset($qrBuscaTemplate)) {
		$cod_pesquisa = $qrBuscaTemplate['COD_PESQUISA'];
		$log_ativo = $qrBuscaTemplate['LOG_ATIVO'];
		$des_pesquisa = $qrBuscaTemplate['DES_PESQUISA'];
		$abr_pesquisa = $qrBuscaTemplate['ABR_PESQUISA'];
		$des_icone = $qrBuscaTemplate['DES_ICONE'];
		$des_cor = $qrBuscaTemplate['DES_COR'];
		$log_permite = $qrBuscaTemplate['LOG_PERMITE'];
		$log_principal = $qrBuscaTemplate['LOG_PRINCIPAL'];
		$des_observa = $qrBuscaTemplate['DES_OBSERVA'];
		$hor_ini = $qrBuscaTemplate['HOR_INI'];
		$hor_fim = $qrBuscaTemplate['HOR_FIM'];
		$dat_ini = $qrBuscaTemplate['DAT_INI'];
		$dat_fim = $qrBuscaTemplate['DAT_FIM'];

		if ($log_ativo == "S") {
			$mostraChecadoAT = "checked";
		} else {
			$mostraChecadoAT = "";
		}

		if ($log_permite == "S") {
			$mostraChecadoRT = "checked";
		} else {
			$mostraChecadoRT = "";
		}

		if ($log_principal == "S") {
			$mostraChecadoPcp = "checked";
		} else {
			$mostraChecadoPcp = "";
		}
	}
} else {
	$cod_pesquisa = "";
	$log_ativo = "";
	$des_pesquisa = "";
	$abr_pesquisa = "";
	$des_icone = "";
	$des_cor = "";
	$log_permite = "";
	$des_observa = "";
	$hor_ini = "";
	$hor_fim = "";
	$dat_ini = "";
	$dat_fim = "";
	$mostraChecadoAT = "checked";
	$mostraChecadoPcp = "";
}

// fnEscreve($cod_campanha);

?>

<?php if ($popUp != "true") {  ?>
	<div class="push30"></div>
<?php } ?>

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
							<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
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

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Dados Gerais</legend>

							<div class="row">
								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Código</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_PESQUISA" id="COD_PESQUISA" value="<?php echo $cod_pesquisa; ?>">
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Pesquisa Ativa</label>
										<div class="push5"></div>
										<label class="switch">
											<input type="checkbox" name="LOG_ATIVO" id="LOG_ATIVO" class="switch" value="S" <?php echo $mostraChecadoAT; ?>>
											<span></span>
										</label>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Empresa</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa; ?>">
										<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
									</div>
								</div>

								<div class="col-md-5">
									<div class="form-group">
										<label for="inputName" class="control-label required">Título</label>
										<input type="text" class="form-control input-sm" name="DES_PESQUISA" id="DES_PESQUISA" value="<?php echo $des_pesquisa; ?>" required>
									</div>
								</div>

								<!-- <div class="col-md-2">
								<div class="form-group">
									<label for="inputName" class="control-label required">Apelido</label>
									<input type="text" class="form-control input-sm" name="ABR_PESQUISA" id="ABR_PESQUISA" value="<?php echo $abr_pesquisa ?>">
								</div>														
							</div> -->



							</div>

							<div class="row">

								<div class="col-md-3">
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

								<div class="col-md-3">
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

								<!-- <div class="col-md-2">
								<div class="form-group">
									<label for="inputName" class="control-label">Hora Início</label>
									
									<div class='input-group date clockPicker'>
										<input type='text' class="form-control input-sm" name="HOR_INI" id="HOR_INI" value="<?php echo $hor_ini; ?>" />
										<span class="input-group-addon">
											<span class="glyphicon glyphicon-time"></span>
										</span>
									</div>

								</div>
							</div> -->

								<!-- <div class="col-md-2">
								<div class="form-group">
									<label for="inputName" class="control-label">Hora Fim</label>
									
									<div class='input-group date clockPicker'>
										<input type='text' class="form-control input-sm" name="HOR_FIM" id="HOR_FIM" value="<?php echo $hor_fim; ?>" />
										<span class="input-group-addon">
											<span class="glyphicon glyphicon-time"></span>
										</span>
									</div>

								</div>
							</div> -->

								<div class="col-md-1">
									<div class="form-group">
										<label for="inputName" class="control-label">Ícone</label><br />
										<button class="btn btn-primary" id="btniconpicker" data-iconset="fontawesome"
											data-icon="vazio" role="iconpicker" data-arrow-prev-icon-class="fas fa-arrow-left" data-arrow-next-icon-class="fas fa-arrow-right"
											data-rows="6" data-cols="6" data-search-text="Buscar ícone..." data-label-footer="{0} - {1} de {2} ícones" data-label-header="{0} de {1}">
										</button>
										<input type="hidden" name="DES_ICONE" id="DES_ICONE" value="">
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Cor</label>
										<input type="text" class="form-control input-sm pickColor" style="margin-top: 4px;" name="DES_COR" id="DES_COR" value="">
									</div>
									<div class="help-block with-errors"></div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Permite refazer campanha?</label>
										<div class="push5"></div>
										<label class="switch">
											<input type="checkbox" name="LOG_PERMITE" id="LOG_PERMITE" class="switch" value="S" <?php echo $mostraChecadoRT; ?>>
											<span></span>
										</label>
									</div>
								</div>

							</div>

							<div class="row">

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Pesquisa Principal</label>
										<div class="push5"></div>
										<label class="switch">
											<input type="checkbox" name="LOG_PRINCIPAL" id="LOG_PRINCIPAL" class="switch" value="S" <?php echo $mostraChecadoPcp; ?>>
											<span></span>
										</label>
									</div>
								</div>

								<div class="col-md-12">
									<div class="form-group">
										<label for="inputName" class="control-label">Objetivo da Pesquisa</label><br />
										<textarea class="form-control" rows="3" name="DES_OBSERVA" id="DES_OBSERVA" maxlength="40"><?php echo $des_observa ?></textarea>
										<div class="help-block with-errors"></div>
									</div>
								</div>

							</div>

							<div class="push10"></div>

						</fieldset>


						<div class="push10"></div>
						<hr>
						<div class="form-group text-right col-lg-12">

							<!--<button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>-->
							<?php
							if ($cod_tipo == 'CAD') {
							?>
								<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
							<?php
							} else {
							?>
								<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Alterar</button>
							<?php
							}
							?>

							<!-- <button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fa fa-times" aria-hidden="true"></i>&nbsp; Excluir</button> -->

						</div>

						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="COD_CAMPANHA" id="COD_CAMPANHA" value="<?= $cod_campanha ?>">
						<input type="hidden" name="HOR_INI" id="HOR_INI" value="00:00">
						<input type="hidden" name="HOR_FIM" id="HOR_FIM" value="23:59">
						<input type="hidden" name="ABR_PESQUISA" id="ABR_PESQUISA" value="<?= $des_pesquisa ?>">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

						<div class="push5"></div>

					</form>

					<div class="push50"></div>

					<div class="push"></div>

				</div>

				</div>
			</div>
			<!-- fim Portlet -->
	</div>

</div>

<div class="push20"></div>

<link rel="stylesheet" href="../css/bootstrap-iconpicker.min.css" />
<script type="text/javascript" src="../js/bootstrap-iconpicker-iconset-fa5.js"></script>
<script type="text/javascript" src="../js/bootstrap-iconpicker.js"></script>

<script src="js/plugins/minicolors/jquery.minicolors.min.js"></script>
<link rel="stylesheet" href="js/plugins/minicolors/jquery.minicolors.css">

<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />

<script type="text/javascript">
	$(function() {

		$('.datePicker').datetimepicker({
			format: 'DD/MM/YYYY',
			//maxDate : 'now',
		}).on('changeDate', function(e) {
			$(this).datetimepicker('hide');
		});

		$('.clockPicker').datetimepicker({
			format: 'LT',
		}).on('changeDate', function(e) {
			$(this).datetimepicker('hide');
		});

	});

	$(document).ready(function() {
		//chosen
		$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
		$('#formulario').validator();

		//color picker
		$('.pickColor').minicolors({
			control: $(this).attr('data-control') || 'hue',
			theme: 'bootstrap'
		});

		//icon picker
		$('.btnSearchIcon').iconpicker({
			cols: 8,
			iconset: 'fontawesome',
			rows: 6,
			searchText: 'Procurar  &iacute;cone'
		});

		//capturando o ícone selecionado no botão
		$('#btniconpicker').on('change', function(e) {
			$('#DES_ICONE').val(e.icon);
			//alert($('#DES_ICONE').val());
		});

		icone = "<?php echo $des_icone ?>";

		cor = "<?php echo $des_cor ?>";

		if (icone == "") {
			icone = "fal fa-smile-plus";
		}

		if (cor == "") {
			cor = "#2C3E50";
		}

		$("#btniconpicker").iconpicker('setIcon', icone);
		$("#DES_ICONE").val(icone);

		$("#DES_COR").minicolors('value', cor);
	});
</script>