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
$opcao = "";
$hHabilitado = "";
$hashForm = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$cod_desafio = "";
$cod_cliente = "";
$formBack = "";
$qrLista = "";
$sql2 = "";
$qrTotalDesafio = "";
$total_desafio = 0;
$qrTotalFeitos = "";
$totalFeitos = 0;
$qrMeta = "";
$qrMeta2 = "";
$objetivoDesafio = "";
$clientesFaltam = "";
$totalProjetado = 0;
$resgateProjetado = "";
$vvrProjetado = "";
$countLinha = "";
$qrListaVendas = "";
$TOTAL_QTD_ATENDIMENTO = 0;
$TOTAL_QTD_ATINGIDO = 0;
$TOTAL_VAL_METADES = 0;
$TOTAL_QTD_CLIENTE = 0;
$TOTAL_VAL_RESGATE = 0;
$TOTAL_VAL_TOTVENDA = 0;
$TOTAL_VAL_VENDAS_VINCULADAS = 0;
$lojasSelecionadas = "";
$content = "";


$hashLocal = mt_rand();

//inicialização de variáveis
//$hoje = fnFormatDate(date("Y-m-d"));
$hoje = fnFormatDate(date('Y-m-d', strtotime($hoje . '- 1 days')));
//$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 30 days')));
$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30 . '- 2 days')));

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

		if ($opcao != '' && $opcao != 0) {
		}
	}
}

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

