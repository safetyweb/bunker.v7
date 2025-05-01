<?php

include "_system/_functionsMain.php";

$des_senhaus_old = fnEncode($_REQUEST['DES_SENHAUS_OLD']);
$des_senhaus = fnEncode($_REQUEST['DES_SENHAUS']);
$cod_cliente = fnLimpaCampoZero(fnDecode($_REQUEST['idc']));

// fnEscreve($_REQUEST['DES_SENHAUS_OLD']);
// fnEscreve($_REQUEST['DES_SENHAUS']);

$cod_empresa = 19;

// echo("Empresa: ".$cod_empresa);

$sql = "SELECT COD_CLIENTE FROM CLIENTES WHERE COD_CLIENTE = $cod_cliente AND DES_SENHAUS = '$des_senhaus_old'";

// fnEscreve($sql);

$arrayQuery = mysqli_query(connTemp($cod_empresa,''),trim($sql));	

$linhas = mysqli_num_rows($arrayQuery);

// fnEscreve($linhas);
  
if($linhas == 1){
	$sql = "UPDATE CLIENTES SET DES_SENHAUS = '$des_senhaus' WHERE COD_CLIENTE = $cod_cliente";
	mysqli_query(connTemp($cod_empresa,''),trim($sql));
	echo 1;
}else{
	echo 0;
}

?>


