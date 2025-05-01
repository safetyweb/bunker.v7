<?php
include './_system/_functionsMain.php';
$cod_cliente= fnLimpaCampoZero(fnDecode($_GET['secur']));


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
        <script src="libs/jquery-3.6.0.min.js"></script>
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
		$tituloPagina = "Postos";
		include "menu.php"; 
		?>	

        <div class="container">
            <div class="push30"></div> 

            <div class="row">
                
                <div class="col-xs-12">
                    
                    <a href="enderecos.php?id=9999&secur=<?=fnEncode($cod_cliente)?>&idp=<?=fnEncode('S')?>" class="btnLoad">
                        <div class="col-xs-2 text-center" style="padding-left: 0;padding-right: 0; padding-top: 7px;">
                            <img src="img/desconto.png" width="35px">
                        </div>

                        <div class="col-xs-10">
                            <h4 style="color:#03204F"><b>Postos promocionais para usuários Duque APP</b></h4>
                        </div>
                    </a>

                </div>

            </div>

            <div class="push20"></div>

            <div class="row">
                
                <div class="col-xs-12">
                    
                    <a href="enderecos.php?id=9999&secur=<?=fnEncode($cod_cliente)?>&idp=<?=fnEncode('C')?>" class="btnLoad">
                        <div class="col-xs-2 text-center" style="padding-left: 0;padding-right: 0; padding-top: 7px;">
                            <span class="fa fa-usd" style="font-size: 35px; color:#03204F"></span>
                        </div>

                        <div class="col-xs-10">
                            <h4 style="color:#03204F"><b>Postos com cashback para usuários Duque APP</b></h4>
                        </div>
                    </a>

                </div>

            </div>

            <div class="push20"></div>

            <div class="row">
                
                <div class="col-xs-12">
                    <a href="enderecos.php?id=9999&secur=<?=fnEncode($cod_cliente)?>&idp=<?=fnEncode('N')?>" class="btnLoad">
                        <div class="col-xs-2 text-center" style="padding-left: 0;padding-right: 0; padding-top: 7px;">
                           <img src="img/gas-pump.png" width="35px">
                        </div>

                        <div class="col-xs-10">
                            <h4 style="color:#03204F"><b>Todos os postos</b></h4>
                        </div>
                    </a>

                </div>

            </div>		
			
			<div class="push50"></div> 
			
        </div> <!-- /container -->	

		<?php include "jsLib.php"; ?>
<?php
function get_browser_name($user_agent)
{
    if (strpos($user_agent, 'Opera') || strpos($user_agent, 'OPR/')) return 'Opera';
    elseif (strpos($user_agent, 'Edge')) return 'Edge';
    elseif (strpos($user_agent, 'Chrome')) return 'Chrome';
    elseif (strpos($user_agent, 'Safari')) return 'Safari';
    elseif (strpos($user_agent, 'Firefox')) return 'Firefox';
    elseif (strpos($user_agent, 'iPhone')) return 'iPhone';
    elseif (strpos($user_agent, 'iPad')) return 'iPad';
     elseif (strpos($user_agent, 'iPod')) return 'iPod';
    elseif (strpos($user_agent, 'MSIE') || strpos($user_agent, 'Trident/7')) return 'Internet Explorer';
    
    return 'Other';
}

// Usage:

$navegador=get_browser_name($_SERVER['HTTP_USER_AGENT']); 

//if($navegador!= 'Safari' || $navegador!= 'iPhone'||$navegador!= 'iPad'||$navegador!= 'iPod'||$navegador!= 'Other' )
//{    
echo'        
<script type="text/javascript">
var x = document.getElementById("demo");
  
			function getLocation()
			  {
                        
			  if (navigator.geolocation)
				{
				navigator.geolocation.getCurrentPosition(showPosition);
                              
				}
			  else{x.innerHTML="O seu navegador não suporta Geolocalização.";}
			  }
			function showPosition(position)
			  {
			    document.cookie="RD_localAtual="+position.coords.latitude + "," + position.coords.longitude;
                         
			  }

			getLocation()	
</script>';

//}
?>
    </body>
</html>

<script type="text/javascript">
    $(function(){
        $(".btnLoad").click(function(){
            $(".container").html("<div class='push100'></div><div class='push100'></div><div class='push50'></div><div class='loading' style='width:100%'></div>");
        });
    });
</script>
