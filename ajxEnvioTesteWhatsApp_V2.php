<?php

include '_system/_functionsMain.php';

//fnDebug("true");
$cod_empresa = fnLimpaCampoZero(fnDecode($_GET['id']));
$cod_campanha = fnLimpaCampoZero(fnDecode($_GET['idc']));
$cod_usucada = $_SESSION['SYS_COD_USUARIO'];

$sql = "SELECT SENHAS_WHATSAPP.*
from SENHAS_WHATSAPP
WHERE COD_EMPRESA = $cod_empresa
LIMIT 1";

// fnEscreve($sql);
$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

$count = 0;
$qrBuscaModulos = mysqli_fetch_assoc($arrayQuery);

$session = $qrBuscaModulos['NOM_SESSAO'];

//if($qrBuscaModulos[COD_UNIVEND] != 0 && $qrBuscaModulos[COD_UNIVEND] != ""){
//   $session = $cod_empresa."_".$qrBuscaModulos[COD_UNIVEND];
// }

$des_token = $qrBuscaModulos[DES_TOKEN];
$des_authkey = $qrBuscaModulos[DES_AUTHKEY];
$log_login = $qrBuscaModulos[LOG_LOGIN];
$port = $qrBuscaModulos[PORT_SERVICAO];

// echo "EM MANUTENÇÃO";       	
// exit();


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

$sqlDec = "SELECT TIP_RETORNO, NUM_DECIMAIS_B FROM empresas where COD_EMPRESA = $cod_empresa";
//fnEscreve($sql);
$arrayQuery = mysqli_query($connAdm->connAdm(), $sqlDec);
$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);

if (isset($arrayQuery)) {

	$tip_retorno = $qrBuscaEmpresa['TIP_RETORNO'];
	$num_decimais_b = $qrBuscaEmpresa['NUM_DECIMAIS_B'];

	if ($tip_retorno == 1) {
		$casasDec = 0;
	} else {
		$casasDec = $qrBuscaEmpresa['NUM_DECIMAIS_B'];
	}
}

// fnEscreve($casasDec);

// exit();

$sql = "SELECT DES_CAMPANHA FROM CAMPANHA WHERE COD_EMPRESA = $cod_empresa AND COD_CAMPANHA = $cod_campanha";

$qrCamp = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa, ''), $sql));

$des_campanha = preg_replace('/\s+/', '_', fnAcentos($qrCamp['DES_CAMPANHA']));

$des_campanha = str_replace('/', '.', $des_campanha);

$sql = "SELECT CP.DES_CAMPANHA, 
CP.DAT_INI, 
CP.HOR_INI,
CP.COD_EXT_CAMPANHA, 
CP.COD_CAMPANHA, 
CP.COD_EMPRESA, 
ECA.COD_PERSONAS,
TE.COD_TEMPLATE,
TE.DES_IMAGEM,
TE.NOM_TEMPLATE AS TITULO_MSG,
TE.DES_TEMPLATE AS HTML,
DES_TEMPLATE2 AS HTML2,
DES_TEMPLATE3 AS HTML3, 
DES_TEMPLATE4 AS HTML4, 
DES_TEMPLATE5 AS HTML5 
FROM CAMPANHA CP
INNER JOIN WHATSAPP_CONTROLE_AUX ECA ON ECA.COD_CAMPANHA = CP.COD_CAMPANHA
INNER JOIN TEMPLATE_WHATSAPP TE ON TE.COD_TEMPLATE = ECA.COD_TEMPLATE
WHERE CP.COD_EMPRESA = $cod_empresa
AND CP.COD_CAMPANHA = $cod_campanha";
// AND ME.LOG_PRINCIPAL = 'S'";
// fnEscreve($sql);
$qrMsg = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa, ''), $sql));
$des_imagem = $qrMsg['DES_IMAGEM'];
$des_titulo = $qrMsg['DES_TITULO'];

$tagsPersonaliza = procpalavras($qrMsg['HTML'], $connAdm->connAdm());

$nomeRel = $cod_empresa . '_' . date("YmdHis") . "_" . $des_campanha . "_CONTROLE.csv";
$arquivo = '_system/ibope/listas_envio/' . $nomeRel;
$caminhoRelat = '_system/ibope/listas_envio/';

$tagsPersonaliza = '<#EMAIL>,<#CODIGO>,' . $tagsPersonaliza;

$tags = explode(',', $tagsPersonaliza);

