<?php

if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}

$hashLocal = "";
$check_HEXC = "";
$check_PONTUAR = "";
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
$sku = "";
$url_img_prod = "";
$log_prodpbm = "";
$log_habitexc = "";
$log_nresgate = "";
$log_pontuar = "";
$filtro = "";
$val_pesquisa = "";
$hHabilitado = "";
$hashForm = "";
$cod_usucada = "";
$sqlCod = "";
$arrayCod = [];
$qrCod = "";
$sqlRes = "";
$result = "";
$linhas = "";
$arrayQuery = [];
$qrLista = "";
$cod_blklist = "";
$produto = "";
$qrBuscaEmpresa = [];
$nom_empresa = "";
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
$produtoGestao = "";
$sql2 = "";
$arrayQuery2 = [];
$qrProdutoUnico = "";
$ean = "";
$check_BPM = "";
$check_NRESGATE = "";
$CarregaMaster = "";
$popUp = "";
$abaEmpresa = "";
$qrListaCategoria = "";
$blocoAtributo = "";
$row = "";
$i = 0;
$qrAttr = "";
$esconde = "";
$pesquisa = "";
$andExternoTkt = "";
$andProduto = "";
$andExterno = "";
$andFiltro = "";
$sqlCat = "";
$arrayCat = [];
$qrCat = "";
$retorno = "";
$inicio = "";
$qrListaProduto = "";
$mostraDES_IMAGEM = "";
$content = "";


// definir o numero de itens por pagina
$itens_por_pagina = 50;
$pagina = "1";

$hashLocal = mt_rand();
$check_HEXC = '';
$check_PONTUAR = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request'] = $request;

		$cod_empresa = fnLimpacampoZero(@$_REQUEST['COD_EMPRESA']);
		$cod_produto = fnLimpacampoZero(@$_REQUEST['COD_PRODUTO']);
		$cod_categor = fnLimpacampoZero(@$_REQUEST['COD_CATEGOR']);
		$cod_subcate = fnLimpacampoZero(@$_REQUEST['COD_SUBCATE']);
		$cod_externo = fnLimpacampo(@$_REQUEST['COD_EXTERNO']);
		$cod_fornecedor = fnLimpacampoZero(@$_REQUEST['COD_FORNECEDOR']);
		$cod_ean = fnLimpacampo(@$_REQUEST['COD_EAN']);
		$des_produto = fnLimpacampo(@$_REQUEST['DES_PRODUTO']);
		$atributo1 = fnLimpaCampoZero(@$_REQUEST['ATRIBUTO1']);
		$atributo2 = fnLimpaCampoZero(@$_REQUEST['ATRIBUTO2']);
		$atributo3 = fnLimpaCampoZero(@$_REQUEST['ATRIBUTO3']);
		$atributo4 = fnLimpaCampoZero(@$_REQUEST['ATRIBUTO4']);
		$atributo5 = fnLimpaCampoZero(@$_REQUEST['ATRIBUTO5']);
		$atributo6 = fnLimpaCampoZero(@$_REQUEST['ATRIBUTO6']);
		$atributo7 = fnLimpaCampoZero(@$_REQUEST['ATRIBUTO7']);
		$atributo8 = fnLimpaCampoZero(@$_REQUEST['ATRIBUTO8']);
		$atributo9 = fnLimpaCampoZero(@$_REQUEST['ATRIBUTO9']);
		$atributo10 = fnLimpaCampoZero(@$_REQUEST['ATRIBUTO10']);
		$atributo11 = fnLimpaCampoZero(@$_REQUEST['ATRIBUTO11']);
		$atributo12 = fnLimpaCampoZero(@$_REQUEST['ATRIBUTO12']);
		$atributo13 = fnLimpaCampoZero(@$_REQUEST['ATRIBUTO13']);
		$des_imagem = fnLimpacampo(@$_REQUEST['DES_IMAGEM']);
		$sku = fnLimpacampo(@$_REQUEST['SKU']);
		$url_img_prod = fnLimpacampo(@$_REQUEST['URL_IMG_PROD']);
		if (empty(@$_REQUEST['LOG_PRODPBM'])) {
			$log_prodpbm = 'N';
		} else {
			$log_prodpbm = "S";
		}
		if (empty(@$_REQUEST['LOG_HABITEXC'])) {
			$log_habitexc = 'N';
		} else {
			$log_habitexc = "S";
		}
		if (empty(@$_REQUEST['LOG_NRESGATE'])) {
			$log_nresgate = 'N';
		} else {
			$log_nresgate = "S";
		}
		if (empty(@$_REQUEST['LOG_PONTUAR'])) {
			$log_pontuar = '0';
		} else {
			$log_pontuar = "1";
		}

		$filtro = fnLimpaCampo(@$_REQUEST['VAL_PESQUISA']);
		$val_pesquisa = fnLimpaCampo(@$_REQUEST['INPUT']);
		unset($_GET['idP']);

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
			'" . $log_habitexc . "',
			'" . $log_pontuar . "',
			'" . $log_nresgate . "',
			'" . $sku . "',
			'" . $url_img_prod . "',
			'" . $opcao . "'   
		); ";

			//fnEscreve($sql);
			//fnTesteSql(connTemp($cod_empresa,""),$sql);
			mysqli_query(connTemp($cod_empresa, ""), trim($sql));

			if ($opcao == 'CAD') {

				if ($log_nresgate == 'S') {

					$sqlCod = "SELECT COD_PRODUTO FROM PRODUTOCLIENTE
				WHERE COD_EMPRESA = $cod_empresa
				AND COD_USUCADA = $cod_usucada
				ORDER BY 1 DESC
				LIMIT 1";

					// fnEscreve($sqlCod);

					$arrayCod = mysqli_query(connTemp($cod_empresa, ""), $sqlCod);

					$qrCod = mysqli_fetch_assoc($arrayCod);

					$sqlRes = "INSERT INTO PRODUTO_SEM_RESGATE(
					COD_EMPRESA,
					COD_PRODUTO,
					COD_USUCADA
					) VALUES(
					$cod_empresa,
					$qrCod[COD_PRODUTO],
					$cod_usucada
				)";

					// fnEscreve($sqlRes);

					mysqli_query(connTemp($cod_empresa, ""), trim($sqlRes));
				}
			} else if ($opcao == 'ALT') {

				if ($log_nresgate == 'S') {
					$sqlRes = "INSERT INTO PRODUTO_SEM_RESGATE(
						COD_EMPRESA,
						COD_PRODUTO,
						COD_USUCADA
						) VALUES(
						$cod_empresa,
						$cod_produto,
						$cod_usucada
					)";
				} else {
					$sqlRes = "DELETE FROM PRODUTO_SEM_RESGATE 
						WHERE COD_EMPRESA = $cod_empresa 
						AND COD_PRODUTO = $cod_produto";
				}

				mysqli_query(connTemp($cod_empresa, ""), trim($sqlRes));
			}

			if ($log_habitexc == 'S') {
				$sql = "SELECT * FROM BLACKLISTTKT WHERE TIP_BLKLIST = 'PRD' AND COD_EMPRESA = $cod_empresa AND COD_EXCLUSA = 0";
				$result = mysqli_query(connTemp($cod_empresa, ""), trim($sql));
				$linhas = mysqli_num_rows($result);

				if ($linhas == 0) {
					$sql = "INSERT INTO BLACKLISTTKT (TIP_BLKLIST, NOM_BLKLIST, ABV_BLKLIST, COD_USUCADA, COD_EMPRESA) VALUES ('PRD', 'Black List por Produtos (Automática)', 'Produtos', $cod_usucada, $cod_empresa);";
					$result = mysqli_query(connTemp($cod_empresa, ""), trim($sql));

					$sql = "SELECT * FROM BLACKLISTTKT WHERE TIP_BLKLIST = 'PRD' AND COD_EMPRESA = $cod_empresa AND COD_EXCLUSA = 0 LIMIT 1";
					$arrayQuery = mysqli_query(connTemp($cod_empresa, ""), trim($sql));

					while ($qrLista = mysqli_fetch_assoc($arrayQuery)) {
						$cod_blklist = $qrLista['COD_BLKLIST'];
						$sql = "INSERT INTO BLACKLISTTKTPROD (COD_PRODUTO, COD_BLKLIST, COD_USUCADA, COD_EMPRESA) VALUES ($cod_produto, $cod_blklist, $cod_usucada, $cod_empresa);";
						mysqli_query(connTemp($cod_empresa, ""), trim($sql));
					}
				} else {
					$sql = "SELECT * FROM BLACKLISTTKT WHERE TIP_BLKLIST = 'PRD' AND COD_EMPRESA = $cod_empresa AND COD_EXCLUSA = 0 LIMIT 1";
					$arrayQuery = mysqli_query(connTemp($cod_empresa, ""), trim($sql));

					while ($qrLista = mysqli_fetch_assoc($arrayQuery)) {
						$cod_blklist = $qrLista['COD_BLKLIST'];
						$sql = "INSERT INTO BLACKLISTTKTPROD (COD_PRODUTO, COD_BLKLIST, COD_USUCADA, COD_EMPRESA) VALUES ($cod_produto, $cod_blklist, $cod_usucada, $cod_empresa);";
						mysqli_query(connTemp($cod_empresa, ""), trim($sql));
					}
				}
			} else if ($log_habitexc == 'N') {
				$sql = "SELECT * FROM BLACKLISTTKT WHERE TIP_BLKLIST = 'PRD' AND COD_EMPRESA = $cod_empresa AND COD_EXCLUSA = 0 LIMIT 1";
				$arrayQuery = mysqli_query(connTemp($cod_empresa, ""), trim($sql));

				while ($qrLista = mysqli_fetch_assoc($arrayQuery)) {
					$cod_blklist = $qrLista['COD_BLKLIST'];
					$sql = "DELETE FROM BLACKLISTTKTPROD WHERE COD_PRODUTO = $cod_produto AND COD_BLKLIST = $cod_blklist AND COD_EMPRESA = $cod_empresa;";
					mysqli_query(connTemp($cod_empresa, ""), trim($sql));
				}
			}

			//mensagem de retorno
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
			}
			$msgTipo = 'alert-success';
		}
	}
}

