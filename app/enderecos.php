<?php

    include 'header.php'; 
    @$tituloPagina = "Endereços";
    include "navegacao.php";

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
        
    <div class="container">

        <div class="push20"></div>

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
            
            iframe {
                width: 100%;
                height: 100%;
            }
            
            .modal-body {
                padding: 0;
            }           

        </style>

        <!-- Fire the plugin onDocumentReady -->
        <script type="text/javascript">
            jQuery(document).ready(function( $ ) {
                $("#menu").mmenu({
                    // options
                    extensions  : ["theme-white"]
                }, {
                    // configuration
                    offCanvas: {
                        pageSelector: ".container"
                    }
                });
            });
        </script>        

        
        <?php      
   
    $cod_regiao = fnLimpacampoZero(fnDecode($_GET['id']));

  //$sql = "select * from unidadevenda where cod_empresa  = '".$cod_empresa."' order by NOM_FANTASI ";
  if ($cod_regiao == 9999){
    $sql = "SELECT * FROM UNIDADEVENDA WHERE COD_EMPRESA = $cod_empresa AND LOG_ATIVOHS = 'S' ORDER BY NOM_FANTASI ";
  } else {
    $sql = "SELECT * FROM UNIDADEVENDA WHERE COD_EMPRESA = $cod_empresa AND COD_TIPOREG = $cod_regiao AND LOG_ATIVOHS = 'S' ORDER BY NOM_FANTASI ";  
  }
  //fnEscreve($sql);
  $arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
 
  $count=0;
  $novoArrayEnderecos = array();
 while ($qrListaUniVendas = mysqli_fetch_assoc($arrayQuery))
   {                
    //calcula distancia
     
     //print_r($qrListaUniVendas);
     //print_r($qrListaUniVendas);

    //fnEscreve('while');
     
     
     
    if(@$_COOKIE['RD_localAtual']!='')
    {
     
     //$update="UPDATE location SET LOG_ATIVO=2 WHERE  COD_CARTAO=$userid;";
     // mysqli_query(connTemp($cod_empresa, ''), $update);
       
      
  
     @$postcode1=urlencode($_COOKIE['RD_localAtual']);
     @$postcode2=$qrListaUniVendas['lat'].','.$qrListaUniVendas['lng'];
    
    $url = "http://maps.googleapis.com/maps/api/distancematrix/json?origins=$postcode1&destinations=$postcode2&mode=driving&language=pt-BR&sensor=false";
    $data = @file_get_contents($url);
    $result = json_decode($data, true);
    @$KM=str_replace("km"," ",$result['rows']['0']['elements'][0]['distance']['text']);
    $qrListaUniVendas['distancia'] = $KM;
    array_push($novoArrayEnderecos, $qrListaUniVendas);
    
    }else{
        
    $buscacord="select * from location WHERE COD_CARTAO='$userid';";
    $calccoord=mysqli_query(connTemp($cod_empresa, ''), $buscacord);
    $retucoord=mysqli_fetch_assoc($calccoord);
    
    
    $km=distancia($retucoord['LAT'],$retucoord['LNG'],$qrListaUniVendas['lat'],$qrListaUniVendas['lng']);
    $qrListaUniVendas['distancia'] = $km;
    
    array_push($novoArrayEnderecos, $qrListaUniVendas);
    
    
    }
    
   }
   
    $sort = array();
    foreach($novoArrayEnderecos as $k=>$v) {
    $sort['distancia'][$k] = $v['distancia'];
    }

    array_multisort($sort['NOM_FANTASI'], SORT_ASC, $novoArrayEnderecos);
   
    foreach ($novoArrayEnderecos as $objeto) {

   ?> 
        <div style="margin-top: 20px" class="text-center">
            <i class="fa-60 fal fa-map-marker-alt" aria-hidden="true" style="float: left; margin-right: 15px; margin-left: 15px;"></i>
            <div style="margin-left: 70px; font-size: 16px; text-transform: uppercase; text-align: left;">
                <div><?php echo $objeto['NOM_FANTASI']; ?></div>
                <div style="font-size: 18px;"><b><?php echo $objeto['DES_BAIRROC']; ?></b></div>
                <div style="margin-top: 10px; font-size: 13px;"><?php echo $objeto['DES_ENDEREC'].', '.$objeto['NUM_ENDEREC']; ?></div>
                <!-- <div style='margin-top:5px; font-size: 13px;' id='distancia'><h7>Você está: <?php echo $objeto['distancia'].' KM';?></h7></div> --> 
                <div class="push10"></div>  
            </div>

        </div>

        <div class="push5"></div>

        <div class="text-center">

            <div class="col-md-12 text-center">
                <a  src="" class="btn btn-primary btn-sm btn-block btn-sm abrirMapa" style=" background-color: #4dace9; border-color: #4dace9;"><i class="fal fa-map-marker-alt"></i>&nbsp; Mapa</a>
                <div class="push10"></div>
            </div>
            
            <div class="col-md-12 text-center">
                <a href="http://waze.to/?ll=<?php echo $objeto['lat'];?>,<?php echo $objeto['lng'];?>" class="btn btn-primary btn-sm btn-block" style="background-color: #4dace9; border-color: #4dace9;"><i class="fa fa-car"></i>&nbsp; Waze</a>
                <div class="push10"></div>
            </div>
        
            <div class="col-md-12 text-center">
                <a href="https://maps.google.com/maps?daddr=<?php echo $objeto['lat'];?>,<?php echo $objeto['lng'];?>&ll=" class="btn btn-primary btn-sm btn-block" style="background-color: #4dace9; border-color: #4dace9;"><i class="fa fa-map-o"></i>&nbsp; Google Maps</a>
            </div>
            
        </div>          

        <div class="push30"></div>
        <div class="mytextdiv">
            <div class="divider"></div>
        </div>
        <div class="push10"></div>
            
    <?php   
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

    <?php include 'footer.php'; ?>

<script type="text/javascript">
jQuery(document).ready(function( $ ) {
	$( ".abrirMapa" ).click(function() {
	  $('#myModal').appendTo("body").modal('show');
	});
                                                  

	$('#myModal').on('show.bs.modal', function () {
		
		$('.modal .modal-body').css('height', '100%');
		$('.modal .modal-body').css('max-height', window.innerHeight * 0.75);
	});
});
</script>
