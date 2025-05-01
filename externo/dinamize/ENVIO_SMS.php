<?php
require '../../_system/_functionsMain.php';
//require '../../_system/func_nexux/func_nexux.php';
include '../../_system/func_nexux/func_transacional.php';

//https://bunker.mk/externo/dinamize/ENVIO_SMS.php?COD_EMPRESA=328&TIP_GATILHO=inativos&ENVIAR=S

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

$sql = "SELECT COUNT(0) QTD,TIMESTAMPDIFF(MINUTE,MAX(DATAHORA_INICIO),NOW()) TEMPO FROM gatilhos_logs_exec WHERE TIPO='SMS' AND DATAHORA_ATUALIZACAO_EMPRE IS NULL";
$rs = mysqli_query($conadmin, $sql);
$linha = mysqli_fetch_assoc($rs);
if ($linha["QTD"] > 0 && $linha["TEMPO"] <= 5) {
	if (@$_GET["COD_EMPRESA"] == "") {
		fnLog(array("DESCRICAO" => "[ EXISTE UMA ROTINA AINDA EM EXECUÇÃO INICIADA A POUCO TEMPO ]"));
		exit;
	}
}

$sql = "INSERT INTO gatilhos_logs_exec (UID,COD_EXECUCAO,TIPO,DATAHORA_INICIO,CODS_EMPRESA) VALUES "
	. "('" . $uuid . "','" . str_replace("-", "", str_replace(":", "", str_replace(" ", "", $ini_rotina))) . "','SMS',NOW(),0)";
$rs = mysqli_query($conadmin, $sql);

function fnLog($dados = array())
{
	global $ini_rotina, $gera_log, $conadmin, $sequencia, $uuid;

	$sql = "UPDATE gatilhos_logs_exec SET DATAHORA_ATUALIZACAO_LOG=NOW() WHERE UID='" . $uuid . "' AND COD_EXECUCAO='" . str_replace("-", "", str_replace(":", "", str_replace(" ", "", $ini_rotina))) . "'";
	$rs = mysqli_query($conadmin, $sql);

	if ($gera_log == "S") {
		foreach ($dados as $k => $v) {
			$dados[$k] = str_replace("'", "''", $v);
		}
		$sql_log = "INSERT INTO gatilhos_logs
					(COD_EXECUCAO,DATAHORA_INICIO,DATAHORA,TIPO,SEQUENCIA,COD_EMPRESA,DESCRICAO,QUERY,ERRO,JSON,COD_GATILHO,TIP_GATILHO)
					VALUES
					(
					'" . str_replace("-", "", str_replace(":", "", str_replace(" ", "", $ini_rotina))) . "',
					'$ini_rotina',
					'" . date("Y-m-d H:i:s") . "',
					'SMS',
					'$sequencia',
					'0" . @$dados["COD_EMPRESA"] . "',
					'" . @$dados["DESCRICAO"] . "',
					'" . @$dados["SQL"] . "',
					'" . @$dados["ERRO"] . "',
					'" . @$dados["JSON"] . "',
					'0" . @$dados["COD_GATILHO"] . "',
					'" . @$dados["TIP_GATILHO"] . "'
					)
					";
		$rw = mysqli_query($conadmin, $sql_log);
		if (mysqli_error($conadmin) <> "") {
			echo $sql_log;
		}
	}
	$sequencia++;
	echo date("Y-m-d H:i:s") . " - " . json_encode($dados) . "<br>";
}


echo "<pre>";
fnLog(array("DESCRICAO" => "[ INÍCIO ROTINA ]"));

$where = "";
if (@$_GET["COD_EMPRESA"] <> "") {
	$where .= "AND apar.COD_EMPRESA in (0" . @$_GET["COD_EMPRESA"] . ")";
}

/*EMPRESAS************************************************************************************************************************/
$sqlempresa = "SELECT * FROM senhas_parceiro apar
				INNER JOIN parceiro_comunicacao par ON par.COD_PARCOMU=apar.COD_PARCOMU
				WHERE par.COD_TPCOM='2' AND apar.COD_PARCOMU='17' AND apar.LOG_ATIVO='S'
				$where
				";

