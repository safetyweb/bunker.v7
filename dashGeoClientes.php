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

	$sql = "SELECT
		(SELECT COUNT(0) FROM clientes WHERE cod_empresa=$cod_empresa AND lat='') TOT_SEMGEO,
		(SELECT COUNT(0) FROM clientes WHERE cod_empresa=$cod_empresa) TOT_CLIENTE,
		(SELECT COUNT(0) FROM clientes WHERE cod_empresa=$cod_empresa AND DES_ENDEREC ='' AND lat='') TOT_ENDERECO
	";
	$arrayQuery = mysqli_query($conTemp,trim($sql));
	$qrCount = mysqli_fetch_assoc($arrayQuery);

	$sql = "SELECT cod_cliente FROM clientes WHERE cod_empresa=
	      	$cod_empresa AND lat!=''";
			$arrayQuery = mysqli_query($conTemp,trim($sql));
			//$nroClientes = mysqli_num_rows($arrayQuery);

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
	z-index: 1000;
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

@keyframes spinner-border {
  to {transform: rotate(360deg);}
}

.spinner-border {
    display: inline-block;
    width: 2rem;
    height: 2rem;
    vertical-align: text-bottom;
    border: .25em solid currentColor;
    border-right-color: transparent;
    border-radius: 50%;
    -webkit-animation: spinner-border .75s linear infinite;
    animation: spinner-border .75s linear infinite;
}
</style>


<link rel="stylesheet" href="js/plugins/leaflet.markercluster-master/dist/leaflet.css" integrity="sha512-07I2e+7D8p6he1SIM+1twR5TIrhUQn9+I6yjqD53JQjFiMf8EtC93ty0/5vJTZGF8aAocvHYNEDJajGdNx1IsQ==" crossorigin="" />
<script src="js/plugins/leaflet.markercluster-master/dist/leaflet-src.js" integrity="sha512-WXoSHqw/t26DszhdMhOXOkI7qCiv5QWXhH9R7CgvgZMHz1ImlkVQ3uNsiQKu5wwbbxtPzFXd1hK4tzno2VqhpA==" crossorigin=""></script>

