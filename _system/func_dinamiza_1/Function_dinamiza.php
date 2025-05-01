<?php
function autenticacao_dinamiza( $usuario,$senha,$client_code){

/*$teste='{"user":"'.$usuario.'",
								 "password":"'.$senha.'",
								 "client_code":"'.$client_code.'"}';
return $teste;*/								 

$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.dinamize.com/auth",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_SSL_VERIFYPEER=> false,
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS =>'{"user":"'.$usuario.'",
								 "password":"'.$senha.'",
								 "client_code":"'.$client_code.'"}',
		  CURLOPT_HTTPHEADER => array(
			"Content-Type: application/json; charset=utf-8"
		  ),
		));
		$err = curl_error($curl);
		$response = curl_exec($curl);

		curl_close($curl);
		if ($err) {
			  echo "cURL Error #:" . $err;
			} else {
			$connect=json_decode ($response,true); 
			}
    return $connect;
}

function contatos_dinamiza ($token,$file,$posicao_coluna,$contact_list_code)
{ 
		
		/*$teste=array('file'=> new CURLFILE($file),
			  'command' => 'import',
			  'parameters' => '{"contact-list_code": "'.$contact_list_code.'",
			  "separator": ";", 
			  "header": true, 
			  "file_columns": ['.$posicao_coluna.'] }');				  
		return	  $teste;*/
		
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.dinamize.com/emkt/contact",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_SSL_VERIFYPEER=> false,
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS => array('file'=> new CURLFILE($file),
		                              'command' => 'import',
									  'parameters' => '{"contact-list_code": "'.$contact_list_code.'", "separator": ";", "header": true, "file_columns": ['.$posicao_coluna.'] }'),
		  CURLOPT_HTTPHEADER => array(
			"Content-Type: multipart/form-data",
			"auth-token: $token"
		  ),
		));
		$response = curl_exec($curl);
		$err = curl_error($curl);
  //      $teste=curl_getinfo($curl, CURLINFO_HTTP_CODE);
		curl_close($curl);
        if ($err) {
			  $connect= "cURL Error #:" . $err;
			} else {
     			$connect=json_decode ($response,true); 
			}
    return $connect;
}
/*
contatos_dinamiza ("33303.328600.12.f1gh50PYqWt2ZoGCPAalu0YmcntnFT3zr0dExLDWIQO1SY7S", "77_20201018140301_0_-_TEST_CONTROLE.csv",
                                                    '{"Position":"0", "Field":"1", "Rule":"3"},
													 {"Position":"1", "Field":"14", "Rule":"3"},
                                                     {"Position":"2", "Field":"17", "Rule":"3"}'
													 );
echo '<pre>';
print_r( contatos_dinamiza ("33303.328600.20.fI7CTrA4cpzS+QURTTdTNl4z4dBQHsjrODNTHWJM8qYusF5w",
                           "lista_14102020.csv",
						  '{"Position":"0", "Field":"1", "Rule":"3"}, 
						   {"Position":"1", "Field":"2", "Rule":"3"}, 
						   {"Position":"2", "Field":"5", "Rule":"3"},
						   {"Position":"3", "Field":"3", "Rule":"3"},
						   {"Position":"4", "Field":"11", "Rule":"3"}'));

echo '</pre>';
*/
function FiltroSegmentos($token,$Nome_segmento,$cod_contatos,$contact_list_code){
	
/*	$teste="{\r\n    \"contact-list_code\": \"$contact_list_code\",\r\n    \"title\": \"$Nome_segmento\",\r\n    \"type\": \"AND\",\r\n    \"rule_list\": [\r\n        {\r\n            \"type\": \"E\",\r\n            \"reverse\": false,\r\n            \"rule\": {\r\n                \"event\": \"IMPORT\",\r\n                \"import\": \"$cod_contatos\"\r\n            }\r\n        }\r\n    ]\r\n}";
return $teste;

	*/
	$curl = curl_init();

	curl_setopt_array($curl, array(
	  CURLOPT_URL => "https://api.dinamize.com/emkt/filter/add",
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_SSL_VERIFYPEER=> false,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "POST",
	  CURLOPT_POSTFIELDS =>"{\r\n    \"contact-list_code\": \"$contact_list_code\",\r\n    \"title\": \"$Nome_segmento\",\r\n    \"type\": \"AND\",\r\n    \"rule_list\": [\r\n        {\r\n            \"type\": \"E\",\r\n            \"reverse\": false,\r\n            \"rule\": {\r\n                \"event\": \"IMPORT\",\r\n                \"import\": \"$cod_contatos\"\r\n            }\r\n        }\r\n    ]\r\n}",
	  CURLOPT_HTTPHEADER => array(
		"Content-Type: application/json; charset=utf-8",
		"auth-token: $token"
	  ),
	));
	$response = curl_exec($curl);
		$err = curl_error($curl);
  //      $teste=curl_getinfo($curl, CURLINFO_HTTP_CODE);
		curl_close($curl);
        if ($err) {
			  $connect= "cURL Error #:" . $err;
			} else {
     			$connect=json_decode ($response,true); 
			}
    return $connect;
}

