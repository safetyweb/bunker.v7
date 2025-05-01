<?php
include "../_system/_functionsMain.php";
include_once './funWS/atualizacadastro.php';
include_once './funWS/TKT.php';

 header("Access-Control-Allow-Origin: *");

//echo "<h1>".$_GET['param']."</h1>";
//echo fnDebug('true');
//fnEscreve("totem");

$dias30="";
$dat_ini="";
$dat_fim="";

//inicialização de variáveis
$hoje = fnFormatDate(date("Y-m-d"));
//$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 1 days')));
$dias30 = fnFormatDate(date("Y-m-d"));

$dat_ini = fnDataSql($dias30); 
$dat_fim = fnDataSql($hoje);

	//dados da url
	$parametros = fnDecode($_GET['key']);
	$arrayCampos = explode(";", $parametros);
	$cod_empresa = $arrayCampos[4];
	$cod_players = $arrayCampos[7];

        if($cod_empresa==7)
        {    
        //fnMostraForm();
        }
	$tktOff = "";

	$hashLocal = mt_rand();	
	
	if( $_SERVER['REQUEST_METHOD']=='POST' )
	{
		$request = md5( implode( $_POST ) );
		
		if( isset( $_SESSION['last_request'] ) && $_SESSION['last_request']== $request )
		{
			$msgRetorno = 'Essa página já foi utilizada';
			$msgTipo = 'alert-warning';
		}
		else
		{
			//$_SESSION['last_request']  = $request;
			
			$c1 = fnLimpacampoZero(fnLimpaDoc($_REQUEST['c1']));

			if($_REQUEST['c10'] && $_REQUEST['c10'] != ""){
				$c10 = $_REQUEST['c10'];
			}else{
				$c10 = fnLimpaDoc($_REQUEST['cpf']);
			}
			//$cod_orcamento = fnLimpacampo($_REQUEST['COD_ORCAMENTO']);
                    

			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
							
 
                                              
			if ($opcao != ''){
				
					//mensagem de retorno
					switch ($opcao)
					{
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


$sql = "SELECT * FROM SITE_EXTRATO WHERE COD_EMPRESA = $cod_empresa";
//fnEscreve($sql);
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql) or die(mysqli_error());
$qrBuscaSiteExtrato = mysqli_fetch_assoc($arrayQuery);

if (isset($arrayQuery)) {
	//fnEscreve("entrou if");
	$cod_extrato = $qrBuscaSiteExtrato['COD_EXTRATO'];
	$des_dominio = $qrBuscaSiteExtrato['DES_DOMINIO'];

}


//busca dados do layout
$sql = "SELECT * FROM TOTEM WHERE COD_EMPRESA = $cod_empresa ";
//fnEscreve($sql);
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql) or die(mysqli_error());
$qrBuscaSiteTotem = mysqli_fetch_assoc($arrayQuery);

if (isset($qrBuscaSiteTotem)) {
    //fnEscreve("entrou if");

    $cod_totem = $qrBuscaSiteTotem['COD_TOTEM'];
    $des_logo = $qrBuscaSiteTotem['DES_LOGO'];
    $des_alinham = $qrBuscaSiteTotem['DES_ALINHAM'];
    $des_imgback = $qrBuscaSiteTotem['DES_IMGBACK'];
	$des_imgback_mob = $qrBuscaSiteTotem['DES_IMGBACK_MOB'];
	if($des_imgback_mob == ""){
		$des_imgback_mob = $des_imgback;
	}
    $cod_layout = $qrBuscaSiteTotem['COD_LAYOUT'];

    if ($qrBuscaSiteTotem['LOG_CORPERS'] == "N") {
        $check_CORPERS = '';
    } else {
        $check_CORPERS = "checked";
    }
	
	$log_ticket = $qrBuscaSiteTotem['LOG_TICKET'];

    $cor_backbar = $qrBuscaSiteTotem['COR_BACKBAR'];
    $cor_backpag = $qrBuscaSiteTotem['COR_BACKPAG'];
    $cor_titulos = $qrBuscaSiteTotem['COR_TITULOS'];
    $cor_textos = $qrBuscaSiteTotem['COR_TEXTOS'];
    $cor_botao = $qrBuscaSiteTotem['COR_BOTAO'];
    $cor_botaoon = $qrBuscaSiteTotem['COR_BOTAOON'];

    $des_paghome = $qrBuscaSiteTotem['DES_PAGHOME'];
	if ($des_paghome == "index"){$destinoHome = "";}
	else {$destinoHome = "banner.do";}
	
    $val_inativo = $qrBuscaSiteTotem['VAL_INATIVO'];

    if ($qrBuscaSiteTotem['LOG_CORPERS'] == "N") {
        $check_CORPERS = '';
    } else {
        $check_CORPERS = "checked";
    }

	
	//fnMostraForm();
	//fnEscreve($c1);
	//fnEscreve($tip_contabil);	
}

$sqlPlayer = "SELECT * FROM TOTEM_PLAYERS WHERE COD_EMPRESA = $cod_empresa AND COD_PLAYERS = $cod_players";
$arrayQueryPlayer = mysqli_query(connTemp($cod_empresa, ''), $sqlPlayer);
$qrBuscaTotemPlayer = mysqli_fetch_assoc($arrayQueryPlayer);

if (isset($qrBuscaTotemPlayer)) {

	$log_ticket = $qrBuscaTotemPlayer['LOG_TICKET'];
	$log_nps = $qrBuscaTotemPlayer['LOG_NPS'];
    $val_inativo = $qrBuscaTotemPlayer['VAL_INATIVO'];
    $des_paghome = $qrBuscaTotemPlayer['DES_PAGHOME'];

	if($des_paghome == "index"){
		$destinoHome = "";
	}else if($des_paghome == "nps"){
		$destinoHome = "pesquisa.do";
	}else if($des_paghome == "cad"){
		$destinoHome = "consulta_V2.do";
	}else{
		$destinoHome = "banner.do";
	}

}
	
	//busca cliente
	if(isset($_GET['idc'])){
		$cod_cliente = fnLimpaCampoZero(fnDecode($_GET['idc']));
	}else{
		$sqlCliente = "select COD_CLIENTE from clientes where COD_EMPRESA = $cod_empresa and NUM_CGCECPF = ".$dadosatualiza['cpf']." ";
		$qrBuscaCliente =  mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sqlCliente));
		$cod_cliente = $qrBuscaCliente['COD_CLIENTE'];
	}

	// busca info empresa
	$sqlEmp = "SELECT TIP_RETORNO, NUM_DECIMAIS FROM EMPRESAS WHERE COD_EMPRESA = $cod_empresa";
	$qrEmp = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(),$sqlEmp));

	if($qrEmp['TIP_RETORNO'] == 1){
		$casasDec = 0;
	}else{
		$casasDec = $qrEmp['NUM_DECIMAIS'];
	}

	// //busca saldo do cliente
	// $sqlSaldo = "CALL SP_CONSULTA_SALDO_CLIENTE($cod_cliente);";
 //    $row = mysqli_query(connTemp($cod_empresa,''),$sqlSaldo);
	// $qrBuscaSaldo = mysqli_fetch_assoc($row);
 //    // fnEscreveArray($qrBuscaSaldo);
	// $credito_disponivel = fnValor($qrBuscaSaldo['CREDITO_DISPONIVEL'],$casasDec);
	// $credito_aliberar = fnValor(($qrBuscaSaldo['TOTAL_CREDITO']-$qrBuscaSaldo['CREDITO_DISPONIVEL']),$casasDec);
	// $saldototal = fnValor($qrBuscaSaldo['TOTAL_CREDITO'],$casasDec);  

	
	//fnMostraForm();
	//fnEscreve($c1);
	//fnEscreve($tip_contabil);

	// busca ticket
	// $sqlTkt = "SELECT COUNT(COD_PRODTKT) AS NRO_PRODUTOS FROM PRODUTOTKT 
	// WHERE COD_EMPRESA = $cod_empresa
	// AND	DAT_INIPTKT <= '$dat_ini 00:00:00' 
	// AND	DAT_FIMPTKT >= '$dat_fim 23:59:59'";
	// $arrayQueryTkt = mysqli_query(connTemp($cod_empresa,''),$sqlTkt);
	// $qrBuscaTkt = mysqli_fetch_assoc($arrayQueryTkt);
	//fnEscreve($sqlTkt);
	
