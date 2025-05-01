					<div class="push10"></div>

					<div class="row">
																	

						<?php

							$sql = "SELECT COD_TPFILTRO, DES_TPFILTRO FROM TIPO_FILTRO
							WHERE COD_EMPRESA = $cod_empresa
							ORDER BY NUM_ORDENAC";
							$arrayQuery = mysqli_query(connTemp($cod_empresa,''),trim($sql));

							if(mysqli_num_rows($arrayQuery) > 0){
							$countFiltros = 0;
						?>
								<style>@import url("css/fa5all.css");</style>
								

						<?php 
									while($qrTipo = mysqli_fetch_assoc($arrayQuery)){
						?>

						<style type="text/css">
							#COD_FILTRO_<?=$qrTipo["COD_TPFILTRO"]?>_chosen .chosen-drop .chosen-results li:last-child{
								font-weight: bolder;
								font-size: 11px;
								color: #000;
							}

							#COD_FILTRO_<?=$qrTipo["COD_TPFILTRO"]?>_chosen .chosen-drop .chosen-results li:last-child:before{
								content: '\002795';
								font-weight: bolder;
								font-size: 9px;
							}
						</style>

										<div class="col-md-3">
											<div class="form-group">
												<label for="inputName" class="control-label"><?=$qrTipo['DES_TPFILTRO']?></label>
												<div id="relatorioFiltro_<?=$countFiltros?>">
													<input type="hidden" name="COD_TPFILTRO_<?=$countFiltros?>" id="COD_TPFILTRO_<?=$countFiltros?>" value="<?=$qrTipo['COD_TPFILTRO']?>">
													<select data-placeholder="Selecione o filtro" name="COD_FILTRO_<?=$countFiltros?>" id="COD_FILTRO_<?=$qrTipo[COD_TPFILTRO]?>" class="chosen-select-deselect last-chosen-link">
														<option value=""></option>
						<?php
														$sqlFiltro = "SELECT COD_FILTRO, DES_FILTRO FROM FILTROS_CLIENTE
																	  WHERE COD_TPFILTRO = ".$qrTipo['COD_TPFILTRO'];

														$arrayFiltros = mysqli_query(connTemp($cod_empresa,''),trim($sqlFiltro));
														while($qrFiltros = mysqli_fetch_assoc($arrayFiltros)){
						?>

															<option value="<?=$qrFiltros['COD_FILTRO']?>"><?=$qrFiltros['DES_FILTRO']?></option>

						<?php 
														}

														if($cod_usuario != "" && $cod_usuario != 0){
																$sqlChosen = "SELECT COD_FILTRO FROM CLIENTE_FILTROS
																				WHERE COD_CLIENTE = $cod_usuario AND COD_TPFILTRO =".$qrTipo['COD_TPFILTRO'];
																$arrayChosen = mysqli_query(connTemp($cod_empresa,''),$sqlChosen);
																if(mysqli_num_rows($arrayChosen) > 0){
																	$qrChosen = mysqli_fetch_assoc($arrayChosen);
						?>
																	<script>
																		$('#COD_FILTRO_<?=$qrTipo[COD_TPFILTRO]?>').val(<?=$qrChosen['COD_FILTRO']?>).trigger('chosen:updated');
																	</script>
						<?php
																}
															}
						?>						
														<!--<option value="add">&nbsp;ADICIONAR NOVO</option>-->
													</select>
													<script type="text/javascript">
														$('#COD_FILTRO_<?=$qrTipo[COD_TPFILTRO]?>').change(function(){
															valor = $(this).val();
															if(valor=="add"){
																$(this).val('').trigger("chosen:updated");
																$('#btnCad_<?=$countFiltros?>').click();
															}
														});
													</script>                                                         
													<div class="help-block with-errors"></div>
												</div>
											</div>
										</div>
										<a type="hidden" name="btnCad_<?=$countFiltros?>" id="btnCad_<?=$countFiltros?>" class="addBox" data-url="action.php?mod=<?php echo fnEncode(1398)?>&id=<?php echo fnEncode($cod_empresa)?>&idF=<?=fnEncode($qrTipo[COD_TPFILTRO])?>&idS=<?=fnEncode($countFiltros)?>&pop=true" data-title="Cadastrar Filtro - <?=$qrTipo[DES_TPFILTRO]?>"></a>

						<?php 
										$countFiltros++;

										if($countFiltros == 4){
											echo "<div class='push10'></div>";
										}
									}
						?>
									
								<div class="push10"></div>

						<?php 
							}
						?>

					</div>		
						
					<div class="push10"></div>
					<hr>	
					<div class="form-group text-right col-lg-12">
						
						  <button type="button" class="btn btn-default limpaGeo"><i class="far fa-star-half-alt" aria-hidden="true"></i>&nbsp; Limpar Bloco</button>
						  <button type="button" class="btn btn-success atualiza" <?php echo $bloqueiaAlt; ?> ><i class="fa fa-check" aria-hidden="true"></i>&nbsp; Aplicar Filtro</button>
						
					</div>
					
		
	<script type="text/javascript">
	
		$(document).ready(function(){
			
			$("#BL5_COD_UNIVE").chosen({ width: "100%" }); 

			$(".chosen-select-deselect").chosen();
				
		});
		
		$(".limpaGeo").click(function() {
			$("#BL5_COD_UNIVE").val("").trigger("chosen:updated");
			$("#notificaGeo").hide();
		});		
		
	</script>