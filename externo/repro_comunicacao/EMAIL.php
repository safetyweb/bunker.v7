<?php

require '../../_system/_functionsMain.php';
$conadmmysql=$connAdm->connAdm();
$empresa=39;
//$sqlpacero="SELECT * FROM CONFIGURACAO_ACESSO WHERE  cod_parcomu='12'and cod_empresa=77 AND LOG_STATUS='S'";
//$parcerorw=mysqli_query($conadmmysql, $sqlpacero);
//while ($parcerors=mysqli_fetch_assoc($parcerorw))
//{  
    $contempmysql=connTemp($empresa,''); 
    $sqlQTD_LISTA="SELECT             
                    QTD_LISTA, 
                    COD_CAMPANHA,
                    COD_EMPRESA,
                    LOG_TESTE,
                    LOG_ENVIO,
                    DAT_CADASTR 
                    FROM   EMAIL_LOTE 
                 where                   
         cod_empresa=$empresa AND  LOG_ENVIO ='S' and DAT_AGENDAMENTO>='2020-01-01 00:00:00'";  
    $rwQTD_LISTA= mysqli_query($contempmysql, $sqlQTD_LISTA);
    $Contador=0;
    while ($rsQTD_LISTA= mysqli_fetch_assoc($rwQTD_LISTA))
    {
        $arraydebitos=array('quantidadeEmailenvio'=>$rsQTD_LISTA['QTD_LISTA'],
                            'COD_EMPRESA'=>$rsQTD_LISTA['COD_EMPRESA'],
                            'PERMITENEGATIVO'=>'S',
                            'COD_CANALCOM'=>'1',
                            'CONFIRMACAO'=>'N',
                            'COD_CAMPANHA'=>$rsQTD_LISTA['COD_CAMPANHA'],    
                            'LOG_TESTE'=>$rsQTD_LISTA['LOG_TESTE'],
                            'DAT_CADASTR'=>$rsQTD_LISTA['DAT_CADASTR'],
                            'CONNADM'=>$connAdm->connAdm()
                            );  
        $teste=FnDebitos($arraydebitos);
       
        unset($arraydebitos);
        $Contador++;
    }        

echo '<br>'.$Contador.'<br>';
//}

/*

$arraydebitos=array('quantidadeEmailenvio'=>'50000',
                    'COD_EMPRESA'=>'77',
                    'PERMITENEGATIVO'=>'S',
                    'COD_CANALCOM'=>'1',
                    'CONFIRMACAO'=>'N',
                    'COD_CAMPANHA'=>'0',                
                    'CONNADM'=>$connAdm->connAdm());  
$teste=FnDebitos($arraydebitos);
echo'<pre>';
print_r($teste);
echo'</pre>';
*/
?>