$selectCliente = "";
$idDisparo = date('Ymd');
$insertListaRet = "";

for ($i = 0; $i < count($tags); $i++) {
	// fnEscreve($tags[$i]);
	switch ($tags[$i]) {

		case '<#NOME>';
			$selectCliente .= "C.NOM_CLIENTE,";
			break;
		case '<#CARTAO>';
			$selectCliente .= "";
			break;
		case '<#ESTADOCIVIL>';
			$selectCliente .= "";
			break;
		case '<#SEXO>';
			$selectCliente .= "";
			break;
		case '<#PROFISSAO>';
			$selectCliente .= "";
			break;
		case '<#NASCIMENTO>';
			$selectCliente .= "";
			break;
		case '<#ENDERECO>';
			$selectCliente .= "";
			break;
		case '<#NUMERO>';
			$selectCliente .= "";
			break;
		case '<#BAIRRO>';
			$selectCliente .= "";
			break;
		case '<#CIDADE>';
			$selectCliente .= "";
			break;
		case '<#ESTADO>';
			$selectCliente .= "";
			break;
		case '<#CEP>';
			$selectCliente .= "";
			break;
		case '<#COMPLEMENTO>';
			$selectCliente .= "";
			break;
		case '<#TELEFONE>';
			$selectCliente .= "";
			break;
		case '<#CELULAR>';
			$selectCliente .= "";
			break;
		case '<#SALDO>';
			$selectCliente .= "FORMAT(TRUNCATE(IFNULL((
		SELECT IFNULL((
		SELECT  SUM(val_saldo)
		FROM creditosdebitos f
		WHERE f.cod_cliente = cred.cod_cliente AND 
		f.tip_credito = 'C' AND 
		f.cod_statuscred = 1 AND 
		f.tip_campanha = cred.tip_campanha AND 
		((f.log_expira = 'S' AND DATE_FORMAT(f.dat_expira, '%Y-%m-%d') >= DATE_FORMAT(NOW(), '%Y-%m-%d')) OR (f.log_expira = 'N'))),0)+ IFNULL((
		SELECT SUM(val_saldo)
		FROM creditosdebitos_bkp g
		WHERE g.cod_cliente = cred.cod_cliente AND g.tip_credito = 'C' AND g.cod_statuscred = 1 AND g.tip_campanha = cred.tip_campanha AND ((g.log_expira = 'S' AND DATE_FORMAT(g.dat_expira, '%Y-%m-%d') >= DATE_FORMAT(NOW(), '%Y-%m-%d')) OR (g.log_expira = 'N'))),0)
		FROM creditosdebitos cred
		WHERE cred.cod_cliente=C.cod_CLIENTE
		GROUP BY cred.cod_cliente),0),$casasDec),$casasDec,'pt_BR') AS CREDITO_DISPONIVEL, ";
			break;
		case '<#PRIMEIRACOMPRA>';
			$selectCliente .= "";
			break;
		case '<#ULTIMACOMPRA>';
			$selectCliente .= "";
			break;
		case '<#TOTALCOMPRAS>';
			$selectCliente .= "";
			break;
		case '<#CODIGO>';
			$selectCliente .= "C.COD_CLIENTE,";
			break;
		case '<#CUPOMSORTEIO>';
			$selectCliente .= "";
			break;
		case '<#CUPOM_INDICACAO>';
			$selectCliente .= "";
			break;
		case '<#NUMEROLOJA>';
			$selectCliente .= "";
			break;
		case '<#BAIRROLOJA>';
			$selectCliente .= "";
			break;
		case '<#NOMELOJA>';
			$selectCliente .= "C.COD_UNIVEND,";
			break;
		case '<#ENDERECOLOJA>';
			$selectCliente .= "";
			break;
		case '<#TELEFONELOJA>';
			$selectCliente .= "";
			break;
		case '<#ANIVERSARIO>';
			$selectCliente .= "C.DAT_NASCIME,";
			break;
		case '<#DATAEXPIRA>';
			$selectCliente .= "(SELECT 
		MIN(DAT_EXPIRA) AS DAT_EXPIRA
		FROM creditosdebitos 
		WHERE DAT_EXPIRA >= NOW() AND  cod_CLIENTE=C.cod_CLIENTE) AS DAT_EXPIRA,";
			break;
		default:
			$selectCliente .= "C.COD_CLIENTE, C.NUM_CELULAR, C.NOM_CLIENTE, C.COD_UNIVEND,";
			break;
	}
}