?>
	

<html lang="pt">
    <head>
	
		
	<meta name="viewport" content="width=device-width,height=device-height,initial-scale=1.0"/>
	
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=9"/>
	<meta http-equiv="X-UA-Compatible" content="IE=10"/>
	<meta http-equiv="X-UA-Compatible" content="IE=11"/>

	<title>Totem</title>
		
	<link href="http://bunker.mk/css/bootstrap.flatly.min.css" rel="stylesheet">
	<script src="http://bunker.mk/js/jquery.min.js"></script>
	
	<!-- JQUERY-CONFIRM -->
	<link href="http://bunker.mk/css/jquery-confirm.min.css" rel="stylesheet"/>
	
	<!-- extras -->
	<link href="http://bunker.mk/css/jquery.webui-popover.min.css" rel="stylesheet" />
	<link href="http://bunker.mk/css/chosen-bootstrap.css" rel="stylesheet" />
	<link href="http://bunker.mk/css/font-awesome.min.css" rel="stylesheet" />
	
    <!-- complement -->
	<link href="http://bunker.mk/css/default.css" rel="stylesheet" />
	<link href="http://bunker.mk/css/checkMaster.css" rel="stylesheet" />
		
	<!--[if lt IE 9]><script src="js/ie8-responsive-file-warning.js"></script><![endif]
	<script src="http://bunker.mk/js/plugins/ie-emulation-modes-warning.js"></script>-->
	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
	  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

	<!-- Favicons -->
	<link rel="icon" type="image/ico" rel="shortcut icon" href="images/favicon.ico"/>

	<?php if($check_CORPERS == "checked"){ include "customCss.php"; } ?>
	
	
	<style>
	
	.input-lg {
		font-size: 28px;
		line-height: 1.5;
	}
	body { 
	  background:#<?php echo $cor_backpag; ?> url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback; ?>') no-repeat center center fixed; 
	  -webkit-background-size: cover;
	  -moz-background-size: cover;
	  -o-background-size: cover;
	  background-size: cover;
	}
	/*iframe {
	 pointer-events: none;
	}*/	
	.navbar img{
		width: 360px;
		margin-top: -15px;
	}
	
	.logo-center {
		float: none;
	}
	
	.logo-center-page {
		float: none;
		width: 160px;
	}	
	
	.logo-left {
		float: left;
	}

	.logo-right {
		float: right;
	}	
	
	<?php if ($cor_backbar == "") { ?>
	.navbar {
	   background-color: transparent;
	   background: transparent;
	   border-color: transparent;
	}
	<?php } else { ?>
	.navbar {
	   background-color: #<?php echo $cor_backbar; ?>;
	   background: #<?php echo $cor_backbar; ?>;
	   border-color: #<?php echo $cor_backbar; ?>;
	}
	<?php } ?>
	
	/*-- bloco saldos --*/
	
	.blkSaldo {
		margin-top: 1.5em;
	}
	.blkSaldo-left{
		background:#1B4F72;
		background-image: url(../images/lighten.png);
		text-align:center;
		padding: 15px 0 0 0px;
		 border-bottom-left-radius: 0.3em;
		-o-border-bottom-left-radius: 0.3em;
		-moz-border-bottom-left-radius: 0.3em;
		border-top-left-radius: 0.3em;
		-o-border-top-left-radius: 0.3em;
		-moz-border-top-left-radius: 0.3em;
		
	}
	.blkSaldo-middle{
		background:#2874A6;
		background-image:url('../images/lighten.png');
		border-radius:0;
	}
	
	.blkSaldo-right{
		background:#cc324b;
		background-image:url('../images/lighten.png');
		border-radius:0;
	}
	
	.blkSaldo-lost{
		background:#3498DB;
		background-image:url('../images/lighten.png');
		border-radius:0;
		border-bottom-right-radius: 0.3em;
		-o-border-bottom-right-radius: 0.3em;
		-moz-border-bottom-right-radius: 0.3em;
		-webkit-border-bottom-right-radius: 0.3em;
		border-top-right-radius: 0.3em;
		-o-border-top-right-radius: 0.3em;
		-moz-border-top-right-radius: 0.3em;
		-webkit-border-top-right-radius: 0.3em;

	}
	
	.blkSaldo-left span{
		display: block;
		font-size: 15px;
		font-weight: 400;
		color: #fff;
		background-color: #1B4F72;
		padding: 8px 0;
		margin-top: 15px;
		border-bottom-left-radius: 0.3em;
		-o-border-bottom-left-radius: 0.3em;
		-moz-border-bottom-left-radius: 0.3em;

	}
	span.resgatado {
		background-color: #2874A6;
		border-radius:0;
	}
	span.liberar {
		background-color: #3498DB;
		border-bottom-right-radius: 0.3em;
	}
	span.expirar {
		background-color: #3498DB;
		border-bottom-right-radius: 0.3em;
		-o-border-bottom-right-radius: 0.3em;
		-moz-border-bottom-right-radius: 0.3em;
		-webkit-border-bottom-right-radius: 0.3em;
	}
	.blkSaldo img {
		text-align: center;
		margin: 0 auto;
	}
	/*-- bloco saldo --*/

	/* (320x480) iPhone (Original, 3G, 3GS) */
