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

		$des_fluxo = fnLimpaCampo($_REQUEST['DES_FLUXO']);

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		if ($opcao != '') {

			//mensagem de retorno
			switch ($opcao) {
				case 'CAD':
					$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
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
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error());
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
								<legend>Dados Gerais</legend>

								<div class="row">

									<div class="col-md-4">
										<div class="form-group">
											<label for="inputName" class="control-label">Descrição</label>
											<input type="text" class="form-control input-sm" name="DES_FLUXO" id="DES_FLUXO" maxlength="4">
											<div class="help-block with-errors"></div>
										</div>
									</div>

								</div>

							</fieldset>

							<div class="push10"></div>
							<hr>
							<div class="form-group text-right col-lg-12">

								<button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>
								<button type="submit" name="BUS" id="BUS" class="btn btn-primary getBtn"><i class="fas fa-search" aria-hidden="true"></i>&nbsp; Buscar</button>

							</div>

							<input type="hidden" name="opcao" id="opcao" value="">
							<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
							<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">

							<div class="push5"></div>

						</form>

						<div class="push50"></div>

						<div class="col-lg-12">

							<div class="no-more-tables">

								<form name="formLista">

									<table class="table table-bordered table-striped table-hover tablesorter buscavel">
										<thead>
											<tr>
												<th class="{ sorter: false }"></th>
												<th>Código</th>
												<th>Descrição</th>
												<th class="{ sorter: false }"></th>
												<th>Código</th>
												<th>Descrição</th>
											</tr>
										</thead>
										<tbody>

											<?php

											if ($des_fluxo != '') {
												$whereCod = "WHERE DES_FLUXO LIKE '%$des_fluxo%'";
											} else {
												$whereCod = "WHERE 1=1";
											}

											$sql = "select * from DATA_DRIVEN_DECISION $whereCod order by DES_FLUXO";
											$arrayQuery = mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error());

											$count = 0;
											while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery)) {

												if ($count % 2 == 0) {
													$abreTR = "<tr>";
													$fechaTR = "";
												} else {
													$abreTR = "";
													$fechaTR = "</tr>";
												}
												// fnEscreve(($count % 2));				

												echo $abreTR . "
															
															<td class='text-center'>
															  	   
																<div class='form-group'>																	
																	 	<a href='javascript: downForm(" . $count . ")' style='margin-left: 10px;'><i class='fa fa-arrow-circle-down' aria-hidden='true'></i></a>
																	
																</div>
															  <td>" . $qrBuscaModulos['COD_DATA_DRIVEN'] . "</td>															  
															  <td>" . $qrBuscaModulos['DES_FLUXO'] . "</td>															  														  
															
															<input type='hidden' id='ret_ID_" . $count . "' value='" . $qrBuscaModulos['COD_DATA_DRIVEN'] . "'>															
															<input type='hidden' id='ret_DES_FLUXO_" . $count . "' value='" . $qrBuscaModulos['DES_FLUXO'] . "'>
															
															
															" . $fechaTR;

												$count++;
											}

											?>

										</tbody>
									</table>

								</form>

							</div>

						</div>

						<div class="push"></div>

					</div>

				</div>
				</div>
				<!-- fim Portlet -->
			</div>

	</div>

	<div class="push20"></div>

	<script type="text/javascript">
		function downForm(index) {

			try {
				parent.$('#COD_DATA_DRIVEN').val($("#ret_ID_" + index).val());
			} catch (err) {}
			try {
				parent.$('#DES_FLUXO').val($("#ret_DES_FLUXO_" + index).val());
			} catch (err) {}
			$(this).removeData('bs.modal');
			//console.log('entrou' + index);
			parent.$('#popModal').modal('hide');


		}
	</script>