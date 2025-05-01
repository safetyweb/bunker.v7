<?php
	
	//require('sendinblue/Email.php');
	
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
			
			$cod_comunic = fnLimpaCampoZero($_REQUEST['COD_COMUNIC']);
			$cod_empresa = fnLimpaCampo($_REQUEST['COD_EMPRESA']);
			$cod_campanha = fnLimpaCampo($_REQUEST['COD_CAMPANHA']);
			$cod_comunicacao = fnLimpaCampoZero($_REQUEST['COD_COMUNICACAO']);
			$cod_tipcomu = 1; //tipo email transacional -- comunicacao_tipo
			$des_texto_sms = fnLimpaCampo($_REQUEST['DES_TEXTO_SMS']);
			$cod_disparo = fnLimpaCampo($_REQUEST['COD_DISPARO']);
			$cod_modmail = fnLimpaCampo($_REQUEST['COD_MODMAIL']);	
			$cod_bancovar = fnLimpaCampo($_REQUEST['COD_BANCOVAR']);	 
			$cod_program = fnLimpaCampoZero($_REQUEST['COD_PROGRAM']);
			$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
			$nom_empresa = fnLimpaCampo($_REQUEST['NOM_EMPRESA']);
			$cod_ctrlenv = fnLimpaCampoZero($_REQUEST['COD_CTRLENV']);

			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
			
			if ($opcao != ''){

				$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
				
				$sql = "CALL SP_ALTERA_COMUNICACAO_MODELO (
				 '".$cod_comunic."', 
				 '".$cod_empresa."', 
				 '".$cod_campanha."', 
				 '".$cod_comunicacao."', 
				 '".$cod_tipcomu."', 
				 '".$des_texto_sms."', 
				 '".$cod_bancovar."', 
				 '".$cod_usucada."', 
				 '".$cod_disparo."', 
				 '".$cod_modmail."', 
				 '".$cod_ctrlenv."', 
				 '".$opcao."'    
				) ";
				
				// fnEscreve($sql);
				$arrayQuery = mysqli_query(connTemp($cod_empresa,""),trim($sql)) or die(mysqli_error());	
				$row = mysqli_fetch_array($arrayQuery);

				if($opcao == 'CAD'){
					$cod_comunic = $row["ULTIMO_CODIGO"];
				}
				
				
				$sql = "CALL SP_ALTERA_COMUNICACAO_EMPRESAS (
				 ".$cod_empresa.", 
				 ".$cod_comunic.",
				 ".$cod_tipcomu.",
				 '".$opcao."'    
				) ";		
				
				//fnEscreve($sql);
				//fnTestesql($connAdm->connAdm(),$sql);
				mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
				
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
		$sql = "SELECT COD_EMPRESA, NOM_EMPRESA FROM empresas where COD_EMPRESA = '".$cod_empresa."' ";
		//fnEscreve($sql);
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
		
		if (isset($qrBuscaEmpresa)){
			$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
			$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
			
			//liberação das abas
			$abaPersona	= "S";
			$abaVantagem = "S";
			$abaRegras = "S";
			$abaComunica = "S";
			$abaAtivacao = "N";
			$abaResultado = "N";

			//$abaPersonaComp = "completed ";
			$abaPersonaComp = "active ";
			$abaCampanhaComp = "active ";
			$abaRegrasComp = "completed ";
			$abaComunicaComp = "completed ";
			$abaResultadoComp = "";
			//revalidada na aba de regras	
			$abaAtivacaoComp = "";
			
		}
												
	}else {
		$cod_empresa = 0;		
		//fnEscreve('entrou else');
	}
	
	//busca dados da campanha
	$cod_campanha = fnDecode($_GET['idc']);	
	$sql = "SELECT * FROM CAMPANHA where COD_CAMPANHA = '".$cod_campanha."' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
	$qrBuscaCampanha = mysqli_fetch_assoc($arrayQuery);
	
	if (isset($qrBuscaCampanha)){
		$log_ativo = $qrBuscaCampanha['LOG_ATIVO'];
		$des_campanha = $qrBuscaCampanha['DES_CAMPANHA'];
		$abr_campanha = $qrBuscaCampanha['ABR_CAMPANHA'];
		$des_icone = $qrBuscaCampanha['DES_ICONE'];
		$tip_campanha = $qrBuscaCampanha['TIP_CAMPANHA'];				
		$log_realtime = $qrBuscaCampanha['LOG_REALTIME'];
		
	}	
 		
	//busca dados do tipo da campanha
	$sql = "SELECT * FROM TIPOCAMPANHA where COD_TPCAMPA = '".$tip_campanha."' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
	$qrBuscaTpCampanha = mysqli_fetch_assoc($arrayQuery);
	
	if (isset($qrBuscaTpCampanha)){
		$nom_tpcampa = $qrBuscaTpCampanha['NOM_TPCAMPA'];
		$abv_tpcampa = $qrBuscaTpCampanha['ABV_TPCAMPA'];
		$des_iconecp = $qrBuscaTpCampanha['DES_ICONE'];
		$label_1 = $qrBuscaTpCampanha['LABEL_1'];
		$label_2 = $qrBuscaTpCampanha['LABEL_2'];
		$label_3 = $qrBuscaTpCampanha['LABEL_3'];
		$label_4 = $qrBuscaTpCampanha['LABEL_4'];
		$label_5 = $qrBuscaTpCampanha['LABEL_5'];
		
	}  

	//busca dados da regra 
	$sql = "SELECT * FROM CAMPANHAREGRA where COD_CAMPANHA = '".$cod_campanha."' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
	$qrBuscaTpCampanha = mysqli_fetch_assoc($arrayQuery);
	
	if (isset($arrayQuery)){
		$cod_persona = $qrBuscaTpCampanha['COD_PERSONA'];
		if (!empty($cod_persona)) {
			$tem_personas = "sim";
		} else {$tem_personas = "nao";}
		$pct_vantagem = $qrBuscaTpCampanha['PCT_VANTAGEM'];
		$qtd_vantagem = $qrBuscaTpCampanha['QTD_VANTAGEM'];
		$qtd_resultado = $qrBuscaTpCampanha['QTD_RESULTADO'];
		$nom_vantagem = $qrBuscaTpCampanha['NOM_VANTAGE'];
		$num_pessoas = $qrBuscaTpCampanha['NUM_PESSOAS'];
		$cod_vantage = $qrBuscaTpCampanha['COD_VANTAGE'];

	}
	
	//fnMostraForm();	
	//fnEscreve($num_minresg);
	
	//fnEscreve($tip_campanha);

