<?php
	
	//echo fnDebug('true');
 
    $hashLocal = mt_rand();	
	$cod_tpmodal = 0;
	
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
			
			$cod_objeto = fnLimpaCampoZero($_REQUEST['COD_OBJETO']);
			$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
			$cod_conveni = fnLimpaCampo($_REQUEST['COD_CONVENI']);
			$cod_licitac = fnLimpaCampo($_REQUEST['COD_LICITAC']);
			$nom_objeto = fnLimpaCampo($_REQUEST['NOM_OBJETO']);
			$des_objeto = fnLimpaCampo($_REQUEST['DES_OBJETO']);
			$num_contador = fnLimpaCampo($_REQUEST['NUM_CONTADOR']);

			//fnEscreve($cod_licitac);
			
			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
			
			$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
                      
			if ($opcao != ''){							
				
				//mensagem de retorno
				switch ($opcao)
				{
					case 'CAD':

						$sql = "INSERT INTO LICITACAO_OBJETO(
											COD_EMPRESA,
											COD_CONVENI,
											COD_LICITAC,
											NOM_OBJETO,
											DES_OBJETO,
											COD_USUCADA
											) VALUES(
											$cod_empresa,
											$cod_conveni,
											$cod_licitac,
											'$nom_objeto',
											'$des_objeto',
											$cod_usucada
											)";
					
						//fnEscreve($sql);
		                mysqli_query(connTemp($cod_empresa,''),$sql);

						$sqlCod = "SELECT MAX(COD_OBJETO) COD_OBJETO FROM LICITACAO_OBJETO WHERE COD_EMPRESA = $cod_empresa AND COD_CONVENI = $cod_conveni";
						$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sqlCod);
						$qrCod = mysqli_fetch_assoc($arrayQuery);
						$cod_objeto = $qrCod[COD_OBJETO];

						$sqlArquivos = "SELECT 1 FROM ANEXO_CONVENIO WHERE COD_EMPRESA = $cod_empresa AND COD_PROVISORIO = $num_contador";
						$arrayCont = mysqli_query(connTemp($cod_empresa,''),$sqlArquivos);

						if(mysqli_num_rows($arrayCont) > 0){
							$sqlUpd = "UPDATE ANEXO_CONVENIO SET COD_OBJETO = $cod_objeto, LOG_STATUS = 'S' WHERE COD_EMPRESA = $cod_empresa AND COD_PROVISORIO = $num_contador";
							mysqli_query(connTemp($cod_empresa,''),$sqlUpd);
						}

						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";	
						break;
					case 'ALT':

						$sql = "UPDATE LICITACAO_OBJETO SET
											COD_LICITAC=$cod_licitac,
											NOM_OBJETO='$nom_objeto',
											DES_OBJETO='$des_objeto',
											COD_ALTERAC=$cod_usucada
								WHERE COD_OBJETO = $cod_objeto";
					
						//fnEscreve($sql);
		                mysqli_query(connTemp($cod_empresa,''),$sql);

		                $sqlUpd = "UPDATE ANEXO_CONVENIO SET LOG_STATUS = 'S' WHERE COD_EMPRESA = $cod_empresa AND COD_OBJETO = $cod_objeto AND LOG_STATUS = 'N'";
						mysqli_query(connTemp($cod_empresa,''),$sqlUpd);

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
			$sql = "SELECT NOM_CONVENI FROM CONVENIO WHERE COD_CONVENI = ".$cod_conveni;	
			
			//fnEscreve($sql);
			$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
			$qrBuscaTemplate = mysqli_fetch_assoc($arrayQuery);
				
			if (isset($qrBuscaTemplate)){
				$nom_conveni = $qrBuscaTemplate['NOM_CONVENI'];
			
			}			
			
		}
	}	
	
	//busca dados do usuário
	$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
	$sql = "SELECT NOM_USUARIO FROM USUARIOS WHERE COD_USUARIO = ".$cod_usucada;	
			
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
	$qrBuscaUsuario = mysqli_fetch_assoc($arrayQuery);
		
	if (isset($qrBuscaUsuario)){
		$nom_usuario = $qrBuscaUsuario['NOM_USUARIO'];
	}

	//fnEscreve(fnDecode($_GET['idC']));

	if (is_numeric(fnLimpacampo(fnDecode($_GET['idC'])))){
		$cod_conveni = fnDecode($_GET['idC']);
	}
	      
	//fnMostraForm();
	//fnEscreve($cod_checkli);

	$tp_cont = 'Anexo do Objeto da Licitação';
	$tp_anexo = 'COD_OBJETO';
	$cod_tpanexo = 'COD_OBJETO';
	$cod_busca = $cod_objeto;
	
	$sqlUpdtCont = "DELETE FROM ANEXO_CONVENIO WHERE COD_EMPRESA = $cod_empresa AND COD_OBJETO != 0 AND LOG_STATUS = 'N'";
	mysqli_query(connTemp($cod_empresa,''),$sqlUpdtCont);
	
	$sqlUpdtCont = "UPDATE CONTADOR SET NUM_CONTADOR = (NUM_CONTADOR+1) WHERE DES_CONTADOR = '$tp_cont'";
	mysqli_query(connTemp($cod_empresa,''),$sqlUpdtCont);

	$sqlCont = "SELECT NUM_CONTADOR FROM CONTADOR WHERE DES_CONTADOR = '$tp_cont'";
	$qrCont = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sqlCont));
	$num_contador = $qrCont['NUM_CONTADOR'];

?>
	
