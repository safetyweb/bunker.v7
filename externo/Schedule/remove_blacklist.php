<?php
require_once '../../_system/_functionsMain.php';
date_default_timezone_set('Etc/GMT+3');
echo 'INICIO...' . date('H:i:s') . '<br>';
$conadmin = $connAdm->connAdm();


//push
//if($_GET['ID']=='1')
//{

$sqliniciopush = "SELECT * FROM senhas_parceiro apar
			INNER JOIN parceiro_comunicacao par ON par.COD_PARCOMU=apar.COD_PARCOMU
                        INNER JOIN empresas emp ON emp.COD_EMPRESA = apar.COD_EMPRESA  and emp.LOG_ATIVO='S'
			WHERE  par.COD_TPCOM='5' AND apar.LOG_ATIVO='S'";
$rwcomunicacaopush = mysqli_query($conadmin, $sqliniciopush);
while ($rscomunicacaopush = mysqli_fetch_assoc($rwcomunicacaopush)) {
    $contemporaria = connTemp($rscomunicacaopush['COD_EMPRESA'], '');
    $gatilhos = array("cadPush", "credPush", "resgPush"); // <-- Colocar na ordem do select
    $gatilhos_impl_in = "'" . (implode("','", $gatilhos)) . "'";
    $sqlgatilho = "SELECT GROUP_CONCAT( DISTINCT gt.COD_CAMPANHA SEPARATOR ',') COD_CAMPANHA,gt.COD_EMPRESA,cp.DAT_FIM,cp.HOR_FIM  FROM gatilho_PUSH gt
                    INNER JOIN campanha cp ON gt.COD_CAMPANHA=cp.COD_CAMPANHA 
                    INNER JOIN PUSH_parametros  p ON p.COD_EMPRESA=gt.cod_empresa AND p.COD_CAMPANHA=gt.cod_campanha
                            AND COD_LISTA IN (SELECT MAX(COD_LISTA) FROM PUSH_parametros WHERE COD_EMPRESA=gt.cod_empresa AND COD_CAMPANHA=gt.cod_campanha)
                    WHERE 
						
                gt.TIP_GATILHO IN ($gatilhos_impl_in) 
                     AND gt.LOG_STATUS ='S'
                    AND cp.LOG_ATIVO = 'S'
                    AND gt.cod_empresa=$rscomunicacaopush[COD_EMPRESA]
                    AND STR_TO_DATE(CONCAT(cp.DAT_INI,' ',cp.HOR_INI),'%Y-%m-%d %H:%i:%s') <= NOW()
		    AND STR_TO_DATE(CONCAT(cp.DAT_FIM,' ',cp.HOR_FIM),'%Y-%m-%d %H:%i:%s') >= NOW()      
            GROUP BY gt.COD_CAMPANHA,gt.TIP_GATILHO";
    $rwgatilho = mysqli_query($contemporaria, $sqlgatilho);
    while ($rsgatilho = mysqli_fetch_assoc($rwgatilho)) {
        //remover da lista de envio se o device nao contiver cliente_push
        //colocar registro de log para devices invalidos
        $logpushin = "INSERT INTO push_fila_error()
                                            SELECT * FROM email_fila
                                                                   WHERE cod_empresa=$rscomunicacaopush[COD_EMPRESA] AND 
                                                                                            cod_campanha IN (" . rtrim($rsgatilho['COD_CAMPANHA'], ',') . ")  and 
                                                                                            ROW(COD_CLIENTE,COD_EMPRESA)  IN (SELECT COD_CLIENTE,COD_EMPRESA FROM cliente_push where cod_empresa=$rscomunicacaopush[COD_EMPRESA]) AND 
                                                                                            dt_cadastr <= date_add(now(), INTERVAL -30 MINUTE) AND 
                                                                                            COD_ENVIADO='N'
                                                                             ORDER BY ID_FILA DESC";
        mysqli_query($contemporaria, $logpushin);

        $sqlpush = "DELETE FROM email_fila
	               WHERE cod_empresa=$rscomunicacaopush[COD_EMPRESA] AND 
						cod_campanha IN (" . rtrim($rsgatilho['COD_CAMPANHA'], ',') . ")  and 
						ROW(COD_CLIENTE,COD_EMPRESA) not IN (SELECT COD_CLIENTE,COD_EMPRESA FROM cliente_push where cod_empresa=$rscomunicacaopush[COD_EMPRESA]) AND 
						dt_cadastr <= date_add(now(), INTERVAL -30 MINUTE) AND 
						COD_ENVIADO='N'
				 ORDER BY ID_FILA desc";

        mysqli_query($contemporaria, $sqlpush);
        //deletar registro de campanha vencida
        $vencimentocamp = $rsgatilho['DAT_FIM'] . ' ' . $rsgatilho['HOR_FIM'];
        if ($vencimentocamp <= date('Y-m-d H:i:s')) {
            //deletar fila de campanha ja vencida 
            $sqlpushven = "DELETE FROM email_fila
	                                        WHERE cod_empresa=$rscomunicacaopush[COD_EMPRESA] AND 
						cod_campanha =IN (" . rtrim($rsgatilho['COD_CAMPANHA'], ',') . ")  and 
						COD_ENVIADO='N'
				 ORDER BY ID_FILA desc";
            mysqli_query($contemporaria, $sqlpushven);
            echo 'aquio';
        }
    }
}
echo 'FIM DO PUSH';
//exit();
//}





