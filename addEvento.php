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

			$cod_event = fnLimpaCampoZero($_REQUEST['COD_EVENT']);
			$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
			if (empty($_REQUEST['LOG_TAREFAS'])) {$log_tarefas='N';}else{$log_tarefas=$_REQUEST['LOG_TAREFAS'];}
			$cod_tpevent = fnLimpaCampoZero($_REQUEST['COD_TPEVENT']);
			$nom_event = fnLimpaCampo($_REQUEST['NOM_EVENT']);
			$des_local = fnLimpaCampo($_REQUEST['DES_LOCAL']);
			$cod_prioridade = fnLimpaCampoZero($_REQUEST['COD_PRIORIDADE']);

			$dat_ini = fnDataSql(fnLimpacampo($_REQUEST['DAT_INI']));
			$dat_fim = fnDataSql(fnLimpacampo($_REQUEST['DAT_FIM']));
			$hor_ini = fnLimpacampo($_REQUEST['HOR_INI']);
			$hor_fim = fnLimpacampo($_REQUEST['HOR_FIM']);
			$des_event = fnLimpacampo($_REQUEST['DES_EVENT']);
			$dias_repete = "";

			$count_filtros = fnLimpacampo($_REQUEST['COUNT_FILTROS']);
			
			for ($i=0; $i <=6 ; $i++) { 
				if (!empty($_REQUEST["LOG_REPETE_$i"])){
					if ($_REQUEST["LOG_REPETE_$i"] == 0) {
						$dias_repete .= "0,";
					}else{
						$dias_repete .= $_REQUEST["LOG_REPETE_$i"].",";
					}
				}
				// fnEscreve($dias_repete);
			}

			if($dias_repete != ""){
				$dias_repete = substr($dias_repete,0,-1);
			}

			$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
						
			if ($opcao != ''){				
				
				//mensagem de retorno
				switch ($opcao)
				{
					case 'CAD':

						$sql = "INSERT INTO EVENTOS_AGENDA(
									COD_EMPRESA,
									LOG_TAREFAS,
									DIAS_REPETE,
									COD_TPEVENT,
									NOM_EVENT,
									DES_LOCAL,
									COD_PRIORIDADE,
									DAT_INI,
									DAT_FIM,
									HOR_INI,
									HOR_FIM,
									DES_EVENT,
									COD_USUCADA
									) VALUES(
									$cod_empresa,
									'$log_tarefas',
									'$dias_repete',
									$cod_tpevent,
									'$nom_event',
									'$des_local',
									$cod_prioridade,
									'$dat_ini',
									'$dat_fim',
									'$hor_ini',
									'$hor_fim',
									'$des_event',
									$cod_usucada
									)";
					
						// fnEscreve($sql);
						
						mysqli_query(connTemp($cod_empresa,''),$sql);

						$sqlCod = "SELECT MAX(COD_EVENT) AS COD_EVENT FROM EVENTOS_AGENDA WHERE COD_EMPRESA = $cod_empresa";
						$qrCod = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sqlCod));
						$cod_event = $qrCod['COD_EVENT'];


						if (isset($_POST['COD_USUARIOS_ENV'])){

							$Arr_COD_USUARIOS_ENV = $_POST['COD_USUARIOS_ENV'];
							$sqlUsu = "";			 
							 
							   for ($i=0;$i<count($Arr_COD_USUARIOS_ENV);$i++) 
							   { 

							   	$sqlUsu .= "INSERT INTO USUARIO_EVENTO(
							   							COD_EMPRESA,
							   							COD_USUARIO,
							   							COD_EVENT
							   							) VALUES(
							   							$cod_empresa,
							   							$Arr_COD_USUARIOS_ENV[$i],
							   							$cod_event
							   							);";

								}

								if($sqlUsu != ""){
									mysqli_multi_query(connTemp($cod_empresa,''),$sqlUsu);
								}							   
								
						}

						if (isset($_POST['COD_CLIENTES_ENV'])){

							$Arr_COD_CLIENTES_ENV = $_POST['COD_CLIENTES_ENV'];
							$sqlCli = "";			 
							 
							   for ($i=0;$i<count($Arr_COD_CLIENTES_ENV);$i++) 
							   { 

							   	$sqlCli .= "INSERT INTO CLIENTE_EVENTO(
							   							COD_EMPRESA,
							   							COD_CLIENTE,
							   							COD_EVENT
							   							) VALUES(
							   							$cod_empresa,
							   							$Arr_COD_CLIENTES_ENV[$i],
							   							$cod_event
							   							);";

								}

								if($sqlCli != ""){
									mysqli_multi_query(connTemp($cod_empresa,''),$sqlCli);
								}							   
								
						}

						if($count_filtros != ""){

							$sql = "";
							$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

							for ($i=0; $i < $count_filtros; $i++) {

								$cod_filtro = fnLimpacampoZero($_REQUEST["COD_FILTRO_$i"]);
								$cod_tpfiltro = fnLimpacampoZero($_REQUEST["COD_TPFILTRO_$i"]);

								if($cod_filtro != 0){
									$sql .= "INSERT INTO EVENTO_FILTROS(
														COD_EMPRESA,
														COD_TPFILTRO,
														COD_FILTRO,
														COD_EVENTO,
														COD_USUCADA
														)VALUES(
														$cod_empresa,
														$cod_tpfiltro,
														$cod_filtro,
														(SELECT MAX(COD_EVENT) FROM EVENTOS_AGENDA WHERE COD_USUCADA = $cod_usucada AND COD_EMPRESA = $cod_empresa),
														$cod_usucada
														);";
								}
										
							}

							//fnEscreve($sql);
							if($sql != ""){
								mysqli_multi_query(connTemp($cod_empresa,''),$sql);
							}							

						}


						?>
						<script>
							try { parent.$('#REFRESH_TAREFA').val("S"); } catch(err) {}
						</script>
						<?php 

						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";	
						break;
					case 'ALT':

						$sql = "UPDATE EVENTOS_AGENDA SET
									LOG_TAREFAS='$log_tarefas',
									DIAS_REPETE='$dias_repete',
									COD_TPEVENT=$cod_tpevent,
									NOM_EVENT='$nom_event',
									DES_LOCAL='$des_local',
									COD_PRIORIDADE=$cod_prioridade,
									DAT_INI='$dat_ini',
									DAT_FIM='$dat_fim',
									HOR_INI='$hor_ini',
									HOR_FIM='$hor_fim',
									DAT_ALTERAC = CONVERT_TZ(NOW(),'America/Sao_Paulo','America/Sao_Paulo'),
									COD_ALTERAC = $cod_usucada,
									DES_EVENT='$des_event'
									WHERE COD_EVENT = $cod_event";
					
						// fnEscreve($sql);
						
						mysqli_query(connTemp($cod_empresa,''),$sql);

						if (isset($_POST['COD_USUARIOS_ENV'])){

							$sqlDel = "DELETE FROM USUARIO_EVENTO WHERE COD_EVENT = $cod_event";
							mysqli_query(connTemp($cod_empresa,''),$sqlDel);

							$Arr_COD_USUARIOS_ENV = $_POST['COD_USUARIOS_ENV'];
							$sqlUsu = "";			 
							 
							   for ($i=0;$i<count($Arr_COD_USUARIOS_ENV);$i++) 
							   { 

							   	$sqlUsu .= "INSERT INTO USUARIO_EVENTO(
							   							COD_EMPRESA,
							   							COD_USUARIO,
							   							COD_EVENT
							   							) VALUES(
							   							$cod_empresa,
							   							$Arr_COD_USUARIOS_ENV[$i],
							   							$cod_event
							   							);";

								}

								if($sqlUsu != ""){
									// fnEscreve($sqlUsu);
									mysqli_multi_query(connTemp($cod_empresa,''),$sqlUsu);
								}							   
								
							}

							if (isset($_POST['COD_CLIENTES_ENV'])){

							$sqlDel = "DELETE FROM CLIENTE_EVENTO WHERE COD_EVENT = $cod_event";
							mysqli_query(connTemp($cod_empresa,''),$sqlDel);

							$Arr_COD_CLIENTES_ENV = $_POST['COD_CLIENTES_ENV'];
							$sqlCli = "";			 
							 
							   for ($i=0;$i<count($Arr_COD_CLIENTES_ENV);$i++) 
							   { 

							   	$sqlCli .= "INSERT INTO CLIENTE_EVENTO(
							   							COD_EMPRESA,
							   							COD_CLIENTE,
							   							COD_EVENT
							   							) VALUES(
							   							$cod_empresa,
							   							$Arr_COD_CLIENTES_ENV[$i],
							   							$cod_event
							   							);";

								}

								if($sqlCli != ""){
									// fnEscreve($sqlCli);
									mysqli_multi_query(connTemp($cod_empresa,''),$sqlCli);
								}							   
								
							}

							if($count_filtros != ""){

								$sql = "DELETE FROM EVENTO_FILTROS WHERE COD_EVENTO = $cod_event;";
								$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

								for ($i=0; $i < $count_filtros; $i++) {

									$cod_filtro = fnLimpacampoZero($_REQUEST["COD_FILTRO_$i"]);
									$cod_tpfiltro = fnLimpacampoZero($_REQUEST["COD_TPFILTRO_$i"]);

									if($cod_filtro != 0){
										$sql .= "INSERT INTO EVENTO_FILTROS(
															COD_EMPRESA,
															COD_TPFILTRO,
															COD_FILTRO,
															COD_EVENTO,
															COD_USUCADA
															)VALUES(
															$cod_empresa,
															$cod_tpfiltro,
															$cod_filtro,
															$cod_event,
															$cod_usucada
															);";
									}
											
								}

								//fnEscreve($sql);
								if($sql != ""){
									mysqli_multi_query(connTemp($cod_empresa,''),$sql);
								}							

							}

						?>
						<script>
							try { parent.$('#REFRESH_TAREFA').val("S"); } catch(err) {}
						</script>
						<?php 

						$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";		
						break;
					case 'EXC':

						$sql = "UPDATE EVENTOS_AGENDA SET
								COD_EXCLUSA = $cod_usucada,
								DAT_EXCLUSA=CONVERT_TZ(NOW(),'America/Sao_Paulo','America/Sao_Paulo')
						WHERE COD_EVENT = $cod_event";

						mysqli_query(connTemp($cod_empresa,''),$sql);

						//echo($sql);

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

	if(isset($_GET['idE'])){

		$cod_event = fnLimpaCampoZero($_GET['idE']);

		$sqlUsu = "SELECT COD_USUARIO FROM USUARIO_EVENTO WHERE COD_EVENT = $cod_event";
		$arrayUsu = mysqli_query(connTemp($cod_empresa,''),$sqlUsu);
		$cod_usuarios_env = "";

		while ($qrUsu = mysqli_fetch_assoc($arrayUsu)) {
			$cod_usuarios_env = $cod_usuarios_env.$qrUsu['COD_USUARIO'].",";
		}

		if($cod_usuarios_env != ""){
			$cod_usuarios_env = substr($cod_usuarios_env,0,-1);
		}

		$sqlCli = "SELECT COD_CLIENTE FROM CLIENTE_EVENTO WHERE COD_EVENT = $cod_event";
		$arrayCli = mysqli_query(connTemp($cod_empresa,''),$sqlCli);
		$cod_clientes_env = "";

		while ($qrCli = mysqli_fetch_assoc($arrayCli)) {
			$cod_clientes_env = $cod_clientes_env.$qrCli['COD_CLIENTE'].",";
		}

		if($cod_clientes_env != ""){
			$cod_clientes_env = substr($cod_clientes_env,0,-1);
		}

		// fnEscreve($cod_clientes_env);
		
		$sql = "SELECT * FROM EVENTOS_AGENDA WHERE COD_EVENT = $cod_event";
		$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);

		$qrEvent = mysqli_fetch_assoc($arrayQuery);

		if ($qrEvent['LOG_TAREFAS']=='S') {
			$check_tarefas='checked';
		}else{
			$check_tarefas="";
		}

		$check_dia_0 = "";
		$check_dia_1 = "";
		$check_dia_2 = "";
		$check_dia_3 = "";
		$check_dia_4 = "";
		$check_dia_5 = "";
		$check_dia_6 = "";

		if ($qrEvent['DIAS_REPETE']!='') {

			$arrayDias = explode(",", $qrEvent['DIAS_REPETE']);
			$count = count($arrayDias);

			for ($i=0; $i < $count ; $i++) {

				switch($arrayDias[$i]){

					case "0":
						$check_dia_0 = "checked";
					break;
					case "1":
						$check_dia_1 = "checked";
					break;
					case "2":
						$check_dia_2 = "checked";
					break;
					case "3":
						$check_dia_3 = "checked";
					break;
					case "4":
						$check_dia_4 = "checked";
					break;
					case "5":
						$check_dia_5 = "checked";
					break;
					default:
						$check_dia_6 = "checked";
					break;

				}

			}

		}

		$cod_tpevent = $qrEvent['COD_TPEVENT'];
		$nom_event = $qrEvent['NOM_EVENT'];
		$cod_prioridade = $qrEvent['COD_PRIORIDADE'];
		$dat_ini = $qrEvent['DAT_INI'];
		$dat_fim = $qrEvent['DAT_FIM'];
		$hor_ini = $qrEvent['HOR_INI'];
		$hor_fim = $qrEvent['HOR_FIM'];
		$des_event = $qrEvent['DES_EVENT'];
		$des_local = $qrEvent['DES_LOCAL'];

	}else{

		$cod_event = 0;
		$cod_tpevent = "";
		$nom_event = "";
		$cod_prioridade = "";
		$dat_ini = "";
		$dat_fim = "";
		$hor_ini = "";
		$hor_fim = "";
		$cod_usuario = "";
		$des_event = "";
		$des_local = "";
		$check_tarefas="checked";
		$check_dia_0 = "";
		$check_dia_1 = "";
		$check_dia_2 = "";
		$check_dia_3 = "";
		$check_dia_4 = "";
		$check_dia_5 = "";
		$check_dia_6 = "";

	}

	if(isset($_GET['idU']) && (!isset($cod_usuarios_env) || $cod_usuarios_env == "" || $cod_usuarios_env == 0)){
		$cod_clientes_env = fnDecode($_GET['idU']);
	}

	// fnEscreve($cod_usuarios_env);

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
									<?php } 

									if($popUp != 'true'){
										$abaEmpresa = 1398; include "abasEmpresaConfig.php";
									}

									switch ($_SESSION["SYS_COD_SISTEMA"]) {
										case 16: //gabinete
											$usuario = "Colaborador";
											$cliente = "Apoiador";
											$solicitante = "Indicador";
											$plural = "es";
											break;
										default;
											$usuario = "Usuário";
											$cliente = "Cliente";
											$solicitante = "Solicitante";
											$plural = "s";
										break;
									}
									?>
									
									<div class="push10"></div> 
			
									<div class="login-form">
									
										<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
																				
										<fieldset>
											<legend>Dados Gerais</legend> 
											
												<div class="row">

													<div class="col-md-2">   
														<div class="form-group">
															<label for="inputName" class="control-label">Gera Tarefa?</label> 
															<div class="push5"></div>
																<label class="switch">
																<input type="checkbox" name="LOG_TAREFAS" id="LOG_TAREFAS" class="switch" value="S" <?=$check_tarefas?>>
																<span></span>
																</label>
														</div>
													</div>	
													
													<div class="col-md-3">
					                                    <div class="form-group">
					                                        <label for="inputName" class="control-label required">Tipo do Evento</label>
				                                            <select data-placeholder="Selecione um tipo" name="COD_TPEVENT" id="COD_TPEVENT" class="chosen-select-deselect chk" style="width:100%" required >
				                                                <option value=""></option>
				                                                <?php 
				                                                
				                                                    $sql = "SELECT COD_TPEVENT, DES_TPEVENT FROM TIPO_EVENTO
				                                                            WHERE COD_EMPRESA = $cod_empresa";
				                                                    $arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
				                                                
				                                                    while ($qrLista = mysqli_fetch_assoc($arrayQuery))
				                                                    {                                                     
				                                                ?>
				                                                        <option value="<?=$qrLista['COD_TPEVENT']?>"><?=$qrLista['DES_TPEVENT']?></option> 
				                                                <?php 
				                                                    }                                         
				                                                ?>

				                                                <!-- <option value="add">&nbsp;ADICIONAR NOVO</option> -->
				                                            </select>
				                                           	<script type="text/javascript">$('#COD_TPEVENT').val("<?=$cod_tpevent?>").trigger("chosen:updated");</script>
				                                            <div class="help-block with-errors"></div>
					                                    </div>
					                                </div>

					                                <div class="col-md-4">
														<div class="form-group">
															<label for="inputName" class="control-label required ">Nome do Evento</label>
															<input type="text" class="form-control input-sm" name="NOM_EVENT" id="NOM_EVENT" maxlength="150" data-error="Campo obrigatório" value="<?=$nom_event?>" required>
															<div class="help-block with-errors"></div>
														</div>
													</div>

													<div class="col-md-3"> 
														<div class="form-group">
															<label for="inputName" class="control-label required">Prioridade</label>
															<select class="chosen-select-deselect Chk" data-placeholder="Selecione a prioridade" name="COD_PRIORIDADE" id="COD_PRIORIDADE" required>
																<?php 
																	
																		$sql = "SELECT * FROM SAC_PRIORIDADE";
																		$arrayQuery = mysqli_query($connAdmSAC->connAdm(),$sql) or die(mysqli_error());
																	
																		while ($qrPrioridade = mysqli_fetch_assoc($arrayQuery))
																		  {
																		  	?>
																		  	<option value="<?php echo $qrPrioridade['COD_PRIORIDADE']; ?>"><?php echo $qrPrioridade['DES_PRIORIDADE']; ?></option>
																		  	<?php } ?>
															</select>
															<script type="text/javascript">$('#COD_PRIORIDADE').val("<?=$cod_prioridade?>").trigger("chosen:updated");</script>
															<div class="help-block with-errors"></div>
														</div>
													</div>

												</div>

												<div class="row">

					                                <div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label required">Data Inicial</label>
															
															<div class="input-group date datePicker" id="DAT_INI_GRP">
																<input type='text' class="form-control input-sm data" name="DAT_INI" id="DAT_INI" value="<?=fnDataShort($dat_ini)?>" required />
																<span class="input-group-addon">
																	<span class="glyphicon glyphicon-calendar"></span>
																</span>
															</div>
															<div class="help-block with-errors"></div>
														</div>
													</div>
													
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label required">Data Final</label>
															
															<div class="input-group date datePicker" id="DAT_FIM_GRP">
																<input type='text' class="form-control input-sm data" name="DAT_FIM" id="DAT_FIM" value="<?=fnDataShort($dat_fim)?>" required/>
																<span class="input-group-addon">
																	<span class="glyphicon glyphicon-calendar"></span>
																</span>
															</div>
															<div class="help-block with-errors"></div>
														</div>
													</div>

													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label required">Hora Inicial</label>
															
															<div class="input-group timePicker" id="HOR_INI_GRP">
																<input type='text' class="form-control input-sm" name="HOR_INI" id="HOR_INI" value="<?=$hor_ini?>" required/>
																<span class="input-group-addon">
																	<span class="far fa-clock"></span>
																</span>
															</div>
															<div class="help-block with-errors"></div>
														</div>
													</div>
													
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label required ">Hora Final</label>
															
															<div class="input-group timePicker" id="HOR_FIM_GRP">
																<input type='text' class="form-control input-sm" name="HOR_FIM" id="HOR_FIM" value="<?=$hor_fim?>" required/>
																<span class="input-group-addon">
																	<span class="far fa-clock"></span>
																</span>
															</div>
															<div class="help-block with-errors"></div>
														</div>
													</div>
																				
												</div>

												<div class="row">
													
													<div class="col-md-8">
														<div class="form-group">
															<label for="inputName" class="control-label required">Responsável</label>
																<select data-placeholder="Selecione os <?=$usuario.$plural?>" name="COD_USUARIOS_ENV[]" id="COD_USUARIOS_ENV" multiple="multiple" class="chosen-select-deselect requiredChk" style="width:100%;" tabindex="1" required>
																	<?php 
																	
																		$sql = "select COD_USUARIO, NOM_USUARIO from usuarios 
																		where COD_EMPRESA = $cod_empresa AND usuarios.DAT_EXCLUSA is null order by  usuarios.NOM_USUARIO ";
																		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
																	
																		while ($qrLista = mysqli_fetch_assoc($arrayQuery))
																		  {														
																			echo"
																				  <option value='".$qrLista['COD_USUARIO']."'>".$qrLista['NOM_USUARIO']."</option> 
																				"; 
																			  }											
																	?> 
																</select>
															<div class="help-block with-errors"></div>
														</div>
													</div>

													<div class="col-md-4">
														<div class="form-group">
															<label for="inputName" class="control-label ">Local do Evento</label>
															<input type="text" class="form-control input-sm" name="DES_LOCAL" id="DES_LOCAL" maxlength="250" value="<?=$des_local?>">
															<div class="help-block with-errors"></div>
														</div>
													</div>
													
													<div class="push10"></div>

													<div class="col-md-12">
														<label for="inputName" class="control-label"><?=$solicitante."(".$plural.")"?></label>
														<div class="input-group">
														<span class="input-group-btn">
														<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary btn-sm addBox" data-url="action.php?mod=<?php echo fnEncode(1071)?>&id=<?php echo fnEncode($cod_empresa)?>&op=AGE&pop=true" data-title="Busca <?=$cliente?>"><i class="fa fa-search" aria-hidden="true" style="padding-top: 3px;" ></i></a>
														</span>
														<select data-placeholder="Nenhum <?=$cliente?> Selecionado" name="COD_CLIENTES_ENV[]" id="COD_CLIENTES_ENV" multiple="multiple" class="chosen-select-deselect" style="width:100%;" tabindex="1">
															<?php 

																if($cod_clientes_env != ""){
																	$sql = "SELECT COD_CLIENTE, NOM_CLIENTE FROM CLIENTES WHERE COD_EMPRESA = $cod_empresa AND COD_CLIENTE IN($cod_clientes_env)";
																	$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
																	
																		while ($qrLista = mysqli_fetch_assoc($arrayQuery))
																		{														
																			echo"
																				  <option value='".$qrLista['COD_CLIENTE']."'>".$qrLista['NOM_CLIENTE']."</option> 
																				"; 
																		}

																}

															?>
														</select>
														<!-- <?php if($cod_clientes_env != ""){ fnEscreve($sql); } ?> -->
														</div>
														<div class="help-block with-errors"><?=$cliente.$plural?></div>														
													</div>

													<div class="push10"></div>

					                                <div class="col-md-12"><!--Bloco de repetição-->
					                                
					                                	<fieldset>
															<legend>Repetição de Evento</legend>

															<div class="row">

																<div class="col-md-1">   
																	<div class="form-group">
																		<label for="inputName" class="control-label">Domingo</label> 
																		<div class="push5"></div>
																			<label class="switch">
																			<input type="checkbox" name="LOG_REPETE_0" id="LOG_REPETE_0" class="switch" value="'0'" <?=$check_dia_0?>>
																			<span></span>
																			</label>
																	</div>
																</div>
																
																<div class="col-md-1">   
																	<div class="form-group">
																		<label for="inputName" class="control-label">Segunda</label> 
																		<div class="push5"></div>
																			<label class="switch">
																			<input type="checkbox" name="LOG_REPETE_1" id="LOG_REPETE_1" class="switch" value="1" <?=$check_dia_1?>>
																			<span></span>
																			</label>
																	</div>
																</div>

																<div class="col-md-1">   
																	<div class="form-group">
																		<label for="inputName" class="control-label">Terça</label> 
																		<div class="push5"></div>
																			<label class="switch">
																			<input type="checkbox" name="LOG_REPETE_2" id="LOG_REPETE_2" class="switch" value="2" <?=$check_dia_2?>>
																			<span></span>
																			</label>
																	</div>
																</div>

																<div class="col-md-1">   
																	<div class="form-group">
																		<label for="inputName" class="control-label">Quarta</label> 
																		<div class="push5"></div>
																			<label class="switch">
																			<input type="checkbox" name="LOG_REPETE_3" id="LOG_REPETE_3" class="switch" value="3" <?=$check_dia_3?>>
																			<span></span>
																			</label>
																	</div>
																</div>

																<div class="col-md-1">   
																	<div class="form-group">
																		<label for="inputName" class="control-label">Quinta</label> 
																		<div class="push5"></div>
																			<label class="switch">
																			<input type="checkbox" name="LOG_REPETE_4" id="LOG_REPETE_4" class="switch" value="4" <?=$check_dia_4?>>
																			<span></span>
																			</label>
																	</div>
																</div>

																<div class="col-md-1">   
																	<div class="form-group">
																		<label for="inputName" class="control-label">Sexta</label> 
																		<div class="push5"></div>
																			<label class="switch">
																			<input type="checkbox" name="LOG_REPETE_5" id="LOG_REPETE_5" class="switch" value="5" <?=$check_dia_5?>>
																			<span></span>
																			</label>
																	</div>
																</div>
																
																<div class="col-md-1">   
																	<div class="form-group">
																		<label for="inputName" class="control-label">Sábado</label> 
																		<div class="push5"></div>
																			<label class="switch">
																			<input type="checkbox" name="LOG_REPETE_6" id="LOG_REPETE_6" class="switch" value="6" <?=$check_dia_6?>>
																			<span></span>
																			</label>
																	</div>
																</div>

															</div>

														</fieldset>

					                                </div><!--/Bloco de repetição-->

					                                <div class="push10"></div>

					                                <div class="col-md-12">

						                                <?php

															$sql = "SELECT COD_TPFILTRO, DES_TPFILTRO FROM TIPO_FILTRO
															WHERE COD_EMPRESA = $cod_empresa AND COD_TPFILTRO IN(28,29,31)
															ORDER BY NUM_ORDENAC";
															$arrayQuery = mysqli_query(connTemp($cod_empresa,''),trim($sql));

															if(mysqli_num_rows($arrayQuery) > 0){
															$countFiltros = 0;
														?>
																<style>@import url("css/fa5all.css");</style>
																<fieldset>
																<legend>Filtros</legend> 
																	
																	<div class="row">

														<?php 
																	while($qrTipo = mysqli_fetch_assoc($arrayQuery)){
														?>

														<style type="text/css">
															#COD_FILTRO_<?=$qrTipo["COD_TPFILTRO"]?>_chosen .chosen-drop .chosen-results li:last-child{
															    font-weight: bolder;
															    font-size: 11px;
															    color: #000;
															}

															#COD_FILTRO_<?=$qrTipo["COD_TPFILTRO"]?>_chosen .chosen-drop .chosen-results li:last-child:before{
															    content: '\002795';
															    font-weight: bolder;
															    font-size: 9px;
															}
														</style>

																		<div class="col-md-3">
																			<div class="form-group">
																				<label for="inputName" class="control-label"><?=$qrTipo['DES_TPFILTRO']?></label>
																				<div id="relatorioFiltro_<?=$countFiltros?>">
																					<input type="hidden" name="COD_TPFILTRO_<?=$countFiltros?>" id="COD_TPFILTRO_<?=$countFiltros?>" value="<?=$qrTipo['COD_TPFILTRO']?>">
																					<select data-placeholder="Selecione o filtro" name="COD_FILTRO_<?=$countFiltros?>" id="COD_FILTRO_<?=$qrTipo[COD_TPFILTRO]?>" class="chosen-select-deselect last-chosen-link">
																						<option value=""></option>
														<?php
																						$sqlFiltro = "SELECT COD_FILTRO, DES_FILTRO FROM FILTROS_CLIENTE
																									  WHERE COD_TPFILTRO = $qrTipo[COD_TPFILTRO]
																									  ORDER BY DES_FILTRO";

																						$arrayFiltros = mysqli_query(connTemp($cod_empresa,''),trim($sqlFiltro));
																						while($qrFiltros = mysqli_fetch_assoc($arrayFiltros)){
														?>

																							<option value="<?=$qrFiltros['COD_FILTRO']?>"><?=$qrFiltros['DES_FILTRO']?></option>

														<?php 
																						}

																						if($cod_event != "" && $cod_event != 0){
																								$sqlChosen = "SELECT COD_FILTRO FROM EVENTO_FILTROS
																												WHERE COD_EVENTO = $cod_event AND COD_TPFILTRO =".$qrTipo['COD_TPFILTRO'];
																								$arrayChosen = mysqli_query(connTemp($cod_empresa,''),$sqlChosen);
																								if(mysqli_num_rows($arrayChosen) > 0){
																									$qrChosen = mysqli_fetch_assoc($arrayChosen);
														?>
																										<script>
																										$('#COD_FILTRO_<?=$qrTipo[COD_TPFILTRO]?>').val(<?=$qrChosen['COD_FILTRO']?>).trigger('chosen:updated');
														 												</script>
														<?php
																								}
																						}
														?>					
																						<option value="add">&nbsp;ADICIONAR NOVO</option>
																					</select>
																					<script type="text/javascript">
										                                            	$('#COD_FILTRO_<?=$qrTipo[COD_TPFILTRO]?>').change(function(){
																							valor = $(this).val();
																							if(valor=="add"){
																								$(this).val('').trigger("chosen:updated");
																								$('#btnCad_<?=$countFiltros?>').click();
																							}
																						});
										                                            </script>                                                         
																					<div class="help-block with-errors"></div>
																				</div>
																			</div>
																		</div>
																		<a type="hidden" name="btnCad_<?=$countFiltros?>" id="btnCad_<?=$countFiltros?>" class="addBox" data-url="action.php?mod=<?php echo fnEncode(1398)?>&id=<?php echo fnEncode($cod_empresa)?>&idF=<?=fnEncode($qrTipo[COD_TPFILTRO])?>&idS=<?=fnEncode($countFiltros)?>&pop=true" data-title="Cadastrar Filtro - <?=$qrTipo[DES_TPFILTRO]?>"></a>

														<?php 
																		$countFiltros++;
																	}
														?>

																	</div>

																</fieldset>

																<div class="push10"></div>

														<?php 
															}
														?>

													</div>

												</div>

												<div class="push10"></div>

												<div class="row">
													<div class="col-lg-12">
														<div class="form-group">
															<label for="inputName" class="control-label ">Descrição do Evento: </label>
															<textarea class="form-control input-sm" rows="6" name="DES_EVENT" id="DES_EVENT"><?=$des_event?></textarea>
															<div class="help-block with-errors"></div>
														</div>
													</div>
												</div>
												
											</fieldset>	
																				
										<div class="push10"></div>
										<hr>	
										<div class="form-group text-right col-lg-12">
											
											  <button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
											  <?php if($cod_event == 0){ ?>
											  	<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
											  <?php }else{ ?>
											  	<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>
											  <?php } ?>
											   <button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fal fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>
											
										</div>
										
										
										
										<input type="hidden" name="COD_EVENT" id="COD_EVENT" value="<?=$cod_event?>">
										<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?=$cod_empresa?>">
										<input type="hidden" name="COUNT_FILTROS" id="COUNT_FILTROS" value="<?=$countFiltros?>">
										<input type="hidden" name="COD_CLIENTE_ENV" id="COD_CLIENTE_ENV" value="">
										<input type="hidden" name="NOM_CLIENTE_ENV" id="NOM_CLIENTE_ENV" value="">
										<input type="hidden" name="REFRESH_CLIENTE" id="REFRESH_CLIENTE" value="N">
										<input type="hidden" name="opcao" id="opcao" value="">
										<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
										<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">		
										
										<div class="push5"></div> 
										
										</form>
										
										<div class="push50"></div>								
									
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
	
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
	<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />
	<script type="text/javascript">

		$(function(){

			$('.datePicker').datetimepicker({
			 format: 'DD/MM/YYYY'
			}).on('changeDate', function(e){
				$(this).datetimepicker('hide');
			});

			$('.timePicker').datetimepicker({
			 format: 'HH:mm'
			}).on('changeDate', function(e){
				$(this).datetimepicker('hide');
			});

			$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
			$('#formulario').validator();
			
			var usuarios_env = '<?php echo $cod_usuarios_env; ?>';
			if(usuarios_env != 0 && usuarios_env != ""){
				//retorno combo multiplo - USUARIOS_ENV
			$("#formulario #COD_USUARIOS_ENV").val('').trigger("chosen:updated");

				var sistemasUni = '<?php echo $cod_usuarios_env; ?>';				
				var sistemasUniArr = sistemasUni.split(',');				
				//opções multiplas
				for (var i = 0; i < sistemasUniArr.length; i++) {
				  $("#formulario #COD_USUARIOS_ENV option[value=" + Number(sistemasUniArr[i]).toString() + "]").prop("selected", "true");				  
				}
				$("#formulario #COD_USUARIOS_ENV").trigger("chosen:updated");
			}

			var clientes_env = '<?php echo $cod_clientes_env; ?>';
			if(clientes_env != 0 && clientes_env != ""){
			//retorno combo multiplo - USUARIOS_ENV
			$("#formulario #COD_CLIENTES_ENV").val('').trigger("chosen:updated");

				var sistemasUni = '<?php echo $cod_clientes_env; ?>';			
				var sistemasUniArr = sistemasUni.split(',');
				
				//opções multiplas
				for (var i = 0; i < sistemasUniArr.length; i++) {
				  $("#formulario #COD_CLIENTES_ENV option[value=" + sistemasUniArr[i] + "]").prop("selected", "true");				  
				}
				$("#formulario #COD_CLIENTES_ENV").trigger("chosen:updated");
				
			}

		});

		$('.modal').on('hidden.bs.modal', function () {
			  
			if ($('#REFRESH_CLIENTE').val() == "S"){

				$("#COD_CLIENTES_ENV").append('<option value="'+$("#COD_CLIENTE_ENV").val()+'">'+$("#NOM_CLIENTE_ENV").val()+'</option>').trigger("chosen:updated");

				var sistemasUniArr = $("#COD_CLIENTES_ENV").val();

				// alert(sistemasUniArr);

				if(sistemasUniArr){	
					
					//opções multiplas
					for (var i = 0; i < sistemasUniArr.length; i++) {
					  $("#formulario #COD_CLIENTES_ENV option[value=" + sistemasUniArr[i] + "]").prop("selected", "true");				  
					}

				}

				$("#formulario #COD_CLIENTES_ENV option[value=" + $("#COD_CLIENTE_ENV").val() + "]").prop("selected", "true").trigger("chosen:updated");
				$('#REFRESH_CLIENTE').val('N');
				
			}

		});
		
	</script>	