<?php

//echo fnDebug('true');

$hashLocal = mt_rand();

$conn = conntemp($cod_empresa, "");
$adm = $connAdm->connAdm();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_faseint = fnLimpaCampoZero($_REQUEST['COD_FASEINT']);
		$tip_fasevnd = fnLimpaCampo($_REQUEST['TIP_FASEVND']);
		$nom_faseint = fnLimpaCampo($_REQUEST['NOM_FASEINT']);
		$key_faseint = fnLimpaCampo($_REQUEST['KEY_FASEINT']);
		$des_faseint = fnLimpaCampo($_REQUEST['DES_FASEINT']);

		$nom_usuario = $_SESSION["SYS_NOM_USUARIO"];
		$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$MODULO = $_GET['mod'];
		$COD_MODULO = fndecode($_GET['mod']);

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		if ($opcao != '') {

			$sql = "CALL SP_ALTERA_INTEGRA_VENDAMTZ (
				 '" . $cod_faseint . "', 
				 '" . $tip_fasevnd . "', 
				 '" . $nom_faseint . "', 
				 '" . $key_faseint . "', 
				 '" . $des_faseint . "', 
				 '" . $opcao . "'    
				) ";

			//echo $sql;
			//fntestesql($connAdm->connAdm(),trim($sql));

			$arrayProc = mysqli_query($adm, trim($sql));

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

				<?php $abaModulo = 1151;
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
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_FASEINT" id="COD_FASEINT" value="">
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Momento no Fluxo</label>
										<select data-placeholder="Selecione o tipo de usuário" name="TIP_FASEVND" id="TIP_FASEVND" class="chosen-select-deselect requiredChk" required>
											<option value="">&nbsp;</option>
											<option value="ATD">Atendimento</option>
											<option value="VND">Venda</option>
										</select>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Nome da Fase</label>
										<input type="text" class="form-control input-sm" name="NOM_FASEINT" id="NOM_FASEINT" maxlength="20" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Chave da Fase </label>
										<input type="text" class="form-control input-sm" name="KEY_FASEINT" id="KEY_FASEINT" maxlength="10" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label required">Descrição da Fase </label>
										<input type="text" class="form-control input-sm" name="DES_FASEINT" id="DES_FASEINT" maxlength="50" required>
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
											<th class="bg-primary">Momento</th>
											<th class="bg-primary">Nome da Fase</th>
											<th class="bg-primary">Chave</th>
											<th class="bg-primary">Descrição</th>
										</tr>
									</thead>
									<tbody>

										<?php

										$sql = "select * from INTEGRA_VENDAMTZ order by NUM_ORDENAC";
										$arrayQuery = mysqli_query($adm, $sql);

										$count = 0;
										while ($qrBuscaFases = mysqli_fetch_assoc($arrayQuery)) {
											if ($qrBuscaFases['TIP_FASEVND'] == "ATD") {
												$tipoFluxo = "Atendimento";
											} else {
												$tipoFluxo = "Venda";
											}
											$count++;
											echo "
															<tr>
															  <td align='center'><span class='glyphicon glyphicon-move grabbable' data-id='" . $qrBuscaFases['COD_FASEINT'] . "'></span></td>
															  <td align='center'><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></th>
															  <td>" . $qrBuscaFases['COD_FASEINT'] . "</td>
															  <td>" . $tipoFluxo . "</td>
															  <td>" . $qrBuscaFases['NOM_FASEINT'] . "</td>
															  <td>" . $qrBuscaFases['KEY_FASEINT'] . "</td>
															  <td>" . $qrBuscaFases['DES_FASEINT'] . "</td>
															</tr>
															<input type='hidden' id='ret_COD_FASEINT_" . $count . "' value='" . $qrBuscaFases['COD_FASEINT'] . "'>
															<input type='hidden' id='ret_TIP_FASEVND_" . $count . "' value='" . $qrBuscaFases['TIP_FASEVND'] . "'>
															<input type='hidden' id='ret_NOM_FASEINT_" . $count . "' value='" . $qrBuscaFases['NOM_FASEINT'] . "'>
															<input type='hidden' id='ret_KEY_FASEINT_" . $count . "' value='" . $qrBuscaFases['KEY_FASEINT'] . "'>
															<input type='hidden' id='ret_DES_FASEINT_" . $count . "' value='" . $qrBuscaFases['DES_FASEINT'] . "'>
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
				execOrdenacao(arrayOrdem, 8);

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
		$("#formulario #COD_FASEINT").val($("#ret_COD_FASEINT_" + index).val());
		$("#formulario #TIP_FASEVND").val($("#ret_TIP_FASEVND_" + index).val()).trigger("chosen:updated");
		$("#formulario #NOM_FASEINT").val($("#ret_NOM_FASEINT_" + index).val());
		$("#formulario #KEY_FASEINT").val($("#ret_KEY_FASEINT_" + index).val());
		$("#formulario #DES_FASEINT").val($("#ret_DES_FASEINT_" + index).val());
		$("#formulario #NUM_ORDENAC").val($("#ret_NUM_ORDENAC_" + index).val());
		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
	}
</script>