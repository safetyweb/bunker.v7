<?php

if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}

//fnMostraForm();
// definir o numero de itens por pagina
$itens_por_pagina = 50;
$pagina = 1;
$cod_cupom = '';
$tip_ordenac = '';
$dias30 = "";
$dat_ini = "";
$dat_fim = "";
$numCartao = "";
$nomCliente = "";
$cod_vendapdv = "";
$tipoVenda = "T";
$hashLocal = mt_rand();

//inicialização de variáveis
$hoje = fnFormatDate(date("Y-m-d", strtotime($dias30 . '- 1 month')));
//$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 1 days')));
$dias30 = fnFormatDate(date("Y-m-d", strtotime($dias30 . '- 2 months')));
//$cod_univend = "9999"; //todas revendas - default

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
		$numCartao = @$_POST['NUM_CARTAO'];
		$nomCliente = @$_POST['NOM_CLIENTE'];
		$cod_vendapdv = fnLimpaCampoZero(@$_POST['COD_VENDAPDV']);
		$cod_cupom = fnLimpaCampo(@$_POST['COD_CUPOM']);
		$tipoVenda = @$_POST['tipoVenda'];
		$tip_ordenac = fnLimpaCampoZero(@$_POST['TIP_ORDENAC']);

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
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI, COD_CLIENTE_AV, TIP_RETORNO FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
		$cod_cliente_av = $qrBuscaEmpresa['COD_CLIENTE_AV'];
		$tip_retorno = $qrBuscaEmpresa['TIP_RETORNO'];

		if ($tip_retorno == 1) {
			$casasDec = 0;
		} else {
			$casasDec = 2;
		}
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

