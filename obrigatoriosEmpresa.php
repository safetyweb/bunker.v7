<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
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
$abaEmpresa = "";
$abaModulo = "";
$qrBuscaCampos = "";
$qtdBlocos = 0;
$checado = "";
$tipoCampo = "";
$sql2 = "";
$arrayQuery2 = [];
$qrBuscaCampos2 = "";
$qtdBlocos2 = 0;
$checadoObg = "";
$checadoReq = "";
$checadoCad = "";
$checadoOpc = "";
$checadoTkn = "";
$tipoCampoObg = "";
$tipoCampoReq = "";
$tipoCampoCad = "";
$tipoCampoOpc = "";
$tipoCampoTkn = "";
$sql3 = "";
$arrayQuery3 = [];
$qrBuscaCampos3 = "";
$qtdBlocos3 = 0;
$sql4 = "";
$arrayQuery4 = [];
$qrBuscaCampos4 = "";
$qtdBlocos4 = 0;



$hashLocal = mt_rand();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

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

		if ($opcao == '999') {
			//if ($opcao != '' && $opcao != 0){


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
	$sql = "SELECT COD_EMPRESA, NOM_EMPRESA FROM empresas where COD_EMPRESA = '" . $cod_empresa . "' ";
	//fnEscreve($sql);
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($arrayQuery)) {
		$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
		$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
	}
} else {
	$cod_empresa = 0;
	//fnEscreve('entrou else');
}

