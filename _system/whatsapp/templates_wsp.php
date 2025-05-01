<?php

	function template_wsp($search_text){

		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'https://api.easychat.tech/core/v2/api/action-cards/templates',
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'GET',
		  CURLOPT_HTTPHEADER => array(
		    'accept: application/json',
		    'access-token: 64c10c20fa1acc9fa2c1846c'
		  ),
		));

		$response = curl_exec($curl);

		curl_close($curl);

		$response = json_decode($response,true);

		$template = array_filter($response, function($el) use ($search_text) {
					    return ( strpos($el['id'], $search_text) !== false );
					});

		$template = call_user_func_array('array_merge', $template);
		$template = array_values($template);

		// echo $template[4][0][text];

		return addslashes($template[4][0][text]);


	}

	function busca_contatos_wsp($numero){

		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'https://api.easychat.tech/core/v2/api/contacts',
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'GET',
		  CURLOPT_HTTPHEADER => array(
		    'access-token: 64c10c20fa1acc9fa2c1846c',
		    'Accept: application/json'
		  ),
		));

		$response = curl_exec($curl);

		curl_close($curl);

		$response = json_decode($response,true);

		$contatos = array_filter($response, function($el) use ($numero) {
					    return ( strpos($el['number'], $search_text) !== false );
					});

		// $contatos = call_user_func_array('array_merge', $contatos);
		// $contatos = array_values($contatos);

		echo "<pre>";
		print_r($response);
		print_r($contatos);
		echo "</pre>";
		// return $contatos;

	}

	// echo template_wsp("64e34b59e15b5210bd285f80");

	busca_contatos_wsp("15981146246");

	// echo "<pre>";
	// print_r(busca_contatos_wsp("5515981146246"));
	// echo "</pre>";

?>