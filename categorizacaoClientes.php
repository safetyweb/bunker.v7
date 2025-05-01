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
$cod_categoria = "";
$nom_faixacat = "";
$val_faixaini = "";
$val_faixafim = "";
$tip_pontua = "";
$num_ordenac = "";
$nom_usuario = "";
$actual_link = "";
$MODULO = "";
$COD_MODULO = "";
$hHabilitado = "";
$hashForm = "";
$cod_usucada = "";
$arrayProc = [];
$cod_erro = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$abaEmpresa = "";
$abaCategoria = "";
$qrBuscaCategoria = "";


//echo fnDebug('true');

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

		$cod_categoria = fnLimpaCampoZero(@$_REQUEST['COD_CATEGORIA']);
		$nom_faixacat = fnLimpaCampo(@$_REQUEST['NOM_FAIXACAT']);
		$val_faixaini = fnLimpaCampo(@$_REQUEST['VAL_FAIXAINI']);
		$val_faixafim = fnLimpaCampo(@$_REQUEST['VAL_FAIXAFIM']);
		$tip_pontua = fnLimpaCampo(@$_REQUEST['TIP_PONTUA']);
		$num_ordenac = fnLimpaCampoZero(@$_REQUEST['NUM_ORDENAC']);
		$cod_empresa = fnLimpaCampoZero(@$_REQUEST['COD_EMPRESA']);

		$nom_usuario = $_SESSION["SYS_NOM_USUARIO"];
		$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$MODULO = @$_GET['mod'];
		$COD_MODULO = fndecode(@$_GET['mod']);

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];
		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

		if ($opcao != '') {

			$sql = "CALL SP_ALTERA_CATEGORIA_CLIENTE (
				 '" . $cod_categoria . "', 
				 '" . $cod_empresa . "', 
				 '" . $nom_faixacat . "', 
				 '" . fnValorSql($val_faixaini) . "', 
				 '" . fnValorSql($val_faixafim) . "', 
				 '" . $tip_pontua . "', 
				 '" . $cod_usucada . "', 
				 '" . $opcao . "'    
				) ";

			// fnescreve($sql);				
			$arrayProc = mysqli_query($conn, $sql);

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

