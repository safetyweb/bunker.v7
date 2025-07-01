<?php

// definir o numero de itens por pagina
$itens_por_pagina = 20;
$pagina  = "1";

$dias30 = "";
$dat_ini = "";
$dat_fim = "";

$cod_externo = "";
$nom_chamado = "";
$cod_integradora = "";
$cod_plataforma = "";
$cod_versaointegra = "";
$cod_prioridade = "";

$cod_tpsolicitacao = "";
$cod_status = "";

$hashLocal = mt_rand();

//inicialização de variáveis
$hoje = fnFormatDate(date("Y-m-d"));
//$dias30 = fnFormatDate(date('Y-m-d', strtotime($dias30. '- 1 days')));
$dias30 = fnFormatDate(date("Y-m-d"));

//echo fnDebug('true');

$hashLocal = mt_rand();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = md5(serialize($_POST));

	if (isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request) {
		$msgRetorno = 'Essa página já foi utilizada';
		$msgTipo = 'alert-warning';
	} else {
		$_SESSION['last_request']  = $request;

		$dat_ini = fnDataSql($_POST['DAT_INI']);
		$dat_fim = fnDataSql($_POST['DAT_FIM']);
		$cod_externo = $_POST['COD_EXTERNO'];

		$nom_chamado = $_POST['NOM_CHAMADO'];

		$cod_tpsolicitacao = $_POST['COD_TPSOLICITACAO'];
		$cod_status = $_POST['COD_STATUS'];


		//fnEscreve($cod_usuario);

		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];
		$usu_cadastr = $_SESSION["SYS_COD_USUARIO"];

		if ($opcao != '') {


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

if (strlen($dat_ini) == 0 || $dat_ini == "1969-12-31") {
	$dat_ini = fnDataSql($dias30);
}
if (strlen($dat_fim) == 0 || $dat_fim == "1969-12-31") {
	$dat_fim = fnDataSql($hoje);
}

//Adicionado por Lucas ref chamado #6406
//inicio
$cod_usuario = $_SESSION["SYS_COD_USUARIO"];

$sqlUsu = "SELECT * FROM USUARIOS WHERE COD_USUARIO = $cod_usuario";
$query = mysqli_query($connAdm->connAdm(), $sqlUsu);

if ($qrUsuario = mysqli_fetch_assoc($query)) {
	$cod_univend = $qrUsuario['COD_UNIVEND'];
}
//Fim

//busca revendas do usuário
include "unidadesAutorizadas.php";

// $LOJAS = explode(',', $lojasSelecionadas);

// $lojasSelecionadas = "'".implode("','", $LOJAS)."'";
// fnEscreve2($lojasSelecionadas);

// verifica se o perfil é limitado nas unidades
// $sqlUnivend = "SELECT COD_UNIVEND FROM UNIDADEVENDA WHERE COD_EMPRESA = $cod_empresa AND LOG_ESTATUS = 'S'";
// $arrayUnivend = mysqli_query($connAdm->connAdm(),$sql);
// $totUnidades = mysqli_num_rows($arrayUnivend);
// // variavel vem da session SYS_COD_UNIVEND
// $arrUnidadesUsu = explode(",", $cod_univendUsu);

// if(count($arrUnidadesUsu) < $totUnidades){
// 	echo "perfil limitado";
// }

//fnEscreve($cod_empresa);	
//fnEscreve($nom_empresa);	

//fnMostraForm('#formulario');

?>

<div class="push30"></div>

<div class="row">

	<div class="col-md12 margin-bottom-30">
		<!-- Portlet -->
		<div class="portlet portlet-bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="glyphicon glyphicon-calendar"></i>
					<span class="text-primary"><?php echo $NomePg; ?> <?php echo $nom_empresa; ?></span>
				</div>

				<?php
				//$formBack = "1019";

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

				<?php $abaInfoSuporte = 1280;
				include "abasInfoSuporteEmpresa.php"; ?>

				<div class="push20"></div>

				<div class="login-form">

					<form data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

						<fieldset>
							<legend>Filtros para Busca</legend>

							<div class="row">

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Data Inicial</label>

										<div class="input-group date datePicker" id="DAT_CHAMADO_GRP">
											<input type='text' class="form-control input-sm data" name="DAT_INI" id="DAT_INI" value="" />
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
										</div>
										<div class="help-block with-errors"></div>
									</div>
								</div>

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Data Final</label>

										<div class="input-group date datePicker" id="DAT_CHAMADO_GRP">
											<input type='text' class="form-control input-sm data" name="DAT_FIM" id="DAT_FIM" value="<?php echo fnFormatDate($dat_fim); ?>" />
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

								<div class="col-md-2">
									<div class="form-group">
										<label for="inputName" class="control-label">Tipo de Solicitação</label>
										<select class="chosen-select-deselect requiredChk" data-placeholder="Selecione o tipo" name="COD_TPSOLICITACAO" id="COD_TPSOLICITACAO">
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
										<label for="inputName" class="control-label">Status</label>
										<select class="chosen-select-deselect requiredChk" data-placeholder="Selecione o status" name="COD_STATUS" id="COD_STATUS">
											<option value=""></option>
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
									<div class="push20"></div>
									<button type="submit" name="BUS" id="BUS" class="btn btn-primary btn-sm btn-block getBtn"><i class="fa fa-filter" aria-hidden="true"></i>&nbsp; Filtrar</button>
								</div>

							</div>

						</fieldset>

						<div class="push30"></div>
						<a href="action.php?mod=<?php echo fnEncode(1278); ?>&id=<?php echo fnEncode($cod_empresa); ?>" name="ADD" id="ADD" class="btn btn-success pull-left"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Criar Novo Chamado</a>


						<input type="hidden" name="opcao" id="opcao" value="">
						<input type="hidden" name="LOJAS" id="LOJAS" value="<?php echo $lojasSelecionadas; ?>" />
						<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />
						<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">

					</form>

					<div class="push30"></div>

					<div class="col-lg-12" style="padding:0;">

						<div class="no-more-tables">

							<form name="formLista">

								<table class="table table-bordered table-striped table-hover tableSorter">
									<thead>
										<tr>
											<th><small>Código</small></th>
											<th><small>Título</small></th>
											<th><small>Data de Cadastro</small></th>
											<th><small>Solicitante</small></th>
											<th><small>Tipo da Solicitação</small></th>
											<th><small>Previsão (Entrega)</small></th>
											<th class="text-center"><small>Status</small></th>
										</tr>
									</thead>
									<tbody id="relatorioConteudo">

										<?php

										if ($dat_ini == date('Y-m-d')) {
											$datIniAND = " ";
										} else {
											$datIniAND = "DATE_FORMAT(SC.DAT_CHAMADO, '%Y-%m-%d') >= '$dat_ini' AND ";
										}

										if ($dat_fim == date('Y-m-d')) {
											$dat_fim = fnDataSql($hoje);
										}

										if ($cod_externo == "") {
											$ANDcodExterno = " ";
										} else {
											$ANDcodExterno = "AND SC.COD_EXTERNO LIKE '%$cod_externo%' ";
										}

										if ($nom_chamado == "") {
											$ANDnomChamado = " ";
										} else {
											$ANDnomChamado = "AND SC.NOM_CHAMADO LIKE '%$nom_chamado%' ";
										}

										if ($cod_tpsolicitacao == "") {
											$ANDcodTipo = " ";
										} else {
											$ANDcodTipo = "AND SC.COD_TPSOLICITACAO = $cod_tpsolicitacao ";
										}

										if ($cod_status == "") {
											$ANDcodStatus = " ";
										} else {
											$ANDcodStatus = "AND SC.COD_STATUS = $cod_status ";
										}

										if ($cod_integradora == "") {
											$ANDcodIntegradora = " ";
										} else {
											$ANDcodIntegradora = "AND SC.COD_INTEGRADORA = $cod_integradora ";
										}

										if ($cod_plataforma == "") {
											$ANDcodPlataforma = " ";
										} else {
											$ANDcodPlataforma = "AND SC.COD_PLATAFORMA = $cod_plataforma ";
										}

										if ($cod_versaointegra == "") {
											$ANDcodVersaointegra = " ";
										} else {
											$ANDcodStatus = "AND SC.COD_VERSAOINTEGRA = $cod_versaointegra ";
										}

										if ($cod_prioridade == "") {
											$ANDcodPrioridade = " ";
										} else {
											$ANDcodPrioridade = "AND SC.COD_PRIORIDADE = $cod_prioridade ";
										}


										$sqlCount = "SELECT COUNT(*) AS CONTADOR FROM SAC_CHAMADOS SC 
																WHERE
																$datIniAND
												  				DATE_FORMAT(SC.DAT_CHAMADO, '%Y-%m-%d') <= '$dat_fim'
												  				$ANDcodExterno
												  				$ANDnomChamado
												  				$ANDcodStatus
												  				$ANDcodTipo
												  				$ANDcodIntegradora
												  				$ANDcodPlataforma
												  				$ANDcodVersaointegra
												  				$ANDcodPrioridade
												  				AND SC.COD_EMPRESA = $cod_empresa 
												  				AND SC.COD_UNIVEND IN($lojasSelecionadas)
																ORDER BY SC.DAT_CADASTR DESC
																";
										//fnEscreve($sqlSac);

										$retorno = mysqli_query($connAdmSAC->connAdm(), $sqlCount);
										$total_itens_por_pagina = mysqli_fetch_assoc($retorno);

										$numPaginas = ceil($total_itens_por_pagina['CONTADOR'] / $itens_por_pagina);

										//variavel para calcular o início da visualização com base na página atual
										$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

										$sqlSac = "SELECT SC.COD_CHAMADO, SC.COD_EMPRESA, SC.NOM_CHAMADO, SC.LOG_INTERAC,
																SC.COD_EXTERNO,	SC.DAT_CADASTR, SC.DAT_CHAMADO, SC.COD_USUARIO, SC.DAT_ENTREGA,
																ST.DES_TPSOLICITACAO, SS.ABV_STATUS, SS.DES_COR AS COR_STATUS, SS.DES_ICONE AS ICO_STATUS
																FROM SAC_CHAMADOS SC 
																LEFT JOIN SAC_TPSOLICITACAO ST ON ST.COD_TPSOLICITACAO=SC.COD_TPSOLICITACAO
																LEFT JOIN SAC_STATUS SS ON SS.COD_STATUS=SC.COD_STATUS
																WHERE 
																$datIniAND
																DATE_FORMAT(SC.DAT_CADASTR, '%Y-%m-%d') <= '$dat_fim'
																$ANDcodExterno
																$ANDnomChamado
																$ANDcodStatus
																$ANDcodTipo
																$ANDcodIntegradora
																$ANDcodPlataforma
																$ANDcodVersaointegra
																$ANDcodPrioridade
																AND SC.COD_EMPRESA = $cod_empresa 
																-- AND SC.COD_UNIVEND IN($lojasSelecionadas)
																AND (
																      FIND_IN_SET(SUBSTRING_INDEX(SC.COD_UNIVEND, ',', 1), '$lojasSelecionadas') > 0 OR
																      FIND_IN_SET(SUBSTRING_INDEX(SUBSTRING_INDEX(SC.COD_UNIVEND, ',', -1), ',', 1), '$lojasSelecionadas') > 0 OR
																      FIND_IN_SET('$lojasSelecionadas', SC.COD_UNIVEND) > 0  
																)
																ORDER BY SC.COD_CHAMADO DESC limit $inicio,$itens_por_pagina
																";
										//fnEscreve2($sqlSac);
										$arrayQuerySac = mysqli_query($connAdmSAC->connAdm(), $sqlSac);

										$count = 0;
										while ($qrSac = mysqli_fetch_assoc($arrayQuerySac)) {
											$count++;

											if ($qrSac['DAT_ENTREGA'] == "1969-12-31") {
												$entrega = "";
											} else {
												$entrega = fnDataShort($qrSac['DAT_ENTREGA']);
												if (fnDatasql($entrega) < fnDatasql($hoje)) {
													$entrega = "<span class='text-danger'><b>" . fnDataShort($qrSac['DAT_ENTREGA']) . "</b></span>";
												}
											}

											if ($qrSac['COD_USUARIO'] != '') {
												$selectSolicitante = "(SELECT NOM_USUARIO FROM USUARIOS WHERE COD_USUARIO = $qrSac[COD_USUARIO]) AS NOM_SOLICITANTE";
											} else {
												$selectSolicitante = "('') AS NOM_SOLICITANTE";
											}

											if (isset($qrSac['COD_USURES']) && $qrSac['COD_USURES'] != '') {
												$selectRespons = "(SELECT NOM_USUARIO FROM USUARIOS WHERE COD_USUARIO = $qrSac[COD_USURES]) AS NOM_RESPONSAVEL";
											} else {
												$selectRespons = "('') AS NOM_RESPONSAVEL";
											}

											$sqlUsuarios = "SELECT $selectSolicitante,$selectRespons";
											//fnEscreve($sqlUsuarios);
											$qrNomUsu = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $sqlUsuarios));

											if ($qrSac['LOG_INTERAC'] == 'S') {
												$cor_interac = "background: #FCF3CF";
											} else {
												$cor_interac = "";
											}

										?>

											<tr style="<?= $cor_interac ?>">
												<td class="text-center">
													<small>
														<a href="action.php?mod=<?= fnEncode(1288); ?>&id=<?php echo fnEncode($qrSac['COD_EMPRESA']); ?>&idC=<?php echo fnEncode($qrSac['COD_CHAMADO']); ?>" target="_blank"><?= $qrSac['COD_CHAMADO'] ?>&nbsp;
															<span class="fa fa-external-link-square"></span>
														</a>
													</small>
												</td>
												<td><small><?php echo $qrSac['NOM_CHAMADO']; ?></small></td>
												<td class="text-center"><small><?php echo fnDataShort($qrSac['DAT_CADASTR']);; ?></small></td>
												<td><small><?php echo $qrNomUsu['NOM_SOLICITANTE']; ?></small></td>
												<td><small><?php echo $qrSac['DES_TPSOLICITACAO']; ?></small></td>
												<td class="text-center f14"><small><?= $entrega ?></small></td>
												<td class="text-center">
													<small>
														<p class="label" style="background-color: <?php echo $qrSac['COR_STATUS'] ?>">
															<span class="<?php echo $qrSac['ICO_STATUS']; ?>" style="color: #FFF;"></span>
															&nbsp;<?php echo $qrSac['ABV_STATUS']; ?>
														</p>
													</small>
												</td>

											</tr>
										<?php
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



							</form>

							<div class="push10"></div>

						</div>

					</div>

					<div class="push30"></div>

				</div>

			</div>
		</div>
		<!-- fim Portlet -->
	</div>

