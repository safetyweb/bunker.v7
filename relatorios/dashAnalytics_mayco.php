<?php
//https://adm.bunker.mk/action.do?mod=VU6Q8bfsZp%C2%A30%C2%A2&id=GLtHxidZjko%C2%A2

	setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
	date_default_timezone_set('America/Sao_Paulo');
	
//	 echo fnDebug('true');

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
	
	//inicialização das variáveis - default	
	if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31" ){
		$dat_ini = fnDataSql($dias30); 
	} 
	if (strlen($dat_fim ) == 0 || $dat_fim == "1969-12-31"){
		$dat_fim = fnDataSql($hoje); 
	}	
	
	//faz pesquisa por revenda (geral)
	// if ($cod_univend == "9999"){$temUnivend = "N";} else {$temUnivend = "S";}

	// fnEscreve($dt_filtro);
	
	//busca revendas do usuário
	include "unidadesAutorizadas.php";

	// Filtro por Grupo de Lojas
	include "filtroGrupoLojas.php";

	$sqlPeriodos = "SELECT DISTINCT MESANO FROM TB_FECHAMENTO_CLIENTE where COD_EMPRESA = $cod_empresa order by MESANO desc ";
	$arrayQueryPeriodos = mysqli_query(connTemp($cod_empresa,""),trim($sqlPeriodos));

	$qtd_periodos = mysqli_num_rows($arrayQueryPeriodos);

	if($qtd_periodos == 0){
		$msgTipo = "alert-danger";
		$msgRetorno = "Você ainda <b>não possui</b> a configuração para utilizar essa tela. <br/> Entre em <b>contato</b> com o seu <b>consultor</b>.";
	}


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
																<label for="inputName" class="control-label">Período </label>
																	<select data-placeholder="Selecione o período" name="DT_FILTRO" id="DT_FILTRO" class="chosen-select-deselect">
																		<option value=""></option>					
																		<?php
																		
																			while ($qrListaFiltro = mysqli_fetch_assoc($arrayQueryPeriodos))
																				//$data = $qrListaFiltro['DT_FILTRO'];
																				//$partes = explode("-", $data);
																				//$dia = $partes[0];
																				//$mes = $partes[1];
																				//$ano = $partes[2];

																				
																			  {														
																				echo"
																					  <option value='".fnmesanosql($qrListaFiltro['MESANO']."-01")."'>".date("m/Y",strtotime($qrListaFiltro['MESANO']."-01"))." ".$ano."</option> 
																					"; 
																				  }											
																		?>	
																	</select>
	                                                                <script>$("#formulario #DT_FILTRO").val("<?php echo $dt_filtro; ?>").trigger("chosen:updated"); </script>                                                       
																<div class="help-block with-errors"></div>
															</div>
														</div>	
														
														<div class="col-md-2">
															<div class="push20"></div>
															<button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-sm btn-block getBtn"><i class="fa fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
														</div>
																					
													</div>
													
											</fieldset>

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
	if($qtd_periodos > 0){ 

											if($dt_filtro == ""){
												$sql = "SELECT MAX(MESANO) AS DT_FILTRO FROM TB_FECHAMENTO_CLIENTE where COD_EMPRESA = $cod_empresa";
												// $sql = "SELECT MAX(DT_FILTRO) AS DT_FILTRO FROM filtro_frequencia where COD_EMPRESA = $cod_empresa";
												$qrDt = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,""),trim($sql)));
												$dt_filtro = fnmesanosql($qrDt['DT_FILTRO']);
												$dt_exibe = $qrDt['DT_FILTRO']."-01";
												$mes0 = ucfirst(strftime('%B', strtotime($dt_exibe." -6 months")));
												$mes1 = ucfirst(strftime('%B', strtotime($dt_exibe." -5 months")));
												$mes2 = ucfirst(strftime('%B', strtotime($dt_exibe." -4 months")));
												$mes3 = ucfirst(strftime('%B', strtotime($dt_exibe." -3 months")));
												$mes4 = ucfirst(strftime('%B', strtotime($dt_exibe." -2 months")));
												$mes5 = ucfirst(strftime('%B', strtotime($dt_exibe." -1 months")));
												$mes6 = ucfirst(strftime('%B', strtotime($dt_exibe)));
												
												$mesAniv = ucfirst(strftime('%B', strtotime($dt_exibe." +1 months")));
												// fnEscreve($dt_exibe);
												// fnEscreve($mes);
											}else{
												$dt_filtro = fnmesanosql($dt_filtro);
												$dt_exibe = $dt_filtro."-01";
												$mes0 = ucfirst(strftime('%B', strtotime($dt_exibe." -6 months")));
												$mes1 = ucfirst(strftime('%B', strtotime($dt_exibe." -5 months")));
												$mes2 = ucfirst(strftime('%B', strtotime($dt_exibe." -4 months")));
												$mes3 = ucfirst(strftime('%B', strtotime($dt_exibe." -3 months")));
												$mes4 = ucfirst(strftime('%B', strtotime($dt_exibe." -2 months")));
												$mes5 = ucfirst(strftime('%B', strtotime($dt_exibe." -1 months")));												
												$mes6 = ucfirst(strftime('%B', strtotime($dt_exibe)));
												
												$mesAniv = ucfirst(strftime('%B', strtotime($dt_exibe." +1 months")));
											}
											
											//$mesIni = '2019-08';
											$mesFim = '2019-09';
											$sql = "CALL SP_RELAT_FECHAMENTO_CLIENTE('$dt_filtro','$lojasSelecionadas', $cod_empresa )";
											 //fnEscreve($sql);
											
											$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
											$qrAnalitics = mysqli_fetch_assoc($arrayQuery);
											
											$mes_ano = $qrAnalitics['MES_ANO'];
											$qtd_clientes = $qrAnalitics['QTD_CLIENTES'];
											$qtd_clientes_novos = $qrAnalitics['QTD_CLIENTES_NOVOS'];
											$qtd_masculino = $qrAnalitics['QTD_MASCULINO'];
											$qtd_feminino = $qrAnalitics['QTD_FEMININO'];
											$pct_masculino = $qrAnalitics['PCT_MASCULINO'];
											$pct_feminino = $qrAnalitics['PCT_FEMININO'];											
											
											$idade_media = $qrAnalitics['IDADE_MEDIA'];
											$qtd_idade1 = $qrAnalitics['QTD_IDADE1'];
											$qtd_idade2 = $qrAnalitics['QTD_IDADE2'];
											$qtd_idade3 = $qrAnalitics['QTD_IDADE3'];
											$qtd_idade4 = $qrAnalitics['QTD_IDADE4'];
											$qtd_idade5 = $qrAnalitics['QTD_IDADE5'];
											$qtd_idade6 = $qrAnalitics['QTD_IDADE6'];
											$qtd_idade7 = $qrAnalitics['QTD_IDADE7'];
											
											$qtd_email = $qrAnalitics['QTD_EMAIL'];
											$pct_email = $qrAnalitics['PCT_EMAIL'];
											$qtd_celular = $qrAnalitics['QTD_CELULAR'];
											$pct_celular = $qrAnalitics['PCT_CELULAR'];
											$qtd_nascimento = $qrAnalitics['QTD_NASCIMENTO'];
											$pct_nascimento = $qrAnalitics['PCT_NASCIMENTO'];
											$qtd_cep = $qrAnalitics['QTD_CEP'];
											$pct_cep = $qrAnalitics['PCT_CEP'];
											$qtd_endereco = $qrAnalitics['QTD_ENDERECO'];
											$pct_endereco = $qrAnalitics['PCT_ENDERECO'];
											
											$vl_faturamento_fidelizado = $qrAnalitics['VL_FATURAMENTO_FIDELIZADO'];
											//$pct_faturamento_fidelizado = $qrAnalitics['PCT_FATURAMENTO_FIDELIZADO'];											
											$qtd_transacoes_fidelizado = $qrAnalitics['QTD_TRANSACOES_FIDELIZADO'];
											$qtd_transacoes = $qrAnalitics['QTD_TRANSACOES'];
											$qtd_transacoes_avulso = $qrAnalitics['QTD_TRANSACOES_AVULSO'];
											
											$ticket_medio_fidelizado = $qrAnalitics['TICKET_MEDIO_FIDELIZADO'];
											$ticket_medio_avulso = $qrAnalitics['TICKET_MEDIO_AVULSO'];

											$pct_fidelizado_anterior = $qrAnalitics['PCT_FIDELIZADO_ANTERIOR'];
											$qtd_inativos = $qrAnalitics['QTD_INATIVOS'];
											
											$qtd_aniversariantes = $qrAnalitics['QTD_ANIVERSARIANTES'];
											$vl_faturamento_aniver = $qrAnalitics['VL_FATURAMENTO_ANIVER'];
											
											$qtd_cli_expirar = $qrAnalitics['QTD_CLI_EXPIRAR'];
											$vl_faturamento_expirar = $qrAnalitics['VL_FATURAMENTO_EXPIRAR'];

											$tm_inativos = $qrAnalitics['TICKET_MEDIO_INATIVO'];
											$vl_gasto_acumulado_inativos = $qrAnalitics['VL_GASTO_ACUMULADO_INATIVOS'];

											$qtd_20_cli_faturamento = $qrAnalitics['QTD_20_CLI_FATURAMENTO'];
											$vl_total_concentracao_faturamento = $qrAnalitics['VL_TOTAL_CONCENTRACAO_FATURAMENTO'];
											$perc_20_concentracao_faturamento = $qrAnalitics['PERC_20_CONCENTRACAO_FATURAMENTO'];
											
											$vl_gm = $qrAnalitics['VL_GM'];
											$qtd_transacoes = $qrAnalitics['QTD_TRANSACOES'];

											$vl_total_resgate = $qrAnalitics['VL_TOTAL_RESGATE'];
											$qtd_cli_resgate = $qrAnalitics['QTD_CLI_RESGATE'];
											$perc_vl_resgate = $qrAnalitics['PERC_VL_RESGATE'];
											$qtd_cli_expirado = $qrAnalitics['QTD_CLI_EXPIRADO'];
											
											$vl_faturamento_expirado = $qrAnalitics['VL_FATURAMENTO_EXPIRADO'];

											$vl_faturamento_fidelizado_mes_ant = $qrAnalitics['VL_FATURAMENTO_FIDELIZADO_MES_ANT'];
											$perc_faturamento_fidelizado_mes_ant = $qrAnalitics['PERC_FIDELIZADO_ANTERIOR'];
											
											$pct_faturamento_fidelizado = (($vl_faturamento_fidelizado - $vl_faturamento_fidelizado_mes_ant) / $vl_faturamento_fidelizado_mes_ant) * 100;
											$pct_faturamento_ref = ($vl_faturamento_fidelizado / $qrAnalitics['VL_FATURAMENTO']) * 100;

											// fnEscreve($qrAnalitics['VL_FATURAMENTO_FIDELIZADO']);
											// fnEscreve($qrAnalitics['VL_FATURAMENTO']);
											// fnEscreve($pct_faturamento_ref);

											$qtd_transacoes_fidelizado_mes_ant = $qrAnalitics['QTD_TRANSACOES_FIDELIZADO_MES_ANT'];
											$qtd_transacoes_mes_ant = $qrAnalitics['QTD_TRANSACOES_MES_ANT'];
											$qtd_transacoes_avulso_mes_ant = $qrAnalitics['QTD_TRANSACOES_AVULSO_MES_ANT'];

											// 7 barras
											$qtd_clientes_compraram_mesm0 = $qrAnalitics['QTD_CLIENTES_COMPRARAM_MES'];
											$qtd_clientes_compraram_mesm1 = $qrAnalitics['QTD_CLIENTES_COMPRARAM_MESM1'];
											$qtd_clientes_compraram_mesm2 = $qrAnalitics['QTD_CLIENTES_COMPRARAM_MESM2'];
											$qtd_clientes_compraram_mesm3 = $qrAnalitics['QTD_CLIENTES_COMPRARAM_MESM3'];
											$qtd_clientes_compraram_mesm4 = $qrAnalitics['QTD_CLIENTES_COMPRARAM_MESM4'];
											$qtd_clientes_compraram_mesm5 = $qrAnalitics['QTD_CLIENTES_COMPRARAM_MESM5'];
											$qtd_clientes_compraram_mesm6 = $qrAnalitics['QTD_CLIENTES_COMPRARAM_MESM6'];
											
											$pct_20_cli_faturamento = $qrAnalitics['PCT_20_CLI_FATURAMENTO'];
											
																						
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
													
													<h5>Total de Clientes Cadastrados até <?=date("m/Y",strtotime($dt_exibe))?></h5>
													<div class="push20"></div>
													
													<div style="max-height: 200px; max-width:100%;">
														<canvas id="chart-area" style="height: 100%"></canvas>
													</div>												

													<div class="push50"></div>
													<div class="push20"></div>
													<h5>Base de Cadastros <b class="f21"><?=fnValor($qtd_clientes,0);?></b></h5>
													<!--<h5>Novos Cadastros <?=fnValor($qtd_clientes_novos,0);?></h5>-->
													
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

