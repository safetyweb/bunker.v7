<?php
include './_system/_functionsMain.php';
//fnDebug('true');

@$cod_cliente=$_SESSION["COD_RETORNO"];
// fnEscreve($cod_cliente);  
@$cod_entidad=$_SESSION["cod_entidad"];
if($cod_cliente!='')
{
  
}else{
      //header("Location:http://191.252.2.68/app/"); 
       header("Location:http://bunker.mk/appduque/"); 
     // session_destroy();
     // session_unset();
      
} 

$cartao="select COD_CLIENTE, NUM_CGCECPF from clientes where num_cartao='".$cod_cliente."'";
$rcartao=mysqli_fetch_assoc(mysqli_query(connTemp(19,''), $cartao));

$sqlproc="CALL SP_VERIFICA_TOKEN('19', '".$rcartao['COD_CLIENTE']."')";
$returnproc=mysqli_fetch_assoc(mysqli_query(connTemp(19,''), $sqlproc));
 //fnEscreve($sqlproc);
if($returnproc['v_RESULTADO']=='S')
{    
 
    $senha = fngeraSenha(6, true, true, true);
    $des_placa = fnLimpaCampo(fnDecode($_GET['idp']));
    // fnEscreve($des_placa);

    $gravatokem="INSERT INTO token_resgate
                 (DES_TOKEN, 
                 num_cgcecpf,
                 cod_empresa, 
                 dat_cadastr,
                 des_placa,
                 cod_msg
                 ) 
                 VALUES ('".addslashes($senha)."', 
                          '".$rcartao['NUM_CGCECPF']."', 
                          19,
                          '".date('Y-m-d H:i:s')."',
                          '".$des_placa."',
                          0   
                          );";
    // fnEscreve($gravatokem);
    mysqli_query(connTemp(19,''), $gravatokem);  
$msg='Token gerado com sucesso!';
$img='<div style="border-radius: 20px; background: #02520A;">
        <span class="fa-stack" style="height: 100px; width: 100%;">
          <i class="fa fa-mobile" style="font-size: 100px; color: #FFF;"></i>
          <i class="fa fa-check fa-stack-2x" style="font-size: 30px; color: #FFF; margin-top: 35px;"></i>
        </span>
      </div>';
$msg_placa = '<b>Token gerado para a placa:</b> <span style="font-weight: 900!important; color: #02520A!important;">'.$des_placa.'</span>';
    //fnEscreve($senha); 

    include './_system/codebar/BarcodeGenerator.php';
    include './_system/codebar/BarcodeGeneratorHTML.php';
    //$generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
    $generator = new \Picqer\Barcode\BarcodeGeneratorHTML();    
    $codbar= $generator->getBarcode($senha, $generator::TYPE_CODE_39,2.5,60);     
		     
}else{

    $msg='Limite excedido!';  
    $img='<div class="bg-danger" style="border-radius: 20px;">
            <span class="fa-stack" style="height: 100px; width: 100%;">
              <i class="fa fa-mobile" style="font-size: 100px; color: #FFF;"></i>
              <i class="fa fa-times fa-stack-2x" style="font-size: 30px; color: #FFF; margin-top: 35px;"></i>
            </span>
          </div>';
    $msg_placa = "";
}

if(1==1){
  $linkCode = "$senha";
}

include './_system/lista_oferta.php';

$arrayCampos = explode(";", $_SESSION["KEY"]);

$dadoslogin = array(
    '0'=>$arrayCampos[0],
    '1'=>$arrayCampos[1],
    '2'=>$arrayCampos[3],
    '3'=>'maquina',
    '4'=>$arrayCampos[2]
);

$arrayOfertas=fnofertas($cod_cliente,$dadoslogin);

if ($cod_cliente=='01734200014') {

  // print_r($arrayOfertas);
  // exit();

}

$log_bannerlista = 'S';

?>﻿

