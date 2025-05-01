<?php
	
	//echo fnDebug('true');
	
	$hashLocal = mt_rand();
	$cod_filtro = "";
	$cod_tpfiltro = "";
	$cod_clientes_filtro = "";
	$temFiltro = 'N';
	$check_titularIni = "checked";
	$check_statusIni= "checked";
	$check_prestadorIni = "checked";
	
	if(isset($_POST['COD_EMPRESA']))
	{

		$cod_empresa = fnLimpacampo(fnDecode($_REQUEST['COD_EMPRESA']));
		$cod_empresaCode = fnLimpacampo($_REQUEST['COD_EMPRESA']);
		$cod_cliente  = fnLimpacampo($_REQUEST['COD_CLIENTE']);
		$cod_externo  = fnLimpacampoZero($_REQUEST['COD_EXTERNO']);
		$nom_cliente  = fnLimpacampo($_REQUEST['NOM_CLIENTE']);	
		$num_cartao  = fnLimpacampo($_REQUEST['NUM_CARTAO']);	
		$des_superb  = fnLimpacampo($_REQUEST['DES_SUPERB']);	
		$num_cgcecpf  = fnLimpaDoc(fnLimpacampo($_REQUEST['NUM_CGCECPF']));
        if (empty($_REQUEST['LOG_TITULAR'])) {$log_titular='N';}else{$log_titular=$_REQUEST['LOG_TITULAR'];}
        if (empty($_REQUEST['LOG_PRESTADOR'])) {$log_prestador='N';}else{$log_prestador=$_REQUEST['LOG_PRESTADOR'];}
        if (empty($_REQUEST['LOG_TERMO'])) {$log_termo='N';}else{$log_termo=$_REQUEST['LOG_TERMO'];}
        if (empty($_REQUEST['LOG_ESTATUS'])) {$log_status='N';}else{$log_status='S';}
        $num_celular  = fnLimpacampo($_REQUEST['NUM_CELULAR']);	
		$des_emailus  = fnLimpacampo(trim($_REQUEST['DES_EMAILUS']));
		$cod_indicad = fnLimpaCampo($_REQUEST['COD_INDICAD']);
		$pagina  = fnLimpacampo($_REQUEST['pagina']);
		$count_filtros = fnLimpacampo($_REQUEST['COUNT_FILTROS']);
		$andFiltros = "";
		$des_tpfiltros = [];
		$colunas = "";
		$filtros = "";

		if ($_REQUEST['LOG_TITULAR'] == "S") {
			$check_titular = "checked";
			$check_titularIni = "";
		}else{
			$check_titular = "";
			$check_titularIni = "";
		}
	        
	        
		if ($_REQUEST['LOG_ESTATUS'] == "S") {
			$check_status = "checked";
			$check_statusIni = "";
		}else{
			$check_status = "";
			$check_statusIni = "";
		}

		

		if ($log_prestador == "S") {
			$check_prestador = "checked";
			$check_prestadorIni = "";
		}else{
			$check_prestador = "";
			$check_prestadorIni = "";
		}

		if ($log_termo == "S") {
			$check_termo = "checked";
			$check_termoIni = "";
		}else{
			$check_termo = "";
			$check_termoIni = "";
		}

		//array das unidades de venda
		if (isset($_POST['COD_UNIVEND'])) {
			$Arr_COD_UNIVEND = $_POST['COD_UNIVEND'];
			//print_r($Arr_COD_MULTEMP);			 

			for ($i = 0; $i < count($Arr_COD_UNIVEND); $i++) {
				$cod_univend = $cod_univend . $Arr_COD_UNIVEND[$i] . ",";
			}

			$cod_univend = substr($cod_univend, 0, -1);
		} else {
			$cod_univend = "0";
		}
                
                //fnEscreve($log_status);
		if($count_filtros != ""){

			for ($i=0; $i < $count_filtros; $i++) {

				$cod_filtro = "";

				if (isset($_POST["COD_FILTRO_$i"])){

					$Arr_COD_FILTRO = $_POST["COD_FILTRO_$i"];

					if(fnLimpacampo($_POST["COD_TPFILTRO_$i"]) != ''){

						$cod_filtro = $cod_filtro.fnLimpacampo($_POST["COD_TPFILTRO_$i"]).":";

					}

				    for ($j=0;$j<count($Arr_COD_FILTRO);$j++){

						$cod_filtro = $cod_filtro.$Arr_COD_FILTRO[$j].",";
						$filtros = $filtros.$Arr_COD_FILTRO[$j].",";

				    }

				}

				if($_POST["COD_FILTRO_$i"] != ''){

					$cod_filtro = rtrim($cod_filtro,',');

					$filtros_div = explode(':', $cod_filtro);

					$cod_tpfiltro = $filtros_div[0];
					$cod_filtros = $filtros_div[1];

					$sql = "SELECT DES_TPFILTRO FROM TIPO_FILTRO WHERE COD_TPFILTRO = $cod_tpfiltro";
					$qrTipo = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));
					array_push($des_tpfiltros, $qrTipo['DES_TPFILTRO']);
					$campo = explode(' ',strtoupper(fnacentos($qrTipo['DES_TPFILTRO'])));

					$colunas .= $campo[0].$i.".DES_FILTRO AS $campo[0],";

					

					$innerJoin .= "
								  INNER JOIN CLIENTE_FILTROS ".$campo[0]." ON ".$campo[0].".COD_FILTRO IN($cod_filtros) AND ".$campo[0].".COD_CLIENTE=CL.COD_CLIENTE 
								  INNER JOIN FILTROS_CLIENTE ".$campo[0].$i." ON ".$campo[0].".COD_FILTRO = ".$campo[0].$i.".COD_FILTRO
					";

					

				}	

			}

			$filtros = rtrim(ltrim($filtros,','),',');	

		}

		if ($_SESSION['SYS_COD_EMPRESA'] == 2) {
			// fnEscreve($andClientes);
		// fnConsoleLog($cod_clientes_filtro);
		}
	 
	}else{ 

		$cod_empresaCode = "";
		$cod_cliente  = "";
		$nom_cliente  = "";
		$num_cartao  = "";
		$num_cgcecpf  = "";
		$des_superb = "";
		$pagina  = "1";
		$andClientes = "";
		
	}

	if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))){
		//busca dados da empresa
		$cod_empresa = fnDecode($_GET['id']);
		$mod = fnDecode($_GET['mod']);
	}

	$sqlInd = "SELECT COD_PERFILS, COD_INDICADOR FROM USUARIOS WHERE COD_USUARIO = $_SESSION[SYS_COD_USUARIO]";
	$qrUsu = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(),trim($sqlInd)));
	// fnEscreve($cod_empresa);

	if($mod == 1424){

		if($qrUsu['COD_PERFILS'] == 1154){
			$cod_indicad = $qrUsu['COD_INDICADOR'];
			$disableCombo = "disabled";
		}else{
			$disableCombo = "";
		}

	}

	if($log_externo == 'S'){
		$check_externo = 'checked';
	}else{
		$check_externo = '';
	}
	// echo($log_externo);
	// fnEscreve($DestinoPg);
  
	//fnEscreve(fnLimpacampo(fnDecode('Oh5QUTtPIOs¢'))); 	
	//fnEscreve(fnEncode($_SESSION["SYS_COD_EMPRESA"])); 	
	//fnEscreve($num_cgcecpf); 	
	//fnMostraForm();
	
	// echo($check_prestador);

	if($cod_empresa == 332){
		if($_SESSION['SYS_COD_USUARIO'] != "11478"){
			$andUnivendCombo = 'and cod_univend in(' . $_SESSION['SYS_COD_UNIVEND']. ')';
		}else{
			$andUnivendCombo = '';
		}
	}
