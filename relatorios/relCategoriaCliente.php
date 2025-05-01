<?php

if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set(
		'display_startup_errors',
		1
	);
	error_reporting(E_ALL);
}

$log_antigo = '';

//echo "<h5>_".$opcao."</h5>";


$itens_por_pagina = 50;
$pagina = 1;
$cod_univend = '';
$dias30 = "";
$dat_ini = "";
$dat_fim = "";
$numCartao = "";
$nomCliente = "";
$cod_vendapdv = "";
$tipoVenda = "T";
$hashLocal = mt_rand();
$cod_categoria = '';
$log_antigo = '';
$mostraCracha = '';
$qtd_diashist = '';

//inicialização de variáveis
$hoje = fnFormatDate(date("Y-m-d"));
//$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 1 days')));
$dias30 = fnFormatDate(date("Y-m-d"));

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_empresa = fnLimpaCampo($_REQUEST['COD_EMPRESA']);

		if ($cod_univend != '') {
			$cod_univend = fnLimpaCampo($_REQUEST['COD_UNIVEND']);
		}

		$cod_categoria = fnLimpaCampoZero(@$_REQUEST['COD_CATEGORIA']);
		$dat_ini = fnDataSql(@$_POST['DAT_INI']);
		$dat_fim = fnDataSql(@$_POST['DAT_FIM']);

		if (empty(@$_REQUEST['LOG_ANTIGO'])) {
			$log_antigo = 'N';
		} else {
			$log_antigo = @$_REQUEST['LOG_ANTIGO'];
		}

		// fnEscreve($cod_univend);

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];
	}
}


//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode($_GET['id']);
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
	//fnEscreve('entrou else');
}

//busca dados da tabela
$sql = "SELECT * FROM EMPRESA_CLASSIFICA WHERE COD_EMPRESA = $cod_empresa ";
//fnEscreve($sql);
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
$qrBuscaClassifica = mysqli_fetch_assoc($arrayQuery);
//fnEscreve($sql);

if (isset($qrBuscaClassifica)) {
	//fnEscreve("entrou if");

	$cod_classifica = $qrBuscaClassifica['COD_CLASSIFICA'];
	$qtd_diashist = $qrBuscaClassifica['QTD_DIASHIST'];
	$qtd_mesclass = $qrBuscaClassifica['QTD_MESCLASS'];
	$qtd_mreclass = $qrBuscaClassifica['QTD_MRECLASS'];
}

//fnEscreve($qtd_diashis);	

$hoje = fnFormatDate(date("Y-m-d"));
$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30 . '- ' . $qtd_diashist . ' days')));
//$dias30 = fnFormatDate(date("Y-m-d"));
//inicialização das variáveis - default	
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
	$dat_ini = fnDataSql($hoje);
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
	$dat_fim = fnDataSql($hoje);
}

if ($log_antigo == "S") {
	$checkAntigo = "checked";
	$dspDatas = "none";
} else {
	$checkAntigo = "";
	$dspDatas = "block";
}

//busca revendas do usuário
include "unidadesAutorizadas.php";

// fnEscreve($lojasSelecionadas);

