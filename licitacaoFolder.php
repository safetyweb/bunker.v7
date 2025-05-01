<?php
	
	//echo fnDebug('true');
	
	$log_obrigat = "N";
 
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
			
			$cod_conveni = fnLimpaCampoZero($_REQUEST['COD_CONVENI']);
			$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
			$cod_entidad = fnLimpaCampoZero($_REQUEST['COD_ENTIDAD']);
			$num_process = fnLimpaCampo($_REQUEST['NUM_PROCESS']);
			$num_conveni = fnLimpaCampo($_REQUEST['NUM_CONVENI']);
			$nom_conveni = fnLimpaCampo($_REQUEST['NOM_CONVENI']);
			$nom_abrevia = fnLimpaCampo($_REQUEST['NOM_ABREVIA']);
			$des_descric = fnLimpaCampo($_REQUEST['DES_DESCRIC']);
			$val_valor = fnLimpaCampo($_REQUEST['VAL_VALOR']);
			$val_contpar = fnLimpaCampo($_REQUEST['VAL_CONTPAR']);
			$dat_inicinv = fnLimpaCampo($_REQUEST['DAT_INICINV']);
			$dat_fimconv = fnLimpaCampo($_REQUEST['DAT_FIMCONV']);
			$dat_assinat = fnLimpaCampo($_REQUEST['DAT_ASSINAT']);
			
			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
			
			$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
                      
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
		$sql = "SELECT NOM_EMPRESA FROM EMPRESAS WHERE COD_EMPRESA = ".$cod_empresa;	
				
		//fnEscreve($sql);
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
			
		if (isset($qrBuscaEmpresa)){
			$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
		}
												
	}else {	
		$nom_empresa = "";
	}

	if(isset($_GET['idC'])){
		if (is_numeric(fnLimpacampo(fnDecode($_GET['idC'])))){
		
			//busca dados do convênio
			$cod_conveni = fnDecode($_GET['idC']);	
			$sql = "SELECT * FROM CONVENIO WHERE COD_CONVENI = ".$cod_conveni;	
			
			//fnEscreve($sql);
			$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
			$qrBuscaTemplate = mysqli_fetch_assoc($arrayQuery);
				
			if (isset($qrBuscaTemplate)){
				$cod_conveni = $qrBuscaTemplate['COD_CONVENI'];
				$cod_entidad = $qrBuscaTemplate['COD_ENTIDAD'];
				$num_process = $qrBuscaTemplate['NUM_PROCESS'];
				$num_conveni = $qrBuscaTemplate['NUM_CONVENI'];
				$cod_tpconveni = $qrBuscaTemplate['COD_TPCONVENI'];
				$nom_conveni = $qrBuscaTemplate['NOM_CONVENI'];
				$nom_abrevia = $qrBuscaTemplate['NOM_ABREVIA'];
				$des_descric = $qrBuscaTemplate['DES_DESCRIC'];
				$val_valor = fnValor($qrBuscaTemplate['VAL_VALOR'],2);
				$val_conced = fnValor($qrBuscaTemplate['VAL_CONCED'],2);
				$val_contpar = fnValor($qrBuscaTemplate['VAL_CONTPAR'],2);
				$dat_inicinv = fnDataShort($qrBuscaTemplate['DAT_INICINV']);
				$dat_fimconv = fnDataShort($qrBuscaTemplate['DAT_FIMCONV']);
				$dat_assinat = fnDataShort($qrBuscaTemplate['DAT_ASSINAT']);
				$log_licitacao = $qrBuscaTemplate['LOG_LICITACAO'];
			
			}

		$leitura = "disabled";
			
		}else{
			$cod_conveni = "";
			$cod_entidad = "";
			$num_process = "";
			$num_conveni = "";
			$cod_tpconveni = "";
			$nom_conveni = "";
			$nom_abrevia = "";
			$des_descric = "";
			$val_valor = "";
			$val_conced = "";
			$val_contpar = "";
			$dat_inicinv = "";
			$dat_fimconv = "";
			$dat_assinat = "";
			$log_licitacao = "";
			$leitura = "";
		}
	}
	      
	//fnMostraForm();
	//fnEscreve($cod_checkli);

?>

<style>
	
.collapse-chevron .fa {
  transition: .3s transform ease-in-out;
}

.collapse-chevron .collapsed .fa {
  transform: rotate(-90deg);
}