// FiltroSegmentos($token,$cod_contatos);
function UpdateSegmento($token,$cod_import,$nome_segmento,$filter_code,$contact_list_code){

		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.dinamize.com/emkt/filter/update",
		  CURLOPT_RETURNTRANSFER => true,
	      CURLOPT_SSL_VERIFYPEER=> false,
	      CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS =>"{\r\n    \"contact-list_code\": \"$contact_list_code\",\r\n    \"filter_code\": \"$filter_code\",\r\n    \"title\": \"$nome_segmento\",\r\n    \"type\": \"AND\",\r\n    \"rule_list\": [\r\n        {\r\n            \"type\": \"E\",\r\n            \"reverse\": false,\r\n            \"rule\": {\r\n                \"event\": \"IMPORT\",\r\n                \"import\": \"$cod_import\"\r\n            }\r\n        }\r\n    ]\r\n}",
		  CURLOPT_HTTPHEADER => array(
			"Content-Type: application/json; charset=utf-8",
			"auth-token: $token"
		  ),
		));

	$response = curl_exec($curl);
		$err = curl_error($curl);
  //      $teste=curl_getinfo($curl, CURLINFO_HTTP_CODE);
		curl_close($curl);
        if ($err) {
			  $connect= "cURL Error #:" . $err;
			} else {
     			$connect=json_decode ($response,true); 
			}
    return $connect;
}
	
//UpdateSegmento($token,$cod_import,$nome_segmento,$filter_code);



function ListaSegmento($token,$filter_code,$contact_list_code){
/*
$teste='{"contact-list_code": "'.$contact_list_code.'","filter_code": "'.$filter_code.'"}';
return $teste;
*/
 			$curl = curl_init();

			curl_setopt_array($curl, array(
			  CURLOPT_URL => "https://api.dinamize.com/emkt/filter/get",
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_SSL_VERIFYPEER=> false,
	          CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 0,
			  CURLOPT_FOLLOWLOCATION => true,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "POST",
			  CURLOPT_POSTFIELDS =>'{"contact-list_code": "'.$contact_list_code.'","filter_code": "'.$filter_code.'"}',
			  CURLOPT_HTTPHEADER => array(
				"Content-Type: application/json; charset=utf-8",
				"auth-token: $token"
			  ),
			));

	$response = curl_exec($curl);
		$err = curl_error($curl);
  //      $teste=curl_getinfo($curl, CURLINFO_HTTP_CODE);
		curl_close($curl);
        if ($err) {
			  $connect= "cURL Error #:" . $err;
			} else {
     			$connect=json_decode ($response,true); 
			}
	return $connect;
}
function ListaSegmento2($token,$filter_code,$contact_list_code){

$vl='{
			"contact-list_code": "'.$contact_list_code.'",
			"page_number": "1",
			"page_size": "10",
			"search": [
				{
					"field": "title",
					"operator": "=",
					"value": "'.$filter_code.'"
				}
			]
		}';
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'https://api.dinamize.com/emkt/filter/search',
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_SSL_VERIFYPEER=> false,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'POST',
		  CURLOPT_POSTFIELDS =>$vl,
		  CURLOPT_HTTPHEADER => array(
			'Content-Type: application/json; charset=utf-8',
			"auth-token: $token"
		  ),
		));

	$response = curl_exec($curl);
		$err = curl_error($curl);
  //      $teste=curl_getinfo($curl, CURLINFO_HTTP_CODE);
		curl_close($curl);
        if ($err) {
			  $connect= "cURL Error #:" . $err;
			} else {
     			$connect=json_decode ($response,true); 
			}
	return $connect;
}
//ListaSegmento($token,$filter_code);

function AddVariavel($Nome,$Type,$token,$contact_list_code) 
{
    $curl = curl_init();

  curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.dinamize.com/emkt/field/add",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_SSL_VERIFYPEER=> false,
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS =>"{\r\n    \"contact-list_code\": \"$contact_list_code\",\r\n    \"title\": \"$Nome\",\r\n    \"is_required\": false,\r\n    \"type\": \"$Type\",\r\n    \"is_uniquevalue\": false,\r\n    \"is_searchable\": false\r\n}",
  CURLOPT_HTTPHEADER => array(
    "Content-Type: application/json; charset=utf-8",
    "auth-token: $token"
  ),
));

    $response = curl_exec($curl);
    $err = curl_error($curl);
  //
