<?php 
include './_system/_functionsMain.php';
include './_system/lista_oferta.php';

    $cod_empresa = 19;
 
      $_SESSION['login'] = "OK";
      if($_SESSION["COD_RETORNO"]!='')
      {$cod_cliente=$_SESSION["COD_RETORNO"];} else {$cod_cliente= fnDecode($_GET['secur']);} 
	   
	$sql2="SELECT *  FROM clientes   WHERE NUM_CARTAO=$cod_cliente and COD_EMPRESA=19";
        $qrBuscaCliente = mysqli_fetch_assoc(mysqli_query(connTemp(19,''),$sql2)); 
       //fnescreve($sql2);
    
    $nome_cliente= explode(' ',trim($qrBuscaCliente['NOM_CLIENTE']));
    $cod_entidad = $qrBuscaCliente['COD_ENTIDAD'];
    $_SESSION["cod_entidad"]=$qrBuscaCliente['COD_ENTIDAD'];
    
    $sql3="select NOM_ENTIDAD,COD_EXTERNO,COD_ENTIDAD from ENTIDADE where COD_ENTIDAD = $cod_entidad";
    $qrBuscaEntidade = mysqli_fetch_assoc(mysqli_query(connTemp(19,''),$sql3));		
   // fnEscreve($sql3);	
    @$nom_entidad = $qrBuscaEntidade['NOM_ENTIDAD'];
    @$COD_ENTIDAD = $qrBuscaEntidade['COD_ENTIDAD'];
  
//cookies

$json=array("RD_userId"=>$cod_cliente,
            "RD_userCompany"=>$COD_ENTIDAD,
            "RD_userMail"=>$qrBuscaCliente['DES_EMAILUS'],
            "RD_userName"=>$qrBuscaCliente['NOM_CLIENTE'],
            "RD_userType"=>$qrBuscaCliente['COD_TPCLIENTE']);
$jsoncookies=json_encode($json);
//$jsoncookies=str_replace(':', '=', $jsoncookies);

setcookie("REDE_DUQUE",$jsoncookies);
setcookie("REDE_DUQUE_V2", base64_encode(fnEncode($jsoncookies)));

    $sqlBUSCA="SELECT COD_USUARIO,
                LOG_USUARIO,
                DES_SENHAUS,
                COD_UNIVEND,
                COD_EMPRESA
         FROM usuarios 
         WHERE cod_empresa=$cod_empresa
               AND COD_TPUSUARIO=10 AND 
               COD_EXCLUSA = 0 LIMIT 1";
   $resultuser=mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $sqlBUSCA));
   $COD_UNIVENDARRAY = explode(",", $resultuser['COD_UNIVEND']); 
   
   $key=$resultuser['LOG_USUARIO'].';'.fnDecode($resultuser['DES_SENHAUS']).';'.$resultuser['COD_EMPRESA'].';'.$COD_UNIVENDARRAY['0'];
   $key= fnEncode($key);
   $_SESSION["KEY"]= fnDecode($key);

    $arrayCampos = explode(";", $_SESSION["KEY"]);

    $dadoslogin = array(
        '0'=>$arrayCampos[0],
        '1'=>$arrayCampos[1],
        '2'=>$arrayCampos[3],
        '3'=>'maquina',
        '4'=>$arrayCampos[2]
    );

    $arrayOfertas=fnofertas($qrBuscaCliente['NUM_CGCECPF'],$dadoslogin);

    // $sqlCli = "SELECT COD_CLIENTE, NOM_CLIENTE FROM CLIENTES WHERE NUM_CGCECPF ='$qrBuscaCliente[NUM_CGCECPF]' AND COD_EMPRESA = $cod_empresa";
    // $arrayQueryCli = mysqli_query(connTemp($cod_empresa,''),$sqlCli);
    // $qrCli = mysqli_fetch_assoc($arrayQueryCli);

    $cod_cliente_consulta = $qrBuscaCliente['COD_CLIENTE'];
    $nom_cliente = $qrBuscaCliente['NOM_CLIENTE'];
    $cod_profiss = $qrBuscaCliente['COD_PROFISS'];
    $nom_cliente = explode(" ", $nom_cliente);
    $nom_cliente = ucfirst(strtolower($nom_cliente[0]));

    $sql = "CALL total_wallet('$cod_cliente_consulta', '$cod_empresa')";
                    
    //fnEscreve($sql);
    
    $arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
    $qrBuscaTotais = mysqli_fetch_assoc($arrayQuery);
    
    
    if (isset($arrayQuery)){
        
        $total_creditos = $qrBuscaTotais['TOTAL_CREDITOS'];
        $total_debitos = $qrBuscaTotais['TOTAL_DEBITOS'];
        $credito_disponivel = $qrBuscaTotais['CREDITO_DISPONIVEL'];
        $credito_aliberar = $qrBuscaTotais['CREDITO_ALIBERAR'];
        $credito_expirados = $qrBuscaTotais['CREDITO_EXPIRADOS'];
        $credito_bloqueado = $qrBuscaTotais['CREDITO_BLOQUEADO'];
    }else{
        
        $total_creditos = 0;
        $total_debitos = 0;
        $credito_disponivel = 0;
        $credito_aliberar = 0;
        $credito_expirados = 0;
        $credito_bloqueado = 0;
        
    }

    $casasDec = 0;

 
