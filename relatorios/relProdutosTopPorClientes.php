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
$cod_produto = "";
$msgRetorno = "";
$msgTipo = "";
$cod_categor = "";
$cod_subcate = "";
$cod_persona = "";
$cod_usuario = "";
$des_produto = "";
$cod_produtos = "";
$dat_ini = "";
$dat_fim = "";
$multi_prod = "";
$produto = "";
$opcao = "";
$hHabilitado = "";
$hashForm = "";
$cod_persona_url = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$cod_cliente = "";
$formBack = "";
$andUnidade = "";
$qrListaPersonas = "";
$desabilitado = "";
$desabilitadoOnTxt = "";
$desabilitadoRg = "";
$desabilitadoRgTxt = "";
$cod_campanha = "";
$qrListaCategoria = "";
$qrLista = "";
$andProdutos = "";
$andGroupby = "";
$andCategor = "";
$andSubCate = "";
$andVendedor = "";
$innerPersonas = "";
$lojasSelecionadas = "";
$retorno = "";
$totalitens_por_pagina = "";
$inicio = "";
$countLinha = "";
$qrListaVendas = "";
$unitarioMedio = "";
$content = "";




$hashLocal = mt_rand();
$itens_por_pagina = 50;
$pagina = 1;

//inicialização de variáveis
$hoje = fnFormatDate(date("Y-m-d"));
$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30 . '- 30 days')));
//$hoje = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 1 days')));
//$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 2 days')));
$cod_produto = "0";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_empresa = fnLimpaCampoZero(@$_POST['COD_EMPRESA']);
		$cod_univend = fnLimpaCampoZero(@$_POST['COD_UNIVEND']);
		$cod_produto = fnLimpaCampoZero(@$_POST['COD_PRODUTO']);
		$cod_categor = fnLimpaCampoZero(@$_POST['COD_CATEGOR']);
		$cod_subcate = fnLimpaCampoZero(@$_POST['COD_SUBCATE']);
		$cod_persona = fnLimpaCampoZero(@$_POST['COD_PERSONA']);
		$cod_usuario = fnLimpaCampoZero(@$_POST['COD_USUARIO']);
		$des_produto = ltrim(rtrim(fnLimpaCampo(trim(@$_POST['DES_PRODUTO'])), ","), ",");
		$cod_produtos = ltrim(rtrim(fnlimpacampo(@$_POST['MULTI_PROD']), ","), ",");

		// FNeSCREVE($cod_produtos);

		$dat_ini = fnDataSql(@$_POST['DAT_INI']);
		$dat_fim = fnDataSql(@$_POST['DAT_FIM']);

		// if(count($multi_prod) > 0){

		// 	foreach ($multi_prod as $produto) {
		// 		$cod_produtos .= $produto['COD_PRODUTO'].",";
		// 	}

		// 	$cod_produtos = rtrim(ltrim(trim($cod_produtos),","),",");

		// }else{
		// 	$cod_produtos = 0;
		// }

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
	$cod_persona_url = fnLimpaCampoZero(@$_GET['idP']);
	$sql = "SELECT COD_EMPRESA, NOM_EMPRESA FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
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


