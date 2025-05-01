<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$hashLocal = "";
$temFiltro = "";
$log_externo = "";
$log_ativo = "";
$cod_empresaCode = "";
$cod_cliente = "";
$nom_cliente = "";
$num_cartao = "";
$des_igreja = "";
$des_local = "";
$des_superb = "";
$num_cgcecpf = "";
$cod_indicad = "";
$count_filtros = "";
$andFiltros = "";
$des_tpfiltros = "";
$colunas = "";
$filtros = "";
$cod_estado = "";
$cod_municipio = "";
$i = "";
$cod_filtro = "";
$Arr_COD_FILTRO = "";
$j = "";
$tpFiltros_e_filtros = "";
$filtros_div = "";
$cod_tpfiltro = "";
$cod_filtros = "";
$qrTipo = "";
$campo = "";
$innerJoin = "";
$andClientes = "";
$mod = "";
$sqlInd = "";
$qrUsu = "";
$disableCombo = "";
$msgRetorno = "";
$msgTipo = "";
$countFiltros = "";
$arrayQuery = [];
$countObjeto = "";
$sqlFiltro = "";
$arrayFiltros = [];
$qrFiltros = "";
$sqlChosen = "";
$cod_persona = "";
$arrayChosen = [];
$qrChosen = "";
$RedirectPg = "";
$DestinoPg = "";
$andCodigo = "";
$andNome = "";
$andIgreja = "";
$andLocal = "";
$andCartao = "";
$andCpf = "";
$andIndicad = "";
$dat_anive_ini = "";
$dat_anive_fim = "";
$andAnive = "";
$dat_ini_busca = "";
$andDataIni = "";
$dat_fim_busca = "";
$andDataFim = "";
$andExterno = "";
$andAtivo = "";
$andEstado = "";
$andCidade = "";
$totClientes = "";
$countConsulta = "";
$total = 0;
$registros = "";
$inicio = "";
$arraybusca = [];
$dadosdabusca = "";
$objeto = "";
$qrApoia = "";
$idade = "";
$tel = "";
$linhas = "";
$alias = "";
$resPagina = "";
$qrListaBusca = "";
$paginaAtiva = "";
$content = "";


$hashLocal = mt_rand();
$temFiltro = 'N';

