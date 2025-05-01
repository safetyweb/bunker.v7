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

		$cod_cliente = fnLimpaCampoZero($_REQUEST['COD_CLIENTE']);
		$cod_titular = fnLimpaCampoZero($_REQUEST['COD_TITULAR']);
		$nom_cliente = fnLimpacampo($_REQUEST['NOM_CLIENTE']);
		$dat_nascime = fnLimpacampo($_REQUEST['DAT_NASCIME']);
		$tip_depende = fnLimpacampo($_REQUEST['TIP_DEPENDE']);
		$cod_sexopes = fnLimpacampoZero($_REQUEST['COD_SEXOPES']);
		$cod_empresa = fnLimpaCampo($_REQUEST['COD_EMPRESA']);
		$cod_grauesc = fnLimpaCampo($_REQUEST['COD_GRAUESC']);
		$cod_usucada = $_SESSION[SYS_COD_USUARIO];

		$newDate = explode('/', $dat_nascime);
		$dia = $newDate[0];
		$mes   = $newDate[1];
		$ano  = $newDate[2];
		$idade = date_diff(date_create(fnDataSql($dat_nascime)), date_create('now'))->y;

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		if ($opcao != '') {

			//mensagem de retorno
			switch ($opcao) {
				case 'CAD':

					$sql = "INSERT INTO CLIENTES(
												COD_EMPRESA,
												COD_TITULAR,
												NOM_CLIENTE,
												DAT_NASCIME,
												DIA,
												MES,
												ANO,
												IDADE,
												COD_SEXOPES,
												TIP_DEPENDE,
												LOG_TITULAR,
												COD_USUCADA
											) VALUES(
												$cod_empresa,
												$cod_titular,
												'$nom_cliente',
												'$dat_nascime',
												'$dia',
												'$mes',
												'$ano',
												$idade,
												$cod_sexopes,
												'$tip_depende',
												'N',
												$cod_usucada
											)";

					// fnEscreve($sql);

					mysqli_query(connTemp($cod_empresa, ''), $sql);

					$sqlCli = "SELECT MAX(COD_CLIENTE) COD_CLIENTE FROM CLIENTES 
									WHERE COD_EMPRESA = $cod_empresa 
									AND COD_USUCADA = $cod_usucada
									AND COD_TITULAR = $cod_titular";

					$arrayCli = mysqli_query(connTemp($cod_empresa, ''), $sqlCli);

					$qrCli = mysqli_fetch_assoc($arrayCli);

					$cod_cliente = $qrCli[COD_CLIENTE];


					$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";
					break;
				case 'ALT':

					$sql = "UPDATE CLIENTES SET
												NOM_CLIENTE='$nom_cliente',
												DAT_NASCIME='$dat_nascime',
												DIA = '$dia',
												MES = '$mes',
												ANO = '$ano',
												IDADE = '$idade',
												COD_SEXOPES=$cod_sexopes,
												TIP_DEPENDE='$tip_depende',
												COD_ALTERAC=$cod_usucada,
												DAT_ALTERAC=NOW()
								WHERE COD_EMPRESA = $cod_empresa
								AND COD_CLIENTE = $cod_cliente";

					mysqli_query(connTemp($cod_empresa, ''), $sql);

					$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";
					break;
				case 'EXC':

					$sql = "DELETE FROM CLIENTES
								WHERE COD_EMPRESA = $cod_empresa
								AND COD_CLIENTE = $cod_cliente";

					mysqli_query(connTemp($cod_empresa, ''), $sql);

					$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";
					break;
			}
			$msgTipo = 'alert-success';
		}
		$sqlDependente = "SELECT COUNT(*) AS COD_DEPENDENTE FROM DADOS_APOIADOR
						WHERE COD_CLIENTE = $cod_cliente";
		$arrayDependente = mysqli_query(connTemp($cod_empresa, ''), $sqlDependente);

		$qrDependente = mysqli_fetch_assoc($arrayDependente);

		$cod_dependente = $qrDependente['COD_DEPENDENTE'];

		if ($cod_dependente == 0) {
			$insertDependente = "INSERT INTO DADOS_APOIADOR (
															COD_CLIENTE,
															COD_GRAUESC,
															DAT_CADASTR															
															)VALUES(
															$cod_cliente,
															$cod_grauesc,
															NOW()
															)";

			//echo $insertDependente;
			mysqli_query(connTemp($cod_empresa, ''), $insertDependente);
		} else {
			$updateDependente = "UPDATE DADOS_APOIADOR SET
								COD_GRAUESC = $cod_grauesc
								WHERE COD_CLIENTE = $cod_cliente";
			//echo $updateDependente;
			mysqli_query(connTemp($cod_empresa, ''), $updateDependente);
		}
	}
}




