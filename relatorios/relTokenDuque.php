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
	$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 7 days')));
	//$dias30 = fnFormatDate(date("Y-m-d"));
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
								
									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Empresa</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa ?>">
											<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
										</div>														
									</div>
									
									<div class="col-md-4">
										<div class="form-group">
											<label for="inputName" class="control-label required">Unidade de Atendimento</label>
											<?php include "unidadesAutorizadasComboMulti.php"; ?>
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
										<button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-block btn-sm getBtn"><i class="fa fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
									</div>
														
									
								</div>
								
								<div class="push10"></div>
								
									
						</fieldset>	
						
						<div class="push20"></div>
						
						<div>
							<div class="row">
								<div class="col-md-12" id="div_Produtos">

									<div class="push20"></div>
									
									<table class="table table-bordered table-hover">
									
									  <thead>
										<tr>
										  <th><small>Autorização</small></th>
										  <th><small>Cliente</small></th>
										  <th><small>Convênio</small></th>
										  <th><small>Código</small></th>
										  <th><small>Loja</small></th>
										  <th><small>Data/Hora</small></th>
										  <th><small>Valor Venda</small></th>
										  <th><small>Placa</small></th>
										  <th><small>PDV</small></th>
										  <th><small>Vendedor</small></th>
										  <th><small>Token</small></th>
										  <th><small>Status</small></th>
										  <th><small>Conformidade</small></th>
										</tr>
									  </thead>
										<tbody id="relatorioConteudo">
										<?php
										
