<?php
require '../../_system/_functionsMain.php';
require '../../_system/func_push/onsignal.php';
/*
ini_set('display_errors', 1);
ini_set('display_startup_erros', 1);
error_reporting(E_ALL);
 */
$gera_log = "S";
$conadmin = $connAdm->connAdm();

$datahoraatual = date('Y-m-d H:i:s');
$horaatual = date("H");
$minutoatual = date("i");
$uuid = md5(uniqid(rand(), true));

$ini_rotina = $datahoraatual;
$sequencia = 0;

//$sql = "SELECT COUNT(0) QTD,TIMESTAMPDIFF(MINUTE,MAX(DATAHORA_INICIO),NOW()) TEMPO FROM gatilhos_logs_exec WHERE TIPO='PUSH' AND DATAHORA_ATUALIZACAO_EMPRE IS NULL";
//$rs = mysqli_query($conadmin, $sql);
//$linha = mysqli_fetch_assoc($rs);
//if ($linha["QTD"] > 0 && $linha["TEMPO"] <= 5) {
//    fnLog(array("DESCRICAO" => "[ EXISTE UMA ROTINA AINDA EM EXECUÇÃO INICIADA A POUCO TEMPO ]"));
//    exit;
//}

$sql = "INSERT INTO gatilhos_logs_exec (UID,COD_EXECUCAO,TIPO,DATAHORA_INICIO,CODS_EMPRESA) VALUES "
    . "('" . $uuid . "','" . str_replace("-", "", str_replace(":", "", str_replace(" ", "", $ini_rotina))) . "','PUSH_GENERICO',NOW(),0)";
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
					'PUSH_GENERICO',
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
if (@$_GET["COD_EMPRESA"] != "") {
    $where .= "AND apar.COD_EMPRESA in (0" . @$_GET["COD_EMPRESA"] . ")";
}

/*EMPRESAS************************************************************************************************************************/
$sqlempresa = "SELECT * FROM senhas_parceiro apar
				INNER JOIN parceiro_comunicacao par ON par.COD_PARCOMU=apar.COD_PARCOMU
				WHERE par.COD_TPCOM='5' AND apar.COD_PARCOMU='18' AND apar.LOG_ATIVO='S'
				$where
				";