?>
<style>
    #ICONE{
        position: relative;
        left: 215px;
        top: -25px
    }
</style>
			
					<div class="push30"></div> 
					
					<div class="row">				
					
						<div class="col-md12 margin-bottom-30">
							<!-- Portlet -->
							<div class="portlet portlet-bordered">
								<div class="portlet-title">
									<div class="caption">
										<i class="fal fa-terminal"></i>
										<span class="text-primary"><?php echo $NomePg ?></span>
									</div>
									<?php include "atalhosPortlet.php"; ?>
								</div>
								<div class="portlet-body">
									
									<?php if ($msgRetorno <> '') { ?>	
									<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
									<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									 <?php echo $msgRetorno; ?>
									</div>
									<?php } ?>	
								
									<div class="login-form">
                                        
										<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>"> 
																				
											<fieldset>
												<legend>Dados Gerais</legend> 

												<div class="row">
													
													<div class="col-md-2">   
														<div class="form-group">
															<label for="inputName" class="control-label">Somente Cadastros Titulares</label> 
															<div class="push5"></div>
															<label class="switch">
																<input type="checkbox" name="LOG_TITULAR" id="LOG_TITULAR" class="switch" value="S" <?=$check_titular." ".$check_titularIni ?>>
																<span></span>
															</label>
														</div>
													</div>

													<div class="col-md-2">   
														<div class="form-group">
															<label for="inputName" class="control-label">Status</label> 
															<div class="push5"></div>
															<label class="switch">
																<input type="checkbox" name="LOG_ESTATUS" id="LOG_ESTATUS" class="switch" value="S" <?=$check_status." ".$check_statusIni ?>>
																<span></span>
															</label>
														</div>
													</div>

													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label">Cod. Colaborador</label>
															<input type="text" class="form-control input-sm"  name="COD_CLIENTE" id="COD_CLIENTE" value="<?php echo $cod_cliente; ?>">
															<div class="help-block with-errors"></div>
														</div>

													</div>

													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">CPF/CNPJ</label>
															<input type="text" class="form-control input-sm cpfcnpj"  name="NUM_CGCECPF" id="NUM_CGCECPF" value="<?php echo $num_cgcecpf; ?>">
															<div class="help-block with-errors"></div>
														</div>

													</div>

													<div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label">Nome do Colaborador</label>
															<input type="text" class="form-control input-sm" name="NOM_CLIENTE" id="NOM_CLIENTE" maxlength="40" value="<?php echo $nom_cliente; ?>">
															<div class="help-block with-errors"></div>
														</div>
													</div>

												</div>

												<?php 

														 if($cod_empresa == 332){
													?>

														<div class="row">

															<div class="col-md-2">   
																<div class="form-group">
																	<label for="inputName" class="control-label">Somente Prestadores de Serviço</label> 
																	<div class="push5"></div>
																	<label class="switch">
																		<input type="checkbox" name="LOG_PRESTADOR" id="LOG_PRESTADOR" class="switch" value="S" <?=$check_prestador." ".$check_prestadorIni?>>
																		<span></span>
																	</label>
																</div>
															</div>

															<div class="col-xs-2 hidden-print">
																<div class="form-group">
																	<label for="inputName" class="control-label hidden-print">Contrato Assinado</label><br/>
																	<label class="switch">
																	<input type="checkbox" name="LOG_TERMO" id="LOG_TERMO" class="switch" value="S" <?=$check_termo." ".$check_termoIni ?>>
																	<span></span>
																	</label> 								
																	<div class="help-block with-errors"></div>
																</div>
																						
															</div>

															<div class="col-md-3">
																<div class="form-group">
																	<label for="inputName" class="control-label required">Campanhas</label>

																	<select data-placeholder="Selecione uma ou mais campanhas" name="COD_UNIVEND[]" id="COD_UNIVEND" multiple="multiple" class="chosen-select-deselect requiredChk" style="width:100%;" tabindex="1" required>
																		<?php
																		$sql = "SELECT COD_UNIVEND, NOM_FANTASI, LOG_ESTATUS FROM UNIDADEVENDA WHERE COD_EMPRESA = $cod_empresa $andUnivendCombo AND LOG_ESTATUS = 'S' AND DAT_EXCLUSA IS NULL ORDER BY NOM_FANTASI ";
																		$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
																		while ($qrListaUnive = mysqli_fetch_assoc($arrayQuery)) {

																			if ($qrListaUnive['LOG_ESTATUS'] == 'N') {
																				$disabled = "disabled";
																			} else {
																				$disabled = " ";
																			}

																			echo "
																				<option value='" . $qrListaUnive['COD_UNIVEND'] . "'" . $disabled . ">" . ucfirst($qrListaUnive['NOM_FANTASI']) . "</option> 
																			";
																		}
																		?>
																	</select>
																	<?php //fnEscreve($sql); 
																	?>
																	<div class="help-block with-errors"></div>

																	<a class="btn btn-default btn-sm" id="iAll" style="padding: 0 2px ; font-size: 10px;"><i class="fa fa-check-square" aria-hidden="true"></i> selecionar todos</a>&nbsp;
																	<a class="btn btn-default btn-sm" id="iNone" style="padding: 0 2px ; font-size: 10px;"><i class="fa fa-square-o" aria-hidden="true"></i>&nbsp; deselecionar todos</a>

																</div>
															</div>

															<div class="col-md-2">
																<div class="form-group">
																	<label for="inputName" class="control-label">Cod. Externo</label>
																	<input type="tel" class="form-control input-sm" name="COD_EXTERNO" id="COD_EXTERNO" maxlength="40" value="<?php echo $cod_externo; ?>">
																	<div class="help-block with-errors"></div>
																</div>
															</div>

														</div>

													<?php 
														}

													?>
												
											</fieldset>	
										
										
										
										<?php
											//FILTROS DINÂMICOS
											$countFiltros = 0;

											if($mod == 1424){
										?>
											
											<div class="push20"></div>
											
											<!-- filtros dinâmicos -->
										
											<fieldset>
												<legend>Filtros Dinâmicos</legend>

												<?php
													//FILTROS DINÂMICOS
													$countFiltros = 0;

													$sql = "SELECT COD_TPFILTRO, DES_TPFILTRO FROM TIPO_FILTRO
													WHERE COD_EMPRESA = $cod_empresa
													ORDER BY NUM_ORDENAC";
													$arrayQuery = mysqli_query(connTemp($cod_empresa,''),trim($sql));

													if(mysqli_num_rows($arrayQuery) > 0){
													
													$countObjeto = 0
												?>
															
												<div class="row">
																
												<?php 
													while($qrTipo = mysqli_fetch_assoc($arrayQuery)){
												?>

														<div class="col-xs-3">
															<div class="form-group">
																<label for="inputName" class="control-label"><?=$qrTipo['DES_TPFILTRO']?></label>
																<div id="relatorioFiltro_<?=$countFiltros?>">
																	<input type="hidden" name="COD_TPFILTRO_<?=$countFiltros?>" id="COD_TPFILTRO_<?=$countFiltros?>" value="<?=$qrTipo['COD_TPFILTRO']?>">
																	<select data-placeholder="Selecione os filtros" name="COD_FILTRO_<?=$countFiltros?>[]" id="COD_FILTRO_<?=$qrTipo[COD_TPFILTRO]?>" multiple="multiple" class="chosen-select-deselect last-chosen-link">
																		<option value=""></option>
												<?php
																		$sqlFiltro = "SELECT COD_FILTRO, DES_FILTRO FROM FILTROS_CLIENTE
																					  WHERE COD_TPFILTRO = $qrTipo[COD_TPFILTRO]
																					  ORDER BY DES_FILTRO";

																		$arrayFiltros = mysqli_query(connTemp($cod_empresa,''),trim($sqlFiltro));
																		while($qrFiltros = mysqli_fetch_assoc($arrayFiltros)){
												?>

																			<option value="<?=$qrFiltros['COD_FILTRO']?>"><?=$qrFiltros['DES_FILTRO']?></option>

												<?php 
																		}

																		
																		$sqlChosen = "SELECT COD_FILTRO FROM FILTROS_PERSONA
																						WHERE COD_PERSONA = $cod_persona AND COD_TPFILTRO =".$qrTipo['COD_TPFILTRO'];
																		$arrayChosen = mysqli_query(connTemp($cod_empresa,''),$sqlChosen);
																		$cod_filtros = "";

																		while($qrChosen = mysqli_fetch_assoc($arrayChosen)){
																			$cod_filtros .= $qrChosen['COD_FILTRO'].",";
																		}
																		$cod_filtros = rtrim($cod_filtros,",");

																			
												?>
																		<script>
																			var filtros = '<?php echo $filtros; ?>';
																			
																			if(filtros != 0 && filtros != ""){

																				var sistemasUni = '<?php echo $filtros; ?>';				
																				var sistemasUniArr = sistemasUni.split(',');				
																				//opções multiplas
																				for (var i = 0; i < sistemasUniArr.length; i++) {
																				  $("#COD_FILTRO_<?=$qrTipo[COD_TPFILTRO]?> option[value=" + sistemasUniArr[i] + "]").prop("selected", "true");				  
																				}
																				$("#COD_FILTRO_<?=$qrTipo[COD_TPFILTRO]?>").trigger("chosen:updated");

																			}
																		</script>
															
																	</select>
																	<div class="help-block with-errors"></div>
																</div>
															</div>
														</div>

												<?php 	
														if($countObjeto == 3){
															$countObjeto = 0;
															echo '<div class="push10"></div>';
														}else{
															$countObjeto++;
														}
														$countFiltros++;
													}
												?>
														
													<div class="col-md-2">
														<div class="push20"></div>
														<button type="submit" name="BUS" id="BUS" class="btn btn-primary btn-sm btn-block getBtn"><i class="fa fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
													</div>
														
														
												</div>

																

													<?php 
														}
													?>										
												
											</fieldset>		
												

										<?php
											}
										?>										
										
										
										
										
																				
										<div class="push10"></div>
										<hr>	
										<div class="form-group text-right col-lg-12">
											
											  <button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
											  <button type="submit" name="ADD" id="ADD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Novo Cliente</button>
											  <button type="submit" name="BUS" id="BUS" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Pesquisar</button>
											
										</div>
										
										<?php
										if (!is_null($RedirectPg)) {
											$DestinoPg = fnEncode($RedirectPg);		
										}else {
											$DestinoPg = "";		
										}

										if($cod_empresa == 136){
											$DestinoPg = fnEncode(1423);
										}
										// else if($cod_empresa == 224){
										// 	$DestinoPg = fnEncode(1688);
										// }

										?>
										
										<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo fnEncode($cod_empresa); ?>">
										<input type="hidden" name="COUNT_FILTROS" id="COUNT_FILTROS" value="<?=$countFiltros?>">
										<input type="hidden" name="dId" id="dId" value="K2xr0lE3UHI¢">
										<input type="hidden" name="dKey" id="dKey" value="<?php echo fnEncode($cod_empresa); ?>">
										<input type="hidden" name="dUrl" id="dUrl" value="<?php echo $DestinoPg; ?>">
										<input type="hidden" name="pagina" id="pagina" value="<?php echo $pagina; ?>">
										<input type="hidden" name="opcao" id="opcao" value="">
										<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
                                                                                <input type="hidden" name="hHabilitado" id="hHabilitado" value="N">		
										
										<div class="push5"></div> 
										
										</form>
										
										<div class="push50"></div>
										
										<?php 
										//verifica post
										if(isset($_POST['opcao']))
										{
										
											if ($cod_empresa != 0 ){
												
												$pagina = (isset($_REQUEST['pagina']))? $_REQUEST['pagina'] : 1;

												if($mod == 1424){
													if($cod_indicad != ""){
														$andIndicad = "AND COD_INDICAD = $cod_indicad";
													}else{
														$andIndicad = "";
													}
												}else{
													$andIndicad = "";
												}
													
												if ($cod_cliente!=0){
													$andCodigo = 'and cod_cliente='.$cod_cliente; }
													else { $andCodigo = ' ';}
																								  
												if ($nom_cliente!=''){ 

													if($mod == 1424){
													 	$andNome = 'and nom_cliente like "%'.$nom_cliente.'%"';
													}else{
													 	$andNome = 'and nom_cliente like "'.$nom_cliente.'%"';	
													}

												}else{
													$andNome = ' '; 
												}  
													
												if ($num_cartao!=''){ 													
													 $andCartao = 'and num_cartao='.$num_cartao; }
													else {$andCartao = ' '; } 
													
												if ($num_cgcecpf!=''){ 
													 $andCpf = 'and num_cgcecpf ='.$num_cgcecpf; }
													else {$andCpf = ' '; }

												if ($des_emailus!=''){ 													
													$andEmail = 'and des_emailus="'.$des_emailus.'"'; }
												else {$andEmail = ' '; }

												if ($num_celular!=''){ 													
													 $andcelular = 'and num_celular="'.$num_celular.'"'; }
												else {$andCelular = ' '; } 

												if ($log_titular == 'S'){ 													
                                                    $andTitular = 'and LOG_TITULAR = "S"'; 
                                                         
                                                }else{
                                                    $andTitular = 'and LOG_TITULAR = "N"'; 
                                                }
                                                if($log_status == 'S'){
                                                    $andStatus = "AND LOG_ESTATUS = 'S'";
                                                }else{
                                                    $andStatus = "AND LOG_ESTATUS = 'N'";
                                                }

                                                if($cod_empresa == 332){

	                                                if($cod_univend != 0){
														$andUnivend = 'and cod_univend in(' . $cod_univend. ')';
													}else{
														$andUnivend = "";
													}

													if($cod_externo != 0){
														$andCodExterno = "and cod_externo = '$cod_externo'";
													}else{
														$andCodExterno = "";
													}

													if($log_prestador == "S"){
														$andPrestador = " AND COD_INDICAD != 29007 ";
														
													}else{
														$andPrestador = " AND COD_INDICAD = 29007 ";
													}

													if($log_termo == "S"){
														$andTermo = " AND LOG_TERMO = 'S' ";
														
													}else{
														$andTermo = " ";
													}

												}else{

													$andUnivend = "";
													$andPrestador = "";
													$andTermo = "";

												}
                                                                                                
														
													
												$sql = "select count(COD_CLIENTE) as CONTADOR from  ".connTemp($cod_empresa,'true').".clientes where cod_empresa = ".$cod_empresa." 
                                                                                                                                                                    ".$andCodigo."
                                                                                                                                                                    ".$andNome."
                                                                                                                                                                    ".$andCartao."
                                                                                                                                                                    ".$andCpf."
                                                                                                                                                                    ".$andEmail."
                                                                                                                                                                    ".$andCelular."
                                                                                                                                                                    ".$andTermo."
                                                                                                                                                                    $andExterno
                                                                                                                                                                    $andIndicad
                                                                                                                                                                    $andcelular
                                                                                                                                                                    $andTitular
                                                                                                                                                                    $andStatus
                                                                                                                                                                    $andUnivend
                                                                                                                                                                    $andPrestador
                                                                                                                                                                    $andCodExterno
                                                                                                                                                                    and LOG_AVULSO = 'N'
                                                                                                                                                                    order by NOM_CLIENTE ";
											//fnEscreve($sql);
											
											$resPagina = mysqli_query(connTemp($cod_empresa,''),$sql);
											$total = mysqli_fetch_assoc($resPagina);
											//seta a quantidade de itens por página, neste caso, 2 itens
											$registros =100;
                                                                                        //fnEscreve($total['CONTADOR']);
											//calcula o número de páginas arredondando o resultado para cima
											$numPaginas = ceil($total['CONTADOR']/$registros);
											//variavel para calcular o início da visualização com base na página atual
											$inicio = ($registros*$pagina)-$registros;
											
											}

											if($cod_empresa == 136){
												$txt_externo = "Cód. Externo";
												$externo = 'COD_EXTERNO';
											}else{
												$txt_externo = "Num. Cartão";
												$externo = 'NUM_CARTAO';
											}
										
										?>
	

<style>

	input[type="search"]::-webkit-search-cancel-button {
		height: 16px;
		width: 16px;
		background: url(images/close-filter.png) no-repeat right center;
		position: relative;
		cursor: pointer;
	}
	
	input.tableFilter {
		border: 0px;
		background-color: #fff;
	}
	
			
</style>

										<div class="row">

											<div class="col-md-12">

												<div class="push20"></div>

												<?php 


													$sql = "SELECT 1 FROM CLIENTES WHERE COD_EMPRESA = ".$cod_empresa." 
                                                                                                        $andUnivend
                                                                                                        and LOG_AVULSO = 'N'";
													$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);

													$qtdCli = mysqli_num_rows($arrayQuery);

													$sqlFiltro = "SELECT 1 FROM CLIENTES WHERE COD_EMPRESA = ".$cod_empresa." 
	                                                                                                        ".$andCodigo."
	                                                                                                        ".$andNome."
	                                                                                                        ".$andCartao."
	                                                                                                        ".$andCpf."
	                                                                                                        ".$andEmail."
	                                                                                                        ".$andCelular."
	                                                                                                        ".$andTermo."
	                                                                                                        $andExterno
	                                                                                                        $andClientes
	                                                                                                        $andIndicad
	                                                                                                        $andcelular
	                                                                                                        $andTitular
	                                                                                                        $andStatus
	                                                                                                        $andCliente
	                                                                                                        $andUnivend
	                                                                                                        $andPrestador
	                                                                                                        $andCodExterno
	                                                                                                        and LOG_AVULSO = 'N'";
													$arrayFiltro = mysqli_query(connTemp($cod_empresa,''),$sqlFiltro);

													$qtdCliFiltro = mysqli_num_rows($arrayFiltro);

												?>

												<table class="table table-hover">
													<thead>
														<tr>
															<th class="text-center text-info">Apoaidores da Unidade<b> &nbsp; <?= fnValor($qtdCli, 0); ?></b></th>
															<th class="text-center text-info">Apoiadores do Filtro &nbsp; <b><?= fnValor($qtdCliFiltro, 0); ?></b></th>
														</tr>
													</thead>

												</table>
												<div class="push10"></div>

											</div>

										</div>

										<div class="push30"></div>
	
										<div class="col-lg-12">

											<div class="no-more-tables">
											
												<form name="formLista" id="formLista" method="post" action="">
									
												<table class="table table-bordered table-striped table-hover tablesorter" id="tablista">
												  <thead>
													<tr>
													  <th width="40"></th>
													  <th>Cod.Colaborador</th>
													  <th>Nome do Colaborador</th>
													  <!--<th>e-Mail</th>-->
													  <th>CPF</th>
													  <th>Titular</th>
													</tr>
												  </thead>
												<tbody>
												  												  
												<?php
													
													if ($cod_empresa != 0 ){

															$sql = "SELECT * FROM CLIENTES WHERE COD_EMPRESA = ".$cod_empresa." 
		                                                                                                        ".$andCodigo."
		                                                                                                        ".$andNome."
		                                                                                                        ".$andCartao."
		                                                                                                        ".$andCpf."
		                                                                                                        ".$andEmail."
		                                                                                                        ".$andCelular."
		                                                                                                        ".$andTermo."
		                                                                                                        $andExterno
		                                                                                                        $andClientes
		                                                                                                        $andIndicad
		                                                                                                        $andcelular
		                                                                                                        $andTitular
		                                                                                                        $andStatus
		                                                                                                        $andCliente
		                                                                                                        $andUnivend
		                                                                                                        $andPrestador
		                                                                                                        $andCodExterno
		                                                                                                        and LOG_AVULSO = 'N'
		                                                                                                        order by NOM_CLIENTE limit $inicio,$registros";

															$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
															// fnescreve($sql);
															
															$count=0;
															while ($qrListaEmpresas = mysqli_fetch_assoc($arrayQuery)){														  
																$count++;

																$pessoa = "FÍSICA";
																$letraPessoa = "F";

																if($qrListaEmpresas[LOG_JURIDICO] == "S"){
																    $pessoa = "JURÍDICA";
																    $letraPessoa = "J";
																}

																if($qrListaEmpresas[LOG_TITULAR] == 'S'){
																	$titular = '<span class="fal fa-check"></span>';
																	$redirectCli = fnEncode($qrListaEmpresas['COD_CLIENTE']);
																}else{
																	//$titular = '<span class="fal fa-times"></span>';
																	$titular = '';
																	$redirectCli = fnEncode($qrListaEmpresas['COD_TITULAR']);
																}
																										  
																echo"
																	<tr>
																	  <td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(".$count.")'></th>
																	  <td>".$qrListaEmpresas['COD_CLIENTE']."</td>
																	  <td>".$qrListaEmpresas['NOM_CLIENTE']."</td>
																	  <td class='cpfcnpj'>".fnCompletaDoc($qrListaEmpresas['NUM_CGCECPF'],$letraPessoa)."</td>
																	  <td>".$titular."</td>
																	</tr>
																	<input type='hidden' id='ret_COD_CLIENTE_".$count."' value='".$redirectCli."'>
																	<input type='hidden' id='ret_COD_EMPRESA_".$count."' value='".fnEncode($cod_empresa)."'>
																	"; 
															}

														

													}	
												?>
													
												</tbody>
												<?php if ($cod_empresa != 0 && $des_superb == "") {  ?>
													<tfoot>
														<tr>
														  <th class="" colspan="100"><ul class="pagination pagination-sm">
														  <?php
															for($i = 1; $i < $numPaginas + 1; $i++) {
																if ($pagina == $i){$paginaAtiva = "active";}else{$paginaAtiva = "";}	
																echo "<li class='pagination $paginaAtiva'><a href='javascript:void(0);' onclick='page(".$i.")' style='text-decoration: none;'>".$i."</a></li>";   
															}													  
														  ?></ul>
														  </th>
														</tr>
													</tfoot>
												<?php }  
												
												
										//fim verifica post
										}	
										?>

												</table>
												
												<div class="push"></div>
												
												</form>

											</div>
											
										</div>										
									
									<div class="push"></div>
									
									</div>								
								
								</div>
							</div>
							<!-- fim Portlet -->
						</div>
						
					</div>	
					
					<div class="push20"></div>
				

	<script type="text/javascript">	
	
	
		$(document).ready(function(){

			//retorno combo multiplo - lojas
			$("#formulario #COD_UNIVEND").val('').trigger("chosen:updated");
			if ("<?=$cod_univend?>" != "" && "<?=$cod_univend?>" != "0") {
				var sistemasUni = "<?=$cod_univend?>";
				var sistemasUniArr = sistemasUni.split(',');
				//opções multiplas
				for (var i = 0; i < sistemasUniArr.length; i++) {
					$("#formulario #COD_UNIVEND option[value=" + sistemasUniArr[i] + "]").prop("selected", "true");
				}
				$("#formulario #COD_UNIVEND").trigger("chosen:updated");
			}

			$(document).on('keypress',function(e) {
			    if(e.which == 13) {
			        e.preventDefault();
			        $("#BUS").click();
			    }
			});			
	
			//chosen obrigatório
			$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
			$('#formulario').validator();
		
			//table sorter
			$(function() { 
			  var tabelaFiltro = $('table.tablesorter')
			  tabelaFiltro.find("tbody > tr").find("td:eq(1)").mousedown(function(){
				$(this).prev().find(":checkbox").click()
			  });
			  $("#filter").keyup(function() {
				$.uiTableFilter( tabelaFiltro, this.value );
			  })
			  $('#formLista').submit(function(){
				tabelaFiltro.find("tbody > tr:visible > td:eq(1)").mousedown();
				return false;
			  }).focus();
			}); 

			//pesquisa table sorter
			$('.filter-all').on('input', function(e) {
				if('' == this.value) {
				var lista = $("#filter").find("ul").find("li");  
				filtrar(lista, "");
				}
			});			
				
		});	
			
		$(document).on('change', '#COD_EMPRESA', function(){ 
		   $("#dKey").val($("#COD_EMPRESA").val());
		});	

		function page(index){
			
			$("#pagina").val(index);
			$( "#formulario" )[0].submit();   			
			//alert(index);	
				
		}
	
		function retornaForm(index){
				
			$('#formulario').attr('action', 'action.do?mod=<?php echo $DestinoPg; ?>&id='+$("#ret_COD_EMPRESA_"+index).val()+'&idC='+$("#ret_COD_CLIENTE_"+index).val());					
			$("#formulario #hHabilitado").val('S');
			$( "#formulario" )[0].submit();   			
			
		}
	
	</script>
	