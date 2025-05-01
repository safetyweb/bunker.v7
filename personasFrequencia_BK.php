			
					<div class="push10"></div>
					
					<div class="row">					
						
						<div class="col-md-6">
							
							<h5>Cadastros realizados no período</h5>
							
							<div class="col-md-6"  style="padding-left: 0;">
								<div class="form-group">
									<label for="inputName" class="control-label">De</label>
									
									<div class="input-group date datePicker">
										<input type='text' class="form-control input-sm data" name="BL3_CADASTROS_INI" id="BL3_CADASTROS_INI" value="<?php echo $bl3_cadastros_ini; ?>"/>
										<span class="input-group-addon">
											<span class="glyphicon glyphicon-calendar"></span>
										</span>
									</div>
									<div class="help-block with-errors"></div>
								</div>
							</div>
							
							<div class="col-md-6">
								<div class="form-group">
									<label for="inputName" class="control-label">Até</label>
									
									<div class="input-group date datePicker">
										<input type='text' class="form-control input-sm data" name="BL3_CADASTROS_FIM" id="BL3_CADASTROS_FIM" value="<?php echo $bl3_cadastros_fim; ?>"/>
										<span class="input-group-addon">
											<span class="glyphicon glyphicon-calendar"></span>
										</span>
									</div>
									<div class="help-block with-errors"></div>
								</div>
							</div>				
						
						</div>
						
						<div class="col-md-6">
							
							<h5>Compras realizadas no período</h5>
							
							<div class="col-md-6"  style="padding-left: 0;">
								<div class="form-group">
									<label for="inputName" class="control-label">De</label>
									
									<div class="input-group date datePicker">
										<input type='text' class="form-control input-sm data" name="BL3_COMPRAS_INI" id="BL3_COMPRAS_INI" value="<?php echo $bl3_compras_ini; ?>" />
										<span class="input-group-addon">
											<span class="glyphicon glyphicon-calendar"></span>
										</span>
									</div>
									<div class="help-block with-errors"></div>
								</div>
							</div>
							
							<div class="col-md-6">
								<div class="form-group">
									<label for="inputName" class="control-label">Até</label>
									
									<div class="input-group date datePicker">
										<input type='text' class="form-control input-sm data" name="BL3_COMPRAS_FIM" id="BL3_COMPRAS_FIM" value="<?php echo $bl3_compras_fim; ?>"/>
										<span class="input-group-addon">
											<span class="glyphicon glyphicon-calendar"></span>
										</span>
									</div>
									<div class="help-block with-errors"></div>
								</div>
							</div>				
						
						</div>	
						
						<div class="push10"></div> 
						
					</div>
					
					<div class="push10"></div> 
					
					<div class="row">

						<div class="col-md-6">
							
							<h5>Última compra realizadas no período</h5>
							
							<div class="col-md-6"  style="padding-left: 0;">
								<div class="form-group">
									<label for="inputName" class="control-label">De</label>
									
									<div class="input-group date datePicker">
										<input type='text' class="form-control input-sm data" name="BL3_UCOMPRAS_INI" id="BL3_UCOMPRAS_INI" value="<?php echo $bl3_ucompras_ini; ?>" />
										<span class="input-group-addon">
											<span class="glyphicon glyphicon-calendar"></span>
										</span>
									</div>
									<div class="help-block with-errors"></div>
								</div>
							</div>
							
							<div class="col-md-6">
								<div class="form-group">
									<label for="inputName" class="control-label">Até</label>
									
									<div class="input-group date datePicker">
										<input type='text' class="form-control input-sm data" name="BL3_UCOMPRAS_FIM" id="BL3_UCOMPRAS_FIM" value="<?php echo $bl3_ucompras_fim; ?>" />
										<span class="input-group-addon">
											<span class="glyphicon glyphicon-calendar"></span>
										</span>
									</div>
									<div class="help-block with-errors"></div>
								</div>
							</div>				
						
						</div>
						
						<?php
						//para aparecer somente para user adm - enquanto grava						
						if($_SESSION["SYS_COD_MASTER"] == 2){
						?>
						
						<div class="col-md-6 borda">
							
							<h5>Compras estornadas no período</h5>
							
							<div class="col-md-6"  style="padding-left: 0;">
								<div class="form-group">
									<label for="inputName" class="control-label">De</label>
									
									<div class="input-group date datePicker">
										<input type='text' class="form-control input-sm data" name="BL3_COMPRASE_INI" id="BL3_COMPRASE_INI" value="<?php echo $bl3_ucompras_ini; ?>" />
										<span class="input-group-addon">
											<span class="glyphicon glyphicon-calendar"></span>
										</span>
									</div>
									<div class="help-block with-errors"></div>
								</div>
							</div>
							
							<div class="col-md-6">
								<div class="form-group">
									<label for="inputName" class="control-label">Até</label>
									
									<div class="input-group date datePicker">
										<input type='text' class="form-control input-sm data" name="BL3_COMPRASE_FIM" id="BL3_COMPRASE_FIM" value="<?php echo $bl3_ucompras_fim; ?>" />
										<span class="input-group-addon">
											<span class="glyphicon glyphicon-calendar"></span>
										</span>
									</div>
									<div class="help-block with-errors"></div>
								</div>
							</div>				
						
						</div>
						
						<?php 
						}
						?>
						
						<div class="push20"></div> 
						
						<div class="col-md-6">
							
							<h5>Compras com retorno</h5>			
								
								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label">De </label> 
										<input type="text" class="form-control text-center input-sm int" name="BL3_QTD_RETORNO_INI" id="BL3_QTD_RETORNO_INI" maxlength="3"  value="<?php echo $bl3_qtd_retorno_ini; ?>" data-error="Campo obrigatório" >
										<div class="help-block with-errors">quantidade de vezes</div>
									</div>														
								</div>	
								
								<div class="col-md-2 text-center">
								<div class="push30"></div>
								a										
								</div>	
								
								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label">Até</label> 
										<input type="text" class="form-control text-center input-sm int" name="BL3_QTD_RETORNO_FIM" id="BL3_QTD_RETORNO_FIM" maxlength="3"  value="<?php echo $bl3_qtd_retorno_fim; ?>" data-error="Campo obrigatório" >
										<div class="help-block with-errors">quantidade de vezes</div>
									</div>														
								</div>	
								
								<div class="col-md-2"></div>							
								
						</div>	
						
					
						<div class="col-md-6">
							
							<h5>Compras com resgates</h5>
							
								<div class="col-md-4" style="padding-left: 0;">
									<div class="form-group">
										<label for="inputName" class="control-label">Com resgates</label> 
										<div class="push5"></div>
											<label class="switch">
											<input type="checkbox" name="BL3_LOG_RESGATE" id="BL3_LOG_RESGATE" class="switch" value="S" <?php echo $check_bl3_log_resgate; ?>>
											<span></span>
											</label>
									</div>
								</div>
								
								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label">Intervalo</label>
											<select data-placeholder="Selecione um resgate" name="BL3_TIP_RESGATE" id="BL3_TIP_RESGATE" class="chosen-select-deselect">
												<option value="">&nbsp;</option>					
												<option value="Acima">Maior que</option>					
												<option value="Abaixo">Menor que</option>					
												<option value="Igual">Igual</option>					
											</select>
										<script>$("#formulario #BL3_TIP_RESGATE").val("<?php echo $bl3_tip_resgate; ?>").trigger("chosen:updated"); </script>											
										<div class="help-block with-errors"></div>
									</div>
								</div>			
								
								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label">Qtd. de Resgates</label> 
										<input type="text" class="form-control text-center input-sm int" name="BL3_QTD_RESGATE" id="BL3_QTD_RESGATE" maxlength="3"  value="<?php echo $bl3_qtd_resgate; ?>" data-error="Campo obrigatório" >
										<div class="help-block with-errors"></div>
									</div>														
								</div>		
						
						</div>	
						
						<div class="push20"></div>
					
						<?php
						//para aparecer somente para user adm - enquanto grava						
						if($_SESSION["SYS_COD_MASTER"] == 2){
						?>

						<div class="col-md-6 borda">
							
							<h5>Sem Compras</h5>
							
								<div class="col-md-4" style="padding-left: 0;">
									<div class="form-group">
										<label for="inputName" class="control-label">Sem nenhuma compra</label> 
										<div class="push5"></div>
											<label class="switch">
											<input type="checkbox" name="BL3_LOG_SEMCOMPR" id="BL3_LOG_SEMCOMPR" class="switch" value="S" <?php echo $check_bl3_log_resgate; ?>>
											<span></span>
											</label>
									</div>
								</div>
									
								<div class="col-md-4"  style="padding-left: 0;">
									<div class="form-group">
										<label for="inputName" class="control-label">De</label>
										
										<div class="input-group date datePicker">
											<input type='text' class="form-control input-sm data" name="BL3_SEMCOMPR_INI" id="BL3_SEMCOMPR_INI" value="<?php echo $bl3_ucompras_ini; ?>" />
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
										</div>
										<div class="help-block with-errors"></div>
									</div>
								</div>
								
								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label">Até</label>
										
										<div class="input-group date datePicker">
											<input type='text' class="form-control input-sm data" name="BL3_SEMCOMPR_FIM" id="BL3_SEMCOMPR_FIM" value="<?php echo $bl3_ucompras_fim; ?>" />
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
										</div>
										<div class="help-block with-errors"></div>
									</div>
								</div>									
							
						</div>						
						
						<div class="col-md-6 borda">
							
							<h5>Compras sem resgates</h5>
							
								<div class="col-md-4" style="padding-left: 0;">
									<div class="form-group">
										<label for="inputName" class="control-label">Sem nenhum resgate</label> 
										<div class="push5"></div>
											<label class="switch">
											<input type="checkbox" name="BL3_LOG_SEMRESG" id="BL3_LOG_SEMRESG" class="switch" value="S" <?php echo $check_bl3_log_resgate; ?>>
											<span></span>
											</label>
									</div>
								</div>
								
								<div class="col-md-4"  style="padding-left: 0;">
									<div class="form-group">
										<label for="inputName" class="control-label">De</label>
										
										<div class="input-group date datePicker">
											<input type='text' class="form-control input-sm data" name="BL3_SEMRESG_INI" id="BL3_SEMRESG_INI" value="<?php echo $bl3_ucompras_ini; ?>" />
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
										</div>
										<div class="help-block with-errors"></div>
									</div>
								</div>
								
								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label">Até</label>
										
										<div class="input-group date datePicker">
											<input type='text' class="form-control input-sm data" name="BL3_SEMRESG_FIM" id="BL3_SEMRESG_FIM" value="<?php echo $bl3_ucompras_fim; ?>" />
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
										</div>
										<div class="help-block with-errors"></div>
									</div>
								</div>									
							
						</div>

						<?php 
						}
						?>						
						
					</div>						

					
					<div class="push10"></div>
					<hr>	
					<div class="form-group text-right col-lg-12">
						
						  <button type="button" class="btn btn-default limpaFreq"><i class="far fa-star-half-alt" aria-hidden="true"></i>&nbsp; Limpar Bloco</button>
						  <button type="button" name="CAD" id="CAD" class="btn btn-success atualiza" <?php echo $bloqueiaAlt; ?> ><i class="fa fa-check" aria-hidden="true"></i>&nbsp; Aplicar Filtro</button>
						
					</div>
					
					
	<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
	<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />	
	
	<script type="text/javascript">
	
		$(document).ready(function(){
			
		$("#BL3_TIP_RESGATE").chosen({ width: "100%" }); 
		$("#BL3_TIP_RETORNO").chosen({ width: "100%" }); 
		
		$('.datePicker').datetimepicker({
			 format: 'DD/MM/YYYY',
			 //maxDate : 'now',
			}).on('changeDate', function(e){
				$(this).datetimepicker('hide');
			});
			
		$('.clockPicker').datetimepicker({
			 format: 'LT',
			}).on('changeDate', function(e){
				$(this).datetimepicker('hide');
			});
				
		});
		
		$(".limpaFreq").click(function() {
			$("#BL3_CADASTROS_INI").val("");
			$("#BL3_CADASTROS_FIM").val("");
			$("#BL3_COMPRAS_INI").val("");
			$("#BL3_COMPRAS_FIM").val("");
			$("#BL3_UCOMPRAS_INI").val("");
			$("#BL3_UCOMPRAS_FIM").val("");
			$("#BL3_QTD_RETORNO_INI").val("");
			$("#BL3_QTD_RETORNO_FIM").val("");
			$("#BL3_LOG_RESGATE").val("N");
			$("#BL3_TIP_RESGATE").val("").trigger("chosen:updated");
			$("#BL3_QTD_RESGATE").val("");
			$("#notificaFrequencia").hide();
		});			
		
	</script>