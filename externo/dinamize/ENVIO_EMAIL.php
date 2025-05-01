<?php
require '../../_system/_functionsMain.php';
require '../../_system/func_dinamiza/Function_dinamiza.php';

$gera_log = "S";
$conadmin = $connAdm->connAdm();


$datahoraatual = date('Y-m-d H:i:s');
$horaatual = date("H");
$minutoatual = date("i");
$uuid = md5(uniqid(rand(), true));

//função de geração de arquivos
//function gerandorcvs($caminho,$nomeArquivo,$delimitador,$arraydados,$arrayheders)
//caminho para salavar aquivo
//_system/func_dinamiza/lista_envio

/*

$horaatual=date('H:i:s');
$numerotentativa=3;
$MINLISTA=1;
$PERMITENEGATIVO='S';
$CONFIRMACAO='N';
*/

$ini_rotina = $datahoraatual;
$sequencia = 0;
/*
$sql = "SELECT COUNT(0) QTD,TIMESTAMPDIFF(MINUTE,MAX(DATAHORA_INICIO),NOW()) TEMPO FROM gatilhos_logs_exec WHERE TIPO='EMAIL' AND DATAHORA_ATUALIZACAO_EMPRE IS NULL HAVING TIMESTAMPDIFF(MINUTE,MAX(DATAHORA_ATUALIZACAO_LOG),NOW()) <= 60";
$rs = mysqli_query($conadmin, $sql);
$linha = mysqli_fetch_assoc($rs);
if ($linha["QTD"] > 0 && $linha["TEMPO"] <= 5) {
	if (@$_GET["COD_EMPRESA"] == "") {
		fnLog(array("DESCRICAO" => "Existe uma rotina ainda em execução iniciada a pouco tempo", "LAYOUT" => "text-warning"));
		exit;
	}
}*/

$sql = "INSERT INTO gatilhos_logs_exec (UID,COD_EXECUCAO,TIPO,DATAHORA_INICIO,CODS_EMPRESA) VALUES "
	. "('" . $uuid . "','" . str_replace("-", "", str_replace(":", "", str_replace(" ", "", $ini_rotina))) . "','EMAIL',NOW(),0)";
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
					'EMAIL',
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
	}
	$sequencia++;
	echo date("Y-m-d H:i:s") . " - " . json_encode($dados) . "<br>";
}

echo "<pre>";
fnLog(array("DESCRICAO" => "Início da Rotina", "LAYOUT" => "text-success"));

$where = "";
if (@$_REQUEST["COD_EMPRESA"] <> "") {
	$where .= "AND apar.COD_EMPRESA in (0" . @$_REQUEST["COD_EMPRESA"] . ")";
}

/*EMPRESAS************************************************************************************************************************/
$sqlempresa = "SELECT * FROM senhas_parceiro apar
				INNER JOIN parceiro_comunicacao par ON par.COD_PARCOMU=apar.COD_PARCOMU
				WHERE par.COD_TPCOM='1' AND apar.LOG_ATIVO='S'
				$where
				";
