<?php

//echo "<h5>_".$opcao."</h5>"; 


$hashLocal = mt_rand();
$mod = fnDecode($_GET['mod']);

// fnEscreve($mod);
// fnEscreve($cod_chamado);


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_empresa = fnLimpaCampo($_REQUEST['COD_EMPRESA']);
		$cod_desafio = fnLimpaCampoZero($_REQUEST['COD_DESAFIO']);
		$cod_cliente = fnLimpaCampo($_REQUEST['COD_CLIENTE']);
		$nom_follow = fnLimpaCampo($_REQUEST['NOM_FOLLOW']);
		$des_coment = fnLimpaCampo($_REQUEST['DES_COMENT']);

		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];


		$opcao = $_REQUEST['opcao'];
		// $hHabilitado = $_REQUEST['hHabilitado'];
		// $hashForm = $_REQUEST['hashForm'];

		if ($opcao != '') {

			//mensagem de retorno
			switch ($opcao) {
				case 'CAD':

					$sql = "INSERT INTO FOLLOW_CLIENTE(
											COD_EMPRESA,
											COD_DESAFIO,
											COD_CLIENTE,
											NOM_FOLLOW,
											DES_COMENT,
											TIP_FOLLOW,
											COD_SAC,
											COD_USUCADA
											) VALUES(
											$cod_empresa,
											$cod_desafio,
											$cod_cliente,
											'$nom_follow',
											'$des_coment',
											1,
											0,
											$cod_usucada
											)";
					// fnEscreve($sql);
					mysqli_query(connTemp($cod_empresa, ''), $sql);

?>
					<script>
						try {
							parent.$('#REFRESH_FOLLOW').val("S");
						} catch (err) {}
					</script>
<?php

					$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					break;
				case 'ALT':
					$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";
					break;
				case 'EXC':
					$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";
					break;
					break;
			}
			$msgTipo = 'alert-success';
		}
	}
}

$cod_cliente = fnDecode($_GET['idC']);

//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode($_GET['id']))) && fnDecode($_GET['id']) != 0) {
	//busca dados da empresa
	$cod_empresa = fnDecode($_GET['id']);
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
	}
} else {
	$cod_empresa = 7;
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
	}

	//fnEscreve('entrou else');
}

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

					<style>
						li {
							list-style: none;
						}

						.chec-radio .radio-inline .clab {
							cursor: pointer;
							background: #e7e7e7;
							padding: 7px 20px;
							text-align: center;
							text-transform: uppercase;
							color: #2c3e50;
							position: relative;
							height: 34px;
							float: left;
							margin: 0;
							margin-bottom: 5px;
						}

						.chec-radio label.radio-inline input[type="radio"] {
							display: none;
						}

						.chec-radio label.radio-inline input[type="radio"]:checked+div {
							color: #fff;
							background-color: #2c3e50;
						}

						.chec-radio label.radio-inline input[type="radio"]:checked+div:before {
							content: "\e013";
							margin-right: 5px;
							font-family: 'Glyphicons Halflings';
						}
					</style>

					<div class="login-form">

						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

							<fieldset>
								<legend>Dados Follow Up</legend>

								<div class="row">

									<div class="col-md-12">
										<div class="form-group">
											<label for="NOM_FOLLOW" class="control-label required">Título: </label>
											<input type="text" class="form-control input-sm" name="NOM_FOLLOW" id="NOM_FOLLOW" maxlength="100" required>
										</div>
									</div>

								</div>

								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label for="DES_COMENT" class="control-label required">Comentário: </label>
											<textarea class="form-control input-sm" rows="6" name="DES_COMENT" id="DES_COMENT"></textarea>
											<div class="help-block with-errors"></div>
										</div>
									</div>
								</div>

							</fieldset>

							<input type="hidden" name="opcao" id="opcao" value="0">
							<input type="hidden" name="COD_DESAFIO" value="0">
							<input type="hidden" name="COD_CLIENTE" value="<?= $cod_cliente ?>">
							<input type="hidden" name="COD_EMPRESA" value="<?= $cod_empresa ?>">

							<div class="push10"></div>
							<hr>
							<div class="form-group text-right">

								<button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>
								<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Cadastrar</button>

							</div>

						</form>

						<div class="push50"></div>

					</div>

				</div>

				</div>

			</div>

	</div>

</div>

<script type="text/javascript">
	// $(function(){
	// 	$('#CAD_COMMENT').click(function(){

	// 		$.ajax({
	// 			method: 'POST',
	// 			url: 'ajxComentarioDesafio.php?tpF=manual',
	// 			data: $('#formulario').serialize(),
	// 			success:function(data){
	// 				// $('#relatorioFollowUp').html(data);
	// 				$('#DES_COMENT').val('');
	// 				$('#NOM_FOLLOW').val('');	
	// 			}
	// 		});

	// 	});
	// });
</script>