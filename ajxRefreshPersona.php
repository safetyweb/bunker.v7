<?php include "_system/_functionsMain.php";

//echo fnDebug('true');

$cod_empresa = fnLimpaCampo(fnDecode($_REQUEST['COD_EMPRESA']));
$CarregaMaster = fnLimpaCampoZero($_REQUEST['MASTER']);
$log_ativo = fnLimpacampo($_REQUEST['LOG_ATIVO']);
$log_personasusu = fnLimpacampo($_REQUEST['LOG_PERSONASUSU']);
if (isset($_GET['opcao'])) {
	$opcao = fnLimpacampo($_GET['opcao']);
} else {
	$opcao = "";
}

$modsAutorizados = $_REQUEST['modsAutorizados'];

switch ($opcao) {

	case 'EXC':

		$cod_persona = fnLimpaCampoZero(fnDecode($_REQUEST['COD_PERSONA']));
		$cod_usucada = $_SESSION['SYS_COD_USUARIO'];

		$sql = "CALL SP_EXCLUI_PERSONA($cod_persona, $cod_empresa, $cod_usucada, 'EXC')";

		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

		break;

	default:

		if ($log_ativo == "S") {
			$ativa = "N";
			$txtAtiva = "Desativar";
			$paramAtiva = "";
		} else {
			$ativa = "S";
			$txtAtiva = "Ativar";
			$paramAtiva = "Arquivadas";
		}


		// ROTINA DE ATIVAÇÃO/DESATIVAÇÃO DA PERSONA VIA BOTÃO DO MENU DE AÇÕES
		$cod_persona = fnLimpaCampoZero(fnDecode($_REQUEST['COD_PERSONA']));

		if ($cod_persona != 0) {
			$sqlUpdt = "UPDATE PERSONA SET LOG_ATIVO = '$ativa'
						WHERE COD_EMPRESA = $cod_empresa
						AND COD_PERSONA = $cod_persona";
			// fnEscreve($sqlUpdt);
			mysqli_query(connTemp($cod_empresa, ''), $sqlUpdt);
		}


		//fnEscreve($buscaAjx1);
		//fnEscreve($cod_empresa);

		$ARRAY_UNIDADE1 = array(
			'sql' => "select COD_UNIVEND,cod_empresa,nom_fantasi,NOM_UNIVEND from unidadevenda where cod_empresa=$cod_empresa  and cod_exclusa=0",
			'cod_empresa' => $cod_empresa,
			'conntadm' => $connAdm->connAdm(),
			'IN' => 'N',
			'nomecampo' => '',
			'conntemp' => '',
			'SQLIN' => ""
		);
		$ARRAY_UNIDADE = fnUnivend($ARRAY_UNIDADE1);

		$ARRAY_VENDEDOR1 = array(
			'sql' => "select COD_USUARIO,NOM_USUARIO from usuarios where cod_empresa in($cod_empresa,3)",
			'cod_empresa' => $cod_empresa,
			'conntadm' => $connAdm->connAdm(),
			'IN' => 'N',
			'nomecampo' => '',
			'conntemp' => '',
			'SQLIN' => ""
		);
		$ARRAY_VENDEDOR = fnUniVENDEDOR($ARRAY_VENDEDOR1);

		$arrayAutorizado = explode(",", $_SESSION["SYS_COD_UNIVEND"]);

		$sql = "CALL SP_BUSCA_PERSONA($cod_empresa, '$log_ativo');";

		// fnEscreve($sql);
		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);


		if (fnAutMaster($_SESSION["SYS_COD_TPUSUARIO"], $_SESSION["SYS_COD_EMPRESA"]) == '1') {
			$CarregaMaster = '1';
		} else {
			$CarregaMaster = '0';
		}
		$count = 0;
		while ($qrListaPersonas = mysqli_fetch_assoc($arrayQuery)) {

			$count++;
			$publico = $qrListaPersonas['LOG_PUBLICO'];
			$NOM_ARRAY_NON_VENDEDOR = (array_search($qrListaPersonas['COD_USUCADA'], array_column($ARRAY_VENDEDOR, 'COD_USUARIO')));

			if ($qrListaPersonas['LOG_CONGELA'] == "S") {
				$personaCongela = "<i class='far fa-pause-circle' aria-hidden='true'></i>";
			} else {
				$personaCongela = "";
			}

			if ($log_ativo == "S") {
				$personaaAtivo = "<i class='fal fa-check' aria-hidden='true'></i>";
			} else {
				$personaaAtivo = "";
			}

			if ($qrListaPersonas['LOG_RESTRITO'] == "S") {
				$personaaAtualiza = "<i class='fal fa-check' aria-hidden='true'></i>";
			} else {
				$personaaAtualiza = "";
			}
			//echo fnAutMaster($_SESSION["SYS_COD_TPUSUARIO"],$_SESSION["SYS_COD_EMPRESA"]);
			//$qrListaPersonas['COD_UNIVED']

			$lojaLoop = $qrListaPersonas['COD_UNIVEND'];
			if ($lojaLoop == 9999) {
				$nomeLoja = "Todas";
			} else {
				$NOM_ARRAY_UNIDADE = (array_search($qrListaPersonas['COD_UNIVEND'], array_column($ARRAY_UNIDADE, 'COD_UNIVEND')));
				$nomeLoja = $ARRAY_UNIDADE[$NOM_ARRAY_UNIDADE]['nom_fantasi'];
			}

			if ($qrListaPersonas['COD_USUCADA'] == 0 || $qrListaPersonas['COD_USUCADA'] == '') {
				$usuario = "";
			} else {
				$usuario = $ARRAY_VENDEDOR[$NOM_ARRAY_NON_VENDEDOR]['NOM_USUARIO'];
			}

			if ($log_personasusu == 'S') {
				if ($_SESSION['SYS_COD_USUARIO'] != $qrListaPersonas['COD_USUCADA']) {
					continue;
				}
			}

			if ($CarregaMaster == '1' || $publico == 'S') {

				if ($publico == 'S') {
					$nomeLoja = "Pública";
				}


?>
				<tr>
					<td><a class='btn btn-xs btn-info' style="border:0; padding: 3px 5px;  background-color: #<?php echo $qrListaPersonas['DES_COR'] ?>; color: #fff;"><i class="<?php echo $qrListaPersonas['DES_ICONE']; ?>" aria-hidden="true"></i></a></td>
					<td><?php echo $qrListaPersonas['DES_PERSONA']; ?></td>
					<td class="text-center"><?php echo fnValor($qrListaPersonas['TOTALCLI'], 0); ?></td>
					<td class="text-center"><small><?php echo $nomeLoja; ?></small></td>
					<td class="text-center"><small><?php echo $usuario; ?></small></td>
					<td class='text-center'><?php echo $personaaAtivo; ?></td>
					<td class='text-center'><?php echo $personaaAtualiza . "&nbsp;" . $personaCongela; ?></td>
					<td><?php echo fnDataFull($qrListaPersonas['DAT_CADASTR']); ?></td>
					<td><?php echo fnDataFull($qrListaPersonas['DAT_ALTERAC']); ?></td>
					<?php if (fnControlaAcesso("1600", $modsAutorizados) === false && $qrListaCampanha['LOG_RESTRITO'] == 'S') { ?>
						<td></td>
					<?php } else { ?>
						<td class="text-center">
							<small>
								<div class="btn-group dropdown dropleft">
									<button type="button" class="btn btn-info btn-xs dropdown-toggle transparency" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										ações &nbsp;
										<span class="fas fa-caret-down"></span>
									</button>
									<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
										<li><a href='javascript:void(0)' class='addBox' data-url="action.do?mod=<?php echo fnEncode(1038) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idx=<?php echo fnEncode($qrListaPersonas['COD_PERSONA']) ?>&pop=true" data-title="Persona / <?php echo $qrListaPersonas['DES_PERSONA']; ?>">Editar</a></li>
										<li><a href="action.do?mod=<?php echo fnEncode(1035) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idx=<?php echo fnEncode($qrListaPersonas['COD_PERSONA']) ?>">Acessar</a></li>
										<li class="divider"></li>
										<li><a href="javascript:void(0)" onclick='RefreshPersona("<?= fnEncode($cod_empresa) ?>","<?= $paramAtiva ?>","<?= fnEncode($qrListaPersonas['COD_PERSONA']) ?>")'><?= $txtAtiva ?></a></li>
										<li><a href="javascript:void(0)" onclick='excluiPersona("<?= fnEncode($cod_empresa) ?>","<?= fnEncode($qrListaPersonas['COD_PERSONA']) ?>","<?= $qrListaPersonas['qtd_campanha'] ?>","<?= $qrListaPersonas['DES_PERSONA'] ?>")'>Excluir </a></li>
										<!-- <li><a tabindex="-1" href="#">Separated link</a></li> -->
									</ul>
								</div>
							</small>
						</td>
					<?php } ?>
				</tr>
				<?php
			} else {


				if (recursive_array_search($qrListaPersonas['COD_UNIVEND'], $arrayAutorizado) !== false) {
				?>
					<tr>
						<td><a class='btn btn-xs btn-info' style="border:0; padding: 3px 5px;  background-color: #<?php echo $qrListaPersonas['DES_COR'] ?>; color: #fff;"><i class="<?php echo $qrListaPersonas['DES_ICONE']; ?>" aria-hidden="true"></i></a></td>
						<td><?php echo $qrListaPersonas['DES_PERSONA']; ?></td>
						<td class="text-center"><?php echo fnValor($qrListaPersonas['TOTALCLI'], 0); ?></td>
						<td class="text-center"><small><?php echo $nomeLoja; ?></small></td>
						<td class="text-center"><small><?php echo $usuario; ?></small></td>
						<td class='text-center'><?php echo $personaaAtivo; ?></td>
						<td class='text-center'><?php echo $personaaAtualiza . "&nbsp;" . $personaCongela; ?></td>
						<td><?php echo fnDataFull($qrListaPersonas['DAT_CADASTR']); ?></td>
						<td><?php echo fnDataFull($qrListaPersonas['DAT_ALTERAC']); ?></td>
						<?php if (fnControlaAcesso("1600", $modsAutorizados) === false && $qrListaCampanha['LOG_RESTRITO'] == 'S') { ?>
							<td></td>
						<?php } else { ?>
							<td class="text-center">
								<small>
									<div class="btn-group dropdown dropleft">
										<button type="button" class="btn btn-info btn-xs dropdown-toggle transparency" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											ações &nbsp;
											<span class="fas fa-caret-down"></span>
										</button>
										<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
											<li><a href='javascript:void(0)' class='addBox' data-url="action.do?mod=<?php echo fnEncode(1038) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idx=<?php echo fnEncode($qrListaPersonas['COD_PERSONA']) ?>&pop=true" data-title="Persona / <?php echo $qrListaPersonas['DES_PERSONA']; ?>">Editar</a></li>
											<li><a href="action.do?mod=<?php echo fnEncode(1035) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idx=<?php echo fnEncode($qrListaPersonas['COD_PERSONA']) ?>">Acessar</a></li>
											<li class="divider"></li>
											<li><a href="javascript:void(0)" onclick='RefreshPersona("<?= fnEncode($cod_empresa) ?>","<?= $paramAtiva ?>","<?= fnEncode($qrListaPersonas['COD_PERSONA']) ?>")'><?= $txtAtiva ?></a></li>
											<li><a href="javascript:void(0)" onclick='excluiPersona("<?= fnEncode($cod_empresa) ?>","<?= fnEncode($qrListaPersonas['COD_PERSONA']) ?>","<?= $qrListaPersonas['qtd_campanha'] ?>","<?= $qrListaPersonas['DES_PERSONA'] ?>")'>Excluir </a></li>
											<!-- <li><a tabindex="-1" href="#">Separated link</a></li> -->
										</ul>
									</div>
								</small>
							</td>
						<?php } ?>
					</tr>
<?php
				}
			}
		}

		break;
}



?>