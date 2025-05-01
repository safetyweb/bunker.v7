<?php include "_system/_functionsMain.php"; 

//echo fnDebug('true');

$cod_empresa = fnLimpacampo($_GET['codEmpresa']);

$sql="select * from campanha";

//fnEscreve($sql);
$arrayQuery = mysqli_query(connTemp($cod_empresa,""),$sql) or die(mysqli_error());

$inicioTag = '<div class="location_filter"><div class="location_filter_inner">';
$fimTag = '</a></div></div>';

while ($qrLista = mysqli_fetch_assoc($arrayQuery)){
	if(strtoupper($qrLista["DES_CAMPANHA"][0]) == 'A'){
		$listaA .= $inicioTag .'<a class="valorFiltro" codigo="'.$qrLista["COD_CAMPANHA"].'" href="#">' . $qrLista["DES_CAMPANHA"]. $fimTag;
	} else if (strtoupper($qrLista["DES_CAMPANHA"][0]) == 'B') {
		$listaB .= $inicioTag .'<a class="valorFiltro" codigo="'.$qrLista["COD_CAMPANHA"].'" href="#">' . $qrLista["DES_CAMPANHA"]. $fimTag;
	} else if (strtoupper($qrLista["DES_CAMPANHA"][0]) == 'C') {
		$listaC .= $inicioTag .'<a class="valorFiltro" codigo="'.$qrLista["COD_CAMPANHA"].'" href="#">' . $qrLista["DES_CAMPANHA"]. $fimTag;
	} else if (strtoupper($qrLista["DES_CAMPANHA"][0]) == 'D') {
		$listaD .= $inicioTag .'<a class="valorFiltro" codigo="'.$qrLista["COD_CAMPANHA"].'" href="#">' . $qrLista["DES_CAMPANHA"]. $fimTag;
	} else if (strtoupper($qrLista["DES_CAMPANHA"][0]) == 'E') {
		$listaE .= $inicioTag .'<a class="valorFiltro" codigo="'.$qrLista["COD_CAMPANHA"].'" href="#">' . $qrLista["DES_CAMPANHA"]. $fimTag;
	} else if (strtoupper($qrLista["DES_CAMPANHA"][0]) == 'F') {
		$listaF .= $inicioTag .'<a class="valorFiltro" codigo="'.$qrLista["COD_CAMPANHA"].'" href="#">' . $qrLista["DES_CAMPANHA"]. $fimTag;
	} else if (strtoupper($qrLista["DES_CAMPANHA"][0]) == 'G') {
		$listaG .= $inicioTag .'<a class="valorFiltro" codigo="'.$qrLista["COD_CAMPANHA"].'" href="#">' . $qrLista["DES_CAMPANHA"]. $fimTag;
	} else if (strtoupper($qrLista["DES_CAMPANHA"][0]) == 'H') {
		$listaH .= $inicioTag .'<a class="valorFiltro" codigo="'.$qrLista["COD_CAMPANHA"].'" href="#">' . $qrLista["DES_CAMPANHA"]. $fimTag;
	} else if (strtoupper($qrLista["DES_CAMPANHA"][0]) == 'I') {
		$listaI .= $inicioTag .'<a class="valorFiltro" codigo="'.$qrLista["COD_CAMPANHA"].'" href="#">' . $qrLista["DES_CAMPANHA"]. $fimTag;
	} else if (strtoupper($qrLista["DES_CAMPANHA"][0]) == 'J') {
		$listaJ .= $inicioTag .'<a class="valorFiltro" codigo="'.$qrLista["COD_CAMPANHA"].'" href="#">' . $qrLista["DES_CAMPANHA"]. $fimTag;
	} else if (strtoupper($qrLista["DES_CAMPANHA"][0]) == 'K') {
		$listaK .= $inicioTag .'<a class="valorFiltro" codigo="'.$qrLista["COD_CAMPANHA"].'" href="#">' . $qrLista["DES_CAMPANHA"]. $fimTag;
	} else if (strtoupper($qrLista["DES_CAMPANHA"][0]) == 'L') {
		$listaL .= $inicioTag .'<a class="valorFiltro" codigo="'.$qrLista["COD_CAMPANHA"].'" href="#">' . $qrLista["DES_CAMPANHA"]. $fimTag;
	} else if (strtoupper($qrLista["DES_CAMPANHA"][0]) == 'M') {
		$listaM .= $inicioTag .'<a class="valorFiltro" codigo="'.$qrLista["COD_CAMPANHA"].'" href="#">' . $qrLista["DES_CAMPANHA"]. $fimTag;
	} else if (strtoupper($qrLista["DES_CAMPANHA"][0]) == 'N') {
		$listaN .= $inicioTag .'<a class="valorFiltro" codigo="'.$qrLista["COD_CAMPANHA"].'" href="#">' . $qrLista["DES_CAMPANHA"]. $fimTag;
	} else if (strtoupper($qrLista["DES_CAMPANHA"][0]) == 'O') {
		$listaO .= $inicioTag .'<a class="valorFiltro" codigo="'.$qrLista["COD_CAMPANHA"].'" href="#">' . $qrLista["DES_CAMPANHA"]. $fimTag;
	} else if (strtoupper($qrLista["DES_CAMPANHA"][0]) == 'P') {
		$listaP .= $inicioTag .'<a class="valorFiltro" codigo="'.$qrLista["COD_CAMPANHA"].'" href="#">' . $qrLista["DES_CAMPANHA"]. $fimTag;
	} else if (strtoupper($qrLista["DES_CAMPANHA"][0]) == 'Q') {
		$listaQ .= $inicioTag .'<a class="valorFiltro" codigo="'.$qrLista["COD_CAMPANHA"].'" href="#">' . $qrLista["DES_CAMPANHA"]. $fimTag;
	} else if (strtoupper($qrLista["DES_CAMPANHA"][0]) == 'R') {
		$listaR .= $inicioTag .'<a class="valorFiltro" codigo="'.$qrLista["COD_CAMPANHA"].'" href="#">' . $qrLista["DES_CAMPANHA"]. $fimTag;
	} else if (strtoupper($qrLista["DES_CAMPANHA"][0]) == 'S') {
		$listaS .= $inicioTag .'<a class="valorFiltro" codigo="'.$qrLista["COD_CAMPANHA"].'" href="#">' . $qrLista["DES_CAMPANHA"]. $fimTag;
	} else if (strtoupper($qrLista["DES_CAMPANHA"][0]) == 'T') {
		$listaT .= $inicioTag .'<a class="valorFiltro" codigo="'.$qrLista["COD_CAMPANHA"].'" href="#">' . $qrLista["DES_CAMPANHA"]. $fimTag;
	} else if (strtoupper($qrLista["DES_CAMPANHA"][0]) == 'U') {
		$listaU .= $inicioTag .'<a class="valorFiltro" codigo="'.$qrLista["COD_CAMPANHA"].'" href="#">' . $qrLista["DES_CAMPANHA"]. $fimTag;
	} else if (strtoupper($qrLista["DES_CAMPANHA"][0]) == 'V') {
		$listaV .= $inicioTag .'<a class="valorFiltro" codigo="'.$qrLista["COD_CAMPANHA"].'" href="#">' . $qrLista["DES_CAMPANHA"]. $fimTag;
	} else if (strtoupper($qrLista["DES_CAMPANHA"][0]) == 'W') {
		$listaW .= $inicioTag .'<a class="valorFiltro" codigo="'.$qrLista["COD_CAMPANHA"].'" href="#">' . $qrLista["DES_CAMPANHA"]. $fimTag;
	} else if (strtoupper($qrLista["DES_CAMPANHA"][0]) == 'X') {
		$listaX .= $inicioTag .'<a class="valorFiltro" codigo="'.$qrLista["COD_CAMPANHA"].'" href="#">' . $qrLista["DES_CAMPANHA"]. $fimTag;
	} else if (strtoupper($qrLista["DES_CAMPANHA"][0]) == 'Y') {
		$listaY .= $inicioTag .'<a class="valorFiltro" codigo="'.$qrLista["COD_CAMPANHA"].'" href="#">' . $qrLista["DES_CAMPANHA"]. $fimTag;
	} else if (strtoupper($qrLista["DES_CAMPANHA"][0]) == 'Z') {
		$listaZ .= $inicioTag .'<a class="valorFiltro" codigo="'.$qrLista["COD_CAMPANHA"].'" href="#">' . $qrLista["DES_CAMPANHA"]. $fimTag;
	}
}

