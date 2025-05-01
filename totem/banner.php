<?php
include "../_system/_functionsMain.php";
include_once './funWS/buscaConsumidor.php';

	//echo "<h1>".$_GET['param']."</h1>";
	//echo fnDebug('true');
	//fnEscreve("totem");
	
	$parametros = fnDecode($_GET['key']);
	$arrayCampos = explode(";", $parametros);
	//$buscaconsumidor=fnconsulta($cpf, $arrayCampos);
	$cod_empresa = $arrayCampos[4];
	
	//fnEscreve($cod_empresa);

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
		//fnEscreve($des_imgback_mob);
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
		if ($des_paghome == "index"){$destinoHome = "";}
		else {$destinoHome = "banner.do";}

	}

	
?>


<html lang="pt">
    <head>
		<meta charset="utf-8">
			<meta http-equiv="X-UA-Compatible" content="IE=10" />
			<meta http-equiv="X-UA-Compatible" content="IE=11" />
		<meta name="viewport" content="width=device-width,height=device-height,initial-scale=1.0"/>

		<title>Totem</title>
		
		
		<script src="https://bunker.mk/js/jquery.min.js"></script>
		
		<link href="https://bunker.mk/css/bootstrap.flatly.min.css" rel="stylesheet">
		<link href="https://bunker.mk/css/font-awesome.min.css" rel="stylesheet" />
		
		<link href="https://bunker.mk/css/superslides.css" rel="stylesheet">
			
		<!--[if lt IE 9]><script src="js/ie8-responsive-file-warning.js"></script><![endif]
		<script src="https://bunker.mk/js/plugins/ie-emulation-modes-warning.js"></script>
		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
		  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->

		<!-- Favicons -->
		<link rel="icon" type="image/ico" rel="shortcut icon" href="images/favicon.ico"/>	
		
		<!-- Favicons -->
		<link rel="icon" href="images/favicon.ico">
    </head>

    <?php if($check_CORPERS == "checked"){ include "customCss.php"; } ?>
	
	<style>
		.slides-navigation a {
			display: block;
			text-decoration: none;
			color: white;
			font-weight: bold;
			font-size: 26px;
			margin: 0 25px;
			text-shadow: 0 1px 1px #000;
			text-align: center;
			top: -40px;
			padding: 1px;
			-webkit-transition: background 0.15s ease;
			-moz-transition: background 0.15s ease;
			-o-transition: background 0.15s ease;
			transition: background 0.15s ease;
			opacity: 0.6;
		}	
		
		.cliqueAqui {
			position: absolute; 
			bottom: 50;
			right: 15;
			z-index: 100; 
			width: 100%;
		}

		.image{
			width: 100vw;
			height: 100vh;
		}

		#slides{
			height: 100%;
		}



		<?php 
		
			$sql1="select A.* from BANNER_TOTEM A 
				where A.COD_EMPRESA='".$cod_empresa."' 
				AND A.COD_EXCLUSA = 0 
				AND A.LOG_ATIVO = 'S' 
				order by A.DES_BANNER ";

			//fnEscreve($sql);
			$arrayQuery = mysqli_query(connTemp($cod_empresa,""),$sql1) or die(mysqli_error());
			$count=1;
			
			while ($qrListaProduto = mysqli_fetch_assoc($arrayQuery))
			{	

				if(isset($qrListaProduto['DES_IMAGEM_MOB']) && $qrListaProduto['DES_IMAGEM_MOB'] != ''){										  
					$des_imagem_mob = $qrListaProduto['DES_IMAGEM_MOB'];
				}else{
					$des_imagem_mob = $qrListaProduto['DES_IMAGEM'];
				}
				?>

		/* (320x480) iPhone (Original, 3G, 3GS) */
@media only screen and (min-device-width: 320px) and (max-device-width: 480px) {
	.image:nth-child(<?php echo $count; ?>){
		height: 50vh;
		background:url('https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/banner/<?php echo $des_imagem_mob; ?>') bottom no-repeat !important; background-size: cover !important;
	}
    
}
 
/* (320x480) Smartphone, Portrait */
@media only screen and (device-width: 320px) and (orientation: portrait) {
    .image:nth-child(<?php echo $count; ?>){
		height: 50vh;
		background:url('https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/banner/<?php echo $des_imagem_mob; ?>') bottom no-repeat !important; background-size: cover !important;
	}

}
 

/* (1280x800) Tablets, Portrait */
@media only screen and (max-width: 800px) and (orientation : portrait) {
	.image:nth-child(<?php echo $count; ?>){
		height: 50vh;
		background:url('https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/banner/<?php echo $des_imagem_mob; ?>') bottom no-repeat !important; background-size: cover !important;
	}	
}

/* (768x1024) iPad 1 & 2, Portrait */
@media only screen and (max-width: 768px) and (orientation : portrait) {
   .image:nth-child(<?php echo $count; ?>){
		height: 50vh;
		background:url('https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/banner/<?php echo $des_imagem_mob; ?>') bottom no-repeat !important; background-size: cover !important;
	}	 
}
 
@media only screen and (min-device-width: 1100px) and (orientation : portrait) {
   .image:nth-child(<?php echo $count; ?>){
		height: 50vh;
		background:url('https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/banner/<?php echo $des_imagem_mob; ?>') bottom no-repeat !important; background-size: cover !important;
	}	 
}
<?php $count++; } ?>

	</style>
	
    <body>
	
	  <div id="slides">
		<div class="slides-container">
			
		
		<?php 
		
			$sql1="select A.* from BANNER_TOTEM A 
				where A.COD_EMPRESA='".$cod_empresa."' 
				AND A.COD_EXCLUSA = 0 
				AND A.LOG_ATIVO = 'S' 
				order by A.DES_BANNER ";

			//fnEscreve($sql);
			$arrayQuery = mysqli_query(connTemp($cod_empresa,""),$sql1) or die(mysqli_error());
			
			while ($qrListaProduto = mysqli_fetch_assoc($arrayQuery))
			{														  
				$des_imagem = $qrListaProduto['DES_IMAGEM'];
				?>
				<div class="image" style="background: url('https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/banner/<?php echo $des_imagem; ?>') center no-repeat; background-size: cover;"></div>
				<?php	
			}
		?>		
		
		  
		</div>

		<nav class="slides-navigation">
		  <a href="#" class="next"><i class="fa fa-chevron-right" style="font-size: 60px;"></i></a>
		  <a href="#" class="prev"><i class="fa fa-chevron-left" style="font-size: 60px;"></i></a>
		</nav>
	  </div>

		<div class="row borda cliqueAqui">
			<div class="col-md-4 hidden-xs"></div>
			<div class="col-md-4 col-xs-12 text-center borda" style="padding: 15px;">	
				<a href="/?key=<?php echo $_GET['key'] ;?>" class="btn btn-lg btn-block btn-primary"><i class="fa fa-thumbs-o-up" aria-hidden="true"></i>&nbsp; Clique aqui para entrar</a>
			</div>
			<div class="col-md-4 hidden-xs"></div>
		</div>		
	
	<script src="https://bunker.mk/js/plugins/jquery.superslides.min.js" type="text/javascript" charset="utf-8"></script>
	
  <script>

    $(function() {
      $('#slides').superslides({
        hashchange: true,
		play: 15000,
      });
    });

  </script>

    </body>
	
</html>

	