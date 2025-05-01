<?php

	echo fnDebug('true');
	
	if (@$_GET["tp"] == "popup_unive"){
		include("usuariosUnidades.php");
	}else{
		
		$hashLocal = mt_rand();	
		
		// definir o numero de itens por pagina
		$itens_por_pagina = 50;	
		$pagina  = "1";	

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

				$cod_empresa = fnLimpacampoZero($_REQUEST['COD_EMPRESA']);
				$cod_turno = fnLimpacampoZero($_REQUEST['COD_TURNO']);
				$nom_turno = fnLimpacampo($_REQUEST['NOM_TURNO']);
				$hor_entrada = fnLimpacampo($_REQUEST['HOR_ENTRADA']);
				$hor_saida = fnLimpacampo($_REQUEST['HOR_SAIDA']);
				$s = explode(":",$hor_saida);
				$s[2] = "59";
				$hor_saida = implode(":",$s);
				for ($i = 1;$i <= 7;$i++){
					$log_semana = "log_semana_$i";
					$$log_semana = (fnLimpacampo(@$_REQUEST['LOG_SEMANA_'.$i]) == ""?"N":fnLimpacampo(@$_REQUEST['LOG_SEMANA_'.$i]));
				}
				$log_diapar = (fnLimpacampo(@$_REQUEST['LOG_DIAPAR']) == ""?"N":fnLimpacampo(@$_REQUEST['LOG_DIAPAR']));
				$log_diaimpar = (fnLimpacampo(@$_REQUEST['LOG_DIAIMPAR']) == ""?"N":fnLimpacampo(@$_REQUEST['LOG_DIAIMPAR']));
				$opcao = ($_REQUEST['opcao'] == ""?"CAD":$_REQUEST['opcao']);
				$hHabilitado = $_REQUEST['hHabilitado'];
				$hashForm = $_REQUEST['hashForm'];

				$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

				if ($opcao == "CAD"){
		   
					$sql = "INSERT INTO turnostrabalho (
								COD_EMPRESA,
								NOM_TURNO,
								HOR_ENTRADA,
								HOR_SAIDA,
								LOG_SEMANA_1,
								LOG_SEMANA_2,
								LOG_SEMANA_3,
								LOG_SEMANA_4,
								LOG_SEMANA_5,
								LOG_SEMANA_6,
								LOG_SEMANA_7,
								LOG_DIAPAR,
								LOG_DIAIMPAR,
								COD_USUCADA,
								DAT_CADASTR
							) VALUES (
								'$cod_empresa',
								'$nom_turno',
								'$hor_entrada',
								'$hor_saida',
								'$log_semana_1',
								'$log_semana_2',
								'$log_semana_3',
								'$log_semana_4',
								'$log_semana_5',
								'$log_semana_6',
								'$log_semana_7',
								'$log_diapar',
								'$log_diaimpar',
								'$cod_usucada',
								NOW()
							)";
					
				}elseif ($opcao == "ALT"){

					$sql = "UPDATE turnostrabalho SET
								NOM_TURNO='$nom_turno',
								HOR_ENTRADA='$hor_entrada',
								HOR_SAIDA='$hor_saida',
								LOG_SEMANA_1='$log_semana_1',
								LOG_SEMANA_2='$log_semana_2',
								LOG_SEMANA_3='$log_semana_3',
								LOG_SEMANA_4='$log_semana_4',
								LOG_SEMANA_5='$log_semana_5',
								LOG_SEMANA_6='$log_semana_6',
								LOG_SEMANA_7='$log_semana_7',
								LOG_DIAPAR='$log_diapar',
								LOG_DIAIMPAR='$log_diaimpar',
								COD_ALTERAC=$cod_usucada,
								DAT_ALTERAC=NOW()
							WHERE COD_EMPRESA='$cod_empresa' AND COD_TURNO = '$cod_turno'";

				}elseif ($opcao == "EXC"){

					$sql = "DELETE FROM turnostrabalho
							WHERE COD_EMPRESA='$cod_empresa' AND COD_TURNO = '$cod_turno'";

				}
				mysqli_query($connAdm->connAdm(),trim($sql)) or die(mysqli_error());

				//mensagem de retorno
				switch ($opcao)
				{
					case 'CAD':

						$sql = "SELECT MAX(COD_USUARIO) AS COD_USUARIO FROM USUARIOS WHERE COD_EMPRESA = $cod_empresa";
						$qrCod = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(),trim($sql)));

						$cod_usuario = $qrCod['COD_USUARIO'];

						if ($_SESSION["SYS_COD_SISTEMA"] == 16) {

							$sqlAgenda = "INSERT INTO USUARIOS_AGENDA(
												COD_EMPRESA,
												COD_USUARIO,
												COD_USUARIOS_AGE,
												COD_USUCADA
												) VALUES(
												$cod_empresa,
												$cod_usuario,
												'$cod_usuarios_age',
												$cod_usucada
												)";
							// fnEscreve($sqlAgenda);
							mysqli_query($connAdm->connAdm(),trim($sqlAgenda));

						}

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
		
		//busca dados da url	
		if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))){
			//busca dados da empresa
			$cod_empresa = fnDecode($_GET['id']);	
			$sql = "SELECT COD_EMPRESA, NOM_FANTASI, DES_SUFIXO FROM empresas where COD_EMPRESA = '".$cod_empresa."' ";
				 
			//fnEscreve($sql);
			$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
			$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
			
			if (isset($qrBuscaEmpresa)){
				$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
				$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
			}
													
		}else {
			$cod_empresa = 0;		
			//fnEscreve('entrou else');
		}



	?>
			
						<div class="push30"></div> 
						
						<div class="row">				
						
							<div class="col-md12 margin-bottom-30">
								<!-- Portlet -->
								<div class="portlet portlet-bordered">
									<div class="portlet-title">
										<div class="caption">
											<i class="fal fa-terminal"></i>
											<span class="text-primary"> <?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
										</div>
										
										<?php 
										$formBack = "1019";
										include "atalhosPortlet.php"; 
										?>	

									</div>
									<div class="portlet-body">
				
										<?php if ($msgRetorno <> '') { ?>	
										<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
										<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										 <?php echo $msgRetorno; ?>
										</div>
										<?php } ?>	
										
										<?php 
										//menu superior - empresas
										$abaEmpresa = 1017;										
										
										switch ($_SESSION["SYS_COD_SISTEMA"]) {
											case 14: //rede duque
												include "abasEmpresaDuque.php";
												break;
											case 15: //quiz
												include "abasEmpresaQuiz.php";
												break;
											case 16: //gabinete
												include "abasGabinete.php";
												break;
											default;
												include "abasEmpresaConfig.php";
												break;
										}									
										
										?>	
										
										<div class="push20"></div> 
										
										<?php
										$abaUsuario = fnDecode($_GET['mod']);
										include "abasUsuariosEmpresa.php";
										?>
										
										<div class="push30"></div> 
				
										<div class="login-form">
										
											<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
																													
											<fieldset>
												<legend>Dados do Turno</legend> 
												
													<div class="row">		

														<div class="col-md-1">
															<div class="form-group">
																<label for="inputName" class="control-label required">Código</label>
																<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_TURNO" id="COD_TURNO" value="">
															</div>
														</div>
	
														<div class="col-md-3">
															<div class="form-group">
																<label for="inputName" class="control-label required">Nome do Turno</label>
																<input type="text" class="form-control input-sm" name="NOM_TURNO" id="NOM_TURNO" maxlength="50" data-error="Campo obrigatório" required>
																<div class="help-block with-errors"></div>
															</div>
														</div>

														<div class="col-md-1">   
															<div class="form-group">
																<label for="inputName" class="control-label">Dia Par</label> 
																<div class="push5"></div>
																	<label class="switch switch-small">
																	<input type="checkbox" name="LOG_DIAPAR" id="LOG_DIAPAR" class="switch" value="S" checked>
																	<span></span>
																	</label>
															</div>
														</div>

														<div class="col-md-1">   
															<div class="form-group">
																<label for="inputName" class="control-label">Dia Ímpar</label> 
																<div class="push5"></div>
																	<label class="switch switch-small">
																	<input type="checkbox" name="LOG_DIAIMPAR" id="LOG_DIAIMPAR" class="switch" value="S" checked>
																	<span></span>
																	</label>
															</div>
														</div>
														
														<div class="push10"></div>
														
														<div class="col-md-2">
															<div class="form-group">
																<label for="inputName" class="control-label">Hora Entrada</label>
																<input type="time" class="form-control input-sm" name="HOR_ENTRADA" id="HOR_ENTRADA" value="">
															</div>
														</div>
														

														<div class="col-md-2">
															<div class="form-group">
																<label for="inputName" class="control-label">Hora Saída</label>
																<input type="time" class="form-control input-sm" name="HOR_SAIDA" id="HOR_SAIDA" value="">
															</div>
														</div>
														
														<?php
														$diasemana = array('Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado');
														for ($i = 1;$i <= 7;$i++){
														?>
														
															<div class="col-md-1">   
																<div class="form-group">
																	<label for="inputName" class="control-label"><?=$diasemana[$i-1];?></label> 
																	<div class="push5"></div>
																		<label class="switch switch-small">
																		<input type="checkbox" name="LOG_SEMANA_<?=$i?>" id="LOG_SEMANA_<?=$i?>" class="switch" value="S">
																		<span></span>
																		</label>
																</div>
															</div>

														<?php
														}
														?>														
													

													</div>
													
												
											</fieldset>	


											<div class="push10"></div>

											<hr>	
											<div class="form-group text-right col-lg-12">
												<button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
												<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
												<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>
												<button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fal fa-times" aria-hidden="true"></i>&nbsp; Excluir</button> 

											</div>
											
											<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
											<input type="hidden" name="opcao" id="opcao" value="">
											<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
											<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">		
											
											<div class="push5"></div> 
											
											</form>

											
											<div class="push30"></div>
											
											<div class="col-lg-12">

												<div class="no-more-tables">
											
													<form name="formLista">
													
													<table class="table table-bordered table-striped table-hover tablesorter buscavel">
													  <thead>
														<tr>
														  <th class="{sorter:false}" width="40"></th>
														  <th>Código</th>
														  <th>Nome do Turno</th>
														  <th>Hora Entrada</th>
														  <th>Hora Saída</th>
														  <th>Par</th>
														  <th>Ímpar</th>
														  <th>Dom</th>
														  <th>Seg</th>
														  <th>Ter</th>
														  <th>Qua</th>
														  <th>Qui</th>
														  <th>Sex</th>
														  <th>Sáb</th>
														</tr>
													  </thead>
													<tbody id="relatorioConteudo">	
													
													<?php

														$sql = "SELECT * FROM TURNOSTRABALHO 
														WHERE COD_EMPRESA = $cod_empresa 
														AND COD_EXCLUSA=0
														ORDER BY NOM_TURNO";

														$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
														
														$count=0;

														while ($qrListaUsuario = mysqli_fetch_assoc($arrayQuery)){

															$count++;

//																$mostraAtivo = '<i class='fas fa-check' aria-hidden='true'></i>';	
																  
															echo "
																<tr>
																  <td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(".$count.")'></th>
																  <td>".$qrListaUsuario['COD_TURNO']."</td>
																  <td>".$qrListaUsuario['NOM_TURNO']."</td>
																  <td align='center'>".$qrListaUsuario['HOR_ENTRADA']."</td>
																  <td align='center'>".$qrListaUsuario['HOR_SAIDA']."</td>
																  <td align='center'>".($qrListaUsuario['LOG_DIAPAR']=="S"?"<i class='fal fa-check' aria-hidden='true'></i>":"<i class='text-danger fal fa-times' aria-hidden='true'></i>")."</td>
																  <td align='center'>".($qrListaUsuario['LOG_DIAIMPAR']=="S"?"<i class='fal fa-check' aria-hidden='true'></i>":"<i class='text-danger fal fa-times' aria-hidden='true'></i>")."</td>
																  <td align='center'>".($qrListaUsuario['LOG_SEMANA_1']=="S"?"<i class='fal fa-check' aria-hidden='true'></i>":"<i class='text-danger fal fa-times' aria-hidden='true'></i>")."</td>
																  <td align='center'>".($qrListaUsuario['LOG_SEMANA_2']=="S"?"<i class='fal fa-check' aria-hidden='true'></i>":"<i class='text-danger fal fa-times' aria-hidden='true'></i>")."</td>
																  <td align='center'>".($qrListaUsuario['LOG_SEMANA_3']=="S"?"<i class='fal fa-check' aria-hidden='true'></i>":"<i class='text-danger fal fa-times' aria-hidden='true'></i>")."</td>
																  <td align='center'>".($qrListaUsuario['LOG_SEMANA_4']=="S"?"<i class='fal fa-check' aria-hidden='true'></i>":"<i class='text-danger fal fa-times' aria-hidden='true'></i>")."</td>
																  <td align='center'>".($qrListaUsuario['LOG_SEMANA_5']=="S"?"<i class='fal fa-check' aria-hidden='true'></i>":"<i class='text-danger fal fa-times' aria-hidden='true'></i>")."</td>
																  <td align='center'>".($qrListaUsuario['LOG_SEMANA_6']=="S"?"<i class='fal fa-check' aria-hidden='true'></i>":"<i class='text-danger fal fa-times' aria-hidden='true'></i>")."</td>
																  <td align='center'>".($qrListaUsuario['LOG_SEMANA_7']=="S"?"<i class='fal fa-check' aria-hidden='true'></i>":"<i class='text-danger fal fa-times' aria-hidden='true'></i>")."</td>
																</tr>
																<input type='hidden' id='ret_COD_TURNO_".$count."' value='".$qrListaUsuario['COD_TURNO']."'>
																<input type='hidden' id='ret_NOM_TURNO_".$count."' value='".$qrListaUsuario['NOM_TURNO']."'>
																<input type='hidden' id='ret_COD_EMPRESA_".$count."' value='".$qrListaUsuario['COD_EMPRESA']."'>
																<input type='hidden' id='ret_HOR_ENTRADA_".$count."' value='".$qrListaUsuario['HOR_ENTRADA']."'>
																<input type='hidden' id='ret_HOR_SAIDA_".$count."' value='".substr($qrListaUsuario['HOR_SAIDA'],0,5).":00"."'>
																<input type='hidden' id='ret_LOG_SEMANA_1_".$count."' value='".$qrListaUsuario['LOG_SEMANA_1']."'>
																<input type='hidden' id='ret_LOG_SEMANA_2_".$count."' value='".$qrListaUsuario['LOG_SEMANA_2']."'>
																<input type='hidden' id='ret_LOG_SEMANA_3_".$count."' value='".$qrListaUsuario['LOG_SEMANA_3']."'>
																<input type='hidden' id='ret_LOG_SEMANA_4_".$count."' value='".$qrListaUsuario['LOG_SEMANA_4']."'>
																<input type='hidden' id='ret_LOG_SEMANA_5_".$count."' value='".$qrListaUsuario['LOG_SEMANA_5']."'>
																<input type='hidden' id='ret_LOG_SEMANA_6_".$count."' value='".$qrListaUsuario['LOG_SEMANA_6']."'>
																<input type='hidden' id='ret_LOG_SEMANA_7_".$count."' value='".$qrListaUsuario['LOG_SEMANA_7']."'>
																<input type='hidden' id='ret_LOG_DIAPAR_".$count."' value='".$qrListaUsuario['LOG_DIAPAR']."'>
																<input type='hidden' id='ret_LOG_DIAIMPAR_".$count."' value='".$qrListaUsuario['LOG_DIAIMPAR']."'>
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
						
		
		<script type="text/javascript">

			//Barra de pesquisa essentials ------------------------------------------------------
			$(document).ready(function(e){
				
			});

			function buscaRegistro(el){
				var filtro = $('#search_concept').text().toLowerCase();

				if(filtro == "sem filtro"){
					var value = $(el).val().toLowerCase().trim();
					if(value){
						$('#CLEARDIV').show();
					}else{
						$('#CLEARDIV').hide();
					}
					$(".buscavel tr").each(function (index) {
						if (!index) return;
						$(this).find("td").each(function () {
							var id = $(this).text().toLowerCase().trim();
							var sem_registro = (id.indexOf(value) == -1);
							$(this).closest('tr').toggle(!sem_registro);
							return sem_registro;
						});
					});
				}
			}

		//-----------------------------------------------------------------------------------
		
			$(document).ready(function(){

				//chosen
				$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
				$('#formulario').validator();

				
			});	
			

					
			function retornaForm(index){
				$("#formulario #COD_TURNO").val($("#ret_COD_TURNO_"+index).val());
				$("#formulario #NOM_TURNO").val($("#ret_NOM_TURNO_"+index).val());
				$("#formulario #COD_EMPRESA").val($("#ret_COD_EMPRESA_"+index).val());
				$("#formulario #HOR_ENTRADA").val($("#ret_HOR_ENTRADA_"+index).val());
				$("#formulario #HOR_SAIDA").val($("#ret_HOR_SAIDA_"+index).val());

				if ($("#ret_LOG_SEMANA_1_"+index).val() == 'S'){$("#formulario #LOG_SEMANA_1").prop('checked', true);}else{$('#formulario #LOG_SEMANA_1').prop('checked', false);}
				if ($("#ret_LOG_SEMANA_2_"+index).val() == 'S'){$("#formulario #LOG_SEMANA_2").prop('checked', true);}else{$('#formulario #LOG_SEMANA_2').prop('checked', false);}
				if ($("#ret_LOG_SEMANA_3_"+index).val() == 'S'){$("#formulario #LOG_SEMANA_3").prop('checked', true);}else{$('#formulario #LOG_SEMANA_3').prop('checked', false);}
				if ($("#ret_LOG_SEMANA_4_"+index).val() == 'S'){$("#formulario #LOG_SEMANA_4").prop('checked', true);}else{$('#formulario #LOG_SEMANA_4').prop('checked', false);}
				if ($("#ret_LOG_SEMANA_5_"+index).val() == 'S'){$("#formulario #LOG_SEMANA_5").prop('checked', true);}else{$('#formulario #LOG_SEMANA_5').prop('checked', false);}
				if ($("#ret_LOG_SEMANA_6_"+index).val() == 'S'){$("#formulario #LOG_SEMANA_6").prop('checked', true);}else{$('#formulario #LOG_SEMANA_6').prop('checked', false);}
				if ($("#ret_LOG_SEMANA_7_"+index).val() == 'S'){$("#formulario #LOG_SEMANA_7").prop('checked', true);}else{$('#formulario #LOG_SEMANA_7').prop('checked', false);}
				if ($("#ret_LOG_DIAPAR_"+index).val() == 'S'){$("#formulario #LOG_DIAPAR").prop('checked', true);}else{$('#formulario #LOG_DIAPAR').prop('checked', false);}
				if ($("#ret_LOG_DIAIMPAR_"+index).val() == 'S'){$("#formulario #LOG_DIAIMPAR").prop('checked', true);}else{$('#formulario #LOG_DIAIMPAR').prop('checked', false);}
				
				$('#formulario').validator('validate');			
				$("#formulario #hHabilitado").val('S');						
			}	
					
		</script>	
		
<?php
	}
?>