//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode(@$_GET['id']);
	$produto = fnDecode(@$_GET['idP']);
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
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
		$lblAtributo1 = @$qrBuscaEmpresa['ATRIBUTO1'];
		$lblAtributo2 = @$qrBuscaEmpresa['ATRIBUTO2'];
		$lblAtributo3 = @$qrBuscaEmpresa['ATRIBUTO3'];
		$lblAtributo4 = @$qrBuscaEmpresa['ATRIBUTO4'];
		$lblAtributo5 = @$qrBuscaEmpresa['ATRIBUTO5'];
		$lblAtributo6 = @$qrBuscaEmpresa['ATRIBUTO6'];
		$lblAtributo7 = @$qrBuscaEmpresa['ATRIBUTO7'];
		$lblAtributo8 = @$qrBuscaEmpresa['ATRIBUTO8'];
		$lblAtributo9 = @$qrBuscaEmpresa['ATRIBUTO9'];
		$lblAtributo10 = @$qrBuscaEmpresa['ATRIBUTO10'];
		$lblAtributo11 = @$qrBuscaEmpresa['ATRIBUTO11'];
		$lblAtributo12 = @$qrBuscaEmpresa['ATRIBUTO12'];
		$lblAtributo13 = @$qrBuscaEmpresa['ATRIBUTO13'];
	}
} else {
	$cod_empresa = 0;
	//fnEscreve('entrou else');
}

if (@$opcao != '') {
	$produto = $cod_produto;
} else {
	$produto = $produto;
}

