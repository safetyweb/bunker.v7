<?php include "_system/_functionsMain.php";

$buscaAjx1 = fnLimpaCampoZero(fnDecode($_GET['ajx1']));
$log_campusu = fnLimpacampo($_GET['LOG_CAMPUSU']);
$log_campvalida = fnLimpacampo($_GET['LOG_CAMPVALIDA']);
if (isset($_GET['opcao'])) {
	$opcao = fnLimpacampo($_GET['opcao']);
} else {
	$opcao = "";
}
$modsAutorizados = $_GET['MODSAUTORIZADOS'];

$cod_empresa = $buscaAjx1;

// fnEscreve($cod_empresa);

switch ($opcao) {
	case 'EXC':

		$cod_campanha = fnLimpaCampoZero(fnDecode($_REQUEST['COD_CAMPANHA']));
		$cod_empresa = fnLimpaCampoZero(fnDecode($_REQUEST['COD_EMPRESA']));
		$cod_usucada = $_SESSION['SYS_COD_USUARIO'];

		$sql = "CALL SP_EXCLUI_CAMPANHA($cod_campanha, $cod_empresa, $cod_usucada, 'EXC')";

		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

		break;

	default:

		if ($log_campusu == 'S') {
			$andUsucada = "AND A.COD_USUCADA = $_SESSION[SYS_COD_USUARIO]";
		} else {
			$andUsucada = "";
		}

		if ($log_campvalida == 'S') {
			$andCampValida = "AND CONCAT(A.DAT_FIM,' ',A.HOR_FIM) > NOW()";
		} else {
			$andCampValida = "";
		}

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

		$arrayAutorizado = explode(",", $_SESSION["SYS_COD_UNIVEND"]);

		$sql = "SELECT A.*, B.NOM_TPCAMPA, B.COD_TPCAMPA, C.NOM_USUARIO, D.NOM_FANTASI,
		IFNULL((SELECT B.NUM_PESSOAS FROM CAMPANHAREGRA B where B.COD_CAMPANHA = A.COD_CAMPANHA),0) as NUM_PESSOAS
		FROM CAMPANHA A
		LEFT JOIN WEBTOOLS.TIPOCAMPANHA B ON B.COD_TPCAMPA = A.TIP_CAMPANHA
		LEFT JOIN WEBTOOLS.USUARIOS C ON C.COD_USUARIO = A.COD_USUCADA
		LEFT JOIN WEBTOOLS.UNIDADEVENDA D ON D.COD_UNIVEND = A.COD_UNIVEND
		WHERE A.COD_EMPRESA = $cod_empresa 
		AND A.COD_EXCLUSA = 0
		$andUsucada
		$andCampValida
		order by A.DES_CAMPANHA ";
		//fnEscreve($sql);
		//echo($sql);

		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

		if (fnAutMaster($_SESSION["SYS_COD_TPUSUARIO"], $_SESSION["SYS_COD_EMPRESA"]) == '1') {
			$CarregaMaster = '1';
		} else {
			$CarregaMaster = '0';
		}

		$count = 0;
		while ($qrListaCampanha = mysqli_fetch_assoc($arrayQuery)) {
			$count++;

			if ($qrListaCampanha['LOG_ATIVO'] == "S") {
				$campanhaAtivo = "<i class='fal fa-check' aria-hidden='true'></i>";
			} else {
				$campanhaAtivo = "";
			}

			if ($qrListaCampanha['LOG_ATUALIZA'] == "S") {
				$campanhaAtualiza = "<i class='fal fa-check' aria-hidden='true'></i>";
			} else {
				$campanhaAtualiza = "";
			}

			if ($qrListaCampanha['COD_TPCAMPA'] == 21) {
				$mod = 1169;
			} else if ($qrListaCampanha['COD_TPCAMPA'] == 23) {
				$mod = 2013;
			} else {
				$mod = 1022;
			}

			$dat_expira = $qrListaCampanha['DAT_FIM'] . " " . $qrListaCampanha['HOR_FIM'];

			if ($dat_expira < date('Y-m-d H:i:s')) {
				$cor = "text-danger";
			} else {
				$cor = "";
			}

			$nomeLoja = $qrListaCampanha['NOM_FANTASI'];

			if ($qrListaCampanha['cod_univend'] == 9999) {
				$nomeLoja = "Todas";
			}

			if ($CarregaMaster == '1') {

				$lojaLoop = $qrListaCampanha['cod_univend'];


				if ($qrListaCampanha['LOG_CONTINU'] == "S") {
					$fimCampanha = "Contínua";
					$cor = "";
				} else {
					$fimCampanha = fnDataFull($dat_expira);
				}



?>

				<tr>
					<td class="text-center"><small><?php echo $qrListaCampanha['COD_CAMPANHA']; ?></small></td>
					<td><a class='btn btn-xs btn-info' style="border:0; padding: 3px 5px;  background-color: #<?php echo $qrListaCampanha['DES_COR'] ?>; color: #fff;"><i class="<?php echo $qrListaCampanha['DES_ICONE']; ?>" aria-hidden="true"></i></a> <small> &nbsp;&nbsp; <?php echo $qrListaCampanha['DES_CAMPANHA'];; ?></td>
					<td class="text-center"><small><?php echo $nomeLoja; ?></small></td>
					<td><small><?php echo $qrListaCampanha['NOM_TPCAMPA']; ?></td>
					<td><small><?php echo $qrListaCampanha['NOM_USUARIO']; ?></td>
					<td class='text-center'><?php echo $campanhaAtivo; ?></td>
					<td class='text-center'><?php echo $campanhaAtualiza; ?></td>
					<td><small><?php echo fnDataFull($qrListaCampanha['DAT_CADASTR']); ?></td>
					<td><small><?php echo fnDataFull($qrListaCampanha['DAT_ALTERAC']); ?></td>
					<td class="<?= $cor ?>"><small><?php echo $fimCampanha; ?></td>
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
										<li><a href='javascript:void(0)' class='addBox' data-url="action.do?mod=<?php echo fnEncode(1040) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idc=<?php echo fnEncode($qrListaCampanha['COD_CAMPANHA']) ?>&pop=true" data-title="Campanha / <?php echo $qrListaCampanha['DES_CAMPANHA']; ?>">Editar </a></li>
										<li><a href="action.do?mod=<?php echo fnEncode($mod); ?>&id=<?php echo fnEncode($cod_empresa); ?>&idc=<?php echo fnEncode($qrListaCampanha['COD_CAMPANHA']); ?>&idt=<?php echo fnEncode($qrListaCampanha['COD_TPCAMPA']); ?>">Acessar </a></li>
										<li class="divider"></li>
										<li><a href="javascript:void(0)" onclick='excluiCampanha("<?= fnEncode($cod_empresa) ?>","<?= fnEncode($qrListaCampanha['COD_CAMPANHA']) ?>","<?= $qrListaCampanha['DES_CAMPANHA'] ?>")'>Excluir </a></li>
										<!-- <li><a tabindex="-1" href="#">Separated link</a></li> -->
									</ul>
								</div>
							</small>
						</td>
					<?php } ?>
				</tr>

				<?php
			} else {

				if (recursive_array_search($qrListaCampanha['cod_univend'], $arrayAutorizado) !== false) {
				?>

					<tr>
						<td><a class='btn btn-xs btn-info' style="border:0; padding: 3px 5px;  background-color: #<?php echo $qrListaCampanha['DES_COR'] ?>; color: #fff;"><i class="<?php echo $qrListaCampanha['DES_ICONE']; ?>" aria-hidden="true"></i></a> <small> &nbsp;&nbsp; <?php echo $qrListaCampanha['DES_CAMPANHA']; ?></td>
						<td class="text-center"><small><?php echo $nomeLoja; ?></small></td>
						<td><small><?php echo $qrListaCampanha['NOM_TPCAMPA']; ?></td>
						<td><small><?php echo $qrListaCampanha['NOM_USUARIO']; ?></td>
						<td class='text-center'><?php echo $campanhaAtivo; ?></td>
						<td class='text-center'><?php echo $campanhaAtualiza; ?></td>
						<td><small><?php echo fnDataFull($qrListaCampanha['DAT_CADASTR']); ?></td>
						<td><small><?php echo fnDataFull($qrListaCampanha['DAT_ALTERAC']); ?></td>
						<td class="<?= $cor ?>"><small><?php echo $fimCampanha; ?></td>
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
											<li><a href='javascript:void(0)' class='addBox' data-url="action.do?mod=<?php echo fnEncode(1040) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idc=<?php echo fnEncode($qrListaCampanha['COD_CAMPANHA']) ?>&pop=true" data-title="Campanha / <?php echo $qrListaCampanha['DES_CAMPANHA']; ?>">Editar </a></li>
											<li><a href="action.do?mod=<?php echo fnEncode($mod); ?>&id=<?php echo fnEncode($cod_empresa); ?>&idc=<?php echo fnEncode($qrListaCampanha['COD_CAMPANHA']); ?>&idt=<?php echo fnEncode($qrListaCampanha['COD_TPCAMPA']); ?>">Acessar </a></li>
											<li class="divider"></li>
											<li><a href="javascript:void(0)" onclick='excluiCampanha("<?= fnEncode($cod_empresa) ?>","<?= fnEncode($qrListaCampanha['COD_CAMPANHA']) ?>","<?= $qrListaCampanha['DES_CAMPANHA'] ?>")'>Excluir </a></li>
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