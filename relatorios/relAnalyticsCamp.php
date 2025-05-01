<?php
	
	//echo fnDebug('true');
	
	$itens_por_pagina = 50;
	$pagina = 1;
	
	$dias30="";
	$dat_ini="";
	$dat_fim="";
	$dat_ini2="";
	$dat_fim2="";
	$cod_persona = 0;
	$hashLocal = mt_rand();
	$tip_relat = 1;
	
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
			$cod_univend = $_POST['COD_UNIVEND'];
			$dat_ini = fnDataSql($_POST['DAT_INI']);
			$dat_fim = fnDataSql($_POST['DAT_FIM']);
			$dat_ini2 = fnDataSql($_POST['DAT_INI2']);
			$dat_fim2 = fnDataSql($_POST['DAT_FIM2']);
			$tip_relat = fnLimpacampo($_REQUEST['TIP_RELAT']);	
			$cod_grupotr = $_REQUEST['COD_GRUPOTR'];	
			$cod_tiporeg = $_REQUEST['COD_TIPOREG'];

			if (isset($_POST['COD_PERSONA'])){
				$cod_persona = "";
				$Arr_COD_PERSONA = $_POST['COD_PERSONA'];			 
				 
				   for ($i=0;$i<count($Arr_COD_PERSONA);$i++) 
				   { 
					$cod_persona = $cod_persona.$Arr_COD_PERSONA[$i].",";
				   } 
				   
				   $cod_persona = ltrim(rtrim($cod_persona,','),',');
					
			}else{$cod_persona = "0";}	

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
	if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31" && strlen($dat_ini2) == 0 || $dat_ini2 == "1969-12-31"){
		$dat_ini = fnDataSql($dias30); 
		$dat_ini2 = fnDataSql($dias30); 
	} 
	if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31" && strlen($dat_fim2) == 0 || $dat_fim2 == "1969-12-31"){
		$dat_fim = fnDataSql($hoje); 
		$dat_fim2 = fnDataSql($hoje); 
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
.table-bordered:hover{
    z-index: 2;
}
.p1  {
    background-color: #f8f9f9;
	z-index: 0;
}

tr:hover{
	background-color: #ECF0F1!important;

}

