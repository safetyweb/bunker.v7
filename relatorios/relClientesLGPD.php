<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$tipoVenda = "";
$hashLocal = "";
$condData = "";
$hoje = "";
$dias30 = "";
$msgRetorno = "";
$msgTipo = "";
$dat_ini = "";
$dat_fim = "";
$numCartao = "";
$nomCliente = "";
$opcao = "";
$hHabilitado = "";
$hashForm = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$cod_cliente_av = "";
$tip_retorno = "";
$casasDec = "";
$arrayParamAutorizacao = [];
$autoriza = "";
$lojasSelecionadas = "";
$cod_univendUsu = "";
$qtd_univendUsu = 0;
$lojasAut = "";
$usuReportAdm = "";
$nom_cliente = "";
$andNome = "";
$andCartao = "";
$andUnivend = "";
$andCelular = "";
$arrayCount = [];
$qrCount = "";
$qtd_total = 0;
$qtd_envio = 0;
$qtd_total_aceite_count = 0;
$pct_qtd_total_aceite_count = 0;
$qtd_total_nao_aceite_count = 0;
$qtd_total_reenvio = 0;
$qtd_antigos_atualizados = 0;
$qtd_novos_atualizados = 0;
$pct_qtd_total_nao_aceite = 0;
$qtd_total_nao_aceite = 0;
$pct_qtd_novos = 0;
$pct_qtd_antigos = 0;
$retorno = "";
$totalitens_por_pagina = "";
$qtd_total_aceite = 0;
$pct_qtd_total_aceite = 0;
$qtd_pdvsh = 0;
$qtd_totem = 0;
$qtd_hotsite = 0;
$inicio = "";
$qrListaEmpresas = "";
$canal = "";
$colCliente = "";
$mostraCracha = "";
$mostraPlaca = "";
$content = "";
$condicaoCartao = "";
$andCreditos = "";
$condicaoVendaPDV = "";



//fnMostraForm();
// definir o numero de itens por pagina
$itens_por_pagina = 50;
$pagina = 1;


$tipoVenda = "T";
$hashLocal = mt_rand();
$condData = "";

//inicializaÃ§Ã£o de variÃ¡veis
$hoje = fnFormatDate(date("Y-m-d"));
$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30 . '- 30 days')));
//$dias30 = fnFormatDate(date("Y-m-d"));
//$cod_univend = "9999"; //todas revendas - default

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_empresa = fnLimpaCampoZero(@$_REQUEST['COD_EMPRESA']);
		$cod_univend = fnLimpaCampoZero(@$_REQUEST['COD_UNIVEND']);
		$cod_grupotr = fnLimpaCampoZero(@$_REQUEST['COD_GRUPOTR']);
		$cod_tiporeg = fnLimpaCampoZero(@$_REQUEST['COD_TIPOREG']);
		$dat_ini = fnDataSql(@$_REQUEST['DAT_INI']);
		$dat_fim = fnDataSql(@$_REQUEST['DAT_FIM']);
		$numCartao = fnLimpaCampo(@$_REQUEST['NUM_CARTAO']);
		$nomCliente = fnLimpaCampo(@$_REQUEST['NOM_CLIENTE']);

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];
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

//inicializaÃƒÆ’Ã‚Â§ÃƒÆ’Ã‚Â£o das variÃƒÆ’Ã‚Â¡veis - default	
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
	$dat_ini = fnDataSql($dias30);
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
	$dat_fim = fnDataSql($hoje);
}

