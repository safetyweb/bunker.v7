<?php 
include 'header.php';
$tituloPagina = "O Programa";
include "navegacao.php";

$sql = "SELECT DES_SOBRE FROM SITE_EXTRATO WHERE COD_EMPRESA = $cod_empresa";
//fnEscreve($sql);

$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql) or die(mysqli_error());
$qrBuscaSiteExtrato = mysqli_fetch_assoc($arrayQuery);

?>

<div class="container">

		<div class="push50"></div>
			
		<div class="row">		
			
			<div class="col-xs-10 col-xs-offset-1" style="height:78vh; overflow-y: auto; text-align: justify;">
				<?=html_entity_decode($qrBuscaSiteExtrato['DES_SOBRE'])?>
			</div>

		</div>
</div>