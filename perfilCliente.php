<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$hashLocal = "";
$codMaster = "";
$codEmpresa = "";
$codPerfil = "";
$codSistema = "";
$campoMontadoJson = "";
$nom_usuario = "";
$actual_link = "";
$MODULO = "";
$COD_MODULO = "";
$hHabilitado = "";
$hashForm = "";
$jsonAtual = "";
$retQueryJsonAtual = "";
$ARRAY = [];
$menuJson = [];
$temMenu = "";
$ArrayPOSt = [];
$ArrayPOSt1 = [];
$IdPOST = "";
$IdMOD = "";
$SqlInsPerfil = "";
$qrInsert = "";
$cod_erro = "";
$msgRetorno = "";
$SqlUpdate = "";
$qrUpdate = "";
$sqlUpdate = "";
$msgTipo = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$perfil_recebido = "";
$sistema_recebido = "";
$qrBuscaPerfil = "";
$cod_perfils = "";
$des_perfils = "";
$des_sistema = "";
$cod_sistema = "";
$abaEmpresa = "";
$formBack = "";
$arrMenu = "";
$arrMenuP = "";
$sql1 = "";
$arrayQuery1 = [];
$qrBuscaMenu = "";
$cargaM = "";
$tipoMenu1 = "";
$codMenu1 = "";
$modbusca2 = "";
$mod = "";
$arrSub = [];
$sql2 = "";
$arrayQuery2 = [];
$qrBuscaSubMenu = "";
$cargaS = "";
$tipoSUB = "";
$codSUB = "";
$modbusca1 = "";
$arrMod = "";
$sql3 = "";
$arrayQuery3 = [];
$qrBuscaModulo = "";
$tipoM = "";
$codM = "";
$modbusca = "";
$sqlPerfil = "";
$arrayQueryPerfil = [];
$qrBuscaModuloP = "";
$array = [];
$arrayPerfil = [];
$i = 0;
$tipoMenu = "";
$codMenu = "";
$idMenu = "";
$icoMenu3 = "";
$vl = "";
$menuV = "";
$codiV = "";
$sub1 = "";
$idMenu4 = "";
$tipoMenu2 = "";
$vl1 = "";
$codMenu2 = "";
$sub = "";
$idMenu1 = "";
$menuVs = "";
$codiVs = "";
$submod = 0;
$tipoMenu3 = "";
$codMenu3 = "";
$idMenu3 = "";
$vl2 = "";
$cod_sistemas = "";
$teste = "";
$perfilmaster = "";
$perfilmaster1 = "";
$qrSistemasEmpresa = "";
$sistemasEmpresa = "";
$qrListaUsuario = "";
$menuMaster = "";
$mostraMaster = "";

//echo fnDebug('true');

//fnMostraForm();
$hashLocal = mt_rand();

$conn = conntemp($cod_empresa, "");
$adm = $connAdm->connAdm();

