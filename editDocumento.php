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

			$cod_categortkt = fnLimpaCampoZero($_REQUEST['COD_CATEGORTKT']);
			$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
			$des_categor = fnLimpaCampo($_REQUEST['DES_CATEGOR']);
			$des_abrevia = fnLimpaCampo($_REQUEST['DES_ABREVIA']);
			$des_icones = fnLimpaCampo($_REQUEST['DES_ICONES']);
			if (empty($_REQUEST['LOG_DESTAK'])) {$log_destak='N';}else{$log_destak=$_REQUEST['LOG_DESTAK'];}
			
			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
			
			//fnEscreve($des_icones);	
			
			if ($opcao != ''){
 
				$sql = "CALL SP_ALTERA_CATEGORIATKT (
				 '".$cod_categortkt."', 
				 '".$cod_empresa."', 
				 '".$des_categor."', 
				 '".$des_abrevia."', 
				 '".$des_icones."', 
				 '".$log_destak."', 
				 '".$_SESSION["SYS_COD_USUARIO"]."', 
				 '".$opcao."'    
				) ";
				
				
				//fnEscreve($sql);
				
				mysqli_query(connTemp($cod_empresa,""),trim($sql)) or die(mysqli_error());				
				
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
		$cod_documento = fnLimpaCampoZero(fnDecode($_GET['idD']));
		$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '".$cod_empresa."' ";

		//fnEscreve($sql);
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
			
		if (isset($qrBuscaEmpresa)){
			$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];

		}
												
	}else {
		$cod_empresa = 0;		
		$nom_empresa = "";
	
	}

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
	
?>

<style type="text/css">


    .connectedSortable {
        list-style-type: none;
        padding: 0;
    }

    .connectedSortable li:not(.normal) {
        min-height: 60px;
        text-align: center;
        width: 80px;
		height: auto !important;
		overflow: hidden;
    }

    #sortable1 {
        float: left;
    }
	
	#sortable1 li, #sortable3 li {
        margin-top: 20px;
		border-radius: 5px;
		background-color: transparent;
		font-size: 25px !important;
    }

   
	.ui-state-default {
		border: 1px solid #c5c5c5;
		background: #f6f6f6;
		font-weight: normal;
		color: #454545;
	}
	
	.ui-sortable-handle {
		touch-action: none;
	}
	
	.ui-state-default{
		border: none;
	}
	
	.ui-state-default a {
		color: #454545;
		text-decoration: none;
	}
	
	.container {
	  max-width: 100%;
	  margin: 0;
	  padding: 0;
	}

	/*.left {
	  float: left;
	  position: relative;
	  width: 50%;
	  height: 100%;
	}

	.right {
	  float: left;
	  position: relative;
	  width: 40%;
	  margin-left: 5%;
	  height: 100%;
	}*/

	#display {
	  background: #2d2d2d;
	  border: 10px solid #000000;
	  border-radius: 5px;
	  font-size: 2em;
	  color: white;
	  height: 100px;
	  min-width:200px;
	  text-align: center;
	  padding: 1em;
	  display:table-cell;
	  vertical-align:middle;
	}

	#drag-elements {
	  display: block;
	  /*background-color: #FAFBFC;*/
	  border-radius: 5px;
	  min-height: 50px;
	  margin: 0 auto;
	  height: auto;
	  /*padding: 1em 2em;*/
	  -webkit-touch-callout: none;
	  -webkit-user-select: none;
	  -khtml-user-select: none;
	  -moz-user-select: none;
	  -ms-user-select: none;
	  -o-user-select: none;
	  user-select: none;
	}

	#drag-elements > div .outline {
	  cursor: move; /* fallback if grab cursor is unsupported */
	    cursor: grab;
	    cursor: -moz-grab;
	    cursor: -webkit-grab;
	  padding: 0.7em 0;
	  /*argin: 0 1em 1em 0;*/
	  /*width: 100%;*/
	  /*box-shadow: 1px 1px 1px rgba(0, 0, 0, 0.3);*/
	  margin-bottom: 10px;
	  border: 2px solid #F4F4F4;
	  border-radius: 3px;
	  background: #FFF;
	  transition: all .5s ease;
	}

	#drag-elements > div .outline:active {
	  cursor: move; /* fallback if grab cursor is unsupported */
	    cursor: grab;
	    cursor: -moz-grab;
	    cursor: -webkit-grab;
	  -webkit-animation: flickerAnimation 0.3s 0s infinite ease-in-out;
	  animation: flickerAnimation 0.8s 0s infinite ease-in-out;
	  -webkit-touch-callout: none; /* iOS Safari */
	    -webkit-user-select: none; /* Safari */
	     -khtml-user-select: none; /* Konqueror HTML */
	       -moz-user-select: none; /* Old versions of Firefox */
	        -ms-user-select: none; /* Internet Explorer/Edge */
	            user-select: none; /* Non-prefixed version, currently
	                                  supported by Chrome, Opera and Firefox */
	  opacity: .6;
	  border: 2px solid #000;
	}

	#drag-elements > div .outline:hover {
	  border: 2px solid gray;
	  background-color: #e5e5e5;
	}

	#drop-target {
	  border: 2px dashed #ECECEC;
	  border-radius: 5px;
	  min-height: 270px;
	  margin: 0 auto;
	  height: auto;
	  padding: 2em;
	  display: block;
