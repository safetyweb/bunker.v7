<?php
include_once '../../_system/_functionsMain.php';
$json='{"event":"onmessage","session":"362_97853","id":"false_5511999227646@c.us_3A145C07428E782B3091","viewed":false,"body":"Eu quero ser um cliente Clube Cash Farma!","type":"chat","t":1701783606,"notifyName":"Tiago Miranda","from":"5511999227646@c.us","to":"5511993611043@c.us","self":"in","ack":1,"invis":false,"isNewMsg":true,"star":false,"kicNotified":false,"recvFresh":true,"isFromTemplate":false,"thumbnail":"","pollInvalidated":false,"isSentCagPollCreation":false,"latestEditMsgKey":null,"latestEditSenderTimestampMs":null,"mentionedJidList":[],"groupMentions":[],"isVcardOverMmsDocument":false,"labels":[],"hasReaction":false,"productHeaderImageRejected":false,"lastPlaybackProgress":0,"isDynamicReplyButtonsMsg":false,"isMdHistoryMsg":false,"stickerSentTs":0,"isAvatar":false,"lastUpdateFromServerTs":0,"invokedBotWid":null,"bizBotType":null,"botResponseTargetId":null,"botPluginType":null,"botPluginReferenceIndex":null,"botPluginSearchProvider":null,"botPluginSearchUrl":null,"requiresDirectConnection":null,"chatId":"5511999227646@c.us","fromMe":false,"sender":{"id":"5511999227646@c.us","pushname":"Tiago Miranda","type":"in","privacyMode":null,"labels":[],"textStatusLastUpdateTime":-1,"formattedName":"+55 11 99922-7646","isMe":false,"isMyContact":false,"isPSA":false,"isUser":true,"isWAContact":true,"profilePicThumbObj":{},"msgs":null},"timestamp":1701783606,"content":"Eu quero ser um cliente Clube Cash Farma!","isGroupMsg":false,"mediaData":{}}';
$arrajson=json_decode($json,true);
echo '<pre>';
print_r($arrajson);
echo '</pre>';
 $empresas= explode("_", $arrajson[session]);  
 
 echo '<pre>';
print_r($empresas);
echo '</pre>';
$CHKEMPRESA="SELECT * FROM EMPRESAS WHERE COD_EMPRESA=".$empresas[0]." AND LOG_ATIVO='S'";
echo $CHKEMPRESA;
$chkemp= mysqli_query($connAdm->connAdm(), $CHKEMPRESA);
if($chkemp->num_rows >= '1')
{    
    //pegando o numero limpo
    $celular = explode("@", $arrajson['from']);    
    if (strlen(substr($celular[0], 2)) == 10) {
            // Separa os primeiros 2 dígitos (código de área)
            $codigo_area = substr(substr($celular[0], 2), 0, 2);

            // Separa os 9 dígitos restantes
            $parte_numerica = substr(substr($celular[0], 2), 2);

            // Formata o número com o "9" adicionado
            $celular = $codigo_area . '9' . $parte_numerica;
    }else{
        $celular=substr($celular[0], 2);
    }
    
    //capturando informações complementares do token
    $consultadadosextras="SELECT * from geratoken WHERE   NUM_celular='".$celular."'  and cod_empresa='".$empresas[0]."' ORDER BY COD_TOKEN DESC LIMIT 1";
    echo $consultadadosextras;
    $rsdadosxtras= mysqli_fetch_assoc(mysqli_query(connTemp($arrajson[session],''), $consultadadosextras));
    //verificar se o ultimo token ja foi enviar e nao ter ação 
    if($rsdadosxtras[LOG_USADO]=='1' && $rsdadosxtras[COD_EXCLUSA]=='0')
    { 
        //montar a chave de autenticação para o metodo WS
        $sqluser="SELECT LOG_USUARIO,DES_SENHAUS FROM usuarios  WHERE cod_empresa=".$rsdadosxtras['COD_EMPRESA']." AND cod_tpusuario=10";
        $usuarios= mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $sqluser)) ;

        $senha=$usuarios['LOG_USUARIO'].';'.fndecode($usuarios['DES_SENHAUS']).';'.$rsdadosxtras['COD_UNIVEND'].';webhook;'.$rsdadosxtras['COD_EMPRESA'];
        $autoriz= base64_encode(fnEncode($senha));
        
        echo '{
                                    "tipoGeracao": "1",
                                    "nome": "'.$arrajson['notifyName'].'",
                                    "cpf": "'.$rsdadosxtras['NUM_CGCECPF'].'",
                                    "celular": "",
                                    "email": "",
                                    "whatsapp": "'.$celular.'"
                                }';
        
        
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://soap.bunker.mk/api/Geratoken.do',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_SSL_VERIFYPEER => false,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>'{
                                    "tipoGeracao": "1",
                                    "nome": "'.$arrajson['notifyName'].'",
                                    "cpf": "'.$rsdadosxtras['NUM_CGCECPF'].'",
                                    "celular": "",
                                    "email": "",
                                    "whatsapp": "'.$celular.'"
                                }',
          CURLOPT_HTTPHEADER => array(
            'authorizationCode: '.$autoriz.'',
            'Content-Type: application/json'
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;
        
      
    }
}