<?php

include "../_system/_functionsMain.php";


//echo "<h1>".$_GET['param']."</h1>";

	//echo fnDebug('true');

	
	//busca dados da url	
	if (fnLimpacampo($_GET['param']) != ""){
		//busca codigo da empresa
		//$cod_busca = strtolower(fnLimpacampo($_GET['param']));	
		$cod_busca = "modelo";
		$sql = "select COD_EMPRESA from DOMINIO WHERE DES_DOMINIO = '$cod_busca' ";
               // fnEscreve($sql);
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
			$log_vantagem = $qrBuscaSiteExtrato['LOG_VANTAGEM'];
			$txt_vantagem = $qrBuscaSiteExtrato['TXT_VANTAGEM'];
			$log_regula = $qrBuscaSiteExtrato['LOG_REGULA'];
			$txt_regula = $qrBuscaSiteExtrato['TXT_REGULA'];
			$log_lojas = $qrBuscaSiteExtrato['LOG_LOJAS'];
			$txt_lojas = $qrBuscaSiteExtrato['TXT_LOJAS'];
			$log_faq = $qrBuscaSiteExtrato['LOG_FAQ'];
			$txt_faq = $qrBuscaSiteExtrato['TXT_FAQ'];
			$log_extrato = $qrBuscaSiteExtrato['LOG_EXTRATO'];
			$txt_extrato = $qrBuscaSiteExtrato['TXT_EXTRATO'];
			$log_contato = $qrBuscaSiteExtrato['LOG_CONTATO'];
			$txt_contato = $qrBuscaSiteExtrato['TXT_CONTATO'];
			$cor_titulos = $qrBuscaSiteExtrato['COR_TITULOS'];
			$cor_textos = $qrBuscaSiteExtrato['COR_TEXTOS'];
			$cor_rodapebg = $qrBuscaSiteExtrato['COR_RODAPEBG'];
			$cor_rodape = $qrBuscaSiteExtrato['COR_RODAPE'];
			$cor_botao = $qrBuscaSiteExtrato['COR_BOTAO'];
			$cor_botaoon = $qrBuscaSiteExtrato['COR_BOTAOON'];
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
		} 

	?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Marka Fidelização e Relacionamento</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/main.css" rel="stylesheet">

        <link href="css/custom.css" rel="stylesheet">

        <link rel="icon" type="image/png" href="http://www.markafidelizacao.net.br/wp-content/uploads/2016/10/icone-marka.png" />
		
        <!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
        <!--[if lt IE 9]>
          <script src="js/html5shiv.js"></script>
        <![endif]-->
    </head>
    
	<style>
	
	body {
		background-color: #ecf0f1;
	}
	
    </style>
  
    
    <!-- Scrollspy set in the body -->
    <body id="home" data-spy="scroll" data-target=".main-nav" data-offset="73">

    
    <!--/////////////////////////////////////// NAVIGATION BAR ////////////////////////////////////////-->
    <section id="header">

        <nav class="navbar navbar-fixed-top" role="navigation">

            <div class="navbar-inner">
                <div class="container">

                    <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target="#navigation"></button>

                    <a href="http://marka.mk" class="navbar-brand"><img class="logo-img" src="images/logo_modelo.png" alt="Marka Soluções em Fidelização - Portal do Cliente"></a>

                    <div class="collapse navbar-collapse main-nav" id="navigation">


                        <ul class="nav pull-right">
                            <!--
                            <li><a href="index.html">&laquo; Back to Home</a></li>
							-->
                        </ul>

                    </div><!-- /nav-collapse -->
                </div><!-- /container -->
            </div><!-- /navbar-inner -->
        </nav>

    </section>

    <!--/////////////////////////////////////// BLOG SECTION ////////////////////////////////////////-->
    <section id="main-content">

        <div class="container">

            <div class="icon-huge">
                <img src="images/icons/compas.svg" alt="" />
            </div>

            <header>
                <h1>Ooops... Parece que você está perdido!</h1>
                <p class="lead">O <strong>Hot Site</strong> que você está procurando não existe ou foi desativado.</p>
                <br><!--
                <a href="index.html" class="btn btn-hg btn-primary btn-embossed text-center"><span class="fui-arrow-left"></span> Take me back</a>
				-->
            </header>

        </div> 

    </section>
	
		<script src="js/jquery-1.8.3.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/custom.js"></script>

  </body>
