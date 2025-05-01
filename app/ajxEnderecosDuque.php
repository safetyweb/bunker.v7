<?php

	include '_system/_functionsMain.php';

	$cod_empresa = fnDecode($_GET['id']);
	$itens_por_pagina = $_GET['itens_por_pagina'];
	$inicio = $itens_por_pagina - 5;
	$novoArrayEnderecos = json_decode($_POST['novoArrayEnderecos'],true);

	// echo "<pre>";
	// print_r($novoArrayEnderecos);
	// echo "</pre>";

	
    $count = 0;

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
                    // $("#btnWaze").attr("href","https://waze.com/ul?ll="+$(this).attr("coord")+"&navigate=yes");
                    $("#btnWaze").attr("href","https://www.waze.com/ul?ll="+$(this).attr("coord")+"&navigate=yes");
                    $("#btnMaps").attr("href","https://maps.google.com/maps?saddr=My+Location&daddr="+$(this).attr("coord"));
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
                
<?php
    	$count++;
	}       
?>

