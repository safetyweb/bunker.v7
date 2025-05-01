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
$cod_canalcom = "";
$cod_tpcom = "";
$nom_segment = "";
$abv_canalcom = "";
$des_canalcom = "";
$des_icone = "";
$des_cor = "";
$log_personaliza = "";
$log_preco = "";
$num_ordenac = "";
$nom_submenus = "";
$cod_usucada = "";
$hHabilitado = "";
$hashForm = "";
$cod_submenus = "";
$abasComunicacao = "";
$arrayQuery = [];
$qrListaComunicacao = "";
$qrBuscaModulos = "";


$hashLocal = mt_rand();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_canalcom = fnLimpaCampoZero(@$_POST['COD_CANALCOM']);
		$cod_tpcom = fnLimpacampoZero(@$_POST['COD_TPCOM']);
		// $nom_segment = @$_POST['NOM_SEGMENT'];
		$abv_canalcom = fnLimpaCampo(@$_POST['ABV_CANALCOM']);
		$des_canalcom = fnLimpaCampo(@$_POST['DES_CANALCOM']);
		$des_icone = fnLimpaCampo(@$_REQUEST['DES_ICONE']);
		$des_cor = fnLimpaCampoHtml(@$_REQUEST['DES_COR']);
		if (empty(@$_REQUEST['LOG_PERSONALIZA'])) {
			$log_personaliza = 'N';
		} else {
			$log_personaliza = @$_REQUEST['LOG_PERSONALIZA'];
		}
		if (empty(@$_REQUEST['LOG_PRECO'])) {
			$log_preco = 'N';
		} else {
			$log_preco = @$_REQUEST['LOG_PRECO'];
		}
		$num_ordenac = @$_POST['NUM_ORDENAC'];

		//fnEscreve($nom_submenus);

		$cod_usucada = $_SESSION['SYS_COD_USUARIO'];

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		if ($opcao != '') {

			// $sql = "CALL SP_ALTERA_COMUNICACAO (
			//  '".$cod_canalcom."',
			//  '".$cod_tpcom."',
			//  '".$abv_canalcom."',				
			//  '".$des_canalcom."',				
			//  '".$des_icone."', 
			//  '".$des_cor."', 
			//  '".$log_personaliza."', 
			//  '".$log_preco."', 
			//  '".$opcao."'    
			// ) ";

			//echo $sql;
			//fnEscreve($cod_submenus);

			// mysqli_query($connAdm->connAdm(),trim($sql));				

			//mensagem de retorno
			switch ($opcao) {
				case 'CAD':
					$sql = "INSERT INTO CANAL_COMUNICACAO(
									COD_TPCOM, 
									ABV_CANALCOM, 
									DES_CANALCOM, 
									DES_ICONE, 
									DES_COR, 
									LOG_PERSONALIZA, 
									LOG_PRECO, 
									COD_USUCADA
									)VALUES(
									$cod_tpcom, 
									'$abv_canalcom', 
									'$des_canalcom', 
									'$des_icone', 
									'$des_cor', 
									'$log_personaliza', 
									'$log_preco', 
									$cod_usucada
									);";

					//echo $sql;
					// fnEscreve($sql);								

					mysqli_query($connAdm->connAdm(), trim($sql));

					$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					break;

				case 'ALT':
					$sql = "UPDATE CANAL_COMUNICACAO SET
								COD_TPCOM = $cod_tpcom, 
								ABV_CANALCOM = '$abv_canalcom', 
								DES_CANALCOM = '$des_canalcom', 
								DES_ICONE = '$des_icone', 
								DES_COR = '$des_cor', 
								LOG_PERSONALIZA = '$log_personaliza', 
								LOG_PRECO = '$log_preco'				 				
								WHERE COD_CANALCOM = $cod_canalcom;";

					mysqli_query($connAdm->connAdm(), trim($sql));


					$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";
					break;

				case 'EXC':
					$sql = "DELETE FROM CANAL_COMUNICACAO WHERE COD_CANALCOM = $cod_canalcom;";
					mysqli_query($connAdm->connAdm(), trim($sql));
					$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";
					break;
					break;
			}
			$msgTipo = 'alert-success';
		}
	}
}

//fnMostraForm();

?>
<style>
	.table-icons button {
		background: #fff;
		color: #3c3c3c;
	}

	.table-icons button:hover {
		background: #2c3e50;
	}
</style>

<div class="push30"></div>