$selectCliente = rtrim(trim($selectCliente), ',');

// echo'<pre>';
// print_r($tags);
// echo'</pre>';


$sql = "SELECT $selectCliente
FROM clientes C 
INNER JOIN WHATSAPP_CONTROLE EC ON EC.COD_CLIENTE = C.COD_CLIENTE
WHERE EC.COD_EMPRESA = $cod_empresa 
AND EC.COD_CAMPANHA = $cod_campanha";
// AND EC.COD_CLIENTE IN($cod_clientes)";

// fnEscreve($sql);
// fnEscreve($arquivo);

$arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sql);

// fnEscreve('chegou 2');

$array = array();
$linhas = 0;

// fnEscreve('chega aqui');

// include "autenticaNexux.php";
// retorna: $usuario, $senha, $cliente_externo e $parc_cadastrado(0/1)
// fnEscreve('chega aqui - autentica');

// if($parc_cadastrado == 0){
// 	fnEscreve("Parceiro não cadastrado na empresa");
// }

$sqlInsert = "";

$dat_envio = date("Y-m-d H:i:s", strtotime("+ 10 seconds"));
$dat_envio = date("Y-m-d H:i:s", strtotime("- 1 hour"));

while ($row = mysqli_fetch_assoc($arrayQuery)) {

	$linha = "";

	for ($i = 0; $i < count($tags); $i++) {
		// fnEscreve($tags[$i]);
		switch ($tags[$i]) {

			case '<#NOME>';
				$nome = explode(' ', $row['NOM_CLIENTE']);
				$itemLinha = ucfirst(strtolower($nome[0]));
				break;
			case '<#CARTAO>';
				$itemLinha = "DES_EMAILUS AS '<#EMAIL>',";
				break;
			case '<#ESTADOCIVIL>';
				$itemLinha = "DES_EMAILUS AS '<#EMAIL>',";
				break;
			case '<#SEXO>';
				$itemLinha = "DES_EMAILUS AS '<#EMAIL>',";
				break;
			case '<#PROFISSAO>';
				$itemLinha = "DES_EMAILUS AS '<#EMAIL>',";
				break;
			case '<#NASCIMENTO>';
				$itemLinha = $row['DAT_NASCIME'];
				break;
			case '<#ENDERECO>';
				$itemLinha = "DES_EMAILUS AS '<#EMAIL>',";
				break;
			case '<#NUMERO>';
				$itemLinha = "DES_EMAILUS AS '<#EMAIL>',";
				break;
			case '<#BAIRRO>';
				$itemLinha = "DES_EMAILUS AS '<#EMAIL>',";
				break;
			case '<#CIDADE>';
				$itemLinha = "DES_EMAILUS AS '<#EMAIL>',";
				break;
			case '<#ESTADO>';
				$itemLinha = "DES_EMAILUS AS '<#EMAIL>',";
				break;
			case '<#CEP>';
				$itemLinha = "DES_EMAILUS AS '<#EMAIL>',";
				break;
			case '<#COMPLEMENTO>';
				$itemLinha = "DES_EMAILUS AS '<#EMAIL>',";
				break;
			case '<#TELEFONE>';
				$itemLinha = "DES_EMAILUS AS '<#EMAIL>',";
				break;
			case '<#CELULAR>';
				$itemLinha = "DES_EMAILUS AS '<#EMAIL>',";
				break;
			case '<#SALDO>';
				$itemLinha = fnValor($row['CREDITO_DISPONIVEL'], $casasDec);
				break;
			case '<#PRIMEIRACOMPRA>';
				$itemLinha = "DES_EMAILUS AS '<#EMAIL>',";
				break;
			case '<#ULTIMACOMPRA>';
				$itemLinha = "DES_EMAILUS AS '<#EMAIL>',";
				break;
			case '<#TOTALCOMPRAS>';
				$itemLinha = "DES_EMAILUS AS '<#EMAIL>',";
				break;
			case '<#CODIGO>';
				$itemLinha = $row['COD_CLIENTE'];
				break;
			case '<#CUPOMSORTEIO>';
				$itemLinha = "DES_EMAILUS AS '<#EMAIL>',";
				break;
			case '<#CUPOM_INDICACAO>';
				$itemLinha = "DES_EMAILUS AS '<#EMAIL>',";
				break;
			case '<#NUMEROLOJA>';
				$itemLinha = "DES_EMAILUS AS '<#EMAIL>',";
				break;
			case '<#BAIRROLOJA>';
				$itemLinha = "DES_EMAILUS AS '<#EMAIL>',";
				break;
			case '<#NOMELOJA>';
				$NOM_ARRAY_UNIDADE = (array_search($row['COD_UNIVEND'], array_column($ARRAY_UNIDADE, 'COD_UNIVEND')));
				$itemLinha = fnAcentos($ARRAY_UNIDADE[$NOM_ARRAY_UNIDADE]['nom_fantasi']);
				break;
			case '<#ENDERECOLOJA>';
				$itemLinha = "DES_EMAILUS AS '<#EMAIL>',";
				break;
			case '<#TELEFONELOJA>';
				$itemLinha = "DES_EMAILUS AS '<#EMAIL>',";
				break;
			case '<#ANIVERSARIO>';
				$itemLinha = substr($row['DAT_NASCIME'], 0, -5);
				break;
			case '<#DATAEXPIRA>';
				$itemLinha = fnDataShort($row['DAT_EXPIRA']);
				break;
			default:
				$itemLinha = $row['DES_EMAILUS'];
				break;
		}
		$linha .= $itemLinha . ";";
	}


	$rand = rand(1, 5);

	if ($rand == 1) {
		$rand = "";
	}

	switch ($rand) {
		case 1:
			$templateNro = "HTML";
			break;

		case 2:
			$templateNro = "HTML2";
			break;

		case 3:
			$templateNro = "HTML3";
			break;

		case 4:
			$templateNro = "HTML4";
			break;

		case 5:
			$templateNro = "HTML5";
			break;

		default:
			$templateNro = "HTML";
			break;
	}

	$newRow[] = rtrim($linha, ';');
	$linhas++;

	$msgCli = $qrMsg[$templateNro];

	$NOM_CLIENTE = explode(" ", ucfirst(strtolower(fnAcentos($row['NOM_CLIENTE']))));
	$TEXTOENVIO = str_replace('<#NOME>', $NOM_CLIENTE[0], $msgCli);
	$TEXTOENVIO = str_replace('<#CODCLIENTE>', $row['COD_CLIENTE'], $TEXTOENVIO);
	$TEXTOENVIO = str_replace('<#SALDO>', $row['CREDITO_DISPONIVEL'], $TEXTOENVIO);
	$TEXTOENVIO = str_replace('<#NOMELOJA>',  $row['NOM_FANTASI'], $TEXTOENVIO);
	$TEXTOENVIO = str_replace('<#ANIVERSARIO>', $row['DAT_NASCIME'], $TEXTOENVIO);
	$TEXTOENVIO = str_replace('<#DATAEXPIRA>', fnDataShort($row['DAT_EXPIRA']), $TEXTOENVIO);
	$TEXTOENVIO = str_replace('<#EMAIL>', $row['DES_EMAILUS'], $TEXTOENVIO);
	$msgsbtr = nl2br($TEXTOENVIO, true);
	$msgsbtr = str_replace('<br />', "\n", $msgsbtr);

	// fnEscreve($msgsbtr);


	// $nom_camp_msg=$qrMsg[COD_CAMPANHA].'||'.$qrMsg[COD_EMPRESA].'||'.$row[COD_CLIENTE].'||'.$qrMsg[COD_TEMPLATE];

	$CLIE_WHATSAPP_L[] = array(
		"type" => "text",
		"message" => "$msgsbtr",
		"token" => "$des_token",
		"session" => "$session",
		"number" => fnLimpaDoc($row['NUM_CELULAR'])
	);


	$insertListaRet .= "('" . $cod_empresa . "',
                         '" . $cod_campanha . "',       
                         '" . $row['NOM_CLIENTE'] . "',       
                         '" . $row['COD_UNIVEND'] . "',
                         '" . $row['COD_CLIENTE'] . "',
                         '" . $row['NUM_CELULAR'] . "',
                         0,
                         '" . $msgsbtr . "',
                         NOW(),
                         'S'  
                        ),";
}




