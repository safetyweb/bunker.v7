<?php
exit();
include '../../_system/Class_conn.php';
///$xmljson=file_get_contents("php://input");
//$array=json_decode($row['TEXTO'],true);

$xmlteste=addslashes(file_get_contents("php://input"));
$conn=$connAdm->connAdm();

       

    $ins='insert INTO LOG_TRINKS (TEXTO) VALUE("'.$xmlteste.'")';
    mysqli_query($conn, $ins);
    $COD_log= mysqli_insert_id($conn);

//$busca='select * from LOG_TRINKS';
$busca='select * from LOG_TRINKS where ID='.$COD_log;
//$busca='select * from LOG_TRINKS where  pdv =""';
$rw=mysqli_query($conn, $busca);
while ($row = mysqli_fetch_assoc($rw)) {
    //array total
    $array=json_decode($row['TEXTO'],true); 
   //=====fim ================================
    $array2=json_decode($array['Message'],true);
      $data = str_replace("/", "-",$array2['DataDoFechamento']);
      $strcount= "'".date('d/m/Y H:i:s', strtotime($data))."'";
      $strcount = str_replace("'", "",$strcount);
      
   $ValorDaCompra= str_replace(",", ".", $array2['ValorDaCompra']);
   $ValorDaCompra=number_format ($ValorDaCompra,2,",",".");
   $ValorDaCompra=str_replace(".", "", $ValorDaCompra);
    $codunivendxml="SELECT COD_UNIVEND FROM unidadevenda WHERE cod_empresa=39 AND cod_externo='".$array2 ['IdDoEstabelecimento']."'";
   $rs_univend= mysqli_fetch_assoc(mysqli_query($conn, $codunivendxml));
   
  
   //verificando o cpf 
        //

    if($array2['TipoDeEvento']==1)
    {   
        if($array2['CPFDoCliente']!='')
        {   
   
        if($array2['DataDeNascimentoDoCliente']=='')
        {
         $DataDeNascimentoDoCliente='1969-12-12 00:00:00' ;  
        }else
        {
            $DataDeNascimentoDoCliente=$array2['DataDeNascimentoDoCliente'];
        }    
         
        if($array2['DataDeNascimentoDoCliente']!='')
        {    //inserir cadastro
             $datahora=DateTime::createFromFormat('Y-m-d h:i:s', $DataDeNascimentoDoCliente);
              $DataDeNascimentoDoCliente=$datahora->format('Y-m-d');
 
                $curlatualiza = curl_init();
                curl_setopt($curlatualiza, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt_array($curlatualiza, array(
                  CURLOPT_URL => "http://ws.bunker.mk/?wsdl=",
                  CURLOPT_RETURNTRANSFER => true,
                 // CURLOPT_ENCODING => "utf-8",
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 30,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => "POST",
                  CURLOPT_POSTFIELDS => "<SOAP-ENV:Envelope xmlns:SOAP-ENV=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\">
                                      <SOAP-ENV:Body>
                                      <AtualizaCadastro xmlns=\"Linker20\">
                                          <cliente xmlns=\"\">
                                                   <cartao xmlns=\"Linker20\">".$array2['CPFDoCliente']."</cartao>
                                                   <tipocliente>PF</tipocliente>    
                                                   <nome xmlns=\"Linker20\">".$array2['NomeDoCliente']."</nome>
                                                   <cpf xmlns=\"Linker20\">".$array2['CPFDoCliente']."</cpf>
                                                   <sexo xmlns=\"Linker20\">".$array2['SexoDoCliente']."</sexo>
                                                   <datanascimento xmlns=\"Linker20\">".$DataDeNascimentoDoCliente."</datanascimento>
                                                   <email xmlns=\"Linker20\">".$array2['EmailDoCliente']."</email>
                                                   <telcelular xmlns=\"Linker20\">".$array2['TelefoneDoCliente']['0']['TelefoneCompleto']."</telcelular>
                                                   <senha>marka123456</senha>    
                                            </cliente>
                                             <dadoslogin>
                                                    <login>opwsh.depyl</login>
                                                    <senha>depyl</senha>
                                                    <idloja>".$rs_univend['COD_UNIVEND']."</idloja>
                                                    <idmaquina>trinks</idmaquina>
                                                    <idcliente>39</idcliente>
                                                    <codvendedor>??</codvendedor>
                                                    <nomevendedor>??</nomevendedor>
                                              </dadoslogin>
                                          </AtualizaCadastro>
                                         </SOAP-ENV:Body>
                                       </SOAP-ENV:Envelope>",
                  CURLOPT_HTTPHEADER => array(
                    "cache-control: no-cache",
                    "content-type: text/xml",
                    "postman-token: bbd5f12a-e4ca-8d50-45a5-8e67f84eae5f"
                  ),
                ));

                $responseAtualiza = curl_exec($curlatualiza);
                $err = curl_error($curlatualiza);
                curl_close($curlatualiza);
                if ($err) 
                {
                  echo "cURL Error #:" . $err;
                } else {
                  $doc = new DOMDocument();
                  libxml_use_internal_errors(true);
                  $doc->loadHTML($responseAtualiza);
                  libxml_clear_errors();
                  $xml = $doc->saveXML($doc->documentElement);
                  //$xml = simplexml_load_string($xml);
                  $xml = simplexml_load_string($xml,'SimpleXMLElement',LIBXML_NOCDATA);
                  $msgerro=$xml->body->envelope->body->atualizacadastroresponse->atualizacadastroresult->msgerro;
                  echo $msgerro;
                }
            }    
          //iniciar a venda 
         // se cadastro For OK iniciar a venda .
               if($msgerro=='OK')
               {
                   unset($vendaitem); 
                   //looping de itens
                   foreach ($array2['Itens'] as $cod_chave => $valor) 
                   {
                       echo '<pre>';
                       print_r($valor);
                       print_r($cod_chave);
                       echo '</pre>';
                            $vendaitem.=   '<ns1:vendaitem>
                                                <ns1:id_item>'.$valor['Id'].'</ns1:id_item>
                                                <ns1:produto>'.$valor['Nome'].'</ns1:produto>
                                                <ns1:codigoproduto>'.$valor['Id'].'</ns1:codigoproduto>
                                                <ns1:quantidade>'.$valor['Quantidade'].'</ns1:quantidade>
                                                <ns1:valor>'.number_format ($valor['ValorUnitario'],2,",",".").'</ns1:valor>
                                            </ns1:vendaitem>';
                        if( $valor['MotivoDesconto'] == 'Bee Happy')
                        { 
                            $Beedesconto=str_replace('-', '',$valor['DescontoTotal']);
                            $somaresgate1= (float) ($somaresgate1+$Beedesconto);
                            $somaresgate= number_format ($somaresgate1,2,",",".");
                            
                        }
                   }        
                  echo $array2['IdDaTransacao'].'='.$somaresgate.'<br>';
                   
            
                    //inserir cadastro
                  //  [DataDoFechamento] => 2019-05-31 17:59:58
                    // $datahoravenda=DateTime::createFromFormat('Y-m-d h:i:s', $array2['DataDoFechamento']);
                    // echo 'OK entrou';
                    //  $Dtvenda=$datahoravenda->format('d/m/Y H:i:s');
                        //venda
                        $curlvenda = curl_init();
                        curl_setopt($curlvenda, CURLOPT_SSL_VERIFYPEER, false);
                        curl_setopt_array($curlvenda, array(
                        CURLOPT_URL => "http://ws.bunker.mk/?wsdl=",
                        CURLOPT_RETURNTRANSFER => true,
                      // CURLOPT_ENCODING => "utf-8",
                       CURLOPT_MAXREDIRS => 10,
                       CURLOPT_TIMEOUT => 30,
                       CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                       CURLOPT_CUSTOMREQUEST => "POST",
                       CURLOPT_POSTFIELDS => '<?xml version="1.0" encoding="UTF-8"?>
                                             <SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ns1="Linker20">
                                                 <SOAP-ENV:Body>
                                                     <ns1:InserirVenda>
                                                         <venda>
                                                             <ns1:id_vendapdv>'.$array2['IdDaTransacao'].'</ns1:id_vendapdv>
                                                             <ns1:datahora>'.$strcount.'</ns1:datahora>
                                                             <ns1:cartao>'.$array2['CPFDoCliente'].'</ns1:cartao>
                                                             <ns1:valortotal>'.$ValorDaCompra.'</ns1:valortotal>
                                                             <ns1:valor_resgate>'.$somaresgate.'</ns1:valor_resgate>
                                                             <ns1:cupom>'.$array2['IdDaTransacao'].'</ns1:cupom>
                                                             <ns1:formapagamento>trinks</ns1:formapagamento>
                                                             <ns1:codatendente>'.$array2['0']['IdDoProfissional'].'</ns1:codatendente>
                                                             <ns1:codvendedor>'.$array2['0']['IdDoProfissionalNoEstabelecimento'].'</ns1:codvendedor>
                                                             <ns1:items>
                                                                '.$vendaitem.'
                                                             </ns1:items>
                                                         </venda>
                                                       <dadoslogin>
                                                            <login>opwsh.depyl</login>
                                                            <senha>depyl</senha>
                                                            <idloja>'.$rs_univend['COD_UNIVEND'].'</idloja>
                                                            <idmaquina>trinks</idmaquina>
                                                            <idcliente>39</idcliente>
                                                            <codvendedor>??</codvendedor>
                                                            <nomevendedor>??</nomevendedor>
                                                      </dadoslogin>
                                                     </ns1:InserirVenda>
                                                 </SOAP-ENV:Body>
                                             </SOAP-ENV:Envelope>',
                       CURLOPT_HTTPHEADER => array(
                         "cache-control: no-cache",
                         "content-type: text/xml",
                         "postman-token: bbd5f12a-e4ca-8d50-45a5-8e67f84eae5f"
                       ),
                     ));

                     $responsevenda = curl_exec($curlvenda);
                     $err = curl_error($responsevenda);
                     curl_close($curlvenda);
                     if ($err) {
                       echo "cURL Error #:" . $err;
                     } else {
                       $doc = new DOMDocument();
                       libxml_use_internal_errors(true);
                       $doc->loadHTML($responsevenda);
                       libxml_clear_errors();
                       $xml = $doc->saveXML($doc->documentElement);
                       //$xml = simplexml_load_string($xml);
                       $xml = simplexml_load_string($xml,'SimpleXMLElement',LIBXML_NOCDATA);
                       echo '<pre>';
                       print_r($xml);
                       echo '</pre>';
                       //$msgerro=$xml->body->envelope->body->atualizacadastroresponse->atualizacadastroresult->msgerro;
                      // echo $msgerro;
                }
                   //
                $update1="UPDATE log_trinks SET PDV='".$array2['IdDaTransacao']."',CPF='".$array2['CPFDoCliente']."' WHERE  ID=".$row['ID']; 
                mysqli_query($conn, $update1) ;
               }else{
//looping de itens
                   foreach ($array2['Itens'] as $cod_chave => $valor) 
                   {
                         unset($vendaitem); 
                       echo '<pre>';
                       print_r($valor);
                       print_r($cod_chave);
                       echo '</pre>';
                            $vendaitem.=   '<ns1:vendaitem>
                                                <ns1:id_item>'.$valor['Id'].'</ns1:id_item>
                                                <ns1:produto>'.$valor['Nome'].'</ns1:produto>
                                                <ns1:codigoproduto>'.$valor['Id'].'</ns1:codigoproduto>
                                                <ns1:quantidade>'.$valor['Quantidade'].'</ns1:quantidade>
                                                <ns1:valor>'.number_format ($valor['ValorUnitario'],2,",",".").'</ns1:valor>
                                            </ns1:vendaitem>';
                        if( $valor['MotivoDesconto'] == 'Bee Happy')
                        { 
                            $Beedesconto=str_replace('-', '',$valor['DescontoTotal']);
                            $somaresgate1= (float) ($somaresgate1+$Beedesconto);
                            $somaresgate= number_format ($somaresgate1,2,",",".");
                            
                        }
                   }        
                  echo $array2['IdDaTransacao'].'='.$somaresgate.'<br>';
                   
            
                    //inserir cadastro
                  //  [DataDoFechamento] => 2019-05-31 17:59:58
                    // $datahoravenda=DateTime::createFromFormat('Y-m-d h:i:s', $array2['DataDoFechamento']);
                    // echo 'OK entrou';
                    //  $Dtvenda=$datahoravenda->format('d/m/Y H:i:s');
                        //venda
                        $curlvenda = curl_init();
                        curl_setopt($curlvenda, CURLOPT_SSL_VERIFYPEER, false);
                        curl_setopt_array($curlvenda, array(
                        CURLOPT_URL => "http://ws.bunker.mk/?wsdl=",
                        CURLOPT_RETURNTRANSFER => true,
                      // CURLOPT_ENCODING => "utf-8",
                       CURLOPT_MAXREDIRS => 10,
                       CURLOPT_TIMEOUT => 30,
                       CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                       CURLOPT_CUSTOMREQUEST => "POST",
                       CURLOPT_POSTFIELDS => '<?xml version="1.0" encoding="UTF-8"?>
                                             <SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ns1="Linker20">
                                                 <SOAP-ENV:Body>
                                                     <ns1:InserirVenda>
                                                         <venda>
                                                             <ns1:id_vendapdv>'.$array2['IdDaTransacao'].'</ns1:id_vendapdv>
                                                             <ns1:datahora>'.$strcount.'</ns1:datahora>
                                                             <ns1:cartao>'.$array2['CPFDoCliente'].'</ns1:cartao>
                                                             <ns1:valortotal>'.$ValorDaCompra.'</ns1:valortotal>
                                                             <ns1:valor_resgate>'.$somaresgate.'</ns1:valor_resgate>
                                                             <ns1:cupom>'.$array2['IdDaTransacao'].'</ns1:cupom>
                                                             <ns1:formapagamento>trinks</ns1:formapagamento>
                                                             <ns1:codatendente>'.$array2['0']['IdDoProfissional'].'</ns1:codatendente>
                                                             <ns1:codvendedor>'.$array2['0']['IdDoProfissionalNoEstabelecimento'].'</ns1:codvendedor>
                                                             <ns1:items>
                                                                '.$vendaitem.'
                                                             </ns1:items>
                                                         </venda>
                                                       <dadoslogin>
                                                            <login>opwsh.depyl</login>
                                                            <senha>depyl</senha>
                                                            <idloja>'.$rs_univend['COD_UNIVEND'].'</idloja>
                                                            <idmaquina>trinks</idmaquina>
                                                            <idcliente>39</idcliente>
                                                            <codvendedor>??</codvendedor>
                                                            <nomevendedor>??</nomevendedor>
                                                      </dadoslogin>
                                                     </ns1:InserirVenda>
                                                 </SOAP-ENV:Body>
                                             </SOAP-ENV:Envelope>',
                       CURLOPT_HTTPHEADER => array(
                         "cache-control: no-cache",
                         "content-type: text/xml",
                         "postman-token: bbd5f12a-e4ca-8d50-45a5-8e67f84eae5f"
                       ),
                     ));

                     $responsevenda = curl_exec($curlvenda);
                     $err = curl_error($responsevenda);
                     curl_close($curlvenda);
                     if ($err) {
                       echo "cURL Error #:" . $err;
                     } else {
                       $doc = new DOMDocument();
                       libxml_use_internal_errors(true);
                       $doc->loadHTML($responsevenda);
                       libxml_clear_errors();
                       $xml = $doc->saveXML($doc->documentElement);
                       //$xml = simplexml_load_string($xml);
                       $xml = simplexml_load_string($xml,'SimpleXMLElement',LIBXML_NOCDATA);
                       echo '<pre>';
                       print_r($xml);
                       echo '</pre>';
                       //$msgerro=$xml->body->envelope->body->atualizacadastroresponse->atualizacadastroresult->msgerro;
                      // echo $msgerro;
                     }   
               }
                $update1="UPDATE log_trinks SET PDV='".$array2['IdDaTransacao']."',CPF='".$array2['CPFDoCliente']."' WHERE  ID=".$row['ID']; 
                mysqli_query($conn, $update1) ;
        }else{
          //venda avulsa
        }   
    }elseif ($array2['TipoDeEvento']=='4') {

            //SO CADASTRO

            //inserir cadastro
            if($array2['DataDeNascimento']=='')
            {
             $DataDeNascimento='01/12/1969' ;  
            }else{
                $DataDeNascimento=$array2['DataDeNascimento'];
            }
                     $datahora=DateTime::createFromFormat('d/m/Y', $DataDeNascimento);
                     $DataDeNascimentoDoCliente=$datahora->format('Y-m-d');

                       $curlatualiza = curl_init();
                        curl_setopt($curlatualiza, CURLOPT_SSL_VERIFYPEER, false);
                        curl_setopt_array($curlatualiza, array(
                          CURLOPT_URL => "http://ws.bunker.mk/?wsdl=",
                          CURLOPT_RETURNTRANSFER => true,
                         // CURLOPT_ENCODING => "utf-8",
                          CURLOPT_MAXREDIRS => 10,
                          CURLOPT_TIMEOUT => 30,
                          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                          CURLOPT_CUSTOMREQUEST => "POST",
                          CURLOPT_POSTFIELDS => "<SOAP-ENV:Envelope xmlns:SOAP-ENV=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\">
                                              <SOAP-ENV:Body>
                                              <AtualizaCadastro xmlns=\"Linker20\">
                                                  <cliente xmlns=\"\">
                                                           <cartao xmlns=\"Linker20\">".$array2['CPF']."</cartao>
                                                           <tipocliente>PF</tipocliente>    
                                                           <nome xmlns=\"Linker20\">".$array2['Nome']."</nome>
                                                           <cpf xmlns=\"Linker20\">".$array2['CPF']."</cpf>
                                                           <sexo xmlns=\"Linker20\">".$array2['Sexo']."</sexo>
                                                           <datanascimento xmlns=\"Linker20\">".$DataDeNascimentoDoCliente."</datanascimento>
                                                           <email xmlns=\"Linker20\">".RTRIM(TRIM($array2['Email']))."</email>
                                                           <telcelular xmlns=\"Linker20\">".$array2['Telefone']['0']['TelefoneCompleto']."</telcelular>
                                                           <senha>marka123456</senha>    
                                                    </cliente>
                                                      <dadoslogin>
                                                            <login>opwsh.depyl</login>
                                                            <senha>depyl</senha>
                                                            <idloja>".$rs_univend['COD_UNIVEND']."</idloja>
                                                            <idmaquina>trinks</idmaquina>
                                                            <idcliente>39</idcliente>
                                                            <codvendedor>??</codvendedor>
                                                            <nomevendedor>??</nomevendedor>
                                                      </dadoslogin>
                                                  </AtualizaCadastro>
                                                 </SOAP-ENV:Body>
                                               </SOAP-ENV:Envelope>",
                          CURLOPT_HTTPHEADER => array(
                            "cache-control: no-cache",
                            "content-type: text/xml",
                            "postman-token: bbd5f12a-e4ca-8d50-45a5-8e67f84eae5f"
                          ),
                        ));

                        $responseAtualiza = curl_exec($curlatualiza);
                        $err = curl_error($curlatualiza);
                        curl_close($curlatualiza);
                        if ($err) 
                        {
                          echo "cURL Error #:" . $err;
                        } else 
                        {
                          $doc = new DOMDocument();
                          libxml_use_internal_errors(true);
                          $doc->loadHTML($responseAtualiza);
                          libxml_clear_errors();
                          $xml = $doc->saveXML($doc->documentElement);
                          //$xml = simplexml_load_string($xml);
                          $xml = simplexml_load_string($xml,'SimpleXMLElement',LIBXML_NOCDATA);
                          $msgerro=$xml->body->envelope->body->atualizacadastroresponse->atualizacadastroresult->msgerro;


                        } 
        }elseif ($array2['TipoDeEvento']=='3') {
            //SO CADASTRO

            //inserir cadastro
            if($array2['DataDeNascimento']=='')
            {
             $DataDeNascimento='01/12/1969' ;  
            }else{
                $DataDeNascimento=$array2['DataDeNascimento'];
            }
                

                     $datahora=DateTime::createFromFormat('d/m/Y', $DataDeNascimento);
                     $DataDeNascimentoDoCliente=$datahora->format('Y-m-d');

                       $curlatualiza = curl_init();
                        curl_setopt($curlatualiza, CURLOPT_SSL_VERIFYPEER, false);
                        curl_setopt_array($curlatualiza, array(
                          CURLOPT_URL => "http://ws.bunker.mk/?wsdl=",
                          CURLOPT_RETURNTRANSFER => true,
                         // CURLOPT_ENCODING => "utf-8",
                          CURLOPT_MAXREDIRS => 10,
                          CURLOPT_TIMEOUT => 30,
                          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                          CURLOPT_CUSTOMREQUEST => "POST",
                          CURLOPT_POSTFIELDS => "<SOAP-ENV:Envelope xmlns:SOAP-ENV=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\">
                                              <SOAP-ENV:Body>
                                              <AtualizaCadastro xmlns=\"Linker20\">
                                                  <cliente xmlns=\"\">
                                                           <cartao xmlns=\"Linker20\">".$array2['CPF']."</cartao>
                                                           <tipocliente>PF</tipocliente>    
                                                           <nome xmlns=\"Linker20\">".$array2['Nome']."</nome>
                                                           <cpf xmlns=\"Linker20\">".$array2['CPF']."</cpf>
                                                           <sexo xmlns=\"Linker20\">".$array2['Sexo']."</sexo>
                                                           <datanascimento xmlns=\"Linker20\">".$DataDeNascimentoDoCliente."</datanascimento>
                                                           <email xmlns=\"Linker20\">".RTRIM(TRIM($array2['Email']))."</email>
                                                           <telcelular xmlns=\"Linker20\">".$array2['Telefone']['0']['TelefoneCompleto']."</telcelular>
                                                           <senha>marka123456</senha>    
                                                    </cliente>
                                                      <dadoslogin>
                                                            <login>opwsh.depyl</login>
                                                            <senha>depyl</senha>
                                                            <idloja>".$rs_univend['COD_UNIVEND']."</idloja>
                                                            <idmaquina>trinks</idmaquina>
                                                            <idcliente>39</idcliente>
                                                            <codvendedor>??</codvendedor>
                                                            <nomevendedor>??</nomevendedor>
                                                      </dadoslogin>
                                                  </AtualizaCadastro>
                                                 </SOAP-ENV:Body>
                                               </SOAP-ENV:Envelope>",
                          CURLOPT_HTTPHEADER => array(
                            "cache-control: no-cache",
                            "content-type: text/xml",
                            "postman-token: bbd5f12a-e4ca-8d50-45a5-8e67f84eae5f"
                          ),
                        ));

                        $responseAtualiza = curl_exec($curlatualiza);
                        $err = curl_error($curlatualiza);
                        curl_close($curlatualiza);
                        if ($err) 
                        {
                          echo "cURL Error #:" . $err;
                        } else 
                        {
                          $doc = new DOMDocument();
                          libxml_use_internal_errors(true);
                          $doc->loadHTML($responseAtualiza);
                          libxml_clear_errors();
                          $xml = $doc->saveXML($doc->documentElement);
                          //$xml = simplexml_load_string($xml);
                          $xml = simplexml_load_string($xml,'SimpleXMLElement',LIBXML_NOCDATA);
                          $msgerro=$xml->body->envelope->body->atualizacadastroresponse->atualizacadastroresult->msgerro;
                        }

        
    } 
} 

/*
  echo'<pre>';
   print_r($array2);
   echo'</pre>';
 * 
 */