<?php
	
	//echo "<h5>_".$opcao."</h5>";

	$hashLocal = mt_rand();	
	$mod = fnDecode($_GET['mod']);

	$tipo = fnLimpaCampo($_GET['tipo']);

	$andTip = "";

	if($tipo == '' || !isset($_GET['tipo']) || $tipo == "F"){
		$andTip = 'AND LOG_LANCAME = "F"';
	}

	if($mod == 1699){
		$log_juridico = "S";
	}else{
		$log_juridico = "N";
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

			$cod_lancame = fnLimpaCampoZero($_REQUEST['COD_LANCAME']);
			$cod_cliente = fnLimpaCampoZero($_REQUEST['COD_CLIENTE']);
			$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
			if (empty($_REQUEST['LOG_JURIDICO'])) {$log_juridico='N';}else{$log_juridico=$_REQUEST['LOG_JURIDICO'];}
			$pct_juridico = fnValorSql($_REQUEST['PCT_JURIDICO']);
			$cod_tipo = fnLimpacampoZero($_REQUEST['COD_TIPO']);
			$val_lancame = fnValorSql($_REQUEST['VAL_LANCAME']);
			$tip_lancame = $tipo;
			$cod_usucada = $_SESSION[SYS_COD_USUARIO];

			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];

			// if($cod_tipo != 0){
			// 	$sqlTipo = "SELECT LOG_LANCAME FROM TIP_CREDITO WHERE COD_EMPRESA = $cod_empresa AND COD_TIPO = $cod_tipo"; 
			// 	$arrayTipo = mysqli_query(connTemp($cod_empresa,''),$sqlTipo);
			// 	$qrTipo = mysqli_fetch_assoc($arrayTipo);
			// 	$tip_lancame = $qrTipo[LOG_LANCAME];
			// }
						
			if ($opcao != ''){				
				
				//mensagem de retorno
				switch ($opcao)
				{
					case 'CAD':

						$sqlVer = "SELECT COD_LANCAME FROM LANCAMENTO_AUTOMATICO 
								   WHERE COD_EMPRESA = $cod_empresa 
								   AND COD_TIPO = $cod_tipo
								   AND COD_CLIENTE = $cod_cliente";

						$arrayVer = mysqli_query(connTemp($cod_empresa,''),$sqlVer);

						if(mysqli_num_rows($arrayVer) == 0){

							$sql = "INSERT INTO LANCAMENTO_AUTOMATICO(
														COD_EMPRESA,
														COD_CLIENTE,
														COD_TIPO,
														LOG_JURIDICO,
														TIP_LANCAME,
														VAL_LANCAME,
														COD_USUCADA
													) VALUES(
														$cod_empresa,
														$cod_cliente,
														'$cod_tipo',
														'$log_juridico',
														'$tip_lancame',
														'$val_lancame',
														$cod_usucada
												)";

							 // fnEscreve($sql);

							mysqli_query(connTemp($cod_empresa,''),$sql);

							$sqlCod = "SELECT MAX(COD_LANCAME) COD_LANCAME FROM LANCAMENTO_AUTOMATICO 
										WHERE COD_EMPRESA = $cod_empresa 
										AND COD_USUCADA = $cod_usucada
										AND COD_CLIENTE = $cod_cliente";

							$arrayCod = mysqli_query(connTemp($cod_empresa,''),$sqlCod);

							$qrCod = mysqli_fetch_assoc($arrayCli);

							$cod_lancame = $qrCod[COD_LANCAME];

							$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";	

						}else{

							$qrVer = mysqli_fetch_assoc($arrayVer);

							$cod_lancame = $qrVer[COD_LANCAME];

							$sql = "UPDATE LANCAMENTO_AUTOMATICO SET
													COD_TIPO = '$cod_tipo',
													LOG_JURIDICO = '$log_juridico',
													TIP_LANCAME = '$tip_lancame',
													VAL_LANCAME = '$val_lancame'
									WHERE COD_EMPRESA = $cod_empresa
									AND COD_LANCAME = $cod_lancame";

							// fnEscreve($sql);

							mysqli_query(connTemp($cod_empresa,''),$sql);

							$msgRetorno = "Lançamento já existente. Alteração efetuada com <strong>sucesso!</strong>";

						}

						break;
					case 'ALT':

						$sql = "UPDATE LANCAMENTO_AUTOMATICO SET
												COD_TIPO = '$cod_tipo',
												LOG_JURIDICO = '$log_juridico',
												TIP_LANCAME = '$tip_lancame',
												VAL_LANCAME = '$val_lancame'
								WHERE COD_EMPRESA = $cod_empresa
								AND COD_LANCAME = $cod_lancame";

						// fnEscreve($sql);

						mysqli_query(connTemp($cod_empresa,''),$sql);

						$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";		
						break;
					case 'EXC':

						$sql = "DELETE FROM LANCAMENTO_AUTOMATICO
								WHERE COD_EMPRESA = $cod_empresa
								AND COD_LANCAME = $cod_lancame";

						mysqli_query(connTemp($cod_empresa,''),$sql);
						
						$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";		
						break;
					break;
				}		

				$sql = "INSERT INTO HISTORICO_LANCAMENTO_AUTOMATICO(
											COD_EMPRESA,
											COD_LANCAME,
											COD_CLIENTE,
											COD_TIPO,
											LOG_JURIDICO,
											TIP_LANCAME,
											VAL_LANCAME,
											COD_USUCADA
										) VALUES(
											$cod_empresa,
											$cod_lancame,
											$cod_cliente,
											'$cod_tipo',
											'$log_juridico',
											'$tip_lancame',
											'$val_lancame',
											$cod_usucada
									)";

				// fnEscreve($sql);

				mysqli_query(connTemp($cod_empresa,''),$sql);

				$msgTipo = 'alert-success';
				
			}  	

		}
	}
      
	
	//busca dados da url	
	if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))){
		//busca dados da empresa
		$cod_empresa = fnDecode($_GET['id']);	
		$cod_cliente = fnDecode($_GET['idc']);
		$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '".$cod_empresa."' ";
		//fnEscreve($sql);
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql);
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
		
		if (isset($arrayQuery)){
			$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
			$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
		}
												
	}else {
		$cod_empresa = 0;		
		//fnEscreve('entrou else');
	}

	$sqlCli = "SELECT NOM_CLIENTE FROM CLIENTES WHERE COD_EMPRESA = $cod_empresa AND COD_CLIENTE = $cod_cliente";
	$arrayCli = mysqli_query(connTemp($cod_empresa,''),$sqlCli);
	$qrCli = mysqli_fetch_assoc($arrayCli);

	$nom_cliente = $qrCli[NOM_CLIENTE];
	// fnEscreve($cod_cliente);
	
	//fnMostraForm();

