<?php
include '../../_system/_functionsMain.php';
$intervalo = 10;

$conadmmysql = $connAdm->connAdm();
$empresa = "select emp.COD_EMPRESA,apar.DES_AUTHKEY,apar.DES_AUTHKEY2,par.COD_PARCOMU from empresas emp
         INNER JOIN senhas_parceiro apar  ON apar.cod_empresa=emp.COD_EMPRESA
         INNER JOIN parceiro_comunicacao par ON par.COD_PARCOMU=apar.COD_PARCOMU
         inner join tab_database t ON t.COD_EMPRESA=emp.COD_EMPRESA
         WHERE emp.log_ativo='S' AND par.COD_TPCOM='2' AND apar.COD_PARCOMU='19' AND apar.LOG_ATIVO='S' $COD_EMPRESAURL;";
$rwempresa = mysqli_query($conadmmysql, $empresa);
while ($rsempresa = mysqli_fetch_assoc($rwempresa)) {
    $contemporaria = connTemp($rsempresa['COD_EMPRESA'], '');
    $limpablk = "DELETE FROM blacklist_sms WHERE WHERE NUM_CELULAR IS NULL;";
    mysqli_query($contemporaria, $limpablk);
    //contadores da lista
    $sqlcontadores = "SELECT   TIP_GATILHO,
                            LOG_TESTE,
                            ID_DISPARO,
                            COD_CAMPANHA,
                            DATA_CADASTRO ,
                            sum(COD_OPTOUT_ATIVO) COD_OPTOUT_ATIVO,
                            sum(BOUNCE) BOUNCE,
                            sum(COD_NRECEBIDO) COD_NRECEBIDO,
                            sum(COD_CCONFIRMACAO) COD_CCONFIRMACAO,
                            SUM(COD_SCONFIRMACAO) COD_SCONFIRMACAO, 
                            sum(COD_OPTOUT_ATIVO)+ sum(BOUNCE) + sum(COD_NRECEBIDO)+ sum(COD_CCONFIRMACAO) + SUM(COD_SCONFIRMACAO) TOTAL,
                            sum(SUB_TOTAL)   SUB_TOTAL

                    FROM (
                            SELECT g.TIP_GATILHO,
                                           ret.LOG_TESTE,
                                            ret.ID_DISPARO,
                                            ret.COD_CAMPANHA,
                                            date(ret.DAT_CADASTR) DATA_CADASTRO ,
                                            case when ret.COD_OPTOUT_ATIVO='1' then '1'  ELSE '0' END COD_OPTOUT_ATIVO,
                                            case when ret.BOUNCE='1' then '1'  ELSE '0' END BOUNCE,
                                            case when ret.COD_NRECEBIDO='1' then '1'  ELSE '0' END COD_NRECEBIDO,
                                            case when ret.COD_CCONFIRMACAO='1' then '1'  ELSE '0' END COD_CCONFIRMACAO,
                                            CASE WHEN ret.COD_SCONFIRMACAO='1' THEN '1' ELSE '0' END COD_SCONFIRMACAO,
                                            case when ret.COD_CCONFIRMACAO='0' then '1'
                                                 when  ret.COD_SCONFIRMACAO='0' then '1'
                                                 when ret.COD_NRECEBIDO='0' then '1'																	    
                                                 when ret.BOUNCE='0' then '1'																	  
                                                 when ret.COD_OPTOUT_ATIVO='0' then '1'
                                                 ELSE '1' END SUB_TOTAL

                            FROM sms_lista_ret ret
                            INNER JOIN gatilho_sms g ON g.COD_CAMPANHA=ret.COD_CAMPANHA
                            WHERE 
                             ret.CHAVE_CLIENTE is not NULL and 
                            ret.COD_EMPRESA=" . $rsempresa['COD_EMPRESA'] . " AND 
                            DATE(ret.dat_cadastr)>=date(DATE_SUB(NOW(), INTERVAL $intervalo DAY))
                                                    ) tmpsms
                   GROUP BY LOG_TESTE, COD_CAMPANHA,DATE(DATA_CADASTRO);";
    $rwcontadores = mysqli_query($contemporaria, $sqlcontadores);
    while ($rscontadore = mysqli_fetch_assoc($rwcontadores)) {
        if ($rwcontadores->num_rows > '0') {
            $total = $rscontadore[SUB_TOTAL];

            //VERIFICAR SE O LOTE EXISTE PARA NÃO INSERIR DUPLICADO 
            $lote = "SELECT * FROM sms_lote WHERE LOG_TESTE='" . $rscontadore[LOG_TESTE] . "' and COD_EMPRESA='" . $rsempresa['COD_EMPRESA'] . "' AND COD_CAMPANHA='" . $rscontadore[COD_CAMPANHA] . "' AND COD_DISPARO_EXT='" . $rscontadore[ID_DISPARO] . "'";
            $rwlote = mysqli_query($contemporaria, $lote);
            $row = mysqli_fetch_assoc($rwlote);
            if ($rwlote->num_rows > '0') {
                $uplote = "UPDATE sms_lote SET QTD_LISTA='" . $total . "'  WHERE LOG_TESTE='$rscontadore[LOG_TESTE]' and COD_EMPRESA='" . $rsempresa['COD_EMPRESA'] . "' AND COD_CAMPANHA='" . $rscontadore[COD_CAMPANHA] . "' AND COD_DISPARO_EXT='" . $rscontadore[ID_DISPARO] . "'";
                mysqli_query($contemporaria, $uplote);
            } else {
                $inslote = "INSERT INTO SMS_LOTE(
                                     COD_CAMPANHA,
                                     COD_EMPRESA,						
                                     COD_LOTE,
                                     QTD_LISTA,
                                     NOM_ARQUIVO,
                                     DES_PATHARQ,                                                    
                                     LOG_ENVIO,
                                     COD_USUCADA,
                                     COD_DISPARO_EXT,
                                     DAT_AGENDAMENTO,
                                     LOG_TESTE
                             ) VALUES(
                                     $rscontadore[COD_CAMPANHA],
                                     $rsempresa[COD_EMPRESA],						
                                     0,
                                      $total,
                                     '$rscontadore[TIP_GATILHO]',
                                     '$rscontadore[TIP_GATILHO]',                                                     
                                     'S',
                                     0,
                                     $rscontadore[ID_DISPARO],
                                     '" . $rscontadore[DATA_CADASTRO] . "',
                                     '" . $rscontadore[LOG_TESTE] . "'    
                             );";
                echo $inslote . '<br>';
                mysqli_query($contemporaria, $inslote);
            }

            //inserir contadores do relatorio
            $entregasms = "SELECT * FROM controle_entrega_sms WHERE log_teste='$rscontadore[LOG_TESTE]' and COD_EMPRESA='" . $rsempresa['COD_EMPRESA'] . "' AND COD_CAMPANHA='" . $rscontadore[COD_CAMPANHA] . "' AND COD_DISPARO='" . $rscontadore[ID_DISPARO] . "'";
            $rwentregasms = mysqli_query($contemporaria, $entregasms);
            if ($rwentregasms->num_rows > '0') {
                $updateentrega = "UPDATE controle_entrega_sms 
                                        SET  qtd_disparados=$total, 
                                             qtd_sucesso=$rscontadore[COD_CCONFIRMACAO] + $rscontadore[COD_SCONFIRMACAO], 
                                             qtd_falha= $rscontadore[BOUNCE],
                                             QTD_AGUARADANDO='0',
                                             QTD_CCONFIRMACAO=$rscontadore[COD_CCONFIRMACAO],
                                             QTD_SCONFIRMACAO=$rscontadore[COD_SCONFIRMACAO],    
                                             QTD_NRECEBIDO=$rscontadore[COD_NRECEBIDO],
                                             QTD_OPTOUT=$rscontadore[COD_OPTOUT_ATIVO],
                                             log_teste='$rscontadore[LOG_TESTE]'
                                    WHERE log_teste='$rscontadore[LOG_TESTE]' and COD_EMPRESA='" . $rsempresa['COD_EMPRESA'] . "' AND COD_CAMPANHA='" . $rscontadore[COD_CAMPANHA] . "' AND COD_DISPARO='" . $rscontadore[ID_DISPARO] . "';";
                mysqli_query($contemporaria, $updateentrega);
            } else {
                $entregue = "INSERT INTO controle_entrega_sms (cod_empresa, 
                                                                     cod_campanha_ext,                                                                                
                                                                     cod_campanha, 
                                                                     dat_cadastr, 
                                                                     cod_disparo, 
                                                                     dat_envio,
                                                                     qtd_disparados, 
                                                                     qtd_sucesso, 
                                                                     qtd_falha,                                                                         
                                                                     QTD_CCONFIRMACAO,
                                                                     QTD_SCONFIRMACAO,
                                                                     QTD_NRECEBIDO,
                                                                     QTD_OPTOUT,
                                                                     QTD_AGUARADANDO,
                                                                     log_teste) 
                                                     VALUES 
                                                                     ($rsempresa[COD_EMPRESA], 
                                                                     '$rscontadore[ID_DISPARO]',                                                                                
                                                                     '$rscontadore[COD_CAMPANHA]', 
                                                                     '$rscontadore[DATA_CADASTRO]', 
                                                                     '$rscontadore[ID_DISPARO]', 
                                                                     '$rscontadore[DATA_CADASTRO]',
                                                                     $total, 
                                                                     $rscontadore[COD_CCONFIRMACAO], 
                                                                     $rscontadore[BOUNCE],                                                                                
                                                                     $rscontadore[COD_SCONFIRMACAO],
                                                                     0,
                                                                     $rscontadore[COD_NRECEBIDO],
                                                                     $rscontadore[COD_OPTOUT_ATIVO],
                                                                     '0',
                                                                     '$rscontadore[LOG_TESTE]'
                                                                     );";
                mysqli_query($contemporaria, $entregue);
                echo $entregue . '<br>';
            }
        }
        echo '<br>EXECUTADO EM TODAS AS EMPRESAS<br>';
    }
}
