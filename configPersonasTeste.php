<?php
	//echo fnDebug('true');
	
	$hashLocal = mt_rand();

	//inicializacao
	$cod_persona = 0;
			
	//se tem pre configuração 
	if (empty($_GET['pre'])) {$log_preconf='N';}else{$log_preconf='S';};
	if ($log_preconf == 'S') {
		$cod_preconf = $_GET['pre'];	
	}
	
	if( $_SERVER['REQUEST_METHOD']=='POST' )
	{
		$request = md5( implode( $_POST ) );
		
		if( isset( $_SESSION['last_request'] ) && $_SESSION['last_request']== $request )
		{
			$msgRetorno = 'Essa página já foi utilizada';
			$msgTipo = 'alert-warning';
		}
		else
		{
			$_SESSION['last_request']  = $request;

			//BLOCO 1 - PERFIL
			//homens
			if (empty($_REQUEST['BL1_MASCULINO'])) {$bl1_masculino='N';}else{$bl1_masculino=$_REQUEST['BL1_MASCULINO'];}
			//mulheres
			if (empty($_REQUEST['BL1_FEMININO'])) {$bl1_feminino='N';}else{$bl1_feminino=$_REQUEST['BL1_FEMININO'];}
			//jurídico
			if (empty($_REQUEST['BL1_JURIDICO'])) {$bl1_juridico='N';}else{$bl1_juridico=$_REQUEST['BL1_JURIDICO'];}
			
			//array - idades
			$bl1_idades = $_REQUEST['BL1_IDADES'][0];
			
			//endereço
			if (empty($_REQUEST['BL1_ENDERECO'])) {$bl1_endereco='N';}else{$bl1_endereco=$_REQUEST['BL1_ENDERECO'];}			
			//celular
			if (empty($_REQUEST['BL1_CELULAR'])) {$bl1_celular='N';}else{$bl1_celular=$_REQUEST['BL1_CELULAR'];}
			//email
			if (empty($_REQUEST['BL1_EMAIL'])) {$bl1_email='N';}else{$bl1_email=$_REQUEST['BL1_EMAIL'];}
			//telefone
			if (empty($_REQUEST['BL1_TELEFONE'])) {$bl1_telefone='N';}else{$bl1_telefone=$_REQUEST['BL1_TELEFONE'];}
			
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

			$bl1_operaprofi = fnLimpaCampoHtml($_REQUEST['BL1_OPERAPROFI']);
			
            //array - profissões
			if (isset($_POST['BL1_PROFISSOES'])){
				$Arr_BL1_PROFISSOES = $_POST['BL1_PROFISSOES'];
				//print_r($Arr_BL1_PROFISSOES);			 
			   for ($i=0;$i<count($Arr_BL1_PROFISSOES);$i++) 
			   { 
				$bl1_profissoes.= $Arr_BL1_PROFISSOES[$i].";";
			   } 			   
			   $bl1_profissoes = substr($bl1_profissoes,0,-1);				
			}else{$bl1_profissoes = "0";}
					
			$cod_empresa = fnLimpaCampo($_REQUEST['COD_EMPRESA']);
			$cod_persona = fnLimpaCampo($_REQUEST['COD_PERSONA']);
			
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
			
			//fnEscreve($bl5_cod_unive);
			
			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
			
			$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
						
			if ($opcao != ''){				
				$sql = "CALL SP_ALTERA_PERSONAREGRA (
				'".$cod_persona."', 
				'".$bl1_masculino."',				 
				'".$bl1_feminino."', 
				'".$bl1_juridico."', 
				'".$bl1_idades."',
				'".$bl1_endereco."',
				'".$bl1_celular."',
				'".$bl1_email."',
				'".$bl1_telefone."',
				'".$bl1_aniversario."',				 
				'".$bl1_operaprofi."',				 
				'".$bl1_profissoes."',
				'".$cod_usucada."',
				'".fnDataSql($bl3_cadastros_ini)."',
				'".fnDataSql($bl3_cadastros_fim)."',
				'".fnDataSql($bl3_compras_ini)."',
				'".fnDataSql($bl3_compras_fim)."',
				'".fnDataSql($bl3_ucompras_ini)."',
				'".fnDataSql($bl3_ucompras_fim)."',
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
				'".fnValorSql($bl3_qtd_retorno_fim)."'
				) ";
				
				//echo $sql;
				mysqli_query(connTemp($cod_empresa,""),trim($sql)) or die(mysqli_error());				
				
				//mensagem de retorno
				switch ($opcao)
				{
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
	
	//busca dados da url	
	if (!empty(fnLimpacampo(fnDecode($_GET['id'])))){
		//busca dados da empresa
		$cod_empresa = fnDecode($_GET['id']);	
		$cod_persona = fnDecode($_GET['idx']);
		
		$sql = "SELECT COD_EMPRESA, NOM_EMPRESA, NOM_FANTASI,
		(select des_persona from persona where cod_persona = '".$cod_persona."') as DES_PERSONA,	
		(select LOG_BLOQUEA from persona where cod_persona = '".$cod_persona."') as LOG_BLOQUEA	
		FROM ".$connAdm->DB.".empresas where COD_EMPRESA = '".$cod_empresa."' 		
		";
		
		//fnEscreve($sql);

		$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
		
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
		$des_persona = $qrBuscaEmpresa['DES_PERSONA'];
		$log_bloquea = $qrBuscaEmpresa['LOG_BLOQUEA'];
	
		//bloqueia edição das personas
		if ($log_bloquea == "S"){
			$bloqueiaAlt = "disabled ";			
		} else {
			$bloqueiaAlt = " ";
		}
		
		//busca dados da persona (regras)
		$sql = "SELECT * FROM PERSONAREGRA where COD_PERSONA = '".$cod_persona."' ";
		//fnEscreve($sql);
		
		$arrayQuery = mysqli_query(connTemp($cod_empresa,""),$sql) or die(mysqli_error());
		$qrBuscaRegra = mysqli_fetch_assoc($arrayQuery);
		//echo '<pre>';
		//print_r ($qrBuscaRegra);
		//echo '</pre>';
		//echo ">_ ".empty($qrBuscaRegra);
		
		if (empty($qrBuscaRegra) != 1){
			//fnEscreve("banco de dados");
			
			//bloco perfil
			$bl1_masculino = $qrBuscaRegra['BL1_MASCULINO'];
			if ($bl1_masculino == "S") {$check_masculino = "checked";} else{$check_masculino = "";}
			$bl1_feminino = $qrBuscaRegra['BL1_FEMININO'];
			if ($bl1_feminino == "S") {$check_feminino = "checked";} else{$check_feminino = "";}
			$bl1_juridico = $qrBuscaRegra['BL1_JURIDICO'];
			if ($bl1_juridico == "S") {$check_juridico = "checked";} else{$check_juridico = "";}
			
			$bl1_idades = explode(';', $qrBuscaRegra['BL1_IDADES']);
			
			$bl1_endereco = $qrBuscaRegra['BL1_ENDERECO'];
			if ($bl1_endereco == "S") {$check_endereco = "checked";} else{$check_endereco = "";}			
			$bl1_celular = $qrBuscaRegra['BL1_CELULAR'];
			if ($bl1_celular == "S") {$check_celular = "checked";} else{$check_celular = "";}
			$bl1_email = $qrBuscaRegra['BL1_EMAIL'];
			if ($bl1_email == "S") {$check_email = "checked";} else{$check_email = "";}
			$bl1_telefone = $qrBuscaRegra['BL1_TELEFONE'];
			if ($bl1_telefone == "S") {$check_telefone = "checked";} else{$check_telefone = "";}
			
			$idadeIni = $bl1_idades[0];
			$idadeFim = $bl1_idades[1];			
			$bl1_aniversario = $qrBuscaRegra['BL1_ANIVERSARIO'];
			$bl1_operaprofi = $qrBuscaRegra['BL1_OPERAPROFI'];
			$bl1_profissoes = $qrBuscaRegra['BL1_PROFISSOES'];

			
			//BLOCO 3 - FREQUÊNCIA	
			$bl3_compras_ini = $qrBuscaRegra['BL3_COMPRAS_INI'];
			if (!empty($bl3_compras_ini)){$bl3_compras_ini = fnDateRetorno($bl3_compras_ini);} else {$bl3_compras_ini = "";}			
			$bl3_compras_fim = $qrBuscaRegra['BL3_COMPRAS_FIM'];
			if (!empty($bl3_compras_fim)){$bl3_compras_fim = fnDateRetorno($bl3_compras_fim);} else {$bl3_compras_fim = "";}
			$bl3_cadastros_ini = $qrBuscaRegra['BL3_CADASTROS_INI'];
			if (!empty($bl3_cadastros_ini)){$bl3_cadastros_ini = fnDateRetorno($bl3_cadastros_ini);} else {$bl3_cadastros_ini = "";}
			$bl3_cadastros_fim = $qrBuscaRegra['BL3_CADASTROS_FIM'];
			if (!empty($bl3_cadastros_fim)){$bl3_cadastros_fim = fnDateRetorno($bl3_cadastros_fim);} else {$bl3_cadastros_fim = "";}
			$bl3_ucompras_ini = $qrBuscaRegra['BL3_UCOMPRAS_INI'];
			if (!empty($bl3_ucompras_ini)){$bl3_ucompras_ini = fnDateRetorno($bl3_ucompras_ini);} else {$bl3_ucompras_ini = "";}
			$bl3_ucompras_fim = $qrBuscaRegra['BL3_UCOMPRAS_FIM'];
			if (!empty($bl3_ucompras_fim)){$bl3_ucompras_fim = fnDateRetorno($bl3_ucompras_fim);} else {$bl3_ucompras_fim = "";}
			
			$bl3_qtd_retorno_ini = $qrBuscaRegra['BL3_QTD_RETORNO_INI'];
			if (!empty($bl3_qtd_retorno_ini)){$bl3_qtd_retorno_ini = $bl3_qtd_retorno_ini;} else {$bl3_qtd_retorno_ini = "";}
			
			$bl3_qtd_retorno_fim = $qrBuscaRegra['BL3_QTD_RETORNO_FIM'];
			if (!empty($bl3_qtd_retorno_fim)){$bl3_qtd_retorno_fim = $bl3_qtd_retorno_fim;} else {$bl3_qtd_retorno_fim = "";}
		
			$bl3_log_resgate = $qrBuscaRegra['BL3_LOG_RESGATE'];
			if ($bl3_log_resgate == "S") {$check_bl3_log_resgate = "checked";} else{$check_bl3_log_resgate = "";}
			$bl3_tip_resgate = $qrBuscaRegra['BL3_TIP_RESGATE'];
			
			$bl3_qtd_resgate = $qrBuscaRegra['BL3_QTD_RESGATE'];
			if (!empty($bl3_qtd_resgate)){$bl3_qtd_resgate = $bl3_qtd_resgate;} else {$bl3_qtd_resgate = "";}
			
			//BLOCO 4 - VALOR
			$bl4_compra_min = $qrBuscaRegra['BL4_COMPRA_MIN'];
			if ($bl4_compra_min == 0.00){$bl4_compra_min = "";}			
			$bl4_compra_max = $qrBuscaRegra['BL4_COMPRA_MAX'];
			if ($bl4_compra_max == 0.00){$bl4_compra_max = "";}			
			$bl4_valortm_min = $qrBuscaRegra['BL4_VALORTM_MIN'];
			if ($bl4_valortm_min == 0.00){$bl4_valortm_min = "";}			
			$bl4_valortm_max = $qrBuscaRegra['BL4_VALORTM_MAX'];
			if ($bl4_valortm_max == 0.00){$bl4_valortm_max = "";}			
			$bl4_credito_min = $qrBuscaRegra['BL4_CREDITO_MIN'];
			if ($bl4_credito_min == 0.00){$bl4_credito_min = "";}			
			$bl4_credito_max = $qrBuscaRegra['BL4_CREDITO_MAX'];
			if ($bl4_credito_max == 0.00){$bl4_credito_max = "";}
			$bl4_tip_resgate = $qrBuscaRegra['BL4_TIP_RESGATE'];
			$bl4_qtd_resgate = $qrBuscaRegra['BL4_QTD_RESGATE'];
			if ($bl4_qtd_resgate == 0.00){$bl4_qtd_resgate = "";}
			$bl4_qtd_avencer = $qrBuscaRegra['BL4_QTD_AVENCER'];
			if ($bl4_qtd_avencer == 0.00){$bl4_qtd_avencer = "";}
			$bl4_tip_avencer = $qrBuscaRegra['BL4_TIP_AVENCER'];
			$bl4_tip_saldo = $qrBuscaRegra['BL4_TIP_SALDO'];
			$bl4_val_saldo = $qrBuscaRegra['BL4_VAL_SALDO'];
			if ($bl4_val_saldo == 0.00){$bl4_val_saldo = "";}
			
			//BLOCO 5 - GEO
			$bl5_cod_unive = $qrBuscaRegra['BL5_COD_UNIVE'];
				
			//liberação das abas
			$abaPersona	= "S";
			$abaVantagem = "S";
			$abaRegras = "N";
			$abaComunica = "N";
			$abaAtivacao = "N";
			$abaResultado = "N";

			$abaPersonaComp = "completed";
			$abaVantagemComp = "";
			$abaRegrasComp = "";
			$abaComunicaComp = "";
			$abaAtivacaoComp = "";
			$abaResultadoComp = "";
							
			//tipo de cálculo da proc - BANCO DE DADOS
			//$procCalc = "B";
			
		}else{
		//fnEscreve('else');
				      	
			//se tem pre configuração
			if ($log_preconf == 'S') {
				//fnEscreve("bloco pre conf");
				
				$cod_preconf = fnDecode($_GET['pre']);	
				$sql = "SELECT * FROM segmarkaitem where COD_SEGITEM = '".$cod_preconf."' ";
				//fnEscreve($sql);
				$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
				$qrBuscaPreConf = mysqli_fetch_assoc($arrayQuery);
				//fnEscreve($sql);				
				//bloco perfil
				$bl1_masculino = $qrBuscaPreConf['BL1_MASCULINO'];
				if ($bl1_masculino == "S") {$check_masculino = "checked";} else{$check_masculino = "";}
				$bl1_feminino = $qrBuscaPreConf['BL1_FEMININO'];
				if ($bl1_feminino == "S") {$check_feminino = "checked";} else{$check_feminino = "";}
				$bl1_juridico = $qrBuscaPreConf['BL1_JURIDICO'];
				if ($bl1_juridico == "S") {$check_juridico = "checked";} else{$check_juridico = "";}
					
				$check_endereco = "";
				$check_celular = "";
				$check_email = "";
				$check_telefone = "";
				
				$idadeIni = $qrBuscaPreConf['BL1_IDADES_INI'];
				$idadeFim = $qrBuscaPreConf['BL1_IDADES_FIM'];			
				$bl1_aniversario = $qrBuscaPreConf['BL1_ANIVERSARIO'];
				
				$bl1_operaprofi = $qrBuscaPreConf['BL1_OPERAPROFI'];
				$bl1_profissoes = $qrBuscaPreConf['BL1_PROFISSOES'];
				
				//BLOCO 3 - FREQUÊNCIA	
				$bl3_compras_ini = "";
				$bl3_compras_fim = "";				
				$bl3_cadastros_ini = "";
				$bl3_cadastros_fim = "";				
				$bl3_ucompras_ini = "";
				$bl3_ucompras_fim = "";
				
				$bl3_qtd_retorno_ini = "";
				$bl3_qtd_retorno_fim = "";
				
				$bl3_log_resgate='N';
				$bl3_tip_resgate = "";
				$bl3_qtd_resgate = "";	

				//BLOCO 4 - VALOR
				$bl4_compra_min = "";
				$bl4_compra_max = "";				
				$bl4_valortm_min = "";
				$bl4_valortm_max = "";
				$bl4_credito_min = "";
				$bl4_credito_max = "";
				$bl4_tip_resgate = "";
				$bl4_qtd_resgate = "";
				$bl4_qtd_avencer = "";
				$bl4_tip_avencer = "";
				$bl4_tip_saldo = "";
				$bl4_val_saldo = "";
				
				//BLOCO 5 - GEO
				$bl5_cod_unive = "0";
				
				//liberação das abas
				$abaPersona	= "S";
				$abaVantagem = "N";
				$abaRegras = "N";
				$abaComunica = "N";
				$abaAtivacao = "N";
				$abaResultado = "N";
				
				$abaPersonaComp = "";
				$abaVantagemComp = "";
				$abaRegrasComp = "";
				$abaComunicaComp = "";
				$abaAtivacaoComp = "";
				$abaResultadoComp = "";
				
				//tipo de cálculo da proc - PRE CONFIGURAÇÃO
				//$procCalc = "P";
							
			}else{
				//sem opções e sem gravar - traz default tudo
				//fnEscreve("default");
				
				$check_masculino = "checked";
				$check_feminino = "checked";
				$bl1_masculino = "S";
				$bl1_feminino = "S";
				$bl1_juridico = "N";
				$idadeIni = 0;
				$idadeFim = 110;
				$check_endereco = "";
				$check_celular = "";
				$check_email = "";
				$check_telefone = 
				$bl1_aniversario = 0;
				$bl1_operaprofi = "";
				$bl1_profissoes = 0;
				$procCalc = "P";
					
				//BLOCO 3 - FREQUÊNCIA	
				$bl3_compras_ini = "";
				$bl3_compras_fim = "";				
				$bl3_cadastros_ini = "";
				$bl3_cadastros_fim = "";				
				$bl3_ucompras_ini = "";
				$bl3_ucompras_fim = "";

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
				$bl4_credito_min = "";
				$bl4_credito_max = "";
				$bl4_tip_resgate = "";
				$bl4_qtd_resgate = "";
				$bl4_qtd_avencer = "";
				$bl4_tip_avencer = "";
				$bl4_tip_saldo = "";
				$bl4_val_saldo = "";
				
				//BLOCO 5 - GEO
				$bl5_cod_unive = "0";
				
			}		
			
		}
        
		if($idadeFim==''){$idadeFim='150';}
		 
		//Busca total inicial 
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
                                                        '".fnLimpaCampoZero($bl3_qtd_resgate)."',
                                                        '".fnLimpaCampoZero($bl4_compra_min)."',
                                                        '".fnLimpaCampoZero($bl4_compra_max)."',
                                                        '".fnLimpaCampoZero($bl4_valortm_min)."',
                                                        '".fnLimpaCampoZero($bl4_valortm_max)."',
                                                        '".fnLimpaCampoZero($bl4_credito_min)."',
                                                        '".fnLimpaCampoZero($bl4_credito_max)."',
                                                        '".fnLimpaCampo($bl4_tip_resgate)."',
                                                        '".fnLimpaCampoZero($bl4_qtd_resgate)."',
                                                        '".fnLimpaCampoZero($bl4_qtd_avencer)."',
                                                        '".fnLimpaCampo($bl4_tip_avencer)."',
                                                        '".fnLimpaCampo($bl4_tip_saldo)."',
                                                        '".fnLimpaCampoZero($bl4_val_saldo)."',
                                                        '".$bl5_cod_unive."',
                                                        '".fnLimpaCampoZero($bl3_qtd_retorno_ini)."',
                                                        '".fnLimpaCampoZero($bl3_qtd_retorno_fim)."',
                                                        '".$opcao."'
                                                        ) ";
		
		fnEscreve("oi");
		fnEscreve($sqlPersonas);
		$sqlPersonasquery = mysqli_query(connTemp($cod_empresa,''),$sqlPersonas) or die(mysqli_error());
		//fnTestesql(connTemp($cod_empresa,''),$sqlPersonas);
		$qrCalcRegra = mysqli_fetch_assoc($sqlPersonasquery);
                                        
		//atualiza base de personas
		$sql2 = "CALL SP_ALTERA_PERSONACLASSIFICA_TESTE (
		 '".$cod_persona."', 
		 '".$cod_empresa."' 
		) ";		
		fnEscreve($sql2);
		
		//fnTestesql(connTemp($cod_empresa,''),$sql2);
		mysqli_query(connTemp($cod_empresa,''),trim($sql2)) or die(mysqli_error());	
		
	    //$sqlPersonasquery= mysqli_fetch_row($sqlPersonasquery);
	    //fnEscreve($sqlPersonasquery[0]);
		$totalIni = $qrCalcRegra['QTD_TOTCLI'];
		$totalGeral = $qrCalcRegra['TOT_GERAL'];
		$totalHom = $qrCalcRegra['QTD_MASCULINO'];
		$totalFem = $qrCalcRegra['QTD_FEMININO'];		
		$totalPJ = $qrCalcRegra['QTD_JURIDICO'];
		
		$tot_masculino = $qrCalcRegra['TOT_MASCULINO'];		
		$tot_feminino = $qrCalcRegra['TOT_FEMININO'];		

		$valor1 = $qrCalcRegra['IDADE1'];
		$valor2 = $qrCalcRegra['IDADE2'];
		$valor3 = $qrCalcRegra['IDADE3'];
		$valor4 = $qrCalcRegra['IDADE4'];
		$valor5 = $qrCalcRegra['IDADE5'];
		$valor6 = $qrCalcRegra['IDADE6'];
		$valor7 = $qrCalcRegra['IDADE7'];	

		//fnEscreve($bl1_masculino);
		//fnEscreve($bl1_feminino);
		
 												
	}else {
		$cod_empresa = 0;		
		//fnEscreve($cod_empresa);
	}
	
	//calcula totalizador do bloco
	$bl1_total = 0;
	if ($bl1_masculino == "S") {$bl1_total = $bl1_total + 1;}
	if ($bl1_feminino == "S") {$bl1_total = $bl1_total + 1;}
	if ($bl1_juridico == "S") {$bl1_total = $bl1_total + 1;}
	if ($bl1_idades != "0") {$bl1_total = $bl1_total + 1;}
	if ($bl1_aniversario != "0") {$bl1_total = $bl1_total + 1;}
	if ($bl1_operaprofi != "") {$bl1_total = $bl1_total + 1;}
	if ($bl1_profissoes != "0") {$bl1_total = $bl1_total + 1;}
	
	$bl2_total = 0;	
	$sql = "SELECT count(COD_PERPROD) as TEM_PRODUTO
		FROM personas_produtos where COD_PERSONA = $cod_persona ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
	$qrListaTemProdutos = mysqli_fetch_assoc($arrayQuery);
	$bl2_total = $qrListaTemProdutos['TEM_PRODUTO'];
	//fnEscreve($tem_produto);
		
	$bl3_total = 0;	
	if (!empty($bl3_compras_ini)){$bl3_total = $bl3_total + 1;}
	if (!empty($bl3_compras_fim)){$bl3_total = $bl3_total + 1;}
	if (!empty($bl3_cadastros_ini)){$bl3_total = $bl3_total + 1;}
	if (!empty($bl3_cadastros_fim)){$bl3_total = $bl3_total + 1;}
	if (!empty($bl3_ucompras_ini)){$bl3_total = $bl3_total + 1;}
	if (!empty($bl3_ucompras_fim)){$bl3_total = $bl3_total + 1;}
	if (!empty($bl3_qtd_retorno_ini)){$bl3_total = $bl3_total + 1;}
	if (!empty($bl3_qtd_retorno_fim)){$bl3_total = $bl3_total + 1;}
	if ($bl3_log_resgate == "S") {$bl3_total = $bl3_total + 1;}
	if (!empty($bl3_tip_resgate)){$bl3_total = $bl3_total + 1;}
	if (!empty($bl3_qtd_resgate)){$bl3_total = $bl3_total + 1;}
		
	$bl4_total = 0;
	if (!empty($bl4_compra_min)){$bl4_total = $bl4_total + 1;}
	if (!empty($bl4_compra_max)){$bl4_total = $bl4_total + 1;}
	if (!empty($bl4_valortm_min)){$bl4_total = $bl4_total + 1;}
	if (!empty($bl4_valortm_max)){$bl4_total = $bl4_total + 1;}
	if (!empty($bl4_credito_min)){$bl4_total = $bl4_total + 1;}
	if (!empty($bl4_credito_max)){$bl4_total = $bl4_total + 1;}
	if (!empty($bl4_tip_resgate)){$bl4_total = $bl4_total + 1;}
	if (!empty($bl4_qtd_resgate)){$bl4_total = $bl4_total + 1;}
	if (!empty($bl4_qtd_avencer)){$bl4_total = $bl4_total + 1;}
	if (!empty($bl4_tip_avencer)){$bl4_total = $bl4_total + 1;}
	if (!empty($bl4_tip_saldo)){$bl4_total = $bl4_total + 1;}
	if (!empty($bl4_val_saldo)){$bl4_total = $bl4_total + 1;}
	
	$bl5_total = 0;
	if (!empty($bl5_cod_unive)){$bl5_total = $bl5_total + 1;}
	
	//fnMostraForm();
	//fnEscreve($bl4_qtd_avencer);
	//fnEscreve($bl4_tip_avencer);
	//fnEscreve($bl1_operaprofi);
	//fnEscreve($bl1_profissoes);
	//fnEscreve($abaPersona);       
	//fnEscreve($bl3_cadastros_ini);
	//fnEscreve($bl1_idades);       
        
?>

<link rel="stylesheet" href="css/ion.rangeSlider.css" />
<link rel="stylesheet" href="css/ion.rangeSlider.skinHTML5.css" />


<style>

body{
    font-family: 'Roboto', sans-serif;
}

.scrollPersona {
	position: fixed;
	top: 15%;
	right: 0;
	-webkit-transform: translateX(-50%) translateY(-50%);
	-moz-transform: translateX(-50%) translateY(-50%);
	transform: translateX(-50%) translateY(-50%);
	letter-spacing: 1px;
	font-weight: 700;
	font-size: 2em;
	line-height: 2;
	width: 10em;
	text-align: center;
	height: 70px;
	opacity: 0.7;
	z-index: 5;
}

#blocker
{
    display:none; 
	position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: .8;
    background-color: #fff;
    z-index: 1000;
}
    
#blocker div
{
	position: absolute;
	top: 30%;
	left: 48%;
	width: 200px;
	height: 2em;
	margin: -1em 0 0 -2.5em;
	color: #000;
	font-weight: bold;
}

.notify-badge{
    position: absolute;
    right:36%;
    top:10px;
    background:#18bc9c;
    border-radius: 30px 30px 30px 30px;
    text-align: center;
    color:white;
    font-size:11px;
}

.notify-badge span{
	margin: 0 auto;
}

.pos{
	left: 105;
	top:-10;
	background: #ffbf00;
	font-size: 9px;
	padding-top: 7px;
}

.posHidden{
	display: none;
}

</style>

									
   <div id="blocker">
       <div style="text-align: center;"><img src="images/loading2.gif"><br/> Aguarde. Processando... ;-)</div>
   </div>


		
					<div class="push30"></div> 
					
					<div class="row">				
					
						<div class="col-md12 margin-bottom-30">
							<!-- Portlet -->
							<div class="portlet portlet-bordered">
								<div class="portlet-title">
									<div class="caption">
										<i class="glyphicon glyphicon-calendar"></i>
										<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?> / <?php echo $des_persona; ?> </span>
									</div>
									
									<?php $formBack = "1048"; include "atalhosPortlet.php";?>	

								</div>
								
								<div class="push10"></div> 
								
								<div class="portlet-body">
									
									<?php if ($msgRetorno <> '') { ?>	
									<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
									<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									 <?php echo $msgRetorno; ?>
									</div>
									<?php } ?>

									<?php if ($log_bloquea == "S"){ ?>									
									<div class="alert alert-danger" role="alert">
									   Persona  <strong>bloqueada</strong> para edição.
									</div>
									<?php } ?>
									
									<div  class="col-sm-12"	style="padding-left: 0;">

										<div class="col-xs-2" style="padding-left: 0;">
										<a class="btn btn-info btn-block addBox" data-url="action.php?mod=<?php echo fnEncode(1038)?>&id=<?php echo fnEncode($cod_empresa)?>&idx=<?php echo fnEncode($cod_persona)?>&pop=true" data-title="Persona / <?php echo $des_persona; ?>"><i class="fa fa-cogs fa fa-white hidden-xs"></i>
												Editar Persona
										</a>
										
										</div>
										
									</div>
									
									<div class="push20"></div> 
									
									<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
	
										<!-- <h1><?php echo $cmdPage; ?></h1> -->
	
										<div  class="col-sm-12"	style="padding-left: 0;">

											<div class="col-xs-2" style="padding-left: 0;"> <!-- required for floating -->
											  <!-- Nav tabs -->
											  <ul class="vTab nav nav-tabs tabs-left text-center">
												
												<li class="active vTab">				
													<a href="#perfil" data-toggle="tab">
													<?php if ($bl1_total>0) { ?>
													<div class="notify-badge text-center"><span><?php echo $bl1_total; ?></span></div>
													<?php } ?>
													<i class="fal fa-user-edit fa-2x" style="margin: 10px 0 2px 0"></i>
													<h5 class="hidden-xs" style="margin: 3px 0 0 0">Perfil</h5>												
													</a>
												</li>
												
												<li class="vTab">				
													<a href="#produtos" data-toggle="tab">
													<?php if ($bl2_total>0) { ?>
													<div class="notify-badge text-center"><span><?php echo $bl2_total; ?></span></div>
													<?php } ?>
													<i class="fal fa-cubes fa-2x" style="margin: 10px 0 2px 0"></i>
													<h5 class="hidden-xs" style="margin: 3px 0 0 0">Produtos</h4>												
													</a>
												</li>
												
												<li class="vTab">				
													<a href="#frequencia" data-toggle="tab">
													<?php if ($bl3_total>0) { ?>
													<div class="notify-badge text-center"><span><?php echo $bl3_total; ?></span></div>
													<?php } ?>
													<i class="fal fa-hourglass-end fa-2x" style="margin: 10px 0 2px 0"></i>
													<h5 class="hidden-xs" style="margin: 3px 0 0 0">Frequência e <br> Recência</h4>												
													</a>
												</li>
												
												<li class="vTab">				
													<a href="#valor" data-toggle="tab">
													<?php if ($bl4_total>0) { ?>
													<div class="notify-badge text-center"><span><?php echo $bl4_total; ?></span></div>
													<?php } ?>
													<i class="fal fa-money-check-alt fa-2x" style="margin: 10px 0 2px 0"></i>
													<h5 class="hidden-xs" style="margin: 3px 0 0 0">Valor</h4>												
													</a>
												</li>
												
												<li class="vTab">				
													<a href="#geolocalizacao" data-toggle="tab">
													<?php if ($bl5_total>0) { ?>
													<div class="notify-badge text-center"><span><?php echo $bl5_total; ?></span></div>
													<?php } ?>
													<i class="fal fa-map-marker-alt fa-2x" style="margin: 10px 0 2px 0"></i>
													<h5 class="hidden-xs" style="margin: 3px 0 0 0">Geolocalização</h4>												
													</a>
												</li>
												
												<li class="vTab disabled">				
													<a href="#engajamento" data-toggle="tab">
													<i class="fal fa-thumbs-up fa-2x" style="margin: 10px 0 2px 0"></i>
													<h5 class="hidden-xs" style="margin: 3px 0 0 0">Engajamento</h4>												
													</a>
												</li>
												
											  </ul>
											</div>
											
											
											<?php                                             
											//CALCULOS INICIAIS
											//meses do aniversario                                              
                                            $arrayNiver = explode(";", $bl1_aniversario);
											?>											

											<div class="col-xs-10">
											  <!-- conteudo abas -->
											  <div class="tab-content">
												<!-- aba perfil-->
												<div class="tab-pane active" id="perfil">
													<h4 style="margin: 0 0 5px 0;">Configuração de Perfil</h4>
													<small style="font-size: 12px;">Quais as características do público que sua campanha quer atingir?</small>

													<div class="push20"></div> 

													<div class="row">
													
														<h5 style="margin: 0 0 5px 15px;">Total geral de pessoas cadastradas</h5>
														<div class="push20"></div> 
													
														<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
															
															<div class="widget widget-default widget-item-icon">
																<div class="widget-item-left">
																	<span class="fa fa-users text-success"></span>
																</div>                             
																<div class="widget-data">
																	<div class="widget-int"><?php echo number_format ($totalGeral,0,",","."); ?></div>
																	<div class="widget-title">100%</div>
																	<div class="widget-subtitle">													
																	<div class="push20"></div>
																	<div class="push5"></div>
																	</div>
																</div>
															</div>  
															
														</div>	
													  
														<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">

															<div class="widget widget-default widget-item-icon">
																<div class="widget-item-left">
																	<span class="fa fa-male text-success"></span>
																</div>                             
																<div class="widget-data">
																	<div class="widget-int"><?php echo number_format ($tot_masculino,0,",","."); ?></div>
																	<div class="widget-title"><?php fnCalculaporcento($tot_masculino,$totalGeral); ?>% </div>
																	<div class="widget-subtitle">													
																	<div class="push20"></div>
																	<div class="push5"></div>
																	</div>
																	
																</div>
															</div> 
															
														</div>	
													  
														<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">

															<div class="widget widget-default widget-item-icon">
																<div class="widget-item-left">
																	<span class="fa fa-female text-success"></span>
																</div>                             
																<div class="widget-data">
																	<div class="widget-int"><?php echo number_format ($tot_feminino,0,",","."); ?></div>
																	<div class="widget-title"> <?php fnCalculaporcento($tot_feminino,$totalGeral); ?>% </div>
																	<div class="widget-subtitle">													
																	<div class="push20"></div>
																	<div class="push5"></div>
																	</div>
																	
																</div>
															</div> 
															
														</div>
													  

													</div>
													
													<div class="push20"></div> 
													
													<div class="row">
													
														<h5 style="margin: 0 0 5px 15px;">Seleção da persona</h5>
														<div class="push20"></div> 
													  
														<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">

															<div class="widget widget-default widget-item-icon">
																<div class="widget-item-left">
																	<span class="fa fa-industry"></span>
																</div>                             
																<div class="widget-data">
																	<div class="widget-int"><?php echo number_format ($totalPJ,0,",","."); ?></div>
																	<div class="widget-title"> <?php fnCalculaporcento($totalPJ,$totalGeral); ?>% </div>
																	<div class="widget-subRight">
																	
																		<label class="switch">
																		<input type="checkbox" name="BL1_JURIDICO" id="BL1_JURIDICO" class="switch" value="S" <?php echo $check_juridico; ?>  />
																		<span></span>
																		</label>							
																		<div class="push5"></div> 
																	
																	</div>
																</div>
															</div> 
															
														</div>

														<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">

															<div class="widget widget-default widget-item-icon">
																<div class="widget-item-left">
																	<span class="fa fa-male"></span>
																</div>                             
																<div class="widget-data">
																	<div class="widget-int"><?php echo number_format ($totalHom,0,",","."); ?></div>
																	<div class="widget-title"><?php fnCalculaporcento($totalHom,$totalGeral); ?>% </div>
																	<div class="widget-subRight">
																	
																		<label class="switch">
																		<input type="checkbox" name="BL1_MASCULINO" id="BL1_MASCULINO" class="switch" value="S" <?php echo $check_masculino; ?> />
																		<span></span>
																		</label> 								
																	
																	</div>
																</div>
															</div> 
															
														</div>	
													  
														<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">

															<div class="widget widget-default widget-item-icon">
																<div class="widget-item-left">
																	<span class="fa fa-female"></span>
																</div>                             
																<div class="widget-data">
																	<div class="widget-int"><?php echo number_format ($totalFem,0,",","."); ?></div>
																	<div class="widget-title"> <?php fnCalculaporcento($totalFem,$totalGeral); ?>% </div>
																	<div class="widget-subRight">
																	
																		<label class="switch">
																		<input type="checkbox" name="BL1_FEMININO" id="BL1_FEMININO" class="switch" value="S" <?php echo $check_feminino; ?>  />
																		<span></span>
																		</label>							
																	
																	</div>
																</div>
															</div> 
															
														</div>													  
														
													</div>													
													
													<div class="row">

														<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">

															<div class="widget widget-default widget-item-icon">
																<div class="widget-item-left">
																	<span class="fa fa-child" style="font-size: 30px;"></span><span class="fa fa-male"></span>
																</div>                             
																<div class="widget-data">
																	<div class="widget-title">Idade</div>
																	<div class="widget-subtitle" style="padding: 20px 30px 20px 10px;">
																		
																		<div class="push20"></div>
																		<input type="text" name="BL1_IDADES[]" id="BL1_IDADES" value="" />
																		<div class="push30"></div>
																		
																	</div>
																</div>
															</div> 												  
																										
														</div>
																																								
														<div class="col-lg-6 col-md-6 col-sm-6col-xs-12">

															<div class="widget widget-default widget-item-icon" style="height: 190px;">
																<div class="widget-item-left">
																	<span class="fa fa-address-book"></span>
																</div>                             
																<div class="widget-data">
																	<div class="widget-title">Qualidade de Cadastro</div>
																	<div class="widget-subtitle" style="padding: 10px 10px 0 0;">
																		
																		<div class="push10"></div>
																		
																		<div class="col-lg-6 col-md-6 col-sm-6col-xs-12 text-center">
																	
																			<label class="switch">
																			<input type="checkbox" name="BL1_ENDERECO" id="BL1_ENDERECO" class="switch" value="S" <?php echo $check_endereco; ?> />
																			<span></span>
																			</label>
																			<div class="push10"></div>
																			Com Endereço
																			
																			<div class="push20"></div>
																			
																			<label class="switch">
																			<input type="checkbox" name="BL1_EMAIL" id="BL1_EMAIL" class="switch" value="S" <?php echo $check_email; ?> />
																			<span></span>
																			</label>
																			<div class="push10"></div>
																			Com e-Mail
																			
																		</div>
																		
																		<div class="col-lg-6 col-md-6 col-sm-6col-xs-12 text-center">
																	
																			<label class="switch">
																			<input type="checkbox" name="BL1_CELULAR" id="BL1_CELULAR" class="switch" value="S" <?php echo $check_celular; ?> />
																			<span></span>
																			</label>
																			<div class="push10"></div>
																			Com Celular
																			
																			<div class="push20"></div>
																			
																			<label class="switch">
																			<input type="checkbox" name="BL1_TELEFONE" id="BL1_TELEFONE" class="switch" value="S" <?php echo $check_telefone; ?> />
																			<span></span>
																			</label>
																			<div class="push10"></div>
																			Com Telefone
																			
																		</div>																	

																	</div>
																</div>
															</div> 
															
														</div>
														
													</div>
													
													<div class="row">
																																								
														<div class="col-lg-6 col-md-6 col-sm-6col-xs-12">

															<div class="widget widget-default widget-item-icon" style="height: 175px;">
																<div class="widget-item-left">
																	<span class="fa fa-birthday-cake"></span>
																</div>                             
																<div class="widget-data">
																	<div class="widget-title">Aniversáriantes</div>
																	<div class="widget-subtitle" style="padding: 10px 10px 0 0;">
																			
																		<select data-placeholder="Escolha os aniversariantes" name="BL1_ANIVERSARIO[]" id="BL1_ANIVERSARIO" multiple="multiple" class="chosen-select-deselect" style="width:100%;" tabindex="1">
                                                                            <option value="1" <?php if (in_array("1", $arrayNiver)){ echo "selected";} ?> >Janeiro</option> 
																			<option value="2" <?php if (in_array("2", $arrayNiver)){ echo "selected";} ?> >Fevereiro</option> 
																			<option value="3" <?php if (in_array("3", $arrayNiver)){ echo "selected";} ?> >Março</option> 
																			<option value="4" <?php if (in_array("4", $arrayNiver)){ echo "selected";} ?> >Abril</option> 
																			<option value="5" <?php if (in_array("5", $arrayNiver)){ echo "selected";} ?> >Maio</option> 
																			<option value="6" <?php if (in_array("6", $arrayNiver)){ echo "selected";} ?> >Junho</option> 
																			<option value="7" <?php if (in_array("7", $arrayNiver)){ echo "selected";} ?> >Julho</option> 
																			<option value="8" <?php if (in_array("8", $arrayNiver)){ echo "selected";} ?> >Agosto</option> 
																			<option value="9" <?php if (in_array("9", $arrayNiver)){ echo "selected";} ?> >Setembro</option> 
																			<option value="10" <?php if (in_array("10", $arrayNiver)){ echo "selected";} ?> >Outubro</option> 
																			<option value="11" <?php if (in_array("11", $arrayNiver)){ echo "selected";} ?> >Novembro</option> 
																			<option value="12" <?php if (in_array("12", $arrayNiver)){ echo "selected";} ?> >Dezembro</option> 
																		</select>										

																	</div>
																</div>
															</div> 
															
														</div>													

														<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">

															<div class="widget widget-default widget-item-icon">
																<div class="widget-item-left">
																	<span class="fa fa-briefcase"></span>
																</div>                             
																<div class="widget-data">
																	<div class="widget-title">Profissão</div>
																	<div class="widget-subtitle" style="padding: 20px 30px 20px 10px;">

																		<select data-placeholder="Escolha a forma de uso da profissão" name="BL1_OPERAPROFI" id="BL1_OPERAPROFI" class="chosen-select-deselect" style="width:100%;" tabindex="1">
																			<option value=""></option>					
																			<option value="=" >Profissões iguais a:</option> 
																			<option value="!=" >Profissões diferentes de:</option> 
																		</select>
																		<script>$("#formulario #BL1_OPERAPROFI").val("<?php echo $bl1_operaprofi; ?>").trigger("chosen:updated"); </script>

																		<div class="push20"></div>

																		
																			<select data-placeholder="Escolha as profissões" name="BL1_PROFISSOES[]" id="BL1_PROFISSOES" multiple="multiple" class="chosen-select-deselect" style="width:100%;" tabindex="1">
																			<option value=""></option>					
																			<?php 																	
																				$sql = "select COD_PROFISS, DES_PROFISS from $connAdm->DB.PROFISSOES order by DES_PROFISS ";
																				$arrayQuery = mysqli_query($connAdm->connAdm(),$sql);
																				$arrayProfissoes = explode(";",$bl1_profissoes);
																				
																				while ($qrListaProfi = mysqli_fetch_assoc($arrayQuery))
																				  {
																					if (in_array($qrListaProfi['COD_PROFISS'],$arrayProfissoes)){ 
																						$checado = " selected";
																					}else{
																						$checado = " ";
																					}
																						
																					echo"
																						  <option value='".$qrListaProfi['COD_PROFISS']."' $checado>".$qrListaProfi['DES_PROFISS']."</option> 
																						"; 
																					  }											
																			?>	
																		</select>	

																		
																	</div>
																</div>
															</div> 												  
														
														</div>
														
													</div>
													
													<div class="row">
					
														<div class="push10"></div>
														<hr>	
														<div class="form-group text-right col-lg-12">
												
															  <button type="button" class="btn btn-default limpaPerfil"><i class="far fa-star-half-alt" aria-hidden="true"></i>&nbsp; Limpar Bloco</button>
															  <button type="button" class="btn btn-success atualiza" <?php echo $bloqueiaAlt; ?> ><i class="fa fa-check" aria-hidden="true"></i>&nbsp; Aplicar Filtros</button>
															
														</div>	

														<div class="push30"></div>														
														
													</div>
													
													
													<div class="row">
													
														<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

															<div class="widget widget-default widget-item-icon">
																<div class="widget-item-left">
																	<span class="fa fa-bar-chart"></span>
																</div>                             
																<div class="widget-data">
																	<div class="widget-title">Visão Geral por Idade</div>
																	<div class="widget-subtitle" style="padding: 20px 30px 20px 10px;">
																		<div class="push20"></div>
																		<?php if ($cod_empresa == 69) { ?>
																		<h5>Idade média dos clientes: <b>44 anos</b></h5>
																		<?php } ?>
																		<div class="push20"></div>
																		<canvas id="mybarChart"></canvas>					
																		
																	</div>
																</div>
															</div> 												  
																										
														</div>

													  <div class="push50"></div>
													  
													</div>
												
												</div>
												
												<!-- aba produtos-->
												<div class="tab-pane" id="produtos">
												<h4 style="margin: 0 0 5px 0;">Configuração de Produtos</h4>
												<small style="font-size: 12px;">Quais produtos sua campanha deseja promover?</small>
												
													<?php /////// Bloco Produtos //////// ?>	
													<?php include "personasProdutos.php"; ?>	
												
												</div>
												
												<!-- aba frequencia-->
												<div class="tab-pane" id="frequencia">
												<h4 style="margin: 0 0 5px 0;">Configuração de Frequência e Recência </h4>
												<small style="font-size: 12px;">Qual é a frequencia de compra e/ou interação do público que você deseja atingir?</small>

													<?php /////// Bloco Frequência //////// ?>	
													<?php include "personasFrequencia.php"; ?>	
													
												</div>
																						
												<!-- aba valor-->
												<div class="tab-pane" id="valor">
												<h4 style="margin: 0 0 5px 0;">Configuração de Valor</h4>
												<small style="font-size: 12px;">Quais os valores de consumo e a forma de pagamento do público alvo?</small>

													<?php /////// Bloco Valor //////// ?>	
													<?php include "personasValor.php"; ?>	
												
												</div>
												
												<!-- aba geolocalizacao-->
												<div class="tab-pane" id="geolocalizacao">
												<h4 style="margin: 0 0 5px 0;">Configuração de Geolocalização</h4>
												<small style="font-size: 12px;">Qual a localização do público que você deseja atingir?</small>
												
													<?php //////// Bloco Geo /////// ?>	
													<?php include "personasGeo.php"; ?>	
												
												</div>			
													
												<!-- aba engajamento-->
												<div class="tab-pane" id="engajamento">
												<h4 style="margin: 0 0 5px 0;">Configuração de Engajamento</h4>
												<small style="font-size: 12px;">Qual é o perfil de interação <br/> do público que responderá melhor <br/> a sua campanha?</small>
												
												</div>											
												
											  </div>
											  
											</div>

											<div class="clearfix"></div>
																					
										</div>
																		
										<div class="push10"></div>
										<hr>	
										<div class="form-group text-right col-lg-12">

											<a class="btn btn-info exportarCSV pull-left">
												<div class="notify-badge text-center pos posHidden" id="notificaExportar">
													<span class="fas fa-info"></span>
												</div>
												<i class="fal fa-file-excel" aria-hidden="true"></i>&nbsp;Exportar
											</a>

											<button type="reset" class="btn btn-default"><i class="far fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
											<?php if ($cod_empresa == "0") {?>	
											<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar Persona</button>
											<?php } else { ?>
											<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn" <?php echo $bloqueiaAlt; ?> ><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Atualizar Persona</button>
											<?php } ?>
										</div>
										
										<input type="hidden" name="CONTROLE" id="CONTROLE" value="0">
										<input type="hidden" name="COD_PERSONA" id="COD_PERSONA" value="<?php echo $cod_persona; ?>">
										<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
										<input type="hidden" name="opcao" id="opcao" value="">
										<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
										<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">												
										
									</form>
									
									<!-- totalizador personas -->
									<div class="scrollPersona">
										<div class="widget widget-primary widget-item-icon">
											<div class="widget-item-left">
												<span class="fa fa-users"></span>
											</div>
											<div class="widget-data">
												<div class="widget-int num-count" id="div_Total" style="text-align: center; font-size: 40px; padding-top: 10px;"><?php echo number_format ( $totalIni,0,",","."); ?></div>
												<div class="widget-title" style="text-align: center;">Clientes Selecionados</div>
											</div>													                        
										</div>	
									</div>
																			
									<!-- modal -->									
									<div class="modal fade" id="popModalAux" tabindex='-1'>
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

									<div class="push50"></div> 
									
									<div class="clearfix"></div>									
									
									<div class="push50"></div> 
											
								</div>
							</div>
							<!-- fim Portlet -->
						</div>
						
					</div>					
						
					<div class="push20"></div> 


	<script type="text/javascript">
	
		$(document).ready(function() {
			
			$(".disabled").click(function (e) {
					e.preventDefault();
					return false;
		});
			
			//controle de alteração do form
			$("input, select").change(function(){
				$("#CONTROLE").val(parseInt($("#CONTROLE").val())+1);

				if($("#CONTROLE").val() > 0){
					$('#notificaExportar').removeClass('posHidden');
				}
			});				

			$(".exportarCSV").click(function() {

			if($("#CONTROLE").val() > 0){

					$.confirm({
						title: 'Exportação',
						content: 'Você fez alterações na pesquisa atual.<br> Deseja atualizar a pesquisa antes de exportar?',
						buttons: {
							cancelar: function () {
								$.confirm({
									title: 'Exportação',
									content: '' +
									'<form action="" class="formName">' +
									'<div class="form-group">' +
									'<label>Insira o nome do arquivo:</label>' +
									'<input type="text" placeholder="Nome" class="nome form-control" required />' +				
									'</div>' +
									'</form>',
									buttons: {
										cancelar: function () {
											//close
										},
										formSubmit: {
											text: 'Gerar',
											btnClass: 'btn-blue',
											action: function () {
												var nome = this.$content.find('.nome').val();
												if(!nome){
													$.alert('Por favor, insira um nome');
													return false;
												}
												$.confirm({
													title: 'Mensagem',
													type: 'green',
													icon: 'fa fa-check-square-o',
													content: function(){
														var self = this;
														return $.ajax({
															url: "ajxListaPersonasClientes.do?opcao=exportar&nomeRel="+nome+"&id=<?php echo fnEncode($cod_empresa); ?>&codPersona=<?php echo fnEncode($cod_persona); ?>",
															method: 'POST'
														}).done(function (response) {
															self.setContentAppend('<div>Exportação realizada com sucesso.</div>');
															var fileName = '<?php echo $cod_empresa; ?>_' + nome + '.csv';
															SaveToDisk('media/excel/' + fileName, fileName);
															//console.log(response);
														}).fail(function(){
															self.setContentAppend('<div>Erro ao realizar o procedimento!</div>');
														});
													},							
													buttons: {
														fechar: function () {
															//close
														}									
													}
												});								
											}
										},
									}
								});
							},
							formSubmit: {
								text: 'Atualizar Persona',
								btnClass: 'btn-primary',
								action: function () {
										$('#ALT').click();
										$("#CONTROLE").val('0');
										$('#notificaExportar').addClass('posHidden');
									}
							},
						}
					});

				}else{					
				$.confirm({
					title: 'Exportação',
					content: '' +
					'<form action="" class="formName">' +
					'<div class="form-group">' +
					'<label>Insira o nome do arquivo:</label>' +
					'<input type="text" placeholder="Nome" class="nome form-control" required />' +				
					'</div>' +
					'</form>',
					buttons: {
						formSubmit: {
							text: 'Gerar',
							btnClass: 'btn-blue',
							action: function () {
								var nome = this.$content.find('.nome').val();
								if(!nome){
									$.alert('Por favor, insira um nome');
									return false;
								}
								$.confirm({
									title: 'Mensagem',
									type: 'green',
									icon: 'fa fa-check-square-o',
									content: function(){
										var self = this;
										return $.ajax({
											url: "ajxListaPersonasClientes.do?opcao=exportar&nomeRel="+nome+"&id=<?php echo fnEncode($cod_empresa); ?>&codPersona=<?php echo fnEncode($cod_persona); ?>",
											method: 'POST'
										}).done(function (response) {
											self.setContentAppend('<div>Exportação realizada com sucesso.</div>');
											var fileName = '<?php echo $cod_empresa; ?>_' + nome + '.csv';
											SaveToDisk('media/excel/' + fileName, fileName);
											//console.log(response);
										}).fail(function(){
											self.setContentAppend('<div>Erro ao realizar o procedimento!</div>');
										});
									},							
									buttons: {
										fechar: function () {
											//close
										}									
									}
								});								
							}
						},
						cancelar: function () {
							//close
						},
					}
				});
			}				
		});
												
			
		});
		
		$(".atualiza").click(function () {
			calcPersona();
		});

		
		$("#BL1_IDADES").mouseup(function () {
			//alert("passou");
		});

		function calcPersona() {
			$.ajax({
				type: "POST",
				url: "ajxCalcPersonaTeste.php",
				data: $('#formulario').serialize(),
				beforeSend:function(){
					$('#div_Total').html('<div class="loading" style="width: 100%;"></div>');
				},
				success:function(data){
					$("#div_Total").html(data); 
				},
				error:function(){
					$('#div_Total').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
				}
			});		
		}
		
		function retornaForm(index){
			$("#formulario #COD_GRUPOTR").val($("#ret_COD_GRUPOTR_"+index).val());
			$("#formulario #DES_GRUPOTR").val($("#ret_DES_GRUPOTR_"+index).val());
			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');						
		}
			
		$(function () {
			/* atualiza no change do slider
			,
				onFinish: function (data) {
					calcPersona();
				}
			*/
			
			$("#BL1_IDADES").ionRangeSlider({
				hide_min_max: true,
				keyboard: true,
				min: 0,
				max: 150,
				from: <?php echo $idadeIni ?>,
				to: <?php echo $idadeFim ?>,
				type: 'int',
				step: 5,
				//prettify_enabled: true,
				//prettify_separator: "."
				//prefix: "Idade ",
				postfix: " anos",
				max_postfix: "+"
				//grid: true
			});
			/*
			$("#range").ionRangeSlider();
			*/

		});

		$(window).resize(function() {
		  if ($(window).width() <= 600) {
			$('#prop-type-group').removeClass('btn-group');
			$('#prop-type-group').addClass('btn-group-vertical');
		  } else {
			$('#prop-type-group').addClass('btn-group');
			$('#prop-type-group').removeClass('btn-group-vertical');
		  }
		});
		
		$(".limpaPerfil").click(function() { 

			$("#BL1_JURIDICO").val("N");
			$("#BL1_MASCULINO").val("S");
			$('#BL1_MASCULINO').prop('checked', true);
			$("#BL1_FEMININO").val("S");
			$('#BL1_FEMININO').prop('checked', true);			
			$("#BL1_ENDERECO").val("N");
			$('#BL1_ENDERECO').prop('checked', false);
			$("#BL1_CELULAR").val("N");
			$('#BL1_CELULAR').prop('checked', false);
			$("#BL1_EMAIL").val("N");
			$('#BL1_EMAIL').prop('checked', false);
			$("#BL1_TELEFONE").val("N");
			$('#BL1_TELEFONE').prop('checked', false);			
			$("#BL1_ANIVERSARIO").val("").trigger("chosen:updated");
			$("#BL1_OPERAPROFI").val("").trigger("chosen:updated");
			$("#BL1_PROFISSOES").val("").trigger("chosen:updated");
			$("#BL1_IDADES").data("ionRangeSlider").reset();
			
		});		
				
	</script>
	
	<script src="js/plugins/ion.rangeSlider.js"></script>
   
    <script src="js/plugins/Chart.min.js"></script>
    	
    <script>
      Chart.defaults.global.legend = {
        enabled: false
      };

      // Bar chart
	  // gentelella
      var ctx = document.getElementById("mybarChart");
      var mybarChart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: ["18 a 20", "21 a 30", "31 a 40", "41 a 50", "51 a 60", "61 a 70", "Acima de 70"],
          datasets: [{
            label: 'Clientes',
            backgroundColor: "#85C1E9",
            data: [<?php echo $valor1 ?>, 
				   <?php echo $valor2 ?>, 
				   <?php echo $valor3 ?>, 
				   <?php echo $valor4 ?>, 
				   <?php echo $valor5 ?>, 
				   <?php echo $valor6 ?>, 
				   <?php echo $valor7 ?>
				   ]
          }]
        },

        options: {
          scales: {
            yAxes: [{
              ticks: {
                beginAtZero: true
              }
            }]
          }
        }
      });

    </script>
    <!-- /Chart.js -->
