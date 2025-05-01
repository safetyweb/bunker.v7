<?php

include "_system/_functionsMain.php";
$cod_empresa = fnLimpacampo($_POST['codEmpresa']);
$dado_compara = fnLimpacampo(fnDecode($_POST['DADO_COMPARA']));
$dado_confirm = fnLimpacampo($_POST['DADO_CONFIRM']);

if($dado_compara == $dado_confirm){
	echo 1;
}else{
	echo 0;
}

?>


