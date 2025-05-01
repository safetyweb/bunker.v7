<?php

include '_system/_functionsMain.php';

$cod_empresa = fnLimpaCampoZero(fnDecode($_GET['id']));
$cod_persona = fnLimpaCampoZero(fnDecode($_GET['idp']));
$log_restrito = fnLimpaCampo($_GET['idl']);
$opcao = @$_GET['opcao'];
$itens_por_pagina = @$_GET['itens_por_pagina'];
$pagina = @$_GET['idPage'];

switch ($opcao) {
	case 'paginar':

		// =================================PAGINACAO===============================================

		$sql = "SELECT 1 FROM PERSONA 
					WHERE COD_EMPRESA = $cod_empresa 
					ORDER BY LOG_ATIVO DESC, DES_PERSONA";

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

		$sql = "SELECT * FROM PERSONA 
					WHERE COD_EMPRESA = $cod_empresa 
					ORDER BY LOG_ATIVO DESC, DES_PERSONA
					LIMIT $inicio, $itens_por_pagina";
		//fnEscreve($sql);
		$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

		if (fnAutMaster($_SESSION["SYS_COD_TPUSUARIO"], $_SESSION["SYS_COD_EMPRESA"]) == '1') {
			$CarregaMaster = '1';
		} else {
			$CarregaMaster = '0';
		}

		$count = 0;
		while ($qrListaPersona = mysqli_fetch_assoc($arrayQuery)) {
			$count++;

			if ($qrListaPersona['LOG_ATIVO'] == "S") {
				$campanhaAtivo = "<i class='fas fa-check' aria-hidden='true'></i>";
			} else {
				$campanhaAtivo = "";
			}

			if ($CarregaMaster == '1') {

				@$lojaLoop = @$qrListaPersona['cod_univend'];
				if ($lojaLoop == 9999) {
					$nomeLoja = "Todas";
				} else {
					@$NOM_ARRAY_UNIDADE = (array_search(@$qrListaPersona['cod_univend'], array_column(@$ARRAY_UNIDADE, 'COD_UNIVEND')));
					$nomeLoja = $ARRAY_UNIDADE[$NOM_ARRAY_UNIDADE]['nom_fantasi'];
				}
			}

			if ($qrListaPersona['LOG_RESTRITO'] == 'S') {
				$checkRestrito  = "checked";
			} else {
				$checkRestrito = "";
			}
			// fnEscreve($qrListaPersona['COD_PERSONA']);

?>

			<tr>
				<td class="text-center">
					<label class="switch">
						<input type="checkbox" class="switch" onclick='toggleRestrito(this,"<?= fnEncode($cod_empresa) ?>","<?= fnEncode($qrListaPersona['COD_PERSONA']) ?>")' <?= $checkRestrito ?>>
						<span style="height: 25px;"></span>
					</label>
				</td>
				<td><a class='btn btn-xs btn-info' style="border:0; padding: 3px 5px;  background-color: #<?php echo $qrListaPersona['DES_COR'] ?>; color: #fff;"><i class="<?php echo $qrListaPersona['DES_ICONE']; ?>" aria-hidden="true"></i></a> <small> &nbsp;&nbsp; <?php echo $qrListaPersona['DES_PERSONA'];; ?></td>
				<td class="text-center"><small><?php echo $nomeLoja; ?></small></td>
				<td class='text-center'><?php echo $campanhaAtivo; ?></td>
				<td><small><?php echo fnDataFull($qrListaPersona['DAT_CADASTR']); ?></td>
				<td><small><?php echo fnDataFull($qrListaPersona['DAT_ALTERAC']); ?></td>
				<td class="text-center">
					<small>
						<div class="btn-group dropdown dropleft">
							<button type="button" class="btn btn-info btn-xs dropdown-toggle transparency" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								ações &nbsp;
								<span class="fas fa-caret-down"></span>
							</button>
							<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
								<li><a href='javascript:void(0)' class='addBox' data-url="action.do?mod=<?php echo fnEncode(1038) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idx=<?php echo fnEncode($qrListaPersona['COD_PERSONA']) ?>&pop=true" data-title="Persona / <?php echo $qrListaPersona['DES_PERSONA']; ?>">Editar </a></li>
								<li><a href="action.do?mod=<?php echo fnEncode(1035) ?>&id=<?php echo fnEncode($cod_empresa) ?>&idx=<?php echo fnEncode($qrListaPersona['COD_PERSONA']) ?>" target="_blank">Acessar </a></li>
							</ul>
						</div>
					</small>
				</td>
			</tr>

<?php
		}
		break;

	case 'restrito':

		$sql = "UPDATE PERSONA 
			SET LOG_RESTRITO = '$log_restrito' 
			WHERE COD_EMPRESA = $cod_empresa 
			AND COD_PERSONA = $cod_persona";

		fnTestesql(connTemp($cod_empresa, ''), $sql);

		break;
}
