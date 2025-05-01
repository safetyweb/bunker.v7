<?php

	//echo fnDebug('true');

	$tipoUsuario = @$_GET["tipoUsuario"];
	$cod_empresa = fnDecode($_GET['id']);
	$hashLocal = mt_rand();	
	
	// definir o numero de itens por pagina
	$itens_por_pagina = 50;	
	$pagina  = "1";	
	
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
			$usuarios = implode(",",$_POST["COD_USUARIO"]);
			$univend = implode(",",$_POST["COD_UNIVEND"]);

			$sql = "SELECT USUARIOS.* FROM USUARIOS 
			WHERE USUARIOS.COD_EMPRESA = $cod_empresa 
			AND LOG_ESTATUS='S'
			AND USUARIOS.COD_TPUSUARIO IN ($tipoUsuario)
			AND COD_USUARIO IN (0$usuarios)";

			$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
			while ($qrListaUsuario = mysqli_fetch_assoc($arrayQuery)){
				$u = ($univend == ""?"0":$univend).",".($qrListaUsuario["COD_UNIVEND"] == ""?"0":$qrListaUsuario["COD_UNIVEND"]).",0";
				$arru = explode(",",$u);
				$remove = array(0);
				$arru = array_diff($arru, $remove); 
				$arru = array_unique($arru);
				$u = implode(",",$arru);

				$sql = "UPDATE USUARIOS SET
							COD_UNIVEND = '".$u."'
						WHERE USUARIOS.COD_EMPRESA = $cod_empresa 
							AND LOG_ESTATUS='S'
							AND USUARIOS.COD_TPUSUARIO IN ($tipoUsuario)
							AND COD_USUARIO = 0".$qrListaUsuario["COD_USUARIO"];
				mysqli_query($connAdm->connAdm(),trim($sql)) or die($sql);
			}
			$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";		
			$msgTipo = 'alert-success';
				
		}
	}


		
		//busca dados da url	
		if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))){
			//busca dados da empresa
			$cod_empresa = fnDecode($_GET['id']);	
			$sql = "SELECT COD_EMPRESA, NOM_FANTASI, DES_SUFIXO FROM empresas where COD_EMPRESA = '".$cod_empresa."' ";
				 
			//fnEscreve($sql);
			$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
			$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
			
			if (isset($qrBuscaEmpresa)){
				$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
				$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
				$des_sufixo = $qrBuscaEmpresa['DES_SUFIXO'];
			}
													
		}else {
			$cod_empresa = 0;		
			//fnEscreve('entrou else');
		}

		if($val_pesquisa != ""){
			$esconde = " ";
		}else{
			$esconde = "display: none;";
		}

		if($log_inativos == "S"){
			$checkInativos = "checked";
			$andInativos = "AND USUARIOS.DAT_EXCLUSA IS NOT NULL";
		}else{
			$checkInativos = "";
			$andInativos = "AND USUARIOS.DAT_EXCLUSA IS NULL";
		}

		$mod = fnLimpaCampo(fnDecode($_GET['mod']));

		// fnEscreve($mod);

		if ($mod == 1017){
			$obriga_email = "required";
		}else{
			$obriga_email = "";
		}

		if($_SESSION["SYS_COD_SISTEMA"] == 136){
			$labelIndicador = "Indicador Associado";
		}else{
			$labelIndicador = "Cliente Associado";
		}  
		
		//fnMostraForm();
		//fnEscreve($filtro);
		//fnEscreve($val_pesquisa);
			
