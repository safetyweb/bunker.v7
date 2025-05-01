<?php
include '../_system/_functionsMain.php';
$connAdm=$connAdm->connAdm();

/*
log_integration_venda
log_integration_user
*/
$COD_EMPRESA=$_GET['id'];
$conntempo= connTemp($COD_EMPRESA, '');
$dadosempresa="SELECT * FROM empresas where cod_empresa=".$COD_EMPRESA;
$rwdadosempresa=mysqli_fetch_assoc(mysqli_query($connAdm, $dadosempresa));
$COD_DATAWS="select FORMATO_WS from dataws  where cod_dataws=".$rwdadosempresa['COD_DATAWS'];
$COD_DATAWS=mysqli_fetch_assoc(mysqli_query($connAdm, $COD_DATAWS));   

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
  
    //===pedido pegar clientes
     //=======================INICIO DA VENDA=============================================
         /*SELECT * FROM log_integration_venda WHERE 
                                                       cod_insert=1 AND 
                                                       cod_empresa=$COD_EMPRESA AND
                                                       DAT_CADASTR <= DATA_VERIFICA";
        $rsestorno=mysqli_query($conntempo, $sql_estorno); 
       while ($dadosretorno= mysqli_fetch_assoc($rsestorno))
       {*/
    //==========FIM================
    
    
    
    //primeiro passo o cadastro do cliente 
        //inicio da consulta de clientes Fbits 
         // $timeconsulta=urlencode('');
