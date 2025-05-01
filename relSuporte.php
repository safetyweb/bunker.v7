<?php

	// definir o numero de itens por pagina
	$itens_por_pagina = 50;	
	$pagina  = "1";
	
	$dias30="";
	$dat_ini="";
	$dat_fim="";
	
	$cod_externo = "";
	$cod_empresa = "";
	$nom_chamado = "";

	$cod_tpsolicitacao = "";
	$cod_status = "";
	$cod_integradora = "";
	$cod_plataforma = "";
	$cod_versaointegra = "";
	$cod_prioridade = "";

	$hashLocal = mt_rand();

	//inicialização de variáveis
	$hoje = fnFormatDate(date("Y-m-d"));
	//$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 1 days')));
	$dias30 = fnFormatDate(date("Y-m-d"));
	
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
			
			$dat_ini = fnDataSql($_POST['DAT_INI']);
			$dat_fim = fnDataSql($_POST['DAT_FIM']);
			$dat_ini_ent = fnDataSql($_POST['DAT_INI_ENT']);
			$dat_fim_ent = fnDataSql($_POST['DAT_FIM_ENT']);
			$cod_chamado = $_POST['COD_CHAMADO'];
			$cod_externo = $_POST['COD_EXTERNO'];
			$cod_empresa = $_POST['COD_EMPRESA'];
			$nom_chamado = $_POST['NOM_CHAMADO'];

			$cod_tpsolicitacao = $_POST['COD_TPSOLICITACAO'];
			$cod_status = $_POST['COD_STATUS'];
			$cod_status_exc = $_POST['COD_STATUS_EXC'];
			$cod_integradora = $_POST['COD_INTEGRADORA'];
			$cod_plataforma = $_POST['COD_PLATAFORMA'];
			$cod_versaointegra = $_POST['COD_VERSAOINTEGRA'];
			$cod_prioridade = $_POST['COD_PRIORIDADE'];
			$cod_usuario = $_POST['COD_USUARIO'];
			$cod_usures = $_POST['COD_USURES'];
			
			
			// fnEscreve($dat_ini_ent);
			// fnEscreve($dat_fim_ent);

			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
			$usu_cadastr = $_SESSION["SYS_COD_USUARIO"];
						
			if ($opcao != ''){
				
				
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

	if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31" ){
		$dat_ini = fnDataSql($dias30); 
	} 
	if (strlen($dat_fim ) == 0 || $dat_fim == "1969-12-31"){
		$dat_fim = fnDataSql($hoje); 
	}

	if (strlen($dat_ini_ent) == 0 || $dat_ini_ent == "1969-12-31" ){
		$dat_ini_ent = fnDataSql($dias30); 
	} 
	if (strlen($dat_fim_ent ) == 0 || $dat_fim_ent == "1969-12-31"){
		$dat_fim_ent = ""; 
	}

	if(isset($_GET['x'])){
		$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";
		$msgTipo = 'alert-success';
	}
	
	//fnEscreve($cod_empresa);	
	//fnEscreve($nom_empresa);	
	
	//fnMostraForm('#formulario');



?>

<style type="text/css">
	
table a:not(.btn), .table a:not(.btn) {
	text-decoration: none;
}
table a:not(.btn):hover, .table a:not(.btn):hover {
	text-decoration: underline;
}

.badge{
    display: table-cell;
    border-radius: 30px 30px 30px 30px;
    width: 26px;
    height: 26px;
    /*text-align: center;*/
    color:white;
    font-size:11px;
    /*margin-right: auto;
    margin-left: auto;*/
}

.txtBadge{
	display: table-cell;
	vertical-align: middle;
}

