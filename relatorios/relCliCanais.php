<?php
include '../_system/_functionsMain.php';

//echo fnDebug('true');

$hashLocal = mt_rand();

//busca dados da empresa
$cod_empresa = fnDecode($_GET['id']);

$sql = "SELECT NOM_FANTASI
	FROM empresas where COD_EMPRESA = $cod_empresa ";

//fnEscreve($sql);

$arrayQuery = mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error());
$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];

//fnEscreve($cod_empresa); 	
//fnEscreve($cod_persona); 	
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
					<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?> </span>
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

				<div class="login-form">

					<div class="push20"></div>

					<?php

					$sql = "SELECT COUNT(*) as CONTADOR FROM CLIENTES
													WHERE COD_EMPRESA = $cod_empresa AND
													LENGTH(NUM_CARTAO) > 8 ";
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

					?>


					<style>
						input[type="search"]::-webkit-search-cancel-button {
							height: 16px;
							width: 16px;
							background: url(images/close-filter.png) no-repeat right center;
							position: relative;
							cursor: pointer;
						}

						input.tableFilter {
							border: 0px;
							background-color: #fff;
						}

						table a:not(.btn),
						.table a:not(.btn) {
							text-decoration: none;
						}

						table a:not(.btn):hover,
						.table a:not(.btn):hover {
							text-decoration: underline;
						}
					</style>

					<div class="col-lg-12">

						<div class="no-more-tables">

							<form name="formLista" id="formLista" method="post" action="">


								<table class="table table-bordered table-striped table-hover tablesorter" id="tablista">
									<thead>
										<tr>
											<th class="bg-primary">Nome</th>
											<th class="bg-primary">Cartão</th>
											<th class="bg-primary">CPF</th>
											<th class="bg-primary">e-Mail</th>
											<th class="bg-primary">Sexo</th>
											<th class="bg-primary">Nascimento</th>
											<th class="bg-primary">Cadastro</th>
										</tr>
									</thead>

									<tbody>

										<?php

										$sql = "SELECT COD_CLIENTE, NUM_CARTAO, NUM_CGCECPF, NOM_CLIENTE,
															DES_EMAILUS, DAT_CADASTR, DAT_NASCIME , COD_SEXOPES 
															FROM CLIENTES WHERE 															
															COD_EMPRESA = $cod_empresa AND
															LENGTH(NUM_CARTAO) > 8
															order by NOM_CLIENTE limit 2000 ";

										//fnEscreve($sql);

										$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql) or die(mysqli_error());

										$count = 0;
										while ($qrListaPersonas = mysqli_fetch_assoc($arrayQuery)) {
											$count++;

											if ($qrListaPersonas['COD_SEXOPES'] == 1) {
												$mostraSexo = '<i class="fa fa-male" aria-hidden="true"></i>';
											} else {
												$mostraSexo = '<i class="fa fa-female" aria-hidden="true"></i>';
											}

											echo "
															<tr>
															  <td><small><a href='action.do?mod=" . fnEncode(1024) . "&id=" . fnEncode($cod_empresa) . "&idC=" . fnEncode($qrListaPersonas['COD_CLIENTE']) . "' target='_blank'>" . $qrListaPersonas['NOM_CLIENTE'] . "</a></td>
															  <td><small>" . $qrListaPersonas['NUM_CARTAO'] . "</small></td>
															  <td><small>" . $qrListaPersonas['NUM_CGCECPF'] . "</small></td>
															  <td><small>" . $qrListaPersonas['DES_EMAILUS'] . "</small></td>
															  <td class='text-center'>" . $mostraSexo . "</td>
															  <td><small>" . fnDataFull($qrListaPersonas['DAT_NASCIME']) . "</small></td>
															  <td><small>" . fnDataFull($qrListaPersonas['DAT_CADASTR']) . "</small></td>
															</tr>
															";
										}

										?>
										<!--	
												</tbody>
													<tfoot>
														<tr>
														  <th class="" colspan="100"><ul class="pagination pagination-sm">
														  <?php
															for ($i = 1; $i < $numPaginas + 1; $i++) {
																if ($pagina == $i) {
																	$paginaAtiva = "active";
																} else {
																	$paginaAtiva = "";
																}
																echo "<li class='pagination $paginaAtiva'><a href='javascript:void(0);' onclick='page(" . $i . ")' style='text-decoration: none;'>" . $i . "</a></li>";
															}
															?></ul>
														  </th>
														</tr>
													</tfoot>
												-->
								</table>

								<div class="push"></div>

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


<script type="text/javascript">
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

	$(document).on('change', '#COD_EMPRESA', function() {
		$("#dKey").val($("#COD_EMPRESA").val());
	});


	function page(index) {

		$("#pagina").val(index);
		$("#formulario")[0].submit();
		//alert(index);	

	}

	function retornaForm(index) {

		$('#formulario').attr('action', 'action.do?mod=<?php echo $DestinoPg; ?>&id=' + $("#ret_COD_EMPRESA_" + index).val() + '&idC=' + $("#ret_COD_CLIENTE_" + index).val());
		$("#formulario #hHabilitado").val('S');
		$("#formulario")[0].submit();

	}
</script>