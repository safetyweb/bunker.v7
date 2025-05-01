<?php

//  echo fnDebug('true');
	
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

		$request = md5( json_encode( @$_POST ) );
		
		if(isset( $_SESSION['last_request'] ) && $_SESSION['last_request']== $request )
		//if(false)
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
				$bl1_aniversario = @$bl1_aniversario.$Arr_BL1_ANIVERSARIO[$i].";";
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

			//Participa Fidelização
			if (empty($_REQUEST['BL1_LOG_FIDELIZADO'])) {$bl1_log_fidelizado='N';}else{$bl1_log_fidelizado=$_REQUEST['BL1_LOG_FIDELIZADO'];}
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

			//BLOCO 2 - PRODUTOS (AUTOMÁTICO)
			
			$log_congela = fnLimpaCampo($_REQUEST['LOG_CONGELA']);

			//BLOCO 3 - FREQUÊNCIA	
			$bl3_compras_ini = fnLimpaCampo($_REQUEST['BL3_COMPRAS_INI']);
			$bl3_compras_fim = fnLimpaCampo($_REQUEST['BL3_COMPRAS_FIM']);
			
			$bl3_cadastros_ini = fnLimpaCampo($_REQUEST['BL3_CADASTROS_INI']);
			$bl3_cadastros_fim = fnLimpaCampo($_REQUEST['BL3_CADASTROS_FIM']);
			
			$bl3_ucompras_ini = fnLimpaCampo($_REQUEST['BL3_UCOMPRAS_INI']);
			$bl3_ucompras_fim = fnLimpaCampo($_REQUEST['BL3_UCOMPRAS_FIM']);
			
			$bl3_comprase_ini = fnLimpaCampo($_REQUEST['BL3_COMPRASE_INI']);
			$bl3_comprase_fim = fnLimpaCampo($_REQUEST['BL3_COMPRASE_FIM']);

			if (empty($_REQUEST['BL3_LOG_SEMCOMPR'])) {$bl3_log_semcompr='N';}else{$bl3_log_semcompr=$_REQUEST['BL3_LOG_SEMCOMPR'];}
			$bl3_semcompr_ini = fnLimpaCampo($_REQUEST['BL3_SEMCOMPR_INI']);
			$bl3_semcompr_fim = fnLimpaCampo($_REQUEST['BL3_SEMCOMPR_FIM']);
			
			if (empty($_REQUEST['BL3_LOG_SEMRESG'])) {$bl3_log_semresg='N';}else{$bl3_log_semresg=$_REQUEST['BL3_LOG_SEMRESG'];}
			$bl3_semresg_ini = fnLimpaCampo($_REQUEST['BL3_SEMRESG_INI']);
			$bl3_semresg_fim = fnLimpaCampo($_REQUEST['BL3_SEMRESG_FIM']);
			
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
			
			if (empty($_REQUEST['BL5_UNIPREF'])) {$bl5_unipref='N';}else{$bl5_unipref=$_REQUEST['BL5_UNIPREF'];}
			if (empty($_REQUEST['BL5_UNIVE_TODOS'])) {$bl5_unive_todos='N';}else{$bl5_unive_todos=$_REQUEST['BL5_UNIVE_TODOS'];}
			if (empty($_REQUEST['BL5_UNIVE_ORIGEM'])) {$bl5_unive_origem='N';}else{$bl5_unive_origem=$_REQUEST['BL5_UNIVE_ORIGEM'];}

			if (!empty($_REQUEST['BL5_UNIVE_ORIGEM_V'])){
				$bl5_unive_origem_ref = "V";
			}else if(!empty($_REQUEST['BL5_UNIVE_ORIGEM_O'])){
				$bl5_unive_origem_ref = "O";
			}else if(!empty($_REQUEST['BL5_UNIVE_ORIGEM_C'])){
				$bl5_unive_origem_ref = "C";
			}else{
				$bl5_unive_origem_ref = 'N';
			}

			//default no geo
			$bl5_unive_origem = $bl5_unive_origem_ref;

			//array - lojas
			if (isset($_POST['BL5_COD_UNIVE'])){
				$Arr_BL5_COD_UNIVE = $_POST['BL5_COD_UNIVE'];
				//print_r($Arr_BL5_COD_UNIVEO);			 
			   for ($i=0;$i<count($Arr_BL5_COD_UNIVE);$i++) 
			   { 
				$bl5_cod_unive = @$bl5_cod_unive.$Arr_BL5_COD_UNIVE[$i].";";
			   } 			   
			   $bl5_cod_unive = rtrim($bl5_cod_unive,';');				
			}else{$bl5_cod_unive = "0";}

			//array - estados
			if (isset($_POST['BL5_COD_ESTADOF'])){
				$Arr_BL5_COD_ESTADOF = $_POST['BL5_COD_ESTADOF'];
 
			   for ($i=0;$i<count($Arr_BL5_COD_ESTADOF);$i++) 
			   { 
				$bl5_cod_estadof = @$bl5_cod_estadof.$Arr_BL5_COD_ESTADOF[$i].";";
			   } 			   
			   $bl5_cod_estadof = rtrim($bl5_cod_estadof,';');				
			}else{$bl5_cod_estadof = "0";}

			$cod_univend_master = fnLimpaCampo($_REQUEST['COD_UNIVEND_MASTER']);

			if($bl5_cod_unive != "0"){
				$bl5_cod_unive = $bl5_cod_unive.";".$cod_univend_master;
			}else{
				if($cod_univend_master != ""){
					$bl5_cod_unive = $cod_univend_master;
				}
			}
			
			$bl5_cod_unive = ltrim(rtrim($bl5_cod_unive,';'),';');
			$bl5_cod_estadof = ltrim(rtrim($bl5_cod_estadof,';'),';');


			//array - Categorização de clientes
			if (isset($_POST['BL6_FREQ_CLIENTE'])){
				$Arr_BL6_FREQ_CLIENTE = $_POST['BL6_FREQ_CLIENTE'];
				//print_r($Arr_BL6_FREQ_CLIENTE);			 
			   for ($i=0;$i<count($Arr_BL6_FREQ_CLIENTE);$i++) 
			   { 
				$bl6_freq_cliente = @$bl6_freq_cliente.$Arr_BL6_FREQ_CLIENTE[$i].";";
			   } 			   
			   $bl6_freq_cliente = rtrim($bl6_freq_cliente,';');				
			}else{$bl6_freq_cliente = "";}

			//array - Categoriação exclusiva
			if (isset($_POST['BL6_FREQ_CLIENTE_U'])){
				$Arr_BL6_FREQ_CLIENTE_U = $_POST['BL6_FREQ_CLIENTE_U'];
				//print_r($Arr_BL6_FREQ_CLIENTE_U);
			   for ($i=0;$i<count($Arr_BL6_FREQ_CLIENTE_U);$i++) 
			   { 
				$bl6_freq_cliente_u = @$bl6_freq_cliente_u.$Arr_BL6_FREQ_CLIENTE_U[$i].";";
			   } 			   
			   $bl6_freq_cliente_u = rtrim($bl6_freq_cliente_u,';');				
			}else{$bl6_freq_cliente_u = "";}
			
			$bl6_freq_cliente = ltrim(rtrim($bl6_freq_cliente,';'),';');
			$bl6_freq_cliente_u = ltrim(rtrim($bl6_freq_cliente_u,';'),';');

			$count_filtros = fnLimpacampo($_REQUEST['COUNT_FILTROS']);
			
			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
			$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

			//BLOCO 6 - ENGAJA
			
			if (empty($_REQUEST['BL6_ENGAJA_1'])) {$bl6_engaja_1='N';}else{$bl6_engaja_1=$_REQUEST['BL6_ENGAJA_1'];}
			if (empty($_REQUEST['BL6_ENGAJA_2'])) {$bl6_engaja_2='N';}else{$bl6_engaja_2=$_REQUEST['BL6_ENGAJA_2'];}
			if (empty($_REQUEST['BL6_ENGAJA_3'])) {$bl6_engaja_3='N';}else{$bl6_engaja_3=$_REQUEST['BL6_ENGAJA_3'];}
			if (empty($_REQUEST['BL6_ENGAJA_4'])) {$bl6_engaja_4='N';}else{$bl6_engaja_4=$_REQUEST['BL6_ENGAJA_4'];}

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
				'".$bl1_log_fidelizado."',
				'".$bl1_log_email."',
				'".$bl1_log_sms."',
				'".$bl1_log_telemark."',
				'".$bl1_log_whatsapp."',
				'".$bl1_log_push."',
				'".$cod_usucada."',
				'".fnDataSql($bl3_cadastros_ini)."',
				'".fnDataSql($bl3_cadastros_fim)."',
				'".fnDataSql($bl3_compras_ini)."',
				'".fnDataSql($bl3_compras_fim)."',
				'".fnDataSql($bl3_ucompras_ini)."',
				'".fnDataSql($bl3_ucompras_fim)."',
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
				'".$bl6_freq_cliente_u."'
				) ";
								
				//fnEscreve($sql);
				
				mysqli_query(connTemp($cod_empresa,""),trim($sql)) or die(mysqli_error());				
				//fnTestesql(connTemp($cod_empresa,""),trim($sql)) or die(mysqli_error());

				if($count_filtros != ""){

					$sql = "DELETE FROM FILTROS_PERSONA WHERE COD_PERSONA = $cod_persona;";
					$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

					for ($i=0; $i < $count_filtros; $i++) {

						$cod_tpfiltro = fnLimpacampoZero($_REQUEST["COD_TPFILTRO_$i"]);

						if (isset($_REQUEST["COD_FILTRO_$i"])){

							// fnEscreve("TEM FILTRO");

							$Arr_COD_FILTRO = $_REQUEST['COD_FILTRO_$i'];

							//print_r($_REQUEST["COD_FILTRO_$i"]);	 
							 
							for ($j=0;$j<count($_REQUEST["COD_FILTRO_$i"]);$j++){

								$sql .= "INSERT INTO FILTROS_PERSONA(
												COD_EMPRESA,
												COD_TPFILTRO,
												COD_FILTRO,
												COD_PERSONA,
												COD_USUCADA
												)VALUES(
												$cod_empresa,
												$cod_tpfiltro,
												".$_REQUEST["COD_FILTRO_$i"][$j].",
												$cod_persona,
												$cod_usucada
												);
										";


							} 
								
						}

								
					}
					
					if($sql != ""){
						//fnEscreve($sql);
						mysqli_multi_query(connTemp($cod_empresa,''),$sql);
					}							

				}

				//atualiza base de personas
				$sql2 = "CALL SP_ALTERA_PERSONACLASSIFICA (
				 '".$cod_persona."', 
				 '".$cod_empresa."' 
				) ";		
				
				//fnEscreve($sql2);
				mysqli_query(connTemp($cod_empresa,''),$sql2);
				//mysqli_query(connTemp($cod_empresa,''),trim($sql2)) or die(mysqli_error());

				$sql3 = "UPDATE PERSONA SET LOG_CONGELA = '$log_congela' 
						 WHERE COD_EMPRESA = $cod_empresa 
						 AND COD_PERSONA = $cod_persona";
				// fnEscreve($sql3);

				mysqli_query(connTemp($cod_empresa,''),$sql3);
				
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
		
		//busca dados da empresa
		$sqlEmp = "SELECT COD_EMPRESA, NOM_EMPRESA, NOM_FANTASI
		FROM empresas where COD_EMPRESA = '".$cod_empresa."' 		
		";
		
		//fnEscreve($sql);
		//fnTestesql(connTemp($cod_empresa,''),$sqlPersonas);
		
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sqlEmp) or die(mysqli_error());
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
		
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];

		//busca dados da persona
		$sqlPers = "select DES_PERSONA, LOG_BLOQUEA, LOG_RESTRITO, COD_USUCADA, COD_UNIVEND from persona where cod_persona = '".$cod_persona."' and COD_EMPRESA = '".$cod_empresa."' 		
		";
				
		//fnEscreve($sql);
		//fnTestesql(connTemp($cod_empresa,''),$sqlPersonas);
		
		$arrayQuery = mysqli_query(connTemp($cod_empresa,""),$sqlPers) or die(mysqli_error());
		$qrBuscaPersona = mysqli_fetch_assoc($arrayQuery);
		
		$des_persona = $qrBuscaPersona['DES_PERSONA'];
		//$log_bloquea = $qrBuscaPersona['LOG_BLOQUEA'];
		$log_bloquea = $qrBuscaPersona['LOG_RESTRITO'];
		$cod_usucada_persona = $qrBuscaPersona['COD_USUCADA'];
		$cod_univend_persona = $qrBuscaPersona['COD_UNIVEND'];
		
		//bloqueia edição das personas
		if ($log_bloquea == "S"){
			$bloqueiaAlt = "disabled ";			
		} else {
			$bloqueiaAlt = " ";
		}

		$sqlLog = "SELECT LOG_CONGELA, LOG_IMPORT FROM PERSONA WHERE COD_EMPRESA = $cod_empresa AND COD_PERSONA = $cod_persona";
		$qrLog = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sqlLog));

		if(isset($qrLog)){
			$log_congela = $qrLog['LOG_CONGELA'];
			$log_import = $qrLog['LOG_IMPORT'];
			if ($log_congela == "S"){
				$bloqueiaAlt = "disabled ";			
			} else {
				$bloqueiaAlt = " ";
			}
		}else{
			$log_congela = 'N';
		}
		
		//busca dados da persona (regras)
		$sql = "SELECT * FROM PERSONAREGRA where COD_PERSONA = '".$cod_persona."' ";
		
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

			$bl1_log_fidelizado = $qrBuscaRegra['BL1_LOG_FIDELIZADO'];
			if ($bl1_log_fidelizado == "S") {$check_log_fidelizado = "checked";} else{$check_log_fidelizado = "";}
			$bl1_log_email = $qrBuscaRegra['BL1_LOG_EMAIL'];
			if ($bl1_log_email == "S") {$check_log_email = "checked";} else{$check_log_email = "";}
			$bl1_log_sms = $qrBuscaRegra['BL1_LOG_SMS'];
			if ($bl1_log_sms == "S") {$check_log_sms = "checked";} else{$check_log_sms = "";}
			$bl1_log_telemark = $qrBuscaRegra['BL1_LOG_TELEMARK'];
			if ($bl1_log_telemark == "S") {$check_log_telemark = "checked";} else{$check_log_telemark = "";}
			$bl1_log_whatsapp = $qrBuscaRegra['BL1_LOG_WHATSAPP'];
			if ($bl1_log_whatsapp == "S") {$check_log_whatsapp = "checked";} else{$check_log_whatsapp = "";}
			$bl1_log_push = $qrBuscaRegra['BL1_LOG_PUSH'];
			if ($bl1_log_push == "S") {$check_log_push = "checked";} else{$check_log_push = "";}

			$bl1_telefone = $qrBuscaRegra['BL1_TELEFONE'];
			if ($bl1_telefone == "S") {$check_telefone = "checked";} else{$check_telefone = "";}
			
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

			$bl3_comprase_ini = $qrBuscaRegra['BL3_COMPRASE_INI'];
			if (!empty($bl3_comprase_ini)){$bl3_comprase_ini = fnDateRetorno($bl3_comprase_ini);} else {$bl3_comprase_ini = "";}
			$bl3_comprase_fim = $qrBuscaRegra['BL3_COMPRASE_FIM'];
			if (!empty($bl3_comprase_fim)){$bl3_comprase_fim = fnDateRetorno($bl3_comprase_fim);} else {$bl3_comprase_fim = "";}

			$bl3_log_semcompr = $qrBuscaRegra['BL3_LOG_SEMCOMPR'];
			if ($bl3_log_semcompr == "S") {$check_bl3_log_semcompr = "checked";} else{$check_bl3_log_semcompr = "";}
			$bl3_semcompr_ini = $qrBuscaRegra['BL3_SEMCOMPR_INI'];
			if (!empty($bl3_semcompr_ini)){$bl3_semcompr_ini = fnDateRetorno($bl3_semcompr_ini);} else {$bl3_semcompr_ini = "";}
			$bl3_semcompr_fim = $qrBuscaRegra['BL3_SEMCOMPR_FIM'];
			if (!empty($bl3_semcompr_fim)){$bl3_semcompr_fim = fnDateRetorno($bl3_semcompr_fim);} else {$bl3_semcompr_fim = "";}

			$bl3_log_semresg = $qrBuscaRegra['BL3_LOG_SEMRESG'];
			if ($bl3_log_semresg == "S") {$check_bl3_log_semresg = "checked";} else{$check_bl3_log_semresg = "";}
			$bl3_semresg_ini = $qrBuscaRegra['BL3_SEMRESG_INI'];
			if (!empty($bl3_semresg_ini)){$bl3_semresg_ini = fnDateRetorno($bl3_semresg_ini);} else {$bl3_semresg_ini = "";}
			$bl3_semresg_fim = $qrBuscaRegra['BL3_SEMRESG_FIM'];
			if (!empty($bl3_semresg_fim)){$bl3_semresg_fim = fnDateRetorno($bl3_semresg_fim);} else {$bl3_semresg_fim = "";}

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
			if ($bl4_compra_min == 0.00){$bl4_compra_min = "";} else {$bl4_compra_min = fnValor($qrBuscaRegra['BL4_COMPRA_MIN'],2);}
			$bl4_compra_max = $qrBuscaRegra['BL4_COMPRA_MAX'];
			if ($bl4_compra_max == 0.00){$bl4_compra_max = "";} else {$bl4_compra_max = fnValor($qrBuscaRegra['BL4_COMPRA_MAX'],2);}
			$bl4_valortm_min = $qrBuscaRegra['BL4_VALORTM_MIN'];
			if ($bl4_valortm_min == 0.00){$bl4_valortm_min = "";} else {$bl4_valortm_min = fnValor($qrBuscaRegra['BL4_VALORTM_MIN'],2);}
			$bl4_valortm_max = $qrBuscaRegra['BL4_VALORTM_MAX'];
			if ($bl4_valortm_max == 0.00){$bl4_valortm_max = "";} else {$bl4_valortm_max = fnValor($qrBuscaRegra['BL4_VALORTM_MAX'],2);}			
			$bl4_gastos_min = $qrBuscaRegra['BL4_GASTOS_MIN'];
			if ($bl4_gastos_min == 0.00){$bl4_gastos_min = "";} else {$bl4_gastos_min = fnValor($qrBuscaRegra['BL4_GASTOS_MIN'],2);}
			$bl4_gastos_max = $qrBuscaRegra['BL4_GASTOS_MAX'];
			if ($bl4_gastos_max == 0.00){$bl4_gastos_max = "";} else {$bl4_gastos_max = fnValor($qrBuscaRegra['BL4_GASTOS_MAX'],2);}
			$bl4_credito_min = $qrBuscaRegra['BL4_CREDITO_MIN'];
			if ($bl4_credito_min == 0.00){$bl4_credito_min = "";} else {$bl4_credito_min = fnValor($qrBuscaRegra['BL4_CREDITO_MIN'],2);}
			$bl4_credito_max = $qrBuscaRegra['BL4_CREDITO_MAX'];
			if ($bl4_credito_max == 0.00){$bl4_credito_max = "";} else {$bl4_credito_max = fnValor($qrBuscaRegra['BL4_CREDITO_MAX'],2);}
			$bl4_tip_resgate = $qrBuscaRegra['BL4_TIP_RESGATE'];
			
			$bl4_qtd_resgate_min = $qrBuscaRegra['BL4_QTD_RESGATE_MIN'];
			if ($bl4_qtd_resgate_min == 0.00){$bl4_qtd_resgate_min = "";} else {$bl4_qtd_resgate_min = fnValor($qrBuscaRegra['BL4_QTD_RESGATE_MIN'],2);}
			
			$bl4_qtd_resgate = $qrBuscaRegra['BL4_QTD_RESGATE'];
			if ($bl4_qtd_resgate == 0.00){$bl4_qtd_resgate = "";} else {$bl4_qtd_resgate = fnValor($qrBuscaRegra['BL4_QTD_RESGATE'],2);}
			
			$bl4_qtd_avencer = $qrBuscaRegra['BL4_QTD_AVENCER'];
			if ($bl4_qtd_avencer == 0.00){$bl4_qtd_avencer = "";} else {$bl4_qtd_avencer = fnValor($qrBuscaRegra['BL4_QTD_AVENCER'],0);}
			$bl4_tip_avencer = $qrBuscaRegra['BL4_TIP_AVENCER'];
			$bl4_tip_saldo = $qrBuscaRegra['BL4_TIP_SALDO'];
			
			$bl4_val_saldo_min = $qrBuscaRegra['BL4_VAL_SALDO_MIN'];
			if ($bl4_val_saldo_min == 0.00){$bl4_val_saldo_min = "";} else {$bl4_val_saldo_min = fnValor($qrBuscaRegra['BL4_VAL_SALDO_MIN'],2);}
			
			$bl4_val_saldo = $qrBuscaRegra['BL4_VAL_SALDO'];
			if ($bl4_val_saldo == 0.00){$bl4_val_saldo = "";} else {$bl4_val_saldo = fnValor($qrBuscaRegra['BL4_VAL_SALDO'],2);}
			
			//BLOCO 5 - GEO
			
			$bl5_unive_origem = $qrBuscaRegra['BL5_UNIVE_ORIGEM'];
			//fnEscreve($bl5_unive_origem);
			switch ($bl5_unive_origem) {
				case 'V': //todas as vendas 
					$check_unive_origem_v = "checked";
					$check_unive_origem_o = "";
					$check_unive_origem_c = "";
					break;
				case 'O': //loja de origem
					$check_unive_origem_v = "";
					$check_unive_origem_o = "checked";
					$check_unive_origem_c = "";
					break;
				case 'C': //clientes geral - cpf
					$check_unive_origem_v = "";
					$check_unive_origem_o = "";
					$check_unive_origem_c = "checked";
					break;
				default:
					$check_unive_origem_v = "";
					$check_unive_origem_o = "";
					$check_unive_origem_c = "checked";
					break;
				}
				
			if ($bl5_unive_origem == "S") {$check_unive_origem = "checked";} else{$check_unive_origem = "";}
			$bl5_unipref = $qrBuscaRegra['BL5_UNIPREF'];
			if ($bl5_unipref == "S") {$check_unipref = "checked";} else{$check_unipref = "";}			
			
			$bl5_unive_todos = $qrBuscaRegra['BL5_UNIVE_TODOS'];
			if ($bl5_unive_todos == "S") {$check_unive_todos = "checked";} else{$check_unive_todos = "";}			
			
			$bl5_cod_unive = $qrBuscaRegra['BL5_COD_UNIVE'];
			$bl5_cod_estadof = $qrBuscaRegra['BL5_COD_ESTADOF'];


			//BLOCO 6 - ENGAJA
			$bl6_engaja_1 = $qrBuscaRegra['BL6_ENGAJA_1'];
			$bl6_engaja_2 = $qrBuscaRegra['BL6_ENGAJA_2'];
			$bl6_engaja_3 = $qrBuscaRegra['BL6_ENGAJA_3'];
			$bl6_engaja_4 = $qrBuscaRegra['BL6_ENGAJA_4'];
			if ($bl6_engaja_1 == "S") {$check_bl6_engaja_1 = "checked";} else{$check_bl6_engaja_1 = "";}
			if ($bl6_engaja_2 == "S") {$check_bl6_engaja_2 = "checked";} else{$check_bl6_engaja_2 = "";}
			if ($bl6_engaja_3 == "S") {$check_bl6_engaja_3 = "checked";} else{$check_bl6_engaja_3 = "";}
			if ($bl6_engaja_4 == "S") {$check_bl6_engaja_4 = "checked";} else{$check_bl6_engaja_4 = "";}
			
			$bl6_freq_cliente = $qrBuscaRegra['BL6_FREQ_CLIENTE'];
			$bl6_freq_cliente_u = $qrBuscaRegra['BL6_FREQ_CLIENTE_U'];

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
				
				$check_log_fidelizado = "checked";
				$check_log_email = "";
				$check_log_sms = "";
				$check_log_telemark = "";
				$check_log_whatsapp = "";
				$check_log_push = "";				
				
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

				$bl3_comprase_ini = "";
				$bl3_comprase_fim = "";
				$bl3_log_semcompr='N';
				$bl3_semcompr_ini = "";
				$bl3_semcompr_fim = "";
				$bl3_log_semresg='N';
				$bl3_semresg_ini = "";
				$bl3_semresg_fim = "";
				
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


				//BLOCO 6 - ENGAJA
				$bl6_engaja_1 = "N";
				$bl6_engaja_2 = "N";
				$bl6_engaja_3 = "N'";
				$bl6_engaja_4 = "N";
				if ($bl6_engaja_1 == "S") {$check_bl6_engaja_1 = "checked";} else{$check_bl6_engaja_1 = "";}
				if ($bl6_engaja_2 == "S") {$check_bl6_engaja_2 = "checked";} else{$check_bl6_engaja_2 = "";}
				if ($bl6_engaja_3 == "S") {$check_bl6_engaja_3 = "checked";} else{$check_bl6_engaja_3 = "";}
				if ($bl6_engaja_4 == "S") {$check_bl6_engaja_4 = "checked";} else{$check_bl6_engaja_4 = "";}
				$bl6_freq_cliente = "";
				$bl6_freq_cliente_u = "";
				
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
				$check_unipref = "";

				$check_unive_origem_v = "";
				$check_unive_origem_o = "";
				$check_unive_origem_c = "checked";


				
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
				$idadeFim = 150;
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
					
				//BLOCO 3 - FREQUÊNCIA	
				$bl3_compras_ini = "";
				$bl3_compras_fim = "";				
				$bl3_cadastros_ini = "";
				$bl3_cadastros_fim = "";				
				$bl3_ucompras_ini = "";
				$bl3_ucompras_fim = "";

				$bl3_comprase_ini = "";
				$bl3_comprase_fim = "";
				$bl3_log_semcompr='N';
				$bl3_semcompr_ini = "";
				$bl3_semcompr_fim = "";
				$bl3_log_semresg='N';
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
				if ($bl6_engaja_1 == "S") {$check_bl6_engaja_1 = "checked";} else{$check_bl6_engaja_1 = "";}
				if ($bl6_engaja_2 == "S") {$check_bl6_engaja_2 = "checked";} else{$check_bl6_engaja_2 = "";}
				if ($bl6_engaja_3 == "S") {$check_bl6_engaja_3 = "checked";} else{$check_bl6_engaja_3 = "";}
				if ($bl6_engaja_4 == "S") {$check_bl6_engaja_4 = "checked";} else{$check_bl6_engaja_4 = "";}
				$bl6_freq_cliente = "";
				$bl6_freq_cliente_u = "";

			}
			
		}

		if($idadeIni==''){$idadeIni='0';}
		if($idadeFim==''){$idadeFim='150';}
		 
		//Busca total inicial 
		$opcao = "S";
		
		$sqlPersonas = "CALL SP_BUSCA_PERSONA_MASTER(
													'".@$cod_persona."', 
													'".@$bl1_masculino."', 
													'".@$bl1_feminino."', 				
													'".@$idadeIni."',										
													'".@$idadeFim."',										
													'".@$bl1_endereco."',
													'".@$bl1_celular."',
													'".@$bl1_email."',
													'".@$bl1_telefone."',
													'".@$bl1_aniversario."',
													'".@$bl1_operaprofi."',
													'".@$bl1_profissoes."',
													'".@$bl1_log_fidelizado."',
													'".@$bl1_log_email."',
													'".@$bl1_log_sms."',
													'".@$bl1_log_telemark."',
													'".@$bl1_log_whatsapp."',
													'".@$bl1_log_push."',
													'".@$cod_empresa."',
													'".@$bl1_juridico."',
													".fnDataSqlNull(@$bl3_cadastros_ini).",
													".fnDataSqlNull(@$bl3_cadastros_fim).",
													".fnDataSqlNull(@$bl3_compras_ini).",
													".fnDataSqlNull(@$bl3_compras_fim).",
													".fnDataSqlNull(@$bl3_ucompras_ini).",
													".fnDataSqlNull(@$bl3_ucompras_fim).",
													".fnDataSqlNull(@$bl3_comprase_ini).",
													".fnDataSqlNull(@$bl3_comprase_fim).",
													'".@$bl3_log_semcompr."',
													".fnDataSqlNull(@$bl3_semcompr_ini).",
													".fnDataSqlNull(@$bl3_semcompr_fim).",
													'".@$bl3_log_semresg."',
													".fnDataSqlNull(@$bl3_semresg_ini).",
													".fnDataSqlNull(@$bl3_semresg_fim).",
													'".@$bl3_log_resgate."',
													'".@$bl3_tip_resgate."',
													'".fnValorSql(@$bl3_qtd_resgate)."',
													'".fnValorSql(@$bl4_compra_min)."',
													'".fnValorSql(@$bl4_compra_max)."',
													'".fnValorSql(@$bl4_valortm_min)."',
													'".fnValorSql(@$bl4_valortm_max)."',
													'".fnValorSql(@$bl4_gastos_min)."',
													'".fnValorSql(@$bl4_gastos_max)."',
													'".fnValorSql(@$bl4_credito_min)."',
													'".fnValorSql(@$bl4_credito_max)."',
													'".fnLimpaCampo(@$bl4_tip_resgate)."',
													'".fnValorSql(@$bl4_qtd_resgate_min)."',
													'".fnValorSql(@$bl4_qtd_resgate)."',
													'".fnValorSql(@$bl4_qtd_avencer)."',
													'".fnLimpaCampo(@$bl4_tip_avencer)."',
													'".fnLimpaCampo(@$bl4_tip_saldo)."',
													'".fnValorSql(@$bl4_val_saldo_min)."',
													'".fnValorSql(@$bl4_val_saldo)."',
													'".@$bl5_cod_unive."',
													'".@$bl5_unive_origem."',
													'".@$bl5_unive_todos."',
													'".@$bl5_unipref."',
													'".fnValorSql(@$bl3_qtd_retorno_ini)."',
													'".fnValorSql(@$bl3_qtd_retorno_fim)."',
													'".@$bl5_cod_estadof."',
													'".@$bl6_engaja_1."',
													'".@$bl6_engaja_2."',
													'".@$bl6_engaja_3."',
													'".@$bl6_engaja_4."',
													'".@$bl6_freq_cliente."',
													'".@$bl6_freq_cliente_u."',
													'".@$opcao."',
													'N'
													) ";
													
													
		//fnEscreve($sqlPersonas);
		//fnEscreve($cod_empresa);
		
		//fnTestesql(connTemp($cod_empresa,''),$sqlPersonas);
		$sqlPersonasquery = mysqli_query(connTemp($cod_empresa,''),$sqlPersonas) or die(mysqli_error());        
		$qrCalcRegra = mysqli_fetch_assoc($sqlPersonasquery);
		//print_r($qrCalcRegra);
		
		//atualiza base de personas
		//mudado para a rotina de gravação - Rone e Adilson (03/04/2019)
		/*
		$sql2 = "CALL SP_ALTERA_PERSONACLASSIFICA (
		 '".$cod_persona."', 
		 '".$cod_empresa."' 
		) ";		
		*/
		//fnEscreve($sql2);
		//fnTestesql(connTemp($cod_empresa,''),$sql2);
		//mysqli_query(connTemp($cod_empresa,''),trim($sql2)) or die(mysqli_error());		
	    //$sqlPersonasquery= mysqli_fetch_row($sqlPersonasquery);
		
	    //fnEscreve($sqlPersonasquery[0]);
		$totalIni = $qrCalcRegra['QTD_TOTCLI'];
		$totalGeral = $qrCalcRegra['TOT_GERAL'];
		$totalHom = $qrCalcRegra['QTD_MASCULINO'];
		$totalFem = $qrCalcRegra['QTD_FEMININO'];		
		$totalPJ = $qrCalcRegra['QTD_JURIDICO'];
		
		$tot_masculino = $qrCalcRegra['TOT_MASCULINO'];		
		$tot_feminino = $qrCalcRegra['TOT_FEMININO'];		

		$valor1 = @$qrCalcRegra['IDADE1'];
		$valor2 = @$qrCalcRegra['IDADE2'];
		$valor3 = @$qrCalcRegra['IDADE3'];
		$valor4 = @$qrCalcRegra['IDADE4'];
		$valor5 = @$qrCalcRegra['IDADE5'];
		$valor6 = @$qrCalcRegra['IDADE6'];
		$valor7 = @$qrCalcRegra['IDADE7'];

		$totIdade = $valor1 + $valor2 + $valor3 + $valor4 + $valor5 + $valor6 + $valor7;

		$percIdade1 = ($totIdade<=0?0:($valor1/$totIdade)*100);
		$percIdade2 = ($totIdade<=0?0:($valor2/$totIdade)*100);
		$percIdade3 = ($totIdade<=0?0:($valor3/$totIdade)*100);
		$percIdade4 = ($totIdade<=0?0:($valor4/$totIdade)*100);
		$percIdade5 = ($totIdade<=0?0:($valor5/$totIdade)*100);
		$percIdade6 = ($totIdade<=0?0:($valor6/$totIdade)*100);
		$percIdade7 = ($totIdade<=0?0:($valor7/$totIdade)*100);
		
		//fnEscreve($totalIni);
		//fnEscreve($totalGeral);
		//fnEscreve($totalHom);
		//fnEscreve($totalHom);
		//fnEscreve($totalFem);
		//fnEscreve($totalPJ);
 												
	}else {
		$cod_empresa = 0;		
		//fnEscreve($cod_empresa);
	}
	
	//calcula totalizador do bloco
	$bl1_total = 0;
	if (@$bl1_masculino == "S") {$bl1_total = $bl1_total + 1;}
	if (@$bl1_feminino == "S") {$bl1_total = $bl1_total + 1;}
	if (@$bl1_juridico == "S") {$bl1_total = $bl1_total + 1;}
	if (@$bl1_idades != "0") {$bl1_total = $bl1_total + 1;}
	if (@$bl1_aniversario != "0") {$bl1_total = $bl1_total + 1;}
	if (@$bl1_operaprofi != "") {$bl1_total = $bl1_total + 1;}
	if (@$bl1_profissoes != "0") {$bl1_total = $bl1_total + 1;}
	
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
	if (!empty($bl4_gastos_min)){$bl4_total = $bl4_total + 1;}
	if (!empty($bl4_gastos_max)){$bl4_total = $bl4_total + 1;}
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
	if (!empty($bl5_cod_estadof)){$bl5_total = $bl5_total + 1;}
	
	$bl6_total = 0;
	if ($bl6_engaja_1 == "S") {
		$bl6_total++;
	}
	if ($bl6_engaja_2 == "S") {
		$bl6_total++;
	}
	if ($bl6_engaja_3 == "S") {
		$bl6_total++;
	}
	if ($bl6_engaja_4 == "S") {
		$bl6_total++;
	}
	if (!empty($bl6_freq_cliente)){$bl5_total = $bl5_total + 1;}
	if (!empty($bl6_freq_cliente_u)){$bl5_total = $bl5_total + 1;}

	//liberação das abas
	$abaPersona	= "S";
	$abaVantagem = "N";
	$abaRegras = "N";
	$abaComunica = "N";
	$abaAtivacao = "N";
	$abaResultado = "N";

	$abaPersonaComp = "active ";
	$abaCampanhaComp = "";
	$abaRegrasComp = "";
	$abaComunicaComp = "";
	$abaAtivacaoComp = "";
	$abaResultadoComp = "";		
	
	//fnMostraForm();
	//fnEscreve($bl4_qtd_avencer);
	//fnEscreve($bl4_tip_avencer);
	//fnEscreve($bl1_operaprofi);
	//fnEscreve($bl1_profissoes);
	//fnEscreve($abaPersona);       
	//fnEscreve($bl3_cadastros_ini);
	//fnEscreve($bl1_idades); 

	if(fnAutMaster($_SESSION["SYS_COD_TPUSUARIO"], $_SESSION["SYS_COD_EMPRESA"])=='1'){
		$usuLimitado = 'false';
	}else{
		$usuLimitado = 'true';
	}

	// fnEscreve($log_congela);
        

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
	left: 145;
	top:-10;
	background: #ffbf00;
	font-size: 9px;
	padding-top: 7px;
}

