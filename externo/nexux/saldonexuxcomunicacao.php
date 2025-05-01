<?php
include '../../_system/_functionsMain.php';
fnDebug('TRUE');
// AND apar.cod_empresa=219
$stringname='TOKEN';
$conadmmysql=$connAdm->connAdm();
$sqlempresa = "SELECT * FROM senhas_parceiro apar
				INNER JOIN parceiro_comunicacao par ON par.COD_PARCOMU=apar.COD_PARCOMU
				INNER JOIN empresas emp ON emp.COD_EMPRESA=apar.COD_EMPRESA
				WHERE par.COD_TPCOM='2' AND apar.COD_PARCOMU='17' AND apar.LOG_ATIVO='S'";
$rwempresa = mysqli_query($conadmmysql, $sqlempresa);
while ($rsempresa = mysqli_fetch_assoc($rwempresa)){

   
    if($rsempresa[DES_AUTHKEY]!='')
    {    
        $responsetk=file_get_contents('https://sms.nexuscomunicacao.com/api/sms/saldo.aspx?chave='.$rsempresa[DES_AUTHKEY]);
        $arraytk= json_decode($responsetk,true);
    }
   sleep(2);
   if($rsempresa[DES_AUTHKEY2]!='')
    {
        $responsenormal=file_get_contents('https://sms.nexuscomunicacao.com/api/sms/saldo.aspx?chave='.$rsempresa[DES_AUTHKEY2]);
        $arrayMo= json_decode($responsetk,true);
    }
   $Array[$rsempresa[COD_EMPRESA]][]=array('tk'=>$arraytk[SaldoComLimite],
                                           'Mo'=>$arrayMo[SaldoComLimite],
                                            'Nome_empresa'=>$rsempresa[NOM_FANTASI]
                                            );
}
echo '<pre>';
print_r($Array);
echo '</pre>';
?>