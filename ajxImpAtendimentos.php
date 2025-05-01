<?php 

	include '_system/_functionsMain.php'; 	

	//echo fnDebug('true');

	$opcao = $_GET['opcao'];
	$itens_por_pagina = $_GET['itens_por_pagina'];	
	$pagina = $_GET['idPage'];
	$esteira = (@$_GET['esteira'] == "true");
	//echo "<tr><td colspan=100>";
	//print_r($_POST);
	//echo "</td></tr>";
	
	$dat_ini = fnDataSql($_POST['DAT_INI']);
	$dat_fim = fnDataSql($_POST['DAT_FIM']);
	$dat_ini_ent = fnDataSql($_POST['DAT_INI_ENT']);
	$dat_fim_ent = fnDataSql($_POST['DAT_FIM_ENT']);
	$cod_externo = $_POST['COD_EXTERNO'];
	$cod_empresa = fnLimpaCampoZero(fnDecode($_GET['id']));
	$cod_univend_ate = fnLimpacampoZero($_POST['COD_UNIVEND_ATE']);
	$nom_chamado = $_POST['NOM_CHAMADO'];
	$cod_chamado = $_POST['COD_CHAMADO'];
	$cod_usuario = $_POST['COD_USUARIO'];
	$usuariosAutorizados = fnLimpaCampo(fnDecode($_POST['USUARIOS_AUT']));
	if($usuariosAutorizados == ""){
		$usuariosAutorizados = 0;
	}

	$cod_tpsolicitacao = $_POST['COD_TPSOLICITACAO'];
	$cod_status = $_POST['COD_STATUS'];
	$cod_integradora = $_POST['COD_INTEGRADORA'];
	$cod_plataforma = $_POST['COD_PLATAFORMA'];
	$cod_versaointegra = $_POST['COD_VERSAOINTEGRA'];
	$cod_prioridade = $_POST['COD_PRIORIDADE'];
	$cod_usures = $_POST['COD_USURES'];

	if (isset($_POST['COD_STATUS_EXC'])){
			$Arr_COD_STATUS_EXC = $_POST['COD_STATUS_EXC'];
			$cod_status_exc = "";	 
			 
			   for ($i=0;$i<count($Arr_COD_STATUS_EXC);$i++) 
			   { 
				$cod_status_exc = $cod_status_exc.$Arr_COD_STATUS_EXC[$i].",";
			   } 
			   
			   $cod_status_exc = rtrim($cod_status_exc, ',');
				
		}else{$cod_status_exc = "0";}

		if (isset($_POST['COD_TIPO_EXC'])){
			$Arr_COD_TIPO_EXC = $_POST['COD_TIPO_EXC'];
			$cod_tipo_exc = "";	 
			 
			   for ($i=0;$i<count($Arr_COD_TIPO_EXC);$i++) 
			   { 
				$cod_tipo_exc = $cod_tipo_exc.$Arr_COD_TIPO_EXC[$i].",";
			   } 
			   
			   $cod_tipo_exc = rtrim($cod_tipo_exc, ',');
				
		}else{$cod_tipo_exc = "0";}

		$hoje = fnFormatDate(date("Y-m-d"));

		if($dat_ini == ""){$ANDdatIni = " ";}else{$ANDdatIni = "AND DATE_FORMAT(AC.DAT_CADASTR, '%Y-%m-%d') >= '$dat_ini' ";}

		if($dat_ini_ent == date('Y-m-d')){$ANDdatIniEnt = " ";}else{$ANDdatIniEnt = "AND DATE_FORMAT(AC.DAT_ENTREGA, '%Y-%m-%d') >= '$dat_ini_ent'";}

		if($dat_fim_ent == ""){$ANDdatFimEnt = " ";}else{$ANDdatFimEnt = "AND DATE_FORMAT(AC.DAT_ENTREGA, '%Y-%m-%d') <= '$dat_fim_ent'";}

		if($cod_externo == ""){$ANDcodExterno = " ";}else{$ANDcodExterno = "AND AC.COD_EXTERNO LIKE '%$cod_externo%' ";}

		if($cod_chamado == ""){$ANDcodChamado = " ";}else{$ANDcodChamado = "AND AC.COD_ATENDIMENTO = $cod_chamado ";}

		if($cod_empresa == ""){$ANDcodEmpresa = " ";}else{$ANDcodEmpresa = "AND AC.COD_EMPRESA = $cod_empresa ";}

		if($nom_chamado == ""){$ANDnomChamado = " ";}else{$ANDnomChamado = "AND AC.NOM_CHAMADO LIKE '%$nom_chamado%' ";}

		if($cod_tpsolicitacao == ""){$ANDcodTipo = " ";}else{$ANDcodTipo = "AND AC.COD_TPSOLICITACAO = $cod_tpsolicitacao ";}

		if($cod_status == ""){$ANDcodStatus = "";}else{$ANDcodStatus = "AND AC.COD_STATUS = $cod_status ";}

		if($cod_status_exc == "0"){$ANDcodStatusExc = "";}else{$ANDcodStatusExc = "AND AC.COD_STATUS NOT IN($cod_status_exc) ";}

		if($cod_integradora == ""){$ANDcodIntegradora = " ";}else{$ANDcodIntegradora = "AND AC.COD_INTEGRADORA = $cod_integradora ";}

		if($cod_plataforma == ""){$ANDcodPlataforma = " ";}else{$ANDcodPlataforma = "AND AC.COD_PLATAFORMA = $cod_plataforma ";}

		if($cod_versaointegra == ""){$ANDcodVersaointegra = " ";}else{$ANDcodStatus = "AND AC.COD_VERSAOINTEGRA = $cod_versaointegra ";}

		if($cod_prioridade == ""){$ANDcodPrioridade = " ";}else{$ANDcodPrioridade = "AND AC.COD_PRIORIDADE = $cod_prioridade ";}

		if($cod_usuario == ""){$ANDcodUsuario = " ";}else{$ANDcodUsuario = "AND AC.COD_SOLICITANTE = $cod_usuario ";}

		if($cod_usures == ""){$ANDcod_usures = " ";}else{$ANDcod_usures = "AND AC.COD_USURES = $cod_usures ";}

		if($cod_usuarios_env == ""){$ANDcod_usuarios_env = " ";}else{$ANDcod_usuarios_env = "AND AC.COD_USUARIOS_ENV IN($cod_usuarios_env)";}

		if($cod_univend_ate == 0){$ANDcod_univend_ate = " ";}else{$ANDcod_univend_ate = "AND AC.COD_UNIVEND_ATE = $cod_univend_ate ";}

		if($cod_clientes_env == "" || $cod_clientes_env == 0){
			$ANDcod_clientes_env = " ";
		}else{

			$clientes = explode(',', $cod_clientes_env);

			if(count($clientes) > 1){

				$ANDcod_clientes_env = "AND (";

				for ($i=0; $i < count($clientes); $i++) { 

					if($i == 0){
						$ANDcod_clientes_env .= "FIND_IN_SET('".$clientes[0]."', AC.COD_CLIENTES_ENV) ";
					}else{
						$ANDcod_clientes_env .= "OR FIND_IN_SET('".$clientes[$i]."', AC.COD_CLIENTES_ENV) ";
					}
					

				}

				$ANDcod_clientes_env .= ")";

			}else{

				$ANDcod_clientes_env = "AND FIND_IN_SET('$cod_clientes_env', AC.COD_CLIENTES_ENV)";

			}
		}

		if($usuariosAutorizados == ""){
			$andAutorizados = "AND COD_USURES IN(0)";
		}else if($usuariosAutorizados == "9999"){
			$andAutorizados = "";
		}else{
			$andAutorizados = "AND COD_USURES IN($usuariosAutorizados)";
		}

