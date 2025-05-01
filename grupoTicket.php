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
$cod_categortkt = "";
$des_categor = "";
$des_abrevia = "";
$des_icones = "";
$log_destak = "";
$hHabilitado = "";
$hashForm = "";
$sqlCod = "";
$arrayCod = [];
$qrCod = "";
$sqlOrd = "";
$arrayOrd = [];
$qrOrd = "";
$sqlUpdt = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$formBack = "";
$abaModulo = "";
$qrBuscaProdutos = "";
$mostraDestak = "";

$hashLocal = mt_rand();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_categortkt = fnLimpaCampoZero(@$_REQUEST['COD_CATEGORTKT']);
		$cod_empresa = fnLimpaCampoZero(@$_REQUEST['COD_EMPRESA']);
		$des_categor = fnLimpaCampo(@$_REQUEST['DES_CATEGOR']);
		$des_abrevia = fnLimpaCampo(@$_REQUEST['DES_ABREVIA']);
		$des_icones = fnLimpaCampo(@$_REQUEST['DES_ICONES']);
		if (empty(@$_REQUEST['LOG_DESTAK'])) {
			$log_destak = 'N';
		} else {
			$log_destak = @$_REQUEST['LOG_DESTAK'];
		}

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		//fnEscreve($des_icones);	

		if ($opcao != '') {

			$sql = "CALL SP_ALTERA_CATEGORIATKT (
				 '" . $cod_categortkt . "', 
				 '" . $cod_empresa . "', 
				 '" . $des_categor . "', 
				 '" . $des_abrevia . "', 
				 '" . $des_icones . "', 
				 '" . $log_destak . "', 
				 '" . $_SESSION["SYS_COD_USUARIO"] . "', 
				 '" . $opcao . "'    
				) ";


			// fnEscreve($sql);

			mysqli_query(connTemp($cod_empresa, ""), trim($sql));

			//mensagem de retorno
			switch ($opcao) {
				case 'CAD':

					$sqlCod = "SELECT MAX(COD_CATEGORTKT) AS COD_CATEGORTKT
								   FROM CATEGORIATKT
								   WHERE COD_EMPRESA = $cod_empresa
								   AND COD_USUCADA = $_SESSION[SYS_COD_USUARIO]";

					$arrayCod = mysqli_query(connTemp($cod_empresa, ""), trim($sqlCod));
					$qrCod = mysqli_fetch_assoc($arrayCod);

					$sqlOrd = "SELECT MAX(NUM_ORDENAC) AS NUM_ORDENAC
								   FROM CATEGORIATKT
								   WHERE COD_EMPRESA = $cod_empresa";

					$arrayOrd = mysqli_query(connTemp($cod_empresa, ""), trim($sqlOrd));
					$qrOrd = mysqli_fetch_assoc($arrayOrd);

					$sqlUpdt = "UPDATE CATEGORIATKT 
								SET NUM_OREDENAC = ($qrOrd[NUM_OREDENAC]+1)
								WHERE COD_EMPRESA = $cod_empresa
								AND COD_CATEGORTKT = $qrCod[COD_CATEGORTKT]";

					mysqli_query(connTemp($cod_empresa, ""), trim($sqlUpdt));

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


//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode(@$_GET['id']);
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";

	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($qrBuscaEmpresa)) {
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
	}
} else {
	$cod_empresa = 0;
	$nom_empresa = "";
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
					<i class="fal fa-terminal"></i>
					<span class="text-primary"><?php echo $NomePg; ?></span>
				</div>

				<?php
				$formBack = "1108";
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

				<?php $abaModulo = 1107;
				include "abasTicketConfig.php"; ?>

				<div class="push30"></div>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Dados Gerais</legend>

							<div class="row">
								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Código</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_CATEGORTKT" id="COD_CATEGORTKT" value="">
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Empresa</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa ?>">
										<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label required">Nome do Grupo</label>
										<input type="text" class="form-control input-sm" name="DES_CATEGOR" id="DES_CATEGOR" maxlength="20" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Abreviação</label>
										<input type="text" class="form-control input-sm" name="DES_ABREVIA" id="DES_ABREVIA" value="">
									</div>
								</div>

								<div class="col-md-1">
									<div class="form-group">
										<label for="inputName" class="control-label">Ícone</label><br />
										<button class="btn btn-primary" id="btniconpicker" data-iconset="fontawesome" data-icon="vazio" role="iconpicker" data-arrow-prev-icon-class="fas fa-arrow-left" data-arrow-next-icon-class="fas fa-arrow-right" data-rows="6" data-cols="6" data-search-text="Buscar ícone..." data-label-footer="{0} - {1} de {2} ícones" data-label-header="{0} de {1}">
										</button>
										<input type="hidden" name="DES_ICONE" id="DES_ICONE" value="">
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

						<input type="hidden" name="LOG_DESTAK" id="LOG_DESTAK" value="N">
						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">

						<div class="push5"></div>

					</form>

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
											<th>Nome do Grupo</th>
											<th>Abreviação</th>
											<th>Ícone</th>
											<!--<th class="bg-primary">Destaque</th>-->
										</tr>
									</thead>
									<tbody>

										<?php

										$sql = "select * from CATEGORIATKT where COD_EMPRESA = '" . $cod_empresa . "' AND DAT_EXCLUSA IS NULL order by NUM_ORDENAC";
										$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

										$count = 0;
										while ($qrBuscaProdutos = mysqli_fetch_assoc($arrayQuery)) {
											$count++;

											if ($qrBuscaProdutos['LOG_DESTAK'] == 'S') {
												$mostraDestak = '<i class="fal fa-check" aria-hidden="true"></i>';
											} else {
												$mostraDestak = '';
											}

											echo "
												<tr>
													<td align='center'><span class='fal fa-arrows grabbable' data-id='" . $qrBuscaProdutos['COD_CATEGORTKT'] . "'></span></td>
													<td><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></td>
													<td>" . $qrBuscaProdutos['COD_CATEGORTKT'] . "</td>
													<td>" . $qrBuscaProdutos['DES_CATEGOR'] . "</td>
													<td>" . $qrBuscaProdutos['DES_ABREVIA'] . "</td>
													<td align='center'><i class='fa  " . $qrBuscaProdutos['DES_ICONES'] . "'></i></td>
													<!--<td align='center'>" . $mostraDestak . "</td>-->
												</tr>
												<input type='hidden' id='ret_COD_CATEGORTKT_" . $count . "' value='" . $qrBuscaProdutos['COD_CATEGORTKT'] . "'>
												<input type='hidden' id='ret_DES_CATEGOR_" . $count . "' value='" . $qrBuscaProdutos['DES_CATEGOR'] . "'>
												<input type='hidden' id='ret_DES_ABREVIA_" . $count . "' value='" . $qrBuscaProdutos['DES_ABREVIA'] . "'>
												<input type='hidden' id='ret_DES_ICONES_" . $count . "' value='" . $qrBuscaProdutos['DES_ICONES'] . "'>
												<input type='hidden' id='ret_LOG_DESTAK_" . $count . "' value='" . $qrBuscaProdutos['LOG_DESTAK'] . "'>
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

<!-- <link rel="stylesheet" href="fonts/font-awesome-4.2.0/css/font-awesome.min.css"/> -->
<link rel="stylesheet" href="../css/bootstrap-iconpicker.min.css" />

<script type="text/javascript" src="../js/bootstrap-iconpicker-iconset-fa5.js"></script>
<script type="text/javascript" src="../js/bootstrap-iconpicker.js"></script>

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script type="text/javascript">
	$(document).ready(function() {

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

		$(".table-sortable tbody").sortable();

		$('.table-sortable tbody').sortable({
			handle: 'span'
		});

		$(".table-sortable tbody").sortable({

			stop: function(event, ui) {

				var Ids = "";
				$('table tr').each(function(index) {
					if (index != 0) {
						Ids = Ids + $(this).children().find('span.fal').attr('data-id') + ",";
					}
				});

				//update ordenação
				console.log(Ids.substring(0, (Ids.length - 1)));

				var arrayOrdem = Ids.substring(0, (Ids.length - 1));
				//alert(arrayOrdem);
				execOrdenacao(arrayOrdem, 1);

				function execOrdenacao(p1, p2) {
					var codEmpresa = <?php echo $cod_empresa ?>;
					$.ajax({
						type: "GET",
						url: "ajxOrdenacaoEmp.php",
						data: {
							ajx1: p1,
							ajx2: p2,
							ajx3: codEmpresa
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

	function retornaForm(index) {
		$("#formulario #COD_CATEGORTKT").val($("#ret_COD_CATEGORTKT_" + index).val());
		$("#formulario #DES_CATEGOR").val($("#ret_DES_CATEGOR_" + index).val());
		$("#formulario #DES_ABREVIA").val($("#ret_DES_ABREVIA_" + index).val());
		$("#formulario #DES_ICONES").val($("#ret_DES_ICONES_" + index).val());
		$('#btnIcon').iconpicker('setIcon', $("#ret_DES_ICONES_" + index).val());
		if ($("#ret_LOG_DESTAK_" + index).val() == 'S') {
			$('#formulario #LOG_DESTAK').prop('checked', true);
		} else {
			$('#formulario #LOG_LOG_DESTAK').prop('checked', false);
		}
		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
	}
</script>