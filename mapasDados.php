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

?>
<style>

body{
	height: 100%;
	overflow: hidden;
}

#map {
    height: calc(100vh - 156px);
    width: calc(100%);
}


@keyframes spinner-border {
  to {transform: rotate(360deg);}
}

#firstHeading {
	font-size: 16px;
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
	height: calc(100vh - 136px);
	margin-top: 39px;
	z-index: 1000;
	max-width: 350px;
	width: 350px;
	left: -400;
	background: rgba(255,255,255,0.7);
	box-shadow: 16px 0 16px -4px rgba(0,0,0,0.2);
	overflow: auto;
	overflow-x: hidden;
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

.load{
	margin:50px 0 0 60px;
	right:0;
	position:absolute;
	z-index:999;
	text-align:right;
}


.marker .markerIcon{
	position:absolute;
	top:8px;
	left:8px;
	color:#FFF;
}
.markerClientes{
	color:#54CBEC;
}
.markerUnidades{
	color:#F84462;
}
</style>

<div class="col-md12 margin-bottom-30 portlet portlet-bordered">
	<!-- Portlet -->
	<div class="col-md-1 portlet-title pull-left m-b-0">

		<div class="actions p-r-20">
			<a class="btn btn-red btn-circle btn-lg btnMenuMapa" href="#" onclick="return false">
				<span class="fal fa-bars fa-1-5x"></span>
			</a>
		</div>

	</div>

	<div class="col-md-11 portlet-title p-r-20 m-b-0">
		<div class="caption">

			<span class="text-primary"> <?php echo $NomePg." - ".$nom_mapa; ?></span>
		</div>
		<?php
		$formBack = "1549";
		include "atalhosPortlet.php";
		?>
	</div>
	<div class="portlet-body">
	
	
<link rel="stylesheet" href="js/plugins/leaflet.markercluster-master/dist/leaflet.css" integrity="sha512-07I2e+7D8p6he1SIM+1twR5TIrhUQn9+I6yjqD53JQjFiMf8EtC93ty0/5vJTZGF8aAocvHYNEDJajGdNx1IsQ==" crossorigin="" />
<script src="js/plugins/leaflet.markercluster-master/dist/leaflet-src.js" integrity="sha512-WXoSHqw/t26DszhdMhOXOkI7qCiv5QWXhH9R7CgvgZMHz1ImlkVQ3uNsiQKu5wwbbxtPzFXd1hK4tzno2VqhpA==" crossorigin=""></script>

<link rel="stylesheet" href="js/plugins/leaflet.markercluster-master/dist/MarkerCluster.css" />
<link rel="stylesheet" href="js/plugins/leaflet.markercluster-master/dist/MarkerCluster.Default.css" />
<script src="js/plugins/leaflet.markercluster-master/dist/leaflet.markercluster-src.js"></script>


