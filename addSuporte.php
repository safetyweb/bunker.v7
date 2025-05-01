<?php

//echo "<h5>_".$opcao."</h5>";
//fnDebug("true");

$hashLocal = mt_rand();
$cod_chamado = fnLimpaCampoZero(fnDecode(@$_GET['idC']));
$cod_usucada = $_SESSION["SYS_COD_USUARIO"];
$andCodChamado = "";
$andFiltro = "";
$qrSac = array();
$cod_usures = '';


if ($cod_chamado != 0) {
	$andCodChamado = "&idC=" . fnEncode($cod_chamado);
}

if ($_SESSION['SYS_COD_EMPRESA'] != 2 && $_SESSION['SYS_COD_EMPRESA'] != 3) {
?>
	<script type="text/javascript">
		window.location.replace("https://adm.bunker.mk/action.do?mod=<?= fnEncode(1278) ?>&id=<?= fnEncode($_SESSION['SYS_COD_EMPRESA']) . $andCodChamado ?>");
	</script>
	<?php
	exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$cod_chamado = fnLimpacampoZero($_REQUEST['COD_CHAMADO']);
		$nom_chamado = fnLimpacampo($_REQUEST['NOM_CHAMADO']);
		$cod_empresa = fnLimpacampoZero($_REQUEST['COD_EMPRESA']);
		$dat_cadastr = fnDateSql(fnLimpacampo($_REQUEST['DAT_CADASTR']));
		$dat_chamado = fnLimpacampo($_REQUEST['DAT_CHAMADO']);
		$dat_entrega = fnLimpacampo($_REQUEST['DAT_ENTREGA']);
		if ($dat_entrega == "") {
			$dat_entrega = "31/12/1969";
		}
		$dat_proxint = fnLimpacampo($_REQUEST['DAT_PROXINT']);
		if ($dat_proxint == "") {
			$dat_proxint = "31/12/1969";
		}
		$cod_usuario = fnLimpaCampoZero($_REQUEST['COD_USUARIO']);
		$cod_usures = fnLimpaCampoZero($_REQUEST['COD_USURES']);
		$cod_sistemas = fnLimpacampo($_REQUEST['COD_SISTEMAS']);
		$cod_externo = fnLimpacampo($_REQUEST['COD_EXTERNO']);
		$link_externo = fnLimpacampo($_REQUEST['LINK_EXTERNO']);
		$url = fnLimpacampo($_REQUEST['URL']);
		$des_previsao = fnLimpacampo($_REQUEST['DES_PREVISAO']);
		$des_email = fnLimpacampo($_REQUEST['DES_EMAIL']);
		$num_telefone = fnLimpacampo($_REQUEST['NUM_TELEFONE']);
		$cod_integradora = fnLimpacampoZero($_REQUEST['COD_INTEGRADORA']);
		$cod_plataforma = fnLimpacampoZero($_REQUEST['COD_PLATAFORMA']);
		$cod_tpsolicitacao = fnLimpacampoZero($_REQUEST['COD_TPSOLICITACAO']);
		$cod_tpsolicita_old = fnLimpacampoZero($_REQUEST['COD_TPSOLICITA_OLD']);
		$cod_versaointegra = fnLimpacampoZero($_REQUEST['COD_VERSAOINTEGRA']);
		$cod_status = fnLimpacampoZero($_REQUEST['COD_STATUS']);
		$cod_prioridade = fnLimpacampoZero($_REQUEST['COD_PRIORIDADE']);
		$des_sac = addslashes(htmlentities($_REQUEST['DES_SAC']));
		$sac_anexo = fnLimpacampo($_REQUEST['SAC_ANEXO']);
		$cod_refdown = fnLimpacampo($_REQUEST['COD_REFDOWN']);
		$primeiroUp = fnLimpaCampo($_REQUEST['PRIMEIRO_UP']);
		if (empty($_REQUEST['LOG_ANALISE'])) {
			$log_analise = 'N';
		} else {
			$log_analise = $_REQUEST['LOG_ANALISE'];
		}
		$cod_usures_old = fnLimpacampo($_REQUEST['COD_USURES_OLD']);
		$cod_status_old = fnLimpacampo($_REQUEST['COD_STATUS_OLD']);
		$cod_prioridade_old = fnLimpacampo($_REQUEST['COD_PRIORIDADE_OLD']);
		$dat_entrega_old = fnLimpacampo($_REQUEST['DAT_ENTREGA_OLD']);
		$dat_proxint_old = fnLimpacampo($_REQUEST['DAT_PROXINT_OLD']);

		// fnEscreve($des_sac);


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

		if (isset($_POST['COD_UNIVEND'])) {
			$Arr_COD_UNIVEND = $_POST['COD_UNIVEND'];

			for ($i = 0; $i < count($Arr_COD_UNIVEND); $i++) {
				$cod_univend = $cod_univend . $Arr_COD_UNIVEND[$i] . ",";
			}

			$cod_univend = substr($cod_univend, 0, -1);
		} else {
			$cod_univend = "0";
		}


		//fnEscreve($cod_usuarios_env);

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];


		if ($opcao != '') {

			// if($dat_entrega = "31/12/1969"){
			// 	$dat_entrega = null;
			// }else{
			// 	$dat_entrega = fnDataSql($dat_entrega);
			// }

			// if($dat_proxint = "31/12/1969"){
			// 	$dat_proxint = null;
			// }else{
			// 	$dat_proxint = fnDataSql($dat_entrega);
			// }

			if ($opcao == 'CAD') {

				$msgChamado = "Chamado aberto em <i>" . date('d/m/Y H:i:s') . "</i>";

				$sql = "INSERT INTO SAC_CHAMADOS(
									NOM_CHAMADO,
									COD_EMPRESA,
									DAT_CADASTR,
									DAT_CHAMADO,
									DAT_PROXINT,
									DAT_ENTREGA,
									COD_EXTERNO,
									LINK_EXTERNO,
									URL,
									COD_USURES,
									DES_PREVISAO,
									DES_EMAIL,
									NUM_TELEFONE,
									COD_INTEGRADORA,
									COD_PLATAFORMA,
									COD_TPSOLICITACAO,
									COD_VERSAOINTEGRA,
									COD_STATUS,
									COD_PRIORIDADE,
									DES_SAC,
									SAC_ANEXO,
									COD_USUARIO,
									COD_SISTEMAS,
									COD_USUARIOS_ENV,
									COD_CONSULTORES,
									COD_UNIVEND,
									USU_CADASTR,
									LOG_ADM,
									LOG_ANALISE
									) VALUES(
									'$nom_chamado',
									'$cod_empresa',
									 $dat_cadastr,
									'" . fnDataSql($dat_chamado) . "',
									'" . fnDataSql($dat_proxint) . "',
									'" . fnDataSql($dat_entrega) . "',
									'$cod_externo',
									'$link_externo',
									'$url',
									'$cod_usures',
									'" . fnValorSql($des_previsao) . "',
									'$des_email',
									'$num_telefone',
									'$cod_integradora',
									'$cod_plataforma',
									'$cod_tpsolicitacao',
									'$cod_versaointegra',
									'$cod_status',
									'$cod_prioridade',
									'$des_sac',
									'$sac_anexo',
									'$cod_usuario',
									'$cod_sistemas',
									'$cod_usuarios_env',
									'$cod_consultores',
									'$cod_univend',
									'$cod_usucada',
									'S',
									'$log_analise'
									);

							INSERT INTO SAC_COMENTARIO(
										COD_CHAMADO,
										DES_COMENTARIO,
										TP_COMENTARIO,
										COD_EMPRESA,
										COD_USUARIO,
										DAT_CADASTRO,
										COD_COR,
										COD_STATUS
										) VALUES(
										(SELECT MAX(COD_CHAMADO) FROM SAC_CHAMADOS WHERE COD_EMPRESA = $cod_empresa AND USU_CADASTR = $cod_usucada),
										'$msgChamado',
										 1,
										'$cod_empresa',
										$cod_usucada,
										$dat_cadastr,
										'2',
										'$cod_status'
										 )";

				//fnEscreve($sql);

				//fnTestesql($connAdm->connAdm(), $sql);
				mysqli_multi_query($connAdmSAC->connAdm(), $sql);

				//fnMostraForm('#formulario');

				if ($primeiroUp == "N") {

					$sql = "SELECT COD_CHAMADO, COD_EMPRESA FROM SAC_CHAMADOS WHERE COD_CHAMADO = (SELECT MAX(COD_CHAMADO) FROM SAC_CHAMADOS)";
					$arrayQuery = mysqli_query($connAdmSAC->connAdm(), $sql);
					$sqlSac = mysqli_fetch_assoc($arrayQuery);
					$cod_chamado = $sqlSac['COD_CHAMADO'];
					$cod_empresa = $sqlSac['COD_EMPRESA'];

					$sql = "UPDATE SAC_ANEXO SET 
									   COD_CHAMADO = $cod_chamado,
									   COD_EMPRESA = $cod_empresa
									   WHERE 
									   COD_REFDOWN = $cod_refdown
									   ";

					mysqli_query($connAdmSAC->connAdm(), $sql);
				}
			} elseif ($opcao == 'EXC') {
				$sql = "DELETE FROM SAC_CHAMADOS WHERE COD_CHAMADO = $cod_chamado";
				mysqli_query($connAdmSAC->connAdm(), $sql);
	?>
				<script type="text/javascript">
					window.location.replace("http://adm.bunker.mk/action.do?mod=kiWbp%C2%A3ffARCI%C2%A2&x=<?= fnEncode(1) ?>");
				</script>
<?php

			} else {
				$sql = "UPDATE SAC_CHAMADOS SET
				 				NOM_CHAMADO='$nom_chamado',
								DAT_CADASTR=$dat_cadastr,
								DAT_CHAMADO='" . fnDataSql($dat_chamado) . "',
								DAT_PROXINT='" . fnDataSql($dat_proxint) . "',
								DAT_ENTREGA='" . fnDataSql($dat_entrega) . "',
								COD_EXTERNO='$cod_externo',
								LINK_EXTERNO='$link_externo',
								URL='$url',
								COD_USURES='$cod_usures',
								DES_PREVISAO='" . fnValorSql($des_previsao) . "',
								DES_EMAIL='$des_email',
								NUM_TELEFONE='$num_telefone',
								COD_INTEGRADORA='$cod_integradora',
								COD_PLATAFORMA='$cod_plataforma',
								COD_TPSOLICITACAO='$cod_tpsolicitacao',
								COD_TPSOLICITA_OLD='$cod_tpsolicita_old',
								COD_VERSAOINTEGRA='$cod_versaointegra',
								COD_STATUS='$cod_status',
								COD_PRIORIDADE='$cod_prioridade',
								DES_SAC='$des_sac',
								SAC_ANEXO='$sac_anexo',
								COD_USUARIO='$cod_usuario',
								COD_SISTEMAS='$cod_sistemas',
								COD_USUARIOS_ENV='$cod_usuarios_env',
								COD_CONSULTORES='$cod_consultores',
								COD_UNIVEND='$cod_univend'
								WHERE COD_CHAMADO = $cod_chamado";

				//mysqli_query($connAdmSAC->connAdm(),$sql);
				mysqli_query($connAdmSAC->connAdm(), $sql);

				if ($cod_usures_old != $cod_usures) {
					$msgUsuRes = "'\r\nAtribuiu <i>',(SELECT NOM_USUARIO FROM WEBTOOLS.USUARIOS WHERE COD_USUARIO = $cod_usures),'</i> como responsável<br/>',";
				} else {
					$msgUsuRes = "";
				}

				if ($cod_status_old != $cod_status) {
					$msgStatus = "'\r\nAlterou o status de: <i>',(SELECT DES_STATUS FROM SAC_STATUS WHERE COD_STATUS = $cod_status_old), 
							 			'</i> para: <i>',(SELECT DES_STATUS FROM SAC_STATUS WHERE COD_STATUS = $cod_status),'</i><br/>'";
				} else {
					$msgStatus = "";
				}

				if ($cod_prioridade_old != $cod_prioridade) {
					$msgPrioridade = "'\r\nAlterou a prioridade de: <i>',(SELECT DES_PRIORIDADE FROM SAC_PRIORIDADE WHERE COD_PRIORIDADE = $cod_prioridade_old), 
							 			'</i> para: <i>',(SELECT DES_PRIORIDADE FROM SAC_PRIORIDADE WHERE COD_PRIORIDADE = $cod_prioridade),'</i><br/>'";
				} else {
					$msgPrioridade = "";
				}

				if ($dat_entrega_old != $dat_entrega && $mod != 1289) {

					$upDatEnt = ", DAT_ENTREGA = '" . fnDataSql($dat_entrega) . "'";

					if ($dat_entrega == "1969-12-31") {

						$msgDatEnt = "'\r\nAlterou que a data de <i>entrega</i> está para <i>ser definida</i> <br/>',";
					} else {

						$msgDatEnt = "'\r\nAlterou a data de <i>entrega</i> para <i>" . $dat_entrega . "</i> <br/>',";
					}
				} else {

					$msgDatEnt = "";
				}

				if ($dat_proxint_old != $dat_proxint && $mod != 1289) {

					$upDatProxint = ", DAT_PROXINT = '" . fnDataSql($dat_proxint) . "'";

					if ($dat_proxint == "1969-12-31") {

						$msgDatProxint = "'\r\nAlterou que a data da <i>próxima interação</i> está para <i>ser definida</i> <br/>',";
					} else {

						$msgDatProxint = "'\r\nAlterou a data da <i>próxima interação</i> para <i>" . $dat_proxint . "</i> <br/>',";
					}
				} else {

					$msgDatProxint = "";
				}

				if ($cod_usures_old != $cod_usures || $cod_status_old != $cod_status || $dat_entrega_old != $dat_entrega || $cod_prioridade_old != $cod_prioridade || $dat_proxint_old != $dat_proxint) {
					$sqlFeed = "INSERT INTO SAC_COMENTARIO(
										COD_CHAMADO,
										DES_COMENTARIO,
										TP_COMENTARIO,
										COD_EMPRESA,
										COD_USUARIO,
										DAT_CADASTRO,
										COD_COR,
										COD_STATUS
										) VALUES(
										'$cod_chamado',
										CONCAT(
										$msgUsuRes $msgPrioridade $msgStatus $msgDatEnt $msgDatProxint
										' '),
										 1,
										'$cod_empresa',
										$cod_usucada,
										CONVERT_TZ(NOW(),'America/Sao_Paulo','America/Sao_Paulo'),
										'2',
										'$cod_status'
										 )";
					// fnEscreve($sqlFeed);
					mysqli_query($connAdmSAC->connAdm(), $sqlFeed);
				}
			}

			//mensagem de retorno
			switch ($opcao) {
				case 'CAD':
					$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
					$cod_chamado_sql = "(SELECT MAX(COD_CHAMADO) FROM SAC_CHAMADOS
											WHERE cod_empresa=$cod_empresa AND USU_CADASTR=$cod_usucada)";
					$tipo_email = "Criado";
					$novo_chamado = "Novo ";
					break;
				case 'ALT':
					$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";
					$cod_chamado_sql = $cod_chamado;
					$tipo_email = "Alterado";
					$novo_chamado = "Atualização - ";
					break;
				case 'EXC':
					$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";
					break;
					break;
			}

			/////////////////--Envio do Email--/////////////////
			/**/
			include 'envioEmailSac.php';		    /**/
			////////////////////////////////////////////////////

			$msgTipo = 'alert-success';
		}
	}
}
if (!isset($_GET['id'])) {
	$cod_empresa = 0;
} else {
	$cod_empresa = fnDecode($_GET['id']);
}


