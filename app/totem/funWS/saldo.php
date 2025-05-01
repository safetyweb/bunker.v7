<?php
function fnexibisaldo($arraydadosaldo)
{
//select do saldo
       $saldo = "SELECT (SELECT Sum(val_credito) 
        FROM   creditosdebitos 
        WHERE  cod_cliente = A.cod_cliente
            AND cod_statuscred <> 6            
            AND tip_credito = 'C')  AS TOTAL_CREDITOS,
            
        (SELECT Sum(val_credito) 
        FROM   creditosdebitos 
        WHERE  cod_cliente = A.cod_cliente 
            AND tip_credito = 'D')  AS TOTAL_DEBITOS,
            
        (SELECT Sum(val_saldo) 
        FROM   creditosdebitos 
        WHERE  cod_cliente = A.cod_cliente 
            AND tip_credito = 'C' 
            AND COD_STATUSCRED = 1 
            AND ((log_expira='S' and dat_expira > Now())or(log_expira='N'))) AS CREDITO_DISPONIVEL, 
            
        (SELECT Sum(val_credito) 
        FROM   creditosdebitos 
        WHERE  cod_cliente = A.cod_cliente 
            AND tip_credito = 'C' 
            AND COD_STATUSCRED = 2 
            AND dat_expira > Now()) AS CREDITO_ALIBERAR,
            
        (SELECT Sum(val_credito) 
        FROM   creditosdebitos 
        WHERE  cod_cliente = A.cod_cliente 
            AND tip_credito = 'C' 
            AND COD_STATUSCRED = 3 
            AND dat_expira > Now()) AS CREDITO_BLOQUEADO,
            
        (SELECT Sum(val_saldo) 
        FROM   creditosdebitos 
        WHERE  cod_cliente = A.cod_cliente 
            AND tip_credito = 'C' 
            AND COD_STATUSCRED = 4) AS CREDITO_EXPIRADOS 
      
      FROM CREDITOSDEBITOS A
      WHERE COD_CLIENTE=".$result['COD_CLIENTE']."
      AND COD_EMPRESA = ".$arrayCampos['4']."
      GROUP BY COD_CLIENTE
      ";
      $arrayQuery =  mysqli_fetch_assoc(mysqli_query(connTemp($arrayCampos['4'],''),$saldo));
      $SALDOTOTAL = fnValor(($arrayQuery['CREDITO_DISPONIVEL']+$arrayQuery['CREDITO_ALIBERAR']),2);
}     
      ?>