<?php

//echo "<h5>_".$opcao."</h5>";

$hashLocal = mt_rand();

$conn = conntemp($cod_empresa,"");
$adm = $connAdm->connAdm();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_vendedor = fnLimpaCampoZero($_REQUEST['COD_VENDEDOR']);
		$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
		$cod_univend = fnLimpaCampoZero($_REQUEST['COD_UNIVEND']);
		$cod_ext_vendedor = fnLimpaCampo($_REQUEST['COD_EXT_VENDEDOR']);
		$cod_usuario = fnLimpaCampoZero($_REQUEST['COD_USUARIO']);

		$nom_usuario = $_SESSION["SYS_NOM_USUARIO"];
		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
		$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$MODULO = $_GET['mod'];
		$COD_MODULO = fndecode($_GET['mod']);

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		if ($opcao != '') {

			

			//mensagem de retorno
			switch ($opcao) {
				case 'CAD':

					$sql = "INSERT INTO VENDEDOR_ADORAI(
												COD_EMPRESA,
												COD_UNIVEND,
												COD_USUARIO,
												COD_EXT_VENDEDOR,
												COD_USUCADA
											) VALUES(
												$cod_empresa,
												$cod_univend,
												$cod_usuario,
												$cod_ext_vendedor,
												$cod_usucada
											)";

					// FNeSCREVE($sql);

					$arrayProc = mysqli_query(connTemp($cod_empresa,''), $sql);

					if (!$arrayProc) {

						$cod_erro = Log_error_comand($adm,connTemp($cod_empresa,''), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql,$nom_usuario);
					}

					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível cadastrar o registro : $cod_erro";
					}

				break;
				case 'ALT':

					$sql = "UPDATE VENDEDOR_ADORAI SET
												COD_UNIVEND = $cod_univend,
												COD_EXT_VENDEDOR = $cod_ext_vendedor,
												COD_ALTERAC = $cod_usucada,
												DAT_ALTERAC = NOW()
							WHERE COD_EMPRESA = $cod_empresa
							AND COD_VENDEDOR = $cod_vendedor";

					//echo $sql;

					$arrayProc = mysqli_query(connTemp($cod_empresa,''), $sql);

					if (!$arrayProc) {

						$cod_erro = Log_error_comand($adm,connTemp($cod_empresa,''), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql,$nom_usuario);
					}

					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível alterar o registro : $cod_erro";
					}

				break;
				case 'EXC':

					$sql = "UPDATE VENDEDOR_ADORAI SET
												COD_EXCLUSA = $cod_usucada,
												DAT_EXCLUSA = NOW()
							WHERE COD_EMPRESA = $cod_empresa
							AND COD_VENDEDOR = $cod_vendedor";

					//echo $sql;

					$arrayProc = mysqli_query(connTemp($cod_empresa,''), $sql);

					if (!$arrayProc) {

						$cod_erro = Log_error_comand($adm,connTemp($cod_empresa,''), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql,$nom_usuario);
					}

					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível excluir o registro : $cod_erro";
					}

				break;					
			}
			if ($cod_erro == 0 || $cod_erro == "") {
				$msgTipo = 'alert-success';
			} else {
				$msgTipo = 'alert-danger';
			}
		}
	}
}


//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode($_GET['id']);
	$cod_usuario = fnDecode($_GET['idu']);
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($adm, $sql);
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
		<?php if ($popUp != "true"){  ?>							
		<div class="portlet portlet-bordered">
		<?php } else { ?>
		<div class="portlet" style="padding: 0 20px 20px 20px;" >
		<?php } ?>
		
			<?php if ($popUp != "true"){  ?>
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

								<div class="col-xs-5">
									<div class="form-group">
										<label for="inputName" class="control-label required">Unidade</label>
											<select data-placeholder="Selecione a unidade" name="COD_UNIVEND" id="COD_UNIVEND"  class="chosen-select-deselect" required>
												<option value=""></option>
												<?php
													$sqlHotel = "SELECT COD_UNIVEND, NOM_FANTASI FROM UNIDADEVENDA WHERE COD_EMPRESA = $cod_empresa AND LOG_ESTATUS = 'S'";
													$arrayHotel = mysqli_query(connTemp($cod_empresa,''), $sqlHotel);

													while ($qrHotel = mysqli_fetch_assoc($arrayHotel)) {
												?>
														<option value="<?=$qrHotel[COD_UNIVEND]?>">"<?=$qrHotel[NOM_FANTASI]?>"</option>
												<?php 
													}
												?>
												<!-- <option value="2957">Adorai/SP</option>
												<option value="3010">Piedade 2/SP</option>
												<option value="3008">Cunha/SP</option>
												<option value="956">Paraty/RJ</option> -->
											</select>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label required">Código Externo</label>
										<input type="tel" class="form-control input-sm" name="COD_EXT_VENDEDOR" id="COD_EXT_VENDEDOR" maxlength="50" required>
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
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="COD_VENDEDOR" id="COD_VENDEDOR" value="<?php echo $cod_vendedor; ?>" />
						<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>" />
						<input type="hidden" name="COD_USUARIO" id="COD_USUARIO" value="<?php echo $cod_usuario; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">
						<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">

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
											<th>Código</th>
											<th>Unidade</th>
											<th>Cod. Externo</th>
										</tr>
									</thead>
									<tbody>

										<?php

										$sql = "SELECT VA.COD_VENDEDOR, 
													   VA.COD_EXT_VENDEDOR, 
													   VA.COD_UNIVEND,
													   UV.NOM_FANTASI
												FROM VENDEDOR_ADORAI VA
												INNER JOIN UNIDADEVENDA UV ON UV.COD_UNIVEND = VA.COD_UNIVEND
												WHERE VA.COD_EMPRESA = $cod_empresa 
												AND VA.COD_USUARIO = $cod_usuario
												AND (VA.COD_EXCLUSA IS NULL OR VA.COD_EXCLUSA = 0)";
										// FNeSCREVE($sql);
										$arrayQuery = mysqli_query(connTemp($cod_empresa,''), $sql);

										$count = 0;
										while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery)) {
											$count++;
											echo "
													<tr>
														<td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></th>
														<td>" . $qrBuscaModulos['COD_VENDEDOR'] . "</td>
														<td>" . $qrBuscaModulos['NOM_FANTASI'] . "</td>
														<td>" . $qrBuscaModulos['COD_EXT_VENDEDOR'] . "</td>
													</tr>
													<input type='hidden' id='ret_COD_VENDEDOR_" . $count . "' value='" . $qrBuscaModulos['COD_VENDEDOR'] . "'>
													<input type='hidden' id='ret_COD_EXT_VENDEDOR_" . $count . "' value='" . $qrBuscaModulos['COD_EXT_VENDEDOR'] . "'>
													<input type='hidden' id='ret_COD_UNIVEND_" . $count . "' value='" . $qrBuscaModulos['COD_UNIVEND'] . "'>
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
		$("#formulario #COD_VENDEDOR").val($("#ret_COD_VENDEDOR_" + index).val());
		$("#formulario #COD_EXT_VENDEDOR").val($("#ret_COD_EXT_VENDEDOR_" + index).val());
		$("#formulario #COD_UNIVEND").val($("#ret_COD_UNIVEND_" + index).val()).trigger("chosen:updated");
		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
	}
</script>