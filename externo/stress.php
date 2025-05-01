<?php
set_time_limit(600);
echo "0: ok the operation was successful.<br>
2 : unable to get issuer certificate<br>
3: unable to get certificate CRL<br>
4: unable to decrypt certificate's signature<br>
5: unable to decrypt CRL's signature<br>
6: unable to decode issuer public key<br>
7: certificate signature failure<br>
8: CRL signature failure<br>
9: certificate is not yet valid<br>
10: certificate has expired<br>
11: CRL is not yet valid<br>
12:CRL has expired<br>
13: format error in certificate's notBefore field<br>
14: format error in certificate's notAfter field<br>
15: format error in CRL's lastUpdate field<br>
16: format error in CRL's nextUpdate field<br>
17: out of memory<br>
18: self signed certificate<br>
19: self signed certificate in certificate chain<br>
20: unable to get local issuer certificate<br>
21:unable to verify the first certificate<br>
22: certificate chain too long<br>
23: certificate revoked<br>
24: invalid CA certificate<br>
25: path length constraint exceeded<br>
26: unsupported certificate purpose<br>
27: certificate not trusted<br>
28: certificate rejected<br>
29: subject issuer mismatch<br>
30: authority and subject key identifier mismatch<br>
31: authority and issuer serial number mismatch<br>
32: key usage does not include certificate signing<br>
50: application verification failure<br>
details at http://www.openssl.org/docs/apps/verify.html#VERIFY_OPERATION<br>";
for ($x = 0; $x <= 100; $x++) {
     ob_start();
    sleep(3);
  $curl = curl_init();
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://adm.bunker.mk",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_FAILONERROR=> true,
      CURLOPT_NOBODY     => true,  
      CURLOPT_HEADER    => true,  
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 1800,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => "",
      CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache",
        "content-type: text/html",
        "postman-token: 2b0075e3-9bf1-91d8-ccf6-a519eeca3c33"
      ),
    ));
     $info = curl_getinfo($curl);
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    
   
    if ($err) {
      
      echo 'erro:'.$err;    
    } else {
       echo 'OK';
    }
    echo'<pre>';
    print_r($info);
    echo'</pre>';
    ob_end_flush();
    ob_flush();
    flush();
    
} 