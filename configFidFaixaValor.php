<?php

//echo fnDebug('true');

$hashLocal = mt_rand();

$cod_geral = 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_geral = fnLimpaCampoZero($_POST['COD_GERAL']);
		$cod_campanha = fnLimpaCampoZero($_POST['COD_CAMPANHA']);
		$cod_empresa = fnLimpaCampoZero($_POST['COD_EMPRESA']);
		$tip_faixas = "VAL";
		$val_faixini = $_POST['VAL_FAIXINI'];
		$val_faixfim = $_POST['VAL_FAIXFIM'];
		$qtd_faixext = $_POST['QTD_FAIXEXT'];
		$tip_faixext = $_POST['TIP_FAIXEXT'];
		$qtd_faixlim = $_POST['QTD_FAIXLIM'];
		$cod_produto = 0;
		$cod_formapa = 0;
		$cod_categoria = 0;
		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		if ($opcao != '') {

			//busca dados da regra extra (tela) 
			$sql = "SELECT COD_EXTRA FROM VANTAGEMEXTRA where COD_CAMPANHA = '" . $cod_campanha . "' ";
			//fnEscreve($sql);

			$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
			$tem_extra = mysqli_num_rows($arrayQuery);

			if ($tem_extra == 0) {

				$sqlExtra = "INSERT INTO VANTAGEMEXTRA(
												COD_CAMPANHA, 
												COD_USUCADA, 
												COD_EMPRESA
											 ) VALUES(
											 	$cod_campanha,
											 	$cod_usucada,
											 	$cod_empresa
											 )";

				mysqli_query(connTemp($cod_empresa, ''), $sqlExtra);
			}

			$sql = "CALL SP_ALTERA_VANTAGEMEXTRAFAIXA (
				 '" . $cod_geral . "', 
				 '" . $cod_campanha . "', 
				 '" . $cod_empresa . "', 
				 '" . $cod_categoria . "', 
				 '" . $tip_faixas . "', 
				 '" . fnValorSql($val_faixini) . "',
				 '" . fnValorSql($val_faixfim) . "',
				 '" . fnValorSql($qtd_faixext) . "',
				 '" . $tip_faixext . "',
				 '" . $qtd_faixlim . "',
				 '" . $cod_produto . "',
				 '" . $cod_formapa . "',
				 '" . $cod_usucada . "',
				 '1',
				 '0',
				 '" . $opcao . "'    
				) ";

			//echo $sql;

			mysqli_query(connTemp($cod_empresa, ''), trim($sql));
			//fnEscreve($sql2); 

			//busca quantidade total de itens	
			$sql2 = "select count(*) as TEMFAIXA from VANTAGEMEXTRAFAIXA where COD_CAMPANHA = '" . $cod_campanha . "' AND TIP_FAIXAS = 'VAL' ";
			//fnEscreve($sql2);

			//fnEscreve($sql);
			$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql2);
			$qrBuscaTotalExtra = mysqli_fetch_assoc($arrayQuery);
			$temfaixa = $qrBuscaTotalExtra['TEMFAIXA'];

			//if ($temfaixa > 0) {					

			$sql3 = "update VANTAGEMEXTRA set QTD_TOTFAIXA = " . $temfaixa . " where cod_campanha = " . $cod_campanha . " ";
			$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql3);

			//atualiza lista iframe				
?>
			<script>
				try {
					parent.$('#REFRESH_FAIXAS').val("S");
				} catch (err) {}
			</script>
