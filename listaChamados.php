<link rel="stylesheet" href="js/colorPick/colorPick.css">
<script src="js/colorPick/colorPick.js"></script>
<style>
	/*bolinha (cor tem que ser fixa)*/
	.badge {
		display: table-cell;
		border-radius: 999px;
		width: 20px;
		height: 20px;
		color: white;
		font-size: 9px;
		margin-right: 15px;
		padding: 0;
		text-align: center;
	}

	/*pill (aceita cores do bootstrap)*/
	.label-as-badge {
		border-radius: 1em;
		display: table-cell;
		text-align: center;
		color: white;
		font-size: 9px;
	}

	.txtBadge {
		display: table-cell;
		vertical-align: middle;
		padding: 0;
		width: 20px;
	}



	.notify-badge {
		position: relative;
		background: #18bc9c;
		right: 1px;
		top: -5px;
		border-radius: 10px 10px 10px 10px;
		padding: 4px;
		text-align: center;
		color: white;
		font-size: 11px;
	}

	.notify-badge span {
		margin: 0 auto;
	}


	/*Menu DropDown*/
	.menu {
		top: 0 !important;
		left: -100px !important;
		width: 100px !important;
		z-index: 9999999;
		font-size: 13px !important;
	}



	.menu li a {
		color: #3c3c3c !important;
	}



	.menu-down-right,
	.menu-down-left,
	.menu.menu--right {
		transform-origin: top left !important;
	}


	.pitstop {
		background: #d98880;
		color: #FFF;
		padding: 1px 5px 2px 5px;
		border-radius: 3px;
	}

	.pitstop:hover {
		color: #FFF;
	}


	.picker {
		border-radius: 5px;
		width: 65px;
		height: 20px;
		cursor: pointer;
		-webkit-transition: all linear .2s;
		-moz-transition: all linear .2s;
		-ms-transition: all linear .2s;
		-o-transition: all linear .2s;
		transition: all linear .2s;
		border: 2px dashed #E5E7E9;
	}

	.picker:hover {
		transform: scale(1.1);
	}

	#colorPick {
		z-index: 99999
	}

	@keyframes spin {
		0% {
			transform: rotateZ(0deg);
		}

		100% {
			transform: rotateZ(360deg);
		}
	}

	.btn_refresh .fa-spinner {
		animation: spin 1.5s linear infinite;
		color: #FFF;
	}
</style>
<script>
	var gravaCor = false;

	function corChamado(cod_chamado, cor) {
		if (!gravaCor) {
			return false;
		}

		if (cor == "#ffffff" || cor == "#FFFFFF") {
			cor = "";
		}
		console.log(cod_chamado + "/" + encodeURIComponent(cor));
		$.ajax({
			type: "POST",
			url: "ajxListaSuporte.do?opcao=cor&cod_chamado=" + cod_chamado + "&cor=" + encodeURIComponent(cor),
			beforeSend: function() {
				//$('#divId_sub').html('<div class="loading" style="width: 100%;"></div>');
			},
			success: function(data) {
				data = $.trim(data);
				$("#tr_" + cod_chamado).attr("style", "background:" + cor);
				console.log(data);
			},
			error: function() {
				$('#divId_sub').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Falha no processamento...</p>');
			}
		});
	}
</script>

