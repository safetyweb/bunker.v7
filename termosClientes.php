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
$cod_pergunta = "";
$des_pergunta = "";
$des_resposta = "";
$num_ordenac = "";
$nom_submenus = "";
$hHabilitado = "";
$hashForm = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$log_cadtoken = "";
$sqlControle = "";
$arrayControle = [];
$e = "";
$qrCont = "";
$sqlIns = "";
$sqlContole = "";
$qrControle = "";
$log_separa = "";
$log_lgpd = "";
$des_img_g = "";
$des_img = "";
$des_imgmob = "";
$chkSepara = "";
$chkLgpd = "";
$qrLista = "";
$qtd_sms = 0;
$qtd_wpp = 0;
$qtd_email = 0;
$sql1 = "";
$arrayQuery1 = [];
$qrAcessoIntegracao = "";
$integracaoEmail = "";
$abaEmpresa = "";
$checkSms = "";
$checkCadToken = "";
$qrAcessoSms = "";
$integracaoSms = "";
$checkSmsAcc = "";
$sql3 = "";
$arrayQuery3 = [];
$campanhaSms = "";
$checkCampSms = "";
$sql5 = "";
$arrayQuery5 = [];
$totemCad = "";
$checkTotem = "";
$checkEmail = "";
$sql2 = "";
$arrayQuery2 = [];
$qrAcessoEmail = "";
$checkEmailAcc = "";
$sql4 = "";
$arrayQuery4 = [];
$campanhaEmail = "";
$checkCampEmail = "";
$qrBuscaFAQ = "";
$ativoTermo = "";
$obrigaTermo = "";
$tipo = "";
$obrigaChk = "";
$sqlTermos = "";
$arrayTermos = [];
$des_bloco = "";
$qrTermos = "";
$chkTermo = "";
$temp = "";


$hashLocal = mt_rand();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_pergunta = fnLimpaCampoZero(@$_REQUEST['COD_PERGUNTA']);
		$des_pergunta = @$_REQUEST['DES_PERGUNTA'];
		$des_resposta = addslashes(htmlentities(@$_REQUEST['DES_RESPOSTA']));
		$num_ordenac = @$_REQUEST['NUM_ORDENAC'];
		$cod_empresa = @$_REQUEST['COD_EMPRESA'];

		//fnEscreve($nom_submenus);

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		if ($opcao != '') {

			$sql = "CALL SP_ALTERA_PERGUNTAS (
				 '" . $cod_pergunta . "', 
				 '" . $cod_empresa . "', 
				 '" . $des_pergunta . "', 
				 '" . $des_resposta . "', 
				 '" . $opcao . "'    
				) ";

			//echo $sql;				
			mysqli_query(connTemp($cod_empresa, ''), $sql);

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
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI, LOG_CADTOKEN FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
		$log_cadtoken = $qrBuscaEmpresa['LOG_CADTOKEN'];
	}
} else {
	$cod_empresa = 0;
	//fnEscreve('entrou else');
}

$sqlControle = "SELECT * FROM CONTROLE_TERMO WHERE COD_EMPRESA = $cod_empresa";

// fnEscreve($sqlControle);

$arrayControle = mysqli_query(connTemp($cod_empresa, ''), $sqlControle);

// try {mysqli_query(connTemp($cod_empresa,''),$sqlControle);} 
//                            catch (mysqli_sql_exception $e) {fnEscreve($e);}
//                            fnEscreve($e);

$qrCont = mysqli_fetch_assoc($arrayControle);

if (mysqli_num_rows($arrayControle) == 0) {

	$sqlIns = "INSERT INTO CONTROLE_TERMO(
							      COD_EMPRESA,
							      TXT_ACEITE,
								  TXT_COMUNICA,
								  LOG_SEPARA,
								  COD_USUCADA
							   ) VALUES(
							   	  $cod_empresa,
							   	  'Estou ciente e de acordo com os termos, e desejo me cadastrar:',
							   	  'Comunicação',
							   	  'N',
							   	  $_SESSION[SYS_COD_USUARIO]
							   ); ";

	$sqlIns .= "INSERT INTO TERMOS_EMPRESA 
					(COD_EMPRESA, COD_TIPO, NOM_TERMO, ABV_TERMO, LOG_ATIVO, DES_TERMO, COD_USUCADA) 
					VALUES 
					($cod_empresa, 1, 'Termos de Uso', 'Termos de Uso', 'S', '', $_SESSION[SYS_COD_USUARIO]),
					($cod_empresa, 1, 'Política de Privacidade', 'Política de Privacidade', 'S', '', $_SESSION[SYS_COD_USUARIO]),
					($cod_empresa, 1, 'Regulamento de Uso do Programa', 'Regulamento', 'S', '', $_SESSION[SYS_COD_USUARIO]),
					($cod_empresa, 2, 'Autorização de email', 'email', 'S', '', $_SESSION[SYS_COD_USUARIO]),
					($cod_empresa, 3, 'Autorização de SMS', 'SMS', 'S', '', $_SESSION[SYS_COD_USUARIO]),
					($cod_empresa, 4, 'Autorização de WhatsApp', 'WhatsApp', 'S', '', $_SESSION[SYS_COD_USUARIO]),
					($cod_empresa, 5, 'Autorização de Push', 'Push', 'S', '', $_SESSION[SYS_COD_USUARIO]),
					($cod_empresa, 6, 'Ofertas personalizadas', 'Ofertas', 'S', '', $_SESSION[SYS_COD_USUARIO]),
					($cod_empresa, 7, 'Autorização de Telemarketing', 'Telemarketing', 'S', '', $_SESSION[SYS_COD_USUARIO]); ";

	// fnEscreve($sqlIns);

	mysqli_multi_query(connTemp($cod_empresa, ''), $sqlIns);
}

