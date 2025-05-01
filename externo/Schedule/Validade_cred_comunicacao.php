<?php
include '../../_system/_functionsMain.php';


//capturar o saldo ativo para vencimento

$Sqlsaldo="SELECT 
                 pedido.LOG_TESTE,
		 pedido.COD_CAMPANHA,			
                 pedido.COD_EMPRESA , 
                 pedido.COD_PRODUTO,
                 pedido.COD_ORCAMENTO,
                 pedido.COD_VENDA,  
                 pedido.QTD_SALDO_ATUAL,
                 canal.COD_CANALCOM
        FROM pedido_marka pedido 
           INNER JOIN produto_marka prod ON prod.COD_PRODUTO = pedido.COD_PRODUTO 
           INNER JOIN canal_comunicacao canal ON canal.COD_CANALCOM = prod.COD_CANALCOM 
           INNER JOIN empresas emp ON emp.COD_EMPRESA = pedido.COD_EMPRESA  and emp.LOG_ATIVO='S'
           WHERE pedido.COD_ORCAMENTO > 0 AND
                   PAG_CONFIRMACAO='S' 
                  and pedido.DAT_VALIDADE < CURDATE()
                  AND pedido.QTD_SALDO_ATUAL > 0  AND 
                   pedido.DAT_VALIDADE IS NOT NULL and
                   pedido.TIP_LANCAMENTO ='C' 
          ORDER BY pedido.TIP_LANCAMENTO desc ";

$rs_saldo=mysqli_query($connAdm->connAdm (), $Sqlsaldo);
while ($rwsqldo = mysqli_fetch_assoc($rs_saldo)) {

   //lançar o debito
    $sqlinDebito="INSERT INTO pedido_marka (        COD_ORCAMENTO, 
                                                    COD_PRODUTO, 
                                                    QTD_PRODUTO, 
                                                    VAL_UNITARIO, 
                                                    COD_EMPRESA, 
                                                    COD_UNIVEND, 
                                                    ID_SESSION_PAGSEGURO, 
                                                    PAG_CONFIRMACAO, 
                                                    TIP_LANCAMENTO, 
                                                    COD_CAMPANHA,
                                                    LOG_TESTE,
                                                    DAT_CADASTR)
                                                    VALUES ('".date('ymdHis')."', 
                                                            '".$rwsqldo['COD_PRODUTO']."', 
                                                            '".$rwsqldo['QTD_SALDO_ATUAL']."', 
                                                            '0.000000', 
                                                            '".$rwsqldo['COD_EMPRESA']."', 
                                                            '0', 
                                                            'EXPIROU',
                                                            'S', 
                                                            'D',
                                                            '".$rwsqldo['COD_CAMPANHA']."',
                                                            '".$rwsqldo['LOG_TESTE']."',
                                                             now());";
  
    $rwdebitos=mysqli_query($connAdm->connAdm (), $sqlinDebito); 
    if(!$rwdebitos)
    {
          echo '<br>'.$sqlinDebito.'<br>';
    }    
   // update para zerar o saldo
    $updatesaldo= "UPDATE pedido_marka SET QTD_SALDO_ATUAL=0 WHERE COD_EMPRESA ='".$rwsqldo['COD_EMPRESA']."' AND COD_VENDA=$rwsqldo[COD_VENDA]";
  
    $rwupsaldp=mysqli_query($connAdm->connAdm (), $updatesaldo);
    if(!$rwupsaldp)
    {
         echo '<br>'.$updatesaldo.'<br>';
    }  
}