//////////////////////// produtos/prod. gestão de ofertas  /////////////////////////////////////
$produtoGestao = fnDecode(@$_GET['idPrd']);
if (isset($produtoGestao) && fnDecode(@$_GET['mod']) == 1194) {
	//fnEscreve("tem gestão");		

	if ($produtoGestao != "0") {
		$sql2 = "select A.* from PRODUTOCLIENTE A 
					where A.COD_EMPRESA = $cod_empresa
					AND A.COD_PRODUTO = $produtoGestao
					AND A.COD_EXCLUSA=0 order by A.DES_PRODUTO ";
		//fnEscreve($sql);
		$arrayQuery2 = mysqli_query(connTemp($cod_empresa, ""), $sql2);
		$qrProdutoUnico = mysqli_fetch_assoc($arrayQuery2);

		$cod_produto = $qrProdutoUnico['COD_PRODUTO'];
		$cod_externo = $qrProdutoUnico['COD_EXTERNO'];
		$des_produto = $qrProdutoUnico['DES_PRODUTO'];
		$cod_categor = $qrProdutoUnico['COD_CATEGOR'];
		$cod_subcate = $qrProdutoUnico['COD_SUBCATE'];
		$cod_fornecedor = $qrProdutoUnico['COD_FORNECEDOR'];
		$ean = $qrProdutoUnico['EAN'];
		$atributo1 = $qrProdutoUnico['ATRIBUTO1'];
		$atributo2 = $qrProdutoUnico['ATRIBUTO2'];
		$atributo3 = $qrProdutoUnico['ATRIBUTO3'];
		$atributo4 = $qrProdutoUnico['ATRIBUTO4'];
		$atributo5 = $qrProdutoUnico['ATRIBUTO5'];
		$atributo6 = $qrProdutoUnico['ATRIBUTO6'];
		$atributo7 = $qrProdutoUnico['ATRIBUTO7'];
		$atributo8 = $qrProdutoUnico['ATRIBUTO8'];
		$atributo9 = $qrProdutoUnico['ATRIBUTO9'];
		$atributo10 = $qrProdutoUnico['ATRIBUTO10'];
		$atributo11 = $qrProdutoUnico['ATRIBUTO11'];
		$atributo12 = $qrProdutoUnico['ATRIBUTO12'];
		$atributo13 = $qrProdutoUnico['ATRIBUTO13'];
		$des_imagem = fnBase64DecodeImg($qrProdutoUnico['DES_IMAGEM']);
		$sku = $qrProdutoUnico['SKU'];
		$url_img_prod = $qrProdutoUnico['URL_IMG_PROD'];


		if ($qrProdutoUnico['LOG_PRODPBM'] == "N") {
			$check_BPM = '';
		} else {
			$check_BPM = "checked";
		}

		if ($qrProdutoUnico['LOG_HABITEXC'] == "N") {
			$check_HEXC = '';
		} else {
			$check_HEXC = "checked";
		}

		if ($qrProdutoUnico['LOG_NRESGATE'] == "N") {
			$check_NRESGATE = '';
		} else {
			$check_NRESGATE = "checked";
		}

		if ($qrProdutoUnico['LOG_PONTUAR'] == "1") {
			$check_PONTUAR = 'checked';
		} else {
			$check_PONTUAR = '';
		}
	}
} else {
	//fnEscreve("não tem gestão");		
	$cod_produto = "";
	$cod_externo = "";
	$des_produto = "";
	$cod_categor = "";
	$cod_subcate = "";
	$cod_fornecedor = "";
	$ean = "";
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
	$sku = "";
	$url_img_prod = "";
	$check_BPM = "";
	$check_PONTUAR = "";
	$check_HEXC = "";
	$check_NRESGATE = '';
}


//fnMostraForm();
//fnEscreve($cod_empresa);
//fnEscreve(fnDecode(@$_GET['idPrd']));

if (fnAutMaster($_SESSION["SYS_COD_TPUSUARIO"], $_SESSION["SYS_COD_EMPRESA"]) == '1') {
	$CarregaMaster = '1';
} else {
	$CarregaMaster = '0';
}
?>


<?php if ($popUp != "true") { ?>
	<div class="push30"></div>
<?php } ?>

