<?php

$hashLocal = mt_rand();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		if (isset($_REQUEST['COD_AREABLOCK'])) {
			$cod_areablock = fnLimpaCampoZero($_REQUEST['COD_AREABLOCK']);
		} else {
			$cod_areablock = "";
		}
		if (isset($_REQUEST['COD_GRUPOMODMK'])) {
			$cod_grupomodmk = fnLimpaCampoZero($_REQUEST['COD_GRUPOMODMK']);
		} else {
			$cod_grupomodmk = "";
		}
		if (isset($_REQUEST['COD_MODULOS'])) {
			$cod_modulos = fnLimpaCampoZero($_REQUEST['COD_MODULOS']);
		} else {
			$cod_modulos = "";
		}
		if (isset($_REQUEST['NOM_AREABLOCK'])) {
			$nom_areablock = fnLimpaCampo($_REQUEST['NOM_AREABLOCK']);
		} else {
			$nom_areablock = "";
		}

		if (isset($_REQUEST['opcao'])) {
			$opcao = $_REQUEST['opcao'];
		} else {
			$opcao = "";
		}

		if (isset($_REQUEST['hHabilitado'])) {
			$hHabilitado = $_REQUEST['hHabilitado'];
		} else {
			$hHabilitado = "";
		}
		$hashForm = $_REQUEST['hashForm'];

		//fnEscreve($des_icones);	

		//if ($opcao != ''){
		if ($opcao == '999') {

			$sql = "CALL SP_ALTERA_MODULOSMARKA_AREA (
				 '" . $cod_areablock . "', 
				 '" . $cod_grupomodmk . "', 
				 '" . $nom_areablock . "', 
				 '" . $cod_modulos . "',    
				 '" . $opcao . "'    
				) ";

			//echo $sql;

			mysqli_query($connAdm->connAdm(), trim($sql));

			//mensagem de retorno
			switch ($opcao) {
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
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode($_GET['id']);
	$sql = "SELECT COD_EMPRESA, NOM_EMPRESA, NOM_FANTASI FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";

	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($qrBuscaEmpresa)) {
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
	}
} else {
	$cod_empresa = 0;
	$nom_empresa = "";
}

//fnMostraForm();

?>
<style>
	#blocker {
		display: none;
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		opacity: .8;
		background-color: #fff;
		z-index: 1000;
		cursor: wait;
	}

	#blocker div {
		position: absolute;
		top: 30%;
		left: 48%;
		width: 200px;
		height: 2em;
		margin: -1em 0 0 -2.5em;
		color: #000;
		font-weight: bold;
	}
</style>
<!-- Versão do fontawesome compatível com as checkbox (não remover) -->
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.2.0/css/font-awesome.min.css">
<div id="blocker">
	<div style="text-align: center;"><img src="images/loading2.gif"><br /> Aguarde. Processando...</div>
</div>
<div class="push30"></div>

