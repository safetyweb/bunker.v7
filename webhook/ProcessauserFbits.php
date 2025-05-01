<?php
include '../_system/_functionsMain.php';
$connAdm=$connAdm->connAdm();

/*
log_integration_venda
log_integration_user
*/
$COD_EMPRESA=$_GET['id'];
$cod_user=$_GET['user'];

//295755
$conntempo= connTemp($COD_EMPRESA, '');



$sql="SELECT * from WEBHOOK WHERE COD_EMPRESA='$COD_EMPRESA' AND TIP_WEBHOOK=3 AND LOG_ESTATUS='S'"; 
$execute= mysqli_query($connAdm, $sql);

while($result= mysqli_fetch_assoc($execute))
{
    echo '<br>ENTROU NO LOOP.<br>';
    $buscausermarka='select * from usuarios where 
                                            cod_usuario="'.$result['COD_USUARIO'].'" and  
                                            cod_empresa='.$COD_EMPRESA;      
    $rs_usuarios=mysqli_fetch_assoc(mysqli_query($connAdm, $buscausermarka)); 
    if($rs_usuarios['LOG_ESTATUS']=='N')
    {
       echo '<br>Usuario marka Inativo<br>';   
    }    
  
    
    //primeiro passo o cadastro do cliente 
        //inicio da consulta de clientes Fbits 
         // $timeconsulta=urlencode('');
          $curl = curl_init();
            curl_setopt_array($curl, array(
              CURLOPT_URL => "HTTPS://".$result['URL']."/usuarios/usuarioId/$cod_user",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_SSL_VERIFYPEER=> false,  
              CURLOPT_ENCODING => "",
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 30,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => "GET",
              CURLOPT_POSTFIELDS => "",
              CURLOPT_HTTPHEADER => array(
                "Accept: application/json",
                "Content-Type: application/json",  
                "Authorization: ".$result['DES_SENHA']."",
                "Postman-Token: e4de2b69-290f-4689-9213-8374387fa94c",
                "cache-control: no-cache"
              ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
              echo "<br>cURL Error #:" . $err.'<br>';
            } else {
            
                    $arrayList=json_decode(fnAcentos($response), true); 
                     //echo '<pre>';
                    // print_r($arrayList);
                    // echo '</pre>';
                     
                 
                    

                        $checkuser="select COD_EXT_USER from log_integration_user where COD_EXT_USER=".rtrim(trim($arrayList['usuarioId']))." and COD_EMPRESA=$COD_EMPRESA";
                        $rsnum=mysqli_query($conntempo, $checkuser);
                        if(mysqli_num_rows($rsnum)<=0)
                        { 
                            
                            if(fnLimpaDoc($arrayList['cpf']=='')){$CPFCNPJ=$arrayList['CNPJ'];}else{$CPFCNPJ=$arrayList['cpf'];}
                            if($arrayList['grupoInformacaoCadastral'][0]['valor']=='Sim'){$aceite='S';}else{$aceite='N';}
                            
                                //$xamls= addslashes(str_replace(array("\n",""),array(""," "), var_export($response,true)));
                               $xamls= addslashes(json_encode(fnAcentos($arrayList),true));
                                $insertolog="INSERT INTO log_integration_user 
                                                                     (COD_EXT_USER, 
                                                                      COD_EMPRESA,
                                                                      NUM_CGCECPF,
                                                                      DES_VENDA,
                                                                      COD_INSERT,
                                                                      ACEITE) 
                                                                      VALUES 
                                                                      (
                                                                      ".rtrim(trim($arrayList['usuarioId'])).", 
                                                                       ".$COD_EMPRESA.", 
                                                                       '".fnLimpaDoc($CPFCNPJ)."', 
                                                                       '$xamls', 
                                                                       '0',
                                                                       '$aceite'
                                                                       );";                               
                                     mysqli_query($conntempo, $insertolog);

                        } else {
                               /* $xamls= addslashes(json_encode(fnAcentos($arrayList),true));
                               $updatelog="UPDATE log_integration_user SET DES_VENDA='".$xamls."',
                                                                           COD_INSERT='0' 
                                     WHERE COD_EXT_USER=".rtrim(trim($arrayList['usuarioId']))." and
                                           COD_INSERT='1' and
                                           COD_EMPRESA=".$COD_EMPRESA;
                               mysqli_query($conntempo, $updatelog);*/
                        } 
                   
                 if($value=='')
                 {
                   echo '<br>Nao tem cadastros novos<br>';  
                 }    
                    
            }
        //=========================Fim da consulta fbits==================================
       //Iniciar o cadastramento no marka para fidelizar o cliente.
            $processcliente="select COD_EXT_USER from log_integration_user where  COD_EMPRESA=$COD_EMPRESA and COD_INSERT='0'";
            $rsusuario=mysqli_query($conntempo, $processcliente);
            while ($clientedados=mysqli_fetch_assoc($rsusuario))
            {    
                
                $clientes_insert= json_decode(fnAcentos($clientedados['DES_VENDA']),true); 
               // echo'<pre>';         
              //  print_r($clientes_insert);
              //  echo'</pre>';
                
                
                if($clientedados['ACEITE']=='S')
                {
                    if($clientes_insert['tipoPessoa']=='Fisica'){$tipocliente='PF';}else{$tipocliente='PJ';}
                    $curl1 = curl_init();
                    curl_setopt($curl1, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt_array($curl1, array(
                                                    CURLOPT_URL => "http://ws.bunker.mk/?wsdl=",
                                                    CURLOPT_RETURNTRANSFER => true,
                                                    CURLOPT_ENCODING => "utf-8",
                                                    CURLOPT_MAXREDIRS => 10,
                                                    CURLOPT_TIMEOUT => 30,
                                                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                                    CURLOPT_CUSTOMREQUEST => "POST",
                                                    CURLOPT_POSTFIELDS => "<SOAP-ENV:Envelope xmlns:SOAP-ENV=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\">
                                                                        <SOAP-ENV:Body>
                                                                        <AtualizaCadastro xmlns=\"Linker20\">
                                                                            <cliente xmlns=\"\">
                                                                                     <cartao xmlns=\"Linker20\">".fnLimpaDoc($clientes_insert['cpf'])."</cartao>
                                                                                     <tipocliente>".$tipocliente."</tipocliente>    
                                                                                     <nome xmlns=\"Linker20\">".fnAcentos($clientes_insert['nome'])."</nome>
                                                                                     <cpf xmlns=\"Linker20\">".fnLimpaDoc($clientes_insert['cpf'])."</cpf>
                                                                                     <sexo xmlns=\"Linker20\">".$clientes_insert['tipoSexo']."</sexo>
                                                                                     <datanascimento xmlns=\"Linker20\">".date('Y-m-d', strtotime($clientes_insert['dataNascimento']))."</datanascimento>
                                                                                     <email xmlns=\"Linker20\">".$clientes_insert['email']."</email>
                                                                                     <telcelular xmlns=\"Linker20\">".$clientes_insert['telefoneResidencial']."</telcelular>
                                                                                     <telresidencial xmlns=\"Linker20\">".$clientes_insert['telefoneCelular']."</telresidencial>    
                                                                                     <senha>123456</senha>    
                                                                              </cliente>
                                                                             <dadosLogin xmlns=\"\">
                                                                              <login xmlns=\"Linker20\">".$rs_usuarios['LOG_USUARIO']."</login>
                                                                               <senha xmlns=\"Linker20\">".fnDecode($rs_usuarios['DES_SENHAUS'])."</senha>
                                                                               <idloja xmlns=\"Linker20\">".$result['COD_UNIVEND']."</idloja>
                                                                                <idmaquina xmlns=\"Linker20\">FbitisS</idmaquina>
                                                                                <idcliente xmlns=\"Linker20\">".$COD_EMPRESA."</idcliente>
                                                                                <codvendedor xmlns=\"Linker20\"></codvendedor>
                                                                               <nomevendedor xmlns=\"Linker20\"></nomevendedor>
                                                                                 </dadosLogin>
                                                                            </AtualizaCadastro>
                                                                           </SOAP-ENV:Body>
                                                                         </SOAP-ENV:Envelope>",
                                                    CURLOPT_HTTPHEADER => array(
                                                      "cache-control: no-cache",
                                                      "content-type: text/xml",
                                                      "postman-token: bbd5f12a-e4ca-8d50-45a5-8e67f84eae5f"
                      ),
                    ));

                    $response_cliente = curl_exec($curl1);
                    $err = curl_error($curl1);

                    curl_close($curl1);

                    if ($err) {
                      echo "<br>cURL Error #:" . $err.'<br>';
                    } else {
                      
                        $doc = new DOMDocument();
                        libxml_use_internal_errors(true);
                        $doc->loadHTML($response_cliente);
                        libxml_clear_errors();
                        $xml = $doc->saveXML($doc->documentElement);                     
                        $xml = simplexml_load_string($xml,'SimpleXMLElement',LIBXML_NOCDATA);
                        $msgerro=$xml->body->envelope->body->atualizacadastroresponse->atualizacadastroresult->msgerro;

                        if($msgerro=='OK')
                        {
                          $updatelog="UPDATE log_integration_user SET   COD_INSERT='1' 
                                       WHERE COD_EXT_USER=".rtrim(trim($clientes_insert['usuarioId']))." and                                          
                                             COD_EMPRESA=".$COD_EMPRESA;
                                 mysqli_query($conntempo, $updatelog);  

                        }    
                    }
                }else{
                    Echo '<br>cliente nao aceito o programa<br>'; 
                      $updatelog="UPDATE log_integration_user SET   COD_INSERT='1' 
                                       WHERE COD_EXT_USER=".rtrim(trim($clientes_insert['usuarioId']))." and                                          
                                             COD_EMPRESA=".$COD_EMPRESA;
                                 mysqli_query($conntempo, $updatelog);
                }
                
            }
       //=======================FIM DO CADASTRO MARKA=======================================     
}