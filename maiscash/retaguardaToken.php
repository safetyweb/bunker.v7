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

			$cod_empresa = fnLimpaCampo($_REQUEST['COD_EMPRESA']);

			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
						
			if ($opcao != ''){

				include_once './totem/funWS/GeraToken.php';

				// fnEscreve('chegou');

				$sql = "SELECT COD_EMPRESA, NOM_FANTASI, QTD_CHARTKN, TIP_TOKEN, TIP_RETORNO, NUM_DECIMAIS_B  FROM empresas where COD_EMPRESA = '".$cod_empresa."' ";
				//fnEscreve($sql);
				$arrayQuery = mysqli_query($connAdm->connAdm(),$sql);
				$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
				
				if (isset($arrayQuery)){
					$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
					$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
					$qtd_chartkn = $qrBuscaEmpresa['QTD_CHARTKN'];
					$tip_token = $qrBuscaEmpresa['TIP_TOKEN'];


					if($qrBuscaEmpresa['TIP_RETORNO'] == 1){
						$casasDec = 0;
					}else{
						$casasDec = $qrBuscaEmpresa['NUM_DECIMAIS_B'];
						$pref = "R$ ";
					}

					// echo($casasDec);
				}

				$num_celular = fnLimpaCampo(fnLimpaDoc($_POST['NUM_CELULAR']));
				$num_cgcecpf = fnLimpaCampo(fnLimpaDoc($_POST['NUM_CGCECPF']));
				$nom_cliente = fnLimpaCampo($_POST['NOM_CLIENTE']);

				if($num_celular == ""){
					$num_celular = fnLimpaCampo(fnLimpaDoc($_POST['KEY_NUM_CELULAR']));
				}

				if($num_cgcecpf == ""){
					$num_cgcecpf = fnLimpaCampo(fnLimpaDoc($_POST['KEY_NUM_CGCECPF']));
				}

				if($num_cgcecpf == "00000000000" || $num_cgcecpf == ""){
					$num_cgcecpf = $num_celular;
				}

				$sql = "SELECT * FROM  USUARIOS
						WHERE LOG_ESTATUS='S' AND
							  COD_EMPRESA = $cod_empresa AND
							  COD_TPUSUARIO = 10  limit 1  ";
				//fnEscreve($sql);
				$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
				$qrBuscaUsuTeste = mysqli_fetch_assoc($arrayQuery);
								
				if (isset($arrayQuery)) {
					$log_usuario = $qrBuscaUsuTeste['LOG_USUARIO'];
					$des_senhaus = fnDecode($qrBuscaUsuTeste['DES_SENHAUS']);
				}

				$sqlUn = "SELECT COD_UNIVEND FROM UNIDADEVENDA 
						  WHERE COD_EMPRESA = $cod_empresa 
						  AND LOG_ESTATUS = 'S' 
						  ORDER BY 1 ASC LIMIT 1";

				$arrayUn = mysqli_query($connAdm->connAdm(), $sqlUn);
				$qrLista = mysqli_fetch_assoc($arrayUn);

				$idlojaKey = $qrLista['COD_UNIVEND'];
				$idmaquinaKey = 0;
				$codvendedorKey = 0;
				$nomevendedorKey = 0;

				$urltotem = $log_usuario.';'
							.$des_senhaus.';'
							.$idlojaKey.';'
							.$idmaquinaKey.';'
							.$cod_empresa.';'
							.$codvendedorKey.';'
							.$nomevendedorKey;

				$arrayCampos = explode(";", $urltotem);

				$dadosenvio = array(
									 'tipoGeracao'=>'1',
									 'nome'=>"$nom_cliente",
									 'cpf'=>"$num_cgcecpf",
									 'celular'=>"$num_celular",
									 'email'=>''
									);

				$retornoEnvio = GeraToken($dadosenvio, $arrayCampos);

				// echo '<pre>';
				// echo '_'.$_POST['NUM_CGCECPF'];
				// echo '_'.$_POST['CAD_NUM_CGCECPF'];
				// echo '_'.$_POST['KEY_NUM_CGCECPF'];
			    // print_r($dadosenvio);
			    // print_r($retornoEnvio);
			    // echo '</pre>';
			    // exit();

				$cod_envio = $retornoEnvio[body][envelope][body][geratokenresponse][retornatoken][coderro];		
				$msgTipo = 'alert-success';		
				
				//mensagem de retorno
				switch ($cod_envio)
				{
					case '39':
						$msgRetorno = "Token enviado.";	
						break;
					case '96':
						$msgRetorno = "Rotinas de token incompletas.<br>Contate o suporte.";	
						$msgTipo = 'alert-danger';	
						break;
					default:
						$msgRetorno = "O token já enviado.";	
						$msgTipo = 'alert-warning';	
						break;
					
				}			
				
				
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
					
						<div class="col-md-12 margin-bottom-30">
							<!-- Portlet -->
							<div class="portlet">
								
								<div class="portlet-body">
									
									<?php if ($msgRetorno <> '') { ?>	
									<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
									<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									 <?php echo $msgRetorno; ?>
									</div>
									<?php } ?>	
								
			
									<div class="login-form">
									
										<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
																				
											<?php

											$sqlCampos = "SELECT NOM_CAMPOOBG, 
																 NOM_CAMPOOBG, 
																 DES_CAMPOOBG, 
																 MATRIZ_CAMPO_INTEGRACAO.TIP_CAMPOOBG AS CAT_CAMPO, 
																 INTEGRA_CAMPOOBG.TIP_CAMPOOBG AS TIPO_DADO,
																 (SELECT COUNT(MCI.TIP_CAMPOOBG) 
																	FROM matriz_campo_integracao MCI
																	WHERE MCI.TIP_CAMPOOBG = 'OBG' 
																	AND MCI.COD_CAMPOOBG = MATRIZ_CAMPO_INTEGRACAO.COD_CAMPOOBG
																	AND MCI.COD_EMPRESA = $cod_empresa) AS OBRIGATORIO,
																 COL_MD, 
																 COL_XS, 
																 CLASSE_INPUT, 
																 CLASSE_DIV 
															FROM MATRIZ_CAMPO_INTEGRACAO                         
															LEFT JOIN INTEGRA_CAMPOOBG ON INTEGRA_CAMPOOBG.COD_CAMPOOBG=MATRIZ_CAMPO_INTEGRACAO.COD_CAMPOOBG                         
															WHERE MATRIZ_CAMPO_INTEGRACAO.COD_EMPRESA = $cod_empresa
															AND MATRIZ_CAMPO_INTEGRACAO.TIP_CAMPOOBG = 'TKN'
															ORDER BY COL_MD ASC, COL_XS ASC, MATRIZ_CAMPO_INTEGRACAO.COD_CAMPOOBG, MATRIZ_CAMPO_INTEGRACAO.TIP_CAMPOOBG ASC";

											$arrayCampos = mysqli_query($connAdm->connAdm(),$sqlCampos);

											$nroCampos = mysqli_num_rows($arrayCampos);

											// echo($sqlCampos);

											$lastField = "";

											while($qrCampos = mysqli_fetch_assoc($arrayCampos)){

												// echo "<pre>";
												// print_r($qrCampos);
												// echo "</pre>";

												$colMd = $qrCampos[COL_MD];
												$colXs = $qrCampos[COL_XS];
												$dataError = "";

												$required = "";
												// echo "$qrCampos[NOM_CAMPOOBG]: $qrCampos[CAT_CAMPO] - $required<br>";

												if($lastField == ""){
													$lastField = $qrCampos[NOM_CAMPOOBG];
												}else if($lastField == $qrCampos[NOM_CAMPOOBG]){
													continue;
												}else{
													$lastField = $qrCampos[NOM_CAMPOOBG];
												}

												if($qrCampos[OBRIGATORIO] > 0){
													$required = "required";
													$dataError = "data-error='Campo obrigatório'";
												}

												// echo "$qrCampos[CAT_CAMPO]";

												if($colMd == "" || $colMd == 0){
													$colMd = 12;
												}

												if($colXs == "" || $colXs == 0){
													$colXs = 12;
												}

												switch ($qrCampos[DES_CAMPOOBG]) {

													case 'NOM_CLIENTE':

														$dado = $buscaconsumidor['nome'];

													break;
													
													case 'COD_SEXOPES':

														$dado = $buscaconsumidor['sexo'];

													break;
													
													case 'DES_EMAILUS':

														$dado = $buscaconsumidor['email'];

													break;
													
													case 'NUM_CELULAR':

														$dado = $buscaconsumidor['telcelular'];

													break;
													
													case 'NUM_CARTAO':

														$dado = $buscaconsumidor['cartao'];

													break;

													case 'NUM_CGCECPF':

														$dado = $buscaconsumidor['cpf'];

													break;
													
													
													case 'DAT_NASCIME':

														$dado = $buscaconsumidor['datanascimento'];

													break;
													
													case 'COD_PROFISS':

														$dado = $buscaconsumidor['profissao'];

													break;
													
													case 'COD_ATENDENTE':

														$dado = $buscaconsumidor['codatendente'];

													break;
													
													case 'DES_SENHAUS':

														$dado = $buscaconsumidor['senha'];

													break;
													
													case 'DES_ENDEREC':

														$dado = $buscaconsumidor['endereco'];

													break;
													
													case 'NUM_ENDEREC':

														$dado = $buscaconsumidor['numero'];

													break;
													
													case 'NUM_CEPOZOF':

														$dado = $buscaconsumidor['cep'];

													break;
													
													case 'estado':

														$dado = $buscaconsumidor['estado'];

													break;
													
													case 'NOM_CIDADEC':

														$dado = $buscaconsumidor['cidade'];

													break;
													
													case 'DES_BAIRROC':

														$dado = $buscaconsumidor['bairro'];

													break;
													
													case 'DES_COMPLEM':

														$dado = $buscaconsumidor['complemento'];

													break;

													default:

														$dado = "";

													break;

												}

												switch ($qrCampos[TIPO_DADO]) {

													case 'Data':

														?>
															<div class="col-md-<?=$colMd?> col-xs-<?=$colXs?>">
																<div class="form-group">
																	<label>&nbsp;</label>
																	<label for="inputName" class="control-label <?=$required?>"><?=$qrCampos[NOM_CAMPOOBG]?></label>
																	<input type="tel" value="<?=$dado?>" class="form-control input-sm input-hg <?=$qrCampos[CLASSE_INPUT]?> data" name="<?=$qrCampos[DES_CAMPOOBG]?>" id="<?=$qrCampos[DES_CAMPOOBG]?>" maxlenght="10" <?=$dataError?> <?=$required?>>
																	<div class="help-block with-errors"></div>
																</div>
															</div>

														<?php

													break;

													case 'email':

													    $dataError = "";

														?>
															<div class="col-md-<?=$colMd?> col-xs-<?=$colXs?>">
																<div class="form-group">
																	<label>&nbsp;</label>
																	<label for="inputName" class="control-label <?=$required?>"><?=$qrCampos[NOM_CAMPOOBG]?></label>
																	<input type="email" value="<?=$dado?>" class="form-control input-sm input-hg <?=$qrCampos[CLASSE_INPUT]?>" name="<?=$qrCampos[DES_CAMPOOBG]?>" id="<?=$qrCampos[DES_CAMPOOBG]?>" <?=$dataError?> <?=$required?>>
																	<div class="help-block with-errors"></div>
																</div>
															</div>

														<?php
														
													break;

													case 'numeric':

														if($qrCampos[DES_CAMPOOBG] == "COD_SEXOPES"){

															?>
																<div class="col-md-<?=$colMd?> col-xs-<?=$colXs?>">
																	<div class="form-group">
																		<label>&nbsp;</label>
																		<label for="inputName" class="control-label <?=$required?>">Sexo</label>
																			<select data-placeholder="Selecione o sexo" name="COD_SEXOPES" id="COD_SEXOPES" class="chosen-select-deselect input-sm <?=$qrCampos[CLASSE_INPUT]?>" <?=$required?>>
																				<option value=""></option>					
																				<?php 																	
																					$sql = "select COD_SEXOPES, DES_SEXOPES from sexo order by des_sexopes ";
																					$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
																				
																					while ($qrListaSexo = mysqli_fetch_assoc($arrayQuery))
																					  {														
																						echo"
																							  <option value='".$qrListaSexo['COD_SEXOPES']."'>".$qrListaSexo['DES_SEXOPES']."</option> 
																							"; 
																						  }											
																				?>	
																			</select>
																			<script type="text/javascript">$("#COD_SEXOPES").val("<?=$dado?>").trigger('chosen:updated');</script>    
																		<div class="help-block with-errors"></div>
																	</div>
																</div>

															<?php

														}else if($qrCampos[DES_CAMPOOBG] == "COD_PROFISS"){

															?>
																<div class="col-md-<?=$colMd?> col-xs-<?=$colXs?>">
																	<div class="form-group">
																		<label>&nbsp;</label>
																		<label for="inputName" class="control-label <?=$required?>">Profissão </label>
																			<select data-placeholder="Selecione a profissão" name="COD_PROFISS" id="COD_PROFISS" class="chosen-select-deselect input-sm <?=$qrCampos[CLASSE_INPUT]?>" <?=$required?>>
																				<option value=""></option>					
																				<?php 	
																					$sql = "select COD_PROFISS, DES_PROFISS from profissoes_empresa where cod_empresa=$cod_empresa  order by DES_PROFISS";
																					if(mysqli_num_rows(mysqli_query(connTemp($cod_empresa, ''), $sql)) <= '0' )
																					{
																					  $sql = "select COD_PROFISS, DES_PROFISS from PROFISSOES order by DES_PROFISS ";
																					  $arrayQuery = mysqli_query($connAdm->connAdm(),$sql);
																					}else
																					{
																					  $arrayQuery= mysqli_query(connTemp($cod_empresa, ''), $sql); 
																					}
																				
																					while ($qrListaProfi = mysqli_fetch_assoc($arrayQuery))
																					  {														
																						echo"
																							  <option value='".$qrListaProfi['COD_PROFISS']."'>".$qrListaProfi['DES_PROFISS']."</option> 
																							"; 
																						  }											
																				?>
																			</select>
																			<script type="text/javascript">$("#COD_PROFISS").val("<?=$dado?>").trigger('chosen:updated');</script>                                                    
																		<div class="help-block with-errors"></div>
																	</div>
																</div>

															<?php

														}else if($qrCampos[DES_CAMPOOBG] == "COD_ESTACIV"){

															?>
																<div class="col-md-<?=$colMd?> col-xs-<?=$colXs?>">
																	<div class="form-group">
																		<label>&nbsp;</label>
																		<label for="inputName" class="control-label <?=$required?>">Estado Civil</label>
																			<select data-placeholder="Selecione um estado civil" name="COD_ESTACIV" id="COD_ESTACIV" class="chosen-select-deselect input-sm <?=$qrCampos[CLASSE_INPUT]?>" <?=$required?>>
																				<option value=""></option>					
																				<?php																	
																					$sql = "select COD_ESTACIV, DES_ESTACIV from estadocivil order by des_estaciv; ";
																					$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
																				
																					while ($qrListaEstCivil = mysqli_fetch_assoc($arrayQuery))
																					  {													
																						echo"
																							  <option value='".$qrListaEstCivil['COD_ESTACIV']."'>".$qrListaEstCivil['DES_ESTACIV']."</option> 
																							"; 
																						  }											
																				?>	
																			</select>
																			<script type="text/javascript">$("#COD_ESTACIV").val("<?=$dado?>").trigger('chosen:updated');</script>
																			<div class="help-block with-errors"></div>
																	</div>
																</div>

															<?php

														}else{

															$type = "text";

															if($qrCampos[DES_CAMPOOBG] == "NUM_CGCECPF"){
																$nomeCampo = "CPF/CNPJ";
																$mask = "cpfcnpj";
																$type = "tel";
															}else{
																$nomeCampo = $qrCampos[NOM_CAMPOOBG];
																$mask = "";
															}

															?>
																<div class="col-md-<?=$colMd?> col-xs-<?=$colXs?>">
																	<div class="form-group">
																		<label>&nbsp;</label>
																		<label for="inputName" class="control-label <?=$required?>"><?=$nomeCampo?></label>
																		<input type="<?=$type?>" value="<?=$dado?>" class="form-control input-sm input-hg <?=$qrCampos[CLASSE_INPUT]?> <?=$mask?>" name="<?=$qrCampos[DES_CAMPOOBG]?>" id="<?=$qrCampos[DES_CAMPOOBG]?>" <?=$dataError?> <?=$required?>>
																		<div class="help-block with-errors"></div>
																	</div>
																</div>

															<?php

														}
														
													break;
													
													default:

														$type = "text";

														if($qrCampos[DES_CAMPOOBG] == "NUM_CGCECPF"){
															$nomeCampo = "CPF/CNPJ";
															$mask = "cpfcnpj";
															$type = "tel";
														}else if($qrCampos[DES_CAMPOOBG] == "NUM_CELULAR" || $qrCampos[DES_CAMPOOBG] == "NUM_TELEFONE" || $qrCampos[DES_CAMPOOBG] == "NUM_CEPOZOF"){
															$type = "tel";
														}else{
															$nomeCampo = $qrCampos[NOM_CAMPOOBG];
															$mask = "";
														}

														?>
															<div class="col-md-<?=$colMd?> col-xs-<?=$colXs?>">
																<div class="form-group">
																	<label>&nbsp;</label>
																	<label for="inputName" class="control-label <?=$required?>"><?=$qrCampos[NOM_CAMPOOBG]?></label>
																	<input type="<?=$type?>" value="<?=$dado?>" class="form-control input-sm input-hg <?=$qrCampos[CLASSE_INPUT]?>" name="<?=$qrCampos[DES_CAMPOOBG]?>" id="<?=$qrCampos[DES_CAMPOOBG]?>" <?=$dataError?> <?=$required?>>
																	<div class="help-block with-errors"></div>
																</div>
															</div>

														<?php

													break;

												}

										?>
												<!-- <div class="push10"></div> -->
										<?php

											}

										?>

										<div class="push20"></div>

										<div class="col-md-12">
											<button type="submit" name="ALT" id="ALT" class="btn btn-primary btn-sm btn-block getBtn">Enviar Token</button>
										</div>

										<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?=$cod_empresa?>">
										<input type="hidden" name="opcao" id="opcao" value="">
										<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
										<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">	
												
										</form>

									</div>
									
								</div>										
							
							<div class="push"></div>
							
							</div>								
						
						</div>
					</div>
					<!-- fim Portlet -->
										
						
					<div class="push20"></div> 
	
	<script type="text/javascript">
		
		function retornaForm(index){
			$("#formulario #COD_GRUPOTR").val($("#ret_COD_GRUPOTR_"+index).val());
			$("#formulario #DES_GRUPOTR").val($("#ret_DES_GRUPOTR_"+index).val());
			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');						
		}
		
	</script>	