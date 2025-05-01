<?php

//echo fnDebug('true');

$connAdmSACV = $connAdmSAC->connAdm();

$hashLocal = mt_rand();
$cod_atendimento = fnLimpaCampoZero(fnDecode($_GET['idC']));
$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(implode($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_atendimento = fnLimpacampo($_REQUEST['COD_ATENDIMENTO']);
		$nom_chamado = fnLimpacampo($_REQUEST['NOM_CHAMADO']);
		$cod_empresa = fnLimpacampo($_REQUEST['COD_EMPRESA']);
		$dat_cadastr = fnDateSql(fnLimpacampo($_REQUEST['DAT_CADASTR']));
		$dat_chamado = fnDataSql(fnLimpacampo($_REQUEST['DAT_CHAMADO']));
		$dat_entrega = fnDataSql(fnLimpacampo($_REQUEST['DAT_ENTREGA']));
		$cod_externo = fnLimpacampo($_REQUEST['COD_EXTERNO']);
		$url = fnLimpacampo($_REQUEST['URL']);
		$des_email = fnLimpacampo($_REQUEST['DES_EMAIL']);
		$num_telefone = fnLimpacampo($_REQUEST['NUM_TELEFONE']);
		$cod_integradora = fnLimpacampoZero($_REQUEST['COD_INTEGRADORA']);
		$cod_tpsolicitacao = fnLimpacampoZero($_REQUEST['COD_TPSOLICITACAO']);
		$cod_versaointegra = fnLimpacampoZero($_REQUEST['COD_VERSAOINTEGRA']);
		$cod_prioridade = fnLimpacampoZero($_REQUEST['COD_PRIORIDADE']);
		$cod_univend_ate = fnLimpacampoZero($_REQUEST['COD_UNIVEND_ATE']);
		$cod_status = fnLimpacampoZero($_REQUEST['COD_STATUS']);
		$des_sac = fnLimpacampo($_REQUEST['DES_SAC']);
		$sac_anexo = fnLimpacampo($_REQUEST['SAC_ANEXO']);
		$cod_refdown = fnLimpacampo($_REQUEST['COD_REFDOWN']);
		$primeiroUp = fnLimpaCampo($_REQUEST['PRIMEIRO_UP']);
		$count_filtros = fnLimpacampo($_REQUEST['COUNT_FILTROS']);

		if (isset($_POST['COD_USUARIOS_ENV'])) {
			$Arr_COD_USUARIOS_ENV = $_POST['COD_USUARIOS_ENV'];

			for ($i = 0; $i < count($Arr_COD_USUARIOS_ENV); $i++) {
				$cod_usuarios_env = $cod_usuarios_env . $Arr_COD_USUARIOS_ENV[$i] . ",";
			}
			$cod_usuarios_env = substr($cod_usuarios_env, 0, -1);
		} else {
			$cod_usuarios_env = "0";
		}

		if (isset($_POST['COD_CONSULTORES'])) {
			$Arr_COD_CONSULTORES = $_POST['COD_CONSULTORES'];

			for ($i = 0; $i < count($Arr_COD_CONSULTORES); $i++) {
				$cod_consultores = $cod_consultores . $Arr_COD_CONSULTORES[$i] . ",";
			}
			$cod_consultores = substr($cod_consultores, 0, -1);
		} else {
			$cod_consultores = "0";
		}

		if (isset($_POST['COD_CLIENTES_ENV'])) {
			$Arr_COD_CLIENTES_ENV = $_POST['COD_CLIENTES_ENV'];

			for ($i = 0; $i < count($Arr_COD_CLIENTES_ENV); $i++) {
				$cod_clientes_env = $cod_clientes_env . $Arr_COD_CLIENTES_ENV[$i] . ",";
			}
			$cod_clientes_env = substr($cod_clientes_env, 0, -1);
		} else {
			$cod_clientes_env = "0";
		}

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];

		$zero = "0";
		$default = "3";

		//fnEscreve($opcao);

		if ($opcao != '') {

			if ($opcao == 'CAD') {


				$sql = "INSERT INTO ATENDIMENTO_CHAMADOS(
									NOM_CHAMADO,
									COD_EMPRESA,
									DAT_CADASTR,
									DAT_CHAMADO,
									DAT_ENTREGA,
									COD_EXTERNO,
									COD_PRIORIDADE,
									COD_STATUS,
									COD_TPSOLICITACAO,
									DES_SAC,
									COD_SOLICITANTE,
									COD_USURES,
									COD_USUARIOS_ENV,
									COD_CLIENTES_ENV,
									COD_UNIVEND_ATE,
									USU_CADASTR
									) VALUES(
									'$nom_chamado',
									'$cod_empresa',
									 $dat_cadastr,
									'$dat_chamado',
									'$dat_entrega',
									'$cod_externo',
									 $cod_prioridade,
									 $cod_status,
									'$cod_tpsolicitacao',
									'$des_sac',
									'$cod_usucada',
									 0,
									'$cod_usuarios_env',
									'$cod_clientes_env',
									'$cod_univend_ate',
									'$cod_usucada'
									)";

				// fnEscreve($sql);
				//fnTestesql($connAdmSACV, $sql);
				mysqli_query(connTemp($cod_empresa, ''), $sql);

				//busca id do chamado
				$sql = "SELECT MAX(COD_ATENDIMENTO) as COD_ATENDIMENTO FROM ATENDIMENTO_CHAMADOS where COD_EMPRESA = $cod_empresa AND USU_CADASTR = $cod_usucada";
				$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
				$qrBuscaId = mysqli_fetch_assoc($arrayQuery);
				$cod_atendimento = $qrBuscaId['COD_ATENDIMENTO'];
				// fnEscreve($primeiroUp);

				if ($primeiroUp == 'N') {

					$sql = "UPDATE ATENDIMENTO_ANEXO SET 
									   COD_ATENDIMENTO = $cod_atendimento
									   WHERE 
									   COD_REFDOWN = $cod_refdown
									   ";

					mysqli_query(connTemp($cod_empresa, ''), $sql);
					//fnEscreve($sql);
				}

				if($count_filtros != ""){

					$sqlFiltro = "";
					$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

					for ($i=0; $i < $count_filtros; $i++) {

						$cod_filtro = fnLimpacampoZero($_REQUEST["COD_FILTRO_$i"]);
						$cod_tpfiltro = fnLimpacampoZero($_REQUEST["COD_TPFILTRO_$i"]);

						if($cod_filtro != 0){
							$sqlFiltro .= "INSERT INTO ATENDIMENTO_FILTROS(
								COD_EMPRESA,
								COD_TPFILTRO,
								COD_FILTRO,
								COD_ATENDIMENTO,
								COD_USUCADA
								)VALUES(
								$cod_empresa,
								$cod_tpfiltro,
								$cod_filtro,
								$cod_atendimento,
								$cod_usucada
							);";
						}

					}

                       fnEscreve($sqlFiltro);
					if($sql != ""){
								//fnTestesql(connTemp($cod_empresa,''),$sql);
						mysqli_multi_query(connTemp($cod_empresa, ''),$sqlFiltro);

					}							

				}

			} elseif ($opcao == 'EXC') {
				$sql = "DELETE FROM ATENDIMENTO_CHAMADOS WHERE COD_ATENDIMENTO = $cod_atendimento";
				mysqli_query(connTemp($cod_empresa, ''), $sql);
			} else {
				$sql = "UPDATE ATENDIMENTO_CHAMADOS SET
				 				NOM_CHAMADO='$nom_chamado',
								DAT_CADASTR=$dat_cadastr,
								DAT_CHAMADO='$dat_chamado',
								COD_EXTERNO='$cod_externo',
								COD_TPSOLICITACAO='$cod_tpsolicitacao',
								COD_PRIORIDADE='$cod_prioridade',
								COD_STATUS='$cod_status',
								DES_SAC='$des_sac',
								SAC_ANEXO='$sac_anexo',
								COD_USUARIO='$cod_usuario',
								COD_UNIVEND_ATE='$cod_univend_ate',
								COD_USUARIOS_ENV='$cod_usuarios_env'
								WHERE COD_ATENDIMENTO = $cod_atendimento";

				mysqli_query(connTemp($cod_empresa, ''), $sql);
				
				if($count_filtros != ""){

					$sql = "DELETE FROM ATENDIMENTO_FILTROS WHERE COD_ATENDIMENTO = $cod_atendimento;";
					$cod_usucada = $_SESSION["SYS_COD_USUARIO"];

					for ($i=0; $i < $count_filtros; $i++) {

						$cod_filtro = fnLimpacampoZero($_REQUEST["COD_FILTRO_$i"]);
						$cod_tpfiltro = fnLimpacampoZero($_REQUEST["COD_TPFILTRO_$i"]);

						if($cod_filtro != 0){
							$sql .= "INSERT INTO ATENDIMENTO_FILTROS(
								COD_EMPRESA,
								COD_TPFILTRO,
								COD_FILTRO,
								COD_ATENDIMENTO,
								COD_USUCADA
								)VALUES(
								$cod_empresa,
								$cod_tpfiltro,
								$cod_filtro,
								$cod_atendimento,
								$cod_usucada
							);";
						}

					}

                //fnEscreve($sql);
					if($sql != ""){
						mysqli_multi_query(conntemp($cod_empresa,''),$sql);
					}							

				}

			}

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


