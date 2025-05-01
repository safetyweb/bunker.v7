<?php 

	include '_system/_functionsMain.php'; 

	$opcao = fnLimpaCampo($_GET['opcao']);
	$cod_empresa = fnLimpaCampoZero(fnDecode($_REQUEST['COD_EMPRESA']));
	$cod_documento = fnLimpaCampoZero(fnDecode($_REQUEST['COD_DOCUMENTO']));
	$cod_usucada = $_SESSION['SYS_COD_USUARIO'];

	if($cod_documento != 0){

		$sqlDoc = "SELECT * FROM DOCUMENTOS 
				   WHERE COD_EMPRESA = $cod_empresa
				   AND COD_DOCUMEN = $cod_documento";

		//fnEscreve($sql);
		$arrayDoc = mysqli_query(connTemp($cod_empresa,""),$sqlDoc);
		$qrDoc = mysqli_fetch_assoc($arrayDoc);

		if(isset($qrDoc)){

			$font_family = $qrDoc[FONT_FAMILY];
			$fsize_cabecalho = $qrDoc[FSIZE_CABECALHO];
			$fsize_rodape = $qrDoc[FSIZE_RODAPE];
			$fsize_texto = $qrDoc[FSIZE_TEXTO];
			$fsize_titulo = $qrDoc[FSIZE_TITULO];
			$fsize_bloco = $qrDoc[FSIZE_BLOCO];
			$fsize_looping = $qrDoc[FSIZE_LOOPING];

		}else{

			$font_family = "Arial";
			$fsize_cabecalho = "18";
			$fsize_rodape = "18";
			$fsize_texto = "16";
			$fsize_titulo = "24";
			$fsize_bloco = "14";
			$fsize_looping = "14";

		}

	}	

	switch($opcao){

		case 'texto':

			if($opcao == "texto"){

				$cod_template = fnLimpaCampoZero($_REQUEST['COD_TEMPLATE']);
				$des_template = base64_encode($_REQUEST['DES_TEMPLATE']);

				$sql = "UPDATE TEMPLATE_DOCUMENTO SET 
								DES_TEMPLATE = '$des_template', 
								COD_ALTERAC = $cod_usucada, 
								DAT_ALTERAC = NOW() 
						WHERE COD_EMPRESA = $cod_empresa 
						AND COD_TEMPLATE = $cod_template";
				// fnEscreve($sql);
				mysqli_query(connTemp($cod_empresa,''),$sql);
			}

		case 'exc':

			if($opcao == "exc"){

				$cod_template = fnLimpaCampoZero($_REQUEST['COD_TEMPLATE']);

				$sql = "DELETE FROM TEMPLATE_DOCUMENTO WHERE COD_EMPRESA = $cod_empresa AND COD_TEMPLATE = $cod_template";
				// fnEscreve($sql);
				mysqli_query(connTemp($cod_empresa,''),$sql);

			}

		case 'fonte':

			if($opcao == "fonte"){

				$font_family = fnLimpaCampo($_REQUEST['COD_TEMPLATE']);

				$sqlFamily = "UPDATE DOCUMENTOS SET FONT_FAMILY = '$font_family'
												WHERE COD_EMPRESA = $cod_empresa 
												AND COD_DOCUMEN = $cod_documento";

				// fnEscreve($sqlFamily);

				mysqli_query(connTemp($cod_empresa,''),$sqlFamily);

			}

		case 'paginar':

			$sql = "SELECT * FROM TEMPLATE_DOCUMENTO 
					WHERE COD_DOCUMENTO = $cod_documento
					ORDER BY NUM_ORDENAC";
			//fnEscreve($sql);

			$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
			
			while($qrTempl = mysqli_fetch_assoc($arrayQuery)){

				$chave = $qrTempl[COD_TEMPLATE];

				$conteudo = "";

				$imagem = $qrTempl[DES_IMAGEM];

				$cod_bltempl = $qrTempl[COD_BLTEMPL];

				switch($cod_bltempl){

					case 1:
					case 2:
					case 3:
					case 4:
					case 5:
					case 7:

						if($cod_bltempl == 1){
							$classeFonte = "cabecalho";
						}else if($cod_bltempl == 2){
							$classeFonte = "rodape";
						}else if($cod_bltempl == 3){
							$classeFonte = "texto";
						}else if($cod_bltempl == 4){
							$classeFonte = "titulo";
						}else{
							$classeFonte = "bloco";
						}

						if($qrTempl[DES_TEMPLATE] != ""){
							$conteudo = base64_decode($qrTempl[DES_TEMPLATE]);
						}

						


						$conteudoMovable = '<div class="form-group '.$classeFonte.'">
												<textarea class="editor form-control input-sm" rows="3" name="DES_TEMPLATE_'.$chave.'" id="DES_TEMPLATE_'.$chave.'" maxlength="4000">'.$conteudo.'</textarea>
												<div class="help-block with-errors"></div>
											</div>
											<script type="text/javascript">
												$(function(){

													// TextArea
													$("#DES_TEMPLATE_'.$chave.'").jqte({
														sup: true,
														sub: true,
														outdent: true,
														indent: true,
														left: true,
											    		center: true,
											    		color: false,
											    		right: true,
											    		strike: true,
											    		source: false,
												        link:false,
												        unlink: false,		        
												        remove: true,
												    	rule: true,
												    	fsize: true,
												    	format: true,
												    	blur: function(){
												    		$("#BLOCO_'.$chave.' .jqte .jqte_toolbar").css("display","none");
												    		acaoCarregaBlocos('.$chave.',$("#DES_TEMPLATE_'.$chave.'").val(),0,"texto","'.fnEncode($cod_documento).'");
												    	},
												    	focus: function(){
												    		$("#BLOCO_'.$chave.' .jqte .jqte_toolbar").css("display","block");
												    	}
												    });

												});

											</script>';
						
					break;

					case 6:

						if($imagem == ""){

							$conteudoMovable = '<div class="div-imagem">
													<div  style="height:auto; width: 100%;  display: flex; align-items: center; justify-content: center; padding: 10px; padding-right: 20px;">
														<button class="btn btn-block btn-success" id="upload-image_'.$chave.'"><i class="fa fa-cloud-upload" aria-hidden="true"></i>&nbsp; Insira aqui sua imagem</button>
														<input type="file" id="DES_TEMPLATE_'.$chave.'" cod_registr="'.$chave.'" accept="text/cfg" class="form-control image-file" name="arquivo" style="display: none;"/>
													</div>
												</div>';
						}else{

							$conteudoMovable = "<div  style='height:auto; width: 100%;  display: flex; align-items: center; justify-content: center; padding: 10px; padding-right: 20px;'>
								                <img src='media/clientes/$cod_empresa/$imagem' id='upload-image_$chave' style='cursor: pointer; max-width:100%; max-height: 100%'>
								    			<input type='file' id='DES_TEMPLATE_$chave' cod_registr='$chave' accept='text/cfg' class='form-control image-file' name='arquivo' style='display: none;'/>
								    			</div>";

						}

						$conteudoMovable .= '
											<script>
												$(function(){
													$("#upload-image_'.$chave.'").click(function(){
														$("#DES_TEMPLATE_'.$chave.'").click();
													});
												});
											</script>';
						
					break;

					case 7:
						
					break;

				}

	?>
				<style type="text/css">
					#BLOCO_<?=$chave?> .jqte .jqte_toolbar{
						display: none;
					}
					.jqte:hover{
						cursor: text;
					}
				</style>

				<div class="row movable" id="BLOCO_<?=$chave?>" style="border: unset; box-shadow: unset; border: dashed 1px #ECECEC; padding: 0; margin-bottom: -1px;">
					<?php 
						if($cod_bltempl == 7){
					?>
							<div class="col-md-12">
								<fieldset>
				            		<legend>Variáveis <small>(<b>Clique na tag para copiar, e depois cole na área desejada</b>)</small> </legend>
					<?php
						              
									// $sql = "select * from VARIAVEIS where COD_BANCOVAR in (3,23,39,41,44,45) order by NUM_ORDENAC";
									$sql = "select * from VARIAVEIS where LOG_SMS = 'S' order by NUM_ORDENAC";
									$arrayQuery = mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error());
						              
						        while ($qrBuscaFases = mysqli_fetch_assoc($arrayQuery)) {
					?>
						            <a href="javascript:void(0)" class="btn btn-info btn-xs dragTag" draggable="true" style="margin: 0 4px 7px; box-shadow: 0 2px 2px -1px #D7DBDD;"
						               dragTagName="<?=$qrBuscaFases[KEY_BANCOVAR]?>"
									   tamanho="<?=$qrBuscaFases["NUM_TAMSMS"]?>"
						               onclick="$(function(){quickCopy('<?=$qrBuscaFases[KEY_BANCOVAR]?>')});">
						               <span><?=$qrBuscaFases['ABV_BANCOVAR']?></span>
						            </a>
						             
					<?php
						        }							        
							        
					?>
								</fieldset>
						    </div>

					<?php 
						} 
					?>
					<div class="col-xs-12">
						<div class="row">
							<div class="col-xs-4 col-xs-offset-8 text-right" style="margin-bottom: -26px;">
								<div class="push10"></div>
								<a href="javascript:void(0)" onclick='acaoCarregaBlocos(<?=$chave?>,"",0,"exc","<?=fnEncode($cod_documento)?>")'><span class="fal fa-times text-danger"></span></a>
							</div>
						</div>
						<?=$conteudoMovable?>
					</div>
				</div>

