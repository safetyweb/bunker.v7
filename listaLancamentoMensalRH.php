<?php

//echo "<h5>_".$opcao."</h5>";

$hashLocal = mt_rand();

$cod_mes = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_mes = fnLimpaCampoZero(fnDecode($_REQUEST['COD_MES']));
		$cod_empresa = fnLimpaCampo($_REQUEST['COD_EMPRESA']);

		$cod_usucada = $_SESSION[SYS_COD_USUARIO];

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		if ($opcao != '') {

			//mensagem de retorno
			$msgTipo = 'alert-success';

			switch ($opcao) {


				case 'CAD':



					break;
				case 'ALT':



					break;
				case 'EXC':


					break;
			}
		}
	}
}


//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode($_GET['id']);
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
	}
} else {
	$cod_empresa = 0;
	//fnEscreve('entrou else');
}

if ($cod_mes == "" || $cod_mes == 0) {

	$sqlUltMes = "SELECT COD_MES FROM MES_CAIXA WHERE COD_EMPRESA = $cod_empresa ORDER BY DAT_FIM DESC LIMIT 1";

	$arrayUltMes = mysqli_query(connTemp($cod_empresa, ''), $sqlUltMes);
	$qrUltMes = mysqli_fetch_assoc($arrayUltMes);

	$cod_mes = $qrUltMes[COD_MES];
}

// fnEscreve($cod_mes);

?>

<div class="push30"></div>

