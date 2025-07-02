<?php

//echo fnDebug('true');
if ($_SESSION['SYS_COD_USUARIO'] == 11478) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$hashLocal = mt_rand();
$check_SALDO = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_registr = fnLimpaCampoZero($_REQUEST['COD_REGISTR']);
		$cod_empresa = fnLimpaCampo($_REQUEST['COD_EMPRESA']);
		$cod_univend = fnLimpaCampoZero(fnDecode($_REQUEST['COD_UNIVEND']));
		$cod_complem = fnLimpaCampoZero($_REQUEST['COD_COMPLEM']);
		$cod_usucada = $_SESSION[SYS_COD_USUARIO];

		if (empty($_REQUEST['LOG_SALDO'])) {
			$log_saldo = 'N';
		} else {
			$log_saldo = $_REQUEST['LOG_SALDO'];
		}


		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		if ($opcao != '') {

			//mensagem de retorno
			switch ($opcao) {
				case 'CAD':

					$sql = "INSERT INTO COMPLEMENTO_SALDO ( COD_EMPRESA, 
																COD_UNIVEND, 
																COD_COMPLEM,
																LOG_SALDO,
																COD_USUCADA
															  ) VALUES (
															  	'$cod_empresa', 
															  	'$cod_univend', 
															  	'$cod_complem',
															  	'$log_saldo',
															  	'$cod_usucada'
															  );";

					// fnEscreve($sql);

					mysqli_query(connTemp($cod_empresa, ""), $sql);

					$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					break;
				case 'ALT':

					$sql = "UPDATE COMPLEMENTO_SALDO SET
									  	COD_UNIVEND = '$cod_univend', 
									  	COD_COMPLEM = '$cod_complem', 
									  	LOG_SALDO = '$log_saldo',
									  	COD_ALTERAC = $cod_usucada,
									  	DAT_ALTERAC = NOW()
								WHERE COD_EMPRESA = $cod_empresa
								AND COD_REGISTR = $cod_registr";

					mysqli_query(connTemp($cod_empresa, ""), $sql);

					$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";
					break;
				case 'EXC':

					$sql = "DELETE FROM COMPLEMENTO_SALDO WHERE COD_REGISTR = $cod_registr AND COD_EMPRESA = $cod_empresa ";
					mysqli_query(connTemp($cod_empresa, ""), $sql);

					$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";
					break;
					break;
			}
			$msgTipo = 'alert-success';
		}
	}
}


//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode($_GET['id']);
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_fantasi = $qrBuscaEmpresa['NOM_FANTASI'];
	}
} else {
	$cod_empresa = 0;
	//fnEscreve('entrou else');
}

//busca usuário modelo	
$sql = "SELECT * FROM  USUARIOS
			WHERE LOG_ESTATUS='S' AND
				  COD_EMPRESA = $cod_empresa AND
				  COD_TPUSUARIO=10  limit 1  ";
//fnEscreve($sql);
$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
$qrBuscaUsuTeste = mysqli_fetch_assoc($arrayQuery);

if (isset($arrayQuery)) {
	$log_usuario = $qrBuscaUsuTeste['LOG_USUARIO'];
	$des_senhaus = fnDecode($qrBuscaUsuTeste['DES_SENHAUS']);
}

//busca dados da tabela
$sql = "SELECT DES_PAGHOME FROM TOTEM WHERE COD_EMPRESA = $cod_empresa";
//fnEscreve($sql);
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
$qrBuscaSiteTotem = mysqli_fetch_assoc($arrayQuery);

if (isset($qrBuscaSiteTotem)) {
	$des_paghome = $qrBuscaSiteTotem['DES_PAGHOME'];
	if ($des_paghome == "index") {
		$destinoHome = "";
	} else {
		$destinoHome = "banner.do";
	}
}

//fnMostraForm();

?>

<style>
	.chosen-container {
		width: 100% !important;
	}
</style>

<div class="push30"></div>

