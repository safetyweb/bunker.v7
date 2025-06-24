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
$cod_categortkt = "";
$des_categor = "";
$des_abrevia = "";
$des_icones = "";
$log_destak = "";
$hHabilitado = "";
$hashForm = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$cod_template = "";
$qrBuscaTemplate = "";
$log_ativo = "";
$mostraLog_ativo = "";
$nom_template = "";
$abv_template = "";
$des_template = "";
$formBack = "";
$abaModulo = "";
$qtdeBloco = 0;
$qrListaBlocos = "";
$qrListaModelos = "";
$sqlMsg = "";
$arrayMsg = [];
$qrBuscaComunicacao = "";
$temMsg = "";
$msg = "";
$dia_hoje = "";
$mes_hoje = "";
$ano_hoje = "";
$dia_nascime = "";
$mes_nascime = "";
$ano_nascime = "";
$NOM_CLIENTE = "";
$TEXTOENVIO = "";
$msgsbtr = "";


$hashLocal = mt_rand();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_categortkt = fnLimpaCampoZero(@$_REQUEST['COD_CATEGORTKT']);
		$cod_empresa = fnLimpaCampoZero(@$_REQUEST['COD_EMPRESA']);
		$des_categor = fnLimpaCampo(@$_REQUEST['DES_CATEGOR']);
		$des_abrevia = fnLimpaCampo(@$_REQUEST['DES_ABREVIA']);
		$des_icones = fnLimpaCampo(@$_REQUEST['DES_ICONES']);
		if (empty(@$_REQUEST['LOG_DESTAK'])) {
			$log_destak = 'N';
		} else {
			$log_destak = @$_REQUEST['LOG_DESTAK'];
		}

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		//fnEscreve($des_icones);	

		if ($opcao != '') {

			$sql = "CALL SP_ALTERA_CATEGORIATKT (
				 '" . $cod_categortkt . "', 
				 '" . $cod_empresa . "', 
				 '" . $des_categor . "', 
				 '" . $des_abrevia . "', 
				 '" . $des_icones . "', 
				 '" . $log_destak . "', 
				 '" . $_SESSION["SYS_COD_USUARIO"] . "', 
				 '" . $opcao . "'    
				) ";


			//fnEscreve($sql);

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
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";

	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($qrBuscaEmpresa)) {
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
	}
} else {
	$cod_empresa = 0;
	$nom_empresa = "";
}

if (is_numeric(fnLimpacampo(fnDecode(@$_GET['idT'])))) {

	//busca dados do convênio
	$cod_template = fnDecode(@$_GET['idT']);
	$sql = "SELECT * FROM TEMPLATE WHERE COD_TEMPLATE = " . $cod_template;

	//fnEscreve($sql);
	$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
	$qrBuscaTemplate = mysqli_fetch_assoc($arrayQuery);

	if (isset($qrBuscaTemplate)) {
		$cod_template = $qrBuscaTemplate['COD_TEMPLATE'];
		$log_ativo = $qrBuscaTemplate['LOG_ATIVO'];
		if ($log_ativo == "S") {
			$mostraLog_ativo = "checked";
		} else {
			$mostraLog_ativo = "";
		}
		$nom_template = $qrBuscaTemplate['NOM_TEMPLATE'];
		$abv_template = $qrBuscaTemplate['ABV_TEMPLATE'];
		$des_template = $qrBuscaTemplate['DES_TEMPLATE'];
	}
} else {
	$cod_template = "";
	$log_ativo = "";
	$nom_template = "";
	$abv_template = "";
	$des_template = "";
}

// BUSCA LINK ENCURTADO
$urlEncurtada = '';
$sqlBusca = "SELECT * FROM TAB_ENCURTADOR WHERE COD_EMPRESA = $cod_empresa AND TIP_URL = 'TKT'";
$arrayBusca = mysqli_query($connAdm->connAdm(), $sqlBusca);
if (mysqli_num_rows($arrayBusca) == 0) {
	$sql = "SELECT COD_TEMPLATE, NOM_TEMPLATE FROM TEMPLATE WHERE COD_EMPRESA = $cod_empresa AND LOG_ATIVO = 'S' LIMIT 1";
	$array = mysqli_query($conn, $sql);
	if (mysqli_num_rows($array) > 0) {
		$sqlProd = "SELECT * FROM PRODUTOTKT WHERE COD_EMPRESA = $cod_empresa AND LOG_ATIVOTK = 'S'";
		$arrayProd = mysqli_query($conn, $sqlProd);
		if (mysqli_num_rows($arrayProd) > 0) {
			$qrTkt = mysqli_fetch_assoc($array);
			$titulo = $qrTkt['NOM_TEMPLATE'] . ' #' . $qrTkt['COD_TEMPLATE'];
			$code = fnEncurtador($titulo, '', '', '', 'TKT', $cod_empresa, $connAdm->connAdm(), $qrTkt['COD_TEMPLATE']);
			$urlEncurtada = "https://tkt.far.br/" . $code . "/";
		}
	}
} else {
	$qrBuscaLink = mysqli_fetch_assoc($arrayBusca);
	$urlEncurtada = "https://tkt.far.br/" . short_url_encode($qrBuscaLink['id']) . "/";
}
//fnMostraForm();
//fnEscreve($_SESSION["SYS_COD_SISTEMA"]);

?>

