<?php 
        include 'header.php'; 
        $tituloPagina = "Token";
        include "navegacao.php";
        include "controleSession.php";
        include '_system/Lista_oferta.php';


        @$cod_cliente = $usuario;
        // fnEscreve($cod_cliente);  
        @$cod_entidad=$_SESSION["cod_entidad"];
        @$produto = fnLimpaCampo(fnDecode($_GET['idP']));


        $cartao="select COD_CLIENTE,NUM_CARTAO,NOM_CLIENTE from clientes where num_cartao='".$usuario."' AND COD_EMPRESA = 19";
        $rcartao=mysqli_fetch_assoc(mysqli_query(connTemp(19,''), $cartao));

        // $sqlproc="CALL SP_VERIFICA_TOKEN('19', '".$rcartao['COD_CLIENTE']."')";
        // $returnproc=mysqli_fetch_assoc(mysqli_query(connTemp(19,''), $sqlproc));
        //  //fnEscreve($sqlproc);
        // if($returnproc['v_RESULTADO']=='S' && $rcartao['NUM_CARTAO'] != 0 && $rcartao['NUM_CARTAO'] != ""){    

        //   $sqlToken = "SELECT * FROM TOKEM 
        //                WHERE COD_CLIENTE = $usuario 
        //                AND LOG_USADO = 'N'";
        //   $arrayToken = mysqli_query(connTemp(19,''), $sqlToken);

        //   $des_placa = fnLimpaCampo(fnDecode($_GET['idp']));
        //   $unidadePref = fnLimpaCampoZero(fnDecode($_GET['uni']));

        //   if(mysqli_num_rows($arrayToken) == 0){

        //     // fnEscreve($des_placa);

        //     do {

        //       $senha = fngeraSenha(6, true, true, true);
              
        //       $sqlToken = "SELECT 1 FROM tokem WHERE DES_TOKEM = '$senha'";

        //       $arrayToken = mysqli_query(connTemp(19,''),$sqlToken);

        //       $existeTkn = mysqli_num_rows($arrayToken);

        //     } while ($existeTkn > 0);



        //     $gravatokem="INSERT INTO tokem 
        //                  (des_tokem, 
        //                  cod_cliente, 
        //                  dat_cadastr, 
        //                  cod_loja,
        //                  cod_unipref,
        //                  des_placa
        //                  ) 
        //                  VALUES ('".addslashes($senha)."', 
        //                           '".$usuario."', 
        //                           '".date('Y-m-d H:i:s')."', 
        //                           '".$cod_entidad."',
        //                           '".$unidadePref."',
        //                           '".$des_placa."'    
        //                           );";

        //     // fnEscreve($gravatokem);
        //     mysqli_query(connTemp(19,''), $gravatokem);

        //   }else{

        //     $qrToken = mysqli_fetch_assoc($arrayToken);

        //     $gravatokem="UPDATE tokem SET des_placa = '$des_placa',
        //                                   COD_UNIPREF = '$unidadePref'
        //                  WHERE DES_TOKEM = '$qrToken[des_tokem]' 
        //                  AND COD_CLIENTE = $usuario";
        //     /*if($cod_cliente=='01734200014')
        //     {
        //         echo $gravatokem;
        //     }*/
        //     mysqli_query(connTemp(19,''), $gravatokem);

        //     if($usuario=='01734200014' || $usuario=='31175927848'){
        //       // PLACA + $I + TOKEN
        //       $senha = $des_placa.'$I'.$qrToken['des_tokem'];
        //       // echo $senha;
        //     }else{
        //       $senha = $qrToken['des_tokem'];
        //     }

        //       $senha = $qrToken['des_tokem'];

        //   }

        // $msg='Token gerado com sucesso!';
        // $img='';
        // $msg_placa = '<b>Token gerado para a placa:</b> <span style="font-weight: 900!important; color: #02520A!important;">'.$des_placa.'</span>';
        //     //fnEscreve($senha); 

        //     // include './_system/codebar/BarcodeGenerator.php';
        //     // include './_system/codebar/BarcodeGeneratorHTML.php';
        //     // //$generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
        //     // $generator = new \Picqer\Barcode\BarcodeGeneratorHTML();    
        //     // $codbar= $generator->getBarcode($senha, $generator::TYPE_CODE_39,2.5,60);     
                     
        // }else if($rcartao['NUM_CARTAO'] == 0 || $rcartao['NUM_CARTAO'] == ""){

        //   $msg='A operação falhou. Por favor, faça login novamente.';  
        //   $img='';
        //   $msg_placa = "";

        // }else{

        //     $msg='Limite excedido!';  
        //     $img='';
        //     $msg_placa = "";
        // }

        $senha = fngeraSenha(6, true, true, true);

        if(1==1){
          $linkCode = "$qrBrinde[COD_EXTERNO]\t$senha";
        }

        $msg='Token gerado com sucesso!';
        $img='';

        $sqlBrinde = "SELECT EAN, DES_PRODUTO, COD_EXTERNO 
                      FROM PRODUTOCLIENTE
                      WHERE COD_EMPRESA = $cod_empresa 
                      AND COD_PRODUTO = $produto";
        $qrBrinde=mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''), $sqlBrinde));

        // include './_system/lista_oferta.php';

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

    ?>

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

  .f20{
    font-size: 20px;
  }


