<?php

echo fnDebug('true');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$dias30 = "";
$dat_ini = "";
$dat_fim = "";
$hoje = '';
$cod_univend = 0;

$hashLocal = mt_rand();
//fnMostraForm();
//inicialização de variáveis
//$hoje = fnFormatDate(date("Y-m-d"));
$hoje = fnFormatDate(date('Y-m-d', strtotime($hoje . '- 1 days')));
$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30 . '- 30 days')));
$dataLimite = date('Y-m-d', strtotime('+ 4 months'));

$conn = connTemp($cod_empresa, '');
$adm = $connAdm->connAdm();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_empresa = fnLimpaCampoZero(@$_POST['COD_EMPRESA']);
		$cod_univend = @$_REQUEST['COD_UNIVEND'];
		$cod_grupotr = @$_REQUEST['COD_GRUPOTR'];
		$cod_tiporeg = @$_REQUEST['COD_TIPOREG'];
		$cod_filtro = @$_REQUEST['COD_FILTRO'];
		$dat_ini = fnDataSql(@$_POST['DAT_INI']);
		$dat_fim = fnDataSql(@$_POST['DAT_FIM']);
		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		if ($opcao != '') {
		}
	}
}

//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode(@$_GET['id']);
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI, DAT_CADASTR FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($adm, $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
		$dat_cadastr = $qrBuscaEmpresa['DAT_CADASTR'];
	}
} else {
	$cod_empresa = 0;
	//fnEscreve('entrou else');
}

//inicialização das variáveis - default	
if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
	$dat_ini = fnDataSql($dias30);
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
	$dat_fim = fnDataSql($hoje);
}

//faz pesquisa por revenda (geral)
if ($cod_univend == "9999") {
	$temUnivend = "N";
} else {
	$temUnivend = "S";
}

//busca revendas do usuário
include "unidadesAutorizadas.php";

// Filtro por Grupo de Lojas
include "filtroGrupoLojas.php";


?>

