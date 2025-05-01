<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$tipoVenda = "";
$hashLocal = "";
$tip_roi = "";
$hoje = "";
$dias30 = "";
$msgRetorno = "";
$msgTipo = "";
$des_canal = "";
$dat_ini = "";
$dat_iniFiltro = "";
$dat_fimFiltro = "";
$dat_fim = "";
$opcao = "";
$hHabilitado = "";
$hashForm = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$cod_cliente_av = "";
$tip_retorno = "";
$casasDec = "";
$checkTodas = "";
$checkCreditos = "";
$temUnivend = "";
$lojasSelecionadas = "";
$cod_univendUsu = "";
$qtd_univendUsu = 0;
$lojasAut = "";
$usuReportAdm = "";
$andEntreguesSms = "";
$anulaEmail = "";
$anulaSms = "";
$andEntregues = "";
$filtroVal = "";
$arrayCamp = [];
$qrCamp = "";
$ativoCamp = "";
$canal = "";
$sqlCli = "";
$sql2 = "";
$arrayCli = [];
$qrCli = "";
$arrayVal = [];
$qrVal = "";
$val_unit = "";
$fimCampanha = "";
$invest = "";
$retorno = "";
$roi = "";
$content = "";




//fnMostraForm();
// definir o numero de itens por pagina
$itens_por_pagina = 50;
$pagina = 1;
$tipoVenda = "T";
$hashLocal = mt_rand();

$tip_roi = 0;

//inicialização de variáveis
$hoje = fnFormatDate(date("Y-m-d"));
//$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 1 days')));
$dias30 = fnFormatDate(date("Y-m-d"));
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
		$tip_roi = @$_REQUEST['TIP_ROI'];
		$des_canal = @$_REQUEST['DES_CANAL'];
		$dat_ini = fnDataSql(@$_POST['DAT_INI']);
		$dat_iniFiltro = fnDataSql(@$_POST['DAT_INI']);
		$dat_fimFiltro = fnDataSql(@$_POST['DAT_FIM']);
		$dat_fim = fnDataSql(@$_POST['DAT_FIM']);

		// fnEscreve($tip_roi);

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

if (!is_null($RedirectPg)) {
	$DestinoPg = fnEncode($RedirectPg);
} else {
	$DestinoPg = "";
}

// 	//faz pesquisa por revenda (geral)
// if ($cod_univend == "9999") {
// 	$temUnivend = "N";
// } else {
// 	$temUnivend = "S";
// }
// 	//busca revendas do usuário
// include "unidadesAutorizadas.php"; 

//fnMostraForm();	
//fnEscreve($dat_ini);
//fnEscreve($lojasSelecionadas);
//fnEscreve($cod_univendUsu);
//fnEscreve($qtd_univendUsu);
//fnEscreve($lojasAut);
//fnEscreve($usuReportAdm);
//fnEscreve($tipoVenda);

?>

<div class="push30"></div>