curl_close($curl);
  if ($err) {
    $connect= "cURL Error #:" . $err;
  } else {
  $connect=json_decode ($response,true); 
  }
  return $connect;

}
/*
    echo '<pre>';
    print_r(AddVariavel("<#TESTE@>",'VC',"33303.328600.12.f1gh50PYqWt2ZoGCPAalu0YmcntnFT3zr0dExLDWIQO1SY7S")) ;
    echo '</pre>';
	*/
function ListaVariavel($NomeVariavel,$token,$contact_list_code)
{
    
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://api.dinamize.com/emkt/field/search",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_SSL_VERIFYPEER=> false,
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS =>"{  \"contact-list_code\":\"$contact_list_code\",  \r\n   \"page_number\":\"1\", \r\n   \"page_size\":\"10\",  \r\n   \"search\": [{\"field\":\"title\", \r\n               \"operator\":\"=\", \r\n                \"value\":\"$NomeVariavel\"}],  \r\n                 \"order\": [{\"field\":\"title\", \r\n                             \"type\":\"DESC\"}]}",
      CURLOPT_HTTPHEADER => array(
        "Content-Type: application/json; charset=utf-8",
        "auth-token: $token"
      ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);
  //
curl_close($curl);
  if ($err) {
    $connect= "cURL Error #:" . $err;
  } else {
  $connect=json_decode ($response,true); 
  }
  return $connect;

}
/*
echo '<pre>';
print_r(ListaVariavel("<#CUPOMSORTEIO>","33303.328600.12.f1gh50PYqWt2ZoGCPAalu0YmcntnFT3zr0dExLDWIQO1SY7S"));
echo '</pre>';
*/

function AtualizaVariavel($token,$field_code,$title,$contact_list_code){
			$curl = curl_init();

			curl_setopt_array($curl, array(
			  CURLOPT_URL => "https://api.dinamize.com/emkt/field/update",
			  CURLOPT_RETURNTRANSFER => true,
	          CURLOPT_SSL_VERIFYPEER=> false,
    		  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 0,
			  CURLOPT_FOLLOWLOCATION => true,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "POST",
			  CURLOPT_POSTFIELDS =>"{\r\n    \"contact-list_code\": \"$contact_list_code\",\r\n    \"field_code\": \"$field_code\",\r\n    \"title\": \"$title\",\r\n    \"is_uniquevalue\": true,\r\n    \"is_searchable\": \"false\"\r\n}",
			  CURLOPT_HTTPHEADER => array(
				"Content-Type: application/json; charset=utf-8",
				"auth-token: $token"
			  ),
			));

			  $response = curl_exec($curl);
    $err = curl_error($curl);
  //
curl_close($curl);
  if ($err) {
    $connect= "cURL Error #:" . $err;
  } else {
  $connect=json_decode ($response,true); 
  }
  return $connect;
}	
/*
    echo '<pre>';
    print_r(ListaVariavel('<#NOME>',"33303.328600.20.Rx0KWv2XOTaczx5OZJ64yaM9ONOBYxt68hg0Ca7+S9ZwaKM5"));
    echo '</pre>';
*/
//add Campanha
function AddCampanha($token,$nomeCampanha)
{

	$curl = curl_init();

	curl_setopt_array($curl, array(
	  CURLOPT_URL => "https://api.dinamize.com/emkt/campaign/add",
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_SSL_VERIFYPEER=> false,
      CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "POST",
	  CURLOPT_POSTFIELDS =>"{  \"title\":\"$nomeCampanha\"}",
	  CURLOPT_HTTPHEADER => array(
		"Content-Type: application/json; charset=utf-8",
		"auth-token: $token"
	  ),
	));

	$response = curl_exec($curl);
    $err = curl_error($curl);
	curl_close($curl);
    if ($err) {
		$connect= "cURL Error #:" . $err;
	} else {
	  $connect=json_decode ($response,true); 
	}
	  return $connect;
	
}	
/*
 echo '<pre>';
 print_r(AddCampanha("33303.328600.14.4XlwHzRJXPmVqKEtnw6NwfELKpbyl/764FGLa2v0FJ+NjTPc",'LISTA_SEGMENTADA'));
 echo '</pre>';
*/
function AltualizaCampanha($token,$cod_externo,$Nome_Campanha)
{
	 $curl = curl_init();

	curl_setopt_array($curl, array(
	  CURLOPT_URL => "https://api.dinamize.com/emkt/campaign/update",
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_SSL_VERIFYPEER=> false,
      CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "POST",
	  CURLOPT_POSTFIELDS =>"{  \"campaign_code\":\"$cod_externo\",  \"title\":\"$Nome_Campanha\"}",
	  CURLOPT_HTTPHEADER => array(
		"Content-Type: application/json; charset=utf-8",
		"auth-token:$token"
	  ),
	));

	$response = curl_exec($curl);
    $err = curl_error($curl);
	curl_close($curl);
    if ($err) {
		$connect= "cURL Error #:" . $err;
	} else {
	  $connect=json_decode ($response,true); 
	}
	  return $connect;
}
/*
 echo '<pre>';
 print_r(AltualizaCampanha("33303.328600.13.01kCfgZBHUB7+Oc94cq8qf+1PgkjDR0lJymaFO573OEMZogU",'2','Diogo1'));
 echo '</pre>';
*/
function DeleteCampanha($token,$cod_externo)
{

	$curl = curl_init();

	curl_setopt_array($curl, array(
	  CURLOPT_URL => "https://api.dinamize.com/emkt/campaign/delete",
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_SSL_VERIFYPEER=> false,
      CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "POST",
	  CURLOPT_POSTFIELDS =>"{  \"campaign_code\":\"$cod_externo\"}",
	  CURLOPT_HTTPHEADER => array(
		"Content-Type: application/json; charset=utf-8",
		"auth-token: $token"
	  ),
	));

	$response = curl_exec($curl);
    $err = curl_error($curl);
	curl_close($curl);
    if ($err) {
		$connect= "cURL Error #:" . $err;
	} else {
	  $connect=json_decode ($response,true); 
	}
	  return $connect;
}
 /*
 echo '<pre>';
 print_r(DeleteCampanha("33303.328600.13.01kCfgZBHUB7+Oc94cq8qf+1PgkjDR0lJymaFO573OEMZogU","2"));
 echo '</pre>';
 */