if ($cod_chamado != 0) {
	$sqlSac = "SELECT * FROM SAC_CHAMADOS WHERE COD_CHAMADO = '" . $cod_chamado . "' ";
	$sqlSac = mysqli_query($connAdmSAC->connAdm(), $sqlSac);
	$qrSac = mysqli_fetch_assoc($sqlSac);

	if (isset($qrSac)) {

		$cod_chamado = $qrSac['COD_CHAMADO'];
		$cod_empresa = $qrSac['COD_EMPRESA'];
		$nom_chamado = $qrSac['NOM_CHAMADO'];
		$dat_cadastr = fnDataFull($qrSac['DAT_CADASTR']);
		$dat_chamado = fnDataShort($qrSac['DAT_CHAMADO']);
		$dat_entrega = fnDataShort($qrSac['DAT_ENTREGA']);
		$dat_proxint = fnDataShort($qrSac['DAT_PROXINT']);
		$cod_usuario = $qrSac['COD_USUARIO'];
		$cod_usures = $qrSac['COD_USURES'];
		$cod_externo = $qrSac['COD_EXTERNO'];
		$link_externo = $qrSac['LINK_EXTERNO'];
		$cod_sistemas = $qrSac['COD_SISTEMAS'];
		$cod_integradora = $qrSac['COD_INTEGRADORA'];
		$cod_plataforma = $qrSac['COD_PLATAFORMA'];
		$cod_prioridade = $qrSac['COD_PRIORIDADE'];
		$cod_versaointegra = $qrSac['COD_VERSAOINTEGRA'];
		$cod_status = $qrSac['COD_STATUS'];
		$cod_tpsolicitacao = $qrSac['COD_TPSOLICITACAO'];
		$url = $qrSac['URL'];
		$des_previsao = $qrSac['DES_PREVISAO'];
		$des_email = $qrSac['DES_EMAIL'];
		$num_telefone = $qrSac['NUM_TELEFONE'];
		$cod_univend = $qrSac['COD_UNIVEND'];
		$cod_usuarios_env = $qrSac['COD_USUARIOS_ENV'];
		$cod_consultores = $qrSac['COD_CONSULTORES'];
		$des_sac = $qrSac['DES_SAC'];
		$sac_anexo = $qrSac['SAC_ANEXO'];
		$cod_usuario = $qrSac['USU_CADASTR'];
	}
} else {
	$cod_externo = "";
	$cod_empresa = 0;
	$dat_cadastr = (new \DateTime())->format('d/m/Y H:i:s');
	$dat_chamado = (new \DateTime())->format('d/m/Y');
	//$dat_entrega = (new \DateTime())->format('d/m/Y');
	$dat_proxint = "";
	$dat_entrega = "";
	$nom_chamado = "";
	$cod_tpsolicitacao = "5";
	$cod_usuario = $cod_usucada;
	$cod_plataforma = "0";
	$cod_versaointegra = "0";
	$cod_status = "12";
	$cod_integradora = "0";
	$cod_prioridade = "0";
	$cod_sistemas = "0";
	$url = "";
	$des_previsao = "";
	$num_telefone = "";
	$des_email = "";
	$cod_univend = "0";
	$cod_usuarios_env = "0";
	$cod_consultores = "0";
	$des_sac = "";
	$sac_anexo = "Sem Anexo";
}

