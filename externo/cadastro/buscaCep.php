<?php 
require '../../_system/_functionsMain.php';
$_SESSION['SYS_COD_EMPRESA'] = 2;
?>

<html lang="pt">
    <head>
	
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,height=device-height,initial-scale=1.0"/>

	<title>Cadastro Externo</title>
	
	<?php
	$css_skin = "bootstrap.flatly.min.css";	
	?>
	
	<link href="https://adm.bunker.mk/css/<?php echo $css_skin ?>" rel="stylesheet">

	<?php

		if (isset($_SESSION["SYS_DES_CSSAUX"]) ){
		// fnEscreve($_SESSION["SYS_DES_CSSAUX"]);
	?>
		<link href="https://adm.bunker.mk/css/<?php echo $_SESSION[SYS_DES_CSSAUX] ?>" rel="stylesheet">
	<?php
	}

	?>

	<script src="https://adm.bunker.mk/js/jquery.min.js"></script>
	
	<!-- JQUERY-CONFIRM -->
	<link href="https://adm.bunker.mk/css/jquery-confirm.min.css" rel="stylesheet"/>
	
	<!-- extras -->
	<link href="https://adm.bunker.mk/css/jquery.webui-popover.min.css" rel="stylesheet" />
	<link href="https://adm.bunker.mk/css/chosen-bootstrap.css" rel="stylesheet" />
	<link href="https://adm.bunker.mk/css/fontawesome-pro-5.13.0-web/css/all.min.css" rel="stylesheet" type="text/css" />
	<link href="https://adm.bunker.mk/css/bootstrap.vertical-tabs.css" rel="stylesheet" />
	
    <!-- mmenu -->
	<link href="https://adm.bunker.mk/js/plugins/mmenu/jquery.mmenu.css" type="text/css" rel="stylesheet" />
	<link href="https://adm.bunker.mk/js/plugins/tablesorter/css/theme.bootstrap_4.min.css" type="text/css" rel="stylesheet" />
	
    <!-- complement -->
	<link href="https://adm.bunker.mk/css/default.css" rel="stylesheet" />
	<link href="https://adm.bunker.mk/css/checkMaster.css" rel="stylesheet" />
		
	<!--[if lt IE 9]><script src="js/ie8-responsive-file-warning.js"></script><![endif]-->
	<script src="https://adm.bunker.mk/js/plugins/ie-emulation-modes-warning.js"></script>
	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
	  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

	<!-- Favicons -->
	<link rel="icon" type="image/ico" rel="shortcut icon" href="https://adm.bunker.mk/images/favicon.ico"/>
	
	<style>
		
	<?php if ($popUp != "true"){ ?>
	body {		
		background: #f2f3f4;
		/*background: #f2f3f4;*/
	}
	<?php } else { ?>	
	body {		
		background: #fff;
	}
	<?php } ?>		
	</style>	
	<!-- Favicons -->
	<link rel="icon" href="images/favicon.ico">
	
    </head>
	
    <body>
	
    <div class="outContainer">
	
	
		<div class="containerfluid">

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

					$cod_estadof = fnLimpaCampo($_REQUEST['COD_ESTADOF']);
					$nom_cidade = fnLimpaCampo($_REQUEST['NOM_CIDADE']);
					$des_enderec = fnLimpaCampo($_REQUEST['DES_ENDEREC']);
					$num_cep = fnLimpaCampo(fnLimpaDoc($_REQUEST['NUM_CEP']));

					// fnEscreve($cod_estadof);
					// fnEscreve($nom_cidade);
					// fnEscreve($des_enderec);
					//fnEscreve($num_cep);

					$opcao = $_REQUEST['opcao'];
					$hHabilitado = $_REQUEST['hHabilitado'];
					$hashForm = $_REQUEST['hashForm'];
								
					if ($opcao != ''){				
						
						//mensagem de retorno
						switch ($opcao)
						{
							case 'CAD':
								$msgRetorno = "Consulta realizada com <strong>sucesso!</strong>";	
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
				
				if (isset($arrayQuery)){
					$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
					$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
				}
														
			}else {
				$cod_empresa = 0;		
				//fnEscreve('entrou else');
			}
		  
		?>
		<html lang="pt">
			<head>
			
			<meta charset="utf-8">
			<meta name="viewport" content="width=device-width,height=device-height,initial-scale=1.0"/>

			<title>Webtools</title>

			<link href="../../css/bootstrap.flatly.min.css" rel="stylesheet">
				<link href="../../css/bootstrap.flatly.min.css" rel="stylesheet">
				<script src="../../js/jquery.min.js"></script>
			
			<!-- JQUERY-CONFIRM -->
			<link href="../../css/jquery-confirm.min.css" rel="stylesheet"/>
			
			<!-- extras -->
			<link href="../../css/jquery.webui-popover.min.css" rel="stylesheet" />
			<link href="../../css/chosen-bootstrap.css" rel="stylesheet" />
			<link rel="stylesheet" type="text/css" href="../../css/fontawesome-pro-5.13.0-web/css/all.min.css" />
			<link href="../../css/bootstrap.vertical-tabs.css" rel="stylesheet" />
			
			<!-- mmenu -->
			<link href="../../js/plugins/mmenu/jquery.mmenu.css" type="text/css" rel="stylesheet" />
			<link href="../../js/plugins/tablesorter/css/theme.bootstrap_4.min.css" type="text/css" rel="stylesheet" />
			
			<!-- complement -->
			<link href="../../css/default.css" rel="stylesheet" />
			<link href="../../css/checkMaster.css" rel="stylesheet" />
				
			<!--[if lt IE 9]><script src="js/ie8-responsive-file-warning.js"></script><![endif]-->
			<script src="../../js/plugins/ie-emulation-modes-warning.js"></script>
			<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
			<!--[if lt IE 9]>
			  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
			  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
			<![endif]-->

			<!-- Favicons -->
			<link rel="icon" type="image/ico" rel="shortcut icon" href="../../images/favicon.ico"/>

			<!-- Favicons -->
			<link rel="icon" href="../../images/favicon.ico">
			
			</head>
			
			<body>
					
				<style>

				.alert .alert-link {
					text-decoration: none;
				}
				.alert:hover .alert-link:hover {
					text-decoration: underline;
				}

				.chosen-container{
					width: 100%!important;
				}

				.outContainer{
					padding-left: unset;
				}

				</style>
							<div class="push30"></div> 
														
							<div class="row">				
								
								<div class="col-md-12 margin-bottom-30">								
					
								<div class="portlet" style="padding: 0 20px 20px 20px;" >
								
									<div class="portlet-title">
										<div class="caption">
											<i class="far fa-handshake" style="font-size: 25px;"></i>
											<span class="text-primary">Cadastro Externo</span>
										</div>
											
									</div>
									
										<div class="portlet-body">
					
											<?php if ($msgRetorno <> '') { ?>	
											<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30" role="alert" id="msgRetorno">
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

																<div class="col-md-3">
																	<div class="form-group">
																		<label for="inputName" class="control-label">Estado</label>
																			<select data-placeholder="Selecione um estado" name="COD_ESTADOF" id="COD_ESTADOF" class="chosen-select-deselect">
																				<option value=""></option>					
																				<option value="AC">AC</option> 
																				<option value="AL">AL</option> 
																				<option value="AM">AM</option> 
																				<option value="AP">AP</option> 
																				<option value="BA">BA</option> 
																				<option value="CE">CE</option> 
																				<option value="DF">DF</option> 
																				<option value="ES">ES</option> 
																				<option value="GO">GO</option> 
																				<option value="MA">MA</option> 
																				<option value="MG">MG</option> 
																				<option value="MS">MS</option> 
																				<option value="MT">MT</option> 
																				<option value="PA">PA</option> 
																				<option value="PB">PB</option> 
																				<option value="PE">PE</option> 
																				<option value="PI">PI</option> 
																				<option value="PR">PR</option> 
																				<option value="RJ">RJ</option> 
																				<option value="RN">RN</option> 
																				<option value="RO">RO</option> 
																				<option value="RR">RR</option> 
																				<option value="RS">RS</option> 
																				<option value="SC">SC</option> 
																				<option value="SE">SE</option> 
																				<option value="SP">SP</option> 
																				<option value="TO">TO</option> 							
																			</select>
																		<div class="help-block with-errors"></div>
																	</div>
																</div>

																<div class="col-md-3">
																	<div class="form-group">
																		<label for="inputName" class="control-label">Cidade</label>
																			<div id="relatorioConteudo">
																				<select data-placeholder="Nenhum estado selecionado" name="NOM_CIDADE" id="NOM_CIDADE" class="chosen-select-deselect"> 							
																				</select>
																			</div>
																		<div class="help-block with-errors"></div>
																	</div>
																</div>

																<div class="col-md-3">
																	<div class="form-group">
																		<label for="inputName" class="control-label">Logradouro</label>
																		<input type="text" class="form-control input-sm" name="DES_ENDEREC" id="DES_ENDEREC" maxlength="250">
																		<div class="help-block with-errors"></div>
																	</div>
																</div>

																<div class="col-md-1 text-center">
																	<div class="form-group">
																		<label for="inputName" class="control-label">&nbsp;</label>
																		<p class="text-muted">OU</p>
																	</div>
																</div>
																
																<div class="col-md-2">
																	<div class="form-group">
																		<label for="inputName" class="control-label">CEP</label>
																		<input type="text" class="form-control input-sm cep" name="NUM_CEP" id="NUM_CEP" maxlength="50">
																		<div class="help-block with-errors"></div>
																	</div>
																</div>
																							
															</div>
															
													</fieldset>	
																							
													<div class="push10"></div>
													<hr>	
													<div class="form-group text-right col-lg-12">
														
														  <button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>
														  <button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fas fa-magnifying-glass" aria-hidden="true"></i>&nbsp; Buscar</button>
														
													</div>
													
													<input type="hidden" name="opcao" id="opcao" value="">
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
															  <th>CEP</th>
															  <th>Logradouro</th>
															  <th>Complemento</th>
															  <th>Bairro</th>
															  <th>Cidade</th>
															  <th>UF</th>
															</tr>
														  </thead>
														<tbody>
														  
														<?php

															include '../../_system/CEP2.php';

															// [cep] => 18270-310
												   			// [logradouro] => Rua Quinze de Novembro
												   			// [complemento] => até 866/867
												   			// [bairro] => Centro
												   			// [localidade] => Tatuí
												   			// [uf] => SP
												   			// [unidade] => 
												   			// [ibge] => 3554003
												   			// [gia] => 6877

												   			// $arraydados=array('ESTADO'=>'SP',
															   //                'CIDADE'=>'TATUÍ',
															   //                'RUA'=>'NOVEMBRO',
															   //                'CEP'=>'');

															if($num_cep == ""){
																$arraydados=array(
																				'ESTADO'=>"$cod_estadof",
																                'CIDADE'=>"$nom_cidade",
																                'RUA'=>"$des_enderec",
																                'CEP'=>''
																            );
															}else{
																$arraydados=array(
																				'ESTADO'=>'',
																				'CIDADE'=>'',
																				'RUA'=>'',
																				'CEP'=>"$num_cep"
																			);
															}

															$enderecos = consulta_cep($arraydados);

															// print_r($arraydados);

															foreach ($enderecos as $key => $endereco) {
																$count++;

																$sql = "SELECT COD_ESTADO FROM ESTADO WHERE UF = '$endereco[uf]'";
																$qrEstado = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));

																$sql2 = "SELECT COD_MUNICIPIO FROM MUNICIPIOS WHERE NOM_MUNICIPIO = '$endereco[localidade]' AND COD_ESTADO = $qrEstado[COD_ESTADO]";
																$qrCidade = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql2));

																echo"
																	<tr>
																	  <td><a href='javascript: downForm(".$count.")' style='margin-left: 10px;'><i class='fa fa-arrow-circle-down' aria-hidden='true'></i></a></th>
																	  <td>".$endereco['cep']."</td>
																	  <td>".$endereco['logradouro']."</td>
																	  <td>".$endereco['complemento']."</td>
																	  <td>".$endereco['bairro']."</td>
																	  <td>".$endereco['localidade']."</td>
																	  <td>".$endereco['uf']."</td>
																	</tr>
																	<input type='hidden' id='ret_NUM_CEPOZOF_".$count."' value='".$endereco['cep']."'>
																	<input type='hidden' id='ret_COD_ESTADOF_".$count."' value='".$endereco['uf']."'>
																	<input type='hidden' id='ret_NOM_CIDADEC_".$count."' value='".$endereco['localidade']."'>
																	<input type='hidden' id='ret_DES_BAIRROC_".$count."' value='".$endereco['bairro']."'>
																	<input type='hidden' id='ret_DES_COMPLEM_".$count."' value='".$endereco['complemento']."'>
																	<input type='hidden' id='ret_DES_ENDEREC_".$count."' value='".$endereco['logradouro']."'>
																	<input type='hidden' id='ret_COD_ESTADO_".$count."' value='".$qrEstado['COD_ESTADO']."'>
																	<input type='hidden' id='ret_COD_MUNICIPIO_".$count."' value='".$qrCidade['COD_MUNICIPIO']."'>
																	"; 
															}

																										

														?>
															
														</tbody>
														</table>
														
														</form>

													</div>
													
												</div>
											
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
											<iframe id="frameExterno" frameborder="0" style="width: 100%; height: 80%"></iframe>
										</div>		
									</div><!-- /.modal-content -->
								</div><!-- /.modal-dialog -->
							</div><!-- /.modal -->
								
							<div class="push20"></div>

			<?php include "../../jsLib.php"; ?>

			<script type="text/javascript">
				
				console.clear();

				$('#COD_ESTADOF').change(function(){
					var uf = $(this).val();
					$.ajax({
						method: 'POST',
						url: 'https://adm.bunker.mk/ajxBuscaCep.php',
						data: {ESTADO: uf},
						beforeSend:function(){
							$('#relatorioConteudo').html('<div class="loading" style="width: 100%;"></div>');
						},
						success:function(data){
							$('#relatorioConteudo').html(data);
							// console.log(data);
						}
					});
				});
				
				function downForm(index){
					try { parent.$('#NUM_CEPOZOF').val($("#ret_NUM_CEPOZOF_"+index).val()); } catch(err) {}		
					try { parent.$('#COD_ESTADO').val($("#ret_COD_ESTADO_"+index).val()).trigger('chosen:updated'); } catch(err) {}		
					try { parent.$('#COD_ESTADOF').val($("#ret_COD_ESTADOF_"+index).val()); } catch(err) {}		
					try { 
						$.when(parent.$('#COD_MUNICIPIO_AUX').val($("#ret_COD_MUNICIPIO_"+index).val()).trigger('chosen:updated')).then(parent.carregaComboCidades($("#ret_COD_ESTADO_"+index).val()));
					} catch(err) {}		
					try { parent.$('#NOM_CIDADEC').val($("#ret_NOM_CIDADEC_"+index).val()); } catch(err) {}			
					try { parent.$('#DES_BAIRROC').val($("#ret_DES_BAIRROC_"+index).val()); } catch(err) {}	
					try { parent.$('#DES_COMPLEM').val($("#ret_DES_COMPLEM_"+index).val()); } catch(err) {}	
					try { parent.$('#DES_ENDEREC').val($("#ret_DES_ENDEREC_"+index).val()); } catch(err) {}	
					$(this).removeData('bs.modal');	
					//console.log('entrou' + index);
					parent.$('#popModal').modal('hide');
				}
				
			</script>	

		 
		</div>
		<!-- end container -->    
	
	</div>
	<!-- end outContainer -->
	
	<script src="https://adm.bunker.mk/js/bootstrap.min.js" type="text/javascript"></script>
	<script src="https://adm.bunker.mk/js/plugins/jquery.webui-popover.min.js" type="text/javascript"></script>
	<script src="https://adm.bunker.mk/js/chosen.jquery.min.js" type="text/javascript"></script>	
	<script src="https://adm.bunker.mk/js/plugins/validator.min.js" type="text/javascript"></script>
	<script src="https://adm.bunker.mk/js/plugins/mmenu/jquery.mmenu.min.js" type="text/javascript" ></script>
	<script src="https://adm.bunker.mk/js/main.js" type="text/javascript"></script>
	<script src="https://adm.bunker.mk/js/jquery.mask.min.js" type="text/javascript"></script>
	<script src="https://adm.bunker.mk/js/plugins/ie10-viewport-bug-workaround.js" type="text/javascript"></script>
	<script src="https://adm.bunker.mk/js/tablesorter/jquery.tablesorter.js" type="text/javascript"></script>
	<script src="https://adm.bunker.mk/js/tablesorter/jquery.tablesorter.widgets.js" type="text/javascript" ></script>
	<script src="https://adm.bunker.mk/js/plugins/jquery.metadata.js" type="text/javascript"></script>
	<script src="https://adm.bunker.mk/js/plugins/jquery.uitablefilter.js" type="text/javascript"></script>
	<script src="https://adm.bunker.mk/js/jquery-confirm.min.js"></script>
	<script src="https://adm.bunker.mk/js/jquery.twbsPagination.min.js"></script>
	
    </body>
	
</html>
