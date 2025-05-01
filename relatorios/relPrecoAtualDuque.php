
<?php
	
	//echo fnDebug('true');
	
	$hashLocal = mt_rand();	
	
	//inicialização de variáveis
	$hoje = fnFormatDate(date("Y-m-d"));
	$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 1 days')));
	$cod_univend = "9999";
	
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
			
			$cod_empresa = fnLimpaCampoZero($_POST['COD_EMPRESA']);			
			$cod_univend = $_POST['COD_UNIVEND'];
			$dat_ini = fnDataSql($_POST['DAT_INI']);
			$dat_fim = fnDataSql($_POST['DAT_FIM']);

			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
						
			if ($opcao != ''){
				
				
			}  

		}
	}
	
	//busca dados url
	if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))){
		//busca dados da empresa
		$cod_empresa = fnDecode($_GET['id']);
		$sql = "SELECT COD_EMPRESA, NOM_EMPRESA FROM empresas where COD_EMPRESA = '".$cod_empresa."' ";
		//fnEscreve($sql);
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
		
		if (isset($arrayQuery)){
			$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
			$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
		}
												
	}else {
		$cod_empresa = 0;	
		$nom_empresa = "";
	}
	
	//inicialização das variáveis - default	
	if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31" ){
		$dat_ini = fnDataSql($dias30); 
	} 
	if (strlen($dat_fim ) == 0 || $dat_fim == "1969-12-31"){
		$dat_fim = fnDataSql($hoje); 
	}	
	if (strlen($cod_univend ) == 0){
		$cod_univend = "9999"; 
	}	
	//faz pesquisa por revenda (geral)
	if ($cod_univend == "9999"){$temUnivend = "N";} else {$temUnivend = "S";}
	
	//fnMostraForm();
	//fnEscreve($dat_ini);
	//fnEscreve($dat_fim);
	
?>
		
					<div class="push30"></div> 
					
					<div class="row">				
					
						<div class="col-md12 margin-bottom-30">
							<!-- Portlet -->
							<div class="portlet portlet-bordered">
								<div class="portlet-title">
									<div class="caption">
										<i class="glyphicon glyphicon-calendar"></i>
										<span class="text-primary"> <?php echo $NomePg; ?></span>
									</div>
									
									<?php 
									$formBack = "1015";
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
										
									<div class="push30"></div> 
			
									<div class="login-form">
									
										<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
	
										<div class="row">
														
											<div class="col-md-3">
												<div class="form-group">
													<label for="inputName" class="control-label required">Empresa</label>
													<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa ?>">
													<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>" required>
												</div>														
											</div>
																
											<div class="col-md-5">
												<label for="inputName" class="control-label required">Nome do Motorista</label>
												<div class="input-group">
													<span class="input-group-btn">
														<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary btn-sm addBox" data-url="action.php?mod=<?php echo fnEncode(1071)?>&id=<?php echo fnEncode($cod_empresa)?>&pop=true" data-title="Busca Clientes">
															<i class="fa fa-search" aria-hidden="true" style="padding-top: 3px;"></i>
														</a>
													</span>
													<input type="text" name="NOM_CLIENTE" id="NOM_CLIENTE" class="form-control input-sm leitura" style="border-radius:0 3px 3px 0;" placeholder="Procurar cliente..." value="<?php echo $nom_cliente;?>">
													<input type="hidden" name="COD_CLIENTE" id="COD_CLIENTE" value="" required>
												</div>																
											</div>
											
											<div class="col-md-1 text-center">
												<div class="push20"></div> 
												<a href="#" onclick="carrega1();" name="CAD" id="CAD" class="btn btn-primary btn-sm btn-block"><i class="fa fa-refresh" aria-hidden="true"></i></a>
											</div>
											
											<div id="div_basicos">	
											
											<div class="col-md-3">
												<div class="form-group">
													<label for="inputName" class="control-label required">Empresa Conveniada</label>
													<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_ENTIDAD" id="NOM_ENTIDAD" value="">
													<input type="hidden" class="form-control input-sm" name="COD_ENTIDAD" id="COD_ENTIDAD" value="<?php echo $cod_empresa ?>" required>
												</div>														
											</div>
											
											<div class="push10"></div> 
											
											
											<div class="col-md-3">
												<div class="form-group">
													<label for="inputName" class="control-label required">Grupo de Postos </label>
														<select data-placeholder="Selecione o grupo" name="COD_GRUPO" id="COD_GRUPO" class="chosen-select-deselect requiredChk" required>
															<option value=""></option>					
														</select>
													<div class="help-block with-errors"></div>
												</div>
											</div>	

											</div>	
											
										</div>
																
										<input type="hidden" name="NOVO_CLIENTE" id="NOVO_CLIENTE" value="">
										<input type="hidden" name="REFRESH_CLIENTE" id="REFRESH_CLIENTE" value="N">
										<input type="hidden" name="opcao" id="opcao" value="">
										<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
										<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">		
										
										<div class="push5"></div> 
										
										</form>
										
									<div class="push50"></div>									
									
									<div class="push"></div>
									
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
					
	
    <script>
	
	
		$(document).ready(function(){
			
			//modal close
			$('#popModal').on('hidden.bs.modal', function () {
			  
			  if ($('#REFRESH_PERSONA').val() == "S"){
				//alert("atualiza");
				RefreshPersona("<?php echo fnEncode($cod_empresa)?>");
				$('#REFRESH_PERSONA').val("N");				
			  }	
			  
			  if ($('#REFRESH_CAMPANHA').val() == "S"){
				//alert("atualiza");
				RefreshCampanha("<?php echo fnEncode($cod_empresa)?>");
				$('#REFRESH_CAMPANHA').val("N");				
			  }
			  
			});
			
		});
		
		
		function carrega1(){
			
			var idEmp = $('#COD_EMPRESA').val();
			var idCli = $('#COD_CLIENTE').val();
			
			//alert($('#COD_CLIENTE').val());
			
			$.ajax({
				type: "GET",
				url: "ajxDuqueDadosBasicos.php",
				data: { ajx1:idEmp, ajx2:idCli},
				beforeSend:function(){
					$('#div_basicos').html('<div class="loading" style="width: 100%;"></div>');
				},
				success:function(data){
					$("#div_basicos").html(data); 
				},
				error:function(){
					$('#div_basicos').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
				}
			});		
			
		} 	
				
		$('#COD_CLIENTE').change(function() {
			$(this).val(); 
		});	
	
	</script>	
   