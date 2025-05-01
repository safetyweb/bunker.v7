<?php
include '../../_system/_functionsMain.php';
if($_POST['ID_MARKA']=='' || $_POST['ID_MARKA']=='0')
{
    echo 'OPS Verifique com o administrador do sistema!';
}else{ 
   $COD_EMPRESA=$_POST['ID_MARKA'];  
   $connadmin=$connAdm->connAdm();
   $consultauser='SELECT w.cod_empresa,w.COD_UNIVEND,w.COD_USUARIO,u.LOG_USUARIO,u.DES_SENHAUS from webhook w
                INNER JOIN usuarios u ON u.COD_USUARIO=w.COD_USUARIO 
                WHERE w.cod_empresa='.$COD_EMPRESA.' AND w.TIP_WEBHOOK="5"'; 
   $var_ws=mysqli_fetch_assoc(mysqli_query($connadmin, $consultauser));
    
   $COD_UNIVEND=$var_ws['COD_UNIVEND'];
   $COD_USUARIO= $var_ws['COD_USUARIO'];
   $NOM_USUARIO=$var_ws['LOG_USUARIO'];
   $DES_SENHAUS=fnDecode($var_ws['DES_SENHAUS']);
   
  $valor_venda=$_POST['VL_TOTALVENDA'];
  $CPF=$_POST['markaCPF']; 
  $conntmp= connTemp($COD_EMPRESA, '');
}    