/*	  text-align: center;*/
	  -webkit-touch-callout: none;
	  -webkit-user-select: none;
	  -khtml-user-select: none;
	  -moz-user-select: none;
	  -ms-user-select: none;
	  -o-user-select: none;
	  user-select: none;
	}

	#drop-target > div {
	  transition: all .5s;
	  cursor: move; /* fallback if grab cursor is unsupported */
	    cursor: grab;
	    cursor: -moz-grab;
	    cursor: -webkit-grab;
	  padding: 1em;
	  margin: 0 1em 0.5em 0;
	  width: 100%;
	  box-shadow: 1px 1px 1px rgba(0, 0, 0, 0.3);
	  border-radius: 5px;
	  border: 1px solid;
	  /*background: #F7F7F7;*/
	  transition: all .5s ease;
	}

	#drop-target > div:active {
	  cursor: move; /* fallback if grab cursor is unsupported */
	  cursor: grab;
	  cursor: -moz-grab;
	  cursor: -webkit-grab;
	  opacity: .6;
	  border: 2px solid #000;
	  -webkit-animation: flickerAnimation 1s 0s infinite ease-in-out;
	  animation: flickerAnimation 1s 0s infinite ease-in-out;
	  -webkit-touch-callout: none; /* iOS Safari */
	    -webkit-user-select: none; /* Safari */
	     -khtml-user-select: none; /* Konqueror HTML */
	       -moz-user-select: none; /* Old versions of Firefox */
	        -ms-user-select: none; /* Internet Explorer/Edge */
	            user-select: none; /* Non-prefixed version, currently
	                                  supported by Chrome, Opera and Firefox */
	}

	@keyframes flickerAnimation {
	  0%   { opacity:1; }
	  50%  { opacity:0; }
	  100% { opacity:1; }
	}
	@-o-keyframes flickerAnimation{
	  0%   { opacity:1; }
	  50%  { opacity:0; }
	  100% { opacity:1; }
	}
	@-moz-keyframes flickerAnimation{
	  0%   { opacity:1; }
	  50%  { opacity:0; }
	  100% { opacity:1; }
	}
	@-webkit-keyframes flickerAnimation{
	  0%   { opacity:1; }
	  50%  { opacity:0; }
	  100% { opacity:1; }
	}

	@-webkit-keyframes wiggle {
	  0% {
	    -webkit-transform: rotate(0deg);
	  }
	  25% {
	    -webkit-transform: rotate(2deg);
	  }
	  75% {
	    -webkit-transform: rotate(-2deg);
	  }
	  100% {
	    -webkit-transform: rotate(0deg);
	  }
	}

	@keyframes wiggle {
	  0% {
	    transform: rotate(-2deg);
	  }
	  25% {
	    transform: rotate(2deg);
	  }
	  75% {
	    transform: rotate(-2deg);
	  }
	  100% {
	    transform: rotate(0deg);
	  }
	}

	.gu-mirror {
	   cursor: move; /* fallback if grab cursor is unsupported */
	    cursor: grabbing;
	    cursor: -moz-grabbing;
	    cursor: -webkit-grabbing;
	  padding: 0.3em 0;
	  margin: 0 1em 1em 0;
	  /*width: 100%;*/
	  /*box-shadow: 1px 1px 1px rgba(0, 0, 0, 0.3);*/
	  border: 2px solid #F4F4F4;
	  border-radius: 3px;
	  background: #FFF;
	  transition: opacity 0.4s ease-in-out;
	}

	.gu-hide {
	  display: none!important
	}

	.gu-unselectable {
	  -webkit-user-select: none!important;
	  -moz-user-select: none!important;
	  -ms-user-select: none!important;
	  user-select: none!important
	}

	.gu-transit {
	  opacity: .2;
	  -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=20)";
	  filter: alpha(opacity=20)
	}

	.barra:before{
		position:absolute;
		content: "|";
		/*margin-top: 3px;*/
		left:2px;
		font-size: 15px;
		font-weight: 1000;
		transform: scale(2,1.3);
		/*border-radius: 1px;*/
		/*background: #000;*/
	}

	#trigger:hover{
		cursor: default;
	}

	#trigger:active{
		cursor: default!important;
		-webkit-animation: none!important;
	  	animation: none!important;
	  	opacity: 1!important;
	  	border: 2px solid skyblue!important;
	}

	/*#drag-elements .movable{
		margin-bottom: 10px;
	}

	#drag-elements .movable:nth-child(odd){
		margin-right: 10px;
	}*/

	.not-movable{
		/*padding-right: 0!important;
		padding-left: 0!important;*/
		/*margin-right: 0!important;*/
	}

	.not-movable:hover .transparency{
		opacity: 1!important;
	}

	.no-padding-sides{
		padding-left: 0;
		padding-right: 0;
	}


