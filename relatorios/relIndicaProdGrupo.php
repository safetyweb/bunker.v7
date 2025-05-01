<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}

require './_system/_FUNCTION_WS.php';

$hashLocal = "";
$hoje = "";
$dias30 = "";
$qtd_produto = 0;
$qtd_relacionada = 0;
$cod_produto = "";
$check_online = "";
$online = "";
$webAtivo = "";
$baseAtivo = "";
$onAtivo = "";
$opcao = "";
$msgRetorno = "";
$msgTipo = "";
$hHabilitado = "";
$hashForm = "";
$cod_externo = "";
$dat_ini = "";
$dat_fim = "";
$des_periodo = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$dadoscliente = "";
$as = "";
$countLinha = "";
$countRegistro = "";
$countQuebra = "";
$objeto = "";
$envelope = "";
$body = "";
$indicacaoprodutoresponse = "";
$retornoitems = "";
$itemindicado = "";
$produto = "";
$qrListaVendas = "";
$cod_loop = "";
$qrListaFiltro = "";
$content = "";
$lojasSelecionadas = "";


$hashLocal = mt_rand();

//inicialização de variáveis
$hoje = fnFormatDate(date("Y-m-d"));
//$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 30 days')));
//$hoje = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 1 days')));
$dias30 = fnFormatDate(date('Y-m-d', strtotime(@$dias30 . '- 2 days')));
$qtd_produto = 10;
$qtd_relacionada = 5;
$cod_produto = 0;
$check_online = '';
$online = 'N';
$webAtivo = "";
$baseAtivo = "in active";
$onAtivo = "";
$opcao = "";

//echo "<pre>";
//print_r(@$_REQUEST);
//echo "</pre>";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_empresa = fnLimpaCampoZero(@$_POST['COD_EMPRESA']);
		$cod_produto = fnLimpaCampoZero(@$_POST['COD_PRODUTO']);

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];



		if ($opcao != '') {

			switch ($opcao) {
				case 'BUS':

					$cod_externo = fnLimpaCampoZero(@$_POST['COD_EXTERNO']);
					$qtd_produto = fnLimpaCampoZero(@$_POST['QTD_PRODUTO']);
					$qtd_relacionada = fnLimpaCampoZero(@$_POST['QTD_RELACIONADA']);
					//if (@$_POST['ONLINE']=='S') {$check_online='checked';}else{$check_online='';}
					//$online = @$_POST['ONLINE'];
					$dat_ini = "1969-12-31";
					$dat_fim = "1969-12-31";
					$webAtivo = "in active";
					$baseAtivo = "";
					$onAtivo = "";

					// require '../_system/_FUNCTION_WS.php';


					break;

				case 'ALT':


					$des_periodo = explode(';', fnLimpaCampo(@$_POST['DES_PERIODO']));
					$dat_ini = $des_periodo['0'];
					$dat_fim = $des_periodo['1'];
					$qtd_produto = fnLimpaCampoZero(@$_POST['QTD_PRODUTO']);
					$qtd_relacionada = fnLimpaCampoZero(@$_POST['QTD_RELACIONADA']);
					//if (@$_POST['ONLINE']=='S') {$check_online='checked';}else{$check_online='';}
					//$online = @$_POST['ONLINE'];
					$webAtivo = "";
					$baseAtivo = "in active";
					$onAtivo = "";

					break;

				case 'ONL':

					@$_POST['DES_PERIODO'] = @$_POST['DT_INICIAL'] . ";" . @$_POST['DT_FINAL'];
					$des_periodo = explode(';', fnLimpaCampo(@$_POST['DES_PERIODO']));
					$dat_ini = fnLimpaCampo(@$_POST['DT_INICIAL']);
					$dat_fim = fnLimpaCampo(@$_POST['DT_FINAL']);
					$qtd_produto = fnLimpaCampoZero(@$_POST['QTD_PRODUTO']);
					$qtd_relacionada = fnLimpaCampoZero(@$_POST['QTD_RELACIONADA']);
					//if (@$_POST['ONLINE']=='S') {$check_online='checked';}else{$check_online='';}
					//$online = @$_POST['ONLINE'];
					$webAtivo = "";
					$baseAtivo = "";
					$onAtivo = "in active";


					break;
			}
		}
	}
}