if (isset($_POST['COD_EMPRESA'])) {

	$cod_empresa = fnLimpacampo(fnDecode(@$_REQUEST['COD_EMPRESA']));
	if (empty(@$_REQUEST['LOG_EXTERNO'])) {
		$log_externo = 'N';
	} else {
		$log_externo = @$_REQUEST['LOG_EXTERNO'];
	}
	if (empty(@$_REQUEST['LOG_ATIVO'])) {
		$log_ativo = 'N';
	} else {
		$log_ativo = @$_REQUEST['LOG_ATIVO'];
	}
	$cod_empresaCode = fnLimpacampo(@$_REQUEST['COD_EMPRESA']);
	$cod_cliente  = fnLimpacampo(@$_REQUEST['COD_CLIENTE']);
	$nom_cliente  = fnLimpacampo(@$_REQUEST['NOM_CLIENTE']);
	$num_cartao  = fnLimpacampo(@$_REQUEST['NUM_CARTAO']);
	$des_igreja  = fnLimpacampo(@$_REQUEST['DES_IGREJA']);
	$des_local  = fnLimpacampo(@$_REQUEST['DES_LOCAL']);
	$des_superb  = fnLimpacampo(@$_REQUEST['DES_SUPERB']);
	$num_cgcecpf  = fnLimpaDoc(fnLimpacampo(@$_REQUEST['NUM_CGCECPF']));
	$cod_indicad = fnLimpaCampo(@$_REQUEST['COD_INDICAD']);
	$pagina  = fnLimpacampo(@$_REQUEST['pagina']);
	$count_filtros = fnLimpacampo(@$_REQUEST['COUNT_FILTROS']);
	$andFiltros = "";
	$des_tpfiltros = [];
	$colunas = "";
	$filtros = "";

	$cod_estado = fnLimpacampoZero(@$_REQUEST['COD_ESTADO']);
	$cod_municipio = fnLimpacampoZero(@$_REQUEST['COD_MUNICIPIO']);

	if ($count_filtros != '' && $count_filtros != 0) {

		for ($i = 0; $i < $count_filtros; $i++) {

			$cod_filtro = "";

			if (isset($_POST["COD_FILTRO_$i"])) {

				$Arr_COD_FILTRO = @$_POST["COD_FILTRO_$i"];

				if (fnLimpacampo(@$_POST["COD_TPFILTRO_$i"]) != '') {

					$cod_filtro = $cod_filtro . fnLimpacampo(@$_POST["COD_TPFILTRO_$i"]) . ":";
				}

				for ($j = 0; $j < count($Arr_COD_FILTRO); $j++) {

					$cod_filtro = $cod_filtro . $Arr_COD_FILTRO[$j] . ",";
					$filtros = $filtros . $Arr_COD_FILTRO[$j] . ",";
				}
			}

			if (@$_POST["COD_FILTRO_$i"] != '') {

				$cod_filtro = rtrim($cod_filtro, ',');

				$tpFiltros_e_filtros = $tpFiltros_e_filtros . $cod_filtro . ';';

				$filtros_div = explode(':', $cod_filtro);

				$cod_tpfiltro = $filtros_div['0'];
				$cod_filtros = $filtros_div['1'];

				$sql = "SELECT DES_TPFILTRO FROM TIPO_FILTRO WHERE COD_TPFILTRO = $cod_tpfiltro";
				$qrTipo = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa, ''), $sql));
				array_push($des_tpfiltros, $qrTipo['DES_TPFILTRO']);
				$campo = explode(' ', strtoupper(fnacentos($qrTipo['DES_TPFILTRO'])));

				$colunas .= $campo['0'] . $i . ".DES_FILTRO AS $campo[0],";

				$cod_filtros = rtrim(ltrim($cod_filtros, ','), ',');

				$innerJoin .= "
								  INNER JOIN CLIENTE_FILTROS " . $campo['0'] . " ON " . $campo['0'] . ".COD_FILTRO IN($cod_filtros) AND " . $campo['0'] . ".COD_TPFILTRO = $cod_tpfiltro AND " . $campo['0'] . ".COD_CLIENTE=CL.COD_CLIENTE 
								  LEFT JOIN FILTROS_CLIENTE " . $campo['0'] . $i . " ON " . $campo['0'] . ".COD_FILTRO = " . $campo['0'] . $i . ".COD_FILTRO
					";
			}
		}

		$filtros = rtrim(ltrim($filtros, ','), ',');
		// fnEscreve($innerJoin);


		// echo "<pre>";
		// print_r($des_tpfiltros);
		// echo "</pre>";

	}
} else {

	$cod_empresaCode = "";
	$cod_cliente  = "";
	$nom_cliente  = "";
	$num_cartao  = "";
	$num_cgcecpf  = "";
	$des_superb = "";
	$pagina  = "1";
	$andClientes = "";
}

if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode(@$_GET['id']);
	$mod = fnDecode(@$_GET['mod']);
}

$sqlInd = "SELECT COD_PERFILS, COD_INDICADOR FROM USUARIOS WHERE COD_USUARIO = $_SESSION[SYS_COD_USUARIO]";
$qrUsu = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), trim($sqlInd)));
// fnEscreve($cod_empresa);

if ($qrUsu['COD_PERFILS'] == 1154) {
	$cod_indicad = $qrUsu['COD_INDICADOR'];
	$disableCombo = "disabled";
} else {
	$disableCombo = "";
}
// fnEscreve($cod_indicad);

?>

<div class="push30"></div>

