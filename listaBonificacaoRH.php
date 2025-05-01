<?php
	
	//echo "<h5>_".$opcao."</h5>";

	$hashLocal = mt_rand();

	$cod_mes = "";
	
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

			$cod_mes = fnLimpaCampoZero(fnDecode($_REQUEST['COD_MES']));
			$cod_empresa = fnLimpaCampo($_REQUEST['COD_EMPRESA']);

			$cod_usucada = $_SESSION[SYS_COD_USUARIO];

			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
						
			if ($opcao != ''){

				//mensagem de retorno
				$msgTipo = 'alert-success';

				switch ($opcao){


					case 'CAD':

						

						break;
					case 'ALT':

						

						break;
					case 'EXC':

								
						break;
					
				}			
				
			}  	

		}
	}
      
	
	//busca dados da url	
	if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))){
		//busca dados da empresa
		$cod_empresa = fnDecode($_GET['id']);	
		$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '".$cod_empresa."' ";
		//fnEscreve($sql);
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql);
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
		
		if (isset($arrayQuery)){
			$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
			$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
		}
												
	}else {
		$cod_empresa = 0;		
		//fnEscreve('entrou else');
	}

	if($cod_mes == "" || $cod_mes == 0){

		$sqlUltMes = "SELECT COD_MES FROM MES_CAIXA WHERE COD_EMPRESA = $cod_empresa ORDER BY DAT_FIM DESC LIMIT 1";

		$arrayUltMes = mysqli_query(connTemp($cod_empresa,''),$sqlUltMes);
		$qrUltMes = mysqli_fetch_assoc($arrayUltMes);

		$cod_mes = $qrUltMes[COD_MES];

	}
	
	// fnEscreve($cod_mes);

