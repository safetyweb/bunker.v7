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
			
			$cod_tpcontrat = fnLimpaCampoZero($_REQUEST['COD_TPCONTRAT']);
			$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
			$des_tpcontrat = fnLimpaCampo($_REQUEST['DES_TPCONTRAT']);
			$abv_tpcontrat = fnLimpaCampo($_REQUEST['ABV_TPCONTRAT']);
			
			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
			
			$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
                      
			if ($opcao != ''){							
				
				//mensagem de retorno
				switch ($opcao)
				{
					case 'CAD':

						$sql = "INSERT INTO TIPO_CONTRATO (
						COD_EMPRESA,
						DES_TPCONTRAT,
						ABV_TPCONTRAT
						) VALUES(
						$cod_empresa,
						'$des_tpcontrat',
						'$abv_tpcontrat'
						)";
							
		                mysqli_query(connTemp($cod_empresa,''),$sql);
						
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
	//fnEscreve($cod_empresa);

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
				
					<?php $abaFormalizacao = 1351; include "abasFormalizacaoEmp.php"; ?>
					
					<div class="push30"></div> 			
					
						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
																
						<fieldset>
							<legend>Dados Gerais</legend> 
						
								<div class="row">
						
									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Código</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_TPCONTRAT" id="COD_TPCONTRAT" value="">
										</div>
										<div class="help-block with-errors"></div>
									</div>       
						
									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Empresa</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa ?>">
											<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">
										</div>														
									</div>	      
						
									<div class="col-md-5">
										<div class="form-group">
											<label for="inputName" class="control-label">Descrição</label>
											<input type="text" class="form-control input-sm" name="DES_TPCONTRAT" id="DES_TPCONTRAT" value="" maxlength="50">
										</div>
										<div class="help-block with-errors"></div>
									</div>       
						
									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Abreviação</label>
											<input type="text" class="form-control input-sm" name="ABV_TPCONTRAT" id="ABV_TPCONTRAT" value="" maxlength="20">
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
								
								<table class="table table-bordered table-striped table-hover">
								  <thead>
									<tr>
									  <th class="bg-primary" width="40"></th>
									  <th class="bg-primary">Código</th>
									  <th class="bg-primary">Descrição</th>
									  <th class="bg-primary">Abreviação</th>
									</tr>
								  </thead>
								<tbody>
								
								<?php 
									$sql = "SELECT * FROM TIPO_CONTRATO WHERE COD_EMPRESA = $cod_empresa";
											
									$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
									
									$count=0;
									while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery))
									  {														  
										$count++;	
										echo"
											<tr>
											  <td><input type='radio' name='radio1' onclick='retornaForm(".$count.")'></th>
											  <td>".$qrBuscaModulos['COD_TPCONTRAT']."</td>
											  <td>".$qrBuscaModulos['DES_TPCONTRAT']."</td>
											  <td>".$qrBuscaModulos['ABV_TPCONTRAT']."</td>
											</tr>
											
											<input type='hidden' id='ret_COD_CHECKLI_".$count."' value='".$qrBuscaModulos['COD_TPCONTRAT']."'>
											<input type='hidden' id='ret_DES_DESCRIC_".$count."' value='".$qrBuscaModulos['DES_TPCONTRAT']."'>
											<input type='hidden' id='ret_DES_ABREVIA_".$count."' value='".$qrBuscaModulos['ABV_TPCONTRAT']."'>
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
			$("#formulario #COD_TPCONTRAT").val($("#ret_COD_TPCONTRAT_"+index).val());
			$("#formulario #DES_TPCONTRAT").val($("#ret_DES_TPCONTRAT_"+index).val());
			$("#formulario #ABV_TPCONTRAT").val($("#ret_ABV_TPCONTRAT_"+index).val());
			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');			
		}
		
	</script>	