/* jqte editor -------------------------------------------------------------------------------------------	*/
	.jqte {
	    border: none!important;
/*	    border-radius: 3px!important;*/
/*	    -webkit-border-radius: 3px!important;    */
	    box-shadow: 0 0 2px #dce4ec!important;
	    -webkit-box-shadow: 0 0 0px #dce4ec!important;
	    -moz-box-shadow: 0 0 3px #dce4ec!important;    
/*	    transition: box-shadow 0.4s, border 0.4s;*/
	    margin-top: 0px!important;
	    margin-bottom: 0px!important;
	}

	.jqte_toolbar {   
	    background: #fff!important;
	    border-bottom: none!important;
	}

	.jqte_focused {
		border: none!important;
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
	.jqte_tool{
/*		float: right!important;*/
	}
	.form-group{
		border: none!important;
	}
/* -------------------------------------------------------------------------------------------------------	*/

#opcoes,
#variaveis,
#visualizar{
	display: none;
}

.modal-content{
	width: 100vw;
	height: 100vh;
}
.modal-dialog{
	max-width: unset;
	max-height: unset;
	margin: 0;
}

.cabecalho, .cabecalho .jqte > *,
.rodape, .rodape .jqte > *,
.texto, .texto .jqte > *,
.titulo, .titulo .jqte > *,
.bloco, .bloco .jqte > *{
	font-family: "<?=$font_family?>";
}
.cabecalho, .cabecalho .jqte > *{font-size: <?=$fsize_cabecalho?>!important}
.rodape, .rodape .jqte > *{font-size: <?=$fsize_rodape?>!important}
.texto, .texto .jqte > *{font-size: <?=$fsize_texto?>!important}
.titulo, .titulo .jqte > *{font-size: <?=$fsize_titulo?>!important}
.bloco, .bloco .jqte > *{font-size: <?=$fsize_bloco?>!important}
</style>
			
<div class="push30"></div> 

