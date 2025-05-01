<?php
if (isset($_REQUEST['COD_UNIVEND'])) {
	@$Arr_COD_UNIVEND = array_filter($_REQUEST['COD_UNIVEND']);
}
//print_r($Arr_COD_UNIVEND);
//inicializa univend vazia - Todas
if (isset($Arr_COD_UNIVEND)) {
	//array das unidades de venda
	$countUnive = 0;
	if (isset($Arr_COD_UNIVEND)) {
		for ($i = 0; $i < count($Arr_COD_UNIVEND); $i++) {
			@$str_univend .= $Arr_COD_UNIVEND[$i] . ',';
			$countUnive++;
		}
		$str_univend = substr($str_univend, 0, -1);
	}
	$cod_univend = $str_univend;
} else {
	$cod_univend = "9999";
}

//fnEscreve($str_univend);
//fnEscreve($cod_univend);	
//fnEscreve($str_univend);	

//busca revendas do usuário
$usuReportAdm = $_SESSION["SYS_COD_USUARIO"];
$empReportAdm = $_SESSION["SYS_COD_EMPRESA"];
$sqlUsu = "SELECT COD_UNIVEND,
			   (select count(*) from unidadevenda where cod_empresa=usuarios.COD_EMPRESA and LOG_ESTATUS = 'S') QTD_UNIVEND
			   FROM usuarios WHERE cod_empresa = $cod_empresa AND cod_usuario = $usuReportAdm ";
//fnEscreve($sqlUsu);
$arrayQueryUsu = mysqli_query($connAdm->connAdm(), $sqlUsu);
$qrUnidadesUsuario = mysqli_fetch_assoc($arrayQueryUsu);
$cod_univendUsu = @$qrUnidadesUsuario['COD_UNIVEND'];
$qtd_univendUsu = @$qrUnidadesUsuario['QTD_UNIVEND'];
$lojas = explode(',', $cod_univendUsu);
$lojasAut = count($lojas);

// echo "<pre>";
// print_r($cod_univendUsu);
// echo "</pre>";

//carrega unives autorizadas
//$qtd_univendUsu = 2;
$mod = fnDecode($_GET['mod']);
//verifica se acessa total - mensagem
if ($lojasAut < $qtd_univendUsu) {
	//fnEscreve("Acesso parcial");
	if ($mod != 1280) {
		$msgRetorno = "Você <strong>não possui</strong> acesso a todas as unidades. <br/>Massa de dados <strong>parcial</strong>.";
	} else {
		$msgRetorno = "Visualização dos chamados das <strong>unidades autorizadas</strong>. <br/>Massa de dados <strong>parcial</strong>.";
	}
	$msgTipo = 'alert-warning';
}

//se usuário não é da empresa, acesso master
if ($empReportAdm == $cod_empresa) {
	$usuReportAdm = "N";

	//busca loja específica
	if ($cod_univend == "9999") {
		$lojasSelecionadas = $cod_univendUsu;
	} else {
		$lojasSelecionadas = $cod_univend;
	}
} else {
	$usuReportAdm = "S";

	$lojasReportAdm = "";

	//monta array todas lojas - usuario adm
	$sql = "select COD_UNIVEND from unidadevenda where COD_EMPRESA = $cod_empresa and (cod_exclusa=0 or cod_exclusa is null) and log_estatus = 'S' ";
	$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);
	//fnEscreve($sql);

	while ($qrListaUnidades = mysqli_fetch_assoc($arrayQuery)) {
		$lojasReportAdm = $lojasReportAdm . $qrListaUnidades['COD_UNIVEND'] . ",";
	}
	$lojasReportAdm = rtrim(ltrim($lojasReportAdm, ','), ',');

	////busca loja específica 
	if ($cod_univend == "9999") {
		$lojasSelecionadas = $lojasReportAdm;
	} else {
		$lojasSelecionadas = $cod_univend;
	}
}

// echo $lojasSelecionadas;
/*if ($lojasSelecionadas == "9999"){
		$lojasSelecionadas = "0,9999";
	}*/