//==============consulta============== 
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => "http://soap.bunker.mk",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => "<soapenv:Envelope xmlns:soapenv='http://schemas.xmlsoap.org/soap/envelope/' xmlns:fid='fidelidade'>
                                <soapenv:Header/>
                                <soapenv:Body>
                                   <fid:BuscaConsumidor>
                                       <fase>fase1</fase>
                                      <opcoesbuscaconsumidor>
                                          <cartao>$CPF</cartao>
                                          <cpf>$CPF</cpf>            
                                      </opcoesbuscaconsumidor>
                                            <dadoslogin>
                                                     <login>$NOM_USUARIO</login>
                                                     <senha>$DES_SENHAUS</senha>
                                                     <idloja>$COD_UNIVEND</idloja>
                                                     <idmaquina>ONLINE</idmaquina>
                                                     <idcliente>$COD_EMPRESA</idcliente>
                                                     <codvendedor>ONLINE</codvendedor>
                                                     <nomevendedor>ONLINE</nomevendedor>
                                               </dadoslogin>
                                   </fid:BuscaConsumidor>
                                </soapenv:Body>
                             </soapenv:Envelope>",
          CURLOPT_HTTPHEADER => array(
            "Content-Type: text/xml",
            "Postman-Token: 01337a55-f74f-4e2c-a974-6fa92035ef8d",
            "cache-control: no-cache"
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
         
        } else { 
          $doc = new DOMDocument();
          libxml_use_internal_errors(true);
          $doc->loadHTML($response);
          libxml_clear_errors();
          $xml = $doc->saveXML($doc->documentElement);       
          $xml = simplexml_load_string($xml,'SimpleXMLElement',LIBXML_NOCDATA);         
          $SALDO = $xml->body->envelope->body->buscaconsumidorresponse->buscaconsumidorresponse->acao_h_saldo->saldodisponivel;
            
          //==============Valida descontos
            $curl1 = curl_init();

        curl_setopt_array($curl1, array(
          CURLOPT_URL => "http://soap.bunker.mk",
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
                                          <cpfcnpj>$CPF</cpfcnpj>
                                          <cartao>$CPF</cartao>
                                          <valortotalliquido>".rtrim(trim($valor_venda))."</valortotalliquido>
                                          <valor_resgate>$SALDO</valor_resgate>
                                         <dadoslogin>
                                                <login>$NOM_USUARIO</login>
                                                <senha>$DES_SENHAUS</senha>
                                                <idloja>$COD_UNIVEND</idloja>
                                                <idmaquina>ONLINE</idmaquina>
                                                <idcliente>$COD_EMPRESA</idcliente>
                                                <codvendedor>ONLINE</codvendedor>
                                                <nomevendedor>ONLINE</nomevendedor>
                                          </dadoslogin>
                                       </fid:ValidaDescontos>
                                    </soapenv:Body>
                                 </soapenv:Envelope>",
          CURLOPT_HTTPHEADER => array(
            "Content-Type: text/xml",
            "Postman-Token: 01337a55-f74f-4e2c-a974-6fa92035ef8d",
            "cache-control: no-cache"
          ),
        ));

        $response1 = curl_exec($curl1);
        $err1 = curl_error($curl1);

        curl_close($curl1);

        if ($err1) {
         
        } else { 
          $doc = new DOMDocument();
          libxml_use_internal_errors(true);
          $doc->loadHTML($response1);
          libxml_clear_errors();
          $xml1 = $doc->saveXML($doc->documentElement);       
          $xml1 = simplexml_load_string($xml1,'SimpleXMLElement',LIBXML_NOCDATA);   
          
           $SALDOerro=$xml1->body->envelope->body->validadescontosresponse->validadescontos->coderro;  
         
           if($SALDOerro!='52')
           {
                
                if($SALDOerro!='50')
                {
                  $maximoresgate=$xml1->body->envelope->body->validadescontosresponse->validadescontos->maximoresgate;
                   //inserir o saldo resgate para retorno fbits
                       //verificar se ja foin inserido uma consulta para resgate.

                       $verificaresgate="SELECT * FROM log_integration_resgate WHERE CPF_CLIENTE='".$CPF."' and DAT_CADASTR =CURDATE()  AND COD_INSERT='1'";
                       $rwresgate=mysqli_query($conntmp, $verificaresgate);
                       if(mysqli_num_rows($rwresgate) > 0)
                       {   
                           while ($rsresgate= mysqli_fetch_assoc($rwresgate)) 
                           {        
                                //verificar se ja existe carrinho para resgate
                                 if($rsresgate['COD_INSERT']!='1' || $rsresgate['COD_INSERT']=='')
                                 {
                                    $insertVLRESGATE=" INSERT INTO log_integration_resgate (DAT_CADASTR,
                                                                                             CPF_CLIENTE,
                                                                                             VL_RESGATE,
                                                                                             COD_INSERT,
                                                                                             STATUS_PEDIDO,
                                                                                             COD_EMPRESA) 
                                                                                      VALUES (
                                                                                              CURDATE() ,
                                                                                              '$CPF',
                                                                                              '".fnValorSQL($maximoresgate)."',
                                                                                              1,
                                                                                              1,
                                                                                              $COD_EMPRESA);";
                                   mysqli_query($conntmp, $insertVLRESGATE);

                                 }else{
                                    //echo 'NOK insert';
                                    $UPVLRESGATE="UPDATE log_integration_resgate SET VL_RESGATE='".fnValorSQL($maximoresgate)."' 
                                                  WHERE DAT_CADASTR =CURDATE()  AND COD_INSERT='1' AND CPF_CLIENTE='$CPF'";
                                    mysqli_query($conntmp, $UPVLRESGATE);         
                                 }
                           }
                        }else{
                           $insertVLRESGATE=" INSERT INTO log_integration_resgate (DAT_CADASTR,
                                                                                             CPF_CLIENTE,
                                                                                             VL_RESGATE,
                                                                                             COD_INSERT,
                                                                                             STATUS_PEDIDO,
                                                                                             COD_EMPRESA) 
                                                                                      VALUES (
                                                                                              CURDATE() ,
                                                                                              '$CPF',
                                                                                              '".fnValorSQL($maximoresgate)."',
                                                                                              1,
                                                                                              1,
                                                                                              $COD_EMPRESA);";
                                   mysqli_query($conntmp, $insertVLRESGATE);
                        }    

                }else{
                 $maximoresgate=$xml1->body->envelope->body->validadescontosresponse->validadescontos->msgerro;
                       
                }    
                echo $maximoresgate;
                
           }else{
               
                $msgerro= $xml1->body->envelope->body->validadescontosresponse->validadescontos->msgerro;
                echo $msgerro;
                
            }
           
      
        
          //===========================
        }
    }
    
    unset($_POST);                                          
                                           
?>