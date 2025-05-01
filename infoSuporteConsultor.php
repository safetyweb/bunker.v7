<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$hashLocal = "";
$cod_chamado = "";
$mod = "";
$msgRetorno = "";
$msgTipo = "";
$nom_chamado = "";
$dat_cadastr = "";
$dat_chamado = "";
$cod_usuario = "";
$cod_sistemas = "";
$cod_externo = "";
$link_externo = "";
$url = "";
$cod_integradora = "";
$cod_plataforma = "";
$cod_tpsolicitacao = "";
$cod_versaointegra = "";
$cod_status = "";
$cod_prioridade = "";
$des_sac = "";
$sac_anexo = "";
$hHabilitado = "";
$hashForm = "";
$usu_cadastr = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$sqlSac = "";
$arrayQuerySac = [];
$qrSac = "";
$sqlEmpresa = "";
$qrNomEmp = "";
$selectSolicitante = "";
$selectRespons = "";
$sqlUsuarios = "";
$qrNomUsu = "";
$des_email = "";
$num_telefone = "";
$cod_usuarios_env = "";
$cod_consultores = "";
$dat_entrega = "";
$qrCont = "";
$conta = "";
$primeiroUp = "";
$tp_prazo = "";
$formBack = "";
$abaInfoSuporte = "";
$qrListaUnive = "";
$qrLista = "";
$arrayQueryComment = [];
$qrComment = "";
$interno = "";
$mes = "";


//echo "<h5>_".$opcao."</h5>"; 

$hashLocal = mt_rand();
$cod_chamado = fnLimpaCampoZero(fnDecode(@$_GET['idC']));
$mod = fnLimpaCampoZero(fnDecode(@$_GET['mod']));

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_chamado = fnLimpacampo(@$_REQUEST['COD_CHAMADO']);
		$nom_chamado = fnLimpacampo(@$_REQUEST['NOM_CHAMADO']);
		$cod_empresa = fnLimpacampo(@$_REQUEST['COD_EMPRESA']);
		$dat_cadastr = fnDateSql(fnLimpacampo(@$_REQUEST['DAT_CADASTR']));
		$dat_chamado = fnDataSql(fnLimpacampo(@$_REQUEST['DAT_CHAMADO']));
		$cod_usuario = fnLimpacampo(@$_REQUEST['COD_USUARIO']);
		$cod_sistemas = fnLimpacampo(@$_REQUEST['COD_SISTEMAS']);
		$cod_externo = fnLimpacampo(@$_REQUEST['COD_EXTERNO']);
		$link_externo = fnLimpacampo(@$_REQUEST['LINK_EXTERNO']);
		$url = fnLimpacampo(@$_REQUEST['URL']);
		$cod_integradora = fnLimpacampoZero(@$_REQUEST['COD_INTEGRADORA']);
		$cod_plataforma = fnLimpacampoZero(@$_REQUEST['COD_PLATAFORMA']);
		$cod_tpsolicitacao = fnLimpacampoZero(@$_REQUEST['COD_TPSOLICITACAO']);
		$cod_versaointegra = fnLimpacampoZero(@$_REQUEST['COD_VERSAOINTEGRA']);
		$cod_status = fnLimpacampoZero(@$_REQUEST['COD_STATUS']);
		$cod_prioridade = fnLimpacampoZero(@$_REQUEST['COD_PRIORIDADE']);
		$des_sac = fnLimpacampo(@$_REQUEST['DES_SAC']);
		$sac_anexo = fnLimpacampo(@$_REQUEST['SAC_ANEXO']);


		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		$usu_cadastr = $_SESSION["SYS_COD_USUARIO"];

		if ($opcao != '') {


			switch ($opcao) {
				case 'CAD':
					$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					break;
				case 'ALT':
					$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";
					break;
				case 'EXC':

					$sql = "DELETE FROM SAC_CHAMADOS WHERE COD_CHAMADO = $cod_chamado";
					mysqli_query($connAdmSAC->connAdm(), $sql);
?>
					<script type="text/javascript">
						window.location.replace("http://adm.bunker.mk/action.do?mod=kiWbp%C2%A3ffARCI%C2%A2&x=<?= fnEncode(1) ?>");
					</script>
<?php
					break;
					break;
			}
			$msgTipo = 'alert-success';
		}
	}
}


//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id']))) && fnDecode(@$_GET['id']) != 0) {
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
}

