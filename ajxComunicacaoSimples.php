<?php 

include '_system/_functionsMain.php';

$cod_empresa = $_REQUEST['id'];
$des_campanha = $_REQUEST['dcp'];
$cod_usucada = $_REQUEST['usu'];
$cod_campanha = $_REQUEST['camp'];
$opcao = $_GET['opcao'];

$tipoCom = $_REQUEST['tpc'];

$tabelaGatilho = "GATILHO_".$tipoCom;
$tabelaSchedule = "CONTROLE_SCHEDULE_".$tipoCom;
$tabelaAutomacao = "TEMPLATE_AUTOMACAO_".$tipoCom;
$tabelaMensagem = "MENSAGEM_".$tipoCom;
$tabelaLote = $tipoCom."_LOTE";
$tabelaParametros = $tipoCom."_PARAMETROS";
$tabelaControle = $tipoCom."_LISTA_CONTROLE";
$tabelaLista = $tipoCom."_LISTA";
$procedureLista = "SP_RELAT_".$tipoCom."_CLIENTE";

$clientesUnicos = "CLIENTES_UNICOS_".$tipoCom;
$clientesTotalNao = "TOTAL_CLIENTE_".$tipoCom."_NAO";

$log_processa = "LOG_PROCESSA_".$tipoCom;
$dat_processa = "DAT_PROCESSA_".$tipoCom;

$dat_ini = $today = date("Y-m-d");
$cod_bltempl = 25;

if($tipoCom == "EMAIL"){
	$tabelaAutomacao = "TEMPLATE_AUTOMACAO";
	$tabelaSchedule = "CONTROLE_SCHEDULE";
	$cod_bltempl = 22;
}

switch ($opcao) {
	case 'CAD':

	$log_sab = 'S';
	$log_dom = 'S';
	$log_seg = 'S';
	$log_ter = 'S';
	$log_qua = 'S';
	$log_qui = 'S';
	$log_sex = 'S';

	$sql = "CALL SP_ALTERA_CAMPANHA (
	'0', 
	'".$cod_empresa."', 
	'9999', 
	'S', 
	'".$des_campanha."', 
	'DSF', 
	'fa-phone-alt', 
	'#5CFF66', 
	'N', 
	'Campanha comunicação', 
	'".$cod_usucada."', 
	'21',
	'S',
	'".$dat_ini."',
	NULL,
	'00:00:00',
	'',
	'N',
	'".$log_sab."',
	'".$log_dom."',
	'".$log_seg."',
	'".$log_ter."',
	'".$log_qua."',
	'".$log_qui."',
	'".$log_sex."',
	'CAD'    
) ";

$query = mysqli_query(connTemp($cod_empresa,''),$sql);
$qrCampanha = mysqli_fetch_assoc($query);
$cod_campanha = $qrCampanha['COD_NOVO'];

$sqlPers = "SELECT COD_PERSONA FROM PERSONA WHERE COD_EMPRESA = $cod_empresa and DES_PERSONA = 'Comunicação (Automática)'";

$arrayPers = mysqli_query(connTemp($cod_empresa,''),$sqlPers);

if(mysqli_num_rows($arrayPers) == 0){

	$sql = "CALL SP_ALTERA_PERSONA (
	0,
	$cod_empresa, 
	'S', 
	'Comunicação (Automática)', 
	'COM', 
	'fal fa-paper-plane',
	'#2c3e50', 
	'Automática comunicação simplificada', 
	'N', 
	'S', 
	$cod_usucada,
	9999, 
	'CAD'    
) ";



$result = mysqli_query(connTemp($cod_empresa,''),$sql);				
$qrBuscaPers = mysqli_fetch_assoc($result);

			//fnEscreve($qrBuscaNovo["COD_NOVO"]);				
$cod_persona = $qrBuscaPers["COD_NOVO"];

}else{

	$qrPers = mysqli_fetch_assoc($arrayPers);

	$cod_persona = $qrPers["COD_PERSONA"];

}

