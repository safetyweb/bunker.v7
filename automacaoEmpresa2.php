<?php

//echo "<h5>_".$opcao."</h5>";

$hashLocal = mt_rand();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_database = 0;
		$cod_servidor = fnLimpaCampo($_REQUEST['COD_SERVIDOR']);
		$nom_database = fnLimpaCampo($_REQUEST['NOM_DATABASE']);
		$cod_empresa = fnLimpaCampo($_REQUEST['COD_EMPRESA']);
		$nom_empresa = fnLimpaCampo($_REQUEST['NOM_EMPRESA']);

		$des_database = substr($nom_empresa, 0, 20);

		$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
		$actual_link = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		$MODULO = @$_GET['mod'];
		$COD_MODULO = fndecode(@$_GET['mod']);

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		// fnEscreveArray($_REQUEST);


		if ($opcao != '') {

			//mensagem de retorno
			switch ($opcao) {
				case 'CAD':

					$sqlBusca = "SELECT NOM_DATABASE, IP, USUARIODB, SENHADB FROM tab_database 
					WHERE NOM_DATABASE = '$nom_database'
					LIMIT 1";

					$query = mysqli_query($connAdm->connAdm(), trim($sqlBusca));
					if ($qrResult = mysqli_fetch_assoc($query)) {
						$ip = $qrResult['IP'];
						$usuariodb = $qrResult['USUARIODB'];
						$senhadb = $qrResult['SENHADB'];

						$sql = "CALL SP_ALTERA_TAB_DATABASE (
							'" . $cod_database . "', 
							'" . $cod_servidor . "', 
							'" . $des_database . "', 
							'" . $cod_empresa . "', 
							'" . $ip . "', 
							'" . $usuariodb . "', 
							'" . $senhadb . "', 
							'" . $nom_database . "', 
							'" . $nom_empresa . "', 
							'" . $opcao . "'    
						) ";
						// fnEscreve($sql);
						$arrayProcEmp = mysqli_query($connAdm->connAdm(), trim($sql));

						if (!$arrayProcEmp) {
							$cod_erro = Log_error_comand($connAdm->connAdm(), $conn, $cod_empresa, $actual_link, $MODULO, $COD_MODULO, $arrayProcEmp, $nom_usuario);
							$msgRetorno = "Ocorreu um <strong>erro!</strong>";
							$msgTipo = 'alert-danger';
						} else {
							$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
							$msgTipo = 'alert-success';

							$sqlData = "SELECT COD_DATABASE FROM TAB_DATABASE WHERE COD_EMPRESA = $cod_empresa";
							$queryData = mysqli_query($connAdm->connAdm(), trim($sqlData));
							$qrResult = mysqli_fetch_assoc($queryData);
							$cod_database = $qrResult['COD_DATABASE'];

							$sqlAudit = "UPDATE AUDITORIA_EMPRESA SET 
										COD_DATABASE = $cod_database,
										FASE2 = 'S' 
										WHERE COD_EMPRESA = $cod_empresa";

							$arrayProcAudit = mysqli_query($connAdm->connAdm(), trim($sqlAudit));
						}

						// INSERE REGIÃO
						$sqlReg = "SELECT COD_TIPOREG FROM REGIAO_GRUPO WHERE COD_EMPRESA = $cod_empresa AND DES_TIPOREG = 'GERAL'";
						$arrayReg = mysqli_query(connTemp($cod_empresa, ""), trim($sqlReg));
						if (mysqli_num_rows($arrayReg) == 0) {
							$sql = "CALL SP_ALTERA_REGIAO_GRUPO (
								0, 
								'GERAL', 
								'0', 
								'" . $cod_empresa . "', 
								'CAD'    
							) ";
							mysqli_query(connTemp($cod_empresa, ""), trim($sql));
						}

						$sqlReg = "SELECT COD_TIPOREG FROM REGIAO_GRUPO WHERE COD_EMPRESA = $cod_empresa AND DES_TIPOREG = 'GERAL'";
						$arrayReg = mysqli_query(connTemp($cod_empresa, ""), trim($sqlReg));
						$qrResult = mysqli_fetch_assoc($arrayReg);
						$cod_tiporeg = $qrResult['COD_TIPOREG'];

						$updateUnv = "UPDATE UNIDADEVENDA SET COD_TIPOREG = $cod_tiporeg WHERE COD_EMPRESA = $cod_empresa";
						mysqli_query(connTemp($cod_empresa, ""), trim($updateUnv));

						// INSERE GRUPO DE PRODUTOS
					} else {
						$msgRetorno = "Ocorreu um <strong>erro!</strong>";
						$msgTipo = 'alert-danger';
					}

					break;
				case 'ALT':
					$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";
					break;
				case 'EXC':
					$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";
					break;
			}
		}
	}
}


