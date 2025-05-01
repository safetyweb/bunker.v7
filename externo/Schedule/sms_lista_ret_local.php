<?php
include '../../_system/_functionsMain.php';
$conadmmysql=$connAdm->connAdm();
$empresas="select * from empresas  WHERE LOG_ATIVO='S'";
$rsempresas= mysqli_query($conadmmysql, $empresas);

while ($rwempresas= mysqli_fetch_assoc($rsempresas))
{
    $contemporaria = connTemp($rwempresas['COD_EMPRESA'], ''); 
    $sqlcliente="SELECT 
                        ger.COD_TOKEN,
                        ger.COD_EMPRESA,
                        ger.DAT_CADASTR,
                        ger.DES_TOKEN,
                        ger.LOG_USADO,
                        listret.ID_DISPARO,
                        listret.COD_CLIENTE codigoclienteup,
                        ger.COD_CLIENTE codigoclientegerado,		
                        listret.CHAVE_GERAL,
                        listret.CHAVE_CLIENTE,
                        rel.CHAVE_GERAL,
                        rel.CHAVE_CLIENTE
            FROM geratoken ger
                INNER JOIN   rel_geratoken rel ON rel.COD_GERATOKEN=ger.COD_TOKEN
                inner JOIN sms_lista_ret  listret ON listret.ID_DISPARO=ger.COD_TOKEN AND listret.CHAVE_GERAL=rel.CHAVE_GERAL AND listret.CHAVE_CLIENTE=rel.CHAVE_CLIENTE
               WHERE 
              ger.COD_EMPRESA = ".$rwempresas['COD_EMPRESA']." AND
              ger.COD_CLIENTE > '0' AND
              listret.COD_CLIENTE= '0' AND 
              ger.LOG_USADO=2 AND 
              DATE(ger.DAT_CADASTR) >='2021-10-29'";
    
    $rscliente=mysqli_query($contemporaria, $sqlcliente);
    while ($rwcliente= mysqli_fetch_assoc($rscliente)) {
       echo "empresa...: ".$rwempresas['COD_EMPRESA'].'<br>'; 
       $alterclienteret="update sms_lista_ret set COD_CLIENTE=$rwcliente[codigoclientegerado] where ID_DISPARO=$rwcliente[COD_TOKEN] and CHAVE_CLIENTE='".$rwcliente[CHAVE_CLIENTE]."'";
       mysqli_query($contemporaria, $alterclienteret);
    }
}        

?>