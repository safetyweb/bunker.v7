<?php

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

			$cod_estadof = fnLimpaCampo($_REQUEST['COD_ESTADOF']);
			$nom_cidade = fnLimpaCampo($_REQUEST['NOM_CIDADE']);
			$des_enderec = fnLimpaCampo($_REQUEST['DES_ENDEREC']);
			$num_cep = fnLimpaCampo(fnLimpaDoc($_REQUEST['NUM_CEP']));

			// fnEscreve($cod_estadof);
			// fnEscreve($nom_cidade);
			// fnEscreve($des_enderec);
			//fnEscreve($num_cep);

			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
						
			if ($opcao != ''){				
				
				//mensagem de retorno
				switch ($opcao)
				{
					case 'CAD':
						$msgRetorno = "Consulta realizada com <strong>sucesso!</strong>";	
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
											<legend>Dados Gerais</legend> 
											
												<div class="row">

													<div class="col-md-3">
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
															<div class="help-block with-errors"></div>
														</div>
													</div>

													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Cidade</label>
																<div id="relatorioConteudo">
																	<select data-placeholder="Nenhum estado selecionado" name="NOM_CIDADE" id="NOM_CIDADE" class="chosen-select-deselect"> 							
																	</select>
																</div>
															<div class="help-block with-errors"></div>
														</div>
													</div>

													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Logradouro</label>
															<input type="text" class="form-control input-sm" name="DES_ENDEREC" id="DES_ENDEREC" maxlength="250">
															<div class="help-block with-errors"></div>
														</div>
													</div>

													<div class="col-md-1 text-center">
														<div class="form-group">
															<label for="inputName" class="control-label">&nbsp;</label>
															<p class="text-muted">OU</p>
														</div>
													</div>
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">CEP</label>
															<input type="text" class="form-control input-sm cep" name="NUM_CEP" id="NUM_CEP" maxlength="50">
															<div class="help-block with-errors"></div>
														</div>
													</div>
																				
												</div>
												
										</fieldset>	
																				
										<div class="push10"></div>
										<hr>	
										<div class="form-group text-right col-lg-12">
											
											  <button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>
											  <button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fas fa-magnifying-glass" aria-hidden="true"></i>&nbsp; Buscar</button>
											
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
												
												<table class="table table-bordered table-striped table-hover tableSorter">
												  <thead>
													<tr>
													  <th class="{ sorter: false }" width="40"></th>
													  <th>CEP</th>
													  <th>Logradouro</th>
													  <th>Complemento</th>
													  <th>Bairro</th>
													  <th>Cidade</th>
													  <th>UF</th>
													</tr>
												  </thead>
												<tbody>
												  
												<?php

													include './_system/CEP2.php';

													// [cep] => 18270-310
										   			// [logradouro] => Rua Quinze de Novembro
										   			// [complemento] => até 866/867
										   			// [bairro] => Centro
										   			// [localidade] => Tatuí
										   			// [uf] => SP
										   			// [unidade] => 
										   			// [ibge] => 3554003
										   			// [gia] => 6877

										   			// $arraydados=array('ESTADO'=>'SP',
													   //                'CIDADE'=>'TATUÍ',
													   //                'RUA'=>'NOVEMBRO',
													   //                'CEP'=>'');

													if($num_cep == ""){
														$arraydados=array(
																		'ESTADO'=>"$cod_estadof",
														                'CIDADE'=>"$nom_cidade",
														                'RUA'=>"$des_enderec",
														                'CEP'=>''
														            );
													}else{
														$arraydados=array(
																		'ESTADO'=>'',
																		'CIDADE'=>'',
																		'RUA'=>'',
																		'CEP'=>"$num_cep"
																	);
													}

													$enderecos = consulta_cep($arraydados);

													// print_r($arraydados);

													foreach ($enderecos as $key => $endereco) {
														$count++;

														$sql = "SELECT COD_ESTADO FROM ESTADO WHERE UF = '$endereco[uf]'";
														$qrEstado = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));

														$sql2 = "SELECT COD_MUNICIPIO FROM MUNICIPIOS WHERE NOM_MUNICIPIO = '$endereco[localidade]' AND COD_ESTADO = $qrEstado[COD_ESTADO]";
														$qrCidade = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql2));

														echo"
															<tr>
															  <td><a href='javascript: downForm(".$count.")' style='margin-left: 10px;'><i class='fa fa-arrow-circle-down' aria-hidden='true'></i></a></th>
															  <td>".$endereco['cep']."</td>
															  <td>".$endereco['logradouro']."</td>
															  <td>".$endereco['complemento']."</td>
															  <td>".$endereco['bairro']."</td>
															  <td>".$endereco['localidade']."</td>
															  <td>".$endereco['uf']."</td>
															</tr>
															<input type='hidden' id='ret_NUM_CEPOZOF_".$count."' value='".$endereco['cep']."'>
															<input type='hidden' id='ret_COD_ESTADOF_".$count."' value='".$endereco['uf']."'>
															<input type='hidden' id='ret_NOM_CIDADEC_".$count."' value='".$endereco['localidade']."'>
															<input type='hidden' id='ret_DES_BAIRROC_".$count."' value='".$endereco['bairro']."'>
															<input type='hidden' id='ret_DES_COMPLEM_".$count."' value='".$endereco['complemento']."'>
															<input type='hidden' id='ret_DES_ENDEREC_".$count."' value='".$endereco['logradouro']."'>
															<input type='hidden' id='ret_COD_ESTADO_".$count."' value='".$qrEstado['COD_ESTADO']."'>
															<input type='hidden' id='ret_COD_MUNICIPIO_".$count."' value='".$qrCidade['COD_MUNICIPIO']."'>
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

		$('#COD_ESTADOF').change(function(){
			var uf = $(this).val();
			$.ajax({
				method: 'POST',
				url: 'ajxBuscaCep.php',
				data: {ESTADO: uf},
				beforeSend:function(){
					$('#relatorioConteudo').html('<div class="loading" style="width: 100%;"></div>');
				},
				success:function(data){
					$('#relatorioConteudo').html(data);
					// console.log(data);
				}
			});
		});
		
		function downForm(index){
				try { parent.$('#NUM_CEPOZOF').val($("#ret_NUM_CEPOZOF_"+index).val()); } catch(err) {}		
				try { parent.$('#COD_ESTADO').val($("#ret_COD_ESTADO_"+index).val()).trigger('chosen:updated'); } catch(err) {}		
				try { parent.$('#COD_ESTADOF').val($("#ret_COD_ESTADOF_"+index).val()); } catch(err) {}		
				try { 
					$.when(parent.$('#COD_MUNICIPIO_AUX').val($("#ret_COD_MUNICIPIO_"+index).val()).trigger('chosen:updated')).then(parent.carregaComboCidades($("#ret_COD_ESTADO_"+index).val()));
				} catch(err) {}		
				try { parent.$('#NOM_CIDADEC').val($("#ret_NOM_CIDADEC_"+index).val()); } catch(err) {}			
				try { parent.$('#DES_BAIRROC').val($("#ret_DES_BAIRROC_"+index).val()); } catch(err) {}	
				try { parent.$('#DES_COMPLEM').val($("#ret_DES_COMPLEM_"+index).val()); } catch(err) {}	
				try { parent.$('#DES_ENDEREC').val($("#ret_DES_ENDEREC_"+index).val()); } catch(err) {}	
				$(this).removeData('bs.modal');	
				//console.log('entrou' + index);
				parent.$('#popModal').modal('hide');
			}
		
	</script>	