<?php
exit();
require '../../_system/_functionsMain.php';
//require '../../_system/func_nexux/func_nexux.php';
include '../../_system/whatsapp/wstAdorai.php';
//https://bunker.mk/externo/dinamize/ENVIO_WHATSAPP.php?COD_EMPRESA=328&TIP_GATILHO=inativos&ENVIAR=S

//Força dar timeout após 1 hora
//set_time_limit(60 * 60);

/*
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);
 */
$gera_log = "S";
$conadmin = $connAdm->connAdm();

$datahoraatual = date('Y-m-d H:i:s');
$horaatual = date("H");
$minutoatual = date("i");
$uuid = md5(uniqid(rand(), true));

$limite_envio = 500;
//função de geração de arquivos
//function gerandorcvs($caminho,$nomeArquivo,$delimitador,$arraydados,$arrayheders)
//caminho para salavar aquivo
//_system/func_dinamiza/lista_envio

$ini_rotina = $datahoraatual;
$sequencia = 0;

/*$sql = "SELECT COUNT(0) QTD,TIMESTAMPDIFF(MINUTE,MAX(DATAHORA_INICIO),NOW()) TEMPO FROM gatilhos_logs_exec WHERE TIPO='WHATSAPP' AND DATAHORA_ATUALIZACAO_EMPRE IS NULL";
$rs = mysqli_query($conadmin, $sql);
$linha = mysqli_fetch_assoc($rs);
if ($linha["QTD"] > 0 && $linha["TEMPO"] <= 5) {
    if (@$_REQUEST["COD_EMPRESA"] == "") {
        fnLog(array("DESCRICAO" => "Existe uma rotina ainda em execução iniciada a pouco tempo", "LAYOUT" => "text-warning"));
        exit;
    }
}*/


$sql = "INSERT INTO gatilhos_logs_exec (UID,COD_EXECUCAO,TIPO,DATAHORA_INICIO,CODS_EMPRESA) VALUES "
    . "('" . $uuid . "','" . str_replace("-", "", str_replace(":", "", str_replace(" ", "", $ini_rotina))) . "','WHATSAPP',NOW(),0)";
$rs = mysqli_query($conadmin, $sql);

function fnLog($dados = array())
{
    global $ini_rotina, $gera_log, $conadmin, $sequencia, $uuid;

    $sql = "UPDATE gatilhos_logs_exec SET
                DATAHORA_ATUALIZACAO_LOG=NOW(),
                FILA=(SELECT SUM(QTD_FILA) FROM gatilhos_logs WHERE UID=gatilhos_logs_exec.UID),
                ENVIOS=(SELECT SUM(QTD_ENVIOS) FROM gatilhos_logs WHERE UID=gatilhos_logs_exec.UID),
                ERROS=(SELECT COUNT(0) FROM gatilhos_logs WHERE ERRO <> '' AND UID=gatilhos_logs_exec.UID)
            WHERE UID='" . $uuid . "' AND COD_EXECUCAO='" . str_replace("-", "", str_replace(":", "", str_replace(" ", "", $ini_rotina))) . "'";
    $rs = mysqli_query($conadmin, $sql);

    if ($gera_log == "S") {
        foreach ($dados as $k => $v) {
            $dados[$k] = str_replace("'", "''", $v);
        }
        $sql_log = "INSERT INTO gatilhos_logs
					(UID,COD_EXECUCAO,DATAHORA_INICIO,DATAHORA,TIPO,SEQUENCIA,COD_EMPRESA,DESCRICAO,QUERY,ERRO,JSON,COD_GATILHO,TIP_GATILHO,COD_CAMPANHA,LAYOUT,QTD_ENVIOS,QTD_FILA)
					VALUES
					(
                    '" . $uuid . "',
					'" . str_replace("-", "", str_replace(":", "", str_replace(" ", "", $ini_rotina))) . "',
					'$ini_rotina',
					'" . date("Y-m-d H:i:s") . "',
					'WHATSAPP',
					'$sequencia',
					'0" . @$dados["COD_EMPRESA"] . "',
					'" . @$dados["DESCRICAO"] . "',
					'" . @$dados["SQL"] . "',
					'" . @$dados["ERRO"] . "',
					'" . @$dados["JSON"] . "',
					'0" . @$dados["COD_GATILHO"] . "',
					'" . @$dados["TIP_GATILHO"] . "',
					'0" . @$dados["COD_CAMPANHA"] . "',
					'" . @$dados["LAYOUT"] . "',
					'0" . @$dados["QTD_ENVIOS"] . "',
					'0" . @$dados["QTD_FILA"] . "'
					)
					";
        $rw = mysqli_query($conadmin, $sql_log);
        if (mysqli_error($conadmin) != "") {
            echo $sql_log;
        }
    }
    $sequencia++;
    echo date("Y-m-d H:i:s") . " - " . json_encode($dados) . "<br>";
}

echo "<pre>";
fnLog(array("DESCRICAO" => "Início da Rotina", "LAYOUT" => "text-success"));

$where = "";
if (@$_REQUEST["COD_EMPRESA"] != "") {
    $where .= "AND apar.COD_EMPRESA in (0" . @$_REQUEST["COD_EMPRESA"] . ")";
    $contemporaria = connTemp($_REQUEST["COD_EMPRESA"], '');
}

/*EMPRESAS************************************************************************************************************************/
$sqlempresa = "SELECT * FROM senhas_whatsapp apar
                INNER JOIN parceiro_comunicacao par ON par.COD_PARCOMU=apar.COD_PARCOMU WHERE par.COD_TPCOM='6' AND apar.COD_PARCOMU='21' AND apar.LOG_ATIVO='S'
				$where
				";

if ((int) $horaatual == 0 && (int) $minutoatual <= 30) {
    /*LIMPA REGISTROS ANTIGOS*********************************************************************************/
    $sql = "DELETE FROM email_fila WHERE DATE_ADD(DT_CADASTR,INTERVAL 32 DAY) < DATE(NOW())";
    $rs = mysqli_query($contemporaria, $sql);
    fnLog(array("DESCRICAO" => "Limpa registros da tabela email_fila com mais de 32 dias de cadastro", "SQL" => $sql, "COD_EMPRESA" => $cod_empresa, "ERRO" => mysqli_error($contemporaria)));

    $sql = "DELETE FROM email_filavalidades WHERE DATE_ADD(DT_CADASTR,INTERVAL 90 DAY) < DATE(NOW())";
    $rs = mysqli_query($contemporaria, $sql);
    fnLog(array("DESCRICAO" => "Limpa registros da tabela email_filavalidades com mais de 90 dias de cadastro", "SQL" => $sql, "COD_EMPRESA" => $cod_empresa, "ERRO" => mysqli_error($contemporaria)));

    $sql = "DELETE FROM gatilhos_logs WHERE DATE_ADD(DATAHORA_INICIO,INTERVAL 30 DAY) < DATE(NOW())";
    $rs = mysqli_query($conadmin, $sql);
    fnLog(array("DESCRICAO" => "Limpa registros da tabela gatilhos_logs com mais de 30 dias de cadastro", "SQL" => $sql, "COD_EMPRESA" => $cod_empresa, "ERRO" => mysqli_error($contemporaria)));

    $sql = "DELETE FROM gatilhos_logs_exec WHERE DATE_ADD(DATAHORA_INICIO,INTERVAL 30 DAY) < DATE(NOW())";
    $rs = mysqli_query($conadmin, $sql);
    fnLog(array("DESCRICAO" => "Limpa registros da tabela gatilhos_logs_exec com mais de 30 dias de cadastro", "SQL" => $sql, "COD_EMPRESA" => $cod_empresa, "ERRO" => mysqli_error($contemporaria)));
}

