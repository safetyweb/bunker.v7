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
$cod_prodtkt = "";
$nom_prodtkt = "";
$cod_produto = "";
// $fil_univend_aut = "";
// $fil_univend_blk = "";
$log_ativotk = "";
$log_prodtkt = "";
$dat_iniptkt = "";
$dat_fimptkt = "";
$pct_desctkt = "";
$val_prodtkt = "";
$val_promtkt = "";
$log_habitkt = "";
$log_ofertas = "";
$cod_persona_tkt = "";
$Arr_COD_PERSONA_TKT = "";
$Arr_COD_MULTEMP = "";
$i = 0;
$cod_univend_aut = "";
$Arr_COD_UNIVEND_AUT = "";
$cod_univend_blk = "";
$Arr_COD_UNIVEND_BLK = "";
$cod_categortkt = "";
$fil_categortkt = "";
$cod_desctkt = "";
$des_mensgtkt = "";
$hHabilitado = "";
$hashForm = "";
$cod_usucada = "";
$univend_aut = "";
$univend_blk = "";
$orBlk = "";
$sqlVerifica = "";
$arrVerifica = "";
$qtdVerifica = 0;
$sql2 = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$log_tktunivend = "";
$andLojasUsu = "";
$optAllUnivend = "";
$CarregaMaster = "";
$lojasUsuario = "";
$arrLojasAut = "";
$arrayLojasAut2 = [];
$sqlPersonasExtras = "";
$arrayExtra = [];
$qrExtra = "";
$personasExtra = "";
$arrPerExtra = "";
$esconde = "";
$uniAutObg = "";
$lblAutObg = "";
$txtAutObg = "";
$radioAutObg = "";
$where = "";
$fil_univend = "";
$popUp = "";
$abaModulo = "";
$qrListaCategoria = "";
$qrListaUnive = "";
$qrListaGrupo = "";
$qrListaPersonas = [];
$persona = [];
$orderBy = "";
$andFiltro = "";
$sqlCat = "";
$arrayCat = [];
$cod_categor = "";
$qrCat = "";
$ARRAY_UNIDADE1 = [];
$ARRAY_UNIDADE = [];
$ARRAY_VENDEDOR1 = [];
$ARRAY_VENDEDOR = [];
$retorno = "";
$inicio = "";
$teste = "";
$qrBuscaProdutosTkt = "";
$NOM_ARRAY_NON_VENDEDOR = [];
$lojaLoop = "";
$nomeLoja = "";
$NOM_ARRAY_UNIDADE = [];
$mostraLOG_ATIVOTK = "";
$mostraLOG_PRODTKT = "";
$mostraCOD_UNIVEND_AUT = "";
$mostraCOD_UNIVEND_BLK = "";
$mostraDES_IMAGEM = "";
$mostraOFERTAS = "";
$mostraAUTOMATIC = "";
$mostraValidade = "";
$mostraValidadeHora = "";
$mostraInvalidado = "";
$textoDanger = "";
$e = "";
$arrayPersonas = [];
$valores = "";
$iconePersona = "";
$obj = "";
$log_destaque = "";


$hashLocal = mt_rand();
$itens_por_pagina = 50;

// Página default
$pagina = 1;

$conn = connTemp($cod_empresa, '');
$adm = $connAdm->connAdm();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_prodtkt = fnLimpaCampoZero(@$_POST['COD_PRODTKT']);
		$nom_prodtkt = fnLimpaCampo(@$_POST['NOM_PRODTKT']);
		$cod_empresa = fnLimpaCampoZero(@$_POST['COD_EMPRESA']);
		$cod_produto = fnLimpaCampoZero(@$_POST['COD_PRODUTO']);
		$cod_univend = fnLimpaCampoZero(@$_POST['COD_UNIVEND']);
		$cod_univend = fnLimpaCampoZero(@$_POST['COD_UNIVEND']);
		$fil_univend_aut =  isset($_POST['FIL_UNIVEND_AUT'])  && fnLimpaCampoArray(@$_POST['FIL_UNIVEND_AUT']);
		$fil_univend_blk =  isset($_POST['FIL_UNIVEND_AUT']) &&  fnLimpaCampoArray(@$_POST['FIL_UNIVEND_BLK']);

		if (empty(@$_REQUEST['LOG_ATIVOTK'])) {
			$log_ativotk = 'N';
		} else {
			$log_ativotk = @$_REQUEST['LOG_ATIVOTK'];
		}
		if (empty(@$_REQUEST['LOG_PRODTKT'])) {
			$log_prodtkt = 'N';
		} else {
			$log_prodtkt = @$_REQUEST['LOG_PRODTKT'];
		}
		if (empty(@$_REQUEST['LOG_DESTAQUE'])) {
			$log_destaque = 'N';
		} else {
			$log_destaque = @$_REQUEST['LOG_DESTAQUE'];
		}

		$dat_iniptkt = fnDataSql(@$_POST['DAT_INIPTKT']);
		if ($dat_iniptkt != '' && $dat_iniptkt != 0) {
			$dat_iniptkt = $dat_iniptkt . " 00:00:00";
		}

		$dat_fimptkt = fnDataSql(@$_POST['DAT_FIMPTKT']);
		if ($dat_fimptkt != '' && $dat_fimptkt != 0) {
			$dat_fimptkt = $dat_fimptkt . " 23:59:59";
		}

		$pct_desctkt = fnLimpaCampo(@$_POST['PCT_DESCTKT']);
		$val_prodtkt = fnLimpaCampo(@$_POST['VAL_PRODTKT']);
		$val_promtkt = fnLimpaCampo(@$_POST['VAL_PROMTKT']);
		$log_habitkt = fnLimpaCampo(@$_POST['LOG_HABITKT']);
		if (empty(@$_REQUEST['LOG_HABITKT'])) {
			$log_habitkt = 'N';
		} else {
			$log_habitkt = @$_REQUEST['LOG_HABITKT'];
		}
		if (empty(@$_REQUEST['LOG_OFERTAS'])) {
			$log_ofertas = 'N';
		} else {
			$log_ofertas = @$_REQUEST['LOG_OFERTAS'];
		}

		//$cod_persona_tkt = fnLimpaCampo(@$_POST['COD_PERSONA_TKT']);
		//array das personas
		if (isset($_POST['COD_PERSONA_TKT'])) {
			$Arr_COD_PERSONA_TKT = @$_POST['COD_PERSONA_TKT'];
			//print_r($Arr_COD_MULTEMP);			 
			for ($i = 0; $i < count($Arr_COD_PERSONA_TKT); $i++) {
				$cod_persona_tkt = $cod_persona_tkt . $Arr_COD_PERSONA_TKT[$i] . ",";
			}
			$cod_persona_tkt = substr($cod_persona_tkt, 0, -1);
		} else {
			$cod_persona_tkt = "0";
		}

		//$cod_univend_aut = fnLimpaCampo(@$_POST['COD_UNIVEND_AUT']);
		//array das lojas
		if (isset($_POST['COD_UNIVEND_AUT'])) {
			$Arr_COD_UNIVEND_AUT = @$_POST['COD_UNIVEND_AUT'];
			//print_r($Arr_COD_MULTEMP);			 
			for ($i = 0; $i < count($Arr_COD_UNIVEND_AUT); $i++) {
				$cod_univend_aut = $cod_univend_aut . $Arr_COD_UNIVEND_AUT[$i] . ",";
			}
			$cod_univend_aut = substr($cod_univend_aut, 0, -1);
		} else {
			$cod_univend_aut = "0";
		}

		//$cod_univend_blk = fnLimpaCampo(@$_POST['COD_UNIVEND_BLK']);			
		//array das lojas
		if (isset($_POST['COD_UNIVEND_BLK'])) {
			$Arr_COD_UNIVEND_BLK = @$_POST['COD_UNIVEND_BLK'];
			//print_r($Arr_COD_MULTEMP);			 
			for ($i = 0; $i < count($Arr_COD_UNIVEND_BLK); $i++) {
				$cod_univend_blk = $cod_univend_blk . $Arr_COD_UNIVEND_BLK[$i] . ",";
			}
			$cod_univend_blk = substr($cod_univend_blk, 0, -1);
		} else {
			$cod_univend_blk = "0";
		}

		$cod_categortkt = fnLimpaCampo(@$_POST['COD_CATEGORTKT']);
		$fil_categortkt = fnLimpaCampo(@$_POST['FIL_CATEGORTKT']);
		$cod_desctkt = fnLimpaCampo(@$_REQUEST['COD_DESCTKT']);
		if (empty($cod_desctkt)) {
			$cod_desctkt = 0;
		}

		$des_mensgtkt = fnLimpaCampo(@$_POST['DES_MENSGTKT']);

		// - variáveis da barra de pesquisa -------------
		$filtro = fnLimpaCampo(@$_POST['VAL_PESQUISA']);
		$val_pesquisa = fnLimpaCampo(@$_POST['INPUT']);
		// ----------------------------------------------

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		if ($opcao == "EXC") {

			$sql = "DELETE FROM PRODUTOTKT 
						WHERE  COD_PRODTKT = $cod_prodtkt
						AND COD_EMPRESA = $cod_empresa";

			mysqli_query($conn, trim($sql));
			$msgTipo = 'alert-success';
			$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";
		} else if ($opcao == "EXC_SEL") {
			$cod_prodtkt = fnLimpaCampo(@$_POST['CODS_PRODTKT']);
			$sql = "DELETE FROM PRODUTOTKT WHERE  COD_PRODTKT IN (0$cod_prodtkt)";
			mysqli_query($conn, trim($sql));
			$msgTipo = 'alert-success';
			$msgRetorno = "Registro(s) excluido(s) com <strong>sucesso!</strong>";
		} elseif ($opcao != '' && $opcao != 'FIL' && $opcao != 'FIL2') {

			//fnEscreve($dat_iniptkt);	

			$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
			$univend_aut = "0|" . str_replace(",", "|", $cod_univend_aut);
			$univend_blk = str_replace(",", "|", $cod_univend_blk);

			$orBlk = "OR CONCAT(',', COD_UNIVEND_BLK, ',')  REGEXP ',($univend_blk),'";

			if ($univend_blk == 0) {
				$orBlk = "";
			}

			$sqlVerifica = "SELECT 1 FROM PRODUTOTKT
							WHERE COD_PRODUTO = $cod_produto
							AND COD_EMPRESA = $cod_empresa
							AND (
								CONCAT(',', COD_UNIVEND_AUT, ',')  REGEXP ',($univend_aut),' 
								$orBlk
							)";

			// fnEscreve($sqlVerifica);

			$arrVerifica = mysqli_query($conn, trim($sqlVerifica));

			$qtdVerifica = mysqli_num_rows($arrVerifica);

			if ($qtdVerifica == 0 || $opcao == "ALT") {

				$sql = "CALL SP_ALTERA_PRODUTOTKT (
					 '" . $cod_prodtkt . "', 
					 '" . $cod_empresa . "', 
					 '" . $cod_produto . "', 
					 '" . $nom_prodtkt . "', 
					 " . fnDateSql($dat_iniptkt) . ",
					 " . fnDateSql($dat_fimptkt) . ",
					 '" . fnValorSql($pct_desctkt) . "',
					 '" . fnValorSql($val_prodtkt) . "',
					 '" . fnValorSql($val_promtkt) . "',
					 '" . $cod_persona_tkt . "',
					 '" . $cod_univend_aut . "',
					 '" . $cod_univend_blk . "',
					 '" . $cod_univend . "',
					 '" . $log_prodtkt . "',
					 '" . $log_habitkt . "',
					 '" . $cod_desctkt . "',
					 '" . $cod_usucada . "', 
					 '" . $cod_categortkt . "', 
					 '" . $log_ofertas . "', 
					 '" . $des_mensgtkt . "', 
					 '" . $log_ativotk . "', 
					 '" . $opcao . "'    
					) ";

				// fnEscreve($sql);

				mysqli_query($conn, trim($sql));
				//fnEscreve($sql2); 
				if ($opcao == "ALT") {
?>
					<script>
						$("#FIL").click();
					</script>
<?php
				}

				$msgTipo = 'alert-success';
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
			} else {
				$msgRetorno = "Este produto já está cadastrado para uma das unidades selecionadas";
				$msgTipo = 'alert-danger';
			}
		}
	}
}