//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode(@$_GET['id']);
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($adm, $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
	}
} else {
	$cod_empresa = 0;
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
					<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa ?></span></span>
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
				$abaEmpresa = fnDecode(@$_GET['mod']);
				include "abasEmpresaConfig.php";
				?>

				<div class="push20"></div>

				<?php
				$abaCategoria = 1264;
				include "abasCategoriaEmpresa.php";
				?>

				<div class="push30"></div>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Dados Gerais</legend>

							<div class="row">
								<div class="col-md-1">
									<div class="form-group">
										<label for="inputName" class="control-label required">Código</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_CATEGORIA" id="COD_CATEGORIA" value="">
									</div>
								</div>




								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Nome da Categoria</label>
										<input type="text" class="form-control input-sm" name="NOM_FAIXACAT" id="NOM_FAIXACAT" maxlength="50" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Faixa Inicial</label>
										<input type="text" class="form-control text-center input-sm money" name="VAL_FAIXAINI" id="VAL_FAIXAINI" maxlength="10" required>
										<div class="help-block with-errors">volume de compras</div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Faixa Final</label>
										<input type="text" class="form-control text-center input-sm money" name="VAL_FAIXAFIM" id="VAL_FAIXAFIM" maxlength="10" required>
										<div class="help-block with-errors">volume de compras</div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Tipo de Cálculo</label>
										<select data-placeholder="Selecione uma utilização" name="TIP_PONTUA" id="TIP_PONTUA" class="chosen-select-deselect requiredChk" required>
											<option value="">&nbsp;</option>
											<option value="VAL">Sobre vendas</option>
											<option value="PON">Sobre pontos</option>
										</select>
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
							<!-- <button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fal fa-times" aria-hidden="true"></i>&nbsp; Excluir</button> -->

						</div>

						<input type="hidden" name="NUM_ORDENAC" id="NUM_ORDENAC" value="">
						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">
						<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">


						<div class="push5"></div>

					</form>

					<div class="push50"></div>

					<div class="col-lg-12">

						<div id="divId_sub">
						</div>

						<div class="no-more-tables">

							<form name="formLista" id="formLista">

								<table class="table table-bordered table-striped table-hover table-sortable tableSorter">
									<thead>
										<tr>
											<th class='{ sorter: false } text-center' width="40"></th>
											<th class='{ sorter: false } text-center' width="40"></th>
											<th>Código</th>
											<th>Nome da Categoria</th>
											<th>Faixa Inicial</th>
											<th>Faixa Final</th>
										</tr>
									</thead>
									<tbody>

										<?php

										$sql = "select * from CATEGORIA_CLIENTE WHERE COD_EMPRESA = $cod_empresa order by NUM_ORDENAC";
										$arrayQuery = mysqli_query($conn, $sql);

										$count = 0;
										while ($qrBuscaCategoria = mysqli_fetch_assoc($arrayQuery)) {
											$count++;
											echo "
													<tr>
														<td align='center'><span class='fal fa-equals grabbable' data-id='" . $qrBuscaCategoria['COD_CATEGORIA'] . "'></span></td>
														<td align='center'><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></th>
														<td>" . $qrBuscaCategoria['COD_CATEGORIA'] . "</td>
														<td>" . $qrBuscaCategoria['NOM_FAIXACAT'] . "</td>
														<td class='text-right'>" . fnValor($qrBuscaCategoria['VAL_FAIXAINI'], 2) . "</td>
														<td class='text-right'>" . fnValor($qrBuscaCategoria['VAL_FAIXAFIM'], 2) . "</td>
													</tr>
													<input type='hidden' id='ret_COD_CATEGORIA_" . $count . "' value='" . $qrBuscaCategoria['COD_CATEGORIA'] . "'>
													<input type='hidden' id='ret_NOM_FAIXACAT_" . $count . "' value='" . $qrBuscaCategoria['NOM_FAIXACAT'] . "'>
													<input type='hidden' id='ret_VAL_FAIXAINI_" . $count . "' value='" . fnValor($qrBuscaCategoria['VAL_FAIXAINI'], 2) . "'>
													<input type='hidden' id='ret_VAL_FAIXAFIM_" . $count . "' value='" . fnValor($qrBuscaCategoria['VAL_FAIXAFIM'], 2) . "'>
													<input type='hidden' id='ret_NUM_ORDENAC_" . $count . "' value='" . $qrBuscaCategoria['NUM_ORDENAC'] . "'>
													<input type='hidden' id='ret_TIP_PONTUA_" . $count . "' value='" . $qrBuscaCategoria['TIP_PONTUA'] . "'>
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
						Ids = Ids + $(this).children().find('span.fa-equals').attr('data-id') + ",";
					}
				});

				//update ordenação
				//console.log(Ids.substring(0,(Ids.length-1)));

				var arrayOrdem = Ids.substring(0, (Ids.length - 1));
				//alert(arrayOrdem);
				execOrdenacao(arrayOrdem, 7, '<?= $cod_empresa ?>');

				function execOrdenacao(p1, p2, p3) {
					//alert(p2);
					$.ajax({
						type: "GET",
						url: "ajxOrdenacaoEmp.php",
						data: {
							ajx1: p1,
							ajx2: p2,
							ajx3: p3
						},
						beforeSend: function() {
							//$('#divId_sub').html('<div class="loading" style="width: 100%;"></div>');
						},
						success: function(data) {
							// $("#divId_sub").html(data);
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

<script>
	$(document).ready(function() {

		//chosen
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
		$("#formulario #COD_CATEGORIA").val($("#ret_COD_CATEGORIA_" + index).val());
		$("#formulario #NOM_FAIXACAT").val($("#ret_NOM_FAIXACAT_" + index).val());
		$("#formulario #VAL_FAIXAINI").val($("#ret_VAL_FAIXAINI_" + index).val());
		$("#formulario #VAL_FAIXAFIM").val($("#ret_VAL_FAIXAFIM_" + index).val());
		$("#formulario #NUM_ORDENAC").val($("#ret_NUM_ORDENAC_" + index).val());
		$("#formulario #TIP_PONTUA").val($("#ret_TIP_PONTUA_" + index).val()).trigger("chosen:updated");
		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
	}
</script>