<link rel="stylesheet" href="js/plugins/leaflet.markercluster-master/dist/MarkerCluster.css" />
<link rel="stylesheet" href="js/plugins/leaflet.markercluster-master/dist/MarkerCluster.Default.css" />
<script src="js/plugins/leaflet.markercluster-master/dist/leaflet.markercluster-src.js"></script>




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
										
										<b><br/><span id="nroClientes"></span></b></b><br/>
										<small style="font-weight:normal;">Clientes com Geo</small>
									</div>

									<div class="push20"></div>

									<div class="col-md-12 text-center text-info">
										<i class="fal fa-map fa-3x" aria-hidden="true"></i>
																					
										<b><br/><?php echo fnValor($qrCount['TOT_SEMGEO'],0)?></b><br/>
										<small style="font-weight:normal;">Clientes sem Geo</small>
									</div>

									<div class="push20"></div>
									
									<div class="col-md-12 text-center text-info">
										<i class="fal fa-flag fa-3x" aria-hidden="true"></i>
																					
										<b><br/><span id="nroUnidades"></span></b><br/>
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
																					
										<b><br/><?php echo fnValor($qrCount['TOT_CLIENTE'],0)?></b><br/>
										<small style="font-weight:normal;">Total de Clientes</small>
									</div>

									<div class="push20"></div>

									<div class="col-md-12 text-center text-info">
										<i class="fal fa-exclamation-triangle fa-3x" aria-hidden="true"></i>
																					
										<b><br/><?php echo fnValor(($qrCount['TOT_CLIENTE']-$qrCount['TOT_ENDERECO']),0)?></b><br/>
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
						
						
						<div id="alerta"></div>
						
						<div id="loadClientes" style="display:none;float:left;margin:5px;">
							<div class="spinner-border" role="status">
								<span class="sr-only"></span>
							</div> Carregando clientes...
						</div>
					
						<div id="loadUnidades" style="display:none;float:left;margin:5px;">
							<div class="spinner-border" role="status">
								<span class="sr-only"></span>
							</div> Carregando unidades...
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
	var centerLat = -23.533;
	var centerLng = -46.625;

	var zoom = 4;


	$(document).ready(function(e){
		initMap();

		var toggle = true;

		map.on("click", function(e){
			if(!toggle){
				$('.menuMapa').animate({
					left: '-400'
				});
				toggle = true;
			}
		});
		map.on("contextmenu", function(e){
			if(!toggle){
				$('.menuMapa').animate({
					left: '-400'
				});
				toggle = true;
			}else{
				addMarker(e);
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
		var tiles = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
				maxZoom: 18,
				attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Points &copy 2012 LINZ'
			}),
			latlng = L.latLng(centerLat, centerLng);

		map = L.map('map', {center: latlng, zoom: zoom, layers: [tiles]});

		addLocationClientes();
		addLocationUnidades();



		$("#resetZoom").click(function(e) {
			e.preventDefault();
			$(".leaflet-popup").hide();
			map.flyTo([centerLat, centerLng],zoom);
		});

	}

	function addMarker(e){
		var icon = L.icon({
			iconUrl: 'images/map_user3.png',
			iconSize:     [50, 50], // size of the icon
			iconAnchor:   [25, 50], // point of the icon which will correspond to marker's location
			popupAnchor:  [0, -50] // point from which the popup should open relative to the iconAnchor
		});
		markerNew = L.markerClusterGroup();
		var title = '<div id="content">'+
								'<div id="siteNotice"></div>'+
									'Novo marcador'+
								'</div>'+
							 '</div>';

		var marker = L.marker(e.latlng, {icon: icon});
		marker.bindPopup(title);
		markerNew.addLayer(marker);
		map.addLayer(markerNew);
	}

	function addLocationClientes(){

		$("#loadClientes").show();
		var icon = L.icon({
			iconUrl: 'images/map_user3.png',
			iconSize:     [50, 50], // size of the icon
			iconAnchor:   [25, 50], // point of the icon which will correspond to marker's location
			popupAnchor:  [0, -50] // point from which the popup should open relative to the iconAnchor
		});
		markerCli = L.markerClusterGroup();


		data="cod_empresa=<?=$cod_empresa?>&tipo=cli";
		$.ajax({
			type: "POST",
			data: data,
			url: "ajxGeoClientes.php",
			success: function(data){
				$("#nroClientes").html(data.length.toLocaleString('pt-BR'));
				
				$.each(data, function(k, mark){

					var title = '<div id="content">'+
											'<div id="siteNotice"></div>'+
												'<a href="action.do?mod=<?=fnEncode(1024);?>&id=<?=fnEncode($cod_empresa);?>&idC='+mark.cod_cliente_encode+'" target="_blank"><h1 id="firstHeading" class="firstHeading">'+mark.nom_cliente+'</h1></a>'+
												'<p>'+mark.idade+' Anos ('+mark.sexo+')</p>'+
												'<p>'+mark.unidade+'</p>'+
											'</div>'+
										 '</div>';

					var marker = L.marker(new L.LatLng(mark.lat,mark.lng), {icon: icon});
					marker.bindPopup(title);
					markerCli.addLayer(marker);

				});
				
				map.addLayer(markerCli);
				$("#loadClientes").hide();
			},
			error: function(XMLHttpRequest, textStatus, errorThrown){
				var alerta="<div class=\"alert alert-danger alert-dismissible top30 bottom30\" role=\"alert\">";
				alerta=alerta+"<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>";
				alerta=alerta+"Erro ao carregar clientes: "+errorThrown+"</strong>";
				alerta=alerta+"</div>";
				$("#alerta").html($("#alerta").html()+alerta);
				$("#loadClientes").hide();
			}
		});

	}

	function addLocationUnidades(){

		$("#loadUnidades").show();
		var icon = L.icon({
			iconUrl: 'images/map_store.png',
			iconSize:     [50, 50], // size of the icon
			iconAnchor:   [25, 50], // point of the icon which will correspond to marker's location
			popupAnchor:  [0, -50] // point from which the popup should open relative to the iconAnchor
		});
		markerUni = L.markerClusterGroup();


		data="cod_empresa=<?=$cod_empresa?>&tipo=uni";
		$.ajax({
			type: "POST",
			data: data,
			url: "ajxGeoClientes.php",
			success: function(data){
				$("#nroUnidades").html(data.length.toLocaleString('pt-BR'));

				$.each(data, function(k, mark){

					var title = '<div id="content">'+
											'<div id="siteNotice"></div>'+
												'<a href="javascrpt:"><h1 id="firstHeading" class="firstHeading">'+mark.NOM_FANTASI+'</h1></a>'+												
												'<p>'+mark.DES_ENDEREC+' '+mark.NUM_ENDEREC+'</p>'+
												'<p><small>'+mark.NOM_CIDADEC+', '+mark.COD_ESTADOF+'</small></p>'+
											'</div>'+
										 '</div>';

					var marker = L.marker(new L.LatLng(mark.lat,mark.lng), {icon: icon});
					marker.bindPopup(title);
					markerUni.addLayer(marker);

				});
				
				map.addLayer(markerUni);
				$("#loadUnidades").hide();
			},
			error: function(XMLHttpRequest, textStatus, errorThrown){
				var alerta="<div class=\"alert alert-danger alert-dismissible top30 bottom30\" role=\"alert\">";
				alerta=alerta+"<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>";
				alerta=alerta+"Erro ao carregar unidades: "+errorThrown+"</strong>";
				alerta=alerta+"</div>";
				$("#alerta").html($("#alerta").html()+alerta);
				$("#loadUnidades").hide();
			}
		});

	}



	function filterMarkersClientes(toggle){
		if($(toggle).is(':checked')){
			map.addLayer(markerCli);
		}else{
			markerCli.remove();
		}
	}

	function filterMarkersUnidades(toggle){
		if($(toggle).is(':checked')){
			map.addLayer(markerUni);
		}else{
			markerUni.remove();
		}
	}

</script>
<?php 
mysqli_close($conAdm);
mysqli_close($conTemp);
?>