?>
			
					<div class="push30"></div> 
					
					<div class="row">				
					
						<div class="col-md-12 margin-bottom-30">
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
													
													<div class="col-md-12">
														<div class="form-group">
															<!-- <label for="inputName" class="control-label required"></label> -->
															<input type="text" class="form-control input-sm leitura" name="NOM_CLIENTE" id="NOM_CLIENTE" value="<?=$nom_cliente?>" required>
														</div>
														<div class="help-block with-errors"></div>
													</div>

												</div>

												<div class="push10"></div>
											
												<div class="row">
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label required">Código</label>
															<input type="text" class="form-control input-sm leitura" name="COD_CLIENTE" id="COD_CLIENTE" value="<?=$cod_cliente?>" required>
														</div>
														<div class="help-block with-errors"></div>
													</div>       

													<div class="col-xs-3">
														<div class="form-group">
															<label for="inputName" class="control-label required">Tipo de Lançamento</label>
																<select data-placeholder="Selecione o tipo do lançamento" name="COD_TIPO" id="COD_TIPO" class="chosen-select-deselect" required>
																	<option></option>
																	<?php

																		$sqlLancame = "SELECT COD_TIPO, 
																							  DES_TIPO 
																					   FROM TIP_CREDITO 
																					   WHERE COD_EMPRESA = $cod_empresa 
																					   AND LOG_AUTOMATICO = 'S'
																					   $andTip
																					   AND COD_EXCLUSA = 0 ";

																		$arrayLanca = mysqli_query(connTemp($cod_empresa,''),$sqlLancame);

																		while ($qrLanca = mysqli_fetch_assoc($arrayLanca)) {
																	?>
																			<option value="<?=$qrLanca[COD_TIPO]?>"><?=$qrLanca[DES_TIPO]?></option>
																	<?php 
																		}

																	?>
																</select>
                                                                <script>
                                                                	$("#formulario #COD_TIPO").val("<?php echo $cod_tipo; ?>").trigger("chosen:updated"); 
                                                                </script>
															<div class="help-block with-errors"></div>
														</div>
													</div>

													<div class="col-md-2" id="blocoJuridico" style="display: none;">
														<div class="form-group">
															<label for="inputName" class="control-label">Percentual Jurídico</label><br/>
															<label class="switch switch-small">
															<input type="checkbox" name="LOG_JURIDICO" id="LOG_JURIDICO" class="switch" value="S" <?php echo $check_juridico; ?> />
															<span></span>
															</label> 								
															<div class="help-block with-errors">Considera valor como % (jurídico)</div>
														</div>
																				
													</div>

													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label required">Valor do Lançamento</label>
                                                            <input type="text" class="form-control input-sm money" name="VAL_LANCAME" id="VAL_LANCAME" value="<?=fnValor($val_lancame,2);?>" required>
															<div class="help-block with-errors"></div>
														</div>
													</div>

													<div class="col-md-2" id="percentual" style="display: none;">
														<div class="form-group">
															<label for="inputName" class="control-label">&nbsp;</label>
                                                            <input type="text" class="form-control input-sm leitura" name="PERC" id="PERC" value="%">
															<div class="help-block with-errors"></div>
														</div>
													</div>

													<?php 
														if($check_juridico == 'checked'){
															$mostraJuri = "block";
														}else{
															$mostraJuri = "none";
														}
													?>

													<!-- <div id="dadosJuridicos" style="display: <?=$mostraJuri?>">

														<div class="col-md-2">
															<div class="form-group">
																<label for="inputName" class="control-label">Percentual Jurídico</label>
	                                                            <input type="text" class="form-control input-sm money" name="PCT_JURIDICO" id="PCT_JURIDICO" value="<?=fnValor($pct_juridico,2);?>">
																<div class="help-block with-errors"></div>
															</div>
														</div>

													</div> -->
																				
												</div>
												
										</fieldset>	
																				
										<div class="push10"></div>
										<hr>	
										<div class="form-group text-right col-lg-12">
											
											  <button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>
											  <button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
											  <button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Alterar</button>
											  <button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fa fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>
											
										</div>
										
										<input type="hidden" name="opcao" id="opcao" value="">
										<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?=$cod_empresa?>">
										<!-- <input type="hidden" name="COD_CLIENTE" id="COD_CLIENTE" value="<?=$cod_cliente?>"> -->
										<input type="hidden" name="COD_LANCAME" id="COD_LANCAME" value="<?=$cod_lancame?>">
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
													  <th>Cód.</th>
													  <th>Lançamento</th>
													  <th>Valor</th>
													  <th class='text-center'>% Jurídico</th>
													</tr>
												  </thead>
												<tbody>
												  
												<?php 
												
													$sql = "SELECT LA.*, TC.DES_TIPO FROM LANCAMENTO_AUTOMATICO LA
															INNER JOIN TIP_CREDITO TC ON TC.COD_TIPO = LA.COD_TIPO
															WHERE LA.COD_EMPRESA = $cod_empresa 
															AND LA.TIP_LANCAME = '$tipo'
															AND LA.COD_CLIENTE = $cod_cliente";

													// fnEscreve($sql);
													$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
													
													$count=0;
													while ($qrDados = mysqli_fetch_assoc($arrayQuery))
													  {		

													  	$juridico = '';
													  	$pct_juridico = '';

													  	if($qrDados[LOG_JURIDICO] == 'S'){
													  		$juridico = '<span class="fas fa-check"></span>';
													  		$pct_juridico = fnValor($qrDados['PCT_JURIDICO'],2)."%";
													  	}

														$count++;	
														echo"
															<tr>
															  <td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(".$count.")'></th>
															  <td>".$qrDados['COD_LANCAME']."</td>
															  <td>".$qrDados['DES_TIPO']."</td>
															  <td>".fnValor($qrDados['VAL_LANCAME'],2)."</td>
															  <td class='text-center'>".$juridico."</td>
															</tr>
															<input type='hidden' id='ret_COD_LANCAME_".$count."' value='".$qrDados['COD_LANCAME']."'>
															<input type='hidden' id='ret_COD_TIPO_".$count."' value='".$qrDados['COD_TIPO']."'>
															<input type='hidden' id='ret_VAL_LANCAME_".$count."' value='".fnValor($qrDados['VAL_LANCAME'],2)."'>
															<input type='hidden' id='ret_LOG_JURIDICO_".$count."' value='".$qrDados['LOG_JURIDICO']."'>
															<input type='hidden' id='ret_PCT_JURIDICO_".$count."' value='".fnValor($qrDados['PCT_JURIDICO'],2)."'>
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

		$(function(){

			$("#COD_TIPO").on("change", function(){
				if($(this).val() == 2){
					$("#blocoJuridico").fadeIn("fast");
				}else{
					$("#blocoJuridico").fadeOut("fast");
				}
			});

			$("#LOG_JURIDICO").change(function(){
				if($(this).prop('checked')){
					$("#percentual").fadeIn("fast");
				}else{
					$("#percentual").fadeOut("fast");
				}
			});

		});
		
		function retornaForm(index){
			$("#formulario #COD_LANCAME").val($("#ret_COD_LANCAME_"+index).val());
			$("#formulario #COD_TIPO").val($("#ret_COD_TIPO_"+index).val()).trigger("chosen:updated");
			$("#formulario #VAL_LANCAME").val($("#ret_VAL_LANCAME_"+index).val());

			if($("#formulario #COD_TIPO").val() == 2){
				$("#blocoJuridico").fadeIn("fast");
			}else{
				$("#blocoJuridico").fadeOut("fast");
			}
			
			if($("#ret_LOG_JURIDICO_"+index).val() == 'S'){
				$("#formulario #LOG_JURIDICO").prop('checked',true);
				$("#percentual").fadeIn("fast");;
			}else{
				$("#formulario #LOG_JURIDICO").prop('checked',false);
				$("#percentual").fadeOut("fast");
			}

			// $("#formulario #PCT_JURIDICO").val($("#ret_PCT_JURIDICO_"+index).val());
			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');						
		}
		
	</script>	