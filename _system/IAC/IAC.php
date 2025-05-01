<?php
function fnChat($texto)
{
    
   $arg= '{
        "model": "gpt-4",
        "messages": [
                        {
                            "role": "user",
                            "content":"'.$texto.'"
                        }
                    ],
         "temperature": 0.7
            }';
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://api.openai.com/v1/chat/completions',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS =>$arg,
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json',
        'Authorization: Bearer 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'
      ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    return $response;
}


function fnChat_v2($system, $user) {
    $curl = curl_init();

    $data = array(
        "model" => "gpt-4",
        "messages" => array(
            array("role" => "system", "content" => $system),
            array("role" => "user", "content" => $user)
        ),
        "max_tokens" => 2000,
        "n" => 1,
        "temperature" => 0.7
    );

    $data_string = json_encode($data);

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.openai.com/v1/chat/completions',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $data_string,
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer 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',
            'Content-Type: application/json'
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);

    return $response;
}

?>