//  $perido_venda= date('Y-m-d',strtotime(date('H:i:s').'- 360 day'))."T".date('H:i:s');
$perido_venda=date('Y-m-d')."T".date('H:i:s',strtotime(date('H:i:s').'- 5 minute'));
$perido_final=date('Y-m-d')."T".date('H:i:s',strtotime(date('H:i:s').'+ 1 minute'));
//$perido_venda='2020-08-17'."T".date('H:i:s',strtotime(date('H:i:s').'- 10 minute'));
//$perido_venda='2020-08-18T00:00:00';
$perido_final=date('Y-m-d')."T".date('H:i:s',strtotime(date('H:i:s').'+ 1 minute'));
echo '<br>'.$perido_venda.'<br>';

          $curl = curl_init();
            curl_setopt_array($curl, array(
              CURLOPT_URL => "HTTPS://".$result['URL']."/usuarios?dataInicial=".$perido_venda."&dataFinal=$perido_final",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_SSL_VERIFYPEER=> false,  
              CURLOPT_ENCODING => "",
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 300,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => "GET",
              CURLOPT_POSTFIELDS => "",
              CURLOPT_HTTPHEADER => array(
                "Accept: application/json",
                "Content-Type: application/json",  
                "Authorization: ".$result['DES_SENHA']."",
                "Postman-Token: e4de2b69-290f-4689-9213-8374387fa94c",
                "cache-control: no-cache",
                "encoding: UTF-8"  
              ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
              echo "<br>cURL Error #:" . $err.'<br>';
            } else {
            
                    $arrayList=json_decode(fnAcentos($response), true); 
                    // echo '<pre>';
                    // print_r($arrayList);
                    // echo '</pre>';
                     
                    foreach ($arrayList as $key => $value) {
                    // echo '<pre>';
                     //print_r($value);
                    // echo '</pre>';

                        $checkuser="select COD_EXT_USER from log_integration_user where COD_EXT_USER=".rtrim(trim($value['usuarioId']))." and COD_EMPRESA=$COD_EMPRESA";
                        $rsnum=mysqli_query($conntempo, $checkuser);
                        if(mysqli_num_rows($rsnum)<=0)
                        { echo '<br><br>teste aqui'.$checkuser.'<br><br>';
                            
                            if(fnLimpaDoc($value['cpf']=='')){$CPFCNPJ=$value['CNPJ'];}else{$CPFCNPJ=$value['cpf'];}
                            if($value['grupoInformacaoCadastral'][0]['valor']=='Sim' ||$value['grupoInformacaoCadastral'][0]['valor']=='' )
                            {$aceite='S';}else{$aceite='N';}
                            
                                //$xamls= addslashes(str_replace(array("\n",""),array(""," "), var_export($response,true)));
                              
                               $xamls= addslashes(json_encode(fnAcentos($value),true));
                                $insertolog="INSERT INTO log_integration_user 
                                                                     (COD_EXT_USER, 
                                                                      COD_EMPRESA,
                                                                      NUM_CGCECPF,
                                                                      DES_VENDA,
                                                                      COD_INSERT,
                                                                      ACEITE) 
                                                                      VALUES 
                                                                      (
                                                                      ".rtrim(trim($value['usuarioId'])).", 
                                                                       ".$COD_EMPRESA.", 
                                                                       '".fnLimpaDoc($CPFCNPJ)."', 
                                                                       '$xamls', 
                                                                       '0',
                                                                       '$aceite'
                                                                       );";                               
                                     mysqli_query($conntempo, $insertolog);
                                

                        } else {
                               //$xamls= addslashes(str_replace(array("\n",""),array(""," "), var_export($response,true)));
                                $xamls= addslashes(json_encode(fnAcentos($value),true));
                               $updatelog="UPDATE log_integration_user SET DES_VENDA='".$xamls."',
                                                                           COD_INSERT='0' 
                                     WHERE COD_EXT_USER=".rtrim(trim($value['usuarioId']))." and
                                           COD_INSERT='1' and
                                           COD_EMPRESA=".$COD_EMPRESA;
                               mysqli_query($conntempo, $updatelog);
                        } 
                        $cod_cliente.=$value['usuarioId'].',';
                    }
                 if(@$value=='')
                 {
                   echo '<br>Nao tem cadastros novos<br>';  
                 }    
                    
            }
        //=========================Fim da consulta fbits==================================
       //Iniciar o cadastramento no marka para fidelizar o cliente.
        
            $processcliente="select * from log_integration_user where COD_INSERT='0' and COD_EMPRESA=$COD_EMPRESA";
         
            $rsusuario=mysqli_query($conntempo, $processcliente);
            while ($clientedados=mysqli_fetch_assoc($rsusuario))
            {    
                 $clientes_insert= json_decode(fnAcentos($clientedados['DES_VENDA']),true); 
                echo'<pre>';         
                print_r($clientes_insert);
                echo'</pre>';
                
                
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
                                                    CURLOPT_TIMEOUT => 300,
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
                                                                                <idmaquina xmlns=\"Linker20\">FbitisP</idmaquina>
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
                          echo '<br><br>'.$updatelog.'<br><br>';
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
       //=======================INICIO DA VENDA=============================================
echo "<br> Entrou na venda...................<br>";
$perido_venda= date('Y-m-d',strtotime(date('H:i:s').'- 360 day'))."T".date('H:i:s');
//$perido_venda='2020-08-17'."T".date('H:i:s',strtotime(date('H:i:s').'- 5 minute'));
$perido_final=date('Y-m-d')."T".date('H:i:s',strtotime(date('H:i:s').'+ 1 minute'));
//$perido_venda=date('Y-m-d')."T".date('H:i:s',strtotime(date('H:i:s').'- 10 minute'));
       $temp_venda = microtime(true);  
           $curl = curl_init();
            curl_setopt_array($curl, array(
              CURLOPT_URL => "HTTPS://".$result['URL']."/pedidos?dataInicial=".$perido_venda."&dataFinal=$perido_final",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_SSL_VERIFYPEER=> false,  
              CURLOPT_ENCODING => "",
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 300,
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
            
                    $arrayListVENDA=json_decode(fnAcentos($response), true); 
                    foreach ($arrayListVENDA as $keyVENDA => $valuevenda) {
                     echo "<br> Entrou no loop de  venda...................<br>";   
                      // echo '<pre>';
                      // print_r($valuevenda);
                      // echo '</pre>'; 
                       
                       //Inserir venda na base de dados
                        $verif_venda="SELECT * FROM log_integration_venda WHERE COD_EXT_VEN='".$valuevenda['pedidoId']."' AND COD_EMPRESA=".$COD_EMPRESA;
                       
                        $ex_venda=mysqli_fetch_assoc(mysqli_query($conntempo, $verif_venda));
                         $xamls= addslashes(json_encode(fnAcentos($valuevenda),true));
                          
                        if($ex_venda['COD_EXT_VEN']=='')
                        {   
                            
                            $startDate = time();
                            $datverifi=date('Y-m-d H:i:s', strtotime('+7 day', $startDate));
                            $insertolog="INSERT INTO log_integration_venda 
                                                                         (COD_EXT_VEN,
                                                                          COD_EXT_USER, 
                                                                          COD_EMPRESA,
                                                                          DES_VENDA,
                                                                          STATUS_PEDIDO,
                                                                          COD_INSERT,
                                                                          DATA_VERIFICA) 
                                                                          VALUES 
                                                                          (
                                                                         ".rtrim(trim($valuevenda['pedidoId'])).",
                                                                         ".rtrim(trim($valuevenda['usuario']['usuarioId'])).", 
                                                                         ".$COD_EMPRESA.", 
                                                                         '$xamls', 
                                                                         '".rtrim(trim($valuevenda['situacaoPedidoId']))."',     
                                                                         '0',
                                                                         '".$datverifi."'
                                                                           );";                         
                            mysqli_query($conntempo, $insertolog);
                        //antes de continuar verificar se o cliente ja esta cadastrado no marka.
                        
                        //=====================fim=============================================
                        }else{
                            Echo 'Nao a venda para processar';
                        }
                       //Fim da insercao do XML 
        //======================PRIMEIRO PASSO VERIFICAR SE O CLIENTE QUE SER FIDELIDADE======
                       //++++++++++++++++verificar se o cliente ja esta cadastrado
                        $curl = curl_init();

                        curl_setopt_array($curl, array(
                          CURLOPT_URL => "http://webhook.bunker.mk/ProcessauserFbits?id=$COD_EMPRESA&user=".$valuevenda['usuario']['usuarioId']."",
                          CURLOPT_RETURNTRANSFER => true,
                          CURLOPT_ENCODING => "",
                          CURLOPT_MAXREDIRS => 10,
                          CURLOPT_TIMEOUT => 300,
                          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                          CURLOPT_CUSTOMREQUEST => "POST",
                          CURLOPT_POSTFIELDS => "",
                          CURLOPT_HTTPHEADER => array(
                            "Postman-Token: e723cbc1-cefe-478f-bbf1-dd019bea5cb7",
                            "cache-control: no-cache"
                          ),
                        ));

                        $response = curl_exec($curl);
                        $err = curl_error($curl);

                        curl_close($curl);

                        if ($err) {
                          echo "cURL Error #:" . $err;
                        } else {
                          //echo $response;
                        }
                        //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++
                        $ACEITEFIDELIDADE="SELECT * FROM log_integration_user WHERE  COD_EXT_USER='".$valuevenda['usuario']['usuarioId']."' AND COD_INSERT=1 and COD_EMPRESA=$COD_EMPRESA";
                        $rsaceite=mysqli_fetch_assoc(mysqli_query($conntempo, $ACEITEFIDELIDADE)); 
                        echo '<pre>';
                        print_r($rsaceite);
                        echo '</pre>'; 
                        
                            if($rsaceite['ACEITE']=='S')
                            {  
                                //=======Venda fidelizada==========================
                                $ENVIAVENDA="SELECT * FROM log_integration_venda WHERE COD_EXT_VEN='".$valuevenda['pedidoId']."' AND
                                                    COD_INSERT=0 and COD_EMPRESA=$COD_EMPRESA";
                                $rsENVIAVENDA=mysqli_fetch_assoc(mysqli_query($conntempo, $ENVIAVENDA)); 
                                $ARRAYDADOSVENDA=json_decode(fnAcentos($rsENVIAVENDA['DES_VENDA']),true);
                                //LOOP DOS ITENS
                                
                                 unset($xmlitem); //zerando array de itens ante de entrar na rotina novamente
                                 foreach ($ARRAYDADOSVENDA['itens'] as $keyiTEM => $valueITEM) {
                                       
                                  // echo '<pre>';
                                 //  print_r($valueITEM);
                                 //  echo '</pre>';   
                                   $xmlitem.="   <vendaitem>
                                                    <id_item>".$valueITEM['produtoVarianteId']."</id_item>
                                                    <produto>".fnAcentos($valueITEM['nome'])."</produto>
                                                    <codigoproduto>".$valueITEM['sku']."</codigoproduto>
                                                    <quantidade>".$valueITEM['quantidade']."</quantidade>
                                                    <valor>".fnValor($valueITEM['precoVenda'],2)."</valor>
                                                </vendaitem>
                                           
                                            ";
                                    $vl_descmarka[]=abs($valueITEM['formulas'][0]['valor']);
                                   
                                 }  
                                 print_r($vl_descmarka);
                                 echo '</pre>';
                                 $vldesconto=array_sum($vl_descmarka);
                                 
                                //inserir venda no bunker                               
                                
                                $date=str_replace('T', ' ', $ARRAYDADOSVENDA['data']);
                                $arraydate=explode('.', $date);
                                $datahora=DateTime::createFromFormat($COD_DATAWS['FORMATO_WS'], $arraydate[0]);
                                    if($datahora===false){
                                        echo '<br> data hora invalida........... <br>';
                                           } else {
                                       $datahora=$datahora->format('Y-m-d H:i:s');

                                    }
                                 /* echo 'COD_EMPRESA >>>>>>>>>>>>> '.$COD_EMPRESA.'<BR>
                                                 datetimevendaoriginal>>'.$arraydate[0].'
                                                 <br>DATA VENDA CONVERTIDA '.$datahora.'---------------<br>
                                                    DATA CONFIGURADA...'.$COD_DATAWS['FORMATO_WS'].'<br>';   
                                  * 
                                  */
                                    echo '<pre>';
                                    print_r($ARRAYDADOSVENDA);
                                    echo '</pre>';
                                    
                                    $vl_totel=$ARRAYDADOSVENDA['valorTotalPedido']-$ARRAYDADOSVENDA['valorFrete'];
                                    $vl_totel+=$vldesconto;
                                 
                                        $curl1 = curl_init();
                                        curl_setopt($curl1, CURLOPT_SSL_VERIFYPEER, false);
                                        curl_setopt_array($curl1, array(
                                                                        CURLOPT_URL => "http://ws.bunker.mk/?wsdl=",
                                                                        CURLOPT_RETURNTRANSFER => true,
                                                                        CURLOPT_ENCODING => "utf-8",
                                                                        CURLOPT_MAXREDIRS => 10,
                                                                        CURLOPT_TIMEOUT => 300,
                                                                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                                                        CURLOPT_CUSTOMREQUEST => "POST",
                                                                        CURLOPT_POSTFIELDS => '<?xml version="1.0" encoding="UTF-8"?>
                                                                                                <SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns="Linker20">
                                                                                                    <SOAP-ENV:Body>
                                                                                                        <InserirVenda>
                                                                                                            <venda>
                                                                                                                <id_vendapdv>'.$ARRAYDADOSVENDA['pedidoId'].'</id_vendapdv>
                                                                                                                <datahora>'.$datahora.'</datahora>
                                                                                                                <cartao>'.fnLimpaDoc($ARRAYDADOSVENDA['usuario']['cpf']).'</cartao>
                                                                                                                <valortotal>'.fnValor($vl_totel,2).'</valortotal>
                                                                                                                <valor_resgate>'.fnValor($vldesconto,2).'</valor_resgate>
                                                                                                                <cupom>'.$ARRAYDADOSVENDA['transacaoId'].'</cupom>
                                                                                                                <formapagamento>Promissoria</formapagamento>
                                                                                                                <codatendente></codatendente>
                                                                                                                <codvendedor></codvendedor>
                                                                                                                <items>'.$xmlitem.'</items>
                                                                                                            </venda>
                                                                                                            <dadosLogin>
                                                                                                                <login>'.$rs_usuarios['LOG_USUARIO'].'</login>
                                                                                                                <senha>'.fnDecode($rs_usuarios['DES_SENHAUS']).'</senha>
                                                                                                                <idloja>'.$result['COD_UNIVEND'].'</idloja>
                                                                                                                <idmaquina>Fbitis</idmaquina>
                                                                                                                <idcliente>'.$COD_EMPRESA.'</idcliente>
                                                                                                            </dadosLogin>
                                                                                                        </InserirVenda>
                                                                                                    </SOAP-ENV:Body>
                                                                                                </SOAP-ENV:Envelope>',
                                                                        CURLOPT_HTTPHEADER => array(
                                                                          "cache-control: no-cache",
                                                                          "content-type: text/xml",
                                                                          "postman-token: bbd5f12a-e4ca-8d50-45a5-8e67f84eae5f"
                                                                        ),
                                        ));

                                        $response_venda = curl_exec($curl1);
                                        $err = curl_error($curl1);

                                        curl_close($curl1);

                                        if ($err) {
                                          echo "<br>cURL Error #:" . $err.'<br>';
                                        } else {
                                            
                                            $doc = new DOMDocument();
                                            libxml_use_internal_errors(true);
                                            $doc->loadHTML($response_venda);
                                            libxml_clear_errors();
                                            $xml = $doc->saveXML($doc->documentElement);                     
                                            $xml = simplexml_load_string($xml,'SimpleXMLElement',LIBXML_NOCDATA);
                                            $msgerro=$xml->body->envelope->body->inserirvendaresponse->inserirvendaresult->msgerro;
                                            if($msgerro=='OK')
                                            {
                                                echo "<br> Venda OK...................<br>";
                                                $VENDAok="UPDATE log_integration_venda
                                                    SET COD_INSERT='1' WHERE COD_EXT_VEN='".$ARRAYDADOSVENDA['pedidoId']."' AND COD_EMPRESA=".$COD_EMPRESA;
                                                mysqli_query($conntempo, $VENDAok);
                                            } elseif ($msgerro=='Id_vendaPdv ja existe, tente outro codigo por favor!') {
                                               echo "<br> $msgerro ...................<br>";
                                                $VENDAok="UPDATE log_integration_venda
                                                    SET COD_INSERT='1' WHERE COD_EXT_VEN='".$ARRAYDADOSVENDA['pedidoId']."' AND COD_EMPRESA=".$COD_EMPRESA;
                                                mysqli_query($conntempo, $VENDAok);
                                              } 
                                              
                                        }
                                        //FIM da VENDA                                 
                               echo '<br>aceite<br>';   
                            } else{
                                //=======Cliente não fidelizou venda avulsa========
                               echo '<br>nao aceite.........<br>';  
                                 $ENVIAVENDA="SELECT * FROM log_integration_venda WHERE COD_EXT_VEN='".$valuevenda['pedidoId']."' AND
                                                    COD_INSERT=0 and COD_EMPRESA=$COD_EMPRESA";
                                $rsENVIAVENDA=mysqli_fetch_assoc(mysqli_query($conntempo, $ENVIAVENDA)); 
                                $ARRAYDADOSVENDA=json_decode(fnAcentos($rsENVIAVENDA['DES_VENDA']),true);
                                if($ARRAYDADOSVENDA['pedidoId']!='')
                                {    
                                    echo '<br>ENTROU NO PEDIDO.....<br>';
                                        //LOOP DOS ITENS
                                         unset($xmlitem); //zerando array de itens ante de entrar na rotina novamente
                                         foreach ($ARRAYDADOSVENDA['itens'] as $keyiTEM => $valueITEM) {
                                          // echo '<pre>';
                                         //  print_r($valueITEM);
                                         //  echo '</pre>';   
                                           $xmlitem.="   <vendaitem>
                                                            <id_item>".$valueITEM['produtoVarianteId']."</id_item>
                                                            <produto>".fnAcentos($valueITEM['nome'])."</produto>
                                                            <codigoproduto>".$valueITEM['sku']."</codigoproduto>
                                                            <quantidade>".$valueITEM['quantidade']."</quantidade>
                                                            <valor>".fnValor($valueITEM['precoVenda'],2)."</valor>
                                                        </vendaitem>

                                                    ";

                                         }                       
                                         //inserir venda no bunker
                                                                         
                                            $date=str_replace('T', ' ', $ARRAYDADOSVENDA['data']);
                                            $arraydate=explode('.', $date);
                                            $datahora=DateTime::createFromFormat($COD_DATAWS['FORMATO_WS'], $arraydate[0]);  
                                             
                                          
                                               if($datahora===false){
                                                   echo '<br> data hora invalida........... <br>';
                                                      } else {
                                                  $datahora=$datahora->format('Y-m-d H:i:s');

                                               }
                                               /* echo 'COD_EMPRESA >>>>>>>>>>>>> '.$COD_EMPRESA.'<BR>
                                                 datetimevendaoriginal>>'.$arraydate[0].'
                                                 <br>DATA VENDA CONVERTIDA '.$datahora.'---------------<br>
                                                  DATA CONFIGURADA...'.$COD_DATAWS['FORMATO_WS'].'<br>';*/
                                        //inserir venda no bunker
                                         $vl_totel=$ARRAYDADOSVENDA['valorTotalPedido']-$ARRAYDADOSVENDA['valorFrete'];
                                                $curl1 = curl_init();
                                                curl_setopt($curl1, CURLOPT_SSL_VERIFYPEER, false);
                                                curl_setopt_array($curl1, array(
                                                                                CURLOPT_URL => "http://ws.bunker.mk/?wsdl=",
                                                                                CURLOPT_RETURNTRANSFER => true,
                                                                                CURLOPT_ENCODING => "utf-8",
                                                                                CURLOPT_MAXREDIRS => 10,
                                                                                CURLOPT_TIMEOUT => 300,
                                                                                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                                                                CURLOPT_CUSTOMREQUEST => "POST",
                                                                                CURLOPT_POSTFIELDS => '<?xml version="1.0" encoding="UTF-8"?>
                                                                                                        <SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns="Linker20">
                                                                                                            <SOAP-ENV:Body>
                                                                                                                <InserirVenda>
                                                                                                                    <venda>
                                                                                                                        <id_vendapdv>'.$ARRAYDADOSVENDA['pedidoId'].'</id_vendapdv>
                                                                                                                        <datahora>'.$datahora.'</datahora>
                                                                                                                        <cartao>0</cartao>
                                                                                                                        <valortotal>'.fnValor($vl_totel,2).'</valortotal>
                                                                                                                        <valor_resgate>'.fnValor($ARRAYDADOSVENDA['valorCreditoFidelidade'],2).'</valor_resgate>
                                                                                                                        <cupom>'.$ARRAYDADOSVENDA['transacaoId'].'</cupom>
                                                                                                                        <formapagamento>Promissoria</formapagamento>
                                                                                                                        <codatendente></codatendente>
                                                                                                                        <codvendedor></codvendedor>
                                                                                                                        <items>'.$xmlitem.'</items>
                                                                                                                    </venda>
                                                                                                                    <dadosLogin>
                                                                                                                        <login>'.$rs_usuarios['LOG_USUARIO'].'</login>
                                                                                                                        <senha>'.fnDecode($rs_usuarios['DES_SENHAUS']).'</senha>
                                                                                                                        <idloja>'.$result['COD_UNIVEND'].'</idloja>
                                                                                                                        <idmaquina>Fbitis</idmaquina>
                                                                                                                        <idcliente>'.$COD_EMPRESA.'</idcliente>
                                                                                                                    </dadosLogin>
                                                                                                                </InserirVenda>
                                                                                                            </SOAP-ENV:Body>
                                                                                                        </SOAP-ENV:Envelope>',
                                                                                CURLOPT_HTTPHEADER => array(
                                                                                  "cache-control: no-cache",
                                                                                  "content-type: text/xml",
                                                                                  "postman-token: bbd5f12a-e4ca-8d50-45a5-8e67f84eae5f"
                                                                                ),
                                                ));

                                                $response_venda = curl_exec($curl1);
                                                $err = curl_error($curl1);

                                                curl_close($curl1);

                                                if ($err) {
                                                  echo "cURL Error #:" . $err;
                                                } else {

                                                    $doc = new DOMDocument();
                                                    libxml_use_internal_errors(true);
                                                    $doc->loadHTML($response_venda);
                                                    libxml_clear_errors();
                                                    $xml = $doc->saveXML($doc->documentElement);                     
                                                    $xml = simplexml_load_string($xml,'SimpleXMLElement',LIBXML_NOCDATA);
                                                    $msgerro=$xml->body->envelope->body->inserirvendaresponse->inserirvendaresult->msgerro;
                                                    if($msgerro=='VENDA AVULSA OK')
                                                    {
                                                        $VENDAok="UPDATE log_integration_venda
                                                            SET COD_INSERT='1' WHERE COD_EXT_VEN='".$ARRAYDADOSVENDA['pedidoId']."' AND COD_EMPRESA=".$COD_EMPRESA;
                                                        mysqli_query($conntempo, $VENDAok);
                                                    } elseif ($msgerro=='Id_vendaPdv ja existe, tente outro codigo por favor!') {
                                                        $VENDAok="UPDATE log_integration_venda
                                                            SET COD_INSERT='1' WHERE COD_EXT_VEN='".$ARRAYDADOSVENDA['pedidoId']."' AND COD_EMPRESA=".$COD_EMPRESA;
                                                        mysqli_query($conntempo, $VENDAok);
                                                      }   
                                                }
                                } else {
                                  echo '<br>Pdv vazio<br>';    
                                }        
                            }
                    }  
            }
        $total_venda = microtime(true) - $temp_venda;
       echo '<br>Tempo de execução do segundo script: ' . $total_venda.'<br>';            
           
       //=======================FINDA VENDA====================================================     
      // Estorno de venda
       $temp_estrono = microtime(true);  
       $sql_estorno="SELECT * FROM log_integration_venda WHERE 
                                                       cod_insert=1 AND 
                                                       cod_empresa=$COD_EMPRESA AND
                                                       NOW() <= DATA_VERIFICA limit 5";
        $rsestorno=mysqli_query($conntempo, $sql_estorno);
       
       while ($dadosretorno= mysqli_fetch_assoc($rsestorno))
       {
           
           unset($arrayListestorno);
           $curl = curl_init();
           curl_setopt_array($curl, array(
             CURLOPT_URL => "HTTPS://".$result['URL']."/pedidos/".$dadosretorno['COD_EXT_VEN']."/status",
             CURLOPT_RETURNTRANSFER => true,
             CURLOPT_SSL_VERIFYPEER=> false,  
             CURLOPT_ENCODING => "",
             CURLOPT_MAXREDIRS => 10,
             CURLOPT_TIMEOUT => 300,
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

                   $arrayListestorno=json_decode(fnAcentos($response), true); 
                    if($arrayListestorno['situacaoPedidoId'] >='3' && $arrayListestorno['situacaoPedidoId'] <='8' ||
                       $arrayListestorno['situacaoPedidoId']=='12' || $arrayListestorno['situacaoPedidoId']=='22' ||
                       $arrayListestorno['situacaoPedidoId']=='23')
                    {  
                       echo '<br>PEDIDO CANCELADO<br>';
                       $curl1 = curl_init();
                                        curl_setopt($curl1, CURLOPT_SSL_VERIFYPEER, false);
                                        curl_setopt_array($curl1, array(
                                                                        CURLOPT_URL => "http://ws.bunker.mk/?wsdl=",
                                                                        CURLOPT_RETURNTRANSFER => true,
                                                                        CURLOPT_ENCODING => "utf-8",
                                                                        CURLOPT_MAXREDIRS => 10,
                                                                        CURLOPT_TIMEOUT => 300,
                                                                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                                                        CURLOPT_CUSTOMREQUEST => "POST",
                                                                        CURLOPT_POSTFIELDS => '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:lin="Linker20">
                                                                                                <soapenv:Header />
                                                                                                <soapenv:Body>
                                                                                                    <lin:EstornaVenda>         
                                                                                                        <id_vendapdv>'.$dadosretorno['COD_EXT_VEN'].'</id_vendapdv>
                                                                                                        <dadosLogin>             
                                                                                                            <lin:login>'.$rs_usuarios['LOG_USUARIO'].'</lin:login>               
                                                                                                            <lin:senha>'.fnDecode($rs_usuarios['DES_SENHAUS']).'</lin:senha>            
                                                                                                            <lin:idloja>'.$result['COD_UNIVEND'].'</lin:idloja>              
                                                                                                            <lin:idmaquina>Fbitis</lin:idmaquina>               
                                                                                                            <lin:idcliente>'.$COD_EMPRESA.'</lin:idcliente>               
                                                                                                            <lin:codvendedor></lin:codvendedor>               
                                                                                                            <lin:nomevendedor></lin:nomevendedor>                
                                                                                                            <lin:rawdata></lin:rawdata>
                                                                                                        </dadosLogin>
                                                                                                    </lin:EstornaVenda>
                                                                                                </soapenv:Body>
                                                                                            </soapenv:Envelope>',
                                                                        CURLOPT_HTTPHEADER => array(
                                                                          "cache-control: no-cache",
                                                                          "content-type: text/xml",
                                                                          "postman-token: bbd5f12a-e4ca-8d50-45a5-8e67f84eae5f"
                                                                        ),
                                        ));

                                        $response_venda = curl_exec($curl1);
                                        $err = curl_error($curl1);

                                        curl_close($curl1);

                                        if ($err) {
                                          echo "<br>cURL Error #:" . $err.'<br>';
                                        } else {
                                            $VENDAok="UPDATE log_integration_venda
                                                        SET COD_INSERT='2',STATUS_PEDIDO='".$arrayListestorno['situacaoPedidoId']."' WHERE COD_EXT_VEN='".$dadosretorno['COD_EXT_VEN']."' AND COD_EMPRESA=".$COD_EMPRESA;
                                             $rsestorno=mysqli_query($conntempo, $VENDAok);
                                             echo 'VEnda estornoda'.$VENDAok.'<br>';
                                        }
                    }else{
                        echo '<br>Pedidos OK<br>';
                    }        
            }        
       }       
       $total_estorno = microtime(true) - $temp_estrono;
       echo 'Tempo de execução do segundo script: ' . $total_estorno;
       
        //Fim do estorno           
}