<?php

	
	//echo "<h5>_".$opcao."</h5>";
	//fnDebug('true');

	$hashLocal = mt_rand();	
	
	if( $_SERVER['REQUEST_METHOD']=='POST' )
	{
		$request = md5( implode( $_POST ) );
		
		if( isset( $_SESSION['last_request'] ) && $_SESSION['last_request']== $request )
		{
			$msgRetorno = 'Essa página já foi utilizada';
			$msgTipo = 'alert-warning';
		}
		else
		{
			$_SESSION['last_request']  = $request;



			// $cod_empresa = fnLimpaCampo($_REQUEST['COD_EMPRESA']);

			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
						
			if ($opcao != ''){

				$sql = "CALL SP_ALTERA_GRUPOTRABALHO (
				 '".$cod_grupotr."', 
				 '".$des_grupotr."', 
				 '".$cod_empresa."', 
				 '".$opcao."'    
				) ";
				
				//echo $sql;
				
				mysqli_query($conAdm,trim($sql)) or die(mysqli_error());				
				
				//mensagem de retorno
				switch ($opcao)
				{
					case 'CAD':
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";	
						break;
					case 'ALT':
						$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";		
						break;
					case 'EXC':
						$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";		
						break;
					break;
				}			
				$msgTipo = 'alert-success';
				
			}  	

		}
	}
      
	
	//busca dados da url	
	if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))){
		//busca dados da empresa
		$cod_empresa = fnDecode($_GET['id']);	
		$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '".$cod_empresa."' ";
		//fnEscreve($sql);
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
		
		if (isset($arrayQuery)){
			$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
			$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
		}
												
	}else {
		$cod_empresa = 0;		
		//fnEscreve('entrou else');
	}
	
			$conAdm = $connAdm->connAdm();
			$conTemp = connTemp($cod_empresa,"");

	$sql = "SELECT CASE WHEN lat='' THEN (SELECT COUNT(*) FROM clientes WHERE lat='') ELSE 0 END TOT_SEMGEO, CASE WHEN cod_cliente >0 THEN COUNT(cod_cliente) ELSE '0' END TOT_CLIENTE, CASE WHEN DES_ENDEREC = '' THEN 0 ELSE (SELECT COUNT(*) FROM clientes WHERE DES_ENDEREC ='' AND lat='') END TOT_ENDERECO FROM clientes WHERE cod_empresa=$cod_empresa";
	$arrayQuery = mysqli_query($conTemp,trim($sql));
	$qrCount = mysqli_fetch_assoc($arrayQuery);

	$sql = "SELECT cod_cliente FROM clientes WHERE cod_empresa=
	      	$cod_empresa AND lat!=''";
			$arrayQuery = mysqli_query($conTemp,trim($sql));
			$nroClientes = mysqli_num_rows($arrayQuery);

?>
<style>

body{
	height: 100%;
	overflow: hidden;
}
	
.container, .container > div, .container > div #map {
    height: 75vh;
    width: 100%;
}

.container, .p-0{
	padding: 0;
}

.portlet{
	padding-left: 0;
	padding-right: 0;
	padding-bottom: 0;
	position: relative;
	overflow: hidden;
}

.no-underline,
.no-underline:hover,
.no-underline:active,
.no-underline:visited,
.no-underline:link{
	color: #3c3c3c;
	text-decoration: none;
}

.p-r-20,.p-t-20{
	padding-right: 40px;
}

.m-b-0{
	margin-bottom: 0; 
}

.margin-fix{
	margin-left: -15px;
	margin-right: -15px;
}

.menuMapa{
	position: absolute;
	height: 100vh;
	z-index: 10;
	max-width: 350px;
	width: 350px;
	left: -400;
	background: rgba(255,255,255,0.7);
	box-shadow: 16px 0 16px -4px rgba(0,0,0,0.2);

}

.btn-circle{
	width: 40px !important;
	height: 40px !important;
	margin-top: -10px;
	padding: 15px;
}

.fa-1-5x{
	font-size: 14px;
	margin-top: 6px;
}

#firstHeading {
	font-size: 16px;
}

#bodyContent a {
	color: red;
	font-size: 20px;
	text-decoration: none;
	font-weight: bold;
}