$rwempresa = mysqli_query($conadmin, $sqlempresa);
fnLog(array("DESCRICAO" => "Lista Empresas", "SQL" => $sqlempresa, "ERRO" => mysqli_error($conadmin)));
$count_empre = 0;
$tot_empre = mysqli_num_rows($rwempresa);
while ($rsempresa = mysqli_fetch_assoc($rwempresa)) {
	$cod_empresa = $rsempresa['COD_EMPRESA'];
	$contemporaria = connTemp($cod_empresa, '');
	$DES_CLIEXT = $rsempresa['DES_CLIEXT'];
	$count_empre++;

	$sql = "UPDATE gatilhos_logs_exec SET DATAHORA_ATUALIZACAO_EMPRE=NOW(), CODS_EMPRESA=CONCAT(CODS_EMPRESA,',',$cod_empresa) WHERE UID='" . $uuid . "' AND COD_EXECUCAO='" . str_replace("-", "", str_replace(":", "", str_replace(" ", "", $ini_rotina))) . "'";
	$rs = mysqli_query($conadmin, $sql);

	fnLog(array("DESCRICAO" => "Início EMPRESA $cod_empresa [ $count_empre / $tot_empre ]", "COD_EMPRESA" => $cod_empresa, "JSON" => json_encode($rsempresa)));

	if ((int)$horaatual == 0 && (int)$minutoatual <= 30) {
		/*LIMPA REGISTROS ANTIGOS*********************************************************************************/
		$sql = "DELETE FROM email_fila WHERE DATE_ADD(DT_CADASTR,INTERVAL 32 DAY) < DATE(NOW())";
		$rs = mysqli_query($contemporaria, $sql);
		fnLog(array("DESCRICAO" => "Limpa registros da EMAIL_FILA com mais de 32 dias de cadastro", "SQL" => $sql, "COD_EMPRESA" => $cod_empresa, "ERRO" => mysqli_error($contemporaria)));

		$sql = "DELETE FROM email_filavalidades WHERE DATE_ADD(DT_CADASTR,INTERVAL 90 DAY) < DATE(NOW())";
		$rs = mysqli_query($contemporaria, $sql);
		fnLog(array("DESCRICAO" => "Limpa registros da EMAIL_FILAVALIDADES com mais de 90 dias de cadastro", "SQL" => $sql, "COD_EMPRESA" => $cod_empresa, "ERRO" => mysqli_error($contemporaria)));

		$sql = "DELETE FROM gatilhos_logs WHERE DATE_ADD(DATAHORA_INICIO,INTERVAL 90 DAY) < DATE(NOW())";
		$rs = mysqli_query($conadmin, $sql);
		fnLog(array("DESCRICAO" => "Limpa registros da gatilhos_logs com mais de 90 dias de cadastro", "SQL" => $sql, "COD_EMPRESA" => $cod_empresa, "ERRO" => mysqli_error($contemporaria)));
	}



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
	$gatilhos = array("individual", "cadastro", "resgate", "venda", "aniv", "anivSem", "anivQuinz", "anivMes", "anivDia", "anivCad", "credExp", "inativos", "credVen"); // <-- Colocar na ordem do select
	if (@$_GET["TIP_GATILHO"] <> "") {
		$gatilhos = array($_GET["TIP_GATILHO"]);
	}
	$gatilhos_impl_in = "'" . (implode("','", $gatilhos)) . "'";
	$gatilhos_impl_ord = "'" . (implode(",", $gatilhos)) . "'";
	$sqlgatilho = "SELECT * FROM gatilho_sms gt
						INNER JOIN campanha cp ON gt.COD_CAMPANHA=cp.COD_CAMPANHA 
						INNER JOIN sms_parametros  p ON p.COD_EMPRESA=gt.cod_empresa AND p.COD_CAMPANHA=gt.cod_campanha
							AND COD_LISTA IN (SELECT MAX(COD_LISTA) FROM sms_parametros WHERE COD_EMPRESA=gt.cod_empresa AND COD_CAMPANHA=gt.cod_campanha)
					WHERE gt.TIP_GATILHO IN ($gatilhos_impl_in)
						AND gt.LOG_STATUS ='S'
						AND cp.LOG_ATIVO = 'S'
						AND STR_TO_DATE(CONCAT(cp.DAT_INI,' ',cp.HOR_INI),'%Y-%m-%d %H:%i:%s') <= NOW()
						AND STR_TO_DATE(CONCAT(cp.DAT_FIM,' ',cp.HOR_FIM),'%Y-%m-%d %H:%i:%s') >= NOW()
						AND gt.cod_empresa=$cod_empresa
						" . (@$_GET["COD_CAMPANHA"] <> "" ? " AND gt.COD_CAMPANHA = '0" . $_GET["COD_CAMPANHA"] . "'" : "") . "
					GROUP BY gt.COD_CAMPANHA
					ORDER BY FIND_IN_SET(gt.TIP_GATILHO, $gatilhos_impl_ord)";
	$rwgatilho = mysqli_query($contemporaria, $sqlgatilho);
	fnLog(array("DESCRICAO" => "Obter dados do GATILHO", "COD_EMPRESA" => $cod_empresa, "SQL" => $sqlgatilho, "ERRO" => mysqli_error($contemporaria)));
	$count_gati = 0;
	$tot_gati = mysqli_num_rows($rwgatilho);
	while ($rsgatilho = mysqli_fetch_assoc($rwgatilho)) {
		$count_gati++;
		$cod_gatilho = $rsgatilho["COD_GATILHO"];
		$cod_campanha = $rsgatilho["COD_CAMPANHA"];
		$tip_gatilho = $rsgatilho["TIP_GATILHO"];
		$tip_controle = $rsgatilho["TIP_CONTROLE"];
		$tip_momento = $rsgatilho["TIP_MOMENTO"];
		$log_ativo = $rsgatilho["LOG_ATIVO"];
		$datetimecampanha = $rsgatilho["DAT_FIM"] . ' ' . $rsgatilho["HOR_FIM"];
		$log_processa = $rsgatilho["LOG_PROCESSA_SMS"];
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

		fnLog(array("DESCRICAO" => "Dados do GATILHO $cod_gatilho (log_process=$log_process / tip_gatilho=$tip_gatilho / des_periodo=$des_periodo / tip_controle (periodicidade)=$tip_controle / tip_momento=$tip_momento / cod_campanha=$cod_campanha / cod_persona=$cod_personas / dias_hist=$dias_hist) [ $count_gati / $tot_gati ]", "COD_EMPRESA" => $cod_empresa, "JSON" => json_encode($rsgatilho), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));

		if (@$_GET["ENVIAR"] == "S") {
			fnLog(array("DESCRICAO" => "Forçar execução do GATILHO (log_process=$log_process / tip_gatilho=$tip_gatilho / des_periodo=$des_periodo / tip_controle (periodicidade)=$tip_controle / tip_momento=$tip_momento / cod_campanha=$cod_campanha / cod_persona=$cod_personas / dias_hist=$dias_hist) [ $count_gati / $tot_gati ]", "COD_EMPRESA" => $cod_empresa, "JSON" => json_encode($rsgatilho), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));
			$gravacao = true;
			$process = true;
		} else {

			if ($des_periodo == 7 || $des_periodo == 15) {
				$d = date("w");
				fnLog(array("DESCRICAO" => "---- calc=$d ---- semana=" . date("w") . " ---- dias antec=" . $dias_anteced . " ---- mod=" . ($d % 7), "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));
				if (($d % 7) <> 0) {
					fnLog(array("DESCRICAO" => "Gatilho semanal / quinzenal fora do período. (período: $des_periodo / antecedência: $dias_anteced / referencica: DOMINGO)", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));
					continue;
				} else {
					fnLog(array("DESCRICAO" => "Gatilho semanal / quinzenal. (período: $des_periodo / antecedência: $dias_anteced / referencica: DOMINGO)", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));
				}
			} elseif ($des_periodo == 30) {
				$d = date('d', strtotime(date("Y-m-d") . " + 0 days"));
				fnLog(array("DESCRICAO" => "---- calc=$d ----  dias antec=" . $dias_anteced . " ---- ", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));
				if ($d <> "01" && $d <> "1") {
					fnLog(array("DESCRICAO" => "Gatilho mensal fora do período. (período: $des_periodo / antecedência: $dias_anteced / referencica: DIA 1&ordm;)", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));
					continue;
				} else {
					fnLog(array("DESCRICAO" => "Gatilho mensal. (período: $des_periodo / antecedência: $dias_anteced / referencica: DIA 1&ordm;)", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));
				}
			}


			//Checa o horário de envio
			$process = true;
			$gravacao = false;
			if ($tip_momento < 24 && $tip_momento > 0 && ($tip_momento - 1) == $horaatual && (int)$minutoatual >= 20 && (int)$minutoatual < 40) {
				fnLog(array("DESCRICAO" => "Enviar comando para gravar dados do gatilho (Hora atual: $horaatual / Hora de envio: $tip_momento)!", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));
				$gravacao = true;
			}
			if ($tip_momento >= 24) {
				fnLog(array("DESCRICAO" => "Gatilho imediato - gravar dados (Hora atual: $horaatual / Hora de envio: $tip_momento)!", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));
				$gravacao = true;
			}

			if ($tip_momento < 24 && $tip_momento > 0 && $tip_momento != $horaatual) {
				fnLog(array("DESCRICAO" => "Fora do horário de envio (Hora atual: $horaatual / Hora de envio: $tip_momento)!", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));
				$process = false;
			}
			if ($tip_momento < 24 && $tip_momento > 0 && (int)$minutoatual >= 20) {
				fnLog(array("DESCRICAO" => "Fora do minuto de envio (Hora atual: $horaatual:$minutoatual / Hora de envio: $tip_momento)!", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));
				$process = false;
			}
			if ($tip_momento >= 24 && (int)$minutoatual <= 20 && $horaatual == 8) {
				fnLog(array("DESCRICAO" => "Gatilho imediato adiado para não sobrecarregar gatilhos agendados! (Hora atual: $horaatual:$minutoatual / Hora de envio: $tip_momento)!", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));
				continue;
			}

			if (!$process) {
				if ($log_process == "S" && ((int)$minutoatual < 20)) {
					fnLog(array("DESCRICAO" => "Gatilho não enviado no horário agendado. REPROCESSANDO.... (Hora atual: $horaatual / Hora de envio: $tip_momento)!", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));
					fnLog(array("DESCRICAO" => "REPROCESSAMENTO CANCELADO.... (Hora atual: $horaatual / Hora de envio: $tip_momento)!", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));
					continue;
				} elseif ($gravacao) {
					fnLog(array("DESCRICAO" => "GRAVANDO DADOS para envio agendado.... (Hora atual: $horaatual / Hora de envio: $tip_momento)!", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));
				} else {
					continue;
				}
			} else {
				fnLog(array("DESCRICAO" => "Dentro do horário de envio (Hora atual: $horaatual:$minutoatual / Hora de envio: $tip_momento)!", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));
			}
		}

		/*MARCA GATILHO PARA SER EXECUTADO*********************************************************************************/
		$sql = "UPDATE gatilho_sms SET LOG_PROCESS_GATILHO='S',DATHOR_PROCESS=NOW() WHERE COD_GATILHO=$cod_gatilho AND COD_EMPRESA=$cod_empresa";
		$rs = mysqli_query($contemporaria, $sql);
		fnLog(array("DESCRICAO" => "Gatilho marcado para ser executado!", "SQL" => $sql, "COD_EMPRESA" => $cod_empresa, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));

		if ($gravacao) {
			if ($tip_gatilho == "aniv" || $tip_gatilho == "anivDia" || $tip_gatilho == "anivSem" || $tip_gatilho == "anivQuinz" || $tip_gatilho == "anivMes") {
				$tip_gatilho = "aniv";

				//$sqlIns = "DELETE FROM email_fila WHERE COD_EMPRESA = $cod_empresa AND TIP_GATILHO = '$tip_gatilho' AND COD_ENVIADO='N' AND COD_CAMPANHA = '$cod_campanha' AND TIP_FILA IN (3,7,8,10)";
				$sqlIns = "UPDATE email_fila SET COD_ENVIADO='Y',DT_ALTERAC=NOW() WHERE COD_EMPRESA = $cod_empresa AND TIP_GATILHO = '$tip_gatilho' AND COD_ENVIADO='N' AND COD_CAMPANHA = '$cod_campanha' AND TIP_FILA IN (3,7,8,10)";
				mysqli_query($contemporaria, $sqlIns);
				fnLog(array("DESCRICAO" => "Apagando não enviados da EMAIL_FILA", "COD_EMPRESA" => $cod_empresa, "SQL" => $sqlIns, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));

				$where = "";
				switch ($des_periodo) {
					case 7:
						$where = "WEEK( STR_TO_DATE(CONCAT(SUBSTR(C.DAT_NASCIME,1,5),'/" . date("Y") . "'),'%d/%m/%Y') ) = WEEK(CURDATE())";
						break;
					case 15:
						$where = "(
									WEEK( STR_TO_DATE(CONCAT(SUBSTR(C.DAT_NASCIME,1,5),'/" . date("Y") . "'),'%d/%m/%Y') ) = WEEK(CURDATE()) OR
									WEEK( STR_TO_DATE(CONCAT(SUBSTR(C.DAT_NASCIME,1,5),'/" . date("Y") . "'),'%d/%m/%Y') ) = WEEK(DATE_ADD(CURDATE(),INTERVAL 7 DAY)) OR
								  )";
						break;
					case 30:
					case 99:
						$where = "MONTH( STR_TO_DATE(CONCAT(SUBSTR(C.DAT_NASCIME,1,5),'/" . date("Y") . "'),'%d/%m/%Y') ) = MONTH(CURDATE())";
						break;
					default:
						$where = "DAY( STR_TO_DATE(CONCAT(SUBSTR(C.DAT_NASCIME,1,5),'/" . date("Y") . "'),'%d/%m/%Y') ) = DAY(CURDATE()) AND
								  MONTH( STR_TO_DATE(CONCAT(SUBSTR(C.DAT_NASCIME,1,5),'/" . date("Y") . "'),'%d/%m/%Y') ) = MONTH(CURDATE())";
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

				$sqlIns = "INSERT IGNORE INTO email_fila (LOG_BLACKLIST_SMS,DT_CADASTR,COD_EMPRESA,COD_UNIVEND,COD_CLIENTE,NUM_CGCECPF,NOM_CLIENTE,DT_NASCIME,DES_EMAILUS,NUM_CELULAR,VAL_RESGATE,
						VAL_CRED_ACUMULADO,COD_SEXOPES,COD_CAMPANHA,TIP_MOMENTO,TIP_CONTROLE,TIP_FILA,TIP_GATILHO,VAL_EXPIRAR,DAT_EXPIRA,SEMANA,MES,COD_ENVIADO)
						SELECT
							'S',NOW() DT_CADASTR,C.COD_EMPRESA,C.COD_UNIVEND,C.COD_CLIENTE,C.NUM_CGCECPF,C.NOM_CLIENTE,C.DAT_NASCIME ,
							C.DES_EMAILUS,C.NUM_CELULAR,0 VAL_RESGATE,0 VAL_CRED_ACUMULADO,C.COD_SEXOPES,$cod_campanha COD_CAMPANHA,'$tip_momento' TIP_MOMENTO,
							'$tip_controle' TIP_CONTROLE,3 TIP_FILA,'$tip_gatilho' TIP_GATILHO,0 VAL_EXPIRAR,NULL DAT_EXPIRA,'' SEMANA,'' MES,'N' COD_ENVIADO
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

				fnLog(array("DESCRICAO" => "Gravando aniversariantes na tabela EMAIL_FILA [" . $qtd["QTD"] . "]", "COD_EMPRESA" => $cod_empresa, "SQL" => $sqlIns, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));

				//$sqlIns = "DELETE FROM email_fila WHERE DT_NASCIME='31/12/1969' AND TIP_CONTROLE = '$tip_controle' AND COD_EMPRESA = $cod_empresa";
				$sqlIns = "UPDATE email_fila SET COD_ENVIADO='Y',DT_ALTERAC=NOW() WHERE DT_NASCIME='31/12/1969' AND TIP_CONTROLE = '$tip_controle' AND COD_EMPRESA = $cod_empresa";
				mysqli_query($contemporaria, $sqlIns);
				fnLog(array("DESCRICAO" => "Apagando datas inválidas EMAIL_FILA", "COD_EMPRESA" => $cod_empresa, "SQL" => $sqlIns, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));
			} elseif ($tip_gatilho == "anivCad") {

				//$sqlIns = "DELETE FROM email_fila WHERE COD_EMPRESA = $cod_empresa AND TIP_GATILHO = '$tip_gatilho' AND COD_ENVIADO='N' AND COD_CAMPANHA = '$cod_campanha'";
				$sqlIns = "UPDATE email_fila SET COD_ENVIADO='Y',DT_ALTERAC=NOW() WHERE COD_EMPRESA = $cod_empresa AND TIP_GATILHO = '$tip_gatilho' AND COD_ENVIADO='N' AND COD_CAMPANHA = '$cod_campanha'";
				mysqli_query($contemporaria, $sqlIns);
				fnLog(array("DESCRICAO" => "Apagando não enviados da EMAIL_FILA", "COD_EMPRESA" => $cod_empresa, "SQL" => $sqlIns, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));

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
				$sqlIns = "INSERT IGNORE INTO email_fila (LOG_BLACKLIST_SMS,DT_CADASTR,COD_EMPRESA,COD_UNIVEND,COD_CLIENTE,NUM_CGCECPF,NOM_CLIENTE,DT_NASCIME,DES_EMAILUS,NUM_CELULAR,VAL_RESGATE,
						VAL_CRED_ACUMULADO,COD_SEXOPES,COD_CAMPANHA,TIP_MOMENTO,TIP_CONTROLE,TIP_FILA,TIP_GATILHO,VAL_EXPIRAR,DAT_EXPIRA,SEMANA,MES,COD_ENVIADO)
						SELECT
							'S',NOW() DT_CADASTR,C.COD_EMPRESA,C.COD_UNIVEND,C.COD_CLIENTE,C.NUM_CGCECPF,C.NOM_CLIENTE,C.DAT_NASCIME ,
							C.DES_EMAILUS,C.NUM_CELULAR,0 VAL_RESGATE,0 VAL_CRED_ACUMULADO,C.COD_SEXOPES,$cod_campanha COD_CAMPANHA,'$tip_momento' TIP_MOMENTO,
							'$tip_controle' TIP_CONTROLE,3 TIP_FILA,'$tip_gatilho' TIP_GATILHO,0 VAL_EXPIRAR,NULL DAT_EXPIRA,'' SEMANA,'' MES,'N' COD_ENVIADO
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

				fnLog(array("DESCRICAO" => "Gravando aniversariantes de cadastro na tabela EMAIL_FILA", "COD_EMPRESA" => $cod_empresa, "SQL" => $sqlIns, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));

				//$sqlIns = "DELETE FROM email_fila WHERE DT_NASCIME='31/12/1969' AND TIP_CONTROLE = '$tip_controle' AND COD_EMPRESA = $cod_empresa";
				$sqlIns = "UPDATE email_fila SET COD_ENVIADO='Y',DT_ALTERAC=NOW() WHERE DT_NASCIME='31/12/1969' AND TIP_CONTROLE = '$tip_controle' AND COD_EMPRESA = $cod_empresa";
				mysqli_query($contemporaria, $sqlIns);
				fnLog(array("DESCRICAO" => "Apagando datas inválidas EMAIL_FILA", "COD_EMPRESA" => $cod_empresa, "SQL" => $sqlIns, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));
			} elseif ($tip_gatilho == "credExp") {

				$dias_anteced = ($dias_anteced == "" ? 1 : $dias_anteced);
				$tot_saldomin = ($tot_saldomin <= 0 ? 1 : $tot_saldomin);
				$des_periodomax = ($des_periodomax  == "" ? 0 : $des_periodomax);
				$sqlIns = "INSERT IGNORE INTO email_fila (DT_CADASTR,COD_EMPRESA,COD_UNIVEND,COD_CLIENTE,NUM_CGCECPF,NOM_CLIENTE,DT_NASCIME,DES_EMAILUS,NUM_CELULAR,VAL_RESGATE,
							VAL_CRED_ACUMULADO,COD_SEXOPES,COD_CAMPANHA,TIP_MOMENTO,TIP_CONTROLE,TIP_FILA,TIP_GATILHO,VAL_EXPIRAR,DAT_EXPIRA,SEMANA,MES,COD_ENVIADO)
							SELECT  
								NOW() DT_CADASTR,C.COD_EMPRESA,C.COD_UNIVEND,C.COD_CLIENTE,C.NUM_CGCECPF,C.NOM_CLIENTE,NULL DT_NASCIME,
								C.DES_EMAILUS,C.NUM_CELULAR,0 VAL_RESGATE,0 VAL_CRED_ACUMULADO,C.COD_SEXOPES,$cod_campanha COD_CAMPANHA,'$tip_momento' TIP_MOMENTO,
								'$tip_controle' TIP_CONTROLE,3 TIP_FILA,'$tip_gatilho' TIP_GATILHO,0 VAL_EXPIRAR,NULL DAT_EXPIRA,'' SEMANA,'' MES,'N' COD_ENVIADO
							FROM PERSONACLASSIFICA A
							INNER JOIN CREDITOSDEBITOS B ON A.COD_CLIENTE=B.COD_CLIENTE
							INNER JOIN CLIENTES C ON  A.COD_CLIENTE=C.COD_CLIENTE  
							WHERE 
							A.cod_persona IN (0$cod_personas) AND
							A.COD_EMPRESA=$cod_empresa AND
							DATE(B.DAT_EXPIRA) >= DATE(DATE_ADD(NOW(),INTERVAL $dias_anteced DAY)) AND
							DATE(B.DAT_EXPIRA) <= DATE(DATE_ADD(NOW(),INTERVAL " . ($dias_anteced + $des_periodomax) . " DAY)) AND
							B.TIP_CREDITO='C' AND
							B.COD_STATUSCRED='1' 
							GROUP BY B.COD_CLIENTE      
							HAVING (SELECT SUM(val_saldo) FROM creditosdebitos E WHERE E.COD_CLIENTE=C.COD_CLIENTE AND COD_STATUSCRED=1) >= $tot_saldomin";
				mysqli_query($contemporaria, $sqlIns);
				fnLog(array("DESCRICAO" => "Gravando Créditos a Expirar", "COD_EMPRESA" => $cod_empresa, "SQL" => $sqlIns, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));
			} elseif ($tip_gatilho == "inativos") {

				$dias_anteced = ($dias_anteced == "" ? 1 : $dias_anteced);
				$tot_saldomin = ($tot_saldomin <= 0 ? 1 : $tot_saldomin);
				$des_periodomax = ($des_periodomax  == "" ? 0 : $des_periodomax);
				$sqlIns = "INSERT IGNORE INTO email_fila (DT_CADASTR,COD_EMPRESA,COD_UNIVEND,COD_CLIENTE,NUM_CGCECPF,NOM_CLIENTE,DT_NASCIME,DES_EMAILUS,NUM_CELULAR,VAL_RESGATE,
							VAL_CRED_ACUMULADO,COD_SEXOPES,COD_CAMPANHA,TIP_MOMENTO,TIP_CONTROLE,TIP_FILA,TIP_GATILHO,VAL_EXPIRAR,DAT_EXPIRA,SEMANA,MES,COD_ENVIADO)
							SELECT
								NOW() DT_CADASTR,BB.COD_EMPRESA,BB.COD_UNIVEND,BB.COD_CLIENTE,BB.NUM_CGCECPF,BB.NOM_CLIENTE,NULL DT_NASCIME,
								BB.DES_EMAILUS,BB.NUM_CELULAR,0 VAL_RESGATE,0 VAL_CRED_ACUMULADO,BB.COD_SEXOPES,$cod_campanha COD_CAMPANHA,NULL TIP_MOMENTO,
								NULL TIP_CONTROLE,3 TIP_FILA,'$tip_gatilho' TIP_GATILHO,0 VAL_EXPIRAR,NULL DAT_EXPIRA,'' SEMANA,'' MES,'N' COD_ENVIADO
							FROM clientes BB
							INNER JOIN PERSONACLASSIFICA A ON A.COD_CLIENTE=BB.COD_CLIENTE  
							WHERE BB.cod_empresa=$cod_empresa AND
							A.cod_persona IN (0$cod_personas) AND
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
				fnLog(array("DESCRICAO" => "Gravando Inativos", "COD_EMPRESA" => $cod_empresa, "SQL" => $sqlIns, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));
			} else {
				fnLog(array("DESCRICAO" => "Não há dados para serem gravados", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));
			}

			if (!$process) {
				/*MARCA GATILHO PARA SER EXECUTADO*********************************************************************************/
				$sql = "UPDATE gatilho_sms SET LOG_PROCESS_GATILHO='N',DATHOR_PROCESS=NOW() WHERE COD_GATILHO=$cod_gatilho";
				$rs = mysqli_query($contemporaria, $sql);
				fnLog(array("DESCRICAO" => "Gatilho marcado para ser executado!", "SQL" => $sql, "COD_EMPRESA" => $cod_empresa, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));

				fnLog(array("DESCRICAO" => "Gravação efetuada.... Aguardando envio (Hora atual: $horaatual / Hora de envio: $tip_momento)!", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));
				continue;
			} else {
				fnLog(array("DESCRICAO" => "Gravação efetuada.... gatilho imediato prosseguindo com envio (Hora atual: $horaatual / Hora de envio: $tip_momento)!", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));
			}
		}


		//VERIFICA se a campanha está ativa
		if ($log_ativo != 'S') {
			fnLog(array("DESCRICAO" => "CAMPANHA NÃO ESTÁ ATIVA!", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));
			continue;
		}

		//VERIFICA se a campanha está dentro da validade
		if ($datetimecampanha < $datahoraatual) {
			fnLog(array("DESCRICAO" => "CAMPANHA FORA DA VALIDADE!", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));
			continue;
		}


		//VERIFICA se a campanha está ativa para envio de e-mail/SMS
		if ($log_processa != 'S') {
			fnLog(array("DESCRICAO" => "CAMPANHA NÃO HABILITADA PARA ENVIO DE SMS! (log_processa_sms=$log_processa / cod_gatilho=$cod_gatilho / cod_campanha=$cod_campanha)", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));
			continue;
		}

		/*
		fnLog(array("DESCRICAO"=>"Rotina de blacklist....","COD_EMPRESA"=>$cod_empresa,"COD_GATILHO"=>$cod_gatilho,"TIP_GATILHO"=>$tip_gatilho));
		$sql = "CALL SP_REMOVE_FILA_BLACKLIST($cod_empresa,'$tip_gatilho','SMS')";
		mysqli_query($contemporaria, $sql);
		fnLog(array("DESCRICAO"=>"SMS da blacklist e em branco removidos","COD_EMPRESA"=>$cod_empresa,"SQL"=>$sql,"ERRO"=>mysqli_error($contemporaria),"COD_GATILHO"=>$cod_gatilho,"TIP_GATILHO"=>$tip_gatilho));
		if (mysqli_error($contemporaria) <> ""){continue;}
*/

		$sql = "DELETE em
					FROM email_fila em
					INNER JOIN unidadevenda un ON un.COD_UNIVEND = em.COD_UNIVEND AND un.LOG_ESTATUS = 'N'
					WHERE em.COD_EMPRESA = $cod_empresa;";
		$rs = mysqli_query($contemporaria, $sql);

		/*INICIO ROTINA DE ENVIO*/
		$tampletevariavel = "SELECT DES_TEMPLATE,COD_TEMPLATE,COD_EXT_TEMPLATE FROM TEMPLATE_sms T
						INNER JOIN mensagem_sms M ON M.COD_TEMPLATE_SMS=T.COD_TEMPLATE
						WHERE T.COD_EMPRESA='$cod_empresa' 
							AND COD_CAMPANHA='$cod_campanha' AND LOG_ATIVO='S'";

		$html = mysqli_fetch_assoc(mysqli_query($contemporaria, $tampletevariavel));
		fnLog(array("DESCRICAO" => "Carregando TEMPLATE", "COD_EMPRESA" => $cod_empresa, "SQL" => $tampletevariavel, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));

		//gera lista de variaveis	
		$msg_envio = $html['DES_TEMPLATE'];
		$cod_template = $html["COD_TEMPLATE"];
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
												email_fila.TIP_FILA IN (3,7,8,10) AND    
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
												email_fila.TIP_FILA IN (3,7,8,10) AND
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
		$selectCliente .= "C.COD_CLIENTE,C.NUM_CELULAR";
		//		$tagsDinamize = rtrim($tagsDinamize,',');

		$sqlcli_cad = "SELECT DISTINCT $selectCliente
						FROM clientes C
						INNER JOIN email_fila ON (email_fila.COD_CLIENTE = C.COD_CLIENTE)
						$innerjoin
						WHERE C.COD_EMPRESA = $cod_empresa
						AND C.LOG_SMS = 'S'
						AND C.LOG_FIDELIZADO = 'S'
						AND C.LOG_ESTATUS = 'S'
						AND TRIM(C.NUM_CELULAR) != ''
						AND email_fila.TIP_GATILHO='$tip_gatilho'
						AND email_fila.TIP_FILA IN (3,7,8,10) 
						" . (@$_GET["ENVIAR"] <> "S" ? "AND email_fila.LOG_BLACKLIST_SMS = 'S'" : "") . "
						" . ($tip_gatilho == "aniv" || $tip_gatilho == "anivCad" || $tip_gatilho == "credExp" || $tip_gatilho == "inativos" ? "AND DATE(email_fila.DT_CADASTR)=DATE(NOW())" : "") . "
						" . ($tip_gatilho == "cadastro" && $des_periodo <= 30 ? "AND DATE(email_fila.DT_CADASTR) < DATE(NOW())" : "") . "
						" . ($des_periodo ==  7 ? "AND (WEEK(email_fila.DT_CADASTR)>=WEEK(DATE_ADD(NOW(), INTERVAL -1 WEEK)) OR WEEK(email_fila.DT_CADASTR)=WEEK(NOW()))" : "") . "
						" . ($des_periodo == 15 ? "AND (WEEK(email_fila.DT_CADASTR)>=WEEK(DATE_ADD(NOW(), INTERVAL -2 WEEK)) OR WEEK(email_fila.DT_CADASTR)=WEEK(NOW()))" : "") . "
						" . ($des_periodo == 30 ? "AND (MONTH(DT_CADASTR)>=MONTH(DATE_ADD(NOW(), INTERVAL -1 MONTH)) OR MONTH(DT_CADASTR)=MONTH(NOW()))" : "") . "
						AND email_fila.COD_EMPRESA=C.COD_EMPRESA
						AND email_fila.COD_CAMPANHA=$cod_campanha
						AND email_fila.COD_ENVIADO='N'
						" . ($tip_gatilho == "cadastro" && $des_periodo > 30 ? "" :
			"AND 
								email_fila.COD_CLIENTE IN (
									SELECT DISTINCT COD_CLIENTE
										FROM personaclassifica
										WHERE cod_persona IN (0$cod_personas)
										AND cod_empresa=$cod_empresa
								)
							") . "
						group by C.COD_CLIENTE
						" .
			($tip_gatilho == "venda" ?
				"HAVING (SELECT SUM(val_saldo) FROM creditosdebitos E WHERE E.COD_CLIENTE=C.COD_CLIENTE AND E.COD_STATUSCRED=1) >= $tot_saldomin" :
				""
			);

		// echo $sqlcli_cad;
		$rwsql = mysqli_query($contemporaria, $sqlcli_cad);
		fnLog(array("DESCRICAO" => "Carrega clientes... Qtd. Retornado: " . mysqli_num_rows($rwsql), "COD_EMPRESA" => $cod_empresa, "SQL" => $sqlcli_cad, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));
		$CLIE_CAD = array();
		$CLIE_SMS = array();
		$CLIE_CAD_L = array();
		$CLIE_SMS_L = array();
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
			$textoenvio = $html['DES_TEMPLATE'];
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
			//echo $textoenvio."<br>";
			//print_r($rsemail_fila);
			$NOM_CLIENTE = explode(" ", ucfirst(strtolower(fnAcentos($rsemail_fila['NOM_CLIENTE']))));
			$textoenvio = str_replace('<#NOME>', @$NOM_CLIENTE[0], $textoenvio);
			$textoenvio = str_replace('<#SALDO>', @$rsemail_fila['CREDITO_DISPONIVEL'], $textoenvio);
			$textoenvio = str_replace('<#NOMELOJA>',  @$rsemail_fila['NOM_FANTASI'], $textoenvio);
			$textoenvio = str_replace('<#ANIVERSARIO>', @$rsemail_fila['DAT_NASCIME'], $textoenvio);
			$textoenvio = str_replace('<#ANIVERSARIOCAD>', @$rsemail_fila['DAT_CADASTRO'], $textoenvio);
			$textoenvio = str_replace('<#DATAEXPIRA>', fnDataShort($rsemail_fila['DAT_EXPIRA']), $textoenvio);
			$textoenvio = str_replace('<#EMAIL>', @$rsemail_fila['DES_EMAILUS'], $textoenvio);
			$textoenvio = str_replace('<#RESGATE>', @$rsemail_fila['VAL_RESGATE'], $textoenvio);
			$textoenvio = str_replace('<#SALDOEXPIRA>', @$rsemail_fila['VAL_EXPIRAR'], $textoenvio);
			$textoenvio = str_replace('<#CREDITOVENDA>', @$rsemail_fila['CRED_VENDA'], $textoenvio);
			$textoenvio = str_replace('<#DATAEXPIRAMAX>', fnDataShort($rsemail_fila['DATAEXPIRAMAX']), $textoenvio);


			//$textoenvio=str_replace('<#LINKATIVACAO>', 'http://'.@$DES_DOMINIO.'.mais.cash/active.do?idC='.fnEncode($cod_cliente), $textoenvio);
			if ($COD_DOMINIO == '1') {
				$textoenvio = str_replace('<#LINKTOKEN>', 'https://' . @$DES_DOMINIO . '.mais.cash/ativacao.do', $textoenvio);
				$textoenvio = str_replace('<#LINKATIVACAO>', 'https://' . @$DES_DOMINIO . '.mais.cash/ativacao.do', $textoenvio);
			}
			if ($COD_DOMINIO == '2') {
				$textoenvio = str_replace('<#LINKTOKEN>', 'https://' . @$DES_DOMINIO . '.fidelidade.mk/ativacao.do', $textoenvio);
				$textoenvio = str_replace('<#LINKATIVACAO>', 'https://' . @$DES_DOMINIO . '.fidelidade.mk/ativacao.do', $textoenvio);
			}

			$textoenvio = nl2br($textoenvio, true);
			$textoenvio = str_replace('<br />', ' \n ', $textoenvio);
			$textoenvio = str_replace("'", "", $textoenvio);
			// echo $textoenvio;exit;

			if (strlen($num_celular) == 12) {
				$inicio = substr($rsemail_fila['NUM_CELULAR'], 0, 4);
				$fim =  substr($rsemail_fila['NUM_CELULAR'], 4, 10);
				$tel = $inicio . '9' . $fim;
			} else {
				$tel = fnLimpaDoc($num_celular);
			}
			//$tel = "55".$tel;
			$rsemail_fila["NUM_CELULAR"] = $tel;

			$desc_cliente = "CLIENTE $cod_cliente / CELULAR $tel ";
			//fnLog(array("DESCRICAO"=>"CLIENTE $cod_cliente / CELULAR $tel / controle = $tip_controle)","COD_EMPRESA"=>$cod_empresa,"COD_GATILHO"=>$cod_gatilho,"TIP_GATILHO"=>$tip_gatilho));

			//Checa se está preenchido
			if (trim($num_celular) == "") {
				$msg_env .= PHP_EOL . "$desc_cliente - Celular não preenchido!";
				//fnLog(array("DESCRICAO"=>"$desc_cliente - Celular não preenchido!","COD_EMPRESA"=>$cod_empresa,"COD_GATILHO"=>$cod_gatilho,"TIP_GATILHO"=>$tip_gatilho));
				continue;
			}

			//Checa se está na blacklist
			/*
			$sql = "SELECT COUNT(0) QTD FROM blacklist_sms WHERE COD_EMPRESA=$cod_empresa AND AND TRIM(NUM_CELULAR)='$tel'";
			$rs = mysqli_query($contemporaria, $sql);
			$linha = mysqli_fetch_assoc($rs);
			if ($linha["QTD"] > 0){
				fnLog(array("DESCRICAO"=>"$desc_cliente - Celular na blacklist!","COD_EMPRESA"=>$cod_empresa,"COD_GATILHO"=>$cod_gatilho,"TIP_GATILHO"=>$tip_gatilho));
				continue;
			}
*/

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
							AND WEEK(DT_CADASTR)=WEEK(NOW())
							AND YEAR(DT_CADASTR)=YEAR(NOW())
							AND COD_EMPRESA=$cod_empresa
							$where";
				$rs = mysqli_query($contemporaria, $sql);
				$linha = mysqli_fetch_assoc($rs);
				if ($linha["QTD"] > 0) {
					$msg_env .= PHP_EOL . "$desc_cliente - SMS já enviado esta SEMANA!";
					//fnLog(array("DESCRICAO"=>"$desc_cliente - SMS já enviado esta SEMANA!","COD_EMPRESA"=>$cod_empresa,"SQL"=>$sql,"ERRO"=>mysqli_error($contemporaria),"COD_GATILHO"=>$cod_gatilho,"TIP_GATILHO"=>$tip_gatilho));
					continue;
				}
			} elseif ($tip_controle == 15) {
				//1 vez a cada 15 dias
				//Checa se já foi enviado nos últimos 15 dias
				$sql = "SELECT COUNT(0) QTD FROM email_filavalidades
							WHERE NUM_CELULAR = '$num_celular_ori'
							AND DATE(DT_CADASTR) > DATE(DATE_ADD(NOW(), INTERVAL -13 DAY))
							AND COD_EMPRESA=$cod_empresa
							$where";
				$rs = mysqli_query($contemporaria, $sql);
				$linha = mysqli_fetch_assoc($rs);
				if ($linha["QTD"] > 0) {
					$msg_env .= PHP_EOL . "$desc_cliente - SMS já enviado dentro de 15 DIAS!";
					//fnLog(array("DESCRICAO"=>"$desc_cliente - SMS já enviado dentro de 15 DIAS!","COD_EMPRESA"=>$cod_empresa,"SQL"=>$sql,"ERRO"=>mysqli_error($contemporaria),"COD_GATILHO"=>$cod_gatilho,"TIP_GATILHO"=>$tip_gatilho));
					continue;
				}
			} elseif ($tip_controle == 30) {
				//1 vez no mês
				//Checa se já foi enviado esse mes
				$sql = "SELECT COUNT(0) QTD FROM email_filavalidades
							WHERE NUM_CELULAR = '$num_celular_ori'
							AND MONTH(DT_CADASTR)=MONTH(NOW())
							AND YEAR(DT_CADASTR)=YEAR(NOW())
							AND COD_EMPRESA=$cod_empresa
							$where";
				$rs = mysqli_query($contemporaria, $sql);
				$linha = mysqli_fetch_assoc($rs);
				if ($linha["QTD"] > 0) {
					$msg_env .= PHP_EOL . "$desc_cliente - SMS já enviado este MÊS!";
					//fnLog(array("DESCRICAO"=>"$desc_cliente - SMS já enviado este MÊS!","COD_EMPRESA"=>$cod_empresa,"SQL"=>$sql,"ERRO"=>mysqli_error($contemporaria),"COD_GATILHO"=>$cod_gatilho,"TIP_GATILHO"=>$tip_gatilho));
					continue;
				}
			} elseif ($tip_controle <> 99) {
				//1 vez no dia
				//Checa se já foi enviado hoje
				$sql = "SELECT COUNT(0) QTD FROM email_filavalidades
							WHERE NUM_CELULAR = '$num_celular_ori'
							AND DATE(DT_CADASTR)=DATE(NOW())
							AND COD_EMPRESA=$cod_empresa
							$where";
				$rs = mysqli_query($contemporaria, $sql);
				$linha = mysqli_fetch_assoc($rs);
				if ($linha["QTD"] > 0) {
					$msg_env .= PHP_EOL . "$desc_cliente - SMS já enviado este HOJE!";
					//fnLog(array("DESCRICAO"=>"$desc_cliente - SMS já enviado HOJE!","COD_EMPRESA"=>$cod_empresa,"SQL"=>$sql,"ERRO"=>mysqli_error($contemporaria),"COD_GATILHO"=>$cod_gatilho,"TIP_GATILHO"=>$tip_gatilho));
					continue;
				}
			}

			$qtd_lote++;
			if ($qtd_lote > $limite_envio) {
				$lote++;
				$qtd_lote = 1;
			}
			$cods_cliente .= "," . $rsemail_fila["COD_CLIENTE"];
			$cods_cliente_l[$lote] .= (@$cods_cliente_l[$lote] <> "" ? "," : "") . $rsemail_fila["COD_CLIENTE"];
			$CLIE_CAD_L[$lote][] = $rsemail_fila;
			$dat_envio = date("Y-m-d H:i:s", strtotime("+ 1 minutes"));
			$nom_camp_msg = $cod_campanha . '||' . $cod_empresa . '||' . $cod_cliente . '||' . $cod_template;
			$qtd_total_envio++;

			//$tel = "551100000000";/*****************************************************************************************************************/
			$CLIE_SMS_L[$lote][] = array("numero" => fnLimpaDoc($tel), "mensagem" => $textoenvio, "Codigo_cliente" => $nom_camp_msg, "DataAgendamento" => $dat_envio);
		}

		fnLog(array("DESCRICAO" => "Alertas: " . $msg_env, "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));

		if (count($CLIE_SMS_L) <= 0) {
			fnLog(array("DESCRICAO" => "Sem dados para serem enviados!", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));
			continue;
		}

		$qt_envio_real = 0;
		$qt_lotes_processados = 0;
		fnLog(array("DESCRICAO" => "Qtd. Lotes Gerados: " . count($CLIE_CAD_L), "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));
		//print_r($CLIE_CAD_L);exit;
		foreach ($CLIE_CAD_L as $k => $CLIE_CAD) {
			sleep(1);
			$linhas = count($CLIE_CAD);
			$CLIE_SMS = $CLIE_SMS_L[$k];
			$qtd_lote = count($CLIE_SMS);

			$cod_lote = $k;
			fnLog(array("DESCRICAO" => "Lote: $cod_lote / Qtd.: " . $qtd_lote, "COD_EMPRESA" => $cod_empresa, "JSON" => json_encode($CLIE_SMS), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));

			//fnLog(array("DESCRICAO"=>"[ ROTINA PAUSADA ]","COD_EMPRESA"=>$cod_empresa));continue;


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
				'CONNADM' => $connAdm->connAdm()
			);

			$saldo = SaldoNexux($rsempresa["DES_AUTHKEY2"]);
			fnLog(array("DESCRICAO" => "Consultando Saldo NEXUX: " . $saldo . " / Qtd Envio.: " . $linhas . " / Saldo após envio: " . ($saldo - $linhas), "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));
			if ($saldo < $linhas) {
				fnLog(array("DESCRICAO" => "Sem saldo na NEXUX para envio de lote", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));
				continue;
			}

			$consulta_saldo = FnDebitos($arraydebitos, true);
			fnLog(array("DESCRICAO" => "Consultando Saldo: " . $consulta_saldo["saldorestante"] . " / Qtd Envio.: " . $consulta_saldo["qtd_envio"] . " / Saldo após envio: " . $consulta_saldo["saldo_apos_envio"], "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "JSON" => json_encode($consulta_saldo)));
			if (!$consulta_saldo["tem_saldo"]) {
				//Subtrai do total
				$tot_ori = $qtd_total_envio;
				$qtd_total_envio = $qtd_total_envio - $qtd_lote;
				fnLog(array("DESCRICAO" => "Sem saldo para envio de lote - subtraindo do total: ($tot_ori >> " . ($qtd_total_envio) . ")", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));
				continue;
			}
			/*$debitos=FnDebitos($arraydebitos);
			fnLog(array("DESCRICAO"=>"Consultando Débitos","COD_EMPRESA"=>$cod_empresa,"JSON"=>json_encode($debitos),"COD_GATILHO"=>$cod_gatilho,"TIP_GATILHO"=>$tip_gatilho));
			if (@$debitos["cod_mensagem"] = 3 && $PERMITENEGATIVO != "S"){
				if ($debitos["cod_msg"] == 3 || $debitos["cod_msg"] == 5){
					fnLog(array("DESCRICAO"=>"Não foi possível enviar","COD_EMPRESA"=>$cod_empresa,"ERRO"=>$debitos["MSG"],"COD_GATILHO"=>$cod_gatilho,"TIP_GATILHO"=>$tip_gatilho));
					continue;
				}
			}*/

			$sql = "SELECT NUM_CONTADOR FROM contador WHERE NUM_TKT=50";
			$rwcont = mysqli_query($contemporaria, $sql);
			$lcont = mysqli_fetch_assoc($rwcont);

			fnLog(array("DESCRICAO" => "Gera Contador", "COD_EMPRESA" => $cod_empresa, "SQL" => $sql, "ERRO" => mysqli_error($contemporaria), "JSON" => json_encode($lcont), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));
			if (mysqli_error($contemporaria) <> "") {
				continue;
			}

			if ($lcont["NUM_CONTADOR"] == "") {
				$sql = "INSERT INTO contador (COD_ORCAMENTO, DES_CONTADOR, NUM_TKT, NUM_EXPIRA) VALUES ('50', 'COMUNICACAO', '50', '50')";
				$rwinsert = mysqli_query($contemporaria, $sql);

				fnLog(array("DESCRICAO" => "Erro ao inserir Contador", "COD_EMPRESA" => $cod_empresa, "SQL" => $sql, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));
				if (mysqli_error($contemporaria) <> "") {
					continue;
				}


				$sql = "SELECT NUM_CONTADOR FROM contador WHERE NUM_TKT=50";
				$rwcont = mysqli_query($contemporaria, $sql);
				$lcont = mysqli_fetch_assoc($rwcont);

				fnLog(array("DESCRICAO" => "Gera Contador", "COD_EMPRESA" => $cod_empresa, "SQL" => $sql, "ERRO" => mysqli_error($contemporaria), "JSON" => json_encode($lcont), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));
				if (mysqli_error($contemporaria) <> "") {
					continue;
				}
			}

			if (@$lcont["NUM_CONTADOR"] == "") {
				//Subtrai do total
				$qtd_total_envio = $qtd_total_envio - $qtd_lote;
				fnLog(array("DESCRICAO" => "Não foi possível obter contador! - subtraindo do total: ($qtd_total_envio >> " . ($qtd_total_envio - $qtd_lote) . ")", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));
				continue;
			}


			$disparo = $cod_empresa . "," . $cod_campanha . "," . $html["COD_TEMPLATE"] . "," . $lcont["NUM_CONTADOR"];
			$retornoSMS = array();


			fnLog(array("DESCRICAO" => "Iniciando Disparo de SMS........", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));

			try {
				$retornoSMS = EnvioSms_fast(
					$rsempresa["DES_AUTHKEY2"],
					$des_campanha,
					json_encode($CLIE_SMS),
					'short'
				);
			} catch (Exception $e) {
				//Subtrai do total
				$qtd_total_envio = $qtd_total_envio - $qtd_lote;
				fnLog(array("DESCRICAO" => "[ NÃO ENVIADO ] / Par&acirc;metros de Disparo: " . $disparo . " - subtraindo do total: ($qtd_total_envio >> " . ($qtd_total_envio - $qtd_lote) . ")", "COD_EMPRESA" => $cod_empresa, "JSON" => json_encode($retornoSMS), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "ERRO" => $e->getMessage()));
				continue;
			}

			$sqlControle = "INSERT INTO log_nuxux(
							COD_EMPRESA,
							DAT_CADASTR,
							TIP_LOG,
							LOG_JSON
					)VALUES(
							0" . $cod_empresa . ",
							NOW(),
							2,
							'" . json_encode($retornoSMS) . "')";
			$rwinsert = mysqli_query($contemporaria, $sqlControle);
			if (mysqli_error($contemporaria) <> "") {
				fnLog(array("DESCRICAO" => "Erro ao inserir na tabela LOG_NUXUX", "COD_EMPRESA" => $cod_empresa, "SQL" => $sqlControle, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));
				continue;
			}
			fnLog(array("DESCRICAO" => "Insere na tabela LOG_NUXUX", "COD_EMPRESA" => $cod_empresa, "SQL" => $sqlControle, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));

			if (@$retornoSMS["Resultado"]["CodigoResultado"] <> 0) {
				fnLog(array("DESCRICAO" => "[ NÃO ENVIADO ] / Erro: " . @$retornoSMS["Resultado"]["CodigoResultado"] . " - " . @$retornoSMS["Resultado"]["Mensagem"], "COD_EMPRESA" => $cod_empresa, "JSON" => json_encode($retornoSMS), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));
				continue;
			}

			fnLog(array("DESCRICAO" => "Disparo de SMS (EnvioSms) / Par&acirc;metros de Disparo: " . $disparo, "COD_EMPRESA" => $cod_empresa, "JSON" => json_encode($retornoSMS), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));


			fnLog(array("DESCRICAO" => "Rotina SMS processada!", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "JSON" => json_encode($retornoSMS)));


			if (count(@$retornoSMS["Mensagens"]) > 0) {
				$qt_lotes_processados++;
				$debitos = FnDebitos($arraydebitos);
				fnLog(array("DESCRICAO" => "Efetuando Débitos", "COD_EMPRESA" => $cod_empresa, "JSON" => json_encode($debitos), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));

				fnLog(array("DESCRICAO" => "[ ENVIADO ]", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));

				/*MARCA GATILHO COMO EXECUTADO*********************************************************************************/
				$sql = "UPDATE gatilho_sms SET LOG_PROCESS_GATILHO='N',DATHOR_PROCESS=NOW() WHERE COD_GATILHO=$cod_gatilho AND COD_EMPRESA=$cod_empresa";
				$rs = mysqli_query($contemporaria, $sql);
				fnLog(array("DESCRICAO" => "Gatilho marcado como executado!", "SQL" => $sql, "COD_EMPRESA" => $cod_empresa, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));

				$sql = "UPDATE contador SET NUM_CONTADOR=IFNULL(NUM_CONTADOR,1)+1 WHERE NUM_TKT=50";
				mysqli_query($contemporaria, $sql);
				if (mysqli_error($contemporaria) <> "") {
					fnLog(array("DESCRICAO" => "Erro SQL!", "COD_EMPRESA" => $cod_empresa, "SQL" => $sql, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));
					continue;
				}

				fnLog(array("DESCRICAO" => "Vai entrar na rotina de SUCESSO! Total de sucesso: " . count($retornoSMS["Mensagens"]), "COD_EMPRESA" => $cod_empresa, "SQL" => $sqlControle, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));

				$CHAVE_GERAL = $retornoSMS["Resultado"]["Chave"];
				$CHAVE_CLIENTE = $retornoSMS["Mensagens"][0]["UniqueID"];
				$msgenvio = $retornoSMS["Resultado"]["Mensagem"];

				foreach ($retornoSMS["Mensagens"] as $key => $cliente) {


					$info = explode("||", $cliente["Codigo_cliente"]);

					$cod_cliente = $info[2];
					$celular = substr($cliente["numero"], 3);
					$idDisparo = date('Ymd');
					$TEXTOENVIO = $cliente["mensagem"];
					$CHAVE_CLIENTE = $cliente["UniqueID"];




					$sqlControle = "INSERT INTO sms_lista_ret (
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
												DES_STATUS
												) VALUES (
												'" . $cod_empresa . "',
												'" . $cod_campanha . "',       
												(SELECT NOM_CLIENTE FROM CLIENTES WHERE COD_EMPRESA = " . $cod_empresa . " AND COD_CLIENTE = " . $cod_cliente . "),
												(SELECT COD_UNIVEND FROM email_fila WHERE COD_EMPRESA  = " . $cod_empresa . " AND COD_CLIENTE = " . $cod_cliente . " ORDER BY 1 DESC LIMIT 1),
												'" . $cod_cliente . "',
												'" . fnlimpacelular($celular) . "',
												'S',
												'" . $idDisparo . "',
												'" . $TEXTOENVIO . "',
												'" . $CHAVE_GERAL . "',
												'" . $CHAVE_CLIENTE . "',
												NOW(),
												'N',
												'" . $msgenvio . "'    
												)";
					//fnLog(array("DESCRICAO"=>"Insere na sms_lista_ret","COD_EMPRESA"=>$cod_empresa,"SQL"=>$sqlControle,"COD_GATILHO"=>$cod_gatilho,"TIP_GATILHO"=>$tip_gatilho));
					mysqli_query($contemporaria, $sqlControle);
					if (mysqli_error($contemporaria) <> "") {
						fnLog(array("DESCRICAO" => "Erro SQL ao inserir na sms_lista_ret!", "COD_EMPRESA" => $cod_empresa, "SQL" => $sqlControle, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));
						// continue;
					}
					$qt_envio_real++;
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
							COD_CLIENTE IN (" . $cods_cliente_l[$cod_lote] . ")
						)";
				fnLog(array("DESCRICAO" => "Insere na email_filavalidades", "COD_EMPRESA" => $cod_empresa, "SQL" => $sql, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));
				mysqli_query($contemporaria, $sql);
				if (mysqli_error($contemporaria) <> "") {
					fnLog(array("DESCRICAO" => "Erro SQL ao inserir na email_filavalidades!", "COD_EMPRESA" => $cod_empresa, "SQL" => $sql, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));
					// continue;
				}

				$sql = "UPDATE email_fila SET COD_ENVIADO='S',DT_ALTERAC=NOW()
							WHERE
								TIP_FILA IN (3,7,8,10) AND
								TIP_GATILHO='$tip_gatilho' AND
								COD_EMPRESA=$cod_empresa AND 
								COD_CAMPANHA=$cod_campanha AND
								COD_ENVIADO='N' AND
								COD_CLIENTE IN (" . $cods_cliente_l[$cod_lote] . ")";
				fnLog(array("DESCRICAO" => "Update na email_fila", "COD_EMPRESA" => $cod_empresa, "SQL" => $sql, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));
				mysqli_query($contemporaria, $sql);
				if (mysqli_error($contemporaria) <> "") {
					fnLog(array("DESCRICAO" => "Erro SQL ao fazer update na email_fila!", "COD_EMPRESA" => $cod_empresa, "SQL" => $sql, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));
					// continue;
				}
			} else {
				fnLog(array("DESCRICAO" => "[ NÃO ENVIADO ]", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "ERRO" => json_encode($retornoSMS)));
			}
		}

		/*
		//COMENTADO PARA NÃO ENVIAR RETROATIVO CASO DE ERRO - DEFINIDO EM 19/12/2021
		$sql = "UPDATE email_fila SET COD_ENVIADO='X',DT_ALTERAC=NOW()
					WHERE
						TIP_FILA IN (3,7,8,10) AND
						TIP_GATILHO='$tip_gatilho' AND
						COD_EMPRESA=$cod_empresa AND 
						COD_CAMPANHA=$cod_campanha AND
						COD_ENVIADO='N' AND
						DATE(DT_CADASTR) < DATE(DATE_ADD(NOW(), INTERVAL -".$des_periodo." DAY))";
						*/
		$sql = "UPDATE email_fila SET COD_ENVIADO='X',DT_ALTERAC=NOW()
		WHERE
			TIP_FILA IN (3,7,8,10) AND
			TIP_GATILHO='$tip_gatilho' AND
			COD_EMPRESA=$cod_empresa AND 
			COD_CAMPANHA=$cod_campanha AND
			COD_ENVIADO='N'";
		fnLog(array("DESCRICAO" => "Ajusta tabela email_fila para não enviar retroativo.", "COD_EMPRESA" => $cod_empresa, "SQL" => $sql, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));
		mysqli_query($contemporaria, $sql);
		if (mysqli_error($contemporaria) <> "") {
			fnLog(array("DESCRICAO" => "Erro SQL!", "COD_EMPRESA" => $cod_empresa, "SQL" => $sql, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));
			// continue;
		}

		//if ($qtd_total_envio > 0 && $qt_lotes_processados > 0){
		if ($qt_envio_real > 0) {
			$sql = "SELECT COUNT(0) QTD FROM SMS_LOTE WHERE COD_DISPARO_EXT='" . date("Ymd") . "' AND COD_CAMPANHA='0" . $cod_campanha . "' AND COD_EMPRESA='0" . $cod_empresa . "'";
			$rs = mysqli_query($contemporaria, $sql);
			$linha = mysqli_fetch_assoc($rs);
			fnLog(array("DESCRICAO" => "Checa se já tem lote (SMS_LOTE)", "JSON" => json_encode($linha), "COD_EMPRESA" => $cod_empresa, "SQL" => $sql, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));
			if (@$linha["QTD"] <= 0) {

				$sqlControle = "INSERT INTO SMS_LOTE(
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
					" . ($html["COD_EXT_TEMPLATE"] <> "" ? $html["COD_EXT_TEMPLATE"] : "NULL") . ",
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
				fnLog(array("DESCRICAO" => "Inserir na tabela SMS_LOTE", "JSON" => json_encode($retornoSMS), "COD_EMPRESA" => $cod_empresa, "SQL" => $sqlControle, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));
			}
		} else {
			fnLog(array("DESCRICAO" => "Nada foi enviado deste lote!", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));
		}
	}
}

fnLog(array("DESCRICAO" => "[ FIM ROTINA ]"));
echo "</pre>";


$sql = "UPDATE gatilhos_logs_exec SET DATAHORA_FIM=NOW() WHERE UID='" . $uuid . "' AND COD_EXECUCAO='" . str_replace("-", "", str_replace(":", "", str_replace(" ", "", $ini_rotina))) . "'";
$rs = mysqli_query($conadmin, $sql);
