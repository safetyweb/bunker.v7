<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if($_SERVER[REQUEST_METHOD]!='POST')
{
        http_response_code(400);
        $erroinformation='{"errors": [
                                            {
                                             "msgerro": "Esse metodo so aceita post",                                
                                             "coderro": "400"
                                            }
                                         ]
                              }';    
        echo $erroinformation; 
        exit();
}  


include '../func/function.php';
include '../../_system/Class_conn.php';
$dadosenvio = json_decode(file_get_contents("php://input"),true);

//connadm

$conadmcep=$connAdm->connAdm();

$sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('".$dadosenvio[login]."', '".$dadosenvio[senha]."','','','".$dadosenvio[idcliente]."','','')";
$row=mysqli_fetch_assoc(mysqli_query($conadmcep,$sql));
if(!isset($row['LOG_USUARIO']) || !isset($row['DES_SENHAUS']))
{
     http_response_code(400);
        $erroinformation='{"errors": [
                                        {
                                         "msgerro": "Verifique os dados de dadoslogin!",                                
                                         "coderro": "400"
                                        }
                                     ]
                          }';    
          echo $erroinformation;
          exit();
}
/*if(isset($dadosenvio[CEP]))
{
     http_response_code(400);
        $erroinformation='{"errors": [
                                        {
                                         "msgerro": "CEP nao pode ser vazio",                                
                                         "coderro": "400"
                                        }
                                     ]
                          }';    
          echo $erroinformation;
          exit();
}*/
//COMMCEP
$concep=$DADOS_CEP->connAdm();
$buscacepsql="SELECT cp.logradouro,br.bairro,cid.cidade,cid.uf,cp.cep,cp.tipo_logradouro FROM cepbr_endereco cp
                inner JOIN cepbr_cidade cid ON cid.id_cidade=cp.id_cidade
                inner JOIN  cepbr_bairro br ON br.id_bairro=cp.id_bairro
                WHERE cp.cep='$dadosenvio[CEP]'";
$rwcep=mysqli_query($concep, $buscacepsql);
if($rwcep->num_rows>'0')
{ 
    $rscep=mysqli_fetch_assoc($rwcep);
    $cepLOCAL= array(   "logradouro" =>$rscep[tipo_logradouro].' '.$rscep[logradouro],
                        "bairro" =>$rscep[bairro],
                        "cidade"=>$rscep[cidade],
                        "uf"=>$rscep[uf],
                        "cep"=>$rscep[cep]
                       );
    echo json_encode($cepLOCAL,true);
    exit();
}

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt_array($curl, array(
    CURLOPT_URL => "https://apps.correios.com.br/SigepMasterJPA/AtendeClienteService/AtendeCliente?wsdl=",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => "<soapenv:Envelope xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:cli=\"http://cliente.bean.master.sigep.bsb.correios.com.br/\">\r\n   <soapenv:Header/>\r\n   <soapenv:Body>\r\n      <cli:consultaCEP>\r\n         <!--Optional:-->\r\n         <cep>".$dadosenvio[CEP]."</cep>\r\n      </cli:consultaCEP>\r\n   </soapenv:Body>\r\n</soapenv:Envelope>",
    CURLOPT_HTTPHEADER => array(
      "cache-control: no-cache",
      "postman-token: 1c811616-5d03-93fa-371a-14c22575ae9a"
    ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    $doc = new DOMDocument();
    libxml_use_internal_errors(true);
    $doc->loadHTML($response);
    libxml_clear_errors();
    $xml = $doc->saveXML($doc->documentElement);
    $xml = simplexml_load_string($xml,'SimpleXMLElement', LIBXML_NOCDATA);
    $xml = json_decode(json_encode($xml), TRUE);
    $rua= $xml['body']['envelope']['body']['consultacepresponse']['return']['end'];
    $bairro= $xml['body']['envelope']['body']['consultacepresponse']['return']['bairro'];
    $cidade= $xml['body']['envelope']['body']['consultacepresponse']['return']['cidade'];
    $uf= $xml['body']['envelope']['body']['consultacepresponse']['return']['uf'];
    $cep1= $xml['body']['envelope']['body']['consultacepresponse']['return']['cep'];
    $cep= array( "logradouro" => $rua,
                 "bairro" => $bairro,
                 'cidade'=>$cidade,
                 'uf'=>$uf,
                 'cep'=>$cep1
                );
   echo json_encode($cep,true);