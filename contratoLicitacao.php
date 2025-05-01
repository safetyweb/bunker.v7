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
			
			$cod_contrat = fnLimpaCampoZero($_REQUEST['COD_CONTRAT']);
			$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
			$cod_conveni = fnLimpaCampoZero($_REQUEST['COD_CONVENI']);
			$cod_objeto = fnLimpaCampoZero($_REQUEST['COD_OBJETO']);
			$cod_proposta = fnLimpaCampoZero($_REQUEST['COD_PROPOSTA']);
			$num_contador = fnLimpaCampo($_REQUEST['NUM_CONTADOR']);
							
			$sqlLicita = "SELECT PPT.COD_LICITAC, PPT.COD_CLIENTE 
							FROM PROPOSTA PPT 
							WHERE PPT.COD_PROPOSTA = $cod_proposta ";

			$arrayLicita = mysqli_query(connTemp($cod_empresa,''),$sqlLicita);
			$qrLicita = mysqli_fetch_assoc($arrayLicita);

			$cod_licitac = $qrLicita['COD_LICITAC'];			
			$cod_cliente = $qrLicita['COD_CLIENTE'];
			
			//fnEscreve($sqlLicita);
			//fnEscreve($cod_licitac);
			//fnEscreve($cod_proposta);
			//fnEscreve($cod_objeto);

			$nro_contrat = fnLimpaCampo($_REQUEST['NRO_CONTRAT']);
			$des_ano = fnLimpaCampo($_REQUEST['DES_ANO']);
			$dat_ini = fnLimpaCampo($_REQUEST['DAT_INI']);
			$dat_fim = fnLimpaCampo($_REQUEST['DAT_FIM']);
			$dat_assinat = fnLimpaCampo($_REQUEST['DAT_ASSINAT']);
			$dat_ordem = fnLimpaCampo($_REQUEST['DAT_ORDEM']);
			$val_valor = fnLimpaCampo($_REQUEST['VAL_VALOR']);
			$val_conveni = fnLimpaCampo($_REQUEST['VAL_CONVENI']);
			$val_contpar = fnLimpaCampo($_REQUEST['VAL_CONTPAR']);
			$des_tpcontrat = fnLimpaCampo($_REQUEST['DES_TPCONTRAT']);
			
			//chuncho - Ricardo fdp
			$val_valor = fnValorSql($val_conveni) + fnValorSql($val_contpar); 
			
			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
			
			$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
                      
			if ($opcao != ''){				
				
				//mensagem de retorno
				switch ($opcao)
				{
					case 'CAD':

					$sql = "INSERT INTO CONTRATO(
							COD_EMPRESA,
							COD_OBJETO,
							COD_CONVENI,
							COD_CLIENTE,
							COD_PROPOSTA,
							COD_LICITAC,
							NRO_CONTRAT,
							DES_ANO,
							DAT_INI,
							DAT_FIM,
							DAT_ASSINAT,
							DAT_ORDEM,
							VAL_VALOR,
							VAL_CONVENI,
							VAL_CONTPAR,
							DES_TPCONTRAT
							) VALUES(
							$cod_empresa,
							$cod_objeto,
							$cod_conveni,
							$cod_cliente,
							$cod_proposta,
							$cod_licitac,
							$nro_contrat,
							'$des_ano',
							'".fnDataSql($dat_ini)."',
							'".fnDataSql($dat_fim)."',
							'".fnDataSql($dat_assinat)."',
							'".fnDataSql($dat_ordem)."',
							'".$val_valor."',
							'".fnValorSql($val_conveni)."',
							'".fnValorSql($val_contpar)."',
							'$des_tpcontrat'
							)";
						
						//fnEscreve($sql);
		                mysqli_query(connTemp($cod_empresa,''),$sql);

		                $sqlCod = "SELECT MAX(COD_CONTRAT) COD_CONTRAT_LIC FROM CONTRATO WHERE COD_EMPRESA = $cod_empresa AND COD_CONVENI = $cod_conveni";
						$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sqlCod);
						$qrCod = mysqli_fetch_assoc($arrayQuery);
						$cod_contrat = $qrCod[COD_CONTRAT_LIC];

						$sqlArquivos = "SELECT 1 FROM ANEXO_CONVENIO WHERE COD_EMPRESA = $cod_empresa AND COD_PROVISORIO = $num_contador";
						$arrayCont = mysqli_query(connTemp($cod_empresa,''),$sqlArquivos);

						if(mysqli_num_rows($arrayCont) > 0){
							$sqlUpd = "UPDATE ANEXO_CONVENIO SET COD_CONTRAT_LIC = $cod_contrat, LOG_STATUS = 'S' WHERE COD_EMPRESA = $cod_empresa AND COD_PROVISORIO = $num_contador";
							mysqli_query(connTemp($cod_empresa,''),$sqlUpd);
						}

						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";	
						break;
					case 'ALT':

						$sql = "UPDATE CONTRATO SET
							COD_EMPRESA=$cod_empresa,
							NRO_CONTRAT=$nro_contrat,
							COD_OBJETO=$cod_objeto,
							COD_CONVENI=$cod_conveni,
							COD_CLIENTE=$cod_cliente,
							COD_PROPOSTA=$cod_proposta,
							COD_LICITAC=$cod_licitac,
							DES_ANO='$des_ano',
							DAT_INI='".fnDataSql($dat_ini)."',
							DAT_FIM='".fnDataSql($dat_fim)."',
							DAT_ASSINAT='".fnDataSql($dat_assinat)."',
							DAT_ORDEM='".fnDataSql($dat_ordem)."',
							VAL_VALOR='".$val_valor."',
							VAL_CONVENI='".fnValorSql($val_conveni)."',
							VAL_CONTPAR='".fnValorSql($val_contpar)."',
							DES_TPCONTRAT='$des_tpcontrat'
							WHERE COD_CONTRAT = $cod_contrat";
						
						//fnEscreve($sql);
		                mysqli_query(connTemp($cod_empresa,''),$sql);

		                $sqlUpd = "UPDATE ANEXO_CONVENIO SET LOG_STATUS = 'S' WHERE COD_EMPRESA = $cod_empresa AND COD_CONTRAT_LIC = $cod_contrat AND LOG_STATUS = 'N'";
						mysqli_query(connTemp($cod_empresa,''),$sqlUpd);

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

	if(isset($_GET['idC'])){
		if (is_numeric(fnLimpacampo(fnDecode($_GET['idC'])))){
		
			//busca dados do convênio
			$cod_conveni = fnDecode($_GET['idC']);
			
			//busca dados do convênio
			$cod_conveni = fnDecode($_GET['idC']);	
			//$sql = "SELECT NOM_CONVENI, COD_OBJETO FROM CONVENIO WHERE COD_CONVENI = ".$cod_conveni;	
			$sql = "SELECT A.NOM_CONVENI,B.COD_OBJETO FROM CONVENIO A,LICITACAO_OBJETO B
					WHERE A.COD_CONVENI=B.COD_CONVENI AND
						  A.COD_CONVENI = ".$cod_conveni;	
			
			//fnEscreve($sql);
			$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
			$qrBuscaTemplate = mysqli_fetch_assoc($arrayQuery);
				
			if (isset($qrBuscaTemplate)){
				$nom_conveni = $qrBuscaTemplate['NOM_CONVENI'];
				$cod_objeto = $qrBuscaTemplate['COD_OBJETO'];
			
			}			
			
			
		}
	}
	      
	//fnMostraForm();

	$tp_cont = 'Anexo do Contrato LIC';
	$tp_anexo = 'COD_CONTRAT_LIC';
	$cod_tpanexo = 'COD_CONTRAT';
	$cod_busca = $cod_contrat;
	
	$sqlUpdtCont = "DELETE FROM ANEXO_CONVENIO WHERE COD_EMPRESA = $cod_empresa AND COD_CONTRAT_LIC != 0 AND LOG_STATUS = 'N'";
	mysqli_query(connTemp($cod_empresa,''),$sqlUpdtCont);
	
	$sqlUpdtCont = "UPDATE CONTADOR SET NUM_CONTADOR = (NUM_CONTADOR+1) WHERE DES_CONTADOR = '$tp_cont'";
	mysqli_query(connTemp($cod_empresa,''),$sqlUpdtCont);

	$sqlCont = "SELECT NUM_CONTADOR FROM CONTADOR WHERE DES_CONTADOR = '$tp_cont'";
	$qrCont = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sqlCont));
	$num_contador = $qrCont['NUM_CONTADOR'];
	
