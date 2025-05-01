<?php

include "../_system/_functionsMain.php";

$cod_empresa = fnLimpaCampo(fnDecode($_GET['id']));
$cod_cliente = fnLimpaCampo(fnDecode($_GET['idc']));
$tipo_dado = fnLimpaCampo($_POST['TIPO_DADO']);
$dado_confirm = fnLimpaCampo($_POST['DADO_CONFIRM']);

$sqlConfirm = "SELECT 1 FROM CLIENTES WHERE $tipo_dado = '$dado_confirm' AND COD_EMPRESA = $cod_empresa AND COD_CLIENTE = $cod_cliente";

$arrayConfirm = mysqli_query(connTemp($cod_empresa,''),$sqlConfirm);

$valida = mysqli_num_rows($arrayConfirm);

if($valida == 1){

	echo 'validado';

}else{

	echo 0;

}

?>