<?php 
include "_system/_functionsMain.php"; 


$cod_busca = fnLimpacampoZero($_REQUEST['ajxEmp']);

fnEscreve($cod_busca);

?>