$codMaster = 0;
$codEmpresa = 0;
$codPerfil = 0;
$codSistema = 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	if (isset($_REQUEST['menuMontadoJson'])) {
		$campoMontadoJson = @$_REQUEST['menuMontadoJson'];
	}

	$nom_usuario = $_SESSION["SYS_NOM_USUARIO"];
	$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$MODULO = @$_GET['mod'];
	$COD_MODULO = fndecode(@$_GET['mod']);

	$opcao = @$_REQUEST['opcao'];
	$hHabilitado = @$_REQUEST['hHabilitado'];
	$hashForm = @$_REQUEST['hashForm'];

	//filtros da lista de opções (baixo)
	$codMaster = @$_REQUEST['codMaster'];
	$codEmpresa = @$_REQUEST['codEmpresa'];
	$codPerfil = @$_REQUEST['codPerfil'];
	$codSistema = @$_REQUEST['codSistema'];

	//busca o json atualizado da base (MENU PRINCIPAL)
	$sql = "select * from menuprincipal where COD_SISTEMA='" . $codSistema . "'";
	$jsonAtual = mysqli_query($adm, $sql);
	$retQueryJsonAtual = mysqli_fetch_assoc($jsonAtual);

	//carrega json da tabela
	$ARRAY = REPLACE_STD_SET(@$retQueryJsonAtual['DES_MENUPRI']);
	$menuJson = json_decode($ARRAY, true);

	//se tem dados
	if (isset($retQueryJsonAtual)) {
		$temMenu = "sim";
	} else {
		$temMenu = "nao";
	}
	if ($opcao != '') {

		//mensagem de retorno
		switch ($opcao) {
			case 'CAD':
				$ArrayPOSt = @$_POST['modulo'];
				for ($ArrayPOSt1 = 0; $ArrayPOSt1 <= count($ArrayPOSt) - 1; $ArrayPOSt1++) {

					$IdPOST = substr($ArrayPOSt[$ArrayPOSt1], 4, 5);
					$IdMOD .= $IdPOST . ",";
					$IdPOST = substr($IdMOD, 0, -1);
				}

				$SqlInsPerfil = "insert into PERFIL (DES_PERFILS,COD_SISTEMA,COD_EMPRESA,COD_MODULOS) values ('" . @$_POST['DES_PERFILS'] . "','" . fnLimpaCampo(@$_REQUEST['COD_SISTEMA']) . "','" . fnLimpaCampo(@$_REQUEST['COD_EMPRESA']) . "','" . $IdPOST . "')";
				$qrInsert = mysqli_query($adm, $SqlInsPerfil);

				if (!$qrInsert) {

					$cod_erro = Log_error_comand($adm, $conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $SqlInsPerfil, $nom_usuario);
				}

				if ($cod_erro == 0 || $cod_erro ==  "") {
					$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
				} else {
					$msgRetorno = "Não foi possível cadastrar o registro : $cod_erro";
				}
				$temMenu = "sim";

				break;
			case 'ALT':
				$ArrayPOSt = @$_POST['modulo'];

				for ($ArrayPOSt1 = 0; $ArrayPOSt1 <= count($ArrayPOSt) - 1; $ArrayPOSt1++) {
					$IdPOST = substr($ArrayPOSt[$ArrayPOSt1], 4, 5);
					$IdMOD .= $IdPOST . ",";
					$IdPOST = substr($IdMOD, 0, -1);
				}
				$SqlUpdate = "UPDATE PERFIL SET COD_MODULOS='" . $IdPOST . "',DES_PERFILS='" . fnLimpaCampo(@$_POST['DES_PERFILS']) . "' WHERE COD_PERFILS = '" . @$_POST['COD_PERFILS'] . "'";
				$qrUpdate = mysqli_query($adm, $SqlUpdate);

				if (!$qrUpdate) {

					$cod_erro = Log_error_comand($adm, $conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $sqlUpdate, $nom_usuario);
				}

				if ($cod_erro == 0 || $cod_erro ==  "") {
					$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
				} else {
					$msgRetorno = "Não foi possível alterar o registro : $cod_erro";
				}
				break;
			case 'EXC':
				$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";
				break;
		}

		if ($cod_erro == 0 || $cod_erro == "") {
			$msgTipo = 'alert-success';
		} else {
			$msgTipo = 'alert-danger';
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
} else {
	$cod_empresa = 0;
	// $codEmpresa = $qrBuscaEmpresa['COD_SISTEMA'];

}

//fnEscreve(@$_REQUEST['codPerfil']);

//busca dados do perfil	
if (is_numeric(fnLimpacampo(@$_REQUEST['codPerfil']))) {

	//fnEscreve('entrou');
	//busca dados da empresa
	$perfil_recebido = fnLimpacampo(@$_REQUEST['codPerfil']);
	$sistema_recebido = fnLimpacampoZero(@$_REQUEST['codSistema']);

	$sql = "SELECT A.COD_PERFILS, A.DES_PERFILS, 
			(SELECT B.DES_SISTEMA FROM SISTEMAS B WHERE B.COD_SISTEMA = " . $sistema_recebido . " ) DES_SISTEMA 
			FROM perfil A  where A.COD_PERFILS = '" . $perfil_recebido . "' 
			";

	//fnEscreve($sql);
	$arrayQuery = mysqli_query($adm, $sql);
	$qrBuscaPerfil = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_perfils = $qrBuscaPerfil['COD_PERFILS'];
		$des_perfils = $qrBuscaPerfil['DES_PERFILS'];
		$des_sistema = $qrBuscaPerfil['DES_SISTEMA'];
		$cod_sistema = $sistema_recebido;
	}
} else {
	$cod_perfils = "";
	$des_perfils = "";
	$des_sistema = "";
	$cod_sistema = "";
	//fnEscreve('entrou else');
}

//fnEscreve($sistema_recebido);