$cod_personas = $cod_persona;

$sqlRegra = "SELECT COD_PERSONA FROM PERSONAREGRA WHERE COD_PERSONA = $cod_persona";

		// fnEscreve($sqlRegra);

$arrayRegra = mysqli_query(connTemp($cod_empresa,''),$sqlRegra);

		// fnEscreve(mysqli_num_rows($arrayRegra));


if(mysqli_num_rows($arrayRegra) == 0){

			////////////////////////////////////////////////////////

	$check_masculino = "checked";
	$check_feminino = "checked";
	$bl1_masculino = "S";
	$bl1_feminino = "S";
	$bl1_funcionario = "N";
	$bl1_juridico = "S";
	$idadeIni = 0;
	$idadeFim = 150;
	$bl1_idades = "0;150";
	$bl1_logidade = "S";
	$bl1_tpniver = "";
	$check_endereco = "";
	$check_celular = "";
	$check_email = "";
	$check_telefone = 
	$bl1_aniversario = 0;
	$bl1_operaprofi = "";
	$bl1_profissoes = 0;
	$procCalc = "P";
	$check_log_fidelizado = "checked";
	$check_log_email = "";
	$check_log_sms = "";
	$check_log_telemark = "";
	$check_log_whatsapp = "";
	$check_log_push = "";	
	$bl1_log_lgpd = "N";
	$bl1_log_semlgpd = "N";
	$bl1_funcionafem = "N";
	$bl1_lognsexo = "S";
	$bl1_endereco = "N";
	$bl1_celular = "N";
	$bl1_email = "N";
	$bl1_telefone = "N";
	$bl1_log_fidelizado = "S";
	$bl1_log_email = "N";
	$bl1_log_sms = "N";
	$bl1_log_telemark = "N";
	$bl1_log_whatsapp = "N";
	$bl1_log_push = "N";

			//BLOCO 3 - FREQUÊNCIA	
	$bl3_compras_ini = "";
	$bl3_compras_fim = "";				
	$bl3_cadastros_ini = "";
	$bl3_cadastros_fim = "";				
	$bl3_ucompras_ini = "";
	$bl3_ucompras_fim = "";
	$bl3_ucompdias_ini = 0;
	$bl3_ucompdias_fim = 0;
	$bl3_inativo_ini = 0;
	$bl3_inativo_fim = 0;

	$bl3_comprase_ini = "";
	$bl3_comprase_fim = "";
	$bl3_log_semcompr='N';
	$bl3_semcompr_ini = "";
	$bl3_semcompr_fim = "";
	$bl3_log_semresg='N';
	$bl3_semresg_ini = "";
	$bl3_semresg_fim = "";

	$bl3_qtd_retorno_ini = 0;
	$bl3_qtd_retorno_fim = 0;

	$bl3_log_resgate = 'N';
	$bl3_tip_resgate = "";
	$bl3_qtd_resgate = 0;	

			//BLOCO 4 - VALOR
	$bl4_compra_min = 0;
	$bl4_compra_max = 0;				
	$bl4_valortm_min = 0;
	$bl4_valortm_max = 0;
	$bl4_gastos_min = 0;
	$bl4_gastos_max = 0;
	$bl4_credito_min = 0;
	$bl4_credito_max = 0;
	$bl4_tip_resgate = "";
	$bl4_qtd_resgate_min = 0;
	$bl4_qtd_resgate = 0;
	$bl4_qtd_avencer = 0;
	$bl4_tip_avencer = "";
	$bl4_tip_saldo = "";
	$bl4_val_saldo_min = 0;
	$bl4_val_saldo = 0;

			//BLOCO 5 - GEO
	$bl5_unive_origem = "C";
	$bl5_unive_todos = "S";
	$bl5_cod_unive = "0";
	$bl5_cod_estadof = 0;
	$check_unipref = "";
	$bl5_unipref = "N";

	$check_unive_origem_v = "";
	$check_unive_origem_o = "";
	$check_unive_origem_c = "checked";


			//BLOCO 6 - ENGAJA
	$bl6_engaja_1 = "N";
	$bl6_engaja_2 = "N";
	$bl6_engaja_3 = "N";
	$bl6_engaja_4 = "N";
	if ($bl6_engaja_1 == "S") {$check_bl6_engaja_1 = "checked";} else{$check_bl6_engaja_1 = "";}
	if ($bl6_engaja_2 == "S") {$check_bl6_engaja_2 = "checked";} else{$check_bl6_engaja_2 = "";}
	if ($bl6_engaja_3 == "S") {$check_bl6_engaja_3 = "checked";} else{$check_bl6_engaja_3 = "";}
	if ($bl6_engaja_4 == "S") {$check_bl6_engaja_4 = "checked";} else{$check_bl6_engaja_4 = "";}
	$bl6_freq_cliente = "";
	$bl6_freq_cliente_u = "";
	$tip_ticket = 0;
	$ticket_ini = "";
	$ticket_fim = "";

			////////////////////////////////////////////////////////

	$sqlPersRegra = "CALL SP_ALTERA_PERSONAREGRA (
	'".$cod_persona."', 
	'".$bl1_masculino."',				 
	'".$bl1_feminino."', 
	'".$bl1_funcionario."',
	'".$bl1_funcionafem."',
	'".$bl1_juridico."', 
	'".$bl1_lognsexo."', 
	'".$bl1_idades."',
	'".$bl1_logidade."',
	'".$bl1_tpniver."',
	'".$bl1_endereco."',
	'".$bl1_celular."',
	'".$bl1_email."',
	'".$bl1_telefone."',
	'".$bl1_aniversario."',				 
	'".$bl1_operaprofi."',				 
	'".$bl1_profissoes."',
	'".$bl1_log_fidelizado."',
	'".$bl1_log_email."',
	'".$bl1_log_sms."',
	'".$bl1_log_telemark."',
	'".$bl1_log_whatsapp."',
	'".$bl1_log_push."',
	'".$bl1_log_lgpd."',
	'".$bl1_log_semlgpd."',
	'".$cod_usucada."',
	'".fnDataSql($bl3_cadastros_ini)."',
	'".fnDataSql($bl3_cadastros_fim)."',
	'".fnDataSql($bl3_compras_ini)."',
	'".fnDataSql($bl3_compras_fim)."',
	'".fnDataSql($bl3_ucompras_ini)."',
	'".fnDataSql($bl3_ucompras_fim)."',
	'".fnValorSql($bl3_ucompdias_ini)."',
	'".fnValorSql($bl3_ucompdias_fim)."',
	'".fnValorSql($bl3_inativo_ini)."',
	'".fnValorSql($bl3_inativo_fim)."',
	'".fnDataSql($bl3_comprase_ini)."',
	'".fnDataSql($bl3_comprase_fim)."',
	'".$bl3_log_semcompr."',
	'".fnDataSql($bl3_semcompr_ini)."',
	'".fnDataSql($bl3_semcompr_fim)."',
	'".$bl3_log_semresg."',
	'".fnDataSql($bl3_semresg_ini)."',
	'".fnDataSql($bl3_semresg_fim)."',
	'".$bl3_log_resgate."',
	'".$bl3_tip_resgate."',
	'".fnValorSql($bl3_qtd_resgate)."',
	'".fnValorSql($bl4_compra_min)."',
	'".fnValorSql($bl4_compra_max)."',
	'".fnValorSql($bl4_valortm_min)."',
	'".fnValorSql($bl4_valortm_max)."',
	'".fnValorSql($bl4_gastos_min)."',
	'".fnValorSql($bl4_gastos_max)."',
	'".fnValorSql($bl4_credito_min)."',
	'".fnValorSql($bl4_credito_max)."',
	'".$bl4_tip_resgate."',
	'".fnValorSql($bl4_qtd_resgate_min)."',
	'".fnValorSql($bl4_qtd_resgate)."',
	'".fnValorSql($bl4_qtd_avencer)."',
	'".$bl4_tip_avencer."',
	'".$bl4_tip_saldo."',
	'".fnValorSql($bl4_val_saldo_min)."',
	'".fnValorSql($bl4_val_saldo)."',
	'".$bl5_unive_origem."',
	'".$bl5_unipref."',
	'".$bl5_unive_todos."',
	'".$bl5_cod_unive."',
	'".$bl5_cod_estadof."',
	'".fnValorSql($bl3_qtd_retorno_ini)."',
	'".fnValorSql($bl3_qtd_retorno_fim)."',
	'".$bl6_engaja_1."',
	'".$bl6_engaja_2."',
	'".$bl6_engaja_3."',
	'".$bl6_engaja_4."',
	'".$bl6_freq_cliente."',
	'".$bl6_freq_cliente_u."',
	".$tip_ticket.",
	null,
	null
) ";

