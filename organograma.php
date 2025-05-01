<?php


//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
echo fnDebug('true');
	
	//echo "<h5>_".$opcao."</h5>";

	$hashLocal = mt_rand();	
	$refresh = "N";

	$tipo = fnDecode(@$_GET['tipo']);	
	$cod_empresa = fnDecode(@$_GET['id']);	
	$sql="SELECT COD_EMPRESA, NOM_FANTASI, COD_CHAVECO, LOG_CATEGORIA, LOG_AUTOCAD
		  FROM empresas WHERE COD_EMPRESA=$cod_empresa";
	$qrBuscaEmpresa = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(),trim($sql)));
	
	$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
	$popup = @$_GET["pop"];
	
	
	
	if (@$_GET["idT"] <> ""){
		$cod_organograma = fnDecode(@$_GET["idT"]);
		$sql="SELECT * FROM ORGANOGRAMA WHERE COD_EMPRESA=$cod_empresa AND COD_ORGANOGRAMA=$cod_organograma";
		$qrBusca = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),trim($sql)));

		$cod_organograma = fnLimpacampoZero($qrBusca['COD_ORGANOGRAMA']);
		$nom_organograma = fnLimpacampo($qrBusca['NOM_ORGANOGRAMA']);
		$des_organograma = fnLimpacampo($qrBusca['DES_ORGANOGRAMA']);
	}
	
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
			

			$cod_organograma = fnLimpacampoZero($_REQUEST['COD_ORGANOGRAMA']);
			$nom_organograma = fnLimpacampo($_REQUEST['NOM_ORGANOGRAMA']);
			$des_organograma = fnLimpacampo($_REQUEST['DES_ORGANOGRAMA']);
			$refresh = "S";

			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
						
			if ($opcao != ''){

				$sql = "CALL SP_ALTERA_ORGANOGRAMA (
				 '".$cod_organograma."', 
				 '".$cod_empresa."', 
				 '".$nom_organograma."', 
				 '".$des_organograma."',
				 '".$opcao."'
				) ";
				
				//echo $sql;exit;
				
				mysqli_query(connTemp($cod_empresa,''),trim($sql)) or die(mysqli_error());				
				
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

      
	//fnMostraForm();

