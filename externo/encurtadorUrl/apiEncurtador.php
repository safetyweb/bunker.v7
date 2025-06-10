<?php
include_once '../../_system/_functionsMain.php';
include_once '../../_system/_FUNCTION_WS.php';

function consultaCliente($CPF, $EMPRESA, $LOGIN, $SENHA, $COD_UNIVEND)
{

    $xml = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:fid="fidelidade" xmlns:S="http://schemas.xmlsoap.org/soap/envelope/">
                            <soapenv:Header/>
                                <soapenv:Body>
                                <fid:BuscaConsumidor xmlns:ns2="fidelidade">
                                    <fase>fase1</fase>
                                    <opcoesbuscaconsumidor>
                                        <cartao>' . $CPF . '</cartao>
                                        <cpf>' . $CPF . '</cpf>
                                    </opcoesbuscaconsumidor>
                                    <dadoslogin>
                                        <login>' . $LOGIN . '</login>
                                        <senha>' . $SENHA . '</senha>
                                        <idloja>' . $COD_UNIVEND . '</idloja>
                                        <idmaquina/>
                                        <idcliente>' . $EMPRESA . '</idcliente>
                                        <codvendedor/>
                                        <nomevendedor/>
                                    </dadoslogin>
                                </fid:BuscaConsumidor>
                            </soapenv:Body>
                        </soapenv:Envelope>';
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "http://soap.bunker.mk/?wsdl=",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $xml,
        CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
            "content-type: text/xml",
            "postman-token: 578a6edd-959d-e00b-e1db-20e3518425e1"
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        $msg = "cURL Error #:" . $err;
        $arraycpf = array('msg' => $msg);
    } else {

        $doc = new DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTML($response);
        libxml_clear_errors();
        $xml = $doc->saveXML($doc->documentElement);
        $xml = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);

        // Converter o SimpleXMLElement para JSON e depois para array
        $json = json_encode($xml);
        $array = json_decode($json, true);

        return $array;
    }
}

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Methods: POST");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$connadmtemp = $connAdm->connAdm();

$data = [];
$data["errors"] = [];