$sqlinicio = "SELECT * FROM senhas_parceiro apar
			INNER JOIN parceiro_comunicacao par ON par.COD_PARCOMU=apar.COD_PARCOMU
                        INNER JOIN empresas emp ON emp.COD_EMPRESA = apar.COD_EMPRESA  and emp.LOG_ATIVO='S'
			WHERE par.COD_TPCOM ='1' AND apar.LOG_ATIVO='S'";

$rwcomunicacao = mysqli_query($conadmin, $sqlinicio);
while ($rscomunicacao = mysqli_fetch_assoc($rwcomunicacao)) {
    $contemporaria = connTemp($rscomunicacao['COD_EMPRESA'], '');

    $exec = "SELECT COUNT(*) qtd_exec FROM information_schema.processlist WHERE STATE in ('updating','Searching rows for update') AND INFO LIKE 'UPDATE email_fila%'";
    $rsexec = mysqli_fetch_assoc(mysqli_query($contemporaria, $exec));
    if ($rsexec['qtd_exec'] <= '0') {

        $gatilhos = array("individual", "cadastro", "resgate", "venda", "aniv", "anivSem", "anivQuinz", "anivMes", "anivDia", "anivCad", "credExp", "inativos", "credVen");
        $gatilhos_impl_in = "'" . (implode("','", $gatilhos)) . "'";
        $sqlgatilho = "SELECT gt.COD_CAMPANHA ,gt.COD_EMPRESA,CONCAT(cp.DAT_FIM,' ',cp.HOR_FIM) DATA_FINAL,gt.LOG_STATUS,cp.LOG_ATIVO,cp.LOG_CONTINU FROM gatilho_email gt
							INNER JOIN campanha cp ON gt.COD_CAMPANHA=cp.COD_CAMPANHA 
							INNER JOIN email_parametros p ON gt.COD_EMPRESA=p.cod_empresa AND gt.COD_CAMPANHA=p.cod_campanha
								AND COD_LISTA IN (SELECT MAX(COD_LISTA) FROM email_parametros WHERE COD_EMPRESA=gt.cod_empresa AND COD_CAMPANHA=gt.cod_campanha)
						WHERE gt.TIP_GATILHO IN ($gatilhos_impl_in)
							AND gt.LOG_STATUS ='S'
							AND cp.LOG_ATIVO = 'S'
							AND gt.cod_empresa=$rscomunicacao[COD_EMPRESA];";
        $rwgatilho = mysqli_query($contemporaria, $sqlgatilho);
        if ($rwgatilho->num_rows > 0) {
            $contador = 1;
            $arraycamp = array();
            while ($rsgatilho = mysqli_fetch_assoc($rwgatilho)) {
                $arraycamp[] = $rsgatilho['COD_CAMPANHA'];

                //verificar se a campanha e continua ou esta na validade
                if ($rsgatilho['LOG_CONTINU'] == 'N') {
                    if ($rsgatilho['DATA_FINAL'] <= date('Y-m-d H:i:s')) {

                        if ($rsgatilho['LOG_ATIVO'] == 'S' || $rsgatilho['LOG_STATUS'] == 'S') {
                            $ipdatecampanha = "UPDATE campanha set LOG_ATIVO='N' where cod_empresa=$rscomunicacao[COD_EMPRESA] and cod_campanha=$rsgatilho[COD_CAMPANHA];";
                            $camp = mysqli_query(connTemp($rscomunicacao['COD_EMPRESA'], ''), $ipdatecampanha);
                            if (!$camp) {
                                echo $ipdatecampanha . '<br>';
                            }
                            $updateG = "UPDATE  gatilho_email SET LOG_STATUS='N' WHERE  cod_empresa=$rscomunicacao[COD_EMPRESA] and cod_campanha=$rsgatilho[COD_CAMPANHA];";
                            $gat = mysqli_query(connTemp($rscomunicacao['COD_EMPRESA'], ''), $updateG);
                            if (!$gat) {
                                echo $updateG . '<br>';
                            }
                            //deletar a fila
                            $deletarF = "DELETE FROM email_fila  WHERE 
                                                               COD_CAMPANHA= $rsgatilho[COD_CAMPANHA] and 
                                                               COD_EMPRESA=$rscomunicacao[COD_EMPRESA]";
                            mysqli_query(connTemp($rscomunicacao['COD_EMPRESA'], ''), $deletarF);
                        }
                    }
                }

                //=====================================================================
                if ($contador == '1') {
                    //DELETEAR EMAIL VAZIOS DA LISTA 
                    $DELETESEMEMAIL = "DELETE FROM email_fila  WHERE 
                                                               TIP_FILA IN (2,5,6,9) AND    
                                                               COD_EMPRESA=" . $rscomunicacao['COD_EMPRESA'] . " AND
                                                               DES_EMAILUS=''";

                    mysqli_query($contemporaria,  $DELETESEMEMAIL);
                }
                $contador++;
            }
            //executa das as campanhas uma unica vez
            $campar = "'" . (implode("','", $arraycamp)) . "'";
            //==========Contem na blacklist
            $sqltemblacklist = "UPDATE email_fila ef
                               INNER JOIN blacklist_email em ON em.DES_EMAIL=ef.DES_EMAILUS AND ef.COD_EMPRESA=em.COD_EMPRESA
                               SET 
                                   ef.LOG_BLACKLIST_EMAIL='N',
                                   ef.SCAN_OPOUT_EMAIL='S',
                                   ef.DAT_BLK =now()
                               WHERE ef.COD_CAMPANHA in ($campar) 
                                   AND ef.COD_ENVIADO ='N'
                                   AND ef.COD_EMPRESA=" . $rscomunicacao['COD_EMPRESA'] . "
                                   AND ef.SCAN_OPOUT_EMAIL='N';";
            $rw1 = mysqli_query($contemporaria,  $sqltemblacklist);
            if (!$rw1) {
                echo '<br>' . $sqltemblacklist . '<br>';
            }
            // echo '<br>'.$sqltemblacklist.'<br>';	
            //===================================================================== 	
            //==========Nao Contem na blacklist
            $sqltemblacklistn = "UPDATE email_fila ef 
                                LEFT JOIN blacklist_email AS be ON ef.DES_EMAILUS = be.DES_EMAIL AND be.COD_EMPRESA=$rscomunicacao[COD_EMPRESA]
                                               SET  ef.LOG_BLACKLIST_EMAIL='S', 
                                                    ef.SCAN_OPOUT_EMAIL='S',
                                                    ef.DAT_BLK =now()
                               WHERE ef.COD_CAMPANHA in ($campar) 
                                       AND ef.COD_ENVIADO ='N'
                                       AND ef.COD_EMPRESA=" . $rscomunicacao['COD_EMPRESA'] . "
                                       AND ef.SCAN_OPOUT_EMAIL='N'
                                       AND be.DES_EMAIL IS NULL;";
            $rw2 = mysqli_query($contemporaria,   $sqltemblacklistn);
            if (!$rw2) {
                echo '<br>' . $sqltemblacklistn . '<br>';
            }
            // echo '<br>'.$sqltemblacklistn.'<br>';	
            unset($arraycamp);
        }
    } else {
        echo '<br>Ja em execucao<br> ';
        echo 'INICIO...' . date('H:i:s') . '<br>';
    }
}
//======================================================
mysqli_close($contemporaria);
echo '<br>FIM...' . date('H:i:s');
