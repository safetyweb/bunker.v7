<?php

$hashLocal = mt_rand();
$cod_usucada = $_SESSION['SYS_COD_USUARIO'];

$log_unifica = "N"; //CAMPANHA DE CUPONS

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		if (isset($_REQUEST['COD_RESGATE'])) {
			$cod_resgate = fnLimpaCampoZero($_REQUEST['COD_RESGATE']);
		} else {
			$cod_resgate = "";
		}

		if (isset($_REQUEST['COD_PERSONA'])) {
			$cod_persona = fnLimpaCampoZero($_REQUEST['COD_PERSONA']);
		} else {
			$cod_persona = "";
		}

		if (isset($_REQUEST['TIP_MOMRESG'])) {
			$tip_momresg = fnLimpaCampo($_REQUEST['TIP_MOMRESG']);
		} else {
			$tip_momresg = "";
		}

		if (isset($_REQUEST['NUM_DIASRSG'])) {
			$num_diasrsg = fnLimpaCampoZero($_REQUEST['NUM_DIASRSG']);
		} else {
			$num_diasrsg = "";
		}

		if (isset($_REQUEST['QTD_VALIDAD'])) {
			$qtd_validad = fnLimpaCampoZero($_REQUEST['QTD_VALIDAD']);
		} else {
			$qtd_validad = "";
		}

		if (isset($_REQUEST['TIP_DIASVLD'])) {
			$tip_diasvld = fnLimpaCampo($_REQUEST['TIP_DIASVLD']);
		} else {
			$tip_diasvld = "";
		}

		if (isset($_REQUEST['QTD_INATIVO'])) {
			$qtd_inativo = fnLimpaCampoZero($_REQUEST['QTD_INATIVO']);
		} else {
			$qtd_inativo = "";
		}

		if (isset($_REQUEST['NUM_INATIVO'])) {
			$num_inativo = fnLimpaCampo($_REQUEST['NUM_INATIVO']);
		} else {
			$num_inativo = "";
		}

		if (isset($_REQUEST['NUM_MINRESG'])) {
			$num_minresg = fnLimpaCampo($_REQUEST['NUM_MINRESG']);
		} else {
			$num_minresg = "";
		}

		if (isset($_REQUEST['PCT_MAXRESG'])) {
			$pct_maxresg = fnLimpaCampo($_REQUEST['PCT_MAXRESG']);
		} else {
			$pct_maxresg = "";
		}


		if (isset($_REQUEST['QTD_FRAUDES'])) {
			$qtd_fraudes = fnLimpaCampoZero($_REQUEST['QTD_FRAUDES']);
		} else {
			$qtd_fraudes = "";
		}

		if (isset($_REQUEST['TIP_FRAUDES'])) {
			$tip_fraudes = fnLimpaCampo($_REQUEST['TIP_FRAUDES']);
		} else {
			$tip_fraudes = "";
		}


		if (isset($_REQUEST['QTD_FRAUDES2'])) {
			$qtd_fraudes2 = fnLimpaCampoZero($_REQUEST['QTD_FRAUDES2']);
		} else {
			$qtd_fraudes2 = "";
		}

		if (isset($_REQUEST['TIP_FRAUDES2'])) {
			$tip_fraudes2 = fnLimpaCampo($_REQUEST['TIP_FRAUDES2']);
		} else {
			$tip_fraudes2 = "";
		}


		if (isset($_REQUEST['TIP_LIBFUNC'])) {
			$tip_libfunc = fnLimpaCampo($_REQUEST['TIP_LIBFUNC']);
		} else {
			$tip_libfunc = "";
		}

		if (isset($_REQUEST['TIP_LIBCLIE'])) {
			$tip_libclie = fnLimpaCampo($_REQUEST['TIP_LIBCLIE']);
		} else {
			$tip_libclie = "";
		}

		if (isset($_REQUEST['TIP_RELINFO'])) {
			$tip_relinfo = fnLimpaCampo($_REQUEST['TIP_RELINFO']);
		} else {
			$tip_relinfo = "";
		}

		if (isset($_REQUEST['HOR_RELINFO'])) {
			$hor_relinfo = fnLimpaCampo($_REQUEST['HOR_RELINFO']);
		} else {
			$hor_relinfo = "";
		}

		if (isset($_REQUEST['VAL_FRAUDFU'])) {
			$val_fraudfu = fnLimpaCampo($_REQUEST['VAL_FRAUDFU']);
		} else {
			$val_fraudfu = "";
		}

		if (isset($_REQUEST['TIP_FRAUDFU'])) {
			$tip_fraudfu = fnLimpaCampo($_REQUEST['TIP_FRAUDFU']);
		} else {
			$tip_fraudfu = "";
		}


		if (isset($_REQUEST['QTD_ALERTREG'])) {
			$qtd_alertreg = fnLimpaCampoZero($_REQUEST['QTD_ALERTREG']);
		} else {
			$qtd_alertreg = "";
		}

		if (isset($_REQUEST['TIP_ALERTREG'])) {
			$tip_alertreg = fnLimpaCampo($_REQUEST['TIP_ALERTREG']);
		} else {
			$tip_alertreg = "";
		}



		if (isset($_REQUEST['COD_PROGRAM'])) {
			$cod_program = fnLimpaCampoZero($_REQUEST['COD_PROGRAM']);
		} else {
			$cod_program = "";
		}

		if (isset($_REQUEST['COD_EMPRESA'])) {
			$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
		} else {
			$cod_empresa = "";
		}

		if (isset($_REQUEST['CONTA_FAIXA'])) {
			$conta_faixa = fnLimpaCampoZero($_REQUEST['CONTA_FAIXA']);
		} else {
			$conta_faixa = "";
		}

		if (isset($_REQUEST['QTDE_REGRAS'])) {
			$qtde_regras = fnLimpaCampoZero($_REQUEST['QTDE_REGRAS']);
		} else {
			$qtde_regras = "";
		}

		if (isset($_REQUEST['NOM_EMPRESA'])) {
			$nom_empresa = fnLimpaCampo($_REQUEST['NOM_EMPRESA']);
		} else {
			$nom_empresa = "";
		}


		if (isset($cod_univend) && ($cod_univend == 9999 || $cod_univend == '')) {
			$lojasSelecionadas = '0';
		}

		if (isset($_REQUEST['COD_CAMPANHA'])) {
			$cod_campanha = fnLimpaCampoZero($_REQUEST['COD_CAMPANHA']);
		} else {
			$cod_campanha = "";
		}


		if (isset($_REQUEST['PCT_VANTAGEM'])) {
			$pct_vantagem = fnLimpaCampo($_REQUEST['PCT_VANTAGEM']);
		} else {
			$pct_vantagem = "";
		}

		if (isset($_REQUEST['QTD_VANTAGEM'])) {
			$qtd_vantagem = fnLimpaCampo($_REQUEST['QTD_VANTAGEM']);
		} else {
			$qtd_vantagem = "";
		}

		if (isset($_REQUEST['COD_VANTAGE'])) {
			$cod_vantage = fnLimpaCampoZero($_REQUEST['COD_VANTAGE']);
		} else {
			$cod_vantage = "";
		}

		if (isset($_REQUEST['QTD_RESULTADO'])) {
			$qtd_resultado = fnLimpaCampo($_REQUEST['QTD_RESULTADO']);
		} else {
			$qtd_resultado = "";
		}

		if (isset($_REQUEST['NOM_VANTAGEM'])) {
			$nom_vantagem = fnLimpaCampo($_REQUEST['NOM_VANTAGEM']);
		} else {
			$nom_vantagem = "";
		}


		// fixo automático
		$nom_vantagem = "Cash back";

		if (isset($_REQUEST['LOG_PRODUTO'])) {
			$log_produto = fnLimpaCampo($_REQUEST['LOG_PRODUTO']);
		} else {
			$log_produto = "";
		}

		if ($log_produto == "") {
			$log_produto = "N";
		}

		if (isset($_REQUEST['LOG_INDICADOR'])) {
			$log_indicador = fnLimpaCampo($_REQUEST['LOG_INDICADOR']);
		} else {
			$log_indicador = "";
		}

		if ($log_indicador == "") {
			$log_indicador = "N";
		}

		if (isset($_REQUEST['LOG_CATPROD'])) {
			$log_catprod = fnLimpaCampo($_REQUEST['LOG_CATPROD']);
		} else {
			$log_catprod = "";
		}

		if ($log_catprod == "") {
			$log_catprod = "N";
		}

		if (isset($_REQUEST['LOG_FRAUDECLI'])) {
			$log_fraudecli = fnLimpaCampo($_REQUEST['LOG_FRAUDECLI']);
		} else {
			$log_fraudecli = "";
		}

		if ($log_fraudecli == "") {
			$log_fraudecli = "N";
		}

		$log_catprod = 'S';
		$log_produto = 'S';

		if (isset($_REQUEST['PCT_VANTAGEM_IND'])) {
			$pct_vantagem_ind = fnLimpaCampo($_REQUEST['PCT_VANTAGEM_IND']);
		} else {
			$pct_vantagem_ind = "";
		}

		if (isset($_REQUEST['COD_VANTAGEM_IND'])) {
			$cod_vantagem_ind = fnLimpaCampoZero($_REQUEST['COD_VANTAGEM_IND']);
		} else {
			$cod_vantagem_ind = "";
		}

		if (isset($_REQUEST['QTD_RESULTADO_IND'])) {
			$qtd_resultado_ind = fnLimpaCampoZero($_REQUEST['QTD_RESULTADO_IND']);
		} else {
			$qtd_resultado_ind = "";
		}


		if (isset($_REQUEST['TIP_GERACAO'])) {
			$tip_geracao = fnLimpaCampo($_REQUEST['TIP_GERACAO']);
		} else {
			$tip_geracao = "";
		}


		if (isset($_REQUEST['CPS_EXTRA_DOM'])) {
			$cps_extra_dom = fnLimpaCampoZero($_REQUEST['CPS_EXTRA_DOM']);
		} else {
			$cps_extra_dom = "";
		}

		if (isset($_REQUEST['CPS_EXTRA_SEG'])) {
			$cps_extra_seg = fnLimpaCampoZero($_REQUEST['CPS_EXTRA_SEG']);
		} else {
			$cps_extra_seg = "";
		}

		if (isset($_REQUEST['CPS_EXTRA_TER'])) {
			$cps_extra_ter = fnLimpaCampoZero($_REQUEST['CPS_EXTRA_TER']);
		} else {
			$cps_extra_ter = "";
		}

		if (isset($_REQUEST['CPS_EXTRA_QUA'])) {
			$cps_extra_qua = fnLimpaCampoZero($_REQUEST['CPS_EXTRA_QUA']);
		} else {
			$cps_extra_qua = "";
		}

		if (isset($_REQUEST['CPS_EXTRA_QUI'])) {
			$cps_extra_qui = fnLimpaCampoZero($_REQUEST['CPS_EXTRA_QUI']);
		} else {
			$cps_extra_qui = "";
		}

		if (isset($_REQUEST['CPS_EXTRA_SEX'])) {
			$cps_extra_sex = fnLimpaCampoZero($_REQUEST['CPS_EXTRA_SEX']);
		} else {
			$cps_extra_sex = "";
		}

		if (isset($_REQUEST['CPS_EXTRA_SAB'])) {
			$cps_extra_sab = fnLimpaCampoZero($_REQUEST['CPS_EXTRA_SAB']);
		} else {
			$cps_extra_sab = "";
		}


		if (isset($_REQUEST['CPS_EXTIND_DOM'])) {
			$cps_extind_dom = fnLimpaCampoZero($_REQUEST['CPS_EXTIND_DOM']);
		} else {
			$cps_extind_dom = "";
		}

		if (isset($_REQUEST['CPS_EXTIND_SEG'])) {
			$cps_extind_seg = fnLimpaCampoZero($_REQUEST['CPS_EXTIND_SEG']);
		} else {
			$cps_extind_seg = "";
		}

		if (isset($_REQUEST['CPS_EXTIND_TER'])) {
			$cps_extind_ter = fnLimpaCampoZero($_REQUEST['CPS_EXTIND_TER']);
		} else {
			$cps_extind_ter = "";
		}

		if (isset($_REQUEST['CPS_EXTIND_QUA'])) {
			$cps_extind_qua = fnLimpaCampoZero($_REQUEST['CPS_EXTIND_QUA']);
		} else {
			$cps_extind_qua = "";
		}

		if (isset($_REQUEST['CPS_EXTIND_QUI'])) {
			$cps_extind_qui = fnLimpaCampoZero($_REQUEST['CPS_EXTIND_QUI']);
		} else {
			$cps_extind_qui = "";
		}

		if (isset($_REQUEST['CPS_EXTIND_SEX'])) {
			$cps_extind_sex = fnLimpaCampoZero($_REQUEST['CPS_EXTIND_SEX']);
		} else {
			$cps_extind_sex = "";
		}

		if (isset($_REQUEST['CPS_EXTIND_SAB'])) {
			$cps_extind_sab = fnLimpaCampoZero($_REQUEST['CPS_EXTIND_SAB']);
		} else {
			$cps_extind_sab = "";
		}


		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		if ($opcao != '') {

			$sql = "CALL SP_ALTERA_CAMPANHAREGRA (
					 '" . $cod_campanha . "', 
					 '" . $cod_persona . "', 
					 '" . fnValorSql($pct_vantagem) . "', 
					 '" . $qtd_vantagem . "', 
					 '" . fnValorSql($qtd_resultado) . "', 
					 '" . $cod_usucada . "', 
					 '" . $nom_vantagem . "', 
					 '" . $cod_vantage . "', 
					 '" . $log_produto . "', 
					 '" . $log_catprod . "', 
					 '" . $cod_empresa . "', 
					 '" . $log_indicador . "', 
					 '" . $log_unifica . "', 
					 '" . fnValorSql($pct_vantagem_ind) . "', 
					 '" . $cod_vantagem_ind . "',
					 '" . $qtd_resultado_ind . "',
					 
					 '" . $tip_geracao . "',
					 '" . $cps_extra_dom . "',
					 '" . $cps_extra_seg . "',
					 '" . $cps_extra_ter . "',
					 '" . $cps_extra_qua . "',
					 '" . $cps_extra_qui . "',
					 '" . $cps_extra_sex . "',
					 '" . $cps_extra_sab . "', 					 					
					 '" . $cps_extind_dom . "',
					 '" . $cps_extind_seg . "',
					 '" . $cps_extind_ter . "',
					 '" . $cps_extind_qua . "',
					 '" . $cps_extind_qui . "',
					 '" . $cps_extind_sex . "',
					 '" . $cps_extind_sab . "',
					 '" . @$lojasSelecionadas . "'
					 
					); ";

			// fnEscreve($sql);

			mysqli_query(connTemp($cod_empresa, ''), $sql);


			$sql2 = "CALL SP_ALTERA_CAMPANHARESGATE (
					 '" . $cod_resgate . "', 
					 '" . $cod_campanha . "', 
					 '" . $cod_empresa . "', 
					 '" . $tip_momresg . "', 
					 '" . $num_diasrsg . "', 
					 '" . $qtd_validad . "', 
					 '" . $tip_diasvld . "', 
					 '" . $qtd_inativo . "', 
					 '" . $num_inativo . "', 
					 '" . fnValorSql($num_minresg) . "', 
					 '" . $pct_maxresg . "', 
					 '" . $qtd_fraudes . "', 
					 '" . $tip_fraudes . "', 
					 '" . $qtd_fraudes2 . "', 
					 '" . $tip_fraudes2 . "', 
					 '" . $log_fraudecli . "', 
					 '" . $tip_libfunc . "', 
					 '" . $tip_libclie . "', 
					 '" . $tip_relinfo . "', 
					 '" . $hor_relinfo . "', 
					 '" . @$cod_mailusu . "', 
					 '" . @$cod_acesusu . "', 
					 '" . $cod_usucada . "', 
					 '" . fnValorSql($val_fraudfu) . "', 
					 '" . $tip_fraudfu . "', 
					 '" . $qtd_alertreg . "', 
					 '" . $tip_alertreg . "', 
					 '" . $opcao . "'    
					) ";

			// fnEscreve($sql2);
			// fnEscreve($sql2);

			mysqli_query(connTemp($cod_empresa, ''), $sql2);

			$sqlOnline = "SELECT PC.COD_PRODUTO,
									 PC.DES_PRODUTO 
							  FROM PRODUTOCLIENTE PC 
							  WHERE LOG_MAISCASH = 'S' 
							  AND COD_EMPRESA = $cod_empresa";

			$arrayOnline = mysqli_query(connTemp($cod_empresa, ''), $sqlOnline);

			$temProd = mysqli_num_rows($arrayOnline);

			if ($temProd == 0) {

				$sqlProd = "INSERT INTO `PRODUTOCLIENTE` (`COD_EXTERNO`, `COD_EMPRESA`, `EAN`, `DES_PRODUTO`, `COD_CATEGOR`, `COD_SUBCATE`, `COD_FORNECEDOR`, `ATRIBUTO1`, `ATRIBUTO2`, `ATRIBUTO3`, `ATRIBUTO4`, `ATRIBUTO5`, `ATRIBUTO6`, `ATRIBUTO7`, `ATRIBUTO8`, `ATRIBUTO9`, `ATRIBUTO10`, `ATRIBUTO11`, `ATRIBUTO12`, `ATRIBUTO13`, `DES_IMAGEM`, `LOG_PONTUAR`, `COD_USUCADA`, `DAT_CADASTR`, `COD_ALTERAC`, `DAT_ALTERAC`, `COD_EXCLUSA`, `DAT_EXCLUSA`, `LOG_PRODPBM`, `VAL_CUSTO`, `VAL_PRECO`, `LOG_HABITEXC`, `LOG_IMPORT`, `LOG_NRESGATE`, `LOG_MAISCASH`) VALUES ('0', $cod_empresa, 'N', 'Venda', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, 0, NULL, 0, NULL, 'N', 0.00, 0.00, 'N', 'N', 'N', 'S')";

				mysqli_query(connTemp($cod_empresa, ''), $sqlProd);
			}

			// mysqli_multi_query(connTemp($cod_empresa,''),$sql);

			//mensagem de retorno
			switch ($opcao) {
				case 'CAD':
					$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					break;
				case 'ALT':
					$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";
					break;
				case 'EXC':
					$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";
					break;
					break;
			}
			$msgTipo = 'alert-success';
		}
	}
}