$sqlContole = "SELECT * FROM CONTROLE_TERMO WHERE COD_EMPRESA = $cod_empresa";

$arrayControle = mysqli_query(connTemp($cod_empresa, ''), $sqlControle);

$qrControle = mysqli_fetch_assoc($arrayControle);

$log_separa = $qrControle['LOG_SEPARA'];
$log_lgpd = $qrControle['LOG_LGPD'];
$des_img_g = fnBase64DecodeImg($qrControle['DES_IMG_G']);
$des_img = fnBase64DecodeImg($qrControle['DES_IMG']);
$des_imgmob = fnBase64DecodeImg($qrControle['DES_IMGMOB']);

if ($log_separa == 'S') {
	$chkSepara = "checked";
} else {
	$chkSepara = "";
}

if ($log_lgpd == 'S') {
	$chkLgpd = "checked";
} else {
	$chkLgpd = "";
}

$sql = "SELECT PM.QTD_PRODUTO, 
                   PM.TIP_LANCAMENTO,
                   CC.DES_CANALCOM 
            FROM PEDIDO_MARKA PM
            INNER JOIN PRODUTO_MARKA PRM ON PRM.COD_PRODUTO = PM.COD_PRODUTO
            INNER JOIN CANAL_COMUNICACAO CC ON CC.COD_CANALCOM = PRM.COD_CANALCOM 
            WHERE PM.COD_ORCAMENTO > 0 
            AND PM.COD_EMPRESA = $cod_empresa";

// fnEscreve($sql);

$arrayQuery = mysqli_query($connAdm->connAdm(), trim($sql));

while ($qrLista = mysqli_fetch_assoc($arrayQuery)) {

	// fnEscreve($qrLista['QTD_PRODUTO']);

	$count++;

	switch ($qrLista['DES_CANALCOM']) {

		case 'SMS':
			if ($qrLista['TIP_LANCAMENTO'] == 'D') {
				$qtd_sms = $qtd_sms - $qrLista['QTD_PRODUTO'];
			} else {
				$qtd_sms = $qtd_sms + $qrLista['QTD_PRODUTO'];
			}
			break;

		case 'Whats App':
			if ($qrLista['TIP_LANCAMENTO'] == 'D') {
				$qtd_wpp = $qtd_wpp - $qrLista['QTD_PRODUTO'];
			} else {
				$qtd_wpp = $qtd_wpp + $qrLista['QTD_PRODUTO'];
			}
			break;

		default:
			if ($qrLista['TIP_LANCAMENTO'] == 'D') {
				$qtd_email = $qtd_email - $qrLista['QTD_PRODUTO'];
			} else {
				$qtd_email = $qtd_email + $qrLista['QTD_PRODUTO'];
			}
			break;
	}
}

// fnEscreve($log_lgpd);
// fnEscreve($chkLgpd);

?>


<script type="text/javascript" src="js/plugins/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
	tinyMCE.init({
		// General options
		mode: "textareas",
		setup: function(ed) {
			// set the editor font size
			ed.onInit.add(function(ed) {
				ed.getBody().style.fontSize = '13px';
			});
		},
		language: "pt",
		theme: "advanced",
		plugins: "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",

		// Theme options
		theme_advanced_buttons1: "undo,redo,|,bold,italic,underline,strikethrough,nonbreaking,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,|,forecolor,backcolor,|,copy,paste,cut,|,pastetext,pasteword,|,search,replace,|,link,unlink,anchor,image,|,hr,removeformat,visualaid,|,cleanup,preview,print,code,fullscreen",
		theme_advanced_buttons2: "",
		theme_advanced_buttons3: "",
		theme_advanced_toolbar_location: "top",
		theme_advanced_toolbar_align: "left",
		theme_advanced_statusbar_location: "bottom",
		theme_advanced_resizing: true,

		// Example content CSS (should be your site CSS)
		//content_css : "css/content.css",

		// Drop lists for link/image/media/template dialogs
		template_external_list_url: "lists/template_list.js",
		external_link_list_url: "lists/link_list.js",
		external_image_list_url: "lists/image_list.js",
		media_external_list_url: "lists/media_list.js",

		// Replace values for the template plugin
		template_replace_values: {
			username: "Some User",
			staffid: "991234"
		}
	});
</script>

