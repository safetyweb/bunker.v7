<?php 

	include '_system/_functionsMain.php'; 

	$cod_empresa = fnLimpaCampoZero(fnDecode($_GET['id']));

?>

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
			<select data-placeholder="Opções de <?php echo strtolower ($lblAtributo1); ?>" name="ATRIBUTO1" id="ATRIBUTO1" class="chosen-select-deselect" style="width:100%;" tabindex="1">
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
		</div>
	</div>

	<?php if ($lblAtributo2 != "") { $atribObrig2 = ""; $hide2 = "";} else { $atribObrig2 = ""; $hide2 = "hidden";} ?>
	<div class="col-md-3 <?php echo $hide2; ?>">
		<div class="form-group">
			<label for="inputName" class="control-label <?php echo $atribObrig2; ?>"><?php echo $lblAtributo2; ?> </label>
			<!--<input type="text" class="form-control input-sm" name="ATRIBUTO2" id="ATRIBUTO2" maxlength="20" value="<?php echo $atributo2; ?>" <?php echo $atribObrig2; ?>>-->
			<select data-placeholder="Opções de <?php echo strtolower ($lblAtributo2); ?>" name="ATRIBUTO2" id="ATRIBUTO2" class="chosen-select-deselect" style="width:100%;" tabindex="1">
				<option value=""></option>
				<?php 

				$sql = "SELECT COD_PARAMETRO, DES_PARAMETRO FROM PARAMETRO2 WHERE COD_EMPRESA = $cod_empresa";
				$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
				while($qrParam = mysqli_fetch_assoc($arrayQuery)){

					?>

					<option value="<?=$qrParam[COD_PARAMETRO]?>"><?=$qrParam['DES_PARAMETRO']?></option>

					<?php

				}

				?>
			</select>															
			<div class="help-block with-errors"></div>
		</div>
	</div>

	<?php if ($lblAtributo3 != "") { $atribObrig3 = ""; $hide3 = "";} else { $atribObrig3 = ""; $hide3 = "hidden";} ?>
	<div class="col-md-3 <?php echo $hide3; ?>">
		<div class="form-group">
			<label for="inputName" class="control-label <?php echo $atribObrig3; ?>"><?php echo $lblAtributo3; ?> </label>
			<!--<input type="text" class="form-control input-sm" name="ATRIBUTO3" id="ATRIBUTO3" maxlength="30" value="<?php echo $atributo3; ?>" <?php echo $atribObrig3; ?>>-->
			<select data-placeholder="Opções de <?php echo strtolower ($lblAtributo3); ?>" name="ATRIBUTO3" id="ATRIBUTO3" class="chosen-select-deselect" style="width:100%;" tabindex="1">
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
		</div>
	</div>	

	<?php if ($lblAtributo4 != "") { $atribObrig4 = ""; $hide4 = "";} else { $atribObrig4 = ""; $hide4 = "hidden";} ?>
	<div class="col-md-3 <?php echo $hide4; ?>">
		<div class="form-group">
			<label for="inputName" class="control-label <?php echo $atribObrig4; ?>"><?php echo $lblAtributo4; ?> </label>
			<!--<input type="text" class="form-control input-sm" name="ATRIBUTO4" id="ATRIBUTO4" maxlength="40" value="<?php echo $atributo4; ?>" <?php echo $atribObrig4; ?>>-->
			<select data-placeholder="Opções de <?php echo strtolower ($lblAtributo4); ?>" name="ATRIBUTO4" id="ATRIBUTO4" class="chosen-select-deselect" style="width:100%;" tabindex="1">
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
		</div>
	</div>

	<div class="push10"></div>													

	<?php if ($lblAtributo5 != "") { $atribObrig5 = ""; $hide5 = "";} else { $atribObrig5 = ""; $hide5 = "hidden";} ?>
	<div class="col-md-3 <?php echo $hide5; ?>">
		<div class="form-group">
			<label for="inputName" class="control-label <?php echo $atribObrig5; ?>"><?php echo $lblAtributo5; ?> </label>
			<!--<input type="text" class="form-control input-sm" name="ATRIBUTO5" id="ATRIBUTO5" maxlength="50" value="<?php echo $atributo5; ?>" <?php echo $atribObrig5; ?>>-->
			<select data-placeholder="Opções de <?php echo strtolower ($lblAtributo5); ?>" name="ATRIBUTO5" id="ATRIBUTO5" class="chosen-select-deselect" style="width:100%;" tabindex="1">
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
		</div>
	</div>	

	<?php if ($lblAtributo6 != "") { $atribObrig6 = ""; $hide6 = "";} else { $atribObrig6 = ""; $hide6 = "hidden";} ?>
	<div class="col-md-3 <?php echo $hide6; ?>">
		<div class="form-group">
			<label for="inputName" class="control-label <?php echo $atribObrig6; ?>"><?php echo $lblAtributo6; ?> </label>
			<!--<input type="text" class="form-control input-sm" name="ATRIBUTO6" id="ATRIBUTO6" maxlength="60" value="<?php echo $atributo6; ?>" <?php echo $atribObrig6; ?>>-->
			<select data-placeholder="Opções de <?php echo strtolower ($lblAtributo6); ?>" name="ATRIBUTO6" id="ATRIBUTO6" class="chosen-select-deselect" style="width:100%;" tabindex="1">
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
		</div>
	</div>	

	<?php if ($lblAtributo7 != "") { $atribObrig7 = ""; $hide7 = "";} else { $atribObrig7 = ""; $hide7 = "hidden";} ?>
	<div class="col-md-3 <?php echo $hide7; ?>">
		<div class="form-group">
			<label for="inputName" class="control-label <?php echo $atribObrig7; ?>"><?php echo $lblAtributo7; ?> </label>
			<!--<input type="text" class="form-control input-sm" name="ATRIBUTO7" id="ATRIBUTO7" maxlength="70" value="<?php echo $atributo7; ?>" <?php echo $atribObrig7; ?>>-->
			<select data-placeholder="Opções de <?php echo strtolower ($lblAtributo7); ?>" name="ATRIBUTO7" id="ATRIBUTO7" class="chosen-select-deselect" style="width:100%;" tabindex="1">
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
		</div>
	</div>														

	<?php if ($lblAtributo8 != "") { $atribObrig8 = ""; $hide8 = "";} else { $atribObrig8 = ""; $hide8 = "hidden";} ?>
	<div class="col-md-3 <?php echo $hide8; ?>">
		<div class="form-group">
			<label for="inputName" class="control-label <?php echo $atribObrig8; ?>"><?php echo $lblAtributo8; ?> </label>
			<!--<input type="text" class="form-control input-sm" name="ATRIBUTO8" id="ATRIBUTO8" maxlength="80" value="<?php echo $atributo8; ?>" <?php echo $atribObrig8; ?>>-->
			<select data-placeholder="Opções de <?php echo strtolower ($lblAtributo8); ?>" name="ATRIBUTO8" id="ATRIBUTO8" class="chosen-select-deselect" style="width:100%;" tabindex="1">
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
		</div>
	</div>	

	<?php if ($lblAtributo9 != "") { $atribObrig9 = ""; $hide9 = "";} else { $atribObrig9 = ""; $hide9 = "hidden";} ?>
	<div class="col-md-3 <?php echo $hide9; ?>">
		<div class="form-group">
			<label for="inputName" class="control-label <?php echo $atribObrig9; ?>"><?php echo $lblAtributo9; ?> </label>
			<!--<input type="text" class="form-control input-sm" name="ATRIBUTO9" id="ATRIBUTO9" maxlength="90" value="<?php echo $atributo9; ?>" <?php echo $atribObrig9; ?>>-->
			<select data-placeholder="Opções de <?php echo strtolower ($lblAtributo9); ?>" name="ATRIBUTO9" id="ATRIBUTO9" class="chosen-select-deselect" style="width:100%;" tabindex="1">
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
		</div>
	</div>														

	<?php if ($lblAtributo10 != "") { $atribObrig10 = ""; $hide10 = "";} else { $atribObrig10 = ""; $hide10 = "hidden";} ?>
	<div class="col-md-3 <?php echo $hide10; ?>">
		<div class="form-group">
			<label for="inputName" class="control-label <?php echo $atribObrig10; ?>"><?php echo $lblAtributo10; ?> </label>
			<!--<input type="text" class="form-control input-sm" name="ATRIBUTO10" id="ATRIBUTO10" maxlength="100" value="<?php echo $atributo10; ?>" <?php echo $atribObrig10; ?>>-->
			<select data-placeholder="Opções de <?php echo strtolower ($lblAtributo10); ?>" name="ATRIBUTO10" id="ATRIBUTO10" class="chosen-select-deselect" style="width:100%;" tabindex="1">
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
		</div>
	</div>

	<?php if ($lblAtributo11 != "") { $atribObrig11 = ""; $hide11 = "";} else { $atribObrig11 = ""; $hide11 = "hidden";} ?>
	<div class="col-md-3 <?php echo $hide11; ?>">
		<div class="form-group">
			<label for="inputName" class="control-label <?php echo $atribObrig11; ?>"><?php echo $lblAtributo11; ?> </label>
			<!--<input type="text" class="form-control input-sm" name="ATRIBUTO11" id="ATRIBUTO11" maxlength="110" value="<?php echo $atributo11; ?>" <?php echo $atribObrig11; ?>>-->
			<select data-placeholder="Opções de <?php echo strtolower ($lblAtributo11); ?>" name="ATRIBUTO11" id="ATRIBUTO11" class="chosen-select-deselect" style="width:100%;" tabindex="1">
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
		</div>
	</div>

	<?php if ($lblAtributo12 != "") { $atribObrig12 = ""; $hide12 = "";} else { $atribObrig12 = ""; $hide12 = "hidden";} ?>
	<div class="col-md-3 <?php echo $hide12; ?>">
		<div class="form-group">
			<label for="inputName" class="control-label <?php echo $atribObrig12; ?>"><?php echo $lblAtributo12; ?> </label>
			<!--<input type="text" class="form-control input-sm" name="ATRIBUTO12" id="ATRIBUTO12" maxlength="120" value="<?php echo $atributo12; ?>" <?php echo $atribObrig12; ?>>-->
			<select data-placeholder="Opções de <?php echo strtolower ($lblAtributo12); ?>" name="ATRIBUTO12" id="ATRIBUTO12" class="chosen-select-deselect" style="width:100%;" tabindex="1">
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
		</div>
	</div>	

	<?php if ($lblAtributo13 != "") { $atribObrig13 = ""; $hide13 = "";} else { $atribObrig13 = ""; $hide13 = "hidden";} ?>
	<div class="col-md-3 <?php echo $hide13; ?>">
		<div class="form-group">
			<label for="inputName" class="control-label <?php echo $atribObrig13; ?>"><?php echo $lblAtributo13; ?> </label>
			<!--<input type="text" class="form-control input-sm" name="ATRIBUTO13" id="ATRIBUTO13" maxlength="130" value="<?php echo $atributo12; ?>" <?php echo $atribObrig13; ?>>-->
			<select data-placeholder="Opções de <?php echo strtolower ($lblAtributo13); ?>" name="ATRIBUTO13" id="ATRIBUTO13" class="chosen-select-deselect" style="width:100%;" tabindex="1">
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
		</div>
	</div> 

</div>

<script type="text/javascript">
	$(".chosen-select-deselect").chosen({
        allow_single_deselect: true,
        width: "100%"
    });
</script>