<div class="row">

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="glyphicon glyphicon-calendar"></i>
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

				<?php
				//menu Tipos
				$abasComunicacao = 1442;
				include "abasComunicacao.php";
				?>

				<div class="push30"></div>


				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Dados Gerais</legend>

							<div class="row">
								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Código Canal</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_CANALCOM" id="COD_CANALCOM" value="">
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Personalizar</label>
										<div class="push5"></div>
										<label class="switch">
											<input type="checkbox" name="LOG_PERSONALIZA" id="LOG_PERSONALIZA" class="switch" value="S" checked>
											<span></span>
										</label>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Mostrar Preço</label>
										<div class="push5"></div>
										<label class="switch">
											<input type="checkbox" name="LOG_PRECO" id="LOG_PRECO" class="switch" value="S" checked>
											<span></span>
										</label>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Tipo da Comunicação</label>
										<select data-placeholder="Selecione o tipo" name="COD_TPCOM" id="COD_TPCOM" class="chosen-select-deselect requiredChk" required>
											<option value="">&nbsp;</option>
											<?php
											$sql = "SELECT COD_TPCOM, DES_TPCOM FROM tipo_Comunicacao order by DES_TPCOM ";
											$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

											while ($qrListaComunicacao = mysqli_fetch_assoc($arrayQuery)) {
												echo "
																				  <option value='" . $qrListaComunicacao['COD_TPCOM'] . "'>" . $qrListaComunicacao['DES_TPCOM'] . "</option> 
																				";
											}
											?>
										</select>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<!-- <div class="col-md-4">
														<div class="form-group">
															<label for="inputName" class="control-label required">Nome do Segmento</label>
															<input type="text" class="form-control input-sm" name="NOM_SEGMENT" id="NOM_SEGMENT" maxlength="50" required>
															<div class="help-block with-errors"></div>
														</div>
													</div>	 -->

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Abreviação</label>
										<input type="text" class="form-control input-sm" name="ABV_CANALCOM" id="ABV_CANALCOM" maxlength="20">
										<div class="help-block with-errors"></div>
									</div>
								</div>
							</div>

							<div class="row">

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Descrição</label>
										<input type="text" class="form-control input-sm" name="DES_CANALCOM" id="DES_CANALCOM" maxlength="20">
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Cor</label>
										<input type="text" class="form-control input-sm pickColor" style="margin-top: 4px;" name="DES_COR" id="DES_COR" value="<?php echo $des_cor ?>">
									</div>
								</div>

								<div class="col-md-1">
									<div class="form-group">
										<label for="inputName" class="control-label">Ícone</label><br />
										<button class="btn btn-primary" id="btniconpicker" data-iconset="fontawesome"
											data-icon="vazio" role="iconpicker" data-arrow-prev-icon-class="fas fa-arrow-left" data-arrow-next-icon-class="fas fa-arrow-right"
											data-rows="6" data-cols="6" data-search-text="Buscar ícone..." data-label-footer="{0} - {1} de {2} ícones" data-label-header="{0} de {1}">
										</button>
										<input type="hidden" name="DES_ICONE" id="DES_ICONE" value="<?php echo $des_icone ?>">
									</div>
								</div>
							</div>
						</fieldset>

						<div class="push10"></div>
						<hr>
						<div class="form-group text-right col-lg-12">

							<button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>
							<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
							<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Alterar</button>
							<button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fa fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>

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
											<th width="40"></th>
											<th width="40"></th>
											<th>Código</th>
											<th>Tipo da Comunicação</th>
											<th>Abreviação</th>
											<th>Descrição</th>
											<th>Ícone</th>
										</tr>
									</thead>
									<tbody>

										<?php

										$sql = "select * from CANAL_COMUNICACAO CC LEFT JOIN TIPO_COMUNICACAO TC ON TC.COD_TPCOM = CC.COD_TPCOM ORDER BY NUM_ORDENAC";
										$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

										$count = 0;
										while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery)) {
											$count++;
											echo "
															<tr>
															  <td align='center'><span class='fal fa-equals grabbable' data-id='" . $qrBuscaModulos['COD_CANALCOM'] . "'></span></td>
															  <td align='center'><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></th>
															  <td>" . $qrBuscaModulos['COD_CANALCOM'] . "</td>
															  <td>" . $qrBuscaModulos['DES_TPCOM'] . "</td>
															  <td>" . $qrBuscaModulos['ABV_CANALCOM'] . "</td>
															  <td>" . $qrBuscaModulos['DES_CANALCOM'] . "</td>
															  <td align='center'><span style='color:" . $qrBuscaModulos['DES_COR'] . "' class='" . $qrBuscaModulos['DES_ICONE'] . "' ></td>															  
															</tr>
															<input type='hidden' id='ret_COD_CANALCOM_" . $count . "' value='" . $qrBuscaModulos['COD_CANALCOM'] . "'>
															<input type='hidden' id='ret_LOG_PERSONALIZA_" . $count . "' value='" . $qrBuscaModulos['LOG_PERSONALIZA'] . "'>
															<input type='hidden' id='ret_LOG_PRECO_" . $count . "' value='" . $qrBuscaModulos['LOG_PRECO'] . "'>															
															<input type='hidden' id='ret_COD_TPCOM_" . $count . "' value='" . $qrBuscaModulos['COD_TPCOM'] . "'>															
															<input type='hidden' id='ret_ABV_CANALCOM_" . $count . "' value='" . $qrBuscaModulos['ABV_CANALCOM'] . "'>
															<input type='hidden' id='ret_DES_CANALCOM_" . $count . "' value='" . $qrBuscaModulos['DES_CANALCOM'] . "'>															
															<input type='hidden' id='ret_DES_ICONE_" . $count . "' value='" . $qrBuscaModulos['DES_ICONE'] . "'>
															<input type='hidden' id='ret_DES_COR_" . $count . "' value='" . $qrBuscaModulos['DES_COR'] . "'>
															<input type='hidden' id='ret_NUM_ORDENAC_" . $count . "' value='" . $qrBuscaModulos['NUM_ORDENAC'] . "'>															
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


<link rel="stylesheet" href="../css/bootstrap-iconpicker.min.css" />

<script type="text/javascript" src="../js/bootstrap-iconpicker-iconset-fa5.js"></script>
<script type="text/javascript" src="../js/bootstrap-iconpicker.js"></script>
<script src="js/plugins/minicolors/jquery.minicolors.min.js"></script>
<link rel="stylesheet" href="js/plugins/minicolors/jquery.minicolors.css">

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
						Ids = Ids + $(this).children().find('span.fa-equals').attr('data-id') + ",";
					}
				});

				//update ordenação
				//console.log(Ids.substring(0,(Ids.length-1)));

				var arrayOrdem = Ids.substring(0, (Ids.length - 1));
				//alert(arrayOrdem);
				execOrdenacao(arrayOrdem, 13);

				function execOrdenacao(p1, p2) {
					//alert(p2);
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

		//arrastar 
		$('.grabbable').on('change', function(e) {
			//console.log(e.icon);
			$("#DES_ICONE").val(e.icon);
		});

		$(".grabbable").click(function() {
			$(this).parent().addClass('selected').siblings().removeClass('selected');

		});

		//color picker
		$('.pickColor').minicolors({
			control: $(this).attr('data-control') || 'hue',
			theme: 'bootstrap'
		});

		// //icon picker
		// $('.btnSearchIcon').iconpicker({ 
		// 	cols: 8,
		// 	iconset: 'fontawesome',   
		// 	rows: 6,
		// 	searchText: 'Procurar  &iacute;cone'
		// });	

		//capturando o ícone selecionado no botão
		$('#btniconpicker').on('change', function(e) {
			$('#DES_ICONE').val(e.icon);
			//alert($('#DES_ICONE').val());
		});

	});


	function retornaForm(index) {
		$("#formulario #COD_CANALCOM").val($("#ret_COD_CANALCOM_" + index).val());
		// $("#formulario #COD_TPCOM").val($("#ret_COD_TPCOM_"+index).val());
		$("#formulario #COD_TPCOM").val($("#ret_COD_TPCOM_" + index).val()).trigger("chosen:updated");
		// $("#formulario #NOM_SEGMENT").val($("#ret_NOM_SEGMENT_"+index).val());
		$("#formulario #ABV_CANALCOM").val($("#ret_ABV_CANALCOM_" + index).val());
		$('#btniconpicker').iconpicker('setIcon', $("#ret_DES_ICONE_" + index).val());
		$("#formulario #DES_CANALCOM").val($("#ret_DES_CANALCOM_" + index).val());
		$("#formulario #DES_ICONE").val($("#ret_DES_ICONE_" + index).val());
		$("#formulario #DES_COR").val($("#ret_DES_COR_" + index).val());
		$("#formulario #NUM_ORDENAC").val($("#ret_NUM_ORDENAC_" + index).val());
		if ($("#ret_LOG_PERSONALIZA_" + index).val() == 'S') {
			$('#formulario #LOG_PERSONALIZA').prop('checked', true);
		} else {
			$('#formulario #LOG_PERSONALIZA').prop('checked', false);
		}
		if ($("#ret_LOG_PRECO_" + index).val() == 'S') {
			$('#formulario #LOG_PRECO').prop('checked', true);
		} else {
			$('#formulario #LOG_PRECO').prop('checked', false);
		}
		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
	}
</script>