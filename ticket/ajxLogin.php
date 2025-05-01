<?php

include "../_system/_functionsMain.php";

if (isset($_POST['senha'])) {
	$senha = fnEncode(fnLimpacampo($_POST['senha']));
} else {
	$senha = "";
}

$cod_empresa = fnLimpacampo($_POST['COD_EMPRESA']);

$k_num_cartao = fnLimpaCampo($_REQUEST['KEY_NUM_CARTAO']);
$k_num_celular = fnLimpaCampo(fnLimpaDoc($_REQUEST['KEY_NUM_CELULAR']));
$k_cod_externo = fnLimpaCampo($_REQUEST['KEY_COD_EXTERNO']);
$k_num_cgcecpf = fnLimpaDoc(fnLimpaCampo($_REQUEST['KEY_NUM_CGCECPF']));
$k_dat_nascime = fnLimpaCampo($_REQUEST['KEY_DAT_NASCIME']);
$k_des_emailus = fnLimpaCampo($_REQUEST['KEY_DES_EMAILUS']);

$whereSql = "";

if ($k_num_cartao != "") {
	$whereSql .= "OR NUM_CARTAO = '$k_num_cartao' ";
}

if ($k_num_celular != "") {
	$whereSql .= "OR NUM_CELULAR = '$k_num_celular' ";
}

if ($k_cod_externo != "") {
	$whereSql .= "OR COD_EXTERNO = '$k_cod_externo' ";
}

if ($k_num_cgcecpf != "") {
	$whereSql .= "OR NUM_CGCECPF = '$k_num_cgcecpf' ";
}

if ($k_dat_nascime != "") {
	$whereSql .= "OR DAT_NASCIME = '$k_dat_nascime' ";
}

if ($k_des_emailus != "") {
	$whereSql .= "OR DES_EMAILUS = '$k_des_emailus' ";
}

$whereSql = trim(ltrim($whereSql, "OR"));

$sqlCli = "SELECT * FROM CLIENTES 
	       WHERE COD_EMPRESA = $cod_empresa
	       AND ($whereSql)
	       ORDER BY 1 LIMIT 1";



$result = mysqli_query(connTemp($cod_empresa, ''), $sqlCli);

$qrCliente = mysqli_fetch_assoc($result);

$linhas = mysqli_num_rows($result);

if ($linhas == 0) {

	echo 'sem_resultado';
} else {




	$idCliente = $qrCliente['COD_CLIENTE'];
	$senhaCli = $qrCliente['DES_SENHAUS'];
	$ativo = $qrCliente['LOG_ESTATUS'];
	$log_termos = $qrCliente['LOG_TERMO'];


	// echo $senhaCli."<br>";
	// echo $senha."<br>";
	if ($senhaCli == $senha) {

		if ($ativo == 'S') {

			$sql = "SELECT DES_DOMINIO, COD_DOMINIO from SITE_EXTRATO WHERE COD_EMPRESA = $cod_empresa ";
			//fnEscreve($sql);
			$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);
			$qrBuscaDesEmpresa = mysqli_fetch_assoc($arrayQuery);
			//fnEscreve($qrBuscaCodEmpresa['COD_EMPRESA']);                
			$des_dominio = $qrBuscaDesEmpresa['DES_DOMINIO'];

			if ($qrBuscaDesEmpresa['COD_DOMINIO'] == 2) {
				$extensaoDominio = ".fidelidade.mk";
			} else {
				$extensaoDominio = ".mais.cash";
			}

			if ($log_termos == 'S') {

				echo '<header><h1>Extrato</h1><p class="lead">Relatório detalhado de lançamentos</p></header>';
				//echo '<iframe src="http://adm.bunker.mk/action.do?mod='.fnEncode(1231).'&id='.fnEncode($cod_empresa).'&idC='.fnEncode($idCliente).'&pop=true&security=OFF" frameborder="0" height="800px" width="100%"></iframe>';
				echo '<iframe src="https://' . $des_dominio . $extensaoDominio . '/historicoComprasHS.do?id=' . fnEncode($cod_empresa) . '&idC=' . fnEncode($idCliente) . '&pop=true&security=OFF" id="conteudoAba" frameborder="0" style="min-height: 500px; width:100%;"></iframe>';
			} else {

				$sqlTermos = "SELECT LOG_LGPD FROM CONTROLE_TERMO WHERE cod_empresa = 77";
				$arrTermos = mysqli_query(connTemp($cod_empresa, ''), $sql);
				$qrTermos = mysqli_fetch_assoc($arrTermos);

				if ($qrTermos['LOG_LGPD'] == 'S') {
					echo '<iframe src="https://' . $des_dominio . $extensaoDominio . '/active.do?idC=' . fnEncode($idCliente) . '&pop=true" id="conteudoAba" frameborder="0" style="min-height: 500px; width:100%;"></iframe>';
				} else {
					echo '<header><h1>Extrato</h1><p class="lead">Relatório detalhado de lançamentos</p></header>';
					//echo '<iframe src="http://adm.bunker.mk/action.do?mod='.fnEncode(1231).'&id='.fnEncode($cod_empresa).'&idC='.fnEncode($idCliente).'&pop=true&security=OFF" frameborder="0" height="800px" width="100%"></iframe>';
					echo '<iframe src="https://' . $des_dominio . $extensaoDominio . '/historicoComprasHS.do?id=' . fnEncode($cod_empresa) . '&idC=' . fnEncode($idCliente) . '&pop=true&security=OFF" id="conteudoAba" frameborder="0" style="min-height: 500px; width:100%;"></iframe>';
				}
			}
		} else {
			echo 'inativo';
		}
	} else {

		echo 'sem_resultado';
	}
}
