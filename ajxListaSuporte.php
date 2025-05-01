<?php

include '_system/_functionsMain.php';

$opcao = $_GET['opcao'];
$itens_por_pagina = $_GET['itens_por_pagina'];
$pagina = $_GET['idPage'];
$esteira = (@$_GET['esteira'] == "true");

$dat_ini = fnDataSql($_POST['DAT_INI']);
$dat_fim = fnDataSql($_POST['DAT_FIM']);
$dat_ini_ent = fnDataSql($_POST['DAT_INI_ENT']);
$dat_fim_ent = fnDataSql($_POST['DAT_FIM_ENT']);
$cod_externo = $_POST['COD_EXTERNO'];
$cod_empresa = $_POST['COD_EMPRESA'];
$nom_chamado = $_POST['NOM_CHAMADO'];
$cod_chamado = $_POST['COD_CHAMADO'];
$cod_usuario = $_POST['COD_USUARIO'];

$cod_tpsolicitacao = $_POST['COD_TPSOLICITACAO'];
$cod_status = $_POST['COD_STATUS'];
$cod_integradora = $_POST['COD_INTEGRADORA'];
$cod_plataforma = $_POST['COD_PLATAFORMA'];
$cod_versaointegra = $_POST['COD_VERSAOINTEGRA'];
$cod_prioridade = $_POST['COD_PRIORIDADE'];
$cod_usures = $_POST['COD_USURES'];