//busca dados da url	
if (is_numeric(fnLimpacampo(fnDecode(@$_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode(@$_GET['id']);
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
				//abas - via empresa
				if (fnLimpacampo(fnDecode(@$_GET['mod'])) == 1101) {
					$formBack = "1019";
				}
				//abas - via campos obrigatórios
				if (fnLimpacampo(fnDecode(@$_GET['mod'])) == 1157) {
					$formBack = "1156";
				}
				//abas - via integradora
				if (fnLimpacampo(fnDecode(@$_GET['mod'])) == 1160) {
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
				//abas - via empresa
				/*
									if (fnLimpacampo(fnDecode(@$_GET['mod'])) == 1101){
									$abaEmpresa = 1101; 
									include "abasEmpresaConfig.php";
									}
									//abas - via campos obrigatórios
									if (fnLimpacampo(fnDecode(@$_GET['mod'])) == 1157){
									$abaModulo = 1157; 
									include "abasCamposObrigatorios.php";
									}
									//abas - via integradora
									if (fnLimpacampo(fnDecode(@$_GET['mod'])) == 1160){
									$abaModulo = 1160; 
									include "abasIntegradora.php";
									}
									*/
				$abaEmpresa = 1101;
				switch ($_SESSION["SYS_COD_SISTEMA"]) {
					case 14: //rede duque
						include "abasEmpresaDuque.php";
						break;
					case 15: //quiz
						include "abasEmpresaQuiz.php";
						break;
					case 18: //mais cash
						include "abasMaisCash.php";
						break;
					default;
						//include "abasEmpresaConfig.php";
						break;
				}

				?>


				<div class="push30"></div>

				<style>
					div.vertical {
						margin-left: -85px;
						margin-right: -85px;
						margin-top: 30px;
						width: auto;
						transform: rotate(-90deg);
						-webkit-transform: rotate(-90deg);
						/* Safari/Chrome */
						-moz-transform: rotate(-90deg);
						/* Firefox */
						-o-transform: rotate(-90deg);
						/* Opera */
						-ms-transform: rotate(-90deg);
						/* IE 9 */
						white-space: nowrap;
					}

					td.vertical {
						height: 250px;
						line-height: 1.42857143;
						padding-bottom: 20px;
						text-align: left;
						font-height: bold;

					}

					td {
						text-align: center !important;
						vertical-align: middle !important;
					}

					.checkbox input[type="checkbox"] {
						position: relative;
						margin-left: 0;
					}
				</style>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<div class="push10"></div>

						<table class="table table-bordered table-hover" style="min-width: 500px; width: auto;">
							<thead>
								<tr>
									<th width="40"></th>
									<th>Nome do Campo <div class="push20"></div>
									</th>
									<!--
													  <th class="text-center">1 Obrigatório <div class="push20"></div></th> --obg
													  <th class="text-center">2 Opcional <div class="push20"></div></th> -- req
													  <th class="text-center">3 Complementar <div class="push20"></div></th> -- opc 
													  <th class="text-center">4 Qualidade <br/> Cadastro</th> --cad
													  <th class="text-center">5 Inicial/Token <div class="push20"></div></th> --tkn
													  -->
									<th class="text-center">Inicial/Token <div class="push20"></div>
									</th>
									<th class="text-center">Obrigatório <div class="push20"></div>
									</th>
									<th class="text-center">Obrigatário <br /> Pós Cadastro
				</div>
				</th>
				<th class="text-center">Opcional <div class="push20"></div>
				</th>
				<th class="text-center">Qualidade <br /> Cadastro</th>
				</tr>
				</thead>
				<tbody>

					<?php

					$sql = "select a.*,
															(select count(*) from empresas b where b.cod_chaveco = a.cod_chaveco and b.cod_empresa = $cod_empresa ) as TEM_CHAVE,
															(select count(*) from CHAVECADASTRO) as QTD_BLOCOS
															from CHAVECADASTRO a order by a.DES_CHAVECO";

					//fnEscreve($sql);	
					$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

					$count = 0;
					while ($qrBuscaCampos = mysqli_fetch_assoc($arrayQuery)) {
						$qtdBlocos = $qrBuscaCampos['QTD_BLOCOS'];
						if ($qrBuscaCampos['TEM_CHAVE'] > 0) {
							$checado = "checked";
						} else {
							$checado = "";
						}
						$tipoCampo = '"KEY"';
						$count++;

						echo "
															<tr>";
						if ($count == 1) {
							echo "
															  <td rowspan='" . $qtdBlocos . "'> <div class='vertical' style='margin-top: -5px;'><b>Chave</b></div></td>";
						}

						//matriz campos obrigatórios - fidelidade
						if ((fnDecode(@$_GET['mod']) == 1157) || (fnDecode(@$_GET['mod']) == 1101)) {
							echo "
																	  <td><div class='text-left'> " . $qrBuscaCampos['DES_CHAVECO'] . "</div></td>
																	  <td></td>
																	  <td>
																		<div class='checkbox checkbox-primary'>
																			<input type='radio' name='OBG' id='OBG_" . $count . "' " . $checado . " disabled>
																			<label for='OBG_" . $count . "'>&nbsp;</label>
																		</div>												  
																	  </td>
																	  
																	  <td></td>
																	  <td></td>
																	  <td></td>
																	</tr>
																	";
						} else {
							if ($qrBuscaCampos['COD_MATRIZ'] > 0) {
								echo "
																	  <td><div class='text-left'> " . $qrBuscaCampos['DES_CHAVECO'] . "</div></td>
																	  <td></td>
																	  <td>
																		<div class='checkbox checkbox-primary'>FDFF
																			<i class='fal fa-check' aria-hidden='true'></i>																		
																		</div>												  
																	  </td>
																	  
																	  <td></td>
																	  <td></td>
																	  <td></td>
																	</tr>
																	";
							} else {
								echo "
																	  <td><div class='text-left'> " . $qrBuscaCampos['DES_CHAVECO'] . "</div></td>
																	  <td></td>
																	  <td> 
																		<div class='checkbox checkbox-primary'>
																		";

								if ($checado == 'checked') {
									echo "<i class='fal fa-check' aria-hidden='true'></i>";
								}

								echo "		
																		</div>												  
																	  </td>																	  
																	  <td></td>
																	  <td></td>
																	  <td></td>
																	</tr>
																	";
							}
						}
					}

					//bloco campos gerais - GRL
					$sql2 = "select A.*, 
															(select COUNT(*) from INTEGRA_CAMPOOBG where TIP_BLOCOOBG = 'GRL') as QTD_BLOCOS, 
															(select COUNT(*) from matriz_campo_integracao B where B.cod_campoobg = A.COD_CAMPOOBG AND TIP_CAMPOOBG='OBG' AND B.COD_EMPRESA = $cod_empresa ) CAMPO_OBG,
															(select COUNT(*) from matriz_campo_integracao B where B.cod_campoobg = A.COD_CAMPOOBG AND TIP_CAMPOOBG='REQ' AND B.COD_EMPRESA = $cod_empresa ) CAMPO_REQ,
															(select COUNT(*) from matriz_campo_integracao B where B.cod_campoobg = A.COD_CAMPOOBG AND TIP_CAMPOOBG='OPC' AND B.COD_EMPRESA = $cod_empresa ) CAMPO_OPC,
															(select COUNT(*) from matriz_campo_integracao B where B.cod_campoobg = A.COD_CAMPOOBG AND TIP_CAMPOOBG='CAD' AND B.COD_EMPRESA = $cod_empresa ) CAMPO_CAD,
															(select COUNT(*) from matriz_campo_integracao B where B.cod_campoobg = A.COD_CAMPOOBG AND TIP_CAMPOOBG='TKN' AND B.COD_EMPRESA = $cod_empresa ) CAMPO_INI
															from INTEGRA_CAMPOOBG A where TIP_BLOCOOBG = 'GRL' 
															order by NUM_ORDENAC";

					$arrayQuery2 = mysqli_query($connAdm->connAdm(), $sql2);

					//fnEscreve($sql2);

					$count = 0;
					while ($qrBuscaCampos2 = mysqli_fetch_assoc($arrayQuery2)) {
						$qtdBlocos2 = $qrBuscaCampos2['QTD_BLOCOS'];
						if ($qrBuscaCampos2['CAMPO_OBG'] > 0) {
							$checadoObg = "checked";
						} else {
							$checadoObg = "";
						}
						if ($qrBuscaCampos2['CAMPO_REQ'] > 0) {
							$checadoReq = "checked";
						} else {
							$checadoReq = "";
						}
						if ($qrBuscaCampos2['CAMPO_CAD'] > 0) {
							$checadoCad = "checked";
						} else {
							$checadoCad = "";
						}
						if ($qrBuscaCampos2['CAMPO_OPC'] > 0) {
							$checadoOpc = "checked";
						} else {
							$checadoOpc = "";
						}
						if ($qrBuscaCampos2['CAMPO_INI'] > 0) {
							$checadoTkn = "checked";
						} else {
							$checadoTkn = "";
						}
						$tipoCampoObg = '"OBG"';
						$tipoCampoReq = '"REQ"';
						$tipoCampoCad = '"CAD"';
						$tipoCampoOpc = '"OPC"';
						$tipoCampoTkn = '"TKN"';
						$count++;
						echo "
															<tr>";
						if ($count == 1) {
							echo "
															  <td rowspan='" . $qtdBlocos2 . "'> <div class='vertical' style='margin-top: -5px;'><b>Gerais</b></div></td>";
						}

						//matriz campos obrigatórios - fidelidade
						if ((fnDecode(@$_GET['mod']) == 1157) || (fnDecode(@$_GET['mod']) == 1101)) {
							echo "
																	  <td><div class='text-left'> " . $qrBuscaCampos2['NOM_CAMPOOBG'] . " <small class='f12'>" . $qrBuscaCampos2['COD_CAMPOOBG'] . "</small></div></td>
																	  
																	  <td><!--5G-->
																			<div class='checkbox checkbox-primary'>
																				<input type='checkbox' id='addTkn_" . $qrBuscaCampos2['COD_CAMPOOBG'] . "' onclick='checkCadastro(" . $tipoCampoTkn . "," . $qrBuscaCampos2['COD_CAMPOOBG'] . ",this);' " . $checadoTkn . ">
																				<label for='addTkn_" . $qrBuscaCampos2['COD_CAMPOOBG'] . "'>&nbsp;</label>
																			</div>	
																	  </td>
																	  
																	  <td><!--1G-->
																			<div class='checkbox checkbox-primary'>
																				<input type='checkbox' id='addObg_" . $qrBuscaCampos2['COD_CAMPOOBG'] . "' onclick='checkCadastro(" . $tipoCampoObg . "," . $qrBuscaCampos2['COD_CAMPOOBG'] . ",this);' " . $checadoObg . ">
																				<label for='addObg_" . $qrBuscaCampos2['COD_CAMPOOBG'] . "'>&nbsp;</label>
																			</div>	
																	  </td>
																	  
																	  <td><!--3G-->
																			<div class='checkbox checkbox-primary'>
																				<input type='checkbox' id='addOpc_" . $qrBuscaCampos2['COD_CAMPOOBG'] . "' onclick='checkCadastro(" . $tipoCampoOpc . "," . $qrBuscaCampos2['COD_CAMPOOBG'] . ",this);' " . $checadoOpc . ">
																				<label for='addOpc_" . $qrBuscaCampos2['COD_CAMPOOBG'] . "'>&nbsp;</label>
																			</div>	
																	  </td>
																	  
																	  <td><!--2G-->
																			<div class='checkbox checkbox-primary'>
																				<input type='checkbox' id='addReq_" . $qrBuscaCampos2['COD_CAMPOOBG'] . "' onclick='checkCadastro(" . $tipoCampoReq . "," . $qrBuscaCampos2['COD_CAMPOOBG'] . ",this);' " . $checadoReq . ">
																				<label for='addReq_" . $qrBuscaCampos2['COD_CAMPOOBG'] . "'>&nbsp;</label>
																			</div>	
																	  </td>
																	  
																	  <td><!--4G-->
																			<div class='checkbox checkbox-primary'>
																				<input type='checkbox' id='addCad_" . $qrBuscaCampos2['COD_CAMPOOBG'] . "' onclick='checkCadastro(" . $tipoCampoCad . "," . $qrBuscaCampos2['COD_CAMPOOBG'] . ",this);' " . $checadoCad . ">
																				<label for='addCad_" . $qrBuscaCampos2['COD_CAMPOOBG'] . "'>&nbsp;</label>
																			</div>	
																	  </td>


																	  
																	</tr>
																	";
						} else {

							if ($qrBuscaCampos2['CAMPO_INI'] > 0) {
								echo "
																		  <td><div class='text-left'> " . $qrBuscaCampos2['NOM_CAMPOOBG'] . "</div></td>
																		  <td>
																			  <i class='fal fa-check' aria-hidden='true'></i>	
																		  </td>
																		";
							} else {
								echo "
																		  <td><div class='text-left'> " . $qrBuscaCampos2['NOM_CAMPOOBG'] . "</div></td>
																		  <td>
																		  </td>
																		";
							}

							if ($qrBuscaCampos2['CAMPO_OBG'] > 0) {
								echo "
																		  <td>
																			  <i class='fal fa-check' aria-hidden='true'></i>	
																		  </td>
																		";
							} else {
								echo "
																		  <td>
																		  </td>
																		";
							}

							if ($qrBuscaCampos2['CAMPO_OPC'] > 0) {
								echo "
																		  <td>
																			  <i class='fal fa-check' aria-hidden='true'></i>	
																		  </td>
																		";
							} else {
								echo "
																		  <td>
																		  </td>
																		";
							}

							if ($qrBuscaCampos2['CAMPO_REQ'] > 0) {
								echo "
																		  <td>
																			  <i class='fal fa-check' aria-hidden='true'></i>	
																		  </td>
																		";
							} else {
								echo "
																		  <td>
																		  </td>
																		";
							}

							if ($qrBuscaCampos2['CAMPO_CAD'] > 0) {
								echo "
																		  <td>
																			  <i class='fal fa-check' aria-hidden='true'></i>	
																		  </td>
																		";
							} else {
								echo "
																		  <td>
																		  </td>
																		";
							}
						}
					}

					//bloco campos comunicação - COM
					$sql3 = "select A.*, 
															(select COUNT(*) from INTEGRA_CAMPOOBG where TIP_BLOCOOBG = 'COM') as QTD_BLOCOS, 
															(select COUNT(*) from matriz_campo_integracao B where B.cod_campoobg = A.COD_CAMPOOBG AND TIP_CAMPOOBG='OBG' AND B.COD_EMPRESA = $cod_empresa ) CAMPO_OBG,
															(select COUNT(*) from matriz_campo_integracao B where B.cod_campoobg = A.COD_CAMPOOBG AND TIP_CAMPOOBG='REQ' AND B.COD_EMPRESA = $cod_empresa ) CAMPO_REQ,
															(select COUNT(*) from matriz_campo_integracao B where B.cod_campoobg = A.COD_CAMPOOBG AND TIP_CAMPOOBG='OPC' AND B.COD_EMPRESA = $cod_empresa ) CAMPO_OPC,
															(select COUNT(*) from matriz_campo_integracao B where B.cod_campoobg = A.COD_CAMPOOBG AND TIP_CAMPOOBG='CAD' AND B.COD_EMPRESA = $cod_empresa ) CAMPO_CAD,
															(select COUNT(*) from matriz_campo_integracao B where B.cod_campoobg = A.COD_CAMPOOBG AND TIP_CAMPOOBG='TKN' AND B.COD_EMPRESA = $cod_empresa ) CAMPO_INI
															from INTEGRA_CAMPOOBG A where TIP_BLOCOOBG = 'COM'  
															order by NUM_ORDENAC";

					$arrayQuery3 = mysqli_query($connAdm->connAdm(), $sql3);

					//fntestesql($connAdm->connAdm(),$sql3);
					//fnEscreve($sql3);

					$count = 0;
					while ($qrBuscaCampos3 = mysqli_fetch_assoc($arrayQuery3)) {
						$qtdBlocos3 = $qrBuscaCampos3['QTD_BLOCOS'];
						if ($qrBuscaCampos3['CAMPO_OBG'] != 0) {
							$checadoObg = "checked";
						} else {
							$checadoObg = "";
						}
						if ($qrBuscaCampos3['CAMPO_REQ'] != 0) {
							$checadoReq = "checked";
						} else {
							$checadoReq = "";
						}
						if ($qrBuscaCampos3['CAMPO_CAD'] != 0) {
							$checadoCad = "checked";
						} else {
							$checadoCad = "";
						}
						if ($qrBuscaCampos3['CAMPO_OPC'] != 0) {
							$checadoOpc = "checked";
						} else {
							$checadoOpc = "";
						}
						if ($qrBuscaCampos3['CAMPO_INI'] != 0) {
							$checadoTkn = "checked";
						} else {
							$checadoTkn = "";
						}
						$tipoCampoObg = '"OBG"';
						$tipoCampoReq = '"REQ"';
						$tipoCampoCad = '"CAD"';
						$tipoCampoOpc = '"OPC"';
						$tipoCampoTkn = '"TKN"';
						$count++;
						echo "
															<tr>";
						if ($count == 1) {
							echo "
															  <td rowspan='" . $qtdBlocos3 . "'> <div class='vertical' style='margin-top: -5px;'><b>Comunicação</b></div></td>";
						}

						//matriz campos obrigatórios - fidelidade
						if ((fnDecode(@$_GET['mod']) == 1157) || (fnDecode(@$_GET['mod']) == 1101)) {
							echo "
																	  <td><div class='text-left'> " . $qrBuscaCampos3['NOM_CAMPOOBG'] . " <small class='f12'>" . $qrBuscaCampos3['COD_CAMPOOBG'] . "</small></div></td>
																	  
																	  <td><!--5C-->
																			<div class='checkbox checkbox-primary'>
																				<input type='checkbox' id='addTkn_" . $qrBuscaCampos3['COD_CAMPOOBG'] . "' onclick='checkCadastro(" . $tipoCampoTkn . "," . $qrBuscaCampos3['COD_CAMPOOBG'] . ",this);' " . $checadoTkn . ">
																				<label for='addTkn_" . $qrBuscaCampos3['COD_CAMPOOBG'] . "'>&nbsp;</label>
																			</div>	
																	  </td>
																	  
																	  <td><!--1C-->
																			<div class='checkbox checkbox-primary'>
																				<input type='checkbox' id='addObg_" . $qrBuscaCampos3['COD_CAMPOOBG'] . "' onclick='checkCadastro(" . $tipoCampoObg . "," . $qrBuscaCampos3['COD_CAMPOOBG'] . ",this);' " . $checadoObg . ">
																				<label for='addObg_" . $qrBuscaCampos3['COD_CAMPOOBG'] . "'>&nbsp;</label>
																			</div>	
																	  </td>
																	  
																	  <td><!--3C-->
																			<div class='checkbox checkbox-primary'>
																				<input type='checkbox' id='addOpc_" . $qrBuscaCampos3['COD_CAMPOOBG'] . "' onclick='checkCadastro(" . $tipoCampoOpc . "," . $qrBuscaCampos3['COD_CAMPOOBG'] . ",this);' " . $checadoOpc . ">
																				<label for='addOpc_" . $qrBuscaCampos3['COD_CAMPOOBG'] . "'>&nbsp;</label>
																			</div>	
																	  </td>
																	  
																	  <td><!--2C-->
																			<div class='checkbox checkbox-primary'>
																				<input type='checkbox' id='addReq_" . $qrBuscaCampos3['COD_CAMPOOBG'] . "' onclick='checkCadastro(" . $tipoCampoReq . "," . $qrBuscaCampos3['COD_CAMPOOBG'] . ",this);' " . $checadoReq . ">
																				<label for='addReq_" . $qrBuscaCampos3['COD_CAMPOOBG'] . "'>&nbsp;</label>
																			</div>	
																	  </td>

																	  <td><!--4C-->
																			<div class='checkbox checkbox-primary'>
																				<input type='checkbox' id='addCad_" . $qrBuscaCampos3['COD_CAMPOOBG'] . "' onclick='checkCadastro(" . $tipoCampoCad . "," . $qrBuscaCampos3['COD_CAMPOOBG'] . ",this);' " . $checadoCad . ">
																				<label for='addCad_" . $qrBuscaCampos3['COD_CAMPOOBG'] . "'>&nbsp;</label>
																			</div>	
																	  </td>


																	  
																	</tr>
																	";
						} else {


							if ($qrBuscaCampos2['CAMPO_INI'] > 0) {
								echo "
																		  <td><div class='text-left'> " . $qrBuscaCampos3['NOM_CAMPOOBG'] . "</div></td>
																		  <td>
																			  <i class='fal fa-check' aria-hidden='true'></i>	
																		  </td>
																		";
							} else {
								echo "
																		  <td><div class='text-left'> " . $qrBuscaCampos3['NOM_CAMPOOBG'] . "</div></td>
																		  <td>
																		  </td>
																		";
							}

							if ($qrBuscaCampos3['CAMPO_OBG'] > 0) {
								echo "
																		  <td>
																			  <i class='fal fa-check' aria-hidden='true'></i>	
																		  </td>
																		";
							} else {
								echo "
																		  <td>
																		  </td>
																		";
							}

							if ($qrBuscaCampos3['CAMPO_OPC'] > 0) {
								echo "
																		  <td>
																			  <i class='fal fa-check' aria-hidden='true'></i>	
																		  </td>
																		";
							} else {
								echo "
																		  <td>
																		  </td>
																		";
							}

							if ($qrBuscaCampos3['CAMPO_REQ'] > 0) {
								echo "
																		  <td>
																			  <i class='fal fa-check' aria-hidden='true'></i>	
																		  </td>
																		";
							} else {
								echo "
																		  <td>
																		  </td>
																		";
							}

							if ($qrBuscaCampos3['CAMPO_CAD'] > 0) {
								echo "
																		  <td>
																			  <i class='fal fa-check' aria-hidden='true'></i>	
																		  </td>
																		";
							} else {
								echo "
																		  <td>
																		  </td>
																		";
							}
						}
					}



					//bloco campos localização - LOC
					$sql4 = "select A.*, 
															(select COUNT(*) from INTEGRA_CAMPOOBG where TIP_BLOCOOBG = 'LOC') as QTD_BLOCOS, 
															(select COUNT(*) from matriz_campo_integracao B where B.cod_campoobg = A.COD_CAMPOOBG AND TIP_CAMPOOBG='OBG' AND B.COD_EMPRESA = $cod_empresa ) CAMPO_OBG,
															(select COUNT(*) from matriz_campo_integracao B where B.cod_campoobg = A.COD_CAMPOOBG AND TIP_CAMPOOBG='REQ' AND B.COD_EMPRESA = $cod_empresa ) CAMPO_REQ,
															(select COUNT(*) from matriz_campo_integracao B where B.cod_campoobg = A.COD_CAMPOOBG AND TIP_CAMPOOBG='OPC' AND B.COD_EMPRESA = $cod_empresa ) CAMPO_OPC,
															(select COUNT(*) from matriz_campo_integracao B where B.cod_campoobg = A.COD_CAMPOOBG AND TIP_CAMPOOBG='CAD' AND B.COD_EMPRESA = $cod_empresa ) CAMPO_CAD,
															(select COUNT(*) from matriz_campo_integracao B where B.cod_campoobg = A.COD_CAMPOOBG AND TIP_CAMPOOBG='TKN' AND B.COD_EMPRESA = $cod_empresa ) CAMPO_INI
															from INTEGRA_CAMPOOBG A where TIP_BLOCOOBG = 'LOC' 
															order by NUM_ORDENAC";

					$arrayQuery4 = mysqli_query($connAdm->connAdm(), $sql4);

					//fntestesql($connAdm->connAdm(),$sql4);
					//fnEscreve($sql4);

					$count = 0;
					while ($qrBuscaCampos4 = mysqli_fetch_assoc($arrayQuery4)) {
						$qtdBlocos4 = $qrBuscaCampos4['QTD_BLOCOS'];
						if ($qrBuscaCampos4['CAMPO_OBG'] > 0) {
							$checadoObg = "checked";
						} else {
							$checadoObg = "";
						}
						if ($qrBuscaCampos4['CAMPO_REQ'] > 0) {
							$checadoReq = "checked";
						} else {
							$checadoReq = "";
						}
						if ($qrBuscaCampos4['CAMPO_CAD'] > 0) {
							$checadoCad = "checked";
						} else {
							$checadoCad = "";
						}
						if ($qrBuscaCampos4['CAMPO_OPC'] > 0) {
							$checadoOpc = "checked";
						} else {
							$checadoOpc = "";
						}
						if ($qrBuscaCampos4['CAMPO_INI'] > 0) {
							$checadoTkn = "checked";
						} else {
							$checadoTkn = "";
						}
						$tipoCampoObg = '"OBG"';
						$tipoCampoReq = '"REQ"';
						$tipoCampoCad = '"CAD"';
						$tipoCampoOpc = '"OPC"';
						$tipoCampoTkn = '"TKN"';
						$count++;
						echo "
															<tr>";
						if ($count == 1) {
							echo "
															  <td rowspan='" . $qtdBlocos4 . "'> <div class='vertical' style='margin-top: -5px;'><b>Localização</b></div></td>";
						}

						//matriz campos obrigatórios - fidelidade
						if ((fnDecode(@$_GET['mod']) == 1157) || (fnDecode(@$_GET['mod']) == 1101)) {
							echo "
																	  <td><div class='text-left'> " . $qrBuscaCampos4['NOM_CAMPOOBG'] . " - " . $qrBuscaCampos4['COD_CAMPOOBG'] . "</div></td>
																	  
																	  <td><!--5L-->
																			<div class='checkbox checkbox-primary'>
																				<input type='checkbox' id='addTkn_" . $qrBuscaCampos4['COD_CAMPOOBG'] . "' onclick='checkCadastro(" . $tipoCampoTkn . "," . $qrBuscaCampos4['COD_CAMPOOBG'] . ",this);' " . $checadoTkn . ">
																				<label for='addTkn_" . $qrBuscaCampos4['COD_CAMPOOBG'] . "'>&nbsp;</label>
																			</div>	
																	  </td>

																	  <td><!--1L-->
																			<div class='checkbox checkbox-primary'>
																				<input type='checkbox' id='addObg_" . $qrBuscaCampos4['COD_CAMPOOBG'] . "' onclick='checkCadastro(" . $tipoCampoObg . "," . $qrBuscaCampos4['COD_CAMPOOBG'] . ",this);' " . $checadoObg . ">
																				<label for='addObg_" . $qrBuscaCampos4['COD_CAMPOOBG'] . "'>&nbsp;</label>
																			</div>	
																	  </td>
																	  
																	  <td><!--3L-->
																			<div class='checkbox checkbox-primary'>
																				<input type='checkbox' id='addOpc_" . $qrBuscaCampos4['COD_CAMPOOBG'] . "' onclick='checkCadastro(" . $tipoCampoOpc . "," . $qrBuscaCampos4['COD_CAMPOOBG'] . ",this);' " . $checadoOpc . ">
																				<label for='addOpc_" . $qrBuscaCampos4['COD_CAMPOOBG'] . "'>&nbsp;</label>
																			</div>	
																	  </td>
																	  

																	  <td><!--2L-->
																			<div class='checkbox checkbox-primary'>
																				<input type='checkbox' id='addReq_" . $qrBuscaCampos4['COD_CAMPOOBG'] . "' onclick='checkCadastro(" . $tipoCampoReq . "," . $qrBuscaCampos4['COD_CAMPOOBG'] . ",this);' " . $checadoReq . ">
																				<label for='addReq_" . $qrBuscaCampos4['COD_CAMPOOBG'] . "'>&nbsp;</label>
																			</div>	
																	  </td>
																	 
																	  <td><!--4L-->
																			<div class='checkbox checkbox-primary'>
																				<input type='checkbox' id='addCad_" . $qrBuscaCampos4['COD_CAMPOOBG'] . "' onclick='checkCadastro(" . $tipoCampoCad . "," . $qrBuscaCampos4['COD_CAMPOOBG'] . ",this);' " . $checadoCad . ">
																				<label for='addCad_" . $qrBuscaCampos4['COD_CAMPOOBG'] . "'>&nbsp;</label>
																			</div>	
																	  </td>
																	  
																	</tr>
																	";
						} else {


							if ($qrBuscaCampos4['CAMPO_INI'] > 0) {
								echo "
																		  <td><div class='text-left'> " . $qrBuscaCampos2['NOM_CAMPOOBG'] . "</div></td>
																		  <td>
																			  <i class='fal fa-check' aria-hidden='true'></i>	
																		  </td>
																		";
							} else {
								echo "
																		  <td><div class='text-left'> " . $qrBuscaCampos2['NOM_CAMPOOBG'] . "</div></td>
																		  <td>
																		  </td>
																		";
							}

							if ($qrBuscaCampos4['CAMPO_OBG'] > 0) {
								echo "
																		  <td>
																			  <i class='fal fa-check' aria-hidden='true'></i>	
																		  </td>
																		";
							} else {
								echo "
																		  <td>
																		  </td>
																		";
							}

							if ($qrBuscaCampos4['CAMPO_OPC'] > 0) {
								echo "
																		  <td>
																			  <i class='fal fa-check' aria-hidden='true'></i>	
																		  </td>
																		";
							} else {
								echo "
																		  <td>
																		  </td>
																		";
							}

							if ($qrBuscaCampos4['CAMPO_REQ'] > 0) {
								echo "
																		  <td>
																			  <i class='fal fa-check' aria-hidden='true'></i>	
																		  </td>
																		";
							} else {
								echo "
																		  <td>
																		  </td>
																		";
							}

							if ($qrBuscaCampos4['CAMPO_CAD'] > 0) {
								echo "
																		  <td>
																			  <i class='fal fa-check' aria-hidden='true'></i>	
																		  </td>
																		";
							} else {
								echo "
																		  <td>
																		  </td>
																		";
							}
						}
					}


					?>

				</tbody>
				</table>

				<div class="push10"></div>

				<input type="hidden" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa ?>">
				<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa ?>">

				<input type="hidden" name="opcao" id="opcao" value="">
				<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
				<input type="hidden" name="hHabilitado" id="hHabilitado" value="N">

				<div class="push5"></div>

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
	function checkCadastro(tipo, idCampo, campo) {
		// alert("tipo: "+ tipo +"\ncampo: "+ idCampo );
		// alert("campo: "+ campo.id );
		var opcao = "";
		if ($(campo).prop('checked') == true) {
			//alert("selected");

			if (tipo == "OBG") {
				$("#addReq_" + idCampo).prop('checked', true);
			}

			opcao = "CAD";

		} else {
			//alert("deselect");
			opcao = "EXC";
		}

		$.ajax({
			type: "POST",
			url: "ajxMatrizCadastro.php",
			data: {
				TIPO: tipo,
				ID_CAMPO: idCampo,
				COD_EMPRESA: "<?= fnEncode($cod_empresa) ?>",
				OPCAO: opcao
			},
			beforeSend: function() {
				$('#blocker').fadeIn(1);
			},
			success: function(data) {
				$('#blocker').fadeOut(1);
				console.log(data);
			},
			error: function() {
				//$('#div_Matriz').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
			}
		});

	}
</script>