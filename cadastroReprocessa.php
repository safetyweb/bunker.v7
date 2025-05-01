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

				//fnMostraForm();
				
				//echo $sql;
				
				//mysqli_query($connAdm->connAdm(),trim($sql)) or die(mysqli_error());				
				
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

	//Totalizadores
	$sql = "SELECT COUNT(*) as CONTADOR FROM CLIENTES B
			WHERE 
			B.LOG_AVULSO='N' AND
			B.COD_EMPRESA = $cod_empresa AND
			( B.COD_SEXOPES = 3  or 
			  B.COD_SEXOPES = 0  or 
			DATE_FORMAT(str_to_date(B.DAT_NASCIME,'%d/%m/%Y'), '%Y-%m-%d') > DATE_FORMAT(CURRENT_DATE() , '%Y-%m-%d') or
			B.DAT_NASCIME is null )
			";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
	$qrBuscaGeral = mysqli_fetch_assoc($arrayQuery);
	$totalCli = $qrBuscaGeral['CONTADOR'];
	$precoTotal = $totalCli * 0.09;
	
	$sql = "SELECT COUNT(*) as CONTADOR FROM CLIENTES B
			WHERE 
			B.LOG_AVULSO='N' AND
			B.COD_EMPRESA = $cod_empresa AND
			( B.COD_SEXOPES = 3  or 
			  B.COD_SEXOPES = 0 )
			";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
	$qrBuscaSexo = mysqli_fetch_assoc($arrayQuery);
	$totalSex = $qrBuscaSexo['CONTADOR'];

	$sql = "SELECT COUNT(*) as CONTADOR FROM CLIENTES B
			WHERE 
			B.LOG_AVULSO='N' AND
			B.COD_EMPRESA = $cod_empresa AND
			DATE_FORMAT(str_to_date(B.DAT_NASCIME,'%d/%m/%Y'), '%Y-%m-%d') > DATE_FORMAT(CURRENT_DATE() , '%Y-%m-%d')
			";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
	$qrBuscaData = mysqli_fetch_assoc($arrayQuery);
	$totalData = $qrBuscaData['CONTADOR'];
	
	$sql = "SELECT COUNT(*) as CONTADOR FROM CLIENTES B
			WHERE 
			B.LOG_AVULSO='N' AND
			B.COD_EMPRESA = $cod_empresa AND
			B.DAT_NASCIME is null
			";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
	$qrBuscaDataVazia = mysqli_fetch_assoc($arrayQuery);
	$totalDataVazia = $qrBuscaDataVazia['CONTADOR'];
	

	//$sql = "DELETE FROM IMPORT_BLACKLIST WHERE COD_EMPRESA = $cod_empresa";
	//mysqli_query(connTemp($cod_empresa,""),trim($sql)) or die(mysqli_error());

	//fnEscreve($cod_empresa);
	

?>
			
					<div class="push30"></div> 
					
					<div class="row">				
					
						<div class="col-md12 margin-bottom-30">
							<!-- Portlet -->
							<div class="portlet portlet-bordered">
								<div class="portlet-title">
									<div class="caption">
										<i class="glyphicon glyphicon-calendar"></i>
										<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
									</div>
					
									<?php 
									//$formBack = "1015";
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

<style>

	.leitura2{
		border: none transparent !important;
		outline: none !important;
		background: #fff !important;
		font-size: 18px;
		padding: 0;
	}

	.container-fluid fieldset{
		border: 0;
	}

	.container-fluid fieldset:not(:first-of-type){
		display: none;
	}

	.wizard .col-md-2{
		padding: 0;
	}

	.btn-circle {
		background-color:#DDD;
		opacity: 1 !important;
	    border:2px solid #efefef;    
	    height:55px;
	    width:55px;
	    margin-top: -23px;
	    padding-top: 11px;
	    border-radius:50%;
	    -moz-border-radius:50%;
	    -webkit-border-radius:50%;
	    color: #fff;
	    font-size: 20px;
	}

	.fa-2x{
		font-size: 19px;
		margin-top: 5px;
	}

	.collapse-chevron .fa {
	  transition: .3s transform ease-in-out;
	}

	.collapse-chevron .collapsed .fa {
	  transform: rotate(-90deg);
	}

	.pull-right,.pull-left{
		margin-top: 3.5px;
	}

	.fundo{
		background: #D3D3D3;
		height: 10px;
		width: 100%;
	}

	.fundoAtivo{
		background: #2ed4e0;
	}

	.inicio{
		background: #2ed4e0;
		border-bottom-left-radius: 10px 7px;
		border-top-left-radius: 10px 7px;
	}

	.final{
		border-bottom-right-radius: 10px 7px;
		border-top-right-radius: 10px 7px;
	}