</html>	
	

	<?php
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
	
	
	//carrega site vazio
	}else {
	?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Marka Fidelização e Relacionamento</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/main.css" rel="stylesheet">

        <link href="css/custom.css" rel="stylesheet">

        <link rel="icon" type="image/png" href="http://www.markafidelizacao.net.br/wp-content/uploads/2016/10/icone-marka.png" />
		
        <!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
        <!--[if lt IE 9]>
          <script src="js/html5shiv.js"></script>
        <![endif]-->
    </head>
  
    
    <!-- Scrollspy set in the body -->
    <body id="home" data-spy="scroll" data-target=".main-nav" data-offset="73">

    
    <!--/////////////////////////////////////// NAVIGATION BAR ////////////////////////////////////////-->
    <section id="header">

        <nav class="navbar navbar-fixed-top" role="navigation">

            <div class="navbar-inner">
                <div class="container">

                    <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target="#navigation"></button>

                    <a href="http://marka.mk" class="navbar-brand"><img class="logo-img" src="images/logo_modelo.png" alt="Marka Soluções em Fidelização - Portal do Cliente"></a>

                    <div class="collapse navbar-collapse main-nav" id="navigation">


                        <ul class="nav pull-right">
                            <!--
                            <li><a href="index.html">&laquo; Back to Home</a></li>
							-->
                        </ul>

                    </div><!-- /nav-collapse -->
                </div><!-- /container -->
            </div><!-- /navbar-inner -->
        </nav>

    </section>

    <!--/////////////////////////////////////// BLOG SECTION ////////////////////////////////////////-->
    <section id="main-content">

        <div class="container">

            <div class="icon-huge">
                <img src="images/icons/compas.svg" alt="" />
            </div>

            <header>
                <h1>Ooops... Parece que você está perdido!</h1>
                <p class="lead">O <strong>Hot Site</strong> que você está procurando não existe ou foi desativado.</p>
                <br><!--
                <a href="index.html" class="btn btn-hg btn-primary btn-embossed text-center"><span class="fui-arrow-left"></span> Take me back</a>
				-->
            </header>

        </div> 

    </section>
	
    <!--//////////////////////////////////////// FOOTER SECTION ////////////////////////////////////////-->
    <section id="footer">
        <div class="bottom-menu-inverse">

            <div class="container">

                <div class="row">
                    <div class="col-md-6">
 							<p class="fFooter">Marka Fidelização e Relacionamento - &copy; Todos os direitos reservados. <br/> 
							Solução: &nbsp; <a href="http://marka.mk" class="fFooter" target="_blank">Marka Soluções em Fidelização</a>.</p>
                   </div>

                    <div class="col-md-6 social">
                        <ul class="bottom-icons">
                            <li>
                              <a href="https://www.facebook.com/MarkaFidelizacao/" class="fui-facebook"></a>
                            </li>
                             <li>
                              <a href="https://www.youtube.com/user/marcelofidelizacao/videos" class="fui-youtube"></a>
                            </li>
                          </ul>                      
                    </div>
                </div>
            
            </div><!-- /row -->
        </div><!-- /container -->

    </section>

		<script src="js/jquery-1.8.3.min.js"></script>
		<script src="js/jquery-ui-1.10.3.custom.min.js"></script>
		<script src="js/jquery.ui.touch-punch.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/jquery.isotope.min.js"></script>
		<script src="js/bootstrap-select.js"></script>
		<script src="js/custom.js"></script>

  </body>
</html>


	<?php 
	}	
	?>
	