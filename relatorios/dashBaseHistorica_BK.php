<?php
//https://adm.bunker.mk/action.do?mod=VU6Q8bfsZp%C2%A30%C2%A2&id=GLtHxidZjko%C2%A2

	//setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
	//date_default_timezone_set('America/Sao_Paulo');
	
	//echo fnDebug('true');

	$hashLocal = mt_rand();	
	
	//inicialização de variáveis
	$hoje = fnFormatDate(date("Y-m-d"));
	$hoje = fnFormatDate(date('Y-m-d', strtotime($hoje. '- 1 days')));
	$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 30 days')));

	$dt_filtro = "";
	$mes = ucfirst(strftime('%B', strtotime($hoje)));
	$mes_nome = ucfirst(strftime('%B', strtotime($hoje)));
	$mesAnt = ucfirst(strftime('%B', strtotime($hoje)));
	
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
			$cod_grupotr = $_REQUEST['COD_GRUPOTR'];	
			$cod_tiporeg = $_REQUEST['COD_TIPOREG'];
			$dt_filtro = $_REQUEST['DT_FILTRO'];
			$dt_exibe = $dt_filtro."-01";
			$mes = ucfirst(strftime('%B', strtotime($dt_exibe)));
			$mes_nome = ucfirst(strftime('%B', strtotime($dt_exibe)));
			$mesAnt = ucfirst(strftime('%B', strtotime($dt_exibe)));

			$dat_ini = fnDatasql($_REQUEST['DAT_INI']);
			$dat_fim = fnDatasql($_REQUEST['DAT_FIM']);

			if (empty($_REQUEST['LOG_LABELS'])) {$log_labels='N';}else{$log_labels=$_REQUEST['LOG_LABELS'];}
			 
	   
			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];

			// fnescreve($dt_exibe);
			// fnescreve($dt_filtro);
			
			if ($opcao != ''){
				
				
			}  
			

		}
	}
		
	//busca dados da url	
	if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))){
		//busca dados da empresa
		$cod_empresa = fnDecode($_GET['id']);	
		$sql = "SELECT COD_EMPRESA, NOM_FANTASI, DAT_CADASTR FROM empresas where COD_EMPRESA = '".$cod_empresa."' ";
		//fnEscreve($sql);
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
		
		if (isset($arrayQuery)){
			$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
			$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
			$dat_cadastr = $qrBuscaEmpresa['DAT_CADASTR'];
		}
												
	}else {
		$cod_empresa = 0;		
		//fnEscreve('entrou else');
	}
	// $log_labels = 'S';

	if($log_labels == 'S'){
		$checkLabels = "checked";
	}else{
		$checkLabels = "";
	}


	$sqlRef = "SELECT MAX(DAT_CADASTR) AS DAT_REF_MAX, MIN(DAT_CADASTR) AS DAT_REF_MIN FROM VENDAS_BKP WHERE COD_EMPRESA = $cod_empresa";
	$qrRef = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sqlRef));
	$dat_ref_max = $qrRef['DAT_REF_MAX'];
	$dat_ref_min = $qrRef['DAT_REF_MIN'];

	// fnEscreve($dat_ref_min);
	// fnEscreve($dat_ref_max);
	//inicialização das variáveis - default	
	if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31" ){
		$dat_ini = ""; 
	} 
	if (strlen($dat_fim ) == 0 || $dat_fim == "1969-12-31"){
		$dat_fim = ""; 
	}

	// fnEscreve($dat_ini);
	// fnEscreve($dat_fim);

	//busca revendas do usuário
	include "unidadesAutorizadas.php";

	// Filtro por Grupo de Lojas
	include "filtroGrupoLojas.php";

	// $array_dat_fim  = explode("/", $dat_fim);

	// $dat_fim = cal_days_in_month(CAL_GREGORIAN, $array_dat_fim[0], $array_dat_fim[1])."/".$dat_fim;	

	// $dat_ini = fnDatasql($dat_ini);
 // $dat_fim = fnDatasql($dat_fim);

