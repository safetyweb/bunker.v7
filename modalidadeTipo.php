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
			
			$cod_tpmodal = fnLimpaCampoZero($_REQUEST['COD_TPMODAL']);
			$des_tpmodal = fnLimpaCampo($_REQUEST['DES_TPMODAL']);
			$des_abrevia = fnLimpaCampo($_REQUEST['DES_ABREVIA']);
			
			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
			
			$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
                      
			if ($opcao != ''){			
				
				$sql = "CALL SP_ALTERA_TIPOMODALIDADE (
				 '".$cod_tpmodal."', 
				 '".$des_tpmodal."',
				 '".$des_abrevia."',
				 '".$opcao."'    
			        );";
					
				$cod_empresa = fnDecode($_GET['id']);
                mysqli_query(connTemp($cod_empresa,''),$sql);				
				
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
				
					<?php $abaFormalizacao = 1088; include "abasFormalizacaoEmp.php"; ?>
					
					<div class="push30"></div> 			
					
						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
																
						<fieldset>
							<legend>Dados Gerais</legend> 
						
								<div class="row">
						
									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Código</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_TPMODAL" id="COD_TPMODAL" value="">
										</div>
										<div class="help-block with-errors"></div>
									</div>       
						
									<div class="col-md-6">
										<div class="form-group">
											<label for="inputName" class="control-label required">Descrição</label>
											<input type="text" class="form-control input-sm" name="DES_TPMODAL" id="DES_TPMODAL" value="" required>
										</div>
										<div class="help-block with-errors"></div>
									</div>   

									<div class="col-md-4">
										<div class="form-group">
											<label for="inputName" class="control-label required">Abreviação</label>
											<input type="text" class="form-control input-sm" name="DES_ABREVIA" id="DES_ABREVIA" value="" required>
										</div>
										<div class="help-block with-errors"></div>
									</div> 									
							
								</div>
								
								<div class="push10"></div>
								
						</fieldset>
						
																
						<div class="push10"></div>
						<hr>	
						<div class="form-group text-right col-lg-12">
							
							  <button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>
							  <button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
							  <button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Alterar</button>
							  <!-- <button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fa fa-times" aria-hidden="true"></i>&nbsp; Excluir</button> -->
							
						</div>
						
						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">		
						
						<div class="push5"></div> 
						
						</form>
						
						<div class="push50"></div>
						
						<div class="col-lg-12">

							<div class="no-more-tables">
						
								<form name="formLista">
								
								<table class="table table-bordered table-striped table-hover tablesorter buscavel">
								  <thead>
									<tr>
									  <th width="40"></th>
									  <th>Código</th>
									  <th>Descrição</th>
									  <th>Abreviação</th>
									</tr>
								  </thead>
								<tbody>
								
								<?php 
									$sql = "SELECT  TIPOMODALIDADE.COD_TPMODAL,
													TIPOMODALIDADE.DES_TPMODAL,
													TIPOMODALIDADE.DES_ABREVIA 
										FROM TIPOMODALIDADE order by COD_TPMODAL";
									
									$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
									
									$count=0;
									while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery))
									  {														  
										$count++;	
										echo"
											<tr>
											  <td><input type='radio' name='radio1' onclick='retornaForm(".$count.")'></th>
											  <td>".$qrBuscaModulos['COD_TPMODAL']."</td>
											  <td>".$qrBuscaModulos['DES_TPMODAL']."</td>
											  <td>".$qrBuscaModulos['DES_ABREVIA']."</td>
											</tr>
											
											<input type='hidden' id='ret_COD_TPMODAL_".$count."' value='".$qrBuscaModulos['COD_TPMODAL']."'>
											<input type='hidden' id='ret_DES_TPMODAL_".$count."' value='".$qrBuscaModulos['DES_TPMODAL']."'>
											<input type='hidden' id='ret_DES_ABREVIA_".$count."' value='".$qrBuscaModulos['DES_ABREVIA']."'>
											"; 
										  }											
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
		
		function retornaForm(index){
			$("#formulario #COD_TPMODAL").val($("#ret_COD_TPMODAL_"+index).val());
			$("#formulario #DES_TPMODAL").val($("#ret_DES_TPMODAL_"+index).val());
			$("#formulario #DES_ABREVIA").val($("#ret_DES_ABREVIA_"+index).val()).trigger("chosen:updated");
			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');			
		}
		
	</script>	