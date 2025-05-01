<?php

if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}

$hashLocal = mt_rand();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_subcategor = fnLimpaCampoZero($_REQUEST['COD_SUBCATEGOR']);
		$cod_categor = fnLimpaCampoZero($_REQUEST['COD_CATEGOR']);
		$des_subcategor = fnLimpaCampo($_REQUEST['DES_SUBCATEGOR']);
		$abv_subcategor = fnLimpaCampo($_REQUEST['ABV_SUBCATEGOR']);
		$des_icone = fnLimpaCampo($_REQUEST['DES_ICONE']);
		$des_cor = fnLimpaCampoHtml($_REQUEST['DES_COR']);
		$num_ordenac = $_POST['NUM_ORDENAC'];

		//fnEscreve($nom_submenus);

		$cod_usucada = $_SESSION['SYS_COD_USUARIO'];

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

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

			// mysqli_query($connAdm->connAdm(),trim($sql)) or die(mysqli_error());				

			//mensagem de retorno
			switch ($opcao) {
				case 'CAD':
					$sql = "INSERT INTO SUBCATEGORIA_TUTORIAL(
											COD_CATEGOR,																		
											DES_SUBCATEGOR,
											ABV_SUBCATEGOR,
											DES_ICONE, 
											DES_COR,						
											COD_USUCADA
											)VALUES(
											$cod_categor,																		
											'$des_subcategor',
											'$abv_subcategor',
											'$des_icone', 
											'$des_cor',
											$cod_usucada
											)";

					//echo $sql;
					// fnEscreve($sql);								

					mysqli_query($connAdm->connAdm(), trim($sql));

					$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					break;

				case 'ALT':
					$sql = "UPDATE SUBCATEGORIA_TUTORIAL SET
										COD_CATEGOR = $cod_categor,
										DES_SUBCATEGOR = '$des_subcategor', 
										ABV_SUBCATEGOR = '$abv_subcategor',
										DES_ICONE = '$des_icone',  
										DES_COR = '$des_cor'																		 				
								WHERE COD_SUBCATEGOR = $cod_subcategor;";
					// fnEscreve($sql);

					mysqli_query($connAdm->connAdm(), trim($sql));


					$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";
					break;

				case 'EXC':
					$sql = "DELETE FROM SUBCATEGORIA_TUTORIAL WHERE COD_SUBCATEGOR = $cod_subcategor;";
					// fnEscreve($sql);
					mysqli_query($connAdm->connAdm(), trim($sql));
					$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";
					break;
					break;
			}
			$msgTipo = 'alert-success';
		}
	}
}