//busca dados da url - empresa
if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode($_GET['id']);
	$cod_atendimento = fnLimpaCampoZero(fnDecode($_GET['idC']));
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
}

if ($cod_atendimento != 0) {
	$sqlSac = "SELECT * FROM ATENDIMENTO_CHAMADOS WHERE COD_ATENDIMENTO = '" . $cod_atendimento . "' ";
	$sqlSac = mysqli_query(connTemp($cod_empresa, ''), $sqlSac);
	$qrSac = mysqli_fetch_assoc($sqlSac);

	if (isset($qrSac)) {

		$cod_atendimento = $qrSac['COD_ATENDIMENTO'];
		$cod_univend_ate = $qrSac['COD_UNIVEND_ATE'];
		$nom_chamado = $qrSac['NOM_CHAMADO'];
		$dat_cadastr = fnDataFull($qrSac['DAT_CADASTR']);
		$dat_chamado = fnDateRetorno($qrSac['DAT_CHAMADO']);
		$dat_entrega = fnDateRetorno($qrSac['DAT_ENTREGA']);
		$cod_externo = $qrSac['COD_EXTERNO'];
		$cod_status = $qrSac['COD_STATUS'];
		$cod_tpsolicitacao = $qrSac['COD_TPSOLICITACAO'];
		$url = $qrSac['URL'];
		$cod_clientes_env = $qrSac['COD_CLIENTES_ENV'];
		$cod_usuarios_env = $qrSac['COD_USUARIOS_ENV'];
		$des_sac = $qrSac['DES_SAC'];
		$sac_anexo = $qrSac['SAC_ANEXO'];
	}
} else {
	$cod_externo = "";
	$dat_cadastr = (new \DateTime())->format('d/m/Y H:i:s');
	$dat_chamado = (new \DateTime())->format('d/m/Y');
	$dat_entrega = (new \DateTime())->format('d/m/Y');
	$nom_chamado = "";
	$cod_tpsolicitacao = "0";
	$cod_status = "0";
	$cod_univend_ate = "0";
	$url = "";
	$cod_clientes_env = "0";
	$cod_usuarios_env = "0";
	$des_sac = "";
	$sac_anexo = "Sem Anexo";
}

