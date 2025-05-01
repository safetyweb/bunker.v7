<?php 

	include '../_system/_functionsMain.php';

	$cod_estado = fnLimpaCampoZero($_GET['uf']);
	$cod_empresa = fnLimpaCampoZero(fnDecode($_GET['id']));

	$sql = "SELECT COD_MUNICIPIO, NOM_MUNICIPIO FROM MUNICIPIOS WHERE COD_ESTADO = $cod_estado ORDER BY NOM_MUNICIPIO";

	// fnEscreve($cod_estado);
	$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
													
	while ($qrCidade = mysqli_fetch_assoc($arrayQuery)){												
		?>
			<div class="cidade">
				<a class="activeRel" href="javascript:void(0)" onclick="geraFiltro(this,'<?=$qrCidade[NOM_MUNICIPIO]?>')">&rsaquo; <?php echo $qrCidade['NOM_MUNICIPIO']?> </a> 
				<div class="push5"></div>
			</div>
		<?php	
	}

?>