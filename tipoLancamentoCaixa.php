<?php

//echo "<h5>_".$opcao."</h5>";                  

$hashLocal = mt_rand();

$modulo = fnDecode($_GET['mod']);
$cod_modulo = fnDecode($_GET['mod']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
		$cod_tipo = fnLimpaCampoZero($_REQUEST['COD_TIPO']);
		$cod_conta = fnLimpaCampoZero($_REQUEST['COD_CONTA']);
		$des_tipo = fnLimpaCampo($_REQUEST['DES_TIPO']);
		$abv_tipo = fnLimpaCampo($_REQUEST['ABV_TIPO']);
		$tip_operacao = fnLimpaCampo($_REQUEST['TIP_OPERACAO']);
		$log_lancame = fnLimpaCampo($_REQUEST['LOG_LANCAME']);
		if (empty($_REQUEST['LOG_AVULSO'])) {
			$log_avulso = 'N';
		} else {
			$log_avulso = $_REQUEST['LOG_AVULSO'];
		}
		if (empty($_REQUEST['LOG_CONTABILIZA'])) {
			$log_contabiliza = 'N';
		} else {
			$log_contabiliza = $_REQUEST['LOG_CONTABILIZA'];
		}
		if (empty($_REQUEST['LOG_AUTOMATICO'])) {
			$log_automatico = 'N';
		} else {
			$log_automatico = $_REQUEST['LOG_AUTOMATICO'];
		}

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		if ($cod_empresa == 274) {
			$andConta = "COD_CONTA,";
			$conta = $cod_conta;
			$updateConta = "COD_CONTA = $cod_conta,";
		}

		if ($opcao != '') {

			if ($opcao == 'CAD') {

				$sql = "INSERT INTO TIP_CREDITO(
							COD_EMPRESA,
							DES_TIPO,
							ABV_TIPO,
							TIP_OPERACAO,
							LOG_LANCAME,
							LOG_AVULSO,
							$andConta
							LOG_AUTOMATICO
							) VALUES(
							$cod_empresa,
							'$des_tipo',
							'$abv_tipo',
							'$tip_operacao',
							'$log_lancame',
							'$log_avulso',
							'$conta',
							'$log_automatico'
							)";

				//fnEscreve($sql);
				mysqli_query(connTemp($cod_empresa, ''), $sql);
				//mysqli_query(connTemp($cod_empresa,""),$sql);

			} elseif ($opcao == 'EXC') {

				//$sql = "DELETE FROM TIP_CREDITO WHERE COD_TIPO = $cod_tipo AND COD_EMPRESA = $cod_empresa";
				$usuExc  = $_SESSION["SYS_COD_USUARIO"];
				$dataExc = date("Y-m-d H:i:s");

				$sql = "UPDATE TIP_CREDITO SET 
							COD_EXCLUSA = '$usuExc',
							DAT_EXCLUSA = '$dataExc'
							WHERE COD_TIPO= $cod_tipo
							AND COD_EMPRESA = $cod_empresa
							";

				//echo $sql;	

				mysqli_query(connTemp($cod_empresa, ''), $sql);
			} else {

				$usuAlt  = $_SESSION["SYS_COD_USUARIO"];
				$dataAlt = date("Y-m-d H:i:s");

				$sql = "UPDATE TIP_CREDITO SET 
							DES_TIPO = '$des_tipo',
							ABV_TIPO = '$abv_tipo',
							TIP_OPERACAO = '$tip_operacao',
							LOG_AVULSO = '$log_avulso',
							LOG_AUTOMATICO = '$log_automatico',
							LOG_CONTABILIZA = '$log_contabiliza',
							COD_ALTERAC = '$usuAlt',
							$updateConta
							DAT_ALTERAC = '$dataAlt'

							WHERE COD_TIPO = $cod_tipo
							AND COD_EMPRESA = $cod_empresa
							";

				//echo $sql;			

				mysqli_query(connTemp($cod_empresa, ''), $sql);
				//fnTestesql($connAdm->connAdm(), $sql);

			}

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
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode($_GET['id']);
	$sql = "SELECT COD_EMPRESA, NOM_EMPRESA FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
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
					<i class="glyphicon glyphicon-calendar"></i>
					<span class="text-primary"><?php echo $NomePg; ?></span>
				</div>

				<?php
				//$formBack = "1019";
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

				<?php
				if ($modulo != 2022) {
					$abaEmpresa = 1704;
					include "abasEmpresas.php";
				} else if ($modulo == 2022) {
					$abaEmpresa = 2022;

					$abaAdorai = 2006;
					include "abasAdorai.php";

					// 
					echo ('<div class="push20"></div>');
					$abaManutencaoAdorai = 2019;
					include "abasSistemaAdorai.php";
				}
				//menu abas


				//echo $cod_modulo;

				//tipo de lançamento	
				switch ($cod_modulo) {
					case 1704: //folha de pagamento
						$tip_lancame = "F";
						$sql_lancame = "AND LOG_LANCAME = 'F' ";
						//include "abasRH.php"; 
						break;
					case 1718: //bonificação
						$tip_lancame = "B";
						$sql_lancame = " ";
						break;
				}


				?>

				<div class="push30"></div>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Dados Gerais</legend>

							<div class="row">
								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Código</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_TIPO" id="COD_TIPO" value="">
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Descrição</label>
										<input type="text" class="form-control input-sm" name="DES_TIPO" id="DES_TIPO" maxlength="50" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>


								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Abreviação</label>
										<input type="text" class="form-control input-sm" name="ABV_TIPO" id="ABV_TIPO" maxlength="20" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Tipo</label>
										<select data-placeholder="Selecione o tipo" name="TIP_OPERACAO" id="TIP_OPERACAO" class="chosen-select-deselect" style="width:100%;">
											<option value=""></option>
											<option value="C">Crédito</option>
											<option value="D">Débito</option>
										</select>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<?php if ($cod_empresa == 274) { ?>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Conta Bancária Preferencial</label>
											<select data-placeholder="Selecione o status" name="COD_CONTA" id="COD_CONTA" class="chosen-select-deselect" required>
												<option value=""></option>
												<?php
												$sql = "SELECT * FROM CONTABANCARIA WHERE COD_EMPRESA = $cod_empresa ORDER BY NOM_BANCO";
												$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

												while ($qrStatuspag = mysqli_fetch_assoc($arrayQuery)) {
												?>
													<option value="<?= $qrStatuspag['COD_CONTA'] ?>"><?= $qrStatuspag['NOM_BANCO'] ?></option>
												<?php
												}
												?>
											</select>
											<div class="help-block with-errors"></div>
										</div>
									</div>

								<?php } ?>

								<div class="push10"></div>
								<div class="col-md-2"></div>

								<?php if ($cod_modulo == 1718) { ?>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Não Contabilizar</label>
											<div class="push5"></div>
											<label class="switch switch-small">
												<input type="checkbox" name="LOG_CONTABILIZA" id="LOG_CONTABILIZA" class="switch" value="S">
												<span></span>
											</label>
										</div>
									</div>

								<?php } else { ?>

									<input type="hidden" name="LOG_CONTABILIZA" id="LOG_CONTABILIZA" value="S">

								<?php }
								if ($modulo != 2022) {
								?>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Imprimir avulso</label>
											<div class="push5"></div>
											<label class="switch switch-small">
												<input type="checkbox" name="LOG_AVULSO" id="LOG_AVULSO" class="switch" value="S">
												<span></span>
											</label>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Lança Automaticamente</label>
											<div class="push5"></div>
											<label class="switch switch-small">
												<input type="checkbox" name="LOG_AUTOMATICO" id="LOG_AUTOMATICO" class="switch" value="S">
												<span></span>
											</label>
										</div>
									</div>
								<?php
								} else {
								?>
									<input type="hidden" name="LOG_AVULSO" id="LOG_AVULSO" value="">
									<input type="hidden" name="LOG_AUTOMATICO" id="LOG_AUTOMATICO" value="">
								<?php
								}
								?>
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

						<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">
						<input type="hidden" name="LOG_LANCAME" id="LOG_LANCAME" value="<?php echo $tip_lancame; ?>">

					</form>

					<div class="push5"></div>

					<div class="push50"></div>

					<div class="col-lg-12">

						<div class="no-more-tables">

							<form name="formLista">

								<table class="table table-bordered table-striped table-hover">
									<thead>
										<tr>
											<th width="40"></th>
											<th>Código</th>
											<th>Descrição</th>
											<th>Abreviação</th>
											<th>Tipo</th>
											<?php if ($cod_empresa == 274) { ?>
												<th>Conta Preferencial</th>
											<?php } ?>
											<?php if ($cod_modulo == 1718) { ?>
												<th class="text-center">Não Contabiliza</th>
											<?php }
											if ($modulo != 2022) {
											?>
												<th class="text-center">Gera Automático</th>
												<th class="text-center">Imprimir Avulso</th>
											<?php
											}
											?>
										</tr>
									</thead>

									<tbody>
										<?php

										if ($cod_empresa != 274) {
											$sql = "SELECT * FROM TIP_CREDITO WHERE COD_EMPRESA = $cod_empresa $sql_lancame AND COD_EXCLUSA = 0 ";
										} else {
											$sql = "SELECT TC.*, CB.NOM_BANCO FROM TIP_CREDITO AS TC 
													LEFT JOIN CONTABANCARIA AS CB ON CB.COD_CONTA = TC.COD_CONTA
													WHERE TC.COD_EMPRESA = $cod_empresa $sql_lancame AND TC.COD_EXCLUSA = 0 ";
										}
										$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

										$count = 0;
										while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery)) {
											$count++;

											if ($qrBuscaModulos['TIP_OPERACAO'] == 'C') {
												$tipoLanca = "Crédito";
											} else {
												$tipoLanca = "Débito";
											}

											if ($qrBuscaModulos['LOG_CONTABILIZA'] == "S") {
												$mostraContabil = "<span class='fal fa-check'></span>";
											} else {
												$mostraContabil = "";
											}

											if ($qrBuscaModulos['LOG_AUTOMATICO'] == "S") {
												$mostraAuto = "<span class='fal fa-check'></span>";
											} else {
												$mostraAuto = "";
											}

											if ($qrBuscaModulos['LOG_AVULSO'] == "S") {
												$mostraAvulso = "<span class='fal fa-check'></span>";
											} else {
												$mostraAvulso = "";
											}

										?>
											<tr>
												<td><input type='radio' name='radio1' onclick='retornaForm(<?php echo $count; ?>)'></td>
												<td><?php echo $qrBuscaModulos['COD_TIPO']; ?></td>
												<td><?php echo $qrBuscaModulos['DES_TIPO']; ?></td>
												<td><?php echo $qrBuscaModulos['ABV_TIPO']; ?></td>
												<td><?= $tipoLanca ?></td>
												<?php if ($cod_empresa == 274) { ?>
													<td><?php echo $qrBuscaModulos['NOM_BANCO']; ?></td>
												<?php } ?>
												<?php if ($cod_modulo == 1718) { ?>
													<td class="text-center"><?php echo $mostraContabil; ?></td>
												<?php }
												if ($modulo != 2022) {
												?>
													<td class="text-center"><?php echo $mostraAuto; ?></td>
													<td class="text-center"><?php echo $mostraAvulso; ?></td>
												<?php
												}
												?>
											</tr>

											<input type='hidden' id='ret_COD_TIPO_<?php echo $count; ?>' value='<?php echo $qrBuscaModulos['COD_TIPO']; ?>'>
											<input type='hidden' id='ret_DES_TIPO_<?php echo $count; ?>' value='<?php echo $qrBuscaModulos['DES_TIPO']; ?>'>
											<input type='hidden' id='ret_ABV_TIPO_<?php echo $count; ?>' value='<?php echo $qrBuscaModulos['ABV_TIPO']; ?>'>
											<input type='hidden' id='ret_TIP_OPERACAO_<?php echo $count; ?>' value='<?php echo $qrBuscaModulos['TIP_OPERACAO']; ?>'>
											<?php
											if ($cod_empresa == 274) {
											?>
												<input type='hidden' id='ret_COD_CONTA_<?php echo $count; ?>' value='<?php echo $qrBuscaModulos['COD_CONTA']; ?>'>
											<?php
											}
											?>
											<input type='hidden' id='ret_TIP_OPERACAO_<?php echo $count; ?>' value='<?php echo $qrBuscaModulos['TIP_OPERACAO']; ?>'>
											<?php
											if ($modulo != 2022) {
											?>
												<input type='hidden' id='ret_LOG_AVULSO_<?php echo $count; ?>' value='<?php echo $qrBuscaModulos['LOG_AVULSO']; ?>'>
												<input type='hidden' id='ret_LOG_AUTOMATICO_<?php echo $count; ?>' value='<?php echo $qrBuscaModulos['LOG_AUTOMATICO']; ?>'>
											<?php
											}
											?>
											<input type='hidden' id='ret_LOG_CONTABILIZA_<?php echo $count; ?>' value='<?php echo $qrBuscaModulos['LOG_CONTABILIZA']; ?>'>
										<?php
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
<link rel="stylesheet" href="js/plugins/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css" />
<script type="text/javascript" src="js/bootstrap-iconpicker-iconset-fa5.js"></script>
<script type="text/javascript" src="js/plugins/bootstrap-iconpicker/js/bootstrap-iconpicker.min.js"></script>

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
						Ids = Ids + $(this).children().find('span.glyphicon').attr('data-id') + ",";
					}
				});

				//update ordenação
				//console.log(Ids.substring(0,(Ids.length-1)));

				var arrayOrdem = Ids.substring(0, (Ids.length - 1));
				//alert(arrayOrdem);
				execOrdenacao(arrayOrdem, 5);

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


	});

	function retornaForm(index) {
		$("#formulario #COD_TIPO").val($("#ret_COD_TIPO_" + index).val());
		$("#formulario #DES_TIPO").val($("#ret_DES_TIPO_" + index).val());
		$("#formulario #ABV_TIPO").val($("#ret_ABV_TIPO_" + index).val());
		$("#formulario #TIP_OPERACAO").val($("#ret_TIP_OPERACAO_" + index).val()).trigger("chosen:updated");
		$("#formulario #COD_CONTA").val($("#ret_COD_CONTA_" + index).val()).trigger("chosen:updated");
		$('#formulario').validator('validate');
		if ($("#ret_LOG_AVULSO_" + index).val() == 'S') {
			$('#formulario #LOG_AVULSO').prop('checked', true);
		} else {
			$('#formulario #LOG_AVULSO').prop('checked', false);
		}
		if ($("#ret_LOG_AUTOMATICO_" + index).val() == 'S') {
			$('#formulario #LOG_AUTOMATICO').prop('checked', true);
		} else {
			$('#formulario #LOG_AUTOMATICO').prop('checked', false);
		}

		<?php if ($cod_modulo == 1718) { ?>
			if ($("#ret_LOG_CONTABILIZA_" + index).val() == 'S') {
				$('#formulario #LOG_CONTABILIZA').prop('checked', true);
			} else {
				$('#formulario #LOG_CONTABILIZA').prop('checked', false);
			}
		<?php } else { ?>
			$("#formulario #LOG_CONTABILIZA").val($("#ret_LOG_CONTABILIZA_" + index).val());
		<?php }  ?>

		$("#formulario #hHabilitado").val('S');
	}
</script>