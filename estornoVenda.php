
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
			
			$cod_venda = fnLimpacampoZero($_REQUEST['COD_VENDA']);
			$cod_orcamento = fnLimpacampoZero($_REQUEST['COD_ORCAMENTO']);
			$cod_empresa = fnLimpacampoZero($_REQUEST['COD_EMPRESA']);
			$cod_cliente = fnLimpacampoZero($_REQUEST['COD_CLIENTE']);
			$cod_lancamen = fnLimpacampoZero($_REQUEST['COD_LANCAMEN']);
			$cod_ocorren = fnLimpacampoZero($_REQUEST['COD_OCORREN']);
			$cod_univend = fnLimpacampoZero($_REQUEST['COD_UNIVEND']);
			$cod_formapa = fnLimpacampoZero($_REQUEST['COD_FORMAPA']);
			$tem_prodaux = fnLimpacampoZero($_REQUEST['TEM_PRODAUX']);			
			
			$val_totprodu = fnLimpacampo($_REQUEST['VAL_TOTPRODU']);
			$val_resgate = fnLimpacampo($_REQUEST['VAL_RESGATE']);
			$val_desconto = fnLimpacampo($_REQUEST['VAL_DESCONTO']);
			$val_totvenda = fnLimpacampo($_REQUEST['VAL_TOTVENDA']);
			$cod_vendapdv = fnLimpacampo($_REQUEST['COD_VENDAPDV']);

			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
						
			if ($opcao != ''){
				
						$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
					
						$sql1 = "CALL SP_INSERE_VENDAS1(
							'".$cod_venda."',
							'".$cod_orcamento."',
							'".$cod_empresa."',
							'".$cod_cliente."',
							'".$cod_lancamen."',
							'".$cod_ocorren."',
							'".$cod_univend."',
							'".$cod_formapa."',
							'".fnValorSql($val_totprodu)."',
							'".$tem_prodaux."',
							'".fnValorSql($val_resgate)."',
							'".fnValorSql($val_desconto)."',
							'".fnValorSql($val_totvenda)."',
							'".$cod_vendapdv."',
							'".$cod_usucada."'   
						) ";
						
					//echo $sql1;	
						
					mysqli_query(connTemp(fnDecode($_GET['key']),''),$sql1);

					//mensagem de retorno
					switch ($opcao)
					{
						case 'CAD':
							$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";	
							break;
						case 'ALT':
							$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";		
							break;
						case 'EXC':
							$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";		
							break;
						break;
					}			
					$msgTipo = 'alert-success';						  
				
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
	//fnEscreve($cod_cliente);
	