?>

<link rel="stylesheet" href="css/widgets.css" />
   
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
									
									<?php 
									//$formBack = "1169";
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
									
									<?php $abaCampanhas = 1169; include "abasCampanhasConfig.php"; ?>
									
									<div class="push30"></div>
		
									<div class="login-form">
									
										<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
											<!--
											<fieldset>
												<legend>Banco de Variáveis</legend>
												
												<input type="hidden" name="form" value="test" id="frm-test-elm-0" />
												<div class="row">
												
															
													<div class="col-sm-12 variaveis">
													
														<?php
															/*
															$sql = "select * from VARIAVEIS order by NUM_ORDENAC";
															$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
															
															while ($qrBuscaFases = mysqli_fetch_assoc($arrayQuery))
															  {	
																echo"
																	<span class='button-checkbox'>
																		<button type='button' class='btn btn-xs' data-color='info' style='margin: 0 4px 7px'>".$qrBuscaFases['ABV_BANCOVAR']."</button>
																		<input type='checkbox' name='".$qrBuscaFases['COD_BANCOVAR']."' id='".$qrBuscaFases['COD_BANCOVAR']."' class='hidden' value='S' />
																	</span>
																	"; 
																  }											
														*/
														?>													

													</div>
													
													<div class="push10"></div>

												

												</div>

											</fieldset>										
											-->
											
											<div class="push10"></div>
										
											<fieldset>
												<legend>Dados Gerais</legend> 
												
												<div class="row">
												
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label required">Código</label>
															<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_COMUNIC" id="COD_COMUNIC" value="">
														</div>
													</div>
													
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label required">Empresa</label>
															<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa ?>">
															<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">
														</div>														
													</div>	
													
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label required">Campanha</label>
															<input type="text" class="form-control input-sm leitura" readonly="readonly" name="DES_CAMPANHA" id="DES_CAMPANHA" value="<?php echo $des_campanha ?>">
														</div>														
													</div>	
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Tipo do Programa</label>
															<div class="push10"></div>
															<span class="fa <?php echo $des_iconecp; ?>"></span>  <b><?php echo $nom_tpcampa; ?> (<?php echo $nom_vantagem; ?>) </b>
														</div>														
													</div>
													
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Pessoas Atingidas</label>
															<div class="push10"></div>
															<span class="fa fa-users"></span>&nbsp;  <?php echo number_format ($num_pessoas,0,",","."); ?>
														</div>														
													</div>
													
												</div>

										</fieldset>
									
										<div class="push20"></div>
										
										<fieldset>
											<legend>Dados do e-Mail</legend> 
															
												<div class="row">
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label required">Momento da Comunicação </label>
																<select data-placeholder="Selecione o grupo" name="COD_COMUNICACAO" id="COD_COMUNICACAO" class="chosen-select-deselect requiredChk" required >
																	<option value="">&nbsp;</option>											  
																	<?php																	
																		$sql = "select * from comunicacao where tip_campanha = 0 order by DES_COMUNICACAO ";
																		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
																		
																		//tipos genéricos de momento
																		while ($qrListaComunica = mysqli_fetch_assoc($arrayQuery))
																		  {																			
																			?>
																				<option value="<?php echo $qrListaComunica['COD_COMUNICACAO'] ?>"><?php echo $qrListaComunica['DES_COMUNICACAO'] ?></option> 
																																								
																			<?php 
																			  
																		  }										
																		?>

																	<?php
																		if ($tip_campanha == 20) {																	
																		$sql = "select * from comunicacao where tip_campanha  = 20 order by DES_COMUNICACAO ";
																		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
																		
																		//campanhas com cupom
																		while ($qrListaComunica = mysqli_fetch_assoc($arrayQuery))
																		  {																			
																			?>
																				<option value="<?php echo $qrListaComunica['COD_COMUNICACAO'] ?>"><?php echo $qrListaComunica['DES_COMUNICACAO'] ?></option> 
																																								
																			<?php 
																			  
																		  }
																		}
																		?>

																		
																</select>	
															<div class="help-block with-errors"></div>
														</div>
													</div>
												
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label required">Momento do Disparo da Comunicação </label>
																<select data-placeholder="Selecione o grupo" name="COD_DISPARO" id="COD_DISPARO" class="chosen-select-deselect requiredChk" required>
																	<option value="0">&nbsp;</option>
																	<option value="1">Imediatamente</option>																	
																	<?php 	
                                                                                                                                              
																		$sql = "select COD_DISPARO,DES_ABREVIA from COMUNICACAO_DISPARO";
																		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
                                                                        $qrListaDisparo = mysqli_fetch_assoc($arrayQuery);
                                                                                                                                             
																		while ($qrListaDisparo = mysqli_fetch_assoc($arrayQuery))
																		  {	

																			//echo "<pre>";
																			//print_r($qrListaDisparo);
																			//echo "</pre>";
																		  
																			echo"
																				  <option value='".$qrListaDisparo['COD_DISPARO']."'>".$qrListaDisparo['DES_ABREVIA']."</option> 
																				"; 
																		  }											
																	?>	
																</select>	
															<div class="help-block with-errors"></div>
														</div>
													</div>
													
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label required">Controle de Envio </label>
																<select data-placeholder="Selecione o grupo" name="COD_CTRLENV" id="COD_CTRLENV" class="chosen-select-deselect requiredChk" required>
																	<option value="">&nbsp;</option>
																	<option value="0">Enviar a cada evento</option>											  
																	<option value="1">1 vez no dia</option>											  
																	<option value="7">1 vez na semana</option>											  
																	<option value="30">1 vez ao mês</option>											  
																</select>	
															<div class="help-block with-errors"></div>
														</div>
													</div>

													
													<!-- sendi in blue
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label required">Modelo do e-Mail</label>
																<div id="divId_sub">
																<select data-placeholder="Selecione o sub grupo" name="COD_MODMAIL" id="COD_MODMAIL" class="chosen-select-deselect requiredChk" required>
																	<option value="0">&nbsp;</option>					
																	<?php
																	/*
																		$sql = "select DES_AUTHKEY from CONFIGURACAO_ACESSO where COD_EMPRESA = $cod_empresa";
																		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());	
																		$row = mysqli_fetch_assoc($arrayQuery);
																	
																		$email = new Email();
																		$email->setApiKey($row['DES_AUTHKEY']); 
																		$data = array( "");

																		$array = $email->mailin->get_campaigns_v2($data); 
																		
																		foreach ($array as &$value) {
																			foreach ($value as &$value2) {
																				foreach ($value2 as &$campanha) {
																					echo $campanha['id'];
																					echo $campanha['campaign_name'] . '<br>';
																					echo '<option value='.$campanha['id'].'>'.$campanha['campaign_name'].'</option>';
																				}																
																			}															
																		}
																	*/	
																	?>
																</select>	
																</div>	
															<div class="help-block with-errors"></div>
														</div>
													</div>
													
													
													<div class="col-md-2">
														<div class="push13"></div>
														<div class="push5"></div>
														<a href="http://smtp.automacao.marka.ws/" target="_blank" name="GERENCIAR" id="GERENCIAR" class="btn btn-info btn-sm btn-block"><i class="fa fa-cogs" aria-hidden="true"></i>&nbsp; Gerenciar Modelos</a>														
													</div>													
													-- >
													
													<!-- bunker mail -->
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label required">Modelo do e-Mail</label>
																<div id="divId_sub">
																<select data-placeholder="Selecione o sub grupo" name="COD_MODMAIL" id="COD_MODMAIL" class="chosen-select-deselect requiredChk" required>
																	<option value="0">&nbsp;</option>

																	<?php 
																		$sql = "SELECT * FROM TEMPLATE_EMAIL WHERE cod_empresa = $cod_empresa ORDER BY NOM_TEMPLATE";
																				
																		$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
																		
																		$count=0;
																		while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery))
																		  {														  
																			$count++;	
																			?>																			
																			<option value="<?php echo $qrBuscaModulos['COD_TEMPLATE']; ?>"><?php echo $qrBuscaModulos['NOM_TEMPLATE']; ?></option>
																																			
																			<?php			
																			  }											
																	?>		
																		
																</select>	
																</div>	
															<div class="help-block with-errors"></div>
														</div>
													</div>
													
													<div class="col-md-2">
														<div class="push13"></div>
														<div class="push5"></div>
														<a href="action.do?mod=<?php echo fnEncode(1407)?>&id=<?php echo fnEncode($cod_empresa)?>&idc=<?php echo fnEncode($cod_campanha)?>" target="_blank" name="GERENCIAR" id="GERENCIAR" class="btn btn-info btn-sm btn-block"><i class="fa fa-cogs" aria-hidden="true"></i>&nbsp; Gerenciar Modelos</a>														
													</div>													
													
													
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
										
										<input type="hidden" name="COD_CAMPANHA" id="COD_CAMPANHA" value="<?php echo $cod_campanha; ?>">
										<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
										<input type="hidden" name="COD_BANCOVAR" id="COD_BANCOVAR" value="">
										
										<input type="hidden" name="opcao" id="opcao" value="">
										<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
										<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">		
										
										<div class="push5"></div> 
										
										</form>
										
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
													  <th>Momento</th>
													  <th>Disparo</th>
													</tr>
												  </thead>
												<tbody>
					  
												<?php 												
													$sql = "select A.DES_COMUNICACAO, B.DES_ABREVIA, COMUNICACAO_MODELO.* from COMUNICACAO_MODELO
													LEFT JOIN COMUNICACAO A ON A.COD_COMUNICACAO = COMUNICACAO_MODELO.COD_COMUNICACAO
													LEFT JOIN COMUNICACAO_DISPARO B ON B.COD_DISPARO = COMUNICACAO_MODELO.COD_DISPARO
													where COD_CAMPANHA = '$cod_campanha' AND COD_TIPCOMU = '1'  AND COD_EXCLUSA = 0 
													ORDER BY COD_COMUNICACAO
													";
													
													//fnEscreve($sql);
													// $arrayQuery = mysqli_query($connAdm->connAdm(),trim($sql)) or die(mysqli_error()); -- Eu que copiei a conn e ja deixei comentado (Ricardo). Vai ter ajuste.
													$arrayQuery = mysqli_query(connTemp($cod_empresa,""),trim($sql)) or die(mysqli_error());
													
													$count=0;
													
													while ($qrBuscaComunicacao = mysqli_fetch_assoc($arrayQuery))
													  {														  
														$count++;														
														
														echo"
															<tr>
															  <td align='center'><input type='radio' name='radio1' onclick='retornaForm(".$count.")'></th>
															  <td>".$qrBuscaComunicacao['COD_COMUNIC']."</td>
															  <td>".$qrBuscaComunicacao['DES_COMUNICACAO']."</td>
															  <td>".$qrBuscaComunicacao['DES_ABREVIA']."</td>
															</tr>
															<input type='hidden' id='ret_COD_COMUNIC_".$count."' value='".$qrBuscaComunicacao['COD_COMUNIC']."'>
															<input type='hidden' id='ret_COD_MODMAIL_".$count."' value='".$qrBuscaComunicacao['COD_MODMAIL']."'>
															<input type='hidden' id='ret_COD_COMUNICACAO_".$count."' value='".$qrBuscaComunicacao['COD_COMUNICACAO']."'>
															<input type='hidden' id='ret_COD_DISPARO_".$count."' value='".$qrBuscaComunicacao['COD_DISPARO']."'>
															<input type='hidden' id='ret_COD_BANCOVAR_".$count."' value='".$qrBuscaComunicacao['COD_BANCOVAR']."'>
															<input type='hidden' id='ret_COD_CTRLENV_".$count."' value='".$qrBuscaComunicacao['COD_CTRLENV']."'>
															"; 
														  }											

												?>
													
												</tbody>
												</table>
																
												</form>

											</div>
											
										</div>										
									
									<div class="push30"></div>
									
									</div>								
								
								</div>
							</div>
							<!-- fim Portlet -->
						</div>
						
					</div>
	
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
					
					<div class="push20"></div> 
					
	
	
	<style>
	.button-checkbox .btn {
		font-size: 11px;
	}
	
	.btn-default {
		color: #ffffff;
		background-color: #c7d0d1;
		border-color: #c7d0d1;
		
	}	
	
	.btn-default:hover {
		color: #ffffff;
		background-color: #c7d0d1;
		border-color: #c7d0d1;
		
	}
	
	.button-checkbox .btn-info {
		color: #ffffff;
		background-color: #52a7e0 !important;
		border-color: #52a7e0 !important;
		box-shadow: none;
	}	
	
	.btn-default:focus, .btn-default.focus {
		color: #ffffff;
		background-color: #c7d0d1;
		border-color: #c7d0d1;	
		outline-color: #c7d0d1;	
	}
	

	
	</style>
	
	<script type="text/javascript">	
	
		$(document).ready(function(){
			
			//chosen
			$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
			$('#formulario').validator();
			
		});	
	
	
		$(function () {
			$('.button-checkbox').each(function () {

				// Settings
				var $widget = $(this),
					$button = $widget.find('button'),
					$checkbox = $widget.find('input:checkbox'),
					color = $button.data('color'),
					settings = {
						on: {
							//icon: 'glyphicon glyphicon-check'
							icon: 'fa fa-check fa-1x'
						},
						off: {
							//icon: 'glyphicon glyphicon-unchecked'
							icon: 'fa fa-times fa-1x'
						}
					};

				// Event Handlers
				$button.on('click', function () {
					$checkbox.prop('checked', !$checkbox.is(':checked'));
					$checkbox.triggerHandler('change');
					updateDisplay();
				});
				$checkbox.on('change', function () {
					updateDisplay();
				});

				// Actions
				function updateDisplay() {
					var isChecked = $checkbox.is(':checked');

					// Set the button's state
					$button.data('state', (isChecked) ? "on" : "off");

					// Set the button's icon
					$button.find('.state-icon')
						.removeClass()
						.addClass('state-icon ' + settings[$button.data('state')].icon);

					// Update the button's color
					if (isChecked) {
						$button
							.removeClass('btn-default')
							.addClass('btn-' + color + ' active');
					}
					else {
						$button
							.removeClass('btn-' + color + ' active')
							.addClass('btn-default');
					}
				}

				// Initialization
				function init() {

					updateDisplay();

					// Inject the icon if applicable
					if ($button.find('.state-icon').length == 0) {
						$button.prepend('<i class="state-icon ' + settings[$button.data('state')].icon + '"></i> ');
					}
				}
				init();
			});
			
			$('#formulario').submit(function(e) {
				var arrayVariaveis = "";
				$(".variaveis > span").each(function( index ) {	
					if($(this).children().hasClass('active')){
						if(arrayVariaveis === ''){
							arrayVariaveis += $(this).children().next().attr('id');							
						}else{
							arrayVariaveis += ',' + $(this).children().next().attr('id');
						}
					}
				});	
				
				$('#COD_BANCOVAR').val(arrayVariaveis);
			});
	

});	
	
		
	function retornaForm(index){
		$("#formulario #COD_COMUNIC").val($("#ret_COD_COMUNIC_"+index).val());
		$("#formulario #COD_COMUNICACAO").val($("#ret_COD_COMUNICACAO_"+index).val()).trigger("chosen:updated");
		$("#formulario #COD_DISPARO").val($("#ret_COD_DISPARO_"+index).val()).trigger("chosen:updated");
		$("#formulario #COD_MODMAIL").val($("#ret_COD_MODMAIL_"+index).val()).trigger("chosen:updated");
		$("#formulario #COD_CTRLENV").val($("#ret_COD_CTRLENV_"+index).val()).trigger("chosen:updated");
		$('#formulario').validator('validate');			
		$("#formulario #hHabilitado").val('S');

		carregaBancoVar(index);
	}
	
	function carregaBancoVar(index){
		
		//limpar
		$(".variaveis > span").each(function( index ) {
			if($(this).children().hasClass('active')){
				$(this).children().removeClass('active');	
				$(this).children().toggleClass('btn-info btn-default');	
				$(this).children().children().toggleClass('fa-check fa-times');				
			}
		});		
		
		var bancoVar = $("#ret_COD_BANCOVAR_"+index).val().split(',');
		
		for(var i = 0; i < bancoVar.length; i++){
			$(".variaveis > span").each(function( index ) {
				if($(this).children().next().attr('id') == bancoVar[i]){
					$(this).children().addClass('active');
					$(this).children().toggleClass('btn-default btn-info');
					$(this).children().children().toggleClass('fa-times fa-check');
				}
			});
		}
	}
		
	</script>	