//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode($_GET['id']);
	$cod_titular = fnDecode($_GET['idc']);
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
								<legend>Dados Gerais</legend>

								<div class="row">

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label required">Nome</label>
											<input type="text" class="form-control input-sm" name="NOM_CLIENTE" id="NOM_CLIENTE" value="<?php echo $nom_cliente; ?>">
										</div>
									</div>

									<div class="col-xs-2">
										<div class="form-group">
											<label for="inputName" class="control-label required lbl_req">Data de Nascimento</label>
											<input type="text" class="form-control input-sm data" name="DAT_NASCIME" value="<?php echo $dat_nascime; ?>" id="DAT_NASCIME" maxlength="10" required>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-xs-2">
										<div class="form-group">
											<label for="inputName" class="control-label required lbl_req">Sexo</label>
											<select data-placeholder="Selecione o sexo" name="COD_SEXOPES" id="COD_SEXOPES" class="chosen-select-deselect requiredChk" required>
												<option value=""></option>
												<?php
												$sql = "select COD_SEXOPES, DES_SEXOPES from sexo order by des_sexopes ";
												$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

												while ($qrListaSexo = mysqli_fetch_assoc($arrayQuery)) {
													echo "
														<option value='" . $qrListaSexo['COD_SEXOPES'] . "'>" . $qrListaSexo['DES_SEXOPES'] . "</option> 
													";
												}
												?>
											</select>
											<script>
												$("#formulario #COD_SEXOPES").val("<?= $cod_sexopes; ?>").trigger("chosen:updated");
											</script>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-xs-2">
										<div class="form-group">
											<label for="inputName" class="control-label required lbl_req">Tipo</label>
											<select data-placeholder="Selecione o tipo de dependente" name="TIP_DEPENDE" id="TIP_DEPENDE" class="chosen-select-deselect requiredChk" required>
												<option value="0">Filho(a)</option>
												<option value="1">Esposo(a)</option>
												<option value="2">Pai/Mãe</option>
											</select>
											<script>
												$("#formulario #TIP_DEPENDE").val("<?= $tip_depende; ?>").trigger("chosen:updated");
											</script>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-xs-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Escolaridade </label>
											<select data-placeholder="Selecione a escolaridade" name="COD_GRAUESC" id="COD_GRAUESC" class="chosen-select-deselect">
												<option value=""></option>
												<option value="1">Educação infantil</option>
												<option value="2">Fundamental</option>
												<option value="3">Médio</option>
												<option value="4">Superior (Graduação)</option>
												<option value="5">Pós-graduação</option>
												<option value="6">Mestrado</option>
												<option value="7">Doutorado</option>
												<option value="8">Escola</option>
											</select>
											<script>
												$("#formulario #COD_GRAUESC").val("<?= $cod_grauesc; ?>").trigger("chosen:updated");
											</script>
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
								<button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fal fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>

							</div>

							<input type="hidden" name="opcao" id="opcao" value="">
							<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?= $cod_empresa ?>">
							<input type="hidden" name="COD_TITULAR" id="COD_TITULAR" value="<?= $cod_titular ?>">
							<input type="hidden" name="COD_CLIENTE" id="COD_CLIENTE" value="<?= $cod_cliente ?>">
							<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
							<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">

							<div class="push5"></div>

						</form>

						<div class="push50"></div>

						<div class="col-lg-12">

							<div class="no-more-tables">

								<form name="formLista">

									<table class="table table-bordered table-striped table-hover tableSorter">
										<thead>
											<tr>
												<th class="{ sorter: false }" width="40"></th>
												<th>Dependente</th>
												<th>Dt. Nascimento</th>
												<th>Tipo</th>
											</tr>
										</thead>
										<tbody>

											<?php

											$sql = "SELECT CL.*,DA.COD_GRAUESC FROM CLIENTES CL 
													INNER JOIN DADOS_APOIADOR DA ON DA.COD_CLIENTE = CL.COD_CLIENTE
													WHERE CL.COD_EMPRESA = $cod_empresa 
													AND CL.COD_TITULAR = $cod_titular
													AND CL.LOG_TITULAR = 'N'
													ORDER BY CL.NOM_CLIENTE";

											// fnEscreve($sql);
											$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

											$count = 0;
											while ($qrDepende = mysqli_fetch_assoc($arrayQuery)) {

												switch ($qrDepende['TIP_DEPENDE']) {

													case 1:
														$tip_depende = "Esposo(a)";
														break;

													case 2:
														$tip_depende = "Pai/Mãe";
														break;

													default:
														$tip_depende = "Filho(a)";
														break;
												}

												$count++;
												echo "
													<tr>
													<td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></th>
													<td>" . $qrDepende['NOM_CLIENTE'] . "</td>
													<td>" . $qrDepende['DAT_NASCIME'] . "</td>
													<td>" . $tip_depende . "</td>
													</tr>
													<input type='hidden' id='ret_COD_CLIENTE_" . $count . "' value='" . $qrDepende['COD_CLIENTE'] . "'>
													<input type='hidden' id='ret_NOM_CLIENTE_" . $count . "' value='" . $qrDepende['NOM_CLIENTE'] . "'>
													<input type='hidden' id='ret_TIP_DEPENDE_" . $count . "' value='" . $qrDepende['TIP_DEPENDE'] . "'>
													<input type='hidden' id='ret_COD_GRAUESC_" . $count . "' value='" . $qrDepende['COD_GRAUESC'] . "'>
													<input type='hidden' id='ret_DAT_NASCIME_" . $count . "' value='" . $qrDepende['DAT_NASCIME'] . "'>
													<input type='hidden' id='ret_COD_SEXOPES_" . $count . "' value='" . $qrDepende['COD_SEXOPES'] . "'>
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
				</div> <!-- fim Portlet -->
			</div>
	</div>
</div>

<div class="push20"></div>

<script type="text/javascript">
	function retornaForm(index) {
		$("#formulario #COD_CLIENTE").val($("#ret_COD_CLIENTE_" + index).val());
		$("#formulario #NOM_CLIENTE").val($("#ret_NOM_CLIENTE_" + index).val());
		$("#formulario #DAT_NASCIME").val($("#ret_DAT_NASCIME_" + index).val());
		$("#formulario #TIP_DEPENDE").val($("#ret_TIP_DEPENDE_" + index).val()).trigger("chosen:updated");
		$("#formulario #COD_GRAUESC").val($("#ret_COD_GRAUESC_" + index).val()).trigger("chosen:updated");
		$("#formulario #COD_SEXOPES").val($("#ret_COD_SEXOPES_" + index).val()).trigger("chosen:updated");
		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
	}
</script>