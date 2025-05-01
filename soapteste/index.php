<?php
 @$wsdl=$_REQUEST['wsdl'];
    @$message=$_REQUEST['message']; 

?>
 
<form action="index.php" method="POST">
Links<select name="wsdl">
            <option value="http://soap.bunker.mk/?wsdl">.....................</option>
            <option value="http://soap.bunker.mk/?wsdl">http://soap.bunker.mk/?wsdl</option>
            <option value="http://ws.bunker.mk/?wsdl">http://ws.bunker.mk/?wsdl</option>
            <option value="http://ws.bunker.mk/bridge/ws2/fidelidadebridge.do?wsdl">http://ws.bunker.mk/bridge/ws2/fidelidadebridge.do?wsdl</option>
               <option value="http://ws.bunker.mk/bridge/ws1/fidelidadebridge.do?wsdl">http://ws.bunker.mk/bridge/ws1/fidelidadebridge.do?wsdl</option>
        </select><br>
XML: <textarea name="message" rows="10" cols="30"><?php echo html_entity_decode($message, ENT_QUOTES | ENT_XML1, 'UTF-8');?></textarea><br>
  <input type="submit" value="Submit">
</form>
<?php
if( $_SERVER['REQUEST_METHOD']=='POST' )
{
   
    
      $curl = curl_init();
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt_array($curl, array(
      CURLOPT_URL => "$wsdl",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => "$message",
      CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache",
        "content-type: text/xml",
        "charset=utf-8",  
        
      ),
    ));
 
    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
      $response1= "cURL Error #:" . $err;
    } else {
    $response1= html_entity_decode($response, ENT_XML1, 'UTF-8');
  
 
    }
    
    
   
}?>
<textarea name="message" rows="10" cols="30">
<?php 

 echo $response1;
  
?>
</textarea>	