<?php

			//}

			//mensagem de retorno
			switch ($opcao) {
				case 'CAD':
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

//busca dados da campanha
$cod_campanha = fnDecode($_GET['idc']);
$cod_empresa = fnDecode($_GET['id']);
$sql = "SELECT * FROM CAMPANHA where COD_CAMPANHA = '" . $cod_campanha . "' ";
//fnEscreve($_GET['idc']);
//fnEscreve(fnDecode($_GET['idc']));
//fnEscreve($sql);
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
$qrBuscaCampanha = mysqli_fetch_assoc($arrayQuery);

if (isset($arrayQuery)) {
	$log_ativo = $qrBuscaCampanha['LOG_ATIVO'];
	$des_campanha = $qrBuscaCampanha['DES_CAMPANHA'];
	$abr_campanha = $qrBuscaCampanha['ABR_CAMPANHA'];
	$des_icone = $qrBuscaCampanha['DES_ICONE'];
	$tip_campanha = $qrBuscaCampanha['TIP_CAMPANHA'];
	$log_realtime = $qrBuscaCampanha['LOG_REALTIME'];
}

//busca dados do tipo da campanha
$sql = "SELECT * FROM TIPOCAMPANHA where COD_TPCAMPA = '" . $tip_campanha . "' ";
//fnEscreve($sql);
$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
$qrBuscaTpCampanha = mysqli_fetch_assoc($arrayQuery);

if (isset($arrayQuery)) {
	$nom_tpcampa = $qrBuscaTpCampanha['NOM_TPCAMPA'];
	$abv_tpcampa = $qrBuscaTpCampanha['ABV_TPCAMPA'];
	$des_iconecp = $qrBuscaTpCampanha['DES_ICONE'];
	$label_1 = $qrBuscaTpCampanha['LABEL_1'];
	$label_2 = $qrBuscaTpCampanha['LABEL_2'];
	$label_3 = $qrBuscaTpCampanha['LABEL_3'];
	$label_4 = $qrBuscaTpCampanha['LABEL_4'];
	$label_5 = $qrBuscaTpCampanha['LABEL_5'];
}

//fnMostraForm();

?>

<?php if ($popUp != "true") {  ?>
	<div class="push30"></div>
<?php } ?>

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
							<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
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

					<div class="login-form">

						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

							<fieldset>
								<legend>Faixa de Valor</legend>

								<div class="row">

									<div class="col-md-1">
										<h4 class="text-center" style="padding-top: 13px;">R$</h4>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">De</label>
											<input type="text" class="form-control input-sm text-center money" name="VAL_FAIXINI" id="VAL_FAIXINI" maxlength="15" value="" required>
											<span class="help-block">valor</span>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Até</label>
											<input type="text" class="form-control input-sm text-center money" name="VAL_FAIXFIM" id="VAL_FAIXFIM" maxlength="15" value="" required>
											<span class="help-block">valor</span>
										</div>
									</div>

									<div class="col-md-1">
										<h4 class="text-center" style="padding-top: 8px; font-size: 30px;">=</h4>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Qtd. Extra</label>
											<input type="text" class="form-control input-sm text-center money" name="QTD_FAIXEXT" id="QTD_FAIXEXT" maxlength="20" value="" required>
											<span class="help-block">valor</span>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Ganha</label>
											<select data-placeholder="Selecione um estado civil" name="TIP_FAIXEXT" id="TIP_FAIXEXT" class="chosen-select-deselect requiredChk" required>
												<option value="">...</option>
												<option value="PCT">Percentual sobre a venda</option>
												<option value="ABS"><?php echo $nom_tpcampa; ?></option>
											</select>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Limite de Uso</label>
											<input type="text" class="form-control input-sm text-center int" name="QTD_FAIXLIM" id="QTD_FAIXLIM" maxlength="20" value="" required>
											<span class="help-block">quantidade máxima</span>
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

							<input type="hidden" name="COD_GERAL" id="COD_GERAL" value="<?php echo $cod_geral; ?>">
							<input type="hidden" name="COD_CAMPANHA" id="COD_CAMPANHA" value="<?php echo $cod_campanha; ?>">
							<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">

							<input type="hidden" name="opcao" id="opcao" value="">
							<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
							<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">

							<div class="push5"></div>

						</form>

						<div class="push50"></div>

						<div id="div_Ordena"></div>

						<div class="col-lg-12">

							<div class="no-more-tables">

								<form name="formLista">

									<table class="table table-bordered table-striped table-hover table-sortable">
										<thead>
											<tr>
												<th class="grabbable" width="40"></th>
												<th width="40"></th>
												<th>Código</th>
												<th>Campanha</th>
												<th>De / Até</th>
												<th>Ganha</th>
												<th>Limite</th>
											</tr>
										</thead>
										<tbody>

											<?php

											$sql = "select A.*,
													(select B.DES_CAMPANHA from CAMPANHA B where B.COD_CAMPANHA = A.COD_CAMPANHA ) as NOM_CAMPANHA
													from VANTAGEMEXTRAFAIXA A where A.COD_CAMPANHA = '" . $cod_campanha . "' AND A.TIP_FAIXAS = 'VAL' order by A.VAL_FAIXINI													
													";
											//fnEscreve($sql);
											$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

											$count = 0;

											while ($qrBuscaCampanhaExtra = mysqli_fetch_assoc($arrayQuery)) {
												$count++;

												if ($qrBuscaCampanhaExtra['TIP_FAIXEXT'] == "ABS") {
													$tipoGanho = $nom_tpcampa;
												} else {
													$tipoGanho = "Percentual";
												}

												echo "
															<tr>
															  <td align='center'><span class='glyphicon glyphicon-move grabbable' data-id='" . $qrBuscaCampanhaExtra['COD_VANTAGEMFAIXA'] . "'></span></td>
															  <td align='center'><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></th>
															  <td>" . $qrBuscaCampanhaExtra['COD_VANTAGEMFAIXA'] . "</td>
															  <td>" . $qrBuscaCampanhaExtra['NOM_CAMPANHA'] . "</td>
															  <td>R$ " . number_format($qrBuscaCampanhaExtra['VAL_FAIXINI'], 2, ",", ".") . " à R$ " . number_format($qrBuscaCampanhaExtra['VAL_FAIXFIM'], 2, ",", ".") . "</td>
															  <td>" . number_format($qrBuscaCampanhaExtra['QTD_FAIXEXT'], 2, ",", ".") . " " . $tipoGanho . "</td>															
															  <td>" . $qrBuscaCampanhaExtra['QTD_FAIXLIM'] . "</td>
															</tr>
															<input type='hidden' id='ret_COD_GERAL_" . $count . "' value='" . $qrBuscaCampanhaExtra['COD_VANTAGEMFAIXA'] . "'>
															<input type='hidden' id='ret_VAL_FAIXINI_" . $count . "' value='" . number_format($qrBuscaCampanhaExtra['VAL_FAIXINI'], 2, ",", ".") . "'>
															<input type='hidden' id='ret_VAL_FAIXFIM_" . $count . "' value='" . number_format($qrBuscaCampanhaExtra['VAL_FAIXFIM'], 2, ",", ".") . "'>
															<input type='hidden' id='ret_QTD_FAIXEXT_" . $count . "' value='" . number_format($qrBuscaCampanhaExtra['QTD_FAIXEXT'], 2, ",", ".") . "'>
															<input type='hidden' id='ret_TIP_FAIXEXT_" . $count . "' value='" . $qrBuscaCampanhaExtra['TIP_FAIXEXT'] . "'>
															<input type='hidden' id='ret_QTD_FAIXLIM_" . $count . "' value='" . $qrBuscaCampanhaExtra['QTD_FAIXLIM'] . "'>
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

	<link rel="stylesheet" href="fonts/font-awesome-4.2.0/css/font-awesome.min.css" />
	<link rel="stylesheet" href="js/plugins/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css" />

	<script type="text/javascript" src="js/plugins/bootstrap-iconpicker/js/iconset/iconset-fontawesome-4.2.0.min.js"></script>
	<script type="text/javascript" src="js/plugins/bootstrap-iconpicker/js/bootstrap-iconpicker.min.js"></script>

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
					execOrdenacao(arrayOrdem, <?php echo $cod_campanha; ?>, <?php echo $cod_empresa; ?>, 1);


					function execOrdenacao(p1, p2, p3, p4) {
						//alert(p2);
						$.ajax({
							type: "GET",
							url: "ajxOrdenacaoExtra.php",
							data: {
								ajx1: p1,
								ajx2: p2,
								ajx3: p3,
								ajx4: p4
							},
							beforeSend: function() {
								//$('#div_Ordena').html('<div class="loading" style="width: 100%;"></div>');
							},
							success: function(data) {
								//$("#div_Ordena").html(data); 
							},
							error: function() {
								$('#div_Ordena').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Falha no processamento...</p>');
							}
						});
					}


				}

			});


			$(".table-sortable tbody").disableSelection();

		});


		$(document).ready(function() {

			//chosen obrigatório
			$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
			$('#formulario').validator();

			//arrastar 
			$('.grabbable').on('change', function(e) {
				//console.log(e.icon);
				$("#DES_ICONE").val(e.icon);
			});

			$(".grabbable").click(function() {
				$(this).parent().addClass('selected').siblings().removeClass('selected');

			});


		});


		function retornaForm(index) {

			$("#formulario #COD_GERAL").val($("#ret_COD_GERAL_" + index).val());
			$("#formulario #VAL_FAIXINI").val($("#ret_VAL_FAIXINI_" + index).val());
			$("#formulario #VAL_FAIXFIM").val($("#ret_VAL_FAIXFIM_" + index).val());
			$("#formulario #TIP_FAIXEXT").val($("#ret_TIP_FAIXEXT_" + index).val()).trigger("chosen:updated");
			$("#formulario #QTD_FAIXEXT").val($("#ret_QTD_FAIXEXT_" + index).val());
			$("#formulario #QTD_FAIXLIM").val($("#ret_QTD_FAIXLIM_" + index).val());

			$('#formulario').validator('validate');
			$("#formulario #hHabilitado").val('S');
		}
	</script>