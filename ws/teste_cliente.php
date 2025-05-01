<?php
for($i=10000;$i<=20000;$i++){
$curl = curl_init();


curl_setopt_array($curl, array(
  CURLOPT_URL => "http://149.56.22.17/webserver/fidelidade.php?wsdl=",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "<soapenv:Envelope xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" 
                         xmlns:urn=\"urn:server\">\r\n   
                         <soapenv:Header/>\r\n   
                         <soapenv:Body>\r\n      
                         <urn:InserirVenda>\r\n                 
                         <venda>\r\n            
                           <!--Optional:-->\r\n            
                           <id_vendapdv>".$i."</id_vendapdv>\r\n           
                           <!--Optional:-->\r\n            
                           <datahora>?</datahora>\r\n            
                           <!--Optional:-->\r\n            
                           <cartao>94858993000</cartao>\r\n            
                           <!--Optional:-->\r\n            
                           <valortotal>70,35</valortotal>\r\n            
                           <!--Optional:-->\r\n            
                           <valor_resgate>0,00</valor_resgate>\r\n           
                           <!--Optional:-->\r\n            
                           <cupom>?</cupom>\r\n            
                           <!--Optional:-->\r\n            
                           <formapagamento>1</formapagamento>\r\n            
                           <!--Optional:-->\r\n            
                           <cartaoamigo>?</cartaoamigo>\r\n            
                           <!--Optional:-->\r\n            
                           <pontosextras>?</pontosextras>\r\n            
                           <!--Optional:-->\r\n            
                           <naopontuar>?</naopontuar>\r\n            
                           <!--Optional:-->\r\n            
                           <codatendente>?</codatendente>\r\n            
                           <!--Optional:-->\r\n            
                           <codvendedor>?</codvendedor>\r\n           
                           <!--Optional:-->\r\n           
                           <pontostotal>?</pontostotal>\r\n            
                           <!--Optional:-->\r\n            
                            <items>\r\n               
                                        <vendaitem>
                                            <!--Optional:-->
                                            <id_item>5913</id_item>
                                            <!--Optional:-->
                                            <produto>teste</produto>
                                            <!--Optional:-->
                                            <codigoproduto>5913</codigoproduto>
                                            <!--Optional:-->
                                            <quantidade>20,1</quantidade>
                                            <!--Optional:-->
                                            <valor>3,50</valor>
                                            <!--Optional:-->
                                            <naopontuar></naopontuar>
                                        </vendaitem>\r\n             
                             </items>\r\n         
                           </venda>\r\n      
                           <dadosLogin>\r\n           
                           <!--Optional:-->\r\n           
                           <login>diogo</login>\r\n           
                           <!--Optional:-->\r\n           
                           <senha>123456</senha>\r\n            
                           <!--Optional:-->\r\n            
                           <idloja>?</idloja>\r\n           
                           <!--Optional:-->\r\n            
                           <idmaquina>?</idmaquina>\r\n            
                           <!--Optional:-->\r\n            
                           <idcliente>?</idcliente>\r\n            
                           <!--Optional:-->\r\n            
                           <codvendedor>?</codvendedor>\r\n            
                           <!--Optional:-->\r\n            
                           <nomevendedor>?</nomevendedor>\r\n        
                           </dadosLogin>\r\n      
                           </urn:InserirVenda>\r\n   
                           </soapenv:Body>\r\n
                           </soapenv:Envelope>",
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache",
    "content-type: text/xml",
    "postman-token: c94ff1ce-132d-f421-e9f7-fa7a886b10b5"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo $response;
    
}
}
?>

