<?php
	
	//echo fnDebug('true');

	$hashLocal = mt_rand();	
	
	$mostraChecadoAT = "checked";				
	$mostraChecadoRT = "";
	$cod_campanha = 0;

	// if(isset($_GET['idT'])){
	// 	$tip_campanha = fnLimpaCampoZero(fnDecode($_GET['idT']));
	// }

	
	//verifica se vem da tela sem pop up
	if (is_null($_GET['idp'])) {

		$log_preTipo='N';

	}else{

		$log_preTipo='S'; 
		$cod_preTipo = fnDecode($_GET['idp']);

	}
				
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

			$cod_campanha = fnLimpaCampoZero($_REQUEST['COD_CAMPANHA']);
			$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
			$cod_univend = fnLimpaCampoZero($_REQUEST['COD_UNIVEND']);
			if (empty($_REQUEST['LOG_ATIVO'])) {$log_ativo='N';}else{$log_ativo=$_REQUEST['LOG_ATIVO'];}
			if (empty($_REQUEST['LOG_REALTIME'])) {$log_realtime='N';}else{$log_realtime=$_REQUEST['LOG_REALTIME'];}			
			$des_campanha = fnLimpaCampo($_REQUEST['DES_CAMPANHA']);
			$abr_campanha = fnLimpaCampo($_REQUEST['ABR_CAMPANHA']);
			$tip_campanha = fnLimpaCampo($_REQUEST['TIP_CAMPANHA']);
			$des_icone = fnLimpaCampo($_REQUEST['DES_ICONE']);
			$des_cor = fnLimpaCampo($_REQUEST['DES_COR']);
			$des_observa = fnLimpaCampo($_REQUEST['DES_OBSERVA']);
			if (empty($_REQUEST['LOG_CONTINU'])) {$log_continu='N';}else{$log_continu=$_REQUEST['LOG_CONTINU'];}
			if (empty($_REQUEST['LOG_ATUALIZA'])) {$log_atualiza='N';}else{$log_atualiza=$_REQUEST['LOG_ATUALIZA'];}			
			$dat_ini = fnLimpaCampo($_REQUEST['DAT_INI']);
			$hor_ini = fnLimpaCampo($_REQUEST['HOR_INI']);
			$dat_fim = fnLimpaCampo($_REQUEST['DAT_FIM']);
			$hor_fim = fnLimpaCampo($_REQUEST['HOR_FIM']);

			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];

			$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
			
			// fnEscreve($cod_univend);
						
			if ($opcao != ''){	
				
				$sql = "CALL SP_ALTERA_CAMPANHA (
												'".$cod_campanha."', 
												'".$cod_empresa."', 
												'".$cod_univend."', 
												'".$log_ativo."', 
												'".$des_campanha."', 
												'".$abr_campanha."', 
												'".$des_icone."', 
												'".$des_cor."', 
												'".$log_realtime."', 
												'".$des_observa."', 
												'".$cod_usucada."', 
												'".$tip_campanha."',
												'".$log_continu."',
												".fnDateSql($dat_ini).",
												".fnDateSql($dat_fim).",
												'".$hor_ini."',
												'".$hor_fim."',
												'".$log_atualiza."',
												'".$opcao."'    
												) ";
				
				//versão antiga
				//'".fndataSql($dat_ini)."',
				//echo $sql;
				//fnTestesql(connTemp($cod_empresa,""),$sql);
				$result = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());				
				$qrBuscaNovo = mysqli_fetch_assoc($result);
				
				//fnEscreve($qrBuscaNovo["COD_NOVO"]);				
				$cod_campanha = $qrBuscaNovo["COD_NOVO"];

				//atualiza lista iframe				
				?>
				<script>
					try { parent.$('#REFRESH_CAMPANHA').val("S"); } catch(err) {}
				</script>

				<?php				
				
				
				//se pre configuração, redireciona para CAMPANHA - já está no 
				if ($log_preTipo == 'S') {						
					
				?>				

				<!-- <div style="width: 100%; margin: auto;">
				<div class="loading" style="width: 100%;"></div>
				<center>Aguarde. Processando...</center>
				</div>
				<script>
					window.location = "action.php?mod=<?php echo fnEncode(1022) ?>&id=<?php echo fnEncode($cod_empresa); ?>&idx=<?php echo $_GET['idx']; ?>&idp=<?php echo fnEncode($tip_campanha); ?>&idc=<?php echo fnEncode($qrBuscaNovo["COD_NOVO"]); ?> ";
				</script> -->	
				
				<?php	
				}			
				 
				//mensagem de retorno
				switch ($opcao)
				{
					case 'CAD':

						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";	

						$sql = "SELECT MAX(COD_CAMPANHA) AS COD_CAMPANHA 
								FROM CAMPANHA 
								WHERE COD_EMPRESA = $cod_empresa 
								AND COD_USUCADA = $cod_usucada";

						$qrCod = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));
						
						$cod_campanha = $qrCod['COD_CAMPANHA'];

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
	
	//defaul - perfil
	
	//busca dados da url	
	if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))){
		//busca dados da empresa
		$cod_empresa = fnDecode($_GET['id']);	
		$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '".$cod_empresa."' ";
		//fnEscreve($sql);
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);                     

		// fnEscreve($cod_campanha);
		if ($cod_campanha == 0) {$cod_campanha = fnDecode($_GET['idc']);}		
		if ($cod_campanha != 0){
			$sql = "select (select count(*) from CAMPANHAREGRA WHERE CAMPANHAREGRA.COD_CAMPANHA=CAMPANHA.COD_CAMPANHA) AS TEM_REGRA ,CAMPANHA.* 
				    from CAMPANHA 			
					where COD_CAMPANHA = ".$cod_campanha;
			 
			// fnEscreve($sql);
			$arrayQuery = mysqli_query(ConnTemp($cod_empresa,''),$sql);
			$qrBuscaCAMPANHA = mysqli_fetch_assoc($arrayQuery);

			if (isset($arrayQuery)){
				//fnEscreve('query busca');
				$log_ativo = $qrBuscaCAMPANHA['LOG_ATIVO'];
				if ($log_ativo == "S"){ $mostraChecadoAT = "checked";}
				else {$mostraChecadoAT = "";}				
				$log_realtime = $qrBuscaCAMPANHA['LOG_REALTIME'];
				if ($log_realtime == "S"){ $mostraChecadoRT = "checked";}
				else {$mostraChecadoRT = "";}
				$cod_univend = $qrBuscaCAMPANHA['cod_univend'];
				$des_campanha = $qrBuscaCAMPANHA['DES_CAMPANHA'];
				$abr_campanha = $qrBuscaCAMPANHA['ABR_CAMPANHA'];
				$tip_campanha = $qrBuscaCAMPANHA['TIP_CAMPANHA'];
				$des_icone = $qrBuscaCAMPANHA['DES_ICONE'];
				$des_cor = $qrBuscaCAMPANHA['DES_COR'];
				$des_observa = $qrBuscaCAMPANHA['DES_OBSERVA']; 
				$tem_regra = $qrBuscaCAMPANHA["TEM_REGRA"];
				
				$log_continu = $qrBuscaCAMPANHA["LOG_CONTINU"];
				if ($log_continu == "S"){ $mostraChecadoTC = "checked";}
				else {$mostraChecadoTC = "";}
				
				$dat_ini = $qrBuscaCAMPANHA["DAT_INI"];
				$hor_ini = $qrBuscaCAMPANHA["HOR_INI"];
				$dat_fim = $qrBuscaCAMPANHA["DAT_FIM"];
				$hor_fim = $qrBuscaCAMPANHA["HOR_FIM"];
				
				$log_atualiza = $qrBuscaCAMPANHA["LOG_ATUALIZA"];
				// fnEscreve($log_atualiza);
				if ($log_atualiza == "S"){ $mostraChecadoATU = "checked";}
				else {$mostraChecadoATU = "";}
			}
		
		}else{
			
			//fnEscreve('sem query busca');
			$cod_campanha = 0;
			$cod_univend = "9999";
			$log_ativo = "N";
			$mostraChecado = "checked";
			$des_campanha = "";
			$abr_campanha = "";
			$des_icone = "";
			$des_cor = "";
			$des_observa = "";
			$tip_campanha = $cod_preTipo;
			$log_continu = "N";
			$dat_ini = "";
			$hor_ini = "";
			$dat_fim = "";
			$hor_fim = "";
			$mostraChecadoTC = "";
			$tem_regra = "";
		}
			
		if (isset($arrayQuery)){
			$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
			$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
		}
												
	}else {
		$cod_empresa = 0;
		$cod_campanha = 0;		
		//fnEscreve('entrou else');
		$log_ativo = "N";
		$mostraChecado = "checked";
		$mostraChecadoATU = "checked";		
		$des_campanha = "";
		$abr_campanha = "";
		$des_icone = "";
		$des_cor = "";
		$des_observa = "";	
		$tip_campanha = "";	
		$log_continu = "N";
		$dat_ini = "";
		$hor_ini = "";
		$dat_fim = "";
		$hor_fim = "";
	}

	if($des_icone == ""){

		if($tip_campanha != 0 || $tip_campanha != ''){
			$sql = "SELECT DES_ICONE FROM TIPOCAMPANHA WHERE COD_TPCAMPA = $tip_campanha";
			$qrIco = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(),$sql));
			$des_icone = $qrIco['DES_ICONE'];
			//fnEscreve($des_icone);
		}else{
			$des_icone = "";
		}

	}
	
	
	//fnEscreve($qrBuscaNovo["COD_NOVO"]);
	// fnEscreve($cod_univend);
	//fnMostraForm();

