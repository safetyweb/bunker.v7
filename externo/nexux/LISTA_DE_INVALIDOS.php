<?php

include '../../_system/_functionsMain.php';

exit();
$empresa="select emp.COD_EMPRESA,apar.DES_AUTHKEY,apar.DES_AUTHKEY2,par.COD_PARCOMU from empresas emp
         INNER JOIN senhas_parceiro apar  ON apar.cod_empresa=emp.COD_EMPRESA
         INNER JOIN parceiro_comunicacao par ON par.COD_PARCOMU=apar.COD_PARCOMU
         WHERE emp.log_ativo='S' AND par.COD_TPCOM='2' AND apar.COD_PARCOMU='17' AND apar.LOG_ATIVO='S'";

$rwempresa=mysqli_query($connAdm->connAdm(), $empresa);
while($rsempresa= mysqli_fetch_assoc($rwempresa)){
    ob_start();
    echo 'COD_EMPRESA: '.$rsempresa['COD_EMPRESA'].'<br>';
    $contemporaria= connTemp($rsempresa['COD_EMPRESA'], '');
               
          
          
    

    
                        //optou não pode ser inserido no balcklist
                        
                        //verificar se o cliente ja esta no optout
                        $verificaoptout="SELECT NUM_CELULAR,COD_CLIENTE,DAT_CADASTR FROM sms_lista_ret ret
                                        WHERE 
                                        ret.COD_EMPRESA=$rsempresa[COD_EMPRESA] and 
                                        DATE(ret.dat_cadastr) >= '".date('Y-m-d', strtotime('-3 days'))."'  and 
                                        ret.BOUNCE='1'  AND ret.COD_CCONFIRMACAO=0 and
                                        ret.cod_cliente>0 
                                        AND ret.NUM_CELULAR not IN (SELECT NUM_CELULAR FROM blacklist_sms WHERE NUM_CELULAR!='') 
                                        order by ret.COD_LISTA desc ";
                        
                        $rwclioptout=mysqli_query($contemporaria,$verificaoptout);
                        if($rwclioptout->num_rows > '0')
                        { 
                            echo 'Execucao: SIM <br>';
                            $delistaruim="DELETE FROM blacklist_sms WHERE NUM_CELULAR='' OR NUM_CELULAR IS null";
                            $listaruim=mysqli_query($contemporaria, $delistaruim);
                            mysqli_next_result($contemporaria);
                            
                            while ($rslistaoptou= mysqli_fetch_assoc($rwclioptout))
                            {        
                                $insblacklist="INSERT INTO blacklist_sms (NUM_CELULAR,COD_EMPRESA, COD_CLIENTE,COD_USUCADA,DAT_CADASTR)
                                                                          VALUES 
                                                                         ('$rslistaoptou[NUM_CELULAR]',$rsempresa[COD_EMPRESA],$rslistaoptou[COD_CLIENTE], '999999', '$rslistaoptou[DAT_CADASTR]');";
                                mysqli_query($contemporaria,$insblacklist);
                            }
                        }
                         mysqli_next_result($contemporaria);
                        //removendo da blacklist
                  
                         $verificaremocao="SELECT NUM_CELULAR,COD_CLIENTE,DAT_CADASTR FROM sms_lista_ret ret
                                        WHERE 
                                        ret.COD_EMPRESA=$rsempresa[COD_EMPRESA] AND  DATE(ret.dat_cadastr) >= '".date('Y-m-d', strtotime('-3 days'))."' 
                                         and ret.COD_CCONFIRMACAO=1 
                                        AND ret.NUM_CELULAR IN (SELECT NUM_CELULAR FROM blacklist_sms WHERE NUM_CELULAR!='') 
                                        order by ret.COD_LISTA desc ";
                        $rwverificaremocao=mysqli_query($contemporaria,$verificaremocao);
                        if($rwverificaremocao->num_rows > '0')
                        { 
                            while ($rSverificaremocao=mysqli_fetch_assoc($rwverificaremocao))
                            { 
                                $delistaruim="DELETE FROM blacklist_sms WHERE COD_EMPRESA=$rsempresa[COD_EMPRESA] AND NUM_CELULAR='".$rSverificaremocao[NUM_CELULAR]."'";
                               $listaruim=mysqli_query($contemporaria, $delistaruim);
                                IF(!$listaruim)
                                {
                                  echo 'ERRO DELETE LISTA: '.$delistaruim.'<br>';   
                                }    
                            }
                        }
ob_end_flush();
ob_flush();
flush();
}
?>