<?php

	//echo fnDebug('true');

	$hashLocal = mt_rand();	
	
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

			$cod_empresa = fnLimpacampoZero($_REQUEST['ID']);
			$cod_cadastr = $_SESSION["SYS_COD_USUARIO"];
			$nom_empresa = fnLimpacampo($_REQUEST['NOM_EMPRESA']);
			$des_abrevia = fnLimpacampo($_REQUEST['DES_ABREVIA']);
			$nom_respons = fnLimpacampo($_REQUEST['NOM_RESPONS']);
			$num_cgcecpf = fnLimpacampo($_REQUEST['NUM_CGCECPF']);
			$des_sufixo = str_replace (" ","",fnAcentos(fnLimpacampo($_REQUEST['DES_SUFIXO'])));
			$cod_estatus = fnLimpacampo($_REQUEST['COD_ESTATUS']);
			if (empty($_REQUEST['LOG_ATIVO'])) {$log_ativo='N';}else{$log_ativo=$_REQUEST['LOG_ATIVO'];}
			if (empty($_REQUEST['LOG_PRECUNI'])) {$log_precuni='N';}else{$log_precuni=$_REQUEST['LOG_PRECUNI'];}
			if (empty($_REQUEST['LOG_ESTOQUE'])) {$log_estoque='N';}else{$log_estoque=$_REQUEST['LOG_ESTOQUE'];}
			if (empty($_REQUEST['LOG_CONFIGU'])) {$log_configu='N';}else{$log_configu=$_REQUEST['LOG_CONFIGU'];}
			if (empty($_REQUEST['TIP_REGVENDA'])) {$tip_regvenda='1';}else{$tip_regvenda=$_REQUEST['TIP_REGVENDA'];}
			$tip_contabil = fnLimpacampo($_REQUEST['TIP_CONTABIL']);
			$num_escrica = fnLimpacampo($_REQUEST['NUM_ESCRICA']);
			$nom_fantasi = fnLimpacampo($_REQUEST['NOM_FANTASI']);
			$num_telefon = fnLimpacampo($_REQUEST['NUM_TELEFON']);
			$num_celular = fnLimpacampo($_REQUEST['NUM_CELULAR']);
			$des_enderec = fnLimpacampo($_REQUEST['DES_ENDEREC']);
			$num_enderec = fnLimpacampo($_REQUEST['NUM_ENDEREC']);
			$des_complem = fnLimpacampo($_REQUEST['DES_COMPLEM']);
			$des_bairroc = fnLimpacampo($_REQUEST['DES_BAIRROC']);
			$num_cepozof = fnLimpacampo($_REQUEST['NUM_CEPOZOF']);
			$nom_cidadec = fnLimpacampo($_REQUEST['NOM_CIDADEC']);
			$cod_estadof = fnLimpacampo($_REQUEST['COD_ESTADOF']);
			$tip_retorno = fnLimpacampo($_REQUEST['TIP_RETORNO']);
			$tip_header = fnLimpacampo($_REQUEST['TIP_HEADER']);
			$des_alinham = fnLimpacampo($_REQUEST['DES_ALINHAM']);
			$des_logo = fnLimpaCampo($_REQUEST['DES_LOGO']);
			$des_imgback = fnLimpaCampo($_REQUEST['DES_IMGBACK']);
			$cod_plataforma = fnLimpacampoZero($_REQUEST['COD_PLATAFORMA']);
			$cod_versaointegra = fnLimpacampoZero($_REQUEST['COD_VERSAOINTEGRA']);
			$qtd_chartkn = fnLimpacampoZero($_REQUEST['QTD_CHARTKN']);
			$tip_token = fnLimpacampoZero($_REQUEST['TIP_TOKEN']);
			$pct_parceiro = fnLimpacampo($_REQUEST['PCT_PARCEIRO']);

			$filtro = fnLimpaCampo($_POST['VAL_PESQUISA']);			
			$val_pesquisa = fnLimpaCampo($_POST['INPUT']);
			
			//array dos sistemas da empresas
			if (isset($_POST['COD_SISTEMAS'])){
				$Arr_COD_SISTEMAS = $_POST['COD_SISTEMAS'];
				//print_r($Arr_COD_SISTEMAS);			 
			 
			   for ($i=0;$i<count($Arr_COD_SISTEMAS);$i++) 
			   { 
				@$cod_sistemas.= $Arr_COD_SISTEMAS[$i].",";
			   } 
			   
			   $cod_sistemas = substr($cod_sistemas,0,-1);
				
			}else{$cod_sistemas = "";}
			$cod_master = fnLimpacampo($_REQUEST['COD_MASTER']);
			$cod_layout = fnLimpacampo($_REQUEST['COD_LAYOUT']);
			$cod_segment = fnLimpacampo($_REQUEST['COD_SEGMENT']);
			
			if (empty($_REQUEST['LOG_CONSEXT'])) {$log_consext='N';}else{$log_consext=$_REQUEST['LOG_CONSEXT'];}
			if (empty($_REQUEST['LOG_AUTOCAD'])) {$log_autocad='N';}else{$log_autocad=$_REQUEST['LOG_AUTOCAD'];}
			if (empty($_REQUEST['LOG_TOKEN'])) {$log_token='N';}else{$log_token=$_REQUEST['LOG_TOKEN'];}
			if (empty($_REQUEST['LOG_CADTOKEN'])) {$log_cadtoken='N';}else{$log_cadtoken=$_REQUEST['LOG_CADTOKEN'];}
			$cod_chaveco = fnLimpacampo($_REQUEST['COD_CHAVECO']);			
			$tip_campanha = fnLimpacampo($_REQUEST['TIP_CAMPANHA']);			

			if (empty($_REQUEST['LOG_INTEGRADORA'])) {$log_integradora ='N';}else{$log_integradora =$_REQUEST['LOG_INTEGRADORA'];}
			$des_patharq = fnLimpacampo($_REQUEST['DES_PATHARQ']);			
			$cod_integradora = fnLimpacampoZero($_REQUEST['COD_INTEGRADORA']);
			$site = fnLimpacampo($_REQUEST['SITE']);
			$des_coment = fnLimpacampo($_REQUEST['DES_COMENT']);
			
			$num_decimais = fnLimpacampo($_REQUEST['NUM_DECIMAIS']);			
			$num_decimais_b = fnLimpacampo($_REQUEST['NUM_DECIMAIS_B']);			
			$cod_dataws = fnLimpacampoZero($_REQUEST['COD_DATAWS']);			
			
			if (empty($_REQUEST['DAT_PRODUCAO'])) {$dat_producao ='NULL';}else{$dat_producao = "'".fnDataSql($_REQUEST['DAT_PRODUCAO'])."'";}
			$cod_consultor = fnLimpacampoZero($_REQUEST['COD_CONSULTOR']);			
			if (empty($_REQUEST['LOG_WS'])) {$log_ws ='N';}else{$log_ws =$_REQUEST['LOG_WS'];}
			
			if (empty($_REQUEST['LOG_PONTUAR'])) {$log_pontuar ='N';}else{$log_pontuar = $_REQUEST['LOG_PONTUAR'];}
			if (empty($_REQUEST['LOG_ATIVCAD'])) {$log_ativcad ='N';}else{$log_ativcad = $_REQUEST['LOG_ATIVCAD'];}
			if (empty($_REQUEST['LOG_AVULSO'])) {$log_avulso ='N';}else{$log_avulso = $_REQUEST['LOG_AVULSO'];}
			if (empty($_REQUEST['LOG_CATEGORIA'])) {$log_categoria ='N';}else{$log_categoria = $_REQUEST['LOG_CATEGORIA'];}
			if (empty($_REQUEST['LOG_ALTVENDA'])) {$log_altvenda ='N';}else{$log_altvenda = $_REQUEST['LOG_ALTVENDA'];}
			if (empty($_REQUEST['LOG_QUALICAD'])) {$log_qualicad='N';}else{$log_qualicad=$_REQUEST['LOG_QUALICAD'];}
			$log_cadvendedor = fnLimpacampoZero($_REQUEST['LOG_CADVENDEDOR']);
			if (empty($_REQUEST['LOG_PDVMANU'])) {$log_pdvmanu ='0';}else{$log_pdvmanu = $_REQUEST['LOG_PDVMANU'];}
			if (empty($_REQUEST['LOG_CREDAVULSO'])) {$log_credavulso='N';}else{$log_credavulso=$_REQUEST['LOG_CREDAVULSO'];}
			if (empty($_REQUEST['LOG_NEGATIVO'])) {$log_negativo='N';}else{$log_negativo=$_REQUEST['LOG_NEGATIVO'];}
                        
			//fnEscreve($log_ativo);

			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];			
						
			if ($opcao != ''){
				
				$sql = "CALL SP_ALTERA_EMPRESAS_FULL (
				 '".$cod_empresa."', 
				 '".$cod_cadastr."', 
				 '".$nom_empresa."', 
				 '".$des_abrevia."', 
				 '".$nom_respons."', 
				 '".fnLimpaDoc($num_cgcecpf)."', 
				 '".$log_ativo."', 
				 '".$cod_estatus."', 
				 '".$num_escrica."', 
				 '".$nom_fantasi."', 
				 '".$num_telefon."', 
				 '".$num_celular."', 
				 '".$des_enderec."', 
				 '".$num_enderec."', 
				 '".$des_complem."', 
				 '".$des_bairroc."', 				 
				 '".$num_cepozof."',				 
				 '".$nom_cidadec."',    
				 '".$cod_estadof."',    
				 '".$cod_sistemas."',    
				 '".$cod_master."',    
				 '".$cod_layout."',  
				 '".$log_precuni."',    
				 '".$log_estoque."',    
				 '".$cod_segment."',    
				 '".$des_sufixo."', 
				 '".$log_consext."', 
				 '".$log_autocad."', 
				 '".$cod_chaveco."', 
				 '".$tip_contabil."', 
				 '".$log_configu."', 
				 '".$log_integradora."', 
				 '".$des_patharq."', 
				 '".$cod_integradora."', 
				 '".$site."', 
				 '".$des_coment."', 
				 '".$tip_regvenda."', 
				 '".$num_decimais."', 
				 '".$num_decimais_b."', 
				 ".$dat_producao.", 
				 '".$cod_consultor."', 
				 '".$log_ws."', 
				 '".$log_pontuar."', 
				 '".$tip_retorno."', 
				 '".$tip_header."', 
				 '".$des_alinham."', 
				 '".$des_logo."',
				 '".$des_imgback."',
                 '".$log_ativcad."',  
                 '".$log_avulso."',  
                 '".$log_categoria."',  
                 '".$log_altvenda."',  
                 '".$cod_dataws."', 
                 '".$cod_plataforma."', 
                 '".$cod_versaointegra."', 
                 '".$log_qualicad."', 
                 '".$log_cadvendedor."', 
                 '".$log_pdvmanu."', 
                 '".$log_credavulso."', 
                 '".$log_token."', 
                 '".$log_cadtoken."', 
                 '".$log_negativo."', 
                 '".fnValorsql($pct_parceiro)."',
				 '".$tip_campanha."', 
				 '".$qtd_chartkn."', 
				 '".$tip_token."', 
				 '".$opcao."'    
				) ";

				//fnEscreve($sql);
				
				mysqli_query($connAdm->connAdm(),trim($sql));			
				
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
      
	//fnMostraForm();
	//fnEscreve($filtro);

        