?>
<style type="text/css">
	.cf:after {
		visibility: hidden;
		display: block;
		font-size: 0;
		content: " ";
		clear: both;
		height: 0;
	}

	* html .cf {
		zoom: 1;
	}

	*:first-child+html .cf {
		zoom: 1;
	}

	p {
		line-height: 1.5em;
	}

	.small {
		color: #666;
		font-size: 0.875em;
	}

	.large {
		font-size: 1.25em;
	}

	/**
		 * Nestable
		 */

	.dd {
		position: relative;
		display: block;
		margin: 0;
		padding: 0;
		max-width: auto;
		min-height: 30px;
		list-style: none;
		font-size: 13px;
		line-height: 20px;
	}

	.dd-list {
		display: block;
		position: relative;
		margin: 0;
		padding: 0;
		list-style: none;
	}

	.dd-list .dd-list {
		padding-left: 30px;
	}

	.dd-collapsed .dd-list {
		display: none;
	}

	.dd-item,
	.dd-empty,
	.dd-placeholder {
		display: block;
		position: relative;
		margin: 0;
		padding: 0;
		min-height: 20px;
		font-size: 15px;
	}

	.dd-handle {
		display: block;
		height: 30px;
		margin: 5px 0;
		padding: 1px 10px 1px 15px;
		color: #333;
		text-decoration: none;
		font-weight: normal;
		border: 1px solid #ccc;
		background: #fafafa;
		background: -webkit-linear-gradient(top, #fafafa 0%, #eee 100%);
		background: -moz-linear-gradient(top, #fafafa 0%, #eee 100%);
		background: linear-gradient(top, #fafafa 0%, #eee 100%);
		-webkit-border-radius: 3px;
		border-radius: 3px;
		box-sizing: border-box;
		-moz-box-sizing: border-box;
	}

	.dd-handle:hover {
		color: #2ea8e5;
		background: #fff;
	}

	.dd-nodrag {
		display: block;
		height: 30px;
		margin: 5px 0;
		padding: 2px 10px 7px 10px;
		color: #333;
		text-decoration: none;
		font-weight: normal;
		border: 1px solid #ccc;
		background: #fafafa;
		background: -webkit-linear-gradient(top, #dedede 0%, #cecece 100%);
		background: -moz-linear-gradient(top, #dedede 0%, #cecece 100%);
		background: linear-gradient(top, #dedede 0%, #cecece 100%);
		-webkit-border-radius: 3px;
		border-radius: 3px;
		box-sizing: border-box;
		-moz-box-sizing: border-box;
	}

	.dd-nodrag:hover {
		color: #2ea8e5;
		background: #dedede;
	}


	.dd-item>button {
		display: block;
		position: relative;
		cursor: pointer;
		float: left;
		width: 25px;
		height: 20px;
		margin: 5px 0;
		padding: 0;
		text-indent: 100%;
		white-space: nowrap;
		overflow: hidden;
		border: 0;
		background: transparent;
		font-size: 12px;
		line-height: 1;
		text-align: center;
		font-weight: normal;
	}

	.dd-item>button:before {
		content: '+';
		display: block;
		position: absolute;
		width: 100%;
		text-align: center;
		text-indent: 0;
	}

	.dd-item>button[data-action="collapse"]:before {
		content: '-';
	}

	.dd-placeholder,
	.dd-empty {
		margin: 5px 0;
		padding: 0;
		min-height: 30px;
		background: #f2fbff;
		border: 1px dashed #b6bcbf;
		box-sizing: border-box;
		-moz-box-sizing: border-box;
	}

	.dd-empty {
		border: 1px dashed #bbb;
		min-height: 100px;
		background-color: #e5e5e5;
		background-image: -webkit-linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff),
			-webkit-linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff);
		background-image: -moz-linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff),
			-moz-linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff);
		background-image: linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff),
			linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff);
		background-size: 60px 60px;
		background-position: 0 0, 30px 30px;
	}

	.dd-dragel {
		position: absolute;
		pointer-events: none;
		z-index: 9999;
	}

	.dd-dragel>.dd-item .dd-handle {
		margin-top: 0;
	}

	.dd-dragel .dd-handle {
		-webkit-box-shadow: 2px 4px 6px 0 rgba(0, 0, 0, .1);
		box-shadow: 2px 4px 6px 0 rgba(0, 0, 0, .1);
	}

	/**
		 * Nestable Extras
		 */

	.nestable-lists {
		display: block;
		clear: both;
		padding: 30px 0;
		width: 100%;
		border: 0;
		border-top: 2px solid #ddd;
		border-bottom: 2px solid #ddd;
	}

	#nestable-menu {
		padding: 0;
		margin: 20px 0;
	}

	#nestable-output,
	#nestable2-output,
	#nestable3-output,
	#nestable4-output {
		width: 100%;
		height: 7em;
		font-size: 0.75em;
		line-height: 1.333333em;
		font-family: Consolas, monospace;
		padding: 5px;
		box-sizing: border-box;
		-moz-box-sizing: border-box;
	}

	#nestable4 .dd-handle {
		color: #fff;
		border: 1px solid #999;
		background: #bbb;
		background: -webkit-linear-gradient(top, #bbb 0%, #999 100%);
		background: -moz-linear-gradient(top, #bbb 0%, #999 100%);
		background: linear-gradient(top, #bbb 0%, #999 100%);
	}

	#nestable4 .dd-handle:hover {
		background: #bbb;
	}

	#nestable4 .dd-item>button:before {
		color: #fff;
	}

	.dd-hover>.dd-handle {
		background: #2ea8e5 !important;
	}

	/**
		 * Nestable Draggable Handles
		 */

	.dd3-content {
		display: block;
		height: 30px;
		margin: 5px 0;
		padding: 5px 10px 5px 40px;
		color: #333;
		text-decoration: none;
		font-weight: normal;
		border: 1px solid #ccc;
		background: #fafafa;
		background: -webkit-linear-gradient(top, #fafafa 0%, #eee 100%);
		background: -moz-linear-gradient(top, #fafafa 0%, #eee 100%);
		background: linear-gradient(top, #fafafa 0%, #eee 100%);
		-webkit-border-radius: 3px;
		border-radius: 3px;
		box-sizing: border-box;
		-moz-box-sizing: border-box;
	}

	.dd3-content:hover {
		color: #2ea8e5;
		background: #fff;
	}

	.dd-dragel>.dd3-item>.dd3-content {
		margin: 0;
	}

	.dd3-item>button {
		margin-left: 30px;
	}

	.dd3-handle {
		position: absolute;
		margin: 0;
		left: 0;
		top: 0;
		cursor: pointer;
		width: 30px;
		text-indent: 100%;
		white-space: nowrap;
		overflow: hidden;
		border: 1px solid #aaa;
		background: #ddd;
		background: -webkit-linear-gradient(top, #ddd 0%, #bbb 100%);
		background: -moz-linear-gradient(top, #ddd 0%, #bbb 100%);
		background: linear-gradient(top, #ddd 0%, #bbb 100%);
		border-top-right-radius: 0;
		border-bottom-right-radius: 0;
	}

	.dd3-handle:before {
		content: '=';
		display: block;
		position: absolute;
		left: 0;
		top: 3px;
		width: 100%;
		text-align: center;
		text-indent: 0;
		color: #fff;
		font-size: 20px;
		font-weight: normal;
	}

	.dd3-handle:hover {
		background: #ddd;
	}

	/**
		 * Socialite
		 */

	.socialite {
		display: block;
		float: left;
		height: 35px;
	}

	.bigCheck {
		width: 20px;
		height: 20px;
		margin-top: 5px
	}
</style>


<div class="push20"></div>

<div class="row">

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fal fa-terminal"></i>
					<span class="text-primary"> <?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
				</div>

				<?php
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

				<?php if ($sistema_recebido == '') { ?>
					<div class="alert alert-warning alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						Selecione um perfil <b>abaixo</b> para manutenção
					</div>
				<?php } ?>

				<?php
				$abaEmpresa = 1018;

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
					case 19: //rh
						include "abasRH.php";
						break;
					default;
						include "abasEmpresaConfig.php";
						//$formBack = "1019";
						break;
				}
				?>

				<div class="push30"></div>

				<div class="login-form">

					<form onSubmit="return validaItens();" method="POST" id="formulario" action="<?php echo $cmdPage; ?>">


						<fieldset>
							<legend>Dados Gerais</legend>

							<div class="row">

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Código</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_PERFILS" id="COD_PERFILS" value="<?php echo $cod_perfils; ?>">
									</div>

								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Sistema</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="DES_SISTEMA" id="DES_SISTEMA" value="<?php echo $des_sistema; ?>">
										<input type="hidden" class="form-control input-sm" name="COD_SISTEMA" id="COD_SISTEMA" value="<?php echo $cod_sistema; ?>">
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label required">Nome do Perfil</label>
										<input type="text" class="form-control input-sm" name="DES_PERFILS" id="DES_PERFILS" maxlength="40" value="<?php echo $des_perfils; ?>" data-error="Campo obrigatório" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>

							</div>


							<?php if ($sistema_recebido == '') { ?>
								<div class="push20"></div>
								<h5>Selecione um perfil abaixo </h5>
							<?php } else { ?>

								<div style="display: none;">
									<!-- itens do menu -->
									<div class="dd" id="nestable">
										<ol class="dd-list"></ol>
									</div>

									<!-- itens do sub menu -->
									<div class="dd" id="nestable2">
										<ol class="dd-list"></ol>
									</div>

									<!-- módulos -->
									<div class="dd" id="nestable3">
										<ol class="dd-list"></ol>
									</div>

								</div>


								<div class="row">

									<div class="col-md-3"></div>

									<?php

									// itens do menu	
									$arrMenu = array();
									$arrMenuP = array();
									$sql1 = "select * from menus order by NOM_MENUSIS";
									$arrayQuery1 = mysqli_query($adm, $sql1);
									$count = 0;
									while ($qrBuscaMenu = mysqli_fetch_assoc($arrayQuery1)) {
										array_push($arrMenu, array("cod_menu" => $qrBuscaMenu['COD_MENUSIS'], "nom_menu" => $qrBuscaMenu['NOM_MENUSIS']));
									}
									for ($cargaM = 0; $cargaM <= count($arrMenu) - 1; $cargaM++) {
										$tipoMenu1 = $arrMenu[$cargaM]['nom_menu'];
										$codMenu1 = $arrMenu[$cargaM]['cod_menu'];
										$modbusca2 = 'MEN_' . $codMenu1;
										$mod = 'dd-handle';
										if (recursive_array_search($modbusca2, $menuJson) !== false) {
											$mod = 'dd-nodrag';
										} else {
											$mod = 'dd-handle';
										}
									}

									// itens do submenu 
									$arrSub = array();
									$sql2 = "select * from submenus order by nom_submenus";
									$arrayQuery2 = mysqli_query($adm, $sql2);
									$count = 0;
									while ($qrBuscaSubMenu = mysqli_fetch_assoc($arrayQuery2)) {
										array_push($arrSub, array("cod_sub" => $qrBuscaSubMenu['COD_SUBMENUS'], "nom_sub" => $qrBuscaSubMenu['NOM_SUBMENUS']));
									}
									for ($cargaS = 0; $cargaS <= count($arrSub) - 1; $cargaS++) {
										$tipoSUB = $arrSub[$cargaS]['nom_sub'];
										$codSUB = $arrSub[$cargaS]['cod_sub'];
										$modbusca1 = 'SUB_' . $codSUB;
										$mod = 'dd-handle';

										if (recursive_array_search($modbusca1, $menuJson) !== false) {
											$mod = 'dd-nodrag';
										} else {
											$mod = 'dd-handle';
										}
									}

									//módulos
									$arrMod = array();
									$sql3 = "select * from modulos order by DES_MODULOS";
									$arrayQuery3 = mysqli_query($adm, $sql3);

									$count = 0;
									while ($qrBuscaModulo = mysqli_fetch_assoc($arrayQuery3)) {
										array_push($arrMod, array("cod_mod" => $qrBuscaModulo['COD_MODULOS'], "nom_mod" => $qrBuscaModulo['NOM_MODULOS']));
									}
									for ($cargaM = 0; $cargaM <= count($arrMod) - 1; $cargaM++) {
										$tipoM = $arrMod[$cargaM]['nom_mod'];
										$codM = $arrMod[$cargaM]['cod_mod'];
										$modbusca = 'MOD_' . $codM;
										$mod = 'dd-handle';

										if (recursive_array_search($modbusca, $menuJson) !== false) {
											$mod = 'dd-nodrag';
										} else {
											$mod = 'dd-handle';
										}
									}


									$sqlPerfil = "select * from perfil where COD_PERFILS='" . $cod_perfils . "'";
									$arrayQueryPerfil = mysqli_query($adm, $sqlPerfil);
									while ($qrBuscaModuloP = mysqli_fetch_assoc($arrayQueryPerfil)) {
										$array = $qrBuscaModuloP['COD_MODULOS'];
										$arrayPerfil = explode(",", $array);
									}


									?>

									<div class="col-md-5">

										<div class="dd" id="nestable4">
											<?php if (!empty($cod_perfils)) {  ?>
												<div class="push20"></div>
												<h4> Autorização de Menu </h4>
												<div class="push5"></div>
												<div class="col-md-12 col-xs-12 col-sm-12 ">
													<div class="push20"></div>
													<a class="btn btn-xs btn-default" href="javascript:" onclick="check_checkbox('.bigCheck','T');">
														Marcar todos
													</a> &nbsp;&nbsp;

													<a class="btn btn-xs btn-default" href="javascript:" onclick="check_checkbox('.bigCheck','N');">
														Desmarcar todos
													</a> &nbsp;&nbsp;
													<a class="btn btn-xs btn-default" href="javascript:" onclick="check_checkbox('.bigCheck','I');">
														Inverter seleção
													</a>
												</div>
												<div class="push5"></div>
											<?php }  ?>
											<ol class="dd-list">

												<?php if ($temMenu == "nao") {
												} else {


													//nivel 1 loop
													for ($i = 0; $i <= count($menuJson) - 1; $i++) {
														$tipoMenu = substr($menuJson[$i]['id'], 0, 3);
														$codMenu = substr($menuJson[$i]['id'], 4, 5);
														$idMenu = $menuJson[$i]['id'];

														switch ($tipoMenu) {
															case 'MEN':
																$icoMenu3 = "fal fa-bars";
																$vl = (array_search($codMenu, array_column($arrMenu, 'cod_menu')));
																$menuV = $arrMenu[$vl]['nom_menu'];
																// nível menu
																echo ' <li class="dd-item" data-id="' . $idMenu . '">
                              <div class="dd-nodrag"><i class="' . $icoMenu3 . '" aria-hidden="true"></i>&nbsp;' . $menuV . '</div>
                              <!-- tem que ter esse antes de criar o filho -->';

																break;

															case 'SUB':
																$icoMenu3 = "fal fa-list";
																$vl = (array_search($codMenu, array_column($arrSub, 'cod_sub')));
																$menuV = $arrSub[$vl]['nom_sub'];
																echo ' <li class="dd-item" data-id="' . $idMenu . '">
                             <div class="dd-nodrag"><i class="' . $icoMenu3 . '" aria-hidden="true"></i>&nbsp;' . $menuV . '</div> <!-- tem que ter esse antes de criar o filho -->';
																break;
															case 'MOD':
																$icoMenu3 = "fal fa-caret-right";
																$vl = (array_search($codMenu, array_column($arrMod, 'cod_mod')));
																$menuV = $arrMod[$vl]['nom_mod'];
																$codiV = $arrMod[$vl]['cod_mod'];

																///////////////////////verifica checked true///////////////////////////////////////////
																if (recursive_array_search($codMenu, $arrayPerfil) !== false) {

																	echo ' <li class="dd-item" data-id="' . $idMenu . '">
                                         <div class="dd-nodrag"><i class="fal fa-caret-right" aria-hidden="true"></i> &nbsp;' . $menuV . ' &nbsp;<small class=f12>' . $codiV . ' </small> <div class="pull-right"><input type="checkbox" name="modulo[]" class="bigCheck" value="' . $idMenu . '" checked > </div></div> <!-- tem que ter esse antes de criar o filho -->';
																} else {
																	echo ' <li class="dd-item" data-id="' . $idMenu . '">
                                               <div class="dd-nodrag"><i class="fal fa-caret-right" aria-hidden="true"></i> &nbsp;' . $menuV . ' &nbsp;<small class=f12>' . $codiV . ' </small> <div class="pull-right"><input type="checkbox" name="modulo[]" class="bigCheck" value="' . $idMenu . '"  > </div></div> <!-- tem que ter esse antes de criar o filho -->';
																}
																////////////////////////////////////////////////////////////////////////////////////////////////    
																break;
														}

														if (isset($menuJson[$i]['children'][$sub]['children']) && is_array($menuJson[$i]['children'][$sub]['children'])) {
															for ($submod = 0; $submod < count($menuJson[$i]['children'][$sub]['children']); $submod++) {

																if (@$menuJson[$i]['children'][@$sub1]['children'][0] == $menuJson[@$i]['children']) {
																	$tipoMenu1 = substr($menuJson[$i]['children'][$sub1]['id'], 0, 3);
																	$codMenu1 = substr($menuJson[$i]['children'][$sub1]['id'], 4, 5);
																	$idMenu4 = $menuJson[$i]['children'][$sub1]['id'];

																	switch ($tipoMenu2) {
																		case 'MEN':
																			$icoMenu3 = "fal fa-bars";
																			$vl1 = (array_search($codMenu2, array_column($arrMenu, 'cod_menu')));
																			$menuV = $arrMenu[$vl1]['nom_menu'];
																			echo '
                                                    <!-- nivel sozinho - mesmo nivel sub menu -->
                                                     <ol class="dd-list">			
                                                        <li class="dd-item" data-id="' . $idMenu4 . '">
                                                            <div class="dd-nodrag"><i class="' . $icoMenu3 . '" aria-hidden="true"></i>&nbsp;' . $menuV . '</div>
                                                        </li>
                                                   </ol>';
																			break;
																		case 'SUB':

																			$icoMenu3 = "fal fa-list";
																			$vl = (array_search($codMenu2, array_column($arrSub, 'cod_sub')));
																			$menuV = $arrSub[$vl]['nom_sub'];
																			echo '
                                                    <!-- nivel sozinho - mesmo nivel sub menu -->
                                                     <ol class="dd-list">			
                                                        <li class="dd-item" data-id="' . $idMenu4 . '">
                                                            <div class="dd-nodrag"><i class="' . $icoMenu3 . '" aria-hidden="true"></i>&nbsp;' . $menuV . '</div>
                                                        </li>
                                                   </ol>';
																			break;
																		case 'MOD':
																			$icoMenu3 = "fal fa-caret-right";
																			$vl = (array_search($codMenu2, array_column($arrMod, 'cod_mod')));
																			$menuV = $arrMod[$vl]['nom_mod'];
																			$codiV = $arrMod[$vl]['cod_mod'];
																			///////////////////////verifica checked true///////////////////////////////////////////
																			if (recursive_array_search($codMenu1, $arrayPerfil) !== false) {

																				echo '
                                                            <!-- nivel sozinho - mesmo nivel sub menu -->
                                                             <ol class="dd-list">			
                                                                <li class="dd-item" data-id="' . $idMenu4 . '">
                                                                    <div class="dd-nodrag"><i class="fal fa-caret-right" aria-hidden="true"></i> &nbsp;' . $menuV . ' &nbsp;<small class=f12>' . $codiV . ' </small> <div class="pull-right"><input type="checkbox" name="modulo[]" class="bigCheck" value="' . $idMenu4 . '"  checked > </div></div>
                                                                </li>
                                                           </ol>';
																			} else {
																				echo '
                                                            <!-- nivel sozinho - mesmo nivel sub menu -->
                                                             <ol class="dd-list">			
                                                                <li class="dd-item" data-id="' . $idMenu4 . '">
                                                                    <div class="dd-nodrag"><i class="fal fa-caret-right" aria-hidden="true"></i> &nbsp;' . $menuV . ' &nbsp;<small class=f12>' . $codiV . ' </small> <div class="pull-right"><input type="checkbox" name="modulo[]" class="bigCheck" value="' . $idMenu4 . '" ></div></div>
                                                                </li>
                                                           </ol>';
																			}
																			break;
																	}
																}
															}
														}

														if (isset($menuJson[$i]['children']) && is_array($menuJson[$i]['children'])) {
															for ($sub = 0; $sub <= count(@$menuJson[$i]['children']) - 1; $sub++) {

																$tipoMenu2 = substr($menuJson[$i]['children'][$sub]['id'], 0, 3);
																$codMenu2 = substr($menuJson[$i]['children'][$sub]['id'], 4, 5);
																$idMenu1 = $menuJson[$i]['children'][$sub]['id'];
																switch ($tipoMenu2) {
																	case 'MEN':
																		$icoMenu3 = "fal fa-bars";
																		$vl1 = (array_search($codMenu2, array_column($arrMenu, 'cod_menu')));
																		$menuVs = $arrMenu[$vl1]['nom_menu'];
																		echo '<!-- nivel submenu - classe filho -->
                                <ol class="dd-list">
                                <li class="dd-item" data-id="' . $idMenu1 . '">
                                <div class="dd-nodrag"><i class="' . $icoMenu3 . '" aria-hidden="true"></i>&nbsp;' . $menuVs . '</div><!-- tem que ter esse antes de criar o filho -->
                                ';
																		break;
																	case 'SUB':

																		$icoMenu3 = "fal fa-list";
																		$vl = (array_search($codMenu2, array_column($arrSub, 'cod_sub')));
																		$menuVs = $arrSub[$vl]['nom_sub'];
																		echo '<!-- nivel submenu - classe filho -->
                                 <ol class="dd-list">
                                 <li class="dd-item" data-id="' . $idMenu1 . '">
                                 <div class="dd-nodrag"><i class="' . $icoMenu3 . '" aria-hidden="true"></i>&nbsp;' . $menuVs . '</div><!-- tem que ter esse antes de criar o filho -->
                                 ';
																		break;
																	case 'MOD':
																		$icoMenu3 = "fal fa-caret-right";
																		$vl = (array_search($codMenu2, array_column($arrMod, 'cod_mod')));
																		$menuVs = $arrMod[$vl]['nom_mod'];
																		$codiVs = $arrMod[$vl]['cod_mod'];

																		///////////////////////verifica checked true///////////////////////////////////////////
																		if (recursive_array_search($codMenu2, $arrayPerfil) !== false) {
																			echo '<!-- nivel submenu - classe filho -->
                                      <ol class="dd-list">
                                      <li class="dd-item" data-id="' . $idMenu1 . '">

                                      <div class="dd-nodrag"><i class="fal fa-caret-right" aria-hidden="true"></i> &nbsp;' . $menuVs . ' &nbsp;<small class=f12>' . $codiVs . ' </small> <div class="pull-right"><input type="checkbox" name="modulo[]" class="bigCheck" value="' . $idMenu1 . '" checked > </div></div><!-- tem que ter esse antes de criar o filho -->
                                      ';
																		} else {
																			echo '<!-- nivel submenu - classe filho -->
                                      <ol class="dd-list">
                                      <li class="dd-item" data-id="' . $idMenu1 . '">

                                      <div class="dd-nodrag"><i class="fa fa-caret-right" aria-hidden="true"></i> &nbsp;' . $menuVs . ' &nbsp;<small class=f12>' . $codiVs . ' </small> <div class="pull-right"><input type="checkbox" name="modulo[]" class="bigCheck" value="' . $idMenu1 . '" > </div></div><!-- tem que ter esse antes de criar o filho -->
                                      ';
																		}


																		///////////////////////////////////////////////////////////////////////

																		break;
																}

																// print_r($menuJson);

																if (isset($menuJson[$i]['children'][$sub]['children']) && is_array($menuJson[$i]['children'][$sub]['children'])) {
																	for ($submod = 0; $submod <= count(@$menuJson[@$i]['children'][@$sub]['children']) - 1; $submod++) {
																		$tipoMenu3 = substr($menuJson[$i]['children'][$sub]['children'][$submod]['id'], 0, 3);
																		$codMenu3 = substr($menuJson[$i]['children'][$sub]['children'][$submod]['id'], 4, 5);
																		$idMenu3 = $menuJson[$i]['children'][$sub]['children'][$submod]['id'];
																		switch ($tipoMenu3) {
																			case 'MEN':
																				$icoMenu3 = "fal fa-bars";
																				$vl2 = (array_search($codMenu3, array_column($arrMenu, 'cod_menu')));
																				$menuV = $arrMenu[$vl2]['nom_menu'];
																				echo '<!-- ultimo nivel -->
                                                    <ol class="dd-list">
                                                        <li class="dd-item" data-id="' . $idMenu3 . '">
                                                            <div class="dd-nodrag"><i class="' . $icoMenu3 . '" aria-hidden="true"></i>&nbsp;' . $menuV . '</div>
                                                        </li>
                                                    </ol><!-- fim ultimo nivel -->
                                                    ';
																				break;
																			case 'SUB':
																				$icoMenu3 = "fal fa-list";
																				$vl = (array_search($codMenu3, array_column($arrSub, 'cod_sub')));
																				$menuV = $arrSub[$vl]['nom_sub'];
																				echo '<!-- ultimo nivel -->
                                                    <ol class="dd-list">
                                                        <li class="dd-item" data-id="' . $idMenu3 . '">
                                                            <div class="dd-nodrag"><i class="' . $icoMenu3 . '" aria-hidden="true"></i>&nbsp;' . $menuV . '</div>
                                                        </li>
                                                    </ol><!-- fim ultimo nivel -->
                                                    ';
																				break;
																			case 'MOD':
																				$icoMenu3 = "fal fa-caret-right";
																				$vl = (array_search($codMenu3, array_column($arrMod, 'cod_mod')));
																				$menuV = $arrMod[$vl]['nom_mod'];
																				$codiV = $arrMod[$vl]['cod_mod'];
																				///////////////////////verifica checked true///////////////////////////////////////////
																				if (recursive_array_search($codMenu3, $arrayPerfil) !== false) {
																					echo '<!-- ultimo nivel -->
                                                            <ol class="dd-list">
                                                                <li class="dd-item" data-id="' . $idMenu3 . '">
                                                                    <div class="dd-nodrag"><i class="fa fa-caret-right" aria-hidden="true"></i> &nbsp;' . $menuV . ' &nbsp;<small class=f12>' . $codiV . ' </small> <div class="pull-right"><input type="checkbox" class="bigCheck" name="modulo[]" value="' . $idMenu3 . '" checked></div></div>
                                                                </li>
                                                            </ol><!-- fim ultimo nivel -->
                                                            ';
																				} else {
																					echo '<!-- ultimo nivel -->
                                                            <ol class="dd-list">
                                                                <li class="dd-item" data-id="' . $idMenu3 . '">
                                                                    <div class="dd-nodrag"><i class="fa fa-caret-right" aria-hidden="true"></i> &nbsp;' . $menuV . ' &nbsp;<small class=f12>' . $codiV . ' </small> <div class="pull-right"><input type="checkbox" class="bigCheck" name="modulo[]" value="' . $idMenu3 . '" ></div></div>
                                                                </li>
                                                            </ol><!-- fim ultimo nivel -->
                                                            ';
																				}
																				/////////////////////////////////////////////////////////////////

																				break;
																		}
																	}
																}


																echo '    </li>  </ol><!-- fim nivel submenu - classe filho -->

                 </li><!-- fim nível menu -->';
															}
														}
														echo  '</li><!-- fim nível menu -->';
													}

													for ($cargaM = 0; $cargaM <= count($arrMod) - 1; $cargaM++) {
														//substr($cod_sistemas,0,-1);
														$tipoM = $arrMod[$cargaM]['nom_mod'];
														$codM = $arrMod[$cargaM]['cod_mod'];
														$modbusca = 'MOD_' . $codM;
														$mod = 'dd-handle';
														if (recursive_array_search($modbusca, $menuJson) !== false) {
															//  $teste=$modbusca='MOD_'.$codM;
															$perfilmaster .= $codM . ",";
														} else {
															$teste = "";
														}

														$perfilmaster1 = substr($perfilmaster, 0, -1);
													}
												} ?>

											</ol>

										</div>
										<div class="col-md-12 col-xs-12 col-sm-12 ">
											<div class="push20"></div>
											<a class="btn btn-xs btn-default" href="javascript:" onclick="check_checkbox('.bigCheck','T');">
												Marcar todos
											</a> &nbsp;&nbsp;

											<a class="btn btn-xs btn-default" href="javascript:" onclick="check_checkbox('.bigCheck','N');">
												Desmarcar todos
											</a> &nbsp;&nbsp;
											<a class="btn btn-xs btn-default" href="javascript:" onclick="check_checkbox('.bigCheck','I');">
												Inverter seleção
											</a>
										</div>
									</div>
								</div>

							<?php
								//falta de perfil
							} ?>

							<div class="push50"></div>

						</fieldset>

						<div class="push10"></div>

						<?php

						if ($codMaster === "S") {  ?>

							<hr>
							<div class="form-group text-right col-lg-12">

								<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>

							</div>

						<?php }

						if ($codMaster === "N") {  ?>

							<hr>
							<div class="form-group text-right col-lg-12">

								<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>
								<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>

							</div>

						<?php } ?>

						<div class="push5"></div>

						<div style="display:none;">
							<textarea id="nestable-output"></textarea>
							<textarea id="nestable2-output"></textarea>
							<textarea id="nestable3-output"></textarea>
							<textarea id="nestable4-output" name="menuMontadoJson"><?php echo @$retQueryJsonAtual['DES_MENUPRI']; ?></textarea>
						</div>

						<!-- variaveis do sistema escolhido -->
						<input type="hidden" name="perfil" id="codEmpresa" value="<?php echo $perfilmaster1; ?>">
						<input type="hidden" name="codEmpresa" id="codEmpresa" value="<?php echo $codEmpresa; ?>">
						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
						<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">

					</form>

					<div class="push"></div>

					<div class="col-lg-12">

						<div class="no-more-tables">

							<form name="formLista" id="formLista" method="post">

								<table class="table table-bordered table-striped table-hover tableSorter">
									<thead>
										<tr>
											<th class="{ sorter: false }" width="40"></th>
											<th>Código</th>
											<th>Sistema</th>
											<th>Descrição do Perfil</th>
											<th>Master</th>
										</tr>
									</thead>
									<tbody>
										<?php

										$sql1 = "SELECT COD_SISTEMAS FROM EMPRESAS WHERE COD_EMPRESA = '" . $cod_empresa . "' ";
										$arrayQuery = mysqli_query($adm, $sql1);
										$qrSistemasEmpresa = mysqli_fetch_assoc($arrayQuery);
										$sistemasEmpresa = $qrSistemasEmpresa['COD_SISTEMAS'];

										$sql = ' SELECT COD_PERFILS,DES_PERFILS,PERFIL.COD_SISTEMA,PERFIL.COD_EMPRESA,COD_MODULOS, DES_ABREVIA, DES_SISTEMA '
											. 'FROM PERFIL,SISTEMAS '
											. 'WHERE '
											. 'PERFIL.COD_SISTEMA=SISTEMAS.COD_SISTEMA AND '
											. 'PERFIL.COD_SISTEMA IN(' . $sistemasEmpresa . ') '
											. 'AND  PERFIL.COD_EMPRESA IS NULL '
											. 'UNION '
											. 'SELECT COD_PERFILS,DES_PERFILS,PERFIL.COD_SISTEMA,PERFIL.COD_EMPRESA,COD_MODULOS, DES_ABREVIA, DES_SISTEMA '
											. 'FROM PERFIL,SISTEMAS '
											. 'WHERE '
											. 'PERFIL.COD_SISTEMA=SISTEMAS.COD_SISTEMA AND '
											. 'PERFIL.COD_EMPRESA = ' . $cod_empresa . ' ';

										$arrayQuery = mysqli_query($adm, $sql);
										//fnEscreve($sql);

										$count = 0;
										while ($qrListaUsuario = mysqli_fetch_assoc($arrayQuery)) {
											$count++;

											if (empty($qrListaUsuario['COD_EMPRESA'])) {
												$menuMaster = "S";
												$mostraMaster = '<i class="fal fa-check" aria-hidden="true"></i>';
											} else {
												$menuMaster = "N";
												$mostraMaster = '';
											}

											echo "
															<tr>
															  <td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(" . $count . ")'></th>
															  <td>" . $qrListaUsuario['COD_PERFILS'] . "</td>
															  <td>" . $qrListaUsuario['DES_SISTEMA'] . "</td>
															  <td>" . $qrListaUsuario['DES_PERFILS'] . "</td>
															  <td class='text-center'>" . $mostraMaster . "</td>
															</tr>
															<input type='hidden' id='ret_MASTER_" . $count . "' value='" . $menuMaster . "'>
															<input type='hidden' id='ret_COD_EMPRESA_" . $count . "' value='" . $cod_empresa . "'>
															<input type='hidden' id='ret_COD_PERFILS_" . $count . "' value='" . $qrListaUsuario['COD_PERFILS'] . "'>
															<input type='hidden' id='ret_COD_SISTEMA_" . $count . "' value='" . $qrListaUsuario['COD_SISTEMA'] . "'>
															";
										}

										?>

									</tbody>
								</table>

								<input type="hidden" name="codMaster" id="codMaster" value="">
								<input type="hidden" name="codEmpresa" id="codEmpresa" value="">
								<input type="hidden" name="codPerfil" id="codPerfil" value="">
								<input type="hidden" name="codSistema" id="codSistema" value="">
								<input type="hidden" name="opcao" id="opcao" value="">
								<input type="hidden" name="hashForm" id="hashForm" value="<?php $hashLocal; ?>" />
								<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

							</form>

						</div>

					</div>

					<div class="push30"></div>

				</div>

			</div>
		</div>
		<!-- fim Portlet -->
	</div>

</div>

<div class="push20"></div>


<script>
	$(document).ready(function() {

		//$("#shortRFH").prop('disabled', true);
		//$("#shortRFH").addClass("disabled");

	});

	function retornaForm(index) {
		//alert(1);
		$("#formLista #codMaster").val($("#ret_MASTER_" + index).val());
		$("#formLista #codPerfil").val($("#ret_COD_PERFILS_" + index).val());
		$("#formLista #codEmpresa").val($("#ret_COD_EMPRESA_" + index).val());
		$("#formLista #codSistema").val($("#ret_COD_SISTEMA_" + index).val());
		$('#formLista').attr('action', '<?php echo $cmdPage; ?>');
		$('#formLista').submit();
	}

	function check_checkbox(check, acao = "I") {
		if (acao == "T" || acao == "N") {
			$(check).prop("checked", (acao == "T"));
		} else {
			$(check).each(function() {
				$(this).prop("checked", !(this.checked));
			});
		}
	}

	function validaItens() {
		if ($(".bigCheck:checked").length <= 0) {
			alert("É necessário autorizar, pelo menos, 1 menu!");
			return false;
		} else {
			return true;
		}
	}
</script>
<?php


?>