?>

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

				<?php
				$formBack = "1019";
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

				<div class="push10"></div>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Filtros</legend>

							<div class="row">

								<div class="col-md-1">
									<div class="form-group">
										<label for="inputName" class="control-label">Histórico</label>
										<input type="text" class="form-control input-sm leitura" name="QTD_DIASHIST" id="QTD_DIASHIST" maxlength="40" value="<?php echo $qtd_diashist; ?>">
										<div class="help-block with-errors">dias</div>
									</div>
								</div>

								<div class="col-md-1">
									<div class="form-group">
										<label for="inputName" class="control-label">Classificação Online</label>
										<div class="push5"></div>
										<label class="switch">
											<input type="checkbox" name="LOG_ANTIGO" id="LOG_ANTIGO" class="switch" value="S" <?= $checkAntigo ?>>
											<span></span>
										</label>
										<script type="text/javascript">
											$("#LOG_ANTIGO").change(function() {
												if ($(this).prop("checked")) {
													$("#caixaDatas").fadeOut('fast');
												} else {
													$("#caixaDatas").fadeIn('fast');
												}
											});
										</script>
										<div class="help-block with-errors">clientes + históricos de classificações</div>
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label required">Unidade de Atendimento</label>
										<?php include "unidadesAutorizadasComboMulti.php"; ?>
									</div>
								</div>

								<div id="caixaDatas" style="display: <?= $dspDatas ?>;">

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Data Inicial</label>

											<div class="input-group date datePicker" id="DAT_INI_GRP">
												<input type='text' class="form-control input-sm data" name="DAT_INI" id="DAT_INI" value="<?php echo fnDataShort($dat_ini); ?>" required />
												<span class="input-group-addon">
													<span class="glyphicon glyphicon-calendar"></span>
												</span>
											</div>
											<div class="help-block with-errors">classificação</div>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Data Final</label>

											<div class="input-group date datePicker" id="DAT_FIM_GRP">
												<input type='text' class="form-control input-sm data" name="DAT_FIM" id="DAT_FIM" value="<?php echo fnDataShort($dat_fim); ?>" required />
												<span class="input-group-addon">
													<span class="glyphicon glyphicon-calendar"></span>
												</span>
											</div>
											<div class="help-block with-errors">classificação</div>
										</div>
									</div>

								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Categorias</label>
										<select data-placeholder="Selecione uma categoria" name="COD_CATEGORIA" id="COD_CATEGORIA" class="chosen-select-deselect">
											<option value=""></option>
											<?php
											$sql = "select * from CATEGORIA_CLIENTE WHERE COD_EMPRESA = $cod_empresa order by NUM_ORDENAC";
											$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
											//fnEscreve($sql);

											while ($qrListaCategorias = mysqli_fetch_assoc($arrayQuery)) {

												echo "
																				  <option value='" . $qrListaCategorias['COD_CATEGORIA'] . "' >" . $qrListaCategorias['NOM_FAIXACAT'] . "</option> 
																				";
											}
											?>
										</select>
										<script type="text/javascript">
											$("#COD_CATEGORIA").val("<?= $cod_categoria ?>").trigger("chosen:updated")
										</script>
										<div class="help-block with-errors"></div>
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

						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="LOJAS" id="LOJAS" value="<?= $lojasSelecionadas ?>">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
						<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
						<div class="push5"></div>

					</form>

				</div>
			</div>
		</div>

		<div class="push20"></div>

		<div class="portlet portlet-bordered">

			<div class="portlet-body">

				<div class="login-form">

					<h4>Total On Line das Categorias</h4>

					<div class="push30"></div>


					<div class="flexrow">

						<?php

						$sqlTotal = "SELECT COUNT(DISTINCT COD_CLIENTE) QTD_CLI FROM CLIENTES WHERE COD_EMPRESA = $cod_empresa";
						$arrTotal = mysqli_query(connTemp($cod_empresa, ''), $sqlTotal);
						$qrTotal = mysqli_fetch_assoc($arrTotal);
						$qtdTotal = $qrTotal['QTD_CLI'];


						?>

						<div class="form-group text-center col">

							<div class="push20"></div>

							<p><span id="SEM_CAT"><?= fnValor($qtdTotal, 0) ?></span></p>
							<div class="pie-title-center main-pie" data-percent="100">
								<span class="pie-value">100%</span>
							</div>
							<div class="push10"></div>
							<p><b>Total de Clientes</b></p>

							<div class="push20"></div>

						</div>

						<?php

						$sqlSemCat = "SELECT COUNT(DISTINCT COD_CLIENTE) QTD_CLI FROM CLIENTES WHERE COD_EMPRESA = $cod_empresa AND COD_CATEGORIA = 0";
						$arrSemCat = mysqli_query(connTemp($cod_empresa, ''), $sqlSemCat);
						$qrSemCat = mysqli_fetch_assoc($arrSemCat);
						$qtdSemCat = $qrSemCat['QTD_CLI'];
						$pctSemCat = round(($qtdSemCat / $qtdTotal) * 100);

						?>

						<div class="form-group text-center col">

							<div class="push20"></div>

							<p><span id="SEM_CAT"><?= fnValor($qtdSemCat, 0) ?></span></p>
							<div class="pie-title-center main-pie" data-percent="<?= $pctSemCat ?>">
								<span class="pie-value"><?= $pctSemCat ?>%</span>
							</div>
							<div class="push10"></div>
							<p><b>Sem Categoria</b></p>

							<div class="push20"></div>

						</div>

						<?php


						$sqlCat = "SELECT * FROM CATEGORIA_CLIENTE WHERE COD_EMPRESA = $cod_empresa ORDER BY VAL_FAIXAINI ASC";

						// fnEscreve($sql);
						$arrCat = mysqli_query(connTemp($cod_empresa, ''), $sqlCat);

						while ($qrCat = mysqli_fetch_assoc($arrCat)) {

							$sqlCount = "SELECT COUNT(DISTINCT COD_CLIENTE) QTD_CLI FROM CLIENTES WHERE COD_EMPRESA = $cod_empresa AND COD_CATEGORIA = $qrCat[COD_CATEGORIA]";
							$arrCount = mysqli_query(connTemp($cod_empresa, ''), $sqlCount);
							$qrCount = mysqli_fetch_assoc($arrCount);
							$qtdCli = $qrCount['QTD_CLI'];
							$pctCli = round(($qtdCli / $qtdTotal) * 100);

						?>

							<div class="form-group text-center col">

								<div class="push20"></div>

								<p><span id="<?= $qrCat['NOM_FAIXACAT'] ?>"><?= fnValor($qtdCli, 0) ?></span></p>
								<div class="pie-title-center main-pie" data-percent="<?= $pctCli ?>">
									<span class="pie-value"><?= $pctCli ?>%</span>
								</div>
								<div class="push10"></div>
								<p><b>Cliente <?= $qrCat['NOM_FAIXACAT'] ?></b></p>

								<div class="push20"></div>

							</div>

						<?php

						}

						?>

					</div>

					<div class="push"></div>

				</div>

			</div>
		</div>
		<!-- fim Portlet -->

		<div class="push20"></div>

		<div class="portlet portlet-bordered">

			<div class="portlet-body">

				<div class="login-form">

					<h4>Movimentação nas Categorias</h4>

					<div class="push30"></div>

					<div class="row">

						<div class="col-lg-12">

							<div class="no-more-tables">

								<table class="table table-bordered table-striped table-hover tableSorter">
									<thead>
										<tr>
											<th class="{sorter:false}"></th>
											<th>Nome</th>
											<th>Unidade</th>
											<th>Data Classificacão</th>
											<th>Data Últ. Compra</th>
											<th>Cat. Anterior</th>
											<th>Cat. Atualizada</th>
											<th>Cat. Atual</th>
											<th>Tipo de Classificação</th>
											<th>Venda na Class.</th>
										</tr>
									</thead>
									<tbody id="relatorioConteudo">

										<?php

										$andUnidade = "";

										if ($cod_univend != "" && $cod_univend != "9999") {
											// $andUnidade = "AND b.COD_UNIVEND = $cod_univend";
										}

										$andCat = "";

										if ($cod_categoria != 0) {
											$andCat = "AND B.COD_CATEGORIA = $cod_categoria";
										}

										if ($log_antigo == "S") {
											$groupBy = "";
											$andData = "";
										} else {
											$groupBy = "GROUP BY COD_CLIENTE";
											$andData = "AND DATE(A.DAT_CADASTR) BETWEEN '$dat_ini' AND '$dat_fim'";
											$andData2 = "AND DATE(DAT_CADASTR) BETWEEN '$dat_ini' AND '$dat_fim'";
										}

										$sql = "SELECT B.COD_CLIENTE
															FROM HISTORICO_CLASSIFICA_CLIENTE A
															INNER  JOIN clientes b ON a.cod_cliente=b.cod_cliente
															INNER  JOIN WEBTOOLS.UNIDADEVENDA C ON B.COD_UNIVEND=C.COD_UNIVEND
															WHERE a.cod_empresa = $cod_empresa 
															$andData
															AND b.COD_UNIVEND IN($lojasSelecionadas)
															AND b.LOG_AVULSO = 'N'
															$andUnidade
															$andCat
															$groupBy";

										// fnEscreve($sql);
										$retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
										$totalitens_por_pagina = mysqli_num_rows($retorno);
										$numPaginas = ceil($totalitens_por_pagina / $itens_por_pagina);

										$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

										// $sql = "SELECT 
										//         * 
										//         FROM(
										//             SELECT
										// 		        uni.NOM_FANTASI, 
										// 				B.COD_CLIENTE,
										// 				B.COD_EMPRESA,
										// 				B.NOM_CLIENTE,
										// 				B.DAT_ULTCOMPR,
										// 				A.DAT_CADASTR,
										// 				A.VAL_COMPRAS,
										// 				C.NOM_FAIXACAT AS CATEGORIA_ANTERIOR ,
										// 				E.NOM_FAIXACAT AS CATEGORIA_ATUAL,
										// 				D.NOM_FAIXACAT AS CATEGORIA_NOVA,
										// 		        CASE WHEN A.TIP_CLASSIF='V' THEN 'Venda'
										// 	        WHEN A.TIP_CLASSIF='R' THEN 'Reclassificação' END TIP_CLASSIFICACAO
										// 			FROM HISTORICO_CLASSIFICA_CLIENTE A
										// 			INNER  JOIN clientes b ON a.cod_cliente=b.cod_cliente
										// 			LEFT JOIN CATEGORIA_CLIENTE c ON a.cod_categor=c.cod_categoria AND a.cod_empresa=c.cod_empresa
										// 			LEFT JOIN CATEGORIA_CLIENTE d ON a.cod_categoria_nova=d.cod_categoria AND a.cod_empresa=d.cod_empresa 
										// 			LEFT JOIN CATEGORIA_CLIENTE e ON b.COD_CATEGORIA=e.cod_categoria AND a.cod_empresa=e.cod_empresa 
										// 			LEFT JOIN unidadevenda uni ON uni.COD_UNIVEND=b.COD_UNIVEND
										// 			WHERE a.cod_empresa = $cod_empresa 
										// 			$andData
										// 			AND b.COD_UNIVEND IN($lojasSelecionadas)
										// 			$andUnidade
										// 			$andCat
										// 			ORDER BY B.NOM_CLIENTE,A.COD_REGISTRO desc
										// 			limit $inicio,$itens_por_pagina
										//         )tmpHISTORICO					
										//         $groupBy ";

										$sql = "SELECT * FROM (
																SELECT        uni.NOM_FANTASI,
																              B.COD_CLIENTE,
																              B.COD_EMPRESA,
																              B.NOM_CLIENTE,
																              B.DAT_ULTCOMPR,
																              A.DAT_CADASTR,
																              A.VAL_COMPRAS,
																              C.NOM_FAIXACAT AS CATEGORIA_ANTERIOR,
																              E.NOM_FAIXACAT AS CATEGORIA_ATUAL,
																              D.NOM_FAIXACAT AS CATEGORIA_NOVA,
																              (SELECT COUNT(COD_CLIENTE) FROM HISTORICO_CLASSIFICA_CLIENTE WHERE COD_EMPRESA = A.COD_EMPRESA AND COD_CLIENTE = A.COD_CLIENTE $andData2) TEM_HISTORICO,
																              CASE
																                WHEN A.TIP_CLASSIF = 'V' THEN 'Venda'   WHEN A.TIP_CLASSIF = 'R' THEN 'Reclassificação'   END  TIP_CLASSIFICACAO
																        FROM   HISTORICO_CLASSIFICA_CLIENTE A
																              INNER JOIN CLIENTES B ON a.cod_cliente = b.COD_CLIENTE
																              LEFT JOIN CATEGORIA_CLIENTE C ON A.COD_CATEGOR = C.COD_CATEGORIA AND A.COD_EMPRESA = C.COD_EMPRESA
																              LEFT JOIN CATEGORIA_CLIENTE D ON A.COD_CATEGORIA_NOVA = D.COD_CATEGORIA AND A.COD_EMPRESA = D.COD_EMPRESA
																              LEFT JOIN CATEGORIA_CLIENTE E ON B.COD_CATEGORIA = E.COD_CATEGORIA AND A.COD_EMPRESA = E.COD_EMPRESA
																              LEFT JOIN UNIDADEVENDA UNI ON uni.COD_UNIVEND = b.COD_UNIVEND
																        WHERE  A.COD_EMPRESA = $cod_empresa
																        $andData
																        AND B.COD_UNIVEND IN($lojasSelecionadas)
																        $andUnidade
																		$andCat
																        ORDER  BY A.COD_REGISTRO DESC
																)tmpHISTORICO
																 GROUP BY COD_CLIENTE 
																 ORDER BY NOM_CLIENTE
																 LIMIT  $inicio,$itens_por_pagina";

										$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);


										// fnEscreve($sql);
										$count = 0;
										while ($qrCategoriaCli = mysqli_fetch_assoc($arrayQuery)) {

											$count++;

											//fnEscreve()

											if (@$qrCategoriaCli['LOG_USADO'] == 2) {
												$cliCadastrado = "<i class='fal fa-check text-success' aria-hidden='true'></i>";
												$reenvioTkn = "";
											} else {
												$cliCadastrado = "<i class='fal fa-times text-danger' aria-hidden='true'></i>";
												$reenvioTkn = "<a class='btn btn-xs btn-info' onclick='reenvioTkn(" . @$qrCategoriaCli['COD_TOKEN'] . ")'><span class='fal fa-repeat'></span> Reenviar</a>";
											}

											if ($qrCategoriaCli['TEM_HISTORICO'] == 1) {


												echo "
																	<tr>
																	  <td></td>
																	  <td><small><a href='action.do?mod=" . fnEncode(1024) . "&id=" . fnEncode($cod_empresa) . "&idC=" . fnEncode($qrCategoriaCli['COD_CLIENTE']) . "' target='_blank'>" . $qrCategoriaCli['NOM_CLIENTE'] . "&nbsp;" . $mostraCracha . "</a></small></td>
																	  <td><small>" . $qrCategoriaCli['NOM_FANTASI'] . "</small></td>
																	  <td><small>" . fnDataFull($qrCategoriaCli['DAT_CADASTR']) . "</small></td>
																	  <td><small>" . fnDataFull($qrCategoriaCli['DAT_ULTCOMPR']) . "</small></td>
																	  <td><small>" . $qrCategoriaCli['CATEGORIA_ANTERIOR'] . "</small></td>
																	  <td><small>" . $qrCategoriaCli['CATEGORIA_NOVA'] . "</small></td>
																	  <td><small>" . $qrCategoriaCli['CATEGORIA_ATUAL'] . "</small></td>
																	  <td><small>" . $qrCategoriaCli['TIP_CLASSIFICACAO'] . "</small></td>
																	  <td><small><small>R$ </small>" . fnValor($qrCategoriaCli['VAL_COMPRAS'], 2) . "</small></td>
																	</tr>
																	
																	";
											} else {

										?>

												<tr id="bloco_<?= fnEncode($qrCategoriaCli['COD_CLIENTE']) ?>">
													<td class="text-center">
														<a href="javascript:void(0);" onclick='abreDetail("<?= fnEncode($qrCategoriaCli["COD_CLIENTE"]) ?>")' style="padding:10px;">
															<i class="fa fa-angle-right" aria-hidden="true"></i>
														</a>
													</td>
													<td><small><a href="action.do?mod=<?= fnEncode(1024) ?>&id=<?= fnEncode($cod_empresa) ?>&idC=<?= fnEncode($qrCategoriaCli['COD_CLIENTE']) ?>" target="_blank"><?= $qrCategoriaCli['NOM_CLIENTE'] ?>&nbsp;<?= $mostraCracha ?></a></small></td>
													<td><small><?php echo $qrCategoriaCli['NOM_FANTASI']; ?></small></td>
													<td><small><?php echo fnDataFull($qrCategoriaCli['DAT_CADASTR']); ?></small></td>
													<td><small><?php echo fnDataFull($qrCategoriaCli['DAT_ULTCOMPR']); ?></small></td>
													<td><small><?php echo $qrCategoriaCli['CATEGORIA_ANTERIOR']; ?></small></td>
													<td><small><?php echo $qrCategoriaCli['CATEGORIA_NOVA']; ?></small></td>
													<td><small><?php echo $qrCategoriaCli['CATEGORIA_ATUAL']; ?></small></td>
													<td><small><?php echo $qrCategoriaCli['TIP_CLASSIFICACAO']; ?></small></td>
													<td><small><small>R$ </small><?php echo fnValor($qrCategoriaCli['VAL_COMPRAS'], 2); ?></small></td>
												</tr>

										<?php

											}
										}



										?>

									</tbody>

									<tfoot>

										<tr>
											<th colspan="100">
												<!-- <a class="btn btn-info btn-sm exportarCSV"> <i class="fa fa-file-excel" aria-hidden="true"></i>&nbsp; Exportar</a> -->
												<div class="btn-group dropdown dropleft">
													<button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
														Exportar &nbsp;
														<span class="fas fa-file-excel"></span>
													</button>
													<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
														<li><a href='javascript:void(0)' class='exportarCSV' data-opcao="exportar"><span class="fal fa-users"></span>&nbsp;Geral </a></li>
														<li><a href='javascript:void(0)' class='exportarCSV' data-opcao="exportarDetalhes"><span class="fal fa-user-plus"></span>&nbsp;Detalhado </a></li>
														<!-- <li><a tabindex="-1" href="#">Separated link</a></li> -->
													</ul>
												</div>
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

								<?php //echo $count; 
								?>


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

