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

			$cod_app = fnLimpaCampoZero($_REQUEST['COD_APP']);
			$cod_empresa = fnLimpaCampo($_REQUEST['COD_EMPRESA']);
			$des_termosapp = addslashes(htmlentities($_REQUEST['DES_TERMOSAPP']));

			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
						
			if ($opcao != ''){

				//mensagem de retorno
				switch ($opcao)
				{
					case 'CAD':

						$des_logo = "";
						$des_imgback = "";

						$cor_fullpag = "#34495e";
					    $cor_textfull = "#fff";

					    $cor_backbar = "34495e";
					    $cor_backpag = "f2f3f4";

					    $cor_titulos = "#34495e";
					    $cor_textos = "#34495e";

					    $cor_botao = "#0092d8";
					    $cor_botaoon = "#48c9b0";

					    $log_colunas = 'S';
					    $log_ofertas = 'S';
					    $log_jornal = 'S';
					    $log_habito = 'S';
						$log_dados = 'S';
						$log_extrato = 'S';
						$log_premios = 'S';
						$log_enderecos = 'S';
						$log_comunica = 'S';
						$log_bannerhome = 'N';
						$log_bannerlista = 'N';

					    $chk_colunas = "checked";
					    $disp_dupla = "block";
					    $disp_unica = "none";
					    $chk_ofertas = "checked";
					    $disp_ofertas = "block";
					    $chk_jornal = "";
					    $disp_jornal = "none";
					    $chk_habito = "";
					    $disp_habito = "none";
					    $chk_dados = "checked";
					    $disp_dados = "block";
					    $chk_extrato = "checked";
					    $disp_extrato = "block";
					    $chk_premios = "checked";
					    $disp_premios = "block";
					    $chk_enderecos = "checked";
					    $disp_enderecos = "block";
					    $chk_comunica = "checked";
					    $disp_comunica = "block";
					    $chk_bannerhome = "";
					    $disp_bannerhome = "none";
					    $chk_bannerlista = "";
					    $disp_bannerlista = "none";

					    $sql = "INSERT INTO totem_app(
									COD_EMPRESA, 
									DES_LOGO, 
									DES_IMGBACK, 
									COR_BACKBAR, 
									COR_BACKPAG, 
									COR_TITULOS, 
									COR_TEXTOS, 
									COR_BOTAO, 
									COR_BOTAOON, 
									COR_FULLPAG, 
									COR_TEXTFULL,
									LOG_COLUNAS,
									LOG_OFERTAS,
									LOG_JORNAL,
									LOG_HABITO,
									LOG_DADOS,
									LOG_EXTRATO,
									LOG_PREMIOS,
									LOG_ENDERECOS,
									LOG_COMUNICA,
									LOG_BANNERHOME,
									LOG_BANNERLISTA,
									DES_TERMOSAPP
									) 
									VALUES (
									'$cod_empresa', 
									'$des_logo', 
									'$des_imgback', 
									'$cor_backbar', 
									'$cor_backpag', 
									'$cor_titulos', 
									'$cor_textos', 
									'$cor_botao', 
									'$cor_botaoon', 
									'$cor_fullpag', 
									'$cor_textfull',
									'$log_colunas',
									'$log_ofertas',
									'$log_jornal',
									'$log_habito',
									'$log_dados',
									'$log_extrato',
									'$log_premios',
									'$log_enderecos',
									'$log_comunica',
									'$log_bannerhome',
									'$log_bannerlista',
									'$des_termosapp'
									);";

						// fnEscreve($sql);


						mysqli_query(connTemp($cod_empresa,""),$sql);

						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";	
						break;
					case 'ALT':

						$sql = "UPDATE TOTEM_APP SET
								DES_TERMOSAPP='$des_termosapp'
								WHERE COD_EMPRESA = $cod_empresa
								AND COD_APP = $cod_app";

						// fnEscreve($sql);

						mysqli_query(connTemp($cod_empresa,""),$sql);

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

	//busca dados da tabela
	$sql = "SELECT COD_APP, DES_TERMOSAPP FROM TOTEM_APP WHERE COD_EMPRESA = $cod_empresa";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
	$qrBuscaSiteTotemApp = mysqli_fetch_assoc($arrayQuery);

	if (isset($qrBuscaSiteTotemApp)){
		$cod_app = $qrBuscaSiteTotemApp['COD_APP'];
		$des_termosapp = $qrBuscaSiteTotemApp['DES_TERMOSAPP'];
	}else{
		$cod_app = 0;
		$des_termosapp = "";
	}
	
	//fnMostraForm();

?>

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
										<i class="fal fa-terminal"></i>
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
									
									<?php $abaEmpresa = 1258; include "abasEmpresaConfig.php"; ?>

									<div class="push50"></div>

									<?php $abaApp = 1613; include "abasApp.php";  ?>
									
									<div class="push50"></div>
			
									<div class="login-form">
									
										<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
																				
											<fieldset>
					                            <legend>Termos e Condições</legend> 

					                            <div class="row">

					                                <div class="col-md-12">

					                                    <textarea name="DES_TERMOSAPP" id="DES_TERMOSAPP" style="width: 100%; height: 240px;"><?php echo $des_termosapp; ?></textarea>

					                                </div>

					                            </div>

					                        </fieldset>
																					
											<div class="push10"></div>
											<hr>	
											<div class="form-group text-right col-lg-12">
												
												  <button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
												  <?php if($cod_app == 0){ ?>
												  <button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
												  <?php }else{ ?>
												  <button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>
												  <?php } ?>
												
											</div>
											
											<input type="hidden" name="opcao" id="opcao" value="">
											<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?=$cod_empresa?>">
											<input type="hidden" name="COD_APP" id="COD_APP" value="<?=$cod_app?>">
											<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
											<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">		
											
											<div class="push5"></div> 
										
										</form>
									
									</div>								
								
								</div>
							</div>
							<!-- fim Portlet -->
						</div>
						
					</div>					
						
					<div class="push20"></div> 
	
	<script type="text/javascript">
		
		function retornaForm(index){
			$("#formulario #COD_GRUPOTR").val($("#ret_COD_GRUPOTR_"+index).val());
			$("#formulario #DES_GRUPOTR").val($("#ret_DES_GRUPOTR_"+index).val());
			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');						
		}
		
	</script>	