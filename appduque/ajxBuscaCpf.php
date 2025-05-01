<?php
include './_system/_functionsMain.php';

$cod_empresa = fnLimpaCampoZero(fnDecode($_REQUEST['COD_EMPRESA']));
$cpf = fnLimpaCampo(fnLimpaDoc($_REQUEST['CPF']));

$sql = "SELECT COD_CLIENTE FROM CLIENTES WHERE NUM_CGCECPF = '$cpf' AND COD_EMPRESA = $cod_empresa";

// fnEscreve($sql);

$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);

if(mysqli_num_rows($arrayQuery) == 1){
	echo 1;
}else{
	echo 0;
}

?>