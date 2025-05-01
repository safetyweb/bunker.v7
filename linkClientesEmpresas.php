<?php

$hashLocal = mt_rand();
$cod_filtro = "";
$cod_tpfiltro = "";
$cod_clientes_filtro = "";
$temFiltro = 'N';
$num_cgcecpf = "";
$nom_cliente = "";
$des_emailus = "";
$num_celular = "";
$check_externo = "";
$disableCombo = "";
$cod_indicad = "";
$des_superb = "";
$innerJoin = "";
$andCelular = "";
$andClientes = "";

if (isset($_POST['COD_EMPRESA'])) {

	if (isset($_REQUEST['COD_EMPRESA'])) {
		$cod_empresa = fnLimpacampo(fnDecode($_REQUEST['COD_EMPRESA']));
	} else {
		$cod_empresa = "";
	}

	if (isset($_REQUEST['COD_EMPRESA'])) {
		$cod_empresaCode = fnLimpacampo($_REQUEST['COD_EMPRESA']);
	} else {
		$cod_empresaCode = "";
	}

	if (isset($_REQUEST['COD_CLIENTE'])) {
		$cod_cliente  = fnLimpacampo($_REQUEST['COD_CLIENTE']);
	} else {
		$cod_cliente  = "";
	}

	if (isset($_REQUEST['NOM_CLIENTE'])) {
		$nom_cliente  = fnLimpacampo($_REQUEST['NOM_CLIENTE']);
	} else {
		$nom_cliente  = "";
	}

	if (isset($_REQUEST['NUM_CARTAO'])) {
		$num_cartao  = fnLimpacampo($_REQUEST['NUM_CARTAO']);
	} else {
		$num_cartao  = "";
	}

	if (isset($_REQUEST['DES_SUPERB'])) {
		$des_superb  = fnLimpacampo($_REQUEST['DES_SUPERB']);
	} else {
		$des_superb  = "";
	}

	if (isset($_REQUEST['NUM_CGCECPF'])) {
		$num_cgcecpf  = fnLimpaDoc(fnLimpacampo($_REQUEST['NUM_CGCECPF']));
	} else {
		$num_cgcecpf  = "";
	}

	if (empty($_REQUEST['LOG_EXTERNO'])) {
		$log_externo = 'N';
	} else {
		$log_externo = $_REQUEST['LOG_EXTERNO'];
	}

	if (isset($_REQUEST['NUM_CELULAR'])) {
		$num_celular  = fnLimpacampo($_REQUEST['NUM_CELULAR']);
	} else {
		$num_celular  = "";
	}

	if (isset($_REQUEST['DES_EMAILUS'])) {
		$des_emailus  = fnLimpacampo(trim($_REQUEST['DES_EMAILUS']));
	} else {
		$des_emailus  = "";
	}

	if (isset($_REQUEST['COD_INDICAD'])) {
		$cod_indicad = fnLimpaCampo($_REQUEST['COD_INDICAD']);
	} else {
		$cod_indicad = "";
	}

	if (isset($_REQUEST['pagina'])) {
		$pagina  = fnLimpacampo($_REQUEST['pagina']);
	} else {
		$pagina  = "";
	}

	if (isset($_REQUEST['COUNT_FILTROS'])) {
		$count_filtros = fnLimpacampo($_REQUEST['COUNT_FILTROS']);
	} else {
		$count_filtros = "";
	}

	$andFiltros = "";
	$des_tpfiltros = [];
	$colunas = "";
	$filtros = "";

	if ($count_filtros != "") {

		for ($i = 0; $i < $count_filtros; $i++) {

			$cod_filtro = "";

			if (isset($_POST["COD_FILTRO_$i"])) {

				$Arr_COD_FILTRO = $_POST["COD_FILTRO_$i"];

				if (fnLimpacampo($_POST["COD_TPFILTRO_$i"]) != '') {

					$cod_filtro = $cod_filtro . fnLimpacampo($_POST["COD_TPFILTRO_$i"]) . ":";
				}

				for ($j = 0; $j < count($Arr_COD_FILTRO); $j++) {

					$cod_filtro = $cod_filtro . $Arr_COD_FILTRO[$j] . ",";
					$filtros = $filtros . $Arr_COD_FILTRO[$j] . ",";
				}
			}

			if ($_POST["COD_FILTRO_$i"] != '') {

				$cod_filtro = rtrim($cod_filtro, ',');

				$tpFiltros_e_filtros = $tpFiltros_e_filtros . $cod_filtro . ';';

				$filtros_div = explode(':', $cod_filtro);

				$cod_tpfiltro = $filtros_div[0];
				$cod_filtros = $filtros_div[1];

				$sql = "SELECT DES_TPFILTRO FROM TIPO_FILTRO WHERE COD_TPFILTRO = $cod_tpfiltro";
				$qrTipo = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa, ''), $sql));
				array_push($des_tpfiltros, $qrTipo['DES_TPFILTRO']);
				$campo = explode(' ', strtoupper(fnacentos($qrTipo['DES_TPFILTRO'])));

				$colunas .= $campo[0] . $i . ".DES_FILTRO AS $campo[0],";

				$cod_filtros = rtrim(ltrim($cod_filtros, ','), ',');

				$innerJoin .= "
				INNER JOIN CLIENTE_FILTROS " . $campo[0] . " ON " . $campo[0] . ".COD_FILTRO IN($cod_filtros) AND " . $campo[0] . ".COD_TPFILTRO = $cod_tpfiltro AND " . $campo[0] . ".COD_CLIENTE=CL.COD_CLIENTE 
				LEFT JOIN FILTROS_CLIENTE " . $campo[0] . $i . " ON " . $campo[0] . ".COD_FILTRO = " . $campo[0] . $i . ".COD_FILTRO
				";
			}
		}

		$filtros = rtrim(ltrim($filtros, ','), ',');
		// fnEscreve($innerJoin);


		// echo "<pre>";
		// print_r($des_tpfiltros);
		// echo "</pre>";

	}

	if ($_SESSION['SYS_COD_EMPRESA'] == 2) {
		// fnEscreve($andClientes);
		// fnConsoleLog($cod_clientes_filtro);
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

if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode($_GET['id']);
	$mod = fnDecode($_GET['mod']);

	$sql = "SELECT NOM_EMPRESA FROM EMPRESAS WHERE COD_EMPRESA = " . $cod_empresa;

	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($qrBuscaEmpresa)) {
		$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
	}
} else {
	$nom_empresa = "";
}