<style type="text/css">
	.template {
		margin: 0 auto;
		height: auto;
		width: 600px;
		margin-top: 50px;

	}

	.connectedSortable {
		list-style-type: none;
		padding: 0;
	}

	.connectedSortable li:not(.normal) {
		min-height: 60px;
		text-align: center;
		width: 80px;
		height: auto !important;
		overflow: hidden;
	}

	#sortable1 {
		float: left;
	}

	#sortable3 {
		float: right;
	}

	#sortable1 li,
	#sortable3 li {
		margin-top: 20px;
		border-radius: 5px;
		background-color: transparent;
		font-size: 25px !important;
	}

	#sortable2 {
		float: left;
		margin: 4px;
		height: auto !important;
		border: 3px dashed #cecece;
		padding: 10px;
		border-radius: 5px;
		width: 100%;
	}

	#sortable2 li {
		width: auto;
		background-color: #ffffff;
		border: none;
	}

	.ui-state-default {
		border: 1px solid #c5c5c5;
		background: #f6f6f6;
		font-weight: normal;
		color: #454545;
	}

	.ui-sortable-handle {
		touch-action: none;
	}

	.ui-state-default {
		border: none;
	}

	.ui-state-default a {
		color: #454545;
		text-decoration: none;
	}

	.descricaobloco {
		font-size: 11px;
	}

	.template i {
		margin-top: 10px;
	}

	hr {
		width: 100%;
		border-top: 2px solid #161616;
	}

	hr.divisao {
		width: 100%;
		border-top: 1px dashed #cecece;
		margin: 15px 0;
	}

	.excluirBloco {
		position: absolute;
		top: 10px;
		right: 0;
		font-size: 16px;
		margin-right: 5px;
		color: #cccccc !important;
	}

	.excluirBloco.black {
		margin-right: 10px;
		margin-top: 10px;
		color: #404040 !important;
	}

	.excluirBloco:hover {
		color: #ff4a4a !important;
		cursor: pointer;
	}

	.addImagem {
		position: absolute;
		top: 20px;
		right: 0px;
		font-size: 16px;
		margin-right: 5px;
		color: #cccccc !important;
	}

	.addImagem:hover {
		color: #18bc9c !important;
		cursor: pointer;
	}

	.imagemTicket {
		height: auto;
		width: 100%;
		display: flex;
		align-items: center;
		justify-content: center;
		padding: 10px;
		padding-right: 20px;
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
					<span class="text-primary"><?php echo $NomePg; ?></span>
				</div>

				<?php
				$formBack = "1108";
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

				<?php $abaModulo = 1111;
				include "abasTicketConfig.php"; ?>

				<div class="push30"></div>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Dados Gerais</legend>

							<div class="row">
								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Código</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_CATEGORTKT" id="COD_CATEGORTKT" value="<?php echo $cod_template ?>">
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Empresa</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa ?>">
										<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label required">Nome da Template</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_TEMPLATE" id="NOM_TEMPLATE" value="<?php echo $nom_template ?>" maxlength="20" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Abreviação</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="ABV_TEMPLATE" id="ABV_TEMPLATE" value="<?php echo $abv_template ?>">
									</div>
								</div>

								<div class="col-md-1">
									<div class="disabledBlock"></div>
									<div class="form-group">
										<label for="inputName" class="control-label">Ativo</label>
										<div class="push5"></div>
										<label class="switch">
											<input type="checkbox" name="LOG_DESTAK" id="LOG_DESTAK" class="switch" value="S" <?php echo $mostraLog_ativo; ?>>
											<span></span>
										</label>
									</div>
								</div>

							</div>

							<div class="row">

								<div class="col-md-2">
									<input type="text" id="linkPesquisa" class="form-control input-md pull-right text-center" value='<?= $urlEncurtada ?>' readonly>
									<input type="hidden" id="LINK_SEMCLI" value='<?= $urlEncurtada ?>'>
								</div>


								<div class="col-md-2">
									<button type="button" class="btn btn-default" id="btnPesquisa" <?= $disableBtn ?>><i class="fas fa-copy" aria-hidden="true"></i>&nbsp; Copiar Link</button>
									<script type="text/javascript">
										$("#btnPesquisa").click(function() {
											if (navigator.userAgent.match(/ipad|ipod|iphone/i)) {
												var el = $("#linkPesquisa").get(0);
												var editable = el.contentEditable;
												var readOnly = el.readOnly;
												el.contentEditable = true;
												el.readOnly = false;
												var range = document.createRange();
												range.selectNodeContents(el);
												var sel = window.getSelection();
												sel.removeAllRanges();
												sel.addRange(range);
												el.setSelectionRange(0, 999999);
												el.contentEditable = editable;
												el.readOnly = readOnly;
											} else {
												$("#linkPesquisa").select();
											}
											document.execCommand('copy');
											$("#linkPesquisa").blur();
											$("#btnPesquisa").text("Link Copiado");
											setTimeout(function() {
												$("#btnPesquisa").html("<i class='fas fa-copy' aria-hidden='true'></i>&nbsp; Copiar Link");
											}, 2000);
										});
									</script>
								</div>
							</div>
						</fieldset>

						<div class="row">
							<div class="col-md-12">
								<div class="template">
									<div class="row">
										<div class="col-md-2">
											<ul id="sortable1" class="connectedSortable">
												<?php
												$sql = "select count(*) from BLOCOTEMPLATE";
												$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
												$qtdeBloco = round((mysqli_fetch_row($arrayQuery)[0] / 2), 0, PHP_ROUND_HALF_UP);
												//fnEscreve($qtdeBloco);

												$sql = "select * from BLOCOTEMPLATE where NUM_ORDENAC <= $qtdeBloco order by NUM_ORDENAC ";
												$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

												while ($qrListaBlocos = mysqli_fetch_assoc($arrayQuery)) {
												?>
													<li class="ui-state-default shadow grabbable" cod-registr="" cod-bloco="<?php echo $qrListaBlocos['COD_BLTEMPL'] ?>">
														<i class="<?php echo $qrListaBlocos['DES_ICONE'] ?>" aria-hidden="true"></i>
														<div class="descricaobloco"><?php echo $qrListaBlocos['ABV_BLTEMPL'] ?></div>
													</li>
												<?php
												}
												?>
											</ul>
										</div>
										<div class="col-md-8">
											<ul id="sortable2" class="connectedSortable">
												<?php
												$sql = "SELECT MODELOTEMPLATETKT.COD_REGISTR,
															   MODELOTEMPLATETKT.COD_EMPRESA,
															   MODELOTEMPLATETKT.COD_TEMPLATE,
															   MODELOTEMPLATETKT.COD_BLTEMPL,
															   MODELOTEMPLATETKT.DES_IMAGEM,
															   MODELOTEMPLATETKT.DES_TEXTO
													    FROM   MODELOTEMPLATETKT
														WHERE  MODELOTEMPLATETKT.COD_EMPRESA = $cod_empresa 
														AND    MODELOTEMPLATETKT.COD_TEMPLATE = $cod_template
														AND    MODELOTEMPLATETKT.COD_EXCLUSA is null
														ORDER BY NUM_ORDENAC";

												//fnEscreve($sql);
												$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

												while ($qrListaModelos = mysqli_fetch_assoc($arrayQuery)) {

													//fnEscreve($qrListaModelos['COD_BLTEMPL']); 

												?>
													<li class="ui-state-default grabbable" cod-registr="<?php echo $qrListaModelos['COD_REGISTR'] ?>" cod-bloco="<?php echo $qrListaModelos['COD_BLTEMPL'] ?>">
														<?php
														switch ($qrListaModelos['COD_BLTEMPL']) {
															case 1: //nome do cliente
														?>
																<div style="position: relative">
																	<center>
																		<h3 style="margin: 5px; font-weight: 900">ISABEL,</h3>
																		<h5 style="margin: 5px; font-weight: 600"><small><b>Cliente Gold</b></small></h5>
																		<h5 style="margin-top: 5px">Está <b>esquecendo</b> de algo?</h5>
																		<a class="excluirBloco">
																			<i class="fa fa-trash" style="margin: 0" aria-hidden="true"></i>
																		</a>
																		<hr class="divisao" />
																		<center>

																</div>
															<?php
																break;
															case 2: //Produtos modelo 1
															?>
																<div style="position: relative">
																	<center>
																		<h5 style="margin-top: 5px">Veja <b>ofertas personalizadas</b> para você!</h5>
																	</center>
																	<div style="width: 100%;">
																		<div style="width: 55%; float: left; text-align: left;">
																			<h5 style="font-weight: 900">[Nome do produto]</h5>
																			<h5>[código]</h5>
																		</div>
																	</div>

																	<div style="width: 100%;">
																		<div style="width: 40%; float: right; text-align: left;">
																			<h5 style="font-weight: 900">de: R$ [de1]</h5>
																			<h5 style="font-weight: 900">por: R$ [por1]</h5>
																		</div>
																	</div>
																	<hr />
																	<div style="width: 100%;">
																		<div style="width: 55%; float: left; text-align: left;">
																			<h5 style="font-weight: 900">[Nome do produto]</h5>
																			<h5>[código]</h5>
																		</div>
																	</div>

																	<div style="width: 100%;">
																		<div style="width: 40%; float: right; text-align: left;">
																			<h5 style="font-weight: 900">de: R$ [de2]</h5>
																			<h5 style="font-weight: 900">por: R$ [por2]</h5>
																		</div>
																	</div>
																	<hr />
																	<div style="width: 100%;">
																		<div style="width: 55%; float: left; text-align: left;">
																			<h5 style="font-weight: 900">[Nome do produto]</h5>
																			<h5>[código]</h5>
																		</div>
																	</div>

																	<div style="width: 100%;">
																		<div style="width: 40%; float: right; text-align: left;">
																			<h5 style="font-weight: 900">de: R$ [de3]</h5>
																			<h5 style="font-weight: 900">por: R$ [por3]</h5>
																		</div>
																	</div>
																	<hr />
																	<a class="excluirBloco">
																		<i class="fa fa-trash" style="margin: 0" aria-hidden="true"></i>
																	</a>
																	<hr class="divisao" />
																</div>
															<?php
																break;
															case 3: //lista de promoções black
															?>
																<div style="position: relative">
																	<center style="margin-bottom: 20px; padding: 5px; background-color: #161616; color: #fff">
																		<h5 style="font-weight: 900; margin-bottom: 20px; font-size: 17px;">OFERTA EM DESTAQUE</h5>
																		<h4 style="font-size: 21px; margin-top: 2px; margin-bottom: 2px">ÔMEGA 3 C/60 - ORANGE</h4>
																		<h5 style="font-weight: 500; font-size:12px;">998765</h5>
																		<h4 style="font-weight: 900; font-size: 23px; margin-top: 2px">De R$ 158,49 Por R$ 49,99</h4>
																		<h4 style="margin-top: 20px;">Aproveite!</h4>
																	</center>
																	<a class="excluirBloco black">
																		<i class="fa fa-trash" style="margin: 0" aria-hidden="true"></i>
																	</a>
																	<hr class="divisao" />
																</div>
															<?php
																break;
															case 4: //destaque
															?>
																<div style="position: relative">
																	<center>
																		<h6>ISABEL DE ANDRADE MARTINEZ SALES BR</h6>
																		<h6>Saldo: R$ 0,18</h6>
																		<h6>31/05/2017 às 10:00</h6>
																	</center>
																	<a class="excluirBloco">
																		<i class="fa fa-trash" style="margin: 0" aria-hidden="true"></i>
																	</a>
																	<hr class="divisao" />
																</div>
															<?php
																break;
															case 5: //rodape
															?>
																<div style="position: relative">
																	<center>
																		<h6 style="margin-right: 20px">Ofertas válidas até o término da campanha ou enquanto durar o estoque.</h6>
																		<div class="div-imagem">
																			<?php
																			if (empty(trim($qrListaModelos['DES_IMAGEM']))) {
																			?>
																				<div class="imagemTicket">
																					<button class="btn btn-block btn-success upload-image"><i class="fa fa-cloud-upload" aria-hidden="true"></i>&nbsp; Insira aqui sua imagem</button>
																					<input type="file" cod_registr='<?php echo $qrListaModelos['COD_REGISTR']; ?>' accept="text/cfg" class="form-control image-file" name="arquivo" style="display: none;" />
																				</div>
																			<?php
																			} else {
																			?>
																				<div class="imagemTicket">
																					<img src='../media/clientes/<?php echo $cod_empresa ?>/<?php echo $qrListaModelos['DES_IMAGEM']; ?>' class='upload-image' style='cursor: pointer; max-width:100%; max-height: 100%'>
																					<input type="file" cod_registr='<?php echo $qrListaModelos['COD_REGISTR']; ?>' accept="text/cfg" class="form-control image-file" name="arquivo" style="display: none;" />
																				</div>

																			<?php
																			}
																			?>
																		</div>
																		<a class="excluirBloco">
																			<i class="fa fa-trash" style="margin: 0" aria-hidden="true"></i>
																		</a>
																		<h6 style="font-size: 11px">Ticket de Ofertas | Marka Sistemas</h6>
																	</center>
																	<hr class="divisao" />
																</div>
															<?php
																break;
															case 6: //imagem
															?>
																<div style="position: relative">
																	<div class="div-imagem">
																		<?php
																		if (empty(trim($qrListaModelos['DES_IMAGEM']))) {
																		?>
																			<div class="imagemTicket">
																				<button class="btn btn-block btn-success upload-image"><i class="fa fa-cloud-upload" aria-hidden="true"></i>&nbsp; Insira aqui sua imagem</button>
																				<input type="file" cod_registr='<?php echo $qrListaModelos['COD_REGISTR']; ?>' accept="text/cfg" class="form-control image-file" name="arquivo" style="display: none;" />
																			</div>
																		<?php
																		} else {
																		?>
																			<div class="imagemTicket">
																				<img src='../media/clientes/<?php echo $cod_empresa ?>/<?php echo $qrListaModelos['DES_IMAGEM']; ?>' class='upload-image' style='cursor: pointer; max-width:100%; max-height: 100%'>
																				<input type="file" cod_registr='<?php echo $qrListaModelos['COD_REGISTR']; ?>' accept="text/cfg" class="form-control image-file" name="arquivo" style="display: none;" />
																			</div>
																		<?php
																		}
																		?>
																	</div>
																	<a class="excluirBloco">
																		<i class="fa fa-trash" style="margin: 0" aria-hidden="true"></i>
																	</a>
																	<hr class="divisao" />
																</div>
															<?php
																break;
															case 7: //lista de promoções white
															?>
																<div style="position: relative">
																	<center style="margin-bottom: 15px; padding: 5px; background-color: #fff; color: #000">
																		<h5 style="font-weight: 900; margin-bottom: 20px; font-size: 17px;">OFERTA EM DESTAQUE</h5>
																		<h4 style="font-size: 21px; margin-top: 2px; margin-bottom: 2px">ÔMEGA 3 C/60 - ORANGE</h4>
																		<h5 style="font-weight: 500; font-size:12px;">998765</h5>
																		<h4 style="font-weight: 900; font-size: 23px; margin-top: 2px">De R$ 158,49 Por R$ 49,99</h4>
																		<h4 style="margin-top: 20px;margin-bottom: 0">Aproveite!</h4>
																	</center>
																	<a class="excluirBloco">
																		<i class="fa fa-trash" style="margin: 0" aria-hidden="true"></i>
																	</a>
																	<hr class="divisao" />
																</div>
															<?php
																break;
															case 8: //habito de compras
															?>
																<div style="position: relative; text-align: left; font-size: 13px">
																	<div>&emsp;•&emsp; LISADOR GTS 15ML</div>
																	<div>&emsp;•&emsp; VOLTAREN 100MG RET 10'S</div>
																	<div>&emsp;•&emsp; TORAGESIC 10MG 10'S</div>
																	<a class="excluirBloco">
																		<i class="fa fa-trash" style="margin: 0" aria-hidden="true"></i>
																	</a>
																	<hr class="divisao" />
																</div>
															<?php
																break;
															case 9: //promoções logo white
															case 19: //promoções logo white com percentual
															?>
																<div style="position: relative">
																	<center style="margin-bottom: 15px; padding: 5px; background-color: #fff; color: #000">
																		<h5 style="font-weight: 900; font-size: 17px;">OFERTA EM DESTAQUE</h5>
																		<img style='cursor: pointer; max-width:100%; max-height: 100%;'>
																		<i class="fa fa-picture-o fa-3x" aria-hidden="true"></i>
																		</img>
																		<h4 style="font-size: 21px; margin-bottom: 2px">ÔMEGA 3 C/60 - ORANGE</h4>
																		<h5 style="font-weight: 500; font-size:12px;">998765</h5>
																		<h4 style="font-weight: 900; font-size: 23px; margin-top: 2px">De R$ 158,49 Por R$ 49,99</h4>
																		<?php if ($qrListaModelos['COD_BLTEMPL'] == 19) { ?>
																			<h4 style="font-weight: 900; font-size: 40px; margin-top: 2px"><strong>35%</strong></h4>
																		<?php } ?>
																		<h4 style="margin-top: 20px;margin-bottom: 0">Aproveite!</h4>
																	</center>
																	<a class="excluirBloco">
																		<i class="fa fa-trash" style="margin: 0" aria-hidden="true"></i>
																	</a>
																	<hr class="divisao" />
																</div>
															<?php
																break;
															case 10: //promoções logo black
															?>
																<div style="position: relative">
																	<center style="margin-bottom: 15px; padding: 5px; background-color: #161616; color: #fff">
																		<h5 style="font-weight: 900; font-size: 17px;">OFERTA EM DESTAQUE</h5>
																		<img style='cursor: pointer; max-width:100%; max-height: 100%;'>
																		<i class="fa fa-picture-o fa-3x" aria-hidden="true"></i>
																		</img>
																		<h4 style="font-size: 21px; margin-bottom: 2px">ÔMEGA 3 C/60 - ORANGE</h4>
																		<h5 style="font-weight: 500; font-size:12px;">998765</h5>
																		<h4 style="font-weight: 900; font-size: 23px; margin-top: 2px">De R$ 158,49 Por R$ 49,99</h4>
																		<h4 style="margin-top: 20px;margin-bottom: 0">Aproveite!</h4>
																	</center>
																	<a class="excluirBloco">
																		<i class="fa fa-trash" style="margin: 0" aria-hidden="true"></i>
																	</a>
																	<hr class="divisao" />
																</div>
															<?php
																break;
															case 11: // saldo cartão
															?>
																<div style="position: relative">
																	<center>
																		<h6>ISABEL DE ANDRADE MARTINEZ SALES BR</h6>
																		<h6>Número Cartão: 1234 5678 9012 3456</h6>
																		<h6>Saldo: R$ 0,18</h6>
																		<h6>31/05/2017 às 10:00</h6>
																	</center>
																	<a class="excluirBloco">
																		<i class="fa fa-trash" style="margin: 0" aria-hidden="true"></i>
																	</a>
																	<hr class="divisao" />
																</div>
															<?php
																break;
															case 12: // grupo de desconto - 2 colunas
															?>
																<div style="position: relative;">
																	<div class="row">
																		<div class="col-md-6" style="text-align: left; border-right: 1px dashed gray; padding-bottom: 20px;">
																			<div class="row">
																				<div class="col-md-12">
																					<h5 style="font-weight: 900; font-size: 50px; letter-spacing: -3px;">15%
																						<br /><span style='background-color: #454545; color: #fff; font-weight: 900; padding: 2px 4px 2px 4px; font-size: 11px; letter-spacing: 1px; margin: 0;'>DE DESCONTO</span>
																						<div class="push10"></div>
																				</div>
																			</div>
																			<div class="row">
																				<div class="col-md-12">
																					<div style="font-size: 11px; text-transform: uppercase; margin-top: -10px; margin-left: 5px"><b>[Nome da Categoria]</b></div>
																				</div>
																			</div>
																			<div class="row">
																				<div class="col-md-12">
																					<div style="font-size: 12px; text-transform: uppercase; margin: 0 0 10px 5px;"><b>Produto 1</b><br /><small>[código]</small></div>
																					<div style="font-size: 12px; text-transform: uppercase; margin: 0 0 10px 5px;"><b>Produto 2</b><br /><small>[código]</small></div>
																					<div style="font-size: 12px; text-transform: uppercase; margin: 0 0 10px 5px;"><b>Produto 3</b><br /><small>[código]</small></div>
																					<div style="font-size: 12px; text-transform: uppercase; margin: 0 0 10px 5px;"><b>Produto 4</b><br /><small>[código]</small></div>
																				</div>
																			</div>
																		</div>

																		<div class="col-md-6" style="text-align: left; border-right: 1px dashed gray; padding-bottom: 20px;">
																			<div class="row">
																				<div class="col-md-12">
																					<h5 style="font-weight: 900; font-size: 50px; letter-spacing: -3px;">25%
																						<br /><span style='background-color: #454545; color: #fff; font-weight: 900; padding: 2px 4px 2px 4px; font-size: 11px; letter-spacing: 1px; margin: 0;'>DE DESCONTO</span>
																						<div class="push10"></div>
																				</div>
																			</div>

																			<div class="row">
																				<div class="col-md-12">
																					<div style="font-size: 11px; text-transform: uppercase; margin-top: -10px; margin-left: 5px"><b>[Nome da Categoria]</b></div>
																				</div>
																			</div>
																			<div class="row">
																				<div class="col-md-12">
																					<div style="font-size: 12px; text-transform: uppercase; margin: 0 0 10px 5px;"><b>Produto 1</b><br /><small>[código]</small></div>
																					<div style="font-size: 12px; text-transform: uppercase; margin: 0 0 10px 5px;"><b>Produto 2</b><br /><small>[código]</small></div>
																					<div style="font-size: 12px; text-transform: uppercase; margin: 0 0 10px 5px;"><b>Produto 3</b><br /><small>[código]</small></div>
																					<div style="font-size: 12px; text-transform: uppercase; margin: 0 0 10px 5px;"><b>Produto 4</b><br /><small>[código]</small></div>
																				</div>
																			</div>
																		</div>
																	</div>

																	<a class="excluirBloco">
																		<i class="fa fa-trash" style="margin: 0" aria-hidden="true"></i>
																	</a>
																	<hr class="divisao" />
																</div>
															<?php
																break;

															case 21: //grupo de desconto - 1 coluna
															?>
																<div style="position: relative">

																	<center>
																		<h5 style="margin-top: 5px">Veja <b>ofertas personalizadas </b> para você!</h5>
																	</center>

																	<div style="width: 70%;  float:left;">

																		<div style="width: 100%; height: auto; text-align: left; margin: 0 0 20px 0;">
																			<h5 style="font-weight: 900">[Nome da Categoria]</h5>
																			<h5 style="font-weight: 900">[Nome do produto]</h5>
																			<h5>[código]</h5>
																		</div>

																		<div style="width: 100%; height: auto; text-align: left; margin: 0 0 20px 0;">
																			<h5 style="font-weight: 900">[Nome do produto]</h5>
																			<h5>[código]</h5>
																		</div>

																		<div style="width: 100%; height: auto; text-align: left; margin: 0 0 20px 0;">
																			<h5 style="font-weight: 900">[Nome do produto]</h5>
																			<h5>[código]</h5>
																		</div>

																	</div>

																	<div style="width: 29%; float:right;">
																		<div style="width: 100%; text-align: center;">
																			<h5 style="font-weight: 900; font-size: 50px; letter-spacing: -3px;">15%
																				<br /><span style='background-color: #454545; color: #fff; font-weight: 900; padding: 2px 4px 2px 4px; font-size: 11px; letter-spacing: 1px; margin: 0;'>DE DESCONTO</span>
																		</div>
																	</div>


																	<a class="excluirBloco">
																		<i class="fa fa-trash" style="margin: 0" aria-hidden="true"></i>
																	</a>
																	<hr class="divisao" />
																</div>
															<?php
																break;

															case 15: //Código com Número
															?>
																<div style="position: relative">
																	<center>
																		<img style='cursor: pointer; max-width:100%; max-height: 100%' src="/images/codigo-barras.png" width="320" height="70"></img>
																		<h5 style="margin-bottom: 0px">1234567890123</h5>
																	</center>
																	<a class="excluirBloco">
																		<i class="fa fa-trash" style="margin: 0" aria-hidden="true"></i>
																	</a>
																	<hr class="divisao" />
																</div>
															<?php
																break;
															case 16: //Código sem Número
															?>
																<div style="position: relative">
																	<center>
																		<img style='cursor: pointer; max-width:100%; max-height: 100%' src="/images/codigo-barras.png" width="320" height="70"></img>
																	</center>
																	<a class="excluirBloco">
																		<i class="fa fa-trash" style="margin: 0" aria-hidden="true"></i>
																	</a>
																	<hr class="divisao" />
																</div>
															<?php
																break;
															case 17: //Última Compra
															?>
																<div style="position: relative">
																	<center><small>Dat. Referência: 01/01/2017</small></center>
																	<a class="excluirBloco">
																		<i class="fa fa-trash" style="margin-top: -10px;" aria-hidden="true"></i>
																	</a>
																	<hr class="divisao" style="margin-top: 5px" />
																</div>
															<?php
																break;
															case 18: //Produtos modelo 2 (desconto white)
															case 20: //Produtos modelo 3 (desconto black)
															?>
																<div style="position: relative">
																	<center>
																		<h5 style="margin-top: 5px">Veja <b>ofertas personalizadas </b> para você!</h5>
																	</center>

																	<?php if ($qrListaModelos['COD_BLTEMPL'] == 20) { ?>
																		<div style="background: #000; display: flex; color: #fff; padding: 0 0 0 5px; margin: 0 0 10px 0;">
																		<?php } ?>

																		<div style="width: 70%;  float:left;">
																			<div style="width: 100%; height: auto; text-align: left;">
																				<h5 style="font-weight: 900">[Nome da Categoria 3]</h5>
																				<h5 style="font-weight: 900">[Nome do produto]</h5>
																				<h5 style="font-weight: 900">de: R$ [de1] por: R$ [por1]</h5>
																				<h5>[código]</h5>
																			</div>
																		</div>

																		<div style="width: 29%; float:right;">
																			<div style="width: 100%; text-align: center;">
																				<h5 style="font-weight: 900; font-size: 45px; letter-spacing: -3px;">35%</h5>
																			</div>
																		</div>

																		<?php if ($qrListaModelos['COD_BLTEMPL'] == 20) { ?>
																		</div>
																	<?php } else {
																	?>
																		<hr />
																	<?php } ?>

																	<?php if ($qrListaModelos['COD_BLTEMPL'] == 20) { ?>
																		<div style="background: #000; display: flex; color: #fff; padding: 0 0 0 5px; margin: 0 0 10px 0;">
																		<?php } ?>

																		<div style="width: 70%;  float:left;">
																			<div style="width: 100%; height: auto; text-align: left;">
																				<h5 style="font-weight: 900">[Nome da Categoria]</h5>
																				<h5 style="font-weight: 900">[Nome do produto]</h5>
																				<h5 style="font-weight: 900">de: R$ [de1] por: R$ [por1]</h5>
																				<h5>[código]</h5>
																			</div>
																		</div>

																		<div style="width: 29%; float:right;">
																			<div style="width: 100%; text-align: center;">
																				<h5 style="font-weight: 900; font-size: 45px; letter-spacing: -3px;">35%</h5>
																			</div>
																		</div>

																		<?php if ($qrListaModelos['COD_BLTEMPL'] == 20) { ?>
																		</div>
																	<?php } else {
																	?>
																		<hr />
																	<?php } ?>

																	<?php if ($qrListaModelos['COD_BLTEMPL'] == 20) { ?>
																		<div style="background: #000; display: flex; color: #fff; padding: 0 0 0 5px; margin: 0 0 10px 0;">
																		<?php } ?>

																		<div style="width: 70%;  float:left;">
																			<div style="width: 100%; height: auto; text-align: left;">
																				<h5 style="font-weight: 900">[Nome da Categoria]</h5>
																				<h5 style="font-weight: 900">[Nome do produto]</h5>
																				<h5 style="font-weight: 900">de: R$ [de1] por: R$ [por1]</h5>
																				<h5>[código]</h5>
																			</div>
																		</div>

																		<div style="width: 29%; float:right;">
																			<div style="width: 100%; text-align: center;">
																				<h5 style="font-weight: 900; font-size: 45px; letter-spacing: -3px;">35%</h5>
																			</div>
																		</div>

																		<?php if ($qrListaModelos['COD_BLTEMPL'] == 20) { ?>
																		</div>
																	<?php } else {
																	?>
																		<hr />
																	<?php } ?>

																	<a class="excluirBloco">
																		<i class="fa fa-trash" style="margin: 0" aria-hidden="true"></i>
																	</a>
																	<hr class="divisao" />
																</div>
															<?php
																break;
															case 22: //texto livre
															?>
																<div style="position: relative">
																	<div class="div-texto">

																		<?php
																		if ($qrListaModelos['DES_TEXTO'] != "") {
																			$qrListaModelos['DES_TEXTO'] = html_entity_decode($qrListaModelos['DES_TEXTO']);
																			$qrListaModelos['DES_TEXTO'] = preg_replace('#<a.*?>(.*?)</a>#i', '\1', $qrListaModelos['DES_TEXTO']);
																		?>
																			<a href="javascript:void(0)" class="addBox" data-url="action.do?mod=<?php echo fnEncode(1597) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idr=<?php echo fnEncode($qrListaModelos['COD_REGISTR']) ?>&pop=true" data-title="Editar Texto" style="text-decoration: none;">
																				<?= $qrListaModelos['DES_TEXTO'] ?>
																			</a>

																		<?php
																		} else {
																		?>

																			<div style="height:auto; width: 100%;  display: flex; align-items: center; justify-content: center; padding: 10px; padding-right: 20px;">
																				<a href="javascript:void(0)" class="btn btn-block btn-success addBox" data-url="action.do?mod=<?php echo fnEncode(1597) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idr=<?php echo fnEncode($qrListaModelos['COD_REGISTR']) ?>&pop=true" data-title="Editar Texto" style="color: white; text-decoration: none;"><span class="far fa-text-height"></span>&nbsp;&nbsp; Insira aqui seu texto</a>
																				<input type="file" cod_registr='<?php echo $qrListaModelos['COD_REGISTR']; ?>' accept="text/cfg" class="form-control image-file" name="arquivo" style="display: none;" />
																			</div>

																		<?php
																		}
																		?>

																	</div>
																	<a class="excluirBloco">
																		<i class="fa fa-trash" style="margin: 0" aria-hidden="true"></i>
																	</a>
																	<hr class="divisao" />
																</div>
															<?php
																break;
															case 23: //Aniversariante

																$sqlMsg = "SELECT * FROM COMUNICACAO_MODELO_TKT WHERE COD_EMPRESA = $cod_empresa LIMIT 1";
																// echo($sql);
																$arrayMsg = mysqli_query(connTemp($cod_empresa, ""), $sqlMsg);

																$qrBuscaComunicacao = mysqli_fetch_assoc($arrayMsg);

																$temMsg = mysqli_num_rows($arrayMsg);

																$msg = $qrBuscaComunicacao['DES_TEXTO_SMS'];

																$dia_hoje = date('d');
																$mes_hoje = date('m');
																$ano_hoje = date('Y');
																$dia_nascime = $dia_hoje;
																$mes_nascime = $mes_hoje;
																$ano_nascime = '2000';

																$NOM_CLIENTE = explode(" ", ucfirst(strtolower("Fulano")));
																$TEXTOENVIO = str_replace('<#NOME>', $NOM_CLIENTE['0'], $msg);
																$TEXTOENVIO = str_replace('<#SALDO>', fnValor('9.99', 2), $TEXTOENVIO);
																$TEXTOENVIO = str_replace('<#NOMELOJA>',  'Unidade Tal', $TEXTOENVIO);
																$TEXTOENVIO = str_replace('<#ANIVERSARIO>', '01/01/2000', $TEXTOENVIO);
																$TEXTOENVIO = str_replace('<#DATAEXPIRA>', '01/01/2999', $TEXTOENVIO);
																$TEXTOENVIO = str_replace('<#EMAIL>', 'exemplo@email.com', $TEXTOENVIO);
																$msgsbtr = nl2br($TEXTOENVIO, true);
																$msgsbtr = str_replace('<br />', ' \n ', $msgsbtr);
																$msgsbtr = str_replace(array("\r", "\n"), '', $msgsbtr);


															?>
																<div style="position: relative">
																	<div class="div-aniv">
																		<?php
																		if ($msgsbtr != '') {
																		?>
																			<div class="imagemTicket text-center f18">
																				<a href="javascript:void(0)" class="addBox" data-url="action.do?mod=<?php echo fnEncode(1916) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idC=<?= fnEncode($qrBuscaComunicacao['COD_COMUNIC']) ?>&pop=true" data-title="Editar Texto"><?= $msgsbtr ?></a>
																			</div>
																		<?php
																		} else {
																		?>
																			<div class="imagemTicket">
																				<a href="javascript:void(0)" class="btn btn-block btn-xs btn-info addBox" data-url="action.do?mod=<?php echo fnEncode(1916) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idC=<?= fnEncode($qrBuscaComunicacao['COD_COMUNIC']) ?>&pop=true" data-title="Editar Texto"><i class="fa fa-cog" aria-hidden="true"></i>&nbsp; Configurar</a>
																				<input type="file" cod_registr='<?php echo $qrListaModelos['COD_REGISTR']; ?>' accept="text/cfg" class="form-control image-file" name="arquivo" style="display: none;" />
																			</div>
																		<?php
																		}
																		?>
																	</div>
																	<a class="excluirBloco">
																		<i class="fa fa-trash" style="margin: 0" aria-hidden="true"></i>
																	</a>
																	<hr class="divisao" />
																</div>
														<?php
																break;
														}

														?>
													</li>
												<?php
												}
												?>
											</ul>
										</div>
										<div class="col-md-2">
											<ul id="sortable3" class="connectedSortable">
												<?php
												$sql = "select * from BLOCOTEMPLATE where NUM_ORDENAC > $qtdeBloco order by NUM_ORDENAC ";
												$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

												while ($qrListaBlocos = mysqli_fetch_assoc($arrayQuery)) {
												?>
													<li class="ui-state-default shadow grabbable" cod-registr="" cod-bloco="<?php echo $qrListaBlocos['COD_BLTEMPL'] ?>">
														<i class="<?php echo $qrListaBlocos['DES_ICONE'] ?>" aria-hidden="true"></i>
														<div class="descricaobloco"><?php echo $qrListaBlocos['ABV_BLTEMPL'] ?></div>
													</li>
												<?php
												}
												?>
											</ul>
										</div>
									</div>
								</div>
							</div>
						</div>


						<div class="100"></div>

						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">

						<div class="push5"></div>

					</form>

					<div class="push100"></div>



				</div>

			</div>
		</div>
		<!-- fim Portlet -->
	</div>



</div>


<!-- modal -->
<div class="modal fade" id="popModal" tabindex='-1' style="margin: auto;">
	<div class="modal-dialog" style="">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"></h4>
			</div>
			<div class="modal-body">
				<iframe frameborder="0" style="width: 100%; height: 86%"></iframe>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div class="push20"></div>

<link rel="stylesheet" href="js/plugins/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css" />
<script type="text/javascript" src="js/bootstrap-iconpicker-iconset-fa5.js"></script>
<script type="text/javascript" src="js/plugins/bootstrap-iconpicker/js/bootstrap-iconpicker.min.js"></script>

<script src="js/jquery-ui.js"></script>

<script type="text/javascript">
	$(document).ready(function() {

		$('body').on('click', '.upload-image', function() {
			$(this).siblings().click();
		});

		$('body').on('change', '.image-file', function() {
			var formData = new FormData();
			formData.append('arquivo', $(this)[0].files[0]);
			formData.append('id', "<?= fnEncode($cod_empresa) ?>");
			formData.append('cod_registr', $(this).attr('cod_registr'));

			var div_imagem = $(this).parent().parent();

			$.ajax({
				url: 'uploads/uploadpro.php',
				type: 'POST',
				data: formData,
				processData: false, // tell jQuery not to process the data
				contentType: false, // tell jQuery not to set contentType
				success: function(data) {
					div_imagem.html(data);
				}
			});
		});

		//icon picker
		$('.btnSearchIcon').iconpicker({
			cols: 8,
			iconset: 'fontawesome',
			rows: 6,
			searchText: 'Procurar  &iacute;cone'
		});

		$('.btnSearchIcon').on('change', function(e) {
			//console.log(e.icon);
			$("#DES_ICONES").val(e.icon);
		});

		// Builder
		$('#sortable2').css('min-height', $('#sortable1').height());
		//$('#sortable2').width($('#sortable2').parent().width() - 20);

		var altura = $('.template').height();
		var itens = $('#sortable1 li').length;

		$('#sortable1 > li').css('height', (altura - 10) / itens - 2);

		var listaHeight = $('#sortable2').height();
		var listaContent = 0;

		$("#sortable1").sortable({
			connectWith: ".connectedSortable",
			remove: function(event, ui) {
				var idTem = <?php echo $cod_template ?>;
				var idEmp = <?php echo $cod_empresa ?>;
				var codBloco = ui.item.attr('cod-bloco');
				var cod_registr = ""

				// Adicionar modelo template
				$.ajax({
					type: "GET",
					url: "ajxBlocoTkt.do",
					data: {
						ajx1: idEmp,
						ajx2: 0,
						ajx3: idTem,
						ajx4: codBloco
					},
					success: function(data) {
						cod_registr = data.trim();
						var indice = ui.item.index();
						ui.item.clone().attr('cod-registr', data.trim()).removeClass('shadow').insertBefore($('#sortable2 li').eq(indice));
						$('#sortable1').sortable('cancel');

						// Retorna info li
						$.ajax({
							type: "GET",
							url: "ajxBlocoTkt.do",
							data: {
								ajx1: idEmp,
								ajx2: codBloco,
								ajx3: idTem,
								ajx4: cod_registr
							},
							beforeSend: function() {
								$('#sortable2 li[cod-registr=' + cod_registr + ']').html('<div class="loading" style="width: 100%;"></div>');
							},
							success: function(data) {
								$('#sortable2 li[cod-registr=' + cod_registr + ']').html(data);
								ordenar();
							},
							error: function() {
								$('#sortable2 li[cod-registr=' + cod_registr + ']').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
							}
						});
					},
				});
			}
		}).disableSelection();


		$("#sortable3").sortable({
			connectWith: ".connectedSortable",
			remove: function(event, ui) {
				var idTem = <?php echo $cod_template ?>;
				var idEmp = <?php echo $cod_empresa ?>;
				var codBloco = ui.item.attr('cod-bloco');
				var cod_registr = ""

				// Adicionar modelo template
				$.ajax({
					type: "GET",
					url: "ajxBlocoTkt.do",
					data: {
						ajx1: idEmp,
						ajx2: 0,
						ajx3: idTem,
						ajx4: codBloco
					},
					success: function(data) {
						cod_registr = data.trim();
						var indice = ui.item.index();
						ui.item.clone().attr('cod-registr', data.trim()).removeClass('shadow').insertBefore($('#sortable2 li').eq(indice));
						$('#sortable3').sortable('cancel');

						// Retorna info li
						$.ajax({
							type: "GET",
							url: "ajxBlocoTkt.do",
							data: {
								ajx1: idEmp,
								ajx2: codBloco,
								ajx3: idTem,
								ajx4: cod_registr
							},
							beforeSend: function() {
								$('#sortable2 li[cod-registr=' + cod_registr + ']').html('<div class="loading" style="width: 100%;"></div>');
							},
							success: function(data) {
								$('#sortable2 li[cod-registr=' + cod_registr + ']').html(data);
								ordenar();
							},
							error: function() {
								$('#sortable2 li[cod-registr=' + cod_registr + ']').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
							}
						});
					},
				});
			}
		}).disableSelection();

		$("#sortable2").sortable({
			connectWith: ".connectedSortable",
			stop: function(event, ui) {
				ordenar();
			}
		}).disableSelection();

		$('body').on('click', '.excluirBloco', function() {
			var cod_registr = $(this).parents('.ui-state-default').attr('cod-registr');
			var _this = $(this).parents('.ui-state-default');
			var idEmp = <?php echo $cod_empresa ?>;

			$.ajax({
				type: "GET",
				url: "ajxBlocoTkt.do",
				data: {
					ajx1: idEmp,
					ajx2: 99,
					ajx3: cod_registr
				},
				beforeSend: function() {
					$('#sortable2 li[cod-registr=' + cod_registr + ']').html('<div class="loading" style="width: 100%;"></div>');
				},
				success: function(data) {
					_this.remove();
					$('#sortable2 li[cod-registr=' + cod_registr + ']').html(data);
				},
				error: function() {
					$('#sortable2 li[cod-registr=' + cod_registr + ']').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
				}
			});
		});
	});

	function ordenar() {
		var ids = "";
		$('#sortable2 li.ui-state-default').each(function(index) {
			ids += $(this).attr('cod-registr') + ",";
		});

		var arrayOrdem = ids.substring(0, (ids.length - 1));
		execOrdenacao(arrayOrdem, 2);

		function execOrdenacao(p1, p2) {
			var codEmpresa = <?php echo $cod_empresa ?>;
			$.ajax({
				type: "GET",
				url: "ajxOrdenacaoEmp.do",
				data: {
					ajx1: p1,
					ajx2: p2,
					ajx3: codEmpresa
				},
				beforeSend: function() {
					//$('#divId_sub').html('<div class="loading" style="width: 100%;"></div>');
				},
				success: function(data) {
					//$("#divId_sub").html(data); 
				},
				error: function() {
					//$('#divId_sub').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Falha no processamento...</p>');
				}
			});
		}
	}

	function retornaForm(index) {
		$("#formulario #COD_CATEGORTKT").val($("#ret_COD_CATEGORTKT_" + index).val());
		$("#formulario #DES_CATEGOR").val($("#ret_DES_CATEGOR_" + index).val());
		$("#formulario #DES_ABREVIA").val($("#ret_DES_ABREVIA_" + index).val());
		$("#formulario #DES_ICONES").val($("#ret_DES_ICONES_" + index).val());
		$('#btnIcon').iconpicker('setIcon', $("#ret_DES_ICONES_" + index).val());
		if ($("#ret_LOG_DESTAK_" + index).val() == 'S') {
			$('#formulario #LOG_DESTAK').prop('checked', true);
		} else {
			$('#formulario #LOG_LOG_DESTAK').prop('checked', false);
		}
		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
	}
</script>