$sqlSac = "SELECT AC.*, AT.DES_TPSOLICITACAO, 
			AP.DES_PRIORIDADE, AP.DES_COR AS COR_PRIORIDADE, AP.DES_ICONE AS ICO_PRIORIDADE,
			AST.ABV_STATUS, AST.DES_COR AS COR_STATUS, AST.DES_ICONE AS ICO_STATUS,
			UV.NOM_FANTASI AS SECRETARIA
			FROM ATENDIMENTO_CHAMADOS AC
			LEFT JOIN ATENDIMENTO_PRIORIDADE AP ON AP.COD_PRIORIDADE = AC.COD_PRIORIDADE
			LEFT JOIN ATENDIMENTO_STATUS AST ON AST.COD_STATUS = AC.COD_STATUS
			LEFT JOIN ATENDIMENTO_TPSOLICITACAO AT ON AT.COD_TPSOLICITACAO = AC.COD_TPSOLICITACAO
			LEFT JOIN UNIDADEVENDA UV ON UV.COD_UNIVEND=AC.COD_UNIVEND_ATE
			WHERE AC.COD_EMPRESA = $cod_empresa
				AND DATE_FORMAT(AC.DAT_CADASTR, '%Y-%m-%d') <= '$dat_fim'
				$andAutorizados
				$ANDdatIni
				$ANDcodExterno
				$ANDcodChamado
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
				$ANDdatIniEnt
				$ANDdatFimEnt
				$ANDcod_usuarios_env
				$ANDcod_clientes_env
				$ANDcod_univend_ate
			ORDER BY AC.COD_ATENDIMENTO DESC";
	// echo($sqlSac);

	$arrayQuerySac = mysqli_query(connTemp($cod_empresa, ''), $sqlSac);

	$count = 0;
	$adm = "";
	$entrega = "";

