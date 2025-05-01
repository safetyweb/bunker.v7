<?php
include '../_system/_functionsMain.php';

if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}


$dias30 = '';
$dat_ini = '';
$dat_fim = '';
$num_cartao = '';
$nom_cliente = '';



//inicialização de variáveis
$hoje = fnFormatDate(date("Y-m-d"));
//$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 30 days')));
$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30 . '- 1 days')));

// definir o numero de itens por pagina
$itens_por_pagina = 50;
$pagina  = "1";

$hashLocal = mt_rand();

$conn = connTemp($cod_empresa, '');
$adm = $connAdm->connAdm();

//busca dados da empresa
$cod_empresa = fnDecode($_GET['id']);

$sql = "SELECT NOM_FANTASI FROM empresas where COD_EMPRESA = $cod_empresa ";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_empresa = fnLimpaCampoZero(@$_POST['COD_EMPRESA']);
		$cod_univend = @$_POST['COD_UNIVEND'];
		$cod_grupotr = @$_REQUEST['COD_GRUPOTR'];
		$cod_tiporeg = @$_REQUEST['COD_TIPOREG'];
		$dat_ini = fnDataSql(@$_POST['DAT_INI']);
		$dat_fim = fnDataSql(@$_POST['DAT_FIM']);
		$nom_cliente = @$_POST['NOM_CLIENTE'];
		$num_cartao = @$_POST['NUM_CARTAO'];

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		//fnEscreve($_REQUEST['hHabilitado'];);

		if ($opcao != '') {
		}
	}
}

//fnEscreve($sql);

$arrayQuery = mysqli_query($adm, $sql);
$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];

//inicialização das variáveis - default	
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
	$dat_ini = fnDataSql($dias30);
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
	$dat_fim = fnDataSql($hoje);
}

//busca revendas do usuário
include "unidadesAutorizadas.php";

//rotina de controle de acessos por módulo
include "moduloControlaAcesso.php";

if (fnControlaAcesso("1024", $arrayParamAutorizacao) === true) {
	$autoriza = 1;
} else {
	$autoriza = 0;
}

//fnEscreve($cod_empresa); 	
//fnEscreve($cod_persona); 	
//fnMostraForm();
/*$ARRAY_UNIDADE1=array(
            'sql'=>"select COD_UNIVEND,cod_empresa,nom_fantasi,NOM_UNIVEND from unidadevenda where cod_empresa=$cod_empresa",
            'cod_empresa'=>$cod_empresa,
            'conntadm'=>$connAdm->connAdm(),
            'IN'=>'N',
            'nomecampo'=>'',
            'conntemp'=>'',
            'SQLIN'=> ""   
            );
$ARRAY_UNIDADE=fnUnivend($ARRAY_UNIDADE1);
  * 
  */