if (isset($_GET['idD'])) {
	$cod_desafio = fnDecode(@$_GET['idD']);
} else {
	$cod_desafio = 0;
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
//fnEscreve($cod_cliente);

?>

<style>
	small[class^='qtde_col'] {
		font-weight: normal;
	}

	table a:not(.btn),
	.table a:not(.btn) {
		text-decoration: none;
	}

	table a:not(.btn):hover,
	.table a:not(.btn):hover {
		text-decoration: underline;
	}

	.activeRel {
		text-decoration: underline !important;
	}
</style>

<div class="push30"></div>

<div class="row">

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="glyphicon glyphicon-calendar"></i>
					<span class="text-primary"> <?php echo $NomePg; ?></span>
				</div>

				<?php
				$formBack = "1381";
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

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Unidade de Atendimento</label>
										<?php include "unidadesAutorizadasComboMulti.php"; ?>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Vendedor</label>
										<select data-placeholder="Selecione um vendedor" name="COD_USUARIO" id="COD_USUARIO" class="chosen-select-deselect" style="width:100%;">
											<option value="">&nbsp;</option>
											<?php

											$sql = "SELECT * from USUARIOS 
					                                                            WHERE COD_EMPRESA = $cod_empresa 
					                                                            AND DAT_EXCLUSA IS NULL 
					                                                            AND COD_TPUSUARIO in(7,11) 
					                                                            ORDER BY NOM_USUARIO";
											$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

											while ($qrLista = mysqli_fetch_assoc($arrayQuery)) {
												echo "
					                                                              <option value='" . $qrLista['COD_USUARIO'] . "'>" . $qrLista['NOM_USUARIO'] . "</option> 
					                                                            ";
											}
											?>

										</select>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="push20"></div>
									<button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-sm btn-block getBtn"><i class="fa fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
								</div>


							</div>

						</fieldset>

						<div class="push20"></div>

						<div class="row">

							<?php

							//totais
							$sql2 = "SELECT count(1) as hitsDesafio from DESAFIO_CONTROLE where DESAFIO_CONTROLE.COD_DESAFIO = $cod_desafio ;
														";
							//fnEscreve($sql);	
							$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql2);
							$qrTotalDesafio = mysqli_fetch_assoc($arrayQuery);
							$total_desafio = $qrTotalDesafio['hitsDesafio'];

							//totais
							$sql2 = "SELECT count(distinct cod_cliente) as hitsFeitos from FOLLOW_CLIENTE where FOLLOW_CLIENTE.COD_DESAFIO = $cod_desafio ;
														";
							//fnEscreve($sql);	
							$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql2);
							$qrTotalFeitos = mysqli_fetch_assoc($arrayQuery);
							$totalFeitos = $qrTotalFeitos['hitsFeitos'];

							$sql = "SELECT  
														B.VAL_METADES,
														COUNT(A.COD_CLIENTE),
														B.DAT_INI,
														B.DAT_FIM,

														IFNULL((SELECT COUNT(DISTINCT C.COD_CLIENTE) FROM VENDAS C,DESAFIO_CONTROLE D 
														  WHERE C.COD_CLIENTE=D.COD_CLIENTE AND
														        D.COD_DESAFIO=A.COD_DESAFIO AND 
																   DATE_FORMAT(C.DAT_CADASTR_WS, '%Y-%m-%d') >= B.DAT_INI AND 
																	DATE_FORMAT(C.DAT_CADASTR_WS, '%Y-%m-%d')<= B.DAT_FIM ),0) QTD_CLIENTE,

														IFNULL((SELECT SUM(VAL_TOTVENDA) FROM VENDAS C,DESAFIO_CONTROLE D 
														  WHERE C.COD_CLIENTE=D.COD_CLIENTE AND
														        D.COD_DESAFIO=A.COD_DESAFIO AND  
																   DATE_FORMAT(C.DAT_CADASTR_WS, '%Y-%m-%d')>= B.DAT_INI AND 
																	DATE_FORMAT(C.DAT_CADASTR_WS, '%Y-%m-%d')<= B.DAT_FIM ),0) VAL_TOTVENDA,
														IFNULL((SELECT SUM(VAL_CREDITO) FROM CREDITOSDEBITOS D,DESAFIO_CONTROLE E 
														       WHERE D.COD_CLIENTE=E.COD_CLIENTE AND
														       E.COD_DESAFIO=A.COD_DESAFIO AND 
														  		 D.TIP_CREDITO='D' AND
														   		DATE_FORMAT(D.DAT_REPROCE, '%Y-%m-%d')>= B.DAT_INI AND 
																	DATE_FORMAT(D.DAT_REPROCE, '%Y-%m-%d')<= B.DAT_FIM ),0) VAL_RESGATE,
														IFNULL((SELECT SUM(VAL_TOTVENDA) FROM VENDAS E ,CREDITOSDEBITOS F, DESAFIO_CONTROLE G  
														    WHERE 
															 E.COD_VENDA=F.COD_VENDA AND 
															 F.COD_CLIENTE=G.COD_CLIENTE AND
															 G.COD_DESAFIO=A.COD_DESAFIO AND 
														    F.TIP_CREDITO='D' AND
														   DATE_FORMAT(F.DAT_REPROCE, '%Y-%m-%d')>= B.DAT_INI AND DATE_FORMAT(F.DAT_REPROCE, '%Y-%m-%d')<= B.DAT_FIM ),0) VAL_VENDAS_VINCULADAS 
														  
														   
														FROM DESAFIO_CONTROLE A, DESAFIO B
														WHERE A.COD_DESAFIO=B.COD_DESAFIO AND 
														      A.COD_DESAFIO = $cod_desafio AND 
														      A.COD_EMPRESA = $cod_empresa";

							$qrMeta = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa, ''), $sql));

							$sql = "SELECT * FROM DESAFIO WHERE COD_DESAFIO = $cod_desafio AND COD_EMPRESA = $cod_empresa";

							$qrMeta2 = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa, ''), $sql));

							$objetivoDesafio = ($qrMeta2['VAL_METADES'] / 100) * $total_desafio;
							$clientesFaltam  = $objetivoDesafio - $totalFeitos;
							$totalProjetado =  $qrMeta['QTD_CLIENTE'] != 0 ? ($qrMeta['VAL_TOTVENDA'] * $objetivoDesafio) / $qrMeta['QTD_CLIENTE'] : 0;
							$resgateProjetado =  $qrMeta['QTD_CLIENTE'] != 0 ? ($qrMeta['VAL_RESGATE'] * $objetivoDesafio) / $qrMeta['QTD_CLIENTE'] : 0;
							$vvrProjetado =  $qrMeta['QTD_CLIENTE'] != 0 ? ($qrMeta['VAL_VENDAS_VINCULADAS'] * $objetivoDesafio) / $qrMeta['QTD_CLIENTE'] : 0;
							//fnEscreve($qrMeta['VAL_TOTVENDA']);
							// fnEscreve($qrMeta2['VAL_METADES']);
							//fnEscreve($qrMeta['QTD_CLIENTE']);

							?>

							<div class="col-md-4 top-content">

								<div class="col-md-12 top-content" style="background: #F4F6F6; border-radius: 5px;">

									<div class="push5"></div>
									Objetivo
									<div class="push10"></div>

									<div class="col-md-7 no-side-padding">
										<p><span class='f13'>Clientes na Lista:</span></p>
									</div>
									<div class="col-md-5"><span><small><?= fnValor($total_desafio, 0) ?></small></span></div>

									<div class="push5"></div>

									<div class="col-md-7 no-side-padding">
										<p><span class='f13'>Período:</span></p>
									</div>
									<div class="col-md-4"><span><small><?= fnDataShort($qrMeta2['DAT_INI']) . " à " . fnDataShort($qrMeta2['DAT_FIM']) ?></small></span></div>

									<div class="push5"></div>

									<div class="col-md-7 no-side-padding">
										<p><span class='f13'>Meta:</span></p>
									</div>
									<div class="col-md-5"><span><small><?= fnValor($qrMeta2['VAL_METADES'], 2) ?>%</small></span></div>

									<div class="push5"></div>

									<div class="col-md-7 no-side-padding">
										<p><span class='f13'>Faltam:</span></p>
									</div>
									<div class="col-md-5"><span><small><?= fnValor($clientesFaltam, 0) ?></small></span></div>

									<div class="push5"></div>

								</div>

							</div>

							<div class="col-md-4">

								<div class="col-md-12 top-content" style="background: #F4F6F6; border-radius: 5px;">

									<div class="push5"></div>
									Alcançado
									<div class="push10"></div>

									<div class="col-md-7 no-side-padding">
										<p><span class='f13'>Clientes com compras:</span></p>
									</div>
									<div class="col-md-5"><span><small><?= $qrMeta['QTD_CLIENTE'] ?></small></span></div>

									<div class="push5"></div>

									<div class="col-md-7 no-side-padding">
										<p><span class='f13'>Valor total:</span></p>
									</div>
									<div class="col-md-5"><span><small>R$ <?= fnValor($qrMeta['VAL_TOTVENDA'], 2) ?></small></span></div>

									<div class="push5"></div>

									<div class="col-md-7 no-side-padding">
										<p><span class='f13'>Resgates:</span></p>
									</div>
									<div class="col-md-5"><span><small>R$ <?= fnValor($qrMeta['VAL_RESGATE'], 2) ?></small></span></div>

									<div class="push5"></div>

									<div class="col-md-7 no-side-padding">
										<p><span class='f13'>VVR:</span></p>
									</div>
									<div class="col-md-5"><span><small>R$ <?= fnValor($qrMeta['VAL_VENDAS_VINCULADAS'], 2) ?></small></span></div>

									<div class="push20"></div>
									<div class="push5"></div>

								</div>

							</div>

							<div class="col-md-4">

								<div class="col-md-12 top-content" style="background: #F4F6F6; border-radius: 5px;">

									<div class="push5"></div>
									Potencial da Meta
									<div class="push10"></div>

									<div class="col-md-7 no-side-padding">
										<p><span class='f13'>Clientes com compras:</span></p>
									</div>
									<div class="col-md-5"><span><small><?php echo fnValor($objetivoDesafio, 0); ?></small></span></div>

									<div class="push5"></div>

									<div class="col-md-7 no-side-padding">
										<p><span class='f13'>Valor total:</span></p>
									</div>
									<div class="col-md-5"><span><small>R$ <?php echo fnValor($totalProjetado, 2); ?></small></span></div>

									<div class="push5"></div>

									<div class="col-md-7 no-side-padding">
										<p><span class='f13'>Resgates:</span></p>
									</div>
									<div class="col-md-5"><span><small>R$ <?php echo fnValor($resgateProjetado, 2); ?></small></span></div>

									<div class="push5"></div>

									<div class="col-md-7 no-side-padding">
										<p><span class='f13'>VVR:</span></p>
									</div>
									<div class="col-md-5"><span><small>R$ <?php echo fnValor($vvrProjetado, 2); ?></small></span></div>

									<div class="push20"></div>
									<div class="push5"></div>

								</div>

							</div>

						</div>

						<div class="push20"></div>

						<div class="row">

							<div class="col-md-3 linksNav">

								<h3 style="margin-top:0;">Filtros</h3>

								<div class="push10"></div>

								<a class="activeRel" href="javascript:void(0)" onclick="geraRelDesafio('loja',this)">&rsaquo; Loja </a>
								<div class="push5"></div>

								<a href="javascript:void(0)" onclick="geraRelDesafio('vendedor',this)">&rsaquo; Vendedor </a>
								<div class="push5"></div>

							</div>

							<div class="col-md-9" id="relatorioConteudo">

								<div class="push20"></div>

								<table class="table table-bordered table-hover  ">

									<thead>
										<tr>
											<th><small>Loja</small></th>
											<th><small>Atendimentos</small></th>
											<th><small>Atingido</small></th>
											<th><small>% Meta</small></th>
											<th><small>Clientes</small></th>
											<th><small>Tot. Vendas</small></th>
											<th><small>Val. Resgate</small></th>
											<th><small>Vendas <br />Vinculadas</small></th>
										</tr>
									</thead>

									<?php
									// Filtro por Grupo de Lojas
									include "filtroGrupoLojas.php";

									$sql = "SELECT A.COD_UNIVEND, 
																       F.NOM_FANTASI, 
																       COUNT(*) AS QTD_ATENDIMENTO, 
																	       (SELECT COUNT(*) QTD_ATENDIMENTO 
																	        FROM   DESAFIO_CONTROLE AA 
																	               INNER JOIN CLIENTES BB 
																	                       ON AA.COD_CLIENTE = BB.COD_CLIENTE 
																	                          AND AA.COD_EMPRESA = BB.COD_EMPRESA 
																	        WHERE  BB.LOG_AVULSO = 'N' 
																	               AND (SELECT FC.DES_COMENT 
																	                    FROM   FOLLOW_CLIENTE FC 
																	                    WHERE  FC.COD_EMPRESA = A.COD_EMPRESA 
																	                           AND FC.COD_CLIENTE = BB.COD_CLIENTE 
																	                           AND FC.COD_DESAFIO = AA.COD_DESAFIO 
																	                           AND COD_FOLLOW = (SELECT MAX(COD_FOLLOW) 
																	                                             FROM   FOLLOW_CLIENTE 
																	                                             WHERE  COD_CLIENTE = BB.COD_CLIENTE 
																	                                                    AND COD_DESAFIO = 
																	                                                        AA.COD_DESAFIO)) IS NOT NULL 
																	               AND AA.COD_DESAFIO = A.COD_DESAFIO 
																	               AND AA.COD_EMPRESA = A.COD_EMPRESA 
																	               AND AA.COD_UNIVEND = A.COD_UNIVEND) AS QTD_ATINGIDO, 

																       D.VAL_METADES, 

																	       IFNULL((SELECT COUNT(DISTINCT CC.COD_CLIENTE) 
																	               FROM   VENDAS CC, 
																	                      DESAFIO_CONTROLE DD 
																	               WHERE  CC.COD_CLIENTE = DD.COD_CLIENTE 
																	                      AND DD.COD_DESAFIO = A.COD_DESAFIO 
																	                      AND CC.COD_UNIVEND = A.COD_UNIVEND 
																	                      AND DATE_FORMAT(CC.DAT_CADASTR_WS, '%Y-%m-%d') >= D.DAT_INI 
																	                      AND DATE_FORMAT(CC.DAT_CADASTR_WS, '%Y-%m-%d') <= D.DAT_FIM), 0) AS QTD_CLIENTE, 
																	       IFNULL((SELECT SUM(VAL_TOTVENDA) 
																	               FROM   VENDAS CC, 
																	                      DESAFIO_CONTROLE DD 
																	               WHERE  CC.COD_CLIENTE = DD.COD_CLIENTE 
																	                      AND DD.COD_DESAFIO = A.COD_DESAFIO 
																	                      AND CC.COD_UNIVEND = A.COD_UNIVEND 
																	                      AND DATE_FORMAT(CC.DAT_CADASTR_WS, '%Y-%m-%d') >= D.DAT_INI 
																	                      AND DATE_FORMAT(CC.dat_cadastr_ws, '%Y-%m-%d') <= D.dat_fim), 0) AS VAL_TOTVENDA, 
																	       IFNULL((SELECT SUM(VAL_CREDITO) 
																	               FROM   CREDITOSDEBITOS DD, 
																	                      DESAFIO_CONTROLE EE 
																	               WHERE  DD.COD_CLIENTE = EE.COD_CLIENTE 
																	                      AND EE.COD_DESAFIO = A.COD_DESAFIO 
																	                      AND DD.COD_UNIVEND = A.COD_UNIVEND 
																	                      AND DD.TIP_CREDITO = 'D' 
																	                      AND DATE_FORMAT(DD.DAT_REPROCE, '%Y-%m-%d') >= D.DAT_INI 
																	                      AND DATE_FORMAT(DD.DAT_REPROCE, '%Y-%m-%d') <= D.DAT_FIM), 0) AS VAL_RESGATE, 
																	       IFNULL((SELECT SUM(VAL_TOTVENDA) 
																	               FROM   VENDAS EE, 
																	                      CREDITOSDEBITOS FF, 
																	                      DESAFIO_CONTROLE GG 
																	               WHERE  EE.COD_VENDA = FF.COD_VENDA 
																	                      AND EE.COD_UNIVEND = A.COD_UNIVEND 
																	                      AND FF.COD_CLIENTE = GG.COD_CLIENTE 
																	                      AND GG.COD_DESAFIO = A.COD_DESAFIO 
																	                      AND FF.TIP_CREDITO = 'D' 
																	                      AND DATE_FORMAT(FF.DAT_REPROCE, '%Y-%m-%d') >= D.DAT_INI 
																	                      AND DATE_FORMAT(FF.DAT_REPROCE, '%Y-%m-%d') <= D.DAT_FIM), 0) AS VAL_VENDAS_VINCULADAS 
																FROM   DESAFIO_CONTROLE A 
																       INNER JOIN CLIENTES B 
																               ON A.COD_CLIENTE = B.COD_CLIENTE 
																                  AND A.COD_EMPRESA = B.COD_EMPRESA 
																       LEFT JOIN CATEGORIA_CLIENTE C 
																              ON C.COD_CATEGORIA = B.COD_CATEGORIA 
																       INNER JOIN DESAFIO D 
																               ON A.COD_DESAFIO = D.COD_DESAFIO 
																       INNER JOIN WEBTOOLS.UNIDADEVENDA F 
																               ON F.COD_UNIVEND = A.COD_UNIVEND 
																WHERE  B.LOG_AVULSO = 'N' 
																       AND A.COD_DESAFIO = $cod_desafio 
																       AND A.COD_EMPRESA = $cod_empresa 
																GROUP BY A.COD_UNIVEND 
																ORDER BY B.NOM_CLIENTE ";

									//fnEscreve($sql);
									$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

									$countLinha = 1;
									while ($qrListaVendas = mysqli_fetch_assoc($arrayQuery)) {

									?>

										<tr>
											<td><small><b><?php echo $qrListaVendas['NOM_FANTASI']; ?></b></small></td>
											<td class="text-center"><small><?php echo fnValor($qrListaVendas['QTD_ATENDIMENTO'], 0); ?></small></td>
											<td class="text-center"><small><?php echo fnValor($qrListaVendas['QTD_ATINGIDO'], 0); ?></small></td>
											<td class="text-center"><small><?php echo fnValor($qrListaVendas['VAL_METADES'], 2); ?>%</small></td>
											<td class="text-center"><small><?php echo fnValor($qrListaVendas['QTD_CLIENTE'], 0); ?></small></td>
											<td class="text-center"><small><small>R$ </small></small><small><?php echo fnValor($qrListaVendas['VAL_TOTVENDA'], 2); ?></small></td>
											<td class="text-center"><small><small>R$ </small></small><small><?php echo fnValor($qrListaVendas['VAL_RESGATE'], 2); ?></small></td>
											<td class="text-center"><small><small>R$ </small></small><small><?php echo fnValor($qrListaVendas['VAL_VENDAS_VINCULADAS'], 2); ?></small></td>
										</tr>

									<?php

										// $TOTAL_QTD_ATENDIMENTO += $qrListaVendas['QTD_ATENDIMENTO'];
										$TOTAL_QTD_ATINGIDO += $qrListaVendas['QTD_ATINGIDO'];
										// $TOTAL_VAL_METADES += $qrListaVendas['VAL_METADES'];
										// $TOTAL_QTD_CLIENTE += $qrListaVendas['QTD_CLIENTE'];
										// $TOTAL_VAL_RESGATE += $qrListaVendas['VAL_RESGATE'];
										// $TOTAL_VAL_TOTVENDA += $qrListaVendas['VAL_TOTVENDA'];
										// $TOTAL_VAL_VENDAS_VINCULADAS += $qrListaVendas['VAL_VENDAS_VINCULADAS'];

										$countLinha++;
									}

									?>
									</tbody>

									<tfoot>
										<tr colspan="2"></tr>
										<tr>
											<th><?= fnValor($TOTAL_QTD_ATINGIDO, 2) ?></th>
										</tr>
										<tr>
											<th colspan="100">
												<a class="btn btn-info btn-sm exportarCSV"><i class="fa fa-file-excel" aria-hidden="true"></i>&nbsp; Exportar </a>
											</th>
										</tr>
									</tfoot>

								</table>

							</div>

						</div>

						<input type="hidden" name="COD_DESAFIO" id="COD_DESAFIO" value="<?= $cod_desafio ?>">
						<input type="hidden" name="REFRESH_CLIENTE" id="REFRESH_CLIENTE" value="N">
						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
						<input type="hidden" name="LOJAS" id="LOJAS" value="<?php echo $lojasSelecionadas; ?>">

						<div class="push5"></div>

					</form>

					<div class="push50"></div>

					<div class="push"></div>

				</div>

				<span class="f12" style="color: #fff;">
					<?php
					//echo ($sql);
					?>
				</span>

			</div>
		</div>
		<!-- fim Portlet -->
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
	//datas
	$(function() {

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

		var TOTAL_VAL_VINCULADO = 0;
		var TOTAL_VAL_RESGATE = 0;

		// Carregar totais de quantidade na linhas
		$("div[id^='total_col']").each(function(index) {
			var total = 0;

			if (!$(this).hasClass('porcent')) {
				$(".qtde_col" + $(this).attr('id').replace('total_col', '')).each(function(index, item) {
					total += limpaValor($(this).text());
				});

				if ($(this).hasClass('VAL_VINCULADO')) {
					TOTAL_VAL_VINCULADO = total;
				}

				if ($(this).hasClass('VAL_RESGATE')) {
					TOTAL_VAL_RESGATE = total;
				}

				var totalVar = $('#' + $(this).attr('id'));
				totalVar.unmask();
				totalVar.text(total.toFixed(2));
				totalVar.mask("#.##0,00", {
					reverse: true
				});
			} else {

				if (TOTAL_VAL_VINCULADO == 0 && TOTAL_VAL_RESGATE == 0) {
					var resultado = -100;
				} else {
					var resultado = ((TOTAL_VAL_VINCULADO / TOTAL_VAL_RESGATE) - 1) * 100;
				}

				var totalVar = $('#' + $(this).attr('id'));
				totalVar.unmask();
				totalVar.text(resultado.toFixed(2));
				//totalVar.mask("#.##0,00", {reverse: true});	

				TOTAL_VAL_VINCULADO = 0;
				TOTAL_VAL_RESGATE = 0;
			}
		});

		$("div[id^='total_col1']").each(function() {
			$(this).text($(this).text().slice(0, -3));
		});

		$("div[id^='total_col2']").each(function() {
			$(this).text($(this).text().slice(0, -3));
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
										url: "relatorios/ajxRelVendasVinculadas.do?opcao=exportar&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>&LOJAS=<?php echo $lojasSelecionadas; ?>",
										data: $('#formulario').serialize(),
										method: 'POST'
									}).done(function(response) {
										self.setContentAppend('<div>Exportação realizada com sucesso.</div>');
										var fileName = '<?php echo $cod_empresa; ?>_' + nome + '.csv';
										SaveToDisk('media/excel/' + fileName, fileName);
										//aqui escrevo no console o retorno
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


	function geraRelDesafio(tipoRel, link) {
		$.ajax({
			type: "POST",
			url: "relatorios/ajxRelDesafio.do?id=<?php echo fnEncode($cod_empresa); ?>&opcao=" + tipoRel,
			data: $('#formulario').serialize(),
			beforeSend: function() {
				$('#relatorioConteudo').html('<div class="loading" style="width: 100%;"></div>');
				$('.linksNav a').removeClass('activeRel');
				$(link).addClass('activeRel');
			},
			success: function(data) {
				$("#relatorioConteudo").html(data);
				console.log(data);
			},
			error: function(data) {
				$('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
				console.log(data);
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