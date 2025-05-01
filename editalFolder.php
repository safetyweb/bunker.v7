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
	      
	//fnMostraForm();
	//fnEscreve($cod_checkli);

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

						<div class="col-md-2">  
							<a href="action.php?mod=<?php echo fnEncode(1096)?>&id=<?php echo fnEncode($cod_empresa)?>" class="tile tile-default shadow" style="color: #2c3e50; border: none">
							<div class="item">
								<span class="fa fa-arrow-circle-left"></span>
							</div>
								<p style="height: 60px;">Voltar</p>                            
								<div class="informer informer-default" style="color: #2c3e50;"></div>
							</a>  
						</div>	
						
						<div class="col-md-2">  
							<a href="" class="tile tile-default shadow" style="color: #2c3e50; border: none">
							<div class="item">
								<span class="notify-badge">2</span>
								<span class="fa fa-newspaper"></span>
							</div>
								<p style="height: 60px;">Edital</p>                            
								<div class="informer informer-default" style="color: #2c3e50;"><span class="fa fa-edit"></span></div>
							</a>  
						</div>
						
						<div class="col-md-2">
							<a href="" class="tile tile-default shadow" style="color: #2c3e50; border: none">
							<div class="item">
								<span class="notify-badge">15</span>
								<span class="fa fa-building"></span>
							</div>
								<p style="height: 60px;">Empresas</p>                            
								<div class="informer informer-default" style="color: #2c3e50;"><span class="fa fa-edit"></span></div>
							</a>  
						</div>	
						
						<div class="col-md-2">
							<a href="" class="tile tile-default shadow" style="color: #2c3e50; border: none">
							<div class="item">
								<span class="notify-badge" style="background: green;">1</span>
								<span class="fa fa-gavel"></span>
							</div>
								<p style="height: 60px;">Licitação</p>                            
								<div class="informer informer-default" style="color: #2c3e50;"><span class="fa fa-edit"></span></div>
							</a>  
						</div>
						
						<div class="push20"></div>
						
						<div class="col-md-2"><button type="reset" class="btn btn-default"><i class="fa fa-ban" aria-hidden="true"></i>&nbsp; Visualizar Pendências</button>					
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