$sql = "SELECT COD_REFDOWN FROM SAC_ANEXO WHERE COD_CHAMADO = $cod_chamado";
$arrayQuery = mysqli_query($connAdmSAC->connAdm(), $sql);
$qrCont = mysqli_fetch_assoc($arrayQuery);

if (!isset($qrCont) || $cod_chamado == 0) {

	$sql = "UPDATE CONTADOR SET NUM_CONTADOR = (NUM_CONTADOR+1) WHERE COD_CONTADOR = 2";
	mysqli_query($connAdmSAC->connAdm(), $sql);

	$sql = "SELECT NUM_CONTADOR FROM CONTADOR WHERE COD_CONTADOR = 2";
	$arrayQuery = mysqli_query($connAdmSAC->connAdm(), $sql);
	$qrCont = mysqli_fetch_assoc($arrayQuery);

	$conta = $qrCont['NUM_CONTADOR'];
	$primeiroUp = "S";
} else {
	$conta = $qrCont['COD_REFDOWN'];
	$primeiroUp = "N";
}

$sql = "SELECT NOM_USUARIO, DES_EMAILUS, NUM_CELULAR, NUM_TELEFON FROM USUARIOS WHERE COD_USUARIO = $cod_usuario";
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


?>

<div class="push30"></div>

