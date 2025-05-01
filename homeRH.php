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
			
			$cod_servidor = fnLimpaCampoZero($_REQUEST['COD_SERVIDOR']);
			$des_servidor = fnLimpaCampo($_POST['DES_SERVIDOR']);
			$des_abrevia = fnLimpaCampo($_POST['DES_ABREVIA']);
			$des_geral = fnLimpaCampo($_POST['DES_GERAL']);
			$cod_operacional = fnLimpaCampoZero($_POST['COD_OPERACIONAL']);
			$des_observa = fnLimpaCampo($_POST['DES_OBSERVA']);
	   
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
    
	//busca dados da empresa
	//$cod_empresa = $_SESSION["SYS_COD_EMPRESA"];
	//echo "<h5>"."oiiii"."</h5>" ;
	//echo "<h5>sistema - ".$_SESSION["SYS_COD_SISTEMA"]."</h5>" ;
	//echo "<h5>usuario - ".$_SESSION["SYS_COD_USUARIO"]."</h5>" ;
	$cod_empresa = fnDecode($_GET['id']);
	if ($_SESSION["SYS_COD_SISTEMA"] == 19){
		$cod_empresa = $_SESSION["SYS_COD_EMPRESA"];
	}
	
	$sql = "SELECT COD_EMPRESA, NOM_FANTASI, COD_SEGMENT FROM empresas where COD_EMPRESA = '".$cod_empresa."' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)){
			$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
			$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
			$cod_segmentEmp = $qrBuscaEmpresa['COD_SEGMENT'];
	}
	
	//liberação das abas
	$abaPersona	= "S";
	$abaCampanha = "S";
	$abaVantagem = "N";
	$abaRegras = "N";
	$abaComunica = "N";
	$abaAtivacao = "N";
	$abaResultado = "N";

	$abaPersonaComp = "active ";
	$abaCampanhaComp = "";
	$abaVantagemComp = "";
	$abaRegrasComp = "";
	$abaComunicaComp = "";
	$abaResultadoComp = "";
	
	//revalidada na aba de regras	
	$abaAtivacaoComp = "";
	
	//Busca módulos autorizados
	$sql = "SELECT COD_PERFILS FROM usuarios WHERE COD_USUARIO = $_SESSION[SYS_COD_USUARIO]";
	$qrPfl = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(),$sql));

	$sqlAut = "SELECT COD_MODULOS FROM perfil WHERE
			   COD_SISTEMA = 19
			   AND COD_PERFILS IN($qrPfl[COD_PERFILS])";
	$qrAut = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(),$sqlAut));

	$modsAutorizados = explode(",", $qrAut['COD_MODULOS']);

	//echo($qrAut['COD_MODULOS']);

	//echo "<pre>";	
	//print_r($modsAutorizados);	
	//echo "</pre>";
	
	//echo(fnControlaAcesso("1049",$modsAutorizados));
	
	//fnEscreve($cod_empresa);
	
?>

<style>
	.fa-1dot5x{
		font-size: 45px;
		margin-top: 7px;
		margin-bottom: 7px;
	}
	.next, .prev{
		margin-top: 150px;
		font-size: 65px;
	}
	.item{
		padding: 0;
	}
</style>