<div>
	<div class="menuMapa">
		<div class="row">
			<div class="col-md-1"></div>
			<div class="col-md-12">
				<div class="push20"></div>

				<?php
				if ($log_unidades == "S" || $log_pessoas == "S"){
				?>
				<div class="col-md-2"></div>
				<?php
				}
				?>

				<div class="col-md-5 text-center" style="<?=($log_unidades <> "S"?"display:none":"")?>">
					<div class="form-check">
						<label class="control-label" for="LOG_UNIDADES"><i class="fas fa-flag"></i> Unidades</label>
						<div class="push5"></div>
						<span id="qtdUnidades">0</span>
						<div class="push5"></div>
						<label class="switch">
						<input type="checkbox" id="Clientes" class="switch" checked onchange="filterMarkersUnidades(this);">
						<span></span>
						</label>
					</div>
				</div>

				<div class="col-md-5 text-center" style="<?=($log_pessoas <> "S"?"display:none":"")?>">
					<div class="form-check">
						<label class="control-label" for="LOG_CLIENTES"><i class="fa fa-user"></i> Clientes</label>
						<div class="push5"></div>
						<span id="qtdClientes">0</span>
						<div class="push5"></div>
						<label class="switch">
						<input type="checkbox" id="LOG_CLIENTES" class="switch" checked onchange="filterMarkersClientes(this)">
						<span></span>
						</label>
					</div>
				</div>

				<?php
				$sql = "SELECT * FROM mapas_tipos
							WHERE COD_EMPRESA = $cod_empresa AND LOG_CONFIRM='S' AND COD_MAPA_TIPO IN (0".implode(",",$des_mapa_tipos).")
							ORDER BY NOM_MAPA_TIPO
						  ";
				$reg = 0;
				if ($log_unidades.$log_pessoas == "SN" || $log_unidades.$log_pessoas == "NS"){
					$reg = 1;
				}
				$result = mysqli_query(connTemp($cod_empresa,""),trim($sql)) or die(mysqli_error());
				$js_mapa = "";
				$sel_opt_tipos = "";
				while($qr = mysqli_fetch_assoc($result)){
					$sel_opt_tipos .= "<option value=".$qr['COD_MAPA_TIPO'].">".$qr['NOM_MAPA_TIPO']."</option> ";

					$js_mapa .= "mapa_tipo[".$qr["COD_MAPA_TIPO"]."] = '".$qr["NOM_MAPA_TIPO"]."';";
					$js_mapa .= "mapa_cor[".$qr["COD_MAPA_TIPO"]."] = '".$qr["DES_COR"]."';";
					$js_mapa .= "mapa_icone[".$qr["COD_MAPA_TIPO"]."] = '".$qr["DES_ICONE"]."';";
					$js_mapa .= "var ajxLocation".$qr["COD_MAPA_TIPO"]." = null;";
					if ($reg <= 0){
						echo "<div class=\"col-md-2\"></div>";
					}
					$reg++;
					?>
					<div class="col-md-5 text-center">   
						<div class="form-group">
							<label for="inputName" class="control-label"><i class="<?=$qr["DES_ICONE"]?>"></i> <?=$qr["NOM_MAPA_TIPO"]?></label> 
							<div class="push5"></div>
							<span id="qtdLoc<?=$qr["COD_MAPA_TIPO"]?>">0</span>
							<div class="push5"></div>
							<label class="switch">
							<input type="checkbox" id="LOG_MAPA_TIPO_<?=$qr["COD_MAPA_TIPO"]?>" class="switch" checked onchange="filterMarkers(this,<?=$qr["COD_MAPA_TIPO"]?>)">
							<span></span>
							</label>
						</div>
					</div>
				<?php
					if ($reg >= 2){
						$reg = 0;
					}
				}
				?>
			</div>

			<div class="push20"></div>

			<div class="col-md-1"></div>

			<div class="col-md-10">
				<div class="form-group">
					<label for="inputName" class="control-label">Cidades</label>
						<select multiple data-placeholder="Selecione uma cidade" name="FIL_CIDADE" id="FIL_CIDADE" class="chosen-select-deselect" required>
							<option value=""></option>
						</select>
					<div class="help-block with-errors"></div>
				</div>
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
</div>

<div id="loadMapa" class="load"></div>

<div id="map"></div>
			

				<!-- <div class="push20"></div> --> 

