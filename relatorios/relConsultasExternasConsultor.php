<?php
	
	//echo fnDebug('true');
	
	$hashLocal = mt_rand();	
	 
	//inicialização de variáveis
	$hoje = fnFormatDate(date("Y-m-d"));
	$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 30 days')));
	//$hoje = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 1 days')));
	//$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 2 days')));
	$cod_univend = "9999";
	   
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
		$sql = "SELECT COD_EMPRESA, NOM_EMPRESA FROM empresas where   LOG_ATIVO='S' AND COD_EMPRESA = '".$cod_empresa."' ";
		//fnEscreve($sql);
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
		
		if (isset($arrayQuery)){
			$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
			$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
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

	//busca revendas do usuário
	include "unidadesAutorizadas.php"; 
	
	$hor_ini = " 00:00:00";
	$hor_fim = " 23:59:59";
	
	//fnMostraForm();
	//fnEscreve($cod_cliente);
	
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
															<label for="inputName" class="control-label required">Consultor</label>
															<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_USUARIO" id="NOM_USUARIO" value="<?php echo $_SESSION["SYS_NOM_USUARIO"]; ?>">
														</div>														
													</div>
													
													<div class="col-md-3" style="display: none;">
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
										
										<div class="push20"></div>
										
									
										<div class="row">
															
											<div class="col-md-12" id="div_Produtos">
  
												<div class="push20"></div>
												
												<table class="table table-bordered table-hover  ">
												
												  <thead>
													<tr>
													  <th></th>
													  <th class="text-center"><small>Cadastros Efetivados </small></th>
													  <th class="text-center"><small>Cadastros s/ Loja </small></th>
													  <th class="text-center"><small>Total Cobrança </small></th>
													  <th class="text-center"><small>Consultas Externas Únicas </small></th>
													  <th class="text-center"><small>Consultas Externas Múltiplas </small></th>
													  <th class="text-center"><small>Valor Unitário (R$)</small></th>
													  <th class="text-center"><small>Valor Total (R$)</small></th>
													</tr>
												  </thead>
													
													<?php	
														$sql = "select A.CONSULTA_ORIGINAL as COD_EMPRESA,
																B.NOM_FANTASI
																from log_consulta A 
																LEFT JOIN EMPRESAS B ON B.COD_EMPRESA = A.CONSULTA_ORIGINAL
																where 
																A.DATA_HORA BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' AND
																A.CONSULTA_ORIGINAL IN (".$_SESSION["SYS_COD_MULTEMP"].")
                                                                                                                                AND B.LOG_ATIVO='S'    
																GROUP BY A.CONSULTA_ORIGINAL 
																ORDER BY B.NOM_FANTASI 
																";
																
													    //fnEscreve($sql);
														$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
														
														$countLinha = 1;
														while ($qrListaConsultasGeral = mysqli_fetch_assoc($arrayQuery))
														  {
														
														$empresaLoop = $qrListaConsultasGeral['COD_EMPRESA'];
														
														/*$sql2 = "SELECT sum(Q.NUM_QTD) AS qtd FROM log_cpf l 
													            INNER JOIN log_consulta Q ON Q.cpf = l.cpf 
																WHERE  Q.consulta_outras = $empresaLoop 
																AND Q.data_hora BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' 
																AND Q.NUM_QTD <=2
                                                                                                                                GROUP BY Q.CONSULTA_OUTRAS
																";*/
														$sql2 = "SELECT sum(Q.NUM_QTD)  AS qtd FROM log_consulta Q
																WHERE  Q.consulta_outras = $empresaLoop 
																AND Q.data_hora BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59' 
																AND Q.NUM_QTD <=2
                                                                                                                                GROUP BY Q.CONSULTA_OUTRAS
																";		
														//fnEscreve($sql2);
														$arrayQuery2 = mysqli_query($connAdm->connAdm(),$sql2);
														$CONSULTASEXT=mysqli_fetch_assoc($arrayQuery2);
                                                                                                                $CONSULTASEXT = $CONSULTASEXT['qtd'];//mysqli_num_rows($arrayQuery2);	
														
														$sql5 = "SELECT SUM(NUM_QTD) AS TOTAL_CADASTRO_MULT 
																FROM log_consulta Q 
																WHERE  Q.consulta_outras = $empresaLoop
																AND Q.data_hora BETWEEN '$dat_ini 00:00:00' AND '$dat_fim 23:59:59'																	
																";
																
														//fnEscreve($sql5);
														$arrayQuery5 = mysqli_query($connAdm->connAdm(),$sql5) or die(mysqli_error());
														$qrTotalCadastroMult = mysqli_fetch_assoc($arrayQuery5);
														$TOTAL_CADASTRO_MULT = $qrTotalCadastroMult['TOTAL_CADASTRO_MULT']; 
														
														$sql3 = "SELECT count(1) as TOTAL_CADASTRO 
																from clientes where cod_empresa = $empresaLoop 
																AND DAT_CADASTR between '$dat_ini 00:00' AND '$dat_fim 23:59:59' 
																AND COD_UNIVEND != 0
																AND COD_EXCLUSA =0
																";
																
														//fnEscreve($sql3);
														//fnTestesql($connAdm->connAdm(),$sql3);
														$arrayQuery3 = mysqli_query(connTemp($empresaLoop,''),$sql3) or die(mysqli_error()); 
														$qrTotalCadastro = mysqli_fetch_assoc($arrayQuery3);
														$TOTAL_CADASTRO = $qrTotalCadastro['TOTAL_CADASTRO']; 
														
														$sql4 = "SELECT count(1) as LOJAS_AVULSAS 
																from log_cpf PF 
																where PF.cod_empresa = $empresaLoop 
																AND PF.DATA_HORA between '$dat_ini 00:00:00' and '$dat_fim 23:59:59' 
																AND PF.ID_LOJA =0
																";
																
														//fnEscreve($sql4);
														//fnTestesql($connAdm->connAdm(),$sql3);
														$arrayQuery4 = mysqli_query($connAdm->connAdm(),$sql4) or die(mysqli_error());
														$qrTotalLoja0 = mysqli_fetch_assoc($arrayQuery4);
														$LOJAS_AVULSAS = $qrTotalLoja0['LOJAS_AVULSAS'];  
                                                                                                                                
															switch ($qrListaConsultasGeral['COD_EMPRESA']) {
																case 42: //ultrafarma
																	$custo = 0.09;
																	break;
																case 52: //drogamix
																	$custo = 0.06;
																	break;
																case 46: //drog assis
																	$custo = 0.06;
																	break;
																case 31: //droga vema
																	$custo = 0.06;
																	break;
																case 62: //drog rio branco
																	$custo = 0.09;
																	break;
																case 32: //davimed
																	$custo = 0.09;
																	break;
																case 22: //bifarma
																	$custo = 0.03;
																	break;
																case 20: //drogaleste
																	$custo = 0.03;
																	break;
																case 60: //D&F
																	$custo = 0.09;
																	break;
																case 57: //são joão
																	$custo = 0.09;
																	break;
																case 82: //kairu
																	$custo = 0.09;
																	break;
																case 83: //feitosa
																	$custo = 0.09;
																	break;
																case 85: //farmamed
																	$custo = 0.09;
																	break;
																case 73: //giga popular
																	$custo = 0.09;
																	break;
																case 49: //jb
																	$custo = 0.09;
																	break;
																case 64: //jb
																	$custo = 0.09;
																	break;
																case 80: //new Big
																	$custo = 0.09;
																	break;
																case 80: //new Big
																	$custo = 0.09;
																	break;
																case 12: //milagrosa
																	$custo = 0.06;
																	break;
																case 37: //vida e saude
																	$custo = 0.09;
																	break;
																case 54: //ultrafitness
																	$custo = 0.09;
																	break;
																case 45: //uv line
																	$custo = 0.00;
																	break;
																case 81: //cg farma
																	$custo = 0.00;
																	break;
																case 61: //aliança
																	$custo = 0.09;
																	break;
																case 69: //aliança
																	$custo = 0.09;
																	break;
																default;											
																	$custo = 0.00;
																	break;
															}													  	   
															
															//$custoTotal = $qrListaConsultasGeral['TOTAL'] * $custo;
															$custoTotal = ($TOTAL_CADASTRO + $LOJAS_AVULSAS) * $custo;
															$somaTotal = $somaTotal + $custoTotal;
															$consultaTotal = $somaTotal + $custoTotal;
															
															if ($LOJAS_AVULSAS > 0){
																$erroCad = "text-danger";
															} else {
																$erroCad = "";	
															}
															
															?>	
																<tr>
																  <td><b><a href="action.do?mod=<?php echo fnEncode(1210); ?>&id=<?php echo fnEncode($qrListaConsultasGeral['COD_EMPRESA']); ?>" target="_blank"><?php echo $qrListaConsultasGeral['NOM_FANTASI']; ?></b></td>
                                                                  <td class="text-center"><b><?php echo fnValor($TOTAL_CADASTRO,0); ?></b></td>
                                                                  <td class="text-center <?php echo $erroCad; ?>"><b><?php echo fnValor($LOJAS_AVULSAS,0); ?></b></td>
                                                                  <td class="text-center"><b><?php echo fnValor(($TOTAL_CADASTRO + $LOJAS_AVULSAS),0); ?></b></td>
                                                                  <td class="text-center"><small><?php echo fnValor($CONSULTASEXT,0); ?></small></td>
                                                                  <td class="text-center"><small><?php echo fnValor($TOTAL_CADASTRO_MULT,0); ?></small></td>
                                                                  <td class="text-center"><small><small>R$</small> <?php echo fnValor($custo,2); ?></small></td>
                                                                  <td class="text-center"><small><small>R$</small> <?php echo fnValor($custoTotal,2); ?></small></td>
																</tr>
															<?php
															  
														  $countLinha++;	
														  }
														  
													?>
													
													</tbody>
													
													<tfoot>
														<!-- <tr>
															<th colspan="100">
																<a class="btn btn-info btn-sm exportarCSV">Exportar &nbsp;<i class="fa fa-file-excel-o" aria-hidden="true"></i></a>
															</th>
														</tr> -->													
														<tr>
															<th colspan="6"></th>
															<th class="text-center">
															<small>R$</small> <?php echo fnValor($somaTotal,2); ?></small>
															</th>
														</tr>														
													</tfoot>
													
												</table>
																								
											</div>
											
										</div>
																
										<input type="hidden" name="NOVO_CLIENTE" id="NOVO_CLIENTE" value="">
										<input type="hidden" name="REFRESH_CLIENTE" id="REFRESH_CLIENTE" value="N">
										<input type="hidden" name="opcao" id="opcao" value="">
										<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
										<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">		
										
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
											url: "relatorios/ajxRelConsultasExternasConsultor.do?opcao=exportar&nomeRel="+nome+"&id=<?php echo fnEncode($cod_empresa); ?>",
											data: $('#formulario').serialize(),
											method: 'POST'
										}).done(function (response) {
											self.setContentAppend('<div>Exportação realizada com sucesso.</div>');
											var fileName = '<?php echo $cod_empresa; ?>_' + nome + '.csv';
											SaveToDisk('media/excel/' + fileName, fileName);
											//console.log(response);
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
   