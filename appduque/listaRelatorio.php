<?php
include './_system/_functionsMain.php';

if($_SESSION["COD_RETORNO"]!=''){$cod_cliente=$_SESSION["COD_RETORNO"];} else {$cod_cliente= fnDecode($_GET['secur']);} 

$MENUSTARING=fnEncode($cod_cliente);

?>﻿
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">

        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link href="libs/bootstrap_flatly.css" rel="stylesheet">
        <link href="libs/font-awesome.min.css" rel="stylesheet">
        <link href="libs/bootstrap-social.css" rel="stylesheet">
        <link href="libs/layout.css" rel="stylesheet">


        <title>Rede Duque</title>

        <style>
            
            body {
                padding-bottom: 40px;
                background-color: #eee;
                font-size: 14px;
                color: #03214f;
            }
			
            .fa-map-marker {
                font-size: 80px;
            }
        </style>


        <script src="libs/ie-emulation-modes-warning.js"></script>


        <!-- Include jQuery.mmenu .css files -->
        <link type="text/css" href="libs/jquery.mmenu.all.css" rel="stylesheet" />

        <!-- Include jQuery and the jQuery.mmenu .js files -->
        <script src="libs/jquery.min.js"></script>
        <script type="text/javascript" src="libs/jquery.mmenu.all.js"></script>

        <!-- Fire the plugin onDocumentReady -->
        <script type="text/javascript">
            jQuery(document).ready(function( $ ) {
				$("#menu").mmenu({
					// options
					extensions	: ["theme-white"]
				}, {
					// configuration
					offCanvas: {
						pageSelector: ".container"
					}
				});
            });
        </script>        

    </head>

    <body class="bgColor" data-gr-c-s-loaded="true">
	
 		<?php 
		$tituloPagina = "Relatórios";
		include "menu.php"; 
		?>	

        <div class="container">
            <div class="push10"></div> 
		
			<h4>Escolha abaixo o relatório desejado.</h4>

            <div class="push10"></div> 
            <a href="relTokens.php?secur=<?php echo $MENUSTARING;?>" class="btn btn-primary btn-block">Tokens Gerados</a>			
			<div class="push10"></div> 
            <a href="relOverview.php?secur=<?php echo $MENUSTARING;?>" class="btn btn-primary btn-block">Vendas Overview</a>			
			<div class="push10"></div> 
            <a href="relVendasRT.php?secur=<?php echo $MENUSTARING;?>" class="btn btn-primary btn-block">Vendas Gerais</a>			
			<div class="push10"></div>
            <a href="relClientesTop.php?secur=<?php echo $MENUSTARING;?>" class="btn btn-primary btn-block">Melhores Clientes</a>			
			<div class="push10"></div>
			
			<div class="push50"></div> 
			
        </div> <!-- /container -->	

		<?php include "jsLib.php"; ?>		

    </body>
</html>