// À PRINCÍPIO, NÃO HAVERÁ DÉBITOS
// $arraydebitos=array('quantidadeEmailenvio'=>$linhas,
//                     'COD_EMPRESA'=>$cod_empresa,
//                     'PERMITENEGATIVO'=>'N',
//                     'COD_CANALCOM'=>'2',
//                     'CONFIRMACAO'=>'S',
//                     'COD_CAMPANHA'=>$cod_campanha,    
//                     'LOG_TESTE'=> 'S',
//                     'DAT_CADASTR'=> date('Y-m-d H:i:s'),
//                     'CONNADM'=>$connAdm->connAdm()
//                     ); 

// $retornoDeb=FnDebitos($arraydebitos);

// if($retornoDeb['cod_msg'] == 1){

fngravacvs($newRow, $caminhoRelat, $nomeRel);

// include './_system/func_nexux/func_transacional.php';

// $mensagensContatos = rtrim($mensagensContatos,',');

// $sqlCont = "SELECT NUM_CONTADOR FROM contador WHERE NUM_TKT = 50";
// 				$arrayCont = mysqli_query(connTemp($cod_empresa,''),$sqlCont);
// 				$qrCont = mysqli_fetch_assoc($arrayCont);
// 				$contador = $qrCont['NUM_CONTADOR'];