<div class="row">

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fal fa-terminal"></i>
					<span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
				</div>

				<?php
				//matriz integração - fidelidade
				if (fnDecode($_GET['mod']) == 1153) {
					$formBack = "1154";
				}
				//matriz integração - sh manager
				if (fnDecode($_GET['mod']) == 1159) {
					$formBack = "1158";
				}
				include "atalhosPortlet.php";
				?>

			</div>
			<div class="portlet-body">

				<?php if ($msgRetorno <> '') { ?>
					<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<?php echo $msgRetorno; ?>
					</div>
				<?php } ?>

				<?php
				//matriz integração - fidelidade
				if (fnDecode($_GET['mod']) == 1153) {
					$abaModulo = 1153;
					include "abasMatrizIntegracao.php";
				}
				//matriz integração - sh manager
				if (fnDecode($_GET['mod']) == 1159) {
					$abaModulo = 1159;
					include "abasIntegradora.php";
				}
				?>
				<div class="push30"></div>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<style>
							.bigCheck {
								width: 20px;
								height: 20px;
								margin-top: 5px
							}

							.table-header-rotated th.rotate-90 {
								height: 100px;
								position: relative;
								vertical-align: bottom;
								padding: 0;
								font-size: 17px;
								font-weight: bold;
								line-height: 1.42857143;
								;
							}

							.table-header-rotated th.rotate-90 span {
								transform: rotate(270deg);
								position: absolute;
								bottom: 50px;
								display: inline-block;
								width: 110px;
								text-align: left;
							}
						</style>

						<table class="table table-bordered table-hover table-header-rotated">
							<thead>
								<tr>
									<th></th>
									<th style="width: 200px;"></th>
									<?php

									$sql = "select * from INTEGRA_VENDAMTZ order by NUM_ORDENAC";
									// fnEscreve($sql);
									$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

									$countModulos = 0;
									$arrayCOD_FASEINT = "";
									$arrayNUM_FASEINT = "";

									while ($qrBuscaFases = mysqli_fetch_assoc($arrayQuery)) {
										$countModulos++;
										$arrayNUM_FASEINT .= $countModulos . ';';
										$arrayCOD_FASEINT .= $qrBuscaFases['COD_FASEINT'] . ';';

										//se módulo SH Manager
										if (fnDecode($_GET['mod']) == 1159) {
											echo "
																	<th class='text-center'>" . $qrBuscaFases['NOM_FASEINT'] . "<br/> <font class='f12'>" . $qrBuscaFases['DES_FASEINT'] . "<br/> chave: <b>" . $qrBuscaFases['KEY_FASEINT'] . "</b></font> </th>
																	";
										} else {
											echo "
																	<th class='text-center'>" . $qrBuscaFases['NOM_FASEINT'] . "<br/> <font class='f12'>" . $qrBuscaFases['DES_FASEINT'] . " </font></th>
																	";
										}
									}


									?>
								</tr>
							</thead>
							<tbody>

								<?php

								//fnEscreve( substr($arrayNUM_FASEINT,0,-1));
								//fnEscreve( substr($arrayCOD_FASEINT,0,-1));
								$arrLimpo = substr($arrayCOD_FASEINT, 0, -1);

								$limpoCOD_FASEINT = explode(";", $arrLimpo);
								//print_r($limpoCOD_FASEINT);
								//echo $limpoCOD_FASEINT[3];												

								$sql = "select * from INTEGRA_ACAOMTZ order by NUM_ORDENAC";

								$arrayQuery = mysqli_query($connAdm->connAdm(), trim($sql));

								$count = 0;
								//looping por grupo de módulo
								while ($qrBuscaAcao = mysqli_fetch_assoc($arrayQuery)) {
									$count++;
									$cod_acao = $qrBuscaAcao['COD_ACAOINT'];
									echo "
															<tr>
															  <td class='text-center'><b>" . $qrBuscaAcao['KEY_ACAOINT'] . "</b></td>
															  <td><b>" . $qrBuscaAcao['NOM_ACAOINT'] . " </b></td> 
															";


									$sql2 = "select INTEGRA_VENDAMTZ.*,
																	(select  count(*) from MATRIZ_INTEGRACAO where MATRIZ_INTEGRACAO.COD_EMPRESA = $cod_empresa AND MATRIZ_INTEGRACAO.COD_ACAOINT = $cod_acao AND MATRIZ_INTEGRACAO.COD_FASEVND = INTEGRA_VENDAMTZ.COD_FASEINT) AS TEM_MATRIZ 
																	 from INTEGRA_VENDAMTZ order by NUM_ORDENAC";

									//fnEscreve($sql2);
									$arrayQuery2 = mysqli_query($connAdm->connAdm(), $sql2);

									$countFase = 0;
									while ($qrBuscaFasesOn = mysqli_fetch_assoc($arrayQuery2)) {
										$cod_fase = $qrBuscaFasesOn['COD_FASEINT'];
										$tem_matriz = $qrBuscaFasesOn['TEM_MATRIZ'];
										if ($tem_matriz > 0) {
											$checado = "checked";
										} else {
											$checado = "";
										}

										//matriz integração - fidelidade
										if (fnDecode($_GET['mod']) == 1153) {
											echo "<td class='text-center'>
																			<div class='checkbox checkbox-primary'>
																				<input class='styled' type='checkbox' name='CHECK_" . $qrBuscaAcao['COD_ACAOINT'] . "_" . $countFase . "' id='CHECK_" . $qrBuscaAcao['COD_ACAOINT'] . "_" . $countFase . "' onclick='checkAcao(" . $limpoCOD_FASEINT[$countFase] . "," . $qrBuscaAcao['COD_ACAOINT'] . ",this);' " . $checado . ">
																				<label for='CHECK_" . $qrBuscaAcao['COD_ACAOINT'] . "_" . $countFase . "' >&nbsp;</label>
																			</div>
																		</td>";
										} else {
											if ($tem_matriz > 0) {
												echo "<td class='text-center'>
																			<i class='fas fa-check' aria-hidden='true'></i>
																		</td>";
											} else {
												echo "<td class='text-center'>
																		</td>";
											}
										}

										$countFase++;
									}

									echo "
															</tr>
															";
								}

								?>


							</tbody>
						</table>

						<div class="push10"></div>

						<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">

						<div class="push50"></div>

					</form>


					<div class="push"></div>

					<div id="div_Matriz"></div>

				</div>

			</div>
		</div>
		<!-- fim Portlet -->
	</div>

</div>

<div class="push20"></div>

<script type="text/javascript">
	function checkAcao(idFase, idAcao, campo) {
		//alert("fase: "+ idFase +"\nação: "+ idAcao );
		//alert("campo: "+ campo.id );
		var opcao = "";
		if ($(campo).prop('checked') == true) {
			//alert("selected");
			opcao = "CAD";
		} else {
			//alert("deselect");
			opcao = "EXC";
		}

		$.ajax({
			type: "GET",
			url: "ajxMatrizIntegracao.php",
			data: {
				ajx1: idFase,
				ajx2: idAcao,
				ajx3: <?php echo $cod_empresa; ?>,
				ajx4: opcao
			},
			beforeSend: function() {
				// $('#blocker').fadeIn(1);
			},
			success: function(data) {
				// $('#blocker').fadeOut(1);
				console.log(data);
			},
			error: function() {
				//$('#div_Matriz').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
			}
		});

	}
</script>