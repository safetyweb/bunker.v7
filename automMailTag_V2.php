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
			
			$cod_tag = fnLimpaCampoZero($_REQUEST['COD_TAG']);
			$cod_campanha = fnLimpaCampoZero($_REQUEST['COD_CAMPANHA']);
			$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
			$cod_template = fnLimpaCampoZero($_REQUEST['COD_TEMPLATE']);
			$des_tags = fnLimpaCampo($_REQUEST['DES_TAGS']);
			
			
			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
			
			$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
			
                      
			if ($opcao != ''){			
				
				$des_tag = explode(',', $des_tags);
				$tags = "";

				$sql = "DELETE FROM TAGS_AUTOMACAO 
						WHERE COD_CAMPANHA = $cod_campanha 
						AND COD_EMPRESA = $cod_empresa 
						AND COD_TEMPLATE = $cod_template";

				 mysqli_query(connTemp($cod_empresa,''),$sql);

				for ($i=0; $i < count($des_tag) ; $i++) { 
					$tags .= "(
								$cod_empresa,
								$cod_campanha,
								$cod_template,
								'".trim($des_tag[$i])."',
								$cod_usucada
							),";
				}

				$tags = rtrim($tags,',');

				if($tags != ""){

					$sql = "INSERT INTO TAGS_AUTOMACAO(
									COD_EMPRESA,
									COD_CAMPANHA,
									COD_TEMPLATE,
									DES_TAG,
									COD_USUCADA
								) VALUES $tags";
				
					// fnEscreve($sql);
	                mysqli_query(connTemp($cod_empresa,''),$sql);

	            }

                //atualiza lista iframe				
				?>
				<script>
					// try { parent.$('#REFRESH_TEMPLATES').val("S"); } catch(err) {}
				</script>						
				<?php				
				
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

				?>
					<script>
						parent.mudaAba(parent.$('#conteudoAba').attr('src')+"&rnd="+Math.random());
					</script>
				<?php
				
				$msgTipo = 'alert-success';
			}                
		}
	}
	
	//busca dados da url	
	if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))){
            
		//busca dados da empresa
		$cod_empresa = fnDecode($_GET['id']);	
		$cod_campanha = fnDecode($_GET['idc']);
		$cod_template = fnDecode($_GET['idt']);

		$sql = "SELECT NOM_EMPRESA FROM EMPRESAS WHERE COD_EMPRESA = ".$cod_empresa;	
		
		//fnEscreve($sql);
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql);
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
			
		if (isset($qrBuscaEmpresa)){
			$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
		}
												
	}else {	
		$nom_empresa = "";
	}

	$sql = "SELECT * FROM TAGS_AUTOMACAO WHERE COD_TEMPLATE = $cod_template";	
	// fnEscreve($sql);
	// exit();
	$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
	$des_tags = "";

	while($qrBuscaTemplate = mysqli_fetch_assoc($arrayQuery)){
		$des_tags .= $qrBuscaTemplate["DES_TAG"].',';
	}

	$des_tags = rtrim($des_tags,',');
		
	

	// fnEscreve($cod_template);

