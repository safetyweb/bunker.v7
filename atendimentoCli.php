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
			
			$num_cartao = fnLimpacampo($_REQUEST['NUM_CARTAO']);
			$num_cartao_novo = fnLimpacampo($_REQUEST['NUM_CARTAO_NOVO']);

			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
						
			if ($opcao != ''){
				
					$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
					
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
						
					//$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql1);
					//$qrBuscaRetorno = mysqli_fetch_assoc($arrayQuery);
					//$mensagem_retorno = $qrBuscaRetorno['mensagem_retorno'];

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

	include "labelLibrary.php"; 
    	
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
										<i class="glyphicon glyphicon-calendar"></i>
										<span class="text-primary"> <?php echo $NomePg; ?></span>
									</div>
									
									<?php 
									switch ($_SESSION["SYS_COD_SISTEMA"]) {
										case 16: //gerenciador social
											$formBack = "1424";
											break;
										default;											
											$formBack = "1015";
											break;
									}
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
									
									<?php $abaCli = 1476; include "abasClienteConfig.php"; ?>
									
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
														<label for="inputName" class="control-label required"><?=$labelNome?></label>
														<div class="input-group">
														<span class="input-group-btn">
														<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary btn-sm addBox" data-url="action.php?mod=<?php echo fnEncode(1071)?>&id=<?php echo fnEncode($cod_empresa)?>&pop=true" data-title="Venda Avulsa - Busca Clientes"><i class="fa fa-search" aria-hidden="true" style="padding-top: 3px;" ></i></a>
														</span>
														<input type="text" name="NOM_CLIENTE" id="NOM_CLIENTE" class="form-control input-sm leituraOff" style="border-radius:0 3px 3px 0;" placeholder="Procurar cliente..." value="<?php echo $nom_cliente;?>">
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

										<div class="row">

											<div class="col-md-12">
												<a href="javascript:void(0)" class="btn btn-info addBox pull-right" data-url="action.php?mod=<?php echo fnEncode(1436)?>&id=<?php echo fnEncode($cod_empresa)?>&idU=<?=fnEncode($cod_cliente)?>&pop=true" data-title="Novo Atendimento - <?=$nom_cliente?>">Cadastrar Atendimento &nbsp;<span class="fas fa-plus"></span></a>
											</div>

										</div>										
										
										<div class="push20"></div>

											<div class="col-lg-12" style="padding:0;">

											<div class="no-more-tables">
										
												<form name="formLista">
												
												<table class="table table-bordered table-striped table-hover">
												  <thead>
													<tr>
													  <th><small>Atendimento</small></th>
													  <th><small>Título</small></th>
													  <th><small>Solicitante</small></th>
													  <th><small>Solicitação</small></th>
													  <th><small>Prioridade</small></th>
													  <th><small>Status</small></th>
													  <th><small>Cadastro</small></th>
													  <th><small>Prazo</small></th>
													  <th><small>Atualizado</small></th>
													</tr>
												  </thead>
												<tbody id="relatorioConteudo">
												  
												<?php

													

												
													// $sqlCount = "SELECT COD_ATENDIMENTO FROM ATENDIMENTO_CHAMADOS AC 
												 //  				WHERE AC.COD_EMPRESA = $cod_empresa
												 //  				AND (AC.COD_SOLICITANTE = $cod_cliente OR AC.COD_USURES = $cod_cliente OR AC.COD_USUARIOS_ENV IN($cod_cliente))									  				
													// 			";
													// //fnEscreve($sqlCount);
													
													// $retorno = mysqli_query(connTemp($cod_empresa,''),$sqlCount);
													// $total_itens_por_pagina = mysqli_num_rows($retorno);
													
													// $numPaginas = ceil($total_itens_por_pagina/$itens_por_pagina);	

													// //variavel para calcular o início da visualização com base na página atual
													// $inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;													
												
													$sqlSac = "SELECT AC.*, AT.DES_TPSOLICITACAO, 
																AP.DES_PRIORIDADE, AP.DES_COR AS COR_PRIORIDADE, AP.DES_ICONE AS ICO_PRIORIDADE,
																AST.ABV_STATUS, AST.DES_COR AS COR_STATUS, AST.DES_ICONE AS ICO_STATUS 
																FROM ATENDIMENTO_CHAMADOS AC
																LEFT JOIN ATENDIMENTO_PRIORIDADE AP ON AP.COD_PRIORIDADE = AC.COD_PRIORIDADE
																LEFT JOIN ATENDIMENTO_STATUS AST ON AST.COD_STATUS = AC.COD_STATUS
																LEFT JOIN ATENDIMENTO_TPSOLICITACAO AT ON AT.COD_TPSOLICITACAO = AC.COD_TPSOLICITACAO
																WHERE AC.COD_EMPRESA = $cod_empresa
												  				AND FIND_IN_SET('$cod_cliente', AC.COD_CLIENTES_ENV)	
																ORDER BY AC.COD_ATENDIMENTO DESC
																";
													// fnEscreve($sqlSac);

													$arrayQuerySac = mysqli_query(connTemp($cod_empresa,''),$sqlSac);
													
													$count=0;
													$adm="";
													$entrega = "";
													while ($qrSac = mysqli_fetch_assoc($arrayQuerySac))
													 {	

													 	if($qrSac['LOG_ADM'] == 'S'){
													 		$adm = "<i class='fal fa-user-check shortCut' data-toggle='tooltip' data-placement='left' data-original-title='ti'></i>";
													 	}else{
													 		$adm = "<i class='fal fa-user-tie shortCut' data-toggle='tooltip' data-placement='left' data-original-title='cliente'></i>";
													 	}

														$count++;


														$sqlUsuarios = "SELECT (SELECT NOM_USUARIO FROM USUARIOS WHERE COD_USUARIO = $qrSac[COD_SOLICITANTE]) AS NOM_SOLICITANTE,
																				(SELECT NOM_USUARIO FROM USUARIOS WHERE COD_USUARIO = $qrSac[COD_USURES]) AS NOM_RESPONSAVEL";
														$qrNomUsu = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(),$sqlUsuarios));
														//fnEscreve($sqlUsuarios);										  

														if($qrSac['DAT_ENTREGA'] == "1969-12-31"){
															$entrega = "";
														}else{
															$entrega = fnDataShort($qrSac['DAT_ENTREGA']);
														}

														if($qrSac['DAT_INTERAC'] != ""){
															if(fnDatasql($qrSac['DAT_INTERAC']) == fnDatasql($hoje)){
																$atualizado = "Hoje";
															}else if(fnDatasql($qrSac['DAT_INTERAC']) == date('Y-m-d', strtotime(' -1 days'))){
																$atualizado = "Ontem";
															}else{
																$atualizado = fnDataFull($qrSac['DAT_INTERAC']);
															}
														}else{
															$atualizado = "";
														}

														//$diff_dias = fnDateDif($qrSac['DAT_CADASTR'],Date("Y-m-d"));
														// fnEscreve(fnDatasql($qrSac['DAT_INTERAC']));
													?>

													<tr>
													  <td class="text-center">
													  	<small>
													  		<a href="action.php?mod=<?=fnEncode(1440);?>&id=<?php echo fnEncode($qrSac['COD_EMPRESA']);?>&idC=<?php echo fnEncode($qrSac['COD_ATENDIMENTO']); ?>" target="_blank">
													  			<?=$qrSac['COD_ATENDIMENTO'] ?>
													  		</a>
													  	</small>
													  </td>
													  <td><small><?=$qrSac['NOM_CHAMADO'] ?></small></td>
													  <td><small><?=$qrNomUsu['NOM_SOLICITANTE'] ?></small></td>
													  <td><small><?=$qrSac['DES_TPSOLICITACAO'] ?></small></td>
													  
													  <td class="text-center">
													  	<small>
													  		<p class="label" style="background-color: <?php echo $qrSac['COR_PRIORIDADE'] ?>"> 
													  			<span class="<?php echo $qrSac['ICO_PRIORIDADE']; ?>" style="color: #FFF;"></span>
													  			<!-- &nbsp; <?php echo $qrSac['DES_PRIORIDADE']; ?> -->
													  		</p>
													  	</small>
													  </td>

													  <td class="text-center">
													  	<small>
													  		<p class="label" style="background-color: <?php echo $qrSac['COR_STATUS'] ?>"> 
													  			<span class="<?php echo $qrSac['ICO_STATUS']; ?>" style="color: #FFF;"></span>
													  			&nbsp;<?php echo $qrSac['ABV_STATUS']; ?>
													  		</p>
													  	</small>
													  </td>
													  
													  <td class="text-center"><small><?=fnDataShort($qrSac['DAT_CADASTR']); ?></small></td>
													  <td class="text-center"><small><?=$entrega?></small></td>
													  <td class="text-center"><small><?=$atualizado?></small></td>

													</tr>
												    <?php
													}									
												?> 
													
												</tbody>
												<!-- <tfoot>
													<tr>
													  <th class="" colspan="100">
														<center><ul id="paginacao" class="pagination-sm"></ul></center>
													  </th>
													</tr>
												</tfoot> -->												
												</table>


												
												</form>
												
											<div class="push10"></div>	

											</div>
											
										</div>

										<!-- <div class="push50"></div>
										<hr>

  										<div class="row">

											<div class="col-md-12 text-center">
												<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-retweet" aria-hidden="true"></i>&nbsp; Carregar mais registros</button>
											</div>

										</div> -->

										<div class="push50"></div>

										<div class="form-group text-center col-lg-12">

											

										</div>
										
										<input type="hidden" name="NOVO_CLIENTE" id="NOVO_CLIENTE" value="">
										<input type="hidden" name="REFRESH_CLIENTE" id="REFRESH_CLIENTE" value="N">
										<input type="hidden" name="REFRESH_FOLLOW" id="REFRESH_FOLLOW" value="N">
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
				window.location.href = "action.php?mod=<?php echo fnEncode(1253); ?>&id=<?php echo fnEncode($cod_empresa); ?>&idC="+newCli+" ";
				$('#REFRESH_PRODUTOS').val("N");				
			  }

			  if($('#REFRESH_FOLLOW').val() == "S"){

			  	$.ajax({
					type: "POST",
					url: "ajxFollowManual.php",
					data: { COD_EMPRESA:<?=$cod_empresa?>, COD_CLIENTE:<?=$cod_cliente?> },
					beforeSend:function(){
						$("#relatorioConteudo").html('<div class="loading" style="width: 100%;"></div>');
					},
					success:function(data){
						$("#relatorioConteudo").html(data); 
					},
					error:function(){
						$("#relatorioConteudo").html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
					}
				});

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