<div class="row">

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="glyphicon glyphicon-calendar"></i>
					<span class="text-primary"><?php echo $NomePg; ?></span>
				</div>

				<?php
				$formBack = "1019";
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

				<?php $abaInfoSuporte = 1267;
				include "abasInfoSuporte.php";  ?>

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

					.jqte {
						border: #dce4ec 2px solid !important;
						border-radius: 3px !important;
						-webkit-border-radius: 3px !important;
						box-shadow: 0 0 2px #dce4ec !important;
						-webkit-box-shadow: 0 0 0px #dce4ec !important;
						-moz-box-shadow: 0 0 3px #dce4ec !important;
						transition: box-shadow 0.4s, border 0.4s;
						margin-top: 0px !important;
						margin-bottom: 0px !important;
					}

					.jqte_toolbar {
						background: #fff !important;
						border-bottom: none !important;
					}

					.jqte_focused {
						border: none !important;
						box-shadow: 0 0 3px #00BDFF;
						-webkit-box-shadow: 0 0 3px #00BDFF;
						-moz-box-shadow: 0 0 3px #00BDFF;
					}

					.jqte_titleText {
						border: none !important;
						border-radius: 3px;
						-webkit-border-radius: 3px;
						-moz-border-radius: 3px;
						word-wrap: break-word;
						-ms-word-wrap: break-word
					}

					.jqte_tool,
					.jqte_tool_icon,
					.jqte_tool_label {
						border: none !important;
					}

					.jqte_tool_icon:hover {
						border: none !important;
						box-shadow: 1px 5px #EEE;
					}
				</style>

				<div class="push30"></div>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Dados do Chamado</legend>

							<div class="row">

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Código</label>
										<input type="text" class="form-control input-sm leitura2" readonly="readonly" name="COD_CHAMADO" id="COD_CHAMADO" value="<?php echo $cod_chamado; ?>">
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Empresa</label>
										<?php if ($cod_chamado == 0) { ?>
											<select data-placeholder="Selecione uma empresa" name="COD_EMPRESA_COMBO" id="COD_EMPRESA_COMBO" class="chosen-select-deselect requiredChk" style="width:100%;" required>
												<option value=""></option>
												<?php
												//$sql = "SELECT COD_EMPRESA, NOM_FANTASI FROM EMPRESAS";
												if ($_SESSION["SYS_COD_MASTER"] == "2") {
													$sql = "SELECT empresas.COD_EMPRESA, empresas.NOM_FANTASI
																				FROM empresas  
																				WHERE empresas.COD_EMPRESA <> 1 
																				$andFiltro
																				ORDER by NOM_FANTASI
																		";
													//fnEscreve("1");
												} else {
													$sql = "SELECT empresas.COD_EMPRESA, empresas.NOM_FANTASI
																				FROM empresas  
																				WHERE COD_EMPRESA IN (" . $_SESSION["SYS_COD_MULTEMP"] . ")
																				$andFiltro
																				ORDER by NOM_FANTASI
																		";
													//fnEscreve("2");
												}
												$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

												while ($qrLista = mysqli_fetch_assoc($arrayQuery)) {
													echo "
																				  <option value='" . $qrLista['COD_EMPRESA'] . "'>" . $qrLista['NOM_FANTASI'] . "</option> 
																				";
												}
												?>
											</select>
										<?php } else {
											$sql = "SELECT COD_EMPRESA, NOM_FANTASI from EMPRESAS
																			WHERE COD_EMPRESA = $cod_empresa";
											$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
											$qrEmpresa = mysqli_fetch_assoc($arrayQuery);
										?>
											<input type="text" class="form-control input-sm leitura2" readonly="readonly" name="NOM_FANTASI" id="NOM_FANTASI" value="<?php echo $qrEmpresa['NOM_FANTASI']; ?>">
										<?php } ?>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label required">Título do Chamado</label>
										<input type="text" class="form-control input-sm" name="NOM_CHAMADO" id="NOM_CHAMADO" maxlength="50" value="<?php echo $nom_chamado; ?>" required>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Responsável</label>
										<select data-placeholder="Selecione um usuário" name="COD_USURES" id="COD_USURES" class="chosen-select-deselect requiredChk" style="width:100%;" required>
											<option value=""></option>
											<optgroup label="Usuários Marka">
												<?php

												$sql = "SELECT COD_USUARIO, NOM_USUARIO from usuarios 
																		where (usuarios.COD_EMPRESA = 2 OR usuarios.COD_EMPRESA = 3)
																		and usuarios.DAT_EXCLUSA is null 
																		AND COD_TPUSUARIO IN(9,6,1,3)
																		AND LOG_ESTATUS = 'S' order by  usuarios.NOM_USUARIO ";
												$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

												while ($qrLista = mysqli_fetch_assoc($arrayQuery)) {
													echo "
																			  <option value='" . $qrLista['COD_USUARIO'] . "'>" . $qrLista['NOM_USUARIO'] . "</option> 
																			";
												}
												?>
											</optgroup>
										</select>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Data da Ocorrência</label>
										<div class="input-group date datePicker" id="DAT_CHAMADO_GRP">
											<input type='text' class="form-control input-sm data" name="DAT_CHAMADO" id="DAT_CHAMADO" value="<?php echo $dat_chamado; ?>" required />
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
										</div>
										<div class="help-block with-errors"></div>
									</div>
								</div>

							</div>

							<div class="push10"></div>

							<div class="row">

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label required">Email</label>
										<input type="text" class="form-control input-sm" name="DES_EMAIL" id="DES_EMAIL" maxlength="70" value="<?php echo $des_email; ?>" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Telefone</label>
										<input type="text" class="form-control input-sm phone" name="NUM_TELEFONE" id="NUM_TELEFONE" minlength="15" maxlength="15" value="<?php echo $num_telefone; ?>" required>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Status</label>
										<select class="chosen-select-deselect requiredChk" data-placeholder="Selecione o status" name="COD_STATUS" id="COD_STATUS" required>
											<?php

											$sql = "SELECT * FROM SAC_STATUS";
											$arrayQuery = mysqli_query($connAdmSAC->connAdm(), $sql);

											while ($qrStatus = mysqli_fetch_assoc($arrayQuery)) {
											?>
												<option value="<?php echo $qrStatus['COD_STATUS']; ?>"><?php echo $qrStatus['DES_STATUS']; ?></option>
											<?php } ?>
										</select>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Prazo (interação)</label>
										<div class="input-group date datePicker" id="DAT_PROXINT_GRP">
											<input type='text' class="form-control input-sm data" name="DAT_PROXINT" id="DAT_PROXINT" value="<?php echo $dat_proxint; ?>" />
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
										</div>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Entrega</label>
										<div class="input-group date datePicker" id="DAT_ENTREGA_GRP">
											<input type='text' class="form-control input-sm data" name="DAT_ENTREGA" id="DAT_ENTREGA" value="<?php echo $dat_entrega; ?>" />
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
										</div>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-1">
									<div class="form-group">
										<label for="inputName" class="control-label">Esforço(Hr)</label>
										<input type="text" class="form-control input-sm" name="DES_PREVISAO" id="DES_PREVISAO" value="<?php echo $des_previsao; ?>">
										<div class="help-block with-errors"></div>
									</div>
								</div>

							</div>

						</fieldset>

						<div class="push10"></div>

						<fieldset>
							<legend>Classificação da Solicitação</legend>

							<div class="row">

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Solicitante (Usuário)</label>
										<input type="text" class="form-control input-sm leitura2" readonly="readonly" name="NOM_USUARIO" id="NOM_USUARIO" value="<?= $qrUsu['NOM_USUARIO'] ?>">
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Sistema</label>
										<div id="relatorioSis">
											<select data-placeholder="Selecione um sistema" name="COD_SISTEMAS" id="COD_SISTEMAS" class="chosen-select-deselect requiredChk" style="width:100%;" required>
												<option value="">&nbsp; </option>
											</select>
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

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Link Externo</label>
										<input type="text" class="form-control input-sm" name="LINK_EXTERNO" id="LINK_EXTERNO" maxlength="200" value="<?php echo @$link_externo; ?>">
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label for="inputName" class="control-label">Url</label>
										<input type="text" class="form-control input-sm" name="URL" id="URL" maxlength="200" value="<?php echo @$url; ?>">
									</div>
								</div>

							</div>

							<div class="push10"></div>

							<div class="row">

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Integradora</label>
										<div id="relatorioIntegra">
											<select data-placeholder="Selecione a integradora" name="COD_INTEGRADORA" id="COD_INTEGRADORA" class="chosen-select-deselect">
												<option value=""></option>
											</select>
										</div>
										<div class="help-block with-errors"></div>
									</div>

								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Plataforma</label>
										<div id="relatorioPlat">
											<select data-placeholder="Selecione a plataforma" name="COD_PLATAFORMA" id="COD_PLATAFORMA" class="chosen-select-deselect requiredChk" style="width:100%;" required>
												<option value="">&nbsp; </option>
											</select>
										</div>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Versão da Integração</label>
										<div id="relatorioVersao">
											<select data-placeholder="Selecione a versão" name="COD_VERSAOINTEGRA" id="COD_VERSAOINTEGRA" class="chosen-select-deselect requiredChk" style="width:100%;" required>
												<option value="">&nbsp; </option>
											</select>
										</div>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Tipo de Solicitação</label>
										<select class="chosen-select-deselect requiredChk" data-placeholder="Selecione o tipo" name="COD_TPSOLICITACAO" id="COD_TPSOLICITACAO" required>
											<option value=""></option>
											<?php

											$sql = "SELECT * FROM SAC_TPSOLICITACAO";
											$arrayQuery = mysqli_query($connAdmSAC->connAdm(), $sql);

											while ($qrSolicitacao = mysqli_fetch_assoc($arrayQuery)) {
											?>
												<option value="<?php echo $qrSolicitacao['COD_TPSOLICITACAO']; ?>"><?php echo $qrSolicitacao['DES_TPSOLICITACAO']; ?></option>
											<?php } ?>
										</select>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label required">Prioridade</label>
										<select class="chosen-select-deselect requiredChk" data-placeholder="Selecione a prioridade" name="COD_PRIORIDADE" id="COD_PRIORIDADE" required>
											<option></option>
											<?php

											$sql = "SELECT * FROM SAC_PRIORIDADE WHERE COD_PRIORIDADE <> 8";
											$arrayQuery = mysqli_query($connAdmSAC->connAdm(), $sql);

											while ($qrPrioridade = mysqli_fetch_assoc($arrayQuery)) {
											?>
												<option value="<?php echo $qrPrioridade['COD_PRIORIDADE']; ?>"><?php echo $qrPrioridade['DES_PRIORIDADE']; ?></option>
											<?php } ?>
										</select>
										<div class="help-block with-errors"></div>
									</div>
								</div>

							</div>

						</fieldset>

						<div class="push10"></div>

						<fieldset>
							<legend>Envolvidos</legend>

							<div class="row">

								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label">Unidades Envolvidas</label>
										<div id="relatorioUnivend">
											<select data-placeholder="Selecione uma unidade para acesso" name="COD_UNIVEND[]" id="COD_UNIVEND" multiple="multiple" class="chosen-select-deselect" style="width:100%;" tabindex="1">
												<option value="">&nbsp; </option>
											</select>
										</div>
										<div class="help-block with-errors"></div>
										<a class="btn btn-default btn-sm" id="iAll" style="padding: 0 2px ; font-size: 10px;"><i class="fa fa-check-square" aria-hidden="true"></i> selecionar todos</a>&nbsp;
										<a class="btn btn-default btn-sm" id="iNone" style="padding: 0 2px ; font-size: 10px;"><i class="fa fa-square-o" aria-hidden="true"></i>&nbsp; deselecionar todos</a>
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label">Usuários Envolvidos</label>
										<div id="relatorioUsuEnv">
											<select data-placeholder="Selecione um usuários" name="COD_USUARIOS_ENV[]" id="COD_USUARIOS_ENV" multiple="multiple" class="chosen-select-deselect requiredChk" style="width:100%;" tabindex="1">
												<option value="">&nbsp; </option>
											</select>
										</div>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label for="inputName" class="control-label">Consultores Envolvidos</label>
										<select data-placeholder="Selecione um consultor" name="COD_CONSULTORES[]" id="COD_CONSULTORES" multiple="multiple" class="chosen-select-deselect requiredChk" tabindex="1">
											<?php

											$sql = "select COD_USUARIO, NOM_USUARIO from usuarios 
																		where usuarios.COD_EMPRESA = 3
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
								<div class="col-lg-12">
									<div class="form-group">
										<label for="inputName" class="control-label required">Descrição:</label>
										<textarea class="editor form-control input-sm" rows="6" name="DES_SAC" id="DES_SAC" maxlength="4000"><?php echo $des_sac; ?></textarea>
										<div class="help-block with-errors"></div>
									</div>
								</div>
							</div>

						</fieldset>

						<div class="push10"></div>

						<div class="row">

							<div class="col-md-2">
								<div class="collapse-plus">
									<a data-toggle="collapse" class="collapsed btn btn-sm btn-success" href="#collapseFilter2" style="width: 90%;">
										<span class="fas fa-times" aria-hidden="true"></span>&nbsp;
										Criar Novo Anexo
									</a>
								</div>
							</div>

							<?php if ($cod_chamado != 0) { ?>

								<div class="col-md-8">
									<a type="button" name="ADD" id="ADD" class="btn btn-info btn-sm pull-right addBox" data-url="action.php?mod=<?php echo fnEncode(1287) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idC=<?php echo fnEncode($cod_chamado); ?>&pop=true" data-title="Novo Comentário - Chamado #<?php echo $cod_chamado; ?>"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Adicionar Comentário</a>
								</div>

							<?php } ?>

						</div>

						<div class="row">

							<div class="col-md-4">
								<?php include "addAnexoSac.php"; ?>
							</div>

						</div>

						<div class="row">

							<div class="col-md-4">
								<?php include "listaUploadSac.php"; ?>
							</div>

						</div>



						<div class="push10"></div>
						<hr>
						<div class="form-group text-right col-lg-12">

							<a href="action.php?mod=<?php echo fnEncode(1282); ?>" name="ADD" id="ADD" class="btn btn-default pull-left" style="margin-right: 5px;"><i class="fa fa-arrow-left" aria-hidden="true"></i>&nbsp; Voltar à Lista</a>

							<button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>
							<?php if ($cod_chamado == 0) { ?>

								<button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Abrir suporte</button>

							<?php } else { ?>

								<button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Alterar</button>
								<button type="submit" name="EXC" id="EXC" class="btn btn-primary getBtn"><i class="fa fa-times" aria-hidden="true"></i>&nbsp; Excluir</button>

							<?php } ?>


						</div>
						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="COD_EMPRESA" id="COD_EMPRESA" value="<?= $cod_empresa ?>">
						<input type="hidden" name="DAT_CADASTR" id="DAT_CADASTR" value="<?php echo $dat_cadastr; ?>" required>
						<!--não pode passar $cod_empresa por aqui, pois ele será selecionado na combo-->
						<input type="hidden" name="COD_USURES_OLD" id="COD_USURES_OLD" value="<?= @$qrSac['COD_USURES'] ?>">
						<input type="hidden" name="COD_STATUS_OLD" id="COD_STATUS_OLD" value="<?= @$qrSac['COD_STATUS'] ?>">
						<input type="hidden" name="COD_PRIORIDADE_OLD" id="COD_PRIORIDADE_OLD" value="<?= @$qrSac['COD_PRIORIDADE'] ?>">
						<input type="hidden" name="COD_TPSOLICITA_OLD" id="COD_TPSOLICITA_OLD" value="<?= @$qrSac['COD_TPSOLICITACAO'] ?>">
						<input type="hidden" name="DAT_ENTREGA_OLD" id="DAT_ENTREGA_OLD" value="<?= fnDataShort($dat_entrega) ?>">
						<input type="hidden" name="DAT_PROXINT_OLD" id="DAT_PROXINT_OLD" value="<?= fnDataShort($dat_proxint) ?>">
						<input type="hidden" name="COD_USUARIO" id="COD_USUARIO" value="<?php echo $cod_usuario; ?>">
						<input type="hidden" name="COD_REFDOWN" id="COD_REFDOWN" value="<?php echo $conta; ?>">
						<input type="hidden" name="PRIMEIRO_UP" id="PRIMEIRO_UP" value="<?php echo $primeiroUp; ?>">
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">
						<div class="push5"></div>

					</form>


					<div class="push50"></div>

				</div>

				<div class="push"></div>

			</div>

		</div><!-- fim Portlet -->
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