$sqlSac = "SELECT SC.*, 
				SP.DES_PLATAFORMA,ST.DES_TPSOLICITACAO, 
				SV.DES_VERSAOINTEGRA, SPR.DES_PRIORIDADE, SS.ABV_STATUS, 
				ST.DES_ICONE AS ICO_TIPO, ST.DES_COR AS COR_TIPO,
				SPR.DES_ICONE AS ICO_PRIORIDADE, SPR.DES_COR AS COR_PRIORIDADE,
				SS.DES_ICONE AS ICO_STATUS, SS.DES_COR AS COR_STATUS,
				SC.LOG_ADM
				FROM SAC_CHAMADOS SC 
				LEFT JOIN SAC_PLATAFORMA SP ON SP.COD_PLATAFORMA=SC.COD_PLATAFORMA
				LEFT JOIN SAC_TPSOLICITACAO ST ON ST.COD_TPSOLICITACAO=SC.COD_TPSOLICITACAO
				LEFT JOIN SAC_VERSAOINTEGRA SV ON SV.COD_VERSAOINTEGRA=SC.COD_VERSAOINTEGRA
				LEFT JOIN SAC_PRIORIDADE SPR ON SPR.COD_PRIORIDADE=SC.COD_PRIORIDADE
				LEFT JOIN SAC_STATUS SS ON SS.COD_STATUS=SC.COD_STATUS
				WHERE SC.COD_CHAMADO = $cod_chamado
				";

$arrayQuerySac = mysqli_query($connAdmSAC->connAdm(), $sqlSac);

$qrSac = mysqli_fetch_assoc($arrayQuerySac);

$sqlEmpresa = "SELECT NOM_FANTASI FROM EMPRESAS WHERE COD_EMPRESA = $qrSac[COD_EMPRESA]";
$qrNomEmp = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $sqlEmpresa));

if ($qrSac['COD_USUARIO'] != '') {
	$selectSolicitante = "(SELECT NOM_USUARIO FROM USUARIOS WHERE COD_USUARIO = $qrSac[COD_USUARIO]) AS NOM_SOLICITANTE";
} else {
	$selectSolicitante = "('') AS NOM_SOLICITANTE";
}

if ($qrSac['COD_USURES'] != '') {
	$selectRespons = "(SELECT NOM_USUARIO FROM USUARIOS WHERE COD_USUARIO = $qrSac[COD_USURES]) AS NOM_RESPONSAVEL";
} else {
	$selectRespons = "('') AS NOM_RESPONSAVEL";
}

$sqlUsuarios = "SELECT $selectSolicitante,$selectRespons";
//fnEscreve($sqlUsuarios);
$qrNomUsu = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $sqlUsuarios));

if (isset($qrSac)) {

	$cod_chamado = $qrSac['COD_CHAMADO'];
	$nom_chamado = $qrSac['NOM_CHAMADO'];
	$dat_cadastr = fnDataFull($qrSac['DAT_CADASTR']);
	$dat_chamado = fnDateRetorno($qrSac['DAT_CHAMADO']);
	$cod_usuario = $qrSac['COD_USUARIO'];
	$cod_externo = $qrSac['COD_EXTERNO'];
	$link_externo = $qrSac['LINK_EXTERNO'];
	$des_email = $qrSac['DES_EMAIL'];
	$num_telefone = $qrSac['NUM_TELEFONE'];
	$cod_sistemas = $qrSac['COD_SISTEMAS'];
	$cod_integradora = $qrSac['COD_INTEGRADORA'];
	$cod_plataforma = $qrSac['COD_PLATAFORMA'];
	$cod_prioridade = $qrSac['COD_PRIORIDADE'];
	$cod_versaointegra = $qrSac['COD_VERSAOINTEGRA'];
	$cod_status = $qrSac['COD_STATUS'];
	$cod_tpsolicitacao = $qrSac['COD_TPSOLICITACAO'];
	$url = $qrSac['URL'];
	$cod_univend = $qrSac['COD_UNIVEND'];
	$cod_usuarios_env = $qrSac['COD_USUARIOS_ENV'];
	$cod_consultores = $qrSac['COD_CONSULTORES'];
	$des_sac = $qrSac['DES_SAC'];
	$sac_anexo = $qrSac['SAC_ANEXO'];
	$usu_cadastr = $qrSac['USU_CADASTR'];
	$des_email = $qrSac['DES_EMAIL'];
	$num_telefone = $qrSac['NUM_TELEFONE'];
	if ($qrSac['DAT_ENTREGA'] == '1969-12-31') {
		$dat_entrega = "";
	} else {
		$dat_entrega = fnDataShort($qrSac['DAT_ENTREGA']);
	}
} else {
	$cod_externo = "";
	$dat_cadastr = (new \DateTime())->format('d/m/Y H:i:s');
	$dat_chamado = (new \DateTime())->format('d/m/Y');
	$nom_chamado = "";
	$cod_tpsolicitacao = "0";
	$cod_usuario = "0";
	$cod_plataforma = "0";
	$cod_tpsolicitacao = "0";
	$cod_versaointegra = "0";
	$cod_status = "0";
	$cod_integradora = "0";
	$cod_prioridade = "0";
	$cod_sistemas = "0";
	$url = "";
	$cod_univend = "0";
	$cod_usuarios_env = "0";
	$cod_consultores = "0";
	$des_sac = "";
	$sac_anexo = "Sem Anexo";
}