<?php
			}

		break;

		default:

			$cod_bltempl = fnLimpaCampoZero($_REQUEST['COD_BLTEMPL']);

			if($cod_bltempl != 0){

				$qrOrdenac = "SELECT MAX(COD_TEMPLATE)+1 FROM TEMPLATE_DOCUMENTO 
												WHERE COD_EMPRESA = $cod_empresa 
												AND COD_DOCUMENTO = $cod_documento";

				$qrOrdenac = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sqlOrdenac));

				$num_ordenac = 1;

				if($qrOrdenac[ORDENAC] != ""){
					$num_ordenac = $qrOrdenac[ORDENAC];
				}

				if($cod_bltempl == 1){
					$conteudo = 'Digite seu cabeçalho aqui';
				}else if($cod_bltempl == 2){
					$conteudo = 'Digite seu rodapé aqui';
				}else if($cod_bltempl == 3){
					$conteudo = 'Digite seu texto aqui';
				}else if($cod_bltempl == 4){
					$conteudo = 'Digite seu título aqui';
				}else if($cod_bltempl == 5){
					$conteudo = 'Digite seu bloco aqui';
				}else if($cod_bltempl == 7){
					$conteudo = 'Digite seu texto com variáveis aqui';
				}else{
					$conteudo = '';
				}

				$sql = "INSERT INTO TEMPLATE_DOCUMENTO(
									COD_EMPRESA,
									COD_DOCUMENTO,
									COD_BLTEMPL,
									DES_TEMPLATE,
									COD_USUCADA
									) VALUES(
									$cod_empresa,
									$cod_documento,
									$cod_bltempl,
									'".base64_encode($conteudo)."',
									$cod_usucada
									)";
				// fnEscreve($sql);

				mysqli_query(connTemp($cod_empresa,''),$sql);
				
				$count=0;
															
				$sql = "SELECT * FROM TEMPLATE_DOCUMENTO 
						WHERE COD_TEMPLATE = (SELECT MAX(COD_TEMPLATE) FROM TEMPLATE_DOCUMENTO 
												WHERE COD_EMPRESA = $cod_empresa 
												AND COD_BLTEMPL = $cod_bltempl)
						ORDER BY NUM_ORDENAC";
				//fnEscreve($sql);
				$qrTempl = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));

				$cod_bltempl = $qrTempl[COD_BLTEMPL];

				$chave = $qrTempl[COD_TEMPLATE];

				$conteudo = "";

				$imagem = $qrTempl[DES_IMAGEM];

				$cod_bltempl = $qrTempl[COD_BLTEMPL];

				switch($cod_bltempl){

					case 1:
					case 2:
					case 3:
					case 4:
					case 5:
					case 7:

						if($cod_bltempl == 1){
							$classeFonte = "cabecalho";
						}else if($cod_bltempl == 2){
							$classeFonte = "rodape";
						}else if($cod_bltempl == 3){
							$classeFonte = "texto";
						}else if($cod_bltempl == 4){
							$classeFonte = "titulo";
						}else{
							$classeFonte = "bloco";
						}

						if($qrTempl[DES_TEMPLATE] != ""){
							$conteudo = base64_decode($qrTempl[DES_TEMPLATE]);
						}

						$conteudoMovable = '<div class="form-group '.$classeFonte.'">
												<textarea class="editor form-control input-sm" rows="3" name="DES_TEMPLATE_'.$chave.'" id="DES_TEMPLATE_'.$chave.'" maxlength="4000">'.$conteudo.'</textarea>
												<div class="help-block with-errors"></div>
											</div>
											<script type="text/javascript">
												$(function(){

													// TextArea
													$("#DES_TEMPLATE_'.$chave.'").jqte({
														sup: true,
														sub: true,
														outdent: true,
														indent: true,
														left: true,
											    		center: true,
											    		color: false,
											    		right: true,
											    		strike: true,
											    		source: false,
												        link:false,
												        unlink: false,		        
												        remove: true,
												    	rule: true,
												    	fsize: true,
												    	format: true,
												    	blur: function(){
												    		$("#BLOCO_'.$chave.' .jqte .jqte_toolbar").css("display","none");
												    		acaoCarregaBlocos('.$chave.',$("#DES_TEMPLATE_'.$chave.'").val(),0,"texto","'.fnEncode($cod_documento).'");
												    	},
												    	focus: function(){
												    		$("#BLOCO_'.$chave.' .jqte .jqte_toolbar").css("display","block");
												    	}
												    });

												});

											</script>';
						
					break;

					case 6:

						if($imagem == ""){

							$conteudoMovable = '<div class="div-imagem">
													<div  style="height:auto; width: 100%;  display: flex; align-items: center; justify-content: center; padding: 10px; padding-right: 20px;">
														<button class="btn btn-block btn-success" id="upload-image_'.$chave.'"><i class="fa fa-cloud-upload" aria-hidden="true"></i>&nbsp; Insira aqui sua imagem</button>
														<input type="file" id="DES_TEMPLATE_'.$chave.'" cod_registr="'.$chave.'" accept="text/cfg" class="form-control image-file" name="arquivo" style="display: none;"/>
													</div>
												</div>';
						}else{

							$conteudoMovable = "<div  style='height:auto; width: 100%;  display: flex; align-items: center; justify-content: center; padding: 10px; padding-right: 20px;'>
								                <img src='media/clientes/$cod_empresa/$imagem' id='upload-image_$chave' style='cursor: pointer; max-width:100%; max-height: 100%'>
								    			<input type='file' id='DES_TEMPLATE_$chave' cod_registr='$chave' accept='text/cfg' class='form-control image-file' name='arquivo' style='display: none;'/>
								    			</div>";

						}

						$conteudoMovable .= '
											<script>
												$(function(){
													$("#upload-image_'.$chave.'").click(function(){
														$("#DES_TEMPLATE_'.$chave.'").click();
													});
												});
											</script>';
						
					break;

				}

	?>
				<style type="text/css">
					#BLOCO_<?=$chave?> .jqte .jqte_toolbar{
						display: none;
					}
					.jqte:hover{
						cursor: text;
					}
				</style>

				<div class="row movable" id="BLOCO_<?=$chave?>" style="border: unset; box-shadow: unset; border: dashed 1px #ECECEC; padding: 0; margin-bottom: -1px;">
					<?php 
						if($cod_bltempl == 7){
					?>
							<div class="col-md-12">
								<fieldset>
				            		<legend>Variáveis <small>(<b>Clique na tag para copiar, e depois cole na área desejada</b>)</small> </legend>
					<?php
						              
									// $sql = "select * from VARIAVEIS where COD_BANCOVAR in (3,23,39,41,44,45) order by NUM_ORDENAC";
									$sql = "select * from VARIAVEIS where LOG_SMS = 'S' order by NUM_ORDENAC";
									$arrayQuery = mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error());
						              
						        while ($qrBuscaFases = mysqli_fetch_assoc($arrayQuery)) {
					?>
						            <a href="javascript:void(0)" class="btn btn-info btn-xs dragTag" draggable="true" style="margin: 0 4px 7px; box-shadow: 0 2px 2px -1px #D7DBDD;"
						               dragTagName="<?=$qrBuscaFases[KEY_BANCOVAR]?>"
									   tamanho="<?=$qrBuscaFases["NUM_TAMSMS"]?>"
						               onclick="$(function(){quickCopy('<?=$qrBuscaFases[KEY_BANCOVAR]?>')});">
						               <span><?=$qrBuscaFases['ABV_BANCOVAR']?></span>
						            </a>
						             
					<?php
						        }							        
							        
					?>
								</fieldset>
						    </div>

					<?php 
						} 
					?>
					<div class="col-xs-12">
						<div class="row">
							<div class="col-xs-4 col-xs-offset-8 text-right" style="margin-bottom: -26px;">
								<div class="push10"></div>
								<a href="javascript:void(0)" onclick='acaoCarregaBlocos(<?=$chave?>,"",0,"exc","<?=fnEncode($cod_documento)?>")'><span class="fal fa-times text-danger"></span></a>
							</div>
						</div>
						<?=$conteudoMovable?>
					</div>
				</div>

				<script type="text/javascript">

					//ORDENAR AO CARREGAR ITENS -----------------------------
					var Ids = "";
					jQuery('#drop-target .movable').each(function( index ) {
						Ids += jQuery(this).attr('id').substring(6) + ",";
					});

					var arrayOrdem = Ids.substring(0,(Ids.length-1));

					execOrdenacao(arrayOrdem,11,"<?=$cod_empresa?>");
					// ------------------------------------------------------
				</script>

<?php
			}

		break;
	}
?>
