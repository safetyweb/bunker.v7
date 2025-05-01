					<div class="push10"></div>
					
					<div class="row">

						<div class="col-md-6">						
							
							<h5>Valor da compra</h5>
							
							<div class="col-md-6" style="padding-left: 0;">
								<div class="form-group">
									<label for="inputName" class="control-label">Mínimo</label> 
									<input type="text" class="form-control text-center input-sm money" name="BL4_COMPRA_MIN" id="BL4_COMPRA_MIN" maxlength="12"  value="<?php echo $bl4_compra_min; ?>" data-error="Campo obrigatório" >
									<div class="help-block with-errors"></div>
								</div>														
							</div>
							
							<div class="col-md-6">
								<div class="form-group">
									<label for="inputName" class="control-label">Máximo</label> 
									<input type="text" class="form-control text-center input-sm money" name="BL4_COMPRA_MAX" id="BL4_COMPRA_MAX" maxlength="12"  value="<?php echo $bl4_compra_max; ?>" data-error="Campo obrigatório" >
									<div class="help-block with-errors"></div>
								</div>														
							</div>	
						
						</div>
						
						<div class="col-md-6">						
							
							<h5>Valor do ticket médio</h5>
							
							<div class="col-md-6" style="padding-left: 0;">
								<div class="form-group">
									<label for="inputName" class="control-label">Mínimo</label> 
									<input type="text" class="form-control text-center input-sm money" name="BL4_VALORTM_MIN" id="BL4_VALORTM_MIN" maxlength="12"  value="<?php echo $bl4_valortm_min; ?>" data-error="Campo obrigatório" >
									<div class="help-block with-errors"></div>
								</div>														
							</div>	
							
							<div class="col-md-6">
								<div class="form-group">
									<label for="inputName" class="control-label">Máximo</label> 
									<input type="text" class="form-control text-center input-sm money" name="BL4_VALORTM_MAX" id="BL4_VALORTM_MAX" maxlength="12"  value="<?php echo $bl4_valortm_max; ?>" data-error="Campo obrigatório" >
									<div class="help-block with-errors"></div>
								</div>														
							</div>	
						
						</div>
						
					</div>
					
					<div class="push10"></div>

					<div class="row">
					
						<div class="col-md-6">
							
							<h5>Gasto em compras no período</h5>
							
							<div class="col-md-6"  style="padding-left: 0;">
								<div class="form-group">
									<label for="inputName" class="control-label">Mínimo</label>
										<input type='text' class="form-control text-center input-sm money" name="BL4_GASTOS_MIN" id="BL4_GASTOS_MIN" maxlength="12" value="<?php echo $bl4_gastos_min; ?>" />
									<div class="help-block with-errors"></div> 
								</div>
							</div>
							
							<div class="col-md-6">
								<div class="form-group">
									<label for="inputName" class="control-label">Máximo</label>
										<input type='text' class="form-control text-center input-sm money" name="BL4_GASTOS_MAX" id="BL4_GASTOS_MAX" maxlength="12" value="<?php echo $bl4_gastos_max; ?>"/>
									<div class="help-block with-errors"></div>
								</div>
							</div>				
						
						</div>
						
					</div>
															
					<div class="push10"></div>
					
					<div class="row">

						<div class="col-md-6">						
							
							<h5>Valor de créditos/pontos <b>disponíveis</b></h5>
							
							<div class="col-md-6" style="padding-left: 0;">
								<div class="form-group">
									<label for="inputName" class="control-label">Mínimo</label> 
									<input type="text" class="form-control text-center input-sm money" name="BL4_CREDITO_MIN" id="BL4_CREDITO_MIN" maxlength="12"  value="<?php echo $bl4_credito_min; ?>" data-error="Campo obrigatório" >
									<div class="help-block with-errors"></div>
								</div>														
							</div>
							
							<div class="col-md-6">
								<div class="form-group">
									<label for="inputName" class="control-label">Máximo</label> 
									<input type="text" class="form-control text-center input-sm money" name="BL4_CREDITO_MAX" id="BL4_CREDITO_MAX" maxlength="12"  value="<?php echo $bl4_credito_max; ?>" data-error="Campo obrigatório" >
									<div class="help-block with-errors"></div>
								</div>														
							</div>	
						
						</div>
						
						<div class="col-md-6">						
							
							<h5>Valor de resgate</h5>
							<!--
							<div class="col-md-4" style="padding-left: 0;">
								<div class="form-group">
									<label for="inputName" class="control-label">Intervalo</label>
										<select data-placeholder="Selecione um resgate" name="BL4_TIP_RESGATE" id="BL4_TIP_RESGATE" class="chosen-select-deselect">
											<option value="">&nbsp;</option>					
											<option value="Acima">Maior que</option>					
											<option value="Abaixo">Menor que</option>					
											<option value="Igual">Igual</option>					
										</select>	
									<script>$("#formulario #BL4_TIP_RESGATE").val("<?php echo $bl4_tip_resgate; ?>").trigger("chosen:updated"); </script>	
									<div class="help-block with-errors"></div>
								</div>
							</div>			
							-->
							
							<input type="hidden" name="BL4_TIP_RESGATE" id="BL4_TIP_RESGATE" value="">
							
							<div class="col-md-6" style="padding-left: 0;">
								<div class="form-group">
									<label for="inputName" class="control-label">Mínimo</label> 
									<input type="text" class="form-control text-center input-sm money" name="BL4_QTD_RESGATE_MIN" id="BL4_QTD_RESGATE_MIN" maxlength="9"  value="<?php echo $bl4_qtd_resgate_min; ?>" data-error="Campo obrigatório" >
									<div class="help-block with-errors"></div>
								</div>														
							</div>	
						
							<div class="col-md-6">
								<div class="form-group">
									<label for="inputName" class="control-label">Máximo</label> 
									<input type="text" class="form-control text-center input-sm money" name="BL4_QTD_RESGATE" id="BL4_QTD_RESGATE" maxlength="9"  value="<?php echo $bl4_qtd_resgate; ?>" data-error="Campo obrigatório" >
									<div class="help-block with-errors"></div>
								</div>														
							</div>	
						
						</div>
						
					</div>

					<div class="push10"></div>
					
					<div class="row">
						
						<div class="col-md-6">						
							
							<h5>Período de <b>expiração</b> de créditos/pontos</h5>
							
							<div class="col-md-6" style="padding-left: 0;">
								<div class="form-group">
									<label for="inputName" class="control-label">Quantidade</label> 
									<input type="text" class="form-control text-center input-sm int" name="BL4_QTD_AVENCER" id="BL4_QTD_AVENCER" maxlength="3"  value="<?php echo $bl4_qtd_avencer; ?>" data-error="Campo obrigatório" >
									<div class="help-block with-errors"></div>
								</div>														
							</div>	
							
							<div class="col-md-4">
								<div class="form-group">
									<label for="inputName" class="control-label">Intervalo</label>
										<select data-placeholder="Selecione um resgate" name="BL4_TIP_AVENCER" id="BL4_TIP_AVENCER" class="chosen-select-deselect">
											<option value="">&nbsp;</option>					
											<option value="D">Dias</option>					
											<!--<option value="M">Meses</option>-->
										</select>
									<script>$("#formulario #BL4_TIP_AVENCER").val("<?php echo $bl4_tip_avencer; ?>").trigger("chosen:updated"); </script>
									<div class="help-block with-errors"></div>
								</div>
							</div>	
							
						</div>	
						
						<div class="col-md-6">						
							
							<h5>Saldo <b>total</b> de expiração </h5>
							
							<!--
							<div class="col-md-4" style="padding-left: 0;">
								<div class="form-group">
									<label for="inputName" class="control-label">Intervalo</label>
										<select data-placeholder="Selecione um resgate" name="BL4_TIP_SALDO" id="BL4_TIP_SALDO" class="chosen-select-deselect">
											<option value="">&nbsp;</option>					
											<option value="Acima">Maior que</option>					
											<option value="Abaixo">Menor que</option>					
											<option value="Igual">Igual</option>					
										</select>
									<script>$("#formulario #BL4_TIP_SALDO").val("<?php echo $bl4_tip_saldo; ?>").trigger("chosen:updated"); </script>
									<div class="help-block with-errors"></div>
								</div>
							</div>			
							-->
							
							<input type="hidden" name="BL4_TIP_SALDO" id="BL4_TIP_SALDO" value="">
							
							<div class="col-md-6" style="padding-left: 0;">
								<div class="form-group">
									<label for="inputName" class="control-label">Mínimo</label> 
									<input type="text" class="form-control text-center input-sm money" name="BL4_VAL_SALDO_MIN" id="BL4_VAL_SALDO_MIN" maxlength="9"  value="<?php echo $bl4_val_saldo_min; ?>" data-error="Campo obrigatório" >
									<div class="help-block with-errors"></div>
								</div>														
							</div>	
							
							<div class="col-md-6">
								<div class="form-group">
									<label for="inputName" class="control-label">Máximo</label> 
									<input type="text" class="form-control text-center input-sm money" name="BL4_VAL_SALDO" id="BL4_VAL_SALDO" maxlength="12"  value="<?php echo $bl4_val_saldo; ?>" data-error="Campo obrigatório" >
									<div class="help-block with-errors"></div>
								</div>														
							</div>
							
						</div>
						
					</div>	
					
					<div class="push10"></div>
					<hr>	
					<div class="form-group text-right col-lg-12">
						
						  <button type="button" class="btn btn-default limpaValor"><i class="far fa-star-half-alt" aria-hidden="true"></i>&nbsp; Limpar Bloco</button>
						  <button type="button" class="btn btn-success atualiza" <?php echo $bloqueiaAlt; ?> ><i class="fa fa-check" aria-hidden="true"></i>&nbsp; Aplicar Filtro</button>
						
					</div>
					
		
	<script type="text/javascript">
	
		$(document).ready(function(){
			
			//$("#BL4_TIP_RESGATE").chosen({ width: "100%" }); 
			$("#BL4_TIP_AVENCER").chosen({ width: "100%" }); 
			//$("#BL4_TIP_SALDO").chosen({ width: "100%" }); 
				
		});
		
		$(".limpaValor").click(function() { 
			$("#BL4_COMPRA_MIN").val("");
			$("#BL4_COMPRA_MAX").val("");
			$("#BL4_VALORTM_MIN").val("");
			$("#BL4_VALORTM_MAX").val("");
			$("#BL4_CREDITO_MIN").val("");
			$("#BL4_CREDITO_MAX").val("");
			$("#BL4_TIP_RESGATE").val("").trigger("chosen:updated");
			$("#BL4_QTD_AVENCER").val("");
			$("#BL4_QTD_AVENCER").val("");
			$("#BL4_TIP_AVENCER").val("").trigger("chosen:updated");
			$("#BL4_TIP_SALDO").val("").trigger("chosen:updated");
			$("#BL4_QTD_RESGATE_MIN").val("");
			$("#BL4_QTD_RESGATE").val("");
			$("#BL4_GASTOS_MIN").val("");
			$("#BL4_GASTOS_MAX").val("");
			$("#BL4_VAL_SALDO_MIN").val("");
			$("#BL4_VAL_SALDO").val("");
			$("#notificaValor").hide();
		});		
		
	</script>