<form name="formLista">

	<table class="table table-bordered table-striped table-hover table-sortable tablesorter">
		<thead>
			<tr>
				<th data-sorter="false" class="sorter-false"></th>
				<th><small>Chamado</small></th>
				<th class="sorter-text"><small>Empresa</small></th>
				<th><small>Cadastro</small></th>
				<th class="sorter-text"><small>Título</small></th>
				<th class="sorter-text"><small>Solicitante</small></th>
				<th class="sorter-text"><small>Consultor</small></th>
				<th class="sorter-text"><small>Solicitação</small></th>
				<th class="sorter-text"><small>Responsável</small></th>
				<th class="{ sorter: false }"><small>Prioridade</small></th>
				<th class="{ sorter: false }"><small>Status</small></th>
				<?php if ($manutencao) { ?>
					<th data-sorter="shortDate" data-date-format="ddmmyyyy"><small>Próx. Interação</small></th>
					<th data-sorter="shortDate" data-date-format="ddmmyyyy"><small>Atualizado</small></th>
					<th><small>Esforço</small></th>
					<th data-sorter="shortDate" data-date-format="ddmmyyyy"><small>Previsão (Entrega)</small></th>
					<th data-sorter="false"></th>
				<?php } ?>
			</tr>
		</thead>

		<tbody id="relatorioConteudo">

			<?php

			if ($dat_ini == "") {
				$ANDdatIni = " ";
			} else {
				$ANDdatIni = "AND DATE_FORMAT(SC.DAT_CADASTR, '%Y-%m-%d') >= '$dat_ini' ";
			}
			if ($dat_fim == "") {
				$ANDdatFim = " ";
			} else {
				$ANDdatFim = "AND DATE_FORMAT(SC.DAT_CADASTR, '%Y-%m-%d') <= '$dat_fim' ";
			}


			if (@$dat_ini_ent == date('Y-m-d')) {
				$ANDdatIniEnt = " ";
			} else {
				$ANDdatIniEnt = "AND DATE_FORMAT(SC.DAT_ENTREGA, '%Y-%m-%d') >= '$dat_ini_ent'";
			}

			if (@$dat_fim_ent == "") {
				$ANDdatFimEnt = " ";
			} else {
				$ANDdatFimEnt = "AND DATE_FORMAT(SC.DAT_ENTREGA, '%Y-%m-%d') <= '$dat_fim_ent'";
			}

			if (@$cod_externo == "") {
				$ANDcodExterno = " ";
			} else {
				$ANDcodExterno = "AND SC.COD_EXTERNO LIKE '%$cod_externo%' ";
			}

			if (@$cod_empresa == "") {
				$ANDcodEmpresa = " ";
			} else {
				$ANDcodEmpresa = "AND SC.COD_EMPRESA = $cod_empresa ";
			}

			if (@$nom_chamado == "") {
				$ANDnomChamado = " ";
			} else {
				$ANDnomChamado = "AND SC.NOM_CHAMADO LIKE '%$nom_chamado%' ";
			}

			if (@$cod_tpsolicitacao == "") {
				$ANDcodTipo = " ";
			} else {
				$ANDcodTipo = "AND SC.COD_TPSOLICITACAO = $cod_tpsolicitacao ";
			}

			if (@$cod_status == "") {
				$ANDcodStatus = "";
			} else {
				$ANDcodStatus = "AND SC.COD_STATUS = $cod_status ";
			}

			if (@$cod_status_exc == "0") {
				$ANDcodStatusExc = "";
			} else {
				$ANDcodStatusExc = "AND SC.COD_STATUS NOT IN($cod_status_exc) ";
			}

			if (@$cod_tipo_exc == "0") {
				$ANDcodTipoExc = "";
			} else {
				$ANDcodTipoExc = "AND SC.COD_TPSOLICITACAO NOT IN($cod_tipo_exc) ";
			}

			if (@$cod_integradora == "") {
				$ANDcodIntegradora = " ";
			} else {
				$ANDcodIntegradora = "AND SC.COD_INTEGRADORA = $cod_integradora ";
			}

			if (@$cod_plataforma == "") {
				$ANDcodPlataforma = " ";
			} else {
				$ANDcodPlataforma = "AND SC.COD_PLATAFORMA = $cod_plataforma ";
			}

			if (@$cod_versaointegra == "") {
				$ANDcodVersaointegra = " ";
			} else {
				$ANDcodStatus = "AND SC.COD_VERSAOINTEGRA = $cod_versaointegra ";
			}

			if (@$cod_prioridade == "") {
				$ANDcodPrioridade = " ";
			} else {
				$ANDcodPrioridade = "AND SC.COD_PRIORIDADE = $cod_prioridade ";
			}

			if (@$cod_usuario == "") {
				$ANDcodUsuario = " ";
			} else {
				$ANDcodUsuario = "AND SC.COD_USUARIO = $cod_usuario ";
			}

			if (@$cod_usures == "") {
				$ANDcod_usures = " ";
			} else {
				$ANDcod_usures = "AND SC.COD_USURES = $cod_usures ";
			}

			if (@$cod_chamado == "") {
				$ANDcodChamado = " ";
			} else {
				$ANDcodChamado = "AND SC.COD_CHAMADO = $cod_chamado ";
				$ANDcodStatusExc = "";
			}

			if (@$cod_usuario_ordenac == "") {
				$ANDcod_usuario_ordenac = " ";
			} else {
				$ANDcod_usuario_ordenac = "AND IFNULL(SC.COD_USUARIO_ORDENAC, 0) = $cod_usuario_ordenac ";
			}

			$sqlCount = "SELECT COUNT(0) QTD FROM SAC_CHAMADOS SC 
				WHERE 1=1
				$ANDdatIni
				$ANDdatFim
				$ANDcodExterno
				$ANDcodChamado
				$ANDcodEmpresa
				$ANDnomChamado
				$ANDcodStatus
				$ANDcodTipo
				$ANDcodIntegradora
				$ANDcodPlataforma
				$ANDcodVersaointegra
				$ANDcodPrioridade
				$ANDcod_usures
				$ANDcodUsuario
				$ANDcodStatusExc
				$ANDcodTipoExc
				$ANDdatIniEnt
				$ANDdatFimEnt
				$ANDcod_usuario_ordenac				
				ORDER BY SC.COD_PRIORIDADE ASC
				";
			//fnEscreve($sqlCount);

			$rs = mysqli_query($connAdmSAC->connAdm(), $sqlCount);
			$retorno = mysqli_fetch_array($rs);
			$total_itens_por_pagina = $retorno["QTD"];

			$numPaginas = ceil($total_itens_por_pagina / $itens_por_pagina);

			//variavel para calcular o início da visualização com base na página atual
			$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;
			$sqlSac = "SELECT 
			SC.COD_CHAMADO, 
			SC.COD_EMPRESA, 
			SC.NOM_CHAMADO, 
			SC.COD_EXTERNO, 
			SC.DAT_CADASTR, 
			SC.DAT_CHAMADO, 
			SC.DAT_ENTREGA, 
			SC.DES_PREVISAO, 
			SC.COD_USUARIO, 
			SC.DAT_PROXINT,
			SC.COD_USURES, 
			SC.COD_CONSULTORES AS CONSULTORES, 
			SC.LOG_ADM, 
			SP.DES_PLATAFORMA, 
			ST.DES_TPSOLICITACAO, 
			SC.COD_STATUS,
			SV.DES_VERSAOINTEGRA, 
			SPR.ABV_PRIORIDADE, 
			SPR.DES_COR AS COR_PRIORIDADE, 
			SPR.DES_ICONE AS ICO_PRIORIDADE,
			SS.ABV_STATUS, 
			SS.DES_COR AS COR_STATUS, 
			SS.DES_ICONE AS ICO_STATUS,
			(SELECT MAX(SCM.DAT_CADASTRO) 
			FROM SAC_COMENTARIO SCM 
			WHERE SCM.COD_CHAMADO = SC.COD_CHAMADO) AS DAT_INTERAC,
			SC.LOG_PITSTOP,
			SC.DES_COR AS COR_CHAMADO,
			SC.COD_USUARIO_ORDENAC,
			SC.DAT_INICIO
		FROM 
			SAC_CHAMADOS SC 
		LEFT JOIN 
			SAC_PLATAFORMA SP ON SP.COD_PLATAFORMA = SC.COD_PLATAFORMA
		LEFT JOIN 
			SAC_TPSOLICITACAO ST ON ST.COD_TPSOLICITACAO = SC.COD_TPSOLICITACAO
		LEFT JOIN 
			SAC_VERSAOINTEGRA SV ON SV.COD_VERSAOINTEGRA = SC.COD_VERSAOINTEGRA
		LEFT JOIN 
			SAC_PRIORIDADE SPR ON SPR.COD_PRIORIDADE = SC.COD_PRIORIDADE
		LEFT JOIN 
			SAC_STATUS SS ON SS.COD_STATUS = SC.COD_STATUS
		WHERE 
			1=1 
			" . (@$_POST['LOG_ESTEIRA'] == "S" ? " AND 1=2" : "") . "
			$ANDdatIni
			$ANDdatFim
			$ANDcodExterno
			$ANDcodChamado
			$ANDcodEmpresa
			$ANDnomChamado
			$ANDcodStatus
			$ANDcodTipo
			$ANDcodIntegradora
			$ANDcodPlataforma
			$ANDcodVersaointegra
			$ANDcodPrioridade
			$ANDcod_usures
			$ANDcodUsuario
			$ANDcodStatusExc
			$ANDcodTipoExc
			$ANDdatIniEnt
			$ANDdatFimEnt
			$ANDcod_usuario_ordenac
			" . (@$orderby <> "" ?
				"ORDER BY $orderby" :
				"ORDER BY SC.COD_CHAMADO DESC 
				LIMIT $inicio, $itens_por_pagina");

			//echo ("<pre>".$sqlSac."</pre>");

			//fnEscreve($sqlSac);

			$arrayQuerySac = mysqli_query($connAdmSAC->connAdm(), $sqlSac);

			$count = 0;
			$adm = "";
			$entrega = "";
			while ($qrSac = mysqli_fetch_assoc($arrayQuerySac)) {

				if ($qrSac['LOG_ADM'] == 'S') {
					$adm = "<i class='far fa-user shortCut' data-toggle='tooltip' data-placement='top' data-original-title='ti'></i>";
				} else {
					$adm = "<i class='far fa-suitcase shortCut' data-toggle='tooltip' data-placement='top' data-original-title='cliente'></i>";
				}

				$count++;

				$sqlEmpresa = "SELECT NOM_FANTASI FROM EMPRESAS WHERE COD_EMPRESA = $qrSac[COD_EMPRESA]";
				$qrNomEmp = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $sqlEmpresa));

				$sqlUsuarios = "SELECT 
				(SELECT NOM_USUARIO FROM USUARIOS WHERE COD_USUARIO = " . intval($qrSac['COD_USUARIO']) . " LIMIT 1) AS NOM_SOLICITANTE,
				(SELECT NOM_USUARIO FROM USUARIOS WHERE COD_USUARIO = " . intval($qrSac['COD_USURES']) . " LIMIT 1) AS NOM_RESPONSAVEL,
				(SELECT GROUP_CONCAT(NOM_USUARIO SEPARATOR ', ') 
				FROM USUARIOS 
				WHERE FIND_IN_SET(COD_USUARIO, '" . @$qrSac['CONSULTORES'] . "')) AS NOM_CONSULTORES";

				$qrNomUsu = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $sqlUsuarios));
				//fnEscreve($sqlUsuarios);	
				$arrConsultores = explode(',', rtrim($qrNomUsu['NOM_CONSULTORES'], ','));
				$consultorEnv = $arrConsultores[0];

				//$entrega = fnFormataDataEntregaSAC($qrSac);
				$entrega = "";

				if ($qrSac['DAT_PROXINT'] == "1969-12-31") {
					$proxInt = "";
				} else {
					$proxInt = fnDataShort($qrSac['DAT_PROXINT']);
					if (fnDatasql($proxInt) < fnDatasql($hoje)) {
						$proxInt = "<span class='text-danger'><b>" . fnDataShort($qrSac['DAT_PROXINT']) . "</b></span>";
					}
				}

				if ($qrSac['DAT_INTERAC'] != "") {
					if (fnDatasql($qrSac['DAT_INTERAC']) == fnDatasql($hoje)) {
						$atualizado = "<b>Hoje</b>";
						$f = "f17";
					} else if (fnDatasql($qrSac['DAT_INTERAC']) == date('Y-m-d', strtotime(' -1 days'))) {
						$atualizado = "<b>Ontem</b>";
						$f = "f17";
					} else {
						$atualizado = fnDataFull($qrSac['DAT_INTERAC']);
						$f = "f14";
					}
				} else {
					$atualizado = "";
				}

				if ($qrSac['COD_STATUS'] == 12) {

					$difference = fnValor((abs(strtotime(date("Y-m-d H:i:s")) - strtotime($qrSac['DAT_CADASTR'])) / 3600), 0);

					if ($difference <= 12) {
						$corDiff = "label-success";
					} else if ($difference > 12 && $difference <= 24) {
						$corDiff = "label-warning";
					} else {
						$corDiff = "label-danger";
					}

					$badgeDias = "<span class='label-as-badge text-center " . $corDiff . "'><span class='txtBadge'>" . $difference . "</span></span>";
				} else {
					$badgeDias = "";
				}

				if ($qrSac['DES_PREVISAO'] != "" && $qrSac['DES_PREVISAO'] != 0) {
					$esforco = fnValor($qrSac['DES_PREVISAO'], 2);
				} else {
					$esforco = "<span class='fal fa-plus' style='padding-top:10px;'></span>";
				}

				if (fnDataShort($qrSac['DAT_PROXINT']) == "31/12/1969" || fnDataShort($qrSac['DAT_PROXINT']) == "") {
					$interac = "<span class='fal fa-calendar' style='padding-top:10px;'></span>";;
				} else {
					$interac = fnDataShort($qrSac['DAT_PROXINT']);
				}

			?>

				<tr style="background:<?= $qrSac['COR_CHAMADO'] ?>" id="tr_<?= $qrSac['COD_CHAMADO'] ?>">
					<td></td>
					<td class="text-center">
						<small>
							<a id="cod_chamado_<?= $qrSac['COD_CHAMADO'] ?>" class="<?= ($qrSac['LOG_PITSTOP'] == "S" ? "pitstop" : "") ?>" href="action.php?mod=<?= fnEncode(1285); ?>&id=<?php echo fnEncode($qrSac['COD_EMPRESA']); ?>&idC=<?php echo fnEncode($qrSac['COD_CHAMADO']); ?>" target="_blank">
								<?= $qrSac['COD_CHAMADO'] ?>
								<?php /*
				&nbsp;<span class="far fa-external-link-square"></span>&nbsp;
				*/ ?>
							</a>
						</small>
					</td>
					<td><small><?= isset($qrNomEmp['NOM_FANTASI']) ? $qrNomEmp['NOM_FANTASI'] : null ?></small></td>
					<td class="text-center f14"><small><?= fnDataShort($qrSac['DAT_CADASTR']); ?></small></td>
					<td><small><?= $qrSac['NOM_CHAMADO'] ?></small></td>
					<td><small><?= $adm ?> <?= $qrNomUsu['NOM_SOLICITANTE'] ?> </small></td>
					<td><small><?= $consultorEnv ?> </small></td>
					<td><small><?= $qrSac['DES_TPSOLICITACAO'] ?></small></td>
					<td><small><?= $qrNomUsu['NOM_RESPONSAVEL'] ?></small></td>

					<td class="text-center">
						<small>
							<p class="label" style="background-color: <?php echo $qrSac['COR_PRIORIDADE'] ?>" data-toggle='tooltip' data-placement='top' data-original-title='<?= $qrSac['ABV_PRIORIDADE'] ?>'>
								<span class="<?php echo $qrSac['ICO_PRIORIDADE']; ?>" style="color: #FFF;"></span>
							</p>
						</small>
					</td>

					<td class="text-center">
						<small>
							<p class="label" style="background-color: <?php echo $qrSac['COR_STATUS'] ?>">
								<span class="<?php echo $qrSac['ICO_STATUS']; ?>" style="color: #FFF;"></span>
								&nbsp;<?php echo $qrSac['ABV_STATUS']; ?>
							</p>
							&nbsp;
							<?= $badgeDias ?>
						</small>

						<!-- <div><?= $badgeDias ?></div> -->
					</td>

					<?php if ($manutencao) { ?>

						<td class='text-center'>
							<a href="#" class="editable-data"
								data-type='date'
								data-title='Editar Interação'
								data-pk="<?= $qrSac['COD_CHAMADO'] ?>"
								data-name="DAT_PROXINT" style='border:none;'><small><?= $interac ?></small>

							</a>
						</td>

						<td class="text-center <?= $f ?>"><small><?= $atualizado ?></small></td>

						<td class="text-center vl">
							<small>
								<a href="#" class="editable"
									data-type='text'
									data-title='Editar Esforço'
									data-pk="<?= $qrSac['COD_CHAMADO'] ?>"
									data-name="DES_PREVISAO" style='border:none;'><?= $esforco ?>

								</a>
							</small>
						</td>

						<td class='text-center f14'>
							<?= "<small>" . ($qrSac["DAT_ENTREGA"] == "1969-12-31" ? "" : fnDataShort($qrSac["DAT_ENTREGA"])) . "</small>" ?>
						</td>

						<td class="text-center f14">
							<div class="col-sm-2 col-xs-4">
								<ul class="menu menu-down-left" data-menu data-menu-toggle="#menu-toggle_<?= $qrSac['COD_CHAMADO'] ?>">
									<li class="text-info">
										<a href="javascript:" onClick="pitStop(<?= $qrSac['COD_CHAMADO'] ?>)">Pit Stop</a>
									</li>
									<li class="text-info">
										<div class="picker" id="picker_<?= $qrSac['COD_CHAMADO'] ?>"></div>
									</li>
									<hr>
									<li class="text-info text-center dataMask">
										<p class="f12">Dt. Entrega</p>
										<small>
											<a href="#" class="editable"
												data-type='text'
												data-title='Editar Entrega'
												data-pk="<?= $qrSac["COD_CHAMADO"] ?>"
												data-name="DAT_ENTREGA" style='border:none;'><?= fnDataShort($entrega) ?>

											</a>
										</small>
									</li>
									<li class="text-info text-center dataMask">
										<p class="f12">Dt. Início</p>
										<small>
											<a href="#" class="editable"
												data-type='text'
												data-title='Editar Entrega'
												data-pk="<?= $qrSac['COD_CHAMADO'] ?>"
												data-name="DAT_INICIO" style='border:none;'><?= 'DD/MM/YYYY' ?>

											</a>
										</small>
									</li>
								</ul>
								<div class="row set1 dropleft">
									<a href="javascript:void(0)" class="col-xs-2" id="menu-toggle_<?= $qrSac['COD_CHAMADO'] ?>">
										<span class="fal fa-ellipsis-v fa-2x"></span>
									</a>

								</div>
							</div>
							<script>
								$("#picker_<?= $qrSac['COD_CHAMADO'] ?>").colorPick({
									'initialColor': '<?= $qrSac['COR_CHAMADO'] ?>',
									'palette': ["#FDEDEC", "#F5B7B1", "#EBF5FB", "#A9CCE3", "#FEF9E7", "#FCF3CF", "#EAFAF1", "#ABEBC6", "#ffffff"],
									'onColorSelected': function() {

										this.element.css({
											'backgroundColor': this.color,
											'color': this.color
										});
										corChamado(<?= $qrSac['COD_CHAMADO'] ?>, this.color);
									}
								});
								//'palette': ["#1abc9c", "#16a085", "#2ecc71", "#27ae60", "#3498db", "#2980b9", "#9b59b6", "#8e44ad", "#34495e", "#2c3e50", "#f1c40f", "#f39c12", "#e67e22", "#d35400", "#e74c3c", "#c0392b", "#ecf0f1", "#ffffff"],
							</script>
						</td>

					<?php } ?>
				</tr>
			<?php
			}

			?>

		</tbody>
		<tfoot>
			<tr>
				<th id="resultados_paginacao" class="{ sorter: false }" colspan="100">
					<center><small style="font-weight: normal;">Resultados: <b><span id="ini_cont"><?= $inicio ?></span></b> a <b><span id="fim_cont"><?= ($total_itens_por_pagina < ($itens_por_pagina + $inicio) ? $total_itens_por_pagina : ($itens_por_pagina + $inicio)) ?></span></b> de <b><?= $total_itens_por_pagina ?></b> registros.</small></center>
				</th>
			</tr>
			<tr>
				<th class="" colspan="100">
					<center>
						<ul id="paginacao" class="pagination-sm"></ul>
					</center>
				</th>
			</tr>
			<tr>
				<th colspan="100">
					<a class="btn btn-info btn-sm exportarCSV"> <i class="fa fa-file-excel" aria-hidden="true"></i>&nbsp; Exportar</a> &nbsp;&nbsp;
					<a class="btn btn-info btn-sm" href="action.php?mod=<?php echo fnEncode(1789); ?>" target="_blank"> <i class="fa fa-chart-bar" aria-hidden="true"></i>&nbsp; Dashboard</a>
				</th>
			</tr>
		</tfoot>
	</table>



