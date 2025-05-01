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

			$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
			$cod_registr = fnLimpaCampo($_REQUEST['COD_REGISTR']);
			$tip_bloco = fnLimpaCampo($_REQUEST['TIP_BLOCO']);
			$des_pergunta = fnLimpaCampo($_REQUEST['DES_PERGUNTA']);
			$num_quantid = fnLimpaCampoZero($_REQUEST['NUM_QUANTID']);
			$cod_rotulo = fnLimpaCampoZero($_REQUEST['COD_ROTULO']);
			if (empty($_REQUEST['LOG_PRINCIPAL'])) {$log_principal='N';}else{$log_principal=$_REQUEST['LOG_PRINCIPAL'];}

			// fnEscreve($des_pergunta);

			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
			$cod_usucada = $_SESSION['SYS_COD_USUARIO'];
			$num_redirect = "";
						
			if ($opcao != ''){

				$sql = "SELECT * FROM CONDICAO_PESQUISA 
						WHERE COD_EMPRESA = $cod_empresa 
						AND COD_REGISTR = $cod_registr";
				//fnEscreve($sql);
				$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);

				$sql2 = "UPDATE MODELOPESQUISA SET
						DES_PERGUNTA = '$des_pergunta',
						TIP_BLOCO = '$tip_bloco',
						NUM_QUANTID = $num_quantid,
						LOG_PRINCIPAL = '$log_principal',
						COD_ROTULO = '$cod_rotulo',
						COD_USUCALT = $cod_usucada,
						DAT_ALTERAC = NOW()
						WHERE COD_REGISTR = $cod_registr; ";

				while($qrCond = mysqli_fetch_assoc($arrayQuery)){

					$tip_condicao = fnDecode($_REQUEST["TIP_CONDICAO_$qrCond[COD_CONDICAO]"]);
					$num_resultado = fnLimpaCampo($_REQUEST["NUM_RESULTADO_$qrCond[COD_CONDICAO]"]);
					unset($num_redirect);

					$condicoes = explode(',', $qrCond['NUM_REDIRECT']);

					$qtdCond = count();

					for ($i=0; $i <= count($condicoes); $i++) {
						if($_REQUEST["NUM_REDIRECT_$qrCond[COD_CONDICAO]_$i"] != 0 && $_REQUEST["NUM_REDIRECT_$qrCond[COD_CONDICAO]_$i"] != ""){
							$num_redirect .= fnLimpaCampo($_REQUEST["NUM_REDIRECT_$qrCond[COD_CONDICAO]_$i"]).",";
						}
					}

					$redirects = ltrim(rtrim($num_redirect,','),',');


					$sql2 .= "UPDATE CONDICAO_PESQUISA SET
							 TIP_CONDICAO = '$tip_condicao',
							 NUM_RESULTADO = $num_resultado,
							 NUM_REDIRECT = '$redirects'
							 WHERE COD_CONDICAO = $qrCond[COD_CONDICAO]; ";

				}
				
				// fnEscreve($sql2);
				
				mysqli_multi_query(connTemp($cod_empresa,''),$sql2);

				?>
				<script>
					parent.refreshCondicao("<?=fnEncode($cod_registr)?>");
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
				$msgTipo = 'alert-success';
				
			}  	

		}
	}
      
	
	//busca dados da url	
	if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))){
		//busca dados da empresa
		$cod_empresa = fnDecode($_GET['id']);	
		$cod_registr = fnDecode($_GET['idr']);	
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

	$sql = "SELECT * FROM MODELOPESQUISA
			WHERE COD_REGISTR = $cod_registr";
		
	//fnEscreve($sql);
	$qrAvalia = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));

	$cod_pesquisa = $qrAvalia['COD_TEMPLATE'];

	if($qrAvalia['LOG_PRINCIPAL'] == 'S'){
		$checkPrincipal = "checked";
	}else{
		$checkPrincipal = "";
	}
	
	//fnMostraForm();

?>

