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
$val_produto = "";
$cod_fornecedor = "";
$cod_ean = "";
$des_produto = "";
$des_disponibilidade = "";
$des_tipoentrega = "";
$num_pontos = "";
$log_ativo = "";
$log_markapontos = "";
$filtro = "";
$val_pesquisa = "";
$nom_usuario = "";
$actual_link = "";
$MODULO = "";
$COD_MODULO = "";
$opcao = "";
$hHabilitado = "";
$hashForm = "";
$cod_usucada = "";
$arrayProc = [];
$cod_erro = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$produtoGestao = "";
$sql2 = "";
$arrayQuery2 = [];
$qrProdutoUnico = "";
$ean = "";
$check_LOG_ATIVO = "";
$check_LOG_MARKAPONTOS = "";
$check_BPM = "";
$esconde = "";
$popUp = "";
$abaMarkaPontos = "";
$qrListaCategoria = "";
$andFiltro = "";
$pesquisa = "";
$andExternoTkt = "";
$andProduto = "";
$andExterno = "";
$retorno = "";
$inicio = "";
$sql1 = "";
$qrListaProduto = "";
$markapontosAtivo = "";
$content = "";


//echo fnDebug('true');

$itens_por_pagina = 50;
$pagina  = "1";

$conn = conntemp($cod_empresa, "");
$adm = $connAdm->connAdm();

$hashLocal = mt_rand();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

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
		$val_produto = fnValorSql(@$_REQUEST['VAL_PRODUTO']);
		$cod_fornecedor = fnLimpacampoZero(@$_REQUEST['COD_FORNECEDOR']);
		$cod_ean = fnLimpacampo(@$_REQUEST['COD_EAN']);
		$des_produto = fnLimpacampo(@$_REQUEST['DES_PRODUTO']);
		$des_disponibilidade = fnLimpacampo(@$_REQUEST['DES_DISPONIBILIDADE']);
		$des_tipoentrega = fnLimpacampo(@$_REQUEST['DES_TIPOENTREGA']);
		$num_pontos = fnLimpacampoZero(@$_REQUEST['NUM_PONTOS']);
		$des_imagem = fnLimpacampo(@$_REQUEST['DES_IMAGEM']);
		if (empty(@$_REQUEST['LOG_ATIVO'])) {
			$log_ativo = 'N';
		} else {
			$log_ativo = "S";
		}
		if (empty(@$_REQUEST['LOG_MARKAPONTOS'])) {
			$log_markapontos = '0';
		} else {
			$log_markapontos = "1";
		}

		// fnEscreve($log_markapontos);

		$filtro = fnLimpaCampo(@$_POST['VAL_PESQUISA']);
		$val_pesquisa = fnLimpaCampo(@$_POST['INPUT']);

		$nom_usuario = $_SESSION["SYS_NOM_USUARIO"];
		$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$MODULO = @$_GET['mod'];
		$COD_MODULO = fndecode(@$_GET['mod']);

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];
		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

		if ($opcao != '') {

			$sql = "CALL SP_ALTERA_PRODUTOPROMOCAO (
				 '" . $cod_produto . "', 
				 '" . $cod_externo . "', 
				 '" . $cod_empresa . "',				
				 '" . $log_ativo . "', 
				 '" . $log_markapontos . "', 
				 '" . $cod_ean . "',				
				 '" . $des_produto . "',				
				 '" . $cod_categor . "', 
				 '" . $cod_subcate . "', 
				 '" . $cod_fornecedor . "', 
				 '" . $des_disponibilidade . "',
				 '" . $des_tipoentrega . "',
				 '" . $num_pontos . "',				 
				 '" . $val_produto . "',				 
				 '" . $des_imagem . "',				 
				 '" . $cod_usucada . "',
				 '" . $opcao . "'   
				) ";

			// fnTesteSql(connTemp($cod_empresa,""),$sql);

			$arrayProc = mysqli_query($conn, trim($sql));

			if (!$arrayProc) {

				$cod_erro = Log_error_comand($adm, $conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sql, $nom_usuario);
			}

			//mensagem de retorno
			switch ($opcao) {
				case 'CAD':
					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível cadastrar o registro : $cod_erro";
					}
					break;
				case 'ALT':
					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível alterar o registro : $cod_erro";
					}
					break;
				case 'EXC':
					if ($cod_erro == 0 || $cod_erro ==  "") {
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					} else {
						$msgRetorno = "Não foi possível excluir o registro : $cod_erro";
					}
					break;
			}
			if ($cod_erro == 0 || $cod_erro == "") {
				$msgTipo = 'alert-success';
			} else {
				$msgTipo = 'alert-danger';
			}
		}
	}
}

