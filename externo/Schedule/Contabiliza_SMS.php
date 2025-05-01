<?php
require '../../_system/_functionsMain.php';

$empresa = "select * from empresas emp
         INNER JOIN senhas_parceiro apar  ON apar.cod_empresa=emp.COD_EMPRESA
         INNER JOIN parceiro_comunicacao par ON par.COD_PARCOMU=apar.COD_PARCOMU
         WHERE emp.log_ativo='S' AND par.COD_TPCOM='2' AND apar.LOG_ATIVO='S'";

$rwempresa = mysqli_query($connAdm->connAdm(), $empresa);
while ($rsempresa = mysqli_fetch_assoc($rwempresa)) {
  $contemporaria = connTemp($rsempresa['COD_EMPRESA'], '');

  $semchave = "UPDATE sms_lista_ret SET BOUNCE=1 WHERE  idContatosMailing in ('22','23','24') AND cod_empresa=" . $rsempresa['COD_EMPRESA'] . " AND CHAVE_CLIENTE=''";
  $rwcontadores = mysqli_query($contemporaria, $semchave);
  // iniciar os contadores para atualização dos relatorios contabeis

  //contadores da lista
  $sqlcontadores = "SELECT   TIP_GATILHO,
                             LOG_TESTE,
                             ID_DISPARO,
                             COD_CAMPANHA,
                             DATA_CADASTRO ,
                             sum(COD_OPTOUT_ATIVO) COD_OPTOUT_ATIVO,
                             sum(BOUNCE) BOUNCE,
                             sum(CANCELADO) CANCELADO,
                             sum(COD_NRECEBIDO) + ABS((sum(SUB_TOTAL)- sum(CANCELADO)) - (sum(COD_OPTOUT_ATIVO)+ sum(BOUNCE) + sum(COD_NRECEBIDO)+ sum(COD_CCONFIRMACAO))) COD_NRECEBIDO,
                             sum(COD_CCONFIRMACAO) COD_CCONFIRMACAO,
                             sum(COD_OPTOUT_ATIVO)+ sum(BOUNCE) + sum(COD_NRECEBIDO)+ sum(COD_CCONFIRMACAO) TOTAL,
                             sum(SUB_TOTAL)- sum(CANCELADO)   SUB_TOTAL

                     FROM (
                             SELECT g.TIP_GATILHO,
                                     ret.LOG_TESTE,
                                     ret.ID_DISPARO,
                                     ret.COD_CAMPANHA,
                                     date(ret.DAT_CADASTR) DATA_CADASTRO ,
                                     case when ret.COD_OPTOUT_ATIVO='1' then '1'  ELSE '0' END COD_OPTOUT_ATIVO,
                                     case when ret.BOUNCE='1' then '1'  ELSE '0' END BOUNCE,
                                     case when ret.BOUNCE='2' then '1'  ELSE '0' END CANCELADO,
                                     case when ret.COD_NRECEBIDO='1' then '1'  ELSE '0' END COD_NRECEBIDO,
                                     case when ret.COD_CCONFIRMACAO='1' then '1'  ELSE '0' END COD_CCONFIRMACAO,
                                     case when ret.COD_CCONFIRMACAO='0' then '1'																	   
                                          when ret.COD_NRECEBIDO='0' then '1'																	    
                                          when ret.BOUNCE='0' then '1'																	  
                                          when ret.COD_OPTOUT_ATIVO='0' then '1'
                                          ELSE '1' END SUB_TOTAL

                             FROM sms_lista_ret ret
                             INNER JOIN gatilho_sms g ON g.COD_CAMPANHA=ret.COD_CAMPANHA
                             WHERE 
                             ret.CHAVE_CLIENTE is not NULL and 
                             ret.COD_EMPRESA=" . $rsempresa['COD_EMPRESA'] . " AND 
                             DATE(ret.dat_cadastr)>=date(DATE_SUB(NOW(), INTERVAL 120 DAY))
                                                     ) tmpsms
                    GROUP BY LOG_TESTE, COD_CAMPANHA,ID_DISPARO;";

  $rwcontadores = mysqli_query($contemporaria, $sqlcontadores);
  while ($rscontadore = mysqli_fetch_assoc($rwcontadores)) {


    if (!strstr($rsbuscaatualizacao['TIP_GATILHO'], 'token')) {

      $tipoenvio = true;
    }

    if ($rwcontadores->num_rows > '0') {
      $total = $rscontadore['SUB_TOTAL'];

      //VERIFICAR SE O LOTE EXISTE PARA NÃO INSERIR DUPLICADO 
      $lote = "SELECT * FROM sms_lote WHERE LOG_TESTE='" . $rscontadore['LOG_TESTE'] . "' and COD_EMPRESA='" . $rsempresa['COD_EMPRESA'] . "' AND COD_CAMPANHA='" . $rscontadore['COD_CAMPANHA'] . "' AND COD_DISPARO_EXT='" . $rscontadore['ID_DISPARO'] . "'";
      //   echo $lote.'<br>';
      $rwlote = mysqli_query($contemporaria, $lote);
      if ($rwlote->num_rows <= '0') {

        if (!$tipoenvio) {
          $stringname = $rsbuscaatualizacao['TIP_GATILHO'];
        }


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
                                             '$stringname',
                                             '$stringname',                                                    
                                             'S',
                                             0,
                                             $rscontadore[ID_DISPARO],
                                             '" . $rscontadore['DATA_CADASTRO'] . "',
                                             '" . $rscontadore['LOG_TESTE'] . "'    
                                     );";
        // echo $inslote.'<br>';    
        mysqli_query(connTemp($rsempresa['COD_EMPRESA'], ''), $inslote);
      }


      while ($row = mysqli_fetch_assoc($rwlote)) {

        if ($rwlote->num_rows > '0') {
          $uplote = "UPDATE sms_lote SET QTD_LISTA='" . $total . "'  WHERE LOG_TESTE='$rscontadore[LOG_TESTE]' and COD_EMPRESA='" . $rsempresa['COD_EMPRESA'] . "' AND COD_CAMPANHA='" . $rscontadore['COD_CAMPANHA'] . "' AND COD_DISPARO_EXT='" . $rscontadore['ID_DISPARO'] . "'";
          //  echo $uplote.'<br>';
          mysqli_query($contemporaria, $uplote);
        } else {
          if (!$tipoenvio) {
            $stringname = 'venda';
          }


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
                                             '$stringname',
                                             '$stringname',                                                     
                                             'S',
                                             0,
                                             $rscontadore[ID_DISPARO],
                                             '" . $rscontadore['DATA_CADASTRO'] . "',
                                             '" . $rscontadore['LOG_TESTE'] . "'    
                                     );";
          // echo $inslote.'<br>';    
          mysqli_query($contemporaria, $inslote);
        }
      }
      //inserir contadores do relatorio
      $entregasms = "SELECT * FROM controle_entrega_sms WHERE log_teste='$rscontadore[LOG_TESTE]' and COD_EMPRESA='" . $rsempresa['COD_EMPRESA'] . "' AND COD_CAMPANHA='" . $rscontadore['COD_CAMPANHA'] . "' AND COD_DISPARO='" . $rscontadore['ID_DISPARO'] . "'";
      $rwentregasms = mysqli_query($contemporaria, $entregasms);

      //   echo $entregasms.'<br>';
      if ($rwentregasms->num_rows > '0') {
        $updateentrega = "UPDATE controle_entrega_sms 
                                        SET  qtd_disparados=$total, 
                                             qtd_sucesso=$rscontadore[COD_CCONFIRMACAO], 
                                             qtd_falha= $rscontadore[BOUNCE],
                                             QTD_AGUARADANDO='0',
                                             CANCELADO=$rscontadore[CANCELADO],
                                             QTD_CCONFIRMACAO=$rscontadore[COD_CCONFIRMACAO],
                                             QTD_NRECEBIDO=$rscontadore[COD_NRECEBIDO],
                                             QTD_OPTOUT=$rscontadore[COD_OPTOUT_ATIVO],
                                             log_teste='$rscontadore[LOG_TESTE]'
                                    WHERE log_teste='$rscontadore[LOG_TESTE]' and COD_EMPRESA='" . $rsempresa['COD_EMPRESA'] . "' AND COD_CAMPANHA='" . $rscontadore['COD_CAMPANHA'] . "' AND COD_DISPARO='" . $rscontadore['ID_DISPARO'] . "';";
        mysqli_query($contemporaria, $updateentrega);
        //  echo $updateentrega.'<br>';
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
                                                                        log_teste,
                                                                        CANCELADO) 
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
                                                                        $rscontadore[COD_CCONFIRMACAO],
                                                                        0,
                                                                        $rscontadore[COD_NRECEBIDO],
                                                                        $rscontadore[COD_OPTOUT_ATIVO],
                                                                        '0',
                                                                        '$rscontadore[LOG_TESTE]',
                                                                        $rscontadore[CANCELADO]    
                                                                        );";
        $insertsms = mysqli_query($contemporaria, $entregue);
        if (!$insertsms) {
          $file = './aquivosX/error_insert' . date('YmdHis') . '.txt';
          //  file_put_contents($file, $entregue);
        } else {
          $file = './aquivosX/OK_insert' . date('YmdHis') . '.txt';
          //   file_put_contents($file, $entregue);
        }
        //echo $entregue.'<br>';
      }
    }
  }
}
echo  'contabilização concluida';
