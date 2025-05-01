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
$cod_redesoc = "";
$des_redesoc = "";
$cod_redes = "";
$nom_usuario = "";
$actual_link = "";
$MODULO = "";
$COD_MODULO = "";
$hHabilitado = "";
$hashForm = "";
$sqlInsert = "";
$arrayInsert = [];
$cod_erro = "";
$sqlUpdate = "";
$arrayUpdate = [];
$sqlExc = "";
$arrayExc = [];
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$formBack = "";
$abaEmpresa = "";
$sql_redes = "";
$arrayQuer_redes = [];
$qrListaUnidades_redes = "";
$qrBuscaRedesSociais = "";



$conn = conntemp($cod_empresa, "");
$adm = $connAdm->connAdm();
$hashLocal = mt_rand();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_redesoc = fnLimpaCampoZero(@$_REQUEST['COD_REDESOC']);
		$des_redesoc = fnLimpaCampo(@$_REQUEST['DES_REDESOC']);
		$cod_empresa = fnLimpaCampo(@$_REQUEST['COD_EMPRESA']);
		$cod_redes = fnLimpaCampoZero(@$_REQUEST['COD_REDES']);

		$nom_usuario = $_SESSION["SYS_NOM_USUARIO"];
		$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$MODULO = @$_GET['mod'];
		$COD_MODULO = fndecode(@$_GET['mod']);

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];


		if ($opcao != '') {

			if ($opcao == 'CAD') {

				$sqlInsert = "INSERT INTO rede_sociais (COD_EMPRESA,DES_REDESOC,COD_REDES) VALUES('" . $cod_empresa . "','" . $des_redesoc . "','" . $cod_redes . "') ";
				//echo $sql;
				$arrayInsert = mysqli_query($conn, $sqlInsert);

				if (!$arrayInsert) {

					$cod_erro = Log_error_comand($adm, $conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sqlInsert, $nom_usuario);
				}

				//fnEscreve($arrayInsert);

				if ($cod_erro == 0 || $cod_erro ==  "") {
					$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
				} else {
					$msgRetorno = "Não foi possível cadastrar o registro : $cod_erro";
				}
			}

			if ($opcao == 'ALT') {

				$sqlUpdate = "UPDATE rede_sociais set DES_REDESOC = '" . $des_redesoc . "', COD_REDES = '" . $cod_redes . "' where COD_REDESOC =  '" . $cod_redesoc . "' and COD_EMPRESA = '" . $cod_empresa . "'  ";
				//echo $sql;
				$arrayUpdate = mysqli_query($conn, $sqlUpdate);

				if (!$arrayUpdate) {

					$cod_erro = Log_error_comand($adm, $conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sqlUpdate, $nom_usuario);
				}
				//fnEscreve($sqlUpdate);

				if ($cod_erro == 0 || $cod_erro ==  "") {
					$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
				} else {
					$msgRetorno = "Não foi possível alterar o registro : $cod_erro";
				}
			}


			if ($opcao == 'EXC') {

				$sqlExc = "delete from rede_sociais where COD_REDESOC =  '" . $cod_redesoc . "' and COD_EMPRESA = '" . $cod_empresa . "'  ";
				//echo $sql;
				$arrayExc = mysqli_query($conn, $sqlExc);

				if (!$arrayUpdate) {

					$cod_erro = Log_error_comand($adm, $conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sqlExc, $nom_usuario);
				}
				//fnEscreve($sqlUpdate);

				if ($cod_erro == 0 || $cod_erro ==  "") {
					$msgRetorno = "Registro Excluído com <strong>sucesso!</strong>";
				} else {
					$msgRetorno = "Não foi possível excluir o registro : $cod_erro";
				}
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
if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode(@$_GET['id']);
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	// fnEscreve($sql);
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
		<div class="portlet portlet-bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fal fa-terminal"></i>
					<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa ?></span>
				</div>

				<?php
				$formBack = "1019";
				include "atalhosPortlet.php";
				?>

			</div>
			<div class="portlet-body">

				<?php if ($msgRetorno <> '') { ?>
					<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<?php echo $msgRetorno; ?>
					</div>
				<?php } ?>

				<?php $abaEmpresa = 1230;
				include "abasEmpresaConfig.php"; ?>

				<div class="push30"></div>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Dados Gerais</legend>

							<div class="row">
								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Código</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_REDESOC" id="COD_REDESOC" value="">
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Tipo da Rede Social</label>
										<select data-placeholder="Selecione uma rede social" name="COD_REDES" id="COD_REDES" class="chosen-select-deselect requiredChk">
											<option value=""></option>
											<?php
											$sql_redes = "select COD_REDES, NOM_REDES from tipo_redes_sociais order by NOM_REDES ";
											$arrayQuer_redes = mysqli_query($adm, $sql_redes);

											while ($qrListaUnidades_redes = mysqli_fetch_assoc($arrayQuer_redes)) {
												echo "
													<option value='" . $qrListaUnidades_redes['COD_REDES'] . "'>" . $qrListaUnidades_redes['NOM_REDES'] . "</option> 
												";
											}
											?>
										</select>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-5">
									<div class="form-group">
										<label for="inputName" class="control-label required">Url / Site</label>
										<input type="text" class="form-control input-sm" name="DES_REDESOC" id="DES_REDESOC" maxlength="150" required>
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
											<th>Tipo</th>
											<th>Url</th>
											<th>Ícone</th>
										</tr>
									</thead>
									<tbody>

										<?php

										$sql = "SELECT * 
															FROM rede_sociais RS
															INNER JOIN $connAdm->DB.tipo_redes_sociais TRD on TRD.COD_REDES = RS.COD_REDES
															WHERE 
															RS.COD_EMPRESA = $cod_empresa 
															ORDER BY TRD.NOM_REDES ";

										// fnEscreve($sql);
										$arrayQuery = mysqli_query($conn, $sql);

										$count = 0;
										while ($qrBuscaRedesSociais = mysqli_fetch_assoc($arrayQuery)) {
											$count++;
											echo "
												<tr>
													<td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></th>
													<td>" . $qrBuscaRedesSociais['COD_REDESOC'] . "</td>
													<td>" . $qrBuscaRedesSociais['NOM_REDES'] . "</td>
													<td>" . $qrBuscaRedesSociais['DES_REDESOC'] . "</td>
													<td align='center'><i class='fa  " . $qrBuscaRedesSociais['DES_ICONE'] . "'></i></td>
												</tr>
												<input type='hidden' id='ret_COD_REDESOC_" . $count . "' value='" . $qrBuscaRedesSociais['COD_REDESOC'] . "'>
												<input type='hidden' id='ret_DES_REDESOC_" . $count . "' value='" . $qrBuscaRedesSociais['DES_REDESOC'] . "'>
												<input type='hidden' id='ret_COD_REDES_" . $count . "' value='" . $qrBuscaRedesSociais['COD_REDES'] . "'>
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
	$(document).ready(function() {

		//chosen
		$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
		$('#formulario').validator();

	});





	function retornaForm(index) {
		$("#formulario #COD_REDESOC").val($("#ret_COD_REDESOC_" + index).val());
		$("#formulario #DES_REDESOC").val($("#ret_DES_REDESOC_" + index).val());
		$("#formulario #COD_REDES").val($("#ret_COD_REDES_" + index).val()).trigger("chosen:updated");
		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
	}
</script>