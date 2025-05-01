	<?php

	if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
		echo fnDebug('true');
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);
	}


	$val_pesquisa = '';

	$hashLocal = mt_rand();

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$request = md5(serialize($_POST));

		if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
			$msgRetorno = 'Essa página já foi utilizada';
			$msgTipo = 'alert-warning';
		} else {
			$_SESSION['last_request']  = $request;

			$cod_grupotr = fnLimpaCampoZero(@$_REQUEST['COD_GRUPOTR']);
			$des_grupotr = fnLimpaCampo(@$_REQUEST['DES_GRUPOTR']);
			$cod_empresa = fnLimpaCampo(@$_REQUEST['COD_EMPRESA']);

			$opcao = @$_REQUEST['opcao'];
			$hHabilitado = @$_REQUEST['hHabilitado'];
			$hashForm = @$_REQUEST['hashForm'];


			// - variáveis da barra de pesquisa -------------
			$filtro = fnLimpaCampo(@$_POST['VAL_PESQUISA']);
			$val_pesquisa = fnLimpaCampo(@$_POST['INPUT']);
			// ----------------------------------------------

			if ($opcao != '') {

				//mensagem de retorno
				switch ($opcao) {
					case 'CAD':
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
						break;
					case 'ALT':
						$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";
						break;
					case 'EXC':
						$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";
						break;
						break;
				}
				$msgTipo = 'alert-success';
			}
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

	//rotina de controle de acessos por módulo
	include "moduloControlaAcesso.php";


	//echo "<pre>";	
	//print_r($arrayParamAutorizacao);	
	//echo "</pre>";	


	// esquema do X da barra - (recarregar pesquisa)
	if ($val_pesquisa != "") {
		$esconde = " ";
	} else {
		$esconde = "display: none;";
	}
	// ---------------------------------------------


	//fnEscreve($_SESSION["SYS_COD_SISTEMA"]);	
	//fnMostraForm();
	//fnEscreve($modulosRelatorios);
	?>


	<style>
		#services {}

		#services .services-top {
			padding: 70px 0 50px;
		}

		#services .services-list {
			padding-top: 50px;
		}

		.services-list .service-block {
			margin-bottom: 25px;
		}

		.services-list .service-block .ico {
			font-size: 38px;
			float: left;
		}

		.services-list .service-block .text-block {
			margin-left: 58px;
		}

		.services-list .service-block .text-block .name {
			font-size: 20px;
			font-weight: 900;
			margin-bottom: 5px;
		}

		.services-list .service-block .text-block .info {
			font-size: 16px;
			font-weight: 300;
			margin-bottom: 10px;
		}

		.services-list .service-block .text-block .text {
			font-size: 12px;
			line-height: normal;
			font-weight: 300;
		}

		.highlight {
			color: #2ac5ed;
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
						<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
					</div>

					<?php
					switch ($_SESSION["SYS_COD_SISTEMA"]) {
						case 3: //adm marka
							$formBack = "1189";
							break;
					}

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

					<?php
					//1190 - Lista relatórios - adm
					//1189 - Lista relatórios - campanhas
					if (fnDecode($_GET['mod']) == 1182) {
						$abaCampanhas = 1182;

						//liberação das abas
						$abaPersona	= "S";
						$abaVantagem = "S";
						$abaRegras = "N";
						$abaComunica = "N";
						$abaAtivacao = "N";
						$abaResultado = "N";

						//$abaPersonaComp = "completed ";
						$abaPersonaComp = "";
						$abaCampanhaComp = " ";
						$abaRegrasComp = "";
						$abaComunicaComp = "";
						$abaResultadoComp = "active ";
						//revalidada na aba de regras	
						$abaAtivacaoComp = "";

						include "abasCampanhasConfig.php";
						echo "<div class='push30'></div>";
					}
					//fnEscreve()	
					?>

					<div class="login-form">

						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

							<!-- barra de pesquisa -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------  -->

							<div class="row" style="visibility: visible;">
								<form name="formLista2" id="formLista2" method="post" action="<?php echo $cmdPage; ?>">

									<div class="col-xs-4 col-xs-offset-4">
										<div class="input-group activeItem">
											<div class="input-group-btn search-panel">
												<button type="button" class="btn btn-outline dropdown-toggle form-control form-control-sm rounded-left search-bar" id="FILTERS" data-toggle="dropdown">
													<span id="search_concept">Sem filtro</span>&nbsp;
													<span class="far fa-angle-down"></span>
												</button>
												<ul class="dropdown-menu" role="menu">
													<li class="divisor"><a href="#">Sem filtro</a></li>
												</ul>
											</div>
											<input type="hidden" name="VAL_PESQUISA" value="" id="VAL_PESQUISA">
											<input type="text" id="INPUT" class="form-control form-control-sm remove-side-borders search-bar" name="INPUT" value="<?= $val_pesquisa ?>" onkeyup="buscaRegistro(this)">
											<div class="input-group-btn" id="CLEARDIV" style="<?= $esconde ?>">
												<button class="btn btn-outline form-control form-control-sm remove-side-borders search-bar" id="CLEAR" type="button">&nbsp;<span class="fal fa-times"></span></button>
											</div>
											<div class="input-group-btn">
												<button type="submit" class="btn btn-outline form-control form-control-sm rounded-right search-bar" id="SEARCH">&nbsp;<span class="fal fa-search"></span></button>
											</div>
										</div>
									</div>

									<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
									<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

								</form>

							</div>

							<div class="push30"></div>

							<!-- ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ -->

							<div class="row">
								<div class="services-list buscavel">

									<div class="row" style="margin: 0 0 0 1px;">

										<div class="col-sm-6 col-md-4">
											<div class="service-block" style="visibility: visible;">
												<div class="ico fal fa-chart-pie highlight"></div>
												<div class="text-block">
													<h4>Dash Board</h4>
													<div class="text">Infográficos incríveis</div>
													<div class="push10"></div>

													<?php if (fnControlaAcesso("1490", $arrayParamAutorizacao) === true) { ?>
														<b><a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1490) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Analytics</a></b> <br />
													<?php } ?>

													<?php if (fnControlaAcesso("1210", $arrayParamAutorizacao) === true) { ?>
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1210) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Cadastros <small>RT</small> </a> <br />
													<?php } ?>

													<?php if (fnControlaAcesso("1562", $arrayParamAutorizacao) === true) { ?>
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1562) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Evolução Comparativa </a> <br />
													<?php } ?>

													<?php if (fnControlaAcesso("1841", $arrayParamAutorizacao) === true) { ?>
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?= fnEncode(1841) . "&id=" . fnEncode($cod_empresa) ?>" target="_blank">&rsaquo; Evolução de Índice Fidelização <small>(lojas)</small></a> <br />
													<?php } ?>

													<?php if (fnControlaAcesso("1646", $arrayParamAutorizacao) === true) { ?>
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1646) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Funil de Clientes por Frequência</a> <br />
													<?php } ?>

													<?php if (fnControlaAcesso("1395", $arrayParamAutorizacao) === true) { ?>
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1395) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Funil de Clientes por Gasto</a> <br />
													<?php } ?>

													<?php if (fnControlaAcesso("1326", $arrayParamAutorizacao) === true) { ?>
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1326) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Índice de Engajamento </a> <br />
													<?php } ?>

													<?php if (fnControlaAcesso("1384", $arrayParamAutorizacao) === true) { ?>
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1384) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Índice de Faturamento </a> <br />
													<?php } ?>

													<?php if (fnControlaAcesso("1133", $arrayParamAutorizacao) === true) { ?>
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1133) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Índice de Fidelização</a> <br />
													<?php } ?>

													<?php if (fnControlaAcesso("1266", $arrayParamAutorizacao) === true) { ?>
														<b><a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1266) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Índice de Fidelização <small>RT</small></a></b> <br />
													<?php } ?>

													<?php if (fnControlaAcesso("1312", $arrayParamAutorizacao) === true) { ?>
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1312) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Índice de Fidelização <small>(por atendente)</small></a> <br />
													<?php } ?>

													<?php if (fnControlaAcesso("1216", $arrayParamAutorizacao) === true) { ?>
														<b><a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1216) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Índice de Fidelização <small>(por vendedor <small> ADM </small>)</small></a></b><br />
													<?php } ?>

													<?php if (fnControlaAcesso("1617", $arrayParamAutorizacao) === true) { ?>
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1617) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Índice de Fidelização <small>(por vendedor)</small></a> <br />
													<?php } ?>

													<?php if (fnControlaAcesso("1342", $arrayParamAutorizacao) === true) { ?>
														<b><a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1342) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Índice de Performance </a></b> <br />
													<?php } ?>

													<?php if (fnControlaAcesso("1341", $arrayParamAutorizacao) === true) { ?>
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1341) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Índice de Resgates </a> <br />
													<?php } ?>

													<?php if (fnControlaAcesso("1894", $arrayParamAutorizacao) === true) { ?>
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1894) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Performance Geral do Programa </a> <br />
													<?php } ?>

													<?php if (fnControlaAcesso("1246", $arrayParamAutorizacao) === true) { ?>
														<b><a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1246) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Tickets de Ofertas <small>(consolidado)</small></a></b> <br />
													<?php } ?>
												</div>
											</div>
										</div>

										<div class="col-sm-6 col-md-4 col-md-4">
											<div class="service-block" style="visibility: visible;">
												<div class="ico fal fa-chart-line highlight"></div>
												<div class="text-block">
													<h4>Vendas</h4>
													<div class="text">Informações detalhadas do seu programa</div>
													<div class="push10"></div>

													<?php if (fnControlaAcesso("1463", $arrayParamAutorizacao) === true) { ?>
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?= fnEncode(1463) . "&id=" . fnEncode($cod_empresa) ?>" target="_blank">&rsaquo; Comparação de Vendas</a> <br />
													<?php } ?>

													<?php if (fnControlaAcesso("1238", $arrayParamAutorizacao) === true) { ?>
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1238) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Compensação</a> <br />
													<?php } ?>

													<?php if (fnControlaAcesso("1773", $arrayParamAutorizacao) === true) { ?>
														<b><a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1773) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Concedidos x Resgatados</a></b> <br />
													<?php } ?>

													<?php if (fnControlaAcesso("1734", $arrayParamAutorizacao) === true) { ?>
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1734) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Consolidado de Resgates</a> <br />
													<?php } ?>

													<?php if (fnControlaAcesso("1218", $arrayParamAutorizacao) === true) { ?>
														<b><a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1218) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Consolidado Mensal</a></b> <br />
													<?php } ?>

													<?php if (fnControlaAcesso("1320", $arrayParamAutorizacao) === true) { ?>
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1323) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Créditos Manuais</a> <br />
													<?php } ?>

													<?php if (fnControlaAcesso("1451", $arrayParamAutorizacao) === true) { ?>
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?= fnEncode(1459) . "&id=" . fnEncode($cod_empresa) ?>" target="_blank">&rsaquo; Movimentação de Clientes</a> <br />
													<?php } ?>

													<?php if (fnControlaAcesso("1544", $arrayParamAutorizacao) === true) { ?>
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1544) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Movimentação Histórica </a> <br />
													<?php } ?>

													<?php if (fnControlaAcesso("1952", $arrayParamAutorizacao) === true) { ?>
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1952) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Performance do Vendedor </a> <br />
													<?php } ?>

													<?php if (fnControlaAcesso("1859", $arrayParamAutorizacao) === true) { ?>
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1859) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Pós Vendas</a> <br />
													<?php } ?>

													<?php if (fnControlaAcesso("1224", $arrayParamAutorizacao) === true) { ?>
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1224) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Resgates</a> <br />
													<?php } ?>

													<?php if (fnControlaAcesso("1755", $arrayParamAutorizacao) === true) { ?>
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1755) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Resgates Manuais</a> <br />
													<?php } ?>

													<?php if (fnControlaAcesso("1614", $arrayParamAutorizacao) === true) { ?>
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1614) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Vendas Avulsas</a> <br />
													<?php } ?>

													<?php if (fnControlaAcesso("1245", $arrayParamAutorizacao) === true) { ?>
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1245) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Vendas Estornadas</a> <br />
													<?php } ?>

													<?php if (fnControlaAcesso("1320", $arrayParamAutorizacao) === true) { ?>
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1320) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Vendas Manuais</a> <br />
													<?php } ?>

													<?php if (fnControlaAcesso("1242", $arrayParamAutorizacao) === true) { ?>
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1242) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Vendas por Produto</a> <br />
													<?php } ?>

													<?php if (fnControlaAcesso("1139", $arrayParamAutorizacao) === true) { ?>
														<b><a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1139) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Vendas <small>RT</small></a></b> <br />
													<?php } ?>

													<?php if (fnControlaAcesso("1135", $arrayParamAutorizacao) === true) { ?>
														<b><a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1135) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Vendas Vinculadas a Resgates</a></b> <br />
													<?php } ?>

												</div>
											</div>
										</div>

										<div class="col-sm-6 col-md-4 col-md-4">
											<div class="service-block" style="visibility: visible;">
												<div class="ico fal fa-users highlight"></div>
												<div class="text-block">
													<h4>Clientes</h4>
													<div class="text">Conheça profundamente seu cliente </div>
													<div class="push10"></div>

													<?php if (fnControlaAcesso("1480", $arrayParamAutorizacao) === true) { ?>
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1480) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Análises de Índices﻿ </a> <br />
													<?php } ?>

													<?php if (fnControlaAcesso("1896", $arrayParamAutorizacao) === true) { ?>
														<b><a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1896) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Aniversariantes</a></b> <br />
													<?php } ?>

													<?php if (fnControlaAcesso("2004", $arrayParamAutorizacao) === true) { ?>
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(2004) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Bônus Extra</small></a> <br />
													<?php } ?>

													<?php if (fnControlaAcesso("1997", $arrayParamAutorizacao) === true) { ?>
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1997) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Bônus Extras Resgatados</a> <br />
													<?php } ?>

													<?php if (fnControlaAcesso("2095", $arrayParamAutorizacao) === true) { ?>
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(2095) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Cadastros Campanha QrCode</a> <br />
													<?php } ?>

													<?php if (fnControlaAcesso("1303", $arrayParamAutorizacao) === true) { ?>
														<b><a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1303) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Cadastros Geral</a></b> <br />
													<?php } ?>

													<?php if (fnControlaAcesso("1733", $arrayParamAutorizacao) === true) { ?>
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1733) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Categoria de Clientes﻿ </a> <br />
													<?php } ?>

													<?php if (fnControlaAcesso("1430", $arrayParamAutorizacao) === true) { ?>
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1430) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Clientes Cadastrados por Vendedor</a> <br />
													<?php } ?>

													<?php if (fnControlaAcesso("1747", $arrayParamAutorizacao) === true) { ?>
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1747) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Clientes Excluídos</a> <br />
													<?php } ?>

													<?php if (fnControlaAcesso("1229", $arrayParamAutorizacao) === true) { ?>
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1229) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Clientes Top 100</a> <br />
													<?php } ?>

													<?php if (fnControlaAcesso("1223", $arrayParamAutorizacao) === true) { ?>
														<b><a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1223) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Créditos a Expirar</a></b> <br />
													<?php } ?>

													<?php if (fnControlaAcesso("1257", $arrayParamAutorizacao) === true) { ?>
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1257) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Créditos Expirados Sem Resgate</a> <br />
													<?php } ?>

													<?php if (fnControlaAcesso("1222", $arrayParamAutorizacao) === true) { ?>
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1222) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Créditos Extras</a> <br />
													<?php } ?>

													<?php if (fnControlaAcesso("1884", $arrayParamAutorizacao) === true) { ?>
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1884) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Creditos Resgatados Aniversariante</a> <br />
													<?php } ?>

													<?php if (fnControlaAcesso("1882", $arrayParamAutorizacao) === true) { ?>
														<b><a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1882) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Inatividade</a></b> <br />
													<?php } ?>

													<?php if (fnControlaAcesso("1209", $arrayParamAutorizacao) === true) { ?>
														<b><a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1209) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Inconsistências</a></b> <br />
													<?php } ?>

													<?php if (fnControlaAcesso("1531", $arrayParamAutorizacao) === true) { ?>
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1531) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Loja de Preferência </a> <br />
													<?php } ?>

													<?php if (fnControlaAcesso("1801", $arrayParamAutorizacao) === true) { ?>
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1801) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Migração Funil</a> <br />
													<?php } ?>

													<?php if (fnControlaAcesso("1206", $arrayParamAutorizacao) === true) { ?>
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1206) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Personas</a> <br />
													<?php } ?>

													<?php if (fnControlaAcesso("1221", $arrayParamAutorizacao) === true) { ?>
														<b><a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1221) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Qualidade de Cadastros <small>(Loja/Vendedor) </small></a></b> <br />
													<?php } ?>

													<?php if (fnControlaAcesso("1879", $arrayParamAutorizacao) === true) { ?>
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1879) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Tokens de Senha da App</a> <br />
													<?php } ?>

													<?php if (fnControlaAcesso("1460", $arrayParamAutorizacao) === true) { ?>
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1460) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Troca de Cartões</a> <br />
													<?php } ?>

												</div>
											</div>
										</div>

										<div class="push30"></div>

										<div class="col-sm-6 col-md-4 col-md-4">
											<div class="service-block" style="visibility: visible;">
												<div class="ico fal fa-cubes highlight"></div>
												<div class="text-block">
													<h4>Produtos</h4>
													<div class="text">Uma visão estratégica</div>
													<div class="push10"></div>

													<?php if (fnControlaAcesso("1385", $arrayParamAutorizacao) === true) { ?>
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1385) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Prêmios Resgatados</a> <br />
													<?php } ?>

													<?php if (fnControlaAcesso("1199", $arrayParamAutorizacao) === true) { ?>
														<b><a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1199) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Produtos do Ticket</a></b> <br />
													<?php } ?>

													<?php if (fnControlaAcesso("1219", $arrayParamAutorizacao) === true) { ?>
														<b><a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1219) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Produtos Mais Vendidos</a></b> <br />
													<?php } ?>

													<?php if (fnControlaAcesso("1925", $arrayParamAutorizacao) === true) { ?>
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1925) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Produtos por Clientes/Vendas</a> <br />
													<?php } ?>

													<?php if (fnControlaAcesso("1386", $arrayParamAutorizacao) === true) { ?>
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1386) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Produtos Preços Diferenciados</a> <br />
													<?php } ?>

													<?php if (fnControlaAcesso("1382", $arrayParamAutorizacao) === true) { ?>
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1382) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Produtos Resgatados</a> <br />
													<?php } ?>

													<?php if (fnControlaAcesso("1620", $arrayParamAutorizacao) === true) { ?>
														<b><a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1620) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Produtos TO</a></b> <br />
													<?php } ?>
												</div>
											</div>
										</div>

										<div class="col-sm-6 col-md-4 col-md-4">
											<div class="service-block" style="visibility: visible;">
												<div class="ico fal fa-unlock-alt highlight"></div>
												<div class="text-block">
													<h4>Segurança</h4>
													<div class="text">Seu dia a dia seguro </div>
													<div class="push10"></div>

													<?php if (fnControlaAcesso("1397", $arrayParamAutorizacao) === true) { ?>
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1397) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Acessos</a> <br />
													<?php } ?>

													<?php if (fnControlaAcesso("1745", $arrayParamAutorizacao) === true) { ?>
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1745) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Cadastros LGPD</a> <br />
													<?php } ?>

													<?php if (fnControlaAcesso("1685", $arrayParamAutorizacao) === true) { ?>
														<b><a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1685) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Controle de Tokens</a></b> <br />
													<?php } ?>

													<?php if (fnControlaAcesso("1472", $arrayParamAutorizacao) === true) { ?>
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1472) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Logins de Usuários</a> <br />
													<?php } ?>

													<?php if (fnControlaAcesso("1746", $arrayParamAutorizacao) === true) { ?>
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1746) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Pendenciamento LGPD</a> <br />
													<?php } ?>

													<?php if (fnControlaAcesso("1618", $arrayParamAutorizacao) === true) { ?>
														<b><a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1618) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Vendas Bloqueadas</small></a></b> <br />
													<?php } ?>

													<?php if (fnControlaAcesso("1191", $arrayParamAutorizacao) === true) { ?>
														<b><a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1191) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Vendas Bloqueadas <small><small>(ADM)</small></small></a></b> <br />
													<?php } ?>

													<?php if (fnControlaAcesso("1615", $arrayParamAutorizacao) === true) { ?>
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1615) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Vendas Desbloqueadas e Excluídas</a> <br />
													<?php } ?>

												</div>
											</div>
										</div>

										<div class="col-sm-6 col-md-4">
											<div class="service-block" style="visibility: visible;">
												<div class="ico fal fa-comments highlight"></div>
												<div class="text-block">
													<h4>Comunicação</h4>
													<div class="text">Controle de comunicações realizadas</div>
													<div class="push10"></div>

													<?php if (fnControlaAcesso("1412", $arrayParamAutorizacao) === true) { ?>
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1412) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Campanhas Extras</a> <br />
													<?php } ?>

													<?php if (fnControlaAcesso("1233", $arrayParamAutorizacao) === true) { ?>
														<b><a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1233) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Comunicações Geradas</a></b> <br />
													<?php } ?>

													<?php if (fnControlaAcesso("1381", $arrayParamAutorizacao) === true) { ?>
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1381) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Desafio</a> <br />
													<?php } ?>

													<?php if (fnControlaAcesso("1625", $arrayParamAutorizacao) === true) { ?>
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1625) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Pesquisas</a> <br />
													<?php } ?>

													<?php if (fnControlaAcesso("1671", $arrayParamAutorizacao) === true) { ?>
														<b><a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1671) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Resultados de Campanhas</a></b> <br />
													<?php } ?>

												</div>
											</div>
										</div>

										<div class="push30"></div>

										<?php
										$sql = "SELECT COUNT(TIP_CAMPANHA) as TEM_CAMPANHA FROM CAMPANHA WHERE TIP_CAMPANHA = 20 AND COD_EMPRESA = $cod_empresa";
										$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
										$qrBuscaCampanha = mysqli_fetch_assoc($arrayQuery);
										$tem_campanha = $qrBuscaCampanha['TEM_CAMPANHA'];

										if ($tem_campanha == 0) {
											$desabilitaBlock = "<div class='disabledBlock'></div>";
										} else {
											$desabilitaBlock = "";
										}
										//fnEscreve($tem_campanha);
										?>


										<div class="col-sm-6 col-md-4">
											<?= $desabilitaBlock ?>
											<div class="service-block" style="visibility: visible;">
												<div class="ico fal fa-ticket-alt highlight"></div>
												<div class="text-block">
													<h4>Campanhas de Ticket</h4>
													<div class="text">Acompanhe todos os detalhes das campanhas </div>
													<div class="push10"></div>

													<?php if (fnControlaAcesso("1414", $arrayParamAutorizacao) === true) { ?>
														<b><a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?= fnEncode(1414) . "&id=" . fnEncode($cod_empresa) ?>&idc=<?= fnEncode(15) ?>" target="_blank">&rsaquo; Cupons Gerados</a></b> <br />
													<?php } ?>

													<?php if (fnControlaAcesso("1417", $arrayParamAutorizacao) === true) { ?>
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?= fnEncode(1417) . "&id=" . fnEncode($cod_empresa) ?>&idc=<?= fnEncode(15) ?>" target="_blank">&rsaquo; Cupons Gerados por Loja</a> <br />
													<?php } ?>

													<?php if (fnControlaAcesso("1422", $arrayParamAutorizacao) === true) { ?>
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?= fnEncode(1422) . "&id=" . fnEncode($cod_empresa) ?>&idc=<?= fnEncode(15) ?>" target="_blank">&rsaquo; Emails Enviados</a> <br />
													<?php } ?>

													<?php if (fnControlaAcesso("1416", $arrayParamAutorizacao) === true) { ?>
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?= fnEncode(1416) . "&id=" . fnEncode($cod_empresa) ?>&idc=<?= fnEncode(15) ?>" target="_blank">&rsaquo; Indicação de Clientes </a> <br />
													<?php } ?>

													<?php if (fnControlaAcesso("1418", $arrayParamAutorizacao) === true) { ?>
														<b><a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?= fnEncode(1418) . "&id=" . fnEncode($cod_empresa) ?>&idc=<?= fnEncode(15) ?>" target="_blank">&rsaquo; Números Gerados por Cliente por Loja</a></b> <br />
													<?php } ?>

													<?php if (fnControlaAcesso("1415", $arrayParamAutorizacao) === true) { ?>
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?= fnEncode(1415) . "&id=" . fnEncode($cod_empresa) ?>&idc=<?= fnEncode(15) ?>" target="_blank">&rsaquo; Resultado de Indicações</a> <br />
													<?php } ?>

												</div>
											</div>
										</div>

										<div class="col-sm-6 col-md-4">
											<div class="service-block" style="visibility: visible;">
												<div class="ico fal fa-user-tie highlight"></div>
												<div class="text-block">
													<h4>Personalizados</h4>
													<div class="text">Feitos exclusivamente para você</div>
													<div class="push10"></div>

													<!-- Relatórios gerais / temporários -->
													<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1959) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Análise de Campanha</a> <br />

													<?php
													if ($_SESSION["SYS_COD_SISTEMA"] == 3 || $cod_empresa == 58) { //danny 
													?>
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1740) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Exportação de Vendas</a> <br />
													<?php } ?>

													<?php if ($_SESSION["SYS_COD_SISTEMA"] == 3 || $cod_empresa == 77) { //multicoisas 
													?>
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1313) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Controle de Alterações <small>(cadastros)</small></a> <br />
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1469) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Indicação de Produtos <small>(por agrupador)</small></a> <br />
													<?php } ?>

													<?php if ($_SESSION["SYS_COD_SISTEMA"] == 3 || $cod_empresa == 85) { //farmamed 
													?>
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1753) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Alteração Cadastral <small>(Exportação)</small></a> <br />
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1325) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Dash de Geo Localização</a> <br />
													<?php } ?>

													<?php if ($_SESSION["SYS_COD_SISTEMA"] == 3 || $cod_empresa == 19) { //Rede Duque App 
													?>
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1898) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Cadastro Geral Detalhado</a> <br />
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1498) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Dash Fidelização</a> <br />
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1954) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Desempenho de Categoria</a> <br />
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1299) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Importações</a> <br />
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1214) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Vendas (Token)</a> <br />
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1009) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Vendas (Overview)</a> <br />
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1800) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Veículos Excluídos</a> <br />
													<?php } ?>

													<?php if ($cod_empresa == 292 || $cod_empresa == 210 || $cod_empresa == 210 || $cod_empresa == 276) {  //São Rafael / Lumi
													?>
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1299) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Importações</a> <br />
													<?php } ?>

													<?php if ($cod_empresa == 210) {  // Lumi
													?>
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1787) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Estudo Faixa Valor <small>(para fidelidade) </small></a> <br />
													<?php } ?>

													<?php if ($cod_empresa == 292) {  // São Rafael
													?>
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1825) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Divergência de CPF</a> <br />
													<?php } ?>

													<?php if ($_SESSION["SYS_COD_SISTEMA"] == 3 || $cod_empresa == 219 || $cod_empresa == 306) { //kings, manock 
													?>
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1898) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Cadastro Geral Detalhado</a> <br />
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1790) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Créditos Aniversariantes</small></a> <br />
													<?php } ?>

													<?php if ($_SESSION["SYS_COD_SISTEMA"] == 3 || $cod_empresa == 386) { //rede mais vale 
													?>
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1898) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Cadastro Geral Detalhado</a> <br />
													<?php } ?>

												</div>

											</div>
										</div>

										<div class="col-sm-6 col-md-4">
											<div class="service-block" style="visibility: visible;">
												<div class="ico fal fa-code highlight"></div>
												<div class="text-block">
													<h4>Sistema</h4>
													<div class="text">Logs de acessos e muito mais</div>
													<div class="push10"></div>

													<?php if (fnControlaAcesso("1197", $arrayParamAutorizacao) === true) { ?>
														<b><a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1197) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Cadastro de Clientes WS</a></b> <br />
													<?php } ?>

													<?php if (fnControlaAcesso("1198", $arrayParamAutorizacao) === true) { ?>
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1198) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Consulta de Clientes WS</a> <br />
													<?php } ?>

													<?php if (fnControlaAcesso("1502", $arrayParamAutorizacao) === true) { ?>
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1502) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Créditos Não Gerados</a> <br />
													<?php } ?>

													<?php if (fnControlaAcesso("1202", $arrayParamAutorizacao) === true) { ?>
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1370) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Dash XML Vendas</a> <br />
													<?php } ?>

													<?php if ($_SESSION["SYS_COD_SISTEMA"] == 3 || $_SESSION["SYS_COD_MASTER"] == 2) { ?>
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1802) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Debug WS</a> <br />
													<?php } ?>

													<?php if (fnControlaAcesso("1196", $arrayParamAutorizacao) === true) { ?>
														<b><a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1196) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Entrada de Venda WS</a></b> <br />
													<?php } ?>

													<?php if ($_SESSION["SYS_COD_SISTEMA"] == 3 || $_SESSION["SYS_COD_MASTER"] == 2) { ?>
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(2034) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Exclusão de Lote de Produtos</a> <br />
													<?php } ?>

													<?php if ($_SESSION["SYS_COD_SISTEMA"] == 3 || $_SESSION["SYS_COD_MASTER"] == 2) { ?>
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1737) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Logs Auditoria</a> <br />
													<?php } ?>

													<?php if (fnControlaAcesso("1499", $arrayParamAutorizacao) === true) { ?>
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1499) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Monitor de Unidades</a> <br />
													<?php } ?>

													<?php if (fnControlaAcesso("1599", $arrayParamAutorizacao) === true) { ?>
														<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1614) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Vendas Avulsas</a> <br />
													<?php } ?>

													<?php if (fnControlaAcesso("1599", $arrayParamAutorizacao) === true) { ?>
														<b><a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1599) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Vendas Estornadas WS</a></b> <br />
													<?php } ?>
												</div>

											</div>

										</div>


									</div>

									<?php
									if ($_SESSION["SYS_COD_SISTEMA"] == "3") {
									?>

										<div class="push30"></div>

										<div class="col-sm-6 col-md-4">
											<div class="service-block" style="visibility: visible;">
												<div class="ico fal fa-briefcase highlight"></div>
												<div class="text-block">
													<h4>Consultor</h4>
													<div class="text">Exclusivos para o consultor</div>
													<div class="push10"></div>
													<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1137) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Consolidado TO <small>(por vendedor)</small></a> <br />
													<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1787) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Estudo Faixa Valor <small>(para fidelidade) </small></a> <br />
													<b><a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1812) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Gerencial de Projetos</a></b> <br />
													<a href="https://<?php echo $_SERVER['HTTP_HOST']; ?>/action.do?mod=<?php echo fnEncode(1473) . "&id=" . fnEncode($cod_empresa); ?>" target="_blank">&rsaquo; Usuários Comunicação</a> <br />

												</div>
											</div>
										</div>

									<?php
									}
									?>

								</div>

							</div>

					</div>




					</form>

					<div class="push50"></div>

				</div>

			</div>
		</div>
		<!-- fim Portlet -->
	</div>

	</div>

	<div class="push20"></div>

	<script type="text/javascript">
		function buscaRegistro(input) {
			var value = input.value.toLowerCase().trim();
			var listaLinks = $('.service-block');

			listaLinks.each(function() {
				var links = $(this).find('a');

				links.each(function() {
					var linkText = $(this).text().toLowerCase().trim();
					var linkVisible = (linkText.indexOf(value) !== -1);
					$(this).toggle(linkVisible);
				});
			});
		}


		//Barra de pesquisa essentials ------------------------------------------------------
		/*$(document).ready(function(e){
			var value = $('#INPUT').val().toLowerCase().trim();
		    if(value){
		    	$('#CLEARDIV').show();
		    }else{
		    	$('#CLEARDIV').hide();
		    }
		    $('.search-panel .dropdown-menu').find('a').click(function(e) {
				e.preventDefault();
				var param = $(this).attr("href").replace("#","");
				var concept = $(this).text();
				$('.search-panel span#search_concept').text(concept);
				$('.input-group #VAL_PESQUISA').val(param);
				$('#INPUT').focus();
			});

		    $("#FILTERS, #INPUT, #SEARCH, #CLEAR").focus(function(){
			    $("#FILTERS, #INPUT, #SEARCH, #CLEAR").addClass("activeItem");
		    });

		    $("#FILTERS, #INPUT, #SEARCH, #CLEAR").blur(function(){
		    	$("#FILTERS, #INPUT, #SEARCH, #CLEAR").removeClass("activeItem");
		    });

		    $('#CLEAR').click(function(){
		    	$('#INPUT').val('');
		    	$('#INPUT').focus();
		    	$('#CLEARDIV').hide();
		    		location.reload();
		    	}else{
		    		var value = $('#INPUT').val().toLowerCase().trim();
				    if(value){
				    	$('#CLEARDIV').show();
				    }else{
				    	$('#CLEARDIV').hide();
				    }
				    $(".buscavel tr").each(function (index) {
				        if (!index) return;
				        $(this).find("td").each(function () {
				            var id = $(this).text().toLowerCase().trim();
				            var sem_registro = (id.indexOf(value) == -1);
				            $(this).closest('tr').toggle(!sem_registro);
				            return sem_registro;
				        });
				    });
		    	}
		    });

		    // $('#SEARCH').click(function(){
		    // 	$('#formulario').submit();
		    // });
		    	
		    
		});

		function buscaRegistro(el){
			var filtro = $('#search_concept').text().toLowerCase();

			if(filtro == "sem filtro"){
			    var value = $(el).val().toLowerCase().trim();
			    if(value){
			    	$('#CLEARDIV').show();
			    }else{
			    	$('#CLEARDIV').hide();
			    }
			    $(".buscavel tr").each(function (index) {
			        if (!index) return;
			        $(this).find("td").each(function () {
			            var id = $(this).text().toLowerCase().trim();
			            var sem_registro = (id.indexOf(value) == -1);
			            $(this).closest('tr').toggle(!sem_registro);
			            return sem_registro;
			        });
			    });
			}
		}*/

		//-----------------------------------------------------------------------------------

		function retornaForm(index) {
			$("#formulario #COD_GRUPOTR").val($("#ret_COD_GRUPOTR_" + index).val());
			$("#formulario #DES_GRUPOTR").val($("#ret_DES_GRUPOTR_" + index).val());
			$('#formulario').validator('validate');
			$("#formulario #hHabilitado").val('S');
		}


		//      $(document).ready( function() {

		// $(function(){

		// 	// Id Canvas
		// 	var idCanvas = 'myCanvas';
		// 	//var idCanvas2 = 'myCanvas2';
		// 	//var idCanvas3 = 'myCanvas3';

		// 	// Cor que será usado no preenchimento do gráfico.
		// 	var corGrafico = 'gold';

		// 	// iD tabela
		// 	var idTabela = 'tabela1';
		// 	//var idTabela2 = 'tabela2';
		// 	//var idTabela3 = 'tabela3';

		// 	// Pontos de Inicio em cada coluna, mandar porcentagem de 0 a 100.
		// 	var pontos = [100, 55, 20, 15, 8, 3];

		// 	// Criar Gráfico
		// 	criarGrafico(idCanvas, pontos, corGrafico, idTabela);
		// 	// Criar Gráfico
		// 	//criarGrafico(idCanvas2, pontos, corGrafico, idTabela2);
		// 	// Criar Gráfico
		// 	//criarGrafico(idCanvas3, pontos, corGrafico, idTabela3);

		// });

		// function criarGrafico(idCanvas, pontos, corGrafico, idTabela){
		// 	var canvas = document.getElementById(idCanvas);
		// 	var ctx = canvas.getContext('2d');

		// 	// Seta Valores de altura e largura da tabela para o canvas
		// 	ctx.canvas.width  = $('#' + idTabela).outerWidth();
		// 	ctx.canvas.height = $('#' + idTabela).outerHeight();

		// 	$('#'+ idCanvas).css('top', $('#' + idTabela).position().top);

		// 	//Pega informações da tabela
		// 	var larguraColuna = $('#' + idTabela +' td').outerWidth();
		// 	var numColunas = $('#' + idTabela +' tr:first td').size();
		// 	var alturaPrimeiraLinha = $('.primeiraLinha').outerHeight();
		// 	var alturaSegundaLinha = $('.segundaLinha').outerHeight();

		// 	ctx.beginPath();
		// 	ctx.moveTo(0, (((1 - (pontos[0] / 100)) * alturaSegundaLinha) + alturaPrimeiraLinha)); // altura e largura do ponto inicial

		// 	// Seta linhas
		// 	var cont = 1;
		// 	while(cont < pontos.length){
		// 		ctx.lineTo((larguraColuna * cont) + cont, (((1 - (pontos[cont] / 100)) * alturaSegundaLinha) + alturaPrimeiraLinha));
		// 		cont++;
		// 	}

		// 	// Pega a soma da primeira e segunda linha, assim é possível descobrir qual o ponto final do gráfico
		// 	var pontoFinal = alturaPrimeiraLinha + alturaSegundaLinha;

		// 	// Fecha lado direito
		// 	ctx.lineTo((larguraColuna * numColunas) + cont, pontoFinal);

		// 	// Fecha Parte de baixo
		// 	ctx.lineTo(0, pontoFinal);

		// 	//Pinta novo "Quadrado"
		// 	ctx.fillStyle= corGrafico;
		// 	ctx.fill();

		// 	// Muda cor das linhas
		// 	ctx.strokeStyle='lightgray';

		// 	// Desenha propriedades definidas
		// 	ctx.stroke();
		// }


		//      });		
	</script>