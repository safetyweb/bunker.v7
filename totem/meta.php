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
	$cod_players = $arrayCampos[7];
	$_GET['id'] = fnEncode($cod_empresa);
	
	$sql = "SELECT * FROM totem_players WHERE COD_EMPRESA=$cod_empresa AND COD_PLAYERS=$cod_players";
	$arrayQuery = mysqli_query(connTemp($cod_empresa,""),$sql);
	$qrBusca = mysqli_fetch_assoc($arrayQuery);
	$cod_univend = $qrBusca['COD_UNIVEND'];
	$totem = true;
	
?>


<html lang="pt">
    <head>
		<meta charset="utf-8">
			<meta http-equiv="X-UA-Compatible" content="IE=10" />
			<meta http-equiv="X-UA-Compatible" content="IE=11" />
		<meta name="viewport" content="width=device-width,height=device-height,initial-scale=1.0"/>

		<title>Meta</title>
		
		
		<script src="https://bunker.mk/js/jquery.min.js"></script>
		
		<link href="https://bunker.mk/css/bootstrap.flatly.min.css" rel="stylesheet">
		<link href="https://bunker.mk/css/font-awesome.min.css" rel="stylesheet" />
		
		<link href="https://bunker.mk/css/superslides.css" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="https://adm.bunker.mk/css/fontawesome-pro-5.13.0-web/css/all.min.css" />
			
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
		
		<style>
		body{
		    overflow-x: hidden;
		}
		</style>
    </head>
	
    <body>

		<?php
		include_once("../previewMetas.php");
		?>

    </body>
	
</html>