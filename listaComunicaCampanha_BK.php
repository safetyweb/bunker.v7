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
			$abaComunica = "S";
			$abaAtivacao = "N";
			$abaResultado = "N";

			$abaPersonaComp = "active ";
			$abaCampanhaComp = "active";
			$abaRegrasComp = "completed ";
			$abaComunicaComp = "completed ";
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

<style>
	.fa-1dot5x{
		font-size: 45px;
		margin-top: 7px;
		margin-bottom: 7px;
	}
	.tile{
		border: none;
	}
</style>
   
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
									$formBack = "1048";
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
									
									<?php $abaCampanhas = 1254; include "abasCampanhasConfig.php"; ?>
									
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
															<span class="fal fa-users"></span>&nbsp;  <?php echo fnValor($num_pessoas,0); ?>
														</div>														
													</div>
													
												</div>

										</fieldset>
									
										<div class="push50"></div>
										
										<div class="row">
										
											<h3 style="margin: 0 0 20px 15px;">Configure a comunicação <strong>transacional</strong> da sua campanha</h3>

											<div class="col-md-2">                         
												<a href="action.php?mod=<?php echo fnEncode(1170)?>&id=<?php echo fnEncode($cod_empresa);?>&idc=<?php echo fnEncode($cod_campanha);?>" class="tile shadow" style="color: #2c3e50;">
													<span class="fal fa-envelope fa-1dot5x"></span>
													<p style="height: 40px;">e-Mail</p>                            
												</a>                        
											</div>
											
											<div class="col-md-2">
												<a href="action.php?mod=<?php echo fnEncode(1171)?>&id=<?php echo fnEncode($cod_empresa);?>&idc=<?php echo fnEncode($cod_campanha);?>" class="tile shadow" style="color: #2c3e50;">
													<span class="fal fa-comment-alt fa-1dot5x"></span>
													<p style="height: 40px;">SMS</p>                            
												</a>                        
											</div>	
											
											<div class="col-md-2">
											<div class="disabledBlock"></div>		
												<a href="action.php?mod=<?php echo fnEncode(1038)?>&id=<?php echo fnEncode($cod_empresa);?>" class="tile shadow" style="color: #2c3e50;">
													<span class="fab fa-whatsapp-square fa-1dot5x"></span>
													<p style="height: 40px;">Whats App</p>                            
												</a>                        
											</div>											
											
											<div class="col-md-2">
												<a href="action.php?mod=<?php echo fnEncode(1177)?>&id=<?php echo fnEncode($cod_empresa);?>&idc=<?php echo fnEncode($cod_campanha);?>" class="tile shadow" style="color: #2c3e50;">
													<span class="fal fa-file-alt fa-1dot5x"></span>
													<p style="height: 40px;">Mensagem PDV</p>                            
												</a>                        
											</div>	
											
											<div class="col-md-2">
											<div class="disabledBlock"></div>		
												<a href="action.php?mod=<?php echo fnEncode(1038)?>&id=<?php echo fnEncode($cod_empresa);?>" class="tile shadow" style="color: #2c3e50;">
													<span class="fal fa-phone fa-1dot5x"></span>
													<p style="height: 40px;">Telefone</p>                            
												</a>                        
											</div>

										</div>										
																	
										<div class="push30"></div>
										
										<div class="row">
										
											<h3 style="margin: 0 0 20px 15px;">Configure a comunicação <strong>em massa</strong> da sua campanha</h3>

											<div class="col-md-2">  
											<div class="disabledBlock"></div>											
												<a href="action.php?mod=<?php echo fnEncode(1172)?>&id=<?php echo fnEncode($cod_empresa);?>&idc=<?php echo fnEncode($cod_campanha);?>" class="tile shadow" style="color: #2c3e50;">
													<span class="fal fa-envelope fa-1dot5x"></span>
													<p style="height: 40px;">e-Mail</p>                            
												</a>                        
											</div>
											
											<div class="col-md-2">
											<div class="disabledBlock"></div>											
												<a href="action.php?mod=<?php echo fnEncode(1038)?>&id=<?php echo fnEncode($cod_empresa);?>" class="tile shadow" style="color: #2c3e50;">
													<span class="fal fa-comment-alt fa-1dot5x"></span>
													<p style="height: 40px;">SMS</p>                            
												</a>                        
											</div>	
											
											<div class="col-md-2">
											<div class="disabledBlock"></div>		
												<a href="action.php?mod=<?php echo fnEncode(1038)?>&id=<?php echo fnEncode($cod_empresa);?>" class="tile shadow" style="color: #2c3e50;">
													<span class="fab fa-whatsapp-square fa-1dot5x"></span>
													<p style="height: 40px;">Whats App</p>                            
												</a>                        
											</div>	

											<div class="col-md-2">
												<a href="action.php?mod=<?php echo fnEncode(1254)?>&id=<?php echo fnEncode($cod_empresa);?>&idc=<?php echo fnEncode($cod_campanha);?>" class="tile shadow" style="color: #2c3e50;">
													<span class="fal fa-list fa-1dot5x"></span>
													<p style="height: 40px;">Pesquisa</p>                            
												</a>                        
											</div>

										</div>	
										
										<div class="push20"></div>
										
										<h4 style="margin: 0 0 20px 0;">Listas de <strong>comunicação em massa</strong> já geradas</h4>
										
										<table class="table table-bordered table-striped table-hover">
										  <thead>
											<tr>
											  <th>Tipo</th>
											  <th>Nome da Lista</th>
											  <th>Data de Criação</th>
											  <th>Última Geração</th>
											  <th class="text-center"><i class='fas fa-users'></i></th>
											  <th class="text-center">Schedule</th>
											  <th class="text-center">Ativa</th>
											  <th></th>
											  <th></th>
											</tr>
										  </thead>
										<tbody>
										  
											<tr>
											  <td class="text-center"><span class="fal fa-commenting-o"></span>SMS</td>
											  <td><small>Hábito de Consumo Excluídos</td>
											  <td><small>31/12/2017 09:03:22</td>
											  <td><small>31/07/2017 22:01:00</td>
											  <td class="text-right"><small>234.321</td>
											  <td class='text-center'></td>
											  <td class='text-center'><i class="fas fa-check-square" aria-hidden="true"></i></td>
											  <td class='text-center'>
												<a class='btn btn-xs btn-info' href='#'><i class='fas fa-pencil'></i> Editar </a>
											  </td>
											  <td class='text-center'>
												
											  </td>
											</tr>
											
											<tr>
											  <td class="text-center"><span class="fal fa-envelope-o"></span> e-Mail</td>
											  <td><small>Hábito de Consumo Excluídos</td>
											  <td><small>31/12/2017 09:03:22</td>
											  <td><small>31/07/2017 22:01:00</td>
											  <td class="text-right"><small>1.154.321</td>
											  <td class='text-center'><i class="fal fa-clock-o" aria-hidden="true"></td>
											  <td class='text-center'><i class="fal fa-check-square-o" aria-hidden="true"></i></td>
											  <td class='text-center'>
												<a class='btn btn-xs btn-info' href='#'><i class='fal fa-pencil'></i> Editar </a>
											  </td>
											  <td class='text-center'>
												<!--<a class='btn btn-xs btn-success' href='http://automacao.marka.ws/login' target="_blank"><i class='fal fa-cogs'></i> Acessar Gerenciado fa-1dot5xr </a>-->
												<a href="action.do?mod=<?php echo fnEncode(1407)?>&id=<?php echo fnEncode($cod_empresa)?>&idc=<?php echo fnEncode($cod_campanha)?>" target="_blank" name="GERENCIAR" id="GERENCIAR" class="btn btn-info btn-xs btn-block"><i class="fal fa-cogs" aria-hidden="true"></i>&nbsp; Gerenciar Modelos</a>																										
												
											  </td>
											</tr>
													
										</tbody>
										</table>
										
										<div class="push30"></div>
										
										<input type="hidden" name="COD_RESGATE" id="COD_RESGATE" value="<?php echo $cod_resgate ?>">
										<input type="hidden" name="opcao" id="opcao" value="">
										<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
										<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">		
										
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
	
	<script type="text/javascript">
		
		function retornaForm(index){
			$("#formulario #COD_GRUPOTR").val($("#ret_COD_GRUPOTR_"+index).val());
			$("#formulario #DES_GRUPOTR").val($("#ret_DES_GRUPOTR_"+index).val());
			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');						
		}
		
	</script>	