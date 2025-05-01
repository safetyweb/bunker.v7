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
			
			$cod_aditivo = fnLimpaCampoZero($_REQUEST['COD_ADITIVO']);
			
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
			$num_contador = fnLimpaCampo($_REQUEST['NUM_CONTADOR']);
			$cod_provisorio = fnLimpaCampo($_REQUEST['COD_PROVISORIO']);
			
			$val_valor = fnValorSql($val_conveni) + fnValorSql($val_contpar) ; 
			
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
							COD_CONVENI,
							COD_CLIENTE,
							COD_ADITIVO,
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
							$cod_conveni,
							0,
							$cod_aditivo,
							0,
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

						$sqlCod = "SELECT MAX(COD_CONTRAT) COD_CONTRAT_CON FROM CONTRATO WHERE COD_EMPRESA = $cod_empresa AND COD_CONVENI = $cod_conveni";
						$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sqlCod);
						$qrCod = mysqli_fetch_assoc($arrayQuery);
						$cod_contrat = $qrCod[COD_CONTRAT_CON];

						$sqlArquivos = "SELECT 1 FROM ANEXO_CONVENIO WHERE COD_EMPRESA = $cod_empresa AND COD_PROVISORIO = $num_contador";
						$arrayCont = mysqli_query(connTemp($cod_empresa,''),$sqlArquivos);

						if(mysqli_num_rows($arrayCont) > 0){
							$sqlUpd = "UPDATE ANEXO_CONVENIO SET COD_CONTRAT_CON = $cod_contrat, LOG_STATUS = 'S' WHERE COD_EMPRESA = $cod_empresa AND COD_PROVISORIO = $num_contador";
							mysqli_query(connTemp($cod_empresa,''),$sqlUpd);
						}

						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";	
						break;
					case 'ALT':

						$sql = "UPDATE CONTRATO SET
								COD_EMPRESA=$cod_empresa,
								NRO_CONTRAT=$nro_contrat,
								COD_CONVENI=$cod_conveni,
								COD_CLIENTE=$cod_cliente,
								COD_PROPOSTA=$cod_proposta,
								COD_LICITAC=$cod_licitac,
								DES_ANO='$des_ano',
								DAT_INI=".fnDataSqlNull($dat_ini).",
								DAT_FIM=".fnDataSqlNull($dat_fim).",
								DAT_ASSINAT=".fnDataSqlNull($dat_assinat).",
								DAT_ORDEM=".fnDataSqlNull($dat_ordem).",
								VAL_VALOR='".$val_valor."',
								VAL_CONVENI='".fnValorSql($val_conveni)."',
								VAL_CONTPAR='".fnValorSql($val_contpar)."',
								DES_TPCONTRAT='$des_tpcontrat'
								WHERE COD_CONTRAT = $cod_contrat";
							
						//fnEscreve($sql);
		                mysqli_query(connTemp($cod_empresa,''),$sql);

		                $sqlUpd = "UPDATE ANEXO_CONVENIO SET LOG_STATUS = 'S' WHERE COD_EMPRESA = $cod_empresa AND COD_CONTRAT_CON = $cod_contrat AND LOG_STATUS = 'N'";
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
			$sql = "SELECT NOM_CONVENI FROM CONVENIO WHERE COD_CONVENI = ".$cod_conveni;	
			
			//fnEscreve($sql);
			$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
			$qrBuscaTemplate = mysqli_fetch_assoc($arrayQuery);
				
			if (isset($qrBuscaTemplate)){
				$nom_conveni = $qrBuscaTemplate['NOM_CONVENI'];
			
			}			
			
		}
	}
	      
	//fnMostraForm();
	//fnEscreve($cod_empresa);

	$tp_cont = 'Anexo do Contrato CON';
	$tp_anexo = 'COD_CONTRAT_CON';
	$cod_tpanexo = 'COD_CONTRAT';
	$cod_busca = $cod_contrat;
	
	$sqlUpdtCont = "DELETE FROM ANEXO_CONVENIO WHERE COD_EMPRESA = $cod_empresa AND COD_CONTRAT_CON != 0 AND LOG_STATUS = 'N'";
	mysqli_query(connTemp($cod_empresa,''),$sqlUpdtCont);

	$sqlUpdtCont = "UPDATE CONTADOR SET NUM_CONTADOR = (NUM_CONTADOR+1) WHERE DES_CONTADOR = '$tp_cont'";
	mysqli_query(connTemp($cod_empresa,''),$sqlUpdtCont);

	$sqlCont = "SELECT NUM_CONTADOR FROM CONTADOR WHERE DES_CONTADOR = '$tp_cont'";
	$qrCont = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sqlCont));
	$num_contador = $qrCont['NUM_CONTADOR'];

	// fnEscreve($num_contador);

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
				
					<?php 
					
					if (fnDecode($_GET['mod']) == 1550){
					?>
						<div class="tabbable-line">
							<ul class="nav nav-tabs ">
								<li>
									<a href="action.do?mod=<?php echo fnEncode(1563)."&id=".fnEncode($cod_empresa)."&idC=".fnEncode($cod_conveni); ?>">
									<span class="fal fa-arrow-circle-left fa-2x"></span></a>
								</li>
							</ul>
						</div>
					<?php		
					} else {
						$abaFormalizacao = 1092; 
						include "abasFormalizacaoEmp.php";	
					}  ?>
					
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
											<label for="inputName" class="control-label required">Convênio / Aditivos</label>
												<select data-placeholder="Selecione uma empresa" name="COD_ADITIVO" id="COD_ADITIVO" class="chosen-select-deselect requiredChk" style="width:100%;" required>
													<option value=""></option>
													<optgroup label="Convênio">
													   <?php 
														
														$sql = "SELECT COD_CONVENI,NOM_CONVENI FROM CONVENIO A
																WHERE a.cod_empresa = $cod_empresa AND 
																	  a.cod_conveni = $cod_conveni 
																";
														
														$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
													
														while ($qrLista = mysqli_fetch_assoc($arrayQuery))
														 {														
															?>
																  <option value='0'><?=$qrLista['NOM_CONVENI']?></option>
															<?php 
														}											
														
														
														?>
														</optgroup>
														
														<?php
														$sql = "SELECT 
																	 a.COD_ADITIVO,
																	CONCAT(B.DES_TPMOTIV,' / ', case when a.tip_aditivo='P' then
																		'Prazo'
																			when a.tip_aditivo='V' then
																		'Valor'
																		END)  TERMO    

																FROM termoaditivo a,webtools.TIPOMOTIVO b
																WHERE a.COD_TIPMOTI=b.COD_TIPMOTI AND 
																	  a.cod_empresa = $cod_empresa AND 
																	  a.cod_conveni = $cod_conveni ";
														
														$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);														
														?>
														
														<optgroup label="Aditivos">
														<?php
														while ($qrLista = mysqli_fetch_assoc($arrayQuery))
														 {														
															?>
																  <option value='<?=$qrLista['COD_ADITIVO']?>'><?=$qrLista['TERMO']?></option>
															<?php 
														}
													?>
														</optgroup>
												</select>
											<div class="help-block with-errors"></div>
										</div>
									</div>

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
										<label for="inputName" class="control-label required">Valor Concedente</label>
										<div id="relatorioValConveni">
											<input type="text" class="form-control input-sm text-right money leituraOff" readonly name="VAL_CONVENI" id="VAL_CONVENI" value="000" data-mask="##0" data-mask-reverse="true" maxlength="11" required>
										</div>
									</div>
									<div class="help-block with-errors"></div>
								</div>

								<div class="col-md-1 text-center">
								<div class="push20"></div>
								<span class="f21">+</span>
								</div>
								
								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Valor Contrapartida</label>
										<div id="relatorioValContpar">
											<input type="text" class="form-control input-sm text-right money leituraOff" readonly name="VAL_CONTPAR" id="VAL_CONTPAR" value="000" data-mask="##0" data-mask-reverse="true" maxlength="11" required>
										</div>
									</div>
									<div class="help-block with-errors"></div>
								</div>
								
								<div class="col-md-1 text-center">
								<div class="push20"></div>
								<span class="f21">=</span>
								</div>
								
								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Valor Global</label>
										<div id="relatorioValValor">
											<input type="text" class="form-control input-sm money text-right leituraOff" readonly name="VAL_VALOR" id="VAL_VALOR" readonly value="000" data-mask="##0" data-mask-reverse="true" maxlength="11" required>
										</div>
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
						
						<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?=$cod_empresa;?>" />	
						<input type="hidden" name="DES_TPCONTRAT" id="DES_TPCONTRAT" value="CON" />	
						<input type="hidden" name="COD_CONVENI" id="COD_CONVENI" value="<?=$cod_conveni;?>">
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
								
								<table class="table table-bordered table-striped table-hover tablesorter buscavel">
								  <thead>
									<tr>
									  <th width="40"></th>
									  <th>Código</th>
									  <th>Tipo</th>
									  <th>Núm. Contrato</th>
									  <th>Ano</th>
									  <th class='text-right'>Valor Concedente</th>
									  <th class='text-right'>Valor Contrapartida</th>
									  <th class='text-right'>Valor Global</th>
									</tr>
								  </thead>
								<tbody>
								
								<?php 
									$sql = "SELECT * FROM CONTRATO 
											WHERE DES_TPCONTRAT = 'CON' AND COD_EMPRESA = $cod_empresa AND COD_CONVENI = $cod_conveni";
											
									//fnEscreve($sql);
									
									$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
									
									$count=0;
									while ($qrContrat = mysqli_fetch_assoc($arrayQuery))
									  {	

										if ($qrContrat['COD_ADITIVO'] == "0"){
											$tipoContrato = "Convênio";
										}else{
											$tipoContrato = "Aditivo";	
										}
								  
										$count++;
										echo"
											<tr>
											  <td><input type='radio' name='radio1' onclick='retornaForm(".$count.")'></th>
											  
											  <td>".$qrContrat['COD_CONTRAT']."</td>
											  <td>".$tipoContrato."</td>
											  <td>".$qrContrat['NRO_CONTRAT']."</td>
											  <td>".$qrContrat['DES_ANO']."</td>
											  <td class='text-right'>".fnValor($qrContrat['VAL_CONVENI'],2)."</td>
											  <td class='text-right'>".fnValor($qrContrat['VAL_CONTPAR'],2)."</td>
											  <td class='text-right'>".fnValor($qrContrat['VAL_VALOR'],2)."</td>
											</tr>
											
											<input type='hidden' id='ret_COD_CONTRAT_".$count."' value='".$qrContrat['COD_CONTRAT']."'>
											<input type='hidden' id='ret_COD_EMPRESA_".$count."' value='".$qrContrat['COD_EMPRESA']."'>
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
											<input type='hidden' id='ret_VAL_CONVENI_".$count."' value='".fnValor($qrContrat['VAL_CONVENI'],2)."'>
											<input type='hidden' id='ret_VAL_CONTPAR_".$count."' value='".fnValor($qrContrat['VAL_CONTPAR'],2)."'>
											<input type='hidden' id='ret_DES_TPCONTRAT_".$count."' value='".$qrContrat['DES_TPCONTRAT']."'>
											<input type='hidden' id='ret_COD_ADITIVO_".$count."' value='".$qrContrat['COD_ADITIVO']."'>
											
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
	
<script type="text/javascript">

    $(document).ready(function(){
            $('#btnBusca').prop('disabled',true);

            $('.datePicker').datetimepicker({
                     format: 'DD/MM/YYYY',
                    }).on('changeDate', function(e){
                            $(this).datetimepicker('hide');
                    });

            //chosen obrigatório
            $.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
            $('#formulario').validator();

            $(document).on('change','#VAL_CONTPAR,#VAL_CONVENI',function(){
                    $('#VAL_VALOR').unmask();
                    val_contpar = parseFloat($('#VAL_CONTPAR').val().replace('.','').replace(',','.')),
                    val_conveni = parseFloat($('#VAL_CONVENI').val().replace('.','').replace(',','.')),
                    total = (val_contpar+val_conveni).toFixed(2);
                    $('#VAL_VALOR').val(total).toString();
            });

            $('#COD_ADITIVO').change(function(){
                    $.ajax({
                            type: "POST",
                            url: "ajxValorConvenio.do",
                            data: $('#formulario').serialize(),
                            beforeSend:function(){
                                    $('#relatorioValContpar,#relatorioValConveni,#relatorioValValor').html('<center><div class="loading" style="width: 100%;"></div></center>');
                            },
                            success:function(data){
                                    $("#relatorioValConveni").html($("#RET_VAL_CONVENI",data));											
                                    $("#relatorioValContpar").html($("#RET_VAL_CONTPAR",data));
                                    $("#relatorioValValor").html($("#RET_VAL_VALOR",data));
                                    // console.log(data);										
                            },
                            error:function(){
                                    // $('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
                            }
                    });	
            });
            $('.datePicker').datetimepicker({
			format: 'DD/MM/YYYY',
			maxDate : 'now',
		}).on('changeDate', function(e){
		       $(this).datetimepicker('hide');
		});

        $("#DAT_INI_GRP").data("DateTimePicker").defaultDate(false);
		$('#DAT_FIM_GRP').data("DateTimePicker").defaultDate(false);
		$('#DAT_ASSINAT_GRP').data("DateTimePicker").defaultDate(false);

		$("#DAT_FIM_GRP").on("dp.error", function (e) {
               $('#DAT_FIM_GRP').data("DateTimePicker").date(null);
        });

        $('#DAT_ASSINAT_GRP').on("dp.error", function (e) {
               $('#DAT_ASSINAT_GRP').data("DateTimePicker").date(null);
        });
        $('#DAT_ORDEM_GRP').on("dp.error", function (e) {
               $('#DAT_ORDEM_GRP').data("DateTimePicker").date(null);
        });

        $("#DAT_INI_GRP").on("dp.change", function (e) {
               $('#DAT_FIM_GRP').data("DateTimePicker").minDate(e.date).date(null);
               $('#DAT_ASSINAT_GRP').data("DateTimePicker").minDate(e.date).date(null);
               $('#DAT_ORDEM_GRP').data("DateTimePicker").minDate(e.date).date(null);
        });
        $("#DAT_FIM_GRP").on("dp.change", function (e) {
        });          
    });	

    function retornaForm(index){
            $("#formulario #COD_CONTRAT").val($("#ret_COD_CONTRAT_"+index).val());
            $("#formulario #COD_OBJETOANEXO").val($("#ret_COD_CONTRAT_"+index).val());
            $("#formulario #COD_EMPRESA").val($("#ret_COD_EMPRESA_"+index).val());
            $("#formulario #COD_CONVENI").val($("#ret_COD_CONVENI_"+index).val());
            $("#formulario #COD_CLIENTE").val($("#ret_COD_CLIENTE_"+index).val());
            $("#formulario #COD_PROPOSTA").val($("#ret_COD_PROPOSTA_"+index).val()).trigger("chosen:updated");
            $("#formulario #COD_LICITAC").val($("#ret_COD_LICITAC_"+index).val()).trigger("chosen:updated");
            $("#formulario #COD_ADITIVO").val($("#ret_COD_ADITIVO_"+index).val()).trigger("chosen:updated");
            $("#formulario #NRO_CONTRAT").val($("#ret_NRO_CONTRAT_"+index).val());
            $("#formulario #DES_ANO").val($("#ret_DES_ANO_"+index).val());
            $("#formulario #DAT_INI").val($("#ret_DAT_INI_"+index).val());
            $("#formulario #DAT_FIM").val($("#ret_DAT_FIM_"+index).val());
            $("#formulario #DAT_ASSINAT").val($("#ret_DAT_ASSINAT_"+index).val());
            $("#formulario #DAT_ORDEM").val($("#ret_DAT_ORDEM_"+index).val());
            $("#formulario #VAL_VALOR").val($("#ret_VAL_VALOR_"+index).val());
            $("#formulario #VAL_CONVENI").val($("#ret_VAL_CONVENI_"+index).val());
            $("#formulario #VAL_CONTPAR").val($("#ret_VAL_CONTPAR_"+index).val());
            <?php if ($popUp != "true"){  ?>
                    $("#formulario #DES_TPCONTRAT").val($("#ret_DES_TPCONTRAT_"+index).val()).trigger("chosen:updated");
            <?php }else{
            ?>
                    $("#formulario #DES_TPCONTRAT").val($("#ret_DES_TPCONTRAT_"+index).val());
            <?php 
            } ?>
            $('.upload').prop('disabled',false).removeAttr('disabled');
            $('#formulario').validator('validate');			
            $("#formulario #hHabilitado").val('S');

            refreshUpload();	
    }

</script>