//and apar.COD_EMPRESA not in  (144)		
$rwempresa = mysqli_query($conadmin, $sqlempresa);
fnLog(array("DESCRICAO" => "Obter lista das Empresas", "SQL" => $sqlempresa, "ERRO" => mysqli_error($conadmin)));
$count_empre = 0;
$tot_empre = mysqli_num_rows($rwempresa);
while ($rsempresa = mysqli_fetch_assoc($rwempresa)) {

	$cod_empresa = $rsempresa['COD_EMPRESA'];
	$contemporaria = connTemp($cod_empresa, '');
	$cod_lista1 = $rsempresa['COD_LISTA'];
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


	//Verifica se essa empresa ainda está em execução
	$sql = "SELECT COUNT(0) QTD,GROUP_CONCAT(COD_LOG_EXEC) CODS FROM gatilhos_logs_exec "
		. " WHERE 1=1 "
		. " AND UID <> '$uuid'"
		. " AND TIPO='EMAIL'"
		. " AND DATAHORA_FIM IS NULL"
		. " AND CONCAT(',',CODS_EMPRESA,',') LIKE '%,$cod_empresa,%'"
		. " AND TIMESTAMPDIFF(MINUTE,DATAHORA_ATUALIZACAO_LOG,NOW()) < 60";
	$rs = mysqli_query($conadmin, $sql);
	$linha = mysqli_fetch_assoc($rs);

	fnLog(array("DESCRICAO" => "Processos em execução: " . $linha["QTD"], "COD_EMPRESA" => $cod_empresa, "SQL" => $sql, "ERRO" => mysqli_error($conadmin)));
	if ($linha["QTD"] > 0 && @$_GET["ENVIAR"] <> "S") {
		fnLog(array("DESCRICAO" => "Existe uma rotina ainda em execução para esta empresa - Código processo: <b>" . $linha["CODS"] . "</b>", "COD_EMPRESA" => $cod_empresa, "LAYOUT" => "text-warning"));
		continue;
	}


	$parcerorw = mysqli_query($conadmmysql, $sqlpacero);
	//iniciar autenticação na dinamize
	$atenticacaoDInamize = autenticacao_dinamiza($rsempresa['DES_USUARIO'], $rsempresa['DES_AUTHKEY'], $rsempresa['DES_CLIEXT']);
	$senha_dinamize = $atenticacaoDInamize['body']['auth-token'];
	fnLog(array("DESCRICAO" => "Autenticação Dinamize", "COD_EMPRESA" => $cod_empresa, "SQL" => $sqlgatilho, "JSON" => json_encode($atenticacaoDInamize)));


	/*GATILHO************************************************************************************************************************/
	$gatilhos = array("individual", "cadastro", "resgate", "venda", "aniv", "anivSem", "anivQuinz", "anivMes", "anivDia", "anivCad", "credExp", "inativos", "credVen"); // <-- Colocar na ordem do select
	if (@$_GET["TIP_GATILHO"] <> "") {
		$gatilhos = array($_GET["TIP_GATILHO"]);
	}
	$gatilhos_impl_in = "'" . (implode("','", $gatilhos)) . "'";
	$gatilhos_impl_ord = "'" . (implode(",", $gatilhos)) . "'";
	$sqlgatilho = "SELECT gt.*,p.*,cp.* FROM gatilho_email gt
						INNER JOIN campanha cp ON gt.COD_CAMPANHA=cp.COD_CAMPANHA 
						INNER JOIN email_parametros p ON gt.COD_EMPRESA=p.cod_empresa AND gt.COD_CAMPANHA=p.cod_campanha
							AND COD_LISTA IN (SELECT MAX(COD_LISTA) FROM email_parametros WHERE COD_EMPRESA=gt.cod_empresa AND COD_CAMPANHA=gt.cod_campanha)
					WHERE gt.TIP_GATILHO IN ($gatilhos_impl_in)
						AND gt.LOG_STATUS ='S'
						AND cp.LOG_ATIVO = 'S'
						AND gt.cod_empresa=$cod_empresa

						AND CONCAT(cp.DAT_FIM,' ',cp.HOR_FIM) > NOW()

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
		$log_processa = $rsgatilho["LOG_PROCESSA"];
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
			fnLog(array("DESCRICAO" => "Campanha fora da validade - <b>validade:</b> $datetimecampanha", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha, "LAYOUT" => "text-danger"));
			continue;
		}

		//VERIFICA se a campanha está ativa para envio de e-mail
		if ($log_processa != 'S') {
			fnLog(array("DESCRICAO" => "Campanha não habilitada para envio de e-mail!", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha, "LAYOUT" => "text-danger"));
			continue;
		}

		if (@$_GET["ENVIAR"] == "S") {
			fnLog(array("DESCRICAO" => "Forçar execução do GATILHO", "COD_EMPRESA" => $cod_empresa, "JSON" => json_encode($rsgatilho), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha, "LAYOUT" => "text-warning"));
			$gravacao = true;
			$process = true;
		} else {

			if ($des_periodo == 7 || $des_periodo == 15) {
				$d = date("w");
				fnLog(array("DESCRICAO" => "Dia da semana: $d / Antecedência: $dias_anteced", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));
				if (($d % 7) <> 0) {
					fnLog(array("DESCRICAO" => "Gatilho semanal / quinzenal fora do período. (Período: $des_periodo / Antecedência: $dias_anteced / Referência: DOMINGO)", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));
					continue;
				} else {
					fnLog(array("DESCRICAO" => "Gatilho semanal / quinzenal. (Período: $des_periodo / Antecedência: $dias_anteced / Referência: DOMINGO)", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));
				}
			} elseif ($des_periodo == 30) {
				$d = date('d', strtotime(date("Y-m-d") . " + 0 days"));
				fnLog(array("DESCRICAO" => "Dia da semana: $d / Antecedência: $dias_anteced", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));
				if ($d <> "01" && $d <> "1") {
					fnLog(array("DESCRICAO" => "Gatilho mensal fora do período. (Período: $des_periodo / Antecedência: $dias_anteced / Referência: DIA 1&ordm;)", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));
					continue;
				} else {
					fnLog(array("DESCRICAO" => "Gatilho mensal. (Período: $des_periodo / Antecedência: $dias_anteced / Referência: DIA 1&ordm;)", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));
				}
			}

			//Checa o horário de envio
			$process = true;
			$gravacao = false;
			if ($tip_momento < 24 && $tip_momento > 0 && ($tip_momento - 1) == $horaatual && (int)$minutoatual >= 20) {
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
			//if ($tip_momento < 24 && $tip_momento > 0 && (int)$minutoatual >= 20) {
			//	fnLog(array("DESCRICAO" => "Fora do minuto de envio (Hora atual: $horaatual:$minutoatual / Hora de envio: $tip_momento)", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));
			//	$process = false;
			//}
			if ($tip_momento >= 24 && (int)$minutoatual <= 20) {
				fnLog(array("DESCRICAO" => "Gatilho imediato adiado para não sobrecarregar gatilhos agendados! (Hora atual: $horaatual:$minutoatual / Hora de envio: $tip_momento)!", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));
				continue;
			}

			if (!$process) {
				if ($log_process == "S" && ((int)$minutoatual < 20)) {
					fnLog(array("DESCRICAO" => "Gatilho não enviado no horário agendado. Reprocessando.... (Hora atual: $horaatual / Hora de envio: $tip_momento)!", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));
				} elseif ($gravacao) {
					fnLog(array("DESCRICAO" => "Gravando dados para envio agendado.... (Hora atual: $horaatual / Hora de envio: $tip_momento)!", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));
				} else {
					continue;
				}
			} else {
				fnLog(array("DESCRICAO" => "Dentro do horário de envio (Hora atual: $horaatual:$minutoatual / Hora de envio: $tip_momento)!", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));
			}
		}

		/*MARCA GATILHO PARA SER EXECUTADO*********************************************************************************/
		$sql = "UPDATE gatilho_sms SET LOG_PROCESS_GATILHO='S',DATHOR_PROCESS=NOW() WHERE COD_GATILHO=$cod_gatilho";
		$rs = mysqli_query($contemporaria, $sql);
		fnLog(array("DESCRICAO" => "Gatilho marcado para ser executado!", "SQL" => $sql, "COD_EMPRESA" => $cod_empresa, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));

		if ($gravacao) {

			if ($tip_gatilho == "aniv" || $tip_gatilho == "anivDia" || $tip_gatilho == "anivSem" || $tip_gatilho == "anivQuinz" || $tip_gatilho == "anivMes") {
				$tip_gatilho = "aniv";

				//$sqlIns = "DELETE FROM email_fila WHERE COD_EMPRESA = $cod_empresa AND TIP_GATILHO = '$tip_gatilho' AND COD_ENVIADO='N' AND COD_CAMPANHA = '$cod_campanha' AND TIP_FILA IN (2,5,6,9)";
				$sqlIns = "UPDATE email_fila SET COD_ENVIADO='Y',DT_ALTERAC=NOW() WHERE COD_EMPRESA = $cod_empresa AND TIP_GATILHO = '$tip_gatilho' AND COD_ENVIADO='N' AND COD_CAMPANHA = '$cod_campanha' AND TIP_FILA IN (2,5,6,9)";
				mysqli_query($contemporaria, $sqlIns);
				fnLog(array("DESCRICAO" => "Apagando itens não enviados na tabela email_fila", "COD_EMPRESA" => $cod_empresa, "SQL" => $sqlIns, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));

				$where = "";
				switch ($des_periodo) {
					case 7:
						$where = "WEEK( STR_TO_DATE(C.DAT_NASCIME,'%d/%m/%Y') ) = WEEK(CURDATE())";
						break;
					case 15:
						$where = "(
									WEEK( STR_TO_DATE(C.DAT_NASCIME,'%d/%m/%Y') ) = WEEK(CURDATE()) OR
									WEEK( STR_TO_DATE(C.DAT_NASCIME,'%d/%m/%Y') ) = WEEK(DATE_ADD(CURDATE(),INTERVAL 7 DAY)) OR
								  )";
						break;
					case 30:
					case 99:
						$where = "MONTH( STR_TO_DATE(C.DAT_NASCIME,'%d/%m/%Y') ) = MONTH(CURDATE())";
						break;
					default:
						$where = "DAY( STR_TO_DATE(C.DAT_NASCIME,'%d/%m/%Y') ) = DAY(CURDATE()) AND
								  MONTH( STR_TO_DATE(C.DAT_NASCIME,'%d/%m/%Y') ) = MONTH(CURDATE())";
						break;
				}
				$sqlIns = "INSERT IGNORE INTO email_fila (LOG_BLACKLIST_EMAIL,DT_CADASTR,COD_EMPRESA,COD_UNIVEND,COD_CLIENTE,NUM_CGCECPF,NOM_CLIENTE,DT_NASCIME,DES_EMAILUS,NUM_CELULAR,VAL_RESGATE,
						VAL_CRED_ACUMULADO,COD_SEXOPES,COD_CAMPANHA,TIP_MOMENTO,TIP_CONTROLE,TIP_FILA,TIP_GATILHO,VAL_EXPIRAR,DAT_EXPIRA,SEMANA,MES,COD_ENVIADO)
						SELECT
							'S',NOW() DT_CADASTR,C.COD_EMPRESA,C.COD_UNIVEND,C.COD_CLIENTE,C.NUM_CGCECPF,C.NOM_CLIENTE,C.DAT_NASCIME ,
							C.DES_EMAILUS,C.NUM_CELULAR,0 VAL_RESGATE,0 VAL_CRED_ACUMULADO,C.COD_SEXOPES,$cod_campanha COD_CAMPANHA,'$tip_momento' TIP_MOMENTO,
							'$tip_controle' TIP_CONTROLE,2 TIP_FILA,'$tip_gatilho' TIP_GATILHO,0 VAL_EXPIRAR,NULL DAT_EXPIRA,'' SEMANA,'' MES,'N' COD_ENVIADO
						FROM PERSONACLASSIFICA A
						INNER JOIN CLIENTES C ON  A.COD_CLIENTE=C.COD_CLIENTE
						WHERE
						A.cod_persona IN (0$cod_personas) AND
						A.COD_EMPRESA=$cod_empresa AND
						C.DAT_NASCIME <> '00/00/0000' AND
						LENGTH (C.DAT_NASCIME) = 10 AND
						$where
						";
				mysqli_query($contemporaria, $sqlIns);

				fnLog(array("DESCRICAO" => "Gravando aniversariantes na tabela email_fila", "COD_EMPRESA" => $cod_empresa, "SQL" => $sqlIns, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));

				//$sqlIns = "DELETE FROM email_fila WHERE DT_NASCIME='31/12/1969' AND TIP_CONTROLE = '$tip_controle' AND COD_EMPRESA = $cod_empresa";
				$sqlIns = "UPDATE email_fila SET COD_ENVIADO='Y',DT_ALTERAC=NOW() WHERE DT_NASCIME='31/12/1969' AND TIP_CONTROLE = '$tip_controle' AND COD_EMPRESA = $cod_empresa";
				mysqli_query($contemporaria, $sqlIns);
				fnLog(array("DESCRICAO" => "Apagando datas inválidas na tabela email_fila", "COD_EMPRESA" => $cod_empresa, "SQL" => $sqlIns, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));
			} elseif ($tip_gatilho == "anivCad") {

				//$sqlIns = "DELETE FROM email_fila WHERE COD_EMPRESA = $cod_empresa AND TIP_GATILHO = '$tip_gatilho' AND COD_ENVIADO='N'";
				$sqlIns = "UPDATE email_fila SET COD_ENVIADO='Y',DT_ALTERAC=NOW() WHERE COD_EMPRESA = $cod_empresa AND TIP_GATILHO = '$tip_gatilho' AND COD_ENVIADO='N'";
				mysqli_query($contemporaria, $sqlIns);
				fnLog(array("DESCRICAO" => "Apagando itens não enviados na tabela email_fila", "COD_EMPRESA" => $cod_empresa, "SQL" => $sqlIns, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));

				$where = "";
				switch ($des_periodo) {
					case 7:
						$where = "WEEK( C.DAT_CADASTR ) = WEEK(CURDATE())";
						break;
					case 15:
						$where = "(
									WEEK( C.DAT_CADASTR ) = WEEK(CURDATE()) OR
									WEEK( C.DAT_CADASTR ) = WEEK(DATE_ADD(CURDATE(),INTERVAL 7 DAY)) OR
								  )";
						break;
					case 30:
					case 99:
						$where = "MONTH( C.DAT_CADASTR ) = MONTH(CURDATE())";
						break;
					default:
						$where = "DAY( C.DAT_CADASTR ) = DAY(CURDATE()) AND
								  MONTH( C.DAT_CADASTR ) = MONTH(CURDATE())";
						break;
				}
				$sqlIns = "INSERT IGNORE INTO email_fila (LOG_BLACKLIST_EMAIL,DT_CADASTR,COD_EMPRESA,COD_UNIVEND,COD_CLIENTE,NUM_CGCECPF,NOM_CLIENTE,DT_NASCIME,DES_EMAILUS,NUM_CELULAR,VAL_RESGATE,
						VAL_CRED_ACUMULADO,COD_SEXOPES,COD_CAMPANHA,TIP_MOMENTO,TIP_CONTROLE,TIP_FILA,TIP_GATILHO,VAL_EXPIRAR,DAT_EXPIRA,SEMANA,MES,COD_ENVIADO)
						SELECT
							'S',NOW() DT_CADASTR,C.COD_EMPRESA,C.COD_UNIVEND,C.COD_CLIENTE,C.NUM_CGCECPF,C.NOM_CLIENTE,C.DAT_NASCIME ,
							C.DES_EMAILUS,C.NUM_CELULAR,0 VAL_RESGATE,0 VAL_CRED_ACUMULADO,C.COD_SEXOPES,$cod_campanha COD_CAMPANHA,'$tip_momento' TIP_MOMENTO,
							'$tip_controle' TIP_CONTROLE,2 TIP_FILA,'$tip_gatilho' TIP_GATILHO,0 VAL_EXPIRAR,NULL DAT_EXPIRA,'' SEMANA,'' MES,'N' COD_ENVIADO
						FROM PERSONACLASSIFICA A
						INNER JOIN CLIENTES C ON  A.COD_CLIENTE=C.COD_CLIENTE
						WHERE
						A.cod_persona IN (0$cod_personas) AND
						A.COD_EMPRESA=$cod_empresa AND
						C.DAT_CADASTR <> '00/00/0000' AND
						LENGTH (C.DAT_NASCIME) = 10 AND
						$where
						";
				mysqli_query($contemporaria, $sqlIns);

				fnLog(array("DESCRICAO" => "Gravando aniversariantes de cadastro na tabela email_fila", "COD_EMPRESA" => $cod_empresa, "SQL" => $sqlIns, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));

				//$sqlIns = "DELETE FROM email_fila WHERE DT_NASCIME='31/12/1969' AND TIP_CONTROLE = '$tip_controle' AND COD_EMPRESA = $cod_empresa";
				$sqlIns = "UPDATE email_fila SET COD_ENVIADO='Y',DT_ALTERAC=NOW() WHERE DT_NASCIME='31/12/1969' AND TIP_CONTROLE = '$tip_controle' AND COD_EMPRESA = $cod_empresa";
				mysqli_query($contemporaria, $sqlIns);
				fnLog(array("DESCRICAO" => "Apagando datas inválidas na tabela email_fila", "COD_EMPRESA" => $cod_empresa, "SQL" => $sqlIns, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));
			} elseif ($tip_gatilho == "credExp") {

				$dias_anteced = ($dias_anteced == "" ? 1 : $dias_anteced);
				$tot_saldomin = ($tot_saldomin <= 0 ? 1 : $tot_saldomin);
				$des_periodomax = ($des_periodomax  == "" ? 0 : $des_periodomax);
				$sqlIns = "INSERT INTO email_fila (DT_CADASTR,COD_EMPRESA,COD_UNIVEND,COD_CLIENTE,NUM_CGCECPF,NOM_CLIENTE,DT_NASCIME,DES_EMAILUS,NUM_CELULAR,VAL_RESGATE,
							VAL_CRED_ACUMULADO,COD_SEXOPES,COD_CAMPANHA,TIP_MOMENTO,TIP_CONTROLE,TIP_FILA,TIP_GATILHO,VAL_EXPIRAR,DAT_EXPIRA,SEMANA,MES,COD_ENVIADO)
							SELECT  
								NOW() DT_CADASTR,C.COD_EMPRESA,C.COD_UNIVEND,C.COD_CLIENTE,C.NUM_CGCECPF,C.NOM_CLIENTE,NULL DT_NASCIME,
								C.DES_EMAILUS,C.NUM_CELULAR,0 VAL_RESGATE,0 VAL_CRED_ACUMULADO,C.COD_SEXOPES,$cod_campanha COD_CAMPANHA,'$tip_momento' TIP_MOMENTO,
								'$tip_controle' TIP_CONTROLE,2 TIP_FILA,'$tip_gatilho' TIP_GATILHO,
								ROUND((SELECT SUM(val_saldo) FROM creditosdebitos E WHERE E.COD_CLIENTE=C.COD_CLIENTE AND E.COD_STATUSCRED=1 AND DATE(E.DAT_EXPIRA) >= DATE(DATE_ADD(NOW(),INTERVAL $dias_anteced DAY)) AND DATE(E.DAT_EXPIRA) <= DATE(DATE_ADD(NOW(),INTERVAL " . ($dias_anteced + $des_periodomax) . " DAY))),2)  VAL_EXPIRAR,
								NULL DAT_EXPIRA,'' SEMANA,'' MES,'N' COD_ENVIADO
							FROM PERSONACLASSIFICA A
							INNER JOIN CREDITOSDEBITOS B ON A.COD_CLIENTE=B.COD_CLIENTE
							INNER JOIN CLIENTES C ON  A.COD_CLIENTE=C.COD_CLIENTE  
							WHERE 
							A.cod_persona=$cod_personas AND
							A.COD_EMPRESA=$cod_empresa AND
							DATE(B.DAT_EXPIRA) >= DATE(DATE_ADD(NOW(),INTERVAL $dias_anteced DAY)) AND
							DATE(B.DAT_EXPIRA) <= DATE(DATE_ADD(NOW(),INTERVAL " . ($dias_anteced + $des_periodomax) . " DAY)) AND
							B.TIP_CREDITO='C' AND
							B.COD_STATUSCRED='1' 
							GROUP BY B.COD_CLIENTE      
							HAVING (SELECT SUM(val_saldo) FROM creditosdebitos E WHERE E.COD_CLIENTE=C.COD_CLIENTE AND COD_STATUSCRED=1) >= $tot_saldomin";
				mysqli_query($contemporaria, $sqlIns);
				fnLog(array("DESCRICAO" => "Gravando Créditos a Expirar", "COD_EMPRESA" => $cod_empresa, "SQL" => $sqlIns, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));
			} elseif ($tip_gatilho == "inativos") {

				$dias_anteced = ($dias_anteced == "" ? 1 : $dias_anteced);
				$tot_saldomin = ($tot_saldomin <= 0 ? 1 : $tot_saldomin);
				$des_periodomax = ($des_periodomax  == "" ? 0 : $des_periodomax);
				$sqlIns = "INSERT INTO email_fila (DT_CADASTR,COD_EMPRESA,COD_UNIVEND,COD_CLIENTE,NUM_CGCECPF,NOM_CLIENTE,DT_NASCIME,DES_EMAILUS,NUM_CELULAR,VAL_RESGATE,
							VAL_CRED_ACUMULADO,COD_SEXOPES,COD_CAMPANHA,TIP_MOMENTO,TIP_CONTROLE,TIP_FILA,TIP_GATILHO,VAL_EXPIRAR,DAT_EXPIRA,SEMANA,MES,COD_ENVIADO)
							SELECT
								NOW() DT_CADASTR,BB.COD_EMPRESA,BB.COD_UNIVEND,BB.COD_CLIENTE,BB.NUM_CGCECPF,BB.NOM_CLIENTE,NULL DT_NASCIME,
								BB.DES_EMAILUS,BB.NUM_CELULAR,0 VAL_RESGATE,0 VAL_CRED_ACUMULADO,BB.COD_SEXOPES,$cod_campanha COD_CAMPANHA,NULL TIP_MOMENTO,
								NULL TIP_CONTROLE,2 TIP_FILA,'$tip_gatilho' TIP_GATILHO,0 VAL_EXPIRAR,NULL DAT_EXPIRA,'' SEMANA,'' MES,'N' COD_ENVIADO
							FROM clientes BB
							INNER JOIN PERSONACLASSIFICA A ON A.COD_CLIENTE=BB.COD_CLIENTE  
							WHERE BB.cod_empresa=$cod_empresa AND
							A.cod_persona=$cod_personas AND
							BB.LOG_ESTATUS = 'S' AND 
							BB.LOG_FIDELIZADO = 'S' AND
							IFNULL(DATEDIFF(DATE(NOW()),DATE(BB.DAT_ULTCOMPR)),0) > 0" . $dias_hist . " AND
							DATE_FORMAT(BB.DAT_CADASTR, '%Y-%m-%d') <= DATE_FORMAT(ADDDATE( NOW(), INTERVAL - $des_periodomin DAY), '%Y-%m-%d') AND 
							NOT EXISTS (
									SELECT 1
									FROM VENDAS A
									WHERE DATE_FORMAT(A.DAT_CADASTR, '%Y-%m-%d') BETWEEN  DATE_FORMAT(ADDDATE( NOW(), INTERVAL - $des_periodomax DAY), '%Y-%m-%d') AND DATE_FORMAT(ADDDATE( NOW(), INTERVAL - $des_periodomin DAY), '%Y-%m-%d') AND 
										  A.COD_EMPRESA=$cod_empresa AND
											A.COD_AVULSO=2 AND 
										  A.VAL_TOTVENDA>1 AND 
										  A.COD_STATUSCRED IN(0,1,2,3,4,5,7,8,9) AND 
										  A.COD_CLIENTE=BB.COD_CLIENTE)
							AND BB.COD_CLIENTE NOT IN (
								SELECT COD_CLIENTE FROM email_filavalidades
								WHERE COD_EMPRESA=BB.cod_empresa
								AND TIP_GATILHO='$tip_gatilho'
								AND DATE(DT_CADASTR)=DATE(NOW())
							)";

				mysqli_query($contemporaria, $sqlIns);
				fnLog(array("DESCRICAO" => "Gravando Inativos", "COD_EMPRESA" => $cod_empresa, "SQL" => $sqlIns, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));
			} else {
				fnLog(array("DESCRICAO" => "Não há dados para serem gravados", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));
			}

			if (!$process) {
				/*MARCA GATILHO PARA SER EXECUTADO*********************************************************************************/
				$sql = "UPDATE gatilho_sms SET LOG_PROCESS_GATILHO='N',DATHOR_PROCESS=NOW() WHERE COD_GATILHO=$cod_gatilho";
				$rs = mysqli_query($contemporaria, $sql);
				fnLog(array("DESCRICAO" => "Gatilho marcado para ser executado!", "SQL" => $sql, "COD_EMPRESA" => $cod_empresa, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));

				fnLog(array("DESCRICAO" => "Gravação efetuada.... Aguardando envio (Hora atual: $horaatual / Hora de envio: $tip_momento)!", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));
				continue;
			} else {
				fnLog(array("DESCRICAO" => "Gravação efetuada.... gatilho imediato prosseguindo com envio (Hora atual: $horaatual / Hora de envio: $tip_momento)!", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));
			}
		}


		$sql = "DELETE FROM email_fila em
						INNER JOIN unidadevenda un ON un.COD_UNIVEND = em.COD_UNIVEND AND un.LOG_ESTATUS = 'N'
						WHERE em.COD_EMPRESA = $cod_empresa;";
		$rs = mysqli_query($contemporaria, $sql);


		/*INICIO ROTINA DE ENVIO*/
		mysqli_query($contemporaria, "set character_set_client='utf8mb4'");
		mysqli_query($contemporaria, "set character_set_results='utf8mb4'");
		mysqli_query($contemporaria, "set collation_connection='utf8mb4_unicode_ci'");
		//   mysqli_free_result($rwtkt);
		mysqli_next_result($contemporaria);
		$tampletevariavel = "SELECT
							CP.DES_CAMPANHA, 
							CP.DAT_INI, 
							CP.HOR_INI,
							CP.COD_EXT_CAMPANHA, 
							TE.COD_EXT_TEMPLATE,
							TE.DES_ASSUNTO,
							TE.DES_REMET,
							TE.END_REMET,
							TE.EMAIL_RESPOSTA,
							MDE.DES_TEMPLATE AS HTML 
						FROM CAMPANHA CP
						INNER JOIN mensagem_email ECA ON ECA.COD_CAMPANHA = CP.COD_CAMPANHA
						INNER JOIN TEMPLATE_EMAIL TE ON TE.COD_TEMPLATE = ECA.COD_TEMPLATE_EMAIL
						INNER JOIN MODELO_EMAIL MDE ON MDE.COD_TEMPLATE = TE.COD_TEMPLATE
						WHERE CP.COD_EMPRESA = '$cod_empresa'
							AND CP.COD_CAMPANHA = '$cod_campanha'
							AND ECA.LOG_PRINCIPAL='S'
						ORDER BY MDE.COD_MODELO DESC LIMIT 1";
		$html = mysqli_fetch_assoc(mysqli_query($contemporaria, $tampletevariavel));
		$des_campanha_titulo = preg_replace('/\s+/', '_', fnAcentos($html['DES_CAMPANHA']));
		$des_campanha_titulo = str_replace('/', '.', $des_campanha_titulo);

		mysqli_free_result($html);
		mysqli_next_result($contemporaria);

		fnLog(array("DESCRICAO" => "Carregando Template", "COD_EMPRESA" => $cod_empresa, "SQL" => $tampletevariavel, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));



		//gera lista de variaveis
		$tagsPersonaliza = '{{cmp1}},{{cmp2}},' . procpalavrasV2($html['DES_ASSUNTO'] . $html['DES_TEMPLATE'], $connAdm->connAdm(), $cod_empresa) . ',{{cmp3}}';
		$tagsPersonaliza = str_replace(",,", ",", $tagsPersonaliza);
		$tagsPersonaliza = explode(',', $tagsPersonaliza);
		$contador = '0';

		$selectCliente = "";
		$tagsDinamize = "";
		foreach ($tagsPersonaliza as $key) {

			$sqlExt = "SELECT VD.COD_EXTERNO, VR.KEY_BANCOVAR,VD.COD_EXTERNO FROM VARIAVEIS_DINAMIZE VD 
							   INNER JOIN VARIAVEIS VR ON VR.COD_BANCOVAR = VD.COD_BANCOVAR
							   WHERE VD.COD_EMPRESA = $cod_empresa AND VD.DES_EXTERNO = '$key'";
			$qrExterno = mysqli_fetch_assoc(mysqli_query($conadmin, $sqlExt));
			$tagsDinamize .= '{"Position":"' . $contador . '", "Field":"' . $qrExterno[COD_EXTERNO] . '", "Rule":"3"},';
			switch ($qrExterno['KEY_BANCOVAR']) {

				case '<#NOME>';
					$selectCliente .= "SUBSTRING_INDEX(SUBSTRING_INDEX(concat(Upper(SUBSTR(C.NOM_CLIENTE, 1,1)), lower(SUBSTR(C.NOM_CLIENTE, 2,LENGTH(C.NOM_CLIENTE)))), ' ', 1), ' ', -1) AS NOM_CLIENTE, ";
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
					$selectCliente .= "(SELECT MIN(COD_UNIVEND) FROM email_fila WHERE
															email_fila.COD_CLIENTE=C.COD_CLIENTE
															email_fila.TIP_GATILHO='$tip_gatilho' AND
															email_fila.TIP_FILA IN (2,5,6,9) AND    
															email_fila.COD_EMPRESA=$cod_empresa AND 
															email_fila.COD_CAMPANHA=$cod_campanha AND
															email_fila.COD_ENVIADO='N') COD_UNIVEND,";
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
				case '<#SALDOEXPIRA>';
					$selectCliente .= "(SELECT MIN(VAL_EXPIRAR) FROM email_fila WHERE
															email_fila.COD_CLIENTE=C.COD_CLIENTE
															email_fila.TIP_GATILHO='$tip_gatilho' AND
															email_fila.TIP_FILA IN (2,5,6,9) AND    
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
		$selectCliente .= "C.COD_CLIENTE";
		$tagsDinamize = rtrim($tagsDinamize, ',');

		$where = "";
		if ($tip_gatilho == 'cadastro' || $tip_gatilho == 'resgate' || $tip_gatilho == 'venda') {
			$where .= "AND TIP_GATILHO IN ('cadastro','resgate','venda')";
		} else {
			$where .= "AND TIP_GATILHO IN ('$tip_gatilho')";
		}

		$sqlcli_cad = "SELECT DISTINCT $selectCliente
						FROM clientes C 							
						WHERE C.COD_EMPRESA = $cod_empresa
						AND C.LOG_EMAIL = 'S'
						AND C.LOG_FIDELIZADO = 'S'
						AND C.LOG_ESTATUS = 'S'
						AND TRIM(C.DES_EMAILUS) != ''
						AND C.COD_CLIENTE in (SELECT COD_CLIENTE FROM email_fila WHERE                                                                                                  
											TIP_GATILHO='$tip_gatilho' AND
											TIP_FILA IN (2,5,6,9) AND    
											COD_EMPRESA=$cod_empresa AND 
											COD_CAMPANHA=$cod_campanha AND
											LOG_BLACKLIST_EMAIL = 'S' AND
											" . ($tip_gatilho == "aniv" || $tip_gatilho == "anivCad" || $tip_gatilho == "credExp" || $tip_gatilho == "inativos" ? "DATE(email_fila.DT_CADASTR)=DATE(NOW()) AND" : "") . "
											" . ($tip_gatilho == "cadastro" && $des_periodo <= 30 ? "DATE(email_fila.DT_CADASTR) < DATE(NOW()) AND" : "") . "
											" . ($tip_gatilho == "venda" && $tip_momento <= 24 && $des_periodo == 1 ? "DATE(email_fila.DT_CADASTR) = DATE_SUB(DATE(NOW()), INTERVAL 1 DAY) AND" : "") . "
											COD_ENVIADO='N'
											" . ($tip_gatilho == "cadastro" && $des_periodo > 30 ? "" :
			"AND email_fila.COD_CLIENTE IN (
													SELECT DISTINCT COD_CLIENTE
														FROM personaclassifica
														WHERE cod_persona IN (0$cod_personas)
														AND cod_empresa=$cod_empresa
												)
												") . "
											group by COD_CLIENTE)

						AND NOT EXISTS ("
			. ($tip_controle == 7 ?
				"SELECT DES_EMAILUS FROM email_filavalidades
								WHERE TRIM(DES_EMAILUS) = TRIM(C.DES_EMAILUS)
								AND	COD_CLIENTE = C.COD_CLIENTE
								AND WEEK(DT_CADASTR)=WEEK(NOW())
								AND YEAR(DT_CADASTR)=YEAR(NOW())
								AND COD_EMPRESA=$cod_empresa
								$where"
				: ($tip_controle == 15 ?
					"SELECT DES_EMAILUS FROM email_filavalidades
									WHERE TRIM(DES_EMAILUS) = TRIM(C.DES_EMAILUS)
									AND	COD_CLIENTE = C.COD_CLIENTE
									AND DATE(DT_CADASTR) > DATE(DATE_ADD(NOW(), INTERVAL -15 DAY))
									AND COD_EMPRESA=$cod_empresa
									$where"
					: ($tip_controle == 30 ?
						"SELECT DES_EMAILUS FROM email_filavalidades
										WHERE TRIM(DES_EMAILUS) = TRIM(C.DES_EMAILUS)
										AND	COD_CLIENTE = C.COD_CLIENTE
										AND MONTH(DT_CADASTR)=MONTH(NOW())
										AND YEAR(DT_CADASTR)=YEAR(NOW())
										AND COD_EMPRESA=$cod_empresa
										$where"
						: "SELECT DES_EMAILUS FROM email_filavalidades
											WHERE TRIM(DES_EMAILUS) = TRIM(C.DES_EMAILUS)
											AND	COD_CLIENTE = C.COD_CLIENTE
											AND DATE(DT_CADASTR)=DATE(NOW())
											AND COD_EMPRESA=$cod_empresa
											$where"
					)
				)
			)
			. ") " .
			($tip_gatilho == "venda" ?
				"AND IFNULL((SELECT SUM(IFNULL(val_saldo,0)) FROM creditosdebitos E WHERE E.COD_CLIENTE=C.COD_CLIENTE AND E.COD_STATUSCRED=1),0) >= $tot_saldomin" :
				""
			);
		$sql_dados = $sqlcli_cad;
		//echo '<br>' . $sqlcli_cad . '<br>';

		$rwsql = mysqli_query($contemporaria, $sqlcli_cad);
		fnLog(array("DESCRICAO" => "Carregando clientes... Qtd. Retornado: " . mysqli_num_rows($rwsql), "COD_EMPRESA" => $cod_empresa, "SQL" => $sqlcli_cad, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));
		$CLIE_CAD = array();
		while ($headers = mysqli_fetch_field($rwsql)) {
			$headers1[campos][$headers->name] = $headers->name;
		}
		$cods_cliente = "0";
		$msg_env = "";

		while ($rsemail_fila = mysqli_fetch_assoc($rwsql)) {
			$cod_cliente = $rsemail_fila["COD_CLIENTE"];
			$des_emailus = $rsemail_fila["DES_EMAILUS"];


			$desc_cliente = "CLIENTE $cod_cliente / EMAIL $des_emailus ";

			//Checa se está preenchido
			if ($des_emailus == "") {
				$msg_env .= "<br>" . "$desc_cliente - E-mail não preenchido!";
				continue;
			}

			//$rsemail_fila["DES_EMAILUS"] = "teste@hotmail.com";
			$cods_cliente .= "," . $rsemail_fila["COD_CLIENTE"];
			$CLIE_CAD[] = $rsemail_fila;
		}

		fnLog(array("DESCRICAO" => "<b>Alertas:</b>" . ($msg_env == "" ? "<br>Nenhum alerta" : $msg_env), "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));

		if (count($CLIE_CAD) <= 0) {
			fnLog(array("DESCRICAO" => "Sem dados para serem enviados!", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha, "LAYOUT" => "text-warning"));
			continue;
		}
		$linhas = count($CLIE_CAD);
		$qtd_envios = $linhas;
		fnLog(array("DESCRICAO" => "E-mails Gerados", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha, "QTD_FILA" => $linhas));


		/* CHECA SALDO */
		$sql = "SELECT pedido.QTD_SALDO_ATUAL SALDO
					FROM pedido_marka pedido 
					INNER JOIN produto_marka prod ON prod.COD_PRODUTO = pedido.COD_PRODUTO 
					INNER JOIN canal_comunicacao canal ON canal.COD_CANALCOM = prod.COD_CANALCOM 
					INNER JOIN empresas emp ON emp.COD_EMPRESA = pedido.COD_EMPRESA
					WHERE pedido.COD_ORCAMENTO > 0 AND 
							pedido.COD_EMPRESA ='" . $cod_empresa . "' AND
							PAG_CONFIRMACAO IN ('S') AND
							pedido.TIP_LANCAMENTO ='C' AND  
							pedido.QTD_SALDO_ATUAL > 0 AND
							canal.COD_TPCOM=1 AND
							pedido.DAT_VALIDADE IS not null
						GROUP BY pedido.COD_VENDA";
		$rwcont = mysqli_query($conadmin, $sql);
		$lsaldo = mysqli_fetch_assoc($rwcont);
		fnLog(array("DESCRICAO" => "Consultando Saldo: " . $lsaldo["SALDO"] . " / Qtd Envio.: " . $linhas, "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "SQL" => $sql, "ERRO" => mysqli_error($contemporaria), "COD_CAMPANHA" => $cod_campanha));

		if ($lsaldo["SALDO"] < $linhas) {
			fnLog(array("DESCRICAO" => "Sem saldo para envio de lote", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha, "LAYOUT" => "text-danger"));
			continue;
		}

		$PERMITENEGATIVO = 'N';
		$CONFIRMACAO = 'S';
		//Contabiliza debitos
		$arraydebitos = array(
			'quantidadeEmailenvio' => $linhas,
			'COD_EMPRESA' => $cod_empresa,
			'PERMITENEGATIVO' => $PERMITENEGATIVO,
			'COD_CANALCOM' => '1',
			'CONFIRMACAO' => $CONFIRMACAO,
			'COD_CAMPANHA' => $cod_campanha,
			'LOG_TESTE' => 'N',
			'DAT_CADASTR' => date('Y-m-d H:i:s'),
			'CONNADM' => $connAdm->connAdm()
		);

		$consulta_saldo = FnDebitos($arraydebitos, true);
		fnLog(array("DESCRICAO" => "Consultando Saldo: " . $consulta_saldo["saldorestante"] . " / Qtd Envio.: " . $consulta_saldo["qtd_envio"] . " / Saldo após envio: " . $consulta_saldo["saldo_apos_envio"], "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "JSON" => json_encode($consulta_saldo), "ERRO" => mysqli_error($contemporaria), "COD_CAMPANHA" => $cod_campanha));
		if (!$consulta_saldo["tem_saldo"]) {
			fnLog(array("DESCRICAO" => "Sem saldo para envio de lote", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));
			continue;
		}


		fnLog(array("DESCRICAO" => "Iniciando Disparo de E-mail", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));

		$nomeRel = $cod_empresa . '_' . date("YmdHis") . "_" . $des_campanha . "cadastro.csv";
		$caminhoRelat = '/srv/www/htdocs/_system/func_dinamiza/lista_envio/';
		gerandorcvs($caminhoRelat, $nomeRel, ";", $CLIE_CAD, $headers1);
		$arquivodinamize = $caminhoRelat . $nomeRel;
		fnLog(array("DESCRICAO" => "Arquivo $arquivodinamize gerado", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));

		try {
			fnLog(array("DESCRICAO" => "Enviando... senha_dinamize=$senha_dinamize, arquivodinamize=$arquivodinamize, tagsDinamize=$tagsDinamize, cod_lista1=$cod_lista1", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));
			$retornoContatos = contatos_dinamiza("$senha_dinamize", "$arquivodinamize", "$tagsDinamize", "$cod_lista1");
		} catch (Exception $e) {
			fnLog(array("DESCRICAO" => "<b>Não Enviado</b>", "COD_EMPRESA" => $cod_empresa, "JSON" => json_encode($retornoContatos), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "ERRO" => $e->getMessage(), "COD_CAMPANHA" => $cod_campanha, "LAYOUT" => "text-danger"));
			continue;
		}
		fnLog(array("DESCRICAO" => "Disparo de E-mail (contatos_dinamiza)", "COD_EMPRESA" => $cod_empresa, "JSON" => json_encode($retornoContatos), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));

		sleep(1);

		if ($retornoContatos["code_detail"] == "Sucesso") {
			$cod_mailing_ext = $retornoContatos["body"]["code"];
			$nome_segmento = $cod_empresa . "_" . $cod_campanha . "_" . $des_campanha . "_" . date("YmdHis");
			$retornoSegmento = FiltroSegmentos(
				"$senha_dinamize",
				$nome_segmento,
				$cod_mailing_ext,
				$cod_lista1
			);
			$cod_ext_segmento = $retornoSegmento["body"]["code"];
			fnLog(array("DESCRICAO" => "Gravação segmento", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "JSON" => json_encode($retornoSegmento), "COD_CAMPANHA" => $cod_campanha));


			if ($retornoSegmento["code_detail"] == "Sucesso") {
				$dat_envio = date("Y-m-d H:i:s", strtotime("+ 3 minutes"));
				$retornoEnvio = array();
				$retornoEnvio = addenvio(
					$senha_dinamize,
					$des_campanha_titulo,
					$cod_lista1,
					$html["DES_ASSUNTO"],
					$html["DES_REMET"],
					$html["END_REMET"],
					$html["EMAIL_RESPOSTA"],
					$cod_ext_campanha,
					$cod_ext_segmento,
					$html["COD_EXT_TEMPLATE"],
					($velocidade_envio == "" ? 2 : $velocidade_envio),
					$dat_envio
				);
				fnLog(array("DESCRICAO" => "Envio processado", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "JSON" => json_encode($retornoEnvio), "COD_CAMPANHA" => $cod_campanha));

				if ($retornoEnvio["code_detail"] == "Sucesso") {
					$debitos = FnDebitos($arraydebitos);
					fnLog(array("DESCRICAO" => "Efetuando Débitos", "COD_EMPRESA" => $cod_empresa, "JSON" => json_encode($debitos), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));

					fnLog(array("DESCRICAO" => "<b>Enviado</b>", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha, "QTD_ENVIOS" => $qtd_envios, "LAYOUT" => "text-info"));

					/*MARCA GATILHO COMO EXECUTADO*********************************************************************************/
					$sql = "UPDATE gatilho_sms SET LOG_PROCESS_GATILHO='N',DATHOR_PROCESS=NOW() WHERE COD_GATILHO=$cod_gatilho";
					$rs = mysqli_query($contemporaria, $sql);
					fnLog(array("DESCRICAO" => "Gatilho marcado como executado!", "SQL" => $sql, "COD_EMPRESA" => $cod_empresa, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));

					$sqlControle = "INSERT IGNORE INTO EMAIL_LOTE(
													COD_EXT_SEGMENTO,
													COD_DISPARO_EXT,
													DAT_AGENDAMENTO,     
													COD_CAMPANHA,
													COD_EMPRESA,
													COD_LOTE,                                                                                         
													COD_STATUSUP,
													NOM_ARQUIVO,
													DES_PATHARQ,
													COD_USUCADA,                                                                                                            
													QTD_LISTA,
													COD_PERSONAS,
													COD_LISTA,
													COD_MAILING_EXT,
													ID_CONTROLEIBOPE,
													LOG_ENVIO,
													COD_EXT_TEMPLATE
											  )VALUES(
													'$cod_ext_segmento',
													'" . @$retornoEnvio["body"]["code"] . "',
													'" . date('Y-m-d H:i:s') . "',
													" . $cod_campanha . ",
													" . $cod_empresa . ",
													'0',
													'3',
													'$nomeRel',
													'" . $arquivodinamize . "',
													9999,                                                                                                               
													'" . $linhas . "',
													'" . $cod_personas . "',    
													'" . $cod_lista . "',
													'" . $cod_mailing_ext . "',
													'1',
													'S',
													'" . $html["COD_EXT_TEMPLATE"] . "'
													)";
					mysqli_query($contemporaria, $sqlControle);
					fnLog(array("DESCRICAO" => "Inserir na tabela EMAIL_LOTE", "COD_EMPRESA" => $cod_empresa, "SQL" => $sqlControle, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));


					$sqlControle = "INSERT IGNORE INTO email_lista_ret(
													COD_CAMPANHA,
													COD_CLIENTE,
													COD_EMPRESA,
													ID_DISPARO
											  ) SELECT
													'0" . $cod_campanha . "',
													COD_CLIENTE,
													'0" . $cod_empresa . "',
													'" . @$retornoEnvio["body"]["code"] . "'
												FROM CLIENTES
												WHERE COD_CLIENTE IN (0$cods_cliente)";
					mysqli_query($contemporaria, $sqlControle);
					fnLog(array("DESCRICAO" => "Insere na tabela email_lista_ret", "COD_EMPRESA" => $cod_empresa, "SQL" => $sqlControle, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));

					$rsemail_fila["COD_ENVIADO"] = "S";
					$sql = "INSERT IGNORE INTO email_filavalidades (
								DT_CADASTR,COD_EMPRESA,COD_UNIVEND,COD_CLIENTE,NUM_CGCECPF,NOM_CLIENTE,DT_NASCIME,
								DES_EMAILUS,NUM_CELULAR,VAL_RESGATE,VAL_CRED_ACUMULADO,COD_SEXOPES,COD_CAMPANHA,TIP_MOMENTO,
								TIP_CONTROLE,TIP_FILA,TIP_GATILHO,VAL_EXPIRAR,DAT_EXPIRA,SEMANA,MES,COD_ENVIADO
							) (
							SELECT
								NOW(),COD_EMPRESA,COD_UNIVEND,COD_CLIENTE,NUM_CGCECPF,NOM_CLIENTE,DT_NASCIME,
								DES_EMAILUS,'' NUM_CELULAR,VAL_RESGATE,VAL_CRED_ACUMULADO,COD_SEXOPES,COD_CAMPANHA,TIP_MOMENTO,
								TIP_CONTROLE,TIP_FILA,TIP_GATILHO,VAL_EXPIRAR,DAT_EXPIRA,SEMANA,MES,'S' COD_ENVIADO
							FROM email_fila
							WHERE
								TIP_GATILHO='$tip_gatilho' AND
								COD_EMPRESA=$cod_empresa AND 
								COD_CAMPANHA=$cod_campanha AND
								COD_ENVIADO='N' AND
								COD_CLIENTE IN ($cods_cliente)
							)";
					mysqli_query($contemporaria, $sql);
					if (mysqli_error($contemporaria) <> "") {
						fnLog(array("DESCRICAO" => "Erro SQL ao inserir na email_filavalidades!", "COD_EMPRESA" => $cod_empresa, "SQL" => $sql, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha, "LAYOUT" => "text-danger"));
						// continue;
					}


					$sql = "UPDATE email_fila SET COD_ENVIADO='S',DT_ALTERAC=NOW()
								WHERE
									TIP_FILA IN (2,5,6,9) AND
									TIP_GATILHO='$tip_gatilho' AND
									COD_EMPRESA=$cod_empresa AND 
									COD_CAMPANHA=$cod_campanha AND
									COD_ENVIADO='N' AND
									COD_CLIENTE IN ($cods_cliente)";
					fnLog(array("DESCRICAO" => "Update na tabela email_fila", "COD_EMPRESA" => $cod_empresa, "SQL" => $sql, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "COD_CAMPANHA" => $cod_campanha));
					mysqli_query($contemporaria, $sql);
					if (mysqli_error($contemporaria) <> "") {
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
					fnLog(array("DESCRICAO" => "<b>Não Enviado</b>", "COD_EMPRESA" => $cod_empresa, "JSON" => json_encode($retornoEnvio), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "ERRO" => json_encode($retornoEnvio), "COD_CAMPANHA" => $cod_campanha, "LAYOUT" => "text-danger"));
				}
			} else {
				fnLog(array("DESCRICAO" => "<b>Não Enviado</b>", "COD_EMPRESA" => $cod_empresa, "JSON" => json_encode($retornoSegmento), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "ERRO" => json_encode($retornoSegmento), "COD_CAMPANHA" => $cod_campanha, "LAYOUT" => "text-danger"));
			}
		} else {
			fnLog(array("DESCRICAO" => "<b>Não Enviado</b>", "COD_EMPRESA" => $cod_empresa, "JSON" => json_encode($retornoContatos), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "ERRO" => json_encode($retornoContatos), "COD_CAMPANHA" => $cod_campanha, "LAYOUT" => "text-danger"));
		}
	}
}


fnLog(array("DESCRICAO" => "Fim da Rotina", "LAYOUT" => "text-success"));
echo "</pre>";


$sql = "UPDATE gatilhos_logs_exec SET DATAHORA_FIM=NOW() WHERE UID='" . $uuid . "' AND COD_EXECUCAO='" . str_replace("-", "", str_replace(":", "", str_replace(" ", "", $ini_rotina))) . "'";
$rs = mysqli_query($conadmin, $sql);
