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
									<select data-placeholder="Selecione" name="BL6_FREQ_CLIENTE[]" id="BL6_FREQ_CLIENTE"  multiple="multiple"  class="chosen-select-deselect" <?=$rqrCOD_ESTADOF?>>
										<?php
											$sql = "
													SELECT
														1 COD_CATEGORIA,
														(IFNULL((SELECT IF(TXT_CASUAIS='',NULL,TXT_CASUAIS) FROM frequencia_cliente WHERE COD_EMPRESA=$cod_empresa),'Casual')) DESCRICAO
													UNION
													SELECT
														2 COD_CATEGORIA,
														(IFNULL((SELECT IF(TXT_FREQUENTES='',NULL,TXT_FREQUENTES) FROM frequencia_cliente WHERE COD_EMPRESA=$cod_empresa),'Frequente')) DESCRICAO
													UNION
													SELECT
														3 COD_CATEGORIA,
														(IFNULL((SELECT IF(TXT_FIEIS='',NULL,TXT_FIEIS) FROM frequencia_cliente WHERE COD_EMPRESA=$cod_empresa),'Fiel')) DESCRICAO
													UNION
													SELECT
														4 COD_CATEGORIA,
														(IFNULL((SELECT IF(TXT_FANS='',NULL,TXT_FANS) FROM frequencia_cliente WHERE COD_EMPRESA=$cod_empresa),'Fã')) DESCRICAO";

								
											$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
											$arrayItens = explode(";",$bl6_freq_cliente);
											while ($qrLista = mysqli_fetch_assoc($arrayQuery)){	
												if (in_array($qrLista['COD_CATEGORIA'],$arrayItens)){ 
													$checado = " selected";
												}else{
													$checado = " ";
												}
												echo"
													  <option value='".$qrLista['COD_CATEGORIA']."' $checado>".$qrLista['DESCRICAO']."</option> 
													"; 
											}
										?>
									</select>
									<script>$("#formulario #COD_ESTADOF").val("<?php echo @$cod_estadof; ?>").trigger("chosen:updated"); </script>
								<div class="help-block with-errors"></div>
							</div>
						</div>	
						
						<div class="col-md-6 borda">
							<div class="form-group">
								<label for="inputName" class="control-label">Categorização exclusiva</label>
									<select data-placeholder="Selecione" name="BL6_FREQ_CLIENTE_U[]" id="BL6_FREQ_CLIENTE_U"  multiple="multiple"  class="chosen-select-deselect" <?=$rqrCOD_ESTADOF?>>
										<?php
											$sql = "
													SELECT
														4 COD_CATEGORIA,
														(IFNULL((SELECT IF(TXT_FANS='',NULL,TXT_FANS) FROM FREQUENCIA_CLIENTE_U WHERE COD_EMPRESA=$cod_empresa),'Fã')) DESCRICAO
													UNION
													SELECT
														3 COD_CATEGORIA,
														(IFNULL((SELECT IF(TXT_FIEIS='',NULL,TXT_FIEIS) FROM FREQUENCIA_CLIENTE_U WHERE COD_EMPRESA=$cod_empresa),'Fiel')) DESCRICAO
													UNION
													SELECT
														2 COD_CATEGORIA,
														(IFNULL((SELECT IF(TXT_FREQUENTES='',NULL,TXT_FREQUENTES) FROM FREQUENCIA_CLIENTE_U WHERE COD_EMPRESA$cod_empresa7),'Frequente')) DESCRICAO
													UNION
													SELECT
														1 COD_CATEGORIA,
														(IFNULL((SELECT IF(TXT_CASUAIS='',NULL,TXT_CASUAIS) FROM FREQUENCIA_CLIENTE_U WHERE COD_EMPRESA=$cod_empresa),'Casual')) DESCRICAO";

								
											$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
											$arrayItens = explode(";",$bl6_freq_cliente_u);
											while ($qrLista = mysqli_fetch_assoc($arrayQuery)){	
												if (in_array($qrLista['COD_CATEGORIA'],$arrayItens)){ 
													$checado = " selected";
												}else{
													$checado = " ";
												}
												echo"
													  <option value='".$qrLista['COD_CATEGORIA']."' $checado>".$qrLista['DESCRICAO']."</option> 
													"; 
											}
										?>
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

		
	</script>