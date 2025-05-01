<?php include "_system/_functionsMain.php";

header("Content-Type: application/json");

if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))) {
	//busca dados da empresa
	$cod_empresa = fnDecode($_GET['id']);

	$sql = "SELECT EMPRESAS.NOM_FANTASI,CATEGORIA.* FROM $connAdm->DB.EMPRESAS
				left JOIN CATEGORIA ON CATEGORIA.COD_EMPRESA=EMPRESAS.COD_EMPRESA
				where EMPRESAS.COD_EMPRESA = $cod_empresa";

	$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql) or die(mysqli_error(connTemp($cod_empresa, '')));
	$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

	if (isset($qrBuscaEmpresa)) {
		$nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];
	}
} else {
	$cod_empresa = 0;
}

$cod_municipio = fnLimpacampoZero($_GET["cidade"]);

$sql = "SELECT * FROM municipios_coord WHERE CD_MUNIBGE='$cod_municipio'";

$rs = mysqli_query(connTemp($cod_empresa, ""), trim($sql)) or die(mysqli_error(connTemp($cod_empresa, '')));
$linha = mysqli_fetch_assoc($rs);


echo json_encode($linha);