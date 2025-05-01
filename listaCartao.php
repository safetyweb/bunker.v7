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

	if(isset($_GET['opcao'])){
		$tipo = $_GET['opcao'];
	}else{
		$tipo = "";
	}

	if(isset($_GET['idC'])){
		$cod_chaveco = fnDecode($_GET['idC']);
	}else{
		$cod_chaveco = 0;
	}
	
	//fnMostraForm();

?>
			
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
														$andNumCartao = "AND GC.NUM_CARTAO = $num_cartao";
													}else{
														$andNumCartao = "";
													}
												
													$sql = "SELECT GC.NUM_CARTAO, LC.NUM_TAMANHO FROM GERACARTAO GC
															INNER JOIN LOTECARTAO LC ON LC.COD_LOTCARTAO=GC.COD_LOTCARTAO
															WHERE GC.COD_EMPRESA = $cod_empresa 
															AND GC.COD_USUALTE = 0 
															AND GC.LOG_USADO = 'N'
															$andNumCartao
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
	
	<script type="text/javascript">

		$(function(){

			var tipo = "<?=$tipo?>",
			cod_chaveco = <?=$cod_chaveco?>,
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

		});

		function downForm(index){
				
				try { parent.$('#NUM_CARTAO').val($("#ret_NUM_CARTAO_"+index).val()); } catch(err) {}		
				
				$(this).removeData('bs.modal');	
				//console.log('entrou' + index);
				parent.$('#popModal').modal('hide');
			
					
		}	
		
	</script>	