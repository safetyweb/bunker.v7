<!DOCTYPE html>
<html>
<head>
	<title></title>

	<?php 	
		$css_skin = "bootstrap.flatly.min.css";
		include "../../_system/_functionsMain.php";
		//echo fnDebug('true');

		//só pra fixar o upload
		$cod_empresa = 7;

		$cod_busca = fnLimpaCampoZero($_GET['id']);

		//fnEscreve($cod_busca);

		$sql1 = "select * from MODULOSMARKA where COD_MODULMK = $cod_busca ";
		$arrayQuery1 = mysqli_query($connAdm->connAdm(),$sql1) or die(mysqli_error());
		$qrBuscaModulos = mysqli_fetch_assoc($arrayQuery1);
		$nom_modulmk = $qrBuscaModulos['NOM_MODULMK'];
		$des_imagem = $qrBuscaModulos['DES_IMAGEM'];
		$des_extras = $qrBuscaModulos['DES_EXTRAS'];
	?>
	
	<link href="../../css/<?php echo $css_skin ?>" rel="stylesheet">

	<script src="../../js/jquery.min.js"></script>
	<!-- extras -->
	<link href="../../css/jquery.webui-popover.min.css" rel="stylesheet" />
	<link href="../../css/chosen-bootstrap.css" rel="stylesheet" />
	<link rel="stylesheet" type="text/css" href="../../css/fa5all.css" />
	<link href="../../css/bootstrap.vertical-tabs.css" rel="stylesheet" />
	<!-- complement -->
	<link href="../../css/default.css" rel="stylesheet" />
	<link href="../../css/checkMaster.css" rel="stylesheet" />

	<link rel="stylesheet" href="../../css/widgets.css" />
	<link rel="stylesheet" href="../../css/default.css" />
		
	<!--[if lt IE 9]><script src="js/ie8-responsive-file-warning.js"></script><![endif]-->
	<script src="../../js/plugins/ie-emulation-modes-warning.js"></script>
	<!-- Favicons -->
	<link rel="icon" type="image/ico" rel="shortcut icon" href="../../images/favicon.ico"/>

</head>

<body>
<style>

	body{
		overflow-x: hidden;
	}

	p {
	    margin: 0; 
	}
	.change-icon:hover > .fa + .fa {
	  display: inherit;
	}
	
	.fa-edit:hover{
		color: #18bc9c;
	}
	
	.item{
		padding-top: 0;
	}
	
	.folder {
		height: 30px;
	}
	
	a, a:hover {
		text-decoration:none;
	}
	
</style>


		<link rel="stylesheet" href="css/widgets.css" />
		<div class="row">								

			<div class="col-md-4">

				<div class="row">
					<div class="col-xs-12">
													
						<div class='tile tile-default shadow change-icon' style='background-color: <?php echo $qrBuscaModulos['DES_COR']; ?>; font-size: 15px; color: #fff'>		
						<div class="row">
							<div class="col-xs-12">
								<i class="fa fa-plus" style="font-size: 15px; line-height: 4px; color: #fff; float: right; margin: 5px 0 0 0;"></i>
							</div>
						</div>
						<div class="push"></div>
						
						
							<i class="fa <?php echo $qrBuscaModulos['DES_ICONE']; ?> fa-3x" style="line-height: 40px; margin-bottom: 25px; "></i>
						
							<p class="folder" style="margin-bottom: 5px; font-size: 12px;"><?php echo $qrBuscaModulos['NOM_MODULMK']; ?> </p>
							<p style="font-size: 12px; height: 60px;"><?php echo $qrBuscaModulos['DES_MODULMK']; ?> </p>
																
						</div>

					</div>
				</div>

				<div class="row">
					<div class="col-md-12 text-center">
						<img src="../../media/clientes/7/logo_marka_cor.png" width="300px">
					</div>
				</div>									
					
			</div>	
			
			<div class="col-md-8">

				<h3 style="margin-top: 0;"><?php echo $nom_modulmk; ?></h3>				
				<div class="push10"></div>
				
				<?php echo html_entity_decode($des_extras); ?>

			</div>

			
		</div>
		
		<div class="push20"></div> 

</body>
</html>
					

	
	<script type="text/javascript">
		
		
		
	</script>	
   