<?php 

	include '_system/_functionsMain.php'; 

	$num_celular = fnLimpaDoc($_POST['NUM_CELULAR']);
	$des_mensagem = $_POST['DES_MENSAGEM'];

	// fnEscreve($num_celular);


	$exec = curl_init();

	curl_setopt_array($exec, array(
	  CURLOPT_URL => 'https://api.easychat.tech/core/v2/api/chats/send-text',
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_SSL_VERIFYPEER=> false,
	  CURLOPT_ENCODING => '',
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => 'POST',
	  CURLOPT_POSTFIELDS =>'{
	  "forceSend": true,
	  "message": "'.$des_mensagem.'",
	  "number": "55'.$num_celular.'",
	  "verifyContact": true
	}',
	  CURLOPT_HTTPHEADER => array(
	    'access-token: 60b7fefa6739b9349ab43fd5',
	    'Content-Type: application/json',
	    'Accept: application/json'
	  ),
	));

	$response = curl_exec($exec);




	// curl_close($exec);
	// echo "<pre>";
	// print_r($response);
	// echo "</pre>";




	// $curl = curl_init();

	// curl_setopt_array($curl, array(
	//   CURLOPT_URL => 'https://api.easychat.tech/core/v2/api/chats/send-media',
	//   CURLOPT_RETURNTRANSFER => true,
	//   CURLOPT_SSL_VERIFYPEER=> false,
	//   CURLOPT_ENCODING => '',
	//   CURLOPT_MAXREDIRS => 10,
	//   CURLOPT_TIMEOUT => 0,
	//   CURLOPT_FOLLOWLOCATION => true,
	//   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	//   CURLOPT_CUSTOMREQUEST => 'POST',
	//   CURLOPT_POSTFIELDS =>'{
	//   "extension": ".jpg",
	//   "forceSend": true,
	//   "number": "55'.$num_celular.'",
	//   "verifyContact": true,
	//   "linkUrl": "https://images.focomultimidia.com/curl/motor_reserva/images/quarto/cliente_2957/20211105163612612306.JPG",
	//   "imageAsSticker": true,
	//   "fileName": "imagem-chale",
	//   "caption": "'.$des_mensagem.'"
	// }',
	//   CURLOPT_HTTPHEADER => array(
	//     'access-token: 60b7fefa6739b9349ab43fd5',
	//     'Content-Type: application/json',
	//     'Accept: application/json'
	//   ),
	// ));

	// $response = curl_exec($curl);

	curl_close($curl);
	echo "<pre>";
	print_r($response);
	echo "</pre>";


?>