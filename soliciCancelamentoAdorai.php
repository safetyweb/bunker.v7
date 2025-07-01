<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$opcao = "";
$hoje = "";
$ontem = "";
$hashLocal = "";
$msgRetorno = "";
$msgTipo = "";
$dat_ini = "";
$dat_fim = "";
$cod_usucada = "";
$hHabilitado = "";
$hashForm = "";
$cod_mes = "";
$popUp = "";
$abaAdorai = "";
$abaManutencaoAdorai = "";
$abaUsuario = "";
$andDat = "";
$array = [];
$qrFunc = "";


//echo "<h5>_".$opcao."</h5>";

//inicialização de variáveis
$hoje = fnFormatDate(date("Y-m-d"));
$ontem = fnFormatDate(date('Y-m-d', strtotime($ontem . '-1 days')));

$hashLocal = mt_rand();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_empresa = fnLimpaCampo(@$_REQUEST['COD_EMPRESA']);
		$dat_ini = fnDataSql(@$_POST['DAT_INI']);
		$dat_fim = fnDataSql(@$_POST['DAT_FIM']);

		$cod_usucada = $_SESSION['SYS_COD_USUARIO'];

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];
	}
}

$cod_empresa = 274;

//inicialização das variáveis - default	
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
	$dat_ini = fnDataSql($ontem);
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {

	$dat_fim = fnDataSql($hoje);
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
					$abaAdorai = 2006;
					include "abasAdorai.php";

					$abaManutencaoAdorai = 2019;
					//echo $abaUsuario;

					//se não for sistema de campanhas

					echo ('<div class="push20"></div>');
					include "abasSistemaAdorai.php";
					?>

					<div class="push30"></div>

					<div class="login-form">

						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

							<fieldset>
								<legend>Dados do Lançamento</legend>

								<div class="row">

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Data Inicial</label>

											<div class="input-group date datePicker" id="DAT_INI_GRP">
												<input type='text' class="form-control input-sm data" name="DAT_INI" id="DAT_INI" value="<?= fnDataShort($dat_ini) ?>" required />
												<span class="input-group-addon">
													<span class="glyphicon glyphicon-calendar"></span>
												</span>
											</div>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Data Final</label>

											<div class="input-group date datePicker" id="DAT_FIM_GRP">
												<input type='text' class="form-control input-sm data" name="DAT_FIM" id="DAT_FIM" value="<?= fnDataShort($dat_fim) ?>" required />
												<span class="input-group-addon">
													<span class="glyphicon glyphicon-calendar"></span>
												</span>
											</div>
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
							<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
							<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

							<div class="push50"></div>

							<table class="table table-bordered table-hover tableSorter">
								<thead>
									<tr>
										<th class="{ sorter: false }"></th>
										<th class="{ sorter: false }"></th>
										<th>Cód. Contrato</th>
										<th>Nome</th>
										<th>Cpf</th>
										<th>Email</th>
										<th>Telefone</th>
										<th class='text-right'>Valor Pago</th>
										<th class='text-right'>Forma Pagamento</th>
										<th class='text-right'>Dat. Reserva</th>
										<th class='text-right'>Motiv. Cancelamento</th>
										<th>Status Paga.</th>
										<th class="{ sorter: false }"></th>
										<th class="{ sorter: false }"></th>
									</tr>
								</thead>
								<tbody>

									<?php
									// if($dat_ini !="1969-12-31" && $dat_fim != "1969-12-31"){
									// 	$andDat = "WHERE a.DAT_CANCELA BETWEEN '$dat_ini' AND '$dat_fim'";
									// }

									$sql = "SELECT b.COD_PEDIDO,
												a.COD_STATUS,
												a.COD_CANCELAMENTO,
												b.CPF,
												b.NOME,
												b.EMAIL,
												b.TELEFONE,
												b.DAT_CADASTR AS DAT_PEDIDO,
												a.DAT_CADASTR AS DAT_CANCELAME,
												c.ABV_STATUSPAG,
												a.ID_RESERVA,
												a.DES_OBSERVA,
												(
													SELECT SUM(CX.val_credito)
													FROM caixa AS cx 
													INNER JOIN adorai_pedido AS p ON cx.cod_contrat = p.COD_PEDIDO
													INNER JOIN TIP_CREDITO AS TC ON TC.COD_TIPO = cx.COD_TIPO
													WHERE p.COD_EMPRESA = 274 
													AND p.COD_PEDIDO = a.COD_PEDIDO
													AND cx.cod_contrat = a.COD_PEDIDO
													AND TC.TIP_OPERACAO = 'C'
													) AS tot_val_credito,
												e.ABV_FORMAPAG
												FROM adorai_cancelamentos a
												LEFT JOIN adorai_pedido b ON a.ID_RESERVA=b.ID_RESERVA
												LEFT JOIN adorai_statuspag c ON c.COD_STATUSPAG=a.COD_STATUS
												LEFT JOIN adorai_formapag e ON e.COD_FORMAPAG=b.COD_FORMAPAG
												GROUP BY A.COD_PEDIDO
												ORDER BY A.COD_PEDIDO
									";
									$array = mysqli_query(connTemp($cod_empresa, ''), $sql);

									$count = 0;
									while ($qrFunc = mysqli_fetch_assoc($array)) {
										$count++;
									?>
										<tr cod_cliente="<?php echo $qrFunc['ID_RESERVA']; ?>">
											<td class='text-center'><a href='javascript:void(0);' onclick='abreDetail(<?php echo $qrFunc['ID_RESERVA']; ?>)'><i class='fal fa-chevron-right' aria-hidden='true'></i></a></td>
											<td></td>
											<td><?php echo $qrFunc['COD_PEDIDO']; ?></td>
											<td><?php echo $qrFunc['NOME']; ?></td>
											<td><?php echo $qrFunc['CPF']; ?></td>
											<td><?php echo $qrFunc['EMAIL']; ?></td>
											<td><?php echo fnmasktelefone($qrFunc['TELEFONE']); ?></td>
											<td class='text-right'><?php echo fnValor($qrFunc['tot_val_credito'], 2); ?></td>
											<td class='text-right'><?php echo $qrFunc['ABV_FORMAPAG']; ?></td>
											<td class='text-right'><?php echo fnDataShort($qrFunc['DAT_PEDIDO']); ?></td>
											<td class='text-right'><?php echo $qrFunc['DES_OBSERVA']; ?></td>
											<td><?php echo $qrFunc['ABV_STATUSPAG']; ?></td>
											<?php if ($qrFunc['COD_STATUS'] != 4) { ?>
												<td><a href='javascript:void(0);' id='btnNovo<?php echo $qrFunc['ID_RESERVA']; ?>' class='btn btn-info btn-xs addBox' data-url='action.do?mod=<?php echo fnEncode(2031); ?>&id=<?php echo fnEncode(274); ?>&idr=<?php echo fnEncode($qrFunc['ID_RESERVA']); ?>&pop=true' data-title='Cadastro de Lançamento'><i class='fal fa-plus' aria-hidden='true'></i></a></td>
											<?php } else { ?>
												<td></td>
											<?php } ?>
											<td></td>
										</tr>
										<tr style='display:none; background-color: #fff;' id='abreDetail_<?php echo $qrFunc['ID_RESERVA']; ?>'>
											<td></td>
											<td colspan='11'>
												<div id='mostraDetail_<?php echo $qrFunc['ID_RESERVA']; ?>'></div>
											</td>
										</tr>
									<?php

									}

									?>

								</tbody>

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

			//chosen
			$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
			$('#formulario').validator();

			//modal close
			$('.modal').on('hidden.bs.modal', function() {
				//reloadPage(current_page);
				//alert("fechou...");
			});


		});

		function abreDetail(idCli) {
			refreshCaixa(idCli);
		}
	</script>