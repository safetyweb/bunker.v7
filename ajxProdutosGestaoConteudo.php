<?php include "_system/_functionsMain.php"; 

echo fnDebug('true');

$cod_empresa = fnLimpacampo($_GET['codEmpresa']);
$cod_produto = fnLimpacampo($_GET['codProduto']);

$sql = "SELECT PRODUTOTKT.COD_PRODUTO, PERSONA.COD_PERSONA, PERSONA.DES_PERSONA
		FROM PRODUTOTKT
			INNER JOIN PERSONA ON PRODUTOTKT.COD_PERSONA_TKT = PERSONA.COD_PERSONA
		WHERE PRODUTOTKT.COD_PRODUTO = $cod_produto";

$arrayPersona = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());

$sql = "SELECT VANTAGEMEXTRAFAIXA.COD_PRODUTO, CAMPANHA.COD_CAMPANHA, CAMPANHA.DES_CAMPANHA
		FROM VANTAGEMEXTRAFAIXA
		INNER JOIN CAMPANHA ON VANTAGEMEXTRAFAIXA.COD_CAMPANHA = CAMPANHA.COD_CAMPANHA
		WHERE VANTAGEMEXTRAFAIXA.COD_PRODUTO = $cod_produto";

$arrayCampanha = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
?>

<div class="row">
	<div class="col-lg-6">
	<h5>Campanhas</h5>
	<div class="push10"></div>
		<ul class="tag">
			<?php
			while ($qrListaCampanha = mysqli_fetch_assoc($arrayCampanha)){	
				echo '<li class="tag"><span class="label label-warning">● &nbsp;' .$qrListaCampanha["DES_CAMPANHA"]. '</span></li>';
			}
			?>
		</ul>
	</div>
	<div class="col-lg-6">
	<h5>Personas</h5>
	<div class="push10"></div>
		<ul class="tag">
			<?php
			while ($qrListaPersona = mysqli_fetch_assoc($arrayPersona)){	
				echo '<li class="tag"><span class="label label-info">● &nbsp;' .$qrListaPersona["DES_PERSONA"]. '</span></li>';
			}
			?>
		</ul>
	</div>
	<div class="push20"></div>	
</div>




