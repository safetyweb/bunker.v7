<?php
	
	//echo fnDebug('true');
	
	$hashLocal = mt_rand();	
	
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
			
			$cod_empresa = fnLimpacampoZero($_REQUEST['COD_EMPRESA']);
			$cod_cliente = fnLimpacampoZero($_REQUEST['COD_CLIENTE']);
			$cod_tipmoti = fnLimpacampoZero($_REQUEST['COD_TIPMOTI']);
			
			$num_cartao = fnLimpacampo($_REQUEST['NUM_CARTAO_OLD']);
			$num_cartao_novo = fnLimpacampo($_REQUEST['NUM_CARTAO']);

			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
						
			if ($opcao != ''){
				
					$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
					
					//criticas
					/*	
					select  
					(SELECT NUM_TAMANHO FROM LOTECARTAO A WHERE A.COD_EMPRESA=geracartao.COD_EMPRESA AND A.COD_LOTCARTAO=geracartao.COD_LOTCARTAO) AS NUM_TAMANHO,
					cod_cartao,log_usado,num_cartao,count(*) contador  from geracartao where num_cartao='$cartao'  and cod_empresa=".$row['COD_EMPRESA'];					
					*/
					
					//busca dados da empresa
					$sql = "select LOG_AUTOCAD FROM EMPRESAS WHERE COD_EMPRESA = '".$cod_empresa."' ";
					//fnEscreve($sql);
					$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
					$qrBuscaLOG_AUTOCAD = mysqli_fetch_assoc($arrayQuery);
					$log_autocad = $qrBuscaLOG_AUTOCAD['LOG_AUTOCAD'];
				
					$sql1 = "CALL SP_ALTERA_NUMEROCARTAO(
						'".$cod_cliente."',
						'".$cod_empresa."',
						'".$num_cartao."',
						'".$num_cartao_novo."',
						'".$cod_usucada."',
						'".$cod_tipmoti."',
						'".$log_autocad."',
						'".$opcao."'  
					) ";
						
					//echo $sql1;	
					//fnTestesql(connTemp($cod_empresa,''),$sql1);
					
					$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql1);
					$qrBuscaRetorno = mysqli_fetch_assoc($arrayQuery);
					$mensagem_retorno = $qrBuscaRetorno['mensagem_retorno'];

					//mensagem de retorno
					$msgRetorno = $mensagem_retorno;
					if ($mensagem_retorno != "Alterado com sucesso!"){
						$msgTipo = 'alert-danger';						  
					}else {
						$msgTipo = 'alert-success';						  
					}
					
					
				
			}  

		}
	}
	
	//busca dados url
	if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))){
		//busca dados da empresa
		$cod_empresa = fnDecode($_GET['id']);
		$cod_cliente = fnDecode($_GET['idC']);	
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
	

	//busca dados do cliente
	$sql = "SELECT NOM_CLIENTE, NUM_CARTAO, NUM_CGCECPF, COD_CLIENTE FROM CLIENTES where COD_CLIENTE = '".$cod_cliente."' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
	$qrBuscaCliente = mysqli_fetch_assoc($arrayQuery);
	
	if (isset($arrayQuery)){
		
		$nom_cliente = $qrBuscaCliente['NOM_CLIENTE'];
		$cod_cliente = $qrBuscaCliente['COD_CLIENTE'];
		$num_cartao = $qrBuscaCliente['NUM_CARTAO'];
		$num_cgcecpf = $qrBuscaCliente['NUM_CGCECPF'];

	}else{
				
		$nom_cliente = "";
		$cod_cliente = "";
		$num_cartao = "";
		$num_cgcecpf = "";
			
	}
    	
	//fnMostraForm();
	//fnEscreve($mensagem_retorno);
	
?>

<style>
.chosen-big + div > .chosen-single{
	height: 45px !important;
	line-height: 20px !important;
	padding: 10px 15px !important;
}
</style>
		
					<div class="push30"></div> 
					
					<div class="row">				
					
						<div class="col-md12 margin-bottom-30">
							<!-- Portlet -->
							<div class="portlet portlet-bordered">
								<div class="portlet-title">
									<div class="caption">
										<i class="fal fa-terminal"></i>
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
									
									<?php 
									//menu superior - cliente
									$abaCli = 1112;									
									switch ($_SESSION["SYS_COD_SISTEMA"]) {
										case 14: //rede duque
											include "abasClienteDuque.php";
											break;
										case 13: //sh manager
											include "abasIntegradoraCli.php";
											break;
										default;											
											include "abasClienteConfig.php";
											break;
									}															
									?>									
									
									<div class="push30"></div> 
			
									<div class="login-form">
									
										<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
																	
										<fieldset>
										<legend>Dados Gerais</legend>
										
												<div class="row">
												
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label required">Código do Cliente</label>
                                                            <input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_CLIENTE" id="COD_CLIENTE" value="<?php echo $cod_cliente;?>">
														</div>
													</div>
													
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label required">Empresa</label>
															<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa ?>">
															<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>" required>
														</div>														
													</div>
																		
													<div class="col-md-5">
														<label for="inputName" class="control-label required">Nome do Usuário</label>
														<div class="input-group">
														<span class="input-group-btn">
														<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary btn-sm addBox" data-url="action.php?mod=<?php echo fnEncode(1071)?>&id=<?php echo fnEncode($cod_empresa)?>&pop=true" data-title="Venda Avulsa - Busca Clientes"><i class="fal fa-search" aria-hidden="true" style="padding-top: 3px;" ></i></a>
														</span>
														<input type="text" name="NOM_CLIENTE" id="NOM_CLIENTE" class="form-control input-sm leituraOff" style="border-radius:0 3px 3px 0;" placeholder="Procurar cliente..." value="<?php echo $nom_cliente;?>">
														<input type="hidden" name="COD_CLIENTE" id="COD_CLIENTE" value="<?php echo $cod_cliente;?>" required>
														</div>																
													</div>															
															
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label required">Número do Cartão</label>
                                                            <input type="text" class="form-control input-sm leitura" value="<?php echo $num_cartao;?>" maxlength="50" data-error="Campo obrigatório" required>
															<div class="help-block with-errors"></div>
														</div>
													</div>
													
												</div>
													
										</fieldset>
										
										<div class="push10"></div>
										
																	
										<fieldset>
											<legend>Dados do Cartão</legend> 
											
												<div class="row">
													
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label required">Cartão Atual</label>
															<input type="text" class="form-control leitura" readonly="readonly" name="NUM_CARTAO_OLD" id="NUM_CARTAO_OLD" value="<?php echo $num_cartao ?>" required>
														</div>														
													</div>	
													
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label required">Novo Cartão</label>
															<div class="input-group">
															<span class="input-group-btn">
															<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary btn-sm addBox" data-url="action.php?mod=<?php echo fnEncode(1467)?>&id=<?php echo fnEncode($cod_empresa)?>&opcao=troca&pop=true" data-title="Busca Clientes"><i class="fa fa-search" aria-hidden="true" style="padding-top: 3px;" ></i></a>
															</span>
															<input type="text" name="NUM_CARTAO" id="NUM_CARTAO" readonly maxlength="50" class="form-control input-sm" style="border-radius:0 3px 3px 0;" data-error="Campo obrigatório" required>
															</div>
														</div>														
													</div>
													
													<style>
														.chosen-container-single .chosen-single span {
															height: 44px !important;
														}													
													</style>
													
													<div class="col-md-4">
														<div class="form-group">
															<label for="inputName" class="control-label">Motivo da Troca</label>
																<select data-placeholder="Selecione um motivo da troca" name="COD_TIPMOTI" id="COD_TIPMOTI" class="chosen-select-deselect">
																	<option value="">&nbsp;</option>					
																	<?php																	
																		$sql = "select * from TIPOMOTIVO_CARTAO order by DES_TPMOTIV ";
																		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
																	
																		while ($qrListaMotivo = mysqli_fetch_assoc($arrayQuery))
																		  {													
																			echo"
																				  <option value='".$qrListaMotivo['COD_TIPMOTI']."'>".$qrListaMotivo['DES_TPMOTIV']."</option> 
																				"; 
																			  }											
																	?>	
																</select>	
															<div class="help-block with-errors"></div>
														</div>
													</div>	

													
												</div>
													
										</fieldset>										
										
										<div class="push10"></div>
										<hr>	
										<div class="form-group text-right col-lg-12">

											<button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
											<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-id-card-o" aria-hidden="true"></i>&nbsp; Alterar Cartão</button>

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
					
	
	<script type="text/javascript">
		$(document).ready(function(){
			
			$(".calcula").change(function(){				
				recalcula();
			});
		
			//chosen
			$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
			$('#formulario').validator();
			
			//modal close
			$('.modal').on('hidden.bs.modal', function () {
			  
			  if ($('#REFRESH_CLIENTE').val() == "S"){
				var newCli = $('#NOVO_CLIENTE').val();  
				window.location.href = "action.php?mod=<?php echo fnEncode(1112); ?>&id=<?php echo fnEncode($cod_empresa); ?>&idC="+newCli+" ";
				$('#REFRESH_PRODUTOS').val("N");				
			  }	
			  
			});
			
		});	
		
		function recalcula(){
		
			var valTotal = 0;
			$('.calcula').each(function(index,item){
				if($(item).val() != ""){
					if($(item).attr('id') == "VAL_RESGATE" || $(item).attr('id') == "VAL_DESCONTO" ){
						valTotal = valTotal - limpaValor($(item).val());
					}else{
						valTotal = valTotal + limpaValor($(item).val());
					}				
				}
			 });
			$('#VAL_TOTVENDA').val();				 
			$('#VAL_TOTVENDA').unmask();
			$('#VAL_TOTVENDA').val(valTotal.toFixed(2));				 
			$('#VAL_TOTVENDA').mask("#.##0,00", {reverse: true});
			
		}	
		
		function abreDetail(idVenda){
			RefreshProdutos(<?php echo $cod_empresa; ?>, idVenda);
		}
		
		function RefreshProdutos(idEmp, idVenda) {
			var idItem = $('#abreDetail_'+idVenda);
			
			if (!idItem.is(':visible')){
				$.ajax({
					type: "GET",
					url: "ajxProdutosVenda.php",
					data: { ajx1:idEmp, ajx2:idVenda },
					beforeSend:function(){
						$("#mostraDetail_"+idVenda).html('<div class="loading" style="width: 100%;"></div>');
					},
					success:function(data){
						$("#mostraDetail_"+idVenda).html(data); 
					},
					error:function(){
						$("#mostraDetail_"+idVenda).html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
					}
				});
				
				idItem.show();
				
				$('#cod_venda_'+idVenda).find($(".fa")).removeClass('fa-plus').addClass('fa-minus');
			}else{
				idItem.hide();
				$('#cod_venda_'+idVenda).find($(".fa")).removeClass('fa-minus').addClass('fa-plus');
			}
		}	
		
		function RefreshProdutosExc(idEmp, idOrc, tipo, idItem) {
			$.ajax({
				type: "GET",
				url: "ajxListaOrcamento.php",
				data: { ajx1:idEmp, ajx2:idOrc, ajx3:tipo, ajx4: idItem },
				beforeSend:function(){
					$('#div_Produtos').html('<div class="loading" style="width: 100%;"></div>');
				},
				success:function(data){
					$("#div_Produtos").html(data);
					//recalcula();					
				},
				error:function(){
					$('#div_Produtos').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
				}
			});		
		}
						
		function retornaForm(index){
					
		}

	
	</script>	