/*$ARRAY_VENDEDOR1=array(
            'sql'=>"select COD_USUARIO,NOM_USUARIO from usuarios where cod_empresa=$cod_empresa",
            'cod_empresa'=>$cod_empresa,
            'conntadm'=>$connAdm->connAdm(),
            'IN'=>'N',
            'nomecampo'=>'',
            'conntemp'=>'',
            'SQLIN'=> ""   
            );
$ARRAY_VENDEDOR=fnUniVENDEDOR($ARRAY_VENDEDOR1);
*/
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
					<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
				</div>
				<?php include "atalhosPortlet.php"; ?>
			</div>

			<?php if ($msgRetorno <> '') { ?>
				<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<?php echo $msgRetorno; ?>
				</div>
			<?php } ?>

			<div class="login-form">

				<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

					<fieldset>
						<legend>Filtros</legend>

						<div class="row">

							<div class="col-md-3">
								<div class="form-group">
									<label for="inputName" class="control-label required">Empresa</label>
									<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa; ?>">
									<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
								</div>
							</div>

							<div class="col-md-3">
								<div class="form-group">
									<label for="inputName" class="control-label">Nome do Cliente</label>
									<input type="text" class="form-control input-sm" name="NOM_CLIENTE" id="NOM_CLIENTE" value="<?php echo $nom_cliente; ?>">
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label for="inputName" class="control-label">Cartão</label>
									<input type="text" class="form-control input-sm" name="NUM_CARTAO" id="NUM_CARTAO" value="<?php echo $num_cartao; ?>">
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label for="inputName" class="control-label required">Data Inicial</label>

									<div class="input-group date datePicker" id="DAT_INI_GRP">
										<input type='text' class="form-control input-sm data" name="DAT_INI" id="DAT_INI" value="<?php echo fnFormatDate($dat_ini); ?>" required />
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
										<input type='text' class="form-control input-sm data" name="DAT_FIM" id="DAT_FIM" value="<?php echo fnFormatDate($dat_fim); ?>" required />
										<span class="input-group-addon">
											<span class="glyphicon glyphicon-calendar"></span>
										</span>
									</div>
									<div class="help-block with-errors"></div>
								</div>
							</div>

							<div class="push10"></div>

							<div class="col-md-4">
								<div class="form-group">
									<label for="inputName" class="control-label required">Unidade de Atendimento</label>
									<?php include "unidadesAutorizadasComboMulti.php"; ?>
								</div>
							</div>

							<div class="col-md-3">
								<div class="form-group">
									<label for="inputName" class="control-label">Grupo de Lojas</label>
									<?php include "grupoLojasComboMulti.php"; ?>
								</div>
							</div>

							<div class="col-md-3">
								<div class="form-group">
									<label for="inputName" class="control-label">Região</label>
									<?php include "grupoRegiaoMulti.php"; ?>
								</div>
							</div>

							<div class="col-md-2">
								<div class="push20"></div>
								<button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-sm btn-block getBtn"><i class="fal fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
							</div>


						</div>

					</fieldset>

					<div class="push30"></div>
					<?php

					if ($num_cartao == "") {
						$andCartao = "";
					} else {
						$andCartao = "AND B.NUM_CARTAO = $num_cartao";
					}

					if ($nom_cliente == "") {
						$andNome = "";
					} else {
						$andNome = "AND B.NOM_CLIENTE LIKE '%$nom_cliente%' ";
					}

					// Filtro por Grupo de Lojas
					include "filtroGrupoLojas.php";

					$sql = "SELECT COUNT(*) CONTADOR, SUM(A.VAL_RESGATE) VAL_CREDITO, SUM(A.VAL_TOTPRODU) AS VAL_VINCULADO 
								 FROM VENDAS A 
								INNER JOIN CLIENTES B ON B.COD_CLIENTE = A.COD_CLIENTE
								LEFT JOIN USUARIOS US ON US.COD_USUARIO = A.COD_VENDEDOR
								LEFT JOIN unidadevenda uni ON uni.COD_UNIVEND=A.COD_UNIVEND	 
								WHERE A.VAL_RESGATE > 0 AND 
								A.DAT_CADASTR_WS BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' AND 
								A.COD_STATUSCRED in (0,1,2,3,4,5,7,8,9) AND 
								A.COD_CREDITOU != 4 AND 
								A.COD_EMPRESA = $cod_empresa AND 
								A.COD_UNIVEND IN ($lojasSelecionadas) 
								AND A.COD_VENDA > 0
							   $andNome																	   
							   $andCartao																	   
							   ";
					// fnEscreve($sql);
					//fnTestesql(connTemp($cod_empresa,''), $sql);
					$retorno = mysqli_query($conn, $sql);
					$total_itens_por_pagina = mysqli_fetch_assoc($retorno);

					$numPaginas = ceil($total_itens_por_pagina['CONTADOR'] / $itens_por_pagina);

					?>

					<input type="hidden" name="LOJAS" id="LOJAS" value="<?php echo $lojasSelecionadas; ?>" />
					<input type="hidden" name="AUTORIZA" id="AUTORIZA" value="<?= $autoriza ?>" />
					<input type="hidden" name="CASAS_DEC" id="CASAS_DEC" value="<?= $casasDec ?>">
					<input type="hidden" name="opcao" id="opcao" value="">
					<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
					<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

					<div class="push5"></div>

				</form>
			</div>
		</div>

		<div class="push30"></div>

		<div class="row">

			<div class="col-md-12 col-lg-12 margin-bottom-30">
				<!-- Portlet -->
				<div class="portlet portlet-bordered">

					<div class="portlet-body">


						<div class="row text-center">

							<div class="form-group text-center col-md-4 col-lg-4">

								<div class="push20"></div>

								<p><span><?php echo fnValor($total_itens_por_pagina['CONTADOR'], 0); ?></span></p>
								<p><b>Quantidade Total de Resgates</b></p>

								<div class="push20"></div>

							</div>

							<div class="form-group text-center col-md-4 col-lg-4">

								<div class="push20"></div>

								<p>R$ <span><?php echo fnValor($total_itens_por_pagina['VAL_CREDITO'], 2); ?></span></p>
								<p><b>Valor Total Resgatado</b></p>

								<div class="push20"></div>

							</div>

							<div class="form-group text-center col-md-4 col-lg-4">

								<div class="push20"></div>

								<p>R$ <span><?php echo fnValor($total_itens_por_pagina['VAL_VINCULADO'], 2); ?></span></p>
								<p><b>Valor Total Compras Vinculadas</b></p>

								<div class="push20"></div>

							</div>

						</div>

					</div>
					<!-- fim Portlet -->
				</div>

			</div>

		</div>


		<div class="portlet portlet-bordered">
			<div class="portlet-body">

				<div class="login-form">
					<div class="row">
						<div class="col-md-12">

							<table class="table table-bordered table-striped table-hover tablesorter" id="tablista">
								<thead>
									<tr>
										<th>Nome</th>
										<th>Cartão</th>
										<th>Loja</th>
										<th>Data de Resgate</th>
										<th>Valor <br />Líquido</th>
										<th>Resgate</th>
										<th>Valor <br />Vinculado</th>
										<th>ID Venda</th>
										<th>Cupom</th>
										<th>Operador</th>
									</tr>
								</thead>

								<tbody id="relatorioConteudo">

									<?php

									// Filtro por Grupo de Lojas
									include "filtroGrupoLojas.php";

									//variavel para calcular o início da visualização com base na página atual
									$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

									$sql = "SELECT A.COD_CLIENTE, 
											   B.NOM_CLIENTE, 
												 B.NUM_CARTAO,  
												 uni.NOM_FANTASI,
												 A.COD_CUPOM, 
												 A.DAT_CADASTR, 
												 ROUND(A.VAL_TOTPRODU,2) VAL_TOTPRODU , 
												 ROUND(A.VAL_RESGATE,2)  VAL_RESGATE, 
												 ROUND(A.VAL_TOTVENDA,2) VAL_TOTVENDA, 
												 A.COD_VENDEDOR, 
												 A.COD_VENDAPDV, 
												 A.COD_UNIVEND,
												 US.NOM_USUARIO,
												 US.COD_EXTERNO 
										FROM VENDAS A 
										INNER JOIN CLIENTES B ON B.COD_CLIENTE = A.COD_CLIENTE
										LEFT JOIN USUARIOS US ON US.COD_USUARIO = A.COD_VENDEDOR										 
										LEFT JOIN unidadevenda uni ON uni.COD_UNIVEND=A.COD_UNIVEND										 
										WHERE A.VAL_RESGATE > 0 AND 
										A.DAT_CADASTR_WS BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' AND 
										A.COD_STATUSCRED in (0,1,2,3,4,5,7,8,9) AND 
										A.COD_CREDITOU != 4 AND 
										A.COD_EMPRESA = $cod_empresa AND 
										A.COD_UNIVEND IN ($lojasSelecionadas) 
									   $andNome																	   
									   $andCartao																	   
										-- GROUP BY A.COD_CLIENTE, B.NOM_CLIENTE, B.NUM_CARTAO 
										 ORDER BY A.DAT_CADASTR_WS  limit $inicio,$itens_por_pagina ";

									// fnEscreve($sql);
									$arrayQuery = mysqli_query($conn, $sql);

									$count = 0;
									while ($qrListaResgates = mysqli_fetch_assoc($arrayQuery)) {
										/*$NOM_ARRAY_UNIDADE=(array_search($qrListaResgates['COD_UNIVEND'], array_column($ARRAY_UNIDADE, 'COD_UNIVEND')));
								 $NOM_ARRAY_NON_VENDEDOR=(array_search($qrListaResgates['COD_VENDEDOR'], array_column($ARRAY_VENDEDOR, 'COD_USUARIO')));
                                                                  * 
                                                                  */
										if ($autoriza == 1) {
											$colCliente = "<td><small><a href='action.do?mod=" . fnEncode(1024) . "&id=" . fnEncode($cod_empresa) . "&idC=" . fnEncode($qrListaResgates['COD_CLIENTE']) . "' target='_blank'>" . fnMascaraCampo($qrListaResgates['NOM_CLIENTE']) . "</a></small></td>";
										} else {
											$colCliente = "<td><small>" . fnMascaraCampo($qrListaResgates['NOM_CLIENTE']) . "</small></td>";
										}
										$count++;

										echo "
									<tr>
									  " . $colCliente . "
									  <td><small>" . fnMascaraCampo($qrListaResgates['NUM_CARTAO']) . "</small></td>
									  <td><small>" . @$qrListaResgates['nom_fantasi'] . "</small></td>
									  <td><small>" . fnDataFull($qrListaResgates['DAT_CADASTR']) . "</small></td>
									  <td class='text-center'><small><small>R$</small> " . fnValor($qrListaResgates['VAL_TOTVENDA'], 2) . "</small></td>
									  <td class='text-center'><small><small>R$</small> " . fnValor($qrListaResgates['VAL_RESGATE'], 2) . "</small></td>
									  <td class='text-center'><small><small>R$</small> " . fnValor($qrListaResgates['VAL_TOTPRODU'], 2) . "</small></td>
									  <td><small>" . $qrListaResgates['COD_VENDAPDV'] . "</small></td>
									  <td><small>" . $qrListaResgates['COD_CUPOM'] . "</small></td>
									  <td><small>" . $qrListaResgates['NOM_USUARIO'] . "</small></td>
									</tr>
									";
									}

									?>

								</tbody>
								<tfoot>
									<!--
								<tr>
									<th colspan="4">
									</th>
									<th class="text-center"><small>R$ <?php echo fnValor($totalResg, 2); ?></small></th>
									<th colspan="3">
									</th>
								</tr>														
							-->
									<tr>
										<th colspan="100">
											<a class="btn btn-info btn-sm exportarCSV"><i class="fa fa-file-excel" aria-hidden="true"></i>&nbsp; Exportar </a>
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

						</div>
					</div>
				</div>

				<div class="push"></div>

			</div>

		</div>
	</div>
	<!-- fim Portlet -->

