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
	$cod_empresa = $_SESSION["SYS_COD_EMPRESA"];	
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
			   COD_SISTEMA = 4 
			   AND COD_PERFILS IN($qrPfl[COD_PERFILS])";
	$qrAut = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(),$sqlAut));

	$modsAutorizados = explode(",", $qrAut['COD_MODULOS']);

	//echo($qrAut['COD_MODULOS']);

	//echo "<pre>";	
	//print_r($modsAutorizados);	
	//echo "</pre>";
	
	//echo(fnControlaAcesso("1049",$modsAutorizados));
	
?>

<style>
	.fa-1dot5x{
		font-size: 45px;
		margin-top: 7px;
		margin-bottom: 7px;
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
							$formBack = "1048";
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
						
		
							<div class="row">
							
								<div class="col-md-12">
								
									<?php $abaCampanhas = 0; include "abasCampanhasConfig.php"; ?>
									
								</div>
								
							</div>
							
							<!--
							<div class="push10"></div>	
							
							<div class="row">
							
								<div class="col-md-12">
								
									<div class="alert alert-warning" role="alert">
										<h3 class="bg-warning " style="margin:10px 0 10px 0;">Como vamos melhorar os <strong>resultados</strong> do seu negócio <strong>hoje?</strong> </h3>
									</div>										
								
								</div>
								
							</div>
							-->
							
							<div class="push20"></div>
							
							<div class="row">
							
								<h3 style="margin: 0 0 30px 15px;"><b>Personas:</b> Qual tipo de comportamento você quer <strong>incentivar</strong>?</h3>

								<?php 
									$sql = "";
									$sql = "SELECT S.COD_HOME,M.DES_COMMAND FROM sistemas S LEFT JOIN modulos M ON M.COD_MODULOS=S.COD_HOME WHERE cod_sistema=".$_SESSION["SYS_COD_SISTEMA"]."  ";
									$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
									$qrPaginaHome = mysqli_fetch_assoc($arrayQuery);
									//fnTestesql($connAdm->connAdm(),$sql);
									$pagHome = 	$qrPaginaHome['COD_HOME'];
									
									//fnEscreve($sql);
									//fnEscreve($pagHome);
									//fnEscreve($qrPaginaHome['DES_COMMAND']);
								
									//segmentos
									$sql = "select * from SEGMENTOMARKA where COD_SEGMENT = '".$cod_segmentEmp."'  order by NUM_ORDENAC";
									$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
									//echo $sql;
									$count = 1;
									while ($qrLista = mysqli_fetch_assoc($arrayQuery))
										{
											
										//itens do segmento
										$sql2 = "select * from SEGMARKAITEM where COD_SEGMENT = '".$qrLista['COD_SEGMENT']."' order by NUM_ORDENAC";
										$arrayQuery2 = mysqli_query($connAdm->connAdm(),$sql2) or die(mysqli_error());
										
										while ($qrLista2 = mysqli_fetch_assoc($arrayQuery2))
											{
								?>
											<div class="col-md-2">

												
												<?php if(fnControlaAcesso("1049",$modsAutorizados) === true) { ?>												
												<a href="#" class="tile tile-default shadow addBox" style="color: #2c3e50;" data-url="action.do?mod=<?php echo fnEncode(1038)?>&id=<?php echo fnEncode($cod_empresa)?>&pop=true" data-title="Persona / <?php echo $nom_empresa; ?>">
												<?php } else { ?>
												<a href="#" class="tile tile-default shadow" style="color: #2c3e50;" data-title="Persona / <?php echo $nom_empresa; ?>">
												<?php }?>
												
												<span class="fal <?php echo $qrLista2['DES_ICONE']; ?> fa-1dot5x"></span>
												<p style="height: 50px;"><?php echo $qrLista2['NOM_SEGITEM']; ?></p>                            
												<!-- <div class="informer informer-default" style="color: #2c3e50;"><span class="fas <?php echo $qrLista['DES_ICONE']; ?>"></span></div> -->
												</a>                        
											</div>
								<?php	

											}
										// echo "<div class='push10'></div>";
										$count ++;

										}

										if(fnControlaAcesso("1049",$modsAutorizados) === true){

								?>
											<div class="col-md-2">

												<a href="action.do?mod=<?php echo fnEncode(1049)?>&id=<?php echo fnEncode($cod_empresa)?>" class="tile tile-default shadow" style="color: #2c3e50; background-color: #F3F4F5;">
												<span class="fal fa-list fa-1dot5x"></span>
												<p style="height: 50px;">Acesse sua lista de Personas</p>                            
												</a>                        
											</div>
								<?php	
											
										}else{

											// echo 'não autorizado';
											echo "<div class='push10'></div>";

										}
								?> 

							</div>
							
							<div class="push50"></div>
							
							<div class="row">
							
								<h3 style="margin: 0 0 30px 15px;"><b>Campanhas:</b> Que tipo de <strong>benefício</strong> será concedido?</h3>
								
								<?php 
							
								//itens do segmento
								$sql2 = "select * from TIPOCAMPANHA order by NUM_ORDENAC";
								$arrayQuery = mysqli_query($connAdm->connAdm(),$sql2) or die(mysqli_error());
								
								while ($qrLista = mysqli_fetch_assoc($arrayQuery)){
										
								?>

									<div class="col-md-2">
									<?php if ($qrLista['LOG_ATIVO'] == "N") { ?>
									<div class="disabledBlock"></div>
									<?php } ?>
									
										<?php if(fnControlaAcesso("1468",$modsAutorizados) === true) { ?>												
										<a href="#" class="tile tile-default shadow addBox" style="color: #2c3e50;" data-url="action.do?mod=<?php echo fnEncode(1040)?>&id=<?php echo fnEncode($cod_empresa)?>&pop=true" data-title="Campanha / <?php echo $nom_empresa; ?>">
										<?php } else { ?>
										<a href="#" class="tile tile-default shadow" style="color: #2c3e50;" data-title="Campanha / <?php echo $nom_empresa; ?>">
										<?php }?>										
											<span class="<?php echo $qrLista['DES_ICONE']; ?> fa-1dot5x"></span>
											<p style="height: 50px;"><?php echo $qrLista['NOM_TPCAMPA']; ?></p>                            
											<div class="informer informer-default" style="color: #2c3e50;"></div>
										</a>                        
									</div>												

								<?php	
									}

									if(fnControlaAcesso("1468",$modsAutorizados) === true){

								?>
											<div class="col-md-2">

												<a href="action.do?mod=<?php echo fnEncode(1468)?>&id=<?php echo fnEncode($cod_empresa)?>" class="tile tile-default shadow" style="color: #2c3e50; background-color: #F3F4F5;">
												<span class="fal fa-list fa-1dot5x"></span>
												<p style="height: 50px;">Acesse sua lista de Campanhas</p>                            
												</a>                        
											</div>
								<?php	
											
										}else{

											// echo 'não autorizado';
											echo "<div class='push10'></div>";

										}
								?>

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