<?php

$hashLocal = mt_rand();

$conn = conntemp($cod_empresa,"");
$adm = $connAdm->connAdm();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_empresa = fnLimpaCampo($_REQUEST['COD_EMPRESA']);
		$cod_tpfiltro = fnLimpaCampo($_REQUEST['COD_TPFILTRO']);
		$des_tpfiltro = fnLimpaCampo($_REQUEST['DES_TPFILTRO']);
		$num_ordenac = fnLimpaCampo($_REQUEST['NUM_ORDENAC']);
		if (empty($_REQUEST['LOG_REQUIRED'])) {
			$log_required = 'N';
		} else {
			$log_required = $_REQUEST['LOG_REQUIRED'];
		}

		// fnEscreve($des_tpfiltro);

		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

		$nom_usuario = $_SESSION["SYS_NOM_USUARIO"];
		$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$MODULO = $_GET['mod'];
		$COD_MODULO = fndecode($_GET['mod']);


		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		// fnEscreve($opcao);

		if ($opcao != '') {

			//mensagem de retorno
			switch ($opcao) {
				case 'CAD':

					$sqlInsert = "INSERT INTO TIPO_FILTRO_ATENDIMENTO(
											COD_EMPRESA,
											DES_TPFILTRO,
											LOG_REQUIRED,
											COD_USUCADA
											) VALUES(
											$cod_empresa,
											'$des_tpfiltro',
											'$log_required',
											$cod_usucada
											)";
					//fnEscreve($sql);
					$arrayInsert = mysqli_query($conn, $sqlInsert);

                    if (!$arrayInsert) {

                        $cod_erro = Log_error_comand($adm,$conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sqlInsert,$nom_usuario);
                    }

                    if ($cod_erro == 0 || $cod_erro ==  "") {
                        $msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
                    } else {
                        $msgRetorno = "Não foi possível cadastrar o registro : $cod_erro";
                    }

					// if ($cod_empresa == 136) {
					// 	$sqlInsert = "INSERT INTO CLIENTE_FILTROS(
					// 							COD_EMPRESA, 
					// 							COD_TPFILTRO, 
					// 							COD_FILTRO,
					// 							COD_CLIENTE, 
					// 							COD_USUCADA
					// 							) 
					// 							SELECT $cod_empresa,
					// 								(SELECT MAX(COD_TPFILTRO) FROM TIPO_FILTRO 
					// 								WHERE COD_EMPRESA = $cod_empresa 
					// 								AND COD_USUCADA = $cod_usucada),
					// 							0,
					// 							COD_CLIENTE,
					// 							$cod_usucada 
					// 							FROM CLIENTES WHERE COD_EMPRESA = $cod_empresa";
					// 	$arrayInsert = mysqli_query($conn, $sqlInsert);

					// 	if (!$arrayInsert) {
	
					// 		$cod_erro = Log_error_comand($adm,$conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sqlInsert,$nom_usuario);
					// 	}
	
					// 	if ($cod_erro == 0 || $cod_erro ==  "") {
					// 		$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					// 	} else {
					// 		$msgRetorno = "Não foi possível cadastrar o registro : $cod_erro";
					// 	}
					// }

?>
					<script>
						try {
							parent.$('#REFRESH_FILTRO').val("S");
						} catch (err) {}
					</script>
				<?php

					break;
				case 'ALT':

					$sqlUpdate = "UPDATE TIPO_FILTRO_ATENDIMENTO SET
								DES_TPFILTRO='$des_tpfiltro',
								LOG_REQUIRED='$log_required'
								WHERE COD_TPFILTRO = $cod_tpfiltro";
					//fnEscreve($sql);
					$arrayUpdate = mysqli_query($conn, $sqlUpdate);

					if (!$arrayUpdate) {
 
					$cod_erro = Log_error_comand($adm,$conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sqlUpdate,$nom_usuario);
					}
					//fnEscreve($cod_erro);
 
					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível alterar o registro : $cod_erro";
					}

				?>
					<script>
						try {
							parent.$('#REFRESH_FILTRO').val("S");
						} catch (err) {}
					</script>
