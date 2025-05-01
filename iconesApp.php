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

			$cod_grupotr = fnLimpaCampoZero($_REQUEST['COD_GRUPOTR']);
			$des_grupotr = fnLimpaCampo($_REQUEST['DES_GRUPOTR']);
			$cod_empresa = fnLimpaCampo($_REQUEST['COD_EMPRESA']);

			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
						
			if ($opcao != ''){

				$sql = "CALL SP_ALTERA_GRUPOTRABALHO (
				 '".$cod_grupotr."', 
				 '".$des_grupotr."', 
				 '".$cod_empresa."', 
				 '".$opcao."'    
				) ";
				
				//echo $sql;
				
				mysqli_query($connAdm->connAdm(),trim($sql)) or die(mysqli_error());				
				
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

<style>
	
	.table-icons button{
		background: #fff;
		color: #3c3c3c;
	}

	.table-icons button:hover{ background: #2c3e50; }

	.leitura2{
	border: none transparent !important;
	outline: none !important;
	background: #fff !important;
	font-size: 18px;
	padding: 0;
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

									<div class="row">

										<div class="col-md-3"></div>
										<div class="col-md-3">

											<div class="row">

												<div class="col-md-12">
													<h4>Exemplo Iconpicker Botão</h4>
												</div>

											</div>

											<div class="row">									

												<div class="col-md-12">
													<label for="inputName" class="control-label">Ícone Selecionado: </label>
													<input type="text" class="form-control leitura2" readonly="readonly" name="DES_ICONE" id="DES_ICONE">
												</div>

											</div>

											<div class="row">

												<div class="col-md-3"></div>
												<div class="col-md-6">
													<button class="btn btn-default" id="btniconpicker" data-iconset="fontawesome" 
														data-icon="vazio" role="iconpicker" data-arrow-prev-icon-class="fas fa-arrow-left" data-arrow-next-icon-class="fas fa-arrow-right" 
														data-rows="6" data-cols="6" data-search-text="Buscar ícone..." data-label-footer="{0} - {1} de {2} ícones" data-label-header="{0} de {1}">
													</button>
												</div>
												<div class="col-md-3"></div>											

											</div>

										</div>

										<div class="col-md-3">

											<div class="row">
											
												<div class="col-md-12">
													<h4>Exemplo Iconpicker DIV</h4>
												</div>

											</div>

											<div class="row">
												
												<div class="col-md-12">
													<label for="inputName" class="control-label">Ícone Selecionado: </label>
													<input type="text" class="form-control leitura2" readonly="readonly" name="DES_ICONE2" id="DES_ICONE2">
												</div>

											</div>

											<div class="row">
												
												<div class="col-md-12">
													<div class="btn btn-default" id="diviconpicker" data-iconset="fontawesome" data-align="center"
														data-icon="vazio" role="iconpicker" data-arrow-prev-icon-class="fas fa-arrow-left" data-arrow-next-icon-class="fas fa-arrow-right" 
														data-rows="6" data-cols="6" data-search-text="Buscar ícone..." data-label-footer="{0} - {1} de {2} ícones" data-label-header="{0} de {1}">
													</div>
												</div>

											</div>

										</div>
										<div class="col-md-3"></div>

									</div>

									<div class="push50"></div>

									<div class="row">
										
										<div class="col-md-4"></div>
										<div class="col-md-4">
											
											<span class="fab fa-facebook fa-5x" style="color: #4267b2;"></span>
											<span class="fab fa-twitter fa-5x" style="color: #1da1f2;"></span>
											<span class="fab fa-youtube fa-5x" style="color: red;"></span>
											<span class="fab fa-whatsapp fa-5x" style="color: #00e676;"></span>
											<span class="fab fa-500px fa-5x" style="color: black;"></span>

										</div>
										<div class="col-md-4"></div>

									</div>			

								</div>
							</div>
							<!-- fim Portlet -->
						</div>
						
					</div>					
						
					<div class="push20"></div> 


					
				<!------Bibliotecas Essenciais do Iconpicker--------------------------------------------------->

					<!-- Bootstrap-Iconpicker Iconset -->
					<script type="text/javascript" src="../js/bootstrap-iconpicker-iconset-fa5.js"></script>
					<!-- Bootstrap-Iconpicker -->
					<script type="text/javascript" src="../js/bootstrap-iconpicker.js"></script>
					<!-- Bootstrap-Iconpicker -->
					<link rel="stylesheet" href="../css/bootstrap-iconpicker.min.css"/>

				<!--------------------------------------------------------------------------------------------->
	
	<script type="text/javascript">

		$(document).ready(function(){
			$('#DES_ICONE').val("vazio");
			$('#DES_ICONE2').val("vazio");
		});
		
		//capturando o ícone selecionado no botão
		$('#btniconpicker').on('change', function(e) {
		    $('#DES_ICONE').val(e.icon);
		});

		//capturando o ícone selecionado na div
		$('#diviconpicker').on('change', function(e) {
		    $('#DES_ICONE2').val(e.icon);
		});
		
	</script>	