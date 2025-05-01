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
			
			$cod_resgate = fnLimpaCampoZero($_REQUEST['COD_RESGATE']);
			$tip_momresg = fnLimpaCampo($_REQUEST['TIP_MOMRESG']);
			$num_diasrsg = fnLimpaCampoZero($_REQUEST['NUM_DIASRSG']);
			$qtd_validad = fnLimpaCampoZero($_REQUEST['QTD_VALIDAD']);
            $tip_diasvld = fnLimpaCampo($_REQUEST['TIP_DIASVLD']);
			$qtd_inativo = fnLimpaCampoZero($_REQUEST['QTD_INATIVO']);
			$num_inativo = fnLimpaCampo($_REQUEST['NUM_INATIVO']);
			$num_minresg = fnLimpaCampo($_REQUEST['NUM_MINRESG']);
			$pct_maxresg = fnLimpaCampo($_REQUEST['PCT_MAXRESG']);
			$qtd_fraudes = fnLimpaCampoZero($_REQUEST['QTD_FRAUDES']);
			$tip_fraudes = fnLimpaCampo($_REQUEST['TIP_FRAUDES']);
			$tip_libfunc = fnLimpaCampo($_REQUEST['TIP_LIBFUNC']);
			$tip_libclie = fnLimpaCampo($_REQUEST['TIP_LIBCLIE']);
			$tip_relinfo = fnLimpaCampo($_REQUEST['TIP_RELINFO']);
			$hor_relinfo = fnLimpaCampo($_REQUEST['HOR_RELINFO']);
			
			//$cod_mailusu = fnLimpaCampo($_REQUEST['COD_MAILUSU']);			
			//array das usuários email
			if (isset($_POST['COD_MAILUSU'])){
				$Arr_COD_MAILUSU = $_POST['COD_MAILUSU'];
				//print_r($Arr_COD_MAILUSU);			 
			   for ($i=0;$i<count($Arr_COD_MAILUSU);$i++) 
			   { 
				$cod_mailusu = $cod_mailusu.$Arr_COD_MAILUSU[$i].",";
			   } 			   
			   $cod_mailusu = substr($cod_mailusu,0,-1);				
			}else{$cod_mailusu = "0";}

			//$cod_acesusu = fnLimpaCampo($_REQUEST['COD_ACESUSU']);
			//array das usuários de acesso
			if (isset($_POST['COD_ACESUSU'])){
				$Arr_COD_ACESUSU = $_POST['COD_ACESUSU'];
				//print_r($Arr_COD_ACESUSU);			 
			   for ($i=0;$i<count($Arr_COD_ACESUSU);$i++) 
			   { 
				$cod_acesusu = $cod_acesusu.$Arr_COD_ACESUSU[$i].",";
			   } 			   
			   $cod_acesusu = substr($cod_acesusu,0,-1);				
			}else{$cod_acesusu = "0";}
		
			$cod_program = fnLimpaCampoZero($_REQUEST['COD_PROGRAM']);
			$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
			$nom_empresa = fnLimpaCampo($_REQUEST['NOM_EMPRESA']);

			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
			
			if ($opcao != ''){

				$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
				
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
			$abaComunica = "N";
			$abaAtivacao = "N";
			$abaResultado = "N";

			$abaPersonaComp = "completed ";
			$abaVantagemComp = "completed ";
			$abaRegrasComp = "completed ";
			$abaComunicaComp = "active";
			$abaAtivacaoComp = "";
			$abaResultadoComp = "";				
			
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
	
	//fnMostraForm();	
	//fnEscreve($num_minresg);

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
										
											<fieldset>
												<legend>Dados Gerais</legend> 
												
												<div class="row">
												
													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label required">Código</label>
															<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_PROGRAM" id="COD_PROGRAM" value="<?php echo $cod_campanha ?>">
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
															<label for="inputName" class="control-label">Momento da Comunicação </label>
																<select data-placeholder="Selecione o grupo" name="COD_CATEGOR" id="COD_CATEGOR" class="chosen-select-deselect">
																	<option value="0">&nbsp;</option>											  
																	<option value="0">Cadastro do Cliente</option>											  
																	<option value="0">Compra</option>
																</select>	
															<div class="help-block with-errors"></div>
														</div>
													</div>											
													
													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Modelo do e-Mail</label>
																<div id="divId_sub">
																<select data-placeholder="Selecione o sub grupo" name="COD_SUBCATE" id="COD_SUBCATE" class="chosen-select-deselect">
																	<option value="0">&nbsp;</option>					
																	<option value="0">Modelo 1</option>					
																	<option value="0">Modelo 2</option>					
																</select>	
																</div>	
															<div class="help-block with-errors"></div>
														</div>
													</div>
													
													<div class="col-md-2">
														<div class="push13"></div>
														<div class="push5"></div>
														<a  href="" class="btn btn-info btn-sm"><i class="fa fa-cogs" aria-hidden="true"></i>&nbsp; Gerenciar Modelos</a>
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
										
										<input type="hidden" name="COD_CAMPAPROD" id="COD_CAMPAPROD" value="">
										<input type="hidden" name="COD_CAMPANHA" id="COD_CAMPANHA" value="<?php echo $cod_campanha; ?>">
										<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
										
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
													  <th class="bg-primary" width="40"></th>
													  <th class="bg-primary">Grupo</th>
													  <th class="bg-primary">Sub Grupo</th>
													  <th class="bg-primary">Fornecedor</th>
													  <th class="bg-primary"><?php echo $nom_tpcampa; ?> Normais</th>
													  <th class="bg-primary"><?php echo $nom_tpcampa; ?> Extras</th>
													  <th class="bg-primary">Ganho</th>
													</tr>
												  </thead>
												<tbody>
					  
												<?php 												
													$sql = "select CATEGORIA.DES_CATEGOR,SUBCATEGORIA.DES_SUBCATE, FORNECEDORMRKA.NOM_FORNECEDOR,CAMPANHAPRODUTO.* 
													from CAMPANHAPRODUTO
													LEFT JOIN CATEGORIA  ON CATEGORIA.COD_CATEGOR=CAMPANHAPRODUTO.COD_CATEGOR
													LEFT JOIN SUBCATEGORIA  ON SUBCATEGORIA.COD_SUBCATE=CAMPANHAPRODUTO.COD_SUBCATE
													LEFT JOIN FORNECEDORMRKA  ON FORNECEDORMRKA.COD_FORNECEDOR=CAMPANHAPRODUTO.COD_FORNECEDOR
													where CAMPANHAPRODUTO.COD_CAMPANHA = '".$cod_campanha."' AND COD_EXCLUSAO = 0 ORDER BY DES_CATEGOR, DES_SUBCATE, NOM_FORNECEDOR  												
													";													
													
													//fnEscreve($sql);
													$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
									
													$count=0;
													
													while ($qrBuscaCampanhaExtra = mysqli_fetch_assoc($arrayQuery))
													  {														  
														$count++;
														
														if ($qrBuscaCampanhaExtra['TIP_PONTUACAO'] == "ABS") { $tipoGanho = $nom_tpcampa; }
														else { $tipoGanho = "Percentual"; }
												
														echo"
															<tr>
															  <td align='center'><input type='radio' name='radio1' onclick='retornaForm(".$count.")'></th>
															  <td>".$qrBuscaCampanhaExtra['DES_CATEGOR']."</td>
															  <td>".$qrBuscaCampanhaExtra['DES_SUBCATE']."</td>
															  <td>".$qrBuscaCampanhaExtra['NOM_FORNECEDOR']."</td>
															  <td>".number_format ($qrBuscaCampanhaExtra['VAL_PONTUACAO'],2,",",".")."</td>
															  <td>".number_format ($qrBuscaCampanhaExtra['VAL_PONTOEXT'],2,",",".")."</td>
															  <td>".$tipoGanho."</td>
															</tr>
															<input type='hidden' id='ret_COD_CAMPAPROD_".$count."' value='".$qrBuscaCampanhaExtra['COD_CAMPAPROD']."'>
															<input type='hidden' id='ret_COD_CATEGOR_".$count."' value='".$qrBuscaCampanhaExtra['COD_CATEGOR']."'>
															<input type='hidden' id='ret_COD_SUBCATE_".$count."' value='".$qrBuscaCampanhaExtra['COD_SUBCATE']."'>
															<input type='hidden' id='ret_COD_FORNECEDOR_".$count."' value='".$qrBuscaCampanhaExtra['COD_FORNECEDOR']."'>
															<input type='hidden' id='ret_VAL_PONTUACAO_".$count."' value='".number_format ($qrBuscaCampanhaExtra['VAL_PONTUACAO'],2,",",".")."'>
															<input type='hidden' id='ret_VAL_PONTOEXT_".$count."' value='".number_format ($qrBuscaCampanhaExtra['VAL_PONTOEXT'],2,",",".")."'>
															<input type='hidden' id='ret_TIP_PONTUACAO_".$count."' value='".$qrBuscaCampanhaExtra['TIP_PONTUACAO']."'>
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
	
	<script type="text/javascript">
		
		function retornaForm(index){
			$("#formulario #COD_GRUPOTR").val($("#ret_COD_GRUPOTR_"+index).val());
			$("#formulario #DES_GRUPOTR").val($("#ret_DES_GRUPOTR_"+index).val());
			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');						
		}
		
	</script>	