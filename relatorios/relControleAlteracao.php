<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$hashLocal = "";
$hoje = "";
$dias30 = "";
$msgRetorno = "";
$msgTipo = "";
$dat_ini = "";
$dat_fim = "";
$opcao = "";
$hHabilitado = "";
$hashForm = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$cod_cliente = "";
$qrListaGrupoWork = "";
$selecionado = "";
$qrListaUniVendas = "";
$grupoTrabalho = "";
$lojasSelecionadas = "";
$retorno = "";
$inicio = "";
$countLinha = "";
$qrListaVendas = "";
$atualizado = "";
$loja = "";
$content = "";


$hashLocal = mt_rand();

//inicialização de variáveis
//$hoje = fnFormatDate(date("Y-m-d"));
$hoje = fnFormatDate(date('Y-m-d'));
//$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 30 days')));
$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30 . '- 2 days')));

// definir o numero de itens por pagina
$itens_por_pagina = 50;
$pagina  = "1";

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
		$dat_ini = fnDataSql(@$_POST['DAT_INI']);
		$dat_fim = fnDataSql(@$_POST['DAT_FIM']);

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		if ($opcao != '' && $opcao != 0) {
		}
	}
}

//busca dados url
if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode(@$_GET['id']);
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
//fnEscreve($cod_cliente);

?>

<div class="push30"></div>

