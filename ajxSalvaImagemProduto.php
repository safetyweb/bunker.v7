<?php
include "_system/_functionsMain.php";
echo fnDebug('true');

$COD_EMPRESA = fnLimpacampo($_REQUEST['COD_EMPRESA']);
$COD_PRODUTO = fnLimpacampo($_REQUEST['COD_PRODUTO']);
$DES_IMAGEM = fnLimpacampo($_REQUEST['DES_IMAGEM']);
$conn = connTemp($COD_EMPRESA, '');
if ($COD_PRODUTO != "" && $DES_IMAGEM != "") {
	$sql = "UPDATE PRODUTOCLIENTE SET DES_IMAGEM='$DES_IMAGEM' WHERE COD_PRODUTO='0$COD_PRODUTO' AND COD_EMPRESA='$COD_EMPRESA'";
	$rs = mysqli_query($conn, trim($sql)) or die(mysqli_error($conn));

	echo "UPDATE EFETUADO";
}
