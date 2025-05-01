<?php include "_system/_functionsMain.php";

$i = fnLimpaCampoZero($_REQUEST["param"]);
$cod_empresa = fnLimpaCampoZero($_REQUEST["cod_empresa"]);
$des_parametro = fnLimpaCampo($_REQUEST["des"]);
$acao = fnLimpaCampo($_REQUEST["acao"]);

if ($acao == "all"){
	$sql = "SELECT COD_PARAMETRO, DES_PARAMETRO FROM PARAMETRO".$i." WHERE COD_EMPRESA = $cod_empresa order by DES_PARAMETRO";
	$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
	$post = array();
	while($qrParam = mysqli_fetch_assoc($arrayQuery)){
		$post[] = array("id"=>$qrParam["COD_PARAMETRO"],"name"=>$qrParam["DES_PARAMETRO"]);
	}
}else{
	$sql = "SELECT COD_PARAMETRO, DES_PARAMETRO FROM PARAMETRO".$i." WHERE DES_PARAMETRO LIKE '%".$des_parametro."%' AND COD_EMPRESA = $cod_empresa order by DES_PARAMETRO LIMIT 100";
	$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
	$post = array();
	while($qrParam = mysqli_fetch_assoc($arrayQuery)){
		$post[] = array("id"=>$qrParam["COD_PARAMETRO"],"name"=>$qrParam["DES_PARAMETRO"]);
	}
}
echo json_encode($post);
?>