?>

<style>
	
	.circle {
	  display: block;
	  border-radius: 50%;
	  height: 75px;
	  width: 75px;
	  margin: auto; 
	  padding: 24px 0;  
	}

	.circle span {
	  font-size:20px;
	  color: #ffffff;
	  font-weight: bold;
	}

	.circle2 {
	  display: block;
	  border-radius: 50%;
	  height: 75px;
	  width: 75px;
	  margin: auto; 
	  padding: 28px 0;  
	}

	.circle2 span {
	  font-size:17px;
	  color: #ffffff;
	  font-weight: bold;
	}

	.corBase {background: #F8F9F9;}
	
	.cor1 {background: #EC7063;}
	.cor2 {background: #F4D03F;}
	.cor3 {background: #58D68D;}
	.cor4 {background: #5DADE2;}
	.cor5 {background: #909497;}
	
	.fCor1 {color: #EC7063;}
	.fCor2 {color: #F4D03F;}
	.fCor3 {color: #58D68D;}
	.fCor4 {color: #5DADE2;}
	.fCor5 {color: #909497;}

	.cor1on {background: #CB4335; font-size:18px !important;}
	.cor2on {background: #D4AC0D; font-size:18px !important;}
	.cor3on {background: #239B56; font-size:18px !important;}
	.cor4on {background: #2874A6; font-size:18px !important;}

	.bar {
		font-size: 16px;
		line-height: 50px;
		height:50px;
		border-radius: 5px;
		color: #ffffff;
		font-weight: bold;
		text-align: left;
		margin: auto;
	}

	.f30 {
		font-size: 30px;
		font-weight: bold;
	}

	.bar span{
	  background: rgba(255,255,255,0.3);
	  padding: 6px 9px;
	  border-radius: 4px;
	  margin-left: 15px;
	  font-size:18px;
	}
	
	.tooltip.top .tooltip-inner {
		color: #3c3c3c;
		min-width: 140px;
		min-height: 60px;
		padding-top: 10px;
		font-size: 16px;
		background-color:white;
		opacity: 1!important;
		filter: alpha(opacity=100)!important;
		-webkit-box-shadow: 0px 0px 11px 0px rgba(186,186,186,1);
		-moz-box-shadow: 0px 0px 11px 0px rgba(186,186,186,1);
		box-shadow: 0px 0px 11px 0px rgba(186,186,186,1);
	}
	.tooltip.top .tooltip-arrow {
		border-top-color: white;
		opacity: 1!important;
		filter: alpha(opacity=100)!important;
	}

	.tooltip.in {
		opacity: 0.97!important;
		filter: alpha(opacity=97)!important;
	}

</style>

					<link href="https://unpkg.com/minibarjs@latest/dist/minibar.min.css" rel="stylesheet" type="text/css">
					<div class="push30"></div> 
					
					<div class="row">				
					
						<div class="col-md-12 margin-bottom-30">
							<!-- Portlet -->
							<div class="portlet portlet-bordered">

								<div class="portlet-title">
									<div class="caption">
										<i class="glyphicon glyphicon-calendar"></i>
										<span class="text-primary"><?php echo $NomePg; ?></span>
									</div>
									
									<?php 
									include "backReport.php"; 
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
														
														<div class="col-md-3">
															<div class="form-group">
																<label for="inputName" class="control-label">Região</label>
																<?php include "grupoRegiaoMulti.php"; ?>
															</div>
														</div>

													</div>

													<div class="row">

														<div class="col-md-2">
															<div class="form-group">
																<label for="inputName" class="control-label">Referência Histórica</label>
																<input type="text" class="form-control input-sm leitura" readonly="readonly" name="DAT_REF" id="DAT_REF" value="<?=fnDataShort($dat_ref_min)?>">
															</div>	
															<div class="help-block with-errors">Data mínima</div>																														
														</div>

														<div class="col-md-2">
															<div class="form-group">
																<label for="inputName" class="control-label">&nbsp;</label>
																<input type="text" class="form-control input-sm leitura" readonly="readonly" name="DAT_REF" id="DAT_REF" value="<?=fnDataShort($dat_ref_max)?>">
															</div>	
															<div class="help-block with-errors">Data máxima</div>															
														</div>

														<div class="col-md-2">
															<div class="form-group">
																<label for="inputName" class="control-label required">Data Inicial</label>
																
																<div class="input-group date datePicker" id="DAT_INI_GRP">
																	<input type='text' class="form-control input-sm" name="DAT_INI" id="DAT_INI" value="<?=fnDataShort($dat_ini)?>" required/>
																	<span class="input-group-addon">
																		<span class="glyphicon glyphicon-calendar"></span>
																	</span>
																</div>
																<div class="help-block with-errors"></div>
															</div>
														</div>
														
														<div class="col-md-2">
															<div class="form-group">
																<label for="inputName" class="control-label required">Data Final</label>
																
																<div class="input-group date datePicker" id="DAT_FIM_GRP">
																	<input type='text' class="form-control input-sm" name="DAT_FIM" id="DAT_FIM" value="<?=fnDataShort($dat_fim)?>" required/>
																	<span class="input-group-addon">
																		<span class="glyphicon glyphicon-calendar"></span>
																	</span>
																</div>
																<div class="help-block with-errors"></div>
															</div>
														</div>
														
														<div class="col-md-1">   
															<div class="form-group">
																<label for="inputName" class="control-label">Exibir legendas</label> 
																<div class="push5"></div>
																	<label class="switch">
																	<input type="checkbox" name="LOG_LABELS" id="LOG_LABELS" class="switch" value="S" <?=$checkLabels?>>
																	<span></span>
																	</label>
															</div>
														</div>
														
														<div class="col-md-2">
															<div class="push20"></div>
															<button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-sm btn-block getBtn"><i class="fa fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
														</div>
																					
													</div>
													
											</fieldset>

											<input type="hidden" name="LOJAS" id="LOJAS" value="<?=$lojasSelecionadas?>">
											<input type="hidden" name="opcao" id="opcao" value="">
											<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
											<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

										</form>
									
									</div>
								
								</div>

							</div>
							<!-- fim Portlet -->						
						</div>

					</div>

					<?php

						if($dat_ini != ""){

							$sql = "CALL SP_RELAT_COMPARACAO_CONSOLIDADA ( '".$dat_ini."' , '".$dat_fim."' , '$lojasSelecionadas' , $cod_empresa , 'LOJA' ) ;";
							//fnEscreve($sql);
							$arrayQuery1 = mysqli_query(connTemp($cod_empresa,''),$sql);
						
						// -$qtd_consulta = mysqli_num_rows($arrayQuery);

					?>	

					<div class="row">				
					
						<div class="col-md-12 margin-bottom-30">
							<!-- Portlet -->
							<div class="portlet portlet-bordered">
							
								<div class="portlet-body">
	
										<div class="row text-center">											
																		
											<div class="col-md-12 col-lg-12">
												
												<h4>Perfil do Cliente</h4>											
												<div class="push20"></div>												

												<div class="form-group text-center col-md-4 col-lg-4">
													
													<h5>Total de clientes com compras no período</h5>
													<div class="push20"></div>
													
													<div style="max-height: 200px; max-width:100%;">
														<canvas id="chart-area" style="height: 100%"></canvas>
													</div>												

													<div class="push50"></div>
													
												</div>
												
												<div class="form-group text-center col-md-4 col-lg-4">
														
													<h5>Idade Média dos Clientes Cadastrados</h5>												
													<div class="push20"></div>
													
													<div style="max-height: 200px; max-width:100%;">
														<canvas id="bar-chart-grouped" style="height: 100%"></canvas>
													</div>
		   
												</div>
												
												<div class="form-group text-center col-md-4 col-lg-4">												
													
													<h5>Cadastros</h5>
													<div class="push20"></div>
													
													<div style="max-height: 200px; max-width:100%;">
														<canvas id="bar-chart-grouped-2" style="height: 100%"></canvas>
													</div>

												</div>

											</div>

										</div>

										<div class="push100"></div>
<!-- =========================================== -->								
								</div>
							<!-- fim Portlet -->
							</div>
						
						</div>

					</div>

					<div class="row">				
					
						<div class="col-md-12 col-lg-12 margin-bottom-30">
							<!-- Portlet -->
							<div class="portlet portlet-bordered">
							
								<div class="portlet-body">		


									<div class="row text-center">
										
										<div class="form-group text-center col-md-4 col-lg-4">

											<div class="push20"></div>
												
											<p><span id="QTD_TRANSACOES"><?=fnValor(0,0)?></span></p>
											<p><b>Quantidade de compras no período</b></p>
										
											<div class="push20"></div>

										</div>

										<div class="form-group text-center col-md-4 col-lg-4">

											<div class="push20"></div>
												
											<p>R$<span id="VL_FATURAMENTO"><?=fnValor(0,2)?></span></p>
											<p><b>Faturamento do grupo no período</b></p>
										
											<div class="push20"></div>

										</div>

										<div class="form-group text-center col-md-4 col-lg-4">

											<div class="push20"></div>
												
											<p>R$<span id="TICKET_MEDIO"><?=fnValor(0,2)?></span></p>
											<p><b>Ticket médio do grupo no período</b></p>
										
											<div class="push20"></div>

										</div>

									</div>					
					
								</div>
							<!-- fim Portlet -->
							</div>
						
						</div>
						
					</div>		

					<div class="row">				
					
						<div class="col-md-12 col-lg-12 margin-bottom-30">
							<!-- Portlet -->
							<div class="portlet portlet-bordered">
							
								<div class="portlet-body">		


									<div class="row text-center">
										
										<div class="form-group text-center col-md-12 col-lg-12 slimscroll">
												
											<!-- <h5>Lorem Ipsum</h5> -->
											<div class="push20"></div>
											<div class="push20"></div>
											
											<table class="table table-striped">

												<thead>
													<tr>													      
														<th scope="col">Loja</th>
														<th scope="col">Qtd. Clientes</th>
														<th scope="col">Qtd. Juridico</th>
														<th scope="col">Qtd. Email</th>
														<th scope="col">% Email</th>
														<th scope="col">Qtd. Celular</th>
														<th scope="col">% Celular</th>
														<th scope="col">Qtd. Nascimento</th>
														<th scope="col">% Nascime</th>
														<th scope="col">Qtd. Cep</th>
														<th scope="col">% Cep</th>
														<th scope="col">Qtd. Endereco</th>
														<th scope="col">% Endereco</th>
														<th scope="col">idade_media</th>
														<th scope="col">Vl. Faturamento</th>
														<th scope="col">Qtd. Transacoes</th>
														<th scope="col">Qtd. Clinovos</th>
														<th scope="col">TM</th>
														<th scope="col">Qtd. Masculino</th>
														<th scope="col">% Homem</th>
														<th scope="col">Qtd. Feminino</th>
														<th scope="col">% Mulher</th>
													</tr>
												</thead>

												<tbody>

													<?php

														$count = 1;

														while($qrComp = mysqli_fetch_assoc($arrayQuery1)){
															
															$qtd_clientes += $qrComp['QTD_CLIENTES'];
															$qtd_clientes_novos += $qrComp['QTD_CLINOVOS'];
															$qtd_juridico += $qrComp['QTD_JURIDICO'];

															$vl_faturamento += $qrComp['VL_FATURAMENTO'];
															$qtd_transacoes += $qrComp['QTD_TRANSACOES'];
															// fnEscreve($qtd_transacoes);
															$ticket_medio += $qrComp['TICKET_MEDIO'];
															
															$pct_masculino += $qrComp['PERC_HOMEM'];
															$pct_feminino += $qrComp['PERC_MULHER'];

															$qtd_masculino += $qrComp['QTD_MASCULINO'];
															$qtd_feminino += $qrComp['QTD_FEMININO'];											
															
															$idade_media += $qrComp['IDADE_MEDIA'];
															$qtd_idade0 += $qrComp['QTD_IDADE0'];
															$qtd_idade1 += $qrComp['QTD_IDADE1'];
															$qtd_idade2 += $qrComp['QTD_IDADE2'];
															$qtd_idade3 += $qrComp['QTD_IDADE3'];
															$qtd_idade4 += $qrComp['QTD_IDADE4'];
															$qtd_idade5 += $qrComp['QTD_IDADE5'];
															$qtd_idade6 += $qrComp['QTD_IDADE6'];
															$qtd_idade7 += $qrComp['QTD_IDADE7'];
															$qtd_idade8 += $qrComp['QTD_IDADE8'];

															$qtd_email += $qrComp['QTD_EMAIL'];
															$pct_email += $qrComp['PERC_EMAIL'];
															$qtd_celular += $qrComp['QTD_CELULAR'];
															$pct_celular += $qrComp['PERC_CELULAR'];
															$qtd_nascimento += $qrComp['QTD_NASCIMENTO'];
															$pct_nascimento += $qrComp['PERC_NASCIME'];
															$qtd_cep += $qrComp['QTD_CEP'];
															$pct_cep += $qrComp['PERC_CEP'];
															$qtd_endereco += $qrComp['QTD_ENDERECO'];
															$pct_endereco += $qrComp['PERC_ENDERECO'];

													?>

															<tr>
																<td><?=$qrComp['LOJA']?></td>
																<td><?=fnValor($qrComp['QTD_CLIENTES'],0)?></td>
																<td><?=fnValor($qrComp['QTD_JURIDICO'],0)?></td>
																<td><?=fnValor($qrComp['QTD_EMAIL'],0)?></td>
																<td class="text-center"><?=fnValor($qrComp['PERC_EMAIL'],2)?>%</td>
																<td><?=fnValor($qrComp['QTD_CELULAR'],0)?></td>
																<td class="text-center"><?=fnValor($qrComp['PERC_CELULAR'],2)?>%</td>
																<td><?=fnValor($qrComp['QTD_NASCIMENTO'],0)?></td>
																<td class="text-center"><?=fnValor($qrComp['PERC_NASCIME'],2)?>%</td>
																<td><?=fnValor($qrComp['QTD_CEP'],0)?></td>
																<td class="text-center"><?=fnValor($qrComp['PERC_CEP'],2)?>%</td>
																<td><?=fnValor($qrComp['QTD_ENDERECO'],0)?></td>
																<td class="text-center"><?=fnValor($qrComp['PERC_ENDERECO'],2)?>%</td>
																<td><?=fnValor($qrComp['IDADE_MEDIA'],0)?></td>
																<td class="text-right">R$<?=fnValor($qrComp['VL_FATURAMENTO'],2)?></td>
																<td><?=fnValor($qrComp['QTD_TRANSACOES'],0)?></td>
																<td><?=fnValor($qrComp['QTD_CLINOVOS'],0)?></td>
																<td class="text-right">R$<?=fnValor($qrComp['TICKET_MEDIO'],2)?></td>
																<td><?=fnValor($qrComp['QTD_MASCULINO'],0)?></td>
																<td class="text-center"><?=fnValor($qrComp['PERC_HOMEM'],2)?>%</td>
																<td><?=fnValor($qrComp['QTD_FEMININO'],0)?></td>
																<td class="text-center"><?=fnValor($qrComp['PERC_MULHER'],2)?>%</td>
															</tr>

													<?php

															$count ++;

														}

														$pct_masculino = $pct_masculino/$count;
														$pct_feminino = $pct_feminino/$count;

														$pct_email = $pct_email/$count;
														$pct_celular = $pct_celular/$count;
														$pct_nascimento = $pct_nascimento/$count;
														$pct_cep = $pct_cep/$count;
														$pct_endereco = $pct_endereco/$count;

														$pct_idade0 = ($qtd_idade0/$qtd_clientes)*100;
														$pct_idade1 = ($qtd_idade1/$qtd_clientes)*100;
														$pct_idade2 = ($qtd_idade2/$qtd_clientes)*100;
														$pct_idade3 = ($qtd_idade3/$qtd_clientes)*100;
														$pct_idade4 = ($qtd_idade4/$qtd_clientes)*100;
														$pct_idade5 = ($qtd_idade5/$qtd_clientes)*100;
														$pct_idade6 = ($qtd_idade6/$qtd_clientes)*100;
														$pct_idade7 = ($qtd_idade7/$qtd_clientes)*100;
														$pct_idade8 = ($qtd_idade8/$qtd_clientes)*100;

													?>

												</tbody>

											</table>
										
											<div class="push50"></div>

										</div>

										<div class="push20"></div>

										<div class="row">
											<div class="col-md-12">
												<a class="btn btn-info btn-sm exportarCSV pull-left"> <i class="fa fa-file-excel" aria-hidden="true"></i>&nbsp; Exportar</a>
											</div>
										</div>

										<div class="push50"></div>

									</div>					
					
								</div>
							<!-- fim Portlet -->
							</div>
						
						</div>

					</div>

					<?php } ?>			
						
					<div class="push20"></div>
					
					
	<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
	<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />
	<script src="https://unpkg.com/minibarjs@latest/dist/minibar.min.js" type="text/javascript"></script>				

	<script src="js/gauge.coffee.js" type="text/javascript"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.min.js"></script> 
	<script src="js/pie-chart.js"></script>
    <script src="js/plugins/Chart_Js/utils.js"></script>
	
	<?php
		if($log_labels == 'S'){
	?>
			<!-- Script dos labels -->
			<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.5.0"></script>

	<?php
		}
	?>
    	
    <script>
    	<?php
			if($log_labels == 'S'){
		?>
		Chart.plugins.unregister(ChartDataLabels);
		<?php
			}
		?>
		//datas
		$(function () {
			
			var cod_empresa = "<?=$cod_empresa?>";

			$('.datepicker').datetimepicker({
				format: 'DD/MM/YYYY',
				minDate:'<?=fnDataSql($dat_ref_min)?>',
				maxDate:'<?=fnDataSql($dat_ref_max)?>',
				useCurrent: false,
				viewMode: 'years'
			}).on('changeDate', function(e){
				$(this).datetimepicker('hide');
			});

			// $('#DAT_FIM_GRP').datetimepicker({
			// 	format: 'DD/MM/YYYY',
			// 	maxDate:'<?=fnDataSql($dat_ref_max)?>',
			// 	useCurrent: false,
			// 	viewMode: 'years'
			// }).on('changeDate', function(e){
			// 	$(this).datetimepicker('hide');
			// });

			
			$("#DAT_INI_GRP").on("dp.change", function (e) {
				$('#DAT_FIM_GRP').data("DateTimePicker").minDate(e.date);
			});
			
			$("#DAT_FIM_GRP").on("dp.change", function (e) {
				$('#DAT_INI_GRP').data("DateTimePicker").maxDate(e.date);
			});		

		});

		<?php if($dat_ini != ""){ ?>
		
		//graficos
        $(document).ready( function() {

        	$("#QTD_TRANSACOES").text("<?=fnValor($qtd_transacoes,0)?>");
			$("#VL_FATURAMENTO").text("<?=fnValor($vl_faturamento,2)?>");
			$("#TICKET_MEDIO").text("<?=fnValor($ticket_medio,2)?>");

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
											url: "relatorios/ajxDashBaseHistorica.do?opcao=exportar&nomeRel="+nome+"&id=<?php echo fnEncode($cod_empresa); ?>",
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
			
			
            MiniBarOptions = {
			    barType: "default",
			    minBarSize: 10,
			    hideBars: false,  /* v0.4.0 and above */
			    alwaysShowBars: true,
			    horizontalMouseScroll: false,

			    scrollX: true,
			    scrollY: true,

			    navButtons: false,
			    scrollAmount: 10,

			    mutationObserver: {
			        attributes: false,
			        childList: true,
			        subtree: true
			    },

			     /* v0.4.0 and above */
			    onInit: function() {
			    	
			    },

			     /* v0.4.0 and above */
			    onUpdate: function() {
			    /* do something on update */
			    },

			     /* v0.4.0 and above */
			    onScroll: function() {
			    /* do something on init */
			    },

			    classes: {
			        container: "mb-container",
			        content: "mb-content",
			        track: "mb-track",
			        bar: "mb-bar",
			        visible: "mb-visible",
			        progress: "mb-progress",
			        hover: "mb-hover",
			        scrolling: "mb-scrolling",
			        textarea: "mb-textarea",
			        wrapper: "mb-wrapper",
			        nav: "mb-nav",
			        btn: "mb-button",
			        btns: "mb-buttons",
			        increase: "mb-increase",
			        decrease: "mb-decrease",
			        item: "mb-item", /* v0.4.0 and above */
			        itemVisible: "mb-item-visible", /* v0.4.0 and above */
			        itemPartial: "mb-item-partial", /* v0.4.0 and above */
			        itemHidden: "mb-item-hidden" /* v0.4.0 and above */
			    }
			};

			new MiniBar('.slimscroll', MiniBarOptions);
			
			//chosen
			$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
			$('#formulario').validator();

			//grouped
			var barchartgrouped = new Chart(document.getElementById("bar-chart-grouped"), {
				type: 'bar',
				data: {
				  labels: [
				  			["-17","<?=fnValor($pct_idade0,0)?>%"],
				  			["18-20","<?=fnValor($pct_idade1,0)?>%"],
				  			["21-30","<?=fnValor($pct_idade2,0)?>%"],
				  			["31-40","<?=fnValor($pct_idade3,0)?>%"],
				  			["41-50","<?=fnValor($pct_idade4,0)?>%"],
				  			["51-60","<?=fnValor($pct_idade5,0)?>%"],
				  			["61-70","<?=fnValor($pct_idade6,0)?>%"],
				  			["71-80","<?=fnValor($pct_idade7,0)?>%"],
				  			["+81","<?=fnValor($pct_idade8,0)?>%"]
				  		  ],
				  datasets: [
					{
					  <?php if($log_labels == 'S'){ ?>
					  	datalabels: {
							clamp: true,
							align: 'middle',
							anchor: 'end',
							borderRadius: 4,
							backgroundColor: '#75B1D9',
							color: '#fff',
							formatter: function(value) {
							    if(parseInt(value) >= 1000){
					                return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
					              } else {
					                return value;
					              }
							    // eq. return ['line1', 'line2', value]
							}
						},
					  <?php } ?>					  
					  backgroundColor: "#85C1E9",					 
					  data: [<?=$qtd_idade0?>,<?=$qtd_idade1?>, <?=$qtd_idade2?>, <?=$qtd_idade3?>, <?=$qtd_idade4?>, <?=$qtd_idade5?>, <?=$qtd_idade6?>, <?=$qtd_idade7?>, <?=$qtd_idade8?>]
					},
				  ]
				},
				<?php if($log_labels == 'S'){ ?>
				plugins: [ChartDataLabels],
				<?php } ?>
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
							$("input[name=barchartgrouped]").val(barchartgrouped.toBase64Image());
							
						}
					}
				}
			});

			//grouped
			var barchartgrouped2 = new Chart(document.getElementById("bar-chart-grouped-2"), {
				type: 'bar',
				data: {
				  labels: ["E-mails", "Celulares", "Dt. Nascimento", "CEP", "Endereços"],
				  datasets: [{
				  	<?php if($log_labels == 'S'){ ?>
					  	datalabels: {
							clamp: true,
							align: 'middle',
							anchor: 'end',
							borderRadius: 4,
							backgroundColor: '#d5d5d5',
							color: '#fff',
							formatter: function(value) {
							    if(parseInt(value) >= 1000){
					                return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
					              } else {
					                return value.toFixed(0)+"%";
					              }
							    // eq. return ['line1', 'line2', value]
							}
						},
					  <?php } ?>
					  backgroundColor: window.chartColors.cyan,
					  data: [<?=$pct_email?>, <?=$pct_celular?>, <?=$pct_nascimento?>, <?=$pct_cep?>, <?=$pct_endereco?>]
					}
				  ]
				},
				<?php if($log_labels == 'S'){ ?>
				plugins: [ChartDataLabels],
				<?php } ?>
				options: {
				   legend: {
			            display: false
			         },
				   tooltips: {
				      callbacks: {
				         label: function (t, d) {
					        return t.yLabel.toFixed(0) + "%"
					  }
					}
				   },
				  scales: {						
						yAxes: [{
							ticks: {
			            		min: 0,
			            		stepSize: 20,
			            		callback: function(value, index, values) {
					              if(parseInt(value) >= 1000){
					                return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")+'%';
					              } else {
					                return value+'%';
					              }
					            }
			                }													
						}]					
					},
					animation: {
						animateScale: true,
						animateRotate: true,
						onComplete : function(){   
							$("input[name=barchartgrouped2]").val(barchartgrouped2.toBase64Image());
							
						}
					}
				}
			});
	
			//donut 
			var config = {
				<?php if($log_labels == 'S'){ ?>
				plugins: [ChartDataLabels],
				<?php } ?>
				type: 'doughnut',
				data: {
					datasets: [{
						<?php if($log_labels == 'S'){ ?>
					  	datalabels: {
							clamp: true,
							align: 'middle',
							anchor: 'end',
							borderRadius: 4,
							backgroundColor: [
							'#3BB0B0',
							'#2692DB'
							],
							color: '#fff',
							formatter: function(value) {
							    if(parseInt(value) >= 1000){
					                return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
					              } else {
					                return value;
					              }
							    // eq. return ['line1', 'line2', value]
							}
						},
						<?php } ?>
						data: [
							"<?=$qtd_feminino?>",
							"<?=$qtd_masculino?>",
							// <?=($qtd_masculino+$qtd_feminino)?>,
						],
						backgroundColor: [
							window.chartColors.green,
							window.chartColors.blue,
							// "#E5E5E5",
						],
						label: 'Dataset 1'
					}],
					labels: [
						"Mulheres - <?=fnValor($pct_feminino,0)?>%",
						"Homens - <?=fnValor($pct_masculino,0)?>%",
						// "Indefinidos"
					]
				},
				options: {
					tooltips: {
				      callbacks: {
				        title: function(tooltipItem, data) {
				          return data['labels'][tooltipItem[0]['index']].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
				        },
				        label: function(tooltipItem, data) {
				          return data['datasets'][0]['data'][tooltipItem['index']].toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
				        },
				      }
				    },
					//rotation: 1 * Math.PI,
					//circumference: 1 * Math.PI,
					responsive: true,
					legend: {
						position: 'bottom',
					},
					// title: {
					// 	display: true,
					// 	text: 'Chart.js Doughnut Chart'
					// },
					animation: {
						animateScale: true,
						animateRotate: true,
						onComplete : function(){    
							$("input[name=chartarea]").val(myDoughnut.toBase64Image());
							
						}
					}
				}
			};

			var ctx = document.getElementById("chart-area").getContext("2d");
    		var myDoughnut = new Chart(ctx,config);
			
        });

		<?php } ?>

	</script>