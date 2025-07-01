<?php
if ($_SERVER['REMOTE_ADDR'] == '177.104.209.219') {
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}

$connUser = "";

ob_start('ob_gzhandler');
//gc_enable();
require_once "_system/_functionsMain.php";
//echo $_SESSION["SYS_MODUL_AUTOR"];
//echo  $_SESSION["testesql"];
//$_SESSION["SYS_COD_SISTEMA"]=fnDecode($_GET['sys']);
//echo fnDebug('true');


$moduloAtual = fnDecode(@$_GET['mod']);
$empresaModulo = fnLimpaCampoZero(fnDecode(@$_GET['id']));
if ($empresaModulo == 0) {
	$empresaModulo = @$_SESSION["SYS_COD_EMPRESA"];
}

$cod_univend_usuario = @$_SESSION["SYS_COD_UNIVEND"];

setcookie('SEGMENTO_BUNKER', @$_SESSION["SYS_COD_SEGMENT"]);
$_COOKIE["SEGMENTO_BUNKER"] = @$_SESSION["SYS_COD_SEGMENT"];

if (@$_GET['security'] != 'OFF') {
	fn_url();
	fnLogin();

	fncompress(@$connAdm->connAdm(), '30');
	$userConn = $connUser->connUser();
	if (isset($userConn) && $userConn !== null) {
		fncompress($userConn, '30');
	}

	$arraypost = addslashes(str_replace(array("\n", ""), array("", " "), var_export(gravapos(), true)));
	fnMemInicial($connAdm->connAdm(), 'true', $_SESSION["usuario"], $arraypost);
	$i = tempoinicial();
	carregaPagina('true');
}
//verifica se tela é pop up
if (isset($_REQUEST['pop'])) {
	$popUp = $_REQUEST['pop'];
	if ($popUp != "true") {
		$tipoPortlet = "portlet-bordered";
	} else {
		$tipoPortlet = "";
	}
} else {
	$popUp = "false";
}


//muda sistema
if (isset($_REQUEST['sys'])) {
	unset($_SESSION["SYS_MODUL_AUTOR"]);
	unset($_SESSION["SYS_COD_SISTEMA"]);
	unset($_SESSION["SYS_LOG_MULTEMPRESA"]);
	$_SESSION["SYS_COD_SISTEMA"] = fnDecode($_REQUEST['sys']);


	//altera session de multiempresa
	$sql = "select LOG_MULTEMPRESA,COD_HOME from SISTEMAS WHERE COD_SISTEMA = '" . fnDecode($_REQUEST['sys']) . "'";
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	$qrVerificaMultiEmpresa = mysqli_fetch_assoc($arrayQuery);
	$_SESSION["SYS_LOG_MULTEMPRESA"] = $qrVerificaMultiEmpresa['LOG_MULTEMPRESA'];
	$_SESSION["SYS_COD_HOME"] = $qrVerificaMultiEmpresa['COD_HOME'];
}
//selecionar os perfis do usuario
$sqluserperfil = "SELECT cod_perfils,des_apelido from usuarios where cod_empresa=" . $_SESSION["SYS_COD_EMPRESA"] . " and cod_usuario=" . $_SESSION["SYS_COD_USUARIO"];
$rsuserperfil = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $sqluserperfil));

$sqlperfil = "select cod_perfils, cod_modulos,cod_sistema from perfil where cod_sistema= " . $_SESSION["SYS_COD_SISTEMA"] . " and cod_perfils in (" . $rsuserperfil['cod_perfils'] . ")";

$rsperfil = mysqli_query($connAdm->connAdm(), $sqlperfil);
// eu diogo removi array_unique poque nao faz sentido caso alguem reclame me explique o porque dessa trem aqui.
//original  while ($resultperfil= array_unique(mysqli_fetch_assoc($rsperfil)))
while ($resultperfil = mysqli_fetch_assoc($rsperfil)) {
	@$SYS_MODUL_AUTOR .= $resultperfil['cod_modulos'] . ',';
}
$_SESSION["SYS_MODUL_AUTOR"] = explode(",", $SYS_MODUL_AUTOR);
//echo print_r($_SESSION["SYS_MODUL_AUTOR"]);