?>
			
					<?php if ($popUp != "true"){ ?>
						<div class="push30"></div> 
					<?php } ?>
					
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
										<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
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
									
									<?php if ($log_preTipo =='S') { ?>	
									<div class="alert alert-warning top30 bottom30" role="alert">
									<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									 Informe os dados para o preenchimento da sua <strong>Campanha</strong>. 
									</div>
									<?php } ?>
		
									<div class="login-form">
									
										<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
										
										<fieldset>
											<legend>Dados Gerais</legend> 
														
												<div class="row">
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label required">Código</label>
															<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_CAMPANHA" id="COD_CAMPANHA" value="<?php echo $cod_campanha; ?>">
														</div>
													</div>
													
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label required">Título da Campanha</label>
															<input type="text" class="form-control input-sm" name="DES_CAMPANHA" id="DES_CAMPANHA" maxlength="30" value="<?php echo $des_campanha; ?>" required>
														</div>														
													</div>
											
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Campanha Ativa</label> 
																<div class="push5"></div>
																<label class="switch">
																<input type="checkbox" name="LOG_ATIVO" id="LOG_ATIVO" class="switch" value="S" <?php echo $mostraChecadoAT; ?> >
																<span></span>
																</label>
														</div>
													</div>

													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Campanha Contínua?</label> 
															<div class="push5"></div>
																<label class="switch">
																<input type="checkbox" name="LOG_CONTINU" id="LOG_CONTINU" class="switch" value="S" <?php echo $mostraChecadoTC; ?> >
																<span></span>
																</label>
														</div>
														<script>
															$(function(){
																$("#LOG_CONTINU").on("change",function(){
																	if($(this).is(':checked')){
																		$("#DAT_FIM, #HOR_FIM").val("").attr("readonly",true);
																		$(".avisoData").text("Desabilitado em campanha contínua");
																	}else{
																		$("#DAT_FIM, #HOR_FIM").attr("readonly",false);
																		$(".avisoData").text("");
																	}
																});
															});
														</script>
													</div>

													<div class="col-md-1">
														<div class="form-group">
															<label for="inputName" class="control-label">Ícone</label><br/>
																<button class="btn btn-primary" id="btniconpicker" data-iconset="fontawesome" 
																	data-icon="<?php echo $des_icone ?>" role="iconpicker" data-arrow-prev-icon-class="fas fa-arrow-left" data-arrow-next-icon-class="fas fa-arrow-right"
																	data-rows="6" data-cols="6" data-search-text="Buscar ícone..." data-label-footer="{0} - {1} de {2} ícones" data-label-header="{0} de {1}">
																</button>
															<input type="hidden" name="DES_ICONE" id="DES_ICONE" value="<?php echo $des_icone ?>">
														</div> 
													</div>

													<div class="col-md-1">
														<div class="form-group">
															<label for="inputName" class="control-label required">Cor</label>
															<input type="text" class="form-control input-sm pickColor" style="margin-top: 4px;" name="DES_COR" id="DES_COR" value="<?php echo $des_cor ?>" required>															
														</div>
														<div class="help-block with-errors"></div>														
													</div>
													
													<!-- <div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Venda em Tempo Real?</label> 
															<div class="push5"></div>
																<label class="switch">
																<input type="checkbox" name="LOG_REALTIME" id="LOG_REALTIME" class="switch" value="S" <?php echo $mostraChecadoRT; ?> >
																<span></span>
																</label>
															<div class="help-block with-errors">Acontece no momento da venda</div>
														</div>
													</div> -->

													<input type="hidden" name="LOG_REALTIME" id="LOG_REALTIME" value="N">
													
												</div>
												
												<div class="push20"></div>
												
												<div class="row">
													
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label required">Data Inicial</label>
															
															<div class="input-group date datePicker" id="DAT_INI_GRP">
																<input type='text' class="form-control input-sm data" name="DAT_INI" id="DAT_INI" value="<?php echo fnFormatDate($dat_ini); ?>" required/>
																<span class="input-group-addon">
																	<span class="glyphicon glyphicon-calendar"></span>
																</span>
															</div>
															<div class="help-block with-errors"></div>
														</div>
													</div>
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Hora Início</label>
															
															<div class='input-group date clockPicker'>
																<input type='text' class="form-control input-sm" name="HOR_INI" id="HOR_INI" value="<?php echo $hor_ini; ?>" />
																<span class="input-group-addon">
																	<span class="glyphicon glyphicon-time"></span>
																</span>
															</div>

														</div>
													</div>
													
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Data Final</label>
															
															<div class="input-group date datePicker" id="DAT_FIM_GRP">
																<input type='text' class="form-control input-sm data" name="DAT_FIM" id="DAT_FIM" <?php if($mostraChecadoTC == "checked"){ echo "readonly value=''"; }else{ echo "value='".fnFormatDate($dat_fim)."'";} ?> />
																<span class="input-group-addon">
																	<span class="glyphicon glyphicon-calendar"></span>
																</span>
															</div>
															<div class="help-block with-errors avisoData"><?php if($mostraChecadoTC == "checked") echo "Desabilitado em campanha contínua"; ?></div>
														</div>
													</div>	
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Hora Final</label>
															
															<div class='input-group date clockPicker'>
																<input type='text' class="form-control input-sm" name="HOR_FIM" id="HOR_FIM" <?php if($mostraChecadoTC == "checked"){ echo "readonly value=''"; }else{ echo "value='".$hor_fim."'";} ?> />
																<span class="input-group-addon">
																	<span class="glyphicon glyphicon-time"></span>
																</span>
															</div>

														</div>
													</div>
													
												</div>
												
												<div class="push20"></div>
												
												<div class="row">

													<!-- <div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Atualizar Personas na Venda <small class="f12">(Live Data)</small></label> 
															<div class="push5"></div>
																<label class="switch">
																<input type="checkbox" name="LOG_ATUALIZA" id="LOG_ATUALIZA" class="switch" value="S" <?php echo $mostraChecadoATU; ?> >
																<span></span>
																</label>
														</div>
													</div> -->

													<input type="hidden" name="LOG_ATUALIZA" id="LOG_ATUALIZA" value="N">
												
													<!-- <div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label required">Tipo da Campanha</label>
																<select data-placeholder="Selecione um tipo de vantagem" name="TIP_CAMPANHA" id="TIP_CAMPANHA" class="chosen-select-deselect requiredChk" required>
																	<option value="">&nbsp;</option>					
																	<?php																	
																		$sql = "select * from TIPOCAMPANHA order by NUM_ORDENAC ";
																		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
																	
																		while ($qrListaVantagem = mysqli_fetch_assoc($arrayQuery))
																		  {	

																			if ($qrListaVantagem['LOG_ATIVO'] == 'N'){ $desabilitado = "disabled";}
																			else {$desabilitado = "";}
																	  
																			echo"
																				  <option value='".$qrListaVantagem['COD_TPCAMPA']."' ".$desabilitado." >".$qrListaVantagem['NOM_TPCAMPA']."</option> 
																				"; 
																			  }											
																	?>	
																</select> 
																<script>$("#formulario #TIP_CAMPANHA").val("<?php echo $tip_campanha; ?>").trigger("chosen:updated"); </script>	
																<?php if ($tem_regra > 0){ ?>
																<script>$("#formulario #TIP_CAMPANHA").prop('disabled', true).trigger("chosen:updated");</script>	
																<input type="hidden" name="TIP_CAMPANHA" id="TIP_CAMPANHA" value="<?php echo $tip_campanha; ?>" >
																<?php }  ?>																	
															<div class="help-block with-errors"></div>
														</div>
													</div> -->

													<input type="hidden" name="TIP_CAMPANHA" id="TIP_CAMPANHA" value="21">

													<!-- div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label required">Apelido da Campanha</label>
															<input type="text" class="form-control input-sm" name="ABR_CAMPANHA" id="ABR_CAMPANHA" value="<?php echo $abr_campanha ?>">
														</div>														
													</div> -->

													<?php
													//rotina de mostar 
													if (fnAutMaster($_SESSION["SYS_COD_TPUSUARIO"], $_SESSION["SYS_COD_EMPRESA"])){
													?>	
														<input type="hidden" name="COD_UNIVEND" id="COD_UNIVEND" value="<?=$cod_univend?>">
														
													<?php	
													}else {
													?>	
														
													<div class="col-md-4">
														<div class="form-group">
															<label for="inputName" class="control-label required">Selecione a sua unidade de referência</label>
															<div class="push5"></div>
															<select data-placeholder="Selecione a sua unidade de referência" name="COD_UNIVEND" id="COD_UNIVEND" class="chosen-select-deselect" style="width:100%;" tabindex="1">
																<?php
																$lojasUsuario = $_SESSION["SYS_COD_UNIVEND"];
																$sql = "SELECT COD_UNIVEND, NOM_FANTASI, LOG_ESTATUS FROM UNIDADEVENDA WHERE COD_EMPRESA = $cod_empresa AND COD_UNIVEND IN ($lojasUsuario) AND LOG_ESTATUS = 'S' AND DAT_EXCLUSA IS NULL ORDER BY NOM_FANTASI ";
																$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());																
																while ($qrListaUnive = mysqli_fetch_assoc($arrayQuery))
																  {
																	echo"
																		  <option value='".$qrListaUnive['COD_UNIVEND']."'>".ucfirst($qrListaUnive['NOM_FANTASI']). "</option> 
																		"; 
																	  }	
																?>								
															</select>
															<script>$("#formulario #COD_UNIVEND").val("<?php echo $cod_univend; ?>").trigger("chosen:updated"); </script>
														
															<div class="help-block with-errors"></div>
														</div>
													</div>														
													<?php	
													}
													?>
													
												</div>
												
												<div class="push20"></div>
												
												<div class="row">													
													
													<div class="col-md-12">
														<div class="form-group">
															<label for="inputName" class="control-label">Objetivo da Campanha</label><br/>
																<textarea class="form-control" rows="3" name="DES_OBSERVA" id="DES_OBSERVA" maxlength="500"><?php echo $des_observa ?></textarea>
															<div class="help-block with-errors"></div>
														</div>
													</div>
													
												</div>
												
										</fieldset>										
											
										<div class="push10"></div>
										<hr>	
										<div class="form-group col-md-4">
											<?php if ($cod_campanha != 0) { ?>
											<a class="btn btn-info" href="action.do?mod=<?php echo fnEncode(1022)?>&id=<?php echo fnEncode($cod_empresa)?>&idc=<?php echo fnEncode($cod_campanha)?>" target="_blank"><i class="fa fa-arrow-right" aria-hidden="true"></i>&nbsp; Acessar Campanha</a>
											<?php } ?>
										</div>	
										<div class="form-group text-right col-md-8">
											  <button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>
											  <?php if ($cod_campanha <> 0) { ?>
											  <button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Alterar</button>
											  <?php } else { ?>
											  <button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
											  <?php } ?>
											
										</div>
										
										<input type="hidden" name="opcao" id="opcao" value="">
										<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
										<input type="hidden" name="ABR_CAMPANHA" id="ABR_CAMPANHA" value="<?php echo $abr_campanha ?>">
										<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
										<?php if ($cod_campanha <> 0) { ?>
											<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">		
										<?php } else { ?>
											<input type="hidden" name="hHabilitado" id="hHabilitado" value="N'">		
										<?php } ?>
										
										<div class="push5"></div> 
										
										</form>
										
										<div class="push50"></div>
									
									</div>								
								
								</div>
							</div>
							<!-- fim Portlet -->
						</div>
						
					</div>					
						
					<div class="push20"></div>
					
	<link rel="stylesheet" href="../css/bootstrap-iconpicker.min.css"/>
	
	<script type="text/javascript" src="../js/bootstrap-iconpicker-iconset-fa5.js"></script>
	<script type="text/javascript" src="../js/bootstrap-iconpicker.js"></script>
	
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    
	<script src="js/plugins/minicolors/jquery.minicolors.min.js"></script>
    <link rel="stylesheet" href="js/plugins/minicolors/jquery.minicolors.css">
	
	<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
	<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />	
	
    <script>
	
		//datas
		$(function () {
			
			$('.datePicker').datetimepicker({
				 format: 'DD/MM/YYYY',
				 //maxDate : 'now',
				}).on('changeDate', function(e){
					$(this).datetimepicker('hide');
				});
				
			$('.clockPicker').datetimepicker({
				 format: 'LT',
				}).on('changeDate', function(e){
					$(this).datetimepicker('hide');
				});

		});		
		
        $(document).ready( function() {
			
			//chosen
			$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
			$('#formulario').validator();

			//color picker
			$('.pickColor').minicolors({
				control: $(this).attr('data-control') || 'hue',				
				theme: 'bootstrap'
			});
			
			//capturando o ícone selecionado no botão
			$('#btniconpicker').on('change', function(e) {
			    $('#DES_ICONE').val(e.icon);
			    //alert($('#DES_ICONE').val());
			});

			icone = "<?php echo $des_icone?>";

			cor = "<?php echo $des_cor?>";

			if(icone == ""){
				icone = "fal fa-user-tag";
			}

			if(cor == ""){
				cor = "#2C3E50";
			}
 
			$("#btniconpicker").iconpicker('setIcon', icone);
			$("#DES_ICONE").val(icone);

			$("#DES_COR").minicolors('value', cor);
			
        });
		
		function retornaForm(index){
			$("#formulario #COD_GRUPOTR").val($("#ret_COD_GRUPOTR_"+index).val());
			$("#formulario #DES_GRUPOTR").val($("#ret_DES_GRUPOTR_"+index).val());
			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');						
		}
		
	</script>	