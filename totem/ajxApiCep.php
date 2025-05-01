<?php 

include '../_system/_functionsMain.php'; 

$cod_empresa = fnLimpaCampoZero(fnDecode($_GET['id']));
$num_cepozof = fnLimpaCampo(fnLimpaDoc($_POST['CEP']));
$arrayCampos = json_decode(fnDecode($_POST['URL']),true);

$login = $arrayCampos[0];
$senha = fnEncode($arrayCampos[1]);
$cod_univend = $arrayCampos[2];
$cod_empresa = $arrayCampos[4];
$cod_players = $arrayCampos[7];

$content = "{
            \"login\": \"$login\",
            \"senha\": \"$senha\",
            \"idloja\": \"$cod_univend\",
            \"idmaquina\": \"CEP\",
            \"idcliente\": \"$cod_empresa\",
            \"CEP\": \"$num_cepozof\"
        	}";

if($num_cepozof != ""){

	$buscaCep=file_get_contents(
	                        "https://soap.bunker.mk/api/BuscaCep",
	                        false,
	                        stream_context_create(
	                            array(
	                                "http" => array(
	                                    "header"  => "Content-type: application/json; charset=ISO-8859-1",
	                                    "method"  => "POST",
	                                    "content" => $content
	                                ),
	                                'ssl' => array(
	                                    'verify_peer' => false,
	                                    'verify_peer_name' => false
	                                )
	                            )
	                        )
	                    );

	$arrCep =Utf8_ansi($buscaCep);

	// echo "<pre>";	
	// print_r($arrCep);
	// echo "</pre>";

	echo $arrCep;

}

// echo "<pre>";
// print_r($login);
// echo "</pre>";
// echo "<pre>";
// print_r($senha);
// echo "</pre>";
// echo "<pre>";
// print_r($cod_univend);
// echo "</pre>";
// echo "<pre>";
// print_r($cod_empresa);
// echo "</pre>";
// echo "<pre>";
// print_r($num_cepozof);
// echo "</pre>";

// echo "<pre>";
// print_r($arrayCampos);
// echo "</pre>";
	

?>