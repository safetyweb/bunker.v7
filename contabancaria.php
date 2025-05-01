<?php

// echo fnDebug('true');

$hashLocal = mt_rand();

$cod_conta = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_conta = fnLimpaCampoZero($_REQUEST['COD_CONTA']);
		$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
		$cod_conveni = fnLimpaCampoZero($_REQUEST['COD_CONVENI']);
		$cod_entidad = fnLimpaCampoZero($_REQUEST['COD_ENTIDAD']);
		$num_banco = fnLimpaCampo($_REQUEST['NUM_BANCO']);
		$num_agencia = fnLimpaCampoZero($_REQUEST['NUM_AGENCIA']);
		$num_contaco = fnLimpaCampo($_REQUEST['NUM_CONTACO']);
		$num_pix = fnLimpaCampo($_REQUEST['NUM_PIX']);
		$tip_pix = fnLimpaCampoZero($_REQUEST['TIP_PIX']);

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

		if ($opcao != '') {

			$sql = "CALL SP_ALTERA_CONTABANCARIA (
				 '" . $cod_conta . "', 
				 '" . $cod_empresa . "',
				 '" . $cod_entidad . "', 
				 '" . $cod_conveni . "', 
				 '" . $cod_cliente . "',
				 '" . $num_banco . "',
				 '" . $num_agencia . "',
				 '" . $num_contaco . "',
				 '" . $opcao . "'    
			        );";


			// mysqli_query(connTemp($cod_empresa,''),$sql);				

			//mensagem de retorno
			switch ($opcao) {
				case 'CAD':

					$sql = "INSERT INTO CONTABANCARIA(
													COD_EMPRESA,
													COD_CONVENI,
													COD_ENTIDAD,
													NUM_BANCO,
													NUM_AGENCIA,
													NUM_CONTACO,
													NUM_PIX,
													TIP_PIX,
													COD_USUCADA
												) VALUES(
													$cod_empresa,
													$cod_conveni,
													$cod_entidad,
													'$num_banco',
													'$num_agencia',
													'$num_contaco',
													'$num_pix',
													'$tip_pix',
													$cod_usucada
											)";

					//fnEscreve($sql);

					mysqli_query(connTemp($cod_empresa, ''), $sql);

					$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";

					break;
				case 'ALT':

					$sql = "UPDATE CONTABANCARIA SET
										COD_ENTIDAD = $cod_entidad,
										NUM_BANCO = '$num_banco',
										NUM_AGENCIA = '$num_agencia',
										NUM_CONTACO = '$num_contaco',
										NUM_PIX = '$num_pix',
										TIP_PIX = '$tip_pix',
										COD_ALTERAC = $cod_usucada,
										DAT_ALTERAC = NOW()
								WHERE COD_EMPRESA = $cod_empresa
								AND COD_CONTA = $cod_conta";

					fnEscreve($sql);

					mysqli_query(connTemp($cod_empresa, ''), $sql);

					$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";

					break;
				case 'EXC':

					$sql = "UPDATE CONTABANCARIA
										COD_EXCLUSA = $cod_usucada,
										DAT_EXCLUSA = NOW()
								WHERE COD_EMPRESA = $cod_empresa
								AND COD_CONTA = $cod_conta";

					// fnEscreve($sql);

					// mysqli_query(connTemp($cod_empresa,''),$sql);

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

if (isset($_GET['idE'])) {
	$cod_entidad = fnDecode($_GET['idE']);
}

if (isset($_GET['idC'])) {
	if (is_numeric(fnLimpacampo(fnDecode($_GET['idC'])))) {

		//busca dados do convênio
		$cod_conveni = fnDecode($_GET['idC']);
		$sql = "SELECT NOM_CONVENI FROM CONVENIO WHERE COD_CONVENI = " . $cod_conveni;

		//fnEscreve($sql);
		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
		$qrBuscaTemplate = mysqli_fetch_assoc($arrayQuery);

		if (isset($qrBuscaTemplate)) {
			$nom_conveni = $qrBuscaTemplate['NOM_CONVENI'];
		}
	}
}

if ($cod_conveni != "" && $cod_conveni != "0") {
	$andConveni = " AND COD_CONVENI = $cod_conveni ";
} else {
	$andConveni = " ";
}

