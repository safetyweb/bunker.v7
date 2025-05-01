<?php
	
	//echo fnDebug('true');
 
    $hashLocal = mt_rand();	
    $mod = fnDecode($_GET['mod']);

    $tableAnexo = "ANEXO_CONVENIO";
    $cod_cliente = "";

    if($mod == 1851){
    	$tableAnexo = "ANEXO_DOC";
    	$cod_cliente = $_GET['idcli'];
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
			
			$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
			$cod_anexo = fnLimpaCampoZero($_REQUEST['COD_ANEXO']);
			$des_justifica = fnLimpaCampo($_REQUEST['DES_JUSTIFICA']);
			$des_observa = fnLimpaCampo($_REQUEST['DES_OBSERVA']);
			$chave_linha = fnLimpaCampo($_REQUEST['CHAVE_LINHA']);
			
			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
			
			$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
			
                      
			if ($opcao != ''){

				//mensagem de retorno
				$sql = "INSERT INTO HISTORICO_ANEXO(
										COD_EMPRESA,
										COD_ANEXO,
										COD_STATUS,
										DAT_STATUS,
										DES_JUSTIFICA,
										DES_OBSERVA,
										COD_USUCADA
									) VALUES(
										$cod_empresa,
										$cod_anexo,
										3,
										NOW(),
										'$des_justifica',
										'$des_observa',
										$cod_usucada
									  );

						UPDATE $tableAnexo
            			SET COD_STATUS = 3
            			WHERE COD_DOCUMENTO = $cod_anexo
            			AND COD_EMPRESA = $cod_empresa;";
					
				// fnEscreve($sql);
                mysqli_multi_query(connTemp($cod_empresa,''),$sql);

				if($cod_cliente != ""){

?>
					<script type="text/javascript">
						parent.reloadAnexo("<?=fnEncode($cod_anexo)?>","paginar","<?=$chave_linha?>","<?=$cod_cliente?>");
						parent.$('#popModal').modal('toggle');
					</script>
<?php 

				}else{

?>
					<script type="text/javascript">
						parent.reloadAnexo("<?=fnEncode($cod_anexo)?>","paginar","<?=$chave_linha?>");
						parent.$('#popModal').modal('toggle');
					</script>
<?php 

				}

				$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";			
				
				$msgTipo = 'alert-success';
			}                
		}
	}
	
	//busca dados da url	
	if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))){
            
		//busca dados da empresa
		$cod_empresa = fnDecode($_GET['id']);	
		$cod_anexo = fnDecode($_GET['ida']);
		$chave_linha = fnDecode($_GET['idc']);

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

	$sqlAnx = "SELECT COD_JUSTIFICA, DES_OBSERVA FROM HISTORICO_ANEXO WHERE COD_EMPRESA = $cod_empresa AND COD_ANEXO = $cod_anexo";
	$arrayAnx = mysqli_query(connTemp($cod_empresa,''),$sqlAnx);
	$qrAnx = mysqli_fetch_assoc($arrayAnx);

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
												
					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
															
					<fieldset>
					<legend>Dados Gerais</legend> 
				
						<div class="row">

							<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label">Justificativa</label>
										<select data-placeholder="Selecione a Justificativa" name="DES_JUSTIFICA" id="DES_JUSTIFICA"  class="chosen-select-deselect" style="width:100%;">																     
											<option value=""></option>
											<?php

											$sqlTipo = "SELECT * FROM justificativa WHERE COD_EMPRESA = $cod_empresa ORDER BY DES_JUSTIFICA ASC";
											$arrayTipo = mysqli_query(connTemp($cod_empresa, ''), $sqlTipo);

											while ($qrTipo = mysqli_fetch_assoc($arrayTipo)) {
												?>

												<option value="<?= $qrTipo[COD_JUSTIFICA] ?>"><?= $qrTipo[DES_JUSTIFICA] ?></option>

												<?php

											}

											?>
											<option class="fas fa-plus" value="add">&nbsp;Adicionar Novo</option>													
										</select>

										<script type="text/javascript">

										$('#DES_JUSTIFICA').change(function () {
												valor = $(this).val();
												if (valor == "add") {
													$(this).val('').trigger("chosen:updated");
													$('#btnCad_DES_JUSTIFICA').click();
												}
											});

										$('#DES_JUSTIFICA').val("<?=$qrAnx[COD_JUSTIFICA]?>").trigger('chosen:updated')
										</script>
										
										<div class="help-block with-errors"></div>
										<a type="hidden" name="btnCad_DES_JUSTIFICA" id="btnCad_DES_JUSTIFICA" class="addBox" data-url="action.php?mod=<?php echo fnEncode(1782) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idc=<?=fnEncode($chave_linha)?>&pop=true" data-title="Cadastro de Justificativa"></a>	
									</div>
							</div>

						</div>

						<div class="row">
							
							<div class="col-md-12">
								<div class="form-group">
									<label for="inputName" class="control-label">Observação</label>
									<textarea class="form-control input-sm" rows="3" name="DES_OBSERVA" id="DES_OBSERVA" maxlength="250"><?=$qrAnx[DES_OBSERVA]?></textarea>
								</div>
							</div>

						</div>

					</fieldset>
					
															
					<div class="push10"></div>
					<hr>	
					<div class="form-group text-right col-lg-12">
						
							<!--<button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>-->
							<?php
							if($cod_mensagem == 0){
								?>
								<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button> 
								<?php
							}else{
								?>
								<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Alterar</button>
								<?php
							}
							?>
							
							<!-- <button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fa fa-times" aria-hidden="true"></i>&nbsp; Excluir</button> -->
						
					</div>
					
					<input type="hidden" name="opcao" id="opcao" value="">
					<input type="hidden" name="CHAVE_LINHA" id="CHAVE_LINHA" value="<?=$chave_linha?>">
					<input type="hidden" name="COD_MENSAGEM" id="COD_MENSAGEM" value="<?=$cod_mensagem?>">
					<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?=$cod_empresa?>">
					<input type="hidden" name="COD_ANEXO" id="COD_ANEXO" value="<?=$cod_anexo?>">
					<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
					<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">		
					
					<div class="push5"></div> 
					
					</form>
					
					<div class="push50"></div>									
				
				<div class="push"></div>
				
				</div>								
			
			</div>
		</div>
		<!-- fim Portlet -->
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
	
		// if($( "#LOG_ATIVO" ).val() === 'S'){
		// 	$( "#LOG_ATIVO" ).trigger( "click" );
		// }
	
		// $( "#LOG_ATIVO" ).change(function() {
		// 	if($(this).val() === 'N'){
		// 		$(this).val('S');
		// 	}else{
		// 		$(this).val('N');
		// 	}
		// });
	
		function retornaForm(index){
			/*
			$("#formulario #COD_TEMPLATE").val($("#ret_COD_TEMPLATE_"+index).val());
			$("#formulario #COD_EMPRESA").val($("#ret_COD_EMPRESA_"+index).val());
			$("#formulario #NOM_TEMPLATE").val($("#ret_NOM_TEMPLATE_"+index).val());
			$("#formulario #ABV_TEMPLATE").val($("#ret_ABV_TEMPLATE_"+index).val());
			$("#formulario #DES_TEMPLATE").val($("#ret_DES_TEMPLATE_"+index).val());
			if ($("#ret_LOG_ATIVO_"+index).val() == 'S'){$('#formulario #LOG_ATIVO').prop('checked', true);}else{$('#formulario #LOG_ATIVO').prop('checked', false);}
			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');			
			*/
		}
		
	</script>	