					<div class="push50"></div> 
					
					<div class="row">				
					
						<div class="col-md12 margin-bottom-30">
							<!-- Portlet -->
							<div class="portlet portlet-bordered">
								<div class="portlet-title">
									<div class="caption">
										<i class="glyphicon glyphicon-calendar"></i>
										<span class="text-primary"> Nome da Tela</span>
									</div>
									<?php include "atalhosPortlet.php"; ?>
								</div>
								<div class="portlet-body">
								
									<div class="login-form">
									
										<form data-toggle="validator" role="form2" method="post" id="formulario" action="appCode/">
										<!-- https://1000hz.github.io/bootstrap-validator/#validator-examples -->
										
										<fieldset>
											<legend>Dados Gerais</legend> 
											
													<div class="row">
														<div class="col-md-6">
															<div class="form-group">
																<label for="inputName" class="control-label required">Nome</label>
																<input type="text" class="form-control input-sm" name="nome" id="nome" placeholder="Nome" data-error="Campo obrigatório" required>
																<div class="help-block with-errors"></div>
															</div>
														</div>
														<div class="col-md-6">
															<div class="form-group">
																<label for="inputName" class="control-label required">SobreNome</label>
																<input type="text" class="form-control input-sm" name="sobrenome" id="sobrenome" placeholder="Sobre nome" data-error="Campo obrigatório" required>
																<div class="help-block with-errors"></div>
															</div>
														</div>
													</div>

													<div class="row">
														<div class="col-md-6">
															<div class="form-group">
																<label for="inputName" class="control-label required">Endereço</label>
																<input type="text" class="form-control input-sm" name="endereco" id="endereco" placeholder="Endereco" data-error="Campo obrigatório" required>
																<div class="help-block with-errors"></div>
															</div>
														</div>
														<div class="col-md-3">
															<div class="form-group">
																<label for="inputName" class="control-label">Número</label>
																<input type="text" class="form-control input-sm" name="numero" id="numero" placeholder="Número" data-error="Campo obrigatório">
																<div class="help-block with-errors"></div>
															</div>
														</div>
														<div class="col-md-3">
															<div class="form-group">
																<label for="inputName" class="control-label">Bairro</label>
																<input type="text" class="form-control input-sm" name="bairro" id="bairro" placeholder="Bairro" data-error="Campo obrigatório">
																<div class="help-block with-errors"></div>
															</div>
														</div>
													</div>
																				
										</fieldset>									
										
										<div class="push10"></div>
										<hr>	
										<div class="form-group text-right col-lg-12">
											
											  <button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>
											  <button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
											  <button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Alterar</button>
											  <button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fa fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>
											
										</div>
										
										<input type="hidden" name="opcao" id="opcao" value="">		
										<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">		
										
										<div class="push5"></div> 
										
										</form>
										
										<div class="push50"></div>
										
										<div class="col-lg-12">

											<div class="no-more-tables">
										
												<form name="formLista">
												
												<table class="table table-bordered table-striped table-hover">
												  <thead>
													<tr>
													  <th class="bg-primary" colspan="100">&nbsp;</th>
													</tr>
												  </thead>
												  <thead>
													<tr>
													  <th class="bg-primary" width="40"></th>
													  <th class="bg-primary">Nome</th>
													  <th class="bg-primary">Sobrenome</th>
													  <th class="bg-primary">Usuario</th>
													  <th class="bg-primary">e-Mail</th>
													</tr>
												  </thead>
												  <tbody>
													<?php 
													$x = 1;
													while($x <= 10) {
													?>	
													<tr>
													  <th scope="row"><input type="radio" name="radio1" onclick="retornaForm(<?php echo $x ?>)"></th>
													  <td>Nome 	<?php echo $x ?></td>
													  <td>Sobrenome <?php echo $x ?> </td>
													  <td>@user<?php echo $x ?></td>
													  <td>email<?php echo $x ?>@teste.com</td>
													</tr>
													<input type="hidden" id="ret_nome<?php echo $x ?>" value="Retorno Nome<?php echo $x ?>">
													<input type="hidden" id="ret_sobrenome<?php echo $x ?>" value="Retorno Sobrenome<?php echo $x ?>">
													<input type="hidden" id="ret_endereco<?php echo $x ?>" value="Retorno Endereço<?php echo $x ?>">
													<?php
													$x++;
													} 
													?>	
												  </tbody>
												</table>
												
												</form>

											</div>
											
										</div>										
									
									<div class="push"></div>
									
									</div>								
								
								</div>
							</div>
							<!-- fim Portlet -->
						</div>
						
					</div>					
						
					<div class="push20"></div> 
	
	<script type="text/javascript">
	
		$(document).ready(function(){	

			//tooltip
			$('[data-toggle="tooltip"]').tooltip(); 

			//pop over
			$('.popOverCb').webuiPopover({
					title: 'Popover Web UI - Combo',
					placement:'top',
					width:290,
					height:130,
					closeable:true,
					url:'#popup-content'
			});	
			
			$('.popOverCb2').webuiPopover({
					title: 'Popover Web UI - Combo',
					placement:'top',
					width:290,
					height:130,
					closeable:true,
					url:'#popup-content2'
			});	

		});	
		
		function retornaForm(index){
			$("#formulario #nome").val($("#ret_nome"+index).val());
			$("#formulario #sobrenome").val($("#ret_sobrenome"+index).val());
			$("#formulario #endereco").val($("#ret_endereco"+index).val());
			//$("#id_cli").val(eval('document.getElementById("FORMLISTA").R_id_cli' + index + '.value')).trigger("liszt:updated");
			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');						
		}
		
	</script>	