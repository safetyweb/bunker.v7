<?php include "_system/_functionsMain.php"; 

//echo fnDebug('true');

$tipo = fnLimpacampo($_POST['TIPO']);
$idCampo = fnLimpacampo($_POST['ID_CAMPO']);
$cod_empresa = fnLimpacampozero(fnDecode($_POST['COD_EMPRESA']));
$opcao = fnLimpacampo($_POST['OPCAO']);

$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

$sql = "CALL SP_ALTERA_MATRIZ_CAMPO_INTEGRA (
 '".$cod_empresa."', 
 '".$idCampo."', 
 '".$tipo."', 
 '".$cod_usucada."', 
 '".$opcao."'    
) ";

echo $sql;				
mysqli_query($connAdm->connAdm(),trim($sql));

if($tipo == "OBG" && $opcao == "CAD"){

	$sql2 = "CALL SP_ALTERA_MATRIZ_CAMPO_INTEGRA (
	 '".$cod_empresa."', 
	 '".$idCampo."', 
	 'REQ', 
	 '".$cod_usucada."', 
	 '".$opcao."'    
	) ";

	mysqli_query($connAdm->connAdm(),trim($sql2));

}


// fnEscreve($tipo);
// fnEscreve($idCampo);
// fnEscreve($cod_empresa);
// fnEscreve($opcao);
fnEscreve($sql);
fnEscreve($sql2);


?>	
