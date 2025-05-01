<?php

$modulosAutorizados = '';

//busca perfil do usuário 
//4 - fidelidade
$sql1 = "select cod_usuario,cod_defsist,cod_perfils
		from usuarios
		where cod_empresa = " . $_SESSION["SYS_COD_EMPRESA"] . " and
			  cod_defsist in (4," . $_SESSION["SYS_COD_SISTEMA"] . ") and
			  cod_usuario = " . $_SESSION["SYS_COD_USUARIO"] . " ";

//fnEscreve2($sql1);			  
//echo $sql1.'<br>';

if ($_SESSION["SYS_COD_SISTEMA"] == 3) {
	$cod_perfils = '9999';
} else {
	$arrayQuery1 = mysqli_query($connAdm->connAdm(), $sql1);
	$qrBuscaPerfil = mysqli_fetch_assoc($arrayQuery1);
	$cod_perfils = $qrBuscaPerfil['cod_perfils'];
}

//busca modulos autorizados

$sql2 = "select cod_modulos from perfil
		  where cod_sistema=" . $_SESSION["SYS_COD_SISTEMA"] . " and
		cod_perfils in($cod_perfils)";

//echo '_'.$sql2.'_';

/*$sql2 = "select cod_modulos from perfil
		where cod_sistema=4 and
		cod_perfils in($cod_perfils)";
*/
//fnEscreve($sql2);			
//echo("<h1>".$sql2."</h1>");			

$arrayQuery2 = mysqli_query($connAdm->connAdm(), $sql2);

$count = 0;
while ($qrBuscaAutorizacao = mysqli_fetch_assoc($arrayQuery2)) {
	$cod_modulos_aut = $qrBuscaAutorizacao['cod_modulos'];
	$modulosAutorizados = $modulosAutorizados . $cod_modulos_aut . ",";
}

$arrayAutorizado = explode(",", $modulosAutorizados);


//fnEscreve($sql2);

$arrayParamAutorizacao = array(
	'COD_MODULO' => "9999",
	'MODULOS_AUT' => $arrayAutorizado,
	'COD_SISTEMA' => $_SESSION["SYS_COD_SISTEMA"]
);

//echo "AAAAA ".$cod_modulos_aut."<hr><br> ";
//echo "BBBBB".$cod_perfils."<hr><br> ";
