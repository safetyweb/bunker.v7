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
		
		//aniversario
		if(isset($_POST['BL1_ANIVERSARIO'])){		
			$bl1_aniversario = implode(',', $_POST['BL1_ANIVERSARIO']);	
		}else{$bl1_aniversario=0;}
		
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
			
			$bl4_credito_min = fnLimpaCampo($_REQUEST['BL4_CREDITO_MIN']);
			$bl4_credito_max = fnLimpaCampo($_REQUEST['BL4_CREDITO_MAX']);
			
			$bl4_tip_resgate = fnLimpaCampo($_REQUEST['BL4_TIP_RESGATE']);
			$bl4_qtd_resgate = fnLimpaCampo($_REQUEST['BL4_QTD_RESGATE']);
			
			$bl4_qtd_avencer = fnLimpaCampo($_REQUEST['BL4_QTD_AVENCER']);
			$bl4_tip_avencer = fnLimpaCampo($_REQUEST['BL4_TIP_AVENCER']);
			
			$bl4_tip_saldo = fnLimpaCampo($_REQUEST['BL4_TIP_SALDO']);
			$bl4_val_saldo = fnLimpaCampo($_REQUEST['BL4_VAL_SALDO']);
			
			//BLOCO 5 - GEO
			//array - lojas
			if (isset($_POST['BL5_COD_UNIVE'])){
				$Arr_BL5_COD_UNIVE = $_POST['BL5_COD_UNIVE'];
				//print_r($Arr_BL5_COD_UNIVEO);			 
			   for ($i=0;$i<count($Arr_BL5_COD_UNIVE);$i++) 
			   { 
				$bl5_cod_unive = $bl5_cod_unive.$Arr_BL5_COD_UNIVE[$i].";";
			   } 			   
			   $bl5_cod_unive = substr($bl5_cod_unive,0,-1);				
			}else{$bl5_cod_unive = "0";}			
			
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
		$sqlPersonas = "CALL SP_BUSCA_PERSONA_MASTER_TESTE (
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
										'".fnValorSql($bl4_credito_min)."',
										'".fnValorSql($bl4_credito_max)."',
										'".$bl4_tip_resgate."',
										'".fnValorSql($bl4_qtd_resgate)."',
										'".fnValorSql($bl4_qtd_avencer)."',
										'".$bl4_tip_avencer."',
										'".$bl4_tip_saldo."',
										'".fnValorSql($bl4_val_saldo)."',
										'".$bl5_cod_unive."',
										'".fnValorSql($bl3_qtd_retorno_ini)."',
										'".fnValorSql($bl3_qtd_retorno_fim)."',
										'".$opcao."'
										) ";
	
	//fnEscreve("<h5>".$sqlPersonas."</h5>");
	
	fnEscreve("<h5>".$sqlPersonas."</h5>");
	
	$sqlPersonasquery = mysqli_query(connTemp($cod_empresa,''),$sqlPersonas) or die(mysqli_error());
	$qrCalcRegra = mysqli_fetch_assoc($sqlPersonasquery);
        
	//$sqlPersonasquery= mysqli_fetch_row($sqlPersonasquery);
	//fnEscreve($sqlPersonasquery[0]);
	$totalIni = $qrCalcRegra['QTD_TOTCLI'];

?>
	
	<div class="widget-int num-count" id="div_Total" style="text-align: center; font-size: 40px; padding-top: 10px;"><?php echo number_format ($totalIni,0,",","."); ?></div>