<div class="row">

	<div class="col-md-12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fal fa-terminal"></i>
					<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
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
							<legend>Filtros</legend>

							<div class="row">

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

								<!--<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Unidade de Atendimento</label>
										<?php include "unidadesAutorizadasComboMulti.php"; ?>
									</div>
								</div> -->

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Tipos de Interação com Email</label>
										<select data-placeholder="Selecione o tipo" name="TIP_ROI" id="TIP_ROI" class="chosen-select-deselect" style="width:100%;">
											<!-- <option value="0">Entregues com sucesso </option> -->
											<option value="0">Entregues (Email)</option>
											<option value="2">Leitura (Email)</option>
											<option value="1">Cliques (Email)</option>
										</select>
									</div>
									<script type="text/javascript">
										$("#TIP_ROI").val('<?= $tip_roi ?>').trigger("chosen:updated");
									</script>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Canal</label>
										<select data-placeholder="Selecione o tipo" name="DES_CANAL" id="DES_CANAL" class="chosen-select-deselect" style="width:100%;">
											<!-- <option value="0">Entregues com sucesso </option> -->
											<option value=""></option>
											<option value="Email">Email</option>
											<option value="SMS">SMS</option>
										</select>
									</div>
									<script type="text/javascript">
										$("#DES_CANAL").val('<?= $des_canal ?>').trigger("chosen:updated");
									</script>
								</div>

								<div class="col-md-2">
									<div class="push20"></div>
									<button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-sm btn-block getBtn"><i class="fal fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
								</div>

							</div>

						</fieldset>

						<input type="hidden" name="LOJAS" id="LOJAS" value="<?php echo $lojasSelecionadas; ?>" />
						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

					</form>

				</div>

			</div>

		</div>

		<div class="push30"></div>

		<?php include 'includeBlocoSaldoComunicacao.php'; ?>

		<div class="push30"></div>

		<div class="portlet portlet-bordered ">
			<!-- Portlet -->
			<div class="portlet-body">

				<div class="col-lg-12">

					<div class="no-more-tables">

						<form name="formLista" id="formLista" method="post" action="action.do?mod=<?php echo $DestinoPg; ?>&id=<?= fnEncode($cod_empresa) ?>" target="_blank">

							<table id="table" class="table table-bordered table-striped table-hover">

								<thead>
									<tr>
										<th class="text-center" width="50"><small class="f9">Marcar Todos</small><br /><input type='checkbox' id="selectAll"></th>
										<th><small>Campanha</small></th>
										<th class="text-center"><small>Canal</small></th>
										<th class="text-center"><small>Campanha Ativa</small></th>
										<!-- <th>Data de Criação</th> -->
										<th class="text-right"><small>Faturamento</small></th>
										<th class="text-right"><small>Valor Unitário</small></th>
										<th class="text-right"><small>Investimento</small></th>
										<th><small>Retorno da Campanha</small></th>
										<th class="text-right"><small>ROI</small></th>
										<th class="text-right"><small>Clientes c/ Compras</small></th>
									</tr>
								</thead>

								<tbody>

									<?php

									$andEntreguesSms = "AND CASE
									WHEN cli_list.cod_cconfirmacao = '1' THEN '1'
									WHEN cli_list.cod_sconfirmacao = '1' THEN '1'
									ELSE '0'
									END IN ( 1, 1 )";

									$anulaEmail = "";
									$anulaSms = "";

									if ($des_canal == "SMS") {
										$anulaEmail = "AND 1 = 0";
										$anulaSms = "";
									} else if ($des_canal == "Email") {
										$anulaEmail = "";
										$anulaSms = "AND 1 = 0";
									}

									if ($tip_roi == 0) {

										// 26/05/2021 - Maurice pediu pra fazer em cima dos entregues
										// $andEntregues = "AND cli_list.cod_leitura IN('1','0')
										//                     and cli_list.bounce = '0'
										//                     and cli_list.SPAM = '0'";

										// $andEntreguesSms = "AND CASE
										// 										WHEN cli_list.cod_cconfirmacao = '1'
										// 				            THEN '1'
										// 											WHEN cli_list.cod_sconfirmacao = '1'
										// 				            THEN '1'
										// 				              ELSE '0'
										// 				            END IN ( 1, 1 )";

										$andEntregues = "AND cli_list.ENTREGUE = 1";
										// $andEntreguesSms = "";

										$filtroVal = $andEntregues;
									} else if ($tip_roi == 1) {

										// $andEntreguesSms = "AND CASE
										// 										WHEN cli_list.cod_cconfirmacao = '1'
										// 				            THEN '1'
										// 											WHEN cli_list.cod_sconfirmacao = '1'
										// 				            THEN '1'
										// 				              ELSE '0'
										// 				            END IN ( 1, 1 )";

										$andEntregues = "AND cli_list.CLICK IN('1')
										and cli_list.bounce = '0'
										and cli_list.SPAM = '0'";

										$filtroVal = $andEntregues;
									} else {

										// $andEntreguesSms = "AND CASE
										// 										WHEN cli_list.cod_cconfirmacao = '1'
										// 				            THEN '1'
										// 											WHEN cli_list.cod_sconfirmacao = '1'
										// 				            THEN '1'
										// 				              ELSE '0'
										// 				            END IN ( 1, 1 )";

										$andEntregues = "AND cli_list.cod_optout_ativo = '0' 
										AND cli_list.cod_leitura=1 
										AND cli_list.bounce = '0' 
										AND cli_list.SPAM = '0'";

										$filtroVal = $andEntregues;
									}

									// fnESCREVE($andEntregues);





									$sql = "SELECT * FROM
														(
															SELECT CAMP.DES_CAMPANHA,
																CAMP.LOG_CONTINU,
																CAMP.DAT_INI AS DAT_INI_CAMP,
																CAMP.DAT_FIM AS DAT_FIM_CAMP,
																CAMP.LOG_ATIVO,
																'SMS' AS DES_CANAL,
																(SELECT COUNT(DISTINCT cli_list.COD_CLIENTE)
																FROM sms_lista_ret cli_list
																WHERE cli_list.cod_empresa = $cod_empresa
																	AND cli_list.cod_campanha = COD_CAMPANHA
																	AND DATE(cli_list.dat_cadastr) BETWEEN '$dat_ini' AND '$dat_fim'
																	AND CASE
																			WHEN cli_list.cod_cconfirmacao = '1' THEN '1'
																			WHEN cli_list.cod_sconfirmacao = '1' THEN '1'
																		ELSE '0' END IN (1,1)
																	AND cli_list.COD_CLIENTE IN (SELECT v.COD_CLIENTE   FROM vendas v  WHERE DATE(v.dat_cadastr_ws) BETWEEN '$dat_ini' AND '$dat_fim' AND v.cod_empresa = $cod_empresa )
																	) AS CLI_ATIVOS,				
																count(LOT.COD_LISTA) QTD_LISTA,
																VAL.COD_VALOR,
																VAL.VAL_UNITARIO,
																LOT.COD_CAMPANHA,
																CASE WHEN CAMP.DAT_INI > '$dat_ini' THEN CAMP.DAT_INI ELSE '$dat_ini' END DAT_INI,
																CASE WHEN CAMP.DAT_FIM < '$dat_fim' THEN CAMP.DAT_FIM ELSE '$dat_fim' END DAT_FIM	
															FROM sms_lista_ret LOT
															INNER JOIN campanha CAMP ON CAMP.COD_CAMPANHA=LOT.COD_CAMPANHA
															LEFT JOIN VALORES_COMUNICACAO VAL ON VAL.COD_CAMPANHA = CAMP.COD_CAMPANHA
															AND VAL.DES_CANAL = 'SMS'
															WHERE date(LOT.DAT_CADASTR) BETWEEN '$dat_ini' AND '$dat_fim'
																AND LOT.STATUS_ENVIO = 'S'
																AND LOT.cod_empresa=$cod_empresa
																AND CAMP.LOG_PROCESSA_SMS = 'S'
																$anulaSms
															GROUP BY LOT.COD_CAMPANHA
														) AS SMS

									UNION

									(SELECT CAMP.DES_CAMPANHA, CAMP.LOG_CONTINU, CAMP.DAT_INI AS DAT_INI_CAMP, CAMP.DAT_FIM AS DAT_FIM_CAMP, CAMP.LOG_ATIVO, 'EMAIL' AS DES_CANAL,
										(SELECT COUNT(DISTINCT cli_list.cod_cliente)
											FROM   email_lista_ret cli_list
											WHERE
											cli_list.cod_empresa = $cod_empresa
											AND cli_list.cod_campanha = COD_CAMPANHA
											AND DATE(cli_list.dat_cadastr) BETWEEN '$dat_ini' AND '$dat_fim'
											$andEntregues
											AND  cli_list.cod_cliente IN (SELECT v.COD_CLIENTE
												FROM   vendas v
												WHERE  DATE(v.dat_cadastr_ws) BETWEEN '$dat_ini' AND '$dat_fim'
												AND v.cod_empresa = $cod_empresa
												AND v.cod_avulso = 2
												)) AS CLI_ATIVOS, 
										count(LOT.COD_LISTA) QTD_LISTA, VAL.COD_VALOR, VAL.VAL_UNITARIO, LOT.COD_CAMPANHA, 
										case when CAMP.DAT_INI > '$dat_ini' then CAMP.DAT_INI ELSE '$dat_ini' END DAT_INI,					   
										case when CAMP.DAT_FIM < '$dat_fim' then  CAMP.DAT_FIM ELSE '$dat_fim' END DAT_FIM
										FROM email_lista_ret LOT 
										INNER JOIN campanha CAMP ON CAMP.COD_CAMPANHA=LOT.COD_CAMPANHA 
										LEFT JOIN VALORES_COMUNICACAO VAL ON VAL.COD_CAMPANHA = CAMP.COD_CAMPANHA AND VAL.DES_CANAL = 'EMAIL' 
										WHERE date(LOT.DAT_CADASTR) BETWEEN '$dat_ini' AND '$dat_fim' 
										AND LOT.LOG_EMAIL = 'S' 
										AND LOT.cod_empresa=$cod_empresa 
										AND CAMP.LOG_PROCESSA = 'S'
										$anulaEmail
										GROUP BY LOT.COD_CAMPANHA) 

									ORDER BY COD_CAMPANHA, DES_CAMPANHA";

									// fnEscreve($sql);

									$arrayCamp = mysqli_query(connTemp($cod_empresa, ''), $sql);

									$count = 0;

									while ($qrCamp = mysqli_fetch_assoc($arrayCamp)) {

										$ativoCamp = "";

										$dat_iniFiltro = $qrCamp['DAT_INI'];
										$dat_fimFiltro = $qrCamp['DAT_FIM'];

										if ($qrCamp['LOG_ATIVO'] == 'S') {
											$ativoCamp = "<span class='fal fa-check'></span>";
										}

										if ($qrCamp['DES_CANAL'] == 'SMS') {

											$canal = "SMS";

											$sqlCli = "SELECT COUNT( distinct cli_list.cod_cliente) CLI_ATIVOS,'SMS' FROM   sms_lista_ret cli_list 
											WHERE  cli_list.cod_empresa = $cod_empresa                      
											AND DATE(cli_list.dat_cadastr) BETWEEN  '$dat_iniFiltro' AND '$dat_fimFiltro' 
											AND  cli_list.cod_campanha = $qrCamp[COD_CAMPANHA]
											$andEntreguesSms
											AND cli_list.cod_cliente IN (SELECT v.cod_cliente 
												FROM   vendas v 
												WHERE 
												DATE(v.dat_cadastr_ws) BETWEEN '$dat_iniFiltro' AND '$dat_fimFiltro' 
												AND v.cod_empresa = $cod_empresa) 
											$anulaSms                           
											GROUP BY cli_list.cod_campanha";

											$sql2 = "SELECT    
											SUM(v.VAL_TOTPRODU)   VAL_TOTVENDA,
											SUM(v.VAL_TOTPRODU-v.VAL_DESCONTO) VAL_TOTPRODU, 
											SUM(v.VAL_RESGATE) VAL_RESGATE
											FROM vendas v
											WHERE
											date(v.DAT_CADASTR_WS) BETWEEN '$dat_iniFiltro' AND '$dat_fimFiltro'
											AND v.cod_empresa=$cod_empresa
											AND v.COD_AVULSO=2
											AND v.cod_statuscred IN ( 0, 1, 2, 3,4, 5, 7, 8, 9 )
											AND v.COD_CLIENTE IN (
												SELECT cli_list.COD_CLIENTE FROM  sms_lista_ret cli_list WHERE
												cli_list.COD_EMPRESA=v.cod_empresa
												AND  cli_list.COD_CAMPANHA=$qrCamp[COD_CAMPANHA] 
												AND  Date(cli_list.DAT_CADASTR) BETWEEN '$dat_iniFiltro' AND '$dat_fimFiltro'
												$andEntreguesSms
												$anulaSms
												GROUP BY cli_list.cod_cliente
											)";
										} else {

											$canal = "Email";

											$sqlCli = "SELECT COUNT( distinct cli_list.cod_cliente) CLI_ATIVOS,'EMAIL'
											FROM   email_lista_ret cli_list
											WHERE  cli_list.cod_empresa = $cod_empresa
											AND cli_list.cod_campanha = $qrCamp[COD_CAMPANHA]
											AND DATE(cli_list.dat_cadastr) BETWEEN
											'$dat_iniFiltro' AND '$dat_fimFiltro'
											$filtroVal
											AND cli_list.cod_cliente IN (SELECT v.cod_cliente
												FROM   vendas v
												WHERE
												DATE(v.dat_cadastr_ws) BETWEEN
												'$dat_iniFiltro' AND '$dat_fimFiltro'
												AND v.cod_empresa = $cod_empresa
												AND v.cod_avulso = 2)
											$anulaEmail
											GROUP BY cli_list.cod_campanha";

											$sql2 = "SELECT    
											SUM(v.VAL_TOTPRODU)   VAL_TOTVENDA,
											SUM(v.VAL_TOTPRODU-v.VAL_DESCONTO) VAL_TOTPRODU, 
											SUM(v.VAL_RESGATE) VAL_RESGATE
											FROM vendas v
											WHERE
											date(v.DAT_CADASTR_WS) BETWEEN '$dat_iniFiltro' AND '$dat_fimFiltro'
											AND v.cod_empresa=$cod_empresa
											AND v.COD_AVULSO=2
											AND v.cod_statuscred IN ( 0, 1, 2, 3,4, 5, 7, 8, 9 )
											AND v.COD_CLIENTE IN (
												SELECT cli_list.COD_CLIENTE FROM  email_lista_ret cli_list WHERE
												cli_list.COD_EMPRESA=v.cod_empresa
												AND  cli_list.COD_CAMPANHA=$qrCamp[COD_CAMPANHA] 
												AND  Date(cli_list.DAT_CADASTR) BETWEEN '$dat_iniFiltro' AND '$dat_fimFiltro'
												$filtroVal
												$anulaEmail
												GROUP BY cli_list.cod_cliente
											)";
										}

										// fnEscreve($sql2);

										$arrayCli = mysqli_query(connTemp($cod_empresa, ''), $sqlCli);
										$qrCli = mysqli_fetch_assoc($arrayCli);

										$arrayVal = mysqli_query(connTemp($cod_empresa, ''), $sql2);
										$qrVal = mysqli_fetch_assoc($arrayVal);

										if ($qrCamp['VAL_UNITARIO'] == "") {
											$val_unit = 0;
										} else {
											$val_unit = $qrCamp['VAL_UNITARIO'];
										}

										if ($qrCamp['LOG_CONTINU'] == "S") {
											$fimCampanha = "Contínua";
										} else {
											$fimCampanha = fnDataShort($qrCamp['DAT_FIM_CAMP']);
										}

										$invest = $val_unit * $qrCamp['QTD_LISTA'];
										$retorno = ($qrVal['VAL_TOTPRODU'] - $invest);
										$roi = $retorno / $invest;

										// fnEscreve($qrCamp['VAL_UNITARIO']);

									?>

										<tr>
											<td class='text-center'><input type='checkbox' name='radio_<?= $count ?>'>&nbsp;</td>
											<td><small><?= $qrCamp['DES_CAMPANHA'] ?> <span data-html="true" data-toggle="tooltip" data-placement="right" data-original-title="<span class='text-left'>Periodo da campanha:<br><?= fnDataShort($qrCamp['DAT_INI_CAMP']) ?> - <?= $fimCampanha ?></span>"><i class="fal fa-calendar text-info"></i></span></small></td>
											<td class="text-center"><small><?= $canal ?></small></td>
											<td class="text-center"><small><?= $ativoCamp ?></small></td>
											<!-- <td>30/11/2020</td> -->
											<td class="text-right" id="VAL_FATURA_<?= $qrCamp['COD_CAMPANHA'] . '_' . $canal ?>"><small><small>R$</small> <?= fnValor($qrVal['VAL_TOTPRODU'], 2) ?></small></td>
											<td class="text-right vl">
												<a href="#" class="editable"
													data-type='text'
													data-title='Editar Valor' data-pk="<?= fnLimpaCampoZero($qrCamp['COD_VALOR']) ?>"
													data-canal='<?= $qrCamp["DES_CANAL"] ?>'
													data-name="VAL_UNITARIO"
													data-descanal="<?= $canal ?>"
													data-fatur="<?= $qrVal['VAL_TOTPRODU'] ?>"
													data-qtdlista="<?= $qrCamp['QTD_LISTA'] ?>"
													data-codcampanha="<?= $qrCamp['COD_CAMPANHA'] ?>"
													data-codempresa="<?= $cod_empresa ?>"><small><?= fnValor($qrCamp['VAL_UNITARIO'], 5) ?></small>
												</a>
											</td>
											<td class="text-right" id="VAL_INVEST_<?= $qrCamp['COD_CAMPANHA'] . '_' . $canal ?>"><small>R$</small> <small class="VAL_INVEST"><?= fnValor($invest, 2) ?></small></td>
											<td id="VAL_RETORNO_<?= $qrCamp['COD_CAMPANHA'] . '_' . $canal ?>" class="text-right"><small>R$</small> <small><?= fnValor($retorno, 2) ?></small></td>
											<td id="VAL_ROI_<?= $qrCamp['COD_CAMPANHA'] . '_' . $canal ?>" class="text-right"><small><?= fnValor($roi, 0) ?></small>x</td>
											<td class="text-right"><small><?= fnValor($qrCli['CLI_ATIVOS'], 0) ?></small></td>
										</tr>

										<input type="hidden" name="ret_COD_CAMPANHA_<?= $count ?>" id="ret_COD_CAMPANHA_<?= $count ?>" value="<?= $qrCamp['COD_CAMPANHA'] ?>">
										<input type="hidden" name="ret_DES_CANAL_<?= $count ?>" id="ret_DES_CANAL_<?= $count ?>" value="<?= $canal ?>">
										<input type="hidden" name="ret_DAT_INI_CONSULTA_<?= $count ?>" id="ret_DAT_INI_CONSULTA_<?= $count ?>" value="<?= $dat_iniFiltro ?>">
										<input type="hidden" name="ret_DAT_FIM_CONSULTA_<?= $count ?>" id="ret_DAT_FIM_CONSULTA_<?= $count ?>" value="<?= $dat_fimFiltro ?>">

									<?php

										$count++;
									}

									?>

								</tbody>
								<tfoot>
									<tr>
										<td></td>
										<td>
											<b><i class="fal fa-comment-alt"></i></b> <span id="TOT_SMS">0</span>
											&nbsp;
											<b><i class="fal fa-envelope"></i></b> <span id="TOT_EMAIL">0</span>
										</td>
										<td colspan=5 class="text-right">
											<b>Total Investimento:</b> R$ <span id="TOT_INVESTIMENTO">0,00</span>
										</td>
										<td class="text-right"></td>
										<td class="text-right"></td>
										<td class="text-right"></td>
									</tr>
									<tr>
										<th colspan="100">
											<a class="btn btn-info btn-sm exportarCSV"><i class="fa fa-file-excel" aria-hidden="true"></i>&nbsp; Exportar </a>
										</th>
									</tr>
								</tfoot>
							</table>

							<div class="push20"></div>

							<div class="col-md-12">
								<div class="form-group text-right">
									<a href="javascript:void(0)" name="addProdutos" id="addProdutos" class="btn btn-primary"><i class="fal fa-external-link" aria-hidden="true"></i>&nbsp; Acessar Dashboard</a>
								</div>
							</div>

							<div class="push50"></div>

							<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
							<input type="hidden" name="LISTA_CAMPANHAS" id="LISTA_CAMPANHAS" value="">
							<input type="hidden" name="TIP_ROI_PESQ" id="TIP_ROI_PESQ" value="<?= fnEncode($tip_roi) ?>">
							<input type="hidden" name="DAT_INI_PESQ" id="DAT_INI_PESQ" value="<?= fnDataShort($dat_ini) ?>">
							<input type="hidden" name="DAT_FIM_PESQ" id="DAT_FIM_PESQ" value="<?= fnDataShort($dat_fim) ?>">
							<input type="hidden" name="codBusca" id="codBusca" value="">
							<input type="hidden" name="nomBusca" id="nomBusca" value="">

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