//busca dados da empresa
//$cod_empresa = $_SESSION["SYS_COD_EMPRESA"];
$cod_empresa = fnDecode($_GET['id']);
$sql = "SELECT COD_EMPRESA, NOM_FANTASI, COD_SEGMENT FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
//fnEscreve($sql);
$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

if (isset($arrayQuery)) {
	$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
	$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
	$cod_segmentEmp = $qrBuscaEmpresa['COD_SEGMENT'];
}

//liberação das abas
$abaPersona	= "S";
$abaCampanha = "S";
$abaVantagem = "N";
$abaRegras = "N";
$abaComunica = "N";
$abaAtivacao = "N";
$abaResultado = "N";

$abaPersonaComp = "active ";
$abaCampanhaComp = "";
$abaVantagemComp = "";
$abaRegrasComp = "";
$abaComunicaComp = "";
$abaResultadoComp = "";

//revalidada na aba de regras	
$abaAtivacaoComp = "";

//Busca módulos autorizados
// $sql = "SELECT COD_PERFILS FROM usuarios WHERE COD_USUARIO = $_SESSION[SYS_COD_USUARIO]";
// $qrPfl = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(),$sql));

// $sqlAut = "SELECT COD_MODULOS FROM perfil WHERE
// 		   COD_SISTEMA = 4 
// 		   AND COD_PERFILS IN($qrPfl[COD_PERFILS])";
// $qrAut = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(),$sqlAut));

