<?php

//echo fnDebug('true');

// definir o numero de itens por pagina
$itens_por_pagina = 50;
$pagina  = "1";

$hashLocal = mt_rand();

$adm = $connAdm->connAdm();

if (isset($_POST['COD_EMPRESA'])) {
} else {

	$cod_empresa = "";
	$cod_empresaCode = "";
	$cod_cliente  = "";
	$nom_cliente  = "";

	if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
		//busca dados da empresa
		$cod_empresa = fnDecode($_GET['id']);
		$cod_persona = fnDecode($_GET['idP']);

		$sql = "SELECT NOM_FANTASI,
			(select des_persona from persona where cod_persona = '" . $cod_persona . "') as DES_PERSONA	
			FROM " . $connAdm->DB . ".empresas where COD_EMPRESA = '" . $cod_empresa . "' 		
			";

		//fnEscreve($sql);

		$arrayQuery = mysqli_query(conntemp($cod_empresa, ''), $sql);
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
		$des_persona = $qrBuscaEmpresa['DES_PERSONA'];
	}
}

//fnEscreve($cod_empresa); 	
//fnEscreve($cod_persona); 	
//fnMostraForm();

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

<div class="push30"></div>

<div class="row">

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fal fa-terminal"></i>
					<span class="text-primary"><?php echo $NomePg; ?>: <?php echo $des_persona; ?> / <?php echo $nom_empresa; ?> </span>
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

					<div class="row">
						<div class="col-lg-12">

							<div class="no-more-tables">

								<form name="formLista" id="formLista" method="post" action="">

									<table class="table table-bordered table-striped table-hover tablesorter" id="tablista">
										<thead>
											<tr>
												<th>Nome</th>
												<th>Cartão</th>
												<th>CPF</th>
												<th>Sexo</th>
												<th>e-Mail</th>
												<th>Celular</th>
												<th>Nascimento</th>
												<th>Profissão</th>
												<th>Cadastro</th>
												<th>Origem</th>
											</tr>
										</thead>

										<tbody id="relatorioConteudo">

											<?php

											$sql = "SELECT COUNT(*) as CONTADOR FROM PERSONACLASSIFICA A, CLIENTES B
													WHERE 
													A.COD_CLIENTE = B.COD_CLIENTE AND
													B.LOG_AVULSO='N' AND
													A.COD_PERSONA = $cod_persona AND 
													A.COD_EMPRESA = $cod_empresa ";
											//fnEscreve($sql);

											$retorno = mysqli_query(conntemp($cod_empresa, ''), $sql);
											$total_itens_por_pagina = mysqli_fetch_assoc($retorno);

											$numPaginas = ceil($total_itens_por_pagina['CONTADOR'] / $itens_por_pagina);

											//variavel para calcular o início da visualização com base na página atual
											$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;


											$sql = "SELECT B.COD_CLIENTE,
												       B.NUM_CARTAO,
												       B.NUM_CGCECPF,
												       B.NOM_CLIENTE,
												       B.DES_EMAILUS,
												       B.NUM_CELULAR,
												       B.DAT_CADASTR,
												       B.DAT_NASCIME,
												       B.COD_SEXOPES,
												       PE.DES_PROFISS,
												       C.NOM_UNIVEND
												FROM PERSONACLASSIFICA A
												INNER JOIN CLIENTES B ON A.COD_CLIENTE = B.COD_CLIENTE
												INNER JOIN $connAdm->DB.unidadevenda C ON B.COD_UNIVEND = C.COD_UNIVEND
												LEFT JOIN $connAdm->DB.profissoes PE ON PE.COD_PROFISS = B.COD_PROFISS
												WHERE A.COD_PERSONA = $cod_persona
												  AND A.COD_EMPRESA = $cod_empresa
												  AND B.LOG_AVULSO = 'N'
												ORDER BY B.NOM_CLIENTE limit $inicio,$itens_por_pagina";

											//fnEscreve($sql);

											$arrayQuery = mysqli_query(conntemp($cod_empresa, ''), $sql);

											$count = 0;
											while ($qrListaPersonas = mysqli_fetch_assoc($arrayQuery)) {
												$count++;

												if ($qrListaPersonas['COD_SEXOPES'] == 1) {
													$mostraSexo = '<i class="fa fa-male" aria-hidden="true"></i>';
												} else {
													$mostraSexo = '<i class="fa fa-female" style="color:pink" aria-hidden="true"></i>';
												}

												echo "
													<tr>
														<td><small><a href='action.do?mod=" . fnEncode(1024) . "&id=" . fnEncode($cod_empresa) . "&idC=" . fnEncode($qrListaPersonas['COD_CLIENTE']) . "' target='_blank'>" . fnMascaraCampo($qrListaPersonas['NOM_CLIENTE']) . "</a></td>
														<td><small>" . fnMascaraCampo($qrListaPersonas['NUM_CARTAO']) . "</small></td>
														<td><small>" . fnMascaraCampo($qrListaPersonas['NUM_CGCECPF']) . "</small></td>
														<td class='text-center'>" . $mostraSexo . "</td>
														<td><small>" . fnMascaraCampo($qrListaPersonas['DES_EMAILUS']) . "</small></td>
														<td><small>" . fnMascaraCampo($qrListaPersonas['NUM_CELULAR']) . "</small></td>
														<td><small>" . fnMascaraCampo($qrListaPersonas['DAT_NASCIME']) . "</small></td>
														<td><small>" . $qrListaPersonas['DES_PROFISS'] . "</small></td>
														<td><small>" . fnDataFull($qrListaPersonas['DAT_CADASTR']) . "</small></td>
														<td><small>" . $qrListaPersonas['NOM_UNIVEND'] . "</small></td>
													</tr>
												";
											}

											?>

										</tbody>

										<tfoot>
											<tr>
												<th colspan="100">
													<a class="btn btn-info btn-sm exportarCSV"><i class="fa fa-file-excel" aria-hidden="true"></i>&nbsp; Exportar</a>
												</th>
											</tr>
											<tr>
												<th class="" colspan="100">
													<center>
														<ul id="paginacao" class="pagination-sm"></ul>
													</center>
												</th>
											</tr>
										</tfoot>

									</table>

									<div class="push"></div>
								</form>
							</div>
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

		var numPaginas = <?php echo $numPaginas; ?>;
		if (numPaginas != 0) {
			carregarPaginacao(numPaginas);
		}

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


		$(".exportarCSV").click(function() {
			$.confirm({
				title: 'Exportação',
				content: '' +
					'<form action="" class="formName">' +
					'<div class="form-group">' +
					'<label>Insira o nome do arquivo:</label>' +
					'<input type="text" placeholder="Nome" class="nome form-control" required />' +
					'</div>' +
					'</form>',
				buttons: {
					formSubmit: {
						text: 'Gerar',
						btnClass: 'btn-blue',
						action: function() {
							var nome = this.$content.find('.nome').val();
							if (!nome) {
								$.alert('Por favor, insira um nome');
								return false;
							}

							$.confirm({
								title: 'Mensagem',
								type: 'green',
								icon: 'fa fa-check-square-o',
								content: function() {
									var self = this;
									return $.ajax({
										url: "ajxListaPersonasClientes.do?opcao=exportar&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>&codPersona=<?php echo fnEncode($cod_persona); ?>",
										method: 'POST'
									}).done(function(response) {
										self.setContentAppend('<div>Exportação realizada com sucesso.</div>');
										var fileName = '<?php echo $cod_empresa; ?>_' + nome + '.csv';
										SaveToDisk('media/excel/' + fileName, fileName);
										console.log(response);
									}).fail(function() {
										self.setContentAppend('<div>Erro ao realizar o procedimento!</div>');
									});
								},
								buttons: {
									fechar: function() {
										//close
									}
								}
							});
						}
					},
					cancelar: function() {
						//close
					},
				}
			});
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


	function reloadPage(idPage) {
		$.ajax({
			type: "POST",
			url: "ajxListaPersonasClientes.do?opcao=paginar&id=<?php echo fnEncode($cod_empresa); ?>&codPersona=<?php echo fnEncode($cod_persona); ?>&itens_por_pagina=<?php echo $itens_por_pagina; ?>&idPage=" + idPage,
			data: $('#formulario').serialize(),
			beforeSend: function() {
				$('#relatorioConteudo').html('<tr><td colspan="100"><div class="loading" style="width: 100%;"></div></tr></td>');
			},
			success: function(data) {
				$("#relatorioConteudo").html(data);
			},
			error: function() {
				$('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
			}
		});
	}

	function retornaForm(index) {
		$('#formulario').attr('action', 'action.do?mod=<?php echo $DestinoPg; ?>&id=' + $("#ret_COD_EMPRESA_" + index).val() + '&idC=' + $("#ret_COD_CLIENTE_" + index).val());
		$("#formulario #hHabilitado").val('S');
		$("#formulario")[0].submit();
	}
</script>