</style>    
        
        <div class="container text-center">

          <div class="push50"></div>
          <div class="push5"></div>

          <div class="col-xs-12 text-center shadow2 fundoCel1" style=" color: <?=$cor_textfull?>; background-color: <?=$cor_fullpag?>;  border-radius: 30px 30px 5px 5px;">
        
            <div class="push"></div>

            <div class="col-md-12 textoCel1">
                <h4 style="margin-bottom: 0;"><?=$msg?></h4>
                <span class="f21">
                    <strong style="font-size: 36px;"><?php echo $senha; ?></strong>
                </span>
            </div>

            <div class="push10"></div>

            <div class="col-md-12 texto2Cel1">

                <div class="push10"></div>
                        
            </div>

        </div>

          <!-- <div class="row">
            <div class="col-xs-3 text-center">
              <?=$img?>
            </div>
            <div class="col-xs-9 text-center" style="padding-left: 0; padding-right: 0;">
              <h5 style="font-weight: 900!important;"><?php echo strtoupper($msg); ?></h5>
              <div class="push"></div>
              <h1 style="font-weight: 900!important; color: #02520A!important;"><?php echo $qrToken['des_tokem']; ?></h1>
              <div class="push5"></div>
              <p><?=$msg_placa?></p>
            </div>
          </div> -->

          <div class="push20"></div> 

          <?php if($msg != "Limite excedido!"){ ?>

            <div id="qrcodeCanvas"></div>

            <div class="push5"></div>

            <div class="row">
              <div class="col-xs-12 text-center">
                <p class="f14"><?=$qrBrinde['DES_PRODUTO']?><small><br><small>EAN:</small>&nbsp;<?=$qrBrinde['EAN']?></small></p>
                <p class="f20"><b><?=$rcartao['NOM_CLIENTE']?></b></p>
                <p class="f16">O token expirará em <br/><span id="timer" class="f18">03:00</span></p>
              </div>
            </div>

            <div class="push20"></div>

            <div class="col-xs-12">
              <p>Para obter seu prêmio, apresente este código a um atendente</p>
            </div>

          <?php }else{ ?>

            <div class="bg-danger" style="border-radius: 20px;">
              <span class="fa-stack" style="height: 100px; width: 100%;">
                <i class="fa fa-mobile" style="font-size: 100px; color: #FFF;"></i>
                <i class="fa fa-times fa-stack-2x" style="font-size: 30px; color: #FFF; margin-top: 35px;"></i>
              </span>
            </div>

          <?php } ?>

          <div class="push20"></div>

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
                            if($valor['imagem'] != ''){

                      ?>
                            <img src="<?=$valor['imagem']?>" width="100%">
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

                    if ($arrayOfertas['oferta0']['produtoticket']['imagem'] != ''){
                ?>

                       <div class="item <?=$active?>">
                      <?php 
                            if($arrayOfertas['oferta0']['produtoticket']['imagem'] != ''){

                      ?>
                            <img src="<?=$arrayOfertas['oferta0']['produtoticket']['imagem']?>" width="100%">
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
        
      </div>

        <form id="formulario" method="POST" action="tipoToken.do?key=<?=$_GET['key']?>&idU=<?=$usuEncrypt?>">
            <input type="hidden" name="DES_PLACA" id="DES_PLACA" value="<?=$des_placa?>">
            <input type="hidden" name="DES_TOKEM" id="DES_TOKEM" value="<?=$senha?>">
            <input type="hidden" name="COD_CLIENTE" id="COD_CLIENTE" value="<?=$cod_cliente?>">
            <input type="hidden" name="DES_TIPO" id="DES_TIPO" value="desc">
        </form>

    <?php include 'footer.php'; ?>

    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.touchswipe/1.6.4/jquery.touchSwipe.min.js"></script>
    <script type="text/javascript" src="libs/jquery-qrcode-master/src/jquery.qrcode.js"></script>
    <script type="text/javascript" src="libs/jquery-qrcode-master/src/qrcode.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.touchswipe/1.6.4/jquery.touchSwipe.min.js"></script>

    <script>

        let duration = 180, // 3 minutos em segundos
            timerDisplay = $('#timer'),
            minutes = "",
            seconds = "",
            interval = "";

        $(document).ready(function(){

            interval = setInterval(function() {
                minutes = Math.floor(duration / 60);
                seconds = duration % 60;

              minutes = minutes < 10 ? '0' + minutes : minutes;
              seconds = seconds < 10 ? '0' + seconds : seconds;

              timerDisplay.text(minutes + ':' + seconds);

              if (duration <= 0) {
                clearInterval(interval);
                $('#formulario').submit();
              }

              duration--;
            }, 1000);

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