<!DOCTYPE html>
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
			
            .fa-map-marker {
                font-size: 80px;
            }

            .barcode{
              width: 320px!important;
              height: 60px!important;
              margin-left: auto;
              margin-right: auto;
            }
            .text-right{
              float: right;
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
                $("#menu").mmenu();
            });
        </script>        

    </head>

    <body class="bgColor" data-gr-c-s-loaded="true">
	
 		<?php 
		$tituloPagina = "Token";
		include "menu.php"; 
		?>	

        <div class="container text-center">

          <div class="push30"></div>

          <div class="row">
            <div class="col-xs-3 text-center">
              <?=$img?>
            </div>
            <div class="col-xs-9 text-center" style="padding-left: 0; padding-right: 0;">
              <h5 style="font-weight: 900!important;"><?php echo strtoupper($msg); ?></h5>
              <div class="push"></div>
              <h1 style="font-weight: 900!important; color: #02520A!important;"><?php echo $senha; ?></h1>
              <div class="push5"></div>
              <p><?=$msg_placa?></p>
            </div>
          </div>

          <!-- <div class="push20"></div> 

          <div class="barcode text-center">
            <?php  echo  $codbar; ?>
          </div> -->

          <div class="push20"></div> 

          <?php  if(1==1){ ?>

            <div id="qrcodeCanvas"></div>

          <?php  }else{ ?>

            <div class="barcode text-center">
              <?php  echo  $codbar; ?>
            </div>

          <?php  } ?>

          <div class="push20"></div>

          <div class="col-xs-12">
            <p>Para obter seu resgate no momento do pagamento, apresente este código ao frentista</p>
          </div>

          <div class="push50"></div>

          <!-- <div class="row">
            <div class="col-md-12">
              <hr style="margin:0; border-color: #3c3c3c; width: 100%; max-width: 100%;">
            </div>
            <div class="push10"></div>
            <div class="col-xs-12 text-right"> -->
              <!-- <a href="#" style="color: #03204F; font-size: 16px; font-weight: 900;"><span class="fa fa-external-link"></span>&nbsp; Mais ofertas</a> -->
            <!-- </div>
          </div> -->

          <div class="row">

            <div class="col-md-12">
              
            <?php  // if ($cod_cliente=='01734200014') { ?>
            <?php  if (trim($arrayOfertas['oferta0']['produtoticket']['msgerro']) != "Não há Produtos no ticket!") { ?>

              <div id="carouselOfertas" class="carousel slide shadow2" >

            <?php

              // if ($arrayOfertas['oferta0']['produtoticket'][imagem] != ''){
              if (array_key_exists("0", $arrayOfertas['oferta0']['produtoticket'])){

              // echo 1;

            ?>
          

              <ol class="carousel-indicators">
                <?php

                  $count = 0;
                  $active = 'active';
                  
                  foreach ($arrayOfertas['oferta0']['produtoticket'] AS $chave => $valor){

                    if($valor['descricao'] != ''){                             

                ?>
                      <li data-target="#carouselOfertas" data-slide-to="<?=$count?>" class="<?=$active?>"></li>
                <?php

                      $count++;
                      $active = ''; 

                    }
                  }

                ?>
              </ol>
              <div class="carousel-inner shadow2">

                <?php

                  $active = 'active';

                    foreach ($arrayOfertas['oferta0']['produtoticket'] AS $chave => $valor){  

                      // print_r($chave);

                      ?>

                          <div class="item <?=$active?>">
                      <?php 
                            if($valor[imagem] != ''){

                      ?>
                            <img src="<?=$valor[imagem]?>" width="100%">
                      <?php

                        }else{ 
                      ?>
                          <img src="https://img.bunker.mk/media/clientes/branco.jpg" width="100%">
                      <?php 
                        } 
                      ?>
                      </div>

                         <?php

                         $active = '';

                    }

                  }else{

                    if ($arrayOfertas['oferta0']['produtoticket'][imagem] != ''){
                ?>

                       <div class="item <?=$active?>">
                      <?php 
                            if($arrayOfertas['oferta0']['produtoticket'][imagem] != ''){

                      ?>
                            <img src="<?=$arrayOfertas['oferta0']['produtoticket'][imagem]?>" width="100%">
                      <?php

                        }else{ 
                      ?>
                          <img src="https://img.bunker.mk/media/clientes/branco.jpg" width="100%">
                      <?php 
                        } 
                      ?>
                      </div>

                         <?php

                         $active = '';

                    }

                  }

                }

                ?>

              </div>

              <?php

              // if ($arrayOfertas['oferta0']['produtoticket'][imagem] != ''){
                if (array_key_exists("0", $arrayOfertas['oferta0']['produtoticket'])){

                // echo 1;

              ?>

              <!-- Carousel controls -->
              <a class="carousel-control left" href="#carouselOfertas" data-slide="prev">
                <div class="push20"></div>
                  <span class="fa fa-angle-left"></span>
              </a>
              <a class="carousel-control right" href="#carouselOfertas" data-slide="next">
                <div class="push20"></div>
                  <span class="fa fa-angle-right"></span>
              </a>

            </div>

            <?php

              }

            ?>

            <div class="push20"></div>
                                  
                                
              
            </div>

        </div>

        
		<?php include "jsLib.php"; ?>

    <script type="text/javascript" src="libs/jquery-qrcode-master/src/jquery.qrcode.js"></script>
    <script type="text/javascript" src="libs/jquery-qrcode-master/src/qrcode.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.touchswipe/1.6.4/jquery.touchSwipe.min.js"></script>

    <script>

      $(document).ready(function(){

        $('.carousel').carousel({
        interval: 5000
      });

        $(".carousel").swipe({

          swipe: function(event, direction, distance, duration, fingerCount, fingerData) {

            if (direction == 'left') $(this).carousel('next');
            if (direction == 'right') $(this).carousel('prev');

          },
          allowPageScroll:"vertical"

        });

      });
      
      <?php if(1==1){ ?>
        geraQRCode();
      <?php } ?>

      function geraQRCode(){
        $("#qrcodeCanvas").html("");
        jQuery('#qrcodeCanvas').qrcode({
          text: "<?=$linkCode?>",
          width: 150,
          height: 150
        }); 
        
      }

    </script>		

    </body>
</html>