// fnMostraForm();

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
				<div class="push30"></div>
				<?php $abaTutorial = 1482;
				include "abasTutorial.php"; ?>

				<div class="push30"></div>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Subcategoria</legend>

							<div class="row">
								<div class="col-md-1">
									<div class="form-group">
										<label for="inputName" class="control-label">Código</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_SUBCATEGOR" id="COD_SUBCATEGOR" value="">
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Categoria</label>
										<select class="chosen-select-deselect requiredChk" data-placeholder="Selecione a categoria" name="COD_CATEGOR" id="COD_CATEGOR" required>
											<option value=""></option>
											<?php

											$sql = "SELECT COD_CATEGOR, DES_CATEGOR FROM CATEGORIA_TUTORIAL order by DES_CATEGOR ";
											$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

											while ($qrListaComunicacao = mysqli_fetch_assoc($arrayQuery)) {
											?>
												<option value="<?php echo $qrListaComunicacao['COD_CATEGOR']; ?>"><?php echo $qrListaComunicacao['DES_CATEGOR']; ?></option>

											<?php } ?>
										</select>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Descrição</label>
										<input type="text" class="form-control input-sm" name="DES_SUBCATEGOR" id="DES_SUBCATEGOR" maxlength="75" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>


								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Abreviação</label>
										<input type="text" class="form-control input-sm" name="ABV_SUBCATEGOR" id="ABV_SUBCATEGOR" maxlength="3" required>
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

							<button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
							<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
							<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>
							<button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fal fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>

						</div>

						<input type="hidden" name="NUM_ORDENAC" id="NUM_ORDENAC" value="">
						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">

					</form>

					<div class="push5"></div>

					<div class="push50"></div>

					<div class="col-lg-12">

						<div class="no-more-tables">

							<form name="formLista">

								<table class="table table-bordered table-striped table-hover table-sortable">
									<thead>
										<tr>
											<th width="40"></th>
											<th width="40"></th>
											<th>Código</th>
											<th>Categoria</th>
											<th>Descrição</th>
											<th>Abreviação</th>
											<th>Ícone</th>
										</tr>
									</thead>
									<tbody>

										<?php

										$sql = "SELECT ST.*, CT.DES_CATEGOR 
															FROM SUBCATEGORIA_TUTORIAL ST 
															LEFT JOIN CATEGORIA_TUTORIAL CT ON CT.COD_CATEGOR = ST.COD_CATEGOR 
															ORDER BY NUM_ORDENAC";

										$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

										$count = 0;
										while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery)) {


											$count++;
											echo "
															<tr>
															  <td align='center'><span class='fal fa-equals grabbable' data-id='" . $qrBuscaModulos['COD_SUBCATEGOR'] . "'></span></td>
															  <td align='center'><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></th>

															  <td>" . $qrBuscaModulos['COD_SUBCATEGOR'] . "</td>
															  <td>" . $qrBuscaModulos['DES_CATEGOR'] . "</td>
															  <td>" . $qrBuscaModulos['DES_SUBCATEGOR'] . "</td>
															  <td>" . $qrBuscaModulos['ABV_SUBCATEGOR'] . "</td>															  		  
															  <td align='center'><span style='color:" . $qrBuscaModulos['DES_COR'] . "' class='" . $qrBuscaModulos['DES_ICONE'] . "' ></td>
															</tr>
															<input type='hidden' id='ret_COD_SUBCATEGOR_" . $count . "' value='" . $qrBuscaModulos['COD_SUBCATEGOR'] . "'>
															<input type='hidden' id='ret_COD_CATEGOR_" . $count . "' value='" . $qrBuscaModulos['COD_CATEGOR'] . "'>
															<input type='hidden' id='ret_DES_SUBCATEGOR_" . $count . "' value='" . $qrBuscaModulos['DES_SUBCATEGOR'] . "'>
															<input type='hidden' id='ret_ABV_SUBCATEGOR_" . $count . "' value='" . $qrBuscaModulos['ABV_SUBCATEGOR'] . "'>
															<input type='hidden' id='ret_DES_ICONE_" . $count . "' value='" . $qrBuscaModulos['DES_ICONE'] . "'>
															<input type='hidden' id='ret_DES_COR_" . $count . "' value='" . $qrBuscaModulos['DES_COR'] . "'>
															<input type='hidden' id='ret_NUM_ORDENAC_" . $count . "' value='" . @$qrBuscaModulos['NUM_ORDENAC'] . "'>
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
				execOrdenacao(arrayOrdem, 16);

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
							console.log(data);
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

		//icon picker
		$('.btnSearchIcon').iconpicker({
			cols: 8,
			iconset: 'fontawesome',
			rows: 6,
			searchText: 'Procurar  &iacute;cone'
		});

		//capturando o ícone selecionado no botão
		$('#btniconpicker').on('change', function(e) {
			$('#DES_ICONE').val(e.icon);
			//alert($('#DES_ICONE').val());
		});

	});


	function retornaForm(index) {
		$("#formulario #COD_SUBCATEGOR").val($("#ret_COD_SUBCATEGOR_" + index).val());
		$("#formulario #COD_CATEGOR").val($("#ret_COD_CATEGOR_" + index).val()).trigger("chosen:updated");
		$("#formulario #DES_SUBCATEGOR").val($("#ret_DES_SUBCATEGOR_" + index).val());
		$("#formulario #ABV_SUBCATEGOR").val($("#ret_ABV_SUBCATEGOR_" + index).val());
		$("#formulario #DES_ICONE").val($("#ret_DES_ICONE_" + index).val());
		$("#formulario #DES_COR").minicolors('value', $("#ret_DES_COR_" + index).val());
		$("#formulario #NUM_ORDENAC").val($("#ret_NUM_ORDENAC_" + index).val());
		$("#btniconpicker").iconpicker('setIcon', $("#ret_DES_ICONE_" + index).val());
		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
	}
</script>