<link rel="stylesheet" href="css/widgets.css" />
			
					<div class="push30"></div> 
						
					<!-- Portlet -->
					<div class="portlet portlet-bordered">
						
						<div class="portlet-title">
							<div class="caption">
								<i class="far fa-terminal"></i>
								<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
							</div>
							
							<?php 
							//$formBack = "1048";
							include "atalhosPortlet.php"; ?>
							
						</div>								
							
						<div class="push10"></div> 
						
						<div class="portlet-body">
							
							<?php if ($msgRetorno <> '') { ?>	
							<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							 <?php echo $msgRetorno; ?>
							</div>
							<?php } ?>

							<?php

								$sql1="SELECT A.DES_IMAGEM,
											  A.DES_BANNER 
										FROM BANNER_TOTEM A 
										WHERE A.COD_EMPRESA = $cod_empresa 
										AND A.COD_EXCLUSA = 0 
										AND A.LOG_ATIVO = 'S' 
										ORDER BY A.DES_BANNER";

								// fnEscreve($sql1);
								$arrayQuery = mysqli_query(connTemp($cod_empresa,""),$sql1);

								$count = 0;
								$active = 'active';
								$nroImagens = mysqli_num_rows($arrayQuery);
								
								if($nroImagens > 0){

							?>

							<div class="push10"></div>

							<div class="row">
								
								<div class="col-md-12">
									
									<div id="carouselOfertas" class="carousel slide" style="border-radius: 10px;">

										<ol class="carousel-indicators">
											<?php
												
												while ($nroImagens >= $count){														  

											?>
														<li data-target="#carouselOfertas" data-slide-to="<?=$count?>" class="<?=$active?>"></li>
											<?php

											    	$count++;
											    	$active = '';	
												}

											    if($nroImagens <= 1){
											?>
													<li data-target="#carouselOfertas" data-slide-to="0" class="active"></li>
											<?php

												}

											?>
										</ol>
										<div class="carousel-inner" style="border-radius: 10px;">

											<?php

												$active = 'active';

											    while ($qrJornal = mysqli_fetch_assoc($arrayQuery)){	

											         ?>

											         	<div class="item <?=$active?>" style="border-radius: 10px;">
											         	<?php 
											         		if($qrJornal['DES_IMAGEM'] != ''){

											         			 
														?>
																<div class="zoom"><img src="https://img.bunker.mk/media/clientes/<?=$cod_empresa?>/<?=$qrJornal[DES_IMAGEM]?>" width="100%"></div>
														<?php
															}else{ 
														?>
																<img src="https://img.bunker.mk/media/clientes/branco.jpg" width="100%">
														<?php 
															} 
														?>
														</div>

											         <?php

											         $active = '';

											    }

											?>

										</div>

										<!-- Carousel controls -->
										<a class="carousel-control left" href="#carouselOfertas" data-slide="prev" style="border-radius: 10px;">
											<span class="fal fa-angle-left prev"></span>
										</a>
										<a class="carousel-control right" href="#carouselOfertas" data-slide="next" style="border-radius: 10px;">
											<span class="fal fa-angle-right next"></span>
										</a>

									</div>

								</div>

							</div>

							<?php 
								} 
							?>
							
							<div class="push20"></div>
							
							<div class="row">
							
								<h3 style="margin: 0 0 30px 15px;"><b>Dia a Dia:</b> Controle de pessoal agora é <strong>fácil</strong></h3>

									<div class="col-md-2">
									
										<?php if(fnControlaAcesso("1701",$modsAutorizados) === true) { ?>												
										<a href="action.do?mod=<?php echo fnEncode(1701)?>&id=<?php echo fnEncode($cod_empresa)?>" class="tile tile-default shadow" style="color: #2c3e50;">
										<?php } else { ?>
										<a href="#" class="tile tile-default shadow" style="color: #2c3e50;">
										<?php }?>
										
										<span class="fal fa-cogs fa-1dot5x"></span>
										<p style="height: 50px;">Configurações</p>                            
										</a>                        
									</div>

									<div class="col-md-2">
									
										<?php if(fnControlaAcesso("1702",$modsAutorizados) === true) { ?>												
										<a href="action.do?mod=<?php echo fnEncode(1702)?>&id=<?php echo fnEncode($cod_empresa)?>" class="tile tile-default shadow" style="color: #2c3e50;">
										<?php } else { ?>
										<a href="#" class="tile tile-default shadow" style="color: #2c3e50;">
										<?php }?>
										
										<span class="fal fa-users fa-1dot5x"></span>
										<p style="height: 50px;">Colaboradores</p>                            
										</a>                        
									</div>
								
									<div class="col-md-2">
									
										<?php if(fnControlaAcesso("1708",$modsAutorizados) === true) { ?>												
										<a href="action.do?mod=<?php echo fnEncode(1708)?>&id=<?php echo fnEncode($cod_empresa)?>" class="tile tile-default shadow" style="color: #2c3e50;">
										<?php } else { ?>
										<a href="#" class="tile tile-default shadow" style="color: #2c3e50;">
										<?php }?>
										
										<span class="fal fa-money-check-edit-alt fa-1dot5x"></span>
										<p style="height: 50px;">Lançamentos</p>                            
										</a>                        
									</div>	
									
									<div class="col-md-2">
									
										<?php if(fnControlaAcesso("1707",$modsAutorizados) === true) { ?>												
										<a href="action.do?mod=<?php echo fnEncode(1707)?>&id=<?php echo fnEncode($cod_empresa)?>" class="tile tile-default shadow" style="color: #2c3e50;">
										<?php } else { ?>
										<a href="#" class="tile tile-default shadow" style="color: #2c3e50;">
										<?php }?>
										
										<span class="fal fa-chart-line fa-1dot5x"></span>
										<p style="height: 50px;">Relatórios</p>                            
										</a>                        
									</div>

							</div>
							
							<div class="push50"></div>
							
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

					<form id="formModal">					
						<input type="hidden" class="input-sm" name="REFRESH_CAMPANHA" id="REFRESH_CAMPANHA" value="N"> 
						<input type="hidden" class="input-sm" name="REFRESH_PERSONA" id="REFRESH_PERSONA" value="N"> 					
					</form>
	
	<script type="text/javascript">
	
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

			$('.carousel').carousel({
				interval: 20000
			});
			
		});	

		function RefreshPersona(idEmp) {
			$.ajax({
				type: "GET",
				url: "ajxRefreshPersona.do",
				data: { ajx1:idEmp},
				beforeSend:function(){
					$('#div_refreshPersona').html('<div class="loading" style="width: 100%;"></div>');
				},
				success:function(data){
					$("#div_refreshPersona").html(data); 
				},
				error:function(){
					$('#div_refreshPersona').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
				}
			});		
		}
		
		function RefreshCampanha(idEmp) {
			$.ajax({
				type: "GET",
				url: "ajxRefreshCampanha.do#campanha",
				data: { ajx1:idEmp},
				beforeSend:function(){
					$('#div_refreshCampanha').html('<div class="loading" style="width: 100%;"></div>');
				},
				success:function(data){
					$("#div_refreshCampanha").html(data); 
				},
				error:function(){
					$('#div_refreshCampanha').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
				}
			});		
		}		
		
		function retornaForm(index){
			$("#formulario #COD_SERVIDOR").val($("#ret_COD_SERVIDOR_"+index).val());
			$("#formulario #DES_SERVIDOR").val($("#ret_DES_SERVIDOR_"+index).val());
			$("#formulario #DES_ABREVIA").val($("#ret_DES_ABREVIA_"+index).val());
			$("#formulario #DES_GERAL").val($("#ret_DES_GERAL_"+index).val());
			$("#formulario #COD_OPERACIONAL").val($("#ret_COD_OPERACIONAL_"+index).val()).trigger("chosen:updated");
			$("#formulario #DES_OBSERVA").val($("#ret_DES_OBSERVA_"+index).val());
			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');						
		}
		
	</script>	