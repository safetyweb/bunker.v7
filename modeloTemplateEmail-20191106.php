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

			// $cod_grupotr = fnLimpaCampoZero($_REQUEST['COD_GRUPOTR']);
			$cod_modelo = fnLimpaCampoZero($_REQUEST['COD_MODELO']);
			$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
			$cod_template = fnLimpaCampoZero($_REQUEST['COD_TEMPLATE']);
			$des_template = addslashes(htmlentities($_REQUEST['DES_TEMPLATE']));
			$des_assunto = fnLimpaCampo($_REQUEST['DES_ASSUNTO']);
			$des_remet = fnLimpaCampo($_REQUEST['DES_REMET']);

			$sql = "select * from VARIAVEIS order by NUM_ORDENAC ";
			$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
		
			while ($qrListaVariaveis = mysqli_fetch_assoc($arrayQuery))
			  {

				if (strlen(strstr($des_template,$qrListaVariaveis['KEY_BANCOVAR']))>0){ 
					//fnEscreve($qrListaVariaveis['NOM_BANCOVAR']);
					$cod_bancovar = $cod_bancovar.$qrListaVariaveis['COD_BANCOVAR'].",";
				} 
			  
			  }
			  
			$cod_bancovar = substr($cod_bancovar,0,-1);
			

			// fnEscreve($des_template);

			$cod_usucada = $_SESSION['SYS_COD_USUARIO'];

			$opcao = $_REQUEST['opcao'];
			// fnEscreve($opcao);
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
						
			if ($opcao != ''){			
				
				//mensagem de retorno
				switch ($opcao)
				{
					case 'CAD':

						$sql = "INSERT INTO MODELO_EMAIL(
											COD_EMPRESA,
											COD_TEMPLATE,
											DES_ASSUNTO,
											DES_REMET,
											DES_TEMPLATE,
											COD_BANCOVAR,
											COD_USUCADA
											) VALUES(
											$cod_empresa,
											$cod_template,
											'$des_assunto',
											'$des_remet',
											'$des_template',
											'$cod_bancovar',
											$cod_usucada
											)";
						// fnEscreve($sql);

						mysqli_query(connTemp($cod_empresa,""),trim($sql));

						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";	
						break;
					case 'ALT':

						$sql = "UPDATE MODELO_EMAIL SET
											COD_TEMPLATE=$cod_template,
											DES_ASSUNTO='$des_assunto',
											DES_REMET='$des_remet',
											DES_TEMPLATE='$des_template',
											COD_BANCOVAR='$cod_bancovar'
								WHERE COD_MODELO = $cod_modelo";
						fnEscreve($sql);
											
						fnTestesql(connTemp($cod_empresa,""),trim($sql));

						$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";		
						break;
					case 'EXC':

						$sql = "DELETE FROM MODELO_EMAIL
								WHERE COD_MODELO = $cod_modelo";
						// fnEscreve($sql);
											
						mysqli_query(connTemp($cod_empresa,""),trim($sql));

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
		$cod_template = fnDecode($_GET['idT']);	
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

	if($cod_template != "" && $cod_template != 0){

		$sqlModelo = "SELECT COD_MODELO, DES_TEMPLATE, DES_ASSUNTO, DES_REMET FROM MODELO_EMAIL WHERE COD_TEMPLATE = $cod_template";
		$arrayQuery = mysqli_query(connTemp($cod_empresa,""),trim($sqlModelo));

		while ($qrModelo = mysqli_fetch_assoc($arrayQuery)) {
			$cod_modelo = $qrModelo['COD_MODELO'];
			$des_template = $qrModelo['DES_TEMPLATE'];
			$des_assunto = $qrModelo['DES_ASSUNTO'];
			$des_remet = $qrModelo['DES_REMET'];
		}

	}else{
		$cod_modelo = "";
		$des_template = "";
		$des_assunto = "";
		$des_remet = "";
	}
	
	// fnEscreve($cod_modelo);

?>
<?php
require 'emailComponente/config.php';
$id = 0;
?>

<link href="emailComponente/css/colpick.css" rel="stylesheet"  type="text/css"/>
<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" integrity="sha512-dTfge/zgoMYpP7QbHy4gWMEGsbsdZeCXz7irItjcC3sPUFtf0kuFbDz/ixG7ArTxmDjLXDmezHubeNikyKGVyQ==" crossorigin="anonymous">-->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
<!--<link href="emailComponente/css/themes/default.css" rel="stylesheet" type="text/css"/> -->
<link href="emailComponente/css/template.editor.css" rel="stylesheet"/>
<link href="emailComponente/css/responsive-table.css" rel="stylesheet"/>

<script type="text/javascript"> var path = '<?= $path; ?>';</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.22.1/feather.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script type="text/javascript" src="https://code.jquery.com/ui/1.9.2/jquery-ui.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js" integrity="sha512-K1qjQ+NcF2TYO/eI3M6v8EiNYZfA95pQumfvcVrTHtwQVDG+aHRqLi/ETn2uB+1JqwYqVG3LIvdm9lj6imS/pQ==" crossorigin="anonymous"></script>

<script src="//tinymce.cachefly.net/4.0/tinymce.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.2.6/plugins/colorpicker/plugin.min.js"></script>
<script type="text/javascript" src="emailComponente/js/colpick.js"></script>
<script type="text/javascript" src="emailComponente/js/template.editor.js"></script>

<script type="text/javascript" src="js/plugins/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
    tinyMCE.init({
        // General options
        mode: "textareas",
		setup : function(ed)
		{
			// set the editor font size
			ed.onInit.add(function(ed)
			{
			ed.getBody().style.fontSize = '13px';
			});
		},
		language: "pt",
        theme: "advanced",
        plugins: "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",

        // Theme options
        theme_advanced_buttons1: "undo,redo,|,bold,italic,underline,strikethrough,nonbreaking,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,|,forecolor,backcolor,|,copy,paste,cut,|,pastetext,pasteword,|,search,replace,|,link,unlink,anchor,image,|,hr,removeformat,visualaid,|,cleanup,preview,print,code,fullscreen",
        theme_advanced_buttons2: "",
        theme_advanced_buttons3: "",
        theme_advanced_toolbar_location: "top",
        theme_advanced_toolbar_align: "left",
        theme_advanced_statusbar_location: "bottom",
        theme_advanced_resizing: true,

        // Example content CSS (should be your site CSS)
        //content_css : "css/content.css",

        // Drop lists for link/image/media/template dialogs
        template_external_list_url: "lists/template_list.js",
        external_link_list_url: "lists/link_list.js",
        external_image_list_url: "lists/image_list.js",
        media_external_list_url: "lists/media_list.js",

        // Replace values for the template plugin
        template_replace_values: {
            username: "Some User",
            staffid: "991234"
        }
    });
</script>
			
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
									
									<?php 
									$formBack = "1019";
									include "atalhosPortlet.php"; 
									?>	

								</div>
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
												<legend>Banco de Variáveis <small>(<b>Clique e arraste</b> a tag desejada ou <b>copie<b>na área desejada</b>)</small> </legend> 
																
													<div class="row">
														
														<div class="col-md-12">
															<?php 
																if ($cod_empresa == 39){
																$sql = "select * from VARIAVEIS where COD_BANCOVAR in (3,33) order by NUM_ORDENAC";
																}else{
																$sql = "select * from VARIAVEIS order by NUM_ORDENAC";	
																}
																$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
																
																$count=0;
																while ($qrBuscaFases = mysqli_fetch_assoc($arrayQuery))
																  {	
																	$count++;	
																	echo"
																		<button class='btn btn-info btn-xs dragTag' draggable='true' style='margin: 0 4px 7px; box-shadow: 0 2px 2px -1px #D7DBDD;' dragTagName='".$qrBuscaFases['KEY_BANCOVAR']."' onclick='quickCopy('".$qrBuscaFases['ABV_BANCOVAR']."');'>".$qrBuscaFases['ABV_BANCOVAR']."</button>
																		"; 
																	  }											

															?>													
														</div>
														
													</div>
													
											</fieldset>
											
											<div class="push20"></div>

											<fieldset>
					                            <legend>Editar Template</legend> 
																	
					                            <div class="row">
					                                
													<div class="col-md-6">
														<div class="form-group">
															<label for="inputName" class="control-label required">Título do e-Mail (subject)</label>
															<input type="text" class="form-control input-sm" name="DES_ASSUNTO" id="DES_ASSUNTO" maxlength="100" value="<?=$des_assunto?>" required >
														</div>
														<div class="help-block with-errors"></div>
													</div>

													<div class="col-md-6">
														<div class="form-group">
															<label for="inputName" class="control-label required">Remetente do e-Mail (from name)</label>
															<input type="text" class="form-control input-sm" name="DES_REMET" id="DES_REMET" maxlength="100" value="<?=$des_remet?>" required >
														</div>
														<div class="help-block with-errors"></div>
													</div>
													
													<div class="push30"></div>

					                                <div class="col-md-12">

					                                    <textarea name="DES_TEMPLATE" id="DES_TEMPLATE" style="width: 100%; height: 90vh;"><?=$des_template?></textarea>

					                                </div>

					                            </div>

					                        </fieldset>
																				
										<div class="push10"></div>
										<hr>	
										<div class="form-group text-right col-lg-12">
											
											  <button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>
											  <?php if($cod_modelo != '' && $cod_modelo != 0){ ?>
											  	<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Alterar</button>
											  	<button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fa fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>
											  <?php }else{ ?>
											  	<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
											  <?php } ?>
											  
										</div>

										<input type="hidden" name="opcao" id="opcao" value="">
										<input type="hidden" name="COD_MODELO" id="COD_MODELO" value="<?=$cod_modelo?>">
										<input type="hidden" name="COD_TEMPLATE" id="COD_TEMPLATE" value="<?=$cod_template?>">
										<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?=$cod_empresa?>">
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
						
					<div class="push20"></div> 
	
	<script type="text/javascript">

		$('.dragTag').on('dragstart', function (event) {
			var tag = $(this).attr('dragTagName');
			event.originalEvent.dataTransfer.setData("text", ' '+ tag +' ');
			event.originalEvent.dataTransfer.setDragImage(this, 0,0);
		}); 
		
		
		$('.dragTag').on('click', function (event) {
			  var $temp = $("<input>");
			  $("body").append($temp);
			  $temp.val(" @"+$(this).text()+" ").select();
			  document.execCommand("copy");
			  $temp.remove();
		});		
		
		
		
	
		function quickCopy(tag) {
		  var $temp = $("<input>");
		  $("body").append($temp);
		  $temp.val("@"+tag+" ").select();
		  document.execCommand("copy");
		  $temp.remove();
		}
		
		function retornaForm(index){
			$("#formulario #COD_GRUPOTR").val($("#ret_COD_GRUPOTR_"+index).val());
			$("#formulario #DES_GRUPOTR").val($("#ret_DES_GRUPOTR_"+index).val());
			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');						
		}
		
	</script>	