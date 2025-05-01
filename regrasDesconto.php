<?php
	
	//echo fnDebug('true');

	$hashLocal = mt_rand();
	$checkLog_obgcupom = 'checked';
	$checkLog_prodtkt = 'checked';
	
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
	
			$cod_desctkt = fnLimpaCampoZero($_POST['COD_DESCTKT']);			
			$cod_empresa = fnLimpaCampoZero($_POST['COD_EMPRESA']);	

			$nom_desctkt  = fnLimpaCampo($_POST['NOM_DESCTKT']);			
			$cod_cupomtkt  = fnLimpaCampo($_POST['COD_CUPOMTKT']);			
			
			$tip_aplica = fnLimpaCampo($_POST['TIP_APLICA']);			
			
			$val_maxiuso  = fnLimpaCampo($_POST['VAL_MAXIUSO']);
			$val_mincompra  = fnLimpaCampo($_POST['VAL_MINCOMPRA']);
			$qtd_minitem  = fnLimpaCampo($_POST['QTD_MINITEM']);
			$qtd_limite  = fnLimpaCampo($_POST['QTD_LIMITE']);
			
			$qtd_limite  = fnLimpaCampo($_POST['QTD_LIMITE']);
			
			$cod_produto = fnLimpaCampoZero($_POST['COD_PRODUTO']);
			$cod_categor = fnLimpaCampoZero($_POST['COD_CATEGOR']);
			$cod_subcate = fnLimpaCampoZero($_POST['COD_SUBCATE']);
			$cod_fornecedor = fnLimpaCampoZero($_POST['COD_FORNECEDOR']);

			if (empty($_REQUEST['LOG_PRODTKT'])) {$log_prodtkt='N';}else{$log_prodtkt=$_REQUEST['LOG_PRODTKT'];}

			$dat_iniptkt = fnLimpaCampo($_POST['DAT_INIPTKT']);			
			$dat_fimptkt = fnLimpaCampo($_POST['DAT_FIMPTKT']);			

			$pct_desconto = fnLimpaCampo($_POST['PCT_DESCONTO']);			

			$val_desconto = fnLimpaCampo($_POST['VAL_DESCONTO']);
			
			if (empty($_REQUEST['LOG_OBGCUPOM'])) {$log_obgcupom='N';}else{$log_obgcupom=$_REQUEST['LOG_OBGCUPOM'];}
			if (empty($_REQUEST['LOG_INCLUI'])) {$log_inclui='N';}else{$log_inclui=$_REQUEST['LOG_INCLUI'];}
			if (empty($_REQUEST['LOG_EXCLUI'])) {$log_exclui='N';}else{$log_exclui=$_REQUEST['LOG_EXCLUI'];}
			
			//array das personas
			if (isset($_POST['COD_PERSONA_TKT'])){
				$Arr_COD_PERSONA_TKT = $_POST['COD_PERSONA_TKT'];
				//print_r($Arr_COD_MULTEMP);			 
			   for ($i=0;$i<count($Arr_COD_PERSONA_TKT);$i++) 
			   { $cod_persona_tkt = $cod_persona_tkt.$Arr_COD_PERSONA_TKT[$i].","; } 
			   $cod_persona_tkt = substr($cod_persona_tkt,0,-1);
			}else{$cod_persona_tkt = "0";}
			
			//$cod_univend_aut = fnLimpaCampo($_POST['COD_UNIVEND_AUT']);
			//array das lojas
			if (isset($_POST['COD_UNIVEND_AUT'])){
				$Arr_COD_UNIVEND_AUT = $_POST['COD_UNIVEND_AUT'];
				//print_r($Arr_COD_MULTEMP);			 
			   for ($i=0;$i<count($Arr_COD_UNIVEND_AUT);$i++) 
			   { $cod_univend_aut = $cod_univend_aut.$Arr_COD_UNIVEND_AUT[$i].","; } 
			   $cod_univend_aut = substr($cod_univend_aut,0,-1);
			}else{$cod_univend_aut = "0";}

			//$cod_univend_blk = fnLimpaCampo($_POST['COD_UNIVEND_BLK']);			
			//array das lojas
			if (isset($_POST['COD_UNIVEND_BLK'])){
				$Arr_COD_UNIVEND_BLK = $_POST['COD_UNIVEND_BLK'];
				//print_r($Arr_COD_MULTEMP);			 
			   for ($i=0;$i<count($Arr_COD_UNIVEND_BLK);$i++) 
			   { $cod_univend_blk = $cod_univend_blk.$Arr_COD_UNIVEND_BLK[$i].","; } 
			   $cod_univend_blk = substr($cod_univend_blk,0,-1);
			}else{$cod_univend_blk = "0";}
	   
			$des_mensgtkt = fnLimpaCampo($_POST['DES_MENSGTKT']);
			$des_errodesc = fnLimpaCampo($_POST['DES_ERRODESC']);
			
			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];

			// fnEscreve($cod_univend_blk);

			// if($cod_univend_aut == 0 || $cod_univend_blk = 0){

			// 	$sqlUni = "SELECT COD_UNIVEND FROM UNIDADEVENDA
			// 			   WHERE LOG_ATIVO = 'S'
			// 			   AND COD_EMPRESA = $cod_empresa";

			// 	$arrayUni = mysqli_query($connAdm->connAdm(),$sqlUni);

			// 	$lojasSelecionadas = "";

			// 	while($qrUni = mysqli_fetch_assoc($arrayUni)){
			// 		$lojasSelecionadas .= $qrUni[COD_UNIVEND].",";
			// 	}

			// 	$lojasSelecionadas = ltrim(rtrim($lojasSelecionadas,','),',');

			// 	if($cod_univend_aut == 0){
			// 		$cod_univend_aut = $lojasSelecionadas;
			// 	}

			// 	if($cod_univend_blk = 0){
			// 		$cod_univend_blk = $lojasSelecionadas;
			// 	}

			// }	
			
			if ($opcao != ''){

			$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
					
				$sql = "CALL SP_ALTERA_DESCONTOS (
				 '".$cod_desctkt."', 
				 '".$cod_empresa."', 
				 '".$nom_desctkt."', 
				 '".fnDataTimeSql($dat_iniptkt)."',
				 '".fnDataTimeSql($dat_fimptkt)."',
				 '".fnValorSql($pct_desconto)."',
				 '".fnValorSql($val_desconto)."',
				 '".$cod_persona_tkt."',
				 '".$cod_univend_aut."',
				 '".$cod_univend_blk."',
				 '".$log_prodtkt."',
				 '".$des_mensgtkt."', 
				 '".$des_errodesc."', 
				 '".$cod_usucada."', 
				 '".$cod_cupomtkt."', 
				 '".$tip_aplica."', 
				 '".fnValorSql($val_maxiuso,2)."',
				 '".fnValorSql($val_mincompra)."',
				 '".fnValorSql($qtd_minitem)."',
				 '".fnValorSql($qtd_limite)."',
				 '".$cod_produto."',
				 '".$cod_categor."',
				 '".$cod_subcate."',
				 '".$cod_fornecedor."',
				 '".$log_obgcupom."',
				 '".$log_inclui."',
				 '".$log_exclui."',
				 '".$opcao."'    
				) ";
				
				//echo $sql;
				
				mysqli_query(connTemp($cod_empresa,''),trim($sql)) or die(mysqli_error());	
				//fnTestesql(connTemp($cod_empresa,''),trim($sql));	
				//fnEscreve($sql); 

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
		$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '".$cod_empresa."' ";

		//fnEscreve($sql);
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
			
		if (isset($qrBuscaEmpresa)){
			$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];

		}
												
	}else {
		$cod_empresa = 0;		
		$nom_empresa = "";
	
	}
	
	$checkLOG_EMAIL = 'checked';
	$checkLOG_SMS = 'checked';
	$checkLOG_PUSH = 'checked';
	$checkLOG_WHATSAPP = 'checked';
	
	//fnMostraForm();
	//fnEscreve($cod_empresa);
	