//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode(@$_GET['id']);
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI, LOG_TKTUNIVEND FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";

	// echo($sql);
	$arrayQuery = mysqli_query($adm, $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($qrBuscaEmpresa)) {
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
		$log_tktunivend = $qrBuscaEmpresa['LOG_TKTUNIVEND'];
	}
} else {
	$cod_empresa = 0;
	$nom_empresa = "";
	$log_tktunivend = "N";
}

//	fnMostraForm();
// echo($log_tktunivend);

$andLojasUsu = "";
$optAllUnivend = "<option value='9999'>Todas Unidades</option>";
$CarregaMaster = '1';

// echo $_SESSION["SYS_COD_UNIVEND"];
if (fnAutMaster($_SESSION["SYS_COD_TPUSUARIO"], $_SESSION["SYS_COD_EMPRESA"]) == '0') {

	$lojasUsuario = $_SESSION["SYS_COD_UNIVEND"];
	$arrLojasAut = explode(",", $_SESSION["SYS_COD_UNIVEND"]);
	$arrayLojasAut2 = str_replace(",", "|", $_SESSION["SYS_COD_UNIVEND"]);
	$andLojasUsu = "AND COD_UNIVEND IN ($lojasUsuario)";
	$optAllUnivend = "";
	$CarregaMaster = '0';
}

@$sqlPersonasExtras = "SELECT COD_PERSONAS 
						FROM personas_ticket 
						WHERE (COD_UNIVEND REGEXP '^($arrayLojasAut2)' OR COD_UNIVEND = '9999')
						AND COD_EMPRESA = $cod_empresa";

$arrayExtra = mysqli_query($conn, $sqlPersonasExtras);

while (@$qrExtra = mysqli_fetch_assoc($arrayExtra)) {
	$personasExtra .= $qrExtra['COD_PERSONAS'] . ",";
}

@$personasExtra = rtrim(ltrim($personasExtra, ","), ",");

$arrPerExtra = array_unique(explode(",", $personasExtra));

// echo "<pre>";
// echo $sqlPersonasExtras;
// print_r($arrPerExtra);
// echo "</pre>";

// esquema do X da barra - (recarregar pesquisa)
if (@$val_pesquisa != '') {
	$esconde = " ";
} else {
	$esconde = "display: none;";
}
// ---------------------------------------------

$uniAutObg = "disabled";
$lblAutObg = "";
$txtAutObg = "Se vazio <b>todas</b> as unidades estarão <b>autorizadas</b>";
$radioAutObg = "";

if ($_SESSION["SYS_COD_TPUSUARIO"] != 9 && $_SESSION["SYS_COD_TPUSUARIO"] != 16 && $_SESSION["SYS_COD_TPUSUARIO"] != 6) {
	$uniAutObg = "required";
	$lblAutObg = "required";
	$txtAutObg = "";
	$radioAutObg = "checked disabled";
}

$CarregaMaster = '1';

if ($log_tktunivend == "S") {

	if (fnAutMaster($_SESSION["SYS_COD_TPUSUARIO"], $_SESSION["SYS_COD_EMPRESA"]) == '1') {
		$CarregaMaster = '1';
	} else {
		$CarregaMaster = '0';
	}
}

// echo $CarregaMaster;

$where = "";

if ($log_tktunivend == "S" && $CarregaMaster == '0') {
	// $where .= " AND COD_UNIVEND REGEXP ('" . implode("|", explode(",", @$_SESSION['SYS_COD_UNIVEND'])) . "')";
	$where .= " AND CONCAT(',', PRODUTOTKT.COD_UNIVEND, ',')  REGEXP ',(" . implode("|", explode(",", @$_SESSION['SYS_COD_UNIVEND'])) . "),'";
}

if (@$_POST["LOG_VALIDO"] == "S") {
	$where .= " AND NOW() <= PRODUTOTKT.DAT_FIMPTKT";
}
if (@$_POST["FIL_CATEGORTKT"] <> "") {
	$where .= " AND categoriatkt.COD_CATEGORTKT = '" . @$_POST["FIL_CATEGORTKT"] . "'";
}
if (!empty($_POST["FIL_UNIVEND_AUT"]) && is_array($_POST["FIL_UNIVEND_AUT"])) {
	$where .= " AND COD_UNIVEND_AUT REGEXP ('" . implode("|", $_POST["FIL_UNIVEND_AUT"]) . "')";
}

if (!empty($_POST["FIL_UNIVEND_BLK"]) && is_array($_POST["FIL_UNIVEND_BLK"])) {
	$where .= " AND COD_UNIVEND_BLK REGEXP (" . implode("|", $_POST["FIL_UNIVEND_BLK"]) . ")";
}

if (!empty($_POST["FIL_UNIVEND"]) && is_array($_POST["FIL_UNIVEND"]) && $_POST["FIL_UNIVEND"] != "9999") {
	$where .= " AND PRODUTOTKT.COD_UNIVEND IN (" . implode(",", $_POST["FIL_UNIVEND"]) . ")";
	$fil_univend = fnLimpaCampoArray($_POST['FIL_UNIVEND']);
} else {
	$fil_univend = "0";
}

