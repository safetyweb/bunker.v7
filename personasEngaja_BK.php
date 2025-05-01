					<div class="push30"></div>
					
					<div class="row">
						<div class="col-md-4">					
							<h4>Tipo de Engajamento</h4>
						</div>
					</div>
						
					<div class="push"></div>
					
					<div class="row">

						<div class="col-md-3">   
							<div class="form-group">
								<h5>Casual </h5>
								<div class="push5"></div>
									<label class="switch">
									<input type="checkbox" name="BL6_ENGAJA_1" id="BL6_ENGAJA_1" class="switch" value="S" <?php echo $check_bl6_engaja_1; ?>>
									<span></span>
									</label>
							</div>
							<div class="push5"></div>
							<div class="help-block with-errors">Casual (1)</div>
						</div>


						<div class="col-md-3">   
							<div class="form-group">
								<h5>Frequente </h5>
								<div class="push5"></div>
									<label class="switch">
									<input type="checkbox" name="BL6_ENGAJA_2" id="BL6_ENGAJA_2" class="switch" value="S" <?php echo $check_bl6_engaja_2; ?>>
									<span></span>
									</label>
							</div>
							<div class="push5"></div>
							<div class="help-block with-errors">Frequente (2)</div>
						</div>				
						
						<div class="col-md-3">   
							<div class="form-group">
								<h5>Fiel </h5>
								<div class="push5"></div>
									<label class="switch">
									<input type="checkbox" name="BL6_ENGAJA_3" id="BL6_ENGAJA_3" class="switch" value="S" <?php echo $check_bl6_engaja_3; ?>>
									<span></span>
									</label>
							</div>
							<div class="push5"></div>
							<div class="help-block with-errors">Fiel (3)</div>
						</div>	
						
						<div class="col-md-3">   
							<div class="form-group">
								<h5>Fã </h5>
								<div class="push5"></div>
									<label class="switch">
									<input type="checkbox" name="BL6_ENGAJA_4" id="BL6_ENGAJA_4" class="switch" value="S" <?php echo $check_bl6_engaja_4; ?>>
									<span></span>
									</label>
							</div>
							<div class="push5"></div>
							<div class="help-block with-errors">Fã (4)</div>
						</div>	
						
						<div class="push20"></div>
			
					</div>			
						
					<?php
					//para aparecer somente para user adm - enquanto grava						
					if($_SESSION["SYS_COD_MASTER"] == 2){
					?>
					
					<div class="row">
						<div class="col-md-4">					
							<h4>Tipo de Categoria</h4>
						</div>
					</div>
						
					<div class="push"></div>
					
					<div class="row">
						
						<div class="push20"></div>
						
						<div class="col-md-6 borda">
							<div class="form-group">
								<label for="inputName" class="control-label">Categoriação de clientes</label>
									<select data-placeholder="Selecione um estado" name="BL5_COD_ESTADOF[]" id="BL5_COD_ESTADOF"  multiple="multiple"  class="chosen-select-deselect" <?=$rqrCOD_ESTADOF?>>
										<option value=""></option>					
										<option value="AC">Acre</option> 
										<option value="AL">Alagoas</option> 
										<option value="AM">Amazonas</option> 
										<option value="AP">Amapá</option> 
										<option value="BA">Bahia</option> 
									</select>
									<script>$("#formulario #COD_ESTADOF").val("<?php echo @$cod_estadof; ?>").trigger("chosen:updated"); </script>
								<div class="help-block with-errors"></div>
							</div>
						</div>	
						
						<div class="col-md-6 borda">
							<div class="form-group">
								<label for="inputName" class="control-label">Categoriação exclusiva</label>
									<select data-placeholder="Selecione um estado" name="BL5_COD_ESTADOF[]" id="BL5_COD_ESTADOF"  multiple="multiple"  class="chosen-select-deselect" <?=$rqrCOD_ESTADOF?>>
										<option value=""></option>					
										<option value="AC">Acre</option> 
										<option value="AL">Alagoas</option> 
										<option value="AM">Amazonas</option> 
										<option value="AP">Amapá</option> 
										<option value="BA">Bahia</option> 
									</select>
									<script>$("#formulario #COD_ESTADOF").val("<?php echo @$cod_estadof; ?>").trigger("chosen:updated"); </script>
								<div class="help-block with-errors"></div>
							</div>
						</div>
						
						<div class="push20"></div>
			
					</div>	

					<?php 
					}
					?>
						
					<div class="push10"></div>
					<hr>	
					<div class="form-group text-right col-lg-12">
						
						  <button type="button" class="btn btn-default limpaGeo"><i class="far fa-star-half-alt" aria-hidden="true"></i>&nbsp; Limpar Bloco</button>
						  <button type="button" class="btn btn-success atualiza" <?php echo $bloqueiaAlt; ?> ><i class="fa fa-check" aria-hidden="true"></i>&nbsp; Aplicar Filtro</button>
						
					</div>
					
		
	<script type="text/javascript">
	
		$(document).ready(function(){


				
		});


		/*
		$(".limpaGeo").click(function() {
			$("#BL5_COD_UNIVE").val("").trigger("chosen:updated");
			$("#notificaGeo").hide();
		});		
		*/
		
	</script>