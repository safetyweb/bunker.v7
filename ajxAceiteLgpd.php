<?php include "_system/_functionsMain.php"; 

$cod_empresa = fnLimpacampozero(fnDecode($_POST['COD_EMPRESA']));
$log_lgpd = fnLimpacampo($_POST['LOG_LGPD']);

$sqlUpdt = "UPDATE CONTROLE_TERMO SET LOG_LGPD = '$log_lgpd' WHERE COD_EMPRESA = $cod_empresa";

fnEscreve($sqlUpdt);

fnTestesql(connTemp($cod_empresa,''),$sqlUpdt);

?>

