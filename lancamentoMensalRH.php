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

			$cod_caixa = fnLimpaCampoZero(fnDecode($_REQUEST['COD_CAIXA']));
			$cod_cliente = fnLimpaCampoZero($_REQUEST['COD_CLIENTE']);
			$cod_empresa = fnLimpaCampo($_REQUEST['COD_EMPRESA']);
			$dat_ini = fnDataSql($_REQUEST['DAT_INI']);
			$dat_fim = fnDataSql($_REQUEST['DAT_FIM']);

			$cod_usucada = $_SESSION[SYS_COD_USUARIO];

			$newDate = explode('/', $_REQUEST['DAT_INI']);
			$dia = $newDate[0];
			$mes   = $newDate[1];
			$ano  = $newDate[2];
			$mesano = $newDate[1]."/".$newDate[2];

			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
						
			if ($opcao != ''){

				//mensagem de retorno
				$msgTipo = 'alert-success';

				switch ($opcao){


					case 'CAD':

						$sqlVer = "SELECT 1 FROM MES_CAIXA 
									WHERE COD_EMPRESA = $cod_empresa
									AND MESANO = '$mesano'
									LIMIT 1";

						// fnEscreve($sqlVer);

						$arrayVer = mysqli_query(connTemp($cod_empresa,''),$sqlVer);
						$existe = mysqli_num_rows($arrayVer);

						if($existe == 1){

							$msgTipo = 'alert-warning';
							$msgRetorno = "Ja existe lançamento <strong>no mês $mes</strong>.";

						}else{

							$sql = "INSERT INTO MES_CAIXA(
													COD_EMPRESA,
													DAT_INI,
													DAT_FIM,
													MESANO,
													COD_USUCADA
												) VALUES(
													$cod_empresa,
													'$dat_ini',
													'$dat_fim',
													'$mesano',
													$cod_usucada
												)";

							mysqli_query(connTemp($cod_empresa,''),$sql);

							$sqlCod = "SELECT MAX(COD_MES) COD_MES FROM MES_CAIXA
										WHERE COD_EMPRESA = $cod_empresa
										AND COD_USUCADA = $cod_usucada";

							$arrayCod = mysqli_query(connTemp($cod_empresa,''),$sqlCod);
							$qrCod = mysqli_fetch_assoc($arrayCod);

							$cod_mes = $qrCod[COD_MES];

							// $sqlCli = "SELECT COD_CLIENTE, VAL_SALBASE, LOG_JURIDICO, PCT_JURIDICO 
							// 			FROM CLIENTES 
							// 			WHERE COD_EMPRESA = $cod_empresa
							// 			AND LOG_TITULAR = 'S'
							// 			AND LOG_ESTATUS = 'S'";

							// $arrayCli = mysqli_query(connTemp($cod_empresa,''),$sqlCli);

							// $val_juridico = 0;

							// $insertClientes = "";

							// while($qrCli = mysqli_fetch_assoc($arrayCli)){

							// 	$salario = $qrCli[VAL_SALBASE];

							// 	if($salario > 0 && $salario != ""){

							// 		$insertClientes .= "(
							// 							$cod_empresa,
							// 							$cod_mes,
							// 							$qrCli[COD_CLIENTE],
							// 							'$dat_ini',
							// 							$mes,
							// 							$ano,
							// 							1,
							// 							'$salario',
							// 							'F',
							// 							$cod_usucada
							// 						),";

							// 	}


							// 	if($qrCli[LOG_JURIDICO] == 'S'){

							// 		$val_juridico = ($qrCli[PCT_JURIDICO]/100) * $qrCli[VAL_SALBASE];

							// 		$insertClientes .= "(
							// 					$cod_empresa,
							// 					$cod_mes,
							// 					$qrCli[COD_CLIENTE],
							// 					'$dat_ini',
							// 					$mes,
							// 					$ano,
							// 					2,
							// 					'$val_juridico',
							// 					'F',
							// 					$cod_usucada
							// 				),";

							// 	}

							// }


							// $insertClientes = rtrim(trim($insertClientes),',');

							// $sqlMes = "INSERT INTO CAIXA(
							// 					COD_EMPRESA,
							// 					COD_MES,
							// 					COD_CONTRAT,
							// 					DAT_LANCAME,
							// 					MES,
							// 					ANO,
							// 					COD_TIPO,
							// 					VAL_CREDITO,
							// 					TIP_LANCAME,
							// 					COD_USUCADA
							// 				) VALUES $insertClientes";

							// // fnEscreve($sqlMes);

							// if(trim($insertClientes) != ""){

							// 	mysqli_query(connTemp($cod_empresa,''),$sqlMes);

							// }

							// // LANÇAMENTO DAS BONIFICAÇÕES -----------------------------------------------

							// $sqlCli2 = "SELECT COD_CLIENTE, VAL_BONIFICA, PCT_JURIBONI
							// 			FROM CLIENTES 
							// 			WHERE COD_EMPRESA = $cod_empresa
							// 			AND LOG_TITULAR = 'S'
							// 			AND LOG_ESTATUS = 'S'
							// 			AND LOG_BONIFICA = 'S'";

							// $arrayCli2 = mysqli_query(connTemp($cod_empresa,''),$sqlCli2);

							// $val_juridico = 0;

							// $insertClientes = "";

							// while($qrCli2 = mysqli_fetch_assoc($arrayCli2)){

							// 	$salario = $qrCli2[VAL_BONIFICA];

							// 	if($salario > 0 && $salario != ""){

							// 		$insertClientes .= "(
							// 							$cod_empresa,
							// 							$cod_mes,
							// 							$qrCli2[COD_CLIENTE],
							// 							'$dat_ini',
							// 							$mes,
							// 							$ano,
							// 							11,
							// 							'$salario',
							// 							'B',
							// 							$cod_usucada
							// 						),";

							// 	}


							// 	if($qrCli2[PCT_JURIBONI] != "0.00"){

							// 		$val_juridico = ($qrCli2[PCT_JURIBONI]/100) * $qrCli2[VAL_BONIFICA];

							// 		$insertClientes .= "(
							// 					$cod_empresa,
							// 					$cod_mes,
							// 					$qrCli2[COD_CLIENTE],
							// 					'$dat_ini',
							// 					$mes,
							// 					$ano,
							// 					12,
							// 					'$val_juridico',
							// 					'B',
							// 					$cod_usucada
							// 				),";

							// 	}

							// }


							// $insertClientes = rtrim(trim($insertClientes),',');

							// $sqlMes = "INSERT INTO CAIXA(
							// 					COD_EMPRESA,
							// 					COD_MES,
							// 					COD_CONTRAT,
							// 					DAT_LANCAME,
							// 					MES,
							// 					ANO,
							// 					COD_TIPO,
							// 					VAL_CREDITO,
							// 					TIP_LANCAME,
							// 					COD_USUCADA
							// 				) VALUES $insertClientes";

							$sqlCli = "SELECT LA.COD_CLIENTE, LA.VAL_LANCAME, LA.COD_TIPO, LA.TIP_LANCAME, LA.LOG_JURIDICO
										FROM LANCAMENTO_AUTOMATICO LA 
										INNER JOIN CLIENTES CL ON CL.COD_CLIENTE = LA.COD_CLIENTE 
																AND CL.LOG_ESTATUS = 'S' 
																AND CL.LOG_TITULAR = 'S'
										WHERE LA.COD_EMPRESA = $cod_empresa";

							$arrayCli = mysqli_query(connTemp($cod_empresa,''),$sqlCli);

							$val_juridico = 0;

							$insertClientes = "";

							while($qrCli = mysqli_fetch_assoc($arrayCli)){

								$val_lancame = $qrCli[VAL_LANCAME];

								if($val_lancame > 0 && $val_lancame != ""){

									if($qrCli[COD_TIPO] != 2){

										$insertClientes .= "(
															$cod_empresa,
															$cod_mes,
															$qrCli[COD_CLIENTE],
															'$dat_ini',
															$mes,
															$ano,
															$qrCli[COD_TIPO],
															'$val_lancame',
															'$qrCli[TIP_LANCAME]',
															$cod_usucada
														),";

									}else{

										if($qrCli[LOG_JURIDICO] == "S"){

											$sqlCliSal = "SELECT LA.VAL_LANCAME AS SALARIO
														FROM LANCAMENTO_AUTOMATICO LA 
														WHERE LA.COD_EMPRESA = $cod_empresa
														AND LA.COD_CLIENTE = $qrCli[COD_CLIENTE]
														AND LA.COD_TIPO = 1";

											$arrayCliSal = mysqli_query(connTemp($cod_empresa,''),$sqlCliSal);

											$qrSal = mysqli_fetch_assoc($arrayCliSal);


											$val_juridico = ($qrCli[VAL_LANCAME]/100) * $qrSal[SALARIO];

										}else{

											$val_juridico = $qrCli[VAL_LANCAME];

										}

										$insertClientes .= "(
													$cod_empresa,
													$cod_mes,
													$qrCli[COD_CLIENTE],
													'$dat_ini',
													$mes,
													$ano,
													2,
													'$val_juridico',
													'$qrCli[TIP_LANCAME]',
													$cod_usucada
												),";

									}

								}

							}


							$insertClientes = rtrim(trim($insertClientes),',');

							$sqlMes = "INSERT INTO CAIXA(
												COD_EMPRESA,
												COD_MES,
												COD_CONTRAT,
												DAT_LANCAME,
												MES,
												ANO,
												COD_TIPO,
												VAL_CREDITO,
												TIP_LANCAME,
												COD_USUCADA
											) VALUES $insertClientes";

							

							if(trim($insertClientes) != ""){

								mysqli_query(connTemp($cod_empresa,''),$sqlMes);

							}

							$msgRetorno = "Registros gravados com <strong>sucesso!</strong>";

						}

						break;
					case 'ALT':

						$sqlVer = "SELECT 1 FROM MES_CAIXA 
									WHERE COD_EMPRESA = $cod_empresa
									AND MESANO = '$mesano'
									AND COD_MES != $cod_mes
									LIMIT 1";

						// fnEscreve($sqlVer);

						$arrayVer = mysqli_query(connTemp($cod_empresa,''),$sqlVer);
						$existe = mysqli_num_rows($arrayVer);

						if($existe == 1){

							$msgTipo = 'alert-warning';
							$msgRetorno = "Ja existe outro lançamento <strong>no mês $mes</strong>.";

						}else{

							$sql = "UPDATE MES_CAIXA SET
													DAT_INI='$dat_ini',
													DAT_FIM = '$dat_fim',
													MESANO = $mesano,
													COD_ALTERAC = $cod_usucada,
													DAT_ALTERAC = NOW()
									WHERE COD_EMPRESA = $cod_empresa
									AND COD_MES = $cod_mes;

									UPDATE CAIXA SET
													DAT_LANCAME='$dat_ini',
													MES = $mes,
													ANO = $ano,
													COD_ALTERAC = $cod_usucada,
													DAT_ALTERAC = NOW()
									WHERE COD_EMPRESA = $cod_empresa
									AND COD_MES = $cod_mes";

							mysqli_multi_query(connTemp($cod_empresa,''),$sql);

							$msgRetorno = "Registros alterados com <strong>sucesso!</strong>";

						}

						break;
					case 'EXC':

						$sql = "DELETE FROM MES_CAIXA
								WHERE COD_EMPRESA = $cod_empresa
								AND COD_MES = $cod_mes;

								DELETE FROM CAIXA
								WHERE COD_EMPRESA = $cod_empresa
								AND COD_MES = $cod_mes";

						mysqli_multi_query(connTemp($cod_empresa,''),$sql);

						$msgRetorno = "Registros excluídos com <strong>sucesso!</strong>";		
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

	
	//fnMostraForm();

