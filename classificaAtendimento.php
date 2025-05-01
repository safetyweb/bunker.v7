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
$cod_classifica = "";
$des_classifica = "";
$abv_classifica = "";
$des_icone = "";
$des_cor = "";
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
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$formBack = "";
$qrLista = "";

$hashLocal = mt_rand();

$conn = conntemp($cod_empresa, "");
$adm = $connAdm->connAdm();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_classifica = fnLimpaCampoZero(@$_REQUEST['COD_CLASSIFICA']);
		$cod_empresa = fnLimpaCampoZero(@$_REQUEST['COD_EMPRESA']);
		$des_classifica = fnLimpaCampo(@$_REQUEST['DES_CLASSIFICA']);
		$abv_classifica = fnLimpaCampo(@$_REQUEST['ABV_CLASSIFICA']);
		$des_icone = fnLimpaCampo(@$_REQUEST['DES_ICONE']);
		$des_cor = fnLimpaCampo(@$_REQUEST['DES_COR']);

		$nom_usuario = $_SESSION["SYS_NOM_USUARIO"];
		$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$MODULO = @$_GET['mod'];
		$COD_MODULO = fndecode(@$_GET['mod']);

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		if ($opcao != '') {

			//mensagem de retorno
			switch ($opcao) {
				case 'CAD':

					$sqlInsert = "INSERT INTO CLASSIFICA_ATENDIMENTO(
										COD_EMPRESA,
										DES_CLASSIFICA,
										ABV_CLASSIFICA,
										DES_ICONE,
										DES_COR
										) VALUES(
										$cod_empresa,
										'$des_classifica',
										'$abv_classifica',
										'$des_icone',
										'$des_cor'
										)";

					// fnEscreve($sql);

					$arrayInsert = mysqli_query($conn, $sqlInsert);
					// mysqli_query(connTemp($cod_empresa,""),$sql);

					if (!$arrayInsert) {

						$cod_erro = Log_error_comand($adm, $conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sqlInsert, $nom_usuario);
					}

					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível cadastrar o registro : $cod_erro";
					}

					break;

				case 'ALT':

					$sqlUpdate = "UPDATE CLASSIFICA_ATENDIMENTO SET
									DES_CLASSIFICA='$des_classifica',
									ABV_CLASSIFICA='$abv_classifica',
									DES_ICONE='$des_icone',
									DES_COR='$des_cor'
								WHERE COD_CLASSIFICA=$cod_classifica
								AND COD_EMPRESA = $cod_empresa
								";

					//fnEscreve($sql);

					//fnTestesql(connTemp($cod_empresa),$sql);				
					$arrayUpdate = mysqli_query($conn, $sqlUpdate);

					if (!$arrayUpdate) {

						$cod_erro = Log_error_comand($adm, $conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sqlUpdate, $nom_usuario);
					}
					//fnEscreve($arrayUpdate);

					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível alterar o registro : $cod_erro";
					}

					break;

				case 'EXC':


					$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";

					break;

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
if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode(@$_GET['id']);
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
		<div class="portlet portlet-bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fal fa-terminal"></i>
					<span class="text-primary"><?php echo $NomePg; ?></span>
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

				<div class="push30"></div>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Dados Gerais</legend>

							<div class="row">
								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Código</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_CLASSIFICA" id="COD_CLASSIFICA" value="">
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Empresa</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa ?>">
										<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label required">Descrição da Classificação</label>
										<input type="text" class="form-control input-sm" name="DES_CLASSIFICA" id="DES_CLASSIFICA" maxlength="50" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Abreviação</label>
										<input type="text" class="form-control input-sm" name="ABV_CLASSIFICA" id="ABV_CLASSIFICA" maxlength="3">
										<div class="help-block with-errors"></div>
									</div>
								</div>

							</div>

							<div class="row">

								<div class="col-md-1">
									<div class="form-group">
										<label for="inputName" class="control-label">Ícone</label><br />
										<button class="btn btn-primary" id="btniconpicker" data-iconset="fontawesome" data-icon="vazio" role="iconpicker" data-arrow-prev-icon-class="fas fa-arrow-left" data-arrow-next-icon-class="fas fa-arrow-right" data-rows="6" data-cols="6" data-search-text="Buscar ícone..." data-label-footer="{0} - {1} de {2} ícones" data-label-header="{0} de {1}">
										</button>
										<input type="hidden" name="DES_ICONE" id="DES_ICONE" value="">
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Cor</label>
										<input type="text" class="form-control input-sm pickColor" style="margin-top: 4px;" name="DES_COR" id="DES_COR" value="<?php echo $des_cor ?>" required>
									</div>
									<div class="help-block with-errors"></div>
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
											<th>Descrição</th>
											<th>Abreviação</th>
											<th>Ícone</th>
										</tr>
									</thead>
									<tbody>

										<?php

										$sql = "SELECT * FROM CLASSIFICA_ATENDIMENTO WHERE COD_EMPRESA = $cod_empresa";
										$arrayQuery = mysqli_query($conn, $sql);

										$count = 0;
										while ($qrLista = mysqli_fetch_assoc($arrayQuery)) {
											$count++;
											echo "
												<tr>
													<td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></th>
													<td>" . $qrLista['COD_CLASSIFICA'] . "</td>
													<td>" . $qrLista['DES_CLASSIFICA'] . "</td>
													<td>" . $qrLista['ABV_CLASSIFICA'] . "</td>
													<td class='text-center'><span class='" . $qrLista['DES_ICONE'] . "' style='color:#" . $qrLista['DES_COR'] . "'></span></td>
												</tr>
												<input type='hidden' id='ret_COD_CLASSIFICA_" . $count . "' value='" . $qrLista['COD_CLASSIFICA'] . "'>
												<input type='hidden' id='ret_DES_CLASSIFICA_" . $count . "' value='" . $qrLista['DES_CLASSIFICA'] . "'>
												<input type='hidden' id='ret_ABV_CLASSIFICA_" . $count . "' value='" . $qrLista['ABV_CLASSIFICA'] . "'>
												<input type='hidden' id='ret_DES_ICONE_" . $count . "' value='" . $qrLista['DES_ICONE'] . "'>
												<input type='hidden' id='ret_DES_COR_" . $count . "' value='" . $qrLista['DES_COR'] . "'>
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

<link rel="stylesheet" href="../css/bootstrap-iconpicker.min.css" />

<script type="text/javascript" src="../js/bootstrap-iconpicker-iconset-fa5.js"></script>
<script type="text/javascript" src="../js/bootstrap-iconpicker.js"></script>

<script src="js/plugins/minicolors/jquery.minicolors.min.js"></script>
<link rel="stylesheet" href="js/plugins/minicolors/jquery.minicolors.css">

<script type="text/javascript">
	$(document).ready(function() {
		//capturando o ícone selecionado no botão
		$('#btniconpicker').on('change', function(e) {
			$('#DES_ICONE').val(e.icon);
			//alert($('#DES_ICONE').val());
		});

		//color picker
		$('.pickColor').minicolors({
			control: $(this).attr('data-control') || 'hue',
			theme: 'bootstrap'
		});

		icone = "<?php echo $des_icone ?>";

		cor = "<?php echo $des_cor ?>";

		if (icone == "") {
			icone = "fal fa-chart-bar";
		}

		if (cor == "") {
			cor = "#2C3E50";
		}

		$("#btniconpicker").iconpicker('setIcon', icone);
		$("#DES_ICONE").val(icone);

		$("#DES_COR").minicolors('value', cor);

	});

	function retornaForm(index) {
		$("#formulario #COD_CLASSIFICA").val($("#ret_COD_CLASSIFICA_" + index).val());
		$("#formulario #DES_CLASSIFICA").val($("#ret_DES_CLASSIFICA_" + index).val());
		$("#formulario #ABV_CLASSIFICA").val($("#ret_ABV_CLASSIFICA_" + index).val());
		$("#formulario #DES_ICONE").val($("#ret_DES_ICONE_" + index).val());
		$("#formulario #DES_COR").val($("#ret_DES_COR_" + index).val());
		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
	}
</script>