.txtSideBadge{
	position: relative;
	display: table-cell;
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
										<span class="text-primary"><?php echo $NomePg; ?></span>
									</div>
									
									<?php 
									$formBack = "1019";
									include "atalhosPortlet.php"; 
									?>	

								</div>
								<div class="portlet-body">
									
									<?php if ($msgRetorno <> '') { ?>	
									<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
									<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									 <?php echo $msgRetorno; ?>
									</div>
									<?php } ?>
									
									<div class="push20"></div>
			
									<div class="login-form">
									
										<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

											<fieldset>
												<legend>Filtros</legend>
											
												<div class="row" >

													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Código Externo</label>
															<input type="text" class="form-control input-sm" name="COD_EXTERNO" id="COD_EXTERNO" maxlength="45" value="<?php echo $cod_externo; ?>">
														</div>
													</div>

													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Código do Chamado</label>
															<input type="text" class="form-control input-sm" name="COD_CHAMADO" id="COD_CHAMADO" maxlength="45" value="<?php echo $cod_externo; ?>">
														</div>
													</div>

													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Empresa</label>
															<select class="chosen-select-deselect requiredChk" data-placeholder="Selecione a empresa" name="COD_EMPRESA" id="COD_EMPRESA">
																<option value=""></option>
																<?php 
																	
																		//$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM EMPRESAS";
																		if ($_SESSION["SYS_COD_MASTER"] == "2" ) {
																		$sql = "SELECT empresas.COD_EMPRESA, empresas.NOM_FANTASI
																				FROM empresas  
																				WHERE empresas.COD_EMPRESA <> 1 
																				$andFiltro
																				ORDER by NOM_FANTASI
																		";
																		//fnEscreve("1");
																		}else {
																		$sql = "SELECT empresas.COD_EMPRESA, empresas.NOM_FANTASI
																				FROM empresas  
																				WHERE COD_EMPRESA IN (".$_SESSION["SYS_COD_MULTEMP"].")
																				$andFiltro
																				ORDER by NOM_FANTASI
																		";
																		//fnEscreve("2");
																		}
																		
																		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
																	
																		while ($qrEmpresa = mysqli_fetch_assoc($arrayQuery))
																		  {
																		  	?>
																		  	<option value="<?php echo $qrEmpresa['COD_EMPRESA']; ?>"><?php echo $qrEmpresa['NOM_FANTASI']; ?></option>
																		  	<?php } ?>
															</select>
															<div class="help-block with-errors"></div>
														</div>														
													</div>

													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Título do Chamado</label>
															<input type="text" class="form-control input-sm" name="NOM_CHAMADO" id="NOM_CHAMADO" maxlength="50" value="<?php echo $nom_chamado; ?>">
														</div>
													</div>

													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Tipo de Solicitação</label>
															<select class="chosen-select-deselect requiredChk" data-placeholder="Selecione o tipo" name="COD_TPSOLICITACAO" id="COD_TPSOLICITACAO">
																<option value=""></option>
																<?php 
																	
																		$sql = "SELECT * FROM SAC_TPSOLICITACAO";
																		$arrayQuery = mysqli_query($connAdmSAC->connAdm(),$sql) or die(mysqli_error());
																	
																		while ($qrSolicitacao = mysqli_fetch_assoc($arrayQuery))
																		  {
																		  	?>
																		  	<option value="<?php echo $qrSolicitacao['COD_TPSOLICITACAO']; ?>"><?php echo $qrSolicitacao['DES_TPSOLICITACAO']; ?></option>
																		  	<?php } ?>
															</select>
															<div class="help-block with-errors"></div>
														</div>
													</div>

													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Status</label>
															<select class="chosen-select-deselect requiredChk" data-placeholder="Selecione o status" name="COD_STATUS" id="COD_STATUS">
																<option value=""></option>
																<?php 
																	
																		$sql = "SELECT * FROM SAC_STATUS";
																		$arrayQuery = mysqli_query($connAdmSAC->connAdm(),$sql) or die(mysqli_error());
																	
																		while ($qrStatus = mysqli_fetch_assoc($arrayQuery))
																		  {
																		  	?>
																		  	<option value="<?php echo $qrStatus['COD_STATUS']; ?>"><?php echo $qrStatus['DES_STATUS']; ?></option>
																		  	<?php } ?>
															</select>
															<div class="help-block with-errors"></div>
														</div>
													</div>

												</div>

												<div class="row">

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

													<div class="col-md-1">
														<div class="form-group">
															<label for="inputName" class="control-label">Plataforma</label>
															<select class="chosen-select-deselect requiredChk" data-placeholder="Plataforma" name="COD_PLATAFORMA" id="COD_PLATAFORMA">
																<option value=""></option>
																	<?php 
																	
																		$sql = "SELECT * FROM SAC_PLATAFORMA";
																		$arrayQuery = mysqli_query($connAdmSAC->connAdm(),$sql) or die(mysqli_error());
																	
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
																		$arrayQuery = mysqli_query($connAdmSAC->connAdm(),$sql) or die(mysqli_error());
																	
																		while ($qrIntegracao = mysqli_fetch_assoc($arrayQuery))
																		  {
																		  	?>
																		  	<option value="<?php echo $qrIntegracao['COD_VERSAOINTEGRA']; ?>"><?php echo $qrIntegracao['DES_VERSAOINTEGRA']; ?></option>
																		  	<?php } ?>
															</select>
															<div class="help-block with-errors"></div>
														</div>
													</div>

													<div class="col-md-1"> 
														<div class="form-group">
															<label for="inputName" class="control-label">Prioridade</label>
															<select class="chosen-select-deselect requiredChk" data-placeholder="Prioridade" name="COD_PRIORIDADE" id="COD_PRIORIDADE">
																<option value=""></option>
																<?php 
																	
																		$sql = "SELECT * FROM SAC_PRIORIDADE";
																		$arrayQuery = mysqli_query($connAdmSAC->connAdm(),$sql) or die(mysqli_error());
																	
																		while ($qrPrioridade = mysqli_fetch_assoc($arrayQuery))
																		  {
																		  	?>
																		  	<option value="<?php echo $qrPrioridade['COD_PRIORIDADE']; ?>"><?php echo $qrPrioridade['DES_PRIORIDADE']; ?></option>
																		  	<?php } ?>
															</select>
															<div class="help-block with-errors"></div>
														</div>
													</div>

													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Responsável TI</label>
																<select data-placeholder="Selecione um usuário" name="COD_USURES" id="COD_USURES" class="chosen-select-deselect requiredChk" style="width:100%;">
																	<option value=""></option>
																	<option value="">Todos os Responsáveis</option>
																	<optgroup label="Usuários Marka">
																    <?php 
																
																		$sql = "SELECT COD_USUARIO, NOM_USUARIO from usuarios 
																		where (usuarios.COD_EMPRESA = 2 OR usuarios.COD_EMPRESA = 3)
																		and usuarios.DAT_EXCLUSA is null 
																		AND COD_TPUSUARIO IN(9,6,1,3) 
																		AND LOG_ESTATUS = 'S' order by  usuarios.NOM_USUARIO ";
																		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
																	
																		while ($qrLista = mysqli_fetch_assoc($arrayQuery))
																		  {														
																			echo"
																			  <option value='".$qrLista['COD_USUARIO']."'>".$qrLista['NOM_USUARIO']."</option> 
																			"; 
																		  }											
																	?> 
																    </optgroup>
																</select>
															<div class="help-block with-errors"></div>
														</div>
													</div>												

													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Solicitante</label>
																<div id="relatorioUsu">
																	<select data-placeholder="Usuários Marka" name="COD_USUARIO" id="COD_USUARIO" class="chosen-select-deselect requiredChk" style="width:100%;">
																		
																	</select>
																</div>
															<div class="help-block with-errors">requisito: selecionar empresa</div>
														</div>
													</div>

												</div>												

												<div class="row">

													

													<div class="col-md-4">

														<fieldset>
															<legend>Data de Cadastro</legend>

																<div class="col-md-6">
																	<div class="form-group">
																		<label for="inputName" class="control-label">Data Inicial</label>
																		
																		<div class="input-group date datePicker" id="DAT_CHAMADO_GRP">
																			<input type='text' class="form-control input-sm data" name="DAT_INI" id="DAT_INI" value=""/>
																			<span class="input-group-addon">
																				<span class="glyphicon glyphicon-calendar"></span>
																			</span>
																		</div>
																		<div class="help-block with-errors"></div>
																	</div>
																</div>
																
																<div class="col-md-6">
																	<div class="form-group">
																		<label for="inputName" class="control-label">Data Final</label>
																		
																		<div class="input-group date datePicker" id="DAT_CHAMADO_GRP">
																			<input type='text' class="form-control input-sm data" name="DAT_FIM" id="DAT_FIM" value="<?php echo fnFormatDate($dat_fim); ?>"/>
																			<span class="input-group-addon">
																				<span class="glyphicon glyphicon-calendar"></span>
																			</span>
																		</div>
																		<div class="help-block with-errors"></div>
																	</div>
																</div>

														</fieldset>

													</div>

													<div class="col-md-4">

														<fieldset>
															<legend>Data de Entrega</legend>

																<div class="col-md-6">
																	<div class="form-group">
																		<label for="inputName" class="control-label">Data Inicial</label>
																		
																		<div class="input-group date datePicker" id="DAT_CHAMADO_GRP">
																			<input type='text' class="form-control input-sm data" name="DAT_INI_ENT" id="DAT_INI_ENT" value=""/>
																			<span class="input-group-addon">
																				<span class="glyphicon glyphicon-calendar"></span>
																			</span>
																		</div>
																		<div class="help-block with-errors"></div>
																	</div>
																</div>
																
																<div class="col-md-6">
																	<div class="form-group">
																		<label for="inputName" class="control-label">Data Final</label>
																		
																		<div class="input-group date datePicker" id="DAT_CHAMADO_GRP">
																			<input type='text' class="form-control input-sm data" name="DAT_FIM_ENT" id="DAT_FIM_ENT" value="<?php echo fnFormatDate($dat_fim_ent); ?>"/>
																			<span class="input-group-addon">
																				<span class="glyphicon glyphicon-calendar"></span>
																			</span>
																		</div>
																		<div class="help-block with-errors"></div>
																	</div>
																</div>

														</fieldset>

													</div>

													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Remover Status</label>
															<select class="chosen-select-deselect requiredChk" data-placeholder="Selecione o status" name="COD_STATUS_EXC" id="COD_STATUS_EXC">
																<option value=""></option>
																<?php 
																	
																		$sql = "SELECT * FROM SAC_STATUS";
																		$arrayQuery = mysqli_query($connAdmSAC->connAdm(),$sql) or die(mysqli_error());
																	
																		while ($qrStatus = mysqli_fetch_assoc($arrayQuery))
																		  {
																		  	?>
																		  	<option value="<?php echo $qrStatus['COD_STATUS']; ?>"><?php echo $qrStatus['DES_STATUS']; ?></option>
																		  	<?php } ?>
															</select>
															<div class="help-block with-errors"></div>
														</div>
													</div>
													
													<div class="col-md-2">
														<div class="push20"></div>
														<button type="submit" name="BUS" id="BUS" class="btn btn-primary btn-sm btn-block getBtn"><i class="fa fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
													</div>

												</div>

											</fieldset>
																				
										
										<input type="hidden" name="opcao" id="opcao" value="">
										<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
										<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
																				
										</form>
										 
										<div class="push30"></div>
										
										<div class="col-lg-12" style="padding:0;">

											<div class="no-more-tables">
										
												<form name="formLista">
												
												<table class="table table-bordered table-striped table-hover tableSorter">
												  <thead>
													<tr>
													  <th><small>Chamado</small></th>
													  <th><small>Empresa</small></th>
													  <th><small>Título</small></th>
													  <th><small>Solicitante</small></th>
													  <th><small>Solicitação</small></th>
													  <th><small>Responsável</small></th>
													  <th><small>Prioridade</small></th>
													  <th><small>Status</small></th>
													  <th><small>Cadastro</small></th>
													  <th><small>Entrega</small></th>
													  <th><small>Atualizado</small></th>
													</tr>
												  </thead>
												<tbody id="relatorioConteudo">
												  
												<?php

													if($dat_ini == date('Y-m-d')){$datIniAND = " ";}else{$datIniAND = "DATE_FORMAT(SC.DAT_CADASTR, '%Y-%m-%d') >= '$dat_ini' AND ";}

													if($dat_fim == date('Y-m-d')){$dat_fim = fnDataSql($hoje);}

													if($dat_ini_ent == date('Y-m-d')){$ANDdatIniEnt = " ";}else{$ANDdatIniEnt = "AND DATE_FORMAT(SC.DAT_ENTREGA, '%Y-%m-%d') >= '$dat_ini_ent'";}

													if($dat_fim_ent == ""){$ANDdatFimEnt = " ";}else{$ANDdatFimEnt = "AND DATE_FORMAT(SC.DAT_ENTREGA, '%Y-%m-%d') <= '$dat_fim_ent'";}

													if($cod_chamado == ""){$ANDcodChamado = " ";}else{$ANDcodChamado = "AND SC.COD_CHAMADO = $cod_chamado ";}

													if($cod_externo == ""){$ANDcodExterno = " ";}else{$ANDcodExterno = "AND SC.COD_EXTERNO LIKE '%$cod_externo%' ";}													

													if($cod_empresa == ""){$ANDcodEmpresa = " ";}else{$ANDcodEmpresa = "AND SC.COD_EMPRESA = $cod_empresa ";}

													if($nom_chamado == ""){$ANDnomChamado = " ";}else{$ANDnomChamado = "AND SC.NOM_CHAMADO LIKE '%$nom_chamado%' ";}

													if($cod_tpsolicitacao == ""){$ANDcodTipo = " ";}else{$ANDcodTipo = "AND SC.COD_TPSOLICITACAO = $cod_tpsolicitacao ";}

													if($cod_status == ""){$ANDcodStatus = "AND SC.COD_STATUS != 10 ";}else{$ANDcodStatus = "AND SC.COD_STATUS = $cod_status ";}

													if($cod_status_exc == ""){$ANDcodStatusExc = "";}else{$ANDcodStatusExc = "AND SC.COD_STATUS != $cod_status_exc ";}

													if($cod_integradora == ""){$ANDcodIntegradora = " ";}else{$ANDcodIntegradora = "AND SC.COD_INTEGRADORA = $cod_integradora ";}

													if($cod_plataforma == ""){$ANDcodPlataforma = " ";}else{$ANDcodPlataforma = "AND SC.COD_PLATAFORMA = $cod_plataforma ";}

													if($cod_versaointegra == ""){$ANDcodVersaointegra = " ";}else{$ANDcodStatus = "AND SC.COD_VERSAOINTEGRA = $cod_versaointegra ";}

													if($cod_prioridade == ""){$ANDcodPrioridade = " ";}else{$ANDcodPrioridade = "AND SC.COD_PRIORIDADE = $cod_prioridade ";}

													if($cod_usuario == ""){$ANDcodUsuario = " ";}else{$ANDcodUsuario = "AND SC.COD_USUARIO = $cod_usuario ";}

													if($cod_usures == ""){$ANDcod_usures = " ";}else{$ANDcod_usures = "AND SC.COD_USURES = $cod_usures ";}

												
													$sqlCount = "SELECT COUNT(*) AS CONTADOR FROM SAC_CHAMADOS SC 
																WHERE
																$datIniAND
												  				DATE_FORMAT(SC.DAT_CADASTR, '%Y-%m-%d') <= '$dat_fim'
												  				$ANDcodExterno
												  				$ANDcodChamado
												  				$ANDcodEmpresa
												  				$ANDnomChamado
												  				$ANDcodStatus
												  				$ANDcodTipo
												  				$ANDcodIntegradora
												  				$ANDcodPlataforma
												  				$ANDcodVersaointegra
												  				$ANDcodPrioridade
												  				$ANDcod_usures
												  				$ANDcodUsuario
												  				$ANDcodStatusExc
												  				$ANDdatIniEnt
												  				$ANDdatFimEnt													  				
																ORDER BY SC.COD_PRIORIDADE ASC
																";
													// fnEscreve($sqlCount);
													
													$retorno = mysqli_query($connAdmSAC->connAdm(),$sqlCount) or die(mysqli_error());
													$total_itens_por_pagina = mysqli_fetch_assoc($retorno);
													
													$numPaginas = ceil($total_itens_por_pagina['CONTADOR']/$itens_por_pagina);	

													//variavel para calcular o início da visualização com base na página atual
													$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;													
												
													$sqlSac = "SELECT SC.COD_CHAMADO, SC.COD_EMPRESA, SC.NOM_CHAMADO, SC.COD_EXTERNO, 
																SC.DAT_CADASTR, SC.DAT_CHAMADO, SC.DAT_ENTREGA, SC.DES_PREVISAO, SC.COD_USUARIO,
																SC.COD_USURES, SC.LOG_ADM, SP.DES_PLATAFORMA, ST.DES_TPSOLICITACAO, 
																SV.DES_VERSAOINTEGRA, SPR.DES_PRIORIDADE, SPR.DES_COR AS COR_PRIORIDADE, SPR.DES_ICONE AS ICO_PRIORIDADE,
																SS.ABV_STATUS, SS.DES_COR AS COR_STATUS, SS.DES_ICONE AS ICO_STATUS,
																(SELECT MAX(SCM.DAT_CADASTRO) FROM SAC_COMENTARIO SCM WHERE SCM.COD_CHAMADO = SC.COD_CHAMADO) AS DAT_INTERAC
																FROM SAC_CHAMADOS SC 
																LEFT JOIN SAC_PLATAFORMA SP ON SP.COD_PLATAFORMA=SC.COD_PLATAFORMA
																LEFT JOIN SAC_TPSOLICITACAO ST ON ST.COD_TPSOLICITACAO=SC.COD_TPSOLICITACAO
																LEFT JOIN SAC_VERSAOINTEGRA SV ON SV.COD_VERSAOINTEGRA=SC.COD_VERSAOINTEGRA
																LEFT JOIN SAC_PRIORIDADE SPR ON SPR.COD_PRIORIDADE=SC.COD_PRIORIDADE
																LEFT JOIN SAC_STATUS SS ON SS.COD_STATUS=SC.COD_STATUS
																WHERE 
																$datIniAND
																DATE_FORMAT(SC.DAT_CADASTR, '%Y-%m-%d') <= '$dat_fim'
																$ANDcodExterno
																$ANDcodChamado
																$ANDcodEmpresa
																$ANDnomChamado
																$ANDcodStatus
																$ANDcodTipo
																$ANDcodIntegradora
																$ANDcodPlataforma
																$ANDcodVersaointegra
																$ANDcodPrioridade
																$ANDcod_usures
																$ANDcodUsuario
																$ANDcodStatusExc
																$ANDdatIniEnt
																$ANDdatFimEnt
																ORDER BY SC.COD_CHAMADO DESC limit $inicio,$itens_por_pagina
																";
													// fnEscreve($sqlSac);

													$arrayQuerySac = mysqli_query($connAdmSAC->connAdm(),$sqlSac) or die(mysqli_error());
													
													$count=0;
													$adm="";
													$entrega = "";
													while ($qrSac = mysqli_fetch_assoc($arrayQuerySac))
													 {	

													 	if($qrSac['LOG_ADM'] == 'S'){
													 		$adm = "<i class='fal fa-user-check shortCut' data-toggle='tooltip' data-placement='left' data-original-title='ti'></i>";
													 	}else{
													 		$adm = "<i class='fal fa-user-tie shortCut' data-toggle='tooltip' data-placement='left' data-original-title='cliente'></i>";
													 	}

														$count++;

														$sqlEmpresa = "SELECT NOM_FANTASI FROM EMPRESAS WHERE COD_EMPRESA = $qrSac[COD_EMPRESA]";
														$qrNomEmp = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(),$sqlEmpresa));

														$sqlUsuarios = "SELECT (SELECT NOM_USUARIO FROM USUARIOS WHERE COD_USUARIO = $qrSac[COD_USUARIO]) AS NOM_SOLICITANTE,
																				(SELECT NOM_USUARIO FROM USUARIOS WHERE COD_USUARIO = $qrSac[COD_USURES]) AS NOM_RESPONSAVEL";
														$qrNomUsu = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(),$sqlUsuarios));
														//fnEscreve($sqlUsuarios);										  

														if($qrSac['DAT_ENTREGA'] == "1969-12-31"){
															$entrega = "";
														}else{
															$entrega = fnDataShort($qrSac['DAT_ENTREGA']);
														}

														if($qrSac['DAT_INTERAC'] != ""){
															if(fnDatasql($qrSac['DAT_INTERAC']) == fnDatasql($hoje)){
																$atualizado = "Hoje";
															}else if(fnDatasql($qrSac['DAT_INTERAC']) == date('Y-m-d', strtotime(' -1 days'))){
																$atualizado = "Ontem";
															}else{
																$atualizado = fnDataFull($qrSac['DAT_INTERAC']);
															}
														}else{
															$atualizado = "";
														}

														//$diff_dias = fnDateDif($qrSac['DAT_CADASTR'],Date("Y-m-d"));
														// fnEscreve(fnDatasql($qrSac['DAT_INTERAC']));
													?>

													<tr>
													  <td class="text-center">
													  	<small>
													  		<a href="action.php?mod=<?=fnEncode(1285);?>&id=<?php echo fnEncode($qrSac['COD_EMPRESA']);?>&idC=<?php echo fnEncode($qrSac['COD_CHAMADO']); ?>" target="_blank"><?=$qrSac['COD_CHAMADO'] ?>&nbsp; 
													  			<span class="fa fa-external-link-square"></span>
													  		</a>
													  	</small>
													  </td>
													  <td><small><?=$adm?> &nbsp; <?=$qrNomEmp['NOM_FANTASI'] ?></small></td>
													  <td><small><?=$qrSac['NOM_CHAMADO'] ?></small></td>
													  <td><small><?=$qrNomUsu['NOM_SOLICITANTE'] ?></small></td>
													  <td><small><?=$qrSac['DES_TPSOLICITACAO'] ?></small></td>
													  <td><small><?=$qrNomUsu['NOM_RESPONSAVEL'] ?></small></td>
													  
													  <td class="text-center">
													  	<small>
													  		<p class="label" style="background-color: <?php echo $qrSac['COR_PRIORIDADE'] ?>"> 
													  			<span class="<?php echo $qrSac['ICO_PRIORIDADE']; ?>" style="color: #FFF;"></span>
													  			<!-- &nbsp; <?php echo $qrSac['DES_PRIORIDADE']; ?> -->
													  		</p>
													  	</small>
													  </td>

													  <td class="text-center">
													  	<small>
													  		<p class="label" style="background-color: <?php echo $qrSac['COR_STATUS'] ?>"> 
													  			<span class="<?php echo $qrSac['ICO_STATUS']; ?>" style="color: #FFF;"></span>
													  			&nbsp; <?php echo $qrSac['ABV_STATUS']; ?>
													  		</p>
													  	</small>
													  </td>
													  
													  <td class="text-center"><small><?=fnDataShort($qrSac['DAT_CADASTR']); ?></small></td>
													  <td class="text-center"><small><?=$entrega?></small></td>
													  <td class="text-center"><small><?=$atualizado?></small></td>

													</tr>
												    <?php
													}
												?>
													
												</tbody>
												<tfoot>
													<tr>
													    <th class="" colspan="100">
															<center><ul id="paginacao" class="pagination-sm"></ul></center>
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
												
											<div class="push10"></div>	

											</div>
											
										</div>										
									
									<div class="push30"></div>
									
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

			retornaForm(0);

			$('#COD_EMPRESA').val('<?=$cod_empresa?>').trigger('chosen:updated');

			var idEmp = $('#COD_EMPRESA').val();
			buscaCombo(idEmp);
			
			$('.datePicker').datetimepicker({
				 format: 'DD/MM/YYYY',
				 maxDate : 'now',
				}).on('changeDate', function(e){
					$(this).datetimepicker('hide');
				});
				
			//chosen obrigatório
			$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
			$('#formulario').validator();
			
			var numPaginas = <?php echo $numPaginas; ?>;
			if(numPaginas != 0){
				carregarPaginacao(numPaginas);
			}			

		});

		function retornaForm(index){

			var plataforma = '<?php echo $cod_plataforma; ?>';
			if(plataforma != 0 && plataforma != ""){$("#formulario #COD_PLATAFORMA").val(<?php echo $cod_plataforma; ?>).trigger("chosen:updated");}

			var empresa = '<?php echo $cod_empresa; ?>';
			if(empresa != 0 && empresa != ""){$("#formulario #COD_EMPRESA").val(<?php echo $cod_empresa; ?>).trigger("chosen:updated");}

			var versaointegra = '<?php echo $cod_versaointegra; ?>';
			if(versaointegra != 0 && versaointegra != ""){$("#formulario #COD_VERSAOINTEGRA").val(<?php echo $cod_versaointegra; ?>).trigger("chosen:updated");}

			var integradora = '<?php echo $cod_integradora; ?>';
			if(integradora != 0 && integradora != ""){$("#formulario #COD_INTEGRADORA").val(<?php echo $cod_integradora; ?>).trigger("chosen:updated");}

			var tpsolicitacao = '<?php echo $cod_tpsolicitacao; ?>';
			if(tpsolicitacao != 0 && tpsolicitacao != ""){$("#formulario #COD_TPSOLICITACAO").val(<?php echo $cod_tpsolicitacao; ?>).trigger("chosen:updated");}

			var prioridade = '<?php echo $cod_prioridade; ?>';
			if(prioridade != 0 && prioridade != ""){$("#formulario #COD_PRIORIDADE").val(<?php echo $cod_prioridade; ?>).trigger("chosen:updated");}

			var status = '<?php echo $cod_status; ?>';
			if(status != 0 && status != ""){$("#formulario #COD_STATUS").val(<?php echo $cod_status; ?>).trigger("chosen:updated");}

			var usures = '<?php echo $cod_usures; ?>';
			if(usures != 0 && usures != ""){$("#formulario #COD_USURES").val(<?php echo $cod_usures; ?>).trigger("chosen:updated");}
			
			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');						
		}

		$("#COD_EMPRESA").change(function() {
			var idEmp = $('#COD_EMPRESA').val();
			buscaCombo(idEmp);
		});

		function buscaCombo(idEmp){
			$.ajax({
				type: "GET",
				url: "ajxAddSuporte.php",
				data: { ajxEmp:idEmp },
				beforeSend:function(){
					$('#relatorioUsu').html('<div class="loading" style="width: 100%;"></div>');
				},
				success:function(data){
					// console.log(data);	
					$('#relatorioUsu').html($('#relatorioUsuario',data));															
					$('#COD_USUARIO').chosen();															
					$('#COD_USUARIO').val('<?=$cod_usuario?>').trigger('chosen:updated');															
				},
				error:function(){
					$('#relatorioUsu').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Empresa não encontrada...</p>');
				}
			});
		}

		function reloadPage(idPage) {
			$.ajax({
				type: "POST",
				url: "ajxRelSuporte.do?opcao=paginar&idPage="+idPage+"&itens_por_pagina=<?php echo $itens_por_pagina; ?>",
				data: $('#formulario').serialize(),
				beforeSend:function(){
					$('#relatorioConteudo').html('<div class="loading" style="width: 100%;"></div>');
				},
				success:function(data){
					$("#relatorioConteudo").html(data);										
				},
				error:function(){
					$('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
				}
			});		
		}

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
										url: "ajxRelSuporte.do?opcao=exportar&nomeRel="+nome,
										data: $('#formulario').serialize(),
										method: 'POST'
									}).done(function (response) {
										self.setContentAppend('<div>Exportação realizada com sucesso.</div>');
										var fileName = '0_' + nome + '.csv';
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
		
	</script>	