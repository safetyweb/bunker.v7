<?php
	
	//echo "<h5>_".$opcao."</h5>";
	

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
			

			$cod_menusis = fnLimpaCampoZero($_REQUEST['ID']);
			$nom_menusis = fnLimpaCampo($_REQUEST['NOM_MENUSIS']);

			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
						
			if ($opcao != ''){

				$sql = "CALL SP_ALTERA_MENUS (
				 '".$cod_menusis."', 
				 '".$nom_menusis."', 
				 '".$opcao."'    
				) ";
				
				//echo $sql;
				
				mysqli_query($connAdm->connAdm(),trim($sql)) or die(mysqli_error());				
				
				//mensagem de retorno
				switch ($opcao)
				{
					case 'CAD':
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";	
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
      
	//fnMostraForm();

?>

<style>

/* layout.css Style */
.upload-drop-zone {
  height: 200px;
  border-width: 2px;
  margin-bottom: 20px;
}

/* skin.css Style*/
.upload-drop-zone {
  color: #ccc;
  border-style: dashed;
  border-color: #ccc;
  line-height: 200px;
  text-align: center
}
.upload-drop-zone.drop {
  color: #222;
  border-color: #222;
}



.file-preview-input {
    position: relative;
    overflow: hidden;
    margin: 0px;    
    color: #333;
    background-color: #fff;
    border-color: #ccc;    
}
.file-preview-input input[type=file] {
	position: absolute;
	top: 0;
	right: 0;
	margin: 0;
	padding: 0;
	font-size: 20px;
	cursor: pointer;
	opacity: 0;
	filter: alpha(opacity=0);
}
.file-preview-input-title {
    margin-left:2px;
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
										<span class="text-primary"><?php echo $NomePg; ?></span>
									</div>
									<?php include "atalhosPortlet.php"; ?>
								</div>
								<div class="portlet-body">
									
									<?php if ($msgRetorno <> '') { ?>	
									<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
									<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									 <?php echo $msgRetorno; ?>
									</div>
									<?php } ?>	
								
								
									<div class="login-form">
									
										<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
																				
										<fieldset>
											<legend>Dados Gerais</legend> 
											
												<div class="row">
													<div class="col-md-1">
														<div class="form-group">
															<label for="inputName" class="control-label required">Código</label>
															<input type="text" class="form-control input-sm leitura" readonly="readonly" name="ID" id="ID" value="">
														</div>
													</div>	

													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label required">Data / Hora</label>
															<input type="text" class="form-control input-sm leitura" readonly="readonly" name="DATAHORA" id="DATAHORA" value="<?php echo (new \DateTime())->format('d/m/Y H:i:s'); ?>">
														</div>
													</div>
										
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label required">Título do chamado</label>
															<input type="text" class="form-control input-sm" name="NOM_MENUSIS" id="NOM_MENUSIS" maxlength="20" required>
															<div class="help-block with-errors"></div>
														</div>
													</div>
													
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label required">Tipo do chamado </label>
																<select data-placeholder="Selecione o tipo de chamado" name="COD_TPUSUARIO" id="COD_TPUSUARIO" class="chosen-select-deselect" required>
																	<option value=""></option>					
																	<option value="">Suporte</option>					
																	<option value="">Correção</option>					
																	<option value="">Projeto</option>					
																	<option value="">Melhoria</option>					
																	<option value="">Dúvidas</option>					
																</select>	
                                                                                                                       
															<div class="help-block with-errors"></div>
														</div>
													</div>	
													
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label required">Status do Atendimento </label>
																<select data-placeholder="Selecione o tipo status" name="COD_TPUSUARIO" id="COD_TPUSUARIO" class="chosen-select-deselect required">
																	<option value=""></option>					
																	<option value="">Em aberto</option>					
																	<option value="">Em análise</option>					
																	<option value="">Em andamento</option>					
																	<option value="">Transferido</option>					
																	<option value="">Aguardando interação cliente</option>					
																	<option value="">Cancelado</option>					
																	<option value="">Finalizado</option>					
																</select>	
																</select>	
                                                                                                                       
															<div class="help-block with-errors"></div>
														</div>
													</div>	
																				
												</div>
												
							<div class="push10"></div>
												<div class="row">
													<div class="col-md-12">
													
			
					<div class="input-group file-preview">
						<input placeholder="" type="text" class="form-control file-preview-filename" disabled="disabled">
						<!-- don't give a name === doesn't send on POST/GET --> 
						<span class="input-group-btn"> 
						<!-- file-preview-clear button -->
						<button type="button" class="btn btn-default file-preview-clear" style="display:none;"> <span class="glyphicon glyphicon-remove"></span> Clear </button>
						<!-- file-preview-input -->
						<div class="btn btn-default file-preview-input"> <span class="glyphicon glyphicon-folder-open"></span> <span class="file-preview-input-title"> Anexar documento</span>
							<input type="file" accept="text/cfg" name="input-file-preview"/>
							<!-- rename it --> 
						</div>
						</span> </div>
					<!-- /input-group image-preview [TO HERE]--> 					
					
							<div class="push5"></div>
												
													
													</div>
												</div>	
												
												
												<div class="row">
													<div class="col-md-12">
														<div class="form-group">
															<label for="inputName" class="control-label required">Responsável / Atendimento</label>
																<select data-placeholder="Selecione um responsável" name="COD_SISTEMAS[]" id="COD_SISTEMAS" multiple="multiple" class="chosen-select-deselect required" style="width:100%;" tabindex="1">
																	<option value="">Adilson Rosa</option> 
																	<option value="">Diogo Souza</option> 
																	<option value="">Elbio Matos</option> 
																	<option value="">Marcelo Gonçalvez</option> 
																	<option value="">Ronaldo Rosa</option> 
																	<option value="">Rone Alves</option> 
																</select>
															<div class="help-block with-errors"></div>
														</div>
													</div>
												</div>	
												
												<div class="row">
													<div class="col-md-12">
														<div class="form-group">
															<label for="inputName" class="control-label">Observações</label>
																<textarea class="form-control" rows="3" name="DES_OBSERVA" id="DES_OBSERVA" maxlength="100"></textarea>
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
											  <!-- <button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fa fa-times" aria-hidden="true"></i>&nbsp; Excluir</button> -->
											
										</div>
										
										<input type="hidden" name="opcao" id="opcao" value="">
										<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
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
													  <th class="bg-primary" width="40"></th>
													  <th class="bg-primary">Chamado</th>
													  <th class="bg-primary">Cliente</th>
													  <th class="bg-primary">Operador</th>
													  <th class="bg-primary">Data abertura</th>
													  <th class="bg-primary">Tipo</th>
													  <th class="bg-primary">Assunto</th>
													  <th class="bg-primary">Status</th>
													</tr>
												  </thead>
												<tbody>
												  
												<?php 
												
													$sql = "select * from menus order by NOM_MENUSIS";
													$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
													
													$count=0;
													while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery))
													  {														  
														$count++;	
														echo"
															<tr>
															  <td><input type='radio' name='radio1' onclick='retornaForm(".$count.")'></th>
															  <td><a href='#' class='addBox' data-url='action.php?mod=x5rSl4r3UT0¢&id=QunXraEOVrg¢&idx=AWnqWDjS4bE¢&pop=true' data-title='Suporte / Tickets'>Chamado #".($count*rand(1,4)+$count)."</a></td>
															  <td><a href='action.php?mod=PvUR9sokXEM¢&id=eONfyAzWsOI¢&key=QunXraEOVrg¢' >Cliente Teste #".$count."</a></td>
															  <td></td>
															  <td>".(new \DateTime('- '.$count.' day'))->format('d/m/Y H:i:s')."</td>
															  <td>Suporte</td>
															  <td>".$qrBuscaModulos['NOM_MENUSIS']."</td>
															  <td>Em aberto</td>
															</tr> 
															<input type='hidden' id='ret_ID_".$count."' value='".$qrBuscaModulos['COD_MENUSIS']."'>
															<input type='hidden' id='ret_NOM_MENUSIS_".$count."' value='".$qrBuscaModulos['NOM_MENUSIS']."'>
															"; 
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
		
		function retornaForm(index){
			$("#formulario #ID").val($("#ret_ID_"+index).val());
			$("#formulario #NOM_MENUSIS").val($("#ret_NOM_MENUSIS_"+index).val());
			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');						
		}
		
	</script>	