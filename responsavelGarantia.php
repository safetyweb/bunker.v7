<?php

//echo fnDebug('true');
$mod = fnDecode($_GET['mod']);
if($mod == 1986){
	$cod_tipo = 7;
}else {
	$cod_tipo = 6;
}

$regEncontrato = "N";

$hashLocal = mt_rand();

$conn = conntemp($cod_empresa,"");
$adm = $connAdm->connAdm();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$num_contrato = fnLimpaCampoZero($_REQUEST['NUM_CONTRATO']);
		$cod_cliente = fnLimpaCampoZero($_REQUEST['COD_CLIENTE']);
		$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
		$cod_bem = fnLimpaCampoZero($_REQUEST['COD_BEM']);
		$cod_tpusuario = fnLimpaCampoZero($_REQUEST['COD_TPUSUARIO']);
		$cod_garantia = fnLimpaCampoZero($_REQUEST['COD_GARANTIA']);
		$cod_responsavel = fnLimpaCampoZero($_REQUEST['COD_USUARIO']);
		$cod_univend = fnLimpaCampoZero($_REQUEST['COD_UNIVEND']);
		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];


		if ($opcao == 'CAD') {
			$sql = "INSERT INTO GARANTIA_RESPONSAVEL (
				COD_EMPRESA,
				COD_CLIENTE,
				NUM_CONTRATO,
				COD_BEM,
				COD_TPUSUARIO,
				COD_RESPONSAVEL,
				COD_UNIVEND,
				COD_USUCADA
				) VALUES (
				$cod_empresa,
				$cod_cliente,
				$num_contrato,
				0,
				$cod_tpusuario,
				$cod_responsavel,
				$cod_univend,
				$cod_usucada
			)";

				mysqli_query(connTemp($cod_empresa,''),$sql);
				$sql2 = "INSERT INTO GARANTIA_RESPONSAVEL_LOGS (
					COD_EMPRESA,
					COD_CLIENTE,
					NUM_CONTRATO,
					COD_BEM,
					COD_TPUSUARIO,
					COD_RESPONSAVEL,
					COD_UNIVEND,
					COD_USUCADA,
					COD_GARANTIA
					) VALUES (
					$cod_empresa,
					$cod_cliente,
					$num_contrato,
					0,
					$cod_tpusuario,
					$cod_responsavel,
					$cod_univend,
					$cod_usucada,
					0
				)";
					mysqli_query(connTemp($cod_empresa, ''),$sql2);
				}
				
				if($opcao == "ALT"){
					$sqlUpdate = "UPDATE GARANTIA_RESPONSAVEL SET
					COD_RESPONSAVEL = '$cod_responsavel',
					COD_UNIVEND = '$cod_univend',
					COD_ALTERAC = '$cod_usucada',
					DAT_ALTERAC = NOW()
					WHERE COD_GARANTIA = $cod_garantia AND NUM_CONTRATO = '$num_contrato' AND COD_EMPRESA = '$cod_empresa' AND COD_CLIENTE = '$cod_cliente' AND COD_TPUSUARIO = '$cod_tipo'";
					mysqli_query(connTemp($cod_empresa,''),$sqlUpdate);

				// INSERT  
					$sql2 = "INSERT INTO GARANTIA_RESPONSAVEL_LOGS (
						COD_EMPRESA,
						COD_CLIENTE,
						NUM_CONTRATO,
						COD_BEM,
						COD_TPUSUARIO,
						COD_RESPONSAVEL,
						COD_UNIVEND,
						COD_USUCADA,
						COD_GARANTIA
						) VALUES (
						$cod_empresa,
						$cod_cliente,
						$num_contrato,
						0,
						$cod_tpusuario,
						$cod_responsavel,
						$cod_univend,
						$cod_usucada,
						$cod_garantia
					)";
						mysqli_query(connTemp($cod_empresa, ''),$sql2);
					}
				}

			}

	//busca dados da url    
			if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
    //busca dados da empresa
				$cod_empresa = fnDecode($_GET['id']);
				$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
    //fnEscreve($sql);
				$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
				$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

				if (isset($arrayQuery)) {
					$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
					$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
				}
			} else {
				$cod_empresa = 0;
    //fnEscreve('entrou else');
			}

			if(is_numeric(fnLimpacampo(fnDecode($_GET['idC'])))){

				$cod_cliente = fnDecode($_GET['idC']);
				$sql = "SELECT COD_CLIENTE, NOM_CLIENTE FROM CLIENTES where COD_CLIENTE = '". $cod_cliente . "' AND COD_EMPRESA = '". $cod_empresa . "' ";

				$query = mysqli_query(connTemp($cod_empresa, ''), $sql);
				$qrBuscaCliente = mysqli_fetch_assoc($query);

				if(isset($query)) {
					$cod_cliente = $qrBuscaCliente['COD_CLIENTE'];
					$nom_cliente = $qrBuscaCliente['NOM_CLIENTE'];
				}else{
					$cod_cliente = 0;
				}
			}

			if(is_numeric(fnLimpacampo(fnDecode($_GET['idCt'])))){

				$num_contrato = fnDecode($_GET['idCt']);
				$sql = "SELECT NUM_CONTRATO, COD_STATUS, COD_TIPOBEM FROM CONTRATO_BLOCK where NUM_CONTRATO = '". $num_contrato . "' AND COD_CLIENTE = '". $cod_cliente . "' AND COD_EMPRESA = '". $cod_empresa . "' ";

        //fnEscreve($sql);
				$query = mysqli_query(connTemp($cod_empresa, ''), $sql);
				$qrBuscaContrato = mysqli_fetch_assoc($query);

				if(isset($query)) {
					$num_contrato = $qrBuscaContrato['NUM_CONTRATO'];
					$cod_tipobem = $qrBuscaContrato['COD_TIPOBEM'];
					$cod_status = $qrBuscaContrato['COD_STATUS'];

					if($cod_status == 1){
						$status = "Proposta";
					}else{
						$status = "";
					}
				}else{
					$cod_status = 0;
				}

				$sqlResp = "SELECT * FROM GARANTIA_RESPONSAVEL WHERE COD_EMPRESA = '$cod_empresa' AND COD_CLIENTE = '$cod_cliente' AND NUM_CONTRATO = '$num_contrato' AND COD_TPUSUARIO = '$cod_tipo' ";
				$queryResp = mysqli_query(connTemp($cod_empresa, ''), $sqlResp);
				$qrResult = mysqli_fetch_assoc($queryResp);

				if(isset($qrResult)){
					$cod_garantia = $qrResult['COD_GARANTIA'];
					$cod_univend = $qrResult['COD_UNIVEND'];
					$cod_responsavel = $qrResult['COD_RESPONSAVEL'];

					$regEncontrato = "S";
				}
			}

			?>

			<div class="push30"></div>

			<div class="row">

				<div class="col-md12 margin-bottom-30">
					<!-- Portlet -->
					<div class="portlet portlet-bordered">
						<div class="portlet-title">
							<div class="caption">
								<i class="fal fa-terminal"></i>
								<span class="text-primary"><?php echo $NomePg; ?></span>
							</div>
						</div>

						<div class="portlet-body">

							<?php if ($msgRetorno <> '') { ?>
								<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
									<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
										aria-hidden="true">&times;</span></button>
										<?php echo $msgRetorno; ?>
									</div>
								<?php } ?>

								<div class="push30"></div>

								<div class="login-form">

									<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

										<?php include "bensHeader.php"; ?>

										<div class="push20"></div>

										<fieldset>
											<legend>Responsável do Projeto</legend>

											<div class="row form-group">
												<div class="col-md-3">
													<div class="form-group">
														<label for="inputName" class="control-label required">Escritório</label>
														<select data-placeholder="Selecione o Escritório" name="COD_UNIVEND" id="COD_UNIVEND"
														class="chosen-select-deselect">
															<option value=""></option>
															<?php
															$sql = "select COD_UNIVEND, NOM_UNIVEND from UNIDADEVENDA WHERE COD_TPUNIVE = $cod_tipo AND COD_EMPRESA = $cod_empresa";
															$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

															while ($qrUnidade = mysqli_fetch_assoc($arrayQuery)) {
																$selected = ($qrUnidade['COD_UNIVEND'] == $cod_univend) ? 'selected' : '';
																echo "<option value='" . $qrUnidade['COD_UNIVEND'] . "' $selected>" . $qrUnidade['NOM_UNIVEND'] . "</option>";
															}
															?>
														</select>
														<div class="help-block with-errors"></div>
													</div>
												</div>
												<div class="col-md-3">
													<div class="form-group">
														<label for="inputName" class="control-label">Responsável</label>
														<div id="divId_sub">
															<select data-placeholder="Selecione o Responsável" name="COD_USUARIO" id="COD_USUARIO"
																	class="chosen-select-deselect">
																<option value="">&nbsp;</option>
															</select>
														</div>
														<script>

														</script>
														<div class="help-block with-errors"></div>
													</div>
												</div>
										</fieldset>
										
										<div class="push10"></div>
										<hr>
										
										<div class="form-group text-right col-lg-12">
											<?php if($regEncontrato == "N"){?>
											<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus"
												aria-hidden="true"></i>&nbsp; Cadastrar</button>
											<?php }else{  ?>
											<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn">Alterar</button>
											<?php } ?>
										</div>

										<div class="push30"></div>
										<div class="col-lg-12">

											<div class="push20"></div>

											<table class="table table-bordered table-hover tablesorter">

												<thead>
													<tr>
														<th>Data</th>
														<th>Cod. Responsável</th>
														<th>Responsável</th>
														<th>Cod. Escritório</th>
														<th>Escritório</th>
													</tr>
												</thead>

												<tbody id="relatorioConteudo">

													<?php

													$sql2 = "SELECT 
													GR.COD_UNIVEND, 
													GR.DAT_CADASTR, 
													GR.COD_RESPONSAVEL, 
													V.NOM_FANTASI, 
													U.NOM_USUARIO
													FROM GARANTIA_RESPONSAVEL_LOGS AS GR
													INNER JOIN UNIDADEVENDA AS V ON GR.COD_UNIVEND = V.COD_UNIVEND
													INNER JOIN USUARIOS AS U ON GR.COD_RESPONSAVEL = U.COD_USUARIO
													WHERE GR.NUM_CONTRATO = '$num_contrato'
													AND GR.COD_EMPRESA = '$cod_empresa'
													AND GR.COD_CLIENTE = '$cod_cliente'
													AND GR.COD_TPUSUARIO = '$cod_tipo'	
													";

													fnEscreve($sql2);

													$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql2) or die(mysqli_error());

													$count=0;
													while ($qrApoia = mysqli_fetch_assoc($arrayQuery))
													{			

														echo"
														<tr>
														<td class=''>".fnFormatDate($qrApoia['DAT_CADASTR'])."</td>
														<td class=''>".$qrApoia['COD_RESPONSAVEL']."</td>
														<td class=''>".$qrApoia['NOM_USUARIO']."</td>
														<td class=''>".$qrApoia['COD_UNIVEND']."</td>
														<td class=''>".$qrApoia['NOM_FANTASI']."</td>
														</tr>
														"; 
													}											

													?>
												</tbody>

												<tfoot>
													<tr>
														<th class="" colspan="100">
															<center>
																<ul id="paginacao" class="pagination-sm"></ul>
															</center>
														</th>
													</tr>
												</tfoot>

											</table>

										</div>
											<input type="hidden" name="opcao" id="opcao" value="">
											<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
											<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
											<input type="hidden" name="COD_RESPONSAVEL" id="COD_RESPONSAVEL" value="<?php echo $cod_responsavel; ?>">
											<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
											<input type="hidden" name="NUM_CONTRATO" id="NUM_CONTRATO" value="<?php echo $num_contrato; ?>">
											<input type="hidden" name="COD_CLIENTE" id="COD_CLIENTE" value="<?php echo $cod_cliente; ?>">
											<input type="hidden" name="COD_BEM" id="COD_BEM" value="<?php echo $cod_bem; ?>">
											<input type="hidden" name="COD_TPUSUARIO" id="COD_TPUSUARIO" value="<?php echo $cod_tipo; ?>">
											<input type="hidden" name="COD_GARANTIA" id="COD_GARANTIA" value="<?php echo $cod_garantia; ?>">


											<div class="push5"></div>

										</form>

										<div class="push50"></div>

										<div class="push"></div>

									</div>

								</div>
							</div>
							<!-- fim Portlet -->
						</div>
					</div>

					<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

					<div class="push20"></div>

					<script type="text/javascript">
						$(document).ready(function() {

							var resposanvel = $("#COD_RESPONSAVEL").val();
							if (resposanvel != "") {
								var codBusca = $("#COD_UNIVEND").val();
								var codBusca3 = $("#COD_EMPRESA").val();
								var codResposanvel = $("#COD_RESPONSAVEL").val();
								buscaSubCat(codBusca, codResposanvel, codBusca3);
							}

							$("#COD_UNIVEND").change(function() {
								var codBusca = $("#COD_UNIVEND").val();
								var codBusca3 = $("#COD_EMPRESA").val();
								var codResposanvel = $("#COD_RESPONSAVEL").val();
								buscaSubCat(codBusca, codResposanvel, codBusca3);
							});

						});

						function buscaSubCat(idUniv, idUsu, idEmp) {
							$.ajax({
								type: "GET",
								url: "ajxUsuarioEngGarantia.php",
								data: {
									ajx1: idUniv,
									ajx2: idEmp,
									ajx3: idUsu,
									COD_TIPO: "<?=fnEncode($cod_tipo)?>"
								},

								beforeSend: function() {
									$('#divId_sub').html('<div class="loading" style="width: 100%;"></div>');
								},
								success: function(data) {
									$("#divId_sub").html(data);
									console.log(data);
								},
								error: function() {
									$('#divId_sub').html(
										'<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
								}
							});
						}
					</script>