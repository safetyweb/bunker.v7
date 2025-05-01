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
$cod_cadastr = "";
$nom_empresa = "";
$atributo1 = "";
$atributo2 = "";
$atributo3 = "";
$atributo4 = "";
$atributo5 = "";
$atributo6 = "";
$atributo7 = "";
$atributo8 = "";
$atributo9 = "";
$atributo10 = "";
$atributo11 = "";
$atributo12 = "";
$atributo13 = "";
$cod_sistemas = "";
$nom_usuario = "";
$actual_link = "";
$MODULO = "";
$COD_MODULO = "";
$hHabilitado = "";
$hashForm = "";
$arrayProc = [];
$cod_erro = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$cod_complem = "";
$formBack = "";
$abaEmpresa = "";


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

		$cod_empresa = fnLimpacampo(@$_REQUEST['COD_EMPRESA']);
		$cod_cadastr = $_SESSION["SYS_COD_USUARIO"];
		$nom_empresa = fnLimpacampo(@$_REQUEST['NOM_EMPRESA']);
		$atributo1 = fnLimpacampo(@$_REQUEST['ATRIBUTO1']);
		$atributo2 = fnLimpacampo(@$_REQUEST['ATRIBUTO2']);
		$atributo3 = fnLimpacampo(@$_REQUEST['ATRIBUTO3']);
		$atributo4 = fnLimpacampo(@$_REQUEST['ATRIBUTO4']);
		$atributo5 = fnLimpacampo(@$_REQUEST['ATRIBUTO5']);
		$atributo6 = fnLimpacampo(@$_REQUEST['ATRIBUTO6']);
		$atributo7 = fnLimpacampo(@$_REQUEST['ATRIBUTO7']);
		$atributo8 = fnLimpacampo(@$_REQUEST['ATRIBUTO8']);
		$atributo9 = fnLimpacampo(@$_REQUEST['ATRIBUTO9']);
		$atributo10 = fnLimpacampo(@$_REQUEST['ATRIBUTO10']);
		$atributo11 = fnLimpacampo(@$_REQUEST['ATRIBUTO11']);
		$atributo12 = fnLimpacampo(@$_REQUEST['ATRIBUTO12']);
		$atributo13 = fnLimpacampo(@$_REQUEST['ATRIBUTO13']);

		//fnEscreve($cod_sistemas);

		$nom_usuario = $_SESSION["SYS_NOM_USUARIO"];
		$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$MODULO = @$_GET['mod'];
		$COD_MODULO = fndecode(@$_GET['mod']);

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		if ($opcao != '') {
			$sql = "CALL SP_ALTERA_EMPRESACOMPLEMENTO (
				 '" . $cod_empresa . "', 
				 '" . $atributo1 . "', 
				 '" . $atributo2 . "', 
				 '" . $atributo3 . "', 
				 '" . $atributo4 . "', 
				 '" . $atributo5 . "', 
				 '" . $atributo6 . "', 
				 '" . $atributo7 . "', 
				 '" . $atributo8 . "', 
				 '" . $atributo9 . "', 
				 '" . $atributo10 . "', 
				 '" . $atributo11 . "', 
				 '" . $atributo12 . "', 
				 '" . $atributo13 . "',				 
				 '" . $cod_cadastr . "'    
				) ";

			//echo $sql; 

			$arrayProc = mysqli_query($adm, $sql);

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
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($adm, $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
	}

	//busca dados dos atributos
	$sql = "SELECT A.*,
		(select B.NOM_EMPRESA FROM empresas B where B.COD_EMPRESA = A.COD_EMPRESA ) as NOM_EMPRESA
		FROM EMPRESACOMPLEMENTO A where A.COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($adm, $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		//$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
		$cod_complem = $qrBuscaEmpresa['COD_COMPLEM'];
		//$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$atributo1 = $qrBuscaEmpresa['ATRIBUTO1'];
		$atributo2 = $qrBuscaEmpresa['ATRIBUTO2'];
		$atributo3 = $qrBuscaEmpresa['ATRIBUTO3'];
		$atributo4 = $qrBuscaEmpresa['ATRIBUTO4'];
		$atributo5 = $qrBuscaEmpresa['ATRIBUTO5'];
		$atributo6 = $qrBuscaEmpresa['ATRIBUTO6'];
		$atributo7 = $qrBuscaEmpresa['ATRIBUTO7'];
		$atributo8 = $qrBuscaEmpresa['ATRIBUTO8'];
		$atributo9 = $qrBuscaEmpresa['ATRIBUTO9'];
		$atributo10 = $qrBuscaEmpresa['ATRIBUTO10'];
		$atributo11 = $qrBuscaEmpresa['ATRIBUTO11'];
		$atributo12 = $qrBuscaEmpresa['ATRIBUTO12'];
		$atributo13 = $qrBuscaEmpresa['ATRIBUTO13'];
	}
} else {
	$cod_empresa = 0;
	//fnEscreve('entrou else');
}

