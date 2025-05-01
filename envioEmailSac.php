<?php

$emailsEnvolv = "rone.all@gmail.com;diogo@markafidelizacao.com.br;coordenacaoti@markafidelizacao.com.br;";

$modHD = 1285;
$ultimoComent = "";

if ($_SESSION['SYS_COD_EMPRESA'] != 2 && $_SESSION['SYS_COD_EMPRESA'] != 3) {
	$modHD = 1288;
}

if (isset($todos)) {
	$todos = $todos;
} else {
	$todos = "";
}

$cod_usuario = 0;

$sql = "SELECT A.COD_CHAMADO,
			A.NOM_CHAMADO,
			A.COD_USUARIOS_ENV,
			A.COD_CONSULTORES,
			A.COD_USUARIO,
			A.COD_USURES,
			A.DES_SAC,
			A.COD_USUARIO_ORDENAC,
			B.NOM_FANTASI,
			B.COD_CONSULTOR,
			C.DES_STATUS,
			(SELECT DES_EMAILUS FROM WEBTOOLS.USUARIOS WHERE COD_USUARIO = A.COD_USURES) EMAIL_RESPONSAVEL,
			(SELECT DES_EMAILUS FROM WEBTOOLS.USUARIOS WHERE COD_USUARIO = A.COD_USUARIO) EMAIL_SOLICITANTE,
			(SELECT COD_EMPRESA FROM WEBTOOLS.USUARIOS WHERE COD_USUARIO = A.COD_USUARIO) EMPRESA_SOLICITANTE
			FROM SAC_CHAMADOS A
			INNER JOIN WEBTOOLS.EMPRESAS B ON B.COD_EMPRESA=A.COD_EMPRESA
			LEFT JOIN SAC_STATUS C ON C.COD_STATUS = A.COD_STATUS
			WHERE A.COD_CHAMADO = $cod_chamado_sql ";

// fnEscreve($sql);		
$qrUltimoCod = mysqli_fetch_assoc(mysqli_query($connAdmSAC->connAdm(), $sql));

if (isset($qrUltimoCod['COD_CONSULTOR']) && $qrUltimoCod['COD_CONSULTOR'] != "0") {
	$cod_consultores = $qrUltimoCod['COD_CONSULTOR'] . ',';
} else {
	$cod_consultores = "0";
}

if (isset($qrUltimoCod['COD_USUARIO_ORDENAC']) && $qrUltimoCod['COD_USUARIO_ORDENAC'] != "0") {
	$cod_usuarios_env = $qrUltimoCod['COD_USUARIO_ORDENAC'] . ',';
} else {
	$cod_usuarios_env = "0";
}

if (isset($qrUltimoCod['COD_USUARIOS_ENV']) && $qrUltimoCod['COD_USUARIOS_ENV'] != "0" && $todos != 'N') {
	if ($cod_usuarios_env != "0") {
		$cod_usuarios_env .= $qrUltimoCod['COD_USUARIOS_ENV'];
	} else {
		$cod_usuarios_env = $qrUltimoCod['COD_USUARIOS_ENV'];
	}
}

if (isset($qrUltimoCod['COD_CONSULTORES']) && $qrUltimoCod['COD_CONSULTORES'] != "0") {
	$cod_consultores .= $qrUltimoCod['COD_CONSULTORES'];
}

$cod_consultores = implode(',', array_unique(explode(',', rtrim($cod_consultores))));
$cod_usuarios_env = implode(',', array_unique(explode(',', rtrim($cod_usuarios_env))));

$sqlEmailCon = "SELECT DES_EMAILUS FROM USUARIOS WHERE COD_USUARIO IN($cod_usuarios_env) UNION SELECT DES_EMAILUS FROM USUARIOS WHERE COD_USUARIO IN($cod_consultores)";
// fnEscreve($sqlEmailCon);
$arrayEmail = mysqli_query($connAdm->connAdm(), $sqlEmailCon);

while (@$qrEmail = mysqli_fetch_assoc($arrayEmail)) {
	$emailsEnvolv .= $qrEmail['DES_EMAILUS'] . ";";
}
$emailsEnvolv = rtrim($emailsEnvolv, ';');