<link href='https://bevacqua.github.io/dragula/dist/dragula.min.css' rel='stylesheet' type='text/css' />
<link href="https://fonts.cdnfonts.com/css/oklahoma" rel="stylesheet">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@400;500;600;700;800&display=swap" rel="stylesheet">

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
				$formBack = "1108";
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
				
				<?php 
					echo ('<div class="push20"></div>');
					$abaDoc = fnDecode($_GET['mod']);
					include "abasDocumentosConfig.php"; 
				?>
				
				<div class="push30"></div> 

				<div class="login-form">

					
				
					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<div class="row">
											  
							<div class="col-md-2">

								<div class="aba" id="template">

								    <div class="row drag-elements" id="drag-elements">

								    	<?php

									    	$sql = "SELECT * FROM BLOCOS_DOCUMENTO WHERE COD_EMPRESA = $cod_empresa ORDER BY NUM_ORDENAC";
									    	$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);

									    	while($qrBloco = mysqli_fetch_assoc($arrayQuery)){

									    ?>

										    	<div class="col-xs-12 col-md-6 movable" id="<?=$qrBloco[COD_BLTEMPL]?>">
										    		<div class="outline">
											    		<div class="push10"></div>
											    		<div class="col-xs-12 text-center">
											    			<i class="fal <?=$qrBloco[DES_ICONE]?> fa-2x" aria-hidden="true"></i>
											    		</div>
											    		<div class="col-xs-12 text-center f10"><?=$qrBloco[NOM_BLOCO]?></div>
											    		<div class="push10"></div>
										    		</div>
										    	</div>

										<?php 

											}

									    ?>

								    </div>

								</div>

								<div class="aba" id="opcoes">
						
									<div class="row">

										<div class="col-xs-12">
											<div class="form-group">
												<label for="inputName" class="control-label">Fonte</label>
												<select data-placeholder="Selecione uma fonte" name="FONT_FAMILY" id="FONT_FAMILY" class="chosen-select-deselect" tabindex="1" onchange="mudaFonte(this)" >													
													<option value="Arial" style="font-family:Arial;">Arial</option>
											        <option value="Comic Sans MS" style="font-family:Comic Sans MS;">Comic Sans MS</option>
											        <option value="Impact" style="font-family:Impact;">Impact </option>
											        <option value="Oklahoma" style="font-family:Oklahoma;">Oklahoma</option>
											        <option value="Roboto Slab" style="font-family:Roboto Slab;">Roboto</option>
											        <option value="Times New Roman" style="font-family:Times New Roman;">Times New Roman</option>
											        <option value="Verdana" style="font-family:Verdana;">Verdana </option>
												</select>
											</div>
										</div>
										<script type="text/javascript">
											$(function(){
												$("#FONT_FAMILY").val("<?=$font_family?>").trigger("chosen:updated");
											});
											function mudaFonte(family){
												$('.jqte *').css("font-family", $(family).val());
												acaoCarregaBlocos($(family).val(),"",0,"fonte","<?=fnEncode($cod_documento)?>")
											}
										</script>

									</div>

								</div>

								<div class="aba" id="variaveis">
						
									<div class="row">

										<div class="col-xs-12">
											<div class="form-group">
												<label for="inputName" class="control-label">Fonte</label>
												<select data-placeholder="Selecione uma fonte" name="COD_TPCONTRAT" id="COD_TPCONTRAT" class="chosen-select-deselect" tabindex="1" onchange="mostraVeiculos(this)" data-element="#dados">
													<option value="1">Roboto</option>
													<option value="2">Arial</option>
													<option value="3">Times New Roman</option>
													<option value="4">Oklahoma</option>

												</select>
											</div>
										</div>

									</div>

								</div>

							</div>

						    <div class="col-md-8 col-md-offset-1">

						    	<div class="col-md-12 col-sm-12 col-xs-12" id="drop-target">

						    		
						    	</div>

						    </div>

						</div>
						
						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">		
						
						<div class="push5"></div>					
					
					</form>
				
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
				<iframe frameborder="0" style="width: 100%; height: 89vh"></iframe>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
	
	
<div class="push20"></div> 
				
<link rel="stylesheet" href="js/plugins/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css"/>
<script type="text/javascript" src="js/bootstrap-iconpicker-iconset-fa5.js"></script>
<script type="text/javascript" src="js/plugins/bootstrap-iconpicker/js/bootstrap-iconpicker.min.js"></script>

<script src="js/jquery-ui.js"></script>

<script src='https://cdnjs.cloudflare.com/ajax/libs/dragula/3.7.2/dragula.min.js'></script>
<link rel="stylesheet" type="text/css" href="js/plugins/jQuery-TE/jquery-te-1.4.0.css">
<link rel="stylesheet" type="text/css" href="js/plugins/jQuery-TE/jquery-te.png">
<script type="text/javascript" src="js/plugins/jQuery-TE/jquery-te-1.4.0.min.js"></script>	


