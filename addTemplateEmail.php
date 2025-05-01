<?php

//echo fnDebug('true');

$hashLocal = mt_rand();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_template = fnLimpaCampoZero($_REQUEST['COD_TEMPLATE']);
		$cod_campanha = fnLimpaCampoZero($_REQUEST['COD_CAMPANHA']);
		$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
		if (empty($_REQUEST['LOG_ATIVO'])) {
			$log_ativo = 'N';
		} else {
			$log_ativo = $_REQUEST['LOG_ATIVO'];
		}

		$nom_template = fnLimpaCampo($_REQUEST['NOM_TEMPLATE']);
		$abv_template = fnLimpaCampo($_REQUEST['ABV_TEMPLATE']);
		$des_template = fnLimpaCampo($_REQUEST['DES_TEMPLATE']);

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];


		if ($opcao != '') {

			// $sql = "CALL SP_ALTERA_TEMPLATE (
			//  '".$cod_template."', 
			//  '".$cod_empresa."',
			//  '".$log_ativo."', 
			//  '".$nom_template."', 
			//  '".$abv_template."',
			//  '".$des_template."',
			//  '".$cod_usucada."',
			//  '".$opcao."'    
			//        );";

			// //fnEscreve($sql);
			//             mysqli_query(connTemp($cod_empresa,''),$sql);				

			//mensagem de retorno
			switch ($opcao) {
				case 'CAD':

					// FNeSCREVE($cod_campanha);

					$sql = "INSERT INTO TEMPLATE_EMAIL(
												COD_EMPRESA,
												LOG_ATIVO,
												NOM_TEMPLATE,
												ABV_TEMPLATE,
												DES_TEMPLATE,
												COD_USUCADA
								   	  		)VALUES( 
												$cod_empresa,
												'$log_ativo',
												'$nom_template',
												'$abv_template',
												'$des_template',
												$cod_usucada
											); ";

					$sql .= "INSERT INTO TEMPLATE_EMAIL_CAMPANHA(
												COD_EMPRESA, 
												COD_TEMPLATE,
												COD_CAMPANHA, 
												COD_USUCADA
											) VALUES(
												$cod_empresa,
												(SELECT MAX(COD_TEMPLATE) FROM TEMPLATE_EMAIL 
												 WHERE COD_EMPRESA = $cod_empresa AND COD_USUCADA = $cod_usucada),
												$cod_campanha,
												$cod_usucada
											);";

					mysqli_multi_query(connTemp($cod_empresa, ''), $sql);

					$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";

					break;

				case 'ALT':

					$sql = "UPDATE TEMPLATE_EMAIL SET
										LOG_ATIVO='$log_ativo',
										NOM_TEMPLATE='$nom_template',
										ABV_TEMPLATE='$abv_template',
										DES_TEMPLATE='$des_template',
										DAT_ALTERAC=CONVERT_TZ(NOW(),'America/Sao_Paulo','America/Sao_Paulo'),
										COD_ALTERAC=$cod_usucada
								WHERE COD_TEMPLATE=$cod_template";

					// fnEscreve($sql);
					mysqli_query(connTemp($cod_empresa, ''), $sql);

					$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";

					break;

				case 'EXC':
					$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";
					break;
			}
			//atualiza lista iframe				
?>
			<script>
				// try { parent.$('#REFRESH_TEMPLATES').val("S");} catch(err) {}
				// alert('atualiza parent');
			</script>
<?php
			$msgTipo = 'alert-success';
		}
	}
}

//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {

	//busca dados da empresa
	$cod_empresa = fnDecode($_GET['id']);
	$cod_campanha = fnDecode($_GET['idc']);
	$cod_tipo = fnDecode($_GET['tipo']);

	$sql = "SELECT NOM_EMPRESA FROM EMPRESAS WHERE COD_EMPRESA = " . $cod_empresa;

	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error());
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($qrBuscaEmpresa)) {
		$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
	}
} else {
	$nom_empresa = "";
}

