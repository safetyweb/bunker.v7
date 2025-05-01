<?php
//echo fnDebug('true');

$hashLocal = mt_rand();

//verifica se vem da tela sem pop up
if (is_null($_GET['pre'])) {
	$log_preconf = 'N';
} else {
	$log_preconf = 'S';
}

if ($_SESSION['SYS_COD_EMPRESA'] == 2) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
		$cod_campanha = fnLimpaCampoZero($_REQUEST['COD_CAMPANHA']);
		// $num_sorteio = fnLimpaCampoZero($_REQUEST['NUM_SORTEIO']);
		$num_sorteado = fnLimpaCampoZero($_REQUEST['NUM_SORTEADO']);
		$cod_cupom = fnLimpaCampoZero($_REQUEST['COD_CUPOM']);

		if (isset($_POST['COD_UNIVEND'])) {
			$cod_univend = fnLimpaCampo($_REQUEST['COD_UNIVEND']);
		} else {
			$cod_univend = "0";
		}

		// fnEscreve($cod_univend);


		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

		//fnEscreve($cod_empresa);

		if ($opcao != '') {

			$sql = "CALL SP_CUPOM_SORTEADO (
			" . $cod_cupom . ", 
			" . $cod_empresa . ", 
			" . $cod_campanha . ", 
			'" . $cod_univend . "',    
			" . $num_sorteado . "
		) ";

			$arrayProc = mysqli_query(connTemp($cod_empresa, ''), $sql);

			if (!$arrayProc) {

				$cod_erro = Log_error_comand($adm, $conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql, $nom_usuario);
			}
		}

		//mensagem de retorno
		switch ($opcao) {
			case 'ALT':
				if ($cod_erro == 0 || $cod_erro ==  "") {
					$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					echo '<script>
			setTimeout(function() {
				parent.$("#popModal").modal("hide");
				parent.window.location.href = parent.window.location.href;
				}, 2000);
				</script>';
				} else {
					$msgRetorno = "Não foi possível cadastrar o registro : $cod_erro";
				}
				break;
		}
		$msgTipo = 'alert-success';
	}
}

//busca dados da empresa
$cod_empresa = fnDecode($_GET['id']);
$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
//fnEscreve($sql);
$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

//fnEscreve($qrBuscaEmpresa['NOM_FANTASI']);

if (isset($arrayQuery)) {
	$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
	$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
}

$cod_campanha = fnDecode($_GET['idc']);
$sql = "SELECT CP.COD_CAMPANHA, CR.COD_UNIVENDESP AS COD_UNIVEND FROM CAMPANHA AS CP
		INNER JOIN CAMPANHAREGRA AS CR ON CP.COD_CAMPANHA = CR.COD_CAMPANHA
		where CP.COD_CAMPANHA = '" . $cod_campanha . "' ";
// fnEscreve($sql);
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

$qrBuscaCampanha = mysqli_fetch_assoc($arrayQuery);

if (isset($arrayQuery)) {
	// fnEscreveArray($qrBuscaCampanha);
	$cod_campanha = $qrBuscaCampanha['COD_CAMPANHA'];
	$cod_univend = $qrBuscaCampanha['COD_UNIVEND'];
}

$cod_cupom = $_GET['idcp'];

?>

<?php if ($popUp != "true") { ?>
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

					<div class="login-form">

						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

							<fieldset>
								<legend>Dados Gerais</legend>

								<div class="row">
									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Número do Concurso</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_CUPOM" id="COD_CUPOM" value="<?php echo $cod_cupom ?>">
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<!--<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Número do Concurso</label>
											<input type="text" class="form-control input-sm int" name="NUM_SORTEIO" id="NUM_SORTEIO" maxlength="20">
											<div class="help-block with-errors">Loteria Federal</div>
										</div>
									</div>-->

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Número Sorteado</label>
											<input type="text" class="form-control input-sm int" name="NUM_SORTEADO" id="NUM_SORTEADO" maxlength="20">
											<div class="help-block with-errors">Loteria Federal</div>
										</div>
									</div>

								</div>

							</fieldset>

							<div class="push10"></div>
							<hr>

							<div class="form-group text-right col-md-12">
								<button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>
								<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
							</div>

							<input type="hidden" name="opcao" id="opcao" value="">
							<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
							<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>" />
							<input type="hidden" name="COD_CAMPANHA" id="COD_CAMPANHA" value="<?php echo $cod_campanha; ?>" />
							<input type="hidden" name="COD_UNIVEND" id="COD_UNIVEND" value="<?php echo $cod_univend; ?>" />
							<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

							<div class="push5"></div>

						</form>
					</div>
				</div>
				</div>
				<!-- fim Portlet -->
			</div>
	</div>