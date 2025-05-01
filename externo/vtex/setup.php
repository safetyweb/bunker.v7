<?php
//master data sistema de entidade 
//cliente e endereço
//verificar o master data para verificar se o cliente é ou não e fidelidade.
$empresa=$_GET['empresa'];
$pedido_vetx=$_GET['PEDIDO'];
include '../../_system/_functionsMain.php';
include './function_marka.php';
function fnFormatvalor($Num,$dec)
{ 
 // $Num=rtrim(trim($Num));
 // $valor = str_replace(".", "", $Num);
 // $valor = str_replace(",", ".", $Num); 
  $valor = bcmul($Num, '100', $dec); //Multiplicação - Parâmetros[valor, multiplicador, casas decimais] 
  $valor = bcdiv($Num, '100', $dec); //Divisão - Parâmetros[valor, divisor, casas decimais] echo $valor; //Exibe "100.19" (String)
  $valor=number_format($valor, 2, ',', '.');
 return $valor; //retorna o valor formatado para gravar no banco 
}  
//listar os pedidos.
$connadmin=$connAdm->connAdm();
$buscadados="SELECT * FROM SENHAS_ECOMMERCE where log_ativo='S' and TIPO_URL =5  and cod_parcomu=10 AND COD_EMPRESA='".$empresa."'";
$rsconfig =mysqli_query($connadmin, $buscadados);   
while ($row = mysqli_fetch_assoc($rsconfig)) {

   
    @$DES_USUARIO=$row['DES_USUARIO'];
    @$DES_AUTHKEY=urlencode(utf8_encode($row['DES_AUTHKEY']));
    @$cod_empresa=$row['COD_EMPRESA'];
    @$COD_USUINTEGRA=$row['COD_USUINTEGRA'];
    @$urlwsdl=$row['URL_WSDL'];
    
    //conntemp
     $conntemp=connTemp($cod_empresa,'');
     
        $curl = curl_init();
        $datahora=urlencode(utf8_encode($pedido_vetx));      
        curl_setopt_array($curl, array(
          CURLOPT_SSL_VERIFYPEER=> false,  
          CURLOPT_URL => "$urlwsdl/"."$datahora",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_POSTFIELDS => "",
          CURLOPT_HTTPHEADER => array(  
            "Accept: application/json",
            "Content-Type: application/json",
            "X-VTEX-API-AppKey: $DES_USUARIO",
            "X-VTEX-API-AppToken: $DES_AUTHKEY"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
          echo "cURL Error #:" . $err;
        } else {
            $arrayList=json_decode($response, true);
           // foreach ($arrayList['list'] as $key => $value) {
                   
                    if($arrayList['status']=='ready-for-handling')
                    {
                      //pago  
                     $status='0'; 
                     $descri='ready-for-handling';
                    }elseif ($arrayList['status']=='waiting-for-seller-confirmation') {
                     //Aguardando confirmação  
                     $status='1'; 
                     $descri='waiting-for-seller-confirmation';
                    }elseif ($arrayList['status']=='payment-pending') {
                        //Aguardando pagamento  
                     $status='2';
                     $descri='payment-pending';
                    }elseif ($arrayList['status']=='approve-payment') {
                        //Aprovado pagamento  
                     $status='3'; 
                     $descri='approve-payment';
                    }elseif ($arrayList['status']=='payment-approved' || $arrayList['status']=='invoiced') {
                        //Aprovado pagamento  
                     $status='4';  
                     $descri='payment-approved';
                    }elseif ($arrayList['status']=='window-to-cancel') {
                      $status='6';  
                     $descri='window-to-cancel';  
                    }else{
                        //se não achar bota isso
                     $status='5';   
                     $descri='Categoria não listada';
                    }
                    $orderId=$arrayList['orderId'];
                    
                    //VERIFICAR SE JA EXISTE NA BASE DE DADOS ANTES DE INTERIR
                   // mysqli_next_result($conntemp);
                    $VERIFICAEXT="SELECT * from log_integration_venda_vtex  WHERE COD_EMPRESA=$cod_empresa AND COD_EXT_VEN='$orderId'";       
                    $RETURNid=mysqli_fetch_assoc(mysqli_query($conntemp, $VERIFICAEXT));
                    if($RETURNid['COD_ORIGEM']<=0)
                    {
                   // $xamls= addslashes(str_replace(array("\n",""),array(""," "), var_export($arrayList,true)));    
                     $dateformated=date("Y-m-d H:i:s", strtotime($arrayList['creationDate'])); 
                       $insert="INSERT INTO log_integration_venda_vtex (COD_EXT_VEN,
                                                                            COD_EXT_USER, 
                                                                            DAT_CADASTR, 
                                                                            COD_EMPRESA, 
                                                                            COD_INSERT, 
                                                                            STATUS_PEDIDO,
                                                                            status_descricao,
                                                                            des_venda) 
                                                                            VALUES 
                                                                            ('$orderId', 
                                                                            '$COD_USUINTEGRA', 
                                                                            '$dateformated', 
                                                                            '$cod_empresa', 
                                                                            '1', 
                                                                            '$status',
                                                                            '$descri',
                                                                            '$xamls');";
                     
                        mysqli_query($conntemp, $insert);
                    }else{   
                        $statuUpdateP=$RETURNid['STATUS_PEDIDO'];
                        //mudar os status para venda OK
                        if($statuUpdateP ==='100' || $statuUpdateP ==='0' || $statuUpdateP  ==='2' || 
                            $statuUpdateP ==='4'  || $statuUpdateP ==='200'){
                           
                            echo 'Venda ja integrada com o marka';
                        }else{
                            
                             //vou ter que compara o status atual com o cadastrado pra ver se teve alteração
                             if($statuUpdateP != $status){
                                 //se for diferente atualiza na base para o atual
                                $updatestatus="UPDATE log_integration_venda_vtex SET STATUS_PEDIDO='$status', COD_INSERT=1 WHERE  COD_EMPRESA=$cod_empresa AND COD_EXT_VEN='$orderId'";
                                mysqli_query($conntemp, $updatestatus);
                             }
                        }
                            
                    }
                 //ALTER TABLE `log_integration_venda` ALTER `COD_EXT_VEN` DROP DEFAULT;
                 //ALTER TABLE `log_integration_venda`CHANGE COLUMN `COD_EXT_VEN` `COD_EXT_VEN` VARCHAR(150) NOT NULL AFTER `COD_ORIGEM`;
            //    }
                ////////////////////////
        }        


        
}
mysqli_close($conntemp);
 
//dados completos do pedido.
$buscadados="SELECT * FROM SENHAS_ECOMMERCE where log_ativo='S' and TIPO_URL =6  and cod_parcomu=10";
$rsconfig =mysqli_query($connadmin, $buscadados);   
while ($row = mysqli_fetch_assoc($rsconfig)) {
       
        $DES_USUARIO=$row['DES_USUARIO'];
        $DES_AUTHKEY=urlencode(utf8_encode($row['DES_AUTHKEY']));
        @$cod_empresa=$row['COD_EMPRESA'];
        @$COD_USUINTEGRA=$row['COD_USUINTEGRA'];
        @$urlwsdl=$row['URL_WSDL'];
        unset($conntemp) ;       
$conntemp=connTemp($cod_empresa,'');

        //pegar o numero do pedido na base de dados.
        $VERIFICAEXT="SELECT * from log_integration_venda_vtex  WHERE COD_EMPRESA=$cod_empresa AND COD_INSERT=1";       
        $RETURNid=mysqli_query($conntemp, $VERIFICAEXT);
        while ($num_pedido= mysqli_fetch_assoc($RETURNid))
        {
            $pedidoupdate=$num_pedido['COD_EXT_VEN'];
            
                $curl = curl_init();
                curl_setopt_array($curl, array(                
                CURLOPT_URL => "$urlwsdl/$pedidoupdate",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_POSTFIELDS => "",
                CURLOPT_HTTPHEADER => array(
                  "Accept: application/json",
                  "Content-Type: application/json",
                  "X-VTEX-API-AppKey: $DES_USUARIO",
                  "X-VTEX-API-AppToken: $DES_AUTHKEY",
                  "cache-control: no-cache"
                ),
              ));

              $response = curl_exec($curl);
              $err = curl_error($curl);

              curl_close($curl);

              if ($err) {
                echo "cURL Error #:" . $err;
            } else {
                unset($arrayList);
                 $arrayList=json_decode($response, true);
                // echo '<pre>';
                // print_r($arrayList);
                // echo '</pre>';
                 
                  //Fazer o UPdate do pedido na marka.
                 // mysqli_next_result($conntemp);
                 // STATUS_PEDIDO='100'  EMPROCESSAMENTO
                 
                //vou ter que verificar se o venda está paga e aprovado 
                 // e rodar o comando a baixo se nao vou ter que continuar verificando ate mudar os status.
                 $VERIFICAEXT="SELECT * from log_integration_venda_vtex  WHERE COD_EMPRESA=$cod_empresa AND COD_EXT_VEN='$pedidoupdate'";       
                 $statusid=mysqli_fetch_assoc(mysqli_query($conntemp, $VERIFICAEXT));
                 $statuUpdateP=$statusid['STATUS_PEDIDO'];  
                    if($statuUpdateP ==='0' || $statuUpdateP  ==='2' || 
                        $statuUpdateP ==='4' || $statuUpdateP ==='100' || $statuUpdateP ==='6'){


                            $xamls= addslashes(str_replace(array("\n",""),array(""," "), var_export($arrayList,true)));
                            
                            $alterarpedido="UPDATE log_integration_venda_vtex 
                                              SET DES_VENDA='$xamls', 
                                                      COD_INSERT='2', 
                                                      STATUS_PEDIDO='100' 
                                              WHERE  cod_empresa=$cod_empresa AND 
                                                     cod_ext_ven='$pedidoupdate';";
                           
                            mysqli_query($conntemp, $alterarpedido);
                            unset($xamls);
        
       //pegando usuario marka para autenticar
        //busca de usuarios para envia ws_marka
                   $buscausuariows="select  des_senhaus,
                                            log_usuario,
                                            cod_univend
                                    from usuarios 
                                                where   cod_empresa=$cod_empresa and 
                                                        cod_usuario='".$row['COD_USUINTEGRA']."'"; 
                   $usuarioconn=mysqli_fetch_assoc(mysqli_query($connadmin, $buscausuariows));
                   
                   $passwsmarka=fnDecode($usuarioconn['des_senhaus']);
                   $userwsmarka=$usuarioconn['log_usuario'];
                   $cod_univend=$usuarioconn['cod_univend'];
                 
        //vamos iniciar o tratamento dos arreis
        //iniciar a busca dos dados e cadastro de clientes.
        //consulta data quality           
         $client_consulta = new SoapClient('http://ws.bunker.mk?wsdl',
                                                                    array( 
                                                                           'trace'=>true,
                                                                           'exceptions'=>true,
                                                                           'connection_timeout' => 10,
                                                                           'cache_wsdl' => WSDL_CACHE_NONE,
                                                                           'features' => SOAP_SINGLE_ELEMENT_ARRAYS,
                                                                           'encoding' => 'UTF-8' 
                                                                       ));
        $function = 'ConsultaCadastroPorCPF';
        if($arrayList['clientProfileData']['documentType']=='cpf')
        {
           @$cpfdt=  $arrayList['clientProfileData']['document'];
           @$TipoPessoaId='PF';
        }
        else{
           @$cpfdt=$arrayList['clientProfileData']['document'];
           @$TipoPessoaId='PJ';
        }
       
        $arguments= array   ('ConsultaCadastroPorCPF'=>array(  
                                                        'CPF'=>$cpfdt,
                                                        'dadosLogin'=>array( 'login'=>$userwsmarka,
                                                                                'senha'=>$passwsmarka,
                                                                                'idmaquina'=>$cod_univend,
                                                                                'idloja'=>$cod_univend,
                                                                                'idcliente'=>$cod_empresa)));
        
   
        $options = array('location' => 'http://ws.bunker.mk');
        $result_consulta = $client_consulta->__soapCall($function, $arguments, $options);
        $datanascimento=$result_consulta->ConsultaCadastroPorCPFResult->datanascimento;      
        $sexo=$result_consulta->ConsultaCadastroPorCPFResult->sexo;
       //fim
        //buscar o resto dos dados na vtex
        $curl = curl_init();
       
        $urllimpo=str_replace("http://","",$urlwsdl);
        $urllimpo=str_replace(".vtexcommercestable.com.br/api/oms/pvt/orders","",$urllimpo);     
       
        curl_setopt_array($curl, array(
        CURLOPT_URL => "http://api.vtex.com/".$urllimpo."/dataentities/CL/search?_fields=_all&_where=(document=$cpfdt)",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_POSTFIELDS => "",
        CURLOPT_HTTPHEADER => array("Accept: application/json",
                                    "Content-Type: application/json",
                                    "X-VTEX-API-AppKey: $DES_USUARIO",
                                    "X-VTEX-API-AppToken: $DES_AUTHKEY",
                                    "cache-control: no-cache"
        ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
        echo "cURL Error #:" . $err;
        } else {
         unset($arraydados);   
        $arraydados=json_decode($response, true);

        }
       
        //
        unset($dados);   
        $dados=array(   'cartao'=> fnLimpaDoc($cpfdt),
                        'tipocliente'=>@$TipoPessoaId,
                        'nome'=> fnAcentos($arraydados[0]['firstName'].' '.$arraydados[0]['lastName']),
                        'cpf'=>fnLimpaDoc($cpfdt),
                        'sexo'=>@$sexo,
                        'email'=>$arraydados[0]['email'],
                        'telcelular'=>$arraydados[0]['homePhone'],
                        'cnpj'=>fnLimpaDoc($cpfdt),
                        'endereco'=> limitarTexto(fnAcentos($arrayList['shippingData']['address']['street']),99),
                        'numero'=> @limitarTexto($arrayList['shippingData']['address']['number'],10),
                        'complemento'=>fnAcentos($arrayList['shippingData']['address']['complement']),
                        'bairro'=>fnAcentos($arrayList['shippingData']['address']['neighborhood']),
                        'cidade'=>fnAcentos($arrayList['shippingData']['address']['city']),
                        'estado'=>fnAcentos($arrayList['shippingData']['address']['state']),
                        'cep'=>$arrayList['shippingData']['address']['postalCode'],
                        'datanascimento'=>date('Y-m-d', strtotime($datanascimento))
                    );
                                $dadoslogin=array( 'login'=>$userwsmarka,
                                                    'senha'=>$passwsmarka,
                                                    'idmaquina'=>'Ecomerce',
                                                    'idloja'=>$cod_univend,
                                                    'idcliente'=>$cod_empresa);
                            //insere cadastro na base de dados.
                           $retorno=atualiazacadastroMK($dados,$dadoslogin);
                           echo '--<br>'.$retorno.'--<br>';
                           echo '<pre>';
                           print_r($dados);
                            echo '</pre>';
                           //se o cadastro for OK agora vou inserir a venda e mudar os status no base de dados.
                           if($retorno=='OK')
                           {
                               // STATUS_PEDIDO='200' Venda OK
                               //aqui vou colocar o metodo para inserir a venda
                               
                                //Capturar os itens da venda 
                               unset($dados);
                               unset($item);
                                foreach ($arrayList['items'] as $key => $value) {
                                 
                                    $item[]=array(   'id_item'=>$value['id'],
                                                     'produto'=>$value['name'],
                                                     'codigoproduto'=>$value['sellerSku'],
                                                     'quantidade'=>$value['quantity'],
                                                     'valor'=>fnFormatvalor($value['price'], 2)
                                                );  
                                }
                                
                                $dados=array(   'id_vendapdv'=> $arrayList['orderId'],
                                                'datahora'=>date('Y-m-d H:i:s', strtotime($arrayList['creationDate'])),
                                                'cartao'=> fnLimpaDoc($cpfdt),
                                                'valortotal'=>fnFormatvalor($arrayList['totals']['0']['value'], 2),
                                                'cupom'=>$arrayList['sequence'],                                                
                                                'formapagamento'=>fnAcentos($arrayList['paymentData']['transactions']['0']['payments']['0']['paymentSystemName']),
                                                'codatendente'=>'',
                                                'codvendedor'=>'',
                                                'valor_resgate'=>'0,00',
                                                'items'=>$item                                   
                                        );
                                   
                            $dadoslogin=array( 'login'=>$userwsmarka,
                                                'senha'=>$passwsmarka,
                                                'idmaquina'=>$cod_univend,
                                                'idloja'=>$cod_univend,
                                                'idcliente'=>$cod_empresa);
                            $arrvendamarka=inserevendaMK($dados,$dadoslogin);
                            //echo '<pre>';
                            //print_r($arrvendamarka);
                            //echo '<pre>';
                            //echo '<pre>';
                            //print_r($dados);
                            //echo '<pre>';
                            
                           
                                if($arrvendamarka['msgerro']==='OK')
                                {
                                     $alterarpedido="UPDATE log_integration_venda_vtex 
                                              SET   COD_INSERT='2', 
                                                    STATUS_PEDIDO='200',
                                                    status_descricao='OK'
                                              WHERE  cod_empresa=$cod_empresa AND 
                                                     cod_ext_ven='$pedidoupdate';";
                           
                                     mysqli_query($conntemp, $alterarpedido);  
                                }elseif ($arrvendamarka['msgerro']===';o A soma dos itens não correspode ao valor total!') 
                                {
                                  $alterarpedido="UPDATE log_integration_venda_vtex 
                                              SET   COD_INSERT='2', 
                                                    STATUS_PEDIDO='201',
                                                    status_descricao='Valor divergente'
                                              WHERE  cod_empresa=$cod_empresa AND 
                                                     cod_ext_ven='$pedidoupdate';"; 
                                    mysqli_query($conntemp, $alterarpedido); 
                                } elseif ($arrvendamarka['msgerro']==='Oh não! Ja existe uma venda na mesma data e Horas! :(') 
                                {
                                    $alterarpedido="UPDATE log_integration_venda_vtex 
                                                 SET   COD_INSERT='2', 
                                                       STATUS_PEDIDO='202',
                                                       status_descricao='Venda ja existe'
                                                 WHERE  cod_empresa=$cod_empresa AND 
                                                        cod_ext_ven='$pedidoupdate';";
                                     mysqli_query($conntemp, $alterarpedido); 
                                }else{
                                     $alterarpedido="UPDATE log_integration_venda_vtex 
                                                 SET   COD_INSERT='2', 
                                                       STATUS_PEDIDO='203',
                                                       status_descricao='PDV ja existe'
                                                 WHERE  cod_empresa=$cod_empresa AND 
                                                        cod_ext_ven='$pedidoupdate';";
                                      mysqli_query($conntemp, $alterarpedido); 
                                }  
                           }    
                    }else{
                            echo 'Aguardando alteração nos status';
                    }  

            }
        }
}      