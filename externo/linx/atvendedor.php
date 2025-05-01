<?php
include '../../_system/_functionsMain.php';
include '../totvs/funcao.php';
include '../email/envio_sac.php';
//fnDebug('TRUE');
function fnFormatvalorlinx($brl, $casasDecimais = 2)
{
    // Se já estiver no formato USD, retorna como float e formatado
    if (preg_match('/^\d+\.{1}\d+$/', $brl))
        return (float) number_format($brl, $casasDecimais, '.', '');
    // Tira tudo que não for número, ponto ou vírgula
    $brl = preg_replace('/[^\d\.\,]+/', '', $brl);
    // Tira o ponto
    $decimal = str_replace('.', '', $brl);
    // Troca a vírgula por ponto
    $decimal = str_replace(',', '.', $decimal);
    return (float) number_format($decimal, $casasDecimais, '.', '');
}
$admconex = $connAdm->connAdm();
$sqlconfig = "SELECT * FROM webhook web WHERE tip_webhook=8 AND web.LOG_ESTATUS='S'";
$rwconfigorigem = mysqli_query($admconex, $sqlconfig);
while ($rsconfig = mysqli_fetch_assoc($rwconfigorigem)) {

    $data = date('Y-m-d', strtotime(date('Y-m-d') . ' - 1 days'));
    echo $data . '<br />';
    //  $data=date('Y-m-d');   
    //$data='2022-04-19';

    unset($dadoslogin);
    $key_marka = fnDecode(base64_decode($rsconfig['DES_SENHAMARKA']));
    $dadoslogin = explode(';', $key_marka);
    if ($dadoslogin[2] == '99999') {
        // and COD_UNIVEND='97552'
        $sqluni = "SELECT COD_UNIVEND,NUM_CGCECPF,NOM_FANTASI FROM unidadevenda WHERE COD_EMPRESA=$dadoslogin[4] AND LOG_ESTATUS='S' ORDER BY COD_UNIVEND desc";
    } elseif ($dadoslogin[2] == '') {
        $COD_UNIVENDL = preg_replace('/\s+/', '', $dadoslogin[2]);
        $sqluni = "SELECT COD_UNIVEND,NUM_CGCECPF,NOM_FANTASI FROM unidadevenda WHERE COD_UNIVEND= $dadoslogin[2] and  COD_EMPRESA=$COD_UNIVENDL AND LOG_ESTATUS='S' ORDER BY COD_UNIVEND desc";
    }
    $rsuni = mysqli_query($admconex, $sqluni);
    while ($rwuni = mysqli_fetch_assoc($rsuni)) {

        ob_start();
        $NUM_CGCECPF = fnLimpaDoc($rwuni['NUM_CGCECPF']);
        $dadoslogin[2] = preg_replace('/\s+/', '', $rwuni['COD_UNIVEND']);

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://webapi.microvix.com.br/1.0/api/integracao',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 100000000,
            CURLOPT_TIMEOUT => 3000,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '<?xml version=\'1.0\' encoding=\'utf-8\' ?>
                                                        <LinxMicrovix>
                                                            <Authentication user="linx_export" password="linx_export" />
                                                            <ResponseFormat>xml</ResponseFormat>
                                                            <Command>
                                                                <Name>LinxVendedores</Name>
                                                                <Parameters>
                                                                    <Parameter id="chave">' . $rsconfig['DES_SENHA'] . '</Parameter>
                                                                    <Parameter id="cnpjEmp">' . $NUM_CGCECPF . '</Parameter>
                                                                </Parameters>
                                                            </Command>
                                                        </LinxMicrovix>',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/xml; charset=UTF-8'
            ),
        ));
        $err = curl_error($curl);
        $response = curl_exec($curl);
        $info = curl_getinfo($curl);
        //  echo 'Took ' . $info['total_time'] . ' seconds to transfer a request to ' . $info['url'].'<br>';
        if ($err) {
            echo "cURL Error #:" . $err;
        }
        curl_close($curl);
        $xml = simplexml_load_string($response, "SimpleXMLElement", LIBXML_NOCDATA);
        $json = json_encode($xml);
        $arraytroca = json_decode($json, TRUE);
        /* echo '<pre>';
                            print_r($arraytroca);
                            echo '</pre>';*/
        foreach ($arraytroca['ResponseData']['R'] as $key => $value) {
            //pegar só o que é vendedor
            if ($value['D'][3] == 'V' || $value['D'][3] == 'A') {
                //     echo $value[D][1].'<br>'; //cod_vendedor
                //     echo $value[D][2].'<br>'; //nome_vendedor
                //    echo $value[D][3].'<br>'; //tipo_vendedor
                //    echo $value[D][15].'<br>---<br>'; //ATIVO/OU/NAO

                $at = "UPDATE usuarios SET NOM_USUARIO='" . addslashes($value['D'][2]) . "', COD_ALTERAC='9999', DAT_ALTERAC=now() WHERE COD_EXTERNO='" . $value['D'][1] . "' and cod_empresa=$dadoslogin[4] AND COD_TPUSUARIO IN (8,7,11)  AND cod_univend=" . preg_replace('/\s+/', '', $dadoslogin[2]) . ";";
                $error = mysqli_query($admconex, $at);
                echo $at . '<br>';
                if (!$error) {
                    echo $at . '-------------------------------------<br>----------------------------------------<br>';
                }
            }
        }
        unset($dadoslogin[2]);
    }
    echo 'FIM DO PRIMEIRO PERIODO.<br>';
}
