<?php include "_system/_functionsMain.php"; 

//echo fnDebug('true');

$cod_empresa = fnLimpacampo($_GET['codEmpresa']);

$sql="select * from fornecedormrka";

//fnEscreve($sql);
$arrayQuery = mysqli_query(connTemp($cod_empresa,""),$sql) or die(mysqli_error());

$inicioTag = '<div class="location_filter"><div class="location_filter_inner">';
$fimTag = '</a></div></div>';

while ($qrLista = mysqli_fetch_assoc($arrayQuery)){
	if(strtoupper($qrLista["NOM_FORNECEDOR"][0]) == 'A'){
		$listaA .= $inicioTag .'<a class="valorFiltro" codigo="'.$qrLista["COD_FORNECEDOR"].'" href="#">' . $qrLista["NOM_FORNECEDOR"]. $fimTag;
	} else if (strtoupper($qrLista["NOM_FORNECEDOR"][0]) == 'B') {
		$listaB .= $inicioTag .'<a class="valorFiltro" codigo="'.$qrLista["COD_FORNECEDOR"].'" href="#">' . $qrLista["NOM_FORNECEDOR"]. $fimTag;
	} else if (strtoupper($qrLista["NOM_FORNECEDOR"][0]) == 'C') {
		$listaC .= $inicioTag .'<a class="valorFiltro" codigo="'.$qrLista["COD_FORNECEDOR"].'" href="#">' . $qrLista["NOM_FORNECEDOR"]. $fimTag;
	} else if (strtoupper($qrLista["NOM_FORNECEDOR"][0]) == 'D') {
		$listaD .= $inicioTag .'<a class="valorFiltro" codigo="'.$qrLista["COD_FORNECEDOR"].'" href="#">' . $qrLista["NOM_FORNECEDOR"]. $fimTag;
	} else if (strtoupper($qrLista["NOM_FORNECEDOR"][0]) == 'E') {
		$listaE .= $inicioTag .'<a class="valorFiltro" codigo="'.$qrLista["COD_FORNECEDOR"].'" href="#">' . $qrLista["NOM_FORNECEDOR"]. $fimTag;
	} else if (strtoupper($qrLista["NOM_FORNECEDOR"][0]) == 'F') {
		$listaF .= $inicioTag .'<a class="valorFiltro" codigo="'.$qrLista["COD_FORNECEDOR"].'" href="#">' . $qrLista["NOM_FORNECEDOR"]. $fimTag;
	} else if (strtoupper($qrLista["NOM_FORNECEDOR"][0]) == 'G') {
		$listaG .= $inicioTag .'<a class="valorFiltro" codigo="'.$qrLista["COD_FORNECEDOR"].'" href="#">' . $qrLista["NOM_FORNECEDOR"]. $fimTag;
	} else if (strtoupper($qrLista["NOM_FORNECEDOR"][0]) == 'H') {
		$listaH .= $inicioTag .'<a class="valorFiltro" codigo="'.$qrLista["COD_FORNECEDOR"].'" href="#">' . $qrLista["NOM_FORNECEDOR"]. $fimTag;
	} else if (strtoupper($qrLista["NOM_FORNECEDOR"][0]) == 'I') {
		$listaI .= $inicioTag .'<a class="valorFiltro" codigo="'.$qrLista["COD_FORNECEDOR"].'" href="#">' . $qrLista["NOM_FORNECEDOR"]. $fimTag;
	} else if (strtoupper($qrLista["NOM_FORNECEDOR"][0]) == 'J') {
		$listaJ .= $inicioTag .'<a class="valorFiltro" codigo="'.$qrLista["COD_FORNECEDOR"].'" href="#">' . $qrLista["NOM_FORNECEDOR"]. $fimTag;
	} else if (strtoupper($qrLista["NOM_FORNECEDOR"][0]) == 'K') {
		$listaK .= $inicioTag .'<a class="valorFiltro" codigo="'.$qrLista["COD_FORNECEDOR"].'" href="#">' . $qrLista["NOM_FORNECEDOR"]. $fimTag;
	} else if (strtoupper($qrLista["NOM_FORNECEDOR"][0]) == 'L') {
		$listaL .= $inicioTag .'<a class="valorFiltro" codigo="'.$qrLista["COD_FORNECEDOR"].'" href="#">' . $qrLista["NOM_FORNECEDOR"]. $fimTag;
	} else if (strtoupper($qrLista["NOM_FORNECEDOR"][0]) == 'M') {
		$listaM .= $inicioTag .'<a class="valorFiltro" codigo="'.$qrLista["COD_FORNECEDOR"].'" href="#">' . $qrLista["NOM_FORNECEDOR"]. $fimTag;
	} else if (strtoupper($qrLista["NOM_FORNECEDOR"][0]) == 'N') {
		$listaN .= $inicioTag .'<a class="valorFiltro" codigo="'.$qrLista["COD_FORNECEDOR"].'" href="#">' . $qrLista["NOM_FORNECEDOR"]. $fimTag;
	} else if (strtoupper($qrLista["NOM_FORNECEDOR"][0]) == 'O') {
		$listaO .= $inicioTag .'<a class="valorFiltro" codigo="'.$qrLista["COD_FORNECEDOR"].'" href="#">' . $qrLista["NOM_FORNECEDOR"]. $fimTag;
	} else if (strtoupper($qrLista["NOM_FORNECEDOR"][0]) == 'P') {
		$listaP .= $inicioTag .'<a class="valorFiltro" codigo="'.$qrLista["COD_FORNECEDOR"].'" href="#">' . $qrLista["NOM_FORNECEDOR"]. $fimTag;
	} else if (strtoupper($qrLista["NOM_FORNECEDOR"][0]) == 'Q') {
		$listaQ .= $inicioTag .'<a class="valorFiltro" codigo="'.$qrLista["COD_FORNECEDOR"].'" href="#">' . $qrLista["NOM_FORNECEDOR"]. $fimTag;
	} else if (strtoupper($qrLista["NOM_FORNECEDOR"][0]) == 'R') {
		$listaR .= $inicioTag .'<a class="valorFiltro" codigo="'.$qrLista["COD_FORNECEDOR"].'" href="#">' . $qrLista["NOM_FORNECEDOR"]. $fimTag;
	} else if (strtoupper($qrLista["NOM_FORNECEDOR"][0]) == 'S') {
		$listaS .= $inicioTag .'<a class="valorFiltro" codigo="'.$qrLista["COD_FORNECEDOR"].'" href="#">' . $qrLista["NOM_FORNECEDOR"]. $fimTag;
	} else if (strtoupper($qrLista["NOM_FORNECEDOR"][0]) == 'T') {
		$listaT .= $inicioTag .'<a class="valorFiltro" codigo="'.$qrLista["COD_FORNECEDOR"].'" href="#">' . $qrLista["NOM_FORNECEDOR"]. $fimTag;
	} else if (strtoupper($qrLista["NOM_FORNECEDOR"][0]) == 'U') {
		$listaU .= $inicioTag .'<a class="valorFiltro" codigo="'.$qrLista["COD_FORNECEDOR"].'" href="#">' . $qrLista["NOM_FORNECEDOR"]. $fimTag;
	} else if (strtoupper($qrLista["NOM_FORNECEDOR"][0]) == 'V') {
		$listaV .= $inicioTag .'<a class="valorFiltro" codigo="'.$qrLista["COD_FORNECEDOR"].'" href="#">' . $qrLista["NOM_FORNECEDOR"]. $fimTag;
	} else if (strtoupper($qrLista["NOM_FORNECEDOR"][0]) == 'W') {
		$listaW .= $inicioTag .'<a class="valorFiltro" codigo="'.$qrLista["COD_FORNECEDOR"].'" href="#">' . $qrLista["NOM_FORNECEDOR"]. $fimTag;
	} else if (strtoupper($qrLista["NOM_FORNECEDOR"][0]) == 'X') {
		$listaX .= $inicioTag .'<a class="valorFiltro" codigo="'.$qrLista["COD_FORNECEDOR"].'" href="#">' . $qrLista["NOM_FORNECEDOR"]. $fimTag;
	} else if (strtoupper($qrLista["NOM_FORNECEDOR"][0]) == 'Y') {
		$listaY .= $inicioTag .'<a class="valorFiltro" codigo="'.$qrLista["COD_FORNECEDOR"].'" href="#">' . $qrLista["NOM_FORNECEDOR"]. $fimTag;
	} else if (strtoupper($qrLista["NOM_FORNECEDOR"][0]) == 'Z') {
		$listaZ .= $inicioTag .'<a class="valorFiltro" codigo="'.$qrLista["COD_FORNECEDOR"].'" href="#">' . $qrLista["NOM_FORNECEDOR"]. $fimTag;
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
	<?php if($listaA != ''){?>
	<div class="group" style="">
		<div class="filter_group">a</div> 
		<div class="filters">
			<?php echo $listaA; ?>
		</div>
	</div>
	<?php } if($listaB != ''){?>
	<div class="group" style="">
		<div class="filter_group">b</div> 
		<div class="filters">
			<?php echo $listaB; ?>
		</div>
	</div>
	<?php } if($listaC != ''){?>
	<div class="group" style="">
		<div class="filter_group">c</div> 
		<div class="filters">
			<?php echo $listaC; ?>
		</div>
	</div>
	<?php } if($listaD != ''){?>
	<div class="group" style="">
		<div class="filter_group">d</div> 
		<div class="filters">
			<?php echo $listaD; ?>
		</div>
	</div>
	<?php } if($listaE != ''){?>
	<div class="group" style="">
		<div class="filter_group">e</div> 
		<div class="filters">
			<?php echo $listaE; ?>
		</div>
	</div>
	<?php } if($listaF != ''){?>
	<div class="group" style="">
		<div class="filter_group">f</div> 
		<div class="filters">
			<?php echo $listaF; ?>
		</div>
	</div>
	<?php } if($listaG != ''){?>
	<div class="group" style="">
		<div class="filter_group">g</div> 
		<div class="filters">
			<?php echo $listaG; ?>
		</div>
	</div>
	<?php } if($listaH != ''){?>
	<div class="group" style="">
		<div class="filter_group">h</div> 
		<div class="filters">
			<?php echo $listaH; ?>
		</div>
	</div>
	<?php } if($listaI != ''){?>
	<div class="group" style="">
		<div class="filter_group">i</div> 
		<div class="filters">
			<?php echo $listaI; ?>
		</div>
	</div>	
	<?php } if($listaJ != ''){?>
	<div class="group" style="">
		<div class="filter_group">j</div> 
		<div class="filters">
			<?php echo $listaJ; ?>
		</div>
	</div>
	<?php } if($listaK != ''){?>
	<div class="group" style="">
		<div class="filter_group">k</div> 
		<div class="filters">
			<?php echo $listaK; ?>
		</div>
	</div>
	<?php } if($listaL != ''){?>
	<div class="group" style="">
		<div class="filter_group">l</div> 
		<div class="filters">
			<?php echo $listaL; ?>
		</div>
	</div>
	<?php } if($listaM != ''){?>
	<div class="group" style="">
		<div class="filter_group">m</div> 
		<div class="filters">
			<?php echo $listaM; ?>
		</div>
	</div>
	<?php } if($listaN != ''){?>
	<div class="group" style="">
		<div class="filter_group">N</div> 
		<div class="filters">
			<?php echo $listaN; ?>
		</div>
	</div>
	<?php } if($listaO != ''){?>
	<div class="group" style="">
		<div class="filter_group">O</div> 
		<div class="filters">
			<?php echo $listaO; ?>
		</div>
	</div>
	<?php } if($listaP != ''){?>
	<div class="group" style="">
		<div class="filter_group">P</div> 
		<div class="filters">
			<?php echo $listaP; ?>
		</div>
	</div>
	<?php } if($listaQ != ''){?>
	<div class="group" style="">
		<div class="filter_group">q</div> 
		<div class="filters">
			<?php echo $listaQ; ?>
		</div>
	</div>
	<?php } if($listaR != ''){?>
	<div class="group" style="">
		<div class="filter_group">r</div> 
		<div class="filters">
			<?php echo $listaR; ?>
		</div>
	</div>
	<?php } if($listaS != ''){?>
	<div class="group" style="">
		<div class="filter_group">s</div> 
		<div class="filters">
			<?php echo $listaS; ?>
		</div>
	</div>
	<?php } if($listaT != ''){?>
	<div class="group" style="">
		<div class="filter_group">t</div> 
		<div class="filters">
			<?php echo $listaT; ?>
		</div>
	</div>
	<?php } if($listaU != ''){?>
	<div class="group" style="">
		<div class="filter_group">u</div> 
		<div class="filters">
			<?php echo $listaU; ?>
		</div>
	</div>	
	<?php } if($listaV != ''){?>
	<div class="group" style="">
		<div class="filter_group">v</div> 
		<div class="filters">
			<?php echo $listaV; ?>
		</div>
	</div>
	<?php } if($listaW != ''){?>
	<div class="group" style="">
		<div class="filter_group">w</div> 
		<div class="filters">
			<?php echo $listaW; ?>
		</div>
	</div>
	<?php } if($listaX != ''){?>
	<div class="group" style="">
		<div class="filter_group">x</div> 
		<div class="filters">
			<?php echo $listaX; ?>
		</div>
	</div>
	<?php } if($listaY != ''){?>
	<div class="group" style="">
		<div class="filter_group">y</div> 
		<div class="filters">
			<?php echo $listaY; ?>
		</div>
	</div>
	<?php } if($listaZ != ''){?>
	<div class="group" style="">
		<div class="filter_group">z</div> 
		<div class="filters">
			<?php echo $listaZ; ?>
		</div>
	</div>	
	<?php } ?>
</div>

<script type="text/javascript">

		$(document).ready(function(){
			$( ".valorFiltro" ).click(function() {
				parent.$('#nomeModal').text("fornecedor");
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