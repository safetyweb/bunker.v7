
<?php
	
	//echo fnDebug('true');
	
	$hashLocal = mt_rand();	
	
	//inicialização de variáveis
	$hoje = fnFormatDate(date("Y-m-d"));
	$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 1 days')));
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
		$sql = "SELECT COD_EMPRESA, NOM_EMPRESA FROM empresas where COD_EMPRESA = '".$cod_empresa."' ";
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
	//faz pesquisa por revenda (geral)
	if ($cod_univend == "9999"){$temUnivend = "N";} else {$temUnivend = "S";}
	
	//fnMostraForm();
	//fnEscreve($dat_ini);
	//fnEscreve($dat_fim);
	
?>

<style>

/* TILES */
.tile {
  width: 100%;
  float: left;
  margin: 0px;
  list-style: none;
  text-decoration: none;
  font-size: 38px;
  font-weight: 300;
  color: #FFF;
  -moz-border-radius: 5px;
  -webkit-border-radius: 5px;
  border-radius: 5px;
  padding: 10px;
  margin-bottom: 20px;
  min-height: 100px;
  position: relative;
  border: 1px solid #D5D5D5;
  text-align: center;
}
.tile.tile-valign {
  line-height: 75px;
}
.tile.tile-default {
  background: #FFF;
  color: #656d78;
}
.tile.tile-default:hover {
  background: #FAFAFA;
}
.tile.tile-primary {
  background: #33414e;
  border-color: #33414e;
}
.tile.tile-primary:hover {
  background: #2f3c48;
}
.tile.tile-success {
  background: #95b75d;
  border-color: #95b75d;
}
.tile.tile-success:hover {
  background: #90b456;
}
.tile.tile-warning {
  background: #fea223;
  border-color: #fea223;
}
.tile.tile-warning:hover {
  background: #fe9e19;
}
.tile.tile-danger {
  background: #b64645;
  border-color: #b64645;
}
.tile.tile-danger:hover {
  background: #af4342;
}
.tile.tile-info {
  background: #3fbae4;
  border-color: #3fbae4;
}
.tile.tile-info:hover {
  background: #36b7e3;
}
.tile:hover {
  text-decoration: none;
  color: #FFF;
}
.tile.tile-default:hover {
  color: #656d78;
}
.tile .fa {
  font-size: 52px;
  line-height: 74px;
}
.tile p {
  font-size: 14px;
  margin: 0px;
}
.tile .informer {
  position: absolute;
  left: 5px;
  top: 5px;
  font-size: 12px;
  color: #FFF;
  line-height: 14px;
}
.tile .informer.informer-default {
  color: #FFF;
}
.tile .informer.informer-primary {
  color: #33414e;
}
.tile .informer.informer-success {
  color: #95b75d;
}
.tile .informer.informer-info {
  color: #3fbae4;
}
.tile .informer.informer-warning {
  color: #fea223;
}
.tile .informer.informer-danger {
  color: #b64645;
}
.tile .informer .fa {
  font-size: 14px;
  line-height: 16px;
}
.tile .informer.dir-tr {
  left: auto;
  right: 5px;
}
.tile .informer.dir-bl {
  top: auto;
  bottom: 5px;
}
.tile .informer.dir-br {
  left: auto;
  top: auto;
  right: 5px;
  bottom: 5px;
}
/* EOF TILES */

</style>
		
					<div class="push30"></div> 
					
					<div class="row">				
					
						<div class="col-md12 margin-bottom-30">
							<!-- Portlet -->
							<div class="portlet portlet-bordered">
								<div class="portlet-title">
									<div class="caption">
										<i class="glyphicon glyphicon-calendar"></i>
										<span class="text-primary"> <?php echo $NomePg; ?></span>
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

										<div class="row">
										
													<?php	
														$sql = "select count(*) as totClientes from vw_mk_condicao_preco";
														$qrListaVwDuque = mysqli_fetch_assoc(mysqli_query($connDUQUE->connDUQUE(),$sql)) or die(mysqli_error());
														$totClientes = $qrListaVwDuque['totClientes']; 
														
														$sql = "select max(dt_alteracao) as maxData from vw_mk_condicao_preco";
														$qrListaVwDuque =  mysqli_fetch_assoc(mysqli_query($connDUQUE->connDUQUE(),$sql)) or die(mysqli_error());
														$maxData = $qrListaVwDuque['maxData']; 
													?>
											
											<div class="col-md-3">                        
												<a href="#" class="tile tile-success">
													<?php echo $totClientes;?> 
													<p>Acesso View Rede Duque</p>
													<div class="informer informer-default"><small>Max. Update: </small><?php echo fnDataFull($maxData);?></div>
													<div class="informer informer-success dir-tr"></div>
												</a>                        
											</div>
											
											<div class="col-md-3">                        
												<a href="#" class="tile tile-danger">
													0
													<p>Integração Bunker</p>
													<div class="informer informer-default"><small>Update: </small><?php echo fnFormatDate(date("Y-m-d")); ?></div>
													<div class="informer informer-success dir-tr"></span></div>
												</a>                        
											</div>
											
										</div>
										
										<div class="push20"></div>
										
									
										<div class="row">
															
											<div class="col-md-12" id="div_Produtos">
  
												<div class="push20"></div>
												
												<table class="table table-bordered table-hover  ">
												
												  <thead>
													<tr>
													  <th><small>id_condicao</small></th>
													  <th><small>id_cliente</small></th>
													  <th><small>id_produto</small></th>
                                                                                                          <th><small>preco</small></th>
                                                                                                          <th><small>data_inicio</small></th>
                                                                                                          <th><small>dt_alteracao</small></th>
                                                                                                         
													</tr>
												  </thead>
													
													<?php	
														$sql = "select * from vw_mk_condicao_preco ";
														
														//fnEscreve($sql);	
														
														$arrayQuery = mysqli_query($connDUQUE->connDUQUE(),$sql) or die(mysqli_error());
														
														$countLinha = 1;
														while ($qrListaVwDuque = mysqli_fetch_assoc($arrayQuery))
														  {														
															?>
																<tr style="background-color: #fff;">
																  <td><?php echo $qrListaVwDuque['id_condicao']; ?></td>
																  <td><?php echo $qrListaVwDuque['id_cliente']; ?></td>
                                                                                                                                  <td><?php echo $qrListaVwDuque['id_produto']; ?></td>
                                                                                                                                  <td><?php echo $qrListaVwDuque['preco']; ?></td>
                                                                                                                                  <td><?php echo $qrListaVwDuque['data_inicio']; ?></td>
                                                                                                                                  <td><?php echo fnDataFull($qrListaVwDuque['dt_alteracao']); ?></td>
																</tr>
															<?php
															
														  $countLinha++;	
														  }			

													//fnEscreve($countLinha-1);				
													?>	
												
													</tbody>
												</table>
																								
											</div>
											
										</div>
																
										<input type="hidden" name="NOVO_CLIENTE" id="NOVO_CLIENTE" value="">
										<input type="hidden" name="REFRESH_CLIENTE" id="REFRESH_CLIENTE" value="N">
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
		
	</script>	
   