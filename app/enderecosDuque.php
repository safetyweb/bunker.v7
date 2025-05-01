<?php

    include_once 'header.php'; 
    @$tituloPagina = "Endereços";
    include_once "navegacao.php";

    // $userid= $usuario;
    $cod_regiao = '9999';
    $inicio = 0;
    $itens_por_pagina = 5;

    $log_ativohs = fnLimpaCampo(fnDecode($_GET['idp']));
    $andAtivo = "";

    function distancia($lat1, $lon1, $lat2, $lon2) {
        $lat1 = deg2rad($lat1);
        $lat2 = deg2rad($lat2);
        $lon1 = deg2rad($lon1);
        $lon2 = deg2rad($lon2);

        $dist = (6371 * acos( cos( $lat1 ) * cos( $lat2 ) * cos( $lon2 - $lon1 ) + sin( $lat1 ) * sin($lat2) ) );
        $dist = number_format($dist, 2, '.', '');
        return $dist;
    }

    // fnEscreve($cod_empresa);

?>  
    <style>
    
        body {
            padding-bottom: 40px;
            background-color: #eee;
            font-size: 14px;
            color: #03214f;
        }
        
        .fa-60 {
            font-size: 50px;
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
        
    <div class="filtro"></div>

        <div class="container" id="relatorioConteudo">

            <div class="push50"></div>
            <div class="push20"></div>
        
        
  <?php      

    if($log_ativohs == "S"){
        $andAtivo = "AND LOG_ATIVOHS = 'S'
                     AND LOG_ESPECIAL = 'N'";
    }else if($log_ativohs == "C"){
        $andAtivo = "AND LOG_ATIVOHS = 'S'
                     AND LOG_ESPECIAL = 'S'";
    }

    if ($cod_regiao == 9999){
    $sql = "SELECT * from unidadevenda where COD_EMPRESA = 19 and COD_UNIVEND != 955 AND LOG_ESTATUS='S' AND lat != 0 AND lng != 0 $andAtivo  order by NOM_FANTASI";
    } else {
    $sql = "SELECT * from unidadevenda where COD_EMPRESA = 19 and COD_TIPOREG = $cod_regiao and COD_UNIVEND != 955 AND LOG_ESTATUS='S' AND lat != 0 AND lng != 0 $andAtivo  order by NOM_FANTASI";  
    }
    $arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());

    $count=0;
    $novoArrayEnderecos = array();
    while ($qrListaUniVendas = mysqli_fetch_assoc($arrayQuery)){

        array_push($novoArrayEnderecos, $qrListaUniVendas);
         
        // if(@$_COOKIE['RD_localAtual']!=''){
         
        //     $buscacord="SELECT * FROM location WHERE COD_CARTAO='$userid' OR IP ='".$_SERVER['REMOTE_ADDR']."';";
        //     $calccoord=mysqli_query(connTemp(19, ''), $buscacord);
        //     $retucoord=mysqli_fetch_assoc($calccoord);

        //     if($retucoord['LAT']!=''){               
               
        //         $km=distancia($retucoord['LAT'],$retucoord['LNG'],$qrListaUniVendas['lat'],$qrListaUniVendas['lng']);
        //         $qrListaUniVendas['distancia'] = $km;
        //         array_push($novoArrayEnderecos, $qrListaUniVendas);

        //     }else{
        //         //pegar geolocalização do chrome
        //         $geo=explode(',',$_COOKIE['RD_localAtual']); 
        //         $km=distancia($geo['0'],$geo['1'],$qrListaUniVendas['lat'],$qrListaUniVendas['lng']);
        //         $qrListaUniVendas['distancia'] = $km;

        //         array_push($novoArrayEnderecos, $qrListaUniVendas);

        //     } 
        
        
        // }else{
            
        //     $buscacord="SELECT * FROM location WHERE COD_CARTAO='$userid' or IP ='".$_SERVER['REMOTE_ADDR']."';";
        //     $calccoord=mysqli_query(connTemp(19, ''), $buscacord);
        //     $retucoord=mysqli_fetch_assoc($calccoord);
               
        //     $km=distancia($retucoord['LAT'],$retucoord['LNG'],$qrListaUniVendas['lat'],$qrListaUniVendas['lng']);
        //     $qrListaUniVendas['distancia'] = $km;

        //     array_push($novoArrayEnderecos, $qrListaUniVendas);
        
        // }
        
          
        
       }
       
        $sort = array();
        $count = 0;
        // foreach($novoArrayEnderecos as $k=>$v) {
        // $sort['distancia'][$k] = $v['distancia'];
        // }

        // array_multisort($sort['distancia'], SORT_ASC, $novoArrayEnderecos);
        
        foreach ($novoArrayEnderecos as $objeto) {

            if($count == $itens_por_pagina){
                break;
            }

            if ($count < $inicio) {
                $count++;
                continue;
            }

           
   ?> 

            <div class="row postos">

                <div class="col-xs-7" style="padding-right: 0;">
                    <div style='font-size: 16px; font-weight: 900!important;'><?=ucwords(strtolower($objeto['NOM_FANTASI']))." - ".ucwords(strtolower($objeto['DES_BAIRROC']))?></div>
                    <div style="font-size: 12px;"><b><?php echo utf8_decode($objeto['DES_ENDEREC']).', '.$objeto['NUM_ENDEREC']; ?></b></div>
                </div>

                <div class="col-xs-2" style="padding-right: 0; padding-left: 0;">
                    
                    <div class="row">
                        <div class="col-xs-12 text-center">
                            <img src="img/desconto.png" width="35px">
                            <br>
                            <p style="font-size: 11px;">Desconto</p>
                        </div>
                        <div class="col-xs-12 text-center">
                            <?php 

                                if($objeto['LOG_ESPECIAL'] == "S"){
                            ?>
                                    
                                    <span class="fas fa-dollar-sign" style="font-size: 35px; color:#03204F"></span>
                                    <br>
                                    <p style="font-size: 11px;">Cashback</p>
                                    
                            <?php 
                                }
                            ?>
                        </div>
                    </div>

                </div>
                <div class="col-xs-3">

                    <div class="row">
                        <div class="col-xs-12 text-right">
                            <!-- <div style='font-size: 16px; font-weight: 900!important;' id='distancia'><?php echo $objeto['distancia'].' <span style="font-size:10px;">KM</span>';?></div>  -->
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12">
                            <a class="abrirMenu btn btn-sm btn-primary" coord="<?php echo $objeto['lat'];?>,<?php echo $objeto['lng'];?>" style="color:#03204F; font-size: 11px;">
                                Ver mapa
                            </a>
                        </div>
                    </div>

                </div>

            </div>    

            <div class="push10"></div>
            <div class="mytextdiv">
                <div class="divider"></div>
            </div>
            <div class="push10"></div>
                
        <?php
            $count++;
        }       
        ?>

        </div> <!-- /container -->

        <div class="row">
            <div class="col-xs-6 col-xs-offset-3 text-center">
                <a href="javascript:void(0)" class="btn btn-info" id="loadMore"><span class="fal fa-plus"></span>&nbsp; Carregar mais</a>
            </div>
        </div>  
            
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

        <div class="row" id="menu-app">

            <div class="push20"></div>

            <div class="col-xs-6 text-center" style="display: table; align-items: center; height: 50px;">
                
                    <a id="btnWaze" class="btn btn-primary btn-sm btn-block" style="background-color: #4dace9; border-color: #4dace9; display: table-cell;">
                        <i class="fal fa-car"></i>
                        <div class="push5"></div>
                        Waze
                    </a>
                    <div class="push10"></div>
                
            </div>

            <div class="col-xs-6 text-center" style="display: table; align-items: center; height: 50px;">
                
                    <a id="btnMaps" class="btn btn-primary btn-sm btn-block" style="background-color: #4dace9; border-color: #4dace9; display: table-cell;">
                        <i class="fal fa-map"></i>
                        <div class="push5"></div>
                        Google Maps
                    </a>
                
            </div>

        </div>

        <form id="postos" method="POST">
            <input type="hidden" name="novoArrayEnderecos" value='<?=json_encode($novoArrayEnderecos)?>'>
        </form>

    <?php include 'footer.php'; ?>