<?php

					break;
				case 'EXC':
					$sqlExc = "DELETE FROM TIPO_FILTRO_ATENDIMENTO
								WHERE COD_TPFILTRO = $cod_tpfiltro;

								-- DELETE FROM FILTROS_CLIENTE 
								-- WHERE COD_TPFILTRO = $cod_tpfiltro;
								
								-- DELETE FROM CLIENTE_FILTROS
								-- WHERE COD_TPFILTRO = $cod_tpfiltro;";
					//fnEscreve($sql);

					$arrayExc = mysqli_query($conn,$sqlExc);
					if (!$arrayExc) {

                        $cod_erro = Log_error_comand($adm,$conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sqlExc,$nom_usuario);
                    }
                    
                    if($cod_erro == 0 || $cod_erro == "") {
                        $msgRetorno = "Registro Excluido com sucesso";
                    }else{
                        $msgRetorno = "Falha na Exclusão:$cod_erro";
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
if (is_numeric(fnLimpacampo(fnDecode($_GET['id']))) && fnDecode($_GET['id']) != 0) {
	//busca dados da empresa
	$cod_empresa = fnDecode($_GET['id']);
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($adm, $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
	}
} else {
	$cod_empresa = 7;
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($adm, $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
	}

	//fnEscreve('entrou else');
}

?>

<div class="push30"></div>

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
							<i class="fal fa-terminal"></i>
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
					<?php }

					if ($popUp != 'true') {
						
						$abaInfoAtendimento = fnDecode($_GET['mod']); 
						include "abasAtendimentoConfig.php";

					}

					?>

					<div class="push30"></div>
					<div class="push10"></div>

					<?php
					// $abaFiltro = fnDecode($_GET['mod']);
					// include "abasFiltrosDinamicos.php";
					?>

					<div class="push30"></div>

					<div class="login-form">

						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

							<fieldset>
								<legend>Dados do Tipo</legend>

								<div class="row">

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Código</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_TPFILTRO" id="COD_TPFILTRO" value="">
										</div>


									</div>

									<div class="col-md-5">
										<div class="form-group">
											<label for="NOM_FOLLOW" class="control-label required">Tipo de Filtro</label>
											<input type="text" class="form-control input-sm" name="DES_TPFILTRO" id="DES_TPFILTRO" maxlength="100" required>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Campo Obrigatório</label>
											<div class="push5"></div>
											<label class="switch">
												<input type="checkbox" name="LOG_REQUIRED" id="LOG_REQUIRED" class="switch" value="S">
												<span></span>
											</label>
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
							<input type="hidden" name="COD_EMPRESA" value="<?= $cod_empresa ?>">
							<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
							<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">
							<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">

							<div class="push5"></div>

						</form>

						<div class="push50"></div>

						<div id="divId_sub">
						</div>

						<div class="no-more-tables">

							<form name="formLista">

								<table class="table table-bordered table-striped table-hover table-sortable tableSorter">
									<thead>
										<tr>
											<th width="40"></th>
											<th width="40"></th>
											<th>Código</th>
											<th>Tipo de Filtro</th>
											<th width="40">Obrigatório</th>
										</tr>
									</thead>
									<tbody>

										<?php

										$sql = "SELECT COD_TPFILTRO, DES_TPFILTRO, LOG_REQUIRED FROM TIPO_FILTRO_ATENDIMENTO WHERE COD_EMPRESA = $cod_empresa ORDER BY NUM_ORDENAC";
										$arrayQuery = mysqli_query($conn, $sql);

										$count = 0;
										while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery)) {
											$count++;

											if ($qrBuscaModulos['LOG_REQUIRED'] == "S") {
												$obriga = "<span class='fal fa-check text-success'></span>";
											} else {
												$obriga = "<span class='fal fa-times text-danger'></span>";
											}

											echo "
													<tr>
														<td align='center'><span class='fal fa-equals grabbable' data-id='" . $qrBuscaModulos['COD_TPFILTRO'] . "'></span></td>
														<td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></th>
														<td>" . $qrBuscaModulos['COD_TPFILTRO'] . "</td>
														<td>" . $qrBuscaModulos['DES_TPFILTRO'] . "</td>
														<td class='text-center'>" . $obriga . "</td>
													</tr>
													<input type='hidden' id='ret_COD_TPFILTRO_" . $count . "' value='" . $qrBuscaModulos['COD_TPFILTRO'] . "'>
													<input type='hidden' id='ret_DES_TPFILTRO_" . $count . "' value='" . $qrBuscaModulos['DES_TPFILTRO'] . "'>
													<input type='hidden' id='ret_LOG_REQUIRED_" . $count . "' value='" . $qrBuscaModulos['LOG_REQUIRED'] . "'>
													<input type='hidden' id='ret_NUM_ORDENAC_" . $count . "' value='" . $qrBuscaModulos['NUM_ORDENAC'] . "'>
													";
										}

										?>

									</tbody>
								</table>

							</form>

						</div>

					</div>

				</div>

				</div>

			</div>

	</div>

</div>

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
				execOrdenacao(arrayOrdem, 8, '<?= $cod_empresa ?>');

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
	$(function() {

		//arrastar 
		$('.grabbable').on('change', function(e) {
			//console.log(e.icon);
			$("#DES_ICONE").val(e.icon);
		});

		$(".grabbable").click(function() {
			$(this).parent().addClass('selected').siblings().removeClass('selected');

		});

		$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
		$('#formulario').validator();

	});

	function retornaForm(index) {
		$("#formulario #COD_TPFILTRO").val($("#ret_COD_TPFILTRO_" + index).val());
		$("#formulario #DES_TPFILTRO").val($("#ret_DES_TPFILTRO_" + index).val());
		if ($("#ret_LOG_REQUIRED_" + index).val() == 'S') {
			$('#formulario #LOG_REQUIRED').prop('checked', true);
		} else {
			$('#formulario #LOG_REQUIRED').prop('checked', false);
		}
		$("#formulario #NUM_ORDENAC").val($("#ret_NUM_ORDENAC_" + index).val());
		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
	}
</script>