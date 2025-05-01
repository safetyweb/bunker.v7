<?php
function EnvioSms($Usuario,$senha,$centro_custo_interno,$msg,$celular,$id_campanha=true)
{
    $url= 'https://sms.solucoesdigitais.cc/integracao/envio_transacional?usuario='.rawurlencode($Usuario).'&senha='.rawurlencode($senha).'&centro_custo_interno='.rawurlencode($centro_custo_interno).'&id_campanha='.rawurlencode($id_campanha).'&campanha='.rawurlencode($id_campanha).'&numero='.rawurlencode($celular).'&mensagem='.rawurlencode($msg).'&serial='.rawurlencode($id_campanha).'';
    //$teste=file_get_contents($url);
    //return $url;
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
       CURLOPT_SSL_VERIFYPEER=> false,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'GET',
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}
function EnvioSms_fast($KEY,$cod_campanha,$json,$tipoenvio='token')
{
   /* if($tipoenvio=='token')
    {
       $teste=' {
                                "tipo_envio": "'.$tipoenvio.'",
                                "referencia": "'.$cod_campanha.'",
                                "mensagens": '.$json.'
                            }';
       return  $teste;
    }*/
    
    $check_ping_resolve = ["sms.nexuscomunicacao.com:443:168.90.188.37"];
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://sms.nexuscomunicacao.com/api/sms/send.aspx?chave=".rawurlencode($KEY),				 	
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_SSL_VERIFYPEER=> false,
              CURLOPT_IPRESOLVE=> CURL_IPRESOLVE_V4,  
              CURLOPT_POSTREDIR=> CURL_REDIR_POST_ALL,
              CURLOPT_RESOLVE => $check_ping_resolve,
              CURLOPT_TCP_KEEPALIVE=> true,
              CURLOPT_TCP_KEEPIDLE => 600,
              CURLOPT_TCP_KEEPINTVL=>600,
              CURLOPT_ENCODING => 'deflate',
              CURLOPT_MAXREDIRS => 1000,
              CURLOPT_CONNECTTIMEOUT=> 1, // O número de segundos a aguardar ao tentar se conectar. Use 0 para aguardar indefinidamente. 
              CURLOPT_TIMEOUT => 10,  // O número máximo de segundos para permitir que funções cURL sejam executadas.               
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS =>'{
                                "tipo_envio": "'.$tipoenvio.'",
                                "referencia": "'.$cod_campanha.'",
                                "mensagens": '.$json.'
                            }',
      CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json; charset=utf-8"
      ),
    ));
    $response = curl_exec($curl);
    $err = curl_error($curl);
 // $teste=curl_getinfo($curl, CURLINFO_HTTP_CODE);		
    curl_close($curl);
    if ($err) {
          $connect= "cURL Error #:" . $err.'<BR>{
                                                        "tipo_envio": "'.$tipoenvio.'",
                                                        "referencia": "'.$cod_campanha.'",
                                                        "mensagens": '.$json.'
                                                    }';
          
    } else {
      $connect=json_decode ($response,true); 
    }
      return   $connect;
}

function EnvioSms_fast_file_get($KEY,$cod_campanha,$json,$tipoenvio='token')
{
    
    $Arra= '{   "tipo_envio": "'.$tipoenvio.'",
                "referencia": "'.$cod_campanha.'",
                "mensagens": '.$json.'
            }';

        $options = [
                    'http' => [
                        'method' => 'POST',
                         'header' => [
                                        'Content-Type: application/json'
                                    ],

                        'content' =>  $Arra,
                        'timeout' => 10, // Timeout value in seconds
                    ],
        ];
        $context = stream_context_create($options);

         // Make the request



        $response= file_get_contents("https://sms.nexuscomunicacao.com/api/sms/send.aspx?chave=".rawurlencode($KEY), false, $context);
        if ($response !== false) {
            $responseCode = explode(' ', $http_response_header[0])[1];

            if ($responseCode == '200') {

                  $connect=json_decode ($response,true); 
            } else {
                // Handle non-200 response
                $connect= "cURL Error #:" . $err.'<BR>{
                                                        "tipo_envio": "'.$tipoenvio.'",
                                                        "referencia": "'.$cod_campanha.'",
                                                        "mensagens": '.$json.'
                                                    }';
            }
        } else {
            // Error occurred

             $connect= "cURL Error #:" . $err.'<BR>{
                                                        "tipo_envio": "'.$tipoenvio.'",
                                                        "referencia": "'.$cod_campanha.'",
                                                        "mensagens": '.$json.'
                                                    }';
        } 
        return $connect;

}

