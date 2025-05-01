<?php
if ($_SESSION['SYS_COD_USUARIO'] == 127937) {
	echo fnDebug('true');
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
$cod_valor = "";
$cod_campanha = "";
$des_canal = "";
$qtd_lista = 0;
$campo = "";
$fatura = "";
$valor = "";
$cod_usucada = "";
$arrayCod = [];
$qrCod = "";
$invest = "";
$retorno = "";
$roi = "";


include '_system/_functionsMain.php';



$cod_valor = fnLimpaCampoZero(@$_POST['pk']);
$cod_empresa = fnLimpaCampoZero(@$_POST['codempresa']);
$cod_campanha = fnLimpaCampoZero(@$_POST['codcampanha']);
$des_canal = fnLimpaCampo(@$_POST['descanal']);
$qtd_lista = fnLimpaCampoZero(@$_POST['qtdlista']);
$campo = fnLimpaCampo(@$_POST['name']);
$fatura = fnLimpaCampo(@$_POST['fatur']);
$valor = fnLimpaCampo(@$_POST['value']);
$cod_usucada = $_SESSION['SYS_COD_USUARIO'];

if (strpos($valor, ',') !== false) {
	$valor = fnValorSql($valor);
}

// fnEscreve($cod_empresa);
// fnEscreve($campo);
// fnEscreve($valor);

$sql = "SELECT COD_VALOR FROM VALORES_COMUNICACAO WHERE COD_EMPRESA = $cod_empresa AND COD_CAMPANHA = $cod_campanha AND DES_CANAL = '$des_canal'";
$arrayCod = mysqli_query(connTemp($cod_empresa, ''), $sql);

if (mysqli_num_rows($arrayCod) > 0) {

	$qrCod = mysqli_fetch_assoc($arrayCod);
	$cod_valor = $qrCod['COD_VALOR'];

	$sql = "UPDATE VALORES_COMUNICACAO SET $campo='$valor', COD_ALTERAC = $cod_usucada, DAT_ALTERAC = NOW() WHERE COD_VALOR = $cod_valor AND COD_EMPRESA = $cod_empresa";
	fnEscreve($sql);
	mysqli_query(connTemp($cod_empresa, ''), $sql);
} else {

	$sql = "INSERT INTO VALORES_COMUNICACAO(COD_EMPRESA, COD_CAMPANHA, $campo, DES_CANAL, COD_USUCADA) VALUES($cod_empresa, $cod_campanha, '$valor', '$des_canal', $cod_usucada)";
	fnEscreve($sql);
	mysqli_query(connTemp($cod_empresa, ''), $sql);
}

$invest = $valor * $qtd_lista;
$retorno = ($fatura - $invest);
$roi = $retorno / $invest;


?>
<div>

	<div id="RETORNO"><small>R$ <?= fnValor($retorno, 2) ?></small></div>
	<div id="INVEST"><small>R$</small> <small class="VAL_INVEST"><?= fnValor($invest, 2) ?></small></div>
	<div id="FATUR"><small><?= fnValor($roi, 0) ?>x</small></div>
	<div id="SUFIXO"><?= $cod_campanha . '_' . $des_canal ?></div>
	<div id="VALOR"><?= fnValor($valor, 5) ?></div>

</div>