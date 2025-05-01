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
									<label class="switch switch-small">
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
									<label class="switch switch-small">
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
									<label class="switch switch-small">
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
									<label class="switch switch-small">
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
							$arrayEstados = explode(";",@$bl5_cod_estadof);
						?>
						
						<div class="push20"></div>
						
						<div class="col-md-12">
							<div class="form-group">
								<label for="inputName" class="control-label">Estado</label>
									<select data-placeholder="Selecione um estado" name="BL5_COD_ESTADOF[]" id="BL5_COD_ESTADOF"  multiple="multiple"  class="chosen-select-deselect">
										<option value=""></option>					
										<option value="AC" <?=(in_array("AC",$arrayEstados)?"selected":"")?>>Acre</option> 
										<option value="AL" <?=(in_array("AL",$arrayEstados)?"selected":"")?>>Alagoas</option> 
										<option value="AM" <?=(in_array("AM",$arrayEstados)?"selected":"")?>>Amazonas</option> 
										<option value="AP" <?=(in_array("AP",$arrayEstados)?"selected":"")?>>Amapá</option> 
										<option value="BA" <?=(in_array("BA",$arrayEstados)?"selected":"")?>>Bahia</option> 
										<option value="CE" <?=(in_array("CE",$arrayEstados)?"selected":"")?>>Ceará</option> 
										<option value="DF" <?=(in_array("DF",$arrayEstados)?"selected":"")?>>Distrito Federal</option> 
										<option value="ES" <?=(in_array("ES",$arrayEstados)?"selected":"")?>>Espírito Santo</option> 
										<option value="GO" <?=(in_array("GO",$arrayEstados)?"selected":"")?>>Goiás</option> 
										<option value="MA" <?=(in_array("MA",$arrayEstados)?"selected":"")?>>Maranhão</option> 
										<option value="MG" <?=(in_array("MG",$arrayEstados)?"selected":"")?>>Minas Gerais</option> 
										<option value="MS" <?=(in_array("MS",$arrayEstados)?"selected":"")?>>Mato Grosso do Sul</option> 
										<option value="MT" <?=(in_array("MT",$arrayEstados)?"selected":"")?>>Mato Grosso</option> 
										<option value="PA" <?=(in_array("PA",$arrayEstados)?"selected":"")?>>Pará </option> 
										<option value="PB" <?=(in_array("PB",$arrayEstados)?"selected":"")?>>Paraíba</option> 
										<option value="PE" <?=(in_array("PE",$arrayEstados)?"selected":"")?>>Pernambuco</option> 
										<option value="PI" <?=(in_array("PI",$arrayEstados)?"selected":"")?>>Piauí</option> 
										<option value="PR" <?=(in_array("PR",$arrayEstados)?"selected":"")?>>Paraná</option> 
										<option value="RJ" <?=(in_array("RJ",$arrayEstados)?"selected":"")?>>Rio de Janeiro</option> 
										<option value="RN" <?=(in_array("RN",$arrayEstados)?"selected":"")?>>Rio Grande do Norte</option> 
										<option value="RO" <?=(in_array("RO",$arrayEstados)?"selected":"")?>>Rondônia</option> 
										<option value="RR" <?=(in_array("RR",$arrayEstados)?"selected":"")?>>Roraima</option> 
										<option value="RS" <?=(in_array("RS",$arrayEstados)?"selected":"")?>>Rio Grande do Sul</option> 
										<option value="SC" <?=(in_array("SC",$arrayEstados)?"selected":"")?>>Santa Catarina</option> 
										<option value="SE" <?=(in_array("SE",$arrayEstados)?"selected":"")?>>Sergipe</option> 
										<option value="SP" <?=(in_array("SP",$arrayEstados)?"selected":"")?>>São Paulo</option> 
										<option value="TO" <?=(in_array("TO",$arrayEstados)?"selected":"")?>>Tocantins</option> 							
									</select>
								<div class="help-block with-errors"></div>
							</div>
						</div>						
						
					</div>
					
						<?php 
							// fnEscreve('teste usu'); 
							// fnEscreve($codUnivend_usu); 
							// echo "_".$arrayLojas."_";
							// echo "<pre>";
							// print_r($lojasMaster);
							//	echo "</pre>";
						?>

					
					<div class="push10"></div>
					
					<div class="push10"></div>
					<hr>
					<div class="form-group text-right col-lg-12">
						
						  <a type="button" href="https://adm.bunker.mk/action.do?mod=<?=fnEncode("1661");?>&id=<?=$_GET["id"]?>&idx=<?=$_GET["idx"]?>" target="_blank" class="btn btn-success pull-left"><i class="fal fa-map-marked-alt" aria-hidden="true"></i>&nbsp; Visualizar Mapa</a>
						  <button type="button" class="btn btn-default limpaGeo"><i class="far fa-star-half-alt" aria-hidden="true"></i>&nbsp; Limpar Bloco</button>
						  <button type="button" class="btn btn-success atualiza" <?php echo $bloqueiaAlt; ?> ><i class="fa fa-check" aria-hidden="true"></i>&nbsp; Aplicar Filtro</button>
						
					</div>
					
		
	<script type="text/javascript">
	
		$(document).ready(function(){

			var todos = "<?=@$check_unive_todos?>",
			origem = "<?=@$check_unive_todos?>";

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

			$("#BL5_UNIPREF").change(function(){

				if($(this).prop('checked')){
					$("#BL5_UNIVE_TODOS,#BL5_UNIVE_ORIGEM_V,#BL5_UNIVE_ORIGEM_O,#BL5_UNIVE_ORIGEM_C").prop('checked',false);
				}

			});

			$("#BL5_UNIVE_TODOS,#BL5_UNIVE_ORIGEM_V,#BL5_UNIVE_ORIGEM_O,#BL5_UNIVE_ORIGEM_C").change(function(){

				if($(this).prop('checked')){
					$("#BL5_UNIPREF").prop('checked',false);
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