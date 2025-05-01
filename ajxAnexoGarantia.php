<?php 

include '_system/_functionsMain.php'; 

$cod_empresa = fnLimpaCampoZero(fnDecode($_POST['COD_EMPRESA']));
$cod_cliente = fnLimpaCampoZero(fnDecode($_POST['COD_CLIENTE']));
$cod_bem = fnLimpaCampoZero(fnDecode($_POST['COD_BEM']));
$des_anexo = fnLimpaCampo($_POST['NOM_ARQ']);
$opcao = fnLimpaCampo($_POST['OPCAO']);
$cod_usucada = $_SESSION[SYS_COD_USUARIO];


switch ($opcao) {

	case 'CAD':

	$sql = "INSERT INTO ANEXO_AVALIABEM (
			COD_EMPRESA,
			COD_CLIENTE,
			COD_BEM,
			DES_ANEXO,
			COD_USUCADA
				)values(
			'$cod_empresa',
			'$cod_cliente',
			'$cod_bem',
			'$des_anexo',
			'$cod_usucada'
			)";
	fnEscreve($sql);
	mysqli_query(connTemp($cod_empresa,''),$sql);

	break;

	case 'ALT':

	$sql = "UPDATE CONTROLE_TERMO
	SET $campo = '$nom_arq'
	WHERE COD_EMPRESA = $cod_empresa";
	fnEscreve($sql);
	mysqli_query(connTemp($cod_empresa,''),$sql);

	break;

	case 'EXC':

	$sql = "UPDATE CONTROLE_TERMO
	SET $campo = '$nom_arq'
	WHERE COD_EMPRESA = $cod_empresa";
	fnEscreve($sql);
	mysqli_query(connTemp($cod_empresa,''),$sql);

	break;
}

?>