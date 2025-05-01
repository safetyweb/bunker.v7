<?php
	
	//echo fnDebug('true');
	
	// definir o numero de itens por pagina
	$itens_por_pagina = 50;
	$pagina = 1;
	
	$dias30="";
	$dat_ini="";
	$dat_fim="";
	$tipoVenda = "T";
	$hashLocal = mt_rand();	
	
	//inicialização de variáveis
	$hoje = fnFormatDate(date("Y-m-d"));
	//$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 1 days')));
	$dias30 = fnFormatDate(date("Y-m-d"));
	$cod_univend = "9999"; //todas revendas - default
	
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
			$tipoVenda = $_POST['tipoVenda'];

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
	if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31" ){
		$dat_ini = fnDataSql($dias30); 
	} 
	if (strlen($dat_fim ) == 0 || $dat_fim == "1969-12-31"){
		$dat_fim = fnDataSql($hoje); 
	}	
	if (strlen($cod_univend ) == 0){
		$cod_univend = "9999"; 
	}
	
	if ($tipoVenda == "T"){
		$checkTodas = "checked"; 
		$checkCreditos = ""; 
	}else{
		$checkTodas = ""; 
		$checkCreditos = "checked"; 
	}	
	
	//busca revendas do usuário
	include "unidadesAutorizadas.php"; 
	
	//fnMostraForm();	
	//fnEscreve($dat_ini);
	//fnEscreve($lojasSelecionadas);
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
									
								</div>
								
								<div class="push10"></div>
										
						</fieldset>	
						
						<div class="push20"></div>
						
						<div id="relatorioConteudo">
							<div class="row">
								<div class="col-md-12" id="div_Produtos">

									<div class="push20"></div>
									
									<table class="table table-bordered table-hover tablesorter">
									
									  <thead>
										<tr>
										  <th><small>Nome</small></th>
                                          <th><small>CPF</small></th>
										  <th><small>Cartão</small></th>
										  <th><small>Sexo</small></th>
										  <th><small>Data Nascimento</small></th>
										  <th><small>Celular</small></th>
										  <th><small>e-Mail</small></th>
										  <th><small>Data/Hora</small></th>
										  <th><small>Integrado Procfit</small></th>
										  
										</tr>
									  </thead>
										
										<?php
										
											$numPaginas = ceil(500/$itens_por_pagina);
											
											//variavel para calcular o início da visualização com base na página atual
											$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;										

											$hostname = "173.212.201.183";
											$dbname = "INTEGRACAO_CLUBE_SO";
											$username = "Marka_so";
											$pw = "H+proc29.5";
											$con = mssql_connect ($hostname, $username, $pw);
											mssql_select_db ($dbname, $con);
											$mssql= "select top 500 * from dbo.CLIENTES_CLUBE_SO order by DATA_HORA desc";
											$rs= mssql_query ($mssql,$con) or DIE("Table unavailable");
											$rssql=mssql_fetch_assoc($rs);       											
                                                                                    
                                            $countLinha = 1;
											while ($rssql=mssql_fetch_assoc($rs))
											  {
												  
												 if ($rssql['SEXO'] == "M"){		
														$mostraSexo = '<i class="fa fa-male" aria-hidden="true"></i>';	
													}else{ $mostraSexo = '<i class="fa fa-female" aria-hidden="true"></i>'; }	
												  
												 if ($rssql['LIDO_PROCFIT'] == "S"){		
														$mostraLido = '<i class="fa fa-check text-success" aria-hidden="true"></i>';	
													}else{ $mostraLido = '<i class="fa fa-times text-danger" aria-hidden="true"></i>'; }	
												  
												?>
													<tr style="background-color: #fff;">
                                                      <td><a href="action.do?mod=<?php echo fnEncode(1024); ?>&id=<?php echo fnEncode($cod_empresa); ?>&idC=<?php echo fnEncode($qrListaVendas['COD_CLIENTE']); ?>" class="f14" target="_blank"><?php echo $rssql['NOME']; ?></a></td>
													  <td><?php echo $rssql['CPF']; ?></td>
													  <td><small><?php echo $rssql['NUMERO_CARTAO']; ?></small></td>
													  <td class="text-center"><small><?php echo $mostraSexo; ?></small></td>
                                                      <td><small><?php echo fnDataShort($rssql['DATA_NASCIMENTO']); ?></small></td>
													  <td><small><?php echo $rssql['CELULAR']; ?></small></td>
													  <td><small><?php echo $rssql['EMAIL']; ?></small></td>
													  <td><small><?php echo fnDataFull($rssql['DATA_HORA']); ?></small></td>
													  <td class="text-center"><small><?php echo $mostraLido; ?></small></td>
													
													</tr>
												<?php
												
											  
											  $vendaFim = $qrListaVendas['DAT_CADASTR'];
											  $countLinha++;	
											  }			
                                              mssql_close ($con);
										?>	
									
										</tbody>
										
										<!--
										<tfoot>
											<tr>
											  <th class="" colspan="100"><ul class="pagination pagination-sm">
											  <?php
												for($i = 1; $i < $numPaginas + 1; $i++) {
													if ($pagina == $i){$paginaAtiva = "active";}else{$paginaAtiva = "";}	
												echo "<li class='pagination $paginaAtiva'><a href='#' onclick='reloadPage($i);' style='text-decoration: none;'>".$i."</a></li>";   
												}													  
											  ?></ul>
											  </th>
											</tr>
										</tfoot>
										-->
										
									</table>
																					
								</div>
								
								<?php
								
								function fullDateDiff($date1, $date2)
								{		
									$date1=strtotime($date1);
									$date2=strtotime($date2); 
									$diff = abs($date1 - $date2);
									
									$day = $diff/(60*60*24); // in day
									$dayFix = floor($day);
									$dayPen = $day - $dayFix;
									if($dayPen > 0)
									{
										$hour = $dayPen*(24); // in hour (1 day = 24 hour)
										$hourFix = floor($hour);
										$hourPen = $hour - $hourFix;
										if($hourPen > 0)
										{
											$min = $hourPen*(60); // in hour (1 hour = 60 min)
											$minFix = floor($min);
											$minPen = $min - $minFix;
											if($minPen > 0)
											{
												$sec = $minPen*(60); // in sec (1 min = 60 sec)
												$secFix = floor($sec);
											}
										}
									}
									$str = "";
									if($dayFix > 0)
										$str.= $dayFix."d ";
									if($hourFix > 0)
										$str.= $hourFix."h ";
									if($minFix > 0)
										$str.= $minFix."m ";
									if($secFix > 0)
										$str.= $secFix."s ";
									return $str;
								}
																
									//fnEscreve($vendaIni);
									//fnEscreve(fnDataFull($vendaIni));
									//fnEscreve(fnFormatDateTime($vendaIni));
									//fnEscreve($vendaFim);
									//fnEscreve(fnDataFull($vendaFim));
									//fnEscreve(fullDateDiff($vendaIni, $vendaFim));
									//fnEscreve(fnValor($totalVenda,2));
									//fnEscreve(fnValor($totalVenda,2));
									
									//$to_time = strtotime("2008-12-13 10:42:00");
									//$from_time = strtotime("2008-12-13 10:21:00");
									//fnEscreve(round(abs($vendaFim - $vendaini) / 60,2). " minute");									
									
																
								?>
								
								
							</div>
						</div>
												
						<input type="hidden" name="LOJAS" id="LOJAS" value="<?php echo $lojasSelecionadas; ?>" />
						<input type="hidden" name="opcao" id="opcao" value="">
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
				

		});	

		function reloadPage(idPage) {
			$.ajax({
				type: "POST",
				url: "ajxRelProcfitUltra.do?id=<?php echo fnEncode($cod_empresa); ?>&idPage="+idPage+"&itens_por_pagina=<?php echo $itens_por_pagina; ?>",
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
   