//fnMostraForm();
// fnEscreve($cod_conveni);
// fnEscreve($andConveni);

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
							<i class="fal fa-terminal"></i>
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

					<?php
					if (isset($_GET['idC'])) {

						$abaConvenio = 1080;
						include "abasConvenio.php";
					} else {
						$abaFormalizacao = 1080;
						include "abasFormalizacaoEmp.php";
					}
					?>


					<div class="push30"></div>

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Dados Gerais</legend>

							<div class="row">

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Código</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_CONTA" id="COD_CONTA" value="<?= $cod_conta ?>">
									</div>
									<div class="help-block with-errors"></div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Convênio</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_CONVENI" id="NOM_CONVENI" value="<?php echo $nom_conveni ?>" required>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Entidade</label>
										<select data-placeholder="Selecione uma entidade" name="COD_ENTIDAD" id="COD_ENTIDAD" class="chosen-select-deselect" required>
											<option value=""></option>
											<?php
											$sql = "SELECT * from ENTIDADE EN 
																	INNER join ENTIDADE_CONVENIO EC ON EC.COD_ENTIDAD = EN.COD_ENTIDAD 
																	AND EC.COD_CONVENI = $cod_conveni
																	order by EN.COD_ENTIDAD ";
											$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

											while ($qrListaTipoEntidade = mysqli_fetch_assoc($arrayQuery)) {
												echo "
																	  <option value='" . $qrListaTipoEntidade['COD_ENTIDAD'] . "'>" . $qrListaTipoEntidade['NOM_ENTIDAD'] . "</option> 
																	";
											}
											?>
										</select>
										<script>
											$("#formulario #COD_ENTIDAD").val("<?php echo $cod_entidad; ?>").trigger("chosen:updated");
										</script>
										<div class="help-block with-errors"></div>
									</div>
								</div>

							</div>

							<div class="push10"></div>

							<div class="row">

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Número Banco</label>
										<input type="text" class="form-control input-sm int" name="NUM_BANCO" id="NUM_BANCO" value="" maxlength="6" data-mask-reverse="true" maxlength="11" value="<?= $num_banco ?>">
									</div>
									<div class="help-block with-errors"></div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Agência</label>
										<input type="text" class="form-control input-sm" name="NUM_AGENCIA" id="NUM_AGENCIA" value="" maxlength="5" value="<?= $num_agencia ?>">
									</div>
									<div class="help-block with-errors"></div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Conta Corrente</label>
										<input type="text" class="form-control input-sm" name="NUM_CONTACO" id="NUM_CONTACO" value="" maxlength="10" value="<?= $num_contaco ?>">
									</div>
									<div class="help-block with-errors"></div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">PIX</label>
										<input type="text" class="form-control input-sm" name="NUM_PIX" id="NUM_PIX" value="" maxlength="100" value="<?= $num_pix ?>">
									</div>
									<div class="help-block with-errors"></div>
								</div>

								<div class="col-xs-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Tipo de Pix</label>
										<select data-placeholder="Selecione o tipo do PIX" name="TIP_PIX" id="TIP_PIX" class="chosen-select-deselect">
											<option></option>
											<option value="3">CPF/CNPJ</option>
											<option value="1">Celular</option>
											<option value="2">Email</option>
										</select>
										<!-- <script>$("#formulario #TIP_PIX").val("<?php echo $tip_pix; ?>").trigger("chosen:updated"); </script> -->
										<div class="help-block with-errors"></div>
									</div>
								</div>

							</div>

						</fieldset>


						<div class="push10"></div>
						<hr>
						<div class="form-group text-right col-lg-12">

							<button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
							<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
							<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>
							<!-- <button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fa fa-times" aria-hidden="true"></i>&nbsp; Excluir</button> -->

						</div>

						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="COD_CONVENI" id="COD_CONVENI" value="<?= $cod_conveni ?>">
						<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?= $cod_empresa ?>">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">

						<div class="push5"></div>

					</form>
				</div>
			</div>
		</div>

			<div class="push5"></div>

			<div class="portlet portlet-bordered">

				<div class="portlet-body">

					<div class="login-form">

						<div class="col-lg-12">

							<div class="no-more-tables">

								<form name="formLista">

									<table class="table table-bordered table-striped table-hover tablesorter buscavel">
										<thead>
											<tr>
												<th width="40"></th>
												<th>Código</th>
												<th>Empresa</th>
												<th>Entidade</th>
												<th>Agência</th>
												<th>Conta Corrente</th>
											</tr>
										</thead>
										<tbody>

											<?php
											$sql = "SELECT CONTABANCARIA.COD_CONTA,
												 CONTABANCARIA.COD_EMPRESA,
												 CONTABANCARIA.COD_ENTIDAD,
												 CONTABANCARIA.COD_CONVENI,
												 CONTABANCARIA.COD_CLIENTE,
												 CONTABANCARIA.NUM_BANCO,
												 CONTABANCARIA.NUM_AGENCIA,
												 CONTABANCARIA.NUM_CONTACO,
												 CONTABANCARIA.NUM_PIX,
												 CONTABANCARIA.TIP_PIX,
												 EMPRESAS.NOM_EMPRESA,
												 ENTIDADE.NOM_ENTIDAD, 
												 CONVENIO.DES_DESCRIC 
											from CONTABANCARIA  
												left join webtools.empresas ON CONTABANCARIA.COD_EMPRESA = empresas.COD_EMPRESA 
												left join ENTIDADE ON CONTABANCARIA.COD_ENTIDAD = ENTIDADE.COD_ENTIDAD 
												left join CONVENIO ON CONTABANCARIA.COD_CONVENI = CONVENIO.COD_CONVENI 
										    where empresas.COD_EMPRESA =  $cod_empresa
										    AND CONTABANCARIA.COD_CONVENI = $cod_conveni";

											//  fnEscreve($sql);
											$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

											$count = 0;
											while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery)) {
												$count++;
												echo "
											<tr>
											  <td><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></th>
											  <td>" . $qrBuscaModulos['COD_CONTA'] . "</td>
											  <td>" . $qrBuscaModulos['NOM_EMPRESA'] . "</td>
											  <td>" . $qrBuscaModulos['NOM_ENTIDAD'] . "</td>
											  <td>" . $qrBuscaModulos['NUM_AGENCIA'] . "</td>
											  <td>" . $qrBuscaModulos['NUM_CONTACO'] . "</td>
											</tr>
											
											<input type='hidden' id='ret_COD_CONTA_" . $count . "' value='" . $qrBuscaModulos['COD_CONTA'] . "'>
											<input type='hidden' id='ret_COD_ENTIDAD_" . $count . "' value='" . $qrBuscaModulos['COD_ENTIDAD'] . "'>
											<input type='hidden' id='ret_NUM_BANCO_" . $count . "' value='" . $qrBuscaModulos['NUM_BANCO'] . "'>
											<input type='hidden' id='ret_NUM_AGENCIA_" . $count . "' value='" . $qrBuscaModulos['NUM_AGENCIA'] . "'>
											<input type='hidden' id='ret_NUM_CONTACO_" . $count . "' value='" . $qrBuscaModulos['NUM_CONTACO'] . "'>
											<input type='hidden' id='ret_NUM_PIX_" . $count . "' value='" . $qrBuscaModulos['NUM_PIX'] . "'>
											<input type='hidden' id='ret_TIP_PIX_" . $count . "' value='" . $qrBuscaModulos['TIP_PIX'] . "'>
											";
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
	function retornaForm(index) {

		$("#formulario #COD_CONTA").val($("#ret_COD_CONTA_" + index).val());
		$("#formulario #COD_ENTIDAD").val($("#ret_COD_ENTIDAD_" + index).val()).trigger('chosen:updated');
		$("#formulario #NUM_BANCO").val($("#ret_NUM_BANCO_" + index).val());
		$("#formulario #NUM_AGENCIA").val($("#ret_NUM_AGENCIA_" + index).val());
		$("#formulario #NUM_CONTACO").val($("#ret_NUM_CONTACO_" + index).val());
		$("#formulario #NUM_PIX").val($("#ret_NUM_PIX_" + index).val());
		$("#formulario #TIP_PIX").val($("#ret_TIP_PIX_" + index).val()).trigger('chosen:updated');

		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');

	}
</script>