<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.min.js"></script>
<script src="js/plugins/Chart_Js/utils.js"></script>
<script src="js/pie-chart.js"></script>

<script type="text/javascript">
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

		$(".exportarCSV").click(function() {
			opcaoExp = $(this).attr("data-opcao");
			// alert(opcaoExp);
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
										url: "relatorios/ajxCategoriaCliente.do?opcao=" + opcaoExp + "&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>",
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

		$('.main-pie').pieChart({
			barColor: '#2c3e50',
			trackColor: '#eee',
			lineCap: 'round',
			lineWidth: 8,
			onStep: function(from, to, percent) {
				$(this.element).find('.pie-value').text(Math.round(percent) + '%');
			}
		});
	});

	function reloadPage(idPage) {
		$.ajax({
			type: "POST",
			url: "relatorios/ajxCategoriaCliente.do?opcao=paginar&id=<?php echo fnEncode($cod_empresa); ?>&idPage=" + idPage + "&itens_por_pagina=<?php echo $itens_por_pagina; ?>",
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

	function reenvioTkn(idTkn) {
		$.ajax({
			method: 'POST',
			url: '../maiscash/ajxRetaguardaToken.do',
			data: {
				COD_TOKEN: idTkn,
				COD_EMPRESA: "<?= fnEncode($cod_empresa) ?>"
			},
			success: function(data) {
				if (data == 39) {
					$.alert({
						title: "Sucesso",
						content: "Token Enviado",
						type: 'green'
					});
				} else {
					$.alert({
						title: "Falha",
						content: "Token não enviado. Limite alcançado.",
						type: 'orange'
					});
				}
				// console.log(data);
			}
		});
	}

	function abreDetail(idBloco) {
		var idItem = $('.detail_' + idBloco);

		if (!idItem.is(':visible')) {
			var pDataInicial = $('#DAT_INI').val();
			var pDataFinal = $('#DAT_FIM').val();
			$.ajax({
				type: "POST",
				url: "relatorios/ajxCategoriaCliente.do?opcao=detail&id=<?= fnEncode($cod_empresa) ?>&idC=" + idBloco,
				data: $('#formulario').serialize(),
				beforeSend: function() {
					$('#bloco_' + idBloco).after('<tr id="loadDetail"><th colspan = "6"><div class="loading" style="width: 100%;"></div></tr></th>');
				},
				success: function(data) {
					console.log(data);
					$('#loadDetail').remove();
					$('#bloco_' + idBloco).after(data);
					$('#bloco_' + idBloco).find($(".fa")).removeClass('fa-angle-right').addClass('fa-angle-down');
				},
				error: function() {
					idItem.html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
				}
			});
		} else {
			idItem.hide();
			$('#bloco_' + idBloco).find($(".fa")).removeClass('fa-angle-down').addClass('fa-angle-right');
		}
	}
</script>