// $modsAutorizados = explode(",", $qrAut['COD_MODULOS']);


$sqlCamp = "SELECT COD_CAMPANHA FROM CAMPANHA WHERE COD_EMPRESA = $cod_empresa and DES_CAMPANHA = 'Mais Cash (Automática)'";

$arrayCamp = mysqli_query(connTemp($cod_empresa, ''), $sqlCamp);

if (mysqli_num_rows($arrayCamp) == 0) {

	$dat_fim_camp = date("Y-m-d", strtotime("+ 4 years"));

	$sql = "CALL SP_ALTERA_CAMPANHA (
										'0', 
										'" . $cod_empresa . "', 
										'9999', 
										'S', 
										'Mais Cash (Automática)', 
										'MCASH', 
										'fal fa-dollar-sign', 
										'#206827', 
										'S', 
										'Campanha criada automaticamente via Mais Cash', 
										'" . $cod_usucada . "', 
										13,
										'S',
										NOW(),
										'$dat_fim_camp',
										'00:00:00',
										'23:59:59',
										'S',
										'CAD'    
										) ";

	// fnEscreve($sql);
	// fnTestesql(connTemp($cod_empresa,""),$sql);
	$result = mysqli_query(connTemp($cod_empresa, ''), $sql);
	$qrBuscaNovo = mysqli_fetch_assoc($result);

	//fnEscreve($qrBuscaNovo["COD_NOVO"]);				
	$cod_campanha = $qrBuscaNovo["COD_NOVO"];
} else {

	$qrCamp = mysqli_fetch_assoc($arrayCamp);

	$cod_campanha = $qrCamp["COD_CAMPANHA"];
}

$sqlPers = "SELECT COD_PERSONA FROM PERSONA WHERE COD_EMPRESA = $cod_empresa and DES_PERSONA = 'Fidelidade (Automática)'";

$arrayPers = mysqli_query(connTemp($cod_empresa, ''), $sqlPers);

if (mysqli_num_rows($arrayPers) == 0) {

	$sql = "CALL SP_ALTERA_PERSONA (
										0, 
										'" . $cod_empresa . "', 
										'S', 
										'Fidelidade (Automática)', 
										'FID', 
										'fal fa-user-tag', 
										'#2c3e50', 
										'Persona criada automaticamente via Mais Cash', 
										'N', 
										'" . $cod_usucada . "', 
										'9999', 
										'CAD'    
										) ";

	// fnEscreve($sql);
	// fnTestesql(connTemp($cod_empresa,""),$sql);
	$result = mysqli_query(connTemp($cod_empresa, ''), $sql);
	$qrBuscaPers = mysqli_fetch_assoc($result);

	//fnEscreve($qrBuscaNovo["COD_NOVO"]);				
	$cod_persona = $qrBuscaPers["COD_NOVO"];
} else {

	$qrPers = mysqli_fetch_assoc($arrayPers);

	$cod_persona = $qrPers["COD_PERSONA"];
}