if ($tipo_email == "Comentado") {
	if ($tp_comentario != 2) {
		$ultimoComent = "";
		@$sqlComent = "SELECT DES_COMENTARIO AS DES_COMENTARIO FROM SAC_COMENTARIO WHERE COD_COMENTARIO = (SELECT MAX(COD_COMENTARIO) FROM SAC_COMENTARIO WHERE COD_CHAMADO = $qrUltimoCod[COD_CHAMADO])";
		// fnEscreve($sqlComent);
		@$qrComent = mysqli_fetch_assoc(mysqli_query($connAdmSAC->connAdm(), $sqlComent));

		$ultimoComent = "<span style='font-size: 14px;'>Comentário: <i>" . substr(html_entity_decode($qrComent['DES_COMENTARIO']), 0, 250) . "</i><span style='font-size: 8px'>...</span></span>
							 <div style='clear: both; height: 5px;'/>
							 ";
	}
} else {
	$ultimoComent = "";
}

//MONTAGEM DO E-MAIL
include './externo/email/envio_sac.php';

if ($todos != 'N') {
	$emailsEnvolv .= ";" . $qrUltimoCod['EMAIL_SOLICITANTE'];
	$cod_usuario = $qrUltimoCod['COD_USUARIO'];
} else {
	if ($qrUltimoCod['EMPRESA_SOLICITANTE'] == 2 || $qrUltimoCod['EMPRESA_SOLICITANTE'] == 3) {
		$emailsEnvolv .= ";" . $qrUltimoCod['EMAIL_SOLICITANTE'];
		$cod_usuario = $qrUltimoCod['COD_USUARIO'];
	}
}
// echo "<pre>";
// fnEscreve($emailsEnvolv);
// echo "</pre>";

//destinatários
$emailDestino = array('email1' => 'suporte@markafidelizacao.com.br', 'email2' => @$des_email, 'email3' => $qrUltimoCod['EMAIL_RESPONSAVEL'], 'email4' => '', 'email5' => $emailsEnvolv);
$dtEnvio = new DateTime();

$texto_envio = "					
	<h3 style='font-size: 18px;'>Chamado #" . $qrUltimoCod['COD_CHAMADO'] . " - " . $qrUltimoCod['NOM_CHAMADO'] . "</h3>
	<span style='font-size: 14px;'>" . substr(html_entity_decode($qrUltimoCod['DES_SAC']), 0, 250) . "<span style='font-size: 8px'>...</span></span>
	<div style='clear: both; height: 5px;'/>
	<span style='font-size: 14px;'>
	Empresa: <b>" . $qrUltimoCod['NOM_FANTASI'] . "</b>  <div style='clear: both; height: 8px;'/>
	" . $tipo_email . " em: " . $dtEnvio->format('d/m/Y H:i:s') . " <div style='clear: both; height: 15px;'/> </span>
	<span style='font-size: 14px;'>Status: <i>" . $qrUltimoCod['DES_STATUS'] . "</i></span>
	<div style='clear: both; height: 5px;'/>
	" . $ultimoComent . "								
	<div style='background-color: #3498DB; padding: 8px 8px 8px 8px; border-radius: 5px; width: 150px; text-align: center; '>
	<a href='https://adm.bunker.mk/action.php?mod=" . fnEncode($modHD) . "&id=" . fnEncode($cod_empresa) . "&idC=" . fnEncode($qrUltimoCod['COD_CHAMADO']) . "'  
	target='_blank'
	style='font-size: 15px; color: white; text-decoration: none;'
	>&nbsp; Acessar Chamado &nbsp;</a> </div> ";

fnsacmail(
	$emailDestino,
	'Suporte Marka',
	"<html>" . $texto_envio . "</html>",
	$novo_chamado . 'Chamado #' . $qrUltimoCod['COD_CHAMADO'],
	'Help Desk Bunker',
	$connAdm->connAdm(),
	connTemp($cod_empresa, ""),
	'3'
);

// echo "<pre>";
// print_r($emailDestino);			
// echo "</pre>";

$tip_notifica = "SAC";
$nom_chamado = $qrUltimoCod['NOM_CHAMADO'];
$cod_chamado = $qrUltimoCod['COD_CHAMADO'];
$cod_usures = fnLimpaCampoZero($qrUltimoCod['COD_USURES']);

include "notificacao.php";