?>
	
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
				
					<div class="tabbable-line">
		
						<ul class="nav nav-tabs ">
							<li>
								<a href="action.do?mod=<?php echo fnEncode(1344)."&id=".fnEncode($cod_empresa)."&idC=".fnEncode($cod_conveni); ?>">
								<span class="fal fa-arrow-circle-left fa-2x"></span></a>
							</li>
						</ul>
					</div>					
					
					<div class="push30"></div> 			
					
						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
																
						<fieldset>
							<legend>Dados Gerais</legend> 
						
								<div class="row">
						
									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Código</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_CONTRAT" id="COD_CONTRAT" value="">
										</div>
										<div class="help-block with-errors"></div>
									</div>       
						
									<div class="col-md-4">
										<div class="form-group">
											<label for="inputName" class="control-label required">Convênio</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_CONVENI" id="NOM_CONVENI" value="<?php echo $nom_conveni ?>" required>
										</div>														
									</div>         								
									
									<div class="col-md-4">
										<div class="form-group">
											<label for="inputName" class="control-label required">Proposta/Objeto</label>
												<select data-placeholder="Selecione uma empresa" name="COD_PROPOSTA" id="COD_PROPOSTA" class="chosen-select-deselect requiredChk" style="width:100%;" required onchange="retProposta(this)">
													<option value=""></option>
													   <?php 
														$sql = "SELECT PPT.COD_OBJETO, PPT.COD_PROPOSTA, PPT.VAL_VALOR AS VAL_PROPOSTA, CL.NOM_CLIENTE, LCO.NOM_OBJETO 
														FROM PROPOSTA PPT
														LEFT JOIN LICITACAO_OBJETO LCO ON LCO.COD_OBJETO = PPT.COD_OBJETO
														LEFT JOIN CLIENTES CL ON CL.COD_CLIENTE = PPT.COD_CLIENTE
														WHERE PPT.COD_EMPRESA = $cod_empresa 
														AND PPT.COD_CONVENI = $cod_conveni 
														AND PPT.LOG_STATUS = 'S' ORDER BY CL.NOM_CLIENTE
														";

														echo($sql);
														
														$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
													
														while ($qrLista = mysqli_fetch_assoc($arrayQuery))
														 {														
															?>
																  <option value='<?=$qrLista['COD_PROPOSTA']?>'><?=$qrLista['NOM_CLIENTE']." / ".$qrLista['NOM_OBJETO']?></option>
															<?php 
														}											
													?>
												</select>
											<div class="help-block with-errors"></div>
											<script>
												function retProposta(obj){
													cod_proposta = $(obj).val();
													//alert(cod_proposta);
													cod_empresa = <?=$cod_empresa?>;
													$.ajax({
														method: 'POST',
														url: 'ajxValorProposta.php',
														data: {COD_PROPOSTA:cod_proposta,COD_EMPRESA:cod_empresa},
														success:function(data){
															$('#VAL_PROPOSTA').val(data);
														}
													});
												}
											</script>
										</div>
									</div>

									<?php //fnEscreve($sql); ?>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Ano</label>
											<input type="text" class="form-control input-sm" name="DES_ANO" id="DES_ANO" value="" maxlength="4" required>
										</div>
										<div class="help-block with-errors"></div>
									</div>

								</div>

								<div class="row"> 

									<div class="col-md-4">
										<div class="form-group">
											<label for="inputName" class="control-label required">Número Contrato</label>
											<input type="text" class="form-control input-sm int" name="NRO_CONTRAT" id="NRO_CONTRAT" value="" data-mask="##0" data-mask-reverse="true" maxlength="11" required>
										</div>
										<div class="help-block with-errors"></div>
									</div>
									
									<div class="col-md-4">
										<div class="form-group">
											<label for="inputName" class="control-label required">Data Inicio Vigência</label>
											<div class="input-group date datePicker" id="DAT_INI_GRP">
												<input type='text' class="form-control input-sm data" name="DAT_INI" id="DAT_INI" data-error="Data inválida. Preencha este campo." value="<?=$dat_ini?>" <?=$leitura?> required/>
												<span class="input-group-addon">
													<span class="glyphicon glyphicon-calendar"></span>
												</span>
											</div>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-4">
										<div class="form-group">
											<label for="inputName" class="control-label required">Data Término Vigência</label>
											<div class="input-group date datePicker" id="DAT_FIM_GRP">
												<input type='text' class="form-control input-sm data" name="DAT_FIM" id="DAT_FIM" data-error="Data inválida/menor que inicial. Preencha este campo." value="<?=$dat_fim?>" <?=$leitura?> required/>
												<span class="input-group-addon">
													<span class="glyphicon glyphicon-calendar"></span>
												</span>
											</div>
											<div class="help-block with-errors"></div>
										</div>
									</div>  
						
								</div>

								<div class="row">

									<div class="col-md-4">
										<div class="form-group">
											<label for="inputName" class="control-label required">Data de Assinatura</label>
											<div class="input-group date datePicker" id="DAT_ASSINAT_GRP">
												<input type='text' class="form-control input-sm data" name="DAT_ASSINAT" id="DAT_ASSINAT" data-error="Data inválida/fora do intervalo. Preencha este campo." value="<?=$dat_assinat?>" <?=$leitura?> required/>
												<span class="input-group-addon">
													<span class="glyphicon glyphicon-calendar"></span>
												</span>
											</div>
											<div class="help-block with-errors"></div>
										</div>
									</div>  

									<div class="col-md-4">
										<div class="form-group">
											<label for="inputName" class="control-label required">Data Ordem de Serviço</label>
											<div class="input-group date datePicker" id="DAT_ORDEM_GRP">
												<input type='text' class="form-control input-sm data" name="DAT_ORDEM" id="DAT_ORDEM" data-error="Data inválida/menor que inicial. Preencha este campo." value="<?=$dat_ordem?>" <?=$leitura?> required/>
												<span class="input-group-addon">
													<span class="glyphicon glyphicon-calendar"></span>
												</span>
											</div>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-4">
										<div class="form-group">
											<label for="inputName" class="control-label required">Valor da Proposta</label>
											<input type="text" class="form-control text-right input-sm money leituraOff" name="VAL_PROPOSTA" id="VAL_PROPOSTA" value="" readonly data-mask="##0" data-mask-reverse="true" maxlength="11" required>
										</div>
										<div class="help-block with-errors"></div>
									</div>  
							
								</div>
								
								<div class="push10"></div>

								<?php include "uploadConvenio.php"; ?>
								
								<div class="push10"></div>
								
						</fieldset>

						<div class="push10"></div>

						<fieldset>
							<legend>Valores da Licitação</legend>

							<div class="row">
								
								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Valor Efetivo (Participação Global)</label>
										<input type="text" class="form-control input-sm text-right money" name="VAL_CONVENI" id="VAL_CONVENI" value="000" data-mask="##0" data-mask-reverse="true" maxlength="18">
									</div>
									<div class="help-block with-errors"></div>
								</div>

								<div class="col-md-1 text-center">
								<div class="push20"></div>
								<span class="f21">+</span>
								</div>
								
								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Valor de Contrapartida</label>
										<input type="text" class="form-control input-sm text-right money" name="VAL_CONTPAR" id="VAL_CONTPAR" value="000" data-mask="##0" data-mask-reverse="true" maxlength="18">
									</div>
									<div class="help-block with-errors"></div>
								</div>
								
								<div class="col-md-1 text-center">
								<div class="push20"></div>
								<span class="f21">=</span>
								</div>
								
								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Valor do Convênio</label>
										<input type="text" class="form-control input-sm text-right money leituraOff" name="VAL_VALOR" id="VAL_VALOR" value="000" readonly data-mask="##0" data-mask-reverse="true" maxlength="28" required>
									</div>
									<div class="help-block with-errors"></div>
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
						
						<input type="hidden" name="DES_TPCONTRAT" id="DES_TPCONTRAT" value="LIC">
						<input type="hidden" name="COD_OBJETO" id="COD_OBJETO" value="<?php echo $cod_objeto ?>">
						<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">
						<input type="hidden" name="COD_CONVENI" id="COD_CONVENI" value="<?=$cod_conveni?>">
						<input type="hidden" name="COD_OBJETOANEXO" id="COD_OBJETOANEXO" value="">
						<input type="hidden" name="NUM_CONTADOR" id="NUM_CONTADOR" value="<?php echo $num_contador; ?>" />
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
										  <th>Contrato</th>
										  <th>Objeto</th>
										  <th>Favorecido</th>
										  <th class="text-right">Valor Efetivo</th>
										  <th class="text-right">Valor de Contrapartida</th>
										  <th class="text-right">Valor do Convênio</th>
										</tr>
									  </thead>

									<tbody>
									
									<?php 
										$sql = "SELECT CTT.*, LCO.NOM_OBJETO, CL.NOM_CLIENTE, PPT.VAL_VALOR AS VAL_PROPOSTA FROM CONTRATO CTT
												LEFT JOIN PROPOSTA PPT ON PPT.COD_OBJETO = CTT.COD_OBJETO 
												AND CTT.COD_PROPOSTA=PPT.COD_PROPOSTA
												LEFT JOIN LICITACAO_OBJETO LCO ON LCO.COD_OBJETO = CTT.COD_OBJETO
												LEFT JOIN CLIENTES CL ON CTT.COD_CLIENTE = CL.COD_CLIENTE
												WHERE CTT.DES_TPCONTRAT = 'LIC' AND CTT.COD_EMPRESA = $cod_empresa AND CTT.COD_CONVENI = $cod_conveni";

												//fnEscreve($sql);
												
										
										$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
										
										$count=0;
										$val_total = 0;
										while ($qrContrat = mysqli_fetch_assoc($arrayQuery))
										  {														  
											$count++;
											
											echo"
												<tr>
												  <td><input type='radio' name='radio1' onclick='retornaForm(".$count.")'></th>
												  <td>".$qrContrat['COD_CONTRAT']."</td>
												  <td>".$qrContrat['NRO_CONTRAT']."</td>
												  <td>".$qrContrat['NOM_OBJETO']."</td>
												  <td>".$qrContrat['NOM_CLIENTE']."</td>
												  <td class='text-right'>".fnValor($qrContrat['VAL_CONVENI'],2)."</td>
												  <td class='text-right'>".fnValor($qrContrat['VAL_CONTPAR'],2)."</td>
												  <td class='text-right'>".fnValor($qrContrat['VAL_VALOR'],2)."</td>

												</tr>
												
												<input type='hidden' id='ret_COD_CONTRAT_".$count."' value='".$qrContrat['COD_CONTRAT']."'>
												<input type='hidden' id='ret_COD_EMPRESA_".$count."' value='".$qrContrat['COD_EMPRESA']."'>
												<input type='hidden' id='ret_COD_OBJETO_".$count."' value='".$qrContrat['COD_OBJETO']."'>
												<input type='hidden' id='ret_COD_CONVENI_".$count."' value='".$qrContrat['COD_CONVENI']."'>
												<input type='hidden' id='ret_COD_CLIENTE_".$count."' value='".$qrContrat['COD_CLIENTE']."'>
												<input type='hidden' id='ret_COD_PROPOSTA_".$count."' value='".$qrContrat['COD_PROPOSTA']."'>
												<input type='hidden' id='ret_COD_LICITAC_".$count."' value='".$qrContrat['COD_LICITAC']."'>
												<input type='hidden' id='ret_NRO_CONTRAT_".$count."' value='".$qrContrat['NRO_CONTRAT']."'>
												<input type='hidden' id='ret_DES_ANO_".$count."' value='".$qrContrat['DES_ANO']."'>
												<input type='hidden' id='ret_DAT_INI_".$count."' value='".fnDataShort($qrContrat['DAT_INI'])."'>
												<input type='hidden' id='ret_DAT_FIM_".$count."' value='".fnDataShort($qrContrat['DAT_FIM'])."'>
												<input type='hidden' id='ret_DAT_ASSINAT_".$count."' value='".fnDataShort($qrContrat['DAT_ASSINAT'])."'>
												<input type='hidden' id='ret_DAT_ORDEM_".$count."' value='".fnDataShort($qrContrat['DAT_ORDEM'])."'>
												<input type='hidden' id='ret_VAL_VALOR_".$count."' value='".fnValor($qrContrat['VAL_VALOR'],2)."'>
												<input type='hidden' id='ret_VAL_PROPOSTA_".$count."' value='".fnValor($qrContrat['VAL_PROPOSTA'],2)."'>
												<input type='hidden' id='ret_VAL_CONVENI_".$count."' value='".fnValor($qrContrat['VAL_CONVENI'],2)."'>
												<input type='hidden' id='ret_VAL_CONTPAR_".$count."' value='".fnValor($qrContrat['VAL_CONTPAR'],2)."'>
												<input type='hidden' id='ret_DES_TPCONTRAT_".$count."' value='".$qrContrat['DES_TPCONTRAT']."'>
												
												";
												$val_total+= $qrContrat['VAL_VALOR'];
												//fnEscreve($val_total);
											  }												  
									?>

									</tbody>

									<tfoot>
										<tr>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td class="text-right"><b><?=fnValor($val_total,2);?></b></td>
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