//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode($_GET['id']);
	$sql = "SELECT EMP.COD_EMPRESA, EMP.NOM_FANTASI, TAB.COD_DATABASE FROM empresas AS EMP
	LEFT JOIN TAB_DATABASE AS TAB ON TAB.COD_EMPRESA = EMP.COD_EMPRESA
	where EMP.COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
		$nom_fantasi = $qrBuscaEmpresa['NOM_FANTASI'];
		$cod_database = $qrBuscaEmpresa['COD_DATABASE'];
	}

	//BUSCA AUDITORIA
	$sqlAudit = "SELECT * FROM AUDITORIA_EMPRESA WHERE COD_EMPRESA = $cod_empresa";
	$arrayAudit = mysqli_query($connAdm->connAdm(), $sqlAudit);
	$qrAudit = mysqli_fetch_assoc($arrayAudit);
	if (isset($arrayAudit)) {
		$log_maiscash = $qrAudit['LOG_MAISCASH'];
	}
} else {
	$cod_empresa = 0;
	$cod_database = 0;
	//fnEscreve('entrou else');
}


//fnEscreve($cod_empresa);

?>

<div class="push30"></div>

<div class="row">

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="glyphicon glyphicon-calendar"></i>
					<span class="text-primary"><?php echo $NomePg . " - " . $nom_fantasi; ?></span>
				</div>

				<?php
				$formBack = "1019";
				?>

			</div>
			<div class="portlet-body">

				<?php if ($msgRetorno <> '') { ?>
					<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<?php echo $msgRetorno; ?>
					</div>
				<?php } ?>

				<?php //if ($msgRetorno <> '') { 
				?>
				<div class="alert alert-warning alert-dismissible top30 bottom30" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<?php //echo $msgRetorno; 
					?>
					Para gerar os dados, clique em <strong><i class="fas fa-cogs"></i>&nbsp;&nbsp; Processar</strong>, e depois em <strong>Próximo&nbsp;<i class="fas fa-arrow-right"></i></strong>
				</div>
				<?php //} 
				?>

				<?php $abaEmpresa = 1025; ?>

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

					.notify-badge {
						position: absolute;
						display: flex;
						align-items: center;
						right: 36%;
						top: 10px;
						border-radius: 30px 30px 30px 30px;
						text-align: center;
						color: white;
						font-size: 11px;
					}

					.notify-badge span {
						margin: 0 auto;
					}

					.bg-success {
						background-color: #18bc9c;
					}

					.bg-warning {
						background-color: #f39c12;
					}
				</style>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">



						<div class="container-fluid">

							<div class="passo" id="passo1">


								<div class="row">

									<div class="col-sm-12" style="padding-left: 0;">

										<?php
										$abaAtivo = 2092;
										include 'menuAutomacao.php';
										?>

										<div class="col-xs-10">

											<!-- conteudo abas -->
											<div class="tab-content">


												<!-- aba produtos-->
												<div class="row">
													<h4 style="margin: 0 0 5px 0;"><span class="bolder">Databases</span></h4>
													<small style="font-size: 12px;"></small>

													<ul class="nav nav-tabs">
														<li class="active"><a data-toggle="tab" href="#servers">Definir Database Automaticamente</a></li>
														<!-- <li><a data-toggle="tab" href="#servers">Selecionar Database</a></li> -->
													</ul>

													<div class="row">

														<div class="col-md-12">
															<!-- aba totem -->
															<!-- <div id="databases" class="tab-pane fade in active">
															</div> -->
															<!-- aba totem -->
															<div id="servers" class="tab-pane fade in active">

																<div class="push30"></div>

																<div class="row">

																	<?php
																	$desabilita = "";
																	$andData = "";
																	if ($cod_database != "") {
																		$desabilita = "disabled";
																		$andData = "AND TAB.COD_DATABASE = $cod_database";
																	}

																	$andMc = "";
																	if ($log_maiscash == "S") {
																		$andMc = "AND NOM_DATABASE = 'db_maiscash'";
																	}

																	$sql = "SELECT 
																				SV.COD_SERVIDOR,
																				tab.NOM_DATABASE,
																				Count(distinct tab.cod_empresa) QTD_EMPRESA, 
																				COUNT(uni.COD_UNIVEND) QTD_UNV
																				FROM empresas emp
																				INNER JOIN tab_database tab ON tab.COD_EMPRESA=emp.COD_EMPRESA 
																				INNER JOIN unidadevenda uni ON uni.COD_EMPRESA=emp.COD_EMPRESA
																				INNER JOIN servidores SV ON SV.COD_SERVIDOR=tab.COD_SERVIDOR
																				where emp.LOG_ATIVO='S'
																				$andData
																				$andMc
																				GROUP BY NOM_DATABASE order by NOM_DATABASE ";

																	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

																	// fnEscreve($sql);
																	$encontrouBase = false;
																	$qtd_empresa = 0;
																	$qtd_unvd = 0;
																	while ($qrListaDatabase = mysqli_fetch_assoc($arrayQuery)) {

																		// se o nome não começar com db_host pula para o proximo item da lista
																		if ($log_maiscash != "S" && strpos($qrListaDatabase['NOM_DATABASE'], 'db_host') !== 0) {
																			continue;
																		}

																		$qtd_empresa = $qrListaDatabase['QTD_EMPRESA'];
																		$qtd_unvd = $qrListaDatabase['QTD_UNV'] + $qtd_unvCad;

																		if ($log_maiscash == 'S' || $qtd_empresa < 10 && $qtd_unvd < 200) {
																			$encontrouBase = true;

																	?>
																			<div class="col-md-2 item-bd">

																				<div class="panel">
																					<!-- <a href="action.php?mod=<?php echo fnEncode(2093) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idx=<?php echo fnEncode($qrListaDatabase['COD_DATABASE']) ?>"> -->
																					<div class="top primary">
																						<i class="fa fa-database fa-3x iwhite" aria-hidden="true"></i>
																						<h6><?php echo $qrListaDatabase['NOM_DATABASE'] ?></h6>
																					</div>
																					<div class="bottom" style="height: 90px;">
																						<h2 class="referencia-busca" style="font-size: 18px; margin: 0 0 10px 0;">Qtd. Empresas: <?php echo $qtd_empresa ?> </h2>
																						<h2 class="referencia-busca" style="font-size: 18px; margin: 0 0 10px 0;">Qtd. Unidades: <?php echo $qtd_unvd ?> </h2>
																						<input type="hidden" id='COD_SERVIDOR' name='COD_SERVIDOR' value="<?= $qrListaDatabase['COD_SERVIDOR']; ?>" />
																						<input type="hidden" id='NOM_DATABASE' name='NOM_DATABASE' value="<?= $qrListaDatabase['NOM_DATABASE']; ?>" />
																					</div>
																					<!-- </a> -->
																				</div>

																			</div>

																		<?php
																			break;
																		}

																		//if ($qrListaDatabase['COD_SERVIDOR'] == 1 ) {
																		//	$tipoServer = "fa-windows";	
																		//}else {$tipoServer = "fa-linux";	}
																	}

																	if (!$encontrouBase) {
																		?>
																		<div class="col-md-1"></div>
																		<div class="col-md-6 alert alert-warning alert-dismissible top30 bottom30" role="alert">
																			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
																			Nenhum banco de dados disponível.</br>
																			Entre em contato com o time de desenvolvedores.
																		</div>
																	<?php
																	}

																	?>

																</div>

															</div>

														</div>

													</div>

												</div>

											</div>

										</div>

										<div class="clearfix"></div>

									</div>



									<hr>

									<div class="form-group text-right col-lg-12">
										<button type="submit" name="CAD" id="CAD" class="btn btn-success getBtn" <?= $desabilita ?>><i class="fas fa-cogs"></i>&nbsp;&nbsp;Processar</button>
										<?= $btnProximo ?>
									</div>

									<div class="push10"></div>

								</div>



							</div>


							<div class="push10"></div>

							<input type="hidden" name="opcao" id="opcao" value="">
							<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
							<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">
							<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?= $cod_empresa ?>">
							<input type="hidden" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?= $nom_empresa ?>">
							<input type="hidden" name="FEZ_UPLOAD" id="FEZ_UPLOAD" value="N">

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

			// $.ajax({
			// 	type: "POST",
			// 	url: "ajxAutomacaoEmpresa.php?acao=1&id=<?php echo $cod_empresa; ?>",
			// 	/*beforeSend:function(){
			// 		$("#passo1").html('<div class="loading" style="width: 100%;"></div>');
			// 	},*/
			// 	success:function(data){	

			// 		$('#passo1').hide();
			// 		$('#passo2').show();
			// 		$("#passo2").html(data);
			// 		$("#step2 div.fundo, #step2 a.btn").addClass('fundoAtivo');

			// 	}						
			// });
		});

	});
</script>