<style type="text/css">
	.editable {
		color: unset;
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
				<?php include "atalhosPortlet.php"; ?>
			</div>
			<div class="portlet-body">

				<?php

				$sql1 = "SELECT COUNT(1) AS TEMACESSO FROM senhas_parceiro apar
											INNER JOIN parceiro_comunicacao par ON par.COD_PARCOMU=apar.COD_PARCOMU
											WHERE par.COD_TPCOM='2' 
											AND apar.COD_PARCOMU IN('22','17','23')
											AND apar.COD_EMPRESA=$cod_empresa 
											AND apar.LOG_ATIVO='S'";
				$arrayQuery1 = mysqli_query($connAdm->connAdm(), $sql1);
				// fnEscreve($sql1);
				$qrAcessoIntegracao = mysqli_fetch_assoc($arrayQuery1);
				$integracaoEmail = $qrAcessoIntegracao['TEMACESSO'];
				//fnEscreve($integracaoEmail);
				?>

				<?php if ($integracaoEmail == 0) { ?>
					<div class="alert alert-danger top30 bottom30" role="alert">
						<button type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						Sua empresa não possui comunicação <b>ativa</b>. <br />
						Entre em <b>contato</b> com o <b>suporte</b>.
					</div>
				<?php } ?>

				<?php if ($msgRetorno <> '') { ?>
					<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<?php echo $msgRetorno; ?>
					</div>
				<?php } ?>

				<?php
				$abaEmpresa = 1674;

				switch ($_SESSION["SYS_COD_SISTEMA"]) {
					case 14: //rede duque
						include "abasEmpresaDuque.php";
						break;
					case 15: //quiz
						include "abasEmpresaQuiz.php";
						break;
					case 16: //gabinete
						include "abasGabinete.php";
						break;
					case 18: //mais cash
						include "abasMaisCash.php";
						break;
					default;
						include "abasEmpresaConfig.php";
						break;
				}

				?>

				<div class="push30"></div>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<div class="row">

							<div class="push20"></div>

							<div class="col-md-6">
								<!-- configurações de sms -->

								<fieldset>
									<legend class="f19">Configurações de SMS</legend>

									<table class="table table-bordered" style="width:80%; margin:left;">
										<thead>
											<tr>
												<th class="{ sorter: false }" width="40"></th>
												<th>Item</th>
											</tr>
										</thead>
										<tbody>

											<?php

											if ($qtd_sms >= 50) {
												$checkSms = "fa-check text-success";
											} else {
												$checkSms = "fa-times text-danger";
											}

											?>

											<tr>
												<td class='text-center'><i class="fal <?= $checkSms ?>" aria-hidden="true"></i></td>
												<td>Saldo de envio <b><small><?= fnValor($qtd_sms, 0) ?> envios</small></b></td>
											</tr>

											<?php

											if ($log_cadtoken == 'S') {
												$checkCadToken = "fa-check text-success";
											} else {
												$checkCadToken = "fa-times text-danger";
											}

											?>

											<tr>
												<td class='text-center'><i class="fal <?= $checkCadToken ?>" aria-hidden="true"></i></td>
												<td>Cadastro com Token</td>
											</tr>

											<?php

											$sql1 = "SELECT COUNT(1) AS TEMACESSO FROM senhas_parceiro apar
																			INNER JOIN parceiro_comunicacao par ON par.COD_PARCOMU=apar.COD_PARCOMU
																			WHERE par.COD_TPCOM='2' 
																			AND apar.COD_PARCOMU='16' 
																			AND apar.COD_EMPRESA=$cod_empresa 
																			AND apar.LOG_ATIVO='S'";
											$arrayQuery1 = mysqli_query($connAdm->connAdm(), $sql1);
											// fnEscreve($sql1);
											$qrAcessoSms = mysqli_fetch_assoc($arrayQuery1);
											$integracaoSms = $qrAcessoSms['TEMACESSO'];

											if ($integracaoSms > 0) {
												$checkSmsAcc = "fa-check text-success";
											} else {
												$checkSmsAcc = "fa-times text-danger";
											}

											?>

											<tr>
												<td class='text-center'><i class="fal <?= $checkSmsAcc ?>" aria-hidden="true"></i></td>
												<td>Conta SMS ativa</td>
											</tr>

											<?php

											$sql3 = "SELECT 1 FROM GATILHO_SMS 
																				WHERE COD_EMPRESA = $cod_empresa 
																				AND TIP_GATILHO = 'tokenCad'";
											$arrayQuery3 = mysqli_query(connTemp($cod_empresa, ''), $sql3);
											// fnEscreve($sql1);								  
											$campanhaSms = mysqli_num_rows($arrayQuery3);

											if ($campanhaSms > 0) {
												$checkCampSms = "fa-check text-success";
											} else {
												$checkCampSms = "fa-times text-danger";
											}

											?>

											<tr>
												<td class='text-center'><i class="fal <?= $checkCampSms ?>" aria-hidden="true"></i></td>
												<td>Campanha SMS </td>
											</tr>

											<?php

											$sql5 = "SELECT 1 FROM TOTEM_PLAYERS 
																				WHERE COD_EMPRESA = $cod_empresa 
																				AND DES_PAGHOME = 'cad'";
											$arrayQuery5 = mysqli_query(connTemp($cod_empresa, ''), $sql5);
											// fnEscreve($sql1);								  
											$totemCad = mysqli_num_rows($arrayQuery5);

											if ($totemCad > 0) {
												$checkTotem = "fa-check text-success";
											} else {
												$checkTotem = "fa-times text-danger";
											}

											?>

											<tr>
												<td class='text-center'><i class="fal <?= $checkTotem ?>" aria-hidden="true"></i></td>
												<td>Totem de Auto Atendimento </td>
											</tr>

										</tbody>

									</table>


								</fieldset>


							</div>

							<div class="col-md-6">
								<!-- configurações de email -->

								<fieldset>
									<legend class="f19">Configurações de e-Mail</legend>

									<table class="table table-bordered" style="width:80%; margin:left;">
										<thead>
											<tr>
												<th class="{ sorter: false }" width="40"></th>
												<th>Item</th>
											</tr>
										</thead>
										<tbody>

											<?php

											if ($qtd_email >= 50) {
												$checkEmail = "fa-check text-success";
											} else {
												$checkEmail = "fa-times text-danger";
											}

											?>

											<tr>
												<td class='text-center'><i class="fal <?= $checkEmail ?>" aria-hidden="true"></i></td>
												<td>Saldo de envio <b><small><?= fnValor($qtd_email, 0) ?> envios</small></b></td>
											</tr>

											<?php

											if ($log_cadtoken == 'S') {
												$checkCadToken = "fa-check text-success";
											} else {
												$checkCadToken = "fa-times text-danger";
											}

											?>

											<tr>
												<td class='text-center'><i class="fal <?= $checkCadToken ?>" aria-hidden="true"></i></td>
												<td>Cadastro com Token</td>
											</tr>

											<?php

											$sql2 = "SELECT COUNT(1) AS TEMACESSO FROM senhas_parceiro apar
																			INNER JOIN parceiro_comunicacao par ON par.COD_PARCOMU=apar.COD_PARCOMU
																			WHERE par.COD_TPCOM='1' 
																			AND apar.COD_PARCOMU='15' 
																			AND apar.COD_EMPRESA=$cod_empresa 
																			AND apar.LOG_ATIVO='S'";
											$arrayQuery2 = mysqli_query($connAdm->connAdm(), $sql2);
											// fnEscreve($sql1);
											$qrAcessoEmail = mysqli_fetch_assoc($arrayQuery2);
											$integracaoEmail = $qrAcessoEmail['TEMACESSO'];

											if ($integracaoEmail > 0) {
												$checkEmailAcc = "fa-check text-success";
											} else {
												$checkEmailAcc = "fa-times text-danger";
											}

											?>

											<tr>
												<td class='text-center'><i class="fal <?= $checkEmailAcc ?>" aria-hidden="true"></i></td>
												<td>Conta Email ativa</td>
											</tr>

											<?php

											$sql4 = "SELECT 1 FROM GATILHO_EMAIL 
																				WHERE COD_EMPRESA = $cod_empresa 
																				AND TIP_GATILHO = 'tokenCad'";
											$arrayQuery4 = mysqli_query(connTemp($cod_empresa, ''), $sql4);
											// fnEscreve($sql1);								  
											$campanhaEmail = mysqli_num_rows($arrayQuery4);

											if ($campanhaEmail > 0) {
												$checkCampEmail = "fa-check text-success";
											} else {
												$checkCampEmail = "fa-times text-danger";
											}

											?>

											<tr>
												<td class='text-center'><i class="fal <?= $checkCampEmail ?>" aria-hidden="true"></i></td>
												<td>Campanha Email </td>
											</tr>

											<?php

											$sql5 = "SELECT 1 FROM TOTEM_PLAYERS 
																				WHERE COD_EMPRESA = $cod_empresa 
																				AND DES_PAGHOME = 'cad'";
											$arrayQuery5 = mysqli_query(connTemp($cod_empresa, ''), $sql5);
											// fnEscreve($sql1);								  
											$totemCad = mysqli_num_rows($arrayQuery5);

											if ($totemCad > 0) {
												$checkTotem = "fa-check text-success";
											} else {
												$checkTotem = "fa-times text-danger";
											}

											?>

											<tr>
												<td class='text-center'><i class="fal <?= $checkTotem ?>" aria-hidden="true"></i></td>
												<td>Totem de Auto Atendimento </td>
											</tr>

										</tbody>

									</table>

								</fieldset>


							</div>

						</div>

						<div class="push50"></div>

						<div class="row">

							<div class="col-md-3">
								<div class="form-group">
									<label for="inputName" class="control-label">Ativar aceite obrigatório no cadastro</label>
									<div class="push5"></div>
									<label class="switch">
										<input type="checkbox" name="LOG_LGPD" id="LOG_LGPD" class="switch" value="S" onchange="ajxLgpd()" <?= $chkLgpd ?>>
										<span></span>
									</label>
								</div>
							</div>

							<div class="col-md-3">
								<label for="inputName" class="control-label required">Imagem Desktop (G)</label>
								<div class="input-group">
									<span class="input-group-btn">
										<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary upload" idinput="DES_IMG_G" extensao="img"><i class="fa fa-cloud-upload" aria-hidden="true"></i></a>
									</span>
									<input type="text" name="DES_IMG_G" id="DES_IMG_G" class="form-control input-sm" style="border-radius: 0 3px 3px  0;" maxlength="100" value="<?php echo $des_img_g; ?>">
								</div>
								<span class="help-block">(.jpg 940px X 845px)</span>
							</div>

							<div class="col-md-3">
								<label for="inputName" class="control-label required">Imagem Tablet (M)</label>
								<div class="input-group">
									<span class="input-group-btn">
										<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary upload" idinput="DES_IMG" extensao="img"><i class="fa fa-cloud-upload" aria-hidden="true"></i></a>
									</span>
									<input type="text" name="DES_IMG" id="DES_IMG" class="form-control input-sm" style="border-radius: 0 3px 3px  0;" maxlength="100" value="<?php echo $des_img; ?>">
								</div>
								<span class="help-block">(.jpg 680px X 675px)</span>
							</div>

							<div class="col-md-3">
								<label for="inputName" class="control-label required">Imagem Mobile (P)</label>
								<div class="input-group">
									<span class="input-group-btn">
										<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary upload" idinput="DES_IMGMOB" extensao="img"><i class="fa fa-cloud-upload" aria-hidden="true"></i></a>
									</span>
									<input type="text" name="DES_IMGMOB" id="DES_IMGMOB" class="form-control input-sm" style="border-radius: 0 3px 3px  0;" maxlength="100" value="<?php echo $des_imgmob; ?>">
								</div>
								<span class="help-block">(.jpg 360px X 360px)</span>
							</div>

						</div>

						<div class="push10"></div>

						<div class="row">

							<div class="col-md-4">

								<div class="col-md-12">
									<h4>Passo 1</h4>
								</div>

								<div class="push20"></div>

								<div class="col-md-6">

									<a type="button" name="ADD" id="ADD" class="btn btn-info pull-left addBox" data-url="action.php?mod=<?php echo fnEncode(1675) ?>&id=<?php echo fnEncode($cod_empresa) ?>&pop=true" data-title="Novo Termo/Nota"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Adicionar Termo/Nota</a>

								</div>

								<div class="push10"></div>

								<div class="col-md-12">

									<div class="no-more-tables">

										<form name="formLista" id="formLista">

											<table class="table table-bordered table-striped table-hover tableSorter">
												<thead>
													<tr>
														<!-- <th class="{ sorter: false }" width="40"></th> -->
														<th>Termo/Nota Legal</th>
														<th class="{ sorter: false }">Ativo</th>
														<th class="{ sorter: false }" width="40"></th>
													</tr>
												</thead>
												<tbody>

													<?php

													$sql = "SELECT * FROM TERMOS_EMPRESA 
																		WHERE COD_EMPRESA = $cod_empresa";

													// fnEscreve($sql);

													$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

													$count = 0;
													while ($qrBuscaFAQ = mysqli_fetch_assoc($arrayQuery)) {
														$count++;

														if ($qrBuscaFAQ['LOG_ATIVO'] == 'S') {
															$ativoTermo = '<span class="far fa-check text-success"></span>';
														} else {
															$ativoTermo = '<span class="far fa-times text-danger"></span>';
														}
													?>
														<tr>

															<td><?= $qrBuscaFAQ['NOM_TERMO'] ?></td>
															<td><?= $ativoTermo ?></td>
															<td class="text-center">
																<small>
																	<div class="btn-group dropdown dropleft">
																		<button type="button" class="btn btn-info btn-xs dropdown-toggle transparency" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																			ações &nbsp;
																			<span class="fas fa-caret-down"></span>
																		</button>
																		<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
																			<li><a class="addBox" data-url="action.php?mod=<?php echo fnEncode(1675) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idt=<?php echo fnEncode($qrBuscaFAQ['COD_TERMO']) ?>&pop=true&rnd=<?= rand() ?>" data-title="<?= $qrBuscaFAQ['NOM_TERMO'] ?>" onclick=''>Editar</a></li>
																			<li><a class="addBox" data-url="action.php?mod=<?php echo fnEncode(1677) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idt=<?php echo fnEncode($qrBuscaFAQ['COD_TERMO']) ?>&pop=true&rnd=<?= rand() ?>" data-title="<?= $qrBuscaFAQ['NOM_TERMO'] ?>" onclick=''>Visualizar</a></li>

																			<!-- <li class="divider"></li>
																						<li><a href="javascript:void(0)">Excluir</a></li> -->
																		</ul>
																	</div>
																</small>
															</td>
														</tr>

													<?php
													}

													?>

												</tbody>
											</table>

										</form>

									</div>

								</div>

							</div>


							<div class="col-md-4">

								<div class="col-md-12">
									<h4>Passo 2</h4>
								</div>

								<div class="push20"></div>

								<div class="col-md-6">

									<a type="button" name="ADD" id="ADD" class="btn btn-info pull-left addBox" data-url="action.php?mod=<?php echo fnEncode(1676) ?>&id=<?php echo fnEncode($cod_empresa) ?>&pop=true" data-title="Novo Bloco"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Adicionar Bloco de Informação</a>

								</div>

								<div class="push10"></div>

								<div class="col-md-12">

									<div class="no-more-tables">

										<form name="formLista" id="formLista">

											<table class="table table-bordered table-striped table-hover tableSorter">
												<thead>
													<tr>
														<th class="{ sorter: false }" width="40"></th>
														<th>Bloco de Informação</th>
														<th class="{ sorter: false }">Obrigatório</th>
														<th class="{ sorter: false }" width="40"></th>
													</tr>
												</thead>
												<tbody>

													<?php

													$sql = "SELECT * FROM BLOCO_TERMOS WHERE COD_EMPRESA = $cod_empresa AND LOG_EXCLUSAO <> 'S' ORDER BY NUM_ORDENAC";

													$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

													$count = 0;
													while ($qrBuscaFAQ = mysqli_fetch_assoc($arrayQuery)) {
														$count++;

														if ($qrBuscaFAQ['LOG_OBRIGA'] == 'S') {
															$obrigaTermo = '<span class="far fa-check text-success"></span>';
														} else {
															$obrigaTermo = '<span class="far fa-times text-danger"></span>';
														}
													?>
														<tr data-id='<?= $qrBuscaFAQ['COD_BLOCO'] ?>'>
															<td class='text-center'><span class='ordernacao fal fa-bars grabbable' data-id='<?= $qrBuscaFAQ['COD_BLOCO'] ?>'></span></td>
															<td><?= $qrBuscaFAQ['DES_BLOCO'] ?></td>
															<td><?= $obrigaTermo ?></td>
															<td class="text-center">
																<small>
																	<div class="btn-group dropdown dropleft">
																		<button type="button" class="btn btn-info btn-xs dropdown-toggle transparency" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																			ações &nbsp;
																			<span class="fas fa-caret-down"></span>
																		</button>
																		<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
																			<li><a class="addBox" data-url="action.php?mod=<?php echo fnEncode(1676) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idb=<?php echo fnEncode($qrBuscaFAQ['COD_BLOCO']) ?>&pop=true&rnd=<?= rand() ?>" data-title="Editar bloco" onclick=''>Editar</a></li>
																			<li><a class="delBox" onClick="apagaBloco('<?= $qrBuscaFAQ['COD_BLOCO'] ?>');" data-title="Apagar bloco" onclick=''>Excluir</a></li>


																		</ul>
																	</div>
																</small>
															</td>
														</tr>



													<?php
													}

													?>

												</tbody>
											</table>

										</form>

									</div>

								</div>

							</div>

							<div class="col-md-4">

								<div class="push10"></div>

								<fieldset>
									<legend class="f19">Preview</legend>


									<div class="row">

										<div class="col-xs-6 col-xs-offset-1">

											<div class="form-group">
												<label for="inputName" class="control-label">Separar Bloco de Comunicação</label>
												<div class="push5"></div>
												<label class="switch">
													<input type="checkbox" name="LOG_SEPARA" id="LOG_SEPARA" class="switch" value="S" <?= $chkSepara ?> onchange="refreshPreview()">
													<span></span>
												</label>
											</div>

										</div>

										<div class="push20"></div>

										<div class="col-xs-10 col-xs-offset-1">
											<h5 data-toggle='tooltip' data-placement='bottom' data-original-title='Clique para editar'>
												<b>
													<a href="#" class="editable"
														data-type='text'
														data-title='Editar Texto' data-pk="<?= $cod_empresa ?>"
														data-name="TXT_ACEITE"><?= $qrControle['TXT_ACEITE'] ?>

													</a>
												</b>
											</h5>
										</div>

										<div class="push10"></div>

										<div id="relatorioPreview">

											<?php

											if ($log_separa == 'S') {

												$sql = "SELECT * FROM BLOCO_TERMOS WHERE COD_EMPRESA = $cod_empresa AND LOG_EXCLUSAO <> 'S' AND TIP_TERMO != 'COM' ORDER BY NUM_ORDENAC";
												// fnEscreve($sql);
												$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

												$count = 0;
												$tipo = "";
												while ($qrBuscaFAQ = mysqli_fetch_assoc($arrayQuery)) {

													if ($qrBuscaFAQ['LOG_OBRIGA'] == "S") {
														$obrigaChk = "required";
													} else {
														$obrigaChk = "";
													}

													$sqlTermos = "SELECT * FROM TERMOS_EMPRESA
																					  WHERE COD_EMPRESA = $cod_empresa
																					  AND COD_TERMO IN($qrBuscaFAQ[COD_TERMO])";

													// fnEscreve($sqlTermos);

													$arrayTermos = mysqli_query(connTemp($cod_empresa, ''), $sqlTermos);

													$des_bloco = $qrBuscaFAQ['DES_BLOCO'];

													while ($qrTermos = mysqli_fetch_assoc($arrayTermos)) {
														// fnEscreve(strtoupper($qrTermos['ABV_TERMO']));

														$des_bloco = str_replace(
															"<#" . strtoupper($qrTermos['ABV_TERMO']) . ">",
															'
																										</label>
																											
																												<a class="addBox f16" 
																												   data-url="action.php?mod=' . fnEncode(1677) . '&id=' . fnEncode($cod_empresa) . '&idt=' . fnEncode($qrTermos['COD_TERMO']) . '&pop=true&rnd=' . rand() . '" 
																												   data-title="' . $qrTermos['NOM_TERMO'] . '"
																												   style="cursor:pointer;">
																												   ' . $qrTermos['ABV_TERMO'] . '
																												</a>
																											
																									  	<label class="f16" for="TERMOS_' . $count . '">
																									',
															$des_bloco
														);
													}

											?>

													<div class="form-group">
														<div class="col-xs-12">
															<div class="col-xs-1" style="padding-left:0; padding-right: 0;">
																<input type="checkbox" name="TERMOS_<?= $qrBuscaFAQ['COD_BLOCO'] ?>" id="TERMOS_<?= $qrBuscaFAQ['COD_BLOCO'] ?>" style="width: 18px; height: 18px;" <?= $obrigaChk ?> <?= $chkTermo ?>>
																<label class="<?= $obrigaChk ?>"></label>
															</div>
															<div class="col-xs-10" style="padding-left:0; padding-right: 0;">
																<label class="f16" for="TERMOS_<?= $qrBuscaFAQ['COD_BLOCO'] ?>">
																	&nbsp;<?= $des_bloco ?>
																</label>
															</div>
														</div>
														<div class="help-block with-errors"></div>
														<div class="push10"></div>
														<div class="push5"></div>
													</div>

												<?php

													$count++;
												}

												?>

												<div class="col-xs-10 col-xs-offset-1">
													<h5 data-toggle='tooltip' data-placement='bottom' data-original-title='Clique para editar'>
														<b>
															<a href="#" class="editable"
																data-type='text'
																data-title='Editar Texto' data-pk="<?= $cod_empresa ?>"
																data-name="TXT_COMUNICA"><?= $qrControle['TXT_COMUNICA'] ?>

															</a>
														</b>
													</h5>
												</div>
												<div class="push10"></div>

												<?php

												$sql = "SELECT * FROM BLOCO_TERMOS WHERE COD_EMPRESA = $cod_empresa AND LOG_EXCLUSAO <> 'S' AND TIP_TERMO = 'COM' ORDER BY NUM_ORDENAC";
												// fnEscreve($sql);
												$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

												// $count=0;
												$tipo = "";
												while ($qrBuscaFAQ = mysqli_fetch_assoc($arrayQuery)) {

													if ($qrBuscaFAQ['LOG_OBRIGA'] == "S") {
														$obrigaChk = "required";
													} else {
														$obrigaChk = "";
													}

													$sqlTermos = "SELECT * FROM TERMOS_EMPRESA
																					  WHERE COD_EMPRESA = $cod_empresa
																					  AND COD_TERMO IN($qrBuscaFAQ[COD_TERMO])";

													// fnEscreve($sqlTermos);

													$arrayTermos = mysqli_query(connTemp($cod_empresa, ''), $sqlTermos);

													$des_bloco = $qrBuscaFAQ['DES_BLOCO'];

													while ($qrTermos = mysqli_fetch_assoc($arrayTermos)) {
														// fnEscreve(strtoupper($qrTermos['ABV_TERMO']));

														$des_bloco = str_replace(
															"<#" . strtoupper($qrTermos['ABV_TERMO']) . ">",
															'
																										</label>
																											
																												<a class="addBox f16" 
																												   data-url="action.php?mod=' . fnEncode(1677) . '&id=' . fnEncode($cod_empresa) . '&idt=' . fnEncode($qrTermos['COD_TERMO']) . '&pop=true&rnd=' . rand() . '" 
																												   data-title="' . $qrTermos['NOM_TERMO'] . '"
																												   style="cursor:pointer;">
																												   ' . $qrTermos['ABV_TERMO'] . '
																												</a>
																											
																									  	<label class="f16" for="TERMOS_' . $count . '">
																									',
															$des_bloco
														);
													}

												?>

													<div class="form-group">
														<div class="col-xs-12">
															<div class="col-xs-1" style="padding-left:0; padding-right: 0;">
																<input type="checkbox" name="TERMOS_<?= $qrBuscaFAQ['COD_BLOCO'] ?>" id="TERMOS_<?= $qrBuscaFAQ['COD_BLOCO'] ?>" style="width: 18px; height: 18px;" <?= $obrigaChk ?> <?= $chkTermo ?>>
																<label class="<?= $obrigaChk ?>"></label>
															</div>
															<div class="col-xs-10" style="padding-left:0; padding-right: 0;">
																<label class="f16" for="TERMOS_<?= $qrBuscaFAQ['COD_BLOCO'] ?>">
																	&nbsp;<?= $des_bloco ?>
																</label>
															</div>
														</div>
														<div class="help-block with-errors"></div>
														<div class="push10"></div>
														<div class="push5"></div>
													</div>

												<?php

													$count++;
												}
											} else {

												$sql = "SELECT * FROM BLOCO_TERMOS WHERE COD_EMPRESA = $cod_empresa AND LOG_EXCLUSAO <> 'S' ORDER BY NUM_ORDENAC";
												// fnEscreve($sql);
												$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

												$count = 0;
												$tipo = "";
												while ($qrBuscaFAQ = mysqli_fetch_assoc($arrayQuery)) {

													if ($qrBuscaFAQ['LOG_OBRIGA'] == "S") {
														$obrigaChk = "required";
													} else {
														$obrigaChk = "";
													}

													$sqlTermos = "SELECT * FROM TERMOS_EMPRESA
																					  WHERE COD_EMPRESA = $cod_empresa
																					  AND COD_TERMO IN($qrBuscaFAQ[COD_TERMO])";

													// fnEscreve($sqlTermos);

													$arrayTermos = mysqli_query(connTemp($cod_empresa, ''), $sqlTermos);

													$des_bloco = $qrBuscaFAQ['DES_BLOCO'];

													while ($qrTermos = mysqli_fetch_assoc($arrayTermos)) {
														// fnEscreve(strtoupper($qrTermos['ABV_TERMO']));

														$des_bloco = str_replace(
															"<#" . strtoupper($qrTermos['ABV_TERMO']) . ">",
															'
																										</label>
																											
																												<a class="addBox f16" 
																												   data-url="action.php?mod=' . fnEncode(1677) . '&id=' . fnEncode($cod_empresa) . '&idt=' . fnEncode($qrTermos['COD_TERMO']) . '&pop=true&rnd=' . rand() . '" 
																												   data-title="' . $qrTermos['NOM_TERMO'] . '"
																												   style="cursor:pointer;">
																												   ' . $qrTermos['ABV_TERMO'] . '
																												</a>
																											
																									  	<label class="f16" for="TERMOS_' . $count . '">
																									',
															$des_bloco
														);
													}

												?>

													<div class="form-group">
														<div class="col-xs-12">
															<div class="col-xs-1" style="padding-left:0; padding-right: 0;">
																<input type="checkbox" name="TERMOS_<?= $qrBuscaFAQ['COD_BLOCO'] ?>" id="TERMOS_<?= $qrBuscaFAQ['COD_BLOCO'] ?>" style="width: 18px; height: 18px;" <?= $obrigaChk ?> <?= $chkTermo ?>>
																<label class="<?= $obrigaChk ?>"></label>
															</div>
															<div class="col-xs-10" style="padding-left:0; padding-right: 0;">
																<label class="f16" for="TERMOS_<?= $qrBuscaFAQ['COD_BLOCO'] ?>">
																	&nbsp;<?= $des_bloco ?>
																</label>
															</div>
														</div>
														<div class="help-block with-errors"></div>
														<div class="push10"></div>
														<div class="push5"></div>
													</div>

											<?php

													$count++;
												}
											}

											?>

										</div>

									</div>

									<div class="push30"></div>

								</fieldset>
							</div>

						</div>


						<div class="push10"></div>

						<input type="hidden" name="REFRESH_TERMO" id="REFRESH_TERMO" value="N">
						<input type="hidden" name="REFRESH_BLOCO" id="REFRESH_BLOCO" value="N">
						<input type="hidden" name="COD_PERGUNTA" id="COD_PERGUNTA" value="">

						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">

						<div class="push5"></div>

					</form>

					<div class="push50"></div>



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

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script>
	$(function() {

		$('.editable').editable({
			emptytext: '_______________',
			url: 'ajxTextoTermos.php',
			ajaxOptions: {
				type: 'post'
			},
			params: function(params) {
				return params;
			},
			success: function(data) {
				console.log(data);
			}
		});

		$(".tableSorter tbody").sortable();

		$('.tableSorter tbody').sortable({
			handle: 'span'
		});

		$(".tableSorter tbody").sortable({

			stop: function(event, ui) {
				var Ids = "";
				$('.ordernacao').each(function(index) {
					Ids = Ids + $(this).attr('data-id') + ",";
				});

				var arrayOrdem = Ids.substring(0, (Ids.length - 1));
				execOrdenacao(arrayOrdem, 10);

				function execOrdenacao(p1, p2) {
					//alert(p1);
					$.ajax({
						type: "GET",
						url: "ajxOrdenacaoEmp.php",
						data: {
							ajx1: p1,
							ajx2: p2,
							ajx3: <?php echo $cod_empresa ?>
						},
						beforeSend: function() {
							//$('#divId_sub').html('<div class="loading" style="width: 100%;"></div>');
						},
						success: function(data) {
							$("#divId_sub").html(data);
							refreshPreview();
						},
						error: function() {
							$('#divId_sub').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Falha no processamento...</p>');
						}
					});
				}

			}

		});


		$(".tableSorter tbody").disableSelection();

		//modal close
		$('#popModal').on('hidden.bs.modal', function() {

			if ($('#REFRESH_TERMO').val() == "S" || $('#REFRESH_BLOCO').val() == "S") {
				location.reload();
			}

		});

		//arrastar 
		$('.grabbable').on('change', function(e) {
			//console.log(e.icon);
			$("#DES_ICONE").val(e.icon);
		});

		$(".grabbable").click(function() {
			$(this).parent().addClass('selected').siblings().removeClass('selected');

		});

		$('.dragTag').on('dragstart', function(event) {
			var tag = $(this).attr('dragTagName');
			event.originalEvent.dataTransfer.setData("text", ' ' + tag + ' ');
			event.originalEvent.dataTransfer.setDragImage(this, 0, 0);
		});


		$('.dragTag').on('click', function(event) {
			var $temp = $("<input>");
			$("#tosave").append($temp);
			$temp.val($(this).text()).select();
			document.execCommand("copy");
			$temp.remove();
		});

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

	});

	function uploadFile(idField, typeFile) {
		var formData = new FormData();
		var nomeArquivo = $('#' + idField)[0].files[0]['name'];

		formData.append('arquivo', $('#' + idField)[0].files[0]);
		formData.append('diretorio', '../media/clientes/');
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
				$('.jconfirm-open').fadeOut(300, function() {
					$(this).remove();
				});

				var data = JSON.parse(data);
				if (data.success) {
					$('#' + idField.replace("arqUpload_", "")).val(nomeArquivo);

					$.ajax({
						type: "POST",
						url: "ajxImgTermos.php",
						data: {
							COD_EMPRESA: "<?= fnEncode($cod_empresa) ?>",
							NOM_ARQ: data.nome_arquivo,
							CAMPO: idField
						},
						success: function(data) {
							console.log(data);
							$.alert({
								title: "Mensagem",
								content: "Upload feito com sucesso",
								type: 'green'
							});
						}
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

	function refreshPreview() {

		var separa = "N";

		if ($('#LOG_SEPARA').prop('checked')) {
			separa = "S";
		}

		$.ajax({
			type: "POST",
			url: "ajxPreviewTermos.php",
			data: {
				COD_EMPRESA: "<?= fnEncode($cod_empresa) ?>",
				LOG_SEPARA: separa
			},
			beforeSend: function() {
				$('#relatorioPreview').html('<div class="loading" style="width: 100%;"></div>');
			},
			success: function(data) {
				$("#relatorioPreview").html(data);
			},
			error: function() {
				$('#relatorioPreview').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Falha no processamento...</p>');
			}
		});

	}

	function ajxLgpd() {

		var lgpd = "N";

		if ($('#LOG_LGPD').prop('checked')) {
			lgpd = "S";
		}

		$.ajax({
			type: "POST",
			url: "ajxAceiteLgpd.php",
			data: {
				COD_EMPRESA: "<?= fnEncode($cod_empresa) ?>",
				LOG_LGPD: lgpd
			},
			success: function(data) {
				console.log(data);
				// $("#relatorioPreview").html(data);
			},
			error: function() {
				// console.log(data);
				// $('#relatorioPreview').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Falha no processamento...</p>');
			}
		});

	}

	function quickCopy(tag) {
		var dummyContent = tag;
		var dummy = $('<input>').val(dummyContent).appendTo('body');
		dummy.select();
		document.execCommand('copy');
		dummy.remove();
	}


	function retornaForm(index) {
		$("#formulario #COD_PERGUNTA").val($("#ret_COD_PERGUNTA_" + index).val());
		$("#formulario #DES_PERGUNTA").val($("#ret_DES_PERGUNTA_" + index).val());
		tinyMCE.getInstanceById('DES_RESPOSTA').execCommand('mceSetContent', false, eval('document.getElementById("formLista").ret_DES_RESPOSTA_' + index + '.value'));
		$("#formulario #NUM_ORDENAC").val($("#ret_NUM_ORDENAC_" + index).val());
		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
	}

	function apagaBloco(id) {
		$.alert({
			title: "Aviso!",
			content: "Deseja <b>realmente</b> excluir este bloco de informação?",
			type: 'red',
			buttons: {
				"EXCLUIR": {
					btnClass: 'btn-danger',
					action: function() {
						$.ajax({
							type: "GET",
							url: "ajxTermosClientes.php",
							data: {
								acao: "del",
								cod_bloco: id,
								cod_empresa: "<?= @$_GET["id"] ?>"
							},
							beforeSend: function() {
								//$('#divId_sub').html('<div class="loading" style="width: 100%;"></div>');
							},
							success: function(data) {
								if (data == "ok") {
									$("#formLista").find("tr[data-id=" + id + "]").hide();
									refreshPreview();
									// console.log(data);
								} else {
									$.alert({
										title: "Mensagem",
										content: data,
										type: 'red'
									});
								}
							},
							error: function() {
								//
							}
						});
					}
				},
				"CANCELAR": {
					btnClass: 'btn-default',
					action: function() {

					}
				}
			},
			backgroundDismiss: function() {
				return 'CANCELAR';
			}
		});
	}
</script>