<div class="row">

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fal fa-terminal"></i>
					<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_fantasi; ?></span>
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
				$abaEmpresa = 1193;
				include "abasEmpresaConfig.php";
				?>

				<div class="push30"></div>

				<?php
				$abaSaldo = 1748;
				include "abasSaldo.php";
				?>

				<div class="push20"></div>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Dados Gerais</legend>

							<div class="row">

								<div class="col-md-1">
									<div class="form-group">
										<label for="inputName" class="control-label required">Código</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_REGISTR" id="COD_REGISTR" value="">
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Unidade de Atendimento</label>
										<select data-placeholder="Selecione a unidade de atendimento" name="COD_UNIVEND" id="COD_UNIVEND" class="chosen-select-deselect" required>
											<option value="0"></option>
											<?php
											$sql = "select COD_UNIVEND, NOM_FANTASI, LOG_ESTATUS from unidadevenda where COD_EMPRESA = '" . $cod_empresa . "' order by NOM_UNIVEND ";
											$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

											while ($qrListaUnidades = mysqli_fetch_assoc($arrayQuery)) {

												if ($qrListaUnidades['LOG_ESTATUS'] == 'N') {
													$disabled = "disabled";
												} else {
													$disabled = " ";
												}
												echo "
																				<option value='" . fnEncode($qrListaUnidades['COD_UNIVEND']) . "'" . $disabled . ">" . $qrListaUnidades['NOM_FANTASI'] . "</option> 
																				";
											}
											?>
										</select>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Complemento</label>
										<select data-placeholder="Selecione o complemento" name="COD_COMPLEM" id="COD_COMPLEM" class="chosen-select-deselect" required>
											<option value=""></option>
											<option value="1">Assinatura Manual</option>
											<option value="2">Assinatura Digital</option>

										</select>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Mostrar tela de saldo original</label>
										<div class="push5"></div>
										<label class="switch">
											<input type="checkbox" name="LOG_SALDO" id="LOG_SALDO" class="switch" value="S" <?php echo $check_SALDO; ?>>
											<span></span>
										</label>
									</div>
								</div>

							</div>

						</fieldset>

						<div class="push10"></div>
						<hr>
						<div class="form-group text-right col-lg-12">

							<button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
							<button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fal fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>
							<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
							<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>

						</div>

						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">
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
											<th>Unidade</th>
											<th>Complemento</th>
											<th class="text-center">Tela Original</th>
										</tr>
									</thead>
									<tbody>

										<?php

										$sql = "SELECT CS.*, UV.NOM_FANTASI FROM COMPLEMENTO_SALDO CS
															LEFT JOIN UNIDADEVENDA UV ON UV.COD_UNIVEND = CS.COD_UNIVEND
															WHERE CS.COD_EMPRESA = $cod_empresa";

										//fnEscreve($sql);
										$arrayQuery = mysqli_query(connTemp($cod_empresa, ""), $sql);

										$count = 0;
										while ($qrLista = mysqli_fetch_assoc($arrayQuery)) {
											$count++;

											if ($qrLista['LOG_SALDO'] == "S") {
												$logSaldo = "<span class='fal fa-check text-success'></span>";
											} else {
												$logSaldo = "";
											}

											if ($qrLista['COD_COMPLEM'] == "1") {
												$complemento = "Assinatura Manual";
											} else if ($qrLista['COD_COMPLEM'] == "2") {
												$complemento = "Assinatura Digital";
											} else {
												$complemento = "";
											}

										?>
											<tr class="dropdown">
												<td class="text-center"><input type="radio" name="radio1" onclick="retornaForm(<?php echo $count; ?>)"></td>
												<td><?php echo $qrLista['COD_REGISTR']; ?></td>
												<td><?php echo $qrLista['NOM_FANTASI']; ?></td>
												<td><?php echo $complemento; ?></td>
												<td class="text-center"><?php echo $logSaldo; ?></td>

											</tr>


											<input type="hidden" id="ret_COD_REGISTR_<?php echo $count; ?>" value="<?php echo $qrLista['COD_REGISTR']; ?>">
											<input type="hidden" id="ret_COD_UNIVEND_<?php echo $count; ?>" value="<?php echo fnEncode($qrLista['COD_UNIVEND']); ?>">
											<input type="hidden" id="ret_COD_COMPLEM_<?php echo $count; ?>" value="<?php echo $qrLista['COD_COMPLEM']; ?>">
											<input type="hidden" id="ret_LOG_SALDO_<?php echo $count; ?>" value="<?php echo $qrLista['LOG_SALDO']; ?>">

										<?php
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
<!-- modal -->
<div class="modal fade" id="popModal" tabindex='-1'>
	<div class="modal-dialog" style="">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"></h4>
			</div>
			<div class="modal-body">
				<iframe frameborder="0" style="width: 100%; height: 80%"></iframe>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<script type="text/javascript">
	function copiaLink(index) {
		$("#AREACODE_OFF_" + index).show();
		$("#AREACODE_" + index).select();
		document.execCommand('copy');
		$('.bt' + index).fadeOut(function() {
			$('.bt' + index).css('background', '#2C3E50');
			$('.bt' + index).text('Copiado');
			$('.bt' + index).fadeIn(200);
		});

		$("#AREACODE_OFF_" + index).hide();
	}


	// ajax
	$("#COD_UNIVEND").change(function() {
		var codBusca = $("#COD_UNIVEND").val();
		var codBusca2 = $("#COD_EMPRESA").val();
		buscaUsuario(codBusca, codBusca2);
	});

	function buscaUsuario(idUnidade, idEmp) {
		$.ajax({
			type: "GET",
			url: "ajxBuscaUsuarioChave.php",
			data: {
				ajx1: idUnidade,
				ajx2: idEmp
			},
			beforeSend: function() {
				$('#divId_usu').html('<div class="loading" style="width: 100%;"></div>');
			},
			success: function(data) {
				$("#divId_usu").html(data);
			},
			error: function() {
				$('#divId_usu').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
			}
		});
	}

	function buscaUsuarioRetornaForm(id_usuario, idUnidade, idEmp) {
		$.ajax({
			type: "GET",
			url: "ajxBuscaUsuarioChave.php",
			data: {
				ajx1: idUnidade,
				ajx2: idEmp
			},
			beforeSend: function() {
				$('#divId_usu').html('<div class="loading" style="width: 100%;"></div>');
			},
			success: function(data) {
				$("#divId_usu").html(data);
				$("#formulario #COD_USUARIO").val(id_usuario).trigger("chosen:updated");
			},
			error: function() {
				$('#divId_usu').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
			}
		});
	}

	function retornaForm(index) {
		$("#formulario #COD_REGISTR").val($("#ret_COD_REGISTR_" + index).val());
		$("#formulario #COD_UNIVEND").val($("#ret_COD_UNIVEND_" + index).val()).trigger("chosen:updated");
		$("#formulario #COD_COMPLEM").val($("#ret_COD_COMPLEM_" + index).val()).trigger("chosen:updated");
		if ($("#ret_LOG_SALDO_" + index).val() == 'S') {
			$('#formulario #LOG_SALDO').prop('checked', true);
		} else {
			$('#formulario #LOG_SALDO').prop('checked', false);
		}

		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
	}
</script>