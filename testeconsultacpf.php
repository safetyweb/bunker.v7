<?php
//include '_system/_functionsMain.php';
include './_system/_FUNCTION_WS.php';
$teste=fnconsultaCPF('01734200014',7,'diogo.farmacia','tankd1423312');
echo '<pre>';
print_r($teste);
echo '<pre>';
echo $teste['cartao'][0];
echo $teste['tipocliente'][0];
?>
        
