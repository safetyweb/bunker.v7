<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$opcao = "";
$hashLocal = "";
$msgRetorno = "";
$msgTipo = "";
$des_grupotr = "";
$hHabilitado = "";
$hashForm = "";
$arrayQuery = [];
$qrBuscaEmpresa = "";
$nom_empresa = "";
$formBack = "";
$qrListaPersonas = "";
$sqlPersonas = "";
$ListaTotal = "";
$personaAtivo = "";


//echo "<h5>_".$opcao."</h5>";

$hashLocal = mt_rand();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode(@$_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_grupotr = fnLimpaCampoZero(@$_REQUEST['COD_GRUPOTR']);
		$des_grupotr = fnLimpaCampo(@$_REQUEST['DES_GRUPOTR']);
		$cod_empresa = fnLimpaCampo(@$_REQUEST['COD_EMPRESA']);

		$opcao = @$_REQUEST['opcao'];
		$hHabilitado = @$_REQUEST['hHabilitado'];
		$hashForm = @$_REQUEST['hashForm'];

		if ($opcao != '') {

			//fnMostraForm();

			//echo $sql;

			//mysqli_query($connAdm->connAdm(),trim($sql));				

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
if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode(@$_GET['id']);
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


//$sql = "DELETE FROM IMPORT_BLACKLIST WHERE COD_EMPRESA = $cod_empresa";
//mysqli_query(connTemp($cod_empresa,""),trim($sql));

//fnEscreve($cod_empresa);


?>

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
				//$formBack = "1015";
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



				<div class="push30"></div>

				<style>
					.leitura2 {
						border: none transparent !important;
						outline: none !important;
						background: #fff !important;
						font-size: 18px;
						padding: 0;
					}

					.container-fluid .passo:not(:first-of-type) {
						display: none;
					}

					.wizard .col-md-2 {
						padding: 0;
					}

					.btn-circle {
						background-color: #DDD;
						opacity: 1 !important;
						border: 2px solid #efefef;
						height: 55px;
						width: 55px;
						margin-top: -23px;
						padding-top: 11px;
						border-radius: 50%;
						-moz-border-radius: 50%;
						-webkit-border-radius: 50%;
						color: #fff;
						font-size: 20px;
					}

					.fa-2x {
						font-size: 19px;
						margin-top: 5px;
					}

					.collapse-chevron .fa {
						transition: .3s transform ease-in-out;
					}

					.collapse-chevron .collapsed .fa {
						transform: rotate(-90deg);
					}

					.pull-right,
					.pull-left {
						margin-top: 3.5px;
					}

					.fundo {
						background: #D3D3D3;
						height: 10px;
						width: 100%;
					}

					.fundoAtivo {
						background: #2ed4e0;
					}

					.inicio {
						background: #2ed4e0;
						border-bottom-left-radius: 10px 7px;
						border-top-left-radius: 10px 7px;
					}

					.final {
						border-bottom-right-radius: 10px 7px;
						border-top-right-radius: 10px 7px;
					}

					.bigCheck {
						width: 17px;
						height: 17px;
						margin-top: 5px
					}
				</style>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<div class="row text-center wizard setup-panel">

							<div class="col-md-3"></div>

							<div class="col-md-2" id="step1">
								<div class="fundo inicio">
									<a type="button" class="btn btn-circle fundoAtivo disabled" id="btn1"><span>1</span></a>
								</div><br><br>
								<p>Seleção</p>
							</div>

							<div class="col-md-2" id="step2">
								<div class="fundo">
									<a type="button" class="btn btn-circle disabled"><span>2</span></a>
								</div><br><br>
								<p>Confirmação</p>
							</div>

							<div class="col-md-2" id="step3">
								<div class="fundo final">
									<a type="button" class="btn btn-circle disabled"><span class="fas fa-check fa-2x"></span></a>
								</div><br><br>
								<p>Concluído</p>
							</div>

							<div class="col-md-3"></div>

						</div>

						<div class="push30"></div>

						<div class="container-fluid">

							<div class="passo" id="passo1">

								<div class="row">

									<div class="push20"></div>


									<div class="col-md-4 col-xs-12">
										<div class="form-group">
											<label for="inputName" class="control-label">Visualizar Personas Excluídas</label>
											<div class="push5"></div>
											<label class="switch switch-small">
												<input type="checkbox" name="LOG_TODAS" id="LOG_TODAS" class="switch switch-small" value="S" onchange="filtraPersonaAtiva(this)">
												<span></span>
											</label>
										</div>
										<div class="push10"></div>
									</div>


									<div class="col-md-12">

										<table class="table table-bordered table-striped table-hover tablesorter" id="tablista">
											<thead>
												<tr>
													<th class="{ sorter: false }"></th>
													<th>Persona</th>
													<th>Código</th>
													<th class="{ sorter: false } text-center">Qtd. Clientes</th>
													<th class="text-right">Crédito</th>
													<th class="text-right">Total Crédito</th>
													<th class="{ sorter: false }">Dt. Cadastro (Lote)</th>
													<th>Dt. Validade</th>
													<th>Ativo</th>
												</tr>
											</thead>
											<tbody id="relatorioConteudo">

												<?php

												// $sql = "select * from persona where cod_empresa = ".$cod_empresa." and LOG_ATIVO = 'S' order by DES_PERSONA ";

												$sql = "SELECT B.DES_PERSONA,
																			   B.COD_PERSONA,
																			   A.QTD_PESCLASS,
																			   A.VAL_CREDITO,
																			   (A.QTD_PESCLASS*A.VAL_CREDITO) AS TOT_CREDITO,
																			   A.DAT_CADASTR,
																			   A.DAT_VALIDADE,
																			   B.LOG_ATIVO 
																			   FROM PERSONA B
																		LEFT JOIN CREDITOS_LOT A ON A.COD_PERSONAS=B.COD_PERSONA AND A.cod_empresa=$cod_empresa
																		WHERE B.COD_EMPRESA = $cod_empresa
																		AND B.LOG_ATIVO = 'S'
																		ORDER BY A.DAT_CADASTR DESC";

												//fnEscreve($sql);
												$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

												$count = 0;
												while ($qrListaPersonas = mysqli_fetch_assoc($arrayQuery)) {
													$count++;
													/*		
																	echo "<pre>";
																	print_r($qrListaPersonas);
																	echo "</pre>";
																	//exit();
																	*/

													// $sqlPersonas = "SELECT COUNT(B.COD_CLIENTE) as TOTAL_PERSONA FROM PERSONACLASSIFICA B WHERE B.COD_PERSONA = ".$qrListaPersonas['COD_PERSONA']." AND B.COD_EMPRESA = $cod_empresa ";
													// //fnEscreve($sqlPersonas);
													// $ListaTotal = mysqli_query(connTemp($cod_empresa,''),$sqlPersonas);
													// $ListaTotal = mysqli_fetch_assoc($ListaTotal);

													if ($qrListaPersonas['LOG_ATIVO'] == "S") {
														$personaAtivo = "<i class='fal fa-check' aria-hidden='true'></i>";
													} else {
														$personaAtivo = "";
													}

												?>

													<tr>
														<td class="text-center"><input type="checkbox" class="bigCheck" name="cod_persona[]" id="cod_persona_<?php echo $count; ?>" value="<?php echo $qrListaPersonas['COD_PERSONA']; ?>" onclick='liberabtn("#cod_persona_")'>&nbsp;</td>
														<td><small><?php echo $qrListaPersonas['DES_PERSONA']; ?></small></td>
														<td><small><?php echo $qrListaPersonas['COD_PERSONA']; ?></small></td>
														<td class="text-center"><small><?php echo $qrListaPersonas['QTD_PESCLASS'] ?></small></td>
														<td class="text-right"><small><?php echo fnValor($qrListaPersonas['VAL_CREDITO'], 0); ?></small></td>
														<td class="text-right"><small><?php echo fnValor($qrListaPersonas['TOT_CREDITO'], 0); ?></small></td>
														<td><small><?php echo fnDataFull($qrListaPersonas['DAT_CADASTR']); ?></small></td>
														<td><small><?php echo fnDataFull($qrListaPersonas['DAT_VALIDADE']); ?></small></td>
														<td class='text-center'><?php echo $personaAtivo; ?></td>
													</tr>

												<?php
												}

												?>

											</tbody>
										</table>

									</div>


								</div>

								<div class="push20"></div>

								<hr>

								<div class="col-md-8"></div>
								<div class="col-md-2 pull-right">
									<button class="btn btn-primary btn-block next next1" disabled name="next">Próximo &nbsp; <i class="fas fa-arrow-right"></i></button>
								</div>

								<div class="push10"></div>

							</div>


							<div id="passo2"></div>

							<div id="passo3"></div>


							<div class="push10"></div>

							<!--<input type="hidden" name="opcao" id="opcao" value="">-->
							<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
							<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">

							<div class="push5"></div>

					</form>

					<div class="push50"></div>

				</div>

			</div>
		</div>
		<!-- fim Portlet -->
	</div>

</div>

<div class="push20"></div>

<script type="text/javascript">
	function retornaForm(index) {
		$("#formulario #COD_GRUPOTR").val($("#ret_COD_GRUPOTR_" + index).val());
		$("#formulario #DES_GRUPOTR").val($("#ret_DES_GRUPOTR_" + index).val());
		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
	}

	$(document).ready(function() {

		$('.next1').click(function() {

			$.ajax({
				type: "POST",
				url: "ajxCreditosLote.php?passo=1&id=<?php echo fnEncode($cod_empresa); ?>",
				data: $('#formulario').serialize(),
				method: 'POST',
				success: function(data) {
					$("#passo2").html(data);

				},
				beforeSend: function() {
					$('#passo1').hide();
					$('#passo2').show();
					//$("#passo1").html('<div class="loadingBig" style="width: 100%; height: 100px; margin:auto;"></div>');
					$("#passo2").html('<div style="text-align: center;"><img src="images/loading2.gif"><br/> Aguarde. Processando... ;-)</div>');
				},
				error: function() {
					$.alert({
						title: "Erro ao processar",
						content: "Algo saiu errado. Por favor, tente novamente.",
						type: 'red'
					});
					$("#step2 div.fundo, #step2 a.btn").css('background', 'red');
				}
			});

		});

	});

	function filtraPersonaAtiva(el) {

		let log_ativo = "S";

		if ($(el).is(':checked')) {

			log_ativo = "N";

		}

		$.ajax({
			type: "POST",
			url: "ajxCreditosLote.php?passo=ativo&id=<?php echo fnEncode($cod_empresa); ?>",
			data: {
				LOG_ATIVO: log_ativo
			},
			method: 'POST',
			beforeSend: function() {
				$("#relatorioConteudo").html('<div style="text-align: center;" class="loading"></div>');
			},
			success: function(data) {
				$("#relatorioConteudo").html(data);
			},
			error: function() {
				$("#relatorioConteudo").html('<div style="text-align: center;">Oops... Itens não encontrados.</div>');
			}
		});

	}

	//validando checkboxes
	function liberabtn(id) {
		if ($("#passo1 input[type=checkbox]:checked").length > 0) {
			$('.next1').removeAttr('disabled');
		} else {
			$('.next1').attr('disabled', 'disabled');
		}
	}
</script>