function  ListaCampanha($token,$cod_externo)
{

		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'https://api.dinamize.com/emkt/campaign/search',
		  CURLOPT_RETURNTRANSFER => true,
		    CURLOPT_SSL_VERIFYPEER=> false,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'POST',
		  CURLOPT_POSTFIELDS =>'{
			"page_number": "1",
			"page_size": "10",
			"search": [
				{
					"field": "title",
					"operator": "=",
					"value": "$cod_externo"
				}
			],
			"order": [
				{
					"field": "title",
					"type": "DESC"
				}
			]
		}',
		  CURLOPT_HTTPHEADER => array(
			'Content-Type: application/json; charset=utf-8',
			"auth-token: $token"
		  ),
		));
		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);
		if ($err) {
			$connect= "cURL Error #:" . $err;
		} else {
		  $connect=json_decode ($response,true); 
		}
		  return $connect;
}	
 /*
 echo '<pre>';
 print_r(ListaCampanha("33303.328600.13.01kCfgZBHUB7+Oc94cq8qf+1PgkjDR0lJymaFO573OEMZogU","2"));
 echo '</pre>';
 */
 function AddHtml($token,$html,$titulo)
 {
	    $htmlenvio=json_encode($html, JSON_UNESCAPED_UNICODE);
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.dinamize.com/emkt/message/add",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_SSL_VERIFYPEER=> false,
     	  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS =>'{"title":"'.$titulo.'",
		                        "html":'.$htmlenvio.'}',
		  CURLOPT_HTTPHEADER => array(
			"Content-Type: application/json; charset=utf-8",
			"auth-token: $token"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);
		if ($err) {
			$connect= "cURL Error #:" . $err;
		} else {
		  $connect=json_decode ($response,true); 
		}
		  return $connect;
 }	 

