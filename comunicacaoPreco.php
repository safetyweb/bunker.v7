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
$cod_preco = "";
$cod_canalcom = "";
$cod_comfaixa = "";
$cod_sistema = "";
$qtd_diasvalid = 0;
$val_unitario = "";
$val_total = 0;
$des_icone = "";
$des_cor = "";
$log_bestval = "";
$log_preco = "";
$num_ordenac = "";
$nom_submenus = "";
$cod_usucada = "";
$hHabilitado = "";
$hashForm = "";
$cod_tpcom = "";
$abv_canalcom = "";
$des_canalcom = "";
$log_personaliza = "";
$cod_submenus = "";
$abasComunicacao = "";
$arrayQuery = [];
$qrListaComunicacao = "";
$qrBuscaSistema = "";
$sistemasMarka = "";
$qrListaSistemas = "";
$mostraAutoriza = "";
$sqlSis = "";
$arraySistemas = [];
$qrBuscaSistemas = "";
$qrBuscaModulos = "";


$hashLocal = mt_rand();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_preco = fnLimpaCampoZero(@$_POST['COD_PRECO']);
		$cod_canalcom = fnLimpaCampoZero(@$_POST['COD_CANALCOM']);
		$cod_comfaixa = fnLimpaCampoZero(@$_POST['COD_COMFAIXA']);
		$cod_sistema = fnLimpaCampoZero(@$_POST['COD_SISTEMA']);
		$qtd_diasvalid = fnLimpaCampoZero(@$_POST['QTD_DIASVALID']);
		$val_unitario = fnLimpaCampo(@$_POST['VAL_UNITARIO']);
		$val_total = fnLimpaCampo(@$_POST['VAL_TOTAL']);
		// $des_icone = fnLimpaCampo(@$_REQUEST['DES_ICONE']);
		// $des_cor = fnLimpaCampoHtml(@$_REQUEST['DES_COR']);			
		// if (empty(@$_REQUEST['LOG_BESTVAL'])) {$log_bestval='N';}else{$log_bestval=@$_REQUEST['LOG_BESTVAL'];}
		// if (empty(@$_REQUEST['LOG_PRECO'])) {$log_preco='N';}else{$log_preco=@$_REQUEST['LOG_PRECO'];}
		// $num_ordenac = @$_POST['NUM_ORDENAC'];

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
					$sql = "INSERT INTO COMUNICACAO_PRECO(
									COD_CANALCOM,
									COD_COMFAIXA,
									COD_SISTEMA,
									QTD_DIASVALID,
									VAL_UNITARIO,
									VAL_TOTAL,
									COD_USUCADA
									)VALUES(
									$cod_canalcom,
									$cod_comfaixa,
									$cod_sistema,
									$qtd_diasvalid,
									'" . fnValorSql($val_unitario) . "',									
									'" . fnValorSql($val_total) . "',
									$cod_usucada
									);";

					//echo $sql;
					//fnEscreve($sql);								

					mysqli_query($connAdm->connAdm(), trim($sql));

					$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					break;

				case 'ALT':
					$sql = "UPDATE COMUNICACAO_PRECO SET								
								COD_CANALCOM = $cod_canalcom, 
								COD_COMFAIXA = $cod_comfaixa, 
								COD_SISTEMA = $cod_sistema, 
								QTD_DIASVALID = $qtd_diasvalid, 
								VAL_UNITARIO = '" . fnValorSql($val_unitario) . "', 
								VAL_TOTAL = '" . fnValorSql($val_total) . "'				
								WHERE COD_PRECO = $cod_preco;";


					mysqli_query($connAdm->connAdm(), trim($sql));


					$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";
					break;

				case 'EXC':
					$sql = "DELETE FROM COMUNICACAO_PRECO WHERE COD_PRECO = $cod_preco;";
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
				//menu preços
				$abasComunicacao = 1454;
				include "abasComunicacao.php";
				?>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Dados Gerais</legend>

							<div class="row">
								<div class="col-md-1">
									<div class="form-group">
										<label for="inputName" class="control-label required">Código Preço</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_PRECO" id="COD_PRECO" value="">
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Tipo da Comunicação</label>
										<select data-placeholder="Selecione o tipo" name="COD_CANALCOM" id="COD_CANALCOM" class="chosen-select-deselect requiredChk" required>
											<option value="">&nbsp;</option>
											<?php
											$sql = "SELECT COD_CANALCOM, DES_CANALCOM FROM CANAL_COMUNICACAO order by DES_CANALCOM ";
											$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

											while ($qrListaComunicacao = mysqli_fetch_assoc($arrayQuery)) {
												echo "
																				  <option value='" . $qrListaComunicacao['COD_CANALCOM'] . "'>" . $qrListaComunicacao['DES_CANALCOM'] . "</option> 
																				";
											}
											?>
										</select>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Nome da Faixa</label>
										<select data-placeholder="Selecione o tipo" name="COD_COMFAIXA" id="COD_COMFAIXA" class="chosen-select-deselect requiredChk" required>
											<option value="">&nbsp;</option>
											<?php
											$sql = "SELECT COD_COMFAIXA, NOM_FAIXA, (NUM_FAIXAFIM) AS QTD FROM COMUNICACAO_FAIXAS order by NOM_FAIXA";
											$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

											while ($qrListaComunicacao = mysqli_fetch_assoc($arrayQuery)) {
												echo "
																				  <option data-qtd='" . $qrListaComunicacao['QTD'] . "' value='" . $qrListaComunicacao['COD_COMFAIXA'] . "'>" . $qrListaComunicacao['NOM_FAIXA'] . "</option> 
																				";
											}
											?>
										</select>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Sistema</label>
										<select data-placeholder="Selecione o sistema" name="COD_SISTEMA" id="COD_SISTEMA" class="chosen-select-deselect requiredChk" required>
											<option value="">&nbsp;</option>
											<?php

											if ($_SESSION["SYS_COD_MASTER"] == "2") {

												$sql = "SELECT COD_SISTEMA, DES_SISTEMA FROM SISTEMAS";
											} else {

												$sql = "SELECT COD_SISTEMAS FROM EMPRESAS WHERE COD_EMPRESA = 3 ";
												$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
												$qrBuscaSistema = mysqli_fetch_assoc($arrayQuery);
												$sistemasMarka = $qrBuscaSistema['COD_SISTEMAS'];

												$sql = "SELECT COD_SISTEMA, DES_SISTEMA FROM SISTEMAS WHERE COD_SISTEMA IN (" . $sistemasMarka . ") order by DES_SISTEMA ";
											}
											$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

											while ($qrListaSistemas = mysqli_fetch_assoc($arrayQuery)) {
												if ($qrListaSistemas['COD_SISTEMA'] == 'S') {
													$mostraAutoriza = '<i class="fal fa-check" aria-hidden="true"></i>';
												} else {
													$mostraAutoriza = '';
												}

												echo "
																		<option value='" . $qrListaSistemas['COD_SISTEMA'] . "'>" . $qrListaSistemas['DES_SISTEMA'] . "</option> 
																	";
											}

											?>
										</select>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-1">
									<div class="form-group">
										<label for="inputName" class="control-label">Validade</label>
										<input type="text" class="form-control input-sm int" name="QTD_DIASVALID" id="QTD_DIASVALID" maxlength="20">
										<div class="help-block with-errors">Em dias</div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Valor Unitário</label>
										<input type="text" class="form-control input-sm valor" name="VAL_UNITARIO" id="VAL_UNITARIO" maxlength="20">
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Valor Total</label>
										<input type="text" class="form-control input-sm money" name="VAL_TOTAL" id="VAL_TOTAL" maxlength="20" readonly>
										<div class="help-block with-errors"></div>
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

								<?php

								$sqlSis = "SELECT distinct CP.COD_SISTEMA, SIS.DES_SISTEMA from COMUNICACAO_PRECO CP
															INNER JOIN SISTEMAS SIS ON SIS.COD_SISTEMA = CP.COD_SISTEMA";
								$arraySistemas = mysqli_query($connAdm->connAdm(), $sqlSis);

								$count = 0;
								while ($qrBuscaSistemas = mysqli_fetch_assoc($arraySistemas)) {

								?>

									<table class="table table-bordered table-striped table-hover table-sortable">
										<thead>
											<tr>
												<th colspan="7">
													<h3><?= $qrBuscaSistemas['DES_SISTEMA'] ?></h3>
												</th>
											</tr>
										</thead>
										<thead>
											<tr>
												<th width="40"></th>
												<th>Código Preço</th>
												<th>Canal da Comunicação</th>
												<th>Nome da Faixa</th>
												<th class="text-center">Dias Valid.</th>
												<th>Valor Unitário</th>
												<th>Valor Total</th>
												<!-- <th>Ícone</th> -->
											</tr>
										</thead>
										<tbody>

											<?php

											$sql = "SELECT CP.*, CC.DES_CANALCOM, CF.NOM_FAIXA from COMUNICACAO_PRECO CP 
																	LEFT JOIN CANAL_COMUNICACAO CC ON CC.COD_CANALCOM = CP.COD_CANALCOM 
																	LEFT JOIN COMUNICACAO_FAIXAS CF ON CF.COD_COMFAIXA = CP.COD_COMFAIXA
																	WHERE COD_SISTEMA = $qrBuscaSistemas[COD_SISTEMA]
																	";
											$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);


											while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery)) {
												$count++;
												echo "
																	<tr>
																	  <td align='center'><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></th>
																	  <td>" . $qrBuscaModulos['COD_PRECO'] . "</td>
																	  <td>" . $qrBuscaModulos['DES_CANALCOM'] . "</td>
																	  <td>" . $qrBuscaModulos['NOM_FAIXA'] . "</td>
																	  <td class='text-center'>" . fnValor($qrBuscaModulos['QTD_DIASVALID'], 0) . "</td>
																	  <td>" . fnValor($qrBuscaModulos['VAL_UNITARIO'], 5) . "</td>
																	  <td>" . fnValor($qrBuscaModulos['VAL_TOTAL'], 2) . "</td>															  													  
																	</tr>
																	<input type='hidden' id='ret_COD_PRECO_" . $count . "' value='" . $qrBuscaModulos['COD_PRECO'] . "'>
																	<input type='hidden' id='ret_COD_CANALCOM_" . $count . "' value='" . $qrBuscaModulos['COD_CANALCOM'] . "'>
																	<input type='hidden' id='ret_COD_COMFAIXA_" . $count . "' value='" . $qrBuscaModulos['COD_COMFAIXA'] . "'>
																	<input type='hidden' id='ret_COD_SISTEMA_" . $count . "' value='" . $qrBuscaModulos['COD_SISTEMA'] . "'>
																	<input type='hidden' id='ret_QTD_DIASVALID_" . $count . "' value='" . $qrBuscaModulos['QTD_DIASVALID'] . "'>
																	<input type='hidden' id='ret_VAL_UNITARIO_" . $count . "' value='" . fnValor($qrBuscaModulos['VAL_UNITARIO'], 5) . "'>
																	<input type='hidden' id='ret_VAL_TOTAL_" . $count . "' value='" . fnValor($qrBuscaModulos['VAL_TOTAL'], 2) . "'>															
																	";
											}

											?>

										</tbody>
									</table>

									<div class="push30"></div>

								<?php

								}

								?>



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
	// $(function() {

	// 	$( ".table-sortable tbody" ).sortable();

	//           $('.table-sortable tbody').sortable({
	//               handle: 'span'
	//           });

	//    $(".table-sortable tbody").sortable({

	// 			stop: function(event, ui) {

	// 				var Ids = "";
	// 				$('table tr').each(function( index ) {
	// 					if(index != 0){
	// 							Ids =  Ids + $(this).children().find('span.fa-equals').attr('data-id') +",";
	// 					}
	// 				});

	// 				//update ordenação
	// 				//console.log(Ids.substring(0,(Ids.length-1)));

	// 				var arrayOrdem = Ids.substring(0,(Ids.length-1));
	// 				//alert(arrayOrdem);
	// 				execOrdenacao(arrayOrdem,14);

	// 				function execOrdenacao(p1,p2) {
	// 					//alert(p2);
	// 					$.ajax({
	// 						type: "GET",
	// 						url: "ajxOrdenacao.php",
	// 						data: { ajx1:p1,ajx2:p2},
	// 						beforeSend:function(){
	// 							//$('#divId_sub').html('<div class="loading" style="width: 100%;"></div>');
	// 						},
	// 						success:function(data){
	// 							//$("#divId_sub").html(data); 
	// 						},
	// 						error:function(){
	// 							$('#divId_sub').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Falha no processamento...</p>');
	// 						}
	// 					});		
	// 				}

	// 			}

	// 	});


	// 	$( ".table-sortable tbody" ).disableSelection();		

	// });