//fnEscreve($cod_empresa);	
//fnEscreve(fnDecode(@$_GET['id']));
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
					<span class="text-primary"> <?php echo $NomePg; ?></span>
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

				<?php $abaEmpresa = 1045;
				include "abasProdutosConfig.php"; ?>

				<div class="push30"></div>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Atributos do Produto (Label) </legend>

							<div class="row">

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Empresa</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa ?>">
										<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Atributo 1</label>
										<input type="text" class="form-control input-sm" name="ATRIBUTO1" id="ATRIBUTO1" maxlength="20" value="<?php echo $atributo1; ?>" data-error="Campo obrigatório" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Atributo 2</label>
										<input type="text" class="form-control input-sm" name="ATRIBUTO2" id="ATRIBUTO2" maxlength="20" value="<?php echo $atributo2; ?>" data-error="Campo obrigatório">
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Atributo 3</label>
										<input type="text" class="form-control input-sm" name="ATRIBUTO3" id="ATRIBUTO3" maxlength="20" value="<?php echo $atributo3; ?>" data-error="Campo obrigatório">
										<div class="help-block with-errors"></div>
									</div>
								</div>

							</div>

							<div class="row">

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Atributo 4</label>
										<input type="text" class="form-control input-sm" name="ATRIBUTO4" id="ATRIBUTO4" maxlength="20" value="<?php echo $atributo4; ?>" data-error="Campo obrigatório">
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Atributo 5</label>
										<input type="text" class="form-control input-sm" name="ATRIBUTO5" id="ATRIBUTO5" maxlength="20" value="<?php echo $atributo5; ?>" data-error="Campo obrigatório">
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Atributo 6</label>
										<input type="text" class="form-control input-sm" name="ATRIBUTO6" id="ATRIBUTO6" maxlength="20" value="<?php echo $atributo6; ?>" data-error="Campo obrigatório">
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Atributo 7</label>
										<input type="text" class="form-control input-sm" name="ATRIBUTO7" id="ATRIBUTO7" maxlength="20" value="<?php echo $atributo7; ?>" data-error="Campo obrigatório">
										<div class="help-block with-errors"></div>
									</div>
								</div>

							</div>

							<div class="row">

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Atributo 8</label>
										<input type="text" class="form-control input-sm" name="ATRIBUTO8" id="ATRIBUTO8" maxlength="20" value="<?php echo $atributo8; ?>" data-error="Campo obrigatório">
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Atributo 9</label>
										<input type="text" class="form-control input-sm" name="ATRIBUTO9" id="ATRIBUTO9" maxlength="20" value="<?php echo $atributo9; ?>" data-error="Campo obrigatório">
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Atributo 10</label>
										<input type="text" class="form-control input-sm" name="ATRIBUTO10" id="ATRIBUTO10" maxlength="20" value="<?php echo $atributo10; ?>" data-error="Campo obrigatório">
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Atributo 11</label>
										<input type="text" class="form-control input-sm" name="ATRIBUTO11" id="ATRIBUTO11" maxlength="20" value="<?php echo $atributo11; ?>" data-error="Campo obrigatório">
										<div class="help-block with-errors"></div>
									</div>
								</div>

							</div>

							<div class="row">

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Atributo 12</label>
										<input type="text" class="form-control input-sm" name="ATRIBUTO12" id="ATRIBUTO12" maxlength="20" value="<?php echo $atributo12; ?>" data-error="Campo obrigatório">
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Atributo 13</label>
										<input type="text" class="form-control input-sm" name="ATRIBUTO13" id="ATRIBUTO1" maxlength="20" value="<?php echo $atributo13; ?>" data-error="Campo obrigatório">
										<div class="help-block with-errors"></div>
									</div>
								</div>

							</div>

						</fieldset>

						<div class="push10"></div>
						<hr>
						<div class="form-group text-right col-lg-12">

							<button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
							<?php if ($cod_empresa == "0") { ?>
								<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
							<?php } else { ?>
								<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>
							<?php } ?>
						</div>

						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

						<div class="push5"></div>

					</form>

					<div class="push10"></div>

				</div>

			</div>
		</div>
		<!-- fim Portlet -->
	</div>

</div>

<div class="push20"></div>

<script type="text/javascript">
	$(document).ready(function() {


	});
</script>