</div>

<div class="push20"></div>

<script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
<script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />

<script type="text/javascript">
	function retornaForm(index) {

		var tpsolicitacao = '<?php echo $cod_tpsolicitacao; ?>';
		if (tpsolicitacao != 0 && tpsolicitacao != "") {
			$("#formulario #COD_TPSOLICITACAO").val(<?php echo $cod_tpsolicitacao; ?>).trigger("chosen:updated");
		}

		var status = '<?php echo $cod_status; ?>';
		if (status != 0 && status != "") {
			$("#formulario #COD_STATUS").val(<?php echo $cod_status; ?>).trigger("chosen:updated");
		}

		$('#formulario').validator('validate');
		$("#formulario #hHabilitado").val('S');
	}

	$(document).ready(function() {

		retornaForm(0);

		$('.datePicker').datetimepicker({
			format: 'DD/MM/YYYY',
			maxDate: 'now',
		}).on('changeDate', function(e) {
			$(this).datetimepicker('hide');
		});

		//chosen obrigatório
		$.fn.validator.Constructor.INPUT_SELECTOR = ':input:not([type="submit"], button):enabled, .requiredChk';
		$('#formulario').validator();

		var numPaginas = <?php echo $numPaginas; ?>;
		if (numPaginas != 0) {
			carregarPaginacao(numPaginas);
		}

	});

	function reloadPage(idPage) {
		$.ajax({
			type: "POST",
			url: "ajxListaSuporteEmpresa.do?id=<?= fnEncode($cod_empresa) ?>&opcao=paginar&idPage=" + idPage + "&itens_por_pagina=<?php echo $itens_por_pagina; ?>",
			data: $('#formulario').serialize(),
			beforeSend: function() {
				$('#relatorioConteudo').html('<div class="loading" style="width: 100%;"></div>');
			},
			success: function(data) {
				$("#relatorioConteudo").html(data);
			},
			error: function(data) {
				$('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
				console.log(data);
			}
		});
	}
</script>