?>	

<script src="js/jquery.min.js"></script>
<link href="css/font-awesome.min.css" rel="stylesheet" />

<style>

.input-filter-container {
    width: 816px;
    text-align: left;
    padding-bottom: 16px;
    position: relative;
	margin: 0 auto;
}

.input-filter-container input[type=text] {
    width: 100%;
    padding: 0 21px;
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
    font-size: 20px;
    height: 52px;
    line-height: 52px;
    color: #666;
    -webkit-border-radius: 4px;
    border-radius: 4px;
    border: 1px solid #ddd;
    margin: auto;
    display: block;
}

.input-filter-container:after {
	font-family: FontAwesome;
    content: "\f002";
    color: #999;
    font-size: 16px;
    height: 16px;
    position: absolute;
    right: 20px;
    top: 17px;
    width: 16px;
}

.filtro {
	font-family: "Lato","Helvetica Neue",Helvetica,Arial,sans-serif;
}

.group .filter_group {
    float: left;
    text-transform: uppercase;
    font-weight: 700;
    font-size: 18px;
    padding-top: 33px;
    width: 6%;
}

.filters {
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
    min-width: 170px;
}

.group .filters {
    border-bottom: 1px #e6e6e6 solid;
    float: left;
    padding: 30px 0;
    padding-left: 4px;
    color: #333;
    width: 94%;
    -webkit-column-count: 3;
    -moz-column-count: 3;
    column-count: 3;
    font-size: 14px;
}

 .group .filters .location_filter {
    width: 220px;
    text-align: left;
}