?>
<style>
table a:not(.btn), .table a:not(.btn) {
    text-decoration: none;
}
table a:not(.btn):hover, .table a:not(.btn):hover {
    text-decoration: underline;
}
</style>
		
					<div class="push30"></div> 
					
					<div class="row">				
					
						<div class="col-md12 margin-bottom-30">
							<!-- Portlet -->
							<div class="portlet portlet-bordered">
								<div class="portlet-title">
									<div class="caption">
										<i class="glyphicon glyphicon-calendar"></i>
										<span class="text-primary"> <?php echo $NomePg; ?></span>
									</div>
									<?php include "atalhosPortlet.php"; ?>
								</div>
								<div class="portlet-body">
			
									<?php if ($msgRetorno <> '') { ?>	
									<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
									<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									 <?php echo $msgRetorno; ?>
									</div>
									<?php } ?>	
									
									<div class="push30"></div> 
			
									<div class="login-form">
									
										<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
																	
										<fieldset>
											<legend>Configurações de Acesso</legend> 
											
												<div class="row">	

													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Empresa <br/>Ativa </label> 
															<div class="push5"></div>
																<label class="switch switch-small">
																<input type="checkbox" name="LOG_ATIVO" id="LOG_ATIVO" class="switch switch-small" value="S" >
																<span></span>
																</label>
														</div>
													</div>

													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Consulta Automática<br/> de Cadastro (Externa)</label> 
															<div class="push5"></div>
																<label class="switch switch-small">
																<input type="checkbox" name="LOG_CONSEXT" id="LOG_CONSEXT" class="switch switch-small" value="S" >
																<span></span>
																</label>
														</div>
													</div>

													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Cadastro Automático <br/>de Clientes</label> 
															<div class="push5"></div>
																<label class="switch switch-small">
																<input type="checkbox" name="LOG_AUTOCAD" id="LOG_AUTOCAD" class="switch switch-small" value="S" >
																<span></span>
																</label>
														</div>
													</div>
													
													<div class="col-md-2">
													<div class="disabledBlock"></div>
														<div class="form-group">
															<label for="inputName" class="control-label">Controle de <br/>Preço por Loja </label> 
															<div class="push5"></div>
																<label class="switch switch-small">
																<input type="checkbox" name="LOG_PRECUNI" id="LOG_PRECUNI" class="switch switch-small" value="S" >
																<span></span>
																</label>
														</div>
													</div>
													
													<div class="col-md-2">
													<div class="disabledBlock"></div>
														<div class="form-group">
															<label for="inputName" class="control-label">Controla <br/>Estoque </label> 
															<div class="push5"></div>
																<label class="switch switch-small">
																<input type="checkbox" name="LOG_ESTOQUE" id="LOG_ESTOQUE" class="switch switch-small" value="S" >
																<span></span>
																</label>
														</div>
													</div>	
													<!---->
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Empresa <br/>Integradora </label> 
															<div class="push5"></div>
																<label class="switch switch-small">
																<input type="checkbox" name="LOG_INTEGRADORA" id="LOG_INTEGRADORA" class="switch switch-small" value="S" >
																<span></span>
																</label>
														</div>
													</div>
													
												</div>
												
												<div class="push10"></div>
												
												<div class="row">
												
													<div class="col-md-2">
														<div class="disabledBlock"></div>
														<div class="form-group">
															<label for="inputName" class="control-label">Banco de <br/>Dados </label> 
															<div class="push5"></div>
																<label class="switch switch-small">
																<input type="checkbox" name="COD_DATABASE" id="COD_DATABASE" class="switch switch-small" value="S" >
																<span></span>
																</label>
														</div>
													</div>
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Ativar<br/>Log WS</label> 
															<div class="push5"></div>
																<label class="switch switch-small">
																<input type="checkbox" name="LOG_WS" id="LOG_WS" class="switch switch-small" value="S" >
																<span></span>
																</label>
														</div>
													</div>	
													
													<div class="col-md-2">
														<div class="disabledBlock"></div>
														<div class="form-group">
															<label for="inputName" class="control-label">Set Up<br/>Completo</label> 
															<div class="push5"></div>
																<label class="switch switch-small">
																<input type="checkbox" name="LOG_CONFIGU" id="LOG_CONFIGU" class="switch switch-small" value="S" >
																<span></span>
																</label>
														</div>
													</div>	
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Pontuar <br/>Funcionários</label> 
															<div class="push5"></div>
																<label class="switch switch-small">
																<input type="checkbox" name="LOG_PONTUAR" id="LOG_PONTUAR" class="switch switch-small" value="S" >
																<span></span>
																</label>
														</div>
													</div>	
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Pontuar após<br/>ativação de cadastro</label> 
															<div class="push5"></div>
																<label class="switch switch-small">
																<input type="checkbox" name="LOG_ATIVCAD" id="LOG_ATIVCAD" class="switch switch-small" value="S" >
																<span></span>
																</label>
														</div>
													</div>														
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Permitir<br/>venda avulsa</label> 
															<div class="push5"></div>
																<label class="switch switch-small">
																<input type="checkbox" name="LOG_AVULSO" id="LOG_AVULSO" class="switch switch-small" value="S" >
																<span></span>
																</label>
														</div>
													</div>

												</div>
												
												<div class="push10"></div>
												
												<div class="row">
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Possui categorização<br/>de clientes</label> 
															<div class="push5"></div>
																<label class="switch switch-small">
																<input type="checkbox" name="LOG_CATEGORIA" id="LOG_CATEGORIA" class="switch switch-small" value="S" >
																<span></span>
																</label>
														</div>
													</div>
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Permite alteração<br/>de venda</label> 
															<div class="push5"></div>
																<label class="switch switch-small">
																<input type="checkbox" name="LOG_ALTVENDA" id="LOG_ALTVENDA" class="switch switch-small" value="S" >
																<span></span>
																</label>
														</div>
													</div>	
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Controle de Qualidade<br/>de Cadastros</label> 
															<div class="push5"></div>
																<label class="switch switch-small">
																<input type="checkbox" name="LOG_QUALICAD" id="LOG_QUALICAD" class="switch switch-small" value="S" >
																<span></span>
																</label>
														</div>
													</div>
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Créditos manuais<br/>no PDV virtual</label> 
															<div class="push5"></div>
																<label class="switch switch-small">
																<input type="checkbox" name="LOG_PDVMANU" id="LOG_PDVMANU" class="switch switch-small" value="1" >
																<span></span>
																</label>
														</div>
													</div>

													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Crédito Avulso</label> 
															<div class="push5"></div>
																<label class="switch switch-small">
																<input type="checkbox" name="LOG_CREDAVULSO" id="LOG_CREDAVULSO" class="switch switch-small" value="S" >
																<span></span>
																</label>
														</div>
													</div>
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Permite Saldo Negativo?</label> 
															<div class="push5"></div>
																<label class="switch switch-small">
																<input type="checkbox" name="LOG_NEGATIVO" id="LOG_NEGATIVO" class="switch switch-small" value="S" >
																<span></span>
																</label>
														</div>
													</div>
													
												</div>
												
												<div class="push10"></div>
												
												<div class="row">

													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Cadastro com Token</label> 
															<div class="push5"></div>
																<label class="switch switch-small">
																<input type="checkbox" name="LOG_CADTOKEN" id="LOG_CADTOKEN" class="switch switch-small" value="S" >
																<span></span>
																</label>
														</div>
													</div>	

													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Resgate com Token</label> 
															<div class="push5"></div>
																<label class="switch switch-small">
																<input type="checkbox" name="LOG_TOKEN" id="LOG_TOKEN" class="switch switch-small" value="S" >
																<span></span>
																</label>
														</div>
													</div>	

												</div>

												<div class="push10"></div>

												<div class="row">				
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label required">Código</label>
															<input type="text" class="form-control input-sm leitura" readonly="readonly" name="ID" id="ID" value="">
														</div>
													</div>
													
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label required">Nome da Empresa</label>
															<input type="text" class="form-control input-sm" name="NOM_EMPRESA" id="NOM_EMPRESA" maxlength="100" data-error="Campo obrigatório" required>
															<div class="help-block with-errors"></div>
														</div>
													</div>
													
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label required">Nome Fantasia</label>
															<input type="text" class="form-control input-sm" name="NOM_FANTASI" id="NOM_FANTASI" maxlength="40" data-error="Campo obrigatório" required>
															<div class="help-block with-errors"></div>
														</div>
													</div>
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Abreviação</label>
															<input type="text" class="form-control input-sm" name="DES_ABREVIA" id="DES_ABREVIA" maxlength="5" data-error="Campo obrigatório">
															<div class="help-block with-errors"></div>
														</div>
													</div>
													
												</div>	
												
												<div class="row">
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label required">Status</label>
																<select data-placeholder="Selecione um status" name="COD_ESTATUS" id="COD_ESTATUS" class="chosen-select-deselect" required>
																	<option value=""></option>
																	<?php 
																	
																		$sql = "SELECT * FROM STATUSSISTEMA ORDER BY DES_STATUS ";
																		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
																	
																		while ($qrLista = mysqli_fetch_assoc($arrayQuery))
																		  {														
																			echo"
																				  <option value='".$qrLista['COD_STATUS']."'>".$qrLista['DES_STATUS']."</option> 
																				"; 
																			  }											
																	?> 
																</select>
																<div class="help-block with-errors"></div>
														</div>
													</div>
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label required">Sufixo da Empresa</label>
															<input type="text" class="form-control input-sm" name="DES_SUFIXO" id="DES_SUFIXO" maxlength="100" value="" data-error="Campo obrigatório" required>
															<div class="help-block with-errors"></div>
														</div>
													</div>

													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label required">Chave Identificação</label>
																<select data-placeholder="Selecione a chave de identificação" name="COD_CHAVECO" id="COD_CHAVECO" class="chosen-select-deselect" required>
																	<option value=""></option>
																	<?php 

																		if ($_SESSION["SYS_COD_MASTER"] == "2" ) {
																		$sql = "select * from CHAVECADASTRO order by DES_CHAVECO
																		";
																		
																		}else {
																		$sql = "select * from CHAVECADASTRO where COD_CHAVECO <> 6 order by DES_CHAVECO
																		";
																		}

																		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
																	
																		while ($qrListaChaveCad = mysqli_fetch_assoc($arrayQuery))
																		  {	
																	  
																			echo"
																				  <option value='".$qrListaChaveCad['COD_CHAVECO']."'>".$qrListaChaveCad['DES_CHAVECO']."</option> 
																				"; 
																			  }											
																	?>	
																</select>
															<div class="help-block with-errors"></div>
														</div>
													</div>
												
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label required">Tipo Principal de Campanhas</label>
																<select data-placeholder="Selecione um tipo de vantagem" name="TIP_CAMPANHA" id="TIP_CAMPANHA" class="chosen-select-deselect requiredChk" required>
																	<option value="">&nbsp;</option>					
																	<?php																	
																		$sql = "select * from TIPOCAMPANHA order by NUM_ORDENAC ";
																		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
																	
																		while ($qrListaVantagem = mysqli_fetch_assoc($arrayQuery))
																		  {	

																			if ($qrListaVantagem['LOG_ATIVO'] == 'N'){ $desabilitado = "disabled";}
																			else {$desabilitado = "";}
																	  
																			echo"
																				  <option value='".$qrListaVantagem['COD_TPCAMPA']."' ".$desabilitado." >".$qrListaVantagem['NOM_TPCAMPA']."</option> 
																				"; 
																			  }											
																	?>	
																</select> 
															<div class="help-block with-errors"></div>
														</div>
													</div>
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Contabilização do Resgate</label>
																<select data-placeholder="Selecione a forma de contabilização" name="TIP_CONTABIL" id="TIP_CONTABIL" class="chosen-select-deselect">
																	<option value=""></option>
																	<option value="DESC">Como desconto</option>
																	<option value="RESG">Forma de pagamento (resgate)</option>
																</select>
															<div class="help-block with-errors"></div>
														</div>
													</div>	
																				
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Regra de Entrada de Venda</label>
																<select data-placeholder="Tipo da entrada de venda" name="TIP_REGVENDA" id="TIP_REGVENDA" class="chosen-select-deselect">
																	<option value=""></option>
																	<option value="1">Crítica Padrão</option>
																	<option value="2">Permitir data/hora iguais</option>
																	<option value="3">Permitir PDV iguais</option>
                                                                    <option value="4">Permitir PDV iguais se Loja for Diferente</option>
																</select>
															<div class="help-block with-errors"></div>
														</div>
													</div>
													
												</div>
												
												<div class="row">
																								
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label required">Casas Decimais</label>
																<select data-placeholder="Selecione um decimal" name="NUM_DECIMAIS" id="NUM_DECIMAIS" class="chosen-select-deselect requiredChk" required>
																	<option value="2">2</option>
																	<option value="3">3</option>
																	<option value="4">4</option>
																	<option value="5">5</option>
                                                                                                                                      
																</select>
															<div class="help-block with-errors"></div>
														</div>
													</div>

													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label required">Casas Decimais(Bunker)</label>
																<select data-placeholder="Selecione um decimal" name="NUM_DECIMAIS_B" id="NUM_DECIMAIS_B" class="chosen-select-deselect requiredChk" required>
																	<option value="0">0</option>
																	<option value="2">2</option>
																	<option value="3">3</option>
																	<option value="4">4</option>
																	<option value="5">5</option>
                                                                                                                                      
																</select>
															<div class="help-block with-errors"></div>
														</div>
													</div>
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label required">Visualização / Retorno </label>
																<select data-placeholder="Selecione um tipo de visualização dos retornos" name="TIP_RETORNO" id="TIP_RETORNO" class="chosen-select-deselect requiredChk" required>
																	<option value=""></option>
																	<option value="1">Valor inteiro</option>
																	<option value="2">Valor decimal</option>
																</select>
															<div class="help-block with-errors">webservices/relatórios</div>
														</div>
													</div>
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label required">Formato de Data </label>
																<select data-placeholder="Selecione um formato de data" name="COD_DATAWS" id="COD_DATAWS" class="chosen-select-deselect requiredChk" required>
																	<option value=""></option>
																	<?php 
																	
																		$sql = "select * from DATAWS order by COD_DATAWS";
																		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
																	
																		while ($qrListaTipoData = mysqli_fetch_assoc($arrayQuery))
																		  {	
																	  
																			echo"
																				  <option value='".$qrListaTipoData['COD_DATAWS']."'>".$qrListaTipoData['FORMATO_WEB']."</option> 
																				"; 
																			  }											
																	?>	
																</select>
															<div class="help-block with-errors">entrada de webservices</div>
														</div>
													</div>													
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Data Produção</label>
															
															<div class="input-group date datePicker" id="DAT_PRODUCAO_GRP">
																<input type='text' class="form-control input-sm data" name="DAT_PRODUCAO" id="DAT_PRODUCAO" value=""/>
																<span class="input-group-addon">
																	<span class="glyphicon glyphicon-calendar"></span>
																</span>
															</div>
															<div class="help-block with-errors"></div>
														</div>
													</div>
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Integradora</label>
																<select data-placeholder="Selecione a integradora" name="COD_INTEGRADORA" id="COD_INTEGRADORA" class="chosen-select-deselect">
																	<option value=""></option>
																	<?php 
																	
																		$sql = "select * from empresas where COD_EMPRESA <> 1 and LOG_INTEGRADORA = 'S' order by NOM_FANTASI";
																		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
																	
																		while ($qrListaIntegradora = mysqli_fetch_assoc($arrayQuery))
																		  {	
																	  
																			echo"
																				  <option value='".$qrListaIntegradora['COD_EMPRESA']."'>".$qrListaIntegradora['NOM_FANTASI']."</option> 
																				"; 
																			  }											
																	?>	
																</select>
															<div class="help-block with-errors"></div>
														</div>
														
													</div>													
																							
												</div>
																	
												<div class="row">

													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Consultor</label>
																<select data-placeholder="Selecione um consultor" name="COD_CONSULTOR" id="COD_CONSULTOR" class="chosen-select-deselect">
																	<option value=""></option>
																	<?php 
																	
																		$sql = "select COD_USUARIO, NOM_USUARIO from usuarios 
																		where usuarios.COD_EMPRESA = 3
																		and usuarios.DAT_EXCLUSA is null order by  usuarios.NOM_USUARIO ";
																		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
																	
																		while ($qrLista = mysqli_fetch_assoc($arrayQuery))
																		  {														
																			echo"
																				  <option value='".$qrLista['COD_USUARIO']."'>".$qrLista['NOM_USUARIO']."</option> 
																				"; 
																			  }											
																	?> 
																</select>
															<div class="help-block with-errors"></div>
														</div>
													</div>
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label required">Segmento</label>
																<select data-placeholder="Selecione um segmento" name="COD_SEGMENT" id="COD_SEGMENT" class="chosen-select-deselect" required>
																	<option value=""></option>
																	<?php 
																	
																		$sql = "select COD_SEGMENT, NOM_SEGMENT from SEGMENTOMARKA order by NOM_SEGMENT";
																		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
																	
																		while ($qrLista = mysqli_fetch_assoc($arrayQuery))
																		  {														
																			echo"
																				  <option value='".$qrLista['COD_SEGMENT']."'>".$qrLista['NOM_SEGMENT']."</option> 
																				"; 
																			  }											
																	?> 
																</select>
															<div class="help-block with-errors"></div>
														</div>
													</div>	
													
													<!--sistema-->
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label required">Sistemas</label>
																<select data-placeholder="Selecione um sistema" name="COD_SISTEMAS[]" id="COD_SISTEMAS" multiple="multiple" class="chosen-select-deselect requiredChk" style="width:100%;" tabindex="1" required>
																	<?php 
																	
																	if ($_SESSION["SYS_COD_MASTER"] == "2" ){

																		$sql = "SELECT COD_SISTEMA, DES_SISTEMA FROM SISTEMAS";

																	}else{
										
																		$sql = "SELECT COD_SISTEMAS FROM EMPRESAS WHERE COD_EMPRESA = 3 ";
																		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
																		$qrBuscaSistema = mysqli_fetch_assoc($arrayQuery);
																		$sistemasMarka = $qrBuscaSistema['COD_SISTEMAS'];
																		
																		$sql = "SELECT COD_SISTEMA, DES_SISTEMA FROM SISTEMAS WHERE COD_SISTEMA IN (".$sistemasMarka.") order by DES_SISTEMA ";

																	}
																		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
																	
																		while ($qrListaSistemas = mysqli_fetch_assoc($arrayQuery))
																		  {	
																			if ($qrListaSistemas['COD_SISTEMA'] == 'S') {
																				$mostraAutoriza = '<i class="fa fa-check" aria-hidden="true"></i>';	
																			}else{ $mostraAutoriza = ''; }		
																			
																			echo"
																				  <option value='".$qrListaSistemas['COD_SISTEMA']."'>".$qrListaSistemas['DES_SISTEMA']."</option> 
																				"; 
																			  }											

																	?>
																</select>
															<div class="help-block with-errors"></div>
														</div>
														
													</div>

													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label required">Plataforma</label>
															<select class="chosen-select-deselect requiredChk" data-placeholder="Selecione a plataforma" name="COD_PLATAFORMA" id="COD_PLATAFORMA">
																<option value=""></option>
																	<?php 
																	
																		$sql = "SELECT * FROM SAC_PLATAFORMA";
																		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
																	
																		while ($qrPlataforma = mysqli_fetch_assoc($arrayQuery))
																		  {
																		  	?>
																		  	<option value="<?php echo $qrPlataforma['COD_PLATAFORMA']; ?>"><?php echo $qrPlataforma['DES_PLATAFORMA']; ?></option>
																		  	<?php } ?>
															</select>
															<div class="help-block with-errors"></div>
														</div>
													</div>

													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label required">Versão da Integração</label>
															<select class="chosen-select-deselect requiredChk" data-placeholder="Selecione a versão" name="COD_VERSAOINTEGRA" id="COD_VERSAOINTEGRA">
																<option value=""></option>
																	<?php 
																	
																		$sql = "SELECT * FROM SAC_VERSAOINTEGRA";
																		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
																	
																		while ($qrIntegracao = mysqli_fetch_assoc($arrayQuery))
																		  {
																		  	?>
																		  	<option value="<?php echo $qrIntegracao['COD_VERSAOINTEGRA']; ?>"><?php echo $qrIntegracao['DES_VERSAOINTEGRA']; ?></option>
																		  	<?php } ?>
															</select>
															<div class="help-block with-errors"></div>
														</div>
													</div>

													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Informação do Vendedor</label>
															<select class="chosen-select-deselect requiredChk" data-placeholder="Selecione a origem da informação" name="LOG_CADVENDEDOR" id="LOG_CADVENDEDOR">
																<option value=""></option>
																<option value="1">tag dados login</option>
																<option value="2">tag venda</option>
																
															</select>
															<div class="help-block with-errors"></div>
														</div>
													</div>													
													
												</div>

												<div class="push10"></div>

												<div class="row">

													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Qtd. Caracteres do Token</label>
																<select data-placeholder="Selecione a quantidade" name="QTD_CHARTKN" id="QTD_CHARTKN" class="chosen-select-deselect">
																	<option value=""></option>
																	<option value="6">6</option>
																	<option value="8">8</option>
																	<option value="10">10</option>
																</select>
															<div class="help-block with-errors"></div>
														</div>
													</div>

													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Tipo de Token</label>
																<select data-placeholder="Selecione o tipo" name="TIP_TOKEN" id="TIP_TOKEN" class="chosen-select-deselect">
																	<option value=""></option>
																	<option value="1">Alfanumérico</option>
																	<option value="2">Numérico</option>
																</select>
															<div class="help-block with-errors"></div>
														</div>
													</div>
													
												</div>
												
										</fieldset>	
											
										<div class="push10"></div>										
										
										<fieldset>
											<legend>Dados da Empresa</legend> 																				
												
												<div class="row">
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Responsável</label>
															<input type="text" class="form-control input-sm" name="NOM_RESPONS" id="NOM_RESPONS" maxlength="50" data-error="Campo obrigatório">
															<div class="help-block with-errors"></div>
														</div>
													</div>
													
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">CNPJ/CPF</label>
															<input type="text" class="form-control input-sm cpfcnpj" name="NUM_CGCECPF" id="NUM_CGCECPF" maxlength="18" data-error="Campo obrigatório">
															<div class="help-block with-errors"></div>
														</div>
													</div>
													
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Inscrição Estadual</label>
															<input type="text" class="form-control input-sm" name="NUM_ESCRICA" id="NUM_ESCRICA" maxlength="20" data-error="Campo obrigatório">
															<div class="help-block with-errors"></div>
														</div>
													</div>
													
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Telefone Principal</label>
															<input type="text" class="form-control input-sm" name="NUM_TELEFON" id="NUM_TELEFON" maxlength="20">
															<div class="help-block with-errors"></div>
														</div>
													</div>
													
												</div>
												
												<div class="row">									
														
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Telefone Celular</label>
															<input type="text" class="form-control input-sm" name="NUM_CELULAR" id="NUM_CELULAR" maxlength="20">
															<div class="help-block with-errors"></div>
														</div>
													</div>
																	
													<div class="col-md-4">
														<div class="form-group">
															<label for="inputName" class="control-label">Endereço</label>
															<input type="text" class="form-control input-sm" name="DES_ENDEREC" id="DES_ENDEREC" maxlength="40">
															<div class="help-block with-errors"></div>
														</div>
													</div>	
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Número</label>
															<input type="text" class="form-control input-sm" name="NUM_ENDEREC" id="NUM_ENDEREC" maxlength="10">
															<div class="help-block with-errors"></div>
														</div>
													</div>
													
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Complemento</label>
															<input type="text" class="form-control input-sm" name="DES_COMPLEM" id="DES_COMPLEM" maxlength="20">
															<div class="help-block with-errors"></div>
														</div>
													</div>
													
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Bairro</label>
															<input type="text" class="form-control input-sm" name="DES_BAIRROC" id="DES_BAIRROC" maxlength="20">
															<div class="help-block with-errors"></div>
														</div>
													</div>	
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">CEP</label>
															<input type="text" class="form-control input-sm" name="NUM_CEPOZOF" id="NUM_CEPOZOF" maxlength="9">
															<div class="help-block with-errors"></div>
														</div>
													</div>
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Cidade</label>
															<input type="text" class="form-control input-sm" name="NOM_CIDADEC" id="NOM_CIDADEC" maxlength="40">
															<div class="help-block with-errors"></div>
														</div>
													</div>
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Estado</label>
																<select data-placeholder="Selecione um estado" name="COD_ESTADOF" id="COD_ESTADOF" class="chosen-select-deselect">
																	<option value=""></option>					
																	<option value="AC">AC</option> 
																	<option value="AL">AL</option> 
																	<option value="AM">AM</option> 
																	<option value="AP">AP</option> 
																	<option value="BA">BA</option> 
																	<option value="CE">CE</option> 
																	<option value="DF">DF</option> 
																	<option value="ES">ES</option> 
																	<option value="GO">GO</option> 
																	<option value="MA">MA</option> 
																	<option value="MG">MG</option> 
																	<option value="MS">MS</option> 
																	<option value="MT">MT</option> 
																	<option value="PA">PA</option> 
																	<option value="PB">PB</option> 
																	<option value="PE">PE</option> 
																	<option value="PI">PI</option> 
																	<option value="PR">PR</option> 
																	<option value="RJ">RJ</option> 
																	<option value="RN">RN</option> 
																	<option value="RO">RO</option> 
																	<option value="RR">RR</option> 
																	<option value="RS">RS</option> 
																	<option value="SC">SC</option> 
																	<option value="SE">SE</option> 
																	<option value="SP">SP</option> 
																	<option value="TO">TO</option> 							
																</select>	
															<div class="help-block with-errors"></div>
														</div>
													</div>
													
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Site</label>
															<input type="text" class="form-control input-sm" name="SITE" id="SITE" maxlength="100">
															<div class="help-block with-errors"></div>
														</div>
													</div>
													
												</div>
												
												<div class="row">

													<div class="col-md-1">
														<div class="form-group">
															<div class="push20"></div>
															<a class="btn btn-info btn-block btn-sm" href="javascript:void(0)" target='_blank' id="BTN_DOC"><span class="fal fa-file-alt"></span>&nbsp;(<span id="QTD_DOC">0</span>)</a>
														</div>
													</div>

													<div class="col-md-11">
														<div class="form-group">
															<label for="inputName" class="control-label">Comentário</label>
															<textarea class="form-control input-sm" rows="1" name="DES_COMENT" id="DES_COMENT"><?=$des_coment?></textarea>
															<div class="help-block with-errors"></div>
														</div>
													</div>

												</div>
												
										</fieldset>	
											
										<div class="push10"></div>										
										
										<fieldset>
											<legend>Personalização</legend> 
										
												<div class="row">
												
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label required">Layout do Sistema</label>
																<select data-placeholder="Selecione uma skin" name="COD_LAYOUT" id="COD_LAYOUT" class="chosen-select-deselect" required>
																	<option value=""></option>
																	<?php 
																	
																		$sql = "select COD_LAYOUT, DES_LAYOUT from LAYOUTS order by DES_LAYOUT";
																		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
																	
																		while ($qrLayout = mysqli_fetch_assoc($arrayQuery))
																		  {														
																			echo"
																				  <option value='".$qrLayout['COD_LAYOUT']."'>".$qrLayout['DES_LAYOUT']."</option> 
																				"; 
																			  }											
																	?>	
																</select>
															<div class="help-block with-errors"></div>
														</div>
													</div>
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Tipo do Bloco</label>
																<select data-placeholder="Selecione o tipo do do bloco" name="TIP_HEADER" id="TIP_HEADER" class="chosen-select-deselect">
																	<option value=""></option>
																	<option value="wide">Wide</option>
																	<option value="boxed">Boxed</option>
																</select>
															<div class="help-block with-errors"></div>
														</div>
													</div>
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Alinhamento do Logo</label>
																<select data-placeholder="Selecione um alinhamento" name="DES_ALINHAM" id="DES_ALINHAM" class="chosen-select-deselect">
																	<option value=""></option>
																	<option value="left">Esquerda</option>
																	<option value="center">Centro</option>
																	<option value="right">Direita</option>
																</select>
															<div class="help-block with-errors"></div>
														</div>
													</div>
													
													<div class="col-md-3">
														<label for="inputName" class="control-label">Logotipo</label>
														<div class="input-group">
															<span class="input-group-btn">
																<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary upload" idinput="DES_LOGO" extensao="img"><i class="fa fa-cloud-upload" aria-hidden="true"></i></a>
															</span>
															<input type="text" name="DES_LOGO" id="DES_LOGO" class="form-control input-sm" style="border-radius: 0 3px 3px  0;" maxlength="100" value="<?php echo $des_logo; ?>">
														</div>																
														<span class="help-block">(.png 300px X 80px)</span>
													</div>

													<div class="col-md-3">
														<label for="inputName" class="control-label">Imagem de Fundo</label>
														<div class="input-group">
															<span class="input-group-btn">
																<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary upload" idinput="DES_IMGBACK" extensao="img"><i class="fa fa-cloud-upload" aria-hidden="true"></i></a>
															</span>
															<input type="text" name="DES_IMGBACK" id="DES_IMGBACK" class="form-control input-sm" style="border-radius: 0 3px 3px  0;" maxlength="100" value="<?php echo $des_imgback; ?>">
														</div>																
														<span class="help-block">(.jpg 1400px X 600px)</span>
													</div>													
													
													
												</div>																	
												
										</fieldset>

										<?php
										if ($_SESSION["SYS_COD_MASTER"] == "2" ) {
										?>	

										<div class="push10"></div>										
										
										<fieldset style="background: #F4F6F6;">
											<legend>Dados Master</legend> 
										
												<div class="row">

													<div class="col-md-1">
														<div class="form-group">
															<label for="inputName" class="control-label">Avulso</label>
															<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_CLIENTE_AV" id="COD_CLIENTE_AV" value="">
														</div>
													</div>

													<div class="col-md-5">
														<div class="form-group">
															<label for="inputName" class="control-label required">Empresa Master</label>
																<select data-placeholder="Selecione uma empresa" name="COD_MASTER" id="COD_MASTER" class="chosen-select-deselect" required>
																	<option value=""></option>
																	<?php 
																	
																		$sql = "select COD_EMPRESA, NOM_EMPRESA from empresas where COD_EMPRESA IN (1,2,3) order by NOM_EMPRESA";
																		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
																	
																		while ($qrListaEempresas = mysqli_fetch_assoc($arrayQuery))
																		  {	
																																
																			echo"
																				  <option value='".$qrListaEempresas['COD_EMPRESA']."'>".$qrListaEempresas['NOM_EMPRESA']."</option> 
																				"; 
																			  }											
																	?>	
																</select>
															<div class="help-block with-errors"></div>
														</div>
													</div>

													<div class="col-md-6">
														<div class="form-group">
															<label for="inputName" class="control-label">Path Arquivos</label>
															<input type="text" class="form-control input-sm" name="DES_PATHARQ" id="DES_PATHARQ" maxlength="250">
															<div class="help-block with-errors"></div>
														</div>
													</div>

												</div>

												<div class="row">
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Porcentagem do Parceiro</label>
															<input type="text" class="form-control input-sm money" name="PCT_PARCEIRO" id="PCT_PARCEIRO" value="0">
															<div class="help-block with-errors"></div>
														</div>
													</div>

												</div>																	
												
										</fieldset>

										<?php 
										}else{
										?>

										<input type="hidden" name="DES_PATHARQ" id="DES_PATHARQ" value="">
										<input type="hidden" name="COD_MASTER" id="COD_MASTER" value="3">
										<input type="hidden" name="COD_CLIENTE_AV" id="COD_CLIENTE_AV" value="">
										<input type="hidden" name="PCT_PARCEIRO" id="PCT_PARCEIRO" value="0">

										<?php 
										}
										?>																
										
										
										<div class="push10"></div>
										<hr>	
										<div class="form-group text-right col-lg-12">
											
											  <button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>
											  <button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
											  <button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Alterar</button>
											  <!-- <button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fa fa-times" aria-hidden="true"></i>&nbsp; Excluir</button> -->
											
										</div>
										
										
										
										<input type="hidden" name="opcao" id="opcao" value="">
										<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
										<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">		
										
										<div class="push5"></div> 
										
										</form>
										
										<div class="push5"></div>

										<div class="row">
											<form name="formLista2" id="formLista2" method="post" action="<?php echo $cmdPage; ?>">

												<div class="col-xs-4 col-xs-offset-4">
												    <div class="input-group activeItem">
										                <div class="input-group-btn search-panel">
										                    <button type="button" class="btn btn-outline dropdown-toggle form-control form-control-sm rounded-left search-bar" id="FILTERS" data-toggle="dropdown">
										                    	<span id="search_concept">Sem filtro</span>&nbsp;
										                    	<span class="far fa-angle-down"></span>										                    	
										                    </button>
										                    <ul class="dropdown-menu" role="menu">
										                    	<li class="divisor"><a href="#">Sem filtro</a></li>
										                    	<!-- <li class="divider"></li> -->
											                    <li><a href="#NOM_EMPRESA">Razão social</a></li>
											                    <li><a href="#NOM_FANTASI">Nome fantasia</a></li>
											                    <li><a href="#CNPJ">CNPJ</a></li>										                      
										                    </ul>
										                </div>
										                <input type="hidden" name="VAL_PESQUISA" value="" id="VAL_PESQUISA">         
										                <input type="text" id="INPUT" class="form-control form-control-sm remove-side-borders search-bar" name="INPUT" value="<?=$val_pesquisa?>" onkeyup="buscaRegistro(this)">
										                <div class="input-group-btn"id="CLEARDIV" style="<?=$esconde?>">
										                	<button class="btn btn-outline form-control form-control-sm remove-side-borders search-bar" id="CLEAR" type="button">&nbsp;<span class="fal fa-times"></span></button>
										                </div>
										                <div class="input-group-btn">
										                    <button type="submit" class="btn btn-outline form-control form-control-sm rounded-right search-bar" id="SEARCH">&nbsp;<span class="fal fa-search"></span></button>
										                </div>
										            </div>
										        </div>
										         	
										        <input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />							
												<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

											</form>
										    
										</div>

										<div class="push20"></div>
										
										<div class="col-lg-12">

											<div class="no-more-tables">
										
												<form name="formLista">
												
												<table class="table table-bordered table-striped table-hover tableSorter buscavel">
												  <thead>
													<tr>
													  <th class="{ sorter: false }" width="40"></th>
													  <th>Código</th>
													  <th>Nome Fantasia</th>
													  <th>Responsável</th>
													  <th>Coordenador</th>
													  <th>Integradora</th>
													  <th>% Parc.</th>
													  <th>Lojas</th>
													  <th>Ativas</th>
													  <th class="{sorter:false}">SH</th>
													  <th class="{sorter:false}">Ativo</th>
													  <th class="{sorter:false}">BD</th>
													  <th>Status</th>
													  <th>Produção</th>
													</tr>
												  </thead>
												<tbody>
												
												  
												<?php 

													if($filtro != ""){
														$andFiltro = " AND $filtro LIKE '%$val_pesquisa%' ";
													}else{
														$andFiltro = " ";
													}
												
													if ($_SESSION["SYS_COD_MASTER"] == "2" ) {
													$sql = "SELECT 
															STATUSSISTEMA.DES_STATUS,
															empresas.*,
															(select NOM_USUARIO from webtools.usuarios where cod_empresa=3 and cod_usuario=empresas.cod_consultor) as NOM_CONSULTOR, 
															(SELECT count(*) FROM UNIDADEVENDA UV WHERE UV.COD_EMPRESA = empresas.COD_EMPRESA) AS LOJAS,
															(SELECT count(*) FROM UNIDADEVENDA UV WHERE UV.COD_EMPRESA = empresas.COD_EMPRESA AND UV.LOG_ESTATUS = 'S') AS LOJAS_ATIVAS,	
															(SELECT D.NOM_FANTASI FROM EMPRESAS D WHERE D.COD_EMPRESA=empresas.COD_INTEGRADORA  ) NOM_INTEGRADORA,
															B.COD_DATABASE, 
															B.NOM_DATABASE 
															FROM empresas 
															LEFT JOIN STATUSSISTEMA ON STATUSSISTEMA.COD_STATUS=empresas.COD_STATUS 
															LEFT JOIN tab_database B ON B.cod_empresa=empresas.COD_EMPRESA 
															WHERE empresas.COD_EMPRESA <> 1
															$andFiltro
															ORDER by NOM_FANTASI
													";
													
													}else {
													$sql = "SELECT 
															STATUSSISTEMA.DES_STATUS,
															empresas.*, 
															(select NOM_USUARIO from webtools.usuarios where cod_empresa=3 and cod_usuario=empresas.cod_consultor) as NOM_CONSULTOR, 
															(SELECT count(*) FROM UNIDADEVENDA UV WHERE UV.COD_EMPRESA = empresas.COD_EMPRESA) AS LOJAS,	
															(SELECT count(*) FROM UNIDADEVENDA UV WHERE UV.COD_EMPRESA = empresas.COD_EMPRESA AND UV.LOG_ESTATUS = 'S') AS LOJAS_ATIVAS,	
															(SELECT D.NOM_FANTASI FROM EMPRESAS D WHERE D.COD_EMPRESA=empresas.COD_INTEGRADORA  ) NOM_INTEGRADORA,
															B.COD_DATABASE, 
															B.NOM_DATABASE 
															FROM empresas 
															LEFT JOIN STATUSSISTEMA ON STATUSSISTEMA.COD_STATUS=empresas.COD_STATUS 
															LEFT JOIN tab_database B ON B.cod_empresa=empresas.COD_EMPRESA 
															WHERE COD_MASTER IN (1,".$_SESSION["SYS_COD_MASTER"].",".$_SESSION["SYS_COD_EMPRESA"].")
															$andFiltro
															ORDER by NOM_FANTASI
													";
													}
													
													//fnEscreve($sql);
													
													$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
													
													$count=0;
													$totLOjas=0;
													
													while ($qrListaEmpresas = mysqli_fetch_assoc($arrayQuery))
													  {														  
														$count++;
														  if ($qrListaEmpresas['LOG_ATIVO'] == 'S'){		
																$mostraAtivo = '<i class="fa fa-check" aria-hidden="true"></i>';	
															}else{ $mostraAtivo = ''; }	
														
														  if ($qrListaEmpresas['COD_DATABASE'] > 0){
															if ($qrListaEmpresas['NOM_DATABASE'] == "db_host1" || $qrListaEmpresas['NOM_DATABASE'] == "db_host2"){
																$mostraAtivoBD = '<i class="fa fa-clock-o" aria-hidden="true"></i>';
																$mostraEmpresa = "<a href='action.do?mod=".fnEncode(1020)."&id=".fnEncode($qrListaEmpresas['COD_EMPRESA'])."'>".$qrListaEmpresas['NOM_FANTASI']."</a>";	
															}else{
																$mostraAtivoBD = '<i class="fa fa-check" aria-hidden="true"></i>';		
																$mostraEmpresa = "<a href='action.do?mod=".fnEncode(1020)."&id=".fnEncode($qrListaEmpresas['COD_EMPRESA'])."'>".$qrListaEmpresas['NOM_FANTASI']."</a>";	
															}	
														  }else{ 
															$mostraAtivoBD = ''; 
															$mostraEmpresa = $qrListaEmpresas['NOM_FANTASI'];	
														  }	
														
														  if (!empty($qrListaEmpresas['COD_SISTEMAS'])){
															  $tem_sistema = "tem";															  
														  }	else {$tem_sistema = "nao";}

														  if ($qrListaEmpresas['LOG_INTEGRADORA'] == 'S'){		
																$mostraSH = '<i class="fa fa-check" aria-hidden="true"></i>';	
															}else{ $mostraSH = ''; }
														  
                                                          $totLOjas = $totLOjas+ $qrListaEmpresas['LOJAS'];
														  
														  if ($qrListaEmpresas['LOJAS'] > $qrListaEmpresas['LOJAS_ATIVAS']){
															$corLojaAtv = "text-danger";
														  }else{
															$corLojaAtv = "";  
														  }

														  $sqlDocs = "SELECT 1 FROM DOCUMENTOS_EMPRESA WHERE COD_EMPRESA = $qrListaEmpresas[COD_EMPRESA]";

														  $qtd_doc = mysqli_num_rows(mysqli_query(connTemp($qrListaEmpresas[COD_EMPRESA],''),$sqlDocs)); 

														  // fnescreve($qtd_doc);
														  
														echo"
															<tr>
															  <td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(".$count.")'></th>
															  <td class='text-center'>".$qrListaEmpresas['COD_EMPRESA']."</td>
															  <td>".$mostraEmpresa."</td>
															  <td>".$qrListaEmpresas['NOM_RESPONS']."</td>
															  <td>".$qrListaEmpresas['NOM_CONSULTOR']."</td>
															  <td>".$qrListaEmpresas['NOM_INTEGRADORA']."</td>
															  <td>".fnValor($qrListaEmpresas['PCT_PARCEIRO'],2)."</td>
															  <td align='center'>".$qrListaEmpresas['LOJAS']."</td>
															  <td align='center'><span class='".$corLojaAtv."'>".$qrListaEmpresas['LOJAS_ATIVAS']."</td>
															  <td align='center'>".$mostraSH."</td>
															  <td align='center'>".$mostraAtivo."</td>
															  <td align='center'>".$mostraAtivoBD."</td>
															  <td>".$qrListaEmpresas['DES_STATUS']."</td>
															  <td><small>".fnDateRetorno($qrListaEmpresas['DAT_PRODUCAO'])."</small></td>
															</tr>
															<input type='hidden' id='ret_ID_".$count."' value='".$qrListaEmpresas['COD_EMPRESA']."'>
															<input type='hidden' id='ret_NOM_EMPRESA_".$count."' value='".$qrListaEmpresas['NOM_EMPRESA']."'>
															<input type='hidden' id='ret_DES_ABREVIA_".$count."' value='".$qrListaEmpresas['DES_ABREVIA']."'>
															<input type='hidden' id='ret_NOM_RESPONS_".$count."' value='".$qrListaEmpresas['NOM_RESPONS']."'>
															<input type='hidden' id='ret_NUM_CGCECPF_".$count."' value='".$qrListaEmpresas['NUM_CGCECPF']."'>
															<input type='hidden' id='ret_NUM_ESCRICA_".$count."' value='".$qrListaEmpresas['NUM_ESCRICA']."'>
															<input type='hidden' id='ret_LOG_ATIVO_".$count."' value='".$qrListaEmpresas['LOG_ATIVO']."'>
															<input type='hidden' id='ret_COD_ESTATUS_".$count."' value='".$qrListaEmpresas['COD_STATUS']."'>
															<input type='hidden' id='ret_LOG_PRECUNI_".$count."' value='".$qrListaEmpresas['LOG_PRECUNI']."'>
															<input type='hidden' id='ret_LOG_ESTOQUE_".$count."' value='".$qrListaEmpresas['LOG_ESTOQUE']."'>
															<input type='hidden' id='ret_NOM_FANTASI_".$count."' value='".$qrListaEmpresas['NOM_FANTASI']."'>
															<input type='hidden' id='ret_NUM_TELEFON_".$count."' value='".$qrListaEmpresas['NUM_TELEFON']."'>
															<input type='hidden' id='ret_NUM_CELULAR_".$count."' value='".$qrListaEmpresas['NUM_CELULAR']."'>
															<input type='hidden' id='ret_DES_ENDEREC_".$count."' value='".$qrListaEmpresas['DES_ENDEREC']."'>
															<input type='hidden' id='ret_NUM_ENDEREC_".$count."' value='".$qrListaEmpresas['NUM_ENDEREC']."'>
															<input type='hidden' id='ret_DES_COMPLEM_".$count."' value='".$qrListaEmpresas['DES_COMPLEM']."'>
															<input type='hidden' id='ret_DES_BAIRROC_".$count."' value='".$qrListaEmpresas['DES_BAIRROC']."'>
															<input type='hidden' id='ret_NUM_CEPOZOF_".$count."' value='".$qrListaEmpresas['NUM_CEPOZOF']."'>
															<input type='hidden' id='ret_NOM_CIDADEC_".$count."' value='".$qrListaEmpresas['NOM_CIDADEC']."'>
															<input type='hidden' id='ret_COD_ESTADOF_".$count."' value='".$qrListaEmpresas['COD_ESTADOF']."'>
															<input type='hidden' id='ret_COD_SISTEMAS_".$count."' value='".$qrListaEmpresas['COD_SISTEMAS']."'>
															<input type='hidden' id='ret_COD_MASTER_".$count."' value='".$qrListaEmpresas['COD_MASTER']."'>
															<input type='hidden' id='ret_COD_LAYOUT_".$count."' value='".$qrListaEmpresas['COD_LAYOUT']."'>
															<input type='hidden' id='ret_COD_SEGMENT_".$count."' value='".$qrListaEmpresas['COD_SEGMENT']."'>
															<input type='hidden' id='ret_DES_SUFIXO_".$count."' value='".$qrListaEmpresas['DES_SUFIXO']."'>
															<input type='hidden' id='ret_LOG_CONSEXT_".$count."' value='".$qrListaEmpresas['LOG_CONSEXT']."'>
															<input type='hidden' id='ret_LOG_AUTOCAD_".$count."' value='".$qrListaEmpresas['LOG_AUTOCAD']."'>
															<input type='hidden' id='ret_LOG_TOKEN_".$count."' value='".$qrListaEmpresas['LOG_TOKEN']."'>
															<input type='hidden' id='ret_LOG_CADTOKEN_".$count."' value='".$qrListaEmpresas['LOG_CADTOKEN']."'>
															<input type='hidden' id='ret_COD_CHAVECO_".$count."' value='".$qrListaEmpresas['COD_CHAVECO']."'>
															<input type='hidden' id='ret_LOG_CONFIGU_".$count."' value='".$qrListaEmpresas['LOG_CONFIGU']."'>
															<input type='hidden' id='ret_TIP_CONTABIL_".$count."' value='".$qrListaEmpresas['TIP_CONTABIL']."'>
															<input type='hidden' id='ret_LOG_INTEGRADORA_".$count."' value='".$qrListaEmpresas['LOG_INTEGRADORA']."'>
															<input type='hidden' id='ret_DES_PATHARQ_".$count."' value='".$qrListaEmpresas['DES_PATHARQ']."'>
															<input type='hidden' id='ret_COD_DATABASE_".$count."' value='".$qrListaEmpresas['COD_DATABASE']."'>
															<input type='hidden' id='ret_SITE_".$count."' value='".$qrListaEmpresas['SITE']."'>
															<input type='hidden' id='ret_TEM_SISTEMAS_".$count."' value='".$tem_sistema."'>
															<input type='hidden' id='ret_COD_DATABASE_".$count."' value='".$qrListaEmpresas['COD_DATABASE']."'>
															<input type='hidden' id='ret_TIP_REGVENDA_".$count."' value='".$qrListaEmpresas['TIP_REGVENDA']."'>															
															<input type='hidden' id='ret_NUM_DECIMAIS_".$count."' value='".$qrListaEmpresas['NUM_DECIMAIS']."'>
															<input type='hidden' id='ret_NUM_DECIMAIS_B_".$count."' value='".$qrListaEmpresas['NUM_DECIMAIS_B']."'>
															<input type='hidden' id='ret_DAT_PRODUCAO_".$count."' value='".fnDateRetorno($qrListaEmpresas['DAT_PRODUCAO'])."'>
															<input type='hidden' id='ret_COD_INTEGRADORA_".$count."' value='".$qrListaEmpresas['COD_INTEGRADORA']."'>
															<input type='hidden' id='ret_COD_CONSULTOR_".$count."' value='".$qrListaEmpresas['COD_CONSULTOR']."'>
															<input type='hidden' id='ret_LOG_WS_".$count."' value='".$qrListaEmpresas['LOG_WS']."'>
															<input type='hidden' id='ret_LOG_PONTUAR_".$count."' value='".$qrListaEmpresas['LOG_PONTUAR']."'>
															<input type='hidden' id='ret_LOG_ATIVCAD_".$count."' value='".$qrListaEmpresas['LOG_ATIVCAD']."'>    
															<input type='hidden' id='ret_LOG_AVULSO_".$count."' value='".$qrListaEmpresas['LOG_AVULSO']."'>    
															<input type='hidden' id='ret_TIP_RETORNO_".$count."' value='".$qrListaEmpresas['TIP_RETORNO']."'>
															<input type='hidden' id='ret_TIP_HEADER_".$count."' value='".$qrListaEmpresas['TIP_HEADER']."'>
															<input type='hidden' id='ret_DES_ALINHAM_".$count."' value='".$qrListaEmpresas['DES_ALINHAM']."'>
															<input type='hidden' id='ret_DES_LOGO_".$count."' value='".$qrListaEmpresas['DES_LOGO']."'>
															<input type='hidden' id='ret_DES_IMGBACK_".$count."' value='".$qrListaEmpresas['DES_IMGBACK']."'>
															<input type='hidden' id='ret_LOG_CATEGORIA_".$count."' value='".$qrListaEmpresas['LOG_CATEGORIA']."'>
															<input type='hidden' id='ret_LOG_ALTVENDA_".$count."' value='".$qrListaEmpresas['LOG_ALTVENDA']."'>
															<input type='hidden' id='ret_COD_DATAWS_".$count."' value='".$qrListaEmpresas['COD_DATAWS']."'>
															<input type='hidden' id='ret_LOG_QUALICAD_".$count."' value='".$qrListaEmpresas['LOG_QUALICAD']."'>
															<input type='hidden' id='ret_LOG_CADVENDEDOR_".$count."' value='".$qrListaEmpresas['LOG_CADVENDEDOR']."'>
															<input type='hidden' id='ret_COD_VERSAOINTEGRA_".$count."' value='".$qrListaEmpresas['COD_VERSAOINTEGRA']."'>
															<input type='hidden' id='ret_COD_PLATAFORMA_".$count."' value='".$qrListaEmpresas['COD_PLATAFORMA']."'>
															<input type='hidden' id='ret_LOG_PDVMANU_".$count."' value='".$qrListaEmpresas['LOG_PDVMANU']."'>
															<input type='hidden' id='ret_LOG_NEGATIVO_".$count."' value='".$qrListaEmpresas['LOG_NEGATIVO']."'>
															<input type='hidden' id='ret_LOG_CREDAVULSO_".$count."' value='".$qrListaEmpresas['LOG_CREDAVULSO']."'>
															<input type='hidden' id='ret_COD_CLIENTE_AV_".$count."' value='".$qrListaEmpresas['COD_CLIENTE_AV']."'>
															<input type='hidden' id='ret_PCT_PARCEIRO_".$count."' value='".fnValor($qrListaEmpresas['PCT_PARCEIRO'],2)."'>
															<input type='hidden' id='ret_TIP_CAMPANHA_".$count."' value='".$qrListaEmpresas['TIP_CAMPANHA']."'>
															<input type='hidden' id='ret_QTD_CHARTKN_".$count."' value='".$qrListaEmpresas['QTD_CHARTKN']."'>
															<input type='hidden' id='ret_TIP_TOKEN_".$count."' value='".$qrListaEmpresas['TIP_TOKEN']."'>
															<input type='hidden' id='ret_QTD_DOC_".$count."' value='".fnLimpaCampoZero($qtd_doc)."'>
															<input type='hidden' id='ret_BTN_DOC_".$count."' value='action.do?mod=".fnEncode(1488)."&id=".fnEncode($qrListaEmpresas['COD_EMPRESA'])."'>
															";															
														  }
													
												?>
													
												</tbody>
												
												<tfoot>
													<tr>
													  <th></th>
													  <th></th>
													  <th class="text-center"><?php echo $count; ?></th>
													  <th></th>
													  <th class="text-center" colspan="3">Total Lojas</th>
													  <th class="text-center"><?php echo $totLOjas; ?></th>													  
													  <th class="" colspan="5"></th>
													  </th>
													</tr>
													<tr>
														<th colspan="100">
															<a class="btn btn-info btn-sm exportarCSV"> <i class="fa fa-file-excel" aria-hidden="true"></i>&nbsp; Exportar</a>
														</th>
													</tr>
												</tfoot>

												</table>
												
												</form>

											</div>
											
										</div>										
									
									<div class="push"></div>
									
									</div>								
								
								</div>
							</div>
							<!-- fim Portlet -->
						</div>
						
					</div>					
						
					<div class="push20"></div> 
					
	<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
	<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />	
	
	<script type="text/javascript">
	
		$(document).ready(function(){
			
			$('.datePicker').datetimepicker({
				 format: 'DD/MM/YYYY',
				 maxDate : 'now',
				}).on('changeDate', function(e){
					$(this).datetimepicker('hide');
				});
				
			//chosen obrigatório
			$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
			$('#formulario').validator();

			$(".exportarCSV").click(function() {
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
											url: "ajxExportaEmpresas.do?opcao=exportar&nomeRel="+nome,
											data: $('#formLista2').serialize(),
											method: 'POST'
										}).done(function (response) {
											self.setContentAppend('<div>Exportação realizada com sucesso.</div>');
											var fileName = '3_' + nome + '.csv';
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
			});

		});

		//Barra de pesquisa essentials ------------------------------------------------------
		$(document).ready(function(e){
			var value = $('#INPUT').val().toLowerCase().trim();
		    if(value){
		    	$('#CLEARDIV').show();
		    }else{
		    	$('#CLEARDIV').hide();
		    }
		    $('.search-panel .dropdown-menu').find('a').click(function(e) {
				e.preventDefault();
				var param = $(this).attr("href").replace("#","");
				var concept = $(this).text();
				$('.search-panel span#search_concept').text(concept);
				$('.input-group #VAL_PESQUISA').val(param);
				$('#INPUT').focus();
			});

		    $("#FILTERS, #INPUT, #SEARCH, #CLEAR").focus(function(){
			    $("#FILTERS, #INPUT, #SEARCH, #CLEAR").addClass("activeItem");
		    });

		    $("#FILTERS, #INPUT, #SEARCH, #CLEAR").blur(function(){
		    	$("#FILTERS, #INPUT, #SEARCH, #CLEAR").removeClass("activeItem");
		    });

		    $('#CLEAR').click(function(){
		    	$('#INPUT').val('');
		    	$('#INPUT').focus();
		    	$('#CLEARDIV').hide();
		    	if("<?php echo $filtro; ?>" != ""){
		    		location.reload();
		    	}else{
		    		var value = $('#INPUT').val().toLowerCase().trim();
				    if(value){
				    	$('#CLEARDIV').show();
				    }else{
				    	$('#CLEARDIV').hide();
				    }
				    $(".buscavel tr").each(function (index) {
				        if (!index) return;
				        $(this).find("td").each(function () {
				            var id = $(this).text().toLowerCase().trim();
				            var sem_registro = (id.indexOf(value) == -1);
				            $(this).closest('tr').toggle(!sem_registro);
				            return sem_registro;
				        });
				    });
		    	}
		    });

		    // $('#SEARCH').click(function(){
		    // 	$('#formulario').submit();
		    // });
		    	
		    
		});

		function buscaRegistro(el){
			var filtro = $('#search_concept').text().toLowerCase();

			if(filtro == "sem filtro"){
			    var value = $(el).val().toLowerCase().trim();
			    if(value){
			    	$('#CLEARDIV').show();
			    }else{
			    	$('#CLEARDIV').hide();
			    }
			    $(".buscavel tr").each(function (index) {
			        if (!index) return;
			        $(this).find("td").each(function () {
			            var id = $(this).text().toLowerCase().trim();
			            var sem_registro = (id.indexOf(value) == -1);
			            $(this).closest('tr').toggle(!sem_registro);
			            return sem_registro;
			        });
			    });
			}
		}

	//-----------------------------------------------------------------------------------
		
		function retornaForm(index){
			$("#formulario #COD_SISTEMAS").val(0).trigger("chosen:updated");
			$("#formulario #ID").val($("#ret_ID_"+index).val());
			$("#formulario #NOM_EMPRESA").val($("#ret_NOM_EMPRESA_"+index).val());
			$("#formulario #DES_ABREVIA").val($("#ret_DES_ABREVIA_"+index).val());
			$("#formulario #NOM_RESPONS").val($("#ret_NOM_RESPONS_"+index).val());
			$("#formulario #NUM_CGCECPF").val($("#ret_NUM_CGCECPF_"+index).val());
			$("#formulario #COD_ESTATUS").val($("#ret_COD_ESTATUS_"+index).val()).trigger("chosen:updated");
			if ($("#ret_LOG_ATIVO_"+index).val() == 'S'){$('#formulario #LOG_ATIVO').prop('checked', true);} 
			else {$('#formulario #LOG_ATIVO').prop('checked', false);}			
			if ($("#ret_LOG_PRECUNI_"+index).val() == 'S'){$('#formulario #LOG_PRECUNI').prop('checked', true);} 
			else {$('#formulario #LOG_PRECUNI').prop('checked', false);}
			if ($("#ret_LOG_ESTOQUE_"+index).val() == 'S'){$('#formulario #LOG_ESTOQUE').prop('checked', true);} 
			else {$('#formulario #LOG_ESTOQUE').prop('checked', false);}
			if ($("#ret_LOG_TOKEN_"+index).val() == 'S'){$('#formulario #LOG_TOKEN').prop('checked', true);} 
			else {$('#formulario #LOG_TOKEN').prop('checked', false);}
			if ($("#ret_LOG_CADTOKEN_"+index).val() == 'S'){$('#formulario #LOG_CADTOKEN').prop('checked', true);} 
			else {$('#formulario #LOG_CADTOKEN').prop('checked', false);}
			$("#formulario #NUM_ESCRICA").val($("#ret_NUM_ESCRICA_"+index).val());
			$("#formulario #NOM_FANTASI").val($("#ret_NOM_FANTASI_"+index).val());				
			$("#formulario #NUM_TELEFON").val($("#ret_NUM_TELEFON_"+index).val());
			$("#formulario #NUM_CELULAR").val($("#ret_NUM_CELULAR_"+index).val());
			$("#formulario #DES_ENDEREC").val($("#ret_DES_ENDEREC_"+index).val());
			$("#formulario #NUM_ENDEREC").val($("#ret_NUM_ENDEREC_"+index).val());
			$("#formulario #DES_COMPLEM").val($("#ret_DES_COMPLEM_"+index).val());
			$("#formulario #DES_BAIRROC").val($("#ret_DES_BAIRROC_"+index).val());
			$("#formulario #NUM_CEPOZOF").val($("#ret_NUM_CEPOZOF_"+index).val());
			$("#formulario #NOM_CIDADEC").val($("#ret_NOM_CIDADEC_"+index).val());				
			$("#formulario #COD_ESTADOF").val($("#ret_COD_ESTADOF_"+index).val()).trigger("chosen:updated");
			$("#formulario #COD_MASTER").val($("#ret_COD_MASTER_"+index).val()).trigger("chosen:updated");
			$("#formulario #COD_LAYOUT").val($("#ret_COD_LAYOUT_"+index).val()).trigger("chosen:updated");
			$("#formulario #COD_SEGMENT").val($("#ret_COD_SEGMENT_"+index).val()).trigger("chosen:updated");
			$("#formulario #DES_SUFIXO").val($("#ret_DES_SUFIXO_"+index).val());
			$("#formulario #TIP_RETORNO").val($("#ret_TIP_RETORNO_"+index).val()).trigger("chosen:updated");
			$("#formulario #QTD_CHARTKN").val($("#ret_QTD_CHARTKN_"+index).val()).trigger("chosen:updated");
			$("#formulario #TIP_TOKEN").val($("#ret_TIP_TOKEN_"+index).val()).trigger("chosen:updated");
			$("#formulario #TIP_HEADER").val($("#ret_TIP_HEADER_"+index).val()).trigger("chosen:updated");
			$("#formulario #DES_ALINHAM").val($("#ret_DES_ALINHAM_"+index).val()).trigger("chosen:updated");
			$("#formulario #DES_LOGO").val($("#ret_DES_LOGO_"+index).val());
			$("#formulario #DES_IMGBACK").val($("#ret_DES_IMGBACK_"+index).val());
			$("#formulario #COD_CLIENTE_AV").val($("#ret_COD_CLIENTE_AV_"+index).val());
			$("#formulario #PCT_PARCEIRO").val($("#ret_PCT_PARCEIRO_"+index).val());
			$("#formulario #QTD_DOC").text($("#ret_QTD_DOC_"+index).val());
			$("#formulario #BTN_DOC").removeAttr('href').attr('href',$("#ret_BTN_DOC_"+index).val());

			$("#formulario #COD_PLATAFORMA").val($("#ret_COD_PLATAFORMA_"+index).val()).trigger("chosen:updated");
			$("#formulario #COD_VERSAOINTEGRA").val($("#ret_COD_VERSAOINTEGRA_"+index).val()).trigger("chosen:updated");

			if ($("#ret_LOG_CONSEXT_"+index).val() == 'S'){$('#formulario #LOG_CONSEXT').prop('checked', true);} 
			else {$('#formulario #LOG_CONSEXT').prop('checked', false);}			
			if ($("#ret_LOG_AUTOCAD_"+index).val() == 'S'){$('#formulario #LOG_AUTOCAD').prop('checked', true);} 
			else {$('#formulario #LOG_AUTOCAD').prop('checked', false);}
			
			$("#formulario #TIP_CONTABIL").val($("#ret_TIP_CONTABIL_"+index).val()).trigger("chosen:updated");
			if ($("#ret_LOG_CONFIGU_"+index).val() == 'S'){$('#formulario #LOG_CONFIGU').prop('checked', true);} 
			else {$('#formulario #LOG_CONFIGU').prop('checked', false);}
			
			$("#formulario #COD_CHAVECO").val($("#ret_COD_CHAVECO_"+index).val()).trigger("chosen:updated");
			$("#formulario #TIP_CAMPANHA").val($("#ret_TIP_CAMPANHA_"+index).val()).trigger("chosen:updated");

			//retorno combo multiplo
			if ($("#ret_TEM_SISTEMAS_"+index).val() == "tem" ){
				var sistemasCli = $("#ret_COD_SISTEMAS_"+index).val();
				var sistemasCliArr = sistemasCli.split(',');
				//opções multiplas
				for (var i = 0; i < sistemasCliArr.length; i++) {
				  $("#formulario #COD_SISTEMAS option[value=" + sistemasCliArr[i] + "]").prop("selected", "true");				  
				}
				$("#formulario #COD_SISTEMAS").trigger("chosen:updated");    
			} else {$("#formulario #COD_SISTEMAS").val('').trigger("chosen:updated");}
			
			$("#formulario #DES_PATHARQ").val($("#ret_DES_PATHARQ_"+index).val());
			if ($("#ret_LOG_INTEGRADORA_"+index).val() == 'S'){$('#formulario #LOG_INTEGRADORA').prop('checked', true);} 
			else {$('#formulario #LOG_INTEGRADORA').prop('checked', false);}			
			$("#formulario #SITE").val($("#ret_SITE_"+index).val());
			
			if ($("#ret_COD_DATABASE_"+index).val() > 0){$('#formulario #COD_DATABASE').prop('checked', true);} 
			else {$('#formulario #COD_DATABASE').prop('checked', false);}		
			
			$("#formulario #TIP_REGVENDA").val($("#ret_TIP_REGVENDA_"+index).val()).trigger("chosen:updated");
			
			$("#formulario #NUM_DECIMAIS").val($("#ret_NUM_DECIMAIS_"+index).val()).trigger("chosen:updated");
			$("#formulario #NUM_DECIMAIS_B").val($("#ret_NUM_DECIMAIS_B_"+index).val()).trigger("chosen:updated");
			$("#formulario #COD_DATAWS").val($("#ret_COD_DATAWS_"+index).val()).trigger("chosen:updated");
			$("#formulario #DAT_PRODUCAO").val($("#ret_DAT_PRODUCAO_"+index).val());				
			$("#formulario #COD_INTEGRADORA").val($("#RET_COD_INTEGRADORA_"+index).val()).trigger("chosen:updated");
			$("#formulario #COD_CONSULTOR").val($("#ret_COD_CONSULTOR_"+index).val()).trigger("chosen:updated");
			
			if ($("#ret_LOG_WS_"+index).val() == 'S'){$('#formulario #LOG_WS').prop('checked', true);} 
			else {$('#formulario #LOG_WS').prop('checked', false);}
			
			if ($("#ret_LOG_PONTUAR_"+index).val() == 'S'){$('#formulario #LOG_PONTUAR').prop('checked', true);} 
			else {$('#formulario #LOG_PONTUAR').prop('checked', false);}
			
            if ($("#ret_LOG_ATIVCAD_"+index).val() == 'S'){$('#formulario #LOG_ATIVCAD').prop('checked', true);} 
			else {$('#formulario #LOG_ATIVCAD').prop('checked', false);}
			
            if ($("#ret_LOG_AVULSO_"+index).val() == 'S'){$('#formulario #LOG_AVULSO').prop('checked', true);} 
			else {$('#formulario #LOG_AVULSO').prop('checked', false);}
                 
            if ($("#ret_LOG_CATEGORIA_"+index).val() == 'S'){$('#formulario #LOG_CATEGORIA').prop('checked', true);} 
			else {$('#formulario #LOG_CATEGORIA').prop('checked', false);}
            
			if ($("#ret_LOG_ALTVENDA_"+index).val() == 'S'){$('#formulario #LOG_ALTVENDA').prop('checked', true);} 
			else {$('#formulario #LOG_ALTVENDA').prop('checked', false);}
                       
			if ($("#ret_LOG_QUALICAD_"+index).val() == 'S'){$('#formulario #LOG_QUALICAD').prop('checked', true);} 
			else {$('#formulario #LOG_QUALICAD').prop('checked', false);}

			if ($("#ret_LOG_CREDAVULSO_"+index).val() == 'S'){$('#formulario #LOG_CREDAVULSO').prop('checked', true);} 
			else {$('#formulario #LOG_CREDAVULSO').prop('checked', false);}
			
			if ($("#ret_LOG_PDVMANU_"+index).val() == '1'){$('#formulario #LOG_PDVMANU').prop('checked', true);} 
			else {$('#formulario #LOG_PDVMANU').prop('checked', false);}

			if ($("#ret_LOG_NEGATIVO_"+index).val() == 'S'){$('#formulario #LOG_NEGATIVO').prop('checked', true);} 
			else {$('#formulario #LOG_NEGATIVO').prop('checked', false);}

			// alert($("#ret_LOG_NEGATIVO_"+index).val());
			
			$("#formulario #LOG_CADVENDEDOR").val($("#ret_LOG_CADVENDEDOR_"+index).val()).trigger("chosen:updated");
			$("#formulario #COD_VERSAOINTEGRA").val($("#ret_COD_VERSAOINTEGRA_"+index).val()).trigger("chosen:updated");
			$("#formulario #COD_INTEGRADORA").val($("#ret_COD_INTEGRADORA_"+index).val()).trigger("chosen:updated");
			$("#formulario #COD_PLATAFORMA").val($("#ret_COD_PLATAFORMA_"+index).val()).trigger("chosen:updated");
			
                       
			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');						
		}	
		
		
    $('.upload').on('click', function (e) {
        var idField = 'arqUpload_' + $(this).attr('idinput');
        var typeFile = $(this).attr('extensao');

        $.dialog({
            title: 'Arquivo',
            content: '' +
                    '<form method = "POST" enctype = "multipart/form-data">' +
                    '<input id="' + idField + '" type="file" name="image" style="margin-bottom: 20px;" />' +
                    '<div class="progress" style="display: none">' +
                    '<div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width:0%;">'+
                    '   <span style="position: absolute; display: block; width: 100%; color:#2c3e50;">12</span></div>' +
                    '</div>' +
                    '<a type="button" id="btnUploadFile" class="btn btn-primary btn-sm" style="font-weight: bold" onClick="uploadFile(\'' + idField + '\', \'' + typeFile + '\')">UPLOAD</a>' +
                    '</form>'
        });
    });

    function uploadFile(idField, typeFile) {
        var formData = new FormData();
        var nomeArquivo = $('#' + idField)[0].files[0]['name'];

        formData.append('arquivo', $('#' + idField)[0].files[0]);
        formData.append('diretorio', '../media/clientes/');
        formData.append('id', <?php echo $cod_empresa ?>);
        formData.append('typeFile', typeFile);

        $('.progress').show();
        $.ajax({
            xhr: function () {
                var xhr = new window.XMLHttpRequest();
                $('#btnUploadFile').addClass('disabled');
                xhr.upload.addEventListener("progress", function (evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = evt.loaded / evt.total;
                        percentComplete = parseInt(percentComplete * 100);
                        if (percentComplete !== 100) {
                            $('.progress-bar').css('width', percentComplete + "%");
                            $('.progress-bar > span').html(percentComplete + "%");
                        }
                    }
                }, false);
                return xhr;
            },
            url: '../uploads/uploaddoc.php',
            type: 'POST',
            data: formData,
            processData: false, // tell jQuery not to process the data
            contentType: false, // tell jQuery not to set contentType
            success: function (data) {
                $('.jconfirm-open').fadeOut(300, function () {
                    $(this).remove();
                });
                if (!data.trim()) {
                    $('#' + idField.replace("arqUpload_", "")).val(nomeArquivo);
                    $.alert({
                        title: "Mensagem",
                        content: "Upload feito com sucesso",
                        type: 'green'
                    });

                } else {
                    $.alert({
                        title: "Erro ao efetuar o upload",
                        content: data,
                        type: 'red'
                    });
                }
            }
        });
    }		
		
	</script>	