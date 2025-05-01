<?php

	//echo fnDebug('true');

	// definir o numero de itens por pagina
	$itens_por_pagina = 50;
	$pagina = "1";
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
			
			$cod_schedule = fnLimpaCampoZero($_REQUEST['COD_SCHEDULE']);
			if (empty($_REQUEST['LOG_ATIVO'])) {$log_ativo='N';}else{$log_ativo=$_REQUEST['LOG_ATIVO'];}
			$cod_empresa = fnLimpaCampo($_POST['COD_EMPRESA']);
			$des_schedule = fnLimpaCampo($_POST['DES_SCHEDULE']);
			$abv_schedule = str_replace(" ", "_", fnLimpaCampo($_POST['ABV_SCHEDULE']));;
			$des_intervalo = $_POST['DES_INTERVALO'];
			$url_schedule = fnLimpaCampo($_POST['URL_SCHEDULE']);
			$data_ini = fnLimpaCampo($_POST['DATA_INI']);
			$hora_ini = fnLimpaCampo($_POST['HORA_INI']);
			$des_observa = fnLimpaCampo($_POST['DES_OBSERVA']);
	   
			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];			
			
			if ($opcao != ''){

				$sql = "CALL SP_ALTERA_SCHEDULE (
				 '".$cod_schedule."', 
				 '".$log_ativo."', 
				 '".$cod_empresa."', 
				 '".$des_schedule."', 
				 '".$abv_schedule."', 
				 '".$des_intervalo."', 
				 '".$url_schedule."', 
				 '".fnDataSql($data_ini)."', 
				 '".$hora_ini."', 
				 '".$des_observa."', 
				 '".$_SESSION["SYS_COD_USUARIO"]."', 
				 '".$opcao."'    
				) ";
				
			        //echo $sql;
				//fnEscreve($cod_submenus);
	
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

									<?php 
									//menu senhas comunicação
									$abaComunica = 1130;
									include "abasSenhasComunicacao.php";					
									?>

									<div class="push30"></div>									
								
									<div class="login-form">
									
										<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
																				
										<fieldset>
											<legend>Dados Gerais</legend> 
											
												<div class="row">

													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label required">Código</label>
															<input type="text" class="form-control input-sm leitura"  name="COD_SCHEDULE" id="COD_SCHEDULE" value="">
														</div>
													</div>
													
													<div class="col-md-1">
														<div class="form-group">
															<label for="inputName" class="control-label required">Ativo</label>
															<div class="push5"></div>
															<label class="switch">
															<input type="checkbox" name="LOG_ATIVO" id="LOG_ATIVO" class="switch" value="S" <?php echo $log_multempresa; ?> />
															<span></span>
															</label> 								
															<div class="help-block with-errors"></div>
														</div>
													</div>
													
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label required">Empresa</label>
                                                                                                                        
																<select data-placeholder="Selecione uma empresa" name="COD_EMPRESA" id="COD_EMPRESA" class="chosen-select-deselect requiredChk" required="required" >
																	<option value=""></option>					
																	<?php																	
																		
																		if ($_SESSION["SYS_COD_MASTER"] == 2 ) {
																			$sql = "select A.COD_EMPRESA, A.NOM_FANTASI, 
																			(select count(B.COD_DATABASE) FROM tab_database B where B.COD_EMPRESA = A.COD_EMPRESA) as COD_DATABASE   
																			from empresas A where A.cod_empresa <> 1 order by A.NOM_FANTASI 
																			";
																		}else {
																			$sql = "select A.COD_EMPRESA, A.NOM_FANTASI, 
																			(select count(B.COD_DATABASE) FROM tab_database B where B.COD_EMPRESA = A.COD_EMPRESA) as COD_DATABASE   
																			from empresas A where A.COD_MASTER IN (1,".$_SESSION['SYS_COD_MASTER'].",".$_SESSION["SYS_COD_MULTEMP"].") order by A.NOM_FANTASI 
																			";
                                                                                                                                                        
																		//$sql = ' SELECT * FROM EMPRESAS WHERE COD_MASTER IN (1,'.$_SESSION["SYS_COD_MASTER"].') order by NOM_FANTASI ';
																		}																	
																		
																		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
																																	
																		while ($qrListaEmpresa = mysqli_fetch_assoc($arrayQuery))
																		  {													
																			if ((int)$qrListaEmpresa['COD_DATABASE'] == 0){ $desabilitado = "disabled";}
																			else {$desabilitado = "";}
																			
																			echo"
																				  <option value='".$qrListaEmpresa['COD_EMPRESA']."' ".$desabilitado." >".$qrListaEmpresa['NOM_FANTASI']."</option> 
																				"; 
																		  }											
																	?>	
																</select>
                                                                                                         
																<script>$("#formulario #COD_EMPRESA").val("<?php echo $cod_empresaCode; ?>").trigger("chosen:updated"); </script>	
																<div class="help-block with-errors"></div>																
														</div>
													</div>
										
													<div class="col-md-4">
														<div class="form-group">
															<label for="inputName" class="control-label required">Nome do Serviço</label>
															<input type="text" class="form-control input-sm" name="DES_SCHEDULE" id="DES_SCHEDULE" maxlength="50" required>
															<div class="help-block with-errors"></div>
														</div>
													</div>
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label required">Abreviação do Serviço</label>
															<input type="text" class="form-control input-sm" name="ABV_SCHEDULE" id="ABV_SCHEDULE" maxlength="5" required>
															<div class="help-block with-errors">Sem espaços vazios</b></div>
															<div class="help-block with-errors"></div>
														</div>
													</div>
																				
												</div>
												
												<div class="push10"></div>
												
												<div class="row">
													
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Intervalo</label>
																<select data-placeholder="Selecione o minuto" name="DES_INTERVALO" id="DES_INTERVALO" class="chosen-select-deselect">
																	<option value="">&nbsp;</option>					
																	<option value="*/1 * * * *">A cada 1 minuto</option> 
                                                                                                                                        <option value="*/2 * * * *">A cada 2 minuto</option> 
                                                                                                                                        <option value="*/3 * * * *">A cada 3 minuto</option> 
																	<option value="*/5 * * * *">A cada 5 minutos</option> 
																	<option value="*/10 * * * *">A cada 10 minutos</option> 
																	<option value="*/20 * * * *">A cada 20 minutos</option> 
																	<option value="*/30 * * * *">A cada 30 minutos</option> 
																	<option value="00 */1 * * *">A cada 1 hora (24x / dia)</option> 
																	<option value="00 */3 * * *">A cada 3 horas (8x / dia)</option> 
																	<option value="00 */4 * * *">A cada 4 horas (6x / dia)</option> 
																	<option value="00 */6 * * *">A cada 6 horas (4x / dia)</option> 
																	<option value="01 23 * * *">A cada 24 horas (1x / dia)</option>
                                                                                                                                        <option value="01 10 * * *">As 10 horas</option> 
                                                                                                                                        <option value="01 06 * * *">As 06 horas</option>
                                                                                                                                        <option value="00 08 * * *">As 08 horas</option>
																</select>	
															<div class="help-block with-errors"></div>
														</div>
													</div>	
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Data Início</label>
															<input type="text" class="form-control input-sm data" name="DATA_INI" id="INI_SCHEDULE" value="<?php echo date('d/m/Y'); ?>">
														</div>														
													</div>
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Hora Início</label>
															<input type="text" class="form-control input-sm hora" name="HORA_INI" id="HOR_INI" value="<?php echo date('h:m:s'); ?>">
														</div>														
													</div>
													
													<div class="col-md-5">
														<div class="form-group">
															<label for="inputName" class="control-label">Url Completa</label>
															<input type="text" class="form-control input-sm" name="URL_SCHEDULE" id="URL_SCHEDULE" maxlength="100" value="">
														</div>														
													</div>
												
												</div>
												
												<div class="row">													
													
													<div class="col-md-12">
														<div class="form-group">
															<label for="inputName" class="control-label">Observações</label><br/>
																<textarea class="form-control" rows="3" name="DES_OBSERVA" id="DES_OBSERVA" maxlength="500"></textarea>
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
											  <!--<button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fa fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>-->
											
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
													  <th width="40"></th>
													  <th>Código</th>
													  <th>Serviço</th>
													  <th>Empresa</th>
													  <th>Data Ini.</th>
													  <th>Ativo</th>
													</tr>
												  </thead>
												<tbody>
												  
												<?php 

													//paginação
		                                            $sql = "SELECT COUNT(*) AS total_registros FROM SCHEDULE";

		                                            $retorno = mysqli_query($connAdm->connAdm(),$sql);
		                                            $total_itens_por_pagina = mysqli_fetch_assoc($retorno);

		                                            $numPaginas = ceil($total_itens_por_pagina['total_registros'] / $itens_por_pagina);
		                                            fnEscreve($numPaginas);
		                                                //variavel para calcular o início da visualização com base na página atual
		                                            $inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

													$sql = "select EMPRESAS.NOM_FANTASI,SCHEDULE.* from SCHEDULE 
															LEFT JOIN EMPRESAS ON EMPRESAS.COD_EMPRESA = SCHEDULE.COD_EMPRESA
															order by des_schedule LIMIT $inicio,$itens_por_pagina";
															
													$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
													
													$count=0;
													while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery))
													  {														  
														$count++;

														if ($qrBuscaModulos['LOG_ATIVO'] == 'S'){		
															$mostraLOG_ATIVO = '<i class="fa fa-check-square-o" aria-hidden="true"></i>';	
														}else{ $mostraLOG_ATIVO = ''; }		
														
														echo"
															<tr>
															  <td><input type='radio' name='radio1' onclick='retornaForm(".$count.")'></th>
															  <td>".$qrBuscaModulos['COD_SCHEDULE']."</td>
															  <td>".$qrBuscaModulos['DES_SCHEDULE']."</td>
															  <td>".$qrBuscaModulos['NOM_FANTASI']."</td>
															  <td><small>".fnFormatDate($qrBuscaModulos['DATA_INI']).' '.$qrBuscaModulos['HORA_INI']."</small></td>
															  <td class='text-center'>".$mostraLOG_ATIVO."</td>
															</tr>
															<input type='hidden' id='ret_COD_SCHEDULE_".$count."' value='".$qrBuscaModulos['COD_SCHEDULE']."'>
															<input type='hidden' id='ret_LOG_ATIVO_".$count."' value='".$qrBuscaModulos['LOG_ATIVO']."'>
															<input type='hidden' id='ret_COD_EMPRESA_".$count."' value='".$qrBuscaModulos['COD_EMPRESA']."'>
															<input type='hidden' id='ret_DES_SCHEDULE_".$count."' value='".$qrBuscaModulos['DES_SCHEDULE']."'>
															<input type='hidden' id='ret_ABV_SCHEDULE_".$count."' value='".$qrBuscaModulos['ABV_SCHEDULE']."'>
															<input type='hidden' id='ret_DES_INTERVALO_".$count."' value='".$qrBuscaModulos['DES_INTERVALO']."'>
															<input type='hidden' id='ret_URL_SCHEDULE_".$count."' value='".$qrBuscaModulos['URL_SCHEDULE']."'>
															<input type='hidden' id='ret_DATA_INI_".$count."' value='".$qrBuscaModulos['DATA_INI']."'>
															<input type='hidden' id='ret_HORA_INI_".$count."' value='".$qrBuscaModulos['HORA_INI']."'>
															<input type='hidden' id='ret_DES_OBSERVA_".$count."' value='".$qrBuscaModulos['DES_OBSERVA']."'>
															"; 
														  }											

												?>
													
													</tbody>
													<tfoot>
														<tr>
															<th class="" colspan="100">
																<center><ul id="paginacao" class="pagination-sm"></ul></center>
															</th>
														</tr>
													</tfoot>
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

	<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
	<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />	
	
	<script type="text/javascript">

		// var numPaginas = <?php echo $numPaginas; ?>;
        // if (numPaginas != 0) {
        //     carregarPaginacao(numPaginas);
        // }

        // function reloadPage(idPage) {
        // 	$.ajax({
        // 		type: "POST",
        // 		url: "ajxUsuarios.do?opcao=paginar&mod=<?php echo $_GET['mod']; ?>&id=<?php echo fnEncode($cod_empresa); ?>&idPage=" + idPage + "&itens_por_pagina=<?php echo $itens_por_pagina; ?>&tpUsu=<?php echo $tipoUsuario; ?>&des_sufixo=<?php echo $des_sufixo; ?>",
        // 		data: $('#formLista2').serialize(),
        // 		beforeSend: function() {
        // 			$('#relatorioConteudo').html('<div class="loading" style="width: 100%;"></div>');
        // 		},
        // 		success: function(data) {
        // 			$("#relatorioConteudo").html(data);
        // 			$(".tablesorter").trigger("updateAll");
        // 			console.log(data);

        // 			fnEditable();
        // 		},
        // 		error: function() {
        // 			$('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
        // 		}
        // 	});
        // }
		
		function retornaForm(index){
			$("#formulario #COD_SCHEDULE").val($("#ret_COD_SCHEDULE_"+index).val());
			if ($("#ret_LOG_ATIVO_"+index).val() == 'S'){$('#formulario #LOG_ATIVO').prop('checked', true);} 
			else {$('#formulario #LOG_ATIVO').prop('checked', false);}
			$("#formulario #COD_EMPRESA").val($("#ret_COD_EMPRESA_"+index).val()).trigger("chosen:updated");
			$("#formulario #DES_SCHEDULE").val($("#ret_DES_SCHEDULE_"+index).val());
			$("#formulario #ABV_SCHEDULE").val($("#ret_ABV_SCHEDULE_"+index).val());
			$("#formulario #DES_INTERVALO").val($("#ret_DES_INTERVALO_"+index).val()).trigger("chosen:updated");
			$("#formulario #URL_SCHEDULE").val($("#ret_URL_SCHEDULE_"+index).val());
			$("#formulario #DATA_INI").val($("#ret_DATA_INI_"+index).val());
			$("#formulario #HORA_INI").val($("#ret_HORA_INI_"+index).val());
			$("#formulario #DES_OBSERVA").val($("#ret_DES_OBSERVA_"+index).val());
			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');						
		}


		
	</script>	