// looping ENVIO -------------------------------------------------------------------------------------------------------------------------
// $CLIE_WHATSAPP_L[]=array("type"=> "text",
// 						 "message"=> "$msgsbtr",                   
// 						 "token"=> "$des_token",               
// 						 "number"=> "11946335250"
//                         );

//include_once '_system/whatsapp/wsp.php';
include_once '_system/whatsapp/wstAdorai.php';

// $retorno = FnEnvioText($session,$des_token,$CLIE_WHATSAPP_L);
//$retorno = FnEnvioMULT($session,$des_token,$CLIE_WHATSAPP_L);

// fnEscreve('chega aqui depois do include');

foreach ($CLIE_WHATSAPP_L as $key => $dadosArray) {
	$tempo_aleatorio = mt_rand(3, 20);

	if (empty($des_imagem)) {
		// fnEscreve('sem imagem');
		$retorno = FnsendText($session, $des_authkey, '55' . $dadosArray[number], $dadosArray[message], $tempo_aleatorio, $port);
	} else {
		// fnEscreve('com imagem');
		$retorno = sendMedia($session, $des_authkey, '55' . $dadosArray[number], $tempo_aleatorio, 'image', $des_imagem, $dadosArray[message], "https://img.bunker.mk/media/clientes/$cod_empresa/wpp/$des_imagem", $port);
	}

	// echo "<pre>";
	// // fnescreve("https://img.bunker.mk/media/clientes/$cod_empresa/wpp/$des_imagem");
	// print_r($retorno);
	// echo "</pre>";
}

// exit();

$insertListaRet = rtrim($insertListaRet, ',');

$sqlControle = "INSERT INTO WHATSAPP_LOTE(
								COD_CAMPANHA,
								COD_EMPRESA,
								COD_PERSONAS,
								COD_DISPARO_EXT,
								COD_LOTE,
								QTD_LISTA,
								COD_LISTA,
								NOM_ARQUIVO,
								DES_PATHARQ,
								LOG_TESTE,
								LOG_ENVIO,
								COD_USUCADA
								) VALUES(
								$cod_campanha,
								$cod_empresa,
								'$qrMsg[COD_PERSONAS]',
								" . date('Ymd') . ",
								0,
								'$linhas',
								(SELECT MAX(COD_LISTA) FROM WHATSAPP_PARAMETROS WHERE COD_EMPRESA = $cod_empresa AND COD_CAMPANHA = $cod_campanha),
								'$nomeRel',
								'$arquivo',
								'S',
								'S',
								$cod_usucada
								);

					UPDATE WHATSAPP_CONTROLE SET DAT_ENVIO = '$dat_envio'
					WHERE COD_EMPRESA = $cod_empresa 
					AND COD_CAMPANHA = $cod_campanha";

mysqli_multi_query(connTemp($cod_empresa, ''), $sqlControle);

if ($sqlInsertRel != "") {

	$sqlInsertRel = "INSERT INTO WHATSAPP_LISTA_RET(
                                    COD_EMPRESA,
                                    COD_CAMPANHA,                                                                               
                                    NOM_CLIENTE,
                                    COD_UNIVEND,
                                    COD_CLIENTE,
                                    NUM_CELULAR,
                                    ID_DISPARO,
                                    DES_MSG_ENVIADA	,
                                    DAT_CADASTR,
                                    LOG_TESTE
                                    )values $insertListaRet";
	// fnEscreve($sqlInsertRel);
	mysqli_query(connTemp($cod_empresa, ''), $sqlInsertRel);
}

$msgErro = fnDataFull($dat_envio);


// }else {
// 	echo $retornoDeb[MSG];
// }	