<!-- =========================================== -->

									<div class="row text-center">
										
											<div class="col-md-12 col-lg-12">
												<h4>Fidelização</h4>

												<?php

													if($vl_faturamento_fidelizado >= $vl_faturamento_fidelizado_mes_ant){
														$cor_seta_total = "text-info fal fa-arrow-up";
													}else{
														$cor_seta_total = "text-danger fal fa-arrow-down";
													}

													if($qtd_transacoes >= $qtd_transacoes_mes_ant){
														$cor_seta_transac = "text-info fal fa-arrow-up";
													}else{
														$cor_seta_transac = "text-danger fal fa-arrow-down";
													}

													if($qtd_transacoes_fidelizado >= $qtd_transacoes_fidelizado_mes_ant){
														$cor_seta_fid = "text-info fal fa-arrow-up";
													}else{
														$cor_seta_fid = "text-danger fal fa-arrow-down";
													}

													if($qtd_transacoes_avulso >= $qtd_transacoes_avulso_mes_ant){
														$cor_seta_av = "text-info fal fa-arrow-up";
													}else{
														$cor_seta_av = "text-danger fal fa-arrow-down";
													}

													if($qtd_clientes_compraram_mes0 >= $qtd_clientes_compraram_mesm6){
														$cor_seta_fid_ant = "text-info fal fa-arrow-up";
													}else{
														$cor_seta_fid_ant = "text-danger fal fa-arrow-down";
													}

													// if($vl_faturamento_fidelizado >= $vl_faturamento_fidelizado_mes_ant){
													// 	$cor_seta_av = "text-info fal fa-arrow-up";
													// }else{
													// 	$cor_seta_av = "text-danger fal fa-arrow-down";
													// }


												?>
											
												<div class="push20"></div>
												<div class="push20"></div>
												
												<div class="form-group text-center col-md-6 col-lg-4 col-sm-6">												
													
													<h5>Faturamento em <b><?=$mes?></b></h5>
													<div class="push20"></div>
													
														<h3><?=fnValor($pct_faturamento_fidelizado,2)?>% <i class="<?=$cor_seta_total?>" data-toggle='tooltip' data-placement='top' data-html="true" data-original-title='R$<?=fnValor($vl_faturamento_fidelizado_mes_ant,2)?> <br/> Ref. <?php echo $mes6; ?> '></i></h3>																												
														<p>Clientes fidelizados geraram <b>R$ <?=fnValor($vl_faturamento_fidelizado,2)?></b> de receita</p>								
														<p>Que correspondem a <b class="f21"><?=fnValor($pct_faturamento_ref,2)?>% </b>sobre o faturamento total</p>								
													
												</div>											
 
												<div class="form-group col-md-6 col-lg-4 col-sm-6">
													
													<h5>Transações em <b><?=$mes;?></b></h5>
													<div class="push20"></div>
													
													<div style="max-height: 200px; max-width:100%;">
														<div class="col-md-12 col-lg-12 text-center">
															<h3><?=fnValor($qtd_transacoes,0)?> <i class="<?=$cor_seta_transac?>" data-toggle='tooltip' data-placement='top' data-html="true" data-original-title='<?=fnValor($qtd_transacoes_mes_ant,0)?> <br/> Ref. <?=$mes6?>'></i>
															</h3>
															<p>total</p>
														</div>
														<div class="col-xs-6 col-md-6 col-lg-6 text-center">
															<h3><?=fnValor($qtd_transacoes_fidelizado,0)?> <i class="<?=$cor_seta_fid?>" data-toggle='tooltip' data-placement='top' data-html="true" data-original-title='<?=fnValor($qtd_transacoes_fidelizado_mes_ant,0)?> <br/> Ref. <?=$mes6?>'></i></h3>
															<p>Fidelizados</p>														
														</div>
														<div class="col-xs-6 col-md-6 col-lg-6 text-center">
															<h3><?=fnValor($qtd_transacoes_avulso,0)?> <i class="<?=$cor_seta_av?>" data-toggle='tooltip' data-placement='top' data-html="true" data-original-title='<?=fnValor($qtd_transacoes_avulso_mes_ant,0)?> <br/> Ref. <?=$mes6?>'></i></h3>
															<p>Avulsos</p>														
														</div>
													</div>													
													
												</div>

												<div class="form-group text-center col-md-12 col-lg-4 col-sm-12">
												
													<h5>Clientes <b>Únicos</b> Fidelizados que Compraram em <b><?=$mes?></b></h5>
													<div class="push10"></div>
													
														<h3><?=fnValor($qtd_clientes_compraram_mesm6,0)?>&nbsp;&nbsp;<small>(<?=fnValor($pct_fidelizado_anterior,2)?>%)</small></h3>
														<!-- <h3><?=fnValor($pct_fidelizado_anterior,2)?>% <i class="<?=$cor_seta_fid_ant?>" data-toggle='tooltip' data-placement='top' data-html="true" data-original-title='R$ <?=fnValor($vl_total_concentracao_faturamento,2)?>'></i></h3> -->
														<p>Clientes com compras em <?=$mes?> e compras nos meses anteriores</p>

													<div class="push10"></div>

													<div class="form-group text-center col-xs-8 col-xs-offset-2">
														
														<div style="max-width:100%;">
															<canvas id="bar-chart-grouped-4" style="max-height: 250px;"></canvas>
														</div>
			   
													</div>														
												
												</div>

												<div class="push100"></div>
												<div class="push20"></div>

												<div class="form-group text-center col-md-4 col-lg-4">												
												
													<h5>Ticket Médio</h5>
													<div class="push20"></div>
													
													<div style="max-height: 200px; max-width:100%;">
														<canvas id="bar-chart-grouped-3" style="height: 100%"></canvas>
													</div>												
												
												</div>

												<div class="form-group text-center col-md-4 col-lg-4">
												
													<h5>Índice de Fidelização</h5>
													<h5>Composição Transações</h5>
													<div class="push20"></div>
													
													<div style="max-height: 200px; max-width:100%;">
														<canvas id="chart-area2" style="height: 100%"></canvas>
													</div>		   

												</div>

												<div class="form-group text-center col-md-4 col-lg-4">
												
													<h5>Clientes Sem Compras nos <b>Últimos 6 Meses</b></h5>
													<div class="push20"></div>
													
													<div style="max-height: 200px; max-width:100%;">													
														<div class="col-md-6 col-lg-6">
															<h3 style="top-margin: 0;"><?=fnValor($qtd_inativos)?></h3>
															<p>Clientes inativos</p>
														</div>														
														<div class="col-md-6 col-lg-6 text-left">														
															<h4>R$ <?=fnValor($vl_gasto_acumulado_inativos,2)?></h4>
															<p>Gasto acumulado <br><small>12 meses anteriores</small></p>
															
															<div class="push10"></div>
															
															<h4>R$ <?=fnValor($tm_inativos,2)?></h4>
															<p>Ticket médio</p>
														</div>
													</div>

												</div>
												
												<div class="push100"></div>												
												
											</div>										

									</div>

								</div>

							</div>
							<!-- fim portable -->

						</div>

					</div>
			

					<div class="row">				
					
						<div class="col-md-12 col-lg-12 margin-bottom-30">
							<!-- Portlet -->
							<div class="portlet portlet-bordered">
							
								<div class="portlet-body">		


									<div class="row text-center">
										
											<div class="col-md-12 col-lg-12">
												<h4>Relacionamento</h4>
												<div class="push20"></div>
												<div class="push20"></div>
																					

											<div class="form-group text-center col-md-3 col-lg-3">
												
												<h5>Aniversariantes de <b><?=$mesAniv?></b></h5>
												<div class="push20"></div>
												
												<div style="max-height: 200px; max-width:100%;">
												<div class="col-md-3 col-lg-3">
													<i class="fal fa-birthday-cake fa-4x"style="color: violet;"></i>
												</div>													
													<div class="col-md-9 col-lg-9">														
														<h5><b><?=fnValor($qtd_aniversariantes,0)?></b> clientes</h5>
														<h5><b>Aniversariantes</b></h5>
														<div class="push10"></div>
														<p>Já compraram <br/><b>R$ <?=fnValor($vl_faturamento_aniver,2)?></b> <br/> <small>Nos últimos 12 meses</small> </p>
													</div>
												</div>												
												
											</div>

											<div class="form-group text-center col-md-3 col-lg-3">
												
												<h5>Créditos a Expirar em <b><?=$mesAniv?></b></h5>
												<div class="push20"></div>
												
												<div style="max-height: 200px; max-width:100%;">
												<div class="col-md-3 col-lg-3">
													<i class="fal fa-flag fa-3x" style="color: blue;"></i>
												</div>													
													<div class="col-md-9 col-lg-9">														
														<h5><b><?=fnValor($qtd_cli_expirar,0)?></b> CLIENTES</h5>
														<h5><b>COM CRÉDITOS A EXPIRAR</b></h5>
														<div class="push10"></div>
														<p>Já compraram <br/><b>R$ <?=fnValor($vl_faturamento_expirar,2)?></b> <br/> <small>Nos últimos 12 meses</small> </p>
													</div>
												</div>

											</div>

											<div class="form-group text-center col-md-6 col-lg-6">
												
												<h5>Concentração de Faturamento</h5>												
												<div class="push20"></div>
												
												<div class="form-group text-center col-md-6 col-lg-6">
													
													<div style="max-height: 200px; max-width:100%;">
														<div class="col-md-3 col-lg-3">
															<i class="fal fa-chart-pie fa-3x" style="color: red;"></i>
														</div>													
														<div class="col-md-9 col-lg-9">														
															<p><b>20%</b> dos clientes mais rentáveis</p>	
															<p>atendidos em <b><?=$mes?></b></p>														
															<p>correspondem a <b><?=fnValor($qtd_20_cli_faturamento,0)?></b> clientes</p>														
														</div>
													</div>													
													
												</div>

												<div class="form-group text-center col-md-6 col-lg-6">
																										
													<div class="push20"></div>
													
													<div style="max-height: 200px; max-width:100%;">
														<div class="col-md-3 col-lg-3">
															<i class="fal fa-arrow-right fa-3x"style="color: green;"></i>
														</div>													
														<div class="col-md-9 col-lg-9">
															<p>que correspondem a <b><?=fnValor($pct_20_cli_faturamento,2)?>%</b> do faturamento do mês</p>
															<!-- <p>Estes <b>20%</b> de clientes concentram</p>
															<p><b>45,33%</b> do seu faturamento em <?=$mes?></p> -->														
														</div>
													</div>										
													
												</div>
							
											</div>

											<div class="push50"></div>

											<div class="form-group text-center col-md-6 col-lg-6">
												
												<div style="max-height: 200px; max-width:100%;">
													<h4>Top 5 Clientes de <b><?=$mes?></b></h4>
													<div class="push20"></div>
													<div class="push20"></div>
													
													<table class="table table-striped">

														<thead>
													    	<tr>													      
																<th scope="col">NOME</th>
																<th scope="col">CARTÃO</th>
																<th scope="col">VALOR (R$)</th>
																<th scope="col">QTD. COMPRAS</th>
													    	</tr>
														</thead>

														<tbody>

															<?php

																$sql2 = "CALL SP_RELAT_FECHAMENTO_TOP5CLIENTE('$mesFim','$lojasSelecionadas', $cod_empresa )";
																//fnEscreve($sql2);
																
																$arrayQuery2 = mysqli_query(connTemp($cod_empresa,''),$sql2) or die(mysqli_error());
																while($qrAnalitics2 = mysqli_fetch_assoc($arrayQuery2)){
															?>

																<tr>
																	<td><?=$qrAnalitics2['NOM_CLIENTE']?></td>
																	<td><?=$qrAnalitics2['CARTAO']?></td>
																	<td><?=fnValor($qrAnalitics2['VALOR'],2)?></td>
																	<td><?=fnValor($qrAnalitics2['COMPRAS'],0)?></td>
														  		</tr>

															<?php
																}

															?>

														</tbody>

													</table>
												</div>

											</div>

											<div class="form-group text-center col-md-6 col-lg-6">
												
												<div style="max-height: 200px; max-width:100%;">
													<h4>Resgate de Créditos de <b><?=$mes?> </h4>
													<div class="push20"></div>
													<div class="push20"></div>
												</div>

												<?php

															// fnEscreve($vl_total_resgate);
															// fnEscreve($qtd_cli_resgate);
															// fnEscreve($perc_vl_resgate);

														?>

												<div class="row">

													<div class="col-md-5">
														<h4><?=fnValor($qtd_cli_resgate,0)?></h4>
														<p><b>Clientes que realizaram resgate</b></p>
													</div>

													<div class="col-md-2 text-center">
														<div class="push20"></div>
														<i class="fal fa-arrow-right fa-3x text-success"></i>
													</div>

													<div class="col-md-5">
														<h4>R$ <?=fnValor($vl_total_resgate,2)?></h4>
														<p><b>Valor total de resgate</b></p>
													</div>

												</div>

												<div class="push10"></div>
												
												<div class="row">
												
													<div class="col-md-5">
														<h4><?=fnValor($qtd_cli_expirado,0)?></h4>
														<p><b>Clientes com Créditos Expirados</b></p>
													</div>

													<div class="col-md-2 text-center">
														<div class="push20"></div>
														<i class="fal fa-arrow-right fa-3x text-danger"></i>
													</div>

													<div class="col-md-5">
														<h4>R$ <?=fnValor($vl_faturamento_expirado,2)?></h4>
														<p><b>Valor dos créditos expirados</b></p>
													</div>
													
												</div>

												<div class="push10"></div>

												<div class="row">
													
													<div class="col-md-5">
														<h4><?=fnValor($perc_vl_resgate,2)?>%</h4>
														<p><b>Valor vinculado ao resgate</b></p>
													</div>

												</div>
												
											</div>

											<div class="push100"></div>
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

								
										<div class="form-group text-center col-md-12 col-lg-12">
																							
											<h4>Índice de Lucratividade por Perfil de Clientes</h4>
											<div class="push20"></div>												
													
											<?php

												$dt_filtro_ini = $dt_filtro."-01";
												$dt_filtro_fim = date("Y-m-t", strtotime($dt_filtro));

												$sql = "SELECT COD_FILTRO FROM FILTRO_FREQUENCIA WHERE COD_EMPRESA = $cod_empresa AND DT_FILTRO BETWEEN '$dt_filtro_ini' AND '$dt_filtro_fim'";

												$qrSpan = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));

												if($qrSpan['COD_FILTRO'] != ""){
													
												$cod_filtro = $qrSpan['COD_FILTRO'];	
													
												//busca dados do filtro
												$sql = "SELECT COD_FILTRO , QTD_DIASHIST , QTD_MESCLASS , DT_FILTRO FROM filtro_frequencia where COD_EMPRESA = $cod_empresa AND COD_FILTRO = $cod_filtro ";
												//fnEscreve($sql);
												$arrayQuery = mysqli_query(connTemp($cod_empresa,""),trim($sql)) or die(mysqli_error());
												$qrBuscaFiltro = mysqli_fetch_assoc($arrayQuery);
												
													$cod_filtro = $qrBuscaFiltro['COD_FILTRO'];
													$qtd_diashist = $qrBuscaFiltro['QTD_DIASHIST'];
													$qtd_mesclass = $qrBuscaFiltro['QTD_MESCLASS'];
													$dt_filtro = $qrBuscaFiltro['DT_FILTRO'];
													
													$mes = date("m",strtotime($dt_filtro));;      // Mês desejado, pode ser por ser obtido por POST, GET, etc.
													$ano = date("Y",strtotime($dt_filtro)); // Ano atual
													$ultimo_dia = date("t", mktime(0,0,0,$mes,'01',$ano)); // Mágica, plim!
													$ultima_data = $ultimo_dia."/".$mes."/".$ano;
													
													switch ($qtd_mesclass) {
														case 12: 
															$classifica = "Anual";
															break;    
														case 6: 
															$classifica = "Semestral";
															break;    
														case 4: 
															$classifica = "Quadrimestral";
															break;    
														case 3: 
															$classifica = "Trimestral";
															break;    
														case 2: 
															$classifica = "Bimestral";
															break;    
														case 1: 
															$classifica = "Mensal";
															break;    
														case 0: 
															$classifica = "Online (a cada venda)";
															break;    
													}

												$dataConsulta = substr($dt_filtro,0,4)."-".substr($dt_filtro,5,2);
												//fnEscreve($dataConsulta);
												$sql = "CALL SP_RELAT_LUCRO_FREQUENCIA_UNIVEND('$lojasSelecionadas', $cod_empresa, $qrSpan[COD_FILTRO], '$dataConsulta' )";
												//fnEscreve($sql);
												$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
												
												$count = 0;
												$bar = [];
												$cliente = [];
												$gm = [];
												$im = [];
												$nomeFaixa = [];
												$resgate_total = [];
												$vvr = [];
												$resgTot = 0;

												while($qrFunil = mysqli_fetch_assoc($arrayQuery)){

													$bar[$count] = fnValor($qrFunil['PERC_QTD_CLIENTES'],0);
													$faixa[$count] = $qrFunil['DESCRICAO_FAIXA'];
													$cliente[$count] = $qrFunil['QTD_CLIENTES'];
													$gm[$count] = $qrFunil['GM'];
													$im[$count] = $qrFunil['MEDIA_IDADE'];
													$resgate_total[$count] = $qrFunil['VL_RESGATE_TOTAL'];
													$vvr[$count] = $qrFunil['PERC_VVR'];
													$vvr_medio[$count] = $qrFunil['VVR_MEDIO'];
													$qtd_resgate[$count] = $qrFunil['QTD_RESGATE'];
													$media_vr[$count] = $qrFunil['VL_RESGATE_MEDIO'];
													$media_vvr[$count] = $qrFunil['VVR_MEDIO'];
													$resgTot += $qrFunil['VL_RESGATE_TOTAL'];
													//fnEscreve($vvr[$count]);

													$count++;

												}

												$resg1 = ($resgate_total[0]/$resgTot)*100;
												$resg2 = ($resgate_total[1]/$resgTot)*100;
												$resg3 = ($resgate_total[2]/$resgTot)*100;
												$resg4 = ($resgate_total[3]/$resgTot)*100;
												$resg5 = $resgate_total[4];
											
												$freq1 = $gm[0]/$gm[0];
												$freq2 = $gm[1]/$gm[0];
												$freq3 = $gm[2]/$gm[0];
												$freq4 = $gm[3]/$gm[0];	
												$freq5 = $gm[4]/$gm[0];											
																						
												$bar2Calc = 70;
												$bar3Calc = 55;
												$bar4Calc = 35;
												
											?>	
											
												<div class="push50"></div>
												
												<table class="table table-striped">
												  <thead>
													<tr>
													  <!--<th></th>-->
													  
													  <th class="text-center f18" colspan="2">CONCENTRAÇÃO DE CLIENTES</th>
													  <th class="text-center f18">TIPO DE CLIENTE</th>
													  <th class="text-center f18">RENTABILIDADE</th>
													  <th class="text-center f18">TAXA DE RENTABILIDADE</th>
													  <th></th>
													</tr>
												  </thead>
												  <tbody>
												  
													<tr>
													
													  <td style="width: 50px;"></td>
													 
													  <td class="text-center">
														<div class="push30"></div>
														<div class="bar cor1" style="width: -webkit-calc(100%);"><span><?=$bar[0]?>%</span>&nbsp; <?=fnValor($cliente[0],0); ?> </div>
													  </td>
													 
													  <td class="text-center">
														  <div class="push10"></div>
														  <?php
															$compara1 = (round($freq2-1));
															$qtd_compras1 = round($compara1);
															if ($freq1 >= $compara1){
															  $txt_compras1 = "1 compra no período";														  
															}else{
															  $txt_compras1 = round($freq1)." a ".round($compara1)." compras no período";	
															} 
															for ($i=0; $i < round($freq1); $i++) {
																echo "<i class='fas fa-male fa-2x fCor1' style='margin: 0 3px 0 0;'></i>";
															}													  
														  ?>
														  <div class="push5"></div>
														  <span class="f18 fCor1"><b><?php echo $faixa[0]; ?></b></span>
														  <div class="push3"></div>
														  <span class="f12 fCor1"><small><?=fnValor($im[0],0)?> anos </small></span>
														  <div class="push3"></div>
														  <span class="f13 fCor1"><b><?php echo $txt_compras1; ?></b></span>
													  </td>
													  
													  <td class="text-center">
														  <div class="push30"></div>
														  <span class="f26b fCor1"><b>R$ <?php echo fnValor($gm[0],2); ?></b></span>
													  </td>

													  <td class="text-center">
														  <div class="push30"></div>
														  <span class="f30 fCor1"><b><?=round($freq1)?>x</b></span>
													  </td>
													  
													</tr>
													
													<tr>
													
													  <td style="width: 50px;"></td>

													  <td class="text-center">
														<div class="push30"></div>
														<div class="bar cor2" style="width: -webkit-calc(80%);"><span><?=$bar[1]?>%</span>&nbsp;  <?=fnValor($cliente[1],0); ?> </div>
													  </td>
													  
													  <td class="text-center">
														  <div class="push10"></div>
														  <?php
															$compara2 = (round($freq3-1));
															$qtd_compras2 = round($compara2);
															if ($compara2 <= $freq2){
															  $txt_compras2 = round($freq2)." compras no período";
															}else{
															  $txt_compras2 = round($freq2)." a ".round($compara2)." compras no período";
															}
															for ($i=0; $i < round($freq2); $i++) {
																echo "<i class='fas fa-male fa-2x fCor2' style='margin: 0 3px 0 0;'></i>";
															}													  
														  ?>
														  <div class="push5"></div>
														  <span class="f18 fCor2"><b><?php echo $faixa[1]; ?></b></span>
														  <div class="push3"></div>
														  <span class="f12 fCor2"><small><?=fnValor($im[1],0)?> anos </small></span>
														  <div class="push3"></div>
														  <span class="f13 fCor2"><b><?php echo $txt_compras2; ?></b></span>
													  </td>
													  
													  <td class="text-center">
														  <div class="push30"></div>
														  <span class="f26b fCor2"><b>R$ <?php echo fnValor($gm[1],2); ?></b></span>
													  </td>
													  
													  <td class="text-center">
														  <div class="push30"></div>
														  <span class="f30 fCor2"><b> <?=round($freq2)?>x </b></span>
													  </td>
													  
													</tr>
													
													<tr>
													
												      <td style="width: 50px;"></td>
													  
													  <td class="text-center">
														<div class="push30"></div>
														<div class="bar cor3" style="width: -webkit-calc(65%);"><span><?=$bar[2]?>%</span>&nbsp; <?=fnValor($cliente[2],0); ?></div>
													  </td>
													  
													  <td class="text-center">
														  <div class="push10"></div>
														  <?php 
															$compara3 = (round($freq4-1));
															$qtd_compras3 = round($compara3);
															if ($compara3 <= $freq3){ 
															  $txt_compras3 = round($freq3)." compras no período";
															}else{
															  $txt_compras3 = round($freq3)." a ".round($compara3)." compras no período";
															}
															for ($i=0; $i < round($freq3); $i++) {
																echo "<i class='fas fa-male fa-2x fCor3' style='margin: 0 3px 0 0;'></i>";
															}													  
														  ?>
														  <div class="push5"></div>
														  <span class="f18 fCor3"><b><?php echo $faixa[2]; ?></b></span>
														  <div class="push3"></div>
														  <span class="f12 fCor3"><small><?=fnValor($im[2],0)?> anos </small></span>
														  <div class="push3"></div>
														  <span class="f13 fCor3"><b><?php echo $txt_compras3; ?></b></span>
													  </td>
													  
													  <td class="text-center">
														  <div class="push30"></div>
														  <span class="f30 fCor3"><b>R$ <?php echo fnValor($gm[2],2); ?></b></span>
													  </td>
													  
													  <td class="text-center">
														  <div class="push30"></div>
														  <span class="f30 fCor3"><b><?=round($freq3)?>x</b></span>
													  </td>
													  
													</tr>
													
													<tr>
													
													  <td style="width: 50px;"></td>	

													  <td class="text-center">
														<div class="push30"></div>
														<div class="bar cor4" style="width: -webkit-calc(50%);"><span><?=$bar[3]?>%</span>&nbsp; <?=fnValor($cliente[3],0); ?></div>
													  </td>
													  
													  <td class="text-center">
														  <div class="push5"></div>
														  <?php 
															$compara4 = round($freq4);
															for ($i=0; $i < round($freq4); $i++) {
																echo "<i class='fas fa-male fa-2x fCor4' style='margin: 0 3px 0 0;'></i>";
															}													  
														  ?>
														  <div class="push5"></div>
														  <span class="f18 fCor4"><b><?php echo $faixa[3]; ?></b></span>
														  <div class="push3"></div>
														  <span class="f12 fCor4"><small><?=fnValor($im[3],0)?> anos </small></span>
														  <div class="push3"></div>
														  <span class="f13 fCor4"><b><?php echo round($freq4); ?> ou mais compras no período</b></span>
													  </td>
													  
													  <td class="text-center">
														  <div class="push30"></div>
														  <span class="f26b fCor4"><b>R$ <?php echo fnValor($gm[3],2); ?></b></span>
													  </td>
													  
													  <td class="text-center">
														  <div class="push30"></div>
														  <span class="f30 fCor4"><b><?=round($freq4)?>x</b></span>
													  </td>
													  
													</tr>
													
												  </tbody>
												</table>

												<div class="row">			
													
													<div class="form-group text-left col-lg-12">
													
														<h5>Dados do Ciclo de Recompra</h5>
													
													</div>
													
													<div class="col-md-2 text-center">
														Período: <b><?=fnDataShort($dt_filtro); ?> a <?=$ultima_data; ?></b>
													</div>

													<div class="col-md-2 text-center">
														Dias do Ciclo: <b><?=$qtd_diashist?></b>
													</div>	
													
													<div class="col-md-2 text-center">
														Atualização: <b><?=$classifica; ?></b>
													</div>
													
												</div>

												<div class="push50"></div>
												

											<?php
												} else{
											?>

												<div class="push50"></div>

												<div class="row">
													<div class="col-md-6 col-md-offset-3 text-center">
														<h5>Não há dados para o período de referência.</h5>
													</div>
												</div>

											<?php 
												} 
											?>

											</div>																								
												
										</div>
											
									</div>					
					
					
								</div>
							<!-- fim Portlet -->
							</div>
						
						</div>

					<!--
					
					<div class="row">				
					
						<div class="col-md-3 col-lg-3 margin-bottom-30">							
						
							<div class="portlet portlet-bordered">
							
								<div class="portlet-body">						
								
								<div class="push100"></div>								
								<div class="push100"></div>							
									
								</div>
							</div>
							
						</div>	

						
						<div class="col-md-3 col-lg-3 margin-bottom-30">							
						
							<div class="portlet portlet-bordered">
							
								<div class="portlet-body">						
								
								<div class="push100"></div>								
								<div class="push100"></div>							
									
								</div>
							</div>
							
						</div>

					</div>
					-->
							
					
					<div class="row">				
					
						<div class="col-md-12 margin-bottom-30">
							<!-- Portlet -->
							<div class="portlet portlet-bordered">
								<div class="portlet-body">
																
									<div class="login-form">
									
										<form target="_blank" data-toggle="validator" role="geraPDF" method="post" id="geraPDF" action="relatorios/pdfDashAnalytics.php">

											<input type="hidde" name="dt_exibe" value="<?=$dt_exibe?>">
											<input type="hidde" name="qtd_clientes" value="<?=$qtd_clientes?>">
											<input type="hidde" name="cod_empresa" value="<?=$cod_empresa?>">
											<input type="hidde" name="qtd_idade1" value="<?=$qtd_idade1?>">
											<input type="hidde" name="qtd_idade2" value="<?=$qtd_idade2?>">
											<input type="hidde" name="qtd_idade3" value="<?=$qtd_idade3?>">
											<input type="hidde" name="qtd_idade4" value="<?=$qtd_idade4?>">
											<input type="hidde" name="qtd_idade5" value="<?=$qtd_idade5?>">
											<input type="hidde" name="qtd_idade6" value="<?=$qtd_idade6?>">
											<input type="hidde" name="qtd_idade7" value="<?=$qtd_idade7?>">
											<input type="hidde" name="pct_email" value="<?=$pct_email?>">
											<input type="hidde" name="pct_celular" value="<?=$pct_celular?>">
											<input type="hidde" name="pct_nasciment0" value="<?=$pct_nascimento?>">
											<input type="hidde" name="pct_cep" value="<?=$pct_cep?>">
											<input type="hidde" name="pct_endereco" value="<?=$pct_endereco?>">
											<input type="hidde" name="ticket_medio_fidelizado" value="<?=$ticket_medio_fidelizado?>">
											<input type="hidde" name="ticket_medio_avulso" value="<?=$ticket_medio_avulso?>">
											<input type="hidde" name="mes0" value="<?=$mes0?>">
											<input type="hidde" name="mes1" value="<?=$mes1?>">
											<input type="hidde" name="mes2" value="<?=$mes2?>">
											<input type="hidde" name="mes3" value="<?=$mes3?>">
											<input type="hidde" name="mes4" value="<?=$mes4?>">
											<input type="hidde" name="mes5" value="<?=$mes5?>">
											<input type="hidde" name="mes6" value="<?=$mes6?>">
											<input type="hidde" name="qtd_clientes_compraram_mesm0" value="<?=$qtd_clientes_compraram_mesm0?>">
											<input type="hidde" name="qtd_clientes_compraram_mesm1" value="<?=$qtd_clientes_compraram_mesm1?>">
											<input type="hidde" name="qtd_clientes_compraram_mesm2" value="<?=$qtd_clientes_compraram_mesm2?>">
											<input type="hidde" name="qtd_clientes_compraram_mesm3" value="<?=$qtd_clientes_compraram_mesm3?>">
											<input type="hidde" name="qtd_clientes_compraram_mesm4" value="<?=$qtd_clientes_compraram_mesm4?>">
											<input type="hidde" name="qtd_clientes_compraram_mesm5" value="<?=$qtd_clientes_compraram_mesm5?>">
											<input type="hidde" name="qtd_feminino" value="<?=$qtd_feminino?>">
											<input type="hidde" name="qtd_masculino" value="<?=$qtd_masculino?>">
											<input type="hidde" name="qtd_transacoes_fidelizado" value="<?=$qtd_transacoes_fidelizado?>">
											<input type="hidde" name="qtd_transacoes_avulso" value="<?=$qtd_transacoes_avulso?>">
											<input type="hidde" name="listaFatLmp" value="<?=$listaFatLmp?>">
											<input type="hidde" name="listaFatAv" value="<?=$listaFatAv?>">
											<input type="hidde" name="listaFatTotRes" value="<?=$listaFatTotRes?>">
											<input type="hidde" name="listaFatFid" value="<?=$listaFatFid?>">
											<input type="hidde" name="listaPctFatAv" value="<?=$listaPctFatAv?>">
											<input type="hidde" name="listaFatRes" value="<?=$listaFatRes?>">
											<input type="hidde" name="mes" value="<?=$mes?>">
											<input type="hidde" name="mes_nome" value="<?=$mes_nome?>">
											<input type="hidde" name="pct_faturamento_fidelizado" value="<?=$pct_faturamento_fidelizado?>">
											<input type="hidde" name="vl_faturamento_fidelizado_mes_ant" value="<?=$vl_faturamento_fidelizado_mes_ant?>">
											<input type="hidde" name="vl_faturamento_fidelizado" value="<?=$vl_faturamento_fidelizado?>">
											<input type="hidde" name="pct_faturamento_ref" value="<?=$pct_faturamento_ref?>">
											<input type="hidde" name="cor_seta_total" value="<?=$cor_seta_total?>">
											<input type="hidde" name="cor_seta_transac" value="<?=$cor_seta_transac?>">
											<input type="hidde" name="cor_seta_fid" value="<?=$cor_seta_fid?>">
											<input type="hidde" name="cor_seta_av" value="<?=$cor_seta_av?>">
											<input type="hidde" name="qtd_transacoes" value="<?=$qtd_transacoes?>">
											<input type="hidde" name="qtd_transacoes_mes_ant" value="<?=$qtd_transacoes_mes_ant?>">
											<input type="hidde" name="qtd_transacoes_fidelizado" value="<?=$qtd_transacoes_fidelizado?>">
											<input type="hidde" name="qtd_transacoes_fidelizado_mes_ant" value="<?=$qtd_transacoes_fidelizado_mes_ant?>">
											<input type="hidde" name="qtd_transacoes_avulso" value="<?=$qtd_transacoes_avulso?>">
											<input type="hidde" name="qtd_transacoes_avulso_mes_ant" value="<?=$qtd_transacoes_avulso_mes_ant?>">
											<input type="hidde" name="qtd_clientes_compraram_mesm6" value="<?=$qtd_clientes_compraram_mesm6?>">
											<input type="hidde" name="pct_fidelizado_anterior" value="<?=$pct_fidelizado_anterior?>">
											
											<input type="hidde" name="chartarea" value="">
											<input type="hidde" name="barchartgrouped" value="">

											<div class="col-md-2">
												<div class="push20"></div>
												<button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-sm btn-block getBtn">
													<i class="fa fa-filter" aria-hidden="true"></i>&nbsp; Gerar PDF
												</button>
											</div>

											<input type="hidden" name="opcao" id="opcao" value="">
											<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
											<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

										</form>
									
									</div>
								
								</div>
							</div>
						</div>
					</div>
						
					<div class="push20"></div>
					
					
	<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
	<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />				

	<script src="js/gauge.coffee.js" type="text/javascript"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.min.js"></script> 
	<script src="js/pie-chart.js"></script>
    <script src="js/plugins/Chart_Js/utils.js"></script>
	<script type="text/javascript" src="js/plugins/jquery.sparkline.min.js"></script>
    	
    <script>
	
		//datas
		$(function () {
			
			var cod_empresa = "<?=$cod_empresa?>";

			if(cod_empresa == 77){

				$('.datePicker').datetimepicker({
					 format: 'DD/MM/YYYY',
					 maxDate : 'now',
					 minDate : '2018-12-31'
				}).on('changeDate', function(e){
					$(this).datetimepicker('hide');
				});

			}else{
			
				$('.datePicker').datetimepicker({
					 format: 'DD/MM/YYYY',
					 maxDate : 'now',
				}).on('changeDate', function(e){
					$(this).datetimepicker('hide');
				});

			}
			
			$("#DAT_INI_GRP").on("dp.change", function (e) {
				$('#DAT_FIM_GRP').data("DateTimePicker").minDate(e.date);
			});
			
			$("#DAT_FIM_GRP").on("dp.change", function (e) {
				$('#DAT_INI_GRP').data("DateTimePicker").maxDate(e.date);
			});
				

		});	
		
		//graficos
        $(document).ready( function() {
			
			
            // $('#demo-pie-1').pieChart({
            //     barColor: '#3bb2d0',
            //     trackColor: '#eee',
            //     lineCap: 'round',
            //     lineWidth: 8,
            //     onStep: function (from, to, percent) {
            //         $(this.element).find('.pie-value').text(Math.round(percent) + '%');
            //     }
            // });
			
			//chosen
			$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
			$('#formulario').validator();

			//grouped
			var barchartgrouped = new Chart(document.getElementById("bar-chart-grouped"), {
				type: 'bar',
				data: {
				  labels: ["18 a 20", "21 a 30", "31 a 40", "41 a 50", "51 a 60", "61 a 70", "71 a 80"],
				  datasets: [
					{
					  // labels: ["18 a 20", "21 a 30", "31 a 40", "41 a 50", "51 a 60", "61 a 70", "71 a 80"],					  
					  backgroundColor: "#85C1E9",					 
					  data: [<?=$qtd_idade1?>, <?=$qtd_idade2?>, <?=$qtd_idade3?>, <?=$qtd_idade4?>, <?=$qtd_idade5?>, <?=$qtd_idade6?>, <?=$qtd_idade7?>]
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
							$("input[name=barchartgrouped]").val(barchartgrouped.toBase64Image());
						}
					}
				}
			});

			//grouped
			new Chart(document.getElementById("bar-chart-grouped-2"), {
				type: 'bar',
				data: {
				  labels: ["E-mails", "Celulares", "Dt. Nascimento", "CEP", "Endereços"],
				  datasets: [{
				  	  // labels: ["E-mails", "Celulares", "Dt. Nascimento", "CEP", "Endereços"],
					  backgroundColor: window.chartColors.cyan,
					  data: [<?=$pct_email?>, <?=$pct_celular?>, <?=$pct_nascimento?>, <?=$pct_cep?>, <?=$pct_endereco?>]
					}
				  ]
				},
				options: {
				   legend: {
			            display: false
			         },
				   tooltips: {
				      callbacks: {
				         label: function (t, d) {
					        return t.yLabel.toFixed(2) + "%"
					  }
					}
				   },
				  scales: {						
						yAxes: [{
							ticks: {
			            		min: 0,
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
				  
				}
			});

			//grouped
			new Chart(document.getElementById("bar-chart-grouped-3"), {
				type: 'bar',
				data: {
				  labels: [""],
				  datasets: [
					{
					  label: "Fidelizadas",
					  backgroundColor: window.chartColors.blue,
					  data: [<?=$ticket_medio_fidelizado?>]
					}, {
					  label: "Avulsas",
					  backgroundColor: window.chartColors.green,
					  data: [<?=$ticket_medio_avulso?>]
					}
				  ]
				},
				options: {
				 //  title: {
					// display: true,
					// text: ''
				 //  },
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
								 beginAtZero: true,
								callback: function(value, index, values) {
					              if(parseInt(value) >= 1000){
					                return 'R$ ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
					              } else {
					                return 'R$ ' + value;
					              }
					            }
							}													
						}],
						xAxes: [{
							labels: ["Fidelizadas","Avulsas"]													
						}]					
					},
				  
				}
			});

			new Chart(document.getElementById("bar-chart-grouped-4"), {
				type: 'bar',
				data: {
				  labels: ["<?=$mes0?>", "<?=$mes1?>", "<?=$mes2?>", "<?=$mes3?>", "<?=$mes4?>", "<?=$mes5?>"],
				  datasets: [
					{
					  // labels: ["18 a 20", "21 a 30", "31 a 40", "41 a 50", "51 a 60", "61 a 70", "71 a 80"],					  
					  backgroundColor: "#85C1E9",					 
					  data: [<?=$qtd_clientes_compraram_mesm0?>, <?=$qtd_clientes_compraram_mesm1?>, <?=$qtd_clientes_compraram_mesm2?>, <?=$qtd_clientes_compraram_mesm3?>, <?=$qtd_clientes_compraram_mesm4?>, <?=$qtd_clientes_compraram_mesm5?>]
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
				  
				}
			});
	
			//donut 
			var config = {
				type: 'doughnut',
				data: {
					datasets: [{
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
						"Mulheres",
						"Homens",
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


			//donut 
			var config2 = {
				type: 'doughnut',
				data: {
					datasets: [{
						data: [
							"<?=$qtd_transacoes_fidelizado?>",
							"<?=$qtd_transacoes_avulso?>",
						],
						backgroundColor: [
							window.chartColors.blue,
							window.chartColors.green,
						],
						label: 'Dataset 1'
					}],
					labels: [
						"Fidelizadas",
						"Avulsas"
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
						animateRotate: true
					}
				}
			};

			var ctx = document.getElementById("chart-area").getContext("2d");
    		var myDoughnut = new Chart(ctx,config);


    		var ctx2 = document.getElementById("chart-area2").getContext("2d");
    		var myDoughnut2 = new Chart(ctx2,config2);

			var data = {
			    labels: ["Fideliz. Limpo - R$ <?=fnValor($listaFatLmp,2)?>", "Avulso - R$ <?=fnValor($listaFatAv,2)?>", "Resgate Total - R$ <?=fnValor($listaFatTotRes,2)?>"],
			      datasets: [
			        {
			            
			            backgroundColor: [
			            	window.chartColors.red,
			            	window.chartColors.blue,			               	
							window.chartColors.green,
							
			            ],
			            data: [<?=$listaFatFid?>, <?=$listaPctFatAv?>, <?=$listaFatRes?>],
			// Notice the borderColor 
			            // borderColor: ['black', 'black'],
			            borderWidth: [1,1]
			        }
			    ]
			};

			
        });

	</script>

<?php

	}