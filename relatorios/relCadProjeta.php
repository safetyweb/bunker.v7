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
$dat_fim_proj = "";
$num_cgcecpf = "";
$nom_cliente = "";
$hHabilitado = "";
$hashForm = "";
$cod_campanha = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$cod_univendUsu = "";
$qtd_univendUsu = 0;
$lojasAut = "";
$usuReportAdm = "";
$lojasReportAdm = "";
$formBack = "";
$qrTotal = "";
$qtd_total_indica = 0;
$andNome = "";
$andCpf = "";
$inicio = "";
$countPie = "";
$qrApoia = "";
$totalIndica = 0;
$pct_cadIndica = "";
$pct_cadIndicaTot = "";
$min_dat_cadastr = "";
$max_dat_cadastr = "";
$dStart = "";
$dEnd = "";
$dDiff = "";
$dDiffQtd = "";
$hojeAtual = "";
$dStartProj = "";
$dEndProj = "";
$dDiffProj = "";
$dDiffProjQtd = "";
$qtdIndicaZero = 0;
$qtdIndica = 0;
$txtSaldo = "";
$regraProj = "";
$regraProjTot = "";
$regraPrevTot = "";
$regraDifTot = "";
$txtDif = "";
$lojasSelecionadas = "";
$i = "";


$itens_por_pagina = 50;
$pagina = 1;
$hashLocal = mt_rand();

//inicialização de variáveis
$hoje = fnFormatDate(date("Y-m-d"));
$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30 . '- 1 days')));

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_empresa = fnLimpaCampoZero(@$_POST['COD_EMPRESA']);
		$cod_univend = @$_POST['COD_UNIVEND'];
		$dat_ini = fnDataSql(@$_POST['DAT_INI']);
		$dat_fim = fnDataSql(@$_POST['DAT_FIM']);
		$dat_fim_proj = fnDataSql(@$_POST['DAT_FIM_PROJ']);
		$cod_grupotr = @$_REQUEST['COD_GRUPOTR'];
		$cod_tiporeg = @$_REQUEST['COD_TIPOREG'];
		$num_cgcecpf = fnLimpaCampo(fnLimpaDoc(@$_REQUEST['NUM_CGCECPF']));
		$nom_cliente = fnLimpaCampo(@$_REQUEST['NOM_CLIENTE']);

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		if ($opcao != '') {
		}
	}
}

//busca dados url
if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode(@$_GET['id']);
	$cod_campanha = fnDecode(@$_GET['idc']);
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
//fnEscreve($dat_ini);
//fnEscreve($dat_fim);
//fnEscreve($dat_fim_proj);
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

	.corFundo {
		background: #F2F3F4;
	}
</style>

<div class="push30"></div>

