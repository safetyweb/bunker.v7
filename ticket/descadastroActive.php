<?php
include "../_system/_functionsMain.php";
include_once '../totem/funWS/buscaConsumidor.php';
include_once '../totem/funWS/buscaConsumidorCNPJ.php';

//habilitando o cors
// header("Access-Control-Allow-Origin: *");


//busca dados da url	
if (fnLimpacampo($_GET['param']) != ""){
	//busca codigo da empresa
	$cod_busca = strtolower(fnLimpacampo($_GET['param']));	
	$sql = "select COD_EMPRESA from DOMINIO WHERE DES_DOMINIO = '$cod_busca' ";
    //fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(),$sql);
	$qrBuscaCodEmpresa = mysqli_fetch_assoc($arrayQuery);
	//fnEscreve($qrBuscaCodEmpresa['COD_EMPRESA']);                
    $cod_empresa = $qrBuscaCodEmpresa['COD_EMPRESA'];
    //$nom_fantasi = $qrBuscaCodEmpresa['NOM_FANTASI'];
            
	if (isset($qrBuscaCodEmpresa)){
		$cod_empresa = $qrBuscaCodEmpresa['COD_EMPRESA'];
		$siteGo = "OK";
	}else {
		$siteGo = "NOK";
	}
											
}

// echo "$siteGo";

