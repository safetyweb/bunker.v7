<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$hashLocal = "";
$sist = "";
$msgRetorno = "";
$msgTipo = "";
$cod_areablock = "";
$cod_sistema = "";
$cod_modulos = "";
$nom_areablock = "";
$num_ordenac = "";
$nom_usuario = "";
$actual_link = "";
$MODULO = "";
$COD_MODULO = "";
$hHabilitado = "";
$hashForm = "";
$des_icones = "";
$arrayProc = [];
$cod_erro = "";
$formBack = "";
$abaModulo = "";
$arrayQuery = [];
$qrSistema = "";
$qrBuscaProdutos = "";
$qrBuscaModulos = "";
$mostraMulti = "";


$hashLocal = mt_rand();
$sist = (@$_GET["sist"] <> "" ? fnDecode(@$_GET["sist"]) : "");

$conn = conntemp($cod_empresa, "");
$adm = $connAdm->connAdm();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_areablock = fnLimpaCampoZero(@$_REQUEST['COD_AREABLOCK']);
		$cod_sistema = $sist; //fnLimpaCampoZero(@$_REQUEST['COD_SISTEMA']);
		$cod_modulos = fnLimpaCampoZero(@$_REQUEST['COD_MODULOS']);
		$nom_areablock = fnLimpaCampo(@$_REQUEST['NOM_AREABLOCK']);
		$num_ordenac = fnLimpaCampoZero(@$_REQUEST['NUM_ORDENAC']);

		$nom_usuario = $_SESSION["SYS_NOM_USUARIO"];
		$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$MODULO = @$_GET['mod'];
		$COD_MODULO = fndecode(@$_GET['mod']);

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		//fnEscreve($des_icones);	

		if ($opcao != '') {

			$sql = "CALL SP_ALTERA_MODULOSMARKA_AREA (
				 '" . $cod_areablock . "', 
				 '" . $cod_sistema . "', 
				 '" . $nom_areablock . "', 
				 '" . $cod_modulos . "',    
				 '0" . $num_ordenac . "',
				 '" . $opcao . "'    
				) ";

			//echo $sql;

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

				<?php $abaModulo = 1119;
				include "abasModulosMarka.php"; ?>

				<div class="push30"></div>

				<div class="login-form">

					<?php if (@$sist <> "") { ?>

						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

							<fieldset>
								<legend>Dados Gerais</legend>

								<div class="row">
									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Código</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_AREABLOCK" id="COD_AREABLOCK" value="">
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Sistema</label>
											<?php
											$sql = "SELECT DES_SISTEMA FROM SISTEMAS WHERE COD_SISTEMA=0" . $sist;
											$arrayQuery = mysqli_query($adm, $sql);
											$qrSistema = mysqli_fetch_assoc($arrayQuery);
											?>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="DES_SISTEMA" id="DES_SISTEMA" value="<?= $qrSistema["DES_SISTEMA"] ?>">
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-4">
										<div class="form-group">
											<label for="inputName" class="control-label required">Nome da Área</label>
											<input type="text" class="form-control input-sm" name="NOM_AREABLOCK" id="NOM_AREABLOCK" maxlength="50" required>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Código do Módulo</label>
											<input type="text" class="form-control input-sm int" name="COD_MODULOS" id="COD_MODULOS" value="">
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
												<th class="bg-primary">Nome da Área</th>
												<th class="bg-primary">Módulo</th>
											</tr>
										</thead>
										<tbody>

											<?php

											$sql = "select *
															from modulosmarka_area 
															WHERE COD_SISTEMA=0" . @$sist . "
															order by NUM_ORDENAC ";
											$arrayQuery = mysqli_query($adm, $sql);

											$count = 0;
											while ($qrBuscaProdutos = mysqli_fetch_assoc($arrayQuery)) {
												$count++;
												echo "
															<tr>
															  <td align='center'><span class='glyphicon glyphicon-move grabbable' data-id='" . $qrBuscaProdutos['COD_AREABLOCK'] . "'></span></td>
															  <td align='center'><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></th>
															  <td>" . $qrBuscaProdutos['COD_AREABLOCK'] . "</td>
															  <td>" . $qrBuscaProdutos['NOM_AREABLOCK'] . "</td>
															  <td>" . $qrBuscaProdutos['COD_MODULOS'] . "</td>
															</tr>
															<input type='hidden' id='ret_COD_AREABLOCK_" . $count . "' value='" . $qrBuscaProdutos['COD_AREABLOCK'] . "'>
															<input type='hidden' id='ret_COD_SISTEMA_" . $count . "' value='" . $qrBuscaProdutos['COD_SISTEMA'] . "'>
															<input type='hidden' id='ret_NOM_AREABLOCK_" . $count . "' value='" . $qrBuscaProdutos['NOM_AREABLOCK'] . "'>
															<input type='hidden' id='ret_COD_MODULOS_" . $count . "' value='" . $qrBuscaProdutos['COD_MODULOS'] . "'>
															<input type='hidden' id='ret_NUM_ORDENAC_" . $count . "' value='" . $qrBuscaProdutos['NUM_ORDENAC'] . "'>
															";
											}

											?>

										</tbody>
									</table>

								</form>

							</div>

						</div>

					<?php } else { ?>

						<div class="push50"></div>

						<div class="col-lg-12">

							<div class="no-more-tables">

								<form name="formLista">

									<table class="table table-bordered table-striped table-hover">
										<thead>
											<tr>
												<th width="40"></th>
												<th>Código</th>
												<th>Nome do Sistema</th>
												<th>Abreviação do Sistema</th>
											</tr>
										</thead>
										<tbody>

											<?php

											$sql = "SELECT SS.*, MD.NOM_MODULOS FROM SISTEMAS SS 
															LEFT JOIN MODULOS MD ON MD.COD_MODULOS = SS.COD_HOME
															ORDER BY SS.DES_SISTEMA";
											//fnEscreve($sql);
											$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

											$count = 0;
											while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery)) {
												$count++;

												if ($qrBuscaModulos['LOG_MULTEMPRESA'] == 'S') {
													$mostraMulti = '<i class="fa fa-check-square-o" aria-hidden="true"></i>';
												} else {
													$mostraMulti = '';
												}

												echo "
															<tr>
															  <td><input type='radio' name='radio1' onclick=\"abreSistema('" . fnEncode($qrBuscaModulos['COD_SISTEMA']) . "')\"></th>
															  <td>" . $qrBuscaModulos['COD_SISTEMA'] . "</td>
															  <td>" . $qrBuscaModulos['DES_SISTEMA'] . "</td>
															  <td>" . $qrBuscaModulos['DES_ABREVIA'] . "</td>
															</tr>
															";
											}

											?>

										</tbody>
									</table>

								</form>

							</div>

						</div>

					<?php } ?>

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
				execOrdenacao(arrayOrdem, 19);

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
		$("#formulario #COD_AREABLOCK").val($("#ret_COD_AREABLOCK_" + index).val());
		$("#formulario #COD_SISTEMA").val($("#ret_COD_SISTEMA_" + index).val()).trigger("chosen:updated");
		$("#formulario #NOM_AREABLOCK").val($("#ret_NOM_AREABLOCK_" + index).val());
		$("#formulario #COD_MODULOS").val($("#ret_COD_MODULOS_" + index).val());
		$("#formulario #NUM_ORDENAC").val($("#ret_NUM_ORDENAC_" + index).val());
		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
	}

	function abreSistema(sist) {
		window.location.href = "action.do?mod=<?= @$_GET["mod"] ?>&sist=" + sist;
	}
</script>