<div class="row" id="div_Report">

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="glyphicon glyphicon-calendar"></i>
					<span class="text-primary"> <?php echo $NomePg; ?></span>
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

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Filtros</legend>

							<div class="row">

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Empresa</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa ?>">
										<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Data Projeção</label>

										<div class="input-group date datePicker2" id="DAT_FIM_PROJ_GRP">
											<input type='text' class="form-control input-sm data" name="DAT_FIM_PROJ" id="DAT_FIM_PROJ" value="<?php echo fnFormatDate($dat_fim_proj); ?>" />
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
										</div>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="push20"></div>
									<button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-sm btn-block getBtn"><i class="fa fa-calendar-alt" aria-hidden="true"></i>&nbsp; Projetar Cadastros</button>
								</div>

							</div>

						</fieldset>

						<div class="push20"></div>

						<?php
						$sql = "SELECT COUNT(COD_CLIENTE) QTD_TOTAL_INDICA
								FROM CLIENTES A
								WHERE A.COD_EMPRESA=$cod_empresa AND
								A.COD_INDICAD > 0
								";

						//fnEscreve($sql);
						$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

						$qrTotal = mysqli_fetch_assoc($arrayQuery);

						$qtd_total_indica =  $qrTotal['QTD_TOTAL_INDICA'];
						//fnEscreve($qtd_total_indica);

						?>

						<div class="push20"></div>

						<div>
							<div class="row">
								<div class="col-md-12">

									<div class="push20"></div>

									<table class="table table-bordered table-hover">

										<thead>


											<tr>
												<th class="text-center"></th>
												<th class="text-center" colspan="5">Projeção Aritmética</th>
												<th class="text-center" colspan="4">Projeção Metas</th>
											</tr>

											<tr>
												<th>Colaborador</th>
												<th class="text-center"><small>Qtd. Indicações Até <?php echo date('d/m/Y'); ?></th>
												<th class="text-center"><small>Dias de Cadastros</th>
												<th class="text-center"><small>Pct. Atual</th>
												<th class="text-center"><small>Dias Projetado</th>
												<th class="text-center"><small>Qtd. Projetada</th>
												<th class="text-center corFundo"><small>Dias Efetivos</th>
												<th class="text-center corFundo"><small>Previsão</th>
												<th class="text-center corFundo"><small>Realizado</th>
												<th class="text-center corFundo"><small>Saldo</th>
											</tr>



										</thead>

										<tbody id="relatorioConteudo">

											<?php

											if ($nom_cliente != '' && $nom_cliente != 0) {
												$andNome = "AND CL.NOM_CLIENTE LIKE '%$nom_cliente%'";
											} else {
												$andNome = "";
											}

											if ($num_cgcecpf != '' && $num_cgcecpf != 0) {
												$andCpf = "AND CL.NUM_CGCECPF = '$num_cgcecpf'";
											} else {
												$andCpf = "";
											}

											$numPaginas = 1;

											//variavel para calcular o início da visualização com base na página atual
											$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

											// Filtro por Grupo de Lojas
											// include "filtroGrupoLojas.php";

											$sql = "SELECT A.COD_INDICAD,
													  (SELECT B.DAT_ADMISSAO FROM CLIENTES B WHERE B.COD_CLIENTE=A.COD_INDICAD) AS DAT_ADMISSAO,
														 MIN(A.DAT_CADASTR) as MIN_DAT_CADASTR, 
														 MAX(A.DAT_CADASTR) as MAX_DAT_CADASTR, 
														(SELECT B.NOM_CLIENTE FROM CLIENTES B WHERE B.COD_CLIENTE=A.COD_INDICAD) INDICADOR,
																		TIMESTAMPDIFF(DAY,(SELECT B.DAT_ADMISSAO FROM CLIENTES B WHERE B.COD_CLIENTE=A.COD_INDICAD),NOW()) AS DIAS,
																		
																		ROUND((TIMESTAMPDIFF(DAY,(SELECT B.DAT_ADMISSAO FROM CLIENTES B WHERE B.COD_CLIENTE=A.COD_INDICAD),NOW())/7*(SELECT B.QTD_CADASTROS FROM CLIENTES B WHERE B.COD_CLIENTE=A.COD_INDICAD))-(SELECT B.QTD_DIASOFF FROM CLIENTES B WHERE B.COD_CLIENTE=A.COD_INDICAD)) AS PREVISAO,
																		COUNT(*) QTD_INDICA,
																		COUNT(*)-ROUND((TIMESTAMPDIFF(DAY,(SELECT B.DAT_ADMISSAO FROM CLIENTES B WHERE B.COD_CLIENTE=A.COD_INDICAD),NOW())/7*(SELECT B.QTD_CADASTROS FROM CLIENTES B WHERE B.COD_CLIENTE=A.COD_INDICAD))-(SELECT B.QTD_DIASOFF FROM CLIENTES B WHERE B.COD_CLIENTE=A.COD_INDICAD)) AS DIFERENCA       
												FROM CLIENTES A
												WHERE A.COD_EMPRESA = $cod_empresa AND 
														A.COD_INDICAD > 0 
												GROUP BY A.COD_INDICAD 
												ORDER BY COUNT(*) DESC LIMIT 0,50
												";

											//fnEscreve($sql);
											//echo($sql);
											//fnTestesql(connTemp($cod_empresa,''),$sql);											
											$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

											$count = 0;
											$countPie = 1;
											while ($qrApoia = mysqli_fetch_assoc($arrayQuery)) {

												$count++;
												$totalIndica = $totalIndica + $qrApoia['QTD_INDICA'];
												$pct_cadIndica = ($qrApoia['QTD_INDICA'] * 100) / $qtd_total_indica;
												$pct_cadIndicaTot = $pct_cadIndicaTot + $pct_cadIndica;

												$min_dat_cadastr = $qrApoia['MIN_DAT_CADASTR'];
												$max_dat_cadastr = $qrApoia['MAX_DAT_CADASTR'];

												$dStart = new DateTime($min_dat_cadastr);
												$dEnd  = new DateTime($max_dat_cadastr);
												$dDiff = $dStart->diff($dEnd);
												$dDiffQtd = $dDiff->format('%r%a');
												if ($dDiffQtd == 0) {
													$dDiffQtd = 1;
												}

												$hojeAtual = date('Y-m-d');
												$dStartProj = new DateTime($hojeAtual);
												$dEndProj  = new DateTime($dat_fim_proj);
												$dDiffProj = $dStartProj->diff($dEndProj);
												$dDiffProjQtd = $dDiffProj->format('%r%a');

												if ($qrApoia['QTD_INDICA'] == 0) {
													$qtdIndicaZero = 1;
												} else {
													$qtdIndicaZero = $qrApoia['QTD_INDICA'];
												};
												$qtdIndica = $qtdIndicaZero;

												if ($qrApoia['DIFERENCA'] > 0) {
													$txtSaldo = 'text-success';
												} else {
													$txtSaldo = 'text-danger';
												};

												$regraProj = ($dDiffProjQtd * $qrApoia['QTD_INDICA']) / $dDiffQtd;
												$regraProjTot = $regraProjTot + $regraProj;
												$regraPrevTot = $regraPrevTot + $qrApoia['PREVISAO'];
												$regraDifTot = $regraDifTot + $qrApoia['DIFERENCA'];

												echo "
												<tr>
												  <td><small>" . $qrApoia['INDICADOR'] . "</small></td>
												  <td class='text-center'><small>" . $qrApoia['QTD_INDICA'] . "</small></td>
												  
												  <td class='text-center'>" . $dDiffQtd . "</small></td>
												  
												  <td class='text-center'><small>
												  " . fnValor($pct_cadIndica, 2) . "% 
												  </td>
												  
												  <td class='text-center'><small>" . $dDiffProjQtd . "</small></td>
												  
												  <td class='text-center'><small>" . fnValor($regraProj, 0) . "</small></td>
												  
												  <td class='text-center corFundo'><small>" . $qrApoia['DIAS'] . "</small></td>
												  <td class='text-center corFundo'><small>" . $qrApoia['PREVISAO'] . "</small></td>
												  <td class='text-center corFundo'><small>" . $qrApoia['QTD_INDICA'] . "</small></td>
												  <td class='text-center corFundo " . $txtSaldo . " '><small>" . $qrApoia['DIFERENCA'] . "</small></td>
												  
												</tr>
												";
												$countPie++;
												/*
												<div id='pie-".$countPie."' class='pie-title-center' data-percent='".fnValor($pct_cadIndica,0)."'>
													<span class='pie-value'>".fnValor($pct_cadIndica,2)."%</span>
												</div>
												*/
											}

											if ($regraDifTot > 0) {
												$txtDif = 'text-success';
											} else {
												$txtDif = 'text-danger';
											};


											?>
										</tbody>

										<tfoot>
											<tr>
												<th>
													<b>TOTAL</b>
												</th>
												<th class="text-center">
													<?php echo $totalIndica; ?>
												</th>
												<th class="text-center"></th>
												<th class="text-center">
													<?php echo fnValor($pct_cadIndicaTot, 2); ?>%
												</th>
												<th class="text-center"></th>
												<th class="text-center">
													<?php echo fnValor($regraProjTot, 0); ?>
												</th>
												<th class="text-center corFundo"></th>
												<th class="text-center corFundo">
													<?php echo fnValor($regraPrevTot, 0); ?>
												</th>
												<th class="text-center corFundo">
													<?php echo $totalIndica; ?>
												</th>
												<th class="text-center corFundo <?php echo $txtDif; ?>">
													<?php echo $regraDifTot; ?>
												</th>
											</tr>
											<!--
											<tr>
											  <th class="" colspan="100">
												<center><ul id="paginacao" class="pagination-sm"></ul></center>
											  </th>
											</tr>
											-->
										</tfoot>

									</table>

								</div>


							</div>
						</div>

						<input type="hidden" name="LOJAS" id="LOJAS" value="<?php echo $lojasSelecionadas; ?>" />
						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

						<div class="push5"></div>

					</form>

					<div class="push50"></div>

					<div class="push"></div>

				</div>

			</div>
		</div>
		<!-- fim Portlet -->
	</div>