function smsbrasilfone ($msg,$Authorization)
{

        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://apihttp.disparopro.com.br:8433/mt',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_SSL_VERIFYPEER=> false,  
          CURLOPT_ENCODING => '',
          CURLOPT_TCP_KEEPALIVE=> true,
          CURLOPT_TCP_KEEPIDLE => 600,
          CURLOPT_TCP_KEEPINTVL=>600,
          CURLOPT_ENCODING => 'deflate',
          CURLOPT_MAXREDIRS => 1000,
          CURLOPT_CONNECTTIMEOUT=> 1, // O número de segundos a aguardar ao tentar se conectar. Use 0 para aguardar indefinidamente. 
          CURLOPT_TIMEOUT => 10,  // O número máximo de segundos para permitir que funções cURL sejam executadas.   ,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>'['.$msg.']',
          CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer $Authorization",
            'Content-Type: application/json'
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
          curl_close($curl);
        if ($err) {
                  // printf("cUrl error (#%d): %s<br>\n",
                  // curl_errno($curl),
                  // htmlspecialchars(curl_error($curl)));
                   $connect= "cURL Error #:" . $err.'<BR>{
                                                            "tipo_envio": "'.$tipoenvio.'",
                                                            "referencia": "'.$cod_campanha.'",
                                                            "mensagens": '.$json.'
                                                        }';

            }else{
                 $connect=json_decode ($response,true);   
            }  

      return   $connect;
    
}
/*
@$msg='{
            "numero": "5548996243831",
            "servico": "otp",
            "mensagem": "maurice e rone -MARKA - Nossos valores estão cada vez mais em prol de qualidade e performance technologica para a satisfação dos clientes.",
            "parceiro_id": "219||32||campanha_teste_sms",
            "codificacao": "0"
          }';

echo "<pre>";
print_r(smsbrasilfone ($msg,'d63ed41de8ed18f4130397038b080896ba13d671'));
echo "</pre>";
 * 
 */
function sms_twilo($base64,$dadosenvio,$username,$password)
{
    
    $url = 'https://api.twilio.com/2010-04-01/Accounts/'.$username.'/Messages.json';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);    
    foreach ($dadosenvio as  $value){
        
        $data = array(
                        'To' => $value['to'],
                        'From' => $value['from'],
                        'Body' => $value['mensagem'],                        
                        'StatusCallback' => 'http://externo.bunker.mk/twilo/twl?ID='.$value[Codigointerno]
                        );

        if (isset($value['DataAgendamento'])){
          //Tem data de agendamento

          // Criar um objeto DateTime com a data e hora do agendamento
          $dateTime = new DateTime($value['DataAgendamento'], new DateTimeZone('America/Sao_Paulo'));

          // Criar um objeto DateTime para a data e hora atual
          $dateTimeAtual = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
          // Adicionar 15 minutos ao objeto DateTime
          $dateTimeAtual->modify('+15 minutes');

          if ($dateTime > $dateTimeAtual){
            // Se a data do agendamento é futura, então adiciona o parametro de agendamento]
            $dateTime->add(new DateInterval('PT3H'));

            // Formatar a data no formato ISO 8601
            $dateString = $dateTime->format('Y-m-d\TH:i:s\Z');
            //$data['SendAt'] = $dateString;
            $data['MessagingServiceSid'] = 'MG678b59072506c71129a4dda044bea97b';
            //$data['ScheduleType'] = 'fixed';

            /*
            Retirado os parâmetros "SendAt" e "ScheduleType" devido a mensagens enviada pela Twillo (abaixo)
            *******************************************************************************************************
            * What do you need to do?                                                                             *
            *                                                                                                     *
            * If you don’t want to be charged, stop using Engagement Suite features below by March 31, 2024:      *
            *                                                                                                     *
            * For Message Scheduling: don’t include the "ScheduleType" and "SendAt" fields in the API request.    *
            * For Link Shortening: don’t include “shortenUrls” field in the API request                           *
            *******************************************************************************************************
            */
          }
        }

       

       
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_USERPWD, $username . ':' . $password);
    
    
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $dadostwiloret =json_decode($response,true);
        $dadostwiloret['COD_CLIENTE'] =$value['COD_CLIENTE'];
        $ret[] = $dadostwiloret;

        //print_r($ret);
        //print_r($data);exit;
       // $ret[]= json_decode($response,true);
    }
      curl_close($ch);
      return $ret;
}