<script type="text/javascript">
	var tempNamespace = {};

	var map;
	var centerLat = -23.533;
	var centerLng = -46.625;
	var log_unidades = "<?=$log_unidades ?>";
	var log_pessoas = "<?=$log_pessoas ?>";
	var des_mapa_tipos = [<?=ltrim (implode(",",$des_mapa_tipos),"0")?>];
	var mapa_tipo = [];
	var mapa_cor = [];
	var mapa_icone = [];<?=$js_mapa?>
	//var markerLoc = [];
	var bounds = [];
	
	//ICONES
	var iconSize = [30, 36];
	var iconAnchor = [15, 40];
	var defIconColor = "#6BA862";
	var defIcon = "fas fa-map";

	var zoom = 4;
	
	var ajxClientes = null;
	var ajxUnidades = null;
	
	
	//console.log("SISTEMA: <?=$_SESSION["SYS_COD_SISTEMA"]?>");


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
		map.on('popupopen', function(e) {
			if ($(e.popup._source._popup._content).attr("tipo") == "mark"){
				var id = $(e.popup._source._popup._content).attr("id");
				$(".chosen-popup").chosen({allow_single_deselect:true});

				$("#"+id+" [name=COD_MAPA_TIPO]").val($("#"+id+" [name=COD_MAPA_TIPO]").attr("val")).trigger("chosen:updated");
				//alert($("#"+id+" [name=COD_MAPA_TIPO]").attr("val"));
			}
		});
		
		$("#FIL_CIDADE").change(function(){
			bounds = [];
			carregaTiposMapa();
		});

	});

	function initMap() {
		var tiles = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
				//maxZoom: 18,
				//attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Points &copy 2012 LINZ'
			}),
			latlng = L.latLng(centerLat, centerLng);

		map = L.map('map', {center: latlng, zoom: zoom, layers: [tiles]});

		carregaTiposMapa();

		$("#resetZoom").click(function(e) {
			e.preventDefault();
			$(".leaflet-popup").hide();
			if (bounds.length > 0){
				map.fitBounds(bounds);
			}else{
				map.flyTo([centerLat, centerLng],zoom);
			}
		});

	}

	function msg_loading(id,msg){
		if ($("#"+id).length){
			$("#"+id).show();
		}else{
			var html = "";
			html += "<div id=\""+id+"\" style=\"float:right;background:#FFF;margin:3px;padding:5px;\">";
			html += "<div class=\"spinner-border\" role=\"status\">";
			html += "<span class=\"sr-only\"></span>";
			html += "</div>&nbsp;";
			html += msg;
			html += "</div>";
			$("#loadMapa").append(html);
		}
	}
	function msg_alert(id,msg){
		if ($("#"+id).length){
			$("#"+id).show();
		}else{
			var html = "";
			html += "<div id=\""+id+"\" class=\"alert alert-danger alert-dismissible top30 bottom30\" role=\"alert\">";
			html += "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>";
			html += msg+"</strong>";
			html += "</div>";
			$("#loadMapa").append(html);
		}
	}

	function addMarker(e){
		var id = 'mk_0_' + (10000000 + Math.floor((99999999 - 10000000) * Math.random()));
		var icon = L.divIcon({
			html: '<i style="color:'+defIconColor+'" class="markerMarker fa fa-map-marker fa-3x"></i><i class="markerIcon '+defIcon+' text-white"></i>',
			iconSize: iconSize,
			iconAnchor: iconAnchor,
			className: 'marker '+id
		});
		var lat = (e.latlng.lat);
		var lng = (e.latlng.lng);

		markerNew = L.markerClusterGroup();
		popup = new L.Popup({offset: [0, -30]});
		popup.setContent(contentMarker(id,0,'',0,lat,lng));

		var marker = L.marker(e.latlng, {icon: icon,draggable:'true'});
		marker.bindPopup(popup);
        marker.on('dragend', function (e) {
			popup = new L.Popup({offset: [0, -30]});
			popup.setContent(contentMarker(id,0,'',0,e.target.getLatLng().lat,e.target.getLatLng().lng));
			marker.bindPopup(popup);
            //console.log(e.target.getLatLng());
			//marker.setLatLng(new L.LatLng(lat, lng)); 
        });
		markerNew.addLayer(marker);
		map.addLayer(markerNew);
	}

	function addLocationClientes(){

		if (ajxClientes) {
			ajxClientes.abort();
		}

		msg_loading("loadClientes","Carregando clientes...");
		var icon = L.divIcon({
			html: '<i class="markerMarker fa fa-map-marker fa-3x"></i><i class="markerIcon fa fa-user text-white"></i>',
			iconSize: iconSize,
			iconAnchor: iconAnchor,
			className: 'marker markerClientes'
		});
		try {
			markerCli.remove();
		}catch (e) {}
		markerCli = L.markerClusterGroup();

		var cidades="";
		$('#FIL_CIDADE > option:selected').each(function() {
			cidades += $(this).val();
		});
		data="cod_empresa=<?=$cod_empresa?>&tipo=cli&cidades="+cidades;
		console.log("ajxGeoMarkers.php?cod_empresa=<?=$cod_empresa?>&tipo=cli&cidades="+cidades)
		ajxClientes = $.ajax({
			type: "POST",
			data: data,
			url: "ajxGeoMarkers.php",
			success: function(data){
				$("#qtdClientes").html(data.length.toLocaleString('pt-BR'));

				$.each(data, function(k, mark){

					popup = new L.Popup({offset: [0, -30]});
					popup.setContent('<div>'+
										'<a href="action.do?mod=<?=($_SESSION["SYS_COD_SISTEMA"] == 16?fnEncode(1423):fnEncode(1024));?>&id=<?=fnEncode($cod_empresa);?>&idC='+mark.cod_cliente_encode+'" target="_blank" ><h1 class="firstHeading f14" style="margin:0 0 7px 0;padding:0">'+mark.nom_cliente+'</h1></a>'+
										'<p style="margin:0 0 3px 0;padding:0">'+mark.idade+' Anos ('+mark.sexo+')</p>'+
										'<p style="margin:0 0 3px 0;padding:0">'+mark.unidade+'</p>'+
										'<p style="margin:0 0 3px 0;padding:0">'+mark.des_enderec+' '+mark.num_enderec+'</p>'+
										'<p style="margin:0;padding:0"><small>'+mark.nom_cidadec+', '+mark.cod_estadof+'</small></p>'+
										'<p style="margin:0;padding:0"><small>'+mark.lat+','+mark.lng+'</small></p>'+
									 '</div>');

					var marker = L.marker(new L.LatLng(mark.lat,mark.lng), {icon: icon});
					bounds.push([mark.lat,mark.lng]);
					marker.bindPopup(popup);
					markerCli.addLayer(marker);
					addListaCidade(mark.nom_cidadec,mark.cod_estadof);

				});
				
				if($("#LOG_CLIENTES").is(':checked')){
					map.addLayer(markerCli);
				}
				$("#loadClientes").hide();
				$("#FIL_CIDADE").trigger("chosen:updated");
				if (bounds.length > 0){
					map.fitBounds(bounds);
				}
			},
			error: function(XMLHttpRequest, textStatus, errorThrown){
				if (errorThrown != "abort"){
					msg_alert("msgClientes","Erro ao carregar clientes: "+errorThrown);
				}
				$("#loadClientes").hide();
			}
		});

	}

	function addLocationUnidades(){

		if (ajxUnidades) {
			ajxUnidades.abort();
		}

		msg_loading("loadUnidades","Carregando unidades...");
		var icon = L.divIcon({
			html: '<i class="markerMarker fa fa-map-marker fa-3x"></i><i class="markerIcon fas fa-flag text-white"></i>',
			iconSize: iconSize,
			iconAnchor: iconAnchor,
			className: 'marker markerUnidades'
		});
		try {
			markerUni.remove();
		}catch (e) {}
		markerUni = L.markerClusterGroup();

		var cidades="";
		$('#FIL_CIDADE > option:selected').each(function() {
			cidades += $(this).val();
		});
		data="cod_empresa=<?=$cod_empresa?>&tipo=uni&cidades="+cidades;
		ajxUnidades = $.ajax({
			type: "POST",
			data: data,
			url: "ajxGeoMarkers.php",
			success: function(data){
				$("#qtdUnidades").html(data.length.toLocaleString('pt-BR'));

				$.each(data, function(k, mark){

					popup = new L.Popup({offset: [0, -30]});
					popup.setContent('<div>'+
										'<a href="javascrpt:"><h1 class="firstHeading f14" style="margin:0 0 7px 0;padding:0">'+mark.nom_fantasi+'</h1></a>'+												
										'<p style="margin:0 0 3px 0;padding:0">'+mark.des_enderec+' '+mark.num_enderec+'</p>'+
										'<p style="margin:0 0 3px 0;padding:0"><small>'+mark.nom_cidadec+', '+mark.cod_estadof+'</small></p>'+
									'</div>');

					var marker = L.marker(new L.LatLng(mark.lat,mark.lng), {icon: icon});
					bounds.push([mark.lat,mark.lng]);
					marker.bindPopup(popup);
					markerUni.addLayer(marker);
					addListaCidade(mark.nom_cidadec,mark.cod_estadof);

				});
				
				if($("#LOG_UNIDADES").is(':checked')){
					map.addLayer(markerUni);
				}
				$("#loadUnidades").hide();
				$("#FIL_CIDADE").trigger("chosen:updated");
				if (bounds.length > 0){
					map.fitBounds(bounds);
				}
			},
			error: function(XMLHttpRequest, textStatus, errorThrown){
				if (errorThrown != "abort"){
					msg_alert("msgUnidades","Erro ao carregar unidades: "+errorThrown);
				}
				$("#loadUnidades").hide();
			}
		});

	}

	function addLocation(cod,centraliza){
		var ajxLocation = "ajxLocation"+cod;
		var markerLocation = "markerLocation"+cod;
		
		if (centraliza == undefined){
			centraliza = true;
		}

		if (tempNamespace[ajxLocation]) {
			tempNamespace[ajxLocation].abort();
		}

		msg_loading("loadLocation"+cod,"Carregando "+mapa_tipo[cod]+"...");

		try {
			tempNamespace[markerLocation].remove();
		}catch (e) {}
		tempNamespace[markerLocation] = L.markerClusterGroup();

		var cidades="";
		$('#FIL_CIDADE > option:selected').each(function() {
			cidades += $(this).val();
		});
		data="cod_empresa=<?=$cod_empresa?>&tipo=tip&cod="+cod+"&cidades="+cidades;
		tempNamespace[ajxLocation] = $.ajax({
			type: "POST",
			data: data,
			url: "ajxGeoMarkers.php",
			success: function(data){
				$("#qtdLoc"+cod).html(data.length.toLocaleString('pt-BR'));

				$.each(data, function(k, mark){
					var icon = L.divIcon({
						html: '<i style="color:'+mapa_cor[cod]+';" class="markerMarker fa fa-map-marker fa-3x"></i><i class="markerIcon '+mapa_icone[cod]+' text-white"></i>',
						iconSize: iconSize,
						iconAnchor: iconAnchor,
						className: 'marker mk_'+mark.cod
					});

					popup = new L.Popup({offset: [0, -30]});
					popup.setContent(contentMarker('mk_'+mark.cod,mark.cod,mark.nom_nome,mark.tipo,mark.lat,mark.lng));
					/*popup.setContent('<div id="content">'+
											'<div>'+
												'<h1 class="firstHeading"><b>'+mark.nom_nome+'</b></h1>'+
												'<p>'+mark.des_enderec+' '+mark.num_enderec+'</p>'+
												'<p><small>'+mark.nom_cidadec+', '+mark.cod_estadof+'</small></p>'+
											'</div>'+
										 '</div>');*/

					var marker = L.marker(new L.LatLng(mark.lat,mark.lng), {icon: icon,draggable:'true'});
					bounds.push([mark.lat,mark.lng]);

					marker.bindPopup(popup);
					marker.on('dragend', function (e) {
						//console.log(e.target.getLatLng());
						//marker.setLatLng(new L.LatLng(lat, lng));
						$.confirm({
							title: 'Atenção!',
							animation: 'opacity',
							closeAnimation: 'opacity',
							content: 'Deseja mover este marcador?',
							buttons: {
								confirmar: function () {
									data="cod_empresa=<?=$cod_empresa?>&cod_mapa=<?=$cod_mapa?>&tipo=move_item&cod="+mark.cod+"&lat="+e.target.getLatLng().lat+"&lng="+e.target.getLatLng().lng;
									//console.log("ajxGeoMarkers.php?"+data);
									$.ajax({
										type: "POST",
										data: data,
										url: "ajxGeoMarkers.php",
										success: function(data){
											addLocation(mark.tipo,false);
										},
										error: function(XMLHttpRequest, textStatus, errorThrown){
											if (errorThrown != "abort"){
												msg_alert("msgDel"+cod,"Erro ao excluir: "+errorThrown);
											}
										}
									});
								},
								cancelar: function () {
									marker.setLatLng(new L.LatLng(mark.lat, mark.lng));
								},
							}
						});
					});
					tempNamespace[markerLocation].addLayer(marker);
					addListaCidade(mark.nom_cidadec,mark.cod_estadof);

				});
				
				if($("#LOG_MAPA_TIPO_"+cod).is(':checked')){
					map.addLayer(tempNamespace[markerLocation]);
				}
				$("#loadLocation"+cod).hide();
				$("#FIL_CIDADE").trigger("chosen:updated");
				if (centraliza){
					if (bounds.length > 0){
						map.fitBounds(bounds);
					}
				}
			},
			error: function(XMLHttpRequest, textStatus, errorThrown){
				if (errorThrown != "abort"){
					msg_alert("msgLocation"+cod,"Erro ao carregar "+mapa_tipo[cod]+": "+errorThrown);
				}
				$("#loadLocation"+cod).hide();
			}
		});

	}

	function contentMarker(marker,cod,nome,tipo,lat,lng){
		var html = "";
		var id = 'm_' + cod + '_' + (10000000 + Math.floor((99999999 - 10000000) * Math.random()));
		//console.log(id);
		html += '<div tipo="mark" id="'+id+'" lat="'+lat+'" lng="'+lng+'" style="width:200px;">'+
					
					'<div class="row">'+
						
						'<input type="hidden" name="COD_MAPA_TIPO_ITEM" id="COD_MAPA_TIPO_ITEM_'+id+'" value="'+cod+'">'+
						'<input type="hidden" name="LAT" id="LAT_'+id+'" value="'+lat+'">'+
						'<input type="hidden" name="LNG" id="LNG_'+id+'" value="'+lng+'">'+
						'<input type="hidden" name="MARKER" id="MARKER_'+id+'" value="'+marker+'">'+
						'<input type="hidden" name="COD_MAPA_TIPO_OLD" id="COD_MAPA_TIPO_OLD_'+id+'" value="'+tipo+'">'+

						'<div class="col-md-12">'+
							'<div class="form-group">'+
								'<label for="inputName" class="control-label">Nome</label>'+
								'<input type="text" class="form-control input-sm" name="NOM_NOME" name="NOM_NOME_'+id+'" maxlength="50" data-error="Campo obrigatório" value="'+nome+'">'+
								'<div class="help-block with-errors"></div>'+
							'</div>'+
						'</div>'+
					
					'</div>'+
					'<div class="row">'+

						'<div class="col-md-12">'+
							'<div class="form-group">'+
								'<label for="inputName" class="control-label required">Tipo</label>'+

								'<select val='+tipo+' data-placeholder="Selecione..." name="COD_MAPA_TIPO" id="COD_MAPA_TIPO_'+id+'" class="form-control input-sm chosen-popup" required>'+
									'<option value=""></option>'+
									<?php 
										echo "'$sel_opt_tipos'+";
									?>	
								'</select>'+
								'<div class="help-block with-errors"></div>'+
							'</div>'+
						'</div>'+

					'</div>'+

					'<input type="hidden" name="opcao" id="opcao" value="">'+
					'<input type="hidden" name="hashForm" id="hashForm" value="" />'+
					'<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">'+

					'<div style="display:none" class="alert alert-danger alert-dismissible top30 bottom30" role="alert">'+
					'<span class="msg"></span>'+
					'</div>'+

					'<div class="row">'+
						'<center>'+
						'<button style="margin-right:5px;"  onClick="apagaMarker(\''+id+'\',\''+cod+'\',\''+marker+'\');" type="submit" name="EXC" id="EXC" class="btn btn-xs btn-danger getBtn"><i class="fa fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>'+
						'<button onClick="gravaMarker(\''+id+'\',\''+cod+'\');" type="submit" name="ALT" id="ALT" class="btn btn-xs btn-success getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Salvar</button>'+
						'</center>'+
					'</div>'+
				
				'</div>';

		return html;
	}

	function addListaCidade(cidade,estado){
		val = cidade+":"+estado+"|";
		if (!$('#FIL_CIDADE option[value="'+val+'"]').length){
			$("#FIL_CIDADE").append('<option value="'+val+'">' + cidade+'-'+estado + '</option>');
		}
//		$("#FIL_CIDADE").trigger("chosen:updated");
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

	function filterMarkers(toggle,cod){
		if($(toggle).is(':checked')){
			map.addLayer(tempNamespace["markerLocation"+cod]);
		}else{
			tempNamespace["markerLocation"+cod].remove();
		}
	}
	
	function carregaTiposMapa(){
		if (log_pessoas == "S"){
			addLocationClientes();
		}
		if (log_unidades == "S"){
			addLocationUnidades();
		}
		$.each(des_mapa_tipos, function( index, value ) {
			if (value > 0){
				addLocation(value);
			}
		});
	}
	
	function gravaMarker(id,cod){
		$("#"+id+" .alert").hide();

		nome = $("#"+id+" [name=NOM_NOME]").val();
		tipo = $("#"+id+" [name=COD_MAPA_TIPO]").val();
		tipo_old = $("#"+id+" [name=COD_MAPA_TIPO_OLD]").val();
		lat = $("#"+id+" [name=LAT]").val();
		lng = $("#"+id+" [name=LNG]").val();
		marker = $("#"+id+" [name=MARKER]").val();
		select = "COD_MAPA_TIPO_"+id;

		if (tipo == null || tipo == ""){
			$("#"+id+" .alert .msg").html("Escolha o tipo!");
			$("#"+id+" .alert").show();
			return false;
		}

		data="cod_empresa=<?=$cod_empresa?>&cod_mapa=<?=$cod_mapa?>&tipo=grava_item&cod="+cod+"&nome="+nome+"&tipo_item="+tipo+"&lat="+lat+"&lng="+lng;
		//console.log("ajxGeoMarkers.php?"+data);
		$("#"+id).parent().parent().parent().hide();
		mudaMarcador(marker,select);

		$.ajax({
			type: "POST",
			data: data,
			url: "ajxGeoMarkers.php",
			success: function(data){
				$("."+marker).hide();
				addLocation(tipo,false);
				if (tipo_old != tipo){
					addLocation(tipo_old,false);
				}
			},
			error: function(XMLHttpRequest, textStatus, errorThrown){
				if (errorThrown != "abort"){
					msg_alert("msgGrava"+cod,"Erro ao gravar: "+errorThrown);
				}
			}
		});
	}
	function apagaMarker(id,cod,marker){

		tipo = $("#"+id+" [name=COD_MAPA_TIPO]").val();

		$.confirm({
			title: 'Atenção!',
			animation: 'opacity',
			closeAnimation: 'opacity',
			content: 'Deseja realmente excluir esse marcador?',
			buttons: {
				confirmar: function () {
					$("."+marker).hide();
					$("#"+id).parent().parent().parent().hide();
					if (cod > 0){
						data="cod_empresa=<?=$cod_empresa?>&cod_mapa=<?=$cod_mapa?>&tipo=apaga_item&cod="+cod;
						//console.log("ajxGeoMarkers.php?"+data);
						$("."+marker).hide();
						$("#"+id).parent().parent().parent().hide();
						$.ajax({
							type: "POST",
							data: data,
							url: "ajxGeoMarkers.php",
							success: function(data){
								addLocation(tipo,false);
							},
							error: function(XMLHttpRequest, textStatus, errorThrown){
								if (errorThrown != "abort"){
									msg_alert("msgDel"+cod,"Erro ao excluir: "+errorThrown);
								}
							}
						});
					}
				},
				cancelar: function () {
					
				},
			}
		});

	}
	
	function mudaMarcador(marker,select){
		tipo = $("#"+select).val();
		cor = (mapa_cor[tipo] == undefined?defIconColor:mapa_cor[tipo]);
		icon = (mapa_icone[tipo] == undefined?defIcon:mapa_icone[tipo]);

		$("."+marker+" .markerMarker").css("color",cor);
		$("."+marker+" .markerIcon").attr("class","markerIcon text-white "+icon);
	}
</script>

</div>

<div class="push20"></div> 
</div>