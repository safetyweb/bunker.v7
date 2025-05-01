<?php

include "_system/_functionsMain.php";
$cod_empresa = fnLimpaCampoZero(fnDecode(base64_decode($_POST['codEmpresa'])));
$cod_cliente = fnLimpaCampoZero(fnDecode(base64_decode($_POST['COD_CLIENTE'])));
$des_senhaus = fnEncode($_POST['DES_SENHAUS']);

$sqlCli = "SELECT 1 FROM CLIENTES 
			WHERE COD_CLIENTE = $cod_cliente 
			AND COD_EMPRESA = $cod_empresa
			AND DES_SENHAUS = '$des_senhaus'";

// echo($sql);

$resultCli = mysqli_query(connTemp($cod_empresa,''),trim($sqlCli));	

$linhasCli = mysqli_num_rows($resultCli);

$sql = "SELECT 1 FROM CONT_PWD 
		WHERE COD_CLIENTE = $cod_cliente 
		AND COD_EMPRESA = $cod_empresa
		AND DES_SENHAUS = '$des_senhaus'";

// echo($sql);

$result = mysqli_query(connTemp($cod_empresa,''),trim($sql));	

$linhasHist = mysqli_num_rows($result);

$linhas = $linhasCli + $linhasHist;

echo $linhas;



// echo $linhas;

?>


