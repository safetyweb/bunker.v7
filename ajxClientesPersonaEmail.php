<?php 

	include '_system/_functionsMain.php';

	$cod_acao = fnLimpaCampoZero($_POST['COD_ACAO']);
	$cod_empresa = fnLimpaCampoZero($_POST['COD_EMPRESA']);
	$opcao = fnLimpaCampo($_GET['opcao']);

	switch($opcao){

		case 'template':

			$sql = "SELECT DES_ASSUNTO, DES_REMET, DES_TEMPLATE FROM MODELO_EMAIL WHERE COD_TEMPLATE = $cod_acao";
			$qrTemp = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));

		?>

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

			<div class="col-md-6">
				<div class="form-group">
					<label for="inputName" class="control-label required">Título do e-Mail (subject)</label>
					<input type="text" class="form-control input-sm" name="DES_ASSUNTO" id="DES_ASSUNTO" maxlength="100" value="<?=$qrTemp['DES_ASSUNTO']?>" required >
				</div>
				<div class="help-block with-errors"></div>
			</div>

			<div class="col-md-6">
				<div class="form-group">
					<label for="inputName" class="control-label required">Remetente do e-Mail (from name)</label>
					<input type="text" class="form-control input-sm" name="DES_REMET" id="DES_REMET" maxlength="100" value="<?=$qrTemp['DES_REMET']?>" required >
				</div>
				<div class="help-block with-errors"></div>
			</div>
			
			<div class="push30"></div>

            <div class="col-md-12">

                <textarea name="DES_TEMPLATE" id="DES_TEMPLATE" style="width: 100%; height: 90vh;"><?=$qrTemp['DES_TEMPLATE']?></textarea>

            </div>

		<?php

		break;

		default:

			$sqlCli = "SELECT 
						(SELECT COUNT(1) FROM CLIENTES CL 
							LEFT JOIN PERSONACLASSIFICA PC ON PC.COD_CLIENTE = CL.COD_CLIENTE 
					        WHERE CL.DES_EMAILUS != ''
					        AND PC.COD_PERSONA = $cod_acao) AS QTD_EMAILOK, 
						(SELECT COUNT(1) FROM CLIENTES CL 
							LEFT JOIN PERSONACLASSIFICA PC ON PC.COD_CLIENTE = CL.COD_CLIENTE 
					        WHERE (CL.DES_EMAILUS = '' OR CL.DES_EMAILUS IS NULL)
					        AND PC.COD_PERSONA = $cod_acao) AS QTD_EMAILNOK,
					    COUNT(CL.COD_CLIENTE) AS QTD_CLIENTE
					FROM PERSONACLASSIFICA PC 
					LEFT JOIN CLIENTES CL ON CL.COD_CLIENTE = PC.COD_CLIENTE
					WHERE PC.COD_PERSONA = $cod_acao";

			$qrCli = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sqlCli));

			$semEmail = $qrCli['QTD_EMAILNOK'];
			$comEmail = $qrCli['QTD_EMAILOK'];
			$qtdClientes = $qrCli['QTD_CLIENTE'];

	?>

			<table class="table table-hover">
										
			  <thead>
				<tr>
				  <th class="text-center text-info">Clientes Sem Email:<b> &nbsp; <?php echo fnValor($semEmail,0); ?></b></th>
				  <th class="text-center text-info">Clientes Com Email:<b> &nbsp; <?php echo fnValor($comEmail,0); ?></b></th>
				  <th class="text-center text-info">Total de Clientes (Persona):<b> &nbsp; <?php echo fnValor($qtdClientes,0); ?></b></th>
				</tr>
			  </thead>
			  
			</table>
			
			<div class="push10"></div>

			<div class="no-more-tables">
					
				<table class="table table-bordered table-striped table-hover tableSorter">
					  <thead>
						<tr>
						  <th>Cliente</th>
						  <th>Dt. Nasc.</th>
						  <th>Email</th>
						  <th>Loja</th>
						</tr>
					  </thead>
					<tbody>

					<?php

					$sqlCli = "SELECT DISTINCT CL.NOM_CLIENTE, CL.DES_EMAILUS, UN.NOM_FANTASI, CL.DAT_NASCIME 
								FROM PERSONACLASSIFICA PC 
							    LEFT JOIN CLIENTES CL ON CL.COD_CLIENTE = PC.COD_CLIENTE
							    LEFT JOIN WEBTOOLS.UNIDADEVENDA UN ON UN.COD_UNIVEND = CL.COD_UNIVEND
							   WHERE PC.COD_PERSONA = $cod_acao
							   LIMIT 50";

					$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sqlCli);

					while($qrCli = mysqli_fetch_assoc($arrayQuery)){

						if($qrCli['DES_EMAILUS'] != ""){

							echo"
								<tr>
								  <td>".$qrCli['NOM_CLIENTE']."</td>
								  <td>".$qrCli['DAT_NASCIME']."</td>
								  <td>".$qrCli['DES_EMAILUS']."</td>
								  <td>".$qrCli['NOM_FANTASI']."</td>
								</tr>
							";

						}

					}

					?>
					</tbody>
				</table>

				<script>
					$("#QTD_EMAILOK").val("<?=$comEmail?>");
					$("#QTD_EMAILNOK").val("<?=$semEmail?>");
					$("#QTD_CLIENTE").val("<?=$qtdClientes?>");
				</script>

			</div>

	<?php
		break;
	} 
	?>