<div class="row">

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
					include "backReport.php";
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

					<div class="push30"></div>

					<div class="login-form">

						<fieldset>
							<legend>Filtros</legend>

							<div class="row">

								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label required">Unidade de Atendimento</label>
										<?php include "unidadesAutorizadasComboMulti.php"; ?>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Grupo de Lojas</label>
										<select data-placeholder="Selecione um grupo de lojas" name="COD_GRUPOTR" id="COD_GRUPOTR" class="chosen-select-deselect">
											<option value=""></option>
											<?php
											$sql = "select * from grupotrabalho where cod_empresa = $cod_empresa order by DES_GRUPOTR";
											$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
											while ($qrListaGrupoWork = mysqli_fetch_assoc($arrayQuery)) {
												if ($cod_grupotr == $qrListaGrupoWork['COD_GRUPOTR']) {
													$selecionado = "selected";
												} else {
													$selecionado = "";
												}

												echo "
																				  <option value='" . $qrListaGrupoWork['COD_GRUPOTR'] . "' " . $selecionado . " >" . $qrListaGrupoWork['DES_GRUPOTR'] . "</option> 
																				";
											}
											?>
										</select>
										<div class="help-block with-errors"></div>
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

								<div class="col-md-2 pull-right">
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


						<div class="row">

							<div class="col-md-12" id="div_Produtos">

								<div class="push20"></div>

								<table class="table table-bordered table-hover  ">

									<thead>
										<tr>
											<th><small>Loja</small></th>
											<th><small>Vendedor</small></th>
											<th><small>Cliente</small></th>
											<th><small>CPF</small></th>
											<th><small>Campo</small></th>
											<th><small>Dado Atualizado</small></th>
											<th><small>Data Cadastro</small></th>
											<th><small>Data Controle</small></th>
											<th><small>Tipo</small></th>
										</tr>
									</thead>

									<tbody id="relatorioConteudo">

										<?php
										//============================
										/*$ARRAY_UNIDADE1=array(
														   'sql'=>"select COD_UNIVEND,cod_empresa,nom_fantasi,NOM_UNIVEND from unidadevenda where cod_empresa=$cod_empresa  and cod_exclusa=0",
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
										$ARRAY_VENDEDOR1 = array(
											'sql' => "select COD_EXTERNO,COD_USUARIO,NOM_USUARIO from usuarios where cod_empresa=$cod_empresa",
											'cod_empresa' => $cod_empresa,
											'conntadm' => $connAdm->connAdm(),
											'IN' => 'N',
											'nomecampo' => '',
											'conntemp' => '',
											'SQLIN' => ""
										);
										$ARRAY_VENDEDOR = fnUniVENDEDOR($ARRAY_VENDEDOR1);

										// echo '<pre>';
										//  print_r($ARRAY_VENDEDOR);
										//	echo '</pre>'; 
										//      exit();

										//busca por grupo de trabalho
										if (!empty($cod_grupotr)) {
											$sql = "select COD_UNIVEND from unidadevenda where COD_EMPRESA = '" . $cod_empresa . "' and COD_GRUPOTR = '" . $cod_grupotr . "' and cod_exclusa =0 order by trim(NOM_FANTASI)";
											$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
											while ($qrListaUniVendas = mysqli_fetch_assoc($arrayQuery)) {
												$grupoTrabalho .= $qrListaUniVendas['COD_UNIVEND'] . ",";
											}
											//substitui lojas selecionadas
											$lojasSelecionadas = substr($grupoTrabalho, 0, -1);
										}

										$sql = "select COUNT(*) CONTADOR
												from historico_atualizacao A
												left join  clientes C on A.NUM_CGCECPF=C.NUM_CGCECPF
												where A.COD_EMPRESA = $cod_empresa and 
												A.COD_UNIVEND in ($lojasSelecionadas) and
												A.COD_ATUALIZADO in (0,1,2) and
												A.data_hora between '$dat_ini 00:00:00' and '$dat_fim 23:59:59' 
												ORDER BY A.data_hora DESC ";

										//fnEscreve($sql);
										//fnTestesql(connTemp($cod_empresa,''), $sql);
										$retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
										$total_itens_por_pagina = mysqli_fetch_assoc($retorno);

										$numPaginas = ceil($total_itens_por_pagina['CONTADOR'] / $itens_por_pagina);

										//variavel para calcular o início da visualização com base na página atual
										$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

										$sql = "select A.data_hora,
														A.CAMPOS_ATUALIZ,
														A.COD_EMPRESA,
														A.DADOS_ATUALIZADOS,
														A.NUM_CGCECPF,
														A.VENDEDOR,
														A.COD_UNIVEND,
														uni.NOM_FANTASI,
														C.NOM_CLIENTE,
														A.COD_ATUALIZADO,
														C.DAT_CADASTR
														FROM historico_atualizacao A
														LEFT JOIN  clientes C on A.NUM_CGCECPF=C.NUM_CGCECPF
														LEFT JOIN unidadevenda uni ON uni.COD_UNIVEND=A.COD_UNIVEND
														WHERE A.COD_EMPRESA = $cod_empresa AND 
														A.COD_UNIVEND in ($lojasSelecionadas) AND
														A.COD_ATUALIZADO in (0,1,2) AND
														A.data_hora between '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' 
														ORDER BY A.data_hora DESC LIMIT $inicio,$itens_por_pagina";

										//fnEscreve($sql);
										$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

										$countLinha = 1;
										while ($qrListaVendas = mysqli_fetch_assoc($arrayQuery)) {
											//echo $qrListaVendas['VENDEDOR']; 
											/*$NOM_ARRAY_UNIDADE=(array_search($qrListaVendas['COD_UNIVEND'], array_column($ARRAY_UNIDADE, 'COD_UNIVEND')));
                                                                                                                         * 
                                                                                                                         */
											$NOM_ARRAY_NON_VENDEDOR = (array_search($qrListaVendas['VENDEDOR'], array_column($ARRAY_VENDEDOR, 'COD_EXTERNO')));

											switch ($qrListaVendas['COD_ATUALIZADO']) {
												case 0: //cadastro histórico
													$atualizado = "histórico";
													break;
												case 1: //quando ja existe cadastro, mas o cep válido é adicionado
													$atualizado = "atual.";
													break;
												case 2: //campo novo (novo cadastro ou já existente)
													$atualizado = "novo";
													break;
											}

											//fnEscreve($loja);
										?>
											<tr style="background-color: #fff;" class="abreDetail_<?php echo $qrListaVendas['COD_UNIVEND']; ?>">
												<td><small><?php echo $qrListaVendas['NOM_FANTASI']; ?></small></small></td>
												<td><small><?php echo $ARRAY_VENDEDOR[$NOM_ARRAY_NON_VENDEDOR]['NOM_USUARIO']; ?></small></small></td>
												<td><small><?php echo $qrListaVendas['NOM_CLIENTE']; ?></small></td>
												<td><small><?php echo $qrListaVendas['NUM_CGCECPF']; ?></small></td>
												<td><small><?php echo $qrListaVendas['CAMPOS_ATUALIZ']; ?></small></td>
												<td><small><?php echo $qrListaVendas['DADOS_ATUALIZADOS']; ?></small></td>
												<td><small><?php echo fnDataFull($qrListaVendas['DAT_CADASTR']); ?></small></td>
												<td><small><?php echo fnDataFull($qrListaVendas['data_hora']); ?></small></td>
												<td><small><?php echo $atualizado; ?></small></td>
											</tr>
										<?php

											$countLinha++;
										}


										//fnEscreve($countLinha-1);				
										?>

									</tbody>

									<tfoot>
										<tr>
											<th colspan="100">
												<a class="btn btn-info btn-sm exportarCSV"><i class="fa fa-file-excel" aria-hidden="true"></i>&nbsp; Exportar </a> &nbsp;&nbsp;
											</th>
										</tr>
										<th class="" colspan="100">
											<center>
												<ul id="paginacao" class="pagination-sm"></ul>
											</center>
										</th>
										</tr>
									</tfoot>

								</table>

							</div>

						</div>

						<input type="hidden" name="NOVO_CLIENTE" id="NOVO_CLIENTE" value="">
						<input type="hidden" name="REFRESH_CLIENTE" id="REFRESH_CLIENTE" value="N">
						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
						<input type="hidden" name="LOJAS" id="LOJAS" value="<?php echo $lojasSelecionadas; ?>" />
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