?>
<table class="table table-bordered table-striped table-hover">
	<thead>
		<tr>
			<th><small>Chamado</small></th>
			<th><small>Título</small></th>
			<?php if ($cod_empresa == 311) { ?>
				<th><small>Secretaria</small></th>
			<?php } ?>
			<th><small>Solicitantes</small></th>
			<th><small>Solicitação</small></th>
			<!-- <th><small>Responsável</small></th> -->
			<th><small>Prioridade</small></th>
			<th><small>Status</small></th>
			<th><small>Cadastro</small></th>
			<th><small>Prazo</small></th>
			<th><small>Atualizado</small></th>
		</tr>
	</thead>
	<tbody>

<?php 

	while ($qrSac = mysqli_fetch_assoc($arrayQuerySac)) {

	if ($qrSac['LOG_ADM'] == 'S') {
	$adm = "<i class='fal fa-user-check shortCut' data-toggle='tooltip' data-placement='left' data-original-title='ti'></i>";
	} else {
	$adm = "<i class='fal fa-user-tie shortCut' data-toggle='tooltip' data-placement='left' data-original-title='cliente'></i>";
	}

	$count++;


	$sqlUsuarios = "SELECT (SELECT NOM_USUARIO FROM USUARIOS WHERE COD_USUARIO = $qrSac[COD_SOLICITANTE]) AS NOM_SOLICITANTE,
							(SELECT NOM_USUARIO FROM USUARIOS WHERE COD_USUARIO = $qrSac[COD_USURES]) AS NOM_RESPONSAVEL";
	$qrNomUsu = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $sqlUsuarios));
	//fnEscreve($sqlUsuarios);										  

	if ($qrSac['DAT_ENTREGA'] == "1969-12-31") {
	$entrega = "";
	} else {
	$entrega = fnDataShort($qrSac['DAT_ENTREGA']);
	}

	if ($qrSac['DAT_INTERAC'] != "") {
	if (fnDatasql($qrSac['DAT_INTERAC']) == fnDatasql($hoje)) {
	$atualizado = "Hoje";
	} else if (fnDatasql($qrSac['DAT_INTERAC']) == date('Y-m-d', strtotime(' -1 days'))) {
	$atualizado = "Ontem";
	} else {
	$atualizado = fnDataFull($qrSac['DAT_INTERAC']);
	}
	} else {
	$atualizado = "";
	}

	$clientes_env = "";

	$sqlCli = "SELECT COD_CLIENTE, NOM_CLIENTE FROM CLIENTES 
			WHERE COD_CLIENTE IN($qrSac[COD_CLIENTES_ENV])";
	$arrayQueryCli = mysqli_query(connTemp($cod_empresa, ''), $sqlCli);

	while ($qrLista = mysqli_fetch_assoc($arrayQueryCli)) {
	$clientes_env .= $qrLista[NOM_CLIENTE] . ", ";
	}

	$clientes_env = rtrim(ltrim(trim($clientes_env), ','), ',');

	//$diff_dias = fnDateDif($qrSac['DAT_CADASTR'],Date("Y-m-d"));
	// fnEscreve(fnDatasql($qrSac['DAT_INTERAC']));
	?>

		<tr>
			<td class="text-center">
				<small>
					<?= $qrSac['COD_ATENDIMENTO'] ?>
				</small>
			</td>
			<td><small><?= $qrSac['NOM_CHAMADO'] ?></small></td>
			<?php if ($cod_empresa == 311) { ?>
				<td><small><?= $qrSac['SECRETARIA'] ?></small></td>
			<?php } ?>
			<td><small><?= $clientes_env ?></small></td>
			<td><small><?= $qrSac['DES_TPSOLICITACAO'] ?></small></td>
			<!-- <td><small><?= $qrNomUsu['NOM_RESPONSAVEL'] ?></small></td> -->

			<td class="text-center">
				<small>
					<p class="label" style="background-color: <?php echo $qrSac['COR_PRIORIDADE'] ?>">
						<span class="<?php echo $qrSac['ICO_PRIORIDADE']; ?>" style="color: #FFF;"></span>
						<!-- &nbsp; <?php echo $qrSac['DES_PRIORIDADE']; ?> -->
					</p>
				</small>
			</td>

			<td class="text-center">
				<small>
					<p class="label" style="background-color: <?php echo $qrSac['COR_STATUS'] ?>">
						<span class="<?php echo $qrSac['ICO_STATUS']; ?>" style="color: #FFF;"></span>
						&nbsp;<?php echo $qrSac['ABV_STATUS']; ?>
					</p>
				</small>
			</td>

			<td class="text-center"><small><?= fnDataShort($qrSac['DAT_CADASTR']); ?></small></td>
			<td class="text-center"><small><?= $entrega ?></small></td>
			<td class="text-center"><small><?= $atualizado ?></small></td>

		</tr>
	<?php
	}
	?>

	</tbody>
	
</table>