mysqli_query(connTemp($cod_empresa,""),trim($sqlPersRegra));

			//atualiza base de personas
$sql2 = "CALL SP_ALTERA_PERSONACLASSIFICA (
'".$cod_persona."', 
'".$cod_empresa."' 
) ";		

mysqli_query(connTemp($cod_empresa,''),$sql2);

}


break;

case 'COM':

$sqlPers = "SELECT COD_PERSONA FROM PERSONA WHERE COD_EMPRESA = $cod_empresa and DES_PERSONA = 'Comunicação (Automática)'";

$arrayPers = mysqli_query(connTemp($cod_empresa,''),$sqlPers);

if($qrBuscaPersona = mysqli_fetch_assoc($arrayPers)){
	$cod_personas = $qrBuscaPersona['COD_PERSONA'];
}

		//criar gatilho
$sql = "INSERT INTO $tabelaGatilho(
	COD_EMPRESA,
	COD_CAMPANHA,
	TIP_GATILHO,
	TIP_CONTROLE,
	DES_PERIODO,
	TIP_MOMENTO,
	HOR_ESPECIF,
	DAT_INI,
	HOR_INI,
	LOG_DOMINGO,
	LOG_SEGUNDA,
	LOG_TERCA,
	LOG_QUARTA,
	LOG_QUINTA,
	LOG_SEXTA,
	LOG_SABADO,
	COD_USUARIO,
	LOG_STATUS
	) VALUES(
	'$cod_empresa',
	'$cod_campanha',
	'individualD',
	'99',
	'99',
	'99',
	'0',
	'$dat_ini',
	'00:00:00',
	'N',
	'N',
	'N',
	'N',
	'N',
	'N',
	'N',
	'$cod_usucada',
	'S'
);";

	$sql .= "INSERT INTO $tabelaSchedule(
		COD_EMPRESA,
		COD_CAMPANHA,
		TIP_GATILHO,
		COD_USUCADA
		) VALUES(
		'$cod_empresa',
		'$cod_campanha',
		'individualD',
		'$cod_usucada'
	);";

		mysqli_multi_query(ConnTemp($cod_empresa,''),$sql);

		//setar mensagem (template)
		$sql = "INSERT INTO $tabelaAutomacao(
			COD_EMPRESA,
			COD_CAMPANHA,
			COD_BLTEMPL
			) VALUES(
			$cod_empresa,
			$cod_campanha,
			$cod_bltempl
		)";

			mysqli_query(connTemp($cod_empresa,''),$sql);

			$sqlCod = "SELECT MAX(COD_TEMPLATE) COD_TEMPLATE FROM $tabelaAutomacao
			WHERE COD_EMPRESA = $cod_empresa AND COD_CAMPANHA = $cod_campanha AND COD_BLTEMPL = $cod_bltempl";

			$arrCod = mysqli_query(connTemp($cod_empresa,''),$sqlCod);				
			$qrBuscaCod = mysqli_fetch_assoc($arrCod);

			$cod_template_bloco = $qrBuscaCod[COD_TEMPLATE];

		//ESSE É O SQL QUE INSERE A TEMPLATE NO BLOCO - NOTE QUE TEM QUE TER ORDENAÇÃO (PARA LUCAS)

		// $sql = "INSERT INTO $tabelaMensagem(
		// 	COD_TEMPLATE_WHATSAPP,
		// 	COD_TEMPLATE_BLOCO,
		// 	COD_EMPRESA,
		// 	COD_CAMPANHA,
		// 	NUM_ORDENAC,
		// 	LOG_PRINCIPAL,
		// 	COD_USUCADA
		// 	) VALUES(
		// 	$cod_template_whatsapp,
		// 	$cod_template_bloco,
		// 	$cod_empresa,
		// 	$cod_campanha,
		// 	(SELECT NUM_ORDENAC FROM $tabelaAutomacao WHERE COD_TEMPLATE = $cod_template_bloco),
		// 	'S',
		// 	$cod_usucada
		// )";

		// // fnEscreve($sql);
		// mysqli_query(connTemp($cod_empresa,''),$sql);

		// -----------------------------------------------------------------------------------------------

			$sql = "SELECT COD_GATILHO, TIP_GATILHO FROM $tabelaGatilho WHERE COD_CAMPANHA = $cod_campanha";

			$qrCod = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));

			$tip_gatilho = $qrCod['TIP_GATILHO'];
			$cod_gatilho = $qrCod['COD_GATILHO'];
			$pct_reserva = 0;

			if($cod_gatilho != ""){

				if($tip_gatilho == 'individual'){
					$tipo = "CAD";
				}else{
					$tipo = "ANV";
				}

			}

			$sqlDel = "DELETE FROM $tabelaLote 
			WHERE COD_EMPRESA = $cod_empresa
			AND COD_CAMPANHA = $cod_campanha
			AND LOG_ENVIO = 'P'";

			mysqli_query(connTemp($cod_empresa,''),$sqlDel);

			$sqlProcCad = "CALL $procedureLista($cod_empresa, $cod_campanha, '$pct_reserva', '$cod_personas', 'ANV')";

			$retorno = mysqli_query(connTemp($cod_empresa,''),$sqlProcCad);

			$qrTot = mysqli_fetch_assoc($retorno);

			$sql2 = "INSERT INTO $tabelaParametros(
				COD_EMPRESA,
				COD_CAMPANHA,
				COD_PERSONAS,
				PCT_RESERVA,
				TOT_PERSONAS,
				CLIENTES_UNICOS,
				$clientesUnicos,
				CLIENTES_UNICO_PERC,
				$clientesTotalNao,
				CLIENTES_OPTOUT,
				CLIENTES_BLACKLIST,
				COD_USUCADA
				) VALUES(
				$cod_empresa,
				$cod_campanha,
				'$cod_personas',
				'$pct_reserva',
				'".fnLimpaCampoZero($qrTot['TOTAL_PERSONAS'])."',
				'".fnLimpaCampoZero($qrTot['CLIENTES_UNICOS'])."',
				'".fnLimpaCampoZero($qrTot[$clientesUnicos])."',
				'".fnLimpaCampoZero($qrTot['CLIENTES_UNICO_PERC'])."',
				'".fnLimpaCampoZero($qrTot[$clientesTotalNao])."',
				'".fnLimpaCampoZero($qrTot['CLIENTES_OPTOUT'])."',
				'".fnLimpaCampoZero($qrTot['CLIENTES_BLACKLIST'])."',
				$cod_usucada
			)";

			mysqli_query(connTemp($cod_empresa,''),$sql2);

			$sqlControle = "UPDATE $tabelaControle
			SET COD_LISTA = (
				SELECT MAX(COD_LISTA) AS COD_LISTA 
				FROM $tabelaParametros 
				WHERE COD_CAMPANHA = $cod_campanha 
				AND COD_USUCADA = $cod_usucada
				)
			WHERE COD_CAMPANHA = $cod_campanha
			AND COD_LISTA = 0";

			mysqli_query(connTemp($cod_empresa,''),$sqlControle);

			$sqlLista = "SELECT COD_CLIENTE, NUM_CELULAR FROM $tabelaLista
			WHERE COD_EMPRESA = $cod_empresa 
			AND COD_CAMPANHA = $cod_campanha";

			$arrayLista = mysqli_query(connTemp($cod_empresa,''),$sqlLista);

			$sqlLimpaCel = "";

			while ($qrLista = mysqli_fetch_assoc($arrayLista)){

				$numCelular = fnlimpacelular($qrLista['NUM_CELULAR']);

				$sqlLimpaCel .= "UPDATE $tabelaLista SET 
				NUM_CELULAR = '$numCelular'
				WHERE COD_CLIENTE = $qrLista[COD_CLIENTE]
				AND COD_CAMPANHA = $qrLista[COD_CAMPANHA]
				AND COD_EMPRESA = $cod_empresa;";

			}

			mysqli_multi_query(connTemp($cod_empresa,''),$sqlLimpaCel);

			unset($sqlLimpaCel);

			$sqlDelete = "DELETE FROM $tabelaLista 
			WHERE COD_EMPRESA = $cod_empresa
			AND COD_CAMPANHA = $cod_campanha 
			AND NUM_CELULAR = ''";

			mysqli_query(connTemp($cod_empresa,''),$sqlDelete);

	// processamento da campanha
			$sqlUpdt2 = "UPDATE CAMPANHA SET 
			$log_processa = 'S',
			$dat_processa = NOW()
			WHERE COD_EMPRESA = $cod_empresa 
			AND COD_CAMPANHA = $cod_campanha";

			mysqli_query(connTemp($cod_empresa,''),$sqlUpdt2);	

			break;

			case 'DSBC':

			$habilita = $_REQUEST['hab'];

			$sqlUpdt2 = "UPDATE CAMPANHA SET 
			LOG_ATIVO = '$habilita',
			DAT_ALTERAC = NOW()
			WHERE COD_EMPRESA = $cod_empresa 
			AND COD_CAMPANHA = $cod_campanha";
			
			mysqli_query(connTemp($cod_empresa,''),$sqlUpdt2);	

			break;

			case 'INIMASSA';

			$sqlUpdt2 = "UPDATE CAMPANHA SET 
			LOG_PROCESSA = 'S',
			DAT_PROCESSA = NOW(),
			LOG_PROCESSA_WHATSAPP = 'S',
			DAT_PROCESSA_WHATSAPP = NOW(),
			DAT_ALTERAC = NOW()
			WHERE COD_EMPRESA = $cod_empresa 
			AND COD_CAMPANHA = $cod_campanha";
			
			
			mysqli_query(connTemp($cod_empresa,''),$sqlUpdt2);

			$nomeRel = $cod_empresa."_".$cod_campanha.".csv";

			$caminhoRelat = "media/clientes/$cod_empresa";
			$sqlLista = "SELECT * FROM WHATSAPP_LISTA WHERE COD_EMPRESA = $cod_empresa AND COD_CAMPANHA = $cod_campanha";
			$query = mysqli_query(connTemp($cod_empresa , ''), $sqlLista);

			$newRow = array();
			$linha = "";
			while($qrLista = mysqli_fetch_assoc($query)){

				$linha = implode(";", $qrLista);
				$newRow[] = rtrim($linha, ';');
				$linha = "";
			}

			// MONTANDO UMA PLANILHA DOS CONTATOS DO ENVIO ----------------------------------------------------------------
			fngravacvs($newRow,$caminhoRelat,$nomeRel);

		}
	?>