$rwempresa = mysqli_query($conadmin, $sqlempresa);
fnLog(array("DESCRICAO" => "Obter lista das Empresas", "SQL" => $sqlempresa, "ERRO" => mysqli_error($conadmin)));
$count_empre = 0;
$tot_empre = mysqli_num_rows($rwempresa);
while ($rsempresa = mysqli_fetch_assoc($rwempresa)) {
    $des_authkey = $rsempresa['DES_AUTHKEY'];
    $des_usuario = $rsempresa['DES_USUARIO'];
    $des_authkey_ios = $rsempresa['COD_LISTAEXT'];
    $des_usuario_ios = $rsempresa['DES_CLIEXT'];

    $cod_empresa = $rsempresa['COD_EMPRESA'];
    $contemporaria = connTemp($cod_empresa, '');
    $DES_CLIEXT = $rsempresa['DES_CLIEXT'];
    $count_empre++;

    $sql = "UPDATE gatilhos_logs_exec SET DATAHORA_ATUALIZACAO_EMPRE=NOW(), CODS_EMPRESA=CONCAT(CODS_EMPRESA,',',$cod_empresa) WHERE UID='" . $uuid . "' AND COD_EXECUCAO='" . str_replace("-", "", str_replace(":", "", str_replace(" ", "", $ini_rotina))) . "'";
    $rs = mysqli_query($conadmin, $sql);

    fnLog(array("DESCRICAO" => "Empresa $cod_empresa ( $count_empre / $tot_empre )", "COD_EMPRESA" => $cod_empresa, "JSON" => json_encode($rsempresa)));


    $sql = "SELECT TIP_RETORNO FROM EMPRESAS WHERE COD_EMPRESA = $cod_empresa";
    $arrayQuery = mysqli_query($conadmin, $sql);
    $qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
    $tip_retorno = $qrBuscaEmpresa['TIP_RETORNO'];

    if ($tip_retorno == 1) {
        $casasDec = 0;
    } else {
        $casasDec = 2;
    }

    /*GATILHO************************************************************************************************************************/
    $gatilhos = array("individualB"); // <-- Colocar na ordem do select
    if (@$_GET["TIP_GATILHO"] != "") {
        $gatilhos = array($_GET["TIP_GATILHO"]);
    }
    $gatilhos_impl_in = "'" . (implode("','", $gatilhos)) . "'";
    $gatilhos_impl_ord = "'" . (implode(",", $gatilhos)) . "'";
    $sqlgatilho = "SELECT * FROM gatilho_push gt
						INNER JOIN campanha cp ON gt.COD_CAMPANHA=cp.COD_CAMPANHA
						INNER JOIN push_parametros p ON p.COD_EMPRESA=gt.cod_empresa AND p.COD_CAMPANHA=gt.cod_campanha
							AND COD_LISTA IN (SELECT MAX(COD_LISTA) FROM push_parametros WHERE COD_EMPRESA=gt.cod_empresa AND COD_CAMPANHA=gt.cod_campanha)
					WHERE gt.TIP_GATILHO IN ($gatilhos_impl_in)
						AND gt.LOG_STATUS ='S'
						AND cp.LOG_ATIVO = 'S'
						AND STR_TO_DATE(CONCAT(cp.DAT_INI,' ',cp.HOR_INI),'%Y-%m-%d %H:%i:%s') <= NOW()
						AND STR_TO_DATE(CONCAT(cp.DAT_FIM,' ',cp.HOR_FIM),'%Y-%m-%d %H:%i:%s') >= NOW()
						AND gt.cod_empresa=$cod_empresa
						" . (@$_GET["COD_CAMPANHA"] != "" ? " AND gt.COD_CAMPANHA = '0" . $_GET["COD_CAMPANHA"] . "'" : "") . "
					GROUP BY gt.COD_CAMPANHA
					ORDER BY FIND_IN_SET(gt.TIP_GATILHO, $gatilhos_impl_ord)";

    $rwgatilho = mysqli_query($contemporaria, $sqlgatilho);
    fnLog(array("DESCRICAO" => "Obter dados do gatilho", "COD_EMPRESA" => $cod_empresa, "SQL" => $sqlgatilho, "ERRO" => mysqli_error($contemporaria)));
    $count_gati = 0;
    $tot_gati = mysqli_num_rows($rwgatilho);
    if ($tot_gati <= 0) {
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
        $log_processa = $rsgatilho["LOG_PROCESSA_PUSH"];
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

        fnLog(array("DESCRICAO" => "Dados do gatilho $cod_gatilho ( $count_gati / $tot_gati )", "COD_EMPRESA" => $cod_empresa, "JSON" => json_encode($rsgatilho), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));

        //VERIFICA se a campanha está ativa
        if ($log_ativo != 'S') {
            fnLog(array("DESCRICAO" => "Campanha não está ativa", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha, "LAYOUT" => "text-danger"));
            continue;
        }

        //VERIFICA se a campanha está dentro da validade
        if ($datetimecampanha < $datahoraatual) {
            fnLog(array("DESCRICAO" => "Campanha fora da validade", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha, "LAYOUT" => "text-danger"));
            continue;
        }

        //VERIFICA se a campanha está ativa para envio de PUSH
        if ($log_processa != 'S') {
            fnLog(array("DESCRICAO" => "Campanha não habilitada para envio de PUSH!", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha, "LAYOUT" => "text-danger"));
            continue;
        }

        $sql = "DELETE em
						FROM email_fila em
						INNER JOIN unidadevenda un ON un.COD_UNIVEND = em.COD_UNIVEND AND un.LOG_ESTATUS = 'N'
						WHERE em.COD_EMPRESA = $cod_empresa;";
        $rs = mysqli_query($contemporaria, $sql);

        /*INICIO ROTINA DE ENVIO*/
        $tampletevariavel = "SELECT DES_TEMPLATE,COD_TEMPLATE,COD_EXT_TEMPLATE,DES_TITULO FROM TEMPLATE_push T
						INNER JOIN mensagem_push M ON M.COD_TEMPLATE_PUSH=T.COD_TEMPLATE
						WHERE T.COD_EMPRESA='$cod_empresa'
							AND COD_CAMPANHA='$cod_campanha' AND LOG_ATIVO='S'";
        //echo "<pre>$tampletevariavel";exit;

        $html = mysqli_fetch_assoc(mysqli_query($contemporaria, $tampletevariavel));
        fnLog(array("DESCRICAO" => "Carregando Template", "COD_EMPRESA" => $cod_empresa, "SQL" => $tampletevariavel, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));

        //gera lista de variaveis
        $cod_template = $html["COD_TEMPLATE"];

        $selectCliente = "";
        $innerjoin = "";
        $contador = '0';

        $selectCliente .= "";

        $sqlcli_cad = "SELECT DISTINCT C.COD_CLIENTE,email_fila.NUM_CELULAR, cliente_push.TOKEN, cliente_push.VERSAO_SISTEMA
						FROM clientes C
						INNER JOIN email_fila ON (email_fila.COD_CLIENTE = C.COD_CLIENTE)
						INNER JOIN cliente_push ON (cliente_push.COD_CLIENTE = C.COD_CLIENTE)
                        " . ($tip_gatilho == "cadastro" && $des_periodo > 30
            ? ""
            : "INNER JOIN personaclassifica per ON per.COD_CLIENTE=email_fila.COD_CLIENTE AND  per.COD_PERSONA IN (0$cod_personas)"
        ) . "
						$innerjoin
						WHERE C.COD_EMPRESA = $cod_empresa
						AND C.LOG_PUSH = 'S'
						AND C.LOG_FIDELIZADO = 'S'
						AND C.LOG_ESTATUS = 'S'
						AND email_fila.TIP_GATILHO='$tip_gatilho'
						AND email_fila.TIP_FILA IN (14)
						" . ($tip_gatilho == "aniv" || $tip_gatilho == "anivCad" || $tip_gatilho == "credExp" || $tip_gatilho == "inativos" ? "AND DATE(email_fila.DT_CADASTR)=DATE(NOW())" : "") . "
						" . ($tip_gatilho == "cadastro" && $des_periodo <= 30 ? "AND DATE(email_fila.DT_CADASTR) < DATE(NOW())" : "") . "
						" . ($des_periodo == 7 ? "AND (WEEK(email_fila.DT_CADASTR)>=WEEK(DATE_ADD(NOW(), INTERVAL -1 WEEK)) OR WEEK(email_fila.DT_CADASTR)=WEEK(NOW()))" : "") . "
						" . ($des_periodo == 15 ? "AND (WEEK(email_fila.DT_CADASTR)>=WEEK(DATE_ADD(NOW(), INTERVAL -2 WEEK)) OR WEEK(email_fila.DT_CADASTR)=WEEK(NOW()))" : "") . "
						" . ($des_periodo == 30 ? "AND (MONTH(DT_CADASTR)>=MONTH(DATE_ADD(NOW(), INTERVAL -1 MONTH)) OR MONTH(DT_CADASTR)=MONTH(NOW()))" : "") . "
						AND email_fila.COD_EMPRESA=C.COD_EMPRESA
						AND email_fila.COD_CAMPANHA=$cod_campanha
						AND email_fila.COD_ENVIADO='N'
                        group by C.COD_CLIENTE
						" .
            ($tip_gatilho == "venda" ?
                "HAVING (SELECT COALESCE(SUM(E.val_saldo), 0.00) val_saldo FROM creditosdebitos E WHERE E.COD_CLIENTE=C.COD_CLIENTE AND E.COD_STATUSCRED=1) >= $tot_saldomin" :
                ""
            ) .
            " LIMIT 1999 ";

        $rwsql = mysqli_query($contemporaria, $sqlcli_cad);

        fnLog(array("DESCRICAO" => "Carregando clientes... Qtd. Retornado: " . mysqli_num_rows($rwsql), "COD_EMPRESA" => $cod_empresa, "SQL" => $sqlcli_cad, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));

        if (mysqli_num_rows($rwsql) <= 0) {
            fnLog(array("DESCRICAO" => "Sem dados para serem enviados!", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha, "LAYOUT" => "text-warning"));
            continue;
        }

        $CLIE_CAD = array();
        while ($headers = mysqli_fetch_field($rwsql)) {
            $headers1["campos"][$headers->name] = $headers->name;
        }
        $cods_cliente = "0";
        $qtd_total_envio = 0;
        $msg_env = "";

        $CLIE_CAD["DES_MENSAGEM"] = $html['DES_TEMPLATE'];
        $CLIE_CAD["DES_TITULO"] = $html['DES_TITULO'];

        while ($rsemail_fila = mysqli_fetch_assoc($rwsql)) {
            $CLIE_CAD["TOKEN"][$rsemail_fila["VERSAO_SISTEMA"]][] = $rsemail_fila["TOKEN"];
            $CLIE_CAD["COD_CLIENTE"][$rsemail_fila["VERSAO_SISTEMA"]][] = $rsemail_fila["COD_CLIENTE"];
            $qtd_total_envio++;
        }


        $sql = "UPDATE email_fila SET COD_ENVIADO='E',DT_ALTERAC=NOW()
							WHERE
								TIP_FILA IN (14) AND
								TIP_GATILHO='$tip_gatilho' AND
								COD_EMPRESA=$cod_empresa AND
								COD_CAMPANHA=$cod_campanha AND
								COD_ENVIADO='N' AND
								COD_CLIENTE IN (" . $cods_cliente . ")";
        fnLog(array("DESCRICAO" => "Update na email_fila marcando E, para inicio do disparo", "COD_EMPRESA" => $cod_empresa, "SQL" => $sql, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));
        mysqli_query($contemporaria, $sql);
        if (mysqli_error($contemporaria) != "") {
            fnLog(array("DESCRICAO" => "Erro SQL ao fazer update na email_fila!", "COD_EMPRESA" => $cod_empresa, "SQL" => $sql, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha, "LAYOUT" => "text-danger"));
        }

        fnLog(array("DESCRICAO" => "Iniciando Disparo de PUSH", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha, "QTD_FILA" => $qtd_total_envio));

        $qtd_envios = 0;
        foreach ($CLIE_CAD["TOKEN"] as $VERSAO_SISTEMA => $push) {

            //Checa se campanha ainda está ativa
            $sql = "SELECT gt.LOG_STATUS,cp.LOG_ATIVO FROM gatilho_push gt"
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

            $sistema = "";

            if ($VERSAO_SISTEMA == "iOS") {
                $sistema = "iOS";
                $app_id = $des_usuario_ios;
                $Authorization = $des_authkey_ios;
            } else {
                $sistema = "Android";
                $app_id = $des_usuario;
                $Authorization = $des_authkey;
            }

            $include_player_ids = implode(",", $push);
            $contents = $CLIE_CAD["DES_MENSAGEM"];
            $headings = $CLIE_CAD["DES_TITULO"];

            $url = 'https://adm.bunkerapp.com.br/app/historicoPush.do?key=' . rtrim(fnEncode($cod_empresa), '¢') . '&idc=' . base64_encode(fnEncode($push["COD_CLIENTE"]));

            $rs = "SELECT * FROM gatilho_push 
                            WHERE COD_GATILHO = '" . $rsgatilho["COD_GATILHO"] . "'";
            $gatilho_push = mysqli_fetch_assoc(mysqli_query($contemporaria, $rs));

            $send_after = $gatilho_push["DAT_INI"] . " " . $gatilho_push["HOR_INI"];
            if ($send_after < date("Y-m-d H:i:s")) {
                $send_after = date("Y-m-d H:i:s");
            }

            $ret = fnonsignal($app_id, $include_player_ids, $contents, $Authorization, $headings, $url, $send_after);

            $err_arr = json_decode($ret, true);
            if (isset($err_arr["errors"])) {
                fnLog(array("DESCRICAO" => "Erro no retorno da função fnonsignal - $VERSAO_SISTEMA", "COD_EMPRESA" => $cod_empresa, "ERRO" => $ret, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));
                fnLog(array("DESCRICAO" => "Tokens enviados", "COD_EMPRESA" => $cod_empresa, "ERRO" => $include_player_ids, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));
                //continue;
            }

            $envio = array(
                "sistema" => $sistema,
                "app_id" => $app_id,
                "include_player_ids" => $include_player_ids,
                "contents" => $contents,
                "Authorization" => $Authorization,
                "headings" => $headings,
            );
            $qtd_envios++;
            $ret_arr = json_decode($ret, true);
            $tem_erro = isset($ret_arr["errors"]);
            //retorno
            //{"id":"","recipients":0,"errors":["All included players are not subscribed"]}
            //{"id":"feb458a7-64f2-463e-bf5f-acf091f77e5a","recipients":1,"external_id":null}

            $sqlControle = "INSERT INTO log_push(
				COD_EMPRESA,
				LOG_ENVIO,
				TOKEN_PUSH,
				DAT_CADASTR,
				COD_CAMPANHA
			)VALUES(
				0" . $cod_empresa . ",
				'" . json_encode($envio) . "',
				'$ret',
				NOW(),
				$cod_campanha
			)";
            $rs = mysqli_query($contemporaria, $sqlControle);

            if (mysqli_error($contemporaria) != "") {
                fnLog(array("DESCRICAO" => "Erro ao inserir na tabela log_push", "COD_EMPRESA" => $cod_empresa, "SQL" => $sqlControle, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha, "LAYOUT" => "text-danger"));
                continue;
            }

            foreach ($CLIE_CAD["COD_CLIENTE"][$VERSAO_SISTEMA] as $cod_cliente) {
                $celular = '';
                $idDisparo = date('Ymd');

                $sqlControle = "INSERT INTO push_lista_ret (
                                        COD_CAMPANHA,
                                        COD_UNIVEND,
                                        COD_CLIENTE,
                                        COD_EMPRESA,
                                        NOM_CLIENTE,
                                        COD_SEXOPES,
                                        DES_EMAILUS,
                                        NUM_CELULAR,
                                        DAT_NASCIME,
                                        ID_DISPARO,
                                        STATUS_ENVIO,
                                        DES_MSG_ENVIADA,
                                        DAT_CADASTR,
                                        LOG_TESTE,
                                        DES_STATUS,
                                        CHAVE_GERAL,
                                        COD_CCONFIRMACAO,
                                        COD_LEITURA,
                                        BOUNCE,
                                        CHAVE_CLIENTE
                                        ) VALUES (
                                        '" . $cod_campanha . "',
                                        (SELECT COD_UNIVEND FROM email_fila WHERE COD_EMPRESA  = " . $cod_empresa . " AND COD_CLIENTE = " . $cod_cliente . " ORDER BY 1 DESC LIMIT 1),
                                        '" . $cod_cliente . "',
                                        '" . $cod_empresa . "',
                                        (SELECT NOM_CLIENTE FROM CLIENTES WHERE COD_EMPRESA = " . $cod_empresa . " AND COD_CLIENTE = " . $cod_cliente . "),
                                        (SELECT COD_SEXOPES FROM CLIENTES WHERE COD_EMPRESA = " . $cod_empresa . " AND COD_CLIENTE = " . $cod_cliente . "),
                                        (SELECT DES_EMAILUS FROM CLIENTES WHERE COD_EMPRESA = " . $cod_empresa . " AND COD_CLIENTE = " . $cod_cliente . "),
                                        '" . fnlimpacelular($celular) . "',
                                        (SELECT DAT_NASCIME FROM CLIENTES WHERE COD_EMPRESA = " . $cod_empresa . " AND COD_CLIENTE = " . $cod_cliente . "),
                                        '" . $idDisparo . "',
                                        'S',
                                        '" . $contents . "',
                                        NOW(),
                                        'N',
                                        '" . ($tem_erro ? "bounce" : "success") . "',
                                        '" . @$ret_arr["id"] . "',
                                        " . ($tem_erro ? 0 : 1) . ",
                                        " . ($tem_erro ? 0 : 1) . ",
                                        " . ($tem_erro ? 1 : 0) . ",
                                        '" . $include_player_ids . "'
                                        )";
                //fnLog(array("DESCRICAO" => "Insert na push_lista_ret!", "COD_EMPRESA" => $cod_empresa, "SQL" => $sqlControle, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));
                mysqli_query($contemporaria, $sqlControle);
                if (mysqli_error($contemporaria) != "") {
                    fnLog(array("DESCRICAO" => "Erro SQL ao inserir na push_lista_ret!", "COD_EMPRESA" => $cod_empresa, "SQL" => $sqlControle, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha, "LAYOUT" => "text-danger"));
                    // continue;
                }
            }

            $sql = "UPDATE email_fila SET COD_ENVIADO='S',DT_ALTERAC=NOW()
                        WHERE
                            TIP_FILA IN (14) AND
                            TIP_GATILHO='$tip_gatilho' AND
                            COD_EMPRESA=$cod_empresa AND
                            COD_CAMPANHA=$cod_campanha AND
                            COD_ENVIADO='N' AND
                            COD_CLIENTE IN (" . implode(",", $CLIE_CAD["COD_CLIENTE"][$VERSAO_SISTEMA]) . ")";
            //fnLog(array("DESCRICAO" => "Update na email_fila", "COD_EMPRESA" => $cod_empresa, "SQL" => $sql, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));
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
        }

        fnLog(array("DESCRICAO" => "Rotina PUSH processada", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));

        fnLog(array("DESCRICAO" => "<b>Enviado</b>", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha, "QTD_ENVIOS" => $qtd_total_envio, "LAYOUT" => "text-info"));


        $sql = "UPDATE contador SET NUM_CONTADOR=IFNULL(NUM_CONTADOR,1)+1 WHERE NUM_TKT=50";
        mysqli_query($contemporaria, $sql);
        if (mysqli_error($contemporaria) != "") {
            fnLog(array("DESCRICAO" => "Erro ao atualizar tabela contador!", "COD_EMPRESA" => $cod_empresa, "SQL" => $sql, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha, "LAYOUT" => "text-danger"));
            continue;
        }

        $rsemail_fila["COD_ENVIADO"] = "S";
        $sql = "INSERT INTO email_filavalidades (
							DT_CADASTR,COD_EMPRESA,COD_UNIVEND,COD_CLIENTE,NUM_CGCECPF,NOM_CLIENTE,DT_NASCIME,
							DES_EMAILUS,NUM_CELULAR,VAL_RESGATE,VAL_CRED_ACUMULADO,COD_SEXOPES,COD_CAMPANHA,TIP_MOMENTO,
							TIP_CONTROLE,TIP_FILA,TIP_GATILHO,VAL_EXPIRAR,DAT_EXPIRA,SEMANA,MES,COD_ENVIADO
						) (
						SELECT DISTINCT
							NOW(),COD_EMPRESA,COD_UNIVEND,COD_CLIENTE,NUM_CGCECPF,NOM_CLIENTE,DT_NASCIME,
							'' DES_EMAILUS,NUM_CELULAR,VAL_RESGATE,VAL_CRED_ACUMULADO,COD_SEXOPES,COD_CAMPANHA,TIP_MOMENTO,
							TIP_CONTROLE,TIP_FILA,TIP_GATILHO,VAL_EXPIRAR,DAT_EXPIRA,SEMANA,MES,'S' COD_ENVIADO
						FROM email_fila
						WHERE
							TIP_GATILHO='$tip_gatilho' AND
							COD_EMPRESA=$cod_empresa AND
							COD_CAMPANHA=$cod_campanha AND
							COD_CLIENTE IN (" . $cods_cliente . ")
						)";
        fnLog(array("DESCRICAO" => "Insere na email_filavalidades", "COD_EMPRESA" => $cod_empresa, "SQL" => $sql, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));
        mysqli_query($contemporaria, $sql);
        if (mysqli_error($contemporaria) != "") {
            fnLog(array("DESCRICAO" => "Erro SQL ao inserir na email_filavalidades!", "COD_EMPRESA" => $cod_empresa, "SQL" => $sql, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));
            // continue;
        }
    }
}

fnLog(array("DESCRICAO" => "Fim da Rotina", "LAYOUT" => "text-success"));
echo "</pre>";

$sql = "UPDATE gatilhos_logs_exec SET DATAHORA_FIM=NOW() WHERE UID='" . $uuid . "' AND COD_EXECUCAO='" . str_replace("-", "", str_replace(":", "", str_replace(" ", "", $ini_rotina))) . "'";
$rs = mysqli_query($conadmin, $sql);
