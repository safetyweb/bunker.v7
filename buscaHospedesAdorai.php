<?php

//echo fnDebug('true');

$hashLocal = mt_rand();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	//$cod_empresa = fnLimpacampo(fnDecode($_REQUEST['COD_EMPRESA']));
	//$cod_empresaCode = fnLimpacampo($_REQUEST['COD_EMPRESA']);
	$cod_hospede  = fnLimpacampo($_REQUEST['COD_HOSPEDE']);
	$cod_pedido  = fnLimpacampo($_REQUEST['COD_PEDIDO']);
	$num_cgcecpf = fnLimpacampo($_REQUEST['NUM_CGCECPF']);
	$nom_hosp  = fnLimpacampo($_REQUEST['NOM_HOSP']);
	$des_emailus = fnLimpacampo($_REQUEST['DES_EMAILUS']);
	$num_telefone = fnLimpacampo(fnLimpaDoc($_REQUEST['NUM_TELEFONE']));

	// fnEscreve($num_cgcecpf);

} else {
	$cod_hospede  = 0;
	$nom_hosp  = "";
}

//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
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
	$cod_empresa = 274;
	//fnEscreve('entrou else');
}


include "labelLibrary.php";

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
					<?php }

					?>

					<div class="login-form">

						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

							<fieldset>
								<legend>Dados para Pesquisa</legend>

								<div class="row">

									<div class="col-xs-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Cód. Hospede</label>
											<input type="text" class="form-control input-sm" name="COD_HOSPEDE" id="COD_HOSPEDE" value="<?= $cod_hospede ?>">
										</div>
									</div>

									<div class="col-xs-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Cód. Reserva</label>
											<input type="text" class="form-control input-sm" name="COD_PEDIDO" id="COD_PEDIDO" value="<?= $cod_pedido ?>">
										</div>
									</div>

									<div class="col-xs-4">
										<div class="form-group">
											<label for="inputName" class="control-label">CPF/CNPJ</label>
											<input type="text" class="form-control input-sm cpfcnpj" name="NUM_CGCECPF" id="NUM_CGCECPF" maxlength="18" value="<?= $num_cgcecpf ?>">
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-xs-4">
										<div class="form-group">
											<label for="inputName" class="control-label">Nome Hospede</label>
											<input type="text" class="form-control input-sm" name="NOM_HOSP" id="NOM_HOSP" maxlength="40"  value="<?= $nom_hosp ?>">
											<div class="help-block with-errors"></div>
										</div>
									</div>
								</div>

								<div class="row">

									<div class="col-md-4">
										<div class="form-group">
											<label for="inputName" class="control-label">e-Mail</label>
											<input type="text" class="form-control input-sm" name="DES_EMAILUS" id="DES_EMAILUS" maxlength="100" value="<?= $des_emailus ?>" data-error="Campo obrigatório">
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-4">
										<div class="form-group">
											<label for="inputName" class="control-label">Celular/Telefone</label>
											<input type="text" class="form-control input-sm fone" name="NUM_TELEFONE" id="NUM_TELEFONE" maxlength="20" value="<?= $num_telefone ?>">
											<div class="help-block with-errors"></div>
										</div>
									</div>

								</div>

							</fieldset>

							<div class="push10"></div>
							<hr>
							<div class="form-group text-right col-lg-12">

								<button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>

								<button type="submit" name="BUS" id="BUS" class="btn btn-primary getBtn"><i class="fal fa-search" aria-hidden="true"></i>&nbsp; Pesquisar</button>

							</div>

							<input type="hidden" name="opcao" id="opcao" value="">
							<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
							<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">

							<div class="push5"></div>

						</form>

						<div class="push50"></div>

						<?php

						if ($_SERVER['REQUEST_METHOD'] == 'POST') {
							//if ($cod_empresa != 0 ){

							$pagina = (isset($_GET['pagina'])) ? $_GET['pagina'] : 1;

							if ($cod_hospede != 0) {
								$andCodigo = 'AND COD_HOSPEDE=' . $cod_hospede;
							} else {
								$andCodigo = ' ';
							}							

							if ($cod_pedido != 0) {
								$andCodPedido = 'AND COD_PEDIDO=' . $cod_pedido;
							} else {
								$andCodPedido = ' ';
							}

							if ($num_cgcecpf != 0) {
								$andcpf = "AND NUM_CGCECPF= '$num_cgcecpf'";
							} else {
								$andcpf = ' ';
							}

							if ($nom_hosp != '') {
								$andNome = 'and NOM_HOSP like "' . $nom_hosp . '%"';
							} else {
								$andNome = ' ';
							}

							if ($des_emailus != '') {
								$andEmail = 'and des_emailus like "' . $des_emailus . '%"';
							} else {
								$andEmail = "";
							}

							if ($num_telefone != '') {
								$andTelefone = 'AND (NUM_TELEFONE LIKE "' . $num_telefone . '%" OR NUM_TELEFONE LIKE "' . $num_telefone . '%")';
							} else {
								$andTelefone = "";
							}

							$sql = "SELECT count(COD_HOSPEDE) as CONTADOR from HOSPEDES_ADORAI where cod_empresa = " . $cod_empresa . " 
							" . $andCodigo . "
							" . $andCodPedido . "
							" . $andNome . "
							" . $andcpf . "
							" . $andEmail . "
							" . $andTelefone . "
							order by NOM_HOSP ";
							//fnEscreve($sql);

							$resPagina = mysqli_query(connTemp($cod_empresa, ''), $sql) or die(mysqli_error());
							$total = mysqli_fetch_assoc($resPagina);
							//seta a quantidade de itens por página, neste caso, 2 itens
							$registros = 100;
							//fnEscreve($total['CONTADOR']);
							//calcula o número de páginas arredondando o resultado para cima
							$numPaginas = ceil($total['CONTADOR'] / $registros);
							//variavel para calcular o início da visualização com base na página atual
							$inicio = ($registros * $pagina) - $registros;
						} else {
							$numPaginas = 1;
						}

						if ($_SERVER['REQUEST_METHOD'] == 'POST') {
							?>

							<div class="col-lg-12">

								<div class="no-more-tables">

									<table class="table table-bordered table-striped table-hover" id="tablista">
										<thead>
											<tr>
												<th class="{ sorter: false }" width="40"></th>
												<th>Cód. Hospede</th>
												<th>Nome</th>
												<th>Cód. Reserva</th>
												<th>e-Mail</th>
												<th>Celular/Telefone</th>
												<th>CPF</th>
											</tr>
										</thead>
										<tbody>

											<?php
											if ($_SERVER['REQUEST_METHOD'] == 'POST') {


												if ($cod_hospede != 0) {
													$andCodigo = 'AND COD_HOSPEDE=' . $cod_hospede;
												} else {
													$andCodigo = ' ';
												}							

												if ($cod_pedido != 0) {
													$andCodPedido = 'AND COD_PEDIDO=' . $cod_pedido;
												} else {
													$andCodPedido = ' ';
												}

												if ($num_cgcecpf != 0) {
													$andcpf = 'AND NUM_CGCECPF=' . $num_cgcecpf;
												} else {
													$andcpf = ' ';
												}

												if ($nom_hosp != '') {
													$andNome = 'and NOM_HOSP like "' . $nom_hosp . '%"';
												} else {
													$andNome = ' ';
												}

												if ($des_emailus != '') {
													$andEmail = 'AND DES_EMAILUS LIKE "' . $des_emailus . '%"';
												} else {
													$andEmail = "";
												}

												if ($num_telefone != '') {
													$andTelefone = 'AND (NUM_TELEFONE LIKE "' . $num_telefone . '%" OR NUM_TELEFONE LIKE "' . $num_telefone . '%")';
												} else {
													$andTelefone = "";
												}

												$sql = "SELECT COD_HOSPEDE, COD_PEDIDO, NUM_CGCECPF, NOM_HOSP, SOBRENOM_HOSP , DES_EMAILUS, NUM_TELEFONE from HOSPEDES_ADORAI 
												where cod_empresa = " . $cod_empresa . " 
												" . $andCodigo . "
												" . $andCodPedido . "
												" . $andNome . "
												" . $andcpf . "
												" . $andEmail . "
												" . $andTelefone . "

												ORDER BY NOM_HOSP limit $inicio,$registros";
												//fnEscreve($sql);
												$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql) or die(mysqli_error());

												$count = 0;

												while ($qrListaEmpresas = mysqli_fetch_assoc($arrayQuery)) {
													$count++;

													echo "
													<tr>
													<td><a href='javascript: downForm(" . $count . ")' style='margin-left: 10px;'><i class='fa fa-arrow-circle-down' aria-hidden='true'></i></a></th>
													<td>" . $qrListaEmpresas['COD_HOSPEDE'] . "</td>
													<td></td>
													<td>" . $qrListaEmpresas['NOM_HOSP'] . "</td>
													<td>" . $qrListaEmpresas['COD_PEDIDO'] . "</td>
													<td>" . $qrListaEmpresas['DES_EMAILUS'] . "</td>
													<td>" . $qrListaEmpresas['NUM_TELEFONE'] . "/" . $qrListaEmpresas['NUM_TELEFONE'] . "</td>
													<td>" . $qrListaEmpresas['NUM_CGCECPF'] . "</td>
													</tr>
													<input type='hidden' id='ret_COD_HOSPEDE_" . $count . "' value='" . $qrListaEmpresas['COD_HOSPEDE'] . "'>
													<input type='hidden' id='ret_COD_PEDIDO_" . $count . "' value='" . $qrListaEmpresas['COD_PEDIDO'] . "'>
													<input type='hidden' id='ret_NUM_CGCECPF_" . $count . "' value='" . $qrListaEmpresas['NUM_CGCECPF'] . "'>
													<input type='hidden' id='ret_HOSPEDE_" . $count . "' value='" . $qrListaEmpresas['NOM_HOSP'] . " ". $qrListaEmpresas['SOBRENOM_HOSP'] . "'>
													";
												}
											}
											?>

										</tbody>
										<?php if ($cod_empresa != 0) {  ?>
											<tfoot>
												<tr>
													<th colspan="100">
														<ul class="pagination pagination-sm pull-right">
															<?php
															for ($i = 1; $i < $numPaginas + 1; $i++) {
																echo "<li class='pagination'><a href='{$_SERVER['PHP_SELF']}?mod=NN7xULiFM88¢&pagina=$i' style='text-decoration: none;'>" . $i . "</a></li>";
															}
														?></ul>
													</th>
												</tr>
											</tfoot>
										<?php }   ?>

									</table>

									<div class="push"></div>
								</div>

							</div>
							<?php
						}

						?>

						<div class="push"></div>

					</div>

				</div>
			</div>
			<!-- fim Portlet -->
		</div>

	</div>

	<div class="push20"></div>

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
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->


	<script type="text/javascript">
		$(document).keypress(function(event) {
			var keycode = (event.keyCode ? event.keyCode : event.which);
			if (keycode == '13') {
				$('#BUS').click();
			}
		});


		$(document).ready(function() {

			//chosen obrigatório
			$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
			$('#formulario').validator();

			//table sorter
			$(function() {
				var tabelaFiltro = $('table.tablesorter')
				tabelaFiltro.find("tbody > tr").find("td:eq(1)").mousedown(function() {
					$(this).prev().find(":checkbox").click()
				});
				$("#filter").keyup(function() {
					$.uiTableFilter(tabelaFiltro, this.value);
				})
				$('#formLista').submit(function() {
					tabelaFiltro.find("tbody > tr:visible > td:eq(1)").mousedown();
					return false;
				}).focus();
			});

			//pesquisa table sorter
			$('.filter-all').on('input', function(e) {
				if ('' == this.value) {
					var lista = $("#filter").find("ul").find("li");
					filtrar(lista, "");
				}
			});

		});


		// function retornaForm(index) {

		// 	$('#formulario').attr('action', 'action.php?mod=<?php echo $DestinoPg; ?>&id=' + $("#ret_COD_EMPRESA_" + index).val() + '&idC=' + $("#ret_COD_CLIENTE_" + index).val());
		// 	$("#formulario #hHabilitado").val('S');
		// 	$("#formulario")[0].submit();

		// }

		function downForm(index) {
			cod_hospede = $("#ret_COD_HOSPEDE_" + index).val();
			cod_pedido = $("#ret_COD_PEDIDO_" + index).val();
			num_cgcecpf = $("#ret_NUM_CGCECPF_" + index).val();
			hospede = $("#ret_HOSPEDE_" + index).val();

			parent.$('#COD_HOSPEDE').val($("#ret_COD_HOSPEDE_" + index).val());
			parent.$('#COD_PEDIDO').val($("#ret_COD_PEDIDO_" + index).val());
			parent.$('#NUM_CGCECPF').val($("#ret_NUM_CGCECPF_" + index).val());
			parent.$('#HOSPEDE').val($("#ret_HOSPEDE_" + index).val());


			$(this).removeData('bs.modal');
			parent.$('.modal').modal('hide');

			// alert('passou o hide');

		}
	</script>