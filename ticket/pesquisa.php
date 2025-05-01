<?php
include "../_system/_functionsMain.php";

//habilitando o cors
header("Access-Control-Allow-Origin: *");
	//echo fnDebug('true');
	
	// Cod Cliente
	$cod_cliente = fnDecode($_GET['idc']);
	$totem = "N";
	// echo($cliente);
	if($cod_cliente != "preview"){
		$cod_cliente = fnLimpaCampoZero($cod_cliente);	
	}else{
		$cod_cliente = "preview";
	}
	
	if($_GET['cod'] != ""){
		$cod_pesquisa = fnDecode($_GET['cod']);
	}else{
		$cod_pesquisa = "";
	}

	if(isset($_GET['idP'])){
		$cod_pesquisa = fnDecode($_GET['idP']);
	}else{
		$cod_pesquisa = "";
	}

	$log_hotsite = fnLimpaCampoZero($_GET['hs']);

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

	if(isset($_GET['cod_players'])){
		$cod_players = fnLimpaCampoZero(fnDecode($_GET['cod_players']));
		$totem = "S";

		// echo($cod_players);
		// exit();
		//busca usuário modelo	
		$sql = "SELECT * FROM  USUARIOS
				WHERE LOG_ESTATUS='S' AND
					  COD_EMPRESA = $cod_empresa AND
					  COD_TPUSUARIO=10  limit 1  ";
	  //fnEscreve($sql);
	   $arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
		$qrBuscaUsuTeste = mysqli_fetch_assoc($arrayQuery);
						
		if (isset($arrayQuery)) {
			$log_usuario = $qrBuscaUsuTeste['LOG_USUARIO'];
			$des_senhaus = fnDecode($qrBuscaUsuTeste['DES_SENHAUS']);
		}

		$sqlPlayer = "SELECT T.COD_PLAYERS,
								   T.COD_EMPRESA,
								   T.COD_UNIVEND,
								   U.NOM_FANTASI,
								   T.COD_USUARIO,
								   T.VAL_INATIVO, 
								   T.LOG_TICKET, 
								   T.DES_PAGHOME, 
								   T.LOG_NPS 
							FROM TOTEM_PLAYERS T 
							LEFT JOIN UNIDADEVENDA U ON U.COD_UNIVEND=T.COD_UNIVEND
							WHERE T.COD_EMPRESA = $cod_empresa
							AND U.LOG_ESTATUS != 'N' 
							AND COD_PLAYERS = $cod_players";

		// echo $sqlPlayer;

		$arrayQueryPlayer = mysqli_query(connTemp($cod_empresa, ''), $sqlPlayer);
		$qrBuscaTotemPlayer = mysqli_fetch_assoc($arrayQueryPlayer);

		if (isset($qrBuscaTotemPlayer)) {

			$log_ticket = $qrBuscaTotemPlayer['LOG_TICKET'];
			$log_nps = $qrBuscaTotemPlayer['LOG_NPS'];
		    $val_inativo = $qrBuscaTotemPlayer['VAL_INATIVO'];
		    $des_paghome = $qrBuscaTotemPlayer['DES_PAGHOME'];

		    $idlojaKey = $qrBuscaTotemPlayer['COD_UNIVEND'];
			$idmaquinaKey = 0;
			$codvendedorKey = 0;
			$nomevendedorKey = 0;

			$urltotem = fnEncode($log_usuario.';'
						.$des_senhaus.';'
						.$idlojaKey.';'
						.$idmaquinaKey.';'
						.$cod_empresa.';'
						.$codvendedorKey.';'
						.$nomevendedorKey.';'
						.$qrBuscaTotemPlayer['COD_PLAYERS']
			);

			// echo($log_usuario);

			$des_paghome = $qrBuscaTotemPlayer['DES_PAGHOME'];
			$destinoHome = "";

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

	}else{
		$cod_players = 0;	
	}

	//se carrega site
	if ($siteGo == "OK"){
		
		//fnEscreve($siteGo);
		//fnEscreve($cod_empresa);
		
		//busca nome da empresa
		$sql2 = "select NOM_FANTASI from EMPRESAS WHERE COD_EMPRESA = $cod_empresa ";
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql2);
		$qrBuscaDadosEmpresa = mysqli_fetch_assoc($arrayQuery);
        $nom_fantasi = $qrBuscaDadosEmpresa['NOM_FANTASI'];

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

		// echo $cod_cliente;
	
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
			
			/* modal */								
			.modal-dialog {
				width: 40%;
				max-width: 1080px;
				margin-top: 10px;
				margin-bottom: 10px;
				height: 500px;
			}
			
			.modal-content {
				height: 700px;
			}			

			iframe {
			  display: block;
			  margin: 0 auto;
			} 

			.modal-body {
				position: relative;
				padding: 20px;
				height: 700px;
			}			
			
			#contato-info{
				color: green;
				margin-top: 25px;
				text-align: center;
			}
			
			.bloco {
				padding: 30px 0;
			}	

			/****** Style Star Rating Widget *****/
			
			.rating > label { 
				font-size: 40px;
			}			

			.rating.rate10 { 
				border: none;
				float: left;
				width: 585px; 
			}
			
			.rating.rate5 { 
			  border: none;
			  float: left;
			  width: 430px;
			}	

			.rating > input { display: none; } 
			.rating > label:before { 
			  margin: 5px;
			  font-size: 1.25em;
			  font-family: FontAwesome;
			  display: inline-block;
			  content: "\f005";
			}
			
			.rating > label.radioType:before { 
			  margin: 5px;
			  font-size: 1.25em;
			  font-family: FontAwesome;
			  display: inline-block;
			  content: "\f192";
			}	

			.rating > .half:before { 
			  content: "\f089";
			  position: absolute;
			}

			.rating > label { 
			  color: #ddd; 
			  float: right; 
			}

			/***** CSS Magic to Highlight Stars on Hover *****/

			.rating > input:checked ~ label, /* show gold star when clicked */
			.rating:not(:checked) > label:hover, /* hover current star */
			.rating:not(:checked) > label:hover ~ label { color: #FFD700;  } /* hover previous stars in list */
			
			.rating > input:checked ~ label.radioType, /* show gold star when clicked */
			.rating:not(:checked) > label.radioType:hover, /* hover current star */
			.rating:not(:checked) > label.radioType:hover ~ label { color: #4286f4;  } /* hover previous stars in list */	

			.rating > input:checked + label:hover, /* hover current star when changing rating */
			.rating > input:checked ~ label:hover,
			.rating > label:hover ~ input:checked ~ label, /* lighten current selection */
			.rating > input:checked ~ label:hover ~ label { color: #FFED85;  } 
			
			.rating > input:checked + label.radioType:hover, /* hover current star when changing rating */
			.rating > input:checked ~ label.radioType:hover,
			.rating > label:hover ~ input.radioType:checked ~ label, /* lighten current selection */
			.rating > input:checked ~ label.radioType:hover ~ label { color:  #87b2f8;  } 		

			hr{
				width: 100%;
				border-top: 2px solid #161616;
			}
			
			hr.divisao{
				width: 100%;
				border-top: 1px dashed #cecece;
				margin: 5px 0;
			}	

			#footer {
				position: fixed;
				bottom: 0;
				width: 100%;				
			}
			
			.numero{
				font-size: 16px;
				margin-top: -40px;
			}		
			
			@media only screen and (min-width: 761px) and (max-width: 1281px) { /* 10 inch tablet enter here */
				.lead.titulo {
					margin-top: 50px;
				}
			} 			
			
			@media only screen and (max-width: 760px) {
				/* For mobile phones: */
				section#contact {
					padding: 10px 0;
				}
				
				.lead {
					margin-bottom: 10px;
				}				
				
				#footer .bottom-menu, #footer .bottom-menu-inverse {
					padding: 10px 0 0;
					height: 60px;
				}
				
				.rating > label { 
					font-size: 15px;
				}					
				
				.rating.rate10 { 
					border: none;
					float: left;
					width: 315px;
				}	
				.numero {
					margin-top: -10px;
				}				
			}			
						
			
			/*.input-hg {
				background-color: transparent !important;
				border-bottom: 2px solid #4d4d4d !important;	
				border-radius: 0;				
			}
			
			.input-hg:focus {
				border-bottom-color: #48c9b0 !important;
			}*/

			section{
				padding-top: 150px;
				background: #<?=$cor_site?>!important;
			}

			.info-section-white{
				background: #<?=$cor_site?>!important;
			}

			.WordSection1{
				background: #<?=$cor_site?>!important;
			}

			.navbar, .navbar-inner{
				background: #<?=$cor_barra?>!important;
			}

			h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6 {
				color: #<?php echo $cor_titulos; ?>;
			}			
			
			p, p.lead  {
				color: #<?php echo $cor_textos; ?>;
			}
			.navbar .nav > li > a{
				color: #<?php echo $cor_txtbarra; ?>;
			}
			.btn-primary {
				background-color: #<?php echo $cor_botao; ?>;
			}
			.btn-primary:hover {
				background-color: #<?php echo $cor_botaoon; ?>;
			}

			.btn-scale {
				  min-width: 34px;
				  width: 5%;
				  text-align: center;
				  font-weight: bold;
				  color: black;
				  font-family: 'Lato', sans-serif;
				}

				.btn-scale-desc-0, .btn-scale-asc-10 {
				  background-color: #F44336;
				}

				.btn-scale-desc-0:hover,
				.btn-scale-asc-10:hover {
				  background-color: #F44336;
				}

				.btn-scale-desc-1, .btn-scale-asc-9 {
				  background-color: #FF5722;
				}

				.btn-scale-desc-1:hover,
				.btn-scale-asc-9:hover {
				  background-color: #FF5722;
				}

				.btn-scale-desc-2,
				.btn-scale-asc-8{
				  background-color: #FF9800;
				}

				.btn-scale-desc-2:hover,
				.btn-scale-asc-8:hover{
				  background-color: #FF9800;
				}

				.btn-scale-desc-3,
				.btn-scale-asc-7 {
				  background-color: #FFC107;
				}

				.btn-scale-desc-3:hover,
				.btn-scale-asc-7:hover {
				  background-color: #FFC107;
				}

				.btn-scale-desc-4,
				.btn-scale-asc-6 {
				  background-color: #FDD835;
				}

				.btn-scale-desc-4:hover,
				.btn-scale-asc-6:hover {
				  background-color: #FDD835;
				}

				.btn-scale-desc-5,
				.btn-scale-asc-5 {
				  background-color: #FFEB3B;
				}

				.btn-scale-desc-5:hover,
				.btn-scale-asc-5:hover {
				  background-color: #FFEB3B;
				}

				.btn-scale-desc-6,
				.btn-scale-asc-4 {
				  background-color: #EEFF41;
				}

				.btn-scale-desc-6:hover,
				.btn-scale-asc-4:hover {
				  background-color: #EEFF41;
				}

				.btn-scale-desc-7,
				.btn-scale-asc-3 {
				  background-color: #C6FF00;
				}

				.btn-scale-desc-7:hover,
				.btn-scale-asc-3:hover {
				  background-color: #C6FF00;
				}

				.btn-scale-desc-8,
				.btn-scale-asc-2 {
				  background-color: #AEEA00;
				}

				.btn-scale-desc-8:hover,
				.btn-scale-asc-2:hover {
				  background-color: #AEEA00;
				}

				.btn-scale-desc-9,
				.btn-scale-asc-1 {
				  background-color: #64DD17;
				}

				.btn-scale-desc-9:hover,
				.btn-scale-asc-1:hover {
				  background-color: #64DD17;
				}

				.btn-scale-desc-10,
				.btn-scale-asc-0 {
				  background-color: #00C853;
				}

				.btn-scale-desc-10:hover,
				.btn-scale-asc-0:hover {
				  background-color: #00C853;
				}

				input{
					border-radius: 35px!important;
					border: 0px!important;
			        -webkit-box-shadow: 0px 5px 8px 0px rgba(<?=$r?>,<?=$g?>,<?=$b?>,0.8);
					-moz-box-shadow: 0px 5px 8px 0px rgba(<?=$r?>,<?=$g?>,<?=$b?>,0.8);
					box-shadow: 0px 5px 8px 0px rgba(<?=$r?>,<?=$g?>,<?=$b?>,0.8);
			        width: 100%;
			        color: <?=$cor_textos?>;
				}

				button{
					border-radius: 35px!important;
				}

				.checked{
					background:#CCC;
				}

				.icon_negativo,.icon_positivo{
					border:2px solid #EEE;
					border-bottom-width:4px;
					padding:4px 12px;
					border-radius: 15px;
				}
				.icon_negativo{
					border-right-width:1px;
					border-top-right-radius:0;
					border-bottom-right-radius:0;
					padding-left:16px;
				}
				.icon_positivo{
					border-left-width:1px;
					border-top-left-radius:0;
					border-bottom-left-radius:0;
					padding-right:16px;
				}
				.icon_negativo i{
					color:#dc3545;
				}
				.icon_positivo i{
					color:#28a745;
				}
				.icon_negativo.checked i,.icon_positivo.checked i{
					color:#FFF;
				}
				.icon_negativo.checked{
					background-color:#dc3545 !important;
					border-color:#dc3545 !important;
				}
				.icon_positivo.checked{
					background-color:#28a745 !important;
					border-color:#28a745 !important;
				}
		</style>		
	  
		
		<!-- Scrollspy set in the body -->
		<body id="home" data-spy="scroll" data-target=".main-nav" data-offset="73">
		<input type="hidden" id="COD_CLIENTE" name="COD_CLIENTE" value="">
		<input type="hidden" id="COD_CLIENTE_AJAX" name="COD_CLIENTE_AJAX" value="<?=fnEncode($cod_cliente)?>">
		<input type="hidden" id="COD_EMPRESA_AJAX" name="COD_EMPRESA_AJAX" value="<?=$cod_empresa?>">
		<input type="hidden" id="COD_PLAYERS_AJAX" name="COD_PLAYERS_AJAX" value="<?=$cod_players?>">
		<input type="hidden" id="COD_PESQUISA" name="COD_PESQUISA" value="<?=$cod_pesquisa?>">


		<div id="parallax"></div>
		
		<!--/////////////////////////////////////// NAVIGATION BAR ////////////////////////////////////////-->
		<?php if($cod_players == 0){ ?>
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

		<?php } ?>

		<!--/////////////////////////////////////// CONTACT SECTION ////////////////////////////////////////-->
		
		<section id="contact">

			<div class="container">
				<header>
					<h4 class="lead titulo"><b>Pesquisa <?php echo $des_programa; ?></b></h4>
				</header>
				<hr class="divisao"/>
				<div class="row">
					<div class="col-md-8 col-md-offset-2">
						<div id="blocoPesquisa">
							
						</div>
					</div><!-- /col-md-6-->
				</div>
			</div>

		</section>
		

		<div style="height: 80px; clear:both;"></div>

		<?php if($cod_players == 0){ ?>

		<!--//////////////////////////////////////// FOOTER SECTION ////////////////////////////////////////-->
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

		<?php } ?>
		
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

		<script type="text/javascript">

			let totem = "<?=$totem?>",
				valorEstrelas = -1,
			    pCodPesquisa = 0,
			    codPesquisa = '<?=fnLimpaCampoZero($cod_pesquisa)?>',
			    cliente_ajax = $("#COD_CLIENTE_AJAX").val(),
			    empresa_ajax = $("#COD_EMPRESA_AJAX").val();
			// var player = "<?=$cod_players?>";

			$(document).ready( function() {

				// <?php if ($val_inativo != "0" && $val_inativo != ""){ ?>
				// 	var timer;

				// 	// alert(<?=$val_inativo?>);

				// 	window.onload= document.onmousemove= document.onkeypress= function(){
				// 		clearTimeout(timer);
				// 		timer=setTimeout(function(){window.top.location.href = 'http://totem.bunker.mk/<?php echo $destinoTotem; ?>?key=<?php echo $urltotem ;?>'},<?php echo $val_inativo ;?>000);
				// 	}	

				// 	window.addEventListener('touchstart', function() {
				// 		clearTimeout(timer);
				// 		timer=setTimeout(function(){window.top.location.href = 'http://totem.bunker.mk/<?php echo $destinoTotem; ?>?key=<?php echo $urltotem ;?>'},<?php echo $val_inativo ;?>000);
				// 	});
				
				// <?php } ?>
		
				// $('.cpf').mask('000.000.000-00', {reverse: true});
				// mascaraCpfCnpj($(".cpfcnpj"));
		
				
				
				if(codPesquisa == "" || codPesquisa == 0){
					ajxListarPesquisas(cliente_ajax);
					// alert('if');				
				}else{
					ajxIniciarPesquisas(codPesquisa);				
				}
				
				$('body').on('click', '.btnVoltarLista', function() {
					ajxListarPesquisas("<?=fnEncode($cod_cliente)?>");
				});				

				$('body').on('click', '.iniciarPesquisa', function() {
					var cliente_ajax = $("#COD_CLIENTE_AJAX").val();
					pCodPesquisa = $(this).attr('cod-pesquisa');
					// alert(pCodPesquisa);
					ajxIniciarPesquisas(pCodPesquisa);
					$("#COD_PESQUISA").val(pCodPesquisa);			
				});	
				
				$('body').on('click', '.btnNota', function() {
					valorEstrelas = $(this).attr('valor');			
				});					

				$('body').on('click', '.btnContinuar', function() {
					var tipoBloco = $(this).attr('cod-blpesqu');
					var pRespostaNumero = 0;
					var pRespostaTexto = "";
					console.log(pRespostaTexto);

					//alert(tipoBloco);

					// BLOCO TEXTO
					if(tipoBloco == 1){
						proximoBlocoSemSalvar($(this));
					// BLOCO PERGUNTA
					}else if(tipoBloco == 2){
						var obrigatorio = ($('.respostaPergunta').attr("tp-resp") != "TO");
						if(($('.respostaPergunta').val() != '') || ($('.respostaPergunta').val() == '' && obrigatorio != true)){
							pRespostaTexto = $('.respostaPergunta').val();
							proximoBloco($(this), pRespostaTexto, pRespostaNumero);
						}else{
							$.alert({
								title: 'Atenção!',
								content: 'Digite a resposta para continuar!',
							});							
						}
						
					// BLOCO SALDO
					}else if(tipoBloco == 3){
						proximoBlocoSemSalvar($(this));
					// BLOCO IMAGEM
					}else if(tipoBloco == 4){
						proximoBlocoSemSalvar($(this));
					// BLOCO AVALIAÇÃO
					}else if(tipoBloco == 5){
						if (valorEstrelas < 0){
							$.alert({
								title: 'Atenção!',
								content: 'Escolha uma avaliação para continuar!',
							});
						}else{
							proximoBlocoAvalicao($(this), pRespostaTexto, valorEstrelas);
						}
					// BLOCO LOGIN SEM SENHA
					}else if(tipoBloco == 6){
						// alert('login s/ senha: click');
						proximoBlocoLogin($(this));
					// BLOCO LOGIN COM SENHA
					}else if(tipoBloco == 7){
						proximoBlocoLogin($(this));
					//BLOCO SMART LOGIN
					}else if(tipoBloco == 8){
						proximoBlocoSemSalvar($(this));
					}
				});

				$('body').on('click', '.btnContinuarCondicao', function() {
					var tipoBloco = $(this).attr('cod-blpesqu');
					var pRespostaNumero = 0;
					var pRespostaTexto = "";

					//alert(tipoBloco);

					// BLOCO TEXTO
					if(tipoBloco == 1){
						proximoBlocoSemSalvarCondicao($(this));
					// BLOCO PERGUNTA
					}else if(tipoBloco == 2){
						var obrigatorio = ($('.respostaPergunta').attr("tp-resp") != "TO");
						if(($('.respostaPergunta').val() != '') || ($('.respostaPergunta').val() == '' && obrigatorio != true)){
							pRespostaTexto = $('.respostaPergunta').val();
							proximoBlocoCondicao($(this), pRespostaTexto, pRespostaNumero);
						}else{
							$.alert({
								title: 'Atenção!',
								content: 'Digite a resposta para continuar!',
							});							
						}
						
					// BLOCO SALDO
					}else if(tipoBloco == 3){
						proximoBlocoSemSalvarCondicao($(this));
					// BLOCO IMAGEM
					}else if(tipoBloco == 4){
						proximoBlocoSemSalvarCondicao($(this));
					// BLOCO AVALIAÇÃO
					}else if(tipoBloco == 5){
						proximoBlocoAvalicao($(this), pRespostaTexto, valorEstrelas);
					// BLOCO LOGIN SEM SENHA
					}else if(tipoBloco == 6){
						// alert('login s/ senha: click');
						proximoBlocoLogin($(this));
					// BLOCO LOGIN COM SENHA
					}else if(tipoBloco == 7){
						proximoBlocoLogin($(this));
					//BLOCO SMART LOGIN
					}else if(tipoBloco == 8){
						proximoBlocoSemSalvarCondicao($(this));
					}
				});	
			});

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
			
			function verificarNPS(valor){
				if(valor <= 6){
					return 1;
				}else if(valor > 6 && valor <= 8){
					return 2;
				}else if(valor > 8 && valor <= 10){
					return 3;
				}
			}
			
			function ajxIniciarPesquisas(pCodPesquisa){
				var cliente_ajax = $("#COD_CLIENTE_AJAX").val();
				var empresa_ajax = $("#COD_EMPRESA_AJAX").val();
				var player = $("#COD_PLAYERS_AJAX").val();
				//alert(pCodPesquisa);
				console.log("https://adm.bunker.mk/ticket/ajxBlocoPesquisa.do?opcao=iniciarPesquisa&plataforma="+detectar_mobile()+"&cod_pesquisa="+pCodPesquisa+"&cod_empresa="+empresa_ajax+"&cod_cliente="+cliente_ajax+"&log_totem="+totem+"&COD_PLAYERS="+player+"&cod_cliente_totem="+cliente_ajax);
				$.ajax({
					type: "GET",
					url: "https://adm.bunker.mk/ticket/ajxBlocoPesquisa.do",
					data: { opcao: 'iniciarPesquisa', plataforma: detectar_mobile(), cod_pesquisa: pCodPesquisa, cod_empresa: empresa_ajax, cod_cliente: cliente_ajax, log_totem: totem, COD_PLAYERS: player, LOG_HOTSITE: <?=$log_hotsite?>, cod_cliente_totem: cliente_ajax },
					beforeSend:function(){
						$('#blocoPesquisa').html('<div class="loading" style="width: 100%;"></div>');
						// alert(<?php echo $cod_empresa; ?>);
					},						
					success: function(data) {
						$('#blocoPesquisa').html(data);
						console.log("ajxIniciarPesquisas");
					}
				});				
			}
			
			function ajxListarPesquisas(idCliente){
				var empresa_ajax = $("#COD_EMPRESA_AJAX").val();
				var cliente_ajax = $("#COD_CLIENTE_AJAX").val();
				var player = $("#COD_PLAYERS_AJAX").val();
				console.log("https://adm.bunker.mk/ticket/ajxBlocoPesquisa.do?opcao=listarPesquisas&cod_cliente="+cliente_ajax+"&plataforma="+detectar_mobile()+"&cod_empresa="+empresa_ajax+"&COD_PLAYERS="+player+"&cod_cliente_totem="+cliente_ajax);
				$.ajax({
					type: "GET",
					url: "https://adm.bunker.mk/ticket/ajxBlocoPesquisa.do",
					data: { opcao: 'listarPesquisas', cod_cliente: cliente_ajax, plataforma: detectar_mobile(), cod_empresa: empresa_ajax, COD_PLAYERS: player, LOG_HOTSITE: <?=$log_hotsite?>, cod_cliente_totem: cliente_ajax },
					beforeSend:function(){
						$('#blocoPesquisa').html('<div class="loading" style="width: 100%;"></div>');
					},					
					success: function(data) {
						console.log(data);
						if(!isNaN(data)){
							ajxIniciarPesquisas(data);
						}else{
							$('#blocoPesquisa').html(data);
							console.log("ajxListarPesquisas");
						}
					}
				});				
			}
			
			function proximoBlocoLogin(_this){
				var empresa_ajax = $("#COD_EMPRESA_AJAX").val();
				var cliente_ajax = $("#COD_CLIENTE_AJAX").val();
				var player = $("#COD_PLAYERS_AJAX").val();
				var pCodRegistro = _this.attr('cod-registro');
				var pCpf = $('#cpf').val();
				var pCelular = $('#celular').val() == undefined ? "" : $('#celular').val();
				var pEmail = $('#email').val() == undefined ? "" : $('#email').val();
				var pSenha = $('#senha').val() == undefined ? "" : $('#senha').val();
				var cod_pesquisa_ajax = $("#COD_PESQUISA").val();
				
				$.ajax({
					type: "GET",
					url: "https://adm.bunker.mk/ticket/ajxBlocoPesquisa.do",
					data: { opcao: 'login', cod_registro: pCodRegistro, cpf: pCpf, email: pEmail, celular: pCelular, senha: pSenha, cod_empresa: empresa_ajax, cod_pesquisa: cod_pesquisa_ajax, COD_PLAYERS: player, LOG_HOTSITE: <?=$log_hotsite?>, cod_cliente_totem: cliente_ajax},
					beforeSend:function(){
						$('.loading').show();
						$('.btnContinuar').hide();
					},						
					success: function(data) {
						console.log("proximoBlocoLogin");
						console.log(data);
						if(data == 0){
							$('.errorLogin').show();
							$('.loading').hide();
							$('.btnContinuar').show();
						}else{
							$('#blocoPesquisa').html(data);
						}
					},
					error:function(data){
						alert('login s/ senha: erro ajax');
						console.log(data);
					}
				});				
			}			
			
			function proximoBlocoAvalicao(_this, pRespostaTexto, pRespostaNumero){
				var empresa_ajax = $("#COD_EMPRESA_AJAX").val();
				var cliente_ajax = $("#COD_CLIENTE_AJAX").val();
				var player = $("#COD_PLAYERS_AJAX").val();
				var pCodOrdenacao = parseInt(_this.attr('cod-ordenacao'));
				var pCodPesquisa = _this.attr('cod-pesquisa');
				var pCodRegistro = _this.attr('cod-registro');
				var pCodModPesquisa = _this.attr('cod-modpesquisa');					
				var pCondicoes =  JSON.parse($('.log_condicoes').val());
				var pNPS = verificarNPS(pRespostaNumero);
				
				$.ajax({
					type: "GET",
					async: false,
					url: "https://adm.bunker.mk/ticket/ajxBlocoPesquisa.do",
					data: { opcao: 'salvar', cod_cliente: getCodCliente(), nps: pNPS, cod_registro: pCodRegistro, cod_pesquisa: pCodPesquisa, cod_modpesquisa: pCodModPesquisa, resposta_numero: pRespostaNumero, resposta_texto: pRespostaTexto, cod_ordenacao: pCodOrdenacao, cod_empresa: empresa_ajax, COD_PLAYERS: player, LOG_HOTSITE: <?=$log_hotsite?>, cod_cliente_totem: cliente_ajax },
					success: function(data) {
						console.log("salvar");
						console.log(data);

					}
				});

				var blocoParaIr = 0;
				var pBlocosCodigo = "";
				var pResultadoLoopAnterior = 0;
				var pBlocoIrAnterior = 0;
				var pCodCondicao = 0;
				var pCodCondicaoAnterior = 0;
				
				pCondicoes.forEach(function(objeto) {

					if(objeto.condicaoAvalicao == '='){
						// console.log(objeto.condicaoAvalicao);
						if(pRespostaNumero == objeto.resultado){
							blocoParaIr = objeto.blocoIrAvaliacao;
							pCodCondicao = objeto.codCondicao;
							pBlocosCodigo += objeto.blocoIrAvaliacao + ",";
						}
					}
					else if(objeto.condicaoAvalicao == '<='){
						// console.log(objeto.blocoIrAvaliacao);
						if(Number(pRespostaNumero) <= Number(objeto.resultado)){
							if(Number(objeto.resultado) < Number(pResultadoLoopAnterior) || Number(pResultadoLoopAnterior) == 0){
								// alert('if');
								blocoParaIr = objeto.blocoIrAvaliacao;
								pCodCondicao = objeto.codCondicao;
								pResultadoLoopAnterior = objeto.resultado;
								pBlocoIrAnterior = objeto.blocoIrAvaliacao;
								pCodCondicaoAnterior = objeto.codCondicao;
							}else{
								// alert('else');
								blocoParaIr = pBlocoIrAnterior;
								pCodCondicao = pCodCondicaoAnterior;
							}
							pBlocosCodigo += objeto.blocoIrAvaliacao + ",";
							return;
						}						
					}
					else{
						// console.log(objeto.condicaoAvalicao);
						if(Number(pRespostaNumero) >= Number(objeto.resultado)){
							if(Number(objeto.resultado) > Number(pResultadoLoopAnterior) || Number(pResultadoLoopAnterior == 0)){
								blocoParaIr = objeto.blocoIrAvaliacao;
								pCodCondicao = objeto.codCondicao;
								pResultadoLoopAnterior = objeto.resultado;
								pBlocoIrAnterior = objeto.blocoIrAvaliacao;
								pCodCondicaoAnterior = objeto.codCondicao;
							}else{
								blocoParaIr = pBlocoIrAnterior;
								pCodCondicao = pCodCondicaoAnterior;
							}
							pBlocosCodigo += objeto.blocoIrAvaliacao + ",";
							return;
						}					
					}



					// console.log("resposta dada: "+pRespostaNumero);
					// console.log("condição gravada: "+objeto.condicaoAvalicao);
					// console.log("resultado gravado: "+objeto.resultado);
					// console.log("destino gravado: "+objeto.blocoIrAvaliacao);

					// return false;
				});

				pBlocosCodigo = pBlocosCodigo.slice(0, -1);

				if(pBlocosCodigo.trim() != ""){
					var empresa_ajax = $("#COD_EMPRESA_AJAX").val();
					var cliente_ajax = $("#COD_CLIENTE_AJAX").val();
					$.ajax({
						type: "GET",
						url: "https://adm.bunker.mk/ticket/ajxBlocoPesquisa.do",
						data: { opcao: 'proximoBlocoAvaliacao', blocosCodigo: pBlocosCodigo, bloco_ir: blocoParaIr, cod_registro: pCodRegistro, cod_pesquisa: pCodPesquisa, cod_empresa: empresa_ajax, resposta_numero: pRespostaNumero, cod_modpesquisa: pCodModPesquisa, codCondicao: pCodCondicao, LOG_HOTSITE: <?=$log_hotsite?>, cod_cliente_totem: cliente_ajax},
						beforeSend:function(){
							$('#blocoPesquisa').html('<div class="loading" style="width: 100%;"></div>');
						},						
						success: function(data) {
							console.log("proximoBlocoAvalicao");
							$('#blocoPesquisa').html(data);
						}
					});					
				}else{
					proximoBlocoSemSalvar(_this);
				}
				// alert(pCodCondicao);
			}			
			
			function proximoBlocoSemSalvar(_this){
				var empresa_ajax = $("#COD_EMPRESA_AJAX").val();
				var cliente_ajax = $("#COD_CLIENTE_AJAX").val();
				var pCodOrdenacao = parseInt(_this.attr('cod-ordenacao'));
				var pCodPesquisa = _this.attr('cod-pesquisa');
				var pCodRegistro = _this.attr('cod-registro');
				var player = $("#COD_PLAYERS_AJAX").val();					
				$.ajax({
					type: "GET",
					url: "https://adm.bunker.mk/ticket/ajxBlocoPesquisa.do",
					data: { opcao: 'proximoBlocoPesquisa', cod_registro: pCodRegistro, cod_pesquisa: pCodPesquisa, cod_ordenacao: pCodOrdenacao, cod_empresa: empresa_ajax, COD_PLAYERS: player, LOG_HOTSITE: <?=$log_hotsite?>, cod_cliente_totem: cliente_ajax },
					beforeSend:function(){
						$('#blocoPesquisa').html('<div class="loading" style="width: 100%;"></div>');
					},						
					success: function(data) {
						$('#blocoPesquisa').html(data);
						console.log("proximoBlocoSemSalvar");
					}
				});				
			}

			function proximoBlocoSemSalvarCondicao(_this){
				var empresa_ajax = $("#COD_EMPRESA_AJAX").val();
				var cliente_ajax = $("#COD_CLIENTE_AJAX").val();
				var pCodOrdenacao = parseInt(_this.attr('cod-ordenacao'));
				var pCodPesquisa = _this.attr('cod-pesquisa');
				var pCodRegistro = _this.attr('cod-registro');						
				var pCodAvaliacao = _this.attr('cod-avaliacao');
				var pCodModPesquisa = _this.attr('cod-modpesquisa');
				var player = $("#COD_PLAYERS_AJAX").val();
				$.ajax({
					type: "GET",
					url: "https://adm.bunker.mk/ticket/ajxBlocoPesquisa.do",
					data: { opcao: 'proximoBlocoCondicao', cod_registro: pCodRegistro, cod_modpesquisa: pCodModPesquisa, cod_avaliacao: pCodAvaliacao, cod_pesquisa: pCodPesquisa, cod_ordenacao: pCodOrdenacao, cod_empresa: empresa_ajax, COD_PLAYERS: player, LOG_HOTSITE: <?=$log_hotsite?>, cod_cliente_totem: cliente_ajax },
					beforeSend:function(){
						$('#blocoPesquisa').html('<div class="loading" style="width: 100%;"></div>');
					},						
					success: function(data) {
						$('#blocoPesquisa').html(data);
						console.log("proximoBlocoSemSalvarCondicao");
					}
				});				
			}
			
			function proximoBloco(_this, pRespostaTexto, pRespostaNumero){
				var empresa_ajax = $("#COD_EMPRESA_AJAX").val();
				var cliente_ajax = $("#COD_CLIENTE_AJAX").val();
				var player = $("#COD_PLAYERS_AJAX").val();
				var pCodOrdenacao = parseInt(_this.attr('cod-ordenacao'));
				var pCodPesquisa = _this.attr('cod-pesquisa');
				var pCodRegistro = _this.attr('cod-registro');
				var pCodModPesquisa = _this.attr('cod-modpesquisa');					
				
				$.ajax({
					type: "GET",
					async: false,
					url: "https://adm.bunker.mk/ticket/ajxBlocoPesquisa.do",
					data: { opcao: 'salvar', cod_cliente: getCodCliente(), nps: 0, cod_registro: pCodRegistro, cod_pesquisa: pCodPesquisa, cod_modpesquisa: pCodModPesquisa, resposta_numero: pRespostaNumero, resposta_texto: pRespostaTexto, cod_ordenacao: pCodOrdenacao, cod_empresa: empresa_ajax, COD_PLAYERS: player, LOG_HOTSITE: <?=$log_hotsite?>, cod_cliente_totem: cliente_ajax },
					success: function(data) {
						console.log("proximoBloco_A");
						console.log(data);
					}
				})					

				$.ajax({
					type: "GET",
					url: "https://adm.bunker.mk/ticket/ajxBlocoPesquisa.do",
					async: false,
					data: { opcao: 'proximoBlocoPesquisa', cod_registro: pCodRegistro, cod_pesquisa: pCodPesquisa, cod_ordenacao: pCodOrdenacao, cod_empresa: empresa_ajax, LOG_HOTSITE: <?=$log_hotsite?> },
					beforeSend:function(){
						$('#blocoPesquisa').html('<div class="loading" style="width: 100%;"></div>');
					},						
					success: function(data) {
						$('#blocoPesquisa').html(data);
						console.log("proximoBloco_B");
						console.log(data);
					}
				});					
			}

			function proximoBlocoCondicao(_this, pRespostaTexto, pRespostaNumero){
				var empresa_ajax = $("#COD_EMPRESA_AJAX").val();
				var cliente_ajax = $("#COD_CLIENTE_AJAX").val();
				var player = $("#COD_PLAYERS_AJAX").val();
				var pCodOrdenacao = parseInt(_this.attr('cod-ordenacao'));
				var pCodPesquisa = _this.attr('cod-pesquisa');
				var pCodRegistro = _this.attr('cod-registro');
				var pCodAvaliacao = _this.attr('cod-avaliacao');
				var pCodModPesquisa = _this.attr('cod-modpesquisa');					

				$.ajax({
					type: "GET",
					async: false,
					url: "https://adm.bunker.mk/ticket/ajxBlocoPesquisa.do",
					data: { opcao: 'salvar', cod_cliente: getCodCliente(), nps: 0, cod_registro: pCodRegistro, cod_pesquisa: pCodPesquisa, cod_modpesquisa: pCodModPesquisa, resposta_numero: pRespostaNumero, resposta_texto: pRespostaTexto, cod_ordenacao: pCodOrdenacao, cod_empresa: empresa_ajax, COD_PLAYERS: player, LOG_HOTSITE: <?=$log_hotsite?>, cod_cliente_totem: cliente_ajax },
					success: function(data) {
						console.log("proximoBlocoCondicao_A");
						console.log(data);
					}
				})					

				$.ajax({
					type: "GET",
					url: "https://adm.bunker.mk/ticket/ajxBlocoPesquisa.do",
					async: false,
					data: { opcao: 'proximoBlocoCondicao', cod_registro: pCodRegistro, cod_modpesquisa: pCodModPesquisa, cod_avaliacao: pCodAvaliacao, cod_pesquisa: pCodPesquisa, cod_ordenacao: pCodOrdenacao, cod_empresa: empresa_ajax, LOG_HOTSITE: <?=$log_hotsite?> },
					beforeSend:function(){
						$('#blocoPesquisa').html('<div class="loading" style="width: 100%;"></div>');
					},						
					success: function(data) {
						$('#blocoPesquisa').html(data);
						console.log("proximoBlocoCondicao_B");
						console.log(data);
					}
				});					
			}	

			function getCodCliente(){
				return $('#COD_CLIENTE').val();
			}
			
			function detectar_mobile() { 
				var mobile = (/iphone|ipad|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));
				if(totem == 'N'){
					if (mobile) {
						var userAgent = navigator.userAgent.toLowerCase();
						if ((userAgent.search("android") > -1 || userAgent.search("iphone") > -1) && (userAgent.search("mobile") > -1)){
							return 2;
						}else if ((userAgent.search("android") > -1 || userAgent.search("ipad") > -1) && !(userAgent.search("mobile") > -1)){
							return 3;
						}
					} else {
						return 1;		
					}	
				}else{
					return 4;
				}
			}		

			function gravaOpcao(obj,rsp){
				var r = {};
				$("[data-name="+obj+"]").removeClass("checked");
				$("[name="+obj+"]:checked" ).each(function( index ) {
					r[$(this).val()] = $(this).attr("text");
					$("[data-value="+$(this).val()+"]").addClass("checked");
				});
				var v = JSON.stringify(r);
				if (v == "{}"){
					v = "";
				}
				$(rsp).val(v);
			}
			function clicaBloco(obj,rsp,val){
				//$("[name="+obj+"]").attr("checked",false);
				
				$("[name="+obj+"][value="+val+"]").prop('checked',!$("[name="+obj+"][value="+val+"]").prop('checked'));
				gravaOpcao(obj,rsp);
			}
			
			function clicaBlocoAvaliacao(obj,rsp,clk){
				var ident = $(clk).attr("data-id");
				if ($(clk).hasClass("checked")){
					$("[data-id="+ident+"]").removeClass("checked");
				}else{
					$("[data-id="+ident+"]").removeClass("checked");
					$(clk).addClass("checked");
				}
				
				var r = {};
				$("[name="+obj+"]" ).each(function( index ) {
					if ($(this).hasClass("checked")){
						r[$(this).attr("data-id")] = {"texto":$(this).attr("data-text"),"opcao":$(this).attr("data-tp")};
						//alert($(this).attr("data-id"));
					}
				});
				var v = JSON.stringify(r);
				if (v == "{}"){
					v = "";
				}
				$(rsp).val(v);
			}
		</script>
		

	  </body>
	</html>

	<?php } ?>