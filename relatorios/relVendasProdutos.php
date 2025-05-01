
<?php
	
	//echo fnDebug('true');
	
	$hashLocal = mt_rand();	
	
	//inicialização de variáveis
	$hoje = fnFormatDate(date("Y-m-d"));
	//$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 30 days')));
	//$hoje = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 1 days')));
	$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 2 days')));
	$qtd_produto = 10;
	$cod_persona = 0;
	
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
			$dat_ini = fnDataSql($_POST['DAT_INI']);
			$dat_fim = fnDataSql($_POST['DAT_FIM']);
			$qtd_produto = fnLimpaCampoZero($_POST['QTD_PRODUTO']);			
			$cod_persona = fnLimpaCampoZero($_POST['COD_PERSONA']);			

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
	
	//busca revendas do usuário
	include "unidadesAutorizadas.php"; 
	
	//fnMostraForm();
	//fnEscreve($cod_cliente);
	
?>
		
					<div class="push30"></div> 
					
					<div class="row">
                                            
                                            <form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
					
						<div class="col-md12 margin-bottom-30">
							<!-- Portlet -->
							<div class="portlet portlet-bordered">
								<div class="portlet-title">
									<div class="caption">
										<i class="fal fa-terminal"></i>
										<span class="text-primary"> <?php echo $NomePg; ?> / <?php echo $nom_empresa;?></span>
									</div>
									
									<?php 
									$formBack = "1015";
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
										
									<div class="push30"></div> 
			
									<div class="login-form">
																
										<fieldset>
											<legend>Filtros</legend> 
											
												<div class="row">
													
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
									
										<div class="row">
															
											<div class="col-md-12" id="div_Produtos">
  
												<div class="push20"></div>
												
												<table class="table table-bordered table-hover tablesorter">
												
												  <thead>
													<tr>
													  <th><small>Produto</small></th>
													  <th class="text-center"><small>Cód.</small></th>
													  <th class="text-center"><small>Cód. Ext.</small></th>
													  <th class="text-right"><small>Tot. Vendas</small></th>
													  <th class="text-right"><small>Tot. Vendas Fidel.</small></th>
													  <th class="text-right"><small>Tot. Resgate</small></th>
													  <th class="text-right"><small>Tot. Desconto</small></th>
													  <th class="text-right"><small>Tot. Ven. Desconto</small></th>
													  <th class="text-right"><small>Cred. Gerados</small></th>
													</tr>
												  </thead>
													
													<?php	
					
														// Filtro por Grupo de Lojas
														include "filtroGrupoLojas.php";

														// $lojasSelecionadas = str_replace(",", "|", $lojasSelecionadas);
															   
														$sql = "SELECT
																TMPITM.VAL_TOT_VENDA,
																TMPITM.VAL_TOTVENDA_CASH,
																TMPITM.VAL_TOT_RESGATADO,
																TMPITM.VAL_TOTVENDA_DESCONTO,
																TMPITM.VAL_TOT_DESCONTO,
																 IFNULL(TRUNCATE(sum(CRE.VAL_CREDITO),2),0) CREDITOS_GERADOS,
																TMPITM.DES_PRODUTO,
																TMPITM.COD_PRODUTO,
																TMPITM.COD_EXTERNO
																FROM (
																					SELECT 
																					      COD_ITEMVEN, 
																					      PC.COD_CATEGOR,
																							TRUNCATE(SUM(ITM.VAL_TOTITEM),2) VAL_TOT_VENDA,							
																							TRUNCATE(SUM(CASE WHEN ITM.DES_PARAM1 ='None' THEN ITM.VAL_TOTITEM ELSE '0.00' END),2) VAL_TOTVENDA_CASH,
																							TRUNCATE(SUM(ITM.VAL_RESGATE),2) VAL_TOT_RESGATADO,					      
																							TRUNCATE(SUM(CASE WHEN ITM.DES_PARAM1 !='None' THEN ITM.VAL_TOTITEM ELSE '0.00' END),2) VAL_TOTVENDA_DESCONTO,					      
																						   TRUNCATE(SUM(ITM.VAL_DESCONTO1),2) VAL_TOT_DESCONTO,						   
																							PC.DES_PRODUTO,
																						   ITM.COD_PRODUTO,
																							ITM.COD_EXTERNO,
																						   GROUP_CONCAT( DISTINCT  ITM.COD_ITEMVEN  ORDER BY  ITM.COD_ITEMVEN ASC SEPARATOR ',')  COD_ITEMVENDAS 
																						  ,GROUP_CONCAT( DISTINCT VEN.COD_UNIVEND SEPARATOR ',')  COD_UNIDADES
																						from itemvenda ITM
																					   INNER JOIN produtocliente PC ON PC.COD_PRODUTO=ITM.COD_PRODUTO  
																				      INNER JOIN vendas VEN ON ITM.COD_VENDA=VEN.COD_VENDA  AND   VEN.COD_UNIVEND IN ($lojasSelecionadas) 
																				 	   WHERE DATE(ITM.DAT_CADASTR) BETWEEN '$dat_ini' AND '$dat_fim'
																					      AND PC.COD_CATEGOR    
																						-- AND ITM.COD_PRODUTO IN ('423981','453875','423923','455978','423921','423919','423920','430852','424211','423937','454007') 
																					  	 AND ITM.COD_EMPRESA=$cod_empresa
																					   GROUP BY ITM.COD_PRODUTO
																)TMPITM
																 LEFT JOIN creditosdebitos CRE ON CRE.tip_credito='C' 
																 AND CRE.cod_statuscred IN (0,1,2,3,4,5,7,8,9) 
																 AND CONCAT(',', CRE.COD_ITEMVEN, ',')  REGEXP ',(21912212|21912344|21912345|21912407),'
																 -- FIND_IN_SET(CRE.COD_ITEMVEN, TMPITM.COD_ITEMVENDAS)
																 GROUP BY TMPITM.COD_PRODUTO";	
															
														fnEscreve($sql);
														// $arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
														
														// $countLinha = 1;
														// $totalUnit = 0;
														
														// while ($qrListaVendas = mysqli_fetch_assoc($arrayQuery))
														//   {

														// 	?>	
														// 		<tr>
														// 		  <td><small><?php echo $qrListaVendas['DES_PRODUTO']; ?></small></td>
														// 		  <td class="text-center"><small><?php echo $qrListaVendas['COD_PRODUTO']; ?></small></b></td>
														// 		  <td class="text-center"><small><?php echo $qrListaVendas['COD_EXTERNO']; ?></small></b></td>
														// 		  <td class="text-right"><small>R$ </small><small><?php echo fnValor($qrListaVendas['VAL_TOT_VENDA'],2); ?></small></td>
														// 		  <td class="text-right"><small>R$ </small><small><?php echo fnValor($qrListaVendas['VAL_TOTVENDA_CASH'],2); ?></small></td>
														// 		  <td class="text-right"><small>R$ </small><small><?php echo fnValor($qrListaVendas['VAL_TOT_RESGATADO'],2); ?></small></td>
														// 		  <td class="text-right"><small>R$ </small><small><?php echo fnValor($qrListaVendas['VAL_TOT_DESCONTO'],2); ?></small></td>
														// 		  <td class="text-right"><small>R$ </small><small><?php echo fnValor($qrListaVendas['VAL_TOTVENDA_DESCONTO'],2); ?></small></td>
														// 		  <td class="text-right"><small>R$ </small><small><?php echo fnValor($qrListaVendas['CREDITOS_GERADOS'],2); ?></small></td>
														// 		</tr>
														// 	<?php
															
														// 	$VAL_TOT_VENDA += $qrListaVendas['VAL_TOT_VENDA']; 
														// 	$VAL_TOTVENDA_CASH += $qrListaVendas['VAL_TOTVENDA_CASH']; 
														// 	$VAL_TOT_RESGATADO += $qrListaVendas['VAL_TOT_RESGATADO']; 
														// 	$VAL_TOT_DESCONTO += $qrListaVendas['VAL_TOT_DESCONTO']; 
														// 	$VAL_TOTVENDA_DESCONTO += $qrListaVendas['VAL_TOTVENDA_DESCONTO'];
														// 	$CREDITOS_GERADOS += $qrListaVendas['CREDITOS_GERADOS'];
															
														//   $countLinha++;	
														//   }

														  
													//fnEscreve($countLinha-1);				
													?>	
														<tr>
														  <td colspan="3"></td>
														  <td class="text-right"><small><b>R$ <?=fnValor($VAL_TOT_VENDA,2); ?></b></small></td>
														  <td class="text-right"><small><b>R$ <?=fnValor($VAL_TOTVENDA_CASH,2); ?></b></small></td>
														  <td class="text-right"><small><b>R$ <?=fnValor($VAL_TOT_RESGATADO,2); ?></b></small></td>
														  <td class="text-right"><small><b>R$ <?=fnValor($VAL_TOT_DESCONTO,2); ?></b></small></td>
														  <td class="text-right"><small><b>R$ <?=fnValor($CREDITOS_GERADOS,2); ?></b></small></td>
														</tr>
												
													</tbody>

													<tfoot>														
														<td class="text-left">
															<small>
																<div class="btn-group dropdown left">
																	<button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fal fa-file-excel"></i>
																		&nbsp; Exportar &nbsp;
																		<span class="fas fa-caret-down"></span>
																	</button>
																	<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">													
																		<li><a class="btn btn-sm exportarCSV" style="text-align: left" onclick="exportarCSV(this)" value="N">&nbsp; Exportar</a></li>
																		<!-- <li><a tabindex="-1" href="#">Separated link</a></li> -->
																	</ul>
																</div>
															</small>
														</td>												
													</tfoot>													
													
												</table>						

																								
											</div>
											
										</div>
										<input type="hidden" name="LOJAS" id="LOJAS" value="<?php echo $lojasSelecionadas; ?>" />						
										<input type="hidden" name="NOVO_CLIENTE" id="NOVO_CLIENTE" value="">
										<input type="hidden" name="REFRESH_CLIENTE" id="REFRESH_CLIENTE" value="N">
										<input type="hidden" name="opcao" id="opcao" value="">
										<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
										<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">		
										<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
										<div class="push5"></div> 
										
										
										
									<div class="push50"></div>									
									
									
									</div>								
								
								</div>
							</div>
							<!-- fim Portlet -->
						</div>
                                            </form>
					</div>					
	
					<!-- modal -->									
					<div class="modal fade" id="popModal" tabindex='-1'>
						<div class="modal-dialog" style="">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
									<h4 class="modal-title"></h4>
								</div>
								<div class="modal-body">
									<iframe frameborder="0" style="width: 100%; height: 80%"></iframe>
								</div>		
							</div><!-- /.modal-content -->
						</div><!-- /.modal-dialog -->
					</div><!-- /.modal -->
					
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

		function exportarCSV(btn) {
			log_detalhes = $(btn).attr('value');
			// alert(id);
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
									icon: 'fa fa-check-square',
									content: function(){
										var self = this;
										return $.ajax({
											url: "relatorios/ajxRelProdutosTop.do?opcao=exportar&nomeRel="+nome+"&id=<?php echo fnEncode($cod_empresa); ?>&LOJAS=<?php echo $lojasSelecionadas; ?>&log_detalhes="+log_detalhes, 
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
			}
		
	</script>	
   