if (is_numeric(fnLimpacampo(fnDecode($_GET['idT'])))) {

	//busca dados do convênio
	$cod_template = fnDecode($_GET['idT']);
	$sql = "SELECT * FROM TEMPLATE_EMAIL WHERE COD_TEMPLATE = " . $cod_template;

	//fnEscreve($sql);
	$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql) or die(mysqli_error());
	$qrBuscaTemplate = mysqli_fetch_assoc($arrayQuery);

	if (isset($qrBuscaTemplate)) {
		$cod_template = $qrBuscaTemplate['COD_TEMPLATE'];
		if ($qrBuscaTemplate['LOG_ATIVO'] == 'S') {
			$checkAtivo = "checked";
		} else {
			$checkAtivo = "";
		}
		$nom_template = $qrBuscaTemplate['NOM_TEMPLATE'];
		$abv_template = $qrBuscaTemplate['ABV_TEMPLATE'];
		$des_template = $qrBuscaTemplate['DES_TEMPLATE'];
	}
} else {
	$cod_template = "";
	$checkAtivo = "";
	$nom_template = "";
	$abv_template = "";
	$des_template = "";
}

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
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_TEMPLATE" id="COD_TEMPLATE" value="<?php echo $cod_template ?>">
									</div>
									<div class="help-block with-errors"></div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Empresa</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa ?>">
										<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label required">Nome Template</label>
										<input type="text" class="form-control input-sm" name="NOM_TEMPLATE" id="NOM_TEMPLATE" value="<?php echo $nom_template ?>" maxlength="50" required>
									</div>
									<div class="help-block with-errors"></div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Abreviação Template</label>
										<input type="text" class="form-control input-sm" name="ABV_TEMPLATE" id="ABV_TEMPLATE" value="<?php echo $abv_template ?>" maxlength="20">
									</div>
									<div class="help-block with-errors"></div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Ativo</label>
										<div class="push5"></div>
										<label class="switch">
											<input type="checkbox" name="LOG_ATIVO" id="LOG_ATIVO" class="switch" value="S" <?= $checkAtivo ?>>
											<span></span>
										</label>
									</div>
								</div>

								<div class="col-md-12">
									<div class="form-group">
										<label for="inputName" class="control-label">Descrição Template</label>
										<textarea type="text" class="form-control input-sm" rows="3" name="DES_TEMPLATE" id="DES_TEMPLATE" maxlength="200"><?php echo $des_template; ?></textarea>
									</div>
									<div class="help-block with-errors"></div>
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

<script type="text/javascript">
	// if($( "#LOG_ATIVO" ).val() === 'S'){
	// 	$( "#LOG_ATIVO" ).trigger( "click" );
	// }

	// $( "#LOG_ATIVO" ).change(function() {
	// 	if($(this).val() === 'N'){
	// 		$(this).val('S');
	// 	}else{
	// 		$(this).val('N');
	// 	}
	// });

	function retornaForm(index) {
		/*
		$("#formulario #COD_TEMPLATE").val($("#ret_COD_TEMPLATE_"+index).val());
		$("#formulario #COD_EMPRESA").val($("#ret_COD_EMPRESA_"+index).val());
		$("#formulario #NOM_TEMPLATE").val($("#ret_NOM_TEMPLATE_"+index).val());
		$("#formulario #ABV_TEMPLATE").val($("#ret_ABV_TEMPLATE_"+index).val());
		$("#formulario #DES_TEMPLATE").val($("#ret_DES_TEMPLATE_"+index).val());
		if ($("#ret_LOG_ATIVO_"+index).val() == 'S'){$('#formulario #LOG_ATIVO').prop('checked', true);}else{$('#formulario #LOG_ATIVO').prop('checked', false);}
		$('#formulario').validator('validate');			
		$("#formulario #hHabilitado").val('S');			
		*/
	}
</script>