// fnEscreve($opcao);
// fnEscreve($cod_externo);
// fnEscreve($cod_produto);

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
if (strlen(@$dat_ini) == 0 || @$dat_ini == "1969-12-31") {
	$dat_ini = fnDataSql($dias30);
}
if (strlen(@$dat_fim) == 0 || @$dat_fim == "1969-12-31") {
	$dat_fim = fnDataSql($hoje);
}

//busca revendas do usuário
// include "unidadesAutorizadas.php"; 

//fnMostraForm();
// fnEscreve($dat_ini);
// fnEscreve($dat_fim);
//fnEscreve($check_online);
//$online = 'N';

?>

<div class="push30"></div>

<div class="row">

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fal fa-terminal"></i>
					<span class="text-primary"> <?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
				</div>

				<?php
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

				<ul class="nav nav-tabs">
					<li class="<?= $baseAtivo ?>"><a data-toggle="tab" href="#base">Consulta base</a></li>
					<li class="<?= $webAtivo ?>"><a data-toggle="tab" href="#webservice">Consulta webservice</a></li>
					<?php if ($cod_empresa == 2) { ?>
						<li class="<?= $onAtivo ?>"><a data-toggle="tab" href="#online">Consulta online</a></li>
					<?php } ?>
				</ul>

				<div class="tab-content">

					<!-- aba totem -->
					<div id="webservice" class="tab-pane fade <?= $webAtivo ?>">

						<div class="push30"></div>

						<div class="login-form">

							<form data-toggle="validator" role="form2" method="post" id="formulario2" action="<?php echo $cmdPage; ?>">

								<fieldset>
									<legend>Filtros</legend>

									<div class="row">

										<div class="col-md-4">
											<label for="inputName" class="control-label required">Produto </label>
											<div class="input-group">
												<span class="input-group-btn">
													<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary addBox" data-url="action.php?mod=<?php echo fnEncode(1062) ?>&id=<?php echo fnEncode($cod_empresa) ?>&tipo=rel&pop=true" data-title="Busca Produtos"><i class="fa fa-search" aria-hidden="true"></i></a>
												</span>
												<input type="text" name="DES_PRODUTO" id="DES_PRODUTO" class="form-control input-sm leituraOff" style="border-radius: 0 3px 3px  0;" readonly="readonly" placeholder="Procurar produto específico...">
												<input type="hidden" name="COD_PRODUTO" id="COD_PRODUTO" value="">
											</div>
										</div>

										<div class="col-md-2">
											<div class="form-group">
												<label for="inputName" class="control-label">Cód. Externo</label>
												<input type="text" class="form-control input-sm" name="COD_EXTERNO" id="COD_EXTERNO" value="<?= '' ?>">
											</div>
											<div class="help-block with-errors"></div>
										</div>

										<div class="col-md-2">
											<div class="push20"></div>
											<button type="submit" name="BUS" id="BUS" class="btn btn-primary btn-sm btn-block getBtn"><i class="fal fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
										</div>

									</div>

								</fieldset>

								<div class="push20"></div>

								<div class="row">

									<div class="col-md-12">

										<div class="push20"></div>


										<?php

										if ($opcao == "BUS") {


											if ($cod_externo != 0) {
												$cod_produto = $cod_externo;
											}

											//fnEscreve($cod_produto);

											$dadoscliente = array(
												'CPF' => '',
												'COD_PRODUTO' => $cod_produto,
												'CNB' => $connAdm->connAdm(),
												'COD_EMPRESA' => $cod_empresa,
												'COD_UNIVEND' => ''
											);

											$as = IndicacaoProduto($dadoscliente);


											$countLinha = 1;
											$countRegistro = 1;
											$countQuebra = 1;

											foreach ($as as $objeto) {
												foreach ($objeto as $envelope) {
													foreach ($envelope as $body) {
														foreach ($body as $indicacaoprodutoresponse) {
															foreach ($indicacaoprodutoresponse as $retornoitems) {
																foreach ($retornoitems as $itemindicado) {
																	foreach ($itemindicado as $produto) {
										?>
																		<div>

																			- <span class="f12"><?= $produto['nome_produto']; ?><small>&nbsp; (<?= $produto['cod_externo']; ?>)</small></span><br />&nbsp;
																			<span class="label-as-badge text-center label-info" style="border-radius: 3px;"><span style="color: #fff; padding: 0 3px 2px 3px; font-size: 9px;" data-toggle='tooltip' data-original-title='Qtd. Objeto'><?= $produto['ranking']; ?></span></span>&nbsp;
																			<span class="label-as-badge text-center label-info" style="border-radius: 3px;"><span style="color: #fff; padding: 0 3px 2px 3px; font-size: 9px;" data-toggle='tooltip' data-original-title='% do Objeto'><?= $produto['pct_ranking']; ?></span></span>
																			<div class='push5'></div>

																		</div>

										<?php
																	}
																}
															}
														}
													}
												}
											}
											echo "<div class='push30'></div>";

											echo "<pre class='f12'>";
											print_r($as);
											echo "</pre>";

											// 	while ($qrListaVendas = mysqli_fetch_assoc($arrayQuery)){

											// 		if ($countLinha == 0) {
											// 			echo "<pre>";																
											// 			print_r($qrListaVendas);
											// 			echo "</pre>";
											// 		}

											// 		if ($countRegistro == 1) {
											// 			$cod_loop = $qrListaVendas['COD_PRODUTO_ORIGEM'];	
											// 			echo "<div class='col-md-3'>";																
											// 			echo "<b><small>".$qrListaVendas['PRODUTO_ORIGEM']."</small></b><br/>";
											// 			echo "<b><small class='f12'><i>[".$qrListaVendas['NOME_AGRUPADOR_ORIGEM']."<i>]</small></b><br/>";
											// 			echo "<span class='label-as-badge text-center label-success' style='border-radius: 3px;'><span style='color: #fff; padding: 0 3px 2px 3px; font-size: 10px;' data-toggle='tooltip' data-original-title='Qtd. Objeto'>".$qrListaVendas['QTD_ITEM_ORIGEM_GRUPO']."</span></span>&nbsp;";
											// 			echo "<span class='label-as-badge text-center label-default' style='border-radius: 3px;'><span style='color: #fff; padding: 0 3px 2px 3px; font-size: 10px;' data-toggle='tooltip' data-original-title='SKU'>".$qrListaVendas['COD_PRD_EXTERNO']."</span></span>&nbsp;";
											// 			echo "<div class='push3'></div>";																
											// 		}



											// 		if ($countRegistro == $qtd_relacionada ){
											// 		//if ($qrListaVendas['COD_PRODUTO_ORIGEM'] == $cod_loop ){
											// 			echo "</div>";
											// 			$countRegistro = 0;	
											// 		}

											// 		if ($countQuebra == (4 * $qtd_relacionada) ){
											// 			echo "<div class='push20'></div>";
											// 			echo "<div class='push10'></div>";
											// 			$countQuebra = 0;	
											// 		}

											// 		$countRegistro++;	
											// 		$countLinha++;	
											// 		$countQuebra++;	
											// 	}

										}

										//fnEscreve($countLinha-1);				
										?>



									</div>

								</div>

								<input type="hidden" name="opcao" id="opcao" value="">
								<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?= $cod_empresa ?>">
								<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
								<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
								<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
							</form>

						</div>

					</div>


					<!-- aba lista unidades -->
					<div id="base" class="tab-pane fade <?= $baseAtivo ?>">

						<div class="push30"></div>

						<div class="login-form">

							<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

								<fieldset>
									<legend>Filtros</legend>

									<div class="row">

										<div class="col-md-2">
											<div class="form-group">
												<label for="inputName" class="control-label required">Consultar Online</label><br />
												<label class="switch">
													<input type="checkbox" name="ONLINE" id="ONLINE" class="switch" value="S" <?php echo $check_online; ?> />
													<span></span>
												</label>
												<div class="help-block with-errors"></div>
											</div>
										</div>

										<div class="col-md-4">
											<label for="inputName" class="control-label required">Produto </label>
											<div class="input-group">
												<span class="input-group-btn">
													<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary addBox" data-url="action.php?mod=<?php echo fnEncode(1062) ?>&id=<?php echo fnEncode($cod_empresa) ?>&tipo=rel&pop=true" data-title="Busca Produtos"><i class="fa fa-search" aria-hidden="true"></i></a>
												</span>
												<input type="text" name="DES_PRODUTO" id="DES_PRODUTO" class="form-control input-sm leituraOff" style="border-radius: 0 3px 3px  0;" readonly="readonly" placeholder="Procurar produto específico...">
												<input type="hidden" name="COD_PRODUTO" id="COD_PRODUTO" value="">
											</div>
										</div>

										<div class="col-md-2">
											<div class="form-group">
												<label for="inputName" class="control-label">Lista de Produtos <small>(mais vendidos)</small></label>
												<select data-placeholder="escolha a quantidade" name="QTD_PRODUTO" id="QTD_PRODUTO" class="chosen-select-deselect">
													<option value="0">&nbsp;</option>
													<option value="10">10</option>
													<option value="50">50</option>
													<option value="100">100</option>
													<option value="200">200</option>
													<option value="500">500</option>
													<option value="1000">1000</option>
												</select>
												<div class="help-block with-errors"></div>
											</div>
											<script>
												$("#formulario #QTD_PRODUTO").val(<?php echo $qtd_produto; ?>).trigger("chosen:updated");
											</script>
										</div>

										<div class="col-md-2">
											<div class="form-group">
												<label for="inputName" class="control-label">Produtos Relacionados</label>
												<select data-placeholder="escolha a quantidade" name="QTD_RELACIONADA" id="QTD_RELACIONADA" class="chosen-select-deselect">
													<option value="0">&nbsp;</option>
													<option value="5">5</option>
													<option value="10">10</option>
												</select>
												<div class="help-block with-errors"></div>
											</div>
											<script>
												$("#formulario #QTD_RELACIONADA").val(<?php echo $qtd_relacionada; ?>).trigger("chosen:updated");
											</script>
										</div>

									</div>

									<div class="row">

										<div class="col-md-2">
											<div class="form-group">
												<label for="inputName" class="control-label">Período </label>
												<select data-placeholder="Selecione o período" name="DES_PERIODO" id="DES_PERIODO" class="chosen-select-deselect">
													<option value=""></option>
													<?php
													$sql = "SELECT DISTINCT DT_PERIODO_INI , DT_PERIODO_FIM 
																						FROM PRODUTOSINDICA 
																						WHERE COD_EMPRESA = $cod_empresa 
																						ORDER BY DT_PERIODO_INI DESC";
													$arrayQuery = mysqli_query(connTemp($cod_empresa, ""), trim($sql));

													while (@$qrListaFiltro = mysqli_fetch_assoc($arrayQuery)) {
														echo "
																						  <option value='" . $qrListaFiltro['DT_PERIODO_INI'] . ";" . $qrListaFiltro['DT_PERIODO_FIM'] . "'>" . fnDataShort($qrListaFiltro['DT_PERIODO_INI']) . " à " . fnDataShort($qrListaFiltro['DT_PERIODO_FIM']) . "</option> 
																						";
													}
													?>
												</select>
												<?php // fnEscreve($arrayQuery); 
												?>
												<script>
													$("#formulario #DES_PERIODO").val("<?= $dat_ini . ';' . $dat_fim ?>").trigger("chosen:updated");
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

								<?php if ($_SESSION["SYS_COD_EMPRESA"] == 2) { ?>
									<div class="push20"></div>
									<a href="javascript:void(0)" class="btn btn-danger pull-left addBox" data-url="action.php?mod=<?php echo fnEncode(1594) ?>&id=<?php echo fnEncode($cod_empresa) ?>&pop=true" data-title="Processar Produtos"><i class="fa fa-cogs" aria-hidden="true"></i>&nbsp; Processar Manualmente</a>
									<!-- CALL SP_DEFINE_TOP_PRODUTOGRUPO ( 39 , 'PRD' , '2019-11-03' , '2019-11-09' ,100 , 5 , 0, 'S' ) -->
								<?php } ?>

								<div class="push20"></div>

								<div class="row">

									<div class="col-md-12" id="div_Produtos">

										<div class="push20"></div>


										<?php

										if ($opcao == "ALT") {

											$sql = "CALL SP_DEFINE_TOP_PRODUTOGRUPO( $cod_empresa, '" . ($cod_empresa == 77 ? "SEG" : "PRD") . "', '$dat_ini' , '$dat_fim' , $qtd_produto, $qtd_relacionada , $cod_produto, 'N' )";

											//fnEscreve($sql);
											//fnTestesql(connTemp($cod_empresa,''),$sql);											
											$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);


											$countLinha = 1;
											$countRegistro = 1;
											$countQuebra = 1;

											while (@$qrListaVendas = mysqli_fetch_assoc($arrayQuery)) {

												if ($countLinha == 0) {
													//echo "<pre>";																
													//print_r($qrListaVendas);
													//echo "</pre>";
												}

												if ($countRegistro == 1) {
													$cod_loop = $qrListaVendas['COD_PRODUTO_ORIGEM'];
													echo "<div class='col-md-3'>";
													echo "<b><small>" . $qrListaVendas['PRODUTO_ORIGEM'] . "</small></b><br/>";
													echo (@$qrListaVendas['NOME_AGRUPADOR_ORIGEM'] <> "" ? "<b><small class='f12'><i>[" . $qrListaVendas['NOME_AGRUPADOR_ORIGEM'] . "<i>]</small></b><br/>" : "");
													echo "<span class='label-as-badge text-center label-success' style='border-radius: 3px;'><span style='color: #fff; padding: 0 3px 2px 3px; font-size: 10px;' data-toggle='tooltip' data-original-title='Qtd. Objeto'>" . $qrListaVendas['QTD_ITEM_ORIGEM_GRUPO'] . "</span></span>&nbsp;";
													echo "<span class='label-as-badge text-center label-default' style='border-radius: 3px;'><span style='color: #fff; padding: 0 3px 2px 3px; font-size: 10px;' data-toggle='tooltip' data-original-title='SKU'>" . $qrListaVendas['COD_PRD_EXTERNO'] . "</span></span>&nbsp;";
													echo "<div class='push3'></div>";
												}

										?>
												<!--<b><?php echo $countRegistro; ?> / <?php echo $qrListaVendas['COD_PRODUTO_ORIGEM']; ?></b>-->
												- <span class="f12"><?php echo $qrListaVendas['NOME_AGRUPADOR']; ?><small>&nbsp; (<?php echo $qrListaVendas['COD_AGRUPADO']; ?>)</small></span><br />&nbsp;
												<span class="label-as-badge text-center label-info" style="border-radius: 3px;"><span style="color: #fff; padding: 0 3px 2px 3px; font-size: 9px;" data-toggle='tooltip' data-original-title='Qtd. Objeto'><?php echo $qrListaVendas['QTD_ITEM']; ?></span></span>&nbsp;
												<span class="label-as-badge text-center label-info" style="border-radius: 3px;"><span style="color: #fff; padding: 0 3px 2px 3px; font-size: 9px;" data-toggle='tooltip' data-original-title='% do Objeto'><?php echo fnValor($qrListaVendas['PERCENTUAL_ITEM_GRUPO'], 2); ?>%</span></span>
												<div class='push5'></div>

										<?php

												if ($countRegistro == $qtd_relacionada) {
													//if ($qrListaVendas['COD_PRODUTO_ORIGEM'] == $cod_loop ){
													echo "</div>";
													$countRegistro = 0;
												}

												if ($countQuebra == (4 * $qtd_relacionada)) {
													echo "<div class='push20'></div>";
													echo "<div class='push10'></div>";
													$countQuebra = 0;
												}

												$countRegistro++;
												$countLinha++;
												$countQuebra++;
											}
										}

										//fnEscreve($countLinha-1);				
										?>



									</div>

								</div>
								<input type="hidden" name="NOVO_CLIENTE" id="NOVO_CLIENTE" value="">
								<input type="hidden" name="REFRESH_CLIENTE" id="REFRESH_CLIENTE" value="N">
								<input type="hidden" name="opcao" id="opcao" value="">
								<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
								<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
								<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
								<div class="push5"></div>

							</form>

							<div class="push50"></div>

							<div class="push"></div>

						</div>

					</div>








					<div id="online" class="tab-pane fade <?= $onAtivo ?>">

						<div class="push30"></div>

						<div class="login-form">

							<form data-toggle="validator" role="form2" method="post" id="formulario3" action="<?php echo $cmdPage; ?>">

								<fieldset>
									<legend>Filtros</legend>

									<div class="row">
										<?php /*															
															<div class="col-md-2">
																<div class="form-group">
																	<label for="inputName" class="control-label required">Consultar Online</label><br/>
																	<label class="switch">
																	<input type="checkbox" name="ONLINE" id="ONLINE" class="switch" value="S" <?php echo $check_online; ?> />
																	<span></span>
																	</label> 								
																	<div class="help-block with-errors"></div>
																</div>																				
															</div>
*/ ?>
										<div class="col-md-4">
											<label for="inputName" class="control-label required">Produto </label>
											<div class="input-group">
												<span class="input-group-btn">
													<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary addBox" data-url="action.php?mod=<?php echo fnEncode(1062) ?>&id=<?php echo fnEncode($cod_empresa) ?>&tipo=rel&pop=true" data-title="Busca Produtos"><i class="fa fa-search" aria-hidden="true"></i></a>
												</span>
												<input type="text" name="DES_PRODUTO" id="DES_PRODUTO" class="form-control input-sm leituraOff" style="border-radius: 0 3px 3px  0;" readonly="readonly" placeholder="Procurar produto específico...">
												<input type="hidden" name="COD_PRODUTO" id="COD_PRODUTO" value="">
											</div>
										</div>

										<div class="col-md-2">
											<div class="form-group">
												<label for="inputName" class="control-label">Lista de Produtos <small>(mais vendidos)</small></label>
												<select data-placeholder="escolha a quantidade" name="QTD_PRODUTO" id="QTD_PRODUTO" class="chosen-select-deselect">
													<option value="0">&nbsp;</option>
													<option value="10">10</option>
													<option value="50">50</option>
													<option value="100">100</option>
													<option value="200">200</option>
													<option value="500">500</option>
													<option value="1000">1000</option>
												</select>
												<div class="help-block with-errors"></div>
											</div>
											<script>
												$("#formulario3 #QTD_PRODUTO").val(<?php echo $qtd_produto; ?>).trigger("chosen:updated");
											</script>
										</div>

										<div class="col-md-2">
											<div class="form-group">
												<label for="inputName" class="control-label">Produtos Relacionados</label>
												<select data-placeholder="escolha a quantidade" name="QTD_RELACIONADA" id="QTD_RELACIONADA" class="chosen-select-deselect">
													<option value="0">&nbsp;</option>
													<option value="5">5</option>
													<option value="10">10</option>
												</select>
												<div class="help-block with-errors"></div>
											</div>
											<script>
												$("#formulario3 #QTD_RELACIONADA").val(<?php echo $qtd_relacionada; ?>).trigger("chosen:updated");
											</script>
										</div>

									</div>

									<div class="row">

										<div class="col-md-2">
											<div class="form-group">
												<label for="inputName" class="control-label">Data Inicial</label>
												<input type="date" class="form-control input-sm" name="DT_INICIAL" value="<?= $dat_ini ?>" id="DT_INICIAL" value="">
											</div>
										</div>

										<div class="col-md-2">
											<div class="form-group">
												<label for="inputName" class="control-label">Data Final</label>
												<input type="date" class="form-control input-sm" name="DT_FINAL" value="<?= $dat_fim ?>" id="DT_FINAL" value="">
											</div>
										</div>

										<div class="col-md-2">
											<div class="push20"></div>
											<button type="submit" name="ONL" id="ONL" class="btn btn-primary btn-sm btn-block getBtn"><i class="fa fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
										</div>


									</div>

								</fieldset>

								<div class="push20"></div>

								<div class="row">

									<div class="col-md-12" id="div_Produtos">

										<div class="push20"></div>


										<?php

										if ($opcao == "ONL") {

											$sql = "CALL SP_DEFINE_TOP_PRODUTOGRUPO( $cod_empresa, '" . ($cod_empresa == 77 ? "SEG" : "PRD") . "', '$dat_ini' , '$dat_fim' , $qtd_produto, $qtd_relacionada , $cod_produto, 'S' )";

											//fnEscreve($sql);
											//fnTestesql(connTemp($cod_empresa,''),$sql);											
											$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

											$countLinha = 1;
											$countRegistro = 1;
											$countQuebra = 1;
											//print_r($arrayQuery);

											while ($qrListaVendas = mysqli_fetch_assoc($arrayQuery)) {

												if ($countLinha == 0) {
													//echo "<pre>";																
													//print_r($qrListaVendas);
													//echo "</pre>";
												}

												if ($countRegistro == 1) {
													$cod_loop = $qrListaVendas['COD_PRODUTO_ORIGEM'];
													echo "<div class='col-md-3'>";
													echo "<b><small>" . $qrListaVendas['PRODUTO_ORIGEM'] . "</small></b><br/>";
													echo (@$qrListaVendas['NOME_AGRUPADOR_ORIGEM'] <> "" ? "<b><small class='f12'><i>[" . $qrListaVendas['NOME_AGRUPADOR_ORIGEM'] . "<i>]</small></b><br/>" : "");
													echo "<span class='label-as-badge text-center label-success' style='border-radius: 3px;'><span style='color: #fff; padding: 0 3px 2px 3px; font-size: 10px;' data-toggle='tooltip' data-original-title='Qtd. Objeto'>" . $qrListaVendas['QTD_ITEM_ORIGEM_GRUPO'] . "</span></span>&nbsp;";
													echo "<span class='label-as-badge text-center label-default' style='border-radius: 3px;'><span style='color: #fff; padding: 0 3px 2px 3px; font-size: 10px;' data-toggle='tooltip' data-original-title='SKU'>" . $qrListaVendas['COD_PRD_EXTERNO'] . "</span></span>&nbsp;";
													echo "<div class='push3'></div>";
												}

										?>
												<!--<b><?php echo $countRegistro; ?> / <?php echo $qrListaVendas['COD_PRODUTO_ORIGEM']; ?></b>-->
												- <span class="f12"><?php echo $qrListaVendas['NOME_AGRUPADOR']; ?><small>&nbsp; (<?php echo $qrListaVendas['COD_AGRUPADO']; ?>)</small></span><br />&nbsp;
												<span class="label-as-badge text-center label-info" style="border-radius: 3px;"><span style="color: #fff; padding: 0 3px 2px 3px; font-size: 9px;" data-toggle='tooltip' data-original-title='Qtd. Objeto'><?php echo $qrListaVendas['QTD_ITEM']; ?></span></span>&nbsp;
												<span class="label-as-badge text-center label-info" style="border-radius: 3px;"><span style="color: #fff; padding: 0 3px 2px 3px; font-size: 9px;" data-toggle='tooltip' data-original-title='% do Objeto'><?php echo fnValor($qrListaVendas['PERCENTUAL_ITEM_GRUPO'], 2); ?>%</span></span>
												<div class='push5'></div>

										<?php

												if ($countRegistro == $qtd_relacionada) {
													//if ($qrListaVendas['COD_PRODUTO_ORIGEM'] == $cod_loop ){
													echo "</div>";
													$countRegistro = 0;
												}

												if ($countQuebra == (4 * $qtd_relacionada)) {
													echo "<div class='push20'></div>";
													echo "<div class='push10'></div>";
													$countQuebra = 0;
												}

												$countRegistro++;
												$countLinha++;
												$countQuebra++;
											}
										}

										//fnEscreve($countLinha-1);				
										?>



									</div>

								</div>
								<input type="hidden" name="NOVO_CLIENTE" id="NOVO_CLIENTE" value="">
								<input type="hidden" name="REFRESH_CLIENTE" id="REFRESH_CLIENTE" value="N">
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

				<?php //fnEscreve($opcao); 
				?>
			</div>
			<!-- fim Portlet -->
		</div>

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
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="push20"></div>



<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />

<script>
	/*		$(document).ready(function(){
			setTimeout(function() {
				$("#online").addClass("tab-pane fade");
				$("#base").addClass("tab-pane fade");
			}, 100);
		});
	*/

	//datas
	$(function() {

		$("#BUS").click(function(e) {
			e.preventDefault();
			$("#formulario2 #opcao").val("BUS");
			$("#formulario2").submit();
		});
		$("#ONL").click(function(e) {
			e.preventDefault();
			$("#formulario3 #opcao").val("ONL");
			$("#formulario3").submit();
		});

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


	});


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

	function exportarCSV(btn) {
		log_detalhes = $(btn).attr('value');
		// alert(id);
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
							icon: 'fa fa-check-square',
							content: function() {
								var self = this;
								return $.ajax({
									url: "relatorios/ajxRelProdutosTop.do?opcao=exportar&nomeRel=" + nome + "&id=<?php echo fnEncode(@$cod_empresa); ?>&LOJAS=<?php echo @$lojasSelecionadas; ?>&log_detalhes=" + log_detalhes,
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
	}
</script>