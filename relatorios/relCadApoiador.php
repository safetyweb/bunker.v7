<?php
	
	//echo fnDebug('true');
	
	$itens_por_pagina = 50;
	$pagina = 1;
	
	$dias30="";
	$dat_ini="";
	$dat_fim="";
	$dat_anivExib = "";
	$hashLocal = mt_rand();
	$dat_ini_busca = "";
	$dat_fim_busca = "";
	
	//inicialização de variáveis
	$hoje = fnFormatDate(date("Y-m-d"));
	$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 1 days')));
	
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
			
			$cod_empresa = fnLimpaCampoZero($_POST['COD_EMPRESA']);			
			$cod_grupotr = fnLimpaCampo($_REQUEST['COD_GRUPOTR']);	
			$cod_tiporeg = fnLimpaCampo($_REQUEST['COD_TIPOREG']);
			$cod_indicad = fnLimpaCampo($_REQUEST['COD_INDICAD']);
			$cod_envolv = fnLimpaCampo($_REQUEST['COD_ENVOLV']);
			$cod_entidad = fnLimpaCampo($_REQUEST['COD_ENTIDAD']);
			$cod_cargo = fnLimpaCampo($_REQUEST['COD_CARGO']);
			$cod_partido = fnLimpaCampo($_REQUEST['COD_PARTIDO']);
			$dat_ini_busca = $_POST['DAT_INI'];
			$dat_fim_busca = $_POST['DAT_FIM'];
			$dat_anive_ini = $_POST['DAT_ANIVE_INI'];
			$dat_anive_fim = $_POST['DAT_ANIVE_FIM'];
			$nom_cliente = fnLimpaCampo($_REQUEST['NOM_CLIENTE']);

			// fnEscreve($dat_anive);

			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
						
			if ($opcao != ''){
				
				
			}  

		}
	}
	
	//busca dados url
	if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))){
		//busca dados da empresa
		$cod_empresa = fnDecode($_GET['id']);
		$cod_campanha = fnDecode($_GET['idc']);
		$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '".$cod_empresa."' ";
		//fnEscreve($sql);
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
		
		if (isset($arrayQuery)){
			$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
			$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
		}
												
	}else {
		$cod_empresa = 0;	
		$nom_empresa = "";
	}
	
	//inicialização das variáveis - default	
	if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31" ){
		$dat_ini = fnDataSql($dias30); 
	} 
	if (strlen($dat_fim ) == 0 || $dat_fim == "1969-12-31"){
		$dat_fim = fnDataSql($hoje); 
	}
	
	//busca revendas do usuário
	include "unidadesAutorizadas.php"; 
	
	//fnMostraForm();	
	//fnEscreve($dat_ini);
	//fnEscreve($dat_fim);
	//fnEscreve($cod_univendUsu);
	//fnEscreve($qtd_univendUsu);
	//fnEscreve($lojasAut);
	//fnEscreve($usuReportAdm);
	//fnEscreve($lojasReportAdm);
	
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
	
	<div class="row" id="div_Report">				
	
		<div class="col-md12 margin-bottom-30">
			<!-- Portlet -->
			<div class="portlet portlet-bordered">
				<div class="portlet-title">
					<div class="caption">
						<i class="glyphicon glyphicon-calendar"></i>
						<span class="text-primary"> <?php echo $NomePg; ?></span>
					</div>
					
					<?php 
					//$formBack = "1015";
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
						
				
					<div class="login-form">
					
						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
													
						<fieldset>
							<legend>Filtros</legend> 
							
								<div class="row">
								
									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label required">Empresa</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa ?>">
											<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
										</div>														
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label ">Indicador</label>
											<select class="chosen-select-deselect" data-placeholder="Selecione o indicador" name="COD_INDICAD" id="COD_INDICAD" >
												<option value=""></option>
												<?php 
													
														$sql = "SELECT DISTINCT A.COD_INDICAD,
																		(SELECT DISTINCT NOM_CLIENTE FROM CLIENTES WHERE CLIENTES.COD_CLIENTE=A.COD_INDICAD) AS NOM_INDICADOR 
																FROM CLIENTES A 
																WHERE A.COD_EMPRESA = $cod_empresa
																ORDER BY NOM_INDICADOR";
														$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
													
														while ($qrIndica = mysqli_fetch_assoc($arrayQuery))
														{
												?>
														  	<option value="<?php echo $qrIndica['COD_INDICAD']; ?>"><?php echo $qrIndica['NOM_INDICADOR']; ?></option>
												<?php 
														} 
												?>
											</select>
											<script type="text/javascript">
												$('#COD_INDICAD').val('<?=$cod_indicad?>').trigger("chosen:updated");
											</script>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Nome do Apoiador</label>
											<input type="text" class="form-control input-sm" name="NOM_CLIENTE" id="NOM_CLIENTE" maxlength="50" value="<?=$nom_cliente?>">
											<div class="help-block with-errors"></div>
										</div>
									</div>

								</div>

								<div class="row">

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label ">Cargo</label>
											<select class="chosen-select-deselect" data-placeholder="Selecione o cargo" name="COD_CARGO" id="COD_CARGO" >
												<option value=""></option>
												<?php 
													
														$sql = "SELECT DISTINCT A.COD_FILTRO, DES_FILTRO FROM FILTROS_CLIENTE A, CLIENTE_FILTROS B
																WHERE A.COD_TPFILTRO=B.COD_TPFILTRO AND
																A.COD_TPFILTRO=B.COD_TPFILTRO AND
															    A.COD_FILTRO=B.COD_FILTRO AND
															    A.COD_TPFILTRO=32
															    AND A.COD_EMPRESA = $cod_empresa
															    ORDER BY DES_FILTRO";
														$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
													
														while ($qrIndica = mysqli_fetch_assoc($arrayQuery))
														{
												?>
														  	<option value="<?php echo $qrIndica['COD_FILTRO']; ?>"><?php echo $qrIndica['DES_FILTRO']; ?></option>
												<?php 
														} 
												?>
											</select>
											<script type="text/javascript">
												$('#COD_CARGO').val('<?=$cod_cargo?>').trigger("chosen:updated");
											</script>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label ">Partido Político</label>
											<select class="chosen-select-deselect" data-placeholder="Selecione o partido" name="COD_PARTIDO" id="COD_PARTIDO" >
												<option value=""></option>
												<?php 
													
														$sql = "SELECT DISTINCT A.COD_FILTRO, DES_FILTRO FROM FILTROS_CLIENTE A, CLIENTE_FILTROS B
																WHERE A.COD_TPFILTRO=B.COD_TPFILTRO AND
																A.COD_TPFILTRO=B.COD_TPFILTRO AND
															    A.COD_FILTRO=B.COD_FILTRO AND
															    A.COD_TPFILTRO=33
															    AND A.COD_EMPRESA = $cod_empresa
															    ORDER BY DES_FILTRO";
														$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
													
														while ($qrIndica = mysqli_fetch_assoc($arrayQuery))
														{
												?>
														  	<option value="<?php echo $qrIndica['COD_FILTRO']; ?>"><?php echo $qrIndica['DES_FILTRO']; ?></option>
												<?php 
														} 
												?>
											</select>
											<script type="text/javascript">
												$('#COD_PARTIDO').val('<?=$cod_partido?>').trigger("chosen:updated");
											</script>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label ">Representação / Entidade</label>
											<select class="chosen-select-deselect" data-placeholder="Selecione a representação" name="COD_ENTIDAD" id="COD_ENTIDAD" >
												<option value=""></option>
												<?php 
													
														$sql = "SELECT DISTINCT A.COD_FILTRO, DES_FILTRO FROM FILTROS_CLIENTE A, CLIENTE_FILTROS B
																WHERE A.COD_TPFILTRO=B.COD_TPFILTRO AND
																A.COD_TPFILTRO=B.COD_TPFILTRO AND
															    A.COD_FILTRO=B.COD_FILTRO AND
															    A.COD_TPFILTRO=31
															    AND A.COD_EMPRESA = $cod_empresa
															    ORDER BY DES_FILTRO";
														$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
													
														while ($qrIndica = mysqli_fetch_assoc($arrayQuery))
														{
												?>
														  	<option value="<?php echo $qrIndica['COD_FILTRO']; ?>"><?php echo $qrIndica['DES_FILTRO']; ?></option>
												<?php 
														} 
												?>
											</select>
											<script type="text/javascript">
												$('#COD_ENTIDAD').val('<?=$cod_entidad?>').trigger("chosen:updated");
											</script>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label ">Grupo</label>
											<select class="chosen-select-deselect" data-placeholder="Selecione o grupo" name="COD_GRUPOTR" id="COD_GRUPOTR" >
												<option value=""></option>
												<?php 
													
														$sql = "SELECT DISTINCT A.COD_FILTRO, DES_FILTRO FROM FILTROS_CLIENTE A, CLIENTE_FILTROS B
																WHERE A.COD_TPFILTRO=B.COD_TPFILTRO AND
																A.COD_TPFILTRO=B.COD_TPFILTRO AND
															    A.COD_FILTRO=B.COD_FILTRO AND
															    A.COD_TPFILTRO=29
															    AND A.COD_EMPRESA = $cod_empresa
															    ORDER BY DES_FILTRO";
														$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
													
														while ($qrFiltro = mysqli_fetch_assoc($arrayQuery))
														{
												?>
														  	<option value="<?php echo $qrFiltro['COD_FILTRO']; ?>"><?php echo $qrFiltro['DES_FILTRO']; ?></option>
												<?php 
														} 
												?>
											</select>
											<script type="text/javascript">
												$('#COD_GRUPOTR').val('<?=$cod_grupotr?>').trigger("chosen:updated");
											</script>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label ">Região</label>
											<select class="chosen-select-deselect" data-placeholder="Selecione a região" name="COD_TIPOREG" id="COD_TIPOREG" >
												<option value=""></option>
												<?php 
													
														$sql = "SELECT DISTINCT A.COD_FILTRO, DES_FILTRO FROM FILTROS_CLIENTE A, CLIENTE_FILTROS B
																WHERE A.COD_TPFILTRO=B.COD_TPFILTRO AND
																A.COD_TPFILTRO=B.COD_TPFILTRO AND
															    A.COD_FILTRO=B.COD_FILTRO AND
															    A.COD_TPFILTRO=28
															    AND A.COD_EMPRESA = $cod_empresa
															    ORDER BY DES_FILTRO";
														$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
													
														while ($qrIndica = mysqli_fetch_assoc($arrayQuery))
														{
												?>
														  	<option value="<?php echo $qrIndica['COD_FILTRO']; ?>"><?php echo $qrIndica['DES_FILTRO']; ?></option>
												<?php 
														} 
												?>
											</select>
											<script type="text/javascript">
												$('#COD_TIPOREG').val('<?=$cod_tiporeg?>').trigger("chosen:updated");
											</script>
											<div class="help-block with-errors"></div>
										</div>
									</div>
									
									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label ">Envolvimento</label>
											<select class="chosen-select-deselect" data-placeholder="Selecione o nível" name="COD_ENVOLV" id="COD_ENVOLV" >
												<option value=""></option>
												<?php 
													
														$sql = "SELECT DISTINCT A.COD_FILTRO, DES_FILTRO FROM FILTROS_CLIENTE A, CLIENTE_FILTROS B
																WHERE A.COD_TPFILTRO=B.COD_TPFILTRO AND
																A.COD_TPFILTRO=B.COD_TPFILTRO AND
															    A.COD_FILTRO=B.COD_FILTRO AND
															    A.COD_TPFILTRO=30
															    AND A.COD_EMPRESA = $cod_empresa
															    ORDER BY DES_FILTRO";
														$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
													
														while ($qrIndica = mysqli_fetch_assoc($arrayQuery))
														{
												?>
														  	<option value="<?php echo $qrIndica['COD_FILTRO']; ?>"><?php echo $qrIndica['DES_FILTRO']; ?></option>
												<?php 
														} 
												?>
											</select>
											<script type="text/javascript">
												$('#COD_ENVOLV').val('<?=$cod_envolv?>').trigger("chosen:updated");
											</script>
											<div class="help-block with-errors"></div>
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
													
													<div class="input-group date datePicker" id="DAT_INI_GRP">
														<input type='text' class="form-control input-sm data" name="DAT_INI" id="DAT_INI" value="<?=$dat_ini_busca?>"/>
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
													
													<div class="input-group date datePicker" id="DAT_FIM_GRP">
														<input type='text' class="form-control input-sm data" name="DAT_FIM" id="DAT_FIM" value="<?=$dat_fim_busca?>"/>
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
											<legend>Data de Aniversário</legend>

											<div class="col-md-6">
												<div class="form-group">
													<label for="inputName" class="control-label">Data Inicial</label>
													
													<div class="input-group date datePicker2" id="DAT_FIM_GRP">
														<input type='text' class="form-control input-sm data" name="DAT_ANIVE_INI" id="DAT_ANIVE_INI" value="<?=$dat_anive_ini?>"/>
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
													
													<div class="input-group date datePicker2" id="DAT_FIM_GRP">
														<input type='text' class="form-control input-sm data" name="DAT_ANIVE_FIM" id="DAT_ANIVE_FIM" value="<?=$dat_anive_fim?>"/>
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
										<div class="push20"></div>
										<button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-sm btn-block getBtn"><i class="fa fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
									</div>

								</div>

							
									
						</fieldset>	
						
						<div class="push20"></div>
						
						<div>
							<div class="row">
								<div class="col-md-12">

									<div class="push20"></div>
									
									<table class="table table-bordered table-hover tablesorter">
									
									<thead>
										<tr>
											<th><small>Apoiador</small></th>
											<th class="text-center"><small>Dt. Nascimento</small></th>
											<th class="text-center"><small>Idade</small></th>
											<th><small>Email</small></th>
											<th><small>Indicador</small></th>
											<th class="text-center"><small>Dt. Cadastro</small></th>
											<th><small>Cargo</small></th>
											<th><small>Partido</small></th>
											<th><small>Representação</small></th>
											<th><small>Grupo</small></th>
											<th><small>Região</small></th>
											<th><small>Envolvimento</small></th>
											<th><small>Celular/Telefone</small></th>
										</tr>
									</thead>

									<tbody id="relatorioConteudo">							

									<?php

										if($cod_indicad != ""){
											$andIndicad = "AND A.COD_INDICAD = $cod_indicad";
										}else{
											$andIndicad = "";
										}

										if($cod_grupotr != ""){
											$andGrupo = "AND (SELECT distinct A.COD_FILTRO FROM FILTROS_CLIENTE A, CLIENTE_FILTROS B
														  WHERE A.COD_TPFILTRO=B.COD_TPFILTRO AND
														  A.COD_TPFILTRO=B.COD_TPFILTRO AND
													      A.COD_FILTRO=B.COD_FILTRO AND
													      A.COD_TPFILTRO=29 AND
													      B.COD_CLIENTE=A.COD_CLIENTE)=$cod_grupotr";
										}else{
											$andGrupo = "";
										}

										if($cod_tiporeg != ""){
											$andReg = "AND (SELECT distinct A.COD_FILTRO FROM FILTROS_CLIENTE A, CLIENTE_FILTROS B
														 WHERE A.COD_TPFILTRO=B.COD_TPFILTRO AND
														 A.COD_TPFILTRO=B.COD_TPFILTRO AND
													     A.COD_FILTRO=B.COD_FILTRO AND
													     A.COD_TPFILTRO=28 AND
													     B.COD_CLIENTE=A.COD_CLIENTE)=$cod_tiporeg";
										}else{
											$andReg = "";
										}

										if($cod_cargo != ""){
											$andCargo = "AND (SELECT distinct A.COD_FILTRO FROM FILTROS_CLIENTE A, CLIENTE_FILTROS B
														 WHERE A.COD_TPFILTRO=B.COD_TPFILTRO AND
														 A.COD_TPFILTRO=B.COD_TPFILTRO AND
													     A.COD_FILTRO=B.COD_FILTRO AND
													     A.COD_TPFILTRO=32 AND
													     B.COD_CLIENTE=A.COD_CLIENTE)=$cod_cargo";
										}else{
											$andCargo = "";
										}

										if($cod_entidad != ""){
											$andEnt = "AND (SELECT distinct A.COD_FILTRO FROM FILTROS_CLIENTE A, CLIENTE_FILTROS B
														 WHERE A.COD_TPFILTRO=B.COD_TPFILTRO AND
														 A.COD_TPFILTRO=B.COD_TPFILTRO AND
													     A.COD_FILTRO=B.COD_FILTRO AND
													     A.COD_TPFILTRO=31 AND
													     B.COD_CLIENTE=A.COD_CLIENTE)=$cod_entidad";
										}else{
											$andEnt = "";
										}

										if($cod_partido != ""){
											$andPart = "AND (SELECT distinct A.COD_FILTRO FROM FILTROS_CLIENTE A, CLIENTE_FILTROS B
														 WHERE A.COD_TPFILTRO=B.COD_TPFILTRO AND
														 A.COD_TPFILTRO=B.COD_TPFILTRO AND
													     A.COD_FILTRO=B.COD_FILTRO AND
													     A.COD_TPFILTRO=33 AND
													     B.COD_CLIENTE=A.COD_CLIENTE)=$cod_partido";
										}else{
											$andPart = "";
										}

										if($cod_envolv != ""){
											$andEnvolv = "AND (SELECT distinct A.COD_FILTRO FROM FILTROS_CLIENTE A, CLIENTE_FILTROS B
														 WHERE A.COD_TPFILTRO=B.COD_TPFILTRO AND
														 A.COD_TPFILTRO=B.COD_TPFILTRO AND
													     A.COD_FILTRO=B.COD_FILTRO AND
													     A.COD_TPFILTRO=30 AND
													     B.COD_CLIENTE=A.COD_CLIENTE)=$cod_envolv";
										}else{
											$andEnvolv = "";
										}

										if($nom_cliente != ""){
											$andNome = "AND A.NOM_CLIENTE LIKE '%$nom_cliente%'";
										}else{
											$andNome = "";
										}

										if($dat_anive_ini != "" || $dat_anive_fim != ""){

											if($dat_anive_ini != ""){
												$dat_anive_ini = "RIGHT(STR_TO_DATE('".$dat_anive_ini."', '%d/%m/%Y'),5)";
											}else{
												$dat_anive_ini = "RIGHT(STR_TO_DATE('01/01', '%d/%m/%Y'),5)";
											}

											if($dat_anive_fim != ""){
												$dat_anive_fim = "RIGHT(STR_TO_DATE('".$dat_anive_fim."', '%d/%m/%Y'),5)";
											}else{
												$dat_anive_fim = "RIGHT(STR_TO_DATE('31/12', '%d/%m/%Y'),5)";
											}

											$andAnive = "AND RIGHT(STR_TO_DATE(A.DAT_NASCIME, '%d/%m/%Y'),5) BETWEEN $dat_anive_ini AND $dat_anive_fim";
										}else{
											$andAnive = "";
										}

										if($dat_ini_busca != ""){
											$dat_ini_busca = fnDataSql($dat_ini_busca);
											$andDataIni = "AND A.DAT_CADASTR > '$dat_ini_busca 00:00:00'";
										}else{
											$andDataIni = "";
										}

										if($dat_fim_busca != ""){
											$dat_fim_busca = fnDataSql($dat_fim_busca);
											$andDataFim = "AND A.DAT_CADASTR < '$dat_fim_busca 23:59:59'";
										}else{
											$andDataFim = "";
										}


										// Filtro por Grupo de Lojas
										// include "filtroGrupoLojas.php";
									
										$sql = "SELECT A.COD_CLIENTE
												FROM CLIENTES A
												WHERE 
												A.COD_EMPRESA = $cod_empresa
												$andDataIni
												$andDataFim
												$andIndicad
												$andGrupo
												$andReg
												$andCargo
												$andEnt
												$andPart
												$andEnvolv
												$andNome
												$andAnive
												-- FILTRO UNIVERSAL PARA DATAS DE NASCIMENTO, COM CONVERSÃO DE DATA (SEM ANO)
												-- AND RIGHT(STR_TO_DATE(A.DAT_NASCIME, '%d/%m/%Y'),5) BETWEEN $dat_anive_ini AND $dat_anive_fim
												";
										// fnTestesql(connTemp($cod_empresa,''),$sql);		
										//fnEscreve($sql);

										$retorno = mysqli_query(connTemp($cod_empresa,''),$sql);
										$totalitens_por_pagina = mysqli_num_rows($retorno);

										$numPaginas = ceil($totalitens_por_pagina/$itens_por_pagina);

										// fnEscreve($totalitens_por_pagina);
										
										//variavel para calcular o início da visualização com base na página atual
										$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

										// Filtro por Grupo de Lojas
										// include "filtroGrupoLojas.php";

										$sql = "SELECT A.COD_CLIENTE, A.DAT_CADASTR, A.DAT_NASCIME, A.DES_EMAILUS,
												(SELECT NOM_CLIENTE FROM CLIENTES WHERE CLIENTES.COD_CLIENTE=A.COD_INDICAD) AS NOM_INDICADOR,
												A.NOM_CLIENTE AS NOM_COLABORADOR,
												(SELECT DISTINCT DES_FILTRO FROM FILTROS_CLIENTE A, CLIENTE_FILTROS B
												WHERE A.COD_TPFILTRO=B.COD_TPFILTRO AND
														A.COD_TPFILTRO=B.COD_TPFILTRO AND
												      A.COD_FILTRO=B.COD_FILTRO AND
												      A.COD_TPFILTRO=32 AND
												      B.COD_CLIENTE=A.COD_CLIENTE) AS CARGO,
												(SELECT DISTINCT DES_FILTRO FROM FILTROS_CLIENTE A, CLIENTE_FILTROS B
												WHERE A.COD_TPFILTRO=B.COD_TPFILTRO AND
														A.COD_TPFILTRO=B.COD_TPFILTRO AND
												      A.COD_FILTRO=B.COD_FILTRO AND
												      A.COD_TPFILTRO=29 AND
												      B.COD_CLIENTE=A.COD_CLIENTE) AS GRUPO_TRABALHO,
												(SELECT DISTINCT DES_FILTRO FROM FILTROS_CLIENTE A, CLIENTE_FILTROS B
												WHERE A.COD_TPFILTRO=B.COD_TPFILTRO AND
														A.COD_TPFILTRO=B.COD_TPFILTRO AND
												      A.COD_FILTRO=B.COD_FILTRO AND
												      A.COD_TPFILTRO=28 AND
												      B.COD_CLIENTE=A.COD_CLIENTE) AS REGIAO_TRABALHO,
												(SELECT DISTINCT DES_FILTRO FROM FILTROS_CLIENTE A, CLIENTE_FILTROS B
												WHERE A.COD_TPFILTRO=B.COD_TPFILTRO AND
														A.COD_TPFILTRO=B.COD_TPFILTRO AND
												      A.COD_FILTRO=B.COD_FILTRO AND
												      A.COD_TPFILTRO=30 AND
												      B.COD_CLIENTE=A.COD_CLIENTE) AS ENVOLVIMENTO,
												(SELECT DISTINCT DES_FILTRO FROM FILTROS_CLIENTE A, CLIENTE_FILTROS B
												WHERE A.COD_TPFILTRO=B.COD_TPFILTRO AND
														A.COD_TPFILTRO=B.COD_TPFILTRO AND
												      A.COD_FILTRO=B.COD_FILTRO AND
												      A.COD_TPFILTRO=33 AND
												      B.COD_CLIENTE=A.COD_CLIENTE) AS PARTIDO,
												(SELECT DISTINCT DES_FILTRO FROM FILTROS_CLIENTE A, CLIENTE_FILTROS B
												WHERE A.COD_TPFILTRO=B.COD_TPFILTRO AND
														A.COD_TPFILTRO=B.COD_TPFILTRO AND
												      A.COD_FILTRO=B.COD_FILTRO AND
												      A.COD_TPFILTRO=31 AND
												      B.COD_CLIENTE=A.COD_CLIENTE) AS ENTIDADE,
												      A.NUM_TELEFON,
													  A.NUM_CELULAR
												FROM CLIENTES A
												WHERE
												A.COD_EMPRESA = $cod_empresa
												$andDataIni
												$andDataFim
												$andIndicad
												$andGrupo
												$andReg
												$andCargo
												$andEnt
												$andPart
												$andEnvolv
												$andNome
												$andAnive
												-- AND RIGHT(STR_TO_DATE(A.DAT_NASCIME, '%d/%m/%Y'),5) BETWEEN $dat_anive_ini AND $dat_anive_fim
												ORDER BY NOM_CLIENTE
												LIMIT $inicio,$itens_por_pagina
												";
										
										// fnEscreve($sql);
										//fnTestesql(connTemp($cod_empresa,''),$sql);											
										$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
															  
										$count=0;
										while ($qrApoia = mysqli_fetch_assoc($arrayQuery))
										  {

										  	$idade = "";

										  	if($qrApoia['NUM_CELULAR'] != "" && $qrApoia['NUM_TELEFON'] != ""){
										  		
										  		$tel = $qrApoia['NUM_CELULAR']."<br><div class='push5'></div>".$qrApoia['NUM_TELEFON'];
										  		
										  	}else if($qrApoia['NUM_CELULAR'] != "" && $qrApoia['NUM_TELEFON'] == ""){
										  		
										  		$tel = $qrApoia['NUM_CELULAR'];
										  		
										  	}else{
										  		
										  		$tel = $qrApoia['NUM_TELEFON'];
										  		
										  	}

										  	if($qrApoia['DAT_NASCIME'] != ""){
										  		$idade = date_diff(date_create(fnDataSql($qrApoia['DAT_NASCIME'])), date_create('now'))->y;
										  	}

											$count++;	
											echo"
												<tr>
												  <td><a href='action.do?mod=".fnEncode(1024)."&id=".fnEncode($cod_empresa)."&idC=".fnEncode($qrApoia['COD_CLIENTE'])."' class='f14' target='_blank'><small>".$qrApoia['NOM_COLABORADOR']."</small></a></td>
												  <td class='text-center'><small>".$qrApoia['DAT_NASCIME']."</small></td>
												  <td class='text-center'><small>".$idade."</small></td>
												  <td><small>".$qrApoia['DES_EMAILUS']."</small></td>
												  <td><small>".$qrApoia['NOM_INDICADOR']."</small></td>
												  <td class='text-center'><small>".fnDataShort($qrApoia['DAT_CADASTR'])."</small></td>
												  <td><small>".$qrApoia['CARGO']."</small></td>
												  <td><small>".$qrApoia['PARTIDO']."</small></td>
												  <td><small>".$qrApoia['ENTIDADE']."</small></td>
												  <td><small>".$qrApoia['GRUPO_TRABALHO']."</small></td>
												  <td><small>".$qrApoia['REGIAO_TRABALHO']."</small></td>
												  <td><small>".$qrApoia['ENVOLVIMENTO']."</small></td>
												  <td><small>".$tel."</small></td>
												</tr>
												"; 
											  }										

									?>
											<tr>
											  <th class="" colspan="100">
												<center><small style="font-weight: normal;">Resultados: <b><?=$inicio?></b> a <b><?=( $totalitens_por_pagina < ($itens_por_pagina+$inicio) ? $totalitens_por_pagina : ($itens_por_pagina+$inicio))?></b> de <b><?=$totalitens_por_pagina?></b> registros.</small></center>
											  </th>
											</tr>

										</tbody>

										<tfoot>													
											<tr>
											  <th class="" colspan="100">
											  	<div class="col-xs-2">
											  		<a class="btn btn-info btn-sm exportarCSV pull-left"> <i class="fa fa-file-excel" aria-hidden="true"></i>&nbsp; Exportar</a>
											  	</div>
											  	<div class="col-xs-8">
													<center><ul id="paginacao" class="pagination-sm"></ul></center>
												</div>
											  </th>
											</tr>
										</tfoot>
										
									</table>
																					
								</div>
							
								
							</div>
						</div>
							
						<input type="hidden" name="LOJAS" id="LOJAS" value="<?php echo $lojasSelecionadas; ?>" />					
						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">		
						
						<div class="push5"></div> 
						
						</form>
						
					<div class="push50"></div>									
					
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
	
    <script>
	
		//datas
		$(function () {

			var numPaginas = <?php echo $numPaginas; ?>;
			if(numPaginas != 0){
				carregarPaginacao(numPaginas);
			}

			$('.datePicker2').datetimepicker({
				 viewMode: "months",
				 format: 'DD/MM'
				}).on('changeDate', function(e){
					$(this).datetimepicker('hide');
				});
			
			$('.datePicker').datetimepicker({
				 format: 'DD/MM/YYYY',
				 maxDate : 'now',
				}).on('changeDate', function(e){
					$(this).datetimepicker('hide');
				});
			
			$("#DAT_INI_GRP").on("dp.change", function (e) {
				$('#DAT_FIM_GRP').data("DateTimePicker").minDate(e.date);
			});
			
			$("#DAT_FIM_GRP").on("dp.change", function (e) {
				$('#DAT_INI_GRP').data("DateTimePicker").maxDate(e.date);
			});

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
											url: "relatorios/ajxCadApoiador.do?opcao=exportar&nomeRel="+nome+"&id=<?php echo fnEncode($cod_empresa); ?>",
											data: $('#formulario').serialize(),
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
			});
				

		});	

		function reloadPage(idPage) {
			$.ajax({
				type: "POST",
				url: "relatorios/ajxCadApoiador.do?id=<?php echo fnEncode($cod_empresa); ?>&idPage="+idPage+"&itens_por_pagina=<?php echo $itens_por_pagina; ?>&opcao=paginar",
				data: $('#formulario').serialize(),
				beforeSend:function(){
					$('#relatorioConteudo').html('<div class="loading" style="width: 100%;"></div>');
				},
				success:function(data){
					$("#relatorioConteudo").html(data);										
					console.log(data);										
				},
				error:function(){
					$('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
				}
			});		
		}	

		function abreDetail(idBloco){
			var idItem = $('.abreDetail_' + idBloco)
			if (!idItem.is(':visible')){
				idItem.show();
				$('#bloco_'+idBloco).find($(".fa")).removeClass('fa-angle-right').addClass('fa-angle-down');
			}else{
				idItem.hide();
				$('#bloco_'+idBloco).find($(".fa")).removeClass('fa-angle-down').addClass('fa-angle-right');
			}
		}
		
	</script>	
   