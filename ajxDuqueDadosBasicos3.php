<?php include "_system/_functionsMain.php"; 

//echo fnDebug('true');

$buscaAjx1 = $_GET['ajx1'];
$buscaAjx2 = $_GET['ajx2'];
$buscaAjx3 = $_GET['ajx3'];
$buscaAjx4 = $_GET['ajx4'];
$cod_empresa = $buscaAjx1;

//fnEscreve($buscaAjx1);
//fnEscreve($buscaAjx2);
//fnEscreve($buscaAjx3);
//fnEscreve($buscaAjx4);

//busca nome da entidade
$sql4="select COD_PLANO from plano where cod_grupo = $buscaAjx2 and cod_entidad = $buscaAjx3 ";
$qrBuscaPlano = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql4));		
//fnEscreve($sql3);	
$cod_plano = $qrBuscaPlano['COD_PLANO'];

//fnEscreve($cod_plano);

?>

	
	<div class="push10"></div>
	
	<h3 style="margin: 0 0 0 15px;">Preço Autorizado</h3>

	<div class="push20"></div>

	<div class="col-md-3"> 
	<?php
	$sql5 = "select b.DES_PRODUTO,a.VAL_PRODUTO from plano_valor a,produtocliente b where a.cod_produto=b.cod_produto and a.cod_plano = $cod_plano ";	
	//fnEscreve($sql5);
	$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql5) or die(mysqli_error());	
	while ($qrListaPrecos = mysqli_fetch_assoc($arrayQuery))
	  {
	?>
	<small><b><?php echo $qrListaPrecos['DES_PRODUTO']; ?>: </b></small> <?php echo $qrListaPrecos['VAL_PRODUTO']; ?>	<br/>												
	<?php	
	}	
	?>
	</div>
