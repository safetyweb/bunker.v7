<?php
include "../_system/_functionsMain.php";
include_once './funWS/buscaConsumidor.php';

	//echo "<h1>".$_GET['param']."</h1>";
	//$cpf = $_GET['c1'];
	//echo fnDebug('true');
	//fnEscreve("totem");

	$hashLocal = mt_rand();	
	
	$parametros = fnDecode($_GET['key']);
	$urlTicket = fnLimpacampo($_GET['url'].'&ch=3');
	
	//$cpf = $_GET['c1'];
	//$cpf = '16370808830';
	$arrayCampos = explode(";", $parametros);
	//$buscaconsumidor=fnconsulta($cpf, $arrayCampos);
	$cod_empresa = $arrayCampos[4];
	
	//fnEscreve($cod_empresa);
	
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
			$_SESSION['last_request']  = $request;
			
			$c1 = fnLimpacampoZero(fnLimpaDoc($_REQUEST['c1']));
			//$cod_orcamento = fnLimpacampo($_REQUEST['COD_ORCAMENTO']);
 

		}
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

    $cor_backbar = $qrBuscaSiteTotem['COR_BACKBAR'];
    $cor_backpag = $qrBuscaSiteTotem['COR_BACKPAG'];
    $cor_titulos = $qrBuscaSiteTotem['COR_TITULOS'];
    $cor_textos = $qrBuscaSiteTotem['COR_TEXTOS'];
    $cor_botao = $qrBuscaSiteTotem['COR_BOTAO'];
    $cor_botaoon = $qrBuscaSiteTotem['COR_BOTAOON'];
	
    $des_paghome = $qrBuscaSiteTotem['DES_PAGHOME'];
	if ($des_paghome == "index" || $des_paghome == ""){$destinoHome = "";}
	else {$destinoHome = "banner.do";}
    
	$val_inativo = $qrBuscaSiteTotem['VAL_INATIVO'];

	//fnMostraForm();

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
	$destinoHome = "";

	if($des_paghome == "index" || $des_paghome == ""){
		$destinoHome = "";
	}else if($des_paghome == "nps"){
		$destinoHome = "pesquisa.do";
	}else if($des_paghome == "cad"){
		$destinoHome = "consulta_V2.do";
	}else{
		$destinoHome = "banner.do";
	}

}
	
?>
	

<html lang="pt">
    <head>
	
	<meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=10" />
        <meta http-equiv="X-UA-Compatible" content="IE=11" />
	<meta name="viewport" content="width=device-width,height=device-height,initial-scale=1.0"/>

	<title>Totem</title>
		
	<link href="https://bunker.mk/css/bootstrap.flatly.min.css" rel="stylesheet">
	<script src="https://bunker.mk/js/jquery.min.js"></script>
	
	<!-- JQUERY-CONFIRM -->
	<link href="https://bunker.mk/css/jquery-confirm.min.css" rel="stylesheet"/>
	
	<!-- extras -->
	<link href="https://bunker.mk/css/jquery.webui-popover.min.css" rel="stylesheet" />
	<link href="https://bunker.mk/css/chosen-bootstrap.css" rel="stylesheet" />
	<link href="https://bunker.mk/css/font-awesome.min.css" rel="stylesheet" />
	
    <!-- complement -->
	<link href="https://bunker.mk/css/default.css" rel="stylesheet" />
	<link href="https://bunker.mk/css/checkMaster.css" rel="stylesheet" />
		
	<!--[if lt IE 9]><script src="js/ie8-responsive-file-warning.js"></script><![endif]
	<script src="https://bunker.mk/js/plugins/ie-emulation-modes-warning.js"></script>
	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
	  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

	<!-- Favicons -->
	<link rel="icon" type="image/ico" rel="shortcut icon" href="images/favicon.ico"/>
	
	<style>

	*,*{
		margin: 0 auto;
	}
	body{
		overflow-x: hidden;
	}

	@page{size:auto; margin:0mm;}

	.bloco-pai{
		background: #fff;
	}

	.bloco{
		position: relative; 
		clear:both;
		margin-top: 5px;
		text-align:center;
		margin-right: auto;
		margin-left: auto;
		max-width: 360px;
		overflow: hidden;
	}
	
	.input-lg {
		font-size: 35px;
		line-height: 1.5;
	}	
	
	body { 
	  background:#<?php echo $cor_backpag; ?> url('https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback; ?>') no-repeat center center fixed; 
	  -webkit-background-size: cover;
	  -moz-background-size: cover;
	  -o-background-size: cover;
	  background-size: cover;
	  width: 100vw !important;
	}
	
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
	
	@media print {
	  .hidden-print {
		display: none !important;
	  }
	}

	/* (320x480) iPhone (Original, 3G, 3GS) */
@media only screen and (min-device-width: 320px) and (max-device-width: 480px) {
	body { 
	  background:#<?php echo $cor_backpag; ?> url('https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback_mob; ?>') no-repeat center fixed; 
	  -webkit-background-size: cover;
	  -moz-background-size: cover;
	  -o-background-size: cover;
	  background-size: cover;
	}

    
}
 
/* (320x480) Smartphone, Portrait */
@media only screen and (device-width: 320px) and (orientation: portrait) {
    body { 
	  background:#<?php echo $cor_backpag; ?> url('https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback_mob; ?>') no-repeat center fixed; 
	  -webkit-background-size: cover;
	  -moz-background-size: cover;
	  -o-background-size: cover;
	  background-size: cover;
	}

}
 