@media only screen and (min-device-width: 320px) and (max-device-width: 480px) {
	body { 
	  background:#<?php echo $cor_backpag; ?> url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback_mob; ?>') no-repeat center fixed; 
	  -webkit-background-size: cover;
	  -moz-background-size: cover;
	  -o-background-size: cover;
	  background-size: cover;
	}

    
}
 
/* (320x480) Smartphone, Portrait */
@media only screen and (device-width: 320px) and (orientation: portrait) {
    body { 
	  background:#<?php echo $cor_backpag; ?> url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback_mob; ?>') no-repeat center fixed; 
	  -webkit-background-size: cover;
	  -moz-background-size: cover;
	  -o-background-size: cover;
	  background-size: cover;
	}

}
 
/* (320x480) Smartphone, Landscape */
@media only screen and (device-width: 480px) and (orientation: landscape) {
    body { 
	  background:#<?php echo $cor_backpag; ?> url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback; ?>') no-repeat center fixed; 
	  -webkit-background-size: cover;
	  -moz-background-size: cover;
	  -o-background-size: cover;
	  background-size: cover;
	}
	
		
}
 
/* (1024x768) iPad 1 & 2, Landscape */
@media only screen and (min-device-width: 768px) and (max-device-width: 1367px) and (orientation: landscape) {
    body { 
	  background:#<?php echo $cor_backpag; ?> url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback; ?>') no-repeat center fixed; 
	  -webkit-background-size: cover;
	  -moz-background-size: cover;
	  -o-background-size: cover;
	  background-size: cover;
	}


	.navbar img{
		margin-top: 0;
	}
		 
}

