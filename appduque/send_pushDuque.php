<?PHP
	function sendMessage(){
		$content = array(
			"en" => 'English Message'
			);
		
		$fields = array(
			'app_id' => "39a9aedc-8dd1-435f-8585-66a8d0e34528",
			'filters' => array(
                                           array("field" => "tag", "key" => "level", "relation" => ">", "value" => "6"),
                                           array("operator" => "OR"),
                                           array("field" => "amount_spent", "relation" => "=", "value" => "0")
                                     ),
			'data' => array("foo" => "bar"),
			'contents' => $content
		);
		
		$fields = json_encode($fields);
    	print("\nJSON sent:\n");
    	print($fields);
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
						           'Authorization: Basic ZDNhMzk1YTUtZTE0ZC00MWRkLWI5MTktYmIyOGQzMTY5ZjBk'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

		$response = curl_exec($ch);
		curl_close($ch);
		
		return $response;
	}
	
	$response = sendMessage();
	$return["allresponses"] = $response;
	$return = json_encode( $return);
	
	print("\n\nJSON received:\n");
	print($return);
	print("\n");