//se carrega site
if ($siteGo == "OK"){
	
	//fnEscreve($siteGo);
	//fnEscreve($cod_empresa);
	
	//busca nome da empresa
	$sql2 = "select NOM_FANTASI, QTD_CHARTKN, TIP_TOKEN, TIP_RETORNO, NUM_DECIMAIS_B from EMPRESAS WHERE COD_EMPRESA = $cod_empresa ";
	$arrayQuery = mysqli_query($connAdm->connAdm(),$sql2);
	$qrBuscaDadosEmpresa = mysqli_fetch_assoc($arrayQuery);
    $nom_fantasi = $qrBuscaDadosEmpresa['NOM_FANTASI'];
    $qtd_chartkn = $qrBuscaDadosEmpresa['QTD_CHARTKN'];
    $tip_token = $qrBuscaDadosEmpresa['TIP_TOKEN'];

    if($qrBuscaEmpresa['TIP_RETORNO'] == 1){
		$casasDec = 0;
	}else{
		$casasDec = $qrBuscaEmpresa['NUM_DECIMAIS_B'];
		$pref = "R$ ";
	}

    if($tip_token == 2){
		$type = "number";
	}else{
		$type = "text";
	}

	$cod_cliente = fnLimpaCampoZero(fnDecode($_GET['idC']));

	// echo "_".$cod_cliente;

	//busca dados da tabela
	$sql = "SELECT * FROM SITE_EXTRATO WHERE COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql) or die(mysqli_error());
	$qrBuscaSiteExtrato = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		//fnEscreve("entrou if");
		$cod_extrato = $qrBuscaSiteExtrato['COD_EXTRATO'];
		$des_dominio = $qrBuscaSiteExtrato['DES_DOMINIO'];
		$des_logo = $qrBuscaSiteExtrato['DES_LOGO'];
		$des_banner = $qrBuscaSiteExtrato['DES_BANNER'];
		$des_email = $qrBuscaSiteExtrato['DES_EMAIL'];
		$log_home = $qrBuscaSiteExtrato['LOG_HOME'];
		$destino_home = $qrBuscaSiteExtrato['DESTINO_HOME'];
		$log_vantagem = $qrBuscaSiteExtrato['LOG_VANTAGEM'];
		$txt_vantagem = $qrBuscaSiteExtrato['TXT_VANTAGEM'];
		$log_regula = $qrBuscaSiteExtrato['LOG_REGULA'];
		$txt_regula = $qrBuscaSiteExtrato['TXT_REGULA'];
		$log_lojas = $qrBuscaSiteExtrato['LOG_LOJAS'];
		$txt_lojas = $qrBuscaSiteExtrato['TXT_LOJAS'];
		$log_faq = $qrBuscaSiteExtrato['LOG_FAQ'];
		$txt_faq = $qrBuscaSiteExtrato['TXT_FAQ'];
		$log_premios = $qrBuscaSiteExtrato['LOG_PREMIOS'];
		$txt_premios = $qrBuscaSiteExtrato['TXT_PREMIOS'];
		$log_extrato = $qrBuscaSiteExtrato['LOG_EXTRATO'];
		$txt_extrato = $qrBuscaSiteExtrato['TXT_EXTRATO'];
		$log_contato = $qrBuscaSiteExtrato['LOG_CONTATO'];
		$log_cadastro = $qrBuscaSiteExtrato['LOG_CADASTRO'];
		$txt_contato = $qrBuscaSiteExtrato['TXT_CONTATO'];
		$cor_titulos = $qrBuscaSiteExtrato['COR_TITULOS'];
		$cor_barra = $qrBuscaSiteExtrato['COR_BARRA'];
		$cor_txtbarra = $qrBuscaSiteExtrato['COR_TXTBARRA'];
		$cor_site = $qrBuscaSiteExtrato['COR_SITE'];
		$cor_textos = $qrBuscaSiteExtrato['COR_TEXTOS'];
		$cor_rodapebg = $qrBuscaSiteExtrato['COR_RODAPEBG'];
		$cor_rodape = $qrBuscaSiteExtrato['COR_RODAPE'];
		$cor_botao = $qrBuscaSiteExtrato['COR_BOTAO'];
		$cor_botaoon = $qrBuscaSiteExtrato['COR_BOTAOON'];
		$cor_txtbotao = $qrBuscaSiteExtrato['COR_TXTBOTAO'];
		$des_vantagem = $qrBuscaSiteExtrato['DES_VANTAGEM'];
		$ico_bloco1 = $qrBuscaSiteExtrato['ICO_BLOCO1'];
		$ico_bloco2 = $qrBuscaSiteExtrato['ICO_BLOCO2'];
		$ico_bloco3 = $qrBuscaSiteExtrato['ICO_BLOCO3'];
		$tit_bloco1 = $qrBuscaSiteExtrato['TIT_BLOCO1'];
		$des_bloco1 = $qrBuscaSiteExtrato['DES_BLOCO1'];
		$tit_bloco2 = $qrBuscaSiteExtrato['TIT_BLOCO2'];
		$des_bloco2 = $qrBuscaSiteExtrato['DES_BLOCO2'];
		$tit_bloco3 = $qrBuscaSiteExtrato['TIT_BLOCO3'];
		$des_bloco3 = $qrBuscaSiteExtrato['DES_BLOCO3'];
		$des_regras = $qrBuscaSiteExtrato['DES_REGRAS'];
		$des_programa = $qrBuscaSiteExtrato['DES_PROGRAMA'];
		$tp_ordenac = $qrBuscaSiteExtrato['TP_ORDENAC'];
	}

	list($r_cor_backpag, $g_cor_backpag, $b_cor_backpag) = sscanf("#".$cor_site, "#%02x%02x%02x");

	if($r_cor_backpag > 50){
		$r = ($r_cor_backpag-50);
	}else{
		$r =($r_cor_backpag+50);
		if($r_cor_backpag < 30){
			$r = $r_cor_backpag;
		}
	}
	if($g_cor_backpag > 50){
		$g = ($g_cor_backpag-50);
	}else{
		$g =($g_cor_backpag+50);
		if($g_cor_backpag < 30){
			$g = $g_cor_backpag;
		}
	}
	if($b_cor_backpag > 50){
		$b = ($b_cor_backpag-50);
	}else{
		$b =($b_cor_backpag+50);
		if($b_cor_backpag < 30){
			$b = $b_cor_backpag;
		}
	}

	if($r_cor_backpag <= 50 && $g_cor_backpag <= 50 && $b_cor_backpag <= 50){
		$r =($r_cor_backpag+40);
		$g =($g_cor_backpag+40);
		$b =($b_cor_backpag+40);
	}

}