/* (1280x800) Tablets, Portrait */
@media only screen and (max-width: 800px) and (orientation : portrait) {
	 body { 
	  background:#<?php echo $cor_backpag; ?> url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback_mob; ?>') no-repeat bottom fixed; 
	  -webkit-background-size: cover;
	  -moz-background-size: cover;
	  -o-background-size: cover;
	  background-size: 103%;
	}

	.navbar img{
		margin-top: -10px;
	}

	
}

/* (768x1024) iPad 1 & 2, Portrait */
@media only screen and (max-width: 768px) and (orientation : portrait) {
    body { 
	  background:#<?php echo $cor_backpag; ?> url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback_mob; ?>') no-repeat center fixed; 
	  -webkit-background-size: cover;
	  -moz-background-size: cover;
	  -o-background-size: cover;
	  background-size: cover;
	}


	.navbar img{
		margin-top: 0;
	}
		 
}
 
/* (2048x1536) iPad 3 and Desktops*/
@media only screen and (min-device-width: 1536px) and (max-device-width: 2048px) {
    body { 
	  background:#<?php echo $cor_backpag; ?> url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback; ?>') no-repeat center fixed; 
	  -webkit-background-size: cover;
	  -moz-background-size: cover;
	  -o-background-size: cover;
	  background-size: cover;
	}


	.navbar img{
		margin-top: 0;
	}
		 
}

@media only screen and (min-device-width: 1100px) and (orientation : portrait) {
    body { 
	  background:#<?php echo $cor_backpag; ?> url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback_mob; ?>') no-repeat center fixed; 
	  -webkit-background-size: cover;
	  -moz-background-size: cover;
	  -o-background-size: cover;
	  background-size: cover;
	}


	.navbar img{
		margin-top: 0;
	}
		 
}