//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode(@$_GET['id']);
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($adm, $sql);
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
	$arrayQuery = mysqli_query($adm, $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
} else {
	$cod_empresa = 0;
	//fnEscreve('entrou else');
}

//////////////////////// produtos/prod. gestão de ofertas  /////////////////////////////////////
$produtoGestao = fnDecode(@$_GET['idPrd']);
if (isset($produtoGestao) && fnDecode(@$_GET['mod']) == 1194) {
	//fnEscreve("tem gestão");		

	if ($produtoGestao != "0") {
		$sql2 = "select A.* from PRODUTOPROMOCAO A 
				where A.COD_EMPRESA = $cod_empresa
				AND A.COD_PRODUTO = $produtoGestao
				AND A.COD_EXCLUSA=0 order by A.DES_PRODUTO ";
		// fnEscreve($sql);
		exit();
		$arrayQuery2 = mysqli_query($conn, $sql2);
		$qrProdutoUnico = mysqli_fetch_assoc($arrayQuery2);

		$cod_produto = $qrProdutoUnico['COD_PRODUTO'];
		$cod_externo = $qrProdutoUnico['COD_EXTERNO'];
		$des_produto = $qrProdutoUnico['DES_PRODUTO'];
		$cod_categor = $qrProdutoUnico['COD_CATEGOR'];
		$cod_subcate = $qrProdutoUnico['COD_SUBCATE'];
		$cod_fornecedor = $qrProdutoUnico['COD_FORNECEDOR'];
		$ean = $qrProdutoUnico['EAN'];
		$des_disponibilidade = $qrProdutoUnico['DES_DISPONIBILIDADE'];
		$des_tipoentrega = $qrProdutoUnico['DES_TIPOENTREGA'];
		$num_pontos = $qrProdutoUnico['NUM_PONTOS'];
		$des_imagem = $qrProdutoUnico['DES_IMAGEM'];

		if ($qrProdutoUnico['LOG_ATIVO'] == "N") {
			$check_LOG_ATIVO = '';
		} else {
			$check_LOG_ATIVO = "checked";
		}

		if ($qrProdutoUnico['log_markapontos'] == "0") {
			$check_LOG_MARKAPONTOS = '';
		} else {
			$check_LOG_MARKAPONTOS = "checked";
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
	$des_disponibilidade = "";
	$des_tipoentrega = "";
	$num_pontos = "";
	$des_imagem = "";
	$check_BPM = "";
	$check_LOG_ATIVO = "";
	$check_LOG_MARKAPONTOS = "";
}

if ($val_pesquisa != '' && $val_pesquisa != 0) {
	$esconde = " ";
} else {
	$esconde = "display: none;";
}


//fnMostraForm();
//fnEscreve($cod_empresa);
//fnEscreve(fnDecode(@$_GET['idPrd']));


?>


<?php if ($popUp != "true") {  ?>
	<div class="push30"></div>
<?php } ?>

<div class="row">

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<?php if ($popUp != "true") {  ?>
			<div class="portlet portlet-bordered">
			<?php } else { ?>
				<div class="portlet" style="padding: 0 20px 20px 20px;">
				<?php } ?>

				<?php if ($popUp != "true") {  ?>
					<div class="portlet-title">
						<div class="caption">
							<i class="fal fa-terminal"></i>
							<span class="text-primary"><?php echo $NomePg; ?>/<?php echo $nom_empresa; ?></span>
						</div>
						<?php include "atalhosPortlet.php"; ?>
					</div>
				<?php } ?>

				<div class="portlet-body">

					<?php if ($msgRetorno <> '') { ?>
						<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<?php echo $msgRetorno; ?>
						</div>
					<?php } ?>

					<?php
					//menu superior - markapontos
					$abaMarkaPontos = 1225;
					include "abasMarkapontos.php";
					?>

					<?php if ($popUp != "true") {  ?>
						<div class="push30"></div>
					<?php } ?>

					<div class="login-form">

						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

							<fieldset>
								<legend>Dados Gerais</legend>

								<div class="row">

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Produto Ativo</label>
											<div class="push5"></div>
											<label class="switch">
												<input type="checkbox" name="LOG_ATIVO" id="LOG_ATIVO" class="switch" <?php echo $check_LOG_ATIVO; ?>>
												<span></span>
											</label>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Markapontos/Hotsite</label>
											<div class="push5"></div>
											<label class="switch">
												<input type="checkbox" name="LOG_MARKAPONTOS" id="LOG_MARKAPONTOS" class="switch" <?php echo $check_LOG_MARKAPONTOS; ?>>
												<span></span>
											</label>
										</div>
									</div>

									<!-- <div class="push10"></div>  -->

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Código</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_PRODUTO" id="COD_PRODUTO" value="<?php echo $cod_produto; ?>">
										</div>
									</div>


									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Nome do Produto</label>
											<input type="text" class="form-control input-sm" name="DES_PRODUTO" id="DES_PRODUTO" value="<?php echo $des_produto; ?>" maxlength="50" data-error="Campo obrigatório" required>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Código Externo</label>
											<input type="text" class="form-control input-sm" name="COD_EXTERNO" id="COD_EXTERNO" value="<?php echo $cod_externo; ?>" maxlength="50" data-error="Campo obrigatório">
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Custo</label>
											<input type="text" class="form-control input-sm money" name="VAL_PRODUTO" id="VAL_PRODUTO" value="<?php echo $val_produto; ?>">
											<div class="help-block with-errors">Em reais (R$)</div>
										</div>
									</div>

								</div>

								<div class="row">

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Código EAN</label>
											<input type="text" class="form-control input-sm" name="COD_EAN" id="COD_EAN" value="<?php echo $ean; ?>" maxlength="20" data-error="Campo obrigatório">
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Grupo do Produto</label>
											<select data-placeholder="Selecione o grupo" name="COD_CATEGOR" id="COD_CATEGOR" class="chosen-select-deselect">
												<option value="">&nbsp;</option>
												<?php
												$sql = "select * from CAT_PROMOCAO where COD_EMPRESA = $cod_empresa AND COD_EXCLUSA is null order by DES_CATEGOR";
												$arrayQuery = mysqli_query($conn, $sql);

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

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Sub Grupo do Produto</label>
											<div id="divId_sub">
												<select data-placeholder="Selecione o sub grupo" name="COD_SUBCATE" id="COD_SUBCATE" class="chosen-select-deselect">
													<option value="">&nbsp;</option>
												</select>
											</div>
											<script>
												buscaSubCat(<?php echo $cod_categor; ?>, <?php echo $cod_subcate; ?>, <?php echo $cod_empresa; ?>);
											</script>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Parceiro / Fornecedor</label>
											<select data-placeholder="Selecione o grupo" name="COD_FORNECEDOR" id="COD_FORNECEDOR" class="chosen-select-deselect">
												<option value="">&nbsp;</option>
												<?php
												$sql = "select * from FORNECEDORMRKA WHERE COD_EMPRESA = $cod_empresa order by NOM_FORNECEDOR";
												$arrayQuery = mysqli_query($conn, $sql);

												while ($qrListaCategoria = mysqli_fetch_assoc($arrayQuery)) {
													echo "
														<option value='" . $qrListaCategoria['COD_FORNECEDOR'] . "'>" . $qrListaCategoria['NOM_FORNECEDOR'] . "</option> 
													";
												}
												?>
											</select>
											<script>
												$("#COD_FORNECEDOR").val("<?php echo $cod_fornecedor; ?>").trigger("chosen:updated");
											</script>
											<div class="help-block with-errors"></div>
										</div>
									</div>

								</div>

								<div class="row">

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Disponibilidade</label>
											<select data-placeholder="Selecione o grupo" name="DES_DISPONIBILIDADE" id="DES_DISPONIBILIDADE" class="chosen-select-deselect">
												<option value="">&nbsp;</option>
												<option value="entrega">Pronta Entrega</option>
												<option value="solicitacao">Produto a ser solicitado</option>
											</select>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Tipo de Entrega</label>
											<select data-placeholder="Selecione o grupo" name="DES_TIPOENTREGA" id="DES_TIPOENTREGA" class="chosen-select-deselect">
												<option value="">&nbsp;</option>
												<option value="correio">Envio correios</option>
												<option value="central">Central de Relacionamento</option>
												<option value="loja">Lojas</option>
											</select>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Pontos / Troca</label>
											<input type="text" class="form-control input-sm" name="NUM_PONTOS" id="NUM_PONTOS" value="<?php echo $ean; ?>" maxlength="20" data-error="Campo obrigatório">
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Imagem</label>
											<div class="input-group">
												<span class="input-group-btn">
													<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary upload" idinput="DES_IMAGEM" extensao="img"><i class="fa fa-cloud-upload" aria-hidden="true"></i></a>
												</span>
												<input type="text" name="IMAGEM" id="IMAGEM" class="form-control input-sm" style="border-radius: 0 3px 3px  0;" value="<?php echo fnBase64DecodeImg($des_imagem); ?>">
												<input type="hidden" name="DES_IMAGEM" id="DES_IMAGEM" class="form-control input-sm" value="<?php echo $des_imagem; ?>">
											</div>
											<span class="help-block">(.jpg, .png 500px X 500px)</span>
										</div>
									</div>

									<!-- <div class="col-md-1">
														<div class="form-group">
															<label for="inputName" class="control-label">&nbsp;</label>
															<a href="javascript:void(0)" class="btn btn-info addBox"><span class="fas fa-eye"></span></a>
														</div>
													</div> -->


								</div>

								<!-- <div class="row">

													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Controlar Estoque?</label> 
															<div class="push5"></div>
																<label class="switch">
																<input type="checkbox" name="LOG_ESTOQUE" id="LOG_ESTOQUE" class="switch">
																<span></span>
																</label>
															<script>
						                                        $('#LOG_ESTOQUE').change(function(){
						                                            if($('#LOG_ESTOQUE').is(':checked')){
						                                                $('#QTD_ESTOQUE').prop('disabled',false);
						                                            }else{
						                                                $('#QTD_ESTOQUE').prop('disabled',true);
						                                            }
						                                        });
						                                    </script>
														</div>
													</div>
													
													<div class="col-md-1">
														<div class="form-group">
															<label for="inputName" class="control-label">Estoque</label>
															<input type="text" class="form-control input-sm int" name="QTD_ESTOQUE" id="QTD_ESTOQUE" value="" disabled maxlength="50">
															<div class="help-block with-errors"></div>
														</div>
													</div>													

												</div> -->

							</fieldset>

							<div class="push10"></div>
							<hr>
							<div class="form-group text-right col-lg-12">

								<button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
								<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
								<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>
								<button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fal fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>

							</div>

							<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">
							<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
							<input type="hidden" name="opcao" id="opcao" value="">
							<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />

							<div class="push5"></div>

						</form>

						<div class="push50"></div>

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


						<div class="col-lg-12">

							<div class="no-more-tables">

								<!-- <div class="col-lg-12 bg-primary">
												
													<form id="formBusca" action="action.do?mod=<?php echo fnEncode(1225); ?>&id=<?php echo fnEncode($cod_empresa); ?>" method="post" >

													<div class="col-md-2" style="padding: 10px;">
														<div class="form-group">
															<input type="text" class="form-control input-xs" name="DES_PRODUTO" id="DES_PRODUTO" placeholder="Nome do produto" maxlength="50">
														</div>
													</div>
													
													<div class="col-md-2" style="padding: 10px;">
														<div class="form-group">
															<input type="text" class="form-control input-xs" name="COD_EXTERNO" id="COD_EXTERNO" placeholder="Código externo" maxlength="20">
														</div>
													</div>

													<div class="col-md-3" style="padding: 10px;">
														<button type="submit" name="BUSCA" id="BUSCA" class="btn btn-default btn-xs"><i class="fal fa-search" aria-hidden="true"></i>&nbsp; Pesquisar</button>
														<input type="hidden" name="pesquisa" id="pesquisa" value="ON">
													</div>
													
													</form>
													
												</div> -->

								<div class="push30"></div>

								<div class="row">
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
														<!-- <li class="divider"></li> -->
														<li><a href="#DES_PRODUTO">Nome do Produto</a></li>
														<li><a href="#COD_EXTERNO">Código Externo</a></li>
													</ul>
												</div>
												<input type="hidden" name="VAL_PESQUISA" value="<?= $filtro ?>" id="VAL_PESQUISA">
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


								<form name="formLista">

									<table class="table table-bordered table-striped table-hover buscavel">
										<thead>
											<tr>
												<th width="40"></th>
												<th>Código</th>
												<th>Cod. Externo</th>
												<th>Grupo</th>
												<th>Sub Grupo</th>
												<th>Descrição</th>
												<th>Pontos/Troca</th>
												<th>Qtd. Estoque</th>
												<th>Imagem</th>
												<th>Markapontos/Hotsite</th>
											</tr>
										</thead>
										<tbody id="relatorioConteudo">


											<?php

											if ($filtro != '') {
												$andFiltro = " AND A.$filtro LIKE '%$val_pesquisa%' ";
											} else {
												$andFiltro = " ";
											}

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
												if ($des_produto != '') {
													$andProduto = 'AND A.DES_PRODUTO like "%' . $des_produto . '%"';
												} else {
													$andProduto = ' ';
												}

												if ($cod_externo != '') {
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

											$sql = "SELECT A.COD_PRODUTO from PRODUTOPROMOCAO A 
														LEFT JOIN CAT_PROMOCAO B ON A.COD_CATEGOR = B.COD_CATEGOR 
														LEFT JOIN SUB_PROMOCAO C ON A.COD_SUBCATE = C.COD_SUBCATE 
														where A.COD_EMPRESA='" . $cod_empresa . "' 
														AND A.COD_EXCLUSA=0 
														$andFiltro";

											// fnEscreve($sql);

											$retorno = mysqli_query($conn, $sql);
											$total_itens_por_pagina = mysqli_num_rows($retorno);

											// fnEscreve($total_itens_por_pagina);

											$numPaginas = ceil($total_itens_por_pagina / $itens_por_pagina);

											//variavel para calcular o início da visualização com base na página atual
											$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

											$sql1 = "select A.*,B.DES_CATEGOR as GRUPO,C.DES_SUBCATE as SUBGRUPO,
														(SELECT SUM(EP.QTD_ESTOQUE) FROM ESTOQUE_PRODUTO EP WHERE EP.COD_EMPRESA = $cod_empresa AND EP.COD_PRODUTO = A.COD_PRODUTO) AS QTD_ESTOQUE
														from PRODUTOPROMOCAO A 
														LEFT JOIN CAT_PROMOCAO B ON A.COD_CATEGOR = B.COD_CATEGOR 
														LEFT JOIN SUB_PROMOCAO C ON A.COD_SUBCATE = C.COD_SUBCATE 
														where A.COD_EMPRESA='" . $cod_empresa . "' 
														AND A.COD_EXCLUSA=0 
														$andFiltro order by A.DES_PRODUTO limit $inicio,$itens_por_pagina";

											// fnEscreve($sql1);

											$arrayQuery = mysqli_query($conn, $sql1);

											$count = 0;
											while ($qrListaProduto = mysqli_fetch_assoc($arrayQuery)) {
												$count++;

												if ($qrListaProduto['log_markapontos'] == "1") {
													$markapontosAtivo = '<i class="fal fa-check text-success" aria-hidden="true"></i>';
												} else {
													$markapontosAtivo = '<i class="fal fa-times text-danger" aria-hidden="true"></i>';
												}

												if ($qrListaProduto['DES_IMAGEM'] != "") {
													$mostraDES_IMAGEM = '<a href="https://img.bunker.mk/media/clientes/' . $cod_empresa . '/produtospromo/' . $qrListaProduto['DES_IMAGEM'] . '" target="_blank">Visualizar</a>';
												} else {
													$mostraDES_IMAGEM = '';
												}

												echo "
															<tr>
															  <td><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></th>
															  <td>" . $qrListaProduto['COD_PRODUTO'] . "</td>
															  <td>" . $qrListaProduto['COD_EXTERNO'] . "</td>
															  <td>" . $qrListaProduto['GRUPO'] . "</td>
															  <td>" . $qrListaProduto['SUBGRUPO'] . "</td>
															  <td>" . $qrListaProduto['DES_PRODUTO'] . "</td>
															  <td>" . $qrListaProduto['NUM_PONTOS'] . "</td>
															  <td>" . fnValor($qrListaProduto['QTD_ESTOQUE'], 0) . "</td>
															  <td class='text-center'>" . $mostraDES_IMAGEM . "</td>
															  <td class='text-center'>" . $markapontosAtivo . "</td>
															</tr>
															<input type='hidden' id='ret_COD_PRODUTO_" . $count . "' value='" . $qrListaProduto['COD_PRODUTO'] . "'>  
															<input type='hidden' id='ret_COD_EXTERNO_" . $count . "' value='" . $qrListaProduto['COD_EXTERNO'] . "'>
															<input type='hidden' id='ret_DES_PRODUTO_" . $count . "' value='" . $qrListaProduto['DES_PRODUTO'] . "'>
															<input type='hidden' id='ret_COD_CATEGOR_" . $count . "' value='" . $qrListaProduto['COD_CATEGOR'] . "'>
															<input type='hidden' id='ret_COD_SUBCATE_" . $count . "' value='" . $qrListaProduto['COD_SUBCATE'] . "'>
															<input type='hidden' id='ret_COD_FORNECEDOR_" . $count . "' value='" . $qrListaProduto['COD_FORNECEDOR'] . "'>
															<input type='hidden' id='ret_COD_EAN_" . $count . "' value='" . $qrListaProduto['EAN'] . "'>
															<input type='hidden' id='ret_DES_DISPONIBILIDADE_" . $count . "' value='" . $qrListaProduto['DES_DISPONIBILIDADE'] . "'>
															<input type='hidden' id='ret_DES_TIPOENTREGA_" . $count . "' value='" . $qrListaProduto['DES_TIPOENTREGA'] . "'>
															<input type='hidden' id='ret_NUM_PONTOS_" . $count . "' value='" . $qrListaProduto['NUM_PONTOS'] . "'>
															<input type='hidden' id='ret_VAL_PRODUTO_" . $count . "' value='" . fnValor($qrListaProduto['VAL_PRODUTO'], 2) . "'>
															<input type='hidden' id='ret_DES_IMAGEM_" . $count . "' value='" . $qrListaProduto['DES_IMAGEM'] . "'>
															<input type='hidden' id='ret_LOG_ATIVO_" . $count . "' value='" . $qrListaProduto['LOG_ATIVO'] . "'>
															<input type='hidden' id='ret_LOG_MARKAPONTOS_" . $count . "' value='" . $qrListaProduto['log_markapontos'] . "'>
															";
											}
											?>

										</tbody>

										<tfoot>
											<tr>
												<th colspan="100">
													<a class="btn btn-info btn-sm exportarCSV"> <i class="fal fa-file-excel" aria-hidden="true"></i>&nbsp; Exportar</a>
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
					var value = $('#INPUT').val().toLowerCase().trim();
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
			//chosen
			$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
			$('#formulario').validator();

			<?php
			///////// produtos/prod. gestão de ofertas //////////
			if (fnDecode(@$_GET['mod']) == 1194) {  ?>
				var codCat = <?php echo $cod_categor; ?>;
				var codSub = <?php echo $cod_subcate; ?>;
				buscaSubCat(codCat, codSub, <?php echo $cod_empresa; ?>);
			<?php } ?>

			var numPaginas = <?php echo $numPaginas; ?>;
			if (numPaginas != 0) {
				carregarPaginacao(numPaginas);
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
											url: "ajxProdutosPromocao.do?opcao=exportar&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>",
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

		// ajax
		$("#COD_CATEGOR").change(function() {
			var codBusca = $("#COD_CATEGOR").val();
			var codBusca3 = $("#COD_EMPRESA").val();
			buscaSubCat(codBusca, 0, codBusca3);
		});

		function buscaSubCat(idCat, idSub, idEmp) {
			$.ajax({
				type: "GET",
				url: "ajxBuscaSubGrupoPromocao.php",
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
			$("#formulario #VAL_PRODUTO").val($("#ret_VAL_PRODUTO_" + index).val());
			$("#formulario #COD_CATEGOR").val($("#ret_COD_CATEGOR_" + index).val()).trigger("chosen:updated");
			$("#formulario #COD_FORNECEDOR").val($("#ret_COD_FORNECEDOR_" + index).val()).trigger("chosen:updated");

			$("#formulario #DES_DISPONIBILIDADE").val($("#ret_DES_DISPONIBILIDADE_" + index).val()).trigger("chosen:updated");
			$("#formulario #DES_TIPOENTREGA").val($("#ret_DES_TIPOENTREGA_" + index).val()).trigger("chosen:updated");

			var codCat = $("#ret_COD_CATEGOR_" + index).val();
			var codSub = $("#ret_COD_SUBCATE_" + index).val();
			buscaSubCat(codCat, codSub, <?php echo $cod_empresa; ?>);

			$("#formulario #NUM_PONTOS").val($("#ret_NUM_PONTOS_" + index).val());
			$("#formulario #DES_IMAGEM").val($("#ret_DES_IMAGEM_" + index).val());
			$("#formulario #COD_EAN").val($("#ret_COD_EAN_" + index).val());

			if ($("#ret_LOG_ATIVO_" + index).val() == 'S') {
				$('#formulario #LOG_ATIVO').prop('checked', true);
			} else {
				$('#formulario #LOG_ATIVO').prop('checked', false);
			}

			if ($("#ret_LOG_MARKAPONTOS_" + index).val() == '1') {
				$('#formulario #LOG_MARKAPONTOS').prop('checked', true);
			} else {
				$('#formulario #LOG_MARKAPONTOS').prop('checked', false);
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

		function reloadPage(idPage) {
			$.ajax({
				type: "POST",
				url: "ajxProdutosPromocao.do?opcao=paginar&id=<?php echo fnEncode($cod_empresa); ?>&idPage=" + idPage + "&itens_por_pagina=<?php echo $itens_por_pagina; ?>",
				data: $('#formLista2').serialize(),
				beforeSend: function() {
					$('#relatorioConteudo').html('<div class="loading" style="width: 100%;"></div>');
				},
				success: function(data) {
					$("#relatorioConteudo").html(data);
				},
				error: function() {
					$('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
				}
			});
		}

		function uploadFile(idField, typeFile) {
			var formData = new FormData();
			var nomeArquivo = $('#' + idField)[0].files[0]['name'];

			formData.append('arquivo', $('#' + idField)[0].files[0]);
			formData.append('diretorio', '../media/clientes/');
			formData.append('diretorioAdicional', 'produtospromo');
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