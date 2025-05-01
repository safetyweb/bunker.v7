<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$hashLocal = "";
$msgRetorno = "";
$msgTipo = "";
$cod_produto = "";
$cod_categor = "";
$cod_subcate = "";
$cod_externo = "";
$cod_fornecedor = "";
$cod_ean = "";
$des_produto = "";
$atributo1 = "";
$atributo2 = "";
$atributo3 = "";
$atributo4 = "";
$atributo5 = "";
$atributo6 = "";
$atributo7 = "";
$atributo8 = "";
$atributo9 = "";
$atributo10 = "";
$atributo11 = "";
$atributo12 = "";
$atributo13 = "";
$des_imagem = "";
$log_prodpbm = "";
$hHabilitado = "";
$hashForm = "";
$cod_usucada = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$log_estoque = "";
$lblAtributo1 = "";
$lblAtributo2 = "";
$lblAtributo3 = "";
$lblAtributo4 = "";
$lblAtributo5 = "";
$lblAtributo6 = "";
$lblAtributo7 = "";
$lblAtributo8 = "";
$lblAtributo9 = "";
$lblAtributo10 = "";
$lblAtributo11 = "";
$lblAtributo12 = "";
$lblAtributo13 = "";
$formBack = "";
$abaGestao = "";
$qrListaCategoria = "";
$qrListaSubCategoria = "";
$qrListaFornecedor = "";
$pesquisa = "";
$andExternoTkt = "";
$andProduto = "";
$andExterno = "";
$resPagina = "";
$total = 0;
$registros = "";
$inicio = "";
$sql1 = "";
$qrListaProduto = "";
$mostraDES_IMAGEM = "";
$mostraNOM_PRODTKT = "";
$tooltipNOM_PRODTKT = "";
$mostraGRUPO = "";
$mostraSUBGRUPO = "";
$tooltipQTD_ESTOQUE = "";
$tooltipCOD_PERSONA = "";
$tooltipVAL_PROMTKT = "";
$tooltipCAMPANHA = "";
$i = "";
$paginaAtiva = "";