<script type="text/javascript">

let inicio = Number("<?=$inicio?>");
let itens_por_pagina = Number("<?=$itens_por_pagina?>");

jQuery(document).ready(function( $ ) {

    $( ".abrirMapa" ).click(function() {
      $('#myModal').appendTo("body").modal('show');
    });                             

    $('#myModal').on('show.bs.modal', function () {
        
        $('.modal .modal-body').css('height', '100%');
        $('.modal .modal-body').css('max-height', window.innerHeight * 0.75);
    });

    $(".abrirMenu").click(function(){
        // $("#btnWaze").attr("href","https://waze.com/ul?ll="+$(this).attr("coord")+"&navigate=yes");
        $("#btnWaze").attr("href","https://www.waze.com/ul?ll="+$(this).attr("coord")+"&navigate=yes");
        $("#btnMaps").attr("href","https://maps.google.com?saddr=My+Location&daddr="+$(this).attr("coord"));
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

    $("#loadMore").click(function(){

        itens_por_pagina+=5;

        $.ajax({
            type: "POST",
            url: "ajxEnderecosDuque.do?id=<?=fnEncode($cod_empresa)?>&itens_por_pagina="+itens_por_pagina,
            data: $("#postos").serialize(),
            beforeSend:function(){
                $("#loadMore").html('Carregando...');
            },
            success:function(data){
                $("#relatorioConteudo").append(data);
                $("#loadMore").html('<span class="fal fa-plus"></span>&nbsp;Carregar mais');
            },
            error:function(data){
                // $("#mostraDetail_"+idCampanha).html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
            }
        });

    });

});
</script>
