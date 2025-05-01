<?php
if ($_SESSION['SYS_COD_EMPRESA'] == 2) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$hashLocal = "";
$msgRetorno = "";
$msgTipo = "";
$cod_desctkt = "";
$des_desctkt = "";
$abv_desctkt = "";
$pct_desctkt = "";
$log_ativo = "";
$hHabilitado = "";
$hashForm = "";
$des_icones = "";
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

		$cod_desctkt = fnLimpaCampoZero(@$_REQUEST['COD_DESCTKT']);
		$des_desctkt = fnLimpaCampo(@$_REQUEST['DES_DESCTKT']);
		$abv_desctkt = fnLimpaCampo(@$_REQUEST['ABV_DESCTKT']);
		$pct_desctkt = fnLimpaCampo(@$_REQUEST['PCT_DESCTKT']);
		$log_ativo = fnLimpaCampo(@$_REQUEST['LOG_ATIVO']);
		if (empty(@$_REQUEST['LOG_ATIVO'])) {
			$log_ativo = 'N';
		} else {
			$log_ativo = @$_REQUEST['LOG_ATIVO'];
		}
		$cod_empresa = fnLimpaCampoZero(@$_REQUEST['COD_EMPRESA']);

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		//fnEscreve($des_icones);	

		if ($opcao != '') {

			$sqlCad = "CALL SP_ALTERA_DESCONTOTKT (
				 '" . $cod_desctkt . "', 
				 '" . $cod_empresa . "', 
				 '" . $des_desctkt . "', 
				 '" . $abv_desctkt . "', 
				 '" . fnValorSql($pct_desctkt) . "', 
				 '" . $log_ativo . "', 
				 '" . $_SESSION["SYS_COD_USUARIO"] . "', 
				 '" . $opcao . "'    
				) ";

			// fnEscreve($sqlCad);
			// fnTestesql(connTemp($cod_empresa, ""), $sqlCad);

			mysqli_query(connTemp($cod_empresa, ""), trim($sqlCad));

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

				<?php $abaModulo = 1131;
				include "abasTicketConfig.php"; ?>

				<div class="push30"></div>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Dados Gerais</legend>

							<div class="row">

								<div class="col-md-1">
									<div class="form-group">
										<label for="inputName" class="control-label required">Código</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_DESCTKT" id="COD_DESCTKT" value="">
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Empresa</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa ?>">
										<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Descrição do Grupo de Desconto</label>
										<input type="text" class="form-control input-sm" name="DES_DESCTKT" id="DES_DESCTKT" maxlength="20" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="col-md-10">
										<div class="form-group">
											<label for="inputName" class="control-label required">Grupo no Ticket</label>
											<input type="text" class="form-control text-center input-sm int" name="ABV_DESCTKT" id="ABV_DESCTKT" maxlength="4" value="" required>
											<div class="help-block with-errors"></div>
											<span class="help-block">Item demonstrado no ticket</span>
										</div>
									</div>
									<div style="padding:23px 0 0 0; " class="text-left col-md-2 f18"><b>
											% </b>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Grupo Ativo</label>
										<div class="push5"></div>
										<label class="switch">
											<input type="checkbox" name="LOG_ATIVO" id="LOG_ATIVO" class="switch" value="S">
											<span></span>
										</label>
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
							<!--<button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fal fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>-->

						</div>

						<input type="hidden" name="PCT_DESCTKT" id="PCT_DESCTKT" value="0">
						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">

						<div class="push5"></div>

					</form>

					<div class="push50" id="divId_sub"></div>

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
											<th>Grupo no Ticket</th>
											<th>Ativo</th>
										</tr>
									</thead>
									<tbody>

										<?php

										$sql = "select * from DESCONTOTKT where COD_EMPRESA = '" . $cod_empresa . "' order by NUM_ORDENAC";
										$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

										$count = 0;
										while ($qrBuscaProdutos = mysqli_fetch_assoc($arrayQuery)) {
											$count++;

											if ($qrBuscaProdutos['LOG_ATIVO'] == 'S') {
												$mostraDestak = '<i class="fal fa-check" aria-hidden="true"></i>';
											} else {
												$mostraDestak = '';
											}

											echo "
															<tr>
															  <td align='center'><span class='fal fa-arrows grabbable' data-id='" . $qrBuscaProdutos['COD_DESCTKT'] . "'></span></td>
															  <td><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></td>
															  <td>" . $qrBuscaProdutos['COD_DESCTKT'] . "</td>
															  <td>" . $qrBuscaProdutos['DES_DESCTKT'] . "</td>
															  <td>" . $qrBuscaProdutos['ABV_DESCTKT'] . "%</td>
															  <td align='center'>" . $mostraDestak . "</td>
															</tr>
															<input type='hidden' id='ret_COD_DESCTKT_" . $count . "' value='" . $qrBuscaProdutos['COD_DESCTKT'] . "'>
															<input type='hidden' id='ret_DES_DESCTKT_" . $count . "' value='" . $qrBuscaProdutos['DES_DESCTKT'] . "'>
															<input type='hidden' id='ret_ABV_DESCTKT_" . $count . "' value='" . $qrBuscaProdutos['ABV_DESCTKT'] . "'>
															<input type='hidden' id='ret_PCT_DESCTKT_" . $count . "' value='" . fnValor($qrBuscaProdutos['PCT_DESCTKT'], 2) . "'>
															<input type='hidden' id='ret_LOG_ATIVO_" . $count . "' value='" . $qrBuscaProdutos['LOG_ATIVO'] . "'>
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

<script type="text/javascript">
	$(document).ready(function() {

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
				//console.log(Ids.substring(0,(Ids.length-1)));

				var arrayOrdem = Ids.substring(0, (Ids.length - 1));
				//alert(arrayOrdem);
				execOrdenacao(arrayOrdem, 3);

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
							//$('#divId_sub').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Falha no processamento...</p>');
						}
					});
				}

			}

		});


		$(".table-sortable tbody").disableSelection();

	});

	function retornaForm(index) {
		$("#formulario #COD_DESCTKT").val($("#ret_COD_DESCTKT_" + index).val());
		$("#formulario #DES_DESCTKT").val($("#ret_DES_DESCTKT_" + index).val());
		$("#formulario #ABV_DESCTKT").val($("#ret_ABV_DESCTKT_" + index).val());
		$("#formulario #PCT_DESCTKT").val($("#ret_PCT_DESCTKT_" + index).val());
		if ($("#ret_LOG_ATIVO_" + index).val() == 'S') {
			$('#formulario #LOG_ATIVO').prop('checked', true);
		} else {
			$('#formulario #LOG_ATIVO').prop('checked', false);
		}
		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
	}
</script>