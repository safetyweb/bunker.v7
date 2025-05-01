<?php include "_system/_functionsMain.php";

$opcao = $_GET['opcao'];
$cod_empresa = fnDecode($_GET['idEmpresa']);
$log_msgcobr = fnDecode($_GET['LOG_MSGCOBR']);

fnEscreve($opcao);
fnEscreve($cod_empresa);
fnEscreve($log_msgcobr); 


?>