?>	

<style>

.rdo-grp {
  position: absolute;
  top: calc(50% - 10px);
}
.rdo-grp label {
  cursor: pointer;
  -webkit-tap-highlight-color: transparent;
  padding: 6px 8px;
  border-radius: 20px;
  float: left;
  transition: all 0.2s ease;
}
.rdo-grp label:hover {
  background: rgba(52,152,219,0.06);
}
.rdo-grp label:not(:last-child) {
  margin-right: 16px;
}
.rdo-grp label span {
  vertical-align: middle;
}
.rdo-grp label span:first-child {
  position: relative;
  display: inline-block;
  vertical-align: middle;
  width: 20px;
  height: 20px;
  background: #e8eaed;
  border-radius: 50%;
  transition: all 0.2s ease;
  margin-right: 8px;
}
.rdo-grp label span:first-child:after {
  content: '';
  position: absolute;
  width: 16px;
  height: 16px;
  margin: 2px;
  background: #fff;
  border-radius: 50%;
  transition: all 0.2s ease;
}
.rdo-grp label:hover span:first-child {
  background: #3498DB;
}
.rdo-grp input {
  display: none;
}
.rdo-grp input:checked + label span:first-child {
  background: #3498DB;
}
.rdo-grp input:checked + label span:first-child:after {
  transform: scale(0.5);
}


