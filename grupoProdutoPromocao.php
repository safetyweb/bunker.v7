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
$cod_categor = "";
$cod_externo = "";
$des_categor = "";
$des_abrevia = "";
$des_icones = "";
$nom_usuario = "";
$actual_link = "";
$MODULO = "";
$COD_MODULO = "";
$opcao = "";
$hHabilitado = "";
$hashForm = "";
$arrayProc = [];
$cod_erro = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$popUp = "";
$formBack = "";
$abaMarkaPontos = "";
$qrBuscaProdutos = "";
$mostraDown = "";


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

		$cod_categor = fnLimpaCampoZero(@$_REQUEST['COD_CATEGOR']);
		$cod_empresa = fnLimpaCampoZero(@$_REQUEST['COD_EMPRESA']);
		$cod_externo = fnLimpaCampo(@$_REQUEST['COD_EXTERNO']);
		$des_categor = fnLimpaCampo(@$_REQUEST['DES_CATEGOR']);
		$des_abrevia = fnLimpaCampo(@$_REQUEST['DES_ABREVIA']);
		$des_icones = fnLimpaCampo(@$_REQUEST['DES_ICONES']);

		$nom_usuario = $_SESSION["SYS_NOM_USUARIO"];
		$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$MODULO = @$_GET['mod'];
		$COD_MODULO = fndecode(@$_GET['mod']);

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		//fnEscreve($des_icones);	

		if ($opcao != '') {

			$sql = "CALL SP_ALTERA_CAT_PROMOCAO (
				 '" . $cod_categor . "', 
				 '" . $cod_empresa . "', 
				 '" . $cod_externo . "', 
				 '" . $des_categor . "', 
				 '" . $des_abrevia . "', 
				 '" . $des_icones . "', 
				 '" . $_SESSION["SYS_COD_USUARIO"] . "', 
				 '" . $opcao . "'    
				) ";

			//echo $sql;

			$arrayProc = mysqli_query($conn, trim($sql));

			if (!$arrayProc) {

				$cod_erro = Log_error_comand($adm, $conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql, $nom_usuario);
			}

			//mensagem de retorno
			switch ($opcao) {
				case 'CAD':
					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível cadastrar o registro : $cod_erro";
					}
					break;
				case 'ALT':
					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível alterar o registro : $cod_erro";
					}
					break;
				case 'EXC':
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
if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode(@$_GET['id']);

	$sql = "SELECT EMPRESAS.NOM_FANTASI,CATEGORIA.* FROM $connAdm->DB.EMPRESAS
				left JOIN CATEGORIA ON CATEGORIA.COD_EMPRESA=EMPRESAS.COD_EMPRESA
				where EMPRESAS.COD_EMPRESA = $cod_empresa ";

	//fnEscreve($sql);
	//fntesteSql(connTemp($cod_empresa,''),$sql);
	$arrayQuery = mysqli_query($conn, $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($qrBuscaEmpresa)) {
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
		$cod_categor = $qrBuscaEmpresa['COD_CATEGOR'];
		$cod_externo = $qrBuscaEmpresa['COD_EXTERNO'];
		$des_categor = $qrBuscaEmpresa['DES_CATEGOR'];
		$des_abrevia = $qrBuscaEmpresa['DES_ABREVIA'];
		$des_icones = $qrBuscaEmpresa['DES_ICONES'];
	}
} else {
	$cod_empresa = 0;
	$nom_empresa = "";
	$cod_categor = 0;
	$cod_externo = "";
	$des_categor = "";
	$des_abrevia = "";
	$des_icones = "";
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
							<i class="fal fa-terminal"></i>
							<span class="text-primary"><?php echo $NomePg; ?></span>
						</div>
						<?php
						$formBack = 1019;
						include "atalhosPortlet.php";
						?>

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
					//menu superior - markapontos
					$abaMarkaPontos = 1248;
					include "abasMarkapontos.php";
					?>

					<div class="push30"></div>

					<div class="login-form">

						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

							<fieldset>
								<legend>Dados Gerais</legend>

								<div class="row">
									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Código</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_CATEGOR" id="COD_CATEGOR" value="">
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
											<label for="inputName" class="control-label required">Nome do Grupo</label>
											<input type="text" class="form-control input-sm" name="DES_CATEGOR" id="DES_CATEGOR" maxlength="20" required>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Abreviação</label>
											<input type="text" class="form-control input-sm" name="DES_ABREVIA" id="DES_ABREVIA" value="">
										</div>
									</div>

								</div>

								<div class="row">

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Código Externo</label>
											<input type="text" class="form-control input-sm" name="COD_EXTERNO" id="COD_EXTERNO" value="">
										</div>
									</div>

									<div class="col-md-1">
										<div class="form-group">
											<label for="inputName" class="control-label">Ícone</label><br />
											<button class="btn btn-primary" id="btniconpicker" data-iconset="fontawesome" data-icon="vazio" role="iconpicker" data-arrow-prev-icon-class="fas fa-arrow-left" data-arrow-next-icon-class="fas fa-arrow-right" data-rows="6" data-cols="6" data-search-text="Buscar ícone..." data-label-footer="{0} - {1} de {2} ícones" data-label-header="{0} de {1}">
											</button>
											<input type="hidden" name="DES_ICONE" id="DES_ICONE" value="">
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

							<div class="push5"></div>

						</form>

						<div class="push50"></div>

						<div class="col-lg-12">

							<div class="no-more-tables">

								<form name="formLista">

									<table class="table table-bordered table-striped table-hover">
										<thead>
											<tr>
												<th width="50"></th>
												<th>Código</th>
												<th>Cód. Externo </th>
												<th>Nome do Grupo</th>
												<th>Abreviação</th>
												<th>Ícone</th>
											</tr>
										</thead>
										<tbody>

											<?php

											$sql = "select * from CAT_PROMOCAO where COD_EMPRESA = $cod_empresa AND COD_EXCLUSA is null order by DES_CATEGOR";
											$arrayQuery = mysqli_query($conn, $sql);

											$count = 0;
											while ($qrBuscaProdutos = mysqli_fetch_assoc($arrayQuery)) {
												$count++;

												if ($popUp == "true") {
													$mostraDown = "<a href='javascript: downForm($count)' style='margin-left: 10px;'><i class='fal fa-arrow-circle-down' aria-hidden='true'></i></a>";
												} else {
													$mostraDown = "";
												}

												echo "
													<tr>
														<td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'>&nbsp;
														$mostraDown
														</td>
														<td>" . $qrBuscaProdutos['COD_CATEGOR'] . "</td>
														<td>" . $qrBuscaProdutos['COD_EXTERNO'] . "</td>
														<td>" . $qrBuscaProdutos['DES_CATEGOR'] . "</td>
														<td>" . $qrBuscaProdutos['DES_ABREVIA'] . "</td>
														<td align='center'><i class='fa  " . $qrBuscaProdutos['DES_ICONES'] . "'></i></td>
													</tr>
													<input type='hidden' id='ret_COD_CATEGOR_" . $count . "' value='" . $qrBuscaProdutos['COD_CATEGOR'] . "'>
													<input type='hidden' id='ret_COD_EXTERNO_" . $count . "' value='" . $qrBuscaProdutos['COD_EXTERNO'] . "'>
													<input type='hidden' id='ret_DES_CATEGOR_" . $count . "' value='" . $qrBuscaProdutos['DES_CATEGOR'] . "'>
													<input type='hidden' id='ret_DES_ABREVIA_" . $count . "' value='" . $qrBuscaProdutos['DES_ABREVIA'] . "'>
													<input type='hidden' id='ret_DES_ICONES_" . $count . "' value='" . $qrBuscaProdutos['DES_ICONES'] . "'>
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

	<script type="text/javascript">
		$(document).ready(function() {

			//capturando o ícone selecionado no botão
			$('#btniconpicker').on('change', function(e) {
				$('#DES_ICONE').val(e.icon);
				//alert($('#DES_ICONE').val());
			});

		});

		function retornaForm(index) {
			$("#formulario #COD_CATEGOR").val($("#ret_COD_CATEGOR_" + index).val());
			$("#formulario #COD_EXTERNO").val($("#ret_COD_EXTERNO_" + index).val());
			$("#formulario #DES_CATEGOR").val($("#ret_DES_CATEGOR_" + index).val());
			$("#formulario #DES_ABREVIA").val($("#ret_DES_ABREVIA_" + index).val());
			$("#formulario #DES_ICONES").val($("#ret_DES_ICONES_" + index).val());
			$('#btnIcon').iconpicker('setIcon', $("#ret_DES_ICONES_" + index).val());
			$('#formulario').validator('validate');
			$("#formulario #hHabilitado").val('S');
		}

		function downForm(index) {

			try {
				parent.$('#DES_CATEGOR').val($("#ret_DES_CATEGOR_" + index).val());
			} catch (err) {}
			try {
				parent.$('#COD_CATEGOR').val($("#ret_COD_CATEGOR_" + index).val());
			} catch (err) {}
			$(this).removeData('bs.modal');
			console.log('entrou' + index);
			parent.$('#popModalAux').modal('hide');

		}
	</script>