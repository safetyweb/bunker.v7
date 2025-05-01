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

			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
						
			if ($opcao != ''){
				
		
				
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
		
		if (isset($arrayQuery)){
			$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
			$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
		}
												
	}else {
		$cod_empresa = 0;		
		//fnEscreve('entrou else');
	}
	
	//fnMostraForm();

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
							<div class="portlet" style="padding: 0;" >
							<?php } ?>
							
								<?php if ($popUp != "true"){  ?>
								<div class="portlet-title">
									<div class="caption">
										<i class="glyphicon glyphicon-calendar"></i>
										<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
									</div>
									<?php include "atalhosPortlet.php"; ?>
								</div>
								<?php } ?>	

								</div>
								<div class="portlet-body">
									
									<?php if ($msgRetorno <> '') { ?>	
									<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
									<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									 <?php echo $msgRetorno; ?>
									</div>
									<?php } ?>	
			
									<div class="login-form">
									
										<div class="push20"></div>
										
										<div class="col-lg-12">

											<div class="no-more-tables">
										
												<form name="formLista">
																								
												<h4>Templates para <b>imagens de fundos</b> do totem</h4>
												
												<div class="push20"></div>
												
												<table class="table table-bordered table-striped table-hover">
												  <thead>
													<tr>
													  <th>Nome do Arquivo</th>
													  <th>Tipo</th>
													  <th>Resolução</th>
													  <th>Tamanho</th>
													  <th></th>
													</tr>
												  </thead>
												<tbody>
								
													<tr>
													  <td>fundo_totem_horizontal.psd</td>
													  <td>.psd (photoshop)</td>
													  <td>1920 x 1080 (72dpi)</td>
													  <td>935kb</td>
													  <td class="text-center"><a class='btn btn-xs btn-info' href="http://img.bunker.mk/images/fundo_totem_horizontal.psd"><i class='fal fa-download'></i> &nbsp; Download </a></td>
													</tr>
													
													<tr>
													  <td>fundo_totem_vertical.psd</td>
													  <td>.psd (photoshop)</td>
													  <td>1080 x 1920 (72dpi)</td>
													  <td>980kb</td>
													  <td class="text-center"><a class='btn btn-xs btn-info' href="http://img.bunker.mk/images/fundo_totem_vertical.psd"><i class='fal fa-download'></i> &nbsp; Download </a></td>
													</tr>
													
													<tr>
													  <td>exemplo_fundo_totem_horizontal.jpg</td>
													  <td>.jpg </td>
													  <td>1920 x 1080 (72dpi)</td>
													  <td>42kb</td>
													  <td class="text-center"><a class='btn btn-xs btn-info' href="http://img.bunker.mk/images/exemplo_fundo_totem_horizontal.jpg" target="_blank" download="exemplo_fundo_totem_horizontal.jpg"><i class='fal fa-download'></i> &nbsp; Download </a></td>
													</tr>
													
													<tr>
													  <td>exemplo_fundo_totem_vertical.jpg</td>
													  <td>.jpg</td>
													  <td>1080 x 1920 (72dpi)</td>
													  <td>80kb</td>
													  <td class="text-center"><a class='btn btn-xs btn-info' href="http://img.bunker.mk/images/exemplo_fundo_totem_vertical.jpg" target="_blank"  download="exemplo_fundo_totem_vertical.jpg"><i class='fal fa-download'></i> &nbsp; Download </a></td>
													</tr>
													
												</tbody>
												</table>
												
												<div class="push30"></div>
												
												<h4>Templates para <b>imagens do banner</b> do totem</h4>
												
												<div class="push20"></div>
												
												<table class="table table-bordered table-striped table-hover">
												  <thead>
													<tr>
													  <th>Nome do Arquivo</th>
													  <th>Tipo</th>
													  <th>Resolução</th>
													  <th>Tamanho</th>
													  <th></th>
													</tr>
												  </thead>
												<tbody>
								
													<tr>
													  <td>banner_totem_horizintal.psd</td>
													  <td>.psd (photoshop)</td>
													  <td>1920 x 1080 (72dpi)</td>
													  <td>440kb</td>
													  <td class="text-center"><a class='btn btn-xs btn-info' href="http://img.bunker.mk/images/banner_totem_horizintal.psd"><i class='fal fa-download'></i> &nbsp; Download </a></td>
													</tr>
													
													<tr>
													  <td>banner_totem_vertical.psd</td>
													  <td>.psd (photoshop)</td>
													  <td>1080 x 1920 (72dpi)</td>
													  <td>440kb</td>
													  <td class="text-center"><a class='btn btn-xs btn-info' href="http://img.bunker.mk/images/banner_totem_vertical.psd"><i class='fal fa-download'></i> &nbsp; Download </a></td>
													</tr>
													
													<tr>
													  <td>exemplo_banner_totem_horizintal.jpg</td>
													  <td>.jpg </td>
													  <td>1920 x 1080 (72dpi)</td>
													  <td>42kb</td>
													  <td class="text-center"><a class='btn btn-xs btn-info' href="http://img.bunker.mk/images/exemplo_banner_totem_horizintal.jpg" target="_blank" download="exemplo_banner_totem_horizintal.jpg"><i class='fal fa-download'></i> &nbsp; Download </a></td>
													</tr>
													
													<tr>
													  <td>exemplo_banner_totem_vertical.jpg</td>
													  <td>.jpg</td>
													  <td>1080 x 1920 (72dpi)</td>
													  <td>80kb</td>
													  <td class="text-center"><a class='btn btn-xs btn-info' href="http://img.bunker.mk/images/exemplo_banner_totem_vertical.jpg" target="_blank"  download="exemplo_banner_totem_vertical.jpg"><i class='fal fa-download'></i> &nbsp; Download </a></td>
													</tr>
													
													
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
	
		
		// ajax
		$("#COD_UNIVEND").change(function () {
			var codBusca = $("#COD_UNIVEND").val();
			var codBusca2 = $("#COD_EMPRESA").val();
			buscaUsuario(codBusca,codBusca2);
		});

		function buscaUsuario(idUnidade,idEmp) {
			$.ajax({
				type: "GET",
				url: "ajxBuscaUsuarioChave.php",
				data: { ajx1:idUnidade,ajx2:idEmp},
				beforeSend:function(){
					$('#divId_usu').html('<div class="loading" style="width: 100%;"></div>');
				},
				success:function(data){
					$("#divId_usu").html(data); 
				},
				error:function(){
					$('#divId_usu').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
				}
			});		
		}		
		function retornaForm(index){
			$("#formulario #COD_PLAYERS").val($("#ret_COD_PLAYERS_"+index).val());
			$("#formulario #COD_UNIVEND").val($("#ret_COD_UNIVEND_"+index).val()).trigger("chosen:updated");
			buscaUsuario($("#ret_COD_UNIVEND_"+index).val(),<?php echo $cod_empresa; ?>);
			//alert($("#ret_COD_USUARIO_"+index).val());
			//$("#formulario #COD_USUARIO").val($("#ret_COD_USUARIO_"+index).val()).trigger("chosen:updated");
			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');						
		}
		
	</script>	