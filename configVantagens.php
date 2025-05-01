<?php
	
	//echo fnDebug('true');
	
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
				
				//liberação das abas
				$abaPersona	= "S";
				$abaVantagem = "S";
				$abaRegras = "N";
				$abaComunica = "N";
				$abaAtivacao = "N";
				$abaResultado = "N";

				$abaPersonaComp = "completed ";
				$abaVantagemComp = "active ";
				$abaRegrasComp = "";
				$abaComunicaComp = "";
				$abaAtivacaoComp = "";
				$abaResultadoComp = "";					

            }

    }else {
            $cod_empresa = 0;
           // $codEmpresa = $qrBuscaEmpresa['COD_SISTEMA'];

    }  
	
	//fnMostraForm();
	//fnEscreve("QunXraEOVrg¢");

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
								
									
									<?php $abaCampanhas = 1050; include "abasCampanhasConfig.php"; ?>
																		
									<div class="push30"></div> 
									
									<div class="row">
									
									<h3 style="margin: 0 0 20px 15px;">Que tipo de <strong>benefício</strong> será concedido?</h3>
									
									<?php 
								
									//itens do segmento
									$sql2 = "select * from TIPOCAMPANHA order by NUM_ORDENAC";
									$arrayQuery = mysqli_query($connAdm->connAdm(),$sql2) or die(mysqli_error());
									
									while ($qrLista = mysqli_fetch_assoc($arrayQuery)) 
										{
											
										?>

										<div class="col-md-2">
										<?php if ($qrLista['LOG_ATIVO'] == "N") { ?>
										<div class="disabledBlock"></div>
										<?php } ?>

											<a href="action.php?mod=<?php echo fnEncode(1040)?>&id=<?php echo fnEncode($cod_empresa);?>&idx=<?php echo $_GET['idx']; ?>&idp=<?php echo fnEncode($qrLista['COD_TPCAMPA']); ?>" class="tile tile-default shadow" style="color: #2c3e50;">
												<span class="<?php echo $qrLista['DES_ICONE']; ?>"></span>
												<p style="height: 50px;"><?php echo $qrLista['NOM_TPCAMPA']; ?></p>                            
												<div class="informer informer-default" style="color: #2c3e50;"></div>
											</a>                        
										</div>												

										<?php	
										}																										
										?> 
									
									</div>
									
									<div class="push20"></div>
									
									<div class="row">
									
										<h3 style="margin: 0 0 20px 15px;">Utilize suas <strong>Campanhas</strong> já criadas</h3>
									
										
										<?php
											$sql = "select * from campanha where cod_empresa = ".$cod_empresa." and LOG_ATIVO = 'S' order by DES_CAMPANHA ";
											//fnEscreve($sql);
											$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
											
											$count=0;
											while ($qrListaCampanha = mysqli_fetch_assoc($arrayQuery))
											  {	                                           
												$count++;                                                                                                
										?>										  
										
										<div class="col-md-2">
										
											<div class="panel">
												<div class="top primaryPanel" style="background-color: #<?php echo $qrListaCampanha['DES_COR'] ?>"><i class="<?php echo $qrListaCampanha['DES_ICONE'] ?> fa-3x iwhite" aria-hidden="true"></i>
												<a class="btnEdit addBox" data-url="action.php?mod=<?php echo fnEncode(1040)?>&id=<?php echo fnEncode($cod_empresa)?>&idc=<?php echo fnEncode($qrListaCampanha['COD_CAMPANHA'])?>&pop=true" data-title="Campanha / <?php echo $qrListaCampanha['DES_CAMPANHA']; ?>"><i class="fa fa-edit" aria-hidden="true"></i></a>
												<a href="action.php?mod=<?php echo fnEncode(1022)?>&id=<?php echo fnEncode($cod_empresa)?>&idc=<?php echo fnEncode($qrListaCampanha['COD_CAMPANHA'])?>">
												<h6 style="background-color: #<?php echo $qrListaCampanha['DES_COR'] ?>"><?php echo $qrListaCampanha['DES_CAMPANHA'] ?></h6>    	     
												</div>
												<div class="bottom">
												<h2>000</h2>
												<h6>clientes participantes </h6>
												</div>
												</a>
											</div>

										</div>

									<?php
										  }											
									
									?>
									
									</div>
									
									<div class="push100"></div>
									
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
								
												
								</div>
							</div>
							<!-- fim Portlet -->
						</div>
						
					</div>					
						
					<div class="push20"></div> 
	
	<script type="text/javascript">
	
		$(document).ready(function(){


		});		
	
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