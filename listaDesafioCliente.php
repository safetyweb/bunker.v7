<?php

if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}

// definir o numero de itens por pagina
$itens_por_pagina = 50;
$pagina  = "1";
$mod = fnDecode($_GET['mod']);
$cod_usuario = 0;
$tip_lista = 0;
$dat_ini = '';
$dat_fim = '';
$dias30 = '';
$hoje = '';

$hashLocal = mt_rand();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_empresa = fnLimpaCampoZero(@$_REQUEST['COD_EMPRESA']);
		$cod_usuario = 	fnLimpaCampoZero(fnDecode(@$_REQUEST['COD_USUARIO']));
		$cod_vendedor = fnLimpaCampoZero(@$_REQUEST['COD_USUARIO_2']);
		$cod_univend = fnLimpaCampoZero(@$_REQUEST['COD_UNIVEND']);
		$tip_lista = fnLimpaCampoZero(@$_REQUEST['TIP_LISTA']);

		// fnEscreve($cod_vendedor);


		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		if ($opcao != '') {
		}
	}
}

if ($mod == 1491) {
	if (isset($_GET['idU'])) {
		// fnEscreve("idu setado");
		$cod_vendedor = fnDecode($_GET['idU']);
		$cod_usuario = 0;
	} else {
		$cod_vendedor = $_SESSION['SYS_COD_USUARIO'];
	}

	// echo($cod_vendedor);

	$sql = "SELECT NOM_USUARIO FROM USUARIOS WHERE COD_USUARIO = $cod_vendedor";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrUsuario = mysqli_fetch_assoc($arrayQuery);
	// fnEscreve($qrUsuario['NOM_USUARIO']);

	$tamCol = "col-md-12";
} else {
	// fnEscreve($mod);
	$tamCol = "col-md-9";
}

if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode($_GET['id']);
	$cod_desafio = fnDecode($_GET['idD']);
	//fnEscreve($cod_desafio);

	$sql = "SELECT NOM_FANTASI,
		(select NOM_DESAFIO from desafio where cod_desafio = $cod_desafio) as NOM_DESAFIO,
		(select VAL_METADES from desafio where cod_desafio = $cod_desafio) as VAL_METADES
		FROM " . $connAdm->DB . ".empresas where COD_EMPRESA = '" . $cod_empresa . "' 		
		";
	//fnEscreve($sql);

	$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
	$nom_desafio = $qrBuscaEmpresa['NOM_DESAFIO'];
	$val_metades = $qrBuscaEmpresa['VAL_METADES'];
}

$sql = "SELECT COD_UNIVEND FROM DESAFIO
			WHERE COD_EMPRESA = $cod_empresa 
			AND COD_DESAFIO = $cod_desafio";

$arrayUnidade = mysqli_query(connTemp($cod_empresa, ''), $sql);

$cod_unidades = "";

while ($qrUni = mysqli_fetch_assoc($arrayUnidade)) {
	$cod_unidades = $cod_unidades . $qrUni['COD_UNIVEND'] . ",";
}

$sql = "SELECT DISTINCT COD_VENDEDOR, 
							COD_RESPONSAVEL 
			FROM DESAFIO_CONTROLE
			WHERE COD_EMPRESA = $cod_empresa 
			AND COD_DESAFIO = $cod_desafio";

$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
$cod_responsaveis = "";
$cod_vendedores = "";

while ($qrDesafio = mysqli_fetch_assoc($arrayQuery)) {
	if ($qrDesafio['COD_RESPONSAVEL'] != '') {
		$cod_responsaveis = $cod_responsaveis . $qrDesafio['COD_RESPONSAVEL'] . ",";
	}
	if ($qrDesafio['COD_VENDEDOR'] != '') {
		$cod_vendedores = $cod_vendedores . $qrDesafio['COD_VENDEDOR'] . ",";
	}
}

$cod_unidades = ltrim(rtrim($cod_unidades, ','), ',');;
$cod_responsaveis = ltrim(rtrim($cod_responsaveis, ','), ',');;
$cod_vendedores = ltrim(rtrim($cod_vendedores, ','), ',');;

if ($cod_unidades == "") {
	$cod_unidades = 0;
}
if ($cod_responsaveis == "") {
	$cod_responsaveis = 0;
}
if ($cod_vendedores == "") {
	$cod_vendedores = 0;
}

if ($cod_unidades == 9999) {
	$andUnidadesCombo = "";
} else {
	$andUnidadesCombo = "AND COD_UNIVEND IN($cod_unidades)";
}

// //busca revendas do usuário
// include "unidadesAutorizadas.php"; 

//inicialização das variáveis - default	
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
	$dat_ini = fnDataSql($dias30);
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
	$dat_fim = fnDataSql($hoje);
}

//fnEscreve($mod); 	
//fnEscreve($val_metades); 	
//fnMostraForm();

// fnEscreve($cod_usuario);

?>

