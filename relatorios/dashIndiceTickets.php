<?php

if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}

$hashLocal = mt_rand();

$dat_ini = '';
$dat_fim = '';
$dias30 = '';
$hoje = '';
$cod_univend = '';
$log_labels = '';
$MES = '';
$totalTicketgerado = 0;
$totalClientes = 0;
$totalIndice_emissao = 0;
$totalQtd_vendas_oferta = 0;
$totalQtd_vendas_sem = 0;
$totalIndice_com_oferta = 0;
$totalVal_medio_oferta = 0;
$totalVal_media_venda = 0;
$totalVariacao_tkt = 0;
$totalVal_compra_sem = 0;
$totalVal_compra_com = 0;
$QTD_VENDAS_FIDELIZADASTOTAL = 0;
$ticketgerado1 = 0;



//inicialização de variáveis
$hoje = fnFormatDate(date("Y-m-d"));
// $hoje = fnFormatDate(date('Y-m-d', strtotime($hoje. '- 1 days')));
//$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 30 days')));
$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30 . '- 2 days')));

$conn = connTemp($cod_empresa, '');
$adm = $connAdm->connAdm();

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

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		if ($opcao != '') {
		}
	}
}

//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode($_GET['id']);
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI, DAT_CADASTR FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($adm, $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
		$dat_cadastr = $qrBuscaEmpresa['DAT_CADASTR'];
	}
} else {
	$cod_empresa = 0;
	//fnEscreve('entrou else');
}

//inicialização das variáveis - default	
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
	$dat_ini = fnDataSql($dias30);
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
	$dat_fim = fnDataSql($hoje);
}

//faz pesquisa por revenda (geral)
if ($cod_univend == "9999") {
	$temUnivend = "N";
} else {
	$temUnivend = "S";
}

//busca revendas do usuário
include "unidadesAutorizadas.php";

// Filtro por Grupo de Lojas
include "filtroGrupoLojas.php";

//indice de emissão de tickets - geral
$sql = "SELECT 
SUM(ticketgerado) ticketgerado,
SUM(QTD_VENDAS) QTD_VENDAS,
SUM(QTD_VENDASTKT) QTD_VENDASTKT,
SUM(VAL_MEDIA_VENDA) VAL_MEDIA_VENDA,
SUM(VAL_MEDIO_TKT) VAL_MEDIO_TKT
FROM (
	SELECT (
		SELECT COUNT(distinct cod_cliente)
		FROM TICKET
		WHERE date(TICKET.DAT_CADASTR) BETWEEN '$dat_ini' AND '$dat_fim' 
		AND TICKET.COD_EMPRESA=$cod_empresa 
		AND TICKET.COD_UNIVEND IN ($lojasSelecionadas)
		AND LOG_VISUALIZACAO=1) ticketgerado, 
	IFNULL(SUM(IF(A.LOG_TICKET='N' AND B.LOG_AVULSO = 'N',1,0)),0) AS QTD_VENDAS, 
	IFNULL(SUM(IF(A.LOG_TICKET='S',1,0)),0) AS QTD_VENDASTKT, 
	IFNULL(ROUND((SUM(IF(A.LOG_TICKET='N' AND B.LOG_AVULSO = 'N',A.VAL_TOTPRODU,0))/ IFNULL(SUM(IF(A.LOG_TICKET='N' AND B.LOG_AVULSO = 'N',1,0)),0)),2),0) VAL_MEDIA_VENDA, 						
	IFNULL(ROUND((SUM(IF(A.LOG_TICKET='S',A.VAL_TOTPRODU,0))/ IFNULL(SUM(IF(A.LOG_TICKET='S',1,0)),0)),2),0) VAL_MEDIO_TKT
	FROM vendas A FORCE INDEX (COD_UNIVEND,COD_CLIENTE,COD_STATUSCRED,DAT_CADASTR)
	INNER JOIN CLIENTES B ON B.COD_CLIENTE = A.COD_CLIENTE AND B.LOG_AVULSO = 'N'
	WHERE A.COD_STATUSCRED IN(0,1,2,3,4,5,7,8,9) 
	AND A.cod_empresa = $cod_empresa AND date(A.DAT_CADASTR) BETWEEN '$dat_ini' AND '$dat_fim' 
	AND A.COD_UNIVEND IN($lojasSelecionadas)
	GROUP BY A.COD_UNIVEND
)TMPTICKET";


// fnEscreve($sql);
//fntesteSql(connTemp($cod_empresa,''),$sql);

$arrayQuery = mysqli_query($conn, $sql);
$qrBuscaEmissaoTicket = mysqli_fetch_assoc($arrayQuery);

if (isset($qrBuscaEmissaoTicket)) {
	$ticketgerado1 = $qrBuscaEmissaoTicket['ticketgerado'];
	$qtd_vendas1 = $qrBuscaEmissaoTicket['QTD_VENDAS'];
	$qtd_vendastkt1 = $qrBuscaEmissaoTicket['QTD_VENDASTKT'];
	$val_media_venda1 = $qrBuscaEmissaoTicket['VAL_MEDIA_VENDA'];
	$val_medio_tkt1 = $qrBuscaEmissaoTicket['VAL_MEDIO_TKT'];

	//echo $val_media_venda1;
}

// fnEscreve($qtd_vendas1);
// fnEscreve($qtd_vendastkt1);
// fnEscreve($ticketgerado1);

// fnEscreve($val_media_venda1);
// fnEscreve($val_medio_tkt1);