function fnenviosms($array)
{
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_URL, $array['URL']);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_ENCODING, "utf-8");
        curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $array['METHOD']);
         // Configurando o proxy
        $proxy = 'http://p.webshare.io:80'; // Substitua pelo seu proxy
        curl_setopt($curl, CURLOPT_PROXY, $proxy);

        // Se o proxy requer autenticação, adicione estas linhas:
         $proxyUserPwd = 'ihakjfrv-rotate:haqrvntza9gf'; // Substitua por suas credenciais
         curl_setopt($curl, CURLOPT_PROXYUSERPWD, $proxyUserPwd);
        //twillo
       if($array['PROVEDOR']==22)    
       {
        
            foreach ($array['SEND'] as  $value){
                unset($data);
                $data = array(
                        'To' => $value['To'],
                        'From' => $value['From'],
                        'Body' => $value['Body'],                        
                        'StatusCallback' => 'http://externo.bunker.mk/twilo/twl_v2?ID='.$array['COD_EMPRESA'].'_'.$value['COD_CLIENTE']
                        );

                
              /*  curl_setopt($curl, CURLOPT_POSTFIELDS,  http_build_query([
                                                                            'Body'=> $value['Body'],
                                                                            'From' => $value['From'],
                                                                            'statusCallback'=>"http://externo.bunker.mk/twilo/twl_v2?ID=".$array['COD_EMPRESA'].'_'.$value['COD_CLIENTE'],
                                                                            'To' => $value['To']
                                                                        ]));*/
               curl_setopt($curl, CURLOPT_POSTFIELDS,  http_build_query($data)); 
                curl_setopt($curl, CURLOPT_HTTPHEADER,  array(
                                                             'Content-Type: application/x-www-form-urlencoded; charset=utf-8',
                                                             'Authorization: '.$array['Authorization']
                                                           ));
                 $response = curl_exec($curl);
                 $err = curl_error($curl);

                if ($err) {
                   echo "cURL Error #:" . $err;
                } else {
                    $dadostwiloret =json_decode($response,true);
                    $ret[] = array('account_sid'=>$dadostwiloret['account_sid'],
                                   'body'=>$value['Body'],
                                   'sid'=>$dadostwiloret['sid'],
                                   'status'=>$dadostwiloret['status'],
                                   'to'=>$value['To'],
                                   'cod_cliente'=>$value['COD_CLIENTE'],
                                   'cod_empresa'=>$array['COD_EMPRESA'],
                                    'player'=>$array['PROVEDOR']
                                   );
                }
            }
        } elseif ($array['PROVEDOR']==24) {
            
            foreach ($array['SEND'] as  $value){
            // Dados JSON para enviar na requisição POST
                        $data = json_encode(array(
                            "from" => $value['From'], //"coordenacaoti3",
                            "to" => $value['To'],//"5516997970129",
                            "contents" => array(
                                array(
                                    "type" => "text",
                                    "text" => $value['Body']
                                )
                            )
                        ));

                        // Configurando os campos POST
                        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                        // Cabeçalhos HTTP
                        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                            'Content-Type: application/json',
                            'X-API-Token:'.$array['Authorization'] //TAEUDHLVQsl80ihW73P0zFfF56tlbqwtagWn'
                        ));

                 $response = curl_exec($curl);
                 $err = curl_error($curl);

                if ($err) {
                   echo "cURL Error #:" . $err;
                } else {
                    $dadostwiloret =json_decode($response,true);
                    $ret[] = array('account_sid'=>$dadostwiloret['id'],
                                   'body'=>$value['Body'],
                                   'sid'=>$dadostwiloret['id'],
                                   'status'=>'queued',
                                   'to'=>$value['To'],
                                   'cod_cliente'=>$value['COD_CLIENTE'],
                                   'cod_empresa'=>$array['COD_EMPRESA'],
                                   'player'=>$array['PROVEDOR']
                                   );
                    
                    
                }
            }
        }elseif ($array['PROVEDOR']==23) {
            
           $jsonsend = ["messages" => []];

            foreach ($array['SEND'] as $key => $value) {
                if (isset($value['To']) && !empty($value['To'])) {
                    $jsonsend["messages"][] = [
                        "destination" => str_replace('+', '', $value['To']),
                        "messageText" => $value['Body'],
                        "correlationId" => $array['COD_EMPRESA'] . '_' . $value['COD_CLIENTE'],
                        "extraInfo" => $array['COD_EMPRESA'] . '_' . $value['COD_CLIENTE']
                    ];
                } else {
                    echo "Erro: 'To' não está definido ou está vazio para um dos itens.\n";
                }
            }

            $data = json_encode($jsonsend, JSON_PRETTY_PRINT);

            // Configurando os campos POST
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

            // Cabeçalhos HTTP
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data),
                'authenticationtoken:' . $array['Authorization'],
                'username:' . $array['Usuario']
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);
            if ($err) {
                echo "cURL Error #:" . $err;
            } else {
                $dadostwiloret = json_decode($response, true);
                $ret = array();

                // Iterando sobre a resposta para formatar o novo array
                foreach ($dadostwiloret['messages'] as $index => $message) {
                    // Dividindo o correlationId em cod_empresa e cod_cliente
                    list($cod_empresa, $cod_cliente) = explode('_', $message['correlationId']);

                    // Construindo o novo array
                    $ret[] = array(
                                        'account_sid' => $message['id'],
                                        'body' => $jsonsend["messages"][$index]['messageText'], // Usando o corpo da mensagem original
                                        'sid' => $message['id'],
                                        'status' => 'queued', // Supondo que o status seja fixo
                                        'to' => $jsonsend["messages"][$index]['destination'], // Usando o destino original
                                        'cod_cliente' => $cod_cliente,
                                        'cod_empresa' => $cod_empresa,
                                        'player' => $array['PROVEDOR'] // Supondo que o player seja fixo
                                    );
                }
            }
        }
        
      curl_close($curl);
      return $ret;
}
?>