<?php
function inserirvenda($arraydadoscad,$dadoslogin,$itensxml)
{
    $token = "";
    if($arraydadoscad['token_resgate'] != ""){
      $token = "<token_resgate>".$arraydadoscad['token_resgate']."</token_resgate>";
    }
    $curl = curl_init();
          curl_setopt_array($curl, array(
          CURLOPT_URL => "http://soap.bunker.mk/?wsdl=",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "UTF-8",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => "<soapenv:Envelope xmlns:soapenv='http://schemas.xmlsoap.org/soap/envelope/' xmlns:fid='fidelidade'>
									<soapenv:Header/>
									<soapenv:Body>
										<fid:InsereVenda>
											<fase>fase1</fase>
											<venda>
												   <id_vendapdv>".$arraydadoscad['id_vendapdv']."</id_vendapdv>
													<datahora>".$arraydadoscad['datahora']."</datahora>
													<cartao>".$arraydadoscad['cartao']."</cartao>
													<valortotalbruto>".$arraydadoscad['valortotalbruto']."</valortotalbruto>
													<descontototalvalor>".$arraydadoscad['descontototalvalor']."</descontototalvalor>
													<valortotalliquido>".$arraydadoscad['valortotalliquido']."</valortotalliquido>
													<valor_resgate>".$arraydadoscad['valor_resgate']."</valor_resgate>
													<cupomfiscal>".$arraydadoscad['cupomfiscal']."</cupomfiscal>
													<formapagamento>".$arraydadoscad['formapagamento']."</formapagamento>
													<pontostotal>".$arraydadoscad['pontostotal']."</pontostotal>
													<codatendente>".$arraydadoscad['codatendente']."</codatendente>
													<codvendedor>".$arraydadoscad['codvendedor']."</codvendedor>
													".$token."
												<itens>
													  $itensxml
												</itens>
											</venda>
											 <dadosLogin>
												<login>".$dadoslogin['0']."</login>
												<senha>".$dadoslogin['1']."</senha>
												<idloja>".$dadoslogin['2']."</idloja>
												<idmaquina>".$dadoslogin['3']."</idmaquina>
												<idcliente>".$dadoslogin['4']."</idcliente>
												<codvendedor>".$dadoslogin['5']."</codvendedor>
												<nomevendedor>".$dadoslogin['6']."</nomevendedor>
											 </dadosLogin>
										</fid:InsereVenda>
									</soapenv:Body>
								</soapenv:Envelope> ",
          CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
            "content-type: text/xml",
            "postman-token: e16a9d3f-b132-df60-92e0-5f9c923a0baf"
          ),
        ));
          $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
         return ;
        } else {
         
          $doc = new DOMDocument();
          libxml_use_internal_errors(true);
          $doc->loadHTML($response);
          libxml_clear_errors();
          $xml = $doc->saveXML($doc->documentElement);
          $xml = simplexml_load_string($xml);
          $msg= $xml->body->envelope->body->inserevendaresponse->inserevendaresponse->msgerro.'||'.$xml->body->envelope->body->inserevendaresponse->inserevendaresponse->acao_h_saldo->creditovenda;
          $msg = explode("||", $msg);
		  return  $msg;
          		  
        }
}
function validadescontos($arraydadoscad,$dadoslogin)
{
        $curl = curl_init();

      curl_setopt_array($curl, array(
        CURLOPT_URL => "http://soap.bunker.mk/?wsdl=",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "<soapenv:Envelope xmlns:soapenv='http://schemas.xmlsoap.org/soap/envelope/' xmlns:fid='fidelidade'>
                             <soapenv:Header/>
                               <soapenv:Body>  
                                   <fid:ValidaDescontos>
                                       <cpfcnpj>".$arraydadoscad['cartao']."</cpfcnpj>
                                       <cartao>".$arraydadoscad['cartao']."</cartao>         
                                       <valortotalliquido>".$arraydadoscad['valortotalliquido']."</valortotalliquido>
                                       <valor_resgate>".$arraydadoscad['valor_resgate']."</valor_resgate>
                                            <dadosLogin>
                                                <login>".$dadoslogin['0']."</login>
                                                <senha>".$dadoslogin['1']."</senha>
                                                <idloja>".$dadoslogin['2']."</idloja>
                                                <idmaquina>".$dadoslogin['3']."</idmaquina>
                                                <idcliente>".$dadoslogin['4']."</idcliente>
                                                <codvendedor>".$dadoslogin['5']."</codvendedor>
                                                <nomevendedor>".$dadoslogin['6']."</nomevendedor>
                                             </dadosLogin>
                                    </fid:ValidaDescontos>
                                 </soapenv:Body>
                              </soapenv:Envelope>",
        CURLOPT_HTTPHEADER => array(
          "Cache-Control: no-cache",
          "Content-Type: text/xml",
          "Postman-Token: eefcad19-c2fe-2b6a-a1e4-1a70c59b27c5"
        ),
      ));

      $response = curl_exec($curl);
      $err = curl_error($curl);

      curl_close($curl);

      if ($err) {
        echo "cURL Error #:" . $err;
      } else {
         $doc = new DOMDocument();
          libxml_use_internal_errors(true);
          $doc->loadHTML($response);
          libxml_clear_errors();
          $xml = $doc->saveXML($doc->documentElement);
          $xml = simplexml_load_string($xml);
          $minimoresgate= $xml->body->envelope->body->validadescontosresponse->validadescontos->minimoresgate;
          $msgerro= $xml->body->envelope->body->validadescontosresponse->validadescontos->msgerro;
          $coderro= $xml->body->envelope->body->validadescontosresponse->validadescontos->coderro;
          return array('minimoresgate'=>$minimoresgate,
                       'msgerro'=>$msgerro,
                       'coderro'=>$coderro,
                       );    
      }  
}