.posHidden{
	display: none;
}

.bolder{
	font-weight: 1000!important;
}

.bold{
	font-weight: 500!important;
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

									<?php  if ($log_bloquea == "S"){ ?>									
									<div class="alert alert-danger" role="alert">
									   Persona  <strong>bloqueada</strong> para edição.
									</div>
									<?php } ?>

									<?php   if ($log_congela == "S"){

												if ($log_import == "S"){
									?>
													<div class="alert alert-info" role="alert">
													   Persona é de origem de importação e está <strong>congelada</strong> para edição.
													</div>

									<?php

												}else{

									?>									
													<div class="alert alert-info" role="alert">
													   Persona  <strong>congelada</strong> para edição.
													</div>
									<?php 
												} 
											}
									?>
									
									
									<?php $abaCampanhas = 1035; include "abasCampanhasConfig.php"; ?>
									
									<div class="push10"></div> 
									
									<div  class="col-sm-12"	style="padding-left: 0;">

										<div class="col-xs-2" style="padding-left: 0;">
										<a class="btn btn-info btn-block addBox" data-url="action.php?mod=<?php echo fnEncode(1038)?>&id=<?php echo fnEncode($cod_empresa)?>&idx=<?php echo fnEncode($cod_persona)?>&pop=true" data-title="Persona / <?php echo $des_persona; ?>"><i class="fal fa-cogs fa fa-white hidden-xs"></i>
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
													<div class="notify-badge text-center" id="notificaPerfil"><span><?php echo $bl1_total; ?></span></div>
													<?php } ?>
													<i class="fal fa-user-edit fa-2x" style="margin: 10px 0 2px 0"></i>
													<h5 class="hidden-xs" style="margin: 3px 0 0 0">Perfil</h5>												
													</a>
												</li>
												
												<li class="vTab">				
													<a href="#produtos" data-toggle="tab">
													<?php if ($bl2_total>0) { ?>
													<div class="notify-badge text-center" id="notificaProdutos"><span><?php echo $bl2_total; ?></span></div>
													<?php } ?>
													<i class="fal fa-cubes fa-2x" style="margin: 10px 0 2px 0"></i>
													<h5 class="hidden-xs" style="margin: 3px 0 0 0">Produtos</h4>												
													</a>
												</li>
												
												<li class="vTab">				
													<a href="#frequencia" data-toggle="tab">
													<?php if ($bl3_total>0) { ?>
													<div class="notify-badge text-center" id="notificaFrequencia"><span><?php echo $bl3_total; ?></span></div>
													<?php } ?>
													<i class="fal fa-hourglass-end fa-2x" style="margin: 10px 0 2px 0"></i>
													<h5 class="hidden-xs" style="margin: 3px 0 0 0">Frequência e <br> Recência</h4>												
													</a>
												</li>
												
												<li class="vTab">				
													<a href="#valor" data-toggle="tab">
													<?php if ($bl4_total>0) { ?>
													<div class="notify-badge text-center" id="notificaValor"><span><?php echo $bl4_total; ?></span></div>
													<?php } ?>
													<i class="fal fa-money-check-alt fa-2x" style="margin: 10px 0 2px 0"></i>
													<h5 class="hidden-xs" style="margin: 3px 0 0 0">Valor</h4>												
													</a>
												</li>
												
												<li class="vTab">				
													<a href="#geolocalizacao" data-toggle="tab">
													<?php if ($bl5_total>0) { ?>
													<div class="notify-badge text-center" id="notificaGeo"><span><?php echo $bl5_total; ?></span></div>
													<?php } ?>
													<i class="fal fa-map-marker-alt fa-2x" style="margin: 10px 0 2px 0"></i>
													<h5 class="hidden-xs" style="margin: 3px 0 0 0">Geolocalização</h4>												
													</a>
												</li>

												<li class="vTab">				
													<a href="#engajamento" data-toggle="tab">
													<?php if ($bl6_total>0) { ?>
													<div class="notify-badge text-center" id="notificaEngaja"><span><?php echo $bl6_total; ?></span></div>
													<?php } ?>
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
													<h4 style="margin: 0 0 5px 0;"><span class="bolder">Configuração de Perfil</span></h4>
													<small style="font-size: 12px;">Quais as características do público que sua campanha quer atingir?</small>

													<div class="push20"></div> 

													<div class="row">
													
														<h5 style="margin: 0 0 5px 15px;"><span class="bold">Total geral de pessoas cadastradas</span></h5>
														<div class="push20"></div> 
													
														<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
															
															<div class="widget widget-default widget-item-icon">
																<div class="widget-item-left">
																	<span class="fal fa-users text-success"></span>
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
																	<span class="fal fa-male text-success"></span>
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
																	<span class="fal fa-female text-success"></span>
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
													
														<h5 style="margin: 0 0 5px 15px;"><span class="bold">Seleção da persona</span></h5>
														<div class="push20"></div> 
													  
														<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">

															<div class="widget widget-default widget-item-icon">
																<div class="widget-item-left">
																	<span class="fal fa-industry"></span>
																</div>                             
																<div class="widget-data">
																	<div class="widget-int"><?php echo number_format ($totalPJ,0,",","."); ?></div>
																	<div class="widget-title"> <?php fnCalculaporcento($totalPJ,$totalGeral); ?>% </div>
																	<div class="widget-subRight">
																	
																		<label class="switch">
																		<input type="checkbox" name="BL1_JURIDICO" id="BL1_JURIDICO" class="switch" value="S" <?php echo @$check_juridico; ?>  />
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
																	<span class="fal fa-male"></span>
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
																	<span class="fal fa-female"></span>
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
																	<span class="fal fa-child" style="font-size: 30px;"></span><span class="fal fa-male"></span>
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
																	<span class="fal fa-address-book"></span>
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
																			Com Cep
																			
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
																	<span class="fal fa-birthday-cake"></span>
																</div>                             
																<div class="widget-data">
																	<div class="widget-title">Aniversariantes</div>
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
																	<span class="fal fa-briefcase"></span>
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
								
														<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

															<div class="widget widget-default widget-item-icon" style="height: 190px;">
																<div class="widget-item-left">
																	<span class="fal fa-comments"></span>
																</div>                             
																<div class="widget-data">
																	<div class="widget-title">Comunicação</div>
																	<div class="widget-subtitle" style="padding: 10px 10px 0 0;">
																		
																		<div class="push10"></div>
																		
																		<div class="col-lg-2 col-md-2 col-sm-2col-xs-12 text-center borda">
																	
																			<label class="switch">
																			<input type="checkbox" name="BL1_LOG_FIDELIZADO" id="BL1_LOG_FIDELIZADO" class="switch" value="S"  <?php echo $check_log_fidelizado; ?> />
																			<span></span>
																			</label>
																			<div class="push10"></div>
																			Participa Fidelização
																			
																		</div>
																		
																		<div class="col-lg-2 col-md-2 col-sm-2col-xs-12 text-center">
																	
																			<label class="switch">
																			<input type="checkbox" name="BL1_LOG_EMAIL" id="BL1_LOG_EMAIL" class="switch" value="S" <?php echo $check_log_email; ?> />
																			<span></span>
																			</label>
																			<div class="push10"></div>
																			Recebe e-Mail
																			
																		</div>
																		
																		<div class="col-lg-2 col-md-2 col-sm-2col-xs-12 text-center">
																			
																			<label class="switch">
																			<input type="checkbox" name="BL1_LOG_SMS" id="BL1_LOG_SMS" class="switch" value="S" <?php echo $check_log_sms; ?> />
																			<span></span>
																			</label>
																			<div class="push10"></div>
																			Recebe SMS
																			
																		</div>
																		
																		<div class="col-lg-2 col-md-2 col-sm-2col-xs-12 text-center">

																			<label class="switch">
																			<input type="checkbox" name="BL1_LOG_TELEMARK" id="BL1_LOG_TELEMARK" class="switch" value="S" <?php echo $check_log_telemark; ?> />
																			<span></span>
																			</label>
																			<div class="push10"></div>
																			Recebe Telemarketing
																			
																		</div>
																		
																		<div class="col-lg-2 col-md-2 col-sm-2col-xs-12 text-center">
																			
																			<label class="switch">
																			<input type="checkbox" name="BL1_LOG_WHATSAPP" id="BL1_LOG_WHATSAPP" class="switch" value="S" <?php echo $check_log_whatsapp; ?> />
																			<span></span>
																			</label>
																			<div class="push10"></div>
																			Recebe Whatsapp

																		</div>
																		
																		<div class="col-lg-2 col-md-2 col-sm-2col-xs-12 text-center">
																			
																			<label class="switch">
																			<input type="checkbox" name="BL1_LOG_PUSH" id="BL1_LOG_PUSH" class="switch" value="S" <?php echo $check_log_push; ?> />
																			<span></span>
																			</label>
																			<div class="push10"></div>
																			Recebe Push
																			
																		</div>																	

																	</div>
																</div>
															</div> 												  
														
														</div>
														
													</div>

													<?php

														$sql = "SELECT COD_TPFILTRO, DES_TPFILTRO FROM TIPO_FILTRO
														WHERE COD_EMPRESA = $cod_empresa
														ORDER BY NUM_ORDENAC";
														$arrayQuery = mysqli_query(connTemp($cod_empresa,''),trim($sql));

														if(mysqli_num_rows($arrayQuery) > 0){
														$countFiltros = 0;
													?>
													

													<div class="row">
								
														<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

															<div class="widget widget-default widget-item-icon">
																<div class="widget-item-left">
																	<span class="fal fa-filter"></span>
																</div>                             
																<div class="widget-data">
																	<div class="widget-title">Filtros Dinâmicos</div>
																	<div class="widget-subtitle" style="padding: 20px 30px 20px 10px;">		

														<?php 
																	while($qrTipo = mysqli_fetch_assoc($arrayQuery)){
														?>

																		<div class="col-md-3">
																			<div class="form-group">
																				<label for="inputName" class="control-label"><?=$qrTipo['DES_TPFILTRO']?></label>
																				<div id="relatorioFiltro_<?=$countFiltros?>">
																					<input type="hidden" name="COD_TPFILTRO_<?=$countFiltros?>" id="COD_TPFILTRO_<?=$countFiltros?>" value="<?=$qrTipo['COD_TPFILTRO']?>">
																					<select data-placeholder="Selecione os filtros" name="COD_FILTRO_<?=$countFiltros?>[]" id="COD_FILTRO_<?=$qrTipo["COD_TPFILTRO"]?>" multiple="multiple" class="chosen-select-deselect last-chosen-link">
																						<option value=""></option>
														<?php
																						$sqlFiltro = "SELECT COD_FILTRO, DES_FILTRO FROM FILTROS_CLIENTE
																									  WHERE COD_TPFILTRO = ".$qrTipo['COD_TPFILTRO'];

																						$arrayFiltros = mysqli_query(connTemp($cod_empresa,''),trim($sqlFiltro));
																						while($qrFiltros = mysqli_fetch_assoc($arrayFiltros)){
														?>

																							<option value="<?=$qrFiltros['COD_FILTRO']?>"><?=$qrFiltros['DES_FILTRO']?></option>

														<?php 
																						}

																						
																						$sqlChosen = "SELECT COD_FILTRO FROM FILTROS_PERSONA
																										WHERE COD_PERSONA = $cod_persona AND COD_TPFILTRO =".$qrTipo['COD_TPFILTRO'];
																						$arrayChosen = mysqli_query(connTemp($cod_empresa,''),$sqlChosen);
																						$cod_filtros = "";

																						while($qrChosen = mysqli_fetch_assoc($arrayChosen)){
																							$cod_filtros .= $qrChosen['COD_FILTRO'].",";
																						}
																						$cod_filtros = rtrim($cod_filtros,",");

																							
														?>
																						<script>
																							var filtros = '<?php echo $cod_filtros; ?>';
																							
																							if(filtros != 0 && filtros != ""){

																								var sistemasUni = '<?php echo $cod_filtros; ?>';				
																								var sistemasUniArr = sistemasUni.split(',');				
																								//opções multiplas
																								for (var i = 0; i < sistemasUniArr.length; i++) {
																								  $("#COD_FILTRO_<?=$qrTipo['COD_TPFILTRO']?> option[value=" + sistemasUniArr[i] + "]").prop("selected", "true");				  
																								}
																								$("#COD_FILTRO_<?=$qrTipo['COD_TPFILTRO']?>").trigger("chosen:updated");

																							}
																							$("#COD_FILTRO_<?=$qrTipo['COD_TPFILTRO']?>").change(function(){
																								$.ajax({
																									method: 'POST',
																									url: 'ajxSalvaFiltrosPersona.do?id=<?=fnEncode($cod_empresa)?>',
																									data: $('#formulario').serialize(),
																									success:function(data){
																										console.log(data);
																									}
																								});
																							});
																						</script>
																			
																					</select>
																					<div class="help-block with-errors"></div>
																				</div>
																			</div>
																		</div>

														<?php 
																		$countFiltros++;
																	}
														?>


																	</div>
																</div>
															</div> 												  
														
														</div>
														
													</div>

																

													<?php 
														}
													?>																					

													<div class="push10"></div>
													
													<div class="row">
					
														<div class="push10"></div>
														<hr>	
														<div class="form-group text-right col-lg-12">
												
															  <button type="button" class="btn btn-default limpaPerfil"><i class="far fa-star-half-alt" aria-hidden="true"></i>&nbsp; Limpar Bloco</button>
															  <button type="button" class="btn btn-success atualiza" <?php echo $bloqueiaAlt; ?> ><i class="fal fa-check" aria-hidden="true"></i>&nbsp; Aplicar Filtros</button>
															
														</div>	

														<div class="push30"></div>														
														
													</div>
													
													
													<div class="row">
													
														<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

															<div class="widget widget-default widget-item-icon">
																<div class="widget-item-left">
																	<span class="fal fa-bar-chart"></span>
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
												<h4 style="margin: 0 0 5px 0;"><span class="bolder">Configuração de Produtos</span></h4>
												<small style="font-size: 12px;">Quais produtos sua campanha deseja promover?</small>
												
													<?php /////// Bloco Produtos //////// ?>	
													<?php include "personasProdutos.php"; ?>	
												
												</div>
												
												<!-- aba frequencia-->
												<div class="tab-pane" id="frequencia">
												<h4 style="margin: 0 0 5px 0;"><span class="bolder">Configuração de Frequência e Recência</span> </h4>
												<small style="font-size: 12px;">Qual é a frequencia de compra e/ou interação do público que você deseja atingir?</small>

													<?php /////// Bloco Frequência //////// ?>	
													<?php include "personasFrequencia.php"; ?>	
													
												</div>
																						
												<!-- aba valor-->
												<div class="tab-pane" id="valor">
												<h4 style="margin: 0 0 5px 0;"><span class="bolder">Configuração de Valor</span></h4>
												<small style="font-size: 12px;">Quais os valores de consumo e a forma de pagamento do público alvo?</small>

													<?php /////// Bloco Valor //////// ?>	
													<?php include "personasValor.php"; ?>	
												
												</div>
												
												<!-- aba geolocalizacao-->
												<div class="tab-pane" id="geolocalizacao">
												<h4 style="margin: 0 0 5px 0;"><span class="bolder">Configuração de Geolocalização</span></h4>
												<small style="font-size: 12px;">Qual a localização do público que você deseja atingir?</small>
												
													<?php //////// Bloco Geo /////// ?>	
													<?php include "personasGeo_V2.php"; ?>	
												
												</div>			
													
												<!-- aba engajamento-->
												
												<div class="tab-pane" id="engajamento">
												<h4 style="margin: 0 0 5px 0;"><span class="bolder">Configuração de Engajamento</span></h4>
												<small style="font-size: 12px;">Qual é o perfil de interação do público que responderá melhor a sua campanha?</small>
												
													<?php //////// Bloco Engajamento /////// ?>	
													<?php include "personasEngaja_V2.php"; ?>	
													
												</div>											
												
												
												
											  </div>
											  
											</div>

											<div class="clearfix"></div>
																					
										</div>
																		
										<div class="push10"></div>
										<hr>	
										<div class="form-group text-right col-md-8">

											<a class="btn btn-lg btn-info exportarCSV pull-left">
												<div class="notify-badge text-center pos posHidden" id="notificaExportar">
													<span class="fas fa-info"></span>
												</div>
												<i class="fal fa-file-excel" aria-hidden="true"></i>&nbsp;Exportar
											</a>

										</div>
										<div class="col-md-4 text-right">
											<button type="reset" class="btn btn-lg btn-default"><i class="far fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
											<?php if ($cod_empresa == "0") {?>
												<button type="submit" name="CAD" id="CAD" class="btn btn-lg btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar Persona</button>
											<?php } else { ?>
												<button type="submit" name="ALT" id="ALT" class="btn btn-lg btn-primary getBtn atualizaPersona" style="border-radius: 6px 0px 0px 6px;" <?php echo $bloqueiaAlt; ?> ><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Atualizar Persona </button>
												<div class="btn-group dropdown dropleft pull-right">
													<a class="btn btn-primary btn-lg dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="border-radius: 0px 6px 6px 0px; padding-left: 15px; padding-right: 15px;">&nbsp;<span class="fal fa-angle-down"></span>&nbsp;</a>
													<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
														<?php if($log_congela == 'S'){ ?>
														<li><a href="javascript:void(0)" class="f21 descongela"><span class="fal fa-play-circle"></span>&nbsp;&nbsp; Descongelar Persona</a></li>
														<?php }else{ ?>
														<li><a href="javascript:void(0)" class="f21 congela"><span class="fal fa-pause-circle"></span>&nbsp;&nbsp; Congelar Persona</a></li>
														<?php } ?>
														<!-- <li class="divider"></li> -->
														<!-- <li><a tabindex="-1" href="#">Separated link</a></li> -->
													</ul>
												</div>
											<?php } ?>
										</div>
										
										<input type="hidden" name="CONTROLE" id="CONTROLE" value="0">
										<input type="hidden" name="LOG_CONGELA" id="LOG_CONGELA" value="<?=$log_congela?>">
										<input type="hidden" name="COD_PERSONA" id="COD_PERSONA" value="<?php echo $cod_persona; ?>">
										<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
										<input type="hidden" name="COUNT_FILTROS" id="COUNT_FILTROS" value="<?=$countFiltros?>">
										<input type="hidden" name="opcao" id="opcao" value="">
										<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
										<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">												
										
									</form>
									
									<!-- totalizador personas -->
									<div class="scrollPersona">
										<div class="widget widget-primary widget-item-icon">
											<div class="widget-item-left">
												<span class="fal fa-users"></span>
											</div>
											<div class="widget-data">
												<div class="widget-int num-count" id="div_Total" style="text-align: center; font-size: 40px; padding-top: 10px;"><?php echo number_format ( $totalIni,0,",","."); ?></div>
												<div class="widget-title" style="text-align: center;">Clientes Únicos Selecionados</div>
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

			$(".descongela").click(function(){
				$("#LOG_CONGELA").val('N');
				$(".atualizaPersona").removeAttr('disabled').click();
			});	

			$(".congela").click(function(){
				$("#LOG_CONGELA").val('S');
				$(".atualizaPersona").click();
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
															console.log(response);
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
										$('.atualizaPersona').click();
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
											console.log(response);
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
			usuLimitado = "<?=$usuLimitado?>";
			if(usuLimitado == "false"){
				calcPersona();
			}else{
				cod_univend = $("#BL5_COD_UNIVE").val(),
				cod_univend_master = $("#COD_UNIVEND_MASTER").val();
				if(cod_univend || cod_univend_master != ""){
					calcPersona();
				}else{
					$.alert({
	                    title: "Nenhuma unidade selecionada",
	                    content: 'Selecione uma unidade no bloco "Geolocalização"',
	                    buttons: {
							Ok: function () {
								
							}
						}
	                });
				}
			}
		});

		
		$("#BL1_IDADES").mouseup(function () {
			//alert("passou");
		});

		function calcPersona() {
			$.ajax({
				type: "POST",
				url: "ajxCalcPersona_V2.php",
				data: $('#formulario').serialize(),
				beforeSend:function(){
					$('#div_Total').html('<div class="loading" style="width: 100%;"></div>');
				},
				success:function(data){
					console.log(data);
					$("#div_Total").html(data); 
				},
				error:function(data){
					$('#div_Total').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
					console.log(data);
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
			$('#BL1_MASCULINO').prop('checked', false);
			$("#BL1_FEMININO").val("S");
			$('#BL1_FEMININO').prop('checked', false);			
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
			$("#BL1_IDADES").data("ionRangeSlider").update({from:0,to:150});

			$("#BL1_LOG_FIDELIZADO").val("S");
			$('#BL1_LOG_FIDELIZADO').prop('checked', true);
			$("#BL1_LOG_EMAIL").val("N");
			$('#BL1_LOG_EMAIL').prop('checked', false);
			$("#BL1_LOG_SMS").val("N");
			$('#BL1_LOG_SMS').prop('checked', false);
			$("#BL1_LOG_TELEMARK").val("N");
			$('#BL1_LOG_TELEMARK').prop('checked', false);
			$("#BL1_LOG_WHATSAPP").val("N");
			$('#BL1_LOG_WHATSAPP').prop('checked', false);
			$("#BL1_LOG_PUSH").val("N");
			$('#BL1_LOG_PUSH').prop('checked', false);			

			$("#notificaPerfil").hide();
			
		});		
				
	</script>
	
	<script src="js/plugins/ion.rangeSlider.js"></script>
   
    <script src="js/gauge.coffee.js" type="text/javascript"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.min.js"></script> 
	<script src="js/pie-chart.js"></script>
    <script src="js/plugins/Chart_Js/utils.js"></script>
    	
    <script>
      // Chart.defaults.global.legend = {
      //   enabled: false
      // };

      // Bar chart
	  // gentelella
      // var ctx = document.getElementById("mybarChart");
      // var mybarChart = new Chart(ctx, {
      //   type: 'bar',
      //   data: {
      //    labels: [
		  		// 	["18-20","(<?=$valor1?>)"],
		  		// 	["21-30","(<?=$valor2?>)"],
		  		// 	["31-40","(<?=$valor3?>)"],
		  		// 	["41-50","(<?=$valor4?>)"],
		  		// 	["51-60","(<?=$valor5?>)"],
		  		// 	["61-70","(<?=$valor6?>)"],
		  		// 	["+70","(<?=$valor7?>)"]
		  		//   ],
      //     datasets: [{
      //       label: 'Clientes',
      //       backgroundColor: "#85C1E9",
      //       data: [<?php echo $valor1 ?>, 
				  //  <?php echo $valor2 ?>, 
				  //  <?php echo $valor3 ?>, 
				  //  <?php echo $valor4 ?>, 
				  //  <?php echo $valor5 ?>, 
				  //  <?php echo $valor6 ?>, 
				  //  <?php echo $valor7 ?>
				  //  ]
      //     }]
      //   },

      //   options: {
      //     scales: {
      //       yAxes: [{
      //         ticks: {
      //           beginAtZero: true
      //         }
      //       }]
      //     }
      //   }
      // });

      //grouped
		var barchartgrouped = new Chart(document.getElementById("mybarChart"), {
			type: 'bar',
			data: {
			  labels: [
			  			["18-20","<?=$valor1?> pessoas","<?=fnValor($percIdade1,0)?>%"],
			  			["21-30","<?=$valor2?> pessoas","<?=fnValor($percIdade2,0)?>%"],
			  			["31-40","<?=$valor3?> pessoas","<?=fnValor($percIdade3,0)?>%"],
			  			["41-50","<?=$valor4?> pessoas","<?=fnValor($percIdade4,0)?>%"],
			  			["51-60","<?=$valor5?> pessoas","<?=fnValor($percIdade5,0)?>%"],
			  			["61-70","<?=$valor6?> pessoas","<?=fnValor($percIdade6,0)?>%"],
			  			["+70","<?=$valor7?> pessoas","<?=fnValor($percIdade7,0)?>%"]
			  		  ],
			  datasets: [
				{					  
				  backgroundColor: "#85C1E9",					 
				  data: [<?=$valor1?>, <?=$valor2?>, <?=$valor3?>, <?=$valor4?>, <?=$valor5?>, <?=$valor6?>, <?=$valor7?>]
				},
			  ]
			},
			options: {
				legend: {
		            display: false
		         },
			 //  title: {
				// display: true,
				// text: ''
			 //  },
			   tooltips: {
			    callbacks: {
			        label: function (t, d) {
			         	if(parseInt(t.yLabel) >= 1000){
			                return t.yLabel.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
			            } else {
			                return t.yLabel;
			            }
				        // return t.yLabel
				  	}
				}
			   },
			  scales: {						
					yAxes: [{
						ticks: {
							callback: function(value, index, values) {
				              if(parseInt(value) >= 1000){
				                return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
				              } else {
				                return value;
				              }
				            }
						}													
					}]					
				},
				animation: {
					animateScale: true,
					animateRotate: true,
					onComplete : function(){   
						// $("input[name=barchartgrouped]").val(barchartgrouped.toBase64Image());
						// botaoPDF();
					}
				}
			}
		});

    </script>
    <!-- /Chart.js -->