/*
echo '<pre>';
print_r(AddHtml("33303.328600.14.Y3iRe+NJz2SxBUjRgoNH417oPSz+wjxjHjtKo1lpuuo0CK4Y",$html,"DIOGO_plataforma"));
 echo '</pre>';
*/ 
 function AtualizaHtml($token,$html,$titulo,$cod_externo)
 {
	   $htmlenvio=json_encode($html, JSON_UNESCAPED_UNICODE);
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.dinamize.com/emkt/message/update",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_SSL_VERIFYPEER=> false,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS =>'{"message_code":"'.$cod_externo.'", 
                                 "title":"'.$titulo.'",  
								 "html":'.$htmlenvio.'}',
		  CURLOPT_HTTPHEADER => array(
			"Content-Type: application/json; charset=utf-8",
			"auth-token: $token"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);
		if ($err) {
			$connect= "cURL Error #:" . $err;
		} else {
		  $connect=json_decode ($response,true); 
		}
		  return $connect;
 }
 
 /*echo '<pre>';
 print_r(AtualizaHtml("33303.328600.14.Y3iRe+NJz2SxBUjRgoNH417oPSz+wjxjHjtKo1lpuuo0CK4Y",
                    $html,
					"TESTE DINA1",
					"22")
					);
 echo '</pre>';
*/
 
 function delHtml($token,$cod_externo)
 {
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.dinamize.com/emkt/message/delete",
		  CURLOPT_RETURNTRANSFER => true,
	      CURLOPT_SSL_VERIFYPEER=> false,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS =>"{  \"message_code\":\"$cod_externo\"}",
		  CURLOPT_HTTPHEADER => array(
			"Content-Type: application/json; charset=utf-8",
			"auth-token: $token"
		  ),
		));
        
		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);
		if ($err) {
			$connect= "cURL Error #:" . $err;
		} else {
		  $connect=json_decode ($response,true); 
		}
		  return $connect;
	 
 }
/* 
 echo '<pre>';
 print_r(delHtml("33303.328600.15.cvtvmCp4cEbPLNYUe8e49hzNO73fTAFYox70qptisifF89No",'9'));
 echo '</pre>';
 */
 function ConsultaHtml($token,$cod_externo){

	$curl = curl_init();

	curl_setopt_array($curl, array(
	  CURLOPT_URL => "https://api.dinamize.com/emkt/message/get",
	  CURLOPT_RETURNTRANSFER => true,
      CURLOPT_SSL_VERIFYPEER=> false,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "POST",
	  CURLOPT_POSTFIELDS =>"{\r\n    \"message_code\": \"$cod_externo\"\r\n}",
	  CURLOPT_HTTPHEADER => array(
		"Content-Type: application/json; charset=utf-8",
		"auth-token: $token"
	  ),
	));

		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);
		if ($err) {
			$connect= "cURL Error #:" . $err;
		} else {
		  $connect=json_decode ($response,true); 
		}
		  return $connect;
	 
	 
 }	 
 function addenvioTESTE($token,$titulo,$cod_lista,$subject,$sender_name,$sender_email,$reply_to,$campaign_code,$filter_code,$message_code,$speed_envio)
{
	
	/*$teste='{  "title":"'.$titulo.'",
                    "contact-list_code":"'.$cod_lista.'",
                    "subject":"'.$subject.'", 
                    "sender_name":"'.$sender_name.'",
                    "sender_email":"'.$sender_email.'", 
                    "reply_to":"'.$reply_to.'",
                    "campaign_code":"'.$campaign_code.'",
                    "filter_code":"'.$filter_code.'",
                    "message_code":"'.$message_code.'",
                    "message_type":"CAD",
                    "send_speed":"'.$speed_envio.'",
                    "optout_progressMode":"DI"
                    }';
	return $teste;
	*/
 
	$curl = curl_init();

	curl_setopt_array($curl, array(
	  CURLOPT_URL => "https://api.dinamize.com/emkt/action/add",
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_SSL_VERIFYPEER=> false,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "POST",
	  CURLOPT_POSTFIELDS =>'{  "title":"'.$titulo.'",
	                           "contact-list_code":"'.$cod_lista.'",
							   "subject":"'.$subject.'", 
							   "sender_name":"'.$sender_name.'",
							   "sender_email":"'.$sender_email.'", 
                               "reply_to":"'.$reply_to.'",
							   "campaign_code":"'.$campaign_code.'",
							   "filter_code":"'.$filter_code.'",
							   "message_code":"'.$message_code.'",
							   "message_type":"CAD",
							   "send_speed":"'.$speed_envio.'",
							   "optout_progressMode":"DI"
							   }',
	  CURLOPT_HTTPHEADER => array(
		"Content-Type: application/json; charset=utf-8",
		"auth-token: $token"
	  ),
	));

	    $response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);
		if ($err) {
			$connect= "cURL Error #:" . $err;
		} else {
		  $connect=json_decode ($response,true); 
		}
		  return $connect;
	 
}
function Inicioteste($token,$email,$disparo)
{
 /*  $teste='{"action_code": "'.$disparo.'",
	                         "contact_list_data": ['.$email.']}';
	*/						 

	$curl = curl_init();

	curl_setopt_array($curl, array(
	  CURLOPT_URL => "https://api.dinamize.com/emkt/action/sendtest",
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_SSL_VERIFYPEER=> false,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "POST",
	  CURLOPT_POSTFIELDS =>'{"action_code": "'.$disparo.'",
	                         "contact_list_data": ['.$email.']}',
	  CURLOPT_HTTPHEADER => array(
		"Content-Type: application/json; charset=utf-8",
		"auth-token: $token"
	  ),
	));
        $response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);
		if ($err) {
			$connect= "cURL Error #:" . $err;
		} else {
		  $connect=json_decode ($response,true); 
		}
		  return $connect;
	
}										

