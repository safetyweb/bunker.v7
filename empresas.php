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
			$pct_parceiro = fnLimpacampo($_REQUEST['PCT_PARCEIRO']);

			$filtro = fnLimpaCampo($_POST['VAL_PESQUISA']);			
			$val_pesquisa = fnLimpaCampo($_POST['INPUT']);

			//array dos sistemas da empresas
			if (isset($_POST['COD_SISTEMAS'])){
				$Arr_COD_SISTEMAS = $_POST['COD_SISTEMAS'];
				//print_r($Arr_COD_SISTEMAS);			 
			 
			   for ($i=0;$i<count($Arr_COD_SISTEMAS);$i++) 
			   { 
				$cod_sistemas = $cod_sistemas.$Arr_COD_SISTEMAS[$i].",";
			   } 
			   
			   $cod_sistemas = substr($cod_sistemas,0,-1);
				
			}else{$cod_sistemas = "";}
			$cod_master = fnLimpacampo($_REQUEST['COD_MASTER']);
			$cod_layout = fnLimpacampo($_REQUEST['COD_LAYOUT']);
			$cod_segment = fnLimpacampo($_REQUEST['COD_SEGMENT']);
			
			if (empty($_REQUEST['LOG_CONSEXT'])) {$log_consext='N';}else{$log_consext=$_REQUEST['LOG_CONSEXT'];}
			if (empty($_REQUEST['LOG_AUTOCAD'])) {$log_autocad='N';}else{$log_autocad=$_REQUEST['LOG_AUTOCAD'];}
			$cod_chaveco = fnLimpacampo($_REQUEST['COD_CHAVECO']);			
			
			if (empty($_REQUEST['LOG_INTEGRADORA'])) {$log_integradora ='N';}else{$log_integradora =$_REQUEST['LOG_INTEGRADORA'];}
			$des_patharq = fnLimpacampo($_REQUEST['DES_PATHARQ']);			
			$cod_integradora = fnLimpacampoZero($_REQUEST['COD_INTEGRADORA']);
			$site = fnLimpacampo($_REQUEST['SITE']);			

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
				 '".$num_cgcecpf."', 
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
				 '".$tip_regvenda."',
				 '".fnValorsql($pct_parceiro)."', 
				 '".$opcao."'    
				) ";
				
				//echo $sql;
				
				fnTestesql($connAdm->connAdm(),trim($sql)) or die(mysqli_error());				
				
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
											<legend>Dados Gerais</legend> 
											
												<div class="row">	

													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Empresa Ativa</label> 
															<div class="push5"></div>
																<label class="switch">
																<input type="checkbox" name="LOG_ATIVO" id="LOG_ATIVO" class="switch" value="S" >
																<span></span>
																</label>
														</div>
													</div>

													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Consulta Automática de Cadastro (Externa)</label> 
															<div class="push5"></div>
																<label class="switch">
																<input type="checkbox" name="LOG_CONSEXT" id="LOG_CONSEXT" class="switch" value="S" >
																<span></span>
																</label>
														</div>
													</div>

													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Cadastro Automático de Clientes</label> 
															<div class="push5"></div>
																<label class="switch">
																<input type="checkbox" name="LOG_AUTOCAD" id="LOG_AUTOCAD" class="switch" value="S" >
																<span></span>
																</label>
														</div>
													</div>
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Controle de Preço <br/>por Loja</label> 
															<div class="push5"></div>
																<label class="switch">
																<input type="checkbox" name="LOG_PRECUNI" id="LOG_PRECUNI" class="switch" value="S" >
																<span></span>
																</label>
														</div>
													</div>
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Controla <br/>Estoque</label> 
															<div class="push5"></div>
																<label class="switch">
																<input type="checkbox" name="LOG_ESTOQUE" id="LOG_ESTOQUE" class="switch" value="S" >
																<span></span>
																</label>
														</div>
													</div>	
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Empresa <br/>Integradora</label> 
															<div class="push5"></div>
																<label class="switch">
																<input type="checkbox" name="LOG_INTEGRADORA" id="LOG_INTEGRADORA" class="switch" value="S" >
																<span></span>
																</label>
														</div>
													</div>

												</div>

												<div class="push10"></div>

												<div class="row">	
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Banco de <br/>Dados</label> 
															<div class="push5"></div>
																<label class="switch">
																<input type="checkbox" name="COD_DATABASE" id="COD_DATABASE" class="switch" value="S" >
																<span></span>
																</label>
														</div>
													</div>
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Set Up<br/>Completo</label> 
															<div class="push5"></div>
																<label class="switch">
																<input type="checkbox" name="LOG_CONFIGU" id="LOG_CONFIGU" class="switch" value="S" >
																<span></span>
																</label>
														</div>
													</div>

													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Ativar<br/>Log WS</label> 
															<div class="push5"></div>
																<label class="switch">
																<input type="checkbox" name="LOG_WS" id="LOG_WS" class="switch" value="S" >
																<span></span>
																</label>
														</div>
													</div>		
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Pontuar <br/>Funcionários</label> 
															<div class="push5"></div>
																<label class="switch">
																<input type="checkbox" name="LOG_PONTUAR" id="LOG_PONTUAR" class="switch" value="S" >
																<span></span>
																</label>
														</div>
													</div>	
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Pontuar após<br/>ativação de cadastro</label> 
															<div class="push5"></div>
																<label class="switch">
																<input type="checkbox" name="LOG_ATIVCAD" id="LOG_ATIVCAD" class="switch" value="S" >
																<span></span>
																</label>
														</div>
													</div>														
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Permitir<br/>venda avulsa</label> 
															<div class="push5"></div>
																<label class="switch">
																<input type="checkbox" name="LOG_AVULSO" id="LOG_AVULSO" class="switch" value="S" >
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
																<label class="switch">
																<input type="checkbox" name="LOG_CATEGORIA" id="LOG_CATEGORIA" class="switch" value="S" >
																<span></span>
																</label>
														</div>
													</div>
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Permite alteração<br/>de venda</label> 
															<div class="push5"></div>
																<label class="switch">
																<input type="checkbox" name="LOG_ALTVENDA" id="LOG_ALTVENDA" class="switch" value="S" >
																<span></span>
																</label>
														</div>
													</div>	
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Controle de Qualidade<br/>de Cadastros</label> 
															<div class="push5"></div>
																<label class="switch">
																<input type="checkbox" name="LOG_QUALICAD" id="LOG_QUALICAD" class="switch" value="S" >
																<span></span>
																</label>
														</div>
													</div>
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Créditos manuais<br/>no PDV virtual</label> 
															<div class="push5"></div>
																<label class="switch">
																<input type="checkbox" name="LOG_PDVMANU" id="LOG_PDVMANU" class="switch" value="1" >
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
													
													<div class="col-md-1 borda">
														<div class="form-group">
															<label for="inputName" class="control-label">Avulso</label>
															<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_CLIENTE_AV" id="COD_CLIENTE_AV" value="">
														</div>
													</div>
													
													<div class="col-md-4">
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
																<select data-placeholder="Selecione um status" name="COD_ESTATUS" id="COD_ESTATUS" class="chosen-select-deselect requiredChk" required>
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
															<input type="text" class="form-control input-sm" name="DES_SUFIXO" id="DES_SUFIXO" maxlength="10" value="" data-error="Campo obrigatório" required>
															<div class="help-block with-errors"></div>
														</div>
													</div>

													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label required">Chave Identificação</label>
																<select data-placeholder="Selecione a chave de identificação" name="COD_CHAVECO" id="COD_CHAVECO" class="chosen-select-deselect" required>
																	<option value=""></option>
																	<?php 
																	
																		$sql = "select * from CHAVECADASTRO order by DES_CHAVECO";
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
															<label for="inputName" class="control-label">Contabilização</label>
																<select data-placeholder="Selecione a forma de contabilização" name="TIP_CONTABIL" id="TIP_CONTABIL" class="chosen-select-deselect">
																	<option value=""></option>
																	<option value="DESC">Desconto</option>
																	<option value="RESG">Resgate</option>
																</select>
															<div class="help-block with-errors"></div>
														</div>
													</div>	
																				
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Regra de Entrada de venda</label>
																<select data-placeholder="Tipo da entrada de venda" name="TIP_REGVENDA" id="TIP_REGVENDA" class="chosen-select-deselect">
																	<option value=""></option>
																	<option value="1">Crítica Padrão</option>
																	<option value="2">Permitir data/hora iguais</option>
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
																							
												</div>
																	
												<div class="row">
													
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
																	
																		$sql = "SELECT COD_SISTEMAS FROM EMPRESAS WHERE COD_EMPRESA = 3 ";
																		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
																		$qrBuscaSistema = mysqli_fetch_assoc($arrayQuery);
																		$sistemasMarka = $qrBuscaSistema['COD_SISTEMAS'];
																		
																		$sql = "SELECT COD_SISTEMA, DES_SISTEMA FROM SISTEMAS WHERE COD_SISTEMA IN (".$sistemasMarka.") order by DES_SISTEMA ";
																		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
																	
																		while ($qrListaSistemas = mysqli_fetch_assoc($arrayQuery))
																		  {	
																			if ($qrListaSistemas['COD_SISTEMA'] == 'S') {
																				$mostraAutoriza = '<i class="fa fa-check-square-o" aria-hidden="true"></i>';	
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
															<label for="inputName" class="control-label">Plataforma</label>
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
															<label for="inputName" class="control-label">Versão da Integração</label>
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

													<div class="col-md-2 borda">
														<div class="form-group">
															<label for="inputName" class="control-label">Porcentagem do Parceiro</label>
															<input type="text" class="form-control input-sm money" name="PCT_PARCEIRO" id="PCT_PARCEIRO" value="0">
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
															<input type="text" class="form-control input-sm cpf" name="NUM_CGCECPF" id="NUM_CGCECPF" maxlength="18" data-error="Campo obrigatório">
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

													<div class="col-md-3 borda">
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
													
													<!-- <div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label required">Layout</label>
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
													</div> -->	
													
													<!-- <div class="col-md-3">
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
													
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Integradora</label>
																<select data-placeholder="Selecione a integradora" name="COD_INTEGRADORA" id="COD_INTEGRADORA" class="chosen-select-deselect">
																	<option value=""></option>
																	<?php 
																	
																		$sql = "select COD_EMPRESA, NOM_FANTASI from empresas where COD_EMPRESA <> 1 and LOG_INTEGRADORA = 'S' order by NOM_FANTASI";
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
													</div> -->
													
												</div>
												
												<div class="row">
												
													<div class="col-md-6 borda">
														<div class="form-group">
															<label for="inputName" class="control-label">Path Arquivos</label>
															<input type="text" class="form-control input-sm" name="DES_PATHARQ" id="DES_PATHARQ" maxlength="250">
															<div class="help-block with-errors"></div>
														</div>
													</div>

													<div class="col-md-6">
														<div class="form-group">
															<label for="inputName" class="control-label">Site</label>
															<input type="text" class="form-control input-sm" name="SITE" id="SITE" maxlength="100">
															<div class="help-block with-errors"></div>
														</div>
													</div>	
												
												</div>
												
												<!-- <div class="row">
													<div class="col-md-12">
														<div class="form-group">
															<label for="inputName" class="control-label required">Sistemas</label>
																<select data-placeholder="Selecione um sistema" name="COD_SISTEMAS[]" id="COD_SISTEMAS" multiple="multiple" class="chosen-select-deselect requiredChk" style="width:100%;" tabindex="1" required>
																	<?php 
																	
																		$sql = "SELECT COD_SISTEMA,DES_SISTEMA FROM sistemas order by DES_SISTEMA ";
																		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
																	
																		while ($qrListaSistemas = mysqli_fetch_assoc($arrayQuery))
																		  {	
																			if ($qrListaSistemas['COD_SISTEMA'] == 'S') {
																				$mostraAutoriza = '<i class="fa fa-check-square-o" aria-hidden="true"></i>';	
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
												</div> -->																	
												
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
												
												<table class="table table-bordered table-striped table-hover buscavel">
												  <thead>
													<tr>
													  <th width="40"></th>
													  <th>Código</th>
													  <th>Nome da Empresa</th>
													  <th>Nome Fantasia</th>
													  <th>Responsável</th>
													  <th>Sufixo</th>
													  <th>% Parc.</th>
													  <th>Telefones</th>
													  <th>Ativo</th>
													  <th>BD</th>
													</tr>
												  </thead>
												<tbody>
												  
												<?php

												if($filtro != ""){
														$andFiltro = " AND $filtro LIKE '%$val_pesquisa%' ";
													}else{
														$andFiltro = " ";
													}
												
													$sql = "SELECT 
															STATUSSISTEMA.DES_STATUS,
															empresas.*, 
															B.COD_DATABASE, 
															B.NOM_DATABASE 
															FROM empresas 
															LEFT JOIN STATUSSISTEMA ON STATUSSISTEMA.COD_STATUS=empresas.COD_STATUS 
															LEFT JOIN tab_database B ON B.cod_empresa = empresas.COD_EMPRESA
															WHERE empresas.COD_EMPRESA <> 1
															$andFiltro															
															ORDER by NOM_FANTASI
													";
													
													$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
													
													$count=0;
													while ($qrListaEmpresas = mysqli_fetch_assoc($arrayQuery))
													  {														  
														$count++;
														  if ($qrListaEmpresas['LOG_ATIVO'] == 'S'){		
																$mostraAtivo = '<i class="fa fa-check-square-o" aria-hidden="true"></i>';	
															}else{ $mostraAtivo = ''; }
														
														  if ($qrListaEmpresas['COD_DATABASE'] > 0){
															if ($qrListaEmpresas['NOM_DATABASE'] == "db_host1" ){
																$mostraAtivoBD = '<i class="fa fa-clock-o" aria-hidden="true"></i>';
																$mostraEmpresa = "<a href='action.do?mod=".fnEncode(1020)."&id=".fnEncode($qrListaEmpresas['COD_EMPRESA'])."'>".$qrListaEmpresas['NOM_EMPRESA']."</a>";	
															}else{
																$mostraAtivoBD = '<i class="fa fa-check-square-o" aria-hidden="true"></i>';		
																$mostraEmpresa = "<a href='action.do?mod=".fnEncode(1020)."&id=".fnEncode($qrListaEmpresas['COD_EMPRESA'])."'>".$qrListaEmpresas['NOM_EMPRESA']."</a>";	
															}	
														  }else{ 
															$mostraAtivoBD = ''; 
															$mostraEmpresa = $qrListaEmpresas['NOM_EMPRESA'];	
														  }	
														
														  if (!empty($qrListaEmpresas['COD_SISTEMAS'])){
															  $tem_sistema = "tem";															  
														  }	else {$tem_sistema = "nao";}
														
														echo"
															<tr>
															  <td><input type='radio' name='radio1' onclick='retornaForm(".$count.")'></th>
															  <td>".$qrListaEmpresas['COD_EMPRESA']."</td>
															  <td>".$mostraEmpresa."</td>
															  <td>".$qrListaEmpresas['NOM_FANTASI']."</td>
															  <td>".$qrListaEmpresas['NOM_RESPONS']."</td>
															  <td>.".$qrListaEmpresas['DES_SUFIXO']."</td>
															  <td>".fnValor($qrListaEmpresas['PCT_PARCEIRO'],2)."</td>
															  <td>".$qrListaEmpresas['NUM_TELEFON']." / ".$qrListaEmpresas['NUM_CELULAR']."</td>
															  <td align='center'>".$mostraAtivo."</td>
															  <td align='center'>".$mostraAtivoBD."</td>
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
															<input type='hidden' id='ret_COD_CHAVECO_".$count."' value='".$qrListaEmpresas['COD_CHAVECO']."'>
															<input type='hidden' id='ret_COD_CLIENTE_AV_".$count."' value='".$qrListaEmpresas['COD_CLIENTE_AV']."'>
															<input type='hidden' id='ret_LOG_CONFIGU_".$count."' value='".$qrListaEmpresas['LOG_CONFIGU']."'>
															<input type='hidden' id='ret_TIP_CONTABIL_".$count."' value='".$qrListaEmpresas['TIP_CONTABIL']."'>
															<input type='hidden' id='ret_LOG_INTEGRADORA_".$count."' value='".$qrListaEmpresas['LOG_INTEGRADORA']."'>
															<input type='hidden' id='ret_DES_PATHARQ_".$count."' value='".$qrListaEmpresas['DES_PATHARQ']."'>
															<input type='hidden' id='ret_COD_INTEGRADORA_".$count."' value='".$qrListaEmpresas['COD_INTEGRADORA']."'>
															<input type='hidden' id='ret_SITE_".$count."' value='".$qrListaEmpresas['SITE']."'>
															<input type='hidden' id='ret_TEM_SISTEMAS_".$count."' value='".$tem_sistema."'>
															<input type='hidden' id='ret_COD_DATABASE_".$count."' value='".$qrListaEmpresas['COD_DATABASE']."'>
															<input type='hidden' id='ret_TIP_REGVENDA_".$count."' value='".$qrListaEmpresas['TIP_REGVENDA']."'>
															<input type='hidden' id='ret_PCT_PARCEIRO_".$count."' value='".fnValor($qrListaEmpresas['PCT_PARCEIRO'],2)."'>
															"; 
														  }	
													  
													
												?>
													
												</tbody>
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
	
	<script type="text/javascript">
	
		$(document).ready(function(){
			
			//chosen obrigatório
			$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
			$('#formulario').validator();

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
			$("#formulario #COD_CLIENTE_AV").val($("#ret_COD_CLIENTE_AV_"+index).val());
			$("#formulario #PCT_PARCEIRO").val($("#ret_PCT_PARCEIRO_"+index).val());
			
			if ($("#ret_LOG_CONSEXT_"+index).val() == 'S'){$('#formulario #LOG_CONSEXT').prop('checked', true);} 
			else {$('#formulario #LOG_CONSEXT').prop('checked', false);}			
			if ($("#ret_LOG_AUTOCAD_"+index).val() == 'S'){$('#formulario #LOG_AUTOCAD').prop('checked', true);} 
			else {$('#formulario #LOG_AUTOCAD').prop('checked', false);}			
			$("#formulario #COD_CHAVECO").val($("#ret_COD_CHAVECO_"+index).val()).trigger("chosen:updated");
			
			$("#formulario #TIP_CONTABIL").val($("#ret_TIP_CONTABIL_"+index).val()).trigger("chosen:updated");
			if ($("#ret_LOG_CONFIGU_"+index).val() == 'S'){$('#formulario #LOG_CONFIGU').prop('checked', true);} 
			else {$('#formulario #LOG_CONFIGU').prop('checked', false);}

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
			$("#formulario #COD_INTEGRADORA").val($("#ret_COD_INTEGRADORA_"+index).val()).trigger("chosen:updated");
			$("#formulario #SITE").val($("#ret_SITE_"+index).val());

			if ($("#ret_COD_DATABASE_"+index).val() > 0){$('#formulario #COD_DATABASE').prop('checked', true);} 
			else {$('#formulario #COD_DATABASE').prop('checked', false);}

			$("#formulario #TIP_REGVENDA").val($("#ret_TIP_REGVENDA_"+index).val()).trigger("chosen:updated");
			
			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');						
		}	
		
		
	</script>	