if ($_REQUEST["idr"] == "") {
    $data["errors"]["message"] = "Url inválida!";
    http_response_code(400);
    echo json_encode($data);
    exit;
} else {
    $idr = fnLimpaCampo(short_url_decode($_REQUEST["idr"]));
    $sql = "SELECT url_original, tip_url, cod_empresa, titulo, cod_campanha FROM TAB_ENCURTADOR WHERE ID = '$idr' AND cod_exclusa IS NULL";
    $query = mysqli_query($connadmtemp, $sql);

    if (mysqli_num_rows($query) > 0) {
        $row = mysqli_fetch_assoc($query);
        $cdr = "";
        $cod_campanha = $row['cod_campanha'];
        $cod_cliente = '';

        if (isset($_REQUEST["cdr"]) && $_REQUEST["cdr"] != "") {
            $cdr = fnLimpaCampo(fnEncode($_REQUEST["cdr"]));
            $cod_cliente = fnLimpaCampo($_REQUEST["cdr"]);
        }

        switch ($row['tip_url']) {

            case 'NPS':
                $andParam = "";

                if ($cdr != "") {
                    $andParam = "&idc=" . $cdr;
                }
                $url = $row['url_original'];
                $data["url"] = $url . $andParam;
                http_response_code(200);
                echo json_encode($data);
                break;

            case 'TKT':

                if ($cod_cliente != '') {
                    //SE TIVER CODIGO DO CLIENTE, CONSULTA SOAP DO DIOGO PARA BUSCAR A URL DO TICKET DO CLIENTE
                    $sql = "SELECT COD_UNIVEND, NUM_CGCECPF FROM CLIENTES WHERE COD_CLIENTE = $cod_cliente AND COD_EMPRESA = " . $row['cod_empresa'];
                    $arrayQuery = mysqli_query(connTemp($row['cod_empresa'], ""), $sql);
                    if (mysqli_num_rows($arrayQuery) > 0) {
                        $rowCliente = mysqli_fetch_assoc($arrayQuery);
                        $cod_univend = $rowCliente['COD_UNIVEND'];
                        $cpf = $rowCliente['NUM_CGCECPF'];

                        $sqlWs = "SELECT LOG_USUARIO,DES_SENHAUS,COD_EMPRESA FROM usuarios  WHERE cod_empresa=" . $row['cod_empresa'] . " AND COD_TPUSUARIO=10 AND log_estatus='S' AND FIND_IN_SET('18', COD_UNIVEND) limit 1";
                        $queryWs = mysqli_query($connadmtemp, $sqlWs);
                        $qrResult = mysqli_fetch_assoc($queryWs);
                        $log_usuario = $qrResult['LOG_USUARIO'];
                        $des_senhaus = fnDecode($qrResult['DES_SENHAUS']);

                        $response = consultaCliente($cpf, $row['cod_empresa'], $log_usuario, $des_senhaus, $cod_univend);
                        $url = $response["body"]["envelope"]["body"]["buscaconsumidorresponse"]["buscaconsumidorresponse"]["acao_b_ticket_de_ofertas"]["url_ticketdeofertas"];

                        if ($url != "") {
                            $data["url"] = $url . "&print=no";
                            http_response_code(200);
                            echo json_encode($data);
                            exit;
                        }

                        $data["errors"]["message"] = "Ocorreu um erro, se persistir entre em contato com o suporte!";
                        http_response_code(400);
                        echo json_encode($data);
                        exit;
                    } else {
                        $data["errors"]["message"] = "Ocorreu um erro, se persistir entre em contato com o suporte!";
                        http_response_code(400);
                        echo json_encode($data);
                        exit;
                    }
                } else {
                    //SE NÃO TIVER CLIENTE, RETORNA A URL AVULSA
                    $url = $row['url_original'];
                    $data["url"] = $url . $andParam;
                    http_response_code(200);
                    echo json_encode($data);
                }
                break;

            default:
                //SE FOR OUTRO TIPO DE URL, RETORNA A URL AVULSA
                $url = $row['url_original'];
                $data["url"] = $url;
                http_response_code(200);
                echo json_encode($data);
                break;
        }

        $sis_operacional = '';
        if (isset($_REQUEST['SIS_OPERACIONAL']) && $_REQUEST['SIS_OPERACIONAL'] != '') {
            $sis_operacional = $_REQUEST["SIS_OPERACIONAL"];
        }

        $ip = '';
        if (isset($_REQUEST['IP']) && $_REQUEST['IP'] != '') {
            $ip = $_REQUEST["IP"];
        }

        $country = '';
        if (isset($_REQUEST['COUNTRY']) && $_REQUEST['COUNTRY'] != "") {
            $country = $_REQUEST["COUNTRY"];
        }
        $country_code = '';
        if (isset($_REQUEST['COUNTRY_CODE']) && $_REQUEST['COUNTRY_CODE'] != "") {
            $country_code = $_REQUEST["COUNTRY_CODE"];
        }
        $region = '';
        if (isset($_REQUEST['REGION']) && $_REQUEST['REGION'] != "") {
            $region = $_REQUEST["REGION"];
        }
        $region_name = '';
        if (isset($_REQUEST['REGION_NAME']) && $_REQUEST['REGION_NAME'] != "") {
            $region_name = $_REQUEST["REGION_NAME"];
        }
        $city = '';
        if (isset($_REQUEST['CITY']) && $_REQUEST['CITY'] != "") {
            $city = $_REQUEST["CITY"];
        }
        $lat = fnValorSql(0);
        if (isset($_REQUEST['LAT']) && $_REQUEST['LAT'] != "") {
            $lat = $_REQUEST["LAT"];
        }
        $lon = fnValorSql(0);
        if (isset($_REQUEST['LON']) && $_REQUEST['LON'] != "") {
            $lon = $_REQUEST["LON"];
        }

        //ADICIONA NO CONTADOR
        $sqlInsere = "INSERT INTO CLICK_LINKSENCURTADO (
        TITULO,
        COD_CLIENTE, 
        COD_EMPRESA, 
        DAT_CADASTR, 
        COD_ENCURTADOR, 
        DES_LINK, 
        COD_CAMPANHA,
        TIP_URL,
        SIS_OPERACIONAL,
        IP,
        COUNTRY,
        COUNTRY_CODE,
        REGION,
        REGION_NAME,
        CITY,
        LAT,
        LON
        )VALUES(
        '" . $row['titulo'] . "',
        '$cod_cliente',
        " . $row['cod_empresa'] . ",
        now(),
        '$idr',
        '$url',
        '$cod_campanha',
        '" . $row['tip_url'] . "',
        '$sis_operacional',
        '$ip',
        '$country',
        '$country_code',
        '$region',
        '$region_name',
        '$city',
        $lat,
        $lon
        )";
        mysqli_query(connTemp($row['cod_empresa'], ""), $sqlInsere);

        exit;
    }

    $data["errors"]["message"] = "Url inválida!";
    http_response_code(400);
    echo json_encode($data);
    exit;
}
