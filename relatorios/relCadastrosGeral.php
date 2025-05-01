<?php
	
	//echo fnDebug('true');
	
	// definir o numero de itens por pagina
	$itens_por_pagina = 50;
	
	// Página default
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
									
									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label required">Unidade de Atendimento</label>
											<?php include "unidadesAutorizadasCombo.php"; ?>
										</div>
									</div>
									
									<div class="col-md-2">
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
									
									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Data Final</label>
											
											<div class="input-group date datePicker" id="DAT_FIM_GRP">
												<input type='text' class="form-control input-sm data" name="DAT_FIM" id="DAT_FIM" value="<?php echo fnFormatDate($dat_fim); ?>" required/>
												<span class="input-group-addon">
													<span class="glyphicon glyphicon-calendar"></span>
												</span>
											</div>
											<div class="help-block with-errors"></div>
										</div>
									</div>	
									
									<div class="col-md-2">
										<div class="push20"></div>
										<button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-sm getBtn"><i class="fa fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
									</div>
														
									
								</div>
								
								<div class="push10"></div>
								
								<div class="row">
								
									<div class="col-md-1">
									
										<div class="radio radio-info radio-inline">
											<input type="radio" id="tipoVenda1" value="T" name="tipoVenda" <?php echo $checkTodas; ?> >
											<label for="tipoVenda1"> Todas </label>
										</div>
										
									</div>
									
									<div class="col-md-2">
									
										<div class="radio radio-info radio-inline">
											<input type="radio" id="tipoVenda2" value="C" name="tipoVenda" <?php echo $checkCreditos; ?> >
											<label for="tipoVenda2"> Com créditos </label>
										</div>														
									
									</div>
									
								</div>
									
						</fieldset>	
						
						<div class="push20"></div>
						
						<div id="relatorioConteudo">
							<div class="row">
								<div class="col-md-12" id="div_Produtos">

									<div class="push20"></div>
									
									<table class="table table-bordered table-hover">
									
									  <thead>
										<tr>
										  <th><small>Autorização</small></th>
										  <th><small>Cliente</small></th>
										  <th><small>Cartão</small></th>
										  <th><small>Loja</small></th>
										  <th><small>Data/Hora</small></th>
										  <th><small>Valor Venda</small></th>
										  <th><small>Créditos</small></th>
										  <th><small>Expiram em</small></th>
										  <th><small>Operador</small></th>
										  <th><small>Vendedor</small></th>
										  <th><small>Cupom</small></th>
										</tr>
									  </thead>
										
										<?php
										
											if ($tipoVenda == "T"){
												$andCreditos = " "; 
											}else{
												$andCreditos = "AND B.NUM_CARTAO != 0 "; 
											}	
	
											$sql = "SELECT count(*) as contador
													FROM VENDAS A
													LEFT JOIN CLIENTES B ON A.COD_CLIENTE=B.COD_CLIENTE
													LEFT JOIN webtools.USUARIOS C ON C.COD_USUARIO = A.COD_USUCADA
													LEFT JOIN webtools.UNIDADEVENDA D ON D.COD_UNIVEND = A.COD_UNIVEND
													WHERE 
													  DATE_FORMAT(A.DAT_CADASTR, '%Y-%m-%d') >= '$dat_ini' 
														AND DATE_FORMAT(A.DAT_CADASTR, '%Y-%m-%d') <= '$dat_fim' 
														AND A.COD_EMPRESA = $cod_empresa
														$andCreditos														
														AND A.COD_UNIVEND IN($lojasSelecionadas)
													";
													  
											//fnEscreve($sql);

											$retorno = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
											$totalitens_por_pagina = mysqli_fetch_assoc($retorno);

											$numPaginas = ceil($totalitens_por_pagina['contador']/$itens_por_pagina);
											
											//variavel para calcular o início da visualização com base na página atual
											$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;
											
											$sql = "SELECT
												    A.COD_VENDA,														
												    A.COD_CUPOM,														
												    B.COD_CLIENTE,
												    B.NOM_CLIENTE,
												    B.NUM_CARTAO,
												    D.NOM_FANTASI,
												    A.DAT_CADASTR,
												    A.VAL_TOTVENDA,
												    ROUND(IFNULL((SELECT SUM(VAL_CREDITO) FROM CREDITOSDEBITOS WHERE COD_VENDA=A.COD_VENDA AND TIP_CREDITO='C'),0),2) VAL_CREDITOS,
												    (SELECT MAX(DAT_EXPIRA) FROM CREDITOSDEBITOS WHERE COD_VENDA=A.COD_VENDA AND TIP_CREDITO='C') DAT_EXPIRA,
												    C.NOM_USUARIO AS VENDEDOR,
												    E.NOM_USUARIO AS OPERADOR
													FROM VENDAS A
													INNER JOIN CLIENTES B ON A.COD_CLIENTE=B.COD_CLIENTE
													LEFT JOIN webtools.USUARIOS C ON C.COD_USUARIO = A.COD_VENDEDOR
													LEFT JOIN webtools.UNIDADEVENDA D ON D.COD_UNIVEND = A.COD_UNIVEND
													LEFT JOIN webtools.USUARIOS E ON E.COD_USUARIO = A.COD_USUCADA													
													WHERE 
													  DATE_FORMAT(A.DAT_CADASTR, '%Y-%m-%d') >= '$dat_ini' 
													  AND DATE_FORMAT(A.DAT_CADASTR, '%Y-%m-%d') <= '$dat_fim' 
													  AND A.COD_EMPRESA = $cod_empresa
													  AND A.COD_UNIVEND IN($lojasSelecionadas)
													  $andCreditos
													  order by  A.DAT_CADASTR desc  limit $inicio,$itens_por_pagina ";
											
											//fnEscreve($sql);	
											
											$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
											
											$countLinha = 1;
											while ($qrListaVendas = mysqli_fetch_assoc($arrayQuery))
											  {
												if ($countLinha == 1){
													$vendaIni = $qrListaVendas['DAT_CADASTR'];													
												}
												
												$totalVenda = $totalVenda + $qrListaVendas['VAL_TOTVENDA'];
												
												?>
													<tr style="background-color: #fff;" class="abreDetail_<?php echo $qrListaVendas['COD_UNIVEND']; ?>">
													  <td><?php echo $qrListaVendas['COD_VENDA']; ?></td>
													  <td><a href="action.do?mod=<?php echo fnEncode(1024); ?>&id=<?php echo fnEncode($cod_empresa); ?>&idC=<?php echo fnEncode($qrListaVendas['COD_CLIENTE']); ?>" class="f14" target="_blank"><?php echo fnMascaraCampo($qrListaVendas['NOM_CLIENTE']); ?></a></td>
													  <td class="text-right"><small><?php echo fnMascaraCampo($qrListaVendas['NUM_CARTAO']); ?></small></td>
													  <td><small><?php echo $qrListaVendas['NOM_FANTASI']; ?></small></td>
													  <td><small><?php echo fnDataFull($qrListaVendas['DAT_CADASTR']); ?></small></td>
													  <td class="text-right"><small>R$ <?php echo fnValor($qrListaVendas['VAL_TOTVENDA'],2); ?></small></td>
													  <td class="text-right"><small>R$ <?php echo fnValor($qrListaVendas['VAL_CREDITOS'],2); ?></small></td>
													  <td><small><?php echo fnDataFull($qrListaVendas['DAT_EXPIRA']); ?></small></td>
													  <td><small><?php echo $qrListaVendas['OPERADOR']; ?></small></td>
													  <td><small><?php echo $qrListaVendas['VENDEDOR']; ?></small></td>
													  <td><small><?php echo $qrListaVendas['COD_CUPOM']; ?></small></td>
													</tr>
												<?php
											  
											  $vendaFim = $qrListaVendas['DAT_CADASTR'];
											  $countLinha++;	
											  }			
			
										?>	
									
										</tbody>

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
				url: "relatorios/ajxVendasGeral.do?id=<?php echo fnEncode($cod_empresa); ?>&idPage="+idPage+"&itens_por_pagina=<?php echo $itens_por_pagina; ?>",
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
   