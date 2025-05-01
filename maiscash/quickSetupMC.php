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
										<span class="text-primary"><?php echo $NomePg." - ".$nom_empresa; ?></span>
									</div>
									
									<?php 
									$formBack = "1019";
									?>	

								</div>
								<div class="portlet-body">
									
									<?php if ($msgRetorno <> '') { ?>	
									<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
									<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									 <?php echo $msgRetorno; ?>
									</div>
									<?php } ?>	
									
									<?php $abaEmpresa = 1025; ?>
									
									<div class="push30"></div>

<style>

	.leitura2{
		border: none transparent !important;
		outline: none !important;
		background: #fff !important;
		font-size: 18px;
		padding: 0;
	}

	.container-fluid .passo:not(:first-of-type){
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

	.breadcrumb {
	    padding: 0px;
		background: #F3F5FA;
		list-style: none; 
		overflow: hidden;
	    margin-top: 0;
	}
	.breadcrumb>li+li:before {
		padding: 0;
	}
	.breadcrumb li { 
		float: left;
		font-size: 16px;
	}
	.breadcrumb li.active a {
		background: gray;                   /* fallback color */
		background: #2c3e50 ; 
		color: #fff;
	}
	.breadcrumb li.completed a {
		background: gray;                   /* fallback color */
		background: #82E0AA; 
		color: #fff;
	}
	.breadcrumb li.active a:after {
		border-left: 30px solid #2c3e50 ;
	}
	.breadcrumb li.completed a:after {
		border-left: 30px solid #82E0AA;
	} 

	.breadcrumb li a {
		color: #8093A7;
		text-decoration: none; 
		padding: 25px 5px 25px 45px;
		position: relative; 
		display: block;
		float: left;
	}
	.breadcrumb li a:after { 
		content: " "; 
		display: block; 
		width: 0; 
		height: 0;
		border-top: 50px solid transparent;           /* Go big on the size, and let overflow hide */
		border-bottom: 50px solid transparent;
		border-left: 30px solid #F3F5FA;
		position: absolute;
		top: 50%;
		margin-top: -50px; 
		left: 100%;
		z-index: 2; 
	}	
	.breadcrumb li a:before { 
		content: " "; 
		display: block; 
		width: 0; 
		height: 0;
		border-top: 50px solid transparent;           /* Go big on the size, and let overflow hide */
		border-bottom: 50px solid transparent;
		border-left: 30px solid white;
		position: absolute;
		top: 50%;
		margin-top: -50px; 
		margin-left: 5px;
		left: 100%;
		z-index: 1; 
	}	
	.breadcrumb li:first-child a {
		padding-left: 15px;
	}
	.breadcrumb li a:not(.disabled):hover { background: #ffc107; color: #fff; }
	.breadcrumb li a:not(.disabled):hover:after { border-left-color: #ffc107   !important; }
	.breadcrumb li a.disabled:hover { cursor: not-allowed; }


</style>
			
									<div class="login-form">
									
										<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

											<ul class="breadcrumb" >
					
						
												<li id="step1" class="active"><a href="javascript:void(0)" style="pointer-events: none;">1 - Empresa</a></li>
												<li id="step2" class=""><a href="javascript:void(0)" style="pointer-events: none;">2 - Incentivo</a></li>
												<li id="step3" class=""><a href="javascript:void(0)" style="pointer-events: none;">3 - Relacionamento</a></li>
												<li id="step4" class=""><a href="javascript:void(0)" style="pointer-events: none;">4 - Personalização</a></li>
												
												
											</ul>

											<!-- ARQUIVO ORIGINAL -->

											<!-- <div class="row text-center wizard setup-panel">
												<div class="col-md-2"></div>
												<div class="col-md-2" id="step1">
													<div class="fundo inicio">
														<a type="button" class="btn btn-circle fundoAtivo disabled" id="btn1"><span>1</span></a>
													</div><br><br>
											        <p>Importação</p>
											    </div>
												<div class="col-md-2" id="step2">
													<div class="fundo">
														<a type="button" class="btn btn-circle disabled"><span>2</span></a>
													</div><br><br>
											        <p>Sumário</p>
												</div>
												<div class="col-md-2" id="step3">
													<div class="fundo">
														<a type="button" class="btn btn-circle disabled"><span>3</span></a>
													</div><br><br>
											        <p>Confirmação</p>
												</div>
												<div class="col-md-2" id="step4">
													<div class="fundo final">
														<a type="button" class="btn btn-circle disabled"><span class="fas fa-check fa-2x"></span></a>
													</div><br><br>
											        <p>Concluído</p>
												</div>
												<div class="col-md-2"></div>
											</div> -->

											<div class="container-fluid">
																				
												<div class="passo" id="passo1"><!--------------PASSO 1----------------->

													<div class="row">

														<div class="push10"></div>

														<div class="col-md-9">
															
															<iframe frameborder="0" id="conteudoAba1" src="action.php?mod=<?php echo fnEncode(1724)?>&id=<?php echo fnEncode($cod_empresa)?>&pop=true" style="width: 100%; min-height: 200px; overflow: hidden;"></iframe>
															<!-- <a href="javascript:void(0)" class="btn btn-info addBox" data-title="Tutorial 1 - Empresa"><span class="fal fa-video"></span>&nbsp;Tutorial em Vídeo</a> -->

														</div>

														<div class="col-md-3">
															<div class="push30"></div>
															<div class="embed-responsive embed-responsive-16by9">
						                                        <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/SXmRVsTxXD8?rel=0?rel=0" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen="" style="margin-left: auto; margin-right: auto;"></iframe>
						                                    </div>

														</div>		
															
													</div>

													<hr>

													<div class="col-md-12">

														<button class="col-md-offset-10 col-md-2 btn btn-primary next next1" name="next">Próximo<i class="fas fa-arrow-right pull-right"></i></button>

													</div>														

													<div class="push10"></div>

												</div>

												<div class="passo" id="passo2"><!--------------PASSO 2----------------->
													
													<div class="row">

														<div class="push10"></div>

														<div class="col-md-9">
															
															<iframe frameborder="0" id="conteudoAba2" src="action.php?mod=<?php echo fnEncode(1725)?>&id=<?php echo fnEncode($cod_empresa)?>&pop=true" style="width: 100%; min-height: 100vh; overflow-x: hidden;"></iframe>
															<!-- <a href="javascript:void(0)" class="btn btn-info addBox" data-title="Tutorial 2 - Incentivo"><span class="fal fa-video"></span>&nbsp;Tutorial em Vídeo</a> -->

														</div>
															
														<div class="col-md-3">
															<div class="push30"></div>
															<div class="embed-responsive embed-responsive-16by9">
						                                        <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/SXmRVsTxXD8?rel=0?rel=0" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen="" style="margin-left: auto; margin-right: auto;"></iframe>
						                                    </div>
															
														</div>

													</div>

													<hr>

													<div class="col-md-12">

														<button class="col-md-2 btn btn-primary prev prev2" name="prev"><i class="fas fa-arrow-left"></i> Anterior</button>
														<button class="col-md-offset-8 col-md-2 btn btn-primary next next2" name="next">Próximo <i class="fas fa-arrow-right pull-right"></i></button>
														
													</div>														

													<div class="push10"></div>
													
												</div>

												<div class="passo" id="passo3"><!--------------PASSO 3----------------->
													
													<div class="row">

														<div class="push10"></div>

														<div class="col-md-9">

															<div class="text-cemter"><h3>Página em construção.</h3></div>
															
															<!-- <a href="javascript:void(0)" class="btn btn-info addBox" data-title="Tutorial 3 - Relacionamento"><span class="fal fa-video"></span>&nbsp;Tutorial em Vídeo</a> -->
															<!-- <iframe frameborder="0" id="conteudoAba3" src="action.php?mod=<?php echo fnEncode(1712)?>&id=<?php echo fnEncode($cod_empresa)?>&pop=true" style="width: 100%; min-height: 100vh; overflow-x: hidden;"></iframe> -->

														</div>

														<div class="col-md-3">
															<div class="push30"></div>
															<!-- <div class="embed-responsive embed-responsive-16by9">
						                                        <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/SXmRVsTxXD8?rel=0?rel=0" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen="" style="margin-left: auto; margin-right: auto;"></iframe>
						                                    </div> -->
															
														</div>
															

													</div>

													<hr>

													<div class="col-md-12">

														<button class="col-md-2 btn btn-primary prev prev3" name="prev"><i class="fas fa-arrow-left"></i> Anterior</button>
														<button class="col-md-offset-8 col-md-2 btn btn-primary next next3" name="next">Próximo <i class="fas fa-arrow-right pull-right"></i></button>
														
													</div>														

													<div class="push10"></div>
													
												</div>

												<div class="passo" id="passo4"><!--------------PASSO 4----------------->
													
													<div class="row">

														<div class="push10"></div>

														<div class="col-md-9">
															
															<!-- <a href="javascript:void(0)" class="btn btn-info addBox" data-title="Tutorial 4 - Personalização"><span class="fal fa-video"></span>&nbsp;Tutorial em Vídeo</a> -->
															<iframe frameborder="0" id="conteudoAba4" src="action.php?mod=<?php echo fnEncode(1340)?>&id=<?php echo fnEncode($cod_empresa)?>&pop=true" style="width: 100%; min-height: 100vh; overflow-x: hidden;"></iframe>


														</div>

														<div class="col-md-3">
															<div class="push30"></div>
															<div class="embed-responsive embed-responsive-16by9">
						                                        <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/SXmRVsTxXD8?rel=0?rel=0" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen="" style="margin-left: auto; margin-right: auto;"></iframe>
						                                    </div>
															
														</div>
															

													</div>

													<div class="push20"></div>

													<hr>

													<div class="col-md-12">

														<button class="col-md-2 btn btn-primary prev prev4" name="prev"><i class="fas fa-arrow-left"></i> Anterior</button>
														<a href="action.do?mod=<?=fnEncode(1695)?>&id=<?=fnEncode($cod_empresa)?>" class="col-md-offset-8 col-md-2 btn btn-success" >Iniciar (Configurações Avançadas)</a>
														
													</div>														

													<div class="push10"></div>
													
												</div>

											</div>

																				
										<div class="push10"></div>
										
										<input type="hidden" name="opcao" id="opcao" value="">
										<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
										<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">
										<input type="hidden" name="FEZ_UPLOAD" id="FEZ_UPLOAD" value="N">
										
										<div class="push5"></div> 
										
										</form>
										
										<div class="push50"></div>
									
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
									<div class="embed-responsive embed-responsive-16by9">
                                        <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/SXmRVsTxXD8?rel=0?rel=0" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen="" style="margin-left: auto; margin-right: auto;"></iframe>
                                    </div>
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

		$(document).ready(function(){

			$('.next1').click(function(){

				if(2==1){

					$.alert({
                        title: "Mensagem",
                        content: "",
                    });

				}else{

					$('#passo1').fadeOut('fast', function(){
						$('#passo2').fadeIn('fast');
						$("#step2").addClass('active');
						$("#step1").removeClass('active');
					});

				}
			});

			$('.next2').click(function(){

				if(2==1){

					$.alert({
                        title: "Mensagem",
                        content: "",
                    });

				}else{

					$('#passo2').fadeOut('fast', function(){
						$('#passo3').fadeIn('fast');
						$("#step3").addClass('active');
						$("#step2").removeClass('active');
					});

				}
			});

			$('.next3').click(function(){

				if(2==1){

					$.alert({
                        title: "Mensagem",
                        content: "",
                    });

				}else{

					$('#passo3').fadeOut('fast', function(){
						$('#passo4').fadeIn('fast');
						$("#step4").addClass('active');
						$("#step3").removeClass('active');
					});

				}
			});


			$('.prev2').click(function(){

				if(2==1){

					$.alert({
                        title: "Mensagem",
                        content: "",
                    });

				}else{

					$('#passo2').fadeOut('fast', function(){
						$('#passo1').fadeIn('fast');
						$("#step1").addClass('active');
						$("#step2").removeClass('active');
					});

				}
			});

			$('.prev3').click(function(){

				if(2==1){

					$.alert({
                        title: "Mensagem",
                        content: "",
                    });

				}else{

					$('#passo3').fadeOut('fast', function(){
						$('#passo2').fadeIn('fast');
						$("#step2").addClass('active');
						$("#step3").removeClass('active');
					});

				}
			});

			$('.prev4').click(function(){

				if(2==1){

					$.alert({
                        title: "Mensagem",
                        content: "",
                    });

				}else{

					$('#passo4').fadeOut('fast', function(){
						$('#passo3').fadeIn('fast');
						$("#step3").addClass('active');
						$("#step4").removeClass('active');
					});

				}
			});

		});

		

	</script>	