$rwempresa = mysqli_query($conadmin, $sqlempresa);
fnLog(array("DESCRICAO" => "Lista Empresas", "SQL" => $sqlempresa, "ERRO" => mysqli_error($conadmin)));
$count_empre = 0;
$tot_empre = mysqli_num_rows($rwempresa);
while ($rsempresa = mysqli_fetch_assoc($rwempresa)) {
    $cod_empresa = $rsempresa['COD_EMPRESA'];
    //$contemporaria = connTemp($cod_empresa, '');
    $DES_CLIEXT = $rsempresa['COD_LISTAEXT'];
    $count_empre++;

    $sql = "UPDATE gatilhos_logs_exec SET DATAHORA_ATUALIZACAO_EMPRE=NOW(), CODS_EMPRESA=CONCAT(CODS_EMPRESA,',',$cod_empresa) WHERE UID='" . $uuid . "' AND COD_EXECUCAO='" . str_replace("-", "", str_replace(":", "", str_replace(" ", "", $ini_rotina))) . "'";
    $rs = mysqli_query($conadmin, $sql);

    fnLog(array("DESCRICAO" => "Início Empresa $cod_empresa ( $count_empre / $tot_empre )", "COD_EMPRESA" => $cod_empresa, "JSON" => json_encode($rsempresa)));

    $sql = "SELECT TIP_RETORNO FROM EMPRESAS WHERE COD_EMPRESA = $cod_empresa";
    $arrayQuery = mysqli_query($conadmin, $sql);
    $qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
    $tip_retorno = $qrBuscaEmpresa['TIP_RETORNO'];

    if ($tip_retorno == 1) {
        $casasDec = 0;
    } else {
        $casasDec = 2;
    }


    //Verifica se essa empresa ainda está em execução
    $sql = "SELECT COUNT(0) QTD,GROUP_CONCAT(COD_LOG_EXEC) CODS FROM gatilhos_logs_exec "
        . " WHERE 1=1 "
        . " AND UID <> '$uuid'"
        . " AND TIPO='WHATSAPP'"
        . " AND DATAHORA_FIM IS NULL"
        . " AND CONCAT(',',CODS_EMPRESA,',') LIKE '%,$cod_empresa,%'"
        . " AND TIMESTAMPDIFF(MINUTE,DATAHORA_ATUALIZACAO_LOG,NOW()) < 60";
    $rs = mysqli_query($conadmin, $sql);
    $linha = mysqli_fetch_assoc($rs);

    fnLog(array("DESCRICAO" => "Processos em execução: " . $linha["QTD"], "COD_EMPRESA" => $cod_empresa, "SQL" => $sql, "ERRO" => mysqli_error($conadmin)));
    if ($linha["QTD"] > 0) {
        fnLog(array("DESCRICAO" => "Existe uma rotina ainda em execução para esta empresa - Código processo: <b>" . $linha["CODS"] . "</b>", "COD_EMPRESA" => $cod_empresa, "LAYOUT" => "text-warning"));
        continue;
    }



    /*GATILHO************************************************************************************************************************/
    $gatilhos = array("individual", "cadastro", "resgate", "venda", "aniv", "anivSem", "anivQuinz", "anivMes", "anivDia", "anivCad", "credExp", "inativos", "credVen"); // <-- Colocar na ordem do select
    if (@$_GET["TIP_GATILHO"] != "") {
        $gatilhos = array($_GET["TIP_GATILHO"]);
    }
    $gatilhos_impl_in = "'" . (implode("','", $gatilhos)) . "'";
    $gatilhos_impl_ord = "'" . (implode(",", $gatilhos)) . "'";
    $sqlgatilho = "SELECT * FROM gatilho_whatsapp gt
						INNER JOIN campanha cp ON gt.COD_CAMPANHA=cp.COD_CAMPANHA
						INNER JOIN whatsapp_parametros  p ON p.COD_EMPRESA=gt.cod_empresa AND p.COD_CAMPANHA=gt.cod_campanha
							AND COD_LISTA IN (SELECT MAX(COD_LISTA) FROM whatsapp_parametros WHERE COD_EMPRESA=gt.cod_empresa AND COD_CAMPANHA=gt.cod_campanha)
					WHERE gt.TIP_GATILHO IN ($gatilhos_impl_in)
						AND gt.LOG_STATUS ='S'
						AND cp.LOG_ATIVO = 'S'
						AND STR_TO_DATE(CONCAT(cp.DAT_INI,' ',cp.HOR_INI),'%Y-%m-%d %H:%i:%s') <= NOW()
						AND (cp.DAT_FIM IS NULL OR STR_TO_DATE(CONCAT(cp.DAT_FIM,' ',cp.HOR_FIM),'%Y-%m-%d %H:%i:%s') >= NOW())
						AND gt.cod_empresa=$cod_empresa
						" . (@$_GET["COD_CAMPANHA"] != "" ? " AND gt.COD_CAMPANHA = '0" . $_GET["COD_CAMPANHA"] . "'" : "") . "
					GROUP BY gt.COD_CAMPANHA
					ORDER BY FIND_IN_SET(gt.TIP_GATILHO, $gatilhos_impl_ord)";
    //echo $sqlgatilho;exit;
    $rwgatilho = mysqli_query($contemporaria, $sqlgatilho);
    fnLog(array("DESCRICAO" => "Obter dados do gatilho", "COD_EMPRESA" => $cod_empresa, "SQL" => $sqlgatilho, "ERRO" => mysqli_error($contemporaria)));
    $count_gati = 0;
    $tot_gati = mysqli_num_rows($rwgatilho);
    if ($tot_gati <= 0) {
        //echo  $sqlgatilho;
        fnLog(array("DESCRICAO" => "Dados do gatilho não encontrados", "COD_EMPRESA" => $cod_empresa, "SQL" => $sqlgatilho, "LAYOUT" => "text-warning"));
    }
    while ($rsgatilho = mysqli_fetch_assoc($rwgatilho)) {
        $count_gati++;
        $cod_gatilho = $rsgatilho["COD_GATILHO"];
        $cod_campanha = $rsgatilho["COD_CAMPANHA"];
        $tip_gatilho = $rsgatilho["TIP_GATILHO"];
        $tip_controle = $rsgatilho["TIP_CONTROLE"];
        $tip_momento = $rsgatilho["TIP_MOMENTO"];
        $log_ativo = $rsgatilho["LOG_ATIVO"];
        $datetimecampanha = $rsgatilho["DAT_FIM"] . ' ' . $rsgatilho["HOR_FIM"];
        $log_processa = $rsgatilho["LOG_PROCESSA_WHATSAPP"];
        $des_campanha = $rsgatilho["DES_CAMPANHA"];
        $cod_ext_campanha = $rsgatilho["COD_EXT_CAMPANHA"];
        $velocidade_envio = $rsgatilho["VELOCIDADE_ENVIO"];
        $cod_lista = $rsgatilho["COD_LISTA"];
        $cod_personas = $rsgatilho["COD_PERSONAS"];
        $pct_reserva = $rsgatilho["PCT_RESERVA"];
        $des_periodo = $rsgatilho["DES_PERIODO"];
        $des_periodo = ($des_periodo == "" ? "0" : $des_periodo);
        $dias_anteced = $rsgatilho["DIAS_ANTECED"];
        $dias_anteced = ($dias_anteced == "" ? "0" : $dias_anteced);
        $tot_saldomin = $rsgatilho["TOT_SALDOMIN"];
        $des_periodomin = $rsgatilho["DES_PERIODOMIN"];
        $des_periodomax = $rsgatilho["DES_PERIODOMAX"];
        $dias_hist = $rsgatilho["DIAS_HIST"];
        $log_process = $rsgatilho["LOG_PROCESS_GATILHO"];

        if ($des_periodo == 7) {
            $periodo = "Semanal";
        } elseif ($des_periodo == 15) {
            $periodo = "Quinzenal";
        } elseif ($des_periodo == 30) {
            $periodo = "Mensal";
        } else {
            $periodo = "Diário";
        }

        fnLog(array("DESCRICAO" => "Dados do gatilho $cod_gatilho ( $count_gati / $tot_gati ) - <b>Periodicidade:</b> $des_periodo - $periodo | <b>Horário:</b> $tip_momento | <b>Controle:</b> $tip_controle", "COD_EMPRESA" => $cod_empresa, "JSON" => json_encode($rsgatilho), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));

        //VERIFICA se a campanha está ativa
        if ($log_ativo != 'S') {
            fnLog(array("DESCRICAO" => "Campanha não está ativa", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha, "LAYOUT" => "text-danger"));
            continue;
        }

        //VERIFICA se a campanha está dentro da validade
        /*if ($datetimecampanha < $datahoraatual) {
            fnLog(array("DESCRICAO" => "Campanha fora da validade - <b>validade:</b> $datetimecampanha", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha, "LAYOUT" => "text-danger"));
            continue;
        }*/

        //VERIFICA se a campanha está ativa para envio de WHATSAPP
        if ($log_processa != 'S') {
            fnLog(array("DESCRICAO" => "Campanha não habilitada para envio de WHATSAPP (LOG_PROCESSA_WHATSAPP = 'N')!", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha, "LAYOUT" => "text-danger"));
            continue;
        }

        //Parâmetros de envio
        $dat_agendamento = date("Y-m-d H:i:s");


        if (@$_GET["ENVIAR"] == "S") {
            fnLog(array("DESCRICAO" => "Forçar execução do gatilho", "COD_EMPRESA" => $cod_empresa, "JSON" => json_encode($rsgatilho), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));
            $gravacao = true;
            $process = true;
        } else {

            if ($des_periodo == 7 || $des_periodo == 15) {
                $d = date("w");
                fnLog(array("DESCRICAO" => "Dia da semana: $d / Antecedência: $dias_anteced", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));
                //Dia da semana é diferente de DOMINGO? ($d = 0)
                if (($d % 7) != 0) {
                    fnLog(array("DESCRICAO" => "Gatilho semanal / quinzenal fora do período. (Período: $des_periodo / Antecedência: $dias_anteced / Referência: DOMINGO)", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));
                    //Dia da semana é igual a SEXTA e gatilho é niver? ($d = 6) - Então, é ENVIO AGENDADO
                    if ($tip_gatilho == "aniv" && ($d % 7) == 6) {
                        $dat_agendamento = date('Y-m-d H:i:s', strtotime(date("Y-m-d") . " + " . ((7 - ($d % 7)) * 24 + $tip_momento) . " hours"));
                        $tip_momento = 20; //Muda para processar as 20h
                        fnLog(array("DESCRICAO" => "Gatilho semanal / quinzenal habilitado para envio agendado - Agendado para: $dat_agendamento / Horário Agendamento: $tip_momento", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha, "LAYOUT" => "text-warning"));
                    } else {
                        continue;
                    }
                } else {
                    fnLog(array("DESCRICAO" => "Gatilho semanal / quinzenal. (Período: $des_periodo / Antecedência: $dias_anteced / Referência: DOMINGO)", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));
                }
            } elseif ($des_periodo == 30) {
                $de = ($tip_gatilho == "aniv" ? 2 : ($tip_gatilho == "inativos" ? 3 : 1));

                $d = date('d', strtotime(date("Y-m-d")));
                $d = intval($d);

                fnLog(array("DESCRICAO" => "Dia da semana: $d / Antecedência: $dias_anteced", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));
                if ($d == $de) {
                    fnLog(array("DESCRICAO" => "Gatilho mensal. (Período: $des_periodo / Antecedência: $dias_anteced / Referência: DIA 1&ordm;)", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));
                } else {
                    $d1 = date("d", strtotime(date("Y-m-$d", strtotime("+1 month")) . " - 1 days"));

                    fnLog(array("DESCRICAO" => "Gatilho mensal fora do período. (Período: $des_periodo / Antecedência: $dias_anteced / Referência: DIA $de&ordm; / D-0: $d / D-1: $d1)", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));

                    if ($d == $de - 1 && ($tip_gatilho == "aniv" || $tip_gatilho == "inativos")) {
                        //
                        // 1 dias antes
                        $dat_agendamento = date("Y-m-d", strtotime("+1 day")) . " " . $tip_momento . ":00:00";
                        $tip_momento = 20; //Muda para processar as 20h
                        fnLog(array("DESCRICAO" => "Gatilho mensal habilitado para envio agendado - Agendado para: $dat_agendamento / Horário Agendamento: $tip_momento", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha, "LAYOUT" => "text-warning"));
                        //
                    } else {
                        continue;
                    }
                }
            }


            //Checa o horário de envio
            $process = true;
            $gravacao = false;
            if ($tip_momento < 24 && $tip_momento > 0 && ($tip_momento - 1) == $horaatual && (int) $minutoatual >= 20 && (int) $minutoatual < 40) {
                fnLog(array("DESCRICAO" => "Enviar comando para gravar dados do gatilho (Hora atual: $horaatual / Hora de envio: $tip_momento)", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));
                $gravacao = true;
            }
            if ($tip_momento >= 24) {
                fnLog(array("DESCRICAO" => "Gatilho imediato - gravar dados (Hora atual: $horaatual / Hora de envio: $tip_momento)", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));
                $gravacao = true;
            }

            if ($tip_momento < 24 && $tip_momento > 0 && $tip_momento != $horaatual) {
                fnLog(array("DESCRICAO" => "Fora do horário de envio (Hora atual: $horaatual / Hora de envio: $tip_momento)", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));
                $process = false;
            }
            //if ($tip_momento < 24 && $tip_momento > 0 && (int) $minutoatual >= 20) {
            //    fnLog(array("DESCRICAO" => "Fora do minuto de envio (Hora atual: $horaatual:$minutoatual / Hora de envio: $tip_momento)", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));
            //    $process = false;
            //}
            if ($tip_momento >= 24 && (int) $minutoatual <= 20 && $horaatual == 8) {
                fnLog(array("DESCRICAO" => "Gatilho imediato adiado para não sobrecarregar gatilhos agendados! (Hora atual: $horaatual:$minutoatual / Hora de envio: $tip_momento)!", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));
                continue;
            }

            if (!$process) {
                if ($gravacao) {
                    fnLog(array("DESCRICAO" => "Gravando dados para envio agendado.... (Hora atual: $horaatual / Hora de envio: $tip_momento)!", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));
                } elseif ($log_process == "S" && ((int) $minutoatual < 20)) {
                    fnLog(array("DESCRICAO" => "Gatilho não enviado no horário agendado. Reprocessando.... (Hora atual: $horaatual / Hora de envio: $tip_momento)!", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));
                    fnLog(array("DESCRICAO" => "Reprocessamento Cancelado.... (Hora atual: $horaatual / Hora de envio: $tip_momento)!", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha, "LAYOUT" => "text-warning"));
                    continue;
                } else {
                    continue;
                }
            } else {
                fnLog(array("DESCRICAO" => "Dentro do horário de envio (Hora atual: $horaatual:$minutoatual / Hora de envio: $tip_momento)!", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));
            }
        }

        /*MARCA GATILHO PARA SER EXECUTADO*********************************************************************************/
        $sql = "UPDATE gatilho_whatsapp SET LOG_PROCESS_GATILHO='S',DATHOR_PROCESS=NOW() WHERE COD_GATILHO=$cod_gatilho AND COD_EMPRESA=$cod_empresa";
        $rs = mysqli_query($contemporaria, $sql);
        fnLog(array("DESCRICAO" => "Gatilho marcado para ser executado!", "SQL" => $sql, "COD_EMPRESA" => $cod_empresa, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));

        if ($gravacao) {
            if ($tip_gatilho == "aniv" || $tip_gatilho == "anivDia" || $tip_gatilho == "anivSem" || $tip_gatilho == "anivQuinz" || $tip_gatilho == "anivMes") {
                $tip_gatilho = "aniv";

                //$sqlIns = "DELETE FROM email_fila WHERE COD_EMPRESA = $cod_empresa AND TIP_GATILHO = '$tip_gatilho' AND COD_ENVIADO='N' AND COD_CAMPANHA = '$cod_campanha' AND TIP_FILA IN (5,13,14,15)";
                $sqlIns = "UPDATE email_fila SET COD_ENVIADO='Y',DT_ALTERAC=NOW() WHERE COD_EMPRESA = $cod_empresa AND TIP_GATILHO = '$tip_gatilho' AND COD_ENVIADO='N' AND COD_CAMPANHA = '$cod_campanha' AND TIP_FILA IN (5,13,14,15)";
                mysqli_query($contemporaria, $sqlIns);
                fnLog(array("DESCRICAO" => "Apagando itens não enviados na tabela email_fila", "COD_EMPRESA" => $cod_empresa, "SQL" => $sqlIns, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));

                $where = "";
                switch ($des_periodo) {
                    case 7:
                        $where = "WEEK( STR_TO_DATE(CONCAT(SUBSTR(C.DAT_NASCIME,1,5),'/" . date("Y") . "'),'%d/%m/%Y') ) = WEEK('" . $dat_agendamento . "')";
                        break;
                    case 15:
                        $where = "(
									WEEK( STR_TO_DATE(CONCAT(SUBSTR(C.DAT_NASCIME,1,5),'/" . date("Y") . "'),'%d/%m/%Y') ) = WEEK('" . $dat_agendamento . "') OR
									WEEK( STR_TO_DATE(CONCAT(SUBSTR(C.DAT_NASCIME,1,5),'/" . date("Y") . "'),'%d/%m/%Y') ) = WEEK(DATE_ADD('" . $dat_agendamento . "',INTERVAL 7 DAY)) OR
								  )";
                        break;
                    case 30:
                    case 99:
                        $where = "MONTH( STR_TO_DATE(CONCAT(SUBSTR(C.DAT_NASCIME,1,5),'/" . date("Y") . "'),'%d/%m/%Y') ) = MONTH('" . $dat_agendamento . "')";
                        break;
                    default:
                        $where = "DAY( STR_TO_DATE(CONCAT(SUBSTR(C.DAT_NASCIME,1,5),'/" . date("Y") . "'),'%d/%m/%Y') ) = DAY('" . $dat_agendamento . "') AND
								  MONTH( STR_TO_DATE(CONCAT(SUBSTR(C.DAT_NASCIME,1,5),'/" . date("Y") . "'),'%d/%m/%Y') ) = MONTH('" . $dat_agendamento . "')";
                        break;
                }
                $sqlQtd = "SELECT
							COUNT(0) QTD
						FROM PERSONACLASSIFICA A
						INNER JOIN CLIENTES C ON  A.COD_CLIENTE=C.COD_CLIENTE
						WHERE
						A.cod_persona IN (0$cod_personas) AND
						A.COD_EMPRESA=$cod_empresa AND
						C.DAT_NASCIME <> '00/00/0000' AND
						LENGTH (C.DAT_NASCIME) = 10 AND
						$where
						";
                $rs = mysqli_query($contemporaria, $sqlQtd);
                $qtd = mysqli_fetch_assoc($rs);

                $sqlIns = "INSERT IGNORE INTO email_fila ("
                    . " LOG_BLACKLIST_WHATSAPP,DT_CADASTR,COD_EMPRESA,COD_UNIVEND,COD_CLIENTE,NUM_CGCECPF,NOM_CLIENTE,DT_NASCIME,DES_EMAILUS,NUM_CELULAR,VAL_RESGATE,"
                    . " VAL_CRED_ACUMULADO,COD_SEXOPES,COD_CAMPANHA,TIP_MOMENTO,TIP_CONTROLE,TIP_FILA,TIP_GATILHO,VAL_EXPIRAR,DAT_EXPIRA,SEMANA,MES,COD_ENVIADO)"
                    . "	SELECT"
                    . "     'S','$dat_agendamento' DT_CADASTR,C.COD_EMPRESA,C.COD_UNIVEND,C.COD_CLIENTE,C.NUM_CGCECPF,C.NOM_CLIENTE,C.DAT_NASCIME,"
                    . "		C.DES_EMAILUS,C.NUM_CELULAR,0 VAL_RESGATE,0 VAL_CRED_ACUMULADO,C.COD_SEXOPES,$cod_campanha COD_CAMPANHA,'$tip_momento' TIP_MOMENTO,"
                    . "		'$tip_controle' TIP_CONTROLE,3 TIP_FILA,'$tip_gatilho' TIP_GATILHO,0 VAL_EXPIRAR,NULL DAT_EXPIRA,'' SEMANA,'' MES,'N' COD_ENVIADO"
                    . "	FROM PERSONACLASSIFICA A"
                    . "	INNER JOIN CLIENTES C ON  A.COD_CLIENTE=C.COD_CLIENTE"
                    . "	WHERE"
                    . "	A.cod_persona IN (0$cod_personas) AND"
                    . "	A.COD_EMPRESA=$cod_empresa AND"
                    . "	C.DAT_NASCIME <> '00/00/0000' AND"
                    . "	LENGTH (C.DAT_NASCIME) = 10 AND"
                    . "	$where"
                    . "	";
                mysqli_query($contemporaria, $sqlIns);

                fnLog(array("DESCRICAO" => "Gravando aniversariantes na tabela email_fila", "COD_EMPRESA" => $cod_empresa, "SQL" => $sqlIns, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));

                //$sqlIns = "DELETE FROM email_fila WHERE DT_NASCIME='31/12/1969' AND TIP_CONTROLE = '$tip_controle' AND COD_EMPRESA = $cod_empresa";
                $sqlIns = "UPDATE email_fila SET COD_ENVIADO='Y',DT_ALTERAC=NOW() WHERE DT_NASCIME='31/12/1969' AND TIP_CONTROLE = '$tip_controle' AND COD_EMPRESA = $cod_empresa";
                mysqli_query($contemporaria, $sqlIns);
                fnLog(array("DESCRICAO" => "Apagando datas inválidas na tabela email_fila", "COD_EMPRESA" => $cod_empresa, "SQL" => $sqlIns, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));
            } elseif ($tip_gatilho == "anivCad") {

                //$sqlIns = "DELETE FROM email_fila WHERE COD_EMPRESA = $cod_empresa AND TIP_GATILHO = '$tip_gatilho' AND COD_ENVIADO='N' AND COD_CAMPANHA = '$cod_campanha'";
                $sqlIns = "UPDATE email_fila SET COD_ENVIADO='Y',DT_ALTERAC=NOW() WHERE COD_EMPRESA = $cod_empresa AND TIP_GATILHO = '$tip_gatilho' AND COD_ENVIADO='N' AND COD_CAMPANHA = '$cod_campanha'";
                mysqli_query($contemporaria, $sqlIns);
                fnLog(array("DESCRICAO" => "Apagando itens não enviados na tabela email_fila", "COD_EMPRESA" => $cod_empresa, "SQL" => $sqlIns, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));

                $where = "";
                switch ($des_periodo) {
                    case 7:
                        $where = "WEEK( C.DAT_CADASTR ) = WEEK('" . $dat_agendamento . "')";
                        break;
                    case 15:
                        $where = "(
									WEEK( C.DAT_CADASTR ) = WEEK('" . $dat_agendamento . "') OR
									WEEK( C.DAT_CADASTR ) = WEEK(DATE_ADD('" . $dat_agendamento . "',INTERVAL 7 DAY)) OR
								  )";
                        break;
                    case 30:
                    case 99:
                        $where = "MONTH( C.DAT_CADASTR ) = MONTH('" . $dat_agendamento . "')";
                        break;
                    default:
                        $where = "DAY( C.DAT_CADASTR ) = DAY('" . $dat_agendamento . "') AND
								  MONTH( C.DAT_CADASTR ) = MONTH('" . $dat_agendamento . "')";
                        break;
                }
                $sqlIns = "INSERT IGNORE INTO email_fila ("
                    . "LOG_BLACKLIST_WHATSAPP,DT_CADASTR,COD_EMPRESA,COD_UNIVEND,COD_CLIENTE,NUM_CGCECPF,NOM_CLIENTE,DT_NASCIME,DES_EMAILUS,NUM_CELULAR,VAL_RESGATE,"
                    . " VAL_CRED_ACUMULADO,COD_SEXOPES,COD_CAMPANHA,TIP_MOMENTO,TIP_CONTROLE,TIP_FILA,TIP_GATILHO,VAL_EXPIRAR,DAT_EXPIRA,SEMANA,MES,COD_ENVIADO)"
                    . " SELECT"
                    . "		'S','$dat_agendamento' DT_CADASTR,C.COD_EMPRESA,C.COD_UNIVEND,C.COD_CLIENTE,C.NUM_CGCECPF,C.NOM_CLIENTE,C.DAT_NASCIME,"
                    . "		C.DES_EMAILUS,C.NUM_CELULAR,0 VAL_RESGATE,0 VAL_CRED_ACUMULADO,C.COD_SEXOPES,$cod_campanha COD_CAMPANHA,'$tip_momento' TIP_MOMENTO,"
                    . "		'$tip_controle' TIP_CONTROLE,3 TIP_FILA,'$tip_gatilho' TIP_GATILHO,0 VAL_EXPIRAR,NULL DAT_EXPIRA,'' SEMANA,'' MES,'N' COD_ENVIADO"
                    . "	FROM PERSONACLASSIFICA A"
                    . "	INNER JOIN CLIENTES C ON  A.COD_CLIENTE=C.COD_CLIENTE"
                    . "	WHERE"
                    . "	A.cod_persona IN (0$cod_personas) AND"
                    . "	A.COD_EMPRESA=$cod_empresa AND"
                    . "	C.DAT_CADASTR <> '00/00/0000' AND"
                    . "	LENGTH (C.DAT_NASCIME) = 10 AND"
                    . "	$where"
                    . "	";
                mysqli_query($contemporaria, $sqlIns);

                fnLog(array("DESCRICAO" => "Gravando aniversariantes de cadastro na tabela email_fila", "COD_EMPRESA" => $cod_empresa, "SQL" => $sqlIns, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));

                //$sqlIns = "DELETE FROM email_fila WHERE DT_NASCIME='31/12/1969' AND TIP_CONTROLE = '$tip_controle' AND COD_EMPRESA = $cod_empresa";
                $sqlIns = "UPDATE email_fila SET COD_ENVIADO='Y',DT_ALTERAC=NOW() WHERE DT_NASCIME='31/12/1969' AND TIP_CONTROLE = '$tip_controle' AND COD_EMPRESA = $cod_empresa";
                mysqli_query($contemporaria, $sqlIns);
                fnLog(array("DESCRICAO" => "Apagando datas inválidas na tabela email_fila", "COD_EMPRESA" => $cod_empresa, "SQL" => $sqlIns, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));
            } elseif ($tip_gatilho == "credExp") {

                $dias_anteced = ($dias_anteced == "" ? 1 : $dias_anteced);
                $tot_saldomin = ($tot_saldomin <= 0 ? 1 : $tot_saldomin);
                $des_periodomax = ($des_periodomax == "" ? 0 : $des_periodomax);
                $sqlIns = "INSERT IGNORE INTO email_fila ("
                    . " DT_CADASTR,COD_EMPRESA,COD_UNIVEND,COD_CLIENTE,NUM_CGCECPF,NOM_CLIENTE,DT_NASCIME,DES_EMAILUS,NUM_CELULAR,VAL_RESGATE,"
                    . "	VAL_CRED_ACUMULADO,COD_SEXOPES,COD_CAMPANHA,TIP_MOMENTO,TIP_CONTROLE,TIP_FILA,TIP_GATILHO,VAL_EXPIRAR,DAT_EXPIRA,SEMANA,MES,COD_ENVIADO)"
                    . "	SELECT"
                    . "		'$dat_agendamento' DT_CADASTR,C.COD_EMPRESA,C.COD_UNIVEND,C.COD_CLIENTE,C.NUM_CGCECPF,C.NOM_CLIENTE,NULL DT_NASCIME,"
                    . "		C.DES_EMAILUS,C.NUM_CELULAR,0 VAL_RESGATE,0 VAL_CRED_ACUMULADO,C.COD_SEXOPES,$cod_campanha COD_CAMPANHA,'$tip_momento' TIP_MOMENTO,"
                    . "		'$tip_controle' TIP_CONTROLE,3 TIP_FILA,'$tip_gatilho' TIP_GATILHO,"
                    . "     ROUND((SELECT SUM(val_saldo) FROM creditosdebitos E WHERE E.COD_CLIENTE=C.COD_CLIENTE AND E.COD_STATUSCRED=1 AND DATE(E.DAT_EXPIRA) >= DATE(DATE_ADD(NOW(),INTERVAL $dias_anteced DAY)) AND DATE(E.DAT_EXPIRA) <= DATE(DATE_ADD(NOW(),INTERVAL " . ($dias_anteced + $des_periodomax) . " DAY))),2)  VAL_EXPIRAR,"
                    . "     NULL DAT_EXPIRA,'' SEMANA,'' MES,'N' COD_ENVIADO"
                    . "	FROM PERSONACLASSIFICA A"
                    . "	INNER JOIN CREDITOSDEBITOS B ON A.COD_CLIENTE=B.COD_CLIENTE"
                    . "	INNER JOIN CLIENTES C ON  A.COD_CLIENTE=C.COD_CLIENTE"
                    . "	WHERE"
                    . "	A.cod_persona IN (0$cod_personas) AND"
                    . "	A.COD_EMPRESA=$cod_empresa AND"
                    . "	DATE(B.DAT_EXPIRA) >= DATE(DATE_ADD(NOW(),INTERVAL $dias_anteced DAY)) AND"
                    . "	DATE(B.DAT_EXPIRA) <= DATE(DATE_ADD(NOW(),INTERVAL " . ($dias_anteced + $des_periodomax) . " DAY)) AND"
                    . "	B.TIP_CREDITO='C' AND"
                    . "	B.COD_STATUSCRED='1'"
                    . "	GROUP BY B.COD_CLIENTE"
                    . "	HAVING (SELECT SUM(val_saldo) FROM creditosdebitos E WHERE E.COD_CLIENTE=C.COD_CLIENTE AND COD_STATUSCRED=1) >= $tot_saldomin";
                mysqli_query($contemporaria, $sqlIns);
                fnLog(array("DESCRICAO" => "Gravando Créditos a Expirar", "COD_EMPRESA" => $cod_empresa, "SQL" => $sqlIns, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));
            } elseif ($tip_gatilho == "inativos") {

                $dias_anteced = ($dias_anteced == "" ? 1 : $dias_anteced);
                $tot_saldomin = ($tot_saldomin <= 0 ? 1 : $tot_saldomin);
                $des_periodomax = ($des_periodomax == "" ? 0 : $des_periodomax);
                $sqlIns = "INSERT IGNORE INTO email_fila ("
                    . " DT_CADASTR,COD_EMPRESA,COD_UNIVEND,COD_CLIENTE,NUM_CGCECPF,NOM_CLIENTE,DT_NASCIME,DES_EMAILUS,NUM_CELULAR,VAL_RESGATE,"
                    . "	VAL_CRED_ACUMULADO,COD_SEXOPES,COD_CAMPANHA,TIP_MOMENTO,TIP_CONTROLE,TIP_FILA,TIP_GATILHO,VAL_EXPIRAR,DAT_EXPIRA,SEMANA,MES,COD_ENVIADO)"
                    . "	SELECT"
                    . "		'$dat_agendamento' DT_CADASTR,BB.COD_EMPRESA,BB.COD_UNIVEND,BB.COD_CLIENTE,BB.NUM_CGCECPF,BB.NOM_CLIENTE,NULL DT_NASCIME,"
                    . "		BB.DES_EMAILUS,BB.NUM_CELULAR,0 VAL_RESGATE,0 VAL_CRED_ACUMULADO,BB.COD_SEXOPES,$cod_campanha COD_CAMPANHA,NULL TIP_MOMENTO,"
                    . "		NULL TIP_CONTROLE,3 TIP_FILA,'$tip_gatilho' TIP_GATILHO,0 VAL_EXPIRAR,NULL DAT_EXPIRA,'' SEMANA,'' MES,'N' COD_ENVIADO"
                    . "	FROM clientes BB"
                    . "	INNER JOIN PERSONACLASSIFICA A ON A.COD_CLIENTE=BB.COD_CLIENTE"
                    . "	WHERE BB.cod_empresa=$cod_empresa AND"
                    . "	A.cod_persona IN (0$cod_personas) AND"
                    . "	BB.LOG_ESTATUS = 'S' AND"
                    . "	BB.LOG_FIDELIZADO = 'S' AND"
                    . "	IFNULL(DATEDIFF(DATE(NOW()),DATE(BB.DAT_ULTCOMPR)),0) > 0" . $dias_hist . " AND"
                    . "	DATE_FORMAT(BB.DAT_CADASTR, '%Y-%m-%d') <= DATE_FORMAT(ADDDATE( NOW(), INTERVAL - $des_periodomin DAY), '%Y-%m-%d') AND"
                    . "	NOT EXISTS ("
                    . "			SELECT 1"
                    . "			FROM VENDAS A"
                    . "			WHERE DATE_FORMAT(A.DAT_CADASTR, '%Y-%m-%d') BETWEEN  DATE_FORMAT(ADDDATE( NOW(), INTERVAL - $des_periodomax DAY), '%Y-%m-%d') AND DATE_FORMAT(ADDDATE( NOW(), INTERVAL - $des_periodomin DAY), '%Y-%m-%d') AND"
                    . "				  A.COD_EMPRESA=$cod_empresa AND"
                    . "					A.COD_AVULSO=2 AND"
                    . "				  A.VAL_TOTVENDA>1 AND"
                    . "				  A.COD_STATUSCRED IN(0,1,2,3,4,5,7,8,9) AND"
                    . "				  A.COD_CLIENTE=BB.COD_CLIENTE)"
                    . "	AND BB.COD_CLIENTE NOT IN ("
                    . "		SELECT COD_CLIENTE FROM email_filavalidades"
                    . "		WHERE COD_EMPRESA=BB.cod_empresa"
                    . "		AND TIP_GATILHO='$tip_gatilho'"
                    . "		AND DATE(DT_CADASTR)=DATE(NOW())"
                    . "	)";

                mysqli_query($contemporaria, $sqlIns);
                fnLog(array("DESCRICAO" => "Gravando Inativos", "COD_EMPRESA" => $cod_empresa, "SQL" => $sqlIns, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));
            } else {
                fnLog(array("DESCRICAO" => "Não há dados para serem gravados", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));
            }

            if (!$process) {
                /*MARCA GATILHO PARA SER EXECUTADO*********************************************************************************/
                $sql = "UPDATE gatilho_whatsapp SET LOG_PROCESS_GATILHO='N',DATHOR_PROCESS=NOW() WHERE COD_GATILHO=$cod_gatilho";
                $rs = mysqli_query($contemporaria, $sql);
                fnLog(array("DESCRICAO" => "Gatilho marcado para ser executado!", "SQL" => $sql, "COD_EMPRESA" => $cod_empresa, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));

                fnLog(array("DESCRICAO" => "Gravação efetuada.... Aguardando envio (Hora atual: $horaatual / Hora de envio: $tip_momento)!", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));
                continue;
            } else {
                fnLog(array("DESCRICAO" => "Gravação efetuada.... gatilho imediato prosseguindo com envio (Hora atual: $horaatual / Hora de envio: $tip_momento)!", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));
            }
        }


        /* Remove mensagens de unidades de venda intativas */
        $sql = "DELETE em
                        FROM email_fila em
                        INNER JOIN unidadevenda un ON un.COD_UNIVEND = em.COD_UNIVEND AND un.LOG_ESTATUS = 'N'
                        WHERE em.COD_EMPRESA = $cod_empresa;";
        $rs = mysqli_query($contemporaria, $sql);

        /*INICIO ROTINA DE ENVIO*/
        $tampletevariavel = "SELECT 
                            DES_TEMPLATE,DES_TEMPLATE2,DES_TEMPLATE3,DES_TEMPLATE4,DES_TEMPLATE5,
                            COD_TEMPLATE,COD_EXT_TEMPLATE,DES_IMAGEM FROM TEMPLATE_whatsapp T
						INNER JOIN mensagem_whatsapp M ON M.COD_TEMPLATE_WHATSAPP=T.COD_TEMPLATE
						WHERE T.COD_EMPRESA='$cod_empresa'
							AND COD_CAMPANHA='$cod_campanha' AND LOG_ATIVO='S'";

        mysqli_query($contemporaria, "set character_set_client='utf8mb4'");
        mysqli_query($contemporaria, "set character_set_results='utf8mb4'");
        mysqli_query($contemporaria, "set collation_connection='utf8mb4_unicode_ci'");

        $html = mysqli_fetch_assoc(mysqli_query($contemporaria, $tampletevariavel));
        fnLog(array("DESCRICAO" => "Carregando Template", "COD_EMPRESA" => $cod_empresa, "SQL" => $tampletevariavel, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));

        $templates = [];
        if ($html['DES_TEMPLATE'] !== "") {
            $templates[] = $html['DES_TEMPLATE'];
        }
        if ($html['DES_TEMPLATE2'] !== "") {
            $templates[] = $html['DES_TEMPLATE2'];
        }
        if ($html['DES_TEMPLATE3'] !== "") {
            $templates[] = $html['DES_TEMPLATE3'];
        }
        if ($html['DES_TEMPLATE4'] !== "") {
            $templates[] = $html['DES_TEMPLATE4'];
        }
        if ($html['DES_TEMPLATE5'] !== "") {
            $templates[] = $html['DES_TEMPLATE5'];
        }

        $template = $templates[array_rand($templates)];

        $msg_envio = $template;
        $cod_template = $html["COD_TEMPLATE"];
        $des_imagem = $html['DES_IMAGEM'];
        $tagsPersonaliza = procpalavras($msg_envio, $connAdm->connAdm());
        $tags = explode(',', $tagsPersonaliza);
        $selectCliente = "";
        $innerjoin = "";
        $contador = '0';
        for ($i = 0; $i < count($tags); $i++) {
            //echo($tags[$i])."...<br>";
            switch ($tags[$i]) {

                case '<#NOME>';
                    $selectCliente .= "C.NOM_CLIENTE, ";
                    break;
                case '<#CODCLIENTE>';
                    $selectCliente .= "C.COD_CLIENTE CODCLIENTE,";
                    break;
                case '<#CARTAO>';
                    $selectCliente .= "";
                    break;
                case '<#ESTADOCIVIL>';
                    $selectCliente .= "";
                    break;
                case '<#SEXO>';
                    $selectCliente .= "";
                    break;
                case '<#PROFISSAO>';
                    $selectCliente .= "";
                    break;
                case '<#NASCIMENTO>';
                    $selectCliente .= "";
                    break;
                case '<#ENDERECO>';
                    $selectCliente .= "";
                    break;
                case '<#NUMERO>';
                    $selectCliente .= "";
                    break;
                case '<#BAIRRO>';
                    $selectCliente .= "";
                    break;
                case '<#CIDADE>';
                    $selectCliente .= "";
                    break;
                case '<#ESTADO>';
                    $selectCliente .= "";
                    break;
                case '<#CEP>';
                    $selectCliente .= "";
                    break;
                case '<#COMPLEMENTO>';
                    $selectCliente .= "";
                    break;
                case '<#TELEFONE>';
                    $selectCliente .= "";
                    break;
                case '<#CELULAR>';
                    $selectCliente .= "";
                    break;
                case '<#SALDO>';

                    $selectCliente .= "FORMAT(TRUNCATE(IFNULL((
															SELECT IFNULL((
															SELECT  SUM(val_saldo)
															FROM creditosdebitos f
															WHERE f.cod_cliente = cred.cod_cliente AND
																  f.tip_credito = 'C' AND
																  f.cod_statuscred in (1,2) AND
																			f.tip_campanha = cred.tip_campanha AND
																			((f.log_expira = 'S' AND DATE_FORMAT(f.dat_expira, '%Y-%m-%d') >= DATE_FORMAT(NOW(), '%Y-%m-%d')) OR (f.log_expira = 'N'))),0)+ IFNULL((
															SELECT SUM(val_saldo)
															FROM creditosdebitos_bkp g
															WHERE g.cod_cliente = cred.cod_cliente AND g.tip_credito = 'C' AND g.cod_statuscred in (1,2) AND g.tip_campanha = cred.tip_campanha AND ((g.log_expira = 'S' AND DATE_FORMAT(g.dat_expira, '%Y-%m-%d') >= DATE_FORMAT(NOW(), '%Y-%m-%d')) OR (g.log_expira = 'N'))),0)
															FROM creditosdebitos cred
															WHERE cred.cod_cliente=C.cod_CLIENTE
															GROUP BY cred.cod_cliente),0),$casasDec),$casasDec,'pt_BR') AS CREDITO_DISPONIVEL, ";
                    break;
                case '<#PRIMEIRACOMPRA>';
                    $selectCliente .= "";
                    break;
                case '<#ULTIMACOMPRA>';
                    $selectCliente .= "";
                    break;
                case '<#TOTALCOMPRAS>';
                    $selectCliente .= "";
                    break;
                case '<#CODIGO>';
                    $selectCliente .= "";
                    break;
                case '<#CUPOMSORTEIO>';
                    $selectCliente .= "";
                    break;
                case '<#CUPOM_INDICACAO>';
                    $selectCliente .= "";
                    break;
                case '<#NUMEROLOJA>';
                    $selectCliente .= "";
                    break;
                case '<#BAIRROLOJA>';
                    $selectCliente .= "";
                    break;
                case '<#NOMELOJA>';
                    //$selectCliente .= "C.COD_UNIVEND,";
                    $selectCliente .= "uni.COD_UNIVEND,uni.NOM_FANTASI,";
                    $innerjoin .= " INNER JOIN unidadevenda uni ON uni.COD_UNIVEND=email_fila.COD_UNIVEND ";
                    /*
                    $selectCliente = "(SELECT MIN(COD_UNIVEND) FROM email_fila WHERE
                    email_fila.COD_CLIENTE=C.COD_CLIENTE
                    email_fila.TIP_GATILHO='$tip_gatilho' AND
                    email_fila.TIP_FILA IN (5,13,14,15) AND
                    email_fila.COD_EMPRESA=$cod_empresa AND
                    email_fila.COD_CAMPANHA=$cod_campanha AND
                    email_fila.COD_ENVIADO='N') COD_UNIVEND,";
                     */
                    break;
                case '<#ENDERECOLOJA>';
                    $selectCliente .= "";
                    break;
                case '<#RESGATE>';
                    $selectCliente .= "FORMAT(TRUNCATE(email_fila.VAL_RESGATE,$casasDec),$casasDec,'pt_BR') as VAL_RESGATE,";
                    break;
                case '<#TELEFONELOJA>';
                    $selectCliente .= "";
                    break;
                case '<#ANIVERSARIO>';
                    $selectCliente .= "C.DAT_NASCIME,";
                    break;
                case '<#ANIVERSARIOCAD>';
                    $selectCliente .= "C.DAT_CADASTR,";
                    break;
                case '<#DATAEXPIRA>';
                    $selectCliente .= "(SELECT
                                                            MIN(DAT_EXPIRA) AS DAT_EXPIRA
                                                            FROM creditosdebitos
                                                                    WHERE DAT_EXPIRA >= NOW() AND  cod_CLIENTE=C.cod_CLIENTE) AS DAT_EXPIRA,";
                    break;
                case '<#DATAEXPIRAMAX>';
                    $selectCliente .= "(SELECT
                                                                    MAX(DAT_EXPIRA) AS DATAEXPIRAMAX
                                                                    FROM creditosdebitos
                                                                            WHERE DAT_EXPIRA >= NOW() AND  cod_CLIENTE=C.cod_CLIENTE) AS DATAEXPIRAMAX,";
                    break;
                case '<#SALDOEXPIRA>';
                    $selectCliente .= "(SELECT MIN(VAL_EXPIRAR) FROM email_fila WHERE
												email_fila.COD_CLIENTE=C.COD_CLIENTE
												email_fila.TIP_GATILHO='$tip_gatilho' AND
												email_fila.TIP_FILA IN (5,13,14,15) AND
												email_fila.COD_EMPRESA=$cod_empresa AND
												email_fila.COD_CAMPANHA=$cod_campanha AND
												email_fila.COD_ENVIADO='N') VAL_EXPIRAR,";
                    break;
                case '<#CREDITOVENDA>';
                    $selectCliente .= "email_fila.CRED_VENDA,";
                    break;
                default:
                    $selectCliente .= "C.DES_EMAILUS,";
                    break;
            }
            $contador++;
        }

        // verificação das variaveis pra montar o select/arquivos de envio
        $selectCliente .= "C.COD_CLIENTE,email_fila.NUM_CELULAR,email_fila.DT_CADASTR";
        //        $tagsDinamize = rtrim($tagsDinamize,',');

        $sqlcli_cad = "SELECT DISTINCT $selectCliente
						FROM clientes C
						INNER JOIN email_fila ON (email_fila.COD_CLIENTE = C.COD_CLIENTE)
                        " . ($tip_gatilho == "cadastro" && $des_periodo > 30
            ? ""
            : "INNER JOIN personaclassifica per ON per.COD_CLIENTE=email_fila.COD_CLIENTE AND  per.COD_PERSONA IN (0$cod_personas)"
        ) . "
						$innerjoin
						WHERE C.COD_EMPRESA = $cod_empresa
						AND C.LOG_WHATSAPP = 'S'
						AND C.LOG_FIDELIZADO = 'S'
						AND C.LOG_ESTATUS = 'S'
						AND TRIM(email_fila.NUM_CELULAR) != ''
						AND email_fila.TIP_GATILHO='$tip_gatilho'
						AND email_fila.TIP_FILA IN (5,13,14,15)

						" . ($tip_gatilho == "aniv" || $tip_gatilho == "anivCad" || $tip_gatilho == "credExp" || $tip_gatilho == "inativos" ? "AND DATE(email_fila.DT_CADASTR)=DATE('" . $dat_agendamento . "')" : "") . "
						" . ($tip_gatilho == "cadastro" && $des_periodo <= 30 ? "AND DATE(email_fila.DT_CADASTR) < DATE('" . $dat_agendamento . "')" : "") . "
						" . ($tip_gatilho == "venda" && $tip_momento <= 24 && $des_periodo == 1 ? "AND DATE(email_fila.DT_CADASTR) = DATE_SUB(DATE('" . $dat_agendamento . "'), INTERVAL 1 DAY)" : "") . "
						" . ($des_periodo == 7 ? "AND (WEEK(email_fila.DT_CADASTR)>=WEEK(DATE_ADD('" . $dat_agendamento . "', INTERVAL -1 WEEK)) OR WEEK(email_fila.DT_CADASTR)=WEEK('" . $dat_agendamento . "'))" : "") . "
						" . ($des_periodo == 15 ? "AND (WEEK(email_fila.DT_CADASTR)>=WEEK(DATE_ADD('" . $dat_agendamento . "', INTERVAL -2 WEEK)) OR WEEK(email_fila.DT_CADASTR)=WEEK('" . $dat_agendamento . "'))" : "") . "
						" . ($des_periodo == 30 ? "AND (MONTH(DT_CADASTR)>=MONTH(DATE_ADD('" . $dat_agendamento . "', INTERVAL -1 MONTH)) OR MONTH(DT_CADASTR)=MONTH('" . $dat_agendamento . "'))" : "") . "
						AND email_fila.COD_EMPRESA=C.COD_EMPRESA
						AND email_fila.COD_CAMPANHA=$cod_campanha
						AND email_fila.COD_ENVIADO='N'
						group by C.COD_CLIENTE
						" .
            ($tip_gatilho == "venda" ?
                "HAVING (SELECT COALESCE(SUM(E.val_saldo), 0.00) val_saldo FROM creditosdebitos E WHERE E.COD_CLIENTE=C.COD_CLIENTE AND E.COD_STATUSCRED=1) >= $tot_saldomin" :
                ""
            );
        //						" . (@$_GET["ENVIAR"] != "S" ? "AND email_fila.LOG_BLACKLIST_WHATSAPP = 'S'" : "") . "
        //echo $sqlcli_cad;exit;
        $rwsql = mysqli_query($contemporaria, $sqlcli_cad);
        fnLog(array("DESCRICAO" => "Carregando clientes... Qtd. Retornado: " . mysqli_num_rows($rwsql), "COD_EMPRESA" => $cod_empresa, "SQL" => $sqlcli_cad, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));
        $CLIE_CAD = array();
        $CLIE_WHATSAPP = array();
        $CLIE_CAD_L = array();
        $CLIE_WHATSAPP_L = array();
        while ($headers = mysqli_fetch_field($rwsql)) {
            $headers1["campos"][$headers->name] = $headers->name;
        }
        $cods_cliente = "0";
        $cods_cliente_l = [];
        $qtd_lote = 0;
        $lote = 1;
        $qtd_total_envio = 0;
        $msg_env = "";

        while ($rsemail_fila = mysqli_fetch_assoc($rwsql)) {
            //print_r($rsemail_fila);

            $num_celular_ori = $rsemail_fila["NUM_CELULAR"];
            $num_celular = $rsemail_fila["NUM_CELULAR"];
            $cod_cliente = $rsemail_fila["COD_CLIENTE"];
            $textoenvio = $template;

            //capturar dominio
            //capturando dominio inicial
            $sqldominio = "SELECT DES_DOMINIO,COD_DOMINIO from site_extrato WHERE cod_empresa='" . $cod_empresa . "'";
            $rsdominio = mysqli_fetch_assoc(mysqli_query($contemporaria, $sqldominio));
            $COD_DOMINIO = $rsdominio['COD_DOMINIO'];
            $DES_DOMINIO = $rsdominio['DES_DOMINIO'];
            /*$sqltoken="SELECT DES_TOKEN FROM geratoken WHERE
            cod_empresa=$cod_empresa
            AND TIP_TOKEN=1
            AND cod_cliente='".$cod_cliente."'
            AND  LOG_USADO=2
            ORDER BY COD_TOKEN desc LIMIT 1";
            $rstokenmaiscash=mysqli_fetch_assoc(mysqli_query($contemporaria,$sqltoken));*/

            //=========================
            //print_r($rsemail_fila);
            $NOM_CLIENTE = explode(" ", ucfirst(strtolower(fnAcentos($rsemail_fila['NOM_CLIENTE']))));
            $textoenvio = str_replace('<#NOME>', @$NOM_CLIENTE[0], $textoenvio);
            $textoenvio = str_replace('<#SALDO>', @$rsemail_fila['CREDITO_DISPONIVEL'], $textoenvio);
            $textoenvio = str_replace('<#NOMELOJA>', @$rsemail_fila['NOM_FANTASI'], $textoenvio);
            $textoenvio = str_replace('<#ANIVERSARIO>', @$rsemail_fila['DAT_NASCIME'], $textoenvio);
            $textoenvio = str_replace('<#ANIVERSARIOCAD>', @$rsemail_fila['DAT_CADASTRO'], $textoenvio);
            $textoenvio = str_replace('<#DATAEXPIRA>', fnDataShort($rsemail_fila['DAT_EXPIRA']), $textoenvio);
            $textoenvio = str_replace('<#EMAIL>', @$rsemail_fila['DES_EMAILUS'], $textoenvio);
            $textoenvio = str_replace('<#RESGATE>', @$rsemail_fila['VAL_RESGATE'], $textoenvio);
            $textoenvio = str_replace('<#SALDOEXPIRA>', @$rsemail_fila['VAL_EXPIRAR'], $textoenvio);
            $textoenvio = str_replace('<#CREDITOVENDA>', @$rsemail_fila['CRED_VENDA'], $textoenvio);
            $textoenvio = str_replace('<#DATAEXPIRAMAX>', fnDataShort($rsemail_fila['DATAEXPIRAMAX']), $textoenvio);
            $textoenvio = str_replace('<#CODCLIENTE>', $rsemail_fila['CODCLIENTE'], $textoenvio);

            //$textoenvio=str_replace('<#LINKATIVACAO>', 'http://'.@$DES_DOMINIO.'.mais.cash/active.do?idC='.fnEncode($cod_cliente), $textoenvio);
            if ($COD_DOMINIO == '1') {
                $textoenvio = str_replace('<#LINKTOKEN>', 'https://' . @$DES_DOMINIO . '.mais.cash/ativacao.do', $textoenvio);
                $textoenvio = str_replace('<#LINKATIVACAO>', 'https://' . @$DES_DOMINIO . '.mais.cash/ativacao.do', $textoenvio);
            }
            if ($COD_DOMINIO == '2') {
                $textoenvio = str_replace('<#LINKTOKEN>', 'https://' . @$DES_DOMINIO . '.fidelidade.mk/ativacao.do', $textoenvio);
                $textoenvio = str_replace('<#LINKATIVACAO>', 'https://' . @$DES_DOMINIO . '.fidelidade.mk/ativacao.do', $textoenvio);
            }

            //$textoenvio = nl2br($textoenvio, true);
            //$textoenvio = str_replace('<br />', ' \n ', $textoenvio);
            $textoenvio = str_replace("'", "", $textoenvio);

            if (strlen($num_celular) == 12) {
                $inicio = substr($num_celular, 0, 4);
                $fim = substr($num_celular, 4, 10);
                $tel = $inicio . '9' . $fim;
            } else {
                $tel = fnLimpaDoc($num_celular);
            }
            //$tel = "55".$tel;
            $rsemail_fila["NUM_CELULAR"] = $tel;

            $desc_cliente = "CLIENTE $cod_cliente / CELULAR $tel ";
            //fnLog(array("DESCRICAO"=>"CLIENTE $cod_cliente / CELULAR $tel / controle = $tip_controle)","COD_EMPRESA"=>$cod_empresa,"COD_GATILHO"=>$cod_gatilho,"TIP_GATILHO"=>$tip_gatilho, "COD_CAMPANHA" => $cod_campanha));

            //Checa se está preenchido
            if (trim($tel) == "") {
                $msg_env .= "<br>" . "$desc_cliente - Celular não preenchido!";
                //fnLog(array("DESCRICAO"=>"$desc_cliente - Celular não preenchido!","COD_EMPRESA"=>$cod_empresa,"COD_GATILHO"=>$cod_gatilho,"TIP_GATILHO"=>$tip_gatilho, "COD_CAMPANHA" => $cod_campanha));
                continue;
            }

            $where = "";
            if ($tip_gatilho == 'cadastro' || $tip_gatilho == 'resgate' || $tip_gatilho == 'venda') {
                $where .= "AND TIP_GATILHO IN ('cadastro','resgate','venda')";
            } else {
                $where .= "AND TIP_GATILHO IN ('$tip_gatilho')";
            }

            $enviar = "S";
            if ($tip_controle == 7) {
                //1 vez na semana
                //Checa se já foi enviado essa semana
                $sql = "SELECT COUNT(0) QTD FROM email_filavalidades
							WHERE NUM_CELULAR = '$num_celular_ori'
							AND WEEK(DT_CADASTR)=WEEK('" . $dat_agendamento . "')
							AND YEAR(DT_CADASTR)=YEAR('" . $dat_agendamento . "')
							AND COD_EMPRESA=$cod_empresa
							$where";
                $rs = mysqli_query($contemporaria, $sql);
                $linha = mysqli_fetch_assoc($rs);
                if ($linha["QTD"] > 0) {
                    $msg_env .= "<br>" . "$desc_cliente - WHATSAPP já enviado esta SEMANA!";
                    //fnLog(array("DESCRICAO"=>"$desc_cliente - WHATSAPP já enviado esta SEMANA!","COD_EMPRESA"=>$cod_empresa,"SQL"=>$sql,"ERRO"=>mysqli_error($contemporaria),"COD_GATILHO"=>$cod_gatilho,"TIP_GATILHO"=>$tip_gatilho, "COD_CAMPANHA" => $cod_campanha));
                    continue;
                }
            } elseif ($tip_controle == 15) {
                //1 vez a cada 15 dias
                //Checa se já foi enviado nos últimos 15 dias
                $sql = "SELECT COUNT(0) QTD FROM email_filavalidades
							WHERE NUM_CELULAR = '$num_celular_ori'
							AND DATE(DT_CADASTR) > DATE(DATE_ADD('" . $dat_agendamento . "', INTERVAL -13 DAY))
							AND COD_EMPRESA=$cod_empresa
							$where";
                $rs = mysqli_query($contemporaria, $sql);
                $linha = mysqli_fetch_assoc($rs);
                if ($linha["QTD"] > 0) {
                    $msg_env .= "<br>" . "$desc_cliente - WHATSAPP já enviado dentro de 15 DIAS!";
                    //fnLog(array("DESCRICAO"=>"$desc_cliente - WHATSAPP já enviado dentro de 15 DIAS!","COD_EMPRESA"=>$cod_empresa,"SQL"=>$sql,"ERRO"=>mysqli_error($contemporaria),"COD_GATILHO"=>$cod_gatilho,"TIP_GATILHO"=>$tip_gatilho, "COD_CAMPANHA" => $cod_campanha));
                    continue;
                }
            } elseif ($tip_controle == 30) {
                //1 vez no mês
                //Checa se já foi enviado esse mes
                $sql = "SELECT COUNT(0) QTD FROM email_filavalidades
							WHERE NUM_CELULAR = '$num_celular_ori'
							AND MONTH(DT_CADASTR)=MONTH('" . $dat_agendamento . "')
							AND YEAR(DT_CADASTR)=YEAR('" . $dat_agendamento . "')
							AND COD_EMPRESA=$cod_empresa
							$where";
                $rs = mysqli_query($contemporaria, $sql);
                $linha = mysqli_fetch_assoc($rs);
                if ($linha["QTD"] > 0) {
                    $msg_env .= "<br>" . "$desc_cliente - WHATSAPP já enviado este MÊS!";
                    //fnLog(array("DESCRICAO"=>"$desc_cliente - WHATSAPP já enviado este MÊS!","COD_EMPRESA"=>$cod_empresa,"SQL"=>$sql,"ERRO"=>mysqli_error($contemporaria),"COD_GATILHO"=>$cod_gatilho,"TIP_GATILHO"=>$tip_gatilho, "COD_CAMPANHA" => $cod_campanha));
                    continue;
                }
            } elseif ($tip_controle != 99) {
                //1 vez no dia
                //Checa se já foi enviado hoje
                $sql = "SELECT COUNT(0) QTD FROM email_filavalidades
							WHERE NUM_CELULAR = '$num_celular_ori'
							AND DATE(DT_CADASTR)=DATE('" . $dat_agendamento . "')
							AND COD_EMPRESA=$cod_empresa
							$where";
                $rs = mysqli_query($contemporaria, $sql);
                $linha = mysqli_fetch_assoc($rs);
                if ($linha["QTD"] > 0) {
                    $msg_env .= "<br>" . "$desc_cliente - WHATSAPP já enviado este HOJE!";
                    //fnLog(array("DESCRICAO"=>"$desc_cliente - WHATSAPP já enviado HOJE!","COD_EMPRESA"=>$cod_empresa,"SQL"=>$sql,"ERRO"=>mysqli_error($contemporaria),"COD_GATILHO"=>$cod_gatilho,"TIP_GATILHO"=>$tip_gatilho, "COD_CAMPANHA" => $cod_campanha));
                    continue;
                }
            }

            $qtd_lote++;
            if ($qtd_lote > $limite_envio) {
                $lote++;
                $qtd_lote = 1;
            }
            $cods_cliente .= "," . $rsemail_fila["COD_CLIENTE"];
            $cods_cliente_l[$lote] .= (@$cods_cliente_l[$lote] != "" ? "," : "") . $rsemail_fila["COD_CLIENTE"];
            $CLIE_CAD_L[$lote][] = $rsemail_fila;
            $dat_envio = date("Y-m-d H:i:s", strtotime("+ 1 minutes"));
            $nom_camp_msg = $cod_campanha . '||' . $cod_empresa . '||' . $cod_cliente . '||' . $cod_template;
            $qtd_total_envio++;


            /*****************************************************************************************************************/
            //$tel = "11999988887";
            $CLIE_WHATSAPP_L[] = array(
                "COD_CLIENTE" => $rsemail_fila["COD_CLIENTE"],
                "from" => $DES_CLIEXT,
                "to" => '55' . fnLimpaDoc($tel),
                "mensagem" => $textoenvio,
                "DataAgendamento" => $rsemail_fila["DT_CADASTR"],
                "Codigointerno" => base64_encode($nom_camp_msg),
                "Imagem" => $des_imagem
            );
        }

        fnLog(array("DESCRICAO" => "<b>Alertas:</b>" . ($msg_env == "" ? "<br>Nenhum alerta" : $msg_env), "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));

        if (count($CLIE_WHATSAPP_L) <= 0) {
            fnLog(array("DESCRICAO" => "Sem dados para serem enviados!", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha, "LAYOUT" => "text-warning"));
            continue;
        }

        $qt_envio_real = 0;
        $qt_lotes_processados = 0;
        fnLog(array("DESCRICAO" => "<b>Qtd. Mensagens Geradas:</b> " . count($CLIE_WHATSAPP_L), "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha, "QTD_FILA" => $qtd_total_envio));
        //print_r($CLIE_WHATSAPP_L);
        //exit;
        foreach ($CLIE_WHATSAPP_L as $k => $CLIE_WHATSAPP) {

            //Checa se campanha ainda está ativa
            $sql = "SELECT gt.LOG_STATUS,cp.LOG_ATIVO FROM gatilho_whatsapp gt"
                . " INNER JOIN campanha cp ON gt.COD_CAMPANHA=cp.COD_CAMPANHA"
                . " WHERE gt.COD_CAMPANHA=0$cod_campanha";
            $rs = mysqli_query($contemporaria, $sql);
            $linha = mysqli_fetch_assoc($rs);
            if ($linha["LOG_ATIVO"] <> "S") {
                fnLog(array("DESCRICAO" => "Esta campanha foi desativada!", "COD_EMPRESA" => $cod_empresa, "SQL" => $sql, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha, "LAYOUT" => "text-danger"));
                break;
            } elseif ($linha["LOG_STATUS"] <> "S") {
                fnLog(array("DESCRICAO" => "Este gatilho foi desativado!", "COD_EMPRESA" => $cod_empresa, "SQL" => $sql, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha, "LAYOUT" => "text-danger"));
                break;
            }
            fnLog(array("DESCRICAO" => "Campanha/gatilhos ativos!", "COD_EMPRESA" => $cod_empresa, "SQL" => $sql, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));




            sleep(1);
            $linhas = count($CLIE_CAD);
            $qtd_lote = count($CLIE_WHATSAPP);

            $cod_lote = $k + 1;
            fnLog(array("DESCRICAO" => "Lote: $cod_lote", "COD_EMPRESA" => $cod_empresa, "JSON" => json_encode($CLIE_WHATSAPP), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));

            //fnLog(array("DESCRICAO"=>"[ ROTINA PAUSADA ]","COD_EMPRESA"=>$cod_empresa, "COD_CAMPANHA" => $cod_campanha));continue;

            $PERMITENEGATIVO = 'N';
            $CONFIRMACAO = 'S';
            //Contabiliza debitos
            $arraydebitos = array(
                'quantidadeEmailenvio' => $linhas,
                'COD_EMPRESA' => $cod_empresa,
                'PERMITENEGATIVO' => $PERMITENEGATIVO,
                'COD_CANALCOM' => '2',
                'CONFIRMACAO' => $CONFIRMACAO,
                'COD_CAMPANHA' => $cod_campanha,
                'LOG_TESTE' => 'N',
                'DAT_CADASTR' => date('Y-m-d H:i:s'),
                'CONNADM' => $connAdm->connAdm(),
            );

            /*$saldo = SaldoNexux($rsempresa["DES_AUTHKEY2"]);
            fnLog(array("DESCRICAO" => "Consultando Saldo NEXUX: " . $saldo . " / Qtd Envio.: " . $linhas . " / Saldo após envio: " . ($saldo - $linhas), "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));
            if ($saldo < $linhas) {
            fnLog(array("DESCRICAO" => "Sem saldo na NEXUX para envio de lote", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));
            continue;
            }*/

            $consulta_saldo = FnDebitos($arraydebitos, true);
            fnLog(array("DESCRICAO" => "Consultando Saldo: " . $consulta_saldo["saldorestante"] . " / Qtd Envio.: " . $consulta_saldo["qtd_envio"] . " / Saldo após envio: " . $consulta_saldo["saldo_apos_envio"], "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "JSON" => json_encode($consulta_saldo), "COD_CAMPANHA" => $cod_campanha));
            if (!$consulta_saldo["tem_saldo"]) {
                //Subtrai do total
                $tot_ori = $qtd_total_envio;
                $qtd_total_envio = $qtd_total_envio - $qtd_lote;
                fnLog(array("DESCRICAO" => "Sem saldo para envio de lote - subtraindo do total: ($tot_ori >> " . ($qtd_total_envio) . ")", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha, "LAYOUT" => "text-danger"));
                continue;
            }
            /*$debitos=FnDebitos($arraydebitos);
            fnLog(array("DESCRICAO"=>"Consultando Débitos","COD_EMPRESA"=>$cod_empresa,"JSON"=>json_encode($debitos),"COD_GATILHO"=>$cod_gatilho,"TIP_GATILHO"=>$tip_gatilho, "COD_CAMPANHA" => $cod_campanha));
            if (@$debitos["cod_mensagem"] = 3 && $PERMITENEGATIVO != "S"){
            if ($debitos["cod_msg"] == 3 || $debitos["cod_msg"] == 5){
            fnLog(array("DESCRICAO"=>"Não foi possível enviar","COD_EMPRESA"=>$cod_empresa,"ERRO"=>$debitos["MSG"],"COD_GATILHO"=>$cod_gatilho,"TIP_GATILHO"=>$tip_gatilho, "COD_CAMPANHA" => $cod_campanha));
            continue;
            }
            }*/

            $sql = "SELECT NUM_CONTADOR FROM contador WHERE NUM_TKT=50";
            $rwcont = mysqli_query($contemporaria, $sql);
            $lcont = mysqli_fetch_assoc($rwcont);

            fnLog(array("DESCRICAO" => "Gera Contador", "COD_EMPRESA" => $cod_empresa, "SQL" => $sql, "ERRO" => mysqli_error($contemporaria), "JSON" => json_encode($lcont), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));
            if (mysqli_error($contemporaria) != "") {
                continue;
            }

            if ($lcont["NUM_CONTADOR"] == "") {
                $sql = "INSERT INTO contador (COD_ORCAMENTO, DES_CONTADOR, NUM_TKT, NUM_EXPIRA) VALUES ('50', 'COMUNICACAO', '50', '50')";
                $rwinsert = mysqli_query($contemporaria, $sql);

                fnLog(array("DESCRICAO" => "Erro ao inserir na tabela contador", "COD_EMPRESA" => $cod_empresa, "SQL" => $sql, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha, "LAYOUT" => "text-danger"));
                if (mysqli_error($contemporaria) != "") {
                    continue;
                }

                $sql = "SELECT NUM_CONTADOR FROM contador WHERE NUM_TKT=50";
                $rwcont = mysqli_query($contemporaria, $sql);
                $lcont = mysqli_fetch_assoc($rwcont);

                fnLog(array("DESCRICAO" => "Gera Contador", "COD_EMPRESA" => $cod_empresa, "SQL" => $sql, "ERRO" => mysqli_error($contemporaria), "JSON" => json_encode($lcont), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha, "LAYOUT" => "text-danger"));
                if (mysqli_error($contemporaria) != "") {
                    continue;
                }
            }

            if (@$lcont["NUM_CONTADOR"] == "") {
                //Subtrai do total
                $qtd_total_envio = $qtd_total_envio - $qtd_lote;
                fnLog(array("DESCRICAO" => "Não foi possível obter contador! - subtraindo do total: ($qtd_total_envio >> " . ($qtd_total_envio - $qtd_lote) . ")", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha, "LAYOUT" => "text-danger"));
                continue;
            }

            $disparo = $cod_empresa . "," . $cod_campanha . "," . $html["COD_TEMPLATE"] . "," . $lcont["NUM_CONTADOR"];
            $retornoWHATSAPP = array();


            $msgsbtr = $CLIE_WHATSAPP["mensagem"];
            fnLog(array("DESCRICAO" => "Iniciando Disparo de WHATSAPP", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha, "JSON" => json_encode([
                "session" => $session,
                "des_authkey" => $des_authkey,
                "to" => $CLIE_WHATSAPP["to"],
                "mensagem" => $msgsbtr,
                "array" => $CLIE_WHATSAPP,
                "port" => $port,
            ])));

            $base64 = base64_encode($rsempresa['DES_USUARIO'] . ':' . $rsempresa['DES_AUTHKEY']);

            $session = $rsempresa['NOM_SESSAO'];
            $des_token = $rsempresa['DES_TOKEN'];
            $des_authkey = $rsempresa['DES_AUTHKEY'];
            $log_login = $rsempresa['LOG_LOGIN'];
            $port = $rsempresa['PORT_SERVICAO'];

            try {
                //$retornoWHATSAPP = whatsapp_twilo($base64, $CLIE_WHATSAPP, $rsempresa['DES_USUARIO'], $rsempresa['DES_AUTHKEY']);
                $retornoWHATSAPP = FnsendText($session, $des_authkey, $CLIE_WHATSAPP["to"], $msgsbtr, 3, $port);
            } catch (Exception $e) {
                //Subtrai do total
                $qtd_total_envio = $qtd_total_envio - $qtd_lote;
                fnLog(array("DESCRICAO" => "<b>Não Enviado</b> -  Par&acirc;metros de Disparo: " . $disparo, "COD_EMPRESA" => $cod_empresa, "JSON" => json_encode($retornoWHATSAPP), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "ERRO" => $e->getMessage(), "COD_CAMPANHA" => $cod_campanha, "LAYOUT" => "text-danger"));
                continue;
            }
            /*  print_r($CLIE_WHATSAPP);
            print_r($retornoWHATSAPP);
            exit;*/
            $sqlControle = "INSERT INTO log_nuxux(
							COD_EMPRESA,
							DAT_CADASTR,
							TIP_LOG,
							LOG_JSON
					)VALUES(
							0" . $cod_empresa . ",
							NOW(),
							2,
							'" . str_replace('"', '', str_replace("'", '', json_encode($retornoWHATSAPP))) . "')";
            fnLog(array("DESCRICAO" => "Insere na tabela log_nuxux", "COD_EMPRESA" => $cod_empresa, "SQL" => $sqlControle, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));
            $rwinsert = mysqli_query($contemporaria, $sqlControle);
            if (mysqli_error($contemporaria) != "") {
                fnLog(array("DESCRICAO" => "Erro ao inserir na tabela log_nuxux", "COD_EMPRESA" => $cod_empresa, "SQL" => $sqlControle, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));
                continue;
            }

            fnLog(array("DESCRICAO" => "Disparo de WHATSAPP (EnvioSms) / Par&acirc;metros de Disparo: " . $disparo, "COD_EMPRESA" => $cod_empresa, "JSON" => json_encode($retornoWHATSAPP), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));

            fnLog(array("DESCRICAO" => "Rotina WHATSAPP processada!", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "JSON" => json_encode($retornoWHATSAPP), "COD_CAMPANHA" => $cod_campanha));

            if (@$retornoWHATSAPP["status"] <> 400) {
                $qt_lotes_processados++;
                $debitos = FnDebitos($arraydebitos);
                fnLog(array("DESCRICAO" => "Efetuando Débitos", "COD_EMPRESA" => $cod_empresa, "JSON" => json_encode($debitos), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));

                fnLog(array("DESCRICAO" => "<b>Enviado</b>", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha, "QTD_ENVIOS" => $qtd_lote, "LAYOUT" => "text-info"));

                /*MARCA GATILHO COMO EXECUTADO*********************************************************************************/
                $sql = "UPDATE gatilho_whatsapp SET LOG_PROCESS_GATILHO='N',DATHOR_PROCESS=NOW() WHERE COD_GATILHO=$cod_gatilho AND COD_EMPRESA=$cod_empresa";
                $rs = mysqli_query($contemporaria, $sql);
                fnLog(array("DESCRICAO" => "Gatilho marcado como executado!", "SQL" => $sql, "COD_EMPRESA" => $cod_empresa, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));

                $sql = "UPDATE contador SET NUM_CONTADOR=IFNULL(NUM_CONTADOR,1)+1 WHERE NUM_TKT=50";
                mysqli_query($contemporaria, $sql);
                if (mysqli_error($contemporaria) != "") {
                    fnLog(array("DESCRICAO" => "Erro ao gravar na tabela contador", "COD_EMPRESA" => $cod_empresa, "SQL" => $sql, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha, "LAYOUT" => "text-danger"));
                    continue;
                }

                fnLog(array("DESCRICAO" => "Vai entrar na rotina de SUCESSO! Total de envios: " . count($retornoWHATSAPP), "COD_EMPRESA" => $cod_empresa, "SQL" => $sqlControle, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));

                $insere = "";
                //foreach ($retornoWHATSAPP as $key => $cliente) {
                //if (@$cliente["code"] == "") {
                // $CHAVE_GERAL = "";
                // $CHAVE_CLIENTE = "";
                $msgenvio = @$CLIE_WHATSAPP["mensagem"];

                $cod_cliente = @$CLIE_WHATSAPP["COD_CLIENTE"];
                $celular = @$CLIE_WHATSAPP["to"];
                $idDisparo = date('Ymd');
                $TEXTOENVIO = @$CLIE_WHATSAPP["mensagem"];
                $CHAVE_CLIENTE = @$cliente["sid"];
                $CHAVE_GERAL = @$cliente["account_sid"];
                $statusreturn = $cliente["status"];
                $statusreturn = $statusreturn ?: 400;

                $sqlControle = "INSERT INTO whatsapp_lista_ret (
												COD_EMPRESA,
												COD_CAMPANHA,
												NOM_CLIENTE,
												COD_UNIVEND,
												COD_CLIENTE,
												NUM_CELULAR,
												STATUS_ENVIO,
												ID_DISPARO,
												DES_MSG_ENVIADA	,
												CHAVE_GERAL,
												CHAVE_CLIENTE,
												DAT_CADASTR,
												LOG_TESTE,
												DES_STATUS,
												idContatosMailing
												) VALUES (
												'" . $cod_empresa . "',
												'" . $cod_campanha . "',
												IFNULL((SELECT NOM_CLIENTE FROM CLIENTES WHERE COD_EMPRESA = 0" . $cod_empresa . " AND COD_CLIENTE = 0" . $cod_cliente . "),0),
												IFNULL((SELECT COD_UNIVEND FROM email_fila WHERE COD_EMPRESA  = 0" . $cod_empresa . " AND COD_CLIENTE = '0" . $cod_cliente . "' AND TIP_GATILHO = '$tip_gatilho' ORDER BY 1 DESC LIMIT 1),0),
												'0" . $cod_cliente . "',
												'" . $celular . "',
												'S',
												'" . $idDisparo . "',
												'" . $TEXTOENVIO . "',
												'" . $CHAVE_GERAL . "',
												'" . $CHAVE_CLIENTE . "',
												NOW(),
												'N',
												'" . $statusreturn . "',
												'" . $rsempresa['COD_PARCOMU'] . "'
												)";
                $insere .= PHP_EOL . PHP_EOL . $sqlControle . ";";
                //fnLog(array("DESCRICAO"=>"Insere na whatsapp_lista_ret","COD_EMPRESA"=>$cod_empresa,"SQL"=>$sqlControle,"COD_GATILHO"=>$cod_gatilho,"TIP_GATILHO"=>$tip_gatilho, "COD_CAMPANHA" => $cod_campanha));
                mysqli_query($contemporaria, $sqlControle);
                if (mysqli_error($contemporaria) != "") {
                    fnLog(array("DESCRICAO" => "Erro SQL ao inserir na whatsapp_lista_ret!", "COD_EMPRESA" => $cod_empresa, "SQL" => $sqlControle, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha, "LAYOUT" => "text-danger"));
                    // continue;
                }
                $qt_envio_real++;
                //}
                //}

                if ($insere != "") {
                    fnLog(array("DESCRICAO" => "Insere na whatsapp_lista_ret", "COD_EMPRESA" => $cod_empresa, "SQL" => $insere, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));
                }

                $rsemail_fila["COD_ENVIADO"] = "S";
                $sql = "INSERT INTO email_filavalidades (
							DT_CADASTR,COD_EMPRESA,COD_UNIVEND,COD_CLIENTE,NUM_CGCECPF,NOM_CLIENTE,DT_NASCIME,
							DES_EMAILUS,NUM_CELULAR,VAL_RESGATE,VAL_CRED_ACUMULADO,COD_SEXOPES,COD_CAMPANHA,TIP_MOMENTO,
							TIP_CONTROLE,TIP_FILA,TIP_GATILHO,VAL_EXPIRAR,DAT_EXPIRA,SEMANA,MES,COD_ENVIADO
						) (
						SELECT DISTINCT
							'$dat_agendamento',COD_EMPRESA,COD_UNIVEND,COD_CLIENTE,NUM_CGCECPF,NOM_CLIENTE,DT_NASCIME,
							'' DES_EMAILUS,NUM_CELULAR,VAL_RESGATE,VAL_CRED_ACUMULADO,COD_SEXOPES,COD_CAMPANHA,TIP_MOMENTO,
							TIP_CONTROLE,TIP_FILA,TIP_GATILHO,VAL_EXPIRAR,DAT_EXPIRA,SEMANA,MES,'S' COD_ENVIADO
						FROM email_fila
						WHERE
							TIP_GATILHO='$tip_gatilho' AND
							COD_EMPRESA=$cod_empresa AND
							COD_CAMPANHA=$cod_campanha AND
							COD_CLIENTE IN (" . $cods_cliente_l[$cod_lote] . ")
						)";
                fnLog(array("DESCRICAO" => "Insere na email_filavalidades", "COD_EMPRESA" => $cod_empresa, "SQL" => $sql, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));
                mysqli_query($contemporaria, $sql);
                if (mysqli_error($contemporaria) != "") {
                    fnLog(array("DESCRICAO" => "Erro SQL ao inserir na email_filavalidades!", "COD_EMPRESA" => $cod_empresa, "SQL" => $sql, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha, "LAYOUT" => "text-danger"));
                    // continue;
                }

                $sql = "UPDATE email_fila SET COD_ENVIADO='S',DT_ALTERAC=NOW()
							WHERE
								TIP_FILA IN (5,13,14,15) AND
								TIP_GATILHO='$tip_gatilho' AND
								COD_EMPRESA=$cod_empresa AND
								COD_CAMPANHA=$cod_campanha AND
								COD_ENVIADO='N' AND
								COD_CLIENTE IN (0" . $CLIE_WHATSAPP["COD_CLIENTE"] . ")";
                fnLog(array("DESCRICAO" => "Update na tabela email_fila", "COD_EMPRESA" => $cod_empresa, "SQL" => $sql, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));
                mysqli_query($contemporaria, $sql);
                if (mysqli_error($contemporaria) != "") {
                    fnLog(array("DESCRICAO" => "Erro SQL ao fazer update na email_fila!", "COD_EMPRESA" => $cod_empresa, "SQL" => $sql, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha, "LAYOUT" => "text-danger"));

                    // Aguarda e tenta novamente
                    sleep(3);
                    fnLog(array("DESCRICAO" => "Nova tentativa de update na tabela email_fila", "COD_EMPRESA" => $cod_empresa, "SQL" => $sql, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha, "LAYOUT" => "text-warning"));
                    mysqli_query($contemporaria, $sql);
                    if (mysqli_error($contemporaria) != "") {
                        fnLog(array("DESCRICAO" => "Erro SQL ao fazer update na email_fila!", "COD_EMPRESA" => $cod_empresa, "SQL" => $sql, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha, "LAYOUT" => "text-danger"));

                        // Aguarda e tenta novamente
                        sleep(10);
                        fnLog(array("DESCRICAO" => "Nova tentativa (2) de update na tabela email_fila", "COD_EMPRESA" => $cod_empresa, "SQL" => $sql, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha, "LAYOUT" => "text-warning"));
                        mysqli_query($contemporaria, $sql);
                        if (mysqli_error($contemporaria) != "") {
                            fnLog(array("DESCRICAO" => "Erro SQL ao fazer update na email_fila!", "COD_EMPRESA" => $cod_empresa, "SQL" => $sql, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha, "LAYOUT" => "text-danger"));
                        }
                    }
                }
            } else {
                fnLog(array("DESCRICAO" => "<b>Não Enviado</b>", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "ERRO" => json_encode($retornoWHATSAPP), "JSON" => json_encode($retornoWHATSAPP), "COD_CAMPANHA" => $cod_campanha, "LAYOUT" => "text-danger"));
            }
        }

        /*
        //COMENTADO PARA NÃO ENVIAR RETROATIVO CASO DE ERRO - DEFINIDO EM 19/12/2021
        $sql = "UPDATE email_fila SET COD_ENVIADO='X',DT_ALTERAC=NOW()
        WHERE
        TIP_FILA IN (5,13,14,15) AND
        TIP_GATILHO='$tip_gatilho' AND
        COD_EMPRESA=$cod_empresa AND
        COD_CAMPANHA=$cod_campanha AND
        COD_ENVIADO='N' AND
        DATE(DT_CADASTR) < DATE(DATE_ADD(NOW(), INTERVAL -".$des_periodo." DAY))";
        */
        $sql = "UPDATE email_fila SET COD_ENVIADO='X',DT_ALTERAC=NOW()
		WHERE
			TIP_FILA IN (5,13,14,15) AND
			TIP_GATILHO='$tip_gatilho' AND
			COD_EMPRESA=$cod_empresa AND
			COD_CAMPANHA=$cod_campanha AND
			COD_ENVIADO='N'";
        fnLog(array("DESCRICAO" => "Ajusta tabela email_fila para não enviar retroativo.", "COD_EMPRESA" => $cod_empresa, "SQL" => $sql, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));
        mysqli_query($contemporaria, $sql);
        if (mysqli_error($contemporaria) != "") {
            fnLog(array("DESCRICAO" => "Erro ao fazer update na tabela email_fila", "COD_EMPRESA" => $cod_empresa, "SQL" => $sql, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha, "LAYOUT" => "text-danger"));
            // continue;
        }

        //if ($qtd_total_envio > 0 && $qt_lotes_processados > 0){
        if ($qt_envio_real > 0) {
            $sql = "SELECT COUNT(0) QTD FROM WHATSAPP_LOTE WHERE COD_DISPARO_EXT='" . date("Ymd") . "' AND COD_CAMPANHA='0" . $cod_campanha . "' AND COD_EMPRESA='0" . $cod_empresa . "'";
            $rs = mysqli_query($contemporaria, $sql);
            $linha = mysqli_fetch_assoc($rs);
            fnLog(array("DESCRICAO" => "Checa se já tem lote na tabela whatsapp_lote", "JSON" => json_encode($linha), "COD_EMPRESA" => $cod_empresa, "SQL" => $sql, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));
            if (@$linha["QTD"] <= 0) {

                $sqlControle = "INSERT INTO WHATSAPP_LOTE(
					COD_DISPARO_EXT,
					COD_GERACAO,
					COD_CAMPANHA,
					COD_EMPRESA,
					COD_LOTE,
					COD_PERSONAS,
					QTD_LISTA,
					COD_LISTA,
					COD_EXT_TEMPLATE,
					DAT_AGENDAMENTO,
					LOG_ENVIO,
					LOG_TESTE,
					NOM_ARQUIVO,
					DES_PATHARQ,
					COD_USUCADA,
					DAT_CADASTR,
					QTD_REENVIO,
					COD_STATUS
			    )VALUES(
					'" . date("Ymd") . "',
					'0',
					'0" . $cod_campanha . "',
					'0" . $cod_empresa . "',
					'0',
					'0" . $cod_personas . "',
					'0" . $qtd_total_envio . "',
					'0" . $cod_lista . "',
					" . ($html["COD_EXT_TEMPLATE"] != "" ? $html["COD_EXT_TEMPLATE"] : "NULL") . ",
					'" . $dat_envio . "',
					'S',
					'N',
					'$nomeRel',
					'" . $arquivodinamize . "',
					9999,
					'" . date('Y-m-d H:i:s') . "',
					'0',
					'1')";
                mysqli_query($contemporaria, $sqlControle);
                fnLog(array("DESCRICAO" => "Não tem lote. Inserindo na tabela whatsapp_lote", "JSON" => json_encode($retornoWHATSAPP), "COD_EMPRESA" => $cod_empresa, "SQL" => $sqlControle, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));
                if (mysqli_error($contemporaria) != "") {
                    fnLog(array("DESCRICAO" => "Erro SQL ao inserir na whatsapp_lote", "COD_EMPRESA" => $cod_empresa, "SQL" => $sql, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha, "LAYOUT" => "text-danger"));
                    // continue;
                }
            } else {
                fnLog(array("DESCRICAO" => "Já tem lote na tabela whatsapp_lote", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));
            }
        } else {
            fnLog(array("DESCRICAO" => "Nada foi enviado nada deste lote!", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha, "LAYOUT" => "text-warning"));
        }
    }


    $sql = "DELETE FROM controle_envio WHERE COD_COMUNICACAO=22 AND COD_EMPRESA = $cod_empresa";
    $arrayQuery = mysqli_query($conadmin, $sql);
    fnLog(array("DESCRICAO" => "Libera a fila da empresa $cod_empresa!", "COD_EMPRESA" => $cod_empresa, "SQL" => $sql, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha, "ERRO" => mysqli_error($contemporaria), "LAYOUT" => "text-warning"));
}

fnLog(array("DESCRICAO" => "Fim da Rotina", "LAYOUT" => "text-success"));
echo "</pre>";

$sql = "UPDATE gatilhos_logs_exec SET DATAHORA_FIM=NOW() WHERE UID='" . $uuid . "' AND COD_EXECUCAO='" . str_replace("-", "", str_replace(":", "", str_replace(" ", "", $ini_rotina))) . "'";
$rs = mysqli_query($conadmin, $sql);
mysqli_close($conadmin);
mysqli_close($contemporaria);
