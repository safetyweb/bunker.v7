<?php
include_once '../../_system/_functionsMain.php';
include_once '../email/envio_sac.php';
$conadmmysql=$connAdm->connAdm();

$empresas=" SELECT 
                res.DES_GATILHO,
                res.COD_EMPRESA, 
                res.TIP_RESTRIC,
                GROUP_CONCAT( DISTINCT 	res.COD_USUARIO SEPARATOR ',')COD_USUARIO,  
                GROUP_CONCAT( DISTINCT 	us.DES_EMAILUS SEPARATOR ';') DES_EMAILUS 
            from usuarios_restritos res 
            INNER JOIN usuarios us ON us.COD_USUARIO=res.COD_USUARIO
            WHERE 
                res.tip_restric = 'SLD' and    
                us.LOG_ESTATUS='S' and
                us.DES_EMAILUS  REGEXP '^[a-zA-Z0-9][a-zA-Z0-9._-]*[a-zA-Z0-9._-]@[a-zA-Z0-9][a-zA-Z0-9._-]*[a-zA-Z0-9].[a-zA-Z]{2,63}$'  
                GROUP BY  res.COD_EMPRESA,res.TIP_RESTRIC";
$rwempresa=mysqli_query($conadmmysql, $empresas);
while ($rsempresa= mysqli_fetch_assoc($rwempresa))
{   
    //verificar o saldo e enviar o email.
        $saldo="SELECT        
                    case when  round(SUM(QTD_SALDO_ATUAL),0) <= ".$rsempresa['DES_GATILHO']." then 'saldo baixo' ELSE 'Saldo OK' END QTD_PRODUTO_saldo,
                    round(SUM(QTD_SALDO_ATUAL),0) QTD_SALDO_ATUAL
                FROM pedido_marka pedido 
                INNER JOIN produto_marka prod ON prod.COD_PRODUTO = pedido.COD_PRODUTO 
                INNER JOIN canal_comunicacao canal ON canal.COD_CANALCOM = prod.COD_CANALCOM 
                INNER JOIN empresas emp ON emp.COD_EMPRESA = pedido.COD_EMPRESA
                            WHERE 
                                    pedido.COD_ORCAMENTO > 0 AND 
                                    pedido.QTD_SALDO_ATUAL> 0 AND
                                    pedido.COD_EMPRESA =".$rsempresa['COD_EMPRESA']." AND
                                    PAG_CONFIRMACAO='S' and
                                    pedido.TIP_LANCAMENTO ='C' 
                            	GROUP BY canal.COD_TPCOM, pedido.TIP_LANCAMENTO	            
                           ORDER BY pedido.TIP_LANCAMENTO desc";
        $rwsaldo=mysqli_fetch_assoc(mysqli_query($conadmmysql, $saldo));
        if($rwsaldo['QTD_PRODUTO_saldo'] != 'Saldo OK')
        {
            $lines = file("https://adm.bunker.mk/templateEmail/template_emailSaldo?id=".fnEncode($rsempresa['COD_EMPRESA']));
            foreach ($lines as $line_num => $line) {
                   $htmle.=$line;
            }
            unset($email['email6']);
           //     $email['email6'] = $rsempresa['DES_EMAILUS'];
           //  $email['email6']='diogo_tank@hotmail.com;ricardoaugusto6693@gmail.com';
              $email['email5'] ='diogo_tank@hotmail.com';


            $retorno = fnsacmail(
                  $email,
                  'Suporte Marka_'.$rsempresa['COD_EMPRESA'],
                  $htmle,
                  "Seu saldo disponivel: ".$rwsaldo['QTD_SALDO_ATUAL'],
                  "Marka Fidelização_".$rsempresa['COD_EMPRESA'],
                  $connAdm->connAdm(),
                  connTemp(7,""),7);
            unset($htmle);
        }
         $retornoarray[]=$rsempresa['COD_EMPRESA'];
    //executar de 4 em 4 horas
}
echo json_encode($retornoarray,true);