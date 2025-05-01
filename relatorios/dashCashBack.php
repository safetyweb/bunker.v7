<?php
	
	//echo fnDebug('true');
	$hashLocal = mt_rand();	
	//fnMostraForm();
	//inicialização de variáveis
	//$hoje = fnFormatDate(date("Y-m-d"));
	$hoje = fnFormatDate(date('Y-m-d', strtotime($hoje. '- 1 days')));
	$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 30 days')));
	
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
			$cod_univend = $_REQUEST['COD_UNIVEND'];
			$cod_grupotr = $_REQUEST['COD_GRUPOTR'];	
			$cod_tiporeg = $_REQUEST['COD_TIPOREG'];
			$cod_filtro = $_REQUEST['COD_FILTRO'];
			$dat_ini = "01/".$_REQUEST['DAT_INI'];
			$dat_fim = $_REQUEST['DAT_FIM'];
	        $opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];

			$array_dat_fim  = explode("/", $dat_fim);

			$dat_fim = cal_days_in_month(CAL_GREGORIAN, $array_dat_fim[0], $array_dat_fim[1])."/".$dat_fim;
			
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
	if ($cod_univend == "9999"){$temUnivend = "N";} else {$temUnivend = "S";}
	
	//busca revendas do usuário
	include "unidadesAutorizadas.php";

	// Filtro por Grupo de Lojas
	include "filtroGrupoLojas.php";
	
	// fnEscreve($maxComp);
	// fnEscreve($maxEvo);
	
	//busca período default
	if ($cod_filtro == "") {
		//fnEscreve(1); 
		$sql = "SELECT MAX(COD_FILTRO) AS COD_FILTRO , MAX(QTD_DIASHIST) AS QTD_DIASHIST , MAX(QTD_MESCLASS) AS QTD_MESCLASS , MAX(DT_FILTRO) AS DT_FILTRO FROM filtro_frequencia where COD_EMPRESA = $cod_empresa ";
		//fnEscreve($sql);
		$arrayQuery = mysqli_query(connTemp($cod_empresa,""),trim($sql)) or die(mysqli_error());
		$qrBuscaFiltro = mysqli_fetch_assoc($arrayQuery);
		
			$cod_filtro = $qrBuscaFiltro['COD_FILTRO'];
			$qtd_diashist = $qrBuscaFiltro['QTD_DIASHIST'];
			$qtd_mesclass = $qrBuscaFiltro['QTD_MESCLASS'];
			$dt_filtro = $qrBuscaFiltro['DT_FILTRO'];
		
	}else{
		//fnEscreve(2); 		
		$sql = "SELECT COD_FILTRO , QTD_DIASHIST , QTD_MESCLASS , DT_FILTRO FROM filtro_frequencia where COD_EMPRESA = $cod_empresa AND COD_FILTRO = $cod_filtro ";
		//fnEscreve($sql);
		$arrayQuery = mysqli_query(connTemp($cod_empresa,""),trim($sql)) or die(mysqli_error());
		$qrBuscaFiltro = mysqli_fetch_assoc($arrayQuery);
		
			$cod_filtro = $qrBuscaFiltro['COD_FILTRO'];
			$qtd_diashist = $qrBuscaFiltro['QTD_DIASHIST'];
			$qtd_mesclass = $qrBuscaFiltro['QTD_MESCLASS'];
			$dt_filtro = $qrBuscaFiltro['DT_FILTRO'];
	}	
	
	$dat_ini = fnDatasql($dat_ini);
 	$dat_fim = fnDatasql($dat_fim);

 	$sqlPeriodo = "SELECT COD_FILTRO, DT_FILTRO FROM filtro_frequencia where COD_EMPRESA = $cod_empresa order by DT_FILTRO desc ";
    $arrayQueryPeriodo = mysqli_query(connTemp($cod_empresa,""),trim($sqlPeriodo)) or die(mysqli_error());

    $qtd_periodos = mysqli_num_rows($arrayQueryPeriodo);

	if($qtd_periodos == 0){
		$msgTipo = "alert-danger";
		$msgRetorno = "Você ainda <b>não possui</b> a configuração para utilizar essa tela. <br/> Entre em <b>contato</b> com o seu <b>consultor</b>.";
	}

 	//fnEscreve($dt_filtro);
 	// fnEscreve($cod_filtro);
	