$tktComVenda = ($ticketgerado1 != 0) ? ($qtd_vendastkt1 * 100) / $ticketgerado1 : 0;
$tktSemVenda = ($ticketgerado1 != 0) ? (($ticketgerado1 - $qtd_vendastkt1) * 100) / $ticketgerado1 : 0;
// $difTktMedio = (100-(($val_media_venda1*100)/$val_medio_tkt1));
$difTktMedio = ($val_media_venda1 != 0) ? (($val_medio_tkt1 / $val_media_venda1) * 100) - 100 : 0;
if ($val_media_venda1 > $val_medio_tkt1) {
	$txt_media = "<span class='text-danger'>menor</span>";
} else {
	$txt_media = "maior";
}

//echo $val_medio_tkt1."/".$val_media_venda1."* 100-100";
//fnEscreve($val_medio_tkt1);
//fnEscreve($val_media_venda1);

//fnEscreve($ticketgerado1);
//fnEscreve($qtd_vendas1);
//fnEscreve($val_media_venda1);
//fnEscreve($qtd_vendastkt1);
//fnEscreve($val_medio_tkt1);

//fnEscreve(strlen($dat_fim));
//fnEscreve($data_fim);
//fnEscreve($lojasSelecionadas);

$hor_ini = " 00:00";
$hor_fim = " 23:59";

?>

<style>
	.progress {
		height: 20px;
		margin-bottom: 10px;
	}

	.progress .skill {
		line-height: 20px;
		padding: 0;
		margin: 0 0 0 20px;
		text-shadow: 0 1px 1px rgba(0, 0, 0, .9);
		text-transform: uppercase;
	}

	.progress .skill .val {
		float: right;
		font-style: normal;
		text-shadow: 0 1px 1px rgba(0, 0, 0, .9);
		margin: 0 20px 0 0;
	}

	.progress-bar {
		text-align: left;
		transition-duration: 3s;
	}

	.progress>.progress-completed {
		position: absolute;
		right: 0px;
		font-weight: 800;
		text-shadow: 0 1px 1px rgba(0, 0, 0, .9);
		padding: 3px 10px 2px;
	}

	.corOn {
		background-color: #F6DDCC;
	}
</style>


<div class="push30"></div>