if ($opcao == "pitstop") {
	$cod_chamado = @$_GET['cod_chamado'];

	$sql = "SELECT LOG_PITSTOP FROM SAC_CHAMADOS WHERE COD_CHAMADO = 0" . $cod_chamado;
	$rs = mysqli_query($connAdmSAC->connAdm(), $sql);
	$retorno = mysqli_fetch_array($rs);
	$pitstop = ($retorno["LOG_PITSTOP"] == "S" ? "N" : "S");

	$sql = "UPDATE SAC_CHAMADOS SET LOG_PITSTOP = '$pitstop' WHERE COD_CHAMADO = 0" . $cod_chamado;
	$rs = mysqli_query($connAdmSAC->connAdm(), $sql);

	echo $pitstop;
} elseif ($opcao == "cor") {

	$cod_chamado = @$_GET['cod_chamado'];
	$cor = @$_GET['cor'];

	$sql = "UPDATE SAC_CHAMADOS SET DES_COR = '$cor' WHERE COD_CHAMADO = 0" . $cod_chamado;
	$rs = mysqli_query($connAdmSAC->connAdm(), $sql);

	echo $cor;
} elseif ($opcao == "paginar") {
	if (isset($_POST['COD_STATUS_EXC'])) {
		$Arr_COD_STATUS_EXC = $_POST['COD_STATUS_EXC'];
		$cod_status_exc = "";

		for ($i = 0; $i < count($Arr_COD_STATUS_EXC); $i++) {
			$cod_status_exc = $cod_status_exc . $Arr_COD_STATUS_EXC[$i] . ",";
		}

		$cod_status_exc = rtrim($cod_status_exc, ',');
	} else {
		$cod_status_exc = "0";
	}

	if (isset($_POST['COD_TIPO_EXC'])) {
		$Arr_COD_TIPO_EXC = $_POST['COD_TIPO_EXC'];
		$cod_tipo_exc = "";

		for ($i = 0; $i < count($Arr_COD_TIPO_EXC); $i++) {
			$cod_tipo_exc = $cod_tipo_exc . $Arr_COD_TIPO_EXC[$i] . ",";
		}

		$cod_tipo_exc = rtrim($cod_tipo_exc, ',');
	} else {
		$cod_tipo_exc = "0";
	}

	$hoje = fnFormatDate(date("Y-m-d"));


	// fnEscreve($cod_status_exc);


	if ($dat_ini == "") {
		$ANDdatIni = " ";
	} else {
		$ANDdatIni = "AND DATE_FORMAT(SC.DAT_CADASTR, '%Y-%m-%d') >= '$dat_ini' ";
	}

	if ($dat_ini_ent == "") {
		$ANDdatIniEnt = " ";
	} else {
		$ANDdatIniEnt = "AND DATE_FORMAT(SC.DAT_ENTREGA, '%Y-%m-%d') >= '$dat_ini_ent'";
	}

	if ($dat_fim_ent == "") {
		$ANDdatFimEnt = " ";
	} else {
		$ANDdatFimEnt = "AND DATE_FORMAT(SC.DAT_ENTREGA, '%Y-%m-%d') <= '$dat_fim_ent'";
	}

	if ($cod_externo == "") {
		$ANDcodExterno = " ";
	} else {
		$ANDcodExterno = "AND SC.COD_EXTERNO LIKE '%$cod_externo%' ";
	}

	if ($cod_empresa == "") {
		$ANDcodEmpresa = " ";
	} else {
		$ANDcodEmpresa = "AND SC.COD_EMPRESA = $cod_empresa ";
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
		$ANDcodStatus = "";
	} else {
		$ANDcodStatus = "AND SC.COD_STATUS = $cod_status ";
	}

	if ($cod_status_exc == "0") {
		$ANDcodStatusExc = "";
	} else {
		$ANDcodStatusExc = "AND SC.COD_STATUS NOT IN($cod_status_exc) ";
	}

	if ($cod_tipo_exc == "0") {
		$ANDcodTipoExc = "";
	} else {
		$ANDcodTipoExc = "AND SC.COD_TPSOLICITACAO NOT IN($cod_tipo_exc) ";
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

	if ($cod_usuario == "") {
		$ANDcodUsuario = " ";
	} else {
		$ANDcodUsuario = "AND SC.COD_USUARIO = $cod_usuario ";
	}

	if ($cod_usures == "") {
		$ANDcod_usures = " ";
	} else {
		$ANDcod_usures = "AND SC.COD_USURES = $cod_usures ";
	}

	if ($cod_chamado == "") {
		$ANDcodChamado = " ";
	} else {
		$ANDcodChamado = "AND SC.COD_CHAMADO = $cod_chamado ";
		$ANDcodStatusExc = "";
	}

	if ($itens_por_pagina > 0) {
		$sqlCount = "SELECT COUNT(0) QTD FROM SAC_CHAMADOS SC 
									WHERE
									DATE_FORMAT(SC.DAT_CADASTR, '%Y-%m-%d') <= '$dat_fim'
									$ANDdatIni
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
									ORDER BY SC.COD_PRIORIDADE ASC
									";
		// fnEscreve($sqlCount);

		$rs = mysqli_query($connAdmSAC->connAdm(), $sqlCount);
		$retorno = mysqli_fetch_array($rs);
		$total_itens_por_pagina = $retorno["QTD"];

		$numPaginas = ceil($total_itens_por_pagina / $itens_por_pagina);

		//variavel para calcular o início da visualização com base na página atual
		$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;
	}

	if ($esteira) {
		$sql = "(SELECT COD_USUARIO, NOM_USUARIO, HOR_ENTRADA, HOR_DEVDIAS, HOR_DEVFDS from usuarios 
						where (usuarios.COD_EMPRESA = 2 OR usuarios.COD_EMPRESA = 3)
						and usuarios.DAT_EXCLUSA is null 
						AND LOG_USUDEV = 'S'
						AND LOG_ESTATUS = 'S' order by  usuarios.NOM_USUARIO)					
						UNION					
						(SELECT 0 COD_USUARIO,'' NOM_USUARIO, '00:00:00' HOR_ENTRADA, 0 HOR_DEVDIAS, 0 HOR_DEVFDS)
						";
	} else {
		$sql = "SELECT 0 COD_USUARIO,'' NOM_USUARIO";
	}
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	while ($qrUser = mysqli_fetch_assoc($arrayQuery)) {

		$ANDusuOrdem = ($esteira ? "AND IFNULL(SC.COD_USUARIO_ORDENAC,0) = 0" . $qrUser["COD_USUARIO"] : " ");
		//;}else{$ANDcodPrioridade = "AND SC.COD_PRIORIDADE = $cod_prioridade ";}

		// fnEscreve($ANDusuOrdem);

		if ($esteira) {
			echo "<tr cod_usu=" . $qrUser["COD_USUARIO"] . " class='trDetail' id='bloco_" . $qrUser["COD_USUARIO"] . "'>";
			echo "<td colspan='100' class='borda'>";
			echo "<div class='col-sm-11'>";

			echo "<a href='javascript:void(0);' onclick='abreDetail(" . $qrUser["COD_USUARIO"] . ")' class='icon-angle' style='padding:10px;'>";
			echo "<i class='fa fa-angle-down' aria-hidden='true'></i>";
			echo "</a>";

			echo ($qrUser["COD_USUARIO"] > 0 ? "<b>" . $qrUser["NOM_USUARIO"] . "</b>" : "N&atilde;o distribu&iacute;dos");
			echo " &nbsp; <span class='notify-badge text-center contador'>0</span>";

			echo "</div>";

			echo "<a class='btn btn-xs btn-default btn_refresh' style='float: right;display:none;' href='javascript:' onclick='reloadPage(1,false);'><i class='fal fa-history'></i></a>";

			echo "</td>";
			echo "</tr>";
		}


		$sqlSac = "SELECT SC.COD_CHAMADO, SC.COD_EMPRESA, SC.NOM_CHAMADO, SC.COD_EXTERNO, 
									SC.DAT_CADASTR, SC.DAT_CHAMADO, SC.DAT_ENTREGA, SC.DES_PREVISAO, SC.COD_USUARIO, SC.DAT_PROXINT,
									SC.COD_USURES, SC.COD_CONSULTORES AS CONSULTORES, SC.LOG_ADM, SP.DES_PLATAFORMA, ST.DES_TPSOLICITACAO, SC.COD_STATUS,
									SV.DES_VERSAOINTEGRA, SPR.ABV_PRIORIDADE, SPR.DES_COR AS COR_PRIORIDADE, SPR.DES_ICONE AS ICO_PRIORIDADE,
									SS.ABV_STATUS, SS.DES_COR AS COR_STATUS, SS.DES_ICONE AS ICO_STATUS,
									(SELECT MAX(SCM.DAT_CADASTRO) FROM SAC_COMENTARIO SCM WHERE SCM.COD_CHAMADO = SC.COD_CHAMADO) AS DAT_INTERAC,
									SC.LOG_PITSTOP,SC.DES_COR AS COR_CHAMADO,SC.COD_USUARIO_ORDENAC,SC.DAT_INICIO
									FROM SAC_CHAMADOS SC 
									LEFT JOIN SAC_PLATAFORMA SP ON SP.COD_PLATAFORMA=SC.COD_PLATAFORMA
									LEFT JOIN SAC_TPSOLICITACAO ST ON ST.COD_TPSOLICITACAO=SC.COD_TPSOLICITACAO
									LEFT JOIN SAC_VERSAOINTEGRA SV ON SV.COD_VERSAOINTEGRA=SC.COD_VERSAOINTEGRA
									LEFT JOIN SAC_PRIORIDADE SPR ON SPR.COD_PRIORIDADE=SC.COD_PRIORIDADE
									LEFT JOIN SAC_STATUS SS ON SS.COD_STATUS=SC.COD_STATUS
									WHERE 
									DATE_FORMAT(SC.DAT_CADASTR, '%Y-%m-%d') <= '$dat_fim'
									$ANDdatIni
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
									$ANDusuOrdem"
			. ($itens_por_pagina > 0 ?
				" ORDER BY SC.COD_CHAMADO DESC LIMIT $inicio,$itens_por_pagina" :
				" ORDER BY SC.NUM_ORDENAC,SC.COD_CHAMADO DESC "
			);
		// fnEscreve($sqlSac);

		$arrayQuerySac = mysqli_query($connAdmSAC->connAdm(), $sqlSac);

		$count = 0;
		$adm = "";
		$entrega = "";
		$prev_inicio = "";
		while ($qrSac = mysqli_fetch_assoc($arrayQuerySac)) {
			// FNeSCREVE('TESTE');
			if ($qrSac['LOG_ADM'] == 'S') {
				$adm = "<i class='far fa-user shortCut' data-toggle='tooltip' data-placement='top' data-original-title='ti'></i>";
			} else {
				$adm = "<i class='far fa-suitcase shortCut' data-toggle='tooltip' data-placement='top' data-original-title='cliente'></i>";
			}

			$count++;

			$sqlEmpresa = "SELECT NOM_FANTASI FROM EMPRESAS WHERE COD_EMPRESA = $qrSac[COD_EMPRESA]";
			$qrNomEmp = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $sqlEmpresa));

			$sqlUsuarios = "SELECT (SELECT NOM_USUARIO FROM USUARIOS WHERE COD_USUARIO = $qrSac[COD_USUARIO]) AS NOM_SOLICITANTE,
													(SELECT NOM_USUARIO FROM USUARIOS WHERE COD_USUARIO = $qrSac[COD_USURES]) AS NOM_RESPONSAVEL,
													(SELECT CONCAT(NOM_USUARIO, ',') FROM USUARIOS WHERE COD_USUARIO IN($qrSac[CONSULTORES])) AS NOM_CONSULTORES";
			$qrNomUsu = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $sqlUsuarios));
			//fnEscreve($sqlUsuarios);	
			$arrConsultores = explode(',', rtrim($qrNomUsu['NOM_CONSULTORES'], ','));
			$consultorEnv = $arrConsultores[0];

			//$entrega = fnFormataDataEntregaSAC($qrSac);

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

			<tr style="background:<?= $qrSac['COR_CHAMADO'] ?>" id="tr_<?= $qrSac['COD_CHAMADO'] ?>" tr_usuario="true" class="abreDetail_<?= $qrUser["COD_USUARIO"] ?>">
				<td align="center"><?= ($esteira ? "<span class=\"fal fa-arrows grabbable\" data-id=\"" . $qrSac["COD_CHAMADO"] . "\"></span>" : "") ?></td>
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
				<td><small>&nbsp; <?= @$qrNomEmp['NOM_FANTASI'] ?></small></td>
				<td class="text-center f14"><small><?= fnDataShort($qrSac['DAT_CADASTR']); ?></small></td>
				<td><small><?= @$qrSac['NOM_CHAMADO'] ?></small></td>
				<td><small><?= $adm ?> <?= @$qrNomUsu['NOM_SOLICITANTE'] ?></small></td>
				<td><small><?= $consultorEnv ?></small></td>
				<td><small><?= @$qrSac['DES_TPSOLICITACAO'] ?></small></td>
				<td><small><?= @$qrNomUsu['NOM_RESPONSAVEL'] ?></small></td>

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


				</td>

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
					<?php
					if ($esteira) {
						$qrSac["INICIO"] = ($qrSac["DAT_INICIO"] <> "" ? $qrSac["DAT_INICIO"] : $prev_inicio);
						$dh = fnPrevisaoSAC($esteira, @$qrUser, @$qrSac);
						echo "<script>";
						echo "console.log(\"DataHora=" . $dh . "; Inicico=" . $qrSac["DAT_INICIO"] . "; Horas Semana=" . $qrUser["HOR_DEVDIAS"] . "\");";
						echo "</script>";
						if ($dh <> "") {
							$prev_inicio = $dh;
						}
						echo "<small>" . fnDataShort($dh) . "</small>";
					} else {
						echo "<small>" . ($qrSac["DAT_ENTREGA"] == "1969-12-31" ? "" : fnDataShort($qrSac["DAT_ENTREGA"])) . "</small>";
					}
					?>
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
										data-pk="<?= $qrSac['COD_CHAMADO'] ?>"
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
							'palette': ["#F5B7B1", "#EC7063", "#A9CCE3", "#5499C7", "#FCF3CF", "#F4D03F", "#ABEBC6", "#58D68D", "#ffffff"],
							'onColorSelected': function() {

								this.element.css({
									'backgroundColor': this.color,
									'color': this.color
								});
								corChamado(<?= $qrSac['COD_CHAMADO'] ?>, this.color);
							}
						});
					</script>
				</td>
			</tr>
	<?php
		}
	}


	?>
	<script type="text/javascript">
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
	</script>
<?php
}
