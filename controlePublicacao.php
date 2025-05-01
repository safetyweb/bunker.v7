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
			
			$cod_conta = fnLimpaCampoZero($_REQUEST['COD_PUBLICA']);
			$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
			$cod_conveni = fnLimpaCampoZero($_REQUEST['COD_CONVENI']);
			$cod_upload = fnLimpaCampoZero($_REQUEST['COD_UPLOAD']);
			$dat_adjudica = fnLimpaCampo($_REQUEST['DAT_ADJUDICA']);
			$dat_homologa = fnLimpaCampo($_REQUEST['DAT_HOMOLOGA']);
			$num_contador = fnLimpaCampo($_REQUEST['NUM_CONTADOR']);
			
			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
			
			$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
                      
			if ($opcao != ''){							
				
				//mensagem de retorno
				switch ($opcao)
				{
					case 'CAD':

						$sql = "INSERT INTO PUBLICACAO(
										COD_EMPRESA,
										COD_CONVENI,
										DAT_ADJUDICA,
										DAT_HOMOLOGA,
										COD_USUCADA
										) VALUES(
										$cod_empresa,
										$cod_conveni,
										'".fnDatasql($dat_adjudica)."',
										'".fnDatasql($dat_homologa)."',
										$cod_usucada
										)";
							//fnEscreve($sql);
						
	                	mysqli_query(connTemp($cod_empresa,''),$sql);

	                	$sqlCod = "SELECT MAX(COD_PUBLICA) COD_PUBLICA FROM PUBLICACAO WHERE COD_EMPRESA = $cod_empresa AND COD_CONVENI = $cod_conveni";
						$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sqlCod);
						$qrCod = mysqli_fetch_assoc($arrayQuery);
						$cod_publica = $qrCod[COD_PUBLICA];

						$sqlArquivos = "SELECT 1 FROM ANEXO_CONVENIO WHERE COD_EMPRESA = $cod_empresa AND COD_PROVISORIO = $num_contador";
						$arrayCont = mysqli_query(connTemp($cod_empresa,''),$sqlArquivos);

						if(mysqli_num_rows($arrayCont) > 0){
							$sqlUpd = "UPDATE ANEXO_CONVENIO SET COD_PUBLICA = $cod_publica, LOG_STATUS = 'S' WHERE COD_EMPRESA = $cod_empresa AND COD_PROVISORIO = $num_contador";
							mysqli_query(connTemp($cod_empresa,''),$sqlUpd);
						}

						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";	
					break;
					case 'ALT':

						$sql = "UPDATE PUBLICACAO SET
										COD_EMPRESA=$cod_empresa,
										COD_CONVENI=$cod_conveni,
										DAT_ADJUDICA='".fnDatasql($dat_adjudica)."',
										DAT_HOMOLOGA='".fnDatasql($dat_homologa)."',
										COD_ALTERAC=$cod_usucada,
										DAT_ALTERAC=NOW()
										WHERE COD_PUBLICA = $cod_publica";
							//fnEscreve($sql);
						
	               		mysqli_query(connTemp($cod_empresa,''),$sql);

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
		}
	}
	      
	//fnMostraForm();
	//fnEscreve($cod_empresa);

	$tp_cont = 'Anexo da Publicação';
	$tp_anexo = 'COD_PUBLICA';
	$cod_tpanexo = 'COD_PUBLICA';
	$cod_busca = $cod_publica;
	
	$sqlUpdtCont = "DELETE FROM ANEXO_CONVENIO WHERE COD_EMPRESA = $cod_empresa AND COD_PUBLICA != 0 AND LOG_STATUS = 'N'";
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
									<i class="fal fa-terminal"></i>
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
					//menu superior - empresas
					$abaProposta = 1362;										
					include "abasPropostas.php";								
					
					?>
					
					<div class="push30"></div>		
					
					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
																
						<fieldset>
							<legend>Dados Gerais</legend> 
						
								<div class="row">
						
									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Código</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_PUBLICA" id="COD_PUBLICA" value="">
										</div>
										<div class="help-block with-errors"></div>
									</div>       
						
									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Empresa</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa ?>" required>
											<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">
										</div>														
									</div> 
									
									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Licitação</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa ?>" required>
											<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">
										</div>														
									</div>
									
								</div>
								
								<div class="row">
								
									<div class="col-md-4">
										<div class="form-group">
											<label for="inputName" class="control-label required">Data Ajudicação</label>
											<div class="input-group date datePicker" id="DAT_INI_GRP">
												<input type='text' class="form-control input-sm data" name="DAT_ADJUDICA" id="DAT_ADJUDICA" value="<?=$dat_adjudica?>"required/>
												<span class="input-group-addon">
													<span class="glyphicon glyphicon-calendar"></span>
												</span>
											</div>
											<div class="help-block with-errors"></div>
										</div>
									</div>


									 <div class="col-md-4">
										<div class="form-group">
											<label for="inputName" class="control-label required">Data Homologação</label>
											<div class="input-group date datePicker" id="DAT_FIM_GRP">
												<input type='text' class="form-control input-sm data" name="DAT_HOMOLOGA" id="DAT_HOMOLOGA" value="<?=$dat_homologa?>" required/>
												<span class="input-group-addon">
													<span class="glyphicon glyphicon-calendar"></span>
												</span>
											</div>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<!-- <div class="col-md-4">
										<div class="form-group">
											<label for="inputName" class="control-label">Anexo</label>
											<div class="input-group">
												<span class="input-group-btn">
													<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary upload" idinput="DES_IMAGEM" extensao="img"><i class="fa fa-cloud-upload" aria-hidden="true"></i></a>
												</span>
												<input type="text" name="DES_IMAGEM" id="DES_IMAGEM" class="form-control input-sm" style="border-radius: 0 3px 3px  0;" value="">
											</div>																
											<span class="help-block">Upload</span>															
										</div>
									</div> -->							
							
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
						
						<input type="hidden" name="COD_CONVENI" id="COD_CONVENI" value="<?=$cod_conveni?>">
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
									  <th>Data Adjudicação</th>
									  <th>Data Homologação</th>
									</tr>
								  </thead>
								<tbody>
								
								<?php 
									$sql = "SELECT * FROM PUBLICACAO WHERE COD_EMPRESA = $cod_empresa AND COD_CONVENI = $cod_conveni";
											
									//fnEscreve($sql);
									$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
									
									$count=0;
									while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery))
									  {
																						  
										$count++;	
										echo"
											<tr>
											  <td><input type='radio' name='radio1' onclick='retornaForm(".$count.")'></th>
											  <td>".$qrBuscaModulos['COD_PUBLICA']."</td>
											  <td>".fnDataShort($qrBuscaModulos['DAT_ADJUDICA'])."</td>
											  <td>".fnDataShort($qrBuscaModulos['DAT_HOMOLOGA'])."</td>
											</tr>
											
											<input type='hidden' id='ret_COD_PUBLICA_".$count."' value='".$qrBuscaModulos['COD_PUBLICA']."'>
											<input type='hidden' id='ret_DAT_ADJUDICA_".$count."' value='".fnDataShort($qrBuscaModulos['DAT_ADJUDICA'])."'>
											<input type='hidden' id='ret_DAT_HOMOLOGA_".$count."' value='".fnDataShort($qrBuscaModulos['DAT_HOMOLOGA'])."'>
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

		// $(document).keyup(function(e) {
		//      if (e.key === "Escape") { // escape key maps to keycode `27`
		//         $('#popModal').modal('hide');
		//     }
		// });

		$(document).ready(function(){

			$('.upload').prop('disabled',true);
			
			$('.datePicker').datetimepicker({
			format: 'DD/MM/YYYY',
			maxDate : new Date(2999,25,12)
		}).on('changeDate', function(e){
		       $(this).datetimepicker('hide');
		});

        $("#DAT_INI_GRP").data("DateTimePicker").defaultDate(false);
		$('#DAT_FIM_GRP').data("DateTimePicker").defaultDate(false);

		$("#DAT_FIM_GRP").on("dp.error", function (e) {
               $('#DAT_FIM_GRP').data("DateTimePicker").date(null);
        });

        $("#DAT_INI_GRP").on("dp.change", function (e) {
               $('#DAT_FIM_GRP').data("DateTimePicker").minDate(e.date).date(null);
               
        });
        $("#DAT_FIM_GRP").on("dp.change", function (e) {
        });
				
			//chosen obrigatório
			$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
			$('#formulario').validator();

		});
		
		function retornaForm(index){
			$("#formulario #COD_PUBLICA").val($("#ret_COD_PUBLICA_"+index).val());
			$("#formulario #COD_OBJETOANEXO").val($("#ret_COD_PUBLICA_"+index).val());
			$("#formulario #DAT_ADJUDICA").val($("#ret_DAT_ADJUDICA_"+index).val());
			$("#formulario #DAT_HOMOLOGA").val($("#ret_DAT_HOMOLOGA_"+index).val());
			$('.upload').prop('disabled',false).removeAttr('disabled');

			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');	

			refreshUpload();		
		}

	</script>

	<?php include 'jsUploadConvenio.php'; ?>