?>
			
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
																<select data-placeholder="Selecione o período" name="COD_FILTRO" id="COD_FILTRO" class="chosen-select-deselect">
																	<option value=""></option>					
																	<?php
																	
																		while ($qrListaFiltro = mysqli_fetch_assoc($arrayQueryPeriodo))
																		  {														
																			echo"
																				  <option value='".$qrListaFiltro['COD_FILTRO']."'>".date("m/Y",strtotime($qrListaFiltro['DT_FILTRO']))." ".$ano."</option> 
																				"; 
																			  }											
																	?>	
																</select>
                                                                <script>$("#formulario #COD_FILTRO").val("<?php echo $cod_filtro; ?>").trigger("chosen:updated"); </script>                                                       
															<div class="help-block with-errors"></div>
														</div>
													</div>
													
													<div class="col-md-2">
														<div class="push20"></div>
														<button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-sm btn-block getBtn"><i class="fa fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
													</div>
																				
												</div>
												
										</fieldset>	
										
										<input type="hidden" name="NUM_ORDENAC" id="NUM_ORDENAC" value="">
										
										<input type="hidden" name="opcao" id="opcao" value="">
										<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
										<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">		
										
										<div class="push5"></div> 
										
										</form>
										
								
									</div>
									
								</div>
							</div>
							<!-- fim Portlet -->
						</div>										
										

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

	.f26b {
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
		width: 120px;
		height: 40px;
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

				<?php 
					if($qtd_periodos > 0){ 
				?>
						<div class="push20"></div>				
										
						<div class="col-md12 margin-bottom-30">
							<!-- Portlet -->
							<div class="portlet portlet-bordered">
																
								<div class="portlet-body">

										<?php
										
											$dataConsulta = substr($dt_filtro,0,4)."-".substr($dt_filtro,5,2);
											//fnEscreve($dataConsulta);
											$sql = "CALL SP_RELAT_LUCRO_FREQUENCIA_UNIVEND('$lojasSelecionadas', $cod_empresa, $cod_filtro, '$dataConsulta')";
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
												$vl_lucro_total[$count] = $qrFunil['VL_LUCRO_TOTAL'];
												$qtd_transacoes[$count] = $qrFunil['QTD_TRANSACOES'];
												$qtd_total_item[$count] = $qrFunil['QTD_TOTAL_ITEM'];
												$qtd_media_compra[$count] = $qrFunil['QTD_MEDIA_COMPRA'];
												$qtd_media_item[$count] = $qrFunil['QTD_MEDIA_ITEM'];

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

											$qtd_media_compra1 = $qtd_media_compra[0];
											$qtd_media_compra2 = $qtd_media_compra[1];
											$qtd_media_compra3 = $qtd_media_compra[2];
											$qtd_media_compra4= $qtd_media_compra[3];
											$qtd_media_compra5 = $qtd_media_compra[4];
											
											$qtd_media_item1 = $qtd_media_compra[0];
											$qtd_media_item2 = $qtd_media_compra[1];
											$qtd_media_item3 = $qtd_media_compra[2];
											$qtd_media_item4 = $qtd_media_compra[3];
											
											$pa1 = $qtd_total_item[0]/$qtd_transacoes[0];
											$pa2 = $qtd_total_item[1]/$qtd_transacoes[1];
											$pa3 = $qtd_total_item[2]/$qtd_transacoes[2];
											$pa4 = $qtd_total_item[3]/$qtd_transacoes[3];
																					
											$bar2Calc = 70;
											$bar3Calc = 55;
											$bar4Calc = 35;	
	
											//fnEscreve($qtd_diashist);
											//fnEscreve($qtd_mesclass);
											//fnEscreve($dt_filtro);

											$mes = date("m",strtotime($dt_filtro));; // Mês desejado, pode ser por ser obtido por POST, GET, etc.
											$ano = date("Y",strtotime($dt_filtro));  // Ano atual
											$ultimo_dia = date("t", mktime(0,0,0,$mes,'01',$ano)); // Mágica, plim!
											$ultima_data = $ultimo_dia."/".$mes."/".$ano;
											//fnEscreve($ultimo_dia);
											$dias_periodo = $qtd_diashist + 1;
											$dt_filtroMenor = date('Y-m-d', strtotime($dt_filtro. '-'.$dias_periodo.' days'));
											
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

										?>	
																				
										<div class="push10"></div>
										
										<div class="row text-center">			
											
											<div class="form-group text-center col-lg-12">
											
												<h4>Dados do Ciclo de Recompra</h4>
											
											</div>
											
											<div class="push30"></div>
											
											<div class="col-md-2"></div>	

											<div class="col-md-2 text-center text-info">
												<i class="fal fa-calendar-alt fa-3x" aria-hidden="true"></i>												
												<div class="push10"></div>
												<b><?=fnDataShort($dt_filtroMenor)?> </b>
												<div class="push10"></div>
												<small style="font-weight:normal;">Total da Base Cadastrados </small>
											</div>
											
											<div class="col-md-2 text-center text-info">
												<i class="fal fa-sync fa-3x" aria-hidden="true"></i>												
												<div class="push10"></div>
												<b><?=$classifica; ?></b>
												<div class="push10"></div>
												<small style="font-weight:normal;">Total de faturamento Fidelizado (+ %)</small></small>
											</div>
											
											<div class="col-md-2 text-center text-info">
												<i class="fal fa-shopping-cart fa-3x" aria-hidden="true"></i>												
												<div class="push10"></div>
												<b><?=fnDataShort($dt_filtroMenor)?> a <?=fnDataShort($dt_filtro); ?></b>
												<div class="push10"></div>
												<small style="font-weight:normal;">Total de transações fidelizadas (+ %)</small>
											</div>

											<div class="col-md-2 text-center text-info">
												<i class="fal fa-history fa-3x" aria-hidden="true"></i>												
												<div class="push10"></div>
												<b><?=$qtd_diashist?></b>
												<div class="push10"></div>
												<small style="font-weight:normal;">Total de Itens (+%)</small>
											</div>	
											
											<div class="col-md-2"></div>	
											
										</div>
										
										<div class="push50"></div>
										
										
										<div class="row text-center">			
											
											<div class="form-group text-center col-lg-12">
											
												<h3>Flow Cash Back</h3>
												
												<div class="push50"></div>
												
													<div class="form-group text-center col-lg-1"></div>
													
													<div class="form-group text-center col-lg-10" style="background-color: #D4E6F1; min-height: 110px;">
														
														<div class="push20"></div>
														
														<div class="form-group text-center col-lg-2">
														<h4>Entradas</h4>
														</div>
														
														<div class="form-group text-center col-lg-2 cor4 ">
															<div class="push10"></div>
															<div class="text-center " style="width: -webkit-calc(100%);">
																Créditos Gerados <br/> 
																<b>R$ <?=fnValor($cliente[0],0); ?></b> <br/>
																<small>(ref. índice de fidelização)
																<div class="push10"></div>
																Clientes único que gerou crédito
																<div class="push10"></div>
																percentual sobre total cadastrado
																<div class="push10"></div>
															</div>
														</div>
														
														<div class="form-group text-center col-lg-4"></div>
													
														<div class="form-group text-center col-lg-2 cor4 ">
															<div class="push10"></div>
															<div class="text-center " style="width: -webkit-calc(100%);">
																Créditos em Lote <br/> 
																<b>R$ <?=fnValor($cliente[0],0); ?></b> <br/>
																<small>(ref. créditos em lote)</small>
																<div class="push10"></div>
																Clientes único que ganhou crédito
																<div class="push10"></div>
																percentual sobre total cadastrado
																<div class="push10"></div>
															</div>
														</div>
														
														<div class="push20"></div>
														
													</div>		
													
													<div class="form-group text-center col-lg-1"></div>													
													
													
												
												<div class="form-group text-right col-lg-4">
												
													<div class="push20"></div>
													<div class="push20"></div>
													<h3>R$ 1.234,56</h3>
													<small>SALDO VANTAGENS<br/>TOTAL</small>
												
												</div>
												
												<div class="form-group text-center col-lg-4 ">
												
													<div class="push50"></div>
												
													<table class="table table-bordered" role="grid">
													
														<thead>
															<tr role="row" class="tablesorter-headerRow">
															  <th class="text-center tablesorter-header tablesorter-headerUnSorted" data-column="1" tabindex="0" scope="col" role="columnheader" aria-disabled="false" unselectable="on" aria-sort="none" aria-label="Cartão: No sort applied, activate to apply an ascending sort" style="user-select: none;"><div class="tablesorter-header-inner"><small>Mês</small></div></th>
															  <th class="text-center tablesorter-header tablesorter-headerUnSorted" data-column="2" tabindex="0" scope="col" role="columnheader" aria-disabled="false" unselectable="on" aria-sort="none" aria-label="Email: No sort applied, activate to apply an ascending sort" style="user-select: none;"><div class="tablesorter-header-inner"><small>Valor a Expirar</small></div></th>
															  <th class="text-center tablesorter-header tablesorter-headerUnSorted" data-column="2" tabindex="0" scope="col" role="columnheader" aria-disabled="false" unselectable="on" aria-sort="none" aria-label="Email: No sort applied, activate to apply an ascending sort" style="user-select: none;"><div class="tablesorter-header-inner"><small>Clientes Únicos</small></div></th>
															</tr>
														</thead>
													  
														<tbody id="relatorioConteudo" aria-live="polite" aria-relevant="all">

															<tr role="row">
																<td class="text-center"><small>Outubro 2020</small></td>
																<td class="text-center"><small>R$ 123,45</small></td>
																<td class="text-center"><small>3.500</small></td>
															</tr>
															
															<tr role="row">
																<td class="text-center"><small>Setembro 2020</small></td>
																<td class="text-center"><small>R$ 123,45</small></td>
																<td class="text-center"><small>3.500</small></td>
															</tr>
															
															<tr role="row">
																<td class="text-center"><small>Agosto 2020</small></td>
																<td class="text-center"><small>R$ 123,45</small></td>
																<td class="text-center"><small>3.500</small></td>
															</tr>

															<tr role="row">
																<td class="text-center"><small>Julho 2020</small></td>
																<td class="text-center"><small>R$ 123,45</small></td>
																<td class="text-center"><small>3.500</small></td>
															</tr>
															
															<tr role="row">
																<td class="text-center"><small>Junho 2020</small></td>
																<td class="text-center"><small>R$ 123,45</small></td>
																<td class="text-center"><small>3.500</small></td>
															</tr>


														</tbody>
																											
													</table>
													
													<div class="push10"></div>
													
												</div>
												
												<div class="form-group text-left col-lg-4 ">
												
													<div class="push20"></div>
													<div class="push20"></div>
													<h3>1.234</h3>
													<small>CLIENTES ÚNICOS <br/>TOTAL</small>
													<div class="push10"></div>
													% de clientes sobre cadastros
																								
												
												</div>
														
												<div class="push10"></div>
												
												<div class="form-group text-center col-lg-1"></div>
												
												<div class="form-group text-center col-lg-10" style="background-color: #E5E7E9; min-height: 110px;">
													
													<div class="push20"></div>
													
													<div class="form-group text-center col-lg-2">
													<h4>Saídas</h4>
													</div>
													
													<div class="form-group text-center col-lg-2" style="background-color: #A6ACAF;">
														<div class="push10"></div>
														<div class="text-center " style="width: -webkit-calc(100%);">
															Crédito Expirado sem Resgate <br/> 
															<b>R$ <?=fnValor($cliente[0],0); ?></b> <br/>
															<small>(ref. créd. expirado sem resgate)
															<div class="push10"></div>
															Taxa de Perda = valor expirado sem resgate / valor total com expiração ate a data
															<div class="push10"></div>
															Clientes únicos que perderam crédito
															<div class="push10"></div>
														</div>
													</div>
													
													<div class="form-group text-center col-lg-4"></div>													
											
													<div class="form-group text-center col-lg-2 " style="background-color: #A6ACAF;">
														<div class="push10"></div>
														<div class="text-center " style="width: -webkit-calc(100%);">
															Créditos Resgatados <br/> 
															<b>R$ <?=fnValor($cliente[0],0); ?></b> <br/>
															<small>(ref. resgates)
															<div class="push10"></div>
															Taxa de Aproveitamento = Total resgatado / (saldo total a expirar + valor total com expiração ate a data)
															<div class="push10"></div>
															Clientes únicos que resgataram
															<div class="push10"></div>
														</div>
													</div>
													
													<div class="push20"></div>
													
												</div>		
												
												<div class="form-group text-center col-lg-1"></div>													
												
												
											</div>
										
										</div>
										
										<div class="push50"></div>
										
									
							</div>
							<!-- fim Portlet -->
				<?php 
					} 
				?>
						</div>
						
					</div>					
						
					<div class="push20"></div> 
					
					
	<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
	<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />				

	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.min.js"></script> 
    <script src="js/plugins/Chart_Js/utils.js"></script>
	
	<script src="js/plugins/ion.rangeSlider.js"></script>
    	
    <script>
	
		//datas
		$(function () {
			
			$('.datePicker').datetimepicker({
				viewMode: 'years',
      			format: 'MM/YYYY',
				}).on('changeDate', function(e){
					$(this).datetimepicker('hide');
				});
			
			$("#DAT_INI_GRP").on("dp.change", function (e) {
				$('#DAT_FIM_GRP').data("DateTimePicker").minDate(e.date);
			});
			
			$("#DAT_FIM_GRP").on("dp.change", function (e) {
				$('#DAT_INI_GRP').data("DateTimePicker").maxDate(e.date);
			});				
				
		});	
		
		//graficos
        $(document).ready( function() {

			
			//chosen
			$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
			$('#formulario').validator();
			

				
			
	
			
        });

	</script>	
   