$sqlRegra = "SELECT COD_PERSONA FROM PERSONAREGRA WHERE COD_PERSONA = $cod_persona";

// fnEscreve($sqlRegra);

$arrayRegra = mysqli_query(connTemp($cod_empresa, ''), $sqlRegra);

// fnEscreve(mysqli_num_rows($arrayRegra));


if (mysqli_num_rows($arrayRegra) == 0) {

	////////////////////////////////////////////////////////

	$check_masculino = "checked";
	$check_feminino = "checked";
	$bl1_masculino = "N";
	$bl1_feminino = "N";
	$bl1_funcionario = "N";
	$bl1_juridico = "N";
	$idadeIni = 0;
	$idadeFim = 150;
	$bl1_idades = "0;150";
	$bl1_logidade = "S";
	$bl1_tpniver = "N";
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

	//BLOCO 3 - FREQUÊNCIA	
	$bl3_compras_ini = "";
	$bl3_compras_fim = "";
	$bl3_cadastros_ini = "";
	$bl3_cadastros_fim = "";
	$bl3_ucompras_ini = "";
	$bl3_ucompras_fim = "";
	$bl3_ucompdias_ini = "";
	$bl3_ucompdias_fim = "";
	$bl3_inativo_ini = "";
	$bl3_inativo_fim = "";

	$bl3_comprase_ini = "";
	$bl3_comprase_fim = "";
	$bl3_log_semcompr = 'N';
	$bl3_semcompr_ini = "";
	$bl3_semcompr_fim = "";
	$bl3_log_semresg = 'N';
	$bl3_semresg_ini = "";
	$bl3_semresg_fim = "";

	$bl3_qtd_retorno_ini = "";
	$bl3_qtd_retorno_fim = "";

	$bl3_log_resgate = 'N';
	$bl3_tip_resgate = "";
	$bl3_qtd_resgate = "";

	//BLOCO 4 - VALOR
	$bl4_compra_min = "";
	$bl4_compra_max = "";
	$bl4_valortm_min = "";
	$bl4_valortm_max = "";
	$bl4_gastos_min = "";
	$bl4_gastos_max = "";
	$bl4_credito_min = "";
	$bl4_credito_max = "";
	$bl4_tip_resgate = "";
	$bl4_qtd_resgate_min = "";
	$bl4_qtd_resgate = "";
	$bl4_qtd_avencer = "";
	$bl4_tip_avencer = "";
	$bl4_tip_saldo = "";
	$bl4_val_saldo_min = "";
	$bl4_val_saldo = "";

	//BLOCO 5 - GEO
	$bl5_unive_origem = "N";
	$bl5_unive_todos = "S";
	$bl5_cod_unive = "0";
	$bl5_cod_estadof = "";
	$check_unipref = "";

	$check_unive_origem_v = "";
	$check_unive_origem_o = "";
	$check_unive_origem_c = "checked";


	//BLOCO 6 - ENGAJA
	$bl6_engaja_1 = "N";
	$bl6_engaja_2 = "N";
	$bl6_engaja_3 = "N";
	$bl6_engaja_4 = "N";
	if ($bl6_engaja_1 == "S") {
		$check_bl6_engaja_1 = "checked";
	} else {
		$check_bl6_engaja_1 = "";
	}
	if ($bl6_engaja_2 == "S") {
		$check_bl6_engaja_2 = "checked";
	} else {
		$check_bl6_engaja_2 = "";
	}
	if ($bl6_engaja_3 == "S") {
		$check_bl6_engaja_3 = "checked";
	} else {
		$check_bl6_engaja_3 = "";
	}
	if ($bl6_engaja_4 == "S") {
		$check_bl6_engaja_4 = "checked";
	} else {
		$check_bl6_engaja_4 = "";
	}
	$bl6_freq_cliente = "";
	$bl6_freq_cliente_u = "";

	////////////////////////////////////////////////////////

	$sqlPersRegra = "CALL SP_ALTERA_PERSONAREGRA (
				'" . $cod_persona . "', 
				'" . $bl1_masculino . "',				 
				'" . $bl1_feminino . "', 
				'" . $bl1_funcionario . "', 
				'" . $bl1_juridico . "', 
				'" . $bl1_idades . "',
				'" . $bl1_logidade . "',
				'" . $bl1_tpniver . "',
				'" . $bl1_endereco . "',
				'" . $bl1_celular . "',
				'" . $bl1_email . "',
				'" . $bl1_telefone . "',
				'" . $bl1_aniversario . "',				 
				'" . $bl1_operaprofi . "',				 
				'" . $bl1_profissoes . "',
				'" . $bl1_log_fidelizado . "',
				'" . $bl1_log_email . "',
				'" . $bl1_log_sms . "',
				'" . $bl1_log_telemark . "',
				'" . $bl1_log_whatsapp . "',
				'" . $bl1_log_push . "',
				'" . $bl1_log_lgpd . "',
				'" . $bl1_log_semlgpd . "',
				'" . $cod_usucada . "',
				'" . fnDataSql($bl3_cadastros_ini) . "',
				'" . fnDataSql($bl3_cadastros_fim) . "',
				'" . fnDataSql($bl3_compras_ini) . "',
				'" . fnDataSql($bl3_compras_fim) . "',
				'" . fnDataSql($bl3_ucompras_ini) . "',
				'" . fnDataSql($bl3_ucompras_fim) . "',
				'" . fnValorSql($bl3_ucompdias_ini) . "',
				'" . fnValorSql($bl3_ucompdias_fim) . "',
				'" . fnValorSql($bl3_inativo_ini) . "',
				'" . fnValorSql($bl3_inativo_fim) . "',
				'" . fnDataSql($bl3_comprase_ini) . "',
				'" . fnDataSql($bl3_comprase_fim) . "',
				'" . $bl3_log_semcompr . "',
				'" . fnDataSql($bl3_semcompr_ini) . "',
				'" . fnDataSql($bl3_semcompr_fim) . "',
				'" . $bl3_log_semresg . "',
				'" . fnDataSql($bl3_semresg_ini) . "',
				'" . fnDataSql($bl3_semresg_fim) . "',
				'" . $bl3_log_resgate . "',
				'" . $bl3_tip_resgate . "',
				'" . fnValorSql($bl3_qtd_resgate) . "',
				'" . fnValorSql($bl4_compra_min) . "',
				'" . fnValorSql($bl4_compra_max) . "',
				'" . fnValorSql($bl4_valortm_min) . "',
				'" . fnValorSql($bl4_valortm_max) . "',
				'" . fnValorSql($bl4_gastos_min) . "',
				'" . fnValorSql($bl4_gastos_max) . "',
				'" . fnValorSql($bl4_credito_min) . "',
				'" . fnValorSql($bl4_credito_max) . "',
				'" . $bl4_tip_resgate . "',
				'" . fnValorSql($bl4_qtd_resgate_min) . "',
				'" . fnValorSql($bl4_qtd_resgate) . "',
				'" . fnValorSql($bl4_qtd_avencer) . "',
				'" . $bl4_tip_avencer . "',
				'" . $bl4_tip_saldo . "',
				'" . fnValorSql($bl4_val_saldo_min) . "',
				'" . fnValorSql($bl4_val_saldo) . "',
				'" . $bl5_unive_origem . "',
				'" . $bl5_unipref . "',
				'" . $bl5_unive_todos . "',
				'" . $bl5_cod_unive . "',
				'" . $bl5_cod_estadof . "',
				'" . fnValorSql($bl3_qtd_retorno_ini) . "',
				'" . fnValorSql($bl3_qtd_retorno_fim) . "',
				'" . $bl6_engaja_1 . "',
				'" . $bl6_engaja_2 . "',
				'" . $bl6_engaja_3 . "',
				'" . $bl6_engaja_4 . "',
				'" . $bl6_freq_cliente . "',
				'" . $bl6_freq_cliente_u . "'
				) ";

	// fnEscreve($sqlPersRegra);	

	mysqli_query(connTemp($cod_empresa, ""), trim($sqlPersRegra));

	//atualiza base de personas
	$sql2 = "CALL SP_ALTERA_PERSONACLASSIFICA (
		 '" . $cod_persona . "', 
		 '" . $cod_empresa . "' 
		) ";

	// //fnEscreve($sql2);
	mysqli_query(connTemp($cod_empresa, ''), $sql2);
}