<script type="text/javascript">
	
	$(function(){

		acaoCarregaBlocos();

		$('.dragTag').on('dragstart', function (event) {
		    var tag = $(this).attr('dragTagName');
		    event.originalEvent.dataTransfer.setData("text", ' ' + tag + ' ');
		    event.originalEvent.dataTransfer.setDragImage(this, 0, 0);
		});

		$('.dragTag').on('dragend', function (event) {
		    updateCount($('#DES_TEMPLATE'));
		});

		$('.dragTag').on('click', function (event) {
		    var $temp = $("<input>");
		    $("#tosave").append($temp);
		    $temp.val($(this).text()).select();
		    document.execCommand("copy");
		    $temp.remove();
		});
		
		$('body').on('change', '.image-file', function() {
			var formData = new FormData();
			formData.append('arquivo', $(this)[0].files[0]);
			formData.append('id', "<?=fnEncode($cod_empresa)?>");
			formData.append('cod_registr', $(this).attr('cod_registr'));
			formData.append('table', "TEMPLATE_DOCUMENTO");
			
			var div_imagem = $(this).parent().parent();
			
			$.ajax({
				url : 'uploads/uploadpro.php',
				type : 'POST',
				data : formData,
				processData: false,  // tell jQuery not to process the data
				contentType: false,  // tell jQuery not to set contentType
				success : function(data) {
					div_imagem.html(data);
				}
			});

		});

		dragula([getById('drag-elements'), getById('drop-target')], {
			revertOnSpill: true,
			// removeOnSpill: true,
			copy: function (el, source) {
			    return source === getById('drag-elements');
			},
			accepts: function (el, target, handle, sibling) {
        		
        		if(sibling){
					id_parente = sibling.id;
				}else{
					id_parente = "";
				}
				
				if(id_parente != "trigger"){
					return target !== getById('drag-elements');
				}
			},
			moves: function (el, source, target, handle, sibling){
				// alert(el.id);
        		if (el.id == "trigger") {
            		return false;
            		console.log("false");
        		}else{
        			return true;
        			console.log("true");
        		}
		    }
		}).on('drop', function(el, source, target) {

			// index = ([].slice.call(el.parentNode.childNodes).findIndex((item) => el === item)-1); // - posição do elemento na caixa
			id_elemento = el.id;

			if(jQuery.isNumeric(id_elemento)){
				el.id = "MOVED_"+id_elemento;
			}

			if(source.id != target.id){

				jQuery.ajax({
					method: 'POST',
					url: 'ajxEditDocumento.do',
					data: {COD_BLTEMPL: id_elemento, COD_EMPRESA: "<?=fnEncode($cod_empresa)?>", COD_DOCUMENTO: "<?=fnEncode($cod_documento)?>"},
					beforeSend:function(){
						$(el.id).innerHTML = '<div class="loading" style="width: 100%;"></div>';
					},
					success:function(data){
						Ids = "";	
						jQuery("#"+el.id).replaceWith(data);
					},
					error:function(){
						$(el.id).innerHTML = '<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Ocorreu um erro...</p>';
					}
				});

				parent.jQuery("#conteudoAba").css("height", jQuery(".portlet").height() + "px");

			}else{

				var Ids = "";
				jQuery('#drop-target .movable').each(function( index ) {
					Ids += jQuery(this).attr('id').substring(6) + ",";
				});

				var arrayOrdem = Ids.substring(0,(Ids.length-1));

				// console.log(arrayOrdem);

				execOrdenacao(arrayOrdem,11,"<?=$cod_empresa?>");

			}

		});

		//modal close
		// parent.jQuery('.modal').on('hidden.bs.modal', function () {
		// 	parent.mudaAba("action.php?mod=<?php echo fnEncode(1500)?>&id=<?php echo fnEncode($cod_empresa)?>&idc=<?=fnEncode($cod_campanha)?>&pop=true");
		// });

	});

	function getById(id) {
	  return document.getElementById(id);
	}

	function mudaAba(id){

		$(".abasSelecao").removeClass("active");
		$(id+"_aba").addClass("active");

		$(".aba").fadeOut("fast", function(){
			$(id).fadeIn("fast");
		});

	}

	function acaoCarregaBlocos(cod_template=0,des_template="",cod_bltempl=0,opcao="paginar",cod_documento="<?=fnEncode($cod_documento)?>"){
		$.ajax({
			method: 'POST',
			url: 'ajxEditDocumento.do?opcao='+opcao,
			data: {COD_EMPRESA: "<?=fnEncode($cod_empresa)?>", COD_DOCUMENTO: cod_documento, COD_BLTEMPL: cod_bltempl, COD_TEMPLATE: cod_template, DES_TEMPLATE: des_template},
			beforeSend:function(){
				$("#drop-target").innerHTML = '<div class="loading" style="width: 100%;"></div>';
			},
			success:function(data){	
				$("#drop-target").html(data);
			},
			error:function(){
				$("#drop-target").innerHTML = '<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Ocorreu um erro...</p>';
			}
		});
	}

	function execOrdenacao(p1,p2,p3) {
		jQuery.ajax({
			type: "GET",
			url: "ajxOrdenacaoEmp.php",
			data: { ajx1:p1,ajx2:p2,ajx3:p3},
			success:function(data){
				console.log(data); 
			},
			error:function(data){
				console.log(data); 
			}
		});		
	}

	function quickCopy(tag) {
	    var dummyContent = tag;
	    var dummy = $('<input>').val(dummyContent).appendTo('body');
	    dummy.select();
	    document.execCommand('copy');
	    dummy.remove();
	}
	
</script>	