?>
			
					<div class="push30"></div> 
					
					<div class="row">				
					
						<div class="col-md-12 margin-bottom-30">
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
									//menu superior - cliente
									
									// $abaEmpresa = 1706;						
																					
									// include "abasRH.php";

									?>
									
									<div class="push30"></div> 
			
									<div class="login-form">
									
										<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
																				
										<fieldset>
											<legend>Dados do Lançamento</legend> 
											
												<div class="row">

													<div class="col-xs-2">
														<div class="form-group">
															<label for="inputName" class="control-label required">Código</label>
                                                            <input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_MES" id="COD_MES">
														</div>
													</div>

													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label required">Data Inicial</label>
															
															<div class="input-group date datePicker" id="DAT_INI_GRP">
																<input type='text' class="form-control input-sm data" name="DAT_INI" id="DAT_INI" value="<?=$dat_ini?>" required/>
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
																<input type='text' class="form-control input-sm data" name="DAT_FIM" id="DAT_FIM" value="<?=$dat_ini?>" required/>
																<span class="input-group-addon">
																	<span class="glyphicon glyphicon-calendar"></span>
																</span>
															</div>
															<div class="help-block with-errors"></div>
														</div>
													</div>
												
													
												</div>
													
										</fieldset>
																				
										<div class="push10"></div>
										<hr>	
										<div class="form-group text-right col-lg-12">
											
											  <button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>
											  <?php if($cod_caixa != 0){ ?>
											  	<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Alterar</button>
											  	<button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fa fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>
											  <?php }else{ ?>
											  	<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
											  <?php } ?>
											
										</div>
										
											<input type="hidden" name="opcao" id="opcao" value="">
											<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?=$cod_empresa?>">
											<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>">	
											<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">		
											
											<div class="push50"></div>

											<div class="col-md-12">
												
												<table class="table table-bordered table-striped table-hover tableSorter">

												  	<thead>
														<tr>
														  <th class="{ sorter: false }" width="40"></th>
														  <th>Código</th>
														  <th>Dt. Lançamento</th>
														  <th>Dt. Fechamento</th>
														</tr>
												 	</thead>

													<tbody>
													  
														<?php 
														
															$sql = "SELECT * FROM MES_CAIXA WHERE COD_EMPRESA = $cod_empresa ORDER BY DAT_INI DESC";
															$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
															
															$count=0;
															while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery)){														  
																$count++;	
																echo"
																	<tr>
																	  <td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(".$count.")'></td>
																	  <td>".$qrBuscaModulos['COD_MES']."</td>
																	  <td>".fnDataShort($qrBuscaModulos['DAT_INI'])."</td>
																	  <td>".fnDataShort($qrBuscaModulos['DAT_FIM'])."</td>
																	</tr>
																	<input type='hidden' id='ret_COD_MES_".$count."' value='".$qrBuscaModulos['COD_MES']."'>
																	<input type='hidden' id='ret_DAT_INI_".$count."' value='".fnDataShort($qrBuscaModulos['DAT_INI'])."'>
																	<input type='hidden' id='ret_DAT_FIM_".$count."' value='".fnDataShort($qrBuscaModulos['DAT_FIM'])."'>
																	"; 
																  }											

														?>
														
													</tbody>

												</table>
												
											</div> 
										
										</form>								
									
										<div class="push10"></div>
										
											
									
									</div>								
								
								</div>

							</div><!-- fim Portlet -->

						</div>

					</div>				
						
					<div class="push20"></div>

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
			
			$("#DAT_INI_GRP").on("dp.change", function (e) {
				$('#DAT_FIM_GRP').data("DateTimePicker").minDate(e.date);
			});
			
			$("#DAT_FIM_GRP").on("dp.change", function (e) {
				$('#DAT_INI_GRP').data("DateTimePicker").maxDate(e.date);
			});

		});
		
		function retornaForm(index){
			$("#formulario #COD_MES").val($("#ret_COD_MES_"+index).val());
			$("#formulario #DAT_INI").val($("#ret_DAT_INI_"+index).val());
			$("#formulario #DAT_FIM").val($("#ret_DAT_FIM_"+index).val());
			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');						
		}
		
	</script>	