// fnEscreve($cod_status);	
//fnEscreve($nom_empresa);	
//fnMostraForm();

$sql = "SELECT NOM_USUARIO, DES_EMAILUS, NUM_CELULAR, NUM_TELEFON FROM USUARIOS WHERE COD_USUARIO = $cod_usucada";
$qrUsu = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $sql));
// fnEscreve($cod_usuario);

if ($des_email == "") {
	$des_email = $qrUsu['DES_EMAILUS'];
}

if ($num_telefone == "") {

	if ($qrUsu['NUM_CELULAR'] != "") {
		$num_telefone = $qrUsu['NUM_CELULAR'];
	} else {
		$num_telefone = $qrUsu['NUM_TELEFON'];
	}
}


$sql = "SELECT COD_REFDOWN FROM ATENDIMENTO_ANEXO WHERE COD_ATENDIMENTO = $cod_atendimento";
$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
$qrCont = mysqli_fetch_assoc($arrayQuery);

if (!isset($qrCont) || $cod_atendimento == 0) {

	$sql = "UPDATE CONTADOR SET NUM_CONTADOR = (NUM_CONTADOR+1) WHERE COD_CONTADOR = 2";
	mysqli_query(connTemp($cod_empresa, ''), $sql);

	$sql = "SELECT NUM_CONTADOR FROM CONTADOR WHERE COD_CONTADOR = 2";
	$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
	$qrCont = mysqli_fetch_assoc($arrayQuery);

	$conta = $qrCont['NUM_CONTADOR'];
	$primeiroUp = "S";
} else {
	$conta = $qrCont['COD_REFDOWN'];
	$primeiroUp = "N";
}

if (isset($_GET['idU']) && (!isset($cod_usuarios_env) || $cod_usuarios_env == "" || $cod_usuarios_env == 0)) {
	$cod_clientes_env = fnDecode($_GET['idU']);
}

include "labelLibrary.php";
// fnEscreve($conta);

?>

<style>
	.leitura2 {
		border: none transparent !important;
		outline: none !important;
		background: #fff !important;
		font-size: 18px;
		padding: 0;
	}

	.collapse-chevron .fa {
		transition: .3s transform ease-in-out;
	}

	.collapse-chevron .collapsed .fa {
		transform: rotate(-90deg);
	}

	.collapse-plus .fas {
		transition: .2s transform ease-in-out;
	}

	.collapse-plus .collapsed .fas {
		transform: rotate(45deg);
	}

	.area {
		width: 100%;
		padding: 7px;
	}

	#dropZone {
		display: block;
		border: 2px dashed #bbb;
		-webkit-border-radius: 5px;
		border-radius: 5px;
		margin-left: -7px;
	}

	#dropZone p {
		font-size: 10pt;
		letter-spacing: -0.3pt;
		margin-bottom: 0px;
	}

	#dropzone .fa {
		font-size: 15pt;
	}
</style>

<div class="push30"></div>