$andDestaque = "";
if (!empty($_POST["LOG_DESTAQUE"]) && $_POST["LOG_DESTAQUE"] == "S") {
	$andDestaque = " AND PRODUTOTKT.LOG_OFERTAS = 'S'";
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

?>

<style>
	.icon-arrow-left:before {
		font-family: "Font Awesome 5 Pro" !important;
		content: "\f104" !important;
		font-style: normal !important;
	}

	.icon-arrow-right:before {
		font-family: "Font Awesome 5 Pro" !important;
		content: "\f105" !important;
		font-style: normal !important;
	}

	table a:not(.btn),
	.table a:not(.btn) {
		text-decoration: none;
	}

	table a:not(.btn):hover,
	.table a:not(.btn):hover {
		text-decoration: underline;
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
</style>


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
							<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
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

					<?php $abaModulo = 1168;
					include "abasTicketConfig.php"; ?>

					<div class="push30"></div>

					<div class="login-form">


						<form role="form" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
							<fieldset>
								<legend>Filtros</legend>

								<div class="row">

									<div class="col-md-2 text-left">
										<div class="form-group">
											<label for="inputName" class="control-label">Somente <br /> Produtos na Validade</label>
											<div class="push5"></div>
											<label class="switch">
												<input type="checkbox" name="LOG_VALIDO" id="LOG_VALIDO" class="switch" value="S" onchange="carregaProdValidos(this)" <?= (@$_POST["LOG_VALIDO"] == "S" ? "checked" : "") ?>>
												<span></span>
											</label>
										</div>
									</div>

									<div class="col-md-2 text-left">
										<div class="form-group">
											<label for="inputName" class="control-label">Somente <br /> Produtos em Destaque</label>
											<div class="push5"></div>
											<label class="switch">
												<input type="checkbox" name="LOG_DESTAQUE" id="LOG_DESTAQUE" class="switch" value="S" onchange="carregaProdValidos(this)" <?= (@$_POST["LOG_DESTAQUE"] == "S" ? "checked" : "") ?>>
												<span></span>
											</label>
										</div>
									</div>

									<div class="col-md-3">
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

								<div class="row">

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Categoria do Ticket</label>
											<select data-placeholder="Selecione a categoria do ticket" name="FIL_CATEGORTKT" id="FIL_CATEGORTKT" class="chosen-select-deselect requiredChk" style="width:100%;" tabindex="1">
												<option value=""></option>
												<?php
												//se sistema marka
												$sql = "select * from CATEGORIATKT where COD_EMPRESA = '" . $cod_empresa . "' order by NUM_ORDENAC ";
												$arrayQuery = mysqli_query($conn, $sql);
												while ($qrListaCategoria = mysqli_fetch_assoc($arrayQuery)) {
													echo "
														<option value='" . $qrListaCategoria['COD_CATEGORTKT'] . "' " . (@$_POST["FIL_CATEGORTKT"] == $qrListaCategoria['COD_CATEGORTKT'] ? "selected" : "") . ">" . ucfirst($qrListaCategoria['DES_CATEGOR']) . "</option> 
													";
												}
												?>
											</select>
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Unidades Autorizadas</label>

											<select data-placeholder="Selecione uma unidade" name="FIL_UNIVEND_AUT[]" id="FIL_UNIVEND_AUT" multiple="multiple" class="chosen-select-deselect" style="width:100%;" tabindex="1">
												<?php
												// Consulta SQL para obter os dados necessários
												$sql = "SELECT COD_UNIVEND, NOM_FANTASI FROM UNIDADEVENDA WHERE COD_EMPRESA = $cod_empresa $andLojasUsu ORDER BY NOM_FANTASI";

												// Executando a consulta
												$arrayQuery = mysqli_query($conn, $sql);


												if ($arrayQuery) {
													while ($qrListaUnive = mysqli_fetch_assoc($arrayQuery)) {

														$isSelected = isset($_POST["FIL_UNIVEND_AUT"]) && is_array($_POST["FIL_UNIVEND_AUT"]) &&
															in_array($qrListaUnive['COD_UNIVEND'], $_POST["FIL_UNIVEND_AUT"]) ? "selected" : "";

														echo "<option value='" . htmlspecialchars($qrListaUnive['COD_UNIVEND']) . "' $isSelected>" .
															htmlspecialchars(ucfirst($qrListaUnive['NOM_FANTASI'])) .
															"</option>";
													}
												} else {
													echo "<option value='' disabled>Erro ao carregar unidades</option>";
												}
												?>
											</select>

											<?php //fnEscreve($sql); 
											?>
											<div class="help-block with-errors">Se vazio <b>todas</b> as unidades estarão <b>autorizadas</b></div>
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Unidades Não Autorizadas</label>

											<select data-placeholder="Selecione uma unidade" name="FIL_UNIVEND_BLK[]" id="FIL_COD_UNIVEND_BLK" multiple="multiple" class="chosen-select-deselect" style="width:100%;" tabindex="1">
												<?php
												// Consulta SQL para obter os dados
												$sql = "SELECT COD_UNIVEND, NOM_FANTASI FROM UNIDADEVENDA WHERE COD_EMPRESA = $cod_empresa $andLojasUsu ORDER BY NOM_FANTASI";

												$arrayQuery = mysqli_query($conn, $sql);

												if ($arrayQuery) {
													while ($qrListaUnive = mysqli_fetch_assoc($arrayQuery)) {

														$isSelected = isset($_POST["FIL_UNIVEND_BLK"]) && is_array($_POST["FIL_UNIVEND_BLK"]) &&
															in_array($qrListaUnive['COD_UNIVEND'], $_POST["FIL_UNIVEND_BLK"]) ? "selected" : "";


														echo "<option value='" . htmlspecialchars($qrListaUnive['COD_UNIVEND']) . "' $isSelected>" .
															htmlspecialchars(ucfirst($qrListaUnive['NOM_FANTASI'])) .
															"</option>";
													}
												} else {
													echo "<option value='' disabled>Erro ao carregar unidades</option>";
												}
												?>
											</select>

											<?php //fnEscreve($sql); 
											?>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<?php
									if ($log_tktunivend == "S") {
									?>
										<div class="col-md-2">
											<div class="form-group">
												<label for="inputName" class="control-label">Unidade de referência</label>
												<!-- <div class="push5"></div> -->
												<select data-placeholder="Selecione a unidade de referência" name="FIL_UNIVEND[]" id="FIL_UNIVEND" multiple="multiple" class="chosen-select-deselect" style="width:100%;" tabindex="1">
													<?= $optAllUnivend ?>
													<?php
													$sql = "SELECT COD_UNIVEND, NOM_FANTASI, LOG_ESTATUS FROM UNIDADEVENDA WHERE COD_EMPRESA = $cod_empresa $andLojasUsu AND LOG_ESTATUS = 'S' AND DAT_EXCLUSA IS NULL ORDER BY NOM_FANTASI ";
													$arrayQuery = mysqli_query($conn, $sql);
													while ($qrListaUnive = mysqli_fetch_assoc($arrayQuery)) {
														echo "
																  <option value='" . $qrListaUnive['COD_UNIVEND'] . "'" . (in_array($qrListaUnive['COD_UNIVEND'], @$_POST["COD_UNIVEND"]) ? "selected" : "") . ">" . ucfirst($qrListaUnive['NOM_FANTASI']) . "</option> 
																";
													}
													?>
												</select>

												<div class="help-block with-errors"></div>
											</div>
										</div>

									<?php
									}
									?>

								</div>

								<div class="row">

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Ordenação</label>
											<!-- <div class="push5"></div> -->
											<select data-placeholder="Selecione a ordenação" name="DES_ORDENAC" id="DES_ORDENAC" class="chosen-select-deselect" style="width:100%;" tabindex="1">
												<option value=""></option>
												<option value="alfa-asc" <?= (@$_POST["DES_ORDENAC"] == "alfa-asc" ? "selected" : "") ?>>Alfabética Crescente</option>
												<option value="alfa-desc" <?= (@$_POST["DES_ORDENAC"] == "alfa-desc" ? "selected" : "") ?>>Alfabética Decrescente</option>
												<option value="data-asc" <?= (@$_POST["DES_ORDENAC"] == "data-asc" ? "selected" : "") ?>>Validade Crescente</option>
												<option value="data-desc" <?= (@$_POST["DES_ORDENAC"] == "data-desc" ? "selected" : "") ?>>Validade Decrescente</option>
												<option value="cat-asc" <?= (@$_POST["DES_ORDENAC"] == "cat-asc" ? "selected" : "") ?>>Categoria Crescente</option>
												<option value="cat-desc" <?= (@$_POST["DES_ORDENAC"] == "cat-desc" ? "selected" : "") ?>>Categoria Decrescente</option>
											</select>

											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-2">
										<div class="push15"></div>
										<input type="hidden" name="opcao" id="opcao" value="FIL">
										<a href="javascript:void(0)" onclick="document.getElementById('formulario').submit()" name="FIL" id="FIL" class="btn getBtn btn-sm btn-primary col-md-12">Filtrar</a>
									</div>

								</div>

							</fieldset>


							<div class="push20"></div>


							<fieldset>
								<legend>Dados Gerais</legend>

								<div class="row">

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Código</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_PRODTKT" id="COD_PRODTKT" value="">
										</div>
									</div>

									<!-- <div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label required">Empresa</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa ?>"> -->
									<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">
									<!-- </div>
									</div> -->

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Inativar/Ativar <br /></label>
											<div class="push5"></div>
											<label class="switch">
												<input type="checkbox" name="LOG_ATIVOTK" id="LOG_ATIVOTK" class="switch" value="S">
												<span></span>
											</label>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Utilizar em <br /> Produtos de Oferta</label>
											<div class="push5"></div>
											<label class="switch">
												<input type="checkbox" name="LOG_PRODTKT" id="LOG_PRODTKT" class="switch" value="S">
												<span></span>
											</label>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Utilizar em <br /> Oferta Destaque</label>
											<div class="push5"></div>
											<label class="switch">
												<input type="checkbox" name="LOG_OFERTAS" id="LOG_OFERTAS" class="switch" value="S">
												<span></span>
											</label>
										</div>
									</div>

								</div>

								<div class="push10"></div>

								<div class="row">

									<div class="col-md-6">
										<label for="inputName" class="control-label required">Produto </label>
										<div class="input-group">
											<span class="input-group-btn">
												<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary addBox" data-url="action.php?mod=<?php echo fnEncode(1062) ?>&id=<?php echo fnEncode($cod_empresa) ?>&pop=true" data-title="Busca Produtos"><i class="fa fa-search" aria-hidden="true"></i></a>
											</span>
											<input type="text" name="DES_PRODUTO" id="DES_PRODUTO" class="form-control input-sm leituraOff" style="border-radius: 0 3px 3px  0;" readonly="readonly" placeholder="Buscar produto...">
											<input type="hidden" name="COD_PRODUTO" id="COD_PRODUTO" value="">
										</div>
									</div>

									<div class="col-md-6">
										<div class="form-group">
											<label for="inputName" class="control-label required">Nome do Produto</label>
											<input type="text" class="form-control input-sm" name="NOM_PRODTKT" id="NOM_PRODTKT" maxlength="50" required>
											<div class="help-block with-errors"></div>
										</div>
									</div>

								</div>

								<div class="push10"></div>

								<div class="row">

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label required">Data Inicial</label>
											<input type="text" class="form-control input-sm data" name="DAT_INIPTKT" id="DAT_INIPTKT" maxlength="20" value="" required>
											<span class="help-block"></span>
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label required">Data Final</label>
											<input type="text" class="form-control input-sm data" name="DAT_FIMPTKT" id="DAT_FIMPTKT" maxlength="20" value="" required>
											<span class="help-block"></span>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">% Desconto</label>
											<input type="text" class="form-control input-sm text-center money" name="PCT_DESCTKT" id="PCT_DESCTKT" maxlength="20" value="">
											<span class="help-block">Percentual</span>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">De</label>
											<input type="text" class="form-control input-sm text-center money" name="VAL_PRODTKT" id="VAL_PRODTKT" maxlength="20" value="">
											<span class="help-block">Valor</span>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Por</label>
											<input type="text" class="form-control input-sm text-center money" name="VAL_PROMTKT" id="VAL_PROMTKT" maxlength="20" value="">
											<span class="help-block">Valor</span>
										</div>
									</div>

								</div>

								<div class="push10"></div>

								<div class="row">

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label required">Categoria do Ticket</label>
											<select data-placeholder="Selecione a categoria do ticket" name="COD_CATEGORTKT" id="COD_CATEGORTKT" class="chosen-select-deselect requiredChk" style="width:100%;" tabindex="1" required>
												<option value=""></option>
												<?php
												//se sistema marka
												$sql = "select * from CATEGORIATKT where COD_EMPRESA = '" . $cod_empresa . "' order by NUM_ORDENAC ";
												$arrayQuery = mysqli_query($conn, $sql);
												while ($qrListaCategoria = mysqli_fetch_assoc($arrayQuery)) {
													echo "
														<option value='" . $qrListaCategoria['COD_CATEGORTKT'] . "'>" . ucfirst($qrListaCategoria['DES_CATEGOR']) . "</option> 
													";
												}
												?>
											</select>
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Grupo de Desconto</label>

											<select data-placeholder="Selecione um grupo de desconto" name="COD_DESCTKT" id="COD_DESCTKT" class="chosen-select-deselect" style="width:100%;" tabindex="1">
												<option value="0"></option>
												<?php
												$sql = "select * from DESCONTOTKT where COD_EMPRESA = $cod_empresa order by NUM_ORDENAC ";
												$arrayQuery = mysqli_query($conn, $sql);
												while ($qrListaGrupo = mysqli_fetch_assoc($arrayQuery)) {
													echo "
														<option value='" . $qrListaGrupo['COD_DESCTKT'] . "'>" . ucfirst($qrListaGrupo['ABV_DESCTKT']) . "% (" . $qrListaGrupo['DES_DESCTKT'] . ")</option> 
													";
												}
												?>
											</select>
											<script>
												$("#formulario #COD_DESCTKT").val("<?php echo $cod_desctkt; ?>").trigger("chosen:updated");
											</script>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-6">
										<div class="form-group">
											<label for="inputName" class="control-label required">Personas participantes</label>

											<select data-placeholder="Selecione as personas desejadas" name="COD_PERSONA_TKT[]" id="COD_PERSONA_TKT" multiple="multiple" class="chosen-select-deselect requiredChk" style="width:100%;" tabindex="1" required>
												<?php
												//se sistema marka
												$sql = "SELECT * FROM PERSONA WHERE COD_EMPRESA = $cod_empresa AND LOG_ATIVO = 'S' AND COD_EXCLUSA = 0 ORDER BY DES_PERSONA ";
												$arrayQuery = mysqli_query($conn, $sql);
												while ($qrListaPersonas = mysqli_fetch_assoc($arrayQuery)) {

													// if($CarregaMaster=='0' && $qrListaPersonas['COD_UNIVEND'] != "9999"){
													if ($CarregaMaster == '0') {
														if (recursive_array_search($qrListaPersonas['COD_UNIVEND'], $arrLojasAut) === false && !in_array($qrListaPersonas['COD_PERSONA'], $arrPerExtra)) {
															continue;
														}
													}

													echo "
															<option value='" . $qrListaPersonas['COD_PERSONA'] . "'>" . ucfirst($qrListaPersonas['DES_PERSONA']) . "</option> 
														";
													$persona[$qrListaPersonas['COD_PERSONA']] = array(
														'DES_COR' => $qrListaPersonas['DES_COR'],
														'DES_ICONE' => $qrListaPersonas['DES_ICONE'],
														'DES_PERSONA' => $qrListaPersonas['DES_PERSONA'],
													);
												}
												?>
											</select>
											<?php //fnEscreve($sql); 
											?>
										</div>
									</div>

								</div>

								<div class="push10"></div>

								<div class="row">

									<div class="col-md-6">

										<div class="col-md-1">
											<div class="form-group">
												<label class="switch">
													<input type="checkbox" name="LOG_INCLUI" id="LOG_INCLUI" class="switch" value="S" <?= $radioAutObg ?>>
													<span></span>
												</label>
											</div>
										</div>

										<div class="col-md-11">
											<div class="form-group">
												<div class="push5"></div>
												<label for="inputName" class="control-label">&nbsp; Incluir Unidades <b>Específicas</b> Para Este Produto?</label>
											</div>
										</div>

									</div>

									<div class="col-md-6">

										<div class="col-md-1">
											<div class="form-group">
												<label class="switch">
													<input type="checkbox" name="LOG_EXCLUI" id="LOG_EXCLUI" class="switch" value="S">
													<span></span>
												</label>
											</div>
										</div>

										<div class="col-md-11">
											<div class="form-group">
												<div class="push5"></div>
												<label for="inputName" class="control-label">&nbsp; Bloquear Unidades <b>Específicas</b> Para Este Produto?</label>
											</div>
										</div>

									</div>


								</div>

								<div class="pushu50"></div>

								<div class="row">

									<div class="col-md-6">
										<div class="form-group">
											<label for="inputName" class="control-label <?= $lblAutObg ?>">Unidades Autorizadas</label>

											<select data-placeholder="Selecione as unidades autorizadas" name="COD_UNIVEND_AUT[]" id="COD_UNIVEND_AUT" multiple="multiple" class="chosen-select-deselect" <?= $uniAutObg ?> style="width:100%;" tabindex="1">
												<?php
												$sql = "SELECT COD_UNIVEND, NOM_FANTASI FROM UNIDADEVENDA WHERE COD_EMPRESA = $cod_empresa $andLojasUsu ORDER BY NOM_FANTASI ";
												$arrayQuery = mysqli_query($conn, $sql);
												while ($qrListaUnive = mysqli_fetch_assoc($arrayQuery)) {
													echo "
														<option value='" . $qrListaUnive['COD_UNIVEND'] . "'>" . ucfirst($qrListaUnive['NOM_FANTASI']) . "</option> 
													";
												}
												?>
											</select>
											<?php //fnEscreve($sql); 
											?>
											<div class="help-block with-errors"><?= $txtAutObg ?></div>
										</div>
									</div>

									<div class="col-md-6">
										<div class="form-group">
											<label for="inputName" class="control-label">Unidades Não Autorizadas</label>

											<select data-placeholder="Selecione uma empresa para acesso" name="COD_UNIVEND_BLK[]" id="COD_UNIVEND_BLK" multiple="multiple" class="chosen-select-deselect" disabled style="width:100%;" tabindex="1">
												<?php
												$sql = "SELECT COD_UNIVEND, NOM_FANTASI FROM UNIDADEVENDA WHERE COD_EMPRESA = $cod_empresa $andLojasUsu ORDER BY NOM_FANTASI ";
												$arrayQuery = mysqli_query($conn, $sql);
												while ($qrListaUnive = mysqli_fetch_assoc($arrayQuery)) {
													echo "
														<option value='" . $qrListaUnive['COD_UNIVEND'] . "'>" . ucfirst($qrListaUnive['NOM_FANTASI']) . "</option> 
													";
												}
												?>
											</select>
											<?php //fnEscreve($sql); 
											?>
											<div class="help-block with-errors"></div>
										</div>
									</div>

								</div>

								<div class="push10"></div>

								<div class="row">

									<div class="col-md-6">
										<div class="form-group">
											<label for="inputName" class="control-label">Mensagem Promocional</label>
											<input type="text" class="form-control input-sm" name="DES_MENSGTKT" id="DES_MENSGTKT" maxlength="150">
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<?php
									//rotina de mostrar

									// echo $log_tktunivend."_<br>";
									// echo fnAutMaster($_SESSION["SYS_COD_TPUSUARIO"], $_SESSION["SYS_COD_EMPRESA"])."_<br>";
									if ($log_tktunivend == "S") {

									?>
										<div class="col-md-6">
											<div class="form-group">
												<label for="inputName" class="control-label required">Selecione a sua unidade de referência</label>
												<!-- <div class="push5"></div> -->
												<select data-placeholder="Selecione a sua unidade de referência" name="COD_UNIVEND" id="COD_UNIVEND" class="chosen-select-deselect" style="width:100%;" tabindex="1" required>
													<?= $optAllUnivend ?>
													<?php
													$sql = "SELECT COD_UNIVEND, NOM_FANTASI, LOG_ESTATUS FROM UNIDADEVENDA WHERE COD_EMPRESA = $cod_empresa $andLojasUsu AND LOG_ESTATUS = 'S' AND DAT_EXCLUSA IS NULL ORDER BY NOM_FANTASI ";
													$arrayQuery = mysqli_query($conn, $sql);
													while ($qrListaUnive = mysqli_fetch_assoc($arrayQuery)) {
														echo "
																	  <option value='" . $qrListaUnive['COD_UNIVEND'] . "'>" . ucfirst($qrListaUnive['NOM_FANTASI']) . "</option> 
																	";
													}
													?>
												</select>

												<div class="help-block with-errors"></div>
											</div>
										</div>

									<?php
									} else {
									?>
										<input type="hidden" name="COD_UNIVEND" id="COD_UNIVEND" value="9999">
									<?php
									}
									?>

								</div>

							</fieldset>

							<div class="push10"></div>
							<hr>
							<div class="form-group text-right col-lg-12">

								<button type="reset" id="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
								<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
								<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>
								<button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fal fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>

							</div>

							<input type="hidden" name="LOG_HABITKT" id="LOG_HABITKT" value="N">
							<input type="hidden" name="WHERE" id="WHERE" value="<?= fnEncode($where) ?>">
							<input type="hidden" name="AND_FILTRO" id="AND_FILTRO" value="">
							<input type="hidden" name="LOG_TKTUNIVEND" id="LOG_TKTUNIVEND" value="<?= $log_tktunivend ?>">
							<input type="hidden" name="opcao" id="opcao" value="">
							<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
							<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">

							<input type="hidden" name="CODS_PRODTKT" id="CODS_PRODTKT" value="">

							<div class="push5"></div>

						</form>

						<!-- barra de pesquisa -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------  -->
						<div class="push30"></div>

						<div class="row">
							<form name="formLista2" id="formLista2" method="post" action="<?php echo $cmdPage; ?>">

								<div class="col-md-4 col-md-offset-4 col-xs-12">
									<div class="push20"></div>

									<div class="input-group activeItem">
										<div class="input-group-btn search-panel">
											<button type="button" class="btn btn-outline dropdown-toggle form-control form-control-sm rounded-left search-bar" id="FILTERS" data-toggle="dropdown">
												<span id="search_concept">Sem filtro</span>&nbsp;
												<span class="far fa-angle-down"></span>
											</button>
											<ul class="dropdown-menu" role="menu">
												<li class="divisor"><a href="#">Sem filtro</a></li>
												<!-- <li class="divider"></li> -->
												<li><a class="item-filtro" href="#DES_PRODUTO">Produto</a></li>
												<li><a class="item-filtro" href="#DES_PRODUTO_EQ">Produto Exato</a></li>
												<li><a class="item-filtro" href="#COD_PRODUTO">Código do produto</a></li>
												<li><a class="item-filtro" href="#COD_EXTERNO">Código externo</a></li>
												<li><a class="item-filtro" href="#EAN">EAN</a></li>
												<!-- <li><a class="item-filtro" href="#COD_CATEGOR">Grupo</a></li> -->
											</ul>
										</div>
										<input type="hidden" name="VAL_PESQUISA" value="" id="VAL_PESQUISA">
										<input type="text" id="INPUT" class="form-control form-control-sm remove-side-borders search-bar" name="INPUT" value="<?= @$val_pesquisa ?>" onkeyup="buscaRegistro(this)">
										<div class="input-group-btn" id="CLEARDIV" style="<?= $esconde ?>">
											<button class="btn btn-outline form-control form-control-sm remove-side-borders search-bar" id="CLEAR" type="button">&nbsp;<span class="fal fa-times"></span></button>
										</div>
										<div class="input-group-btn">
											<button type="submit" class="btn btn-outline form-control form-control-sm rounded-right search-bar" id="SEARCH">&nbsp;<span class="fal fa-search"></span></button>
										</div>
									</div>

								</div>

								<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
								<!-- <input type="hidden" name="COD_SISTEMAS" id="COD_SISTEMAS" value="" /> -->
								<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
								<input type="hidden" name="opcao" id="opcao" value="FIL2">

							</form>

						</div>

						<div class="push30"></div>

						<!-- ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ -->

						<div id="div_Ordena"></div>

						<div class="col-lg-12">

							<div class="no-more-tables">

								<form name="formLista">

									<table class="table table-bordered table-striped table-hover tablesorter buscavel">
										<thead>
											<tr>
												<th width="40" class="{sorter:false}"></th>
												<th>Cód.</th>
												<th>Cód. Ext. </th>
												<th>Produto Ticket</th>
												<th>Loja</th>
												<th>Usuário</th>
												<th>Categoria</th>
												<th class="{sorter:false}"></th>
												<th>Validade</th>
												<th>Grupo</th>
												<th class="{sorter:false}">Persona</th>
												<th class="{sorter:false}">Ativo</th>
												<th class="{sorter:false}">Wizard</th>
												<th class="{sorter:false}">Ticket</th>
												<th class="{sorter:false}">Destaque</th>
												<th class="{sorter:false}">Un. Aut.</th>
												<th class="{sorter:false}">Un. Não Aut.</th>
												<th class="{sorter:false}">Img.</th>
											</tr>
										</thead>
										<tbody id="relatorioConteudo">

											<?php


											if (@$_POST["DES_ORDENAC"] <> "") {

												switch (@$_POST["DES_ORDENAC"]) {
													case 'alfa-asc':
														$orderBy = "ORDER BY NOM_PRODTKT ASC";
														break;

													case 'alfa-desc':
														$orderBy = "ORDER BY NOM_PRODTKT DESC";
														break;

													case 'data-asc':
														$orderBy = "ORDER BY DAT_FIMPTKT ASC";
														break;

													case 'data-desc':
														$orderBy = "ORDER BY DAT_FIMPTKT DESC";
														break;

													case 'cat-asc':
														$orderBy = "ORDER BY DES_CATEGOR ASC";
														break;

													case 'cat-desc':
														$orderBy = "ORDER BY DES_CATEGOR DESC";
														break;

													default:
														$orderBy = "order by DES_CATEGOR, NOM_PRODTKT";
														break;
												}
											}

											// filtro do banco de dados (precisa existir antes do sql)-------------------------------------------------------------------------------------------------
											if (@$filtro != '') {
												if ($filtro == "EAN" || $filtro == "COD_PRODUTO") {
													$andFiltro = " AND PRODUTOCLIENTE.$filtro = '$val_pesquisa' ";
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
													$andFiltro = " AND PRODUTOCLIENTE.DES_PRODUTO = '$val_pesquisa' ";
												} else {
													$andFiltro = " AND PRODUTOCLIENTE.$filtro LIKE '%$val_pesquisa%' ";
												}
											} else {
												$andFiltro = " ";
											}

											// fnEscreve($andFiltro);
											// --------------------------------------------------------------------------------------------------------------------------------------------------------

											$ARRAY_UNIDADE1 = array(
												'sql' => "select COD_UNIVEND,cod_empresa,nom_fantasi,NOM_UNIVEND from unidadevenda where cod_empresa=$cod_empresa  and cod_exclusa=0",
												'cod_empresa' => $cod_empresa,
												'conntadm' => $connAdm->connAdm(),
												'IN' => 'N',
												'nomecampo' => '',
												'conntemp' => '',
												'SQLIN' => ""
											);
											$ARRAY_UNIDADE = fnUnivend($ARRAY_UNIDADE1);

											$ARRAY_VENDEDOR1 = array(
												'sql' => "select COD_USUARIO,NOM_USUARIO from usuarios where cod_empresa in($cod_empresa,3)",
												'cod_empresa' => $cod_empresa,
												'conntadm' => $connAdm->connAdm(),
												'IN' => 'N',
												'nomecampo' => '',
												'conntemp' => '',
												'SQLIN' => ""
											);
											$ARRAY_VENDEDOR = fnUniVENDEDOR($ARRAY_VENDEDOR1);

											$sql = "SELECT PRODUTOTKT.COD_PRODUTO
														FROM PRODUTOTKT
														inner join PRODUTOCLIENTE on PRODUTOCLIENTE.COD_PRODUTO = PRODUTOTKT.COD_PRODUTO
														WHERE PRODUTOTKT.COD_PRODUTO = PRODUTOCLIENTE.COD_PRODUTO 
														AND PRODUTOTKT.COD_EMPRESA = $cod_empresa 
														-- AND PRODUTOCLIENTE.COD_EXCLUSA = 0
                                                        AND  case when PRODUTOCLIENTE.COD_EXCLUSA = 0 then 0 ELSE 1 end IN (0,1)
														$where
														$andFiltro
														$andDestaque";

											//fnEscreve($sql);

											$retorno = mysqli_query(conntemp($cod_empresa, ""), $sql);
											$total_itens_por_pagina = mysqli_num_rows($retorno);

											$numPaginas = ceil($total_itens_por_pagina / $itens_por_pagina);

											//variavel para calcular o início da visualização com base na página atual
											$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

											$sql = " SELECT PRODUTOCLIENTE.DES_PRODUTO,
															      PRODUTOCLIENTE.COD_EXTERNO,
															      PRODUTOCLIENTE.COD_PRODUTO AS PRODUTO,
															      DESCONTOTKT.ABV_DESCTKT,												
															   IF( PRODUTOCLIENTE.DES_IMAGEM <> '','S','N') AS TEM_IMAGEM,
															   PRODUTOTKT.COD_USUCADA USUARIOCAD,
															   PRODUTOTKT.*,
															   categoriatkt.*
															FROM PRODUTOTKT 
															left join categoriatkt on categoriatkt.COD_CATEGORTKT = PRODUTOTKT.COD_CATEGORTKT 
															left join DESCONTOTKT on DESCONTOTKT.COD_DESCTKT = PRODUTOTKT.COD_DESCTKT 
															inner join PRODUTOCLIENTE on PRODUTOCLIENTE.COD_PRODUTO = PRODUTOTKT.COD_PRODUTO
															WHERE PRODUTOTKT.COD_PRODUTO = PRODUTOCLIENTE.COD_PRODUTO 
															AND PRODUTOTKT.COD_EMPRESA = $cod_empresa 
                                                            AND  case when PRODUTOCLIENTE.COD_EXCLUSA = 0 then 0 ELSE 1 end IN (0,1)
															$where
															$andFiltro
															$andDestaque
															$orderBy
															LIMIT $inicio, $itens_por_pagina";
											// echo $sql;
											// echo($sql);
											// fnEscreve($sql);
											//fnTestesql(connTemp($cod_empresa,''),$sql);

											$arrayQuery = mysqli_query($conn, $sql);
											$teste = mysqli_num_rows($arrayQuery);

											// fnEscreve($sql);

											$count = 0;
											//constroi array persona
											while ($qrBuscaProdutosTkt = mysqli_fetch_assoc($arrayQuery)) {

												// if($CarregaMaster == '0'){
												// 	if(recursive_array_search($qrBuscaProdutosTkt['COD_UNIVEND'],$arrLojasAut) === false){
												// 		continue;
												// 	}
												// }

												$NOM_ARRAY_NON_VENDEDOR = "";

												if ($qrBuscaProdutosTkt['USUARIOCAD'] != 0) {

													$NOM_ARRAY_NON_VENDEDOR = (array_search($qrBuscaProdutosTkt['USUARIOCAD'], array_column($ARRAY_VENDEDOR, 'COD_USUARIO')));
												}

												$lojaLoop = $qrBuscaProdutosTkt['COD_UNIVEND'];
												if ($lojaLoop == 9999) {
													$nomeLoja = "Todas";
												} else {
													$NOM_ARRAY_UNIDADE = (array_search($qrBuscaProdutosTkt['COD_UNIVEND'], array_column($ARRAY_UNIDADE, 'COD_UNIVEND')));
													$nomeLoja = $ARRAY_UNIDADE[$NOM_ARRAY_UNIDADE]['nom_fantasi'];
												}

												$count++;

												if ($qrBuscaProdutosTkt['LOG_ATIVOTK'] == "S") {
													$mostraLOG_ATIVOTK = '<i class="fal fa-check" aria-hidden="true"></i>';
												} else {
													$mostraLOG_ATIVOTK = '';
												}

												if ($qrBuscaProdutosTkt['LOG_PRODTKT'] == "S") {
													$mostraLOG_PRODTKT = '<i class="fal fa-check" aria-hidden="true"></i>';
												} else {
													$mostraLOG_PRODTKT = '';
												}

												if ($qrBuscaProdutosTkt['COD_UNIVEND_AUT'] != "0") {
													$mostraCOD_UNIVEND_AUT = '<i class="fal fa-check" aria-hidden="true"></i>';
												} else {
													$mostraCOD_UNIVEND_AUT = '';
												}

												if ($qrBuscaProdutosTkt['COD_UNIVEND_BLK'] != "0") {
													$mostraCOD_UNIVEND_BLK = '<i class="fal fa-check" aria-hidden="true"></i>';
												} else {
													$mostraCOD_UNIVEND_BLK = '';
												}

												if ($qrBuscaProdutosTkt['TEM_IMAGEM'] == "S") {
													$mostraDES_IMAGEM = '<i class="fal fa-check" aria-hidden="true"></i>';
												} else {
													$mostraDES_IMAGEM = '';
												}

												if ($qrBuscaProdutosTkt['LOG_OFERTAS'] == "S") {
													$mostraOFERTAS = '<i class="fal fa-check" aria-hidden="true"></i>';
												} else {
													$mostraOFERTAS = '';
												}

												if ($qrBuscaProdutosTkt['LOG_AUTOMATIC'] == "S") {
													$mostraAUTOMATIC = '<i class="faL fa-check" aria-hidden="true"></i>';
												} else {
													$mostraAUTOMATIC = '';
												}


												//fnFormatDateTime($qrBuscaProdutosTkt['DAT_FIMPTKT']
												//se validade está vencida 
												if ($qrBuscaProdutosTkt['DAT_FIMPTKT'] != "") {

													$mostraValidade = '';
													$mostraValidadeHora = '';
													$mostraInvalidado = '';
													$textoDanger = '';
													if (date('Y-m-d h:i:s') > $qrBuscaProdutosTkt['DAT_FIMPTKT']) {
														//$mostraValidade = '<i class="fa fa-check-o" aria-hidden="true"></i>';	
														//$mostraValidade = ''.fnFormatDateTime($qrBuscaProdutosTkt['DAT_FIMPTKT']);
														$mostraValidade = '' . fnFormatDate($qrBuscaProdutosTkt['DAT_FIMPTKT']);
														$mostraValidadeHora = fnFormatDateTime($qrBuscaProdutosTkt['DAT_FIMPTKT']);
														$textoDanger = "text-danger";
													} else {
														//$mostraValidade = fnFormatDateTime($qrBuscaProdutosTkt['DAT_FIMPTKT']); 
														$mostraValidade = fnFormatDate($qrBuscaProdutosTkt['DAT_FIMPTKT']);
														$mostraValidadeHora = fnFormatDateTime($qrBuscaProdutosTkt['DAT_FIMPTKT']);
														if ($qrBuscaProdutosTkt['LOG_ATIVOTK'] == "N") {
															$mostraInvalidado = '<i class="fal fa-times text-danger" aria-hidden="true"></i>';
														}
														$textoDanger = "text-success";
													}
													$e = explode(" ", $mostraValidadeHora);
													$mostraValidadeHora = @$e['1'];
												} else {
													$mostraValidade = '';
													$mostraValidadeHora = '';
												}

												//fnEscreve($qrBuscaProdutosTkt['TEM_IMAGEM']);
												//fnEscreve($qrBuscaProdutosTkt['DAT_INIPTKT']);

												echo "
															<tr data-id='" . $qrBuscaProdutosTkt['COD_PRODTKT'] . "'>
															  <td align='center'><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></td>
															  <td>" . $qrBuscaProdutosTkt['COD_PRODTKT'] . "</td>
															  <td>" . $qrBuscaProdutosTkt['COD_EXTERNO'] . "</td>
															  <td><a href='action.do?mod=" . fnEncode(1046) . "&id=" . fnEncode($cod_empresa) . "&idP=" . fnEncode($qrBuscaProdutosTkt['COD_PRODUTO']) . "'>" . $qrBuscaProdutosTkt['NOM_PRODTKT'] . "</a></td>
															  <td><small>" . $nomeLoja . "</small></td>
															  <td><small>" . @$ARRAY_VENDEDOR[$NOM_ARRAY_NON_VENDEDOR]['NOM_USUARIO'] . "</small></td>
															  <td>" . $qrBuscaProdutosTkt['DES_CATEGOR'] . "</td>
															  <td align='center'><input type='checkbox' name='check_data' value=" . $qrBuscaProdutosTkt['COD_PRODTKT'] . "></td>
															  <td class='" . $textoDanger . " dt-validade'>
															    <small>
																<a href='#' class='editable editable-click " . $textoDanger . "' data-type='date' data-format='dd/mm/yyyy' data-clear='false' data-empresa='$cod_empresa' data-pk='" . $qrBuscaProdutosTkt['COD_PRODTKT'] . "' data-title='Editar'>$mostraValidade</a> $mostraValidadeHora
																</small>
															  </td>
															  <td class='text-center'><small>" . $qrBuscaProdutosTkt['ABV_DESCTKT'] . "</small></td>
															  <td class='text-center'>
															  ";

												//personas
												//<td class='text-center'><a class='btn btn-circle-long btn-success' data-toggle='tooltip' data-placement='top' data-original-title='em estoque' > 00</a></td>

												$arrayPersonas = explode(',', $qrBuscaProdutosTkt['COD_PERSONA_TKT']);
												foreach ($arrayPersonas as $valores) {

													if (substr(@$persona[$valores]['DES_ICONE'], 0, 3) == 'fa-') {
														$iconePersona = 'fas ' . @$persona[$valores]['DES_ICONE'];
													} else {
														$iconePersona = @$persona[$valores]['DES_ICONE'];
													}

													echo "<a class='btn btn-circle-long' style='color: #fff; background-color: #" . @$persona[$valores]['DES_COR'] . "; border-color: #" . @$persona[$valores]['DES_COR'] . ";' data-toggle='tooltip' data-placement='top' data-original-title='" . @$persona[$valores]['DES_PERSONA'] . "' ><i class='" . @$iconePersona . "' aria-hidden='true'></i></a>&nbsp;";
												}


												echo "
															  </td>
															  <td class='text-center'>" . $mostraLOG_ATIVOTK . $mostraInvalidado . "</td>
															  <td class='text-center'>" . $mostraAUTOMATIC . "</td> 
															  <td class='text-center'>" . $mostraLOG_PRODTKT . "</td>
															  <td class='text-center'>" . $mostraOFERTAS . "</td>
															  <td class='text-center'>" . $mostraCOD_UNIVEND_AUT . "</td>
															  <td class='text-center'>" . $mostraCOD_UNIVEND_BLK . "</td>
															  <td class='text-center'>" . $mostraDES_IMAGEM . "</td>
															</tr>
															<input type='hidden' id='ret_COD_PRODTKT_" . $count . "' value='" . $qrBuscaProdutosTkt['COD_PRODTKT'] . "'>
															<input type='hidden' id='ret_COD_PRODUTO_" . $count . "' value='" . $qrBuscaProdutosTkt['COD_PRODUTO'] . "'>
															<input type='hidden' id='ret_NOM_PRODTKT_" . $count . "' value='" . $qrBuscaProdutosTkt['NOM_PRODTKT'] . "'>
															<input type='hidden' id='ret_DES_PRODUTO_" . $count . "' value='" . $qrBuscaProdutosTkt['DES_PRODUTO'] . "'>
															<input type='hidden' id='ret_LOG_PRODTKT_" . $count . "' value='" . $qrBuscaProdutosTkt['LOG_PRODTKT'] . "'>
															<input type='hidden' id='ret_LOG_HABITKT_" . $count . "' value='" . $qrBuscaProdutosTkt['LOG_HABITKT'] . "'>
															<input type='hidden' id='ret_DAT_INIPTKT_" . $count . "' value='" . fnFormatDateTime($qrBuscaProdutosTkt['DAT_INIPTKT']) . "'>
															<input type='hidden' id='ret_DAT_FIMPTKT_" . $count . "' value='" . fnFormatDateTime($qrBuscaProdutosTkt['DAT_FIMPTKT']) . "'>
															<input type='hidden' id='ret_PCT_DESCTKT_" . $count . "' value='" . number_format($qrBuscaProdutosTkt['PCT_DESCTKT'], 2, ",", ".") . "'>
															<input type='hidden' id='ret_VAL_PRODTKT_" . $count . "' value='" . number_format($qrBuscaProdutosTkt['VAL_PRODTKT'], 2, ",", ".") . "'>
															<input type='hidden' id='ret_VAL_PROMTKT_" . $count . "' value='" . number_format($qrBuscaProdutosTkt['VAL_PROMTKT'], 2, ",", ".") . "'>
															<input type='hidden' id='ret_COD_PERSONA_TKT_" . $count . "' value='" . $qrBuscaProdutosTkt['COD_PERSONA_TKT'] . "'>
															<input type='hidden' id='ret_COD_UNIVEND_AUT_" . $count . "' value='" . $qrBuscaProdutosTkt['COD_UNIVEND_AUT'] . "'>
															<input type='hidden' id='ret_COD_UNIVEND_BLK_" . $count . "' value='" . $qrBuscaProdutosTkt['COD_UNIVEND_BLK'] . "'>
															<input type='hidden' id='ret_COD_UNIVEND_" . $count . "' value='" . $qrBuscaProdutosTkt['COD_UNIVEND'] . "'>
															<input type='hidden' id='ret_COD_CATEGORTKT_" . $count . "' value='" . $qrBuscaProdutosTkt['COD_CATEGORTKT'] . "'>
															<input type='hidden' id='ret_LOG_OFERTAS_" . $count . "' value='" . $qrBuscaProdutosTkt['LOG_OFERTAS'] . "'>
															<input type='hidden' id='ret_LOG_ATIVOTK_" . $count . "' value='" . $qrBuscaProdutosTkt['LOG_ATIVOTK'] . "'>
															<input type='hidden' id='ret_COD_DESCTKT_" . $count . "' value='" . $qrBuscaProdutosTkt['COD_DESCTKT'] . "'>
															<input type='hidden' id='ret_DES_MENSGTKT_" . $count . "' value='" . $qrBuscaProdutosTkt['DES_MENSGTKT'] . "'>
															";
											}

											?>

										</tbody>
										<tfoot>
											<tr>
												<td colspan="7"></td>
												<td colspan="2" class="text-center">
													<button onClick="exc_selecionados();return false;" name="EXC" class="btn btn-xs btn-danger transparency"><i class="fal fa-times" aria-hidden="true"></i>&nbsp; Excluir Selecionados</button>
												</td>
												<td colspan=8></td>
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

	<script>
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
				if ("<?= @$filtro ?>" != "") {
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

			//VERIFICA SE O CHECKBOX FOI MARCADO E DESMARCA O RADIO SELECIONADO, E RESETA OS INPUTS. ADICIONADO POR LUCAS 19/09/2024
			$(document).on('click', 'input[name="check_data"]', function() {
				var isChecked = $(this).is(':checked');

				if (isChecked) {
					$('input[type="radio"]').prop('checked', false);
					$('#reset').trigger('click');
				}
			});

			//VERIFICA O CHECK DO RADIO E DESMARCA TODOS OS CHECKBOX. ADICIONADO POR LUCAS 19/09/2024
			$(document).on('click', 'input[type="radio"]', function() {
				// Quando um radio button é clicado, desmarque todos os checkboxes
				$('input[name="check_data"]').prop('checked', false);
			});

			//chosen obrigatório
			$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';

			$('#formulario').validator();

			// Checkbox validation
			$('#CAD, #ALT').click(function(e) {
				if ($('#LOG_PRODTKT').is(':checked') && $('#LOG_OFERTAS').is(':checked')) {
					e.preventDefault();
					$.alert({
						title: 'Aviso',
						content: 'O  produto não pode ser utilizado em <b>ofertas</b> e <b>destaque</b> ao mesmo tempo.',
					});
				}
			});

			var numPaginas = <?php echo $numPaginas; ?>;
			if (numPaginas != 0) {
				carregarPaginacao(numPaginas);
			}

			$("#AND_FILTRO").val("<?= $andFiltro ?>");

			$('#LOG_INCLUI').change(function() {
				// console.log("CLICOU");
				if ($('#LOG_INCLUI').is(':checked')) {
					$('#COD_UNIVEND_AUT').prop('disabled', false).prop('required', true).trigger("chosen:updated");
				} else {
					$('#COD_UNIVEND_AUT').val('').prop('disabled', true).prop('required', false).trigger("chosen:updated");
				}
			});

			$('#LOG_EXCLUI').change(function() {
				if ($('#LOG_EXCLUI').is(':checked')) {
					$('#COD_UNIVEND_BLK').prop('disabled', false).trigger("chosen:updated");
				} else {
					$('#COD_UNIVEND_BLK').val('').prop('disabled', true).trigger("chosen:updated");
				}
			});

			//retorno filtro unidades autorizadas
			$("#formulario #FIL_UNIVEND_AUT").val('').trigger("chosen:updated");
			if ("<?= @$fil_univend_aut ?>" != "0") {
				var sistemasPersona = "<?= @$fil_univend_aut ?>";
				var sistemasPersonaArr = sistemasPersona.split(',');
				//opções multiplas
				for (var i = 0; i < sistemasPersonaArr.length; i++) {
					$("#formulario #FIL_UNIVEND_AUT option[value=" + Number(sistemasPersonaArr[i]) + "]").prop("selected", "true");
				}
				$("#formulario #FIL_UNIVEND_AUT").trigger("chosen:updated");
			} else {
				$("#formulario #FIL_UNIVEND_AUT").val('').trigger("chosen:updated");
			}

			//retorno formulario unidades blacklist
			$("#formulario #FIL_UNIVEND_BLK").val('').trigger("chosen:updated");
			if ("<?= @$fil_univend_blk ?>" != "0") {
				var sistemasPersona = "<?= @$fil_univend_blk ?>";
				var sistemasPersonaArr = sistemasPersona.split(',');
				//opções multiplas
				for (var i = 0; i < sistemasPersonaArr.length; i++) {
					$("#formulario #FIL_UNIVEND_BLK option[value=" + Number(sistemasPersonaArr[i]) + "]").prop("selected", "true");
				}
				$("#formulario #FIL_UNIVEND_BLK").trigger("chosen:updated");
			} else {
				$("#formulario #FIL_UNIVEND_BLK").val('').trigger("chosen:updated");
			}

			//retorno formulario unidades blacklist
			$("#formulario #FIL_UNIVEND").val('').trigger("chosen:updated");
			if ("<?= $fil_univend ?>" != "0") {
				var sistemasPersona = "<?= $fil_univend ?>";
				var sistemasPersonaArr = sistemasPersona.split(',');
				//opções multiplas
				for (var i = 0; i < sistemasPersonaArr.length; i++) {
					$("#formulario #FIL_UNIVEND option[value=" + Number(sistemasPersonaArr[i]) + "]").prop("selected", "true");
				}
				$("#formulario #FIL_UNIVEND").trigger("chosen:updated");
			} else {
				$("#formulario #FIL_UNIVEND").val('').trigger("chosen:updated");
			}

			$("#formulario #FIL_CATEGORTKT").val("<?= $fil_categortkt ?>").trigger("chosen:updated");
			// $("#formulario #FIL_UNIVEND").val("<?= $fil_univend ?>").trigger("chosen:updated");

			$('.editable').editable({
				ajaxOptions: {
					type: 'post'
				},
				success: function(data) {
					var $obj = $(this);
					var ids = $(this).data("pk");
					$('input[name="check_data"]:checked').each(function() {
						ids += "," + $(this).val();
					});
					var data = "empresa=" + $(this).data("empresa") + "&pk=" + $(this).data("pk") + "&ids=" + ids;
					setTimeout(function() {
						data += "&data=" + ($("tr[data-id=" + $obj.data("pk") + "]").find("td.dt-validade a").html());

						$.ajax({
							method: 'POST',
							url: 'ajxProdutosTicket.php',
							data: data,
							success: function(data) {
								console.log(data);
								$.each(data.ids, function(index, value) {
									$("tr[data-id=" + value + "]").find("td.dt-validade").removeClass("text-danger").removeClass("text-success").addClass(data.class);
									$("tr[data-id=" + value + "]").find("td.dt-validade a").removeClass("text-danger").removeClass("text-success").addClass(data.class);
									$("tr[data-id=" + value + "]").find("td.dt-validade a").html(data.data);
								});
							}
						});
						console.log($obj.data());
					}, 100);
				}
			});
		});

		function carregaProdValidos(obj) {

			log = "";

			if ($(obj).prop('checked')) {
				log = 'S';
			} else {
				log = 'N';
			}

			$.ajax({
				method: 'POST',
				url: 'ajxProdutosTicket.do?id=<?= fnEncode($cod_empresa) ?>',
				data: {
					VALIDADE: log
				},
				beforeSend: function() {
					$("#relatorioConteudo").html("<div class='loading' style='width:100%'></div>");
				},
				success: function(data) {
					$("#relatorioConteudo").html(data);
				}
			});

		}

		function reloadPage(idPage) {
			$.ajax({
				type: "POST",
				url: "ajxPageProdutosTicket.do?id=<?= fnEncode($cod_empresa) ?>&idPage=" + idPage + "&itens_por_pagina=<?php echo $itens_por_pagina; ?>",
				data: $('#formulario').serialize(),
				beforeSend: function() {
					$('#relatorioConteudo').html('<div class="loading" style="width: 100%;"></div>');
				},
				success: function(data) {
					$("#relatorioConteudo").html(data);
					$(".tablesorter").trigger("updateAll");
				},
				error: function(data) {
					console.log(data);
					$('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
				}
			});
		}

		function retornaForm(index) {

			$("#formulario #COD_PRODTKT").val($("#ret_COD_PRODTKT_" + index).val());
			$("#formulario #COD_PRODUTO").val($("#ret_COD_PRODUTO_" + index).val());
			$("#formulario #DES_PRODUTO").val($("#ret_DES_PRODUTO_" + index).val());
			$("#formulario #NOM_PRODTKT").val($("#ret_NOM_PRODTKT_" + index).val());
			if ($("#ret_LOG_PRODTKT_" + index).val() == 'S') {
				$('#formulario #LOG_PRODTKT').prop('checked', true);
			} else {
				$('#formulario #LOG_PRODTKT').prop('checked', false);
			}
			if ($("#ret_LOG_HABITKT_" + index).val() == 'S') {
				$('#formulario #LOG_HABITKT').prop('checked', true);
			} else {
				$('#formulario #LOG_HABITKT').prop('checked', false);
			}
			$("#formulario #DAT_INIPTKT").val($("#ret_DAT_INIPTKT_" + index).val());
			$("#formulario #DAT_FIMPTKT").val($("#ret_DAT_FIMPTKT_" + index).val());
			$("#formulario #PCT_DESCTKT").val($("#ret_PCT_DESCTKT_" + index).val());
			$("#formulario #VAL_PRODTKT").val($("#ret_VAL_PRODTKT_" + index).val());
			$("#formulario #VAL_PROMTKT").val($("#ret_VAL_PROMTKT_" + index).val());




			//retorno combo personas
			$("#formulario #COD_PERSONA_TKT").val('').trigger("chosen:updated");
			if ($("#ret_COD_PERSONA_TKT_" + index).val() != "0") {
				var sistemasPersona = $("#ret_COD_PERSONA_TKT_" + index).val();
				var sistemasPersonaArr = sistemasPersona.split(',');
				//opções multiplas
				for (var i = 0; i < sistemasPersonaArr.length; i++) {
					$("#formulario #COD_PERSONA_TKT option[value=" + sistemasPersonaArr[i] + "]").prop("selected", "true");
				}
				$("#formulario #COD_PERSONA_TKT").trigger("chosen:updated");
			} else {
				$("#formulario #COD_PERSONA_TKT").val('').trigger("chosen:updated");
			}


			//retorno lojas autorizadas
			$("#formulario #COD_UNIVEND_AUT").val('').trigger("chosen:updated");
			if ($("#ret_COD_UNIVEND_AUT_" + index).val() != 0) {
				var sistemasUnidadeAut = $("#ret_COD_UNIVEND_AUT_" + index).val();
				var sistemasUnidadeAutArr = sistemasUnidadeAut.split(',');
				//opções multiplas
				for (var i = 0; i < sistemasUnidadeAutArr.length; i++) {
					$("#formulario #COD_UNIVEND_AUT option[value=" + sistemasUnidadeAutArr[i] + "]").prop("selected", "true");
				}

				$("#formulario #COD_UNIVEND_AUT").prop('disabled', false).attr('required', true).trigger("chosen:updated");
				$('#formulario #LOG_INCLUI').prop('checked', true);
			} else {
				$("#formulario #COD_UNIVEND_AUT").prop('disabled', true).removeAttr('required').trigger("chosen:updated");
				$('#formulario #LOG_INCLUI').prop('checked', false);
			}




			//retorno lojas não autorizadas
			$("#formulario #COD_UNIVEND_BLK").val('').trigger("chosen:updated");
			if ($("#ret_COD_UNIVEND_BLK_" + index).val() != "0") {
				var sistemasUnidadeNAut = $("#ret_COD_UNIVEND_BLK_" + index).val();
				var sistemasUnidadeNAutArr = sistemasUnidadeNAut.split(',');
				//opções multiplas
				for (var i = 0; i < sistemasUnidadeNAutArr.length; i++) {
					$("#formulario #COD_UNIVEND_BLK option[value=" + sistemasUnidadeNAutArr[i] + "]").prop("selected", "true");
				}
				$("#formulario #COD_UNIVEND_BLK").prop('disabled', false).trigger("chosen:updated");
				$('#formulario #LOG_EXCLUI').prop('checked', true);
			} else {
				$("#formulario #COD_UNIVEND_BLK").prop('disabled', true).trigger("chosen:updated");
				$('#formulario #LOG_EXCLUI').prop('checked', false);
			}




			$("#formulario #COD_CATEGORTKT").val($("#ret_COD_CATEGORTKT_" + index).val()).trigger("chosen:updated");
			$("#formulario #COD_DESCTKT").val($("#ret_COD_DESCTKT_" + index).val()).trigger("chosen:updated");
			$("#formulario #COD_UNIVEND").val($("#ret_COD_UNIVEND_" + index).val()).trigger("chosen:updated");

			if ($("#ret_LOG_OFERTAS_" + index).val() == 'S') {
				$('#formulario #LOG_OFERTAS').prop('checked', true);
			} else {
				$('#formulario #LOG_OFERTAS').prop('checked', false);
			}

			if ($("#ret_LOG_ATIVOTK_" + index).val() == 'S') {
				$('#formulario #LOG_ATIVOTK').prop('checked', true);
			} else {
				$('#formulario #LOG_ATIVOTK').prop('checked', false);
			}

			$("#formulario #DES_MENSGTKT").val($("#ret_DES_MENSGTKT_" + index).val());

			$('#formulario').validator('validate');
			$("#formulario #hHabilitado").val('S');
		}

		function exc_selecionados() {
			let qtd = $("[name=check_data]:checked").length;
			if (qtd <= 0) {
				alert("Nenhum registro selecionado para exclusão!");
				return;
			}
			let msg = `Deseja realmente excluir esses ${qtd} registros selecionados?`;
			if (qtd <= 1) {
				msg = `Deseja realmente excluir o registro selecionado?`;
			}
			$.confirm({
				title: 'Atenção!',
				animation: 'opacity',
				closeAnimation: 'opacity',
				content: msg,
				buttons: {
					confirmar: function() {
						let ids = 0;
						$('input[name="check_data"]:checked').each(function() {
							ids += "," + $(this).val();
						});
						$("#CODS_PRODTKT").val(ids);

						$("#formulario #opcao").val("EXC_SEL");
						$("#CAD, #ALT, #EXC").prop('disabled', true);
						$("#formulario")[0].submit();
						$("#hHabilitado").val('N');
					},
					cancelar: function() {

					},
				}
			});

		}
	</script>