$sql = "SELECT COD_REFDOWN FROM SAC_ANEXO WHERE COD_CHAMADO = $cod_chamado";
$arrayQuery = mysqli_query($connAdmSAC->connAdm(), $sql);
$qrCont = mysqli_fetch_assoc($arrayQuery);

if (!isset($qrCont) || $cod_chamado == 0) {

	$sql = "UPDATE CONTADOR SET NUM_CONTADOR = (NUM_CONTADOR+1) WHERE COD_CONTADOR = 2";
	mysqli_query($connAdmSAC->connAdm(), $sql);

	$sql = "SELECT NUM_CONTADOR FROM CONTADOR WHERE COD_CONTADOR = 2";
	$arrayQuery = mysqli_query($connAdmSAC->connAdm(), $sql);
	$qrCont = mysqli_fetch_assoc($arrayQuery);

	$conta = $qrCont['NUM_CONTADOR'];
	$primeiroUp = "S";
} else {
	$conta = $qrCont['COD_REFDOWN'];
	$primeiroUp = "N";
}

//fnEscreve($conta);						


$adm = "";
if ($qrSac['LOG_ADM'] == 'S') {
	$adm = "<i class='fal fa-user-check shortCut' data-toggle='tooltip' data-placement='left' data-original-title='ti'></i>";
} else {
	$adm = "<i class='fal fa-user-tie shortCut' data-toggle='tooltip' data-placement='left' data-original-title='cliente'></i>";
}

$tp_prazo = "";
if ($qrSac['LOG_ANALISE'] == 'S') {
	$tp_prazo = "de Análise";
} else {
	$tp_prazo = "de Entrega";
}


?>




