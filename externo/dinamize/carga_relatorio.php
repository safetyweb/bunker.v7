<?php
require '../../_system/_functionsMain.php';

$conadmin = $connAdm->connAdm();
if ($_GET['EMPRESA'] != '') {
    $GETURL = '1';
    $cod_empresa = $_GET['EMPRESA'];
    $sqlselect = 'and COD_EMPRESA=' . $cod_empresa;
    if ($_GET['DISPARO'] != '') {
        $disporo = 'ret.ID_DISPARO=' . $_GET['DISPARO'] . ' and ';
    }
    if ($_GET['CAMPANHA'] != '') {
        $campanha = 'ret.COD_CAMPANHA=' . $_GET['CAMPANHA'] . ' AND ';
    }
} else {
    $data_filtro = date('Y-m-d', strtotime("-1 days"));
    $dataini = "ret.DAT_CADASTR >= '" . $data_filtro . " 00:00:00' and";
}

$sqlinicio = "SELECT * FROM senhas_parceiro apar
			INNER JOIN parceiro_comunicacao par ON par.COD_PARCOMU=apar.COD_PARCOMU
			WHERE par.COD_TPCOM='1' AND apar.LOG_ATIVO='S' $sqlselect";
$rwempresa = mysqli_query($conadmin, $sqlinicio);
while ($rsempresa = mysqli_fetch_assoc($rwempresa)) {

    $lote = "
            SELECT  
                TIP_GATILHO,                          
                ID_DISPARO,
                COD_CAMPANHA,
                DATA_CADASTRO ,
                sum(COD_OPTOUT_ATIVO) COD_OPTOUT_ATIVO,
                sum(HARD_BOUNCE) HARD_BOUNCE,
                sum(SOFT_BOUNCE) SOFT_BOUNCE,
                sum(CLICK) CLICK,
                sum(ENTREGUE) ENTREGUE,
                sum(COD_LEITURA) COD_LEITURA,
                sum(SPAM) SPAM,				
                sum(HARD_BOUNCE) + sum(SOFT_BOUNCE) FALHA,                 
                sum(ENTREGUE) - sum(COD_LEITURA)  QTD_NLIDOS,
                sum(COD_OPTOUT_ATIVO)+ sum(HARD_BOUNCE) + sum(SOFT_BOUNCE) + sum(ENTREGUE) TOTAL
            
        FROM (
                SELECT g.TIP_GATILHO,
                        ret.ID_DISPARO,
                        ret.COD_CAMPANHA,
                        ret.DAT_CADASTR DATA_CADASTRO,  
                        case when ret.COD_OPTOUT_ATIVO='1' then '1'  ELSE '0' END COD_OPTOUT_ATIVO,
                        case when ret.BOUNCE='1' then '1'  ELSE '0' END HARD_BOUNCE,
                        case when ret.BOUNCE='2' then '1'  ELSE '0' END SOFT_BOUNCE,
                        case when ret.CLICK='1' then '1'  ELSE '0' END CLICK,
                        case when ret.ENTREGUE='1' then '1'  ELSE '0' END ENTREGUE,
                        case when ret.COD_LEITURA='1' then '1'  ELSE '0' END COD_LEITURA,
                        case when ret.SPAM='1' then '1'  ELSE '0' END SPAM
                FROM email_lista_ret ret
                INNER JOIN gatilho_email g ON g.COD_CAMPANHA=ret.COD_CAMPANHA
                WHERE 
                 $campanha
                 $disporo
                ret.COD_EMPRESA=   " . $rsempresa['COD_EMPRESA'] . "                         
            AND DATE(ret.dat_cadastr)>=date(DATE_SUB(NOW(), INTERVAL 3 MONTH))
                                        ) tmpsms
        GROUP BY COD_CAMPANHA,ID_DISPARO; ";
    //  echo $lote . '<br>';
    $rs_lote = mysqli_query(connTemp($rsempresa['COD_EMPRESA'], ''), $lote);
    while ($rwentrega = mysqli_fetch_assoc($rs_lote)) {

        /*      echo 'LOG:<br>';
        echo "COD_CAMPANHA=" . $rwentrega['COD_CAMPANHA'] . '<br>';
        echo "cod_empresa=" . $rsempresa['COD_EMPRESA'] . '<br>';
        echo "COD_DISPARO=" . $rwentrega['ID_DISPARO'] . '<br>';
*/
        //VERIFICAR SE O LOTE EXISTE PARA NÃO INSERIR DUPLICADO 
        $lote = "SELECT * FROM EMAIL_LOTE WHERE  COD_EMPRESA='" . $rsempresa['COD_EMPRESA'] . "' AND COD_CAMPANHA='" . $rwentrega['COD_CAMPANHA'] . "' AND COD_DISPARO_EXT='" . $rwentrega['ID_DISPARO'] . "'";
        $rwlote = mysqli_query(connTemp($rsempresa['COD_EMPRESA'], ''), $lote);
        if ($rwlote->num_rows <= '0') {


            $inslote = "INSERT INTO EMAIL_LOTE(
                                             COD_CAMPANHA,
                                             COD_EMPRESA,						
                                             COD_LOTE,
                                             QTD_LISTA,
                                             NOM_ARQUIVO,
                                             DES_PATHARQ,                                                   
                                             LOG_ENVIO,
                                             COD_USUCADA,
                                             COD_DISPARO_EXT,
                                             DAT_AGENDAMENTO
                                     ) VALUES(
                                             $rwentrega[COD_CAMPANHA],
                                             $rsempresa[COD_EMPRESA],						
                                             0,
                                            " . $rwentrega['TOTAL'] . ",
                                             '" . $rwentrega['TIP_GATILHO'] . "',
                                             '" . $rwentrega['TIP_GATILHO'] . "',                                                    
                                             'S',
                                            '9999',
                                             " . $rwentrega['ID_DISPARO'] . ",
                                             '" . $rwentrega['DATA_CADASTRO'] . "'  
                                     );";
            mysqli_query(connTemp($rsempresa['COD_EMPRESA'], ''), $inslote);
        } else {
            $uplote = "UPDATE sms_lote SET QTD_LISTA='" . $rwentrega['TOTAL'] . "'  WHERE                                 
                                 COD_EMPRESA='" . $rwentrega['COD_EMPRESA'] . "' AND 
                                 COD_CAMPANHA='" . $rwentrega['COD_CAMPANHA'] . "' AND
                                  COD_DISPARO_EXT='" . $rwentrega['ID_DISPARO'] . "'";
            //  echo $uplote.'<br>';
            mysqli_query(connTemp($rsempresa['COD_EMPRESA'], ''), $uplote);
        }

        //verificar se no relatorio ja existe
        $entregaMail = "SELECT * FROM controle_entrega_mail  WHERE 
                COD_CAMPANHA=" . $rwentrega['COD_CAMPANHA'] . " AND 
                cod_empresa=" . $rsempresa['COD_EMPRESA'] . " AND 
                COD_DISPARO='" . $rwentrega['ID_DISPARO'] . "'";
        $rwentrega1 = mysqli_query(connTemp($rsempresa['COD_EMPRESA'], ''), $entregaMail);


        if ($rwentrega1->num_rows <= '0') {

            //inserir registro na base de dados. 
            $lista = "INSERT INTO controle_entrega_mail 
                                    (cod_empresa, 
                                    COD_DISPARO,
                                    cod_campanha_ext,
                                    cod_campanha, 
                                    dat_cadastr, 
                                    dat_envio,
                                    qtd_disparados,
                                    qtd_sucesso,
                                    qtd_falha,
                                    qtd_lidos, 
                                    qtd_nlidos, 
                                    qtd_optout, 
                                    qtd_cliques,
                                    id_templete,
                                    qtd_contatos,
                                    qtd_exclusao,
                                    span,
                                    error_perm,
                                    error_temp
                                    ) 
                                    VALUES 
                                    (" . $rsempresa['COD_EMPRESA'] . ",
                                    '" . $rwentrega['ID_DISPARO'] . "', 
                                    '" . $rwentrega['COD_CAMPANHA'] . "', 
                                    '" . $rwentrega['COD_CAMPANHA'] . "', 
                                    now(), 
                                    now(), 
                                    '" . $rwentrega['TOTAL'] . "', 
                                    '" . $rwentrega['ENTREGUE'] . "', 
                                    '" . $rwentrega['FALHA'] . "',
                                    '" . $rwentrega['COD_LEITURA'] . "', 
                                    '" . $rwentrega['QTD_NLIDOS'] . "',
                                    '" . $rwentrega['COD_OPTOUT_ATIVO'] . "',
                                    '" . $rwentrega['CLICK'] . "',
                                    0,
                                    '" . $rwentrega['TOTAL'] . "',
                                    '0',
                                    '" . $rwentrega['SPAM'] . "',
                                    '" . $rwentrega['HARD_BOUNCE'] . "',
                                    '" . $rwentrega['SOFT_BOUNCE'] . "')";
            // echo $lista . '<br>';
            mysqli_query(connTemp($rsempresa['COD_EMPRESA'], ''), $lista);


            //echo $lista;				
        } else {
            //atualizar registro
            $listaupdate = "UPDATE controle_entrega_mail set
                                    qtd_disparados='" . $rwentrega['TOTAL'] . "', 
                                    qtd_sucesso='" . $rwentrega['ENTREGUE']  . "',
                                    qtd_falha='" . $rwentrega['FALHA'] . "',
                                    qtd_lidos='" .  $rwentrega['COD_LEITURA'] . "',
                                    qtd_nlidos=  '" . $rwentrega['QTD_NLIDOS'] . "',
                                    qtd_optout='" .  $rwentrega['COD_OPTOUT_ATIVO'] . "',
                                    qtd_cliques='" .  $rwentrega['CLICK'] . "',
                                    qtd_contatos='" .  $rwentrega['TOTAL'] . "',
                                    qtd_exclusao='0',
                                    span='" .  $rwentrega['SPAM'] . "',
                                    error_perm='" .  $rwentrega['HARD_BOUNCE'] . "',
                                    error_temp='" .  $rwentrega['SOFT_BOUNCE'] . "'
                                    where cod_empresa=" . $rsempresa['COD_EMPRESA'] . " and COD_DISPARO='" . $rwentrega['ID_DISPARO'] . "'";
            mysqli_query(connTemp($rsempresa['COD_EMPRESA'], ''), $listaupdate);
            //  echo $listaupdate . '<br>';
        }
    }
}