<style type="text/css">
	ul.summary-list {
		display: inline-block;
		padding-left: 0;
		width: 100%;
		margin-bottom: 0;
	}

	ul.summary-list>li {
		display: inline-block;
		width: 19.5%;
		text-align: center;
	}

	ul.summary-list>li>a>i {
		display: block;
		font-size: 18px;
		padding-bottom: 5px;
	}

	ul.summary-list>li>a {
		padding: 10px 0;
		display: inline-block;
		color: #818181;
	}

	ul.summary-list>li {
		border-right: 1px solid #eaeaea;
	}

	ul.summary-list>li:last-child {
		border-right: none;
	}

	/* WIDGETS */
	.widget {
		width: 100%;
		float: left;
		margin: 0px;
		list-style: none;
		text-decoration: none;
		-moz-box-shadow: 0px 1px 1px 0px rgba(0, 0, 0, 0.2);
		-webkit-box-shadow: 0px 1px 1px 0px rgba(0, 0, 0, 0.2);
		box-shadow: 0px 1px 1px 0px rgba(0, 0, 0, 0.2);
		color: #FFF;
		-moz-border-radius: 5px;
		-webkit-border-radius: 5px;
		border-radius: 5px;
		padding: 15px 10px;
		margin-bottom: 20px;
		min-height: 120px;
		position: relative;
	}

	.widget.widget-padding-sm,
	.widget.widget-item-icon {
		padding: 10px 0px 5px;
	}

	.widget.widget-np {
		padding: 0px;
	}

	.widget.widget-no-subtitle {
		padding-top: 25px;
	}

	.widget.widget-carousel {
		padding-bottom: 0px;
		padding-top: 10px;
	}

	.widget.widget-default {
		background: #ffffff;
		background: -moz-linear-gradient(top, #ffffff 0%, #f5f5f5 100%);
		background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #ffffff), color-stop(100%, #f5f5f5));
		background: -webkit-linear-gradient(top, #ffffff 0%, #f5f5f5 100%);
		background: -o-linear-gradient(top, #ffffff 0%, #f5f5f5 100%);
		background: -ms-linear-gradient(top, #ffffff 0%, #f5f5f5 100%);
		background: linear-gradient(to bottom, #ffffff 0%, #f5f5f5 100%);
		filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=#ffffff, endColorstr=#f5f5f5, GradientType=0);
	}

	.widget.widget-primary {
		background: #33414e;
		background: -moz-linear-gradient(top, #33414e 0%, #29343f 100%);
		background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #33414e), color-stop(100%, #29343f));
		background: -webkit-linear-gradient(top, #33414e 0%, #29343f 100%);
		background: -o-linear-gradient(top, #33414e 0%, #29343f 100%);
		background: -ms-linear-gradient(top, #33414e 0%, #29343f 100%);
		background: linear-gradient(to bottom, #33414e 0%, #29343f 100%);
		filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=#33414e, endColorstr=#29343f, GradientType=0);
	}

	.widget.widget-success {
		background: #95b75d;
		background: -moz-linear-gradient(top, #95b75d 0%, #89ad4d 100%);
		background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #95b75d), color-stop(100%, #89ad4d));
		background: -webkit-linear-gradient(top, #95b75d 0%, #89ad4d 100%);
		background: -o-linear-gradient(top, #95b75d 0%, #89ad4d 100%);
		background: -ms-linear-gradient(top, #95b75d 0%, #89ad4d 100%);
		background: linear-gradient(to bottom, #95b75d 0%, #89ad4d 100%);
		filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=#95b75d, endColorstr=#89ad4d, GradientType=0);
	}

	.widget.widget-info {
		background: #3fbae4;
		background: -moz-linear-gradient(top, #3fbae4 0%, #29b2e1 100%);
		background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #3fbae4), color-stop(100%, #29b2e1));
		background: -webkit-linear-gradient(top, #3fbae4 0%, #29b2e1 100%);
		background: -o-linear-gradient(top, #3fbae4 0%, #29b2e1 100%);
		background: -ms-linear-gradient(top, #3fbae4 0%, #29b2e1 100%);
		background: linear-gradient(to bottom, #3fbae4 0%, #29b2e1 100%);
		filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=#3fbae4, endColorstr=#29b2e1, GradientType=0);
	}

	.widget.widget-warning {
		background: #fea223;
		background: -moz-linear-gradient(top, #fea223 0%, #fe970a 100%);
		background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #fea223), color-stop(100%, #fe970a));
		background: -webkit-linear-gradient(top, #fea223 0%, #fe970a 100%);
		background: -o-linear-gradient(top, #fea223 0%, #fe970a 100%);
		background: -ms-linear-gradient(top, #fea223 0%, #fe970a 100%);
		background: linear-gradient(to bottom, #fea223 0%, #fe970a 100%);
		filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=#fea223, endColorstr=#fe970a, GradientType=0);
	}

	.widget.widget-danger {
		background: #b64645;
		background: -moz-linear-gradient(top, #b64645 0%, #a43f3e 100%);
		background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #b64645), color-stop(100%, #a43f3e));
		background: -webkit-linear-gradient(top, #b64645 0%, #a43f3e 100%);
		background: -o-linear-gradient(top, #b64645 0%, #a43f3e 100%);
		background: -ms-linear-gradient(top, #b64645 0%, #a43f3e 100%);
		background: linear-gradient(to bottom, #b64645 0%, #a43f3e 100%);
		filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=#b64645, endColorstr=#a43f3e, GradientType=0);
	}

	.widget .widget-title,
	.widget .widget-subtitle,
	.widget .widget-int,
	.widget .widget-big-int {
		width: 100%;
		float: left;
		text-align: center;
	}

	.widget .widget-title {
		font-size: 16px;
		font-weight: 600;
		margin-bottom: 5px;
		line-height: 20px;
		text-transform: uppercase;
	}

	.widget .widget-subtitle {
		font-size: 12px;
		font-weight: 400;
		margin-bottom: 5px;
		line-height: 15px;
		color: #EEE;
	}

	.widget .widget-int {
		font-size: 32px;
		line-height: 40px;
		font-weight: bold;
		font-family: arial;
	}

	.widget .widget-big-int {
		font-size: 42px;
		line-height: 45px;
		font-weight: 300;
	}

	.widget .widget-item-left {
		margin-left: 10px;
		float: left;
		width: 100px;
	}

	.widget .widget-item-right {
		margin-right: 10px;
		float: right;
		width: 100px;
	}

	.widget.widget-item-icon .widget-item-left,
	.widget.widget-item-icon .widget-item-right {
		width: 70px;
		padding: 20px 0px;
		text-align: center;
	}

	.widget.widget-item-icon .widget-item-left {
		border-right: 1px solid rgba(0, 0, 0, 0.1);
		margin-right: 10px;
		padding-right: 10px;
	}

	.widget.widget-item-icon .widget-item-right {
		border-left: 1px solid rgba(0, 0, 0, 0.1);
		margin-left: 10px;
		padding-left: 10px;
	}

	.widget .widget-item-left .fa,
	.widget .widget-item-right .fa,
	.widget .widget-item-left .glyphicon,
	.widget .widget-item-right .glyphicon {
		font-size: 60px;
	}

	.widget .widget-data {
		padding-left: 120px;
	}

	.widget .widget-data-left {
		padding-right: 120px;
	}

	.widget.widget-item-icon .widget-data {
		padding-left: 90px;
	}

	.widget.widget-item-icon .widget-data-left {
		padding-right: 90px;
		padding-left: 10px;
	}

	.widget .widget-data .widget-title,
	.widget .widget-data-left .widget-title,
	.widget .widget-data .widget-subtitle,
	.widget .widget-data-left .widget-subtitle,
	.widget .widget-data .widget-int,
	.widget .widget-data-left .widget-int,
	.widget .widget-data .widget-big-int,
	.widget .widget-data-left .widget-big-int {
		text-align: left;
	}

	.widget .widget-controls a {
		position: absolute;
		width: 30px;
		height: 30px;
		text-align: center;
		line-height: 27px;
		color: #FFF;
		border: 1px solid #FFF;
		-moz-border-radius: 50%;
		-webkit-border-radius: 50%;
		border-radius: 50%;
		-webkit-transition: all 200ms ease;
		-moz-transition: all 200ms ease;
		-ms-transition: all 200ms ease;
		-o-transition: all 200ms ease;
		transition: all 200ms ease;
		opacity: 0.4;
		filter: alpha(opacity=40);
	}

	.widget .widget-controls a.widget-control-left {
		left: 10px;
		top: 10px;
	}

	.widget .widget-controls a.widget-control-right {
		right: 10px;
		top: 10px;
	}

	.widget .widget-controls a:hover {
		opacity: 1;
		filter: alpha(opacity=100);
	}

	.widget .widget-buttons {
		float: left;
		width: 100%;
		text-align: center;
		padding-top: 3px;
		margin-top: 5px;
		border-top: 1px solid rgba(0, 0, 0, 0.1);
	}

	.widget .widget-buttons a {
		position: relative;
		display: inline-block;
		line-height: 30px;
		font-size: 21px;
	}

	.widget .widget-buttons .col {
		width: 100%;
		float: left;
	}

	.widget .widget-buttons.widget-c2 .col {
		width: 50%;
	}

	.widget .widget-buttons.widget-c3 .col {
		width: 33.333333%;
	}

	.widget .widget-buttons.widget-c4 .col {
		width: 25%;
	}

	.widget .widget-buttons.widget-c5 .col {
		width: 20%;
	}

	.widget.widget-primary .widget-buttons a {
		color: #010101;
		border-color: #010101;
	}

	.widget.widget-primary .widget-buttons a:hover {
		color: #000000;
	}

	.widget.widget-success .widget-buttons a {
		color: #51672e;
		border-color: #51672e;
	}

	.widge.widget-success .widget-buttons a:hover {
		color: #435526;
	}

	.widget.widget-info .widget-buttons a {
		color: #14708f;
		border-color: #14708f;
	}

	.widget.widget-info .widget-buttons a:hover {
		color: #115f79;
	}

	.widget.widget-warning .widget-buttons a {
		color: #a15e01;
		border-color: #a15e01;
	}

	.widget.widget-warning .widget-buttons a:hover {
		color: #874f01;
	}

	.widget.widget-danger .widget-buttons a {
		color: #5a2222;
		border-color: #5a2222;
	}

	.widget.widget-danger .widget-buttons a:hover {
		color: #471b1b;
	}

	.plugin-clock span {
		-webkit-animation: pulsate 1s ease-out;
		-webkit-animation-iteration-count: infinite;
		-moz-animation: pulsate 1s ease-out;
		-moz-animation-iteration-count: infinite;
		animation: pulsate 1s ease-out;
		animation-iteration-count: infinite;
		opacity: 0.0;
		margin-right: 2px;
	}

	.widget.widget-default {
		color: #434a54;
	}

	.widget.widget-default .widget-subtitle {
		color: #434a54;
	}

	.widget.widget-default .widget-controls a {
		color: #434a54;
		border-color: #434a54;
	}

	.widget .widget-int {
		font-size: 21px;
	}

	.widget .widget-title {
		font-size: 12px;
	}

	@media only screen and (max-width: 1366px) {
		.widget .widget-int {
			font-size: 16px;
		}

		.widget .widget-title {
			font-size: 9px;
		}
	}
</style>

<div class="push30"></div>

<div class="row">

	<div class="col-md-12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fal fa-terminal "></i>
					<span class="text-primary"><?php echo $NomePg; ?> / <?= $nom_empresa ?></span>
				</div>

				<?php
				// include "backReport.php"; 
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

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Filtros</legend>

							<div class="row">

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Data Inicial</label>

										<div class="input-group date datePicker" id="DAT_INI_GRP">
											<input type='text' class="form-control input-sm data" name="DAT_INI" id="DAT_INI" value="<?php echo fnFormatDate($dat_ini); ?>" required />
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
										</div>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Data Final</label>

										<div class="input-group date datePicker" id="DAT_FIM_GRP">
											<input type='text' class="form-control input-sm data" name="DAT_FIM" id="DAT_FIM" value="<?php echo fnFormatDate($dat_fim); ?>" required />
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
										</div>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Unidade de Atendimento</label>
										<?php include "unidadesAutorizadasComboMulti.php"; ?>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Grupo de Lojas</label>
										<?php include "grupoLojasComboMulti.php"; ?>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Região</label>
										<?php include "grupoRegiaoMulti.php"; ?>
									</div>
								</div>

							</div>

							<div class="row">

								<div class="col-md-2">
									<div class="push20"></div>
									<button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-sm btn-block getBtn"><i class="fal fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
								</div>

							</div>

						</fieldset>

						<input type="hidden" name="NUM_ORDENAC" id="NUM_ORDENAC" value="">
						<input type="hidden" name="LOJAS" id="LOJAS" value="<?php echo $lojasSelecionadas; ?>" />
						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

						<div class="push5"></div>

					</form>


				</div>

			</div>
		</div>
		<!-- fim Portlet -->
	</div>

</div>

<div class="push20"></div>

<?php
$sqlConsulta =   "SELECT  
                    UV.NOM_FANTASI,
                    tmpcreditos.COD_UNIVEND,
                    tmpcreditos.COD_EMPRESA,
                    sum(val_credito) val_credito,
                    sum(val_resgate) val_resgate,
                    val_a_expirar,
                    val_expirado,
                    count(distinct qtd_clientes_resgate) qtd_clientes_resgate,
                    sum(vl_vinculado) vl_vinculado,
                    sum(val_saldo_total) val_saldo_total,
                    ifnull(sum(val_credito) / sum(vl_vinculado) * 100,0) cada_um_investido,
                    qtd_cliente_credito_expirado
                    FROM (
                SELECT 
                  '' NOM_FANTASI, 
                   a.COD_UNIVEND,
                   a.COD_EMPRESA,
                   CASE WHEN a.tip_credito = 'C'  
                   and   a.cod_statuscred IN(1, 2, 3, 4, 5, 7, 8, 9, 10)  
                   THEN a.val_credito  ELSE  '0.00' END  val_credito, 
                   CASE WHEN a.tip_credito = 'D' 
                   AND a.cod_statuscred IN(1, 2, 3, 4, 5, 7, 8, 9, 10) 
                   THEN a.val_credito  ELSE '0.00' END val_resgate, 
                    (SELECT Sum(val_saldo) 
				   FROM   creditosdebitos AA
				   INNER JOIN clientes c ON c.cod_cliente=AA.cod_cliente
				   WHERE date(AA.dat_expira) >= curdate() 
				   AND AA.log_expira = 'S' 
				   AND AA.cod_statuscred IN (0,1,2,5,7,8,9) 
				   AND AA.cod_empresa = $cod_empresa
				   AND c.COD_UNIVEND IN($lojasSelecionadas)) val_a_expirar,
                                       


                   case when  date(a.dat_expira) >= '$dat_ini' AND
                   a.cod_statuscred = '1' and
                   a.tip_credito = 'C'  AND 
                   a.val_saldo > 0  then  a.val_saldo ELSE '0.00' END val_saldo_total,	       
                   (SELECT Sum(val_saldo) 
				   FROM   creditosdebitos AA
				   INNER JOIN clientes c ON c.cod_cliente=AA.cod_cliente
				   WHERE date(AA.dat_expira) BETWEEN '$dat_ini' AND '$dat_fim'  
				   AND AA.log_expira = 'S' 
				   AND AA.cod_statuscred IN (4) 
				   AND AA.cod_empresa = $cod_empresa
				   AND c.COD_UNIVEND IN($lojasSelecionadas)) val_expirado,
                            
                   CASE WHEN a.tip_credito = 'D' and  a.cod_statuscred IN(1, 2, 3, 4, 5, 7, 8, 9, 10) THEN a.cod_cliente ELSE null END  qtd_clientes_resgate, 
                   CASE WHEN a.tip_credito = 'D' AND  a.cod_statuscred IN(1, 2, 3, 4, 5, 7, 8, 9, 10) THEN a.val_vinculado ELSE '0.00' END  vl_vinculado, 
                   '0.00' cada_um_investido,
                   (SELECT COUNT(DISTINCT a.COD_CLIENTE) FROM  creditosdebitos a
                       INNER JOIN clientes c ON c.cod_cliente = a.cod_cliente 
	                     WHERE a.cod_statuscred IN(4)  AND 
			     DATE(a.DAT_EXPIRA) BETWEEN '$dat_ini' AND '$dat_fim' AND 
			     a.tip_credito = 'C' AND 
			     a.COD_EMPRESA=$cod_empresa   AND
			     c.COD_UNIVEND IN($lojasSelecionadas)
                    )  qtd_cliente_credito_expirado 

                   FROM creditosdebitos a
				   INNER JOIN clientes c ON c.cod_cliente=a.cod_cliente				   
                   WHERE 	
                   a.COD_EMPRESA=$cod_empresa and
                   date(a.dat_reproce) BETWEEN '$dat_ini'  AND '$dat_fim'
                   AND a.COD_UNIVEND IN($lojasSelecionadas)
                   )tmpcreditos
                   LEFT JOIN UNIDADEVENDA UV ON UV.COD_UNIVEND = tmpcreditos.COD_UNIVEND 
               ";
//fnEscreve($sqlConsulta);

$arrayConsulta = mysqli_query($conn, $sqlConsulta);
$qrConsulta = mysqli_fetch_assoc($arrayConsulta);

$val_credito = $qrConsulta['val_credito'];
$val_resgate = $qrConsulta['val_resgate'];
$val_a_expirar = $qrConsulta['val_a_expirar'];
$val_total = $qrConsulta['val_saldo_total'];
$val_expirado = $qrConsulta['val_expirado'];
$val_vinculado = $qrConsulta['vl_vinculado'];
$qtd_clientes_resgate = $qrConsulta['qtd_clientes_resgate'];
$qtd_cliente_credito_expirado = $qrConsulta['qtd_cliente_credito_expirado'];
// $cada_um_investido = $qrConsulta[cada_um_investido];
$cada_um_investido = ($val_resgate != 0) ?  ($val_vinculado - $val_resgate) / $val_resgate : 0;

?>
<div class="row">

	<div class="col-md-12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">

			<div class="portlet-body">

				<div class="row">

					<div class="col-md-4 col-md-offset-4 text-center">

						<h4>Visão Geral de Créditos Concedidos x Resgatados no <b>Período</b></h4>

					</div>

				</div>

				<div class="push20"></div>

				<div class="flexrow">
					<div class="col">

						<!-- START WIDGET MESSAGES -->
						<div class="widget widget-default widget-item-icon">
							<div class="widget-item-left">
								<span class="fal fa-sack-dollar text-info"></span>
							</div>
							<div class="widget-data">
								<div class="widget-int num-count"><small>R$</small> <?= fnValor($val_total, 2) ?></div>
								<div class="widget-title">Saldo total</div>
								<div class="widget-subtitle">disponível para resgate</div>
							</div>
						</div>
						<!-- END WIDGET MESSAGES -->

					</div>
					<div class="col">

						<!-- START WIDGET MESSAGES -->
						<div class="widget widget-default widget-item-icon">
							<div class="widget-item-left">
								<span class="fal fa-money-check-edit-alt"></span>
							</div>
							<div class="widget-data">
								<div class="widget-int num-count"><small>R$</small> <?= fnValor($val_credito, 2) ?></div>
								<div class="widget-title">Gerados</div>
								<div class="widget-subtitle">bônus incluídos</div>
							</div>
						</div>
						<!-- END WIDGET MESSAGES -->

					</div>

					<div class="col">

						<!-- START WIDGET MESSAGES -->
						<div class="widget widget-default widget-item-icon">
							<div class="widget-item-left">
								<span class="fal fa-hourglass-half text-danger"></span>
							</div>
							<div class="widget-data">
								<div class="widget-int num-count"><small>R$</small> <?= fnValor($val_a_expirar, 2) ?></div>
								<div class="widget-title">Saldo à expirar a partir de <b><?php echo date('d/m/Y') ?></b></div>
								<!-- <div class="widget-subtitle">In your mailbox</div> -->
							</div>
						</div>
						<!-- END WIDGET MESSAGES -->

					</div>

				</div>

				<div class="flexrow">


					<div class="col">

						<!-- START WIDGET MESSAGES -->
						<div class="widget widget-default widget-item-icon">
							<div class="widget-item-left">
								<span class="fal fa-funnel-dollar text-info"></span>
							</div>
							<div class="widget-data">
								<div class="widget-int num-count"><small>R$</small> <?= fnValor($val_resgate, 2) ?></div>
								<div class="widget-title">Resgatados</div>
								<!-- <div class="widget-subtitle">In your mailbox</div> -->
							</div>
						</div>
						<!-- END WIDGET MESSAGES -->

					</div>

					<div class="col">

						<!-- START WIDGET MESSAGES -->
						<div class="widget widget-default widget-item-icon">
							<div class="widget-item-left">
								<span class="fal fa-user-plus text-info"></span>
							</div>
							<div class="widget-data">
								<div class="widget-int num-count"><?= fnValor($qtd_clientes_resgate, 0) ?></div>
								<div class="widget-title">clientes que resgataram</div>
								<!-- <div class="widget-subtitle">In your mailbox</div> -->
							</div>
						</div>
						<!-- END WIDGET MESSAGES -->

					</div>

					<div class="col">

						<!-- START WIDGET MESSAGES -->
						<div class="widget widget-default widget-item-icon">
							<div class="widget-item-left">
								<span class="fal fa-chart-line edit-alt"></span>
							</div>
							<div class="widget-data">
								<div class="widget-int num-count"><small>R$</small> <?= fnValor($val_vinculado, 2) ?></div>
								<div class="widget-title">VVR</div>
								<!-- <div class="widget-subtitle">In your mailbox</div> -->
							</div>
						</div>
						<!-- END WIDGET MESSAGES -->

					</div>

					<div class="col">

						<!-- START WIDGET MESSAGES -->
						<div class="widget widget-default widget-item-icon">
							<div class="widget-item-left">
								<span class="fal fa-calendar-minus text-danger"></span>
							</div>
							<div class="widget-data">
								<div class="widget-int num-count"><small>R$</small> <?= fnValor($val_expirado, 2) ?></div>
								<div class="widget-title">Expirados</div>
								<div class="widget-subtitle">bônus incluídos</div>
							</div>
						</div>
						<!-- END WIDGET MESSAGES -->

					</div>

					<div class="col">

						<!-- START WIDGET MESSAGES -->
						<div class="widget widget-default widget-item-icon">
							<div class="widget-item-left">
								<span class="fal fa-user-minus text-danger"></span>
							</div>
							<div class="widget-data">
								<div class="widget-int num-count"><?= fnValor($qtd_cliente_credito_expirado, 0) ?></div>
								<div class="widget-title">Clientes com créditos expirados</div>
								<!-- <div class="widget-subtitle">In your mailbox</div> -->
							</div>
						</div>
						<!-- END WIDGET MESSAGES -->

					</div>


				</div>

				<div class="row">

					<div class="col-md-4 col-md-offset-4 text-center">
						<p style="font-size: 11px;">A cada <b><small>R$</small> 1,00</b> investido em resgate, o cliente comprou</p>
						<h4 class="text-success"><b>R$<?= fnValor($cada_um_investido, 2) ?></b></h4>
					</div>

				</div>

			</div>

		</div>

	</div>

</div>

<div class="push20"></div>

<div class="row">

	<div class="col-md-12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">

			<div class="portlet-body">

				<div class="row">

					<div class="col-md-12">

						<div class="no-more-tables">

							<form name="formLista">

								<table class="table table-bordered table-striped table-hover tableSorter">
									<thead>
										<tr>
											<th>Loja</th>
											<th>Créditos</th>
											<th>Resgates</th>
											<th>VVR</th>
											<th>Cli. Resgates</th>
											<th>Expirados</th>
											<th>Cli. Expirados</th>
											<th>A cada R$1</th>
										</tr>
									</thead>
									<tbody>

										<?php

										$sqlConsulta = "SELECT  
										                    UV.NOM_FANTASI,
										                    tmpcreditos.COD_UNIVEND,
										                    tmpcreditos.COD_EMPRESA,
										                    sum(val_credito) val_credito,
										                    sum(val_resgate) val_resgate,
										                    sum(val_a_expirar) val_a_expirar,
										                    count(distinct qtd_clientes_resgate) qtd_clientes_resgate,
										                    sum(vl_vinculado) vl_vinculado,
										                    sum(val_saldo_total) val_saldo_total,
										                    ifnull(sum(val_credito) / sum(vl_vinculado) * 100,0) cada_um_investido
										                    FROM (
												                SELECT 
												                  '' NOM_FANTASI, 
												                   a.COD_UNIVEND,
												                   a.COD_EMPRESA,
												                   CASE WHEN a.tip_credito = 'C'  
												                   and   a.cod_statuscred IN(1, 2, 3, 4, 5, 7, 8, 9, 10)  
												                   THEN a.val_credito  ELSE  '0.00' END  val_credito, 
												                   CASE WHEN a.tip_credito = 'D' 
												                   AND a.cod_statuscred IN(1, 2, 3, 4, 5, 7, 8, 9, 10) 
												                   THEN a.val_credito  ELSE '0.00' END val_resgate, 
												                   case when  date(a.dat_expira) BETWEEN '$dat_ini' AND '$dat_fim' and
												                   a.cod_statuscred = '1' and
												                   a.tip_credito = 'C'  AND 
												                   a.val_saldo > 0  then  a.val_saldo ELSE '0.00' END val_a_expirar,
												                   case when  date(a.dat_expira) >= '$dat_ini' AND
												                   a.cod_statuscred = '1' and
												                   a.tip_credito = 'C'  AND 
												                   a.val_saldo > 0  then  a.val_saldo ELSE '0.00' END val_saldo_total,
                                                                   CASE WHEN a.tip_credito = 'D' and  a.cod_statuscred IN(1, 2, 3, 4, 5, 7, 8, 9, 10) THEN a.cod_cliente ELSE null END  qtd_clientes_resgate, 
												                   CASE WHEN a.tip_credito = 'D' AND  a.cod_statuscred IN(1, 2, 3, 4, 5, 7, 8, 9, 10) THEN a.val_vinculado ELSE '0.00' END  vl_vinculado, 
												                   '0.00' cada_um_investido

												                   FROM creditosdebitos a 
												                   WHERE 	
												                   a.COD_EMPRESA=$cod_empresa and
												                   date(a.dat_reproce) BETWEEN '$dat_ini'  AND '$dat_fim'
												                   AND a.COD_UNIVEND IN($lojasSelecionadas)
												                   )tmpcreditos
											                   INNER JOIN UNIDADEVENDA UV ON UV.COD_UNIVEND = tmpcreditos.COD_UNIVEND 
											                    GROUP BY tmpcreditos.COD_UNIVEND
											                    ORDER BY UV.NOM_FANTASI ASC";

										// fnEscreve($sqlConsulta);

										$arrayConsulta = mysqli_query($conn, $sqlConsulta);

										$count = 0;

										$sqlRegistro = "SELECT c.COD_UNIVEND, COUNT(DISTINCT a.COD_CLIENTE) qtd_cliente_credito_expirado 
											FROM  creditosdebitos a
											INNER JOIN clientes c ON c.cod_cliente = a.cod_cliente 
											WHERE a.cod_statuscred IN(4)  AND 
											DATE(a.DAT_EXPIRA) BETWEEN '$dat_ini' AND '$dat_fim' AND 
											a.tip_credito = 'C' AND 
											a.COD_EMPRESA=$cod_empresa   AND
											c.COD_UNIVEND IN ($lojasSelecionadas)
											GROUP BY c.COD_UNIVEND";

										$queryRegistro = mysqli_query($conn, $sqlRegistro);
										$resultado = mysqli_fetch_all($queryRegistro, MYSQLI_ASSOC);

										$sqlExpira = "SELECT c.COD_UNIVEND, Sum(val_saldo) val_expirado
											                FROM   creditosdebitos AA
											                INNER JOIN clientes c ON c.cod_cliente=AA.cod_cliente
											                WHERE date(AA.dat_expira) BETWEEN '$dat_ini' AND '$dat_fim'  
											                AND AA.log_expira = 'S' 
											                AND AA.cod_statuscred IN (4) 
											                AND AA.cod_empresa = $cod_empresa
											                AND c.COD_UNIVEND IN ($lojasSelecionadas)
															GROUP BY c.COD_UNIVEND";

										$queryExpira = mysqli_query($conn, $sqlExpira);
										$resultadoExpira = mysqli_fetch_all($queryExpira, MYSQLI_ASSOC);

										while ($qrConsulta = mysqli_fetch_assoc($arrayConsulta)) {
											$count++;

											foreach ($resultado as $res) {
												if ($res['COD_UNIVEND'] == $qrConsulta['COD_UNIVEND']) {
													// Se encontrar, adicionar o campo qtd_cliente_credito_expirado na consulta
													$qrConsulta[$count]['qtd_cliente_credito_expirado'] = $res['qtd_cliente_credito_expirado'];
													// $qtd_cliente = $res['qtd_cliente_credito_expirado'];
												}
											}

											foreach ($resultadoExpira as $exp) {
												if ($exp['COD_UNIVEND'] == $qrConsulta['COD_UNIVEND']) {
													// Se encontrar, adicionar o campo qtd_cliente_credito_expirado na consulta
													$qrConsulta[$count]['val_expirado'] = $exp['val_expirado'];
													// $qtd_cliente = $res['qtd_cliente_credito_expirado'];
												}
											}


											// $sqlSaldoExp = "SELECT Sum(val_saldo) val_expirado
											//                 FROM   creditosdebitos AA
											//                 INNER JOIN clientes c ON c.cod_cliente=AA.cod_cliente
											//                 WHERE date(AA.dat_expira) BETWEEN '$dat_ini' AND '$dat_fim'  
											//                 AND AA.log_expira = 'S' 
											//                 AND AA.cod_statuscred IN (4) 
											//                 AND AA.cod_empresa = $cod_empresa
											//                 AND c.COD_UNIVEND = $qrConsulta[COD_UNIVEND]";

											// $arraySaldoExp = mysqli_query($conn, $sqlSaldoExp);
											// $qrSaldoExp = mysqli_fetch_assoc($arraySaldoExp);

											// $sqlCliExp = "SELECT COUNT(DISTINCT a.COD_CLIENTE) qtd_cliente_credito_expirado 
											// 			  FROM  creditosdebitos a
											//               INNER JOIN clientes c ON c.cod_cliente = a.cod_cliente 
											//               WHERE a.cod_statuscred IN(4)  AND 
											//               DATE(a.DAT_EXPIRA) BETWEEN '$dat_ini' AND '$dat_fim' AND 
											//               a.tip_credito = 'C' AND 
											//               a.COD_EMPRESA=$cod_empresa   AND
											//               c.COD_UNIVEND = $qrConsulta[COD_UNIVEND]";

											// $arrayCliExp = mysqli_query($conn, $sqlCliExp);
											// $qrCliExp = mysqli_fetch_assoc($arrayCliExp);

											$cada_um_investido = $qrConsulta['val_resgate'] != 0 ?  ($qrConsulta['vl_vinculado'] - $qrConsulta['val_resgate']) / $qrConsulta['val_resgate'] : 0;
											echo "
										<tr>
										  <td><small>" . $qrConsulta['NOM_FANTASI'] . "</small></td>
										  <td><small>R$" . fnValor($qrConsulta['val_credito'], 2) . "</small></td>
										  <td><small>R$" . fnValor($qrConsulta['val_resgate'], 2) . "</small></td>
										  <td><small>R$" . fnValor($qrConsulta['vl_vinculado'], 2) . "</small></td>
										  <td><small>" . fnValor($qrConsulta['qtd_clientes_resgate'], 0) . "</small></td>
										  <td><small>R$" . fnValor($qrConsulta[$count]['val_expirado'], 2) . "</small></td>
										  <td><small>" . fnValor($qrConsulta[$count]['qtd_cliente_credito_expirado'], 0) . "</small></td>
										  <td><small>R$" . fnValor($cada_um_investido, 2) . "</small></td>
										</tr>
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
									</tfoot>
								</table>

							</form>

						</div>

					</div>

				</div>

			</div>

		</div>

	</div>

</div>

<div class="push20"></div>


<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.min.js"></script>
<script src="js/plugins/Chart_Js/utils.js"></script>

<script src="js/plugins/ion.rangeSlider.js"></script>

<script>
	//datas
	$(function() {

		$('.datePicker').datetimepicker({
			format: 'DD/MM/YYYY',
			maxDate: "<?= $dataLimite ?> ",
		}).on('changeDate', function(e) {
			$(this).datetimepicker('hide');
		});

		$("#DAT_INI_GRP").on("dp.change", function(e) {
			$('#DAT_FIM_GRP').data("DateTimePicker").minDate(e.date);
		});

		$("#DAT_FIM_GRP").on("dp.change", function(e) {
			$('#DAT_INI_GRP').data("DateTimePicker").maxDate(e.date);
		});
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
										url: "relatorios/ajxdashConcedTroca.do?opcao=exportar&nomeRel=" + nome + "&id=<?php echo fnEncode($cod_empresa); ?>",
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

	//graficos
	$(document).ready(function() {


		//chosen
		$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
		$('#formulario').validator();






	});
</script>