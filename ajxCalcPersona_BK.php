<?php include "_system/_functionsMain.php"; 

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);


	if( $_SERVER['REQUEST_METHOD']=='POST' ){

		//BLOCO 1
		//homens
		if (empty($_REQUEST['BL1_MASCULINO'])) {$bl1_masculino='N';}else{$bl1_masculino=$_REQUEST['BL1_MASCULINO'];}
		//mulheres
		if (empty($_REQUEST['BL1_FEMININO'])) {$bl1_feminino='N';}else{$bl1_feminino=$_REQUEST['BL1_FEMININO'];}
		//jurídico
		if (empty($_REQUEST['BL1_JURIDICO'])) {$bl1_juridico='N';}else{$bl1_juridico=$_REQUEST['BL1_JURIDICO'];}
		
		//array - idades
		$bl1_idades = explode(';', $_POST['BL1_IDADES'][0]);
		$idadeIni = $bl1_idades[0];
		$idadeFim = $bl1_idades[1];
		
		//endereço
		if (empty($_REQUEST['BL1_ENDERECO'])) {$bl1_endereco='N';}else{$bl1_endereco=$_REQUEST['BL1_ENDERECO'];}			
		//celular
		if (empty($_REQUEST['BL1_CELULAR'])) {$bl1_celular='N';}else{$bl1_celular=$_REQUEST['BL1_CELULAR'];}
		//email
		if (empty($_REQUEST['BL1_EMAIL'])) {$bl1_email='N';}else{$bl1_email=$_REQUEST['BL1_EMAIL'];}
		//telefone
		if (empty($_REQUEST['BL1_TELEFONE'])) {$bl1_telefone='N';}else{$bl1_telefone=$_REQUEST['BL1_TELEFONE'];}

		//Recebe E-mail
		if (empty($_REQUEST['BL1_LOG_EMAIL'])) {$bl1_log_email='N';}else{$bl1_log_email=$_REQUEST['BL1_LOG_EMAIL'];}
		//Recebe SMS
		if (empty($_REQUEST['BL1_LOG_SMS'])) {$bl1_log_sms='N';}else{$bl1_log_sms=$_REQUEST['BL1_LOG_SMS'];}
		//Recebe Telemarketing
		if (empty($_REQUEST['BL1_LOG_TELEMARK'])) {$bl1_log_telemark='N';}else{$bl1_log_telemark=$_REQUEST['BL1_LOG_TELEMARK'];}
		//Recebe Whatsapp
		if (empty($_REQUEST['BL1_LOG_WHATSAPP'])) {$bl1_log_whatsapp='N';}else{$bl1_log_whatsapp=$_REQUEST['BL1_LOG_WHATSAPP'];}
		//Recebe Push
		if (empty($_REQUEST['BL1_LOG_PUSH'])) {$bl1_log_push='N';}else{$bl1_log_push=$_REQUEST['BL1_LOG_PUSH'];}
		
		//aniversario		
		//array - aniversario
		if (isset($_POST['BL1_ANIVERSARIO'])){
			$Arr_BL1_ANIVERSARIO = $_POST['BL1_ANIVERSARIO'];
			//print_r($Arr_BL1_ANIVERSARIO);			 
		   for ($i=0;$i<count($Arr_BL1_ANIVERSARIO);$i++) 
		   { 
			$bl1_aniversario = $bl1_aniversario.$Arr_BL1_ANIVERSARIO[$i].";";
		   } 			   
		   $bl1_aniversario = substr($bl1_aniversario,0,-1);				
		}else{$bl1_aniversario = "0";}
		
		//profissões
		$bl1_operaprofi = fnLimpaCampoHtml($_REQUEST['BL1_OPERAPROFI']);
		//array - profissões
		if (isset($_POST['BL1_PROFISSOES'])){
			$Arr_BL1_PROFISSOES = $_POST['BL1_PROFISSOES'];
			//print_r($Arr_BL1_PROFISSOES);			 
		   for ($i=0;$i<count($Arr_BL1_PROFISSOES);$i++) 
		   { 
			$bl1_profissoes = $bl1_profissoes.$Arr_BL1_PROFISSOES[$i].";";
		   } 			   
		   $bl1_profissoes = substr($bl1_profissoes,0,-1);				
		}else{$bl1_profissoes = "0";}
			
			
			//BLOCO 2 - PRODUTOS (AUTOMÁTICO)
			
			//BLOCO 3 - FREQUÊNCIA	
			$bl3_compras_ini = fnLimpaCampo($_REQUEST['BL3_COMPRAS_INI']);
			$bl3_compras_fim = fnLimpaCampo($_REQUEST['BL3_COMPRAS_FIM']);
			
			$bl3_cadastros_ini = fnLimpaCampo($_REQUEST['BL3_CADASTROS_INI']);
			$bl3_cadastros_fim = fnLimpaCampo($_REQUEST['BL3_CADASTROS_FIM']);
			
			$bl3_ucompras_ini = fnLimpaCampo($_REQUEST['BL3_UCOMPRAS_INI']);
			$bl3_ucompras_fim = fnLimpaCampo($_REQUEST['BL3_UCOMPRAS_FIM']);
			
			$bl3_qtd_retorno_ini = fnLimpaCampo($_REQUEST['BL3_QTD_RETORNO_INI']);
			$bl3_qtd_retorno_fim = fnLimpaCampo($_REQUEST['BL3_QTD_RETORNO_FIM']);
			
			if (empty($_REQUEST['BL3_LOG_RESGATE'])) {$bl3_log_resgate='N';}else{$bl3_log_resgate=$_REQUEST['BL3_LOG_RESGATE'];}
			$bl3_tip_resgate = fnLimpaCampo($_REQUEST['BL3_TIP_RESGATE']);
			$bl3_qtd_resgate = fnLimpaCampo($_REQUEST['BL3_QTD_RESGATE']);

			//BLOCO 4 - VALOR
			$bl4_compra_min = fnLimpaCampo($_REQUEST['BL4_COMPRA_MIN']);
			$bl4_compra_max = fnLimpaCampo($_REQUEST['BL4_COMPRA_MAX']);
			
			$bl4_valortm_min = fnLimpaCampo($_REQUEST['BL4_VALORTM_MIN']);
			$bl4_valortm_max = fnLimpaCampo($_REQUEST['BL4_VALORTM_MAX']);
			
			$bl4_gastos_min = fnLimpaCampo($_REQUEST['BL4_GASTOS_MIN']);
			$bl4_gastos_max = fnLimpaCampo($_REQUEST['BL4_GASTOS_MAX']);
			
			$bl4_credito_min = fnLimpaCampo($_REQUEST['BL4_CREDITO_MIN']);
			$bl4_credito_max = fnLimpaCampo($_REQUEST['BL4_CREDITO_MAX']);
			
			$bl4_tip_resgate = fnLimpaCampo($_REQUEST['BL4_TIP_RESGATE']);
			$bl4_qtd_resgate_min = fnLimpaCampo($_REQUEST['BL4_QTD_RESGATE_MIN']);
			$bl4_qtd_resgate = fnLimpaCampo($_REQUEST['BL4_QTD_RESGATE']);
			
			$bl4_qtd_avencer = fnLimpaCampo($_REQUEST['BL4_QTD_AVENCER']);
			$bl4_tip_avencer = fnLimpaCampo($_REQUEST['BL4_TIP_AVENCER']);
			
			$bl4_tip_saldo = fnLimpaCampo($_REQUEST['BL4_TIP_SALDO']);
			$bl4_val_saldo_min = fnLimpaCampo($_REQUEST['BL4_VAL_SALDO_MIN']);
			$bl4_val_saldo = fnLimpaCampo($_REQUEST['BL4_VAL_SALDO']);
			
			//BLOCO 5 - GEO
			if (empty($_REQUEST['BL5_UNIVE_ORIGEM'])) {$bl5_unive_origem='N';}else{$bl5_unive_origem=$_REQUEST['BL5_UNIVE_ORIGEM'];}
			if (empty($_REQUEST['BL5_UNIVE_TODOS'])) {$bl5_unive_todos='N';}else{$bl5_unive_todos=$_REQUEST['BL5_UNIVE_TODOS'];}
			if (empty($_REQUEST['BL5_UNIPREF'])) {$bl5_unipref='N';}else{$bl5_unipref=$_REQUEST['BL5_UNIPREF'];}

			if (!empty($_REQUEST['BL5_UNIVE_ORIGEM_V'])){
				$bl5_unive_origem_ref = "V";
			}else if(!empty($_REQUEST['BL5_UNIVE_ORIGEM_O'])){
				$bl5_unive_origem_ref = "O";
			}else if(!empty($_REQUEST['BL5_UNIVE_ORIGEM_C'])){
				$bl5_unive_origem_ref = "C";
			}else{
				$bl5_unive_origem_ref = 'N';
			}
						
			//array - lojas
			if (isset($_POST['BL5_COD_UNIVE'])){
				$Arr_BL5_COD_UNIVE = $_POST['BL5_COD_UNIVE'];
				//print_r($Arr_BL5_COD_UNIVEO);			 
			   for ($i=0;$i<count($Arr_BL5_COD_UNIVE);$i++) 
			   { 
				$bl5_cod_unive = $bl5_cod_unive.$Arr_BL5_COD_UNIVE[$i].";";
			   } 			   
			   $bl5_cod_unive =  rtrim($bl5_cod_unive,';');				
			}else{$bl5_cod_unive = "0";}

			$cod_univend_master = fnLimpaCampo($_REQUEST['COD_UNIVEND_MASTER']);

			if($bl5_cod_unive != "0"){
				$bl5_cod_unive = $bl5_cod_unive.";".$cod_univend_master;
			}else{
				if($cod_univend_master != ""){
					$bl5_cod_unive = $cod_univend_master;
				}
			}

			$bl5_cod_unive = ltrim(rtrim($bl5_cod_unive,';'),';');			

			if (empty($_REQUEST['BL6_ENGAJA_1'])) {$bl6_engaja_1='N';}else{$bl6_engaja_1=$_REQUEST['BL6_ENGAJA_1'];}
			if (empty($_REQUEST['BL6_ENGAJA_2'])) {$bl6_engaja_2='N';}else{$bl6_engaja_2=$_REQUEST['BL6_ENGAJA_2'];}
			if (empty($_REQUEST['BL6_ENGAJA_3'])) {$bl6_engaja_3='N';}else{$bl6_engaja_3=$_REQUEST['BL6_ENGAJA_3'];}
			if (empty($_REQUEST['BL6_ENGAJA_4'])) {$bl6_engaja_4='N';}else{$bl6_engaja_4=$_REQUEST['BL6_ENGAJA_4'];}

		$cod_empresa = fnLimpaCampo($_REQUEST['COD_EMPRESA']);
		$cod_persona = fnLimpaCampo($_REQUEST['COD_PERSONA']);
		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		$procCalc = "T";
		
		//fnEscreve($idadeIni);
		//fnMostraForm();
	}
		
		$opcao = "S";

		$bl5_unive_origem = $bl5_unive_origem_ref;

		$sqlPersonas = "CALL SP_BUSCA_PERSONA_MASTER(
										'".$cod_persona."', 
										'".$bl1_masculino."', 
										'".$bl1_feminino."', 				
										'".$idadeIni."',										
										'".$idadeFim."',
										'".$bl1_endereco."',
										'".$bl1_celular."',
										'".$bl1_email."',
										'".$bl1_telefone."',
										'".$bl1_aniversario."',
										'".$bl1_operaprofi."',
										'".$bl1_profissoes."',
										
										'".$bl1_log_email."',
										'".$bl1_log_sms."',
										'".$bl1_log_telemark."',
										'".$bl1_log_whatsapp."',
										'".$bl1_log_push."',

										'".$cod_empresa."',
										'".$bl1_juridico."',
										".fnDataSqlNull($bl3_cadastros_ini).",
										".fnDataSqlNull($bl3_cadastros_fim).",
										".fnDataSqlNull($bl3_compras_ini).",
										".fnDataSqlNull($bl3_compras_fim).",
										".fnDataSqlNull($bl3_ucompras_ini).",
										".fnDataSqlNull($bl3_ucompras_fim).",
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
										'".$bl5_cod_unive."',
										'".$bl5_unive_origem."',
										'".$bl5_unive_todos."',
										'".$bl5_unipref."',
										'".fnValorSql($bl3_qtd_retorno_ini)."',
										'".fnValorSql($bl3_qtd_retorno_fim)."',										
										'".$bl6_engaja_1."',
										'".$bl6_engaja_2."',
										'".$bl6_engaja_3."',
										'".$bl6_engaja_4."',
										'".$opcao."',
										'S'
										) ";
										
	//fnEscreve($sqlPersonas);
	
	$sqlPersonasquery = mysqli_query(connTemp($cod_empresa,''),$sqlPersonas) or die(mysqli_error());
	//fnTestesql(connTemp($cod_empresa,''),$sqlPersonas) or die(mysqli_error());
	$qrCalcRegra = mysqli_fetch_assoc($sqlPersonasquery);
        
	//$sqlPersonasquery= mysqli_fetch_row($sqlPersonasquery);
	//fnEscreve($sqlPersonasquery[0]);
	$totalIni = $qrCalcRegra['QTD_TOTCLI'];

?>

<script>
//console.log('<?php echo $sqlPersonas; ?>');
</script>
	
	<div class="widget-int num-count" id="div_Total" style="text-align: center; font-size: 40px; padding-top: 10px;"><?php echo number_format ($totalIni,0,",","."); ?></div>

	