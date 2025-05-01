<?php include "_system/_functionsMain.php"; 

//echo fnDebug('true');

$buscaAjx1 = fnLimpacampo($_GET['ajx1']);
$buscaAjx2 = fnLimpacampo($_GET['ajx2']);
$buscaAjx3 = fnLimpacampo($_GET['ajx3']);
$buscaAjx4 = fnLimpacampo($_GET['ajx4']);

$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
	
$sql = "CALL SP_ALTERA_MATRIZ_INTEGRA (
 '".$buscaAjx3."', 
 '".$buscaAjx2."', 
 '".$buscaAjx1."', 
 '".$cod_usucada."', 
 '".$buscaAjx4."'    
) ";

fnEscreve($sql);				
mysqli_query($connAdm->connAdm(),trim($sql));

//fnEscreve($buscaAjx1);

?>	