#bodyContent p {
	font-size: 12px;
	max-width: 400px;
}


</style>


					<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA_EZf1cygBPrz_21e-EeAveXWtrqugNnQ&sensor=false&libraries=drawing"></script>
					<script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js"></script>
			
					<div class="push10"></div>
					
					<div class="row">				
					
						<div class="col-md-12 margin-bottom-30">

							<div class="portlet portlet-bordered">
								<div class="row m-b-0">

									<div class="col-md-1 portlet-title pull-left m-b-0">

										<div class="actions p-r-20">
											<a class="btn btn-red btn-circle btn-lg btnMenuMapa" href="#" onclick="return false">
												<span class="fal fa-bars fa-1-5x"></span>
											</a>
										</div>

									</div>

									<div class="col-md-11 portlet-title p-r-20 m-b-0">

										<div class="caption">
											<span class="text-primary"><?php echo $NomePg; ?></span>
										</div>
										
										<?php 
										$formBack = "1019";
										include "atalhosPortlet.php"; 
										?>	

									</div>
							
								</div>
									
									<div class="container">

										

									    <div>
									    	<div class="menuMapa">
									    		<div class="row">
									    			<div class="col-md-1"></div>
									    			<div class="col-md-5">
									    				<div class="push20"></div>

									    				<div class="col-md-12 text-center">
										    				<div class="form-check">
										    					<label class="control-label" for="LOG_UNIDADES">Unidades</label>
																<div class="push5"></div>
																<label class="switch">
																<input type="checkbox" id="Clientes" class="switch" checked onchange="filterMarkersUnidades(this);">
																<span></span>
																</label>
															</div>
														</div>

														<div class="push20"></div>

									    				<div class="col-md-12 text-center text-info">
															<i class="fal fa-map-marked-alt fa-3x" aria-hidden="true"></i>
															
															<b><br/><?php echo fnValor($nroClientes,2)?></b></b><br/>
															<small style="font-weight:normal;">Clientes com Geo</small>
														</div>

														<div class="push20"></div>

														<div class="col-md-12 text-center text-info">
															<i class="fal fa-map fa-3x" aria-hidden="true"></i>
																										
															<b><br/><?php echo fnValor($qrCount['TOT_SEMGEO'],2)?></b><br/>
															<small style="font-weight:normal;">Clientes sem Geo</small>
														</div>

														<div class="push20"></div>
														
														<div class="col-md-12 text-center text-info">
															<i class="fal fa-flag fa-3x" aria-hidden="true"></i>
																										
															<b><br/><span id="NRO_UNIVEND"></span></b><br/>
															<small style="font-weight:normal;">Unidades</small>
														</div>
									    				

									    			</div>

									    			<div class="col-md-5">
									    				<div class="push20"></div>

									    				<div class="col-md-12 text-center">
										    				<div class="form-check">
										    					<label class="control-label" for="LOG_CLIENTES">Clientes</label>
																<div class="push5"></div>
																<label class="switch">
																<input type="checkbox" id="LOG_CLIENTES" class="switch" checked onchange="filterMarkersClientes(this)">
																<span></span>
																</label>
															</div>
														</div>

														<div class="push20"></div>

														<div class="col-md-12 text-center text-info">
															<i class="fal fa-users fa-3x" aria-hidden="true"></i>
																										
															<b><br/><?php echo fnValor($qrCount['TOT_CLIENTE'],2)?></b><br/>
															<small style="font-weight:normal;">Total de Clientes</small>
														</div>

														<div class="push20"></div>

														<div class="col-md-12 text-center text-info">
															<i class="fal fa-exclamation-triangle fa-3x" aria-hidden="true"></i>
																										
															<b><br/><?php echo fnValor(($qrCount['TOT_CLIENTE']-$qrCount['TOT_ENDERECO']),2)?></b><br/>
															<small style="font-weight:normal;">Total de Clientes sem Endereço</small>
														</div>

														<div class="push20"></div>
														

														<div class="push20"></div>
									    				

									    			</div>
									    		</div>
									    		<div class="push20"></div>
									    		<div class="row">
									    			<div class="col-md-1"></div>
									    			<div class="col-md-10">
									    				<a href="#" class="btn btn-primary form-control" id="resetZoom">Redefinir Zoom</a>
									    			</div>
									    		</div>
									    	</div>
									    	<div id="map"></div>
									    </div>

									</div>

								</div>															
								
						</div>
						
					</div>					
						
					<!-- <div class="push20"></div> --> 
	
	<script type="text/javascript">

		var map;
		var center = {lat: -23.533, lng: -46.625};
		var drawingManager;
      	var lastShape;
      	var markersClientes = [];
		var markersUnidades = [];
		var clientes = [];
		var unidades = [];
		var markerCluster;
		var InfoWindows = new google.maps.InfoWindow({});
		

		$(document).ready(function(){
			addLocationClientes();
			addLocationUnidades();
			initMap();
			
			//carregarPontos();
			var toggle = true;

			$('#map').click(function(){
				if(!toggle){
					$('.menuMapa').animate({
						left: '-400'
					});
					toggle = true;
				}
			});

			$('.btnMenuMapa').click(function(){
				if(toggle){
					$('.menuMapa').animate({
						left: '0'
					});
					toggle = false;
				}else{
					$('.menuMapa').animate({
						left: '-400'
					});
					toggle = true;
				}
			});

		});

		function initMap() {

        map = new google.maps.Map(document.getElementById('map'), {
          zoom: 4,
          center: center,
          mapTypeControl: false,
          gestureHandling: 'greedy',
          styles: [{"featureType": "administrative", "elementType": "geometry", "stylers": [{"visibility": "off"} ] }, {"featureType": "poi", "stylers": [{"visibility": "off"} ] }, {"featureType": "road", "elementType": "labels.icon", "stylers": [{"visibility": "off"} ] }, {"featureType": "transit", "stylers": [{"visibility": "off"} ] } ] 
      });
        
        var shapeOptions = {
          strokeWeight: 1,
          strokeOpacity: 1,
          fillOpacity: 0.2,
          editable: false,
          clickable: false,
          strokeColor: '#3399FF',
          fillColor: '#3399FF'
        };

       //--------------------------Criando Marcadores de clientes--------------------------------
       
       var iconeCliente = 'images/map_user3.png';
   							
	
		clientes.forEach(function(cliente) {	
			var marker = new google.maps.Marker({
			  position: { lat: cliente.position.lat, lng: cliente.position.lng },
			  category: cliente.category,
			  icon: iconeCliente,
			  title: cliente.title,
			  id: cliente.id
			});
			markersClientes.push(marker);
			marker.addListener('click', function() {
			  InfoWindows.open(map, this);
			  InfoWindows.setContent(cliente.content);
			  map.panTo(this.getPosition());
              //map.setZoom(10);
			});
		});
		//---------------------------------------------------------------------------------------

		//--------------------------Criando Marcadores de unidades--------------------------------
       
       var iconeUnidade = 'images/map_store.png';
   							
	
		unidades.forEach(function(unidade) {	
			var marker = new google.maps.Marker({
			  position: { lat: unidade.position.lat, lng: unidade.position.lng },
			  category: unidade.category,
			  icon: iconeUnidade,
			  title: unidade.title,
			  map: map
			});
			markersUnidades.push(marker);
			marker.addListener('click', function() {
			  InfoWindows.open(map, this);
			  InfoWindows.setContent(unidade.content);
			  map.panTo(this.getPosition());
              //map.setZoom(15);
			});
		});
		//---------------------------------------------------------------------------------------

		// create a drawing manager attached to the map to allow the user to draw
        // markers, lines, and shapes.
        drawingManager = new google.maps.drawing.DrawingManager({
          drawingMode: null,
          drawingControlOptions: {drawingModes: [google.maps.drawing.OverlayType.POLYGON, google.maps.drawing.OverlayType.RECTANGLE]},
          rectangleOptions: shapeOptions,
          polygonOptions: shapeOptions,
          map: map
        });

        google.maps.event.addListener(drawingManager, 'overlaycomplete', function(e) {
            if (lastShape != undefined) {
                    lastShape.setMap(null);
            }
            
            // cancel drawing mode
            if (shift_draw == false) { drawingManager.setDrawingMode(null); }
            
            lastShape = e.overlay;
            lastShape.type = e.type;
            
            
            if (lastShape.type == google.maps.drawing.OverlayType.RECTANGLE) {

            	//var inSelection = [];
                
                lastBounds = lastShape.getBounds();
                
                console.log(lastBounds.toString());

                for (var i = 0; i < markersClientes.length; i++) {

			      // TypeError: e is undefined
			      if (lastBounds.contains(markersClientes[i].getPosition())) {

			        //inSelection.push(markersClientes[i]);
			        console.log(markersClientes[i].id);

			      } else {

			        console.log('----------------------');

			      }

			    }
            //console.log(inSelection);
            
            } else if (lastShape.type == google.maps.drawing.OverlayType.POLYGON) {
                
                console.log('N/A');


            	for (var i = 0; i < markersClientes.length; i++) {
	                // determine if marker is inside the polygon:
	                // (refer to: https://developers.google.com/maps/documentation/javascript/reference#poly)
	                if (google.maps.geometry.poly.containsLocation(markersClientes[i].getPosition(), lastShape)) {
	                     console.log(markersClientes[i].id);
	                } else {
	                     console.log('----------------------');
	                }
	            }
            
            }
            
        });

            var shift_draw = false;
            
            $(document).bind('keydown', function(e) {
                if(e.keyCode==16 && shift_draw == false){
                    map.setOptions({draggable: false, disableDoubleClickZoom: true});
                    shift_draw = true; // enable drawing
                    drawingManager.setDrawingMode(google.maps.drawing.OverlayType.RECTANGLE);
                }
                 
            });
            
            $(document).bind('keyup', function(e) {
                if(e.keyCode==16){
                    map.setOptions({draggable: true, disableDoubleClickZoom: true});
                    shift_draw = false // disable drawing
                    drawingManager.setDrawingMode(null);
                }
                 
            });
            
            google.maps.event.addListener(map, 'mousedown', function () {
                if (lastShape != undefined) {
                        lastShape.setMap(null);
                        console.log('...');
                        console.log('...');
                }
            });
            
            google.maps.event.addListener(map, 'drag', function () {
                if (lastShape != undefined) {
                        lastShape.setMap(null);
                        console.log('...');
                        console.log('...');
                }
            });

        markerCluster = new MarkerClusterer(map, markersClientes,
  		{imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'});

      }

      $("#resetZoom").click(function(e) {
      		e.preventDefault();
	    	InfoWindows.close();
	    	map.panTo(center);
            map.setZoom(4);
	   });

        
        

        function addLocationClientes(){
	      <?php
	      	$ARRAY_UNIDADE1=array(
				   'sql'=>"select COD_UNIVEND,cod_empresa,nom_fantasi,NOM_UNIVEND from unidadevenda where cod_empresa=$cod_empresa  and cod_exclusa is null",
				   'cod_empresa'=>$cod_empresa,
				   'conntadm'=>$connAdm->connAdm(),
				   'IN'=>'N',
				   'nomecampo'=>'',
				   'conntemp'=>'',
				   'SQLIN'=> ""   
				   );
			$ARRAY_UNIDADE=fnUnivend($ARRAY_UNIDADE1);

	      	$sql = "SELECT cod_cliente,nom_cliente,cod_sexopes,idade,cod_univend,lat,lng FROM clientes WHERE cod_empresa=
	      	$cod_empresa AND lat!=''";
			$arrayQuery = mysqli_query($conTemp,trim($sql));
			$nroClientes = mysqli_num_rows($arrayQuery);
			while($qrGeo = mysqli_fetch_assoc($arrayQuery)){

			switch($qrGeo["cod_sexopes"]){
				case 1:
					$sexo = "Masculino";
				break;
				case 2:
					$sexo = "Feminino";
				break;
				default:
					$sexo = "Indefinido";
				break;
			}	
			$NOM_ARRAY_UNIDADE=(array_search($qrGeo['cod_univend'], array_column($ARRAY_UNIDADE, 'COD_UNIVEND')));
	      ?>
			coordenadas = { 
							title: 'Cliente',
							category: 'cliente',
							id: <?php echo $qrGeo['cod_cliente']; ?>,
							position: { 
								lat: <?php echo $qrGeo['lat']; ?>, 
								lng: <?php echo $qrGeo['lng']; ?> }, 
								content: '<div id="content">'+
											'<div id="siteNotice"></div>'+
												'<a href="action.do?mod=<?php echo fnEncode(1024); ?>&id=<?php echo fnEncode($cod_empresa); ?>&idC=<?php echo fnEncode($qrGeo['cod_cliente']); ?>" target="_blank"><h1 id="firstHeading" class="firstHeading"><?php echo $qrGeo["nom_cliente"] ?></h1></a>'+
												'<p><?php echo $qrGeo["idade"] ?> Anos (<?php echo $sexo ?>)</p>'+
												'<p><?php echo $ARRAY_UNIDADE[$NOM_ARRAY_UNIDADE]['nom_fantasi'] ?></p>'+
											'</div>'+
										 '</div>'
						  },

	      	clientes.push(coordenadas);
	      
		  <?php } ?>
		}

		function addLocationUnidades(){
	      <?php
	      $countUnidades = 0;

	      	$sql = "SELECT * FROM unidadevenda WHERE cod_empresa=
	      	$cod_empresa AND lat!=''";
			$arrayQueryUni =  mysqli_query($conAdm,$sql);
			$nroUnidades = mysqli_num_rows($arrayQueryUni);
			while($qrGeoUni = mysqli_fetch_assoc($arrayQueryUni)){
	      ?>
			coordenadas = { 
							title: '<?php echo $qrGeoUni["NOM_FANTASI"] ?>',
							category: 'unidade',
							position: { 
							lat: <?php echo $qrGeoUni['lat']; ?>, 
							lng: <?php echo $qrGeoUni['lng']; ?> }, 
							content: '<div id="content">'+
										'<div id="siteNotice"></div>'+
											'<a href="#" target="_blank"><h1 id="firstHeading" class="firstHeading"><?php echo $qrGeoUni["NOM_FANTASI"] ?></h1></a>'+
											'<p><?php echo $qrGeoUni["DES_ENDEREC"] ?>, <?php echo $qrGeoUni["NUM_ENDEREC"] ?></p>'+
											'<p><small><?php echo $qrGeoUni["NOM_CIDADEC"] ?>, <?php echo $qrGeoUni["COD_ESTADOF"] ?></small></p>'+
										'</div>'+
									 '</div>'
						  },

	      	unidades.push(coordenadas);
	      
		  <?php $countUnidades++; } ?>
		  $('#NRO_UNIVEND').text(<?php echo $nroUnidades ?>);

		}

		function filterMarkersClientes(toggle){
	  		var newmarkers = [];
		  	for (var i = markersClientes.length - 1; i >= 0; i--) {
		  		if($(toggle).is(':checked')){
		  			markersClientes[i].setVisible(true);
      				newmarkers.push(markersClientes[i]);
		  		}else{
		  			markersClientes[i].setVisible(false);
		  		}
		  	}
		  	markerCluster.clearMarkers();
  			markerCluster.addMarkers(newmarkers);
	  	}

	  	function filterMarkersUnidades(toggle){
		  	for (var i = markersUnidades.length - 1; i >= 0; i--) {
		  		if($(toggle).is(':checked')){
		  			markersUnidades[i].setVisible(true);
		  		}else{
		  			markersUnidades[i].setVisible(false);
		  		}
		  	}
	  	}

		// function filterMarkersClientes(categoria){
	 //  		var newmarkers = [];
		//   	for (var i = markersClientes.length - 1; i >= 0; i--) {
		//   		if(markersClientes[i].category == categoria || categoria.length === 0) {
		//   			markersClientes[i].setVisible(true);
  //     				newmarkers.push(markersClientes[i]);
		//   		}else{
		//   			markersClientes[i].setVisible(false);
		//   		}
		//   	}
		//   	markerCluster.clearMarkers();
  // 			markerCluster.addMarkers(newmarkers);
	 //  	}

		
	</script>
	<?php 
	mysqli_close($conAdm);
	mysqli_close($conTemp);
	?>