function fngeraSenha($tamanho = 8, $maiusculas = true, $numeros = true, $simbolos = false)
{
//$lmin = 'abcdefghijklmnopqrstuvwxyz';
$lmai = 'ABCDEFGHJKLMNPQRSTUVWXYZ';
$num = '123456789';
//$simb = '@#$';
$retorno = '';
$caracteres = '';
$caracteres .= $lmin;
if ($maiusculas) $caracteres .= $lmai;
if ($numeros) $caracteres .= $num;
if ($simbolos) $caracteres .= $simb;
$len = strlen($caracteres);
for ($n = 1; $n <= $tamanho; $n++) {
$rand = mt_rand(1, $len);
$retorno .= $caracteres[$rand-1];
}
return $retorno;
}
										
										
											if ($tipoVenda == "T"){
												$andCreditos = " "; 
											}else{
												$andCreditos = "AND B.NUM_CARTAO != 0 "; 
											}

											$sql = "
														
									SELECT count(*)
									 as contador 
									FROM VENDAS A  FORCE INDEX (COD_UNIVEND,COD_CLIENTE,COD_STATUSCRED,DAT_CADASTR)  
									INNER JOIN CLIENTES B ON A.COD_CLIENTE=B.COD_CLIENTE 
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
											
											
											$sql = "  SELECT  A.COD_VENDA, 
													   A.COD_VENDAPDV, 
													   A.COD_MAQUINA, 
													   A.COD_VENDEDOR, 
													   A.COD_CUPOM, 
													   B.COD_CLIENTE, 
													   B.NOM_CLIENTE, 
													   B.NUM_CARTAO, 
													   D.NOM_FANTASI, 
													   A.DAT_CADASTR, 
													   A.VAL_TOTVENDA,
													   C.NOM_USUARIO AS VENDEDOR, 
													   E.NOM_USUARIO AS OPERADOR, 
													   F.DES_TOKEM, G.NOM_ENTIDAD 
													   
													FROM VENDAS A  FORCE INDEX (COD_UNIVEND,COD_CLIENTE,COD_STATUSCRED,DAT_CADASTR)  
													INNER JOIN CLIENTES B ON A.COD_CLIENTE=B.COD_CLIENTE 
													LEFT JOIN webtools.USUARIOS C ON C.COD_USUARIO = A.COD_VENDEDOR 
													LEFT JOIN webtools.UNIDADEVENDA D ON D.COD_UNIVEND = A.COD_UNIVEND 
													LEFT JOIN webtools.USUARIOS E ON E.COD_USUARIO = A.COD_USUCADA 
													LEFT JOIN tokem F ON F.COD_PDV = A.cod_vendapdv 
													LEFT JOIN entidade G ON G.COD_ENTIDAD=B.COD_ENTIDAD 
													WHERE 
													DATE_FORMAT(A.DAT_CADASTR, '%Y-%m-%d') >= '$dat_ini' 
													  AND DATE_FORMAT(A.DAT_CADASTR, '%Y-%m-%d') <= '$dat_fim' 
													  AND A.COD_EMPRESA = $cod_empresa
													  AND A.COD_UNIVEND IN($lojasSelecionadas)
                                                      AND A.COD_STATUSCRED in (1,2,3,4,5,7,8) 
													  $andCreditos
													  order by  A.DAT_CADASTR desc  limit $inicio,$itens_por_pagina 												  
													  ";
											
											//fnEscreve($sql);
											
											$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
											
											$countLinha = 1;
											while ($qrListaVendas = mysqli_fetch_assoc($arrayQuery))
											  {
												if ($countLinha == 1){
													$vendaIni = $qrListaVendas['DAT_CADASTR'];													
												}
												
												$totalVenda = $totalVenda + $qrListaVendas['VAL_TOTVENDA'];
							
												$sqlToken="select 
															itemvenda.COD_VENDA,								
															itemvenda.DES_PARAM1,
															itemvenda.DES_PARAM2,
															tokem.des_tokem,
															tokem.COD_PDV,
															tokem.cod_cliente,
															max(if(itemvenda.DES_PARAM2=tokem.des_tokem,'S','N')) temToken
															from itemvenda 
															left join tokem on itemvenda.DES_PARAM2=tokem.des_tokem
															where 
															cod_venda='".$qrListaVendas['COD_VENDA']."' limit 1 ";
														
												$tokenExec=mysqli_query(connTemp($cod_empresa,''),$sqlToken);
												$queryToken=mysqli_fetch_assoc($tokenExec);
												//fnEscreve($sqlToken);
												/*
												echo "<pre>";
												print_r($queryToken);
												echo "</pre>";
												*/
												
												$colunaEspecial = $queryToken['DES_PARAM2'];
												if($queryToken['temToken']=='S')
												{
													if($qrListaVendas['COD_VENDAPDV'] == $queryToken['COD_PDV']){
														$temToken = '<i class="fa fa-check text-success" aria-hidden="true"></i>';
														$statusToken = "Token válido";
													}elseif ($qrListaVendas['NUM_CARTAO'] == $queryToken['cod_cliente']) {
														$temToken = '<i class="fa fa-times text-danger" aria-hidden="true"></i>';
														$statusToken = "Token já utilizado";
														
													}else {
														$temToken = '<i class="fa fa-times text-danger" aria-hidden="true"></i>';
														$statusToken = "Token inválido";
														}

													if ($qrListaVendas['NUM_CARTAO'] != $queryToken['cod_cliente'] ){
															//$temToken = '<i class="fa fa-times-circle-o text-danger" aria-hidden="true"></i>';
															$temToken = '<i class="fa fa-times text-danger" aria-hidden="true"></i>';
															$statusToken = "Token pertence a outro usuario";
													}
													
												}elseif (!empty($qrListaVendas['NUM_CARTAO']) &&
														($queryToken['des_tokem'] != $queryToken['DES_PARAM2'])) {
															$temToken = '<i class="fa fa-lock text-danger" aria-hidden="true"></i>';
															$statusToken = "Token inexistente";
                                                                                                }else {
													$temToken = '<i class="fa fa-times text-danger" aria-hidden="true"></i>';
													
																if (!empty($queryToken['DES_PARAM1'])){
																//$temToken = '<i class="fa fa-times text-danger" aria-hidden="true"></i>';
																$temToken = '<i class="fa fa-unlock-alt text-warning" aria-hidden="true"></i>';
																$statusToken = "Token não informado";
																} else {$statusToken = "";}
										                }
												
                                                                                                
												if ($qrListaVendas['COD_CLIENTE'] == 58272) {													
													$temToken = ""; }
												
												if (($qrListaVendas['COD_CLIENTE'] == 58272) and (!empty($queryToken['DES_PARAM1'])) ) {													
													$temToken = '<i class="fa fa-exclamation-triangle text-danger" aria-hidden="true"></i>';
													$statusToken = "Cliente não cadastrado"; } 
													
												?>
													<tr style="background-color: #fff;" class="abreDetail_<?php echo $qrListaVendas['COD_UNIVEND']; ?>">
													  <td><?php echo $qrListaVendas['COD_VENDA']; ?> </td>
													  <td><a href="action.do?mod=<?php echo fnEncode(1024); ?>&id=<?php echo fnEncode($cod_empresa); ?>&idC=<?php echo fnEncode($qrListaVendas['COD_CLIENTE']); ?>" class="f14" target="_blank"><?php echo $qrListaVendas['NOM_CLIENTE']; ?></a></td>
													  <td><small><?php echo $qrListaVendas['NOM_ENTIDAD']; ?></small></td>
													  <td><small><?php echo $qrListaVendas['NUM_CARTAO']; ?></small></td>
													  <td><small><?php echo $qrListaVendas['NOM_FANTASI']; ?></small></td>
													  <td><small><?php echo fnDataFull($qrListaVendas['DAT_CADASTR']); ?></small></td>
													  <td class="text-right"><small>R$ <?php echo fnValor($qrListaVendas['VAL_TOTVENDA'],2); ?></small></td>
													  <!--
													  <td class="text-right"><small>R$ <?php echo fnValor($qrListaVendas['VAL_CREDITOS'],2); ?></small></td>
													  <td><small><?php echo fnDataFull($qrListaVendas['DAT_EXPIRA']); ?></small></td>
													  -->
													  <td><small><?php echo $queryToken['DES_PARAM1']; ?></small></td>
													  <td><small><?php echo $qrListaVendas['COD_MAQUINA']; ?></small></td>
													  <td><small><?php echo $qrListaVendas['COD_VENDEDOR']; ?></small></td>
													  <td><small><?php echo $queryToken['DES_PARAM2']; ?> </small></td>
													  <td class="text-center"><small><?php echo $temToken; ?></small></td>
													  <td class="text-center"><small><?php echo $statusToken; ?></small></td>
													</tr>
												<?php
												
											  $vendaFim = $qrListaVendas['DAT_CADASTR'];
											  $countLinha++;	
											  }			
			
										?>	
										
									
										</tbody>

										<tfoot>
											<tr>
											  <th>
											  <?php echo $countLinha-1; ?>
											  </th>
											  <th class="" colspan="5">											  
											  </th>
											  <th class="text-right" width="100">
											  R$ <?php echo fnValor($totalVenda,2); ?>
											  </th>
											  <th class="" colspan="100">
											  </th>
											</tr>
											<tr>
											  <th class="" colspan="100">
												<center><ul id="paginacao" class="pagination-sm"></ul></center>
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
												
						<input type="hidden" name="tipoVenda" id="tipoVenda2" value="C" />
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
			
			var numPaginas = <?php echo $numPaginas; ?>;
			if(numPaginas != 0){
				carregarPaginacao(numPaginas);
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
				

		});	

		function reloadPage(idPage) {
			$.ajax({
				type: "POST",
				url: "relatorios/ajxRelTokenDuque.do?opcao=paginar&id=<?php echo fnEncode($cod_empresa); ?>&idPage="+idPage+"&itens_por_pagina=<?php echo $itens_por_pagina; ?>",
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
   