$sqlControle = "SELECT * FROM CONTROLE_TERMO WHERE COD_EMPRESA = $cod_empresa";

// fnEscreve($sqlControle);

$arrayControle = mysqli_query(connTemp($cod_empresa,''),$sqlControle);

$qrControle = mysqli_fetch_assoc($arrayControle);

$log_separa = $qrControle['LOG_SEPARA'];
$log_lgpd = $qrControle['LOG_LGPD'];

$des_img = $qrControle['DES_IMG'];
$des_img_g = $qrControle['DES_IMG_G'];
$des_imgmob = $qrControle['DES_IMGMOB'];

$sql = "SELECT * FROM  USUARIOS
		WHERE LOG_ESTATUS='S' AND
			  COD_EMPRESA = $cod_empresa AND
			  COD_TPUSUARIO = 10  limit 1  ";
//fnEscreve($sql);
$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
$qrBuscaUsuTeste = mysqli_fetch_assoc($arrayQuery);
				
if (isset($arrayQuery)) {
	$log_usuario = $qrBuscaUsuTeste['LOG_USUARIO'];
	$des_senhaus = fnDecode($qrBuscaUsuTeste['DES_SENHAUS']);
}

$sqlUn = "SELECT COD_UNIVEND FROM UNIDADEVENDA 
		  WHERE COD_EMPRESA = $cod_empresa 
		  AND LOG_ESTATUS = 'S' 
		  ORDER BY 1 ASC LIMIT 1";

$arrayUn = mysqli_query($connAdm->connAdm(), $sqlUn);
$qrLista = mysqli_fetch_assoc($arrayUn);

$idlojaKey = $qrLista['COD_UNIVEND'];
$idmaquinaKey = 0;
$codvendedorKey = 0;
$nomevendedorKey = 0;

$urltotem = $log_usuario.';'
			.$des_senhaus.';'
			.$idlojaKey.';'
			.$idmaquinaKey.';'
			.$cod_empresa.';'
			.$codvendedorKey.';'
			.$nomevendedorKey;

$arrayCampos = explode(";", $urltotem);

if($cod_cliente != 0){
	$sqlDescad = "DELETE FROM CLIENTES WHERE COD_EMPRESA = $cod_empresa AND COD_CLIENTE = $cod_cliente";
	mysqli_query(connTemp($cod_empresa,''), $sqlDescad);
}

?>

