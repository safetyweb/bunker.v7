<?php

include "_system/_functionsMain.php";

$server = $_SERVER['SERVER_NAME'];

if(isset($_REQUEST['email'])){
	$email = fnLimpacampo(trim($_REQUEST['email']));
}else{
	$email = "";
}
if(isset($_REQUEST['senha'])){
	$senha = fnEncode(trim($_REQUEST['senha']));
}else{
	$senha = "";
}



$cod_empresa = fnLimpaCampoZero($_REQUEST['COD_EMPRESA']);
$sql = "SELECT NUM_CARTAO FROM CLIENTES 
		WHERE (DES_EMAILUS = '$email' AND DES_EMAILUS != '' OR NUM_CGCECPF = '".fnLimpaDoc($email)."' AND NUM_CGCECPF != '') 
		AND DES_SENHAUS = '$senha' 
		AND COD_EMPRESA = $cod_empresa 
		LIMIT 1";

// fnEscreve($sql);

$result = mysqli_query(connTemp($cod_empresa,''),trim($sql)) or die(mysqli_error());	
$linhas = mysqli_num_rows($result);
// fnEscreve($linhas);
if($linhas == 1 ){
	$qrUs = mysqli_fetch_assoc($result);
	$_SESSION['login'] = "OK";
	$_SESSION["COD_RETORNO"] = $qrUs['NUM_CARTAO'];
	$cod_retorno = fnEncode($qrUs['NUM_CARTAO']);
        
                $_SESSION['keep']='on'; 
                $_SESSION['ativo']='checked';
                $_SESSION['login1']=$email;
                $_SESSION['senha']= $_REQUEST['senha'];
             
     			setcookie("login", $email,time()+3600*24*30*12);
			setcookie("senha", fnDecode($senha),time()+3600*24*30*12);
                        	$jsonusuario=array(
			                        "usuario"=> $email,
			                        "senha" => fnDecode($senha)
			                        );

			    setcookie("login_v2", base64_encode(fnEncode(json_encode($jsonusuario))),time()+3600*24*30*12);

			    $iPod = stripos($_SERVER['HTTP_USER_AGENT'],"iPod");
			    $iPhone = stripos($_SERVER['HTTP_USER_AGENT'],"iPhone");
			    $iPad = stripos($_SERVER['HTTP_USER_AGENT'],"iPad");

				if($server == 'adm.bunker.mk'){
			      $dominio = "adm.bunker.mk/appduque";
			    }else{
			      if($iPod || $iPhone || $iPad){
			        $dominio = $server."/appduque";
			      }else{
			      	if($server == "adm.bunkerapp.com.br"){
			      		$dominio = $server."/appduque";
			      	}else{
			      		$dominio = $server;
			      	}
			        
			      }

			    }


				echo "https://$dominio/novaHome.do?secur=$cod_retorno&log=1";
}else{

	echo $linhas;
}

?>