<form id="formModal">
	<input type="hidden" class="input-sm" name="REFRESH_COMENTARIO" id="REFRESH_COMENTARIO" value="N">
</form>

<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="js/plugins/jQuery-TE/jquery-te-1.4.0.css">
<link rel="stylesheet" type="text/css" href="js/plugins/jQuery-TE/jquery-te.png">
<script type="text/javascript" src="js/plugins/jQuery-TE/jquery-te-1.4.0.min.js"></script>


<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />

<div id="retornaCombo"></div>

<script type="text/javascript">
	var idEmp = "<?= fnLimpaCampoZero($cod_empresa) ?>";

	$("#COD_EMPRESA_COMBO").change(function() {
		idEmp = $('#COD_EMPRESA_COMBO').val();
		$("#COD_EMPRESA").val(idEmp);
		buscaCombo(idEmp);
	});

	function buscaCombo(idEmp) {
		$.ajax({
			type: "GET",
			url: "ajxAddSuporte.php",
			data: {
				ajxEmp: idEmp
			},
			beforeSend: function() {
				// $('#relatorioUsu').html('<div class="loading" style="width: 100%;"></div>');
				$('#relatorioSis').html('<div class="loading" style="width: 100%;"></div>');
				$('#relatorioPlat').html('<div class="loading" style="width: 100%;"></div>');
				$('#relatorioVersao').html('<div class="loading" style="width: 100%;"></div>');
				$('#relatorioIntegra').html('<div class="loading" style="width: 100%;"></div>');
				$('#relatorioUnivend').html('<div class="loading" style="width: 100%;"></div>');
				$('#relatorioUsuEnv').html('<div class="loading" style="width: 100%;"></div>');
			},
			success: function(data) {
				// console.log(data);	
				// $('#relatorioUsu').html($('#relatorioUsuario',data));								
				$('#relatorioSis').html($('#relatorioSistema', data));
				$('#relatorioPlat').html($('#relatorioPlataforma', data));
				$('#relatorioVersao').html($('#relatorioVersaoIntegra', data));
				$('#relatorioIntegra').html($('#relatorioIntegracao', data));
				$('#relatorioUnivend').html($('#relatorioUnidades', data));
				$('#relatorioUsuEnv').html($('#relatorioUsuariosEnv', data));
				$('#retornaCombo').html($('#scripts', data));
				$('#formulario').validator('validate');
			},
			error: function() {
				// $('#relatorioUsu').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Empresa não encontrada...</p>');
				$('#relatorioUnivend').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Empresa não encontrada...</p>');
				$('#relatorioUsuEnv').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Empresa não encontrada...</p>');
			}
		});
	}

	function retornaForm(index) {

		// var usuario = '<?php echo $cod_usuario; ?>';
		// if(usuario != 0 && usuario != ""){$("#formulario #COD_USUARIO").val(<?php echo $cod_usuario; ?>).trigger("chosen:updated");}

		var usures = '<?php echo $cod_usures; ?>';
		if (usures != 0 && usures != "") {
			$("#formulario #COD_USURES").val(<?php echo $cod_usures; ?>).trigger("chosen:updated");
		}

		var sistemas = '<?php echo $cod_sistemas; ?>';
		if (sistemas != 0 && sistemas != "") {
			$("#formulario #COD_SISTEMAS").val(<?php echo $cod_sistemas; ?>).trigger("chosen:updated");
		}

		var plataforma = '<?php echo $cod_plataforma; ?>';
		if (plataforma != 0 && plataforma != "") {
			$("#formulario #COD_PLATAFORMA").val(<?php echo $cod_plataforma; ?>).trigger("chosen:updated");
		}

		var versaointegra = '<?php echo $cod_versaointegra; ?>';
		if (versaointegra != 0 && versaointegra != "") {
			$("#formulario #COD_VERSAOINTEGRA").val(<?php echo $cod_versaointegra; ?>).trigger("chosen:updated");
		}

		var integradora = '<?php echo $cod_integradora; ?>';
		if (integradora != 0 && integradora != "") {
			$("#formulario #COD_INTEGRADORA").val(<?php echo $cod_integradora; ?>).trigger("chosen:updated");
		}

		var tpsolicitacao = '<?php echo $cod_tpsolicitacao; ?>';
		if (tpsolicitacao != 0 && tpsolicitacao != "") {
			$("#formulario #COD_TPSOLICITACAO").val(<?php echo $cod_tpsolicitacao; ?>).trigger("chosen:updated");
		}

		var prioridade = '<?php echo $cod_prioridade; ?>';
		if (prioridade != 0 && prioridade != "") {
			$("#formulario #COD_PRIORIDADE").val(<?php echo $cod_prioridade; ?>).trigger("chosen:updated");
		}

		var status = '<?php echo $cod_status; ?>';
		if (status != 0 && status != "") {
			$("#formulario #COD_STATUS").val(<?php echo $cod_status; ?>).trigger("chosen:updated");
		}



		var univend = '<?php echo $cod_univend; ?>';
		if (univend != 0 && univend != "") {
			//retorno combo multiplo - lojas
			$("#formulario #COD_UNIVEND").val('').trigger("chosen:updated");

			var sistemasUni = '<?php echo $cod_univend; ?>';
			var sistemasUniArr = sistemasUni.split(',');
			//opções multiplas
			for (var i = 0; i < sistemasUniArr.length; i++) {
				$("#formulario #COD_UNIVEND option[value=" + sistemasUniArr[i] + "]").prop("selected", "true");
			}
			$("#formulario #COD_UNIVEND").trigger("chosen:updated");
		}


		var usuarios_env = '<?php echo $cod_usuarios_env; ?>';
		if (usuarios_env != 0 && usuarios_env != "") {
			//retorno combo multiplo - USUARIOS_ENV
			$("#formulario #COD_USUARIOS_ENV").val('').trigger("chosen:updated");

			var sistemasUni = '<?php echo $cod_usuarios_env; ?>';
			var sistemasUniArr = sistemasUni.split(',');
			//opções multiplas
			for (var i = 0; i < sistemasUniArr.length; i++) {
				$("#formulario #COD_USUARIOS_ENV option[value=" + sistemasUniArr[i] + "]").prop("selected", "true");
			}
			$("#formulario #COD_USUARIOS_ENV").trigger("chosen:updated");
		}

		//retorno combo multiplo - consultores
		var consultores = '<?php echo $cod_consultores; ?>';
		if (consultores != 0 && consultores != "") {
			$("#formulario #COD_CONSULTORES").val('').trigger("chosen:updated");

			var sistemasUni = '<?php echo $cod_consultores; ?>';
			var sistemasUniArr = sistemasUni.split(',');
			//opções multiplas
			for (var i = 0; i < sistemasUniArr.length; i++) {
				$("#formulario #COD_CONSULTORES option[value=" + sistemasUniArr[i] + "]").prop("selected", "true");
			}
			$("#formulario #COD_CONSULTORES").trigger("chosen:updated");
		}

		// $('#formulario').validator('validate');			
		$("#formulario #hHabilitado").val('S');
	}

	$(document).ready(function() {
		// alert($('#DAT_CHAMADO').val());

		// TextArea
		$(".editor").jqte({
			sup: false,
			sub: false,
			outdent: false,
			indent: false,
			left: false,
			center: false,
			color: false,
			right: false,
			strike: false,
			source: false,
			link: false,
			unlink: false,
			remove: false,
			rule: false,
			fsize: false,
			format: false,
		});

		// $(".jqte_editor").prop('contenteditable','false');
		// Fim

		$('#iAll').on('click', function(e) {
			e.preventDefault();
			$('#COD_UNIVEND option').prop('selected', true).trigger('chosen:updated');
		});

		$('#iNone').on('click', function(e) {
			e.preventDefault();
			$("#COD_UNIVEND option:selected").removeAttr("selected").trigger('chosen:updated');
		});


		var userDate = $('#DAT_CHAMADO').val();
		var dat_inicial = moment(userDate, "DD/MM/YYYY").format("YYYY-MM-DD");

		$('#DES_PREVISAO').mask('000.000.000.000.000,00', {
			reverse: true
		});

		$('.phone').mask('(00) 00000-0000');

		buscaCombo(<?php echo $cod_empresa; ?>);


		$('#DAT_CHAMADO_GRP').datetimepicker({
			format: 'DD/MM/YYYY',
			maxDate: 'now'
		}).on('changeDate', function(e) {
			$(this).datetimepicker('hide');
		});

		$('#DAT_PROXINT_GRP').datetimepicker({
			format: 'DD/MM/YYYY',
			minDate: 'now'
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

	});

	$('.upload').on('click', function(e) {

		if (idEmp == 0) {

			$.alert({
				title: "Upload não disponível.",
				content: "Não é possível efetuar um upload sem antes selecionar uma empresa.",
				buttons: {
					Ok: function() {
						$('#COD_EMPRESA_COMBO').trigger('chosen:activate');
					}
				}
			});


		} else {

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

		}

	});


	function uploadFile(idField, typeFile) {
		var formData = new FormData();
		var nomeArquivo = $('#' + idField)[0].files[0]['name'];

		formData.append('arquivo', $('#' + idField)[0].files[0]);
		formData.append('diretorio', '../media/clientes/');
		formData.append('diretorioAdicional', 'helpdesk');
		formData.append('id', idEmp);
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

					//ajax da lista de arquivos upados

					$.ajax({
						type: "POST",
						url: "ajxSacAnexo.php",
						data: $('#formulario').serialize(),
						beforeSend: function() {
							$('#relatorioConteudo').html('<div class="loading" style="width: 100%;"></div>');
						},
						success: function(data) {
							console.log(data);
							$('#relatorioConteudo').html(data);
							$('#PRIMEIRO_UP').val("N");
							$('#COD_EMPRESA_COMBO').prop("disabled", true).trigger("chosen:updated");
						},
						error: function() {
							$('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Itens não encontrados...</p>');
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