$hashLocal = mt_rand();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode(@$_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_empresa = fnLimpacampoZero(@$_REQUEST['COD_EMPRESA']);
		$cod_produto = fnLimpacampoZero(@$_REQUEST['COD_PRODUTO']);
		$cod_categor = fnLimpacampoZero(@$_REQUEST['COD_CATEGOR']);
		$cod_subcate = fnLimpacampoZero(@$_REQUEST['COD_SUBCATE']);
		$cod_externo = fnLimpacampo(@$_REQUEST['COD_EXTERNO']);
		$cod_fornecedor = fnLimpacampoZero(@$_REQUEST['COD_FORNECEDOR']);
		$cod_ean = fnLimpacampo(@$_REQUEST['COD_EAN']);
		$des_produto = fnLimpacampo(@$_REQUEST['DES_PRODUTO']);
		$atributo1 = fnLimpacampo(@$_REQUEST['ATRIBUTO1']);
		$atributo2 = fnLimpacampo(@$_REQUEST['ATRIBUTO2']);
		$atributo3 = fnLimpacampo(@$_REQUEST['ATRIBUTO3']);
		$atributo4 = fnLimpacampo(@$_REQUEST['ATRIBUTO4']);
		$atributo5 = fnLimpacampo(@$_REQUEST['ATRIBUTO5']);
		$atributo6 = fnLimpacampo(@$_REQUEST['ATRIBUTO6']);
		$atributo7 = fnLimpacampo(@$_REQUEST['ATRIBUTO7']);
		$atributo8 = fnLimpacampo(@$_REQUEST['ATRIBUTO8']);
		$atributo9 = fnLimpacampo(@$_REQUEST['ATRIBUTO9']);
		$atributo10 = fnLimpacampo(@$_REQUEST['ATRIBUTO10']);
		$atributo11 = fnLimpacampo(@$_REQUEST['ATRIBUTO11']);
		$atributo12 = fnLimpacampo(@$_REQUEST['ATRIBUTO12']);
		$atributo13 = fnLimpacampo(@$_REQUEST['ATRIBUTO13']);
		$des_imagem = fnLimpacampo(@$_REQUEST['DES_IMAGEM']);
		if (empty(@$_REQUEST['LOG_PRODPBM'])) {
			$log_prodpbm = 'N';
		} else {
			$log_prodpbm = "S";
		}

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];
		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

		if ($opcao != '') {

			$sql = "CALL SP_ALTERA_PRODUTOCLIENTE (
				 '" . $cod_produto . "', 
				 '" . $cod_externo . "', 
				 '" . $cod_empresa . "',				
				 '" . $cod_ean . "',				
				 '" . $des_produto . "',				
				 '" . $cod_categor . "', 
				 '" . $cod_subcate . "', 
				 '" . $cod_fornecedor . "', 
				 '" . $atributo1 . "',
				 '" . $atributo2 . "',
				 '" . $atributo3 . "',
				 '" . $atributo4 . "',
				 '" . $atributo5 . "',
				 '" . $atributo6 . "',
				 '" . $atributo7 . "',
				 '" . $atributo8 . "',
				 '" . $atributo9 . "',
				 '" . $atributo10 . "',
				 '" . $atributo11 . "',
				 '" . $atributo12 . "',
				 '" . $atributo13 . "',				 
				 '" . $des_imagem . "',				 
				 '" . $cod_usucada . "',
				 '" . $log_prodpbm . "',
				 '" . $opcao . "'   
				) ";

			//echo $sql;
			//fnTesteSql(connTemp($cod_empresa,""),$sql);

			mysqli_query(connTemp($cod_empresa, ""), trim($sql));

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
if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode(@$_GET['id']);
	$sql = "SELECT COD_EMPRESA, NOM_EMPRESA, LOG_ESTOQUE FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
		$log_estoque = $qrBuscaEmpresa['LOG_ESTOQUE'];
	}

	/*
		$sql = "SELECT A.*,
		(select B.NOM_EMPRESA FROM empresas B where B.COD_EMPRESA = A.COD_EMPRESA ) as NOM_EMPRESA
		FROM EMPRESACOMPLEMENTO A where A.COD_EMPRESA = '".$cod_empresa."' ";
		*/
	$sql = "select  A.*,B.NOM_EMPRESA as NOM_EMPRESA from EMPRESACOMPLEMENTO A 
				INNER JOIN empresas B ON A.COD_EMPRESA = B.COD_EMPRESA
				where A.COD_EMPRESA = '" . $cod_empresa . "' ";


	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		//$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
		$lblAtributo1 = $qrBuscaEmpresa['ATRIBUTO1'];
		$lblAtributo2 = $qrBuscaEmpresa['ATRIBUTO2'];
		$lblAtributo3 = $qrBuscaEmpresa['ATRIBUTO3'];
		$lblAtributo4 = $qrBuscaEmpresa['ATRIBUTO4'];
		$lblAtributo5 = $qrBuscaEmpresa['ATRIBUTO5'];
		$lblAtributo6 = $qrBuscaEmpresa['ATRIBUTO6'];
		$lblAtributo7 = $qrBuscaEmpresa['ATRIBUTO7'];
		$lblAtributo8 = $qrBuscaEmpresa['ATRIBUTO8'];
		$lblAtributo9 = $qrBuscaEmpresa['ATRIBUTO9'];
		$lblAtributo10 = $qrBuscaEmpresa['ATRIBUTO10'];
		$lblAtributo11 = $qrBuscaEmpresa['ATRIBUTO11'];
		$lblAtributo12 = $qrBuscaEmpresa['ATRIBUTO12'];
		$lblAtributo13 = $qrBuscaEmpresa['ATRIBUTO13'];
	}
} else {
	$cod_empresa = 0;
	//fnEscreve('entrou else');
}

//fnMostraForm();
//fnEscreve($cod_empresa);
//fnEscreve($nom_empresa);

?>