</style>
	
	<link rel="stylesheet" href="css/widgets.css" />
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
					<?php include "atalhosPortlet.php"; ?>
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
								
						<div class="col-md-3 col-md-offset-1">
							<div class="form-group">
								<label for="inputName" class="control-label">Nome</label>
								<input type="text" class="form-control input-sm leitura" name="NOM_CONVENI" id="NOM_CONVENI" value="<?php echo $nom_conveni; ?>" maxlength="60" readonly>
							</div>
							<div class="help-block with-errors"></div>
						</div>


						<div class="col-md-2">
							<div class="form-group">
								<label for="inputName" class="control-label required">Data Inicial</label>
								<input type='text' class="form-control input-sm data leitura" name="DAT_INICINV" id="DAT_INICINV" value="<?=$dat_inicinv?>" readonly/>
							</div>
						</div>       
			
						<div class="col-md-2">
							<div class="form-group">
								<label for="inputName" class="control-label required">Data Final</label>
								<input type='text' class="form-control input-sm data leitura" name="DAT_FIMCONV" id="DAT_FIMCONV" value="<?=$dat_fimconv?>" readonly/>
							</div>
						</div>								
										
						<?php																	
							$sql = "SELECT * FROM ENTIDADE WHERE COD_ENTIDAD = $cod_entidad";
							$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
							//fnEscreve($cod_entidad);
							while ($qrListaTipoEntidade = mysqli_fetch_assoc($arrayQuery))
							{
							?>

							<div class="col-md-3">
								<div class="form-group">
									<label for="inputName" class="control-label">Entidade</label>
									<input type="text" class="form-control input-sm leitura" name="NOM_CONVENI" id="NOM_CONVENI" value="<?php echo $qrListaTipoEntidade['NOM_ENTIDAD']; ?>" maxlength="60" readonly>
								</div>
								<div class="help-block with-errors"></div>
							</div>
							
							<?php 													
							}											
						?>	
									

					</div>
					
					
					<div class="push30"></div>

					<div class="row">

						<div class="col-md-2">  
							<a href="action.php?mod=<?php echo fnEncode(1098)?>&id=<?php echo fnEncode($cod_empresa)?>&idC=<?php echo fnEncode($cod_conveni)?>" class="tile tile-default shadow" style="color: #2c3e50; border: none">
							<div class="item">
								<span class="fal fa-arrow-circle-left fa-2x"></span>
							</div>
								<p style="height: 60px;">Voltar</p>                            
								<div class="informer informer-default" style="color: #2c3e50;"></div>
							</a>  
						</div>	
						
						<div class="col-md-2">  
							<a href="" class="tile tile-default shadow" style="color: #2c3e50; border: none">
							<div class="item">								
								<span class="fal fa-hand-holding-usd fa-2x"></span>
							</div>
								<p style="height: 60px;">Cotação Prévia</p>                            
								<div class="informer informer-default" style="color: #2c3e50;"></div>
							</a>  
						</div>	
						
						<div class="col-md-2">  
							<a href="action.php?mod=<?php echo fnEncode(1089)?>&id=<?php echo fnEncode($cod_empresa)?>&idC=<?php echo fnEncode($cod_conveni)?>" class="tile tile-default shadow"  style="color: #2c3e50; border: none">
							<div class="item">								
								<span class="fal fa-gavel fa-2x"></span>
							</div>
								<p style="height: 60px;">Dados da Licitação</p>                            
								<div class="informer informer-default" style="color: #2c3e50;"></div>
							</a>  
						</div>	
						
						<div class="col-md-2">
							<div class="disabledBlock"></div>
							<a href="javascript:void(0)" class="tile tile-default shadow addBox" data-title="Configuração da Documentação" ystyle="color: #2c3e50; border: none">
							<div class="item">								
								<span class="fal fa-cogs fa-2x"></span>
							</div>
								<p style="height: 60px;">Controle da Licitação</p>
								<div class="informer informer-default" style="color: #2c3e50;"></div>
							</a>  
						</div>
						
						<div class="col-md-2">  
							<a href="action.php?mod=<?php echo fnEncode(1091)?>&id=<?php echo fnEncode($cod_empresa)?>&idC=<?php echo fnEncode($cod_conveni)?>"  class="tile tile-default shadow" style="color: #2c3e50; border: none">
							<div class="item">
								<span class="notify-badge">2</span> 
								<span class="fal fa-file-contract fa-2x"></span>
							</div>
								<p style="height: 60px;">Propostas</p>                            
								<div class="informer informer-default" style="color: #2c3e50;"></div>
							</a>  
						</div>

						<div class="col-md-2">  
							<a href="action.php?mod=<?php echo fnEncode(1349)?>&id=<?php echo fnEncode($cod_empresa)?>&idC=<?php echo fnEncode($cod_conveni)?>"  class="tile tile-default shadow" style="color: #2c3e50; border: none">
							<div class="item">
								<span class="notify-badge">2</span> 
								<span class="fal fa-file-signature fa-2x"></span>
							</div>
								<p style="height: 60px;">Contratos da Licitação</p>                            
								<div class="informer informer-default" style="color: #2c3e50;"></div>
							</a>  
						</div>

					</div>

					<div class="push10"></div>	

					<div class="row">

						<div class="col-md-2">  
							<a href="action.php?mod=<?php echo fnEncode(1783)?>&id=<?php echo fnEncode($cod_empresa)?>&idc=<?php echo fnEncode($cod_conveni)?>"  class="tile tile-default shadow" style="color: #2c3e50; border: none">
							<div class="item">
								<span class="notify-badge">0</span> 
								<span class="fal fa-file-contract fa-2x"></span>
							</div>
								<p style="height: 60px;">Projeto de Execução</p>                            
								<div class="informer informer-default" style="color: #2c3e50;"></div>
							</a>  
						</div>

					</div>

							
						
						<!-- <div class="col-md-2">  
							<a href="javascript:void(0)" class="tile tile-default shadow addBox" data-title="Clientes (Geral)" data-url="action.php?mod=<?php echo fnEncode(1350)?>&id=<?php echo fnEncode($cod_empresa)?>&idC=<?php echo fnEncode($cod_conveni)?>&pop=true" style="color: #2c3e50; border: none">
							<div class="item">
								<span class="notify-badge">2</span>
								<span class="fas fa-building"></span>
							</div>
								<p style="height: 60px;">Empresas</p>                            
								<div class="informer informer-default" style="color: #2c3e50;"></div>
							</a>  
						</div>
						
						<div class="col-md-2">
							<a href="javascript:void(0)" class="tile tile-default shadow addBox" data-title="Formalização" data-url="action.php?mod=<?php echo fnEncode(1345)?>&id=<?php echo fnEncode($cod_empresa)?>&idC=<?php echo fnEncode($cod_conveni)?>&pop=true" style="color: #2c3e50; border: none">
							<div class="item">
								<span class="notify-badge" style="background: green;">1</span>
								<span class="fas fa-calendar-check"></span>
							</div>
								<p style="height: 60px;">Formalização</p>                            
								<div class="informer informer-default" style="color: #2c3e50;"></div>
							</a>  
						</div> -->
						
						<div class="push20"></div>

						<div class="row">
						
							<div class="col-md-2" style="margin-right: -43px;">
								<div class="collapse-chevron">
									<a data-toggle="collapse" class="collapsed btn btn-sm btn-default" href="#collapseFilter" style="width: 90%;">
								    	<span class="fa fa-chevron-down" aria-hidden="true"></span>&nbsp;
								    	Visualizar Pendências 
									</a>
								</div>
							</div>

						</div>

						<div class="row">

							<div class="col-md-2">
								<div class="collapse" id="collapseFilter">
									<h4>Pêndências</h4>
							    	<!-- <table class="table">
							    		<tbody id="#">
							    			
							    		</tbody>
							    	</table> -->
								</div>
							</div>

						</div>
							
				</div>										
					
				<div class="push50"></div>
					

		
			</div>
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
	
	<script type="text/javascript">
	
		function retornaForm(index){
			$("#formulario #COD_CONVENI").val($("#ret_COD_CONVENI_"+index).val());
			$("#formulario #COD_EMPRESA").val($("#ret_COD_EMPRESA_"+index).val());
			$("#formulario #COD_ENTIDAD").val($("#ret_COD_ENTIDAD_"+index).val()).trigger("chosen:updated");
			$("#formulario #NUM_PROCESS").val($("#ret_NUM_PROCESS_"+index).val());
			$("#formulario #NUM_CONVENI").val($("#ret_NUM_CONVENI_"+index).val());
			$("#formulario #NOM_CONVENI").val($("#ret_NOM_CONVENI_"+index).val());
			$("#formulario #NOM_ABREVIA").val($("#ret_NOM_ABREVIA_"+index).val());
			$("#formulario #DES_DESCRIC").val($("#ret_DES_DESCRIC_"+index).val());
			$("#formulario #VAL_VALOR").unmask().val($("#ret_VAL_VALOR_"+index).val());
			$("#formulario #VAL_CONTPAR").unmask().val($("#ret_VAL_CONTPAR_"+index).val());
			$("#formulario #DAT_INICINV").unmask().val($("#ret_DAT_INICINV_"+index).val());
			$("#formulario #DAT_FIMCONV").unmask().val($("#ret_DAT_FIMCONV_"+index).val());
			$("#formulario #DAT_ASSINAT").unmask().val($("#ret_DAT_ASSINAT_"+index).val());
			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');			
		}
		
	</script>	