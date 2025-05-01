<?php
include './_system/_functionsMain.php';
//fnDebug('true');
$userid= fnLimpaCampoZero(fnDecode($_GET['secur']));
function distancia($lat1, $lon1, $lat2, $lon2) {

            $lat1 = deg2rad($lat1);
            $lat2 = deg2rad($lat2);
            $lon1 = deg2rad($lon1);
            $lon2 = deg2rad($lon2);

            $dist = (6371 * acos( cos( $lat1 ) * cos( $lat2 ) * cos( $lon2 - $lon1 ) + sin( $lat1 ) * sin($lat2) ) );
            $dist = number_format($dist, 2, '.', '');
            return $dist;
            }
?>
﻿<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">

        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height" />

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
			
            .fa-80 {
                font-size: 80px;
            }
			
			iframe {
				width: 100%;
				height: 100%;
			}
			
			.modal-body {
				padding: 0;
			}

            .barra:before{
                content: "";
                width: 1px;
                height: 140px;
                background: #9f9f9f;
                position: absolute;
                left: 0;
                top: -10px;
            }

            #menu-app{
                position: fixed;
                width: 100vw;
                height: 15%;
                bottom: -15%;
                background-color: #fff;
                z-index: 99;
                margin-left: 0;
                margin-right: 0;
            }

            .filtro{
                background-color: rgba(0,0,0,0.5);
                height: 100%;
                width: 100vw;
                display: none;
                position: absolute;
                top: 0;
                z-index: 98;
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
		@$tituloPagina = "Endereços";
		include "menu.php"; 
		?>

        	
        <div class="filtro"></div>

        <div class="container">

            <div class="push20"></div>
		
		
  <?php      
   
  	$cod_regiao = fnLimpacampoZero($_GET['id']);
    $log_ativohs = fnLimpaCampo(fnDecode($_GET['idp']));
    $andAtivo = "";
    if($log_ativohs == "S"){
        $andAtivo = "AND LOG_ATIVOHS = 'S'
                     AND LOG_ESPECIAL = 'N'";
    }else if($log_ativohs == "C"){
        $andAtivo = "AND LOG_ATIVOHS = 'S'
                     AND LOG_ESPECIAL = 'S'";
    }

    // fnEscreve($log_ativohs);

  //$sql = "select * from unidadevenda where cod_empresa  = '".$cod_empresa."' order by NOM_FANTASI ";
  if ($cod_regiao == 9999){
	$sql = "SELECT * from unidadevenda where COD_EMPRESA = 19 and COD_UNIVEND != 955 AND LOG_ESTATUS='S' $andAtivo  order by NOM_FANTASI ";
  } else {
	$sql = "SELECT * from unidadevenda where COD_EMPRESA = 19 and COD_TIPOREG = $cod_regiao and COD_UNIVEND != 955 AND LOG_ESTATUS='S' $andAtivo  order by NOM_FANTASI ";  
  }
  $arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
 
  $count=0;
  $novoArrayEnderecos = array();
while ($qrListaUniVendas = mysqli_fetch_assoc($arrayQuery))
{                
    //calcula distancia
     
     //print_r($qrListaUniVendas);
	 //print_r($qrListaUniVendas);
     
	//echo $_COOKIE['RD_localAtual'];
     
    if(@$_COOKIE['RD_localAtual']!='')
    {
     
        
            $buscacord="select * from location WHERE COD_CARTAO='$userid' OR IP ='".$_SERVER['REMOTE_ADDR']."';";
           // echo $buscacord.'-'.$_GET['secur'];
            $calccoord=mysqli_query(connTemp(19, ''), $buscacord);
            $retucoord=mysqli_fetch_assoc($calccoord);

            if($retucoord['LAT']!='')
            {               
               
                $km=distancia($retucoord['LAT'],$retucoord['LNG'],$qrListaUniVendas['lat'],$qrListaUniVendas['lng']);
                $qrListaUniVendas['distancia'] = $km;
                array_push($novoArrayEnderecos, $qrListaUniVendas);
            }
            else
            {
                 //pegar geolocalização do chrome
                     $geo=explode(',',$_COOKIE['RD_localAtual']); 
                     $km=distancia($geo['0'],$geo['1'],$qrListaUniVendas['lat'],$qrListaUniVendas['lng']);
                     $qrListaUniVendas['distancia'] = $km;

                   array_push($novoArrayEnderecos, $qrListaUniVendas);
                } 
    
    
    }else{
        
    $buscacord="select * from location WHERE COD_CARTAO='$userid' or IP ='".$_SERVER['REMOTE_ADDR']."';";
    $calccoord=mysqli_query(connTemp(19, ''), $buscacord);
    $retucoord=mysqli_fetch_assoc($calccoord);
       
    $km=distancia($retucoord['LAT'],$retucoord['LNG'],$qrListaUniVendas['lat'],$qrListaUniVendas['lng']);
    $qrListaUniVendas['distancia'] = $km;

    array_push($novoArrayEnderecos, $qrListaUniVendas);
       
    
    }
	
      
    
   }
   
	$sort = array();
    $count = 0;
	foreach($novoArrayEnderecos as $k=>$v) {
	$sort['distancia'][$k] = $v['distancia'];
	}

	array_multisort($sort['distancia'], SORT_ASC, $novoArrayEnderecos);
    
	foreach ($novoArrayEnderecos as $objeto) {

           
   ?> 

            <div class="row">
                
                <div class="col-xs-9">

                    <div class="col-xs-10" style="padding-right: 0;">
                        <div style='font-size: 16px; font-weight: 900!important;' id='distancia'>Você está a: <?php echo $objeto['distancia'].' <span style="font-size:10px;">KM</span>';?></div> 
                        <div style='font-size: 16px; font-weight: 900!important;'><?=ucwords(strtolower($objeto['NOM_FANTASI']))." - ".ucwords(strtolower($objeto['DES_BAIRROC']))?></div>
                        <div style="font-size: 12px;"><b><?php echo utf8_decode($objeto['DES_ENDEREC']).', '.$objeto['NUM_ENDEREC']; ?></b></div>
                    </div>
                    <?php 
                    if($objeto['LOG_ATIVOHS']=='S' && $objeto['LOG_ESPECIAL'] == "N")
                    {
                    ?>
                    <div class="col-xs-2 text-center" style="padding-left: 0;padding-right: 0;">
                        <img src="img/desconto.png" width="35px">
                    </div>
                    <?php 
                    }else if($objeto['LOG_ATIVOHS']=='S' && $objeto['LOG_ESPECIAL'] == "S"){
                    ?>
                    <div class="col-xs-2 text-center" style="padding-left: 0;padding-right: 0;">
                        <span class="fa fa-usd" style="font-size: 35px; color:#03204F"></span>
                    </div>
                    <?php   
                    }
                    ?>
                </div>

                <div class="col-xs-3 barra" style="padding-left: 0;padding-right: 0;">

                    <!-- <div class="col-md-12 text-center">
                        <a  src="" class="btn btn-primary btn-sm btn-block btn-sm abrirMapa" style=" background-color: #4dace9; border-color: #4dace9;"><i class="fa fa-map-marker"></i>&nbsp; Mapa</a>
                        <div class="push10"></div>
                    </div> -->
                    
                    <div class="col-md-12 text-center">
                        <a class="abrirMenu" coord="<?php echo $objeto['lat'];?>,<?php echo $objeto['lng'];?>" style="color:#03204F">
                            <i class="fa fa-map-o fa-2x"></i>
                            <div class="push5"></div>
                            &nbsp; <b>Veja no mapa</b>
                        </a>
                        <div class="push10"></div>
                    </div>
                    
                    <!-- <div class="col-md-12 text-center">
                        <a href="http://waze.to/?ll=<?php echo $objeto['lat'];?>,<?php echo $objeto['lng'];?>" class="btn btn-primary btn-sm btn-block" style="background-color: #4dace9; border-color: #4dace9;"><i class="fa fa-car"></i>&nbsp; <div class="push"></div> Waze</a>
                        <div class="push10"></div>
                    </div>
                
                    <div class="col-md-12 text-center">
                        <a href="https://maps.google.com/maps?daddr=<?php echo $objeto['lat'];?>,<?php echo $objeto['lng'];?>&ll=" class="btn btn-primary btn-sm btn-block" style="background-color: #4dace9; border-color: #4dace9;"><i class="fa fa-map-o"></i>&nbsp; <div class="push"></div> Maps</a>
                    </div> -->

                </div>

            </div>
            <!-- <div style="margin-top: 20px" class="text-center">
                <i class="fa-80 fa fa-map-marker" aria-hidden="true" style="float: left; margin-right: 30px; margin-left: 25px;"></i>
                <div style="position: absolute; margin-left: 110px; font-size: 16px; text-transform: uppercase; text-align: left;">
                    <div style='margin-top:5px; font-size: 15px; font-weight: 900!important;' id='distancia'><h7>Você está a: <?php echo $objeto['distancia'].' KM';?></h7></div> 
                    <div style='margin-top:5px; font-size: 15px; font-weight: 900!important;'><?=$objeto['NOM_FANTASI']." - ".$objeto['DES_BAIRROC']?></div>
                    <div style="margin-top: 12px; font-size: 13px;"><b><?php echo $objeto['DES_ENDEREC'].', '.$objeto['NUM_ENDEREC']; ?></b></div>
                    <div class="push20"></div>  
                </div>

            </div> -->
  
            <!-- <div class="push20"></div>

            <div class="text-center">

				<div class="col-md-12 text-center">
					<a  src="" class="btn btn-primary btn-sm btn-block btn-sm abrirMapa" style=" background-color: #4dace9; border-color: #4dace9;"><i class="fa fa-map-marker"></i>&nbsp; Mapa</a>
					<div class="push10"></div>
				</div>
				
				<div class="col-md-12 text-center">
					<a href="http://waze.to/?ll=<?php echo $objeto['lat'];?>,<?php echo $objeto['lng'];?>" class="btn btn-primary btn-sm btn-block" style="background-color: #4dace9; border-color: #4dace9;"><i class="fa fa-car"></i>&nbsp; Waze</a>
					<div class="push10"></div>
				</div>
			
				<div class="col-md-12 text-center">
					<a href="https://maps.google.com/maps?daddr=<?php echo $objeto['lat'];?>,<?php echo $objeto['lng'];?>&ll=" class="btn btn-primary btn-sm btn-block" style="background-color: #4dace9; border-color: #4dace9;"><i class="fa fa-map-o"></i>&nbsp; Google Maps</a>
				</div>
                
            </div>	 -->		

            <div class="push30"></div>
            <div class="mytextdiv">
                <div class="divider"></div>
            </div>
            <div class="push10"></div>
				
		<?php
            $count++;	
		} 		
		?>

        </div> <!-- /container -->	
			
		  <!-- Modal -->
		  <div class="modal fade" id="myModal" role="dialog">
			<div class="modal-dialog">
			
			  <!-- Modal content-->
			  <div class="modal-content">
				<div class="modal-header">
				  <button type="button" class="close" data-dismiss="modal">&times;</button>
				  <h4 class="modal-title">Mapa</h4>
				</div>
				<div class="modal-body">
				  <iframe id="mapa" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d467692.0486445752!2d-46.875482127448954!3d-23.681531511765154!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94ce448183a461d1%3A0x9ba94b08ff335bae!2sS%C3%A3o+Paulo%2C+State+of+S%C3%A3o+Paulo!5e0!3m2!1sen!2sbr!4v1523579963188" frameborder="0" style="border:0" allowfullscreen ></iframe>
				</div>
				<div class="modal-footer">
				  <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
				</div>
			  </div>
			  
			</div>
		  </div>
		  

		<?php include "jsLib.php"; ?>

        <div class="row" id="menu-app">

            <div class="push20"></div>

            <div class="col-xs-6 text-center" style="display: table; align-items: center; height: 50px;">
                
                    <a href="http://waze.to/?ll=AQUIVAOASCOORDS" id="btnWaze" class="btn btn-primary btn-sm btn-block" style="background-color: #4dace9; border-color: #4dace9; display: table-cell;">
                        <i class="fa fa-car"></i>
                        <div class="push5"></div>
                        Waze
                    </a>
                    <div class="push10"></div>
                
            </div>

            <div class="col-xs-6 text-center" style="display: table; align-items: center; height: 50px;">
                
                    <a href="https://maps.google.com/maps?daddr=AQUIVAOASCOORDS&ll=" id="btnMaps" class="btn btn-primary btn-sm btn-block" style="background-color: #4dace9; border-color: #4dace9; display: table-cell;">
                        <i class="fa fa-map-o"></i>
                        <div class="push5"></div>
                        Google Maps
                    </a>
                
            </div>

        </div>

    </body>
	
	        <script type="text/javascript">
            jQuery(document).ready(function( $ ) {
				$( ".abrirMapa" ).click(function() {
				  $('#myModal').appendTo("body").modal('show');
				});
                                                              

				$('#myModal').on('show.bs.modal', function () {
					
					$('.modal .modal-body').css('height', '100%');
					$('.modal .modal-body').css('max-height', window.innerHeight * 0.75);
				});

                $(".abrirMenu").click(function(){
                    $("#btnWaze").attr("href","http://waze.to/?ll="+$(this).attr("coord"));
                    $("#btnMaps").attr("href","https://maps.google.com/maps?daddr="+$(this).attr("coord")+"&ll=");
                    $("#menu-app").animate({
                        "bottom":"0"
                    },150);

                    $(".filtro").fadeIn(150);
                });

                $(".filtro").click(function(){
                    $("#menu-app").animate({
                        "bottom":"-15%"
                    },150);
                    $(".filtro").fadeOut(150);
                });
            });
        </script>
</html>