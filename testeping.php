<?php
$ip=$_SERVER['REMOTE_ADDR'];
echo $ip;
exec("ping $ip -c 3", $output, $retval);
//$var=array('COD_TEMPLATE'=>array(3=>array('ArrayProdutos'=>array(2=>array(0=>array('COD_PRODUTO'=>'362596','VAL_PRODTKT'=>'15,89','VAL_PROMTKT'=>'13,00','NOM_PRODUTO'=>'HIDNIVEAMILK400MLEXT.SECA',),1=>array('COD_PRODUTO'=>'368163','VAL_PRODTKT'=>'32,74','VAL_PROMTKT'=>'28,99','NOM_PRODUTO'=>'AGUAMICELARSENSIBIO100ML',),2=>array('COD_PRODUTO'=>'370989','VAL_PRODTKT'=>'50,31','VAL_PROMTKT'=>'45,99','NOM_PRODUTO'=>'VARICELLCREMEDIABETICO300G',),3=>array('COD_PRODUTO'=>'375327','VAL_PRODTKT'=>'8,99','VAL_PROMTKT'=>'5,99','NOM_PRODUTO'=>'HIGICALCINHA300ML',),4=>array('COD_PRODUTO'=>'367305','VAL_PRODTKT'=>'11,94','VAL_PROMTKT'=>'9,99','NOM_PRODUTO'=>'ABSINT.INTIMUSSUPERC/16UN',),),),'ArrayOferta'=>array(9=>array(0=>array('COD_PRODUTO'=>'357447','VAL_PRODTKT'=>'50,40','VAL_PROMTKT'=>'14,99','NOM_PRODUTO'=>'LAVITANOMEGA360','DES_IMAGEM'=>'lavitan-omega-3-1000mg-3-caixas-60-capsulas-total-180-cp-D_NQ_NP_486521-MLB20810095634_072016-O.jpg',),),),'ArraySaldo'=>array(4=>array('TOTAL_CREDITO'=>'0,00',),),'ArrayRodaPe'=>NULL,'ArrayImagem'=>array(6=>array('DES_IMAGEM'=>'Bifarma.png',),),'ArrayHabitos'=>array(8=>'',),'ArraySaldoCartao'=>NULL,),),);

echo '<pre>';
print_r($output);

print_r($var);

 echo '<pre>';


?>