<div class="row">

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fal fa-terminal"></i>
					<span class="text-primary"><?php echo $NomePg ?> </span>
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

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<!-- filtros dinâmicos -->

						<fieldset>
							<legend>Filtros Dinâmicos</legend>

							<?php
							//FILTROS DINÂMICOS
							$countFiltros = 0;

							$sql = "SELECT COD_TPFILTRO, DES_TPFILTRO FROM TIPO_FILTRO
												WHERE COD_EMPRESA = $cod_empresa
                                                AND COD_TPFILTRO = 28
												ORDER BY NUM_ORDENAC";
							$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), trim($sql));

							if (mysqli_num_rows($arrayQuery) > 0) {

								$countObjeto = 0
							?>

								<div class="row">

									<?php
									while ($qrTipo = mysqli_fetch_assoc($arrayQuery)) {
									?>

										<div class="col-xs-3">
											<div class="form-group">
												<label for="inputName" class="control-label"><?= $qrTipo['DES_TPFILTRO'] ?></label>
												<div id="relatorioFiltro_<?= $countFiltros ?>">
													<input type="hidden" name="COD_TPFILTRO_<?= $countFiltros ?>" id="COD_TPFILTRO_<?= $countFiltros ?>" value="<?= $qrTipo['COD_TPFILTRO'] ?>">
													<select data-placeholder="Selecione os filtros" name="COD_FILTRO_<?= $countFiltros ?>[]" id="COD_FILTRO_<?= $qrTipo['COD_TPFILTRO'] ?>" multiple="multiple" class="chosen-select-deselect last-chosen-link">
														<option value=""></option>

														<?php
														$sqlFiltro = "SELECT COD_FILTRO, DES_FILTRO FROM FILTROS_CLIENTE
																				  WHERE COD_TPFILTRO = $qrTipo[COD_TPFILTRO]
																				  ORDER BY DES_FILTRO";

														$arrayFiltros = mysqli_query(connTemp($cod_empresa, ''), trim($sqlFiltro));
														while ($qrFiltros = mysqli_fetch_assoc($arrayFiltros)) {
														?>

															<option value="<?= $qrFiltros['COD_FILTRO'] ?>"><?= $qrFiltros['DES_FILTRO'] ?></option>

														<?php
														}


														$sqlChosen = "SELECT COD_FILTRO FROM FILTROS_PERSONA
																					WHERE COD_PERSONA = $cod_persona AND COD_TPFILTRO =" . $qrTipo['COD_TPFILTRO'];
														$arrayChosen = mysqli_query(connTemp($cod_empresa, ''), $sqlChosen);

														while ($qrChosen = mysqli_fetch_assoc($arrayChosen)) {
															$cod_filtros .= $qrChosen['COD_FILTRO'] . ",";
														}

														$cod_filtros = rtrim(ltrim($cod_filtros, ','), ',');

														?>
														<script>

														</script>

													</select>
													<div class="help-block with-errors"></div>
													<a class="btn btn-default btn-sm" id="iAll_<?= $countFiltros ?>" style="padding: 0 2px ; font-size: 10px;"><i class="fa fa-check-square" aria-hidden="true"></i> selecionar todos</a>&nbsp;
													<a class="btn btn-default btn-sm" id="iNone_<?= $countFiltros ?>" style="padding: 0 2px ; font-size: 10px;"><i class="fa fa-square-o" aria-hidden="true"></i>&nbsp; deselecionar todos</a>
													<script>
														$(function() {
															$('#iAll_<?= $countFiltros ?>').on('click', function(e) {
																e.preventDefault();
																$('#COD_FILTRO_<?= $qrTipo['COD_TPFILTRO'] ?> option').prop('selected', true).trigger('chosen:updated');
															});

															$('#iNone_<?= $countFiltros ?>').on('click', function(e) {
																e.preventDefault();
																$("#COD_FILTRO_<?= $qrTipo['COD_TPFILTRO'] ?> option:selected").removeAttr("selected").trigger('chosen:updated');
															});
														});
													</script>
												</div>
											</div>
										</div>

									<?php
										if ($countObjeto == 3) {
											$countObjeto = 0;
											echo '<div class="push10"></div>';
										} else {
											$countObjeto++;
										}
										$countFiltros++;
									}
									?>

									<div class="col-md-2">
										<div class="push20"></div>
										<button type="submit" name="BUS" id="BUS" class="btn btn-primary btn-sm btn-block getBtn"><i class="fal fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
									</div>


								</div>



							<?php
							}
							?>

						</fieldset>

						<?php
						if (!is_null($RedirectPg)) {
							$DestinoPg = fnEncode($RedirectPg);
						} else {
							$DestinoPg = "";
						}

						if ($cod_empresa == 136) {
							$DestinoPg = fnEncode(1423);
						}

						?>

						<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo fnEncode($cod_empresa); ?>">
						<input type="hidden" name="COUNT_FILTROS" id="COUNT_FILTROS" value="<?= $countFiltros ?>">
						<input type="hidden" name="dId" id="dId" value="K2xr0lE3UHI¢">
						<input type="hidden" name="dKey" id="dKey" value="<?php echo fnEncode($cod_empresa); ?>">
						<input type="hidden" name="dUrl" id="dUrl" value="<?php echo $DestinoPg; ?>">
						<input type="hidden" name="pagina" id="pagina" value="<?php echo $pagina; ?>">
						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">

						<div class="push5"></div>

					</form>

					<div class="push50"></div>

					<?php
					//verifica post
					if (isset($_POST['opcao'])) {

						if ($cod_empresa != 0 && $cod_empresa != '') {

							$pagina = (isset($_REQUEST['pagina'])) ? @$_REQUEST['pagina'] : 1;

							if ($cod_cliente != 0 && $cod_cliente != '') {
								$andCodigo = 'AND CL.COD_CLIENTE=' . $cod_cliente;
							} else {
								$andCodigo = ' ';
							}

							if ($nom_cliente != '' && $nom_cliente != 0) {
								$andNome = 'AND CL.NOM_CLIENTE LIKE "%' . $nom_cliente . '%"';
							} else {
								$andNome = ' ';
							}

							if ($des_igreja != '' && $des_igreja != 0) {
								$andIgreja = "AND DA.DES_IGREJA = '$des_igreja'";
							} else {
								$andIgreja = ' ';
							}

							if ($des_local != '' && $des_local != 0) {
								$andLocal = "AND DA.DES_LOCAL = '$des_local'";
							} else {
								$andLocal = ' ';
							}

							if ($num_cartao != '' && $num_cartao != 0) {
								$andCartao = 'AND CL.NUM_CARTAO=' . $num_cartao;
							} else {
								$andCartao = ' ';
							}

							if ($num_cgcecpf != '' && $num_cgcecpf != 0) {
								$andCpf = 'AND CL.NUM_CGCECPF =' . $num_cgcecpf;
							} else {
								$andCpf = ' ';
							}

							if ($cod_indicad != '' && $cod_indicad != 0) {
								$andIndicad = "AND CL.COD_INDICAD = $cod_indicad";
							} else {
								$andIndicad = "";
							}

							if ($dat_anive_ini != "" || $dat_anive_fim != "") {

								if ($dat_anive_ini != '' && $dat_anive_ini != 0) {
									$dat_anive_ini = "RIGHT(STR_TO_DATE('" . $dat_anive_ini . "', '%d/%m/%Y'),5)";
								} else {
									$dat_anive_ini = "RIGHT(STR_TO_DATE('01/01', '%d/%m/%Y'),5)";
								}

								if ($dat_anive_fim != '' && $dat_anive_fim != 0) {
									$dat_anive_fim = "RIGHT(STR_TO_DATE('" . $dat_anive_fim . "', '%d/%m/%Y'),5)";
								} else {
									$dat_anive_fim = "RIGHT(STR_TO_DATE('31/12', '%d/%m/%Y'),5)";
								}

								$andAnive = "AND RIGHT(STR_TO_DATE(CL.DAT_NASCIME, '%d/%m/%Y'),5) BETWEEN $dat_anive_ini AND $dat_anive_fim";
							} else {
								$andAnive = "";
							}

							if ($dat_ini_busca != '' && $dat_ini_busca != 0) {
								$dat_ini_busca = fnDataSql($dat_ini_busca);
								$andDataIni = "AND CL.DAT_CADASTR > '$dat_ini_busca 00:00:00'";
							} else {
								$andDataIni = "";
							}

							if ($dat_fim_busca != '' && $dat_fim_busca != 0) {
								$dat_fim_busca = fnDataSql($dat_fim_busca);
								$andDataFim = "AND CL.DAT_CADASTR < '$dat_fim_busca 23:59:59'";
							} else {
								$andDataFim = "";
							}

							if ($filtros != '' && $filtros != 0) {
								$andFiltros = "AND B.COD_FILTRO IN($filtros)";
							} else {
								$andFiltros = "";
							}

							if ($log_externo == 'S') {
								$andExterno = 'and cod_externo != ""';
							} else {
								$andExterno = ' ';
							}

							if ($log_ativo == 'N') {
								$andAtivo = 'and CL.LOG_ESTATUS = "N"';
							} else {
								$andAtivo = 'and CL.LOG_ESTATUS = "S"';
							}

							if ($cod_estado != 0 && $cod_estado != '') {
								$andEstado = "AND CL.COD_ESTADO = $cod_estado";
							} else {
								$andEstado = "";
							}

							if ($cod_municipio != 0 && $cod_municipio != '') {
								$andCidade = "AND CL.COD_MUNICIPIO = $cod_municipio";
							} else {
								$andCidade = "";
							}
						}

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
						</style>


						<div class="col-lg-12">

							<div class="no-more-tables">

								<form name="formLista" id="formLista" method="post" action="">

									<table class="table table-bordered table-striped table-hover tablesorter" id="tablista">

										<thead>
											<tr>
												<th><small>Cod. Externo</small></th>
												<th><small>Apoiador</small></th>
												<th class="text-center"><small>Dt. Nascimento</small></th>
												<th class="text-center"><small>Idade</small></th>
												<th><small>Email</small></th>
												<th><small>Cidade</small></th>
												<th><small>Estado</small></th>
												<th><small>Indicador</small></th>
												<th class="text-center"><small>Dt. Cadastro</small></th>

												<?php

												for ($i = 0; $i < count($des_tpfiltros); $i++) {
												?>
													<th><small><?= $des_tpfiltros[$i] ?></small></th>
												<?php
												}

												?>

												<th><small>Celular/Telefone</small></th>
											</tr>
										</thead>

										<div class="col-md-4 col-sm-offset-2">
											<div class="content-top">
												<div class="col-md-8 top-content">
													<p>Cadastros Totais</p>
													<?php
													$sql = "SELECT COD_CLIENTE FROM CLIENTES WHERE COD_EMPRESA = $cod_empresa";
													$totClientes = mysqli_num_rows(mysqli_query(connTemp($cod_empresa, ''), $sql));
													?>
													<label><?= fnValor($totClientes, 0) ?></label>
													<?php if (00 > 0) { ?>
														<br />
														<span class="bg-danger f12" style="padding: 1px 4px; color: #fff; border-radius: 3px;"><?php echo fnValor(00, 0); ?></span>
													<?php } ?>
												</div>
												<div class="col-md-4">
													<div id="main-pie" class="pie-title-center" data-percent="100">
														<span class="pie-value">100%</span>
													</div>
												</div>
												<div class="clearfix"> </div>
											</div>
										</div>

										<div class="col-md-4">
											<div class="content-top">
												<div class="col-md-8 top-content">
													<p>Cadastros Busca</p>
													<label id="total"></label>
													<?php if (00 > 0) { ?>
														<br />
														<span class="bg-danger f12" style="padding: 1px 4px; color: #fff; border-radius: 3px;"><?php echo fnValor(00, 0); ?></span>
													<?php } ?>
												</div>
												<div class="col-md-4">
													<div id="main-pie2" class="pie-title-center" data-percent="">
														<span class="pie-value" id="percent"></span>
													</div>
												</div>
												<div class="clearfix"> </div>
											</div>
										</div>

										<tbody>

											<?php

											if ($cod_empresa != 0 && $cod_empresa != '') {

												if ($des_superb != '' && $des_superb != 0) {

													//COUNT DA SUPERBUSCA
													$countConsulta = array(
														'conn' => connTemp('136', ''),
														'param_busca' => 'like',
														'TextoConsulta' => "%$des_superb%",
														'joinFiltros' => $innerJoin,
														'colunasAdicionais' => '',
														'tipo' => 'count',
														'limite' => ''
													);

													$total = fnConsultaMULT($countConsulta);

													$registros = 100;
													//fnEscreve($total['CONTADOR']);
													//calcula o número de páginas arredondando o resultado para cima
													$numPaginas = ceil($total / $registros);
													//variavel para calcular o início da visualização com base na página atual
													$inicio = ($registros * $pagina) - $registros;

													$arraybusca = array(
														'conn' => connTemp('136', ''),
														'param_busca' => 'like',
														'TextoConsulta' => "%$des_superb%",
														'joinFiltros' => $innerJoin,
														'colunasAdicionais' => $colunas,
														'tipo' => 'consulta',
														'limite' => "LIMIT $inicio,$registros"
													);

													$count = 0;

													$dadosdabusca = fnConsultaMULT($arraybusca);

													foreach ($dadosdabusca as $objeto) {
														foreach ($objeto as $qrApoia) {

															$idade = "";

															if ($qrApoia['NUM_CELULAR'] != "" && $qrApoia['NUM_TELEFON'] != "") {

																$tel = $qrApoia['NUM_CELULAR'] . "<br><div class='push5'></div>" . $qrApoia['NUM_TELEFON'];
															} else if ($qrApoia['NUM_CELULAR'] != "" && $qrApoia['NUM_TELEFON'] == "") {

																$tel = $qrApoia['NUM_CELULAR'];
															} else {

																$tel = $qrApoia['NUM_TELEFON'];
															}

															if ($qrApoia['DAT_NASCIME'] != "") {
																$idade = date_diff(date_create(fnDataSql($qrApoia['DAT_NASCIME'])), date_create('now'))->y;
															}

															$linhas = "";

															for ($i = 0; $i < count($des_tpfiltros); $i++) {
																$alias = explode(' ', strtoupper(fnacentos($des_tpfiltros[$i])));
																if ($qrApoia[$alias['0']] == "") {
																	$linhas .= "<td><small>SEM INFORMAÇÃO</small></td>";
																} else {
																	$linhas .= "<td><small>" . $qrApoia[$alias['0']] . "</small></td>";
																}
															}

															$count++;

															echo "
																		<tr>
																		  <td class='text-center'><small>" . $qrApoia['COD_EXTERNO'] . "</small></td>
																		  <td><a href='action.do?mod=" . fnEncode(1423) . "&id=" . fnEncode($cod_empresa) . "&idC=" . fnEncode($qrApoia['COD_CLIENTE']) . "' class='f14' target='_blank'><small>" . $qrApoia['NOM_CLIENTE'] . "</small></a></td>
																		  <td class='text-center'><small>" . $qrApoia['DAT_NASCIME'] . "</small></td>
																		  <td class='text-center'><small>" . $idade . "</small></td>
																		  <td><small>" . $qrApoia['DES_EMAILUS'] . "</small></td>
																		  <td><small>" . strtoupper($qrApoia['NOM_CIDADEC']) . "</small></td>
																	  	  <td><small>" . $qrApoia['COD_ESTADOF'] . "</small></td>
																		  <td><small>" . $qrApoia['NOM_INDICADOR'] . "</small></td>
																		  <td class='text-center'><small>" . fnDataShort($qrApoia['DAT_CADASTR']) . "</small></td>
																		  $linhas
																		  <td><small>" . $tel . "</small></td>
																		</tr>
																	
																		";
														}
													}
													// echo '<pre>';
													// print_r ($dadosdabusca);
													// echo '</pre>';

												} else {

													//ARRUMAR COUNT DEPOIS JUNTO COM SUPER BUSCA	
													$sql = "SELECT 1
                                                                                                                                FROM CLIENTES CL
                                                                                                                                LEFT JOIN municipios M ON M.COD_MUNICIPIO = CL.COD_MUNICIPIO
                                                                                                                                LEFT JOIN DADOS_APOIADOR DA ON DA.COD_CLIENTE = CL.COD_CLIENTE
                                                                                                                                LEFT JOIN regiao_usuario R ON R.COD_MUNICIPIO = CL.COD_MUNICIPIO AND R.COD_EMPRESA = CL.COD_EMPRESA
                                                                                                                                LEFT JOIN filtros_cliente F ON F.COD_TPFILTRO = R.COD_TPFILTRO AND F.COD_FILTRO = R.COD_FILTRO AND F.COD_EMPRESA = R.COD_EMPRESA
                                                                                                                                WHERE CL.COD_EMPRESA = 136 AND CL.LOG_ESTATUS = 'S' AND CL.LOG_AVULSO = 'N' AND CL.COD_MUNICIPIO IN(
                                                                                                                                SELECT DISTINCT COD_MUNICIPIO
                                                                                                                                FROM regiao_usuario
                                                                                                                                WHERE cod_empresa=$cod_empresa AND cod_tpfiltro=28 AND cod_filtro IN($cod_filtros))
";
													// fnEscreve($sql);

													$resPagina = mysqli_query(connTemp($cod_empresa, ''), $sql);
													$total = mysqli_num_rows($resPagina);
													//seta a quantidade de itens por página, neste caso, 2 itens
													$registros = 100;
													//fnEscreve($total['CONTADOR']);
													//calcula o número de páginas arredondando o resultado para cima
													$numPaginas = ceil($total / $registros);
													//variavel para calcular o início da visualização com base na página atual
													$inicio = ($registros * $pagina) - $registros;

													//fnEscreve($total);

													$sql = "SELECT CL.COD_CLIENTE, 
                                                                                                                                        CL.NOM_CLIENTE, 
                                                                                                                                        CL.DAT_NASCIME, 
                                                                                                                                        CL.DES_EMAILUS, 
                                                                                                                                        CL.DAT_CADASTR, 
                                                                                                                                        CL.NUM_CELULAR, 
                                                                                                                                        CL.NUM_TELEFON, 
                                                                                                                                        CL.NUM_CEPOZOF CEP, 
                                                                                                                                        M.NOM_MUNICIPIO, 
                                                                                                                                        CL.COD_ESTADOF, 
                                                                                                                                        CL.COD_EXTERNO,
                                                                                                                                        F.DES_FILTRO, (
                                                                                                                                        SELECT A.NOM_CLIENTE
                                                                                                                                        FROM CLIENTES A
                                                                                                                                        WHERE A.COD_CLIENTE = CL.COD_INDICAD) AS NOM_INDICADOR
                                                                                                                                        FROM CLIENTES CL
                                                                                                                                        LEFT JOIN municipios M ON M.COD_MUNICIPIO = CL.COD_MUNICIPIO
                                                                                                                                        LEFT JOIN DADOS_APOIADOR DA ON DA.COD_CLIENTE = CL.COD_CLIENTE
                                                                                                                                        LEFT JOIN regiao_usuario R ON R.COD_MUNICIPIO = CL.COD_MUNICIPIO AND R.COD_EMPRESA = CL.COD_EMPRESA
                                                                                                                                        LEFT JOIN filtros_cliente F ON F.COD_TPFILTRO = R.COD_TPFILTRO AND F.COD_FILTRO = R.COD_FILTRO AND F.COD_EMPRESA = R.COD_EMPRESA 
                                                                                                                                        WHERE CL.COD_EMPRESA = 136 AND CL.LOG_ESTATUS = 'S' AND CL.LOG_AVULSO = 'N' AND CL.COD_MUNICIPIO IN(
                                                                                                                                        SELECT DISTINCT COD_MUNICIPIO
                                                                                                                                        FROM regiao_usuario
                                                                                                                                        WHERE cod_empresa=136 AND cod_tpfiltro=28 AND cod_filtro IN($cod_filtros))
                                                                                                                                        GROUP BY CL.cod_cliente
                                                                                                                                        ORDER BY CL.NOM_CLIENTE
                                                                                                                                        LIMIT $inicio,$registros";
													//fnEscreve($sql);
													$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

													$count = 0;
													while ($qrListaBusca = mysqli_fetch_assoc($arrayQuery)) {

														$idade = "";

														if ($qrListaBusca['NUM_CELULAR'] != "" && $qrListaBusca['NUM_TELEFON'] != "") {

															$tel = $qrListaBusca['NUM_CELULAR'] . "<br><div class='push5'></div>" . $qrListaBusca['NUM_TELEFON'];
														} else if ($qrListaBusca['NUM_CELULAR'] != "" && $qrListaBusca['NUM_TELEFON'] == "") {

															$tel = $qrListaBusca['NUM_CELULAR'];
														} else {

															$tel = $qrListaBusca['NUM_TELEFON'];
														}

														if ($qrListaBusca['DAT_NASCIME'] != "") {
															$idade = date_diff(date_create(fnDataSql($qrListaBusca['DAT_NASCIME'])), date_create('now'))->y;
														}
														// fnEscreve($qrListaBusca['CEP']);
														// fnEscreve(substr_replace(str_pad($qrListaBusca['CEP'], 8, '0', STR_PAD_LEFT), '-', 5, 0));

														$count++;

														echo "
																	<tr>
																	  <td class='text-center'><small>" . $qrListaBusca['COD_EXTERNO'] . "</small></td>
																	  <td><a href='action.do?mod=" . fnEncode(1423) . "&id=" . fnEncode($cod_empresa) . "&idC=" . fnEncode($qrListaBusca['COD_CLIENTE']) . "' class='f14' target='_blank'><small>" . $qrListaBusca['NOM_CLIENTE'] . "</small></a></td>
																	  <td class='text-center'><small>" . $qrListaBusca['DAT_NASCIME'] . "</small></td>
																	  <td class='text-center'><small>" . $idade . "</small></td>
																	  <td><small>" . $qrListaBusca['DES_EMAILUS'] . "</small></td>
																	  <td><small>" . $qrListaBusca['NOM_MUNICIPIO'] . "</small></td>
																	  <td><small>" . $qrListaBusca['COD_ESTADOF'] . "</small></td>
																	  <td><small>" . $qrListaBusca['NOM_INDICADOR'] . "</small></td>
																	  <td class='text-center'><small>" . fnDataShort($qrListaBusca['DAT_CADASTR']) . "</small></td>
																	  <td><small>" . $qrListaBusca['DES_FILTRO'] . "</small></td>
																	  <td><small>" . $tel . "</small></td>
																	</tr>
																
																	";
													}
												}
											}
											?>

										</tbody>
										<?php if ($cod_empresa != 0 && $cod_empresa != '') {  ?>
											<tfoot>
												<tr>
													<th class="" colspan="100">
														<ul class="pagination pagination-sm">
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
												<tr>
													<th class="" colspan="100">
														<!-- <div class="col-xs-2"> -->
														<a class="btn btn-info btn-sm exportarCSV pull-left"> <i class="fa fa-file-excel" aria-hidden="true"></i>&nbsp; Exportar</a>
														<!-- </div> -->
													</th>

												</tr>
											</tfoot>
									<?php }

										// fnEscreve(); 


										//fim verifica post
									}
									?>

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