<div class="row">

	<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

		<div class="col-md12 margin-bottom-30">
			<!-- Portlet -->
			<div class="portlet portlet-bordered">
				<div class="portlet-title">
					<div class="caption">
						<i class="fal fa-terminal"></i>
						<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
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

						<fieldset>
							<legend>Filtros</legend>

							<div class="row">

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Unidade de Atendimento</label>
										<?php include "unidadesAutorizadasComboMulti.php"; ?>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Grupo de Lojas</label>
										<?php include "grupoLojasComboMulti.php"; ?>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Região</label>
										<?php include "grupoRegiaoMulti.php"; ?>
									</div>
								</div>

							</div>

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
						<div class="push50"></div>

						<div class="row text-center">

							<div class="form-group text-center col-lg-3">
								<h4>Emissão de Tickets</h4>
								<div class="push20"></div>

								<canvas id="donut"></canvas>
								<div class="push5"></div>
								Total de <b><?php echo fnValor($ticketgerado1, 0); ?></b> tickets emitidos
							</div>

							<div class="form-group text-center col-lg-1">

							</div>

							<div class="form-group text-center col-lg-4">
								<h4>Ticket Médio</h4>
								<div class="push20"></div>

								<canvas id="barra"></canvas>
								Ticket Médio <b><?php echo fnValor(abs($difTktMedio), 2); ?>%</b> <?php echo $txt_media; ?> com ofertas
							</div>


							<div class="form-group text-center col-lg-1">

							</div>

							<div class="form-group text-center col-lg-3">
								<h4>Índice de Emissão de Tickets</h4>
								<div class="push20"></div>

								<?php
								//Busca as unidade de venda
								/*$undadearray = array(
									'sql' => "select COD_UNIVEND,cod_empresa,nom_fantasi,NOM_UNIVEND from unidadevenda where cod_empresa=$cod_empresa",
									'cod_empresa' => $cod_empresa,
									'conntadm' => $connAdm->connAdm(),
									'IN' => 'N',
									'nomecampo' => '',
									'conntemp' => '',
									'SQLIN' => ""
								);
								$univendaarray = fnUnivend($undadearray);
								*/
								//================================================
								//busca emissão lojas

								// Filtro por Grupo de Lojas
								include "filtroGrupoLojas.php";

								// $sql = "SELECT 	A.COD_UNIVEND,
								// 				UNI.NOM_FANTASI,                                                         
								// 				ROUND((SELECT count(DISTINCT cod_cliente) FROM TICKET WHERE date(TICKET.DAT_CADASTR) BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' 
								// 		AND TICKET.COD_UNIVEND IN($lojasSelecionadas) 
								// 		AND TICKET.COD_EMPRESA= $cod_empresa 
								// 		AND LOG_VISUALIZACAO = 1),2)/
								// 		ROUND(COUNT(CASE WHEN A.COD_AVULSO=2 THEN (A.COD_CLIENTE) END),2)*100 indice_emissao
								// 		from vendas A FORCE INDEX (COD_UNIVEND,COD_CLIENTE,COD_STATUSCRED,DAT_CADASTR)
								// 		LEFT JOIN UNIDADEVENDA UNI ON UNI.COD_UNIVEND = A.COD_UNIVEND
								// 		where  A.COD_STATUSCRED IN(0,1,2,3,4,5,7,8,9)AND 
								// 		A.cod_empresa= $cod_empresa AND
								// 		A.COD_AVULSO=2 AND
								// 		date(A.DAT_CADASTR) BETWEEN  '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' and 
								// 		A.COD_UNIVEND IN($lojasSelecionadas) 
								// 		GROUP BY COD_UNIVEND";

								//Alterações referente ao chamado 6209
								/*$sql = "SELECT A.COD_UNIVEND, 
										       UNI.NOM_FANTASI,
											 ROUND((SELECT count(DISTINCT cod_cliente) 
												         FROM TICKET WHERE date(TICKET.DAT_CADASTR) 
												          BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' AND 
												             TICKET.COD_UNIVEND = A.COD_UNIVEND AND
												               TICKET.COD_EMPRESA= $cod_empresa AND 
																	       LOG_VISUALIZACAO = 1
																			 ),2)/ 
												 ROUND(COUNT(CASE WHEN A.COD_AVULSO=2 THEN (A.COD_CLIENTE) END),2)*100 indice_emissao 
												 
										 from vendas A  
										 LEFT JOIN UNIDADEVENDA UNI ON UNI.COD_UNIVEND = A.COD_UNIVEND 
										 WHERE   A.COD_STATUSCRED IN(0,1,2,3,4,5,7,8,9)AND 
										 			A.cod_empresa= $cod_empresa AND 
													A.COD_AVULSO=2 AND 
													date(A.DAT_CADASTR) 
													BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
													and A.COD_UNIVEND IN($lojasSelecionadas) 
													GROUP BY A.COD_UNIVEND";*/

								$sql = "SELECT 
													COD_UNIVEND,
													NOM_FANTASI,
													ROUND(	
														((SELECT COUNT(DISTINCT cod_cliente)
															FROM TICKET
															WHERE date(TICKET.DAT_CADASTR) BETWEEN '$dat_ini' AND '$dat_fim'  AND TICKET.COD_UNIVEND = tmptktp.COD_UNIVEND  AND TICKET.COD_EMPRESA=$cod_empresa AND LOG_VISUALIZACAO=1 
															)/CALC_EMISSAO) *100,2) indice_emissao
													FROM (
														SELECT A.COD_UNIVEND,
														UNI.NOM_FANTASI,
														ROUND(COUNT(CASE WHEN A.COD_AVULSO=2 THEN (A.COD_CLIENTE) END), 2)  CALC_EMISSAO

														FROM vendas A
														LEFT JOIN UNIDADEVENDA UNI ON UNI.COD_UNIVEND = A.COD_UNIVEND
														WHERE A.COD_STATUSCRED IN(0,1,2,3,4,5,7,8,9)
														AND A.cod_empresa= $cod_empresa
														AND A.COD_AVULSO=2
														AND date(A.DAT_CADASTR) BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
														AND A.COD_UNIVEND IN($lojasSelecionadas)
														GROUP BY A.COD_UNIVEND
													)tmptktp";



								// fnEscreve($sql);	
								//fnValidaSql(connTemp($cod_empresa,''),$sql);

								$arrayQuery = mysqli_query($conn, $sql);

								$count = 0;
								$temExpande = "";
								while ($qrEmissaoTicketsLojas = mysqli_fetch_assoc($arrayQuery)) {
									$count++;
									//$nom_univend = $qrEmissaoTicketsLojas['NOM_FANTASI'];
									$nom_univend = "substituir";
									$indice_emissao = $qrEmissaoTicketsLojas['indice_emissao'];

									// if($indice_emissao > 1){
									// 	$indice_emissao = 1;
									// }												

									//abre expansor de lojas
									if ($count == 9) {
										echo "<div id='expLojas' style='display:none;'>";
										$temExpande = "S";
									}
								?>
									<div class="progress99 skill-bar99 pull-left" style="width: 85%;">
										<div class="progress-bar99 progress-bar-info99" role="progressbar" aria-valuenow="<?php echo fnvalorSql(fnValor($indice_emissao, 0)); ?>" aria-valuemin="0" aria-valuemax="300">
											<span class="skill99 pull-left"><?php echo $qrEmissaoTicketsLojas['NOM_FANTASI']; ?></span>
										</div>
									</div>
									<div class="pull-right"><?php echo fnValor($indice_emissao, 2); ?>%</div>
									<div class="push5"></div>
								<?php

								}

								//fecha expansor de lojas
								if ($temExpande == "S") {
									echo "</div>";
									echo "<div class='push5'></div>";
									echo "<div id='dvShow' style='display:block;'><a id='btShow' class='btn btn-default btn-sm btn-block'><i class='fa fa-plus' aria-hidden='true'></i>&nbsp; Ver Mais Lojas </a></div>";
									echo "<div id='dvHide' style='display:none;'><a id='btHide' class='btn btn-default btn-sm btn-block'><i class='fa fa-minus' aria-hidden='true'></i>&nbsp; Ver Menos Lojas </a></div>";
								}

								?>

							</div>

							<div class="push50"></div>

							<div class="row text-center">

								<!-- <h4>Visão Geral </h4> -->
								<?php
								$sql = "SELECT
													IFNULL((
														SELECT SUM(qdt_tkt) FROM ( 
															SELECT COUNT(DISTINCT COD_CLIENTE) qdt_tkt
															FROM TICKET
															WHERE cod_empresa=$cod_empresa AND date(DAT_CADASTR) BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'
															AND LOG_VISUPDV=1
															and COD_UNIVEND IN($lojasSelecionadas)
															GROUP BY COD_UNIVEND)LOG_VISUPDV
														),0) LOG_VISUPDV,

													IFNULL((SELECT COUNT(distinct COD_CLIENTE) FROM TICKET 
														WHERE cod_empresa=$cod_empresa 
														AND date(DAT_CADASTR) BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' 
														AND LOG_VISUAPP=1
														and COD_UNIVEND IN($lojasSelecionadas)
														GROUP BY LOG_VISUAPP),0) LOG_VISUAPP,
													IFNULL((SELECT COUNT(distinct COD_CLIENTE) FROM TICKET 
														WHERE cod_empresa=$cod_empresa 
														AND date(DAT_CADASTR) BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' 
														AND LOG_VISUTOTEM=1
														and COD_UNIVEND IN($lojasSelecionadas)
														GROUP BY LOG_VISUTOTEM),0) LOG_VISUTOTEM
													";

								// fnescreve($sql);
								$arrayQuery = mysqli_query($conn, $sql);
								$qrBusca = mysqli_fetch_assoc($arrayQuery);
								$total = fnValor($ticketgerado1, 0);
								$log_visupdv = $qrBusca['LOG_VISUPDV'];
								$log_visupdvvirtual = @$qrBusca['LOG_VISUPDVVIRTUAL'];
								$log_visuapp = $qrBusca['LOG_VISUAPP'];
								$log_visutotem = $qrBusca['LOG_VISUTOTEM'];
								$pct_visupdv = ($ticketgerado1 != 0) ? ($log_visupdv / $ticketgerado1) * 100 : 0;
								$pct_visupdvvirtual = ($ticketgerado1 != 0) ? ($log_visupdvvirtual / $ticketgerado1) * 100 : 0;
								$pct_visuapp = ($ticketgerado1 != 0) ? ($log_visuapp / $ticketgerado1) * 100 : 0;
								$pct_visutotem = ($log_visutotem != 0) ? ($log_visutotem / $ticketgerado1) * 100 : 0;
								//fnEscreve($total);
								//fnEscreve($log_visupdv);
								?>
								<div class="push20"></div>

								<h4>Tickets Emitidos e Visualizados</h4>
								<div class="push20"></div>

								<div class="col-md-4 text-center text-info">
									<i class="fal fa-cash-register fa-3x" aria-hidden="true"></i>
									<b><br /><?= $log_visupdv ?></b><br />
									<small>
										<div class="push5"></div><?= fnValor($pct_visupdv, 2) ?>%
									</small><br />
									<small style="font-weight:normal;">pdv</small>
								</div>

								<!-- <div class="col-md-3 text-center text-info">
													<i class="fal fa-phone-laptop fa-3x" aria-hidden="true"></i>												
													<b><br/><?= $log_visupdvvirtual ?></b><br/>												
													<small><div class="push5"></div><?= fnValor($pct_visupdvvirtual, 2) ?>%</small><br/>												
													<small style="font-weight:normal;">pdv virtual</small>
												</div> -->

								<div class="col-md-4 text-center text-info">
									<i class="fal fa-tablet-android fa-3x" aria-hidden="true"></i>
									<b><br /><?= $log_visutotem ?></b><br />
									<small>
										<div class="push5"></div><?= fnValor($pct_visutotem, 2) ?>%
									</small><br />
									<small style="font-weight:normal;">totem</small>
								</div>

								<div class="col-md-4 text-center text-info">
									<i class="fal fa-mobile-android fa-3x" aria-hidden="true"></i>
									<b><br /><?= $log_visuapp ?></b><br />
									<small>
										<div class="push5"></div><?= fnValor($pct_visuapp, 2) ?>%
									</small><br />
									<small style="font-weight:normal;">app</small>
								</div>

								<div class="push50"></div>

							</div>
						</div>

					</div>

				</div>
			</div>

			<div class="push20"></div>

			<div class="portlet portlet-bordered">

				<div class="portlet-body">

					<div class="login-form">

						<div class="form-group text-center col-lg-12">

							<table class="table table-bordered table-hover">

								<thead>
									<tr>
										<th class="{sorter:false}"></th>
										<th class="f14 text-center"><b>Loja</b></th>
										<th class="f14 text-center">
											<div class="form-group">
												<label for="inputName" class="control-label"><b>Tickets <br />Emitidos</b></label>
												<input type="hidden" class="form-control input-sm" name="TOUR_TICKETS_EMITIDOS" id="TOUR_TICKETS_EMITIDOS" maxlength="100" value="">
											</div>
										</th>
										<th class="f14 text-center">
											<div class="form-group">
												<label for="inputName" class="control-label"><b>Vendas <br />Fidelizadas</b></label>
												<input type="hidden" class="form-control input-sm" name="TOUR_VENDAS_FIDELIZADAS" id="TOUR_VENDAS_FIDELIZADAS" maxlength="100" value="">
											</div>
										</th>

										<th class="f14 text-center">
											<div class="form-group">
												<label for="inputName" class="control-label"><b>Clientes Únicos</b></label>
												<input type="hidden" class="form-control input-sm" name="TOUR_CLIENTES_UNICOS" id="TOUR_CLIENTES_UNICOS" maxlength="100" value="">
											</div>
										</th>

										<th class="f14 text-center">
											<div class="form-group">
												<label for="inputName" class="control-label"><b>Índice de Emissão <br />de Tickets</b></label>
												<input type="hidden" class="form-control input-sm" name="TOUR_IND_EMISSAO_TICKETS" id="TOUR_IND_EMISSAO_TICKETS" maxlength="100" value="">
											</div>
										</th>

										<th class="f14 text-center">
											<div class="form-group">
												<label for="inputName" class="control-label"><b>Com ofertas/hábitos</b></label>
												<input type="hidden" class="form-control input-sm" name="TOUR_COM_OFERTASHABITOS" id="TOUR_COM_OFERTASHABITOS" maxlength="100" value="">
											</div>
										</th>
										<th class="f14 text-center">
											<div class="form-group">
												<label for="inputName" class="control-label"><b>Valor Vendas<br>Com Ofertas</b></label>
												<input type="hidden" class="form-control input-sm" name="TOUR_VALVENDAS_OFERTAS" id="TOUR_VALVENDAS_OFERTAS" maxlength="100" value="">
											</div>
										</th>
										<th class="f14 text-center">
											<div class="form-group">
												<label for="inputName" class="control-label"><b>Índice de Vendas <br />Com Ofertas</b></label>
												<input type="hidden" class="form-control input-sm" name="TOUR_INDICEVENDAS_COMOFERTAS" id="TOUR_INDICEVENDAS_COMOFERTAS" maxlength="100" value="">
											</div>
										</th>
										<th class="f14 text-center">
											<div class="form-group">
												<label for="inputName" class="control-label"><b>Ticket Médio <br />Com Ofertas</b></label>
												<input type="hidden" class="form-control input-sm" name="TOUR_TICKETMEDIO_COMOFERTAS" id="TOUR_TICKETMEDIO_COMOFERTAS" maxlength="100" value="">
											</div>
										</th>
										<th class="f14 text-center">
											<div class="form-group">
												<label for="inputName" class="control-label"><b>Vendas <br />Sem ofertas/hábitos</b></label>
												<input type="hidden" class="form-control input-sm" name="TOUR_VENDAS_SEMOFERTASHABITOS" id="TOUR_VENDAS_SEMOFERTASHABITOS" maxlength="100" value="">
											</div>
										</th>
										<th class="f14 text-center">
											<div class="form-group">
												<label for="inputName" class="control-label"><b>Valor Vendas <br />Sem ofertas</b></label>
												<input type="hidden" class="form-control input-sm" name="TOUR_VALVENDAS_SEMOFERTAS" id="TOUR_VALVENDAS_SEMOFERTAS" maxlength="100" value="">
											</div>
										</th>
										<th class="f14 text-center">
											<div class="form-group">
												<label for="inputName" class="control-label"><b>Ticket Médio <br />Sem ofertas</b></label>
												<input type="hidden" class="form-control input-sm" name="TOUR_TICKETMEDIO_SEMOFERTAS" id="TOUR_TICKETMEDIO_SEMOFERTAS" maxlength="100" value="">
											</div>
										</th>
										<th class="f14 text-center">
											<div class="form-group">
												<label for="inputName" class="control-label"><b>Variação <br />Ticket Médio</b></label>
												<input type="hidden" class="form-control input-sm" name="TOUR_VARIACAO_TICKETMEDIO" id="TOUR_VARIACAO_TICKETMEDIO" maxlength="100" value="">
											</div>
										</th>
									</tr>
								</thead>

								<tbody>

									<?php

									//loop - detalhes loja

									// Filtro por Grupo de Lojas
									include "filtroGrupoLojas.php";


									$sqllista = "SELECT 
													COD_UNIVEND,
													NOM_FANTASI,
													TICKETGERADO,
													QTDCLIENTES, 	
													(TICKETGERADO/ CALC_EMISSAO) *100 INDICE_EMISSAO  ,
													QTD_VENDAS_OFERTA,
													QTD_VENDAS_SEM,
													VAL_MEDIO_OFERTA,
													VAL_MEDIA_VENDA,
													VAL_COMPRA_SEM,
													VAL_COMPRA_COM,
													QTD_VENDAS_FIDELIZADAS

													FROM(
														SELECT 
														COD_UNIVEND,
														NOM_FANTASI,
														(SELECT COUNT(DISTINCT cod_cliente)
															FROM TICKET
															WHERE date(TICKET.DAT_CADASTR) BETWEEN '$dat_ini' AND '$dat_fim'  AND TICKET.COD_UNIVEND = tmptkt.COD_UNIVEND  AND TICKET.COD_EMPRESA=$cod_empresa AND LOG_VISUALIZACAO=1 
															) TICKETGERADO,
														QTDCLIENTES,
														CALC_EMISSAO,
														QTD_VENDAS_OFERTA,
														QTD_VENDAS_SEM,
														VAL_MEDIO_OFERTA,
														VAL_MEDIA_VENDA,
														VAL_COMPRA_SEM,
														VAL_COMPRA_COM,
														QTD_VENDAS_FIDELIZADAS
														FROM (  SELECT A.COD_UNIVEND,
															UNI.NOM_FANTASI, 
															''TICKETGERADO, 
															COUNT(DISTINCT a.cod_cliente) QTDCLIENTES,                                                                                                                 
		                                                                    /*((SELECT COUNT(DISTINCT cod_cliente)
		                                                                    FROM TICKET
		                                                                    WHERE date(TICKET.DAT_CADASTR) BETWEEN '$dat_ini' AND '$dat_fim' 
		                                                                    AND TICKET.COD_UNIVEND = A.COD_UNIVEND 
		                                                                    AND TICKET.COD_EMPRESA = $cod_empresa
		                                                                    AND TICKET.COD_UNIVEND!=4 
		                                                                    AND LOG_VISUALIZACAO=1)/COUNT(case when A.COD_AVULSO=2 then (A.COD_CLIENTE)
		                                                                    END) *100) INDICE_EMISSAO,*/

		                                                                    COUNT(case when A.COD_AVULSO=2 then (A.COD_CLIENTE) else null END) CALC_EMISSAO,																										
		                                                                    IFNULL(SUM(IF(A.LOG_TICKET='S',1,0)),0) AS QTD_VENDAS_OFERTA, 
		                                                                    IFNULL(SUM(IF(A.LOG_TICKET='N' AND B.LOG_AVULSO = 'N',1,0)),0) AS QTD_VENDAS_SEM, 
		                                                                    IFNULL(ROUND((SUM(IF(A.LOG_TICKET='S',A.VAL_TOTPRODU,0))/ IFNULL(SUM(IF(A.LOG_TICKET='S',1,0)),0)),2),0) VAL_MEDIO_OFERTA, 
		                                                                    IFNULL(ROUND((SUM(IF(A.LOG_TICKET='N' AND B.LOG_AVULSO = 'N',A.VAL_TOTPRODU,0))/ IFNULL(SUM(IF(A.LOG_TICKET='N' AND B.LOG_AVULSO = 'N',1,0)),0)),2),0) VAL_MEDIA_VENDA,										
		                                                                    IFNULL(SUM(IF(A.LOG_TICKET='N' AND B.LOG_AVULSO = 'N',A.VAL_TOTPRODU,0)),0) VAL_COMPRA_SEM,
		                                                                    IFNULL(SUM(IF(A.LOG_TICKET = 'S', A.VAL_TOTPRODU, 0)),0) VAL_COMPRA_COM,
		                                                                    IFNULL(ROUND(COUNT(CASE WHEN A.COD_AVULSO=2 THEN (A.COD_CLIENTE) END),2),0) QTD_VENDAS_FIDELIZADAS
		                                                                    FROM vendas A 
		                                                                    INNER JOIN CLIENTES B ON B.COD_CLIENTE = A.COD_CLIENTE AND B.LOG_AVULSO = 'N'
		                                                                    LEFT JOIN UNIDADEVENDA UNI ON UNI.COD_UNIVEND = A.COD_UNIVEND
		                                                                    WHERE A.COD_STATUSCRED IN(0,1,2,3,4,5,7,8,9) AND A.cod_empresa = $cod_empresa 
		                                                                    AND date(A.DAT_CADASTR) BETWEEN '$dat_ini' AND '$dat_fim' 
		                                                                    AND A.COD_UNIVEND IN ($lojasSelecionadas)
		                                                                    GROUP BY A.COD_UNIVEND)tmptkt)tmptkt1 ";
									//fnEscreve($sqllista);												
									$arrayQuery = mysqli_query($conn, $sqllista);

									$count = 0;
									while ($qrBuscaDados = mysqli_fetch_assoc($arrayQuery)) {
										$nom_univend = $qrBuscaDados['NOM_FANTASI'];
										$ticketgerado = $qrBuscaDados['TICKETGERADO'];
										$clientes = $qrBuscaDados['QTDCLIENTES'];
										$indice_emissao = $qrBuscaDados['INDICE_EMISSAO'];
										$qtd_vendas_oferta = $qrBuscaDados['QTD_VENDAS_OFERTA'];
										$qtd_vendas_sem = $qrBuscaDados['QTD_VENDAS_SEM'];
										$val_compra_sem = $qrBuscaDados['VAL_COMPRA_SEM'];
										$val_compra_com = $qrBuscaDados['VAL_COMPRA_COM'];
										$QTD_VENDAS_FIDELIZADAS = $qrBuscaDados['QTD_VENDAS_FIDELIZADAS'];

										//$indice_com_oferta = $qrBuscaDados['INDICE_COM_OFERTA'];													
										$indice_com_oferta = $qrBuscaDados['QTD_VENDAS_OFERTA'] / ($qrBuscaDados['QTD_VENDAS_SEM'] + $qrBuscaDados['QTD_VENDAS_OFERTA']) * 100;
										$val_medio_oferta = $qrBuscaDados['VAL_MEDIO_OFERTA'];
										//	echo $qrBuscaDados['QTD_VENDAS_OFERTA'].'/'.$qrBuscaDados['QTD_VENDAS_SEM'].'+'.$qrBuscaDados['QTD_VENDAS_OFERTA'].'*100';
										$val_media_venda = $qrBuscaDados['VAL_MEDIA_VENDA'];
										//$variacao_tkt = ($qrBuscaDados['VARIACAO_TKT']*100)-100;
										$variacao_tkt = ($qrBuscaDados['VAL_MEDIO_OFERTA'] / $qrBuscaDados['VAL_MEDIA_VENDA']) * 100 - 100;
										//echo $qrBuscaDados['VAL_MEDIO_OFERTA'].'/'.$qrBuscaDados['VAL_MEDIA_VENDA'].'*100-100';

										//fnEscreve($qrBuscaDados['VAL_MEDIO_OFERTA']);
										//fnEscreve($qrBuscaDados['VAL_MEDIA_VENDA']);

										//if($indice_emissao > 1){
										//	$indice_emissao = 1;
										//}

										$totalTicketgerado = $totalTicketgerado + $ticketgerado;
										$totalClientes = $totalClientes + $clientes;
										$totalIndice_emissao = $totalIndice_emissao + $indice_emissao;
										$totalQtd_vendas_oferta = $totalQtd_vendas_oferta + $qtd_vendas_oferta;
										$totalQtd_vendas_sem = $totalQtd_vendas_sem + $qtd_vendas_sem;
										$totalIndice_com_oferta = $totalIndice_com_oferta + $indice_com_oferta;
										$totalVal_medio_oferta = $totalVal_medio_oferta + $val_medio_oferta;
										$totalVal_media_venda = $totalVal_media_venda + $val_media_venda;
										$totalVariacao_tkt = $totalVariacao_tkt + $variacao_tkt;

										$totalVal_compra_sem = $totalVal_compra_sem + $val_compra_sem;
										$totalVal_compra_com = $totalVal_compra_com + $val_compra_com;
										$QTD_VENDAS_FIDELIZADASTOTAL += $QTD_VENDAS_FIDELIZADAS;

										/*echo $totalVal_compra_com;
										echo "<hr>";
										echo $totalQtd_vendas_oferta;*/


									?>

										<tr cod_univend="<?= $qrBuscaDados['COD_UNIVEND'] ?>">
											<td class='text-center'><a href='javascript:void(0)' onclick='abreDetail("<?= $qrBuscaDados["COD_UNIVEND"] ?>")'><i class='fa fa-plus' aria-hidden='true'></i></a></td>
											<td><?php echo $nom_univend; ?></td>
											<td class="text-right"><b class="f14 text-info"><?php echo fnValor($ticketgerado, 0); ?></b></td>
											<td class="text-right"><b class="f14 text-info"><?php echo fnValor($QTD_VENDAS_FIDELIZADAS, 0); ?></b></td>
											<td class="text-right"><b class="f14 text-info"><?php echo fnValor($clientes, 0); ?></b></td>
											<td class="text-right"><b class="f14 text-info"><?php echo fnValor(($indice_emissao), 2); ?>%</b></td>
											<td class="text-right corOn" style="background-color: #F8F9F9;"><b class="f14 text-info"><?php echo fnValor($qtd_vendas_oferta, 0); ?></b></td>
											<td class="text-right" style="background-color: #F8F9F9;"><b class="f14 text-info">R$ <?php echo fnValor($val_compra_com, 2); ?></b></td>
											<td class="text-right" style="background-color: #F8F9F9;"><b class="f14 text-info"><?php echo fnValor($indice_com_oferta, 2); ?>%</b></td>
											<td class="text-right" style="background-color: #F8F9F9;"><b class="f14 text-info">R$ <?php echo fnValor($val_medio_oferta, 2); ?></b></td>
											<td class="text-right"><b class="f14 text-info"><?php echo fnValor($qtd_vendas_sem, 0); ?></b></td>
											<td class="text-right"><b class="f14 text-info">R$ <?php echo fnValor($val_compra_sem, 2); ?></b></td>
											<td class="text-right"><b class="f14 text-info">R$ <?php echo fnValor($val_media_venda, 2); ?></b></td>
											<td class="text-right" style="background-color: #F8F9F9;"><b class="f14 text-info"><?php echo fnValor($variacao_tkt, 2); ?>%</b></td>
										</tr>

										<tr style='display:none; background-color: #fff; border:0; padding:0;' id="abreDetail_<?= $qrBuscaDados['COD_UNIVEND'] ?>">
											<td colspan='20' style='border:0; padding:0;'>
												<div id="mostraDetail_<?= $qrBuscaDados['COD_UNIVEND'] ?>">


												</div>
											</td>
										</tr>


									<?php
										$count++;
									}

									?>


								</tbody>

								<tfoot>
									<tr>
										<th class="f14 text-right"></th>
										<th class="f14 text-right"></th>
										<th class="f14 text-right"><b><?php echo fnValor($totalTicketgerado, 0); ?></b></th>
										<th class="f14 text-right"><b><?php echo fnValor($QTD_VENDAS_FIDELIZADASTOTAL, 0); ?></b></th>
										<th class="f14 text-right"><b><?php echo fnValor($totalClientes, 0); ?></b></th>
										<th class="f14 text-right"><b><?php echo ($totalQtd_vendas_oferta + $totalQtd_vendas_sem != 0) ? fnValor((($totalTicketgerado / ($totalQtd_vendas_oferta + $totalQtd_vendas_sem)) * 100), 2) : 0; ?>%</b></th>
										<th class="f14 text-right" style="background-color: #F8F9F9;"><b><?php echo fnValor($totalQtd_vendas_oferta, 0); ?></b></th>
										<th class="f14 text-right" style="background-color: #F8F9F9;"><b>R$ <?php echo fnValor($totalVal_compra_com, 2); ?></b></th>
										<th class="f14 text-right" style="background-color: #F8F9F9;"><b><?php echo ($totalQtd_vendas_oferta != 0) ?  fnValor((($totalQtd_vendas_oferta / ($totalQtd_vendas_oferta + $totalQtd_vendas_sem)) * 100), 2) : 0; ?>%</b></th>
										<th class="f14 text-right" style="background-color: #F8F9F9;"><b>R$ <?php echo ($totalQtd_vendas_oferta != 0) ?  fnValor(($totalVal_compra_com / $totalQtd_vendas_oferta), 2) : 0; ?></b></th>
										<th class="f14 text-right"><b><?php echo fnValor($totalQtd_vendas_sem, 0); ?></b></th>
										<th class="f14 text-right"><b>R$ <?php echo fnValor($totalVal_compra_sem, 2); ?></b></th>
										<th class="f14 text-right"><b>R$ <?php echo ($totalQtd_vendas_sem != 0) ? fnValor(($totalVal_compra_sem / $totalQtd_vendas_sem), 2)  : 0; ?></b></th>
										<th class="f14 text-right" style="background-color: #F8F9F9;">
											<b>
												<?php
												if ($totalQtd_vendas_oferta != 0 && $totalQtd_vendas_sem != 0) {
													echo fnValor((((($totalVal_compra_com / $totalQtd_vendas_oferta) / ($totalVal_compra_sem / $totalQtd_vendas_sem)) * 100) - 100), 2) . '%';
												} else {
													echo  fnValor(0, 0) . '%';
												}
												?>
											</b>
										</th>

									</tr>

									<td class="text-left">
										<small>
											<div class="btn-group dropdown left">
												<button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fal fa-file-excel"></i>
													&nbsp; Exportar &nbsp;
													<span class="fas fa-caret-down"></span>
												</button>
												<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
													<li><a class="btn btn-sm exportarCSV" data-attr="all" style="text-align: left"><i aria-hidden="true"></i>&nbsp; Exportar (Geral) </a></li>
													<li><a class="btn btn-sm exportarCSV" data-attr="detalhes" style="text-align: left"><i aria-hidden="true"></i>&nbsp;Exportar Detalhes </a></li>
													<!-- <li><a tabindex="-1" href="#">Separated link</a></li> -->
												</ul>
											</div>
										</small>
									</td>
								</tfoot>

							</table>

						</div>



					</div>

					<input type="hidden" name="LOJAS" id="LOJAS" value="<?= $lojasSelecionadas ?>">
					<input type="hidden" name="opcao" id="opcao" value="">
					<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
					<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
					<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
					<div class="push5"></div>



					<div class="push30"></div>


				</div>

			</div>
		</div>
		<!-- fim Portlet -->
	</form>