if ($tipoVenda == "T") {
	$checkTodas = "checked";
	$checkCreditos = "";
} else {
	$checkTodas = "";
	$checkCreditos = "checked";
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

//fnMostraForm();	
//fnEscreve($dat_ini);
//fnEscreve($lojasSelecionadas);
//fnEscreve($cod_univendUsu);
//fnEscreve($qtd_univendUsu);
//fnEscreve($lojasAut);
//fnEscreve($usuReportAdm);
//fnEscreve($tipoVenda);

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


				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Filtros</legend>

							<div class="row">

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Código Venda PDV</label>
										<input type="text" class="form-control input-sm" name="COD_VENDAPDV" id="COD_VENDAPDV" value="<?php echo $cod_vendapdv; ?>">
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Cupom</label>
										<input type="text" class="form-control input-sm" name="COD_CUPOM" id="COD_CUPOM" value="<?php echo $cod_cupom; ?>">
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

							</div>

							<div class="push10"></div>

							<div class="row">

								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label required">Unidade de Atendimento</label>
										<?php include "unidadesAutorizadasComboMulti.php"; ?>
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label">Grupo de Lojas</label>
										<?php include "grupoLojasComboMulti.php"; ?>
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label">Região</label>
										<?php include "grupoRegiaoMulti.php"; ?>
									</div>
								</div>

							</div>

							<div class="row">

								<div class="col-md-2">
									<div class="push20"></div>
									<button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-sm btn-block getBtn"><i class="fal fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
								</div>

							</div>

						</fieldset>

						<div class="push20"></div>

						<div class="row">
							<div class="col-md-12" id="div_Produtos">

								<div class="push20"></div>

								<?php
								if ($nomCliente == "") {
									$andNome = " ";
								} else {
									$andNome = "AND NOM_CLIENTE LIKE '%" . $nomCliente . "%' ";
								}

								if ($tipoVenda == "T") {
									$andCreditos = " ";
								} else {
									$andCreditos = "AND A.COD_AVULSO = 2 ";
								}

								if ($numCartao == "") {
									$condicaoCartao = " ";
								} else {
									$condicaoCartao = "AND B.NUM_CARTAO = $numCartao ";
								}

								if ($cod_vendapdv == "0") {
									$condicaoVendaPDV = " ";
								} else {
									$condicaoVendaPDV = "AND A.COD_VENDAPDV = '" . $cod_vendapdv . "' ";
								}

								if ($cod_cupom == "") {
									$andCodCupom = " ";
								} else {
									$andCodCupom = "AND A.COD_CUPOM = '" . $cod_cupom . "' ";
								}

								if ($tip_ordenac == 1) {
									$orderBy = "ORDER BY VAL_TOTVENDA DESC";
								} else if ($tip_ordenac == 2) {
									$orderBy = "ORDER BY VAL_CREDITOS DESC";
								} else {
									$orderBy = "ORDER BY A.DAT_CADASTR_WS DESC";
								}

								if ($dat_fim == date('Y-m-d')) {
									$andDataRetro = " ";
								} else {
									$andDataRetro = "AND A.DAT_CADASTR < NOW() ";
								}

								// Filtro por Grupo de Lojas
								include "filtroGrupoLojas.php";

								$sql = "SELECT sum(1) as contador, 
													   SUM(A.VAL_TOTPRODU) AS VAL_TOTPRODU, 
													   SUM(A.VAL_TOTVENDA) AS VAL_TOTVENDA 
												FROM   VENDAS_AVULSA A  
												WHERE  A.DAT_CADASTR_WS BETWEEN '$dat_ini 00:00'AND '$dat_fim 23:59:59' 
													   AND A.COD_EMPRESA = $cod_empresa 
													   AND A.COD_UNIVEND IN($lojasSelecionadas) 
													   AND A.COD_STATUSCRED != 6 
													   $condicaoVendaPDV 
													   $andCodCupom
												ORDER  BY A.DAT_CADASTR_WS DESC ";

								//fnEscreve($sql);
								$retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
								$totalitens_por_pagina = mysqli_fetch_assoc($retorno);
								$numPaginas = ceil($totalitens_por_pagina['contador'] / $itens_por_pagina);


								?>
							</div>
						</div>

						<input type="hidden" name="LOJAS" id="LOJAS" value="<?php echo $lojasSelecionadas; ?>" />
						<input type="hidden" name="AUTORIZA" id="AUTORIZA" value="<?= $autoriza ?>" />
						<input type="hidden" name="CASAS_DEC" id="CASAS_DEC" value="<?= $casasDec ?>">
						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
						<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
						<div class="push5"></div>

					</form>
				</div>
			</div>
		</div>

		<div class="push20"></div>

		<div class="row">

			<div class="col-md-12 col-lg-12 margin-bottom-30">
				<!-- Portlet -->
				<div class="portlet portlet-bordered">

					<div class="portlet-body">


						<div class="row text-center">

							<div class="form-group text-center col-md-4 col-lg-4">

								<div class="push20"></div>

								<p><span><?php echo fnValor($totalitens_por_pagina['contador'], 0); ?></span></p>
								<p><b>Total de Transações no Período</b></p>

								<div class="push20"></div>

							</div>

							<div class="form-group text-center col-md-4 col-lg-4">

								<div class="push20"></div>

								<p>R$ <span><?php echo fnValor($totalitens_por_pagina['VAL_TOTPRODU'], 2); ?></span></p>
								<p><b>Total Bruto</b></p>

								<div class="push20"></div>

							</div>

							<div class="form-group text-center col-md-4 col-lg-4">

								<div class="push20"></div>

								<p>R$ <span><?php echo fnValor($totalitens_por_pagina['VAL_TOTVENDA'], 2); ?></span></p>
								<p><b>Total de Vendas Limpo</b></p>

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
						<div class="col-md-12" id="div_Produtos">
							<table class="table table-bordered table-hover tablesorter">

								<thead>
									<tr>
										<th><small>Autorização</small></th>
										<th><small>Cliente</small></th>
										<th><small>Cupom</small></th>
										<th><small>Loja</small></th>
										<th><small>Data/Hora</small></th>
										<th><small>Valor Bruto</small></th>
										<th><small>Valor Venda</small></th>
										<th><small>Vendedor</small></th>
										<th><small>Atendente</small></th>
										<th><small>Código Venda PDV</small></th>
									</tr>
								</thead>

								<tbody id="relatorioConteudo">

									<?php
									//============================
									/*$ARRAY_UNIDADE1 = array(
										'sql' => "select COD_UNIVEND,cod_empresa,nom_fantasi,NOM_UNIVEND from unidadevenda where cod_empresa=$cod_empresa  and cod_exclusa=0",
										'cod_empresa' => $cod_empresa,
										'conntadm' => $connAdm->connAdm(),
										'IN' => 'N',
										'nomecampo' => '',
										'conntemp' => '',
										'SQLIN' => ""
									);
									$ARRAY_UNIDADE = fnUnivend($ARRAY_UNIDADE1);
									$ARRAY_VENDEDOR1 = array(
										'sql' => "select COD_USUARIO,NOM_USUARIO from usuarios where cod_empresa=$cod_empresa",
										'cod_empresa' => $cod_empresa,
										'conntadm' => $connAdm->connAdm(),
										'IN' => 'N',
										'nomecampo' => '',
										'conntemp' => '',
										'SQLIN' => ""
									);
									*/
									@$ARRAY_VENDEDOR = fnUniVENDEDOR($ARRAY_VENDEDOR1);
									//echo '<pre>';
									//  print_r($ARRAY_VENDEDOR);
									//echo '</pre>';

									//====================================    
									//variavel para calcular o início da visualização com base na página atual
									$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

									// Filtro por Grupo de Lojas
									include "filtroGrupoLojas.php";

									$sql = "SELECT A.COD_UNIVEND,
												   UNI.NOM_FANTASI, 
												   A.COD_VENDEDOR,
												   USU.NOM_USUARIO, 
												   A.COD_ATENDENTE, 
												   A.COD_USUCADA, 
												   A.COD_VENDA,
												   A.COD_CUPOM,
												   A.COD_VENDAPDV, 
												   B.COD_CLIENTE, 
												   B.NOM_CLIENTE, 
												   A.DAT_CADASTR, 
												   A.DAT_CADASTR_WS, 
												   A.VAL_TOTPRODU, 
												   A.VAL_TOTVENDA 
											FROM  VENDAS_AVULSA A 
												   INNER JOIN CLIENTES B ON A.COD_CLIENTE = B.COD_CLIENTE
												   LEFT JOIN UNIDADEVENDA UNI ON UNI.COD_UNIVEND = A.COD_UNIVEND
												   left JOIN USUARIOS USU ON USU.COD_USUARIO=A.COD_VENDEDOR
											WHERE A.DAT_CADASTR_WS BETWEEN '$dat_ini 00:00'AND '$dat_fim 23:59:59' 
												   AND A.COD_EMPRESA = $cod_empresa 
												   AND A.COD_UNIVEND IN($lojasSelecionadas) 
												   AND A.COD_STATUSCRED != 6 
												   $condicaoVendaPDV
												   $andCodCupom
											ORDER BY A.DAT_CADASTR_WS DESC 
											LIMIT $inicio,$itens_por_pagina";


									//fnEscreve($sql);	
									$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

									$countLinha = 1;
									while ($qrListaVendas = mysqli_fetch_assoc($arrayQuery)) {

										$NOM_ARRAY_UNIDADE = null;
										$NOM_ARRAY_NON_VENDEDOR = null;
										$NOM_ARRAY_NON_ATENDENTE = null;

										// Verifica se $ARRAY_UNIDADE está definido e é um array
										if (isset($ARRAY_UNIDADE) && is_array($ARRAY_UNIDADE)) {
											$NOM_ARRAY_UNIDADE = array_search($qrListaVendas['COD_UNIVEND'], array_column($ARRAY_UNIDADE, 'COD_UNIVEND'));
										}

										// Verifica se $ARRAY_VENDEDOR está definido e é um array
										if (isset($ARRAY_VENDEDOR) && is_array($ARRAY_VENDEDOR)) {
											$NOM_ARRAY_NON_VENDEDOR = array_search($qrListaVendas['COD_VENDEDOR'], array_column($ARRAY_VENDEDOR, 'COD_USUARIO'));
											$NOM_ARRAY_NON_ATENDENTE = array_search($qrListaVendas['COD_ATENDENTE'], array_column($ARRAY_VENDEDOR, 'COD_USUARIO'));
										}

										if ($countLinha == 1) {
											$vendaIni = $qrListaVendas['DAT_CADASTR_WS'];
										}

										// Continuar o código da tabela normalmente...
									?>
										<tr style="background-color: #fff;" class="abreDetail_<?php echo $qrListaVendas['COD_UNIVEND']; ?>">
											<td><?php echo $qrListaVendas['COD_VENDA']; ?></td>
											<?php if ($autoriza == 1) { ?>
												<td><a href="action.do?mod=<?php echo fnEncode(1024); ?>&id=<?php echo fnEncode($cod_empresa); ?>&idC=<?php echo fnEncode($qrListaVendas['COD_CLIENTE']); ?>" class="f14" target="_blank"><?php echo $qrListaVendas['NOM_CLIENTE']; ?></a></td>
											<?php } else { ?>
												<td><?php echo $qrListaVendas['NOM_CLIENTE']; ?></td>
											<?php } ?>
											<td><small><?php echo $qrListaVendas['COD_CUPOM']; ?></small></td>
											<td><small><?php echo $qrListaVendas['NOM_FANTASI']; ?></small></td>
											<td><small><?php echo fnDataFull($qrListaVendas['DAT_CADASTR_WS']); ?></small></td>
											<td class="text-right"><small>R$ <?php echo fnValor($qrListaVendas['VAL_TOTPRODU'], 2); ?></small></td>
											<td class="text-right"><small>R$ <?php echo fnValor($qrListaVendas['VAL_TOTVENDA'], 2); ?></small></td>
											<td><small><?php echo $qrListaVendas['NOM_USUARIO']; ?></small></td>
											<td><small><?= $qrListaVendas['NOM_USUARIO'] ?></small></td>
											<td><small><?php echo $qrListaVendas['COD_VENDAPDV']; ?></small></td>
										</tr>
									<?php
										@$totalVenda = @$totalVenda + @$qrListaVendas['VAL_TOTVENDA'];
										@$totalCreditos = @$totalCreditos + @$qrListaVendas['VAL_CREDITOS'];
										$vendaFim = $qrListaVendas['DAT_CADASTR_WS'];
										$countLinha++;
									}


									?>

								</tbody>

								<tfoot>

									<tr>
										<th colspan="100">
											<a class="btn btn-info btn-sm exportarCSV"> <i class="fa fa-file-excel" aria-hidden="true"></i>&nbsp; Exportar</a>
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

						</div>

						<?php

						function fullDateDiff($date1, $date2)
						{
							$date1 = strtotime($date1);
							$date2 = strtotime($date2);
							$diff = abs($date1 - $date2);

							$day = $diff / (60 * 60 * 24); // in day
							$dayFix = floor($day);
							$dayPen = $day - $dayFix;
							if ($dayPen > 0) {
								$hour = $dayPen * (24); // in hour (1 day = 24 hour)
								$hourFix = floor($hour);
								$hourPen = $hour - $hourFix;
								if ($hourPen > 0) {
									$min = $hourPen * (60); // in hour (1 hour = 60 min)
									$minFix = floor($min);
									$minPen = $min - $minFix;
									if ($minPen > 0) {
										$sec = $minPen * (60); // in sec (1 min = 60 sec)
										$secFix = floor($sec);
									}
								}
							}
							$str = "";
							if ($dayFix > 0)
								$str .= $dayFix . "d ";
							if ($hourFix > 0)
								$str .= $hourFix . "h ";
							if ($minFix > 0)
								$str .= $minFix . "m ";
							if ($secFix > 0)
								$str .= $secFix . "s ";
							return $str;
						}

						//fnEscreve($vendaIni);
						//fnEscreve(fnDataFull($vendaIni));
						//fnEscreve(fnFormatDateTime($vendaIni));
						//fnEscreve($vendaFim);
						//fnEscreve(fnDataFull($vendaFim));
						//fnEscreve(fullDateDiff($vendaIni, $vendaFim));
						//fnEscreve(fnValor($totalVenda,2));
						//fnEscreve(fnValor($totalVenda,2));

						//$to_time = strtotime("2008-12-13 10:42:00");
						//$from_time = strtotime("2008-12-13 10:21:00");
						//fnEscreve(round(abs($vendaFim - $vendaini) / 60,2). " minute");									


						?>


					</div>
				</div>

				<div class="push50"></div>

				<div class="push"></div>

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

		$.tablesorter.addParser({
			id: "moeda",
			is: function(s) {
				return true;
			},
			format: function(s) {
				return $.tablesorter.formatFloat(s.replace(new RegExp(/[^0-9,]/g), ""));
			},
			type: "numeric"
		});

		var numPaginas = <?php echo $numPaginas; ?>;
		if (numPaginas != 0) {
			carregarPaginacao(numPaginas);
		}

		// $('#DAT_FIM_GRP').datetimepicker({
		// 	 format: 'DD/MM/YYYY',
		// 	 maxDate : '<?= fnDataShort($hoje) ?>',
		// 	}).on('changeDate', function(e){
		// 		$(this).datetimepicker('hide');
		// 	});

		$('#DAT_INI_GRP, #DAT_FIM_GRP').datetimepicker({
			format: 'DD/MM/YYYY'
		}).on('changeDate', function(e) {
			$(this).datetimepicker('hide');
		});

		// $('#DAT_INI_GRP').datetimepicker({
		// 	 format: 'DD/MM/YYYY'
		// 	}).on('changeDate', function(e){
		// 		$(this).datetimepicker('hide');
		// 	});

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
										url: "relatorios/ajxRelVendasAvulsas.do?opcao=exportar&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>&condicaoVendaPDV=<?php echo $condicaoVendaPDV; ?>",
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

	function reloadPage(idPage) {
		$.ajax({
			type: "POST",
			url: "relatorios/ajxRelVendasAvulsas.do?opcao=paginar&id=<?php echo fnEncode($cod_empresa); ?>&idPage=" + idPage + "&itens_por_pagina=<?php echo $itens_por_pagina; ?>&condicaoCartao=<?php echo $condicaoCartao; ?>&andCreditos=<?php echo $andCreditos; ?>&condicaoVendaPDV=<?php echo $condicaoVendaPDV; ?>&andNome=<?php echo $andNome; ?>",
			data: $('#formulario').serialize(),
			beforeSend: function() {
				$('#relatorioConteudo').html('<tr><td colspan="100"><div class="loading" style="width: 100%;"></div></tr></td>');
			},
			success: function(data) {
				$("#relatorioConteudo").html(data);
				$(".tablesorter").trigger("updateAll");
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