function addenvio($token,$titulo,$cod_lista,$subject,$sender_name,$sender_email,$reply_to,$campaign_code,$filter_code,$message_code,$speed_envio,$date_send)
{
	/*
 $teste='{  "title":"'.$titulo.'",
		   "contact-list_code":"'.$cod_lista.'",
		   "subject":"'.$subject.'", 
		   "sender_name":"'.$sender_name.'",
		   "sender_email":"'.$sender_email.'", 
		   "reply_to":"'.$reply_to.'",
		   "campaign_code":"'.$campaign_code.'",
		   "filter_code":"'.$filter_code.'",
		   "message_code":"'.$message_code.'",
		   "message_type":"CAD",
		   "send_speed":"'.$speed_envio.'",
		   "date_send":"'.$date_send.'",
		   "optout_progressMode":"DI"
		   }';
return $teste;
*/
	$curl = curl_init();

	curl_setopt_array($curl, array(
	  CURLOPT_URL => "https://api.dinamize.com/emkt/action/add",
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_SSL_VERIFYPEER=> false,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "POST",
	  CURLOPT_POSTFIELDS =>'{  "title":"'.$titulo.'",
	                           "contact-list_code":"'.$cod_lista.'",
							   "subject":"'.$subject.'", 
							   "sender_name":"'.$sender_name.'",
							   "sender_email":"'.$sender_email.'", 
                               "reply_to":"'.$reply_to.'",
							   "campaign_code":"'.$campaign_code.'",
							   "filter_code":"'.$filter_code.'",
							   "message_code":"'.$message_code.'",
							   "message_type":"CAD",
							   "send_speed":"'.$speed_envio.'",
							   "date_send":"'.$date_send.'",
							   "optout_progressMode":"DI"
							   }',
	  CURLOPT_HTTPHEADER => array(
		"Content-Type: application/json; charset=utf-8",
		"auth-token: $token"
	  ),
	));

	    $response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);
		if ($err) {
			$connect= "cURL Error #:" . $err;
		} else {
		  $connect=json_decode ($response,true); 
		}
		  return $connect;
	 
}
/*
echo '<pre>';
print_r(addenvio('33303.328600.20.fI7CTrA4cpzS+QURTTdTNl4z4dBQHsjrODNTHWJM8qYusF5w',
                 '1',
				 'Função4',
				 'Função4',
				 'Campanha_segmentada',
				 'diogo@markafidelizacao.com.br',
				 'diogo@markafidelizacao.com.br',
				 '4',
				 '11',
				 '2020-10-14 23:10:00',
				 '13',
				 '2'));
echo '</pre>';
*/
function Updateenvio($token,$titulo,$subject,$sender_name,$sender_email,$reply_to,$campaign_code,$message_code,$date_send,$action_code,$speed_envio,$filter_code=False,$contact_list_code)
{
	$curl = curl_init();
	curl_setopt_array($curl, array(
	  CURLOPT_URL => "https://api.dinamize.com/emkt/action/update",
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_SSL_VERIFYPEER=> false,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "POST",
	  CURLOPT_POSTFIELDS =>'{   "action_code": "'.$action_code.'",
								"title": "'.$titulo.'",
								"contact-list_code": "'.$contact_list_code.'",
								"subject": "'.$subject.'",
								"sender_name": "'.$sender_name.'",
								"sender_email": "'.$sender_email.'",
								"reply_to": "'.$reply_to.'",
								"campaign_code": "'.$campaign_code.'",
								"filter_code": "'.$filter_code.'",
								"message_code": "'.$message_code.'",
								"message_type": "CAD",
								"send_speed": "'.$speed_envio.'",
								"date_send":"'.$date_send.'"
							   }',
	  CURLOPT_HTTPHEADER => array(
		"Content-Type: application/json; charset=utf-8",
		"auth-token: $token"
	  ),
	));

	    $response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);
		if ($err) {
			$connect= "cURL Error #:" . $err;
		} else {
		  $connect=json_decode ($response,true); 
		}
		  return $connect;
	 
}
/*
echo '<pre>';
print_r(Updateenvio('33303.328600.13.5+4/bBoy+g8o8Ue3hKD3EWdLsAzm4qU2L+zG64slcgBLTQjw',
                 'Função de envio com agendamento1',
				 'Função de envio com agendamento1',
				 'Campanha_teste1',
				 'diogo@markafidelizacao.com.br',
				 'diogo@markafidelizacao.com.br',
				 '3',
				 '11',
				 '2020-10-09 16:59:00',
				 '2372955'));
echo '</pre>';
*/
function CancelaEnvio ($token,$cod_envio)
{

	$curl = curl_init();

	curl_setopt_array($curl, array(
	  CURLOPT_URL => "https://api.dinamize.com/emkt/schedule/cancel",
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_SSL_VERIFYPEER=> false,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "POST",
	  CURLOPT_POSTFIELDS =>"{  \"action_code\":\"$cod_envio\"}",
	  CURLOPT_HTTPHEADER => array(
		"Content-Type: application/json; charset=utf-8",
		"auth-token: $token"
	  ),
	));

	$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);
		if ($err) {
			$connect= "cURL Error #:" . $err;
		} else {
		  $connect=json_decode ($response,true); 
		}
		  return $connect;
}
/*
echo '<pre>';
print_r(CancelaEnvio ("33303.328600.13.5+4/bBoy+g8o8Ue3hKD3EWdLsAzm4qU2L+zG64slcgBLTQjw",'2372955'));
echo '</pre>';
*/
function removeenvio($token,$cod_externo)
{

	$curl = curl_init();

	curl_setopt_array($curl, array(
	  CURLOPT_URL => "https://api.dinamize.com/emkt/action/delete",
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_SSL_VERIFYPEER=> false,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "POST",
	  CURLOPT_POSTFIELDS =>"{  \"action_code\":\"$cod_externo\"}",
	  CURLOPT_HTTPHEADER => array(
		"Content-Type: application/json; charset=utf-8",
		"auth-token: $token"
	  ),
	));

    	$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);
		if ($err) {
			$connect= "cURL Error #:" . $err;
		} else {
		  $connect=json_decode ($response,true); 
		}
		  return $connect;
}
/*
echo '<pre>';
print_r(removeenvio("33303.328600.13.5+4/bBoy+g8o8Ue3hKD3EWdLsAzm4qU2L+zG64slcgBLTQjw",'2372955'));
echo '</pre>';
*/