//busca dados da regra 
$sql = "SELECT * FROM CAMPANHAREGRA where COD_CAMPANHA = '" . $cod_campanha . "' ";
// fnEscreve($sql);
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
$qrBuscaTpCampanha = mysqli_fetch_assoc($arrayQuery);

if (isset($qrBuscaTpCampanha)) {
	$cod_persona = $qrBuscaTpCampanha['COD_PERSONA'];
	if (!empty($cod_persona)) {
		$tem_personas = "sim";
	} else {
		$tem_personas = "nao";
	}
	$pct_vantagem = $qrBuscaTpCampanha['PCT_VANTAGEM'];
	$qtd_vantagem = $qrBuscaTpCampanha['QTD_VANTAGEM'];
	$qtd_resultado = $qrBuscaTpCampanha['QTD_RESULTADO'];
	$nom_vantagem = $qrBuscaTpCampanha['NOM_VANTAGE'];
	$num_pessoas = $qrBuscaTpCampanha['NUM_PESSOAS'];
	$cod_vantage = $qrBuscaTpCampanha['COD_VANTAGE'];

	$log_cpfcnpj = $qrBuscaTpCampanha['LOG_CPFCNPJ'];
	if ($log_cpfcnpj == 'S') {
		$checaCPF = 'checked';
	} else {
		$checaCPF = '';
	}
	$log_email = $qrBuscaTpCampanha['LOG_EMAIL'];
	if ($log_email == 'S') {
		$checaMail = 'checked';
	} else {
		$checaMail = '';
	}
	$log_celular = $qrBuscaTpCampanha['LOG_CELULAR'];
	if ($log_celular == 'S') {
		$checaCel = 'checked';
	} else {
		$checaCel = '';
	}
} else {

	// $cod_persona = 0;
	$pct_vantagem = "";
	$qtd_vantagem = "";
	$qtd_vantagem = "";
	$nom_vantagem = "";
	$num_pessoas = 0;
	$cod_vantage = 0;
}

$checaTIP_MOMRESG_I = "checked";
$checaTIP_MOMRESG_D = "";
$qtd_validad = 90;
$tip_diasvld = "DIA";
$num_minresg = 1;
$pct_maxresg = 100;

$sql = "SELECT * FROM CAMPANHA where COD_CAMPANHA = '" . $cod_campanha . "' ";
//fnEscreve($sql);
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
$qrBuscaCampanha = mysqli_fetch_assoc($arrayQuery);

if (isset($qrBuscaCampanha)) {
	$log_ativo = $qrBuscaCampanha['LOG_ATIVO'];
	$des_campanha = $qrBuscaCampanha['DES_CAMPANHA'];
	$abr_campanha = $qrBuscaCampanha['ABR_CAMPANHA'];
	$des_icone = $qrBuscaCampanha['DES_ICONE'];
	$tip_campanha = $qrBuscaCampanha['TIP_CAMPANHA'];
	$log_realtime = $qrBuscaCampanha['LOG_REALTIME'];
}

$sql = "SELECT * FROM TIPOCAMPANHA where COD_TPCAMPA = '" . $tip_campanha . "' ";
//fnEscreve($sql);
$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
$qrBuscaTpCampanha = mysqli_fetch_assoc($arrayQuery);

if (isset($arrayQuery)) {
	$nom_tpcampa = $qrBuscaTpCampanha['NOM_TPCAMPA'];
	$abv_tpcampa = $qrBuscaTpCampanha['ABV_TPCAMPA'];
	$des_iconecp = $qrBuscaTpCampanha['DES_ICONE'];
	$label_1 = $qrBuscaTpCampanha['LABEL_1'];
	$label_2 = $qrBuscaTpCampanha['LABEL_2'];
	$label_3 = $qrBuscaTpCampanha['LABEL_3'];
	$label_4 = $qrBuscaTpCampanha['LABEL_4'];
	$label_5 = $qrBuscaTpCampanha['LABEL_5'];
}

//busca dados do resgate 
$sql = "SELECT * FROM CAMPANHARESGATE where COD_CAMPANHA = '" . $cod_campanha . "' ";
// fnEscreve($sql);
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
$qrCampanhaResgate = mysqli_fetch_assoc($arrayQuery);

//echo "<pre>";
//print_r($arrayQuery);
//echo "</pre>";

if (isset($qrCampanhaResgate)) {
	// fnEscreve($cod_campanha);
	// fnEscreve($tip_momresg);

	$cod_resgate = $qrCampanhaResgate['COD_RESGATE'];
	$tip_momresg = $qrCampanhaResgate['TIP_MOMRESG'];
	if ($tip_momresg == 'I') {
		$checaTIP_MOMRESG_I = 'checked';
	} else {
		$checaTIP_MOMRESG_I = '';
	}
	if ($tip_momresg == 'D') {
		$checaTIP_MOMRESG_D = 'checked';
	} else {
		$checaTIP_MOMRESG_D = '';
	}
	$num_diasrsg = $qrCampanhaResgate['NUM_DIASRSG'];
	$qtd_validad = $qrCampanhaResgate['QTD_VALIDAD'];
	$tip_diasvld = $qrCampanhaResgate['TIP_DIASVLD'];
	$qtd_inativo = fnLimpaCampoZero($qrCampanhaResgate['QTD_INATIVO']);
	$num_inativo = $qrCampanhaResgate['NUM_INATIVO'];
	$num_minresg = $qrCampanhaResgate['NUM_MINRESG'];
	$pct_maxresg = $qrCampanhaResgate['PCT_MAXRESG'];

	$qtd_fraudes = $qrCampanhaResgate['QTD_FRAUDES'];
	$tip_fraudes = $qrCampanhaResgate['TIP_FRAUDES'];

	$qtd_fraudes2 = $qrCampanhaResgate['QTD_FRAUDES2'];
	$tip_fraudes2 = $qrCampanhaResgate['TIP_FRAUDES2'];

	$tip_libfunc = $qrCampanhaResgate['TIP_LIBFUNC'];
	if ($tip_libfunc == 'SEMSENHA') {
		$checaTIP_LIBFUNC_S = 'checked';
	} else {
		$checaTIP_LIBFUNC_S = '';
	}
	if ($tip_libfunc == 'CAIXA') {
		$checaTIP_LIBFUNC_C = 'checked';
	} else {
		$checaTIP_LIBFUNC_C = '';
	}
	if ($tip_libfunc == 'BALCONISTA') {
		$checaTIP_LIBFUNC_B = 'checked';
	} else {
		$checaTIP_LIBFUNC_B = '';
	}
	if ($tip_libfunc == 'GERENTE') {
		$checaTIP_LIBFUNC_G = 'checked';
	} else {
		$checaTIP_LIBFUNC_G = '';
	}
	if ($tip_libfunc == 'SUPERVISOR') {
		$checaTIP_LIBFUNC_SP = 'checked';
	} else {
		$checaTIP_LIBFUNC_SP = '';
	}
	$tip_libclie = $qrCampanhaResgate['TIP_LIBCLIE'];
	if ($tip_libclie == 'SEMSENHA') {
		$checaTIP_LIBCLIE_S = 'checked';
	} else {
		$checaTIP_LIBCLIE_S = '';
	}
	if ($tip_libclie == 'COMSENHA') {
		$checaTIP_LIBCLIE_C = 'checked';
	} else {
		$checaTIP_LIBCLIE_C = '';
	}
	if ($tip_libclie == 'SMS') {
		$checaTIP_LIBCLIE_SM = 'checked';
	} else {
		$checaTIP_LIBCLIE_SM = '';
	}
	$tip_relinfo = $qrCampanhaResgate['TIP_RELINFO'];
	$hor_relinfo = $qrCampanhaResgate['HOR_RELINFO'];
	$cod_mailusu = $qrCampanhaResgate['COD_MAILUSU'];
	$cod_acesusu = $qrCampanhaResgate['COD_ACESUSU'];
	$val_fraudfu = $qrCampanhaResgate['VAL_FRAUDFU'];
	$tip_fraudfu = $qrCampanhaResgate['TIP_FRAUDFU'];
} else {

	$cod_resgate = 0;
	$checaTIP_MOMRESG_I = "checked";
	$checaTIP_LIBFUNC_S = 'checked';
	$checaTIP_LIBCLIE_S = 'checked';
}

