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
$cod_campoobg = "";
$tip_blocoobg = "";
$nom_campoobg = "";
$key_campoobg = "";
$des_campoobg = "";
$tip_campoobg = "";
$col_md = "";
$col_xs = "";
$classe_input = "";
$classe_div = "";
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
$qrBuscaCampos = "";
$tipoBloco = "";
$tipoCampo = "";


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

		$cod_campoobg = fnLimpaCampoZero(@$_REQUEST['COD_CAMPOOBG']);
		$tip_blocoobg = fnLimpaCampo(@$_REQUEST['TIP_BLOCOOBG']);
		$nom_campoobg = fnLimpaCampo(@$_REQUEST['NOM_CAMPOOBG']);
		$key_campoobg = fnLimpaCampo(@$_REQUEST['KEY_CAMPOOBG']);
		$des_campoobg = fnLimpaCampo(@$_REQUEST['DES_CAMPOOBG']);
		$tip_campoobg = fnLimpaCampo(@$_REQUEST['TIP_CAMPOOBG']);
		$col_md = fnLimpaCampoZero(@$_REQUEST['COL_MD']);
		$col_xs = fnLimpaCampoZero(@$_REQUEST['COL_XS']);
		$classe_input = fnLimpaCampo(@$_REQUEST['CLASSE_INPUT']);
		$classe_div = fnLimpaCampo(@$_REQUEST['CLASSE_DIV']);

		$nom_usuario = $_SESSION["SYS_NOM_USUARIO"];
		$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$MODULO = @$_GET['mod'];
		$COD_MODULO = fndecode(@$_GET['mod']);

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		if ($opcao != '') {

			$sql = "CALL SP_ALTERA_INTEGRA_CAMPOOBG (
				 '" . $cod_campoobg . "', 
				 '" . $tip_blocoobg . "', 
				 '" . $nom_campoobg . "', 
				 '" . $key_campoobg . "', 
				 '" . $des_campoobg . "', 
				 '" . $tip_campoobg . "', 
				 '" . $col_md . "', 
				 '" . $col_xs . "', 
				 '" . $classe_input . "', 
				 '" . $classe_div . "', 
				 '" . $opcao . "'    
				) ";

			//echo $sql;
			//fntestesql($connAdm->connAdm(),trim($sql));

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
					<span class="text-primary"><?php echo $NomePg; ?> </span>
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

				<?php $abaModulo = 1155;
				include "abasCamposObrigatorios.php"; ?>

				<div class="push30"></div>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Dados Gerais</legend>

							<div class="row">

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Código</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_CAMPOOBG" id="COD_CAMPOOBG" value="">
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Bloco da Informação</label>
										<select data-placeholder="Selecione o tipo de usuário" name="TIP_BLOCOOBG" id="TIP_BLOCOOBG" class="chosen-select-deselect requiredChk" required>
											<option value="">&nbsp;</option>
											<option value="OBG">Obrigatório</option>
											<option value="GRL">Gerais</option>
											<option value="COM">Comunicação</option>
											<option value="LOC">Localização</option>
										</select>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Nome do Campo</label>
										<input type="text" class="form-control input-sm" name="NOM_CAMPOOBG" id="NOM_CAMPOOBG" maxlength="20" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Tipo do Campo</label>
										<select data-placeholder="Selecione o tipo de usuário" name="TIP_CAMPOOBG" id="TIP_CAMPOOBG" class="chosen-select-deselect requiredChk" required>
											<option value="">&nbsp;</option>
											<option value="String">String</option>
											<option value="Data">Data</option>
											<option value="email">e-Mail</option>
											<option value="numeric">Numérico</option>
										</select>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Chave do Campo </label>
										<input type="text" class="form-control input-sm" name="KEY_CAMPOOBG" id="KEY_CAMPOOBG" maxlength="20" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Chave BD</label>
										<input type="text" class="form-control input-sm" name="DES_CAMPOOBG" id="DES_CAMPOOBG" maxlength="50" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>

							</div>

							<div class="push10"></div>

							<div class="row">

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Tamanho da Coluna (DESKTOP)</label>
										<input type="text" class="form-control input-sm int" name="COL_MD" id="COL_MD">
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Tamanho da Coluna (Tablet/Celular)</label>
										<input type="text" class="form-control input-sm int" name="COL_XS" id="COL_XS">
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Classe do Input</label>
										<input type="text" class="form-control input-sm" name="CLASSE_INPUT" id="CLASSE_INPUT">
										<div class="help-block with-errors">Máscara, alinhamento de texto (separadas por "espaço")</div>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Classe da DIV</label>
										<input type="text" class="form-control input-sm" name="CLASSE_DIV" id="CLASSE_DIV">
										<div class="help-block with-errors">Classes personalizadas (separados por "espaço")</div>
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

								<table class="table table-bordered table-striped table-hover tableSorter">
									<thead>
										<tr>
											<th class="{sorter:false}" width="40"></th>
											<th class="{sorter:false}" width="40"></th>
											<th>Código</th>
											<th>Bloco</th>
											<th>Nome do Campo</th>
											<th>Chave</th>
											<th>Chave BD</th>
											<th>Tipo</th>
										</tr>
									</thead>
									<tbody>

										<?php

										$sql = "select * from INTEGRA_CAMPOOBG order by NUM_ORDENAC";
										$arrayQuery = mysqli_query($adm, $sql);

										$count = 0;
										while ($qrBuscaCampos = mysqli_fetch_assoc($arrayQuery)) {
											$tipoBloco = "";
											$tipoCampo = "";
											switch ($qrBuscaCampos['TIP_BLOCOOBG']) {
												case "OBG":
													$tipoBloco = "Obrigatório";
													break;
												case "GRL":
													$tipoBloco = "Gerais";
													break;
												case "COM":
													$tipoBloco = "Comunicação";
													break;
												case "LOC":
													$tipoBloco = "Localização";
													break;
											}

											switch ($qrBuscaCampos['TIP_CAMPOOBG']) {
												case "String":
													$tipoCampo = "String";
													break;
												case "Data":
													$tipoCampo = "Data";
													break;
												case "email":
													$tipoCampo = "e-Mail";
													break;
												case "numeric":
													$tipoCampo = "Numérico";
													break;
											}

											$count++;

											echo "
													<tr>
														<td align='center'><span class='glyphicon glyphicon-move grabbable' data-id='" . $qrBuscaCampos['COD_CAMPOOBG'] . "'></span></td>
														<td align='center'><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></th>
														<td>" . $qrBuscaCampos['COD_CAMPOOBG'] . "</td>
														<td>" . $tipoBloco . "</td>
														<td>" . $qrBuscaCampos['NOM_CAMPOOBG'] . "</td>
														<td>" . $qrBuscaCampos['KEY_CAMPOOBG'] . "</td>
														<td>" . $qrBuscaCampos['DES_CAMPOOBG'] . "</td>
														<td>" . $tipoCampo . "</td>
													</tr>
													<input type='hidden' id='ret_COD_CAMPOOBG_" . $count . "' value='" . $qrBuscaCampos['COD_CAMPOOBG'] . "'>
													<input type='hidden' id='ret_TIP_BLOCOOBG_" . $count . "' value='" . $qrBuscaCampos['TIP_BLOCOOBG'] . "'>
													<input type='hidden' id='ret_NOM_CAMPOOBG_" . $count . "' value='" . $qrBuscaCampos['NOM_CAMPOOBG'] . "'>
													<input type='hidden' id='ret_KEY_CAMPOOBG_" . $count . "' value='" . $qrBuscaCampos['KEY_CAMPOOBG'] . "'>
													<input type='hidden' id='ret_DES_CAMPOOBG_" . $count . "' value='" . $qrBuscaCampos['DES_CAMPOOBG'] . "'>
													<input type='hidden' id='ret_TIP_CAMPOOBG_" . $count . "' value='" . $qrBuscaCampos['TIP_CAMPOOBG'] . "'>
													<input type='hidden' id='ret_COL_MD_" . $count . "' value='" . $qrBuscaCampos['COL_MD'] . "'>
													<input type='hidden' id='ret_COL_XS_" . $count . "' value='" . $qrBuscaCampos['COL_XS'] . "'>
													<input type='hidden' id='ret_CLASSE_INPUT_" . $count . "' value='" . $qrBuscaCampos['CLASSE_INPUT'] . "'>
													<input type='hidden' id='ret_CLASSE_DIV_" . $count . "' value='" . $qrBuscaCampos['CLASSE_DIV'] . "'>
													<input type='hidden' id='ret_NUM_ORDENAC_" . $count . "' value='" . $qrBuscaCampos['NUM_ORDENAC'] . "'>
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
				execOrdenacao(arrayOrdem, 10);

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
		$("#formulario #COD_CAMPOOBG").val($("#ret_COD_CAMPOOBG_" + index).val());
		$("#formulario #TIP_BLOCOOBG").val($("#ret_TIP_BLOCOOBG_" + index).val()).trigger("chosen:updated");
		$("#formulario #TIP_CAMPOOBG").val($("#ret_TIP_CAMPOOBG_" + index).val()).trigger("chosen:updated");
		$("#formulario #NOM_CAMPOOBG").val($("#ret_NOM_CAMPOOBG_" + index).val());
		$("#formulario #KEY_CAMPOOBG").val($("#ret_KEY_CAMPOOBG_" + index).val());
		$("#formulario #DES_CAMPOOBG").val($("#ret_DES_CAMPOOBG_" + index).val());
		$("#formulario #NUM_ORDENAC").val($("#ret_NUM_ORDENAC_" + index).val());
		$("#formulario #COL_MD").val($("#ret_COL_MD_" + index).val());
		$("#formulario #COL_XS").val($("#ret_COL_XS_" + index).val());
		$("#formulario #CLASSE_INPUT").val($("#ret_CLASSE_INPUT_" + index).val());
		$("#formulario #CLASSE_DIV").val($("#ret_CLASSE_DIV_" + index).val());
		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
	}
</script>