?>
		
					<div class="push30"></div> 
					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
						<div class="row">	

							<div class="portlet portlet-bordered">
								<div class="portlet-body">
			
									<?php if ($msgRetorno <> '') { ?>	
									<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
									<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									 <?php echo $msgRetorno; ?>
									</div>
									<?php } ?>	
									<div class="col-md-12">
										<div class="form-group">
											<label for="inputName" class="control-label">Unidades de Venda</label>
											
												<select data-placeholder="Selecione uma unidade para acesso" name="COD_UNIVEND[]" id="COD_UNIVEND" multiple="multiple" class="chosen-select-deselect" style="width:100%;" tabindex="1">
													<?php
													$sql = "SELECT COD_UNIVEND, NOM_FANTASI, LOG_ESTATUS FROM UNIDADEVENDA WHERE COD_EMPRESA = $cod_empresa AND LOG_ESTATUS = 'S' AND DAT_EXCLUSA IS NULL ORDER BY NOM_FANTASI ";
													$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());																
													while ($qrListaUnive = mysqli_fetch_assoc($arrayQuery))
													  {

														if($qrListaUnive['LOG_ESTATUS'] == 'N'){ $disabled = "disabled"; }else{ $disabled = " "; }

														echo"
															  <option value='".$qrListaUnive['COD_UNIVEND']."'".$disabled.">".ucfirst($qrListaUnive['NOM_FANTASI']). "</option> 
															"; 
														  }	
													?>								
												</select>
												<?php //fnEscreve($sql); ?>		
											<div class="help-block with-errors"></div>
																										
											<a class="btn btn-default btn-sm" id="iAll" style="padding: 0 2px ; font-size: 10px;"><i class="fa fa-check-square" aria-hidden="true"></i> selecionar todos</a>&nbsp;
											<a class="btn btn-default btn-sm" id="iNone" style="padding: 0 2px ; font-size: 10px;"><i class="fa fa-square-o" aria-hidden="true"></i>&nbsp; deselecionar todos</a>															
											
										</div>
									</div>
								</div>
								<div class="push"></div>
							</div>
							
						</div>	

						<div class="row">				
						
							<div class="col-md12 margin-bottom-30">
								<!-- Portlet -->
								<div class="portlet">
			
									<div class="portlet-body	">
							
										<div class="login-form">

											<div class="row">
												<form name="formLista2" id="formLista2" method="post" action="<?php echo $cmdPage; ?>">


													<div class="col-xs-4"></div>

													<div class="col-xs-4">
														<div class="input-group activeItem">
															<div class="input-group-btn search-panel">
																<button type="button" class="btn btn-outline dropdown-toggle form-control form-control-sm rounded-left search-bar" id="FILTERS" data-toggle="dropdown">
																	<span id="search_concept">Sem filtro</span>&nbsp;
																	<span class="far fa-angle-down"></span>										                    	
																</button>
															</div>
															<input type="hidden" name="VAL_PESQUISA" value="<?=$filtro?>" id="VAL_PESQUISA">         
															<input type="text" id="INPUT" class="form-control form-control-sm remove-side-borders search-bar" name="INPUT" value="<?=$val_pesquisa?>" onkeyup="buscaRegistro(this)">
															<div class="input-group-btn"id="CLEARDIV" style="<?=$esconde?>">
																<button class="btn btn-outline form-control form-control-sm remove-side-borders search-bar" id="CLEAR" type="button">&nbsp;<span class="fal fa-times"></span></button>
															</div>
															<div class="input-group-btn">
																<button type="submit" class="btn btn-outline form-control form-control-sm rounded-right search-bar" id="SEARCH">&nbsp;<span class="fal fa-search"></span></button>
															</div>
														</div>
													</div>
														
													<!-- <input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />							
													<input type="hidden" name="hHabilitado" id="hHabilitado" value="S"> -->

												
											</div>
												
											<div class="push30"></div>

											<div class="col-lg-12">

												<div class="no-more-tables">
											
													
														<div style="column-width:400px;">
															<table class="table table-bordered table-striped table-hover tablesorter buscavel">
																<tbody id="relatorioConteudo">	
															
															<?php

															
																$numPaginas = ceil($total_itens_por_pagina['CONTADOR']/$itens_por_pagina);	

																//variavel para calcular o início da visualização com base na página atual
																$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;													
															
																$sql = "SELECT USUARIOS.* FROM USUARIOS 
																WHERE USUARIOS.COD_EMPRESA = $cod_empresa 
																AND LOG_ESTATUS='S'
																AND USUARIOS.COD_TPUSUARIO IN ($tipoUsuario)
																ORDER BY USUARIOS.NOM_USUARIO";

																//fnConsole($sql);
																//--and log_usuario like '%arcio.fabian.mcoisas%'
																
																//fnEscreve($sql);
																$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
																
																$count=0;

																while ($qrListaUsuario = mysqli_fetch_assoc($arrayQuery)){

																	$count++;
																	$arr = explode(",",$qrListaUsuario['COD_UNIVEND']);
																	$qtd_univend = count($arr);
																	
																	echo "<tr>";

																	echo "
																		  <td><input type='checkbox' name='COD_USUARIO[ ]' value='".$qrListaUsuario['COD_USUARIO']."'> <span class='f10'>".$qrListaUsuario['COD_USUARIO']."</span> ".$qrListaUsuario['NOM_USUARIO']." <span class='f10'>($qtd_univend)</span></td>
																		";
																		
																	echo "</tr>";

																	}											
																
															?>
																
															</tbody>
															
															
															</table>
														</div>
													

												</div>
												
											</div>										
										
										</div>								
									
									</div>
								</div>
								<!-- fim Portlet -->
							</div>
							
						</div>	
						
						<div class="push"></div>

						<div class="form-group text-right col-lg-12">
							<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Alterar</button>
						</div>
					</form>					


					<div class="push20"></div>

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
					
	
	<script type="text/javascript">

		//Barra de pesquisa essentials ------------------------------------------------------
		$(document).ready(function(e){
			var value = $('#INPUT').val().toLowerCase().trim();
		    if(value){
		    	$('#CLEARDIV').show();
		    }else{
		    	$('#CLEARDIV').hide();
		    }
		    $('.search-panel .dropdown-menu').find('a').click(function(e) {
				e.preventDefault();
				var param = $(this).attr("href").replace("#","");
				var concept = $(this).text();
				$('.search-panel span#search_concept').text(concept);
				$('.input-group #VAL_PESQUISA').val(param);
				$('#INPUT').focus();
			});

		    $("#FILTERS, #INPUT, #SEARCH, #CLEAR").focus(function(){
			    $("#FILTERS, #INPUT, #SEARCH, #CLEAR").addClass("activeItem");
		    });

		    $("#FILTERS, #INPUT, #SEARCH, #CLEAR").blur(function(){
		    	$("#FILTERS, #INPUT, #SEARCH, #CLEAR").removeClass("activeItem");
		    });

		    $('#CLEAR').click(function(){
		    	$('#INPUT').val('');
		    	$('#INPUT').focus();
		    	$('#CLEARDIV').hide();
		    	if("<?=$filtro?>" != ""){
		    		location.reload();
		    	}else{
		    		var value = $('#INPUT').val().toLowerCase().trim();
				    if(value){
				    	$('#CLEARDIV').show();
				    }else{
				    	$('#CLEARDIV').hide();
				    }
				    $(".buscavel tr").each(function (index) {
				        if (!index) return;
				        $(this).find("td").each(function () {
				            var id = $(this).text().toLowerCase().trim();
				            var sem_registro = (id.indexOf(value) == -1);
				            $(this).closest('tr').toggle(!sem_registro);
				            return sem_registro;
				        });
				    });
		    	}
		    });

		    // $('#SEARCH').click(function(){
		    // 	$('#formulario').submit();
		    // });
		    	
		    
		});

		function buscaRegistro(el){
			var filtro = $('#search_concept').text().toLowerCase();

			if(filtro == "sem filtro"){
			    var value = $(el).val().toLowerCase().trim();
			    if(value){
			    	$('#CLEARDIV').show();
			    }else{
			    	$('#CLEARDIV').hide();
			    }
			    $(".buscavel tr").each(function (index) {
			        if (!index) return;
			        $(this).find("td").each(function () {
			            var id = $(this).text().toLowerCase().trim();
			            var sem_registro = (id.indexOf(value) == -1);
			            $(this).closest('tr').toggle(!sem_registro);
			            return sem_registro;
			        });
			    });
			}
		}

	//-----------------------------------------------------------------------------------
	
		$(document).ready(function(){

			//modal close
			$('.modal').on('hidden.bs.modal', function () {
				if($("#REFRESH_USU").val() == 'S'){
					window.location.replace("<?=$cmdPage?>");
				}
				$(".alert-clie").html("");
			});

			$(".addBox").click(function(e){
				if($(this).attr("disabled")){
					e.stopPropagation();
				}
			});

			var SPMaskBehavior = function (val) {
			  return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
			},
			spOptions = {
			  onKeyPress: function(val, e, field, options) {
				  field.mask(SPMaskBehavior.apply({}, arguments), options);
				}
			};			
			
			$('.sp_celphones').mask(SPMaskBehavior, spOptions);
			
			//chosen
			$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
			$('#formulario').validator();
			
			// mascaraCpfCnpj($("#formulario #NUM_CGCECPF"));
			
			var numPaginas = <?php echo $numPaginas; ?>;
			if(numPaginas != 0){
				carregarPaginacao(numPaginas);
			}	

			$('#iAll').on('click', function(e){
			  e.preventDefault();
			  $('#COD_UNIVEND option').prop('selected', true).trigger('chosen:updated');
			});

			$('#iNone').on('click', function(e){
			  e.preventDefault();
			  $("#COD_UNIVEND option:selected").removeAttr("selected").trigger('chosen:updated');
			});
			
		});	
		
		function reloadPage(idPage) {
			$.ajax({
				type: "POST",
				url: "ajxUsuarios.do?opcao=paginar&mod=<?php echo $_GET['mod']; ?>&id=<?php echo fnEncode($cod_empresa); ?>&idPage="+idPage+"&itens_por_pagina=<?php echo $itens_por_pagina; ?>&tpUsu=<?php echo $tipoUsuario; ?>&des_sufixo=<?php echo $des_sufixo; ?>",
				data: $('#formLista2').serialize(),
				beforeSend:function(){
					$('#relatorioConteudo').html('<div class="loading" style="width: 100%;"></div>');
				},
				success:function(data){
					$("#relatorioConteudo").html(data);		
					//console.log(data);					
				},
				error:function(){
					$('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
				}
			});		
		}		
				
		function retornaForm(index){
			$("#formulario #COD_USUARIO").val($("#ret_COD_USUARIO_"+index).val());
			$("#formulario #COD_EMPRESA").val($("#ret_COD_EMPRESA_"+index).val());
			$("#formulario #DAT_CADASTR").val($("#ret_DAT_CADASTR_"+index).val());
			$("#formulario #NOM_USUARIO").val($("#ret_NOM_USUARIO_"+index).val());
			$("#btnSenha").attr("data-url","action.php?mod=<?=fnEncode(1516)?>&id=<?=fnEncode($cod_empresa)?>&idu="+$("#ret_COD_USUARIO_ENC_"+index).val()+"&pop=true").removeAttr('disabled');
			$("#formulario #LOG_USUARIO").val($("#ret_LOG_USUARIO_"+index).val());
			if ($("#ret_LOG_ESTATUS_"+index).val() == 'S'){$('#formulario #LOG_ESTATUS').prop('checked', true);} 
			else {$('#formulario #LOG_ESTATUS').prop('checked', false);}
			<?php
			if ($_SESSION["SYS_COD_MASTER"] != "2" && $_SESSION["SYS_COD_MASTER"] != "3") {
			?>

				$('#formulario #LOG_USUDEV').val($("#ret_LOG_USUDEV_"+index).val());

			<?php 
			}else{
			?>

				if ($("#ret_LOG_USUDEV_"+index).val() == 'S'){$('#formulario #LOG_USUDEV').prop('checked', true);} 
				else {$('#formulario #LOG_USUDEV').prop('checked', false);}

			<?php
			} 
			?>

			$("#formulario #DES_EMAILUS").val($("#ret_DES_EMAILUS_"+index).val());
			$("#formulario #HOR_DEVDIAS").val($("#ret_HOR_DEVDIAS_"+index).val());
			$("#formulario #HOR_DEVFDS").val($("#ret_HOR_DEVFDS_"+index).val());
			$("#formulario #HOR_ENTRADA").val($("#ret_HOR_ENTRADA_"+index).val());
			$("#formulario #NUM_CGCECPF").val($("#ret_NUM_CGCECPF_"+index).val());				
			$("#formulario #NUM_RGPESSO").val($("#ret_NUM_RGPESSO_"+index).val());				
			$("#formulario #DAT_NASCIME").val($("#ret_DAT_NASCIME_"+index).val());
			$("#formulario #NUM_TENTATI").val($("#ret_NUM_TENTATI_"+index).val());
			$("#formulario #NUM_TELEFON").val($("#ret_NUM_TELEFON_"+index).val());
			$("#formulario #NUM_CELULAR").val($("#ret_NUM_CELULAR_"+index).val());
			$("#formulario #COD_EXTERNO").val($("#ret_COD_EXTERNO_"+index).val());			
			$("#formulario #COD_INDICA").val($("#ret_COD_INDICA_"+index).val());
			$("#formulario #COD_INDICA_ENC").val($("#ret_COD_INDICA_ENC_"+index).val());
			$("#formulario #NOM_INDICA").val($("#ret_NOM_INDICA_"+index).val());			
			$("#formulario #COD_PERFILCOM").val($("#ret_COD_PERFILCOM_"+index).val()).trigger("chosen:updated");
			$("#formulario #COD_ESTACIV").val($("#ret_COD_ESTACIV_"+index).val()).trigger("chosen:updated");
			$("#formulario #COD_SEXOPES").val($("#ret_COD_SEXOPES_"+index).val()).trigger("chosen:updated");
			$("#formulario #COD_TPUSUARIO").val($("#ret_COD_TPUSUARIO_"+index).val()).trigger("chosen:updated");
			$("#formulario #COD_DEFSIST").val($("#ret_COD_DEFSIST_"+index).val()).trigger("chosen:updated");


			var usuarios_age = $('#ret_COD_USUARIOS_AGE_'+index).val();
			if(usuarios_age != 0 && usuarios_age != ""){
				//retorno combo multiplo - USUARIOS_AGE
			$("#formulario #COD_USUARIOS_AGE").val('').trigger("chosen:updated");

				var sistemasUni = usuarios_age;				
				var sistemasUniArr = sistemasUni.split(',');				
				//opções multiplas
				for (var i = 0; i < sistemasUniArr.length; i++) {
				  $("#formulario #COD_USUARIOS_AGE option[value=" + sistemasUniArr[i] + "]").prop("selected", "true");				  
				}
				$("#formulario #COD_USUARIOS_AGE").trigger("chosen:updated");
			}
			
			//retorno combo multiplo - perfil
			$("#formulario #COD_PERFILS").val('').trigger("chosen:updated");
			if ($("#ret_TEM_PERFIL_"+index).val() == "sim" ){
				
				var sistemasCli = $("#ret_COD_PERFILS_"+index).val();				
				var sistemasCliArr = sistemasCli.split(',');				
				//opções multiplas
				for (var i = 0; i < sistemasCliArr.length; i++) {
				  $("#formulario #COD_PERFILS option[value=" + sistemasCliArr[i] + "]").prop("selected", "true");				  
				}
				$("#formulario #COD_PERFILS").trigger("chosen:updated");    
			} else {$("#formulario #COD_PERFILS").val('').trigger("chosen:updated");}
			
			//retorno combo multiplo - lojas
			$("#formulario #COD_UNIVEND").val('').trigger("chosen:updated");			
			if ($("#ret_TEM_UNIVE_"+index).val() == "sim" ){
				var sistemasUni = $("#ret_COD_UNIVEND_"+index).val();				
				var sistemasUniArr = sistemasUni.split(',');				
				//opções multiplas
				for (var i = 0; i < sistemasUniArr.length; i++) {
				  $("#formulario #COD_UNIVEND option[value=" + sistemasUniArr[i] + "]").prop("selected", "true");				  
				}
				$("#formulario #COD_UNIVEND").trigger("chosen:updated");    
			} else {$("#formulario #COD_UNIVEND").val('').trigger("chosen:updated");}
			
			<?php 
			//se sistema de cliente, não mostra combo
			if ($_SESSION["SYS_LOG_MULTEMPRESA"] == "S"){
			?>	
				//retorno combo multiplo - master
				$("#formulario #COD_MULTEMP").val('').trigger("chosen:updated");
				if ($("#ret_TEM_MASTER_"+index).val() == "sim" ){
					//alert("entrou...");
					var sistemasMst = $("#ret_COD_MULTEMP_"+index).val();				
					var sistemasMstArr = sistemasMst.split(',');				
					//opções multiplas
					for (var i = 0; i < sistemasMstArr.length; i++) {
					  $("#formulario #COD_MULTEMP option[value=" + sistemasMstArr[i] + "]").prop("selected", "true");				  
					}
					$("#formulario #COD_MULTEMP").trigger("chosen:updated");    
				} else {$("#formulario #COD_MULTEMP").val('').trigger("chosen:updated");}
			<?php 
			}else {
			?>
			$("#formulario #COD_MULTEMP").val($("#ret_COD_MULTEMP_"+index).val());			
			<?php 
			}
			?>
			
			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');						
		}	
				
	function acessaTelaCliente(){
		$(".alert-clie").html("");
		if ($("#COD_INDICA").val() == "" || $("#COD_INDICA").val() == "0"){
			$(".alert-clie").html("<span class='text-danger'>Escolha um cliente!</span>");
			return false;
		}
		var idC = $("#COD_INDICA_ENC").val();
		window.open('http://adm.bunker.mk/action.do?mod=<?=fnEncode(1024)?>&id=<?=$_GET["id"]?>&idC='+idC, '_blank');
	}
	</script>	