<?php
	
	//echo "<h5>_".$opcao."</h5>";

	$dias30="";
	$dat_ini="";
	$dat_fim="";

	$hashLocal = mt_rand();

	//inicialização de variáveis
	$hoje = fnFormatDate(date("Y-m-d"));
	//$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 1 days')));
	$dias30 = fnFormatDate(date("Y-m-d"));
	
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

			$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
			$dat_ini = fnDataSql($_POST['DAT_INI']);
			$dat_fim = fnDataSql($_POST['DAT_FIM']);
			$qtd_produto = fnLimpaCampoZero($_REQUEST['QTD_PRODUTO']);
			$qtd_relacionada = fnLimpaCampoZero($_REQUEST['QTD_RELACIONADA']);
			$tip_processa = fnLimpaCampo($_REQUEST['TIP_PROCESSA']);
			$perc_processa = fnLimpaCampoZero($_REQUEST['PERC_PROCESSA']);

			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
						
			if ($opcao != ''){

				//mensagem de retorno
				switch ($opcao)
				{
					case 'CAD':
						$sql = "CALL SP_DEFINE_TOP_PRODUTOGRUPO ($cod_empresa, '$tip_processa', '$dat_ini', '$dat_fim', $perc_processa, $qtd_produto, $qtd_relacionada, 'S' )";
						fnEscreve($sql);
						mysqli_query(connTemp($cod_empresa,''),trim($sql));	
						$msgRetorno = "Processado com <strong>sucesso!</strong>";	
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
      
	
	//busca dados da url	
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
		//fnEscreve('entrou else');
	}
	
	//fnMostraForm();

?>

<style>
#blocker
{
    display:none; 
	position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: .8;
    background-color: #fff;
    z-index: 1000;
}
    
#blocker div
{
	position: absolute;
	top: 30%;
	left: 48%;
	width: 200px;
	height: 2em;
	margin: -1em 0 0 -2.5em;
	color: #000;
	font-weight: bold;
}
</style>
			
					<div id="blocker">
				       <div style="text-align: center;"><img src="../images/loading2.gif"><br/> Aguarde. Processando... ;-)</div>
				    </div>

					<div class="push30"></div> 
					
					<div class="row">				
					
						<div class="col-md12 margin-bottom-30">
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
									
									<div class="push30"></div> 
			
									<div class="login-form">
									
										<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
																				
										<fieldset>
											<legend>Dados do processamento</legend> 
											
												<div class="row">

													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Tipo</label>
																<select data-placeholder="Escolha o tipo" name="TIP_PROCESSA" id="TIP_PROCESSA" class="chosen-select-deselect">
																	<option value=""></option>					
																	<option value="SEG">Segmento</option>					
																	<option value="PROD">Produto</option>					
																</select>	
															<div class="help-block with-errors"></div>
														</div>
														<script>$("#formulario #TIP_PROCESSA").val(<?php echo $tip_processa; ?>).trigger("chosen:updated");</script>
													</div>

													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label required">Data Inicial</label>
															
															<div class="input-group date datePicker" id="DAT_INI_GRP">
																<input type='text' class="form-control input-sm data" name="DAT_INI" id="DAT_INI" value="<?=fnDataShort($dat_ini)?>" required/>
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
																<input type='text' class="form-control input-sm data" name="DAT_FIM" id="DAT_FIM" value="<?=fnDataShort($dat_fim)?>" required/>
																<span class="input-group-addon">
																	<span class="glyphicon glyphicon-calendar"></span>
																</span>
															</div>
															<div class="help-block with-errors"></div>
														</div>
													</div>

													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Percentual</label>
																<select data-placeholder="Escolha o tipo" name="PERC_PROCESSA" id="PERC_PROCESSA" class="chosen-select-deselect">
																	<option value=""></option>					
																	<option value="10">10%</option>					
																	<option value="20">20%</option>					
																	<option value="30">30%</option>					
																	<option value="40">40%</option>					
																	<option value="50">50%</option>					
																	<option value="60">60%</option>					
																	<option value="70">70%</option>					
																	<option value="80">80%</option>					
																	<option value="90">90%</option>				
																	<option value="100">100%</option>					
																</select>	
															<div class="help-block with-errors"></div>
														</div>
														<script>$("#formulario #PERC_PROCESSA").val(<?php echo $perc_processa; ?>).trigger("chosen:updated");</script>
													</div>

													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Lista de Produtos</label>
																<select data-placeholder="Escolha a quantidade" name="QTD_PRODUTO" id="QTD_PRODUTO" class="chosen-select-deselect">
																	<option value=""></option>					
																	<option value="10">10</option>					
																	<option value="50">50</option>					
																	<option value="100">100</option>					
																	<option value="200">200</option>					
																	<option value="500">500</option>					
																	<option value="1000">1000</option>					
																</select>	
															<div class="help-block with-errors"></div>
														</div>
														<script>$("#formulario #QTD_PRODUTO").val(<?php echo $qtd_produto; ?>).trigger("chosen:updated");</script>
													</div>
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Produtos Relacionados</label>
																<select data-placeholder="Escolha a quantidade" name="QTD_RELACIONADA" id="QTD_RELACIONADA" class="chosen-select-deselect">
																	<option value=""></option>					
																	<option value="5">5</option>					
																	<option value="10">10</option>					
																</select>	
															<div class="help-block with-errors"></div>
														</div>
														<script>$("#formulario #QTD_RELACIONADA").val(<?php echo $qtd_relacionada; ?>).trigger("chosen:updated");</script>
													</div>
																				
												</div>
												
										</fieldset>	
																				
										<div class="push10"></div>
										<hr>	
										<div class="form-group text-right col-lg-12">
											
											  <button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-cog" aria-hidden="true"></i>&nbsp; Processar</button>
											
										</div>
										
										<input type="hidden" name="opcao" id="opcao" value="">
										<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?=$cod_empresa?>">
										<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
										<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">		
										
										<div class="push5"></div> 
										
										</form>										
									
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
	
	<script type="text/javascript">
		
		$(function(){
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
		
	</script>	