$sqlInd = "SELECT COD_PERFILS, COD_INDICADOR FROM USUARIOS WHERE COD_USUARIO = " . $_SESSION['SYS_COD_USUARIO'];
$qrUsu = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), trim($sqlInd)));
// fnEscreve($cod_empresa);

if ($mod == 1424) {

	if ($qrUsu['COD_PERFILS'] == 1154) {
		$cod_indicad = $qrUsu['COD_INDICADOR'];
		$disableCombo = "disabled";
	} else {
		$disableCombo = "";
	}
}

if (isset($log_externo) && $log_externo == 'S') {
	$check_externo = 'checked';
} else {
	$check_externo = '';
}

include "labelLibrary.php";
// echo($log_externo);
// fnEscreve($DestinoPg);

//fnEscreve(fnLimpacampo(fnDecode('Oh5QUTtPIOs¢'))); 	
//fnEscreve(fnEncode($_SESSION["SYS_COD_EMPRESA"])); 	
//fnEscreve($num_cgcecpf); 	
//fnMostraForm();

?>

<div class="push30"></div>

<div class="row">

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fal fa-terminal"></i>
					<span class="text-primary"><?php echo $NomTela ?> / <?php echo $nom_empresa; ?></span>
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

						<fieldset>
							<legend>Dados Gerais</legend>

							<div class="row">

								<?php
								if ($_SESSION["SYS_COD_SISTEMA"] != 18) {
								?>
									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Código Interno</label>
											<input type="text" class="form-control input-sm" name="COD_CLIENTE" id="COD_CLIENTE" value="<?php echo $cod_cliente; ?>">
										</div>
									</div>
								<?php
								}
								?>

								<?php

								if ($mod != 1424) {

								?>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Cartão</label>
											<input type="text" class="form-control input-sm" name="NUM_CARTAO" id="NUM_CARTAO" value="<?php echo $num_cartao; ?>">
										</div>
									</div>


								<?php

								}

								?>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">CPF/CNPJ</label>
										<input type="text" class="form-control input-sm cpfcnpj" name="NUM_CGCECPF" id="NUM_CGCECPF" value="<?php echo $num_cgcecpf; ?>">
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label"><?= $labelNome ?></label>
										<input type="text" class="form-control input-sm" name="NOM_CLIENTE" id="NOM_CLIENTE" maxlength="40" value="<?php echo $nom_cliente; ?>">
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Email</label>
										<input type="text" class="form-control input-sm" name="DES_EMAILUS" id="DES_EMAILUS" value="<?php echo $des_emailus; ?>">
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Celular</label>
										<input type="text" class="form-control input-sm sp_celphones" name="NUM_CELULAR" value="<?= $num_celular ?>" id="NUM_CELULAR" maxlength="20">
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<?php
								if ($mod == 1424) {
								?>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Somente Cadastros Externos</label>
											<div class="push5"></div>
											<label class="switch switch-small">
												<input type="checkbox" name="LOG_EXTERNO" id="LOG_EXTERNO" class="switch" value="S" <?= $check_externo ?>>
												<span></span>
											</label>
										</div>
									</div>

									<div class="col-md-4">
										<div class="form-group">
											<label for="inputName" class="control-label ">Indicador</label>
											<select class="chosen-select-deselect" data-placeholder="Selecione o indicador" name="COD_INDICAD" id="COD_INDICAD" <?= $disableCombo ?>>
												<option value=""></option>
												<?php

												$sql = "SELECT DISTINCT A.COD_INDICAD,
												(SELECT DISTINCT NOM_CLIENTE FROM CLIENTES WHERE CLIENTES.COD_CLIENTE=A.COD_INDICAD) AS NOM_INDICADOR 
												FROM CLIENTES A 
												WHERE A.COD_EMPRESA = $cod_empresa
												ORDER BY NOM_INDICADOR";
												$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

												while ($qrIndica = mysqli_fetch_assoc($arrayQuery)) {
												?>
													<option value="<?php echo $qrIndica['COD_INDICAD']; ?>"><?php echo $qrIndica['NOM_INDICADOR']; ?></option>
												<?php
												}
												?>
											</select>
											<script type="text/javascript">
												$('#COD_INDICAD').val('<?= $cod_indicad ?>').trigger("chosen:updated");
											</script>
											<div class="help-block with-errors"></div>
										</div>
										<?php

										if ($disableCombo == 'disabled') {
										?>
											<input type="hidden" name="COD_INDICAD" id="COD_INDICAD" value="<?= $cod_indicad ?>">
										<?php
										}

										?>
									</div>


									<div class="col-md-1 text-center">
										<div class="form-group">
											<label>&nbsp;</label>
											<h5 class="text-muted">-OU-</h5>
										</div>
									</div>

									<div class="col-md-5">
										<div class="form-group">
											<label for="inputName" class="control-label">Superbusca</label>
											<input type="text" class="form-control input-sm" name="DES_SUPERB" id="DES_SUPERB" maxlength="100" value="<?php echo $des_superb; ?>">
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="push20"></div>

								<?php
								}
								?>


							</div>

						</fieldset>



						<?php
						//FILTROS DINÂMICOS
						$countFiltros = 0;

						if ($mod == 1424) {
						?>

							<div class="push20"></div>

							<?php
							//FILTROS DINÂMICOS
							$countFiltros = 0;

							$sql = "SELECT COD_TPFILTRO, DES_TPFILTRO FROM TIPO_FILTRO
							WHERE COD_EMPRESA = $cod_empresa
							ORDER BY NUM_ORDENAC";
							$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), trim($sql));

							if (mysqli_num_rows($arrayQuery) > 0) {

								$countObjeto = 0
							?>

								<!-- filtros dinâmicos -->

								<fieldset>
									<legend>Filtros Dinâmicos</legend>


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
															<option value="0">Sem Informação</option>
															<?php
															$sqlFiltro = "SELECT COD_FILTRO, DES_FILTRO FROM FILTROS_CLIENTE
															WHERE COD_TPFILTRO = " . $qrTipo['COD_TPFILTRO'] . "
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
															$cod_filtros = "";

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

										<!-- <div class="col-md-2">
										<div class="push20"></div>
										<button type="submit" name="BUS" id="BUS" class="btn btn-primary btn-sm btn-block getBtn"><i class="fa fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
									</div> -->


									</div>



								<?php
							} else {
								?>
									<!-- <div class="col-xs-2 col-xs-offset-5">
										<div class="push20"></div>
										<button type="submit" name="BUS" id="BUS" class="btn btn-primary btn-sm btn-block getBtn"><i class="fal fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
									</div> -->
								<?php
							}
								?>

								</fieldset>


							<?php
						}

						if (!is_null($RedirectPg)) {
							$DestinoPg = fnEncode($RedirectPg);
						} else {
							$DestinoPg = "";
						}

						if ($cod_empresa == 136) {
							$DestinoPg = fnEncode(1423);
						}
						// else if($cod_empresa == 224){
						// 	$DestinoPg = fnEncode(1688);
						// }

						//echo fndecode($DestinoPg);

							?>



							<div class="push10"></div>
							<hr>
							<div class="form-group text-right col-lg-12">

								<button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
								<a href="action.do?mod=<?php echo $DestinoPg; ?>&id=<?= fnEncode($cod_empresa) ?>&idC=<?= fnEncode(0) ?>" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Novo <?= $abaNome ?></a>
								<button type="submit" name="BUS" id="BUS" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Pesquisar</button>

							</div>

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

						if ($cod_empresa != 0) {

							$pagina = (isset($_REQUEST['pagina'])) ? $_REQUEST['pagina'] : 1;

							$andIndicad = "";
							if ($mod == 1424) {
								if ($cod_indicad != "") {
									$andIndicad = "AND CL.COD_INDICAD = $cod_indicad";
								} else {
									$andIndicad = "";
								}
							}

							$andCodigo = ' ';
							if ($cod_cliente != 0) {
								$andCodigo = 'and CL.cod_cliente=' . $cod_cliente;
							}

							$andNome = ' ';
							if ($nom_cliente != '') {

								if ($mod == 1424) {
									$andNome = 'and CL.nom_cliente like "%' . $nom_cliente . '%"';
								} else {
									$andNome = 'and CL.nom_cliente like "' . $nom_cliente . '%"';
								}
							}

							$andCartao = ' ';
							if ($num_cartao != '') {
								$andCartao = 'and CL.num_cartao=' . $num_cartao;
							}

							$andCpf = ' ';
							if ($num_cgcecpf != '') {
								$andCpf = 'and CL.num_cgcecpf =' . $num_cgcecpf;
							}

							$andEmail = ' ';
							if ($des_emailus != '') {
								$andEmail = 'and CL.des_emailus="' . $des_emailus . '"';
							}

							$andcelular = ' ';
							if ($num_celular != '') {
								$andcelular = 'and CL.num_celular="' . $num_celular . '"';
							}

							$andExterno = ' ';
							if ($log_externo == 'S') {
								$andExterno = 'and CL.cod_externo != ""';
							}


							$sql = "select count(CL.COD_CLIENTE) as CONTADOR from  " . connTemp($cod_empresa, 'true') . ".clientes CL $innerJoin where CL.cod_empresa = " . $cod_empresa . " 
							" . $andCodigo . "
							" . $andNome . "
							" . $andCartao . "
							" . $andCpf . "
							" . $andEmail . "
							" . $andCelular . "
							$andExterno
							$andClientes
							$andIndicad
							$andcelular
							and CL.LOG_AVULSO = 'N'
							order by CL.NOM_CLIENTE ";
							// fnEscreve($sql);

							$resPagina = mysqli_query(connTemp($cod_empresa, ''), $sql);
							$total = mysqli_fetch_assoc($resPagina);
							//seta a quantidade de itens por página, neste caso, 2 itens
							$registros = 100;
							//fnEscreve($total['CONTADOR']);
							//calcula o número de páginas arredondando o resultado para cima
							$numPaginas = ceil($total['CONTADOR'] / $registros);
							//variavel para calcular o início da visualização com base na página atual
							$inicio = ($registros * $pagina) - $registros;

							$total = $total['CONTADOR'];
						}

						if ($cod_empresa == 136) {
							$txt_externo = "Cód. Externo";
							$externo = 'COD_EXTERNO';
						} else if ($cod_empresa == 311) {
							$txt_externo = "";
							$externo = '';
						} else {
							$txt_externo = "Num. Cartão";
							$externo = 'NUM_CARTAO';
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
												<th width="40"></th>
												<th>Código</th>
												<th><?= $txt_externo ?></th>
												<th>Nome do Cliente</th>
												<th>e-Mail</th>
												<th>CPF</th>
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

											if ($cod_empresa != 0) {

												if ($des_superb != "") {

													$arraybusca = array(
														'conn' => connTemp($cod_empresa, ''),
														'param_busca' => 'like',
														'cod_empresa' => $cod_empresa,
														'TextoConsulta' => "%$des_superb%",
														'joinFiltros' => $innerJoin,
														'colunasAdicionais' => '',
														'tipo' => 'consulta',
														'limite' => ""
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
																$linhas .= "<td><small>" . $qrApoia[$alias[0]] . "</small></td>";
															}

															$count++;

															echo "
															<tr>
															<td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></th>
															<td>" . $qrApoia['COD_CLIENTE'] . "</td>
															<td>" . $qrApoia[$externo] . "</td>
															<td>" . $qrApoia['NOM_CLIENTE'] . "</td>
															<td>" . $qrApoia['DES_EMAILUS'] . "</td>
															<td>" . $qrApoia['NUM_CGCECPF'] . "</td>
															</tr>
															<input type='hidden' id='ret_COD_CLIENTE_" . $count . "' value='" . fnEncode($qrApoia['COD_CLIENTE']) . "'>
															<input type='hidden' id='ret_COD_EMPRESA_" . $count . "' value='" . fnEncode($cod_empresa) . "'>
															</tr>

															";
														}
													}
													// echo '<pre>';
													// print_r ($dadosdabusca);
													// echo '</pre>';

												} else {

													if ($cod_cliente != 0) {
														$andCodigo = 'and cod_cliente=' . $cod_cliente;
													} else {
														$andCodigo = ' ';
													}

													if ($nom_cliente != '') {

														if ($mod == 1424) {
															//gabinete
															$andNome = 'and nom_cliente like "%' . $nom_cliente . '%"';
														} else {
															$andNome = 'and nom_cliente like "' . $nom_cliente . '%"';
														}
													} else {
														$andNome = ' ';
													}

													if ($num_cartao != '') {
														$andCartao = 'and num_cartao=' . $num_cartao;
													} else {
														$andCartao = ' ';
													}

													if ($num_cgcecpf != '') {
														$andCpf = 'and num_cgcecpf =' . $num_cgcecpf;
													} else {
														$andCpf = ' ';
													}

													if ($des_emailus != '') {
														$andEmail = 'and des_emailus="' . $des_emailus . '"';
													} else {
														$andEmail = ' ';
													}

													if ($num_celular != '') {
														$andcelular = 'and num_celular="' . $num_celular . '"';
													} else {
														$andCelular = ' ';
													}

													$sql = "select CL.* from CLIENTES CL $innerJoin where CL.cod_empresa = " . $cod_empresa . " 
													" . $andCodigo . "
													" . $andNome . "
													" . $andCartao . "
													" . $andCpf . "
													" . $andEmail . "
													" . $andCelular . "
													$andExterno
													$andClientes
													$andIndicad
													$andcelular
													and CL.LOG_AVULSO = 'N'
													order by CL.NOM_CLIENTE limit $inicio,$registros";
													$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
													// fnEscreve($sql);

													$count = 0;
													while ($qrListaEmpresas = mysqli_fetch_assoc($arrayQuery)) {
														$count++;

														echo "
														<tr>
														<td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></th>
														<td>" . $qrListaEmpresas['COD_CLIENTE'] . "</td>
														<td>" . $qrListaEmpresas[$externo] . "</td>
														<td>" . $qrListaEmpresas['NOM_CLIENTE'] . "</td>
														<td>" . $qrListaEmpresas['DES_EMAILUS'] . "</td>
														<td>" . $qrListaEmpresas['NUM_CGCECPF'] . "</td>
														</tr>
														<input type='hidden' id='ret_COD_CLIENTE_" . $count . "' value='" . fnEncode($qrListaEmpresas['COD_CLIENTE']) . "'>
														<input type='hidden' id='ret_COD_EMPRESA_" . $count . "' value='" . fnEncode($cod_empresa) . "'>
														";
													}
												}
											}
											?>

										</tbody>

										<?php if ($cod_empresa != 0 && $des_superb == "") {  ?>
											<tfoot>
												<tr>
													<th class="" colspan="100">
														<div class="text-center">
															<ul class="pagination pagination-sm">
																<?php
																$numPaginasPorGrupo = 10;
																$paginaInicialGrupo = max(1, $pagina - floor($numPaginasPorGrupo / 2));
																$paginaFinalGrupo = min($paginaInicialGrupo + $numPaginasPorGrupo - 1, $numPaginas);

																for ($i = $paginaInicialGrupo; $i <= $paginaFinalGrupo; $i++) {
																	if ($pagina == $i) {
																		$paginaAtiva = "active";
																	} else {
																		$paginaAtiva = "";
																	}
																	echo "<li class='pagination $paginaAtiva'><a href='javascript:void(0);' onclick='page(" . $i . ")' style='text-decoration: none;'>" . $i . "</a></li>";
																}
																?>
															</ul>
														</div>
													</th>
												</tr>
											</tfoot>

									<?php }


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

<script src="js/pie-chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.min.js"></script>
<script src="js/plugins/Chart_Js/utils.js"></script>

<script type="text/javascript">
	$(document).ready(function() {

		var percent = "<?= ceil((($total / $totClientes) * 100)) ?>";
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

		$(document).on('keypress', function(e) {
			if (e.which == 13) {
				e.preventDefault();
				$("#BUS").click();
			}
		});

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

		var tpFiltros_e_filtros = '<?php echo @$tpFiltros_e_filtros; ?>';

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