/* (320x480) Smartphone, Landscape */
@media only screen and (device-width: 480px) and (orientation: landscape) {
    body { 
	  background:#<?php echo $cor_backpag; ?> url('https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback; ?>') no-repeat center fixed; 
	  -webkit-background-size: cover;
	  -moz-background-size: cover;
	  -o-background-size: cover;
	  background-size: cover;
	}
	
		
}
 
/* (1024x768) iPad 1 & 2, Landscape */
@media only screen and (min-device-width: 768px) and (max-device-width: 1367px) and (orientation: landscape) {
    body { 
	  background:#<?php echo $cor_backpag; ?> url('https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback; ?>') no-repeat center fixed; 
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
	  background:#<?php echo $cor_backpag; ?> url('https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback_mob; ?>') no-repeat bottom fixed; 
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
	  background:#<?php echo $cor_backpag; ?> url('https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback_mob; ?>') no-repeat center fixed; 
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
	  background:#<?php echo $cor_backpag; ?> url('https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback; ?>') no-repeat center fixed; 
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
	  background:#<?php echo $cor_backpag; ?> url('https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback_mob; ?>') no-repeat center fixed; 
	  -webkit-background-size: cover;
	  -moz-background-size: cover;
	  -o-background-size: cover;
	  background-size: cover;
	}


	.navbar img{
		margin-top: 0;
	}
		 
}

	.logo-center{
		margin-left: auto;
		margin-right: auto;
	}
	
	</style>	
	
	<!-- Favicons -->
	<link rel="icon" href="images/favicon.ico">
	
    </head>
	
    <body>

		<!-- top nav bar -->	
		<nav class="navbar navbar-default menuCentral " style="border-radius: 0;">
		  <div class="container-fluid">
			<div id="navbar" class="navbar-collapse">
				<div style="text-align: center;">
					<div class="push20"></div>
					<a href="/?key=<?php echo $_GET['key'] ;?>"><img class="logo-<?php echo $des_alinham; ?> img-responsive" src="https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_logo; ?>"></a>
					<div class="push20"></div>
				<div>
			</div><!--/.nav-collapse -->
		  </div>
		</nav> 
		<!-- end top nav bar -->

	<div class="container">

		<div class="push50"></div>
	
		<form data-toggle="validator" role="form2" method="post" id="formulario" action="cadastro.do?key=<?php echo $_GET['key'] ;?>">

			<div class="col-xs-12">
			
					<div class="row text-center">
					
					
						<div class="col-md-4">
						</div>

						<div class="bloco bloco-pai">
						
							<div class="bloco" style="opacity: 0.9;">
								<center>	
								<?php 
								function getter($url) {
									$ch = curl_init();
									curl_setopt($ch, CURLOPT_URL, $url);
									curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
									curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
									curl_setopt($ch, CURLOPT_HEADER, 0);
									$data = curl_exec($ch);
									curl_close($ch);
									return $data;
								}
								echo getter($urlTicket.'&print=no');
								?>
								</center>
							</div>

						</div>
						
						<div class="col-md-3">
						
<?php
//$teste = $_GET['ch'];
//echo "$teste";
//echo "$urlTicket";
//echo $url;
?>
						
						
						</div>

					</div>

						<div class="push50"></div>

					<div class="row">
					
						<div class="col-xs-12 col-md-6 col-md-offset-3">
							<a href="javascript:window.print();" name="PRINT" id="PRINT" class="btn btn-info btn-lg btn-block hidden-print" tabindex="5"><i class="fa fa-print" aria-hidden="true"></i>&nbsp; Imprimir Ticket de Ofertas</a>
						</div>
					
					</div>
					
					<div class="push10"></div>
						
						<div class="row">
						
							<div class="col-xs-12 col-md-6 col-md-offset-3">
								<a href="/?key=<?php echo $_GET['key'] ;?>" name="CAD" id="CAD" class="btn btn-primary btn-lg btn-block hidden-print" tabindex="5"><i class="fa fa-home" aria-hidden="true"></i>&nbsp; Nova Consulta</a>
							</div>

						</div>
						
						<div class="push100"></div>
						
					

			</div>
			
		<input type="hidden" name="opcao" id="opcao" value="">
		<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
		<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">		
			
		</form>

	</div>
	
	<script src="https://bunker.mk/js/bootstrap.min.js" type="text/javascript"></script>
	<script src="https://bunker.mk/js/chosen.jquery.min.js" type="text/javascript"></script>	
	<script src="https://bunker.mk/js/plugins/validator.min.js" type="text/javascript"></script>
	<script src="https://bunker.mk/js/mainTotem.js" type="text/javascript"></script>
	<script src="https://bunker.mk/js/jquery.mask.min.js" type="text/javascript"></script>
	<script src="https://bunker.mk/js/plugins/ie10-viewport-bug-workaround.js" type="text/javascript"></script>
	<script src="https://bunker.mk/js/jquery-confirm.min.js"></script>
	<script src="https://bunker.mk/js/plugins/jquery.placeholder.js"></script>
	
	<script>
	
	$('input, textarea').placeholder();
	
	</script>

    </body>
	
</html>

	