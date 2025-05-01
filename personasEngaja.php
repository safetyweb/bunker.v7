					<div class="push30"></div>
					
					<div class="row">
						<div class="col-md-4">					
							<h4>Funil de Clientes por Gasto</h4>
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
						
						<div class="push10"></div>
						
						<div class="col-md-12 f12">   
							Com base nos parâmetros de ciclo/período pré configurados no mes anterior
						</div>	
						
						<div class="push20"></div>
						
					</div>			

					
					<div class="row">
						<div class="col-md-4">					
							<h4>Categoria de Clientes</h4>
						</div>
					</div>
						
					<div class="push"></div>
					
					<div class="row">
						
						<div class="push20"></div>

						<?php
							//bloqueia se não tem categoria de clieentes 
							$sql = "select count(COD_CATEGORIA) as TEM_CATEGORIA from CATEGORIA_CLIENTE WHERE COD_EMPRESA = $cod_empresa ";
							$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
							$qrBuscaCategoria = mysqli_fetch_assoc($arrayQuery);
							$tem_categoria = $qrBuscaCategoria['TEM_CATEGORIA'];									
							if ($tem_categoria > 0) {
								$bloqueia_cat = "";	
							}else{
								$bloqueia_cat = "<div class='disabledBlock'></div>";	
							}
							//fnEscreve($tem_categoria);
						?>
						
						<div class="col-md-6">
							<?php echo $bloqueia_cat; ?>
							<div class="form-group">
								<label for="inputName" class="control-label">Categorização de clientes</label>
									<select data-placeholder="Selecione uma categoria de clientes" name="BL6_FREQ_CLIENTE[]" id="BL6_FREQ_CLIENTE"  multiple="multiple"  class="chosen-select-deselect" >
										<?php
											$sql = "select * from CATEGORIA_CLIENTE WHERE COD_EMPRESA = $cod_empresa order by NUM_ORDENAC ";
								
											$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
											$arrayItens = explode(";",$bl6_freq_cliente);
											while ($qrLista = mysqli_fetch_assoc($arrayQuery)){	
												if (in_array($qrLista['COD_CATEGORIA'],$arrayItens)){ 
													$checado = " selected";
												}else{
													$checado = " ";
												}
												echo"
													  <option value='".$qrLista['COD_CATEGORIA']."' $checado>".$qrLista['NOM_FAIXACAT']."</option> 
													"; 
											}
										?>
									</select>
									
								<div class="help-block with-errors"></div>
								
							</div>
							
						</div>

						<?php
							// bloqueia se não tem frequencia U
							$sql = "select count(COD_FREQUENCIA) as TEM_CATEGORIA_U from FREQUENCIA_CLIENTE_U WHERE COD_EMPRESA = $cod_empresa ";
							$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
							$qrBuscaCategoria = mysqli_fetch_assoc($arrayQuery);
							$tem_categoria_U = $qrBuscaCategoria['TEM_CATEGORIA_U'];									
							if ($tem_categoria_U > 0) {
								$bloqueia_cat_U = "";	
							}else{
								$bloqueia_cat_U = "<div class='disabledBlock'></div>";	
							}
							//fnEscreve($tem_categoria);
						?>
						<div class="col-md-6">
							<?php echo $bloqueia_cat_U; ?>
							<div class="form-group">
								<label for="inputName" class="control-label">Funil de Clientes por Frequência</label>
									<select data-placeholder="Selecione o funil por frequência" name="BL6_FREQ_CLIENTE_U[]" id="BL6_FREQ_CLIENTE_U"  multiple="multiple"  class="chosen-select-deselect" >
										<?php
											$sql = "SELECT * FROM FREQUENCIA_CLIENTE_U WHERE COD_EMPRESA = $cod_empresa ";

											$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);

											$arrayItens = explode(";",$bl6_freq_cliente_u);

											$qrLista = mysqli_fetch_assoc($arrayQuery);	

											// if (in_array($qrLista['COD_CATEGORIA'],$arrayItens)){ 
											// 	$checado = " selected";
											// }else{
											// 	$checado = " ";
											// }
											
										?>

											<option value='4' <?=(in_array('4',$arrayItens)?"selected":"")?>><?=$qrLista['TXT_CASUAIS']?></option> 
											<option value='3' <?=(in_array('3',$arrayItens)?"selected":"")?>><?=$qrLista['TXT_FREQUENTES']?></option> 
											<option value='2' <?=(in_array('2',$arrayItens)?"selected":"")?>><?=$qrLista['TXT_FIEIS']?></option> 
											<option value='1' <?=(in_array('1',$arrayItens)?"selected":"")?>><?=$qrLista['TXT_FANS']?></option> 
									</select>
									<?php // fnEscreve($sql); ?>
								<div class="help-block with-errors"></div>
								
								<div class="push5"></div>
								<span class="f12">Com base nos parâmetros de ciclo/período pré configurados no mes anterior</span>
								
							</div>
						</div>
						
						<div class="push20"></div>
			
					</div>	
					
					
					<div class="row">
						<div class="col-md-4">					
							<h4>Ticket de Ofertas</h4>
						</div>
					</div>
						
					<div class="push"></div>
					
					<div class="row">
						
						<div class="push20"></div>
						
						<div class="col-md-2">
							<div class="form-group">
								<label for="inputName" class="control-label">Tipo de Uso</label>
									<select data-placeholder="Selecione um tipo de uso" name="BL6_TIP_TICKET" id="BL6_TIP_TICKET" class="chosen-select-deselect" >
										<option value="0" <?=($bl6_tip_ticket == 0?"selected":"")?>></option>	
										<option value="1" <?=($bl6_tip_ticket == 1?"selected":"")?>>Tickets emitidos</option>	
										<option value="2" <?=($bl6_tip_ticket == 2?"selected":"")?>>Tickets emitidos com compra</option>	
									</select>
								<div class="help-block with-errors"></div>
							</div>
						</div>
						
						<div class="col-md-2"  style="padding-left: 0;">
							<div class="form-group">
								<label for="inputName" class="control-label">De</label>
								
								<div class="input-group date datePicker">
									<input type='text' class="form-control input-sm data" name="BL6_TICKET_INI" id="BL6_TICKET_INI" value="<?php echo $bl6_ticket_ini; ?>"/>
									<span class="input-group-addon">
										<span class="glyphicon glyphicon-calendar"></span>
									</span>
								</div>
								<div class="help-block with-errors"></div>
							</div>
						</div>
						
						<div class="col-md-2">
							<div class="form-group">
								<label for="inputName" class="control-label">Até</label>
								
								<div class="input-group date datePicker">
									<input type='text' class="form-control input-sm data" name="BL6_TICKET_FIM" id="BL6_TICKET_FIM" value="<?php echo $bl6_ticket_fim; ?>"/>
									<span class="input-group-addon">
										<span class="glyphicon glyphicon-calendar"></span>
									</span>
								</div>
								<div class="help-block with-errors"></div>
							</div>
						</div>				
					
					</div>						
					
					<div class="push10"></div>
					<hr>	
					<div class="form-group text-right col-lg-12">
						
						  <button type="button" class="btn btn-default limpaEngaja"><i class="far fa-star-half-alt" aria-hidden="true"></i>&nbsp; Limpar Bloco</button>
						  <button type="button" class="btn btn-success atualiza" <?php echo $bloqueiaAlt; ?> ><i class="fa fa-check" aria-hidden="true"></i>&nbsp; Aplicar Filtro</button>
						
					</div>
					
		
	<script type="text/javascript">
	
		$(document).ready(function(){

			$(".limpaEngaja").click(function(){
				$('#BL6_ENGAJA_1').prop('checked', false);
				$('#BL6_ENGAJA_2').prop('checked', false);
				$('#BL6_ENGAJA_3').prop('checked', false);
				$('#BL6_ENGAJA_4').prop('checked', false);
				$("#BL6_FREQ_CLIENTE").val("").trigger("chosen:updated");
				$("#BL6_FREQ_CLIENTE_U").val("").trigger("chosen:updated");
				$("#BL6_TIP_TICKET").val("").trigger("chosen:updated");
				$("#BL6_TICKET_INI").val("");
				$("#BL6_TICKET_FIM").val("");
			});
	
		});

		
	</script>