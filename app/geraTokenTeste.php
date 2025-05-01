<?php 
    include 'header.php'; 
    $tituloPagina = "Tkn. Resgate";
    include "navegacao.php";
    include "controleSession.php";
    // include '_system/Lista_oferta.php';

    $cpf = $_SESSION['usuario'];

    @$cod_cliente=$_SESSION["usuario"];
    // fnEscreve($cod_cliente);  
    @$cod_entidad=$_SESSION["cod_entidad"];


    $cartao="select COD_CLIENTE, NUM_CGCECPF, NOM_CLIENTE from clientes where num_cartao='".$cod_cliente."'";
    $rcartao=mysqli_fetch_assoc(mysqli_query(connTemp(19,''), $cartao));

    // $sqlproc="CALL SP_VERIFICA_TOKEN('19', '".$rcartao['COD_CLIENTE']."')";
    // $returnproc=mysqli_fetch_assoc(mysqli_query(connTemp(19,''), $sqlproc));
    //  //fnEscreve($sqlproc);
    // if($returnproc['v_RESULTADO']=='S')
    // {    
     
      $sqlToken = "SELECT * FROM token_resgate 
                   WHERE NUM_CGCECPF = '$rcartao[NUM_CGCECPF]'
                   AND COD_MSG = 0";
      $arrayToken = mysqli_query(connTemp(19,''), $sqlToken);

      $des_placa = fnLimpaCampo(fnDecode($_GET['idp']));
      // $placa = fnDecode($_GET['idp']);

      if(mysqli_num_rows($arrayToken) == 0){

        // fnEscreve($des_placa);

        do {

          $senha = fngeraSenha(6, true, true, true);
          
          $sqlToken = "SELECT 1 FROM token_resgate WHERE DES_TOKEN = '$senha'";

          $arrayToken = mysqli_query(connTemp(19,''),$sqlToken);

          $existeTkn = mysqli_num_rows($arrayToken);

        } while ($existeTkn > 0);

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
        // echo($gravatokem);
        mysqli_query(connTemp(19,''), $gravatokem); 


      }else{

        $qrToken = mysqli_fetch_assoc($arrayToken);

        $gravatokem="UPDATE token_resgate SET des_placa = '$des_placa' 
                     WHERE DES_TOKEN = '$qrToken[DES_TOKEN]' 
                     AND NUM_CGCECPF = '$rcartao[NUM_CGCECPF]'";
        /*if($cod_cliente=='01734200014')
        {
            echo $gravatokem;
        }*/

        // echo $gravatokem;
        mysqli_query(connTemp(19,''), $gravatokem);

        // if($cod_cliente=='01734200014' || $cod_cliente=='31175927848'){
        //   // PLACA + $I + TOKEN
        //   $senha = $des_placa.'$I'.$qrToken['des_tokem'];
        //   // echo $senha;
        // }else{
        //   $senha = $qrToken['des_tokem'];
        // }

          $senha = $qrToken['DES_TOKEN'];

      }
 
    $msg='Token gerado com sucesso!';
    $img='<div style="border-radius: 20px; background: #02520A;">
            <span class="fa-stack" style="height: 100px; width: 100%;">
              <i class="fa fa-mobile" style="font-size: 100px; color: #FFF;"></i>
              <i class="fa fa-check fa-stack-2x" style="font-size: 30px; color: #FFF; margin-top: 35px;"></i>
            </span>
          </div>';
    $msg_placa = '<b>Token gerado para a placa:</b> <span style="font-weight: 900!important; color: #02520A!important;">'.$des_placa.'</span>';
        //fnEscreve($senha); 

        // include './_system/codebar/BarcodeGenerator.php';
        // include './_system/codebar/BarcodeGeneratorHTML.php';
        // //$generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
        // $generator = new \Picqer\Barcode\BarcodeGeneratorHTML();    
        // $codbar= $generator->getBarcode($senha, $generator::TYPE_CODE_39,2.5,60);     
             
    // }

    // else{

    //     $msg='Limite excedido!';  
    //     $img='<div class="bg-danger" style="border-radius: 20px;">
    //             <span class="fa-stack" style="height: 100px; width: 100%;">
    //               <i class="fa fa-mobile" style="font-size: 100px; color: #FFF;"></i>
    //               <i class="fa fa-times fa-stack-2x" style="font-size: 30px; color: #FFF; margin-top: 35px;"></i>
    //             </span>
    //           </div>';
    //     $msg_placa = "";
    // }

    if(1==1){
      $linkCode = "$senha";
    }

    // include './_system/lista_oferta.php';

    // $arrayCampos = explode(";", $_SESSION["KEY"]);

    // $dadoslogin = array(
    //     '0'=>$arrayCampos[0],
    //     '1'=>$arrayCampos[1],
    //     '2'=>$arrayCampos[3],
    //     '3'=>'maquina',
    //     '4'=>$arrayCampos[2]
    // );

    // $arrayOfertas=fnofertas($cod_cliente,$dadoslogin);

    if ($cod_cliente=='01734200014') {

      // print_r($arrayOfertas);
      // exit();

    }

    $placa = $des_placa;

    // $log_bannerlista = 'S';

