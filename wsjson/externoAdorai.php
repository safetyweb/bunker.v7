<?php
include '../_system/_functionsMain.php';
header("Content-Type: application/json; charset=utf-8");
// $cod_quarto = 9999; // parametrizado
@$cod_hotel = $_REQUEST['COD_HOTEL'];
@$url_hotel = $_REQUEST['URL_HOTEL'];
@$cod_chale = $_REQUEST['COD_CHALE'];
@$url_chale = $_REQUEST['URL_CHALE'];
@$cod_comod = $_REQUEST['COD_COMOD'];
@$num_celular = $_REQUEST['numC'];
@$dat_inicio = $_REQUEST['dtI'];
@$dat_final = $_REQUEST['dtF'];
@$tipo = $_REQUEST['tipo'];
@$banner = $_REQUEST['banner'];
@$origem = $_REQUEST['origem'];
@$opcionais = $_REQUEST['OPCIONAIS'];
@$vl_pacote = base64_decode($_GET['vl']);
$cod_empresa = 274;

switch ($tipo) {

    case '1': // lista de chales

        $sqlDesc = "SELECT DES_QUARTO, DES_IMAGEM, DES_VIDEO FROM ADORAI_CHALES 
                    WHERE COD_EXTERNO = $cod_chale
                    AND COD_EXCLUSA = 0";
        $arrayDesc = mysqli_query(connTemp(274, ''), $sqlDesc);
        $qrDesc = mysqli_fetch_array($arrayDesc, MYSQLI_ASSOC);

        echo json_encode($qrDesc);

        break;

    case '2': // detalhes

        $andChale = "";

        if ($cod_chale != "") {
            $andChale = "AND AC.COD_EXTERNO = $cod_chale";
        }

        $sqlDesc = "SELECT AC.NOM_QUARTO,
                            DA.DES_CHAMADA,
                            AC.ID,
                            AC.DES_IMAGEM AS IMAGEM_MSG,
                            UV.NOM_FANTASI,
                            UV.COD_ESTADOF, 
                            DA.VAL_DIARIA,
                            DA.NUM_HOSPEDE,
                            DA.DES_TEMPLATE,
                            DA.COD_COMOD,
                            IA.NOM_IMAGEM,
                            IA.DES_IMAGEM
                    FROM ADORAI_CHALES AC
                    INNER JOIN detalhes_adorai DA ON DA.COD_CHALE = AC.ID AND DA.COD_EXCLUSA = 0
                    INNER JOIN imagens_adorai IA ON IA.COD_CHALE = AC.ID AND IA.COD_EXCLUSA = 0
                    INNER JOIN webtools.UNIDADEVENDA UV ON UV.COD_EXTERNO = AC.COD_HOTEL
                    WHERE AC.COD_EMPRESA = 274
                    AND UV.LOG_ESTATUS = 'S'
                    $andChale
                    AND AC.COD_EXCLUSA = 0";

        $arrayDesc = mysqli_query(connTemp(274, ''), $sqlDesc);

        $comodidades = "";
        $arrayChale = array();
        $arrayNomImagens = array();
        $arrayImagens = array();
        $arrayComodidades = array();
        $arrayIcones = array();

        while ($qrDesc = mysqli_fetch_assoc($arrayDesc)) {
            $arrayChale[COD_CHALE] = $qrDesc[ID];
            $arrayChale[NOM_QUARTO] = $qrDesc[NOM_QUARTO];
            $arrayChale[NOM_HOTEL] = $qrDesc[NOM_FANTASI];
            $arrayChale[COD_ESTADOF] = $qrDesc[COD_ESTADOF];
            $arrayChale[DES_CHAMADA] = $qrDesc[DES_CHAMADA];
            $arrayChale[VAL_DIARIA] = $qrDesc[VAL_DIARIA];
            $arrayChale[NUM_HOSPEDE] = $qrDesc[NUM_HOSPEDE];
            $arrayChale[DES_TEMPLATE] = $qrDesc[DES_TEMPLATE];
            $arrayChale[IMAGEM_MSG] = $qrDesc[IMAGEM_MSG];
            $arrayChale[COD_COMOD] = $qrDesc[COD_COMOD];
            $comodidades = explode(",", $qrDesc[COD_COMOD]);
            asort($comodidades, SORT_NUMERIC);
            // echo "<br>";
            // print_r($comodidades);
            // echo "<br>";
            array_push($arrayNomImagens, $qrDesc[NOM_IMAGEM]);
            array_push($arrayImagens, $qrDesc[DES_IMAGEM]);
        }

        // $comodidades = explode(",", $comodidades);

        foreach ($comodidades as $cod_comod) {

            $sqlComod = "SELECT DES_COMOD, DES_ICONE FROM COMODIDADES_ADORAI
                         WHERE COD_EMPRESA = 274
                         AND COD_COMOD = $cod_comod";

            $arrayComod = mysqli_query(connTemp(274, ''), $sqlComod);
            $qrComod = mysqli_fetch_assoc($arrayComod);

            array_push($arrayComodidades, $qrComod[DES_COMOD]);
            $arrayIcones[$qrComod[DES_COMOD]] = $qrComod[DES_ICONE];
        }

        $arrayChale[DES_COMOD] = $arrayComodidades;
        $arrayChale[DES_ICONES] = $arrayIcones;
        $arrayChale[DES_IMAGENS] = $arrayImagens;
        $arrayChale[NOM_IMAGENS] = $arrayNomImagens;

        echo json_encode($arrayChale);
        // echo $sqlDesc;

        break;

    case '3': // combo de hoteis filtro/propriedades

        $andExterno = "";

        if ($cod_hotel != "" && $cod_hotel != 0) {
            $andExterno .= " AND UV.COD_EXTERNO IN($cod_hotel)";
        }
        if ($url_hotel != "") {
            $andExterno .= " AND (UV.NOM_RESPONS IN('$url_hotel') OR UV.COD_EXTERNO IN('$url_hotel'))";
        }

        $sqlDesc = "SELECT UV.NOM_RESPONS AS 'URL', 
                            UV.COD_EXTERNO, 
                            UV.NOM_FANTASI, 
                            UV.COD_ESTADOF,
                            UV.NOM_RESPONS,
                            AP.DES_IMAGEM,
                            AP.TIP_PROPRIEDADE,
                            AP.META_TITLE,
                            AP.META_DESCRIPTION
                    FROM UNIDADEVENDA UV
                    LEFT JOIN ADORAI_PROPRIEDADES AP ON AP.COD_HOTEL = UV.COD_EXTERNO AND AP.COD_EXCLUSA = 0
                    WHERE UV.COD_EMPRESA = 274
                    AND UV.LOG_ESTATUS = 'S'
                    AND UV.COD_EXTERNO NOT IN(977,20)
                    $andExterno
                    ORDER BY NOM_FANTASI";
        // echo($sqlDesc);
        $arrayDesc = mysqli_query(connTemp(274, ''), $sqlDesc);

        $arrUnidades = array();
        $arrHoteis = array();

        while ($qrDesc = mysqli_fetch_assoc($arrayDesc)) {
            $arrHoteis[COD_EXTERNO] = $qrDesc[COD_EXTERNO];
            $arrHoteis[NOM_FANTASI] = $qrDesc[NOM_FANTASI];
            $arrHoteis[COD_ESTADOF] = $qrDesc[COD_ESTADOF];
            $arrHoteis[URL] = $qrDesc[URL];
            $arrHoteis[NOM_RESPONS] = $qrDesc[NOM_RESPONS];
            $arrHoteis[DES_IMAGEM] = $qrDesc[DES_IMAGEM];
            $arrHoteis[TIP_PROPRIEDADE] = $qrDesc[TIP_PROPRIEDADE];
            $arrHoteis[META_TITLE] = $qrDesc[META_TITLE];
            $arrHoteis[META_DESCRIPTION] = $qrDesc[META_DESCRIPTION];
            $arrHoteis[H] = $url_hotel;
            $arrHoteis[SQL] = $sqlDesc;
            array_push($arrUnidades, $arrHoteis);
            unset($arrHoteis);
        }

        echo json_encode($arrUnidades);

        break;

    case '4': // locais/chales em destaque

        $andDestaque = "and AC.LOG_HOME = 'S'";

        if ($cod_hotel > 0) {
            $andDestaque = "and UV.COD_EXTERNO = $cod_hotel";
            if ($cod_hotel == 99) {
                $andDestaque = "";
            }
        }

        if ($banner != "") {
            $andDestaque = "and AC.LOG_BANNER = 'S'";
        }

        if ($url_chale != "") {
            $url_chale = str_replace("-", " ", $url_chale);
            $andDestaque = "AND NOM_QUARTO LIKE '$url_chale'";
        }

        $sqlDesc = "SELECT AC.COD_HOTEL,
                            AC.NOM_QUARTO,
                            UV.COD_EXTERNO as idHotel,
                            UV.COD_ESTADOF,
                            AC.COD_EXTERNO,
                            AC.DES_IMAGEM,
                            AC.DES_BANNER,
                            AC.DES_QUARTO,
                            DA.VAL_DIARIA,
                            DA.COD_COMOD,
                            AC.META_TITLE,
                            AC.META_DESCRIPTION,
                            NOM_FANTASI FROM ADORAI_CHALES AC
                    INNER JOIN UNIDADEVENDA UV ON UV.COD_EXTERNO = AC.COD_HOTEL
                    INNER JOIN ADORAI_PROPRIEDADES AP ON AP.COD_HOTEL = UV.COD_EXTERNO AND AP.COD_EXCLUSA = 0
                    INNER JOIN DETALHES_ADORAI DA ON DA.COD_CHALE = AC.ID
                    WHERE AC.COD_EMPRESA = 274
                    AND AC.COD_EXCLUSA = 0 
                    AND UV.LOG_ESTATUS = 'S'
                    $andDestaque
                    ORDER BY NOM_QUARTO";

        $arrayDesc = mysqli_query(connTemp(274, ''), $sqlDesc);

        $arrayDetalhe = array();
        $arrUnidades = array();

        while ($qrDesc = mysqli_fetch_assoc($arrayDesc)) {
            $arrayDetalhe[COD_HOTEL] = $qrDesc[COD_HOTEL];
            $arrayDetalhe[NOM_QUARTO] = $qrDesc[NOM_QUARTO];
            $arrayDetalhe[COD_EXTERNO] = $qrDesc[COD_EXTERNO];
            $arrayDetalhe[COD_ESTADOF] = $qrDesc[COD_ESTADOF];
            $arrayDetalhe[idHotel] = $qrDesc[idHotel];
            $arrayDetalhe[DES_IMAGEM] = $qrDesc[DES_IMAGEM];
            $arrayDetalhe[DES_BANNER] = $qrDesc[DES_BANNER];
            $arrayDetalhe[DES_QUARTO] = $qrDesc[DES_QUARTO];
            $arrayDetalhe[VAL_DIARIA] = $qrDesc[VAL_DIARIA];
            $arrayDetalhe[COD_COMOD] = $qrDesc[COD_COMOD];
            $arrayDetalhe[NOM_FANTASI] = $qrDesc[NOM_FANTASI];
            $arrayDetalhe[META_DESCRIPTION] = $qrDesc[META_DESCRIPTION];
            $arrayDetalhe[META_TITLE] = $qrDesc[META_TITLE];
            array_push($arrUnidades, $arrayDetalhe);
            unset($arrayDetalhe);
        }

        echo json_encode($arrUnidades);

        break;

    case '5': // datas chales

        function fnArrayPeriodoData($date1, $date2, $format = 'Y-m-d')
        {
            $dates = array();
            $current = strtotime($date1);
            $date2 = strtotime($date2);
            $stepVal = '+1 day';
            while ($current <= $date2) {
                $dates[] = date($format, $current);
                $current = strtotime($stepVal, $current);
            }
            return $dates;
        }

        if ($cod_chale != "") {
            $chaleConsulta = $cod_chale;
        } else {
            $chaleConsulta = 0;
        }

        $hoje = date("Y-m-d");
        $diaPrimeiro = date("Y-m-01");
        if ($hoje > $diaPrimeiro) {
            $diaPrimeiro = $hoje;
        }
        $ultimoDia = date("Y-m-t", strtotime($diaPrimeiro . " +1 year"));

        $dat_ini = $diaPrimeiro;
        $dat_fim = $ultimoDia;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://adorai-connectivity-api.soufoco.com.br/v1/avaiability/ota/OTA_HotelAvailGetRQ',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 360,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '<?xml version="1.0" encoding="UTF-8"?>
                                <OTA_HotelAvailGetRQ xmlns="http://www.opentravel.org/OTA/2003/05" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.opentravel.org/OTA/2003/05 OTA_HotelResNotifRQ.xsd" TimeStamp="2012-11-13T10:06:51-00:00" Target="Production" Version="1">
                                    <HotelAvailRequests>
                                        <HotelAvailRequest>
                                            <DateRange Start="' . $dat_ini . '" End="' . $dat_fim . '" />
                                            <HotelRef HotelCode="' . $cod_hotel . '" />
                                            <TPA_Extensions>
                                                <RestrictionStatusCandidates SendAllRestrictions="true"/>
                                            </TPA_Extensions>
                                        </HotelAvailRequest>
                                    </HotelAvailRequests>
                                </OTA_HotelAvailGetRQ>',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: text/xml',
                'Authorization: Basic YWRvcmFpOmtKbW5mMzQ1SG5maGQ=',
                'Cookie: foco_api_connectivity_session=eyJpdiI6Ikh6cTg4U3NuUUNMUjRKd3paeEY4VkE9PSIsInZhbHVlIjoiSUcwSUlEMklmSVNiUENBdVMrUXdOMWlGRWtXZ1hpWlpiYW9RMVNIQ3JrUk1JaVJ6WVRnM3lWQWxtT1wvSGhoa2dZV0czam5vVFwvV3YwQnFHUllLVmNKTVh1UUFrQTlPWUVLZmdrK0pFKzVGVUN0bXdWajVvRXFiM2RxZHk0NGNuTyIsIm1hYyI6IjNlMDA4MmYxMjQ3ZjVhN2Q3MWU2ZDE0MWY3NmE1ZDgwMjkwYzNiMWQwMDE3YTI2M2U4NjQzY2YyMjZjYWI4MTkifQ%3D%3D'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        $xml = simplexml_load_string($response, "SimpleXMLElement", LIBXML_NOCDATA);
        $hotel = json_decode(json_encode($xml), TRUE);
        unset($hotel['@attributes']);
        unset($hotel['Success']);

        if ($_GET[dev] == 'true') {
            echo "<pre>";
            print_r($hotel);
            echo "</pre>";
        }

        foreach ($hotel as $DadosHotel) {

            $HotelCode = $DadosHotel['@attributes']['HotelCode'];

            foreach ($DadosHotel[AvailStatusMessage] as $dados) {

                //    echo "<pre>";
                // print_r($dados);
                // echo "</pre>";
                $cod_chale = $dados['StatusApplicationControl']['@attributes']['RatePlanCode'];

                if ($chaleConsulta != $cod_chale && $chaleConsulta != 0) {
                    continue;
                }

                if ($dados['@attributes']['BookingLimit'] >= '1') {


                    if ($dados['StatusApplicationControl']['RestrictionStatus'][2]['@attributes']['Status'] == "Open") {

                        if (isset($HotelCode)) {


                            $sqlChale = "SELECT DISTINCT * FROM ADORAI_CHALES 
                                         WHERE COD_EMPRESA = 274
                                         AND COD_HOTEL = $cod_hotel
                                         AND COD_EXTERNO = $cod_chale
                                         AND COD_EXCLUSA = 0";

                            // fnEscreve($sqlChale);

                            $arrayQuery = mysqli_query(connTemp(274, ''), $sqlChale);
                            $qrChale = mysqli_fetch_assoc($arrayQuery);

                            $arrData = fnArrayPeriodoData($dados['StatusApplicationControl']['@attributes']['Start'], $dados['StatusApplicationControl']['@attributes']['End']);

                            $minDiarias = $dados['StatusApplicationControl']['LengthsOfStay']['LengthOfStay'][0]['@attributes']['Time'];

                            if ($_GET[dev] == 'true') {

                                // echo "<pre>";
                                // print_r($arrData);
                                // echo "</pre>";

                            }

                            foreach ($arrData as $dataIniReserva) {

                                $diaIniReserva = date("d", strtotime($dataIniReserva));
                                $dataFimReserva = date('Y-m-d', strtotime($dataIniReserva . ' +1 day'));

                                // if($minDiarias == 1){
                                //     $minDiarias = 2;
                                // }

                                $arrayOpen[] = array(
                                    'Status' => 'OPEN',
                                    'Nome' => "$qrChale[NOM_QUARTO]",
                                    'ID' => "$qrChale[COD_EXTERNO]",
                                    'diaInicio' => $diaIniReserva,
                                    'DataInicio' => $dataIniReserva,
                                    'DataFim' => $dataFimReserva,
                                    'minDiarias' => $minDiarias,
                                    'diff' => 1
                                );
                            }
                        }
                    }
                }
            }
        }


        ksort($arrayOpen);

        // echo "<pre>";
        // print_r($hotel);
        // echo "</pre>";

        echo json_encode($arrayOpen);

        break;

    case '6': // faq

        $sqlDesc = "SELECT * FROM PERGUNTAS
                    WHERE COD_EMPRESA = 274
                    ORDER BY NUM_ORDENAC";
        $arrayDesc = mysqli_query(connTemp(274, ''), $sqlDesc);

        $arrPerguntas = array();
        $arrRegistros = array();

        while ($qrDesc = mysqli_fetch_assoc($arrayDesc)) {
            $arrRegistros['DES_PERGUNTA'] = $qrDesc['DES_PERGUNTA'];
            $arrRegistros['DES_RESPOSTA'] = $qrDesc['DES_RESPOSTA'];
            $arrRegistros['NUM_ORDENAC'] = $qrDesc['NUM_ORDENAC'];
            array_push($arrPerguntas, $arrRegistros);
            unset($arrRegistros);
        }

        echo json_encode($arrPerguntas);

        break;

    case '7': // UF da propriedade

        $sqlDesc = "SELECT NOM_RESPONS AS 'URL', NOM_FANTASI, COD_ESTADOF FROM WEBTOOLS.UNIDADEVENDA 
                    WHERE COD_EMPRESA = 274
                    AND LOG_ESTATUS = 'S'
                    AND COD_EXTERNO = $cod_hotel";
        $arrayDesc = mysqli_query($connAdm->connAdm(), $sqlDesc);

        $qrDesc = mysqli_fetch_array($arrayDesc, MYSQLI_ASSOC);

        echo json_encode($qrDesc);

        break;

    case '8': // comodidades

        if ($cod_comod != "") {
            $andComod = "AND COD_COMOD IN($cod_comod)";
        }

        $sqlComod = "SELECT COD_COMOD, DES_COMOD FROM COMODIDADES_ADORAI
                     WHERE COD_EMPRESA = 274
                     $andComod";

        $arrayComod = mysqli_query(connTemp(274, ''), $sqlComod);

        $arrComodidades = array();
        $count = 0;

        while ($qrComod = mysqli_fetch_assoc($arrayComod)) {
            $arrComodidades[$count][COD_COMOD] = $qrComod[COD_COMOD];
            $arrComodidades[$count][DES_COMOD] = $qrComod[DES_COMOD];
            $count++;
        }

        echo json_encode($arrComodidades);

        break;

    case '9': // verificação de comodidades

        $andComod = "";

        if ($cod_comod != "") {
            $cod_comod = str_replace(",", "|", $cod_comod);
            // $cod_comod = explode(",", $cod_comod);
            // foreach ($cod_comod as $comod) {
            //    $andComod .= "OR FIND_IN_SET($comod, DA.COD_COMOD) ";
            // }
            // $andComod = ltrim(trim($andComod),"OR");
        } else {
            $cod_comod = 0;
        }

        $sqlComod = "SELECT DA.COD_DETALHE FROM DETALHES_ADORAI DA
                        INNER JOIN ADORAI_CHALES AC ON AC.ID = DA.COD_CHALE AND AC.COD_EXCLUSA = 0
                        WHERE CONCAT(',', DA.COD_COMOD, ',')  REGEXP ',($cod_comod),'
                        AND AC.COD_EXTERNO = $cod_chale";

        $arrayComod = mysqli_query(connTemp(274, ''), $sqlComod);

        // echo $sqlComod;
        echo $arrayComod->num_rows;

        break;

    case '10': // datas indisp. chales

        function fnArrayPeriodoData($date1, $date2, $format = 'Y-m-d')
        {
            $dates = array();
            $current = strtotime($date1);
            $date2 = strtotime($date2);
            $stepVal = '+1 day';
            while ($current <= $date2) {
                $dates[] = date($format, $current);
                $current = strtotime($stepVal, $current);
            }
            return $dates;
        }

        $chaleConsulta = $cod_chale;

        $hoje = date("Y-m-d");
        $diaPrimeiro = date("Y-m-01");
        if ($hoje > $diaPrimeiro) {
            $diaPrimeiro = $hoje;
        }
        $ultimoDia = date("Y-m-t", strtotime($diaPrimeiro . " +6 months"));

        $dat_ini = $diaPrimeiro;
        $dat_fim = $ultimoDia;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://adorai-connectivity-api.soufoco.com.br/v1/avaiability/ota/OTA_HotelAvailGetRQ',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 360,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '<?xml version="1.0" encoding="UTF-8"?>
                                <OTA_HotelAvailGetRQ xmlns="http://www.opentravel.org/OTA/2003/05" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.opentravel.org/OTA/2003/05 OTA_HotelResNotifRQ.xsd" TimeStamp="2012-11-13T10:06:51-00:00" Target="Production" Version="1">
                                    <HotelAvailRequests>
                                        <HotelAvailRequest>
                                            <DateRange Start="' . $dat_ini . '" End="' . $dat_fim . '" />
                                            <HotelRef HotelCode="' . $cod_hotel . '" />
                                            <TPA_Extensions>
                                                <RestrictionStatusCandidates SendAllRestrictions="true"/>
                                            </TPA_Extensions>
                                        </HotelAvailRequest>
                                    </HotelAvailRequests>
                                </OTA_HotelAvailGetRQ>',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: text/xml',
                'Authorization: Basic YWRvcmFpOmtKbW5mMzQ1SG5maGQ=',
                'Cookie: foco_api_connectivity_session=eyJpdiI6Ikh6cTg4U3NuUUNMUjRKd3paeEY4VkE9PSIsInZhbHVlIjoiSUcwSUlEMklmSVNiUENBdVMrUXdOMWlGRWtXZ1hpWlpiYW9RMVNIQ3JrUk1JaVJ6WVRnM3lWQWxtT1wvSGhoa2dZV0czam5vVFwvV3YwQnFHUllLVmNKTVh1UUFrQTlPWUVLZmdrK0pFKzVGVUN0bXdWajVvRXFiM2RxZHk0NGNuTyIsIm1hYyI6IjNlMDA4MmYxMjQ3ZjVhN2Q3MWU2ZDE0MWY3NmE1ZDgwMjkwYzNiMWQwMDE3YTI2M2U4NjQzY2YyMjZjYWI4MTkifQ%3D%3D'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        $xml = simplexml_load_string($response, "SimpleXMLElement", LIBXML_NOCDATA);
        $hotel = json_decode(json_encode($xml), TRUE);
        unset($hotel['@attributes']);
        unset($hotel['Success']);

        foreach ($hotel as $DadosHotel) {

            $HotelCode = $DadosHotel['@attributes']['HotelCode'];

            foreach ($DadosHotel[AvailStatusMessage] as $dados) {

                //    echo "<pre>";
                // print_r($dados);
                // echo "</pre>";
                $cod_chale = $dados['StatusApplicationControl']['@attributes']['RatePlanCode'];

                // if($chaleConsulta != $cod_chale && $chaleConsulta != ""){
                //     continue;
                // }

                if ($dados['@attributes']['BookingLimit'] == '0') {


                    if ($dados['StatusApplicationControl']['RestrictionStatus'][2]['@attributes']['Status'] == "Close") {

                        if (isset($HotelCode)) {

                            if ($chaleConsulta != "") {
                                $andChale = "AND COD_EXTERNO = $cod_chale";
                            }

                            $sqlChale = "SELECT DISTINCT * FROM ADORAI_CHALES 
                                         WHERE COD_EMPRESA = 274
                                         AND COD_HOTEL = $cod_hotel
                                         $andChale
                                         AND COD_EXCLUSA = 0";

                            // fnEscreve($sqlChale);

                            $arrayQuery = mysqli_query(connTemp(274, ''), $sqlChale);
                            $qrChale = mysqli_fetch_assoc($arrayQuery);

                            $arrData = fnArrayPeriodoData($dados['StatusApplicationControl']['@attributes']['Start'], $dados['StatusApplicationControl']['@attributes']['End']);

                            foreach ($arrData as $dataIniReserva) {

                                $diaIniReserva = date("d", strtotime($dataIniReserva));
                                $dataFimReserva = date('Y-m-d', strtotime($dataIniReserva . ' +1 day'));

                                $arrayOpen[] = array(
                                    'Status' => 'CLOSE',
                                    'Nome' => "$qrChale[NOM_QUARTO]",
                                    'ID' => "$qrChale[COD_EXTERNO]",
                                    'diaInicio' => $diaIniReserva,
                                    'DataInicio' => $dataIniReserva,
                                    'DataFim' => $dataFimReserva,
                                    'diff' => 1
                                );
                            }
                        }
                    }
                }
            }
        }


        ksort($arrayOpen);

        echo "<pre>";
        print_r($hotel);
        echo "</pre>";

        // echo json_encode($arrayOpen);

        break;

    case '11': // detalhes da propriedade

        $sqlDesc = "SELECT DES_IMAGEM, DES_PROPRIEDADE, DES_CONTRATO, H1_PROPRIEDADE FROM ADORAI_PROPRIEDADES AP
                    WHERE AP.COD_EMPRESA = 274
                    AND AP.COD_EXCLUSA = 0
                    AND COD_HOTEL = $cod_hotel";

        $arrayDesc = mysqli_query(connTemp(274, ''), $sqlDesc);

        $qrDesc = mysqli_fetch_array($arrayDesc, MYSQLI_ASSOC);

        echo json_encode($qrDesc);

        break;

    case '12': // registro da consulta

        // CREATE TABLE ACESSOS_ADORAI(
        //     COD_ACESSO INT PRIMARY KEY AUTO_INCREMENT,
        //     COD_EMPRESA INT,
        //     DES_ORIGEM VARCHAR(20),
        //     NUM_CELULAR VARCHAR(20),
        //     DAT_INI DATETIME,
        //     DAT_FIM DATETIME,
        //     COD_HOTEL INT,
        //     COD_CHALE INT,
        //     DAT_CADASTR TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        // )

        if ($origem == "") {
            $origem = "SITE";
        }

        $sqlDesc = "INSERT INTO ACESSOS_ADORAI(
                                    COD_EMPRESA,
                                    DES_ORIGEM,
                                    NUM_CELULAR,
                                    DAT_INI,
                                    DAT_FIM,
                                    COD_HOTEL,
                                    COD_CHALE
                                ) VALUES(
                                    274,
                                    '$origem',
                                    '$num_celular',
                                    '$dat_inicio',
                                    '$dat_final',
                                    '$cod_hotel',
                                    '$cod_chale'
                                )";

        $arrayDesc = mysqli_query(connTemp(274, ''), $sqlDesc);

        break;

    case '13': // registro da intenção de reserva

        // CREATE TABLE LINK_ADORAI(
        // COD_LINK INT PRIMARY KEY AUTO_INCREMENT,
        // DES_LINK VARCHAR(250),
        // COD_CHALE INT,
        // COD_PROPRIEDADE INT, 
        // NUM_CELULAR VARCHAR(11),
        // DAT_INI DATE,
        // DAT_FIM DATE,
        // DES_CANAL VARCHAR(60),
        // DAT_ACESSO TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        // )

        /* https://motor.roteirosadorai.com.br/search/fnDataSql($dat_ini)/fnDataSql($dat_fim)/2/<?=$cod_hotel/$cod_chale?canal_id=$cod_vendedor */

        if ($origem == "") {
            $origem = "SITE";
        }

        @$link_origem = fnDecode($_REQUEST['link']);
        @$valor = base64_decode($_REQUEST['iv']);

        if ($num_celular != "") {

            $sqlDesc = "INSERT INTO LINK_ADORAI(
                                        DES_LINK_ORIGEM,
                                        DES_LINK_DESTINO,
                                        COD_CHALE,
                                        COD_PROPRIEDADE,
                                        NUM_CELULAR,
                                        DAT_INI,
                                        DAT_FIM,
                                        VAL_RESERVA,
                                        DES_CANAL
                                    ) VALUES(
                                        '$link_origem',
                                        'https://motor.roteirosadorai.com.br/search/$dat_inicio/$dat_final/2/$cod_hotel/$cod_chale?canal_id=$cod_vendedor',
                                        '$cod_chale',
                                        '$cod_hotel',
                                        '$num_celular',
                                        '$dat_inicio',
                                        '$dat_final',
                                        '$valor',
                                        '$origem'
                                    )";

            $arrayDesc = mysqli_query(connTemp(274, ''), $sqlDesc);
        }

        break;

    case '14': //todos os chales


        $sqlDesc = "SELECT AC.NOM_QUARTO,
                            AC.COD_HOTEL,
                            AC.COD_EXTERNO AS COD_CHALE,
                            DA.DES_CHAMADA,
                            UV.NOM_FANTASI,
                            UV.COD_ESTADOF, 
                            DA.VAL_DIARIA,
                            DA.NUM_HOSPEDE,
                            DA.DES_TEMPLATE,
                            DA.COD_COMOD,
                            IA.NOM_IMAGEM,
                            IA.DES_IMAGEM
                    FROM ADORAI_CHALES AC
                    INNER JOIN detalhes_adorai DA ON DA.COD_CHALE = AC.ID AND DA.COD_EXCLUSA = 0
                    INNER JOIN imagens_adorai IA ON IA.COD_CHALE = AC.ID AND IA.COD_EXCLUSA = 0
                    INNER JOIN webtools.UNIDADEVENDA UV ON UV.COD_EXTERNO = AC.COD_HOTEL
                    WHERE AC.COD_EMPRESA = 274
                    AND UV.LOG_ESTATUS = 'S'
                    AND AC.COD_EXCLUSA = 0";

        $arrayDesc = mysqli_query(connTemp(274, ''), $sqlDesc);

        $comodidades = "";
        $arrayChale = array();
        $arrayChales = array();

        while ($qrDesc = mysqli_fetch_assoc($arrayDesc)) {
            $arrayChale[COD_HOTEL] = $qrDesc[COD_HOTEL];
            $arrayChale[COD_CHALE] = $qrDesc[COD_CHALE];
            $arrayChale[NOM_QUARTO] = $qrDesc[NOM_QUARTO];
            $arrayChale[NOM_HOTEL] = $qrDesc[NOM_FANTASI];
            $arrayChale[COD_ESTADOF] = $qrDesc[COD_ESTADOF];
            $arrayChale[DES_CHAMADA] = $qrDesc[DES_CHAMADA];
            $arrayChale[VAL_DIARIA] = $qrDesc[VAL_DIARIA];
            $arrayChale[NUM_HOSPEDE] = $qrDesc[NUM_HOSPEDE];
            $arrayChale[DES_TEMPLATE] = $qrDesc[DES_TEMPLATE];
            $arrayChale[NOM_IMAGEM] = $qrDesc[NOM_IMAGEM];
            $arrayChale[DES_IMAGEM] = $qrDesc[DES_IMAGEM];

            array_push($arrayChales, $arrayChale);
            unset($arrayChale);
        }



        echo json_encode($arrayChales);

        break;

    case '15': // comodidades das propriedades



        $sqlComod = "SELECT COD_COMOD FROM DETALHES_ADORAI DA
                 INNER JOIN ADORAI_CHALES AC ON AC.ID = DA.COD_CHALE AND AC.COD_EXCLUSA = 0
                 WHERE ac.COD_HOTEL IN($cod_hotel)";

        $arrayComod = mysqli_query(connTemp(274, ''), $sqlComod);

        $comod = "";

        while ($qrComod = mysqli_fetch_assoc($arrayComod)) {
            $comod .= $qrComod[COD_COMOD] . ',';
        }

        $comod = rtrim(ltrim($comod, ','), ',');

        $comod = implode(',', array_keys(array_flip(explode(',', $comod))));

        $sqlCom = "SELECT COD_COMOD, DES_COMOD FROM COMODIDADES_ADORAI
                 WHERE COD_EMPRESA = 274
                 AND COD_COMOD IN($comod)";

        $arrayCom = mysqli_query(connTemp(274, ''), $sqlCom);

        $arrComodidades = array();
        $count = 0;

        while ($qrCom = mysqli_fetch_assoc($arrayCom)) {
            $arrComodidades[$count][COD_COMOD] = $qrCom[COD_COMOD];
            $arrComodidades[$count][DES_COMOD] = $qrCom[DES_COMOD];
            $count++;
        }

        echo json_encode($arrComodidades);

        break;

    case '16': // consulta chales por data e localidade

        if ($cod_chale != "") {
            $chaleConsulta = $cod_chale;
        } else {
            $chaleConsulta = 0;
        }

        if ($cod_hotel == 99 || $cod_hotel == "" || $cod_hotel == 0) {
            $cod_hotel = "956,5156,5158,2957,3010,3008";
        }

        $arrHotel = explode(",", $cod_hotel);

        foreach ($arrHotel as $hotel) {
            //fnEscreve($hotel);
            if ($hotel != 0) {
                $reservaHotel .= '<HotelRef HotelCode="' . $hotel . '"/>';
            }
            //fnEscreve($reservaHotel);
        }

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://adorai-connectivity-api.soufoco.com.br/v1/avaiability/ota/OTA_HotelAvailRQ',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 360,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '<?xml version="1.0" encoding="UTF-8"?>
                            <OTA_HotelAvailRQ xmlns="http://www.opentravel.org/OTA/2003/05" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.opentravel.org/OTA/2003/05 OTA_HotelAvailRQ.xsd" TimeStamp="2012-11-13T10:06:51-00:00" Target="Production" Version="1">
                                <AvailRequestSegments>
                                    <AvailRequestSegment>
                                        <HotelSearchCriteria AvailableOnlyIndicator="true">
                                            <Criterion>
                                               ' . $reservaHotel . '
                                            </Criterion>
                                        </HotelSearchCriteria>
                                        <RoomStayCandidates>
                                            <RoomStayCandidate EffectiveDate="' . $dat_inicio . '" ExpireDate="' . $dat_final . '">
                                                <GuestCounts>
                                                    <GuestCount AgeQualifyingCode="10" Count="2"/>
                                                </GuestCounts>
                                            </RoomStayCandidate>
                                        </RoomStayCandidates>
                                    </AvailRequestSegment>
                                </AvailRequestSegments>
                            </OTA_HotelAvailRQ>',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: text/xml',
                'Authorization: Basic YWRvcmFpOmtKbW5mMzQ1SG5maGQ=',
                'Cookie: foco_api_connectivity_session=eyJpdiI6Ikh6cTg4U3NuUUNMUjRKd3paeEY4VkE9PSIsInZhbHVlIjoiSUcwSUlEMklmSVNiUENBdVMrUXdOMWlGRWtXZ1hpWlpiYW9RMVNIQ3JrUk1JaVJ6WVRnM3lWQWxtT1wvSGhoa2dZV0czam5vVFwvV3YwQnFHUllLVmNKTVh1UUFrQTlPWUVLZmdrK0pFKzVGVUN0bXdWajVvRXFiM2RxZHk0NGNuTyIsIm1hYyI6IjNlMDA4MmYxMjQ3ZjVhN2Q3MWU2ZDE0MWY3NmE1ZDgwMjkwYzNiMWQwMDE3YTI2M2U4NjQzY2YyMjZjYWI4MTkifQ%3D%3D'
            ),
        ));

        $response = curl_exec($curl);
        // $xml = simplexml_load_string($response, "SimpleXMLElement", LIBXML_NOCDATA);
        // $hotel = json_decode(json_encode($xml), TRUE);

        $doc = new DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTML($response);
        libxml_clear_errors();
        $xml = $doc->saveXML($doc->documentElement);
        $xml = simplexml_load_string($xml);
        $hotel1 = json_decode(json_encode($xml), true);
        $hotel = $hotel1['body']['ota_hotelavailrs'];

        // if ($_GET[dev] != "") {


        // }

        curl_close($curl);

        $val_total = 0;
        $log_diaria = 'N';
        $val_diarias = array();

        if ($hotel['roomstays']['roomstay']['roomrates']['roomrate']['rates']['rate']['total']['@attributes']['amountaftertax'] > 0) {
            // fnEscreve2('entrou nas diarias simples');

            $chave_linha = $countQuarto;
            $dat_min = $hotel['roomstays']['roomstay']['roomrates']['roomrate']['rates']['rate']['@attributes'];
            $dat_max = $hotel['roomstays']['roomstay']['roomrates']['roomrate']['rates']['rate']['@attributes']['expiredate'];
            $nom_quarto = $hotel['roomstays']['roomstay']['roomtypes']['roomtype']['tpa_extensions']['room']['@attributes']['name'];
            $id_hotel = $hotel['roomstays']['roomstay']['@attributes']['rph'];
            $cod_quarto = $hotel['roomstays']['roomstay']['roomtypes']['roomtype']['tpa_extensions']['room']['@attributes']['id'];
            $val_diaria = $hotel['roomstays']['roomstay']['roomrates']['roomrate']['rates']['rate']['total']['@attributes']['amountaftertax'];
            $val_diarias[$dat_min . "_" . $dat_max] = $hotel['roomstays']['roomstay']['roomrates']['roomrate']['rates']['rate']['total']['@attributes']['amountaftertax'];
            $val_total = $hotel['roomstays']['roomstay']['roomrates']['roomrate']['rates']['rate']['total']['@attributes']['amountaftertax'];

            $nom_quarto = explode("-", $nom_quarto);

            $diasemana = array('Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sabado');

            // $data = date('Y-m-d');

            $diasemana_inicio = date('w', strtotime($dat_min));
            $diasemana_fim = date('w', strtotime($dat_max));
            $nroQuarto = explode(" ", $nom_quarto[0]);
            $nroDiarias = fnDateDif($dat_min, $dat_max);

            $arrChale = file_get_contents("https://adm.bunker.mk/wsjson/externoAdorai.do?tipo=1&COD_CHALE=$cod_quarto");

            $qrDesc = json_decode($arrChale, true);

            if ($cod_comod != "") {
                $countComod = file_get_contents("https://adm.bunker.mk/wsjson/externoAdorai.do?tipo=9&COD_CHALE=$cod_quarto&COD_COMOD=$cod_comod");
            } else {
                $countComod = 1;
            }

            if ($countComod > 0) {

                $arrayVagas[$nroQuarto[1]] = array(
                    "idHotel" => $id_hotel,
                    "idQuarto" => $cod_quarto,
                    "codVendedor" => "",
                    "chale" => $nom_quarto[0],
                    "local" => $nom_quarto[1],
                    "diaria" => $val_diaria,
                    "diarias" => $val_diarias,
                    "total" => $val_total,
                    "dataMin" => $dat_min,
                    "dataMax" => $dat_max,
                    "semanaIni" => $diasemana[$diasemana_inicio],
                    "semanaFim" => $diasemana[$diasemana_fim],
                    "nroDiarias" => $nroDiarias,
                    "nroPessoas" => $num_pessoas,
                    "descricao" => "$qrDesc[DES_QUARTO]",
                    "imagem" => "$qrDesc[DES_IMAGEM]",
                    "video" => "$qrDesc[DES_VIDEO]"
                );

                ksort($arrayVagas);

                $arrayOrdenado = $arrayVagas;
            }
        }

        $abrQuarto = $hotel['roomstays']['roomstay'];

        if ($abrQuarto == "") {
            // fnEscreve2("sem roomstays");
            $abrQuarto = $hotel['roomstay'];
        }

        foreach ($abrQuarto as $quarto) {

            // echo "<pre>";
            // fnEscreve2('entrou nas diarias multiplas');
            // print_r($quarto);
            // echo "</pre>";

            $chave_linha = $countQuarto;

            if (count($quarto['roomrates']['roomrate']['rates']['rate']) > 0) {
                $quartosVaga = $quarto['roomrates']['roomrate']['rates']['rate'];
            } else if (count($quarto['roomrate']['rates']['rate']) > 0) {
                $quartosVaga = $quarto['roomrate']['rates']['rate'];
            } else {
                $quartosVaga = "";
            }

            $val_total = 0;
            $val_diarias = array();

            foreach ($quartosVaga as $vaga) {

                // echo "<pre>";
                // print_r($quartosVaga);
                // echo "</pre>";


                if ($countVaga == 0) {
                    $dat_min = $vaga['@attributes']['effectivedate'];

                    if ($dat_min == "") {
                        $dat_min = $quartosVaga['@attributes']['effectivedate'];
                    }
                }

                $nom_quarto = $quarto['roomtypes']['roomtype']['tpa_extensions']['room']['@attributes']['name'];
                $id_hotel = $quarto['@attributes']['rph'];
                $cod_quarto = $quarto['roomtypes']['roomtype']['tpa_extensions']['room']['@attributes']['id'];

                $dat_max = $vaga['@attributes']['expiredate'];

                if ($dat_max == "") {
                    $dat_max = $quartosVaga['@attributes']['expiredate'];
                }

                // fnEscreve($dat_max);

                if ($nom_quarto == "") {
                    $nom_quarto = $abrQuarto['roomtypes']['roomtype']['tpa_extensions']['room']['@attributes']['name'];
                    $id_hotel = $abrQuarto['@attributes']['rph'];
                    $cod_quarto = $abrQuarto['roomtypes']['roomtype']['tpa_extensions']['room']['@attributes']['id'];
                }

                $val_diaria = $vaga['total']['@attributes']['amountaftertax'];
                $val_diarias[$dat_min . "_" . $dat_max] = $vaga['total']['@attributes']['amountaftertax'];

                if ($val_diaria == "") {
                    $val_diaria = $vaga['@attributes']['amountaftertax'];
                    $val_diarias[$quartosVaga['@attributes']['effectivedate'] . "_" . $dat_max] = $vaga['@attributes']['amountaftertax'];
                }

                if ($val_diaria == "") {
                    $val_diaria = $vaga[0]['@attributes']['amountaftertax'];
                    $val_diarias[$quartosVaga['@attributes']['effectivedate'] . "_" . $dat_max] = $vaga[0]['@attributes']['amountaftertax'];
                }
                $val_total += $val_diaria;

                $nom_quarto = explode("-", $nom_quarto);

                $countVaga++;
            }

            if ($log_diaria == "N" && $val_total != 0) {

                // fnEscreve2('entra no if da diaria maior que 0');

                $diasemana = array('Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sabado');

                // $data = date('Y-m-d');

                $diasemana_inicio = date('w', strtotime($dat_min));
                $diasemana_fim = date('w', strtotime($dat_max));
                $nroQuarto = explode(" ", $nom_quarto[0]);
                $nroDiarias = fnDateDif($dat_min, $dat_max);

                $arrChale = file_get_contents("https://adm.bunker.mk/wsjson/externoAdorai.do?tipo=1&COD_CHALE=$cod_quarto");

                $qrDesc = json_decode($arrChale, true);


                $arrUF = file_get_contents("https://adm.bunker.mk/wsjson/externoAdorai.do?tipo=7&COD_HOTEL=$id_hotel");

                $qrUF = json_decode($arrUF, true);

                // $sqlVend = "SELECT VA.COD_EXT_VENDEDOR FROM VENDEDOR_ADORAI VA 
                //             INNER JOIN UNIDADEVENDA UV ON UV.COD_UNIVEND = VA.COD_UNIVEND
                //             WHERE VA.COD_USUARIO = $_SESSION[SYS_COD_USUARIO] 
                //             AND UV.COD_EXTERNO = $id_hotel";

                // // if($_GET['dev'] == 'true'){
                // //  echo $sqlVend;
                // // }

                // $arrayVend = mysqli_query(connTemp($cod_empresa,''), $sqlVend);
                // $qrVend = mysqli_fetch_assoc($arrayVend);

                if ($cod_comod != "") {
                    $countComod = file_get_contents("https://adm.bunker.mk/wsjson/externoAdorai.do?tipo=9&COD_CHALE=$cod_quarto&COD_COMOD=$cod_comod");
                } else {
                    $countComod = 1;
                }

                // fnEscreve2("https://adm.bunker.mk/wsjson/externoAdorai.do?tipo=9&COD_CHALE=$cod_quarto&COD_COMOD=$cod_comod");
                // fnEscreve2($countComod);


                if ($countComod > 0) {

                    // fnEscreve2('entra no if do comod');

                    $retChale = preg_match("~\b$cod_quarto\b~", $cod_chale);

                    if ($retChale == 0 && $cod_chale != "" && $cod_chale != 0) {
                        // fnEscreve2('entra no if do continue');
                        continue;
                    }

                    // fnEscreve2(in_array($id_hotel, $cp_propriedade));

                    // echo "<pre>";
                    // print_r($id_hotel);
                    // print_r($cp_propriedade);
                    // echo "</pre>";

                    $arrayVagas[$nroQuarto[1]] = array(
                        "idHotel" => $id_hotel,
                        "idQuarto" => $cod_quarto,
                        "uf" => $qrUF[COD_ESTADOF],
                        "codVendedor" => "$qrVend[COD_EXT_VENDEDOR]",
                        "chale" => trim($nom_quarto[0]),
                        "local" => trim($nom_quarto[1]),
                        "diaria" => $val_diaria,
                        "diarias" => $val_diarias,
                        "total" => $val_total,
                        "dataMin" => $dat_min,
                        "dataMax" => $dat_max,
                        "semanaIni" => $diasemana[$diasemana_inicio],
                        "semanaFim" => $diasemana[$diasemana_fim],
                        "nroDiarias" => $nroDiarias,
                        "nroPessoas" => $num_pessoas,
                        "descricao" => "$qrDesc[DES_QUARTO]",
                        "imagem" => "$qrDesc[DES_IMAGEM]",
                        "video" => "$qrDesc[DES_VIDEO]"
                    );

                    // echo "<pre>";
                    // print_r($arrayVagas);
                    // echo "</pre>";

                    ksort($arrayVagas);

                    $arrayOrdenado = $arrayVagas;
                }
            }

            $countQuarto++;
            $countVaga = 0;
            $val_total = 0;
        }

        // echo "<pre>";
        // print_r($arrayOrdenado);
        // echo "</pre>";

        echo json_encode($arrayOrdenado);

        // echo "<pre>";
        // print_r($arrayOrdenado);
        // echo "</pre>";

        break;

    case '17': // busca datas disponiveis

        function fnArrayPeriodoData($date1, $date2, $format = 'Y-m-d')
        {
            $dates = array();
            $current = strtotime($date1);
            $date2 = strtotime($date2);
            $stepVal = '+1 day';
            while ($current <= $date2) {
                $dates[] = date($format, $current);
                $current = strtotime($stepVal, $current);
            }
            return $dates;
        }

        if ($cod_chale != "") {
            $chaleConsulta = $cod_chale;
        } else {
            $chaleConsulta = 0;
        }

        $qtd_chales = explode(',', $cod_chale);
        $qtd_hoteis = explode(',', $cod_hotel);

        $qtd_chales = count($qtd_chales);
        $qtd_hoteis = count($qtd_hoteis);


        if ($qtd_chales >= 5) {
            $diasConsulta = "3";
        } else if ($qtd_chales >= 3) {
            $diasConsulta = "5";
        } else if ($qtd_chales == 2) {
            $diasConsulta = "7";
        } else {
            if ($qtd_hoteis >= 3) {
                $diasConsulta = "2";
            } else if ($qtd_hoteis == 2) {
                $diasConsulta = "3";
            } else if ($qtd_hoteis == 1 && $qtd_chales == 1) {
                $diasConsulta = "25";
            } else {
                $diasConsulta = "15";
            }
        }

        //limitando todas as consultas a 3 dias
        $diasConsulta = "3";

        // echo "<br>";
        // // echo $diasConsulta;
        // echo "</br>";

        $hoje = date("Y-m-d");
        // $diaPrimeiro = date("Y-m-01");
        if ($dat_inicio != "") {
            $diaPrimeiro = $dat_inicio;
            $nroDiarias = fnDateDif($dat_inicio, $dat_fim);
        } else {
            $diaPrimeiro = $hoje;
            $nroDiarias = 0;
        }
        $ultimoDia = date("Y-m-d", strtotime($diaPrimeiro . " +" . $diasConsulta . " days"));

        $dat_ini = $diaPrimeiro;
        $dat_fim = $ultimoDia;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://adorai-connectivity-api.soufoco.com.br/v1/avaiability/ota/OTA_HotelAvailGetRQ',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 360,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '<?xml version="1.0" encoding="UTF-8"?>
                                <OTA_HotelAvailGetRQ xmlns="http://www.opentravel.org/OTA/2003/05" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.opentravel.org/OTA/2003/05 OTA_HotelResNotifRQ.xsd" TimeStamp="2012-11-13T10:06:51-00:00" Target="Production" Version="1">
                                    <HotelAvailRequests>
                                        <HotelAvailRequest>
                                            <DateRange Start="' . $dat_ini . '" End="' . $dat_fim . '" />
                                            <HotelRef HotelCode="' . $cod_hotel . '" />
                                            <TPA_Extensions>
                                                <RestrictionStatusCandidates SendAllRestrictions="true"/>
                                            </TPA_Extensions>
                                        </HotelAvailRequest>
                                    </HotelAvailRequests>
                                </OTA_HotelAvailGetRQ>',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: text/xml',
                'Authorization: Basic YWRvcmFpOmtKbW5mMzQ1SG5maGQ=',
                'Cookie: foco_api_connectivity_session=eyJpdiI6Ikh6cTg4U3NuUUNMUjRKd3paeEY4VkE9PSIsInZhbHVlIjoiSUcwSUlEMklmSVNiUENBdVMrUXdOMWlGRWtXZ1hpWlpiYW9RMVNIQ3JrUk1JaVJ6WVRnM3lWQWxtT1wvSGhoa2dZV0czam5vVFwvV3YwQnFHUllLVmNKTVh1UUFrQTlPWUVLZmdrK0pFKzVGVUN0bXdWajVvRXFiM2RxZHk0NGNuTyIsIm1hYyI6IjNlMDA4MmYxMjQ3ZjVhN2Q3MWU2ZDE0MWY3NmE1ZDgwMjkwYzNiMWQwMDE3YTI2M2U4NjQzY2YyMjZjYWI4MTkifQ%3D%3D'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        $xml = simplexml_load_string($response, "SimpleXMLElement", LIBXML_NOCDATA);
        $hotel = json_decode(json_encode($xml), TRUE);
        unset($hotel['@attributes']);
        unset($hotel['Success']);

        $arrayOpen = array();

        foreach ($hotel as $DadosHotel) {

            $HotelCode = $DadosHotel['@attributes']['HotelCode'];

            foreach ($DadosHotel[AvailStatusMessage] as $dados) {

                //    echo "<pre>";
                // print_r($dados);
                // echo "</pre>";
                $cod_chale = $dados['StatusApplicationControl']['@attributes']['RatePlanCode'];

                $retChale = preg_match("~\b$cod_chale\b~", $chaleConsulta);

                if ($retChale == 0 && $chaleConsulta != 0 && $chaleConsulta != "") {
                    continue;
                }

                if ($dados['@attributes']['BookingLimit'] >= '1') {


                    if ($dados['StatusApplicationControl']['RestrictionStatus'][2]['@attributes']['Status'] == "Open") {

                        if (isset($HotelCode)) {


                            $sqlChale = "SELECT DISTINCT * FROM ADORAI_CHALES 
                                         WHERE COD_EMPRESA = 274
                                         AND COD_HOTEL = $HotelCode
                                         AND COD_EXTERNO = $cod_chale
                                         AND COD_EXCLUSA = 0";

                            // fnEscreve($sqlChale);

                            $arrayQuery = mysqli_query(connTemp(274, ''), $sqlChale);
                            $qrChale = mysqli_fetch_assoc($arrayQuery);

                            $arrData = fnArrayPeriodoData($dados['StatusApplicationControl']['@attributes']['Start'], $dados['StatusApplicationControl']['@attributes']['End']);

                            $minDiarias = $dados['StatusApplicationControl']['LengthsOfStay']['LengthOfStay'][0]['@attributes']['Time'];

                            foreach ($arrData as $dataIniReserva) {

                                $diaIniReserva = date("d", strtotime($dataIniReserva));
                                $dataFimReserva = date('Y-m-d', strtotime($dataIniReserva . ' +' . $minDiarias . ' days'));

                                $nroDiariasVaga = fnDateDif($dataIniReserva, $dataFimReserva);

                                // if($nroDiarias == 0 || $nroDiarias == $nroDiariasVaga){

                                $arrayOpen["$dataIniReserva&$dataFimReserva"][$HotelCode][$qrChale[COD_EXTERNO]] = array(
                                    'COD_HOTEL' => "$HotelCode",
                                    'COD_CHALE' => "$qrChale[COD_EXTERNO]",
                                    'DAT_INI' => $dataIniReserva,
                                    'DAT_FIM' => $dataFimReserva,
                                    'minDiarias' => $minDiarias
                                );
                                // }

                            }
                        }
                    }
                }
            }
        }


        ksort($arrayOpen);

        // echo "<pre>";
        // print_r($arrayOpen);
        // echo "</pre>";

        echo json_encode($arrayOpen);

        break;

    case '18': // locais/chales da home

        $andDestaque = "and AC.LOG_HOME = 'S'";

        if ($cod_hotel > 0) {
            $andDestaque = "and UV.COD_EXTERNO = $cod_hotel";
            if ($cod_hotel == 99) {
                $andDestaque = "";
            }
        }

        if ($banner != "") {
            $andDestaque = "and AC.LOG_BANNER = 'S'";
        }

        $sqlDesc = "SELECT UV.COD_EXTERNO as idHotel,
                            AC.COD_EXTERNO AS idQuarto,
                            AC.LOG_BADGE,
                            AC.TXT_BADGE,
                            AC.COR_BADGE,
                            AC.COR_TXTBADGE,
                            NOM_FANTASI AS 'local',
                            NOM_QUARTO chale,
                            VAL_DIARIA AS total,
                            DAT_INI_DEST,
                            DAT_FIM_DEST
                    FROM ADORAI_CHALES AC
                    INNER JOIN UNIDADEVENDA UV ON UV.COD_EXTERNO = AC.COD_HOTEL
                    INNER JOIN DETALHES_ADORAI DA ON DA.COD_CHALE = AC.ID
                    WHERE AC.COD_EMPRESA = 274
                    AND AC.COD_EXCLUSA = 0
                    AND UV.LOG_ESTATUS = 'S'
                    $andDestaque
                    ORDER BY DAT_INI_DEST ASC";

        $arrayDesc = mysqli_query(connTemp(274, ''), $sqlDesc);

        $arrayDetalhe = array();
        $arrayDestaques = array();
        $count = 0;

        while ($qrDesc = mysqli_fetch_assoc($arrayDesc)) {
            $arrayDetalhe[idCount] = $count;
            $arrayDetalhe[idHotel] = $qrDesc[idHotel];
            $arrayDetalhe[LOG_BADGE] = $qrDesc[LOG_BADGE];
            $arrayDetalhe[TXT_BADGE] = $qrDesc[TXT_BADGE];
            $arrayDetalhe[COR_BADGE] = $qrDesc[COR_BADGE];
            $arrayDetalhe[COR_TXTBADGE] = $qrDesc[COR_TXTBADGE];
            $arrayDetalhe[idQuarto] = $qrDesc[idQuarto];
            $arrayDetalhe[local] = $qrDesc[local];
            $arrayDetalhe[chale] = $qrDesc[chale];
            $arrayDetalhe[total] = $qrDesc[total];
            $arrayDetalhe[DAT_INI_DEST] = $qrDesc[DAT_INI_DEST];
            $arrayDetalhe[DAT_FIM_DEST] = $qrDesc[DAT_FIM_DEST];
            array_push($arrayDestaques, $arrayDetalhe);
            unset($arrayDetalhe);
            $count++;
        }

        echo json_encode($arrayDestaques);

        break;

    case '19': // envio de vagas whatsapp

        $diasemana = array('Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab');
        @$qrQuarto = json_decode(fnDecode($_GET[ARR_QUARTO]), true);
        $canalWhats = fnLimpaCampoZero($_GET["cal"]);
        $qrDesc = base64_decode(json_decode($_GET["local"], true));

        $hotel = $qrDesc["NOM_HOTEL"];
        $comodidades = $qrDesc["COD_COMOD"];
        $total = $qrQuarto['total'];

        $local = fnDecode($_GET["local"]);
        $hotel = fnDecode($_GET["hotel"]);
        $chale = fnDecode($_GET["chale"]);
        $cod_comod = fnDecode($_GET["comodidades"]);
        $imagem = fnDecode($_GET["imagem"]);
        $total = fnDecode($_GET["total"]);
        $idLead = fnLimpaCampoZero($_GET['idL']);

        if ($canalWhats == 0 || $canalWhats == "") {
            $canalWhats = 1;
        }

        // array(
        //     "local" => $qrQuarto[local],
        //     "chale" => $qrQuarto[chale],
        //     "comodidades" => $qrDesc["DES_COMOD"],
        //     "imagem" => $qrDesc["IMAGEM_MSG"],
        //     "total" => $qrQuarto['total']
        //  );

        $comodidades = explode(",", $cod_comod);
        asort($comodidades, SORT_NUMERIC);
        $arrayComodidades = array();

        foreach ($comodidades as $cod_comod) {

            $sqlComod = "SELECT DES_COMOD FROM COMODIDADES_ADORAI
                         WHERE COD_EMPRESA = 274
                         AND COD_COMOD = $cod_comod";

            $arrayComod = mysqli_query(connTemp(274, ''), $sqlComod);
            $qrComod = mysqli_fetch_assoc($arrayComod);

            array_push($arrayComodidades, $qrComod[DES_COMOD]);
        }

        $chale = explode(" ", $chale);

        $linkEnvio = "https://roteirosadorai.com.br/detalhes.php?datI=" . fnDataShort($dat_inicio) . "&datF=" . fnDataShort($dat_final) . "&idh=" . $cod_hotel . "&idc=" . $cod_chale . "&numC=" . $num_celular . "&cal=" . $canalWhats . "&idL=$idLead&iv=" . base64_encode($total);

        // $linkEnvio = file_get_contents("http://tinyurl.com/api-create.php?url=".$linkEnvio);

        $msgEnvio = "Local: *" . $hotel . "*<br />Chalé: *" . $chale[1] . "*<br /><br />Período: *" . $diasemana[date('w', strtotime($dat_inicio))] . " " . fnDataShort($dat_inicio) . " à " . $diasemana[date('w', strtotime($dat_final))] . " " . fnDataShort($dat_final) . "*<br />";

        foreach ($arrayComodidades as $comodidade) {

            if ($countComod == 5) {
                break;
            }

            $msgEnvio .= "<br />• " . $comodidade;

            $countComod++;
        }

        $msgEnvio .= "<br /><br />Para consultar *valores*, ver *fotos*, detalhes e garantir sua reserva, *clique no link*: 👇<br />$linkEnvio";

        $msgsbtr = nl2br($msgEnvio, true);
        $msgsbtr = str_replace('<br />', "\n", $msgsbtr);

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
        fnEscreve2($session);
        fnEscreve2($des_authkey);
        fnEscreve2($num_celular);
        fnEscreve2($msgsbtr);
        fnEscreve2($imagem);
        fnEscreve2($nomArq);
        fnEscreve2($port);
        print_r($resultcreate);
        echo "</pre>";

        break;

    case '20': // envio de QTD DE vagas whatsapp


        $canalWhats = fnLimpaCampoZero($_GET["cal"]);
        $qtd_quartos = fnLimpaCampoZero($_GET["QTD_VAGAS"]);

        if ($canalWhats == 0 || $canalWhats == "") {
            $canalWhats = 1;
        }

        $txtChales = "chalés";

        if ($qtd_quartos == 1 || $qtd_quartos == 0) {
            $txtChales = "chalé";
        }

        $msgEnvio = "*Conseguimos* disponibilidade para *$qtd_quartos $txtChales:*";

        $msgsbtr = nl2br($msgEnvio, true);
        $msgsbtr = str_replace('<br />', "\n", $msgsbtr);

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

        $resultcreate = FnsendText($session, $des_authkey, $num_celular, $msgsbtr, 3, $port);

        // echo "<pre>";
        // print_r($num_celular);
        // print_r($retorno);
        // echo "</pre>";

        break;

    case '21': // envio de link de pesquisa


        $canalWhats = fnLimpaCampoZero($_GET["cal"]);
        $linkEnvio = fnDecode($_GET["link"]);
        $qtd_quartos = fnLimpaCampoZero($_GET["QTD_VAGAS"]);

        if ($canalWhats == 0 || $canalWhats == "") {
            $canalWhats = 1;
        }

        // $linkEnvio = file_get_contents("http://tinyurl.com/api-create.php?url=".$linkEnvio);

        $msgEnvio = "Para sua comodidade, te enviamos nossas *3 principais disponibilidades* na data desejada. Para visualizar todas as *$qtd_quartos*, *clique o link*: 👇<br />$linkEnvio";

        $msgsbtr = nl2br($msgEnvio, true);
        $msgsbtr = str_replace('<br />', "\n", $msgsbtr);

        // fnEscreve2($msgsbtr);

        include "../_system/whatsapp/wstAdorai.php";

        $sql = "SELECT *
                from SENHAS_WHATSAPP
                WHERE COD_EMPRESA = 274
                AND COD_UNIVEND = $canalWhats
                LIMIT 1";

        // fnEscreve($sql);
        $arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

        $qrBuscaModulos = mysqli_fetch_assoc($arrayQuery);

        $retorno = FnsendText($qrBuscaModulos['NOM_SESSAO'], $qrBuscaModulos['DES_AUTHKEY'], $num_celular, $msgsbtr, 3);

        // echo "<pre>";
        // print_r($num_celular);
        // print_r($retorno);
        // echo "</pre>";

        break;

    case '22': // atualização token de acesso KOMMO

        $subdomain = 'reservasroteirosadorai'; //Subdomain of the account
        $link = 'https://' . $subdomain . '.kommo.com/oauth2/access_token'; //Creating URL for request

        $sqlTkn = "SELECT REFRESH_TOKEN FROM TOKENS_KOMMO
                ORDER BY COD_TOKEN DESC LIMIT 1";
        $qrTkn = mysqli_fetch_assoc(mysqli_query(connTemp(274, ''), $sqlTkn));


        /** Gathering data for request */
        $data = [
            'client_id' => '70b4296c-fd45-4252-882a-0b1c32838c14',
            'client_secret' => 'puyJL8mjBY5VzcGzPBg7ztEvN2NRMunib0wLGbIMIQLDxZZE5ZR9kgUhKlxyeATW',
            'grant_type' => 'refresh_token',
            'refresh_token' => 'def502009c4c1ac0e53ee7555ba1db3821ca588a0fb31307ca0c538797134b894f275292280963bd086011871ade9134b880f019404b61892f83786236adf4127b1629fb3517bc2634e7a8bd54879aa766977194f297e5e240940919e23cf0d1fe47f1f029765d4a38555047e0c5f7277cf39a8f251b2e740a61fa27c370b39a8a363ee617a60eeb84cee1ca3d480b15d3b2f0a6dcc6152e3c69af30b5ba61baf825a35a778e1b7d3c9113d3a594c8988b8ff5f37a85a02b05a78ba363cf596935b642745977b463e71342be0c3e70e26ad3ccffa4367a2eadf266798d457113637dd0a011da431d12ad1f78aefde0af3f21867d34758066d7f8310a98550c9740a0ad607e9eb0f10a4924a6c96718c42fa9e1cfc8b57074bce7fae54f22a30bff27c540ff78ffe601fb2d2025a34a64a8fad81171f1b8a9303e5db5e74d8e49f0b4d7c7ecc4687b917227de323911692d40571e4c63fd301be332605ef526c38b9e72100c27a71cfed7d0a153bb28f5cb887fb05c38531b96aabcb3c843c6a139575b099e5813096df7dca35e15d27f1b593ec53d3bc004e2e56b0098d663c95beda02960c2bcc6bda93336f2f812635abd27fd4c37841dfa80c96b994a3682dae8dbb976a4a5af586cc2e959f5adaa2f9e039b9d25542c0fe730032e851ae8d6449309e46bddeade4c779a8c50',
            'redirect_uri' => 'https://roteirosadorai.com.br/api/callback.php',
        ];

        /**
         * We need to initiate the request to the server.
         * Let’s use the library with cURL.
         * You can also use cross-platform cURL if you don’t code on PHP.
         */
        $curl = curl_init(); //Saving descriptor cURL
        /** Installing required options for session cURL */
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Kommo-oAuth-client/1.0');
        curl_setopt($curl, CURLOPT_URL, $link);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        $out = curl_exec($curl); //Initiating request to API and saving response to variable
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        /** 
         * Now we can process responses from the server. 
         * It’s an example, you can process this data the way you want. 
         */
        $code = (int)$code;
        $errors = [
            400 => 'Bad request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Not found',
            500 => 'Internal server error',
            502 => 'Bad gateway',
            503 => 'Service unavailable',
        ];
        try {
            /** If code of the response is not successful - return message of error */
            if ($code < 200 || $code > 204) {
                throw new Exception(isset($errors[$code]) ? $errors[$code] : 'Undefined error', $code);
            }
        } catch (\Exception $e) {
            die('Error: ' . $e->getMessage() . PHP_EOL . 'Error code: ' . $e->getCode());
        }
        /** 
         * Data will be received in JSON, that’s why to get readable data, 
         * we need to parse data that PHP will understand 
         */
        $response = json_decode($out, true);

        $access_token = $response['access_token']; //Access token 
        $refresh_token = $response['refresh_token']; //Refresh token 
        $token_type = $response['token_type']; //Type of token 
        $expires_in = $response['expires_in']; //After how long does the token expire 

        $dat_expira = date("Y-m-d H:i:s", strtotime("+1 day"));

        $sqlIns = "INSERT INTO TOKENS_KOMMO(
                                TOKEN_TYPE,
                                ACCESS_TOKEN,
                                REFRESH_TOKEN,
                                DAT_EXPIRA
                            )VALUES(
                                '$response[token_type]',
                                '$response[access_token]',
                                '$response[refresh_token]',
                                '$dat_expira'
                            )";
        echo $sqlIns;
        // echo "<pre>";
        // print_r($response);
        // echo "</pre>";
        mysqli_query(connTemp(274, ''), $sqlIns);

        break;

    case '23': // busca token de acesso válido KOMMO da base


        $sqlTkn = "SELECT ACCESS_TOKEN 
                   FROM TOKENS_KOMMO
                   ORDER BY COD_TOKEN DESC LIMIT 1";
        $arrTkn = mysqli_query(connTemp(274, ''), $sqlTkn);

        $qrDesc = mysqli_fetch_array($arrTkn, MYSQLI_ASSOC);

        echo json_encode($qrDesc);


        break;

    case '24': // busca opcionais checkout

        $andOpcionais = "";

        if ($opcionais != "") {
            $andOpcionais = "AND COD_OPCIONAL IN($opcionais)";
        }

        $cod_hotel = fnLimpaCampoZero($cod_hotel);

        $sqlDesc = "SELECT * 
                       FROM OPCIONAIS_ADORAI
                       WHERE COD_EMPRESA = 274
                       AND COD_PROPRIEDADE IN (9999,$cod_hotel)
                       $andOpcionais
                       AND COD_EXCLUSA IS NULL
                       ORDER BY NUM_ORDENAC";
        $arrayDesc = mysqli_query(connTemp(274, ''), $sqlDesc);

        $arrOpcionais = array();
        $arrRegistros = array();

        while ($qrDesc = mysqli_fetch_assoc($arrayDesc)) {
            $arrRegistros[COD_OPCIONAL] = $qrDesc[COD_OPCIONAL];
            $arrRegistros[DES_OPCIONAL] = $qrDesc[DES_OPCIONAL];
            $arrRegistros[ABV_OPCIONAL] = $qrDesc[ABV_OPCIONAL];
            $arrRegistros[DES_COMMENT] = $qrDesc[DES_COMMENT];
            $arrRegistros[TIP_CALCULO] = $qrDesc[TIP_CALCULO];
            $arrRegistros[LOG_CORTESIA] = $qrDesc[LOG_CORTESIA];
            $arrRegistros[LOG_QTD] = $qrDesc[LOG_QTD];
            $arrRegistros[DES_COMMENT] = $qrDesc[DES_COMMENT];
            $arrRegistros[DES_IMAGEM] = $qrDesc[DES_IMAGEM];
            $arrRegistros[VAL_VALOR] = $qrDesc[VAL_VALOR];
            $arrRegistros[QTD_MAXOPCIONAL] = $qrDesc[QTD_MAXOPCIONAL];
            array_push($arrOpcionais, $arrRegistros);
            unset($arrRegistros);
        }

        echo json_encode($arrOpcionais);


        break;

    case '25': // envio de QTD DE vagas whatsapp indisponibilidade


        $canalWhats = fnLimpaCampoZero($_GET["cal"]);
        $qtd_quartos = fnLimpaCampoZero($_GET["QTD_VAGAS"]);

        if ($canalWhats == 0 || $canalWhats == "") {
            $canalWhats = 1;
        }

        $txtChales = "chalés";

        if ($qtd_quartos == 1 || $qtd_quartos == 0) {
            $txtChales = "chalé";
        }

        $msgEnvio = "*Não encontramos* disponibilidades na data desejada. Porém, encontramos disponibilidades para *$qtd_quartos $txtChales com datas próximas a desejada:*";

        $msgsbtr = nl2br($msgEnvio, true);
        $msgsbtr = str_replace('<br />', "\n", $msgsbtr);

        // fnEscreve2($msgsbtr);

        include "../_system/whatsapp/wstAdorai.php";

        $sql = "SELECT *
                from SENHAS_WHATSAPP
                WHERE COD_EMPRESA = 274
                AND COD_UNIVEND = $canalWhats
                LIMIT 1";

        // fnEscreve($sql);
        $arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

        $qrBuscaModulos = mysqli_fetch_assoc($arrayQuery);

        $retorno = FnsendText($qrBuscaModulos['NOM_SESSAO'], $qrBuscaModulos['DES_AUTHKEY'], $num_celular, $msgsbtr, 3);

        // echo "<pre>";
        // print_r($num_celular);
        // print_r($retorno);
        // echo "</pre>";

        break;

    case '26': // insere carrinho checkout

        $sqlChale = "SELECT AC.NOM_QUARTO
                    FROM ADORAI_CHALES AC
                    WHERE AC.COD_EMPRESA = 274
                    AND AC.COD_EXTERNO = $cod_chale
                    AND AC.COD_EXCLUSA = 0";

        $arrayChale = mysqli_query(connTemp(274, ''), $sqlChale);

        $qrChale = mysqli_fetch_assoc($arrayChale);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://bunker.mk/servicoAdorai/carrinho.php',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array(
                'acao' => 'insere',
                'COD_EMPRESA' => '274',
                'COD_ITEM' => $cod_chale,
                'NOM_ITEM' => $qrChale[NOM_QUARTO],
                'DAT_INICIAL' => $dat_inicio,
                'DAT_FINAL' => $dat_final,
                'VALOR' => $vl_pacote,
                'UUID' => '',
                'NOME' => '',
                'SOBRENOME' => '',
                'EMAIL' => '',
                'TELEFONE' => $num_celular
            ),
            CURLOPT_HTTPHEADER => array(
                'Cookie: PHPSESSID=h42t2a054i1vcv2rr37gphntkectt4cojn74b50rb8eq5h0nfu71'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;


        break;

    case '27': // envio de link de pesquisa indisponibilidade


        $canalWhats = fnLimpaCampoZero($_GET["cal"]);
        $linkEnvio = fnDecode($_GET["link"]);
        $qtd_quartos = fnLimpaCampoZero($_GET["QTD_VAGAS"]);

        if ($canalWhats == 0 || $canalWhats == "") {
            $canalWhats = 1;
        }

        // $linkEnvio = file_get_contents("http://tinyurl.com/api-create.php?url=".$linkEnvio);

        $msgEnvio = "Para sua comodidade, te enviamos nossas *3 principais disponibilidades* mais próximas da data desejada. Para visualizar todas as *$qtd_quartos*, *clique o link*: 👇<br />$linkEnvio";

        $msgsbtr = nl2br($msgEnvio, true);
        $msgsbtr = str_replace('<br />', "\n", $msgsbtr);

        // fnEscreve2($msgsbtr);

        include "../_system/whatsapp/wstAdorai.php";

        $sql = "SELECT *
                from SENHAS_WHATSAPP
                WHERE COD_EMPRESA = 274
                AND COD_UNIVEND = $canalWhats
                LIMIT 1";

        fnEscreve($sql);
        $arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

        $qrBuscaModulos = mysqli_fetch_assoc($arrayQuery);

        $retorno = FnsendText($qrBuscaModulos['NOM_SESSAO'], $qrBuscaModulos['DES_AUTHKEY'], $num_celular, $msgsbtr, 3);

        // echo "<pre>";
        // print_r($num_celular);
        // print_r($retorno);
        // echo "</pre>";

        break;

    case '28': // envio de email contato

        header('Content-Type: text/html; charset=utf-8');

        $sql = "SELECT DES_EMAIL FROM SITE_EXTRATO WHERE COD_EMPRESA = $cod_empresa";
        $qrEmail = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa, ''), $sql));

        $sqlEmp = "SELECT NOM_FANTASI FROM EMPRESAS WHERE COD_EMPRESA = $cod_empresa";
        $qrEmp = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $sqlEmp));

        include "../_system/EMAIL/PHPMailer/PHPMailerAutoload.php";
        include '../externo/email/envio_sac.php';

        $texto = 'Nome: ' . fnLimpaCampo(utf8_decode(fnDecode($_REQUEST['NOM_CLIENTE']))) .
            '<br>Email: ' . fnLimpaCampo(fnDecode($_REQUEST['EMAIL'])) .
            '<br>Celular: ' . fnLimpaCampo(fnDecode($_REQUEST['Celular'])) .
            '<br>Assunto: ' . fnLimpaCampo(utf8_decode(fnDecode($_REQUEST['DES_ASSUNTO']))) .
            '<br>Menssagem :<br>' . fnLimpaCampo(utf8_decode(fnDecode($_REQUEST['Soli'])));

        // echo $qrEmp[NOM_FANTASI];

        $email['email1'] = $qrEmail[DES_EMAIL];
        // $email['email1'] = 'ricardoaugusto6693@gmail.com';

        $retorno = fnsacmail(
            $email,
            'Roteiros Adorai',
            "<html>" . $texto . "</html>",
            "Contato - SITE",
            "roteirosadorai.com.br",
            $connAdm->connAdm(),
            connTemp($cod_empresa, ""),
            $cod_empresa
        );

        echo json_encode($retorno);

        break;

    case '29': //invoice

        @$uuid = $_REQUEST['uuid'];

        $sqlDesc = "SELECT AP.*,
                           AP.VALOR AS TOT_PEDIDO,
                           API.*,
                           UNV.NOM_FANTASI,
                           AC.NOM_QUARTO,
                           AC.COD_HOTEL,
                           AC.COD_EXTERNO AS COD_CHALE,
                           AST.ABV_STATUSPAG,
                           AF.DES_FORMAPAG,
                           AF.ABV_FORMAPAG,
                           AP.VALOR_PEDIDO,
                           AF.COD_FORMAPAG,
                           UNV.COD_EXTERNO,
                           ADP.DES_CONTRATO,
                           AP.DAT_CADASTR
                           FROM adorai_pedido AS AP
                           LEFT JOIN adorai_pedido_items AS API ON API.COD_PEDIDO = AP.COD_PEDIDO
                           LEFT JOIN unidadevenda AS UNV ON UNV.COD_EXTERNO = API.COD_PROPRIEDADE
                           LEFT JOIN adorai_propriedades as ADP ON ADP.COD_HOTEL = API.COD_PROPRIEDADE
                           LEFT JOIN adorai_chales AS AC ON AC.COD_EXTERNO = API.COD_CHALE and AC.COD_EXCLUSA = 0
                           LEFT JOIN adorai_statuspag AS AST ON AST.COD_STATUSPAG = AP.COD_STATUSPAG
                           LEFT JOIN adorai_formapag AS AF ON AF.COD_FORMAPAG = AP.COD_FORMAPAG
                           WHERE AP.COD_EMPRESA = 274
                           AND AP.UUID = '$uuid'
                           GROUP BY AP.COD_PEDIDO";

        $arrayDesc = mysqli_query(connTemp(274, ''), $sqlDesc);

        $sqlItens = "SELECT APO.VALOR, OA.ABV_OPCIONAL FROM adorai_pedido_opcionais AS APO
                                LEFT JOIN opcionais_adorai as OA ON OA.COD_OPCIONAL = APO.COD_OPCIONAL
                                LEFT JOIN ADORAI_PEDIDO AS AP ON AP.COD_PEDIDO = APO.COD_PEDIDO
                                WHERE APO.COD_EMPRESA = 274 AND AP.UUID = $uuid";

        $array = mysqli_query(connTemp(274, ''), $sqlItens);

        $arrItens = array();

        while ($qrItem = mysqli_fetch_assoc($array)) {
            $detalhesItem = array(
                "ABV_OPCIONAL" => $qrItem['ABV_OPCIONAL'],
                "VALOR" => $qrItem['VALOR']
            );

            $arrItens[] = $detalhesItem;
        }

        $arrPedido = array();

        $qrDesc = mysqli_fetch_assoc($arrayDesc);
        $arrPedido[DES_CONTRATO] = $qrDesc[DES_CONTRATO];
        $arrPedido[ID_RESERVA] = $qrDesc[ID_RESERVA];
        $arrPedido[DAT_INICIAL] = $qrDesc[DAT_INICIAL];
        $arrPedido[DAT_FINAL] = $qrDesc[DAT_FINAL];
        $arrPedido[ABV_FORMAPAG] = $qrDesc[ABV_FORMAPAG];
        $arrPedido[ABV_STATUSPAG] = $qrDesc[ABV_STATUSPAG];
        $arrPedido[NOM_FANTASI] = $qrDesc[NOM_FANTASI];
        $arrPedido[COD_CHALE] = $qrDesc[COD_CHALE];
        $arrPedido[NOME] = $qrDesc[NOME];
        $arrPedido[SOBRENOME] = $qrDesc[SOBRENOME];
        $arrPedido[EMAIL] = $qrDesc[EMAIL];
        $arrPedido[CPF] = $qrDesc[CPF];
        $arrPedido[TELEFONE] = $qrDesc[TELEFONE];
        $arrPedido[NOM_QUARTO] = $qrDesc[NOM_QUARTO];
        $arrPedido[VALOR_PEDIDO] = $qrDesc[VALOR_PEDIDO];
        $arrPedido[ITENS] = $arrItens;

        echo json_encode($arrPedido, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        break;
    case '30': // faq

        $sqlDesc = "SELECT * FROM PERGUNTAS_ADORAI
                    WHERE COD_EMPRESA = 274
                    ORDER BY NUM_ORDENAC";
        $arrayDesc = mysqli_query(connTemp(274, ''), $sqlDesc);

        $arrPerguntas = array();
        $arrRegistros = array();

        while ($qrDesc = mysqli_fetch_assoc($arrayDesc)) {
            $arrRegistros['DES_PERGUNTA'] = $qrDesc['DES_PERGUNTA'];
            $arrRegistros['DES_RESPOSTA'] = $qrDesc['DES_RESPOSTA'];
            $arrRegistros['NUM_ORDENAC'] = $qrDesc['NUM_ORDENAC'];
            $arrRegistros['DES_IMAGEM'] = $qrDesc['DES_IMAGEM'];
            $arrRegistros['COD_PROPRIEDADE'] = $qrDesc['COD_PROPRIEDADE'];
            array_push($arrPerguntas, $arrRegistros);
            unset($arrRegistros);
        }

        echo json_encode($arrPerguntas);

        break;
    case '31': // faq novo
        $cod_propriedade = $_GET['idp'];

        $sqlDesc = "SELECT * FROM PERGUNTAS_ADORAI
                    WHERE COD_EMPRESA = 274
                    AND COD_PROPRIEDADE = $cod_propriedade
                    ORDER BY NUM_ORDENAC";
        $arrayDesc = mysqli_query(connTemp(274, ''), $sqlDesc);

        $arrPerguntas = array();
        $arrRegistros = array();

        while ($qrDesc = mysqli_fetch_assoc($arrayDesc)) {
            $arrRegistros['DES_PERGUNTA'] = $qrDesc['DES_PERGUNTA'];
            $arrRegistros['DES_RESPOSTA'] = $qrDesc['DES_RESPOSTA'];
            $arrRegistros['NUM_ORDENAC'] = $qrDesc['NUM_ORDENAC'];
            $arrRegistros['DES_IMAGEM'] = $qrDesc['DES_IMAGEM'];
            $arrRegistros['COD_PROPRIEDADE'] = $qrDesc['COD_PROPRIEDADE'];
            array_push($arrPerguntas, $arrRegistros);
            unset($arrRegistros);
        }

        echo json_encode($arrPerguntas);

        break;

    case '32': // envio DE CONFIRMAÇÃO DE RESERVAS whatsapp e email


        $canalWhats = fnLimpaCampoZero($_GET["cal"]);
        $metodo = fnLimpaCampoZero($_GET["metodo"]);
        $infoReserva = json_decode(base64_decode($_GET["info"]), true);

        if ($canalWhats == 0 || $canalWhats == "") {
            $canalWhats = 1;
        }

        $sqlChale = "SELECT NOM_QUARTO FROM ADORAI_CHALES 
                    WHERE COD_EXTERNO = $infoReserva[cod_chale]
                    AND COD_EXCLUSA = 0";
        $qrChale = mysqli_fetch_assoc(mysqli_query(connTemp(274, ''), $sqlChale));

        $infoReserva['chale'] = $qrChale['NOM_QUARTO'];

        switch ($infoReserva['cod_hotel']) {

            case '3010': // MONTANHA

                $msgEnvio = "*Parabéns $infoReserva[nome]*, sua reserva no Roteiros Adorai está confirmada!\r\n\r\nChalé: $infoReserva[chale]\r\nPeríodo: $infoReserva[dat_ini] - $infoReserva[dat_fim]\r\nCidade: Piedade/SP\r\nNome da propriedade: Sítio da Montanha\r\n\r\nO Anfitrião Márcio e sua equipe entrarão em contato com você para te dar as boas vindas, passar as regras e orientações sobre como chegar, etc…\r\nCaso prefira, pode chamá-los no whats (11) 94224-8008.\r\nCaso precise, o Willian cuidará das suas necessidades durante sua hospedagem, (15) 99699-4228.😃\r\n\r\nBem vindos ao Sítio da Montanha\r\n\r\nPara que você possa aproveitar sua experiência com maior conforto e tranquilidade, separamos abaixo as dicas, orientações e politicas da propriedade:\r\n\r\nO Sítio da Montanha é um condomínio de chalés de auto-serviço. Você aluga e usa como sua própria casa de campo.\r\n\r\nPor favor, pedimos sua atenção especial para os horários, as instruções do caminho para o sítio e a regra de silêncio.\r\n\r\n1 - CHECK-IN/CHECK-OUT:\r\n- Check in a partir das 16hrs e até às 22hrs.\r\n- Check out até às 12hrs (meio dia).\r\n- Por favor, quando estiver saindo de casa avise sua previsão de chegada ao Willian.\r\n\r\n2 - COMO CHEGAR:\r\n- Atenção: Os endereços das plataformas não funcionam nas áreas rurais. Usem as instruções abaixo.\r\n- Endereço: Rodovia SP-79 (= BR-478), km 129,5, + 4km de terra sinalizados. Bairro Sarapuí Dos Soares. Piedade SP, 18170-000, Brasil.\r\n- Localização Google: https://goo.gl/maps/U6NJYoeHuJk3n1XF6\r\n- Google Maps ou Waze: utilize as seguintes coordenadas e siga as instruções:\r\n-23.796220, -47.480644\r\n\r\n3 - SILÊNCIO:\r\n- No chalé não permitimos sons altos ou sons que causem desconforto ao hóspede do chalé ao lado. Não é permitido festas a partir das 22hrs. Nossos chalés são um local de repouso e descanso.\r\n\r\n4 - WI-FI:\r\n- A senha será informada no check-in. A velocidade é suficiente para fazer videoconferência e assistir Youtube e Netflix, mas pode variar e falhar.\r\n\r\n5 - EMPÓRIO:\r\n- O nosso empório é aberto de segunda a sexta, das 8h às 12h, das 13h às 17h, e das 18 às 22h. Sábados e domingos das 8h às 12h.\r\n\r\n6 - ÁGUA:\r\n- A ÁGUA DO CHALÉ é de poço artesiano e é muito boa.\r\n\r\n7 - UTENSÍLIOS DOS CHALÉS:\r\n- Panelas, frigideira, leiteira, pratos, copos de água, vinho, cerveja, canecas de café, talheres de mesa, facas de cozinha, colheres, abridor de garrafa, saca rolha, ralador, pano de prato, aparelho de fondue, faca e tábua de churrasco, tábua de queijos, liquidificador, garrafa térmica, filtro de café com suporte, jogo americano..\r\n- Material de consumo: sabonete líquido, detergente, bucha, saco de lixo, e papel higiênico.\r\n- Toda roupa de cama, banho e cozinha.\r\n- Os hóspedes devem trazer apenas coisas pessoais.\r\n- TV Sky via satélite.\r\n\r\n8 - ITENS ADICIONAIS:\r\n- Colocamos no chalé lenha de lareira, carvão, álcool gel, e lenha de fogueira para facilitar. O consumo desses materiais será cobrado no check-out. Portanto, não estão incluídos no preço do chalé.\r\n- BANHO DE SAIS (consulte o preço)\r\n- Sais e espuma para um banho quente e relaxante na sua jacuzzi, e depois limpeza e troca da água. Peça pelo Whatsapp 11 94224-8008. Vocês vão adorar!\r\n\r\n9 - ATENÇÃO!:\r\n- O hóspede concorda em pagar no check-out os danos que causar ao chalé e seus equipamentos e acessórios.\r\n- A voltagem é 127v e as tomadas são de 3 pinos.\r\n- Pais devem trazer roupa de cama de berço para seus bebês. Cuidado: crianças sempre devem estar acompanhadas dos responsáveis, pois várias instalações apresentam algum risco, tais como piscina, jacuzzi, barrancos, lago, brinquedos, etc…\r\n\r\n10 - CANCELAMENTO E REMARCAÇÃO\r\n- Todas as informações sobre cancelametos e adiamentos da estadia estão descritas no nosso contrato de hospedagem que segue anexado logo abaixo. Caso não consiga vizualizar o arquivo, basta acessar esse link: https://docs.google.com/document/d/1Vdr0oG9WV16cBqDDMr6jy9wIx3hn0NWpIrZUU8BFHR4/edit?usp=sharing\r\n\r\nQualquer dúvida é só entrar em contato. 😉\r\n\r\n\r\nObrigado por escolher o Roteiros Adorai. 😊";

                $texto = "<b>Parabéns $infoReserva[nome]</b>, sua reserva no Roteiros Adorai está confirmada!<br /><br />Chalé: $infoReserva[chale]<br />Período: $infoReserva[dat_ini] - $infoReserva[dat_fim]<br />Cidade: Piedade/SP<br />Nome da propriedade: Sítio da Montanha<br /><br />O Anfitrião Márcio e sua equipe entrarão em contato com você para te dar as boas vindas, passar as regras e orientações sobre como chegar, etc…<br />Caso prefira, pode chamá-los no whats (11) 94224-8008.<br />Caso precise, o Willian cuidará das suas necessidades durante sua hospedagem, (15) 99699-4228.😃<br /><br />Bem vindos ao Sítio da Montanha<br /><br />Para que você possa aproveitar sua experiência com maior conforto e tranquilidade, separamos abaixo as dicas, orientações e politicas da propriedade:<br /><br />O Sítio da Montanha é um condomínio de chalés de auto-serviço. Você aluga e usa como sua própria casa de campo.<br /><br />Por favor, pedimos sua atenção especial para os horários, as instruções do caminho para o sítio e a regra de silêncio.<br /><br />1 - CHECK-IN/CHECK-OUT:<br />- Check in a partir das 16hrs e até às 22hrs.<br />- Check out até às 12hrs (meio dia).<br />- Por favor, quando estiver saindo de casa avise sua previsão de chegada ao Willian.<br /><br />2 - COMO CHEGAR:<br />- Atenção: Os endereços das plataformas não funcionam nas áreas rurais. Usem as instruções abaixo.<br />- Endereço: Rodovia SP-79 (= BR-478), km 129,5, + 4km de terra sinalizados. Bairro Sarapuí Dos Soares. Piedade SP, 18170-000, Brasil.<br />- Localização Google: https://goo.gl/maps/U6NJYoeHuJk3n1XF6<br />- Google Maps ou Waze: utilize as seguintes coordenadas e siga as instruções:<br />-23.796220, -47.480644<br /><br />3 - SILÊNCIO:<br />- No chalé não permitimos sons altos ou sons que causem desconforto ao hóspede do chalé ao lado. Não é permitido festas a partir das 22hrs. Nossos chalés são um local de repouso e descanso.<br /><br />4 - WI-FI:<br />- A senha será informada no check-in. A velocidade é suficiente para fazer videoconferência e assistir Youtube e Netflix, mas pode variar e falhar.<br /><br />5 - EMPÓRIO:<br />- O nosso empório é aberto de segunda a sexta, das 8h às 12h, das 13h às 17h, e das 18 às 22h. Sábados e domingos das 8h às 12h.<br /><br />6 - ÁGUA:<br />- A ÁGUA DO CHALÉ é de poço artesiano e é muito boa.<br /><br />7 - UTENSÍLIOS DOS CHALÉS:<br />- Panelas, frigideira, leiteira, pratos, copos de água, vinho, cerveja, canecas de café, talheres de mesa, facas de cozinha, colheres, abridor de garrafa, saca rolha, ralador, pano de prato, aparelho de fondue, faca e tábua de churrasco, tábua de queijos, liquidificador, garrafa térmica, filtro de café com suporte, jogo americano..<br />- Material de consumo: sabonete líquido, detergente, bucha, saco de lixo, e papel higiênico.<br />- Toda roupa de cama, banho e cozinha.<br />- Os hóspedes devem trazer apenas coisas pessoais.<br />- TV Sky via satélite.<br /><br />8 - ITENS ADICIONAIS:<br />- Colocamos no chalé lenha de lareira, carvão, álcool gel, e lenha de fogueira para facilitar. O consumo desses materiais será cobrado no check-out. Portanto, não estão incluídos no preço do chalé.<br />- BANHO DE SAIS (consulte o preço)<br />- Sais e espuma para um banho quente e relaxante na sua jacuzzi, e depois limpeza e troca da água. Peça pelo Whatsapp 11 94224-8008. Vocês vão adorar!<br /><br />9 - ATENÇÃO!:<br />- O hóspede concorda em pagar no check-out os danos que causar ao chalé e seus equipamentos e acessórios.<br />- A voltagem é 127v e as tomadas são de 3 pinos.<br />- Pais devem trazer roupa de cama de berço para seus bebês. Cuidado: crianças sempre devem estar acompanhadas dos responsáveis, pois várias instalações apresentam algum risco, tais como piscina, jacuzzi, barrancos, lago, brinquedos, etc…<br /><br />10 - CANCELAMENTO E REMARCAÇÃO<br />- Todas as informações sobre cancelametos e adiamentos da estadia estão descritas no nosso contrato de hospedagem que segue anexado logo abaixo. Caso não consiga vizualizar o arquivo, basta acessar esse link: https://docs.google.com/document/d/1Vdr0oG9WV16cBqDDMr6jy9wIx3hn0NWpIrZUU8BFHR4/edit?usp=sharing<br /><br />Qualquer dúvida é só entrar em contato. 😉<br /><br /><br />Obrigado por escolher o Roteiros Adorai. 😊";

                break;

            case '3008': // FALCÃO

                $msgEnvio = "*Parabéns, $infoReserva[nome]*! Sua reserva no Roteiros Adorai está confirmada!\r\n\r\nChalé: $infoReserva[chale]\r\nPeríodo: $infoReserva[dat_ini] - $infoReserva[dat_fim]\r\nCidade: Cunha/SP\r\nNome da propriedade: Monte do Falcão\r\n\r\nA Anfitriã, Isabella, entrará em contato com você para te passar algumas informações importantes sobre a sua estadia.\r\nCaso prefira, pode chamá-la pelo whatsapp no número +351 911 504 368.\r\n\r\nBem vindos ao sítio Monte do Falcão\r\n\r\nPara que você possa aproveitar sua experiência com maior conforto e tranquilidade, separamos abaixo as dicas e orientações:\r\n\r\n1 - CHECK-IN/CHECK-OUT:\r\n- Nosso check-in é a partir das 16hs.\r\n- Nosso check-out até 12hs (Meio dia)\r\n- Nossos colaboradores, Tânia e Cassiano estarão lá para recebê-los até às 17h.\r\n- Caso o check-in seja após as 17hs, terão que combinar com eles no número (12) 99795-5922, o horário de check-in para eles possam ir ao sítio receber vocês.\r\n- APÓS AS 21Hs somente self check-in com as informações enviadas pela Isabella.\r\n\r\n2 - COMO CHEGAR:\r\n- O trajeto do centro da cidade de Cunha até o sítio é de 4km (exatamente a 1° entrada a direita após a placa de KM 4).\r\n- O acesso é todo asfaltado, porém não pega muito bem celular no caminho, então a melhor forma de chegar é pelo Google Maps, pois a  Waze não funciona bem na região.\r\n- Endereço: estrada municipal KM 4, Roça Grande, Cunha - SP\r\n- Localização google maps:\r\nhttps://maps.app.goo.gl/fX2ZAHwNqCN7sUGU6\r\n- Em nossa propriedade há também mais oito casas. Ao entrar na propriedade é só manter sempre à esquerda até achar seu chalé/casa.\r\n\r\n3 - Politica de cancelamento e contrato de hospedagem:\r\n- Para conferir o nosso contrato de forma integral basta acessar o arquivo anexado.\r\n- Caso não consiga vizualizar o arquivo basta acessar através desse link: https://docs.google.com/document/d/1E1DtZzwuLPu9IsPf_SltvhDxqyqxJMI-QwrRL2o77zM/edit?usp=sharing\r\n\r\n\r\nQualquer dúvida é só entrar em contato. 😉\r\n\r\nObrigado por escolher o Roteiros Adorai. 😊";

                $texto = "<b>Parabéns, $infoReserva[nome]</b>! Sua reserva no Roteiros Adorai está confirmada!<br /><br />Chalé: $infoReserva[chale]<br />Período: $infoReserva[dat_ini] - $infoReserva[dat_fim]<br />Cidade: Cunha/SP<br />Nome da propriedade: Monte do Falcão<br /><br />A Anfitriã, Isabella, entrará em contato com você para te passar algumas informações importantes sobre a sua estadia.<br />Caso prefira, pode chamá-la pelo whatsapp no número +351 911 504 368.<br /><br />Bem vindos ao sítio Monte do Falcão<br /><br />Para que você possa aproveitar sua experiência com maior conforto e tranquilidade, separamos abaixo as dicas e orientações:<br /><br />1 - CHECK-IN/CHECK-OUT:<br />- Nosso check-in é a partir das 16hs.<br />- Nosso check-out até 12hs (Meio dia)<br />- Nossos colaboradores, Tânia e Cassiano estarão lá para recebê-los até às 17h.<br />- Caso o check-in seja após as 17hs, terão que combinar com eles no número (12) 99795-5922, o horário de check-in para eles possam ir ao sítio receber vocês.<br />- APÓS AS 21Hs somente self check-in com as informações enviadas pela Isabella.<br /><br />2 - COMO CHEGAR:<br />- O trajeto do centro da cidade de Cunha até o sítio é de 4km (exatamente a 1° entrada a direita após a placa de KM 4).<br />- O acesso é todo asfaltado, porém não pega muito bem celular no caminho, então a melhor forma de chegar é pelo Google Maps, pois a  Waze não funciona bem na região.<br />- Endereço: estrada municipal KM 4, Roça Grande, Cunha - SP<br />- Localização google maps:<br />https://maps.app.goo.gl/fX2ZAHwNqCN7sUGU6<br />- Em nossa propriedade há também mais oito casas. Ao entrar na propriedade é só manter sempre à esquerda até achar seu chalé/casa.<br /><br />3 - Politica de cancelamento e contrato de hospedagem:<br />- Para conferir o nosso contrato de forma integral basta acessar o arquivo anexado.<br />- Caso não consiga vizualizar o arquivo basta acessar através desse link: https://docs.google.com/document/d/1E1DtZzwuLPu9IsPf_SltvhDxqyqxJMI-QwrRL2o77zM/edit?usp=sharing<br /><br /><br />Qualquer dúvida é só entrar em contato. 😉<br /><br />Obrigado por escolher o Roteiros Adorai. 😊";

                break;

            case '956': //REAL

                $msgEnvio = "*Parabéns $infoReserva[nome]*, sua reserva no Roteiros Adorai está confirmada!\r\n\r\nChalé: $infoReserva[chale]\r\nPeríodo: $infoReserva[dat_ini] - $infoReserva[dat_fim]\r\nCidade: Paraty/RJ\r\nNome da propriedade: Adorai Real\r\n\r\nA Anfitriã, Isabella entrará em contato com você para te passar algumas informações\r\nimportantes sobre sua estadia.\r\nCaso prefira, pode chamá-la pelo whatsapp no número (24) 99251-8298.\r\n\r\nBem vindos ao chalés Paraty Real\r\n\r\n\r\nPara que você possa aproveitar sua experiência com maior conforto e tranquilidade, separamos abaixo as dicas e orientações.\r\n\r\nRecomendamos que leia atentamente.\r\n\r\n\r\n1- COMO CHEGAR NO ADORAI REAL:\r\n- Localização: https://goo.gl/maps/GVSZzTLmvGFqBJy2A\r\n- Estrada Paraty Cunha KM 9,5\r\n- Ponto de referência: Após percorrer 9,5 km a partir do trevo de Paraty, vire à esquerda quando avistar um bambuzal com uma placa redonda indicando \"Chalés Paraty Real\". Siga pela estrada que leva diretamente à garagem, onde haverá sinalização indicando o caminho para a recepção.\r\n\r\n2- DATA DA ESTADIA:\r\n-Confira no voucher que lhe enviamos por e-mail a data e o chalé que você reservou\r\n\r\n\r\n3- DIAS E HORÁRIOS DE ENTRADA:\r\n-Check-in (inicio da estadia): A partir das 14hrs e até as 22hrs (após esse horario, é possivel realizar o self check-in, com as informações enviadas pela anfitriã)\r\n-Check-out (fim da estadia): Até 11hrs (caso passe do horário do check-out, será cobrado valor adicional)\r\n\r\n4- SUPORTE E AJUDA DURANTE A ESTADIA:\r\n- Para qualquer necessidade durante a sua estadia, por gentileza entrar em contato com a anfitriã Isabela pelo número: (24) 99251-8298. Esse mesmo número irá te enviar as informações sobre o seu check-in 48hrs antes da data da chegada\r\n\r\n\r\n5- INTERNET:\r\n- Senha da Recepção: 12345678\r\n- Senha da acomodação: hospedagem\r\n\r\n6- REFEIÇÕES:\r\nCafé da manhã: O café da manhã será servido em cestas de piquenique, na porta do seu chalé, entre 08h e 08:30h.\r\nLojinha do chalé: Nela você encontra opções de bebidas, sais de banho, espuma e vários outros itens. Ela funciona das 08:00hrs até as 20:00hrs\r\nOferecemos algumas indicações de deliverys próximos ao chalé, quando o seu check-in estiver chegando você irá receber uma llista com algumas indicações\r\nO chalé possui uma mini cozinha\r\n\r\n\r\n7- PETS:\r\nSeu pet é super bem-vindo, veja algumas regras e orientações:\r\n- Eles não podem subir na cama.\r\n- Recolher e limpar a sujeira é de responsabilidade do hóspede\r\n- Fora do chalé não existem cercas, se precisar, o cão deverá ser preso na guia.\r\n- A diária de cães acompanhantes de hóspedes é de R$90,00 por noite.\r\n- Qualquer dano causado pelo PET é de responsabilidade do hóspede\r\n- O hóspede deverá levar os itens de higiene para o pet\r\n\r\n\r\n8- LIMPEZA DOS CHALÉS DURANTE A ESTADIA:\r\n- O Chalé será entregue limpo e higienizado, a limpeza e arrumação durante o período de locação será de responsabilidade do hóspede.\r\n\r\n\r\n9- PRIVACIDADE DOS CHALÉS:\r\n- Cada chalé tem entrada independente e não há necessidade de contato entre hóspedes.\r\n- É proibido intimidades com janelas ou portas abertas.\r\n\r\n\r\n10- SILÊNCIO:\r\n- Adorai Real é um espaço de relaxamento e paz, portanto, casais, famílias e grupos, antes de alugar precisam concordar com Silêncio. Os sons do seu chalé, música e vozes, não podem ser ouvidos pelos seus vizinhos. Essa regra é muito importante para nossos hóspedes, pois é um espaço para descansar e curtir a natureza e os confortos que oferece. A partir das 20h00min até as 08:00h, o SILÊNCIO é primordial para tranquilidade dos hóspedes, isso é muito importante para o convívio em grupo.\r\n\r\n\r\n11- UTENSÍLIOS DOS CHALÉS:\r\n- Os chalés são equipados com um cooktop, forninho, frigobar e utensílios para um casal, tendo a possibilidade de fazer comidas leves.\r\n\r\n12- O QUE NÃO É PERMITIDO:\r\n- Proibido narguilé e cigarro de qualquer tipo dentro dos chalés, LEI ESTADUAL No 13.541, DE 07 DE MAIO DE 2009.\r\n- Caso seja detectado uso será cobrado taxa de R$500,00 no momento do check-out.\r\n- Proibidos festas e som alto em qualquer horário\r\n\r\n\r\n13- POLÍTICA DE CANCELAMENTO E REAGENDAMENTO:\r\n- Todas as informações a respeito de cancelamento, reembolso e reagendamento estão descritas no voucher e em nosso contrato de hospedagem que está anexo logo abaixo.\r\n- Caso não consiga abrir o arquivo, basta acessar esse link:\r\n\r\nQualquer dúvida, estamos à disposição. 😉\r\n\r\nObrigado por escolher o Roteiros Adorai. 😊";

                $texto = "<b>Parabéns $infoReserva[nome]</b>, sua reserva no Roteiros Adorai está confirmada!<br /><br />Chalé: $infoReserva[chale]<br />Período: $infoReserva[dat_ini] - $infoReserva[dat_fim]<br />Cidade: Paraty/RJ<br />Nome da propriedade: Adorai Real<br /><br />A Anfitriã, Isabella entrará em contato com você para te passar algumas informações<br />importantes sobre sua estadia.<br />Caso prefira, pode chamá-la pelo whatsapp no número (24) 99251-8298.<br /><br />Bem vindos ao chalés Paraty Real<br /><br /><br />Para que você possa aproveitar sua experiência com maior conforto e tranquilidade, separamos abaixo as dicas e orientações.<br /><br />Recomendamos que leia atentamente.<br /><br /><br />1- COMO CHEGAR NO ADORAI REAL:<br />- Localização: https://goo.gl/maps/GVSZzTLmvGFqBJy2A<br />- Estrada Paraty Cunha KM 9,5<br />- Ponto de referência: Após percorrer 9,5 km a partir do trevo de Paraty, vire à esquerda quando avistar um bambuzal com uma placa redonda indicando \"Chalés Paraty Real\". Siga pela estrada que leva diretamente à garagem, onde haverá sinalização indicando o caminho para a recepção.<br /><br />2- DATA DA ESTADIA:<br />-Confira no voucher que lhe enviamos por e-mail a data e o chalé que você reservou<br /><br /><br />3- DIAS E HORÁRIOS DE ENTRADA:<br />-Check-in (inicio da estadia): A partir das 14hrs e até as 22hrs (após esse horario, é possivel realizar o self check-in, com as informações enviadas pela anfitriã)<br />-Check-out (fim da estadia): Até 11hrs (caso passe do horário do check-out, será cobrado valor adicional)<br /><br />4- SUPORTE E AJUDA DURANTE A ESTADIA:<br />- Para qualquer necessidade durante a sua estadia, por gentileza entrar em contato com a anfitriã Isabela pelo número: (24) 99251-8298. Esse mesmo número irá te enviar as informações sobre o seu check-in 48hrs antes da data da chegada<br /><br /><br />5- INTERNET:<br />- Senha da Recepção: 12345678<br />- Senha da acomodação: hospedagem<br /><br />6- REFEIÇÕES:<br />Café da manhã: O café da manhã será servido em cestas de piquenique, na porta do seu chalé, entre 08h e 08:30h.<br />Lojinha do chalé: Nela você encontra opções de bebidas, sais de banho, espuma e vários outros itens. Ela funciona das 08:00hrs até as 20:00hrs<br />Oferecemos algumas indicações de deliverys próximos ao chalé, quando o seu check-in estiver chegando você irá receber uma llista com algumas indicações<br />O chalé possui uma mini cozinha<br /><br /><br />7- PETS:<br />Seu pet é super bem-vindo, veja algumas regras e orientações:<br />- Eles não podem subir na cama.<br />- Recolher e limpar a sujeira é de responsabilidade do hóspede<br />- Fora do chalé não existem cercas, se precisar, o cão deverá ser preso na guia.<br />- A diária de cães acompanhantes de hóspedes é de R$90,00 por noite.<br />- Qualquer dano causado pelo PET é de responsabilidade do hóspede<br />- O hóspede deverá levar os itens de higiene para o pet<br /><br /><br />8- LIMPEZA DOS CHALÉS DURANTE A ESTADIA:<br />- O Chalé será entregue limpo e higienizado, a limpeza e arrumação durante o período de locação será de responsabilidade do hóspede.<br /><br /><br />9- PRIVACIDADE DOS CHALÉS:<br />- Cada chalé tem entrada independente e não há necessidade de contato entre hóspedes.<br />- É proibido intimidades com janelas ou portas abertas.<br /><br /><br />10- SILÊNCIO:<br />- Adorai Real é um espaço de relaxamento e paz, portanto, casais, famílias e grupos, antes de alugar precisam concordar com Silêncio. Os sons do seu chalé, música e vozes, não podem ser ouvidos pelos seus vizinhos. Essa regra é muito importante para nossos hóspedes, pois é um espaço para descansar e curtir a natureza e os confortos que oferece. A partir das 20h00min até as 08:00h, o SILÊNCIO é primordial para tranquilidade dos hóspedes, isso é muito importante para o convívio em grupo.<br /><br /><br />11- UTENSÍLIOS DOS CHALÉS:<br />- Os chalés são equipados com um cooktop, forninho, frigobar e utensílios para um casal, tendo a possibilidade de fazer comidas leves.<br /><br />12- O QUE NÃO É PERMITIDO:<br />- Proibido narguilé e cigarro de qualquer tipo dentro dos chalés, LEI ESTADUAL No 13.541, DE 07 DE MAIO DE 2009.<br />- Caso seja detectado uso será cobrado taxa de R$500,00 no momento do check-out.<br />- Proibidos festas e som alto em qualquer horário<br /><br /><br />13- POLÍTICA DE CANCELAMENTO E REAGENDAMENTO:<br />- Todas as informações a respeito de cancelamento, reembolso e reagendamento estão descritas no voucher e em nosso contrato de hospedagem que está anexo logo abaixo.<br />- Caso não consiga abrir o arquivo, basta acessar esse link:<br /><br />Qualquer dúvida, estamos à disposição. 😉<br /><br />Obrigado por escolher o Roteiros Adorai. 😊";

                break;

            case '5158': // PRAIA

                $msgEnvio = "*Parabéns $infoReserva[nome]*, sua reserva no Roteiros Adorai está confirmada!\r\n\r\nChalé: $infoReserva[chale]\r\nPeríodo: $infoReserva[dat_ini] - $infoReserva[dat_fim]\r\nCidade: Paraty/RJ\r\n\r\nNome da propriedade: Adorai Praia\r\n\r\nPara que você possa aproveitar sua experiência com maior conforto e tranquilidade, separamos abaixo as dicas e orientações.\r\n\r\nRecomendamos que leia atentamente.\r\n\r\n\r\n1- COMO CHEGAR NO ADORAI CHALÉS:\r\n- Basta consultar no Google Maps ou Waze o destino: Rua da Praia, 100 - Cantinho do sossego - Prainha de Mambucaba, Paraty - RJ, 23970-000, Brasil\r\n\r\n2- DATA DA ESTADIA:\r\n-Confira no voucher que lhe enviamos por e-mail a data e o chalé que você reservou\r\n\r\n3- DIAS E HORÁRIOS DE ENTRADA:\r\n-Check-in - início da estadia: A partir das 14hs.\r\n-Check-out - fim da estadia: Até 12h (meio dia)\r\n\r\n4- SUPORTE E AJUDA DURANTE A ESTADIA:\r\n- Qualquer necessidade durante a sua estadia, basta entrar em contato com a recepção. 😉\r\n\r\n5- REFEIÇÕES:\r\nRestaurante - Quiosque na praia anexo à Pousada, pagamento a parte\r\nCafé da manhã: Incluso na diária. É servido no restaurante da pousada, das 7:45 hrs até às 10:00 hrs da manhã.\r\nCafé da tarde cortesia: Todos os dias às 17 horas a Pousada serve um cafezinho da tarde como cortesia.\r\nHorário das 8:30 às 18hs\r\nPara jantar 3 opções:\r\nRefeições rápidas na pousada, oferecem o espaço de alimentação com os utensílios necessários.\r\nExcelente opções de restaurantes no bairro ao lado, para comer presencial ou pedir delivery.\r\n\r\n6- PETS:\r\nNão são aceitos pets na estadia\r\n\r\n7- LIMPEZA DURANTE A ESTADIA:\r\n- A HOSPEDAGEM conta com serviço de quarto durante a estadia.\r\n\r\n\r\n8- PRIVACIDADE DOS CHALÉS:\r\n- Cada chalé tem entrada independente e não há necessidade de contato entre hóspedes..\r\n- É proibido intimidades com janelas ou portas abertas.\r\n\r\n9- SILÊNCIO:\r\n- O Adorai Praia é um espaço de relaxamento e paz, portanto, casais, famílias e grupos, antes de alugar precisam concordar com Silêncio. Os sons do seu chalé, música e vozes, não podem ser ouvidos pelos seus vizinhos. Essa regra é muito importante para nossos hóspedes, pois é um espaço para descansar e curtir a natureza e os confortos que oferece.\r\n\r\n10- REGRAS E NORMAS GERAIS:\r\n- Proibido narguilé e cigarro de qualquer tipo dentro dos chalés, fumar somente é permitido ao ar livre, e as bitucas devem ser apagadas na água e jogadas no lixo.\r\n- Não colocar malas em cima da mesa da TV, pois não sustenta peso e pode danificá-la\r\n- Todas as tomadas são 110v\r\n- Para trancar a porta de vidro é necessário duas voltas na chave\r\n- Os anfitriões oferecem a manutenção dos chalés gratuitamente, sendo que as roupas de cama e banho serão trocadas a cada dois dias. Caso não deseje esse serviço, por favor comunique a recepção\r\n- As toalhas de banho e rosto são para uso exclusivo dentro do Chalé, portanto não é permitido levá-las para a praia. Caso seja necessário, será disponibilizado toalhas de praia, que deverão ser devolvidas no check-out\r\n- Os serviços de Aluguel de cadeiras de Praia e Caiaques devem ser solicitados na recepção\r\n- Ao abrir a torneira de água quente do chuveiro,aguardar alguns segundos antes de abrir a de água fria\r\n- Fechar a torneira da ducha higiênica após o uso\r\n- Não jogar papel no vaso sanitário\r\n- Não pendurar roupas ou toalhas no corpo da varanda\r\n\r\n11- POLÍTICA DE CANCELAMENTO E REAGENDAMENTO:\r\n- Todas as informações a respeito de cancelamento, reembolso e reagendamento estão descritas no contrato anexado ao voucher e a esta mensagem.\r\n\r\n\r\nQualquer dúvida, estamos à disposição. 😉\r\n\r\nObrigado por escolher o Roteiros Adorai. 😊";

                $texto = "<b>Parabéns $infoReserva[nome]</b>, sua reserva no Roteiros Adorai está confirmada!<br /><br />Chalé: $infoReserva[chale]<br />Período: $infoReserva[dat_ini] - $infoReserva[dat_fim]<br />Cidade: Paraty/RJ<br /><br />Nome da propriedade: Adorai Praia<br /><br />Para que você possa aproveitar sua experiência com maior conforto e tranquilidade, separamos abaixo as dicas e orientações.<br /><br />Recomendamos que leia atentamente.<br /><br /><br />1- COMO CHEGAR NO ADORAI CHALÉS:<br />- Basta consultar no Google Maps ou Waze o destino: Rua da Praia, 100 - Cantinho do sossego - Prainha de Mambucaba, Paraty - RJ, 23970-000, Brasil<br /><br />2- DATA DA ESTADIA:<br />-Confira no voucher que lhe enviamos por e-mail a data e o chalé que você reservou<br /><br />3- DIAS E HORÁRIOS DE ENTRADA:<br />-Check-in - início da estadia: A partir das 14hs.<br />-Check-out - fim da estadia: Até 12h (meio dia)<br /><br />4- SUPORTE E AJUDA DURANTE A ESTADIA:<br />- Qualquer necessidade durante a sua estadia, basta entrar em contato com a recepção. 😉<br /><br />5- REFEIÇÕES:<br />Restaurante - Quiosque na praia anexo à Pousada, pagamento a parte<br />Café da manhã: Incluso na diária. É servido no restaurante da pousada, das 7:45 hrs até às 10:00 hrs da manhã.<br />Café da tarde cortesia: Todos os dias às 17 horas a Pousada serve um cafezinho da tarde como cortesia.<br />Horário das 8:30 às 18hs<br />Para jantar 3 opções:<br />Refeições rápidas na pousada, oferecem o espaço de alimentação com os utensílios necessários.<br />Excelente opções de restaurantes no bairro ao lado, para comer presencial ou pedir delivery.<br /><br />6- PETS:<br />Não são aceitos pets na estadia<br /><br />7- LIMPEZA DURANTE A ESTADIA:<br />- A HOSPEDAGEM conta com serviço de quarto durante a estadia.<br /><br /><br />8- PRIVACIDADE DOS CHALÉS:<br />- Cada chalé tem entrada independente e não há necessidade de contato entre hóspedes..<br />- É proibido intimidades com janelas ou portas abertas.<br /><br />9- SILÊNCIO:<br />- O Adorai Praia é um espaço de relaxamento e paz, portanto, casais, famílias e grupos, antes de alugar precisam concordar com Silêncio. Os sons do seu chalé, música e vozes, não podem ser ouvidos pelos seus vizinhos. Essa regra é muito importante para nossos hóspedes, pois é um espaço para descansar e curtir a natureza e os confortos que oferece.<br /><br />10- REGRAS E NORMAS GERAIS:<br />- Proibido narguilé e cigarro de qualquer tipo dentro dos chalés, fumar somente é permitido ao ar livre, e as bitucas devem ser apagadas na água e jogadas no lixo.<br />- Não colocar malas em cima da mesa da TV, pois não sustenta peso e pode danificá-la<br />- Todas as tomadas são 110v<br />- Para trancar a porta de vidro é necessário duas voltas na chave<br />- Os anfitriões oferecem a manutenção dos chalés gratuitamente, sendo que as roupas de cama e banho serão trocadas a cada dois dias. Caso não deseje esse serviço, por favor comunique a recepção<br />- As toalhas de banho e rosto são para uso exclusivo dentro do Chalé, portanto não é permitido levá-las para a praia. Caso seja necessário, será disponibilizado toalhas de praia, que deverão ser devolvidas no check-out<br />- Os serviços de Aluguel de cadeiras de Praia e Caiaques devem ser solicitados na recepção<br />- Ao abrir a torneira de água quente do chuveiro,aguardar alguns segundos antes de abrir a de água fria<br />- Fechar a torneira da ducha higiênica após o uso<br />- Não jogar papel no vaso sanitário<br />- Não pendurar roupas ou toalhas no corpo da varanda<br /><br />11- POLÍTICA DE CANCELAMENTO E REAGENDAMENTO:<br />- Todas as informações a respeito de cancelamento, reembolso e reagendamento estão descritas no contrato anexado ao voucher e a esta mensagem.<br /><br /><br />Qualquer dúvida, estamos à disposição. 😉<br /><br />Obrigado por escolher o Roteiros Adorai. 😊";

                break;

            case '5156': // ACONCHEGO

                $msgEnvio = "*Parabéns, $infoReserva[nome]*! Sua reserva no Roteiros Adorai está confirmada!\r\n\r\nChalé: $infoReserva[chale]\r\nPeríodo: $infoReserva[dat_ini] - $infoReserva[dat_fim]\r\nCidade: Cunha/SP\r\nNome da propriedade: Adorai Aconchego\r\n\r\nOs Anfitriões entrarão em contato com você para te passar algumas informações importantes sobre a sua estadia.\r\nCaso prefira, pode chamá-la pelo whatsapp no número 12 99662-3988.\r\n\r\nBem vindos ao Adorai Aconchego\r\n\r\nPara que você possa aproveitar sua experiência com maior conforto e tranquilidade, separamos abaixo as dicas e orientações:\r\n\r\n1 - CHECK-IN/CHECK-OUT:\r\n- Nosso check-in é a partir das 14:00hrs a até as 21:00hrs.\r\n- Nosso check-out até 12hs (Meio dia)\r\n- Disponibilizamos apenas 01 chave por apartamento. Ao sair, deixe na recepção, pois saberemos que o apartamento está disponível para arrumação colocando também a placa de porta.\r\n\r\n2 - COMO CHEGAR:\r\n- Link do maps: https://maps.app.goo.gl/KoWFL46qtycNoC158\r\n- Existem dois caminhos para chegar na pousada, recomendamos por favor que sigam as indicações:\r\n- Assim que entrar no portal da cidade, siga na Rua Alameda Francisco da Cunha Menezes, primeira rotatória entra na terceira saída a direita na rua Dhaer Pedro. Siga em frente e vire à esquerda  na Av Augusto Galvão de França, seguindo em frente passa um posto de combustível (BR), continua subindo sempre reto e sairá da cidade, encontrando nossas placas indicativas. No km 4 quando avistar várias placas vire à esquerda e siga 1km de terra.Temos um muro branco com acabamento amarelo e uma placa da pousada à esquerda.\r\n- Recomendamos a vir pelo google maps que é mais confiável.\r\n\r\n4 - ALIMENTAÇÃO:\r\nO café da manhã é servido no restaurante das 08:00hrs ás 10:30hrs\r\nO restaurante funciona de quinta a domingo, das 13:30hrs às 22:00 hrs. Pedidos são aceitos até as 21:00hrs\r\nDelivery: consultar disponibilidade\r\n\r\n5 - PETS:\r\nSeu pet é bem vindo em nossa pousada, entretanto há regras necessárias para um bom convívio junto aos demais hóspedes, quais sejam, mantê-lo em guia, não deixá-lo sozinho na acomodação quando se ausentar da pousada e não adentrar a área do restaurante.\r\nCobramos uma taxa extra de R$90,00 por diária e por pet\r\n\r\n6 - REGRAS E INFORMAÇÕES GERAIS:\r\nAs tomadas nos apartamentos são 110V e 220V.\r\nSenha do Wi-fi: bocaina1\r\nOs frigobares ficam desligados, entretanto, caso seja de seu interesse, bebidas poderão ser solicitadas junto à recepção no momento do Check In ou em outro momento oportuno e serão levadas até sua acomodação.\r\nCaso em sua acomodação não haja secador de cabelo, este poderá ser solicitado junto à recepção, bem como travesseiro ou manta extra.\r\nAs toalhas da acomodação não poderão ser retiradas para uso na piscina, entretanto dispomos de toalhas para esse uso, as quais poderão ser solicitadas na recepção e deverão ser utilizadas apenas nas dependências da pousada e devolvidas.\r\nQuando se ausentar da acomodação favor desligarem as luzes, ventiladores, ar condicionado e banheira, sendo que o não cumprimento poderá implicar em taxa extra.\r\nNão é permitido o consumo de bebidas e comidas trazidas para a pousada, fora da acomodação, ou seja, nas áreas comuns, inclusive na piscina.\r\nConforme Lei Estadual número 13.541 de 07/05/2009 é expressamente proibido fumar dentro das acomodações.\r\n\r\n\r\n7 - CONTRATO DE HOSPEDAGEM:\r\n- Você receberá o nosso contrato anexado ao seu voucher, via e-mail e anexado a essa mensagem\r\n- Caso não consiga visualizar o arquivo basta acessar através deste link:\r\n\r\nQualquer dúvida é só entrar em contato. 😉\r\n\r\nObrigado por escolher o Roteiros Adorai. 😊";

                $texto = "<b>Parabéns, $infoReserva[nome]</b>! Sua reserva no Roteiros Adorai está confirmada!<br /><br />Chalé: $infoReserva[chale]<br />Período: $infoReserva[dat_ini] - $infoReserva[dat_fim]<br />Cidade: Cunha/SP<br />Nome da propriedade: Adorai Aconchego<br /><br />Os Anfitriões entrarão em contato com você para te passar algumas informações importantes sobre a sua estadia.<br />Caso prefira, pode chamá-la pelo whatsapp no número 12 99662-3988.<br /><br />Bem vindos ao Adorai Aconchego<br /><br />Para que você possa aproveitar sua experiência com maior conforto e tranquilidade, separamos abaixo as dicas e orientações:<br /><br />1 - CHECK-IN/CHECK-OUT:<br />- Nosso check-in é a partir das 14:00hrs a até as 21:00hrs.<br />- Nosso check-out até 12hs (Meio dia)<br />- Disponibilizamos apenas 01 chave por apartamento. Ao sair, deixe na recepção, pois saberemos que o apartamento está disponível para arrumação colocando também a placa de porta.<br /><br />2 - COMO CHEGAR:<br />- Link do maps: https://maps.app.goo.gl/KoWFL46qtycNoC158<br />- Existem dois caminhos para chegar na pousada, recomendamos por favor que sigam as indicações:<br />- Assim que entrar no portal da cidade, siga na Rua Alameda Francisco da Cunha Menezes, primeira rotatória entra na terceira saída a direita na rua Dhaer Pedro. Siga em frente e vire à esquerda  na Av Augusto Galvão de França, seguindo em frente passa um posto de combustível (BR), continua subindo sempre reto e sairá da cidade, encontrando nossas placas indicativas. No km 4 quando avistar várias placas vire à esquerda e siga 1km de terra.Temos um muro branco com acabamento amarelo e uma placa da pousada à esquerda.<br />- Recomendamos a vir pelo google maps que é mais confiável.<br /><br />4 - ALIMENTAÇÃO:<br />O café da manhã é servido no restaurante das 08:00hrs ás 10:30hrs<br />O restaurante funciona de quinta a domingo, das 13:30hrs às 22:00 hrs. Pedidos são aceitos até as 21:00hrs<br />Delivery: consultar disponibilidade<br /><br />5 - PETS:<br />Seu pet é bem vindo em nossa pousada, entretanto há regras necessárias para um bom convívio junto aos demais hóspedes, quais sejam, mantê-lo em guia, não deixá-lo sozinho na acomodação quando se ausentar da pousada e não adentrar a área do restaurante.<br />Cobramos uma taxa extra de R$90,00 por diária e por pet<br /><br />6 - REGRAS E INFORMAÇÕES GERAIS:<br />As tomadas nos apartamentos são 110V e 220V.<br />Senha do Wi-fi: bocaina1<br />Os frigobares ficam desligados, entretanto, caso seja de seu interesse, bebidas poderão ser solicitadas junto à recepção no momento do Check In ou em outro momento oportuno e serão levadas até sua acomodação.<br />Caso em sua acomodação não haja secador de cabelo, este poderá ser solicitado junto à recepção, bem como travesseiro ou manta extra.<br />As toalhas da acomodação não poderão ser retiradas para uso na piscina, entretanto dispomos de toalhas para esse uso, as quais poderão ser solicitadas na recepção e deverão ser utilizadas apenas nas dependências da pousada e devolvidas.<br />Quando se ausentar da acomodação favor desligarem as luzes, ventiladores, ar condicionado e banheira, sendo que o não cumprimento poderá implicar em taxa extra.<br />Não é permitido o consumo de bebidas e comidas trazidas para a pousada, fora da acomodação, ou seja, nas áreas comuns, inclusive na piscina.<br />Conforme Lei Estadual número 13.541 de 07/05/2009 é expressamente proibido fumar dentro das acomodações.<br /><br /><br />7 - CONTRATO DE HOSPEDAGEM:<br />- Você receberá o nosso contrato anexado ao seu voucher, via e-mail e anexado a essa mensagem<br />- Caso não consiga visualizar o arquivo basta acessar através deste link:<br /><br />Qualquer dúvida é só entrar em contato. 😉<br /><br />Obrigado por escolher o Roteiros Adorai. 😊";

                break;

            default: // CAMPO

                $msgEnvio = "*Parabéns $infoReserva[nome]*, sua reserva no Roteiros Adorai está confirmada!\r\n\r\nChalé: $infoReserva[chale] \r\nPeríodo: $infoReserva[dat_ini] - $infoReserva[dat_fim] \r\nCidade: Piedade/SP\r\n\r\nNome da propriedade: Adorai Chalés\r\n\r\nPara que você possa aproveitar sua experiência com maior conforto e tranquilidade, separamos abaixo as dicas e orientações.\r\n\r\nRecomendamos que leia atentamente.\r\n\r\n\r\n1- COMO CHEGAR NO ADORAI CHALÉS:\r\n- Basta consultar no Google Maps ou Waze o destino: Adorai Chalés.\r\n- Endereço: R. Dimas Silva, s/n - Caetezal, Piedade - SP, 18170-000\r\n- Link: https://maps.app.goo.gl/mic8zfNG5T2uc63T7\r\n\r\n\r\n2- DATA DA ESTADIA:\r\n-Confirme no voucher que lhe enviamos por e-mail a data e o chalé que você reservou...\r\n\r\n\r\n3- DIAS E HORÁRIOS DE ENTRADA:\r\n-Check-in - início da estadia: A partir das 16hs.\r\n-Check-out - fim da estadia: Até 12h (meio dia)\r\n\r\n\r\n4- SUPORTE E AJUDA DURANTE A ESTADIA:\r\n- A nossa equipe da recepção entrará em contato 72hrs antes do seu check-in para enviar as informações a respeito de como chegar, as instruções do self-check-in, senhas e o funcionamento do seu chalé.\r\n- Para agilizar ainda mais o seu atendimento, pedimos que durante a sua estadia entre em contato somente através do número pelo qual você fechou a reserva\r\n- Nossa equipe estará disponível das 9 às 21hrs de segunda a quinta, das 9 às 17:00hrs na sexta e das 13 às 21:00hrs aos domingos e feriados. Não temos expediente do restante da equipe aos sábados, mas a recepção terá alguém de plantão para te atender em caso de emergências. 😉\r\n\r\n\r\n5- INTERNET E TV:\r\n- Cada chalé é equipado com um roteador independente.\r\n- A velocidade é suficiente para fazer videoconferência e assistir Youtube e Netflix.\r\n- A conexão pode oscilar e falhar como em qualquer lugar, entretanto nosso contrato com o provedor garante que exceto em caso de fortes ventos e vendavais, caso haja interrupção eles restabelecem a conexão em até 4 horas.\r\n- A senha da internet estará disponível dentro do chalé.\r\n\r\n\r\n6- REFEIÇÕES:\r\nAs hospedagens funcionam como auto serviço, onde você viverá a experiência como se estivesse em sua casa de campo, veja as opções:\r\nA - Compras no mercadinho no local que funciona 24 horas no sistema de auto-atendimento, você poderá comprar suas refeições, alimentos, carnes, bebidas e sobremesas;\r\nB - Poderá pedir Delivery, pizzas, lanches, comida japonesa, etc…\r\nC - Montar sua cesta café da manhã com os itens da sua preferência;\r\nD - Comer em deliciosos e pitorescos restaurantes da cidade;\r\nE - Fazer um churrasco ou cozinhar no chalé.\r\n\r\nCaso você tenha pago antecipadamente as refeições ou kit foundue, o valor será inserido como crédito em um cartão pré-pago que estará a sua disposição no chalé, com ele você poderá comprar no mercadinho refeições prontas, alimentos, sobremesas e bebidas ou pedir delivery, com isto terá a liberdade de fazer as refeições da forma que preferir!\r\n\r\n\r\n7- PETS:\r\nSeu pet é super bem-vindo, veja algumas regras e orientações:\r\n- Eles não podem subir na cama.\r\n- Recolher e limpar a sujeira é de responsabilidade do hóspede\r\n- Fora do chalé não existem cercas, se precisar, o cão deverá ser preso na guia. Mas a maioria dos cães ficam soltos próximo do chalé.\r\n- Quando temos a visita de pets retiramos os tapetes do chalé.\r\n- A diária de cães acompanhantes de hóspedes é de R$90,00 por noite.\r\n\r\n\r\n8- ÁGUA:\r\n- A água do chalé é de poço, nós e os funcionários bebem, mas recomendamos que você traga a sua água mineral.\r\n\r\n\r\n9- MANUTENÇÃO DURANTE A ESTADIA:\r\n-Em caso de pane de equipamentos ou qualquer outro problema durante a hospedagem, nossa equipe fará o máximo possível para que seja solucionado em até 2h.\r\n\r\n\r\n10- LIMPEZA DOS CHALÉS DURANTE A ESTADIA:\r\n- O Chalé será entregue limpo e higienizado, a limpeza e arrumação durante o período de locação será de responsabilidade do hóspede.\r\n\r\n\r\n11- PRIVACIDADE DOS CHALÉS:\r\n- Cada chalé tem entrada independente e não há necessidade de contato entre hóspedes.\r\n- Caso queiram usar a banheira ou piscinas dos chalés com janelas abertas é permitido desde que estejam de sungas ou biquínis como numa praia ou clube.\r\n- É proibido intimidades com janelas ou portas abertas.\r\n\r\n\r\n12- SILÊNCIO:\r\n- Adorai Chalés é um espaço de relaxamento e paz, portanto, casais, famílias e grupos, antes de alugar precisam concordar com Silêncio. Os sons do seu chalé, música e vozes, não podem ser ouvidos pelos seus vizinhos. Essa regra é muito importante para nossos hóspedes, pois é um espaço para descansar e curtir a natureza e os confortos que oferece.\r\n\r\n\r\n13- USO DAS PISCINAS AQUECIDAS/BANHEIRA E PISCINA COLETIVA:\r\n- Não é permitido o uso de shampoos e sabonetes para fazer espuma nas piscinas aquecidas, seu uso deve ser feito apenas nos chalés que possuem banheira.\r\n- Caso no momento do Check-out seja detectado uso de espumas, sais de banho ou produtos químicos nas piscinas será cobrado no momento do check-out uma taxa de R$500,00.\r\n- A piscina coletiva é de uso comum.\r\n\r\n\r\n14- UTENSÍLIOS DOS CHALÉS:\r\n- Utensílios básicos como: Panelas, pratos, copos, taças de vinho, talheres, abridor de vinho, pano de prato, aparelho de fondue, faca e tábua de churrasco, cafeteira e jogo americano.\r\n- Material de consumo: Sabonete líquido, detergente, bucha, saco de lixo, papel higiênico, rodo, vassoura, pano de chão.\r\n- Toda roupa de cama, banho e cozinha.\r\n- Os hóspedes devem trazer apenas coisas pessoais.\r\n\r\n\r\n15- O QUE NÃO É PERMITIDO:\r\n- Proibido narguilé e cigarro de qualquer tipo dentro dos chalés, fumar somente é permitido ao ar livre, e as bitucas devem ser apagadas na água e jogadas no lixo.\r\n- Caso seja detectado uso será cobrado taxa de R$ 500,00 no momento do check-out.\r\n- A voltagem é 127v no chalé, com tomadas de 3 pinos.\r\n\r\n\r\n16- POLÍTICA DE CANCELAMENTO E REAGENDAMENTO:\r\n- Todas as informações a respeito de cancelamento, reembolso e reagendamento estão descritas no voucher e em nosso contrato de hospedagem que está anexo logo abaixo.\r\n- Caso não consiga abrir o arquivo, basta acessar esse link: https://docs.google.com/document/d/1SbAQmNyIeE3pjQFzhCHK_G7Hj91CR2lJXlUgLM0FJHY/edit?usp=sharing\r\n\r\n\r\nQualquer dúvida, estamos à disposição. 😉\r\n\r\nObrigado por escolher o Roteiros Adorai. 😊";

                $texto = "<b>Parabéns $infoReserva[nome]</b>, sua reserva no Roteiros Adorai está confirmada!<br /><br />Chalé: $infoReserva[chale]<br />Período: $infoReserva[dat_ini] - $infoReserva[dat_fim]<br />Cidade: Piedade/SP<br /><br />Nome da propriedade: Adorai Chalés<br /><br />Para que você possa aproveitar sua experiência com maior conforto e tranquilidade, separamos abaixo as dicas e orientações.<br /><br />Recomendamos que leia atentamente.<br /><br /><br />1- COMO CHEGAR NO ADORAI CHALÉS:<br />- Basta consultar no Google Maps ou Waze o destino: Adorai Chalés.<br />- Endereço: R. Dimas Silva, s/n - Caetezal, Piedade - SP, 18170-000<br />- Link: https://goo.gl/maps/fEUmFs8iy2SCydBN8<br /><br /><br />2- DATA DA ESTADIA:<br />-Confirme no voucher que lhe enviamos por e-mail a data e o chalé que você reservou...<br /><br /><br />3- DIAS E HORÁRIOS DE ENTRADA:<br />-Check-in - início da estadia: A partir das 16hs.<br />-Check-out - fim da estadia: Até 12h (meio dia)<br /><br /><br />4- SUPORTE E AJUDA DURANTE A ESTADIA:<br />- A nossa equipe da recepção entrará em contato 72hrs antes do seu check-in para enviar as informações a respeito de como chegar, as instruções do self-check-in, senhas e o funcionamento do seu chalé.<br />- Para agilizar ainda mais o seu atendimento, pedimos que durante a sua estadia entre em contato somente através do número pelo qual você fechou a reserva<br />- Nossa equipe estará disponível das 9 às 21hrs de segunda a quinta, das 9 às 17:00hrs na sexta e das 13 às 21:00hrs aos domingos e feriados. Não temos expediente do restante da equipe aos sábados, mas a recepção terá alguém de plantão para te atender em caso de emergências. 😉<br /><br /><br />5- INTERNET E TV:<br />- Cada chalé é equipado com um roteador independente.<br />- A velocidade é suficiente para fazer videoconferência e assistir Youtube e Netflix.<br />- A conexão pode oscilar e falhar como em qualquer lugar, entretanto nosso contrato com o provedor garante que exceto em caso de fortes ventos e vendavais, caso haja interrupção eles restabelecem a conexão em até 4 horas.<br />- A senha da internet estará disponível dentro do chalé.<br /><br /><br />6- REFEIÇÕES:<br />As hospedagens funcionam como auto serviço, onde você viverá a experiência como se estivesse em sua casa de campo, veja as opções:<br />A - Compras no mercadinho no local que funciona 24 horas no sistema de auto-atendimento, você poderá comprar suas refeições, alimentos, carnes, bebidas e sobremesas;<br />B - Poderá pedir Delivery, pizzas, lanches, comida japonesa, etc…<br />C - Montar sua cesta café da manhã com os itens da sua preferência;<br />D - Comer em deliciosos e pitorescos restaurantes da cidade;<br />E - Fazer um churrasco ou cozinhar no chalé.<br /><br />Caso você tenha pago antecipadamente as refeições ou kit foundue, o valor será inserido como crédito em um cartão pré-pago que estará a sua disposição no chalé, com ele você poderá comprar no mercadinho refeições prontas, alimentos, sobremesas e bebidas ou pedir delivery, com isto terá a liberdade de fazer as refeições da forma que preferir!<br /><br /><br />7- PETS:<br />Seu pet é super bem-vindo, veja algumas regras e orientações:<br />- Eles não podem subir na cama.<br />- Recolher e limpar a sujeira é de responsabilidade do hóspede<br />- Fora do chalé não existem cercas, se precisar, o cão deverá ser preso na guia. Mas a maioria dos cães ficam soltos próximo do chalé.<br />- Quando temos a visita de pets retiramos os tapetes do chalé.<br />- A diária de cães acompanhantes de hóspedes é de R$90,00 por noite.<br /><br /><br />8- ÁGUA:<br />- A água do chalé é de poço, nós e os funcionários bebem, mas recomendamos que você traga a sua água mineral.<br /><br /><br />9- MANUTENÇÃO DURANTE A ESTADIA:<br />-Em caso de pane de equipamentos ou qualquer outro problema durante a hospedagem, nossa equipe fará o máximo possível para que seja solucionado em até 2h.<br /><br /><br />10- LIMPEZA DOS CHALÉS DURANTE A ESTADIA:<br />- O Chalé será entregue limpo e higienizado, a limpeza e arrumação durante o período de locação será de responsabilidade do hóspede.<br /><br /><br />11- PRIVACIDADE DOS CHALÉS:<br />- Cada chalé tem entrada independente e não há necessidade de contato entre hóspedes.<br />- Caso queiram usar a banheira ou piscinas dos chalés com janelas abertas é permitido desde que estejam de sungas ou biquínis como numa praia ou clube.<br />- É proibido intimidades com janelas ou portas abertas.<br /><br /><br />12- SILÊNCIO:<br />- Adorai Chalés é um espaço de relaxamento e paz, portanto, casais, famílias e grupos, antes de alugar precisam concordar com Silêncio. Os sons do seu chalé, música e vozes, não podem ser ouvidos pelos seus vizinhos. Essa regra é muito importante para nossos hóspedes, pois é um espaço para descansar e curtir a natureza e os confortos que oferece.<br /><br /><br />13- USO DAS PISCINAS AQUECIDAS/BANHEIRA E PISCINA COLETIVA:<br />- Não é permitido o uso de shampoos e sabonetes para fazer espuma nas piscinas aquecidas, seu uso deve ser feito apenas nos chalés que possuem banheira.<br />- Caso no momento do Check-out seja detectado uso de espumas, sais de banho ou produtos químicos nas piscinas será cobrado no momento do check-out uma taxa de R$500,00.<br />- A piscina coletiva é de uso comum.<br /><br /><br />14- UTENSÍLIOS DOS CHALÉS:<br />- Utensílios básicos como: Panelas, pratos, copos, taças de vinho, talheres, abridor de vinho, pano de prato, aparelho de fondue, faca e tábua de churrasco, cafeteira e jogo americano.<br />- Material de consumo: Sabonete líquido, detergente, bucha, saco de lixo, papel higiênico, rodo, vassoura, pano de chão.<br />- Toda roupa de cama, banho e cozinha.<br />- Os hóspedes devem trazer apenas coisas pessoais.<br /><br /><br />15- O QUE NÃO É PERMITIDO:<br />- Proibido narguilé e cigarro de qualquer tipo dentro dos chalés, fumar somente é permitido ao ar livre, e as bitucas devem ser apagadas na água e jogadas no lixo.<br />- Caso seja detectado uso será cobrado taxa de R$ 500,00 no momento do check-out.<br />- A voltagem é 127v no chalé, com tomadas de 3 pinos.<br /><br /><br />16- POLÍTICA DE CANCELAMENTO E REAGENDAMENTO:<br />- Todas as informações a respeito de cancelamento, reembolso e reagendamento estão descritas no voucher e em nosso contrato de hospedagem que está anexo logo abaixo.<br />- Caso não consiga abrir o arquivo, basta acessar esse link: https://docs.google.com/document/d/1SbAQmNyIeE3pjQFzhCHK_G7Hj91CR2lJXlUgLM0FJHY/edit?usp=sharing<br /><br /><br />Qualquer dúvida, estamos à disposição. 😉<br /><br />Obrigado por escolher o Roteiros Adorai. 😊";

                break;
        }



        $msgsbtr =  str_replace(["\r\n", "\r", "\n"], '\n', $msgEnvio);

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

        $num_celular = $infoReserva[num_celular];

        $codPais = substr($num_celular, 0, 2);

        if ($codPais != "55") {
            $num_celular = "55" . $infoReserva[num_celular];
        }

        $resultcreate = FnsendText($qrBuscaModulos['NOM_SESSAO'], $qrBuscaModulos['DES_AUTHKEY'], $num_celular, $msgsbtr, 3, $port);

        header('Content-Type: text/html; charset=utf-8');

        $sql = "SELECT DES_EMAIL FROM SITE_EXTRATO WHERE COD_EMPRESA = $cod_empresa";
        $qrEmail = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa, ''), $sql));

        $sqlEmp = "SELECT NOM_FANTASI FROM EMPRESAS WHERE COD_EMPRESA = $cod_empresa";
        $qrEmp = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $sqlEmp));

        include "../_system/EMAIL/PHPMailer/PHPMailerAutoload.php";
        include '../externo/email/envio_sac.php';


        $email['email1'] = $infoReserva[email];

        $retorno = fnsacmail(
            $email,
            'Roteiros Adorai',
            "<html>" . $texto . "</html>",
            "RESERVA CONFIRMADA",
            "roteirosadorai.com.br",
            $connAdm->connAdm(),
            connTemp($cod_empresa, ""),
            $cod_empresa
        );

        $sqlConfirma = "UPDATE ADORAI_PEDIDO SET
                        LOG_CONFIRMA = 'S'
                        WHERE UUID = '" . $infoReserva['uuid'] . "'";
        mysqli_query(connTemp(274, ''), $sqlConfirma);

        // echo json_encode($resultcreate);

        break;

    case '33': // envio formulário de hóspedes - pix

        $canalWhats = fnLimpaCampoZero($_GET["cal"]);
        $metodo = fnLimpaCampoZero($_GET["metodo"]);
        $infoReserva = json_decode(base64_decode($_GET["info"]), true);

        if ($canalWhats == 0 || $canalWhats == "") {
            $canalWhats = 1;
        }

        $linkEnvio = "https://roteirosadorai.com.br/hospedes.php?id=" . fnEncode($infoReserva['uuid']);

        $linkEnvio = file_get_contents("http://tinyurl.com/api-create.php?url=" . $linkEnvio);

        $msgEnvio = "Estamos quase lá!\r\nPara confirmar a sua estadia e validar as informações, por favor, clique no link abaixo e preencha os dados solicitados.\r\n$linkEnvio\r\n\r\n*Isso não é uma confirmação de reserva. Caso o pagamento tenha sido feito via PIX, não se esqueça de nos enviar o comprovante.*";

        $msgsbtr =  str_replace(["\r\n", "\r", "\n"], '\n', $msgEnvio);

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

        $num_celular = $infoReserva[num_celular];

        $codPais = substr($num_celular, 0, 2);

        if ($codPais != "55") {
            $num_celular = "55" . $infoReserva[num_celular];
        }

        $resultcreate = FnsendText($qrBuscaModulos['NOM_SESSAO'], $qrBuscaModulos['DES_AUTHKEY'], $num_celular, $msgsbtr, 3, $port);

        header('Content-Type: text/html; charset=utf-8');

        $sql = "SELECT DES_EMAIL FROM SITE_EXTRATO WHERE COD_EMPRESA = $cod_empresa";
        $qrEmail = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa, ''), $sql));

        $sqlEmp = "SELECT NOM_FANTASI FROM EMPRESAS WHERE COD_EMPRESA = $cod_empresa";
        $qrEmp = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $sqlEmp));

        include "../_system/EMAIL/PHPMailer/PHPMailerAutoload.php";
        include '../externo/email/envio_sac.php';

        $texto = "Estamos quase lá!<br />Para confirmar a sua estadia e validar as informações, por favor, clique no link abaixo e preencha os dados solicitados.<br /><br />$linkEnvio<br /><br /><b>Isso não é uma confirmação de reserva. Caso o pagamento tenha sido feito via PIX, não se esqueça de nos enviar o comprovante.</b>";


        $email['email1'] = $infoReserva[email];

        $retorno = fnsacmail(
            $email,
            'Roteiros Adorai',
            "<html>" . $texto . "</html>",
            "INFORMAÇÕES NECESSÁRIAS",
            "roteirosadorai.com.br",
            $connAdm->connAdm(),
            connTemp($cod_empresa, ""),
            $cod_empresa
        );

        // echo json_encode($resultcreate);
        // echo print_r($resultcreate);

        break;

    case '34': // envio DE VOUCHER

        $canalWhats = fnLimpaCampoZero($_GET["cal"]);
        $metodo = fnLimpaCampoZero($_GET["metodo"]);
        $infoReserva = json_decode(base64_decode($_GET["info"]), true);

        if ($canalWhats == 0 || $canalWhats == "") {
            $canalWhats = 1;
        }

        $linkEnvio = "https://roteirosadorai.com.br/voucher.php?id=" . fnEncode($infoReserva['uuid']);

        $linkEnvio = file_get_contents("http://tinyurl.com/api-create.php?url=" . $linkEnvio);

        $msgEnvio = "Ótimo! Agora temos tudo o que precisamos para tornar a sua estadia conosco, inesquecível! 🥰\r\nPara visualizar as informações do seu pedido, é só clicar no link 😊👇🏼\r\n$linkEnvio";

        $msgsbtr =  str_replace(["\r\n", "\r", "\n"], '\n', $msgEnvio);

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

        $num_celular = $infoReserva[num_celular];

        $codPais = substr($num_celular, 0, 2);

        if ($codPais != "55") {
            $num_celular = "55" . $infoReserva[num_celular];
        }

        $resultcreate = FnsendText($qrBuscaModulos['NOM_SESSAO'], $qrBuscaModulos['DES_AUTHKEY'], $num_celular, $msgsbtr, 3, $port);

        header('Content-Type: text/html; charset=utf-8');

        $sql = "SELECT DES_EMAIL FROM SITE_EXTRATO WHERE COD_EMPRESA = $cod_empresa";
        $qrEmail = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa, ''), $sql));

        $sqlEmp = "SELECT NOM_FANTASI FROM EMPRESAS WHERE COD_EMPRESA = $cod_empresa";
        $qrEmp = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $sqlEmp));

        include "../_system/EMAIL/PHPMailer/PHPMailerAutoload.php";
        include '../externo/email/envio_sac.php';

        $texto = "Ótimo! Agora temos tudo o que precisamos para tornar a sua estadia conosco, inesquecível! 🥰<br />Para visualizar as informações do seu pedido, é só clicar no link 😊👇🏼<br />$linkEnvio";


        $email['email1'] = $infoReserva[email];

        $retorno = fnsacmail(
            $email,
            'Roteiros Adorai',
            "<html>" . $texto . "</html>",
            "RESUMO DO PEDIDO",
            "roteirosadorai.com.br",
            $connAdm->connAdm(),
            connTemp($cod_empresa, ""),
            $cod_empresa
        );

        // echo json_encode($resultcreate);
        // echo print_r($resultcreate);

        $sqlVoucher = "UPDATE ADORAI_PEDIDO SET
                        LOG_VOUCHER = 'S'
                        WHERE UUID = '" . $infoReserva['uuid'] . "'";
        mysqli_query(connTemp(274, ''), $sqlVoucher);

        break;

    case '35': // envio formulário de hóspedes e confirmação - cartão

        $uuid = fnLimpaCampo($_GET["uuid"]);
        $canalWhats = fnLimpaCampoZero($_GET["cal"]);
        // $metodo = fnLimpaCampoZero($_GET["metodo"]);
        // $infoReserva = json_decode(base64_decode($_GET["info"]),true);

        if ($canalWhats == 0 || $canalWhats == "") {
            $canalWhats = 1;
        }

        $sqlInfo = "SELECT API.COD_PROPRIEDADE AS 'cod_hotel', 
                             API.COD_CHALE AS 'cod_chale',
                             '' AS 'chale',
                             AP.NOME AS 'nome',
                             AP.SOBRENOME AS 'sobrenome',
                             API.DAT_INICIAL AS 'dat_ini',
                             API.DAT_FINAL AS 'dat_fim',
                             AP.TELEFONE AS 'celular',
                             AP.EMAIL AS 'email'
                    FROM adorai_pedido AP
                    INNER JOIN adorai_pedido_items API ON API.COD_PEDIDO = AP.COD_PEDIDO
                    WHERE UUID = '$uuid'";

        $infoReserva = mysqli_fetch_assoc(mysqli_query(connTemp(274, ''), $sqlInfo));

        $infoReserva[dat_ini] = fnDataShort($infoReserva[dat_ini]);
        $infoReserva[dat_fim] = fnDataShort($infoReserva[dat_fim]);
        $infoReserva[nome] = ucfirst(strtolower($infoReserva[nome])) . " " . ucfirst(strtolower($infoReserva[sobrenome]));

        $sqlChale = "SELECT NOM_QUARTO FROM ADORAI_CHALES 
                    WHERE COD_EXTERNO = $infoReserva[cod_chale]
                    AND COD_EXCLUSA = 0";
        $qrChale = mysqli_fetch_assoc(mysqli_query(connTemp(274, ''), $sqlChale));

        $infoReserva['chale'] = $qrChale['NOM_QUARTO'];

        // echo "<pre>";
        // print_r($infoReserva);
        // echo "</pre>";

        // exit();

        switch ($infoReserva['cod_hotel']) {

            case '3010': // MONTANHA

                $msgEnvio = "*Parabéns $infoReserva[nome]*, sua reserva no Roteiros Adorai está confirmada!\r\n\r\nChalé: $infoReserva[chale]\r\nPeríodo: $infoReserva[dat_ini] - $infoReserva[dat_fim]\r\nCidade: Piedade/SP\r\nNome da propriedade: Sítio da Montanha\r\n\r\nO Anfitrião Márcio e sua equipe entrarão em contato com você para te dar as boas vindas, passar as regras e orientações sobre como chegar, etc…\r\nCaso prefira, pode chamá-los no whats (11) 94224-8008.\r\nCaso precise, o Willian cuidará das suas necessidades durante sua hospedagem, (15) 99699-4228.😃\r\n\r\nBem vindos ao Sítio da Montanha\r\n\r\nPara que você possa aproveitar sua experiência com maior conforto e tranquilidade, separamos abaixo as dicas, orientações e politicas da propriedade:\r\n\r\nO Sítio da Montanha é um condomínio de chalés de auto-serviço. Você aluga e usa como sua própria casa de campo.\r\n\r\nPor favor, pedimos sua atenção especial para os horários, as instruções do caminho para o sítio e a regra de silêncio.\r\n\r\n1 - CHECK-IN/CHECK-OUT:\r\n- Check in a partir das 16hrs e até às 22hrs.\r\n- Check out até às 12hrs (meio dia).\r\n- Por favor, quando estiver saindo de casa avise sua previsão de chegada ao Willian.\r\n\r\n2 - COMO CHEGAR:\r\n- Atenção: Os endereços das plataformas não funcionam nas áreas rurais. Usem as instruções abaixo.\r\n- Endereço: Rodovia SP-79 (= BR-478), km 129,5, + 4km de terra sinalizados. Bairro Sarapuí Dos Soares. Piedade SP, 18170-000, Brasil.\r\n- Localização Google: https://goo.gl/maps/U6NJYoeHuJk3n1XF6\r\n- Google Maps ou Waze: utilize as seguintes coordenadas e siga as instruções:\r\n-23.796220, -47.480644\r\n\r\n3 - SILÊNCIO:\r\n- No chalé não permitimos sons altos ou sons que causem desconforto ao hóspede do chalé ao lado. Não é permitido festas a partir das 22hrs. Nossos chalés são um local de repouso e descanso.\r\n\r\n4 - WI-FI:\r\n- A senha será informada no check-in. A velocidade é suficiente para fazer videoconferência e assistir Youtube e Netflix, mas pode variar e falhar.\r\n\r\n5 - EMPÓRIO:\r\n- O nosso empório é aberto de segunda a sexta, das 8h às 12h, das 13h às 17h, e das 18 às 22h. Sábados e domingos das 8h às 12h.\r\n\r\n6 - ÁGUA:\r\n- A ÁGUA DO CHALÉ é de poço artesiano e é muito boa.\r\n\r\n7 - UTENSÍLIOS DOS CHALÉS:\r\n- Panelas, frigideira, leiteira, pratos, copos de água, vinho, cerveja, canecas de café, talheres de mesa, facas de cozinha, colheres, abridor de garrafa, saca rolha, ralador, pano de prato, aparelho de fondue, faca e tábua de churrasco, tábua de queijos, liquidificador, garrafa térmica, filtro de café com suporte, jogo americano..\r\n- Material de consumo: sabonete líquido, detergente, bucha, saco de lixo, e papel higiênico.\r\n- Toda roupa de cama, banho e cozinha.\r\n- Os hóspedes devem trazer apenas coisas pessoais.\r\n- TV Sky via satélite.\r\n\r\n8 - ITENS ADICIONAIS:\r\n- Colocamos no chalé lenha de lareira, carvão, álcool gel, e lenha de fogueira para facilitar. O consumo desses materiais será cobrado no check-out. Portanto, não estão incluídos no preço do chalé.\r\n- BANHO DE SAIS (consulte o preço)\r\n- Sais e espuma para um banho quente e relaxante na sua jacuzzi, e depois limpeza e troca da água. Peça pelo Whatsapp 11 94224-8008. Vocês vão adorar!\r\n\r\n9 - ATENÇÃO!:\r\n- O hóspede concorda em pagar no check-out os danos que causar ao chalé e seus equipamentos e acessórios.\r\n- A voltagem é 127v e as tomadas são de 3 pinos.\r\n- Pais devem trazer roupa de cama de berço para seus bebês. Cuidado: crianças sempre devem estar acompanhadas dos responsáveis, pois várias instalações apresentam algum risco, tais como piscina, jacuzzi, barrancos, lago, brinquedos, etc…\r\n\r\n10 - CANCELAMENTO E REMARCAÇÃO\r\n- Todas as informações sobre cancelametos e adiamentos da estadia estão descritas no nosso contrato de hospedagem que segue anexado logo abaixo. Caso não consiga vizualizar o arquivo, basta acessar esse link: https://docs.google.com/document/d/1Vdr0oG9WV16cBqDDMr6jy9wIx3hn0NWpIrZUU8BFHR4/edit?usp=sharing\r\n\r\nQualquer dúvida é só entrar em contato. 😉\r\n\r\n\r\nObrigado por escolher o Roteiros Adorai. 😊";

                $texto = "<b>Parabéns $infoReserva[nome]</b>, sua reserva no Roteiros Adorai está confirmada!<br /><br />Chalé: $infoReserva[chale]<br />Período: $infoReserva[dat_ini] - $infoReserva[dat_fim]<br />Cidade: Piedade/SP<br />Nome da propriedade: Sítio da Montanha<br /><br />O Anfitrião Márcio e sua equipe entrarão em contato com você para te dar as boas vindas, passar as regras e orientações sobre como chegar, etc…<br />Caso prefira, pode chamá-los no whats (11) 94224-8008.<br />Caso precise, o Willian cuidará das suas necessidades durante sua hospedagem, (15) 99699-4228.😃<br /><br />Bem vindos ao Sítio da Montanha<br /><br />Para que você possa aproveitar sua experiência com maior conforto e tranquilidade, separamos abaixo as dicas, orientações e politicas da propriedade:<br /><br />O Sítio da Montanha é um condomínio de chalés de auto-serviço. Você aluga e usa como sua própria casa de campo.<br /><br />Por favor, pedimos sua atenção especial para os horários, as instruções do caminho para o sítio e a regra de silêncio.<br /><br />1 - CHECK-IN/CHECK-OUT:<br />- Check in a partir das 16hrs e até às 22hrs.<br />- Check out até às 12hrs (meio dia).<br />- Por favor, quando estiver saindo de casa avise sua previsão de chegada ao Willian.<br /><br />2 - COMO CHEGAR:<br />- Atenção: Os endereços das plataformas não funcionam nas áreas rurais. Usem as instruções abaixo.<br />- Endereço: Rodovia SP-79 (= BR-478), km 129,5, + 4km de terra sinalizados. Bairro Sarapuí Dos Soares. Piedade SP, 18170-000, Brasil.<br />- Localização Google: https://goo.gl/maps/U6NJYoeHuJk3n1XF6<br />- Google Maps ou Waze: utilize as seguintes coordenadas e siga as instruções:<br />-23.796220, -47.480644<br /><br />3 - SILÊNCIO:<br />- No chalé não permitimos sons altos ou sons que causem desconforto ao hóspede do chalé ao lado. Não é permitido festas a partir das 22hrs. Nossos chalés são um local de repouso e descanso.<br /><br />4 - WI-FI:<br />- A senha será informada no check-in. A velocidade é suficiente para fazer videoconferência e assistir Youtube e Netflix, mas pode variar e falhar.<br /><br />5 - EMPÓRIO:<br />- O nosso empório é aberto de segunda a sexta, das 8h às 12h, das 13h às 17h, e das 18 às 22h. Sábados e domingos das 8h às 12h.<br /><br />6 - ÁGUA:<br />- A ÁGUA DO CHALÉ é de poço artesiano e é muito boa.<br /><br />7 - UTENSÍLIOS DOS CHALÉS:<br />- Panelas, frigideira, leiteira, pratos, copos de água, vinho, cerveja, canecas de café, talheres de mesa, facas de cozinha, colheres, abridor de garrafa, saca rolha, ralador, pano de prato, aparelho de fondue, faca e tábua de churrasco, tábua de queijos, liquidificador, garrafa térmica, filtro de café com suporte, jogo americano..<br />- Material de consumo: sabonete líquido, detergente, bucha, saco de lixo, e papel higiênico.<br />- Toda roupa de cama, banho e cozinha.<br />- Os hóspedes devem trazer apenas coisas pessoais.<br />- TV Sky via satélite.<br /><br />8 - ITENS ADICIONAIS:<br />- Colocamos no chalé lenha de lareira, carvão, álcool gel, e lenha de fogueira para facilitar. O consumo desses materiais será cobrado no check-out. Portanto, não estão incluídos no preço do chalé.<br />- BANHO DE SAIS (consulte o preço)<br />- Sais e espuma para um banho quente e relaxante na sua jacuzzi, e depois limpeza e troca da água. Peça pelo Whatsapp 11 94224-8008. Vocês vão adorar!<br /><br />9 - ATENÇÃO!:<br />- O hóspede concorda em pagar no check-out os danos que causar ao chalé e seus equipamentos e acessórios.<br />- A voltagem é 127v e as tomadas são de 3 pinos.<br />- Pais devem trazer roupa de cama de berço para seus bebês. Cuidado: crianças sempre devem estar acompanhadas dos responsáveis, pois várias instalações apresentam algum risco, tais como piscina, jacuzzi, barrancos, lago, brinquedos, etc…<br /><br />10 - CANCELAMENTO E REMARCAÇÃO<br />- Todas as informações sobre cancelametos e adiamentos da estadia estão descritas no nosso contrato de hospedagem que segue anexado logo abaixo. Caso não consiga vizualizar o arquivo, basta acessar esse link: https://docs.google.com/document/d/1Vdr0oG9WV16cBqDDMr6jy9wIx3hn0NWpIrZUU8BFHR4/edit?usp=sharing<br /><br />Qualquer dúvida é só entrar em contato. 😉<br /><br /><br />Obrigado por escolher o Roteiros Adorai. 😊";

                break;

            case '3008': // FALCÃO

                $msgEnvio = "*Parabéns, $infoReserva[nome]*! Sua reserva no Roteiros Adorai está confirmada!\r\n\r\nChalé: $infoReserva[chale]\r\nPeríodo: $infoReserva[dat_ini] - $infoReserva[dat_fim]\r\nCidade: Cunha/SP\r\nNome da propriedade: Monte do Falcão\r\n\r\nA Anfitriã, Isabella, entrará em contato com você para te passar algumas informações importantes sobre a sua estadia.\r\nCaso prefira, pode chamá-la pelo whatsapp no número +351 911 504 368.\r\n\r\nBem vindos ao sítio Monte do Falcão\r\n\r\nPara que você possa aproveitar sua experiência com maior conforto e tranquilidade, separamos abaixo as dicas e orientações:\r\n\r\n1 - CHECK-IN/CHECK-OUT:\r\n- Nosso check-in é a partir das 16hs.\r\n- Nosso check-out até 12hs (Meio dia)\r\n- Nossos colaboradores, Tânia e Cassiano estarão lá para recebê-los até às 17h.\r\n- Caso o check-in seja após as 17hs, terão que combinar com eles no número (12) 99795-5922, o horário de check-in para eles possam ir ao sítio receber vocês.\r\n- APÓS AS 21Hs somente self check-in com as informações enviadas pela Isabella.\r\n\r\n2 - COMO CHEGAR:\r\n- O trajeto do centro da cidade de Cunha até o sítio é de 4km (exatamente a 1° entrada a direita após a placa de KM 4).\r\n- O acesso é todo asfaltado, porém não pega muito bem celular no caminho, então a melhor forma de chegar é pelo Google Maps, pois a  Waze não funciona bem na região.\r\n- Endereço: estrada municipal KM 4, Roça Grande, Cunha - SP\r\n- Localização google maps:\r\nhttps://maps.app.goo.gl/fX2ZAHwNqCN7sUGU6\r\n- Em nossa propriedade há também mais oito casas. Ao entrar na propriedade é só manter sempre à esquerda até achar seu chalé/casa.\r\n\r\n3 - Politica de cancelamento e contrato de hospedagem:\r\n- Para conferir o nosso contrato de forma integral basta acessar o arquivo anexado.\r\n- Caso não consiga vizualizar o arquivo basta acessar através desse link: https://docs.google.com/document/d/1E1DtZzwuLPu9IsPf_SltvhDxqyqxJMI-QwrRL2o77zM/edit?usp=sharing\r\n\r\n\r\nQualquer dúvida é só entrar em contato. 😉\r\n\r\nObrigado por escolher o Roteiros Adorai. 😊";

                $texto = "<b>Parabéns, $infoReserva[nome]</b>! Sua reserva no Roteiros Adorai está confirmada!<br /><br />Chalé: $infoReserva[chale]<br />Período: $infoReserva[dat_ini] - $infoReserva[dat_fim]<br />Cidade: Cunha/SP<br />Nome da propriedade: Monte do Falcão<br /><br />A Anfitriã, Isabella, entrará em contato com você para te passar algumas informações importantes sobre a sua estadia.<br />Caso prefira, pode chamá-la pelo whatsapp no número +351 911 504 368.<br /><br />Bem vindos ao sítio Monte do Falcão<br /><br />Para que você possa aproveitar sua experiência com maior conforto e tranquilidade, separamos abaixo as dicas e orientações:<br /><br />1 - CHECK-IN/CHECK-OUT:<br />- Nosso check-in é a partir das 16hs.<br />- Nosso check-out até 12hs (Meio dia)<br />- Nossos colaboradores, Tânia e Cassiano estarão lá para recebê-los até às 17h.<br />- Caso o check-in seja após as 17hs, terão que combinar com eles no número (12) 99795-5922, o horário de check-in para eles possam ir ao sítio receber vocês.<br />- APÓS AS 21Hs somente self check-in com as informações enviadas pela Isabella.<br /><br />2 - COMO CHEGAR:<br />- O trajeto do centro da cidade de Cunha até o sítio é de 4km (exatamente a 1° entrada a direita após a placa de KM 4).<br />- O acesso é todo asfaltado, porém não pega muito bem celular no caminho, então a melhor forma de chegar é pelo Google Maps, pois a  Waze não funciona bem na região.<br />- Endereço: estrada municipal KM 4, Roça Grande, Cunha - SP<br />- Localização google maps:<br />https://maps.app.goo.gl/fX2ZAHwNqCN7sUGU6<br />- Em nossa propriedade há também mais oito casas. Ao entrar na propriedade é só manter sempre à esquerda até achar seu chalé/casa.<br /><br />3 - Politica de cancelamento e contrato de hospedagem:<br />- Para conferir o nosso contrato de forma integral basta acessar o arquivo anexado.<br />- Caso não consiga vizualizar o arquivo basta acessar através desse link: https://docs.google.com/document/d/1E1DtZzwuLPu9IsPf_SltvhDxqyqxJMI-QwrRL2o77zM/edit?usp=sharing<br /><br /><br />Qualquer dúvida é só entrar em contato. 😉<br /><br />Obrigado por escolher o Roteiros Adorai. 😊";

                break;

            case '956': //REAL

                $msgEnvio = "*Parabéns $infoReserva[nome]*, sua reserva no Roteiros Adorai está confirmada!\r\n\r\nChalé: $infoReserva[chale]\r\nPeríodo: $infoReserva[dat_ini] - $infoReserva[dat_fim]\r\nCidade: Paraty/RJ\r\nNome da propriedade: Adorai Real\r\n\r\nA Anfitriã, Isabella entrará em contato com você para te passar algumas informações\r\nimportantes sobre sua estadia.\r\nCaso prefira, pode chamá-la pelo whatsapp no número (24) 99251-8298.\r\n\r\nBem vindos ao chalés Paraty Real\r\n\r\n\r\nPara que você possa aproveitar sua experiência com maior conforto e tranquilidade, separamos abaixo as dicas e orientações.\r\n\r\nRecomendamos que leia atentamente.\r\n\r\n\r\n1- COMO CHEGAR NO ADORAI REAL:\r\n- Localização: https://goo.gl/maps/GVSZzTLmvGFqBJy2A\r\n- Estrada Paraty Cunha KM 9,5\r\n- Ponto de referência: Após percorrer 9,5 km a partir do trevo de Paraty, vire à esquerda quando avistar um bambuzal com uma placa redonda indicando \"Chalés Paraty Real\". Siga pela estrada que leva diretamente à garagem, onde haverá sinalização indicando o caminho para a recepção.\r\n\r\n2- DATA DA ESTADIA:\r\n-Confira no voucher que lhe enviamos por e-mail a data e o chalé que você reservou\r\n\r\n\r\n3- DIAS E HORÁRIOS DE ENTRADA:\r\n-Check-in (inicio da estadia): A partir das 14hrs e até as 22hrs (após esse horario, é possivel realizar o self check-in, com as informações enviadas pela anfitriã)\r\n-Check-out (fim da estadia): Até 11hrs (caso passe do horário do check-out, será cobrado valor adicional)\r\n\r\n4- SUPORTE E AJUDA DURANTE A ESTADIA:\r\n- Para qualquer necessidade durante a sua estadia, por gentileza entrar em contato com a anfitriã Isabela pelo número: (24) 99251-8298. Esse mesmo número irá te enviar as informações sobre o seu check-in 48hrs antes da data da chegada\r\n\r\n\r\n5- INTERNET:\r\n- Senha da Recepção: 12345678\r\n- Senha da acomodação: hospedagem\r\n\r\n6- REFEIÇÕES:\r\nCafé da manhã: O café da manhã será servido em cestas de piquenique, na porta do seu chalé, entre 08h e 08:30h.\r\nLojinha do chalé: Nela você encontra opções de bebidas, sais de banho, espuma e vários outros itens. Ela funciona das 08:00hrs até as 20:00hrs\r\nOferecemos algumas indicações de deliverys próximos ao chalé, quando o seu check-in estiver chegando você irá receber uma llista com algumas indicações\r\nO chalé possui uma mini cozinha\r\n\r\n\r\n7- PETS:\r\nSeu pet é super bem-vindo, veja algumas regras e orientações:\r\n- Eles não podem subir na cama.\r\n- Recolher e limpar a sujeira é de responsabilidade do hóspede\r\n- Fora do chalé não existem cercas, se precisar, o cão deverá ser preso na guia.\r\n- A diária de cães acompanhantes de hóspedes é de R$90,00 por noite.\r\n- Qualquer dano causado pelo PET é de responsabilidade do hóspede\r\n- O hóspede deverá levar os itens de higiene para o pet\r\n\r\n\r\n8- LIMPEZA DOS CHALÉS DURANTE A ESTADIA:\r\n- O Chalé será entregue limpo e higienizado, a limpeza e arrumação durante o período de locação será de responsabilidade do hóspede.\r\n\r\n\r\n9- PRIVACIDADE DOS CHALÉS:\r\n- Cada chalé tem entrada independente e não há necessidade de contato entre hóspedes.\r\n- É proibido intimidades com janelas ou portas abertas.\r\n\r\n\r\n10- SILÊNCIO:\r\n- Adorai Real é um espaço de relaxamento e paz, portanto, casais, famílias e grupos, antes de alugar precisam concordar com Silêncio. Os sons do seu chalé, música e vozes, não podem ser ouvidos pelos seus vizinhos. Essa regra é muito importante para nossos hóspedes, pois é um espaço para descansar e curtir a natureza e os confortos que oferece. A partir das 20h00min até as 08:00h, o SILÊNCIO é primordial para tranquilidade dos hóspedes, isso é muito importante para o convívio em grupo.\r\n\r\n\r\n11- UTENSÍLIOS DOS CHALÉS:\r\n- Os chalés são equipados com um cooktop, forninho, frigobar e utensílios para um casal, tendo a possibilidade de fazer comidas leves.\r\n\r\n12- O QUE NÃO É PERMITIDO:\r\n- Proibido narguilé e cigarro de qualquer tipo dentro dos chalés, LEI ESTADUAL No 13.541, DE 07 DE MAIO DE 2009.\r\n- Caso seja detectado uso será cobrado taxa de R$500,00 no momento do check-out.\r\n- Proibidos festas e som alto em qualquer horário\r\n\r\n\r\n13- POLÍTICA DE CANCELAMENTO E REAGENDAMENTO:\r\n- Todas as informações a respeito de cancelamento, reembolso e reagendamento estão descritas no voucher e em nosso contrato de hospedagem que está anexo logo abaixo.\r\n- Caso não consiga abrir o arquivo, basta acessar esse link:\r\n\r\nQualquer dúvida, estamos à disposição. 😉\r\n\r\nObrigado por escolher o Roteiros Adorai. 😊";

                $texto = "<b>Parabéns $infoReserva[nome]</b>, sua reserva no Roteiros Adorai está confirmada!<br /><br />Chalé: $infoReserva[chale]<br />Período: $infoReserva[dat_ini] - $infoReserva[dat_fim]<br />Cidade: Paraty/RJ<br />Nome da propriedade: Adorai Real<br /><br />A Anfitriã, Isabella entrará em contato com você para te passar algumas informações<br />importantes sobre sua estadia.<br />Caso prefira, pode chamá-la pelo whatsapp no número (24) 99251-8298.<br /><br />Bem vindos ao chalés Paraty Real<br /><br /><br />Para que você possa aproveitar sua experiência com maior conforto e tranquilidade, separamos abaixo as dicas e orientações.<br /><br />Recomendamos que leia atentamente.<br /><br /><br />1- COMO CHEGAR NO ADORAI REAL:<br />- Localização: https://goo.gl/maps/GVSZzTLmvGFqBJy2A<br />- Estrada Paraty Cunha KM 9,5<br />- Ponto de referência: Após percorrer 9,5 km a partir do trevo de Paraty, vire à esquerda quando avistar um bambuzal com uma placa redonda indicando \"Chalés Paraty Real\". Siga pela estrada que leva diretamente à garagem, onde haverá sinalização indicando o caminho para a recepção.<br /><br />2- DATA DA ESTADIA:<br />-Confira no voucher que lhe enviamos por e-mail a data e o chalé que você reservou<br /><br /><br />3- DIAS E HORÁRIOS DE ENTRADA:<br />-Check-in (inicio da estadia): A partir das 14hrs e até as 22hrs (após esse horario, é possivel realizar o self check-in, com as informações enviadas pela anfitriã)<br />-Check-out (fim da estadia): Até 11hrs (caso passe do horário do check-out, será cobrado valor adicional)<br /><br />4- SUPORTE E AJUDA DURANTE A ESTADIA:<br />- Para qualquer necessidade durante a sua estadia, por gentileza entrar em contato com a anfitriã Isabela pelo número: (24) 99251-8298. Esse mesmo número irá te enviar as informações sobre o seu check-in 48hrs antes da data da chegada<br /><br /><br />5- INTERNET:<br />- Senha da Recepção: 12345678<br />- Senha da acomodação: hospedagem<br /><br />6- REFEIÇÕES:<br />Café da manhã: O café da manhã será servido em cestas de piquenique, na porta do seu chalé, entre 08h e 08:30h.<br />Lojinha do chalé: Nela você encontra opções de bebidas, sais de banho, espuma e vários outros itens. Ela funciona das 08:00hrs até as 20:00hrs<br />Oferecemos algumas indicações de deliverys próximos ao chalé, quando o seu check-in estiver chegando você irá receber uma llista com algumas indicações<br />O chalé possui uma mini cozinha<br /><br /><br />7- PETS:<br />Seu pet é super bem-vindo, veja algumas regras e orientações:<br />- Eles não podem subir na cama.<br />- Recolher e limpar a sujeira é de responsabilidade do hóspede<br />- Fora do chalé não existem cercas, se precisar, o cão deverá ser preso na guia.<br />- A diária de cães acompanhantes de hóspedes é de R$90,00 por noite.<br />- Qualquer dano causado pelo PET é de responsabilidade do hóspede<br />- O hóspede deverá levar os itens de higiene para o pet<br /><br /><br />8- LIMPEZA DOS CHALÉS DURANTE A ESTADIA:<br />- O Chalé será entregue limpo e higienizado, a limpeza e arrumação durante o período de locação será de responsabilidade do hóspede.<br /><br /><br />9- PRIVACIDADE DOS CHALÉS:<br />- Cada chalé tem entrada independente e não há necessidade de contato entre hóspedes.<br />- É proibido intimidades com janelas ou portas abertas.<br /><br /><br />10- SILÊNCIO:<br />- Adorai Real é um espaço de relaxamento e paz, portanto, casais, famílias e grupos, antes de alugar precisam concordar com Silêncio. Os sons do seu chalé, música e vozes, não podem ser ouvidos pelos seus vizinhos. Essa regra é muito importante para nossos hóspedes, pois é um espaço para descansar e curtir a natureza e os confortos que oferece. A partir das 20h00min até as 08:00h, o SILÊNCIO é primordial para tranquilidade dos hóspedes, isso é muito importante para o convívio em grupo.<br /><br /><br />11- UTENSÍLIOS DOS CHALÉS:<br />- Os chalés são equipados com um cooktop, forninho, frigobar e utensílios para um casal, tendo a possibilidade de fazer comidas leves.<br /><br />12- O QUE NÃO É PERMITIDO:<br />- Proibido narguilé e cigarro de qualquer tipo dentro dos chalés, LEI ESTADUAL No 13.541, DE 07 DE MAIO DE 2009.<br />- Caso seja detectado uso será cobrado taxa de R$500,00 no momento do check-out.<br />- Proibidos festas e som alto em qualquer horário<br /><br /><br />13- POLÍTICA DE CANCELAMENTO E REAGENDAMENTO:<br />- Todas as informações a respeito de cancelamento, reembolso e reagendamento estão descritas no voucher e em nosso contrato de hospedagem que está anexo logo abaixo.<br />- Caso não consiga abrir o arquivo, basta acessar esse link:<br /><br />Qualquer dúvida, estamos à disposição. 😉<br /><br />Obrigado por escolher o Roteiros Adorai. 😊";

                break;

            case '5158': // PRAIA

                $msgEnvio = "*Parabéns $infoReserva[nome]*, sua reserva no Roteiros Adorai está confirmada!\r\n\r\nChalé: $infoReserva[chale]\r\nPeríodo: $infoReserva[dat_ini] - $infoReserva[dat_fim]\r\nCidade: Paraty/RJ\r\n\r\nNome da propriedade: Adorai Praia\r\n\r\nPara que você possa aproveitar sua experiência com maior conforto e tranquilidade, separamos abaixo as dicas e orientações.\r\n\r\nRecomendamos que leia atentamente.\r\n\r\n\r\n1- COMO CHEGAR NO ADORAI CHALÉS:\r\n- Basta consultar no Google Maps ou Waze o destino: Rua da Praia, 100 - Cantinho do sossego - Prainha de Mambucaba, Paraty - RJ, 23970-000, Brasil\r\n\r\n2- DATA DA ESTADIA:\r\n-Confira no voucher que lhe enviamos por e-mail a data e o chalé que você reservou\r\n\r\n3- DIAS E HORÁRIOS DE ENTRADA:\r\n-Check-in - início da estadia: A partir das 14hs.\r\n-Check-out - fim da estadia: Até 12h (meio dia)\r\n\r\n4- SUPORTE E AJUDA DURANTE A ESTADIA:\r\n- Qualquer necessidade durante a sua estadia, basta entrar em contato com a recepção. 😉\r\n\r\n5- REFEIÇÕES:\r\nRestaurante - Quiosque na praia anexo à Pousada, pagamento a parte\r\nCafé da manhã: Incluso na diária. É servido no restaurante da pousada, das 7:45 hrs até às 10:00 hrs da manhã.\r\nCafé da tarde cortesia: Todos os dias às 17 horas a Pousada serve um cafezinho da tarde como cortesia.\r\nHorário das 8:30 às 18hs\r\nPara jantar 3 opções:\r\nRefeições rápidas na pousada, oferecem o espaço de alimentação com os utensílios necessários.\r\nExcelente opções de restaurantes no bairro ao lado, para comer presencial ou pedir delivery.\r\n\r\n6- PETS:\r\nNão são aceitos pets na estadia\r\n\r\n7- LIMPEZA DURANTE A ESTADIA:\r\n- A HOSPEDAGEM conta com serviço de quarto durante a estadia.\r\n\r\n\r\n8- PRIVACIDADE DOS CHALÉS:\r\n- Cada chalé tem entrada independente e não há necessidade de contato entre hóspedes..\r\n- É proibido intimidades com janelas ou portas abertas.\r\n\r\n9- SILÊNCIO:\r\n- O Adorai Praia é um espaço de relaxamento e paz, portanto, casais, famílias e grupos, antes de alugar precisam concordar com Silêncio. Os sons do seu chalé, música e vozes, não podem ser ouvidos pelos seus vizinhos. Essa regra é muito importante para nossos hóspedes, pois é um espaço para descansar e curtir a natureza e os confortos que oferece.\r\n\r\n10- REGRAS E NORMAS GERAIS:\r\n- Proibido narguilé e cigarro de qualquer tipo dentro dos chalés, fumar somente é permitido ao ar livre, e as bitucas devem ser apagadas na água e jogadas no lixo.\r\n- Não colocar malas em cima da mesa da TV, pois não sustenta peso e pode danificá-la\r\n- Todas as tomadas são 110v\r\n- Para trancar a porta de vidro é necessário duas voltas na chave\r\n- Os anfitriões oferecem a manutenção dos chalés gratuitamente, sendo que as roupas de cama e banho serão trocadas a cada dois dias. Caso não deseje esse serviço, por favor comunique a recepção\r\n- As toalhas de banho e rosto são para uso exclusivo dentro do Chalé, portanto não é permitido levá-las para a praia. Caso seja necessário, será disponibilizado toalhas de praia, que deverão ser devolvidas no check-out\r\n- Os serviços de Aluguel de cadeiras de Praia e Caiaques devem ser solicitados na recepção\r\n- Ao abrir a torneira de água quente do chuveiro,aguardar alguns segundos antes de abrir a de água fria\r\n- Fechar a torneira da ducha higiênica após o uso\r\n- Não jogar papel no vaso sanitário\r\n- Não pendurar roupas ou toalhas no corpo da varanda\r\n\r\n11- POLÍTICA DE CANCELAMENTO E REAGENDAMENTO:\r\n- Todas as informações a respeito de cancelamento, reembolso e reagendamento estão descritas no contrato anexado ao voucher e a esta mensagem.\r\n\r\n\r\nQualquer dúvida, estamos à disposição. 😉\r\n\r\nObrigado por escolher o Roteiros Adorai. 😊";

                $texto = "<b>Parabéns $infoReserva[nome]</b>, sua reserva no Roteiros Adorai está confirmada!<br /><br />Chalé: $infoReserva[chale]<br />Período: $infoReserva[dat_ini] - $infoReserva[dat_fim]<br />Cidade: Paraty/RJ<br /><br />Nome da propriedade: Adorai Praia<br /><br />Para que você possa aproveitar sua experiência com maior conforto e tranquilidade, separamos abaixo as dicas e orientações.<br /><br />Recomendamos que leia atentamente.<br /><br /><br />1- COMO CHEGAR NO ADORAI CHALÉS:<br />- Basta consultar no Google Maps ou Waze o destino: Rua da Praia, 100 - Cantinho do sossego - Prainha de Mambucaba, Paraty - RJ, 23970-000, Brasil<br /><br />2- DATA DA ESTADIA:<br />-Confira no voucher que lhe enviamos por e-mail a data e o chalé que você reservou<br /><br />3- DIAS E HORÁRIOS DE ENTRADA:<br />-Check-in - início da estadia: A partir das 14hs.<br />-Check-out - fim da estadia: Até 12h (meio dia)<br /><br />4- SUPORTE E AJUDA DURANTE A ESTADIA:<br />- Qualquer necessidade durante a sua estadia, basta entrar em contato com a recepção. 😉<br /><br />5- REFEIÇÕES:<br />Restaurante - Quiosque na praia anexo à Pousada, pagamento a parte<br />Café da manhã: Incluso na diária. É servido no restaurante da pousada, das 7:45 hrs até às 10:00 hrs da manhã.<br />Café da tarde cortesia: Todos os dias às 17 horas a Pousada serve um cafezinho da tarde como cortesia.<br />Horário das 8:30 às 18hs<br />Para jantar 3 opções:<br />Refeições rápidas na pousada, oferecem o espaço de alimentação com os utensílios necessários.<br />Excelente opções de restaurantes no bairro ao lado, para comer presencial ou pedir delivery.<br /><br />6- PETS:<br />Não são aceitos pets na estadia<br /><br />7- LIMPEZA DURANTE A ESTADIA:<br />- A HOSPEDAGEM conta com serviço de quarto durante a estadia.<br /><br /><br />8- PRIVACIDADE DOS CHALÉS:<br />- Cada chalé tem entrada independente e não há necessidade de contato entre hóspedes..<br />- É proibido intimidades com janelas ou portas abertas.<br /><br />9- SILÊNCIO:<br />- O Adorai Praia é um espaço de relaxamento e paz, portanto, casais, famílias e grupos, antes de alugar precisam concordar com Silêncio. Os sons do seu chalé, música e vozes, não podem ser ouvidos pelos seus vizinhos. Essa regra é muito importante para nossos hóspedes, pois é um espaço para descansar e curtir a natureza e os confortos que oferece.<br /><br />10- REGRAS E NORMAS GERAIS:<br />- Proibido narguilé e cigarro de qualquer tipo dentro dos chalés, fumar somente é permitido ao ar livre, e as bitucas devem ser apagadas na água e jogadas no lixo.<br />- Não colocar malas em cima da mesa da TV, pois não sustenta peso e pode danificá-la<br />- Todas as tomadas são 110v<br />- Para trancar a porta de vidro é necessário duas voltas na chave<br />- Os anfitriões oferecem a manutenção dos chalés gratuitamente, sendo que as roupas de cama e banho serão trocadas a cada dois dias. Caso não deseje esse serviço, por favor comunique a recepção<br />- As toalhas de banho e rosto são para uso exclusivo dentro do Chalé, portanto não é permitido levá-las para a praia. Caso seja necessário, será disponibilizado toalhas de praia, que deverão ser devolvidas no check-out<br />- Os serviços de Aluguel de cadeiras de Praia e Caiaques devem ser solicitados na recepção<br />- Ao abrir a torneira de água quente do chuveiro,aguardar alguns segundos antes de abrir a de água fria<br />- Fechar a torneira da ducha higiênica após o uso<br />- Não jogar papel no vaso sanitário<br />- Não pendurar roupas ou toalhas no corpo da varanda<br /><br />11- POLÍTICA DE CANCELAMENTO E REAGENDAMENTO:<br />- Todas as informações a respeito de cancelamento, reembolso e reagendamento estão descritas no contrato anexado ao voucher e a esta mensagem.<br /><br /><br />Qualquer dúvida, estamos à disposição. 😉<br /><br />Obrigado por escolher o Roteiros Adorai. 😊";

                break;

            case '5156': // ACONCHEGO

                $msgEnvio = "*Parabéns, $infoReserva[nome]*! Sua reserva no Roteiros Adorai está confirmada!\r\n\r\nChalé: $infoReserva[chale]\r\nPeríodo: $infoReserva[dat_ini] - $infoReserva[dat_fim]\r\nCidade: Cunha/SP\r\nNome da propriedade: Adorai Aconchego\r\n\r\nOs Anfitriões entrarão em contato com você para te passar algumas informações importantes sobre a sua estadia.\r\nCaso prefira, pode chamá-la pelo whatsapp no número 12 99662-3988.\r\n\r\nBem vindos ao Adorai Aconchego\r\n\r\nPara que você possa aproveitar sua experiência com maior conforto e tranquilidade, separamos abaixo as dicas e orientações:\r\n\r\n1 - CHECK-IN/CHECK-OUT:\r\n- Nosso check-in é a partir das 14:00hrs a até as 21:00hrs.\r\n- Nosso check-out até 12hs (Meio dia)\r\n- Disponibilizamos apenas 01 chave por apartamento. Ao sair, deixe na recepção, pois saberemos que o apartamento está disponível para arrumação colocando também a placa de porta.\r\n\r\n2 - COMO CHEGAR:\r\n- Link do maps: https://maps.app.goo.gl/KoWFL46qtycNoC158\r\n- Existem dois caminhos para chegar na pousada, recomendamos por favor que sigam as indicações:\r\n- Assim que entrar no portal da cidade, siga na Rua Alameda Francisco da Cunha Menezes, primeira rotatória entra na terceira saída a direita na rua Dhaer Pedro. Siga em frente e vire à esquerda  na Av Augusto Galvão de França, seguindo em frente passa um posto de combustível (BR), continua subindo sempre reto e sairá da cidade, encontrando nossas placas indicativas. No km 4 quando avistar várias placas vire à esquerda e siga 1km de terra.Temos um muro branco com acabamento amarelo e uma placa da pousada à esquerda.\r\n- Recomendamos a vir pelo google maps que é mais confiável.\r\n\r\n4 - ALIMENTAÇÃO:\r\nO café da manhã é servido no restaurante das 08:00hrs ás 10:30hrs\r\nO restaurante funciona de quinta a domingo, das 13:30hrs às 22:00 hrs. Pedidos são aceitos até as 21:00hrs\r\nDelivery: consultar disponibilidade\r\n\r\n5 - PETS:\r\nSeu pet é bem vindo em nossa pousada, entretanto há regras necessárias para um bom convívio junto aos demais hóspedes, quais sejam, mantê-lo em guia, não deixá-lo sozinho na acomodação quando se ausentar da pousada e não adentrar a área do restaurante.\r\nCobramos uma taxa extra de R$90,00 por diária e por pet\r\n\r\n6 - REGRAS E INFORMAÇÕES GERAIS:\r\nAs tomadas nos apartamentos são 110V e 220V.\r\nSenha do Wi-fi: bocaina1\r\nOs frigobares ficam desligados, entretanto, caso seja de seu interesse, bebidas poderão ser solicitadas junto à recepção no momento do Check In ou em outro momento oportuno e serão levadas até sua acomodação.\r\nCaso em sua acomodação não haja secador de cabelo, este poderá ser solicitado junto à recepção, bem como travesseiro ou manta extra.\r\nAs toalhas da acomodação não poderão ser retiradas para uso na piscina, entretanto dispomos de toalhas para esse uso, as quais poderão ser solicitadas na recepção e deverão ser utilizadas apenas nas dependências da pousada e devolvidas.\r\nQuando se ausentar da acomodação favor desligarem as luzes, ventiladores, ar condicionado e banheira, sendo que o não cumprimento poderá implicar em taxa extra.\r\nNão é permitido o consumo de bebidas e comidas trazidas para a pousada, fora da acomodação, ou seja, nas áreas comuns, inclusive na piscina.\r\nConforme Lei Estadual número 13.541 de 07/05/2009 é expressamente proibido fumar dentro das acomodações.\r\n\r\n\r\n7 - CONTRATO DE HOSPEDAGEM:\r\n- Você receberá o nosso contrato anexado ao seu voucher, via e-mail e anexado a essa mensagem\r\n- Caso não consiga visualizar o arquivo basta acessar através deste link:\r\n\r\nQualquer dúvida é só entrar em contato. 😉\r\n\r\nObrigado por escolher o Roteiros Adorai. 😊";

                $texto = "<b>Parabéns, $infoReserva[nome]</b>! Sua reserva no Roteiros Adorai está confirmada!<br /><br />Chalé: $infoReserva[chale]<br />Período: $infoReserva[dat_ini] - $infoReserva[dat_fim]<br />Cidade: Cunha/SP<br />Nome da propriedade: Adorai Aconchego<br /><br />Os Anfitriões entrarão em contato com você para te passar algumas informações importantes sobre a sua estadia.<br />Caso prefira, pode chamá-la pelo whatsapp no número 12 99662-3988.<br /><br />Bem vindos ao Adorai Aconchego<br /><br />Para que você possa aproveitar sua experiência com maior conforto e tranquilidade, separamos abaixo as dicas e orientações:<br /><br />1 - CHECK-IN/CHECK-OUT:<br />- Nosso check-in é a partir das 14:00hrs a até as 21:00hrs.<br />- Nosso check-out até 12hs (Meio dia)<br />- Disponibilizamos apenas 01 chave por apartamento. Ao sair, deixe na recepção, pois saberemos que o apartamento está disponível para arrumação colocando também a placa de porta.<br /><br />2 - COMO CHEGAR:<br />- Link do maps: https://maps.app.goo.gl/KoWFL46qtycNoC158<br />- Existem dois caminhos para chegar na pousada, recomendamos por favor que sigam as indicações:<br />- Assim que entrar no portal da cidade, siga na Rua Alameda Francisco da Cunha Menezes, primeira rotatória entra na terceira saída a direita na rua Dhaer Pedro. Siga em frente e vire à esquerda  na Av Augusto Galvão de França, seguindo em frente passa um posto de combustível (BR), continua subindo sempre reto e sairá da cidade, encontrando nossas placas indicativas. No km 4 quando avistar várias placas vire à esquerda e siga 1km de terra.Temos um muro branco com acabamento amarelo e uma placa da pousada à esquerda.<br />- Recomendamos a vir pelo google maps que é mais confiável.<br /><br />4 - ALIMENTAÇÃO:<br />O café da manhã é servido no restaurante das 08:00hrs ás 10:30hrs<br />O restaurante funciona de quinta a domingo, das 13:30hrs às 22:00 hrs. Pedidos são aceitos até as 21:00hrs<br />Delivery: consultar disponibilidade<br /><br />5 - PETS:<br />Seu pet é bem vindo em nossa pousada, entretanto há regras necessárias para um bom convívio junto aos demais hóspedes, quais sejam, mantê-lo em guia, não deixá-lo sozinho na acomodação quando se ausentar da pousada e não adentrar a área do restaurante.<br />Cobramos uma taxa extra de R$90,00 por diária e por pet<br /><br />6 - REGRAS E INFORMAÇÕES GERAIS:<br />As tomadas nos apartamentos são 110V e 220V.<br />Senha do Wi-fi: bocaina1<br />Os frigobares ficam desligados, entretanto, caso seja de seu interesse, bebidas poderão ser solicitadas junto à recepção no momento do Check In ou em outro momento oportuno e serão levadas até sua acomodação.<br />Caso em sua acomodação não haja secador de cabelo, este poderá ser solicitado junto à recepção, bem como travesseiro ou manta extra.<br />As toalhas da acomodação não poderão ser retiradas para uso na piscina, entretanto dispomos de toalhas para esse uso, as quais poderão ser solicitadas na recepção e deverão ser utilizadas apenas nas dependências da pousada e devolvidas.<br />Quando se ausentar da acomodação favor desligarem as luzes, ventiladores, ar condicionado e banheira, sendo que o não cumprimento poderá implicar em taxa extra.<br />Não é permitido o consumo de bebidas e comidas trazidas para a pousada, fora da acomodação, ou seja, nas áreas comuns, inclusive na piscina.<br />Conforme Lei Estadual número 13.541 de 07/05/2009 é expressamente proibido fumar dentro das acomodações.<br /><br /><br />7 - CONTRATO DE HOSPEDAGEM:<br />- Você receberá o nosso contrato anexado ao seu voucher, via e-mail e anexado a essa mensagem<br />- Caso não consiga visualizar o arquivo basta acessar através deste link:<br /><br />Qualquer dúvida é só entrar em contato. 😉<br /><br />Obrigado por escolher o Roteiros Adorai. 😊";

                break;

            default: // CAMPO

                $msgEnvio = "*Parabéns $infoReserva[nome]*, sua reserva no Roteiros Adorai está confirmada!\r\n\r\nChalé: $infoReserva[chale] \r\nPeríodo: $infoReserva[dat_ini] - $infoReserva[dat_fim] \r\nCidade: Piedade/SP\r\n\r\nNome da propriedade: Adorai Chalés\r\n\r\nPara que você possa aproveitar sua experiência com maior conforto e tranquilidade, separamos abaixo as dicas e orientações.\r\n\r\nRecomendamos que leia atentamente.\r\n\r\n\r\n1- COMO CHEGAR NO ADORAI CHALÉS:\r\n- Basta consultar no Google Maps ou Waze o destino: Adorai Chalés.\r\n- Endereço: R. Dimas Silva, s/n - Caetezal, Piedade - SP, 18170-000\r\n- Link: https://maps.app.goo.gl/mic8zfNG5T2uc63T7\r\n\r\n\r\n2- DATA DA ESTADIA:\r\n-Confirme no voucher que lhe enviamos por e-mail a data e o chalé que você reservou...\r\n\r\n\r\n3- DIAS E HORÁRIOS DE ENTRADA:\r\n-Check-in - início da estadia: A partir das 16hs.\r\n-Check-out - fim da estadia: Até 12h (meio dia)\r\n\r\n\r\n4- SUPORTE E AJUDA DURANTE A ESTADIA:\r\n- A nossa equipe da recepção entrará em contato 72hrs antes do seu check-in para enviar as informações a respeito de como chegar, as instruções do self-check-in, senhas e o funcionamento do seu chalé.\r\n- Para agilizar ainda mais o seu atendimento, pedimos que durante a sua estadia entre em contato somente através do número pelo qual você fechou a reserva\r\n- Nossa equipe estará disponível das 9 às 21hrs de segunda a quinta, das 9 às 17:00hrs na sexta e das 13 às 21:00hrs aos domingos e feriados. Não temos expediente do restante da equipe aos sábados, mas a recepção terá alguém de plantão para te atender em caso de emergências. 😉\r\n\r\n\r\n5- INTERNET E TV:\r\n- Cada chalé é equipado com um roteador independente.\r\n- A velocidade é suficiente para fazer videoconferência e assistir Youtube e Netflix.\r\n- A conexão pode oscilar e falhar como em qualquer lugar, entretanto nosso contrato com o provedor garante que exceto em caso de fortes ventos e vendavais, caso haja interrupção eles restabelecem a conexão em até 4 horas.\r\n- A senha da internet estará disponível dentro do chalé.\r\n\r\n\r\n6- REFEIÇÕES:\r\nAs hospedagens funcionam como auto serviço, onde você viverá a experiência como se estivesse em sua casa de campo, veja as opções:\r\nA - Compras no mercadinho no local que funciona 24 horas no sistema de auto-atendimento, você poderá comprar suas refeições, alimentos, carnes, bebidas e sobremesas;\r\nB - Poderá pedir Delivery, pizzas, lanches, comida japonesa, etc…\r\nC - Montar sua cesta café da manhã com os itens da sua preferência;\r\nD - Comer em deliciosos e pitorescos restaurantes da cidade;\r\nE - Fazer um churrasco ou cozinhar no chalé.\r\n\r\nCaso você tenha pago antecipadamente as refeições ou kit foundue, o valor será inserido como crédito em um cartão pré-pago que estará a sua disposição no chalé, com ele você poderá comprar no mercadinho refeições prontas, alimentos, sobremesas e bebidas ou pedir delivery, com isto terá a liberdade de fazer as refeições da forma que preferir!\r\n\r\n\r\n7- PETS:\r\nSeu pet é super bem-vindo, veja algumas regras e orientações:\r\n- Eles não podem subir na cama.\r\n- Recolher e limpar a sujeira é de responsabilidade do hóspede\r\n- Fora do chalé não existem cercas, se precisar, o cão deverá ser preso na guia. Mas a maioria dos cães ficam soltos próximo do chalé.\r\n- Quando temos a visita de pets retiramos os tapetes do chalé.\r\n- A diária de cães acompanhantes de hóspedes é de R$90,00 por noite.\r\n\r\n\r\n8- ÁGUA:\r\n- A água do chalé é de poço, nós e os funcionários bebem, mas recomendamos que você traga a sua água mineral.\r\n\r\n\r\n9- MANUTENÇÃO DURANTE A ESTADIA:\r\n-Em caso de pane de equipamentos ou qualquer outro problema durante a hospedagem, nossa equipe fará o máximo possível para que seja solucionado em até 2h.\r\n\r\n\r\n10- LIMPEZA DOS CHALÉS DURANTE A ESTADIA:\r\n- O Chalé será entregue limpo e higienizado, a limpeza e arrumação durante o período de locação será de responsabilidade do hóspede.\r\n\r\n\r\n11- PRIVACIDADE DOS CHALÉS:\r\n- Cada chalé tem entrada independente e não há necessidade de contato entre hóspedes.\r\n- Caso queiram usar a banheira ou piscinas dos chalés com janelas abertas é permitido desde que estejam de sungas ou biquínis como numa praia ou clube.\r\n- É proibido intimidades com janelas ou portas abertas.\r\n\r\n\r\n12- SILÊNCIO:\r\n- Adorai Chalés é um espaço de relaxamento e paz, portanto, casais, famílias e grupos, antes de alugar precisam concordar com Silêncio. Os sons do seu chalé, música e vozes, não podem ser ouvidos pelos seus vizinhos. Essa regra é muito importante para nossos hóspedes, pois é um espaço para descansar e curtir a natureza e os confortos que oferece.\r\n\r\n\r\n13- USO DAS PISCINAS AQUECIDAS/BANHEIRA E PISCINA COLETIVA:\r\n- Não é permitido o uso de shampoos e sabonetes para fazer espuma nas piscinas aquecidas, seu uso deve ser feito apenas nos chalés que possuem banheira.\r\n- Caso no momento do Check-out seja detectado uso de espumas, sais de banho ou produtos químicos nas piscinas será cobrado no momento do check-out uma taxa de R$500,00.\r\n- A piscina coletiva é de uso comum.\r\n\r\n\r\n14- UTENSÍLIOS DOS CHALÉS:\r\n- Utensílios básicos como: Panelas, pratos, copos, taças de vinho, talheres, abridor de vinho, pano de prato, aparelho de fondue, faca e tábua de churrasco, cafeteira e jogo americano.\r\n- Material de consumo: Sabonete líquido, detergente, bucha, saco de lixo, papel higiênico, rodo, vassoura, pano de chão.\r\n- Toda roupa de cama, banho e cozinha.\r\n- Os hóspedes devem trazer apenas coisas pessoais.\r\n\r\n\r\n15- O QUE NÃO É PERMITIDO:\r\n- Proibido narguilé e cigarro de qualquer tipo dentro dos chalés, fumar somente é permitido ao ar livre, e as bitucas devem ser apagadas na água e jogadas no lixo.\r\n- Caso seja detectado uso será cobrado taxa de R$ 500,00 no momento do check-out.\r\n- A voltagem é 127v no chalé, com tomadas de 3 pinos.\r\n\r\n\r\n16- POLÍTICA DE CANCELAMENTO E REAGENDAMENTO:\r\n- Todas as informações a respeito de cancelamento, reembolso e reagendamento estão descritas no voucher e em nosso contrato de hospedagem que está anexo logo abaixo.\r\n- Caso não consiga abrir o arquivo, basta acessar esse link: https://docs.google.com/document/d/1SbAQmNyIeE3pjQFzhCHK_G7Hj91CR2lJXlUgLM0FJHY/edit?usp=sharing\r\n\r\n\r\nQualquer dúvida, estamos à disposição. 😉\r\n\r\nObrigado por escolher o Roteiros Adorai. 😊";

                $texto = "<b>Parabéns $infoReserva[nome]</b>, sua reserva no Roteiros Adorai está confirmada!<br /><br />Chalé: $infoReserva[chale]<br />Período: $infoReserva[dat_ini] - $infoReserva[dat_fim]<br />Cidade: Piedade/SP<br /><br />Nome da propriedade: Adorai Chalés<br /><br />Para que você possa aproveitar sua experiência com maior conforto e tranquilidade, separamos abaixo as dicas e orientações.<br /><br />Recomendamos que leia atentamente.<br /><br /><br />1- COMO CHEGAR NO ADORAI CHALÉS:<br />- Basta consultar no Google Maps ou Waze o destino: Adorai Chalés.<br />- Endereço: R. Dimas Silva, s/n - Caetezal, Piedade - SP, 18170-000<br />- Link: https://goo.gl/maps/fEUmFs8iy2SCydBN8<br /><br /><br />2- DATA DA ESTADIA:<br />-Confirme no voucher que lhe enviamos por e-mail a data e o chalé que você reservou...<br /><br /><br />3- DIAS E HORÁRIOS DE ENTRADA:<br />-Check-in - início da estadia: A partir das 16hs.<br />-Check-out - fim da estadia: Até 12h (meio dia)<br /><br /><br />4- SUPORTE E AJUDA DURANTE A ESTADIA:<br />- A nossa equipe da recepção entrará em contato 72hrs antes do seu check-in para enviar as informações a respeito de como chegar, as instruções do self-check-in, senhas e o funcionamento do seu chalé.<br />- Para agilizar ainda mais o seu atendimento, pedimos que durante a sua estadia entre em contato somente através do número pelo qual você fechou a reserva<br />- Nossa equipe estará disponível das 9 às 21hrs de segunda a quinta, das 9 às 17:00hrs na sexta e das 13 às 21:00hrs aos domingos e feriados. Não temos expediente do restante da equipe aos sábados, mas a recepção terá alguém de plantão para te atender em caso de emergências. 😉<br /><br /><br />5- INTERNET E TV:<br />- Cada chalé é equipado com um roteador independente.<br />- A velocidade é suficiente para fazer videoconferência e assistir Youtube e Netflix.<br />- A conexão pode oscilar e falhar como em qualquer lugar, entretanto nosso contrato com o provedor garante que exceto em caso de fortes ventos e vendavais, caso haja interrupção eles restabelecem a conexão em até 4 horas.<br />- A senha da internet estará disponível dentro do chalé.<br /><br /><br />6- REFEIÇÕES:<br />As hospedagens funcionam como auto serviço, onde você viverá a experiência como se estivesse em sua casa de campo, veja as opções:<br />A - Compras no mercadinho no local que funciona 24 horas no sistema de auto-atendimento, você poderá comprar suas refeições, alimentos, carnes, bebidas e sobremesas;<br />B - Poderá pedir Delivery, pizzas, lanches, comida japonesa, etc…<br />C - Montar sua cesta café da manhã com os itens da sua preferência;<br />D - Comer em deliciosos e pitorescos restaurantes da cidade;<br />E - Fazer um churrasco ou cozinhar no chalé.<br /><br />Caso você tenha pago antecipadamente as refeições ou kit foundue, o valor será inserido como crédito em um cartão pré-pago que estará a sua disposição no chalé, com ele você poderá comprar no mercadinho refeições prontas, alimentos, sobremesas e bebidas ou pedir delivery, com isto terá a liberdade de fazer as refeições da forma que preferir!<br /><br /><br />7- PETS:<br />Seu pet é super bem-vindo, veja algumas regras e orientações:<br />- Eles não podem subir na cama.<br />- Recolher e limpar a sujeira é de responsabilidade do hóspede<br />- Fora do chalé não existem cercas, se precisar, o cão deverá ser preso na guia. Mas a maioria dos cães ficam soltos próximo do chalé.<br />- Quando temos a visita de pets retiramos os tapetes do chalé.<br />- A diária de cães acompanhantes de hóspedes é de R$90,00 por noite.<br /><br /><br />8- ÁGUA:<br />- A água do chalé é de poço, nós e os funcionários bebem, mas recomendamos que você traga a sua água mineral.<br /><br /><br />9- MANUTENÇÃO DURANTE A ESTADIA:<br />-Em caso de pane de equipamentos ou qualquer outro problema durante a hospedagem, nossa equipe fará o máximo possível para que seja solucionado em até 2h.<br /><br /><br />10- LIMPEZA DOS CHALÉS DURANTE A ESTADIA:<br />- O Chalé será entregue limpo e higienizado, a limpeza e arrumação durante o período de locação será de responsabilidade do hóspede.<br /><br /><br />11- PRIVACIDADE DOS CHALÉS:<br />- Cada chalé tem entrada independente e não há necessidade de contato entre hóspedes.<br />- Caso queiram usar a banheira ou piscinas dos chalés com janelas abertas é permitido desde que estejam de sungas ou biquínis como numa praia ou clube.<br />- É proibido intimidades com janelas ou portas abertas.<br /><br /><br />12- SILÊNCIO:<br />- Adorai Chalés é um espaço de relaxamento e paz, portanto, casais, famílias e grupos, antes de alugar precisam concordar com Silêncio. Os sons do seu chalé, música e vozes, não podem ser ouvidos pelos seus vizinhos. Essa regra é muito importante para nossos hóspedes, pois é um espaço para descansar e curtir a natureza e os confortos que oferece.<br /><br /><br />13- USO DAS PISCINAS AQUECIDAS/BANHEIRA E PISCINA COLETIVA:<br />- Não é permitido o uso de shampoos e sabonetes para fazer espuma nas piscinas aquecidas, seu uso deve ser feito apenas nos chalés que possuem banheira.<br />- Caso no momento do Check-out seja detectado uso de espumas, sais de banho ou produtos químicos nas piscinas será cobrado no momento do check-out uma taxa de R$500,00.<br />- A piscina coletiva é de uso comum.<br /><br /><br />14- UTENSÍLIOS DOS CHALÉS:<br />- Utensílios básicos como: Panelas, pratos, copos, taças de vinho, talheres, abridor de vinho, pano de prato, aparelho de fondue, faca e tábua de churrasco, cafeteira e jogo americano.<br />- Material de consumo: Sabonete líquido, detergente, bucha, saco de lixo, papel higiênico, rodo, vassoura, pano de chão.<br />- Toda roupa de cama, banho e cozinha.<br />- Os hóspedes devem trazer apenas coisas pessoais.<br /><br /><br />15- O QUE NÃO É PERMITIDO:<br />- Proibido narguilé e cigarro de qualquer tipo dentro dos chalés, fumar somente é permitido ao ar livre, e as bitucas devem ser apagadas na água e jogadas no lixo.<br />- Caso seja detectado uso será cobrado taxa de R$ 500,00 no momento do check-out.<br />- A voltagem é 127v no chalé, com tomadas de 3 pinos.<br /><br /><br />16- POLÍTICA DE CANCELAMENTO E REAGENDAMENTO:<br />- Todas as informações a respeito de cancelamento, reembolso e reagendamento estão descritas no voucher e em nosso contrato de hospedagem que está anexo logo abaixo.<br />- Caso não consiga abrir o arquivo, basta acessar esse link: https://docs.google.com/document/d/1SbAQmNyIeE3pjQFzhCHK_G7Hj91CR2lJXlUgLM0FJHY/edit?usp=sharing<br /><br /><br />Qualquer dúvida, estamos à disposição. 😉<br /><br />Obrigado por escolher o Roteiros Adorai. 😊";

                break;
        }

        // fnEscreve2("chega aqui");

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

        $num_celular = $infoReserva['celular'];

        // fnEscreve2($num_celular);
        $codPais = substr($num_celular, 0, 2);

        if ($codPais != "55") {
            $num_celular = "55" . $infoReserva['celular'];
        }

        $msgsbtr =  str_replace(["\r\n", "\r", "\n"], '\n', $msgEnvio);

        $resultcreate = FnsendText($session, $des_authkey, $num_celular, $msgsbtr, 3, $port);

        // echo "<pre>";
        // fnEscreve2($session);
        // fnEscreve2($des_authkey);
        // fnEscreve2($num_celular);
        // fnEscreve2($msgsbtr);
        // print_r($resultcreate);
        // echo "</pre>";

        // ----------------------------------------------------------------------------


        $linkEnvio = "https://roteirosadorai.com.br/hospedes.php?id=" . fnEncode($uuid);

        $linkEnvio = file_get_contents("http://tinyurl.com/api-create.php?url=" . $linkEnvio);

        $msgEnvio = "*Sua reserva já esta confirmada, mas ainda precisamos de algumas informações!*\r\nPor favor valide as informações clicando no link abaixo e preenchendo os dados solicitados.\r\n$linkEnvio";

        $msgsbtr =  str_replace(["\r\n", "\r", "\n"], '\n', $msgEnvio);

        $resultcreate = FnsendText($session, $des_authkey, $num_celular, $msgsbtr, 3, $port);

        // echo "<pre>";
        // print_r($resultcreate);
        // echo "</pre>";

        // exit();

        header('Content-Type: text/html; charset=utf-8');

        $sql = "SELECT DES_EMAIL FROM SITE_EXTRATO WHERE COD_EMPRESA = $cod_empresa";
        $qrEmail = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa, ''), $sql));

        $sqlEmp = "SELECT NOM_FANTASI FROM EMPRESAS WHERE COD_EMPRESA = $cod_empresa";
        $qrEmp = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $sqlEmp));

        include "../_system/EMAIL/PHPMailer/PHPMailerAutoload.php";
        include '../externo/email/envio_sac.php';

        $texto2 = "<b>Sua reserva já esta confirmada, mas ainda precisamos de algumas informações!</b><br />Por favor valide as informações clicando no link abaixo e preenchendo os dados solicitados.<br /><br />$linkEnvio";


        $email['email1'] = $infoReserva[email];

        $retorno2 = fnsacmail(
            $email,
            'Roteiros Adorai',
            "<html>" . $texto . "</html>",
            "RESERVA CONFIRMADA",
            "roteirosadorai.com.br",
            $connAdm->connAdm(),
            connTemp($cod_empresa, ""),
            $cod_empresa
        );

        $retorno = fnsacmail(
            $email,
            'Roteiros Adorai',
            "<html>" . $texto2 . "</html>",
            "INFORMAÇÕES NECESSÁRIAS",
            "roteirosadorai.com.br",
            $connAdm->connAdm(),
            connTemp($cod_empresa, ""),
            $cod_empresa
        );

        // echo json_encode($resultcreate);
        // echo print_r($resultcreate);

        $sqlConfirma = "UPDATE ADORAI_PEDIDO SET LOG_CONFIRMA = 'S' WHERE UUID = '$uuid'";
        mysqli_query(connTemp($cod_empresa, ""), $sqlConfirma);

        break;

    default:

        echo "O sistema acusou excesso de metais  ;-(";

        break;
}
