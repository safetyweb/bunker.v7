<?php
require '../../_system/_functionsMain.php';
date_default_timezone_set('Etc/GMT+3');
echo 'INICIO...'.date('H:i:s').'<br>';

// SMS
$sqlinicio = "SELECT * FROM senhas_parceiro apar
              INNER JOIN parceiro_comunicacao par ON par.COD_PARCOMU=apar.COD_PARCOMU
              INNER JOIN empresas emp ON emp.COD_EMPRESA = apar.COD_EMPRESA  and emp.LOG_ATIVO='S'
              WHERE par.COD_TPCOM='2' AND apar.LOG_ATIVO='S'";

$rwcomunicacao = mysqli_query($connAdm->connAdm(), $sqlinicio); 
while ($rscomunicacao = mysqli_fetch_assoc($rwcomunicacao)) { 
     $conntmp=connTemp($rscomunicacao['COD_EMPRESA'], '');
     
    /* GATILHO */
    $gatilhos = array("individual", "cadastro", "resgate", "venda", "aniv", "anivSem", "anivQuinz", "anivMes", "anivDia", "anivCad", "credExp", "inativos", "credVen");
    $gatilhos_impl_in = "'".implode("','", $gatilhos)."'";
    $sqlgatilho = "SELECT gt.COD_CAMPANHA, gt.COD_EMPRESA, CONCAT(cp.DAT_FIM, ' ', cp.HOR_FIM) DATA_FINAL, gt.LOG_STATUS, cp.LOG_ATIVO, cp.LOG_CONTINU
                   FROM gatilho_sms gt
                   INNER JOIN campanha cp ON gt.COD_CAMPANHA=cp.COD_CAMPANHA 
                   INNER JOIN sms_parametros p ON p.COD_EMPRESA=gt.cod_empresa AND p.COD_CAMPANHA=gt.cod_campanha AND COD_LISTA IN (SELECT MAX(COD_LISTA) FROM sms_parametros WHERE COD_EMPRESA=gt.cod_empresa AND COD_CAMPANHA=gt.cod_campanha)
                  
                   WHERE gt.TIP_GATILHO IN ($gatilhos_impl_in) AND gt.LOG_STATUS='S' AND cp.LOG_ATIVO='S' AND gt.cod_empresa=".$rscomunicacao['COD_EMPRESA']."  AND cp.COD_CAMPANHA IN (SELECT COD_CAMPANHA FROM email_fila f WHERE f.COD_EMPRESA=".$rscomunicacao['COD_EMPRESA']." AND  f.COD_ENVIADO='N' AND f.DAT_BLK  IS null GROUP BY f.COD_CAMPANHA)";
    $rwgatilho = mysqli_query(connTemp($rscomunicacao['COD_EMPRESA'], ''), $sqlgatilho);

    if ($rwgatilho->num_rows > 0) {       
        while ($rsgatilho = mysqli_fetch_assoc($rwgatilho)) {
            ob_start();
            $arraycamp[] = $rsgatilho['COD_CAMPANHA'];

            // Verificar se a campanha é contínua ou está na validade
         //   if ($rsgatilho['LOG_CONTINU'] == 'N') {
                if ( date('Y-m-d H:i:s') > $rsgatilho['DATA_FINAL']) {
                     
                    if ($rsgatilho['LOG_ATIVO'] == 'S' || $rsgatilho['LOG_STATUS'] == 'S') {
                         $ipdatecampanha = "UPDATE campanha SET LOG_ATIVO='N' WHERE cod_empresa=$rscomunicacao[COD_EMPRESA] AND cod_campanha=$rsgatilho[COD_CAMPANHA];";
                         $camp = mysqli_query(connTemp($rscomunicacao['COD_EMPRESA'], ''), $ipdatecampanha);
                         if (!$camp) {
                             echo $ipdatecampanha.'<br>';
                         }    
                         $updateG = "UPDATE gatilho_sms SET LOG_STATUS='N' WHERE cod_empresa=$rscomunicacao[COD_EMPRESA] AND cod_campanha=$rsgatilho[COD_CAMPANHA];";
                         $gat = mysqli_query(connTemp($rscomunicacao['COD_EMPRESA'], ''), $updateG);
                         if (!$gat) {
                             echo $updateG.'<br>';
                         }
                         // Deletar a fila
                         $deletarF = "DELETE FROM email_fila WHERE COD_CAMPANHA=$rsgatilho[COD_CAMPANHA] AND COD_EMPRESA=$rscomunicacao[COD_EMPRESA]";
                         mysqli_query(connTemp($rscomunicacao['COD_EMPRESA'], ''), $deletarF);  
                         echo '<br>fora da validade: '.$rsgatilho['DATA_FINAL'];
                    }
                }
            // }   

            // Removendo caracteres especiais
            $delcelvazio = "DELETE FROM email_fila WHERE NUM_CELULAR='' AND COD_CAMPANHA=".$rsgatilho['COD_CAMPANHA']." AND COD_EMPRESA=".$rscomunicacao['COD_EMPRESA'];
            mysqli_query(connTemp($rscomunicacao['COD_EMPRESA'], ''), $delcelvazio);

            $batch_size = 5000;
            $offset = 0;
            $max_iterations = 100; // Defina um limite máximo de iterações
            $current_iteration = 0;

            do {
                // Consulta SQL para selecionar registros
               /* $listapapelhigienico = "SELECT * FROM email_fila 
                                        WHERE COD_CAMPANHA=" . $rsgatilho['COD_CAMPANHA'] . " AND
                                        COD_ENVIADO='N' AND
                                        COD_EMPRESA=" . $rscomunicacao['COD_EMPRESA'] . " AND
                                        SCAN_OPOUT_SMS='N' AND 
                                        LOG_BLACKLIST_SMS='N' AND 
                                        DATE(DT_CADASTR) = CURDATE()
                                        LIMIT $batch_size OFFSET $offset";*/
               $listapapelhigienico = " SELECT * 
                                                    FROM email_fila 
                                                    WHERE COD_CAMPANHA = " . $rsgatilho['COD_CAMPANHA'] . " 
                                                      AND COD_ENVIADO = 'N' 
                                                      AND COD_EMPRESA = " . $rscomunicacao['COD_EMPRESA'] . " 
                                                      AND DATE(DT_CADASTR) = CURDATE()
                                                    LIMIT $batch_size OFFSET $offset";
/*
 *  AND (
                                                          CASE 
                                                              WHEN SCAN_OPOUT_SMS = 'N' THEN LOG_BLACKLIST_SMS = 'N'
                                                              ELSE LOG_BLACKLIST_SMS = 'S'
                                                          END
                                                      
 */
                $rwlistapapelhigienico = mysqli_query(connTemp($rscomunicacao['COD_EMPRESA'], ''), $listapapelhigienico);
                $num_rows = mysqli_num_rows($rwlistapapelhigienico);

                // Depuração: exibir o valor de $num_rows e $offset
                echo "EMPRESA: ".$rscomunicacao['COD_EMPRESA']. " Offset: $offset, Num Rows: $num_rows<br>";

                // Coletar dados para inserção/substituição
                $values = [];
                $columns = [];

                while ($rshigienico = mysqli_fetch_assoc($rwlistapapelhigienico)) {
                    if (empty($columns)) {
                        // Obter os nomes das colunas na primeira iteração
                        $columns = array_keys($rshigienico);
                    }

                    // Sanitizar e formatar os valores dos dados
                    $rshigienico['NUM_CELULAR'] = fnlimpacelular($rshigienico['NUM_CELULAR']);
                    $rshigienico['COD_CAMPANHA'] = $rsgatilho['COD_CAMPANHA'];
                    $rshigienico['COD_EMPRESA'] = $rscomunicacao['COD_EMPRESA'];
                    $rshigienico['COD_ENVIADO'] = 'N';
                    $rshigienico['SCAN_OPOUT_SMS'] = 'N';
                    $rshigienico['LOG_BLACKLIST_SMS'] = 'N';

                    /*$row = array_map(function($value) use ($rscomunicacao) {
                        if (is_string($value) && $value !== '') {
                            return "'" . mysqli_real_escape_string(connTemp($rscomunicacao['COD_EMPRESA'], ''), $value) . "'";
                        } elseif (is_numeric($value)) {
                            return $value;
                        } elseif (is_null($value) || $value === '') {
                            return "NULL";
                        } else {
                            return "'" . mysqli_real_escape_string(connTemp($rscomunicacao['COD_EMPRESA'], ''), $value) . "'";
                        }
                    }, $rshigienico);*/
                        $row = array_map(function($value) use ($conntmp) {
                            if (is_string($value) && $value !== '') {
                                return "'" . mysqli_real_escape_string($conntmp, $value) . "'";
                            } elseif (is_numeric($value)) {
                                return $value;
                            } elseif (is_null($value) || $value === '') {
                                return "NULL";
                            } else {
                                return "'" . mysqli_real_escape_string($conntmp, $value) . "'";
                            }
                        }, $rshigienico); 
                    $values[] = "(" . implode(", ", $row) . ")";
                }

               /* if (!empty($values)) {
                    $sql = "REPLACE INTO email_fila (" . implode(", ", $columns) . ") VALUES " . implode(", ", $values) . ';';
                    $result = mysqli_query(connTemp($rscomunicacao['COD_EMPRESA'], ''), $sql);
                    if (!$result) {
                        echo '<br>'. $sql .'<br>';
                        echo '<pre>'.print_r(mysqli_error(connTemp($rscomunicacao['COD_EMPRESA'], '')), true).'</pre>';
                    }else{
                       // echo 'OK: '.$sql.'<br>';
                    }
                }*/
                if (!empty($values)) {
                    foreach ($values as $row) {
                        $data = array_combine($columns, explode(", ", trim($row, "()")));
                        
                        // Remover ID_FILA do conjunto de dados para não ser atualizado
                        $id_fila = $data['ID_FILA'];
                        unset($data['ID_FILA']);
                        
                        // Construir a parte do UPDATE para os campos que devem ser atualizados
                        $updates = [];
                        foreach ($data as $column => $value) {
                            if ($column == 'NUM_CELULAR' || $column == 'SCAN_OPOUT_SMS' || $column == 'LOG_BLACKLIST_SMS') {
                                $updates[] = "$column = $value";
                            }
                        }
                        $update_sql = implode(", ", $updates);

                        // Construir o SQL de UPDATE
                        $sql = "UPDATE email_fila SET $update_sql 
                                WHERE COD_EMPRESA = {$data['COD_EMPRESA']} 
                                 AND ID_FILA = $id_fila ";

                        // Executar a consulta
                        $result = mysqli_query(connTemp($rscomunicacao['COD_EMPRESA'], ''), $sql);

                        if (!$result) {
                            echo '<br>'. $sql .'<br>';
                            echo '<pre>'.print_r(mysqli_error(connTemp($rscomunicacao['COD_EMPRESA'], '')), true).'</pre>';
                        } else {
                            // echo 'OK: '.$sql.'<br>';
                        }
                    }
                }

                // Atualiza o offset para o próximo lote
                $offset += $batch_size;

                // Incrementa o contador de iterações
                $current_iteration++;

                // Condição de saída: verifica se o número máximo de iterações foi atingido
                if ($current_iteration >= $max_iterations) {
                    echo "Número máximo de iterações atingido. Saindo do loop.<br>";
                    break;
                }
            } while ($num_rows > 0);

            echo "Processamento concluído.";

            // Excluir registros com base na condição de junção
            $campar = "'".implode("','", $arraycamp)."'";
            $delete_sql = "DELETE email_fila
                           FROM email_fila
                           INNER JOIN clientes ON email_fila.COD_CLIENTE = clientes.COD_CLIENTE
                           WHERE email_fila.COD_CAMPANHA = ".$rsgatilho['COD_CAMPANHA']." AND email_fila.COD_EMPRESA=".$rscomunicacao['COD_EMPRESA']." AND clientes.LOG_SMS='N' AND  email_fila.cod_enviado='N' AND email_fila.log_blacklist_sms='N'";
            $delete_result = mysqli_query(connTemp($rscomunicacao['COD_EMPRESA'], ''), $delete_sql);

            if (!$delete_result) {
                echo '<br>'. $delete_sql .'<br>';
            }

            // Atualizar registros na blacklist
            $sqltemblacklist = "
                UPDATE email_fila ef
                JOIN blacklist_SMS bl ON ef.NUM_CELULAR = bl.NUM_CELULAR AND ef.COD_EMPRESA = bl.COD_EMPRESA
                SET ef.LOG_BLACKLIST_SMS = 'N', ef.SCAN_OPOUT_SMS = 'S', ef.DAT_BLK = NOW()
                WHERE ef.COD_CAMPANHA = ".$rsgatilho['COD_CAMPANHA']." AND ef.COD_ENVIADO='N' AND ef.COD_EMPRESA=".$rscomunicacao['COD_EMPRESA']." AND ef.SCAN_OPOUT_SMS='N'";
            $rw3 = mysqli_query(connTemp($rscomunicacao['COD_EMPRESA'], ''), $sqltemblacklist);
            if (!$rw3) {
                echo '<br>'. $sqltemblacklist .'<br>';
                echo '<pre>'.print_r($rw3).'</pre>';
            }

            $sqltemblacklistn = "
                UPDATE email_fila ef
                LEFT JOIN blacklist_SMS bl ON ef.NUM_CELULAR = bl.NUM_CELULAR AND ef.COD_EMPRESA = bl.COD_EMPRESA
                SET ef.LOG_BLACKLIST_SMS = 'S', ef.SCAN_OPOUT_SMS = 'S', ef.DAT_BLK = NOW()
                WHERE ef.COD_CAMPANHA = ".$rsgatilho['COD_CAMPANHA']." AND ef.COD_ENVIADO='N' AND ef.COD_EMPRESA=".$rscomunicacao['COD_EMPRESA']." AND ef.LOG_BLACKLIST_SMS='N' AND bl.NUM_CELULAR IS NULL";
            $rw4 = mysqli_query(connTemp($rscomunicacao['COD_EMPRESA'], ''), $sqltemblacklistn);
            if (!$rw4) {
                echo '<br>'. $sqltemblacklistn .'<br>';
                echo '<pre>'.print_r($rw4).'</pre>';
            }

            $delcelvazio = "DELETE FROM email_fila WHERE NUM_CELULAR='' AND COD_CAMPANHA = ".$rsgatilho['COD_CAMPANHA']." AND COD_EMPRESA=".$rscomunicacao['COD_EMPRESA'];
            mysqli_query(connTemp($rscomunicacao['COD_EMPRESA'], ''), $delcelvazio);
            unset($arraycamp);
            ob_end_flush();
            ob_flush();
            flush();
        }
    }
}
echo '<br>FIM...'.date('H:i:s');
?>
