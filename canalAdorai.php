<?php

//echo "<h5>_".$opcao."</h5>";

$hashLocal = mt_rand();

$cod_empresa = 274;

$conn = conntemp($cod_empresa,"");
$adm = $connAdm->connAdm();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_canal = fnLimpaCampoZero($_REQUEST['COD_CANAL']);
		$cod_empresa = fnLimpaCampo($_REQUEST['COD_EMPRESA']);
		$des_canal = fnLimpaCampo($_REQUEST['DES_CANAL']);
		$num_canal = fnLimpaCampo($_REQUEST['NUM_CANAL']);
		$key_canal = fnLimpaCampo($_REQUEST['KEY_CANAL']);
		if (empty($_REQUEST['LOG_PREF'])) {
			$log_pref = 'N';
		} else {
			$log_pref = $_REQUEST['LOG_PREF'];
		}

		$nom_usuario = $_SESSION["SYS_NOM_USUARIO"];
		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
		$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$MODULO = $_GET['mod'];
		$COD_MODULO = fndecode($_GET['mod']);

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		if ($opcao != '') {

			// CREATE TABLE CANAL_ADORAI(
			// COD_CANAL INT PRIMARY KEY AUTO_INCREMENT,
			// COD_EMPRESA INT,
			// DES_CANAL VARCHAR(100),
			// NUM_CANAL INT,
			// KEY_CANAL VARCHAR(100),
			// DAT_CADASTR TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
			// COD_USUCADA INT
			// )

			//mensagem de retorno
			switch ($opcao) {
				case 'CAD':

					$sql = "INSERT INTO CANAL_ADORAI(
											COD_EMPRESA,
											LOG_PREF,
											DES_CANAL,
											NUM_CANAL,
											KEY_CANAL,
											COD_USUCADA
										) VALUES(
											$cod_empresa,
											'$log_pref',
											'$des_canal',
											'$num_canal',
											'$key_canal',
											$cod_usucada
										)";

					//echo $sql;

					$arrayProc = mysqli_query(conntemp($cod_empresa,""), $sql);

					if (!$arrayProc) {

						$cod_erro = Log_error_comand($adm,conntemp($cod_empresa,""), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql,$nom_usuario);
					}

					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível cadastrar o registro : $cod_erro";
					}
					break;
				case 'ALT':

					$sql = "UPDATE CANAL_ADORAI SET
										LOG_PREF = '$log_pref',
										DES_CANAL = '$des_canal',
										NUM_CANAL = '$num_canal',
										KEY_CANAL = '$key_canal'
							WHERE COD_EMPRESA = $cod_empresa 
							AND COD_CANAL = $cod_canal";

					// fnEscreve($sql);

					$arrayProc = mysqli_query(conntemp($cod_empresa,""), $sql);

					if (!$arrayProc) {

						$cod_erro = Log_error_comand($adm,conntemp($cod_empresa,""), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql,$nom_usuario);
					}

					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível alterar o registro : $cod_erro";
					}
					break;
				case 'EXC':

					$sql = "DELETE FROM CANAL_ADORAI
							WHERE COD_EMPRESA = $cod_empresa 
							AND COD_CANAL = $cod_canal";

					//echo $sql;

					$arrayProc = mysqli_query(conntemp($cod_empresa,""), $sql);

					if (!$arrayProc) {

						$cod_erro = Log_error_comand($adm,conntemp($cod_empresa,""), $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql,$nom_usuario);
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
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($adm, $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
	}
} else {
	$cod_empresa = 274;
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
					<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
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
					$abaAdorai = 1833;
					include "abasAdorai.php";

					$abaManutencaoAdorai = fnDecode($_GET['mod']);
					//echo $abaUsuario;

					//se não for sistema de campanhas

					echo ('<div class="push20"></div>');
					include "abasManutencaoAdorai.php";
				?>

				<div class="push30"></div>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Dados Gerais</legend>

							<div class="row">


								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Canal Preferencial</label>
										<div class="push5"></div>
										<label class="switch">
											<input type="checkbox" name="LOG_PREF" id="LOG_PREF" class="switch" value="S">
											<span></span>
										</label>
									</div>
								</div>

							</div>

							<div class="row">

								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label">Nome do Canal</label>
										<input type="text" class="form-control input-sm" name="DES_CANAL" id="DES_CANAL" maxlength="50" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label">Nro. do Canal</label>
										<input type="text" class="form-control input-sm sp_celphones" name="NUM_CANAL" id="NUM_CANAL" maxlength="50" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label">Chave</label>
										<input type="text" class="form-control input-sm" name="KEY_CANAL" id="KEY_CANAL" maxlength="50" required>
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
						<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">
						<input type="hidden" name="COD_CANAL" id="COD_CANAL" value="">

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
											<th>Nome Canal</th>
											<th>Nro. Canal</th>
											<th>Chave</th>
											<th class='text-center'>Preferencial</th>
										</tr>
									</thead>
									<tbody>

										<?php

										$sql = "SELECT * FROM CANAL_ADORAI WHERE COD_EMPRESA = $cod_empresa";
										$arrayQuery = mysqli_query(conntemp($cod_empresa,""), $sql);

										$count = 0;
										while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery)) {
											$count++;

											if ($qrBuscaModulos['LOG_PREF'] == 'S') {
												$pref = '<i class="fa fa-check" aria-hidden="true"></i>';
											} else {
												$pref = '';
											}

											echo "
													<tr>
														<td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></th>
														<td>" . $qrBuscaModulos['COD_CANAL'] . "</td>
														<td>" . $qrBuscaModulos['DES_CANAL'] . "</td>
														<td>" . $qrBuscaModulos['NUM_CANAL'] . "</td>
														<td>" . $qrBuscaModulos['KEY_CANAL'] . "</td>
														<td class='text-center'>" . $pref . "</td>
													</tr>
													<input type='hidden' id='ret_COD_CANAL_" . $count . "' value='" . $qrBuscaModulos['COD_CANAL'] . "'>
													<input type='hidden' id='ret_LOG_PREF_" . $count . "' value='" . $qrBuscaModulos['LOG_PREF'] . "'>
													<input type='hidden' id='ret_DES_CANAL_" . $count . "' value='" . $qrBuscaModulos['DES_CANAL'] . "'>
													<input type='hidden' id='ret_NUM_CANAL_" . $count . "' value='" . $qrBuscaModulos['NUM_CANAL'] . "'>
													<input type='hidden' id='ret_KEY_CANAL_" . $count . "' value='" . $qrBuscaModulos['KEY_CANAL'] . "'>
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

	$(function(){
		
		var SPMaskBehavior = function (val) {
			return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
		},
		spOptions = {
			onKeyPress: function(val, e, field, options) {
				field.mask(SPMaskBehavior.apply({}, arguments), options);
			}
		};			
		
		$('.sp_celphones').mask(SPMaskBehavior, spOptions);	
		
	});

	function retornaForm(index) {

		$("#formulario #COD_CANAL").val($("#ret_COD_CANAL_" + index).val());
		$("#formulario #DES_CANAL").val($("#ret_DES_CANAL_" + index).val());
		$("#formulario #NUM_CANAL").val($("#ret_NUM_CANAL_" + index).val());
		$("#formulario #KEY_CANAL").val($("#ret_KEY_CANAL_" + index).val());
		if ($("#ret_LOG_PREF_" + index).val() == 'S') {
			$('#formulario #LOG_PREF').prop('checked', true);
		} else {
			$('#formulario #LOG_PREF').prop('checked', false);
		}
		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');

	}

</script>