<style>
	.chosen-big+div>.chosen-single {
		height: 45px !important;
		line-height: 20px !important;
		padding: 10px 15px !important;
	}

	.chosen-container-multi .chosen-choices {
		border: 0px solid #dce4ec;
	}

	.leitura2 {
		border: none transparent !important;
		outline: none !important;
		background: #fff !important;
		font-size: 16px;
		padding: 0;
	}

	.cd-timeline>h1,
	.cd-timeline>h2 {
		font-size: 16px;
		font-weight: 400;
		margin-top: 13px;
	}

	@media only screen and (min-width: 1170px) {
		.cd-is-hidden {
			visibility: hidden;
		}
	}

	.cd-timeline>h1,
	.cd-timeline>h2 {
		font-size: 16px;
		font-weight: 400;
		margin-top: 13px;
	}

	@media only screen and (min-width: 1170px) {
		.cd-is-hidden {
			visibility: hidden;
		}
	}

	.cd-timeline {
		overflow: hidden;
		margin: 2em auto;
		background: #fff;
	}

	.cd-timeline__block:nth-child(n) .cd-timeline__img {
		background: #AED6F1;
	}

	.cd-timeline__block2:nth-child(n) .cd-timeline__img {
		background: #cecece;
	}

	.cd-timeline__container {
		position: relative;
		width: 80%;
		max-width: 90%;
		margin: 0 auto;
		padding: 6px 0 2em 0;
	}

	.cd-timeline__container::before {
		/* this is the vertical line */
		content: '';
		position: absolute;
		margin-top: 18px;
		left: 18px;
		height: 100%;
		border-right: dashed 4px #cecece;
	}

	.cd-timeline__block2:nth-child(n) .cd-timeline__content {
		background: #FFF;
		border: 1px #cecece;
	}

	.cd-timeline__block2:nth-child(n) .cd-timeline__content h2 {
		color: #2c3e50;
		border-bottom-color: #2c3e50;
	}

	.cd-timeline__block2:nth-child(n) .cd-timeline__content p {
		color: #2c3e50;
	}

	.cd-timeline__block:nth-child(n) .cd-timeline__content::before {
		border-right-color: #AED6F1;
	}

	.cd-timeline__img {
		position: absolute;
		top: 12px;
		left: 0;
		right: 0;
		margin-left: auto;
		margin-right: auto;
		width: 18px;
		height: 18px;
		border-radius: 50%;
		-webkit-box-shadow: 0 0 0 4px white, inset 0 2px 0 rgba(0, 0, 0, 0.08), 0 3px 0 4px rgba(0, 0, 0, 0.05);
		box-shadow: 0 0 0 4px white, inset 0 2px 0 rgba(0, 0, 0, 0.08), 0 3px 0 4px rgba(0, 0, 0, 0.05);
	}

	@media only screen and (min-width: 1170px) {
		.cd-timeline {
			margin-top: 3em;
			margin-bottom: 3em;
		}

		.cd-timeline__container::before {
			left: 50%;
			margin-left: -2px;
		}
	}

	.cd-timeline__block,
	.cd-timeline__block2 {
		position: relative;
		margin: 2em 0;
	}

	.cd-timeline__block:after,
	.cd-timeline__block2:after {
		/* clearfix */
		content: "";
		display: table;
		clear: both;
	}

	.cd-timeline__block:first-child,
	.cd-timeline__block2:first-child {
		margin-top: 0;
	}

	.cd-timeline__block:last-child,
	.cd-timeline__block2:last-child {
		margin-bottom: 0;
	}

	@media only screen and (min-width: 1170px) {

		.cd-timeline__block,
		.cd-timeline__block2 {
			margin: 1.5em 0;
		}
	}

	@media only screen and (min-width: 1170px) {
		.cd-timeline__img {
			width: 21px;
			height: 21px;
			left: 0;
			right: 0;
			margin-left: auto;
			margin-right: auto;
			top: 20px;
			margin-left: -30px;
			/* Force Hardware Acceleration */
			-webkit-transform: translateZ(0);
			transform: translateZ(0);
		}
	}

	.cd-timeline__content {
		position: relative;
		margin-left: 60px;
		background: #AED6F1;
		border-radius: 0.25em;
		padding: 1em;
		border-radius: 5pt;
		box-shadow: 0px 3px 25px 0px rgba(10, 55, 90, 0.2);
	}

	.cd-timeline__content:after {
		/* clearfix */
		content: "";
		display: table;
		clear: both;
	}

	.cd-timeline__content::before {
		/* triangle next to content block */
		content: '';
		position: absolute;
		top: 16px;
		right: 100%;
		height: 0;
		width: 0;
		border: 7px solid transparent;
		border-right: 7px solid white;
	}

	.cd-timeline__content h2 {
		color: #2c3e50;
		padding-bottom: 10px;
	}

	.cd-timeline__content p {
		color: #2c3e50;
	}

	.cd-timeline__content p,
	.cd-timeline__date {
		font-size: 1.3rem;
	}

	.cd-timeline__content p {
		margin: 1em 0;
		line-height: 1.6;
	}

	.cd-timeline__date {
		display: inline-block;
	}

	.cd-timeline__date {
		float: left;
		padding: .8em 0;
		opacity: .7;
	}

	.hora {
		font-size: 14px;
		color: #3c3c3c;
		font-weight: bolder;
	}

	@media only screen and (min-width: 768px) {
		.cd-timeline__content h2 {
			font-size: 18px;
			border-bottom: dashed 1px #2c3e50;
			margin-bottom: -10px;
		}

		.cd-timeline__content p {
			font-size: 1.6rem;
		}

		.cd-timeline__date {
			font-size: 1.4rem;
		}

		.cd-timeline__img {
			position: absolute;
			top: 12px;
			left: 0;
			right: 0;
			margin-left: auto;
			margin-right: auto;
			width: 18px;
			height: 18px;
			border-radius: 50%;
			-webkit-box-shadow: 0 0 0 4px white, inset 0 2px 0 rgba(0, 0, 0, 0.08), 0 3px 0 4px rgba(0, 0, 0, 0.05);
			box-shadow: 0 0 0 4px white, inset 0 2px 0 rgba(0, 0, 0, 0.08), 0 3px 0 4px rgba(0, 0, 0, 0.05);
		}
	}

	@media only screen and (min-width: 1170px) {
		.cd-timeline__content {
			margin-left: 0;
			padding: 1px 1em 1em 1em;
			width: 45%;
			/* Force Hardware Acceleration */
			-webkit-transform: translateZ(0);
			transform: translateZ(0);
		}

		.cd-timeline__img {
			position: absolute;
			top: 22px;
			left: 0;
			right: 0;
			margin-left: auto;
			margin-right: auto;
			width: 18px;
			height: 18px;
			border-radius: 50%;
			-webkit-box-shadow: 0 0 0 4px white, inset 0 2px 0 rgba(0, 0, 0, 0.08), 0 3px 0 4px rgba(0, 0, 0, 0.05);
			box-shadow: 0 0 0 4px white, inset 0 2px 0 rgba(0, 0, 0, 0.08), 0 3px 0 4px rgba(0, 0, 0, 0.05);
		}

		.cd-timeline__content::before {
			top: 24px;
			left: 100%;
			border-color: transparent;
			border-left-color: white;
		}

		.cd-timeline__date {
			position: absolute;
			width: 100%;
			left: 122%;
			top: 6px;
			font-size: 1.6rem;
		}

		.cd-timeline__block2:nth-child(n) .cd-timeline__content {
			float: right;
			background: #FFF;
			border: 1px #cecece;
		}

		.cd-timeline__block2:nth-child(n) .cd-timeline__content::before {
			top: 24px;
			left: auto;
			right: 100%;
			border-color: transparent;
			border-right-color: white;
		}

		.cd-timeline__block:nth-child(n) .cd-timeline__content::before {
			top: 24px;
			left: 100%;
			right: auto;
			border-color: transparent;
			border-bottom-color: #AED6F1;
			transform: rotate(90deg);
		}

		.cd-timeline__block2:nth-child(n) .cd-timeline__content h2 {
			color: #2c3e50;
			border-bottom-color: #2c3e50;
		}

		.cd-timeline__block2:nth-child(n) .cd-timeline__content p {
			color: #2c3e50;
		}

		.cd-timeline__block2:nth-child(n) .cd-timeline__date {
			left: auto;
			right: 122%;
			text-align: right;
			color: #7f8c97;
		}

		.cd-timeline__block:nth-child(n) .cd-timeline__date {
			color: #6bbfee;
		}
	}

	.collapse-chevron .fa {
		transition: .3s transform ease-in-out;
	}

	.collapse-chevron .collapsed .fa {
		transform: rotate(-90deg);
	}

	.collapse-plus .fas {
		transition: .2s transform ease-in-out;
	}

	.collapse-plus .collapsed .fas {
		transform: rotate(45deg);
	}

	.area {
		width: 100%;
		padding: 7px;
	}

	#dropZone {
		display: block;
		border: 2px dashed #bbb;
		-webkit-border-radius: 5px;
		border-radius: 5px;
		margin-left: -7px;
	}

	#dropZone p {
		font-size: 10pt;
		letter-spacing: -0.3pt;
		margin-bottom: 0px;
	}

	#dropzone .fa {
		font-size: 15pt;
	}

	.badge {
		display: table-cell;
		border-radius: 30px 30px 30px 30px;
		width: 26px;
		height: 26px;
		/*text-align: center;*/
		color: white;
		font-size: 11px;
		/*margin-right: auto;
    margin-left: auto;*/
	}

	.txtBadge {
		display: table-cell;
		vertical-align: middle;
	}

	.txtSideBadge {
		position: relative;
		display: table-cell;
	}

	.cd-timeline__block .jqte .jqte_editor {
		background: #AED6F1 !important;
	}

	.jqte {
		margin: 0 !important;
		border: none !important;
		box-shadow: none !important;
		-webkit-box-shadow: none !important;
	}

	.jqte_toolbar {
		visibility: hidden;
	}

	.jqte_editor,
	.jqte_source {
		resize: none !important;
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
				$abaInfoSuporte = 1462;
				include "abasInfoSuporteConsultor.php";
				?>

				<div class="push20"></div>

				<div class="login-form">

					<div class="col-md-7">

						<div class="row">

							<div class="col-md-12">

								<h5>Chamado #<?php echo $cod_chamado; ?></h5>

								<h4><?php echo $adm . " " . $qrSac['NOM_CHAMADO']; ?></h4>
							</div>

						</div>

						<div class="row">

							<div class="col-md-3">
								<h5>Tipo:</h5>
								<p class="label f14" style="background-color: <?php echo $qrSac['COR_TIPO'] ?> "> <span class="<?php echo $qrSac['ICO_TIPO']; ?>" style="color: #FFF;"></span>
									&nbsp;<?php echo $qrSac['DES_TPSOLICITACAO']; ?>
								</p>
							</div>

							<div class="col-md-3">
								<h5>Status:</h5>
								<p class="label f14" style="background-color: <?php echo $qrSac['COR_STATUS'] ?> "> <span class="<?php echo $qrSac['ICO_STATUS']; ?>" style="color: #FFF;"></span>
									&nbsp;<?php echo $qrSac['ABV_STATUS']; ?>
								</p>
							</div>

							<div class="col-md-3">
								<h5>Prioridade:</h5>
								<p class="label f14" style="background-color: <?php echo $qrSac['COR_PRIORIDADE'] ?> "> <span class="<?php echo $qrSac['ICO_PRIORIDADE']; ?>" style="color: #FFF;"></span>
									&nbsp;<?php echo $qrSac['DES_PRIORIDADE']; ?>
								</p>
							</div>

							<!-- <div class="col-md-3">
													<h5>Previsão <?= $tp_prazo ?>:</h5>
													<p><?php echo $dat_entrega; ?> </p>
												</div> -->

						</div>

						<div class="push10"></div>

						<div class="row">
							<div class="col-md-12">
								<h5><b>Descrição:</b></h5>
							</div>
							<div class="col-lg-12" style="max-height:300px; overflow-y: auto;">

								<?php echo html_entity_decode($des_sac); ?>

							</div>

						</div>

						<div class="push20"></div>

						<div class="row">

							<!-- <div class="col-md-3" style="margin-right: -43px;">
													<div class="collapse-chevron">
														<a data-toggle="collapse" class="collapsed btn btn-sm btn-default" href="#collapseFilter" style="width: 90%;">
													    	<span class="fa fa-chevron-down" aria-hidden="true"></span>&nbsp;
													    	Visualizar Anexos 
														</a>
													</div>
												</div> -->


							<div class="col-md-3">
								<div class="collapse-plus">
									<a data-toggle="collapse" class="collapsed btn btn-sm btn-success" href="#collapseFilter2" style="width: 90%;">
										<span class="fas fa-times" aria-hidden="true"></span>&nbsp;
										Criar Novo Anexo
									</a>
								</div>
							</div>


						</div>

						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

							<div class="row">

								<div class="col-md-6">

									<?php include "addAnexoSac.php"; ?>

								</div>

							</div>

							<div class="row">

								<div class="col-md-7">
									<?php include "listaUploadSac.php"; ?>
								</div>

							</div>

							<input type="hidden" name="COD_CHAMADO" id="COD_CHAMADO" value="<?php echo $cod_chamado; ?>">
							<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
							<input type="hidden" name="PRIMEIRO_UP" id="PRIMEIRO_UP" value="<?php echo $primeiroUp; ?>">
							<input type="hidden" name="COD_REFDOWN" id="COD_REFDOWN" value="<?php echo $conta; ?>">
							<input type="hidden" name="opcao" id="opcao" value="" />
							<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />

						</form>

					</div>

					<div class="col-md-1"></div>

					<div class="col-md-4" style="background: #F4F6F6; border-radius: 5px;">

						<div class="push20"></div>

						<div class="row">

							<div class="col-md-6">
								Empresa
								<div class="push10"></div>
								<b><?php echo  isset($qrNomEmp['NOM_FANTASI']) ? $qrNomEmp['NOM_FANTASI'] : null; ?></b> <small><?= $cod_empresa ?></small>
							</div>

							<div class="col-md-6">
								Responsável
								<div class="push10"></div>
								<b><?php echo $qrNomUsu['NOM_RESPONSAVEL']; ?></b>
							</div>

						</div>

						<div class="push20"></div>

						<div class="row">

							<div class="col-md-6">
								Solicitante
								<div class="push10"></div>
								<b><?php echo $qrNomUsu['NOM_SOLICITANTE']; ?></b>
							</div>

							<div class="col-md-6">
								Telefone
								<div class="push10"></div>
								<b><?= $num_telefone ?></b>
							</div>

						</div>

						<div class="push20"></div>

						<div class="row">

							<div class="col-md-12">
								Email
								<div class="push10"></div>
								<b><?= $des_email ?></b>
							</div>

						</div>

						<div class="push20"></div>

						<div class="row">

							<div class="col-md-6">
								Data do Chamado
								<div class="push10"></div>
								<b><?php echo $dat_chamado; ?></b>
							</div>

							<div class="col-md-6">
								Data Cadastro
								<div class="push10"></div>
								<b><?php echo $dat_cadastr; ?></b>
							</div>

						</div>

						<div class="push20"></div>

						<div class="row">

							<div class="col-md-12">
								Url
								<div class="push10"></div>
								<b><?php echo $url; ?></b>
							</div>

							<!-- <div class="col-md-6">
													Data Cadastro
													<div class="push10"></div>
													<b><?php echo $dat_cadastr; ?></b>
												</div> -->

						</div>

						<div class="push20"></div>

						<div class="row">

							<div class="col-md-6">
								Unidades Envolvidas

								<div class="form-group">
									<select data-placeholder="Não há" disabled name="COD_UNIVEND[]" id="COD_UNIVEND" multiple="multiple" class="chosen-select-deselect" style="width:80%;" tabindex="1">
										<?php
										$sql = "SELECT COD_UNIVEND, NOM_FANTASI FROM UNIDADEVENDA WHERE COD_EMPRESA = $cod_empresa ORDER BY NOM_FANTASI ";
										$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
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

							<div class="col-md-6">
								Usuários Envolvidos

								<div class="form-group">
									<select data-placeholder="Não há" disabled name="COD_USUARIOS_ENV[]" id="COD_USUARIOS_ENV" multiple="multiple" class="chosen-select-deselect requiredChk" style="width:80%;" tabindex="1" required>
										<?php

										$sql = "select COD_USUARIO, NOM_USUARIO from usuarios 
																where usuarios.COD_EMPRESA = $cod_empresa
																and usuarios.DAT_EXCLUSA is null order by  usuarios.NOM_USUARIO ";
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

						</div>

					</div>





					<div class="push10"></div>

					<div class="col-md-12">

						<div class="row">

							<div class="col-md-12">
								<h4>Descrição</h4>
								<div class="push10"></div>
								<a type="button" name="ADD" id="ADD" class="btn btn-success pull-left addBox" data-url="action.php?mod=<?php echo fnEncode(1461) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idC=<?php echo fnEncode($cod_chamado); ?>&idU=<?= fnEncode($conta) ?>&pop=true" data-title="Novo Comentário - Chamado #<?php echo $cod_chamado; ?>"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Criar Novo Comentário</a>
							</div>

						</div>

					</div>

					<div class="push10"></div>

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

					<input type="hidden" class="input-sm" name="REFRESH_COMENTARIO" id="REFRESH_COMENTARIO" value="N">

					<section class="cd-timeline">

						<div id="div_refreshComentario">
							<?php

							//setando locale da data
							setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
							date_default_timezone_set('America/Sao_Paulo');

							$sql = "SELECT SC.*, SS.ABV_STATUS FROM SAC_COMENTARIO SC
														LEFT JOIN SAC_STATUS SS ON SS.COD_STATUS = SC.COD_STATUS
														WHERE SC.COD_CHAMADO = $cod_chamado
														ORDER BY SC.DAT_CADASTRO DESC
														";

							//fnEscreve($sql);

							$arrayQueryComment = mysqli_query($connAdmSAC->connAdm(), $sql);

							while ($qrComment = mysqli_fetch_assoc($arrayQueryComment)) {
								$interno = "";
								//fnEscreve('entrou while');
								$mes = strtoupper(strftime('%B', strtotime($qrComment["DAT_CADASTRO"])));
								$mes = substr("$mes", 0, 3);

								$sqlUsuarios = "SELECT NOM_USUARIO FROM USUARIOS WHERE COD_USUARIO = $qrComment[COD_USUARIO]";
								//fnEscreve($sqlUsuarios);
								$qrNomUsu = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $sqlUsuarios));

								if ($qrComment['TP_COMENTARIO'] == 2) {
									$interno = " <span class='f12'> (INTERNO) </span>";
								}

							?>
								<div class="cd-timeline__container">
									<div class="cd-timeline__block<?php echo $qrComment['COD_COR']; ?>">
										<div class="cd-timeline__img"></div>
										<div class="cd-timeline__content">
											<h2><?= $qrNomUsu['NOM_USUARIO'] . $interno ?></h2>
											<div class="push5"></div>
											<textarea class="editor form-control input-sm"><?php echo $qrComment['DES_COMENTARIO']; ?></textarea>
											<span class="cd-timeline__date"><?php echo strftime('%d ', strtotime($qrComment["DAT_CADASTRO"])) . "" . $mes; ?>
												<br>
												<span class="hora"><?php echo date("H:i", strtotime($qrComment["DAT_CADASTRO"])); ?></span>
												<br>
												<span><small><b><?= $qrComment['ABV_STATUS'] ?></b></small></span>
											</span>
										</div>
									</div>
								</div>

							<?php } ?>
						</div>
					</section>

					<div class="push20"></div>

					<div class="push"></div>

				</div>
			</div>

		</div>
	</div>

	<!-- fim Portlet -->