?>
		
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
									
									<?php $abaEmpresa = 1123; include "abasEmpresaConfig.php"; ?>
									
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
														<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary btn-sm addBox" data-url="action.php?mod=<?php echo fnEncode(1071)?>&id=<?php echo fnEncode($cod_empresa)?>&pop=true" data-title="Venda Avulsa - Busca Clientes"><i class="fa fa-search" aria-hidden="true" style="padding-top: 3px;" ></i></a>
														</span>
														<input type="text" name="NOM_CLIENTE" id="NOM_CLIENTE" class="form-control input-sm leitura" style="border-radius:0 3px 3px 0;" placeholder="Procurar cliente..." value="<?php echo $nom_cliente;?>">
														<input type="hidden" name="COD_CLIENTE" id="COD_CLIENTE" value="<?php echo $cod_cliente;?>" required>
														</div>																
													</div>															
															
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label required">Número do Cartão</label>
                                                            <input type="text" class="form-control input-sm text-right leitura" name="NUM_CARTAO" id="NUM_CARTAO" value="<?php echo $num_cartao;?>" maxlength="50" data-error="Campo obrigatório" required>
															<div class="help-block with-errors"></div>
														</div>
													</div>
													
												</div>
													
										</fieldset>	
										
										<div class="push20"></div>
										
										<style>

										.btSmall {
											padding: 3px 4px !important;
											font-size: 12px !important;
											line-height: 1.0 !important;
											border-radius: 3px !important;
										}

										</style>
									
										<div class="row">
															
											<div class="col-md-12" id="div_Produtos">
  
												<div class="push20"></div>

												<table class="table table-bordered table-hover  ">
												  <thead>
													<tr>
													  <th></th>
													  <th>Data</th>
													  <th>ID Venda</th>
													  <th>Tipo</th>
													  <th>Motivo</th>
													  <th>Loja</th>
													  <th>Vl. Total</th>
													  <th>Vl. Resgate</th>
													  <th>Vl. Desconto</th>
													  <th>Vl. Venda</th>
													  <th>Pagamento</th>
													</tr>
												  </thead>
												<tbody>
												  
												<?php 
												
													$sql = "SELECT  B.DES_LANCAMEN, 
															C.DES_OCORREN, 
															D.NOM_UNIVEND, 
															E.DES_FORMAPA,
															F.DAT_EXPIRA, 
															A.* 
															FROM VENDAS A 
															LEFT JOIN tipolancamentomarka b ON b.COD_LANCAMEN = A.COD_LANCAMEN 
															LEFT JOIN ocorrenciamarka c ON c.COD_OCORREN = A.COD_OCORREN 
															LEFT JOIN unidadevenda d ON d.COD_UNIVEND = A.COD_UNIVEND 
															LEFT JOIN formapagamento e ON e.COD_FORMAPA = A.COD_FORMAPA
															INNER JOIN CREDITOSDEBITOS F ON F.COD_VENDA = A.COD_VENDA 
															WHERE 
															A.COD_CLIENTE = '".$cod_cliente."' AND
															F.DAT_EXPIRA > NOW()
															AND A.COD_STATUSCRED <> 6
															GROUP BY A.COD_VENDA 
															ORDER BY DAT_CADASTR DESC
															";															
													
													//fnEscreve($sql);
													$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
													
													$count = 0;
													$valorTTotal = 0;
													$valorTRegaste = 0;
													$valorTDesconto = 0;
													$valorTvenda = 0;
													
													while ($qrBuscaProdutos = mysqli_fetch_assoc($arrayQuery))
													  {														  
														$count++;
														$valorTTotal = $valorTTotal + $qrBuscaProdutos['VAL_TOTPRODU'];
														$valorTRegaste = $valorTRegaste + $qrBuscaProdutos['VAL_RESGATE'];
														$valorTDesconto = $valorTDesconto + $qrBuscaProdutos['VAL_DESCONTO'];
														$valorTvenda = $valorTvenda + $qrBuscaProdutos['VAL_TOTVENDA'];
														
														echo"
															<tr cod_venda=".$qrBuscaProdutos['COD_VENDA'].">
															
															  <td class='text-center'><a href='javascript:void(0);' onclick='abreDetail(".$qrBuscaProdutos['COD_VENDA'].")'><i class='fa fa-plus' aria-hidden='true'></i></a></td>
															  <td><small>".fnFormatDateTime($qrBuscaProdutos['DAT_CADASTR'])."</small></td>
															  <td>".$qrBuscaProdutos['COD_VENDAPDV']."</td>												
															  <td>".$qrBuscaProdutos['DES_LANCAMEN']."</td>												
															  <td><small>".$qrBuscaProdutos['DES_OCORREN']."</small></td>												
															  <td>".$qrBuscaProdutos['NOM_UNIVEND']."</td>												
															  <td class='text-right'><b><div class='totalLinhaVenda'>".fnValor($qrBuscaProdutos['VAL_TOTPRODU'],2)."</div></b></td>
															  <td class='text-right'>".fnValor($qrBuscaProdutos['VAL_RESGATE'],2)."</td>
															  <td class='text-right'>".fnValor($qrBuscaProdutos['VAL_DESCONTO'],2)."</td>
															  <td class='text-right'>".fnValor($qrBuscaProdutos['VAL_TOTVENDA'],2)."</td>
															  <td>".$qrBuscaProdutos['DES_FORMAPA']."</td>												
															</tr>
															
														  <tr style='display:none; background-color: #fff;' id='abreDetail_".$qrBuscaProdutos['COD_VENDA']."'>
															<td></td>
															<td colspan='11'>
															<div id='mostraDetail_".$qrBuscaProdutos['COD_VENDA']."'>
								
															
															</div>
															</td>
														  </tr>
														  
															";
														  }											

												?>
														
												</tbody>
												<!--
												  <tfoot>
													<tr>
													  <th></th>
													  <th colspan="5">Total</th>
													  <th class="text-right"><?php echo fnValor($valorTTotal,2);?></th>
													  <th class="text-right"><?php echo fnValor($valorTRegaste,2);?></th>
													  <th class="text-right"><?php echo fnValor($valorTDesconto,2);?></th>
													  <th class="text-right"><?php echo fnValor($valorTvenda,2);?></th>
													  <th colspan="2"></th>
													</tr>
												  </tfoot>
												  -->
												</table>
												
												<input type="hidden" name="TEM_PRODAUX" id="TEM_PRODAUX" value="<?php echo $tem_prodaux; ?>">
																								
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
				window.location.href = "action.php?mod=<?php echo fnEncode(1123); ?>&id=<?php echo fnEncode($cod_empresa); ?>&idC="+newCli+" ";
				$('#REFRESH_PRODUTOS').val("N");				
			  }	
			  
			});
			
		});	
		
		function recalcula(codVenda){
			var valTotal = 0;
			$('.prodValorLinha').each(function(index,item){
				if($(item).text() != ""){
					valTotal += limpaValor($(item).text());				
				}
				
			 });
			
			$('#mostraDetail_' +codVenda+ ' .subtotalProd').text(valTotal.toFixed(2));				 
			$('#mostraDetail_' +codVenda+ ' .subtotalProd').mask("#.##0,00", {reverse: true});
		}
		
		function abreDetail(idVenda){
			RefreshProdutos(<?php echo $cod_empresa; ?>, idVenda);
		}
		
		function RefreshProdutos(idEmp, idVenda) {
			var idItem = $('#abreDetail_'+idVenda);
			
			if (!idItem.is(':visible')){
				$.ajax({
					type: "GET",
					url: "ajxProdutosVendaEstorno.php",
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
				
				$('[cod_venda="'+idVenda+'"]').find($(".fa")).removeClass('fa-plus').addClass('fa-minus');
			}else{
				idItem.hide();
				$('[cod_venda="'+idVenda+'"]').find($(".fa")).removeClass('fa-minus').addClass('fa-plus');
			}
		}	
		
		function estornarItem(thisObj, codEmpresa, codVenda, codItemVenda, qtdeItemVenda){
			var opcao = "EXC";
			var tipoEstorno = 2;

			$.confirm({
				title: 'Atenção!',
				animation: 'opacity',
                closeAnimation: 'opacity',
				content: 'Deseja realmente estornar o item?',
				buttons: {
					confirmar: function () {
						$.confirm({
							title: 'Atenção!',
							content: '<div class="form-group"><label class="control-label">Quantidade</label>' +
									 '<input autofocus="" type="text" id="quantidadeEstornar" class="form-control"></div>',
							buttons: {
								estornar: function () {
									var qtdeDigi = $('#quantidadeEstornar').val();
									var qtdeAtual = limpaValor($('[codItemVenda="'+codItemVenda+'"] .prodQtdeLinha').text());
									if(qtdeDigi > qtdeItemVenda){
										$.alert({
											title: 'Atenção!',
											type: 'orange',
											content: 'Quantidade digitada é superior a disponível, tente novamente.',
										});
									}else{
										$.ajax({
											type: "GET", 
											url: "ajxEstornaVendaItem.php",
											data: { ajx1:codEmpresa, ajx2:codVenda, ajx3:tipoEstorno, ajx5:opcao, ajx6:codItemVenda, ajx7:qtdeDigi},
											beforeSend:function(){
												$('[codItemVenda="'+codItemVenda+'"] .prodQtdeLinha').html('<div class="loading" style="width: 100%; "></div>');
												$('#mostraDetail_' +codVenda+ ' .subtotalProd').html('<div class="loading" style="width: 100%; "></div>');
											},
											success:function(response){
												if(qtdeAtual != qtdeItemVenda){
													qtdeItemVenda = qtdeAtual;
												}
												
												if(qtdeDigi == qtdeItemVenda){
													$('[codItemVenda="'+codItemVenda+'"]').fadeOut(200, function(){ 
														$(this).remove();		
													});
												}else{
													$('[codItemVenda="'+codItemVenda+'"] .prodQtdeLinha').text((qtdeItemVenda - qtdeDigi).toFixed(2));
													$('[codItemVenda="'+codItemVenda+'"] .prodQtdeLinha').mask("#.##0,00", {reverse: true});
													
													var valorUnitItem = limpaValor($('[codItemVenda="'+codItemVenda+'"] .prodValorUnitLinha').text());
													$('[codItemVenda="'+codItemVenda+'"] .prodValorLinha').text((valorUnitItem * qtdeDigi).toFixed(2));
													$('[codItemVenda="'+codItemVenda+'"] .prodValorLinha').mask("#.##0,00", {reverse: true});
													
												}
												
												recalcula(codVenda);
											},
											error:function (xhr, ajaxOptions, thrownError){
												//On error, we alert user
												alert(thrownError);
											}
										});										
									}
								},
								cancelar: function () {
									// do nothing.
								}
							}
						});
					},
					cancelar: function () {
						
						
					},
				}
			});					
		}
		
		function estornarVenda(thisObj, codEmpresa, codVenda){
			var opcao = "EXC";
			var tipoEstorno = 1;
			var codCliente = <?php echo $cod_cliente; ?>;
			
			$.confirm({
				title: 'Atenção!',
				animation: 'opacity',
                closeAnimation: 'opacity',
				content: 'Deseja realmente estornar essa venda?',
				buttons: {
					confirmar: function () {
						$.ajax({
							type: "GET", 
							url: "ajxEstornaVendaItem.php",
							data: { ajx1:codEmpresa, ajx2:codVenda, ajx3:tipoEstorno, ajx4:codCliente, ajx5:opcao},
							beforeSend:function(){
								//$('[cod_venda="'+codVenda+'"]').html('<td colspan="9"><div class="loading" style="width: 100%; "></div></td>');
							},
							success:function(response){
								$('#mostraDetail_' + codVenda).parent().parent().remove();
								$('#mostraDetail_' + codVenda).remove(); 
								$('[cod_venda="'+codVenda+'"]').find($(".fa")).removeClass('fa-minus');
								$('#cod_venda_' +codVenda+ ' .totalLinhaVenda').text("0,00");
							},
							error:function (xhr, ajaxOptions, thrownError){
								//On error, we alert user
								alert(thrownError);
							}
						});	
					},
					cancelar: function () {
					
					},
				}
			});					
		}
		
						
		function retornaForm(index){
					
		}

	
	</script>	
