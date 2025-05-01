<?php

//echo fnDebug('true');

// definir o numero de itens por pagina
$itens_por_pagina = 50;

// Página default
$pagina = 1;

$dias30 = "";
$dat_ini = "";
$dat_fim = "";
$hashLocal = mt_rand();

$conn = connTemp($cod_empresa, '');
$adm = $connAdm->connAdm();

//inicialização de variáveis
$hoje = fnFormatDate(date("Y-m-d"));
$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30 . '- 1 days')));

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_empresa = fnLimpaCampoZero($_POST['COD_EMPRESA']);
		$cod_univend = $_POST['COD_UNIVEND'];
		$dat_ini = fnDataSql($_POST['DAT_INI']);
		$dat_fim = fnDataSql($_POST['DAT_FIM']);

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		if ($opcao != '') {
		}
	}
}

//busca dados url
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
	$cod_empresa = 0;
	$nom_empresa = "";
}

//inicialização das variáveis - default	
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
	$dat_ini = fnDataSql($dias30);
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
	$dat_fim = fnDataSql($hoje);
}

//busca revendas do usuário
include "unidadesAutorizadas.php";

//fnMostraForm();	
//fnEscreve($dat_ini);
//fnEscreve($dat_fim);
//fnEscreve($cod_univendUsu);
//fnEscreve($qtd_univendUsu);
//fnEscreve($lojasAut);
//fnEscreve($usuReportAdm);
//fnEscreve($lojasReportAdm);

?>

<style>
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

