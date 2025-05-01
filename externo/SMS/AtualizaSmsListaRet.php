<?php
  include '../../_system/_functionsMain.php'; 
    
       $conadmmysql=$connAdm->connAdm();
       $empresa="select emp.COD_EMPRESA,apar.DES_AUTHKEY,apar.DES_AUTHKEY2,par.COD_PARCOMU from empresas emp
                INNER JOIN senhas_parceiro apar  ON apar.cod_empresa=emp.COD_EMPRESA
                INNER JOIN parceiro_comunicacao par ON par.COD_PARCOMU=apar.COD_PARCOMU
                WHERE emp.log_ativo='S' AND par.COD_TPCOM='2' AND apar.COD_PARCOMU='19' AND apar.LOG_ATIVO='S';";
       $rwempresa=mysqli_query($conadmmysql, $empresa);
while($rsempresa= mysqli_fetch_assoc($rwempresa))
{
    $contemporaria= connTemp($rsempresa['COD_EMPRESA'], '');

    //baixando o log 
    $sqlCapLog="SELECT * FROM log_nuxux WHERE LOG_PROCESSADO='N' and  COD_EMPRESA=".$rsempresa['COD_EMPRESA']." AND TIP_LOG=".$rsempresa[COD_PARCOMU];
    $rwCaplog=mysqli_query($contemporaria, $sqlCapLog);
    while ($rsCaplog= mysqli_fetch_assoc($rwCaplog))
    {
        //aqui vou capturar os status e alterar na base de dados
        $arraydadoscom=json_decode($rsCaplog[LOG_JSON],true);
        foreach ( $arraydadoscom[data][after] as $chave =>$dadoslista )
        {
            $lebal=$dadoslista['status'].'||'.$dadoslista['descricao_detalhe'];
            if($dadoslista[codigo_status]=='04' || 
                $dadoslista[codigo_status]=='06' ||
                $dadoslista[codigo_status]=='10')
             {
                 
                 $sqlbounce="UPDATE sms_lista_ret SET DES_STATUS='".$lebal."', BOUNCE='1',COD_LEITURA='0',COD_CCONFIRMACAO='0',COD_SCONFIRMACAO='0',COD_NRECEBIDO='0' WHERE CHAVE_CLIENTE='".$dadoslista[id]."' AND cod_empresa=$rsempresa[COD_EMPRESA] and cod_campanha=$rsCaplog[COD_CAMPANHA];";
              //   echo $sqlbounce.'<br>';
                 mysqli_query($contemporaria,$sqlbounce);
               
             }
             if($dadoslista[codigo_status]=='03')
             {
                 $sqlCconfirmacao="UPDATE sms_lista_ret SET DES_STATUS='".$lebal."', BOUNCE='0',COD_NRECEBIDO='0',COD_LEITURA='1',COD_CCONFIRMACAO='1',COD_SCONFIRMACAO='0' WHERE CHAVE_CLIENTE='".$dadoslista[id]."' AND cod_empresa=$rsempresa[COD_EMPRESA] and cod_campanha=$rsCaplog[COD_CAMPANHA];";
             //    echo $sqlCconfirmacao.'<br>';
                 mysqli_query($contemporaria,$sqlCconfirmacao);
             }  
             if($dadoslista[codigo_status]=='02')
             {
                 $sqlSconfirmacao="UPDATE sms_lista_ret SET DES_STATUS='".$lebal."', BOUNCE='0',COD_NRECEBIDO='0',COD_LEITURA='1',COD_CCONFIRMACAO='0',COD_SCONFIRMACAO='1' WHERE CHAVE_CLIENTE='".$dadoslista[id]."' AND cod_empresa=$rsempresa[COD_EMPRESA] and cod_campanha=$rsCaplog[COD_CAMPANHA];";
              //   echo $sqlSconfirmacao.'<br>';
                 mysqli_query($contemporaria,$sqlSconfirmacao);
             }  
             
               $verificatoken="SELECT DES_MSG FROM rel_geratoken WHERE COD_EMPRESA=$rsempresa[COD_EMPRESA] AND CHAVE_GERAL='".$dadoslista[id]."'";
               $rwverificatoken=mysqli_query($contemporaria,$verificatoken);
               if($rwverificatoken->num_rows > '0')
               {    
               
                    $date = str_replace('/', '-', $dadoslista[data_atualizacao] );
                    $dt_retorno= date('Y-m-d H:i:s', strtotime($date));
                    $sqlLogtoken="UPDATE rel_geratoken SET DES_MSG='".$lebal."',DAT_CADAST='".$dt_retorno."' WHERE CHAVE_CLIENTE='".$dadoslista[id]."' AND cod_empresa=$rsempresa[COD_EMPRESA];";
                    mysqli_query($contemporaria,$sqlLogtoken);
               }
             //alterar relatorio para ja processado.
             $alterlog="UPDATE log_nuxux SET LOG_PROCESSADO='S' WHERE  ID_LOG=$rsCaplog[ID_LOG] and cod_empresa=$rsempresa[COD_EMPRESA];";
             mysqli_query($contemporaria,$alterlog);
        } 
    }   
}