<style>
	html, body {
	    max-width: 100%;
	    overflow-x: hidden;
	    overflow-y: scroll;
	    scrollbar-width: none; /* Firefox */
	    -ms-overflow-style: none;  /* IE 10+ */
	}
	body::-webkit-scrollbar { /* WebKit */
	    width: 0;
	    height: 0;
	}
	.jconfirm-box{
		max-width:350px!important; 
	}
	.chosen-container{
		width: 100%!important;
	}
</style>
					
					<div class="row">				
					
						<div class="col-md-12 margin-bottom-30">
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
									
									<div class="push30"></div> 
			
									<div class="login-form">
									
										<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">
																				
										<div class="row">

											<div class="col-md-10 col-sm-10 col-xs-10">
												<label class="control-label">Digite sua pergunta</label>
												<input type="text" class="texto form-control input-sm" name="DES_PERGUNTA" id="DES_PERGUNTA" placeholder="Seu texto" value="<?=$qrAvalia[DES_PERGUNTA]?>" />
											</div>

											<div class="col-md-2 col-sm-2 col-xs-2">
												<div class="form-group">
													<label for="inputName" class="control-label">Avaliação Principal?</label>
													<div class="push5"></div>
														<label class="switch">
														<input type="checkbox" name="LOG_PRINCIPAL" id="LOG_PRINCIPAL" class="switch" value="S" <?=$checkPrincipal?>>
														<span></span>
														</label>
												</div>
											</div>

										</div>
									
										<div class="row">

											<div class="col-md-12 col-sm-12 col-xs-12">
												<div class="form-group">
													<label class="control-label">R&oacute;tulo da Avalia&ccedil;&atilde;o</label>
														<select data-placeholder="Selecione" name="COD_ROTULO" id="COD_ROTULO" class="chosen-select-deselect">
															<option value="">&nbsp;</option>
															<?php
															$sql = "SELECT * FROM TIPO_ROTULO_AVALIACAO_PESQUISA ORDER BY DES_ROTULO_MIN,DES_ROTULO_MAX";
															// fnEscreve($sql);
															$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());

															while($qrRot = mysqli_fetch_assoc($arrayQuery)){
																echo $qrRot["DES_ROTULO_MIN"];
																echo "<option value=".$qrRot["COD_ROTULO"]." ".($qrAvalia["COD_ROTULO"] == $qrRot["COD_ROTULO"]?"selected":"").">".$qrRot["DES_ROTULO_MIN"]." <-----> ".$qrRot["DES_ROTULO_MAX"]."</option>";
															}
															?>
														</select>
														<script>$("#TIP_CONDICAO_<?=$qrCond[COD_CONDICAO]?>").val("<?=fnEncode($qrCond[TIP_CONDICAO])?>").trigger("chosen:updated");</script>
													<div class="help-block with-errors"></div>
												</div>
											</div>
										
										</div>

										<!-- <div class="row">

											<div class="col-md-4 col-sm-4 col-xs-4">
												<div class="form-group">
													<label class="control-label">Tipo</label>
														<select data-placeholder="Selecione" name="TIP_BLOCO" id="TIP_BLOCO" class="chosen-select-deselect requiredChk" required>    
															<option value="">&nbsp;</option>
															<option value="radio">Radio</option>
															<option value="estrela">Estrela</option>
														</select>
														<script>$("#TIP_BLOCO").val("<?=$qrAvalia[TIP_BLOCO]?>").trigger("chosen:updated");</script>
													<div class="help-block with-errors"></div>
												</div>
											</div>

											<div class="col-md-4 col-sm-4 col-xs-4">
												<div class="form-group">
													<label class="control-label">Quantidade</label>
														<select data-placeholder="Selecione" name="NUM_QUANTID" id="NUM_QUANTID" class="chosen-select-deselect requiredChk" required>
															<option value="">&nbsp;</option>
															<option value="10">10</option>
														</select>
														<script>$("#NUM_QUANTID").val("<?=$qrAvalia[NUM_QUANTID]?>").trigger("chosen:updated");</script>
													<div class="help-block with-errors"></div>
												</div>
											</div>

										</div> -->

										<div class="row">

											<div class="col-md-12 col-sm-12 col-md-xs">
													<i class="fa fa-plus-circle" aria-hidden="true"></i>
													<a href="javascript:void(0)" id="addCondicao" onclick="addCondicao()">Adicionar condição</a>
											</div>

										</div>

										<div id="condicoesConteudo">

											<?php
												$count=0;
												$sql = "SELECT * FROM CONDICAO_PESQUISA 
														WHERE COD_EMPRESA = $cod_empresa 
														AND COD_REGISTR = $cod_registr";
												// fnEscreve($sql);
												$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());

												while($qrCond = mysqli_fetch_assoc($arrayQuery)){

											?>


													<div id="BLOCO_<?=$qrCond[COD_CONDICAO]?>">

														<hr>

														<div class="row">

															<div class="col-md-4 col-sm-4 col-xs-4">
																<div class="col-xs-3" style="padding-left: 0; padding-right: 0;">
																	<a href="javascript:void(0)" title="Excluir Condição" onclick='delCondicao("<?=$qrCond[COD_CONDICAO]?>")'>
																		<i class="far fa-trash-alt text-danger" aria-hidden="true"></i>
																	</a>
																</div>
																<div class="col-xs-9 text-right" style="padding-left: 0;">
																	<div class="push20"></div>
																	<div class="push5"></div>
																	<p>Se resposta é:</p>
																</div>							
															</div>

															<div class="col-md-5 col-sm-5 col-xs-5">
																<div class="form-group">
																	<label class="control-label">Condição</label>
																		<select data-placeholder="Selecione" name="TIP_CONDICAO_<?=$qrCond[COD_CONDICAO]?>" id="TIP_CONDICAO_<?=$qrCond[COD_CONDICAO]?>" class="condicaoAvalicao chosen-select-deselect requiredChk" required>
																			<option value="">&nbsp;</option>
																			<option value="<?=fnEncode('=')?>">Igual a</option>
																			<option value="<?=fnEncode('>=')?>">Maior ou igual a</option>
																			<option value="<?=fnEncode('<=')?>">Menor ou igual a</option>
																		</select>
																		<script>$("#TIP_CONDICAO_<?=$qrCond[COD_CONDICAO]?>").val("<?=fnEncode($qrCond[TIP_CONDICAO])?>").trigger("chosen:updated");</script>
																	<div class="help-block with-errors"></div>
																</div>
															</div>

															<div class="col-md-3 col-sm-3 col-xs-3">
																<div class="form-group">
																	<label class="control-label">Resultado</label>
																		<select data-placeholder="Selecione" name="NUM_RESULTADO_<?=$qrCond[COD_CONDICAO]?>" id="NUM_RESULTADO_<?=$qrCond[COD_CONDICAO]?>" class="chosen-select-deselect requiredChk" required>
																			<option value=""></option>
																			<?php  

																				for ($i=1; $i <= 10 ; $i++) { 
																			?>
																				<option value="<?=$i?>"><?=$i?></option>
																			<?php
																				}

																			?>
																		</select>
																		<script>$("#NUM_RESULTADO_<?=$qrCond[COD_CONDICAO]?>").val("<?=$qrCond[NUM_RESULTADO]?>").trigger("chosen:updated");</script>
																	<div class="help-block with-errors"></div>
																</div>
																<!-- <div class="form-group">
																	<label class="control-label">Resultado</label>
																	<input type="text" class="resultado form-control input-sm" name="NUM_RESULTADO_<?=$qrCond[COD_CONDICAO]?>" id="NUM_RESULTADO_<?=$qrCond[COD_CONDICAO]?>" value="<?=$qrCond[NUM_RESULTADO]?>" placeholder="" required />
																</div> -->
															</div>
														</div>

														<div class="row">

															<div class="col-md-12 col-sm-12 col-md-xs">
																<div class="form-group">

																	<?php

																		$redirects = explode(',', $qrCond['NUM_REDIRECT']);

																	?>

																	<label class="control-label">Bloco para qual será redirecionado</label>
																		<select data-placeholder="Selecione" name="NUM_REDIRECT_<?=$qrCond[COD_CONDICAO]?>_0" id="NUM_REDIRECT_<?=$qrCond[COD_CONDICAO]?>_0" class="blocoIrAvaliacao chosen-select-deselect requiredChk" required>
																			<option value=""></option>
																			<?php 
																				$sql = "SELECT COD_REGISTR, DES_PERGUNTA FROM MODELOPESQUISA 
																						WHERE COD_EXCLUSA IS NULL 
																						AND COD_REGISTR != $cod_registr 
																						AND COD_TEMPLATE = $cod_pesquisa";

																				$arrayQueryRed = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());		
																				while ($qrLista = mysqli_fetch_assoc($arrayQueryRed)) {																			
																			?>
																					<option value="<?=$qrLista[COD_REGISTR]?>"><?=$qrLista['DES_PERGUNTA']?></option>
																			<?php 
																				}
																			?>
																		</select>
																		<script>$("#NUM_REDIRECT_<?=$qrCond[COD_CONDICAO]?>_0").val("<?=$redirects[0]?>").trigger("chosen:updated");</script>
																	<div class="help-block with-errors"></div>

																	<div id="condicoesConteudo_<?=$qrCond[COD_CONDICAO]?>">

																		<?php

																			for ($i=1; $i < count($redirects); $i++) { 
																		?>

																				<div class="push10"></div>
																				<label class="control-label">Bloco seguinte</label>
																				<div class="row">
																					<div class="col-xs-11">
																						<select data-placeholder="Selecione" name="NUM_REDIRECT_<?=$qrCond[COD_CONDICAO].'_'.$i?>" id="NUM_REDIRECT_<?=$qrCond[COD_CONDICAO].'_'.$i?>" class="blocoIrAvaliacao chosen-select-deselect requiredChk" required>
																							<option value=""></option>
																							<?php 
																								$sql = "SELECT COD_REGISTR, DES_PERGUNTA FROM MODELOPESQUISA 
																										WHERE COD_EXCLUSA IS NULL 
																										AND COD_REGISTR != $cod_registr 
																										AND COD_TEMPLATE = $cod_pesquisa";

																								$arrayQueryRed = mysqli_query(connTemp($cod_empresa,''),$sql);		
																								while ($qrLista = mysqli_fetch_assoc($arrayQueryRed)) {																			
																							?>
																									<option value="<?=$qrLista[COD_REGISTR]?>"><?=$qrLista['DES_PERGUNTA']?></option>
																							<?php 
																								}
																							?>
																						</select>
																					</div>
																					<div class="col-xs-1 text-right">
																						<div class="push10"></div>
																						<a href="javascript:void(0)" onclick='delRedirect("<?=$qrCond[COD_CONDICAO]?>","<?=$redirects[$i]?>")'><span class="fal fa-times text-danger"></span></a>
																					</div>
																				</div>
																				<script>$("#NUM_REDIRECT_<?=$qrCond[COD_CONDICAO].'_'.$i?>").val("<?=$redirects[$i]?>").trigger("chosen:updated");</script>
																				<div class="help-block with-errors"></div>

																		<?php
																			}

																		?>
																	</div>

																</div>
																<div class="push10"></div>
																<a href="javascript:void(0)" onclick='addRedirect("<?=$qrCond[COD_CONDICAO]?>")'><span class="fal fa-plus-circle"></span> Adicionar redirecionamento</a>
															</div>

														</div>

													</div>

											<?php
												$count++;
												} 
											?>

										</div>
																				
										<!-- <div class="push20"></div> -->
										<hr>
										<div class="row">	
											<div class="form-group text-right col-lg-12">
												<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fas fa-save" aria-hidden="true"></i>&nbsp; Salvar</button>
											</div>
										</div>
										
										<input type="hidden" name="COD_REGISTR" id="COD_REGISTR" value="<?=$cod_registr?>">
										<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?=$cod_empresa?>">
										<input type="hidden" name="NUM_QUANTID" id="NUM_QUANTID" value="10">
										<input type="hidden" name="TIP_BLOCO" id="TIP_BLOCO" value="squares">
										<input type="hidden" name="opcao" id="opcao" value="">
										<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
										<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">
										
										</form>									
									
									</div>								
								
								</div>
							</div>
							<!-- fim Portlet -->
						</div>
						
					</div>					
	
	<script type="text/javascript">
		
		function addCondicao(){
			$.ajax({
				method: "POST",
				url: "ajxAvaliacaoPesquisa.do?opcao=addCond",
				data: {COD_EMPRESA:"<?=$cod_empresa?>",COD_REGISTR:"<?=$cod_registr?>",COD_PESQUISA:"<?=$cod_pesquisa?>"},
				beforeSend:function(){
					$('#condicoesConteudo').append('<div class="loading" style="width: 100%;"></div>');
				},
				success:function(data){
					$("#condicoesConteudo .loading").remove();
					$("#condicoesConteudo").append(data);
					$(".chosen-select-deselect").chosen({allow_single_deselect:true});
				}
			});
		}

		function addRedirect(cod_condicao){
			$.ajax({
				method: "POST",
				url: "ajxAvaliacaoPesquisa.do?opcao=addRedir",
				data: {COD_EMPRESA:"<?=$cod_empresa?>",COD_REGISTR:"<?=$cod_registr?>",COD_PESQUISA:"<?=$cod_pesquisa?>", COD_CONDICAO: cod_condicao},
				beforeSend:function(){
					$("#condicoesConteudo_"+cod_condicao).append('<div class="loading" style="width: 100%;"></div>');
				},
				success:function(data){
					$("#condicoesConteudo_"+cod_condicao+" .loading").remove();
					$("#condicoesConteudo_"+cod_condicao).append(data);
					$(".chosen-select-deselect").chosen({allow_single_deselect:true});
				}
			});
		}

		function delCondicao(cod){
			$.alert({
                title: "Aviso",
                content: "Deseja remover a condição?",
                type: "red",
                buttons: {
					Ok: function () {
						$.ajax({
							method: "POST",
							url: "ajxAvaliacaoPesquisa.do?opcao=exc",
							data: {COD_EMPRESA:"<?=$cod_empresa?>",COD_CONDICAO:cod},
							beforeSend:function(){
								$('#BLOCO_'+cod).html('<div class="loading" style="width: 100%;"></div>');
							},
							success:function(data){
								$('#BLOCO_'+cod).html(
									'<div class="alert alert-success alert-dismissible" role="alert" style="margin-top:20px;margin-bottom:20px;">'+
										'<button type="button" class="close" data-dismiss="alert" aria-label="Close">'+
											'<span aria-hidden="true">&times;</span>'+
										'</button>'+
									 'Condição Excluida'+
									'</div>'
								).fadeOut(2000);
							}
						});
					},
					Cancelar: function(){

					}
				}
            });
		}

		function delRedirect(cod_condicao, num_redirect){
			$.alert({
                title: "Aviso",
                content: "Deseja remover o redirecionamento?",
                type: "red",
                buttons: {
					Ok: function () {
						$.ajax({
							method: "POST",
							url: "ajxAvaliacaoPesquisa.do?opcao=excRedirect",
							data: {COD_EMPRESA:"<?=$cod_empresa?>",COD_REGISTR:"<?=$cod_registr?>", COD_PESQUISA:"<?=$cod_pesquisa?>", COD_CONDICAO:cod_condicao, NUM_REDIRECT: num_redirect},
							beforeSend:function(){
								$("#condicoesConteudo_"+cod_condicao).html('<div class="loading" style="width: 100%;"></div>');
							},
							success:function(data){
								$("#condicoesConteudo_"+cod_condicao).html(data);
							}
						});
					},
					Cancelar: function(){

					}
				}
            });
		}
		
	</script>	