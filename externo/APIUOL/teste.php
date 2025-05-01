<?php
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://ws.sandbox.pagseguro.uol.com.br/v2/sessions?email=marcelo@markafidelizacao.com.br&token=19A6483822DC43B4A1B9AAB04DCFEFF0",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_HTTPHEADER => array(
    "Accept: */*",
    "Accept-Encoding: gzip, deflate",
    "Cache-Control: no-cache",
    "Connection: keep-alive",
    "Content-Length: ",
    "Host: ws.sandbox.pagseguro.uol.com.br",
    "Postman-Token: 759a8848-3239-4812-a581-af993ae2a2f1,294bc811-1ba6-41b3-b5da-729f3f0c18be",
    "User-Agent: PostmanRuntime/7.16.3",
    "cache-control: no-cache"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {

   $doc = new DOMDocument();
          libxml_use_internal_errors(true);
          $doc->loadHTML($response);
          libxml_clear_errors();
          $xml = $doc->saveXML($doc->documentElement);
          //$xml = simplexml_load_string($xml);
          $xml = simplexml_load_string($xml,'SimpleXMLElement',LIBXML_NOCDATA);
 $ID= json_decode(json_encode($xml),TRUE);
 
}
?>

<script type="text/javascript" src="https://stc.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js">
PagSeguroDirectPayment.setSessionId(<?php echo $ID['body']['session']['id'];?>);
Identificador=PagSeguroPayment.getSenderHash();
PagSeguroDirectPayment.getPaymentMethods({
	amount: 500.00,
	success: function(response) {
	   console.log(response.message);
        return false;
	},
	error: function(response) {
	       console.log(response.message);
        return false;
	},
	complete: function(response) {
	    console.log(response.message);
        return false;
	}
});
</script>



