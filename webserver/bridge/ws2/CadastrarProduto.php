<?php

function CadastrarProduto($DADOSLOGIN) {
    include '../../func/function.php';

          $xml_enviado= fnAcentos(file_get_contents("php://input"));      
          $nova_string = preg_replace(array("/(´|#|%|º|¨|ª)/","/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/"),explode(" ","a A e E i I o O u U n N"),$xml_enviado);
     
          $curl = curl_init();
          curl_setopt_array($curl, array(
          CURLOPT_URL => "http://ws.bunker.mk/?wsdl=",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "utf-8",
          CURLOPT_MAXREDIRS => 1000,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => $nova_string,
          CURLOPT_HTTPHEADER => array(
              "content-type:text/xml; charset=utf-8",
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
          $msg= "cURL Error #:" . $err;
          $arraycpf= array('msg' => $msg);
        } 
           $doc = new DOMDocument();
            libxml_use_internal_errors(true);
            $doc->loadHTML($response);
            libxml_clear_errors();
            $xml = $doc->saveXML($doc->documentElement);
            $xml = simplexml_load_string($xml,'SimpleXMLElement',LIBXML_NOCDATA);
            $array = json_decode(json_encode($xml), TRUE);
    return array('CadastrarProdutoResult'=>array('msgerro'=>$array[body][envelope][body][cadastrarprodutoresponse][cadastrarprodutoresult][msgerro],
                                                 'codigo'=>$array[body][envelope][body][cadastrarprodutoresponse][cadastrarprodutoresult][codigo]));
}