<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />
<script src="js/pie-chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.min.js"></script>
<script src="js/plugins/Chart_Js/utils.js"></script>

<script type="text/javascript">
	$(document).ready(function() {

		if ('<?= $cod_estado ?>' != 0 && '<?= $cod_estado ?>' != '') {

			carregaComboCidades('<?= $cod_estado ?>');

		}

		//chosen obrigatório
		$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
		$('#formulario').validator();

		var percent = <?= ceil((($total / $totClientes) * 100)) ?>;
		// alert(percent);

		$("#total").text("<?= $total ?>");
		$("#main-pie2").attr("data-percent", percent);
		// $("#percent").text('1');

		$('#main-pie,#main-pie2').pieChart({
			barColor: '#2c3e50',
			trackColor: '#eee',
			lineCap: 'round',
			lineWidth: 8,
			onStep: function(from, to, percent) {
				$(this.element).find('.pie-value').text(Math.round(percent) + '%');
			}
		});

		$('.datePicker2').datetimepicker({
			viewMode: "months",
			format: 'DD/MM'
		}).on('changeDate', function(e) {
			$(this).datetimepicker('hide');
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

		$("#COD_ESTADO").change(function() {
			cod_estado = $(this).val();
			carregaComboCidades(cod_estado);
			// estado = $("#COD_ESTADO option:selected").text();
			// $('#COD_ESTADOF').val(estado);
			// $('#NOM_CIDADEC').val('');
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
										url: "relatorios/ajxCadApoiador.do?opcao=exportar3&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>",
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

		var tpFiltros_e_filtros = '<?php echo $tpFiltros_e_filtros; ?>';

		if (tpFiltros_e_filtros != "") {

			var todosFiltros = tpFiltros_e_filtros.split(';');

			for (var i = 0; i < todosFiltros.length; i++) {

				var arrTpFiltro_e_filtros = todosFiltros[i].split(':');

				if (arrTpFiltro_e_filtros[0] != '') {

					var filtros = arrTpFiltro_e_filtros[1].split(',');

					for (var j = 0; j < filtros.length; j++) {

						$("#COD_FILTRO_" + arrTpFiltro_e_filtros[0] + " option[value=" + Number(filtros[j]) + "]").prop("selected", "true");

					}

				}

				$("#COD_FILTRO_" + arrTpFiltro_e_filtros[0]).trigger("chosen:updated");

			}

		}

	});

	$(document).on('change', '#COD_EMPRESA', function() {
		$("#dKey").val($("#COD_EMPRESA").val());
	});

	function carregaComboCidades(cod_estado) {
		$.ajax({
			method: 'POST',
			url: 'ajxComboMunicipio.php?id=<?= fnEncode($cod_empresa) ?>',
			data: {
				COD_ESTADO: cod_estado
			},
			beforeSend: function() {
				$('#relatorioCidade').html('<div class="loading" style="width: 100%;"></div>');
			},
			success: function(data) {
				$("#relatorioCidade").html(data);
				$("#formulario #COD_MUNICIPIO").val("<?php echo $cod_municipio; ?>").trigger("chosen:updated");
			}
		});
	}

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