?>
			
					<div class="push30"></div> 
					
					<div class="row">				
					
						<div class="col-md-12 margin-bottom-30">
							<!-- Portlet -->
							<?php if ($popUp != "true"){  ?>							
							<div class="portlet portlet-bordered">
							<?php } else { ?>
							<div class="portlet" style="padding: 0 20px 20px 20px;" >
							<?php } ?>
							
								<?php if ($popUp != "true"){  ?>
								<div class="portlet-title">
									<div class="caption">
										<i class="glyphicon glyphicon-calendar"></i>
										<span class="text-primary"><?php echo $NomePg; ?></span>
									</div>
									<?php include "atalhosPortlet.php"; ?>
								</div>
								<?php } ?>
								<div class="portlet-body">
									
									<?php if ($msgRetorno <> '') { ?>	
									<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
									<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									 <?php echo $msgRetorno; ?>
									</div>
									<?php } ?>	

									<?php 
									//menu superior - cliente
									
									// $abaEmpresa = 1706;						
																					
									// include "abasRH.php";

									?>
									
									<div class="push30"></div> 
			
									<div class="login-form">
									
										<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
																				
										<!-- <fieldset>
											<legend>Dados do Lançamento</legend> 
											
												<div class="row">

													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Mês</label>
																<select data-placeholder="Selecione o mês" name="COD_MES" id="COD_MES" class="chosen-select-deselect" style="width:100%;">																     
																	<?php

																		$sqlMes = "SELECT COD_MES, MESANO FROM MES_CAIXA
																					WHERE COD_EMPRESA = $cod_empresa
																					ORDER BY DAT_FIM DESC";
																		$arrayMes = mysqli_query(connTemp($cod_empresa,''),$sqlMes);

																		while ($qrMes = mysqli_fetch_assoc($arrayMes)) {
																	?>

																			<option value="<?=fnEncode($qrMes[COD_MES])?>"><?=$qrMes[MESANO]?></option>

																	<?php
																		}

																	?>																	
																</select>
																<script type="text/javascript">$("#COD_MES").val("<?=fnEncode($cod_mes)?>").trigger("chosen:updated");</script>
															<div class="help-block with-errors"></div>
														</div>
													</div>

													<div class="col-md-2">
														<div class="push20"></div>
														<button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-sm btn-block getBtn"><i class="fa fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
													</div>
													
												</div>
													
										</fieldset> -->
																				
										<div class="push10"></div>
										
										<input type="hidden" name="opcao" id="opcao" value="">
										<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?=$cod_empresa?>">
										<input type="hidden" name="CLIENTE_DETALHE" id="CLIENTE_DETALHE" value="">
										<input type="hidden" name="REFRESH_LANCAMENTO" id="REFRESH_LANCAMENTO" value="N">
										<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
										<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">		
										
										<div class="push50"></div>

											<table class="table table-bordered table-striped table-hover tableSorter">
												  <thead>
													<tr>
													  <th>Código</th>
													  <th>Nome</th>
													  <th class="text-center">Bonificação</th>
													  <th class="text-center">Valores</th>
													  <?php if(1 == 1){ ?>

														<th>Remuneração</th>

														<?php 
														}else{
														?>

														<th class="text-right">Valor</th>
												  		<th class="text-center">%</th>

														<?php 
														} 
														?>
													</tr>
												  </thead>
												<tbody>
												  
												<?php 
												
													$sql = "SELECT  CL.COD_CLIENTE, 
																	CL.NOM_CLIENTE,
																	CL.LOG_BONIFICA,
																	CL.VAL_BONIFICA,
																	CL.PCT_JURIBONI
															FROM CLIENTES CL 
															WHERE CL.COD_EMPRESA = $cod_empresa
															AND CL.LOG_TITULAR = 'S'
															AND CL.LOG_ESTATUS = 'S'
															ORDER BY CL.NOM_CLIENTE ASC";

															// fnEscreve($sql);

													$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
													
													$count=0;
													while ($qrFunc = mysqli_fetch_assoc($arrayQuery))
													{														  
														$count++;

														$checkBonifica = "";

														if($qrFunc['LOG_BONIFICA'] == 'S'){
															$checkBonifica = "checked";
														}

														$val_bonifica = fnValor($qrFunc['VAL_BONIFICA'],2);
														$pct_juriboni = fnValor($qrFunc['PCT_JURIBONI'],2);

														if($val_bonifica == "0,00"){
															$val_bonifica = "";
														}
														if($pct_juriboni == "0,00"){
															$pct_juriboni = "";
														}

												?>	
														<tr>
															<td><small><?=$qrFunc['COD_CLIENTE']?></small></td>
															<td><?=$qrFunc['NOM_CLIENTE']?></td>												
															<td class='text-center'>
															  	<label class='switch switch-small'>
																	<input type='checkbox' name='LOG_BONIFICA_<?=$qrFunc[COD_CLIENTE]?>' id='LOG_BONIFICA_<?=$qrFunc[COD_CLIENTE]?>' class='switch switch-small' value='S' <?=$checkBonifica?> onchange='ajxBonifica($(this),"<?=$qrFunc['COD_CLIENTE']?>")'>
																	<span></span>
																</label>
															</td>												
															
															<td>
																
																<ul>

																	<?php

																		$sqlBonus = "SELECT LA.*, TC.DES_TIPO FROM LANCAMENTO_AUTOMATICO LA
																				INNER JOIN TIP_CREDITO TC ON TC.COD_TIPO = LA.COD_TIPO
																				WHERE LA.COD_EMPRESA = $cod_empresa
																				AND LA.TIP_LANCAME = 'B'
																				AND LA.COD_CLIENTE = $qrFunc[COD_CLIENTE]";

																		// fnEscreve($sql);
																		$arrayQueryBonus = mysqli_query(connTemp($cod_empresa,''),$sqlBonus);
																		
																		$count=0;
																		while ($qrRemunera = mysqli_fetch_assoc($arrayQueryBonus)){

																	?>

																		<li class="f12"><?=$qrRemunera[DES_TIPO].": ".fnValor($qrRemunera['VAL_LANCAME'],2)?></li>	

																	<?php

																		}

																	?>

																</ul>

															</td>												

															<?php if(1==1){ ?>

																<td class="text-center">
																	<a href="javascript:void(0)" class="btn btn-default btn-xs addBox" data-url="action.php?mod=<?php echo fnEncode(1764)?>&id=<?php echo fnEncode($cod_empresa); ?>&idc=<?php echo fnEncode($qrFunc[COD_CLIENTE]); ?>&pop=true&tipo=B" data-placement='top' data-title='Cadastro de Remuneração'><i class="fal fa-plus f12" aria-hidden="true"></i></a>

																</td>

															<?php }else{ ?>

																<td class="text-right vl">
															  	<a href="#" class="editable" id="VAL_BONIFICA_<?=$qrFunc[COD_CLIENTE]?>" 
																  	data-type='text' 
																  	data-title='Editar Valor' data-pk="<?=$qrFunc[COD_CLIENTE]?>" 
																  	data-name="VAL_BONIFICA"  
																  	data-codempresa="<?=fnEncode($cod_empresa)?>" >
																  	<?=$val_bonifica?>
															  	</a>
															</td>
															<td class="text-center vl">
															  	<a href="#" class="editable" id="PCT_JURIBONI_<?=$qrFunc[COD_CLIENTE]?>" 
																  	data-type='text' 
																  	data-title='Editar Percentual' data-pk="<?=$qrFunc[COD_CLIENTE]?>" 
																  	data-name="PCT_JURIBONI"  
																  	data-codempresa="<?=fnEncode($cod_empresa)?>" >
																  	<?=$pct_juriboni?>
															  	</a>
															</td>
																
															<?php } ?>
											
														</tr>
												<?php   

													}											

												?>
													
												</tbody>
											</table>
										
										<div class="push10"></div>	
										</form>								
									

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
					</div>			
						
					<div class="push20"></div>

					<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
					<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
					<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
					<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />
	
	<script type="text/javascript">

		$(function(){

			$('.vl .editable-input .input-sm').mask('000.000.000.000.000,00', {reverse: true});

			$('.editable').editable({ 
		    	emptytext: '_______________',
		        url: 'ajxListaBonificacao.php',
        		ajaxOptions:{type:'post'},
        		params: function(params) {
			        params.codempresa = $(this).data('codempresa');
			        return params;
			    },
        		success:function(data){
					console.log(data);
				}
		    });

		});

		function ajxBonifica(check, cod_cliente){

			var log_bonifica = "N";

			if(check.prop('checked')){
				log_bonifica = 'S';
			}

			$.ajax({
				method: 'POST',
				url: 'ajxListaBonificacao.php',
				data: {
					pk: cod_cliente, 
					name: 'LOG_BONIFICA', 
					campo: cod_cliente, 
					value: log_bonifica, 
					codempresa: "<?=fnEncode($cod_empresa)?>"
				},
				success:function(data){
					console.log(data);
					if(log_bonifica == 'N'){
						$("#VAL_BONIFICA_"+cod_cliente+",#PCT_JURIBONI_"+cod_cliente).html('0,00')
					}
				}
			});

		}
		
	</script>	