<?php 
include './_system/_functionsMain.php';

  $cod_empresa = 19;
 
      $_SESSION['login'] = "OK";
      if($_SESSION["COD_RETORNO"]!='')
      {$cod_cliente=$_SESSION["COD_RETORNO"];} else {$cod_cliente= fnDecode($_GET['secur']);} 
	   
	$sql2="SELECT *  FROM clientes   WHERE NUM_CARTAO=$cod_cliente and COD_EMPRESA=19";
        $qrBuscaCliente = mysqli_fetch_assoc(mysqli_query(connTemp(19,''),$sql2)); 
       //fnescreve($sql2);

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

  $log_bannerlista = 'S';
 
?>	

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">

        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height, target-densitydpi=device-dpi" />

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
                $("#menu").mmenu();
            });
        </script>        

    </head>

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
    .shadow{
       -webkit-box-shadow: 0px 0px 8px -2px rgba(204,200,204,1);
        -moz-box-shadow: 0px 0px 8px -2px rgba(204,200,204,1);
        box-shadow: 0px 0px 8px -2px rgba(204,200,204,1);
        /*width: 100%;*/
        border-radius: 5px;
    }

  .carousel{
        border-radius: 10px 10px 10px 10px;
        overflow: hidden;
    }
   .carousel-caption{
         color: <?=$cor_textos?>;
        /*background-color: rgba(0,0,0,0.2);*/
        border-radius: 30px 30px 30px 30px;
        padding-top: 5px;
        padding-bottom: 5px;
        bottom: 0px;
        background-color: rgba(255,255,255,0.7);
    }
    .contorno{
      /*-webkit-text-fill-color: white;  Will override color (regardless of order) */
      /*-webkit-text-stroke-width: 0.5px;
      -webkit-text-stroke-color: white;*/
      text-shadow: 1px 1px black;
    }

    .carousel-indicators{
        z-index: 0;
    }

    .carousel-control.left, .carousel-control.right {
        background-image: none
    }

  .img-lista{
    height: 85px; 
    width: 85px;
    border-radius: 50px; 
  }

  .center{
    margin: auto;
    position:absolute;
    right: 0;
    left: 0;
    top: 50%;
      transform: translateY(-50%);
  }
</style>

    <body class="bgColor" data-gr-c-s-loaded="true">
    
        <?php 
        $tituloPagina = "Minhas Ofertas";
        include "menu.php";

        //echo "<h1>_".$_SESSION['login']."</h1>";
        
        ?>  

        <div class="container">

            <div class="push50"></div>

            
              <div class="row">

                <div class="col-md-12">

                <?php  //if ($cod_cliente=='01734200014') { ?>
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

                    }else{
                      // echo 'teste dev';
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


            <div class="push30"></div>
                        
        </div> <!-- /container -->	

		<?php include "jsLib.php"; ?>		

    </body>
</html>

<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.touchswipe/1.6.4/jquery.touchSwipe.min.js"></script>

<script type="text/javascript">
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
    $('input[type=radio][name=DES_PLACA]').change(function() {
        var secur = "<?=$_GET[secur]?>",
        des_placa = $(this).val();
        $('#btnGeraToken').attr("href","novoGeraTokem.do?secur="+secur+"&idp="+des_placa);
        // alert("novoGeraTokem.do?secur="+secur+"&idp="+des_placa);
    });
</script>