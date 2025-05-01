<?php 

include '_system/_functionsMain.php'; 

$cod_empresa = fnLimpaCampoZero(fnDecode($_GET['id']));
$num_cgcecpf = fnLimpaCampo(fnDecode($_POST['NUM_CGCECPF']));
$des_token = fnLimpaCampo($_POST['DES_TOKEN']);
$tipo_dado = fnLimpaCampo($_POST['TIPO_DADO']);
$cod_univend = fnLimpaCampoZero(fnDecode($_POST['COD_UNIVEND']));

if($tipo_dado != ""){

	$arrayDados = explode("|", $tipo_dado);
	$num_celular = fnDecode(end($arrayDados));
	$des_emailus = "";

	if($arrayDados[0] == "DES_EMAILUS"){
		$des_emailus = $num_celular;
		$num_celular = "";
	}

	$authCode = base64_encode($_GET['id']);

	if($cod_univend == 0){
		$cod_univend = "";
	}

	$options = [
            'http' => [
                'method' => 'POST',
                'timeout' => 10, // Timeout value in seconds
                 'header' => [
                                'Content-Type: application/json',
                                'authorizationCode: "'.$authCode.'"'
                            ],

                'content' =>  '{
								    "Cpf": "'.$num_cgcecpf.'",
								    "Telefone": "'.$num_celular.'",
								    "Email": "'.$des_emailus.'",
								    "Unidade": "'.$cod_univend.'",
								    "Token":"'.$des_token.'"
								}',
            ],
	];
	$context = stream_context_create($options);

	$response= file_get_contents("https://soap.bunker.mk/api/ResgateSenhaApp", false, $context);
	// if ($response !== false) {
	    $responseCode = explode(' ', $http_response_header[0])[1];
	    
	//     if ($responseCode == '200') {
	        
	//          echo  explode(' ', $http_response_header[0])[1];
	//     } else {
	//         // Handle non-200 response
	//         echo 'Error: HTTP ' . $responseCode;
	//     }
	// } else {
	//     // Error occurred

	//      echo explode(' ', $http_response_header[0])[1];
	// }

	if ($responseCode !='200' && $des_token == "") { 
	    echo 0;
	}else if($responseCode !='200' && $des_token != ""){
		echo 99;
	}else if($responseCode =='200' && $des_token == ""){
	    echo 1;
	}else{
		echo 2;
	}
// echo "<pre>";
// 	print_r($responseCode);
// echo "</pre>";

	// $curl = curl_init();
	// curl_setopt_array($curl, array(
	//   CURLOPT_URL => 'https://soap.bunker.mk/api/ResgateSenhaApp',
	//   CURLOPT_RETURNTRANSFER => true,
	//   CURLOPT_ENCODING => '',
	//   CURLOPT_MAXREDIRS => 10,
	//   CURLOPT_TIMEOUT => 0,
	//   CURLOPT_FOLLOWLOCATION => true,
	//   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	//   CURLOPT_CUSTOMREQUEST => 'POST',
	//   CURLOPT_POSTFIELDS =>'{
	//     "Cpf": "'.$num_cgcecpf.'",
	//     "Telefone": "'.$num_celular.'",
	//     "Email": "'.$des_emailus.'",
	//     "Token":"'.$des_token.'"
	// }',
	//   CURLOPT_HTTPHEADER => array(
	//     'authorizationCode: '.base64_encode($_GET['id']).'',
	//     'Content-Type: application/json'
	//   ),
	// ));

	// $response = curl_exec($curl);

	// $errorcode = curl_getinfo($curl);

	// echo "<pre>";
	// fnEscreve($tipo_dado);
	// fnEscreve($num_celular);
	// fnEscreve($num_cgcecpf);
	// print_r($response);
	// print_r($errorcode);
	// echo "</pre>";

	// if (@$errorcode[http_code]!='200' && $des_token == "") { 
	//     echo 0;
	// }else if(@$errorcode[http_code]!='200' && $des_token != ""){
	// 	echo 99;
	// }else if(@$errorcode[http_code]=='200' && $des_token == ""){
	//     echo 1;
	// }else{
	// 	echo 2;
	// }
	// curl_close($curl);


}

?>