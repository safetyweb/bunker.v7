<?php
include 'header.php';
include 'functions.php';

$json = file_get_contents('php://input');

$data = json_decode($json, true);

if($data === null) {
    $data["errors"]["message"] = "Erro ao decodificar o arquivo.";
    http_response_code(400);
    // echo json_encode($data);
    exit;
}

$diasemana = array('Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab');
$canalWhats = fnLimpaCampoZero($_GET["cal"]);
$num_celular = fnLimpaCampo($_GET["numC"]);
$cod_comod = fnDecode($_GET["comodidades"]);
$idLead = fnLimpaCampoZero($_GET['idL']);
$qrQuarto = $data;

if ($canalWhats == 0 || $canalWhats == "") {
    $canalWhats = 1;
}

$comodidades = explode(",", $cod_comod);
asort($comodidades, SORT_NUMERIC);
$arrayComodidades = array();

$sqlDesc = "SELECT NOM_FANTASI FROM WEBTOOLS.UNIDADEVENDA 
            WHERE COD_EMPRESA = 274
            AND LOG_ESTATUS = 'S'
            AND COD_EXTERNO = ".$qrQuarto["idHotel"];
$arrayDesc = mysqli_query($connAdm->connAdm(), $sqlDesc);
$qrDesc = mysqli_fetch_assoc($arrayDesc);

$hotel = $qrDesc["NOM_FANTASI"];

foreach ($comodidades as $cod_comod) {

    $sqlComod = "SELECT DES_COMOD FROM COMODIDADES_ADORAI
                 WHERE COD_EMPRESA = 274
                 AND COD_COMOD = $cod_comod";

    $arrayComod = mysqli_query(connTemp(274, ''), $sqlComod);
    $qrComod = mysqli_fetch_assoc($arrayComod);

    array_push($arrayComodidades, $qrComod[DES_COMOD]);
}

$chale = $qrQuarto["chale"];
$imagem = $qrQuarto["imagem"];

$linkEnvio = "https://roteirosadorai.com.br/detalhes.php?datI=" . fnDataShort($qrQuarto["dataMin"]) . "&datF=" . fnDataShort($qrQuarto["dataMax"]) . "&idh=" . $qrQuarto["idHotel"] . "&idc=" . $qrQuarto["idQuarto"] . "&ccm=" . $cod_comod . "&idL=" . $idLead . "&numC=" . $num_celular . "&infQ=" . base64_encode(json_encode($qrQuarto));

$linkEnvio = file_get_contents("http://tinyurl.com/api-create.php?url=".$linkEnvio);

$msgEnvio = "Local: *" . $hotel . "*<br />Chalé: *" . $chale . "*<br /><br />Período: *" . $diasemana[date('w', strtotime($qrQuarto["dataMin"]))] . " " . fnDataShort($qrQuarto["dataMin"]) . " à " . $diasemana[date('w', strtotime($qrQuarto["dataMax"]))] . " " . fnDataShort($qrQuarto["dataMax"]) . "*<br />";

foreach ($arrayComodidades as $comodidade) {

    if ($countComod == 5) {
        break;
    }

    $msgEnvio .= "<br />• " . $comodidade;

    $countComod++;
}

$msgEnvio .= "<br /><br />Para consultar *valores*, ver *fotos*, detalhes e garantir sua reserva, *clique no link*: 👇<br />$linkEnvio";

$msgsbtr = nl2br($msgEnvio, true);
$msgsbtr = str_replace('<br />',"\n", $msgsbtr);

// fnEscreve2($msgsbtr);

include "../_system/whatsapp/wstAdorai.php";

$sql = "SELECT *
        from SENHAS_WHATSAPP
        WHERE COD_EMPRESA = 274
        AND COD_UNIVEND = $canalWhats
        ORDER BY COD_SENHAPARC DESC LIMIT 1";

$arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

$qrBuscaModulos = mysqli_fetch_assoc($arrayQuery);

$session = $qrBuscaModulos['NOM_SESSAO'];
$des_token = $qrBuscaModulos[DES_TOKEN];
$des_authkey = $qrBuscaModulos[DES_AUTHKEY];
$log_login = $qrBuscaModulos[LOG_LOGIN];
$port = $qrBuscaModulos[PORT_SERVICAO];

if ($imagem != "") {

    // echo "imagem";

    $media = $imagem;
    $nomArq = explode("/", $imagem);

    $nomArq = end($nomArq);

    // fnEscreve2($nomArq);
    // fnEscreve($qrQuarto['chale']);
    // fnEscreve($ext);

    // $resultcreate=sendMedia($session, $des_authkey, '55'.$num_celular, 3, 'image', $chale, $msgsbtr, $imagem);
    $resultcreate = sendMedia($session, $des_authkey, $num_celular, 3, 'image', $nomArq, $msgsbtr, $imagem, $port);
} else {
    // echo "texto";
    $resultcreate = FnsendText($session, $des_authkey, $num_celular, $msgsbtr, 3, $port);
    // $retorno = FnsendText($session,$des_authkey,'55'.$dadosArray[number],$dadosArray[message],$tempo_aleatorio,$port);
}

// $retorno = FnsendText($qrBuscaModulos[NOM_SESSAO], $qrBuscaModulos[DES_AUTHKEY], $num_celular, $msgsbtr, 3);

echo "<pre>";
print_r($resultcreate);
echo "</pre>";
$data["errors"]["message"] = "ok";
http_response_code(200);
echo json_encode($resultcreate);

