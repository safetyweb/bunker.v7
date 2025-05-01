	<?php	
	//echo fnDebug('true');
	
	$log_ativo = 'N';
	
	$cod_template = "";
 
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
			
			$cod_template = fnLimpaCampoZero($_REQUEST['COD_TEMPLATE']);
			$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
			if (empty($_REQUEST['LOG_ATIVO'])) {$log_ativo='N';}else{$log_ativo=$_REQUEST['LOG_ATIVO'];}
			$nom_template = fnLimpaCampo($_REQUEST['NOM_TEMPLATE']);
			$abv_template = fnLimpaCampo($_REQUEST['ABV_TEMPLATE']);
			$des_template = fnLimpaCampo($_REQUEST['DES_TEMPLATE']);
			
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
		$cod_campanha = fnDecode($_GET['idc']);	
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
	
	//liberação das abas
	$abaPersona	= "S";
	$abaVantagem = "S";
	$abaRegras = "S";
	$abaComunica = "N";
	$abaAtivacao = "N";
	$abaResultado = "N";

	//$abaPersonaComp = "completed ";
	$abaPersonaComp = " ";
	$abaVantagemComp = " ";
	$abaRegrasComp = " ";
	$abaComunicaComp = "active";
	$abaAtivacaoComp = "";
	$abaResultadoComp = "";		
	      
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
						
					<?php $abaCampanhas = 1254; include "abasCampanhasConfig.php"; ?>
													
					<div class="push50"></div> 
							
<style>
	.change-icon .fa + .fa,
	.change-icon:hover .fa:not(.fa-edit) {
	  display: none;
	}
	.change-icon:hover .fa + .fa:not(.fa-edit){
	  display: inherit;
	}
	
	.fa-edit:hover{
		color: #18bc9c;
		cursor: pointer;
	}
	
	.item{
		padding-top: 0;
	}
</style>

	<div class="col-md-2">

		<div class="panelBox borda">
		
		<div class="addBox" data-url="action.php?mod=<?php echo fnEncode(1255)?>&id=<?php echo fnEncode($cod_empresa)?>&tipo=<?php echo fnEncode('CAD')?>&idc=<?=fnEncode($cod_campanha)?>&pop=true" data-title="Pesquisa">
		<i class="fa fa-plus fa-2x" aria-hidden="true" style="margin: 55px 0 60px 0;"></i>
		</div>											
		</div> 
		
	</div> 						
			
			<div id="listaPesquisas">
			<?php 
				$sql = "SELECT * FROM PESQUISA WHERE COD_EMPRESA = $cod_empresa AND COD_CAMPANHA = $cod_campanha order by DES_PESQUISA";
						
				$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
				
				$count=0;
				while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery))
				  {														  
					$count++;	
					?>
					
					<div class="col-md-2">  
						<div class='tile tile-default shadow change-icon' style='color: #2c3e50; border: none'>
							<a data-url="action.php?mod=<?php echo fnEncode(1255)?>&id=<?php echo fnEncode($cod_empresa)?>&idP=<?php echo fnEncode($qrBuscaModulos['COD_PESQUISA']); ?>&tipo=<?php echo fnEncode('ALT')?>&idc=<?=fnEncode($cod_campanha)?>&pop=true" data-title="Pesquisa" class="informer informer-default addBox" style="color: #2c3e50;">
								<span class="fa fa-edit"></span>
							</a>
							<a href='action.php?mod=<?php echo fnEncode(1510)?>&id=<?php echo fnEncode($cod_empresa)?>&idP=<?php echo fnEncode($qrBuscaModulos['COD_PESQUISA'])?>&idc=<?=fnEncode($cod_campanha)?>' style='color: #2c3e50; border: none; text-decoration: none;'>
								<div class="push30"></div>
								<i class="fal fa-list fa-lg" style="font-size: 40px"></i>
								<div class="push15"></div> 
								<!--<p class="folder"><?php echo $qrBuscaModulos['DES_PESQUISA']; ?></p>-->
								<p class="folder"><?php echo $qrBuscaModulos['DES_PESQUISA']; ?></p>
							</a>
						</div> 										
					</div>					
			<?php			
					  }											
			?>
			</div>
			
	<input type="hidden" class="input-sm" name="REFRESH_TEMPLATES" id="REFRESH_TEMPLATES" value="N">

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
	
			$(document).ready(function(){
			
			//modal close
			$('.modal').on('hidden.bs.modal', function () {
			  console.log('entrou');
			  if ($('#REFRESH_TEMPLATES').val() == "S"){
				RefreshPesquisas(<?php echo $cod_empresa; ?>);
				$('#REFRESH_TEMPLATES').val("N");				
			  }	
			});
			
		});
		
		function RefreshPesquisas(idEmp) {
			$.ajax({
				type: "GET",
				url: "ajxRefreshPesquisas.php?idc=<?=fnEncode($cod_campanha)?>",
				data: { ajx1:idEmp},
				beforeSend:function(){
					$('#listaPesquisas').html('<div class="loading" style="width: 100%;"></div>');
				},
				success:function(data){
					$("#listaPesquisas").html(data); 
				},
				error:function(){
					$('#listaPesquisas').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
				}
			});		
		}
	
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