if ($cod_persona_url != 0 && $cod_persona_url != '') {
	$cod_persona = $cod_persona_url;
}

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
						<span class="text-primary"> <?php echo $NomePg; ?> / <?php echo $nom_empresa ?></span>
					</div>

					<?php
					$formBack = "1015";
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

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Personas</label>
										<input type="hidden" name="HID_PERSONA" id="HID_PERSONA" value="">
										<select data-placeholder="Selecione a persona desejada" name="COD_PERSONA" id="COD_PERSONA" class="chosen-select-deselect requiredChk" tabindex="1">
											<option value=""></option>
											<?php

											if (fnAutMaster($_SESSION["SYS_COD_TPUSUARIO"], $_SESSION["SYS_COD_EMPRESA"]) == '1') {

												$andUnidade = "";
											} else {

												$andUnidade = "AND PERSONA.COD_UNIVEND IN($_SESSION[SYS_COD_UNIVEND])";
											}

											$sql = "SELECT IFNULL(PERSONAREGRA.COD_REGRA,0) AS TEM_REGRA, 
																				PERSONA.* 
																			 	FROM PERSONA 
																				LEFT JOIN PERSONAREGRA ON PERSONAREGRA.COD_PERSONA = PERSONA.COD_PERSONA
																			 	WHERE COD_EMPRESA = $cod_empresa 
																			 	$andUnidade
																			 	ORDER BY DES_PERSONA ";

											$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

											while ($qrListaPersonas = mysqli_fetch_assoc($arrayQuery)) {

												if ($qrListaPersonas['LOG_ATIVO'] == "N") {
													$desabilitado = "disabled";
													$desabilitadoOnTxt = " (Off)";
												} else {
													$desabilitado = "";
													$desabilitadoOnTxt = "";
												}

												if ($qrListaPersonas['TEM_REGRA'] == "0") {
													$desabilitadoRg = " disabled";
													$desabilitadoRgTxt = " (s/ regra)";
												} else {
													$desabilitadoRg = "";
													$desabilitadoRgTxt = "";
												}

												echo "
																				  <option value='" . $qrListaPersonas['COD_PERSONA'] . "' " . $desabilitado . $desabilitadoRg . ">" . ucfirst($qrListaPersonas['DES_PERSONA']) . $desabilitadoRgTxt . $desabilitadoOnTxt . "</option> 
																				";
											}

											?>
										</select>
										<script type="text/javascript">
											let cod_persona_url = "<?= $cod_persona_url ?>",
												comboPersona = $("#COD_PERSONA"),
												campoPersonaHid = $("#HID_PERSONA");
											if (cod_persona_url != 0) {
												comboPersona.val(cod_persona_url).trigger("chosen:updated").attr("disabled", true);
												campoPersonaHid.val(cod_persona_url);
											} else {
												comboPersona.val("<?= $cod_persona ?>").trigger("chosen:updated");
											}
										</script>
									</div>

								</div>

								<div class="col-md-6">

									<div class="row">

										<div class="col-md-12" id="divProduto">
											<label for="inputName" class="control-label">Produto </label>
											<div class="input-group">
												<span class="input-group-btn">
													<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary addBox" data-url="action.php?mod=<?php echo fnEncode(1856) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idc=<?php echo fnEncode($cod_campanha) ?>&pop=true" data-title="Busca Produtos"><i class="fa fa-search" aria-hidden="true"></i></a>
												</span>
												<input type="text" name="DES_PRODUTO" id="DES_PRODUTO" class="form-control input-sm leituraOff" style="border-radius: 0 3px 3px  0;" readonly="readonly" placeholder="Procurar produto específico..." value="<?php echo $des_produto; ?>">
												<span class="input-group-btn">
													<a href="javascript:void(0)" style="height:35px;" class="btn btn-danger" onclick='$("#MULTI_PROD").val("");$("#DES_PRODUTO").val("");'><i class="fa fa-trash" aria-hidden="true"></i></a>
												</span>
												<input type="hidden" name="COD_PRODUTO" id="COD_PRODUTO" value="<?php echo $cod_produto; ?>">
												<div class="help-block with-errors"></div>
											</div>
										</div>

										<!-- <div class="col-md-12 text-center">
																<div class="push5"></div>
																<a href="javascript:void(0)" class="addBox" data-url="action.php?mod=<?php echo fnEncode(1856) ?>&id=<?php echo fnEncode($cod_empresa) ?>&pop=true" data-title="Vendas por produto - Busca Produtos (Múltiplo)"><i class="fas fa-box-open" aria-hidden="true"></i>&nbsp; Múltiplos Produtos</a>
															</div> -->

									</div>

								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Grupo do Produto</label>
										<select data-placeholder="Selecione o grupo" name="COD_CATEGOR" id="COD_CATEGOR" class="chosen-select-deselect">
											<option value="">&nbsp;</option>
											<?php
											$sql = "select * from CATEGORIA where COD_EMPRESA = $cod_empresa AND (COD_EXCLUSA is null OR COD_EXCLUSA =0) order by DES_CATEGOR";
											$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

											while ($qrListaCategoria = mysqli_fetch_assoc($arrayQuery)) {
												echo "
																	<option value='" . $qrListaCategoria['COD_CATEGOR'] . "'>" . $qrListaCategoria['DES_CATEGOR'] . "</option> 
																	";
											}
											?>
										</select>
										<script>
											$("#COD_CATEGOR").val("<?php echo $cod_categor; ?>").trigger("chosen:updated");
										</script>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Sub Grupo do Produto</label>
										<div id="divId_sub">
											<select data-placeholder="Selecione o sub grupo" name="COD_SUBCATE" id="COD_SUBCATE" class="chosen-select-deselect">
												<option value="">&nbsp;</option>
											</select>
										</div>
										<script>
										</script>
										<div class="help-block with-errors"></div>
									</div>
								</div>

							</div>

							<div class="row">

								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label required">Unidade de Atendimento</label>
										<?php include "unidadesAutorizadasComboMulti.php"; ?>
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

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Vendedor</label>
										<select data-placeholder="Selecione um vendedor" name="COD_USUARIO" id="COD_USUARIO" class="chosen-select-deselect" style="width:100%;">
											<option value=""></option>
											<?php

											$sql = 'SELECT * from USUARIOS 
					                                                            WHERE COD_EMPRESA = ' . $cod_empresa . '
					                                                            AND DAT_EXCLUSA IS NULL 
					                                                            AND COD_TPUSUARIO in(7,11) 
					                                                            AND COD_UNIVEND in("' . $_SESSION['SYS_COD_UNIVEND'] . '") 
					                                                            ORDER BY NOM_USUARIO';
											$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

											while ($qrLista = mysqli_fetch_assoc($arrayQuery)) {
												echo "
					                                                              <option value='" . $qrLista['COD_USUARIO'] . "'>" . $qrLista['NOM_USUARIO'] . "</option> 
					                                                            ";
											}
											?>

										</select>
										<script>
											$("#COD_USUARIO").val("<?= $cod_usuario ?>").trigger("chosen:updated");
										</script>
										<?php // fnEscreve($sql); 
										?>
										<div class="help-block with-errors"></div>
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

						<div class="row">

							<div class="col-md-12" id="div_Produtos">

								<div class="push20"></div>

								<table class="table table-bordered table-hover tablesorter">

									<thead>
										<tr>
											<th><small>Cliente</small></th>
											<th><small>CPF</small></th>
											<th><small>Loja</small></th>
											<th><small>Vendedor</small></th>
											<th class="text-center"><small>Cód. Produto</small></th>
											<th class="text-center"><small>Cód. Ext. Produto</small></th>
											<th class="text-center"><small>Produto</small></th>
											<th class="text-center"><small>Qtd. Produto</small></th>
											<th class="text-center"><small>Tot. Vendas</small></th>
											<th class="text-center"><small>Unitário Médio</small></th>
											<th class="text-center"><small>Qtd. Vendas</small></th>
											<th class="text-center"><small>Dt. Venda</small></th>
										</tr>
									</thead>

									<tbody id="relatorioConteudo">

										<?php

										$andGroupby = "GROUP BY A.COD_CLIENTE";
										if ($cod_produtos != 0 && $cod_produtos != "") {
											$cod_produto = $cod_produtos;
											$andProdutos = "AND B.COD_PRODUTO IN ( $cod_produto )  ";
										}

										if ($cod_categor != 0 && $cod_categor != "") {
											$andCategor = "AND C.COD_CATEGOR = $cod_categor";
										}

										if ($cod_subcate != 0 && $cod_subcate != "") {
											$andSubCate = "AND C.COD_SUBCATE = $cod_subcate";
										}

										if ($cod_usuario != 0 && $cod_usuario != "") {
											$andVendedor = "AND V.COD_USUARIO = $cod_usuario";
										}

										if ($cod_persona != 0 && $cod_persona != "") {
											$innerPersonas = "INNER JOIN personaclassifica p ON p.COD_CLIENTE=A.COD_CLIENTE AND p.COD_EMPRESA=A.COD_EMPRESA AND p.COD_PERSONA=$cod_persona";
										}

										$sql = "SELECT 
																      Count(*)  AS QTD_VENDA,
																       A.DAT_CADASTR_WS,
																       V.NOM_USUARIO NOM_VENDEDOR,
																       ATEN.NOM_USUARIO NOM_ATENDENTE,
																       D.NOM_CLIENTE,
																       D.COD_CLIENTE,
																       D.NUM_CGCECPF,
																       A.COD_UNIVEND,
																       E.NOM_FANTASI,
																       Sum(B.QTD_PRODUTO)   QTD_PRODUTO,
																       B.COD_PRODUTO,
																       C.DES_PRODUTO,
																       C.COD_EXTERNO,
																       Sum(B.VAL_TOTITEM)  VAL_TOTITEM,
																       Count(DISTINCT CASE  WHEN log_avulso = 'N' THEN A.COD_CLIENTE ELSE NULL end) NUM_CLIENTE,
																       Ifnull(Sum(IF(D.LOG_AVULSO = 'N', B.VAL_TOTITEM, 0)), 0) AS VAL_FIDELIZA,
																       Ifnull(Sum(IF(D.LOG_AVULSO = 'N', B.QTD_PRODUTO, 0)), 0) AS QTD_FIDELIZ
																FROM   VENDAS A
																       INNER JOIN ITEMVENDA B ON B.COD_VENDA = A.COD_VENDA  AND A.COD_EMPRESA = B.COD_EMPRESA
																       INNER JOIN produtocliente C  ON B.COD_PRODUTO = C.COD_PRODUTO AND C.COD_EMPRESA = A.COD_EMPRESA
																       INNER JOIN CLIENTES D  ON A.COD_CLIENTE = D.COD_CLIENTE AND D.COD_EMPRESA = A.COD_EMPRESA
																       INNER JOIN UNIDADEVENDA E  ON A.COD_UNIVEND = E.COD_UNIVEND AND E.COD_EMPRESA = A.COD_EMPRESA
																       LEFT JOIN USUARIOS V ON V.COD_USUARIO=A.COD_VENDEDOR
																       LEFT JOIN USUARIOS ATEN ON ATEN.COD_USUARIO=A.COD_ATENDENTE
																       
																		$innerPersonas
																       
																WHERE  A.COD_EMPRESA = $cod_empresa
																       AND Date(A.DAT_CADASTR_WS) BETWEEN '$dat_ini' AND '$dat_fim'
																       AND A.COD_UNIVEND IN($lojasSelecionadas)
																       $andProdutos
                                                                       $andCategor
                                                                       $andSubCate
                                                                       $andVendedor
																	   $andGroupby
																ORDER  BY A.COD_VENDA DESC, A.COD_CLIENTE";

										//fnEscreve($sql);
										$retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
										$totalitens_por_pagina = mysqli_num_rows($retorno);
										$numPaginas = ceil($totalitens_por_pagina / $itens_por_pagina);
										//variavel para calcular o início da visualização com base na página atual
										$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

										$sql = "SELECT 
																      Count(*)  AS QTD_VENDA,
																       A.DAT_CADASTR_WS,
																       V.NOM_USUARIO NOM_VENDEDOR,
																       ATEN.NOM_USUARIO NOM_ATENDENTE,
																       D.NOM_CLIENTE,
																       D.COD_CLIENTE,
																       D.NUM_CGCECPF,
																       A.COD_UNIVEND,
																       E.NOM_FANTASI,
																       Sum(B.QTD_PRODUTO)   QTD_PRODUTO,
																       B.COD_PRODUTO,
																       C.DES_PRODUTO,
																       C.COD_EXTERNO,
																       Sum(B.VAL_TOTITEM)  VAL_TOTITEM,
																       Count(DISTINCT CASE  WHEN log_avulso = 'N' THEN A.COD_CLIENTE ELSE NULL end) NUM_CLIENTE,
																       Ifnull(Sum(IF(D.LOG_AVULSO = 'N', B.VAL_TOTITEM, 0)), 0) AS VAL_FIDELIZA,
																       Ifnull(Sum(IF(D.LOG_AVULSO = 'N', B.QTD_PRODUTO, 0)), 0) AS QTD_FIDELIZ
																FROM   VENDAS A
																       INNER JOIN ITEMVENDA B ON B.COD_VENDA = A.COD_VENDA  AND A.COD_EMPRESA = B.COD_EMPRESA
																       INNER JOIN produtocliente C  ON B.COD_PRODUTO = C.COD_PRODUTO AND C.COD_EMPRESA = A.COD_EMPRESA
																       INNER JOIN CLIENTES D  ON A.COD_CLIENTE = D.COD_CLIENTE AND D.COD_EMPRESA = A.COD_EMPRESA
																       INNER JOIN UNIDADEVENDA E  ON A.COD_UNIVEND = E.COD_UNIVEND AND E.COD_EMPRESA = A.COD_EMPRESA
																       LEFT JOIN USUARIOS V ON V.COD_USUARIO=A.COD_VENDEDOR
																       LEFT JOIN USUARIOS ATEN ON ATEN.COD_USUARIO=A.COD_ATENDENTE
																       
																		$innerPersonas
																       
																WHERE  A.COD_EMPRESA = $cod_empresa
																       AND Date(A.DAT_CADASTR_WS) BETWEEN '$dat_ini' AND '$dat_fim'
																       AND A.COD_UNIVEND IN($lojasSelecionadas)
																       $andProdutos
                                                                       $andCategor
                                                                       $andSubCate
                                                                       $andVendedor
																	   $andGroupby
																ORDER  BY A.COD_VENDA DESC, A.COD_CLIENTE
			                                                    LIMIT $inicio,$itens_por_pagina";

										// fnEscreve($sql);

										$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);


										$countLinha = 1;
										while ($qrListaVendas = mysqli_fetch_assoc($arrayQuery)) {
											$unitarioMedio = $qrListaVendas['VAL_FIDELIZA'] / $qrListaVendas['QTD_FIDELIZ'];
										?>
											<tr>
												<td><a href="action.do?mod=<?php echo fnEncode(1024); ?>&id=<?php echo fnEncode($cod_empresa); ?>&idC=<?php echo fnEncode($qrListaVendas['COD_CLIENTE']); ?>" class="f14" target="_blank"><?php echo $qrListaVendas['NOM_CLIENTE'] ?></a></td>
												<td><small><?php echo fnMascaraCampo($qrListaVendas['NUM_CGCECPF']); ?></small></td>
												<td><small><?php echo $qrListaVendas['NOM_FANTASI']; ?></small></td>
												<td><small><?php echo $qrListaVendas['NOM_VENDEDOR']; ?></small></td>
												<td class="text-center"><small><?php echo $qrListaVendas['COD_PRODUTO']; ?></small></td>
												<td class="text-center"><small><?php echo $qrListaVendas['COD_EXTERNO']; ?></small></td>
												<td class="text-center"><small><?php echo $qrListaVendas['DES_PRODUTO']; ?></small></td>
												<td class="text-center"><b><small><?php echo fnValor($qrListaVendas['QTD_PRODUTO'], 0); ?></small></b></td>
												<td class="text-center"><b><small><small>R$</small><?php echo fnValor($qrListaVendas['VAL_TOTITEM'], 2); ?></small></b></td>
												<td class="text-center"><small><small>R$</small> <?php echo fnValor($unitarioMedio, 2); ?></small></td>
												<td class="text-center"><small><?php echo fnValor($qrListaVendas['QTD_VENDA'], 0); ?></small></td>
												<td class="text-center"><small><?php echo fnDataFull($qrListaVendas['DAT_CADASTR_WS']); ?></small></td>
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
												<div class="btn-group dropdown left">
													<button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-file-excel" aria-hidden="true"></i>
														&nbsp; Exportar&nbsp;
														<span class="fas fa-caret-down"></span>
													</button>
													<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
														<li><a class="btn btn-sm exportarCSV" data-opcao="exportar" style="text-align: left">&nbsp; Exportar</a></li>
														<li><a class="btn btn-sm exportarCSV" data-opcao="detalhes" style="text-align: left">&nbsp; Exportar Detalhado </a></li>
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

							</div>

						</div>

						<input type="hidden" name="LOJAS" id="LOJAS" value="<?php echo $lojasSelecionadas; ?>" />
						<input type="hidden" name="NOVO_CLIENTE" id="NOVO_CLIENTE" value="">
						<input type="hidden" name="REFRESH_CLIENTE" id="REFRESH_CLIENTE" value="N">
						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="itens_por_pagina" id="itens_por_pagina" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
						<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
						<input type="hidden" name="MULTI_PROD" id="MULTI_PROD" value='<?= $cod_produtos ?>'>
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
<div class="modal fade" id="popModalAux" tabindex='-1'>
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
	let opcaoExp = "";

	$(document).ready(function() {
		var codCategoria = '<?= $cod_categor ?>';
		var codSubcategoria = '<?= $cod_subcate ?>';

		if (codCategoria !== '' && codSubcategoria !== '') {
			buscaSubCat(codCategoria, codSubcategoria, '<?= $cod_empresa ?>');
		}
	});

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

		$('#DAT_FIM_GRP').data("DateTimePicker").maxDate(moment("<?= $dat_fim ?>"));

		// $("#DAT_INI_GRP").on("dp.change", function (e) {
		// 	$('#DAT_FIM_GRP').data("DateTimePicker").minDate(e.date);
		// });

		// $("#DAT_FIM_GRP").on("dp.change", function (e) {
		// 	$('#DAT_INI_GRP').data("DateTimePicker").maxDate(e.date);
		// });

		$("#DAT_INI_GRP").on("dp.change", function(e) {
			var nextMonth = e.date.add(3, 'months');
			$('#DAT_FIM_GRP').data("DateTimePicker").maxDate(nextMonth);
		});

		//modal close
		$('.modal').on('hidden.bs.modal', function() {
			$('#formulario').validator('validate');
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
										url: "relatorios/ajxRelProdutosTopPorCliente.do?opcao=" + opcaoExp + "&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>&LOJAS=<?php echo $lojasSelecionadas; ?>",
										data: $('#formulario').serialize(),
										method: 'POST'
									}).done(function(response) {
										self.setContentAppend('<div>Exportação realizada com sucesso.</div>');
										var fileName = '<?php echo $cod_empresa; ?>_' + nome + '.csv';
										SaveToDisk('media/excel/' + fileName, fileName);
										console.log(response);
										console.log(opcaoExp);
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

		// ajax
		$("#COD_CATEGOR").change(function() {
			var codBusca = $("#COD_CATEGOR").val();
			var codBusca3 = $("#COD_EMPRESA").val();
			buscaSubCat(codBusca, 0, codBusca3);
		});
	});

	function buscaSubCat(idCat, idSub, idEmp) {
		$.ajax({
			type: "GET",
			url: "ajxBuscaSubGrupo.php",
			data: {
				ajx1: idCat,
				ajx2: idSub,
				ajx3: idEmp
			},

			beforeSend: function() {
				$('#divId_sub').html('<div class="loading" style="width: 100%;"></div>');
			},
			success: function(data) {
				$("#divId_sub").html(data);
				console.log(data);
			},
			error: function() {
				$('#divId_sub').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
			}
		});
	}

	function reloadPage(idPage) {
		$.ajax({
			type: "POST",
			url: "relatorios/ajxRelProdutosTopPorCliente.do?opcao=paginar&id=<?php echo fnEncode($cod_empresa); ?>&idPage=" + idPage + "&itens_por_pagina=<?php echo $itens_por_pagina; ?>",
			data: $('#formulario').serialize(),
			beforeSend: function() {
				$('#relatorioConteudo').html('<tr><td colspan="100"><div class="loading" style="width: 100%;"></div></tr></td>');
			},
			success: function(data) {
				$("#relatorioConteudo").html(data);
				//$(".tablesorter").trigger("updateAll");
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