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
$cod_usucada = "";
$hHabilitado = "";
$hashForm = "";
$cod_tipo = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$popUp = "";
$id_rpad = "";
$hashLocal = mt_rand();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {

		$_SESSION['last_request']  = $request;

		$cod_empresa = fnLimpaCampoZero(@$_REQUEST['COD_EMPRESA']);
		$id_link = fnLimpaCampo(fnDecode(@$_REQUEST['ID_LINK']));
		$des_titulo = fnLimpaCampo(@$_REQUEST['DES_TITULO']);
		$des_link = fnLimpaCampo(@$_REQUEST['DES_LINK']);

		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		// fnEscreve($log_permite);
		// fnEscreve($log_principal);


		if ($opcao != '') {

			//mensagem de retorno
			switch ($opcao) {
				case 'CAD':

					$result = fnEncurtador($des_titulo, "", "", $des_link, "ALL", $cod_empresa, $connAdm->connAdm(), 0);

                    // echo "<pre>";
                    // print_r($result);
                    // echo "</pre>";

					$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					break;

				case 'ALT':

					$result = fnAltEncurtador($des_titulo, $id_link, $cod_empresa, $connAdm->connAdm(), $des_link);

					// echo "<pre>";
					// fnEscreve($id_link);
                    // print_r($result);
                    // echo "</pre>";

					$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";
					break;
				case 'EXC':

					$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";
					break;
			}
			$msgTipo = 'alert-success';

			//atualiza lista iframe				
?>
			<script>
				try {
					parent.location.reload();
				} catch (err) {}
			</script>
<?php

		}
	}
}

//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {

	//busca dados da empresa
	$cod_empresa = fnDecode(@$_GET['id']);
	if($_GET['idl']){
		$id_rpad = fnDecode(@$_GET['idl']);
	}
	$popUp = @$_GET['pop'];

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

$sql = "SELECT * FROM TAB_ENCURTADOR WHERE COD_EMPRESA = " . $cod_empresa . " AND ID_RPAD = '" . $id_rpad . "'";
// fnEscreve($sql);
$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
if (mysqli_num_rows($arrayQuery) > 0) {
	$qrBuscaLink = mysqli_fetch_assoc($arrayQuery);
	$id_link = $qrBuscaLink['id'];
	$des_titulo = $qrBuscaLink['titulo'];
	$des_link = $qrBuscaLink['url_original'];
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

								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label required">Título</label>
										<input type="text" class="form-control input-sm" name="DES_TITULO" id="DES_TITULO" value="<?php echo $des_titulo; ?>" required> </div>
								</div>

                                <div class="col-md-8">
									<div class="form-group">
										<label for="inputName" class="control-label required">Link</label>
										<input type="text" class="form-control input-sm" name="DES_LINK" id="DES_LINK" value="<?php echo $des_link; ?>" required>
									</div>
								</div>

							</div>

							<div class="push10"></div>

						</fieldset>


						<div class="push10"></div>
						<hr>
						<div class="form-group text-right col-lg-12">

                        <?php
						if ($id_rpad == "") {
						?>
							<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
						<?php
						} else {
						?>
							<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Alterar</button>
						<?php
						}
						?>

						</div>

						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
						<input type="hidden" name="ID_LINK" id="ID_LINK" value="<?=fnEncode($id_link)?>">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

						<div class="push5"></div>

					</form>

					<div class="push"></div>

				</div>

				</div>
			</div>
			<!-- fim Portlet -->
	</div>

</div>


<script type="text/javascript">
	$(document).ready(function() {
		//chosen
		$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
		$('#formulario').validator();

		
	});
</script>