</script>

<script type="text/javascript">
	$(document).ready(function() {

		$('.valor').mask('000.000.000.000.000,00000', {
			reverse: true
		});

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

		$('#COD_COMFAIXA').change(function() {
			if ($('#VAL_UNITARIO').val() != '') {
				calcTotal();
			}
		});

		$('#VAL_UNITARIO').keyup(function() {
			if ($('#COD_COMFAIXA').val() != '') {
				calcTotal();
			}
		});

		function calcTotal() {
			var val1 = $('#VAL_UNITARIO').val().replace(',', '.');
			var val2 = $('#COD_COMFAIXA option:selected').attr('data-qtd');
			console.log(val1);
			console.log(val2);
			var valor = (val1 * val2).toFixed(2);
			$('#VAL_TOTAL').val(valor).mask('000.000.000.000.000,00', {
				reverse: true
			});
		}

		// //icon picker
		// $('.btnSearchIcon').iconpicker({ 
		// 	cols: 8,
		// 	iconset: 'fontawesome',   
		// 	rows: 6,
		// 	searchText: 'Procurar  &iacute;cone'
		// });	

		//capturando o ícone selecionado no botão
		// $('#btniconpicker').on('change', function(e) {
		//     $('#DES_ICONE').val(e.icon);
		//alert($('#DES_ICONE').val());
		// });

	});


	function retornaForm(index) {
		$("#formulario #COD_PRECO").val($("#ret_COD_PRECO_" + index).val());
		$("#formulario #COD_CANALCOM").val($("#ret_COD_CANALCOM_" + index).val()).trigger("chosen:updated");
		$("#formulario #COD_COMFAIXA").val($("#ret_COD_COMFAIXA_" + index).val()).trigger("chosen:updated");
		$("#formulario #COD_SISTEMA").val($("#ret_COD_SISTEMA_" + index).val()).trigger("chosen:updated");
		$("#formulario #QTD_DIASVALID").val($("#ret_QTD_DIASVALID_" + index).val());
		$("#formulario #VAL_UNITARIO").val($("#ret_VAL_UNITARIO_" + index).val());
		$("#formulario #VAL_TOTAL").val($("#ret_VAL_TOTAL_" + index).val());
		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
	}
</script>