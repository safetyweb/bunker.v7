<?php

include '_system/_functionsMain.php';

$cod_empresa = fnLimpaCampoZero(fnDecode($_GET['id']));
$cod_campanha = fnLimpaCampoZero(fnDecode($_GET['idc']));
$log_restrito = fnLimpaCampo($_GET['idl']);
$opcao = @$_GET['opcao'];
$itens_por_pagina = @$_GET['itens_por_pagina'];
$pagina = @$_GET['idPage'];

switch ($opcao) {
	case 'paginar':

		// ========================================PAGINACAO========================================

		$sql = "SELECT 1
			FROM CAMPANHA A
			LEFT JOIN WEBTOOLS.TIPOCAMPANHA B ON B.COD_TPCAMPA = A.TIP_CAMPANHA
			LEFT JOIN WEBTOOLS.USUARIOS C ON C.COD_USUARIO = A.COD_USUCADA
			WHERE A.COD_EMPRESA = $cod_empresa 
			order by A.DES_CAMPANHA ";

		$retorno = mysqli_query(connTemp($cod_empresa, ''), $sql);
		$totalitens_por_pagina = mysqli_num_rows($retorno);


		$numPaginas = ceil($totalitens_por_pagina / $itens_por_pagina);

		//variavel para calcular o início da visualização com base na página atual
		$inicio = ($itens_por_pagina * $pagina) - $itens_por_pagina;

		// ================================================================================

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

		$sql = "SELECT A.*, B.NOM_TPCAMPA, B.COD_TPCAMPA, C.NOM_USUARIO,
						IFNULL((SELECT B.NUM_PESSOAS FROM CAMPANHAREGRA B where B.COD_CAMPANHA = A.COD_CAMPANHA),0) as NUM_PESSOAS
						FROM CAMPANHA A
						LEFT JOIN WEBTOOLS.TIPOCAMPANHA B ON B.COD_TPCAMPA = A.TIP_CAMPANHA
						LEFT JOIN WEBTOOLS.USUARIOS C ON C.COD_USUARIO = A.COD_USUCADA
						WHERE A.COD_EMPRESA = $cod_empresa 
						order by A.DES_CAMPANHA
						LIMIT $inicio, $itens_por_pagina";
		//fnEscreve($sql);
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
				$campanhaAtivo = "<i class='fas fa-check' aria-hidden='true'></i>";
			} else {
				$campanhaAtivo = "";
			}

			if ($qrListaCampanha['LOG_ATUALIZA'] == "S") {
				$campanhaAtualiza = "<i class='fas fa-check' aria-hidden='true'></i>";
			} else {
				$campanhaAtualiza = "";
			}

			if ($qrListaCampanha['COD_TPCAMPA'] == 21) {
				$mod = 1169;
			} else {
				$mod = 1022;
			}

			$dat_expira = $qrListaCampanha['DAT_FIM'] . " " . $qrListaCampanha['HOR_FIM'];

			if ($dat_expira < date('Y-m-d H:i:s')) {
				$cor = "text-danger";
			} else {
				$cor = "";
			}

			if ($CarregaMaster == '1') {

				$lojaLoop = $qrListaCampanha['cod_univend'];
				if ($lojaLoop == 9999) {
					$nomeLoja = "Todas";
				} else {
					$NOM_ARRAY_UNIDADE = (array_search($qrListaCampanha['cod_univend'], array_column($ARRAY_UNIDADE, 'COD_UNIVEND')));
					$nomeLoja = $ARRAY_UNIDADE[$NOM_ARRAY_UNIDADE]['nom_fantasi'];
				}
			}

			if ($qrListaCampanha['LOG_RESTRITO'] == 'S') {
				$checkRestrito  = "checked";
			} else {
				$checkRestrito = "";
			}


?>

			<tr>
				<td class="text-center">
					<label class="switch">
						<input type="checkbox" class="switch" onclick='toggleRestrito(this,"<?= fnEncode($cod_empresa) ?>","<?= fnEncode($qrListaCampanha['COD_CAMPANHA']) ?>")' <?= $checkRestrito ?>>
						<span style="height: 25px;"></span>
					</label>
				</td>
				<td><a class='btn btn-xs btn-info' style="border:0; padding: 3px 5px;  background-color: #<?php echo $qrListaCampanha['DES_COR'] ?>; color: #fff;"><i class="<?php echo $qrListaCampanha['DES_ICONE']; ?>" aria-hidden="true"></i></a> <small> &nbsp;&nbsp; <?php echo $qrListaCampanha['DES_CAMPANHA'];; ?></td>
				<td class="text-center"><small><?php echo $nomeLoja; ?></small></td>
				<td><small><?php echo $qrListaCampanha['NOM_TPCAMPA']; ?></td>
				<td><small><?php echo $qrListaCampanha['NOM_USUARIO']; ?></td>
				<td class='text-center'><?php echo $campanhaAtivo; ?></td>
				<td class='text-center'><?php echo $campanhaAtualiza; ?></td>
				<td><small><?php echo fnDataFull($qrListaCampanha['DAT_CADASTR']); ?></td>
				<td><small><?php echo fnDataFull($qrListaCampanha['DAT_ALTERAC']); ?></td>
				<td class="<?= $cor ?>"><small><?php echo fnDataFull($dat_expira); ?></td>
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
								<!-- <li class="divider"></li> -->
								<!-- <li><a tabindex="-1" href="#">Separated link</a></li> -->
							</ul>
						</div>
					</small>
				</td>
			</tr>

<?php
		}

		break;

	case 'restrito':

		$sql = "UPDATE CAMPANHA 
			SET LOG_RESTRITO = '$log_restrito' 
			WHERE COD_EMPRESA = $cod_empresa 
			AND COD_CAMPANHA = $cod_campanha";

		fnTestesql(connTemp($cod_empresa, ''), $sql);

		break;
}


?>