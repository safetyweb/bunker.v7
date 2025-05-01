<?php include "_system/_functionsMain.php"; 

//echo fnDebug('true');

$codVenda = fnLimpacampo($_GET['ajx1']);
$codCliente = fnLimpacampo($_GET['ajx2']);
$opcao = fnLimpacampo($_GET['ajx3']);
$logGeral = fnLimpacampo($_GET['ajx4']);
$codEmpresa = fnLimpacampo($_GET['ajx5']);
$codLoja = fnLimpacampo($_GET['ajx6']);
	
	$sql = "CALL SP_DESBLOQUEA_VENDA(" .$codCliente. ", " .$codVenda. ", " .$codEmpresa. ", '" .$logGeral. "', '" .$_SESSION["SYS_COD_USUARIO"]. "', '" .$codLoja. "', '" .$opcao. "' )";
	// fnEscreve($sql);
	//fnEscreve($codEmpresa);
	mysqli_query(connTemp($codEmpresa,''),trim($sql)) or die(mysqli_error());			
?>
<div class="help-block with-errors"></div>