$desApelidoUsuarioLogado = $rsuserperfil['des_apelido'];

?>

<style>
	<?php require_once "clippy.css" ?>
</style>


<html lang="pt">

<head>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,height=device-height,initial-scale=1.0" />

	<title>Webtools</title>

	<?php require_once "cssLib.php"; ?>

	<!-- Favicons -->
	<link rel="icon" href="images/favicon.ico">

</head>


<body>

	<?php if ($popUp != "true") {
		require_once "header.php";
	} ?>

	<?php if ($popUp != "true") {
		require_once  "menu.php";
	} ?>

	<?php if ($popUp != "true") {
		$containerOut = "outContainer";
	} else {
		$containerOut = "outContainerPop";
	} ?>

	<div class="<?php echo $containerOut; ?>">

		<?php if ($popUp != "true") {
			$container = "containerfluid";
		} else {
			$container = "containerfluid";
		} ?>

		<div class="<?php echo $container; ?>">


			<?php if ($popUp != "true") {  ?>
				<!-- <div class="push50"></div> -->
			<?php } ?>

			<?php

			//;fnEscreve("Entrou 1");


			if (!empty($_GET['mod'])) {

				$qrModulBUsca = "select * from modulos where COD_MODULOS='" . fnDecode($_GET['mod']) . "'";
				$QrCarregaModul = mysqli_query($connAdm->connAdm(), $qrModulBUsca);
				$QrLinhaModul = mysqli_fetch_assoc($QrCarregaModul);
				$NomePg = $QrLinhaModul['NOM_MODULOS'];
				$tip_modulos = $QrLinhaModul['TIP_MODULOS'];
				$ActionPg = $QrLinhaModul['DES_COMMAND'];
				$moduloSensivel = ($QrLinhaModul['LOG_SENSIVEL'] == "S");

				if (!is_null($QrLinhaModul['COD_DESTINO']) || !empty($QrLinhaModul['COD_DESTINO'])) {
					$RedirectPg = $QrLinhaModul['COD_DESTINO'];
				}
				$filename = $ActionPg;
				// echo 'DIOGO......'.$tip_modulos.'<br>';
				//  echo 'DIOGO......'.$filename;

				$retur = fncomparaPerfil($_SESSION['SYS_COD_MOD'], $moduloAtual, $_SESSION['SYS_COD_MULTEMP'], $empresaModulo, $_SESSION['SYS_COD_SISTEMA']);

				if (@$_GET['dev'] == 'true') {

					echo "<pre>";
					print_r($_SESSION['SYS_COD_MOD']);
					echo "<br />";
					FNeSCREVE2($moduloAtual);
					echo "<br />";
					print_r($_SESSION['SYS_COD_MULTEMP']);
					echo "<br />";
					FNeSCREVE2($empresaModulo);
					echo "<br />";
					print_r($_SESSION['SYS_COD_SISTEMA']);
					echo "<br />";
					print_r($retur);
					echo "</pre>";
				}

				if ($retur == 0) {
					echo ":( Acesso negado ao módulo <b> " . $QrLinhaModul['NOM_MODULOS'] . " </b>. Entre em contato com o suporte.";
					// echo ":( Você não possui acesso a este módulo. Para mais informações entre em contato com o suporte.";
					// echo '<meta http-equiv="refresh" content="5; url=action.do" />';
					// exit();
				} else {

					if ($tip_modulos == 2) {

						if (file_exists('./relatorios/' . $filename)) {
							require_once "./relatorios/" . $ActionPg . "";
						} else {
							echo ":( O módulo <b> " . $QrLinhaModul['NOM_MODULOS'] . " </b> não existe, você esta sendo redirecionado automaticamente";
							$page_action = "action.do";
							if (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false) {
								$page_action = "action.php";
							}
							echo '<meta http-equiv="refresh" content="5; url=' . $page_action . '" />';
						}
					} elseif ($tip_modulos == 5) {


						if (file_exists('./maiscash/' . $filename)) {
							require_once "./maiscash/" . $ActionPg . "";
						} else {
							echo ":( O módulo <b> " . $QrLinhaModul['NOM_MODULOS'] . " </b> não existe, você esta sendo redirecionado automaticamente";
							$page_action = "action.do";
							if (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false) {
								$page_action = "action.php";
							}
							echo '<meta http-equiv="refresh" content="5; url=' . $page_action . '" />';
						}
					} else {
						if (file_exists($filename)) {
							require_once "$ActionPg";
						} else {
							echo ":( O módulo <b> " . $QrLinhaModul['NOM_MODULOS'] . " </b> não existe, você esta sendo redirecionado automaticamente";
							$page_action = "action.do";
							if (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false) {
								$page_action = "action.php";
							}
							echo '<meta http-equiv="refresh" content="5; url=' . $page_action . '" />';
						}
					}

					if ($moduloSensivel) {
			?>
						<script>
							$(document).ready(function() {

								var msg_alert_head = "";
								msg_alert_head += '<div class="alert alert-warning alert-dismissible" role="alert">';
								msg_alert_head += '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>';
								msg_alert_head += '<strong><i class="fas fa-radiation fa-lg"></i>&nbsp; Atenção!</strong> Esta tela contém dados pessoais confidenciais e não podem ser compartilhados.';
								msg_alert_head += '</div>';

								var msg_alert_foot = "";
								msg_alert_foot += '<span class="text-warning" style="background-color: #E5E7E9; padding: 5px 10px 5px 10px; border-radius: 15px;">';
								msg_alert_foot += '<i class="fas fa-radiation fa-lg"></i>&nbsp; Atenção!</strong> Esta tela contém dados pessoais confidenciais e não podem ser compartilhados.';
								msg_alert_foot += '</span>';

								if ($(".portlet-body").length > 0) {
									$(".portlet-body:first").prepend(msg_alert_head);
									$(".portlet-body:last").append(msg_alert_foot);
								} else {
									$(".containerfluid:first").prepend(msg_alert_head);
									$(".containerfluid:last").append(msg_alert_foot);
								}
							});
						</script>
					<?php
					}

					//$sqlClippy = "select * from CLIP_HELP WHERE COD_MODULOS='$moduloAtual' AND COD_EMPRESA IN ($empresaModulo)";
					$sqlClippy = "select * from CLIP_HELP WHERE COD_MODULOS='$moduloAtual' AND (FIND_IN_SET('$empresaModulo', COD_EMPRESA) > 0 OR FIND_IN_SET('9999', COD_EMPRESA) > 0)";
					//fnTesteSql($connAdm->connAdm(), $sqlClippy);
					$arrayClippy = mysqli_query($connAdm->connAdm(), $sqlClippy);
					$qrBuscaClippy = mysqli_fetch_assoc($arrayClippy);

					//fnEscreveArray($qrBuscaClippy);

					if ($qrBuscaClippy) {
					?>
						<script>
							$(document).ready(function() {

								function criarMensagemClippy() {
									var msg_clippy = "";
									msg_clippy += '<div class="alert alert-secondary alert-dismissible top30 msgInfo" role="alert" id="msgInfo" tabindex="-1" style="display: none;">';
									msg_clippy += '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
									msg_clippy += '<strong><i class="fas fa-bullseye-arrow fa-lg"></i></strong>&nbsp; <?php echo $qrBuscaClippy["NOM_CLIP_HELP"]; ?> <div class="push"></div>';
									msg_clippy += '<?php echo $qrBuscaClippy["DES_CLIP_HELP"]; ?>';
									msg_clippy += '</div>';

									$('.portlet-body:first').prepend(msg_clippy);

									$("#msgInfo .close").click(function() {
										$("#msgInfo").fadeOut('fast', function() {
											$(this).remove();
											$(".btn-clippy").prop('disabled', false);
										});
									});

								}

								$(".btn-clippy").click(function() {
									if ($("#msgInfo").length === 0) {
										criarMensagemClippy();
									}
									$("#msgInfo").fadeIn('fast', function() {
										$(this).focus();
									});
								});

							});
						</script>
					<?php
						echo "
						<a href='javascript:void(0)' class='btn-clippy' data-toggle='tooltip' data-placement='top' data-original-title='+info'>
						<div class='clippy' id='clippy'>
						<div class='eye left'></div>
						<div class='eye right'></div>
						</div>
						</a>";
					}

					require_once "action_fluxo.php";
				}
			} else {

				//monta página home default

				if ($_SESSION["SYS_COD_HOME"] != 0) {
					$ActionPg = $_SESSION["SYS_PAG_HOME"];

					if (@$_GET["mod"] == "") {
						$page_action = "action.do";
						$http = "https";
						if (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false) {
							$page_action = "action.php";
							$http = "http";
						}
					?>
						<script>
							window.location.href = "<?php echo $http . "://" . $_SERVER['HTTP_HOST'] . "/" . $page_action . "?mod=" . fnEncode($_SESSION["SYS_COD_HOME"]) . "&id=" . fnEncode($_SESSION["SYS_COD_EMPRESA"]) ?>";
						</script>
				<?php
						echo "Aguarde... Redirecionando...";
						ob_start();
						header("Location:" . $http . "://" . $_SERVER['HTTP_HOST'] . "/" . $page_action . "?mod=" . fnEncode($_SESSION["SYS_COD_HOME"]) . "&id=" . fnEncode($_SESSION["SYS_COD_EMPRESA"]), true, 301);

						exit;
					}
					require_once "$ActionPg";
					//echo "Aguarde. Redirecionando... ;-)";
					//echo '<meta http-equiv="refresh" content="0; url=action.do?mod='.fnEncode($_SESSION["SYS_COD_HOME"]).'&id='.fnEncode($_SESSION["SYS_COD_EMPRESA"]).'" />';
				}
			}


			$sqlEmp = "SELECT LOG_MSGCOBR FROM empresas WHERE cod_empresa=" . $_SESSION['SYS_COD_EMPRESA'];
			$arrayQueryEmp = mysqli_query($connAdm->connAdm(), $sqlEmp);

			$qrEmpresa = mysqli_fetch_assoc($arrayQueryEmp);

			if ($qrEmpresa['LOG_MSGCOBR'] == 'S') {

				$dir = "media/alertas/";
				$nomeArquivo = $cod_empresa . "_mensagem_1.txt";

				if (($file = fopen($dir . $nomeArquivo, "r")) !== FALSE) {
					$contador = 1;
					while (($linha = fgetcsv($file, '24000', ";", '"', "\r\n")) !== FALSE) {

						if ($contador > 1) {
							$msgcobr = $linha[2];
						}

						$contador++;
					}
				}
				?>

				<script>
					$(document).ready(function() {

						var msg_alert_head2 = "";
						msg_alert_head2 += '<div class="alert alert-danger alert-dismissible" role="alert">';
						msg_alert_head2 += '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>';
						msg_alert_head2 += '<strong><i class="fal fa-exclamation-triangle"></i>&nbsp; Atenção! </strong> <?php echo str_replace("\r", "", str_replace("\n", "", str_replace("\r\n", "", nl2br($msgcobr)))); ?>';
						msg_alert_head2 += '</div>';


						if ($(".portlet-body").length > 0) {
							$(".portlet-body:first").prepend(msg_alert_head2);
						} else {
							$(".containerfluid:first").prepend(msg_alert_head2);
						}
					});
				</script>

				<?php


			} elseif ($cod_univend_usuario != 0 || $cod_univend_usuario != "") {

				$sqlUniv = "SELECT LOG_MSGCOBR, COD_UNIVEND FROM unidadevenda WHERE COD_UNIVEND IN ($cod_univend_usuario) AND COD_EMPRESA = $cod_empresa AND LOG_MSGCOBR = 'S'";
				$arrayQueryUniv = mysqli_query($connAdm->connAdm(), $sqlUniv);

				while (@$qrUniv = mysqli_fetch_assoc($arrayQueryUniv)) {

					$dir = "media/alertas/";
					$nomeArquivo = $cod_empresa . "_" . $qrUniv['COD_UNIVEND'] . "_mensagem_1.txt";

					if (($file = fopen($dir . $nomeArquivo, "r")) !== FALSE) {
						$contador = 1;
						while (($linha = fgetcsv($file, '24000', ";", '"', "\r\n")) !== FALSE) {

							if ($contador > 1) {
								$msgcobr = $linha[2];
							}

							$contador++;
						}
					}
				}
				if (@$arrayQueryUniv->num_rows > 0) {

				?>

					<script>
						$(document).ready(function() {

							var msg_alert_head3 = "";
							msg_alert_head3 += '<div class="alert alert-danger alert-dismissible" role="alert">';
							msg_alert_head3 += '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>';
							msg_alert_head3 += '<strong><i class="fal fa-exclamation-triangle"></i>&nbsp; Atenção!</strong> <?php echo str_replace("\r", "", str_replace("\n", "", str_replace("\r\n", "", nl2br($msgcobr)))); ?>';
							msg_alert_head3 += '</div>';


							if ($(".portlet-body").length > 0) {
								$(".portlet-body:first").prepend(msg_alert_head3);
							} else {
								$(".containerfluid:first").prepend(msg_alert_head3);
							}
						});
					</script>

			<?php
				}
			}

			//print_r($_COOKIE);
			//echo "<H1>_".$_COOKIE["SEGMENTO_BUNKER"]."_</H1>";	
			//echo "<H1>_".$_SESSION["SYS_COD_SEGMENT"]."_</H1>";	

			?>

			<?php if ($popUp != "true") {   ?>
				<div class="push100"></div>
			<?php } ?>

			<a href="#0" class="cd-top">Top</a>

		</div>
		<!-- end container -->

	</div>
	<!-- end outContainer -->

	<?php require_once "jsLib.php"; ?>

	<script type="text/javascript">
		try {
			$(".search-bar").first().focus();
		} catch (err) {}
		$(function() {
			let titPagHead = "<?= $NomePg ?>";
			if (titPagHead != "") {
				document.title = 'Webtools - ' + titPagHead;
			}
			// try {
			// 	$("input:text").first().focus();
			// }catch(err) {}
		});

		function pin_workspace(url = '<?= fnEncode("https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]") ?>') {
			// alert('fixando');
			$.ajax({
				type: "POST",
				url: "ajxWorkspace.do",
				data: {
					url: url,
					mod: "<?= $_GET['mod'] ?>"
				},
				beforeSend: function() {
					$('#relatorioConteudo').html('<div class="loading" style="width: 100%;"></div>');
				},
				success: function(data) {
					console.log(data);
					if (data == 1) {
						$('#btnPin').attr("data-original-title", "Remover da Home");
						$('#iconePin').removeClass("fal").addClass("fas");
					} else {
						$('#btnPin').attr("data-original-title", "Fixar na Home");
						$('#iconePin').removeClass("fas").addClass("fal");
					}
				},
				error: function() {

				}
			});
		}
	</script>

</body>

</html>
<?php
//gc_collect_cycles();
if (@$_GET['security'] != 'OFF') {
	carregaPagina('false');
	tempofinal($i, $connAdm->connAdm());
	fnMemInicial($connAdm->connAdm(), 'false', $_SESSION["usuario"], $arraypost);

	LOG_DB($connAdm->connAdm(), $connAdm->connAdm());
	//LOG_DB($connUser->connUser(),$connUser->connUser());
	process_kill($connAdm->connAdm());
	process_kill($connUser->connUser());
	cache_query($connAdm->connAdm(), 1);
	cache_query($connUser->connUser(), 1);
	//cache_query (connTemp($_SESSION["SYS_COD_EMPRESA"],''),1);
}
ob_end_flush();
?>