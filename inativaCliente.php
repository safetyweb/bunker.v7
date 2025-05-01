<?php

//echo "<h5>_".$opcao."</h5>";

$hashLocal = mt_rand();

$adm = $connAdm->connAdm();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_inativa = fnLimpaCampoZero($_REQUEST['COD_INATIVA']);
		$cod_cliente = fnLimpaCampo($_REQUEST['COD_CLIENTE']);
		$cod_motivo = fnLimpaCampo($_REQUEST['COD_MOTIVO']);
		$cod_empresa = fnLimpaCampo($_REQUEST['COD_EMPRESA']);
		$conn = conntemp($cod_empresa, "");

		$nom_usuario = $_SESSION["SYS_NOM_USUARIO"];
		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
		$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$MODULO = $_GET['mod'];
		$COD_MODULO = fndecode($_GET['mod']);

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		if ($opcao != '') {

			// CREATE TABLE MOTIVO_INATIVA(
			// COD_INATIVA INT PRIMARY KEY AUTO_INCREMENT,
			// COD_CLIENTE INT,
			// COD_MOTIVO INT, 
			// DATA_CADASTR TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
			// COD_USUCADA INT
			// )

			//mensagem de retorno
			switch ($opcao) {
				case 'CAD':

					$sql = "INSERT INTO MOTIVO_INATIVA(
											COD_CLIENTE,
											COD_EMPRESA,
											COD_MOTIVO,
											COD_USUCADA
										) VALUES(
										   	$cod_cliente,
										   	$cod_empresa,
											$cod_motivo,
											$cod_usucada
										)";

					// fnEscreve($sql);

					$arrayProc = mysqli_query($conn, $sql);

					if (!$arrayProc) {

						$cod_erro = Log_error_comand($adm, $conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql, $nom_usuario);
					}

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
?>
				<script>
					try {
						parent.$("#formulario").submit();
						$(this).removeData('bs.modal');
						parent.$('#popModal').modal('hide');
					} catch (err) {}
				</script>
<?php
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
	$cod_cliente = fnDecode($_GET['idc']);
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
	$cod_cliente = 0;
	//fnEscreve('entrou else');
}

$sqlInat = "SELECT * FROM MOTIVO_INATIVA WHERE COD_EMPRESA = $cod_empresa AND COD_CLIENTE = $cod_cliente";

$arrInat = mysqli_query($conn, $sqlInat);
$isInat = mysqli_num_rows($arrInat);

if ($isInat > 0) {

	$qrInat = mysqli_fetch_assoc($arrInat);

	$cod_inativa = $qrInat['COD_INATIVA'];
	$cod_motivo = $qrInat['COD_MOTIVO'];
} else {

	$cod_inativa = 0;
	$cod_motivo = 0;
}


// fnEscreve(fnDecode("ZmFsc2U¢"));

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

									<div class="col-md-4 col-md-offset-4">
										<div class="form-group">
											<label for="inputName" class="control-label required">Motivo da inativação</label>
											<select class="chosen-select-deselect" data-placeholder="Selecione o motivo" name="COD_MOTIVO" id="COD_MOTIVO" style="width:100%!important;" required>
												<?php

												$sql = "SELECT * FROM MOTIVO_INATIVA";
												$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

												while ($qrStatus = mysqli_fetch_assoc($arrayQuery)) {
												?>
													<option value="<?php echo $qrStatus['COD_MOTIVO']; ?>"><?php echo $qrStatus['DES_MOTIVO']; ?></option>
												<?php
												}
												?>
											</select>
											<div class="help-block with-errors"></div>
										</div>
									</div>

								</div>

							</fieldset>

							<div class="push10"></div>
							<hr>
							<div class="form-group text-right col-lg-12">
								<button type="submit" name="CAD" id="CAD" class="btn btn-warning getBtn"><i class="fal fa-flag" aria-hidden="true"></i>&nbsp; Inativar Cliente</button>

							</div>

							<input type="hidden" name="opcao" id="opcao" value="">
							<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
							<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">
							<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">
							<input type="hidden" name="COD_INATIVA" id="COD_INATIVA" value="<?php echo $cod_inativa ?>">
							<input type="hidden" name="COD_CLIENTE" id="COD_CLIENTE" value="<?php echo $cod_cliente ?>">

							<div class="push5"></div>

						</form>

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
			$("#formulario #COD_GRUPOTR").val($("#ret_COD_GRUPOTR_" + index).val());
			$("#formulario #DES_GRUPOTR").val($("#ret_DES_GRUPOTR_" + index).val());
			$('#formulario').validator('validate');
			$("#formulario #hHabilitado").val('S');
		}
	</script>