function ReportSumary($Token,$cod_disparo)
{		
	$curl = curl_init();
	curl_setopt_array($curl, array(
	  CURLOPT_URL => "https://api.dinamize.com/emkt/action/report",
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_SSL_VERIFYPEER=> false,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "POST",
	  CURLOPT_POSTFIELDS =>'{"action_code":"'.$cod_disparo.'",
	                         "type":"summary"}',
	  CURLOPT_HTTPHEADER => array(
		"Content-Type: application/json; charset=utf-8",
		"auth-token: $Token"
	  ),
	));
        $response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);
		if ($err) {
			$connect= "cURL Error #:" . $err;
		} else {
		  $connect=json_decode ($response,true); 
		}
		  return $connect;
	
}	
function relEntregue($TOKEN,$action_code,$paginacao,$DELIVER)
{
	$curl = curl_init();

	curl_setopt_array($curl, array(
	  CURLOPT_URL => 'https://api.dinamize.com/emkt/contact/search_from_action',
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_SSL_VERIFYPEER=> false,
	  CURLOPT_ENCODING => '',
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => 'POST',
	  CURLOPT_POSTFIELDS =>'{
		"action_code": "'.$action_code.'",
		"action_filter": "'.$DELIVER.'",
		"page_number": "'.$paginacao.'",
		"page_size": "10000"    
	}',
	  CURLOPT_HTTPHEADER => array(
		"Content-Type: application/json; charset=utf-8",
		"auth-token: $TOKEN"
	  ),
	));
	$response = curl_exec($curl);
	$err = curl_error($curl);
	curl_close($curl);
	if ($err) {
		$connect= "cURL Error #:" . $err;
	} else {
	  $connect=json_decode ($response,true); 
	}
	  return $connect;
}