<script type="text/javascript">
	$(document).ready(function() {
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
										url: "ajxListaRelRoiSms.do?opcao=exportar&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>",
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

	$('#selectAll').click(function() {
		$(this).closest('table').find('td input:checkbox').prop('checked', this.checked);
	});

	$("#addProdutos").click(function() {

		var listaCampanha = new Array(),
			campanhas = "";

		$("table tr").each(function(index) {
			if ($(this).find("input[type='checkbox']:not('#selectAll')").is(':checked')) {
				var codigo = $(this).find("input[type='checkbox']").attr('name').replace('radio_', '');
				listaCampanha.push(retornaForm(codigo));
				campanhas = JSON.stringify(listaCampanha);
				// console.log(campanhas);
				// window.location.href ='action.do?mod=<?php echo $DestinoPg; ?>&id=<?= fnEncode($cod_empresa) ?>&listaCampanhas='+JSON.stringify(listaCampanha);
			}
		});

		console.log(campanhas);

		$("#LISTA_CAMPANHAS").val(campanhas);
		$("#formLista").submit();

	});

	$(function() {

		// $('.vl .editable-input .input-sm').mask('0,0000', {reverse: true});

		$.fn.editable.defaults.mode = 'popup';
		$.fn.editableform.buttons =
			'<button type="button" class="btn btn-primary btn-sm editable-submit" title="Alterar este item" data-type="one"><i class="fas fa-check"></i></button>' +
			'<button type="button" class="btn btn-primary btn-sm editable-submit" title="Alterar para todos os itens deste canal" data-type="all" style="margin-left:7px;"><i class="fas fa-check-double"></i></button>' +
			'<button type="button" class="btn btn-default btn-sm editable-cancel"><i class="glyphicon glyphicon-remove"></i></button>';

		$('.edit-int .editable-input .input-sm[type=text]').mask('000.000.000.000.000', {
			reverse: true
		});
		$('.edit-decimal .editable-input .input-sm[type=text]').mask('000.000.000.000.000,00', {
			reverse: true
		});

		$(document).on('click', '.editable-submit', function() {
			$(".editable-cancel").click();
			acao = ($(this).attr("data-type"));
			var $td = $(this).closest("td");
			var $a = $td.find("a");
			var v_canal = $a.attr('data-canal');
			var v_name = $a.attr('data-name');
			var v_codempresa = $a.attr('data-codempresa');
			var v_codcampanha = $a.attr('data-codcampanha');
			var v_descanal = $a.attr('data-descanal');
			var v_qtdlista = $a.attr('data-qtdlista');
			var v_fatur = $a.attr('data-fatur');
			var v_value = $td.find(".editable-input .form-control").val();

			if (acao == "one") {

				data = "name=" + v_name + "&value=" + v_value + "&codempresa=" + v_codempresa + "&codcampanha=" + v_codcampanha + "&descanal=" + v_descanal + "&qtdlista=" + v_qtdlista + "&fatur=" + v_fatur;
				$a.addClass("text-warning");

				$.ajax({
					method: 'POST',
					url: 'ajxValorComunicacao.php',
					data: data,
					success: function(data) {
						$a.removeClass("text-warning");
						var suf = $('#SUFIXO', data).prop('innerHTML');

						$("#VAL_INVEST_" + suf).html($('#INVEST', data).prop('innerHTML'));
						$("#VAL_ROI_" + suf).html($('#FATUR', data).prop('innerHTML'));
						$("#VAL_RETORNO_" + suf).html($('#RETORNO', data).prop('innerHTML'));
						$a.html($('#VALOR', data).prop('innerHTML'));
						totalizadores();
						console.log(data);

					}
				});

			} else {
				$("#table tbody tr a.editable[data-canal=" + v_canal + "]").each(function() {
					var $a = $(this);

					var v_name = $a.attr('data-name');
					var v_codempresa = $a.attr('data-codempresa');
					var v_codcampanha = $a.attr('data-codcampanha');
					var v_descanal = $a.attr('data-descanal');
					var v_qtdlista = $a.attr('data-qtdlista');
					var v_fatur = $a.attr('data-fatur');

					data = "name=" + v_name + "&value=" + v_value + "&codempresa=" + v_codempresa + "&codcampanha=" + v_codcampanha + "&descanal=" + v_descanal + "&qtdlista=" + v_qtdlista + "&fatur=" + v_fatur;
					$a.addClass("text-warning");

					$.ajax({
						method: 'POST',
						url: 'ajxValorComunicacao.php',
						data: data,
						success: function(data) {
							$a.removeClass("text-warning");
							var suf = $('#SUFIXO', data).prop('innerHTML');

							$("#VAL_INVEST_" + suf).html($('#INVEST', data).prop('innerHTML'));
							$("#VAL_ROI_" + suf).html($('#FATUR', data).prop('innerHTML'));
							$("#VAL_RETORNO_" + suf).html($('#RETORNO', data).prop('innerHTML'));
							$a.html($('#VALOR', data).prop('innerHTML'));
							totalizadores();
							console.log(data);

						}
					});
				});

			}
		});

		$('.editable').editable({
			emptytext: '0,00',
			url: 'ajxValorComunicacao.php',
			ajaxOptions: {
				type: 'post'
			},
			params: function(params) {
				params.codempresa = $(this).data('codempresa');
				params.codcampanha = $(this).data('codcampanha');
				params.descanal = $(this).data('descanal');
				params.qtdlista = $(this).data('qtdlista');
				params.fatur = $(this).data('fatur');
				return params;
			},
			success: function(data) {

				var suf = $('#SUFIXO', data).prop('innerHTML');

				$("#VAL_INVEST_" + suf).html($('#INVEST', data).prop('innerHTML'));
				$("#VAL_ROI_" + suf).html($('#FATUR', data).prop('innerHTML'));
				$("#VAL_RETORNO_" + suf).html($('#RETORNO', data).prop('innerHTML'));

				console.log(data);
			}
		});

		$('#DAT_INI_GRP, #DAT_FIM_GRP').datetimepicker({
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

	function retornaForm(index) {
		var newCampanha = new Object()
		newCampanha.COD_CAMPANHA = $("#ret_COD_CAMPANHA_" + index).val();
		newCampanha.DES_CANAL = $("#ret_DES_CANAL_" + index).val();
		newCampanha.DAT_INI_CONSULTA = $("#ret_DAT_INI_CONSULTA_" + index).val();
		newCampanha.DAT_FIM_CONSULTA = $("#ret_DAT_FIM_CONSULTA_" + index).val();


		return newCampanha;
	}

	function totalizadores() {
		number_format = function(number, decimals, dec_point, thousands_sep) {
			number = number.toFixed(decimals);

			var nstr = number.toString();
			nstr += '';
			x = nstr.split('.');
			x1 = x[0];
			x2 = x.length > 1 ? dec_point + x[1] : '';
			var rgx = /(\d+)(\d{3})/;

			while (rgx.test(x1))
				x1 = x1.replace(rgx, '$1' + thousands_sep + '$2');

			return x1 + x2;
		}
		$("#TOT_SMS").html($("a[data-canal=SMS]").length);
		$("#TOT_EMAIL").html($("a[data-canal=EMAIL]").length);

		var tot = 0;
		$("#table .VAL_INVEST").each(function() {
			var valor = $(this).html().replace(".", "").replace(",", ".");
			tot = tot + parseFloat(valor);
		});
		$("#TOT_INVESTIMENTO").html(number_format(tot, 2, ",", "."));
	}
	totalizadores();

	// function retornaForm(index){
	// 	$("#codBusca").val($("#ret_ID_"+index).val());			
	// 	$("#codBusca").val($("#ret_IDP_"+index).val());	
	// 	$('#formLista').attr('action', 'action.do?mod=<?php echo $DestinoPg; ?>&id='+$("#ret_ID_"+index).val()+'&idP='+$("#ret_IDP_"+index).val());					
	// 	$('#formLista').submit();					
	// }
</script>