</div>

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

		$('.datePicker2').datetimepicker({
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


	});

	function reloadPage(idPage) {
		$.ajax({
			type: "POST",
			url: "relatorios/ajxCadApoiador.do?id=<?php echo fnEncode($cod_empresa); ?>&idPage=" + idPage + "&itens_por_pagina=<?php echo $itens_por_pagina; ?>&opcao=paginar",
			data: $('#formulario').serialize(),
			beforeSend: function() {
				$('#relatorioConteudo').html('<div class="loading" style="width: 100%;"></div>');
			},
			success: function(data) {
				$("#relatorioConteudo").html(data);
				console.log(data);
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


	//graficos
	$(document).ready(function() {

		//chosen
		$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
		$('#formulario').validator();

		/*
            $('#main-pie').pieChart({
                barColor: '#2c3e50',
                trackColor: '#eee',
                lineCap: 'round',
                lineWidth: 8,
                onStep: function (from, to, percent) {
                    $(this.element).find('.pie-value').text(Math.round(percent) + '%');
                }
            });
			*/

		<?php
		//fnEscreve($countPie-1);
		//for ($i=1; $i < ($countPie); $i++) {
		?>
		/*	
		$('#pie-<?php echo $i; ?>').pieChart({
			barColor: '#3bb2d0',
			trackColor: '#eee',
			lineCap: 'round',
			lineWidth: 8,
			onStep: function (from, to, percent) {
				$(this.element).find('.pie-value').text(Math.round(percent) + '%');
			}
		});	
		*/
		<?php
		//}
		?>

	});
</script>