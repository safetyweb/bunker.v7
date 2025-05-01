<?php
//habilitando o cors
// header("Access-Control-Allow-Origin: *");
//echo fnDebug('true');
include '../_system/_functionsMain.php';
$cod_empresa = fnDecode($_GET['id']);
$cod_termo = fnLimpaCampoZero(fnDecode($_GET['idt']));

$sqlTermo = "SELECT * FROM TERMOS_EMPRESA 
			 WHERE COD_EMPRESA = $cod_empresa 
			 AND COD_TERMO = $cod_termo";



$arrayTermo = mysqli_query(connTemp($cod_empresa,''), $sqlTermo);
$qrTermo = mysqli_fetch_assoc($arrayTermo);

$cod_tipo = $qrTermo['COD_TIPO'];
$nom_termo = $qrTermo['NOM_TERMO'];			
$abv_termo = $qrTermo['ABV_TERMO'];
$des_termo = $qrTermo['DES_TERMO'];
if ($qrTermo['LOG_ATIVO'] == 'S') {$checkAtivo='checked';}else{$checkAtivo='';}



?>



	<div class="row">
		
		<div class="col-md-12">

			<?=html_entity_decode($des_termo)?>	

		</div>

	</div>