$(document).ready(function(){

    $('.upload').prop('disabled',true);

    $('.datePicker').datetimepicker({
    format: 'DD/MM/YYYY',
    //maxDate : 'now',
}).on('changeDate', function(e){
   $(this).datetimepicker('hide');
});

$("#DAT_INI_GRP").data("DateTimePicker").defaultDate(false);
        $('#DAT_FIM_GRP').data("DateTimePicker").defaultDate(false);
        $('#DAT_ASSINAT_GRP').data("DateTimePicker").defaultDate(false);
     


$('#DAT_ASSINAT_GRP').on("dp.error", function (e) {
       $('#DAT_ASSINAT_GRP').data("DateTimePicker").date(null);
});
$('#DAT_ORDEM_GRP').on("dp.error", function (e) {
       $('#DAT_ORDEM_GRP').data("DateTimePicker").date(null);
});

$("#DAT_INI_GRP").on("dp.change", function (e) {
    $('#DAT_FIM_GRP, #DAT_ASSINAT_GRP, #DAT_ORDEM_GRP').data("DateTimePicker").minDate(e.date).date(null);
});
$("#DAT_FIM_GRP").on("dp.change", function (e) {
    $('#DAT_ASSINAT_GRP, #DAT_ORDEM_GRP').data("DateTimePicker").maxDate(e.date).date(null);
});

    //chosen obrigatório
    $.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
    $('#formulario').validator();

    $('#VAL_CONTPAR,#VAL_CONVENI,#VAL_VALOR').change(function(){
    	
        if($('#VAL_CONTPAR').val() != ''){
                val_contpar = $('#VAL_CONTPAR').cleanVal();
        }else{
                val_contpar = 0;
        }

        if($('#VAL_CONVENI').val() != ''){
                val_conveni = $('#VAL_CONVENI').cleanVal();
        }else{
                val_conveni = 0;
        }

	    total = Number(val_contpar)+Number(val_conveni);

	    $('#VAL_VALOR').val(total).mask('000.000.000.000.000,00', {reverse: true});
    });

});	

        function retornaForm(index){
                $("#formulario #COD_CONTRAT").val($("#ret_COD_CONTRAT_"+index).val());
                $("#formulario #COD_OBJETOANEXO").val($("#ret_COD_CONTRAT_"+index).val());
                $("#formulario #COD_EMPRESA").val($("#ret_COD_EMPRESA_"+index).val());
                $("#formulario #COD_CONVENI").val($("#ret_COD_CONVENI_"+index).val());
                $("#formulario #COD_CLIENTE").val($("#ret_COD_CLIENTE_"+index).val());
                $("#formulario #COD_OBJETO").val($("#ret_COD_OBJETO_"+index).val());
                $("#formulario #COD_PROPOSTA").val($("#ret_COD_PROPOSTA_"+index).val()).trigger("chosen:updated");
                $("#formulario #NRO_CONTRAT").val($("#ret_NRO_CONTRAT_"+index).val());
                $("#formulario #DES_ANO").val($("#ret_DES_ANO_"+index).val());
                $("#formulario #DAT_INI").val($("#ret_DAT_INI_"+index).val());
                $("#formulario #DAT_FIM").val($("#ret_DAT_FIM_"+index).val());
                $("#formulario #DAT_ASSINAT").val($("#ret_DAT_ASSINAT_"+index).val());
                $("#formulario #DAT_ORDEM").val($("#ret_DAT_ORDEM_"+index).val());
                $("#formulario #VAL_VALOR").val($("#ret_VAL_VALOR_"+index).val());
                $("#formulario #VAL_PROPOSTA").val($("#ret_VAL_PROPOSTA_"+index).val());
                $("#formulario #VAL_CONVENI").val($("#ret_VAL_CONVENI_"+index).val());
                $("#formulario #VAL_CONTPAR").val($("#ret_VAL_CONTPAR_"+index).val());
                <?php if ($popUp != "true"){  ?>
                        $("#formulario #DES_TPCONTRAT").val($("#ret_DES_TPCONTRAT_"+index).val()).trigger("chosen:updated");
                <?php }else{
                ?>
                        $("#formulario #DES_TPCONTRAT").val($("#ret_DES_TPCONTRAT_"+index).val());
                <?php 
                } ?>
                // alert($("#ret_DES_TPCONTRAT_"+index).val());
                $('.upload').prop('disabled',false).removeAttr('disabled');

                $('#formulario').validator('validate');			
                $("#formulario #hHabilitado").val('S');

                refreshUpload();		
        }

</script>

<?php include 'jsUploadConvenio.php'; ?>