<style>
	h1,
	.h1,
	h2,
	.h2,
	h3,
	.h3,
	h4,
	.h4,
	h5,
	.h5 {
		margin-top: 15px;
		margin-bottom: 2px;
	}

	.tags a {
		border: 1px solid;
		font-size: 10px;
		display: inline-block;
		color: #717171;
		background: #FFF;
		-webkit-box-shadow: 0 1px 1px 0 rgba(180, 180, 180, 0.1);
		box-shadow: 0 1px 1px 0 rgba(180, 180, 180, 0.1);
		-webkit-transition: all .1s ease-in-out;
		-moz-transition: all .1s ease-in-out;
		-o-transition: all .1s ease-in-out;
		-ms-transition: all .1s ease-in-out;
		transition: all .1s ease-in-out;
		border-radius: 2px;
		margin: 0 3px 6px 0;
		padding: 3px 5px
	}

	.tags a:hover {
		border-color: #08C;
		text-decoration: none;
	}

	.tags a.primary {
		color: #FFF;
		background-color: #428BCA;
		border-color: #357EBD
	}

	.tags a.success {
		color: #FFF;
		background-color: #5CB85C;
		border-color: #4CAE4C
	}

	.tags a.info {
		color: #FFF;
		background-color: #5BC0DE;
		border-color: #46B8DA
	}

	.tags a.warning {
		color: #FFF;
		background-color: #F0AD4E;
		border-color: #EEA236
	}

	.tags a.danger {
		color: #FFF;
		background-color: #D9534F;
		border-color: #D43F3A
	}

	.input-xs {
		height: 28px;
		padding: 2px 6px;
		font-size: 13px;
		line-height: 1.6;
		/* If Placeholder of the input is moved up, rem/modify this. */
		border-radius: 3px;
		border: 0;
	}

	input[type=search] {
		border: 0;
		outline: 0;
		background: transparent;
		border-bottom: 1px solid #d3d3d3;
		font-size: 12px;
		margin: 0;
		padding: 2px 5px;
		width: 90%;
	}

	.open-search {
		margin-left: 5px;
		padding: 3px;
	}

	.open-search:hover {
		background-color: #d3dcde;
		padding: 3px;
	}

	.p-table th,
	.p-table td {
		vertical-align: middle !important;
	}


	.p-imagem img {
		width: 60px;
		height: 60px;
		border-radius: 5px;
	}

	.p-name a {
		font-size: 14px;
		/*font-weight:bold;*/
	}

	.btn-circle {
		width: 30px;
		height: 30px;
		padding: 6px 0;
		border-radius: 15px;
		text-align: center;
		font-size: 12px;
		line-height: 1.428571429;
	}

	.btn-circle-long {
		height: 30px;
		min-width: 30px;
		padding: 6px;
		border-radius: 15px;
		text-align: center;
		font-size: 12px;
		line-height: 1.428571429;
	}

	.f14 {
		text-decoration: none !important;
	}

	.all {
		color: #fff;
		padding: 0 4px;
		border-radius: 3px;
	}

	a,
	table a:not(.btn),
	.table a:not(.btn) {
		text-decoration: none;
	}

	a:hover,
	table a:not(.btn):hover,
	.table a:not(.btn):hover {
		text-decoration: underline;
	}

	.table-hover>tbody>tr.hidden-table:hover>td {
		background-color: white;
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
					<span class="text-primary"> <?php echo $NomePg; ?></span>
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

				<?php
				$abaGestao = 1181;
				include "abasProdutosEmpresa.php";
				?>

				<div class="push30"></div>

				<div class="col-lg-3" style="padding-right: 0">

					<h3>Filtros</h3>
					<small><b class="totalResultados">0</b> resultados</small>
					<div class="push10"></div>

					<div class="tags"></div>

					<div class="push30"></div>

					<input class="icon filtroDescricao" style="font-family: FontAwesome; font-size: 14px;" type="search" placeholder="&#xF002">
					<div class="push10"></div>

					<h5>Promocionais</h5>
					<div class="push5"></div>
					<?php
					echo '<a class="f14 addFiltro" href="javascript:;" tipo="persona">Personas</a><br/>';
					echo '<a class="f14 addFiltro" href="javascript:;" tipo="campanha">Campanhas</a><br/>';
					echo '<a class="f14 addFiltro" href="javascript:;" tipo="habitoexclusao">Hábito de Exclusão</a><br/>';
					?>
					<div class="push10"></div>

					<h5>Categorias</h5>
					<div class="push5"></div>
					<?php
					$sql = "select * from categoria order by DES_CATEGOR limit 5";

					//fnEscreve($sql);
					$arrayQuery = mysqli_query(connTemp($cod_empresa, ""), $sql);

					while ($qrListaCategoria = mysqli_fetch_assoc($arrayQuery)) {
						echo '<a class="f14 addFiltro" href="javascript:;" tipo="categoria" codigo="' . $qrListaCategoria["COD_CATEGOR"] . '">' . ucfirst(strtolower($qrListaCategoria["DES_CATEGOR"])) . '</a><br/>';
					}

					echo '<a class="f12 bg-success all addBox" href="javascript:;" data-url="ajxCategoria.php?codEmpresa=' . $cod_empresa . '" data-title="Categorias" tipo="outro">Ver todos</a><br/>';
					?>
					<div class="push10"></div>

					<h5>Sub Categorias</h5>
					<div class="push5"></div>
					<?php
					$sql = "select * from subcategoria order by DES_SUBCATE limit 5";

					//fnEscreve($sql);
					$arrayQuery = mysqli_query(connTemp($cod_empresa, ""), $sql);

					while ($qrListaSubCategoria = mysqli_fetch_assoc($arrayQuery)) {
						echo '<a class="f14 addFiltro" href="javascript:;" tipo="subcategoria" codigo="' . $qrListaSubCategoria["COD_SUBCATE"] . '">' . ucfirst(strtolower($qrListaSubCategoria["DES_SUBCATE"])) . '</a><br/>';
					}

					echo '<a class="f12 bg-success all addBox" href="javascript:;" data-url="ajxSubCategoria.php?codEmpresa=' . $cod_empresa . '" data-title="SubCategorias" tipo="outro">Ver todos</a><br/>';
					?>
					<div class="push10"></div>

					<h5>Fornecedores</h5>
					<div class="push5"></div>
					<?php
					$sql = "select * from fornecedormrka order by NOM_FORNECEDOR limit 5";

					//fnEscreve($sql);
					$arrayQuery = mysqli_query(connTemp($cod_empresa, ""), $sql);

					while ($qrListaFornecedor = mysqli_fetch_assoc($arrayQuery)) {
						echo '<a class="f14 addFiltro" href="javascript:;" tipo="fornecedor" codigo="' . $qrListaFornecedor["COD_FORNECEDOR"] . '">' . ucfirst(strtolower($qrListaFornecedor["NOM_FORNECEDOR"])) . '</a><br/>';
					}

					echo '<a class="f12 bg-success all addBox" href="javascript:;" data-url="ajxFornecedor.php?codEmpresa=' . $cod_empresa . '" data-title="Fornecedores" tipo="outro">Ver todos</a><br/>';
					?>


				</div>

				<div id="listaProdutosFiltro">
					<div class="col-lg-9" style="padding-left: 0">

						<div class="push30"></div>

						<div class="no-more-tables">

							<form name="formLista">

								<table class="table table-hover p-table">

									<tbody>

										<?php
										$pagina = (isset($_GET['pagina'])) ? @$_GET['pagina'] : 1;

										//variáveis da pesquisa
										$cod_externo = fnLimpacampo(@$_REQUEST['COD_EXTERNO']);
										$pesquisa = fnLimpacampo(@$_REQUEST['pesquisa']);
										$des_produto = fnLimpacampo(@$_REQUEST['DES_PRODUTO']);

										//pesquisa no form local
										$andExternoTkt = ' ';
										if (empty(@$_REQUEST['pesquisa'])) {
											//fnEscreve("sem pesquisa");
											$andProduto = ' ';
											$andExterno = ' ';
										} else {
											//fnEscreve("com pesquisa");
											if ($des_produto != '' && $des_produto != 0) {
												$andProduto = 'AND A.DES_PRODUTO like "%' . $des_produto . '%"';
											} else {
												$andProduto = ' ';
											}

											if ($cod_externo != '' && $cod_externo != 0) {
												$andExterno = 'AND A.COD_EXTERNO = "' . $cod_externo . '"';
											} else {
												$andExterno = ' ';
											}
										}

										//se pesquisa dos produtos do ticket
										if (!empty(@$_GET['idP'])) {
											$andExterno = 'AND A.COD_EXTERNO = "' . @$_GET['idP'] . '"';
										}

										//fnEscreve("entrou");

										$sql = "select COUNT(*) as contador from PRODUTOCLIENTE A 
												left JOIN CATEGORIA B ON A.COD_CATEGOR = B.COD_CATEGOR 
												left JOIN SUBCATEGORIA C ON A.COD_SUBCATE = C.COD_SUBCATE 
												where A.COD_EMPRESA='" . $cod_empresa . "' 
												" . $andProduto . "
												" . $andExterno . " 
												AND A.COD_EXCLUSA=0 order by A.DES_PRODUTO";

										$resPagina = mysqli_query(connTemp($cod_empresa, ''), $sql);
										$total = mysqli_fetch_assoc($resPagina);
										//seta a quantidade de itens por página, neste caso, 2 itens
										$registros = 50;
										//calcula o número de páginas arredondando o resultado para cima
										$numPaginas = ceil($total['contador'] / $registros);
										//variavel para calcular o início da visualização com base na página atual
										$inicio = ($registros * $pagina) - $registros;

										$sql1 = " select A.*,
													   B.DES_CATEGOR as GRUPO,
													   C.DES_SUBCATE as SUBGRUPO,
														IFNULL((SELECT LOG_PRODTKT FROM PRODUTOTKT WHERE COD_EMPRESA=A.COD_EMPRESA AND COD_PRODUTO=A.COD_PRODUTO AND LOG_PRODTKT='S'),'N') AS LOG_PRODTKT,
														IFNULL((SELECT VAL_PRODTKT FROM PRODUTOTKT WHERE COD_EMPRESA=A.COD_EMPRESA AND COD_PRODUTO=A.COD_PRODUTO),0) AS VAL_PRODTKT,
														IFNULL((SELECT VAL_PROMTKT FROM PRODUTOTKT WHERE COD_EMPRESA=A.COD_EMPRESA AND COD_PRODUTO=A.COD_PRODUTO),0) AS VAL_PROMTKT,
														IFNULL((SELECT SUM(QTD_ESTOQUE) FROM PRODUTO_COMPLEMENTO WHERE COD_EMPRESA=A.COD_EMPRESA AND COD_PRODUTO=A.COD_PRODUTO),0) AS QTD_ESTOQUE,
														(SELECT COUNT(*) FROM CAMPANHAPRODUTO WHERE COD_EMPRESA=A.COD_EMPRESA  AND COD_CATEGOR = A.COD_CATEGOR AND COD_SUBCATE = A.COD_SUBCATE) AS CAMPANHA,
														(SELECT COUNT(*) FROM VANTAGEMEXTRAFAIXA WHERE COD_EMPRESA=A.COD_EMPRESA AND COD_PRODUTO=A.COD_PRODUTO) AS FAIXAS,
														IFNULL(COD_PERSONA_TKT,0) as COD_PERSONA,
														IFNULL((SELECT NOM_PRODTKT FROM PRODUTOTKT WHERE COD_EMPRESA=A.COD_EMPRESA AND COD_PRODUTO=A.COD_PRODUTO),'N') AS NOM_PRODTKT
													from PRODUTOCLIENTE A 
													LEFT JOIN CATEGORIA B ON A.COD_CATEGOR = B.COD_CATEGOR 
													LEFT JOIN SUBCATEGORIA C ON A.COD_SUBCATE = C.COD_SUBCATE 
													LEFT JOIN PRODUTOTKT D  ON A.COD_PRODUTO = D.COD_PRODUTO
													where A.COD_EMPRESA='" . $cod_empresa . "' 
													" . $andProduto . "
													" . $andExterno . " 
													AND A.COD_EXCLUSA=0 order by A.DES_PRODUTO limit $inicio,$registros ";

										//fnEscreve($sql1);
										//$arrayQuery = mysqli_query(connTemp($cod_empresa,""),$sql1);
										$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql1);

										$count = 0;
										while ($qrListaProduto = mysqli_fetch_assoc($arrayQuery)) {
											$count++;

											if ($qrListaProduto['DES_IMAGEM'] != "") {
												$mostraDES_IMAGEM = "<img src='http://img.bunker.mk/media/clientes/$cod_empresa/produtos/" . $qrListaProduto['DES_IMAGEM'] . "' style='max-width:70px; max-height: 100%'/>";
											} else {
												$mostraDES_IMAGEM = "";
											}

											if ($qrListaProduto['NOM_PRODTKT'] == "N") {
												$mostraNOM_PRODTKT = $qrListaProduto['DES_PRODUTO'];
												$tooltipNOM_PRODTKT = "style='opacity:0.4;' data-toggle='tooltip' data-placement='top' data-original-title='não possui ticket'";
											} else {
												$mostraNOM_PRODTKT = $qrListaProduto['NOM_PRODTKT'];
												$tooltipNOM_PRODTKT = "data-toggle='tooltip' data-placement='top' data-original-title='com ticket'";
											}

											if ($qrListaProduto['GRUPO'] == "") {
												$mostraGRUPO = "";
											} else {
												$mostraGRUPO = $qrListaProduto['GRUPO'] . " \ ";
											}

											if ($qrListaProduto['SUBGRUPO'] == "") {
												$mostraSUBGRUPO = "";
											} else {
												$mostraSUBGRUPO = $qrListaProduto['SUBGRUPO'];
											}

											if ($qrListaProduto['QTD_ESTOQUE'] == "0") {
												$tooltipQTD_ESTOQUE = "style='opacity:0.4;' data-toggle='tooltip' data-placement='top' data-original-title='sem estoque'";
											} else {
												$tooltipQTD_ESTOQUE = "data-toggle='tooltip' data-placement='top' data-original-title='em estoque'";
											}

											if ($qrListaProduto['COD_PERSONA'] == "0") {
												$tooltipCOD_PERSONA = "style='opacity:0.4;' data-toggle='tooltip' data-placement='top' data-original-title='sem persona'";
											} else {
												$tooltipCOD_PERSONA = "data-toggle='tooltip' data-placement='top' data-original-title='com persona'";
											}

											if ($qrListaProduto['VAL_PROMTKT'] == "0.00") {
												$tooltipVAL_PROMTKT = "style='opacity:0.4;' data-toggle='tooltip' data-placement='top' data-original-title='sem preço promocional'";
											} else {
												$tooltipVAL_PROMTKT = "data-toggle='tooltip' data-placement='top' data-original-title='com preço promocional'";
											}

											if ($qrListaProduto['CAMPANHA'] == "0" && $qrListaProduto['FAIXAS'] == "0") {
												$tooltipCAMPANHA = "style='opacity:0.4;' data-toggle='tooltip' data-placement='top' data-original-title='não possui campanha'";
											} else {
												$tooltipCAMPANHA = "data-toggle='tooltip' data-placement='top' data-original-title='com campanha'";
											}

											//fnEscreve($qrListaProduto['VAL_PROMTKT']);

										?>
											<tr>
												<td class="text-center p-imagem">
													<?php echo $mostraDES_IMAGEM; ?>
												</td>
												<td class="p-name">
													<small><?php echo $qrListaProduto['DES_PRODUTO']; ?> <br />
														<span class="f12"><i class="fal fa-ticket"></i> <?php echo $mostraNOM_PRODTKT; ?></span></small><br />
													<span class="f12"><?php echo $mostraGRUPO; ?> <?php echo $mostraSUBGRUPO; ?></span></small>
													<div class="push10"></div>
													<a style="cursor: pointer" class="mostrarProduto_<?php echo $qrListaProduto['COD_PRODUTO'] ?>" onClick="mostrarFilho(<?php echo $qrListaProduto['COD_PRODUTO'] ?>)"><i class="fa fa-angle-right"></i></a>
												</td>

												<td style="min-width: 280px">
													<div class="row">
														<div class="socials tex-center col-lg-12">
															<a class="btn btn-circle btn-primary" <?php echo $tooltipNOM_PRODTKT; ?>> <i class="fal fa-ticket"></i></a>
															<a class="btn btn-circle-long btn-success" <?php echo $tooltipQTD_ESTOQUE; ?>> <?php echo fnValor($qrListaProduto['QTD_ESTOQUE'], 0); ?> </a>
															<a class="btn btn-circle btn-info" <?php echo $tooltipCOD_PERSONA; ?>><i class="fal fa-male"></i></a>
															<a class="btn btn-circle btn-warning" <?php echo $tooltipVAL_PROMTKT; ?>><i class="fal fa-usd"></i></a>
															<a class="btn btn-circle btn-default" <?php echo $tooltipCAMPANHA; ?>><i class="fal fa-cart-arrow-down"></i></a>
															<a class="btn btn-circle btn-danger" <?php echo $tooltipCAMPANHA; ?>><i class="fal fa-ban"></i></a>
														</div>
													</div>
													<div class="push10"></div>
													<div class="row">
														<div class="col-lg-12">
															<div class="row" style="font-size: 11px">
																<div class="col-lg-3"><i class="fal fa-step-forward"></i>&nbsp; 0,00</div>
																<div class="col-lg-7"><i class="fal fa-ticket"></i>&nbsp; <b>De:</b> R$ <?php echo $qrListaProduto['VAL_PRODTKT'] ?>
																	&nbsp; <b>Por:</b> R$ <?php echo $qrListaProduto['VAL_PROMTKT'] ?></div>
															</div>

														</div>
													</div>
												</td>

												<td style="min-width: 90px">
													<div class="btn-group">
														<a href="#" class="btn btn-default input-xs">Ação</a>
														<a href="#" class="btn btn-default dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></a>
														<ul class="dropdown-menu">
															<li><a href="#" data-url="action.php?mod=<?php echo fnEncode(1194) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idPrd=<?php echo fnEncode($qrListaProduto['COD_PRODUTO']) ?>&pop=true" data-title="Produto" class="addBox">Editar Produto</a></li>
															<li class="disabled"><a href="#">Editar Produto na Campanha</a></li>
															<li class="disabled"><a href="#">Editar Produto Específico</a></li>
															<li class="disabled"><a href="#">Editar Produto no Ticket</a></li>
														</ul>
													</div>
												</td>

											</tr>
											<tr style="border-bottom: 1px dashed #e5e7e9; display: none" id="conteudoTable_<?php echo $qrListaProduto['COD_PRODUTO'] ?>">
												<td colspan='4' style="padding-top: 0; padding-bottom: 5px;">
													<div id="conteudoProduto_<?php echo $qrListaProduto['COD_PRODUTO'] ?>"></div>
												</td>
											</tr>
										<?php

										}
										?>

									</tbody>

								</table>

							</form>

						</div>

						<div class="push30"></div>

						<table class="table">

							<tfoot>
								<tr>
									<th colspan="100" style="text-align: justify;">
										<ul class="pagination pagination-sm">
											<?php
											for ($i = 1; $i < $numPaginas + 1; $i++) {
												if ($pagina == $i) {
													$paginaAtiva = "active";
												} else {
													$paginaAtiva = "";
												}
												echo "<li class='pagination $paginaAtiva'><a href='{$_SERVER['PHP_SELF']}?mod=" . fnEncode(1181) . "&id=" . fnEncode($cod_empresa) . "&pagina=$i' style='text-decoration: none;'>" . $i . "</a></li>";
											}
											?></ul>
									</th>
								</tr>
							</tfoot>

						</table>

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