</style>
			
									<div class="login-form">
									
										<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

											<div class="row text-center wizard setup-panel">

												<div class="col-md-3"></div>

												<div class="col-md-2 stepwizard-step">
													<div class="fundo inicio">
														<a type="button" class="btn btn-circle fundoAtivo disabled" id="btn1"><span>1</span></a>
													</div><br><br>
											        <p>Seleção</p>
											    </div>
												
												<div class="col-md-2 stepwizard-step">
													<div class="fundo">
														<a type="button" class="btn btn-circle disabled"><span>2</span></a>
													</div><br><br>
											        <p>Confirmação</p>
												</div>

												<div class="col-md-2 stepwizard-step">
													<div class="fundo final">
														<a type="button" class="btn btn-circle disabled"><span class="fas fa-check fa-2x"></span></a>
													</div><br><br>
											        <p>Concluído</p>
												</div>

												<div class="col-md-3"></div>

											</div>
											
											<div class="push30"></div>

											<div class="container-fluid">
																				
												<fieldset id="passo1">
												
														<div class="row">
														
														<div class="push20"></div>

															<div class="col-md-3"></div>

															<div class="col-md-6">

																<div class="col-md-3 text-center">
																	<span class="f21"><?php echo fnValor($totalCli,0); ?> </span></br>
																	<h5 class="text-info f18">
																	Cadastros </br> Inconsistentes</h5>
																	<i class="far fa-user-times fa-2x text-info"></i>
																</div>

																<div class="col-md-3 text-center">
																	<span class="f21"><?php echo fnValor($totalSex,0); ?> </span></br>
																	<h5 class="text-info f18">
																	Sexo </br> Indefinido
																	</h5>
																	<i class="far fa-venus-mars fa-2x text-success"></i>
																</div>
																
																<div class="col-md-3 text-center">
																	<span class="f21"><?php echo fnValor($totalData,0); ?> </span></br>
																	<h5 class="text-info f18">
																	Data </br> Inválida
																	</h5>
																	<i class="far fa-calendar-exclamation fa-2x text-danger"></i>
																</div>
																
																<div class="col-md-3 text-center">
																	<span class="f21"><?php echo fnValor($totalDataVazia,0); ?> </span></br>
																	<h5 class="text-info f18">
																	Sem </br> Data
																	</h5>
																	<i class="far fa-calendar-times fa-2x text-danger"></i>
																</div>
																
															</div>

														</div>

													<div class="push100"></div>

													<hr>

													<div class="col-md-8"></div>
													<div class="col-md-2 pull-right">
														<button type="submit" class="btn btn-primary btn-block next next_1" name="next">Próximo &nbsp; <i class="fas fa-arrow-right"></i></button>
													</div>														

													<div class="push10"></div>

												</fieldset>

												

												<fieldset id="passo2">

														<div class="row">

															<div class="col-md-3"></div>

															<div class="col-md-6">
															
																<div class="alert alert-warning alert-dismissible" role="alert">
																  <button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
																  <h4 style="border: none;">Atenção!</h4>
																  Essa ação possui <b>cobrança</b> adicional. <br/>
																  Valor estimado: <b>R$ <?php echo fnValor($precoTotal,2); ?></b> <br/>
																	
																</div>
																
																<div class="push30"></div>
																
																<div class="col-md-3 text-center">
																	<span class="f21"><?php echo fnValor($totalCli,0); ?> </span></br>
																	<input type="hidden" name="totalCli" value="<?php echo fnValor($totalCli,0); ?>">
																	<h5 class="text-info f18">
																	Cadastros </br> Inconsistentes</h5>
																	<i class="far fa-user-times fa-2x text-info"></i>
																</div>

																<div class="col-md-3 text-center">
																	<span class="f21"><?php echo fnValor($totalSex,0); ?> </span></br>
																	<input type="hidden" name="totalCli" value="<?php echo fnValor($totalSex,0); ?>">
																	<h5 class="text-info f18">
																	Sexo </br> Indefinido
																	</h5>
																	<i class="far fa-venus-mars fa-2x text-success"></i>
																</div>
																
																<div class="col-md-3 text-center">
																	<span class="f21"><?php echo fnValor($totalData,0); ?> </span></br>
																	<input type="hidden" name="totalCli" value="<?php echo fnValor($totalData,0); ?>">
																	<h5 class="text-info f18">
																	Data </br> Inválida
																	</h5>
																	<i class="far fa-calendar-exclamation fa-2x text-danger"></i>
																</div>
																
																<div class="col-md-3 text-center">
																	<span class="f21"><?php echo fnValor($totalDataVazia,0); ?> </span></br>
																	<input type="hidden" name="totalCli" value="<?php echo fnValor($totalDataVazia,0); ?>">
																	<h5 class="text-info f18">
																	Sem </br> Data
																	</h5>
																	<i class="far fa-calendar-times fa-2x text-danger"></i>
																</div>
																
															</div>

														</div>

													<div class="push100"></div>

													<hr>

													<div class="col-md-2">
														<button type="submit" class="col-md-12 btn btn-primary prev" name="prev"><i class="fas fa-arrow-left pull-left"></i>Anterior</button>
													</div>

													<div class="col-md-8"></div>

													<div class="col-md-2">
														<button type="submit" class="col-md-12 btn btn-primary next envia" name="next">Confirmar<i class="fas fa-check pull-right"></i></button>
													</div>
														

													<div class="push10"></div>

												</fieldset>

												<fieldset>

													<div class="row">
														<div class="col-md-12 text-center" id="div_Processa">
															<h4>Todos os passos foram concluídos.<br>Sua Blacklist foi importada com sucesso.</h4>
														</div>
													</div>

													<div class="push100"></div>

												</fieldset>

											</div>

																				
										<div class="push10"></div>
										
										<input type="hidden" name="opcao" id="opcao" value="">
										<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
										<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">
										
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

		$(document).ready(function(){			

			var atual_fs, next_fs, prev_fs, next_btn, 
			prev_btn, circulo, barra;

			next_btn = $('.next'),
			prev_btn = $('.prev'),
			circulo = $('.btn-circle'),
			barra = $('.fundo');

				next_btn.click(function(e){
					e.preventDefault();

						atual_fs = $(this).parents('fieldset'),
						next_fs = $(this).parents('fieldset').next();


						atual_fs.hide();

						next_fs.show();

						barra.eq($('fieldset').index(next_fs)).addClass('fundoAtivo');
						circulo.eq($('fieldset').index(next_fs)).addClass('fundoAtivo');
				});

				prev_btn.click(function(e){
					e.preventDefault();
					atual_fs = $(this).parents('fieldset');
					prev_fs = $(this).parents('fieldset').prev();


					atual_fs.hide();
					prev_fs.show();

					barra.eq($('fieldset').index(atual_fs)).removeClass('fundoAtivo');
					circulo.eq($('fieldset').index(atual_fs)).removeClass('fundoAtivo');


					//alert('anterior');
				});

				circulo.click(function(){
					if(circulo.hasClass('disabled')){
						alert('sem link');
					}
				});	

		});

		$('.envia').click(function(){
			$.ajax({
				type: "GET",
				url: "ajxReprocessa.php",
				data: { ajxEmp:<?php echo $cod_empresa; ?> },
				beforeSend:function(){
				$('#div_Processa').html('<div class="loading" style="width: 100%;"></div>');	
				},
				success:function(data){
				$("#div_Processa").html(data);
								
				},
				error:function(){
				$('#div_Processa').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Falha no processamento...</p>');	
				}
			});
		});

	</script>	