</div>





<div class="push20"></div>


<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />

<script src="js/gauge.coffee.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.min.js"></script>
<script src="js/plugins/Chart_Js/utils.js"></script>

<script>
	//datas
	$(function() {

		var cod_empresa = "<?= $cod_empresa ?>";

		$('.datePicker').datetimepicker({
			format: 'DD/MM/YYYY',
			maxDate: 'now',
			minDate: '2018-12-31'
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


	//graficos
	$(document).ready(function() {

		$('#btShow').click(function() {
			$("#dvShow").hide();
			$("#dvHide").show();
			$("#expLojas").toggle("slow");
		});

		$('#btHide').click(function() {
			$("#dvHide").hide();
			$("#dvShow").show();
			$("#expLojas").toggle("slow");
		});

		//chosen
		$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
		$('#formulario').validator();

		$(".exportarCSV").click(function() {
			let tipo = $(this).attr("data-attr");
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
										url: "relatorios/ajxDashIndiceTickets.do?opcao=exportar&tipo=" + tipo + "&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>",
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

		//donut - índice de emissão de tickets
		var config = {
			type: 'doughnut',
			data: {
				datasets: [{
					data: [

						<?php echo fnvalorSql(fnValor($qtd_vendastkt1, 2)); ?>,
						<?php echo fnvalorSql(fnValor($qtd_vendas1, 2)); ?>
					],
					backgroundColor: [
						window.chartColors.green,
						window.chartColors.blue,
					],
				}],
				labels: [
					["Vendas COM ofertas/hábitos <?= fnValor($tktComVenda, 2) ?>%"],
					["Vendas SEM ofertas/hábitos <?= fnValor($tktSemVenda, 2) ?>%"]
				]
			},
			options: {
				//rotation: 1 * Math.PI,
				//circumference: 1 * Math.PI,
				// tooltips: {
				//         enabled: false
				//    },
				responsive: true,
				legend: {
					position: 'bottom',
				},
				title: {
					display: false,
					text: 'Índice de Emissão de Tickets'
				},
				animation: {
					animateScale: true,
					animateRotate: true
				}
			}
		};

		window.onload = function() {
			var ctx = document.getElementById("donut").getContext("2d");
			window.myDoughnut = new Chart(ctx, config);
		};

		// Bar chart - ticket médio
		var ctx = document.getElementById("barra");
		ctx.height = 220;

		var mybarChart = new Chart(ctx, {
			type: 'bar',
			data: {
				labels: ["COM ofertas/hábitos", "SEM ofertas/hábitos"],
				datasets: [{
					data: [<?php echo fnvalorSql(fnValor(($totalVal_compra_com / $totalQtd_vendas_oferta), 2)); ?>, <?php echo fnvalorSql(fnValor(($totalVal_compra_sem / $totalQtd_vendas_sem), 2)); ?>],
					backgroundColor: [
						window.chartColors.green,
						window.chartColors.blue,
					],
					label: ' Ticket Médio (R$)'
				}],
			},

			options: {
				legend: {
					display: false
				},
				scales: {
					yAxes: [{
						ticks: {
							suggestedMin: 0,
							suggestedMax: 100,
							stepSize: 10,
							beginAtZero: true
						}
					}],
				},
				animation: {
					onComplete: function(animation) {

					}
				}
			}
		});

		//progress bar - índice de emissão de tickets - lojas
		$('.progress .progress-bar').css("width",
			function() {
				return $(this).attr("aria-valuenow") + "%";
			}
		)



	});

	function abreDetail(idUni) {
		var idItem = $('#abreDetail_' + idUni);

		if (!idItem.is(':visible')) {
			$.ajax({
				type: "POST",
				url: "relatorios/ajxDashIndiceTickets.do?id=<?= fnEncode($cod_empresa) ?>&idu=" + idUni + "&opcao=expandir",
				data: $("#formulario").serialize(),
				beforeSend: function() {
					$("#mostraDetail_" + idUni).html('<div class="loading" style="width: 100%;"></div>');
				},
				success: function(data) {
					$("#mostraDetail_" + idUni).html(data);
				},
				error: function(data) {
					$("#mostraDetail_" + idUni).html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
					console.log(data);
				}
			});

			idItem.show();

			$('[cod_univend="' + idUni + '"]').find($(".fa")).removeClass('fa-plus').addClass('fa-minus');
		} else {
			idItem.hide();
			$('[cod_univend="' + idUni + '"]').find($(".fa")).removeClass('fa-minus').addClass('fa-plus');
		}
	}
</script>