<div id="nomeModal" style="display: none"></div>
<div id="codFiltro" style="display: none"></div>
<div id="nomeFiltro" style="display: none"></div>

<script type="text/javascript">
	$(document).ready(function() {

		//Seta quantidade de registros
		$('.totalResultados').text(<?php echo fnValor($total['contador'], 0); ?>);

		//modal close
		$('.modal').on('hidden.bs.modal', function() {
			if ($('#nomeModal').text().trim() != '') {
				addFiltro();
			}
		});

		//chosen
		$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
		$('#formulario').validator();

		$('.filtroDescricao').keypress(function(e) {
			if (e.which == 13) {

				var tag = '<a href="javascript:;" class="tagFiltro" tipo="descricao">' + $(this).val() + ' &nbsp;<i class="fal fa-times"></i></a>';
				$('.tags').append(tag);

				getAjaxProdutosGestao();
				$('.filtroDescricao').val('');

			}
		});

		$("body").on("click", ".tagFiltro", function() {
			$(this).remove();
			getAjaxProdutosGestao();
		});

		$(".addFiltro").click(function() {


			if ($(this).attr('tipo') == 'persona' || $(this).attr('tipo') == 'campanha') {
				var tag = '<a href="javascript:;" class="tagFiltro" tipo="' + $(this).attr('tipo') + '">' + $(this).html() + ' &nbsp;<i class="fal fa-times"></i></a>';
			} else {
				var tag = '<a href="javascript:;" class="tagFiltro" tipo="' + $(this).attr('tipo') + '" codigo="' + $(this).attr('codigo') + '">' + $(this).html() + ' &nbsp;<i class="fal fa-times"></i></a>';
			}
			$('.tags').append(tag);

			getAjaxProdutosGestao();

		});
	});

	function mostrarFilho(pCodProduto) {
		var idDiv = $('#conteudoTable_' + pCodProduto);

		if (!idDiv.is(':visible')) {
			$.ajax({
				type: "GET",
				url: "ajxProdutosGestaoConteudo.do",
				data: {
					codEmpresa: <?php echo $cod_empresa; ?>,
					codProduto: pCodProduto
				},
				beforeSend: function() {
					$('#conteudoProduto_' + pCodProduto).html('<div class="loading" style="width: 100%;"></div>');
				},
				success: function(data) {
					$("#conteudoProduto_" + pCodProduto).html(data);
				},
				error: function() {
					$('#conteudoProduto_' + pCodProduto).html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
				}
			});

			idDiv.show();
			$('.mostrarProduto_' + pCodProduto).find($(".fa")).removeClass('fa-angle-righ').addClass('fa-angle-down');
		} else {
			idDiv.hide();
			$('.mostrarProduto_' + pCodProduto).find($(".fa")).removeClass('fa-angle-down').addClass('fa-angle-righ');
		}
	}

	// ajax
	$("#COD_CATEGOR").change(function() {
		var codBusca = $("#COD_CATEGOR").val()
		var codBusca3 = $("#COD_EMPRESA").val();
		buscaSubCat(codBusca, 0, codBusca3);
	});

	function getAjaxProdutosGestao() {
		var pCategoria = "";
		var pSubCategoria = "";
		var pFornecedor = "";
		var pDescricao = "";
		var pPersona = false;
		var pCampanha = false;

		$(".tags a").each(function(index) {
			if ($(this).attr('tipo') == 'categoria') {
				pCategoria += $(this).attr('codigo') + ',';
			} else if ($(this).attr('tipo') == 'subcategoria') {
				pSubCategoria += $(this).attr('codigo') + ',';
			} else if ($(this).attr('tipo') == 'fornecedor') {
				pFornecedor += $(this).attr('codigo') + ',';
			} else if ($(this).attr('tipo') == 'descricao') {
				pDescricao = $(this).text().trim();
			} else if ($(this).attr('tipo') == 'persona') {
				pPersona = true;
			} else if ($(this).attr('tipo') == 'campanha') {
				pCampanha = true;
			}
		});

		pCategoria = pCategoria.slice(0, -1);
		pSubCategoria = pSubCategoria.slice(0, -1);
		pFornecedor = pFornecedor.slice(0, -1);

		$.ajax({
			type: "GET",
			url: "ajxProdutosGestao.do",
			data: {
				codEmpresa: <?php echo $cod_empresa ?>,
				persona: pPersona,
				campanha: pCampanha,
				categoria: pCategoria,
				subcategoria: pSubCategoria,
				fornecedor: pFornecedor,
				descricao: pDescricao,
				descricaoCodigo: $.isNumeric(pDescricao) ? 'number' : 'string'
			},
			beforeSend: function() {
				$('#listaProdutosFiltro').html('<div class="loading" style="width: 100%;"></div>');
			},
			success: function(data) {
				$("#listaProdutosFiltro").html(data);
				$('.totalResultados').text($('.totalResultadosAjx').text());
				$('[data-toggle="tooltip"]').tooltip();
				console.log(data);
			},
			error: function() {
				$('#listaProdutosFiltro').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
				console.log(data);
			}
		});
	}

	function addFiltro() {
		var tag = '<a href="javascript:;" class="tagFiltro" tipo="' + $('#nomeModal').text() + '" codigo="' + $('#codFiltro').text() + '">' + $('#nomeFiltro').text() + ' &nbsp;<i class="fal fa-times"></i></a>';
		$('.tags').append(tag);

		getAjaxProdutosGestao();
		$('#nomeModal').text('');
		$('#codFiltro').text('');
		$('#nomeFiltro').text('');
	}

	function atualizaPaginacao(novaPagina) {
		getAjaxProdutosGestao();
	}

	function buscaSubCat(idCat, idSub, idEmp) {
		$.ajax({
			type: "GET",
			url: "ajxBuscaSubGrupo.do",
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
			},
			error: function() {
				$('#divId_sub').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
			}
		});
	}

	function retornaForm(index) {
		$("#formulario #COD_PRODUTO").val($("#ret_COD_PRODUTO_" + index).val());
		$("#formulario #COD_EXTERNO").val($("#ret_COD_EXTERNO_" + index).val());
		$("#formulario #DES_PRODUTO").val($("#ret_DES_PRODUTO_" + index).val());
		$("#formulario #COD_CATEGOR").val($("#ret_COD_CATEGOR_" + index).val()).trigger("chosen:updated");
		$("#formulario #COD_FORNECEDOR").val($("#ret_COD_FORNECEDOR_" + index).val()).trigger("chosen:updated");

		var codCat = $("#ret_COD_CATEGOR_" + index).val();
		var codSub = $("#ret_COD_SUBCATE_" + index).val();
		buscaSubCat(codCat, codSub, <?php echo $cod_empresa; ?>);

		$("#formulario #ATRIBUTO1").val($("#ret_ATRIBUTO1_" + index).val());
		$("#formulario #ATRIBUTO2").val($("#ret_ATRIBUTO2_" + index).val());
		$("#formulario #ATRIBUTO3").val($("#ret_ATRIBUTO3_" + index).val());
		$("#formulario #ATRIBUTO4").val($("#ret_ATRIBUTO4_" + index).val());
		$("#formulario #ATRIBUTO5").val($("#ret_ATRIBUTO5_" + index).val());
		$("#formulario #ATRIBUTO6").val($("#ret_ATRIBUTO6_" + index).val());
		$("#formulario #ATRIBUTO7").val($("#ret_ATRIBUTO7_" + index).val());
		$("#formulario #ATRIBUTO8").val($("#ret_ATRIBUTO8_" + index).val());
		$("#formulario #ATRIBUTO9").val($("#ret_ATRIBUTO9_" + index).val());
		$("#formulario #ATRIBUTO10").val($("#ret_ATRIBUTO10_" + index).val());
		$("#formulario #ATRIBUTO11").val($("#ret_ATRIBUTO11_" + index).val());
		$("#formulario #ATRIBUTO12").val($("#ret_ATRIBUTO12_" + index).val());
		$("#formulario #ATRIBUTO13").val($("#ret_ATRIBUTO13_" + index).val());
		$("#formulario #DES_IMAGEM").val($("#ret_DES_IMAGEM_" + index).val());
		$("#formulario #COD_EAN").val($("#ret_COD_EAN_" + index).val());

		if ($("#ret_LOG_PRODPBM_" + index).val() == 'S') {
			$('#formulario #LOG_PRODPBM').prop('checked', true);
		} else {
			$('#formulario #LOG_PRODPBM').prop('checked', false);
		}

		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
	}

	$('.upload').on('click', function(e) {
		var idField = 'arqUpload_' + $(this).attr('idinput');
		var typeFile = $(this).attr('extensao');

		$.dialog({
			title: 'Arquivo',
			content: '' +
				'<form method = "POST" enctype = "multipart/form-data">' +
				'<input id="' + idField + '" type="file" name="image" style="margin-bottom: 20px;" />' +
				'<div class="progress" style="display: none">' +
				'<div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width:0%;">' +
				'   <span style="position: absolute; display: block; width: 100%; color:#2c3e50;">12</span></div>' +
				'</div>' +
				'<a type="button" id="btnUploadFile" class="btn btn-primary btn-sm" style="font-weight: bold" onClick="uploadFile(\'' + idField + '\', \'' + typeFile + '\')">UPLOAD</a>' +
				'</form>'
		});
	});

	function uploadFile(idField, typeFile) {
		var formData = new FormData();
		var nomeArquivo = $('#' + idField)[0].files[0]['name'];

		formData.append('arquivo', $('#' + idField)[0].files[0]);
		formData.append('diretorio', '../media/clientes/');
		formData.append('diretorioAdicional', 'produtos');
		formData.append('id', <?php echo $cod_empresa ?>);
		formData.append('typeFile', typeFile);

		$('.progress').show();
		$.ajax({
			xhr: function() {
				var xhr = new window.XMLHttpRequest();
				$('#btnUploadFile').addClass('disabled');
				xhr.upload.addEventListener("progress", function(evt) {
					if (evt.lengthComputable) {
						var percentComplete = evt.loaded / evt.total;
						percentComplete = parseInt(percentComplete * 100);
						if (percentComplete !== 100) {
							$('.progress-bar').css('width', percentComplete + "%");
							$('.progress-bar > span').html(percentComplete + "%");
						}
					}
				}, false);
				return xhr;
			},
			url: '../uploads/uploaddoc.do',
			type: 'POST',
			data: formData,
			processData: false, // tell jQuery not to process the data
			contentType: false, // tell jQuery not to set contentType
			success: function(data) {
				$('.jconfirm-open').fadeOut(300, function() {
					$(this).remove();
				});
				if (!data.trim()) {
					$('#' + idField.replace("arqUpload_", "")).val(nomeArquivo);
					$.alert({
						title: "Mensagem",
						content: "Upload feito com sucesso",
						type: 'green'
					});

				} else {
					$.alert({
						title: "Erro ao efetuar o upload",
						content: data,
						type: 'red'
					});
				}
			}
		});
	}
</script>