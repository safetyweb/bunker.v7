<?php include "_system/_functionsMain.php"; 

echo fnDebug('true');

$codEmpresa = fnLimpacampo($_GET['ajx1']);
$codVenda = fnLimpacampo($_GET['ajx2']);
$tipoEstorno = fnLimpacampo($_GET['ajx3']);
$codCliente = fnLimpacampo(isset($_GET['ajx4']));
$opcao = fnLimpacampo($_GET['ajx5']);
$codItemVenda = fnLimpacampo($_GET['ajx6']);
$qtdeItemVenda = fnLimpacampo($_GET['ajx7']);

switch ($tipoEstorno) {
	case 1://estornar venda
		$sql = "CALL SP_ESTORNA_VENDA(" .$codCliente. ", " .$codVenda. ", " .$codEmpresa. ", '" .$_SESSION["SYS_COD_USUARIO"]. "', '" .$opcao. "' )";
		break;     
	case 2://estornar item
		$sql = "CALL SP_EXCLUI_ITEM(" .$codEmpresa. ", " .$codVenda. ", " .$codItemVenda. ", " .$qtdeItemVenda. ", '" .$_SESSION["SYS_COD_USUARIO"]. "', '" .$opcao. "' )";
		break; 					
}	
	
//fnEscreve($sql);
//fnEscreve($codEmpresa);
mysqli_query(connTemp($codEmpresa,''),trim($sql)) or die(mysqli_error());			
?>
<script>
alert("<?php echo $sql ?>");
</script>
<div class="help-block with-errors"></div>	