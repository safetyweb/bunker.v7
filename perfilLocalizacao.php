					<div class="push10"></div>
					
					<div class="row">					
													
						<div class="col-md-2">
							<div class="form-group">
								<label for="inputName" class="control-label">Estado</label>
									<select data-placeholder="Selecione um estado" name="COD_ESTADOF" id="COD_ESTADOF" class="chosen-select-deselect">
										<option value=""></option>					
										<option value="AC">AC</option> 
										<option value="AL">AL</option> 
										<option value="AM">AM</option> 
										<option value="AP">AP</option> 
										<option value="BA">BA</option> 
										<option value="CE">CE</option> 
										<option value="DF">DF</option> 
										<option value="ES">ES</option> 
										<option value="GO">GO</option> 
										<option value="MA">MA</option> 
										<option value="MG">MG</option> 
										<option value="MS">MS</option> 
										<option value="MT">MT</option> 
										<option value="PA">PA</option> 
										<option value="PB">PB</option> 
										<option value="PE">PE</option> 
										<option value="PI">PI</option> 
										<option value="PR">PR</option> 
										<option value="RJ">RJ</option> 
										<option value="RN">RN</option> 
										<option value="RO">RO</option> 
										<option value="RR">RR</option> 
										<option value="RS">RS</option> 
										<option value="SC">SC</option> 
										<option value="SE">SE</option> 
										<option value="SP">SP</option> 
										<option value="TO">TO</option> 							
									</select>
									<script>$("#formulario #COD_ESTADOF").val("<?php echo $cod_estadof; ?>").trigger("chosen:updated"); </script>
								<div class="help-block with-errors"></div>


							</div>
						</div>
																			
						<div class="col-md-4">
							<div class="form-group">
								<label for="inputName" class="control-label">Cidade</label>
									<select data-placeholder="Selecione uma cidade" name="COD_ESTADOF" id="COD_ESTADOF" class="chosen-select-deselect">
										<option value=""></option>					
										<option value="AC">AC</option> 		
									</select>
									<script>$("#formulario #COD_ESTADOF").val("<?php echo $cod_estadof; ?>").trigger("chosen:updated"); </script>
								<div class="help-block with-errors"></div>


							</div>
						</div>
																			
						<div class="col-md-4">
							<div class="form-group">
								<label for="inputName" class="control-label">Bairro</label>
									<select data-placeholder="Selecione um bairro" name="COD_ESTADOF" id="COD_ESTADOF" class="chosen-select-deselect">
										<option value=""></option>					
										<option value="AC">AC</option> 		
									</select>
									<script>$("#formulario #COD_ESTADOF").val("<?php echo $cod_estadof; ?>").trigger("chosen:updated"); </script>
								<div class="help-block with-errors"></div>


							</div>
						</div>
						
						
					</div>			
						
					<div class="push10"></div>
					<hr>	
					<div class="form-group text-right col-lg-12">
						
						  <button type="button" class="btn btn-default limpaGeo"><i class="far fa-star-half-alt" aria-hidden="true"></i>&nbsp; Limpar Bloco</button>
						  <button type="button" class="btn btn-success atualiza" <?php echo $bloqueiaAlt; ?> ><i class="fa fa-check" aria-hidden="true"></i>&nbsp; Aplicar Filtro</button>
						
					</div>
					
		
	<script type="text/javascript">
		
	</script>
	