<div class="row">

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<?php if ($popUp != "true") {  ?>
			<div class="portlet portlet-bordered">
			<?php } else { ?>
				<div class="portlet" style="padding: 0 20px 20px 20px;">
				<?php } ?>

				<?php if ($popUp != "true") {  ?>
					<div class="portlet-title">
						<div class="caption">
							<i class="glyphicon glyphicon-calendar"></i>
							<span class="text-primary"><?php echo $NomePg; ?></span>
						</div>
						<?php include "atalhosPortlet.php"; ?>
					</div>
				<?php } ?>
				<div class="portlet-body">

					<?php if ($msgRetorno <> '') { ?>
						<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<?php echo $msgRetorno; ?>
						</div>
					<?php } ?>

					<?php
					if ($popUp != "true") {
						$abaInfoAtendimento = 1436;
						include "abasInfoAtendimento.php";
					}
					?>

					<div class="push20"></div>

					<div class="login-form">

						<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

							<fieldset>
								<legend>Dados do Chamado</legend>

								<div class="row">

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Solicitante (Usuário)</label>
											<input type="text" class="form-control input-sm leitura2" readonly="readonly" name="NOM_USUARIO" id="NOM_USUARIO" value="<?= $qrUsu['NOM_USUARIO'] ?>">
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Data de Cadastro</label>
											<input type="text" class="form-control input-sm leitura2" readonly="readonly" name="DAT_CADASTR" id="DAT_CADASTR" value="<?php echo $dat_cadastr; ?>" required>
										</div>
									</div>

								</div>

								<div class="push10"></div>

								<div class="row">

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Assunto</label>
											<input type="text" class="form-control input-sm" name="NOM_CHAMADO" id="NOM_CHAMADO" maxlength="100" value="<?php echo $nom_chamado; ?>" required>
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label required">Tipo de Solicitação</label>
											<select class="chosen-select-deselect requiredChk" data-placeholder="Selecione o tipo" name="COD_TPSOLICITACAO" id="COD_TPSOLICITACAO" required>
												<?php

												$sql = "SELECT * FROM ATENDIMENTO_TPSOLICITACAO WHERE COD_EMPRESA=$cod_empresa";
												$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), trim($sql));

												while ($qrSolicitacao = mysqli_fetch_assoc($arrayQuery)) {
												?>
													<option value="<?php echo $qrSolicitacao['COD_TPSOLICITACAO']; ?>"><?php echo $qrSolicitacao['DES_TPSOLICITACAO']; ?></option>
												<?php } ?>
											</select>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label">Prioridade</label>
											<select class="chosen-select-deselect requiredChk" data-placeholder="Selecione a prioridade" name="COD_PRIORIDADE" id="COD_PRIORIDADE">
												<?php

												$sql = "SELECT * FROM ATENDIMENTO_PRIORIDADE";
												$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), trim($sql));

												while ($qrPrioridade = mysqli_fetch_assoc($arrayQuery)) {
												?>
													<option value="<?php echo $qrPrioridade['COD_PRIORIDADE']; ?>"><?php echo $qrPrioridade['DES_PRIORIDADE']; ?></option>
												<?php } ?>
											</select>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label required">Status</label>
											<select class="chosen-select-deselect requiredChk" data-placeholder="Selecione o status" name="COD_STATUS" id="COD_STATUS" required>
												<option value=""></option>
												<?php

												$sql = "SELECT * FROM ATENDIMENTO_STATUS WHERE COD_EMPRESA=$cod_empresa";
												$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), trim($sql));

												while ($qrStatus = mysqli_fetch_assoc($arrayQuery)) {
												?>
													<option value="<?php echo $qrStatus['COD_STATUS']; ?>"><?php echo $qrStatus['DES_STATUS']; ?></option>
												<?php } ?>
											</select>
											<div class="help-block with-errors"></div>
										</div>
									</div>

								</div>

								<div class="push10"></div>

								<div class="row">

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label required">Data da Solicitação</label>

											<div class="input-group date datePicker" id="DAT_CHAMADO_GRP">
												<input type='text' class="form-control input-sm data" name="DAT_CHAMADO" id="DAT_CHAMADO" value="<?php echo $dat_chamado; ?>" required />
												<span class="input-group-addon">
													<span class="glyphicon glyphicon-calendar"></span>
												</span>
											</div>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Previsão de Término</label>
											<div class="input-group date datePicker" id="DAT_ENTREGA_GRP">
												<input type='text' class="form-control input-sm data" name="DAT_ENTREGA" id="DAT_ENTREGA" value="<?php echo $dat_entrega ?>" />
												<span class="input-group-addon">
													<span class="glyphicon glyphicon-calendar"></span>
												</span>
											</div>
											<div class="help-block with-errors"></div>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="inputName" class="control-label">Código Externo</label>
											<input type="text" class="form-control input-sm" name="COD_EXTERNO" id="COD_EXTERNO" maxlength="45" value="<?php echo $cod_externo; ?>">
										</div>
									</div>

									<div class="col-md-3">
										<label for="inputName" class="control-label required"><?=$envolvidos?> Envolvidos</label>
										<div class="input-group">
											<span class="input-group-btn">
												<a type="button" name="btnBusca" id="btnBusca" style="height:35px;" class="btn btn-primary btn-sm addBox" data-url="action.php?mod=<?php echo fnEncode(1071) ?>&id=<?php echo fnEncode($cod_empresa) ?>&op=AGE&pop=true" data-title="Busca <?=$envolvidos?>"><i class="fa fa-search" aria-hidden="true" style="padding-top: 3px;"></i></a>
											</span>
											<select data-placeholder="Nenhum <?=$abaNome?> selecionado" name="COD_CLIENTES_ENV[]" id="COD_CLIENTES_ENV" multiple="multiple" class="chosen-select-deselect" style="width:100%;" tabindex="1">
												<?php

												if ($cod_clientes_env != "") {
													$sql = "SELECT COD_CLIENTE, NOM_CLIENTE FROM CLIENTES WHERE COD_EMPRESA = $cod_empresa AND COD_CLIENTE IN($cod_clientes_env)";
													$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

													while ($qrLista = mysqli_fetch_assoc($arrayQuery)) {
														echo "
																<option value='" . $qrLista['COD_CLIENTE'] . "'>" . $qrLista['NOM_CLIENTE'] . "</option> 
															";
													}
												}

												?>
											</select>
											<!-- <?php if ($cod_clientes_env != "") {
														fnEscreve($sql);
													} ?> -->
										</div>
										<div class="help-block with-errors"></div>
									</div>

									<!-- <div class="col-md-3">
														<div class="form-group">
															<label for="inputName" class="control-label required">Email</label>
																<input type="text" class="form-control input-sm" name="DES_EMAIL" id="DES_EMAIL" maxlength="70" value="<?php echo $des_email; ?>" required>
															<div class="help-block with-errors"></div>
														</div>
													</div>

													<div class="col-md-2">
														<div class="form-group">
															<label for="inputName" class="control-label required">Telefone</label>
																<input type="text" class="form-control input-sm phone" name="NUM_TELEFONE" id="NUM_TELEFONE" maxlength="15" value="<?php echo $num_telefone; ?>" required>
															<div class="help-block with-errors"></div>
														</div>
													</div> -->

									<div class="col-md-3">
										<div class="form-group">
											<label for="inputName" class="control-label required">Funcionários Envolvidos</label>

											<select data-placeholder="Selecione um Funcionário" name="COD_USUARIOS_ENV[]" id="COD_USUARIOS_ENV" multiple="multiple" class="chosen-select-deselect requiredChk" style="width:100%;" tabindex="1">
												<?php

												$sql = "select COD_USUARIO, NOM_USUARIO from usuarios 
																		where usuarios.COD_EMPRESA = $cod_empresa
																		and usuarios.DAT_EXCLUSA is null order by  usuarios.NOM_USUARIO ";
												$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

												while ($qrLista = mysqli_fetch_assoc($arrayQuery)) {
													echo "
															<option value='" . $qrLista['COD_USUARIO'] . "'>" . $qrLista['NOM_USUARIO'] . "</option> 
														";
												}
												?>
											</select>
											<div class="help-block with-errors"></div>
										</div>
									</div>

								</div>

								<div class="push10"></div>

								<div class="row">

								<?php if($cod_empresa == 311){ ?>
										
									<div class="col-xs-3">
										<div class="form-group">
											<label for="inputName" class="control-label required">Secretaria</label>
											<div id="relatorioUnivend">
												<select data-placeholder="Selecione a Secretaria" name="COD_UNIVEND_ATE" id="COD_UNIVEND_ATE" class="chosen-select-deselect" required>
													<option value=""></option>
													<?php
													$sql = "select COD_UNIVEND, NOM_FANTASI from unidadevenda where COD_EMPRESA = '" . $cod_empresa . "' AND LOG_ESTATUS = 'S' order by NOM_UNIVEND ";
													$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

													while ($qrListaUnidade = mysqli_fetch_assoc($arrayQuery)) {
														echo "
																<option value='" . $qrListaUnidade['COD_UNIVEND'] . "'>" . $qrListaUnidade['NOM_FANTASI'] . "</option> 
															";
													}
													?>
													<option value="add"><b>+&nbsp;ADICIONAR NOVO</b></option>
												</select>
												<script>
													$("#formulario #COD_UNIVEND_ATE").val("<?php echo $cod_univend; ?>").trigger("chosen:updated");
													$("#COD_UNIVEND_ATE").change(function(){
														let valor = $("#COD_UNIVEND_ATE").val()
														if(valor == "add"){
														$("#formulario #COD_UNIVEND_ATE").val("").trigger("chosen:updated");
														$("#bnt_univend").click();
														
													}	
													})
																									
												</script>
												<a type="hidden" name="bnt_univend" id="bnt_univend" class="addBox" data-url="action.php?mod=<?php echo fnEncode(1816) ?>&id=<?php echo fnEncode($cod_empresa) ?>&pop=true" data-title="Cadastrar Secretaria"></a>
												<div class="help-block with-errors"></div>
											</div>
										</div>
									</div>

								<?php } ?>

								<?php

								$sql = "SELECT COD_TPFILTRO, DES_TPFILTRO FROM TIPO_FILTRO_ATENDIMENTO
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
														$sqlFiltro = "SELECT COD_FILTRO, DES_FILTRO FROM FILTROS_ATENDIMENTO
														WHERE COD_TPFILTRO = ".$qrTipo['COD_TPFILTRO'];

														$arrayFiltros = mysqli_query(connTemp($cod_empresa,''),trim($sqlFiltro));
														while($qrFiltros = mysqli_fetch_assoc($arrayFiltros)){
															?>

															<option value="<?=$qrFiltros['COD_FILTRO']?>"><?=$qrFiltros['DES_FILTRO']?></option>

															<?php 
														}

														$sqlChosen = "SELECT COD_FILTRO FROM ATENDIMENTO_FILTROS
																		WHERE COD_ATENDIMENTO = $cod_atendimento AND COD_TPFILTRO =".$qrTipo['COD_TPFILTRO'];

														fnConsole($sqlChosen);

														$arrayChosen = mysqli_query(connTemp($cod_empresa,''),$sqlChosen);
														if(mysqli_num_rows($arrayChosen) > 0){
															$qrChosen = mysqli_fetch_assoc($arrayChosen);
															?>
															<script>
																$('#COD_FILTRO_<?=$qrTipo[COD_TPFILTRO]?>').val(<?=$qrChosen['COD_FILTRO']?>).trigger('chosen:updated');
															</script>
															<?php
														}
														
														?>						
														<option value="add">&nbsp;ADICIONAR NOVO</option>
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
										<a type="hidden" name="btnCad_<?=$countFiltros?>" id="btnCad_<?=$countFiltros?>" class="addBox" data-url="action.php?mod=<?php echo fnEncode(1869)?>&id=<?php echo fnEncode($cod_empresa)?>&idF=<?=fnEncode($qrTipo[COD_TPFILTRO])?>&idS=<?=fnEncode($countFiltros)?>&pop=true" data-title="Cadastrar Filtro - <?=$qrTipo[DES_TPFILTRO]?>"></a>

										<?php 
										$countFiltros++;
									}

								}
								?>

								</div>

								<div class="push10"></div>

								<div class="row">
									<div class="col-lg-12">
										<div class="form-group">
											<label for="inputName" class="control-label required">Comentário: </label>
											<textarea class="form-control input-sm" rows="6" name="DES_SAC" id="DES_SAC" required><?php echo $des_sac; ?></textarea>
											<div class="help-block with-errors"></div>
										</div>
									</div>
								</div>

							</fieldset>

							<div class="push10"></div>

							<div class="row">

								<div class="col-md-3">
									<div class="collapse-plus">
										<a data-toggle="collapse" class="collapsed btn btn-sm btn-success" href="#collapseFilter2" style="width: 90%;">
											<span class="fas fa-times" aria-hidden="true"></span>&nbsp;
											Criar Novo Anexo
										</a>
									</div>
								</div>

							</div>

							<div class="row">

								<div class="col-md-4">
									<div class="collapse area" id="collapseFilter2">
										<div id="dropZone">

											<div class="row">

												<div class="push15"></div>

												<div class="col-sm-1"></div>

												<div class="col-sm-2">
													<a type="button" name="btnBusca" id="btnBusca" class="btn btn-primary upload" idinput="SAC_ANEXO" extensao="all"><i class="fal fa-paperclip" aria-hidden="true"></i></a>
												</div>

												<div class="col-sm-8 text-center">
													<div class="push5"></div>
													<p>Upload de Arquivos</p>
													<input type="text" name="SAC_ANEXO" id="SAC_ANEXO" maxlength="100" hidden>
													<span class="help-block">(Tamanho máximo de 20MB por anexo)</span>
													<div class="push15"></div>
												</div>

												<div class="col-sm-1"></div>

											</div>


										</div>
									</div>
								</div>

							</div>

							<div class="row">

								<div class="col-md-4">
									<div class="collapse in" id="collapseFilter">
										<table class="table">
											<tbody id="relatorioConteudo">
												<?php
												$sql = "SELECT * FROM ATENDIMENTO_ANEXO WHERE COD_REFDOWN = $conta AND COD_EMPRESA = $cod_empresa ORDER BY DAT_CADASTR DESC
																	";

												//fnEscreve($sql);

												$arrayquery = mysqli_query(connTemp($cod_empresa, ''), $sql);
												while ($qrAnexo = mysqli_fetch_assoc($arrayquery)) {

												?>

													<tr>
														<td><a href="../media/clientes/<?php echo $cod_empresa; ?>/helpdesk/<?php echo $qrAnexo['NOM_ARQUIVO']; ?>"><span class="fa fa-download"></span></a></td>
														<td><?php echo $qrAnexo['NOM_ARQUIVO']; ?></td>
														<td><small><?php echo date("d/m/Y", strtotime($qrAnexo['DAT_CADASTR'])) ?></small>&nbsp;<small><?php echo date("H:i:s", strtotime($qrAnexo['DAT_CADASTR'])) ?></small></td>
													</tr>

												<?php
												}
												?>
											</tbody>
										</table>
									</div>
								</div>

							</div>

							<div class="push10"></div>
							<hr>
							<div class="form-group text-right col-lg-12">

								<a href="action.php?mod=<?php echo fnEncode(1435); ?>&id=<?php echo fnEncode($cod_empresa); ?>" name="ADD" id="ADD" class="btn btn-default pull-left" style="margin-right: 5px;"><i class="fa fa-arrow-left" aria-hidden="true"></i>&nbsp; Voltar à Lista</a>

								<button type="reset" class="btn btn-default"><i class="fal fa-eraser" aria-hidden="true"></i>&nbsp; Apagar</button>
								<?php if ($cod_atendimento == 0) { ?>
									<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fal fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar Atendimento</button>
								<?php } else { ?>
									<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fal fa-sync" aria-hidden="true"></i>&nbsp; Alterar</button>
								<?php } ?>

							</div>

							<input type="hidden" name="COD_ATENDIMENTO" id="COD_ATENDIMENTO" value="<?php echo $cod_atendimento; ?>">
							<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
							<input type="hidden" name="COUNT_FILTROS" id="COUNT_FILTROS" value="<?=$countFiltros?>">
							<input type="hidden" name="REFRESH_CLIENTE" id="REFRESH_CLIENTE" value="N">
							<input type="hidden" name="LOG_UNIVEND" id="LOG_UNIVEND" value="N">
							<input type="hidden" name="COD_CLIENTE_ENV" id="COD_CLIENTE_ENV" value="">
							<input type="hidden" name="NOM_CLIENTE_ENV" id="NOM_CLIENTE_ENV" value="">
							<input type="hidden" name="opcao" id="opcao" value="">
							<input type="hidden" name="COD_REFDOWN" id="COD_REFDOWN" value="<?php echo $conta; ?>">
							<input type="hidden" name="PRIMEIRO_UP" id="PRIMEIRO_UP" value="<?php echo $primeiroUp; ?>">
							<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
							<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

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

	<!-- modal -->
	<div class="modal fade" id="popModal" tabindex='-1'>
		<div class="modal-dialog" style="">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title"></h4>
				</div>
				<div class="modal-body">
					<iframe frameborder="0" style="width: 100%; height: 80%"></iframe>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

	<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
	<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
	<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />

	<script>
		function retornaForm(index) {
			$("#formulario #COD_TPSOLICITACAO").val(<?php echo $cod_tpsolicitacao; ?>).trigger("chosen:updated");
			$("#formulario #COD_UNIVEND_ATE").val(<?php echo $cod_univend_ate; ?>).trigger("chosen:updated");
			$("#formulario #COD_STATUS").val(<?php echo $cod_status; ?>).trigger("chosen:updated");

			var clientes_env = '<?php echo $cod_clientes_env; ?>';
			if (clientes_env != 0 && clientes_env != "") {
				//retorno combo multiplo - USUARIOS_ENV
				$("#formulario #COD_CLIENTES_ENV").val('').trigger("chosen:updated");

				var sistemasUni = '<?php echo $cod_clientes_env; ?>';
				var sistemasUniArr = sistemasUni.split(',');

				//opções multiplas
				for (var i = 0; i < sistemasUniArr.length; i++) {
					$("#formulario #COD_CLIENTES_ENV option[value=" + sistemasUniArr[i] + "]").prop("selected", "true");
				}
				$("#formulario #COD_CLIENTES_ENV").trigger("chosen:updated");

			}


			//retorno combo multiplo - USUARIOS_ENV
			$("#formulario #COD_USUARIOS_ENV").val('').trigger("chosen:updated");

			var sistemasUni = '<?php echo $cod_usuarios_env; ?>';
			var sistemasUniArr = sistemasUni.split(',');
			//opções multiplas
			for (var i = 0; i < sistemasUniArr.length; i++) {
				$("#formulario #COD_USUARIOS_ENV option[value=" + sistemasUniArr[i] + "]").prop("selected", "true");
			}
			$("#formulario #COD_USUARIOS_ENV").trigger("chosen:updated");

			// $('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');
		}


		$(document).ready(function() {

			retornaForm(0);

			var userDate = $('#DAT_CHAMADO').val();
			var dat_inicial = moment(userDate, "DD/MM/YYYY").format("YYYY-MM-DD");

			$('#DAT_CHAMADO_GRP').datetimepicker({
				format: 'DD/MM/YYYY',
				maxDate: 'now'
			}).on('changeDate', function(e) {
				$(this).datetimepicker('hide');
			});

			$('#DAT_ENTREGA_GRP').datetimepicker({
				format: 'DD/MM/YYYY',
				minDate: dat_inicial
			}).on('changeDate', function(e) {
				$(this).datetimepicker('hide');
			});

			$('#DAT_CHAMADO_GRP').on('dp.change', function() {
				userDate = $('#DAT_CHAMADO').val();
				dat_inicial = moment(userDate, "DD/MM/YYYY").format("YYYY-MM-DD");
				$('#DAT_ENTREGA_GRP').datetimepicker('destroy');
				$('#DAT_ENTREGA_GRP').datetimepicker({
					format: 'DD/MM/YYYY',
					minDate: dat_inicial
				}).on('changeDate', function(e) {
					$(this).datetimepicker('hide');
				});

			});

			//chosen obrigatório
			$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
			$('#formulario').validator();

			$('.modal').on('hidden.bs.modal', function() {

				if ($('#REFRESH_CLIENTE').val() == "S") {

					$("#COD_CLIENTES_ENV").append('<option value="' + $("#COD_CLIENTE_ENV").val() + '">' + $("#NOM_CLIENTE_ENV").val() + '</option>').trigger("chosen:updated");

					var sistemasUniArr = $("#COD_CLIENTES_ENV").val();

					//alert(sistemasUniArr);

					if (sistemasUniArr) {

						//opções multiplas
						for (var i = 0; i < sistemasUniArr.length; i++) {
							$("#formulario #COD_CLIENTES_ENV option[value=" + sistemasUniArr[i] + "]").prop("selected", "true");
						}

					}

					$("#formulario #COD_CLIENTES_ENV option[value=" + $("#COD_CLIENTE_ENV").val() + "]").prop("selected", "true").trigger("chosen:updated");

					$('#REFRESH_CLIENTE').val('N');

				}

			});

			//modal close
			$('.modal').on('hidden.bs.modal', function() {
				if($("#LOG_UNIVEND").val() == "S"){
					$.ajax({
						method: "POST",
						url:"ajxCarregaComboProfiss.php?id=<?=fnEncode($cod_empresa)?>",
						data:{
							COD_EMPRESA: <?= $cod_empresa ?>
						},
						beforeSend: function() {
							$('#relatorioUnivend').html('<div class="loading" style="width: 100%;"></div>');
						},
						success: function(data) {
							console.log(data);
							$('#relatorioUnivend' ).html(data);
							$('#LOG_UNIVEND').val("N");
						}
					})
				}
			});

		});

		$('.upload').on('click', function(e) {
			var idField = 'arqUpload_' + $(this).attr('idinput');
			var typeFile = $(this).attr('extensao');

			$.dialog({
				title: 'Arquivo',
				content: '' +
					'<form method = "POST" enctype = "multipart/form-data">' +
					'<input id="' + idField + '" type="file" name="image" style="margin-bottom: 20px;" />' +
					'<div class="progress" style="display: none">' +
					'<div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width:0%;">' +
					'   <span style="position: absolute; display: block; width: 100%; color:#2c3e50;">12</span></div>' +
					'</div>' +
					'<a type="button" id="btnUploadFile" class="btn btn-primary btn-sm" style="font-weight: bold" onClick="uploadFile(\'' + idField + '\', \'' + typeFile + '\')">UPLOAD</a>' +
					'</form>'
			});
		});

		function uploadFile(idField, typeFile) {
			var formData = new FormData();
			var nomeArquivo = $('#' + idField)[0].files[0]['name'];

			formData.append('arquivo', $('#' + idField)[0].files[0]);
			formData.append('diretorio', '../media/clientes/');
			formData.append('diretorioAdicional', 'helpdesk');
			formData.append('id', <?php echo $cod_empresa ?>);
			formData.append('typeFile', typeFile);

			$('.progress').show();
			$.ajax({
				xhr: function() {
					var xhr = new window.XMLHttpRequest();
					$('#btnUploadFile').addClass('disabled');
					xhr.upload.addEventListener("progress", function(evt) {
						if (evt.lengthComputable) {
							var percentComplete = evt.loaded / evt.total;
							percentComplete = parseInt(percentComplete * 100);
							if (percentComplete !== 100) {
								$('.progress-bar').css('width', percentComplete + "%");
								$('.progress-bar > span').html(percentComplete + "%");
							}
						}
					}, false);
					return xhr;
				},
				url: '../uploads/uploaddocSac.php',
				type: 'POST',
				data: formData,
				processData: false, // tell jQuery not to process the data
				contentType: false, // tell jQuery not to set contentType
				success: function(data) {
					$('.jconfirm-open').fadeOut(300, function() {
						$(this).remove();
					});
					if (!data.trim()) {
						$('#' + idField.replace("arqUpload_", "")).val(nomeArquivo);
						$.alert({
							title: "Mensagem",
							content: "Upload feito com sucesso",
							type: 'green'
						});

						//ajax da gravação do anexo
						$.ajax({
							type: "POST",
							url: "ajxAtendimentoAnexo.php",
							data: $('#formulario').serialize(),
							success: function(data) {
								//console.log(data);	
								$('#relatorioConteudo').html(data);
								$('#PRIMEIRO_UP').val("N");
							},
							error: function() {
								alert("Algo saiu errado no upload do arquivo. Tente novamente.");
							}
						});

					} else {
						$.alert({
							title: "Erro ao efetuar o upload",
							content: data,
							type: 'red'
						});
					}
				}
			});
		}
	</script>

	<?php
	mysqli_close($connAdmSACV);
	?>