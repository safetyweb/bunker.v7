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
			
			$des_comentario = addslashes(htmlentities($_REQUEST['DES_COMENTARIO']));
			$cod_empresa = fnLimpacampoZero($_REQUEST['COD_EMPRESA']);
			$cod_registr = fnLimpacampoZero($_REQUEST['COD_REGISTR']);
			
			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
						
			if ($opcao != ''){

				$sql = "UPDATE MODELOTEMPLATETKT SET DES_TEXTO = '$des_comentario' 
						WHERE COD_EMPRESA = $cod_empresa 
						AND COD_REGISTR = $cod_registr";
    			fnTestesql(connTemp($cod_empresa,""),trim($sql));

    			// fnEscreve($sql);

    			?>
    			<script>parent.location.reload();</script>
    			<?php 

    			// fnEscreve($sql);

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
		$cod_registr = fnLimpaCampoZero(fnDecode($_GET['idr']));
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
		$nom_empresa = "";
		// fnEscreve('else');
	}

	// fnEscreve($cod_empresa);
	// fnEscreve($cod_registr);

	$sql = "SELECT DES_TEXTO FROM MODELOTEMPLATETKT 
			WHERE COD_EMPRESA = $cod_empresa 
			AND COD_REGISTR = $cod_registr";

	$qrTexto = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));


?>
					
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


<style>
body{
	width: 98%;
}
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

									<div class="login-form">
									
										<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

											<div class="row">

												<div class="col-lg-12">
													<div class="form-group">
														<label for="inputName" class="control-label required">Texto: </label>
														<textarea class="editor form-control input-sm" rows="6" name="DES_COMENTARIO" id="DES_COMENTARIO"><?=$qrTexto['DES_TEXTO']?></textarea>
														<div class="help-block with-errors"></div>
													</div>
												</div>

											</div>

											
																				
											<div class="push10"></div>
											<hr>	
											<div class="form-group text-right col-lg-12">
												
												<button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>
												<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-send" aria-hidden="true"></i>&nbsp; Enviar</button>		
											</div>
										
										<input type="hidden" name="opcao" id="opcao" value="">
										<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
										<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>" />
										<input type="hidden" name="COD_REGISTR" id="COD_REGISTR" value="<?php echo $cod_registr; ?>" />
										<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">		
										
										
										</form>
										

										</div>										
									
									<div class="push"></div>
									
								</div>								
								
							</div><!-- fim Portlet -->
						</div>
							
					</div>

				</div>

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

			$("#CAD").click(function(e){
				if($(".jqte_editor").text().trim() == ""){
					e.preventDefault();
					$.alert({
                        title: "Aviso",
                        content: "O texto não pode ser vazio.",
                        type: 'orange'
                    });
				}
			});

		});

	</script>	