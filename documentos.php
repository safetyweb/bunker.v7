<?php
	
	//echo fnDebug('true');
 
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
			
			$cod_documen = fnLimpaCampoZero($_REQUEST['COD_DOCUMEN']);
			$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
			$nom_documen = fnLimpaCampo($_REQUEST['NOM_DOCUMEN']);
			$des_abrevia = fnLimpaCampo($_REQUEST['DES_ABREVIA']);
			$des_descricao = fnLimpaCampo($_REQUEST['DES_DESCRICAO']);
			$cod_tipodoc = fnLimpaCampoZero($_REQUEST['COD_TIPODOC']);
			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
			
			$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
                      
			if ($opcao != ''){			
				
				$sql = "CALL SP_ALTERA_DOCUMENTOS (
				 '".$cod_documen."',
				 '".$cod_empresa."',
				 '".$cod_tipodoc."', 
				 '".$nom_documen."', 
				 '".$des_abrevia."', 
				 '".$des_descricao."',
				 '".$opcao."'    
			        );";
					// fnEscreve($sql);
                mysqli_query(connTemp($cod_empresa,''),$sql);				
				
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
	
	//busca dados da url	
	if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))){
            
		//busca dados da empresa
		$cod_empresa = fnDecode($_GET['id']);	
		$sql = "SELECT NOM_EMPRESA FROM EMPRESAS WHERE COD_EMPRESA = ".$cod_empresa;	
				
		//fnEscreve($sql);
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
			
		if (isset($qrBuscaEmpresa)){
			$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
		}
												
	}else {	
		$nom_empresa = "";
	}
	      
	//fnMostraForm();
	//fnEscreve($cod_empresa);

?>
	
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
				
					<?php $abaFormalizacao = 1077; include "abasFormalizacaoEmp.php"; ?>
					
					<div class="push30"></div> 			
					
						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
							
						<!-- <h4>- Tipo de documento (físico ou eletrônico) </h4>  -->
							
						<fieldset>
							<legend>Dados Gerais</legend> 
						
								<div class="row">
						
									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Código</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_DOCUMEN" id="COD_DOCUMEN" value="">
										</div>
										<div class="help-block with-errors"></div>
									</div>       
						
									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Empresa</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa ?>" required>
											<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">
										</div>														
									</div> 

									<div class="col-md-2">
		                                <div class="form-group">
		                                    <label for="inputName" class="control-label">Tipo de Documento</label>
			                                    <select data-placeholder="Selecione um tipo" name="COD_TIPODOC" id="COD_TIPODOC" class="chosen-select-deselect">
			                                    	<option value=""></option>
			                                    	<option value="1">Eletrônico</option>
			                                        <option value="2">Físico</option> 
			                                    </select>
			                                    <script>$("#formulario #COD_TIPODOC").val("<?php echo $cod_tipodoc; ?>").trigger("chosen:updated"); </script>
		                                    <div class="help-block with-errors"></div>
		                                </div>
		                            </div>     
						
									<div class="col-md-4">
										<div class="form-group">
											<label for="inputName" class="control-label required">Nome</label>
											<input type="text" class="form-control input-sm" name="NOM_DOCUMEN" id="NOM_DOCUMEN" value="" maxlength="50" required>
										</div>
										<div class="help-block with-errors"></div>
									</div>       
						
									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Abreviação</label>
											<input type="text" class="form-control input-sm" name="DES_ABREVIA" id="DES_ABREVIA" value="" maxlength="20" required>
										</div>
										<div class="help-block with-errors"></div>
									</div>

								</div>

								<div class="row">       
						
									<div class="col-md-12">
										<div class="form-group">
											<label for="inputName" class="control-label required">Descrição</label>
											<textarea type="text" class="form-control input-sm" rows="3" name="DES_DESCRICAO" id="DES_DESCRICAO" value="" maxlength="200" required></textarea>
										</div>
										<div class="help-block with-errors"></div>
									</div>       
							
								</div>
								
								<div class="push10"></div>
								
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

								<table class="table table-bordered table-striped table-hover tablesorter buscavel">

								  <thead>
									<tr>
									  <th width="40"></th>
									  <th>Código</th>
									  <th>Tipo de Documento</th>
									  <th>Nome</th>
									  <th>Abreviação</th>
									  <th>Descrição</th>
									</tr>
								  </thead>
								<tbody>
								
								<?php 
									$sql = "select DOCUMENTOS.COD_DOCUMEN,"
												 ."DOCUMENTOS.COD_EMPRESA,"
												 ."DOCUMENTOS.NOM_DOCUMEN,"
												 ."DOCUMENTOS.DES_ABREVIA,"
												 ."DOCUMENTOS.DES_DESCRICAO,"
												 ."DOCUMENTOS.COD_TIPODOC,"
												 ."EMPRESAS.NOM_EMPRESA "
											."from DOCUMENTOS " 
												."left join empresas ON DOCUMENTOS.COD_EMPRESA = empresas.COD_EMPRESA "
										    ."where empresas.COD_EMPRESA =  " .$cod_empresa;		
									$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
									
									$count=0;

									while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery))
									  { 													  
										$count++;	
										echo"
											<tr>
											  <td><input type='radio' name='radio1' onclick='retornaForm(".$count.")'></th>
											  <td>".$qrBuscaModulos['COD_DOCUMEN']."</td>
											  <td>";
											    if ($qrBuscaModulos['COD_TIPODOC'] == 1) {
											        echo "Eletrônico";
											    } elseif ($qrBuscaModulos['COD_TIPODOC'] == 2) {
											        echo "Físico";
											    }

											    echo "</td>
											  <td>".$qrBuscaModulos['NOM_DOCUMEN']."</td>
											  <td>".$qrBuscaModulos['DES_ABREVIA']."</td>
											  <td>".$qrBuscaModulos['DES_DESCRICAO']."</td>
											</tr>
											
											<input type='hidden' id='ret_COD_DOCUMEN_".$count."' value='".$qrBuscaModulos['COD_DOCUMEN']."'>
											<input type='hidden' id='ret_COD_EMPRESA_".$count."' value='".$qrBuscaModulos['COD_EMPRESA']."'>
											<input type='hidden' id='ret_NOM_DOCUMEN_".$count."' value='".$qrBuscaModulos['NOM_DOCUMEN']."'>
											<input type='hidden' id='ret_DES_ABREVIA_".$count."' value='".$qrBuscaModulos['DES_ABREVIA']."'>
											<input type='hidden' id='ret_DES_DESCRICAO_".$count."' value='".$qrBuscaModulos['DES_DESCRICAO']."'>
											<input type='hidden' id='ret_COD_TIPODOC_".$count."' value='".$qrBuscaModulos['COD_TIPODOC']."'>
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
		
	<div class="push20"></div> 
	
	<script type="text/javascript">
		
		function retornaForm(index){
			$("#formulario #COD_DOCUMEN").val($("#ret_COD_DOCUMEN_"+index).val());
			$("#formulario #COD_EMPRESA").val($("#ret_COD_EMPRESA_"+index).val());
			$("#formulario #NOM_DOCUMEN").val($("#ret_NOM_DOCUMEN_"+index).val());
			$("#formulario #DES_ABREVIA").val($("#ret_DES_ABREVIA_"+index).val());
			$("#formulario #DES_DESCRICAO").val($("#ret_DES_DESCRICAO_"+index).val());
			$("#formulario #COD_TIPODOC").val($("#ret_COD_TIPODOC_"+index).val()).trigger("chosen:updated");
			$('#formulario').validator('validate');
			$("#formulario #hHabilitado").val('S');
		}
		
	</script>	