<div class="row" id="div_Report">

	<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

		<div class="col-md12 margin-bottom-30">
			<!-- Portlet -->
			<div class="portlet portlet-bordered">
				<div class="portlet-title">
					<div class="caption">
						<i class="fal fa-terminal"></i>
						<span class="text-primary"> <?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
					</div>

					<?php
					//$formBack = "1015";
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


					<div class="login-form">

						<fieldset>
							<legend>Filtros</legend>

							<div class="row">

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Unidade de Atendimento</label>
										<?php include "unidadesAutorizadasComboMulti.php"; ?>
									</div>
								</div>

								<div class="col-md-2">
									<div class="push20"></div>
									<button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-sm btn-block getBtn"><i class="fal fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
								</div>


							</div>

						</fieldset>
					</div>
				</div>
			</div>

			<div class="push20"></div>

			<div class="portlet portlet-bordered">

				<div class="portlet-body">

					<div class="login-form">

						<div class="push20"></div>

						<div id="relatorioConteudo">
							<div class="row">
								<div class="col-md-12" id="div_Produtos">

								<div class="no-more-tables">
										
									<form name="formLista">	

									<table class="table table-bordered table-striped table-hover tablesorter ">

										<thead>
											<tr>
												<th class="{sorter:false} text-center"><small>Ativo</small></th>
												<th class="{sorter:false}"><small>Cód. Externo </small></th>
												<th><small>Produto Ticket </small></th>
												<th><small>Unidade</small></th>
												<th><small>Categoria</small></th>
												<th><small>Validade</small></th>
												<th class="{sorter:false}">Persona</th>	
												<th><small>De </small></th>
												<th><small>Por </small></th>
												<th><small>Un. Aut. </small></th>
												<th><small>Un. Não Aut.</small></th>
											</tr>
										</thead>
										<tbody>

											<?php
											
											include "filtrogrupolojas.php";

											$sql = "SELECT 	PRODUTOCLIENTE.DES_PRODUTO,
															PRODUTOCLIENTE.COD_EXTERNO,
															PRODUTOCLIENTE.COD_PRODUTO AS PRODUTO,
															DESCONTOTKT.ABV_DESCTKT,
															IF(PRODUTOCLIENTE.DES_IMAGEM <> '','S','N') AS TEM_IMAGEM,
															produtotkt.COD_PRODUTO, produtotkt.DAT_INIPTKT,
															produtotkt.DAT_FIMPTKT, produtotkt.PCT_DESCTKT,
															produtotkt.VAL_PRODTKT, produtotkt.VAL_PROMTKT, 
															GROUP_CONCAT(DISTINCT CASE WHEN produtotkt.COD_UNIVEND_AUT = 0 THEN 'TODAS AS UNIDADES AUTORIZADAS' ELSE UNI.NOM_FANTASI END SEPARATOR ',') COD_UNIVEND_AUT,
															GROUP_CONCAT(DISTINCT CASE WHEN produtotkt.COD_UNIVEND_BLK = 0 THEN 'TODAS AS UNIDADES AUTORIZADAS' ELSE UNI.NOM_FANTASI END SEPARATOR ',') COD_UNIVEND_BLK,
															produtotkt.LOG_PRODTKT,
															produtotkt.COD_DESCTKT,
															categoriatkt.COD_CATEGORTKT,
															categoriatkt.DES_ABREVIA, categoriatkt.COD_CATEGORTKT,
															categoriatkt.DES_CATEGOR, produtotkt.COD_PERSONA_TKT,
															produtotkt.LOG_ATIVOTK, GROUP_CONCAT(DISTINCT P.DES_PERSONA SEPARATOR ',') DES_PERSONA
													FROM PRODUTOTKT
													LEFT JOIN categoriatkt ON categoriatkt.COD_CATEGORTKT = PRODUTOTKT.COD_CATEGORTKT
													LEFT JOIN DESCONTOTKT ON DESCONTOTKT.COD_DESCTKT = PRODUTOTKT.COD_DESCTKT
													INNER JOIN PRODUTOCLIENTE ON PRODUTOCLIENTE.COD_PRODUTO = PRODUTOTKT.COD_PRODUTO
													INNER JOIN persona P ON P.COD_PERSONA = produtotkt.COD_PERSONA_TKT
													LEFT JOIN unidadevenda UNI ON  find_in_set(UNI.COD_UNIVEND,produtotkt.COD_UNIVEND_AUT)
													WHERE PRODUTOTKT.COD_PRODUTO = PRODUTOCLIENTE.COD_PRODUTO AND PRODUTOTKT.COD_EMPRESA = 85 
													AND produtotkt.LOG_ATIVOTK = 'S'
													
													GROUP BY PRODUTOCLIENTE.COD_PRODUTO
													ORDER BY DES_CATEGOR, NOM_PRODTKT ";

											//fnEscreve($sql);
											//fnTestesql(connTemp($cod_empresa,''),$sql);

											$arrayQuery = mysqli_query($conn, $sql);

											$count = 0;

											while ($qrBuscaProdutosTkt = mysqli_fetch_assoc($arrayQuery)) {
												$count++;

												if ($qrBuscaProdutosTkt['LOG_ATIVOTK'] == "S") {
													$mostraLOG_ATIVOTK = '<i class="fa fa-check" aria-hidden="true"></i>';
												} else {
													$mostraLOG_ATIVOTK = '';
												}
												if ($qrBuscaProdutosTkt['DAT_FIMPTKT'] != "") {
															
													$mostraValidade = '';															
													$mostraValidadeHora = '';	
													$textoDanger = '';															
													if (date('Y-m-d h:i:s') > $qrBuscaProdutosTkt['DAT_FIMPTKT'] ) {
														//$mostraValidade = '<i class="fa fa-check-o" aria-hidden="true"></i>';	
														//$mostraValidade = ''.fnFormatDateTime($qrBuscaProdutosTkt['DAT_FIMPTKT']);
														$mostraValidade = ''.fnFormatDate($qrBuscaProdutosTkt['DAT_FIMPTKT']);
														$mostraValidadeHora = fnFormatDateTime($qrBuscaProdutosTkt['DAT_FIMPTKT']);
														$textoDanger = "text-danger";		
													}else{ 
														//$mostraValidade = fnFormatDateTime($qrBuscaProdutosTkt['DAT_FIMPTKT']); 
														$mostraValidade = fnFormatDate($qrBuscaProdutosTkt['DAT_FIMPTKT']); 
														$mostraValidadeHora = fnFormatDateTime($qrBuscaProdutosTkt['DAT_FIMPTKT']);
														$textoDanger = "text-success";		
													}
													$e = explode(" ",$mostraValidadeHora);
													$mostraValidadeHora = @$e[1];
													
												}else{ 
													$mostraValidade = ''; 
													$mostraValidadeHora = '';
												}	
												

												//fnEscreve($qrBuscaProdutosTkt['TEM_IMAGEM']);
												//fnEscreve($qrBuscaProdutosTkt['DAT_INIPTKT']);
												echo "
												<tr>
												  <td class='text-center'><small>" . $mostraLOG_ATIVOTK . "</small></td>
												  <td><small>" . $qrBuscaProdutosTkt['COD_EXTERNO'] . "</small></td>
												  <td><a href='action.do?mod=" . fnEncode(1046) . "&id=" . fnEncode($cod_empresa) . "&idP=" . $qrBuscaProdutosTkt['COD_EXTERNO'] . "'></small>" . $qrBuscaProdutosTkt['DES_PRODUTO'] . "</small></a></td>
												  <td><small>" . $qrBuscaProdutosTkt['COD_UNIVEND_AUT'] . "</small></td> 
												  <td><small>" . $qrBuscaProdutosTkt['DES_CATEGOR'] . "</small></td>
												  <td class='".$textoDanger." dt-validade'>
												  	<small>
														<a href='#' class='editable editable-click ".$textoDanger."' data-type='date' data-format='dd/mm/yyyy' data-clear='false' data-empresa='$cod_empresa' data-pk='".$qrBuscaProdutosTkt['COD_PRODTKT']."' data-title='Editar'>$mostraValidade</a> $mostraValidadeHora
													</small>
												  </td>
												  <td><small>" . $qrBuscaProdutosTkt['DES_PERSONA'] . "</small></td>
												  <td class='text-center'><small>" . fnValor($qrBuscaProdutosTkt['VAL_PRODTKT'], 2) . "</small></td>
												  <td class='text-center'><small>" . fnValor($qrBuscaProdutosTkt['VAL_PROMTKT'], 2) . "</small></td>
												  <td class='text-center'><small>" . $mostraCOD_UNIVEND_AUT . "</small></td>
												  <td class='text-center'><small>" . $mostraCOD_UNIVEND_BLK . "</small></td>
												</tr>
												";
											}

											?>
										</tbody>

										<tfoot>
											<td class="text-left">
												<small>
													<div class="btn-group dropdown left">
														<button type="button" class="btn btn-info exportarCSV" aria-haspopup="true" aria-expanded="false"><i class="fal fa-file-excel"></i>
															&nbsp; Exportar &nbsp;
														</button>
													</div>
												</small>
											</td>																		
										</tfoot>

									</table>

								</div>


							</div>
						</div>

						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
						<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
						<div class="push5"></div>



						<div class="push50"></div>

						<div class="push"></div>

					</div>

				</div>
			</div>
			<!-- fim Portlet -->
		</div>
	</form>