function Reldetalhando ($token,$Cod_disparos,$dat_inicial,$dat_final,$page_number,$contact_list_code)
{
					 
/*
codigo Kind
4 - Participou de um envio de email
5 - Visualização de email
6 - Clique de email
7 - Optout de email
8 - Bounce de email
9 - Denuncia de spam do email
*/
/*
echo '{"contact-list_code":"'.$contact_list_code.'",
							 "date_start": "'.$dat_inicial.'",
							 "date_end": "'.$dat_final.'",
							  "kind_list": [
											4,
                                                                                        5,
											6,
											7,
											8,
											9
										],
							 "selection": ["'.$Cod_disparos.'"],
							 "page_number": "'.$page_number.'",
							 "page_size": "10000"}';
echo "auth-token:$token";*/
   	$curl = curl_init();
	curl_setopt_array($curl, array(
	  CURLOPT_URL => "https://api.dinamize.com/emkt/contact/history",
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_SSL_VERIFYPEER=> false,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "POST",
	  CURLOPT_POSTFIELDS =>'{"contact-list_code":"'.$contact_list_code.'",
							 "date_start": "'.$dat_inicial.'",
							 "date_end": "'.$dat_final.'",
							  "kind_list": [
                                                                                        4,
											5,
											6,
											7,
											8,
											9
										],
							 "selection": ["'.$Cod_disparos.'"],
							 "page_number": "'.$page_number.'",
							 "page_size": "500"}',
	  CURLOPT_HTTPHEADER => array(
		"Content-Type: application/json; charset=utf-8",
		"auth-token:$token"
	  ),
	));

	 $response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);
		if ($err) {
			$connect= "cURL Error #:" . $err;
		} else {
		  $connect=json_decode ($response,true); 
		}
		  return $connect;
}

function relimport($token,$cod_import){

		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'https://api.dinamize.com/emkt/import/get',
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_SSL_VERIFYPEER=> false,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 18000,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'POST',
		  CURLOPT_POSTFIELDS =>'{
									"code": "'.$cod_import.'"
								}',
		  CURLOPT_HTTPHEADER => array(
			"Content-Type: application/json; charset=utf-8",
			"auth-token: $token"
		  ),
		));

			 $response = curl_exec($curl);
				$err = curl_error($curl);
				curl_close($curl);
				if ($err) {
					$connect= "cURL Error #:" . $err;
				} else {
				  $connect=json_decode ($response,true); 
				}
				  return $connect;
}
/*echo '<pre>';
print_r(relimport('34038.328600.19.1FxEiD+YkXqgJuvvEMi/BqCpAGIXu9AitBxpk21ebcCMy72T','1873706'));
echo '</pre>';
	*/

function LinksHTML($token,$Id_disparo){
	
	
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.dinamize.com/emkt/url/search_from_action",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_SSL_VERIFYPEER=> false,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 1800,
		  CURLOPT_TIMEOUT => 1800,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS =>'{
									"action_code": "'.$Id_disparo.'"
								}',
		  
		  CURLOPT_HTTPHEADER => array(
			"Content-Type: application/json; charset=utf-8",
			"auth-token: $token"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);
		if ($err) {
			$connect= "cURL Error #:" . $err;
		} else {
		  $connect=json_decode ($response,true); 
		}
		  return $connect;

	
}
/*
echo '<pre>';
print_r(LinksHTML('34424.328892.9.lCi3dByI9HaRO58NYZVxV2jwVYgs22JO30fLu+Ez2vKjmejY','2506601'));
echo '</pre>';	
*/
function gerandorcvs($caminho,$nomeArquivo,$delimitador,$arraydados,$arrayheders)
{
	
        $arrayfull = array_merge($arrayheders,$arraydados); 
        $arquivo = fopen($caminho.$nomeArquivo, 'w',0);
    if(!$arquivo){
        throw new Exception('File open failed.');
    }else{ 
  
        foreach ($arrayfull as $linha )
		{			
			fputcsv ($arquivo,$linha,$delimitador,'"','\n');			
		}
	}
    fclose($arquivo);
	//remove linha em branco
	$lines = file($caminho.$nomeArquivo,FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
	$fp = fopen($caminho.$nomeArquivo, 'w'); 
	fwrite($fp, implode(PHP_EOL, $lines)); 
	fclose($fp);
	
	unset($arrayfull);
    unset($linha);
    unset($arrayheders);	
    unset($arraydados);
};
/*
include '../_functionsMain.php';

$sql="select  DES_EMAILUS as EMail,
              NOM_CLIENTE as Nome,
              NOM_CIDADEC as Cidade,
              COD_EMPRESA
              COD_CLIENTE from clientes limit 10"; 
$rwsql=mysqli_query(connTemp(77,''),$sql);
while($rssql=mysqli_fetch_assoc($rwsql))
{
    $dados[]=$rssql;
}
while($headers=mysqli_fetch_field($rwsql))
{
    $headers1[campos][$headers->name]=$headers->name; 
}
gerandorcvs("/srv/www/htdocs/_system/func_dinamiza/lista_envio/","lista1.csv",";",$dados,$headers1);
*/
 ?>