<div class="row">

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<?php if ($popUp != "true") { ?>
			<div class="portlet portlet-bordered">
			<?php } else { ?>
				<div class="portlet" style="padding: 0 20px 20px 20px;">
				<?php } ?>

				<?php if ($popUp != "true") { ?>
					<div class="portlet-title">
						<div class="caption">
							<i class="fal fa-terminal"></i>
							<span class="text-primary">
								<?php echo $NomePg; ?>
						</div>
						<?php include "atalhosPortlet.php"; ?>
					</div>
				<?php } ?>

				<div class="portlet-body">

					<?php if ($msgRetorno <> '') { ?>
						<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert"
							id="msgRetorno">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
									aria-hidden="true">&times;</span></button>
							<?php echo $msgRetorno; ?>
						</div>
					<?php } ?>

					<?php
					//menu superior - empresas
					$abaEmpresa = 1046;
					switch ($_SESSION["SYS_COD_SISTEMA"]) {
						case 14: //rede duque
							include "abasEmpresaDuque.php";
							break;
						case 15: //quiz
							include "abasEmpresaQuiz.php";
							break;
						default;
							if (fnDecode(@$_GET['mod']) != 1194) {
								include "abasProdutosConfig.php";
							}
							break;
					}
					?>

					<?php if ($popUp != "true") { ?>
						<div class="push30"></div>
					<?php } ?>

					<div class="login-form">

						<form data-toggle="validator" role="form2" method="post" id="formulario"
							action="<?php echo $cmdPage; ?>">

							<fieldset>
								<legend>Geral</legend>
								<div class="row">

									<div class="col-md-1">
										<div class="form-group">
											<label for="inputName" class="control-label required">Código</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly"
												name="COD_PRODUTO" id="COD_PRODUTO" value="<?= $cod_empresa; ?>">
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Empresa</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly"
												name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?= $nom_empresa; ?>">
											<input type="hidden" class="form-control input-sm" name="COD_EMPRESA"
												id="COD_EMPRESA" value="<?= $cod_empresa; ?>">
										</div>
									</div>

									<div class="col-md-6">
										<div class="form-group">
											<label for="inputName" class="control-label required">Nome do
												Produto</label>
											<input type="text" class="form-control input-sm" name="DES_PRODUTO"
												id="DES_PRODUTO" value="<?= $des_produto; ?>" maxlength="50"
												data-error="Campo obrigatório" required>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label required">Código Externo</label>
											<input type="text" class="form-control input-sm" name="COD_EXTERNO"
												id="COD_EXTERNO" value="<?= $cod_externo; ?>" maxlength="50"
												data-error="Campo obrigatório" required>
											<div class="help-block with-errors"></div>
										</div>
									</div>

								</div>

								<div class="row">

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Código EAN</label>
											<input type="text" class="form-control input-sm" name="COD_EAN" id="COD_EAN"
												value="<?= $ean; ?>" maxlength="20" data-error="Campo obrigatório">
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Grupo do Produto</label>
											<select data-placeholder="Selecione o grupo" name="COD_CATEGOR"
												id="COD_CATEGOR" class="chosen-select-deselect">
												<option value="">&nbsp;</option>
												<?php
												$sql = "select * from CATEGORIA where COD_EMPRESA = $cod_empresa AND (COD_EXCLUSA is null OR COD_EXCLUSA =0) order by DES_CATEGOR";
												$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

												while ($qrListaCategoria = mysqli_fetch_assoc($arrayQuery)) {
													echo "
											<option value='" . $qrListaCategoria['COD_CATEGOR'] . "'>" . $qrListaCategoria['COD_CATEGOR'] . " - " . $qrListaCategoria['DES_CATEGOR'] . "</option> 
											";
												}
												?>
											</select>
											<script>
												$("#COD_CATEGOR").val("<?php echo $cod_categor; ?>").trigger(
													"chosen:updated");
											</script>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Sub Grupo do Produto</label>
											<div id="divId_sub">
												<select data-placeholder="Selecione o sub grupo" name="COD_SUBCATE"
													id="COD_SUBCATE" class="chosen-select-deselect">
													<option value="">&nbsp;</option>
												</select>
											</div>
											<script>

											</script>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Fornecedor</label>
											<select data-placeholder="Selecione o grupo" name="COD_FORNECEDOR"
												id="COD_FORNECEDOR" class="chosen-select-deselect">
												<option value="">&nbsp;</option>
												<?php
												$sql = "SELECT * FROM FORNECEDORMRKA WHERE COD_EMPRESA = $cod_empresa ORDER BY NOM_FORNECEDOR";
												$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

												while ($qrListaCategoria = mysqli_fetch_assoc($arrayQuery)) {
													echo "
										<option value='" . $qrListaCategoria['COD_FORNECEDOR'] . "'>" . $qrListaCategoria['COD_FORNECEDOR'] . " - " . $qrListaCategoria['NOM_FORNECEDOR'] . "</option> 
										";
												}
												?>
											</select>
											<script>
												$("#COD_FORNECEDOR").val("<?php echo $cod_fornecedor; ?>").trigger(
													"chosen:updated");
											</script>
											<div class="help-block with-errors"></div>
										</div>
									</div>

								</div>

								<div class="row">

									<div class="col-md-1">
										<div class="form-group">
											<label for="inputName" class="control-label">Produto PBM</label>
											<div class="push5"></div>
											<label class="switch switch-small">
												<input type="checkbox" name="LOG_PRODPBM" id="LOG_PRODPBM"
													class="switch" value="S" <?= $check_BPM; ?>>
												<span></span>
											</label>
										</div>
									</div>

									<div class="col-md-1">
										<div class="form-group">
											<label for="inputName" class="control-label">Não Pontuar </label>
											<div class="push5"></div>
											<label class="switch switch-small">
												<input type="checkbox" name="LOG_PONTUAR" id="LOG_PONTUAR"
													class="switch" value="1" <?= $check_PONTUAR; ?>>
												<span></span>
											</label>
										</div>
									</div>

									<div class="col-md-1">
										<div class="form-group">
											<label for="inputName" class="control-label">Sem Resgate</label>
											<div class="push5"></div>
											<label class="switch switch-small">
												<input type="checkbox" name="LOG_NRESGATE" id="LOG_NRESGATE"
													class="switch" value="S" <?= $check_NRESGATE; ?>>
												<span></span>
											</label>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Hábito de Exclusão</label>
											<div class="push5"></div>
											<label class="switch switch-small">
												<input type="checkbox" name="LOG_HABITEXC" id="LOG_HABITEXC"
													class="switch" value="S" <?= $check_HEXC; ?>>
												<span></span>
											</label>
										</div>
									</div>

									<div class="col-md-7">
										<div class="form-group">
											<label for="inputName" class="control-label">Imagem</label>
											<div class="input-group">
												<span class="input-group-btn">
													<a type="button" name="btnBusca" id="btnBusca" style="height:35px;"
														class="btn btn-primary upload" idinput="DES_IMAGEM"
														extensao="img"><i class="fa fa-cloud-upload"
															aria-hidden="true"></i></a>
												</span>
												<input type="hidden" name="DES_IMAGEM" id="DES_IMAGEM"
													value="<?php echo $des_imagem; ?>">

												<input type="text" name="IMAGEM" id="IMAGEM"
													class="form-control input-sm" style="border-radius: 0 3px 3px  0;"
													value="<?php echo $des_imagem; ?>">
											</div>
											<span class="help-block">(.jpg, .png 500px X 500px)</span>
										</div>
									</div>
								</div>

								<div class="row">

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">SKU</label>
											<input type="text" class="form-control input-sm" name="SKU" id="SKU"
												value="<?= $sku; ?>" maxlength="50">
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-6">
										<div class="form-group">
											<label for="inputName" class="control-label">Link E-commerce</label>
											<input type="text" class="form-control input-sm" name="URL_IMG_PROD"
												id="URL_IMG_PROD" value="<?= $url_img_prod; ?>" maxlength="1000">
											<div class="help-block with-errors"></div>
										</div>
									</div>
								</div>

							</fieldset>

							<?php if (empty($lblAtributo1) == 1) {
								$blocoAtributo = "none";
							} else {
								$blocoAtributo = "block";
							}
							$blocoAtributo = 'block'; ?>
							<div style="display:<?php echo $blocoAtributo; ?>;">
								<div class="push10"></div>
								<fieldset>
									<legend>Atributos</legend>

									<div class="row">

										<?php

										$row = 0;

										for ($i = 1; $i <= 13; $i++) {
										?>

											<div class="col-md-3">
												<div class="form-group">
													<label for="inputName" class="control-label">Atributo
														<?= $i ?>
													</label>
													<select data-placeholder="Selecione o atributo" name="ATRIBUTO<?= $i ?>"
														id="ATRIBUTO<?= $i ?>" class="chosen-select-deselect">
														<option value=""></option>
														<?php
														$sql = "SELECT COD_PARAMETRO, DES_PARAMETRO FROM PARAMETRO" . $i . " WHERE COD_EMPRESA = $cod_empresa ORDER BY DES_PARAMETRO";
														$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

														while ($qrAttr = mysqli_fetch_assoc($arrayQuery)) {
															echo "
													<option value='" . $qrAttr['COD_PARAMETRO'] . "'>" . $qrAttr['DES_PARAMETRO'] . "</option> 
													";
														}
														?>
													</select>
													<div class="help-block with-errors"></div>
												</div>
											</div>

										<?php

											if ($row == 3) {
												echo "</div>
										<div class='row'>";
												$row = 0;
											} else {
												$row++;
											}
										}

										?>

									</div>

								</fieldset>
							</div>

							<div class="push10"></div>
							<hr>
							<div class="form-group text-right col-lg-12">


								<?php if (fnDecode(@$_GET['mod']) == 1046) { ?>
									<button type="reset" class="btn btn-default"><i class="fal fa-eraser"
											aria-hidden="true"></i>&nbsp; Apagar</button>
									<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i
											class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
									<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i
											class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>
									<?php if ($CarregaMaster == 1) { ?>
										<button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i
												class="fal fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>
									<?php } ?>
									<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">
								<?php } else { ?>
									<button type="reset" class="btn btn-default"><i class="fal fa-eraser"
											aria-hidden="true"></i>&nbsp; Apagar</button>
									<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i
											class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>
									<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
								<?php } ?>

							</div>

							<input type="hidden" name="opcao" id="opcao" value="">
							<input type="hidden" name="AND_FILTRO" id="AND_FILTRO" value="">
							<input type="hidden" name="LOG_GRUPO" id="LOG_GRUPO" value="N">
							<input type="hidden" name="hashForm" id="hashForm" value="<?= $hashLocal; ?>" />

							<div class="push5"></div>

						</form>



						<style>
							.input-xs {
								height: 26px;
								padding: 2px 5px;
								font-size: 12px;
								line-height: 1.5;
								/* If Placeholder of the input is moved up, rem/modify this. */
								border-radius: 3px;
								border: 0;
							}
						</style>

						<?php
						///////////// manutenção do produto em lista - produtos/prod. gestão de ofertas ///////////////
						if (fnDecode(@$_GET['mod']) == 1046) { ?>

							<div class="row">
								<form name="formLista2" id="formLista2" method="post" action="<?= $cmdPage; ?>">

									<div class="col-xs-4">

										<div class="col-md-4">
											<div class="form-group">
												<label for="inputName" class="control-label">Trazer Somente Sem
													Grupo</label>
												<div class="push5"></div>
												<label class="switch switch-small">
													<input type="checkbox" name="MOSTRA_GRUPO" id="MOSTRA_GRUPO"
														class="switch switch-small" value="S">
													<span></span>
												</label>
											</div>
											<script>
												$(function() {

													$("#MOSTRA_GRUPO").change(function() {
														if ($(this).prop('checked')) {
															$("#LOG_GRUPO").val('S');
														} else {
															$("#LOG_GRUPO").val('N');
														}
														reloadPage(1);
													});

												});
											</script>
										</div>

									</div>

									<div class="col-xs-4">
										<div class="input-group activeItem">
											<div class="input-group-btn search-panel">
												<button type="button"
													class="btn btn-outline dropdown-toggle form-control form-control-sm rounded-left search-bar"
													id="FILTERS" data-toggle="dropdown">
													<span id="search_concept">Sem filtro</span>&nbsp;
													<span class="far fa-angle-down"></span>
												</button>
												<ul class="dropdown-menu" role="menu">
													<li class="divisor"><a class="item-filtro" href="#">Sem filtro</a></li>
													<!-- <li class="divider"></li> -->
													<li><a class="item-filtro" href="#DES_PRODUTO">Produto</a></li>
													<li><a class="item-filtro" href="#DES_PRODUTO_EQ">Produto Exato</a></li>
													<li><a class="item-filtro" href="#COD_PRODUTO">Código do produto</a>
													</li>
													<li><a class="item-filtro" href="#COD_EXTERNO">Código externo</a></li>
													<li><a class="item-filtro" href="#EAN">EAN</a></li>
													<li><a class="item-filtro" href="#COD_CATEGOR">Grupo</a></li>
													<!-- <li><a href="#CNPJ">CNPJ</a></li> -->
												</ul>
											</div>
											<input type="hidden" name="VAL_PESQUISA" value="<?= $filtro ?>" id="VAL_PESQUISA">
											<input type="text" id="INPUT"
												class="form-control form-control-sm remove-side-borders search-bar"
												name="INPUT" value="<?= $val_pesquisa ?>" onkeyup="buscaRegistro(this)">
											<div class="input-group-btn" id="CLEARDIV" style="<?= $esconde ?>">
												<button
													class="btn btn-outline form-control form-control-sm remove-side-borders search-bar"
													id="CLEAR" type="button">&nbsp;<span
														class="fal fa-times"></span></button>
											</div>
											<div class="input-group-btn">
												<button type="submit"
													class="btn btn-outline form-control form-control-sm rounded-right search-bar"
													id="SEARCH">&nbsp;<span class="fal fa-search"></span></button>
											</div>
										</div>
									</div>

									<input type="hidden" name="hashForm" id="hashForm" value="<?= $hashLocal; ?>" />
									<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

								</form>

							</div>
					</div>
				</div>
				</div>

				<div class="push20"></div>

				<div class="portlet portlet-bordered">

					<div class="portlet-body">

						<div class="login-form">

							<div class="col-lg-12">

								<div class="no-more-tables">

									<!-- <div class="col-lg-12">
												
													<form id="formBusca" action="action.do?mod=<?php //echo fnEncode(1046); 
																								?>&id=<?php //echo fnEncode($cod_empresa); 
																										?>" method="post" >

													<div class="col-md-2" style="padding: 10px;">
														<div class="form-group">
															<input type="text" class="form-control input-xs leituraOff" name="DES_PRODUTO" id="DES_PRODUTO" placeholder="Nome do produto" maxlength="50">
														</div>
													</div>
													
													<div class="col-md-2" style="padding: 10px;">
														<div class="form-group">
															<input type="text" class="form-control input-xs leituraOff" name="COD_EXTERNO" id="COD_EXTERNO" placeholder="Código externo" maxlength="20">
														</div>
													</div>

													<div class="col-md-3" style="padding: 10px;">
														<button type="submit" name="BUSCA" id="BUSCA" class="btn btn-default btn-xs"><i class="fal fa-search" aria-hidden="true"></i>&nbsp; Pesquisar</button>
														<input type="hidden" name="pesquisa" id="pesquisa" value="ON">
													</div>
													
													</form>
													
												</div> -->


									<form name="formLista">

										<table class="table table-bordered table-striped table-hover buscavel">
											<thead>
												<tr>
													<th width="40"></th>
													<th>Código</th>
													<th>Cod. Externo</th>
													<th>Cod. Lote</th>
													<th>EAN</th>
													<th>Grupo</th>
													<th>Sub Grupo</th>
													<th>Descrição</th>
													<th>Imagem</th>
												</tr>
											</thead>
											<tbody id="relatorioConteudo">


												<?php

												// //variáveis da pesquisa
												// $cod_externo = fnLimpacampo(@$_REQUEST['COD_EXTERNO']);
												// $pesquisa = fnLimpacampo(@$_REQUEST['pesquisa']);
												// $des_produto = fnLimpacampo(@$_REQUEST['DES_PRODUTO']);

												// //pesquisa no form local
												// $andExternoTkt = ' ';
												// if (empty(@$_REQUEST['pesquisa'])){
												// 	//fnEscreve("sem pesquisa");
												// 	$andProduto = ' ';
												// 	$andExterno = ' ';
												// }else{
												// 	//fnEscreve("com pesquisa");
												// 	if ($des_produto != '' && $des_produto != 0){
												// 		$andProduto = 'AND A.DES_PRODUTO like "%'.$des_produto.'%"'; }
												// 		else { $andProduto = ' ';}

												// 	if ($cod_externo != '' && $cod_externo != 0){
												// 		$andExterno = 'AND A.COD_EXTERNO = "'.$cod_externo.'"'; }
												// 		else { $andExterno = ' ';}

												// }

												if ($filtro != '') {
													if ($filtro == "EAN" || $filtro == "COD_PRODUTO") {
														$andFiltro = " AND A.$filtro = '$val_pesquisa' ";
													} else if ($filtro == "COD_CATEGOR") {
														$sqlCat = "SELECT COD_CATEGOR FROM CATEGORIA WHERE DES_CATEGOR LIKE '%$val_pesquisa%'";
														$arrayCat = mysqli_query(connTemp($cod_empresa, ''), $sqlCat);
														$cod_categor = "";
														while ($qrCat = mysqli_fetch_assoc($arrayCat)) {
															$cod_categor .= $qrCat['COD_CATEGOR'] . ",";
														}
														$cod_categor = ltrim(rtrim($cod_categor, ','), ',');
														$andFiltro = "AND B.COD_CATEGOR IN($cod_categor)";
													} else if ($filtro == "DES_PRODUTO_EQ") {
														$andFiltro = " AND A.DES_PRODUTO = '$val_pesquisa' ";
													} else {
														$andFiltro = " AND A.$filtro LIKE '%$val_pesquisa%' ";
													}
												} else {
													$andFiltro = " ";
												}


												//se pesquisa dos produtos do ticket
												if (!empty(@$_GET['idP']) && @$_GET['idP'] != "") {
													$andProduto = 'AND A.COD_PRODUTO = "' . fnDecode(@$_GET['idP']) . '"';
												} else {
													$andProduto = " ";
												}

												// fnEscreve($andProduto);

												$sql = "select COUNT(*) as CONTADOR from PRODUTOCLIENTE A 
															left JOIN CATEGORIA B ON A.COD_CATEGOR = B.COD_CATEGOR AND A.COD_EMPRESA = B.COD_EMPRESA 
															left JOIN SUBCATEGORIA C ON A.COD_SUBCATE = C.COD_SUBCATE AND A.COD_EMPRESA = C.COD_EMPRESA 
															where A.COD_EMPRESA='" . $cod_empresa . "' 
															" . $andFiltro . "
															" . $andProduto . "
															AND A.COD_EXCLUSA=0 order by A.DES_PRODUTO";
												// fnEscreve($sql);

												$retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
												$total_itens_por_pagina = mysqli_fetch_assoc($retorno);

												$numPaginas = ceil($total_itens_por_pagina['CONTADOR'] / $itens_por_pagina);

												//variavel para calcular o início da visualização com base na página atual
												$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

												$sql = "SELECT A.*,B.DES_CATEGOR as GRUPO,C.DES_SUBCATE as SUBGRUPO from PRODUTOCLIENTE A 
															LEFT JOIN CATEGORIA B ON A.COD_CATEGOR = B.COD_CATEGOR AND A.COD_EMPRESA = B.COD_EMPRESA 
															LEFT JOIN SUBCATEGORIA C ON A.COD_SUBCATE = C.COD_SUBCATE AND A.COD_EMPRESA = C.COD_EMPRESA  
															where A.COD_EMPRESA='" . $cod_empresa . "' 
															" . $andFiltro . "
															" . $andProduto . " 
															AND A.COD_EXCLUSA=0 order by A.DES_PRODUTO limit $inicio,$itens_por_pagina";

												//fnEscreve($sql);
												$arrayQuery = mysqli_query(connTemp($cod_empresa, ""), $sql);

												$count = 0;
												while ($qrListaProduto = mysqli_fetch_assoc($arrayQuery)) {
													$count++;

													if ($qrListaProduto['DES_IMAGEM'] != "") {
														$mostraDES_IMAGEM = '<a href="https://img.bunker.mk/media/clientes/' . $cod_empresa . '/produtos/' . $qrListaProduto['DES_IMAGEM'] . '" target="_blank">Visualizar</a>';
													} else {
														$mostraDES_IMAGEM = '';
													}

													echo "
																<tr>
																<td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></th>
																<td>" . $qrListaProduto['COD_PRODUTO'] . "</td>
																<td>" . $qrListaProduto['COD_EXTERNO'] . "</td>
																<td>" . $qrListaProduto['COD_LOTE'] . "</td>
																<td>" . $qrListaProduto['EAN'] . "</td>
																<td>" . $qrListaProduto['GRUPO'] . "</td>
																<td>" . $qrListaProduto['SUBGRUPO'] . "</td>
																<td>" . $qrListaProduto['DES_PRODUTO'] . "</td>
																<td class='text-center'>" . $mostraDES_IMAGEM . "</td>
																</tr>
																<input type='hidden' id='ret_COD_PRODUTO_" . $count . "' value='" . $qrListaProduto['COD_PRODUTO'] . "'>  
																<input type='hidden' id='ret_COD_EXTERNO_" . $count . "' value='" . $qrListaProduto['COD_EXTERNO'] . "'>
																<input type='hidden' id='ret_DES_PRODUTO_" . $count . "' value='" . $qrListaProduto['DES_PRODUTO'] . "'>
																<input type='hidden' id='ret_COD_CATEGOR_" . $count . "' value='" . $qrListaProduto['COD_CATEGOR'] . "'>
																<input type='hidden' id='ret_COD_SUBCATE_" . $count . "' value='" . $qrListaProduto['COD_SUBCATE'] . "'>
																<input type='hidden' id='ret_COD_FORNECEDOR_" . $count . "' value='" . $qrListaProduto['COD_FORNECEDOR'] . "'>
																<input type='hidden' id='ret_COD_EAN_" . $count . "' value='" . $qrListaProduto['EAN'] . "'>
																<input type='hidden' id='ret_ATRIBUTO1_" . $count . "' value='" . $qrListaProduto['ATRIBUTO1'] . "'>
																<input type='hidden' id='ret_ATRIBUTO2_" . $count . "' value='" . $qrListaProduto['ATRIBUTO2'] . "'>
																<input type='hidden' id='ret_ATRIBUTO3_" . $count . "' value='" . $qrListaProduto['ATRIBUTO3'] . "'>
																<input type='hidden' id='ret_ATRIBUTO4_" . $count . "' value='" . $qrListaProduto['ATRIBUTO4'] . "'>
																<input type='hidden' id='ret_ATRIBUTO5_" . $count . "' value='" . $qrListaProduto['ATRIBUTO5'] . "'>
																<input type='hidden' id='ret_ATRIBUTO6_" . $count . "' value='" . $qrListaProduto['ATRIBUTO6'] . "'>
																<input type='hidden' id='ret_ATRIBUTO7_" . $count . "' value='" . $qrListaProduto['ATRIBUTO7'] . "'>
																<input type='hidden' id='ret_ATRIBUTO8_" . $count . "' value='" . $qrListaProduto['ATRIBUTO8'] . "'>
																<input type='hidden' id='ret_ATRIBUTO9_" . $count . "' value='" . $qrListaProduto['ATRIBUTO9'] . "'>
																<input type='hidden' id='ret_ATRIBUTO10_" . $count . "' value='" . $qrListaProduto['ATRIBUTO10'] . "'>
																<input type='hidden' id='ret_ATRIBUTO11_" . $count . "' value='" . $qrListaProduto['ATRIBUTO11'] . "'>
																<input type='hidden' id='ret_ATRIBUTO12_" . $count . "' value='" . $qrListaProduto['ATRIBUTO12'] . "'>
																<input type='hidden' id='ret_ATRIBUTO13_" . $count . "' value='" . $qrListaProduto['ATRIBUTO13'] . "'>
																<input type='hidden' id='ret_IMAGEM_" . $count . "' value='" . fnBase64DecodeImg($qrListaProduto['DES_IMAGEM']) . "'>
																<input type='hidden' id='ret_DES_IMAGEM_" . $count . "' value='" . $qrListaProduto['DES_IMAGEM'] . "'>
																<input type='hidden' id='ret_LOG_PRODPBM_" . $count . "' value='" . $qrListaProduto['LOG_PRODPBM'] . "'>
																<input type='hidden' id='ret_LOG_HABITEXC_" . $count . "' value='" . $qrListaProduto['LOG_HABITEXC'] . "'>
																<input type='hidden' id='ret_LOG_NRESGATE_" . $count . "' value='" . $qrListaProduto['LOG_NRESGATE'] . "'>
																<input type='hidden' id='ret_LOG_PONTUAR_" . $count . "' value='" . $qrListaProduto['LOG_PONTUAR'] . "'>
																<input type='hidden' id='ret_SKU_" . $count . "' value='" . $qrListaProduto['SKU'] . "'>
																<input type='hidden' id='ret_URL_IMG_PROD_" . $count . "' value='" . $qrListaProduto['URL_IMG_PROD'] . "'>
																";
												}
												?>

											</tbody>

											<tfoot>
												<tr>
													<th colspan="100">
														<a class="btn btn-info btn-sm exportarCSV"> <i
																class="fal fa-file-excel" aria-hidden="true"></i>&nbsp;
															Exportar</a>
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

									</form>

								</div>

							</div>

						<?php
							///////////// manutenção do produto em lista - produtos/prod. gestão de ofertas ///////////////
						} ?>

						<div class="push"></div>

						</div>

					</div>
				</div>
				<!-- fim Portlet -->
			</div>

	</div>

	<div class="push20"></div>

	<script type="text/javascript">
		//Barra de pesquisa essentials ------------------------------------------------------

		$(document).ready(function() {
			var filtroSelecionado = '<?= $filtro ?>';

			if (filtroSelecionado === '' || filtroSelecionado === 'SEM_FILTRO') {
				$('#search_concept').text('Sem filtro');
			} else {
				// Procura o texto da opção pelo href correspondente
				var textoFiltro = $('.item-filtro[href="#' + filtroSelecionado + '"]').text();

				// Se achou, altera o texto no botão
				if (textoFiltro) {
					$('#search_concept').text(textoFiltro);
				}
			}
		});
		$(document).ready(function(e) {
			var value = $('#INPUT').val().toLowerCase().trim();
			if (value) {
				$('#CLEARDIV').show();
			} else {
				$('#CLEARDIV').hide();
			}
			$('.search-panel .dropdown-menu').find('a').click(function(e) {
				e.preventDefault();
				var param = $(this).attr("href").replace("#", "");
				var concept = $(this).text();
				$('.search-panel span#search_concept').text(concept);
				$('.input-group #VAL_PESQUISA').val(param);
				$('#INPUT').focus();
			});

			$("#FILTERS, #INPUT, #SEARCH, #CLEAR").focus(function() {
				$("#FILTERS, #INPUT, #SEARCH, #CLEAR").addClass("activeItem");
			});

			$("#FILTERS, #INPUT, #SEARCH, #CLEAR").blur(function() {
				$("#FILTERS, #INPUT, #SEARCH, #CLEAR").removeClass("activeItem");
			});

			$('#CLEAR').click(function() {
				$('#INPUT').val('');
				$('#INPUT').focus();
				$('#CLEARDIV').hide();
				if ("<?= $filtro ?>" != "") {
					location.reload();
				} else {
					value = $('#INPUT').val().toLowerCase().trim();
					if (value) {
						$('#CLEARDIV').show();
					} else {
						$('#CLEARDIV').hide();
					}
					$(".buscavel tr").each(function(index) {
						if (!index) return;
						$(this).find("td").each(function() {
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

		function buscaRegistro(el) {
			var filtro = $('#search_concept').text().toLowerCase();

			if (filtro == "sem filtro") {
				var value = $(el).val().toLowerCase().trim();
				if (value) {
					$('#CLEARDIV').show();
				} else {
					$('#CLEARDIV').hide();
				}
				$(".buscavel tr").each(function(index) {
					if (!index) return;
					$(this).find("td").each(function() {
						var id = $(this).text().toLowerCase().trim();
						var sem_registro = (id.indexOf(value) == -1);
						$(this).closest('tr').toggle(!sem_registro);
						return sem_registro;
					});
				});
			}
		}

		//-----------------------------------------------------------------------------------

		$(document).ready(function() {
			$("#AND_FILTRO").val("<?= $andFiltro ?>");

			var numPaginas = <?php echo $numPaginas; ?>;
			if (numPaginas != 0) {
				carregarPaginacao(numPaginas);
			}
			//chosen
			$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
			$('#formulario').validator();

			<?php
			///////// produtos/prod. gestão de ofertas //////////
			if (fnDecode(@$_GET['mod']) == 1194) { ?>
				var codCat = <?php echo $cod_categor; ?>;
				var codSub = <?php echo $cod_subcate; ?>;
				buscaSubCat(codCat, codSub, <?php echo $cod_empresa; ?>);
			<?php } ?>

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
											url: "ajxProdutosEmpresas.do?opcao=exportar&nomeRel=" +
												nome +
												"&id=<?php echo fnEncode($cod_empresa); ?>",
											data: $('#formulario')
												.serialize(),
											method: 'POST'
										}).done(function(response) {
											self.setContentAppend(
												'<div>Exportação realizada com sucesso.</div>'
											);
											var fileName =
												'<?php echo $cod_empresa; ?>_' +
												nome + '.csv';
											SaveToDisk('media/excel/' +
												fileName, fileName);
											console.log(response);
										}).fail(function() {
											self.setContentAppend(
												'<div>Erro ao realizar o procedimento!</div>'
											);
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

		// ajax
		$("#COD_CATEGOR").change(function() {
			var codBusca = $("#COD_CATEGOR").val();
			var codBusca3 = $("#COD_EMPRESA").val();
			buscaSubCat(codBusca, 0, codBusca3);
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
					$('#divId_sub').html(
						'<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>'
					);
				}
			});
		}

		function retornaForm(index) {
			$("#formulario #COD_PRODUTO").val($("#ret_COD_PRODUTO_" + index).val());
			$("#formulario #COD_EXTERNO").val($("#ret_COD_EXTERNO_" + index).val());
			$("#formulario #DES_PRODUTO").val($("#ret_DES_PRODUTO_" + index).val());
			$("#formulario #COD_CATEGOR").val($("#ret_COD_CATEGOR_" + index).val()).trigger("chosen:updated");
			$("#formulario #COD_SUBCATE").val($("#ret_COD_SUBCATE_" + index).val()).trigger("chosen:updated");
			$("#formulario #COD_FORNECEDOR").val($("#ret_COD_FORNECEDOR_" + index).val()).trigger("chosen:updated");

			var codCat = $("#ret_COD_CATEGOR_" + index).val();
			var codSub = $("#ret_COD_SUBCATE_" + index).val();
			buscaSubCat(codCat, codSub, <?php echo $cod_empresa; ?>);

			$("#formulario #ATRIBUTO1").val($("#ret_ATRIBUTO1_" + index).val()).trigger('chosen:updated');
			$("#formulario #ATRIBUTO2").val($("#ret_ATRIBUTO2_" + index).val()).trigger('chosen:updated');
			$("#formulario #ATRIBUTO3").val($("#ret_ATRIBUTO3_" + index).val()).trigger('chosen:updated');
			$("#formulario #ATRIBUTO4").val($("#ret_ATRIBUTO4_" + index).val()).trigger('chosen:updated');
			$("#formulario #ATRIBUTO5").val($("#ret_ATRIBUTO5_" + index).val()).trigger('chosen:updated');
			$("#formulario #ATRIBUTO6").val($("#ret_ATRIBUTO6_" + index).val()).trigger('chosen:updated');
			$("#formulario #ATRIBUTO7").val($("#ret_ATRIBUTO7_" + index).val()).trigger('chosen:updated');
			$("#formulario #ATRIBUTO8").val($("#ret_ATRIBUTO8_" + index).val()).trigger('chosen:updated');
			$("#formulario #ATRIBUTO9").val($("#ret_ATRIBUTO9_" + index).val()).trigger('chosen:updated');
			$("#formulario #ATRIBUTO10").val($("#ret_ATRIBUTO10_" + index).val()).trigger('chosen:updated');
			$("#formulario #ATRIBUTO11").val($("#ret_ATRIBUTO11_" + index).val()).trigger('chosen:updated');
			$("#formulario #ATRIBUTO12").val($("#ret_ATRIBUTO12_" + index).val()).trigger('chosen:updated');
			$("#formulario #ATRIBUTO13").val($("#ret_ATRIBUTO13_" + index).val()).trigger('chosen:updated');
			$("#formulario #DES_IMAGEM").val($("#ret_DES_IMAGEM_" + index).val());
			$("#formulario #DES_IMAGEM").val($("#ret_DES_IMAGEM_" + index).val());
			$("#formulario #IMAGEM").val($("#ret_IMAGEM_" + index).val());
			$("#formulario #SKU").val($("#ret_SKU_" + index).val());
			$("#formulario #URL_IMG_PROD").val($("#ret_URL_IMG_PROD_" + index).val());
			$("#formulario #COD_EAN").val($("#ret_COD_EAN_" + index).val());

			if ($("#ret_LOG_PRODPBM_" + index).val() == 'S') {
				$('#formulario #LOG_PRODPBM').prop('checked', true);
			} else {
				$('#formulario #LOG_PRODPBM').prop('checked', false);
			}

			if ($("#ret_LOG_HABITEXC_" + index).val() == 'S') {
				$('#formulario #LOG_HABITEXC').prop('checked', true);
			} else {
				$('#formulario #LOG_HABITEXC').prop('checked', false);
			}

			if ($("#ret_LOG_NRESGATE_" + index).val() == 'S') {
				$('#formulario #LOG_NRESGATE').prop('checked', true);
			} else {
				$('#formulario #LOG_NRESGATE').prop('checked', false);
			}

			if ($("#ret_LOG_PONTUAR_" + index).val() == '1') {
				$('#formulario #LOG_PONTUAR').prop('checked', true);
			} else {
				$('#formulario #LOG_PONTUAR').prop('checked', false);
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
					'<a type="button" id="btnUploadFile" class="btn btn-primary btn-sm" style="font-weight: bold" onClick="uploadFile(\'' +
					idField + '\', \'' + typeFile + '\')">UPLOAD</a>' +
					'</form>'
			});
		});

		function reloadPage(idPage) {
			$.ajax({
				type: "POST",
				url: "ajxProdutosEmpresas.do?opcao=paginar&id=<?php echo fnEncode($cod_empresa); ?>&idPage=" +
					idPage + "&itens_por_pagina=<?php echo $itens_por_pagina; ?>",
				data: $('#formulario').serialize(),
				beforeSend: function() {
					$('#relatorioConteudo').html('<div class="loading" style="width: 100%;"></div>');
				},
				success: function(data) {
					$("#relatorioConteudo").html(data);
				},
				error: function() {
					$('#relatorioConteudo').html(
						'<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>'
					);
				}
			});
		}

		function upload_check(size) {
			var max = 1048576 / 5;

			if (size > max) {
				return 0;
			} else {
				return 1;
			}
		}

		function uploadFile(idField, typeFile) {
			var formData = new FormData();
			var nomeArquivo = $('#' + idField)[0].files[0]['name'];

			formData.append('arquivo', $('#' + idField)[0].files[0]);
			formData.append('diretorio', '../media/clientes');
			formData.append('diretorioAdicional', 'produtos');
			formData.append('id', <?php echo $cod_empresa ?>);
			formData.append('typeFile', typeFile);

			if (!upload_check($('#' + idField)[0].files[0]['size'])) {

				$('#' + idField).val("");

				$.alert({
					title: "Mensagem",
					content: "O arquivo que você está tentando enviar é muito grande! Tente novamente com um arquivo de 200 KB ou menor.",
					type: 'yellow'
				});

				return false;
			}

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
				url: '../uploads/uploaddoc.php',
				type: 'POST',
				data: formData,
				processData: false, // tell jQuery not to process the data
				contentType: false, // tell jQuery not to set contentType
				success: function(data) {

					var data = JSON.parse(data);

					$('.jconfirm-open').fadeOut(300, function() {
						$(this).remove();
					});
					if (data.success) {
						$('#' + idField.replace("arqUpload_DES_", "")).val(nomeArquivo);
						$('#' + idField.replace("arqUpload_", "")).val(data.nome_arquivo);
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