<?php if ($popUp != "true"){  ?>							
	<div class="push30"></div> 
	<?php } ?>
	
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
						<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
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
					
					<?php 
					//menu superior - licitação
					$abaProposta = 1364;										
					include "abasLicitacao.php";													
					?>					
					
					<div class="push30"></div> 
					
					
						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
																
						<fieldset>
							<legend>Dados Gerais</legend>

							<div class="row">
						
								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Código</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_OBJETO" id="COD_OBJETO" value="">
									</div>
									<div class="help-block with-errors"></div>
								</div>
					
								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label required">Convênio</label>
										<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_CONVENI" id="NOM_CONVENI" value="<?php echo $nom_conveni ?>" required>
									</div>														
								</div> 

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Licitação</label>
											<select data-placeholder="Selecione uma empresa" name="COD_LICITAC" id="COD_LICITAC" class="chosen-select-deselect requiredChk" style="width:100%;" required>
												<option value=""></option>
												   <?php 
													$sql = "SELECT COD_LICITAC, NOM_LICITAC FROM LICITACAO WHERE COD_EMPRESA = $cod_empresa and COD_CONVENI = $cod_conveni ";

													
													$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
												
													while ($qrLista = mysqli_fetch_assoc($arrayQuery))
													 {														
														?>
															  <option value='<?=$qrLista['COD_LICITAC']?>'><?=$qrLista['NOM_LICITAC']?></option>
														<?php 
													}											
												?>
											</select>
										<div class="help-block with-errors"></div>
									</div>
								</div>	

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Título do Bloco</label>
										<input type="text" class="form-control input-sm" name="NOM_OBJETO" id="NOM_OBJETO" value="<?php echo $nom_licitac?>" required> 
									</div>
									<div class="help-block with-errors"></div>
								</div>

							</div>

							<div class="row">		
								
								<div class="col-md-12">
									<div class="form-group">
										<label for="inputName" class="control-label">Descrição do Bloco</label>
										<textarea type="text" class="form-control input-sm" rows="3" name="DES_OBJETO" id="DES_OBJETO" value="" maxlength="250"></textarea>
									</div>
									<div class="help-block with-errors"></div>
								</div>  
								
							</div>

							<div class="push10"></div>

							<?php include "uploadConvenio.php"; ?>
							
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
						
						<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">
						<input type="hidden" name="COD_CONVENI" id="COD_CONVENI" value="<?php echo $cod_conveni; ?>">
						<input type="hidden" name="COD_OBJETOANEXO" id="COD_OBJETOANEXO" value="">
						<input type="hidden" name="NUM_CONTADOR" id="NUM_CONTADOR" value="<?php echo $num_contador; ?>" />
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
									  <th width="40"></th>
									  <th>Código</th>
									  <th>Bloco</th>
									  <th>Licitação</th>
									  <th>Descrição</th>
									</tr>
								  </thead>
								<tbody>
								
								<?php 
									$sql = "SELECT LCO.*, LCT.NOM_LICITAC FROM LICITACAO_OBJETO LCO
									LEFT JOIN LICITACAO LCT ON LCT.COD_LICITAC = LCO.COD_LICITAC
									WHERE LCO.COD_EMPRESA = $cod_empresa AND LCO.COD_CONVENI = $cod_conveni";
											
									//fnEscreve($sql);
									$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error()); 
									
									$count=0;
									while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery))
									  {														  
										$count++;	
										echo"
											<tr>
											  <td><input type='radio' name='radio1' onclick='retornaForm(".$count.")'></th>
											  <td>".$qrBuscaModulos['COD_OBJETO']."</td>											
											  <td>".$qrBuscaModulos['NOM_OBJETO']."</td>
											  <td>".$qrBuscaModulos['NOM_LICITAC']."</td>
											  <td>".$qrBuscaModulos['DES_OBJETO']."</td>
											</tr>
											
											<input type='hidden' id='ret_COD_OBJETO_".$count."' value='".$qrBuscaModulos['COD_OBJETO']."'>
											<input type='hidden' id='ret_NOM_OBJETO_".$count."' value='".$qrBuscaModulos['NOM_OBJETO']."'>
											<input type='hidden' id='ret_COD_LICITAC_".$count."' value='".$qrBuscaModulos['COD_LICITAC']."'>
											<input type='hidden' id='ret_DES_OBJETO_".$count."' value='".$qrBuscaModulos['DES_OBJETO']."'>
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

	<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
	<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />
	
	<script type="text/javascript">

		$(document).ready(function(){

			$('.upload').prop('disabled',true);
			
			$('.datePicker').datetimepicker({
				 format: 'DD/MM/YYYY',
				 maxDate : 'now',
			}).on('changeDate', function(e){
				$(this).datetimepicker('hide');
			});

		});
	
		function retornaForm(index){
			$("#formulario #COD_OBJETO").val($("#ret_COD_OBJETO_"+index).val());
			$("#formulario #COD_OBJETOANEXO").val($("#ret_COD_OBJETO_"+index).val());
			$("#formulario #COD_LICITAC").val($("#ret_COD_LICITAC_"+index).val()).trigger("chosen:updated");
			$("#formulario #NOM_OBJETO").val($("#ret_NOM_OBJETO_"+index).val());
			$("#formulario #DES_OBJETO").val($("#ret_DES_OBJETO_"+index).val());
			$('.upload').prop('disabled',false).removeAttr('disabled');

			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');	

			refreshUpload();		
		}
		
	</script>

	<?php include 'jsUploadConvenio.php'; ?>