</div>

<div class="push20"></div>

<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />

<script>
	//datas
	$(function() {

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
							action: function () {
								var nome = this.$content.find('.nome').val();
								if(!nome){
									$.alert('Por favor, insira um nome');
									return false;
								}
								
								$.confirm({
									title: 'Mensagem',
									type: 'green',
									icon: 'fa fa-check-square-o',
									content: function(){
										var self = this;
										return $.ajax({
											url: "relatorios/ajxProdutosDestaque.do?opcao=exportar&nomeRel="+nome+"&id=<?php echo fnEncode($cod_empresa); ?>", 
											data: $('#formulario').serialize(),
											method: 'POST'
										}).done(function (response) {
											self.setContentAppend('<div>Exportação realizada com sucesso.</div>');
											var fileName = '<?php echo $cod_empresa; ?>_' + nome + '.csv';
											SaveToDisk('media/excel/' + fileName, fileName);
											console.log(response);
										}).fail(function(){
											self.setContentAppend('<div>Erro ao realizar o procedimento!</div>');
										});
									},							
									buttons: {
										fechar: function () {
											//close
										}									
									}
								});								
							}
						},
						cancelar: function () {
							//close
						},
					}
				});				
			});

	});

	function reloadPage(idPage) {
		$.ajax({
			type: "POST",
			url: "relatorios/ajxVendasGeral.do?id=<?php echo fnEncode($cod_empresa); ?>&idPage=" + idPage + "&itens_por_pagina=<?php echo $itens_por_pagina; ?>",
			data: $('#formulario').serialize(),
			beforeSend: function() {
				$('#relatorioConteudo').html('<div class="loading" style="width: 100%;"></div>');
			},
			success: function(data) {
				$("#relatorioConteudo").html(data);
			},
			error: function() {
				$('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
			}
		});
	}

	function abreDetail(idBloco) {
		var idItem = $('.abreDetail_' + idBloco)
		if (!idItem.is(':visible')) {
			idItem.show();
			$('#bloco_' + idBloco).find($(".fa")).removeClass('fa-angle-right').addClass('fa-angle-down');
		} else {
			idItem.hide();
			$('#bloco_' + idBloco).find($(".fa")).removeClass('fa-angle-down').addClass('fa-angle-right');
		}
	}
</script>