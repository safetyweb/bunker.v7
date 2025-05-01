<?php

include "_system/_functionsMain.php";
$cod_empresa = fnLimpacampo($_POST['codEmpresa']);
$tipo = fnLimpacampo($_GET['tp']);
$manter = fnLimpacampo($_POST['manter']);

if(isset($_POST['CPF'])){
	$CPF = fnLimpacampo(fnLimpaDoc($_POST['CPF']));
	if($CPF == ""){
		$CPF = '0';
	}
}else{
	$CPF = 0;
}
if(isset($_POST['senha'])){
	$senha = fnEncode($_POST['senha']);
}else{
	$senha = "";
}

if($tipo == ""){

	$sql = "SELECT LOG_LGPD_LT FROM TOTEM_APP WHERE COD_EMPRESA = $cod_empresa";

	$arrayQuery = mysqli_query(connTemp($cod_empresa,''), $sql);
	$qrBuscaSiteTotemApp = mysqli_fetch_assoc($arrayQuery);

	$log_lgpd_lt = $qrBuscaSiteTotemApp["LOG_LGPD_LT"];


	$sqlControle = "SELECT LOG_LGPD FROM CONTROLE_TERMO WHERE COD_EMPRESA = $cod_empresa";

	$arrayControle = mysqli_query(connTemp($cod_empresa,''),$sqlControle);

	$qrCont = mysqli_fetch_assoc($arrayControle);

	$sql = "SELECT COD_CLIENTE, NUM_CGCECPF, LOG_TERMO, DES_SENHAUS FROM CLIENTES WHERE NUM_CGCECPF = '$CPF' AND DES_SENHAUS = '$senha' AND COD_EMPRESA = $cod_empresa LIMIT 1";

	$result = mysqli_query(connTemp($cod_empresa,''),trim($sql));	

	$linhas = mysqli_num_rows($result);
	  
	if($linhas != 0 ){
		$qrUs = mysqli_fetch_assoc($result);

		$log_termo = $qrUs['LOG_TERMO'];
		$cod_cliente = fnLimpaCampoZero($qrUs['COD_CLIENTE']);
		$usuEncrypt = fnEncode(fnLimpaDoc($qrUs['NUM_CGCECPF']));

        // VARIAVEIS SESSION
        $_SESSION['usuario'] = $qrUs['NUM_CGCECPF'];

		$loginEncrypt = base64_encode($usuEncrypt."|".$qrUs['DES_SENHAUS']);

        $_SESSION['idL'] = "";

		if($manter == "S"){
        	$_SESSION['idL'] = $loginEncrypt;
		}

		if($log_termo == 'S' || $qrCont[LOG_LGPD] == 'N'){
			// LOGIN COM ATUALIZACAO LGPD ACEITE SIMPLES
			echo 'LGPD,0,'.$usuEncrypt.','.$loginEncrypt;
		}else{
			if($log_lgpd_lt == "S"){
				// LOGIN NORMAL
				echo 'LGPD,1,'.$usuEncrypt.','.$loginEncrypt;
			}else{
				// LOGIN COM ATUALIZACAO LGPD ACEITE CADASTRO
				echo 'LGPD,2,'.$usuEncrypt.','.$loginEncrypt;
			}
		}

	}else{
		// USUARIO/SENHA INVALIDO(S)
		echo 0;
	}

}else if($tipo == "c"){
	$sql = "SELECT COUNT(COD_CLIENTE) AS QUANTIDADE FROM CLIENTES WHERE NUM_CGCECPF = '$CPF' AND COD_EMPRESA = $cod_empresa LIMIT 1";
	// echo($sql);

	$result = mysqli_query(connTemp($cod_empresa,''),trim($sql));	

	$qrResult = mysqli_fetch_assoc($result);
	$linhas = $qrResult['QUANTIDADE'];

	if($linhas > 0){
		$temCadastro = $linhas;
	}else{
		$temCadastro = 0;
	}

	// echo $linhas.','.$usuEncrypt.','.$loginEncrypt;

	echo $temCadastro;
}else{

	$sql = "SELECT COD_CLIENTE FROM CLIENTES WHERE NUM_CGCECPF = '$CPF' AND COD_EMPRESA = $cod_empresa LIMIT 1";

	$result = mysqli_query(connTemp($cod_empresa,''),trim($sql));	

	$linhas = mysqli_num_rows($result);

	echo $linhas.','.$usuEncrypt.','.$loginEncrypt;

	// echo $linhas;

}


?>


