<?php

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

			$cod_redesoc = fnLimpaCampoZero($_REQUEST['COD_REDESOC']);
			$des_redesoc = fnLimpaCampo($_REQUEST['DES_REDESOC']);
			$cod_empresa = fnLimpaCampo($_REQUEST['COD_EMPRESA']);
			$cod_redes = fnLimpaCampoZero($_REQUEST['COD_REDES']);

			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
						
			if ($opcao != ''){
				
				if ($opcao != ''){
			
			
				}
				
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
      
	//fnMostraForm();

?>

<style>
	.chosen-container{
		width: 100%!important;
	}
</style>
					
					<div class="push30"></div>

					<div class="row">
												
						<?php
						$sql = "select  A.*,B.NOM_EMPRESA as NOM_EMPRESA from EMPRESACOMPLEMENTO A 
								INNER JOIN empresas B ON A.COD_EMPRESA = B.COD_EMPRESA
								where A.COD_EMPRESA = $cod_empresa ";		
						
						
						//fnEscreve($sql);
						$arrayQuery = mysqli_query($connAdm->connAdm(),$sql);
						$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
				 
						if (isset($arrayQuery)){
							//$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
							$lblAtributo1 = $qrBuscaEmpresa['ATRIBUTO1'];
							$lblAtributo2 = $qrBuscaEmpresa['ATRIBUTO2'];
							$lblAtributo3 = $qrBuscaEmpresa['ATRIBUTO3'];
							$lblAtributo4 = $qrBuscaEmpresa['ATRIBUTO4'];
							$lblAtributo5 = $qrBuscaEmpresa['ATRIBUTO5'];
							$lblAtributo6 = $qrBuscaEmpresa['ATRIBUTO6'];
							$lblAtributo7 = $qrBuscaEmpresa['ATRIBUTO7'];
							$lblAtributo8 = $qrBuscaEmpresa['ATRIBUTO8'];
							$lblAtributo9 = $qrBuscaEmpresa['ATRIBUTO9'];
							$lblAtributo10 = $qrBuscaEmpresa['ATRIBUTO10'];
							$lblAtributo11 = $qrBuscaEmpresa['ATRIBUTO11'];
							$lblAtributo12 = $qrBuscaEmpresa['ATRIBUTO12'];
							$lblAtributo13 = $qrBuscaEmpresa['ATRIBUTO13'];
						}												
						?>

						
						<?php if ($lblAtributo1 != "") { $atribObrig1 = ""; $hide1 = "";} else { $atribObrig2 = ""; $hide1 = "hidden";} ?>
						<div class="col-md-3 <?php echo $hide1; ?>">
							<div class="form-group">
								<label for="inputName" class="control-label <?php echo $atribObrig1; ?>"><?php echo $lblAtributo1; ?> </label>
								<!--<input type="text" class="form-control input-sm" name="ATRIBUTO1" id="ATRIBUTO1" maxlength="20" value="<?php echo $atributo1; ?>" <?php echo $atribObrig1; ?>>-->
								<select data-placeholder="Opções de <?php echo strtolower ($lblAtributo1); ?>" name="ATRIBUTO1" id="ATRIBUTO1" class="chosen-select-deselect" style="width:100%;" tabindex="1" onchange='gravaAtributos(this,1)'>
									<option value=""></option>
									<?php 

										$sql = "SELECT COD_PARAMETRO, DES_PARAMETRO FROM PARAMETRO1 WHERE COD_EMPRESA = $cod_empresa order by DES_PARAMETRO ";
										$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
										while($qrParam = mysqli_fetch_assoc($arrayQuery)){

									?>

										<option value="<?=$qrParam[COD_PARAMETRO]?>"><?=$qrParam['DES_PARAMETRO']?></option>

									<?php

										}

									?>
								</select>
								
								<div class="help-block with-errors"></div>
								<?php

									$sql = "SELECT COD_ATRIBUTO FROM ATRIBUTOS_PRODUTOPERSONA 
											WHERE COD_PERSONA = $cod_persona 
											AND COD_EMPRESA = $cod_empresa 
											AND TIP_ATRIBUTO = 1";

									$qrAttr = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));

								?>
								<script>
									$('#ATRIBUTO1').val('<?=fnLimpaCampoZero($qrAttr[COD_ATRIBUTO])?>').trigger('chosen:updated');
								</script>
							</div>
						</div>
						
						<?php if ($lblAtributo2 != "") { $atribObrig2 = ""; $hide2 = "";} else { $atribObrig2 = ""; $hide2 = "hidden";} ?>
						<div class="col-md-3 <?php echo $hide2; ?>">
							<div class="form-group">
								<label for="inputName" class="control-label <?php echo $atribObrig2; ?>"><?php echo $lblAtributo2; ?> </label>
								<!--<input type="text" class="form-control input-sm" name="ATRIBUTO2" id="ATRIBUTO2" maxlength="20" value="<?php echo $atributo2; ?>" <?php echo $atribObrig2; ?>>-->
								<select data-placeholder="Opções de <?php echo strtolower ($lblAtributo2); ?>" name="ATRIBUTO2" id="ATRIBUTO2" class="chosen-select-deselect" style="width:100%;" tabindex="1" onchange='gravaAtributos(this,2)'>
									<option value=""></option>
									<?php 

										$sql = "SELECT COD_PARAMETRO, DES_PARAMETRO FROM PARAMETRO2 WHERE COD_EMPRESA = $cod_empresa order by DES_PARAMETRO ";
										$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
										while($qrParam = mysqli_fetch_assoc($arrayQuery)){

									?>

										<option value="<?=$qrParam[COD_PARAMETRO]?>"><?=$qrParam['DES_PARAMETRO']?></option>

									<?php

										}

									?>
								</select>															
								<div class="help-block with-errors"></div>
								<?php

									$sql = "SELECT COD_ATRIBUTO FROM ATRIBUTOS_PRODUTOPERSONA 
											WHERE COD_PERSONA = $cod_persona 
											AND COD_EMPRESA = $cod_empresa 
											AND TIP_ATRIBUTO = 2";

									$qrAttr = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));

								?>
								<script>
									$('#ATRIBUTO2').val('<?=fnLimpaCampoZero($qrAttr[COD_ATRIBUTO])?>').trigger('chosen:updated');
								</script>
							</div>
						</div>
						
						<?php if ($lblAtributo3 != "") { $atribObrig3 = ""; $hide3 = "";} else { $atribObrig3 = ""; $hide3 = "hidden";} ?>
						<div class="col-md-3 <?php echo $hide3; ?>">
							<div class="form-group">
								<label for="inputName" class="control-label <?php echo $atribObrig3; ?>"><?php echo $lblAtributo3; ?> </label>
								<!--<input type="text" class="form-control input-sm" name="ATRIBUTO3" id="ATRIBUTO3" maxlength="30" value="<?php echo $atributo3; ?>" <?php echo $atribObrig3; ?>>-->
								<select data-placeholder="Opções de <?php echo strtolower ($lblAtributo3); ?>" name="ATRIBUTO3" id="ATRIBUTO3" class="chosen-select-deselect" style="width:100%;" tabindex="1" onchange='gravaAtributos(this,3)'>
									<option value=""></option>
									<?php 

										$sql = "SELECT COD_PARAMETRO, DES_PARAMETRO FROM PARAMETRO3 WHERE COD_EMPRESA = $cod_empresa order by DES_PARAMETRO ";
										$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
										while($qrParam = mysqli_fetch_assoc($arrayQuery)){

									?>

										<option value="<?=$qrParam[COD_PARAMETRO]?>"><?=$qrParam['DES_PARAMETRO']?></option>

									<?php

										}

									?>
								</select>															
								<div class="help-block with-errors"></div>
								<?php

									$sql = "SELECT COD_ATRIBUTO FROM ATRIBUTOS_PRODUTOPERSONA 
											WHERE COD_PERSONA = $cod_persona 
											AND COD_EMPRESA = $cod_empresa 
											AND TIP_ATRIBUTO = 3";

									$qrAttr = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));

								?>
								<script>
									$('#ATRIBUTO3').val('<?=fnLimpaCampoZero($qrAttr[COD_ATRIBUTO])?>').trigger('chosen:updated');
								</script>
							</div>
						</div>	
						
						<?php if ($lblAtributo4 != "") { $atribObrig4 = ""; $hide4 = "";} else { $atribObrig4 = ""; $hide4 = "hidden";} ?>
						<div class="col-md-3 <?php echo $hide4; ?>">
							<div class="form-group">
								<label for="inputName" class="control-label <?php echo $atribObrig4; ?>"><?php echo $lblAtributo4; ?> </label>
								<!--<input type="text" class="form-control input-sm" name="ATRIBUTO4" id="ATRIBUTO4" maxlength="40" value="<?php echo $atributo4; ?>" <?php echo $atribObrig4; ?>>-->
								<select data-placeholder="Opções de <?php echo strtolower ($lblAtributo4); ?>" name="ATRIBUTO4" id="ATRIBUTO4" class="chosen-select-deselect" style="width:100%;" tabindex="1" onchange='gravaAtributos(this,4)'>
									<option value=""></option>
									<?php 

										$sql = "SELECT COD_PARAMETRO, DES_PARAMETRO FROM PARAMETRO4 WHERE COD_EMPRESA = $cod_empresa order by DES_PARAMETRO ";
										$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
										while($qrParam = mysqli_fetch_assoc($arrayQuery)){

									?>

										<option value="<?=$qrParam[COD_PARAMETRO]?>"><?=$qrParam['DES_PARAMETRO']?></option>

									<?php

										}

									?>
								</select>															
								<div class="help-block with-errors"></div>
								<?php

									$sql = "SELECT COD_ATRIBUTO FROM ATRIBUTOS_PRODUTOPERSONA 
											WHERE COD_PERSONA = $cod_persona 
											AND COD_EMPRESA = $cod_empresa 
											AND TIP_ATRIBUTO = 4";

									$qrAttr = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));

								?>
								<script>
									$('#ATRIBUTO4').val('<?=fnLimpaCampoZero($qrAttr[COD_ATRIBUTO])?>').trigger('chosen:updated');
								</script>
							</div>
						</div>

						<div class="push10"></div>													
						
						<?php if ($lblAtributo5 != "") { $atribObrig5 = ""; $hide5 = "";} else { $atribObrig5 = ""; $hide5 = "hidden";} ?>
						<div class="col-md-3 <?php echo $hide5; ?>">
							<div class="form-group">
								<label for="inputName" class="control-label <?php echo $atribObrig5; ?>"><?php echo $lblAtributo5; ?> </label>
								<!--<input type="text" class="form-control input-sm" name="ATRIBUTO5" id="ATRIBUTO5" maxlength="50" value="<?php echo $atributo5; ?>" <?php echo $atribObrig5; ?>>-->
								<select data-placeholder="Opções de <?php echo strtolower ($lblAtributo5); ?>" name="ATRIBUTO5" id="ATRIBUTO5" class="chosen-select-deselect" style="width:100%;" tabindex="1" onchange='gravaAtributos(this,5)'>
									<option value=""></option>
									<?php 

										$sql = "SELECT COD_PARAMETRO, DES_PARAMETRO FROM PARAMETRO5 WHERE COD_EMPRESA = $cod_empresa order by DES_PARAMETRO ";
										$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
										while($qrParam = mysqli_fetch_assoc($arrayQuery)){

									?>

										<option value="<?=$qrParam[COD_PARAMETRO]?>"><?=$qrParam['DES_PARAMETRO']?></option>

									<?php

										}

									?>
								</select>															
								<div class="help-block with-errors"></div>
								<?php

									$sql = "SELECT COD_ATRIBUTO FROM ATRIBUTOS_PRODUTOPERSONA 
											WHERE COD_PERSONA = $cod_persona 
											AND COD_EMPRESA = $cod_empresa 
											AND TIP_ATRIBUTO = 5";

									$qrAttr = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));

								?>
								<script>
									$('#ATRIBUTO5').val('<?=fnLimpaCampoZero($qrAttr[COD_ATRIBUTO])?>').trigger('chosen:updated');
								</script>
							</div>
						</div>	
						
						<?php if ($lblAtributo6 != "") { $atribObrig6 = ""; $hide6 = "";} else { $atribObrig6 = ""; $hide6 = "hidden";} ?>
						<div class="col-md-3 <?php echo $hide6; ?>">
							<div class="form-group">
								<label for="inputName" class="control-label <?php echo $atribObrig6; ?>"><?php echo $lblAtributo6; ?> </label>
								<!--<input type="text" class="form-control input-sm" name="ATRIBUTO6" id="ATRIBUTO6" maxlength="60" value="<?php echo $atributo6; ?>" <?php echo $atribObrig6; ?>>-->
								<select data-placeholder="Opções de <?php echo strtolower ($lblAtributo6); ?>" name="ATRIBUTO6" id="ATRIBUTO6" class="chosen-select-deselect" style="width:100%;" tabindex="1" onchange='gravaAtributos(this,6)'>
									<option value=""></option>
									<?php 

										$sql = "SELECT COD_PARAMETRO, DES_PARAMETRO FROM PARAMETRO6 WHERE COD_EMPRESA = $cod_empresa order by DES_PARAMETRO ";
										$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
										while($qrParam = mysqli_fetch_assoc($arrayQuery)){

									?>

										<option value="<?=$qrParam[COD_PARAMETRO]?>"><?=$qrParam['DES_PARAMETRO']?></option>

									<?php

										}

									?>
								</select>															
								<div class="help-block with-errors"></div>
								<?php

									$sql = "SELECT COD_ATRIBUTO FROM ATRIBUTOS_PRODUTOPERSONA 
											WHERE COD_PERSONA = $cod_persona 
											AND COD_EMPRESA = $cod_empresa 
											AND TIP_ATRIBUTO = 6";

									$qrAttr = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));

								?>
								<script>
									$('#ATRIBUTO6').val('<?=fnLimpaCampoZero($qrAttr[COD_ATRIBUTO])?>').trigger('chosen:updated');
								</script>
							</div>
						</div>	
						
						<?php if ($lblAtributo7 != "") { $atribObrig7 = ""; $hide7 = "";} else { $atribObrig7 = ""; $hide7 = "hidden";} ?>
						<div class="col-md-3 <?php echo $hide7; ?>">
							<div class="form-group">
								<label for="inputName" class="control-label <?php echo $atribObrig7; ?>"><?php echo $lblAtributo7; ?> </label>
								<!--<input type="text" class="form-control input-sm" name="ATRIBUTO7" id="ATRIBUTO7" maxlength="70" value="<?php echo $atributo7; ?>" <?php echo $atribObrig7; ?>>-->
								<select data-placeholder="Opções de <?php echo strtolower ($lblAtributo7); ?>" name="ATRIBUTO7" id="ATRIBUTO7" class="chosen-select-deselect" style="width:100%;" tabindex="1" onchange='gravaAtributos(this,7)'>
									<option value=""></option>
									<?php 

										$sql = "SELECT COD_PARAMETRO, DES_PARAMETRO FROM PARAMETRO7 WHERE COD_EMPRESA = $cod_empresa order by DES_PARAMETRO ";
										$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
										while($qrParam = mysqli_fetch_assoc($arrayQuery)){

									?>

										<option value="<?=$qrParam[COD_PARAMETRO]?>"><?=$qrParam['DES_PARAMETRO']?></option>

									<?php

										}

									?>
								</select>															
								<div class="help-block with-errors"></div>
								<?php

									$sql = "SELECT COD_ATRIBUTO FROM ATRIBUTOS_PRODUTOPERSONA 
											WHERE COD_PERSONA = $cod_persona 
											AND COD_EMPRESA = $cod_empresa 
											AND TIP_ATRIBUTO = 7";

									$qrAttr = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));

								?>
								<script>
									$('#ATRIBUTO7').val('<?=fnLimpaCampoZero($qrAttr[COD_ATRIBUTO])?>').trigger('chosen:updated');
								</script>
							</div>
						</div>														
						
						<?php if ($lblAtributo8 != "") { $atribObrig8 = ""; $hide8 = "";} else { $atribObrig8 = ""; $hide8 = "hidden";} ?>
						<div class="col-md-3 <?php echo $hide8; ?>">
							<div class="form-group">
								<label for="inputName" class="control-label <?php echo $atribObrig8; ?>"><?php echo $lblAtributo8; ?> </label>
								<!--<input type="text" class="form-control input-sm" name="ATRIBUTO8" id="ATRIBUTO8" maxlength="80" value="<?php echo $atributo8; ?>" <?php echo $atribObrig8; ?>>-->
								<select data-placeholder="Opções de <?php echo strtolower ($lblAtributo8); ?>" name="ATRIBUTO8" id="ATRIBUTO8" class="chosen-select-deselect" style="width:100%;" tabindex="1" onchange='gravaAtributos(this,8)'>
									<option value=""></option>
									<?php 

										$sql = "SELECT COD_PARAMETRO, DES_PARAMETRO FROM PARAMETRO8 WHERE COD_EMPRESA = $cod_empresa order by DES_PARAMETRO ";
										$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
										while($qrParam = mysqli_fetch_assoc($arrayQuery)){

									?>

										<option value="<?=$qrParam[COD_PARAMETRO]?>"><?=$qrParam['DES_PARAMETRO']?></option>

									<?php

										}

									?>
								</select>															
								<div class="help-block with-errors"></div>
								<?php

									$sql = "SELECT COD_ATRIBUTO FROM ATRIBUTOS_PRODUTOPERSONA 
											WHERE COD_PERSONA = $cod_persona 
											AND COD_EMPRESA = $cod_empresa 
											AND TIP_ATRIBUTO = 8";

									$qrAttr = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));

								?>
								<script>
									$('#ATRIBUTO8').val('<?=fnLimpaCampoZero($qrAttr[COD_ATRIBUTO])?>').trigger('chosen:updated');
								</script>
							</div>
						</div>	
						
						<?php if ($lblAtributo9 != "") { $atribObrig9 = ""; $hide9 = "";} else { $atribObrig9 = ""; $hide9 = "hidden";} ?>
						<div class="col-md-3 <?php echo $hide9; ?>">
							<div class="form-group">
								<label for="inputName" class="control-label <?php echo $atribObrig9; ?>"><?php echo $lblAtributo9; ?> </label>
								<!--<input type="text" class="form-control input-sm" name="ATRIBUTO9" id="ATRIBUTO9" maxlength="90" value="<?php echo $atributo9; ?>" <?php echo $atribObrig9; ?>>-->
								<select data-placeholder="Opções de <?php echo strtolower ($lblAtributo9); ?>" name="ATRIBUTO9" id="ATRIBUTO9" class="chosen-select-deselect" style="width:100%;" tabindex="1" onchange='gravaAtributos(this,9)'>
									<option value=""></option>
									<?php 

										$sql = "SELECT COD_PARAMETRO, DES_PARAMETRO FROM PARAMETRO9 WHERE COD_EMPRESA = $cod_empresa order by DES_PARAMETRO ";
										$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
										while($qrParam = mysqli_fetch_assoc($arrayQuery)){

									?>

										<option value="<?=$qrParam[COD_PARAMETRO]?>"><?=$qrParam['DES_PARAMETRO']?></option>

									<?php

										}

									?>
								</select>															
								<div class="help-block with-errors"></div>
								<?php

									$sql = "SELECT COD_ATRIBUTO FROM ATRIBUTOS_PRODUTOPERSONA 
											WHERE COD_PERSONA = $cod_persona 
											AND COD_EMPRESA = $cod_empresa 
											AND TIP_ATRIBUTO = 9";

									$qrAttr = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));

								?>
								<script>
									$('#ATRIBUTO9').val('<?=fnLimpaCampoZero($qrAttr[COD_ATRIBUTO])?>').trigger('chosen:updated');
								</script>
							</div>
						</div>														
						
						<?php if ($lblAtributo10 != "") { $atribObrig10 = ""; $hide10 = "";} else { $atribObrig10 = ""; $hide10 = "hidden";} ?>
						<div class="col-md-3 <?php echo $hide10; ?>">
							<div class="form-group">
								<label for="inputName" class="control-label <?php echo $atribObrig10; ?>"><?php echo $lblAtributo10; ?> </label>
								<!--<input type="text" class="form-control input-sm" name="ATRIBUTO10" id="ATRIBUTO10" maxlength="100" value="<?php echo $atributo10; ?>" <?php echo $atribObrig10; ?>>-->
								<select data-placeholder="Opções de <?php echo strtolower ($lblAtributo10); ?>" name="ATRIBUTO10" id="ATRIBUTO10" class="chosen-select-deselect" style="width:100%;" tabindex="1" onchange='gravaAtributos(this,10)'>
									<option value=""></option>
									<?php 

										$sql = "SELECT COD_PARAMETRO, DES_PARAMETRO FROM PARAMETRO10 WHERE COD_EMPRESA = $cod_empresa order by DES_PARAMETRO ";
										$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
										while($qrParam = mysqli_fetch_assoc($arrayQuery)){

									?>

										<option value="<?=$qrParam[COD_PARAMETRO]?>"><?=$qrParam['DES_PARAMETRO']?></option>

									<?php

										}

									?>
								</select>															
								<div class="help-block with-errors"></div>
								<?php

									$sql = "SELECT COD_ATRIBUTO FROM ATRIBUTOS_PRODUTOPERSONA 
											WHERE COD_PERSONA = $cod_persona 
											AND COD_EMPRESA = $cod_empresa 
											AND TIP_ATRIBUTO = 10";

									$qrAttr = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));

								?>
								<script>
									$('#ATRIBUTO10').val('<?=fnLimpaCampoZero($qrAttr[COD_ATRIBUTO])?>').trigger('chosen:updated');
								</script>
							</div>
						</div>
						
						<?php if ($lblAtributo11 != "") { $atribObrig11 = ""; $hide11 = "";} else { $atribObrig11 = ""; $hide11 = "hidden";} ?>
						<div class="col-md-3 <?php echo $hide11; ?>">
							<div class="form-group">
								<label for="inputName" class="control-label <?php echo $atribObrig11; ?>"><?php echo $lblAtributo11; ?> </label>
								<!--<input type="text" class="form-control input-sm" name="ATRIBUTO11" id="ATRIBUTO11" maxlength="110" value="<?php echo $atributo11; ?>" <?php echo $atribObrig11; ?>>-->
								<select data-placeholder="Opções de <?php echo strtolower ($lblAtributo11); ?>" name="ATRIBUTO11" id="ATRIBUTO11" class="chosen-select-deselect" style="width:100%;" tabindex="1" onchange='gravaAtributos(this,11)'>
									<option value=""></option>
									<?php 

										$sql = "SELECT COD_PARAMETRO, DES_PARAMETRO FROM PARAMETRO11 WHERE COD_EMPRESA = $cod_empresa order by DES_PARAMETRO ";
										$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
										while($qrParam = mysqli_fetch_assoc($arrayQuery)){

									?>

										<option value="<?=$qrParam[COD_PARAMETRO]?>"><?=$qrParam['DES_PARAMETRO']?></option>

									<?php

										}

									?>
								</select>															
								<div class="help-block with-errors"></div>
								<?php

									$sql = "SELECT COD_ATRIBUTO FROM ATRIBUTOS_PRODUTOPERSONA 
											WHERE COD_PERSONA = $cod_persona 
											AND COD_EMPRESA = $cod_empresa 
											AND TIP_ATRIBUTO = 11";

									$qrAttr = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));

								?>
								<script>
									$('#ATRIBUTO11').val('<?=fnLimpaCampoZero($qrAttr[COD_ATRIBUTO])?>').trigger('chosen:updated');
								</script>
							</div>
						</div>
								
						<?php if ($lblAtributo12 != "") { $atribObrig12 = ""; $hide12 = "";} else { $atribObrig12 = ""; $hide12 = "hidden";} ?>
						<div class="col-md-3 <?php echo $hide12; ?>">
							<div class="form-group">
								<label for="inputName" class="control-label <?php echo $atribObrig12; ?>"><?php echo $lblAtributo12; ?> </label>
								<!--<input type="text" class="form-control input-sm" name="ATRIBUTO12" id="ATRIBUTO12" maxlength="120" value="<?php echo $atributo12; ?>" <?php echo $atribObrig12; ?>>-->
								<select data-placeholder="Opções de <?php echo strtolower ($lblAtributo12); ?>" name="ATRIBUTO12" id="ATRIBUTO12" class="chosen-select-deselect" style="width:100%;" tabindex="1" onchange='gravaAtributos(this,12)'>
									<option value=""></option>
									<?php 

										$sql = "SELECT COD_PARAMETRO, DES_PARAMETRO FROM PARAMETRO12 WHERE COD_EMPRESA = $cod_empresa order by DES_PARAMETRO ";
										$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
										while($qrParam = mysqli_fetch_assoc($arrayQuery)){

									?>

										<option value="<?=$qrParam[COD_PARAMETRO]?>"><?=$qrParam['DES_PARAMETRO']?></option>

									<?php

										}

									?>
								</select>															
								<div class="help-block with-errors"></div>
								<?php

									$sql = "SELECT COD_ATRIBUTO FROM ATRIBUTOS_PRODUTOPERSONA 
											WHERE COD_PERSONA = $cod_persona 
											AND COD_EMPRESA = $cod_empresa 
											AND TIP_ATRIBUTO = 12";

									$qrAttr = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));

								?>
								<script>
									$('#ATRIBUTO12').val('<?=fnLimpaCampoZero($qrAttr[COD_ATRIBUTO])?>').trigger('chosen:updated');
								</script>
							</div>
						</div>	
						
						<?php if ($lblAtributo13 != "") { $atribObrig13 = ""; $hide13 = "";} else { $atribObrig13 = ""; $hide13 = "hidden";} ?>
						<div class="col-md-3 <?php echo $hide13; ?>">
							<div class="form-group">
								<label for="inputName" class="control-label <?php echo $atribObrig13; ?>"><?php echo $lblAtributo13; ?> </label>
								<!--<input type="text" class="form-control input-sm" name="ATRIBUTO13" id="ATRIBUTO13" maxlength="130" value="<?php echo $atributo12; ?>" <?php echo $atribObrig13; ?>>-->
								<select data-placeholder="Opções de <?php echo strtolower ($lblAtributo13); ?>" name="ATRIBUTO13" id="ATRIBUTO13" class="chosen-select-deselect" style="width:100%;" tabindex="1" onchange='gravaAtributos(this,13)'>
									<option value=""></option>
									<?php 

										$sql = "SELECT COD_PARAMETRO, DES_PARAMETRO FROM PARAMETRO13 WHERE COD_EMPRESA = $cod_empresa order by DES_PARAMETRO ";
										$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
										while($qrParam = mysqli_fetch_assoc($arrayQuery)){

									?>

										<option value="<?=$qrParam[COD_PARAMETRO]?>"><?=$qrParam['DES_PARAMETRO']?></option>

									<?php

										}

									?>
								</select>															
								<div class="help-block with-errors"></div>
								<?php

									$sql = "SELECT COD_ATRIBUTO FROM ATRIBUTOS_PRODUTOPERSONA 
											WHERE COD_PERSONA = $cod_persona 
											AND COD_EMPRESA = $cod_empresa 
											AND TIP_ATRIBUTO = 13";

									$qrAttr = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));

								?>
								<script>
									$('#ATRIBUTO13').val('<?=fnLimpaCampoZero($qrAttr[COD_ATRIBUTO])?>').trigger('chosen:updated');
								</script>
							</div>
						</div> 
						
					</div>

					<div class="push10"></div>
					
					<div class="row">
					
						<div class="col-md-8">
							<label for="inputName" class="control-label required">Produto </label>
							<div class="input-group">
							<span class="input-group-btn">
							<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary addBox" data-url="action.php?mod=<?php echo fnEncode(1247)?>&id=<?php echo fnEncode($cod_empresa)?>&idc=<?php echo fnEncode($cod_campanha)?>&pop=true" data-title="Vantagens Extras - Busca Produtos"><i class="fa fa-search" aria-hidden="true"></i></a>
							</span>
							<input type="text" name="DES_PRODUTO" id="DES_PRODUTO" class="form-control input-sm leituraOff" style="border-radius: 0 3px 3px  0;" readonly="readonly" placeholder="Procurar produto específico...">
							<input type="hidden" name="COD_PRODUTO" id="COD_PRODUTO" value="0">
							<input type="hidden" name="COD_PERPROD" id="COD_PERPROD" value="0">
							</div>																
						</div>
								
						<div class="col-md-4">
							<div class="form-group">
								<label for="inputName" class="control-label">Fornecedor</label>
									<select data-placeholder="Selecione o grupo" name="BL2_COD_FORNECEDOR" id="BL2_COD_FORNECEDOR" class="chosen-select-deselect">
										<option value="0">&nbsp;</option>											  
										<?php
											$sql = "select * from FORNECEDORMRKA where COD_EMPRESA = $cod_empresa order by NOM_FORNECEDOR";
											$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
											
											while ($qrListaCategoria = mysqli_fetch_assoc($arrayQuery))
											  {														
												echo"
													  <option value='".$qrListaCategoria['COD_FORNECEDOR']."'>".$qrListaCategoria['NOM_FORNECEDOR']."</option> 
													"; 
												  }	
										?>
									</select>	
								<div class="help-block with-errors"></div>
							</div>
						</div>							
						<div class="push10"></div> 
						
						<div class="col-md-6">
							<div class="form-group">
								<label for="inputName" class="control-label">Grupo do Produto</label>
									<select data-placeholder="Selecione o grupo" name="BL2_COD_CATEGOR" id="BL2_COD_CATEGOR" class="chosen-select-deselect">
										<option value="0">&nbsp;</option>											  
										<?php
											$sql = "select * from CATEGORIA where COD_EMPRESA = $cod_empresa AND COD_EXCLUSA is null order by DES_CATEGOR";
											$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
											
											while ($qrListaCategoria = mysqli_fetch_assoc($arrayQuery))
											  {														
												echo"
													  <option value='".$qrListaCategoria['COD_CATEGOR']."'>".$qrListaCategoria['DES_CATEGOR']."</option> 
													"; 
												  }	
										?>
									</select>	
								<div class="help-block with-errors"></div>
							</div>
						</div>											
						
						<div class="col-md-6">
							<div class="form-group">
								<label for="inputName" class="control-label">Sub Grupo do Produto</label>
									<div id="divId_sub">
									<select data-placeholder="Selecione o sub grupo" name="BL2_COD_SUBCATE" id="BL2_COD_SUBCATE" class="chosen-select-deselect">
										<option value="0">&nbsp;</option>					
									</select>	
									</div>	
								<div class="help-block with-errors"></div>
							</div>
						</div>
						
					</div>
						
															
					<div class="push10"></div>
					<hr>	
					<div class="form-group text-right col-lg-12">
						
						  <button type="button" class="btn btn-success atualiza pull-left" <?php echo $bloqueiaAlt; ?> ><i class="fa fa-check" aria-hidden="true"></i>&nbsp; Aplicar Filtros</button>
						
						  <button type="button" class="btn btn-default limpaProduto"><i class="far fa-star-half-alt" aria-hidden="true"></i>&nbsp; Limpar Bloco</button>
						  <button type="button" name="CAD" id="CAD" class="btn btn-primary getBtn addCadProd" <?php echo $bloqueiaAlt; ?> ><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
						  <button type="button" name="ALT" id="ALT" class="btn btn-primary getBtn addCadProd" <?php echo $bloqueiaAlt; ?> ><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Alterar</button>
						  <button type="button" name="EXC" id="EXC" class="btn btn-primary getBtn" <?php echo $bloqueiaAlt; ?> ><i class="fa fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>
						
					</div>
															
					<div class="push50"></div>
					
					<div class="col-lg-12">

						<div class="no-more-tables">
					
							<table class="table table-bordered table-striped table-hover">
							  <thead>
								<tr>
								  <th class="text-center" width="40"><small>Todos</small><br><input type='checkbox' id="selectAll"></th>
								  <th><small>Cód.</small></th>
								  <th><small>Cód. Ext.</small></th>
								  <th><small>Produto</small></th>
								  <th><small>Fornecedor</small></th>
								  <th><small>Categoria</small></th>
								  <th><small>Sub Categoria</small></th>
								  <th><small>Chave</small></th>
								</tr>
							  </thead>
							<tbody id="tablePersonasProdutos">
							  
							<?php								
							
								$sql = "SELECT 
										personas_produtos.COD_PERPROD,
										personas_produtos.COD_PRODUTO,
										personas_produtos.COD_FORNECEDOR,
										personas_produtos.COD_CATEGOR,
										personas_produtos.COD_SUBCATE,
										personas_produtos.DES_CHAVE,

										(SELECT DES_PRODUTO
										 FROM produtocliente
										WHERE produtocliente.COD_PRODUTO = personas_produtos.COD_PRODUTO) as DES_PRODUTO,
				
										(SELECT COD_EXTERNO
										 FROM produtocliente
										WHERE produtocliente.COD_PRODUTO = personas_produtos.COD_PRODUTO) as COD_EXTERNO,
										
										(SELECT NOM_FORNECEDOR
										 FROM fornecedormrka
										WHERE fornecedormrka.COD_FORNECEDOR = personas_produtos.COD_FORNECEDOR) as NOM_FORNECEDOR,
										 
										(SELECT DES_CATEGOR
										 FROM categoria
										WHERE categoria.COD_CATEGOR = personas_produtos.COD_CATEGOR) as DES_CATEGOR,  	 
										 
										(SELECT DES_SUBCATE
										 FROM subcategoria
										WHERE subcategoria.COD_SUBCATE = personas_produtos.COD_SUBCATE) as DES_SUBCATE     

									FROM personas_produtos 
									where COD_PERSONA = $cod_persona 
									AND COD_EMPRESA = $cod_empresa
									ORDER BY COD_PERPROD DESC";
										
								// fnEscreve($sql);
								$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
																					
								$count=0;
								while ($qrListaPersonasProdutos = mysqli_fetch_assoc($arrayQuery))
								  {														  
									$count++;	
									echo"
										<tr>
										  <td class='text-center'><input type='checkbox' name='radio_$count' onclick='retornaFormPersonas(".$count.")'>&nbsp;</td>
										  <td><small>".$qrListaPersonasProdutos['COD_PERPROD']."</small></td>
										  <td><small>".$qrListaPersonasProdutos['COD_EXTERNO']."</small></td>
										  <td><small>".$qrListaPersonasProdutos['DES_PRODUTO']."</small></td>
										  <td><small>".$qrListaPersonasProdutos['NOM_FORNECEDOR']."</small></td>
										  <td><small>".$qrListaPersonasProdutos['DES_CATEGOR']."</small></td>
										  <td><small>".$qrListaPersonasProdutos['DES_SUBCATE']."</small></td>
										  <td><small>".$qrListaPersonasProdutos['DES_CHAVE']."</small></td>
										</tr>
										<input type='hidden' id='ret_COD_PERPROD_".$count."' value='".$qrListaPersonasProdutos['COD_PERPROD']."'>
										<input type='hidden' id='ret_COD_PRODUTO_".$count."' value='".$qrListaPersonasProdutos['COD_PRODUTO']."'>
										<input type='hidden' id='ret_DES_PRODUTO_".$count."' value='".$qrListaPersonasProdutos['DES_PRODUTO']."'>
										<input type='hidden' id='ret_COD_FORNECEDOR_".$count."' value='".$qrListaPersonasProdutos['COD_FORNECEDOR']."'>
										<input type='hidden' id='ret_COD_CATEGOR_".$count."' value='".$qrListaPersonasProdutos['COD_CATEGOR']."'>
										<input type='hidden' id='ret_COD_SUBCATE_".$count."' value='".$qrListaPersonasProdutos['COD_SUBCATE']."'>
										<input type='hidden' id='ret_DES_CHAVE_".$count."' value='".$qrListaPersonasProdutos['DES_CHAVE']."'>
										"; 
									  }	
									  
							?>
								
							</tbody>
							</table>

						</div>
						
					</div>		
						
					<div class="push20"></div> 
										
	
	<script type="text/javascript">

		var listaProdutos = [];
	
		$(document).ready(function(){

			$("#BL2_COD_CATEGOR").chosen({ width: "100%" }); 
			$("#BL2_COD_SUBCATE").chosen({ width: "100%" }); 
			$("#BL2_COD_FORNECEDOR").chosen({ width: "100%" });

			$('#popModalAux').on('hidden.bs.modal', function () {
				atualizarTable();
			})	
		});	
		
		
		$(".limpaProduto").click(function() { 
			$("#DES_PRODUTO").val("");
			$("#COD_PRODUTO").val("0");
			$("#COD_PERPROD").val("0");
			$("#BL2_COD_FORNECEDOR").val("").trigger("chosen:updated");
			$("#BL2_COD_CATEGOR").val("").trigger("chosen:updated");
			$("#BL2_COD_SUBCATE").val("").trigger("chosen:updated");
			$("#notificaProdutos").hide();
		});
		
		// ajax
		$("#BL2_COD_CATEGOR").change(function () {
			var codBusca = $("#BL2_COD_CATEGOR").val();
			var codBusca3 = $("#COD_EMPRESA").val();
			buscaSubCat(codBusca,0,codBusca3);
		});
		
		
		$(".getBtn").click(function() {

			if($(this).attr('id') == 'EXC'){		
				
				$.ajax({
					type: "POST",
					url: "ajxPersonasProdutos.php?acao=excProdutos&cod_empresa=<?php echo $cod_empresa; ?>",
					data:{listaProdutos:JSON.stringify(listaProdutos)},
					success:function(data){
						console.log(data);
						$.confirm({
							title: '<small>Sucesso</small>',
							type: 'green',
							icon: 'fa fa-check-square-o',
							content: 'Produtos excluídos com sucesso!',					
							buttons: {
								fechar: function () {
									atualizarTable();
								}									
							}
						});	
					},
					error:function(){
					}
				});

			}else{
				$.ajax({
					method: "POST",
					url: 'ajxPersonasProdutos.php?acao=proc&opcao=' + $(this).attr('name') + '&cod_empresa=' + $("#COD_EMPRESA").val(),
					data: $('#formulario').serialize(),
					beforeSend:function(){
						//$('#divId_sub').html('<div class="loading" style="width: 100%;"></div>');
					},
					success:function(data){
						console.log(data); 
						//alert('cadastro feito com sucesso');
						$.confirm({
							title: '<small>Produtos da Persona</small>',
							type: 'green',
							icon: 'fa fa-check-square-o',
							content: 'Registro atualizado com sucesso!',							
							buttons: {
								fechar: function () {
									atualizarTable();
								}									
							}
						});	
						
					},
					error:function(){
						//$('#divId_sub').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
					}
				});
			}
		});

		$('#selectAll').click(function () {
		    $(this).closest('table').find('td input:checkbox').prop('checked', this.checked);
		    attListaProdutos();
		});

		function attListaProdutos(){
			listaProdutos = [];
			$("table tr").each(function(index) {
				if($(this).find("input[type='checkbox']:not('#selectAll')").is(':checked')){
					var codigo = $(this).find("input[type='checkbox']").attr('name').replace('radio_', '');
					listaProdutos.push($("#ret_COD_PERPROD_"+index).val());
				}
			});
			if(listaProdutos == ''){
				$.each(listaProdutos, function (index, value) {
					//alert(index);
			        if(index>0){
			        	$('.addCadProd').prop('disabled',true);
			        }
			        else{
			        	$('.addCadProd').prop('disabled',false);
			        }
			    });
			}else{
				// alert('vazio');
			}
		}

		function atualizarTable(){
			$.ajax({
				method: "GET",
				url: "ajxPersonasProdutos.php",
				data: { acao: 'consulta', cod_empresa: $("#COD_EMPRESA").val(), cod_persona: $("#COD_PERSONA").val()},
				beforeSend:function(){
					$('#tablePersonasProdutos').html('<div class="loading" style="width: 100%;"></div>');
				},
				success:function(data){
					console.log(data);
					$("#tablePersonasProdutos").html(data); 
				},
				error:function(){
					$('#tablePersonasProdutos').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
				}
			});			
		}

		function buscaSubCat(idCat,idSub,idEmp) {
			$.ajax({
				method: "POST",
				url: "ajxBuscaSubGrupoPersonasProdutos.php",
				data: { ajx1:idCat,ajx2:idSub,ajx3:idEmp},
				beforeSend:function(){
					$('#divId_sub').html('<div class="loading" style="width: 100%;"></div>');
				},
				success:function(data){
					$("#divId_sub").html(data); 
				},
				error:function(){
					$('#divId_sub').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
				}
			});		
		}		
		
	
		function carregarCombo(idCat){
			$.ajax({
				method: "POST",
				async:false,
				url: "ajxBuscaSubGrupoPersonasProdutos.php",
				data: { ajx1:idCat,ajx2: 0,ajx3:$("#COD_EMPRESA").val()},
				beforeSend:function(){
					$('#divId_sub').html('<div class="loading" style="width: 100%;"></div>');
				},
				success:function(data){
					$("#divId_sub").html(data); 
					// console.log(data);
				},
				error:function(){
					$('#divId_sub').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
				}
			});
		}

		function gravaAtributos(el,tip_atributo){
			$.ajax({
				method: 'POST',
				url: 'ajxSalvaAtributosPersona.do?id=<?=fnEncode($cod_empresa)?>',
				data: {COD_PERSONA: "<?=fnEncode($cod_persona)?>", COD_ATRIBUTO: $(el).val(), TIP_ATRIBUTO: tip_atributo},
				success:function(data){
					console.log(data);
				}
			});
		}
	
		function retornaFormPersonas(index){			
			$("#formulario #COD_PERPROD").val($("#ret_COD_PERPROD_"+index).val());
			$("#formulario #COD_PRODUTO").val($("#ret_COD_PRODUTO_"+index).val()).trigger("chosen:updated");
			$("#formulario #DES_PRODUTO").val($("#ret_DES_PRODUTO_"+index).val());
			$("#formulario #BL2_COD_FORNECEDOR").val($("#ret_COD_FORNECEDOR_"+index).val()).trigger("chosen:updated");
			$("#formulario #BL2_COD_CATEGOR").val($("#ret_COD_CATEGOR_"+index).val()).trigger("chosen:updated");
			buscaSubCat($("#ret_COD_CATEGOR_"+index).val(),$("#ret_COD_SUBCATE_"+index).val(),$("#COD_EMPRESA").val());
			$("#formulario #BL2_COD_SUBCATE").val($("#ret_COD_SUBCATE_"+index).val()).trigger("chosen:updated");
			attListaProdutos();
		}
		
	</script>