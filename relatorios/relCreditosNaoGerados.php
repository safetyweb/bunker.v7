<?php
	
	//echo fnDebug('true');
	
	$hashLocal = mt_rand();	
	
	//inicialização de variáveis
	//$hoje = fnFormatDate(date("Y-m-d"));
	$hoje = fnFormatDate(date('Y-m-d', strtotime($hoje. '- 1 days')));
	//$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 30 days')));
	$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 2 days')));
	
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
			// $cod_univend = $_POST['COD_UNIVEND'];
			// $cod_grupotr = $_REQUEST['COD_GRUPOTR'];	
			// $cod_tiporeg = $_REQUEST['COD_TIPOREG'];
			// $dat_ini = fnDataSql($_POST['DAT_INI']);
			// $dat_fim = fnDataSql($_POST['DAT_FIM']);

			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
						
			if ($opcao != ''){
				
				$sql = " CALL SP_REPROCESSA_CREDITO_NOTURNO('CAD')";
				mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
				$msgRetorno = "Reprocessamento feito com <strong>sucesso!</strong>";
				$msgTipo = 'alert-success';
				
			}  

		}
	}
	
	//busca dados url
	if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))){
		//busca dados da empresa
		$cod_empresa = fnDecode($_GET['id']);
		$sql = "SELECT COD_EMPRESA, NOM_FANTASI, COD_CLIENTE_AV FROM empresas where COD_EMPRESA = '".$cod_empresa."' ";
		//fnEscreve($sql);
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
		
		if (isset($arrayQuery)){
			$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
			$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
			$cod_cliente_av = $qrBuscaEmpresa['COD_CLIENTE_AV'];
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


	//rotina de controle de acessos por módulo
	include "moduloControlaAcesso.php";	

	if(fnControlaAcesso("1024",$arrayParamAutorizacao) === true) { 
		$autoriza = 1;
	}else{
		$autoriza = 0;
	}
	
	//fnMostraForm();
	//fnEscreve($cod_cliente_av);
		
?>
		
					<div class="push30"></div> 
					
					<div class="row">				
					
						<div class="col-md12 margin-bottom-30">
							<!-- Portlet -->
							<div class="portlet portlet-bordered">
								<div class="portlet-title">
									<div class="caption">
										<i class="fal fa-terminal"></i>
										<span class="text-primary"> <?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
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
									
										<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
										
										<div class="push20"></div>
									
										<div class="row">
															
											<div class="col-md-12" id="div_Produtos">
												
												<table class="table table-bordered table-hover tablesorter">
												
												  <thead>
													<tr>
													  <th><small>Nome do Cliente</small></th>
													  <th><small>Cod. do Cliente</small></th>
													  <th class="text-center"><small>Data de Nascimento</small></th>
													  <th class="text-center"><small>Compras</small></th> 
													  <th class="text-center"><small>Menor Data</small></th>
													  <th class="text-center"><small>Maior Data</small></th>
													  <th class="text-center"><small>Valor das Vendas</small></th>
													</tr>
												  </thead>

												  <tbody>
													
													<?php
                                                           $ARRAY_UNIDADE1=array(
														   'sql'=>"select COD_UNIVEND,cod_empresa,nom_fantasi,NOM_UNIVEND from unidadevenda where cod_empresa=$cod_empresa",
														   'cod_empresa'=>$cod_empresa,
														   'conntadm'=>$connAdm->connAdm(),
														   'IN'=>'N',
														   'nomecampo'=>'',
														   'conntemp'=>'',
														   'SQLIN'=> ""   
														   );
											                $ARRAY_UNIDADE=fnUnivend($ARRAY_UNIDADE1);

											                // Filtro por Grupo de Lojas
															include "filtroGrupoLojas.php";
																
															$sql = "SELECT DISTINCT 	A.COD_CLIENTE,
																						B.NOM_CLIENTE,
																						B.DAT_NASCIME,
																						COUNT(*) QTD_COMPRAS,
																						MIN(A.DAT_CADASTR) MENOR_DATA,
																						MAX(A.DAT_CADASTR) MAIOR_DATA,
																						SUM(A.VAL_TOTVENDA) VAL_TOTAL
																	FROM VENDAS A, CLIENTES B
																	WHERE 
																	A.COD_EMPRESA=$cod_empresa AND 
																	A.COD_CLIENTE=B.COD_CLIENTE AND 
																	B.COD_CLIENTE <> $cod_cliente_av AND 
																	A.cod_creditou=3 
																	GROUP BY A.COD_CLIENTE
																	ORDER BY SUM(A.VAL_TOTVENDA)  DESC";

																	
														//fnEscreve($sql);
														$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
														
														$countLinha = 1;
														while ($qrListaVendas = mysqli_fetch_assoc($arrayQuery))
														  {
                                                            
															$NOM_ARRAY_UNIDADE=(array_search($qrListaVendas['COD_UNIVEND'], array_column($ARRAY_UNIDADE, 'COD_UNIVEND')));
												
															//monta primeiro cabeçalho
															$loja = $qrListaVendas['COD_UNIVEND'];
															//monta primeira linha
															//fnEscreve($loja);
															$ticketMedio = $qrListaVendas['VAL_TOTFIDELIZ'] / $qrListaVendas['QTD_TOTFIDELIZ'];
															$valorCliente=$qrListaVendas['VAL_TOTFIDELIZ']/$qrListaVendas['QTD_CLIENTE_FIDELIZ'];

															if(isset($qrListaVendas['DAT_NASCIME'])){
	                                                          	$data = $qrListaVendas['DAT_NASCIME'];
	                                                          }else{
	                                                          	$data = '___/___/_______';
	                                                          }
                                                    ?>	
																<tr>
																  	<?php 
																		if($autoriza == 1) { 
																	?>
																  			<td><a href="action.do?mod=<?php echo fnEncode(1024); ?>&id=<?php echo fnEncode($cod_empresa); ?>&idC=<?php echo fnEncode($qrListaVendas['COD_CLIENTE']); ?>" class="f14" target="_blank"><?php echo $qrListaVendas['NOM_CLIENTE']; ?></a></td>
																  	<?php 
																  		}else{ 
																  	?>
																  			<td><?php echo $qrListaVendas['NOM_CLIENTE']; ?></td>
																  	<?php 
																  		} 
																  	?>
																  <td><small><?php echo $qrListaVendas['COD_CLIENTE']; ?></small></td>

																  <td class='text-center dt'>
																  	<a href="#" class="editable-data" 
																	  	data-type='text' 
																	  	data-cod_empresa="<?=$cod_empresa?>" 
																	  	data-title='Editar Data' data-pk="<?php echo $qrListaVendas[COD_CLIENTE]; ?>" 
																	  	data-name="DAT_NASCIME"
																	  	data-count="<?php echo $count; ?>"><small><?=$data?></small>
																  		
																  	</a>
																  </td>

																  <!-- <td class="text-center"><small><?php echo $qrListaVendas['DAT_NASCIME']; ?></small></td> -->
																  <td class="text-center"><small><?php echo fnValor($qrListaVendas['QTD_COMPRAS'],0); ?></small></td>
																  <td class="text-center"><small><?php echo fnDataFull($qrListaVendas['MENOR_DATA']); ?></small></td>
																  <td class="text-center"><small><?php echo fnDataFull($qrListaVendas['MAIOR_DATA']); ?></small></td>
																  <td class="text-center"><small><small>R$</small> <?php echo fnValor($qrListaVendas['VAL_TOTAL'],2); ?></small></td>
																</tr>
															
													<?php
															
															$TOTAL_COMPRAS += $qrListaVendas['QTD_COMPRAS'];
															$TOTAL_GERAL += $qrListaVendas['VAL_TOTAL'];
															
														  $countLinha++;	
														  }
														  
													?>
																</tbody>
																<tr>
																  <td colspan="3"></td>
																  <td class="text-center"><small><b><?php echo fnValor($TOTAL_COMPRAS,0); ?></b></small></td>
																  <td colspan="2"></td>
																  <td class="text-center"><small><b><small>R$ </small><?php echo fnValor($TOTAL_GERAL,2); ?></b></small></td>
																</tr>														  
														  <?php
													//fnEscreve($countLinha-1);				
													?>	
												
													
													
													<!-- <tfoot>
														<tr>
															<th colspan="100">
																
															</th>
														</tr>														
													</tfoot> -->													
													
													
												</table>
																								
											</div>
											
										</div>
																
										<input type="hidden" name="NOVO_CLIENTE" id="NOVO_CLIENTE" value="">
										<input type="hidden" name="REFRESH_CLIENTE" id="REFRESH_CLIENTE" value="N">
										<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?=$cod_empresa?>">
										<input type="hidden" name="AUTORIZA" id="AUTORIZA" value="<?=$autoriza?>" />
										<input type="hidden" name="opcao" id="opcao" value="">
										<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
										<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">		
										<input type="hidden" name="LOJAS" id="LOJAS" value="<?php echo $lojasSelecionadas; ?>">
										
										<div class="push5"></div>

										<hr>	
										<div class="form-group text-right col-lg-12">
											
											  <!-- <button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button> -->
											  <button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-cog" aria-hidden="true"></i>&nbsp;Reprocessar</button>
											  <!-- <button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button> -->
											  <!-- <button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fal fa-times" aria-hidden="true"></i>&nbsp; Excluir</button> -->
											
										</div>

										<!-- <button type="submit" class="btn btn-primary pull-right" name="CAD" id="CAD"><i class="fas fa-cog" aria-hidden="true"></i>&nbsp;Reprocessar</button> -->
										
										</form>
										
									<div class="push50"></div>									
									
									<div class="push"></div>
									
									</div>								
								
								</div>
							</div>
							<!-- fim Portlet -->
						</div>
						
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

			// $("#CAD").click(function(){
			// 	$("#formulario").submit();
			// });
			
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
									icon: 'fal fa-check-square-o',
									content: function(){
										var self = this;
										return $.ajax({
											url: "relatorios/ajxRelConsolidadoMensal.do?opcao=exportar&nomeRel="+nome+"&id=<?php echo fnEncode($cod_empresa); ?>", 
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

		$(function(){

			$('.dt .editable-input .input-sm').mask('99/99/9999');

		    $('.editable-data').editable({ 
		    	emptytext: '___/___/_______',
		        url: 'relatorios/ajxCreditosNaoGerados.php',
        		ajaxOptions:{type:'post'},
        		params: function(params) {
			        params.count = $(this).data('count');
			        params.cod_empresa = $(this).data('cod_empresa');
			        return params;
			    },
        		success:function(data){
					//console.log(data);
				}
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
		
	</script>	
   