?>

<style>
    body {
        padding-bottom: 40px;
        background-color: #eee;
        font-size: 14px;
        color: #03214f;
    }

    .radio-inline{
        padding-left: 0;
    }

    .placa{
        list-style-type: none!important;
    }
    
    .chec-radio .radio-inline .clab {
/*        cursor: pointer;*/
/*        background: #e7e7e7;*/
/*        padding: 7px 20px;*/
        color: #2c3e50;
    }
    .chec-radio label.radio-inline input[type="radio"] {
        display: none;
    }
    .chec-radio label.radio-inline input[type="radio"]:checked+div {
        color: #fff;
        background-color: #2c3e50;
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
                <h4 style="margin-bottom: 0;">Token gerado com sucesso!</h4>
                <span class="f21">
                    <strong style="font-size: 36px;"><?php echo $linkCode; ?></strong>
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

          <?php  if(1==1){ ?>

            <div id="qrcodeCanvas"></div>

            <div class="push5"></div>

            <div class="row">
                <div class="col-xs-12 text-center">
                    <p class="f16" style="margin-bottom: 0;">Token de resgate gerado para</p>
                    <p class="f20"><b><?=$rcartao['NOM_CLIENTE']?></b></p>
                    <p class="f16">O token expirará em <br/><span id="timer" class="f18">03:00</span></p>
                </div>
            </div>

            <div class="push10"></div>
      
            <div class="row" id="placasConteudo">

                <div class="col-xs-12 text-center">
                    <p class="f16" style="margin-bottom:0;">Placas Cadastradas</p>
                </div>                                  

                    <?php

                        $sql = "SELECT COD_VEICULOS, DES_PLACA FROM VEICULOS WHERE COD_CLIENTE_EXT = $_SESSION[usuario] AND COD_EMPRESA = $cod_empresa AND TRIM(DES_PLACA) != '' AND DES_PLACA IS NOT NULL";
                        // fnEscreve($sql);
                        $arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);

                        // $loopH = 0;
                        $count = 0;
                        $des_placa = "";

                        while ($qrVeic = mysqli_fetch_assoc($arrayQuery)) {

                            if($placa == $qrVeic['DES_PLACA']){
                                $checked = "style='font-weight:700;'";
                            }else{
                                $checked = "";
                            }

                        ?>


                            <div class="placa col-xs-12 text-center chec-radio">
                                <div class="form-group">
                                    <label class="radio-inline">
                                        <div class="clab text-muted f20" <?=$checked?>>
                                            <span class="fa fa-car"></span>
                                            &nbsp; <?=$qrVeic['DES_PLACA']?>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- <div class="col-xs-12 text-center" style="font-size: 20px!important;">
                                <p class="text-muted"><span class="fa fa-car"></span>&nbsp; <?=$qrVeic['DES_PLACA']?></p>
                            </div> -->

                        <?php

                            // if($loopH == 2){
                            //     echo "<div class='push10'></div>";
                            // }else{
                            //     $loopH++;
                            // }

                            $count++;
                        }

                    ?>

                </div>

          <?php  }else{ ?>

            <div class="barcode text-center">
              <?php  echo  $codbar; ?>
            </div>

          <?php  } ?>

          <div class="push20"></div>

          <div class="col-xs-12">
            <p class="f16">Para obter seu resgate no momento do pagamento, apresente este código ao frentista, junto com um <b>comprovante de identidade</b></p>
          </div>

          <div class="push20"></div>
          
        
      </div>

        <form id="formulario" method="POST" action="tipoToken.do?key=<?=$_GET[key]?>&idU=<?=$usuEncrypt?>">
            <input type="hidden" name="DES_PLACA" id="DES_PLACA" value="<?=$placa?>">
            <input type="hidden" name="DES_TOKEM" id="DES_TOKEM" value="<?=$senha?>">
            <input type="hidden" name="COD_CLIENTE" id="COD_CLIENTE" value="<?=$cod_cliente?>">
            <input type="hidden" name="DES_TIPO" id="DES_TIPO" value="resg">
        </form>

    <?php include 'footer.php'; ?>

    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.touchswipe/1.6.4/jquery.touchSwipe.min.js"></script>
    <script type="text/javascript" src="libs/jquery-qrcode-master/src/jquery.qrcode.js"></script>
    <script type="text/javascript" src="libs/jquery-qrcode-master/src/qrcode.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.touchswipe/1.6.4/jquery.touchSwipe.min.js"></script>

    <script>

        let duration = 10, // 3 minutos em segundos
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