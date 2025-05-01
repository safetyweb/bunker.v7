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
$cod_subcate = "";
$cod_categor = "";
$cod_subexte = "";
$des_subcate = "";
$des_subabre = "";
$des_icones = "";
$des_subicon = "";
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
$cod_externo = "";
$des_categor = "";
$des_abrevia = "";
$formBack = "";
$abaMarkaPontos = "";
$qrListaCategoria = "";
$qrBuscaProdutos = "";



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

		$cod_subcate = fnLimpaCampoZero(@$_REQUEST['COD_SUBCATE']);
		$cod_categor = fnLimpaCampoZero(@$_REQUEST['COD_CATEGOR']);
		$cod_empresa = fnLimpaCampoZero(@$_REQUEST['COD_EMPRESA']);
		$cod_subexte = fnLimpaCampo(@$_REQUEST['COD_SUBEXTE']);
		$des_subcate = fnLimpaCampo(@$_REQUEST['DES_SUBCATE']);
		$des_subabre = fnLimpaCampo(@$_REQUEST['DES_SUBABRE']);
		$des_icones = fnLimpaCampo(@$_REQUEST['DES_ICONES']);
		$des_subicon = fnLimpaCampo(@$_REQUEST['DES_ICONES']);

		$nom_usuario = $_SESSION["SYS_NOM_USUARIO"];
		$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$MODULO = @$_GET['mod'];
		$COD_MODULO = fndecode(@$_GET['mod']);

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		//fnEscreve($des_icones);	

		if ($opcao != '') {

			$sql = "CALL SP_ALTERA_SUB_PROMOCAO (
				 '" . $cod_subcate . "', 
				 '" . $cod_categor . "', 
				 '" . $cod_empresa . "', 
				 '" . $cod_subexte . "', 
				 '" . $des_subcate . "', 
				 '" . $des_subabre . "', 
				 '" . $des_subicon . "', 
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
				where EMPRESAS.COD_EMPRESA = " . $cod_empresa . " ";

	//fnEscreve($sql);
	$arrayQuery = mysqli_query($conn, $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($qrBuscaEmpresa)) {
		$nom_empresa = @$qrBuscaEmpresa['NOM_FANTASI'];
		$cod_categor = @$qrBuscaEmpresa['COD_SUBCATE'];
		$cod_externo = @$qrBuscaEmpresa['COD_SUBEXTE'];
		$des_categor = @$qrBuscaEmpresa['DES_SUBCATE'];
		$des_abrevia = @$qrBuscaEmpresa['DES_SUBABRE'];
		$des_icones = @$qrBuscaEmpresa['DES_ICONES'];
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

				<?php
				//menu superior - markapontos
				$abaMarkaPontos = 1249;
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
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_SUBCATE" id="COD_SUBCATE" value="">
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Empresa</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa ?>">
										<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">
									</div>
								</div>


								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Grupo de Produto</label>
										<select data-placeholder="Selecione um grupo" name="COD_CATEGOR" id="COD_CATEGOR" class="chosen-select-deselect requiredChk" required>
											<option value=""></option>
											<?php

											$sql = "SELECT COD_CATEGOR,DES_CATEGOR FROM CAT_PROMOCAO where COD_EMPRESA = " . $cod_empresa . " order by DES_CATEGOR ";
											$arrayQuery = mysqli_query($conn, $sql);

											while ($qrListaCategoria = mysqli_fetch_assoc($arrayQuery)) {

												echo "
													<option value='" . $qrListaCategoria['COD_CATEGOR'] . "'>" . $qrListaCategoria['DES_CATEGOR'] . "</option> 
												";
											}

											?>
										</select>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label required">Nome do Grupo</label>
										<input type="text" class="form-control input-sm" name="DES_SUBCATE" id="DES_SUBCATE" maxlength="20" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>

							</div>

							<div class="row">

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Código Externo</label>
										<input type="text" class="form-control input-sm" name="COD_SUBEXTE" id="COD_SUBEXTE" value="<?php echo $cod_externo ?>">
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Abreviação</label>
										<input type="text" class="form-control input-sm" name="DES_SUBABRE" id="DES_SUBABRE" value="<?php echo $des_abrevia ?>">
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
							<!--<button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fal fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>-->

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
											<th width="40"></th>
											<th>Código</th>
											<th>Grupo </th>
											<th>Nome do Grupo</th>
											<th>Abreviação</th>
											<th>Ícone</th>
										</tr>
									</thead>
									<tbody>

										<?php

										$sql = "select A.*, (select B.DES_CATEGOR from CAT_PROMOCAO B where B.COD_CATEGOR = A.COD_CATEGOR) as DES_CATEGOR 
															from SUB_PROMOCAO A where A.COD_EMPRESA = " . $cod_empresa . "  order by A.DES_SUBCATE, DES_CATEGOR";
										$arrayQuery = mysqli_query($conn, $sql);

										$count = 0;
										while ($qrBuscaProdutos = mysqli_fetch_assoc($arrayQuery)) {
											$count++;
											echo "
															<tr>
															  <td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></td>
															  <td>" . $qrBuscaProdutos['COD_SUBCATE'] . "</td>
															  <td>" . $qrBuscaProdutos['DES_CATEGOR'] . "</td>
															  <td>" . $qrBuscaProdutos['DES_SUBCATE'] . "</td>
															  <td>" . $qrBuscaProdutos['DES_SUBABRE'] . "</td>
															  <td align='center'><i class='fa  " . $qrBuscaProdutos['DES_SUBICON'] . "'></i></td>
															</tr>
															<input type='hidden' id='ret_COD_SUBCATE_" . $count . "' value='" . $qrBuscaProdutos['COD_SUBCATE'] . "'>
															<input type='hidden' id='ret_COD_CATEGOR_" . $count . "' value='" . $qrBuscaProdutos['COD_CATEGOR'] . "'>
															<input type='hidden' id='ret_COD_SUBEXTE_" . $count . "' value='" . $qrBuscaProdutos['COD_SUBEXTE'] . "'>
															<input type='hidden' id='ret_DES_SUBCATE_" . $count . "' value='" . $qrBuscaProdutos['DES_SUBCATE'] . "'>
															<input type='hidden' id='ret_DES_SUBABRE_" . $count . "' value='" . $qrBuscaProdutos['DES_SUBABRE'] . "'>
															<input type='hidden' id='ret_DES_ICONES_" . $count . "' value='" . $qrBuscaProdutos['DES_SUBICON'] . "'>
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

		//chosen
		$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
		$('#formulario').validator();

		//capturando o ícone selecionado no botão
		$('#btniconpicker').on('change', function(e) {
			$('#DES_ICONE').val(e.icon);
			//alert($('#DES_ICONE').val());
		});

	});

	function retornaForm(index) {
		$("#formulario #COD_SUBCATE").val($("#ret_COD_SUBCATE_" + index).val());
		$("#formulario #COD_CATEGOR").val($("#ret_COD_CATEGOR_" + index).val()).trigger("chosen:updated");
		$("#formulario #COD_SUBEXTE").val($("#ret_COD_SUBEXTE_" + index).val());
		$("#formulario #DES_SUBCATE").val($("#ret_DES_SUBCATE_" + index).val());
		$("#formulario #DES_SUBABRE").val($("#ret_DES_SUBABRE_" + index).val());
		$("#formulario #DES_ICONES").val($("#ret_DES_ICONES_" + index).val());
		$('#btnIcon').iconpicker('setIcon', $("#ret_DES_ICONES_" + index).val());
		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
	}
</script>