?>
<style>
table a:not(.btn), .table a:not(.btn) {
    text-decoration: none;
}
table a:not(.btn):hover, .table a:not(.btn):hover {
    text-decoration: underline;
}

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


	<div class="push30"></div> 
	
	<div class="row">
			
			<?php if ($tipo == "ORG"){ ?>


				<div class="col-md12 margin-bottom-30 portlet portlet-bordered">
					<!-- Portlet -->
					<div class="portlet-title">
						<div class="caption">
							<i class="fas fa-boxes"></i>
							<span class="text-primary"> <?php echo $NomePg." - ".$nom_organograma; ?></span>
						</div>
						<?php include "atalhosPortlet.php"; ?>
					</div>
					<div class="portlet-body">
						<?php include("organograma_dados.php") ?>
					</div>
					
					<div class="push20"></div> 
				</div>

				
				
			<?php }elseif ($popup != true){ ?>
		
				<div class="col-md12 margin-bottom-30 portlet portlet-bordered">
					<!-- Portlet -->
					<div class="portlet-title">
						<div class="caption">
							<i class="fas fa-boxes"></i>
							<span class="text-primary"> <?php echo $NomePg; ?></span>
						</div>
						<?php include "atalhosPortlet.php"; ?>
					</div>
					<div class="portlet-body">
					
						<div class="col-md-2">

							<div class="panelBox borda">
							
							<div class="addBox" data-url="action.php?mod=<?=@$_GET["mod"]?>&id=<?=fnEncode($cod_empresa)?>&tipo=<?=fnEncode('CAD')?>&pop=true" data-title="Organograma">
							<i class="fa fa-plus fa-2x" aria-hidden="true" style="margin: 55px 0 60px 0;"></i>
							</div>											
							</div> 
							
						</div>
						
						<div id="listaOrganogramas">
						<?php 
							$sql = "SELECT  * FROM ORGANOGRAMA WHERE cod_empresa = $cod_empresa ORDER BY NOM_ORGANOGRAMA";
									
							$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
							
							$count=0;
							while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery))
							  {														  
								$count++;	
								?>
								
								<div class="col-md-2">  
									<div class='tile tile-default shadow change-icon' style='color: #2c3e50; border: none'>
										<a data-url="action.php?mod=<?=@$_GET["mod"]?>&id=<?=fnEncode($cod_empresa)?>&idT=<?=fnEncode($qrBuscaModulos['COD_ORGANOGRAMA']); ?>&tipo=<?php echo fnEncode('ALT')?>&pop=true" data-title="Organograma" class="informer informer-default addBox" style="color: #2c3e50;">
											<span class="fa fa-edit"></span>
										</a>
										<a href='action.php?mod=<?=@$_GET["mod"]?>&id=<?=fnEncode($cod_empresa)?>&idT=<?=fnEncode($qrBuscaModulos['COD_ORGANOGRAMA'])?>&tipo=<?=fnEncode('ORG')?>' style='color: #2c3e50; border: none; text-decoration: none; text-align:center;'>
											<div class="push30"></div> 
											<center><i class="fal fa-file-check fa-lg" style="font-size: 40px"></i></center>
											<div class="push20"></div> 
											<p class="folder"><?php echo $qrBuscaModulos['NOM_ORGANOGRAMA']; ?></p>
										</a>
									</div> 										
								</div>					
						<?php			
								  }											
						?>
						</div>
					</div>
					
					<div class="push20"></div> 
				</div>

			<?php }else{ ?>			
				
				<?php if ($msgRetorno <> '') { ?>	
				<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				 <?php echo $msgRetorno; ?>
				</div>
				<?php } ?>	

				<script>
				window.parent.$("#REFRESH").val("<?=$refresh?>");
				</script>
			
				<div class="login-form">
				
					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
												
					<fieldset>
						<legend>Dados Gerais</legend> 
						
							<div class="row">
								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Código</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_ORGANOGRAMA" id="COD_ORGANOGRAMA" value="">
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Empresa</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa; ?>">
										<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
									</div>														
								</div>
								
								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label required">Nome</label>
										<input type="text" class="form-control input-sm" name="NOM_ORGANOGRAMA" id="NOM_ORGANOGRAMA" maxlength="50" data-error="Campo obrigatório" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>
								
								<textarea style='display:none;' class="form-control input-sm" name="DES_ORGANOGRAMA" id="DES_ORGANOGRAMA" style="display:non;height:100px;">{}</textarea>
								
							</div>
							
					</fieldset>

					<div class="push10"></div>
					<hr>	
					<div class="form-group text-right col-lg-12">
						
						<?php if ($tipo == "CAD"){ ?>
							<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
						<?php }else{ ?>
							<script>
							$(document).ready(function(){
								$("#formulario #COD_ORGANOGRAMA").val("<?=$cod_organograma?>");
								$("#formulario #NOM_ORGANOGRAMA").val("<?=$nom_organograma?>");
								$("#formulario #DES_ORGANOGRAMA").val("<?=$des_organograma?>");
								$('#formulario').validator('validate');
								$("#formulario #hHabilitado").val('S');
							});
							</script>
							<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Alterar</button>
							<button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fa fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>
						<?php } ?>
						
					</div>
					
					<input type="hidden" name="opcao" id="opcao" value="">
					<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
					<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">		
					
					<div class="push5"></div> 
					
					</form>

				
					<div class="push"></div>
				
				</div>								
			
				<!-- fim Portlet -->
				
			<?php } ?>

		<input type="hidden" class="input-sm" name="REFRESH" id="REFRESH" value="<?=$refresh?>">
	</div>					
		
	<div class="push20"></div> 





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
	
	
	

	<script type="text/javascript">

		
		$(document).ready(function(){

			//chosen
			$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
			$('#formulario').validator();

			
			//modal close
			$('.modal').on('hidden.bs.modal', function () {
			  console.log($("#REFRESH").val());
			  if ($("#REFRESH").val() == "S"){
				RefreshOrganogramas("<?=$cod_empresa?>");
				$('#REFRESH').val("N");				
			  }
			});
			
		});	
		
		
		function RefreshOrganogramas(idEmp) {
			$.ajax({
				type: "GET",
				url: "ajxRefreshOrganogramas.php",
				data: { ajx1:idEmp},
				beforeSend:function(){
					$('#listaOrganogramas').html('<div class="loading" style="width: 100%;"></div>');
				},
				success:function(data){
					$("#listaOrganogramas").html(data); 
				},
				error:function(){
					$('#listaOrganogramas').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
				}
			});		
		}


		function getOrganograma(){
			if ($("#DES_ORGANOGRAMA").val() == "{}"){
				$("#DES_ORGANOGRAMA").val('{"id":"1583941908460926","title":"In&iacute;cio","content":""}');
			}
			return JSON.parse($("#DES_ORGANOGRAMA").val());
		}
		function setOrganograma(data){
			if (data == "Error: nodes do not exist"){
				$("#DES_ORGANOGRAMA").val("{}");
			}else{
				$("#DES_ORGANOGRAMA").val(JSON.stringify(data));
			}
			$(".close").click();
		}
		
		function retornaForm(index){
			$("#formulario #COD_ORGANOGRAMA").val($("#ret_COD_ORGANOGRAMA_"+index).val());
			$("#formulario #NOM_ORGANOGRAMA").val($("#ret_NOM_ORGANOGRAMA_"+index).val());
			$("#formulario #DES_ORGANOGRAMA").val($("#ret_DES_ORGANOGRAMA_"+index).val());
			$('#formulario').validator('validate');
			$("#formulario #hHabilitado").val('S');
		}

		

	</script>