<!DOCTYPE html>
	<html lang="pt-br">
		<head>
			<meta charset="utf-8">
			<title><?php echo $des_programa; ?> - <?php echo $nom_fantasi; ?></title>
			<meta name="viewport" content="width=device-width, initial-scale=1.0">

			<link href="css/main.css" rel="stylesheet">
			<link href="css/custom.css" rel="stylesheet">
			
			<!-- SISTEMA -->
			<link href="https://bunker.mk/css/jquery-confirm.min.css" rel="stylesheet"/>
			<link href="https://bunker.mk/css/jquery.webui-popover.min.css" rel="stylesheet" />
			<link href="https://bunker.mk/css/chosen-bootstrap.css" rel="stylesheet" />
			<link href="https://bunker.mk/css/font-awesome.min.css" rel="stylesheet" />
			<link rel="stylesheet" type="text/css" href="https://bunker.mk/css/fontawesome-pro-5.13.0-web/css/all.min.css" />
			
			<!-- complement -->
			<link href="https://bunker.mk/css/default.css" rel="stylesheet" />
			<link href="https://bunker.mk/css/checkMaster.css" rel="stylesheet" />
			
			
			<!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
			<!--[if lt IE 9]>
			  <script src="js/html5shiv.js"></script>
			<![endif]-->
			
		</head>
		
		<!--///////////////////////////////////////// PARALLAX BACKGROUND ////////////////////////////////////////-->
		<style>

			body{
				width: 100vw;
				background: #<?=$cor_site?>!important;
				-ms-overflow-style: none!important;  /* Internet Explorer 10+ */
    			scrollbar-width: none!important;  /* Firefox */
    			overflow-y: visible;
			}

			body::-webkit-scrollbar { 
			    display: none!important;  /* Safari and Chrome */
			}

			#parallax {
			  height: 652px;
			  width: 100%;
			  position: fixed;
			  background: none;
			  background-size: cover;
			  z-index: -100;
			}

			.logo-img{
				height: 40px!important;
			}

			section{
				padding-top: 15px!important;
			}
			
			h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6 {
				color: #<?php echo $cor_titulos; ?>;
			}			
			
			p, p.lead  {
				color: #<?php echo $cor_textos; ?>;
			}			
			.bottom-menu-inverse {
				background-color: #<?php echo $cor_rodapebg; ?>;
				color: #<?php echo $cor_rodape; ?>;
			}
			
			.fFooter {
				color: #<?php echo $cor_rodape; ?>;
			}	
			
			.navbar .nav > li > a{
				color: #<?php echo $cor_titulos; ?>;
			}

			.btn-primary {
				background-color: #<?php echo $cor_botao; ?>;
			}
			
			.btn-primary:hover {
				background-color: #<?php echo $cor_botaoon; ?>;
			}	

			p {
				font-size: 12px; margin: 0;
				padding: 0 0 3px 0;
			}	
			
			.f18 {
				font-size: 18px;
			}			
			
			#corpoForm{
		width: 100vw!important;
	}

	#caixaForm{
		overflow: auto;
	}

	#caixaImg, #caixaForm{
		height: 100vh;
	}

	#caixaImg{
		background: #4C4C58 url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_img; ?>') no-repeat center center; 
		-webkit-background-size: 100% 100%;
		  -moz-background-size: 100% 100%;
		  -o-background-size: 100% 100%;
		  background-size: 100% 100%;
	}

	input::-webkit-input-placeholder {
		font-size: 22px;
		line-height: 3;
	}

	/* (320x480) iPhone (Original, 3G, 3GS) */
@media only screen and (min-device-width: 320px) and (max-device-width: 480px) {
	body { 
	  background:#<?php echo $cor_backpag; ?> url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback_mob; ?>') no-repeat center fixed; 
	  -webkit-background-size: cover;
	  -moz-background-size: cover;
	  -o-background-size: cover;
	  background-size: cover;
	  overflow: auto!important;
	}

	#corpoForm{
		width: unset!important;
	}

	#caixaImg, #caixaForm{
		height: unset;
	}

	#caixaImg{
		background: #4C4C58 url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgmob; ?>') no-repeat center center;
		height: 360px;
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
	  overflow: auto!important;
	}

#corpoForm{
		width: unset!important;
	}

	#caixaImg, #caixaForm{
		height: unset;
	}

	#caixaImg{
		background: #4C4C58 url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgmob; ?>') no-repeat center center;
		height: 360px;
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

	#caixaImg{
		 padding: 0;
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

	#corpoForm{
		width: unset!important;
	}

	#caixaImg, #caixaForm{
		height: unset;
	}

	#caixaImg{
		background: #4C4C58 url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgmob; ?>') no-repeat center center;
		height: 360px;
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
	  overflow: auto!important;
	}

	

	.navbar img{
		margin-top: 0;
	}

#corpoForm{
		width: unset!important;
	}

	#caixaImg, #caixaForm{
		height: unset;
	}

	#caixaImg{
		background: #4C4C58 url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgmob; ?>') no-repeat center center;
		height: 360px;
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

	#caixaImg{
		background: #4C4C58 url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_img_g; ?>') no-repeat center center;
		 padding: 0;
	}
		 
}

