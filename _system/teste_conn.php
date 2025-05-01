<?php
/*$con=mysqli_init();
if (!$con)
  {
  die("mysqli_init failed");
  }

if (!mysqli_real_connect($con,"127.0.0.1","adminterno","H+admin29.5","webtools",NULL, MYSQLI_CLIENT_COMPRESS))
  {
  die("Connect Error: " . mysqli_connect_error());
  }
  
mysqli_options($con, MYSQLI_OPT_CONNECT_TIMEOUT, 120);  
  echo '<pre>';
print_r($con);
echo '<pre>';
 $sql = "select * from log order by ID desc limit 10";
		//fnEscreve($sql);
		$arrayQuery = mysqli_query($con,$sql) or die(mysqli_error());
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery); 
  
                print_r($con);
 
 $conn2=mysqli_connect('127.0.0.1','adminterno','H+admin29.5','webtools');
 echo 'CONN2';
 echo '<pre>';
 mysqli_options($conn2, MYSQLI_CLIENT_COMPRESS); 
 print_r($conn2);
 echo '<pre>';
 
mysqli_close($con);
//$connAdm = new BD('127.0.0.1','adminterno','H+admin29.5','webtools');
//$connREL = new BD('144.217.255.136','adminterno','H+admin29.5','db_host1');


///curl
*/

  $ch = curl_init();
// informar URL e outras funções ao CURL
//https://account.sendinblue.com/users/make-login  
//curl_setopt($ch, CURLOPT_URL, "https://pt.sendinblue.com/users/login/");
curl_setopt($ch, CURLOPT_URL, "https://account.sendinblue.com/users/make-login");
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
// Faz um POST
$data = array('email' => 'ricardolara.ti@gmail.com', 'pass' => 'teste123456','g-recaptcha-response'=>'6LfQtx0UAAAAAF-06Js5kOgM68rYcdtu0Q79VUMI');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookie.txt');

curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt ($ch, CURLOPT_HTTPGET, 1);
curl_setopt($ch, CURLOPT_VERBOSE, 5);

// Acessar a URL e imprimir a saída
$exec=curl_exec($ch);

echo '<h3>Variáveis que eu recebi: </h3>';
//echo '<pre>';
//echo $exec;
//echo '</pre>';
curl_close($ch)


?>