<div class="push20"></div>


<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />

<script>
	//datas
	$(function() {

		var numPaginas = <?php echo $numPaginas; ?>;
		if (numPaginas != 0) {
			carregarPaginacao(numPaginas);
		}

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

		// Carregar totais de quantidade na linhas
		/*
		$("div[id^='total_col']").each(function( index ) {
			var total = 0;
			
			// Se não tiver a classe porcent faça
			if(!$(this).hasClass('porcent')){
				$(".qtde_col" + $(this).attr('id').replace('total_col','')).each(function(index, item) {
				  total += limpaValor($(this).text());
				});

				var totalVar = $('#' + $(this).attr('id'));
				totalVar.unmask();
				totalVar.text(total.toFixed(2));				 
				totalVar.mask("#.##0,00", {reverse: true});	

			}else{
				var numLinha = $(this).attr('id').replace('total_col3_', '');
				var result = limpaValor($('#total_col2_' + numLinha).text()) / (limpaValor($('#total_col1_' + numLinha).text()) + limpaValor($('#total_col2_' + numLinha).text())) * 100;
				var totalVar = $('#' + $(this).attr('id'));
				totalVar.unmask();					
				totalVar.text(result.toFixed(2));				 
				totalVar.mask("#.##0,00", {reverse: true});					
			}
		});	
		
		
		$("div[id^='total_col0']").each(function( index ) {
			$(this).text(parseFloat($(this).text()));
		});			

		$("div[id^='total_col1']").each(function( index ) {
			$(this).text(parseFloat($(this).text()));
		});
		
		$("div[id^='total_col2']").each(function( index ) {
			$(this).text(parseFloat($(this).text()));
		});		
		*/

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
										url: "relatorios/ajxRelControleAlteracao.do?opcao=exportar&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>&LOJAS=<?php echo $lojasSelecionadas; ?>",
										data: $('#formulario').serialize(),
										method: 'POST'
									}).done(function(response) {
										self.setContentAppend('<div>Exportação realizada com sucesso.</div>');
										var fileName = '<?php echo $cod_empresa; ?>_' + nome + '.csv';
										SaveToDisk('media/excel/' + fileName, fileName);
										//console.log(response);
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

	function reloadPage(idPage) {
		$.ajax({
			type: "POST",
			url: "relatorios/ajxRelControleAlteracao.do?opcao=paginar&id=<?php echo fnEncode($cod_empresa); ?>&idPage=" + idPage + "&itens_por_pagina=<?php echo $itens_por_pagina; ?>",
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