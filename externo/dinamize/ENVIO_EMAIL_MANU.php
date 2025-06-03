<?php
require '../../_system/_functionsMain.php';
require '../../_system/func_dinamiza/Function_dinamiza.php';

$gera_log = "S";
//$gera_log = (date("Y-m-d")=="2020-12-01"?"S":"N");
$conadmin = $connAdm->connAdm();


$datahoraatual = date('Y-m-d H:i:s');
$horaatual = date("H");
$minutoatual = date("i");
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
function fnLog($dados = array())
{
	global $ini_rotina, $gera_log, $conadmin, $sequencia;
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
					'EMAIL',
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
		$rw = mysqli_query($conadmin, $sql_log) or die($sql_log);
	}
	$sequencia++;
	echo date("Y-m-d H:i:s") . " - " . json_encode($dados) . "<br>";
}

echo "<pre>";
fnLog(array("DESCRICAO" => "[ INÍCIO ROTINA ]"));


/*EMPRESAS************************************************************************************************************************/
$sqlempresa = "SELECT * FROM senhas_parceiro apar
				INNER JOIN parceiro_comunicacao par ON par.COD_PARCOMU=apar.COD_PARCOMU
				WHERE par.COD_TPCOM='1' AND apar.LOG_ATIVO='S'
				AND apar.COD_EMPRESA in (123)";