</div>

<div class="push20"></div>

<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />

<script type="text/javascript">
	$('.datePicker').datetimepicker({
		format: 'DD/MM/YYYY',
		maxDate: 'now',
	}).on('changeDate', function(e) {
		$(this).datetimepicker('hide');
	});

	$("#DAT_INI_GRP").on("dp.change", function(e) {
		$('#DAT_FIM_GRP').data("DateTimePicker").minDate(e.date);
	});

	$("#DAT_FIM_GRP").on("dp.change", function(e) {
		$('#DAT_INI_GRP').data("DateTimePicker").maxDate(e.date);
	});

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
										url: "relatorios/ajxRelResgates.do?opcao=exportar&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>",
										data: $('#formulario').serialize(),
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
			url: "relatorios/ajxRelResgates.do?opcao=paginar&id=<?php echo fnEncode($cod_empresa); ?>&idPage=" + idPage + "&itens_por_pagina=<?php echo $itens_por_pagina; ?>",
			data: $('#formulario').serialize(),
			beforeSend: function() {
				$('#relatorioConteudo').html('<div class="loading" style="width: 100%;"></div>');
			},
			success: function(data) {
				$("#relatorioConteudo").html(data);
				//console.log(data);
			},
			error: function() {
				$('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
			}
		});
	}
</script>