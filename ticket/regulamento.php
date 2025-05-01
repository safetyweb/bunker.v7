<?php
	
//echo fnDebug('true');
include '../_system/_functionsMain.php';
// $cod_empresa = fnDecode($_GET['id']);
// $cod_termo = fnLimpaCampoZero(fnDecode($_GET['idt']));

//busca dados da url	
if (fnLimpacampo($_GET['param']) != "") {
	//busca codigo da empresa
	$cod_busca = strtolower(fnLimpacampo($_GET['param']));
	$sql = "select COD_EMPRESA from DOMINIO WHERE DES_DOMINIO = '$cod_busca' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrBuscaCodEmpresa = mysqli_fetch_assoc($arrayQuery);
	//fnEscreve($qrBuscaCodEmpresa['COD_EMPRESA']);                
	$cod_empresa = $qrBuscaCodEmpresa['COD_EMPRESA'];
	//$nom_fantasi = $qrBuscaCodEmpresa['NOM_FANTASI'];

	if (isset($qrBuscaCodEmpresa)) {
		$cod_empresa = $qrBuscaCodEmpresa['COD_EMPRESA'];
		$siteGo = "OK";
	} else {
		$siteGo = "NOK";
	}
}

$sqlTermo = "SELECT * FROM TERMOS_EMPRESA 
			 WHERE COD_EMPRESA = $cod_empresa 
			 AND ABV_TERMO = 'Regulamento'";



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