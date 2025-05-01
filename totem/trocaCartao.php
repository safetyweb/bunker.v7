<?php
	
	//echo "<h5>_".$opcao."</h5>";
	include "../_system/_functionsMain.php";
	$popUp = 'true';
	$parametros = fnDecode($_GET['key']);
	$arrayCampos = explode(";", $parametros);
	$cod_univend = $arrayCampos[2];
	$cod_empresa = $arrayCampos[4];
	$cod_cliente = fnDecode($_GET['idc']);

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

			$num_cartao = fnLimpaCampoZero($_REQUEST['NUM_CARTAO']);

			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
						
			if ($opcao != ''){
				
				
				//mensagem de retorno
				switch ($opcao)
				{
					case 'CAD':

						?>
						<script>
							try { parent.$('#NUM_CARTAO').val("<?=$num_cartao?>"); } catch(err) {}		
							$(this).removeData('bs.modal');	
							parent.$('#popModal').modal('hide');
						</script>
						<?php
						exit();

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
			<html lang="pt">
			    <head>

				<meta name="viewport" content="width=device-width,height=device-height,initial-scale=1.0"/>
				
				<meta charset="utf-8">
				<meta http-equiv="X-UA-Compatible" content="IE=9"/>
				<meta http-equiv="X-UA-Compatible" content="IE=10"/>
				<meta http-equiv="X-UA-Compatible" content="IE=11"/>
				
				<title>Totem</title>
					
				<link href="http://bunker.mk/css/bootstrap.flatly.min.css" rel="stylesheet">
				<script src="http://bunker.mk/js/jquery.min.js"></script>
				
				<!-- JQUERY-CONFIRM -->
				<link href="http://bunker.mk/css/jquery-confirm.min.css" rel="stylesheet"/>
				<script src="http://bunker.mk/js/jquery-confirm.min.js"></script>
				
				<!-- extras -->
				<link href="http://bunker.mk/css/jquery.webui-popover.min.css" rel="stylesheet" />
				<link href="http://bunker.mk/css/chosen-bootstrap.css" rel="stylesheet" />
				
				<link href="http://bunker.mk/css/font-awesome.min.css" rel="stylesheet" />
				
			    <!-- complement -->
				<link href="http://bunker.mk/css/default.css" rel="stylesheet" />
				<link href="http://bunker.mk/css/checkMaster.css" rel="stylesheet" />
					
				<!--[if lt IE 9]><script src="js/ie8-responsive-file-warning.js"></script><![endif]
				<script src="http://bunker.mk/js/plugins/ie-emulation-modes-warning.js"></script>-->
				<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
				<!--[if lt IE 9]>
				  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
				  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
				<![endif]-->
				
				<!--[if IE]>
				  <link rel="stylesheet" type="text/css" href="http://bunker.mk/css/totem.css" />
				<![endif]-->	

				<!-- Favicons -->
				<!-- Favicons -->
				<link rel="icon" href="images/favicon.ico">
				
			    </head>

			    <style>
			    	
			    	.jconfirm-content-pane{
			    		height: 80px!important;
			    	}

			    </style>
				
			    <body>

			
					<div class="push30"></div> 
					
					<div class="row">				
					
						<div class="col-md12 margin-bottom-30">
							<!-- Portlet -->
							<?php if ($popUp != "true"){  ?>							
							<div class="portlet portlet-bordered">
							<?php } else { ?>
							<div class="portlet" style="padding: 0 20px 20px 20px;" >
							<?php } ?>
							
								<?php if ($popUp != "true"){  ?>
								<div class="portlet-title">
									<div class="caption">
										<i class="glyphicon glyphicon-calendar"></i>
										<span class="text-primary"><?php echo $NomePg; ?></span>
									</div>
									<?php include "atalhosPortlet.php"; ?>
								</div>
								<?php } ?>
								<div class="portlet-body">
									
									<?php if ($msgRetorno <> '') { ?>	
									<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
									<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									 <?php echo $msgRetorno; ?>
									</div>
									<?php } ?>	
									
									<div class="push30"></div> 
			
									<div class="login-form">
									
										<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
																				
											<fieldset>
												<legend>Dados Gerais</legend> 

													<div class="row">
														
														<div class="col-md-4">
															<div class="form-group">
																<label for="inputName" class="control-label"><span id="LBL_CARTAO">Número do Cartão</span></label>
																<input type="text" class="form-control input-sm" name="NUM_CARTAO" id="NUM_CARTAO" value="<?=$num_cartao?>">
																<div class="help-block with-errors"></div>
															</div>
														</div>
																					
													</div>
													
											</fieldset>	
																					
											<div class="push10"></div>
											<hr>	
											<div class="form-group text-right col-lg-12">
												
												  <button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>
												  <span id="TP_BTN"><button type="submit" name="BUS" id="BUS" class="btn btn-primary getBtn"><i class="fas fa-search" aria-hidden="true"></i>&nbsp; Buscar</button></span>
												  <!-- <button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Alterar</button>
												  <button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fa fa-times" aria-hidden="true"></i>&nbsp; Excluir</button> -->
												
											</div>
											
											<input type="hidden" name="opcao" id="opcao" value="">
											<input type="hidden" name="TAM_LOTE" id="TAM_LOTE" value="">
											<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
											<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">		
											
											<div class="push5"></div> 
										
										</form>
										
										<div class="push50"></div>
										
										<div class="col-lg-12">

											<div class="no-more-tables">
										
												<form name="formLista">
												
												<table id="TAB_CARTOES" class="table table-bordered table-striped table-hover tableSorter">
												  <thead>
													<tr>
													  <th class="{ sorter: false }" width="40"></th>
													  <th>Nro. Cartão</th>
													</tr>
												  </thead>
												<tbody>
												  
												<?php

													if($num_cartao != ""){
														
												
														$sql = "SELECT GC.NUM_CARTAO, LC.NUM_TAMANHO FROM GERACARTAO GC
																INNER JOIN LOTECARTAO LC ON LC.COD_LOTCARTAO=GC.COD_LOTCARTAO
																WHERE GC.COD_EMPRESA = $cod_empresa 
																AND GC.COD_USUALTE = 0 
																AND GC.LOG_USADO = 'N'
																AND GC.NUM_CARTAO = $num_cartao
																LIMIT 10";

														$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);

														$tam_lote = 0;
														
														$count=0;

														while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery)){

															$tam_lote = $qrBuscaModulos['NUM_TAMANHO'];

															$count++;

															echo"
																<tr>
																  <td><a href='javascript: downForm(".$count.")' style='margin-left: 10px;'><i class='fa fa-arrow-circle-down' aria-hidden='true'></i></a></th>
																  <td>".$qrBuscaModulos['NUM_CARTAO']."</td>
																</tr>
																<input type='hidden' id='ret_NUM_CARTAO_".$count."' value='".$qrBuscaModulos['NUM_CARTAO']."'>
															";

														}

													}

													// fnEscreve($tam_lote);										

												?>
													
												</tbody>
												</table>
												
												</form>

											</div>
											
										</div>										
									
									<div class="push"></div>
									
									</div>								
								
								</div>
							</div>
							<!-- fim Portlet -->
						</div>
						
					</div>					
						
					<div class="push20"></div> 

				</div>

		</body>

		<script src="http://bunker.mk/js/chosen.jquery.min.js"></script>
	
	<script type="text/javascript">

		$(function(){

			var tipo = "<?=$tipo?>",
			cod_chaveco = "<?=$cod_chaveco?>",
			tam_lote = "<?=$tam_lote?>";

			if(tipo != "troca" && cod_chaveco == 5){

				var count = 0;

				$("#NUM_CARTAO").keyup(function(){

					if(this.value.length > tam_lote){

						if(count == 0){
							$("#opcao").val("CAD");
							$("#LBL_CARTAO").text("CPF");
							$("#TAB_CARTOES").fadeOut("fast");
							$("#TP_BTN").html("<button form='formulario' type='submit' name='CAD' id='CAD' class='btn btn-primary getBtn'><i class='fas fa-plus' aria-hidden='true'></i>&nbsp; Usar CPF</button>");
							count = 1;
						}
						
					}else{
						$("#opcao").val("BUS");
						$("#LBL_CARTAO").text("Número do Cartão");
						$("#TAB_CARTOES").fadeIn("fast");
						$("#TP_BTN").html("<button form='formulario' type='submit' name='BUS' id='BUS' class='btn btn-primary getBtn'><i class='fas fa-search' aria-hidden='true'></i>&nbsp; Buscar</button>");
						count = 0;
					}
				});

			}

			$(".chosen-select-deselect").chosen();

		});

		function downForm(index){
			// alert('');

				$.alert({
                    title: "Selecione um motivo para a troca:",
                    height:'70px',
                    content: '<form id="formularioMotivo">'+
	                    		'<div class="col-md-4">'+
									'<div class="form-group">'+
										'<label for="inputName" class="control-label">Motivo da Troca</label>'+
											'<select data-placeholder="Selecione um motivo da troca" name="COD_TIPMOTI" id="COD_TIPMOTI" class="chosen-select-deselect">'+
												'<option value="">&nbsp;</option>'+					
												<?php																	
													$sql = "select * from TIPOMOTIVO_CARTAO order by DES_TPMOTIV ";
													$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
												
													while ($qrListaMotivo = mysqli_fetch_assoc($arrayQuery))
													{													
														echo"
															  '<option value=$qrListaMotivo[COD_TIPMOTI]>$qrListaMotivo[DES_TPMOTIV]</option>'+
															"; 
													}											
												?>	
											'</select>'+	
										'<div class="help-block with-errors"></div>'+
									'</div>'+
								'</div>'+
								'<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?=$cod_empresa?>">'+
								'<input type="hidden" name="COD_CLIENTE" id="COD_CLIENTE" value="<?=$cod_cliente?>">'+
								'<input type="hidden" name="NUM_CARTAO_NOVO" id="NUM_CARTAO_NOVO" value="'+$("#ret_NUM_CARTAO_"+index).val()+'">'+
							'</form>',
                    buttons: {
						Ok: function () {
							$.ajax({
								method: 'POST',
								url: 'ajxSalvaMotivoTroca.php',
								data: $("#formularioMotivo").serialize(),
								success:function(data){
									$.alert({
					                    title: "Sucesso",
					                    content: 'Cartão alterado.',
					                    buttons: {
											Ok: function () {
												try { parent.$('#c10').val($("#ret_NUM_CARTAO_"+index).val()); } catch(err) {}
												$(this).removeData('bs.modal');	
												parent.$('#popModal').modal('hide');
												parent.$('#CAD').prop('disabled',false).removeClass('disabled');
											}
										}
									});
								}
							});
						},
						Cancelar: function(){

						}
					}
                });				
					
		}	
		
	</script>

<html>