?>	

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

    <style>

        .radio-inline{
            padding-left: 0;
        }

        .placa{
            list-style-type: none!important;
        }
        
        .chec-radio .radio-inline .clab {
            cursor: pointer;
            background: #e7e7e7;
            padding: 7px 20px;
            color: #2c3e50;
        }
        .chec-radio label.radio-inline input[type="radio"] {
            display: none;
        }
        .chec-radio label.radio-inline input[type="radio"]:checked+div {
            color: #fff;
            background-color: #2c3e50;
        }
        /*.chec-radio label.radio-inline input[type="radio"]:checked+div:before {
            content: "\e013";
            margin-right: 5px;
            font-family: 'Glyphicons Halflings';
        }*/
        .shadow{
           -webkit-box-shadow: 0px 0px 18px -2px rgba(204,200,204,1);
            -moz-box-shadow: 0px 0px 18px -2px rgba(204,200,204,1);
            box-shadow: 0px 0px 18px -2px rgba(204,200,204,1);
            width: 100%;
            border-radius: 5px;
        }
        .carousel{
            border-radius: 10px 10px 10px 10px;
            overflow: hidden;
        }
        .carousel-caption{
            /*background-color: rgba(0,0,0,0.2);*/
            border-radius: 30px 30px 30px 30px;
        }
        .contorno{
          color: black;
          -webkit-text-fill-color: white; /* Will override color (regardless of order) */
          /*-webkit-text-stroke-width: 0.5px;
          -webkit-text-stroke-color: white;*/
          text-shadow: 1px 1px black;
        }

        .separador{
            border: unset;
            max-width: unset;
            width: unset;
            border-top: 1px solid <?=$cor_textfull?>; 
            margin: 0; 
            padding: 0; 
        }

    </style>

    <body class="bgColor" data-gr-c-s-loaded="true">
	
 		<?php 
		$tituloPagina = "Home";
		include "menu.php";

		//echo "<h1>_".$_SESSION['login']."</h1>";
		
		?>	

        <div class="container">

            <div class="push50"></div>

            <?php 
                // if($qrBuscaCliente['NUM_CGCECPF'] == "26097913800" || $qrBuscaCliente['NUM_CGCECPF'] == "1734200014" || $qrBuscaCliente['NUM_CGCECPF']=="33233121806" || $qrBuscaCliente['NUM_CGCECPF']=="42147177830"){ 
                if($credito_disponivel > 0){ 
            ?>

                <div class="col-xs-12 text-center shadow2 fundoCel1" style=" color: <?=$cor_textfull?>; background-color: <?=$cor_fullpag?>;  border-radius: 5px;">
            
                    <div class="push"></div>

                    <div class="col-md-12 textoCel1">
                        <h4 style="margin-bottom: 0;"><?=$nom_cliente?>, <span class="f12">você tem</span></h4>
                        <span class="f21">
                            <strong style="font-size: 36px;"><?=fnValor($credito_disponivel,2)?></strong>
                        </span>
                        <div class="push"></div>
                        <span class="f10">REAIS ACUMULADO(S)</span>
                    </div>

                    <div class="push10"></div>

                    <div class="col-md-12 texto2Cel1">

                        <div class="push10"></div>

                        <hr class="separador">

                        <div class="push10"></div>
                        
                        <div class="col-xs-6 text-center">
                            <span class="f14"><?=fnValor($total_debitos,2)?></span><br/>
                            <span class="f12">Total Resgatado</span>
                        </div>
                        <div class="col-xs-6 text-center">
                            <span class="f14"><?=fnValor($total_creditos,2)?></span><br/>
                            <span class="f12">Total Ganho</span>
                        </div>

                        <div class="push10"></div>
                                
                    </div>

                </div>

            <?php

                }else{

            ?>

                <div class="row">
                    
                    <div class="col-xs-10 col-xs-offset-1 text-center">
                        <h4>Olá <span style="font-weight: 900!important;"><?php echo $nome_cliente[0]; ?></span>.</h4>
                        <div class="push"></div>
                        <h4>Seja bem vindo(a)!</h4>
                    </div>

                </div>
        
            <?php

                }

            ?>

            <div class="push30"></div>

            <?php
                    // fnEscreve($log_bannerhome);
                if($log_bannerhome == "S"){

            ?>

                <div class="push50"></div>

                <div class="col-xs-12">

                    <div id="carouselOfertas" class="carousel slide">

                      <ol class="carousel-indicators">
                        <?php

                            if (array_key_exists("0", $arrayOfertas['oferta0']['produtoticket'])){
                                $count = 0;
                                $active = 'active';

                                // fnEscreve('if 1');
            
                                foreach ($arrayOfertas['oferta0']['produtoticket'] AS $chave => $valor){
                                    ?>
                                        <li data-target="#carouselOfertas" data-slide-to="<?=$count?>" class="<?=$active?>"></li>
                                    <?php
                                    $count++;
                                    $active = '';                                    
                                }
                                
                            } else {
                               ?>
                                <li data-target="#carouselOfertas" data-slide-to="0" class="active"></li>
                               <?php
                            }

                        ?>
                      </ol>
                      <div class="carousel-inner">

                        <?php

                            if (array_key_exists("0", $arrayOfertas['oferta0']['produtoticket'])){
                                 // fnEscreve('if 2');

                            $active = 'active';
            
                            foreach ($arrayOfertas['oferta0']['produtoticket'] AS $chave => $valor){

                                 ?>

                                    <div class="item <?=$active?>">
                                    <?php if($valor['imagem']!=''){ ?>
                                      <img src="<?=$valor['imagem']?>" width="100%" height="160px" style="height: 190px;">
                                    <?php }else{ ?>
                                        <img src="../media/clientes/branco.jpg" width="100%" height="160px" style="height: 190px;">
                                    <?php } ?>
                                      <div class="carousel-caption d-none d-md-block contorno">
                                        <p style="font-size: 24px;"><b><?=strtoupper($valor['descricao'])?></b></p>
                                        <h5 style="font-size: 13px;"><strike>DE: R$<?=fnValor($valor['preco'],2)?></strike></h5>
                                        <h5><b>POR: <span style="font-size: 21px;">R$<?=fnValor($valor['valorcomdesconto'],2)?></span></b></h5>
                                      </div>
                                    </div>

                                 <?php

                                 $active = '';

                            }
                            
                        } else {
                             // fnEscreve('else 2');

                            ?>

                            <div class="item active">
                              <?php if($arrayOfertas['oferta0']['produtoticket']['imagem']!=''){ ?>
                                  <img src="<?=$arrayOfertas['oferta0']['produtoticket']['imagem']?>" width="100%" height="160px" style="height: 190px;">
                              <?php }else{ ?>
                                <img src="../media/clientes/branco.jpg" width="100%" height="160px" style="height: 190px;">
                              <?php } ?>
                              <div class="carousel-caption d-none d-md-block contorno">
                                <p style="font-size: 24px;"><b><?=strtoupper($arrayOfertas['oferta0']['produtoticket']['descricao'])?></b></p>
                                <h5 style="font-size: 13px;"><strike>DE: R$<?=fnValor($arrayOfertas['oferta0']['produtoticket']['preco'],2)?></strike></h5>
                                <h5><b>POR: <span style="font-size: 21px;">R$<?=fnValor($arrayOfertas['oferta0']['produtoticket']['valorcomdesconto'],2)?></span></b></h5>
                              </div>
                            </div>

                            <?php
                            
                        }

                        ?>

                      </div>

                        <!-- Carousel controls -->
                        <!-- <a class="carousel-control left" href="#carouselOfertas" data-slide="prev">
                            <span class="glyphicon glyphicon-chevron-left"></span>
                        </a>
                        <a class="carousel-control right" href="#carouselOfertas" data-slide="next">
                            <span class="glyphicon glyphicon-chevron-right"></span> -->
                        </a>

                    </div>
                    
                </div>

            <?php
                }else{
            ?>
                    <div class="push10"></div>
            <?php
                }
            ?>



            <!-- <div class="push20"></div>  -->

            <!-- <div class="row">
                
                <div class="col-xs-10 col-xs-offset-1 text-center">
                    <h4>Olá <span style="font-weight: 900!important;"><?php echo $nome_cliente[0]; ?></span>.</h4>
                    <div class="push"></div>
                    <h4>Seja bem vindo(a)!</h4>
                </div>

            </div>
		
			

            <div class="push30"></div> -->
            <!-- <div class="push50"></div> -->
            <!-- <div class="push20"></div> -->

            <div class="row">
              <div class="col-xs-12 text-center">
                <h4 style="font-weight: 900!important;">SEUS VEÍCULOS CADASTRADOS</h4>
              </div>
              <div class="col-md-12">
                <hr style="margin:0; border-color: #3c3c3c; width: 100%; max-width: 100%;">
              </div>
            </div>

            <div class="push30"></div>
			
    		<div class="row" id="placasConteudo">                                           

                <?php

                    $sql = "SELECT COD_VEICULOS, DES_PLACA FROM VEICULOS WHERE COD_CLIENTE_EXT = $cod_cliente AND COD_EMPRESA = $cod_empresa AND TRIM(DES_PLACA) != '' AND DES_PLACA IS NOT NULL";
                    // fnEscreve($sql);
                    $arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);

                    // $loopH = 0;
                    $count = 0;
                    $des_placa = "";

                    while ($qrVeic = mysqli_fetch_assoc($arrayQuery)) {

                        if($count == 0){

                            $des_placa = fnEncode($qrVeic['DES_PLACA']);
                            $checked = "checked";
                        }else{
                            $checked = "";
                        }

                    ?>


                        <div class="placa col-xs-12 text-center chec-radio">
                            <div class="form-group">
                                <label class="radio-inline">
                                    <input type="radio" id="DES_PLACA" name="DES_PLACA"  value="<?=fnEncode($qrVeic[DES_PLACA])?>" <?=$checked?> required>
                                    <div class="clab text-muted">
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

             
            <div class="push10"></div>

            <div class="row">
                <div class="col-xs-10 col-xs-offset-1 text-center">
                    <?php  
                        if($des_placa != ""){
                    ?>
                            <p><b>Para gerar o Token, selecione na lista acima o veículo que será abastecido.</b></p>
                            <!-- <div class="push10"></div> -->
                            <a href="novoGeraTokem.do?secur=<?=$_GET[secur]?>&idp=<?=$des_placa?>" id="btnGeraToken" class='btn btn-primary btn-block'><i class="fa fa-unlock-alt" aria-hidden="true"></i>&nbsp;&nbsp;Gerar Token de Desconto</a>
                            <?php 
                                // if($qrBuscaCliente['NUM_CGCECPF'] == "26097913800" || $qrBuscaCliente['NUM_CGCECPF'] == "1734200014" || $qrBuscaCliente['NUM_CGCECPF']=="33233121806"){ 
                                if($credito_disponivel > 0){ 
                            ?>
                            <!-- <div class="push10"></div> -->
                            <a href="geraTokenRegate.do?secur=<?=$_GET[secur]?>&idp=<?=$des_placa?>" id="btnGeraTokenResgate" class='btn btn-primary btn-block'><i class="fa fa-money" aria-hidden="true"></i>&nbsp;&nbsp;Gerar Token de Resgate</a>
                            <?php 
                                } 
                            ?>
                    <?php
                        }else{
                    ?>
                            <a href="javascript:void(0)" class='btn btn-primary btn-block' disabled><i class="fa fa-lock" aria-hidden="true"></i>&nbsp;&nbsp;Nenhum veículo cadastrado.</a>
                    <?php
                        }
                    ?>
                </div>
            </div> 

            <div class="push30"></div>
                        
        </div> <!-- /container -->	

		<?php include "jsLib.php"; ?>		

    </body>
</html>

<!-- <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.touchswipe/1.6.4/jquery.touchSwipe.min.js"></script> -->

<script type="text/javascript">
    $(document).ready(function(){

        // $('.carousel').carousel({
        //   interval: 5000
        // });

        // $(".carousel").swipe({

        //   swipe: function(event, direction, distance, duration, fingerCount, fingerData) {

        //     if (direction == 'left') $(this).carousel('next');
        //     if (direction == 'right') $(this).carousel('prev');

        //   },
        //   allowPageScroll:"vertical"

        // });

    });
    $('input[type=radio][name=DES_PLACA]').change(function() {
        var secur = "<?=$_GET[secur]?>",
        des_placa = $(this).val();
        $('#btnGeraToken').attr("href","novoGeraTokem.do?secur="+secur+"&idp="+des_placa);
        $('#btnGeraTokenResgate').attr("href","geraTokenRegate.do?secur="+secur+"&idp="+des_placa);
        // alert("novoGeraTokem.do?secur="+secur+"&idp="+des_placa);
    });
</script>