</style>								  
		
			
					<?php if ($popUp != "true"){  ?>							
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
										<i class="fal fa-terminal"></i>
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
									
									<?php $abaModulo = 1180; include "abasTicketConfig.php"; ?>
									
									<div class="push30"></div> 
																
									<div class="login-form">
									
										<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
																				
										<fieldset>
											<legend>Dados Gerais</legend>  
															
												<div class="row">

													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Desconto Ativo</label> 
															<div class="push5"></div>
																<label class="switch">
																<input type="checkbox" name="LOG_PRODTKT" id="LOG_PRODTKT" class="switch" value="S" <?php echo $checkLog_prodtkt; ?> >
																<span></span>
																</label>
														</div>
													</div>
												
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label required">Código</label>
															<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_DESCTKT" id="COD_DESCTKT" value="">
														</div>
													</div>
													
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label required">Empresa</label>
															<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa ?>">
															<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">
														</div>														
													</div>
												
												</div>
												
												<div class="row">
														
													<div class="col-md-4">
														<div class="form-group">
															<label for="inputName" class="control-label required">Título do Desconto</label>
															<input type="text" class="form-control input-sm" name="NOM_DESCTKT" id="NOM_DESCTKT" maxlength="50" required>
															<div class="help-block with-errors"></div>
														</div>
													</div>
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Cupom Obrigatório</label> 
															<div class="push5"></div>
																<label class="switch">
																<input type="checkbox" name="LOG_OBGCUPOM" id="LOG_OBGCUPOM" class="switch" value="S" <?php echo $checkLog_obgcupom; ?> >
																<span></span>
																</label>
														</div>
													</div>
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label ">Nome / Cód. Cupom</label>
															<input type="text" class="form-control input-sm" name="COD_CUPOMTKT" id="COD_CUPOMTKT" maxlength="30" >
															<div class="help-block with-errors"></div>
														</div>
													</div>

													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label required">Data Inicial</label>
															
															<div class="input-group date datePicker" id="DAT_INI_GRP">
																<input type='text' class="form-control input-sm data" name="DAT_INIPTKT" id="DAT_INIPTKT" value="" required />
																<span class="input-group-addon">
																	<span class="glyphicon glyphicon-calendar"></span>
																</span>
															</div>
															<span class="help-block">Validade</span>
														</div>
													</div>
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label required">Data Final</label>
															
															<div class="input-group date datePicker" id="DAT_FIM_GRP">
																<input type='text' class="form-control input-sm data" name="DAT_FIMPTKT" id="DAT_FIMPTKT" value="" required />
																<span class="input-group-addon">
																	<span class="glyphicon glyphicon-calendar"></span>
																</span>
															</div>
															<span class="help-block">Validade</span>
														</div>
													</div>
													
												</div>
												
												<div class="row">
					
													<div class="col-md-3">
														<label for="inputName" class="control-label required">Aplicar Sobre:</label>
														
														<div class="push50"></div>
														
														<div class="rdo-grp">
														  <input id="TIP_APLICA1" type="radio" name="TIP_APLICA" value="C" />
														  <label for="TIP_APLICA1"><span></span><span>Total da Compra</span></label>
														  <input id="TIP_APLICA2" type="radio" name="TIP_APLICA" value="I" />
														  <label for="TIP_APLICA2"><span></span><span>Por Item</span></label>
														</div>		
								
													</div>
													
													<div class="push10"></div>
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Desconto (%)</label>
															<input type="text" class="form-control input-sm text-center money" name="PCT_DESCONTO" id="PCT_DESCONTO" maxlength="20" value="">
															<span class="help-block">Percentual (prioritário)</span>
														</div>
													</div>		

													<div class="col-md-1 text-center">
														<div class="push10"></div>
														<h4>ou</h4>
													</div>		

													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Desconto (R$)</label>
															<input type="text" class="form-control input-sm text-center money" name="VAL_DESCONTO" id="VAL_DESCONTO" maxlength="20" value="">
															<span class="help-block">Valor </span>
														</div>
													</div>

													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label required">Qtd. Máxima ou VR Máximo</label>
															<input type="text" class="form-control input-sm text-center money" name="VAL_MAXIUSO" id="VAL_MAXIUSO" maxlength="10" value="" required>
															<span class="help-block">utilização por cliente</span>
														</div>
													</div>	
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label required">Valor Mínimo de Compra</label>
															<input type="text" class="form-control input-sm text-center money" name="VAL_MINCOMPRA" id="VAL_MINCOMPRA" maxlength="10" value="" required>
															<span class="help-block">por compra</span>
														</div>
													</div>	
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label required">Quantidade Mínima de Itens</label>
															<input type="text" class="form-control input-sm text-center money" name="QTD_MINITEM" id="QTD_MINITEM" maxlength="10" value="" required>
															<span class="help-block">por compra</span>
														</div>
													</div>		
													
													<div class="col-md-1">
														<div class="form-group">
															<label for="inputName" class="control-label required">Limite de Uso</label>
															<input type="text" class="form-control input-sm text-center int" name="QTD_LIMITE" id="QTD_LIMITE" maxlength="6" value="" required>
															<span class="help-block">quantidade máxima</span>
														</div>
													</div>
													
													<div class="push15"></div>													
													
													<div class="col-md-3">
														<label for="inputName" class="control-label required">Produto </label>
														<div class="input-group">
														<span class="input-group-btn">
														<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary addBox" data-url="action.php?mod=<?php echo fnEncode(1062);?>&id=<?php echo fnEncode($cod_empresa);?>&tipo=desc&pop=true" data-title="Busca Produtos / <?php echo $nom_empresa; ?>"><i class="fa fa-search" aria-hidden="true"></i></a>
														</span>
														<input type="text" name="DES_PRODUTO" id="DES_PRODUTO" class="form-control input-sm leituraOff" style="border-radius: 0 3px 3px  0;" readonly="readonly" placeholder="Procurar produto específico...">
														<input type="hidden" name="COD_PRODUTO" id="COD_PRODUTO" value="">
														</div>																
													</div>													
												
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Grupo do Produto</label>
																<select data-placeholder="Selecione o grupo" name="COD_CATEGOR" id="COD_CATEGOR" class="chosen-select-deselect">
																	<option value="0">&nbsp;</option>											  
																	<?php
																		$sql = "select * from CATEGORIA where COD_EMPRESA = $cod_empresa AND COD_EXCLUSA is null order by DES_CATEGOR";
																		$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
																		
																		while ($qrListaCategoria = mysqli_fetch_assoc($arrayQuery))
																		  {														
																			echo"
																				  <option value='".$qrListaCategoria['COD_CATEGOR']."'>".$qrListaCategoria['DES_CATEGOR']."</option> 
																				"; 
																			  }	
																	?>
																</select>	
															<div class="help-block with-errors"></div>
														</div>
													</div>											
													
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Sub Grupo do Produto</label>
																<div id="divId_sub">
																<select data-placeholder="Selecione o sub grupo" name="COD_SUBCATE" id="COD_SUBCATE" class="chosen-select-deselect">
																	<option value="0">&nbsp;</option>					
																</select>	
																</div>	
															<div class="help-block with-errors"></div>
														</div>
													</div>
															
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Fornecedor</label>
																<select data-placeholder="Selecione o grupo" name="COD_FORNECEDOR" id="COD_FORNECEDOR" class="chosen-select-deselect">
																	<option value="0">&nbsp;</option>											  
																	<?php
																		$sql = "select * from FORNECEDORMRKA where COD_EMPRESA = $cod_empresa order by NOM_FORNECEDOR";
																		$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
																		
																		while ($qrListaCategoria = mysqli_fetch_assoc($arrayQuery))
																		  {														
																			echo"
																				  <option value='".$qrListaCategoria['COD_FORNECEDOR']."'>".$qrListaCategoria['NOM_FORNECEDOR']."</option> 
																				"; 
																			  }	
																	?>
																</select>	
															<div class="help-block with-errors"></div>
														</div>
													</div>
													
													<div class="push15"></div>
																										
													<div class="col-md-6">
														<div class="form-group">
															<label for="inputName" class="control-label required">Personas participantes</label>
															
																<select data-placeholder="Selecione as personas desejadas" name="COD_PERSONA_TKT[]" id="COD_PERSONA_TKT" multiple="multiple" class="chosen-select-deselect requiredChk" style="width:100%;" tabindex="1" required>
																	<?php
																	//se sistema marka
																	$sql = "select * from persona where cod_empresa = ".$cod_empresa." and LOG_ATIVO = 'S' order by DES_PERSONA  ";																		
																	$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());																
																	while ($qrListaPersonas = mysqli_fetch_assoc($arrayQuery))
																	  {																			
																		echo"
																			  <option value='".$qrListaPersonas['COD_PERSONA']."'>".ucfirst($qrListaPersonas['DES_PERSONA']). "</option> 
																			"; 
																		  }	
																	?>								
																</select>
														</div>
													</div>
													
												</div>
												
												<!-- <div class="push15"></div> -->

												<!-- <div class="row">

													<div class="col-md-6">

														<div class="col-md-1 col-md-offset-11">
															<div class="form-group">
																<label class="switch">
																	<input type="checkbox" name="LOG_INCLUI" id="LOG_INCLUI" class="switch" value="S" >
																	<span></span>
																</label>
															</div>
														</div>
														
														<div class="col-md-11">
															<div class="form-group">
																<div class="push5"></div>
																<label for="inputName" class="control-label">&nbsp; Incluir Unidades <b>Específicas</b> Para Esta Oferta?</label> 
															</div>
														</div>

													</div>

													<div class="col-md-6">
													
														<div class="col-md-1">
															<div class="form-group">
																<label class="switch">
																	<input type="checkbox" name="LOG_EXCLUI" id="LOG_EXCLUI" class="switch" value="S" >
																	<span></span>
																</label>
															</div>
														</div>
														
														<div class="col-md-11">
															<div class="form-group">
																<div class="push5"></div>
																<label for="inputName" class="control-label">&nbsp; Bloquear Unidades <b>Específicas</b> Para Esta Oferta?</label> 
															</div>
														</div>

													</div>

													<script>
														


														$('#LOG_INCLUI').change(function(){
				                                            if($('#LOG_INCLUI').is(':checked')){
				                                                $('#COD_UNIVEND_AUT').prop('disabled',false).prop('required',true).trigger("chosen:updated");
				                                            }else{
				                                                $('#COD_UNIVEND_AUT').val('').prop('disabled',true).prop('required',false).trigger("chosen:updated");
				                                            }
				                                            $('#formulario').validator('validate');			
															$("#formulario #hHabilitado").val('S');
				                                        });

				                                        $('#LOG_EXCLUI').change(function(){
				                                            if($('#LOG_EXCLUI').is(':checked')){
				                                                $('#COD_UNIVEND_BLK').prop('disabled',false).prop('required',true).trigger("chosen:updated");
				                                            }else{
				                                                $('#COD_UNIVEND_BLK').val('').prop('disabled',true).prop('required',false).trigger("chosen:updated");
				                                            }
				                                            $('#formulario').validator('validate');			
															$("#formulario #hHabilitado").val('S');
				                                        });

													</script>

												</div> -->

												<div class="push10"></div>												
												

												<div class="row">	
												
													<div class="col-md-6">
														<div class="form-group">
															<label for="inputName" class="control-label">Unidades Autorizadas</label>
															
																<select data-placeholder="Selecione uma empresa para acesso" name="COD_UNIVEND_AUT[]" id="COD_UNIVEND_AUT" multiple="multiple" class="chosen-select-deselect" style="width:100%;" tabindex="1" required>
																	<?php
																	$sql = "SELECT COD_UNIVEND, NOM_FANTASI FROM UNIDADEVENDA WHERE COD_EMPRESA = $cod_empresa ORDER BY NOM_FANTASI ";
																	$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());																
																	while ($qrListaUnive = mysqli_fetch_assoc($arrayQuery))
																	  {																			
																		echo"
																			  <option value='".$qrListaUnive['COD_UNIVEND']."'>".ucfirst($qrListaUnive['NOM_FANTASI']). "</option> 
																			"; 
																		  }	
																	?>								
																</select>
																<?php //fnEscreve($sql); ?>		
															<div class="help-block with-errors"><!-- Se vazio, <b>todas</b> as unidades estarão <b>autorizadas</b> --></div>
														</div>
													</div>	
													
													<div class="col-md-6">
														<div class="form-group">
															<label for="inputName" class="control-label">Unidades Não Autorizadas</label>
															
																<select data-placeholder="Selecione uma empresa para acesso" name="COD_UNIVEND_BLK[]" id="COD_UNIVEND_BLK" multiple="multiple" class="chosen-select-deselect" style="width:100%;" tabindex="1" required>
																	<?php
																	$sql = "SELECT COD_UNIVEND, NOM_FANTASI FROM UNIDADEVENDA WHERE COD_EMPRESA = $cod_empresa ORDER BY NOM_FANTASI ";
																	$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());																
																	while ($qrListaUnive = mysqli_fetch_assoc($arrayQuery))
																	  {																			
																		echo"
																			  <option value='".$qrListaUnive['COD_UNIVEND']."'>".ucfirst($qrListaUnive['NOM_FANTASI']). "</option> 
																			"; 
																		  }	
																	?>								
																</select>
																<?php //fnEscreve($sql); ?>		
															<div class="help-block with-errors"></div>
														</div>
													</div>													
												
												</div>
												
												<div class="push10"></div>
												
												<div class="row" style="display: none;">

													<div class="col-md-6">
														<div class="form-group">
															<label for="inputName" class="control-label">Mensagem Promocional</label>
															<input type="text" class="form-control input-sm" name="DES_MENSGTKT" id="DES_MENSGTKT" maxlength="150">
															<div class="help-block with-errors"></div>
														</div>
													</div>												
													
													<div class="col-md-6">
														<div class="form-group">
															<label for="inputName" class="control-label">Mensagem Erro</label>
															<input type="text" class="form-control input-sm" name="DES_ERRODESC" id="DES_ERRODESC" maxlength="150">
															<div class="help-block with-errors"></div>
														</div>
													</div>												
													
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
										
										<input type="hidden" name="opcao" id="opcao" value="">
										<input type="hidden" name="LOG_INCLUI" id="LOG_INCLUI" value="S">
										<input type="hidden" name="LOG_EXCLUI" id="LOG_EXCLUI" value="S">
										<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
										<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">		
										
										<div class="push5"></div> 
										
										</form>
	
										<!-- modal -->									
										<div class="modal fade" id="popModalAux" tabindex='-1'>
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
										
										
										<div class="push50"></div>
										
										<div id="div_Ordena"></div>
										
										<div class="col-lg-12">

											<div class="no-more-tables">
										
												<form name="formLista">
												
												<table class="table table-bordered table-striped table-hover table-sortable">
												  <thead>
													<tr>
													  <th width="40"></th>
													  <th>Código</th>
													  <th>Nome</th>
													  <th>Cupom</th>
													  <th>Produto</th>
													  <th>Cod. Interno</th>
													  <th>Cod. Externo</th>
													  <th>Ativo</th>
													  <th>Cup. Obrig.</th>
													  <th>Unid. Aut.</th>
													  <th>Unid. Não Aut.</th>
													</tr>
												  </thead>
												<tbody>
  
												<?php
												
													$sql=" SELECT A.*,
															P.DES_PRODUTO,
															P.COD_PRODUTO,
															P.COD_EXTERNO
															FROM DESCONTOS A
															LEFT join produtocliente P on A.COD_PRODUTO = P.COD_PRODUTO
															WHERE A.COD_EMPRESA = $cod_empresa 
															  order by A.NOM_DESCTKT ";
																							//fnEscreve($sql);
													//fnTestesql(connTemp($cod_empresa,''),$sql);
													$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
                                                                                                      
													$count=0;
												
													while ($qrBuscaProdutosTkt = mysqli_fetch_assoc($arrayQuery))
													  {	
														$count++;
														
														/*
														echo "<pre>";
														print_r($qrBuscaProdutosTkt) ;
														echo "</pre>";
														*/
														
														if ($qrBuscaProdutosTkt['LOG_PRODTKT'] == "S") {
															$mostraLOG_PRODTKT = '<i class="fal fa-check" aria-hidden="true"></i>';	
														}else{ $mostraLOG_PRODTKT = ''; }	
											
														if ($qrBuscaProdutosTkt['LOG_OBGCUPOM'] == "S") {
															$mostraLOG_OBGCUPOM = '<i class="fal fa-check" aria-hidden="true"></i>';	
														}else{ $mostraLOG_OBGCUPOM = ''; }	
											
														if ($qrBuscaProdutosTkt['COD_UNIVEND_AUT'] != "0") {
															$mostraCOD_UNIVEND_AUT = '<i class="fal fa-check" aria-hidden="true"></i>';	
														}else{ $mostraCOD_UNIVEND_AUT = ''; }	
											
														if ($qrBuscaProdutosTkt['COD_UNIVEND_BLK'] != "0") {
															$mostraCOD_UNIVEND_BLK = '<i class="fal fa-check" aria-hidden="true"></i>';	
														}else{ $mostraCOD_UNIVEND_BLK = ''; }	
														
														
														//fnEscreve($qrBuscaProdutosTkt['TEM_IMAGEM']);
														
														echo"
															<tr>
															  <td align='center'><input type='radio' name='radio1' onclick='retornaForm(".$count.")'></th>
															  <td>".$qrBuscaProdutosTkt['COD_DESCTKT']."</td>
															  <td>".$qrBuscaProdutosTkt['NOM_DESCTKT']."</td>
															  <td>".$qrBuscaProdutosTkt['COD_CUPOMTKT']."</td>
															  <td>".$qrBuscaProdutosTkt['DES_PRODUTO']."</td>
															  <td>".$qrBuscaProdutosTkt['COD_PRODUTO']."</td>
															  <td>".$qrBuscaProdutosTkt['COD_EXTERNO']."</td>
															  <td class='text-center'>".$mostraLOG_PRODTKT."</td>
															  <td class='text-center'>".$mostraLOG_OBGCUPOM."</td>
															  <td class='text-center'>".$mostraCOD_UNIVEND_AUT."</td>
															  <td class='text-center'>".$mostraCOD_UNIVEND_BLK."</td>
															</tr>

															<input type='hidden' id='ret_COD_DESCTKT_".$count."' value='".$qrBuscaProdutosTkt['COD_DESCTKT']."'>
															<input type='hidden' id='ret_NOM_DESCTKT_".$count."' value='".$qrBuscaProdutosTkt['NOM_DESCTKT']."'>
															<input type='hidden' id='ret_DAT_INIPTKT_".$count."' value='".fnFormatDateTime($qrBuscaProdutosTkt['DAT_INIPTKT'])."'>
															<input type='hidden' id='ret_DAT_FIMPTKT_".$count."' value='".fnFormatDateTime($qrBuscaProdutosTkt['DAT_FIMPTKT'])."'>
															<input type='hidden' id='ret_PCT_DESCONTO_".$count."' value='".number_format($qrBuscaProdutosTkt['PCT_DESCONTO'],2,",",".")."'>
															<input type='hidden' id='ret_VAL_DESCONTO_".$count."' value='".number_format($qrBuscaProdutosTkt['VAL_DESCONTO'],2,",",".")."'>
															<input type='hidden' id='ret_COD_PERSONA_TKT_".$count."' value='".$qrBuscaProdutosTkt['COD_PERSONA_TKT']."'>
															<input type='hidden' id='ret_COD_UNIVEND_AUT_".$count."' value='".$qrBuscaProdutosTkt['COD_UNIVEND_AUT']."'>
															<input type='hidden' id='ret_COD_UNIVEND_BLK_".$count."' value='".$qrBuscaProdutosTkt['COD_UNIVEND_BLK']."'>
															<input type='hidden' id='ret_COD_CATEGORTKT_".$count."' value='".$qrBuscaProdutosTkt['COD_CATEGORTKT']."'>
															<input type='hidden' id='ret_LOG_PRODTKT_".$count."' value='".$qrBuscaProdutosTkt['LOG_PRODTKT']."'>
															<input type='hidden' id='ret_COD_DESCTKT_".$count."' value='".$qrBuscaProdutosTkt['COD_DESCTKT']."'>
															<input type='hidden' id='ret_DES_MENSGTKT_".$count."' value='".$qrBuscaProdutosTkt['DES_MENSGTKT']."'>
															<input type='hidden' id='ret_DES_ERRODESC_".$count."' value='".$qrBuscaProdutosTkt['DES_ERRODESC']."'>
															
															<input type='hidden' id='ret_COD_CUPOMTKT_".$count."' value='".$qrBuscaProdutosTkt['COD_CUPOMTKT']."'>
															<input type='hidden' id='ret_TIP_APLICA_".$count."' value='".$qrBuscaProdutosTkt['TIP_APLICA']."'>
															<input type='hidden' id='ret_VAL_MAXIUSO_".$count."' value='".fnValor($qrBuscaProdutosTkt['VAL_MAXIUSO'])."'>
															<input type='hidden' id='ret_VAL_MINCOMPRA_".$count."' value='".fnValor($qrBuscaProdutosTkt['VAL_MINCOMPRA'],2)."'>
															<input type='hidden' id='ret_QTD_MINITEM_".$count."' value='".fnValor($qrBuscaProdutosTkt['QTD_MINITEM'],2)."'>
															<input type='hidden' id='ret_QTD_LIMITE_".$count."' value='".fnValor($qrBuscaProdutosTkt['QTD_LIMITE'],0)."'>
															<input type='hidden' id='ret_COD_PRODUTO_".$count."' value='".$qrBuscaProdutosTkt['COD_PRODUTO']."'>
															<input type='hidden' id='ret_DES_PRODUTO_".$count."' value='".$qrBuscaProdutosTkt['DES_PRODUTO']."'>
															<input type='hidden' id='ret_COD_CATEGOR_".$count."' value='".$qrBuscaProdutosTkt['COD_CATEGOR']."'>
															<input type='hidden' id='ret_COD_SUBCATE_".$count."' value='".$qrBuscaProdutosTkt['COD_SUBCATE']."'>
															<input type='hidden' id='ret_COD_FORNECEDOR_".$count."' value='".$qrBuscaProdutosTkt['COD_FORNECEDOR']."'>
															<input type='hidden' id='ret_LOG_OBGCUPOM_".$count."' value='".$qrBuscaProdutosTkt['LOG_OBGCUPOM']."'>
															<input type='hidden' id='ret_LOG_INCLUI_".$count."' value='".$qrBuscaProdutosTkt['LOG_INCLUI']."'>
															<input type='hidden' id='ret_LOG_EXCLUI_".$count."' value='".$qrBuscaProdutosTkt['LOG_EXCLUI']."'>
															
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
			
	<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
	<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />	
	
    <script>
		
        $(document).ready( function() {
			
			//chosen obrigatório
			$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
			$('#formulario').validator();

			
			$('.datePicker').datetimepicker({
				 format: 'DD/MM/YYYY',
				}).on('changeDate', function(e){
					$(this).datetimepicker('hide');
				});
				
			/*	
			$("#DAT_INI_GRP").on("dp.change", function (e) {
				$('#DAT_FIM_GRP').data("DateTimePicker").minDate(e.date);
			});
			
			$("#DAT_FIM_GRP").on("dp.change", function (e) {
				$('#DAT_INI_GRP').data("DateTimePicker").maxDate(e.date);
			});
			*/

			$("#COD_UNIVEND_AUT").change(function(){
				if($(this).val() != ""){
					$("#COD_UNIVEND_BLK").prop("required",false);
				}else{
					$("#COD_UNIVEND_BLK").prop("required",true);
				}
				$('#formulario').validator('validate');
			});

			$("#COD_UNIVEND_BLK").change(function(){
				if($(this).val() != ""){
					$("#COD_UNIVEND_AUT").prop("required",false);
				}else{
					$("#COD_UNIVEND_AUT").prop("required",true);
				}
				$('#formulario').validator('validate');
			});

			
        });
		
		
		// ajax
		$("#COD_CATEGOR").change(function () {
			var codBusca = $("#COD_CATEGOR").val();
			var codBusca3 = $("#COD_EMPRESA").val();
			buscaSubCat(codBusca,0,codBusca3);
		});

		function buscaSubCat(idCat,idSub,idEmp) {
			$.ajax({
				type: "GET",
				url: "ajxBuscaSubGrupo.php",
				data: { ajx1:idCat,ajx2:idSub,ajx3:idEmp},
                                
				beforeSend:function(){
					$('#divId_sub').html('<div class="loading" style="width: 100%;"></div>');
				},
				success:function(data){
					$("#divId_sub").html(data);
					console.log(data);
				},
				error:function(){
					$('#divId_sub').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
				}
			});		
		}		
				
		function retornaForm(index){			
			$("#formulario #COD_DESCTKT").val($("#ret_COD_DESCTKT_"+index).val());
			$("#formulario #NOM_DESCTKT").val($("#ret_NOM_DESCTKT_"+index).val());
			if ($("#ret_LOG_PRODTKT_"+index).val() == 'S'){$('#formulario #LOG_PRODTKT').prop('checked', true);} 
			else {$('#formulario #LOG_PRODTKT').prop('checked', false);}			
			$("#formulario #DAT_INIPTKT").val($("#ret_DAT_INIPTKT_"+index).val());
			$("#formulario #DAT_FIMPTKT").val($("#ret_DAT_FIMPTKT_"+index).val());
			$("#formulario #PCT_DESCONTO").val($("#ret_PCT_DESCONTO_"+index).val());
			$("#formulario #VAL_DESCONTO").val($("#ret_VAL_DESCONTO_"+index).val());
			
			//retorno combo personas
			$("#formulario #COD_PERSONA_TKT").val('').trigger("chosen:updated");
			if ($("#ret_COD_PERSONA_TKT_"+index).val() != "0" ){				
				var sistemasPersona = $("#ret_COD_PERSONA_TKT_"+index).val();				
				var sistemasPersonaArr = sistemasPersona.split(',');				
				//opções multiplas
				for (var i = 0; i < sistemasPersonaArr.length; i++) {
				  $("#formulario #COD_PERSONA_TKT option[value=" + sistemasPersonaArr[i] + "]").prop("selected", "true");				  
				}
				$("#formulario #COD_PERSONA_TKT").trigger("chosen:updated");    
			} else {$("#formulario #COD_PERSONA_TKT").val('').trigger("chosen:updated");}
			
			//retorno lojas autorizadas
			$("#formulario #COD_UNIVEND_AUT").val('').trigger("chosen:updated");
			if ($("#COD_UNIVEND_AUT"+index).val() != "0" ){				
				var sistemasUnidadeAut = $("#ret_COD_UNIVEND_AUT_"+index).val();				
				var sistemasUnidadeAutArr = sistemasUnidadeAut.split(',');				
				//opções multiplas
				for (var i = 0; i < sistemasUnidadeAutArr.length; i++) {
				  $("#formulario #COD_UNIVEND_AUT option[value=" + sistemasUnidadeAutArr[i] + "]").prop("selected", "true");				  
				}
				$("#formulario #COD_UNIVEND_AUT").trigger("chosen:updated");    
			} else {$("#formulario #COD_UNIVEND_AUT").val('').trigger("chosen:updated");}
			
			//retorno lojas não autorizadas
			$("#formulario #COD_UNIVEND_BLK").val('').trigger("chosen:updated");
			if ($("#COD_UNIVEND_BLK"+index).val() != "0" ){				
				var sistemasUnidadeNAut = $("#ret_COD_UNIVEND_BLK_"+index).val();				
				var sistemasUnidadeNAutArr = sistemasUnidadeNAut.split(',');				
				//opções multiplas
				for (var i = 0; i < sistemasUnidadeNAutArr.length; i++) {
				  $("#formulario #COD_UNIVEND_BLK option[value=" + sistemasUnidadeNAutArr[i] + "]").prop("selected", "true");				  
				}
				$("#formulario #COD_UNIVEND_BLK").trigger("chosen:updated");    
			} else {$("#formulario #COD_UNIVEND_BLK").val('').trigger("chosen:updated");}

			if($("#COD_UNIVEND_AUT").val() != ""){
				$("#COD_UNIVEND_BLK").prop("required",false);
			}else{
				$("#COD_UNIVEND_BLK").prop("required",true);
			}

			if($("#COD_UNIVEND_BLK").val() != ""){
				$("#COD_UNIVEND_AUT").prop("required",false);
			}else{
				$("#COD_UNIVEND_AUT").prop("required",true);
			}
			
			$("#formulario #COD_DESCTKT").val($("#ret_COD_DESCTKT_"+index).val()).trigger("chosen:updated");
			if ($("#ret_LOG_OFERTAS_"+index).val() == 'S'){$('#formulario #LOG_OFERTAS').prop('checked', true);} 
			else {$('#formulario #LOG_OFERTAS').prop('checked', false);}			
			
			$("#formulario #DES_MENSGTKT").val($("#ret_DES_MENSGTKT_"+index).val());
			$("#formulario #DES_ERRODESC").val($("#ret_DES_ERRODESC_"+index).val());
			
			$("#formulario #COD_CUPOMTKT").val($("#ret_COD_CUPOMTKT_"+index).val());
			
			if ($("#ret_TIP_APLICA_"+index).val() == 'C'){$('#formulario #TIP_APLICA1').prop('checked', true);} 
			else {$('#formulario #TIP_APLICA2').prop('checked', true);}			
			
			$("#formulario #VAL_MAXIUSO").val($("#ret_VAL_MAXIUSO_"+index).val());
			$("#formulario #VAL_MINCOMPRA").val($("#ret_VAL_MINCOMPRA_"+index).val());
			$("#formulario #QTD_MINITEM").val($("#ret_QTD_MINITEM_"+index).val());
			$("#formulario #QTD_LIMITE").val($("#ret_QTD_LIMITE_"+index).val());
			
			$("#formulario #COD_PRODUTO").val($("#ret_COD_PRODUTO_"+index).val());
			$("#formulario #DES_PRODUTO").val($("#ret_DES_PRODUTO_"+index).val());
			
			$("#formulario #COD_CATEGOR").val($("#ret_COD_CATEGOR_"+index).val()).trigger("chosen:updated");
			
			var codCat = $("#ret_COD_CATEGOR_"+index).val();
			var codSub = $("#ret_COD_SUBCATE_"+index).val();
			buscaSubCat(codCat,codSub,<?php echo $cod_empresa; ?>);			
			
			$("#formulario #COD_FORNECEDOR").val($("#ret_COD_FORNECEDOR_"+index).val()).trigger("chosen:updated");			

			if ($("#ret_LOG_OBGCUPOM_"+index).val() == 'S'){$('#formulario #LOG_OBGCUPOM').prop('checked', true);} 
			else {$('#formulario #LOG_OBGCUPOM').prop('checked', false);}
			
			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');						
		}
		
	</script>	
   