</form>


<link rel="stylesheet" href="js/plugins/menu-dropdown/menu.min.css" />
<script type="text/javascript" src="js/plugins/menu-dropdown/menu.min.js"></script>

<script>
	$(document).ready(function() {
		gravaCor = true;

		$(function() {
			$('[data-toggle="tooltip"]').tooltip();
		});

		$('.menu').menu({
			position: {
				my: "left top",
				at: "right-5 top+5"
			}
		});

		$(function() {
			// MASCARA NO INPUT DO CAMPO EDITÁVEL
			// INICIALIZANDO O PLUGIN EDITAVEL COM A GLOBAL POPUP
			$('.vl .editable-input .input-sm').mask('000.000.000.000.000,00', {
				reverse: true
			});
			$('.dataMask .editable-input .input-sm').mask("99/99/9999", {
				reverse: true
			});
			$.fn.editable.defaults.mode = 'popup';

			// LOCALIZANDO O CALENDÁRIO DO EDITÁVEL
			$.fn.bdatepicker.dates['pt-br'] = {
				days: ["Domingo", "Segunda", "Terça", "Quarta", "Quinta", "Sexta", "Sábado", "Domingo"],
				daysShort: ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sab", "Dom"],
				daysMin: ["D", "S", "T", "Q", "Q", "S", "S", "D"],
				months: ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"],
				monthsShort: ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez"],
				today: "Hoje",
				clear: "Limpar",
				weekStart: 0
			};

		});

		$('.menu').menu({
			position: {
				my: "left top",
				at: "right-5 top+5"
			}
		});

		$(function() {
			$('.editable').editable({
				emptytext: "<span class='fal fa-plus'></span>",
				url: 'ajxListaSuporteEdit.do',
				ajaxOptions: {
					type: 'post'
				},
				params: function(params) {
					// params.codempresa = $(this).data('codempresa');
					return params;
				},
				success: function(data) {
					atualizaEsteira();
					console.log(data);
				}
			});
		});

		$(function() {
			$('.editable-data').editable({
				emptytext: "<span class='fal fa-calendar'></span>",
				placement: 'bottom',
				datepicker: {
					language: 'pt-br',
					weekStart: 0
				},
				viewformat: 'dd/mm/yyyy',
				url: 'ajxListaSuporteEdit.do',
				ajaxOptions: {
					type: 'post'
				},
				params: function(params) {
					// params.count = $(this).data('count');
					return params;
				},
				success: function(data) {
					atualizaEsteira();
					console.log(data);
				}
			});
		});
	})


	function pitStop(cod_chamado) {
		$.ajax({
			type: "POST",
			url: "ajxListaSuporte.do?opcao=pitstop&cod_chamado=" + cod_chamado,
			beforeSend: function() {
				//$('#divId_sub').html('<div class="loading" style="width: 100%;"></div>');
			},
			success: function(data) {
				data = $.trim(data);
				console.log(data);
				if (data == "S") {
					$("#cod_chamado_" + cod_chamado).addClass("pitstop");
				} else {
					$("#cod_chamado_" + cod_chamado).removeClass("pitstop");
				}
			},
			error: function() {
				$('#divId_sub').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> Falha no processamento...</p>');
			}
		});
	}



	function reloadPage(idPage, loading) {
		loading = (loading == undefined ? true : loading);
		gravaCor = false;
		var esteira = $("#LOG_ESTEIRA").is(":checked");
		var itens_por_pagina = 0;

		if (!esteira) {
			itens_por_pagina = "<?php echo $itens_por_pagina; ?>";
			$(".bot_nav_esteira").hide();
		} else {
			$(".bot_nav_esteira").show();
		}

		$.ajax({
			type: "POST",
			url: "ajxListaSuporte.do?opcao=paginar&idPage=" + idPage + "&itens_por_pagina=" + itens_por_pagina + "&esteira=" + esteira,
			data: $('#formulario').serialize(),
			beforeSend: function() {
				if (loading) {
					$('#relatorioConteudo').html('<div class="loading" style="width: 100%;"></div>');
				}
				$(".btn_refresh").html("<a class='fas fa-spinner'></a>");
			},
			success: function(data) {
				$("#relatorioConteudo").html(data);
				$(".tablesorter").trigger("updateAll");
				carregaContador(idPage);
				contador();
				gravaCor = true;
			},
			error: function() {
				$('#relatorioConteudo').html('<p class="error" style="margin-top: 10px;"><strong>Oops!</strong> itens_por_pagina não encontrados...</p>');
				gravaCor = true;
			}
		});
	}

	function carregaContador(idPage) {
		var esteira = $("#LOG_ESTEIRA").is(":checked");
		if (!esteira) {
			$("#paginacao").show();
			$("#resultados_paginacao").show();
		} else {
			$('.tablesorter').trigger('sortReset');
			$("#paginacao").hide();
			$("#resultados_paginacao").hide();
			$(".tablesorter-headerRow th").removeClass();
		}

		total_itens_por_pagina = <?= $total_itens_por_pagina ?>;
		itens_por_pagina = <?= $itens_por_pagina ?>;
		inicio = (itens_por_pagina * idPage) - itens_por_pagina;
		if (total_itens_por_pagina < (itens_por_pagina + inicio)) {
			fim = total_itens_por_pagina;
		} else {
			fim = (itens_por_pagina + inicio);
		}
		$("#ini_cont").text(inicio);
		$("#fim_cont").text(fim);
	}

	$(".exportarCSV").click(function() {
		$.confirm({
			title: 'Exportação',
			content: '' +
				'<form action="" class="formName">' +
				'<div class="form-group">' +
				'<label>Insira o nome do arquivo:</label>' +
				'<input type="text" placeholder="Nome" class="nome form-control" required />' +
				'</div>' +
				'</form>',
			buttons: {
				formSubmit: {
					text: 'Gerar',
					btnClass: 'btn-blue',
					action: function() {
						var nome = this.$content.find('.nome').val();
						if (!nome) {
							$.alert('Por favor, insira um nome');
							return false;
						}

						$.confirm({
							title: 'Mensagem',
							type: 'green',
							icon: 'fa fa-check-square-o',
							content: function() {
								var self = this;
								return $.ajax({
									url: "ajxRelSuporte.do?opcao=exportar&nomeRel=" + nome,
									data: $('#formulario').serialize(),
									method: 'POST'
								}).done(function(response) {
									self.setContentAppend('<div>Exportação realizada com sucesso.</div>');
									var fileName = '0_' + nome + '.csv';
									SaveToDisk('media/excel/' + fileName, fileName);
									console.log(response);
								}).fail(function() {
									self.setContentAppend('<div>Erro ao realizar o procedimento!</div>');
								});
							},
							buttons: {
								fechar: function() {
									//close
								}
							}
						});
					}
				},
				cancelar: function() {
					//close
				},
			}
		});
	});

	function abreDetail(idBloco) {
		var idItem = $('.abreDetail_' + idBloco);
		var c = ($('#bloco_' + idBloco).find($(".fa")).attr("class"));
		if (c == "fa fa-angle-down") {
			idItem.hide();
			$('#bloco_' + idBloco).find($(".fa")).removeClass('fa-angle-down').addClass('fa-angle-right');
		} else {
			idItem.show();
			$('#bloco_' + idBloco).find($(".fa")).removeClass('fa-angle-right').addClass('fa-angle-down');
		}
	}

	function contador() {
		$('table tr').each(function(index) {
			if ($(this).attr("cod_usu") != undefined) {
				cod_usu = $(this).attr("cod_usu");
				$(this).find(".contador").html($(".abreDetail_" + cod_usu).length);
			}
		});
	}

	function abreDetailTodos(bool) {
		$('tr.trDetail').each(function(index) {
			if ($(this).attr("cod_usu") != undefined) {
				idBloco = this.id;
				cod_usu = $(this).attr("cod_usu");
				var icon = $.trim($("#" + idBloco + " .icon-angle i").attr("class"));
				if ((bool == true && icon == "fa fa-angle-right") || (bool == false && icon == "fa fa-angle-down")) {
					console.log(cod_usu);
					abreDetail(cod_usu);
				}
			}
		});
	}

	function atualizaEsteira() {
		$(".btn_refresh").show();
	}
</script>