//busca revendas do usuÃƒÆ’Ã‚Â¡rio
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
// fnEscreve($cod_univend);

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

	.tooltip-inner {
		max-width: 70%;
		margin-left: auto;
		margin-right: auto;
		word-wrap: break-word;
	}

	.info-header p,
	.panel p,
	.panel h4 {
		margin: 5px 0px !important;
	}

	.tooltip-arrow,
	.red-tooltip+.tooltip>.tooltip-inner {
		background-color: #f9fafb;
		color: #3c3c3c;
		margin-top: -40px !important;
	}

	.tooltip.in {
		opacity: 1 !important;
		pointer-events: none !important;
	}

	.tooltip .tooltip-arrow {
		top: 15 !important;
		border-bottom-color: #f9fafb !important;
		/* black */
		background-color: transparent !important;
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

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Data Inicial de Cadastro</label>

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
										<label for="inputName" class="control-label required">Data Final de Cadastro</label>

										<div class="input-group date datePicker" id="DAT_FIM_GRP">
											<input type='text' class="form-control input-sm data" name="DAT_FIM" id="DAT_FIM" value="<?php echo fnFormatDate($dat_fim); ?>" required />
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
										</div>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label">Nome do Cliente</label>
										<input type="text" class="form-control input-sm" name="NOM_CLIENTE" id="NOM_CLIENTE" value="<?php echo $nomCliente; ?>">
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Número do Cartão</label>
										<input type="text" class="form-control input-sm int" name="NUM_CARTAO" id="NUM_CARTAO" value="<?php echo $numCartao; ?>">
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

								<div class="col-md-2">
									<div class="push20"></div>
									<button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-sm btn-block getBtn"><i class="fal fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
								</div>

							</div>

						</fieldset>

						<input type="hidden" name="LOJAS" id="LOJAS" value="<?php echo $lojasSelecionadas; ?>" />
						<input type="hidden" name="AUTORIZA" id="AUTORIZA" value="<?= $autoriza ?>" />
						<input type="hidden" name="CASAS_DEC" id="CASAS_DEC" value="<?= $casasDec ?>">
						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
						<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
						<input type="hidden" name="COD_UNIVEND_FILTRO" id="COD_UNIVEND_FILTRO" value="<?php echo fnEncode($cod_univend); ?>">

					</form>

					<div class="push20"></div>

					<div class="row">
						<div class="col-md-12">

							<div class="push20"></div>

							<?php

							if ($nom_cliente != '' && $nom_cliente != 0) {
								$andNome = 'AND CL.NOM_CLIENTE LIKE "' . $nom_cliente . '%"';
							} else {
								$andNome = ' ';
							}

							if ($numCartao != '' && $numCartao != 0) {
								$andCartao = 'AND CL.NUM_CARTAO=' . $numCartao;
							} else {
								$andCartao = ' ';
							}

							if ($cod_univend != '9999') {
								$andUnivend = "AND CL.COD_UNIVEND IN ($lojasSelecionadas)";
							} else {
								$andUnivend = "AND (CL.COD_UNIVEND IN ($lojasSelecionadas) OR CL.COD_UNIVEND IS NULL OR CL.COD_UNIVEND = 0)";
							}

							// Filtro por Grupo de Lojas
							include "filtroGrupoLojas.php";

							$sql = "SELECT 
							SUM(QTD_TOTAL_CLIENTE) QTD_TOTAL_CLIENTE,
							SUM(QTD_TOTAL_COM_ACEITE) QTD_TOTAL_COM_ACEITE, 
							SUM(QTD_TOTAL_SEM_ACEITE) QTD_TOTAL_SEM_ACEITE, 
							SUM(QTD_ANTIGOS_ATUALIZADOS) QTD_ANTIGOS_ATUALIZADOS,
							SUM(QTD_NOVOS_ATUALIZADOS) QTD_NOVOS_ATUALIZADOS,
							SUM(QTD_TOTEM) QTD_TOTEM,
							SUM(QTD_HOTSITE) QTD_HOTSITE,
							SUM(QTD_PDVSH) QTD_PDVSH
							FROM(
								SELECT 
								CASE WHEN LOG_TERMO IN ('S','N') THEN 1 ELSE NULL END QTD_TOTAL_CLIENTE,
								CASE WHEN LOG_TERMO='S' THEN 1 ELSE NULL END QTD_TOTAL_COM_ACEITE, 
								CASE WHEN LOG_TERMO='N'  THEN 1 ELSE NULL END QTD_TOTAL_SEM_ACEITE,	
								CASE WHEN  date(CL.DAT_CADASTR) < DATE (termocli.DAT_CADASTR) AND  LOG_TERMO='S' THEN 1 ELSE 0 END QTD_ANTIGOS_ATUALIZADOS, 
								CASE WHEN  date(CL.DAT_CADASTR) >= date(termocli.DAT_CADASTR) and  LOG_TERMO='S' THEN 1 ELSE 0 END QTD_NOVOS_ATUALIZADOS,
								CASE WHEN LOGC.COD_CANAL = 1 THEN 1 ELSE 0 END QTD_PDVSH,
								CASE WHEN LOGC.COD_CANAL = 2 THEN 1 ELSE 0 END QTD_TOTEM,
								CASE WHEN LOGC.COD_CANAL = 3 THEN 1 ELSE 0 END QTD_HOTSITE 	
								FROM clientes CL    
								LEFT JOIN clientes_termos termocli ON termocli.COD_CLIENTE=CL.COD_CLIENTE AND CL.COD_EMPRESA=termocli.COD_EMPRESA
								LEFT JOIN LOG_CANAL LOGC ON LOGC.COD_CLIENTE = CL.COD_CLIENTE AND LOGC.cod_empresa=CL.COD_EMPRESA
								WHERE 
								CL.COD_EMPRESA = $cod_empresa AND 
								CL.DAT_CADASTR BETWEEN '$dat_ini 00:00:00'AND '$dat_fim 23:59:59'   
								$andCelular
								$andUnivend 
								GROUP BY CL.COD_CLIENTE
							)TMP_TOTAL_CLIENTES";

							// fnEscreve($sql);
							$arrayCount = mysqli_query(connTemp($cod_empresa, ''), $sql);
							$qrCount = mysqli_fetch_assoc($arrayCount);

							$qtd_total = $qrCount['QTD_TOTAL_CLIENTE'];
							$qtd_envio = $qrCount['QTD_TOTAL_COM_ACEITE'];
							$qtd_total_aceite_count = $qrCount['QTD_TOTAL_COM_ACEITE'];
							$pct_qtd_total_aceite_count = $qtd_total != 0 ? ($qtd_total_aceite_count * 100) / $qtd_total : 0;
							$qtd_total_nao_aceite_count = $qrCount['QTD_TOTAL_SEM_ACEITE'];
							$qtd_total_reenvio = @$qrCount['QTD_REENVIO'];
							$qtd_antigos_atualizados = $qrCount['QTD_ANTIGOS_ATUALIZADOS'];
							$qtd_novos_atualizados = $qrCount['QTD_NOVOS_ATUALIZADOS'];
							$pct_qtd_total_nao_aceite = $qtd_total != 0 ? ($qtd_total_nao_aceite * 100) / $qtd_total : 0;
							$pct_qtd_novos = $qtd_total_aceite_count != 0 ? ($qtd_novos_atualizados * 100) / $qtd_total_aceite_count : 0;
							$pct_qtd_antigos = $qtd_total_aceite_count != 0 ? ($qtd_antigos_atualizados * 100) / $qtd_total_aceite_count : 0;
							// fnEscreve($sql);



							/*	$sql = "SELECT  count(case when log_avulso='N' then
															 COD_CLIENTE
															END)  QTD_TOTAL,
													count(case when  log_termo='S' then
													 COD_CLIENTE
													END)  QTD_TOTAL_ACEITE,
													count(case when  log_termo='N' then
													 COD_CLIENTE
													END)  QTD_TOTAL_NAO_ACEITE
													
											FROM clientes CL 
											WHERE COD_EMPRESA = $cod_empresa                          
                                           	AND DAT_CADASTR BETWEEN '$dat_ini 00:00:00 ' AND '$dat_fim 23:59:59' 
                                            AND log_avulso='N'
                                            $andUnivend
                                            $andNome
											$andCartao
									";
											   
									//fnEscreve($sql);
									$retorno = mysqli_query(connTemp($cod_empresa,''),$sql);
									$totalitens_por_pagina = mysqli_fetch_assoc($retorno);
									$numPaginas = ceil($totalitens_por_pagina['QTD_TOTAL']/$itens_por_pagina);
									//fnEscreve($totalitens_por_pagina['QTD_TOTAL_NAO_ACEITE']);
									
									$qtd_total = $totalitens_por_pagina['QTD_TOTAL'];*/
							$numPaginas = ceil($qrCount['QTD_TOTAL_CLIENTE'] / $itens_por_pagina);
							$qtd_total_aceite = $qrCount['QTD_TOTAL_COM_ACEITE'];
							$qtd_total_nao_aceite = $qrCount['QTD_TOTAL_SEM_ACEITE'];
							$pct_qtd_total_aceite = $qtd_total != 0 ? ($qtd_total_aceite * 100) / $qtd_total : 0;
							$pct_qtd_total_nao_aceite = $qtd_total != 0 ? ($qtd_total_nao_aceite * 100) / $qtd_total : 0;
							$qtd_pdvsh = $qrCount['QTD_PDVSH'];
							$qtd_totem = $qrCount['QTD_TOTEM'];
							$qtd_hotsite = $qrCount['QTD_HOTSITE'];

							//variavel para calcular o inÃ­cio da visualizaÃ§Ã£o com base na pÃ¡gina atual
							$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;


							?>
						</div>
					</div>

					<div class="push5"></div>

				</div>
			</div>
		</div>

		<div class="push30"></div>

		<style>
			.shadow2 {
				padding: 15px 0 10px 0;
			}
		</style>

		<div class="row">

			<div class="col-md-12 col-lg-12 margin-bottom-30">
				<!-- Portlet -->
				<div class="portlet portlet-bordered">

					<div class="portlet-body">

						<div class="row text-center">

							<div class="col-md-4">
								<div class="shadow2 red-tooltip" data-html="true" data-toggle='tooltip' data-placement='bottom' data-original-title='
										<div class="row">
											<div class="col-xs-12 text-left">
												<div class="push10"></div>
												<p class="f14" style="margin: 0px!important;"><b>Total</b> de cadastros realizados <b>dentro</b> da faixa das datas do filtro.</p>
												<div class="push10"></div>
											</div>
										</div>
										'>
									<div class="col-md-8 top-content">
										<p>Cadastros</p>
										<label><?php echo fnValor($qtd_total, 0); ?></label>
									</div>
									<div class="col-md-4">
										<div id="main-pie" class="pie-title-center" data-percent="100">
											<span class="pie-value"></span>
										</div>
									</div>
									<div class="clearfix"> </div>
								</div>
							</div>

							<div class="col-md-4">
								<div class="shadow2">
									<div class="col-md-8 top-content">
										<p>Clientes com Aceite</p>
										<label><?php echo fnValor($qtd_total_aceite, 0); ?></label>
									</div>
									<div class="col-md-4">
										<div id="main-pie2" class="pie-title-center" data-percent="<?php echo fnValor($pct_qtd_total_aceite, 2); ?>">
											<span class="pie-value"></span>
										</div>
									</div>
									<div class="clearfix"> </div>
								</div>
							</div>

							<div class="col-md-4">
								<div class="shadow2">
									<div class="col-md-8 top-content">
										<p>Clientes sem Aceite</p>
										<label><?php echo fnValor($qtd_total_nao_aceite, 0); ?></label>
									</div>
									<div class="col-md-4">
										<div id="main-pie3" class="pie-title-center" data-percent="<?php echo fnValor($pct_qtd_total_nao_aceite, 2); ?>">
											<span class="pie-value"></span>
										</div>
									</div>
									<div class="clearfix"> </div>
								</div>
							</div>

						</div>

						<div class="row text-center">

							<div class="col-md-4">
								<div class="shadow2 red-tooltip" data-html="true" data-toggle='tooltip' data-placement='bottom' data-original-title='
									<div class="row">
										<div class="col-xs-12 text-left">
											<div class="push10"></div>
											<p class="f14" style="margin: 0px!important;"><b>Soma</b> dos <b>novos</b> e <b>antigos</b>.</p>
											<div class="push10"></div>
										</div>
									</div>
									'>
									<div class="col-md-8 top-content">
										<p>Atualizados</p>
										<label><?php echo fnValor($qtd_total_aceite_count, 0); ?></label>
										<p style="font-size: 14px;">&nbsp;</p>
									</div>
									<div class="col-md-4">
										<div id="main-pie4" class="pie-title-center" data-percent="<?php echo fnValor($pct_qtd_total_aceite_count, 2); ?>">
											<span class="pie-value"><?php echo fnValor($pct_qtd_total_aceite_count, 2); ?>%</span>
										</div>
									</div>
									<div class="clearfix"> </div>
								</div>
							</div>

							<div class="col-md-4">
								<div class="shadow2 red-tooltip" data-html="true" data-toggle='tooltip' data-placement='bottom' data-original-title='
								<div class="row">
									<div class="col-xs-12 text-left">
										<div class="push10"></div>
										<p class="f14" style="margin: 0px!important;">Clientes que aceitaram os termos <b>dentro</b> da faixa das datas do filtro.</p>
										<div class="push10"></div>
									</div>
								</div>
								'>
									<div class="col-md-12 top-content">
										<p>Novos</p>
										<label><?php echo fnValor($qtd_novos_atualizados, 0); ?></label>
										<p style="font-size: 14px;"><?= fnValor($pct_qtd_novos, 2) ?>%</p>
									</div>
									<!-- <div class="col-md-4">    
                                            <div id="main-pie" class="pie-title-center" data-percent="100">
                                                <span class="pie-value">100%</span>
                                            </div>
                                        </div> -->
									<div class="clearfix"> </div>
								</div>
							</div>

							<div class="col-md-4">
								<div class="shadow2 red-tooltip" data-html="true" data-toggle='tooltip' data-placement='bottom' data-original-title='
                                	<div class="row">
                                		<div class="col-xs-12 text-left">
                                			<div class="push10"></div>
                                			<p class="f14" style="margin: 0px!important;">Clientes que aceitaram os termos <b>antes</b> da data do filtro.</p>
                                			<div class="push10"></div>
                                		</div>
                                	</div>
                                	'>
									<div class="col-md-12 top-content">
										<p>Antigos</p>
										<label><?php echo fnValor($qtd_antigos_atualizados, 0); ?></label>
										<p style="font-size: 14px;"><?= fnValor($pct_qtd_antigos, 2) ?>%</p>
									</div>
									<!-- <div class="col-md-4">    
                                            <div id="main-pie" class="pie-title-center" data-percent="100">
                                                <span class="pie-value">100%</span>
                                            </div>
                                        </div> -->
									<div class="clearfix"> </div>
								</div>
							</div>

						</div>

						<div class="row text-center">

							<div class="col-md-4">
								<div class="shadow2 red-tooltip" data-html="true" data-toggle='tooltip' data-placement='bottom' data-original-title='
                            		<div class="row">
                            			<div class="col-xs-12 text-left">
                            				<div class="push10"></div>
                            				<p class="f14" style="margin: 0px!important;">Total de Clientes cadastrados via PDV da SH.</p>
                            				<div class="push10"></div>
                            			</div>
                            		</div>
                            		'>
									<div class="col-md-12 top-content">
										<p>PDV SH</p>
										<label><?php echo fnValor($qtd_pdvsh, 0); ?></label><!-- 
                            			<p style="font-size: 14px;"><?= fnValor($pct_qtd_antigos, 2) ?>%</p> -->
									</div>
									<div class="clearfix"> </div>
								</div>
							</div>

							<div class="col-md-4">
								<div class="shadow2 red-tooltip" data-html="true" data-toggle='tooltip' data-placement='bottom' data-original-title='
                                	<div class="row">
                                		<div class="col-xs-12 text-left">
                                			<div class="push10"></div>
                                			<p class="f14" style="margin: 0px!important;">Total de Clientes cadastrados via Totem.</p>
                                			<div class="push10"></div>
                                		</div>
                                	</div>
                                	'>
									<div class="col-md-12 top-content">
										<p>Totem</p>
										<label><?php echo fnValor($qtd_totem, 0); ?></label><!-- 
                                		<p style="font-size: 14px;"><?= fnValor($pct_qtd_antigos, 2) ?>%</p> -->
									</div>
									<div class="clearfix"> </div>
								</div>
							</div>

							<div class="col-md-4">
								<div class="shadow2 red-tooltip" data-html="true" data-toggle='tooltip' data-placement='bottom' data-original-title='
                                	<div class="row">
                                		<div class="col-xs-12 text-left">
                                			<div class="push10"></div>
                                			<p class="f14" style="margin: 0px!important;">Total de Clientes cadastrados via Hotsite.</p>
                                			<div class="push10"></div>
                                		</div>
                                	</div>
                                	'>
									<div class="col-md-12 top-content">
										<p>Hotsite</p>
										<label><?php echo fnValor($qtd_hotsite, 0); ?></label><!-- 
                                		<p style="font-size: 14px;"><?= fnValor($pct_qtd_antigos, 2) ?>%</p> -->
									</div>
									<div class="clearfix"> </div>
								</div>
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

							<div class="no-more-tables">


								<table class="table table-bordered table-striped table-hover tablesorter" id="tablista">

									<thead>
										<tr>
											<th>Código</th>
											<th>Nome do Cliente</th>
											<th>Data de Cadastro</th>
											<th>Canal de Cadastro</th>
											<th>Última Compra</th>
											<th>Loja</th>
											<th>Vendedor</th>
											<th>Cartão</th>
											<th>CPF</th>
											<th>Telefone</th>
											<th>Celular</th>
											<th>e-Mail</th>
											<th width="100">Saldo Disponí­vel</th>
											<th width="100">Saldo Bloqueado</th>
										</tr>
									</thead>

									<tbody id="relatorioConteudo">

										<?php

										//lista de clientes
										$sql = "SELECT  CL.COD_CLIENTE,
            								CL.NOM_CLIENTE,
            								CL.DAT_CADASTR,
            								CL.DAT_ULTCOMPR,
            								UNI.NOM_FANTASI,
            								USU.NOM_USUARIO,
            								CL.NUM_CARTAO,
            								CL.NUM_CGCECPF,
            								CL.NUM_TELEFON,
            								CL.NUM_CELULAR,
            								CL.DES_EMAILUS,
            								LOGC.COD_CANAL,
            								CL.COD_VENDEDOR,
            								(SELECT ifnull(SUM(VAL_SALDO),0)
            									FROM CREDITOSDEBITOS CDB
            									WHERE CDB.COD_CLIENTE=CL.COD_CLIENTE 
            									AND TIP_CREDITO='C' 
            									AND COD_STATUSCRED=1 
            									AND (DATE_FORMAT(DAT_EXPIRA, '%Y-%m-%d')  >= DATE_FORMAT(NOW(),'%Y-%m-%d') or(LOG_EXPIRA='N'))
            									AND COD_EMPRESA = $cod_empresa ) AS VAL_SALDO,
            								(SELECT ifnull(SUM(VAL_SALDO),0)
            									FROM CREDITOSDEBITOS CDB
            									WHERE CDB.COD_CLIENTE=CL.COD_CLIENTE 
            									AND TIP_CREDITO='C' 
            									AND COD_STATUSCRED IN (3,7) 
            									AND (DATE_FORMAT(DAT_EXPIRA, '%Y-%m-%d')  >= DATE_FORMAT(NOW(),'%Y-%m-%d') or(LOG_EXPIRA='N'))
            									AND COD_EMPRESA = $cod_empresa ) AS SALDO_BLOQUEADO

            								FROM CLIENTES CL
            								LEFT JOIN USUARIOS USU ON USU.COD_USUARIO = CL.COD_VENDEDOR
            								LEFT JOIN unidadevenda UNI ON UNI.COD_UNIVEND=CL.COD_UNIVEND
            								LEFT JOIN LOG_CANAL LOGC ON LOGC.COD_CLIENTE = CL.COD_CLIENTE AND LOGC.cod_empresa=CL.COD_EMPRESA
            								WHERE CL.COD_EMPRESA = $cod_empresa
            								AND CL.DAT_CADASTR BETWEEN '$dat_ini 00:00:00 ' AND '$dat_fim 23:59:59'
            								$andUnivend
            								$andNome
            								$andCartao
            								ORDER BY CL.NOM_CLIENTE 
            								LIMIT $inicio,$itens_por_pagina";


										$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
										// fnEscreve($sql);
										//  echo "___".$sql."___";
										$count = 0;

										while ($qrListaEmpresas = mysqli_fetch_assoc($arrayQuery)) {

											switch ($qrListaEmpresas['COD_CANAL']) {

												case 2:
													$canal = "TOTEM";
													break;

												case 3:
													$canal = "HOTSITE";
													break;

												case 4:
													$canal = "BUNKER";
													break;

												case 5:
													$canal = "PDV VIRTUAL";
													break;

												case 6:
													$canal = "MAIS CASH";
													break;

												default:
													$canal = "PDV SH";
													break;
											}

											$count++;


											if ($autoriza == 1) {
												$colCliente = "<td><small><a href='action.do?mod=" . fnEncode(1024) . "&id=" . fnEncode($cod_empresa) . "&idC=" . fnEncode($qrListaEmpresas['COD_CLIENTE']) . "' target='_blank'>" . fnMascaraCampo($qrListaEmpresas['NOM_CLIENTE']) . "&nbsp;" . $mostraCracha . "</a></small></td>";
											} else {
												$colCliente = "<td><small>" . fnMascaraCampo($qrListaEmpresas['NOM_CLIENTE']) . "&nbsp;" . $mostraCracha . "</small></td>";
											}

											echo "
            									<tr>
            									<td><small>" . $qrListaEmpresas['COD_CLIENTE'] . "</small></td>
            									" . $colCliente . "
            									<td><small>" . fnDataFull($qrListaEmpresas['DAT_CADASTR']) . "</small></td>
            									<td><small>" . $canal . "</small></td>
            									<td><small>" . fnDataFull($qrListaEmpresas['DAT_ULTCOMPR']) . "</small></td>
            									<td><small>" . $qrListaEmpresas['NOM_FANTASI'] . "</small></td>
            									<td> <small>" . $qrListaEmpresas['NOM_USUARIO'] . "</small></td>
            									<td><small>" . fnMascaraCampo($qrListaEmpresas['NUM_CARTAO']) . "</small></td>
            									<td><small>" . fnMascaraCampo($qrListaEmpresas['NUM_CGCECPF']) . "</small></td>
            									<td><small>" . fnMascaraCampo($qrListaEmpresas['NUM_TELEFON']) . "</small></td>
            									<td><small>" . fnMascaraCampo($qrListaEmpresas['NUM_CELULAR']) . "</small></td>
            									<td><small>" . fnMascaraCampo(strtolower($qrListaEmpresas['DES_EMAILUS'])) . "</small></td>
            									$mostraPlaca
            									<td class='text-right'><small>R$ " . fnValor($qrListaEmpresas['VAL_SALDO'], $casasDec) . "</small></td>
            									<td class='text-right'><small>R$ " . fnValor($qrListaEmpresas['SALDO_BLOQUEADO'], $casasDec) . "</small></td>
            									</tr>
            									<input type='hidden' id='ret_COD_CLIENTE_" . $count . "' value='" . fnEncode($qrListaEmpresas['COD_CLIENTE']) . "'>
            									<input type='hidden' id='ret_COD_EMPRESA_" . $count . "' value='" . fnEncode($cod_empresa) . "'>
            									";
										}

										?>

									</tbody>

									<tfoot>
										<th class="" colspan="100">
											<center>
												<ul id="paginacao" class="pagination-sm"></ul>
											</center>
										</th>
									</tfoot>

								</table>
								<div class="col-xs-2">

									<div class="dropdown">
										<a class="dropdown-toggle btn btn-info" data-toggle="dropdown" href="#">
											<span class="fal fa-file-excel"></span> Exportar
										</a>
										<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
											<li><a tabindex="-1" href="javascript:void(0);" onclick="exportaRel('geral')">Resumo Geral</a></li>
											<li><a tabindex="-1" href="javascript:void(0);" onclick="exportaRel('aceite')">Clientes com Aceite</a></li>
											<li><a tabindex="-1" href="javascript:void(0);" onclick="exportaRel('semAceite')">Clientes sem Aceite</a></li>
										</ul>
									</div>
								</div>


							</div>

						</div>


						<div class="push50"></div>

						<div class="push"></div>

					</div>

				</div>
			</div>
			<!-- fim Portlet -->
		</div>

	</div>

</div>

<div class="push20"></div>

<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />

<script src="js/pie-chart.js"></script>

<script type="text/javascript">
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

	function exportaRel(opcao) {
		// alert(opcao);
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
									url: "relatorios/ajxClientesLGPD.do?opcao=exportar&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>&condicaoCartao=<?php echo $condicaoCartao; ?>&andCreditos=<?php echo $andCreditos; ?>&condicaoVendaPDV=<?php echo $condicaoVendaPDV; ?>&andNome=<?php echo $andNome; ?>&tipoRel=" + opcao + "",
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
	};



	function reloadPage(idPage) {
		$.ajax({
			type: "POST",
			url: "relatorios/ajxClientesLGPD.do?opcao=paginar&id=<?php echo fnEncode($cod_empresa); ?>&idPage=" + idPage + "&itens_por_pagina=<?php echo $itens_por_pagina; ?>&condicaoCartao=<?php echo $condicaoCartao; ?>&andCreditos=<?php echo $andCreditos; ?>&condicaoVendaPDV=<?php echo $condicaoVendaPDV; ?>&andNome=<?php echo $andNome; ?>",
			data: $('#formulario').serialize(),
			beforeSend: function() {
				$('#relatorioConteudo').html('<tr><td colspan="100"><div class="loading" style="width: 100%;"></div></tr></td>');
			},
			success: function(data) {
				$("#relatorioConteudo").html(data);
				$(".tablesorter").trigger("updateAll");
			},
			error: function() {
				$('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina nÃ£o encontrados...</p>');
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

		$('#main-pie').pieChart({
			barColor: '#2c3e50',
			trackColor: '#eee',
			lineCap: 'round',
			lineWidth: 8,
			onStep: function(from, to, percent) {
				var decimal = (parseFloat($(this.element).attr('data-percent').replace(",", ".")) % 1) * 100;
				$(this.element).find('.pie-value').text(percent.toFixed(0) + "," + decimal.toFixed(0).padStart(2, '0') + '%');
			}
		});

		$('#main-pie2').pieChart({
			barColor: '#3bb2d0',
			trackColor: '#eee',
			lineCap: 'round',
			lineWidth: 8,
			onStep: function(from, to, percent) {
				var decimal = (parseFloat($(this.element).attr('data-percent').replace(",", ".")) % 1) * 100;
				$(this.element).find('.pie-value').text(percent.toFixed(0) + "," + decimal.toFixed(0).padStart(2, '0') + '%');
			}
		});

		$('#main-pie3').pieChart({
			barColor: '#E74C3C',
			trackColor: '#eee',
			lineCap: 'round',
			lineWidth: 8,
			onStep: function(from, to, percent) {
				var decimal = (parseFloat($(this.element).attr('data-percent').replace(",", ".")) % 1) * 100;
				$(this.element).find('.pie-value').text(percent.toFixed(0) + "," + decimal.toFixed(0).padStart(2, '0') + '%');
			}
		});

		$('#main-pie4').pieChart({
			barColor: '#E74C3C',
			trackColor: '#eee',
			lineCap: 'round',
			lineWidth: 8,
			onStep: function(from, to, percent) {
				var decimal = (parseFloat($(this.element).attr('data-percent').replace(",", ".")) % 1) * 100;
				$(this.element).find('.pie-value').text(percent.toFixed(0) + "," + decimal.toFixed(0).padStart(2, '0') + '%');
			}
		});


	});
</script>