$rwempresa = mysqli_query($conadmin, $sqlempresa);
fnLog(array("DESCRICAO" => "Lista Empresas", "SQL" => $sqlempresa, "ERRO" => mysqli_error($conadmin)));
while ($rsempresa = mysqli_fetch_assoc($rwempresa)) {

	$cod_empresa = $rsempresa['COD_EMPRESA'];
	$contemporaria = connTemp($cod_empresa, '');
	$cod_lista1 = $rsempresa['COD_LISTA'];


	fnLog(array("DESCRICAO" => "Início EMPRESA $cod_empresa [ $count_empre / $tot_empre ]", "COD_EMPRESA" => $cod_empresa, "JSON" => json_encode($rsempresa)));

	if ((int)$horaatual == 0 && (int)$minutoatual <= 5) {
		/*LIMPA REGISTROS COM MAIS DE 32 DIAS DE CADASTRO*********************************************************************************/
		$sql = "DELETE FROM email_fila WHERE DATE_ADD(DT_CADASTR,INTERVAL 32 DAY) < DATE(NOW())";
		$rs = mysqli_query($contemporaria, $sql);
		fnLog(array("DESCRICAO" => "Limpa registros da EMAIL_FILA com mais de 32 dias de cadastro", "SQL" => $sql, "COD_EMPRESA" => $cod_empresa, "ERRO" => mysqli_error($contemporaria)));

		$sql = "DELETE FROM email_filavalidades WHERE DATE_ADD(DT_CADASTR,INTERVAL 32 DAY) < DATE(NOW())";
		$rs = mysqli_query($contemporaria, $sql);
		fnLog(array("DESCRICAO" => "Limpa registros da EMAIL_FILAVALIDADES com mais de 32 dias de cadastro", "SQL" => $sql, "COD_EMPRESA" => $cod_empresa, "ERRO" => mysqli_error($contemporaria)));
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


	$parcerorw = mysqli_query($conadmmysql, $sqlpacero);
	//iniciar autenticação na dinamize
	$atenticacaoDInamize = autenticacao_dinamiza($rsempresa['DES_USUARIO'], $rsempresa['DES_AUTHKEY'], $rsempresa['DES_CLIEXT']);
	$senha_dinamize = $atenticacaoDInamize['body']['auth-token'];
	fnLog(array("DESCRICAO" => "Autenticação Dinamize - chave = $senha_dinamize", "COD_EMPRESA" => $cod_empresa, "SQL" => $sqlgatilho, "JSON" => json_encode($atenticacaoDInamize)));


	/*GATILHO************************************************************************************************************************/
	$gatilhos = array("cadastro", "resgate", "venda", "aniv", "anivSem", "anivQuinz", "anivMes", "anivDia", "credExp", "inativos"); // <-- Colocar na ordem do select
	$gatilhos_impl_in = "'" . (implode("','", $gatilhos)) . "'";
	$gatilhos_impl_ord = "'" . (implode(",", $gatilhos)) . "'";
	$sqlgatilho = "SELECT * FROM gatilho_email gt
						INNER JOIN campanha cp ON gt.COD_CAMPANHA=cp.COD_CAMPANHA 
						INNER JOIN email_parametros p ON gt.COD_EMPRESA=p.cod_empresa AND gt.COD_CAMPANHA=p.cod_campanha
							AND COD_LISTA IN (SELECT MAX(COD_LISTA) FROM email_parametros WHERE COD_EMPRESA=gt.cod_empresa AND COD_CAMPANHA=gt.cod_campanha)
					WHERE gt.TIP_GATILHO IN ($gatilhos_impl_in)
						AND gt.LOG_STATUS ='S'
						AND cp.LOG_ATIVO = 'S'
						AND gt.cod_empresa=$cod_empresa
					GROUP BY gt.COD_CAMPANHA
					ORDER BY FIND_IN_SET(gt.TIP_GATILHO, $gatilhos_impl_ord)";
	$rwgatilho = mysqli_query($contemporaria, $sqlgatilho);
	fnLog(array("DESCRICAO" => "Obter dados do GATILHO", "COD_EMPRESA" => $cod_empresa, "SQL" => $sqlgatilho, "ERRO" => mysqli_error($contemporaria)));
	while ($rsgatilho = mysqli_fetch_assoc($rwgatilho)) {
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


		if ($tip_gatilho != 'cadastro') {
			continue;
		}

		fnLog(array("DESCRICAO" => "Dados do GATILHO $cod_gatilho (log_process=$log_process / tip_gatilho=$tip_gatilho / des_periodo=$des_periodo / tip_controle (periodicidade)=$tip_controle / tip_momento=$tip_momento / cod_campanha=$cod_campanha / cod_persona=$cod_personas / dias_hist=$dias_hist) [ $count_gati / $tot_gati ]", "COD_EMPRESA" => $cod_empresa, "JSON" => json_encode($rsgatilho), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));

		/*

		if ($des_periodo == 7 || $des_periodo == 15){
			$d = date("w");
			fnLog(array("DESCRICAO"=>"---- calc=$d ---- semana=".date("w")." ---- dias antec=".$dias_anteced." ---- mod=".($d % 7),"COD_EMPRESA"=>$cod_empresa,"COD_GATILHO"=>$cod_gatilho,"TIP_GATILHO"=>$tip_gatilho));
			if (($d % 7) <> 0){
				fnLog(array("DESCRICAO"=>"Gatilho semanal / quinzenal fora do período. (período: $des_periodo / antecedência: $dias_anteced / referencica: DOMINGO)","COD_EMPRESA"=>$cod_empresa,"COD_GATILHO"=>$cod_gatilho,"TIP_GATILHO"=>$tip_gatilho));
				continue;
			}else{
				fnLog(array("DESCRICAO"=>"Gatilho semanal / quinzenal. (período: $des_periodo / antecedência: $dias_anteced / referencica: DOMINGO)","COD_EMPRESA"=>$cod_empresa,"COD_GATILHO"=>$cod_gatilho,"TIP_GATILHO"=>$tip_gatilho));
			}
		}elseif ($des_periodo == 30){
			$d = date('d', strtotime(date("Y-m-d"). " + 0 days"));
			fnLog(array("DESCRICAO"=>"---- calc=$d ----  dias antec=".$dias_anteced." ---- ","COD_EMPRESA"=>$cod_empresa,"COD_GATILHO"=>$cod_gatilho,"TIP_GATILHO"=>$tip_gatilho));
			if ($d <> "01" && $d <> "1"){
				fnLog(array("DESCRICAO"=>"Gatilho mensal fora do período. (período: $des_periodo / antecedência: $dias_anteced / referencica: DIA 1&ordm;)","COD_EMPRESA"=>$cod_empresa,"COD_GATILHO"=>$cod_gatilho,"TIP_GATILHO"=>$tip_gatilho));
				continue;
			}else{
				fnLog(array("DESCRICAO"=>"Gatilho mensal. (período: $des_periodo / antecedência: $dias_anteced / referencica: DIA 1&ordm;)","COD_EMPRESA"=>$cod_empresa,"COD_GATILHO"=>$cod_gatilho,"TIP_GATILHO"=>$tip_gatilho));
			}
		}

		//Checa o horário de envio
		$process = true;
		if ($tip_momento < 24 && $tip_momento > 0 && $tip_momento != $horaatual){
			fnLog(array("DESCRICAO"=>"Fora do horário de envio (Hora atual: $horaatual / Hora de envio: $tip_momento)!","COD_EMPRESA"=>$cod_empresa,"COD_GATILHO"=>$cod_gatilho,"TIP_GATILHO"=>$tip_gatilho));
			$process = false;
		}
		if ($tip_momento < 24 && $tip_momento > 0 && (int)$minutoatual >= 5){
			fnLog(array("DESCRICAO"=>"Fora do minuto de envio (Hora atual: $horaatual:$minutoatual / Hora de envio: $tip_momento)!","COD_EMPRESA"=>$cod_empresa,"COD_GATILHO"=>$cod_gatilho,"TIP_GATILHO"=>$tip_gatilho));
			$process = false;
		}
		if ($tip_momento >= 24 && (int)$minutoatual <= 5){
			fnLog(array("DESCRICAO"=>"Gatilho imediato adiado para não sobrecarregar gatilhos agendados! (Hora atual: $horaatual:$minutoatual / Hora de envio: $tip_momento)!","COD_EMPRESA"=>$cod_empresa,"COD_GATILHO"=>$cod_gatilho,"TIP_GATILHO"=>$tip_gatilho));
			continue;
		}
		
		if (!$process){
			if ($log_process == "S" && ((int)$minutoatual < 5)){
				fnLog(array("DESCRICAO"=>"Gatilho não enviado no horário agendado. REPROCESSANDO.... (Hora atual: $horaatual / Hora de envio: $tip_momento)!","COD_EMPRESA"=>$cod_empresa,"COD_GATILHO"=>$cod_gatilho,"TIP_GATILHO"=>$tip_gatilho));
			}else{
				continue;
			}
		}else{
			fnLog(array("DESCRICAO"=>"Dentro do horário de envio (Hora atual: $horaatual:$minutoatual / Hora de envio: $tip_momento)!","COD_EMPRESA"=>$cod_empresa,"COD_GATILHO"=>$cod_gatilho,"TIP_GATILHO"=>$tip_gatilho));
		}
*/

		/*MARCA GATILHO PARA SER EXECUTADO*********************************************************************************/
		//$sql = "UPDATE gatilho_sms SET LOG_PROCESS='S',DATHOR_PROCESS=NOW() WHERE COD_GATILHO=$cod_gatilho";
		//$rs = mysqli_query($contemporaria, $sql);
		//fnLog(array("DESCRICAO"=>"Gatilho marcado para ser executado!","SQL"=>$sql,"COD_EMPRESA"=>$cod_empresa,"ERRO"=>mysqli_error($contemporaria),"COD_GATILHO"=>$cod_gatilho,"TIP_GATILHO"=>$tip_gatilho));


		if ($tip_gatilho == "aniv" || $tip_gatilho == "anivDia" || $tip_gatilho == "anivSem" || $tip_gatilho == "anivQuinz" || $tip_gatilho == "anivMes") {
			$tip_gatilho = "aniv";

			switch ($des_periodo) {
				case 7:
					$per = "anivSem";
					break;
				case 15:
					$per = "anivQuinz";
					break;
				case 30:
					$per = "anivMes";
					break;
				default:
					$per = "anivDia";
					break;
			}

			$sqlIns = "DELETE FROM email_lista WHERE COD_EMPRESA = $cod_empresa";
			mysqli_query($contemporaria, $sqlIns);
			fnLog(array("DESCRICAO" => "Apagando EMAIL_LISTA", "COD_EMPRESA" => $cod_empresa, "SQL" => $sqlIns, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));

			$sql = "CALL SP_RELAT_EMAIL_ANIVERSARIO($cod_empresa,0$cod_campanha,0$pct_reserva,'" . $cod_personas . "',DATE(DATE_ADD(NOW(),INTERVAL $dias_anteced DAY)),'" . $per . "');";
			$rs = mysqli_query($contemporaria, $sql);
			fnLog(array("DESCRICAO" => "Carregando aniversariantes", "COD_EMPRESA" => $cod_empresa, "SQL" => $sql, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));
			mysqli_next_result($contemporaria);


			$sqlIns = "INSERT INTO email_fila (DT_CADASTR,COD_EMPRESA,COD_UNIVEND,COD_CLIENTE,NUM_CGCECPF,NOM_CLIENTE,DT_NASCIME,DES_EMAILUS,NUM_CELULAR,VAL_RESGATE,
					VAL_CRED_ACUMULADO,COD_SEXOPES,COD_CAMPANHA,TIP_MOMENTO,TIP_CONTROLE,TIP_FILA,TIP_GATILHO,VAL_EXPIRAR,DAT_EXPIRA,SEMANA,MES,COD_ENVIADO)
					SELECT
						NOW() DT_CADASTR,COD_EMPRESA,(SELECT COD_UNIVEND FROM clientes WHERE clientes.COD_CLIENTE=email_lista.COD_CLIENTE AND clientes.COD_EMPRESA=email_lista.COD_EMPRESA) COD_UNIVEND,COD_CLIENTE,NULL NUM_CGCECPF,NOM_CLIENTE,'' DT_NASCIME,
						DES_EMAILUS,'' NUM_CELULAR,0 VAL_RESGATE,0 VAL_CRED_ACUMULADO,COD_SEXOPES,COD_CAMPANHA,'$tip_momento' TIP_MOMENTO,
						'$tip_controle' TIP_CONTROLE,2 TIP_FILA,'$tip_gatilho' TIP_GATILHO,0 VAL_EXPIRAR,NULL DAT_EXPIRA,'' SEMANA,'' MES,'N' COD_ENVIADO
					FROM email_lista WHERE COD_EMPRESA = $cod_empresa AND COD_CAMPANHA = $cod_campanha
					AND COD_CLIENTE NOT IN (SELECT COD_CLIENTE FROM email_fila WHERE COD_EMPRESA = $cod_empresa AND COD_CAMPANHA = $cod_campanha AND TIP_GATILHO = '$tip_gatilho' AND DATE(DT_CADASTR) = DATE(NOW()))";
			mysqli_query($contemporaria, $sqlIns);
			fnLog(array("DESCRICAO" => "Gravando aniversariantes na tabela EMAIL_FILA", "COD_EMPRESA" => $cod_empresa, "SQL" => $sqlIns, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));

			$sqlIns = "DELETE FROM email_fila WHERE DT_NASCIME='31/12/1969' AND TIP_CONTROLE = '$tip_controle' AND COD_EMPRESA = $cod_empresa";
			mysqli_query($contemporaria, $sqlIns);
			fnLog(array("DESCRICAO" => "Apagando datas inválidas EMAIL_FILA", "COD_EMPRESA" => $cod_empresa, "SQL" => $sqlIns, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));
		} elseif ($tip_gatilho == "credExp") {

			$dias_anteced = ($dias_anteced == "" ? 1 : $dias_anteced);
			$tot_saldomin = ($tot_saldomin <= 0 ? 1 : $tot_saldomin);
			$des_periodomax = ($des_periodomax  == "" ? 0 : $des_periodomax);
			$sqlIns = "INSERT INTO email_fila (DT_CADASTR,COD_EMPRESA,COD_UNIVEND,COD_CLIENTE,NUM_CGCECPF,NOM_CLIENTE,DT_NASCIME,DES_EMAILUS,NUM_CELULAR,VAL_RESGATE,
						VAL_CRED_ACUMULADO,COD_SEXOPES,COD_CAMPANHA,TIP_MOMENTO,TIP_CONTROLE,TIP_FILA,TIP_GATILHO,VAL_EXPIRAR,DAT_EXPIRA,SEMANA,MES,COD_ENVIADO)
						SELECT  
							NOW() DT_CADASTR,C.COD_EMPRESA,C.COD_UNIVEND,C.COD_CLIENTE,C.NUM_CGCECPF,C.NOM_CLIENTE,NULL DT_NASCIME,
							C.DES_EMAILUS,C.NUM_CELULAR,0 VAL_RESGATE,0 VAL_CRED_ACUMULADO,C.COD_SEXOPES,$cod_campanha COD_CAMPANHA,'$tip_momento' TIP_MOMENTO,
							'$tip_controle' TIP_CONTROLE,2 TIP_FILA,'$tip_gatilho' TIP_GATILHO,0 VAL_EXPIRAR,NULL DAT_EXPIRA,'' SEMANA,'' MES,'N' COD_ENVIADO
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
						HAVING (SELECT COALESCE(SUM(E.val_saldo), 0.00) val_saldo FROM creditosdebitos E WHERE E.COD_CLIENTE=C.COD_CLIENTE AND COD_STATUSCRED=1) >= $tot_saldomin";
			mysqli_query($contemporaria, $sqlIns);
			fnLog(array("DESCRICAO" => "Gravando Créditos a Expirar", "COD_EMPRESA" => $cod_empresa, "SQL" => $sqlIns, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));
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
			fnLog(array("DESCRICAO" => "Gravando Inativos", "COD_EMPRESA" => $cod_empresa, "SQL" => $sqlIns, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));
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
			fnLog(array("DESCRICAO" => "CAMPANHA NÃO HABILITADA PARA ENVIO DE EMAIL! (log_processa=$log_processa / cod_gatilho=$cod_gatilho / cod_campanha=$cod_campanha)", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));
			continue;
		}


		fnLog(array("DESCRICAO" => "Rotina de blacklist....", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));
		$sql = "CALL SP_REMOVE_FILA_BLACKLIST($cod_empresa,'$tip_gatilho','EMAIL')";
		mysqli_query($contemporaria, $sql);
		fnLog(array("DESCRICAO" => "SMS da blacklist e em branco removidos", "COD_EMPRESA" => $cod_empresa, "SQL" => $sql, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));
		if (mysqli_error($contemporaria) <> "") {
			continue;
		}

		/*INICIO ROTINA DE ENVIO*/
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
		fnLog(array("DESCRICAO" => "Carregando TEMPLATE", "COD_EMPRESA" => $cod_empresa, "SQL" => $tampletevariavel, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));

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
																			  f.cod_statuscred = 1 AND 
																						f.tip_campanha = cred.tip_campanha AND 
																						((f.log_expira = 'S' AND DATE_FORMAT(f.dat_expira, '%Y-%m-%d') >= DATE_FORMAT(NOW(), '%Y-%m-%d')) OR (f.log_expira = 'N'))),0)+ IFNULL((
																		SELECT SUM(val_saldo)
																		FROM creditosdebitos_bkp g
																		WHERE g.cod_cliente = cred.cod_cliente AND g.tip_credito = 'C' AND g.cod_statuscred = 1 AND g.tip_campanha = cred.tip_campanha AND ((g.log_expira = 'S' AND DATE_FORMAT(g.dat_expira, '%Y-%m-%d') >= DATE_FORMAT(NOW(), '%Y-%m-%d')) OR (g.log_expira = 'N'))),0)
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
					$selectCliente = "(SELECT MIN(COD_UNIVEND) FROM email_fila WHERE
															email_fila.COD_CLIENTE=C.COD_CLIENTE
															email_fila.TIP_GATILHO='$tip_gatilho' AND
															email_fila.TIP_FILA IN (2,5,6) AND    
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
				case '<#DATAEXPIRA>';
					$selectCliente .= "(SELECT 
																				MIN(DAT_EXPIRA) AS DAT_EXPIRA
																				FROM creditosdebitos 
																					WHERE DAT_EXPIRA >= NOW() AND  cod_CLIENTE=C.cod_CLIENTE) AS DAT_EXPIRA,";
					break;
				case '<#SALDOEXPIRA>';
					$selectCliente = "(SELECT MIN(VAL_EXPIRAR) FROM email_fila WHERE
															email_fila.COD_CLIENTE=C.COD_CLIENTE
															email_fila.TIP_GATILHO='$tip_gatilho' AND
															email_fila.TIP_FILA IN (2,5,6) AND    
															email_fila.COD_EMPRESA=$cod_empresa AND 
															email_fila.COD_CAMPANHA=$cod_campanha AND
															email_fila.COD_ENVIADO='N') VAL_EXPIRAR,";
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
		$sqlcli_cad = "SELECT DISTINCT $selectCliente
						FROM clientes C 							
						WHERE C.COD_EMPRESA = $cod_empresa
						AND C.LOG_EMAIL = 'S'
						AND C.LOG_FIDELIZADO = 'S'
						AND C.LOG_ESTATUS = 'S'
						AND TRIM(C.DES_EMAILUS) != ''
						AND C.COD_CLIENTE in (SELECT COD_CLIENTE FROM email_fila WHERE                                                                                                  
											TIP_GATILHO='$tip_gatilho' AND
											TIP_FILA IN (2,5,6) AND    
											COD_EMPRESA=$cod_empresa AND 
											COD_CAMPANHA=$cod_campanha AND
											COD_ENVIADO='N'
											AND email_fila.COD_CLIENTE IN (
												SELECT DISTINCT COD_CLIENTE
													FROM personaclassifica
													WHERE cod_persona IN (0$cod_personas)
													AND cod_empresa=$cod_empresa
											)
											group by COD_CLIENTE)
						";
		$sql_dados = $sqlcli_cad;
		$rwsql = mysqli_query($contemporaria, $sqlcli_cad);
		fnLog(array("DESCRICAO" => "Carrega clientes", "COD_EMPRESA" => $cod_empresa, "SQL" => $sqlcli_cad, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));
		$CLIE_CAD = array();
		while ($headers = mysqli_fetch_field($rwsql)) {
			$headers1[campos][$headers->name] = $headers->name;
		}
		$cods_cliente = "0";
		while ($rsemail_fila = mysqli_fetch_assoc($rwsql)) {
			$cod_cliente = $rsemail_fila["COD_CLIENTE"];
			$des_emailus = $rsemail_fila["DES_EMAILUS"];

			//fnLog(date('Y-m-d H:i:s').str_repeat("-",7)."E-MAIL $des_emailus / controle = $tip_controle)<br>");

			$desc_cliente = "CLIENTE $cod_cliente / EMAIL $des_emailus ";

			//Checa se está preenchido
			if ($des_emailus == "") {
				fnLog(array("DESCRICAO" => "$desc_cliente - E-mail não preenchido!", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));
				continue;
			}

			//Checa se está na blacklist
			/*
			$sql = "SELECT COUNT(0) QTD FROM blacklist_email WHERE COD_EMPRESA=$cod_empresa AND DES_EMAIL='$des_emailus'";
			$rs = mysqli_query($contemporaria, $sql);
			$linha = mysqli_fetch_assoc($rs);
			if ($linha["QTD"] > 0){
				fnLog(date('Y-m-d H:i:s').str_repeat("-",7)."<i>E-mail na blacklist!</i><br>");
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
							WHERE DES_EMAILUS = '$des_emailus'
							AND WEEK(DT_CADASTR)=WEEK(NOW())
							AND YEAR(DT_CADASTR)=YEAR(NOW())
							AND COD_EMPRESA=$cod_empresa
							$where";
				$rs = mysqli_query($contemporaria, $sql);
				$linha = mysqli_fetch_assoc($rs);
				if ($linha["QTD"] > 0) {
					fnLog(array("DESCRICAO" => "$desc_cliente - EMAIL já enviado esta SEMANA!", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));
					continue;
				}
			} elseif ($tip_controle == 15) {
				//1 vez a cada 15 dias
				//Checa se já foi enviado nos últimos 15 dias
				$sql = "SELECT COUNT(0) QTD FROM email_filavalidades
							WHERE DES_EMAILUS = '$des_emailus'
							AND DATE(DT_CADASTR) > DATE(DATE_ADD(NOW(), INTERVAL -15 DAY))
							AND COD_EMPRESA=$cod_empresa
							$where";
				$rs = mysqli_query($contemporaria, $sql);
				$linha = mysqli_fetch_assoc($rs);
				if ($linha["QTD"] > 0) {
					fnLog(array("DESCRICAO" => "$desc_cliente - EMAIL já enviado dentro de 15 DIAS!", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));
					continue;
				}
			} elseif ($tip_controle == 30) {
				//1 vez no mês
				//Checa se já foi enviado esse mes
				$sql = "SELECT COUNT(0) QTD FROM email_filavalidades
							WHERE DES_EMAILUS = '$des_emailus'
							AND MONTH(DT_CADASTR)=MONTH(NOW())
							AND YEAR(DT_CADASTR)=YEAR(NOW())
							AND COD_EMPRESA=$cod_empresa
							$where";

				$rs = mysqli_query($contemporaria, $sql);
				$linha = mysqli_fetch_assoc($rs);
				if ($linha["QTD"] > 0) {
					fnLog(array("DESCRICAO" => "$desc_cliente - EMAIL já enviado este MÊS!", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));
					continue;
				}
			} else {
				//1 vez no dia
				//Checa se já foi enviado hoje
				$sql = "SELECT COUNT(0) QTD FROM email_filavalidades
							WHERE DES_EMAILUS = '$des_emailus'
							AND DATE(DT_CADASTR)=DATE(NOW())
							AND COD_EMPRESA=$cod_empresa
							$where";
				$rs = mysqli_query($contemporaria, $sql);
				$linha = mysqli_fetch_assoc($rs);
				if ($linha["QTD"] > 0) {
					fnLog(array("DESCRICAO" => "$desc_cliente - EMAIL já enviado HOJE!", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));
					continue;
				}
			}

			//$rsemail_fila["DES_EMAILUS"] = "teste@hotmail.com";
			$cods_cliente .= "," . $rsemail_fila["COD_CLIENTE"];
			$CLIE_CAD[] = $rsemail_fila;
		}

		if (count($CLIE_CAD) <= 0) {
			fnLog(array("DESCRICAO" => "Sem dados para serem enviados!", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));
			continue;
		}
		$linhas = count($CLIE_CAD);
		fnLog(array("DESCRICAO" => "Qtd. E-mails: " . $linhas, "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));


		$PERMITENEGATIVO = 'S';
		$CONFIRMACAO = 'N';
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
		$debitos = FnDebitos($arraydebitos);
		fnLog(array("DESCRICAO" => "Consultando Débitos", "COD_EMPRESA" => $cod_empresa, "JSON" => json_encode($debitos), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));
		if (@$debitos["cod_mensagem"] = 3 && $PERMITENEGATIVO != "S") {
			if ($debitos["cod_msg"] == 3 || $debitos["cod_msg"] == 5) {
				fnLog(array("DESCRICAO" => "Não foi possível enviar", "COD_EMPRESA" => $cod_empresa, "ERRO" => $debitos["MSG"], "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));
				continue;
			}
		}

		fnLog(array("DESCRICAO" => "Iniciando Disparo de EMAIL........", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));

		$nomeRel = $cod_empresa . '_' . date("YmdHis") . "_" . $des_campanha . "cadastro.csv";
		$caminhoRelat = '/srv/www/htdocs/_system/func_dinamiza/lista_envio/';
		gerandorcvs($caminhoRelat, $nomeRel, ";", $CLIE_CAD, $headers1);
		$arquivodinamize = $caminhoRelat . $nomeRel;
		fnLog(array("DESCRICAO" => "Arquivo $arquivodinamize gerado", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));

		try {
			$retornoContatos = contatos_dinamiza("$senha_dinamize", "$arquivodinamize", "$tagsDinamize", "$cod_lista1");
		} catch (Exception $e) {
			fnLog(array("DESCRICAO" => "[ NÃO ENVIADO ]", "COD_EMPRESA" => $cod_empresa, "JSON" => json_encode($retornoContatos), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "ERRO" => $e->getMessage()));
			continue;
		}
		fnLog(array("DESCRICAO" => "Disparo de EMAIL (contatos_dinamiza)", "COD_EMPRESA" => $cod_empresa, "JSON" => json_encode($retornoContatos), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));

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
			fnLog(array("DESCRICAO" => "Gravação segmento", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "JSON" => json_encode($retornoSegmento)));


			if ($retornoSegmento["code_detail"] == "Sucesso") {
				$dat_envio = date("Y-m-d H:i:s", strtotime("+ 3 minutes"));
				$retornoEnvio = array();
				$retornoEnvio = addenvio(
					$senha_dinamize,
					$html["DES_ASSUNTO"],
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
				fnLog(array("DESCRICAO" => "Envio processado", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "JSON" => json_encode($retornoEnvio)));

				if ($retornoEnvio["code_detail"] == "Sucesso") {

					fnLog(array("DESCRICAO" => "[ ENVIADO ]", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));

					/*MARCA GATILHO COMO EXECUTADO*********************************************************************************/
					//$sql = "UPDATE gatilho_sms SET LOG_PROCESS='N',DATHOR_PROCESS=NOW() WHERE COD_GATILHO=$cod_gatilho";
					//$rs = mysqli_query($contemporaria, $sql);
					//fnLog(array("DESCRICAO"=>"Gatilho marcado como executado!","SQL"=>$sql,"COD_EMPRESA"=>$cod_empresa,"ERRO"=>mysqli_error($contemporaria),"COD_GATILHO"=>$cod_gatilho,"TIP_GATILHO"=>$tip_gatilho));

					$sqlControle = "INSERT INTO EMAIL_LOTE(
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
					fnLog(array("DESCRICAO" => "Inserir na tabela EMAIL_LOTE", "COD_EMPRESA" => $cod_empresa, "SQL" => $sqlControle, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));


					$sqlControle = "INSERT INTO email_lista_ret(
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
					fnLog(array("DESCRICAO" => "Inserir na tabela email_lista_ret", "COD_EMPRESA" => $cod_empresa, "SQL" => $sqlControle, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));

					$rsemail_fila["COD_ENVIADO"] = "S";
					$sql = "INSERT INTO email_filavalidades (
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
						fnLog(array("DESCRICAO" => "Erro SQL!", "COD_EMPRESA" => $cod_empresa, "SQL" => $sql, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));
						// continue;
					}


					$sql = "UPDATE email_fila SET COD_ENVIADO='S'
								WHERE
									TIP_GATILHO='$tip_gatilho' AND
									COD_EMPRESA=$cod_empresa AND 
									COD_CAMPANHA=$cod_campanha AND
									COD_ENVIADO='N' AND
									COD_CLIENTE IN ($cods_cliente)";
					mysqli_query($contemporaria, $sql);
					if (mysqli_error($contemporaria) <> "") {
						fnLog(array("DESCRICAO" => "Erro SQL!", "COD_EMPRESA" => $cod_empresa, "SQL" => $sql, "ERRO" => mysqli_error($contemporaria), "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho));
						// continue;
					}
				} else {
					fnLog(array("DESCRICAO" => "[ NÃO ENVIADO ] Erro envio", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "ERRO" => json_encode($retornoEnvio)));
				}
			} else {
				fnLog(array("DESCRICAO" => "[ NÃO ENVIADO ] Erro Segmento", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "ERRO" => json_encode($retornoSegmento)));
			}
		} else {
			fnLog(array("DESCRICAO" => "[ NÃO ENVIADO ]", "COD_EMPRESA" => $cod_empresa, "COD_GATILHO" => $cod_gatilho, "TIP_GATILHO" => $tip_gatilho, "ERRO" => json_encode($retornoContatos)));
		}
	}
}


fnLog(array("DESCRICAO" => "[ FIM ROTINA ]"));
echo "</pre>";