<div class="row">

	<div class="col-md-12 margin-bottom-30">
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
							<span class="text-primary"><?php echo $NomePg; ?></span>
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

					<?php
					//menu superior - cliente

					// $abaEmpresa = 1706;						

					// include "abasRH.php";

					?>

					<div class="push30"></div>

					<div class="login-form">

						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

							<fieldset>
								<legend>Dados do Lançamento</legend>

								<div class="row">

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Mês</label>
											<select data-placeholder="Selecione o mês" name="COD_MES" id="COD_MES" class="chosen-select-deselect" style="width:100%;">
												<?php

												$sqlMes = "SELECT COD_MES, MESANO FROM MES_CAIXA
																					WHERE COD_EMPRESA = $cod_empresa
																					ORDER BY DAT_FIM DESC";
												$arrayMes = mysqli_query(connTemp($cod_empresa, ''), $sqlMes);

												while ($qrMes = mysqli_fetch_assoc($arrayMes)) {
												?>

													<option value="<?= fnEncode($qrMes[COD_MES]) ?>"><?= $qrMes[MESANO] ?></option>

												<?php
												}

												?>
											</select>
											<script type="text/javascript">
												$("#COD_MES").val("<?= fnEncode($cod_mes) ?>").trigger("chosen:updated");
											</script>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-2">
										<div class="push20"></div>
										<button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-sm btn-block getBtn"><i class="fa fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
									</div>

								</div>

							</fieldset>

							<div class="push10"></div>

							<input type="hidden" name="opcao" id="opcao" value="">
							<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?= $cod_empresa ?>">
							<input type="hidden" name="CLIENTE_DETALHE" id="CLIENTE_DETALHE" value="">
							<input type="hidden" name="REFRESH_LANCAMENTO" id="REFRESH_LANCAMENTO" value="N">
							<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
							<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

							<div class="push50"></div>

							<table class="table table-bordered table-hover tableSorter">
								<thead>
									<tr>
										<th class="{ sorter: false }" width="40"></th>
										<th>Código</th>
										<th>Nome</th>
										<th>Salário Base</th>
										<th>Salário Líquido</th>
										<th class="{ sorter: false }" width="100"></th>
										<th class="{ sorter: false }"></th>
									</tr>
								</thead>
								<tbody>

									<?php

									$sql = "SELECT CL.COD_CLIENTE, CL.NOM_CLIENTE, 
															(SELECT VAL_LANCAME FROM LANCAMENTO_AUTOMATICO LA 
																WHERE LA.COD_EMPRESA = $cod_empresa 
																AND LA.COD_CLIENTE = CL.COD_CLIENTE
																AND LA.COD_TIPO = 1) AS VAL_SALBASE,
															(SELECT
																   IFNULL(SUM(case when TIP_CREDITO.TIP_OPERACAO ='C' then
																   CAIXA.VAL_CREDITO 
																  END),0) -
																   IFNULL(SUM(case when TIP_CREDITO.TIP_OPERACAO ='D' then
																   CAIXA.VAL_CREDITO 
																  END),0) VAL_LIQUIDO
																
															FROM CAIXA
															LEFT JOIN TIP_CREDITO ON caixa.COD_TIPO=TIP_CREDITO.COD_TIPO
															WHERE CAIXA.COD_CONTRAT=CL.COD_CLIENTE AND 
																  CAIXA.COD_EMPRESA=CL.COD_EMPRESA AND 
																	CAIXA.COD_MES = $cod_mes AND 
																	CAIXA.DAT_EXCLUSA IS NULL AND 
																	CAIXA.COD_EXCLUSA = 0 AND 
																	CAIXA.TIP_LANCAME = 'F') AS SALARIO_LIQUIDO
															FROM CLIENTES CL
															WHERE CL.COD_EMPRESA = $cod_empresa AND CL.LOG_TITULAR = 'S' AND CL.LOG_ESTATUS = 'S'
															ORDER BY CL.NOM_CLIENTE ASC ";

									//fnEscreve($sql);
									//echo($sql);

									$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

									$count = 0;
									while ($qrFunc = mysqli_fetch_assoc($arrayQuery)) {
										$count++;

										echo "
															<tr cod_cliente=" . $qrFunc['COD_CLIENTE'] . ">
															
															  <td class='text-center'><a href='javascript:void(0);' onclick='abreDetail(" . $qrFunc['COD_CLIENTE'] . ")'><i class='fal fa-chevron-right' aria-hidden='true'></i></a></td>
															  <td><small>" . $qrFunc['COD_CLIENTE'] . "</small></td>
															  <td>" . $qrFunc['NOM_CLIENTE'] . "</td>												
															  <td class='text-right'>" . fnValor($qrFunc['VAL_SALBASE'], 2) . "</td>
															  <td class='text-right'><div id='valLiq" . $qrFunc['COD_CLIENTE'] . "'>" . fnValor($qrFunc['SALARIO_LIQUIDO'], 2) . "</div></td>		 										
															  <td></td>
															  <td class='text-center'><a href='javascript:void(0);' id='btnNovo" . $qrFunc['COD_CLIENTE'] . "' class='btn btn-info btn-xs addBox' data-url='action.php?mod=" . fnEncode(1705) . "&id=" . fnEncode($cod_empresa) . "&idc=" . fnEncode($qrFunc['COD_CLIENTE']) . "&idm=" . fnEncode($cod_mes) . "&pop=true' data-title='Cadastro de Lançamento' onclick='$('#CLIENTE_DETALHE').val('" . $qrFunc['COD_CLIENTE'] . "')' ><i class='fal fa-plus' aria-hidden='true'></i></a></td>
															</tr>
															
														  <tr style='display:none; background-color: #fff;' id='abreDetail_" . $qrFunc['COD_CLIENTE'] . "'>
															<td></td>
															<td colspan='11'>
															<div id='mostraDetail_" . $qrFunc['COD_CLIENTE'] . "'>
								
															
															</div>
															</td>
														  </tr>
														  
															";
									}

									?>

								</tbody>

								<tfoot>

									<tr>
										<th colspan="100">
											<a class="btn btn-info btn-sm" href="action.php?mod=<?php echo fnEncode(1739) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idm=<?= fnEncode($cod_mes) ?>&pop=true" target="_blank"> <i class="fal fa-print" aria-hidden="true"></i>&nbsp; Imprimir todos holerites </a>
											&nbsp;
											<div class="btn-group dropdown dropleft">
												<button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
													<span class="fal fa-print"></span>&nbsp; Impressão todos holorites avulsos &nbsp;
													<span class="fas fa-caret-down"></span>
												</button>
												<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
													<?php
													$sql = "SELECT COD_TIPO,DES_TIPO 
																			FROM TIP_CREDITO
																			WHERE LOG_AVULSO='S' 
																				  AND COD_EMPRESA = $cod_empresa
																				  AND LOG_LANCAME='F' 
																			ORDER BY DES_TIPO";
													//fnEscreve($sql);
													//echo $sql;
													$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

													while ($qrListaTipoAvulso = mysqli_fetch_assoc($arrayQuery)) {
													?>
														<li><a target="_blank" href="action.php?mod=<?php echo fnEncode(1743) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idm=<?= fnEncode($cod_mes) ?>&idt=<?= fnEncode($qrListaTipoAvulso['COD_TIPO']) ?>&pop=true"><?= ucfirst(mb_strtolower($qrListaTipoAvulso['DES_TIPO'], "utf-8")); ?></a></li>
													<?php
													}
													?>

												</ul>
											</div>

										</th>
									</tr>

								</tfoot>

							</table>

							<div class="push10"></div>

						</form>


					</div>

				</div>
				</div>
				<!-- fim Portlet -->
			</div>

	</div>

	<!-- modal -->
	<div class="modal fade" id="popModal" tabindex='-1'>
		<div class="modal-dialog" style="">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title"></h4>
				</div>
				<div class="modal-body">
					<iframe frameborder="0" style="width: 100%; height: 80%"></iframe>
				</div>
			</div><!-- /.modal-content -->
		</div>
	</div>

		<div class="push20"></div>

		<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
		<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
		<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
		<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />

		<script type="text/javascript">
			$(function() {

				$('.datePicker').datetimepicker({
					format: 'DD/MM/YYYY'
				}).on('changeDate', function(e) {
					$(this).datetimepicker('hide');
				});

				$("#DAT_INI_GRP").on("dp.change", function(e) {
					$('#DAT_FIM_GRP').data("DateTimePicker").minDate(e.date);
				});

				$("#DAT_FIM_GRP").on("dp.change", function(e) {
					$('#DAT_INI_GRP').data("DateTimePicker").maxDate(e.date);
				});

				var mes = "";

				//chosen
				$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
				$('#formulario').validator();

				//modal close
				$('.modal').on('hidden.bs.modal', function() {
					// alert('fecha');

					if ($("#REFRESH_LANCAMENTO").val() == 'S') {

						$('#abreDetail_' + $("#CLIENTE_DETALHE").val()).hide();

						refreshCaixa($("#CLIENTE_DETALHE").val());

						$("#CLIENTE_DETALHE").val('');
						$("#REFRESH_LANCAMENTO").val('N');

					}

				});

				//modal close
				$('.modal').on('hidden.bs.modal', function() {
					//reloadPage(current_page);
					//alert("fechou...");
				});


			});

			function abreDetail(idCli) {
				refreshCaixa(idCli);
			}

			function lancarMes(idCli) {
				$.ajax({
					type: "POST",
					url: "ajxListaLancamentoMensalRH.do?OPCAO=lancarMes",
					data: {
						COD_EMPRESA: "<?= fnEncode($cod_empresa) ?>",
						COD_MES: "<?= fnEncode($cod_mes) ?>",
						COD_CLIENTE: idCli
					},
					success: function(data) {
						// console.log(data); 
						$('#abreDetail_' + idCli).hide();
						refreshCaixa(data);
					}
				});
			}

			function refreshSalario(idCli) {
				$.ajax({
					type: "POST",
					url: "ajxListaLancamentoMensalRH.do?OPCAO=salario",
					data: {
						COD_EMPRESA: "<?= fnEncode($cod_empresa) ?>",
						COD_MES: "<?= fnEncode($cod_mes) ?>",
						COD_CLIENTE: idCli
					},
					success: function(data) {
						// console.log(data); 
						$('#abreDetail_' + idCli).hide();
						refreshCaixa(data);
					}
				});
			}

			function refreshCaixa(idCli) {
				var idItem = $('#abreDetail_' + idCli);

				if (!idItem.is(':visible')) {
					$.ajax({
						type: "POST",
						url: "ajxListaLancamentoMensalRH.do?OPCAO=expandir",
						data: {
							COD_EMPRESA: "<?= fnEncode($cod_empresa) ?>",
							COD_MES: "<?= fnEncode($cod_mes) ?>",
							COD_CLIENTE: idCli
						},
						beforeSend: function() {
							$("#mostraDetail_" + idCli).html('<div class="loading" style="width: 100%;"></div>');
						},
						success: function(data) {
							$("#mostraDetail_" + idCli).html(data);
						},
						error: function() {
							$("#mostraDetail_" + idCli).html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
						}
					});

					idItem.show();

					$('[cod_cliente="' + idCli + '"]').find($(".fal")).removeClass('fa-chevron-right').addClass('fa-chevron-down');
				} else {
					idItem.hide();
					$('[cod_cliente="' + idCli + '"]').find($(".fal")).removeClass('fa-chevron-down').addClass('fa-chevron-right');
				}
			}

			function retornaForm(index) {
				$("#formulario #COD_MES").val($("#ret_COD_MES_" + index).val());
				$("#formulario #DAT_INI").val($("#ret_DAT_INI_" + index).val());
				$("#formulario #DAT_FIM").val($("#ret_DAT_FIM_" + index).val());
				$('#formulario').validator('validate');
				$("#formulario #hHabilitado").val('S');
			}
		</script>