<script src="js/pie-chart.js"></script>


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

	table a:not(.btn),
	.table a:not(.btn) {
		text-decoration: none;
	}

	table a:not(.btn):hover,
	.table a:not(.btn):hover {
		text-decoration: underline;
	}

	.modal-dialog,
	.modal-content {
		width: 98vw;
		height: 97vh;
	}

	.modal-dialog {
		position: absolute;
		left: 0.5%;
	}

	.no-side-padding {
		padding-left: 0;
		padding-right: 0;
	}
</style>

<div class="push30"></div>

<div class="row">

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fal fa-terminal"></i>
					<span class="text-primary"><?php echo $NomePg; ?>: <?php echo $nom_desafio; ?> / <?php echo $nom_empresa; ?> </span>
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

						<div class="push20"></div>

						<?php

						if ($tip_lista != 0) {
							$andLista = "AND (SELECT FC3.DAT_AGENDAME FROM FOLLOW_CLIENTE FC3 WHERE FC3.COD_EMPRESA = $cod_empresa 
																 AND FC3.COD_CLIENTE = B.cod_cliente AND FC3.COD_DESAFIO = $cod_desafio 
																 AND COD_FOLLOW = (SELECT MAX(COD_FOLLOW) 
																 FROM FOLLOW_CLIENTE 
																 WHERE COD_CLIENTE = B.COD_CLIENTE 
																 AND COD_DESAFIO = $cod_desafio)) != ''
													";
						} else {
							$andLista = "";
						}

						if ($cod_usuario != 0) {
							$andResponsavel = "AND A.COD_RESPONSAVEL = $cod_usuario";
							$andResponsavelD = "AND D.COD_RESPONSAVEL = $cod_usuario";
							$andResponsavelE = "AND E.COD_RESPONSAVEL = $cod_usuario";
							$andResponsavelG = "AND G.COD_RESPONSAVEL = $cod_usuario";
						} else {
							$andResponsavel = "";
							$andResponsavelD = "";
							$andResponsavelE = "";
							$andResponsavelG = "";
						}

						if ($cod_vendedor != 0) {
							$andVendedor = "AND A.COD_VENDEDOR = $cod_vendedor";
							$andVendedorD = "AND D.COD_VENDEDOR = $cod_vendedor";
							$andVendedorE = "AND E.COD_VENDEDOR = $cod_vendedor";
							$andVendedorG = "AND G.COD_VENDEDOR = $cod_vendedor";
							$whereUsucada = "WHERE COD_USUCADA = $cod_vendedor";
						} else {
							$andVendedor = "";
							$andVendedorD = "";
							$andVendedorE = "";
							$andVendedorG = "";
							$andUsucada = "";
						}

						// fnEscreve($andVendedor);

						//totais
						$sql2 = "SELECT count(1) as hitsDesafio from DESAFIO_CONTROLE A 
														INNER JOIN CLIENTES B 
															           ON A.COD_CLIENTE = B.COD_CLIENTE 
															              AND A.COD_EMPRESA = B.COD_EMPRESA
														where A.COD_DESAFIO = $cod_desafio 
														$andResponsavel
														$andVendedor
														$andLista
														";
						//fnEscreve($sql2);	
						$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql2);
						$qrTotalDesafio = mysqli_fetch_assoc($arrayQuery);
						$total_desafio = $qrTotalDesafio['hitsDesafio'];

						//totais
						$sql2 = "SELECT count(COD_CONTROLE) as hitsFeitos from DESAFIO_CONTROLE A
														INNER JOIN CLIENTES B 
															           ON A.COD_CLIENTE = B.COD_CLIENTE 
															              AND A.COD_EMPRESA = B.COD_EMPRESA
														 WHERE COD_DESAFIO = $cod_desafio
														 AND LOG_CONCLUIDO = 'S'
														 $andResponsavel
														 $andVendedor
														 $andLista
														";
						//fnEscreve($sql2);	
						$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql2);
						$qrTotalFeitos = mysqli_fetch_assoc($arrayQuery);
						$totalFeitos = $qrTotalFeitos['hitsFeitos'];

						?>

						<div class="row">

							<?php
							if ($mod != 1491) {
							?>

								<div class="col-md-3">

									<div class="form-group">
										<label for="inputName" class="control-label">Unidade de Atendimento</label>
										<select data-placeholder="Selecione a unidade de atendimento" name="COD_UNIVEND" id="COD_UNIVEND" class="chosen-select-deselect requiredChk" required>
											<option value=""></option>
											<?php
											//mostra todas

											$sql = "SELECT COD_UNIVEND, NOM_FANTASI from UNIDADEVENDA 
																	WHERE COD_EMPRESA = $cod_empresa 
																	$andUnidadesCombo 
																	AND DAT_EXCLUSA IS NULL 
																	ORDER BY trim(NOM_FANTASI) ";
											$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
											//fnEscreve($sql);

											while ($qrListaUnidades = mysqli_fetch_assoc($arrayQuery)) {

												echo "
																	  <option value='" . $qrListaUnidades['COD_UNIVEND'] . "'>" . $qrListaUnidades['NOM_FANTASI'] . "</option> 
																	";
											}
											?>
										</select>
										<?php // fnEscreve($sql); 
										?>
										<div class="help-block with-errors"></div>
									</div>

									<div class="push10"></div>

									<div class="form-group">
										<label for="inputName" class="control-label">Responsável do Desafio</label>
										<div id="divId_usu">
											<select data-placeholder="Selecione um usuário" name="COD_USUARIO" id="COD_USUARIO" class="chosen-select-deselect">
												<option value=""></option>
												<?php
												//mostra todas

												$sql = "SELECT COD_USUARIO, NOM_USUARIO FROM USUARIOS WHERE COD_EMPRESA = $cod_empresa AND COD_USUARIO IN($cod_responsaveis) AND DAT_EXCLUSA IS NULL order by trim(NOM_USUARIO) ";
												$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
												//fnEscreve($sql);

												while ($qrListaResponsaveis = mysqli_fetch_assoc($arrayQuery)) {

													echo "
																			  <option value='" . $qrListaResponsaveis['COD_USUARIO'] . "'>" . $qrListaResponsaveis['NOM_USUARIO'] . "</option> 
																			";
												}
												?>
											</select>
										</div>
										<div class="help-block with-errors"></div>
									</div>

									<div class="push10"></div>

									<div class="form-group">
										<label for="inputName" class="control-label">Vendedor</label>
										<div id="divId_usu">
											<select data-placeholder="Selecione um vendedor" name="COD_USUARIO_2" id="COD_USUARIO_2" class="chosen-select-deselect">
												<option value=""></option>
												<?php
												//mostra todas

												$sql = "SELECT COD_USUARIO, NOM_USUARIO FROM USUARIOS WHERE COD_EMPRESA = $cod_empresa AND COD_USUARIO IN($cod_vendedores) AND DAT_EXCLUSA IS NULL order by trim(NOM_USUARIO) ";
												$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
												//fnEscreve($sql);

												while ($qrListaVendedores = mysqli_fetch_assoc($arrayQuery)) {

													echo "
																			  <option value='" . $qrListaVendedores['COD_USUARIO'] . "'>" . $qrListaVendedores['NOM_USUARIO'] . "</option> 
																			";
												}
												?>
											</select>
										</div>
										<div class="help-block with-errors"></div>
									</div>

									<div class="push10"></div>

									<div class="form-group">
										<label for="inputName" class="control-label">Tipo da lista</label>
										<div id="divId_usu">
											<select data-placeholder="Selecione um tipo" name="TIP_LISTA" id="TIP_LISTA" class="chosen-select-deselect">
												<option value="0">Geral</option>
												<option value="1">Agendada</option>
											</select>
										</div>
										<div class="help-block with-errors"></div>
									</div>

									<div class="push20"></div>
									<button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-sm btn-block getBtn"><i class="fal fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>

								</div>

							<?php } else {
							?>

								<div class="col-md-12">
									<h3>&nbsp; <?= $qrUsuario['NOM_USUARIO'] ?></h3>
								</div>
								<div class="push10"></div>

							<?php
							}
							?>


							<?php

							$sql = "SELECT  
															Z.VAL_METADES,
															COUNT(A.COD_CLIENTE),
															Z.DAT_INI,
															Z.DAT_FIM,

															IFNULL((SELECT COUNT(DISTINCT C.COD_CLIENTE) FROM VENDAS C,DESAFIO_CONTROLE D 
															  WHERE C.COD_CLIENTE=D.COD_CLIENTE AND
															        D.COD_DESAFIO=A.COD_DESAFIO AND 
																	   DATE_FORMAT(C.DAT_CADASTR_WS, '%Y-%m-%d') >= Z.DAT_INI AND 
																		DATE_FORMAT(C.DAT_CADASTR_WS, '%Y-%m-%d')<= Z.DAT_FIM 
																		AND C.COD_STATUSCRED != 6
																		$andResponsavelD
														 				$andVendedorD
																		),0) QTD_CLIENTE,

															IFNULL((SELECT SUM(VAL_TOTVENDA) FROM VENDAS C,DESAFIO_CONTROLE D 
															  WHERE C.COD_CLIENTE=D.COD_CLIENTE AND
															        D.COD_DESAFIO=A.COD_DESAFIO AND  
																	   DATE_FORMAT(C.DAT_CADASTR_WS, '%Y-%m-%d')>= Z.DAT_INI AND 
																		DATE_FORMAT(C.DAT_CADASTR_WS, '%Y-%m-%d')<= Z.DAT_FIM 
																		AND C.COD_STATUSCRED != 6
																		$andResponsavelD
														 				$andVendedorD
																		),0) VAL_TOTVENDA,
															
															IFNULL((SELECT SUM(VAL_CREDITO) FROM CREDITOSDEBITOS D,DESAFIO_CONTROLE E 
															       WHERE D.COD_CLIENTE=E.COD_CLIENTE AND
															       E.COD_DESAFIO=A.COD_DESAFIO AND 
															  		 D.TIP_CREDITO='D' AND
															   		DATE_FORMAT(D.DAT_REPROCE, '%Y-%m-%d')>= Z.DAT_INI AND 
																		DATE_FORMAT(D.DAT_REPROCE, '%Y-%m-%d')<= Z.DAT_FIM 
																		AND D.COD_STATUSCRED != 6
																		$andResponsavelE
														 				$andVendedorE
																		),0) VAL_RESGATE,
															
															IFNULL((SELECT SUM(VAL_TOTVENDA) FROM VENDAS E ,CREDITOSDEBITOS F, DESAFIO_CONTROLE G  
															    WHERE 
																 E.COD_VENDA=F.COD_VENDA AND 
																 F.COD_CLIENTE=G.COD_CLIENTE AND
																 G.COD_DESAFIO=A.COD_DESAFIO AND 
															    F.TIP_CREDITO='D' AND
															   DATE_FORMAT(F.DAT_REPROCE, '%Y-%m-%d')>= Z.DAT_INI AND DATE_FORMAT(F.DAT_REPROCE, '%Y-%m-%d')<= Z.DAT_FIM 
															   AND E.COD_STATUSCRED != 6
															   AND F.COD_STATUSCRED != 6
															    $andResponsavelG
														 		$andVendedorG
														 		),0) VAL_VENDAS_VINCULADAS 
															  
															   
															FROM DESAFIO_CONTROLE A
															INNER JOIN CLIENTES B 
															           ON A.COD_CLIENTE = B.COD_CLIENTE 
															              AND A.COD_EMPRESA = B.COD_EMPRESA
															INNER JOIN DESAFIO Z ON A.COD_DESAFIO = Z.COD_DESAFIO
															WHERE A.COD_DESAFIO = $cod_desafio 
															AND A.COD_EMPRESA = $cod_empresa
															$andResponsavel
															$andVendedor
															$andLista";

							//echo($sql);

							$qrMeta = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa, ''), $sql));

							$objetivoDesafio = ($val_metades / 100) * $total_desafio;
							$clientesFaltam  = $objetivoDesafio - $totalFeitos;
							$totalProjetado = $qrMeta['QTD_CLIENTE'] != 0 ? ($qrMeta['VAL_TOTVENDA'] * $objetivoDesafio) / $qrMeta['QTD_CLIENTE'] : 0;
							$resgateProjetado = $qrMeta['QTD_CLIENTE'] != 0 ? ($qrMeta['VAL_RESGATE'] * $objetivoDesafio) / $qrMeta['QTD_CLIENTE'] : 0;
							$vvrProjetado = $qrMeta['QTD_CLIENTE'] != 0 ? ($qrMeta['VAL_VENDAS_VINCULADAS'] * $objetivoDesafio) / $qrMeta['QTD_CLIENTE'] : 0;
							//fnEscreve($qrMeta['VAL_TOTVENDA']);
							//fnEscreve($objetivoDesafio);
							//fnEscreve($qrMeta['QTD_CLIENTE']);

							$sql = "SELECT DF.* FROM DESAFIO DF 
													LEFT JOIN DESAFIO_CONTROLE A ON A.COD_DESAFIO = DF.COD_DESAFIO
													INNER JOIN CLIENTES B 
													           ON A.COD_CLIENTE = B.COD_CLIENTE 
													              AND A.COD_EMPRESA = B.COD_EMPRESA
													WHERE DF.COD_DESAFIO = $cod_desafio AND DF.COD_EMPRESA = $cod_empresa
													$andResponsavel
													$andVendedor
													$andLista";

							$qrMeta2 = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa, ''), $sql));

							?>

							<div class="<?= $tamCol ?>">

								<div class="row">

									<div class="col-md-4 top-content">

										<div class="col-md-12" style="height: 70px;">
											<p>Lista de Clientes</p>
											<label><?php echo fnValor($total_desafio, 0); ?></label>
										</div>
									</div>

									<div class="col-md-4 top-content">

										<div class="col-md-5 top-content">
											<p>Objetivo</p>
											<label><?php echo fnValor($objetivoDesafio, 0); ?></label>
										</div>

										<div class="col-md-5">
											<div id="main-pie" class="pie-title-center" data-percent="<?php echo fnValor($val_metades, 0); ?>">
												<span class="pie-value">100%</span>
											</div>
										</div>
									</div>

									<div class="col-md-4 top-content">

										<div class="col-md-5 top-content">
											<p>Contatos</p>
											<label><?php echo fnValor($totalFeitos, 0); ?></label>
										</div>

										<div class="col-md-5">
											<div id="main-pie2" class="pie-title-center" data-percent="<?php echo fnValor((($totalFeitos * 100) / $objetivoDesafio), 0); ?>">
												<span class="pie-value">100%</span>
											</div>
										</div>
									</div>

								</div>

								<div class="push10"></div>

								<div class="col-md-4 top-content">

									<div class="push10"></div>

									<div class="col-md-12 top-content	" style="background: #F4F6F6; border-radius: 5px;">

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
										<div class="col-md-5"><span><small><?= fnValor($objetivoDesafio, 0) ?><small> / <?= fnValor($qrMeta2['VAL_METADES'], 2) ?>%</small></small></span></div>

										<div class="push5"></div>

										<div class="col-md-7 no-side-padding">
											<p><span class='f13'>Faltam:</span></p>
										</div>
										<div class="col-md-5"><span><small><?= fnValor($clientesFaltam, 0) ?></small></span></div>

										<div class="push5"></div>

									</div>

								</div>

								<div class="col-md-4 top-content">

									<div class="push10"></div>

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

								<div class="col-md-4 top-content">

									<div class="push10"></div>

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

								<div class="push20"></div>


								<!-- <input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>"> -->



							</div>

						</div>

						<input type="hidden" name="REFRESH_CLIENTE" id="REFRESH_CLIENTE" value="N">
						<input type="hidden" name="ID_CARTAO" id="ID_CARTAO" value="0">
						<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?= $cod_empresa ?>">
						<input type="hidden" name="andResponsavel" id="andResponsavel" value="<?= $andResponsavel ?>">
						<input type="hidden" name="andVendedor" id="andVendedor" value="<?= $andVendedor ?>">
						<input type="hidden" name="andLista" id="andLista" value="<?= $andLista ?>">
						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

					</form>

				</div>
			</div>
		</div>

		<div class="push20"></div>

		<div class="portlet portlet-bordered">

			<div class="portlet-body">

				<div class="login-form">

					<!-- <a href="https://api.whatsapp.com/send?phone=+55015998438585&text=Teste%20eh%20com%20vc" target="_blank">Whats</a>
											<div class="push50"></div> -->

					<div class="row">
						<div class="col-lg-12">

							<div class="no-more-tables">

								<?php

								$sql = "SELECT COUNT(1) AS TEM_CATEGOR FROM CATEGORIA_CLIENTE WHERE COD_EMPRESA = $cod_empresa";
								$qrCat = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa, ''), $sql));
								// fnEscreve($qrCat['TEM_CATEGOR']);

								if ($qrCat['TEM_CATEGOR'] > 0) {
									$categoriaTh = "<th>Categoria</th>";
								} else {
									$categoriaTh = "";
								}

								?>

								<table class="table table-bordered table-striped table-hover tablesorter" id="tablista">
									<thead>
										<tr>
											<th>Nome</th>
											<th>Cartão</th>
											<?= $categoriaTh ?>
											<th>Contatos</th>
											<th class="text-right">Crédito</th>
											<th>Aniv.</th>
											<th>Responsável do Desafio</th>
											<th>Último Vendedor</th>
											<th>Resgate</th>
											<th>Follow Up</th>
											<th>Resultado</th>
											<th>Agendam.</th>
											<th class="{sorter:false}"></th>
										</tr>
									</thead>

									<tbody id="relatorioConteudo">

										<?php

										$ARRAY_VENDEDOR1 = array(
											'sql' => "select COD_USUARIO,NOM_USUARIO from usuarios where cod_empresa=$cod_empresa",
											'cod_empresa' => $cod_empresa,
											'conntadm' => $connAdm->connAdm(),
											'IN' => 'N',
											'nomecampo' => '',
											'conntemp' => '',
											'SQLIN' => ""
										);
										$ARRAY_VENDEDOR = fnUniVENDEDOR($ARRAY_VENDEDOR1);

										$sql = "SELECT COUNT(*) as CONTADOR FROM DESAFIO_CONTROLE A
																			INNER JOIN CLIENTES B 
																				           ON A.COD_CLIENTE = B.COD_CLIENTE 
																				              AND A.COD_EMPRESA = B.COD_EMPRESA
																			WHERE
																			A.COD_DESAFIO = $cod_desafio AND 
																			A.COD_EMPRESA = $cod_empresa 
																			$andResponsavel
																			$andVendedor
																			$andLista";
										//fnEscreve($sql);

										$retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
										$total_itens_por_pagina = mysqli_fetch_assoc($retorno);

										$numPaginas = ceil($total_itens_por_pagina['CONTADOR'] / $itens_por_pagina);

										//variavel para calcular o início da visualização com base na página atual
										$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

										$sql = "SELECT B.COD_CLIENTE, 
																				   B.NUM_CARTAO, 
																				   B.NUM_CGCECPF, 
																				   B.NOM_CLIENTE, 
																				   B.DES_EMAILUS, 
																				   B.NUM_TELEFON, 
																				   B.NUM_CELULAR, 
																				   B.DAT_CADASTR, 
																				   B.DAT_NASCIME, 
																				   B.COD_SEXOPES,
																				   US1.NOM_USUARIO AS NOM_RESPONSAVEL,
																				   C.NOM_FAIXACAT,
																				   US2.NOM_USUARIO AS NOM_VENDEDOR,
																				   A.LOG_CONCLUIDO,
																				   (SELECT FC.DES_COMENT FROM FOLLOW_CLIENTE FC WHERE FC.COD_EMPRESA = $cod_empresa AND FC.COD_CLIENTE = B.cod_cliente AND FC.COD_DESAFIO = $cod_desafio AND COD_FOLLOW = (SELECT MAX(COD_FOLLOW) FROM FOLLOW_CLIENTE WHERE COD_CLIENTE = B.COD_CLIENTE AND COD_DESAFIO = $cod_desafio)) AS DES_COMENT,
																				   (SELECT FC2.DAT_CADASTR FROM FOLLOW_CLIENTE FC2 WHERE FC2.COD_EMPRESA = $cod_empresa AND FC2.COD_CLIENTE = B.cod_cliente AND FC2.COD_DESAFIO = $cod_desafio AND COD_FOLLOW = (SELECT MAX(COD_FOLLOW) FROM FOLLOW_CLIENTE WHERE COD_CLIENTE = B.COD_CLIENTE AND COD_DESAFIO = $cod_desafio)) AS DAT_CADASTR,
																				   (SELECT DES_CLASSIFICA FROM CLASSIFICA_ATENDIMENTO WHERE COD_CLASSIFICA = (SELECT COD_CLASSIFICA FROM FOLLOW_CLIENTE WHERE COD_CLIENTE = B.COD_CLIENTE AND COD_EMPRESA = $cod_empresa AND COD_FOLLOW = (SELECT MAX(COD_FOLLOW) FROM FOLLOW_CLIENTE WHERE COD_CLIENTE = B.COD_CLIENTE AND COD_EMPRESA = $cod_empresa))) AS DES_CLASSIFICA,
																				   (SELECT FC3.DAT_AGENDAME FROM FOLLOW_CLIENTE FC3 WHERE FC3.COD_EMPRESA = $cod_empresa AND FC3.COD_CLIENTE = B.cod_cliente AND FC3.COD_DESAFIO = $cod_desafio AND COD_FOLLOW = (SELECT MAX(COD_FOLLOW) FROM FOLLOW_CLIENTE WHERE COD_CLIENTE = B.COD_CLIENTE AND COD_DESAFIO = $cod_desafio)) AS DAT_AGENDAME
																				FROM DESAFIO_CONTROLE A 
																				   INNER JOIN CLIENTES B 
																				           ON A.COD_CLIENTE = B.COD_CLIENTE 
																				              AND A.COD_EMPRESA = B.COD_EMPRESA
																				   LEFT JOIN CATEGORIA_CLIENTE C ON C.COD_CATEGORIA = B.COD_CATEGORIA 
																				   LEFT JOIN USUARIOS US1 ON US1.COD_USUARIO = A.COD_RESPONSAVEL 
																				   LEFT JOIN USUARIOS US2 ON US2.COD_USUARIO = A.COD_VENDEDOR 
																				WHERE  B.LOG_AVULSO = 'N' 
																				   AND A.COD_DESAFIO = $cod_desafio 
																				   AND A.COD_EMPRESA = $cod_empresa
																				   $andResponsavel
																				   $andVendedor
																				   $andLista
																				ORDER BY B.NOM_CLIENTE 
																				LIMIT $inicio, $itens_por_pagina 
																				";

										//(SELECT MAX(cod_venda) FROM vendas v WHERE A.COD_CLIENTE=V.COD_CLIENTE AND v.cod_empresa=A.COD_EMPRESA ) AS max_venda
										//fnEscreve($sql);
										//echo($sql);

										$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

										$count = 0;
										while ($qrListaDesafio = mysqli_fetch_assoc($arrayQuery)) {
											$count++;

											$responsavel = "";

											// fnEscreve($qrListaDesafio['COD_RESPONSAVEL']);

											// $NOM_ARRAY_NON_VENDEDOR=(array_search($qrListaDesafio['COD_VENDEDOR'], array_column($ARRAY_VENDEDOR, 'COD_USUARIO')));
											// $NOM_ARRAY_NON_RESPONSAVEL=(array_search($qrListaDesafio['COD_RESPONSAVEL'], array_column($ARRAY_VENDEDOR, 'COD_USUARIO')));

											if ($qrListaDesafio['COD_RESPONSAVEL'] != 0) {
												$responsavel = $ARRAY_VENDEDOR[$NOM_ARRAY_NON_RESPONSAVEL]['NOM_USUARIO'];
											}

											if ($qrListaDesafio['COD_SEXOPES'] == 1) {
												$mostraSexo = '<i class="fa fa-male" aria-hidden="true"></i>';
											} else {
												$mostraSexo = '<i class="fa fa-female" aria-hidden="true"></i>';
											}

											if ($qrListaDesafio['DES_EMAILUS'] != "") {
												$mostraMail = '<i class="fal fa-envelope-open" aria-hidden="true"></i> ' . $qrListaDesafio['DES_EMAILUS'] . ' <br/>';
											} else {
												$mostraMail = '';
											}

											if ($qrListaDesafio['NUM_CELULAR'] != "") {
												$mostraCel = '<i class="fal fa-mobile" aria-hidden="true"></i> ' . $qrListaDesafio['NUM_CELULAR'] . ' <br/>';
											} else {
												$mostraCel = '';
											}

											if ($qrListaDesafio['NUM_TELEFON'] != "") {
												$mostraFone = '<i class="fal fa-phone" aria-hidden="true"></i> ' . $qrListaDesafio['NUM_TELEFON'] . ' <br/>';
											} else {
												$mostraFone = '';
											}

											if ($qrListaDesafio['LOG_CONCLUIDO'] == "S") {
												$corBotao = "btn-success";
											} else {
												$corBotao = "btn-default";
											}

											if ($qrCat['TEM_CATEGOR'] > 0) {
												$categoria = "<td class='text-center'><small>" . $qrListaDesafio['NOM_FAIXACAT'] . "</small></td>";
											} else {
												$categoria = "";
											}

											if ($qrListaDesafio['DAT_AGENDAME'] != "" && $qrListaDesafio['DAT_AGENDAME'] < Date("Y-m-d")) {
												$corData = "text-danger";
											} else {
												$corData = "";
											}

											//busca dados do cliente
											$sqlCred = "CALL total_wallet('$qrListaDesafio[COD_CLIENTE]', '$cod_empresa')";

											//fnEscreve($sql);

											$arrayCred = mysqli_query(connTemp($cod_empresa, ''), $sqlCred);
											$qrBuscaTotais = mysqli_fetch_assoc($arrayCred);


											if (isset($arrayCred)) {

												$credito_disponivel = $qrBuscaTotais['CREDITO_DISPONIVEL'];
											} else {

												$credito_disponivel = 0;
											}

											if ($log_estatus == "N") {
												$credito_disponivel = 0;
											}

											// if($qrListaDesafio['DAT_AGENDAME'] < Date()){}

											echo "
																			<tr id='" . $qrListaDesafio['NUM_CARTAO'] . "'>
																			  <td><small><a href='action.do?mod=" . fnEncode(1024) . "&id=" . fnEncode($cod_empresa) . "&idC=" . fnEncode($qrListaDesafio['COD_CLIENTE']) . "' target='_blank'>" . $mostraSexo . " &nbsp; " . $qrListaDesafio['NOM_CLIENTE'] . "</a></td>
																			  <td><small>" . $qrListaDesafio['NUM_CARTAO'] . "</small></td>
																			  " . $categoria . "
																			  <td><small>" . $mostraMail . " " . $mostraCel . " " . $mostraFone . "</small></td>
																			  <td class='text-right'><small>" . fnValor($credito_disponivel, 2) . "</small></td>																		  
																			  <td><small>" . substr($qrListaDesafio['DAT_NASCIME'], 0, 5) . "</small></td>
																			  <td><small>" . $qrListaDesafio['NOM_RESPONSAVEL'] . "</small></td>																		  
																			  <td><small>" . $qrListaDesafio['NOM_VENDEDOR'] . "</small></td>
																			  <td><small></small></td>																			  
																			  <td><small>" . fnDataShort($qrListaDesafio['DAT_CADASTR']) . "<br>" . $qrListaDesafio['DES_COMENT'] . "</small></td>																			  
																			  <td><small>" . $qrListaDesafio['DES_CLASSIFICA'] . "</small></td>																		  
																			  <td class='text-center $corData'><small>" . fnDataShort($qrListaDesafio['DAT_AGENDAME']) . "</small></td>																		  
																			  <td class='text-center'>
																				<a class='btn btn-xs " . $corBotao . " addBox' data-url='action.php?mod=" . fnEncode(1377) . "&id=" . fnEncode($cod_empresa) . "&idD=" . fnEncode($cod_desafio) . "&idC=" . fnEncode($qrListaDesafio['COD_CLIENTE']) . "&pop=true' data-title='Desafio / " . $des_desafio . " '>&nbsp; <i class='fas fa-user-tag'></i> &nbsp;</a>
																			  </td>
																			</tr>
																			";
											//<td><small>".$qrListaDesafio['NOM_UNIVEND']."</small></td>
										}

										?>

									</tbody>

									<tfoot>
										<tr>
											<th class="" colspan="100">
												<center>
													<ul id="paginacao" class="pagination-sm"></ul>
												</center>
											</th>
										</tr>
										<tr>
											<th colspan="100">
												<a class="btn btn-info btn-sm exportarCSV"><i class="fal fa-file-excel" aria-hidden="true"></i> &nbsp; Exportar </a>
												<!-- <a class="btn btn-info btn-sm exportarCSV" onclick="exportarCSV(this)" value="S"><i class="fa fa-file-excel" aria-hidden="true"></i> &nbsp; Exportar Detalhes</a> &nbsp;&nbsp; -->
											</th>
										</tr>
									</tfoot>

								</table>

								<div class="push"></div>
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

