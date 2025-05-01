<?php
	
	// echo fnDebug('true');
 
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
			
			$cod_documen = fnLimpaCampoZero($_REQUEST['COD_DOCUMEN']);
			$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
			// if (empty($_REQUEST['LOG_ATIVO'])) {$log_ativo = 'N';}else{$log_ativo = $_REQUEST['LOG_ATIVO'];}

			$nom_documen = fnLimpaCampo($_REQUEST['NOM_DOCUMEN']);
			$cod_tipodoc = fnLimpaCampo($_REQUEST['COD_TIPODOC']);
			$nom_tipodoc = fnLimpaCampo($_REQUEST['NOM_TIPODOC']);
			$abv_documento = fnLimpaCampo($_REQUEST['DES_ABREVIA']);
			$des_descricao = fnLimpaCampo($_REQUEST['DES_DESCRICAO']);
			// $des_msgerro = $_REQUEST['DES_MSGERRO'];
			
			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
			
			$cod_usucada = $_SESSION["SYS_COD_USUARIO"];


		if ($opcao != ''){

			switch ($opcao) {
				case 'CAD':
						
					$sqlGrava = "INSERT INTO DOCUMENTOS(
												COD_EMPRESA,
												NOM_DOCUMEN,
												COD_TIPODOC,
												DES_ABREVIA,
												DES_DESCRICAO,
												COD_CADASTR
											) VALUES (
											    '$cod_empresa',
											    '$nom_documen',
											    '$cod_tipodoc',
											    '$abv_documento',
											    '$des_descricao',
											    '$cod_usucada'
											    )";

					mysqli_query(connTemp($cod_empresa, ''), $sqlGrava);
					$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					// fnTesteSql(connTemp($cod_empresa, ''), $sqlGrava);
					// fnEscreve($sqlGrava);

				break;
				case 'ALT':
					
					$sqlEdita = "UPDATE DOCUMENTOS SET
										NOM_DOCUMEN = '$nom_documen',
										DES_ABREVIA = '$abv_documento',
										DES_DESCRICAO = '$des_descricao',
										COD_TIPODOC = '$cod_tipodoc',
										COD_ALTERAC = '$cod_usucada',
										DAT_ALTERAC = NOW()
									WHERE COD_DOCUMEN = '$cod_documen'
									AND COD_EMPRESA = '$cod_empresa'
										";

					mysqli_query(connTemp($cod_empresa, ''), $sqlEdita);
					$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";
					// fnTesteSql(connTemp($cod_empresa, ''), $sqlEdita);

				break;
				case 'EXC':
					
					$sqlApaga = "UPDATE DOCUMENTOS SET
										COD_EXCLUSA = '$cod_usucada',
										DAT_EXCLUSA = NOW()
									WHERE COD_DOCUMEN = '$cod_documen'
									AND COD_EMPRESA = '$cod_empresa'
										";

					mysqli_query(connTemp($cod_empresa, ''), $sqlApaga);
					$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";
					// fnTesteSql(connTemp($cod_empresa, ''), $sqlApaga);

				break;
			}
				$msgTipo = 'alert-success';
				//atualiza lista iframe								
			
			}                
		}
	}

	// fnEscreve($cod_tipodoc);
	
	//busca dados da url	
	if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))){
            
		//busca dados da empresa
		$cod_empresa = fnDecode($_GET['id']);	
		$cod_tipo = fnDecode($_GET['tipo']);

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
	
	if (is_numeric(fnLimpacampo(fnDecode($_GET['idD'])))){
		
		//busca dados
		$cod_documen = fnDecode($_GET['idD']);	
		$sql = "SELECT * FROM DOCUMENTOS WHERE COD_DOCUMEN = ".$cod_documen;	
		
		// fnEscreve($sql);
		$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
		$qrBuscaDocumento = mysqli_fetch_assoc($arrayQuery);
			
		if (isset($qrBuscaDocumento)){
			$cod_documen = $qrBuscaDocumento['COD_DOCUMEN'];
			$nom_documen = $qrBuscaDocumento['NOM_DOCUMEN'];
			$abv_documento = $qrBuscaDocumento['DES_ABREVIA'];
			$des_descricao = $qrBuscaDocumento['DES_DESCRICAO'];
			$cod_tipodoc = $qrBuscaDocumento['COD_TIPODOC'];
			// $des_msgerro = $qrBuscaDocumento['DES_MSGERRO'];
		}
		
	}else{
		$cod_documen = "";
		$nom_documen = "";
		$abv_documento = "";
		$des_descricao = "";
		$cod_tipodoc = "";
		// $des_msgerro = "";
	}

	// fnEscreve($cod_tipo);
	// fnEscreve($cod_tipodoc);


?>
<style>
.jqte {
    border: #dce4ec 2px solid!important;
    border-radius: 3px!important;
    -webkit-border-radius: 3px!important;    
    box-shadow: 0 0 2px #dce4ec!important;
    -webkit-box-shadow: 0 0 0px #dce4ec!important;
    -moz-box-shadow: 0 0 3px #dce4ec!important;    
    transition: box-shadow 0.4s, border 0.4s;
    margin-top: 0px!important;
    margin-bottom: 0px!important;
}

.jqte_toolbar {   
    background: #fff!important;
    border-bottom: none!important;
}

.jqte_focused {
	/*border: none!important;*/
	box-shadow:0 0 3px #00BDFF; -webkit-box-shadow:0 0 3px #00BDFF; -moz-box-shadow:0 0 3px #00BDFF;
}

.jqte_titleText {
	border: none!important;
	border-radius:3px; -webkit-border-radius:3px; -moz-border-radius:3px;
	word-wrap:break-word; -ms-word-wrap:break-word
}

.jqte_tool, .jqte_tool_icon, .jqte_tool_label{
	border: none!important;
}

.jqte_tool_icon:hover{
	border: none!important;
	box-shadow: 1px 5px #EEE;
}
</style>
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

									<style type="text/css">
										#COD_TIPODOC<?=$qrListaTipoDoc["COD_TIPODOC"]?>_chosen .chosen-drop .chosen-results li:last-child{
											font-weight: bolder;
											font-size: 11px;
											color: #000;
										}

										#COD_TIPODOC<?=$qrListaTipoDoc["COD_TIPODOC"]?>_chosen .chosen-drop .chosen-results li:last-child:before{
											content: '\002795';
											font-weight: bolder;
											font-size: 9px;
										}
									</style>
						
									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Código</label>
											<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_DOCUMEN" id="COD_DOCUMEN" value="<?php echo $cod_documen ?>">
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
						
									<div class="col-md-4">
										<div class="form-group">
											<label for="inputName" class="control-label required">Nome do Documento</label>
											<input type="text" class="form-control input-sm" name="NOM_DOCUMEN" id="NOM_DOCUMEN" value="<?php echo $nom_documen ?>" maxlength="50">
										</div>
										<div class="help-block with-errors"></div>
									</div>       
						
									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Abreviação Documento</label>
											<input type="text" class="form-control input-sm" name="DES_ABREVIA" id="DES_ABREVIA" value="<?php echo $abv_documento ?>" maxlength="20">
										</div>
										<div class="help-block with-errors"></div>
									</div>

									<div class="col-md-2">
											<div class="form-group">
												<label for="inputName" class="control-label required">Tipo de Documento</label>
												<div id="relatorioTipoDoc">
													<select data-placeholder="Selecione o tipo de documento" name="COD_TIPODOC" id="COD_TIPODOC" class="chosen-select-deselect requiredChk" required>
														<option value=""></option>
														<?php
														$sql = "select COD_TIPODOC, NOM_TIPODOC from tipo_documento WHERE cod_empresa IN ($cod_empresa) AND COD_EXCLUSA = 0 order by nom_tipodoc ";
														$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);

														while ($qrListaTipoDoc = mysqli_fetch_assoc($arrayQuery)) {
															echo "
																<option value='" . $qrListaTipoDoc['COD_TIPODOC'] . "'>" . $qrListaTipoDoc['NOM_TIPODOC'] . "</option> 
															";

														}
														?>
														<option class="fas fa-plus" value="add">&nbsp;ADICIONAR NOVO</option>
													</select>
													<script type="text/javascript">
														$("#COD_TIPODOC").val("<?=$cod_tipodoc?>").trigger("chosen:updated");
														$('#COD_TIPODOC').change(function(){
															valor = $(this).val();
															if(valor=="add"){
																$(this).val('').trigger("chosen:updated");
																$('#btnCad_COD_TIPODOC').click();
															}
														});
													</script>
												</div>
												<div class="help-block with-errors"></div>
												<a type="hidden" name="btnCad_COD_TIPODOC" id="btnCad_COD_TIPODOC" class="addBox" data-url="action.php?mod=<?php echo fnEncode(1893) ?>&id=<?php echo fnEncode($cod_empresa) ?>&pop=true" data-title="Cadastrar Tipo"></a>
											</div>
									</div>
										
											
									
									<!-- <div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Ativo</label>
											<div class="push5"></div>
											<label class="switch">
												<input type="checkbox" name="LOG_ATIVO" id="LOG_ATIVO" class="switch" value="<?php echo $log_ativo ?>" />
												<span></span>
											</label>
											<div class="help-block with-errors"></div>
										</div>				
									</div> -->	
									
									<div class="col-md-12">
										<div class="form-group">
											<label for="inputName" class="control-label">Descrição do Documento</label>
											<textarea type="text" class="form-control input-sm" rows="3" name="DES_DESCRICAO" id="DES_DESCRICAO" maxlength="200"><?php echo $des_descricao;?></textarea>
										</div>
										<div class="help-block with-errors"></div>
									</div>

									<!-- <div class="col-md-12">
										<div class="form-group">
											<label for="inputName" class="control-label">Mensagem de Erro</label>
											<textarea type="text" class="editor form-control input-sm" rows="6" name="DES_MSGERRO" id="DES_MSGERRO" maxlength="200"><?php echo $des_msgerro;?></textarea>
										</div>
										<div class="help-block with-errors"></div>
									</div> -->

								</div>
								
								<div class="push10"></div>
								
							</fieldset>
							
																	
							<div class="push10"></div>
							<hr>	
							<div class="form-group text-right col-lg-12">
								
								  <!--<button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>-->
								  <?php
									if($cod_tipo == 'CAD'){
										?>
										<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button> 
										<?php
									}else{
										?>
										<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Alterar</button>
										<button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fal fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>
										<?php
									}
								  ?>
								
							</div>
							
							<input type="hidden" name="opcao" id="opcao" value="">
							<input type="hidden" name="REFRESH_COMBO" id="REFRESH_COMBO" value="N">
							<!-- <input type="hidden" name="COD_TIPODOC" id="COD_TIPODOC" value=""> -->
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


	<link rel="stylesheet" type="text/css" href="js/plugins/jQuery-TE/jquery-te-1.4.0.css">
	<link rel="stylesheet" type="text/css" href="js/plugins/jQuery-TE/jquery-te.png">
	<script type="text/javascript" src="js/plugins/jQuery-TE/jquery-te-1.4.0.min.js"></script>

	<script type="text/javascript">
		$(function(){
			var totalChars = 1000;
			// TextArea
			$(".editor").jqte(
			{
				sup: false,
				sub: false,
				outdent: false,
				indent: false,
				left: true,
        		center: true,
        		color: false,
        		right: true,
        		strike: true,
        		source: false,
		        link:true,
		        unlink: false,		        
		        remove: false,
		    	rule: false,
		    	fsize: false,
		    	format: true,
		    });

		    $(document).on("keydown", ".jqte_editor", function (e) {
			    el = $(this);
			    if((el.text().length > totalChars-1) && (e.keyCode != 8)) {
			    	e.preventDefault();
			    }
			});			

		});


		//modal close
        $('.modal').on('hidden.bs.modal', function() {

            if ($('#REFRESH_COMBO').val() == "S") {
                refreshCombo("<?php echo fnEncode($cod_empresa); ?>");
                $('#REFRESH_COMBO').val("N");
                $(".chosen-select-deselect").chosen({
                    allow_single_deselect: true
                });
            }

        });


		if($( "#LOG_ATIVO" ).val() === 'S'){
			$( "#LOG_ATIVO" ).trigger( "click" );
		}
	
		$( "#LOG_ATIVO" ).change(function() {
			if($(this).val() === 'N'){
				$(this).val('S');
			}else{
				$(this).val('N');
			}
		});

		function refreshCombo(cod_empresa) {
	        $.ajax({
	            type: "POST",
	            url: "ajxAddDocumento.do?id=<?= fnEncode($cod_empresa) ?>",
	            beforeSend: function() {
	                $('#relatorioTipoDoc').html('<div class="loading" style="width: 100%;"></div>');
	            },
	            success: function(data) {
	                $('#relatorioTipoDoc').html(data);

	            },
	            error: function() {
	                // $('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
	            }
	        });
	    }

	
		function retornaForm(index){
			
			// $("#formulario #COD_TEMPLATE").val($("#ret_COD_TEMPLATE_"+index).val());
			// $("#formulario #COD_EMPRESA").val($("#ret_COD_EMPRESA_"+index).val());
			// $("#formulario #NOM_TEMPLATE").val($("#ret_NOM_TEMPLATE_"+index).val());
			// $("#formulario #ABV_TEMPLATE").val($("#ret_ABV_TEMPLATE_"+index).val());
			// $("#formulario #DES_TEMPLATE").val($("#ret_DES_TEMPLATE_"+index).val());
			// $("#formulario #DES_MSGERRO").val($("#ret_DES_MSGERRO_"+index).val());
			// if ($("#ret_LOG_ATIVO_"+index).val() == 'S'){$('#formulario #LOG_ATIVO').prop('checked', true);}else{$('#formulario #LOG_ATIVO').prop('checked', false);}
			// $('#formulario').validator('validate');			
			// $("#formulario #hHabilitado").val('S');			
			
		}
		
	</script>	