</div>

<div class="push20"></div>

<link rel="stylesheet" type="text/css" href="js/plugins/jQuery-TE/jquery-te-1.4.0.css">
<link rel="stylesheet" type="text/css" href="js/plugins/jQuery-TE/jquery-te.png">
<script type="text/javascript" src="js/plugins/jQuery-TE/jquery-te-1.4.0.min.js"></script>


<script type="text/javascript">
	$(document).ready(function() {

		retornaForm();

		// TextArea
		$(".editor").jqte({
			sup: false,
			sub: false,
			outdent: false,
			indent: false,
			left: false,
			center: false,
			color: false,
			right: false,
			strike: false,
			source: false,
			link: false,
			unlink: false,
			remove: false,
			rule: false,
			fsize: false,
			format: false,
			b: false,
			i: false,
			u: false,
			ol: false,
			ul: false,
			toolbar: false
		});

		$(".jqte_editor").prop('contenteditable', 'false');

		//modal close
		$('#popModal').on('hidden.bs.modal', function() {

			if ($('#REFRESH_COMENTARIO').val() == "S") {
				//alert("atualiza");
				RefreshComentario("<?php echo fnEncode($cod_chamado); ?>", "<?php echo fnEncode($mod); ?>");
				$('#REFRESH_COMENTARIO').val("N");
			}

		});

		$('#EXC').click(function() {

			$('#opcao').val('EXC');

			$.confirm({
				title: 'Atenção!',
				animation: 'opacity',
				closeAnimation: 'opacity',
				content: 'Deseja realmente excluir este chamado?',
				buttons: {

					confirmar: function() {
						document.getElementById("formulario").submit();
					},
					cancelar: function() {


					},
				}
			});
			// $('#opcao').val('EXC');
			// alert($('#opcao').val());
		});

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


	function uploadFile(idField, typeFile) {
		var formData = new FormData();
		var nomeArquivo = $('#' + idField)[0].files[0]['name'];

		formData.append('arquivo', $('#' + idField)[0].files[0]);
		formData.append('diretorio', '../media/clientes/');
		formData.append('diretorioAdicional', 'helpdesk');
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
			url: '../uploads/uploaddocSac.php',
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

					//ajax da lista de arquivos upados

					$.ajax({
						type: "POST",
						url: "ajxSacAnexo.php",
						data: $('#formulario').serialize(),
						beforeSend: function() {
							$('#relatorioConteudo').html('<div class="loading" style="width: 100%;"></div>');
						},
						success: function(data) {
							console.log(data);
							$('#relatorioConteudo').html(data);
							$('#PRIMEIRO_UP').val("N");
						},
						error: function() {
							$('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Itens não encontrados...</p>');
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

	function RefreshComentario(idC, mod) {
		//alert('entrou ajax');
		$.ajax({
			type: "GET",
			url: "ajxRefreshComentario.php",
			data: {
				ajx1: idC,
				ajx2: mod
			},
			beforeSend: function() {
				$('#div_refreshComentario').html('<div class="loading" style="width: 100%;"></div>');
			},
			success: function(data) {
				$("#div_refreshComentario").html(data);
			},
			error: function() {
				$('#div_refreshComentario').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
			}
		});
	}

	function retornaForm() {
		var univend = '<?php echo $cod_univend; ?>';
		if (univend != 0 && univend != "") {
			//retorno combo multiplo - lojas
			$("#COD_UNIVEND").val('').trigger("chosen:updated");

			var sistemasUni = '<?php echo $cod_univend; ?>';
			var sistemasUniArr = sistemasUni.split(',');
			//opções multiplas
			for (var i = 0; i < sistemasUniArr.length; i++) {
				$("#COD_UNIVEND option[value=" + sistemasUniArr[i] + "]").prop("selected", "true");
			}
			$("#COD_UNIVEND").trigger("chosen:updated");
		}


		var usuarios_env = '<?php echo $cod_usuarios_env; ?>';
		if (usuarios_env != 0 && usuarios_env != "") {
			//retorno combo multiplo - USUARIOS_ENV
			$("#COD_USUARIOS_ENV").val('').trigger("chosen:updated");

			var sistemasUni = '<?php echo $cod_usuarios_env; ?>';
			var sistemasUniArr = sistemasUni.split(',');
			//opções multiplas
			for (var i = 0; i < sistemasUniArr.length; i++) {
				$("#COD_USUARIOS_ENV option[value=" + sistemasUniArr[i] + "]").prop("selected", "true");
			}
			$("#COD_USUARIOS_ENV").trigger("chosen:updated");
		}

		$('#formulario').validator('validate');
	}
</script>