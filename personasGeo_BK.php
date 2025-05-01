					<div class="push30"></div>
					
					<div class="row">
						<div class="col-md-4">					
							<h4>Clientes com Compras</h4>
						</div>
					</div>
						
					<div class="push"></div>
					
					<div class="row">

						<div class="col-md-3" style="display:none;">   
							<input type="checkbox" name="BL5_UNIVE_TODOS" id="BL5_UNIVE_TODOS" class="switch" value="S" <?php echo $check_unive_todos; ?>>
						</div>

						<div class="col-md-3">   
							<div class="form-group">
								<h5>Nas <b>Unidades</b></h5>
								<div class="push5"></div>
									<label class="switch">
									<input type="checkbox" name="BL5_UNIVE_ORIGEM_V" id="BL5_UNIVE_ORIGEM_V" class="switch" value="S" <?php echo $check_unive_origem_v; ?>>
									<span></span>
									</label>
							</div>
							<div class="push5"></div>
							<div class="help-block with-errors">Todas as Vendas</div>
						</div>


						<div class="col-md-3">   
							<div class="form-group">
								<h5>Nas Unidades de <b>Cadastro</b></h5>
								<div class="push5"></div>
									<label class="switch">
									<input type="checkbox" name="BL5_UNIVE_ORIGEM_O" id="BL5_UNIVE_ORIGEM_O" class="switch" value="S" <?php echo $check_unive_origem_o; ?>>
									<span></span>
									</label>
							</div>
							<div class="push5"></div>
							<div class="help-block with-errors">Loja de Origem</div>
						</div>				
						
						<div class="col-md-3">   
							<div class="form-group">
								<h5>Por <b>Cliente</b></h5>
								<div class="push5"></div>
									<label class="switch">
									<input type="checkbox" name="BL5_UNIVE_ORIGEM_C" id="BL5_UNIVE_ORIGEM_C" class="switch" value="S" <?php echo $check_unive_origem_c; ?>>
									<span></span>
									</label>
							</div>
							<div class="push5"></div>
							<div class="help-block with-errors">Cliente / Cartão</div>
						</div>	
						
						<div class="col-md-3">   
							<div class="form-group">
								<h5>Unidade de <b>Preferência</b> </h5>
								<div class="push5"></div>
									<label class="switch">
									<input type="checkbox" name="BL5_UNIPREF" id="BL5_UNIPREF" class="switch" value="S" <?php echo $check_unipref; ?>>
									<span></span>
									</label>
							</div>
						</div>
						
						<div class="push20"></div>
						
						<?php 
						//acesso limitado por loja
						if($usuLimitado == 'false'){
						   $CarregaMaster='1';
						 
						} else {
						   $CarregaMaster='0';
						}


						//fnEscreve(str_replace(',',';',$_SESSION["SYS_COD_UNIVEND"]));
						$unidadeTemporaria = str_replace(',',';',$_SESSION["SYS_COD_UNIVEND"]);
						
						//if()
						//cod_usucada
						//cod_univend 9999
						
						//fnEscreve($cod_usucada_persona);
						//fnEscreve($cod_univend_persona);
						
						$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
						
						$sql3 = "SELECT COD_UNIVEND FROM USUARIOS WHERE COD_USUARIO = $cod_usucada";
						$arrayQuery3 = mysqli_query($connAdm->connAdm(),$sql3);

						//fnEscreve($sql3);

						$qrUs = mysqli_fetch_assoc($arrayQuery3);

						$codUnivend_usu = $qrUs['COD_UNIVEND'];

						// print_r($codUnivend_usu);
						
						
						if (empty($bl5_cod_unive)){
							//fnEscreve("vazio");
							$bl5_cod_unive = $unidadeTemporaria;
						}else{
							//fnEscreve("cheio");
						}
						
						//fnEscreve($bl5_cod_unive);
						
						?>
						
						
						<div class="col-md-12">
							<div class="form-group">
								<label for="inputName" class="control-label">Lojas Desejadas </label>
									<select data-placeholder="Selecione as unidades desejadas" name="BL5_COD_UNIVE[]" id="BL5_COD_UNIVE" multiple="multiple" class="chosen-select-deselect">
										<?php
											if($_SESSION["SYS_COD_EMPRESA"] != $cod_empresa){
												$sql = "SELECT COD_UNIVEND, NOM_FANTASI FROM UNIDADEVENDA WHERE COD_EMPRESA = $cod_empresa AND LOG_ESTATUS = 'S' AND (COD_EXCLUSA = 0 OR COD_EXCLUSA IS NULL) ORDER BY NOM_FANTASI";
											}else{
												
												if($CarregaMaster=='1'){ 
													$sql = "SELECT COD_UNIVEND, NOM_FANTASI FROM UNIDADEVENDA WHERE COD_EMPRESA = $cod_empresa AND LOG_ESTATUS = 'S' AND (COD_EXCLUSA = 0 OR COD_EXCLUSA IS NULL) ORDER BY NOM_FANTASI";
												}else{
													$sql = "SELECT COD_UNIVEND, NOM_FANTASI FROM UNIDADEVENDA WHERE COD_EMPRESA = $cod_empresa AND LOG_ESTATUS = 'S' AND COD_UNIVEND IN($codUnivend_usu) AND (COD_EXCLUSA = 0 OR COD_EXCLUSA IS NULL) ORDER BY NOM_FANTASI";
												}
												
											}
									
											$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());

											$arrayLojas = explode(";",$bl5_cod_unive);
											$arrayLojasUsu = explode(",",$codUnivend_usu);

											$UnSelecionadas = "";
											while ($qrListaUnidade = mysqli_fetch_assoc($arrayQuery)){	

												if (in_array($qrListaUnidade['COD_UNIVEND'],$arrayLojas)){ 
													$checadoLoja = " selected";
												}else{
													$checadoLoja = " ";
												}
										  
												echo"
													  <option value='".$qrListaUnidade['COD_UNIVEND']."' $checadoLoja>".$qrListaUnidade['NOM_FANTASI']."</option> 
													"; 
											}

											$cod_univend_master = "";

											if($CarregaMaster=='0'){

												$ARRAY_UNIDADE1=array(
															   'sql'=>"select COD_UNIVEND,cod_empresa,nom_fantasi,NOM_UNIVEND from unidadevenda where cod_empresa=$cod_empresa  and cod_exclusa=0",
															   'cod_empresa'=>$cod_empresa,
															   'conntadm'=>$connAdm->connAdm(),
															   'IN'=>'N',
															   'nomecampo'=>'',
															   'conntemp'=>'',
															   'SQLIN'=> ""   
															   );
												$ARRAY_UNIDADE=fnUnivend($ARRAY_UNIDADE1);

												$lojasMaster = array_diff($arrayLojas, $arrayLojasUsu);


												foreach ($lojasMaster as $item => $unidade) {

													if($unidade != ""){

														$NOM_ARRAY_UNIDADE=(array_search($unidade, array_column($ARRAY_UNIDADE, 'COD_UNIVEND')));

														echo"
															  <option value='".$unidade."' selected disabled>".$ARRAY_UNIDADE[$NOM_ARRAY_UNIDADE]['nom_fantasi']."</option> 
															";

														$cod_univend_master = $cod_univend_master.$unidade.';';
													}
												}

												$cod_univend_master = rtrim(ltrim($cod_univend_master,';'),';');

											}

										?>	
									</select>
									
								<div class="help-block with-errors"></div>
								<a class="btn btn-default btn-sm" id="iAll" style="padding: 0 2px ; font-size: 10px;"><i class="fa fa-check-square" aria-hidden="true"></i> selecionar todos</a>&nbsp;
								<a class="btn btn-default btn-sm" id="iNone" style="padding: 0 2px ; font-size: 10px;"><i class="fa fa-square-o" aria-hidden="true"></i>&nbsp; deselecionar todos</a>
							</div>
						</div>
						<input type="hidden" name="COD_UNIVEND_MASTER" id="COD_UNIVEND_MASTER" value="<?=$cod_univend_master?>">
						
						
						<?php
						//para aparecer somente para user adm - enquanto grava						
						if($_SESSION["SYS_COD_MASTER"] == 2){
						?>
						
						<div class="push20"></div>
						
						<div class="col-md-12 borda">
							<div class="form-group">
								<label for="inputName" class="control-label">Estado</label>
									<select data-placeholder="Selecione um estado" name="BL5_COD_ESTADOF[]" id="BL5_COD_ESTADOF"  multiple="multiple"  class="chosen-select-deselect" <?=$rqrCOD_ESTADOF?>>
										<option value=""></option>					
										<option value="AC">Acre</option> 
										<option value="AL">Alagoas</option> 
										<option value="AM">Amazonas</option> 
										<option value="AP">Amapá</option> 
										<option value="BA">Bahia</option> 
										<option value="CE">Ceará</option> 
										<option value="DF">Distrito Federal</option> 
										<option value="ES">Espírito Santo</option> 
										<option value="GO">Goiás</option> 
										<option value="MA">Maranhão</option> 
										<option value="MG">Minas Gerais</option> 
										<option value="MS">Mato Grosso do Sul</option> 
										<option value="MT">Mato Grosso</option> 
										<option value="PA">Pará </option> 
										<option value="PB">Paraíba</option> 
										<option value="PE">Pernambuco</option> 
										<option value="PI">Piauí</option> 
										<option value="PR">Paraná</option> 
										<option value="RJ">Rio de Janeiro</option> 
										<option value="RN">Rio Grande do Norte</option> 
										<option value="RO">Rondônia</option> 
										<option value="RR">Roraima</option> 
										<option value="RS">Rio Grande do Sul</option> 
										<option value="SC">Santa Catarina</option> 
										<option value="SE">Sergipe</option> 
										<option value="SP">São Paulo</option> 
										<option value="TO">Tocantins</option> 							
									</select>
									<script>$("#formulario #COD_ESTADOF").val("<?php echo $cod_estadof; ?>").trigger("chosen:updated"); </script>
								<div class="help-block with-errors"></div>
							</div>
						</div>
						
						<?php 
						}
						?>
						
						
					</div>			
						<?php 
							// fnEscreve('teste usu'); 
							// fnEscreve($codUnivend_usu); 
							// echo "_".$arrayLojas."_";
							// echo "<pre>";

							// print_r($lojasMaster);

		     //                echo "</pre>";
						?>
					<div class="push10"></div>
					<hr>	
					<div class="form-group text-right col-lg-12">
						
						  <button type="button" class="btn btn-default limpaGeo"><i class="far fa-star-half-alt" aria-hidden="true"></i>&nbsp; Limpar Bloco</button>
						  <button type="button" class="btn btn-success atualiza" <?php echo $bloqueiaAlt; ?> ><i class="fa fa-check" aria-hidden="true"></i>&nbsp; Aplicar Filtro</button>
						
					</div>
					
		
	<script type="text/javascript">
	
		$(document).ready(function(){

			var todos = "<?=$check_unive_todos?>",
			origem = "<?=$check_unive_todos?>";

			if(todos == "" && origem == ""){
				$("#BL5_UNIVE_TODOS").prop('checked',true);
			}
			
			$("#BL5_COD_UNIVE").chosen({ width: "100%" });

			$("#BL5_UNIVE_TODOS").change(function(){

				if($(this).prop('checked')){
					$("#BL5_UNIVE_ORIGEM").prop('checked',false);
				}

			});

			$("#BL5_UNIVE_ORIGEM_V,#BL5_UNIVE_ORIGEM_O,#BL5_UNIVE_ORIGEM_C").change(function(){

				if($(this).prop('checked')){
					$("#BL5_UNIVE_ORIGEM_V,#BL5_UNIVE_ORIGEM_O,#BL5_UNIVE_ORIGEM_C").prop('checked',false);
					$(this).prop('checked',true);
				}

			});

			$("#BL5_UNIVE_ORIGEM").change(function(){

				if($(this).prop('checked')){
					$("#BL5_UNIVE_TODOS").prop('checked',false);
				}

			});

			$('#iAll').on('click', function(e){
			  e.preventDefault();
			  $('#BL5_COD_UNIVE option').prop('selected', true).trigger('chosen:updated');
			});

			$('#iNone').on('click', function(e){
			  e.preventDefault();
			  $("#BL5_COD_UNIVE option:selected").not(":disabled").removeAttr("selected").trigger('chosen:updated');
			});
				
		});


		
		$(".limpaGeo").click(function() {
			$("#BL5_COD_UNIVE").val("").trigger("chosen:updated");
			$("#notificaGeo").hide();
		});		
		
	</script>