.group .filters .location_filter .location_filter_inner {
    padding: 5px 15px 8px 0;
    display: inline-block;
}

 .location_filter_inner a {
	color: #666;
    text-decoration: none;
	max-width:151px;
}

.input-filter-container {
	display: none;
}
</style>

<div class="filtro">
	<div class="input-filter-container">
		<input type="text" id="input-buscar" onkeypress="buscarValores();" placeholder="Buscar...">
	</div>
	<div class="group" style="">
		<div class="filter_group">a</div> 
		<div class="filters">
			<?php echo $listaA; ?>
		</div>
	</div>
	<div class="group" style="">
		<div class="filter_group">b</div> 
		<div class="filters">
			<?php echo $listaB; ?>
		</div>
	</div>
	<div class="group" style="">
		<div class="filter_group">c</div> 
		<div class="filters">
			<?php echo $listaC; ?>
		</div>
	</div>
	<div class="group" style="">
		<div class="filter_group">d</div> 
		<div class="filters">
			<?php echo $listaD; ?>
		</div>
	</div>
	<div class="group" style="">
		<div class="filter_group">e</div> 
		<div class="filters">
			<?php echo $listaE; ?>
		</div>
	</div>
	<div class="group" style="">
		<div class="filter_group">f</div> 
		<div class="filters">
			<?php echo $listaF; ?>
		</div>
	</div>
	<div class="group" style="">
		<div class="filter_group">g</div> 
		<div class="filters">
			<?php echo $listaG; ?>
		</div>
	</div>
	<div class="group" style="">
		<div class="filter_group">h</div> 
		<div class="filters">
			<?php echo $listaH; ?>
		</div>
	</div>
	<div class="group" style="">
		<div class="filter_group">i</div> 
		<div class="filters">
			<?php echo $listaI; ?>
		</div>
	</div>	
	<div class="group" style="">
		<div class="filter_group">j</div> 
		<div class="filters">
			<?php echo $listaJ; ?>
		</div>
	</div>
	<div class="group" style="">
		<div class="filter_group">k</div> 
		<div class="filters">
			<?php echo $listaK; ?>
		</div>
	</div>
	<div class="group" style="">
		<div class="filter_group">l</div> 
		<div class="filters">
			<?php echo $listaL; ?>
		</div>
	</div>
	<div class="group" style="">
		<div class="filter_group">m</div> 
		<div class="filters">
			<?php echo $listaM; ?>
		</div>
	</div>
	<div class="group" style="">
		<div class="filter_group">N</div> 
		<div class="filters">
			<?php echo $listaN; ?>
		</div>
	</div>
	<div class="group" style="">
		<div class="filter_group">O</div> 
		<div class="filters">
			<?php echo $listaO; ?>
		</div>
	</div>
	<div class="group" style="">
		<div class="filter_group">P</div> 
		<div class="filters">
			<?php echo $listaP; ?>
		</div>
	</div>
	<div class="group" style="">
		<div class="filter_group">q</div> 
		<div class="filters">
			<?php echo $listaQ; ?>
		</div>
	</div>
	<div class="group" style="">
		<div class="filter_group">r</div> 
		<div class="filters">
			<?php echo $listaR; ?>
		</div>
	</div>
	<div class="group" style="">
		<div class="filter_group">s</div> 
		<div class="filters">
			<?php echo $listaS; ?>
		</div>
	</div>
	<div class="group" style="">
		<div class="filter_group">t</div> 
		<div class="filters">
			<?php echo $listaT; ?>
		</div>
	</div>
	<div class="group" style="">
		<div class="filter_group">u</div> 
		<div class="filters">
			<?php echo $listaU; ?>
		</div>
	</div>	
	<div class="group" style="">
		<div class="filter_group">v</div> 
		<div class="filters">
			<?php echo $listaV; ?>
		</div>
	</div>
	<div class="group" style="">
		<div class="filter_group">w</div> 
		<div class="filters">
			<?php echo $listaW; ?>
		</div>
	</div>
	<div class="group" style="">
		<div class="filter_group">x</div> 
		<div class="filters">
			<?php echo $listaX; ?>
		</div>
	</div>
	<div class="group" style="">
		<div class="filter_group">y</div> 
		<div class="filters">
			<?php echo $listaY; ?>
		</div>
	</div>
	<div class="group" style="">
		<div class="filter_group">z</div> 
		<div class="filters">
			<?php echo $listaZ; ?>
		</div>
	</div>	
</div>

<script type="text/javascript">

		$(document).ready(function(){
			$( ".valorFiltro" ).click(function() {
				parent.$('#nomeModal').text("campanha");
				parent.$('#codFiltro').text($(this).attr('codigo'));
				parent.$('#nomeFiltro').text($(this).text());
				
				parent.$('#popModal').modal('hide');
			});
		});

/*
	function buscarValores(){
		$('.valorFiltro').each(function( index ) {
			var parent = $(this).parent().parent().parent().parent();
			
			console.log('visivel: ' + parent.is(':visible'));
				if($(this).text().includes($('#input-buscar').val())){

				}else{
					parent.hide();
				}
			
		});
	}
	*/
</script>