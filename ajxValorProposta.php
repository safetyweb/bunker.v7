<?php 
include './_system/_functionsMain.php';

$cod_empresa = $_POST['COD_EMPRESA'];
$cod_proposta = $_POST['COD_PROPOSTA'];

$sql = "SELECT VAL_VALOR FROM PROPOSTA WHERE COD_PROPOSTA = $cod_proposta AND COD_EMPRESA = $cod_empresa";
$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
$qrValor = mysqli_fetch_assoc($arrayQuery);

//fnEscreve($sql);

echo fnValor($qrValor['VAL_VALOR'],2);

?>