tr:hover td {
    background-color: transparent; /* or #000 */
}
/*.drop-shadow {
    -webkit-box-shadow: 0 0 5px 2px #ECEFF2;
    box-shadow: 0 0 5px 2px #ECEFF2;
    border-radius:5px;
}*/
</style>
		
	<div class="push30"></div> 
	
	<div class="row" id="div_Report">
            
            <form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
	
		<div class="col-md12 margin-bottom-30">
			<!-- Portlet -->
			<div class="portlet portlet-bordered">
				<div class="portlet-title">
					<div class="caption">
						<i class="fal fa-terminal"></i>
						<span class="text-primary"> <?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
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
													
						<fieldset>
							<legend>Filtros</legend> 
									
								<div class="row">
								
									<div class="col-sm-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Campanha Base</label>
											
												<select data-placeholder="Selecione a persona desejada" name="COD_CAMPANHA1[]" id="COD_CAMPANHA1" multiple="multiple" class="chosen-select-deselect requiredChk" tabindex="1">
													<option value=""></option>
													<?php

														$sql = "SELECT COD_CAMPANHA,DES_CAMPANHA from campanha where cod_empresa = $cod_empresa and LOG_ATIVO = 'S' AND TIP_CAMPANHA IN(12,13) order by DES_CAMPANHA";																		
														$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);																
														while ($qrListaPersonas = mysqli_fetch_assoc($arrayQuery))
														{
																															
														echo"
															  <option value='".$qrListaPersonas['COD_CAMPANHA']."'>".ucfirst($qrListaPersonas['DES_CAMPANHA']). "</option> 
															";    
														}

													?>								
												</select>
												
										</div>             

									</div>

									<div class="col-sm-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Campanha Comparação</label>
											
												<select data-placeholder="Selecione a persona desejada" name="COD_CAMPANHA2[]" id="COD_CAMPANHA2" multiple="multiple" class="chosen-select-deselect requiredChk" tabindex="1">
													<option value=""></option>
													<?php

														$sql = "SELECT COD_CAMPANHA,DES_CAMPANHA from campanha where cod_empresa = $cod_empresa and LOG_ATIVO = 'S' AND TIP_CAMPANHA IN(12,13) order by DES_CAMPANHA";																		
														$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);																
														while ($qrListaPersonas = mysqli_fetch_assoc($arrayQuery))
														{
																															
														echo"
															  <option value='".$qrListaPersonas['COD_CAMPANHA']."'>".ucfirst($qrListaPersonas['DES_CAMPANHA']). "</option> 
															";    
														}

													?>								
												</select>

										</div>             

									</div>
									
									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label required">Unidade de Atendimento</label>
											<?php include "unidadesAutorizadasComboMulti.php"; ?>
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Grupo de Lojas</label>
											<?php include "grupoLojasComboMulti.php"; ?>
										</div>
									</div>	

								</div>

								<div class="push10"></div>

								<div class="row">

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Região</label>
											<?php include "grupoRegiaoMulti.php"; ?>
										</div>
									</div>

									<div class="col-md-3">
										
										<div class="form-group">
											<label for="inputName" class="control-label required">Data Inicial</label>
											
											<div class="input-group date datePicker" id="DAT_INI_GRP">
												<input type='text' class="form-control input-sm data" name="DAT_INI" id="DAT_INI" value="<?php echo fnFormatDate($dat_ini); ?>" required/>
												<span class="input-group-addon">
													<span class="glyphicon glyphicon-calendar"></span>
												</span>
											</div>
											<div class="help-block with-errors"></div>
										</div>

									</div>

									<div class="col-md-3">
										
										<div class="form-group">
											<label for="inputName" class="control-label required">Data Inicial</label>
											
											<div class="input-group date datePicker" id="DAT_INI_GRP">
												<input type='text' class="form-control input-sm data" name="DAT_FIM" id="DAT_FIM" value="<?php echo fnFormatDate($dat_fim); ?>" required/>
												<span class="input-group-addon">
													<span class="glyphicon glyphicon-calendar"></span>
												</span>
											</div>
											<div class="help-block with-errors"></div>
										</div>
										
									</div>
									
									<div class="col-md-2">
										
										<button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-sm btn-block getBtn"><i class="fal fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>										
										
									</div>
														
									
								</div>
									
						</fieldset>
                                            </div>
                                    </div>
                            </div>

                            <div class="push20"></div>

                                    <div class="portlet portlet-bordered">

                                             <div class="portlet-body">

                                                <div class="login-form">
						
						
						<div class="push30"></div>
						

						<div class="row">

							
							<div class="col-md-2">
							<i class="fas fa-users fa-2x">&nbsp; </i><span class="f26" id="tot_CLI_PERSONAS"></span> 
							</div>
												
							<div class="col-md-2">
							<i class="fas fa-user fa-2x">&nbsp; </i><span class="f26" id="tot_PES_UNICAS"></span> 
							</div>
												
							
						</div>

						<div class="push30"></div>

						<div class="flexrow">
							
							<div class="form-group text-center col">
											
								<h4>Faturamento</h4>
								<div class="push20"></div>
								
								<div style="max-height: 75px; max-width:100%;">
									<canvas id="pieChart" style="height: 100%"></canvas>
								</div>												

							</div>

							<div class="form-group text-center col">
											
								<h4>Clientes</h4>
								<div class="push20"></div>
								
								<div style="max-height: 75px; max-width:100%;">
									<canvas id="pieChart2" style="height: 100%"></canvas>
								</div>												

							</div>

							<div class="form-group text-center col">
											
								<h4>Transações</h4>
								<div class="push20"></div>
								
								<div style="max-height: 75px; max-width:100%;">
									<canvas id="pieChart3" style="height: 100%"></canvas>
								</div>												

							</div>

							<div class="form-group text-center col">
											
								<h4>Créditos<div class="push">gerados</h4>
								<div class="push20"></div>
								
								<div style="max-height: 75px; max-width:100%;">
									<canvas id="pieChart4" style="height: 100%"></canvas>
								</div>												

							</div>

							<div class="form-group text-center col">
											
								<h4>Itens</h4>
								<div class="push20"></div>
								
								<div style="max-height: 75px; max-width:100%;">
									<canvas id="pieChart5" style="height: 100%"></canvas>
								</div>												

							</div>

						</div>

						<div class="push100"></div>

						<div class="row text-center drop-shadow">					
											
							<!-- <div class="form-group text-center col-md-1"></div> -->
							
							<div class="form-group text-center col-md-2" style="width: 20%!important">
								
								<h4>Gasto Médio</h4>
								<div class="push20"></div>
								
								<div style="max-height: 200px; max-width:100%;">
									<canvas id="bar-chart-grouped-fat" style="height: 100%"></canvas>
								</div>

							</div>

							<div class="form-group text-center col-md-2" style="width: 20%!important">
								
								<h4>Tkt. Médio</h4>
								<div class="push20"></div>
								
								<div style="max-height: 200px; max-width:100%;">
									<canvas id="bar-chart-grouped-tkt" style="height: 100%"></canvas>
								</div>

							</div>

							<div class="form-group text-center col-md-2" style="width: 20%!important">
								
								<h4>VR por Cliente</h4>
								<div class="push20"></div>
								
								<div style="max-height: 200px; max-width:100%;">
									<canvas id="bar-chart-grouped-tran" style="height: 100%"></canvas>
								</div>

							</div>
							
							<div class="form-group text-center col-md-2" style="width: 20%!important">
								
								<h4>VR por Transação</h4>
								<div class="push20"></div>
								
								<div style="max-height: 200px; max-width:100%;">
									<canvas id="bar-chart-grouped-cli" style="height: 100%"></canvas>
								</div>

							</div>

							<div class="form-group text-center col-md-2" style="width: 20%!important">
								
								<h4>VVR %</h4>
								<div class="push20"></div>
								
								<div style="max-height: 200px; max-width:100%;">
									<canvas id="bar-chart-grouped-item" style="height: 100%"></canvas>
								</div>

							</div>
							
							<!-- <div class="form-group text-center col-md-1"></div> -->

						</div>	
													
						<div class="push30"></div>
						
						<div class="row">
							
							<div class="col-md-4">

								<fieldset>
									<legend>Base</legend>

									<table class="table table-hover">
										
										 <thead>

											<tr>
											  	<th class="text-center text-info"><small><small>Faturamento<br></small><b> &nbsp; <span id="tot_FATURAMENTO_P1"></span></b></small></th>
											  	<th class="text-center text-info"><small><small>Ticket Médio<br></small><b> &nbsp; <span id="tot_VAL_TICKET_MEDIO_P1"></span></b></small></th>
											  	<th class="text-center text-info"><small><small>Transações <br></small><b> &nbsp; <span id="tot_QTD_TRANSACAO_P1"></span></b></small></th>
										  	</tr>
											<tr>
											  	<th class="text-center text-info"><small><small>Itens <br></small><b> &nbsp; <span id="tot_QTD_ITENS_P1"></span></b></small></th>
											  	<th class="text-center text-info"><small><small>Clientes <br></small><b> &nbsp; <span id="tot_QTD_CLIENTE_P1"></span></b></small></th>
											  	<th class="text-center text-info"><small><small>Engajamento<br></small><b> &nbsp; <span id="tot_PCT_CLIENTE_P1"></span>%</b></small></th>
										  	</tr>

										 </thead>

									</table>

								</fieldset>

							</div>

							<div class="col-md-4">

								<fieldset>
									<legend>Comparação</legend>

									<table class="table">
										
										 <thead>

											<tr>
											  	<th class="text-center text-info"><small><small>Faturamento<br></small><b> &nbsp; <span id="tot_FATURAMENTO_P2"></span></b></small></th>
												<th class="text-center text-info"><small><small>Ticket Médio<br></small><b> &nbsp; <span id="tot_VAL_TICKET_MEDIO_P2"></span></b></small></th>
												<th class="text-center text-info"><small><small>Transações <br></small><b> &nbsp; <span id="tot_QTD_TRANSACAO_P2"></span></b></small></th>
										  	</tr>
											<tr>
												<th class="text-center text-info"><small><small>Itens <br></small><b> &nbsp; <span id="tot_QTD_ITENS_P2"></span></b></small></th>
												<th class="text-center text-info"><small><small>Clientes <br></small><b> &nbsp; <span id="tot_QTD_CLIENTE_P2"></span></b></small></th>
												<th class="text-center text-info"><small><small>Engajamento<br></small><b> &nbsp; <span id="tot_PCT_CLIENTE_P2"></span>%</b></small></th>
										  	</tr>

										 </thead>

									</table>							
									
								</fieldset>

							</div>

							<div class="col-md-4">

								<fieldset>
									<legend>Variação Percentual</legend>

									<table class="table">
										
										<thead>
											<tr>
											  	<th class="text-center text-info"><small><small>Faturamento<br></small><b> &nbsp; <span id="tot_PERC_FATURAMENTO"></span>%</b></small></th>
											  	<th class="text-center text-info"><small><small>Ticket Médio<br></small><b> &nbsp; <span id="tot_PERC_TICKET_MEDIO"></span>%</b></small></th>
											  	<th class="text-center text-info"><small><small>Transações <br></small><b> &nbsp; <span id="tot_PERC_TRANSACAO"></span>%</b></small></th>
										  	</tr>
											<tr>
											  	<th class="text-center text-info"><small><small>Itens <br></small><b> &nbsp; <span id="tot_PERC_ITENS"></span>%</b></small></th>
											  	<th class="text-center text-info"><small><small>Clientes <br></small><b> &nbsp; <span id="tot_PERC_CLIENTE"></span>%</b></small></th>
											  	<th class="text-center text-info"><small><small>Engajamento<br></small><b> &nbsp; <span id="tot_PCT_CLIENTE"></span>%</b></small></th>
											</tr>
										</thead>

									</table>

								</fieldset>

							</div>

						</div>

						<div class="push20"></div>

						<div class="row">
							<div class="col-md-4 col-xs-12 col-sm-12 bot_nav_esteira">
								<div class="push20"></div>
								<a class="btn btn-xs btn-default" href="javascript:" onclick="$('#relatorioConteudo tr').fadeOut('fast');">
									Esconder todos
								</a> &nbsp;&nbsp;
								
								<a class="btn btn-xs btn-default" href="javascript:" onclick="$('#relatorioConteudo tr').fadeIn('fast');">
									Mostrar todos
								</a>
							</div>
						</div>

						<div class="push20"></div>

						<div class="row">

							<div class="col-md-12">
								
								<table class="table table-bordered table-hover tablesorter">

									<?php

										if($tip_relat == 1){

											$completaSql = "";
											$objeto = "Loja";
											$objConsulta = 'LOJA';
											$colspan = "5";
											$colspanCli = "";

										}else{

											$completaSql = "_CLIENTE";
											$objeto = "Cliente";
											$objConsulta = 'NOME';
											$colspan = "4";
											$colspanCli = "3";

										}

									?>
									
									<thead>
									
										<tr>
											<th colspan="<?=$colspanCli?>" class="{sorter:false}" ></th>
 											<th colspan="<?=$colspan?>" class="text-center p1 {sorter:false}">Base</th>
											<th class="{sorter:false}"></th>
 											<th colspan="<?=$colspan?>" class="text-center {sorter:false}">Comparação</th>
											<th class="{sorter:false}"></th>
											<th colspan="5" class="text-center p1 {sorter:false}">Variação Percentual</th>
										</tr>									
									
										<tr>
											<th><small><?=$objeto?></small></th>
											<?php 
											if($objeto == "Cliente"){
											?>
												<th class="text-center p1"><small>Email</small></th>
												<th class="text-center p1"><small>Celular</small></th>
											<?php
											} 
											?>
											<th class="text-right p1"><small>Fat.</small></th>
											<th class="text-right p1"><small>Tkt. Med.</small></th>
											<th class="p1"><small>Tran.</small></th>
											<th class="p1"><small>Itens</small></th>
											<?php if($objeto == "Loja"){ ?>										
											<th class="p1"><small>Clientes</small></th>
											<?php } ?>
											<th class="{sorter:false}"></th>
											<th class="text-right"><small>Fat.</small></th>
											<th class="text-right"><small>Tkt. Med.</small></th>
											<th><small>Tran.</small></th>
											<th><small>Itens</small></th>											
											<?php if($objeto == "Loja"){ ?>										
											<th class="p1"><small>Clientes</small></th>
											<?php } ?>											
											<th class="{sorter:false}"></th>											
											<th class="p1"><small>%Fat.</small></th>											
											<th class="p1"><small>%Tkt.</small></th>											
											<th class="p1"><small>%Tran.</small></th>											
											<th class="p1"><small>%Itens</small></th>											
											<th class="p1"><small>%Cli.</small></th>											
										</tr>
										
									</thead>

									<tbody id="relatorioConteudo">							

									<?php


										// Filtro por Grupo de Lojas
										include "filtroGrupoLojas.php";

										$sql = "CALL SP_RELAT_PERCENTUAL_PERIODO_VENDA_PERSONAS$completaSql ( '$dat_ini' , '$dat_fim'  , '$dat_ini2' , '$dat_fim2', '$lojasSelecionadas' , $cod_empresa, '$cod_persona' )";
										//fnEscreve($sql);

										$tot_FATURAMENTO_P1 = "";
										$tot_VAL_TICKET_MEDIO_P1 = "";
										$tot_QTD_TRANSACAO_P1 = "";
										$tot_QTD_ITENS_P1 = "";
										$tot_QTD_CLIENTE_P1 = "";
										$tot_FATURAMENTO_P2 = "";
										$tot_VAL_TICKET_MEDIO_P2 = "";
										$tot_QTD_TRANSACAO_P2 = "";
										$tot_QTD_ITENS_P2 = "";
										$tot_QTD_CLIENTE_P2 = "";
										$tot_PERC_FATURAMENTO = "";
										$tot_PERC_TICKET_MEDIO = "";
										$tot_PERC_TRANSACAO = "";
										$tot_PERC_ITENS = "";
										$tot_PERC_CLIENTE = "";
										
										//fnEscreve($sql);
										//fnTestesql(connTemp($cod_empresa,''),$sql);											
										$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
										
										$count=0;
										while ($qrCupom = mysqli_fetch_assoc($arrayQuery))
										  {	
                                                                                    //echo '<pre>';
                                                                                    //print_r($qrCupom);
                                                                                    //echo '</pre>';
                                                                                    
										  	//$VAL_TICKET_MEDIO_P1 = $qrCupom['FATURAMENTO_P1']/$qrCupom['QTD_CLIENTE_P1'];
											//$VAL_TICKET_MEDIO_P2 = $qrCupom['FATURAMENTO_P2']/$qrCupom['QTD_CLIENTE_P2'];
										  	$VAL_TICKET_MEDIO_P1 = $qrCupom['FATURAMENTO_P1']/$qrCupom['QTD_TRANSACAO_P1'];
											$VAL_TICKET_MEDIO_P2 = $qrCupom['FATURAMENTO_P2']/$qrCupom['QTD_TRANSACAO_P2'];

										  	$tot_FATURAMENTO_P1 += $qrCupom['FATURAMENTO_P1'];
											$tot_VAL_TICKET_MEDIO_P1 += $VAL_TICKET_MEDIO_P1;
											$tot_QTD_TRANSACAO_P1 += $qrCupom['QTD_TRANSACAO_P1'];
											$tot_QTD_ITENS_P1 += $qrCupom['QTD_ITENS_P1'];
											$tot_QTD_CLIENTE_P1 += $qrCupom['QTD_CLIENTE_P1'];
											$tot_FATURAMENTO_P2 += $qrCupom['FATURAMENTO_P2'];
											$tot_VAL_TICKET_MEDIO_P2 += $VAL_TICKET_MEDIO_P2;
											$tot_QTD_TRANSACAO_P2 += $qrCupom['QTD_TRANSACAO_P2'];
											$tot_QTD_ITENS_P2 += $qrCupom['QTD_ITENS_P2'];
											$tot_QTD_CLIENTE_P2 += $qrCupom['QTD_CLIENTE_P2'];
											$tot_PERC_FATURAMENTO += $qrCupom['PERC_FATURAMENTO'];
											$tot_PERC_TICKET_MEDIO += $qrCupom['PERC_TICKET_MEDIO'];
											$tot_PERC_TRANSACAO += $qrCupom['PERC_TRANSACAO'];
											$tot_PERC_ITENS += $qrCupom['PERC_ITENS'];
											$tot_PERC_CLIENTE += $qrCupom['PERC_CLIENTE'];
											$tot_CLI_PERSONAS = $qrCupom['TOTAL_CLI_PERSONAS'];
											$tot_PES_UNICAS = $qrCupom['TOTAL_CLIENTES_UNICOS'];
											
											$tot_PES_UNICAS_P1 = $qrCupom['TOTAL_CLIENTES_UNICOS_P1'];
											$tot_PES_UNICAS_P2 = $qrCupom['TOTAL_CLIENTES_UNICOS_P2'];

											$VAR_PERC_FATURAMENTO = (( $qrCupom['FATURAMENTO_P2'] - $qrCupom['FATURAMENTO_P1'] ) / $qrCupom['FATURAMENTO_P1'] ) * 100;
											$VAR_PERC_TICKET_MEDIO = (( $VAL_TICKET_MEDIO_P2 - $VAL_TICKET_MEDIO_P1 ) / $VAL_TICKET_MEDIO_P1 ) * 100;
											$VAR_PERC_TRANSACAO = (($qrCupom['QTD_TRANSACAO_P2'] - $qrCupom['QTD_TRANSACAO_P1']) / $qrCupom['QTD_TRANSACAO_P1']) * 100;
											$VAR_PERC_ITENS = (( $qrCupom['QTD_ITENS_P2'] - $qrCupom['QTD_ITENS_P1'] ) / $qrCupom['QTD_ITENS_P1'] ) * 100;
											$VAR_PERC_CLIENTE = (( $qrCupom['QTD_CLIENTE_P2'] - $qrCupom['QTD_CLIENTE_P1'] ) / $qrCupom['QTD_CLIENTE_P1'] ) * 100;

											if($objeto == "Loja"){
												$linhaCliP1 = "<td class='p1'><small>".$qrCupom['QTD_CLIENTE_P1']."</small></td>";
												$linhaCliP2 = "<td><small>".$qrCupom['QTD_CLIENTE_P2']."</small></td>";
												$emailAndCel = "";
											}else{
												$linhaCliP1 = "";
												$linhaCliP2 = "";
												$emailAndCel = "<td><small>".$qrCupom['EMAIL']."</small></td>
																<td><small>".$qrCupom['CELULAR']."</small></td>";
											}				
											
											$count++;

											$id = fnEncode($count);

											echo"
												<tr id='".$id."'>
												  <td>
												  	<small>
												  		<a href='javascript:void(0)' style='padding:5px;' data-toggle='tooltip' data-placement='top' data-original-title='Esconder ".$objeto."' onclick='$(\"#".$id."\").fadeOut(\"fast\");'>
												  			<i class='fal fa-times'></i>
												  		</a>&nbsp;
												  		".$qrCupom[$objConsulta]."
												  	</small>
												  </td>
												  ".$emailAndCel."
												  <td class='text-right p1'><small> ".fnValor($qrCupom['FATURAMENTO_P1'],2)."</small></td>
												  <td class='text-right p1'><small> ".fnValor($VAL_TICKET_MEDIO_P1,2)."</small></td>
												  <td class='p1'><small>".$qrCupom['QTD_TRANSACAO_P1']."</small></td>
												  <td class='p1'><small>".$qrCupom['QTD_ITENS_P1']."</small></td>
												  ".$linhaCliP1."
												  <td></td>
												  <td class='text-right'><small> ".fnValor($qrCupom['FATURAMENTO_P2'],2)."</small></td>
												  <td class='text-right'><small> ".fnValor($VAL_TICKET_MEDIO_P2,2)."</small></td>
												  <td><small>".$qrCupom['QTD_TRANSACAO_P2']."</small></td>
												  <td><small>".$qrCupom['QTD_ITENS_P2']."</small></td>
												  ".$linhaCliP2."
												  <td></td>
												  <td class='text-right p1'><small>".fnValor($VAR_PERC_FATURAMENTO,2)."%</small></td>
												  <td class='text-right p1'><small>".fnValor($VAR_PERC_TICKET_MEDIO,2)."%</small></td>
												  <td class='text-right p1'><small>".fnValor($VAR_PERC_TRANSACAO,2)."%</small></td>
												  <td class='text-right p1'><small>".fnValor($VAR_PERC_ITENS,2)."%</small></td>
												  <td class='text-right p1'><small>".fnValor($VAR_PERC_CLIENTE,2)."%</small></td>
												</tr>
												"; 
											  }

											  	$tot_VAL_TICKET_MEDIO_P1 = $tot_FATURAMENTO_P1/$tot_QTD_TRANSACAO_P1;
											  	$tot_VAL_TICKET_MEDIO_P2 = $tot_FATURAMENTO_P2/$tot_QTD_TRANSACAO_P2;
											  	$tot_PERC_FATURAMENTO = (( $tot_FATURAMENTO_P2 - $tot_FATURAMENTO_P1 ) / $tot_FATURAMENTO_P1 ) * 100;
											  	$tot_PERC_TICKET_MEDIO = (( $tot_VAL_TICKET_MEDIO_P2 - $tot_VAL_TICKET_MEDIO_P1 ) / $tot_VAL_TICKET_MEDIO_P1 ) * 100;
											  	$tot_PERC_TRANSACAO = (($tot_QTD_TRANSACAO_P2 - $tot_QTD_TRANSACAO_P1 ) / $tot_QTD_TRANSACAO_P1 ) * 100;
											  	$tot_PERC_ITENS = (( $tot_QTD_ITENS_P2 - $tot_QTD_ITENS_P1 ) / $tot_QTD_ITENS_P1 ) * 100;
											  	$tot_PERC_CLIENTE = (( $tot_QTD_CLIENTE_P2 - $tot_QTD_CLIENTE_P1 ) / $tot_QTD_CLIENTE_P1 ) * 100;
												
												$tot_PERC_CLIENTE = (( $tot_QTD_CLIENTE_P2 - $tot_QTD_CLIENTE_P1 ) / $tot_QTD_CLIENTE_P1 ) * 100;
												
												//$tot_PCT_CLIENTE_P1 = ($tot_QTD_CLIENTE_P1 / $tot_PESSOAS_UNICAS) * 100;
												//$tot_PCT_CLIENTE_P2 = ($tot_QTD_CLIENTE_P2 / $tot_PESSOAS_UNICAS) * 100;
												$tot_PCT_CLIENTE_P1 = ($tot_QTD_CLIENTE_P1 / $tot_CLI_PERSONAS) * 100;
												$tot_PCT_CLIENTE_P2 = ($tot_QTD_CLIENTE_P2 / $tot_CLI_PERSONAS) * 100;
												$tot_PCT_CLIENTE = (($tot_PCT_CLIENTE_P2 - $tot_PCT_CLIENTE_P1) / $tot_PCT_CLIENTE_P1) * 100;
												
												//fnEscreve($tot_CLI_PERSONAS);
												//fnEscreve($tot_PCT_CLIENTE_P1);
												//fnEscreve($tot_PCT_CLIENTE_P2);												

									?>


										</tbody>

										<script type="text/javascript">
											
											$("#tot_FATURAMENTO_P1").text('<?=fnValor($tot_FATURAMENTO_P1,2)?>');
											$("#tot_VAL_TICKET_MEDIO_P1").text('<?=fnValor($tot_VAL_TICKET_MEDIO_P1,2)?>');
											$("#tot_QTD_TRANSACAO_P1").text('<?=fnValor($tot_QTD_TRANSACAO_P1,0)?>');
											$("#tot_QTD_ITENS_P1").text('<?=fnValor($tot_QTD_ITENS_P1,0)?>');
											$("#tot_QTD_CLIENTE_P1").text('<?=fnValor($tot_PES_UNICAS_P1,0)?>');
											$("#tot_FATURAMENTO_P2").text('<?=fnValor($tot_FATURAMENTO_P2,2)?>');
											$("#tot_VAL_TICKET_MEDIO_P2").text('<?=fnValor($tot_VAL_TICKET_MEDIO_P2,2)?>');
											$("#tot_QTD_TRANSACAO_P2").text('<?=fnValor($tot_QTD_TRANSACAO_P2,0)?>');
											$("#tot_QTD_ITENS_P2").text('<?=fnValor($tot_QTD_ITENS_P2,0)?>');
											$("#tot_QTD_CLIENTE_P2").text('<?=fnValor($tot_PES_UNICAS_P2,0)?>');
											$("#tot_PERC_FATURAMENTO").text('<?=fnValor($tot_PERC_FATURAMENTO,2)?>');
											$("#tot_PERC_TICKET_MEDIO").text('<?=fnValor($tot_PERC_TICKET_MEDIO,2)?>');
											$("#tot_PERC_TRANSACAO").text('<?=fnValor($tot_PERC_TRANSACAO,2)?>');
											$("#tot_PERC_ITENS").text('<?=fnValor($tot_PERC_ITENS,2)?>');
											$("#tot_PERC_CLIENTE").text('<?=fnValor($tot_PERC_CLIENTE,2)?>');
											
											$("#tot_PCT_CLIENTE_P1").text('<?=fnValor($tot_PCT_CLIENTE_P1,2)?>');
											$("#tot_PCT_CLIENTE_P2").text('<?=fnValor($tot_PCT_CLIENTE_P2,2)?>');
											$("#tot_CLI_PERSONAS").text('<?=fnValor($tot_CLI_PERSONAS,0)?>'); 
											$("#tot_PES_UNICAS").text('<?=fnValor($tot_PES_UNICAS,0)?>'); 
											$("#tot_PCT_CLIENTE").text('<?=fnValor($tot_PCT_CLIENTE,0)?>'); 


										</script>

										<tfoot>
											<tr>
												<th colspan="100">
													<a class="btn btn-info btn-sm exportarCSV"> <i class="fa fa-file-excel" aria-hidden="true"></i>&nbsp; Exportar</a>
												</th>
											</tr>													
										</tfoot>
										
									</table>

								</div>

							</div>

							
							<input type="hidden" name="LOJAS" id="LOJAS" value="<?php echo $lojasSelecionadas; ?>" />					
							<input type="hidden" name="opcao" id="opcao" value="">
							<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
							<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">		
							<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
							<div class="push5"></div> 
					
						
					
						<div class="push50"></div>									
				
						<div class="push"></div>
				
					</div>								
			
				</div>
			</div>
			<!-- fim Portlet -->
		</div>
            </form>
	</div>
	
	<div class="push20"></div>
	
	<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
	<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />

	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.min.js"></script> 
	<!-- Script dos labels -->
	<!-- <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.5.0"></script> -->
	<!-- --------------------------------------------------------------------------- -->
	<script src="js/pie-chart.js"></script>
    <script src="js/plugins/Chart_Js/utils.js"></script>	
	
    <script>
	
		//datas
		$(function () {

			var persona = '<?php echo $cod_persona; ?>';
			if(persona != 0 && persona != ""){
				//retorno combo multiplo - USUARIOS_ENV
				$("#formulario #COD_PERSONA").val('').trigger("chosen:updated");

				var sistemasUni = '<?php echo $cod_persona; ?>';				
				var sistemasUniArr = sistemasUni.split(',');				
				//opções multiplas
				for (var i = 0; i < sistemasUniArr.length; i++) {
				  $("#formulario #COD_PERSONA option[value=" + Number(sistemasUniArr[i]).toString() + "]").prop("selected", "true");				  
				}
				$("#formulario #COD_PERSONA").trigger("chosen:updated");
			}
			
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
											url: "relatorios/ajxPercPeriodoPersonas.do?opcao=exportar&nomeRel="+nome+"&id=<?php echo fnEncode($cod_empresa); ?>",
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

			// function escondeUnidade(id_linha){

			// }

			var data = {
			    labels: ["Fatia 1", "Fatia 2"],
			      datasets: [
			        {
			            
			            backgroundColor: [
			               '#DCE4EC',
			               '#5AA9E1'
			            ],
			            data: [<?=35?>, <?=65?>],
			// Notice the borderColor 
			            // borderColor: ['black', 'black'],
			            borderWidth: [1,1]
			        }
			    ]
			};

			// Chart declaration:
			var mypieChart = new Chart(document.getElementById("pieChart").getContext('2d'), {
			    type: 'pie',
			    data: data,
			    options: {
			    	tooltips: {
				      callbacks: {
				        title: function(tooltipItem, data) {
				          return data['labels'][tooltipItem[0]['index']];
				        },
				        label: function(tooltipItem, data) {
				          return data['datasets'][0]['data'][tooltipItem['index']]+'%';
				        },
				        // afterLabel: function(tooltipItem, data) {
				        //   var dataset = data['datasets'][0];
				        //   var percent = Math.round((dataset['data'][tooltipItem['index']] / dataset["_meta"][0]['total']) * 100)
				        //   return '(' + percent + '%)';
				        // }
				      }
				    },
			    	rotation: -0.3 * Math.PI,
			    	title: {
						display: true,
						position: 'bottom',
						text: "Total: R$<?=fnValor(100,2)?>" 
					  },
			    }
			});

			var data2 = {
			    labels: ["Fatia 1", "Fatia 2"],
			      datasets: [
			        {
			            
			            backgroundColor: [
			               '#DCE4EC',
			               '#5AA9E1'
			            ],
			            data: [<?=5?>, <?=95?>],
			// Notice the borderColor 
			            // borderColor: ['black', 'black'],
			            borderWidth: [1,1]
			        }
			    ]
			};

			// Chart declaration:
			var mypieChart = new Chart(document.getElementById("pieChart2").getContext('2d'), {
			    type: 'pie',
			    data: data2,
			    options: {
			    	tooltips: {
				      callbacks: {
				        title: function(tooltipItem, data) {
				          return data2['labels'][tooltipItem[0]['index']];
				        },
				        label: function(tooltipItem, data) {
				          return data2['datasets'][0]['data'][tooltipItem['index']]+'%';
				        },
				        // afterLabel: function(tooltipItem, data) {
				        //   var dataset = data['datasets'][0];
				        //   var percent = Math.round((dataset['data'][tooltipItem['index']] / dataset["_meta"][0]['total']) * 100)
				        //   return '(' + percent + '%)';
				        // }
				      }
				    },
			    	rotation: -0.3 * Math.PI,
			    	title: {
						display: true,
						position: 'bottom',
						text: "Total: R$<?=fnValor(100,2)?>" 
					  },
			    }
			});

			var data3 = {
			    labels: ["Fatia 1", "Fatia 2"],
			      datasets: [
			        {
			            
			            backgroundColor: [
			               '#DCE4EC',
			               '#5AA9E1'
			            ],
			            data: [<?=5?>, <?=95?>],
			// Notice the borderColor 
			            // borderColor: ['black', 'black'],
			            borderWidth: [1,1]
			        }
			    ]
			};

			// Chart declaration:
			var mypieChart = new Chart(document.getElementById("pieChart3").getContext('2d'), {
			    type: 'pie',
			    data: data3,
			    options: {
			    	tooltips: {
				      callbacks: {
				        title: function(tooltipItem, data) {
				          return data2['labels'][tooltipItem[0]['index']];
				        },
				        label: function(tooltipItem, data) {
				          return data2['datasets'][0]['data'][tooltipItem['index']]+'%';
				        },
				        // afterLabel: function(tooltipItem, data) {
				        //   var dataset = data['datasets'][0];
				        //   var percent = Math.round((dataset['data'][tooltipItem['index']] / dataset["_meta"][0]['total']) * 100)
				        //   return '(' + percent + '%)';
				        // }
				      }
				    },
			    	rotation: -0.3 * Math.PI,
			    	title: {
						display: true,
						position: 'bottom',
						text: "Total: R$<?=fnValor(100,2)?>" 
					  },
			    }
			});

			var data4 = {
			    labels: ["Fatia 1", "Fatia 2"],
			      datasets: [
			        {
			            
			            backgroundColor: [
			               '#DCE4EC',
			               '#5AA9E1'
			            ],
			            data: [<?=5?>, <?=95?>],
			// Notice the borderColor 
			            // borderColor: ['black', 'black'],
			            borderWidth: [1,1]
			        }
			    ]
			};

			// Chart declaration:
			var mypieChart = new Chart(document.getElementById("pieChart4").getContext('2d'), {
			    type: 'pie',
			    data: data4,
			    options: {
			    	tooltips: {
				      callbacks: {
				        title: function(tooltipItem, data) {
				          return data2['labels'][tooltipItem[0]['index']];
				        },
				        label: function(tooltipItem, data) {
				          return data2['datasets'][0]['data'][tooltipItem['index']]+'%';
				        },
				        // afterLabel: function(tooltipItem, data) {
				        //   var dataset = data['datasets'][0];
				        //   var percent = Math.round((dataset['data'][tooltipItem['index']] / dataset["_meta"][0]['total']) * 100)
				        //   return '(' + percent + '%)';
				        // }
				      }
				    },
			    	rotation: -0.3 * Math.PI,
			    	title: {
						display: true,
						position: 'bottom',
						text: "Total: R$<?=fnValor(100,2)?>" 
					  },
			    }
			});

			var data5 = {
			    labels: ["Fatia 1", "Fatia 2"],
			      datasets: [
			        {
			            
			            backgroundColor: [
			               '#DCE4EC',
			               '#5AA9E1'
			            ],
			            data: [<?=5?>, <?=95?>],
			// Notice the borderColor 
			            // borderColor: ['black', 'black'],
			            borderWidth: [1,1]
			        }
			    ]
			};

			// Chart declaration:
			var mypieChart = new Chart(document.getElementById("pieChart5").getContext('2d'), {
			    type: 'pie',
			    data: data5,
			    options: {
			    	tooltips: {
				      callbacks: {
				        title: function(tooltipItem, data) {
				          return data2['labels'][tooltipItem[0]['index']];
				        },
				        label: function(tooltipItem, data) {
				          return data2['datasets'][0]['data'][tooltipItem['index']]+'%';
				        },
				        // afterLabel: function(tooltipItem, data) {
				        //   var dataset = data['datasets'][0];
				        //   var percent = Math.round((dataset['data'][tooltipItem['index']] / dataset["_meta"][0]['total']) * 100)
				        //   return '(' + percent + '%)';
				        // }
				      }
				    },
			    	rotation: -0.3 * Math.PI,
			    	title: {
						display: true,
						position: 'bottom',
						text: "Total: R$<?=fnValor(100,2)?>" 
					  },
			    }
			});

			//grouped
			new Chart(document.getElementById("bar-chart-grouped-fat"), {
				type: 'bar',
				// plugins: [ChartDataLabels],
				data: {
				  labels: ["Base", "Comparação"],
				  datasets: [
					{
					 //  datalabels: {
						// clamp: true,
						// align: 'middle',
						// anchor: 'end',
						// borderRadius: 6,
						// backgroundColor: ['#DCE4EC','#5AA9E1'],
						// color: ['#3c3c3c','#fefefe'],
						// formatter: function(value) {
						//     if(parseInt(value) >= 1000){
				  //               return 'R$ ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
				  //             } else {
				  //               return 'R$ ' + value;
				  //             }
						//     // eq. return ['line1', 'line2', value]
						// }
						
					 //  },
					  label: "",
					  borderColor: ['#DCE4EC','#5AA9E1'],
					  backgroundColor: ['#DCE4EC','#5AA9E1'],
					  borderWidth: 1,
					  data: [<?=$tot_FATURAMENTO_P1?>,<?=$tot_FATURAMENTO_P2?>]
					}
				  ]
				},
				options: {
				  legend: {
				    display: false
				  },
				  title: {
					display: true,
					text: 'Variação: <?=fnValor($tot_PERC_FATURAMENTO,2)?> %',
					position: 'bottom'
				  },
				   tooltips: {
				      callbacks: {
				         label: function (t, d) {
					        return 'R$ ' + t.yLabel.toFixed(2)
					  }
					}
				   },
				  scales: {						
						yAxes: [{
							ticks: {
								callback: function(value, index, values) {
					              if(parseInt(value) >= 1000){
					                return 'R$ ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
					              } else {
					                return 'R$ ' + value;
					              }
					            }
							}													
						}]					
					},
				  
				}
			});

			//grouped
			new Chart(document.getElementById("bar-chart-grouped-tkt"), {
				type: 'bar',
				// plugins: [ChartDataLabels],
				data: {
				   labels: ["Base", "Comparação"],
				  datasets: [
					{
					 //  datalabels: {
						// clamp: true,
						// align: 'middle',
						// anchor: 'end',
						// borderRadius: 6,
						// backgroundColor: ['#DCE4EC','#5AA9E1'],
						// color: ['#3c3c3c','#fefefe'],
						// formatter: function(value) {
						//     if(parseInt(value) >= 1000){
				  //               return 'R$ ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".").toFixed(2);
				  //             } else {
				  //               return 'R$ ' + value.toFixed(2);
				  //             }
						//     // eq. return ['line1', 'line2', value]
						// }
					 //  },
					  label: "",
					  borderColor: ['#DCE4EC','#5AA9E1'],
					  backgroundColor: ['#DCE4EC','#5AA9E1'],
					  borderWidth: 1,
					  data: [<?=$tot_VAL_TICKET_MEDIO_P1?>,<?=$tot_VAL_TICKET_MEDIO_P2?>]
					}
				  ]
				},
				options: {
				  legend: {
				    display: false
				  },
				  title: {
					display: true,
					text: 'Variação: <?=fnValor($tot_PERC_TICKET_MEDIO,2)?> %',
					position: 'bottom'
				  },
				   tooltips: {
				      callbacks: {
				         label: function (t, d) {
					        return 'R$ ' + t.yLabel.toFixed(2)
					  }
					}
				   },
				  scales: {						
						yAxes: [{
							ticks: {
								callback: function(value, index, values) {
					              if(parseInt(value) >= 1000){
					                return 'R$ ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
					              } else {
					                return 'R$ ' + value;
					              }
					            }
							}													
						}]					
					},
				  
				}
			});

			//grouped
			new Chart(document.getElementById("bar-chart-grouped-tran"), {
				type: 'bar',
				// plugins: [ChartDataLabels],
				data: {
				  labels: ["Base", "Comparação"],
				  datasets: [
					{
					 //  datalabels: {
						// clamp: true,
						// align: 'middle',
						// anchor: 'end',
						// borderRadius: 6,
						// backgroundColor: ['#DCE4EC','#5AA9E1'],
						// color: ['#3c3c3c','#fefefe'],
						// formatter: function(value) {
						//     if(parseInt(value) >= 1000){
				  //               return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
				  //             } else {
				  //               return value;
				  //             }
						//     // eq. return ['line1', 'line2', value]
						// }
					 //  },
					  label: "",
					  borderColor: ['#DCE4EC','#5AA9E1'],
					  backgroundColor: ['#DCE4EC','#5AA9E1'],
					  borderWidth: 1,
					  data: [<?=$tot_QTD_TRANSACAO_P1?>,<?=$tot_QTD_TRANSACAO_P2?>]
					}
				  ]
				},
				options: {
				  legend: {
				    display: false
				  },
				  title: {
					display: true,
					text: 'Variação: <?=fnValor($tot_PERC_TRANSACAO,2)?> %',
					position: 'bottom'
				  },
				   tooltips: {
				      callbacks: {
				         label: function (t, d) {
					        return 'Qtd.: ' + t.yLabel
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
				  
				}
			});

			//grouped
			new Chart(document.getElementById("bar-chart-grouped-item"), {
				type: 'bar',
				// plugins: [ChartDataLabels],
				data: {
				  labels: ["Base", "Comparação"],
				  datasets: [
					{
					 //  datalabels: {
						// clamp: true,
						// align: 'middle',
						// anchor: 'end',
						// borderRadius: 6,
						// backgroundColor: ['#DCE4EC','#5AA9E1'],
						// color: ['#3c3c3c','#fefefe'],
						// formatter: function(value) {
						//     if(parseInt(value) >= 1000){
				  //               return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
				  //             } else {
				  //               return value;
				  //             }
						//     // eq. return ['line1', 'line2', value]
						// }
					 //  },
					  label: "",
					  borderColor: ['#DCE4EC','#5AA9E1'],
					  backgroundColor: ['#DCE4EC','#5AA9E1'],
					  borderWidth: 1,
					  data: [<?=$tot_QTD_ITENS_P1?>,<?=$tot_QTD_ITENS_P2?>]
					}
				  ]
				},
				options: {
				  legend: {
				    display: false
				  },
				  title: {
					display: true,
					text: 'Variação: <?=fnValor($tot_PERC_ITENS,2)?> %',
					position: 'bottom'
				  },
				   tooltips: {
				      callbacks: {
				         label: function (t, d) {
					        return 'Qtd.: ' + t.yLabel
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
				  
				}
			});

			//grouped
			new Chart(document.getElementById("bar-chart-grouped-cli"), {
				type: 'bar',
				// plugins: [ChartDataLabels],
				data: {
				  labels: ["Base", "Comparação"],
				  datasets: [
					{
					 //  datalabels: {
						// clamp: true,
						// align: 'middle',
						// anchor: 'end',
						// borderRadius: 6,
						// backgroundColor: ['#DCE4EC','#5AA9E1'],
						// color: ['#3c3c3c','#fefefe'],
						// formatter: function(value) {
						//     if(parseInt(value) >= 1000){
				  //               return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
				  //             } else {
				  //               return value;
				  //             }
						//     // eq. return ['line1', 'line2', value]
						// }
					 //  },
					  label: "",
					  borderColor: ['#DCE4EC','#5AA9E1'],
					  backgroundColor: ['#DCE4EC','#5AA9E1'],
					  borderWidth: 1,
					  data: [<?=$tot_QTD_CLIENTE_P1?>,<?=$tot_QTD_CLIENTE_P2?>]
					}
				  ]
				},
				options: {
				  legend: {
				    display: false
				  },
				  title: {
					display: true,
					text: 'Variação: <?=fnValor($tot_PERC_CLIENTE,2)?> %',
					position: 'bottom'
				  },
				   tooltips: {
				      callbacks: {
				         label: function (t, d) {
					        return 'Qtd.: ' + t.yLabel
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
				  
				}
			});

		});


		
	</script>	
   