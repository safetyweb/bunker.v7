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
$popUp = "";
$sqlQtd = "";
$qtd_notifica = 0;
$qrBuscaModulos = "";
$status = "";
$sqlPerfil = "";
$qrPerfil = "";
$mod = "";
$pref = "";


//echo "<h5>_".$opcao."</h5>";

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

		if ($opcao != '') {

			$sql = "CALL SP_ALTERA_GRUPOTRABALHO (
				 '" . $cod_grupotr . "', 
				 '" . $des_grupotr . "', 
				 '" . $cod_empresa . "', 
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

//fnMostraForm();

?>

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

					<div class="login-form">

						<div class="col-lg-12">

							<div class="push30"></div>

							<div class="col-md-12">
								<a href="javascript:void(0)" class="btn btn-xs btn-info" onclick='leituraNotifica("<?= fnEncode("ALL") ?>",0,0)'>Marcar todos como lido</a>
							</div>

							<div class="push30"></div>

							<div class="no-more-tables">

								<form name="formLista">

									<table class="table table-bordered table-hover tableSorter">

										<thead>
											<tr>
												<th class="{sorter:false}"></th>
												<th>Nome</th>
												<th>Tipo</th>
												<th>Origem</th>
												<th>Notificação</th>
												<th class="{sorter:false}"></th>
											</tr>
										</thead>

										<tbody id="relatorioConteudo">

											<?php

											$sqlQtd = "SELECT N1.* FROM NOTIFICACOES N1
																	   JOIN (
																			  SELECT MAX(COD_NOTIFICACAO) AS COD_NOTIFICACAO 
																			  FROM NOTIFICACOES 
																			  WHERE COD_USUARIO = $_SESSION[SYS_COD_USUARIO] 
																			  GROUP BY TIP_ORIGEM, COD_IDENTIFICACAO) N2
																	   ON N1.COD_NOTIFICACAO = N2.COD_NOTIFICACAO";

											$qtd_notifica = mysqli_num_rows(mysqli_query($connAdm->connAdm(), $sqlQtd));

											$sql = "SELECT N1.* FROM NOTIFICACOES N1
																	JOIN (
																		  SELECT MAX(COD_NOTIFICACAO) AS COD_NOTIFICACAO 
																		  FROM NOTIFICACOES 
																		  WHERE COD_USUARIO = $_SESSION[SYS_COD_USUARIO] 
																		  GROUP BY TIP_ORIGEM, COD_IDENTIFICACAO) N2
																	ON N1.COD_NOTIFICACAO = N2.COD_NOTIFICACAO
																	ORDER BY DAT_LEITURA IS NOT NULL, DAT_LEITURA DESC
																	LIMIT 10
																	";

											$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

											$count = 0;
											while ($qrBuscaModulos = mysqli_fetch_assoc($arrayQuery)) {

												$count++;

												if ($qrBuscaModulos['DAT_LEITURA'] != '') {
													$status = "";
												} else {
													$status = "<span class='fas fa-circle text-danger'></span>&nbsp;";
												}

												switch ($qrBuscaModulos['TIP_ORIGEM']) {

													case 'SAC':

														$sqlPerfil = "SELECT COD_EMPRESA FROM USUARIOS WHERE COD_USUARIO = $_SESSION[SYS_COD_USUARIO]";
														$qrPerfil = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $sqlPerfil));

														if ($qrPerfil['COD_EMPRESA'] == 2) {
															$mod = 1285;
														} else if ($qrPerfil['COD_EMPRESA'] == 3) {
															$mod = 1462;
														} else {
															$mod = 1288;
														}

														$pref = "idC";

														break;

													default:

														break;
												}

											?>
												<tr>
													<td class="text-center"><small><small><?= $status ?></small></small></td>
													<td><small><?= $qrBuscaModulos['DES_NOTIFICA'] ?></small></td>
													<td><small><?= $qrBuscaModulos['DES_MOTIVO'] ?></small></td>
													<td><small><?= $qrBuscaModulos['TIP_ORIGEM'] ?></small></td>
													<td class="text-center"><small><?= fnDataFull($qrBuscaModulos['DAT_CADASTR']) ?></small></td>
													<td class="text-center">
														<small>
															<div class="btn-group dropdown dropleft">
																<button type="button" class="btn btn-info btn-xs dropdown-toggle transparency" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																	ações &nbsp;
																	<span class="fas fa-caret-down"></span>
																</button>
																<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
																	<li><a href='javascript:void(0)' onclick='leituraNotifica("<?= fnEncode($qrBuscaModulos['TIP_ORIGEM']) ?>","<?= fnEncode($qrBuscaModulos['COD_IDENTIFICACAO']) ?>",0)'>Marcar como lida </a></li>
																	<li><a onclick='leituraNotifica("<?= fnEncode($qrBuscaModulos['TIP_ORIGEM']) ?>","<?= fnEncode($qrBuscaModulos['COD_IDENTIFICACAO']) ?>",0)' href="action.php?mod=<?= fnEncode($mod) ?>&id=<?= fnEncode($qrBuscaModulos['COD_EMPRESA']) ?>&<?= $pref ?>=<?= fnEncode($qrBuscaModulos['COD_IDENTIFICACAO']) ?>" target="_blank">Acessar </a></li>
																</ul>
															</div>
														</small>
													</td>
												</tr>
											<?php

											}

											?>

										</tbody>

									</table>

									<?php
									if ($qtd_notifica > 15) { ?>
										<a class="btn btn-primary col-md-12" id="loadMore">Carregar mais notificações</a>
									<?php } ?>

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
		$(function() {

			var cont = 0;

			$('#loadMore').click(function() {

				cont += 10;

				leituraNotifica("<?= fnEncode("") ?>", 0, cont);

			});

		});

		function leituraNotifica(tip_origem, cod_identificacao, inicio) {
			$.ajax({
				type: "POST",
				url: "ajxListaNotificacoes.do",
				data: {
					TIP_ORIGEM: tip_origem,
					COD_IDENTIFICACAO: cod_identificacao,
					INICIO: inicio
				},
				beforeSend: function() {
					if (tip_origem != "<?= fnEncode("") ?>") {
						$('#relatorioConteudo').html('<div class="loading" style="width: 100%;"></div>');
					} else {
						$('#loadMore').text('Carregando...');
					}
				},
				success: function(data) {
					if (tip_origem != "<?= fnEncode("") ?>") {
						$("#relatorioConteudo").html(data);
					} else {
						$('#relatorioConteudo').append(data);
						if ((inicio + 10) >= "<?= $qtd_notifica ?>") {
							$('#loadMore').text('Não há mais notificações').addClass('disabled');
						} else {
							$('#loadMore').text('Carregar mais notificações');
						}
					}
				},
				error: function() {
					$('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Registros não encontrados...</p>');
				}
			});
		}
	</script>