@media (max-height: 824px) and (max-width: 416px){
	body { 
	  background:#<?php echo $cor_backpag; ?> url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback_mob; ?>') no-repeat center fixed; 
	  -webkit-background-size: cover;
	  -moz-background-size: cover;
	  -o-background-size: cover;
	  background-size: cover;
	}

}	

/* (320x480) iPhone (Original, 3G, 3GS) */
@media (max-device-width: 737px) and (max-height: 416px) {
	body { 
	  background:#<?php echo $cor_backpag; ?> url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback; ?>') no-repeat center fixed; 
	  -webkit-background-size: cover;
	  -moz-background-size: cover;
	  -o-background-size: cover;
	  background-size: cover;
	}

	
    
}
	
	</style>	
	
	<!-- Favicons -->
	<link rel="icon" href="images/favicon.ico">
	
    </head>
	
    <body>
	
	<!-- top nav bar -->	
	<nav class="navbar navbar-default menuCentral " style="border-radius: 0;">
	  <div class="container-fluid">
		<div id="navbar" class="navbar-collapse collapse">
			<div style="text-align: center;">
				<div class="push20"></div>
				<a href="/?key=<?php echo $_GET['key'] ;?>"><img class="logo-<?php echo $des_alinham; ?>" src="http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_logo; ?>"></a>
				<div class="push20"></div>
			<div>
		</div><!--/.nav-collapse -->
	  </div>
	</nav> 
	<!-- end top nav bar -->

		<div class="container" id="pesquisa">
			<div class="col-xs-12">
				<iframe frameborder="0" id="blocoPesquisa" src="" style="width: 100%; height: 800px;" sandbox="allow-top-navigation allow-scripts allow-forms"></iframe>
			</div>
		</div>

		<input type="hidden" id="MOVEU_MOUSE" value="N">
	
	<script src="http://bunker.mk/js/bootstrap.min.js" type="text/javascript"></script>
	<script src="http://bunker.mk/js/plugins/jquery.webui-popover.min.js" type="text/javascript"></script>
	<script src="http://bunker.mk/js/chosen.jquery.min.js" type="text/javascript"></script>	
	<script src="http://bunker.mk/js/plugins/validator.min.js" type="text/javascript"></script>
	<script src="http://bunker.mk/js/mainTotem.js" type="text/javascript"></script>
	<script src="http://bunker.mk/js/jquery.mask.min.js" type="text/javascript"></script>
	<script src="http://bunker.mk/js/plugins/ie10-viewport-bug-workaround.js" type="text/javascript"></script>
	<script src="http://bunker.mk/js/plugins/jquery.tablesorter.min.js" type="text/javascript"></script>
	<script src="http://bunker.mk/js/plugins/jquery.uitablefilter.js" type="text/javascript"></script>
	<script src="http://bunker.mk/js/jquery-confirm.min.js"></script>
	<script src="http://bunker.mk/js/plugins/jquery.placeholder.js"></script>	
	<script>

	<?php if ($val_inativo != "0"){ ?>
	// var timer;

	// window.onload= document.onmousemove= document.onkeypress= function(){
	// 	clearTimeout(timer);
	// 	resetaTimerTotem();
	// 	console.log($("#MOVEU_MOUSE").val());
	// }	

	// window.addEventListener('touchstart', function() {
	// 	clearTimeout(timer);
 //  		resetaTimerTotem();
	// });
	
	<?php } ?>
	
	$('input, textarea').placeholder();	

	$(function(){
		$("#blocoPesquisa").attr('src',"https://<?=$des_dominio?>.fidelidade.mk/pesquisa?idP=<?=fnEncode($cod_pesquisa)?>&idc=<?=fnEncode($cod_cliente)?>&cod_players=<?=fnEncode($cod_players)?>");
	});

	// function resetaTimerTotem(){
	// 	timer = setTimeout(function(){ location= '/<?php echo $destinoHome; ?>?key=<?php echo $_GET['key'] ;?>'},<?php echo $val_inativo ;?>000 );
	// 	console.log('resetou');
	// 	console.log(<?php echo $val_inativo ;?>);
	// }

	</script>
	
    </body>
	
</html>

	