?>
<link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="http://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<style>
	*{box-sizing: border-box;}
	html{height: 100%;margin: 0;}
	/*body{min-height: 100%;font-family: 'Roboto';margin: 0;background-color: #fafafa;}*/
	.container { margin: 150px auto; max-width: 960px;}
	label{display: block;padding: 20px 0 5px 0;}
	.tagsinput,.tagsinput *{box-sizing:border-box}
	.tagsinput{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-flex-wrap:wrap;-ms-flex-wrap:wrap;flex-wrap:wrap;background:#fff;font-family:sans-serif;font-size:14px;line-height:20px;color:#556270;padding:5px 5px 0;border:1px solid #e6e6e6;border-radius:2px}
	.tagsinput.focus{border-color:#ccc}
	.tagsinput .tag{position:relative;background:#2C3E50;display:block;max-width:100%;word-wrap:break-word;color:#fff;padding:5px 30px 5px 5px;border-radius:2px;margin:0 5px 5px 0}
	.tagsinput .tag .tag-remove{position:absolute;background:0 0;display:block;width:30px;height:30px;top:0;right:0;cursor:pointer;text-decoration:none;text-align:center;color:#ff6b6b;line-height:30px;padding:0;border:0}
	.tagsinput .tag .tag-remove:after,.tagsinput .tag .tag-remove:before{background:#ff6b6b;position:absolute;display:block;width:10px;height:2px;top:14px;left:10px;content:''}
	.tagsinput .tag .tag-remove:before{-webkit-transform:rotateZ(45deg);transform:rotateZ(45deg)}
	.tagsinput .tag .tag-remove:after{-webkit-transform:rotateZ(-45deg);transform:rotateZ(-45deg)}
	.tagsinput div{-webkit-box-flex:1;-webkit-flex-grow:1;-ms-flex-positive:1;flex-grow:1}
	.tagsinput div input{background:0 0;display:block;width:100%;font-size:14px;line-height:20px;padding:5px;border:0;margin:0 5px 5px 0}
	.tagsinput div input.error{color:#ff6b6b}
	.tagsinput div input::-ms-clear{display:none}
	.tagsinput div input::-webkit-input-placeholder{color:#ccc;opacity:1}
	.tagsinput div input:-moz-placeholder{color:#ccc;opacity:1}
	.tagsinput div input::-moz-placeholder{color:#ccc;opacity:1}
	.tagsinput div input:-ms-input-placeholder{color:#ccc;opacity:1}
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
																 

							<div class="row">   
					
								<div class="col-md-12">
									<div class="form-group">
										<label for="inputName" class="control-label">Tags</label>
										<input type="text" class="form-control input-sm" id="form-tags-4" name="DES_TAGS" id="DES_TAGS" value="<?=$des_tags?>">
									</div>
									<!-- <div class="help-block with-errors">Separar tags por vírgulas ","</div> -->
								</div>       
									
						
							</div>
							
							<div class="push10"></div>						
																
						<div class="push10"></div>
						<hr>	
						<div class="form-group text-right col-lg-12">
							
							  <!--<button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>-->
							  <?php
								if($cod_tag == 0){
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
						
						<input type="hidden" name="COD_TAG" id="COD_TAG" value="<?=$cod_tag?>">
						<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?=$cod_empresa?>">
						<input type="hidden" name="COD_TEMPLATE" id="COD_TEMPLATE" value="<?=$cod_template?>">
						<input type="hidden" name="COD_CAMPANHA" id="COD_CAMPANHA" value="<?=$cod_campanha?>">
						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">		
						
						</form>
					
					</div>								
				
				</div>
			</div>
			<!-- fim Portlet -->
		</div>
		
	</div>					
		
	<div class="push20"></div>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	
	<script type="text/javascript">
	
		$(function() {
			$('#form-tags-1').tagsInput();

			$('#form-tags-2').tagsInput({
				'onAddTag': function(input, value) {
					console.log('tag added', input, value);
				},
				'onRemoveTag': function(input, value) {
					console.log('tag removed', input, value);
				},
				'onChange': function(input, value) {
					console.log('change triggered', input, value);
				}
			});

			$('#form-tags-3').tagsInput({
				'unique': true,
				'minChars': 2,
				'maxChars': 10,
				'limit': 5,
				'validationPattern': new RegExp('^[a-zA-Z]+$')
			});

			$('#form-tags-4').tagsInput({
				'autocomplete': {
					source: [
						'apple',
						'banana',
						'orange',
						'pizza'
					]
				}
			});

			$('#form-tags-5').tagsInput({
				'delimiter': ';'
			});

			$('#form-tags-6').tagsInput({
				'delimiter': [',', ';']
			});
		});



		/* jQuery Tags Input Revisited Plugin
		 *
		 * Copyright (c) Krzysztof Rusnarczyk
		 * Licensed under the MIT license */

		(function($) {
			var delimiter = [];
			var inputSettings = [];
			var callbacks = [];

			$.fn.addTag = function(value, options) {
				options = jQuery.extend({
					focus: false,
					callback: true
				}, options);
				
				this.each(function() {
					var id = $(this).attr('id');

					var tagslist = $(this).val().split(_getDelimiter(delimiter[id]));
					if (tagslist[0] === '') tagslist = [];

					value = jQuery.trim(value);
					
					if ((inputSettings[id].unique && $(this).tagExist(value)) || !_validateTag(value, inputSettings[id], tagslist, delimiter[id])) {
						$('#' + id + '_tag').addClass('error');
						return false;
					}
					
					$('<span>', {class: 'tag'}).append(
						$('<span>', {class: 'tag-text'}).text(value),
						$('<button>', {class: 'tag-remove'}).click(function() {
							return $('#' + id).removeTag(encodeURI(value));
						})
					).insertBefore('#' + id + '_addTag');

					tagslist.push(value);

					$('#' + id + '_tag').val('');
					if (options.focus) {
						$('#' + id + '_tag').focus();
					} else {
						$('#' + id + '_tag').blur();
					}

					$.fn.tagsInput.updateTagsField(this, tagslist);

					if (options.callback && callbacks[id] && callbacks[id]['onAddTag']) {
						var f = callbacks[id]['onAddTag'];
						f.call(this, this, value);
					}
					
					if (callbacks[id] && callbacks[id]['onChange']) {
						var i = tagslist.length;
						var f = callbacks[id]['onChange'];
						f.call(this, this, value);
					}
				});

				return false;
			};

			$.fn.removeTag = function(value) {
				value = decodeURI(value);
				
				this.each(function() {
					var id = $(this).attr('id');

					var old = $(this).val().split(_getDelimiter(delimiter[id]));

					$('#' + id + '_tagsinput .tag').remove();
					
					var str = '';
					for (i = 0; i < old.length; ++i) {
						if (old[i] != value) {
							str = str + _getDelimiter(delimiter[id]) + old[i];
						}
					}

					$.fn.tagsInput.importTags(this, str);

					if (callbacks[id] && callbacks[id]['onRemoveTag']) {
						var f = callbacks[id]['onRemoveTag'];
						f.call(this, this, value);
					}
				});

				return false;
			};

			$.fn.tagExist = function(val) {
				var id = $(this).attr('id');
				var tagslist = $(this).val().split(_getDelimiter(delimiter[id]));
				return (jQuery.inArray(val, tagslist) >= 0);
			};

			$.fn.importTags = function(str) {
				var id = $(this).attr('id');
				$('#' + id + '_tagsinput .tag').remove();
				$.fn.tagsInput.importTags(this, str);
			};

			$.fn.tagsInput = function(options) {
				var settings = jQuery.extend({
					interactive: true,
					placeholder: 'Add a tag',
					minChars: 0,
					maxChars: null,
					limit: null,
					validationPattern: null,
					width: 'auto',
					height: 'auto',
					autocomplete: null,
					hide: true,
					delimiter: ',',
					unique: true,
					removeWithBackspace: true
				}, options);

				var uniqueIdCounter = 0;

				this.each(function() {
					if (typeof $(this).data('tagsinput-init') !== 'undefined') return;

					$(this).data('tagsinput-init', true);

					if (settings.hide) $(this).hide();
					
					var id = $(this).attr('id');
					if (!id || _getDelimiter(delimiter[$(this).attr('id')])) {
						id = $(this).attr('id', 'tags' + new Date().getTime() + (++uniqueIdCounter)).attr('id');
					}

					var data = jQuery.extend({
						pid: id,
						real_input: '#' + id,
						holder: '#' + id + '_tagsinput',
						input_wrapper: '#' + id + '_addTag',
						fake_input: '#' + id + '_tag'
					}, settings);

					delimiter[id] = data.delimiter;
					inputSettings[id] = {
						minChars: settings.minChars,
						maxChars: settings.maxChars,
						limit: settings.limit,
						validationPattern: settings.validationPattern,
						unique: settings.unique
					};

					if (settings.onAddTag || settings.onRemoveTag || settings.onChange) {
						callbacks[id] = [];
						callbacks[id]['onAddTag'] = settings.onAddTag;
						callbacks[id]['onRemoveTag'] = settings.onRemoveTag;
						callbacks[id]['onChange'] = settings.onChange;
					}

					var markup = $('<div>', {id: id + '_tagsinput', class: 'tagsinput'}).append(
						$('<div>', {id: id + '_addTag'}).append(
							settings.interactive ? $('<input>', {id: id + '_tag', class: 'tag-input', value: '', placeholder: settings.placeholder}) : null
						)
					);

					$(markup).insertAfter(this);

					$(data.holder).css('width', settings.width);
					$(data.holder).css('min-height', settings.height);
					$(data.holder).css('height', settings.height);

					if ($(data.real_input).val() !== '') {
						$.fn.tagsInput.importTags($(data.real_input), $(data.real_input).val());
					}
					
					// Stop here if interactive option is not chosen
					if (!settings.interactive) return;
					
					$(data.fake_input).val('');
					$(data.fake_input).data('pasted', false);
					
					$(data.fake_input).on('focus', data, function(event) {
						$(data.holder).addClass('focus');
						
						if ($(this).val() === '') {
							$(this).removeClass('error');
						}
					});
					
					$(data.fake_input).on('blur', data, function(event) {
						$(data.holder).removeClass('focus');
					});

					if (settings.autocomplete !== null && jQuery.ui.autocomplete !== undefined) {
						$(data.fake_input).autocomplete(settings.autocomplete);
						$(data.fake_input).on('autocompleteselect', data, function(event, ui) {
							$(event.data.real_input).addTag(ui.item.value, {
								focus: true,
								unique: settings.unique
							});
							
							return false;
						});
						
						$(data.fake_input).on('keypress', data, function(event) {
							if (_checkDelimiter(event)) {
								$(this).autocomplete("close");
							}
						});
					} else {
						$(data.fake_input).on('blur', data, function(event) {
							$(event.data.real_input).addTag($(event.data.fake_input).val(), {
								focus: true,
								unique: settings.unique
							});
							
							return false;
						});
					}
					
					// If a user types a delimiter create a new tag
					$(data.fake_input).on('keypress', data, function(event) {
						if (_checkDelimiter(event)) {
							event.preventDefault();
							
							$(event.data.real_input).addTag($(event.data.fake_input).val(), {
								focus: true,
								unique: settings.unique
							});
							
							return false;
						}
					});
					
					$(data.fake_input).on('paste', function () {
						$(this).data('pasted', true);
					});
					
					// If a user pastes the text check if it shouldn't be splitted into tags
					$(data.fake_input).on('input', data, function(event) {
						if (!$(this).data('pasted')) return;
						
						$(this).data('pasted', false);
						
						var value = $(event.data.fake_input).val();
						
						value = value.replace(/\n/g, '');
						value = value.replace(/\s/g, '');
						
						var tags = _splitIntoTags(event.data.delimiter, value);
						
						if (tags.length > 1) {
							for (var i = 0; i < tags.length; ++i) {
								$(event.data.real_input).addTag(tags[i], {
									focus: true,
									unique: settings.unique
								});
							}
							
							return false;
						}
					});
					
					// Deletes last tag on backspace
					data.removeWithBackspace && $(data.fake_input).on('keydown', function(event) {
						if (event.keyCode == 8 && $(this).val() === '') {
							 event.preventDefault();
							 var lastTag = $(this).closest('.tagsinput').find('.tag:last > span').text();
							 var id = $(this).attr('id').replace(/_tag$/, '');
							 $('#' + id).removeTag(encodeURI(lastTag));
							 $(this).trigger('focus');
						}
					});

					// Removes the error class when user changes the value of the fake input
					$(data.fake_input).keydown(function(event) {
						// enter, alt, shift, esc, ctrl and arrows keys are ignored
						if (jQuery.inArray(event.keyCode, [13, 37, 38, 39, 40, 27, 16, 17, 18, 225]) === -1) {
							$(this).removeClass('error');
						}
					});
				});

				return this;
			};
			
			$.fn.tagsInput.updateTagsField = function(obj, tagslist) {
				var id = $(obj).attr('id');
				$(obj).val(tagslist.join(_getDelimiter(delimiter[id])));
			};

			$.fn.tagsInput.importTags = function(obj, val) {
				$(obj).val('');
				
				var id = $(obj).attr('id');
				var tags = _splitIntoTags(delimiter[id], val); 
				
				for (i = 0; i < tags.length; ++i) {
					$(obj).addTag(tags[i], {
						focus: false,
						callback: false
					});
				}
				
				if (callbacks[id] && callbacks[id]['onChange']) {
					var f = callbacks[id]['onChange'];
					f.call(obj, obj, tags);
				}
			};
			
			var _getDelimiter = function(delimiter) {
				if (typeof delimiter === 'undefined') {
					return delimiter;
				} else if (typeof delimiter === 'string') {
					return delimiter;
				} else {
					return delimiter[0];
				}
			};
			
			var _validateTag = function(value, inputSettings, tagslist, delimiter) {
				var result = true;
				
				if (value === '') result = false;
				if (value.length < inputSettings.minChars) result = false;
				if (inputSettings.maxChars !== null && value.length > inputSettings.maxChars) result = false;
				if (inputSettings.limit !== null && tagslist.length >= inputSettings.limit) result = false;
				if (inputSettings.validationPattern !== null && !inputSettings.validationPattern.test(value)) result = false;
				
				if (typeof delimiter === 'string') {
					if (value.indexOf(delimiter) > -1) result = false;
				} else {
					$.each(delimiter, function(index, _delimiter) {
						if (value.indexOf(_delimiter) > -1) result = false;
						return false;
					});
				}
				
				return result;
			};
		 
			var _checkDelimiter = function(event) {
				var found = false;
				
				if (event.which === 13) {
					return true;
				}

				if (typeof event.data.delimiter === 'string') {
					if (event.which === event.data.delimiter.charCodeAt(0)) {
						found = true;
					}
				} else {
					$.each(event.data.delimiter, function(index, delimiter) {
						if (event.which === delimiter.charCodeAt(0)) {
							found = true;
						}
					});
				}
				
				return found;
			 };
			 
			 var _splitIntoTags = function(delimiter, value) {
				 if (value === '') return [];
				 
				 if (typeof delimiter === 'string') {
					 return value.split(delimiter);
				 } else {
					 var tmpDelimiter = '∞';
					 var text = value;
					 
					 $.each(delimiter, function(index, _delimiter) {
						 text = text.split(_delimiter).join(tmpDelimiter);
					 });
					 
					 return text.split(tmpDelimiter);
				 }
				 
				 return [];
			 };
		})(jQuery);

		
	</script>	