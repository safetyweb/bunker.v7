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
$cod_acaoint = "";
$nom_acaoint = "";
$key_acaoint = "";
$des_acaoint = "";
$nom_usuario = "";
$actual_link = "";
$MODULO = "";
$COD_MODULO = "";
$hHabilitado = "";
$hashForm = "";
$arrayProc = [];
$cod_erro = "";
$abaModulo = "";
$arrayQuery = [];
$qrBuscaFases = "";


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

		$cod_acaoint = fnLimpaCampoZero(@$_REQUEST['COD_ACAOINT']);
		$nom_acaoint = fnLimpaCampo(@$_REQUEST['NOM_ACAOINT']);
		$key_acaoint = fnLimpaCampo(@$_REQUEST['KEY_ACAOINT']);
		$des_acaoint = fnLimpaCampo(@$_REQUEST['DES_ACAOINT']);

		$nom_usuario = $_SESSION["SYS_NOM_USUARIO"];
		$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$MODULO = @$_GET['mod'];
		$COD_MODULO = fndecode(@$_GET['mod']);

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		if ($opcao != '') {

			$sql = "CALL SP_ALTERA_INTEGRA_ACAOMTZ (
					'" . $cod_acaoint . "', 
					'" . $nom_acaoint . "', 
					'" . $key_acaoint . "', 
					'" . $des_acaoint . "', 
				 '" . $opcao . "'    
				) ";

			//echo $sql;
			//fntestesql($connAdm->connAdm(),trim($sql));

			$arrayProc = mysqli_query($adm, trim($sql));

			if (!$arrayProc) {

				$cod_erro = Log_error_comand($adm, $conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql, $nom_usuario);
			}
			//fnescreve($cod_erro);

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
				<?php include "atalhosPortlet.php"; ?>
			</div>
			<div class="portlet-body">

				<?php if ($msgRetorno <> '') { ?>
					<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<?php echo $msgRetorno; ?>
					</div>
				<?php } ?>

				<?php $abaModulo = 1152;
				include "abasMatrizIntegracao.php"; ?>

				<div class="push30"></div>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Dados Gerais</legend>

							<div class="row">

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Código</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_ACAOINT" id="COD_ACAOINT" value="">
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Nome da Ação</label>
										<input type="text" class="form-control input-sm" name="NOM_ACAOINT" id="NOM_ACAOINT" maxlength="20" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Chave da Ação </label>
										<input type="text" class="form-control input-sm" name="KEY_ACAOINT" id="KEY_ACAOINT" maxlength="10" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label required">Descrição da Ação </label>
										<input type="text" class="form-control input-sm" name="DES_ACAOINT" id="DES_ACAOINT" maxlength="50" required>
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

						<input type="hidden" name="NUM_ORDENAC" id="NUM_ORDENAC" value="">

						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">

						<div class="push5"></div>

					</form>

					<div class="push50"></div>

					<div class="col-lg-12">

						<div id="divId_sub">
						</div>

						<div class="no-more-tables">

							<form name="formLista">

								<table class="table table-bordered table-striped table-hover table-sortable">
									<thead>
										<tr>
											<th class="bg-primary" width="40"></th>
											<th class="bg-primary" width="40"></th>
											<th class="bg-primary">Código</th>
											<th class="bg-primary">Nome da Ação</th>
											<th class="bg-primary">Chave</th>
											<th class="bg-primary">Descrição</th>
										</tr>
									</thead>
									<tbody>

										<?php

										$sql = "select * from INTEGRA_ACAOMTZ order by NUM_ORDENAC";
										$arrayQuery = mysqli_query($adm, $sql);

										$count = 0;
										while ($qrBuscaFases = mysqli_fetch_assoc($arrayQuery)) {
											$count++;
											echo "
															<tr>
															  <td align='center'><span class='glyphicon glyphicon-move grabbable' data-id='" . $qrBuscaFases['COD_ACAOINT'] . "'></span></td>
															  <td align='center'><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></th>
															  <td>" . $qrBuscaFases['COD_ACAOINT'] . "</td>
															  <td>" . $qrBuscaFases['NOM_ACAOINT'] . "</td>
															  <td>" . $qrBuscaFases['KEY_ACAOINT'] . "</td>
															  <td>" . $qrBuscaFases['DES_ACAOINT'] . "</td>
															</tr>
															<input type='hidden' id='ret_COD_ACAOINT_" . $count . "' value='" . $qrBuscaFases['COD_ACAOINT'] . "'>
															<input type='hidden' id='ret_NOM_ACAOINT_" . $count . "' value='" . $qrBuscaFases['NOM_ACAOINT'] . "'>
															<input type='hidden' id='ret_KEY_ACAOINT_" . $count . "' value='" . $qrBuscaFases['KEY_ACAOINT'] . "'>
															<input type='hidden' id='ret_DES_ACAOINT_" . $count . "' value='" . $qrBuscaFases['DES_ACAOINT'] . "'>
															<input type='hidden' id='ret_NUM_ORDENAC_" . $count . "' value='" . $qrBuscaFases['NUM_ORDENAC'] . "'>
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

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script>
	$(function() {

		$(".table-sortable tbody").sortable();

		$('.table-sortable tbody').sortable({
			handle: 'span'
		});

		$(".table-sortable tbody").sortable({

			stop: function(event, ui) {

				var Ids = "";
				$('table tr').each(function(index) {
					if (index != 0) {
						Ids = Ids + $(this).children().find('span.glyphicon').attr('data-id') + ",";
					}
				});

				//update ordenação
				//console.log(Ids.substring(0,(Ids.length-1)));

				var arrayOrdem = Ids.substring(0, (Ids.length - 1));
				//alert(arrayOrdem);
				execOrdenacao(arrayOrdem, 9);

				function execOrdenacao(p1, p2) {
					//alert(p1);
					$.ajax({
						type: "GET",
						url: "ajxOrdenacao.php",
						data: {
							ajx1: p1,
							ajx2: p2
						},
						beforeSend: function() {
							//$('#divId_sub').html('<div class="loading" style="width: 100%;"></div>');
						},
						success: function(data) {
							//$("#divId_sub").html(data); 
						},
						error: function() {
							$('#divId_sub').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Falha no processamento...</p>');
						}
					});
				}

			}

		});


		$(".table-sortable tbody").disableSelection();

	});
</script>

<script type="text/javascript">
	$(document).ready(function() {

		//chosen
		$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
		$('#formulario').validator();

	});

	function retornaForm(index) {
		$("#formulario #COD_ACAOINT").val($("#ret_COD_ACAOINT_" + index).val());
		$("#formulario #NOM_ACAOINT").val($("#ret_NOM_ACAOINT_" + index).val());
		$("#formulario #KEY_ACAOINT").val($("#ret_KEY_ACAOINT_" + index).val());
		$("#formulario #DES_ACAOINT").val($("#ret_DES_ACAOINT_" + index).val());
		$("#formulario #NUM_ORDENAC").val($("#ret_NUM_ORDENAC_" + index).val());
		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
	}
</script>