<script type="text/javascript">
	$(document).ready(function() {


		$('#main-pie').pieChart({
			barColor: '#2c3e50',
			trackColor: '#eee',
			lineCap: 'round',
			lineWidth: 8,
			onStep: function(from, to, percent) {
				$(this.element).find('.pie-value').text(Math.round(percent) + '%');
			}
		});

		$('#main-pie2').pieChart({
			barColor: '#EF5350',
			trackColor: '#eee',
			lineCap: 'round',
			lineWidth: 8,
			onStep: function(from, to, percent) {
				$(this.element).find('.pie-value').text(Math.round(percent) + '%');
			}
		});


		var numPaginas = <?php echo $numPaginas; ?>;
		if (numPaginas != 0) {
			carregarPaginacao(numPaginas);
		}

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

		//modal close
		$('.modal').on('hidden.bs.modal', function() {
			//console.log('entrou');
			if ($('#REFRESH_CLIENTE').val() == "S") {
				//alert("atualiza");
				refreshCliente(<?php echo $cod_empresa; ?>, $('#ID_CARTAO').val());
				$('#REFRESH_CLIENTE').val("N");
			}
		});

		$("#COD_UNIVEND").val("<?= $cod_univend ?>").trigger("chosen:updated");
		//alert("<?= $cod_univend ?>");

		if ("<?= $cod_univend ?>" != "") {
			buscaUsuario($("#COD_UNIVEND").val(), "<?= $cod_empresa ?>");
		}

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
										url: "ajxListaDesafioCliente.do?opcao=exportar&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>&codDesafio=<?php echo fnEncode($cod_desafio); ?>",
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

	$(document).on('change', '#COD_EMPRESA', function() {
		$("#dKey").val($("#COD_EMPRESA").val());
	});


	// ajax
	$("#COD_UNIVEND").change(function() {
		var codBusca = $("#COD_UNIVEND").val();
		var codBusca2 = $("#COD_EMPRESA").val();
		//alert(codBusca);
		//alert(codBusca2);
		buscaUsuario(codBusca, codBusca2);
	});

	function buscaUsuario(idUnidade, idEmp) {
		$.ajax({
			type: "GET",
			url: "ajxBuscaUsuarioChave.php",
			data: {
				ajx1: idUnidade,
				ajx2: idEmp
			},
			beforeSend: function() {
				$('#divId_usu').html('<div class="loading" style="width: 100%;"></div>');
			},
			success: function(data) {
				$("#divId_usu").html(data);
				// $("#COD_USUARIO").chosen({allow_single_deselect:true});
				$("#COD_USUARIO").val("<?= fnEncode($cod_usuario) ?>").trigger("chosen:updated");
				$("#COD_USUARIO_2").val("<?= $cod_vendedor ?>").trigger("chosen:updated");
				$("#TIP_LISTA").val("<?= $tip_lista ?>").trigger("chosen:updated");
				$('#formulario').validator('validate');
			},
			error: function() {
				$('#divId_usu').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
			}
		});
	}

	// function page(index){
	// 	$("#pagina").val(index);
	// 	$( "#formulario" )[0].submit();   			
	// 	//alert(index);	
	// }

	function refreshCliente(cod_empresa, cartao) {
		$.ajax({
			method: 'POST',
			url: 'ajxRefreshClienteDesafio.php',
			data: {
				COD_EMPRESA: cod_empresa,
				NUM_CARTAO: cartao,
				COD_DESAFIO: <?= $cod_desafio ?>
			},
			success: function(data) {
				$('#' + cartao).html(data);
				$('#' + cartao).css('background', '#FCF3CF');
				console.log(data);
			}
		});
	}


	function reloadPage(idPage) {
		// alert($("#COD_USUARIO").val());
		$.ajax({
			type: "POST",
			url: "ajxListaDesafioCliente.do?opcao=paginar&id=<?php echo fnEncode($cod_empresa); ?>&codDesafio=<?php echo fnEncode($cod_desafio); ?>&itens_por_pagina=<?php echo $itens_por_pagina; ?>&idPage=" + idPage,
			data: $('#formulario').serialize(),
			beforeSend: function() {
				$('#relatorioConteudo').html('<tr><td colspan="100"><div class="loading" style="width: 100%;"></div></tr></td>');
			},
			success: function(data) {
				$("#relatorioConteudo").html(data);
			},
			error: function() {
				$('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
			}
		});
	}
</script>