$qtd_alertreg = $qrCampanhaResgate['QTD_ALERTREG'];
$tip_alertreg = $qrCampanhaResgate['TIP_ALERTREG'];


?>

<style>
	.fa-1dot5x {
		font-size: 45px;
		margin-top: 7px;
		margin-bottom: 7px;
	}

	body {
		overflow: hidden;
	}

	.notify-badge {
		position: absolute;
		left: 175;
		top: -10;
		background: #18bc9c;
		border-radius: 30px 30px 30px 30px;
		text-align: center;
		color: white;
		font-size: 11px;
	}

	.notify-badge span {
		margin: 0 auto;
	}

	/*.pos{
		left: 175;
		top:-10;
		background: #49C9B0;
		font-size: 11px;
		padding-top: 1px;
	}*/
</style>

<!-- Versão do fontawesome compatível com as checkbox (não remover) -->
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.2.0/css/font-awesome.min.css">

<!-- <div class="push30"></div>  -->

<!-- Portlet -->
<?php if ($popUp != "true") {  ?>
	<div class="portlet portlet-bordered">
	<?php } else { ?>
		<div class="portlet" style="padding: 0 20px 20px 20px;">
		<?php } ?>

		<?php if ($popUp != "true") {  ?>
			<div class="portlet-title">
				<div class="caption">
					<i class="glyphicon glyphicon-calendar"></i>
					<span class="text-primary"><?php echo $NomePg; ?></span>
				</div>
				<?php include "atalhosPortlet.php"; ?>
			</div>
		<?php } ?>
		<div class="portlet-body">

			<?php if ($msgRetorno <> '') { ?>
				<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<?php echo $msgRetorno; ?>
				</div>
			<?php } ?>

			<!-- <div class="push10"></div>  -->

			<div class="portlet-body">

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<div class="col-md-6 col-xs-12">

							<fieldset>
								<legend>Regras</legend>

								<div class="row">

									<div class="col-md-6">
										<label>&nbsp;</label>
										<div class="push"></div>
										<label for="inputName" class="control-label">% de crédito geral para a próxima compra:</label>
									</div>

									<div class="col-md-3">
										<div class="col-md-9" style="margin:0; padding: 0;">
											<div class="form-group">
												<label for="inputName" class="control-label required">&nbsp;</label>
												<input type="text" class="form-control text-center input-sm money" name="PCT_VANTAGEM" id="PCT_VANTAGEM" maxlength="6" value="<?php echo $pct_vantagem; ?>" data-error="Campo obrigatório">
												<div class="help-block with-errors"></div>
											</div>
										</div>
										<span style="margin:0; padding: 25px 0 0 15px; font-size: 18px;" class="col-md-2 pull-left">%<span>
									</div>

								</div>

								<div class="push20"></div>

								<div class="row">

									<div class="col-md-6">

										<?php

										$sqlVant = "SELECT 1 
																		FROM CAMPANHAPRODUTO
																		WHERE COD_CAMPANHA = $cod_campanha
																		AND COD_EMPRESA = $cod_empresa
																		AND COD_EXCLUSAO = 0";

										// fnEscreve($sqlVant);
										$arrayVant = mysqli_query(connTemp($cod_empresa, ''), $sqlVant);

										$countVantagens = mysqli_num_rows($arrayVant);

										if ($countVantagens > 0) {

										?>

											<div class="notify-badge text-center" id="notificaVantagens">
												<?= $countVantagens ?>
											</div>

										<?php

										}

										?>

										<a href="javascript:void(0)" class="btn btn-info btn-sm addBox" data-url="action.php?mod=<?= fnEncode(1774) ?>&id=<?= fnEncode($cod_empresa) ?>&idc=<?= fnEncode($cod_campanha) ?>&pop=true" data-title="Nova categoria">Adicionar categorias extras</a>

									</div>

								</div>

								<input type="hidden" class="money" name="CONTA_FAIXA" id="CONTA_FAIXA" maxlength="6" value="0" data-error="Campo obrigatório">
								<input type="hidden" name="QTD_VANTAGEM" id="QTD_VANTAGEM" value="1">
								<input type="hidden" name="COD_VANTAGE" id="COD_VANTAGE" value="1">
								<input type="hidden" name="QTD_RESULTADO" id="QTD_RESULTADO" value="1">

								<div class="push20"></div>


								<div class="row">

									<div class="col-md-4">
										<label>&nbsp;</label>
										<div class="push"></div>
										<label for="inputName" class="control-label">Tempo para resgate:</label>
									</div>

									<div class="col-md-3">

										<div class="checkbox checkbox-info">
											<input type="radio" name="TIP_MOMRESG" id="TIP_MOMRESG_1" value="I" <?php echo $checaTIP_MOMRESG_I; ?>>
											<label for="TIP_MOMRESG_1">
												Imediatamente após a compra
											</label>
										</div>

									</div>

									<div class="col-md-3">

										<label>&nbsp;</label>
										<div class="push"></div>

										<div class="checkbox checkbox-info radio-inline" style="margin-left: 15px;">
											<input type="radio" name="TIP_MOMRESG" id="TIP_MOMRESG_2" value="D" <?php echo $checaTIP_MOMRESG_D; ?>>
											<label for="TIP_MOMRESG_2">
												A partir de
											</label>
										</div>

									</div>

									<div class="col-md-2">

										<label>&nbsp;</label>
										<div class="push"></div>

										<div class="radio-inline" style="padding-left: 0;">
											<select data-placeholder="..." name="NUM_DIASRSG" id="NUM_DIASRSG" style="width: 110px; text-align: center;" class="chosen-select-deselect">
												<option value="0"></option>
												<option value="1">1</option>
												<option value="2">2</option>
												<option value="3">3</option>
												<option value="5">5</option>
												<option value="7">7</option>
												<option value="15">15</option>
											</select>
											<script>
												$("#NUM_DIASRSG").val("<?php echo $num_diasrsg; ?>").trigger("chosen:updated");
											</script>
											<label for="inlineRadio2">&nbsp; dias </label>
										</div>

									</div>

								</div>

								<div class="push20"></div>

								<div class="row">

									<div class="col-md-6">
										<label>&nbsp;</label>
										<div class="push"></div>
										<label for="inputName" class="control-label">Tempo de validade para resgate:</label>
									</div>

									<div class="col-md-2">
										<label for="inputName" class="control-label required">Qtd.</label>
										<input type="text" class="form-control text-center int input-sm" name="QTD_VALIDAD" id="QTD_VALIDAD" maxlength="3" value="<?php echo $qtd_validad; ?>" required>
									</div>

									<div class="col-md-4">
										<div class="form-group">
											<label for="inputName" class="control-label required">&nbsp;</label>
											<select data-placeholder="..." name="TIP_DIASVLD" id="TIP_DIASVLD" style="width: 150px; text-align: center;" class="chosen-select-deselect" required>
												<option value="0"></option>
												<option value="DIA">dias</option>
												<option value="NEX">não expira</option>
											</select>
											<script>
												$("#TIP_DIASVLD").val("<?php echo $tip_diasvld; ?>").trigger("chosen:updated");
											</script>
										</div>
									</div>

								</div>

								<div class="push20"></div>

								<div class="row">

									<div class="col-md-6">
										<label>&nbsp;</label>
										<div class="push"></div>
										<label for="inputName" class="control-label">Vantagem expira caso o cliente fique sem comprar por:</label>
									</div>

									<div class="col-md-2">
										<label for="inputName" class="control-label required">Qtd.</label>
										<input type="text" class="form-control text-center int input-sm" name="QTD_INATIVO" id="QTD_INATIVO" maxlength="3" value="<?php echo $qtd_inativo; ?>" required>
									</div>

									<div class="col-md-4">
										<label for="inputName" class="control-label required">&nbsp;</label>
										<select data-placeholder="informe o período" name="NUM_INATIVO" id="NUM_INATIVO" style="width: 220px; text-align: center;" class="chosen-select-deselect" required>
											<option value="0"></option>
											<option value="DIA">dias</option>
											<option value="NEX">não expira por inatividade</option>
										</select>
										<script>
											$("#NUM_INATIVO").val("<?php echo $num_inativo; ?>").trigger("chosen:updated");
										</script>
									</div>

								</div>

								<div class="push20"></div>

								<div class="row">

									<div class="col-md-5">
										<label for="inputName" class="control-label required">Valor mínimo de resgate:</label>
									</div>

									<div class="col-md-4">
										<input type="text" class="form-control text-center input-sm money" name="NUM_MINRESG" id="NUM_MINRESG" value="<?php echo fnValor($num_minresg, 2); ?>" required>
									</div>

								</div>

								<div class="push20"></div>

								<div class="row">

									<div class="col-md-5">
										<label for="inputName" class="control-label required">Valor máx. de resgate:</label>
									</div>

									<div class="col-md-4">
										<input type="text" class="form-control text-center input-sm int" name="PCT_MAXRESG" id="PCT_MAXRESG" value="<?php echo fnValor($pct_maxresg, 0); ?>" required>
									</div>
									<span style="margin:0; padding: 5px 0 0 0; font-size: 18px;" class="col-md-2 pull-left">%<span>

											<!-- <div class="col-md-1">
													<div class="push5"></div>
													%
												</div> -->

								</div>

								<div class="push20"></div>
								<div class="push5"></div>

							</fieldset>

						</div>

						<div class="col-md-6 col-xs-12">

							<fieldset>
								<legend>Anti Fraude</legend>

								<div class="row">

									<div class="col-md-12" style="float: left; ">
										<div class="form-group">
											<label for="inputName" class="control-label">Quantidade máxima de vezes que poderá ser concedido a vantagem para o mesmo CPF</label>
											<div class="push5"></div>

											<div class="col-md-2" style="padding-right: 0;">
												<div class="form-group">
													<label for="inputName" class="control-label">Qtd.</label>
													<input type="text" class="form-control text-center int input-sm" name="QTD_FRAUDES" id="QTD_FRAUDES" maxlength="3" value="<?php echo $qtd_fraudes; ?>">
												</div>
											</div>

											<div class="col-md-7" style="padding-left: 0;">
												<div class="push15"></div>
												<div class="checkbox-info radio-inline">

													<label for="inlineRadio2"><b>vezes</b> por &nbsp; </label>
												</div>
												<div class="radio-inline" style="padding-left: 0; width: 120px;">
													<select data-placeholder="..." name="TIP_FRAUDES" id="TIP_FRAUDES" style="width: 150px; text-align: center;" class="chosen-select-deselect">
														<option value="0"></option>
														<option value="DIA">Dia</option>
														<option value="SEM">Semana</option>
														<option value="MES">Mês</option>
														<option value="ILM">Ilimitado</option>
													</select>
													<script>
														$("#TIP_FRAUDES").val("<?php echo $tip_fraudes; ?>").trigger("chosen:updated");
													</script>
												</div>

											</div>

										</div>
									</div>

									<div class="push10"></div>

									<div class="col-md-12" style="float: left; ">
										<div class="form-group">
											<div class="col-md-2" style="padding-right: 0;">
												<div class="form-group">
													<label for="inputName" class="control-label">Qtd.</label>
													<input type="text" class="form-control text-center int input-sm" name="QTD_FRAUDES2" id="QTD_FRAUDES2" maxlength="3" value="<?php echo $qtd_fraudes2; ?>">
												</div>
											</div>

											<div class="col-md-7" style="padding-left: 0;">
												<div class="push15"></div>
												<div class="checkbox-info radio-inline">

													<label for="inlineRadio2"><b>vezes</b> por &nbsp; </label>
												</div>
												<div class="radio-inline" style="padding-left: 0;  width: 120px;">
													<select data-placeholder="..." name="TIP_FRAUDES2" id="TIP_FRAUDES2" style="width: 150px; text-align: center;" class="chosen-select-deselect">
														<option value="0"></option>
														<option value="DIA">Dia</option>
														<option value="SEM">Semana</option>
														<option value="MES">Mês</option>
														<option value="ILM">Ilimitado</option>
													</select>
													<script>
														$("#TIP_FRAUDES2").val("<?php echo $tip_fraudes2; ?>").trigger("chosen:updated");
													</script>
												</div>

											</div>

										</div>
									</div>

									<div class="push10"></div>

									<div class="col-md-12" style="float: left; ">
										<div class="form-group col-md-12">
											<label for="inputName" class="control-label">Bloquear cliente após anti fraude</label>
											<div class="push5"></div>
											<label class="switch switch-small">
												<input type="checkbox" name="LOG_FRAUDECLI" id="LOG_FRAUDECLI" class="switch switch-small" value="S" <?php echo @$checalog_fraudecli; ?>>
												<span></span>
											</label>
										</div>
									</div>

									<div class="push20"></div>

									<div class="col-md-12" style="float: left; ">
										<div class="form-group">
											<label for="inputName" class="control-label">Para <b>funcionários</b> considerar apenas compras <b>acima</b> de:</label>
											<div class="push5"></div>

											<div class="col-md-3" style="padding-right: 0;">
												<div class="form-group">
													<label for="inputName" class="control-label">Valor da Compra</label>
													<input type="text" class="form-control text-center money input-sm" name="VAL_FRAUDFU" id="VAL_FRAUDFU" maxlength="7" value="<?php echo fnVlVazio($val_fraudfu); ?>">
												</div>
												<div class="help-block with-errors">em reais (R$)</div>
											</div>

											<div class="col-md-7" style="padding-left: 0;">
												<div class="push15"></div>
												<div class="checkbox-info radio-inline">
													<label for="inlineRadio3"><b>por</b> &nbsp; </label>
												</div>
												<div class="radio-inline" style="padding-left: 0;">
													<select data-placeholder="..." name="TIP_FRAUDFU" id="TIP_FRAUDFU" style="width: 150px; text-align: center;" class="chosen-select-deselect">
														<option value="0"></option>
														<option value="DIA">Dia</option>
														<option value="SEM">Semana</option>
														<option value="MES">Mês</option>
													</select>
													<script>
														$("#TIP_FRAUDFU").val("<?php echo $tip_fraudfu; ?>").trigger("chosen:updated");
													</script>
												</div>

											</div>

										</div>

									</div>

								</div>

								<!-- <div class="push20"></div>
										
											<div class="row">

												<div class="col-md-12">
													<div class="disabledBlock"></div>
													<div class="form-group">
														<label for="inputName" class="control-label">Senha para liberação de resgate</label>
														<div class="push5"></div>
														
															<div class="col-md-6">
																<label><b>Funcionários</b></label>
																<div class="checkbox checkbox-info">
																	<input type="radio" name="TIP_LIBFUNC" id="TIP_LIBFUNC_1" value="SEMSENHA" <?php echo $checaTIP_LIBFUNC_S; ?> >
																	<label for="TIP_LIBFUNC_1">
																		Não solicitar senha
																	</label>
																</div>
																
																<div class="checkbox checkbox-info">
																	<input type="radio" name="TIP_LIBFUNC" id="TIP_LIBFUNC_2" value="CAIXA" <?php echo $checaTIP_LIBFUNC_C; ?> >
																	<label for="TIP_LIBFUNC_2">
																		Caixa
																	</label>
																</div>
																
																<div class="checkbox checkbox-info">
																	<input type="radio" name="TIP_LIBFUNC" id="TIP_LIBFUNC_3" value="BALCONISTA" <?php echo $checaTIP_LIBFUNC_B; ?> >
																	<label for="TIP_LIBFUNC_3">
																		Balconista
																	</label>
																</div>
																
																<div class="checkbox checkbox-info"> 
																	<input type="radio" name="TIP_LIBFUNC" id="TIP_LIBFUNC_4" value="GERENTE" <?php echo $checaTIP_LIBFUNC_G; ?> >
																	<label for="TIP_LIBFUNC_4">
																		Gerente
																	</label>
																</div>
																
																<div class="checkbox checkbox-info">
																	<input type="radio" name="TIP_LIBFUNC" id="TIP_LIBFUNC_5" value="SUPERVISOR" <?php echo $checaTIP_LIBFUNC_SP; ?> >
																	<label for="TIP_LIBFUNC_5">
																		Supervisor
																	</label>
																</div>
																
															</div>
															
															<div class="col-md-6">
																<div class="disabledBlock"></div>
																<label><b>Clientes</b></label>
																<div class="checkbox checkbox-info">
																	<input type="radio" name="TIP_LIBCLIE" id="TIP_LIBCLIE_1" value="SEMSENHA" <?php echo $checaTIP_LIBCLIE_S; ?> >
																	<label for="TIP_LIBCLIE_1">
																		Não solicitar senha
																	</label>
																</div>
																
																<div class="checkbox checkbox-info">
																	<input type="radio" name="TIP_LIBCLIE" id="TIP_LIBCLIE_2" value="COMSENHA" <?php echo $checaTIP_LIBCLIE_C; ?> >
																	<label for="TIP_LIBCLIE_2">
																		Cliente digitar senha
																	</label>
																</div>
																
																<div class="checkbox checkbox-info">
																	<input type="radio" name="TIP_LIBCLIE" id="TIP_LIBCLIE_3" value="SMS" <?php echo $checaTIP_LIBCLIE_SM; ?> >
																	<label for="TIP_LIBCLIE_3">
																		Envio de senha por SMS 
																	</label>
																</div>
																														
															</div>
															
															
													</div>														
												</div>

											</div> -->

								<input type="hidden" name="TIP_LIBFUNC" id="TIP_LIBFUNC" value="<?= $tip_libfunc ?>">
								<input type="hidden" name="TIP_LIBCLIE" id="TIP_LIBCLIE" value="<?= $tip_libclie ?>">
								<input type="hidden" name="COD_PERSONA" id="COD_PERSONA" value="<?= $cod_persona ?>">

								<div class="push15"></div>

							</fieldset>

						</div>

						<div class="push10"></div>
						<hr>
						<div class="form-group text-right col-lg-12">
							<?php if ($cod_resgate == 0) { ?>
								<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
							<?php } else { ?>
								<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Alterar</button>
							<?php } ?>

						</div>

						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="COD_RESGATE" id="COD_RESGATE" value="<?= $cod_resgate ?>">
						<input type="hidden" name="COD_CAMPANHA" id="COD_CAMPANHA" value="<?= $cod_campanha ?>">
						<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?= $cod_empresa ?>">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

						<div class="push5"></div>

					</form>

				</div>

			</div>

		</div>


		<!-- modal -->
		<div class="modal fade" id="popModal" tabindex='-1'>
			<div class="modal-dialog" style="">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title"></h4>
					</div>
					<div class="modal-body">
						<iframe frameborder="0" style="width: 100%; height: 80%"></iframe>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->

		<div class="push20"></div>

		<form id="formModal">
			<input type="hidden" class="input-sm" name="REFRESH_CAMPANHA" id="REFRESH_CAMPANHA" value="N">
			<input type="hidden" class="input-sm" name="REFRESH_PERSONA" id="REFRESH_PERSONA" value="N">
		</form>

		<script type="text/javascript">
			parent.$("#conteudoAba2").css("height", ($(".portlet").height() + 50) + "px");

			$(document).ready(function() {

				//modal close
				$('#popModal').on('hidden.bs.modal', function() {

					if ($('#REFRESH_PERSONA').val() == "S") {
						//alert("atualiza");
						RefreshPersona("<?php echo fnEncode($cod_empresa) ?>");
						$('#REFRESH_PERSONA').val("N");
					}

					if ($('#REFRESH_CAMPANHA').val() == "S") {
						//alert("atualiza");
						RefreshCampanha("<?php echo fnEncode($cod_empresa) ?>");
						$('#REFRESH_CAMPANHA').val("N");
					}

				});

			});

			function RefreshPersona(idEmp) {
				$.ajax({
					type: "GET",
					url: "ajxRefreshPersona.do",
					data: {
						ajx1: idEmp
					},
					beforeSend: function() {
						$('#div_refreshPersona').html('<div class="loading" style="width: 100%;"></div>');
					},
					success: function(data) {
						$("#div_refreshPersona").html(data);
					},
					error: function() {
						$('#div_refreshPersona').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
					}
				});
			}

			function RefreshCampanha(idEmp) {
				$.ajax({
					type: "GET",
					url: "ajxRefreshCampanha.do#campanha",
					data: {
						ajx1: idEmp
					},
					beforeSend: function() {
						$('#div_refreshCampanha').html('<div class="loading" style="width: 100%;"></div>');
					},
					success: function(data) {
						$("#div_refreshCampanha").html(data);
					},
					error: function() {
						$('#div_refreshCampanha').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
					}
				});
			}

			function retornaForm(index) {
				$("#formulario #COD_SERVIDOR").val($("#ret_COD_SERVIDOR_" + index).val());
				$("#formulario #DES_SERVIDOR").val($("#ret_DES_SERVIDOR_" + index).val());
				$("#formulario #DES_ABREVIA").val($("#ret_DES_ABREVIA_" + index).val());
				$("#formulario #DES_GERAL").val($("#ret_DES_GERAL_" + index).val());
				$("#formulario #COD_OPERACIONAL").val($("#ret_COD_OPERACIONAL_" + index).val()).trigger("chosen:updated");
				$("#formulario #DES_OBSERVA").val($("#ret_DES_OBSERVA_" + index).val());
				$('#formulario').validator('validate');
				$("#formulario #hHabilitado").val('S');
			}
		</script>