@media only screen and (min-device-width: 1100px) and (orientation : portrait) {
    body { 
	  background:#<?php echo $cor_backpag; ?> url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback_mob; ?>') no-repeat center fixed; 
	  -webkit-background-size: cover;
	  -moz-background-size: cover;
	  -o-background-size: cover;
	  background-size: cover;
	  overflow: auto!important;
	}

	

	.navbar img{
		margin-top: 0;
	}

	#corpoForm{
		width: unset!important;
	}

	#caixaImg, #caixaForm{
		height: unset;
	}

	#caixaImg{
		background: #4C4C58 url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgmob; ?>') no-repeat center center;
		height: 360px;
	}
		 
}

@media (max-height: 824px) and (max-width: 416px){
	body { 
	  background:#<?php echo $cor_backpag; ?> url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgback_mob; ?>') no-repeat center fixed; 
	  -webkit-background-size: cover;
	  -moz-background-size: cover;
	  -o-background-size: cover;
	  background-size: cover;
	  overflow: auto!important;
	}

	#corpoForm{
		width: unset!important;
	}

	#caixaImg, #caixaForm{
		height: unset;
	}

	#caixaImg{
		background: #4C4C58 url('http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_imgmob; ?>') no-repeat center center;
		height: 360px;
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

	#caixaImg{
		 padding: 0;
	}

		
}

	.input-sm, .chosen-single{
		font-size: 20px!important;
	}

	.logo-center{
		margin-left: auto;
		margin-right: auto;
	}

	#blocker
    {
        display:none; 
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: .8;
        background-color: #fff;
        z-index: 1000;
    }
        
    #blocker div
    {
        position: absolute;
        top: 30%;
        left: 48%;
        width: 200px;
        height: 2em;
        margin: -1em 0 0 -2.5em;
        color: #000;
        font-weight: bold;
    }

		</style>		
	  
		
		<!-- Scrollspy set in the body -->
		<body id="home" data-spy="scroll" data-target=".main-nav" data-offset="73">

		<div id="parallax"></div>
		
		<!--/////////////////////////////////////// NAVIGATION BAR ////////////////////////////////////////-->

		<section id="header">

			<nav class="navbar navbar-fixed-top" role="navigation">

				<div class="navbar-inner">
					<div class="container">

						<button type="button" class="btn btn-navbar" data-toggle="collapse" data-target="#navigation"></button>

					   <!-- Logo goes here - replace the image with yours -->
						<a href="." class="navbar-brand"><img src="https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?php echo $des_logo; ?>" class="logo-img img-responsive" alt="<?php echo $des_programa; ?> - <?php echo $nom_fantasi; ?>" title="Booom! Logo"></a>

						<div class="collapse navbar-collapse main-nav" id="navigation">

							<ul class="nav pull-right">
								<!-- Menu -->
								<li class='active'><a href='#home'>Home</a></li>
								<?php
								if ($log_contato == "S"){echo "<li><a href='#contact'>$txt_contato</a></li>";}
								?>	
							</ul>

						</div><!-- /nav-collapse -->
					</div><!-- /container -->
				</div><!-- /navbar-inner -->
			</nav>

		</section>

		<!--/////////////////////////////////////// CONTACT SECTION ////////////////////////////////////////-->
		
		<section id="contact">


			<div class="row" id="corpoForm">

				<form data-toggle="validator" role="form2" method="post" id="formulario" autocomplete="off">

					<!-- <div class="col-md-6 col-xs-12" id="caixaImg"> -->
						<!-- <img src="http://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/<?=$des_img?>" class="img-responsive" style="margin-left: auto; margin-right: auto;"> -->
					<!-- </div> -->

					<div class="col-md-6 col-md-offset-3 col-xs-12 text-center" id="caixaForm" style="background-color: #FFF;">

						<div class="push20"></div>
						<div class="push50"></div>
						
						<h3><b>Dados excluídos</b> com <b>sucesso</b>.</h3>

					</div>
			
				</form>
				
			</div><!-- /container -->

		</section>

		<section id="footer">
			<div class="bottom-menu-inverse">

				<div class="container">

					<div class="row">
						<div class="col-md-6">
							<p class="fFooter"><?php echo $nom_fantasi; ?> - &copy; Todos os direitos reservados. <br/> 
							Solução: &nbsp; <a href="https://marka.mk" class="fFooter" target="_blank">Marka Soluções em Fidelização</a>.</p>
						</div>

						<div class="col-md-6 social">
							<ul class="bottom-icons">
								<?php								
								
									$sql = "select * 
											from rede_sociais RS
											inner join $connAdm->DB.tipo_redes_sociais TRD on TRD.COD_REDES = RS.COD_REDES
											WHERE 
											RS.COD_EMPRESA = $cod_empresa 
											ORDER BY TRD.NOM_REDES ";
											
									//fnEscreve($sql);
									$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
																						
									$count=0;
									while ($qrBuscaRedesSociais = mysqli_fetch_assoc($arrayQuery))
									  {														  
										$count++;
										?>	
										
										<li>
										  <a href="<?php echo $qrBuscaRedesSociais['DES_REDESOC']; ?>" target="_blank" ><i class="fa  fa-lg <?php echo $qrBuscaRedesSociais['DES_ICONE']; ?>"></i></a>
										</li>										
											
									  <?php	
									  }	
										  
								?>							

							  </ul>                      
						</div>
					</div>
				
				</div><!-- /row -->
			</div><!-- /container -->

		</section>
		
		<!-- modal -->									
		<div class="modal fade" id="popModal" tabindex='-1'>
			<div class="modal-dialog" style="">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title"></h4>
					</div>
					<div class="modal-body">
						<iframe frameborder="0" style="width: 100%; height: 600px !important"></iframe>
					</div>		
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->		
		
		<script src="js/jquery.min.js"></script>
		<script src="js/jquery-ui-1.10.3.custom.min.js"></script>
		<script src="js/jquery.ui.touch-punch.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/jquery.isotope.min.js"></script>
		<script src="js/bootstrap-select.js"></script>
		<script src="js/custom.js"></script>
		<script src="js/jquery.mask.min.js"></script>
		<script src="js/iframeResizer.min.js"></script>		
		<script src="js/jquery-confirm.min.js"></script>

		<script>

			$(function(){
	
				// $('input, textarea').placeholder();	

				$('.data').mask('00/00/0000');

				var SPMaskBehavior = function (val) {
				  return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
				},
				spOptions = {
				  onKeyPress: function(val, e, field, options) {
					  field.mask(SPMaskBehavior.apply({}, arguments), options);
					}
				};			
				
				$('.sp_celphones').mask(SPMaskBehavior, spOptions);
				
				//chosen
				$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
				$('#formulario').validator();

			});

			if($('.cpfcnpj').val() != undefined){
				mascaraCpfCnpj($('.cpfcnpj'));
			}
			
			function mascaraCpfCnpj(cpfCnpj){
				var optionsCpfCnpj = {
					onKeyPress: function (cpf, ev, el, op) {
						var masks = ['000.000.000-000', '00.000.000/0000-00'],
							mask = (cpf.length >= 15) ? masks[1] : masks[0];
						cpfCnpj.mask(mask, op);
					}
				}	

				var masks = ['000.000.000-000', '00.000.000/0000-00'];
				mask = (cpfCnpj.val().length >= 14) ? masks[1] : masks[0];
					
				cpfCnpj.mask(mask, optionsCpfCnpj);		
			}

			function toggleAuth(obj){
				$("#relatorioPreview").fadeIn("fast");
				$(obj).fadeOut(1);
			}

			function ajxDescadastra(cod_cliente){

				$.alert({
		          title: "Confirmação",
		          content: "Deseja excluir seus dados de forma <b>definitiva</b>?",
		          type: 'red',
		          buttons: {
		            "EXCLUIR": {
		               btnClass: 'btn-danger',
		               action: function(){
		                
		                    $.alert({
		                      title: "Aviso!",
		                      content: "<b>Todos</b> os dados serão excluídos <b>permanentemente</b>. Deseja <b>realmente</b> continuar?",
		                      type: 'red',
		                      buttons: {
		                        "EXCLUIR PERMANENTEMENTE": {
		                           btnClass: 'btn-danger',
		                           action: function(){
		                            	$.ajax({
											type: "POST",
											url: "ajxCadastro_V2.do?id=<?php echo fnEncode($cod_empresa); ?>",
											data: { COD_CLIENTE: cod_cliente },
											beforeSend:function(){
												$("#blocker").show();
											},
											success:function(data){	
												window.location.href = "descadastro.do?key=<?php echo $_GET['key'] ;?>";				
											},
											error:function(){
											    console.log('Erro');
											}
										});
		                           }
		                        },
		                        "CANCELAR": {
		                          btnClass: 'btn-default',
		                           action: function(){
		                            
		                           }
		                        }
		                      },
		                      backgroundDismiss: function(){
		                          return 'CANCELAR';
		                      }
		                    });

		               }
		            },
		            "CANCELAR": {
		              btnClass: 'btn-default',
		               action: function(){
		                
		               }
		            }
		          },
		          backgroundDismiss: function(){
		              return 'CANCELAR';
		          }
		        });

			}

			function ajxToken(){

				var num_celular = $("#NUM_CELULAR").val(),
					nom_cliente = $("#NOM_CLIENTE").val(),
					num_cgcecpf = $("#NUM_CGCECPF").val();

				if(num_celular != "" && nom_cliente != "" && num_cgcecpf != ""){

					$.ajax({
						type: "POST",
						url: "ajxCadastro_V2.do?id=<?php echo fnEncode($cod_empresa); ?>&opcao=TKN",
						data: { NOM_CLIENTE: nom_cliente, NUM_CELULAR: num_celular, NUM_CGCECPF: num_cgcecpf },
						beforeSend:function(){
							// $("#blocker").show();
						},
						success:function(data){	
							$("#relatorioToken").html(data);
							// window.location.href = "descadastro.do?key=<?php echo $_GET['key'] ;?>";				
						},
						error:function(){
						    console.log('Erro');
						}
					});

				}

			}

			function ajxValidaTkn(){

				var num_celular = $("#NUM_CELULAR").val(),
					nom_cliente = $("#NOM_CLIENTE").val(),
					des_token = $("#DES_TOKEN").val(),
					num_cgcecpf = $("#NUM_CGCECPF").val();

				if(num_celular != "" && nom_cliente != "" && num_cgcecpf != ""){

					$.ajax({
						type: "POST",
						url: "ajxCadastro_V2.do?id=<?php echo fnEncode($cod_empresa); ?>&opcao=VALTKNCAD",
						data: { NOM_CLIENTE: nom_cliente, NUM_CELULAR: num_celular, NUM_CGCECPF: num_cgcecpf, DES_TOKEN: des_token },
						beforeSend:function(){
							$("#blocker").show();
						},
						success:function(data){	

							$("#blocker").hide();

							if(data.trim() == "validado"){

								$("#KEY_DES_TOKEN").val($("#DES_TOKEN").val());

								$("#relatorioToken").fadeOut('fast',function(){
									$("#btnCad").fadeIn('fast');
									$("#formulario").validator("validate");	
								});						

							}else{

								$("#relatorioToken").html(data);

							}	

						},
						error:function(){
						    console.log('Erro');
						}
					});

				}

			}

		</script>	

	  </body>
	</html>