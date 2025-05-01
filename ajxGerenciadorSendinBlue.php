<?php include "_system/_functionsMain.php"; 

echo fnDebug('true');

$cod_empresa = fnLimpacampo($_GET['cod_empresa']);

$sql = "select NOM_EMPRESA, NOM_FANTASI from EMPRESAS where COD_EMPRESA = $cod_empresa";
$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());

echo implode("|",mysqli_fetch_assoc($arrayQuery));
?>